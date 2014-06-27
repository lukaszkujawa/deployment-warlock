<?php

namespace DW;

class XProcesIO {

	const WRITE = 'w';
	const READ = 'r';
	
	private $handlerName;
	private $fh;

	public function __construct( $handlerName, $mode ) {
		$this->handlerName = $handlerName;

		$fileName = self::getFilePath( $handlerName );
		$this->fh = fopen( $fileName, $mode );

		if( ! $this->fh ) {
			throw new \DW\XProcessIO\Exception( 
				\DW\XProcessIO\Exception::CANT_OPEN_FILE, $fileName );
		}
	}

	public function onStdOut( $line ) {
		fwrite( $this->fh, $line );
	}

	public function onStdIn( $line ) {
		fwrite( $this->fh, $line );
	}

	public function close() {
		fclose( $this->fh );
	}

	public static function getFilePath( $token ) {
		return APP_PATH . '/cache/xio/' . $token;
	}

	public static function getContents( $token ) {
		return file_get_contents( self::getFilePath( $token ) );
	}

}