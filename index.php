<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';
$config = [
    'settings' => [
        'displayErrorDetails' => true]];

$app = new \Slim\App(["settings" => $config]);

$app->get('/', function () {
   require 'views/home.php';
});

$app->render('header.php');
//require 'views/header.php';
$app->run();
require 'views/footer.php';
?>