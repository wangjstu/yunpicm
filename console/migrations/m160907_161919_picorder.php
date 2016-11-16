<?php
/**
 * 照片订单表
 */
use yii\db\Migration;

class m160907_161919_picorder extends Migration
{
    const TBL_NAME = '{{%picorder}}';

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'AUTO_INCREMENT=100 CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable(self::TBL_NAME,[
            'id' => $this->primaryKey()->comment('订单流水ID'),
            'originalid' => $this->integer()->comment('预定系统中的订单ID'),
            'notes' => $this->text()->comment('订单备注'),
            'contacts' => $this->string()->comment('订单联系人'),
            'contacttel' => $this->string(20)->comment('订单联系人联系方式'),
            'istodaysee' => $this->boolean()->comment('是否当天看片'),
            'ordertype' => $this->smallInteger()->comment('照片类型ID'), //单人，多人，不计入收费
            'orderstatus' => $this->integer()->comment('订单状态'), //二进制表示各个状态换算成的十进制
            'orderpiccount' => $this->smallInteger()->comment('照片数量'),
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
