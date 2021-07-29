<?php
/**
 * @name:表单设置
 *
 * 功能介绍
 * @author   hexu
 * @date 2021/7/19 4:25 下午
 */

namespace Guest\EasyFrom;


class FromSet extends Alter
{
    public $prefix;

    public function __construct()
    {
        $this->prefix = \Guest\EasyFrom\Config::Init()->get('Config.database.mysql.prefix');
    }

    /**
     * @title 创建表
     * @param $table_name
     * @param $comment
     * @param string $charset
     * @param string $increment
     * @param  $config
     * @param string $uid
     * @return bool
     * @author hexu
     * @date 2021/7/19 4:26 下午
     */
    public function creatTables($table_name, $comment, $config='', $uid='', $charset = 'utf8mb4', $increment = '999'): bool
    {
        $db = EasyDb::init();
        // 检查库表是否存在
        $isSet = $this->issetTable($table_name);
        if($isSet)throw new EasyDbException('表已经存在',10021);
        $taName = $this->prefix.'from';
        $sql = "
            SELECT * FROM {$taName} where table_name = :table_name
        ";
        $isSets = $db->queryOne($sql,['table_name'=>$table_name]);
        if($isSets)throw new EasyDbException('表已经存在',10022);

        $addRes = parent::creatTable($this->prefix.$table_name, $comment, $charset, $increment);
        if($addRes){
            $config = is_array($config) ? json_encode($config) : $config;
            $data = array(
                'from_name'=>$comment,
                'table_name'=>$table_name,
                'user_id'=>$uid,
                'config'=>$config,
                'create_time'=>time(),
                'update_time'=>time(),
            );
            return $db->insert($taName,$data);
        }else{
            return false;
        }
    }

    /**
     * @title 保存表数据
     * @param $table_id
     * @param $table_name
     * @param $comment
     * @param string $config
     * @param string $uid
     * @return bool
     * @author hexu
     * @date 2021/7/23 11:35 上午
     */
    public function saveTable($table_id, $table_name, $comment, $config='', $uid=''): bool
    {
        $db = EasyDb::init();
        $taName = $this->prefix.'from';
        $config = is_array($config) ? json_encode($config) : $config;
        $data = array(
            'from_name'=>$comment,
            'table_name'=>$table_name,
            'user_id'=>$uid,
            'config'=>$config,
            'update_time'=>time(),
        );
        return $db->update($taName,$data,array('id'=>$table_id));
    }

    /**
     * @title 保存字段
     * @param $table_id
     * @param $param array id/name:名称/type:类型/desc:描述
     * @param string $user_id
     * @return bool
     * @author hexu
     * @date 2021/7/19 10:54 下午
     */
    public function saveField($table_id,array $param, $user_id=''): bool
    {
        $db = EasyDb::init();
        $taName = $this->prefix.'from';
        $sql = "
            SELECT * FROM {$taName} where id = :id and status = :status
        ";
        $tableInfo = $db->queryOne($sql,['id'=>$table_id,'status'=>0]);
        if(!$tableInfo)throw new EasyDbException('表不存在',404);
        $taFName = $this->prefix.'from_field';

        //更新状态
        $notIn = implode(',',array_column($param,'id'));
        $update_time = time();
        if($notIn)$db->execSql("UPDATE {$taFName} SET status=1,update_time={$update_time} WHERE NOT id IN({$notIn}) AND status=0");
        if(!$notIn)$db->execSql("UPDATE {$taFName} SET status=1,update_time={$update_time} WHERE status=0");

        foreach ($param as $key=>$temp){
            // 是否有记录
//            $fieldInfo = $db->queryOne("SELECT * FROM {$taFName} where from_id = :from_id and field_name = :field_name and status = :status",
//                ['from_id'=>$table_id, 'field_name'=>$temp['name'], 'status'=>0]);
            if(empty($temp['id'])){
                // 表是否有该字段
                $issetField = $this->issetField($this->prefix.$tableInfo['table_name'],$temp['name']);
                if(!$issetField){
                    $addRes = $this->addField($this->prefix.$tableInfo['table_name'],$temp['name'],$temp['type'],$temp['desc']);
                }else{
                    $addRes = true;
                }
                $data = array(
                    'from_id'=>$table_id,
                    'field_name'=>$temp['name'],
                    'table_name'=>$tableInfo['table_name'],
                    'type'=>$temp['type'],
                    'user_id'=>$user_id,
                    'sort'=>$key,
                    'create_time'=>time(),
                    'update_time'=>time(),
                );
                if($addRes)$db->insert($taFName,$data);
            }else{
                //查找原字段信息
                $sql = "
                    SELECT * FROM {$taFName} where id = :id and status = :status
                ";
                $fieldInfo = $db->queryOne($sql,['id'=>$temp['id'], 'status'=>0]);
                if(empty($fieldInfo))throw new EasyDbException('表字段不存在',404);
                $editRes = $this->editField($this->prefix.$tableInfo['table_name'],$fieldInfo['field_name'],$temp['name'],$temp['type'],$temp['desc']);
                $data = array(
                    'from_id'=>$table_id,
                    'field_name'=>$temp['name'],
                    'table_name'=>$tableInfo['table_name'],
                    'type'=>$temp['type'],
                    'user_id'=>$user_id,
                    'sort'=>$key,
                    'status'=>0,
                    'update_time'=>time(),
                );
                if($editRes)$db->update($taFName,$data,['id'=>$temp['id'],'from_id'=>$table_id]);
            }
        }
        return true;
    }

    /**
     * @title 表单详情
     * @param $table_id
     * @return mixed
     * @author hexu
     * @date 2021/7/23 10:00 上午
     */
    public function tableInfo($table_id){
        $taName = $this->prefix.'from';
        $sql = "select * from {$taName} where id ='{$table_id}';";
        $db = EasyDb::init();
        return $db->queryOne($sql,['id'=>$table_id,'status'=>0]);
    }

    /**
     * @title 获取表单字段记录信息
     * @param $table_id
     * @param $field_name
     * @return mixed
     * @author hexu
     * @date 2021/7/23 2:31 下午
     */
    public function getField($table_id, $field_name){
        $taFName = $this->prefix.'from_field';
        $db = EasyDb::init();
        $sql = "
                    SELECT * FROM {$taFName} where from_id = :from_id and field_name = :field_name and status = :status
                ";
        return $db->queryOne($sql,['from_id'=>$table_id, 'field_name'=>$field_name,'status'=>0]);
    }

}
