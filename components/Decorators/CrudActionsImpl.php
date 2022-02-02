<?php

namespace app\components\Decorators;

use app\components\Exceptions\ModelException;
use app\components\Strategy\SaveStrategy;
use app\models\extend\BaseModel;
use yii\web\NotFoundHttpException;
use yii\web\UnprocessableEntityHttpException;

interface CrudActionsImpl
{
    /**
     * @throws ModelException
     * @throws UnprocessableEntityHttpException
     */
	public function create(object $data): BaseModel;

    /**
     * @throws ModelException
     * @throws UnprocessableEntityHttpException
     * @throws NotFoundHttpException
     */
	public function update(array $filter, object $data): BaseModel;

	public function delete(array $filter): bool;
	public function list(array $filter, array $sort): array;

    /**
     * @throws NotFoundHttpException
     */
	public function get(array $filter): BaseModel;

    public function getStrategy(): SaveStrategy;
    public function setStrategy(SaveStrategy $strategy): void;
}