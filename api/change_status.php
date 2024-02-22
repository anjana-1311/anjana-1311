<?php
require_once '../inc/dal/baseclasses/class.database.php';
require_once '../inc/dal/category_master.child.php';
require_once '../inc/dal/department_master.child.php';
require_once '../inc/dal/supplier_master.child.php';
require_once '../inc/dal/insurance_master.child.php';
require_once '../inc/dal/contract_master.child.php';
require_once '../inc/php/functions.php';
require_once '../inc/php/config.php';

$id = $_REQUEST['id'];
$status = strtolower($_REQUEST['status']);
$tableName = $_REQUEST['table'];
// to execute query
if((isset($id) && ($id > 0)) && (isset($tableName) && ($tableName != "")))
{
    $response = changeStatus($id,$tableName);
    $code = $response[0]; // code
    $status = getStatusCodeMessage($code); // to get the status message
}
else
{
    $code = 701; // mandatory params missing status
    $status = getStatusCodeMessage($code); // to get the status message
}
$response_arr['header']['status'] = $code;
$response_arr['header']['message'] = $status; // Show status message
if($data_flag == 'true') // if this flag is set to true that means it contains the data
    $response_arr['data'] = str_replace("\r\n","",$data); // set Data, If exist
// print json format
$json_data = generateJSON($response_arr); // json encode
echo $json_data; // to throw response
function changeStatus($id,$tableName)
{  
    $currentDateTime = date('Y-m-d H:i:s');
    $result = 1;
	$condition = 'id='.$id;
    switch($tableName)
    {
        case 'category_master':
        {
            $changeStatusObj = new categoryMasterChild();
            $condition = "id IN ($id)";
            break;
        }
        case 'department_master':
        {
            $changeStatusObj = new departmentMasterChild();
            $condition = "id IN ($id)";
            break;
        }
        case 'supplier_master':
        {
            $changeStatusObj = new supplierMasterChild();
            $condition = "id IN ($id)";
            break;
        }
        case 'insurance_master':
        {
            $changeStatusObj = new insuranceMasterChild();
            $condition = "id IN ($id)";
            break;
        }
        case 'contract_master':
        {
            $changeStatusObj = new insuranceMasterChild();
            $condition = "id IN ($id)";
            break;
        }
        default:
            $result = 0;
        	break;
    }
    if($result > 0)
    {
        if($tableName == 'category_master')
        {
            $customerQuery = "UPDATE `category_master` SET status = CASE WHEN status = 'active' THEN 'inactive' ELSE 'active' END WHERE $condition";
            $changeStatusObj->custom_query = $customerQuery;
            $changeStatusObj->customUpdateData(); 
        }
        else if($tableName == 'department_master')
        {
            $customerQuery = "UPDATE `department_master` SET status = CASE WHEN status = 'active' THEN 'inactive' ELSE 'active' END WHERE $condition";
            $changeStatusObj->custom_query = $customerQuery;
            $changeStatusObj->customUpdateData(); 
        }
         else if($tableName == 'supplier_master')
        {
            $customerQuery = "UPDATE `supplier_master` SET status = CASE WHEN status = 'active' THEN 'inactive' ELSE 'active' END WHERE $condition";
            $changeStatusObj->custom_query = $customerQuery;
            $changeStatusObj->customUpdateData(); 
        }
        else if($tableName == 'insurance_master')
        {
            $customerQuery = "UPDATE `insurance_master` SET status = CASE WHEN status = 'active' THEN 'inactive' ELSE 'active' END WHERE $condition";
            $changeStatusObj->custom_query = $customerQuery;
            $changeStatusObj->customUpdateData(); 
        }
        else if($tableName == 'contract_master')
        {
            $customerQuery = "UPDATE `contract_master` SET status = CASE WHEN status = 'active' THEN 'inactive' ELSE 'active' END WHERE $condition";
            $changeStatusObj->custom_query = $customerQuery;
            $changeStatusObj->customUpdateData(); 
        }
        else
        {
            $changeStatusObj->status = $status;
            $changeStatusObj->modified = $currentDateTime;
            $changeStatusObj->condition = $condition;
            $changeStatusObj->update();
        }

        if(empty($changeStatusObj->error))
        {
            $result = array(200); // Success
        }
        else 
        {
            $result = array(752); // Status not updated
        }
        unset($changeStatusObj);
    }
    return $result;
}
?>