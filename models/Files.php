<?php
namespace app\models;

use app\components\Helpers;
use app\models\extend\BaseModel;

/**
 * @property null $id
 * @property null $name
 * @property null $url
 * @property null $path
 * @property null $mime
 * @property null $size
 * @property null $createdAt
 * @property null $updatedAt

*/
class Files extends BaseModel
{
	public static function tableName(): string
	{
		return 'files';
	}

	public function rules(): array
	{
		return [
			[['name', 'url', 'path', 'mime'], "string"],
			[['size', 'createdAt', 'updatedAt'], "integer"],
		];
	}
	
	public function beforeValidate(): bool
	{
		if ($this->isNewRecord) {			
			$this->createdAt = Helpers::getDate();
		}
		
		$this->updatedAt = Helpers::getDate();
		
		return parent::beforeValidate();
	}
	
	public function attributeLabels(): array
	{
		return [
			"id" => "ID",
			"name" => "Название файла",
			"url" => "URL файла",
			"path" => "Путь до файла",
			"mime" => "Тип файла",
			"size" => "Размер файла",
			"createdAt" => "Дата создания",
			"updatedAt" => "Дата обновления",
		];
	}
	
	public function fields(): array
	{
	    return [
			"id",
			"name",
			"url",
			"path",
			"mime",
			"size",
	    ];
	}
}