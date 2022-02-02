<?php

namespace app\components\Notifications;

use app\components\EventData\SmsData;

class SmsNotifications
{

    public function __invoke(SmsData $data)
    {
        // TODO: отправка сообщения на SMS
        //var_dump($data); die();
    }

}