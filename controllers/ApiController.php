<?php

namespace app\controllers;

use app\components\Auth\Auth;
use app\components\Exceptions\ModelException;
use app\components\Response;
use Yii;
use yii\base\InvalidConfigException;
use yii\filters\ContentNegotiator;
use yii\filters\Cors;
use yii\rest\ActiveController;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;

class ApiController extends ActiveController
{
	public $modelClass = "";
    public string $dto;

    public array $post = [];

    /**
     * @throws InvalidConfigException
     */
    public function __construct($id, $module, $config = [])
    {
        $this->createDTOName($id[0], $id[1]);
        $this->post = Yii::$app->request->getBodyParams();

        date_default_timezone_set('UTC');
        parent::__construct($id, $module, $config);
    }

    public function runAction($id, $params = [], $isCustomRoute = false)
    {
        try {
            return parent::runAction($id, $params, $isCustomRoute); // TODO: Change the autogenerated stub
        } catch (ModelException $e) {
            return Response::errors($e->getErrors())->return();
        } catch (ForbiddenHttpException $e) {
            return Response::message($e->getMessage())->setCode(403)->return();
        } catch (UnauthorizedHttpException $e) {
            return Response::message($e->getMessage())->setCode(401)->return();
        }
    }


    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        $behaviors['corsFilter'] = [
            'class' => Cors::class,
            'cors' => [
                'Origin' => Yii::$app->params["origins"],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                'Access-Control-Allow-Credentials' => true,
            ],
        ];

        $behaviors['authenticator'] = [
            'class' => Auth::class,
        ];

        $behaviors['contentNegotiator'] = [
            'class'   => ContentNegotiator::class,
            'formats' => [
                'application/json' => yii\web\Response::FORMAT_JSON,
            ],
        ];

        return $behaviors;
    }

    public function actions(): array
    {
        $actions = parent::actions();

        unset($actions['index']);
        unset($actions['create']);
        unset($actions['delete']);
        unset($actions['update']);
        unset($actions['view']);

        $actions['error'] = ['class'=>'yii\web\ErrorAction'];

        return $actions;
    }

    protected function verbs(): array
    {
        return [
            'index' => ['GET', 'HEAD', 'OPTIONS', 'POST', "PATCH", "PUT", "DELETE"],
            'list' => ['GET', 'HEAD', 'OPTIONS', 'POST', "PATCH", "PUT", "DELETE"],
            'create' => ['GET', 'HEAD', 'OPTIONS', 'POST', "PATCH", "PUT", "DELETE"],
            'update' => ['GET', 'HEAD', 'OPTIONS', 'POST', "PATCH", "PUT", "DELETE"],
            'delete' => ['GET', 'HEAD', 'OPTIONS', 'POST', "PATCH", "PUT", "DELETE"],
        ];
    }
	
	
	/**
	 * @throws NotFoundHttpException
	 */
	public function actionNotFound(){
		throw new NotFoundHttpException("Method Not Found");
	}

    private function createDTOName($controller, $method)
    {
        $this->dto = "app\\components\\dto\\".ucfirst($controller).ucfirst($method)."DTO";
        if($method == 'update' && !class_exists($this->dto)) {
            $this->createDTOName($controller, 'store');
        }
    }


}