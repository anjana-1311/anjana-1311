<?php
error_reporting(0);
class Database
{ // Class : begin
 
    var $host;  		//Hostname, Server
    var $password; 	//Passwort MySQL
    var $user; 		//User MySQL
    var $database; 	//Datenbankname MySQL
    var $link;
    var $query;
    var $result;
    var $rows;
    var $conn;
    function Database()
    { 
        $numArgs = func_num_args();
        if ($numArgs == 0)
        {
            if($_SERVER['HTTP_HOST'] == '192.168.1.200' || $_SERVER['HTTP_HOST'] == '192.168.0.200')
            {
                $this->host = "localhost"; 
                $this->user = "root";     
                $this->dbPassword = "admin";  
                $this->database = 'assets'; 
                $this->rows = 0;
            }
            try 
            {
                $this->conn = new PDO("mysql:host=localhost;dbname=$this->database;charset=UTF8", $this->user, $this->dbPassword, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
                $this->conn->exec("set names utf8");	
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
            }
            catch(PDOException $e) 
            {
                echo $e->getMessage();
            }
        }
        else
        {
            $this->conn = func_get_arg(0)->conn;
        }
   	} // Method : end
    function closeDb()
    {
        $this->conn = NULL;
    }
} // Class : end 
?>