<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected string $accessToken;
    protected string $apiUrl;
    protected string $phoneId;
    protected bool $configured = false;

    public function __construct()
    {
        $this->accessToken = config('whatsapp.access_token', '');
        $this->apiUrl = config('whatsapp.api_url', 'https://graph.facebook.com/v21.0');
        $this->phoneId = config('whatsapp.phone_id', '');

        $this->configured = !empty($this->accessToken) && !empty($this->phoneId);

        if (!$this->configured) {
            Log::warning('WhatsApp service not configured', [
                'has_token' => !empty($this->accessToken),
                'has_phone_id' => !empty($this->phoneId),
            ]);
        }
    }

    /**
     * Check if WhatsApp is configured
     */
    public function isConfigured(): bool
    {
        return $this->configured;
    }

    /**
     * Send a text message
     */
    public function sendMessage(string $to, string $message): array
    {
        return $this->sendRequest([
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $to,
            'type' => 'text',
            'text' => [
                'preview_url' => false,
                'body' => $message,
            ],
        ]);
    }

    /**
     * Send an interactive list message
     */
    public function sendListMessage(string $to, string $header, string $body, string $footer, string $buttonText, array $sections): array
    {
        return $this->sendRequest([
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $to,
            'type' => 'interactive',
            'interactive' => [
                'type' => 'list',
                'header' => [
                    'type' => 'text',
                    'text' => $header,
                ],
                'body' => [
                    'text' => $body,
                ],
                'footer' => [
                    'text' => $footer,
                ],
                'action' => [
                    'button' => $buttonText,
                    'sections' => $sections,
                ],
            ],
        ]);
    }

    /**
     * Send interactive button message
     */
    public function sendButtonMessage(string $to, string $body, array $buttons, ?string $header = null, ?string $footer = null): array
    {
        $interactive = [
            'type' => 'button',
            'body' => [
                'text' => $body,
            ],
            'action' => [
                'buttons' => array_map(function ($button, $index) {
                    return [
                        'type' => 'reply',
                        'reply' => [
                            'id' => $button['id'] ?? "btn_$index",
                            'title' => substr($button['title'], 0, 20), // Max 20 chars
                        ],
                    ];
                }, $buttons, array_keys($buttons)),
            ],
        ];

        if ($header) {
            $interactive['header'] = [
                'type' => 'text',
                'text' => $header,
            ];
        }

        if ($footer) {
            $interactive['footer'] = [
                'text' => $footer,
            ];
        }

        return $this->sendRequest([
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $to,
            'type' => 'interactive',
            'interactive' => $interactive,
        ]);
    }

    /**
     * Send a WhatsApp Flow message
     */
    public function sendFlowMessage(string $to, string $flowId, string $flowToken, string $body, string $buttonText, string $mode = 'draft', array $flowActionPayload = []): array
    {
        $flowAction = [
            'name' => 'flow',
            'parameters' => [
                'flow_message_version' => '3',
                'flow_token' => $flowToken,
                'flow_id' => $flowId,
                'flow_cta' => $buttonText,
                'flow_action' => 'navigate',
                'flow_action_payload' => [
                    'screen' => 'REGISTRATION_TYPE',
                    'data' => $flowActionPayload,
                ],
            ],
        ];

        // Add mode only if draft
        if ($mode === 'draft') {
            $flowAction['parameters']['mode'] = 'draft';
        }

        return $this->sendRequest([
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $to,
            'type' => 'interactive',
            'interactive' => [
                'type' => 'flow',
                'body' => [
                    'text' => $body,
                ],
                'action' => $flowAction,
            ],
        ]);
    }

    /**
     * Send the main menu
     */
    public function sendMainMenu(string $to): array
    {
        $body = "Welcome to *MISCON26* 🎉\n\n";
        $body .= "The Medical Inter-Schools Conference 2026\n\n";
        $body .= "How can we help you today?";

        $sections = [
            [
                'title' => 'Registration',
                'rows' => [
                    [
                        'id' => 'register',
                        'title' => 'Register Now',
                        'description' => 'Register for MISCON26',
                    ],
                    [
                        'id' => 'check_registration',
                        'title' => 'Check Registration',
                        'description' => 'Check your registration status',
                    ],
                ],
            ],
            [
                'title' => 'Conference Info',
                'rows' => [
                    [
                        'id' => 'today_schedule',
                        'title' => "Today's Schedule",
                        'description' => "View today's program",
                    ],
                    [
                        'id' => 'full_schedule',
                        'title' => 'Full Schedule',
                        'description' => 'View complete program',
                    ],
                    [
                        'id' => 'guest_speakers',
                        'title' => 'Guest Speakers',
                        'description' => 'View speaker information',
                    ],
                ],
            ],
            [
                'title' => 'Other',
                'rows' => [
                    [
                        'id' => 'prayer_request',
                        'title' => 'Prayer Request',
                        'description' => 'Submit a prayer request',
                    ],
                    [
                        'id' => 'qas',
                        'title' => 'QAs',
                        'description' => 'Questions and Answers',
                    ],
                ],
            ],
        ];

        return $this->sendListMessage(
            $to,
            '🏥 MISCON26',
            $body,
            'Tap to select an option',
            'Show Menu',
            $sections
        );
    }

    /**
     * Send registration type selection
     */
    public function sendRegistrationTypeSelection(string $to): array
    {
        $body = "📝 *MISCON26 Registration*\n\n";
        $body .= "Please select your registration type:\n\n";
        $body .= "👨‍🎓 *Student* - \$45\n";
        $body .= "🎓 *Alumni* - \$65";

        $buttons = [
            ['id' => 'reg_student', 'title' => 'Student ($45)'],
            ['id' => 'reg_alumni', 'title' => 'Alumni ($65)'],
            ['id' => 'main_menu', 'title' => '← Back to Menu'],
        ];

        return $this->sendButtonMessage($to, $body, $buttons, 'Registration');
    }

    /**
     * Send registration flow based on type (student or alumni)
     */
    public function sendRegistrationFlow(string $to, string $registrationType): array
    {
        // Get the appropriate flow ID based on registration type
        $flowId = $registrationType === 'student' 
            ? config('whatsapp.student_flow_id')
            : config('whatsapp.alumni_flow_id');
        
        $flowMode = config('whatsapp.flow_mode', 'draft');

        if (!$flowId) {
            // Fallback to conversational registration (return false to trigger conversational mode)
            return ['success' => false, 'flow_not_configured' => true];
        }

        $amount = $registrationType === 'student' ? 45 : 65;
        $startScreen = $registrationType === 'student' ? 'STUDENT_DETAILS' : 'ALUMNI_DETAILS';
        
        $body = "📝 Complete your " . ucfirst($registrationType) . " registration (\${$amount})\n\n";
        $body .= "Click the button below to fill in your details.";

        return $this->sendFlowMessageWithScreen(
            $to,
            $flowId,
            uniqid('flow_'),
            $body,
            'Register Now',
            $flowMode,
            $startScreen
        );
    }

    /**
     * Send a WhatsApp Flow message with specific start screen
     */
    public function sendFlowMessageWithScreen(string $to, string $flowId, string $flowToken, string $body, string $buttonText, string $mode = 'draft', string $startScreen = 'STUDENT_DETAILS'): array
    {
        $flowAction = [
            'name' => 'flow',
            'parameters' => [
                'flow_message_version' => '3',
                'flow_token' => $flowToken,
                'flow_id' => $flowId,
                'flow_cta' => $buttonText,
                'flow_action' => 'navigate',
                'flow_action_payload' => [
                    'screen' => $startScreen
                ],
            ],
        ];

        // Add mode only if draft
        if ($mode === 'draft') {
            $flowAction['parameters']['mode'] = 'draft';
        }

        return $this->sendRequest([
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $to,
            'type' => 'interactive',
            'interactive' => [
                'type' => 'flow',
                'body' => [
                    'text' => $body,
                ],
                'action' => $flowAction,
            ],
        ]);
    }

    /**
     * Mark message as read
     */
    public function markAsRead(string $messageId): array
    {
        return $this->sendRequest([
            'messaging_product' => 'whatsapp',
            'status' => 'read',
            'message_id' => $messageId,
        ]);
    }

    /**
     * Send typing indicator (via read receipt)
     */
    public function sendTypingIndicator(string $to): void
    {
        // WhatsApp doesn't have a direct typing indicator API
        // Reading the message gives a visual cue
    }

    /**
     * Make API request to WhatsApp
     */
    protected function sendRequest(array $data): array
    {
        if (!$this->configured) {
            Log::error('WhatsApp API request attempted but not configured');
            return [
                'success' => false,
                'error' => 'WhatsApp service is not configured',
            ];
        }

        try {
            $url = "{$this->apiUrl}/{$this->phoneId}/messages";

            Log::info('WhatsApp API request', [
                'url' => $url,
                'data' => $data,
            ]);

            $response = Http::withToken($this->accessToken)
                ->timeout(30)
                ->post($url, $data);

            if ($response->successful()) {
                Log::info('WhatsApp API success', [
                    'response' => $response->json(),
                ]);

                return [
                    'success' => true,
                    'data' => $response->json(),
                ];
            }

            Log::error('WhatsApp API error', [
                'status' => $response->status(),
                'response' => $response->json(),
            ]);

            return [
                'success' => false,
                'error' => $response->json()['error']['message'] ?? 'Unknown error',
                'status' => $response->status(),
            ];
        } catch (\Exception $e) {
            Log::error('WhatsApp API exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Notify admins of new prayer request
     */
    public function notifyAdminsOfPrayerRequest(string $fromPhone, string $name, string $request): void
    {
        $adminPhones = config('whatsapp.admin_phones', []);

        if (empty($adminPhones)) {
            Log::warning('No admin phones configured for prayer request notifications');
            return;
        }

        $message = "🙏 *New Prayer Request*\n\n";
        $message .= "From: {$name}\n";
        $message .= "Phone: {$fromPhone}\n";
        $message .= "Time: " . now()->format('d M Y, H:i') . "\n\n";
        $message .= "Request:\n{$request}";

        foreach ($adminPhones as $adminPhone) {
            $this->sendMessage(trim($adminPhone), $message);
        }
    }
}
