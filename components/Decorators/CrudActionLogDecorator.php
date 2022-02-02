<?php

namespace app\components\Decorators;

use app\components\Exceptions\ModelException;
use app\components\Strategy\SaveStrategy;
use app\models\extend\BaseModel;
use Exception;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\UnprocessableEntityHttpException;

class CrudActionLogDecorator implements CrudActionsImpl
{
	private CrudActionsImpl $origin;
	public Logger $logger;
	
	public function __construct(CrudActionsImpl $origin)
	{
		$this->logger = new Logger('crud');
        $this->logger->pushHandler(new StreamHandler(Yii::$app->params['log']));
		$this->origin = $origin;
	}

    /**
     * @throws ModelException
     * @throws Exception
     */
    public function create(object $data): BaseModel
	{
        $this->logger->info("Create new Record {" . $this->getStrategy()->getModel()::tableName() . "}");
        $this->logger->info(json_encode($data, JSON_UNESCAPED_UNICODE));
        try {
            $data = $this->origin->create($data);
            $this->logger->info("Created Record {" . $this->getStrategy()->getModel()::tableName() . "}");
            $this->logger->info(json_encode($data->toArray(), JSON_UNESCAPED_UNICODE));
            return $data;
        } catch (ModelException $e) {
            $this->logger->error("Validation Exception");
            $this->logger->error($e->getErrors());
            throw new ModelException($e->getErrors());
        } catch (UnprocessableEntityHttpException $e) {
            $this->logger->error("UnprocessableEntityHttpException");
            $this->logger->error($e->getMessage());
            throw new UnprocessableEntityHttpException($e->getMessage());
        } catch (Exception $e) {
            $this->logger->error("Unknown Exception");
            $this->logger->error($e->getMessage());
            $this->logger->error(json_encode($e->getTrace(), JSON_UNESCAPED_UNICODE));
            throw new Exception($e->getMessage());
        }
	}

    /**
     * @throws ModelException
     * @throws UnprocessableEntityHttpException
     * @throws Exception
     */
    public function update(array $filter, object $data): BaseModel
	{
        $this->logger->info("Update Record {" . $this->getStrategy()->getModel()::tableName() . "}");
        $this->logger->info(json_encode($data, JSON_UNESCAPED_UNICODE));
        $this->logger->info(json_encode($filter, JSON_UNESCAPED_UNICODE));
        try {
            $data = $this->origin->update($filter, $data);
            $this->logger->info("Record was updated {" . $this->getStrategy()->getModel()::tableName() . "}");
            $this->logger->info(json_encode($data->toArray(), JSON_UNESCAPED_UNICODE));
            return $data;
        } catch (ModelException $e) {
            $this->logger->error("Validation Exception");
            $this->logger->error($e->getErrors());
            throw new ModelException($e->getErrors());
        } catch (UnprocessableEntityHttpException $e) {
            $this->logger->error("UnprocessableEntityHttpException");
            $this->logger->error($e->getMessage());
            throw new UnprocessableEntityHttpException($e->getMessage());
        } catch (NotFoundHttpException $e) {
            $this->logger->error("NotFoundHttpException Exception");
            $this->logger->error($e->getMessage());
            throw new NotFoundHttpException($e->getMessage());
        } catch (Exception $e) {
            $this->logger->error("Unknown Exception");
            $this->logger->error($e->getMessage());
            $this->logger->error(json_encode($e->getTrace(), JSON_UNESCAPED_UNICODE));
            throw new Exception($e->getMessage());
        }
	}
	
	public function delete(array $filter): bool
	{
        $this->logger->info("Delete Record {" . $this->getStrategy()->getModel()::tableName() . "}");
        $this->logger->info(json_encode($filter, JSON_UNESCAPED_UNICODE));
		$bool = $this->origin->delete($filter);
        if($bool) {
            $this->logger->info("SUCCESS DELETE");
        } else {
            $this->logger->info("FAILED DELETE");
        }
        return $bool;
	}
	
	public function list(array $filter, array $sort): array
	{
        $this->logger->info("List of Records {" . $this->getStrategy()->getModel()::tableName() . "}");
        $this->logger->info(json_encode($filter, JSON_UNESCAPED_UNICODE));
        $response = $this->origin->list($filter, $sort);
        /** @var BaseModel $item */
        foreach ($response as $item) {
            $this->logger->info(json_encode($item->toArray(), JSON_UNESCAPED_UNICODE));
        }
		return $response;
	}

    /**
     * @throws NotFoundHttpException
     */
    public function get(array $filter): BaseModel
	{
        $this->logger->info("Get Record {" . $this->getStrategy()->getModel()::tableName() . "}");
        $this->logger->info(json_encode($filter, JSON_UNESCAPED_UNICODE));
        try {
            $response = $this->origin->get($filter);
            $this->logger->info(json_encode($response->toArray(), JSON_UNESCAPED_UNICODE));
        } catch (NotFoundHttpException $e) {
            $this->logger->error("NotFoundHttpException Exception");
            $this->logger->error($e->getMessage());
            throw new NotFoundHttpException($e->getMessage());
        }
        return $response;
	}

    public function getStrategy(): SaveStrategy
    {
        return $this->origin->getStrategy();
    }

    public function setStrategy(SaveStrategy $strategy): void
    {
        $this->origin->setStrategy($strategy);
    }
}