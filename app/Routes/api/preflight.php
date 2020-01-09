<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;

return function(App $app){

    $app->options('/api/validate/email', function(Request $req, Response $res){
        return $res;
    });

    $app->options('/api/validate/username', function(Request $req, Response $res){
        return $res;
    });

    $app->options('/api/user/register', function (Request $req, Response $res){
        return $res;
    });
};