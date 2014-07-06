<?php

namespace DW\WebSocket;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Server implements MessageComponentInterface {

	protected $clients;
    private $project;

	public function __construct( \DW\Model\Project $project ) {
        $this->project = $project;
        $this->deployer =  new \DW\IterativeProjectDeployer( $project );
        $this->clients = new \SplObjectStorage();
    }

    public function onStdOut( $data ) {
        echo "STDOUT" . $data;
        $this->broadcast( $data );
    }

    public function onStdErr( $data ) {
        echo "STDERR" . $data;
        $this->broadcast( $data );
    }

    public function broadcast( $data ) {
        foreach ($this->clients as $client) {
            $client->send( $data );
        }
    }

    public function onOpen(ConnectionInterface $conn) {
   		$this->clients->attach($conn);

        $data =  $this->deployer->next();
        if( $data ) {
            $this->broadcast( $data );
        }
        else {
            $this->broadcast('[SERVER][0x00]');
        }
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $data = $this->deployer->next();
        if( $data ) {
            $this->broadcast( $data );
        }
        else {
            $this->broadcast('[SERVER][0x00]');
        }
    	

    	/*
    	$numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

        
        foreach ($this->clients as $client) {
            if ($from !== $client) {
                $client->send($msg);
            }
        }
        */
    }

    public function onClose(ConnectionInterface $conn) {
    	// The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        echo "[!] Connection {$conn->resourceId} has disconnected\n";
        exit();
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
    	echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
        exit();
    }

}
