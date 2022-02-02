<?php

namespace app\components\Uploaders;

use app\components\Exceptions\ModelException;
use app\models\Files;
use Exception;
use yii\helpers\Url;

class BasePhotoUploader
{
    const UPLOAD_PATH = "uploads/";
    const MAX_FILE_SIZE = 20971520;

    protected PhotoUploadData $photoUploadData;
    protected array $images;
    protected array $photoModels;

    /**
     * @throws ModelException
     */
    protected function validateImage(): void
    {
        foreach($this->images['size'] as $size) {
            if ($size > self::MAX_FILE_SIZE) {
                throw new ModelException(["Загрузка файлов" => sprintf("Размер файла \"%s\" слишком большой", $this->photoUploadData->getFieldName())]);
            }
        }

        foreach ($this->images['tmp_name'] as $file) {
            if(!exif_imagetype($file)) {
                throw new ModelException(["Загрузка файлов" => sprintf("Файл \"%s\" должен быть картинкой", $this->photoUploadData->getFieldName())]);
            }
        }

    }

    /**
     * @throws ModelException
     */
    protected function uploadImage(): void
    {
        foreach ($this->images['name'] as $index => $name) {
            $extension = pathinfo($name, PATHINFO_EXTENSION);
            try {
                $randomName = random_int(100000, 999999);
            } catch (Exception $e) {
                $randomName = 123456;
            }
            $fileName = time() . "-" . $randomName . "." . $extension;

            $pathToImage = self::UPLOAD_PATH . $this->photoUploadData->getPhotoLinkedModel()::tableName() . "/";
            $fullPathToImage = $_SERVER["DOCUMENT_ROOT"] . "/" . $pathToImage;

            if (!is_dir($fullPathToImage)) {
                mkdir($fullPathToImage, 0777, true);
            }

            $this->images['fullPath'][$index] = $pathToImage . $fileName;

            if (!move_uploaded_file($this->images["tmp_name"][$index], $fullPathToImage . $fileName)) {
                throw new ModelException(["Сохранение файлов" => "При сохранении файла произошла ошибка"]);
            }
        }

    }

    protected function compressImage(string $path)
    {
        // TODO: Добавить в очередь для RabbitMQ сжатие фотографии
    }

    protected function loadFileModel(
        Files  $model,
        string $pathImage,
        int    $imageIndex
    ): Files {
        $model->path = $_SERVER["DOCUMENT_ROOT"] . "/" . $pathImage;
        $model->url = Url::base(true) . "/" . $pathImage;
        $model->mime = $this->images['type'][$imageIndex];
        $model->name = $this->images['name'][$imageIndex];
        $model->size = $this->images['size'][$imageIndex];

        return $model;
    }

}