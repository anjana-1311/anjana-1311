<?php
// **********************
// CLASS DECLARATION
// **********************

require_once('baseclasses/supplier_master.php');

class supplierMasterChild extends supplier_master
{ 	// class : begin


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
}

//**************//
//SELECT BY JOIN//
//**************//

function selectByJoin()
{

$query = "SELECT $this->selectColumn FROM $this->alias WHERE ";
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
		$this->numRows = $sql->rowCount();
		//print_r($result);
		
		return $result;
	} // try block ends here
	catch(PDOException $e)
	{
		file_put_contents("PDOErrors.txt", $e->getMessage(), FILE_APPEND);
    	return $this->error = "Query execution error";
	} // catch block ends here
} // function ends here

//****************//
//SELECT BY COLUMN//
//****************//

function selectByColumn()
{
		
$query = "SELECT $this->selectColumn FROM supplier_master WHERE ";
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
		$this->numRows = $sql->rowCount();
		//print_r($result);
		
		return $result;
	} // try block ends here
	catch(PDOException $e)
	{
		file_put_contents("PDOErrors.txt", $e->getMessage(), FILE_APPEND);
    	return $this->error = "Query execution error";
	    
	} // catch block ends here
} // function ends here

//**************//
//  BULK INSERT //
//**************//

function bulkInsert($data)
{
$this->id = "" ;
	try
	{
		//Will contain SQL snippets.
		$rowsSQL = array();

		//Will contain the values that we need to bind.
		$toBind = array();

		//Get a list of column names to use in the SQL statement.
		$columnNames = array_keys($data[0]);	
			
		//Loop through our $data array.
		foreach($data as $arrayIndex => $row)
		{
			$params = array();
			foreach($row as $columnName => $columnValue)
			{
				$param = ":" . $columnName . $arrayIndex;
				$params[] = $param;
				$toBind[$param] = $columnValue;
			} // foreach loop ends here
			$rowsSQL[] = "(" . implode(", ", $params) . ")";
		}	//foreach loop ends here
			
		//Construct our SQL statement

		$sql = $this->conn->prepare("INSERT INTO supplier_master (" . implode(", ", $columnNames) . ") VALUES " . implode(", ", $rowsSQL));
		
	    //Bind the values.
		foreach($toBind as $param => $val)
		{
			$sql->bindValue($param, $val);
		}
		
		$result = $sql->execute();
		$this->id = $this->conn->lastInsertId();
		return $result;
	} // try block ends here
	catch(PDOException $e)
	{
		file_put_contents("PDOErrors.txt", $e->getMessage(), FILE_APPEND);
    	return $this->error = "Query execution error";
	} // catch block ends here
} // function ends here
function customSelectData()
{
    $this->id = ""; // clear key for autoincrement
    try
    {
        $sql = $this->conn->prepare("$this->query");
        //echo "<pre>";print_r($sql);
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        $sql->execute();
        $result = $sql->fetchAll();
        $this->numRows = $sql->rowCount();
        return $result;
    } // try block ends here
    catch(PDOException $e)
    {
        return $this->error = "Query execution error";
        file_put_contents("PDOErrors.txt", $e->getMessage(), FILE_APPEND);

    } // catch block ends here
}
function customUpdateData()
{
    $this->id = ""; // clear key for autoincrement
    try
    {
        $sql = $this->conn->prepare("$this->custom_query");
        $sql->execute();
        $this->numRows = $sql->rowCount();
        return $result;
    } // try block ends here
    catch(PDOException $e)
    {
        return $this->error = "Query execution error";
        file_put_contents("PDOErrors.txt", $e->getMessage(), FILE_APPEND);

    } // catch block ends here
} //function ends here

} // class : end
?>
