<?php
// **********************
// CLASS DECLARATION
// **********************

class category_master extends Database
{ 	// class : begin

// **********************
// ATTRIBUTE DECLARATION
// **********************

var $id;   // KEY ATTR. WITH AUTOINCREMENT

		var $name;   // (normal Attribute)
		var $parent_category_id;   // (normal Attribute)
		var $is_deleted;   // (normal Attribute)
		var $status;   // (normal Attribute)
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
$query = "SELECT * FROM category_master WHERE ";
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
$this->name = $sqlResult["name"];
$this->parent_category_id = $sqlResult["parent_category_id"];
$this->is_deleted = $sqlResult["is_deleted"];
$this->status = $sqlResult["status"];
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
	$sql = $this->conn->prepare("SELECT * FROM category_master");
	//echo "<pre>";print_r($sql);
	$sql->setFetchMode(PDO::FETCH_ASSOC);
	$sql->execute();
	$sqlResult = $sql->fetchAll();
	

$this->id = $sqlResult["id"];
$this->name = $sqlResult["name"];
$this->parent_category_id = $sqlResult["parent_category_id"];
$this->is_deleted = $sqlResult["is_deleted"];
$this->status = $sqlResult["status"];
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
	
$query = "SELECT * FROM category_master WHERE ";
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
$this->name = $sqlResult["name"];
$this->parent_category_id = $sqlResult["parent_category_id"];
$this->is_deleted = $sqlResult["is_deleted"];
$this->status = $sqlResult["status"];
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
		$sql =  $this->conn->prepare("DELETE FROM category_master  WHERE $this->condition");
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
	if(isset($this->name))
	 { 
		array_push($columnClause,"name" );
		array_push($valueClause,"$this->name");
	 } 
	if(isset($this->parent_category_id))
	 { 
		array_push($columnClause,"parent_category_id" );
		array_push($valueClause,"$this->parent_category_id");
	 } 
	if(isset($this->is_deleted))
	 { 
		array_push($columnClause,"is_deleted" );
		array_push($valueClause,"$this->is_deleted");
	 } 
	if(isset($this->status))
	 { 
		array_push($columnClause,"status" );
		array_push($valueClause,"$this->status");
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
		$sql = $this->conn->prepare("INSERT INTO category_master ($columnName ) VALUES ( $tempColumnValues )");
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
	if(isset($this->name))
	 { 
		array_push($setClause,"name" );
		array_push($valueClause,"$this->name");
	 } 
	if(isset($this->parent_category_id))
	 { 
		array_push($setClause,"parent_category_id" );
		array_push($valueClause,"$this->parent_category_id");
	 } 
	if(isset($this->is_deleted))
	 { 
		array_push($setClause,"is_deleted" );
		array_push($valueClause,"$this->is_deleted");
	 } 
	if(isset($this->status))
	 { 
		array_push($setClause,"status" );
		array_push($valueClause,"$this->status");
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
		$sql = $this->conn->prepare("UPDATE category_master SET  $columnName WHERE $this->condition ");
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
