<?php
// **********************
// CLASS DECLARATION
// **********************

class supplier_master extends Database
{ 	// class : begin

// **********************
// ATTRIBUTE DECLARATION
// **********************

var $id;   // KEY ATTR. WITH AUTOINCREMENT

		var $type;   // (normal Attribute)
		var $name;   // (normal Attribute)
		var $address;   // (normal Attribute)
		var $country_id;   // (normal Attribute)
		var $state_id;   // (normal Attribute)
		var $city_id;   // (normal Attribute)
		var $contact_person_name;   // (normal Attribute)
		var $mobile_number;   // (normal Attribute)
		var $email_id;   // (normal Attribute)
		var $other_contact_detail;   // (normal Attribute)
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
$query = "SELECT * FROM supplier_master WHERE ";
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
$this->type = $sqlResult["type"];
$this->name = $sqlResult["name"];
$this->address = $sqlResult["address"];
$this->country_id = $sqlResult["country_id"];
$this->state_id = $sqlResult["state_id"];
$this->city_id = $sqlResult["city_id"];
$this->contact_person_name = $sqlResult["contact_person_name"];
$this->mobile_number = $sqlResult["mobile_number"];
$this->email_id = $sqlResult["email_id"];
$this->other_contact_detail = $sqlResult["other_contact_detail"];
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
	$sql = $this->conn->prepare("SELECT * FROM supplier_master");
	//echo "<pre>";print_r($sql);
	$sql->setFetchMode(PDO::FETCH_ASSOC);
	$sql->execute();
	$sqlResult = $sql->fetchAll();
	

$this->id = $sqlResult["id"];
$this->type = $sqlResult["type"];
$this->name = $sqlResult["name"];
$this->address = $sqlResult["address"];
$this->country_id = $sqlResult["country_id"];
$this->state_id = $sqlResult["state_id"];
$this->city_id = $sqlResult["city_id"];
$this->contact_person_name = $sqlResult["contact_person_name"];
$this->mobile_number = $sqlResult["mobile_number"];
$this->email_id = $sqlResult["email_id"];
$this->other_contact_detail = $sqlResult["other_contact_detail"];
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
	
$query = "SELECT * FROM supplier_master WHERE ";
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
$this->type = $sqlResult["type"];
$this->name = $sqlResult["name"];
$this->address = $sqlResult["address"];
$this->country_id = $sqlResult["country_id"];
$this->state_id = $sqlResult["state_id"];
$this->city_id = $sqlResult["city_id"];
$this->contact_person_name = $sqlResult["contact_person_name"];
$this->mobile_number = $sqlResult["mobile_number"];
$this->email_id = $sqlResult["email_id"];
$this->other_contact_detail = $sqlResult["other_contact_detail"];
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
		$sql =  $this->conn->prepare("DELETE FROM supplier_master  WHERE $this->condition");
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
	if(isset($this->type))
	 { 
		array_push($columnClause,"type" );
		array_push($valueClause,"$this->type");
	 } 
	if(isset($this->name))
	 { 
		array_push($columnClause,"name" );
		array_push($valueClause,"$this->name");
	 } 
	if(isset($this->address))
	 { 
		array_push($columnClause,"address" );
		array_push($valueClause,"$this->address");
	 } 
	if(isset($this->country_id))
	 { 
		array_push($columnClause,"country_id" );
		array_push($valueClause,"$this->country_id");
	 } 
	if(isset($this->state_id))
	 { 
		array_push($columnClause,"state_id" );
		array_push($valueClause,"$this->state_id");
	 } 
	if(isset($this->city_id))
	 { 
		array_push($columnClause,"city_id" );
		array_push($valueClause,"$this->city_id");
	 } 
	if(isset($this->contact_person_name))
	 { 
		array_push($columnClause,"contact_person_name" );
		array_push($valueClause,"$this->contact_person_name");
	 } 
	if(isset($this->mobile_number))
	 { 
		array_push($columnClause,"mobile_number" );
		array_push($valueClause,"$this->mobile_number");
	 } 
	if(isset($this->email_id))
	 { 
		array_push($columnClause,"email_id" );
		array_push($valueClause,"$this->email_id");
	 } 
	if(isset($this->other_contact_detail))
	 { 
		array_push($columnClause,"other_contact_detail" );
		array_push($valueClause,"$this->other_contact_detail");
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
		$sql = $this->conn->prepare("INSERT INTO supplier_master ($columnName ) VALUES ( $tempColumnValues )");
		//echo "<pre>";print_r($sql); print_r($valueClause);
		$result = $sql->execute($valueClause);
		$this->id = $this->conn->lastInsertId();	
		return $result;		
	} // try block ends here
	catch(PDOException $e)
	{
		file_put_contents("PDOErrors.txt", $e->getMessage(), FILE_APPEND);
    	return $this->error = "Query execution error".$e->getMessage();
	} // catch block ends here
} // function ends here
		
		
		

// **********************
// UPDATE
// **********************

function update()
{
$valueClause = array();
$setClause = array();
	if(isset($this->type))
	 { 
		array_push($setClause,"type" );
		array_push($valueClause,"$this->type");
	 } 
	if(isset($this->name))
	 { 
		array_push($setClause,"name" );
		array_push($valueClause,"$this->name");
	 } 
	if(isset($this->address))
	 { 
		array_push($setClause,"address" );
		array_push($valueClause,"$this->address");
	 } 
	if(isset($this->country_id))
	 { 
		array_push($setClause,"country_id" );
		array_push($valueClause,"$this->country_id");
	 } 
	if(isset($this->state_id))
	 { 
		array_push($setClause,"state_id" );
		array_push($valueClause,"$this->state_id");
	 } 
	if(isset($this->city_id))
	 { 
		array_push($setClause,"city_id" );
		array_push($valueClause,"$this->city_id");
	 } 
	if(isset($this->contact_person_name))
	 { 
		array_push($setClause,"contact_person_name" );
		array_push($valueClause,"$this->contact_person_name");
	 } 
	if(isset($this->mobile_number))
	 { 
		array_push($setClause,"mobile_number" );
		array_push($valueClause,"$this->mobile_number");
	 } 
	if(isset($this->email_id))
	 { 
		array_push($setClause,"email_id" );
		array_push($valueClause,"$this->email_id");
	 } 
	if(isset($this->other_contact_detail))
	 { 
		array_push($setClause,"other_contact_detail" );
		array_push($valueClause,"$this->other_contact_detail");
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
		$sql = $this->conn->prepare("UPDATE supplier_master SET  $columnName WHERE $this->condition ");
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
