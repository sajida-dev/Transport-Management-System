<?php

namespace App\Notifications;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvoiceOverdue extends Notification implements ShouldQueue
{
    use Queueable;

    public $invoice;

    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $daysOverdue = now()->diffInDays($this->invoice->due_date);

        return (new MailMessage)
            ->subject('Invoice Overdue - LoadMasta')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('An invoice is overdue and requires immediate attention.')
            ->line('**Invoice Details:**')
            ->line('Invoice Number: ' . $this->invoice->invoice_number)
            ->line('Amount: $' . number_format($this->invoice->total_amount, 2))
            ->line('Due Date: ' . $this->invoice->due_date->format('M d, Y'))
            ->line('Days Overdue: ' . $daysOverdue . ' days')
            ->line('Transporter: ' . $this->invoice->transporter->company_name)
            ->action('View Invoice', route('admin.invoices.show', $this->invoice))
            ->line('Please contact the transporter to resolve this payment.')
            ->salutation('Best regards, LoadMasta Team');
    }

    public function toArray($notifiable): array
    {
        $daysOverdue = now()->diffInDays($this->invoice->due_date);

        return [
            'title' => 'Invoice Overdue',
            'message' => 'Invoice ' . $this->invoice->invoice_number . ' is ' . $daysOverdue . ' days overdue.',
            'action_url' => route('admin.invoices.show', $this->invoice),
            'type' => 'invoice_overdue',
            'invoice_id' => $this->invoice->id,
            'invoice_number' => $this->invoice->invoice_number,
            'amount' => $this->invoice->total_amount,
            'days_overdue' => $daysOverdue,
            'priority' => 'urgent'
        ];
    }
} 