<?php 

namespace DW\ORM;

class Client {

	private $adapter;

	private static $instance;

	public function setAdapter( $adapter ) {
		$this->adapter = $adapter;
	}

	public function getAdapter() {
		return $this->adapter;
	}

	public function execute( $sql, $params = array(), $fetchClass = false ) {
		$sth = $this->adapter->prepare( $sql );

		if( !$sth ) {
			$this->throwSthException( $sth );
		}

		if( $fetchClass ) {
			$sth->setFetchMode( \PDO::FETCH_CLASS, $fetchClass );
		}

		foreach( $params as $name => $value ) {
			$sth->bindValue( ':' . $name, $value );
		}

		$sth->execute();

		return $sth;
	}

	public function getLastInsertId() {
		return $this->adapter->lastInsertId();
	}

	public static function getInstance( \PDO $adapter = null ) {
		if( self::$instance == null ) {
			self::$instance = new Client( $adapter );
			self::$instance->setAdapter( $adapter );
		}

		return self::$instance;
	}

	private function throwSthException() {
		throw new \Exception( 'Incorrect SQL: ' . implode( ', ', $this->adapter->errorInfo() ) );
	}

}