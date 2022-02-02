<?php

use yii\db\Migration;

class m220201_992806_create_users_refresh_table extends Migration
{

	public function safeUp()
	{
		$this->createTable("{{%users_refresh}}", [
			'id' => $this->primaryKey(),
			'userId' => $this->integer(),
			'refreshToken' => $this->string(),
			'expiredAt' => $this->integer(),
		]);

		$this->createIndex(
			'{{%idx-users_refresh-userId}}',
			'{{%users_refresh}}',
			'userId'
		);


		$this->addForeignKey(
			'{{%fk-users_refresh-userId}}',
			'{{%users_refresh}}',
			'userId',
			'{{%users}}',
			'id',
			'CASCADE'
		);


	}

	public function safeDown()
	{
		$this->dropForeignKey(
			'{{%fk-users_refresh-userId}}',
			'{{%users_refresh}}');


		$this->dropIndex(
			'{{%idx-users_refresh-userId}}',
			'{{%users_refresh}}'
		);


		$this->dropTable("{{%users_refresh}}");
	}

}