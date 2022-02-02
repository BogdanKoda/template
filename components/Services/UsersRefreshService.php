<?php

namespace app\components\Services;

use app\components\Helpers;
use app\models\UsersRefresh;
use Yii;
use yii\base\Exception as yiiException;

class UsersRefreshService
{

    /**
     * @throws yiiException
     */
    public function newRefreshToken(int $userId): string
    {
        $refreshToken = Helpers::generateToken();

        $refreshTokenCount = UsersRefresh::find()->where(["userId" => $userId])->count();
        $deleteRefresh = $refreshTokenCount - Yii::$app->params["maxRefreshToken"] + 1;
        $transaction = Yii::$app->db->beginTransaction();

        if($deleteRefresh > 0) {
            $deletedRefresh = UsersRefresh::find()
                ->select("id")
                ->limit($deleteRefresh)
                ->orderBy("expiredAt ASC")
                ->all();

            $refreshDeleteIDs = [];
            foreach ($deletedRefresh as $refresh) {
                if(isset($refresh->id)) {
                    $refreshDeleteIDs[] = $refresh->id;
                }
            }

            UsersRefresh::deleteAll(["id" => $refreshDeleteIDs]);
        }

        $refreshModel = new UsersRefresh();
        $refreshModel->userId = $userId;
        $refreshModel->refreshToken = $refreshToken;
        $refreshModel->expiredAt = time() + Yii::$app->params['refreshTokenExpiredAt'];
        $refreshModel->save();

        $transaction->commit();

        return $refreshToken;

    }

}