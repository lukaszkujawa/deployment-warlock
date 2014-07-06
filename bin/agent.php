<?php

namespace DW;

function show_options() {
	printf( "USAGE: php agent.php -p [projectId]\n" );
}

set_time_limit(0);

defined('APP_PATH') ||
	define('APP_PATH', realpath( __DIR__ . '/../') );

require 'vendor/autoload.php';

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

try {
	\DW\ORM\Client::getInstance(new \PDO('sqlite:/' . APP_PATH . '/depwiz.db'));
}
catch ( PDOException $e ) {
    echo 'Connection to the database failed: ' . $e->getMessage();
    exit(1);
}

$options = getopt("p:");

if( ! isset( $options['p'] ) ) {
	show_options();
	exit(0);
}

$project = \DW\Model\Project::getById( $options['p'] );

if( ! $project instanceof \DW\Model\Project ) {
	printf( "Can't find project id %d\n", $options['p']  );
	exit(-1);
}

/*
$dep = new \DW\IterativeProjectDeployer( $project );

while( ( $line = $dep->next() ) ) {
	print_r( $line );
	echo "------\n";
}

die();
*/


$server = IoServer::factory(
	 new HttpServer(
            new WsServer(
    			new \DW\WebSocket\Server( $project ))),
    8081
);

echo "Starting agent\n";

$server->run();