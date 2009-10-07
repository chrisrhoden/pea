<?php
// This is where my ugly code lives.
session_start();
define('BASEDIR', dirname(__FILE__).'/..');
define('CONFIGDIR', BASEDIR.'/config');
function __autoload($class){
    if(preg_match('/^\w+Controller$/',$class)){
        if(file_exists(BASEDIR."/app/controllers/$class.php")){
            require(BASEDIR."/app/controllers/$class.php");
        } else {
            die("Error: unable to load controller $class.");
        }
    } elseif(file_exists(BASEDIR."/app/models/$class.php")){
        require(BASEDIR."/app/models/$class.php");
    }
}
$uri = explode('?',$_SERVER['REQUEST_URI'],2);
$uri = $uri[0];
define('REQUEST_URI', $uri);
unset($uri);
function render_template($template_file, $variables){
    foreach($variables as $name => $value){
        ${$name} = $value;
    }
    ob_start();
    include(BASEDIR.'/app/views/'.$template_file.'.php');
    $main_body    = ob_clean_contents();
    if(file_exists(dirname(BASEDIR.'/app/views/'.$template_file.'.php').'/template.php')){
        ob_start();
        require(dirname(BASEDIR.'/app/views/'.$template_file.'.php').'/template.php');
        $main_body = str_replace('%CHILD%', $main_body, ob_clean_contents());
    }
    if(file_exists(BASEDIR.'/app/views/template.php')){
        ob_start();
        require(BASEDIR.'/app/views/template.php');
        $main_body = str_replace('%CHILD%', $main_body, ob_clean_contents());
    }
    
    return $main_body;
}
function ob_clean_contents(){
    $ret = ob_get_contents();
    ob_end_clean();
    return $ret;
}