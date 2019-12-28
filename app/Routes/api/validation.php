<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;

return function(App $app){
    
    $app->get('/api/validate/email', function(Request $req, Response $res){
       
        // Check wether the email is available
        $email = $req->getQueryParams()['email'];
        $user = $this->get('mongodb')->users->findOne(['email' => $email]);

        $response_object = array();

        if($user == null){
            $response_object['available'] = true;
        } else {
            $response_object['available'] = false;
        }

        $res->getBody()->write(json_encode($response_object));
        return $res;
    });

    $app->get('/api/validate/username', function(Request $req, Response $res){
        
        // Check whether the username is available
        $username = $req->getQueryParams()['username'];
        $user = $this->get('mongodb')->users->findOne(['username' => $username]);

        $response_object = array();
        if($user == null){
            $response_object['available'] = true;
        } else {
            $response_object['available'] = false;
        }

        $res->getBody()->write(json_encode($response_object));
        return $res;
    });
};