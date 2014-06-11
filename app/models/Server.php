<?php

namespace DW\Model;

class Server extends \DW\ORM\Model {
	
	public $name;
	public $address;
	public $login;
	public $auth_type;
	public $password;
	public $ssh_key;
	public $last_update;

	protected static $tableName = 'servers';


	

}