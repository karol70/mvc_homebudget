<?php



require_once dirname(__DIR__).'/vendor/autoload.php';



$loader = new \Twig\Loader\ArrayLoader([
   'index' => 'Hello {{ name }}!',
]);
$twig = new \Twig\Environment($loader);



error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');

session_start();

 $router = new Core\Router();

$router->add('',['controller' => 'Home', 'action' => 'index']);
$router->add('{controller}/{action}');
$router->add('{controller}/{id:\d+}/{action}');
$router->add('admin/{controller}/{action}',['namespace' => 'Admin']);
$router->add('signup/activate/{token:[\da-f]+}', ['controller' => 'Signup', 'action' => 'activate']);
$router->add('logout', ['controller' => 'Login', 'action' => 'destroy']);
$router->add('login', ['controller' => 'Login', 'action' => 'new']);
$router->add('categories', ['controller' => 'Setting', 'action' => 'new']);
$router->add('usersettings', ['controller' => 'Setting', 'action' => 'userSettings']);
$router->add('limit', ['controller' => 'Expense', 'action' => 'isLimit']);




$router->dispatch($_SERVER['QUERY_STRING']);