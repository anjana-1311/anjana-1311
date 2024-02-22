<?php
// **********************
// CLASS DECLARATION
// **********************

class contract_master extends Database
{ 	// class : begin

// **********************
// ATTRIBUTE DECLARATION
// **********************

var $id;   // KEY ATTR. WITH AUTOINCREMENT

		var $title;   // (normal Attribute)
		var $description;   // (normal Attribute)
		var $hyperlink;   // (normal Attribute)
		var $supplier_type_id;   // (normal Attribute)
		var $contact_person_name;   // (normal Attribute)
		var $contact_number;   // (normal Attribute)
		var $other_phone_number;   // (normal Attribute)
		var $cost;   // (normal Attribute)
		var $start_date;   // (normal Attribute)
		var $end_date;   // (normal Attribute)
		var $no_end_date;   // (normal Attribute)
		var $software_contract;   // (normal Attribute)
		var $contract_attachment;   // (normal Attribute)
		var $is_deleted;   // (normal Attribute)
		var $status;   // (normal Attribute)
		var $number_of_licences;   // (normal Attribute)
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
$query = "SELECT * FROM contract_master WHERE ";
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
$this->title = $sqlResult["title"];
$this->description = $sqlResult["description"];
$this->hyperlink = $sqlResult["hyperlink"];
$this->supplier_type_id = $sqlResult["supplier_type_id"];
$this->contact_person_name = $sqlResult["contact_person_name"];
$this->contact_number = $sqlResult["contact_number"];
$this->other_phone_number = $sqlResult["other_phone_number"];
$this->cost = $sqlResult["cost"];
$this->start_date = $sqlResult["start_date"];
$this->end_date = $sqlResult["end_date"];
$this->no_end_date = $sqlResult["no_end_date"];
$this->software_contract = $sqlResult["software_contract"];
$this->contract_attachment = $sqlResult["contract_attachment"];
$this->is_deleted = $sqlResult["is_deleted"];
$this->status = $sqlResult["status"];
$this->number_of_licences = $sqlResult["number_of_licences"];
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
	$sql = $this->conn->prepare("SELECT * FROM contract_master");
	//echo "<pre>";print_r($sql);
	$sql->setFetchMode(PDO::FETCH_ASSOC);
	$sql->execute();
	$sqlResult = $sql->fetchAll();
	

$this->id = $sqlResult["id"];
$this->title = $sqlResult["title"];
$this->description = $sqlResult["description"];
$this->hyperlink = $sqlResult["hyperlink"];
$this->supplier_type_id = $sqlResult["supplier_type_id"];
$this->contact_person_name = $sqlResult["contact_person_name"];
$this->contact_number = $sqlResult["contact_number"];
$this->other_phone_number = $sqlResult["other_phone_number"];
$this->cost = $sqlResult["cost"];
$this->start_date = $sqlResult["start_date"];
$this->end_date = $sqlResult["end_date"];
$this->no_end_date = $sqlResult["no_end_date"];
$this->software_contract = $sqlResult["software_contract"];
$this->contract_attachment = $sqlResult["contract_attachment"];
$this->is_deleted = $sqlResult["is_deleted"];
$this->status = $sqlResult["status"];
$this->number_of_licences = $sqlResult["number_of_licences"];
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
	
$query = "SELECT * FROM contract_master WHERE ";
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
$this->title = $sqlResult["title"];
$this->description = $sqlResult["description"];
$this->hyperlink = $sqlResult["hyperlink"];
$this->supplier_type_id = $sqlResult["supplier_type_id"];
$this->contact_person_name = $sqlResult["contact_person_name"];
$this->contact_number = $sqlResult["contact_number"];
$this->other_phone_number = $sqlResult["other_phone_number"];
$this->cost = $sqlResult["cost"];
$this->start_date = $sqlResult["start_date"];
$this->end_date = $sqlResult["end_date"];
$this->no_end_date = $sqlResult["no_end_date"];
$this->software_contract = $sqlResult["software_contract"];
$this->contract_attachment = $sqlResult["contract_attachment"];
$this->is_deleted = $sqlResult["is_deleted"];
$this->status = $sqlResult["status"];
$this->number_of_licences = $sqlResult["number_of_licences"];
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
		$sql =  $this->conn->prepare("DELETE FROM contract_master  WHERE $this->condition");
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
	if(isset($this->title))
	 { 
		array_push($columnClause,"title" );
		array_push($valueClause,"$this->title");
	 } 
	if(isset($this->description))
	 { 
		array_push($columnClause,"description" );
		array_push($valueClause,"$this->description");
	 } 
	if(isset($this->hyperlink))
	 { 
		array_push($columnClause,"hyperlink" );
		array_push($valueClause,"$this->hyperlink");
	 } 
	if(isset($this->supplier_type_id))
	 { 
		array_push($columnClause,"supplier_type_id" );
		array_push($valueClause,"$this->supplier_type_id");
	 } 
	if(isset($this->contact_person_name))
	 { 
		array_push($columnClause,"contact_person_name" );
		array_push($valueClause,"$this->contact_person_name");
	 } 
	if(isset($this->contact_number))
	 { 
		array_push($columnClause,"contact_number" );
		array_push($valueClause,"$this->contact_number");
	 } 
	if(isset($this->other_phone_number))
	 { 
		array_push($columnClause,"other_phone_number" );
		array_push($valueClause,"$this->other_phone_number");
	 } 
	if(isset($this->cost))
	 { 
		array_push($columnClause,"cost" );
		array_push($valueClause,"$this->cost");
	 } 
	if(isset($this->start_date))
	 { 
		array_push($columnClause,"start_date" );
		array_push($valueClause,"$this->start_date");
	 } 
	if(isset($this->end_date))
	 { 
		array_push($columnClause,"end_date" );
		array_push($valueClause,"$this->end_date");
	 } 
	if(isset($this->no_end_date))
	 { 
		array_push($columnClause,"no_end_date" );
		array_push($valueClause,"$this->no_end_date");
	 } 
	if(isset($this->software_contract))
	 { 
		array_push($columnClause,"software_contract" );
		array_push($valueClause,"$this->software_contract");
	 } 
	if(isset($this->contract_attachment))
	 { 
		array_push($columnClause,"contract_attachment" );
		array_push($valueClause,"$this->contract_attachment");
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
	if(isset($this->number_of_licences))
	 { 
		array_push($columnClause,"number_of_licences" );
		array_push($valueClause,"$this->number_of_licences");
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
		$sql = $this->conn->prepare("INSERT INTO contract_master ($columnName ) VALUES ( $tempColumnValues )");
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
	if(isset($this->title))
	 { 
		array_push($setClause,"title" );
		array_push($valueClause,"$this->title");
	 } 
	if(isset($this->description))
	 { 
		array_push($setClause,"description" );
		array_push($valueClause,"$this->description");
	 } 
	if(isset($this->hyperlink))
	 { 
		array_push($setClause,"hyperlink" );
		array_push($valueClause,"$this->hyperlink");
	 } 
	if(isset($this->supplier_type_id))
	 { 
		array_push($setClause,"supplier_type_id" );
		array_push($valueClause,"$this->supplier_type_id");
	 } 
	if(isset($this->contact_person_name))
	 { 
		array_push($setClause,"contact_person_name" );
		array_push($valueClause,"$this->contact_person_name");
	 } 
	if(isset($this->contact_number))
	 { 
		array_push($setClause,"contact_number" );
		array_push($valueClause,"$this->contact_number");
	 } 
	if(isset($this->other_phone_number))
	 { 
		array_push($setClause,"other_phone_number" );
		array_push($valueClause,"$this->other_phone_number");
	 } 
	if(isset($this->cost))
	 { 
		array_push($setClause,"cost" );
		array_push($valueClause,"$this->cost");
	 } 
	if(isset($this->start_date))
	 { 
		array_push($setClause,"start_date" );
		array_push($valueClause,"$this->start_date");
	 } 
	if(isset($this->end_date))
	 { 
		array_push($setClause,"end_date" );
		array_push($valueClause,"$this->end_date");
	 } 
	if(isset($this->no_end_date))
	 { 
		array_push($setClause,"no_end_date" );
		array_push($valueClause,"$this->no_end_date");
	 } 
	if(isset($this->software_contract))
	 { 
		array_push($setClause,"software_contract" );
		array_push($valueClause,"$this->software_contract");
	 } 
	if(isset($this->contract_attachment))
	 { 
		array_push($setClause,"contract_attachment" );
		array_push($valueClause,"$this->contract_attachment");
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
	if(isset($this->number_of_licences))
	 { 
		array_push($setClause,"number_of_licences" );
		array_push($valueClause,"$this->number_of_licences");
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
		$sql = $this->conn->prepare("UPDATE contract_master SET  $columnName WHERE $this->condition ");
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
