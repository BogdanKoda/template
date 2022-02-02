<?php

namespace app\controllers;

use app\components\dto\DTO;
use app\components\Exceptions\ModelException;
use app\components\Response;
use app\components\services\UsersService;
use Yii;
use yii\base\Exception as yiiException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UnprocessableEntityHttpException;

class UsersController extends CrudController
{

    /**
     * @route 'GET users/me'
     * @secure user, admin
     *
     * @throws NotFoundHttpException
     */
    public function me(): array
    {
        return Response::success($this->service()->show(Yii::$app->user->getId()))->return();
    }

    /**
     * @route 'POST users/logo'
     * @secure user
     *
     * @throws UnprocessableEntityHttpException
     * @throws NotFoundHttpException
     * @throws ModelException
     */
    public function logo(): array
    {
        return $this->update(Yii::$app->user->identity->getId());
    }

    /**
     * @route 'POST /users/login'
     * @throws ForbiddenHttpException
     * @throws yiiException
     */
    public function login(): array
    {
        return Response::success($this->service()->login(DTO::handle($this->dto, $this->post)))->return();
    }

    public function service(): UsersService
    {
        return Yii::$container->get(UsersService::class);
    }
}
