<?php
/**
 * 公共类型表
 */
use yii\db\Migration;

class m160907_162050_pubtype extends Migration
{
    const TBL_NAME = "{{%pubtype}}";
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'AUTO_INCREMENT=100 CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable(self::TBL_NAME,[
            'id' => $this->primaryKey()->comment('类型ID'),
            'typescope' => $this->smallInteger()->comment('所用于的类型[照片类型，操作类型]'),
            'parenttypeid' => $this->smallInteger()->defaultValue(0)->comment('父类型ID'),
            'typename'=>$this->string()->notNull()->comment('类型名称'),
            'note' => $this->text()->comment('类型备注'),
            'created_at' => $this->integer()->notNull()->comment('创建时间'),
            'updated_at' => $this->integer()->notNull()->comment('修改时间'),
            'isvalid' => $this->boolean()->comment('是否有效'),
            'datasource' => $this->string(30)->comment('数据来源'),
            'modifysource' => $this->string(30)->comment('数据修改源')
        ], $tableOptions);
    }

    public function down()
    {
        echo self::TBL_NAME." is to be drop.\n";
        $this->dropTable(self::TBL_NAME);
        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
