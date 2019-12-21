<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;

return function(App $app){
    $app->post('/api/user/register', function(Request $req, Response $res){
        $res->getBody()->write('Registered');
        return $res;
    });

    $app->get('/api/user/authenticate', function(Request $req, Response $res){
        $res->getBody()->write('User Returned');
        return $res;
    });
};