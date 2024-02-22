<?php
// **********************
// CLASS DECLARATION
// **********************

class settings extends Database
{ 	// class : begin

// **********************
// ATTRIBUTE DECLARATION
// **********************

var $id;   // KEY ATTR. WITH AUTOINCREMENT

		var $code_url;   // (normal Attribute)
		var $login_failure_count;   // (normal Attribute)
		var $ip_blocking_duration;   // (normal Attribute)
		var $is_under_maintenance;   // (normal Attribute)
		var $safe_ip_addresses;   // (normal Attribute)
		var $created;   // (normal Attribute)
		var $modified;   // (normal Attribute)

var $criteria; //criteria of search
var $numRows; // numRows for total records

// **********************
// CONSTRUCTOR METHOD
// **********************
function __construct()
{ 
    $numArgs = func_num_args();
        
	if ($numArgs >=1)
	{
            parent::__construct(func_get_arg(0));
	}	
	else
            parent::__construct();   
   
    $this->condition = '1 = 1';
}

// **********************
// SELECT METHOD / LOAD
// **********************

function select()
{
$query = "SELECT * FROM settings WHERE ";
$condition= $this->condition;
$param = $this->param;
	if (is_array($condition)) 
	{
		if(isset($condition['where_clause']) && !empty($condition['where_clause']))
		{
			$query .= $condition['where_clause'];
		} // if ends here of checking where_clause
		else 
		{
			$this->error = "where clause is  missing";
			return $this->error;
		} // else ends here of checking where_clause
		
		if(isset($condition['limit_clause']) && !empty($condition['limit_clause']))
		{
			$query .= $condition['limit_clause'];
		} // if ends here of checking limit_clause
		
	}// if ends here
	else
	{
		$query .= $condition;
	} // else ends here
		
	try
	{		
		$sql = $this->conn->prepare("$query");
		//echo "<pre>";print_r($sql);	
		$sql->setFetchMode(PDO::FETCH_ASSOC);
		$sql->execute($param);
		$result = $sql->fetch();	
		//print_r($result);	

$this->id = $sqlResult["id"];
$this->code_url = $sqlResult["code_url"];
$this->login_failure_count = $sqlResult["login_failure_count"];
$this->ip_blocking_duration = $sqlResult["ip_blocking_duration"];
$this->is_under_maintenance = $sqlResult["is_under_maintenance"];
$this->safe_ip_addresses = $sqlResult["safe_ip_addresses"];
$this->created = $sqlResult["created"];
$this->modified = $sqlResult["modified"];
		
		
		return $result;
	} // try block ends here
	catch(PDOException $e)
	{
		file_put_contents("PDOErrors.txt", $e->getMessage(), FILE_APPEND);
    	return $this->error = "Query execution error";
	} // catch block ends here
} // function ends here

// **********************
// SELECTALL  METHOD / LOAD
// **********************

function selectAll()
{
		
try
{
	$sql = $this->conn->prepare("SELECT * FROM settings");
	//echo "<pre>";print_r($sql);
	$sql->setFetchMode(PDO::FETCH_ASSOC);
	$sql->execute();
	$sqlResult = $sql->fetchAll();
	

$this->id = $sqlResult["id"];
$this->code_url = $sqlResult["code_url"];
$this->login_failure_count = $sqlResult["login_failure_count"];
$this->ip_blocking_duration = $sqlResult["ip_blocking_duration"];
$this->is_under_maintenance = $sqlResult["is_under_maintenance"];
$this->safe_ip_addresses = $sqlResult["safe_ip_addresses"];
$this->created = $sqlResult["created"];
$this->modified = $sqlResult["modified"];
return $sqlResult;
} // try block ends here
catch(PDOException $e)
{
		file_put_contents("PDOErrors.txt", $e->getMessage(), FILE_APPEND);
    	return $this->error = "Query execution error";
    
} // catch block ends here
} // function ends here
				

// **********************
// selectByCriteria  METHOD / LOAD
// **********************

function selectByCriteria()
{
	
$query = "SELECT * FROM settings WHERE ";
$condition= $this->condition;
$param = $this->param;
	if (is_array($condition)) 
	{
		if(isset($condition['where_clause']) && !empty($condition['where_clause']))
		{
			$query .= $condition['where_clause'];
		} // if ends here of checking where_clause
		else 
		{
			$this->error = "where clause is  missing";
			return $this->error;
		} // else ends here of checking where_clause
		
		if(isset($condition['limit_clause']) && !empty($condition['limit_clause']))
		{
			$query .= $condition['limit_clause'];
		} // if ends here of checking limit_clause
		
	}// if ends here
	else
	{
		$query .= $condition;
	} // else ends here
		
	try
	{		
		$sql = $this->conn->prepare("$query");
		//echo "<pre>";print_r($sql);	
		$sql->setFetchMode(PDO::FETCH_ASSOC);
		$sql->execute($param);
		$result = $sql->fetchAll();	
		//print_r($result);

$this->id = $sqlResult["id"];
$this->code_url = $sqlResult["code_url"];
$this->login_failure_count = $sqlResult["login_failure_count"];
$this->ip_blocking_duration = $sqlResult["ip_blocking_duration"];
$this->is_under_maintenance = $sqlResult["is_under_maintenance"];
$this->safe_ip_addresses = $sqlResult["safe_ip_addresses"];
$this->created = $sqlResult["created"];
$this->modified = $sqlResult["modified"];
$this->numRows = $sql->rowCount();

		
return $result;
} // try block ends here
catch(PDOException $e)
{
	file_put_contents("PDOErrors.txt", $e->getMessage(), FILE_APPEND);
   	return $this->error = "Query execution error";
	    
} // catch block ends here
} // function ends here

// **********************
// DELETE
// **********************

function delete()
{
	try
	{	
		$sql =  $this->conn->prepare("DELETE FROM settings  WHERE $this->condition");
		//echo "<pre>";print_r($sql);
		$result = $sql->execute();
		$this->numRows = $sql->rowCount();
		return $result;
	} // try block ends here
	catch(PDOException $e)
	{
		file_put_contents("PDOErrors.txt", $e->getMessage(), FILE_APPEND);
    	return $this->error = "Query execution error";
    	
	    
	} // catch block ends here
} // function ends here
    			

// **********************
// INSERT
// **********************

function insert()
{

$this->id = ""; // clear key for autoincrement

$valueClause = array();
$columnClause = array();
	if(isset($this->code_url))
	 { 
		array_push($columnClause,"code_url" );
		array_push($valueClause,"$this->code_url");
	 } 
	if(isset($this->login_failure_count))
	 { 
		array_push($columnClause,"login_failure_count" );
		array_push($valueClause,"$this->login_failure_count");
	 } 
	if(isset($this->ip_blocking_duration))
	 { 
		array_push($columnClause,"ip_blocking_duration" );
		array_push($valueClause,"$this->ip_blocking_duration");
	 } 
	if(isset($this->is_under_maintenance))
	 { 
		array_push($columnClause,"is_under_maintenance" );
		array_push($valueClause,"$this->is_under_maintenance");
	 } 
	if(isset($this->safe_ip_addresses))
	 { 
		array_push($columnClause,"safe_ip_addresses" );
		array_push($valueClause,"$this->safe_ip_addresses");
	 } 
	if(isset($this->created))
	 { 
		array_push($columnClause,"created" );
		array_push($valueClause,"$this->created");
	 } 
	if(isset($this->modified))
	 { 
		array_push($columnClause,"modified" );
		array_push($valueClause,"$this->modified");
	 } 

for ($i=0; $i<count($columnClause);$i++)
	{
		if ($i != 0)
			$tempColumnValues .= ", ";
			
		$tempColumnValues .= "?";
	} 
$columnName = implode(',',$columnClause); 
$columnValue = implode(',',$valueClause);
	try
	{	
		$sql = $this->conn->prepare("INSERT INTO settings ($columnName ) VALUES ( $tempColumnValues )");
		//echo "<pre>";print_r($sql); print_r($valueClause);
		$result = $sql->execute($valueClause);
		$this->id = $this->conn->lastInsertId();	
		return $result;		
	} // try block ends here
	catch(PDOException $e)
	{
		file_put_contents("PDOErrors.txt", $e->getMessage(), FILE_APPEND);
    	return $this->error = "Query execution error";
	} // catch block ends here
} // function ends here
		
		
		

// **********************
// UPDATE
// **********************

function update()
{
$valueClause = array();
$setClause = array();
	if(isset($this->code_url))
	 { 
		array_push($setClause,"code_url" );
		array_push($valueClause,"$this->code_url");
	 } 
	if(isset($this->login_failure_count))
	 { 
		array_push($setClause,"login_failure_count" );
		array_push($valueClause,"$this->login_failure_count");
	 } 
	if(isset($this->ip_blocking_duration))
	 { 
		array_push($setClause,"ip_blocking_duration" );
		array_push($valueClause,"$this->ip_blocking_duration");
	 } 
	if(isset($this->is_under_maintenance))
	 { 
		array_push($setClause,"is_under_maintenance" );
		array_push($valueClause,"$this->is_under_maintenance");
	 } 
	if(isset($this->safe_ip_addresses))
	 { 
		array_push($setClause,"safe_ip_addresses" );
		array_push($valueClause,"$this->safe_ip_addresses");
	 } 
	if(isset($this->created))
	 { 
		array_push($setClause,"created" );
		array_push($valueClause,"$this->created");
	 } 
	if(isset($this->modified))
	 { 
		array_push($setClause,"modified" );
		array_push($valueClause,"$this->modified");
	 } 

    for ($i=0; $i<count($setClause);$i++)
    {
        if ($i != 0)
        {
            $columnName .= " , $setClause[$i] = ?";
        }
        else
        {
            $columnName .= "$setClause[$i] = ?";
        }
    } 

	try
	{	
		$sql = $this->conn->prepare("UPDATE settings SET  $columnName WHERE $this->condition ");
		//echo "<pre>";print_r($sql); print_r($valueClause);
		$result = $sql->execute($valueClause);
		$this->id = $sql->rowCount();
		return $result;
	} // try block ends here
	catch(PDOException $e)
	{
		file_put_contents("PDOErrors.txt", $e->getMessage(), FILE_APPEND);
    	return $this->error = "Query execution error";
    
	} // catch block ends here
} // function ends here

} // class ends here
