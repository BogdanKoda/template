<?php

use yii\db\Migration;

class m220201_992806_create_users_roles_table extends Migration
{

	public function safeUp()
	{
		$this->createTable("{{%users_roles}}", [
			'id' => $this->primaryKey(),
			'roleId' => $this->integer(),
			'userId' => $this->integer(),
		]);

		$this->createIndex(
			'{{%idx-users_roles-roleId}}',
			'{{%users_roles}}',
			'roleId'
		);
		$this->createIndex(
			'{{%idx-users_roles-userId}}',
			'{{%users_roles}}',
			'userId'
		);


		$this->addForeignKey(
			'{{%fk-users_roles-roleId}}',
			'{{%users_roles}}',
			'roleId',
			'{{%roles}}',
			'id',
			'CASCADE'
		);
		$this->addForeignKey(
			'{{%fk-users_roles-userId}}',
			'{{%users_roles}}',
			'userId',
			'{{%users}}',
			'id',
			'CASCADE'
		);


	}

	public function safeDown()
	{
		$this->dropForeignKey(
			'{{%fk-users_roles-roleId}}',
			'{{%users_roles}}');
		$this->dropForeignKey(
			'{{%fk-users_roles-userId}}',
			'{{%users_roles}}');


		$this->dropIndex(
			'{{%idx-users_roles-roleId}}',
			'{{%users_roles}}'
		);
		$this->dropIndex(
			'{{%idx-users_roles-userId}}',
			'{{%users_roles}}'
		);


		$this->dropTable("{{%users_roles}}");
	}

}