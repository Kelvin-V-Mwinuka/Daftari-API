<?php
declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;

//require_once(__DIR__ . '/../../Models/UserModel.php');

return function (App $app) {
    $app->get('/', function (Request $req, Response $res){
        return $this->get('view')->render($res, 'base.html');
    });
};
