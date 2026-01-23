<?php

namespace App\Http\Controllers;

use App\Models\GuestSpeaker;
use App\Models\PrayerRequest;
use App\Models\ProgramSchedule;
use App\Models\Registration;
use App\Models\WhatsAppSession;
use App\Services\PaynowService;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class WhatsAppWebHookController extends Controller
{
    protected WhatsAppService $whatsApp;
    protected PaynowService $paynow;

    public function __construct(WhatsAppService $whatsApp, PaynowService $paynow)
    {
        $this->whatsApp = $whatsApp;
        $this->paynow = $paynow;
    }

    /**
     * Verify webhook (GET request from Meta)
     */
    public function verify(Request $request): mixed
    {
        $mode = $request->get('hub_mode');
        $token = $request->get('hub_verify_token');
        $challenge = $request->get('hub_challenge');

        Log::info('WhatsApp webhook verification attempt', [
            'mode' => $mode,
            'token' => $token,
            'expected_token' => config('whatsapp.verify_token'),
        ]);

        if ($mode === 'subscribe' && $token === config('whatsapp.verify_token')) {
            Log::info('WhatsApp webhook verified successfully');
            return response($challenge, 200)->header('Content-Type', 'text/plain');
        }

        Log::warning('WhatsApp webhook verification failed');
        return response('Forbidden', 403);
    }

    /**
     * Handle incoming webhook (POST request from Meta)
     */
    public function handle(Request $request): JsonResponse
    {
        $data = $request->all();
        Log::info('WhatsApp webhook received', ['data' => $data]);

        try {
            // Extract message data
            $entry = $data['entry'][0] ?? null;
            if (!$entry) {
                return response()->json(['status' => 'ok']);
            }

            $changes = $entry['changes'][0] ?? null;
            if (!$changes || ($changes['field'] ?? '') !== 'messages') {
                return response()->json(['status' => 'ok']);
            }

            $value = $changes['value'] ?? [];
            $messages = $value['messages'] ?? [];
            $contacts = $value['contacts'] ?? [];

            if (empty($messages)) {
                // Could be a status update, flow response, etc.
                $this->handleNonMessageEvent($value);
                return response()->json(['status' => 'ok']);
            }

            foreach ($messages as $message) {
                $this->processMessage($message, $contacts);
            }
        } catch (\Exception $e) {
            Log::error('WhatsApp webhook processing error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }

        return response()->json(['status' => 'ok']);
    }

    /**
     * Process a single message
     */
    protected function processMessage(array $message, array $contacts): void
    {
        $from = $message['from'] ?? null;
        $messageId = $message['id'] ?? null;
        $messageType = $message['type'] ?? 'unknown';

        if (!$from) {
            return;
        }

        // Get contact name
        $contactName = 'User';
        foreach ($contacts as $contact) {
            if (($contact['wa_id'] ?? '') === $from) {
                $contactName = $contact['profile']['name'] ?? 'User';
                break;
            }
        }

        // Mark as read
        if ($messageId) {
            $this->whatsApp->markAsRead($messageId);
        }

        // Get or create session
        $session = WhatsAppSession::getOrCreate($from);

        // Check for session expiry and reset if needed
        if ($session->hasExpired()) {
            $session->reset();
        }

        // Handle different message types
        switch ($messageType) {
            case 'text':
                $text = $message['text']['body'] ?? '';
                $this->handleTextMessage($from, $text, $session, $contactName);
                break;

            case 'interactive':
                $this->handleInteractiveMessage($from, $message['interactive'], $session, $contactName);
                break;

            case 'button':
                $buttonText = $message['button']['text'] ?? '';
                $this->handleTextMessage($from, $buttonText, $session, $contactName);
                break;

            default:
                $this->whatsApp->sendMessage($from, "Sorry, I can only process text messages and button selections. Please type 'menu' to see available options.");
                break;
        }
    }

    /**
     * Handle text messages
     */
    protected function handleTextMessage(string $from, string $text, WhatsAppSession $session, string $contactName): void
    {
        $text = trim(strtolower($text));
        $state = $session->current_state;

        Log::info('Processing text message', [
            'from' => $from,
            'text' => $text,
            'state' => $state,
        ]);

        // Global commands that work from any state
        if (in_array($text, ['menu', 'hi', 'hello', 'start', 'home', '0', 'help'])) {
            $session->reset();
            $this->whatsApp->sendMainMenu($from);
            return;
        }

        // State-based handling
        switch ($state) {
            case 'main_menu':
                $this->handleMainMenuInput($from, $text, $session, $contactName);
                break;

            case 'awaiting_registration_type':
                $this->handleRegistrationTypeInput($from, $text, $session);
                break;

            case 'awaiting_id_number':
                $this->handleIdNumberInput($from, $text, $session);
                break;

            case 'awaiting_prayer_request':
                $this->handlePrayerRequestInput($from, $text, $session, $contactName);
                break;

            case 'registration_name':
                $this->handleRegistrationName($from, $text, $session);
                break;

            case 'registration_university':
                $this->handleRegistrationUniversity($from, $text, $session);
                break;

            case 'registration_email':
                $this->handleRegistrationEmail($from, $text, $session);
                break;

            case 'registration_id_number':
                $this->handleRegistrationIdNumber($from, $text, $session);
                break;

            case 'registration_gender':
                $this->handleRegistrationGender($from, $text, $session);
                break;

            case 'registration_level':
                $this->handleRegistrationLevel($from, $text, $session);
                break;

            case 'registration_confirm':
                $this->handleRegistrationConfirm($from, $text, $session);
                break;

            case 'awaiting_payment_method':
                $this->handlePaymentMethodInput($from, $text, $session);
                break;

            case 'awaiting_payment_phone':
                $this->handlePaymentPhoneInput($from, $text, $session);
                break;

            default:
                $session->reset();
                $this->whatsApp->sendMainMenu($from);
                break;
        }
    }

    /**
     * Handle interactive messages (list selections, button replies)
     */
    protected function handleInteractiveMessage(string $from, array $interactive, WhatsAppSession $session, string $contactName): void
    {
        $type = $interactive['type'] ?? '';

        if ($type === 'list_reply') {
            $selectedId = $interactive['list_reply']['id'] ?? '';
            $this->handleMenuSelection($from, $selectedId, $session, $contactName);
        } elseif ($type === 'button_reply') {
            $selectedId = $interactive['button_reply']['id'] ?? '';
            $this->handleButtonSelection($from, $selectedId, $session, $contactName);
        } elseif ($type === 'nfm_reply') {
            // Flow response
            $responseJson = $interactive['nfm_reply']['response_json'] ?? '{}';
            $this->handleFlowResponse($from, json_decode($responseJson, true), $session);
        }
    }

    /**
     * Handle main menu text input
     */
    protected function handleMainMenuInput(string $from, string $text, WhatsAppSession $session, string $contactName): void
    {
        // Map text inputs to menu options
        $mappings = [
            '1' => 'register',
            'register' => 'register',
            'registration' => 'register',
            '2' => 'check_registration',
            'check' => 'check_registration',
            'status' => 'check_registration',
            '3' => 'today_schedule',
            'today' => 'today_schedule',
            '4' => 'full_schedule',
            'schedule' => 'full_schedule',
            'program' => 'full_schedule',
            '5' => 'guest_speakers',
            'speakers' => 'guest_speakers',
            '6' => 'prayer_request',
            'prayer' => 'prayer_request',
            'pray' => 'prayer_request',
            '7' => 'qas',
            'qa' => 'qas',
            'question' => 'qas',
        ];

        $option = $mappings[$text] ?? null;

        if ($option) {
            $this->handleMenuSelection($from, $option, $session, $contactName);
        } else {
            $this->whatsApp->sendMainMenu($from);
        }
    }

    /**
     * Handle menu selection
     */
    protected function handleMenuSelection(string $from, string $selectedId, WhatsAppSession $session, string $contactName): void
    {
        Log::info('Menu selection', ['from' => $from, 'selection' => $selectedId]);

        switch ($selectedId) {
            case 'register':
                $session->setState('awaiting_registration_type');
                $this->whatsApp->sendRegistrationTypeSelection($from);
                break;

            case 'check_registration':
                $session->setState('awaiting_id_number');
                $this->whatsApp->sendMessage($from,
                    "🔍 *Check Registration Status*\n\n" .
                    "Please enter your registration number (for students) or national ID (for alumni):"
                );
                break;

            case 'today_schedule':
                $schedule = ProgramSchedule::getTodayScheduleForWhatsApp();
                $this->whatsApp->sendMessage($from, $schedule);
                $this->promptReturnToMenu($from);
                break;

            case 'full_schedule':
                $schedule = ProgramSchedule::getFullScheduleForWhatsApp();
                $this->whatsApp->sendMessage($from, $schedule);
                $this->promptReturnToMenu($from);
                break;

            case 'guest_speakers':
                $this->sendGuestSpeakers($from);
                break;

            case 'prayer_request':
                $session->setState('awaiting_prayer_request', ['name' => $contactName]);
                $this->whatsApp->sendMessage($from,
                    "🙏 *Prayer Request*\n\n" .
                    "Please type your prayer request below. Your request will be kept confidential and shared only with our prayer team.\n\n" .
                    "Type 'cancel' to go back to the menu."
                );
                break;

            case 'qas':
                $this->sendQAs($from);
                break;

            case 'main_menu':
                $session->reset();
                $this->whatsApp->sendMainMenu($from);
                break;

            default:
                $this->whatsApp->sendMainMenu($from);
                break;
        }
    }

    /**
     * Handle button selection
     */
    protected function handleButtonSelection(string $from, string $selectedId, WhatsAppSession $session, string $contactName): void
    {
        Log::info('Button selection', ['from' => $from, 'selection' => $selectedId]);

        switch ($selectedId) {
            case 'reg_student':
                $this->startConversationalRegistration($from, 'student', $session, $contactName);
                break;

            case 'reg_alumni':
                $this->startConversationalRegistration($from, 'alumni', $session, $contactName);
                break;

            case 'main_menu':
                $session->reset();
                $this->whatsApp->sendMainMenu($from);
                break;

            case 'confirm_yes':
                $this->handleRegistrationConfirm($from, 'yes', $session);
                break;

            case 'confirm_no':
                $session->reset();
                $this->whatsApp->sendMessage($from, "Registration cancelled. You can start again anytime.");
                $this->whatsApp->sendMainMenu($from);
                break;

            case 'pay_ecocash':
            case 'pay_innbucks':
            case 'pay_onemoney':
                $method = str_replace('pay_', '', $selectedId);
                $this->handlePaymentMethodInput($from, $method, $session);
                break;

            default:
                $this->handleMenuSelection($from, $selectedId, $session, $contactName);
                break;
        }
    }

    /**
     * Start registration - tries Flow first, falls back to conversational
     */
    protected function startConversationalRegistration(string $from, string $type, WhatsAppSession $session, string $contactName): void
    {
        // Try to send the WhatsApp Flow first
        $flowResult = $this->whatsApp->sendRegistrationFlow($from, $type);

        // If flow was sent successfully, we're done - the flow will handle the rest
        if (isset($flowResult['success']) && $flowResult['success']) {
            $session->setData('registration_type', $type);
            $session->setState('awaiting_flow_completion');
            return;
        }

        // If flow is not configured, fall back to conversational registration
        $amount = $type === 'student' ? 45 : 65;

        $session->setState('registration_name', [
            'registration_type' => $type,
            'amount' => $amount,
        ]);

        $idLabel = $type === 'student' ? 'Registration Number' : 'National ID';

        $this->whatsApp->sendMessage($from,
            "📝 *" . ucfirst($type) . " Registration (\${$amount})*\n\n" .
            "Let's get you registered! I'll need a few details.\n\n" .
            "*Step 1 of 6*\n" .
            "Please enter your *full name*:"
        );
    }

    /**
     * Handle registration name input
     */
    protected function handleRegistrationName(string $from, string $text, WhatsAppSession $session): void
    {
        if (strlen($text) < 3) {
            $this->whatsApp->sendMessage($from, "Please enter a valid full name (at least 3 characters):");
            return;
        }

        $session->setData('full_name', ucwords($text));
        $session->setState('registration_university');

        $this->whatsApp->sendMessage($from,
            "Great! *{$session->getData('full_name')}* ✓\n\n" .
            "*Step 2 of 6*\n" .
            "Please enter your *university/institution*:"
        );
    }

    /**
     * Handle registration university input
     */
    protected function handleRegistrationUniversity(string $from, string $text, WhatsAppSession $session): void
    {
        if (strlen($text) < 2) {
            $this->whatsApp->sendMessage($from, "Please enter a valid university name:");
            return;
        }

        $session->setData('university', ucwords($text));
        $session->setState('registration_email');

        $this->whatsApp->sendMessage($from,
            "University: *{$session->getData('university')}* ✓\n\n" .
            "*Step 3 of 6*\n" .
            "Please enter your *email address*:"
        );
    }

    /**
     * Handle registration email input
     */
    protected function handleRegistrationEmail(string $from, string $text, WhatsAppSession $session): void
    {
        if (!filter_var($text, FILTER_VALIDATE_EMAIL)) {
            $this->whatsApp->sendMessage($from, "Please enter a valid email address (e.g., yourname@email.com):");
            return;
        }

        $session->setData('email', strtolower($text));
        $session->setState('registration_id_number');

        $type = $session->getData('registration_type');
        $idLabel = $type === 'student' ? 'student registration number' : 'national ID number';

        $this->whatsApp->sendMessage($from,
            "Email: *{$session->getData('email')}* ✓\n\n" .
            "*Step 4 of 6*\n" .
            "Please enter your *{$idLabel}*:"
        );
    }

    /**
     * Handle registration ID number input
     */
    protected function handleRegistrationIdNumber(string $from, string $text, WhatsAppSession $session): void
    {
        $text = strtoupper(str_replace([' ', '-'], '', $text));

        if (strlen($text) < 5) {
            $this->whatsApp->sendMessage($from, "Please enter a valid ID number (at least 5 characters):");
            return;
        }

        // Check if already registered
        $existing = Registration::where('id_number', $text)->first();
        if ($existing && $existing->isPaid()) {
            $this->whatsApp->sendMessage($from,
                "⚠️ This ID number is already registered and paid.\n\n" .
                "Reference: *{$existing->reference}*\n" .
                "Name: {$existing->full_name}\n\n" .
                "If this is you, type 'check' to see your registration status."
            );
            $session->reset();
            $this->whatsApp->sendMainMenu($from);
            return;
        }

        $session->setData('id_number', $text);
        $session->setState('registration_gender');

        $this->whatsApp->sendButtonMessage($from,
            "ID Number: *{$text}* ✓\n\n" .
            "*Step 5 of 6*\n" .
            "Please select your *gender*:",
            [
                ['id' => 'gender_male', 'title' => 'Male'],
                ['id' => 'gender_female', 'title' => 'Female'],
            ]
        );
    }

    /**
     * Handle registration gender input
     */
    protected function handleRegistrationGender(string $from, string $text, WhatsAppSession $session): void
    {
        $text = strtolower($text);

        // Handle button responses
        if ($text === 'gender_male') $text = 'male';
        if ($text === 'gender_female') $text = 'female';

        if (!in_array($text, ['male', 'female', 'm', 'f'])) {
            $this->whatsApp->sendButtonMessage($from,
                "Please select your gender:",
                [
                    ['id' => 'gender_male', 'title' => 'Male'],
                    ['id' => 'gender_female', 'title' => 'Female'],
                ]
            );
            return;
        }

        $gender = in_array($text, ['male', 'm']) ? 'male' : 'female';
        $session->setData('gender', $gender);
        $session->setState('registration_level');

        $type = $session->getData('registration_type');
        $levelPrompt = $type === 'student'
            ? "Please enter your *level of study* (e.g., Part 1, Part 2, Part 3):"
            : "Please enter your *graduation year* (e.g., 2020):";

        $this->whatsApp->sendMessage($from,
            "Gender: *" . ucfirst($gender) . "* ✓\n\n" .
            "*Step 6 of 6*\n" .
            $levelPrompt
        );
    }

    /**
     * Handle registration level input
     */
    protected function handleRegistrationLevel(string $from, string $text, WhatsAppSession $session): void
    {
        if (strlen($text) < 1) {
            $type = $session->getData('registration_type');
            $levelPrompt = $type === 'student'
                ? "Please enter your level of study:"
                : "Please enter your graduation year:";
            $this->whatsApp->sendMessage($from, $levelPrompt);
            return;
        }

        $session->setData('level', $text);
        $session->setState('registration_confirm');

        // Show summary
        $type = $session->getData('registration_type');
        $idLabel = $type === 'student' ? 'Reg Number' : 'National ID';
        $levelLabel = $type === 'student' ? 'Level' : 'Graduation Year';

        $summary = "📋 *Registration Summary*\n";
        $summary .= "═══════════════════\n\n";
        $summary .= "Type: *" . ucfirst($type) . "*\n";
        $summary .= "Name: *{$session->getData('full_name')}*\n";
        $summary .= "University: *{$session->getData('university')}*\n";
        $summary .= "Email: *{$session->getData('email')}*\n";
        $summary .= "{$idLabel}: *{$session->getData('id_number')}*\n";
        $summary .= "Gender: *" . ucfirst($session->getData('gender')) . "*\n";
        $summary .= "{$levelLabel}: *{$session->getData('level')}*\n";
        $summary .= "\n💰 Amount: *\${$session->getData('amount')}*\n\n";
        $summary .= "Is this information correct?";

        $this->whatsApp->sendButtonMessage($from, $summary, [
            ['id' => 'confirm_yes', 'title' => '✓ Yes, Proceed'],
            ['id' => 'confirm_no', 'title' => '✗ No, Cancel'],
        ]);
    }

    /**
     * Handle registration confirmation
     */
    protected function handleRegistrationConfirm(string $from, string $text, WhatsAppSession $session): void
    {
        $text = strtolower($text);

        if (in_array($text, ['no', 'cancel', 'confirm_no'])) {
            $session->reset();
            $this->whatsApp->sendMessage($from, "Registration cancelled. You can start again anytime.");
            $this->whatsApp->sendMainMenu($from);
            return;
        }

        if (!in_array($text, ['yes', 'y', 'confirm_yes', 'confirm'])) {
            $this->whatsApp->sendButtonMessage($from,
                "Please confirm your registration:",
                [
                    ['id' => 'confirm_yes', 'title' => '✓ Yes, Proceed'],
                    ['id' => 'confirm_no', 'title' => '✗ No, Cancel'],
                ]
            );
            return;
        }

        // Create registration
        try {
            $registration = Registration::create([
                'reference' => Registration::generateReference(),
                'type' => $session->getData('registration_type'),
                'full_name' => $session->getData('full_name'),
                'university' => $session->getData('university'),
                'phone' => $from,
                'email' => $session->getData('email'),
                'id_number' => $session->getData('id_number'),
                'gender' => $session->getData('gender'),
                'level' => $session->getData('level'),
                'amount' => $session->getData('amount'),
                'payment_status' => 'pending',
            ]);

            $session->setData('registration_id', $registration->id);
            $session->setData('reference', $registration->reference);
            $session->setState('awaiting_payment_method');

            $this->whatsApp->sendMessage($from,
                "✅ *Registration Created!*\n\n" .
                "Reference: *{$registration->reference}*\n" .
                "Amount Due: *\${$registration->amount}*\n\n" .
                "Please complete payment to confirm your registration."
            );

            $this->whatsApp->sendButtonMessage($from,
                "Select your preferred payment method:",
                [
                    ['id' => 'pay_ecocash', 'title' => 'EcoCash'],
                    ['id' => 'pay_innbucks', 'title' => 'InnBucks'],
                    ['id' => 'pay_onemoney', 'title' => 'OneMoney'],
                ]
            );
        } catch (\Exception $e) {
            Log::error('Registration creation failed', [
                'from' => $from,
                'error' => $e->getMessage(),
            ]);

            $this->whatsApp->sendMessage($from,
                "❌ Sorry, there was an error creating your registration. Please try again or contact support."
            );
            $session->reset();
            $this->whatsApp->sendMainMenu($from);
        }
    }

    /**
     * Handle payment method selection
     */
    protected function handlePaymentMethodInput(string $from, string $text, WhatsAppSession $session): void
    {
        $text = strtolower(str_replace('pay_', '', $text));

        if (!in_array($text, ['ecocash', 'innbucks', 'onemoney'])) {
            $this->whatsApp->sendButtonMessage($from,
                "Please select a valid payment method:",
                [
                    ['id' => 'pay_ecocash', 'title' => 'EcoCash'],
                    ['id' => 'pay_innbucks', 'title' => 'InnBucks'],
                    ['id' => 'pay_onemoney', 'title' => 'OneMoney'],
                ]
            );
            return;
        }

        $session->setData('payment_method', $text);
        $session->setState('awaiting_payment_phone');

        $this->whatsApp->sendMessage($from,
            "💳 Payment Method: *" . ucfirst($text) . "*\n\n" .
            "Please enter the *phone number* to pay from:\n" .
            "(e.g., 0771234567 or 263771234567)"
        );
    }

    /**
     * Handle payment phone input
     */
    protected function handlePaymentPhoneInput(string $from, string $text, WhatsAppSession $session): void
    {
        // Clean phone number
        $phone = preg_replace('/[^0-9]/', '', $text);

        if (strlen($phone) < 9) {
            $this->whatsApp->sendMessage($from, "Please enter a valid phone number:");
            return;
        }

        $formattedPhone = PaynowService::formatPhone($phone);
        $registrationId = $session->getData('registration_id');

        if (!$registrationId) {
            $this->whatsApp->sendMessage($from, "Session expired. Please start registration again.");
            $session->reset();
            $this->whatsApp->sendMainMenu($from);
            return;
        }

        $registration = Registration::find($registrationId);

        if (!$registration) {
            $this->whatsApp->sendMessage($from, "Registration not found. Please try again.");
            $session->reset();
            $this->whatsApp->sendMainMenu($from);
            return;
        }

        // Initiate payment
        $this->whatsApp->sendMessage($from, "⏳ Initiating payment...\n\nPlease wait...");

        $paymentMethod = PaynowService::mapPaymentMethod($session->getData('payment_method'));
        $email = $formattedPhone . '@miscon26.co.zw';
        $description = "MISCON26 Registration - {$registration->reference}";

        $result = $this->paynow->initiateMobilePayment(
            $registration->reference,
            $email,
            (float) $registration->amount,
            $description,
            $formattedPhone,
            $paymentMethod
        );

        if (!$result['success']) {
            $this->whatsApp->sendMessage($from,
                "❌ Payment initiation failed.\n\n" .
                "Error: " . ($result['error'] ?? 'Unknown error') . "\n\n" .
                "Please try again or use a different payment method."
            );

            $this->whatsApp->sendButtonMessage($from,
                "Would you like to try another payment method?",
                [
                    ['id' => 'pay_ecocash', 'title' => 'EcoCash'],
                    ['id' => 'pay_innbucks', 'title' => 'InnBucks'],
                    ['id' => 'main_menu', 'title' => '← Main Menu'],
                ]
            );
            return;
        }

        // Update registration
        $registration->update([
            'payment_method' => $session->getData('payment_method'),
            'payment_phone' => $formattedPhone,
            'payment_status' => 'processing',
            'paynow_poll_url' => $result['poll_url'],
        ]);

        $instructions = $result['instructions'] ?? 'Please check your phone and enter your PIN to complete the payment.';

        $this->whatsApp->sendMessage($from,
            "📱 *Payment Request Sent!*\n\n" .
            "{$instructions}\n\n" .
            "Reference: *{$registration->reference}*\n" .
            "Amount: *\${$registration->amount}*\n\n" .
            "Your registration will be confirmed once payment is received.\n\n" .
            "💡 Type 'check' to verify your payment status."
        );

        $session->reset();
    }

    /**
     * Handle ID number input for registration check
     */
    protected function handleIdNumberInput(string $from, string $text, WhatsAppSession $session): void
    {
        if ($text === 'cancel' || $text === 'back') {
            $session->reset();
            $this->whatsApp->sendMainMenu($from);
            return;
        }

        $idNumber = strtoupper(str_replace([' ', '-'], '', $text));

        $registration = Registration::where('id_number', $idNumber)->first();

        if (!$registration) {
            $this->whatsApp->sendMessage($from,
                "❌ *No Registration Found*\n\n" .
                "We couldn't find a registration with ID: *{$idNumber}*\n\n" .
                "Please check your ID number and try again, or type 'menu' to register."
            );
            return;
        }

        $type = ucfirst($registration->type);
        $idLabel = $registration->getIdLabel();
        $levelLabel = $registration->getLevelLabel();

        $statusEmoji = match ($registration->payment_status) {
            'completed' => '✅',
            'processing' => '⏳',
            'failed' => '❌',
            default => '⏸️',
        };

        $statusText = match ($registration->payment_status) {
            'completed' => 'PAID',
            'processing' => 'PROCESSING',
            'failed' => 'FAILED',
            default => 'PENDING',
        };

        $message = "📋 *Registration Details*\n";
        $message .= "═══════════════════\n\n";
        $message .= "Reference: *{$registration->reference}*\n";
        $message .= "Status: {$statusEmoji} *{$statusText}*\n\n";
        $message .= "Name: {$registration->full_name}\n";
        $message .= "Type: {$type}\n";
        $message .= "University: {$registration->university}\n";
        $message .= "{$idLabel}: {$registration->id_number}\n";
        $message .= "{$levelLabel}: {$registration->level}\n";
        $message .= "Amount: \${$registration->amount}\n";

        if ($registration->isPaid()) {
            $message .= "\n✅ *Payment Confirmed*\n";
            $message .= "Paid on: " . $registration->paid_at->format('d M Y, H:i') . "\n";
            $message .= "Paynow Ref: {$registration->paynow_reference}\n";
            $message .= "\n🎉 See you at MISCON26!";
        } elseif ($registration->payment_status === 'processing') {
            $message .= "\n⏳ *Payment Processing*\n";
            $message .= "Please complete the payment on your phone.";
        } else {
            $message .= "\n⚠️ *Payment Required*\n";
            $message .= "Your registration is not complete until payment is made.";
        }

        $this->whatsApp->sendMessage($from, $message);

        // If not paid, offer to pay
        if (!$registration->isPaid() && $registration->payment_status !== 'processing') {
            $session->setData('registration_id', $registration->id);
            $session->setData('reference', $registration->reference);
            $session->setState('awaiting_payment_method');

            $this->whatsApp->sendButtonMessage($from,
                "Would you like to complete your payment now?",
                [
                    ['id' => 'pay_ecocash', 'title' => 'Pay with EcoCash'],
                    ['id' => 'pay_innbucks', 'title' => 'Pay with InnBucks'],
                    ['id' => 'main_menu', 'title' => '← Main Menu'],
                ]
            );
        } else {
            $session->reset();
            $this->promptReturnToMenu($from);
        }
    }

    /**
     * Handle prayer request input
     */
    protected function handlePrayerRequestInput(string $from, string $text, WhatsAppSession $session, string $contactName): void
    {
        if ($text === 'cancel' || $text === 'back') {
            $session->reset();
            $this->whatsApp->sendMainMenu($from);
            return;
        }

        if (strlen($text) < 10) {
            $this->whatsApp->sendMessage($from, "Please provide more details for your prayer request:");
            return;
        }

        // Save prayer request
        $prayerRequest = PrayerRequest::create([
            'phone' => $from,
            'name' => $session->getData('name', $contactName),
            'request' => $text,
            'status' => 'pending',
        ]);

        // Notify admins
        $this->whatsApp->notifyAdminsOfPrayerRequest(
            $from,
            $prayerRequest->name,
            $text
        );

        $this->whatsApp->sendMessage($from,
            "🙏 *Prayer Request Received*\n\n" .
            "Thank you for sharing your prayer request. Our prayer team will lift you up in prayer.\n\n" .
            "\"*The prayer of a righteous person is powerful and effective.*\" - James 5:16\n\n" .
            "God bless you! 🙌"
        );

        $session->reset();
        $this->promptReturnToMenu($from);
    }

    /**
     * Handle registration type input
     */
    protected function handleRegistrationTypeInput(string $from, string $text, WhatsAppSession $session): void
    {
        $text = strtolower($text);

        if (in_array($text, ['1', 'student', 'reg_student'])) {
            $this->startConversationalRegistration($from, 'student', $session, 'User');
        } elseif (in_array($text, ['2', 'alumni', 'reg_alumni'])) {
            $this->startConversationalRegistration($from, 'alumni', $session, 'User');
        } elseif (in_array($text, ['cancel', 'back', '0', 'main_menu'])) {
            $session->reset();
            $this->whatsApp->sendMainMenu($from);
        } else {
            $this->whatsApp->sendRegistrationTypeSelection($from);
        }
    }

    /**
     * Send guest speakers
     */
    protected function sendGuestSpeakers(string $from): void
    {
        $speakers = GuestSpeaker::active()->ordered()->get();

        if ($speakers->isEmpty()) {
            $this->whatsApp->sendMessage($from,
                "👤 *Guest Speakers*\n\n" .
                "Speaker information will be announced soon. Please check back later!"
            );
            $this->promptReturnToMenu($from);
            return;
        }

        $message = "👤 *MISCON26 GUEST SPEAKERS*\n";
        $message .= "═══════════════════\n\n";

        foreach ($speakers as $index => $speaker) {
            $message .= ($index + 1) . ". " . $speaker->toWhatsAppFormat() . "\n\n";
        }

        $this->whatsApp->sendMessage($from, $message);
        $this->promptReturnToMenu($from);
    }

    /**
     * Send QAs/FAQ
     */
    protected function sendQAs(string $from): void
    {
        $faqs = "❓ *Frequently Asked Questions*\n";
        $faqs .= "═══════════════════\n\n";

        $faqs .= "*Q: When is MISCON26?*\n";
        $faqs .= "A: Please check the full schedule for event dates.\n\n";

        $faqs .= "*Q: Where is MISCON26?*\n";
        $faqs .= "A: Venue details will be shared in the program schedule.\n\n";

        $faqs .= "*Q: How much is registration?*\n";
        $faqs .= "A: Students: \$45 | Alumni: \$65\n\n";

        $faqs .= "*Q: What payment methods?*\n";
        $faqs .= "A: EcoCash, InnBucks, OneMoney\n\n";

        $faqs .= "*Q: Can I get a refund?*\n";
        $faqs .= "A: Please contact the organizers for refund requests.\n\n";

        $faqs .= "*Q: Need more help?*\n";
        $faqs .= "A: Contact us at the main MISCON26 WhatsApp number.\n\n";

        $faqs .= "💡 Type 'menu' to return to the main menu.";

        $this->whatsApp->sendMessage($from, $faqs);
        $this->promptReturnToMenu($from);
    }

    /**
     * Handle flow responses
     */
    protected function handleFlowResponse(string $from, array $responseData, WhatsAppSession $session): void
    {
        Log::info('Flow response received', [
            'from' => $from,
            'data' => $responseData,
        ]);

        // Process flow form submission
        // This would be called when using WhatsApp Flows for registration
        if (isset($responseData['flow_token'])) {
            // Handle the registration data from the flow
            // The exact structure depends on your flow design
        }

        $session->reset();
    }

    /**
     * Handle non-message events (status updates, etc.)
     */
    protected function handleNonMessageEvent(array $value): void
    {
        // Handle status updates
        if (isset($value['statuses'])) {
            foreach ($value['statuses'] as $status) {
                Log::info('Message status update', [
                    'message_id' => $status['id'] ?? '',
                    'status' => $status['status'] ?? '',
                    'recipient' => $status['recipient_id'] ?? '',
                ]);
            }
        }
    }

    /**
     * Handle WhatsApp Flow data endpoint (for data exchange with flows)
     */
    public function handleFlowEndpoint(Request $request): JsonResponse
    {
        $data = $request->all();
        Log::info('WhatsApp Flow endpoint called', ['data' => $data]);

        $action = $data['action'] ?? '';
        $screenId = $data['screen'] ?? '';
        $flowToken = $data['flow_token'] ?? '';
        $formData = $data['data'] ?? [];

        // Handle different flow actions
        if ($action === 'data_exchange') {
            return $this->handleFlowDataExchange($screenId, $formData, $flowToken);
        }

        if ($action === 'INIT') {
            // Initial screen data
            return response()->json([
                'screen' => 'REGISTRATION_TYPE',
                'data' => [],
            ]);
        }

        return response()->json(['status' => 'ok']);
    }

    /**
     * Handle flow data exchange requests
     */
    protected function handleFlowDataExchange(string $screenId, array $formData, string $flowToken): JsonResponse
    {
        Log::info('Flow data exchange', [
            'screen' => $screenId,
            'data' => $formData,
        ]);

        // When the flow completes (CONFIRMATION screen), process the registration
        if ($screenId === 'CONFIRMATION') {
            return $this->processFlowRegistration($formData, $flowToken);
        }

        // For other screens, just acknowledge
        return response()->json([
            'data' => $formData,
        ]);
    }

    /**
     * Process registration from completed flow
     */
    protected function processFlowRegistration(array $formData, string $flowToken): JsonResponse
    {
        try {
            // Check for existing registration
            $idNumber = strtoupper(str_replace([' ', '-'], '', $formData['id_number'] ?? ''));
            $existing = Registration::where('id_number', $idNumber)->first();

            if ($existing && $existing->isPaid()) {
                return response()->json([
                    'data' => [
                        'error_message' => 'This ID is already registered and paid.',
                    ],
                ]);
            }

            // Create or update registration
            $registration = $existing ?? Registration::create([
                'reference' => Registration::generateReference(),
                'type' => $formData['registration_type'] ?? 'student',
                'full_name' => $formData['full_name'] ?? '',
                'university' => $formData['university'] ?? '',
                'phone' => '', // Will be updated from webhook
                'email' => $formData['email'] ?? '',
                'id_number' => $idNumber,
                'gender' => $formData['gender'] ?? 'male',
                'level' => $formData['level'] ?? '',
                'amount' => Registration::getAmount($formData['registration_type'] ?? 'student'),
                'payment_status' => 'pending',
            ]);

            // Initiate payment
            $paymentPhone = PaynowService::formatPhone($formData['payment_phone'] ?? '');
            $paymentMethod = PaynowService::mapPaymentMethod($formData['payment_method'] ?? 'ecocash');
            $email = $paymentPhone . '@miscon26.co.zw';
            $description = "MISCON26 Registration - {$registration->reference}";

            $result = $this->paynow->initiateMobilePayment(
                $registration->reference,
                $email,
                (float) $registration->amount,
                $description,
                $paymentPhone,
                $paymentMethod
            );

            if ($result['success']) {
                $registration->update([
                    'payment_method' => $formData['payment_method'] ?? 'ecocash',
                    'payment_phone' => $paymentPhone,
                    'payment_status' => 'processing',
                    'paynow_poll_url' => $result['poll_url'],
                ]);

                return response()->json([
                    'data' => [
                        'success' => true,
                        'reference' => $registration->reference,
                        'message' => 'Payment initiated! Check your phone.',
                    ],
                ]);
            }

            return response()->json([
                'data' => [
                    'error_message' => $result['error'] ?? 'Payment failed. Please try again.',
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Flow registration error', [
                'error' => $e->getMessage(),
                'data' => $formData,
            ]);

            return response()->json([
                'data' => [
                    'error_message' => 'Registration failed. Please try again.',
                ],
            ]);
        }
    }

    /**
     * Prompt user to return to menu
     */
    protected function promptReturnToMenu(string $from): void
    {
        $this->whatsApp->sendButtonMessage($from,
            "What would you like to do next?",
            [
                ['id' => 'main_menu', 'title' => '← Main Menu'],
            ],
            null,
            'Type "menu" anytime to return'
        );
    }
}
