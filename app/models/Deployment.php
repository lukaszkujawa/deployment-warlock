<?php

namespace DW\Model;

class Deployment extends \DW\ORM\Model {
	

	public $name;
	public $script;

	protected static $tableName = 'deployments';

	
}