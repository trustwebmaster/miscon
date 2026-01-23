<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MISCON26 Registration Confirmation</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #0a0e1a;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #0a0e1a; min-height: 100vh;">
        <tr>
            <td align="center" style="padding: 40px 20px;">
                <!-- Main Container -->
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" style="max-width: 600px; width: 100%;">
                    <!-- Header -->
                    <tr>
                        <td align="center" style="padding-bottom: 30px;">
                            <table role="presentation" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td style="text-align: center;">
                                        <div style="display: inline-block; background: linear-gradient(135deg, #3b82f6, #8b5cf6, #ec4899); padding: 15px 25px; border-radius: 15px;">
                                            <span style="color: white; font-size: 28px; font-weight: bold; letter-spacing: 1px;">PCM</span>
                                        </div>
                                        <h1 style="margin: 15px 0 5px; color: #ffffff; font-size: 32px; font-weight: bold;">
                                            MISCON<span style="color: #d4af37;">26</span>
                                        </h1>
                                        <p style="margin: 0; color: #9ca3af; font-size: 14px; letter-spacing: 2px;">PUBLIC CAMPUS MINISTRIES</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Success Card -->
                    <tr>
                        <td>
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background: linear-gradient(145deg, rgba(255,255,255,0.05), rgba(255,255,255,0.02)); border: 1px solid rgba(255,255,255,0.1); border-radius: 24px; overflow: hidden;">
                                <!-- Success Header -->
                                <tr>
                                    <td style="background: linear-gradient(135deg, rgba(34, 197, 94, 0.2), rgba(16, 185, 129, 0.1)); padding: 30px; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.1);">
                                        <div style="width: 70px; height: 70px; background: linear-gradient(135deg, #22c55e, #10b981); border-radius: 50%; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center;">
                                            <span style="color: white; font-size: 36px; line-height: 70px;">✓</span>
                                        </div>
                                        <h2 style="margin: 0 0 10px; color: #22c55e; font-size: 26px; font-weight: bold;">Registration Successful!</h2>
                                        <p style="margin: 0; color: #9ca3af; font-size: 16px;">Your payment has been confirmed</p>
                                    </td>
                                </tr>

                                <!-- Greeting -->
                                <tr>
                                    <td style="padding: 30px 30px 20px;">
                                        <p style="margin: 0; color: #ffffff; font-size: 18px; line-height: 1.6;">
                                            Dear <strong style="color: #d4af37;">{{ $registration->full_name }}</strong>,
                                        </p>
                                        <p style="margin: 15px 0 0; color: #9ca3af; font-size: 16px; line-height: 1.6;">
                                            Thank you for registering for <strong style="color: #ffffff;">MISCON26</strong>! We're thrilled to have you join us for this transformative conference.
                                        </p>
                                    </td>
                                </tr>

                                <!-- Registration Details -->
                                <tr>
                                    <td style="padding: 10px 30px 30px;">
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.1); border-radius: 16px; overflow: hidden;">
                                            <tr>
                                                <td style="padding: 20px; border-bottom: 1px solid rgba(255,255,255,0.1);">
                                                    <p style="margin: 0 0 5px; color: #6b7280; font-size: 12px; text-transform: uppercase; letter-spacing: 1px;">Reference Number</p>
                                                    <p style="margin: 0; color: #d4af37; font-size: 20px; font-weight: bold; font-family: 'Courier New', monospace;">{{ $registration->reference }}</p>
                                                </td>
                                            </tr>
                                            @if($registration->paynow_reference)
                                            <tr>
                                                <td style="padding: 15px 20px; border-bottom: 1px solid rgba(255,255,255,0.1);">
                                                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                            <td style="color: #9ca3af; font-size: 14px;">Paynow Reference</td>
                                                            <td style="color: #ffffff; font-size: 14px; text-align: right; font-family: 'Courier New', monospace;">{{ $registration->paynow_reference }}</td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <td style="padding: 15px 20px; border-bottom: 1px solid rgba(255,255,255,0.1);">
                                                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                            <td style="color: #9ca3af; font-size: 14px;">Registration Type</td>
                                                            <td style="text-align: right;">
                                                                <span style="display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; {{ $registration->type === 'student' ? 'background: rgba(59, 130, 246, 0.2); color: #3b82f6;' : 'background: rgba(139, 92, 246, 0.2); color: #8b5cf6;' }}">
                                                                    {{ ucfirst($registration->type) }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 15px 20px; border-bottom: 1px solid rgba(255,255,255,0.1);">
                                                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                            <td style="color: #9ca3af; font-size: 14px;">Institution</td>
                                                            <td style="color: #ffffff; font-size: 14px; text-align: right;">{{ $registration->university }}</td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 15px 20px; border-bottom: 1px solid rgba(255,255,255,0.1);">
                                                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                            <td style="color: #9ca3af; font-size: 14px;">{{ $registration->type === 'student' ? 'Level' : 'Graduation Year' }}</td>
                                                            <td style="color: #ffffff; font-size: 14px; text-align: right;">{{ $registration->level }}</td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 15px 20px;">
                                                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                            <td style="color: #9ca3af; font-size: 14px;">Amount Paid</td>
                                                            <td style="color: #22c55e; font-size: 18px; font-weight: bold; text-align: right;">${{ number_format($registration->amount, 2) }} USD</td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <!-- Event Details -->
                                <tr>
                                    <td style="padding: 0 30px 30px;">
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.15), rgba(212, 175, 55, 0.05)); border: 1px solid rgba(212, 175, 55, 0.3); border-radius: 16px; overflow: hidden;">
                                            <tr>
                                                <td style="padding: 25px;">
                                                    <h3 style="margin: 0 0 20px; color: #d4af37; font-size: 18px; font-weight: bold; text-align: center;">Event Details</h3>
                                                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                            <td width="50%" style="padding: 10px 0; vertical-align: top;">
                                                                <p style="margin: 0 0 5px; color: #6b7280; font-size: 12px; text-transform: uppercase;">Date</p>
                                                                <p style="margin: 0; color: #ffffff; font-size: 15px; font-weight: 600;">April 3-6, 2026</p>
                                                            </td>
                                                            <td width="50%" style="padding: 10px 0; vertical-align: top;">
                                                                <p style="margin: 0 0 5px; color: #6b7280; font-size: 12px; text-transform: uppercase;">Venue</p>
                                                                <p style="margin: 0; color: #ffffff; font-size: 15px; font-weight: 600;">Amai Mugabe Group of Schools</p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2" style="padding: 10px 0 0;">
                                                                <p style="margin: 0 0 5px; color: #6b7280; font-size: 12px; text-transform: uppercase;">Theme</p>
                                                                <p style="margin: 0; color: #d4af37; font-size: 15px; font-weight: 600; font-style: italic;">"Transforming Lives Through Christ"</p>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <!-- What's Included -->
                                <tr>
                                    <td style="padding: 0 30px 30px;">
                                        <h3 style="margin: 0 0 15px; color: #ffffff; font-size: 16px; font-weight: bold;">What's Included:</h3>
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td width="50%" style="padding: 8px 0; color: #9ca3af; font-size: 14px;">
                                                    <span style="color: #22c55e; margin-right: 8px;">✓</span> Full conference access
                                                </td>
                                                <td width="50%" style="padding: 8px 0; color: #9ca3af; font-size: 14px;">
                                                    <span style="color: #22c55e; margin-right: 8px;">✓</span> Accommodation (4 nights)
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="50%" style="padding: 8px 0; color: #9ca3af; font-size: 14px;">
                                                    <span style="color: #22c55e; margin-right: 8px;">✓</span> Meals included
                                                </td>
                                                <td width="50%" style="padding: 8px 0; color: #9ca3af; font-size: 14px;">
                                                    <span style="color: #22c55e; margin-right: 8px;">✓</span> Conference materials
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" style="padding: 8px 0; color: #9ca3af; font-size: 14px;">
                                                    <span style="color: #22c55e; margin-right: 8px;">✓</span> Certificate of attendance
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <!-- CTA Button -->
                                <tr>
                                    <td style="padding: 0 30px 30px; text-align: center;">
                                        <a href="https://chat.whatsapp.com/BKROFqIVsdE3vWccklOKGU" target="_blank" style="display: inline-block; padding: 16px 32px; background: linear-gradient(135deg, #22c55e, #16a34a); color: white; text-decoration: none; border-radius: 12px; font-weight: bold; font-size: 16px;">
                                            Join WhatsApp Group
                                        </a>
                                        <p style="margin: 15px 0 0; color: #6b7280; font-size: 13px;">Stay updated with the latest conference information</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Important Notice -->
                    <tr>
                        <td style="padding: 30px 0;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background: rgba(234, 179, 8, 0.1); border: 1px solid rgba(234, 179, 8, 0.3); border-radius: 12px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <p style="margin: 0 0 10px; color: #eab308; font-size: 14px; font-weight: bold;">📌 Important:</p>
                                        <ul style="margin: 0; padding: 0 0 0 20px; color: #9ca3af; font-size: 14px; line-height: 1.8;">
                                            <li>Please save this email for your records</li>
                                            <li>Bring your reference number (<strong style="color: #d4af37;">{{ $registration->reference }}</strong>) to the event</li>
                                            <li>Arrive at the venue by 2:00 PM on April 3rd for registration</li>
                                        </ul>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Contact Info -->
                    <tr>
                        <td style="padding: 0 0 30px; text-align: center;">
                            <p style="margin: 0 0 15px; color: #6b7280; font-size: 14px;">Need help? Contact us:</p>
                            <table role="presentation" cellspacing="0" cellpadding="0" style="margin: 0 auto;">
                                <tr>
                                    <td style="padding: 0 15px;">
                                        <a href="tel:+263782504742" style="color: #d4af37; text-decoration: none; font-size: 14px;">📞 +263 78 250 4742</a>
                                    </td>
                                    <td style="padding: 0 15px;">
                                        <a href="https://wa.me/263782504742" style="color: #22c55e; text-decoration: none; font-size: 14px;">💬 WhatsApp</a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="text-align: center; padding: 30px 0; border-top: 1px solid rgba(255,255,255,0.1);">
                            <p style="margin: 0 0 10px; color: #6b7280; font-size: 13px;">
                                © 2026 North Zimbabwe Conference | SDA Church
                            </p>
                            <p style="margin: 0; color: #4b5563; font-size: 12px;">
                                Public Campus Ministries - MISCON26
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
