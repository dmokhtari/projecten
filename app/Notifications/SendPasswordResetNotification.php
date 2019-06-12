<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SendPasswordResetNotification extends Notification
{
    use Queueable;

    /**
     * @var string
     */
    public $token;

    /**
     * SendPasswordResetNotification constructor.
     * @param $token
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Wachtwoord herstellen')
            ->greeting('Hallo,')
            ->line('Jij krijgt deze mail omdat je onlangs heeft verzocht om het wachtwoord van jouw account op Laravel te wijzigen!')
            ->action('Mijn wachtwoord wijzigen', url('/passwords/reset', $this->token))
            ->line('Als jij dit verzoek niet heeft gemaakt, mag je deze mail als niet verzonden beschouwen!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
