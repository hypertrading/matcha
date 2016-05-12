<?php
// Includes & required
session_start();
require 'vendor/autoload.php';
require 'vendor/slim/slim/Slim/Slim.php';

//Init Slim
\Slim\Slim::registerAutoloader();
$config = array(
    'templates.path' => 'views',
    'debug' => true);
$app = new \Slim\Slim($config);


//Routes
$app->get('/', function() use ($app)
{
   $app->flash('info', 'Coucou');
   $app->render('header.php');
   $app->render('home.php');
})->name('home');

$app->get('/connexion', function() use ($app)
{
   $app->flash('info', 'Coucou');
   $app->render('home.php');
})->name('connexion');


$app->run();
$app->render('footer.php');
?>