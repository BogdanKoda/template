<?php

namespace app\components\Services;

use app\components\Decorators\CrudActionsImpl;
use app\components\dto\DTO;
use app\components\Exceptions\ModelException;
use app\components\Strategy\SaveStrategy;
use app\models\extend\BaseModel;
use Yii;
use yii\base\InvalidConfigException;
use yii\web\NotFoundHttpException;
use yii\web\UnprocessableEntityHttpException;

abstract class CrudService
{
    protected array $post;

    public function oneToManyImages(): array
    {
        return [];
    }
    abstract public function model(): BaseModel;
    abstract public function strategy(): SaveStrategy;
    abstract public function crudService(): CrudActionsImpl;
    abstract public function crudDto(): string;

    /**
     * @throws InvalidConfigException
     */
    public function __construct()
    {
        $this->post = Yii::$app->request->getBodyParams();
    }

    /**
     * @throws ModelException
     * @throws UnprocessableEntityHttpException
     */
    public function store(): BaseModel
    {
        $data = DTO::handle($this->crudDto(), $this->post);
        return $this->crudService()->create($data);
    }

    /**
     * @throws ModelException
     * @throws NotFoundHttpException
     * @throws UnprocessableEntityHttpException
     */
    public function update(int $id): BaseModel
    {
        $data = DTO::handle($this->crudDto(), $this->post);
        return $this->crudService()->update(["id" => $id], $data);
    }

    public function destroy(int $id): bool
    {
        return $this->crudService()->delete(["id" => $id]);
    }

    public function index(int $offset = 0, ?int $limit = null): array
    {
        return $this->crudService()->list(
            [],
            [
                "offset" => $offset,
                "limit" => $limit,
                "orderBy" => $this->orderBy ?? null
            ]
        );
    }

    /**
     * @throws NotFoundHttpException
     */
    public function show($id): BaseModel
    {
        return $this->crudService()->get(["id" => $id]);
    }




}