<?php
/**
 * @name:文件名
 *
 * 功能介绍
 * @author   hexu
 */

use PHPUnit\Framework\TestCase;

/**
 * Class alter
 * @property  \Guest\EasyForm\Alter $alter
 */
class alter extends TestCase
{
    public $alter;
    public $db;
    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->alter = new \Guest\EasyForm\Alter();
    }

    /**
     * @test
     * @title 创建表
     * @author hexu
     */
    public function create(){
        $res = $this->alter->creatTable('zt_ext_show','测试表');
        $this->assertThat($res, $this->isTrue());
    }

    /**
     * @test
     * @title 表是否存在
     * @author hexu
     */
    public function isSetTable(){
        $res = $this->alter->issetTable('zt_ext_show');
        $this->assertThat($res, $this->isTrue());
    }

    /**
     * @test
     * @title 添加字段
     * @author hexu
     */
    public function addField(){
        $res = $this->alter->addField('zt_ext_show','phone','phone','这是电话');
        $this->assertThat($res, $this->isTrue());
    }

    /**
     * @test
     * @title 编辑字段
     * @author hexu
     */
    public function editField(){
        $res = $this->alter->editField('zt_ext_show','new_phone','phone','phone','这是新电话');
        $this->assertThat($res, $this->isTrue());
    }

    /**
     * @test
     * @title 字段是否设置
     * @author hexu
     */
    public function issetField(){
        $res = $this->alter->issetField('zt_ext_show','phone');
        $this->assertThat($res, $this->isTrue());
    }

    /**
     * @test
     * @title 删除字段
     * @author hexu
     */
    public function dropField(){
        $res = $this->alter->delField('zt_ext_show','phone');
        $this->assertThat($res, $this->isTrue());
    }

    /**
     * @test
     * @title 完整表实例
     * @author hexu
     */
    public function fullTable(){
        $fromModel = new \Guest\EasyForm\FormSet();
        $res = $fromModel->creatTables('ext_shows','测试表啊');
        $this->assertThat($res, $this->equalTo(1));
    }

    /**
     * @test
     * @title 添加表
     * @author hexu
     */
    public function fullAddField(){
        $fromModel = new \Guest\EasyForm\FormSet();
        $param = array(
            [
                'name'=>'user_name5',
                'type'=>'name',
                'desc'=>'用户名'
            ],
            [
                'name'=>'user_ids',
                'type'=>'number',
                'desc'=>'用户ID'
            ],
            [
                'name'=>'sexs',
                'type'=>'number',
                'desc'=>'性别'
            ],
            [
                'name'=>'time',
                'type'=>'number',
                'desc'=>'时间'
            ],
        );
        $result = $fromModel->saveField(30,$param);
        $this->assertThat($result, $this->isTrue());
    }

    /**
     * @test
     * @title 重命名
     * @author hexu
     */
    public function reNameField(){
        $fromModel = new \Guest\EasyForm\FormSet();
        $param = array(
            [
                'id'=>141,
                'name'=>'user_name300',
                'type'=>'name',
                'desc'=>'用户名'
            ],
            [
                'id'=>133,
                'name'=>'user_ids',
                'type'=>'number',
                'desc'=>'用户ID'
            ],
            [
                'id'=>134,
                'name'=>'sexs',
                'type'=>'number',
                'desc'=>'性别'
            ],
            [
                'id'=>135,
                'name'=>'time',
                'type'=>'number',
                'desc'=>'时间'
            ],
        );
        $result = $fromModel->saveField(30,$param);
        $this->assertThat($result, $this->isTrue());
    }

    /**
     * @test
     * @title 修改表名
     * @author hexu
     */
    public function renameTable(){
        $fromModel = new \Guest\EasyForm\FormSet();
        $result = $fromModel->renameTable('zt_th_shows','zt_th_shows2');
        $this->assertThat($result, $this->isTrue());
    }


}
