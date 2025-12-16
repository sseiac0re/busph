<?php

namespace App\Notifications;

use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class VerifyEmail extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        // Attempt Brevo API send; fall back to default mail channel for compatibility
        $this->sendViaBrevo($notifiable, $verificationUrl);

        return (new MailMessage)
            ->subject('Verify your BusPH Account')
            ->greeting('Welcome to BusPH!')
            ->line('Please click the button below to verify your email address and start booking your trips.')
            ->action('Verify Email Address', $verificationUrl)
            ->line('If you did not create an account, no further action is required.')
            ->salutation('Safe travels, The BusPH Team');
    }

    /**
     * Send verification email via Brevo API.
     * Swallow errors so the notification fallback still works.
     */
    protected function sendViaBrevo($notifiable, string $verificationUrl): void
    {
        $apiKey = env('BREVO_API_KEY');
        $senderEmail = env('BREVO_SENDER_EMAIL');
        $senderName = env('BREVO_SENDER_NAME', 'BusPH');
        $debugEnabled = env('BREVO_DEBUG', false);

        if (empty($apiKey) || empty($senderEmail)) {
            if ($debugEnabled) {
                Log::info('Brevo skipped: missing api key or sender', [
                    'has_api_key' => !empty($apiKey),
                    'has_sender' => !empty($senderEmail),
                ]);
            }
            return;
        }

        try {
            $client = new Client([
                'base_uri' => 'https://api.brevo.com/v3/',
                'timeout' => 10,
                'http_errors' => false, // let us inspect non-2xx without throwing
                'headers' => [
                    'api-key' => $apiKey,
                    'accept' => 'application/json',
                    'content-type' => 'application/json',
                ],
            ]);

            $response = $client->post('smtp/email', [
                'json' => [
                    'sender' => [
                        'email' => $senderEmail,
                        'name' => $senderName,
                    ],
                    'to' => [
                        [
                            'email' => $notifiable->email,
                            'name' => $notifiable->name ?? '',
                        ],
                    ],
                    'subject' => 'Verify your BusPH Account',
                    'htmlContent' => "<p>Hi {$notifiable->name},</p><p>Please verify your email to activate your account.</p><p><a href=\"{$verificationUrl}\" style=\"display:inline-block;padding:10px 16px;background:#0f172a;color:#fff;text-decoration:none;border-radius:6px;\">Verify Email</a></p><p>Or copy this link: {$verificationUrl}</p>",
                    'textContent' => "Hi {$notifiable->name},\n\nVerify your email: {$verificationUrl}",
                ],
            ]);

            $status = $response->getStatusCode();
            $body = (string) $response->getBody();

            if ($status >= 200 && $status < 300) {
                if ($debugEnabled) {
                    Log::info('Brevo send attempt succeeded', [
                        'email' => $notifiable->email,
                        'status' => $status,
                    ]);
                }
            } else {
                Log::warning('Brevo verification email returned non-success', [
                    'email' => $notifiable->email ?? null,
                    'status' => $status,
                    'body' => $body,
                ]);
                // Fallback: log the verification URL for manual delivery
                Log::notice('Verification URL (fallback due to Brevo failure)', [
                    'email' => $notifiable->email ?? null,
                    'url' => $verificationUrl,
                ]);
            }
        } catch (\Throwable $e) {
            Log::warning('Brevo verification email failed', [
                'error' => $e->getMessage(),
                'email' => $notifiable->email ?? null,
            ]);
            // Fallback: log the verification URL for manual delivery
            Log::notice('Verification URL (fallback due to Brevo exception)', [
                'email' => $notifiable->email ?? null,
                'url' => $verificationUrl,
            ]);
        }
    }

    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }
}