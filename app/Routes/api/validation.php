<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;

return function(App $app){
    
    $app->get('/api/validate/email', function(Request $req, Response $res){
        // Check wether the email is available
    });

    $app->get('/api/validate/username', function(Request $req, Response $res){
        // Check whether the username is available
    });
};