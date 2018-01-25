<?php

use Chat\Controller\RoomController;

$route->group('/', function() {
    $this->get('/', [new RoomController, 'index']);

    $this->get('/login', [new RoomController, 'login']);
    
    $this->get('/logout', [new RoomController, 'logout']);
    
    $this->get('/room', [new RoomController, 'room']);
});
