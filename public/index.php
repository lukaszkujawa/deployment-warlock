<?php

$allowed = array(
	'127.0.0.1',
	'192.168.41.1',
	'localhost',
	'::1'
);

if( ! in_array( $_SERVER['REMOTE_ADDR'], $allowed ) ) {
	echo json_encode(array(
		'error' => 1,
		'errorMessage' => 'Connection from '. $_SERVER['REMOTE_ADDR'].' rejected by server. Please add host IP to the allowed group.'
	));
	exit();
}

set_time_limit(0);

defined('APP_PATH') ||
	define('APP_PATH', realpath( __DIR__ . '/../') );

require 'vendor/autoload.php';

try {
	\DW\ORM\Client::getInstance(new PDO('sqlite:/' . APP_PATH . '/depwiz.db'));
}
catch ( PDOException $e ) {
    echo 'Connection to the database failed: ' . $e->getMessage();
    exit(1);
}

$app = new \Slim\Slim(array(
    'templates.path' =>  APP_PATH . '/templates/',
));

$dispatcher = new \DW\MVC\Dispatcher( $app );
$dispatcher->prepare();

$app->run();
