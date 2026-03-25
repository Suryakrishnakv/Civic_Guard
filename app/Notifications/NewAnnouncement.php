<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewAnnouncement extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $announcement;
    public $channels = [];

    /**
     * Create a new notification instance.
     */
    public function __construct($announcement)
    {
        $this->announcement = $announcement;
    }

    public function setChannels($channels)
    {
        $this->channels = $channels;
        return $this;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return $this->channels;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Announcement: ' . $this->announcement->title)
            ->line($this->announcement->message)
            ->action('View Dashboard', route('dashboard'))
            ->line('Thank you for staying connected!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->announcement->title,
            'message' => $this->announcement->message,
            'user_id' => $this->announcement->user_id,
        ];
    }
}
