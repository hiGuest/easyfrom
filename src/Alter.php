<?php
/**
 * @name:数据表结构变更类
 *
 * 功能介绍
 * @author   hexu
 * @date 2021/7/18 10:16 上午
 */

namespace Guest\EasyFrom;


use phpDocumentor\Reflection\Types\False_;

class Alter
{
    public $prefix = '';

    /**
     * @title 创建表
     * @param $table_name
     * @param $comment
     * @param string $charset
     * @param string $increment
     * @return obj
     * @author hexu
     * @date 2021/7/18 10:38 上午
     */
    public function creatTable($table_name,$comment,$charset='utf8mb4',$increment='999'){
        //TODO
        $table_name = $this->prefix.$table_name;
        $sql =           <<<here
                CREATE TABLE `$table_name` (
                    `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
                    PRIMARY KEY (`id`)
                ) ENGINE=InnoDB AUTO_INCREMENT={$increment} DEFAULT CHARSET={$charset} COMMENT='{$comment}';
here;
        $db = EasyDb::init();
        return $db->execSql($sql);
    }

    /**
     * @title 表是否存在
     * @param $table_names
     * @return bool
     * @author hexu
     * @date 2021/7/18 4:05 下午
     */
    public function issetTable($table_names){
      $res = $this->findTable($table_names);
      if($res){
          return true;
      }else{
          return false;
      }
    }

    /**
     * @title 查找表
     * @param $table_names
     * @return mixed
     * @author hexu
     * @date 2021/7/18 4:17 下午
     */
    public function findTable($table_names){
        $sql = "select * from information_schema.tables where table_name ='{$table_names}';";
        $db = EasyDb::init();
        $res = $db->queryOne($sql);
        if(empty($res)){
            return false;
        }else{
            return true;
        }
    }

    /**
     * @title 保存表单
     * @author hexu
     * @date 2021/7/18 10:38 上午
     */
    public function saveForm(){
        //TODO
    }

    /**
     * @title 添加字段
     * @param $tableName
     * @param $name
     * @param $type
     * @param string $desc
     * @return mixed
     * @author hexu
     * @date 2021/7/18 10:38 上午
     */
    public function addField($tableName,$name,$type,$desc=''){
        //TODO
        // 获取字段属性
        $field =  call_user_func([new FieldType(), $type]);
        if ($field['len']){
            $sqlAttr = $field['decimal'] ? "({$field['len']}, {$field['decimal']})" : "({$field['len']})";
        }else{
            $sqlAttr = '';
        }
        $sql = "
        ALTER TABLE {$tableName} ADD {$name} {$field['type']}{$sqlAttr} DEFAULT NULL COMMENT '{$desc}'
        ";
        $db = EasyDb::init();
        return $db->execSql($sql);
    }

    /**
     * @title 编辑字段
     * @param $tableName
     * @param $old_name
     * @param $name
     * @param $type
     * @param string $desc
     * @return bool|mixed
     * @author hexu
     * @date 2021/7/18 10:38 上午
     */
    public function editField($tableName, $old_name, $name, $type, $desc=''): bool
    {
        //TODO
        // 获取字段属性
        $field =  call_user_func([new FieldType(), $type]);
        if ($field['len']){
            $sqlAttr = $field['decimal'] ? "({$field['len']}, {$field['decimal']})" : "({$field['len']})";
        }else{
            $sqlAttr = '';
        }
        $sql = "
            ALTER TABLE {$tableName} CHANGE {$old_name} {$name} {$field['type']}{$sqlAttr} COMMENT '{$desc}'
        ";
        $db = EasyDb::init();
        return $db->execSql($sql);
    }

    /**
     * @title 判断表的字段是否存在
     * @param $tableName
     * @param $name
     * @return bool
     * @author hexu
     * @date 2021/7/19 12:37 上午
     */
    public function issetField($tableName,$name): bool
    {
        $sql = "
            SELECT count(*) as counts from information_schema.columns where table_name = '{$tableName}' and column_name = '{$name}'
        ";
        $db = EasyDb::init();
        $res = $db->queryOne($sql);
        return (int)$res['counts']===1;
    }

    /**
     * @title 删除字段
     * @param $tableName
     * @param $name
     * @return bool
     * @author hexu
     * @date 2021/7/18 10:39 上午
     */
    public function delField($tableName,$name): bool
    {
        $sql = "
           ALTER TABLE {$tableName} DROP  {$name}
        ";
        $db = EasyDb::init();
        return $db->execSql($sql);
    }


}
