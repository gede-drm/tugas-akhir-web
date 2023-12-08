<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\AndroidConfig;
use NotificationChannels\Fcm\Resources\AndroidFcmOptions;
use NotificationChannels\Fcm\Resources\AndroidNotification;

class SendNotification extends Notification
{
    protected $arrNotif;

    public function __construct(array $arrNotif)
    {
        $this->arrNotif = $arrNotif;
    }

    // public function via($notifiable)
    // {
    //     return [SendNotification::class];
    // }

    public function toFcm($notifiable)
    {
        return FcmMessage::create()
            ->setNotification(\NotificationChannels\Fcm\Resources\Notification::create()
                ->setTitle($this->arrNotif['title'])
                ->setBody($this->arrNotif['body']))
            ->setAndroid(
                AndroidConfig::create()
                    ->setFcmOptions(AndroidFcmOptions::create()->setAnalyticsLabel('analytics'))
                    ->setNotification(AndroidNotification::create()->setColor('#002147'))
            );
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function fcmProject($notifiable, $message)
    {
        return 'apartemenku-a2861';
    }
}
