<?php

namespace app\components\Notifications;

use app\components\HttpQueryBuilder;
use app\models\UsersPushTokens;

class SendNotifications
{
    const API_HOST = 'https://exp.host/--/api/v2/push/send';

    public function __invoke(NotificationsData $data)
    {

        $pushTokenList = $this->getPushTokens($data->getUserId());
        if(!empty($pushTokenList)) {

            $data = [
                "to" => $pushTokenList,
                "title" => $data->getTitle(),
                "body" => $data->getBody(),
                "data" => $data->getData()
            ];

            HttpQueryBuilder::url(self::API_HOST)
                ->setMaxRedirs(10)
                ->followLocation(true)
                ->setTypeRequest(HttpQueryBuilder::POST)
                ->withSslVerify(false)
                ->withBody($data)
                ->addHeader("Content-Type", "text/plain")
                ->build()
                ->execute();

        }

    }

    private function getPushTokens(int $userId): array
    {
        $tokens = UsersPushTokens::find()->select("pushToken")->where(["userId" => $userId])->all();
        $pushTokenList = [];

        /** @var UsersPushTokens $token */
        foreach ($tokens as $token) {
            $pushTokenList[] = $token->pushToken;
        }
        return $pushTokenList;
    }
}