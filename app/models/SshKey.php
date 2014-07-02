<?php

namespace DW\Model;

class SshKey extends \DW\ORM\Model {
	
	public $id;
	public $name;
	public $private_value;
	public $public_value;

	protected static $tableName = 'ssh_keys';
	
}