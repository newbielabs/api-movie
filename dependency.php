<?php
defined('APP') OR exit('No direct script access allowed');

$container = $app->getContainer();

$container['logger'] = function($c) {
    $log_conf = $c['settings']['logger'];
    $logger = new \Monolog\Logger($log_conf['name']);
    $logger->pushHandler(new \Monolog\Handler\StreamHandler($log_conf['path'], $log_conf['level']));
    $logger->pushHandler(new \Monolog\Handler\FirePHPHandler());
    return $logger;
};
$container['errorHandler'] = function ($c) {
    return function ($request, $response, $exception) use ($c) {
        return $c['response']->withStatus(500)
                             ->withHeader('Content-Type', 'text/html')
                             ->withJson(array('code' => 500, 'message' => 'Internal Server Error'));
    };
};
$container['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        return $c['response']->withStatus(404)
                             ->withHeader('Content-Type', 'text/html')
                             ->withJson(array('code' => 404, 'message' => 'Not Found'));
    };
};
