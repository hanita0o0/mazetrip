<?php

/*
 *  Routes for WebSocket
 */
 // Add route (Symfony Routing Component)
use App\Http\Sockets\MyClass;

$socket->route('/myclass', new MyClass, ['*']);

