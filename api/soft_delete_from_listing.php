<?php
require_once '../inc/dal/baseclasses/class.database.php';
require_once '../inc/dal/category_master.child.php';
require_once '../inc/dal/department_master.child.php';
require_once '../inc/dal/supplier_master.child.php';
require_once '../inc/dal/insurance_master.child.php';
require_once '../inc/dal/contract_master.child.php';

$tableName = $_REQUEST['table_name'];
$id = $_REQUEST['id'];
//echo "id >> ".$id;
    $isReturn = softDelete($id,$tableName);

echo $isReturn;
exit;

function softDelete($id,$tableName)
{  
    $currentDateTime = date('Y-m-d H:i:s');
    $result = 1;
    $condition = 'id='.$id;
    switch($tableName)
    {
        case 'category_master':
        {
           $deleteObj = new categoryMasterChild();
           $condition = "id = $id";
           break;
        } 
        case 'department_master':
        {
           $deleteObj = new departmentMasterChild();
           $condition = "id = $id";
           break;
        } 
        case 'supplier_master':
        {
           $deleteObj = new supplierMasterChild();
           $condition = "id = $id";
           break;
        } 
        case 'insurance_master':
        {
           $deleteObj = new insuranceMasterChild();
           $condition = "id = $id";
           break;
        } 
        case 'contract_master':
        {
           $deleteObj = new contractMasterChild();
           $condition = "id = $id";
           break;
        } 
        default:
            $result = 0;
        break;
    }
    if($result > 0)
    {
        $deleteObj->is_deleted = 1;
        $deleteObj->modified = $currentDateTime;
        $deleteObj->condition = $condition;
        $deleteObj->update();
        if(empty($deleteObj->error))
        {
            $result = 1;
        }
        else
        {
            $result = 0;
        }
    }
    return $result;
}
?>