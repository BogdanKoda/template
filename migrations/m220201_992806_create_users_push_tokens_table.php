<?php

use yii\db\Migration;

class m220201_992806_create_users_push_tokens_table extends Migration
{

	public function safeUp()
	{
		$this->createTable("{{%users_push_tokens}}", [
			'id' => $this->primaryKey(),
			'pushToken' => $this->string(),
			'userId' => $this->integer(),
		]);

		$this->createIndex(
			'{{%idx-users_push_tokens-userId}}',
			'{{%users_push_tokens}}',
			'userId'
		);


		$this->addForeignKey(
			'{{%fk-users_push_tokens-userId}}',
			'{{%users_push_tokens}}',
			'userId',
			'{{%users}}',
			'id',
			'CASCADE'
		);


	}

	public function safeDown()
	{
		$this->dropForeignKey(
			'{{%fk-users_push_tokens-userId}}',
			'{{%users_push_tokens}}');


		$this->dropIndex(
			'{{%idx-users_push_tokens-userId}}',
			'{{%users_push_tokens}}'
		);


		$this->dropTable("{{%users_push_tokens}}");
	}

}