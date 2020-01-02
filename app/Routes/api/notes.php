<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;

return function(App $app){
    $app->post('/api/notes/create', function(Request $req, Response $res){
        // Create a new note

        $body = $req->getParsedBody();

        $user = $this->get('mongodb')->users->findOne([
            '_id' => new MongoDB\BSON\ObjectID($body['user_id'])
        ]);

        $insertOneResult = $this->get('mongodb')->notes->insertOne([
            'user_id' => $user['_id'],
            'journal_id' => new MongoDB\BSON\ObjectID($body['journal_id']),
            'title' => $body['title'],
            'private' => $body['private'],
            'text' => $body['text'],
            'tags' => $body['tags']
        ]);

        $insertOneResult->getInsertedCount() == 1 ?
        
        $res->getBody()->write(json_encode([
            'status' => 'Success',
            'message' => 'Note created'
        ])) :
        
        $res->getBody()->write(json_encode([
            'status' => 'Failed',
            'message' => 'Could not create note'
        ])) ;

        if($user != null){
            // If user is not null, insert note
        } else {
            $res->getBody()->write(json_encode([
                'status' => 'Failed',
                'message' => 'Could not find user'
            ]));
        }

        return $res;
    });

    $app->get('/api/notes/retrieve', function(Request $req, Response $res){
        /* If journal is specified, retrive all notes from journal if they belong to 
        specified user. Otherwise, retrieve all notes that belong to the specified user. */
    });

    $app->post('/api/notes/update', function(Request $req, Response $res){
        /* Update specified note with the specified information if the note
        belongs to the specified user */
    });

    $app->delete('/api/notes/delete', function(Request $req, Response $res){
        /* Delete specified note if the note belongs to the specified user */
    });
};