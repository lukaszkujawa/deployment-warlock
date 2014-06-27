<?php

namespace DW;

class SSHClient {

	private $server;

	public function __construct( \DW\Model\Server $server ) {
		$this->server = $server;
	}

	public function uploadFile( $filePath, $destFileName ) {
		$cmd = sprintf( 'scp %s %s@%s:.',
						$filePath,
						$this->server->login,
						$this->server->address );

		$connection = ssh2_connect( $this->server->address, 22);
		ssh2_auth_password($connection, $this->server->login, $this->server->password );

		if( ! ssh2_scp_send( $connection, $filePath, $destFileName, 0700 ) ) {
			throw new \Exception('Couldn\'t copy the file');
		}
	}

	public function executeRemoteScript( $path, $stdoutCallback, $stderrCallback ) {
		$connection = ssh2_connect( $this->server->address, 22 );
		ssh2_auth_password( $connection, $this->server->login, $this->server->password );

		$stream = ssh2_exec($connection, $path );
		$errorStream = ssh2_fetch_stream($stream, SSH2_STREAM_STDERR);

		while ( !feof( $stream ) && !feof( $errorStream  ) ) {
			while( $line = fgets( $stream ) ) {
				$stdoutCallback( $line );
			}
			while( $line = fgets( $errorStream ) ) {
				$stderrCallback( $line );
			}
		}
	}

}