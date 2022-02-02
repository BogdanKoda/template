<?php

use yii\db\Migration;

class m220201_112806_create_files_table extends Migration
{

	public function safeUp()
	{
		$this->createTable("{{%files}}", [
			'id' => $this->primaryKey(),
			'name' => $this->string(),
			'url' => $this->string(),
			'path' => $this->string(),
			'mime' => $this->string(),
			'size' => $this->integer(),
			'createdAt' => $this->integer(),
			'updatedAt' => $this->integer(),
		]);
	}

	public function safeDown()
	{
		$this->dropTable("{{%files}}");
	}

}