<?php

namespace DW;

class Exception extends \Exception {

	protected $messages = array();

	public function __construct( $code, $data = array() ) {
		if( isset( $this->messages[ $code ] ) ) {
			$message = vsprintf( $this->messages[ $code ], $data );
		}
		else {
			$message = sprintf( "Unknown Exception \"%s\"", $code );
		}

		parent::__construct( $message, $code );
	}
	
}