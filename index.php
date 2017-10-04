<?php
include_once 'database.php';

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';

function db() {
    return new DatabaseLayer();
}

$app = new \Slim\App;
$app->get('/hello/{name}', function (Request $request, Response $response) {
    $name = $request->getAttribute('name');
    $response->getBody()->write("Hello, $name");
    return $response;
});

$app->get('/', function () {
    echo "root domain";
});

$app->get('/users', function () {
    db()->getUsers();
});

$app->get('/users/{id_user}', function (Request $request) {
   $id_user = $request->getAttribute('id_user');
   db()->getUser($id_user);
});

$app->get('/users_type', function () {
    db()->getUsersType();
});

$app->get('/users_type/{id_type}', function (Request $request) {
    $id_type = $request->getAttribute('id_type');
    db()->getUserType($id_type);
});


$app->run();
?>