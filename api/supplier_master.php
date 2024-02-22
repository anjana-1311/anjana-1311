<?php
/*
    This API is used to add, edit supplier.
    Created by Khyati
    Function included in this file :
    1) addSupplier() - To add supplier
    2) getOrderOnAndOrderBy() - To get order on & order by for datatable
    3) getSearchCondition() - To get search condition for datatable
    4) supplierListing() - To get supplier listing (datatable)
    5) getSupplieryData() - To get supplier data for edit from district id
    6) editSupplier() - To update supplier
*/
error_reporting(0);
require_once '../inc/php/config.php';
require_once '../inc/dal/baseclasses/class.database.php';
require_once '../inc/dal/supplier_master.child.php';
require_once '../inc/php/functions.php';

$requestedData = $_REQUEST;
$action = $_REQUEST['action'];
//echo "Requested data :: <pre>";print_r($requestedData);exit;
//echo "Action :: $action";exit; 
switch($action)
{   
    case "add": 
        $supplierType = trim($requestedData['txtTypeId']);
        $supplierName = trim($requestedData['txtName']);
        
        if(isset($supplierType) && !empty($supplierType) && isset($supplierName) && !empty($supplierName))
        {
            $txtAddress = trim($requestedData['txtAddress']);
            $txtCountry = $requestedData['txtCountry'];
            $txtState = $requestedData['txtState'];
            $txtCity = $requestedData['txtCity'];
            $txtContactPersonName = trim($requestedData['txtContactPersonName']);
            $txtMobileNumber = trim($requestedData['txtMobileNumber']);
            $txtEmailId = trim($requestedData['txtEmailId']);
            $txtContactNumbers = $requestedData['txtContactNumbers'];
            
            $response = addSupplier($supplierType, $supplierName, $txtAddress, $txtCountry, $txtState, $txtCity, $txtContactPersonName, $txtMobileNumber, $txtEmailId, $txtContactNumbers);
            if($response[0] == 200)
            {
                $_SESSION['successMessage'] = "You have successfully added supplier";
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
    case "get_supplier_data":
        $supplierId = $_REQUEST['supplier_id'];
        if(isset($supplierId) && !empty($supplierId))
        {
            $response = getSupplierData($supplierId);
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
        $supplierId = $requestedData['hdnSupplierId'];
        
        $supplierType = trim($requestedData['txtTypeId']);
        $supplierName = trim($requestedData['txtName']);
        if(isset($supplierId) && !empty($supplierId) && isset($supplierType) && !empty($supplierType) && isset($supplierName) && !empty($supplierName))
        {
            $txtAddress = trim($requestedData['txtAddress']);
            $txtCountry = $requestedData['txtCountry'];
            $txtState = $requestedData['txtState'];
            $txtCity = $requestedData['txtCity'];
            $txtContactPersonName = trim($requestedData['txtContactPersonName']);
            $txtMobileNumber = trim($requestedData['txtMobileNumber']);
            $txtEmailId = trim($requestedData['txtEmailId']);
            $txtContactNumbers = $requestedData['txtContactNumbers'];
            
            $response = editSupplier($supplierId, $supplierType, $supplierName, $txtAddress,$txtCountry, $txtState, $txtCity, $txtContactPersonName, $txtMobileNumber, $txtEmailId, $txtContactNumbers);
            if($response[0] == 200)
            {
                $_SESSION['successMessage'] = "You have successfully updated supplier";
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
    case "supplier_listing":
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
            $response = categoryListing($condition, $orderOn, $orderBy, $start, $length, $requestedData, $paramArray);
            echo json_encode($response);
            exit;
        }
        else
        {
            $code = 701; // mandatory params missing status
            $status = getStatusCodeMessage($code); // to get the status message
        } // else ends here of checking for mandatory paramter
    break; 
    case 'check_category_child_present':
        if((isset($_REQUEST['id']) && isset($_REQUEST['id'])) && (isset($_REQUEST['action']) && isset($_REQUEST['action'])))
        {
            $statusChangeId = $_REQUEST['id'];
            $isChild = checkChildExistForCategory($statusChangeId);
            if($isChild == 0) //child present
            {
                $code = 200;
                $status = getStatusCodeMessage($code); // to get the status message
            }
            else
            {
                $code = 752; // 'No child for the selected supplier'
                $status = getStatusCodeMessage($code); // to get the status message
            }
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


// Function to add supplier
function addSupplier($supplierType, $supplierName, $txtAddress, $txtCountry, $txtState, $txtCity, $txtContactPersonName, $txtMobileNumber, $txtEmailId, $txtContactNumbers)
{
    $currentDateTime = date('Y-m-d H:i:s');
    
    if(empty($txtCountry))
        $txtCountry = 0;
    if(empty($txtState))
        $txtState = 0;
    if(empty($txtCity))
        $txtCity = 0;
    
    $objSupplierMasterChild = new supplierMasterChild();
    $objSupplierMasterChild->type = $supplierType;
    $objSupplierMasterChild->name = $supplierName;
    $objSupplierMasterChild->address = $txtAddress;
    $objSupplierMasterChild->country_id = $txtCountry;
    $objSupplierMasterChild->state_id = $txtState;
    $objSupplierMasterChild->city_id = $txtCity;
    $objSupplierMasterChild->contact_person_name = $txtContactPersonName;
    $objSupplierMasterChild->mobile_number = $txtMobileNumber;
    $objSupplierMasterChild->email_id = $txtEmailId;
    $objSupplierMasterChild->other_contact_detail = $txtContactNumbers;
    $objSupplierMasterChild->modified = $currentDateTime;
    $objSupplierMasterChild->created = $currentDateTime;
    $objSupplierMasterChild->insert();
    $lastInsertedId = $objSupplierMasterChild->id;
    //echo "error >> ".$objSupplierMasterChild->error;exit;
    if (($lastInsertedId > 0) && (empty($objSupplierMasterChild->error))) 
    {
        $response = array(200); // Success
    }
    else 
    {
        $response = array(720); // There is something wrong to add data.
    }
    unset($objSupplierMasterChild);
    return $response;
} // addCategory() ends here
/*
    Function to get order on and order by for the datatable
*/
function getOrderOnAndOrderBy($columnIndex, $columnName, $columnSortOrder)
{
    if(isset($columnIndex) && !empty($columnIndex) && isset($columnName) && !empty($columnName) && isset($columnSortOrder) && !empty($columnSortOrder) )
    {   
        if($columnName == 'supplier_type')
        {
            $orderOn = 'type '.$columnSortOrder;
        }
        else if($columnName == 'supplier_name')
        {
            $orderOn = 'name '.$columnSortOrder;
        }
        else if($columnName == 'contact_person_name')
        {
            $orderOn = 'contact_person_name '.$columnSortOrder;
        }
        else if($columnName == 'mobile_number')
        {
            $orderOn = 'mobile_number '.$columnSortOrder;
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
    Function to get search condition for district listing ( datatable )
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
            else if($searchColumnName == 'supplier_name')
            {
                $newCondition = "(name LIKE ?)";
                array_push($paramArray, $searchTextForLikeCondition);
            }
            else if($searchColumnName == 'supplier_type')
            {
                $newCondition = "(type LIKE ?)";
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
    Function to get district listing
*/
function categoryListing($condition, $orderOn, $orderBy, $start, $length, $requestedData, $paramArray)
{
    //echo "condition :: $condition";
    //echo '<pre>';print_r($paramArray);//exit;
    $recordsFiltered = 0;
    $recordsTotal = 0;
    $dataArray = array();
    $objSupplierMaster = new supplierMasterChild();
    $objSupplierMaster->selectColumn = "id";
    $objSupplierMaster->param = $paramArray;
    $objSupplierMaster->condition = $condition;
    $rsSupplierData = $objSupplierMaster->selectByColumn();
    $recordsTotal = $objSupplierMaster->numRows;
    //echo "records total $recordsTotal";exit;
    if($recordsTotal > 0 && empty($objSupplierMaster->error))
    {
        unset($objSupplierMaster);
        $recordsFiltered = $recordsTotal;
        // To get all record from filter
        if ($length == -1)
        {
            $length = $recordsTotal;
        }
        // To get limitwise records
        $objSupplierMasterChild = new supplierMasterChild();
        $objSupplierMasterChild->selectColumn = "id, name, type, contact_person_name, mobile_number, status";
        $objSupplierMasterChild->param = $paramArray;
        $objSupplierMasterChild->condition = "".$condition." ORDER BY $orderOn $orderBy LIMIT ".$start.",".$length;
        $rsSupplierMasterData = $objSupplierMasterChild->selectByColumn();
        $numRows = $objSupplierMasterChild->numRows;
        
        if($numRows > 0 && empty($objSupplierMasterChild->error))
        {
            unset($objSupplierMasterChild);
            $cnt = 0;	
            $count = $start + 1;
            //echo "<pre>";print_r($rsSupplierMasterData);exit;
            foreach($rsSupplierMasterData AS $data)
            {
                $id = $data['id'];
                $supplierName = $data['name'];
                $supplierType = $data['type'];
                $supplierType = str_replace('_', ' ', $supplierType);
                $contactPersonName = $data['contact_person_name'];
                if(empty($contactPersonName))
                    $contactPersonName = '-';
                $mobileNumber = $data['mobile_number'];
                if(empty($mobileNumber))
                    $mobileNumber = '-';
                
                $status = $data['status'];
                //$key is from config file
                global $key;
                $idMd = md5($key.$id);
                $dataArray[$cnt]['DT_RowId'] = "row_".$id;
                $dataArray[$cnt]['id'] = $id;
                $dataArray[$cnt]['count'] = $count;
                $dataArray[$cnt]['supplier_type'] = ucfirst($supplierType);
                $dataArray[$cnt]['supplier_name'] = ucfirst($supplierName);
                $dataArray[$cnt]['contact_person_name'] = ucfirst($contactPersonName);
                $dataArray[$cnt]['mobile_number'] = $mobileNumber;
                $dataArray[$cnt]['status'] = $status;
                $dataArray[$cnt]['idMd'] = $idMd;
                $cnt++;
                $count++;
            }	//foreach ends here
        }
        else
        {
            unset($objSupplierMasterChild);
        }
    }
    else
    {
        unset($objSupplierMaster);
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
    Function to edit district
*/
function editSupplier($editId, $supplierType, $supplierName, $txtAddress,$txtCountry, $txtState, $txtCity, $txtContactPersonName, $txtMobileNumber, $txtEmailId, $txtContactNumbers)
{
    if(empty($txtCountry))
        $txtCountry = 0;
    if(empty($txtState))
        $txtState = 0;
    if(empty($txtCity))
        $txtCity = 0;
    
    $currentDateTime = date('Y-m-d H:i:s');
    $supplierId = getSupplierId($editId);
    if($supplierId != 0)
    {
        $objSupplierMasterChild = new supplierMasterChild();
        $objSupplierMasterChild->type = $supplierType;
        $objSupplierMasterChild->name = $supplierName;
        $objSupplierMasterChild->address = $txtAddress;
        $objSupplierMasterChild->country_id = $txtCountry;
        $objSupplierMasterChild->state_id = $txtState;
        $objSupplierMasterChild->city_id = $txtCity;
        $objSupplierMasterChild->contact_person_name = $txtContactPersonName;
        $objSupplierMasterChild->mobile_number = $txtMobileNumber;
        $objSupplierMasterChild->email_id = $txtEmailId;
        $objSupplierMasterChild->other_contact_detail = $txtContactNumbers;
        $objSupplierMasterChild->modified = $currentDateTime;
        $objSupplierMasterChild->created = $currentDateTime;
        $objSupplierMasterChild->condition = "id = $supplierId";
        $objSupplierMasterChild->update();
        //echo "error >> ".$objSupplierMasterChild->error;exit;
        if(empty($objSupplierMasterChild->error))
        {
            $response = array(200); // Success
        }
        else 
        {
            $response = array(304); // Record could not be updated
        }
        unset($objSupplierMasterChild);
    }
    else
    {
        $response = array(304); // Record could not be updated
    }
    return $response;
} // editCategory() ends here


/*
    Funtion to get supplier data from supplier id
*/
function getSupplierData($id)
{
    $supplierId = getSupplierId($id);
    if($supplierId != 0)
    {
        $paramArray = array($supplierId);
        $objSupplierMasterChild = new supplierMasterChild();
        $objSupplierMasterChild->selectColumn = "id, type, name, address, contact_person_name, mobile_number, email_id, other_contact_detail, country_id, state_id, city_id";
        $objSupplierMasterChild->alias = 'supplier_master as sm';
        $objSupplierMasterChild->param = $paramArray;
        $objSupplierMasterChild->condition = "id = ?";
        $rsSupplierMaster = $objSupplierMasterChild->selectByJoin();
        $numRowsCategoryMaster = $objSupplierMasterChild->numRows;
        $dataArray = array();
        if($numRowsCategoryMaster > 0 && empty($objSupplierMasterChild->error))
        {
            $dataArray['categoryType'] = $rsSupplierMaster[0]['type'];
            $dataArray['categoryName'] = $rsSupplierMaster[0]['name'];
            $dataArray['address'] = $rsSupplierMaster[0]['address'];
            $dataArray['contactPersonName'] = $rsSupplierMaster[0]['contact_person_name'];
            $dataArray['mobileNumber'] = $rsSupplierMaster[0]['mobile_number'];
            $dataArray['emailId'] = $rsSupplierMaster[0]['email_id'];
            $dataArray['otherContactNumbers'] = $rsSupplierMaster[0]['other_contact_detail'];
            $dataArray['countryId'] = $rsSupplierMaster[0]['country_id'];
            $dataArray['stateId'] = $rsSupplierMaster[0]['state_id'];
            $dataArray['cityId'] = $rsSupplierMaster[0]['city_id'];
           
            $response = array(200, $dataArray); // Success
        }
        else
        {
            $response = array(704); // No records present
        }
        unset($objSupplierMasterChild);
    }
    else
    {
        $response = array(704); // No records present
    }
    return $response;
} // getCategoryData() ends here

/*
    Function to get supplier id from md5 supplier id
*/
function getSupplierId($id)
{
    global $key;
    $objSupplier = new supplierMasterChild();
    $objSupplier->query = "SELECT id FROM (SELECT sm.id, md5( CONCAT('$key', sm.id) ) AS supplierid FROM supplier_master AS sm) AS tempSupplier WHERE supplierid = '$id'";
    $rsSupplierMaster = $objSupplier->customSelectData();
    $numRows = $objSupplier->numRows;
    /* echo "num rows :: $numRows";
    echo "Error :: ";print_r($objSupplier->error); */
    if($numRows > 0 && empty($objSupplier->error))
    {
        $supplierId = $rsSupplierMaster[0]['id'];
    }
    else
    {
        $supplierId = 0;
    }
    return $supplierId;
} // getSupplierId() ends here
?>