<?php

declare(strict_types = 1);
session_start();

//ini_set('session.cookie_lifetime', '864000');

/*
 * load composer
 */
require dirname(__DIR__) . '/vendor/autoload.php';
require_once '../App/Utils/debug.php';

error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');

$router = new Core\Router();
$router->add('', ['controller' => 'Home', 'action' => 'index']);
$router->add('signup', ['controller' => 'Signup', 'action' => 'new']);
$router->add('login', ['controller' => 'Login', 'action' => 'new']);

$router->dispatch($_SERVER['QUERY_STRING']);
