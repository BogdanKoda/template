<?php

namespace app\components\Uploaders;

use app\models\extend\BaseModel;

class PhotoUploadData
{
    private BaseModel $photoLinkedModel;
    private string $fieldName;
    private string $columnName;

    public function __construct(BaseModel $photoLinkedModel, string $fieldName, string $columnName)
    {
        $this->photoLinkedModel = $photoLinkedModel;
        $this->fieldName = $fieldName;
        $this->columnName = $columnName;
    }

    /**
     * @return BaseModel
     */
    public function getPhotoLinkedModel(): BaseModel
    {
        return $this->photoLinkedModel;
    }

    /**
     * @return string
     */
    public function getFieldName(): string
    {
        return $this->fieldName;
    }

    /**
     * @return string
     */
    public function getColumnName(): string
    {
        return $this->columnName;
    }
}