<?php
/*
    This API is used to add, edit category.
    Created by Khyati
    Function included in this file :
    1) addCategory() - To add category
    2) getOrderOnAndOrderBy() - To get order on & order by for datatable
    3) getSearchCondition() - To get search condition for datatable
    4) categoryListing() - To get category listing (datatable)
    5) getCategoryData() - To get category data for edit from district id
    6) editCategory() - To update category
*/
error_reporting(0);
require_once '../inc/php/config.php';
require_once '../inc/dal/baseclasses/class.database.php';
require_once '../inc/dal/category_master.child.php';
require_once '../inc/php/functions.php';

$requestedData = $_REQUEST;
$action = $_REQUEST['action'];
//echo "Requested data :: <pre>";print_r($requestedData);exit;
//echo "Action :: $action";exit; 
switch($action)
{   
    case "add": 
        $categoryType = trim($requestedData['categoryType']);
        $categoryName = trim($requestedData['txtCategoryName']);
        
        if(isset($categoryType) && !empty($categoryType) && isset($categoryName) && !empty($categoryName))
        {
            $parentCategoryId = 0;
            if($categoryType == 'subcategory')
            {
                $parentCategoryId = $requestedData['categoryId'];
            }
            $response = addCategory($categoryName, $parentCategoryId);
            if($response[0] == 200)
            {
                $_SESSION['successMessage'] = "You have successfully added category";
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
    case "get_category_data":
        $categoryId = $_REQUEST['category_id'];
        if(isset($categoryId) && !empty($categoryId))
        {
            $response = getCategoryData($categoryId);
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
        $categoryId = $requestedData['hdnCategoryId'];
        
        $categoryType = trim($requestedData['categoryType']);
        $categoryName = trim($requestedData['txtCategoryName']);
        if(isset($categoryId) && !empty($categoryId) && isset($categoryType) && !empty($categoryType) && isset($categoryName) && !empty($categoryName))
        {
            $parentCategoryId = 0;
            if($categoryType == 'subcategory')
            {
                $parentCategoryId = $requestedData['categoryId'];
            }
            $response = editCategory($categoryId, $parentCategoryId, $categoryName);
            if($response[0] == 200)
            {
                $_SESSION['successMessage'] = "You have successfully updated category";
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
    case "category_listing":
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
            $defaultCondition = "cm.is_deleted = 0";
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
                $code = 752; // 'No child for the selected category'
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
// Function to add category
function addCategory($categoryName, $parentCategoryId)
{
    $currentDateTime = date('Y-m-d H:i:s');
    
    $objCategoryMasterChild = new categoryMasterChild();
    $objCategoryMasterChild->name = $categoryName;
    $objCategoryMasterChild->parent_category_id = $parentCategoryId;
    $objCategoryMasterChild->modified = $currentDateTime;
    $objCategoryMasterChild->created = $currentDateTime;
    $objCategoryMasterChild->insert();
    $lastInsertedId = $objCategoryMasterChild->id;
    if (($lastInsertedId > 0) && (empty($objCategoryMasterChild->error))) 
    {
        $response = array(200); // Success
    }
    else 
    {
        $response = array(720); // There is something wrong to add data.
    }
    unset($objCategoryMasterChild);
    return $response;
} // addCategory() ends here
/*
    Function to get order on and order by for the datatable
*/
function getOrderOnAndOrderBy($columnIndex, $columnName, $columnSortOrder)
{
    if(isset($columnIndex) && !empty($columnIndex) && isset($columnName) && !empty($columnName) && isset($columnSortOrder) && !empty($columnSortOrder) )
    {   
        if($columnName == 'category_name')
        {
            $orderOn = 'cm.name '.$columnSortOrder;
        }
        else if($columnName == 'parent_category_name')
        {
            $orderOn = 'cm.name '.$columnSortOrder;
        }
        else if($columnName == 'status')
        {
            $orderOn = 'cm.status '.$columnSortOrder;
        }
        else
        {
            $orderBy = 'cm.id '.$columnSortOrder;
        }
    }
    else
    {
        $orderOn = 'cm.id ASC';
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
                    $newCondition = "( (cm.status = ?) OR (cm.status = ?))";
                    array_push($paramArray, 'active', 'inactive');
                }
                else
                {
                    $newCondition = " (cm.status = ?) ";
                    array_push($paramArray, $searchText);
                }
            }
            else if($searchColumnName == 'parent_category_name')
            {
                $newCondition = "((cm.id = ?) || (cm.parent_category_id IN (?)))";
                array_push($paramArray, $searchText, $searchText);
            }
            else if($searchColumnName == 'category_name')
            {
                $newCondition = "(cm.name LIKE ?)";
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
    $objCategoryMaster = new categoryMasterChild();
    $objCategoryMaster->selectColumn = "cm.id";
    $objCategoryMaster->alias = 'category_master as cm';
    $objCategoryMaster->param = $paramArray;
    $objCategoryMaster->condition = $condition;
    $rsCategoryData = $objCategoryMaster->selectByJoin();
    $recordsTotal = $objCategoryMaster->numRows;
    //echo "records total $recordsTotal";exit;
    if($recordsTotal > 0 && empty($objCategoryMaster->error))
    {
        unset($objCategoryMaster);
        $recordsFiltered = $recordsTotal;
        // To get all record from filter
        if ($length == -1)
        {
            $length = $recordsTotal;
        }
        // To get limitwise records
        $objCategoryMasterChild = new categoryMasterChild();
        $objCategoryMasterChild->selectColumn = "cm.id, cm.name, cm.parent_category_id, cm.status, (SELECT name from category_master as cm1 where cm1.id = cm.parent_category_id ) as parent_name";
        $objCategoryMasterChild->alias = 'category_master as cm';
        $objCategoryMasterChild->param = $paramArray;
        $objCategoryMasterChild->condition = "".$condition." ORDER BY $orderOn $orderBy LIMIT ".$start.",".$length;
        $rsCategoryMasterData = $objCategoryMasterChild->selectByJoin();
        $numRows = $objCategoryMasterChild->numRows;
        
        if($numRows > 0 && empty($objCategoryMasterChild->error))
        {
            unset($objCategoryMasterChild);
            $cnt = 0;	
            $count = $start + 1;
            //echo "<pre>";print_r($rsCategoryMasterData);exit;
            foreach($rsCategoryMasterData AS $data)
            {
                $id = $data['id'];
                $categoryName = $data['name'];
                $parentCategoryName = $data['parent_name'];
                if($parentCategoryName == '')
                    $parentCategoryName = '-';
                $status = $data['status'];
                //$key is from config file
                global $key;
                $idMd = md5($key.$id);
                $dataArray[$cnt]['DT_RowId'] = "row_".$id;
                $dataArray[$cnt]['id'] = $id;
                $dataArray[$cnt]['count'] = $count;
                $dataArray[$cnt]['category_name'] = ucfirst($categoryName);
                $dataArray[$cnt]['parent_category_name'] = ucfirst($parentCategoryName);
                $dataArray[$cnt]['status'] = $status;
                $dataArray[$cnt]['idMd'] = $idMd;
                $cnt++;
                $count++;
            }	//foreach ends here
        }
        else
        {
            unset($objCategoryMasterChild);
        }
    }
    else
    {
        unset($objCategoryMaster);
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
function editCategory($editId, $parentCategoryId, $categoryName)
{
    $currentDateTime = date('Y-m-d H:i:s');
    $categoryId = getCategoryId($editId);
    if($categoryId != 0)
    {
        $objCategoryMasterChild = new categoryMasterChild();
        $objCategoryMasterChild->name = $categoryName;
        $objCategoryMasterChild->parent_category_id = $parentCategoryId;
        $objCategoryMasterChild->modified = $currentDateTime;
        $objCategoryMasterChild->condition = "id = $categoryId";
        $objCategoryMasterChild->update();
        if(empty($objCategoryMasterChild->error))
        {
            $response = array(200); // Success
        }
        else 
        {
            $response = array(304); // Record could not be updated
        }
        unset($objCategoryMasterChild);
    }
    else
    {
        $response = array(304); // Record could not be updated
    }
    return $response;
} // editCategory() ends here


/*
    Funtion to get category data from category id
*/
function getCategoryData($id)
{
    $categoryId = getCategoryId($id);
    if($categoryId != 0)
    {
        $paramArray = array($categoryId);
        $objCategoryMasterChild = new categoryMasterChild();
        $objCategoryMasterChild->selectColumn = "id, name, parent_category_id";
        $objCategoryMasterChild->param = $paramArray;
        $objCategoryMasterChild->condition = "id = ?";
        $rsCategoryMaster = $objCategoryMasterChild->selectByColumn();
        $numRowsCategoryMaster = $objCategoryMasterChild->numRows;
        $dataArray = array();
        if($numRowsCategoryMaster > 0 && empty($objCategoryMasterChild->error))
        {
            $dataArray['categoryName'] = $rsCategoryMaster[0]['name'];
            $dataArray['parentCategoryId'] = $rsCategoryMaster[0]['parent_category_id'];
            $response = array(200, $dataArray); // Success
        }
        else
        {
            $response = array(704); // No records present
        }
        unset($objCategoryMasterChild);
    }
    else
    {
        $response = array(704); // No records present
    }
    return $response;
} // getCategoryData() ends here

/*
    Function to get Category id from md5 Category id
*/
function getCategoryId($id)
{
    global $key;
    $objCategory = new categoryMasterChild();
    $objCategory->query = "SELECT id FROM (SELECT cm.id, md5( CONCAT('$key', cm.id) ) AS categoryid FROM category_master AS cm) AS tempCategory WHERE categoryid = '$id'";
    $rsCategoryMaster = $objCategory->customSelectData();
    $numRows = $objCategory->numRows;
    /* echo "num rows :: $numRows";
    echo "Error :: ";print_r($objCategory->error); */
    if($numRows > 0 && empty($objCategory->error))
    {
        $categoryId = $rsCategoryMaster[0]['id'];
    }
    else
    {
        $categoryId = 0;
    }
    return $categoryId;
} // getCategoryId() ends here
/*
    Function to get Child of selected category
*/
function checkChildExistForCategory($statusChangeId)
{
    $paramArray = array($statusChangeId);
    $objCategoryMasterChild = new categoryMasterChild();
    $objCategoryMasterChild->selectColumn = "COUNT(id) as totalChild";
    $objCategoryMasterChild->param = $paramArray;
    $objCategoryMasterChild->condition = "parent_category_id IN (?)";
    $rsCategoryMaster = $objCategoryMasterChild->selectByColumn();
    $numRowsCategoryMaster = $objCategoryMasterChild->numRows;
    if($numRowsCategoryMaster > 0 && empty($objCategoryMasterChild->error))
    {
        $totalChild = $rsCategoryMaster[0]['totalChild'];
        if($totalChild > 0)
        {
            return 0;
        }
        else
        {
            return 1;
        }
    }
}// checkChildExistForCategory() ends here

?>