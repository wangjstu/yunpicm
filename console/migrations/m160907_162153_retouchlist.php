<?php
/**
 * 修片记录表
 */
use yii\db\Migration;

class m160907_162153_retouchlist extends Migration
{
    const TBL_NAME = "{{%retouchlist}}";
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'AUTO_INCREMENT=100 CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable(self::TBL_NAME,[
            'id' => $this->primaryKey()->comment('操作流水ID'),
            'opttype' => $this->smallInteger()->comment('操作类型-分为修片1和看片2两种'),
            'orderid' => $this->integer()->notNull()->comment('订单ID-picorder'),
            'picid' => $this->integer()->notNull()->comment('照片ID-picture'),
            'orgpicid' => $this->integer()->notNull()->comment('源照片ID-picture'),
            'opterid' => $this->integer()->notNull()->comment('修片师ID-user'),
            'created_at' => $this->integer()->notNull()->comment('创建时间'),
            'updated_at' => $this->integer()->notNull()->comment('修改时间'),
            'isvalid' => $this->boolean()->comment('是否有效'),
            'datasource' => $this->string(30)->comment('数据来源'),
            'modifysource' => $this->string(30)->comment('数据修改源')
        ], $tableOptions);
        $this->createIndex('retouchlist_orderid_picid_index', self::TBL_NAME, ['orderid','picid']);
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
