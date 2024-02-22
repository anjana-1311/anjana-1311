<?php

/***** start files include ******************/
require_once("../inc/dal/baseclasses/class.database.php");
require_once('../inc/dal/supplier_master.child.php');
error_reporting(0);

if(isset($_REQUEST['startWith']) && !empty($_REQUEST['startWith']))
{
    $condition = "";
	$startWith = $_REQUEST['startWith'];
        $alreadyAddedSupplierId = $_REQUEST['alreadyAddedSupplierId'];
        
	if($startWith == '*')
	{
            $condition .= " id > 0 AND is_deleted=0 AND status='active'";
            
            if($alreadyAddedSupplierId != "")
                $condition .= " AND  NOT FIND_IN_SET( id, '".$alreadyAddedSupplierId."' )";
            
            
            $objSupplierMasterChild = new supplierMasterChild();
            $objSupplierMasterChild->selectColumn = "id, name, type";
            $objSupplierMasterChild->condition =  $condition;
            $rsSupplier = $objSupplierMasterChild->selectByColumn();
            $numRow = $objSupplierMasterChild->numRows;
	}
	else
	{
            $condition .= "is_deleted=0 AND status='active' AND (name LIKE '%".$startWith."%' OR type LIKE '%".$startWith."%')";//original
            
            if($alreadyAddedSupplierId != "")
                $condition .= " AND  NOT FIND_IN_SET( id, '".$alreadyAddedSupplierId."' )";
            //echo "condition >> ".$condition;
            
            $objSupplierMasterChild = new supplierMasterChild();
            $objSupplierMasterChild->selectColumn = "id, name, type";
            $objSupplierMasterChild->condition =  $condition;
            $rsSupplier = $objSupplierMasterChild->selectByColumn();
            $numRow = $objSupplierMasterChild->numRows;
	}
	        
    
    //echo "<pre>";print_r($rsSupplier);
    if($numRow > 0)
    {
        foreach($rsSupplier as $result)
        {
            $results[] = array('id' => $result['id'],'supplier_type' => $result['type'],'supplier_name' => $result['name'],'label' => ucfirst($result['name']).' ('.str_replace('_', ' ', ucfirst($result['type'])).')'); // final result
        }
        $returnData = $results;
    }
    else
    {
        $returnData = array('No records found');
    } // else ends here of checking for mandatory paramter
}
else
{
    $returnData = array('No records found');
}
echo json_encode($returnData);

?>

