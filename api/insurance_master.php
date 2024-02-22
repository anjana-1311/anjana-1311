<?php
/*
    This API is used to add, edit insurance.
    Created by Khyati
    Function included in this file :
    1) addCategory() - To add insurance
    2) getOrderOnAndOrderBy() - To get order on & order by for datatable
    3) getSearchCondition() - To get search condition for datatable
    4) insuranceListing() - To get insurance listing (datatable)
    5) getInsuranceData() - To get insurance data for edit from district id
    6) editInsurance() - To update insurance
*/
error_reporting(0);
ini_set('max_execution_time', '0'); // for infinite time of execution
ini_set('upload_max_size', '128MB');
ini_set('post_max_size', '128MB');
ini_set('memory_limit', '512MB');
require_once '../inc/php/config.php';
require_once '../inc/dal/baseclasses/class.database.php';
require_once '../inc/dal/insurance_master.child.php';
require_once '../inc/php/functions.php';
require_once("../inc/php/image_resize_crop.php");

$requestedData = $_REQUEST;
$action = $_REQUEST['action'];
//echo "Requested data :: <pre>";print_r($requestedData);//exit;
//echo "Files Data <pre>";print_r($_FILES);exit;
$filesData = $_FILES;
//echo "Action :: $action";exit; 
switch($action)
{   
    case "add": 
        $insuranceName = trim($requestedData['txtName']);
        $insuranceCompanyName = trim($requestedData['txtInsuranceCompanyName']);
        $startDate = $requestedData['txtStartDate'];
        
        if(isset($insuranceName) && !empty($insuranceName) && isset($insuranceCompanyName) && !empty($insuranceCompanyName) && isset($startDate) && !empty($startDate))
        {
            $description = $requestedData['txtDescription'];
            $policyNumber = $requestedData['txtPolicyNumber'];
            $contactPersonName = $requestedData['txtContactPersonName'];
            $phoneNumber = $requestedData['txtPhoneNumber'];
            $emailId = $requestedData['txtEmailId'];
            $coverageInfo = $requestedData['txtCoverageInfo'];
            $limit = $requestedData['txtLimit'];
            $premiumAmount = $requestedData['txtPremiumAmnt'];
            $deductibleAmount = $requestedData['txtDeductibleAmnt'];
            $endDate = $requestedData['txtEndDate'];
            $requireRenewal = $requestedData['requireRenewel'];
            $renewalDuration = $requestedData['txtRenewalDuration'];            
            
            $hdnDeletedImageName = $requestedData['hdnDeletedImageName'];
            $hdnValidImageArray = explode(",",$requestedData['hdnValidImage']);
            //echo "<pre>";print_r($hdnValidImageArray);
            $imagesName = $requestedData['hdnValidImage'];
            $displayOrder = $requestedData['hdnDisplayOrder'];
            $displayOrderArray = explode(",",$displayOrder);
            //echo "<pre>"; print_r($displayOrderArray);
            $totalImage = $requestedData['hdnTotalImage'];
            
            $response = addInsurance($insuranceName, $insuranceCompanyName, $startDate, $description, $policyNumber, $contactPersonName,$phoneNumber, $emailId, $coverageInfo,$limit, $premiumAmount, $deductibleAmount,$endDate, $requireRenewal, $renewalDuration, $hdnDeletedImageName, $imagesName, $displayOrder, $displayOrderArray, $totalImage, $filesData);
            if($response[0] == 200)
            {
                $_SESSION['successMessage'] = "You have successfully added insurance";
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
    case "get_insurance_data":
        $insuranceId = $_REQUEST['insurance_id'];
        if(isset($insuranceId) && !empty($insuranceId))
        {
            $response = getInsuranceData($insuranceId);
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
        $insuranceId = $requestedData['hdnInsuranceId'];
        
        $insuranceName = trim($requestedData['txtName']);
        $insuranceCompanyName = trim($requestedData['txtInsuranceCompanyName']);
        $startDate = $requestedData['txtStartDate'];
        if(isset($insuranceId) && !empty($insuranceId) && isset($insuranceName) && !empty($insuranceName) && isset($insuranceCompanyName) && !empty($insuranceCompanyName) && isset($startDate) && !empty($startDate))
        {  
            $description = $requestedData['txtDescription'];
            $policyNumber = $requestedData['txtPolicyNumber'];
            $contactPersonName = $requestedData['txtContactPersonName'];
            $phoneNumber = $requestedData['txtPhoneNumber'];
            $emailId = $requestedData['txtEmailId'];
            $coverageInfo = $requestedData['txtCoverageInfo'];
            $limit = $requestedData['txtLimit'];
            $premiumAmount = $requestedData['txtPremiumAmnt'];
            $deductibleAmount = $requestedData['txtDeductibleAmnt'];
            $endDate = $requestedData['txtEndDate'];
            $requireRenewal = $requestedData['requireRenewel'];
            $renewalDuration = $requestedData['txtRenewalDuration'];   
            
            $response = editInsurance($insuranceId,$insuranceName, $insuranceCompanyName, $startDate, $description, $policyNumber, $contactPersonName,$phoneNumber, $emailId, $coverageInfo,$limit, $premiumAmount, $deductibleAmount,$endDate, $requireRenewal, $renewalDuration);
            if($response[0] == 200)
            {
                $_SESSION['successMessage'] = "You have successfully updated insurance";
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
    case "insurance_listing":
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
            $response = insuranceListing($condition, $orderOn, $orderBy, $start, $length, $requestedData, $paramArray);
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


// Function to add insurance
function addInsurance($insuranceName, $insuranceCompanyName, $startDate, $description, $policyNumber, $contactPersonName,$phoneNumber, $emailId, $coverageInfo,$limit, $premiumAmount, $deductibleAmount,$endDate, $requireRenewal, $renewalDuration, $hdnDeletedImageName, $imagesName, $displayOrder, $displayOrderArray, $totalImage, $filesData)
{
    $currentDateTime = date('Y-m-d H:i:s');
   
    $date = DateTime::createFromFormat("m-d-Y" , $startDate);
    $startDateFinal = $date->format('Y-m-d');
    if(!empty($endDate))
    {
        $dateEnd = DateTime::createFromFormat("m-d-Y" , $endDate);
        $endDateFinal = $dateEnd->format('Y-m-d');
    }
    if(empty($premiumAmount))
        $premiumAmount = 0.00;
    if(empty($deductibleAmount))
        $deductibleAmount = 0.00;
    if(empty($renewalDuration))
        $renewalDuration = 'monthly';
    
    $dbObj = new Database(); // database object
    $dbObj->conn->beginTransaction(); // start transaction
   
    $objInsuranceMasterChild = new insuranceMasterChild($dbObj);
    $objInsuranceMasterChild->name = $insuranceName;
    $objInsuranceMasterChild->description = $description;
    $objInsuranceMasterChild->insurance_company = $insuranceCompanyName;
    $objInsuranceMasterChild->policy_number = $policyNumber;
    $objInsuranceMasterChild->contact_person = $contactPersonName;
    $objInsuranceMasterChild->phone_number = $phoneNumber;
    $objInsuranceMasterChild->email_id = $emailId;
    $objInsuranceMasterChild->coverage_information = $coverageInfo;
    $objInsuranceMasterChild->limits = $limit;
    $objInsuranceMasterChild->premium_amount = $premiumAmount;
    $objInsuranceMasterChild->deductible_amount = $deductibleAmount;
    $objInsuranceMasterChild->start_date = $startDateFinal;
    $objInsuranceMasterChild->end_date = $endDateFinal;
    $objInsuranceMasterChild->require_renewal = $requireRenewal;
    $objInsuranceMasterChild->renewal_duration = $renewalDuration;
    $objInsuranceMasterChild->modified = $currentDateTime;
    $objInsuranceMasterChild->created = $currentDateTime;
    $objInsuranceMasterChild->insert();
    //echo 'Error >> '.$objInsuranceMasterChild->error;exit;
    $lastInsertedId = $objInsuranceMasterChild->id;
    if (($lastInsertedId > 0) && (empty($objInsuranceMasterChild->error))) 
    {
        //$response = array(200); // Success
        //If Images Present then code for upload 
        if($totalImage > 0)
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
        }
    }
    else 
    {
        $dbObj->conn->rollBack();
        $dbObj->conn = NULL;
        $response = array(720); // There is something wrong to add data.
    }
    unset($objInsuranceMasterChild);
    return $response;
} // addCategory() ends here

function uploadImagesForInsurance($dbObj, $hdnDeletedImageName, $imagesName, $displayOrder, $displayOrderArray, $totalImage, $lastInsertedId, $filesData)
{
    $displayOrder = 0;
    $tempj = 1;
    
    $path = "../uploads/insurance/".$lastInsertedId;
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
            unlink("../uploads/insurance/".$lastInsertedId."/".$deleteImageName);
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
        //Update code for UPdate record in insurance mastrer table for attachement
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
    $objUpdateInsuranceChild = new insuranceMasterChild($dbObj);
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
        if($columnName == 'insurance_name')
        {
            $orderOn = 'name '.$columnSortOrder;
        }
        else if($columnName == 'insurance_company_name')
        {
            $orderOn = 'insurance_company '.$columnSortOrder;
        }
         else if($columnName == 'policy_number')
        {
            $orderOn = 'policy_number '.$columnSortOrder;
        }
        else if($columnName == 'contact_person')
        {
            $orderOn = 'contact_person '.$columnSortOrder;
        } else if($columnName == 'insurance_company_name')
        {
            $orderOn = 'insurance_company '.$columnSortOrder;
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
            // echo "searched text >> ".$searchText."<br>";
            $searchColumnName = $requestedData['columns'][$i]['name'];		
            // echo "column name >> ".$searchColumnName."<br>";exit;
            if($searchColumnName == 'status')
            {
                if($searchText == 'all')
                {
                    $newCondition = "( (status = ?) OR (status = ?) OR (status = ?))";
                    array_push($paramArray, 'active', 'inactive', 'expired');
                }
                else
                {
                    $newCondition = " (status = ?) ";
                    array_push($paramArray, $searchText);
                }
            }
            else if($searchColumnName == 'insurance_name')
            {
                $newCondition = "(name LIKE ?)";
                array_push($paramArray, $searchTextForLikeCondition);
            }
            else if($searchColumnName == 'insurance_company_name')
            {
                $newCondition = "(insurance_company LIKE ?)";
                array_push($paramArray, $searchTextForLikeCondition);
            }
            else if($searchColumnName == 'policy_number')
            {
                $newCondition = "(policy_number LIKE ?)";
                array_push($paramArray, $searchTextForLikeCondition);
            }
            else if($searchColumnName == 'contact_person')
            {
                $newCondition = "(contact_person LIKE ?)";
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
function insuranceListing($condition, $orderOn, $orderBy, $start, $length, $requestedData, $paramArray)
{
    //echo "condition :: $condition";
    //echo '<pre>';print_r($paramArray);//exit;
    $recordsFiltered = 0;
    $recordsTotal = 0;
    $dataArray = array();
    $objInsuranceMaster = new insuranceMasterChild();
    $objInsuranceMaster->selectColumn = "id";
    $objInsuranceMaster->param = $paramArray;
    $objInsuranceMaster->condition = $condition;
    $rsCategoryData = $objInsuranceMaster->selectByColumn();
    $recordsTotal = $objInsuranceMaster->numRows;
    //echo "records total $recordsTotal";exit;
    if($recordsTotal > 0 && empty($objInsuranceMaster->error))
    {
        unset($objInsuranceMaster);
        $recordsFiltered = $recordsTotal;
        // To get all record from filter
        if ($length == -1)
        {
            $length = $recordsTotal;
        }
        // To get limitwise records
        $objInsuranceMasterChild = new insuranceMasterChild();
        $objInsuranceMasterChild->selectColumn = "id, name, insurance_company, policy_number, contact_person, start_date, end_date, status";
        $objInsuranceMasterChild->param = $paramArray;
        $objInsuranceMasterChild->condition = "".$condition." ORDER BY $orderOn $orderBy LIMIT ".$start.",".$length;
        $rsInsuranceMasterData = $objInsuranceMasterChild->selectByColumn();
        $numRows = $objInsuranceMasterChild->numRows;
        
        if($numRows > 0 && empty($objInsuranceMasterChild->error))
        {
            unset($objInsuranceMasterChild);
            $cnt = 0;	
            $count = $start + 1;
            //echo "<pre>";print_r($rsInsuranceMasterData);exit;
            foreach($rsInsuranceMasterData AS $data)
            {
                $id = $data['id'];
                $insuranceName = ucfirst($data['name']);
                $insuranceCompanyName = ucfirst($data['insurance_company']);
                $policyNumber = $data['policy_number'];
                if(empty($policyNumber))
                    $policyNumber = '-';
                $contactPerson = $data['contact_person'];
                if(empty($contactPerson))
                    $contactPerson = '-';
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
                $dataArray[$cnt]['insurance_name'] = $insuranceName;
                $dataArray[$cnt]['insurance_company_name'] = $insuranceCompanyName;
                $dataArray[$cnt]['policy_number'] = $policyNumber;
                $dataArray[$cnt]['contact_person'] = $contactPerson;
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
            unset($objInsuranceMasterChild);
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
function editInsurance($editId,$insuranceName, $insuranceCompanyName, $startDate, $description, $policyNumber, $contactPersonName,$phoneNumber, $emailId, $coverageInfo,$limit, $premiumAmount, $deductibleAmount,$endDate, $requireRenewal, $renewalDuration)
{
    $currentDateTime = date('Y-m-d H:i:s');
    $insuranceId = getInsuranceId($editId);
    
    if($insuranceId != 0)
    {
        $date = DateTime::createFromFormat("m-d-Y" , $startDate);
        $startDateFinal = $date->format('Y-m-d');
        if(!empty($endDate))
        {
            $dateEnd = DateTime::createFromFormat("m-d-Y" , $endDate);
            $endDateFinal = $dateEnd->format('Y-m-d');
        }
        if(empty($premiumAmount))
            $premiumAmount = 0.00;
        if(empty($deductibleAmount))
            $deductibleAmount = 0.00;
        
        
        $objInsuranceMasterChild = new insuranceMasterChild();
        $objInsuranceMasterChild->name = $insuranceName;
        $objInsuranceMasterChild->description = $description;
        $objInsuranceMasterChild->insurance_company = $insuranceCompanyName;
        $objInsuranceMasterChild->policy_number = $policyNumber;
        $objInsuranceMasterChild->contact_person = $contactPersonName;
        $objInsuranceMasterChild->phone_number = $phoneNumber;
        $objInsuranceMasterChild->email_id = $emailId;
        $objInsuranceMasterChild->coverage_information = $coverageInfo;
        $objInsuranceMasterChild->limits = $limit;
        $objInsuranceMasterChild->premium_amount = $premiumAmount;
        $objInsuranceMasterChild->deductible_amount = $deductibleAmount;
        $objInsuranceMasterChild->start_date = $startDateFinal;
        $objInsuranceMasterChild->end_date = $endDateFinal;
        $objInsuranceMasterChild->require_renewal = $requireRenewal;
        $objInsuranceMasterChild->renewal_duration = $renewalDuration;
        $objInsuranceMasterChild->attachment_files = '';
        $objInsuranceMasterChild->modified = $currentDateTime;
        $objInsuranceMasterChild->created = $currentDateTime;
        $objInsuranceMasterChild->condition = "id = $insuranceId";
        $objInsuranceMasterChild->update();
       // echo "error << ".$objInsuranceMasterChild->error;exit;
        if(empty($objInsuranceMasterChild->error))
        {
            $response = array(200); // Success
        }
        else 
        {
            $response = array(304); // Record could not be updated
        }
        unset($objInsuranceMasterChild);
    }
    else
    {
        $response = array(304); // Record could not be updated
    }
    return $response;
} // editInsurance() ends here


/*
    Funtion to get insurance data from insurance id
*/
function getInsuranceData($id)
{
    $insuranceId = getInsuranceId($id);
    if($insuranceId != 0)
    {
        $paramArray = array($insuranceId);
        $objInsuranceMasterChild = new insuranceMasterChild();
        $objInsuranceMasterChild->selectColumn = "id, name, description, insurance_company, policy_number, contact_person, phone_number, email_id, coverage_information, limits, premium_amount, deductible_amount, start_date, end_date, require_renewal, renewal_duration";
        $objInsuranceMasterChild->param = $paramArray;
        $objInsuranceMasterChild->condition = "id = ?";
        $rsInsuranceMaster = $objInsuranceMasterChild->selectByColumn();
        $numRowsCategoryMaster = $objInsuranceMasterChild->numRows;
        $dataArray = array();
        if($numRowsCategoryMaster > 0 && empty($objInsuranceMasterChild->error))
        {
            $dataArray['name'] = $rsInsuranceMaster[0]['name'];
            $dataArray['description'] = $rsInsuranceMaster[0]['description'];
            $dataArray['insurance_company'] = $rsInsuranceMaster[0]['insurance_company'];
            $dataArray['policy_number'] = $rsInsuranceMaster[0]['policy_number'];
            $dataArray['contact_person'] = $rsInsuranceMaster[0]['contact_person'];
            $dataArray['phone_number'] = $rsInsuranceMaster[0]['phone_number'];
            $dataArray['email_id'] = $rsInsuranceMaster[0]['email_id'];
            $dataArray['coverage_info'] = $rsInsuranceMaster[0]['coverage_information'];
            $dataArray['limits'] = $rsInsuranceMaster[0]['limits'];
            $dataArray['premium_amount'] = $rsInsuranceMaster[0]['premium_amount'];
            $dataArray['deductible_amount'] = $rsInsuranceMaster[0]['deductible_amount'];
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
            $dataArray['require_renewal'] = $rsInsuranceMaster[0]['require_renewal'];
            $dataArray['renewal_duration'] = $rsInsuranceMaster[0]['renewal_duration'];
            $response = array(200, $dataArray); // Success
        }
        else
        {
            $response = array(704); // No records present
        }
        unset($objInsuranceMasterChild);
    }
    else
    {
        $response = array(704); // No records present
    }
    return $response;
} // getCategoryData() ends here

/*
    Function to get insurance id from md5 insurance id
*/
function getInsuranceId($id)
{
    global $key;
    $objInsurance = new insuranceMasterChild();
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