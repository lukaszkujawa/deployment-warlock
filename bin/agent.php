<?php

namespace DW;

set_time_limit(0);

defined('APP_PATH') ||
	define('APP_PATH', realpath( __DIR__ . '/../') );

require 'vendor/autoload.php';

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

$server = IoServer::factory(
	 new HttpServer(
            new WsServer(
    			new \DW\WebSocket\Server())),
    8081
);

$server->run();