<?php

declare(strict_types = 1);

//ini_set('session.cookie_lifetime', '864000');

/*
 * load composer
 */
require dirname(__DIR__) . '/vendor/autoload.php';
require_once '../App/Utils/debug.php';

error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');

session_start();

$router = new Core\Router();

$router->add('', ['controller' => 'Home', 'action' => 'index']);
$router->add('signup', ['controller' => 'Signup', 'action' => 'new']);
$router->add('login', ['controller' => 'Login', 'action' => 'new']);
$router->add('income', ['controller' => 'Income', 'action' => 'new']);
$router->add('expense', ['controller' => 'Expense', 'action' => 'new']);
$router->add('balance', ['controller' => 'Balance', 'action' => 'new']);
$router->add('{controller}/{action}');

$router->dispatch($_SERVER['QUERY_STRING']);
