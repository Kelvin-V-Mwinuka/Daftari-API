<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;

return function(App $app){
    $app->post('/api/user/register', function(Request $req, Response $res){
        
        $params = $req->getParsedBody(); // Associative array

        $insertOneResult = $this->get('mongodb')->users->insertOne([
                'name' => $params['name'],
                'email' => $params['email'],
                'username' => $params['username'],
                'password' => password_hash($params['password'], PASSWORD_DEFAULT),
                'gender' => $params['gender'],
                'private' => "true"
            ]);

        $user = $this->get('mongodb')->users->findOne(
            ['_id' => $insertOneResult->getInsertedID()],
            [
                'projection' => [
                    'password' => 0
                ]
            ]);
        $user['_id'] = (string)$user['_id'];

        $res->getBody()->write(json_encode($user));
        return $res;
    });

    $app->post('/api/user/authenticate', function(Request $req, Response $res){
        
        $params = $req->getQueryParams();

        $user = $this->get('mongodb')->users->findOne(
            // Or must be an array of arrays (each containing one condition)
            [
                '$or' => [ 
                    ['email' => $params['username']],
                    ['username' => $params['username']]
                ]
            ],
        );

        if(password_verify($params['password'], $user['password'])){
            unset($user['password']); // Remove user password from object before sending to the client
            $user['_id'] = (string)$user['_id'];
            $res->getBody()->write(json_encode([
                'status' => 'Success',
                'user' => $user
            ]));
            return $res;
        }

        // Return 401 when authentication has failed
        $res->getBody()->write(json_encode([
            'status' => 'Failed',
            'message' => 'Login failed'
            ]));

        return $res;
    });
};