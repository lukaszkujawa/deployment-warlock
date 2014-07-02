<?php

namespace DW;

class Cache {
	

	private $fileName;

	public function __construct( $fileName ) {
		$this->fileName = $fileName;
	}

	public function save( $content ) {
		file_put_contents( $this->getFullPath(), $content );
	}

	public function getFullPath() {
		return sprintf( "%s%s", $this->getCacheDirectory(), $this->fileName );
	}

	public function getCacheDirectory() {
		return APP_PATH . '/cache/';
	}

	public function delete() {
		return unlink( $this->getFullPath() );
	}

}