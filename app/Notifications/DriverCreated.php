<?php

namespace App\Notifications;

use App\Models\Driver;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DriverCreated extends Notification implements ShouldQueue
{
    use Queueable;

    public $driver;

    public function __construct(Driver $driver)
    {
        $this->driver = $driver;
    }

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Driver Added - LoadMasta')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('A new driver has been added to the system.')
            ->line('**Driver Details:**')
            ->line('Name: ' . $this->driver->full_name)
            ->line('Email: ' . $this->driver->email)
            ->line('License: ' . $this->driver->license_number)
            ->line('Transporter: ' . $this->driver->transporter->company_name)
            ->action('View Driver Details', route('admin.drivers.show', $this->driver))
            ->line('Please review the driver information and verify their documents.')
            ->salutation('Best regards, LoadMasta Team');
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => 'New Driver Added',
            'message' => 'Driver ' . $this->driver->full_name . ' has been created successfully.',
            'action_url' => route('admin.drivers.show', $this->driver),
            'type' => 'driver_created',
            'driver_id' => $this->driver->id,
            'transporter_id' => $this->driver->transporter_id,
            'priority' => 'medium'
        ];
    }
} 