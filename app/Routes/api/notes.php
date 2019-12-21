<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;

return function(App $app){
    $app->post('/api/notes/create', function(Request $req, Response $res){

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