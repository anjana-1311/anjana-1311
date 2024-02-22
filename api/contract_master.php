<?php
/*
    This API is used to add, edit contract.
    Created by Khyati
    Function included in this file :
    1) addCategory() - To add contract
    2) getOrderOnAndOrderBy() - To get order on & order by for datatable
    3) getSearchCondition() - To get search condition for datatable
    4) insuranceListing() - To get contract listing (datatable)
    5) getInsuranceData() - To get contract data for edit from district id
    6) editInsurance() - To update contract
*/
error_reporting(0);
ini_set('max_execution_time', '0'); // for infinite time of execution
ini_set('upload_max_size', '128MB');
ini_set('post_max_size', '128MB');
ini_set('memory_limit', '512MB');
require_once '../inc/php/config.php';
require_once '../inc/dal/baseclasses/class.database.php';
require_once '../inc/dal/contract_master.child.php';
require_once '../inc/dal/supplier_master.child.php';
require_once '../inc/php/functions.php';
require_once("../inc/php/image_resize_crop.php");

$requestedData = $_REQUEST;
$action = $_REQUEST['action'];
//echo "Requested data :: <pre>";print_r($requestedData);exit;
//echo "Files Data <pre>";print_r($_FILES);exit;
$filesData = $_FILES;
//echo "Action :: $action";exit; 
switch($action)
{   
    case "add": 
        $title = trim($requestedData['txtTitle']);
        $startDate = $requestedData['txtStartDate'];
        
        if(isset($title) && !empty($title) && isset($startDate) && !empty($startDate))
        {
            $description = $requestedData['txtDescription'];
            $hyperlink = $requestedData['txtHyperlink'];
            $selectedSupplierId = $requestedData['hiddenSelectedSupplier'];
            $contactPersonName = $requestedData['txtContactPersonName'];
            $contactPersonNumber = $requestedData['txtContactNumbers'];
            $otherNumbers = $requestedData['txtOtherNumbers'];
            $txtCost = $requestedData['txtCost'];
            $txtEndDate = $requestedData['txtEndDate'];
            $txtNoEndDate = $requestedData['txtNoEndDate'];
            $txtNoOfLicence = $requestedData['txtNoOfLicence'];
            $txtSoftwareContract = $requestedData['txtSoftwareContract'];
            
            $response = addContract($title, $startDate, $description, $hyperlink, $selectedSupplierId, $contactPersonName,$contactPersonNumber, $otherNumbers, $txtCost, $txtEndDate,$txtNoEndDate, $txtNoOfLicence, $txtSoftwareContract);
            if($response[0] == 200)
            {
                $_SESSION['successMessage'] = "You have successfully added contract";
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
    case "get_contract_data":
        $contractId = $_REQUEST['contract_id'];
        if(isset($contractId) && !empty($contractId))
        {
            $response = getContractData($contractId);
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
    case "get_supplier_info":
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
        $contractId = $requestedData['hdnContractId'];
        
        $title = trim($requestedData['txtTitle']);
        $startDate = $requestedData['txtStartDate'];
        
        if(isset($title) && !empty($title) && isset($startDate) && !empty($startDate))
        {
            $description = $requestedData['txtDescription'];
            $hyperlink = $requestedData['txtHyperlink'];
            $selectedSupplierId = $requestedData['hiddenSelectedSupplier'];
            $contactPersonName = $requestedData['txtContactPersonName'];
            $contactPersonNumber = $requestedData['txtContactNumbers'];
            $otherNumbers = $requestedData['txtOtherNumbers'];
            $txtCost = $requestedData['txtCost'];
            $txtEndDate = $requestedData['txtEndDate'];
            $txtNoEndDate = $requestedData['txtNoEndDate'];
            $txtNoOfLicence = $requestedData['txtNoOfLicence'];
            $txtSoftwareContract = $requestedData['txtSoftwareContract'];  
            
            $response = editContract($contractId, $title, $startDate, $description, $hyperlink, $selectedSupplierId, $contactPersonName,$contactPersonNumber, $otherNumbers, $txtCost, $txtEndDate,$txtNoEndDate, $txtNoOfLicence, $txtSoftwareContract);
            if($response[0] == 200)
            {
                $_SESSION['successMessage'] = "You have successfully updated contract";
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
    case "contract_listing":
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
            $response = contractListing($condition, $orderOn, $orderBy, $start, $length, $requestedData, $paramArray);
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


// Function to add contract
function addContract($title, $startDate, $description, $hyperlink, $selectedSupplierId, $contactPersonName,$contactPersonNumber, $otherNumbers, $txtCost, $txtEndDate,$txtNoEndDate, $txtNoOfLicence, $txtSoftwareContract)
{
    $currentDateTime = date('Y-m-d H:i:s');
    $date = DateTime::createFromFormat("m-d-Y" , $startDate);
    $startDateFinal = $date->format('Y-m-d');
    if(!empty($txtEndDate))
    {
        $dateEnd = DateTime::createFromFormat("m-d-Y" , $txtEndDate);
        $endDateFinal = $dateEnd->format('Y-m-d');
    }
    
    if(empty($txtNoEndDate))
        $txtNoEndDate = 0;
    if(empty($txtSoftwareContract))
        $txtSoftwareContract = 0;
    if(empty($txtNoOfLicence))
        $txtNoOfLicence = 0;
    if($contactPersonName == '-')
        $contactPersonName = '';
    if($contactPersonNumber == '-')
        $contactPersonNumber = '';
    if(empty($selectedSupplierId))
        $selectedSupplierId = 0;
    
    $dbObj = new Database(); // database object
    $dbObj->conn->beginTransaction(); // start transaction
   
    $objContractMasterChild = new contractMasterChild($dbObj);
    $objContractMasterChild->title = $title;
    $objContractMasterChild->description = $description;
    $objContractMasterChild->hyperlink = $hyperlink;
    $objContractMasterChild->supplier_type_id = $selectedSupplierId;
    $objContractMasterChild->contact_person_name = $contactPersonName;
    $objContractMasterChild->contact_number = $contactPersonNumber;
    $objContractMasterChild->other_phone_number = $otherNumbers;
    $objContractMasterChild->cost = $txtCost;
    $objContractMasterChild->start_date = $startDateFinal;
    $objContractMasterChild->end_date = $endDateFinal;
    $objContractMasterChild->no_end_date = $txtNoEndDate;
    $objContractMasterChild->software_contract = $txtSoftwareContract;
    $objContractMasterChild->number_of_licences = $txtNoOfLicence;
    $objContractMasterChild->modified = $currentDateTime;
    $objContractMasterChild->created = $currentDateTime;
    $objContractMasterChild->insert();
    //echo 'Error >> '.$objContractMasterChild->error;exit;
    $lastInsertedId = $objContractMasterChild->id;
    if (($lastInsertedId > 0) && (empty($objContractMasterChild->error))) 
    {
        $dbObj->conn->commit();
        $dbObj->conn = NULL;
        $response = array(200); // Success
        //If Images Present then code for upload 
        /*if($totalImage > 0)
        {
            $isUpload = uploadImagesForInsurance($dbObj, $hdnDeletedImageName, $imagesName, $displayOrder, $displayOrderArray, $totalImage, $lastInsertedId, $filesData);
            if($isUpload > 0)
            {
                $dbObj->conn->commit();
                $dbObj->conn = NULL;
                $response = array(200); // Success
            }
            else
            {
                $dbObj->conn->rollBack();
                $dbObj->conn = NULL;
                $response = array(720); // There is something wrong to add data.
            }
        }
        else
        {
            $dbObj->conn->commit();
            $dbObj->conn = NULL;
            $response = array(200); // Success
        }*/
    }
    else 
    {
        $dbObj->conn->rollBack();
        $dbObj->conn = NULL;
        $response = array(720); // There is something wrong to add data.
    }
    unset($objContractMasterChild);
    return $response;
} // addCategory() ends here

function uploadImagesForInsurance($dbObj, $hdnDeletedImageName, $imagesName, $displayOrder, $displayOrderArray, $totalImage, $lastInsertedId, $filesData)
{
    $displayOrder = 0;
    $tempj = 1;
    
    $path = "../uploads/contract/".$lastInsertedId;
    if (!is_dir($path)) 
    { 
        mkdir($path."/" ,0777, true); // make dir using res_id
        $path = $path ."/";
        //mkdir($path,0777); 
    } 
    else 
    {
        //echo "else 324 ";
        $path = $path . "/";  
    }
    
    $maxSizeInMb = 10000000;// 10 Mb //2097152(2MB);
    $minWidth = 400;
    $minHeight = 400;

    $tempImageNameArray = array();
    $tempImageOrderArray = array();
    echo "call >> ";print_r($filesData['uploadFile']['name']);exit;

    if($hdnDeletedImageName != '')
    {
        $explodeHdnDeleteImgName = explode(',',$hdnDeletedImageName);
        for($m=0; $m < count($explodeHdnDeleteImgName); $m++)
        {
            $deleteImageName = $explodeHdnDeleteImgName[$m];
            unlink("../uploads/contract/".$lastInsertedId."/".$deleteImageName);
            $imageSizeError = $imageExtError = 1;
        }
    }

    if((count($filesData['uploadFile']['name'])) > 0)
    {
        for($i = 0; $i < count($filesData['uploadFile']['name']); $i++)
        {
            //echo "<pre>";print_r($filesData);
            $imageName = $filesData['uploadFile']['name'][$i];
            //echo "<br>in main  loop image > ".$imageName;
            //echo "<br>in main  loop  Hidden image > ";
            //print_r($hdnValidImageArray);
            if (in_array($imageName,$hdnValidImageArray, TRUE))
            {
                //echo "<br> 87 inside if".$imageName;
                $imageTempName = $filesData['uploadFile']['tmp_name'][$i];
                $imageType = $filesData['uploadFile']['type'][$i];
                $imageSize = $filesData["uploadFile"]["size"][$i];

                $explodedImageData = explode('.', $imageName);
                $imageExt = '.' . end($explodedImageData);   // To get the file extention.
                if ($imageSize > $maxSizeInMb)
                {
                    $imageSizeError = 0;
                    continue;
                }

                $allowedImageExtArray = array(".jpeg",".jpg",".png");
                if (in_array(strtolower($imageExt), $allowedImageExtArray))
                {
                    list($imageWidth, $imageHeight) = getimagesize($imageTempName);

                    if($imageHeight < $minHeight && $imageWidth <= $minWidth)
                    {
                        $imageSizeError = 0;
                        continue;
                    }
                }	

                //$allowedFileExtArray = array(".jpeg",".jpg",".png");
                $allowedFileExtArray = array(".jpeg",".jpg",".png",'.pdf','.xlsx','.xls','.docx','.doc','.csv','.txt');
                //echo "<br>extension >> ".$imageExt;
                //echo "<br>allowed >> ";print_r($allowedFileExtArray);
                if (in_array(strtolower($imageExt), $allowedFileExtArray))
                {
                    //$imagePath = $path.'1_'.$timeStamp.$imageExt;
                    $imagePath = $path.$imageName;

                    //echo '</br>110 $imageTempName :::: '.$imageTempName;
                    //echo '</br>$imagePath :::: '.$imagePath;exit;
                    if(move_uploaded_file($imageTempName, $imagePath))
                    {
                        //echo "</br>Display order array <pre>";print_r($displayOrderArray);
                        //echo 'Temp j >> '.$tempj;
                        $arraySearchOutput = array_search("newLi_".$tempj,$displayOrderArray);
                        //echo '</br> Array search output >> '.$arraySearchOutput;
                        $displayOrderArray [$arraySearchOutput] = $arraySearchOutput + 1;
                        if($arraySearchOutput === 0)
                        {
                            $displayOrder = $arraySearchOutput + 1;
                           //echo '</br> inside if 1 '.$displayOrder;
                        }
                        else
                        {
                            if($arraySearchOutput > 0)
                            {
                                $displayOrder = $arraySearchOutput + 1;
                                //echo '</br> Else IF >> '.$displayOrder;
                            }
                            else
                            {
                                $displayOrder = $tempj;
                                //echo '</br> Else ELSE >> '.$displayOrder;
                            }
                        }

                        $imageSizeError = 1;
                        $imageExtError = 1;
                        $tempj++;

                        $index = array_search ($imageName, $hdnValidImageArray);
                        $hdnValidImageArray[$index] = $imageName;
                        array_push($tempImageNameArray, $imageName);
                        array_push($tempImageOrderArray, $displayOrder);
                        //echo "</br>INSDIE order >> ".$displayOrder;
                    } // check image upload flag
                    else
                    {
                        $imageSizeError = 0;
                    }
                } // Edn ext check
                else
                {
                    $imageExtError = 0;
                }
            }
        } // End i loop
    }

    //print_r($displayOrderArray);
    //echo "<pre>AFTER ";print_r($hdnValidImageArray);
    //echo '$imageSizeError ::  '.$imageSizeError;
    //echo '$imageExtError ::  '.$imageExtError;exit;
    if($imageSizeError == 1 && $imageExtError == 1)
    {
        //New array by HS
        $finalSortedArray = sortArrayInOrder($displayOrderArray, $hdnValidImageArray);
        //print_r($finalSortedArray);

        $implodeImageOrder = implode(",",array_keys($finalSortedArray));
        $implodeImageName = implode(",",$finalSortedArray);
        //print_r($implodeImageOrder);
        //print_r($implodeImageName);//exit;


        $finaluserAnswer = $implodeImageOrder.'#'.$implodeImageName;
        if($finaluserAnswer == '#')
            $finaluserAnswer = '';

        $dbObj = new Database(); // database object
        $dbObj->conn->beginTransaction(); // start transaction

        $updateFilesRecord = updateImageDataRecord($dbObj,$finaluserAnswer, $lastInsertedId);

        /*
         Added On: 30 Sep 21
         By: khyati panchal
         */
        //Update code for UPdate record in contract mastrer table for attachement
        if($updateFilesRecord > 0)
        {
            $dbObj->conn->commit();
            $dbObj->conn = NULL;
            //echo "inside if";exit;
            return 1;
        }
        else
        {
            $dbObj->conn->rollBack();
            $dbObj->conn = NULL;
            return 0;
        }
    }
    else
    {
        //echo 'inside else';exit;
        if($totalImage > 0)
        {
            $dbObj->conn->rollBack();
            $dbObj->conn = NULL;
            return 0;
        }
        else
        {
            $dbObj->conn->commit();
            $dbObj->conn = NULL;
            //echo "inside if";exit;
            return 1;
        }
    }
} //upload image function ends here

function sortArrayInOrder($displayOrderArray, $hdnValidImageArray)
{
    //print_r($displayOrderArray);
    //echo "INNER function Display order <pre>>> ";print_r($displayOrderArray);
    //echo "INNER function <pre>>> ";print_r($hdnValidImageArray);
    $tempSortArray = array();
    for($i = 0; $i < count($displayOrderArray); $i++)
    {
        if (array_key_exists($displayOrderArray[$i], $tempSortArray))
            $displayOrderArray[$i] = $displayOrderArray[$i] + 1;
		
        $tempSortArray[$displayOrderArray[$i]] = $hdnValidImageArray[$i];
    }
    //echo "before sort >> ";print_r($tempSortArray);
    ksort($tempSortArray);
    //echo "After SORT <pre>";print_r($tempSortArray);
    return $tempSortArray;
}

function updateImageDataRecord($dbObj,$finaluserAnswer, $lastInsertedId)
{   
    $currentDateTime = date("Y-m-d H:i:s");// Gets the current timestamp.
    $objUpdateInsuranceChild = new contractMasterChild($dbObj);
    $objUpdateInsuranceChild->attachment_files = $finaluserAnswer;
    $objUpdateInsuranceChild->modified = $currentDateTime;
    $objUpdateInsuranceChild->condition = "id=$lastInsertedId";
    $objUpdateInsuranceChild->update();
    if(empty($objUpdateInsuranceChild->error))
    {
        return 1;
    }
    else
    {
        return 0;
    }
}

/*
    Function to get order on and order by for the datatable
*/
function getOrderOnAndOrderBy($columnIndex, $columnName, $columnSortOrder)
{
    if(isset($columnIndex) && !empty($columnIndex) && isset($columnName) && !empty($columnName) && isset($columnSortOrder) && !empty($columnSortOrder) )
    {   
        if($columnName == 'title')
        {
            $orderOn = 'title '.$columnSortOrder;
        }
        else if($columnName == 'supplier_name')
        {
            $orderOn = 'supplier_type_id '.$columnSortOrder;
        }
         else if($columnName == 'contact_person_name')
        {
            $orderOn = 'contact_person_name '.$columnSortOrder;
        }
        else if($columnName == 'contact_number')
        {
            $orderOn = 'contact_number '.$columnSortOrder;
        } else if($columnName == 'cost')
        {
            $orderOn = 'cost '.$columnSortOrder;
        }
        else if($columnName == 'start_date')
        {
            $orderOn = 'start_date '.$columnSortOrder;
        }
        else if($columnName == 'end_date')
        {
            $orderOn = 'end_date '.$columnSortOrder;
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
            //echo "searched text >> ".$searchText."<br>";
            $searchColumnName = $requestedData['columns'][$i]['name'];		
            //echo "column name >> ".$searchColumnName."<br>";exit;
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
            else if($searchColumnName == 'title')
            {
                $newCondition = "(title LIKE ?)";
                array_push($paramArray, $searchTextForLikeCondition);
            }
            else if($searchColumnName == 'supplier_type_id')
            {
                $newCondition = "(supplier_type_id = ?)";
                array_push($paramArray, $searchText);
            }
            else if($searchColumnName == 'contact_person_name')
            {
                $newCondition = "(contact_person_name LIKE ?)";
                array_push($paramArray, $searchTextForLikeCondition);
            }
            else if($searchColumnName == 'start_date')
            {
                $exploadDate = explode(" to ", $searchText);
                $tempStartDateArr = explode("-",$exploadDate[0]);
                $tempEndtDateArr = explode("-",$exploadDate[1]);
                $startDate = date('Y-m-d', mktime(0, 0, 0, $tempStartDateArr[0], $tempStartDateArr[1], $tempStartDateArr[2]));
                $endDate = date('Y-m-d', mktime(0, 0, 0, $tempEndtDateArr[0], $tempEndtDateArr[1], $tempEndtDateArr[2]));

                if($startDate != '1970-01-01' && $endDate != '1970-01-01')
                {
                    $newCondition = "(start_date BETWEEN '".$startDate."' AND '".$endDate."')";	
                    array_push($conditionArray, $newCondition);
                }
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
function contractListing($condition, $orderOn, $orderBy, $start, $length, $requestedData, $paramArray)
{
    //echo "condition :: $condition";
    //echo '<pre>';print_r($paramArray);//exit;
    $recordsFiltered = 0;
    $recordsTotal = 0;
    $dataArray = array();
    $objContractMaster = new contractMasterChild();
    $objContractMaster->selectColumn = "id";
    $objContractMaster->param = $paramArray;
    $objContractMaster->condition = $condition;
    $rsCategoryData = $objContractMaster->selectByColumn();
    $recordsTotal = $objContractMaster->numRows;
    //echo "records total $recordsTotal";exit;
    if($recordsTotal > 0 && empty($objContractMaster->error))
    {
        unset($objContractMaster);
        $recordsFiltered = $recordsTotal;
        // To get all record from filter
        if ($length == -1)
        {
            $length = $recordsTotal;
        }
        // To get limitwise records
        $objContactMasterChild = new contractMasterChild();
        $objContactMasterChild->selectColumn = "id, title, supplier_type_id, contact_person_name, contact_number, cost, start_date, end_date, status, (SELECT name from supplier_master where id=supplier_type_id) AS supplier_name";
        $objContactMasterChild->param = $paramArray;
        $objContactMasterChild->condition = "".$condition." ORDER BY $orderOn $orderBy LIMIT ".$start.",".$length;
        $rsContractMasterData = $objContactMasterChild->selectByColumn();
        $numRows = $objContactMasterChild->numRows;
        
        if($numRows > 0 && empty($objContactMasterChild->error))
        {
            unset($objContactMasterChild);
            $cnt = 0;	
            $count = $start + 1;
            //echo "<pre>";print_r($rsContractMasterData);exit;
            foreach($rsContractMasterData AS $data)
            {
                $id = $data['id'];
                $title = ucfirst($data['title']);
                $supplierName = ucfirst($data['supplier_name']);
                $contactPerson = ucfirst($data['contact_person_name']);
                $contactNumber = ucfirst($data['contact_number']);
                $cost = $data['cost'];
                $startDate = ucfirst($data['start_date']);
                $endDate = ucfirst($data['end_date']);
                
                
                if(empty($contactPerson))
                    $contactPerson = '-';
                if(empty($contactNumber))
                    $contactNumber = '-';
                if(empty($cost))
                    $cost = '-';
                
                $startDate = $data['start_date'];
                $newStartDate = date("m/d/Y", strtotime($startDate));
                $endDate = $data['end_date'];
                if(!empty($endDate))
                {
                    $newEndDate = date("m/d/Y", strtotime($endDate));
                }
                else
                {
                    $newEndDate = '-';
                }
                $status = $data['status'];
                //$key is from config file
                global $key;
                $idMd = md5($key.$id);
                $dataArray[$cnt]['DT_RowId'] = "row_".$id;
                $dataArray[$cnt]['id'] = $id;
                $dataArray[$cnt]['count'] = $count;
                $dataArray[$cnt]['title'] = $title;
                $dataArray[$cnt]['supplier_name'] = $supplierName;
                $dataArray[$cnt]['contact_person_name'] = $contactPerson;
                $dataArray[$cnt]['contact_number'] = $contactNumber;
                $dataArray[$cnt]['cost'] = $cost;
                $dataArray[$cnt]['start_date'] = $newStartDate;
                $dataArray[$cnt]['end_date'] = $newEndDate;
                $dataArray[$cnt]['status'] = $status;
                $dataArray[$cnt]['idMd'] = $idMd;
                $cnt++;
                $count++;
            }	//foreach ends here
        }
        else
        {
            unset($objContactMasterChild);
        }
    }
    else
    {
        unset($objContractMaster);
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
    Function to edit Contract
*/
function editContract($editId, $title, $startDate, $description, $hyperlink, $selectedSupplierId, $contactPersonName,$contactPersonNumber, $otherNumbers, $txtCost, $txtEndDate,$txtNoEndDate, $txtNoOfLicence, $txtSoftwareContract)
{
    $currentDateTime = date('Y-m-d H:i:s');
    $contractId = getContractId($editId);

    if($contractId != 0)
    {
        $date = DateTime::createFromFormat("m-d-Y" , $startDate);
        $startDateFinal = $date->format('Y-m-d');
        if(!empty($txtEndDate))
        {
            $dateEnd = DateTime::createFromFormat("m-d-Y" , $txtEndDate);
            $endDateFinal = $dateEnd->format('Y-m-d');
        }

        if(empty($txtNoEndDate))
            $txtNoEndDate = 0;
        if(empty($txtSoftwareContract))
            $txtSoftwareContract = 0;
        if(empty($txtNoOfLicence))
            $txtNoOfLicence = 0;
        if($contactPersonName == '-')
            $contactPersonName = '';
        if($contactPersonNumber == '-')
            $contactPersonNumber = '';
        if(empty($selectedSupplierId))
            $selectedSupplierId = 0;
        
        $objContractMasterChild = new contractMasterChild();
        $objContractMasterChild->title = $title;
        $objContractMasterChild->description = $description;
        $objContractMasterChild->hyperlink = $hyperlink;
        $objContractMasterChild->supplier_type_id = $selectedSupplierId;
        $objContractMasterChild->contact_person_name = $contactPersonName;
        $objContractMasterChild->contact_number = $contactPersonNumber;
        $objContractMasterChild->other_phone_number = $otherNumbers;
        $objContractMasterChild->cost = $txtCost;
        $objContractMasterChild->start_date = $startDateFinal;
        $objContractMasterChild->end_date = $endDateFinal;
        $objContractMasterChild->no_end_date = $txtNoEndDate;
        $objContractMasterChild->software_contract = $txtSoftwareContract;
        $objContractMasterChild->number_of_licences = $txtNoOfLicence;
        $objContractMasterChild->modified = $currentDateTime;
        $objContractMasterChild->condition = "id = $contractId";
        $objContractMasterChild->update();
        
        if(empty($objContractMasterChild->error))
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
} // editInsurance() ends here


/*
    Funtion to get contract data from contract id
*/
function getContractData($id)
{
    $contractId = getContractId($id);
    if($contractId != 0)
    {
        $paramArray = array($contractId);
        $objContractMasterChild = new contractMasterChild();
        $objContractMasterChild->selectColumn = "id, title, description, hyperlink, supplier_type_id, contact_person_name, contact_number, other_phone_number, cost, start_date, end_date, no_end_date, software_contract, contract_attachment, status, number_of_licences, (SELECT GROUP_CONCAT( name,'(#)',type ) FROM supplier_master WHERE id = supplier_type_id) AS supplier_list";
        $objContractMasterChild->param = $paramArray;
        $objContractMasterChild->condition = "id = ?";
        $rsInsuranceMaster = $objContractMasterChild->selectByColumn();
        $numRowsCategoryMaster = $objContractMasterChild->numRows;
        $dataArray = array();
        if($numRowsCategoryMaster > 0 && empty($objContractMasterChild->error))
        {
            $dataArray['title'] = $rsInsuranceMaster[0]['title'];
            $dataArray['description'] = $rsInsuranceMaster[0]['description'];
            $dataArray['hyperlink'] = $rsInsuranceMaster[0]['hyperlink'];
            
            $dataArray['supplier_id'] = $rsInsuranceMaster[0]['supplier_type_id'];
            $supplierList = $rsInsuranceMaster[0]['supplier_list'];
            if($supplierList != '')
            {
                $explodeSupplierList = explode('(#)', $supplierList);
                $supName = $explodeSupplierList[0];
                $supType = $explodeSupplierList[1];
                $supplierNameWithType = $supName.' ('.$supType.') ';
                $dataArray['supplier_name'] = str_replace('_', ' ', ucfirst($supplierNameWithType));
            }
            
            $dataArray['contact_person_name'] = $rsInsuranceMaster[0]['contact_person_name'];
            $dataArray['contact_number'] = $rsInsuranceMaster[0]['contact_number'];
            $dataArray['other_phone_number'] = $rsInsuranceMaster[0]['other_phone_number'];
            $dataArray['cost'] = $rsInsuranceMaster[0]['cost'];
            $dataArray['no_end_date'] = $rsInsuranceMaster[0]['no_end_date'];
            $dataArray['software_contract'] = $rsInsuranceMaster[0]['software_contract'];
            $dataArray['number_of_licences'] = $rsInsuranceMaster[0]['number_of_licences'];
            $startDate = $rsInsuranceMaster[0]['start_date'];
            $date = DateTime::createFromFormat("Y-m-d" , $startDate);
            $startDateFinal = $date->format('m-d-Y');
            $dataArray['start_date'] = $startDateFinal;
            
            $endDate = $rsInsuranceMaster[0]['end_date'];
            if(!empty($endDate))
            {
                $dateEnd = DateTime::createFromFormat("Y-m-d" , $endDate);
                $endDateFinal = $dateEnd->format('m-d-Y');
                $dataArray['end_date'] = $endDateFinal;
            }
            else
            {
                $dataArray['end_date'] = $rsInsuranceMaster[0]['end_date'];
            }
            $response = array(200, $dataArray); // Success
        }
        else
        {
            $response = array(704); // No records present
        }
        unset($objContractMasterChild);
    }
    else
    {
        $response = array(704); // No records present
    }
    return $response;
} // getCategoryData() ends here

function getSupplierData($supplierId)
{
    if($supplierId != 0)
    {
        $paramArray = array($supplierId);
        $objSupplierMasterChild = new supplierMasterChild();
        $objSupplierMasterChild->selectColumn = "contact_person_name, mobile_number";
        $objSupplierMasterChild->param = $paramArray;
        $objSupplierMasterChild->condition = "id = ?";
        $rsSupplierMaster = $objSupplierMasterChild->selectByColumn();
        $numRowsSupplierMaster = $objSupplierMasterChild->numRows;
        $dataArray = array();
        if($numRowsSupplierMaster > 0 && empty($objSupplierMasterChild->error))
        {
            if(empty($rsSupplierMaster[0]['contact_person_name']))
                $rsSupplierMaster[0]['contact_person_name'] = '-';
            if(empty($rsSupplierMaster[0]['mobile_number']))
                $rsSupplierMaster[0]['mobile_number'] = '-';
            $dataArray['contact_person_name'] = $rsSupplierMaster[0]['contact_person_name'];
            $dataArray['mobile_number'] = $rsSupplierMaster[0]['mobile_number'];
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
} // getSupplierData() ends here

/*
    Function to get contract id from md5 contract id
*/
function getContractId($id)
{
    global $key;
    $objInsurance = new contractMasterChild();
    $objInsurance->query = "SELECT id FROM (SELECT im.id, md5( CONCAT('$key', im.id) ) AS insuranceid FROM insurance_master AS im) AS tempInsurance WHERE insuranceid = '$id'";
    $rsInsuranceMaster = $objInsurance->customSelectData();
    $numRows = $objInsurance->numRows;
    /* echo "num rows :: $numRows";
    echo "Error :: ";print_r($objInsurance->error); */
    if($numRows > 0 && empty($objInsurance->error))
    {
        $insuranceId = $rsInsuranceMaster[0]['id'];
    }
    else
    {
        $insuranceId = 0;
    }
    return $insuranceId;
} // getInsuranceId() ends here
?>