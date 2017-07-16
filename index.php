<?php
define('APP', 'MOVIE');

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Psr7Middlewares\Middleware\TrailingSlash;

require 'vendor/autoload.php';
require 'config.php';

spl_autoload_register(function ($classname) {
    require_once('classes/' . $classname . '.php');
});

// Instantiate the App object
$app = new \Slim\App($config);

require 'dependency.php';

// BEGIN: Route callbacks
$app->add(new TrailingSlash(false));

$app->group('/genre', function () {
	$this->get('[/{type}]', \Genre::class . ':getList')->setName('genre_list');
});

$app->group('/cinema', function () {
    $this->get('[/{type}]', \Cinema::class . ':getList')->setName('cinema_list');
    $this->get('/detail/{id}', \Cinema::class . ':getDetail')->setName('cinema_detail');
});

$app->group('/series', function () {
    $this->get('[/{type}]', \Series::class . ':getList')->setName('series_list');
    $this->get('/detail/{id}', \Series::class . ':getDetail')->setName('series_detail');
});
// END: Route callbacks

// Run application
$app->run();
