<?php
require __DIR__ . '/../vendor/autoload.php';

$autoloader = new Kadet\Utils\AutoLoader('App', '/Tests/App/');
$autoloader->register();

// sometimes it's needed to have application initialised
$cfg = file_get_contents(__DIR__ . '/Files/config.json', FILE_USE_INCLUDE_PATH);
$app = new \Framework\Application(json_decode($cfg, true));
//$app->init();