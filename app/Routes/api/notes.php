<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;

return function(App $app){
    $app->post('/api/notes/create', function(Request $req, Response $res){
        // Create a new note

        $body = $req->getParsedBody();

        // Check if journal_id is empty and leave it empty in the entry
        $journal_id = null;
        $body['journal_id'] == '' ?
        $journal_id = '' : $journal_id = new MongoDB\BSON\ObjectID($body['journal_id']);

        $insertOneResult = $this->get('mongodb')->notes->insertOne([
            'user_id' => new MongoDB\BSON\ObjectID($body['user_id']),
            'journal_id' => $journal_id,
            'title' => $body['title'],
            'private' => $body['private'],
            'text' => $body['text'],
            'tags' => $body['tags'],
            'likes' => []
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

        return $res;
    });

    $app->get('/api/notes/retrieve', function(Request $req, Response $res){
        /* Retrive all notes from specified journal that belong to 
        specified user. */
        $params = $req->getQueryParams();
        
        if(array_key_exists('user_id', $params) && array_key_exists('journal_id', $params)){

            $journal_id = null;
            $params['journal_id'] == '' ?
            $journal_id = '' : $journal_id = new MongoDB\BSON\ObjectID($params['journal_id']);

            $cursor = $this->get('mongodb')->notes->find([
                'user_id' => new MongoDB\BSON\ObjectID($params['user_id']),
                'journal_id' => $journal_id
            ]);
            
            $notes = array();

            foreach($cursor as $document){
                $document['_id'] = (string)$document['_id'];
                array_push($notes, $document);
            }

            $res->getBody()->write(json_encode([
                'status' => 'Success',
                'notes' => $notes
            ]));

        } else {
            // If user id or journal id aren't provided, return an error
            $res->getBody()->write(json_encode([
                'status' => 'Failed',
                'message' => 'Provide User ID AND Journal ID'
            ]));
        }
        return $res;
    });

    $app->get('/api/notes/all', function(Request $req, Response $res){
        // Retrieve all the notes owned by the specified user
        $params = $req->getQueryParams();
        
        $cursor = $this->get('mongodb')->notes->find([
            'user_id' => new MongoDB\BSON\ObjectID($params['user_id'])
        ]);

        $notes = array();
        foreach($cursor as $document){
            array_push($notes, $document);
        }

        $res->getBody()->write(json_encode([
            'status' => 'Success',
            'notes' => $notes
        ]));

        return $res;
    });

    $app->post('/api/notes/update', function(Request $req, Response $res){
        /* Update specified note with the specified information if the note
        belongs to the specified user */
    });

    $app->delete('/api/notes/delete', function(Request $req, Response $res){
        /* Delete specified note if the note belongs to the specified user */
    });

    $app->post('/api/notes/like', function(Request $req, Response $res){
        /* Create a like for the specified note using the specified user_id */
        
        $body = $req->getParsedBody();

        $note = $this->get('mongodb')->notes->findOne([
            '_id' => new MongoDB\BSON\ObjectID($body['note_id'])
        ]);
        
        // If the like exists, remove it.
        if(in_array($body['user_id'], (array)$note['likes'] )){
            $this->get('mongodb')->notes->updateOne(
                $note,
                [ '$pull' => [ 'likes' => $body['user_id'] ] ]
            );
            $res->getBody()->write(json_encode([
                'status' => 'Success',
                'action' => 'Unliked'
            ]));
        } else {
            // If the like does not exist, place it.
            $this->get('mongodb')->notes->updateOne(
                $note,
                [ '$addToSet' => [ 'likes' => $body['user_id'] ] ]
            );
            $res->getBody()->write(json_encode([
                'status' => 'Success',
                'action' => 'Liked'
            ]));
        }
        
        return $res;
    });

    $app->get('/api/notes/liked', function(Request $req, Response $res){
        /** Get all of the user's liked notes */
        $params = $req->getQueryParams();

        $cursor = $this->get('mongodb')->notes->find([
            'likes' => $params['user_id']
        ]);

        $notes = array();
        
        foreach($cursor as $document){
            $document['_id'] = (string)$document['_id'];
            array_push($notes, $document);
        }

        $res->getBody()->write(json_encode([
            'notes' => $notes
        ]));
        return $res;
    });
};