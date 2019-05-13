<?php
ini_set('display_errors', 1);

$loader = require(__DIR__ .'/../vendor/autoload.php');
$loader->setPsr4('App\\', __DIR__.'/../app');

require __DIR__.'/../app/helpers.php';

$mode = 'dev';
// $mode = 'prod';

(new App\App())->run($mode);