<?php

namespace app\components\Decorators;

use app\components\Strategy\SaveStrategy;
use app\models\extend\BaseModel;
use Yii;
use yii\caching\CacheInterface;

class CrudActionCachedDecorator implements CrudActionsImpl
{
	private CrudActionsImpl $origin;

    public CacheInterface $cache;
    public string $cachePrefix;
    public int $cacheDuration;
	
	public function __construct(CrudActionsImpl $origin)
	{
		$this->origin = $origin;

        $this->cacheDuration = Yii::$app->params['cacheDuration'] ?? 300;
        $this->cachePrefix = $this->origin->getStrategy()->getModel()::tableName() . "_";
        $this->cache = Yii::$app->cache;
	}
	
	
	public function create(object $data): BaseModel
	{
		$response = $this->origin->create($data);
        $this->cache->set($this->getCachedKeyById($response->id ?? 0), $response);
        return $response;
	}
	
	public function update(array $filter, object $data): BaseModel
	{
		$key = $this->getCachedKeyById($filter["id"] ?? 0);
		$response = $this->origin->update($filter, $data);
        $this->cache->set($key, $response);
        return $response;
	}
	
	public function delete(array $filter): bool
	{
		$key = $this->getCachedKeyById($filter["id"] ?? 0);
		$bool = $this->origin->delete($filter);
        if($bool) {
            $this->cache->delete($key);
        }
        return $bool;
	}
	
	public function list(array $filter, array $sort): array
	{
        $key = $this->cachePrefix.($sort["offset"]??0)."_".($sort["limit"]??Yii::$app->params["limit"]);
        if($cached = $this->cache->get($key)) {
            return $cached;
        }

		$response = $this->origin->list($filter, $sort);
        $this->cache->set($key, $response);
        return $response;
	}
	
	public function get(array $filter): BaseModel
	{
        $key = $this->getCachedKeyById($filter["id"] ?? 0);

        if($cached = $this->cache->get($key)) {
            return $cached;
        }

        $response = $this->origin->get($filter);
        $this->cache->set($key, $response);
        return $response;
	}

    public function getStrategy(): SaveStrategy
    {
        return $this->origin->getStrategy();
    }
	
	protected function getCachedKeyById($id): string
	{
		return $this->cachePrefix.$id;
	}

    public function setStrategy(SaveStrategy $strategy): void
    {
        $this->origin->setStrategy($strategy);
    }
}