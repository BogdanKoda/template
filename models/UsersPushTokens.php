<?php
namespace app\models;

use app\models\extend\BaseModel;

/**
 * @property null $id
 * @property null $pushToken
 * @property null $userId

*/
class UsersPushTokens extends BaseModel
{
	public static function tableName(): string
	{
		return 'users_push_tokens';
	}

	public function rules(): array
	{
		return [
			[['pushToken'], "string"],
			[['userId'], "integer"],
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
			"pushToken" => "PushToken",
			"userId" => "ID пользователя",
		];
	}
	
	public function fields(): array
	{
	    return [
			"id",
			"pushToken",
			"userId",
	    
	    ];
	}
}