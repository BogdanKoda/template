<?php

use yii\db\Migration;

class m220201_992806_create_users_photos_table extends Migration
{

	public function safeUp()
	{
		$this->createTable("{{%users_photos}}", [
			'id' => $this->primaryKey(),
			'userId' => $this->integer(),
			'photoId' => $this->integer(),
		]);

		$this->createIndex(
			'{{%idx-users_photos-photoId}}',
			'{{%users_photos}}',
			'photoId'
		);
		$this->createIndex(
			'{{%idx-users_photos-userId}}',
			'{{%users_photos}}',
			'userId'
		);


		$this->addForeignKey(
			'{{%fk-users_photos-photoId}}',
			'{{%users_photos}}',
			'photoId',
			'{{%files}}',
			'id',
			'CASCADE'
		);
		$this->addForeignKey(
			'{{%fk-users_photos-userId}}',
			'{{%users_photos}}',
			'userId',
			'{{%users}}',
			'id',
			'CASCADE'
		);


	}

	public function safeDown()
	{
		$this->dropForeignKey(
			'{{%fk-users_photos-photoId}}',
			'{{%users_photos}}');
		$this->dropForeignKey(
			'{{%fk-users_photos-userId}}',
			'{{%users_photos}}');


		$this->dropIndex(
			'{{%idx-users_photos-photoId}}',
			'{{%users_photos}}'
		);
		$this->dropIndex(
			'{{%idx-users_photos-userId}}',
			'{{%users_photos}}'
		);


		$this->dropTable("{{%users_photos}}");
	}

}