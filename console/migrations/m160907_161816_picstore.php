<?php
/**
 * 门店表
 */
use yii\db\Migration;

class m160907_161816_picstore extends Migration
{

    const TBL_NAME = "{{%picstore}}";

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'AUTO_INCREMENT=100 CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable(self::TBL_NAME,[
            'id' => $this->primaryKey()->comment('门店ID'),
            'storename' => $this->string()->notNull()->comment('店铺名称'),
            'storeaddress' => $this->string()->notNull()->comment('店铺地址'),
            'userid' => $this->integer()->comment('店铺负责人id'),
            'created_at' => $this->integer()->notNull()->comment('创建时间'),
            'updated_at' => $this->integer()->notNull()->comment('修改时间'),
            'isvalid' => $this->boolean()->comment('是否有效'),
            'datasource' => $this->string(30)->comment('数据来源'),
            'modifysource' => $this->string(30)->comment('数据修改源')
        ], $tableOptions);
        $this->createIndex('picstore_storname_index', self::TBL_NAME, ['storename']);
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
