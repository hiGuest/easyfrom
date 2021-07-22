<?php
class RequestLoader{
    public static function run($rules=array()){
        header("content-type:text/html;charset=utf-8");
        self::register();
        self::commandLine();
        self::router($rules);
        self::pathInfo();
    }
    //自动加载
    public static function loadClass($class){
        $class=str_replace('\\', '/', $class);
        $dir=str_replace('\\', '/', __DIR__);
        $class=$dir."/".$class.".php";
        if(!file_exists($class)){
            header("HTTP/1.1 404 Not Found");
        }
        require_once $class;
    }
    //命令行模式
    public static function commandLine(){
        if(php_sapi_name()=="cli"){
            $_SERVER['PATH_INFO']="";
            foreach ($_SERVER['argv'] as $k=>$v) {
                if($k==0) continue;
                $_SERVER['PATH_INFO'].="/".$v;
            }
        }
    }
    //路由模式
    public static function router($rules){
        if(isset($_SERVER['PATH_INFO']) && !empty($rules)){
            $pathInfo=ltrim($_SERVER['PATH_INFO'],"/");
            foreach ($rules as $k=>$v) {
                $reg="/".$k."/i";
                if(preg_match($reg,$pathInfo)){
                    $res=preg_replace($reg,$v,$pathInfo);
                    $_SERVER['PATH_INFO']='/'.$res;
                }
            }
        }
    }
    //pathinfo处理
    public static function pathInfo(){
        $_GET['m']=!empty($_GET['m']) ? ucfirst($_GET['m']) : 'Index';
        $_GET['c']=!empty($_GET['c']) ? ucfirst($_GET['c']) : 'Index';
        $_GET['a']=!empty($_GET['a']) ? $_GET['a'] : 'index';
        $class="\\Controller\\{$_GET['c']}";
        $controller=new $class;
        if(method_exists($controller, $_GET['a'])){
            call_user_func(array($controller,$_GET['a']));
        }else{
            header("HTTP/1.1 404 Not Found");
            echo "404";
        }
    }
    //致命错误回调
    public static function shutdownCallback(){
        $e=error_get_last();
        if(!$e) return;
        self::myErrorHandler($e['type'],'<font color="red">Fatal Error</font> '.$e['message'],$e['file'],$e['line']);
    }
    //错误处理
    protected static function myErrorHandler($errno,$errstr,$errfile,$errline){
        list($micseconds,$seconds)=explode(" ",microtime());
        $micseconds=round($micseconds*1000);
        $micseconds=strlen($micseconds)==1 ? '0'.$micseconds : $micseconds;
        if(php_sapi_name()=="cli"){
            $break="\r\n";
        }else{
            $break="<br/>";
        }
        $mes="[".date("Y-m-d H:i:s",$seconds).":{$micseconds}] ".$errfile." ".$errline." line ".$errstr.$break;
        echo $mes;
    }
    //注册
    public static function register(){
        error_reporting(0);
        set_error_handler(function($errno,$errstr,$errfile,$errline){
            self::myErrorHandler($errno,$errstr,$errfile,$errline);
        });
        register_shutdown_function(function(){
            self::shutdownCallback();
        });
        spl_autoload_register("self::loadClass");
    }
}
