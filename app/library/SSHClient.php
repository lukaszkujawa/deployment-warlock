<?php

namespace DW;

class SSHClient {

	private $server;
	private $stdout;
	private $stderr;

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

	public function executeRemoteScript( $path ) {
		$connection = ssh2_connect( $this->server->address, 22 );
		$this->auth( $connection );
		
		$this->stdout = ssh2_exec( $connection, $path );
		$this->stderr = ssh2_fetch_stream( $this->stdout, SSH2_STREAM_STDERR );
		
		stream_set_blocking( $this->stdout, true );
		stream_set_blocking( $this->stderr, true );
	}

	protected function getStremContent( $stream ) {
		stream_set_timeout( $stream, 1 );
		$content = @stream_get_contents( $this->stdout );
		$info = stream_get_meta_data( $this->stdout );
		if( $info['timed_out'] ) {
			return false;
		}
		else {
			return $content;
		}
	}

	public function next() {
		if( feof( $this->stdout ) && feof( $this->stderr ) ) {
			return false;
		}

		$content = $this->getStremContent( $this->stdout );

		if( ! $content ) {
			return $this->getStremContent( $this->stderr );
		} 
		else {
			return $content;
		}
	}

	protected function auth( $connection ) {
		if( $this->server->auth_type == \DW\Model\Server::AUTH_PUBLIC_KEY ) {
			$this->authPublicKey( $connection );
		}
		else {
			$this->authPassword( $connection );
		}
	}

	private function authPublicKey( $connection ) {
		$privateKey = new \DW\Cache( sprintf( 'key-%d-%d.priv', $this->server->getId(), time() ) );
		$publicKey = new \DW\Cache( sprintf( 'key-%d-%d.pub', $this->server->getId(), time() ) );
		$privateKey->save( $this->server->getSshKey()->private_value );
		$publicKey->save( $this->server->getSshKey()->public_value );
		
		if( ! ssh2_auth_pubkey_file( $connection, $this->server->login, $publicKey->getFullPath(), $privateKey->getFullPath() ) ) {

		}

		$privateKey->delete();
		$publicKey->delete();
	}

	private function authPassword( $connection ) {
		if( ! ssh2_auth_password( $connection, $this->server->login, $this->server->password ) ) {

		}
	}

}