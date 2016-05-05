<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';
$config = [
    'settings' => [
        'displayErrorDetails' => true]];

$app = new \Slim\App(["settings" => $config]);

$container = $app->getContainer();
$container['view'] = new \Slim\Views\PhpRenderer("../templates/");

$app->get('/', function () {
   require 'views/home.php';
});

$app->render('header.php');
//require 'views/header.php';
$app->run();
require 'views/footer.php';
?>