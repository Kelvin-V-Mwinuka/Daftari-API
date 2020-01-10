<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;

return function(App $app){
    
    $app->options('/api/validate/email/{email}', function(Request $req, Response $res, $args){
        file_put_contents('test.txt', 'Options method invoked');
        $res->withHeader('Access-Control-Allow-Origin', '*');
        $res->withStatus(200);
        return $res;
    });

    $app->post('/api/validate/email', function(Request $req, Response $res){
       
        // Check wether the email is available
        $email = $req->getParsedBody()['email'];
        $user = $this->get('mongodb')->users->findOne(['email' => $email]);

        file_put_contents('test.txt', $email);

        $response_object = array();

        if($user == null){
            $response_object['available'] = true;
        } else {
            $response_object['available'] = false;
        }

        $res->getBody()->write(json_encode($response_object));
        return $res;
    });

    $app->post('/api/validate/username', function(Request $req, Response $res){
        
        // Check whether the username is available
        $username = $req->getParsedBody()['username'];
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