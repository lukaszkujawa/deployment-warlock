<?php

namespace DW\ORM;

abstract class Model {
	
	protected static $tableName;
	protected static $joins;

	private static $publicProperties;

	protected $id;

	public function __construct( $options = array() ) {
		if( ! empty( $options ) ) {
			$this->populate( $options );
		}
	}

	public function populate( $data ) {
		if( is_object( $data ) ) {
			$data = (array) $data;
		}

		if( isset( $data['id'] ) ) {
			unset( $data['id'] );
		}

		foreach( $data as $key => $val ) {
			$this->$key = $val;
		}
	}

	public static function getAdapter() {
		return Client::getInstance()->getAdapter();
	}

	public static function getClient() {
		return Client::getInstance();
	}

	public static function getTableName() {
		$childClass = get_called_class();

		if( $childClass::$tableName == null ) {
			$childClass::$tableName = preg_replace( '/.*\//', '', strtolower( get_called_class() ) );
		}

		return $childClass::$tableName;
	}

	public static function getAll() {
		$childClass = get_called_class();
		$tableName = self::getTableName();

		$sql = sprintf( 'SELECT * FROM %s', $tableName );

		$sth = self::getClient()->execute( $sql );
		return $sth->fetchAll( \PDO::FETCH_CLASS, $childClass );
	}

	public static function getById( $id ) {
		$childClass = get_called_class();
		$tableName = self::getTableName();

		$sql = sprintf( 'SELECT * FROM %s WHERE id = :id', $tableName );
		$sth = self::getClient()->execute( $sql, array( 'id' => $id ), $childClass );
		
		return $sth->fetch( \PDO::FETCH_CLASS );
	}

	public function getPublicProperties() {
		if( self::$publicProperties == null ) {
			self::$publicProperties = array();
			$ref = new \ReflectionClass( get_class( $this ) );
			foreach( $ref->getProperties( \ReflectionProperty::IS_PUBLIC ) as $property ) {
				self::$publicProperties[] = $property->name;
			}
		}

		return self::$publicProperties;
	}

	public function getValues( $deepSearch = false ) {
		$values = array('id' => $this->getId() );

		foreach( $this->getPublicProperties() as $name ) {
			$values[ $name ] = $this->$name;
		}

		return $values;				
	}

	public function _getClient() {
		if( self::$client == null ) {
			self::$client = Client::getInstance()->getAdapter();
		}

		return self::$client;
	}

	public function getId() {
		return $this->id;
	}

	public function setId( $id ) {
		return $this->id = $id;
	}

	public function save() {
		if( $this->getId() == null ) {
			return $this->_insert();
		}
		else {
			return $this->_save();
		}
	}

	public function delete() {
		$id = $this->getId();
		$client = self::getClient();

		$sql = sprintf( 'DELETE FROM `%s` WHERE id = :id', self::getTableName() );
		
		$client->execute( $sql, array('id' => $id));
	}

	private function _insert() {
		$client = self::getClient();

		$params = array();
		$insert = array();
		$bind = array();
		$values =  $this->getValues();
		unset( $values['id'] );
		
		foreach( $values as $name => $value ) {
			$insert[] = ':' . $name;
			$params[] = $name;
			$bind[ $name ] = $value; 
		}

		$sql = sprintf( 'INSERT INTO `%s` (id,%s) VALUES (null,%s) ', 
						self::getTableName(),
						implode( ',', $params ),
						implode( ',', $insert ) );

		$client->execute( $sql, $bind );

		$this->id = $client->getLastInsertId();
	}

	private function _save() {
		$client = self::getClient();
		$values =  $this->getValues();
		unset( $values['id'] );

		$update = array();
		$bind = array();
		foreach( $values as $name => $value ) {
			$update[] =  '`' . $name . '`=:' . $name;
			$bind[ $name ] = $value;
		}

		$bind['id'] = $this->getId();

		$sql = sprintf( 'UPDATE `%s` SET %s WHERE `id`=:id',
						self::getTableName(),
						implode( ',', $update ) );

		$client->execute( $sql, $bind );
	}


}