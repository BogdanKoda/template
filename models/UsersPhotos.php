<?php
namespace app\models;

use app\models\extend\BaseModel;

/**
 * @property null $id
 * @property null $userId
 * @property null $photoId

*/
class UsersPhotos extends BaseModel
{
	public static function tableName(): string
	{
		return 'users_photos';
	}

	public function rules(): array
	{
		return [
			[['userId', 'photoId'], "integer"],
			[['photoId'],
				'exist',
				'skipOnError'     => true,
				'targetClass'     => Files::class,
				'targetAttribute' => ['photoId' => 'id']
			],
			[['userId'],
				'exist',
				'skipOnError'     => true,
				'targetClass'     => Users::class,
				'targetAttribute' => ['userId' => 'id']
			],
		];
	}
	
	public function attributeLabels(): array
	{
		return [
			"id" => "ID",
			"userId" => "ID пользователя",
			"photoId" => "ID фотографии",
		];
	}
	
	public function fields(): array
	{
	    return [
			"id",
			"userId",
			"photoId",
	    ];
	}
}