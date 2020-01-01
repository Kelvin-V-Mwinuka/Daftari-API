<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;

return function(App $app){
    $app->post('/api/journals/create', function(Request $req, Response $res){
        
        // Create a new journal
        $body = $req->getParsedBody();

        $user = $this->get('mongodb')->users->findOne([
             '_id' => new MongoDB\BSON\ObjectID($body['user_id'])
        ]);
        
        if($user != null){
            // If user exists, save the journal using their ID
            $insertOneResult = $this->get('mongodb')->journals->insertOne([
                'user_id' => $user['_id'],
                'title' => $body['title'],
                'description' => $body['description'],
                'private' => $body['private'],
                'tags' => $body['tags']
            ]);

            $insertOneResult->getInsertedCount() == 1 ?

            $res->getBody()->write(json_encode([
                'status' => 'Success',
                'message' => 'Journal created'
            ])) :

            $res->getBody()->write(json_encode([
                'status' => 'Failed',
                'message' => 'Failed to insert journal'
            ]));

        } else {
            // Return an error if the user does not exist
            $res->getBody()->write(json_encode([
                'status' => 'Failed',
                'message' => 'Could not find user'
            ]));
        }

        return $res;
    });

    $app->get('/api/journals/retrieve', function(Request $req, Response $res){
        // Return specified journal if it belongs to specified user
        // If journal isn't specified, return all journals for this user
    });

    $app->post('/api/journals/update', function(Request $req, Response $res){
        /* Update spcified journal with the specified details if the journal 
        belongs to the specified user */
    });

    $app->delete('/api/journals/delete', function(Request $req, Response $res){
        // Delete specified journal if it belongs to specified user
    });
};