<?php

namespace App\Http\Livewire\Notification;

use App\Helpers\PortalHelpers;
use Carbon\Carbon;
use Livewire\Component;

class UserPortalNotifications extends Component
{
    public function render()
    {
        $portalNotification = PortalHelpers::getPortalNotification();

        $allNotifications = $portalNotification['Notifications'];
        $notificationsCount = $portalNotification['NotificationsCount'];

        $notifications = $allNotifications->map(function ($notify) {
            $data = json_decode($notify->data, true, 512, JSON_THROW_ON_ERROR);
            $route = (PortalHelpers::getOrderType($data['Order_ID']) === 1) ? route('Order.Details', ['Order_ID' => $data['Order_ID']]) : route('Content.Order.Details', ['Order_ID' => $data['Order_ID']]);
            $senderName = PortalHelpers::notificationSenderName($data['Role_Name']);
            return [
                'Emp_ID' => $data['Emp_ID'],
                'Order_ID' => $data['Order_ID'],
                'Role_Name' => $data['Role_Name'],
                'Message' => $data['Message'],
                'read_at' => $notify->read_at,
                'created_at' => Carbon::parse($notify->created_at),
                'route' => $route,
                'Sender' => $senderName,
                'id' => $notify->id
            ];
        });

        return view('livewire.notification.user-portal-notifications', compact('notifications', 'notificationsCount'))->layout('layouts.authorized');
    }
}
