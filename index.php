<?php
// router.php
use Guest\EasyCom\Config;

require __DIR__ . '/vendor/autoload.php';
if (preg_match('/\.(?:png|jpg|jpeg|gif|html)$/', $_SERVER["REQUEST_URI"]))
  return false;    // 直接返回请求的文件
else {
    require_once "RequestLoader.php";
    Config::$baseConfigPath = __DIR__."/config/";
    RequestLoader::run();
}
?>
