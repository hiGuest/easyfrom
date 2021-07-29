<?php
/**
 * @name:实例控制器
 *
 * 功能介绍 显示大小
 * @author   hexu
 * @date 2021/7/20 10:55 下午
 */
namespace Controller;
use Guest\EasyFrom\EasyDb;
use mysql_xdevapi\Exception;

class Index
{
    /**
     * @title 保存数据
     * @author hexu
     * @date 2021/7/22 2:32 下午
     */
    public function save(){
        $param = $this->getParam();
        try{
            $db = EasyDb::init();
            $data = $this->verifyParam($param);
            // 创建表
            $fromModel = new \Guest\EasyFrom\FromSet();
            $prefix = 'ext_';
            if(empty($param['table_id'])){
                $res = $fromModel->creatTables($prefix.$param['formModel'], $param['formRef'], $param);
                if(!$res)throw new \Exception('表单创建失败','10000');
                $table_id = $db->getLastId();
            }else{
                $table_id = $param['table_id'];
                //TODO 强制类型转换会可能出现报错，此处预检查
                $tableInfo = $fromModel->tableInfo($table_id);
                if(empty($tableInfo))throw new \Exception('原始表单不存在','404');
                // 保存表数据
                $res = $fromModel->saveTable($table_id,$prefix.$param['formModel'], $param['formRef'], $param);
                if(!$res)throw new \Exception('表单保存失败','10000');
                // 表名更新
                $tableIsset = $fromModel->issetTable($fromModel->prefix.$prefix.$param['formModel']);
                if(!$tableIsset)$fromModel->renameTable($fromModel->prefix.$tableInfo['table_name'],$fromModel->prefix.$prefix.$param['formModel']);
            }
            // 表单关系没有新增有更新
            $result = $fromModel->saveField($table_id,$data);
            if($result){
                $this->apiSuccess();
            }else{
                $this->apiError();
            }
        }catch (\Exception $exception){
             $this->apiError($exception->getMessage(),$exception->getCode());
        }
         $this->apiSuccess();
    }

    /**
     * @title 获取表单详情
     * @author hexu
     * @date 2021/7/23 9:57 上午
     */
    public function info(){
        $db = EasyDb::init();
        $fromModel = new \Guest\EasyFrom\FromSet();
        $table_id = $_GET['id'] ?? null;
        if (empty($table_id)){
            $this->apiError('id不能为空',400);
        }
        $res = $fromModel->tableInfo($table_id);
        if(empty($res)){
            $this->apiError('资源不存在',404);
        }else{
            $result = json_decode($res['config'],true);
            $result['table_id'] = (int)$res['id'];
            // 表单ID对应
            foreach ($result['fields'] as &$temp){
                $fieldNode = $fromModel->getField($table_id, $temp['__vModel__']);
                if (empty($fieldNode))continue;
                $temp['id'] = $fieldNode['id'];
            }
            $this->apiSuccess('请求成功',200,$result);

        }
    }

    /**
     * @title 验证与获取表单数据
     * @param $param
     * @return array
     * @throws \Exception
     * @author hexu
     * @date 2021/7/22 5:45 下午
     */
    private function verifyParam($param){
        if(empty($param['formRef']))throw new \Exception('表单名称不能为空','10000');
        if(empty($param['formModel']))throw new \Exception('模型名称不能为空','10000');
        $result = array();
        foreach ($param['fields'] as $item){
            $node = array(
                'name'=>$item['__vModel__'],
                'type'=>$item['type_node'],
                'desc'=>$item['__config__']['label']
            );
            if(!empty($item['id']))$node['id'] = $item['id'];
            $result[] = $node;
        }
        return $result;
    }

    /**
     * ajax数据返回json数据成功
     */
    function apiSuccess($msg="操作成功",$code=200,$data=[],$redirect_url='')
    {
        header('Content-Type:application/json');//加上这行,前端那边就不需要var result = $.parseJSON(data);
        $ret = ["code" => $code,"msg" => $msg, "data" => $data,'redirect_url'=>$redirect_url];
        echo json_encode($ret,JSON_UNESCAPED_UNICODE);
        die();
    }

    /**
     * ajax数据返回json数据成功
     */
    function apiError($msg="请求失败",$code=400,$redirect_url='')
    {
        header('Content-Type:application/json');//加上这行,前端那边就不需要var result = $.parseJSON(data);
        $ret = ["code" => $code,"msg" => $msg];
        echo json_encode($ret,JSON_UNESCAPED_UNICODE);
        die();
    }

    /**
     * @title 获取参数
     * @return mixed
     * @author hexu
     * @date 2021/7/22 2:24 下午
     */
    public function getParam(){
        $data = file_get_contents("PHP://input");
        $param = json_decode($data,true);
        return $param;
    }



}
