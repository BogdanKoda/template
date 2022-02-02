<?php

namespace app\components\Strategy;

use app\components\Exceptions\ModelException;
use app\components\Uploaders\OnePhotoUploader;
use app\components\Uploaders\PhotoUploadData;
use app\components\Uploaders\PhotoUploader;
use app\models\extend\BaseModel;
use Yii;
use yii\db\Exception;
use yii\web\UnprocessableEntityHttpException;

class BasicSave implements SaveStrategy
{

    protected array $oneToManyImages;
    protected BaseModel $model;

    public function __construct(BaseModel $model, array $oneToManyImages = [])
    {
        $this->model = $model;
        $this->oneToManyImages = $oneToManyImages;
    }

    /**
     * @throws ModelException
     * @throws UnprocessableEntityHttpException
     * @throws Exception
     */
    public function handle(object $data): BaseModel
    {
        $model = $this->getModel();

        $data = $data->toArray();
        $data = array_merge($data, OnePhotoUploader::handle($this->model, $this->oneToManyImages));

        $transaction = Yii::$app->db->beginTransaction();

        if($data == []) {
            throw new UnprocessableEntityHttpException("Нет данных для создания/обновления сущности");
        }

        // Сохраняем или создаем новую запись
        $isLoad = $model->load($data, "");
        $isSave = $model->save();

        if(!$isLoad || !$isSave) {
            throw new ModelException($model->getErrors());
        }

        /** @var PhotoUploadData $uploadImage */
        foreach ($this->oneToManyImages ?? [] as $uploadImage) {
            if($uploadImage) {
                if(isset($model->id)) {
                    // Удалить уже существующие фотографии
                    $uploadImage->getPhotoLinkedModel()::deleteAll([$uploadImage->getColumnName() => $model->id]);

                    // Добавить новые фотографии
                    $this->uploadPhoto($model->id, $uploadImage);
                }
            }
        }

        try {
            $transaction->commit();
        } catch (Exception $e){
            throw new ModelException(["error" => $e->getMessage()]);
        }

        return $model;
    }

    /**
     * @throws ModelException
     * @throws Exception
     */
    protected function uploadPhoto(int $id, ?PhotoUploadData $uploadImage = null)
	{
        $photoModels = PhotoUploader::upload($uploadImage);

        // Создаем связи между записями в таблице с фотографиями и созданной записью
        if($photoModels != []) {
            foreach ($photoModels as $photoModel) {
                if ($photoId = $photoModel->id) {
                    $tmpModel = clone $uploadImage->getPhotoLinkedModel();

                    if ($tmpModel) {
                        $tmpModel->{$uploadImage->getColumnName()} = $id;
                        $tmpModel->{"photoId"} = $photoId;

                        $tmpModel->save();
                    }
                }
            }
        }
	}

    public function setData(array $data): void
    {
        $this->oneToManyImages = $data;
    }

    /**
     * @return BaseModel
     */
    public function getModel(): BaseModel
    {
        return $this->model;
    }

    /**
     * @param BaseModel $model
     */
    public function setModel(BaseModel $model): void
    {
        $this->model = $model;
    }

}