<?php
/**
 * @name:文件名
 *
 * 功能介绍
 * @author   hexu
 * @date 2021/7/18 10:50 上午
 */

use Guest\EasyFrom\EasyDb;
use PHPUnit\Framework\TestCase;

/**
 * Class alter
 * @property  \Guest\EasyFrom\Alter $alter
 * @property  EasyDb $db
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
        $this->db = EasyDb::init();
        $this->alter = new \Guest\EasyFrom\Alter();
    }

    /**
     * @test
     * @title 创建表
     * @author hexu
     * @date 2021/7/18 10:48 上午
     */
    public function create(){
        $res = $this->alter->creatTable('showa','测试表');
        $this->assertThat($res, $this->isInstanceOf(\PDOStatement::class));
    }

    /**
     * @test
     * @title
     * @author hexu
     * @date 2021/7/18 4:11 下午
     */
    public function isSetTable(){
        $res = $this->alter->issetTable('zt_ext_showa');
        $this->assertThat($res, $this->isTrue());
    }

    /**
     * @test
     * @title 添加字段
     * @author hexu
     * @date 2021/7/18 4:57 下午
     */
    public function addField(){
        $res = $this->alter->addField('zt_ext_showa','phone','phone','这是电话');
        $this->assertThat($res, $this->isTrue());
    }

    /**
     * @test
     * @title 编辑字段
     * @author hexu
     * @date 2021/7/18 4:57 下午
     */
    public function editField(){
        $res = $this->alter->editField('zt_ext_showa','now_phone','phone','phone','这是新电话');
        $this->assertThat($res, $this->isTrue());
    }

    /**
     * @test
     * @title 编辑字段
     * @author hexu
     * @date 2021/7/18 4:57 下午
     */
    public function issetField(){
        $res = $this->alter->issetField('zt_ext_showa','phone');
        $this->assertThat($res, $this->isTrue());
    }

    /**
     * @test
     * @title 删除字段
     * @author hexu
     * @date 2021/7/18 4:57 下午
     */
    public function dropField(){
        $res = $this->alter->delField('zt_ext_showa','phone');
        $this->assertThat($res, $this->isTrue());
    }





}
