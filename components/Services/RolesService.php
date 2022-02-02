<?php

namespace app\components\Services;

use app\models\Roles;

class RolesService
{

    public function parseRoles(array $userRoles): array
    {
        $result = [];
        /** @var Roles $role */
        foreach ($userRoles as $role) {
            $result[] = $role->code;
        }
        return $result;
    }

}