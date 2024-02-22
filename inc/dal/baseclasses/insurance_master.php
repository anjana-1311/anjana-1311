<?php
// **********************
// CLASS DECLARATION
// **********************

class insurance_master extends Database
{ 	// class : begin

// **********************
// ATTRIBUTE DECLARATION
// **********************

var $id;   // KEY ATTR. WITH AUTOINCREMENT

		var $name;   // (normal Attribute)
		var $description;   // (normal Attribute)
		var $insurance_company;   // (normal Attribute)
		var $policy_number;   // (normal Attribute)
		var $contact_person;   // (normal Attribute)
		var $phone_number;   // (normal Attribute)
		var $email_id;   // (normal Attribute)
		var $coverage_information;   // (normal Attribute)
		var $limits;   // (normal Attribute)
		var $premium_amount;   // (normal Attribute)
		var $deductible_amount;   // (normal Attribute)
		var $start_date;   // (normal Attribute)
		var $end_date;   // (normal Attribute)
		var $require_renewal;   // (normal Attribute)
		var $renewal_duration;   // (normal Attribute)
		var $attachment_files;   // (normal Attribute)
		var $status;   // (normal Attribute)
		var $is_deleted;   // (normal Attribute)
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
$query = "SELECT * FROM insurance_master WHERE ";
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
$this->description = $sqlResult["description"];
$this->insurance_company = $sqlResult["insurance_company"];
$this->policy_number = $sqlResult["policy_number"];
$this->contact_person = $sqlResult["contact_person"];
$this->phone_number = $sqlResult["phone_number"];
$this->email_id = $sqlResult["email_id"];
$this->coverage_information = $sqlResult["coverage_information"];
$this->limits = $sqlResult["limits"];
$this->premium_amount = $sqlResult["premium_amount"];
$this->deductible_amount = $sqlResult["deductible_amount"];
$this->start_date = $sqlResult["start_date"];
$this->end_date = $sqlResult["end_date"];
$this->require_renewal = $sqlResult["require_renewal"];
$this->renewal_duration = $sqlResult["renewal_duration"];
$this->attachment_files = $sqlResult["attachment_files"];
$this->status = $sqlResult["status"];
$this->is_deleted = $sqlResult["is_deleted"];
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
	$sql = $this->conn->prepare("SELECT * FROM insurance_master");
	//echo "<pre>";print_r($sql);
	$sql->setFetchMode(PDO::FETCH_ASSOC);
	$sql->execute();
	$sqlResult = $sql->fetchAll();
	

$this->id = $sqlResult["id"];
$this->name = $sqlResult["name"];
$this->description = $sqlResult["description"];
$this->insurance_company = $sqlResult["insurance_company"];
$this->policy_number = $sqlResult["policy_number"];
$this->contact_person = $sqlResult["contact_person"];
$this->phone_number = $sqlResult["phone_number"];
$this->email_id = $sqlResult["email_id"];
$this->coverage_information = $sqlResult["coverage_information"];
$this->limits = $sqlResult["limits"];
$this->premium_amount = $sqlResult["premium_amount"];
$this->deductible_amount = $sqlResult["deductible_amount"];
$this->start_date = $sqlResult["start_date"];
$this->end_date = $sqlResult["end_date"];
$this->require_renewal = $sqlResult["require_renewal"];
$this->renewal_duration = $sqlResult["renewal_duration"];
$this->attachment_files = $sqlResult["attachment_files"];
$this->status = $sqlResult["status"];
$this->is_deleted = $sqlResult["is_deleted"];
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
	
$query = "SELECT * FROM insurance_master WHERE ";
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
$this->description = $sqlResult["description"];
$this->insurance_company = $sqlResult["insurance_company"];
$this->policy_number = $sqlResult["policy_number"];
$this->contact_person = $sqlResult["contact_person"];
$this->phone_number = $sqlResult["phone_number"];
$this->email_id = $sqlResult["email_id"];
$this->coverage_information = $sqlResult["coverage_information"];
$this->limits = $sqlResult["limits"];
$this->premium_amount = $sqlResult["premium_amount"];
$this->deductible_amount = $sqlResult["deductible_amount"];
$this->start_date = $sqlResult["start_date"];
$this->end_date = $sqlResult["end_date"];
$this->require_renewal = $sqlResult["require_renewal"];
$this->renewal_duration = $sqlResult["renewal_duration"];
$this->attachment_files = $sqlResult["attachment_files"];
$this->status = $sqlResult["status"];
$this->is_deleted = $sqlResult["is_deleted"];
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
		$sql =  $this->conn->prepare("DELETE FROM insurance_master  WHERE $this->condition");
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
	if(isset($this->description))
	 { 
		array_push($columnClause,"description" );
		array_push($valueClause,"$this->description");
	 } 
	if(isset($this->insurance_company))
	 { 
		array_push($columnClause,"insurance_company" );
		array_push($valueClause,"$this->insurance_company");
	 } 
	if(isset($this->policy_number))
	 { 
		array_push($columnClause,"policy_number" );
		array_push($valueClause,"$this->policy_number");
	 } 
	if(isset($this->contact_person))
	 { 
		array_push($columnClause,"contact_person" );
		array_push($valueClause,"$this->contact_person");
	 } 
	if(isset($this->phone_number))
	 { 
		array_push($columnClause,"phone_number" );
		array_push($valueClause,"$this->phone_number");
	 } 
	if(isset($this->email_id))
	 { 
		array_push($columnClause,"email_id" );
		array_push($valueClause,"$this->email_id");
	 } 
	if(isset($this->coverage_information))
	 { 
		array_push($columnClause,"coverage_information" );
		array_push($valueClause,"$this->coverage_information");
	 } 
	if(isset($this->limits))
	 { 
		array_push($columnClause,"limits" );
		array_push($valueClause,"$this->limits");
	 } 
	if(isset($this->premium_amount))
	 { 
		array_push($columnClause,"premium_amount" );
		array_push($valueClause,"$this->premium_amount");
	 } 
	if(isset($this->deductible_amount))
	 { 
		array_push($columnClause,"deductible_amount" );
		array_push($valueClause,"$this->deductible_amount");
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
	if(isset($this->require_renewal))
	 { 
		array_push($columnClause,"require_renewal" );
		array_push($valueClause,"$this->require_renewal");
	 } 
	if(isset($this->renewal_duration))
	 { 
		array_push($columnClause,"renewal_duration" );
		array_push($valueClause,"$this->renewal_duration");
	 } 
	if(isset($this->attachment_files))
	 { 
		array_push($columnClause,"attachment_files" );
		array_push($valueClause,"$this->attachment_files");
	 } 
	if(isset($this->status))
	 { 
		array_push($columnClause,"status" );
		array_push($valueClause,"$this->status");
	 } 
	if(isset($this->is_deleted))
	 { 
		array_push($columnClause,"is_deleted" );
		array_push($valueClause,"$this->is_deleted");
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
		$sql = $this->conn->prepare("INSERT INTO insurance_master ($columnName ) VALUES ( $tempColumnValues )");
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
	if(isset($this->name))
	 { 
		array_push($setClause,"name" );
		array_push($valueClause,"$this->name");
	 } 
	if(isset($this->description))
	 { 
		array_push($setClause,"description" );
		array_push($valueClause,"$this->description");
	 } 
	if(isset($this->insurance_company))
	 { 
		array_push($setClause,"insurance_company" );
		array_push($valueClause,"$this->insurance_company");
	 } 
	if(isset($this->policy_number))
	 { 
		array_push($setClause,"policy_number" );
		array_push($valueClause,"$this->policy_number");
	 } 
	if(isset($this->contact_person))
	 { 
		array_push($setClause,"contact_person" );
		array_push($valueClause,"$this->contact_person");
	 } 
	if(isset($this->phone_number))
	 { 
		array_push($setClause,"phone_number" );
		array_push($valueClause,"$this->phone_number");
	 } 
	if(isset($this->email_id))
	 { 
		array_push($setClause,"email_id" );
		array_push($valueClause,"$this->email_id");
	 } 
	if(isset($this->coverage_information))
	 { 
		array_push($setClause,"coverage_information" );
		array_push($valueClause,"$this->coverage_information");
	 } 
	if(isset($this->limits))
	 { 
		array_push($setClause,"limits" );
		array_push($valueClause,"$this->limits");
	 } 
	if(isset($this->premium_amount))
	 { 
		array_push($setClause,"premium_amount" );
		array_push($valueClause,"$this->premium_amount");
	 } 
	if(isset($this->deductible_amount))
	 { 
		array_push($setClause,"deductible_amount" );
		array_push($valueClause,"$this->deductible_amount");
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
	if(isset($this->require_renewal))
	 { 
		array_push($setClause,"require_renewal" );
		array_push($valueClause,"$this->require_renewal");
	 } 
	if(isset($this->renewal_duration))
	 { 
		array_push($setClause,"renewal_duration" );
		array_push($valueClause,"$this->renewal_duration");
	 } 
	if(isset($this->attachment_files))
	 { 
		array_push($setClause,"attachment_files" );
		array_push($valueClause,"$this->attachment_files");
	 } 
	if(isset($this->status))
	 { 
		array_push($setClause,"status" );
		array_push($valueClause,"$this->status");
	 } 
	if(isset($this->is_deleted))
	 { 
		array_push($setClause,"is_deleted" );
		array_push($valueClause,"$this->is_deleted");
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
		$sql = $this->conn->prepare("UPDATE insurance_master SET  $columnName WHERE $this->condition ");
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
