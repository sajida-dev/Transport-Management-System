<?php

namespace App\Notifications;

use App\Models\KycVerification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class KycDocumentSubmitted extends Notification implements ShouldQueue
{
    use Queueable;

    public $kycVerification;

    public function __construct(KycVerification $kycVerification)
    {
        $this->kycVerification = $kycVerification;
    }

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $verifiable = $this->kycVerification->verifiable;
        $entityName = $verifiable->full_name ?? $verifiable->company_name ?? 'Unknown';

        return (new MailMessage)
            ->subject('KYC Document Submitted - LoadMasta')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('A new KYC document has been submitted for review.')
            ->line('**Document Details:**')
            ->line('Entity: ' . $entityName)
            ->line('Document Type: ' . ucfirst(str_replace('_', ' ', $this->kycVerification->document_type)))
            ->line('Document Number: ' . ($this->kycVerification->document_number ?? 'N/A'))
            ->line('Submitted: ' . $this->kycVerification->created_at->format('M d, Y H:i'))
            ->action('Review Document', route('admin.kyc.show', $this->kycVerification))
            ->line('Please review the document and approve or reject it.')
            ->salutation('Best regards, LoadMasta Team');
    }

    public function toArray($notifiable): array
    {
        $verifiable = $this->kycVerification->verifiable;
        $entityName = $verifiable->full_name ?? $verifiable->company_name ?? 'Unknown';

        return [
            'title' => 'KYC Document Submitted',
            'message' => 'New KYC document submitted by ' . $entityName . ' for review.',
            'action_url' => route('admin.kyc.show', $this->kycVerification),
            'type' => 'kyc_document_submitted',
            'kyc_verification_id' => $this->kycVerification->id,
            'verifiable_type' => $this->kycVerification->verifiable_type,
            'verifiable_id' => $this->kycVerification->verifiable_id,
            'document_type' => $this->kycVerification->document_type,
            'priority' => 'high'
        ];
    }
} 