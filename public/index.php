<?php

use Service\Provider\ConfigProvider;
use Service\Provider\ControllerProvider;
use Service\Provider\DBProvider;
use Service\Provider\RouterProvider;
use Silex\Application;
use Silex\Provider\ServiceControllerServiceProvider;

define("APP_ROOT", dirname(__DIR__));
define("CONFIG_FILE", 'private/config.json');
chdir(APP_ROOT);

require "vendor/autoload.php";

$app = new Application();

$app->register(new ServiceControllerServiceProvider());
$app->register(new ControllerProvider());
$app->register(new RouterProvider());
$app->register(new ConfigProvider());
$app->register(new DBProvider());

if (isset($app['config']->debug)) {
    $app['debug'] = $app['config']->debug;
}

if (isset($app['config']->enableJsonParse)) {
    $app->before(function (Request $request) {
        if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
            $data = json_decode($request->getContent(), true);
            $request->request->replace(is_array($data) ? $data : array());
        }
    });
}

$app->run();
