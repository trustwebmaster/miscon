# WhatsApp Chatbot Setup Guide - MISCON26

This guide explains how to set up the WhatsApp chatbot for MISCON26 registration and information.

## Features

The chatbot provides the following functionality:

1. **Registration** - Complete student/alumni registration with Paynow payment
2. **Check Registration Status** - Verify registration and payment status
3. **Prayer Requests** - Submit confidential prayer requests (admin-viewable only)
4. **Guest Speakers** - View information about conference speakers
5. **Program Schedule** - View full conference schedule
6. **Today's Program** - View current day's events
7. **QAs** - Frequently asked questions

## Prerequisites

1. Meta Business Account
2. WhatsApp Business API access
3. Phone number registered with WhatsApp Business
4. SSL-enabled domain for webhook

## Environment Variables

Add these to your `.env` file:

```env
# WhatsApp Business API Configuration
WHATSAPP_ACCESS_TOKEN=your_access_token_here
WHATSAPP_API_URL=https://graph.facebook.com/v21.0
WHATSAPP_PHONE_ID=your_phone_id_here
WHATSAPP_VERIFY_TOKEN=your_verify_token_here
WHATSAPP_PHONE_NUMBER=+263786714821

# WhatsApp Flow Configuration (optional - uses conversational flow if not set)
WHATSAPP_STUDENT_FLOW_ID=your_student_flow_id_here
WHATSAPP_ALUMNI_FLOW_ID=your_alumni_flow_id_here
WHATSAPP_FLOW_MODE=published

# Admin phones for prayer request notifications (comma-separated)
WHATSAPP_ADMIN_PHONES=+263771234567,+263772345678

# Session timeout in minutes
WHATSAPP_SESSION_TIMEOUT=30
```

## Meta Developer Setup

### 1. Create a Meta App

1. Go to [Meta for Developers](https://developers.facebook.com/)
2. Create a new app (Business type)
3. Add WhatsApp product to your app

### 2. Configure WhatsApp Business

1. Add a phone number to your WhatsApp Business account
2. Get your **Phone Number ID** from the WhatsApp dashboard
3. Generate a **Permanent Access Token** (or use system user token)

### 3. Set Up Webhook

1. In your Meta App Dashboard, go to WhatsApp > Configuration
2. Set the Webhook URL to: `https://yourdomain.com/webhook/whatsapp`
3. Set the Verify Token to match your `WHATSAPP_VERIFY_TOKEN` env variable
4. Subscribe to the following webhook fields:
   - `messages`
   - `message_deliveries`
   - `message_reads`

### 4. Create WhatsApp Flows (Optional)

For a more form-like registration experience, you can use WhatsApp Flows. We have two separate flows for students and alumni:

**Student Flow:**
1. Go to WhatsApp > Flows in your Meta App Dashboard
2. Create a new Flow named "Student Registration"
3. Copy the contents of `whatsapp-flows/student-registration-flow.json`
4. Publish the flow and copy the Flow ID to `WHATSAPP_STUDENT_FLOW_ID`

**Alumni Flow:**
1. Create another new Flow named "Alumni Registration"  
2. Copy the contents of `whatsapp-flows/alumni-registration-flow.json`
3. Publish the flow and copy the Flow ID to `WHATSAPP_ALUMNI_FLOW_ID`

**Note:** If you don't configure the flows, the chatbot will automatically fall back to conversational registration which works just as well!

## Database Migrations

Run the migrations to create the required tables:

```bash
php artisan migrate
```

This creates:
- `prayer_requests` - Store prayer requests
- `guest_speakers` - Store speaker information
- `program_schedules` - Store conference schedule
- `whatsapp_sessions` - Track conversation state

## Admin Management

### Adding Guest Speakers

You can add speakers directly to the database or create an admin panel:

```php
use App\Models\GuestSpeaker;

GuestSpeaker::create([
    'name' => 'John Smith',
    'title' => 'Dr.',
    'topic' => 'Faith in Medicine',
    'bio' => 'Dr. John Smith is a renowned physician...',
    'organization' => 'City Hospital',
    'display_order' => 1,
    'is_active' => true,
]);
```

### Adding Program Schedule

```php
use App\Models\ProgramSchedule;

ProgramSchedule::create([
    'event_date' => '2026-03-15',
    'start_time' => '09:00',
    'end_time' => '10:30',
    'title' => 'Opening Plenary',
    'description' => 'Welcome and opening remarks',
    'venue' => 'Main Auditorium',
    'speaker' => 'Dr. Jane Doe',
    'category' => 'plenary',
    'display_order' => 1,
    'is_active' => true,
]);
```

### Viewing Prayer Requests

Prayer requests are viewable only by admins. Create an admin route or use Tinker:

```php
use App\Models\PrayerRequest;

// Get all pending requests
$requests = PrayerRequest::pending()->latest()->get();

// Mark as prayed
$request = PrayerRequest::find(1);
$request->markAsPrayed('Prayed for in team meeting');
```

## Chatbot Menu Structure

```
Main Menu
├── Register Now
│   ├── Student ($45)
│   └── Alumni ($65)
├── Check Registration
├── Today's Schedule
├── Full Schedule
├── Guest Speakers
├── Prayer Request
└── QAs
```

## Message Commands

Users can type these commands anytime:
- `menu`, `hi`, `hello`, `start`, `home`, `0`, `help` - Return to main menu
- `register` or `1` - Start registration
- `check` or `2` - Check registration status
- `today` or `3` - Today's schedule
- `schedule` or `4` - Full schedule
- `speakers` or `5` - Guest speakers
- `prayer` or `6` - Submit prayer request
- `qa` or `7` - FAQ

## Testing

### Test Webhook Verification

```bash
curl "https://yourdomain.com/webhook/whatsapp?hub_mode=subscribe&hub_verify_token=your_verify_token&hub_challenge=test123"
```

Should return: `test123`

### Test Webhook Handler

```bash
curl -X POST https://yourdomain.com/webhook/whatsapp \
  -H "Content-Type: application/json" \
  -d '{"entry":[{"changes":[{"value":{"messages":[{"from":"263771234567","type":"text","text":{"body":"hi"}}],"contacts":[{"wa_id":"263771234567","profile":{"name":"Test User"}}]},"field":"messages"}]}]}'
```

## Security Considerations

1. Always use HTTPS for webhooks
2. Validate webhook signatures (implement in production)
3. Keep admin phone numbers private
4. Regularly rotate access tokens
5. Monitor logs for suspicious activity

## Troubleshooting

### Webhook not receiving messages
- Verify webhook URL is accessible
- Check SSL certificate is valid
- Ensure webhook fields are subscribed
- Check Laravel logs: `storage/logs/laravel.log`

### Payments not working
- Verify Paynow credentials
- Check phone number format (should be 0771234567)
- Review Paynow callback URL configuration

### Flow not showing
- Ensure flow is published (not draft)
- Verify flow ID in `.env`
- Check flow mode setting

## Support

For issues with:
- WhatsApp API: [Meta Business Help Center](https://www.facebook.com/business/help)
- Paynow: [Paynow Support](https://www.paynow.co.zw/Help)
- This application: Check GitHub issues or contact the development team
