<?php
namespace app\models;

use app\components\Helpers;
use app\models\extend\BaseModel;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;

/**
 * @property null $id
 * @property null $phoneNumber
 * @property null $firstName
 * @property null $lastName
 * @property null $password
 * @property null $lastLoginAt
 * @property null $createdAt
 * @property null $updatedAt
 * @property mixed|null $logo
 */
class Users extends BaseModel
{
    public const USER_ROLE_ID = 4;

	public static function tableName(): string
	{
		return 'users';
	}

	public function rules(): array
	{
		return [
			[['phoneNumber', 'firstName', 'lastName', 'password'], "string"],
			[['lastLoginAt', 'createdAt', 'updatedAt'], "integer"],
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

    public function afterSave($insert, $changedAttributes)
    {
        // При добавлении нового пользователя, по умолчанию устанавливаем ему роль обычного пользователя
        if($insert){
            $usersRoles = new UsersRoles();
            $usersRoles->roleId = Roles::findOne(['code' => 'user'])->id ?? self::USER_ROLE_ID;
            $usersRoles->userId = $this->id;
            $usersRoles->save();
        }

        parent::afterSave($insert, $changedAttributes);
    }
	
	public function attributeLabels(): array
	{
		return [
			"id" => "ID",
			"phoneNumber" => "Номер телефона",
			"firstName" => "Имя",
			"lastName" => "Фамилия",
			"password" => "Пароль",
			"lastLoginAt" => "Дата последнего входа",
			"createdAt" => "Дата создания",
			"updatedAt" => "Дата обновления",
		];
	}
	
	public function fields(): array
	{
	    return [
			"id",
			"phoneNumber",
			"firstName",
			"lastName",
            "roles",
            "images" => function() {
                $result = [];
                foreach ($this->logo as $file) {
                    $result[] = $file->url;
                }
                return $result;
            }
	    ];
	}

    /**
     * @throws InvalidConfigException
     */
    public function getRoles(): ActiveQuery
    {
        // Получаем список ролей пользователя
        return $this->hasMany(Roles::class, ['id' => 'roleId'])
            ->viaTable(UsersRoles::tableName(), ['userId' => 'id']);
    }

    /**
     * @throws InvalidConfigException
     */
    public function getLogo(): ActiveQuery
    {
        return $this->hasMany(Files::class, ['id' => 'photoId'])
            ->viaTable(UsersPhotos::tableName(), ['userId' => 'id']);
    }
}