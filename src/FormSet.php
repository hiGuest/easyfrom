<?php
/**
 * @name:表单设置
 *
 * 功能介绍
 * @author   hexu
 */

namespace Guest\EasyForm;


use Guest\EasyCom\Config;
use Guest\EasyCom\db\EasyDb;
use Guest\EasyCom\db\EasyDbException;

class FormSet extends Alter
{
    public $prefix;
    public $db;

    public function __construct()
    {
        parent::__construct();
        $this->prefix = Config::Init()->get('Config.database.mysql.prefix');
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
     */
    public function creatTables($table_name, $comment, $config='', $uid='', $charset = 'utf8mb4', $increment = '999'): bool
    {
        // 检查库表是否存在
        $isSet = $this->issetTable($table_name);
        if($isSet)throw new EasyDbException('表已经存在',10021);
        $taName = $this->prefix.'form';
        $sql = "
            SELECT * FROM {$taName} where table_name = :table_name
        ";
        $isSets = $this->db->queryOne($sql,['table_name'=>$table_name]);
        if($isSets)throw new EasyDbException('表已经存在',10022);

        $addRes = parent::creatTable($this->prefix.$table_name, $comment, $charset, $increment);
        if($addRes){
            $config = is_array($config) ? json_encode($config) : $config;
            $data = array(
                'form_name'=>$comment,
                'table_name'=>$table_name,
                'user_id'=>$uid,
                'config'=>$config,
                'create_time'=>time(),
                'update_time'=>time(),
            );
            return $this->db->insert($taName,$data);
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
     */
    public function saveTable($table_id, $table_name, $comment, $config='', $uid=''): bool
    {
        $taName = $this->prefix.'form';
        $config = is_array($config) ? json_encode($config) : $config;
        $data = array(
            'form_name'=>$comment,
            'table_name'=>$table_name,
            'user_id'=>$uid,
            'config'=>$config,
            'update_time'=>time(),
        );
        return $this->db->update($taName,$data,array('id'=>$table_id));
    }

    /**
     * @title 保存字段(有则更新无则创建)
     * @param $table_id
     * @param $param array id/name:名称/type:类型/desc:描述
     * @param string $user_id
     * @return bool
     * @author hexu
     */
    public function saveField($table_id,array $param, $user_id=''): bool
    {
        $taName = $this->prefix.'form';
        $sql = "
            SELECT * FROM {$taName} where id = :id and status = :status
        ";
        $tableInfo = $this->db->queryOne($sql,['id'=>$table_id,'status'=>0]);
        if(!$tableInfo)throw new EasyDbException('表不存在',404);
        $taFName = $this->prefix.'form_field';

        //更新状态
        $notIn = implode(',',array_column($param,'id'));
        $update_time = time();
        if($notIn)$this->db->execSql("UPDATE {$taFName} SET status=1,update_time={$update_time} WHERE NOT id IN({$notIn}) AND status=0");
        if(!$notIn)$this->db->execSql("UPDATE {$taFName} SET status=1,update_time={$update_time} WHERE status=0");

        foreach ($param as $key=>$temp){
            // 是否有记录
//            $fieldInfo = $db->queryOne("SELECT * FROM {$taFName} where form_id = :form_id and field_name = :field_name and status = :status",
//                ['form_id'=>$table_id, 'field_name'=>$temp['name'], 'status'=>0]);
            if(empty($temp['id'])){
                // 表是否有该字段
                $issetField = $this->issetField($this->prefix.$tableInfo['table_name'],$temp['name']);
                $data = array(
                    'form_id'=>$table_id,
                    'field_name'=>$temp['name'],
                    'table_name'=>$tableInfo['table_name'],
                    'type'=>$temp['type'],
                    'user_id'=>$user_id,
                    'original_id'=>0,
                    'sort'=>$key,
                    'create_time'=>time(),
                    'update_time'=>time(),
                );
                if(!$issetField){
                    $addRes = $this->addField($this->prefix.$tableInfo['table_name'],$temp['name'],$temp['type'],$temp['desc']);
                    if($addRes)$this->db->insert($taFName,$data);
                }else{
                    $this->db->update($taFName,['status'=>0],['field_name'=>$temp['name'],'form_id'=>$table_id]);
                }

            }else{
                //查找原字段信息
                $sql = "
                    SELECT * FROM {$taFName} where id = :id and status = :status
                ";
                $fieldInfo = $this->db->queryOne($sql,['id'=>$temp['id'], 'status'=>0]);
                if(empty($fieldInfo))continue;
                $editRes = $this->editField($this->prefix.$tableInfo['table_name'],$fieldInfo['field_name'],$temp['name'],$temp['type'],$temp['desc']);
                if($temp['name'] != $fieldInfo['field_name'] && $editRes){
                    $original_id = $fieldInfo['original_id'] == 0 ?  $fieldInfo['id'] : $fieldInfo['original_id'];
                    // 更新
                    $oldData = array(
                        'update_time'=>time(),
                        'now_field_name'=>$temp['name'],
                        'status'=>1
                    );
                    if($fieldInfo['original_id'] == 0){
                        $this->db->update($taFName,$oldData,['id'=>$temp['id'],'form_id'=>$table_id]);
                    }else{
                        // 初始数据更新
                        $this->db->update($taFName,$oldData,['id'=>$original_id]);
                        // 迭代数据更新
                        $oldData['original_id'] = $original_id;
                        $this->db->update($taFName,$oldData,['original_id'=>$original_id,'form_id'=>$table_id]);
                    }
                    // 新增
                    $data = array(
                        'form_id'=>$table_id,
                        'field_name'=>$temp['name'],
                        'table_name'=>$tableInfo['table_name'],
                        'type'=>$temp['type'],
                        'user_id'=>$user_id,
                        'original_id'=>$original_id,
                        'sort'=>$key,
                        'create_time'=>time(),
                        'update_time'=>time(),
                    );
                    $this->db->insert($taFName,$data);

                }elseif($editRes){
                    // 更新
                    $data = array(
                        'form_id'=>$table_id,
                        'field_name'=>$temp['name'],
                        'table_name'=>$tableInfo['table_name'],
                        'type'=>$temp['type'],
                        'user_id'=>$user_id,
                        'sort'=>$key,
                        'status'=>0,
                        'update_time'=>time(),
                    );
                    $this->db->update($taFName,$data,['id'=>$temp['id'],'form_id'=>$table_id]);
                }else{
                    throw new EasyDbException('表字段更新失败',400);
                }
            }
        }
        return true;
    }

    /**
     * @title 表单详情
     * @param $table_id
     * @return mixed
     * @author hexu
     */
    public function tableInfo($table_id){
        $taName = $this->prefix.'form';
        $sql = "select * from {$taName} where id = :table_id;";
        $res = $this->db->queryOne($sql,['table_id'=>$table_id]);
        return $res;
    }

    /**
     * @title 获取表单字段记录信息
     * @param $table_id
     * @param $field_name
     * @return mixed
     * @author hexu
     */
    public function getField($table_id, $field_name){
        $taFName = $this->prefix.'form_field';
        $sql = "
                    SELECT * FROM {$taFName} where form_id = :form_id and field_name = :field_name and status = :status
                ";
        return $this->db->queryOne($sql,['form_id'=>$table_id, 'field_name'=>$field_name,'status'=>0]);
    }

}
