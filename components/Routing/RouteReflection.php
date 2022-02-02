<?php

namespace app\components\Routing;

use app\components\Auth\RouteAccess;
use Exception;
use ReflectionClass;
use ReflectionException;

class RouteReflection
{
    private static ?self $_instance = null;

    private array $controllers;

    public static function handle(): self
    {
        if(self::$_instance == null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
	
	/**
	 * @throws ReflectionException
	 * @throws Exception
	 */
    public function generate(): void
    {
        foreach ($this->getControllers() as $controller) {
            $reflection = new ReflectionClass($controller);

            $methods = $reflection->getMethods();
            foreach ($methods as $method) {
                if($method->isConstructor()) {
                    continue;
                }

                $doc = $method->getDocComment();
                $this->addRoutes($doc, $method, $reflection, $controller);
            }
        }
    }

    /**
     * @throws Exception
     */
    private function addRoutes($doc, $method, $reflection, $controller)
    {
        preg_match_all('!@route[ ]*\'([A-Z]+) [/]*([a-zA-Z0-9{}\-]+)[/]*([a-zA-Z0-9\-/{}]*)\'!', $doc, $route);

        if (isset($route[1]) && isset($route[2])) {
            foreach ($route[1] as $index => $verb) {
                $controllerName = $route[2][$index] ?? "";
                $controllerName = explode("-", $controllerName);
                foreach ($controllerName as $i => $value) {
                    $controllerName[$i] = ucfirst($value);
                }
                $controllerName = implode("", $controllerName);
                $controllerName = ucfirst($controllerName) . "Controller";

                if($reflection->getShortName() != $controllerName) {
                    continue;
                }
                $url = $route[2][$index] . "/" . ($route[3][$index] ?? "");

                preg_match('!@secure ([a-zA-Z0-9\-_ ,]+)!', $doc, $roles);
                if(isset($roles[1])) {
                    $roles = explode(",", $roles[1]);

                    foreach ($roles as $roleIndex => $role) {
                        $roles[$roleIndex] = trim($role, " ");
                    }

                    $src = $verb . " " . $route[2][$index] . "/" . $route[3][$index] ?? "";
                    $src = trim($src, "/");
                    RouteAccess::handle()->addPermission($src, $roles);
                }

                switch ($verb) {
                    case 'GET':
                        Route::get('/' . $url, [$controller, $method->getName()]);
                        break;
                    case 'POST':
                        Route::post('/' . $url, [$controller, $method->getName()]);
                        break;
                    case 'PATCH':
                        Route::patch('/' . $url, [$controller, $method->getName()]);
                        break;
                    case 'DELETE':
                        Route::delete('/' . $url, [$controller, $method->getName()]);
                        break;
                    case 'PUT':
                        Route::put('/' . $url, [$controller, $method->getName()]);
                        break;
                    default:
                        throw new Exception("Unknown HTTP verb");
                }
            }
        }
    }

    private function __construct(){}
    private function __clone(){}

    /**
     * @throws Exception
     */
    public function __wakeup(): void
    {
        throw new Exception("Cannot un serialize a singleton.");
    }


    /**
     * @return string[]
     */
    public function getControllers(): array
    {
        return $this->controllers;
    }

    /**
     * @param string[] $controllers
     */
    public function setControllers(array $controllers): void
    {
        $this->controllers = $controllers;
    }

}
