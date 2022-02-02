<?php

namespace app\components\Auth;

use app\components\Auth\JWT\JWTToken;
use DomainException;
use UnexpectedValueException;
use Yii;
use yii\filters\auth\AuthInterface;
use yii\filters\auth\AuthMethod;
use yii\web\ForbiddenHttpException;
use yii\web\UnauthorizedHttpException;

class Auth extends AuthMethod implements AuthInterface
{
    /**
     * @throws UnauthorizedHttpException
     * @throws ForbiddenHttpException
     */
    public function authenticate($user, $request, $response)
    {
        // Получаем заголовок авторизации
        $headers = Yii::$app->request->getHeaders();
        $authorization = $headers["authorization"] ?? null;

        $roles = [];
        $userId = null;

        // Пытаемся вытащить токен
        if(!is_null($authorization)) {
            $authorization = explode(" ", $authorization);
            if(isset($authorization[0]) && $authorization[0] === "Bearer" && isset($authorization[1])) {
                try {
                    // Присланный токен
                    $jwt = $authorization[1];

                    // Информация из токена
                    $tokenData = JWTToken::parse($jwt);

                    if(isset($tokenData->userId) && isset($tokenData->roles)) {
                        $userId = $tokenData->userId;
                        $roles = $tokenData->roles;
                    } else {
                        throw new UnauthorizedHttpException("Неверный токен");
                    }

                } catch (UnexpectedValueException | DomainException $e) {
                    throw new UnauthorizedHttpException($e->getMessage());
                }
            } else {
                throw new UnauthorizedHttpException("Неверный формат заголовка авторизации");
            }
        }

        $userData = new UserAuthData($userId, $roles);
        Yii::$app->user->setIdentity($userData);

        // Проверка прав
        $routeAccess = RouteAccess::handle()->isAccess(new AccessData($roles));
        if($routeAccess) {
            if(!is_null($userId)) {
                return new $userData;
            }
        }

        return $userData;
    }
}