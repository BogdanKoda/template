<?php

use yii\db\Migration;

class m220201_112806_create_roles_table extends Migration
{

	public function safeUp()
	{
		$this->createTable("{{%roles}}", [
			'id' => $this->primaryKey(),
			'name' => $this->string(),
			'code' => $this->string(),
			'createdAt' => $this->integer(),
			'updatedAt' => $this->integer(),
		]);
	}

	public function safeDown()
	{
		$this->dropTable("{{%roles}}");
	}

}