<?php
/*
    This API is used to add, edit Department.
    Created by Khyati
    Function included in this file :
    1) addDepartment() - To add Department
    2) getOrderOnAndOrderBy() - To get order on & order by for datatable
    3) getSearchCondition() - To get search condition for datatable
    4) departmentListing() - To get Department listing (datatable)
    5) editDepartment() - To update Department
*/
error_reporting(0);
require_once '../inc/php/config.php';
require_once '../inc/dal/baseclasses/class.database.php';
require_once '../inc/dal/department_master.child.php';
require_once '../inc/php/functions.php';

$requestedData = $_REQUEST;
$action = $_REQUEST['action'];
//echo "Requested data :: <pre>";print_r($requestedData);exit;
//echo "Action :: $action";exit; 
switch($action)
{   
    case "add": 
        $departmentName = trim($requestedData['txtDepartmentName']);
        
        if(isset($departmentName) && !empty($departmentName))
        {
            $response = addDepartment($departmentName);
            if($response[0] == 200)
            {
                $_SESSION['successMessage'] = "You have successfully added department";
                $code = $response[0]; // Success code
                $status = getStatusCodeMessage($code); // to get the status message
            }
            else
            {
                $code = $response[0]; // Error code
                $status = getStatusCodeMessage($code); // to get the status message
            }
        } // if ends here of checking for mandatory paramter
        else
        {
            $code = 701; // mandatory params missing status
            $status = getStatusCodeMessage($code); // to get the status message
        } // else ends here of checking for mandatory paramter
    break; 
    case "get_department_data":
        $departmentId = $_REQUEST['department_id'];
        if(isset($departmentId) && !empty($departmentId))
        {
            $response = getDepartmentIdData($departmentId);
            if($response[0] == 200)
            {
                $data_flag = true;
                $data  = array($response[1]);
                $code = 200; // OK status code
                $status = getStatusCodeMessage($code); // to get the status message 
            }
            else 
            {
                $code = $response[0]; // Error code
                $status = getStatusCodeMessage($code); // to get the status message
            }
        } // if ends here of checking for mandatory paramter
        else
        {
            $code = 701; // mandatory params missing status
            $status = getStatusCodeMessage($code); // to get the status message
        } // else ends here of checking for mandatory paramter
    break; 
    case "edit": 
        $departmentId = $requestedData['hdnDepartmentId'];
        
        $departmentName = trim($requestedData['txtDepartmentName']);
        if(isset($departmentId) && !empty($departmentId) && isset($departmentName) && !empty($departmentName))
        {
            $response = editDepartment($departmentId, $departmentName);
            if($response[0] == 200)
            {
                $_SESSION['successMessage'] = "You have successfully updated department";
                $code = $response[0]; // Success code
                $status = getStatusCodeMessage($code); // to get the status message
            }
            else
            {
                $code = $response[0]; // Error code
                $status = getStatusCodeMessage($code); // to get the status message
            }
        } // if ends here of checking for mandatory paramter
        else
        {
            $code = 701; // mandatory params missing status
            $status = getStatusCodeMessage($code); // to get the status message
        } // else ends here of checking for mandatory paramter
    break; 
    case "department_listing":
        if(isset($_REQUEST['start']) && isset($_REQUEST['length']))
        {
            $start = $_REQUEST['start'];
            $length = $_REQUEST['length'];
            $columnIndex = $_REQUEST['order'][0]['column']; // Column index
            $columnName = $_REQUEST['columns'][$columnIndex]['data']; // Column name
            $columnSortOrder = $_REQUEST['order'][0]['dir']; // asc or desc
            $orderOnBy = getOrderOnAndOrderBy($columnIndex, $columnName, $columnSortOrder);
            $splitOrderOnBy = explode('#', $orderOnBy);
            $orderOn = $splitOrderOnBy[0];
            $orderBy = $splitOrderOnBy[1];
            $conditionArray = array();
            $defaultCondition = "is_deleted = 0";
            array_push($conditionArray,$defaultCondition);
            $conditionAndParamArray = getSearchCondition($conditionArray, $requestedData);
            $conditionArray = $conditionAndParamArray['condition'];
            $paramArray = $conditionAndParamArray['paramArray'];
            $condition = implode(" AND ",$conditionArray);
            $response = departmentListing($condition, $orderOn, $orderBy, $start, $length, $requestedData, $paramArray);
            echo json_encode($response);
            exit;
        }
        else
        {
            $code = 701; // mandatory params missing status
            $status = getStatusCodeMessage($code); // to get the status message
        } // else ends here of checking for mandatory paramter
    break; 
    default:
        $code = 708; // unknown request
        $status = getStatusCodeMessage($code); // to get the status message
    break;       
      
} // switch case ends here 
$response_arr['header']['status'] = $code;
$response_arr['header']['message'] = $status; // Show status message
if($data_flag == 'true') // if this flag is set to true that means it contains the data
    $response_arr['data'] = str_replace("\r\n","",$data); // set Data, If exist
// print response in json format
$json_data = generateJSON($response_arr); // json encode
echo $json_data; // to throw response
// Function to add Department
function addDepartment($departmentName)
{
    $currentDateTime = date('Y-m-d H:i:s');
    
    $objDepartmentMasterChild = new departmentMasterChild();
    $objDepartmentMasterChild->name = $departmentName;
    $objDepartmentMasterChild->modified = $currentDateTime;
    $objDepartmentMasterChild->created = $currentDateTime;
    $objDepartmentMasterChild->insert();
    $lastInsertedId = $objDepartmentMasterChild->id;
    if (($lastInsertedId > 0) && (empty($objDepartmentMasterChild->error))) 
    {
        $response = array(200); // Success
    }
    else 
    {
        $response = array(720); // There is something wrong to add data.
    }
    unset($objDepartmentMasterChild);
    return $response;
} // addCategory() ends here
/*
    Function to get order on and order by for the datatable
*/
function getOrderOnAndOrderBy($columnIndex, $columnName, $columnSortOrder)
{
    if(isset($columnIndex) && !empty($columnIndex) && isset($columnName) && !empty($columnName) && isset($columnSortOrder) && !empty($columnSortOrder) )
    {   
        if($columnName == 'department_name')
        {
            $orderOn = 'name '.$columnSortOrder;
        }
        else if($columnName == 'status')
        {
            $orderOn = 'status '.$columnSortOrder;
        }
        else
        {
            $orderBy = 'id '.$columnSortOrder;
        }
    }
    else
    {
        $orderOn = 'id ASC';
    }
    return $orderOn.'#'.$orderBy;
} // getOrderOnAndOrderBy() ends here
/*
    Function to get search condition for Department listing ( datatable )
*/
function getSearchCondition($conditionArray, $requestedData)
{
    $paramArray = array();
    for($i=0;$i < count($requestedData['columns']);$i++)
	{
        //echo "<pre>";print_r($requestedData['columns']);
        if(isset($requestedData['columns'][$i]['search']['value']) && !empty($requestedData['columns'][$i]['search']['value']))
        {
            $searchText = $requestedData['columns'][$i]['search']['value'];
            $searchTextForLikeCondition = "%".$searchText."%";
            // echo "searched text >> ".$searchText."<br>";
            $searchColumnName = $requestedData['columns'][$i]['name'];		
            // echo "column name >> ".$searchColumnName."<br>";exit;
            if($searchColumnName == 'status')
            {
                if($searchText == 'all')
                {
                    $newCondition = "( (status = ?) OR (status = ?))";
                    array_push($paramArray, 'active', 'inactive');
                }
                else
                {
                    $newCondition = " (status = ?) ";
                    array_push($paramArray, $searchText);
                }
            }
            else if($searchColumnName == 'department_name')
            {
                $newCondition = "(name LIKE ?)";
                array_push($paramArray, $searchTextForLikeCondition);
            }
            array_push($conditionArray,$newCondition);
        }
	}	// for loop here
    // echo "<pre>";print_r($conditionArray);exit;
    $data = array(
        "condition" => $conditionArray,
        "paramArray" => $paramArray
    );
    return $data;
} // getSearchCondition() ends here
/*
    Function to get Department listing
*/
function departmentListing($condition, $orderOn, $orderBy, $start, $length, $requestedData, $paramArray)
{
    //echo "condition :: $condition";
    //echo '<pre>';print_r($paramArray);//exit;
    $recordsFiltered = 0;
    $recordsTotal = 0;
    $dataArray = array();
    $objDepartmentMaster = new departmentMasterChild();
    $objDepartmentMaster->selectColumn = "id";
    $objDepartmentMaster->param = $paramArray;
    $objDepartmentMaster->condition = $condition;
    $rsDepartmentData = $objDepartmentMaster->selectByColumn();
    $recordsTotal = $objDepartmentMaster->numRows;
    //echo "records total $recordsTotal";exit;
    if($recordsTotal > 0 && empty($objDepartmentMaster->error))
    {
        unset($objDepartmentMaster);
        $recordsFiltered = $recordsTotal;
        // To get all record from filter
        if ($length == -1)
        {
            $length = $recordsTotal;
        }
        // To get limitwise records
        $objDepartmentMasterChild = new departmentMasterChild();
        $objDepartmentMasterChild->selectColumn = "id, name, status";
        $objDepartmentMasterChild->param = $paramArray;
        $objDepartmentMasterChild->condition = "".$condition." ORDER BY $orderOn $orderBy LIMIT ".$start.",".$length;
        $rsDepartmentMasterData = $objDepartmentMasterChild->selectByColumn();
        $numRows = $objDepartmentMasterChild->numRows;
        
        if($numRows > 0 && empty($objDepartmentMasterChild->error))
        {
            unset($objDepartmentMasterChild);
            $cnt = 0;	
            $count = $start + 1;
            //echo "<pre>";print_r($rsDepartmentMasterData);exit;
            foreach($rsDepartmentMasterData AS $data)
            {
                $id = $data['id'];
                $departmentName = $data['name'];
                $status = $data['status'];
                //$key is from config file
                global $key;
                $idMd = md5($key.$id);
                $dataArray[$cnt]['DT_RowId'] = "row_".$id;
                $dataArray[$cnt]['id'] = $id;
                $dataArray[$cnt]['count'] = $count;
                $dataArray[$cnt]['department_name'] = ucfirst($departmentName);
                $dataArray[$cnt]['status'] = $status;
                $dataArray[$cnt]['idMd'] = $idMd;
                $cnt++;
                $count++;
            }	//foreach ends here
        }
        else
        {
            unset($objDepartmentMasterChild);
        }
    }
    else
    {
        unset($objDepartmentMaster);
    }
    // Refference : https://datatables.net/manual/server-side
    $responseArray = array();
    /* draw - strongly recommended for security reasons that you cast this parameter to an integer, rather than simply echoing back to the client what it sent in the draw parameter, in order to prevent Cross Site Scripting (XSS) attacks.*/
    $responseArray['draw'] = $_REQUEST['draw'];
    /*Total records, before filtering (i.e. the total number of records in the database)*/
    $responseArray['recordsTotal'] = $recordsTotal;
    /*Total records, after filtering (i.e. the total number of records after filtering has been applied - not just the number of records being returned for this page of data).*/
    $responseArray['recordsFiltered'] = $recordsFiltered;
    $responseArray['data'] = str_replace("\r\n","",$dataArray); // Set data, If exist
    return $responseArray;
} // districtListing() ends here

/*
    Function to edit Department
*/
function editDepartment($editId, $departmentName)
{
    $currentDateTime = date('Y-m-d H:i:s');
    if($editId != 0)
    {
        $objDepartmentMasterChild = new departmentMasterChild();
        $objDepartmentMasterChild->name = $departmentName;
        $objDepartmentMasterChild->modified = $currentDateTime;
        $objDepartmentMasterChild->condition = "id = $editId";
        $objDepartmentMasterChild->update();
        //echo "error >> ".$objDepartmentMasterChild->error;exit;
        if(empty($objDepartmentMasterChild->error))
        {
            $response = array(200); // Success
        }
        else 
        {
            $response = array(304); // Record could not be updated
        }
        unset($objDepartmentMasterChild);
    }
    else
    {
        $response = array(304); // Record could not be updated
    }
    return $response;
} // editCategory() ends here



?>