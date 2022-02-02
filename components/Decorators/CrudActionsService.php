<?php

namespace app\components\Decorators;

use app\components\Exceptions\ModelException;
use app\components\Strategy\SaveStrategy;
use app\models\extend\BaseModel;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\UnprocessableEntityHttpException;

class CrudActionsService implements CrudActionsImpl
{
    public SaveStrategy $crudStrategy;

	public function __construct(SaveStrategy $strategy){
        $this->crudStrategy = $strategy;
	}

    /**
     * @param SaveStrategy $strategy
     */
    public function setStrategy(SaveStrategy $strategy): void
    {
        $this->crudStrategy = $strategy;
    }

    /**
     * @param object $data
     * @return BaseModel
     * @throws ModelException
     * @throws UnprocessableEntityHttpException
     */
	public function create(object $data): BaseModel
	{
		return $this->crudStrategy->handle($data);
	}

    /**
     * @param array $filter
     * @param object $data
     * @return BaseModel
     * @throws ModelException
     * @throws NotFoundHttpException
     * @throws UnprocessableEntityHttpException
     */
	public function update(array $filter, object $data): BaseModel
	{
        $model = $this->crudStrategy->getModel()::findOne($filter["id"]);
        if(is_null($model)) {
            throw new NotFoundHttpException("Resource Not Found");
        }

        $this->crudStrategy->setModel($model);
		return $this->crudStrategy->handle($data);
	}

    /**
     * @param array $filter
     * @return bool
     */
	public function delete(array $filter): bool
	{
        return $this->crudStrategy->getModel()::deleteAll($filter) == 1;
	}

    /**
     * @param array $filter
     * @param array $sort
     * @return array
     */
	public function list(array $filter, array $sort): array
	{
		return $this->crudStrategy->getModel()::find()
            ->where($filter)->offset($sort["offset"] ?? 0)
            ->limit($sort["limit"] ?? Yii::$app->params["limit"])
            ->orderBy($sort["orderBy"] ?? "id DESC")
            ->all();
	}

    /**
     * @param array $filter
     * @return BaseModel
     * @throws NotFoundHttpException
     */
    public function get(array $filter): BaseModel
	{
		$object = $this->crudStrategy->getModel()::findOne($filter);
        if(is_null($object)) {
            throw new NotFoundHttpException("Resource Not Found");
        }
        return $object;
	}

    /**
     * @return SaveStrategy
     */
    public function getStrategy(): SaveStrategy
    {
        return $this->crudStrategy;
    }
}