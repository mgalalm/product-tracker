<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Stock;

class ImportantStockUpdate extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected Stock $stock, public Stock $oldStock, public string $type)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        // TODO personalize the email to the product of the user
        return (new MailMessage)
            ->subject('Important Stock Update for ' . $this->stock->products->first()->name)
            ->lineIf($this->type == 'in_stock', 'The product you have been tracking is now in stock!')
            ->lineIf($this->type == 'price_dropped', 'The product you have been tracking has a price drop!')
            // add the stock details as list
            ->line('The stock details are:')
            ->line('Name: ' . $this->stock->products->first()->name)
            // if the type is price_dropped, show  both the old and new price
            ->lineIf($this->type == 'price_dropped', 'Old Price: ' . $this->oldStock->formatted_price)
            ->line('Price: ' . $this->stock->formatted_price)
            ->line('In Stock: ' . ($this->stock->in_stock ? 'Yes' : 'No'))
                    ->action('Buy It Now', url($this->stock->url))
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
}
