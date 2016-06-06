<?php
//define ('WEBROOT', str_replace('index.php', '', $_SERVER['SCRIPT_NAME']));
define ('ROOT',str_replace('index.php', '', $_SERVER['SCRIPT_FILENAME']));
session_start();
require_once (ROOT.'core/VK_Controller.php');

$param = explode('/', $_GET['p']);
if($param[0] != '')
   $controller = ucfirst($param[0]);
else
   $controller = 'Welcome';
if(isset($param[1]))
   $action = $param[1];
else
   $action = 'index';

if(file_exists(ROOT.'controllers/'.$controller.'.php')){
   require_once ('controllers/'.$controller.'.php');
   $controller = new $controller();
   if (method_exists($controller, $action)) {
      if(isset($_GET['t']))
         if(isset($_GET['v']))
            $controller->$action($_GET['t'], $_GET['v']);
         else
            $controller->$action($_GET['t']);
      else
         $controller->$action();
   }
   else {
      require_once 'controllers/Welcome.php';
      $welcome = new Welcome();
      $welcome->page_not_found();
   }
}
else{
   require_once 'controllers/Welcome.php';
   $welcome = new Welcome();
   $welcome->page_not_found();
}
?>