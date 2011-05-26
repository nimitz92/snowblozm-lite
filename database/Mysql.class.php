<?php 

require_once('DataService.interface.php');

// Concrete MySQL implementation for Data services interface
class Mysql implements DataService {
	protected 
		// connection resource
		$conn;
	
	// Constructor
	public function __construct($database, $user, $pass, $host){
		$this->open($database, $user, $pass, $host);
	}

	// DataService interface : should be private
	public function open($database, $user, $pass, $host){
		// TODO Implement the credentials in ini file
		$this->conn = @mysql_connect($host, $user, $pass);
		if( $this->conn==false ) {
			$err = mysql_errno();
			if( $err==1203 ) {
				die('Our database server is overcome with connections. Please try after some time.');
			}
			die('Could not connect to database host');
		}
		@mysql_select_db($database, $this->conn)
			or die('Could not select database');
	}
	
	// DataService interface
	public function getResult($query, $execute=false, $resulttype=MYSQL_NUM){
		$resultset = @mysql_query($query, $this->conn);
		if($resultset === false) 
			return false;
		if($execute)
			return mysql_affected_rows($this->conn);
		$result = array();
		while( $rowset = mysql_fetch_array($resultset, $resulttype) ) {
			array_push( $result, $rowset );
		}
		return $result;
	}
	
	// DataService interface
	public function escape($param, $addslashes=false){
		if( $addslashes==false ) {
			if( get_magic_quotes_gpc() ) $param = stripslashes($param);
		} else {
			if( !get_magic_quotes_gpc() ) $param = addslashes($param);
		}
		$param = mysql_real_escape_string($param, $this->conn);
		
		return $param;
	}
	
	// DataService interface
	public function getAutoId(){
		return mysql_insert_id($this->conn);
	}
	
	// DataService interface
	public function close(){
		return mysql_close($this->conn);
	}
	
	// DataService interface
	public function getError(){
		return mysql_error($this->conn);
	}
	
	//public function getStatement();
}

?>