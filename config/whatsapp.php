<?php

return [

    /*
    |--------------------------------------------------------------------------
    | WhatsApp Business API Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for WhatsApp Business API integration using Meta's
    | Cloud API for the MISCON26 chatbot.
    |
    */

    // WhatsApp Cloud API
    'access_token' => env('WHATSAPP_ACCESS_TOKEN'),
    'api_url' => env('WHATSAPP_API_URL', 'https://graph.facebook.com/v21.0'),
    'phone_id' => env('WHATSAPP_PHONE_ID'),
    'verify_token' => env('WHATSAPP_VERIFY_TOKEN', 'miscon26_verify_token'),
    'phone_number' => env('WHATSAPP_PHONE_NUMBER'),

    // WhatsApp Flow Configuration
    'student_flow_id' => env('WHATSAPP_STUDENT_FLOW_ID'),
    'alumni_flow_id' => env('WHATSAPP_ALUMNI_FLOW_ID'),
    'flow_mode' => env('WHATSAPP_FLOW_MODE', 'draft'),

    // Admin phone numbers (can receive prayer requests notifications)
    'admin_phones' => array_filter(explode(',', env('WHATSAPP_ADMIN_PHONES', ''))),

    // Message templates
    'templates' => [
        'welcome' => env('WHATSAPP_TEMPLATE_WELCOME', 'miscon26_welcome'),
        'registration_complete' => env('WHATSAPP_TEMPLATE_REGISTRATION', 'miscon26_registration_complete'),
        'payment_reminder' => env('WHATSAPP_TEMPLATE_PAYMENT', 'miscon26_payment_reminder'),
    ],

    // Session timeout (minutes)
    'session_timeout' => env('WHATSAPP_SESSION_TIMEOUT', 30),

];
