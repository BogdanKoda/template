<?php

namespace app\components\Auth;

use yii\web\IdentityInterface;

// Данные текущего пользователя
class UserAuthData implements IdentityInterface
{
    // ID пользователя
    private ?int $id;
    // Права доступа пользователя
    private ?array $roles;

    public function __construct(?int $id = null, ?array $roles = null)
    {
        $this->id = $id;
        $this->roles = $roles;
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getRoles(): ?array
    {
        return $this->roles;
    }





    public static function findIdentity($id)
    {
        return null;
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    public function getAuthKey()
    {
        return null;
    }

    public function validateAuthKey($authKey)
    {
        return null;
    }
}