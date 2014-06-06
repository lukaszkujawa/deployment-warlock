<?php

namespace DW\Model;

class Server extends \DW\ORM\Model {
	
	public $name;
	public $address;
	public $login;
	public $auth_type;
	public $password;
	public $ssh_key;

	protected static $tableName = 'servers';


	

}