<?php 

// Port number(requires for Inbound connections)
$port_number = 9000;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

// Load ratchet mannually
require  './vendor/autoload.php';

// Load Breeze chat framework(Abstract layer to deal with ratchet and web sockets)
require  './Breeze/classes.php';
 
$server = IoServer::factory(

    // Init HTTP server
    new HttpServer(

        // Init Web Socket server
        new WsServer(

            // Init Breeze Chat framework
            new Chat()

        )

    ),

    // Port number
    $port_number

);

// Run Ratchet server
$server->run();
    
?>