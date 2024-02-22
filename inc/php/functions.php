<?php
//error_reporting(0);

function getStatusCodeMessage($status)
{
   $codes = Array(
	100 => 'Continue',
	101 => 'Switching Protocols',
	200 => 'OK',
	201 => 'Created',
	202 => 'Accepted',
	203 => 'Non-Authoritative Information',
	204 => 'No Content',
	205 => 'Reset Content',
	206 => 'Partial Content',
	300 => 'Multiple Choices',
	301 => 'Moved Permanently',
	302 => 'Found',
	303 => 'See Other',
	304 => 'Record could not be updated',
	305 => 'Use Proxy',
	306 => '(Unused)',
	307 => 'Temporary Redirect',
	400 => 'Bad Request',
	401 => 'Unauthorized', 
	402 => 'Payment Required',
	403 => 'Forbidden',
	404 => 'Not Found',
	405 => 'Method Not Allowed',
	406 => 'Not Acceptable',
	407 => 'Proxy Authentication Required',
	408 => 'Request Timeout',
	409 => 'Conflict',
	410 => 'Gone',
	411 => 'Length Required',
	412 => 'Precondition Failed',
	413 => 'Request Entity Too Large',
	414 => 'Request-URI Too Long',
	415 => 'Unsupported Media Type',
	416 => 'Requested Range Not Satisfiable',
	417 => 'Expectation Failed',
	500 => 'Internal Server Error',
	501 => 'Not Implemented',
	502 => 'Bad Gateway',
	503 => 'Service Unavailable',
	504 => 'Gateway Time out',
	505 => 'HTTP Version Not Supported',
	701 => 'Mandatory parameters are missing',
	702 => 'Authentication failed. Invalid auth_token',
	703 => 'Email id already exist. Please try different email',
	704 => 'No records present',
	705 => 'Data could not be saved. Please, try again!',
	706 => 'Invalid email-id.',
	707 => 'Unable to update password. Please try again!',
	708 => 'Unknown request.',
	709 => 'Email or password is incorrect. Please try again!',
	710 => 'Please contact administrator.',
	711 => 'Mail send to your email id, Please reset password.',
	712 => 'This user has been disabled. You can not send message!',
	713 => 'Image does not exist!',
	714 => "Looks like you haven't verified your email id yet! Please verify your email to login.",
	715 => 'Your account is inactive. Please contact administrator!',
	716 => 'Your account is deleted. Please contact administrator!',
	717 => 'There is something wrong to get template data',
	718 => 'Thanks for the registration. I sent mail to your email id. Please verify and login to the system.',
	719 => 'Services not available. Please try after sometime!',
	720 => 'There is something wrong to add data.',
	721 => 'Enter registered email id',
	722 => 'Only png and jpg image formate is allow.',
	723 => 'There is something wrong to add your report.',
	724 => 'There is something wrong to delete.',
	725 => 'There is something wrong to upload image',
	726 => 'Curl error',
	727 => 'There is something wrong to add your snap.',
	728 => 'There is something wrong to add contact us.',
	729 => 'Your account has been deleted or inactive. Please contact to administrator!',
	730 => 'Mobile number already exist. Please try different mobile number',
	731 => 'There is something wrong. Please try again OR contact administrator!.',
	732 => 'You can not delete this snap. This snap is used in submitted/approved report ',
        733 => 'Email id and mobile number already exist. Please try different email and mobile number',
        734 => 'Invalid verify email url',
        735 => 'Otp number is not valid. </br> Please enter valid otp number',
        736=> "Looks like you haven't verified your email id yet! Please verify your email id to login",
        737=> 'Username or password are not matching, Please try again!',
        738=> 'Please upload photo with size of minimum and maximum height and width',
        739=> 'Please Upload .jpg, .jpeg ,.png Extentions file only',
       740=> 'Please upload max image size is 2 mb',
       741=> 'Image is not Uploaded. Something went worng',
       742=> 'There is no such user',
       743=> 'Email Id not present. Please try again.',
       744=> 'Please Upload .pdf, .doc Extentions file only',
       745=> 'Invalid current password. Please enter correct password',
       746=> 'Invalid request',
       747=> 'Something went wrong to insert IP Address.',
       748=> 'There is something wrong to send OTP through SMS',
       749=> 'Your account is not verified by admin. Please verify first to access api.',
       750=> 'Invalid request initiator',
       751=> 'There is something wrong to cancle your withdrawal request',
       752=> 'No child for the selected category'
);
   
    return (isset($codes[$status])) ? $codes[$status] : '';
}
function clean($string)
{
    //echo "string ::".$string;
    $string = trim($string);
    $string = strtolower($string);
    $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
    $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
    
    $stringValue =  preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
    $leftTrimString = ltrim($stringValue, '-');
    $rightTrimString = rtrim($leftTrimString, '-');
    return $rightTrimString;
}

function getCodeUrl()
{
    $objSettingChild = new settingsChild(); // object of setting child class
    $objSettingChild->selectColumn = "code_url";
    $objSettingChild->condition = " id=1";
    $rsSetting = $objSettingChild->selectByColumn();
    foreach($rsSetting AS $rsObjectSetting)
    {
        $codeUrl = $rsObjectSetting['code_url'];
    } // for each loop ends here
	
	return $codeUrl;
}// Function 

//Function for gererating JSON
function generateJSON($dataArray)
{
	//echo "<pre>";
	//print_r($dataArray);
	$json_data = (json_encode($dataArray,JSON_UNESCAPED_UNICODE));
	/* 
	JSON error checking can be done with following line of code
	Use json_last_error() - It will return error number
	Use json_last_error_msg() - It will give the error message to understand
	*/
	//echo "<br/> Json Encode Value >> ".$json_data." ::JSON ERROR: ".json_last_error()." :: Error message :: ". json_last_error_msg();
	return $json_data;
}

function getCurrentUrl( $trim_query_string = false ) 
{
    //https://cssjockey.com/current-url-in-php-with-or-without-query-string/
    $pageURL = (isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] == 'on') ? "https://" : "http://";
    $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
    if( ! $trim_query_string ) 
    {
        return $pageURL;
    } 
    else 
    {
        $url = explode( '?', $pageURL );
        return $url[0];
    }
}

function isMobile()
{
    $tablet_browser = 0;
    $mobile_browser = 0;

    if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', strtolower($_SERVER['HTTP_USER_AGENT'])))
    {
        $tablet_browser++;
    }
    if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', strtolower($_SERVER['HTTP_USER_AGENT'])))
    {
        $mobile_browser++;
    }
    if ((strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') > 0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE']))))
    {
        $mobile_browser++;
    }
    $mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
    $mobile_agents = array(
                    'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
                    'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
                    'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
                    'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
                    'newt','noki','palm','pana','pant','phil','play','port','prox',
                    'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
                    'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
                    'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
                    'wapr','webc','winw','winw','xda ','xda-');

    if (in_array($mobile_ua,$mobile_agents))
    {
        $mobile_browser++;
    }
    if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'opera mini') > 0)
    {
        $mobile_browser++;
        //Check for tablets on opera mini alternative headers
        $stock_ua = strtolower(isset($_SERVER['HTTP_X_OPERAMINI_PHONE_UA'])?$_SERVER['HTTP_X_OPERAMINI_PHONE_UA']:(isset($_SERVER['HTTP_DEVICE_STOCK_UA'])?$_SERVER['HTTP_DEVICE_STOCK_UA']:''));
        if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $stock_ua))
        {
            $tablet_browser++;
        }
    }
    if ($tablet_browser > 0)
    {
        // do something for tablet devices
        return 'tablet';
    }
    else if ($mobile_browser > 0)
    {
        // do something for mobile devices
        return 'mobile';
    }
    else
    {
        // do something for everything else
        return 'desktop';
    }
} // function isMobile ends here for detecting that device is mobile or the tablet ,desktop

function getEmailTemplate($templateName)
{
    //echo "<br>email template::".$templateName;
    $paramArray = array($templateName);
    // Welcome mail to user for registration
    $objEmailTemplateChild = new emailTemplateMasterChild();
    $objEmailTemplateChild->selectColumn="from_email,from_name,to_name,to_email,subject,body";
    $objEmailTemplateChild->param = $paramArray;
    $objEmailTemplateChild->condition = "name = ?";
    $rsTemplate = $objEmailTemplateChild->selectByColumn();
    if(count($rsTemplate) > 0 && empty($objEmailTemplateChild->error))
    {
        return $rsTemplate;
    }
    else
    {
        return 0;
    }
}

function getValidationSectionWiseQuestions($auditId,$auditTypeId,$auditTypeFormRevisionId)
{
    //Get All Sectiona fill or not
	$objAuditDataChild = new auditDataChild();
	$objAuditDataChild->selectColumn = "ad.id,ad.audit_id, ad.audit_section_id, asec.section_name,ad.user_answer, ad.question_number, ad.minimum_subquestion, ad.config_option";
	$objAuditDataChild->alias = "audit_data as ad LEFT JOIN audit_section as asec ON ad.audit_section_id = asec.id";
	$objAuditDataChild->condition = "ad.audit_id = $auditId AND ad.audit_type_id=$auditTypeId AND ad.audit_form_revision_id = $auditTypeFormRevisionId AND ad.parent_question_id = 0 ORDER BY asec.id ASC";
    $rsAuditDataDetail = $objAuditDataChild->selectByJoin();
    $auditDataNumRow = $objAuditDataChild->numRows; 
	if($auditDataNumRow > 0)
	{
      	$auditDataSectionUserAnswerArray = array();
		$i=0;
		$tempAuditSectionId = 0;
		$isUserAnswer = true;
		$count=0;
		foreach($rsAuditDataDetail as $getAuditData)
		{	
			$parentId = $getAuditData['id'];
			$parentAuditId = $getAuditData['audit_id'];
            $auditSectionId = $getAuditData['audit_section_id'];
            $auditSectionName = $getAuditData['section_name'];
			$auditSectionUserAnswer = $getAuditData['user_answer'];
			$questionNumber = $getAuditData['question_number'];
			$minimumSubQuestion = $getAuditData['minimum_subquestion'];
			
			$configOptionValue = $getAuditData['config_option'];
			$configOptionValueArray = json_decode( $configOptionValue, true );
			
			/**For SubQuestions START*/
			if($auditTypeFormRevisionId <= 2)
			{
				$subQuestionAttemptCount = checkAttemptSubQuestionCount($parentAuditId,$parentId,$auditSectionId,$minimumSubQuestion);
			}
			else
			{
				$subQuestionAttemptCount = checkNewAttemptSubQuestionCount($parentAuditId,$parentId,$auditSectionId,$minimumSubQuestion);
			}
			
			if($subQuestionAttemptCount < $minimumSubQuestion)
			{
				$auditSectionUserAnswer = '';
			}
			/**For SubQuestions END*/


			if($auditSectionId != $tempAuditSectionId)
			{
				$count++;
				$i=0;
			}

			if($auditTypeFormRevisionId <= 2)
			{	
				if($auditSectionUserAnswer == '' || $auditSectionUserAnswer == NULL)
				{
					$auditDataSectionUserAnswerArray[$count][$i]['audit_section_id'] = $auditSectionId;
					$auditDataSectionUserAnswerArray[$count][$i]['audit_section_name'] = $auditSectionName;
					$auditDataSectionUserAnswerArray[$count][$i]['question_number'] = $questionNumber;
					$auditDataSectionUserAnswerArray[$count][$i]['user_answer'] = $auditSectionUserAnswer;
					$i++;
				}
			}	
			else
			{
				$isSectionCompleted = false;
				if(isset($configOptionValueArray['is_mandatory']))
				{
					$isMandatory = $configOptionValueArray['is_mandatory'];
					//echo "</br> is Mandtory::".$isMandatory." :: ".$auditSectionUserAnswer;
					if($isMandatory == 'true' && $auditSectionUserAnswer != '')
					{
						$isSectionCompleted = true;
					}
					else if($isMandatory == 'false')
					{
						$isSectionCompleted = true;
					}
					else
					{
						$isSectionCompleted = false;
					}
				}//if ends here

				//echo ":: is section completed::".$isSectionCompleted;
				if($isSectionCompleted != 1)
				{
					$auditDataSectionUserAnswerArray[$count][$i]['audit_section_id'] = $auditSectionId;
					$auditDataSectionUserAnswerArray[$count][$i]['audit_section_name'] = $auditSectionName;
					$auditDataSectionUserAnswerArray[$count][$i]['question_number'] = $questionNumber;
					$auditDataSectionUserAnswerArray[$count][$i]['user_answer'] = $auditSectionUserAnswer;
					$i++;
				}
			}
			$tempAuditSectionId = $auditSectionId;
			
		}//foreach ends here
	}
	//echo "<pre>";
    //print_r($auditDataSectionUserAnswerArray);
    //exit;
    return $auditDataSectionUserAnswerArray;
}

function checkAttemptSubQuestionCount($parentAuditId,$parentId, $auditSectionId,$minimumSubQuestion)
{
	$objAuditDataChild = new auditDataChild();
	$objAuditDataChild->selectColumn = "count(id) as subquestion_count";
	$objAuditDataChild->condition = "audit_id = $parentAuditId AND audit_section_id=$auditSectionId AND parent_question_id = $parentId AND user_answer != ''";
    $rsAuditDataDetail = $objAuditDataChild->selectByColumn();
    $auditDataNumRow = $objAuditDataChild->numRows; 
	if($auditDataNumRow > 0)
	{
		$attemptSubQuestionCount = $rsAuditDataDetail[0]['subquestion_count'];

		return $attemptSubQuestionCount;
	}
	else 
	{
		return 0;
	}

}
function checkNewAttemptSubQuestionCount($parentAuditId,$parentId, $auditSectionId,$minimumSubQuestion)
{
	$objAuditDataChild = new auditDataChild();
	$objAuditDataChild->selectColumn = "id,config_option, user_answer";
	$objAuditDataChild->condition = "audit_id = $parentAuditId AND audit_section_id=$auditSectionId AND parent_question_id = $parentId";
    $rsAuditDataDetail = $objAuditDataChild->selectByColumn();
    $auditDataNumRow = $objAuditDataChild->numRows; 
	if($auditDataNumRow > 0)
	{
		$attemptedTotalAnswerCount = 0;
        foreach($rsAuditDataDetail as $data)
        {   
            $questionUserAnswer = $data['user_answer'];
            $configOptionValue = $data['config_option'];
            $configOptionValueArray = json_decode( $configOptionValue, true );
            if(isset($configOptionValueArray['is_mandatory']))
            {
                $isMandatory = $configOptionValueArray['is_mandatory'];
                if($isMandatory == 'true' && $questionUserAnswer != '')
                {
                    $attemptedTotalAnswerCount += 1;                
                }
    
                if($isMandatory == 'false')
                {
                    $attemptedTotalAnswerCount += 1;                
                }
            }//if ends here
		}//Foreach ends here
		$attemptSubQuestionCount = $attemptedTotalAnswerCount;
		return $attemptSubQuestionCount;
	}
	else 
	{
		return 0;
	}
}


function getCompletedSectionWiseQuestions($auditId,$auditTypeId)
{
    //Get All Sectiona fill or not
	$objAuditDataChild = new auditDataChild();
	$objAuditDataChild->selectColumn = "ad.audit_section_id, asec.section_name,ad.user_answer, ad.question_number";
	$objAuditDataChild->alias = "audit_data as ad LEFT JOIN audit_section as asec ON ad.audit_section_id = asec.id";
	$objAuditDataChild->condition = "ad.audit_id = $auditId AND ad.audit_type_id=$auditTypeId ORDER BY asec.id ASC";
    $rsAuditDataDetail = $objAuditDataChild->selectByJoin();
    $auditDataNumRow = $objAuditDataChild->numRows; 
	if($auditDataNumRow > 0)
	{
      	$auditDataSectionUserAnswerArray = array();
		$i=0;
		$tempAuditSectionId = 0;
		$isUserAnswer = true;
		$count=0;
		foreach($rsAuditDataDetail as $getAuditData)
		{	
            $auditSectionId = $getAuditData['audit_section_id'];
            $auditSectionName = $getAuditData['section_name'];
			$auditSectionUserAnswer = $getAuditData['user_answer'];
			$questionNumber = $getAuditData['question_number'];
			if($auditSectionId != $tempAuditSectionId)
			{
				$count++;
				$i=0;
			}

			if($auditSectionUserAnswer != '' || $auditSectionUserAnswer != NULL)
			{
                $auditDataSectionUserAnswerArray[$count][$i]['audit_section_id'] = $auditSectionId;
				$auditDataSectionUserAnswerArray[$count][$i]['audit_section_name'] = $auditSectionName;
				$auditDataSectionUserAnswerArray[$count][$i]['question_number'] = $questionNumber;
				$auditDataSectionUserAnswerArray[$count][$i]['user_answer'] = $auditSectionUserAnswer;
				$i++;
			}
			
			$tempAuditSectionId = $auditSectionId;
			
		}//foreach ends here
	}
	/*echo "<pre>";
    print_r($auditDataSectionUserAnswerArray);
    exit;*/
    return $auditDataSectionUserAnswerArray;
}

function getStoreIdOfLoggedUser($loggedInUserId)
{
	$objStaffChild = new staffChild();
	$objStaffChild->selectColumn = "id,branch_id";
	$objStaffChild->condition = "id = $loggedInUserId";
	$rsData = $objStaffChild->selectByColumn();
	$numOfRows = $objStaffChild->numRows;
	//echo "</br>Num of Rows::".$numOfRows;
	if($numOfRows > 0)
	{
		//echo "<pre>";
		//print_r($rsData);
		$loggedUserStoreIds = $rsData[0]['branch_id'];
		return $loggedUserStoreIds;
	}

}

function checkAuditId($editedAuditId)
{
	$objAuditChild = new auditChild();
	$objAuditChild->selectColumn = "id";
	$objAuditChild->condition = "id = $editedAuditId AND is_deleted=0";
	$rsData = $objAuditChild->selectByColumn();
	$numOfRows = $objAuditChild->numRows;
	unset($objAuditChild);
	if($numOfRows > 0)
	{
		$auditIds = $rsData[0]['id'];
		return $auditIds;
	}
	else
	{
		return 0;
	}

}

function getAuditTypeName($permalink)
{
	$paramArray = array($permalink);
    $objGetAuditTypeId = new auditTypeChild();
    $objGetAuditTypeId->selectColumn = "id,audit_form_revision_id,audit_type,audit_type_name,category,display_type";
    $objGetAuditTypeId->param = $paramArray;
    $objGetAuditTypeId->condition = 'permalink = ?';
    $rsAuditTypeId = $objGetAuditTypeId->selectByColumn();
    $numRowAudit = $objGetAuditTypeId->numRows;
    if($numRowAudit > 0)
    {
        //$auditType = $rsAuditTypeId[0]['audit_type'];
		//$auditTypeName = $rsAuditTypeId[0]['audit_type_name'];
		return $rsAuditTypeId;
	}
	//return $auditTypeName;
}
//Code Added by khyati panchal (23 July 2021)
//Decrease Audit completed count if audit status is 'submitted' or 'edit_submitted'
function decreaseAuditStatesCount($editedAuditId, $auditTypeId)
{
    //Get Audit completed date time of current audit and fetch month from that
    $auditParamArray = array($editedAuditId, 0,'active');
    $objAuditChild = new auditChild();
    $objAuditChild->selectColumn = "audit_completed_datetime";
    $objAuditChild->param = $auditParamArray;
    $objAuditChild->condition = "id= ? AND is_deleted=? AND status = ?";
    $rsAuditData = $objAuditChild->selectByColumn();
    unset($objAuditChild);
    
    $auditCompletedDateTime = $rsAuditData[0]['audit_completed_datetime'];
    $auditCompletedMonth = date("n",strtotime($auditCompletedDateTime));
    $auditCompletedYear = date("Y",strtotime($auditCompletedDateTime));
    //echo "</br>Year >> ".$auditCompletedYear." MOnth >> ".$auditCompletedMonth;
    
    //Decrease 1 count from statistics for this 'month' and 'year' and aslo same 'audit type id'
    $currentDateTime = date('Y-m-d H:i:s');
    $updateFields = " completed_count = GREATEST(0, completed_count - 1), modified = '$currentDateTime'";
        
    $objAuditStatisticsUpdate = new auditStatisticsChild();
    $objAuditStatisticsUpdate->update_fields = $updateFields;
    $objAuditStatisticsUpdate->condition = "audit_type_id = $auditTypeId AND year = $auditCompletedYear AND month = $auditCompletedMonth";
    $objAuditStatisticsUpdate->customDecreseCountUpdate();
    
    if(empty($objAuditStatisticsUpdate->error))
    {
        $return = 1;
    }
    else
    {
        $return = 0;
    }
    return $return;
}
?>