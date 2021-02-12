<?php

declare(strict_types = 1);

ini_set('session.cookie_lifetime', '864000');

/*
 * load composer
 */
require dirname(__DIR__) . '/vendor/autoload.php';
require_once '../App/Utils/debug.php';

echo "Welcome";
$zmienna = 'ksiażka';
dump($zmienna);