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
            $data = $this->verifyParam($param);
            // 创建表
            $fromModel = new \Guest\EasyFrom\FromSet();
            if(empty($param['table_id'])){
                $prefix = 'zt_ext_';
                $res = $fromModel->creatTables($prefix.$param['formModel'], $param['formRef'], $param);
                if(!$res)throw new \Exception('表单创建失败','10000');
                $db = EasyDb::init();
                $table_id = $db->getLastId();
            }else{
                $table_id = $param['table_id'];
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
                'type'=>$item['types'],
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
