<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PortalNotifications extends Notification
{
    use Queueable;
    protected $NotificationData;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($NotificationData)
    {
        //
        $this->NotificationData = $NotificationData;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via(mixed $notifiable): array
    {
//        return ['mail','database'];
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
//    public function toMail($notifiable): MailMessage
//    {
//        return (new MailMessage)
//                    ->line('The introduction to the notification.')
//                    ->action('Notification Action', url('/'))
//                    ->line('Thank you for using our application!');
//    }

    public function toDatabase(mixed $notifiable): array
    {
        return [
            'Order_ID' => $this->NotificationData['Order_ID']?? null,
            'Role_Name' => $this->NotificationData['Role_Name']?? null,
            'Emp_ID' => $this->NotificationData['Emp_ID']?? null,
            'Message' => $this->NotificationData['Message'],
            'Play_Sound' => true,
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray(mixed $notifiable): array
    {
        return [
            //
        ];
    }
}
