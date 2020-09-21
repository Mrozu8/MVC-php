<?php

require '../vendor/autoload.php';

session_start();

error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');

$router = new Core\Router();
$router->add('', ['controller' => 'Home', 'action' => 'index']);

$router->add('register', ['controller' => 'Admin\Register', 'action' => 'register']);
$router->add('create-account', ['controller' => 'Admin\Register', 'action' => 'validateData']);
$router->add('login', ['controller' => 'Admin\Login', 'action' => 'login']);
$router->add('login-action', ['controller' => 'Admin\Login', 'action' => 'validateData']);
$router->add('logout', ['controller' => 'Admin\Login', 'action' => 'logout']);

$router->add('admin/posts', ['controller' => 'Admin\Posts', 'action' => 'index']);
$router->add('admin/post-create', ['controller' => 'Admin\Posts', 'action' => 'create']);
$router->add('admin/{id:\d+}/post-edit', ['controller' => 'Admin\Posts', 'action' => 'edit']);
$router->add('admin/{id:\d+}/post-update', ['controller' => 'Admin\Posts', 'action' => 'update']);
$router->add('admin/{id:\d+}/post-delete', ['controller' => 'Admin\Posts', 'action' => 'delete']);

$router->add('{controller}/{action}');
$router->add('{controller}/{id:\d+}/{action}');
$router->add('admin/{controller}/{action}', ['namespace' => 'Admin']);


$router->dispatch($_SERVER['QUERY_STRING']);
