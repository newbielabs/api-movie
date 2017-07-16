<?php
defined('APP') OR exit('No direct script access allowed');

$config['settings']['displayErrorDetails'] = true;
$config['settings']['logger']['name'] 	= 'mvsvc';
$config['settings']['logger']['level'] 	= Monolog\Logger::DEBUG;
$config['settings']['logger']['path'] 	= __DIR__ . '/logs/app.log';

// Sources
$config['sources'] = 'indoxxi';
