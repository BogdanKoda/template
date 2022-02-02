<?php

use yii\db\Migration;

class m220201_112806_create_users_table extends Migration
{

	public function safeUp()
	{
		$this->createTable("{{%users}}", [
			'id' => $this->primaryKey(),
			'phoneNumber' => $this->string()->unique(),
			'firstName' => $this->string(),
			'lastName' => $this->string(),
			'password' => $this->string(255),
			'lastLoginAt' => $this->integer(),
			'createdAt' => $this->integer(),
			'updatedAt' => $this->integer(),
		]);
	}

	public function safeDown()
	{
		$this->dropTable("{{%users}}");
	}

}