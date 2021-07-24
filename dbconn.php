<?php
error_reporting(0);
ini_set('display_errors', 0);
//DataBase Connection  Class
class DatabaseConn
{
	public $serverName;
	public $hostName;
	public $password;
	public $dbName;
	public $conn;
	public function __construct()
	{
		$this->serverName='localhost';
		$this->hostName='root';
		$this->password='root';
		$this->dbName='product_inventory';
	}
	public function Connection()
	{
		$this->conn = new mysqli($this->serverName, $this->hostName, $this->password, $this->dbName);
		if(!$this->conn)  
		{  
			echo 'Database Connection Error'.mysqli_connect_error($this->conn);   
		}  
		return $this->conn;
	}
}
?>