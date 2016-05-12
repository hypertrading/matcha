<?php
define ('WEBROOT', str_replace('index.php', '', $_SERVER['SCRIPT_NAME']));
define ('ROOT',str_replace('index.php', '', $_SERVER['SCRIPT_FILENAME']));

require (ROOT.'core/VK_Controller.php');

$param = explode('/', $_GET['p']);
if($param[0] != '')
   $controller = $param[0];
else
   $controller = 'Welcome';
if(isset($param[1]))
   $action = $param[1];
else
   $action = 'index';

if(file_exists(ROOT.'controllers/'.$controller.'.php')){
   require('controllers/'.$controller.'.php');
   $controller = new $controller();
   if (method_exists($controller, $action)) {
      $controller->$action();
   } else {
      require 'controllers/Welcome.php';
      $welcome = new Welcome();
      $welcome->page_not_found();
   }
}
else{
   require 'controllers/Welcome.php';
   $welcome = new Welcome();
   $welcome->page_not_found();
}
?>