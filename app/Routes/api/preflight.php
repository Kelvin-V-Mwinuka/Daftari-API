<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;

return function(App $app){
    $app->options('/api/validate/username?{username:.+}', function(Request $req, Response $res){
        $res->withHeader('Access-Control-Allow-Origin', '*');
        $res->withStatus(200);
        return $res;
    });
};