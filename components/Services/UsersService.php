<?php

namespace app\components\Services;

use app\components\Auth\JWT\JWTToken;
use app\components\Auth\UserAuthData;
use app\components\Decorators\CrudActionCachedDecorator;
use app\components\Decorators\CrudActionsImpl;
use app\components\Decorators\CrudActionsService;
use app\components\dto\DTO;
use app\components\dto\UsersDTO;
use app\components\Strategy\BasicSave;
use app\components\Strategy\SaveStrategy;
use app\components\Uploaders\PhotoUploadData;
use app\models\extend\BaseModel;
use app\models\Users;
use app\models\UsersPhotos;
use Yii;
use yii\base\Exception as yiiException;
use yii\web\ForbiddenHttpException;

class UsersService extends CrudService
{

    private RolesService $rolesService;
    private UsersRefreshService $usersRefreshService;

    public function __construct(RolesService $rolesService, UsersRefreshService $usersRefreshService)
    {
        $this->rolesService = $rolesService;
        $this->usersRefreshService = $usersRefreshService;
        parent::__construct();
    }

    public function model(): BaseModel
    {
        return new Users();
    }

    public function strategy(): SaveStrategy
    {
        return new BasicSave($this->model(), $this->oneToManyImages());
    }

    public function crudService(): CrudActionsImpl
    {
        return new CrudActionCachedDecorator(new CrudActionsService($this->strategy()));
    }

    public function crudDto(): string
    {
        return UsersDTO::class;
    }

    public function oneToManyImages(): array
    {
        return [
            new PhotoUploadData(
                new UsersPhotos(),
                "photo",
                "userId"
            )
        ];
    }


    /**
     * @throws ForbiddenHttpException
     * @throws yiiException
     */
    public function login(DTO $dto): array
    {
        /** @var Users $user */
        if($user = $this->model()::findOne($dto->toArray())) {
            $roles = $this->rolesService->parseRoles($user->roles ?? []);
            $userAuth = new UserAuthData($user->id, $roles);

            Yii::$app->user->setIdentity($userAuth);

            return [
                "accessToken" => JWTToken::generate($userAuth),
                "refreshToken" => $this->usersRefreshService->newRefreshToken($user->id)
            ];
        }

        throw new ForbiddenHttpException("Неверный логин или пароль");
    }
}