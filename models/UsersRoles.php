<?php
namespace app\models;

use app\models\extend\BaseModel;

/**
 * @property null $id
 * @property null $roleId
 * @property null $userId

*/
class UsersRoles extends BaseModel
{
	public static function tableName(): string
	{
		return 'users_roles';
	}

	public function rules(): array
	{
		return [
			[['roleId', 'userId'], "integer"],
			[['roleId'],
				'exist',
				'skipOnError'     => true,
				'targetClass'     => Roles::class,
				'targetAttribute' => ['roleId' => 'id']
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
			"roleId" => "ID роли",
			"userId" => "ID пользователя",
		];
	}
	
	public function fields(): array
	{
	    return [
			"id",
			"roleId",
			"userId",
	    
	    ];
	}
}