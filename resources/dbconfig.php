<?php
class Database
{  
    private $host = "??????"; // Host
    private $db_name = "??????"; // Databasens namn
    private $username = "??????"; // Mysql användarnamn
    private $password = "???????"; // Lösenord till root
    public $conn;
     
    public function dbConnection()
	{
	    $this->conn = null;  
		$timezone = (new DateTime('now', new DateTimeZone('Europe/Stockholm')))->format('P');
        try
		{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password, 
			array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8" ));
			$this->conn->exec("SET time_zone='$timezone'");
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	
        }
		catch(PDOException $exception)
		{
            echo "Connection error. Please try later!. " . $exception->getMessage();
        }
         
        return $this->conn;
    }
}
?>