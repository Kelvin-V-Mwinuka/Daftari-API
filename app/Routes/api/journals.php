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
        $params = $req->getQueryParams();

        if(!array_key_exists('user_id', $params)){
            $res->getBody()->write(json_encode([
                'status' => 'Failed',
                'message' => 'Cannot retrieve journal(s) for null user'
            ]));
            return $res;
        }

        // Return specified journal if it belongs to specified user
        if(array_key_exists('journal_id', $params)){
            $journal = $this->get('mongodb')->journals->findOne([
                '_id' => new MongoDB\BSON\ObjectID($params['journal_id']),
                'user_id' => new MongoDB\BSON\ObjectID($params['user_id'])
            ]);
            $res->getBody()->write(json_encode($journal));
            return $res;
        }

        // If journal isn't specified, return all journals for this user
        $cursor = $this->get('mongodb')->journals->find([
            'user_id' => new MongoDB\BSON\ObjectID($params['user_id'])
        ]);

        $journals = array();

        foreach($cursor as $document){
            $document['_id'] = (string)$document['_id'];  // Convert document ID to string before returning
            array_push($journals, $document);
        }

        $res->getBody()->write(json_encode([
            'status' => 'Success',
            'journals' => $journals
        ]));
        return $res;
    });

    $app->post('/api/journals/update', function(Request $req, Response $res){
        /* Update spcified journal with the specified details if the journal 
        belongs to the specified user */
    });

    $app->delete('/api/journals/delete', function(Request $req, Response $res){
        // Delete specified journal if it belongs to specified user
    });
};