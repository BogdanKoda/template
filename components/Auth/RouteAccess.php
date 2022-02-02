<?php

namespace app\components\Auth;

use app\components\Routing\Route;
use Exception;
use yii\web\ForbiddenHttpException;

class RouteAccess
{
    // Указываем права доступа для методов
    public array $permissions = [
        'POST /users' => ["admin"],
        'POST /users/{id}' => ["admin"],
        'DELETE /users/{id}' => ["admin"],
    ];

    /**
     * @throws ForbiddenHttpException
     */
    public function isAccess(AccessData $accessData): bool
    {
        $route = Route::handle()->getUsedMethod();
        // Получаем список доступных ролей для этого метода
	    preg_match('!^([A-Z]+) ([A-Za-z0-9/{}\-]+)$!', $route, $matches);
		$httpVerb = $matches[1] ?? "";
	    $url = trim($matches[2] ?? "", "/");
		$routes = [
			$httpVerb." ".$url,
			$httpVerb." /".$url,
			$httpVerb." /".$url."/",
			$httpVerb." ".$url."/"
		];
        $routePermissions = "*";
		foreach ($routes as $r) {
			if(isset($this->permissions[$r])) {
				$routePermissions = $this->permissions[$r];
				break;
			}
		}
		
		
        if($routePermissions == "*") {
            return true;
        } else {
            foreach ($accessData->getRoles() as $role) {
                // Проверяем совпадение ролей юзера с разрешенными полями для текущего метода
                if(array_search($role, $routePermissions) !== false) {
                    return true;
                }
            }
        }

        throw new ForbiddenHttpException("Доступ запрещён.");
    }

    public function addPermission(string $route, array $roles)
    {
        $this->permissions[$route] = $roles;
    }


    private static ?self $_instance = null;
    public static function handle(): self
    {
        if(is_null(self::$_instance)) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }


    private function __construct() {}
    private function __clone() {}
    /**
     * @throws Exception
     */
    public function __wakeup()
    {
        throw new Exception("Cannot unserializable a singleton.");
    }


}