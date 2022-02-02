<?php

namespace app\components\Strategy;

use app\components\Exceptions\ModelException;
use app\models\extend\BaseModel;
use yii\web\UnprocessableEntityHttpException;

interface SaveStrategy
{

    /**
     * @throws ModelException
     * @throws UnprocessableEntityHttpException
     */
    public function handle(object $data): BaseModel;

    public function setData(array $data): void;
    public function getModel(): BaseModel;
    public function setModel(BaseModel $model): void;

}