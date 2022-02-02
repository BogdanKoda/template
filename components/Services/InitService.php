<?php

namespace app\components\Services;

use app\models\Roles;
use app\models\Users;
use app\models\UsersRoles;
use Yii;
use yii\console\ExitCode;
use yii\db\Exception;

class InitService
{
    private Users $users;
    private Roles $roles;
    private UsersRoles $usersRoles;

    public function __construct(Users $users, Roles $roles, UsersRoles $usersRoles){
        $this->users = $users;
        $this->roles = $roles;
        $this->usersRoles = $usersRoles;
    }

    // Создаем админа из консоли

    /**
     * @throws Exception
     */
    public function addAdmin(): int
    {

        $transaction = Yii::$app->db->beginTransaction();

        $this->users->firstName = "Admin";
        $this->users->phoneNumber = "+78005553535";
        $this->users->password = "123123";

        if (!$this->users->save()) {
            $transaction->rollBack();
            print_r($this->users->getErrors());
            return ExitCode::DATAERR;
        }

        $this->usersRoles->userId = $this->users->id;
        $this->usersRoles->roleId = $this->roles::findOne(["name" => "admin"])->id ?? 1;
        if(!$this->usersRoles->save()) {
            $transaction->rollBack();
            print_r($this->usersRoles->getErrors());
            return ExitCode::DATAERR;
        }

        $transaction->commit();
        echo 'ok';
        return ExitCode::OK;
    }

    // Инициализируем роли для пользователей
    public function initializeRoles(): int
    {
        $roles = [
            'admin' => 'Администратор',
            'content' => 'Редактор',
            'moderator' => 'Модератор',
            'user' => 'Пользователь',
        ];

        $transaction = Yii::$app->db->beginTransaction();
        foreach ($roles as $code => $name) {
            $this->roles = new $this->roles();
            $this->roles->name = $name;
            $this->roles->code = $code;
            $this->roles->save();
        }

        try {
            $transaction->commit();
            return ExitCode::OK;
        } catch (Exception $e) {
            print_r($e);
            return ExitCode::DATAERR;
        }

    }


}