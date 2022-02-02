<?php

namespace app\models\extend;

use app\components\Strategy\Helpers;
use yii\db\ActiveRecord;

class BaseModel extends ActiveRecord
{
    public static function listRoles(array $roles): array
    {
        $list = [];
        foreach ($roles as $role) {
            $list[$role->id] = $role->name;
        }
        return $list;

    }

    public function hashPassword(string $password): string
	{
		return Helpers::hashPassword($password);
	}

    public function parseCodeRoles($roles): array
    {
        return self::parseRoles($roles);
    }

    public static function parseRoles($roles, string $field = "code"): array
    {
        $codes = [];
        foreach ($roles as $role) {
            $codes[] = $role->{$field};
        }

        return $codes;
    }

}