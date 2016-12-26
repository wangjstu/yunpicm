<?php
/**
 * 照片记录表
 */
use yii\db\Migration;

class m160907_162105_picture extends Migration
{
    const TBL_NAME = "{{%picture}}";
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'AUTO_INCREMENT=100 CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable(self::TBL_NAME,[
            'id' => $this->primaryKey()->comment('照片流水ID'),
            'picname' => $this->string()->comment('照片名称'),
            'picdir' => $this->string(1000)->comment('照片存储路径'),
            'notes' => $this->text()->comment('备注'),
            'picsavetype' => $this->integer()->comment('照片存储方式'), //二进制，1表示本地，11表示七牛和本地，10表示仅仅七牛
            'created_at' => $this->integer()->notNull()->comment('创建时间'),
            'updated_at' => $this->integer()->notNull()->comment('修改时间'),
            'isvalid' => $this->boolean()->comment('是否有效'),
            'datasource' => $this->string(30)->comment('数据来源'),
            'modifysource' => $this->string(30)->comment('数据修改源')
        ], $tableOptions);
        $this->createIndex('picture_picname_index', self::TBL_NAME, ['picname']);
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
