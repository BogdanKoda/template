<?php
namespace app\models;

use app\models\extend\BaseModel;

/**
 * @property null $id
 * @property null $userId
 * @property null $refreshToken
 * @property null $expiredAt

*/
class UsersRefresh extends BaseModel
{
	public static function tableName(): string
	{
		return 'users_refresh';
	}

	public function rules(): array
	{
		return [
			[['userId', 'expiredAt'], "integer"],
			[['refreshToken'], "string"],
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
			"refreshToken" => "Refresh токен",
			"expiredAt" => "Дата истечения токена",
		];
	}
	
	public function fields(): array
	{
	    return [
			"id",
			"userId",
			"refreshToken",
			"expiredAt",
	    
	    ];
	}
}