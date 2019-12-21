<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;

return function(App $app){
    $app->post('/api/journals/create', function(Request $req, Response $res){
        // Create a new journal
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