<?php

use yii\db\Migration;

class m170108_135224_menu extends Migration
{
    const TBL_NAME = '{{%menu}}';
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(self::TBL_NAME, [
            'id' => $this->primaryKey(),
            'name' => $this->string(128)->notNull(),
            'parent' => $this->integer(11)->defaultValue(null),
            'route' => $this->string(256)->defaultValue(null),
            'order' => $this->integer(11)->defaultValue(null),
            'data' => $this->text()
        ], $tableOptions);

        $this->createIndex('parent',self::TBL_NAME,'parent');
        $this->addForeignKey('menu_ibfk_1', self::TBL_NAME, 'parent', self::TBL_NAME, 'id','RESTRICT', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable(self::TBL_NAME);
    }
}
