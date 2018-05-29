<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Helper\Helper;
use Http\Request;

ini_set('display_errors','On');
error_reporting(E_ALL & ~E_NOTICE);

$framework = new Framework();
$response = $framework->handle(new Request());
$response->send();

// Helper::debug($response);
