<?php

namespace App\Notifications;

use App\Models\TravelOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TravelOrderStatusNotification extends Notification
{
    use Queueable;

    protected $travelOrder;
    protected $oldStatus;
    protected $newStatus;

    /**
     * Create a new notification instance.
     */
    public function __construct(TravelOrder $travelOrder, $oldStatus, $newStatus)
    {
        $this->travelOrder = $travelOrder;
        $this->oldStatus   = $oldStatus;
        $this->newStatus   = $newStatus;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $orderId   = $this->travelOrder->id;
        $oldStatus = $this->oldStatus;
        $newStatus = $this->newStatus;

        return (new MailMessage)
            ->subject("Travel Order #{$orderId} Status Updated")
            ->greeting("Hello, {$notifiable->name}")
            ->line("Your travel order #{$orderId} status changed from {$oldStatus} to {$newStatus}.")
            ->action('View Order', url("/travel-orders/{$orderId}"))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }

    public function toDatabase($notifiable)
    {
        return [
            'travel_order_id' => $this->travelOrder->id,
            'old_status'      => $this->oldStatus,
            'new_status'      => $this->newStatus,
            'changed_at'      => now(),
        ];
    }
}
