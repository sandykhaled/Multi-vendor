<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderCreatedNotification extends Notification
{
    use Queueable;

    protected $order;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database','broadcast'];

//        $channels = ['database'];
//        if($notifiable->notificatopn_preferences['order_created']['sms'] ?? false){
//            $channels[] = 'vonage';
//        }
//        if($notifiable->notification_preferences['order_created']['mail'] ?? false){
//            $channels[] = 'mail';
//        }
//        if($notifiable->notification_preferences['order_created']['broadcast'] ?? false){
//            $channels[] = 'broadcast';
//        }
//        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $addr = $this->order->billingAddress;
        return (new MailMessage)
                    ->subject("New Order # {$this->order->number}")
                    ->from('notification@store.com','Store')
                    ->greeting("Hi {{$notifiable->name}}")
                    ->line("New Order # ({$this->order->number}) created by ".$addr->name)
                    ->action('View Order', url('/dashboard'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'body'=>"New Order # {$this->order->number}",
            'icon'=>'fas fa-file',
            'url'=>url('dashboard'),
            'order_id'=>$this->order->id
        ];
    }
    public function toBroadcast(object $notifiable): array
    {
        return [
            'body'=>"New Order # {$this->order->number}",
            'icon'=>'fas fa-file',
            'url'=>url('dashboard'),
            'order_id'=>$this->order->id
        ];
    }
}
