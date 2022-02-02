<?php

namespace app\components\Uploaders;

use app\components\Exceptions\ModelException;
use app\models\extend\BaseModel;
use app\models\Files;
use yii\web\UnprocessableEntityHttpException;

class OnePhotoUploader extends BasePhotoUploader
{

    protected BaseModel $model;

    /**
     * @throws ModelException
     * @throws UnprocessableEntityHttpException
     */
    public static function handle(BaseModel $model, array $controllerData): array
    {
        $_instance = new self();
        $_instance->model = $model;

        $mergePhotoData = [];

        foreach ($_FILES as $field => $file) {
            /** @var PhotoUploadData $data */
            $skip = false;
            foreach ($controllerData as $data) {
                if($data->getFieldName() == $field) {
                    $skip = true;
                    break;
                }
            }

            if($skip) {
                continue;
            }

            $_instance->photoUploadData = new PhotoUploadData($model, $field, "");
            $_instance->images = $file;

            foreach ($file as $paramName => $param) {
                if(is_array($param)) {
                    throw new ModelException([$field => "Должен быть только один файл"]);
                }
                $_instance->images[$paramName] = [$param];

            }

            $_instance->validateImage();
            $_instance->uploadImage();

            $mergePhotoData[$field . "Id"] = $_instance->writeModel();

        }

        return $mergePhotoData;
    }

    /**
     * @throws ModelException
     * @throws UnprocessableEntityHttpException
     */
    protected function writeModel(): int
    {
        foreach($this->images['fullPath'] as $index => $pathImage) {
            $model = new Files();
            $model = $this->loadFileModel($model, $pathImage, $index);

            if(!$model->save()) {
                throw new ModelException([$index => $model->getErrors()]);
            }

            $this->compressImage($model->path);

            return $model->id;
        }

        throw new UnprocessableEntityHttpException('Некорректный формат загрузки файла');
    }

}