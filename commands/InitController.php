<?php

namespace app\commands;

use app\components\Services\InitService;
use Yii;
use yii\base\InvalidConfigException;
use yii\console\Controller;
use yii\db\Exception;
use yii\di\NotInstantiableException;

class InitController extends Controller
{
    private object $initService;

    /**
     * @throws NotInstantiableException
     * @throws InvalidConfigException
     */
    public function __construct($id, $module, $config = [])
    {
        $this->initService = Yii::$container->get(InitService::class);
        parent::__construct($id, $module, $config);
    }

    /**
     * @throws Exception
     */
    public function actionAddAdmin(): int
    {
		return $this->initService->addAdmin();
	}

    public function actionIndex(): int
    {
        return $this->initService->initializeRoles();
    }
	
	
}