<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\AndroidConfig;
use NotificationChannels\Fcm\Resources\AndroidFcmOptions;
use NotificationChannels\Fcm\Resources\AndroidNotification;

class SendWMA extends Notification
{
    protected $arrWMA;

    public function __construct(array $arrWMA)
    {
        $this->arrWMA = $arrWMA;
    }

    public function via($notifiable)
    {
        return [FcmChannel::class];
    }

    public function toFcm($notifiable)
    {
        return FcmMessage::create()->setData(['fcmtype'=>'wma', 'title'=>$this->arrWMA['title'], 'body'=>$this->arrWMA['body'], 'unixdelay'=>$this->arrWMA['delay']]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function fcmProject($notifiable, $message)
    {
        return 'app';
    }
}
