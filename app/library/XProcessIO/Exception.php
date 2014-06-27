<?php

namespace \DW\XProcessIO;

class Exception extends \DW\Exception {

	const CANT_OPEN_FILE = 1;

	protected $messages = array(
		self::CANT_OPEN_FILE => 'Unable to open file "%s"'
	);

}