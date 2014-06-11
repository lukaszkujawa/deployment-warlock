<?php

namespace DW\Model;

class Deployment extends \DW\ORM\Model {
	
	public $name;
	public $script;
	public $last_update;

	protected static $tableName = 'deployments';
	
}