<?php
// **********************
// CLASS DECLARATION
// **********************

class country extends Database
{ 	// class : begin

// **********************
// ATTRIBUTE DECLARATION
// **********************

var $id;   // KEY ATTR. WITH AUTOINCREMENT

		var $sortname;   // (normal Attribute)
		var $name;   // (normal Attribute)
		var $isd_code;   // (normal Attribute)

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
$query = "SELECT * FROM country WHERE ";
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
$this->sortname = $sqlResult["sortname"];
$this->name = $sqlResult["name"];
$this->isd_code = $sqlResult["isd_code"];
		
		
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
	$sql = $this->conn->prepare("SELECT * FROM country");
	//echo "<pre>";print_r($sql);
	$sql->setFetchMode(PDO::FETCH_ASSOC);
	$sql->execute();
	$sqlResult = $sql->fetchAll();
	

$this->id = $sqlResult["id"];
$this->sortname = $sqlResult["sortname"];
$this->name = $sqlResult["name"];
$this->isd_code = $sqlResult["isd_code"];
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
	
$query = "SELECT * FROM country WHERE ";
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
$this->sortname = $sqlResult["sortname"];
$this->name = $sqlResult["name"];
$this->isd_code = $sqlResult["isd_code"];
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
		$sql =  $this->conn->prepare("DELETE FROM country  WHERE $this->condition");
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
	if(isset($this->sortname))
	 { 
		array_push($columnClause,"sortname" );
		array_push($valueClause,"$this->sortname");
	 } 
	if(isset($this->name))
	 { 
		array_push($columnClause,"name" );
		array_push($valueClause,"$this->name");
	 } 
	if(isset($this->isd_code))
	 { 
		array_push($columnClause,"isd_code" );
		array_push($valueClause,"$this->isd_code");
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
		$sql = $this->conn->prepare("INSERT INTO country ($columnName ) VALUES ( $tempColumnValues )");
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
	if(isset($this->sortname))
	 { 
		array_push($setClause,"sortname" );
		array_push($valueClause,"$this->sortname");
	 } 
	if(isset($this->name))
	 { 
		array_push($setClause,"name" );
		array_push($valueClause,"$this->name");
	 } 
	if(isset($this->isd_code))
	 { 
		array_push($setClause,"isd_code" );
		array_push($valueClause,"$this->isd_code");
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
		$sql = $this->conn->prepare("UPDATE country SET  $columnName WHERE $this->condition ");
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
