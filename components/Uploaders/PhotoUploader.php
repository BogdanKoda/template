<?php

namespace app\components\Uploaders;

use app\components\Exceptions\ModelException;
use app\models\Files;
use Yii;
use yii\db\Exception;

class PhotoUploader extends BasePhotoUploader
{
    /**
     * @throws ModelException
     * @throws Exception
     */
    public static function upload(PhotoUploadData $uploadData): array
    {
		if(isset($_FILES[$uploadData->getFieldName()])) {

			$_instance = new self;
			$_instance->photoUploadData = $uploadData;
            $_instance->images = $_FILES[$uploadData->getFieldName()];

            foreach ($_instance->images as $nameType => $image) {
                if(!is_array($image)) {
                    $_instance->images[$nameType] = [$image];
                }
            }

            $_instance->validateImage();
            $_instance->uploadImage();
            $_instance->writeModel();

            return $_instance->photoModels;
		}
		
		return [];
		
	}

    /**
     * @throws Exception
     * @throws ModelException
     */
    private function writeModel(): void
    {
        $transaction = Yii::$app->db->beginTransaction();

        foreach($this->images['fullPath'] as $index => $pathImage) {
            $this->photoModels[$index] = new Files();
            $this->photoModels[$index] = $this->loadFileModel($this->photoModels[$index], $pathImage, $index);

            if(!$this->photoModels[$index]->save()) {
                $transaction->rollBack();
                throw new ModelException([$index => $this->photoModels[$index]->getErrors()]);
            }

            $this->compressImage($this->photoModels[$index]->path);
        }

        $transaction->commit();

    }


}