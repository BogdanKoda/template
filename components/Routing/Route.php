<?php

namespace app\components\Routing;

use Exception;

class Route
{

    private static ?self $_instance = null;

    private array $routes = [];

	/**
	 * @param string $url
	 * @param string[] $method
	 */
	public static function get(string $url, array $method)
	{
		self::route($url, $method, 'GET');
	}
	
	/**
	 * @param string $url
	 * @param string[] $method
	 */
	public static function post(string $url, array $method)
	{
		self::route($url, $method, 'POST');
	}
	
	/**
	 * @param string $url
	 * @param string[] $method
	 */
	public static function put(string $url, array $method)
	{
		self::route($url, $method, 'PUT');
	}
	
	/**
	 * @param string $url
	 * @param string[] $method
	 */
	public static function patch(string $url, array $method)
	{
		self::route($url, $method, 'PATCH');
	}
	
	/**
	 * @param string $url
	 * @param string[] $method
	 */
	public static function delete(string $url, array $method)
	{
		self::route($url, $method, 'DELETE');
	}
	
	/**
	 * @param string $url
	 * @param string[] $method
	 * @param string $verb
	 */
	protected static function route(string $url, array $method, string $verb)
	{
        preg_match('!{([A-Za-z0-9]+)}!', $url, $matches);

		$opt = "";
		$src = $url;
        if(!empty($matches)) {
            $url = preg_replace('/\/{([A-Za-z0-9]+)}/', '', $url);
            unset($matches[0]);
			$opt = "%|OPT";
        }

        self::handle()->routes[$verb . "%|" . $url . $opt] = [
            "url" => $url,
	        "src" => $src,
            "action" => $method,
            "verb" => $verb,
            "params" => $matches,
        ];

	}

    public static function handle(): self
    {
        if(is_null(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
	
	public static function apiResource(string $url, string $controllerName)
	{
		Route::get('/'.$url.'/', [$controllerName, 'index']);
		Route::post('/'.$url.'/', [$controllerName, 'store']);
		Route::post('/'.$url.'/{id}', [$controllerName, 'update']);
		Route::get('/'.$url.'/{id}', [$controllerName, 'show']);
		Route::delete('/'.$url.'/{id}', [$controllerName, 'destroy']);
	}

    public static function adminResource(string $route, string $className)
    {
        Route::get("/admin/$route", [$className, "index"]);
        Route::get("/admin/$route/view", [$className, "view"]);
        Route::get("/admin/$route/update", [$className, "updateView"]);
        Route::post("/admin/$route/update", [$className, "update"]);
        Route::post("/admin/$route/delete", [$className, "delete"]);
        Route::get("/admin/$route/create", [$className, "createView"]);
        Route::post("/admin/$route/create", [$className, "create"]);
    }

    public function find($route): ?array
    {
		$method = $this->routes[$route] ?? null;
		if($method) {
			$this->usedMethod = $method;
		}
        return $method;
    }
	
	private array $usedMethod = [];

	public function getUsedMethod(): string
	{
		return ($this->usedMethod['verb'] ?? "") . " " . ($this->usedMethod['src'] ?? "");
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