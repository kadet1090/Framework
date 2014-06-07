<?php
require 'vendor/autoload.php';

$autoloader = new Kadet\Utils\AutoLoader('App', './Tests/App/');
$autoloader->register();