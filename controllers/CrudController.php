<?php

namespace app\controllers;

use app\components\Exceptions\ModelException;
use app\components\Response;
use app\components\Services\CrudService;
use yii\web\NotFoundHttpException;
use yii\web\UnprocessableEntityHttpException;

abstract class CrudController extends ApiController
{
	abstract public function service(): CrudService;

    public function index(int $offset = 0, ?int $limit = null): array
    {
		return Response::success($this->service()->index($offset, $limit))->return();
    }

    /**
     * @throws NotFoundHttpException
     */
    public function show($id): array
    {
        return Response::success($this->service()->show($id))->return();
    }

    public function destroy(int $id): array
    {
        return Response::status($this->service()->destroy($id))->return();
    }

    /**
     * @throws UnprocessableEntityHttpException
     * @throws ModelException
     */
    public function store(): array
    {
        return Response::success($this->service()->store())->return();
    }

    /**
     * @throws UnprocessableEntityHttpException
     * @throws NotFoundHttpException
     * @throws ModelException
     */
    public function update(int $id): array
    {
        return Response::success($this->service()->update($id))->return();
    }


}