<?php
namespace app\models;

use app\components\Helpers;
use app\models\extend\BaseModel;

/**
 * @property null $id
 * @property null $name
 * @property null $code
 * @property null $createdAt
 * @property null $updatedAt

*/
class Roles extends BaseModel
{
	public static function tableName(): string
	{
		return 'roles';
	}

	public function rules(): array
	{
		return [
			[['name', 'code'], "string"],
			[['createdAt', 'updatedAt'], "integer"],
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
			"name" => "Название роли",
			"code" => "Код роли",
			"createdAt" => "Дата создания",
			"updatedAt" => "Дата обновления",
		];
	}
	
	public function fields(): array
	{
	    return [
			"id",
			"name",
			"code",
	    ];
	}
}