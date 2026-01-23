# Email Configuration Guide

This guide explains how to configure Google SMTP for sending registration confirmation emails.

## Google SMTP Configuration

To send emails using Google SMTP, you need to configure the following environment variables in your `.env` file:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="MISCON26"
```

## Setting Up Google App Password

1. **Enable 2-Step Verification** on your Google Account:
   - Go to [Google Account Security](https://myaccount.google.com/security)
   - Enable 2-Step Verification if not already enabled

2. **Generate an App Password**:
   - Go to [App Passwords](https://myaccount.google.com/apppasswords)
   - Select "Mail" as the app
   - Select "Other (Custom name)" as the device
   - Enter "MISCON26 Laravel" as the name
   - Click "Generate"
   - Copy the 16-character password (without spaces)

3. **Update your `.env` file**:
   - Set `MAIL_USERNAME` to your Gmail address
   - Set `MAIL_PASSWORD` to the generated app password
   - Set `MAIL_FROM_ADDRESS` to your Gmail address

## Testing Email Configuration

After configuring your `.env` file, you can test the email functionality by:

1. Running a test registration and completing payment
2. Checking the Laravel logs at `storage/logs/laravel.log` for email sending status
3. Verifying that the confirmation email is received

## Troubleshooting

### Email Not Sending

- Verify your app password is correct (no spaces)
- Ensure 2-Step Verification is enabled
- Check that `MAIL_ENCRYPTION` is set to `tls` (not `ssl`)
- Verify `MAIL_PORT` is `587` (for TLS) or `465` (for SSL)

### Email Going to Spam

- Ensure `MAIL_FROM_ADDRESS` matches your Gmail address
- Use a professional `MAIL_FROM_NAME`
- Consider setting up SPF/DKIM records for your domain (if using custom domain)

## Alternative: Using Mailtrap for Development

For local development, you can use Mailtrap:

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-mailtrap-username
MAIL_PASSWORD=your-mailtrap-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@miscon26.co.zw
MAIL_FROM_NAME="MISCON26"
```

## Email Template

The registration confirmation email template is located at:
`resources/views/emails/registration-confirmation.blade.php`

You can customize this template to match your branding and requirements.
