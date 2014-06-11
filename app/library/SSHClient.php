<?php

namespace DW;

class SSHClient {

	private $server;
	private $descriptorspec = array(
								   0 => array("pipe", "r"), 
								   1 => array("pipe", "w"),  
								   2 => array("pipe", "r") );

	public function __construct( \DW\Model\Server $server ) {
		$this->server = $server;
	}

	public function getAuthOptions() {
		if( $this->server->auth_type == 0 ) {
			return sprintf( "-p%s", 
							$this->server->login, 
							$this->server->password );
		}
		else {
			throw new \Exception('Not Implemented');
		}
	}

	public function uploadFile( $filePath ) {
		$cmd = sprintf( 'scp %s %s@%s:.',
						$filePath,
						$this->server->login,
						$this->server->address );

		$destFileName = '/tmp/' . basename( $filePath );

		$connection = ssh2_connect( $this->server->address, 22);
		ssh2_auth_password($connection, $this->server->login, $this->server->password );

		if( ! ssh2_scp_send($connection, $filePath, $destFileName, 0644) ) {
			throw new \Exception('Couldn\'t copy the file');
		}


	}

}