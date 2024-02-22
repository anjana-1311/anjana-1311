<!-- 
    Created by Khyati panchal 25 Feb 2022
    file name : add_insurance.php
 -->

<?php
    ini_set('max_execution_time', '0'); // for infinite time of execution
    ini_set('upload_max_size', '128MB');
    ini_set( 'post_max_size', '128MB');
    ini_set( 'memory_limit', '512MB');
    require_once 'inc/php/config.php';
    require_once 'inc/dal/baseclasses/class.database.php';
    require_once 'inc/dal/insurance_master.child.php';
    require_once 'inc/dal/settings.child.php';
    require_once 'inc/php/functions.php';

    $codeUrl = getCodeUrl(); // call function and get code url
    $formAction = 'add'; // action = add
    
    if((isset($_REQUEST['action']) && !empty($_REQUEST['action'])) && isset($_REQUEST['id']) && !empty($_REQUEST['id']))
    {
        $formAction = $_REQUEST['action']; // action = edit
        $insuranceId = $_REQUEST['id'];
        $hdnInsuranceId = $insuranceId; // update $hdnCategoryId
    }   
?>

<!doctype html>
<html class="no-js">

<head>
    <title><?php echo ucfirst($formAction); ?> Insurance</title>
    <?php require_once ('inc/php/head.php'); ?>
    
    <style type="text/css">
	#drop-area
	{
            border: 2px dashed #ccc;  
            padding: 35px 0px;
	}
	#drop-area.highlight {
	  border-color: #4048af;
	}
	.my-form {
	  margin-bottom: 10px;
	}
	#gallery {
	  margin-top: 10px;
	}
	#gallery img {
	  width: 150px;
	  margin-bottom: 10px;
	  margin-right: 10px;
	  vertical-align: middle;
	}
	#imageGallery {
	  display: none;
	}
	.addImage .uploadBy input {

	   position:absolute;
		opacity: 0;
	}
    </style>
    
    <script src="inc/js/jquery-ui.js"></script>

    <link rel="stylesheet" href="inc/css/jquery.timepicker.css">
    <script src="inc/js/jquery.timepicker.js"></script>

    <script type="text/javascript">
        var codeUrl = '<?php echo $codeUrl; ?>'; // code url
        var formAction = '<?php echo $formAction;?>'; // action = add, edit
        var countOfParentCategory = '<?php echo $countOfParentCateogry; ?>';
        
        // onload start
        $(document).ready(function() 
        {
            if(formAction == 'edit')
            {
                var insuranceId = '<?php echo $insuranceId; ?>'; 
                getInsuranceDataThroughAjax(insuranceId); 
            }
            $('#txtStartDate').datepicker({
			dateFormat: 'mm-dd-yy',
			onSelect:function(selectedDate, datePicker) {            
			}
		});
            $('#txtEndDate').datepicker({
                   dateFormat: 'mm-dd-yy',
                   onSelect:function(selectedDate, datePicker) {            
                   }
           });
        });
        // onload end

        // function start here
        function getInsuranceDataThroughAjax(insuranceId)
        {
            var finalUrl = codeUrl + "api/insurance_master.php" ; // final url
           
            // ajax start
            $.ajax({
                    type: "POST",
                    url: finalUrl,
                    data : "action=get_insurance_data&insurance_id="+insuranceId,
                    cache: true,
                    async: true,
                    timeout: 30000,
                    dataType : "json",
                    beforeSend: function() 
                    {
                        $("#addInsuranceLoader").show(); // start loader
                    },
                    success: function(response) 
                    {
                        var status = response.header.status; // get status code
                        var message = response.header.message; // get message

                        console.log("status ::"+status);
                        console.log("message ::"+message);

                        if (status === 200 && message == 'OK') 
                        {
                            var data = response.data; // get data
                            console.log("data :: " + JSON.stringify(data));

                            if(data)
                            {
                                var name = data[0].name; 
                                var description = data[0].description; 
                                var insuranceCompany = data[0].insurance_company; 
                                var policyNumber = data[0].policy_number; 
                                var contactPerson = data[0].contact_person; 
                                var phoneNumber = data[0].phone_number; 
                                var emailId = data[0].email_id; 
                                var coverageInfo = data[0].coverage_info; 
                                var limits = data[0].limits; 
                                var premiumAmount = data[0].premium_amount; 
                                var deductibleAmnt = data[0].deductible_amount; 
                                var startDate = data[0].start_date; 
                                var endDate = data[0].end_date; 
                                var requireRenewal = data[0].require_renewal;
                                if(requireRenewal == 0)
                                {
                                    $("#renewalNo").attr('checked', 'checked');
                                }
                                else
                                {
                                    $("#renewalYes").attr('checked', 'checked');
                                } 
                                var renewalDuration = data[0].renewal_duration;
                                
                                $("#txtName").val(name);
                                $("#txtInsuranceCompanyName").val(insuranceCompany);
                                $("#txtDescription").val(description);
                                $("#txtPolicyNumber").val(policyNumber);
                                $("#txtContactPersonName").val(contactPerson);
                                $("#txtPhoneNumber").val(phoneNumber);
                                $("#txtEmailId").val(emailId);
                                $("#txtCoverageInfo").val(coverageInfo);
                                $("#txtLimit").val(limits);
                                $("#txtPremiumAmnt").val(premiumAmount);
                                $("#txtDeductibleAmnt").val(deductibleAmnt);
                                $("#txtStartDate").val(startDate);
                                $("#txtEndDate").val(endDate);
                                $("#txtRenewalDuration").val(renewalDuration);
                            }
                        }
                        else 
                        {
                            $("#errorMessageDiv").show();
                            $("#errorMessageLableId").text('');
                            $("#errorMessageLableId").text(message);	
                        }
                    },
                    error: function(XMLHttpRequest, errorStatus, errorThrown) 
                    {
                        console.log("XHR :: "+JSON.stringify(XMLHttpRequest));
                        console.log("Status :: "+errorStatus);
                        console.log("error :: "+errorThrown);
                        var errorStatus = XMLHttpRequest['status'];
                        if(errorThrown == 'timeout')
                        {
                            alert('Request has been timeout. Please check your internet connection! (Error: '+errorStatus+')');
                        }
                        else
                        {
                            alert('There is something wrong! Please try again! (Error: '+errorStatus+')');
                        }
                    },
                    complete: function() 
                    {
                        $("#addInsuranceLoader").hide(); // hide loader
                    }
            });
    // ajax end here
        }
        // function end here : getCategoryDataThroughAjax
        
        // function start here
        function addInsuranceValidation() 
        {
            var isFormValidate = true; // set flag
            var txtInsuranceName = $("#txtName").val();
            var txtInsuranceCompanyName = $("#txtInsuranceCompanyName").val();
            var txtStartDate = $("#txtStartDate").val();	
            var txtEmail = $("#txtEmailId").val();
            
            var emailId = /\S+@\S+\.\S+/;
            
            if (txtInsuranceName == '' || txtInsuranceName == null || txtInsuranceName == undefined) 
            {
                $("#errorName").text('Insurance name is required');
                $("#errorName").show();
                isFormValidate = false;
            }
            if (txtInsuranceCompanyName == '' || txtInsuranceCompanyName == null || txtInsuranceCompanyName == undefined) 
            {
                $("#errorInsuranceCompanyName").text('Insurance company name is required');
                $("#errorInsuranceCompanyName").show();
                isFormValidate = false;
            }
            if(txtStartDate == '')
            {
                $("#errorStartDate").text('Start date is required');
                $("#errorStartDate").show();
                isFormValidate =  false;
            }
            if(txtEmail != '')
            {
                if(!txtEmail.match(emailId))
                {
                    $("#errorEmail").text('');
                    $("#errorEmail").text('Please enter valid email id');
                    $("#errorEmail").show();
                    isFormValidate =  false;
                }
            }

            navigateToErrorMessage(); // call function for navigate user to specific error message location

            if (isFormValidate) 
            {
                $("#addInsuranceLoader").show(); // show loader
                submitDataThroughAjax();
            } 
            else 
            {
                $("#addInsuranceLoader").hide(); // hide loader
                return false;
            }

        } 
        // addDistrictValidation end

        // function start here
        function navigateToErrorMessage() 
        {
            if($(".validation span").is(":visible"))
            {
                var errorMessageOffset = $(".validation span:visible:first-child").offset().top;
                $('html, body').animate({scrollTop: (errorMessageOffset - 70)}, 700);
            }
        }
        // function end here : navigateToErrorMessage

        // function start here
        function submitDataThroughAjax() 
        {
            var formData = new FormData(); // create object
            var allRequestData = $('#addInsuranceForm').serializeArray();
            
            //alert("allRequestData >> "+JSON.stringify(allRequestData));

            $.each(allRequestData,function(key,input)
            {
                // alert("name >> "+input.name+" value >> "+input.value);
                formData.append(input.name,input.value);
            });
            for (var value of formData.values()) 
            {
                console.log(value);
            }
            
            //alert("formData >> "+JSON.stringify(formData));

            var finalUrl = codeUrl+"api/insurance_master.php"; // finalUrl
            
            $.ajax({
                type: "POST",
                url: finalUrl,
                data: formData,
                dataType: "json",
                contentType:false,
                cache: false,
                processData: false,
                timeout : 0, 
                beforeSend : function()
                {
                    $("#addInsuranceLoader").show(); // show loader
                },
                success: function(response)
                {
                    var status = response.header.status; // get status code
                    var message = response.header.message; // get message

                    console.log("status ::"+status);
                    console.log("message ::"+message);
                    
                    if(status == 200)
                    {
                        window.location.href="insurance.php"; // redirect on page
                    } 
                    else
                    {
                        $("#errorMessageDiv").show();
                    	$("#errorMessageLableId").text('');
                        $("#errorMessageLableId").text(message);
                    }
                    return false;
                },
                error: function(XMLHttpRequest, errorStatus, errorThrown) 
                {
                    console.log("XHR :: "+JSON.stringify(XMLHttpRequest));
                    console.log("Status :: "+errorStatus);
                    console.log("error :: "+errorThrown);
                    var errorStatus = XMLHttpRequest['status'];
                    if(errorThrown == 'timeout')
                    {
                        alert('Request has been timeout. Please check your internet connection! (Error: '+errorStatus+')');
                        return false;
                    }
                    else
                    {
                        alert('There is something wrong! Please try again! (Error: '+errorStatus+')');
                        return false;
                    }
                },
                complete:function()
                {
                    $("#addInsuranceLoader").hide(); // hide loader
                }
            });
            return false;
        }
        // function ends here : submitDataThroughAjax

        // function start here
        function hideErrorMessage(id) 
        {
            $("#" + id).hide(); // hide error message
        } 
        // fuction end here : hideErrorMessage
        
    </script>
</head>
<body class="noMenu">
<!--
	================
	Header Start
	================
-->

<?php require_once ('inc/php/header.php'); ?>

<!-- 
	=============== 
	Page Title Start
	=============== 
-->
<section class="pageTitle">
    <h1><?php echo ucfirst($formAction); ?> Insurance</h1>
    <a href="insurance.php" class="back">Back</a>
    <div class="clr"></div>
</section>
<!-- 
	=============== 
	Page Title End
	=============== 
-->
<!-- 
	===============
	Page Content Start 
	===============
-->
<section class="pageContent widget">

    <!--
		===================
		Alert :: Success
		===================
	-->
    <div class="alert alert-success" style="display: none;" id="successMessageDiv">
        <img src="images/success-tik.svg" />
        <strong>Success!</strong>
        <lable id="successMessageLableId"></lable>
    </div>

	<!--
		===================
		Alert :: Error
		===================
	-->
    <div class="alert alert-error" style="display: none;" id="errorMessageDiv">
        <img src="images/error_x.svg" />
        <strong>Error!</strong>
        <lable id="errorMessageLableId"></lable>
    </div>
	
	<!-- 
    Created by Khyati panchal 25 Feb 2022
    file name : supplier_form.php
 -->
    <!-- form stat -->
    <form id="addInsuranceForm" name="addInsuranceForm" method="post" enctype="multipart/form-data" >
        <div class="card">
            <ul class="form">
                <li>
                    <div class="lbl">Name<span>*</span></div>
                    <div class="val">
                        <input autocomplete="off" type="text" class="input " placeholder="Enter Name" id="txtName" name="txtName" onkeypress="hideErrorMessage('errorName');" value='' />
                        <div class="validation">
                            <span style="display:none;" id="errorName"></span>
                        </div>
                    </div>
                    <!-- value Ends -->
                </li>
                <li>
                    <div class="lbl">Description<span></span></div>
                    <div class="val">
                        <textarea id='txtDescription' name='txtDescription'></textarea>
                        <div class="validation">
                            <span style="display:none;" id="errorDescription"></span>
                        </div>
                    </div>
                    <!-- value Ends -->
                </li>
                <li>
                    <div class="lbl">Insurance Company Name<span>*</span></div>
                    <div class="val">
                        <input autocomplete="off" type="text" class="input " placeholder="Enter Insurance Company Name" id="txtInsuranceCompanyName" name="txtInsuranceCompanyName" onkeypress="hideErrorMessage('errorInsuranceCompanyName');" value='' />
                        <div class="validation">
                            <span style="display:none;" id="errorInsuranceCompanyName"></span>
                        </div>
                    </div>
                    <!-- value Ends -->
                </li>
                <li>
                    <div class="lbl">Policy Number<span></span></div>
                    <div class="val">
                        <input autocomplete="off" type="text" class="input " placeholder="Enter Policy Number" id="txtPolicyNumber" name="txtPolicyNumber" value='' />
                        <div class="validation">
                            <span style="display:none;"></span>
                        </div>
                    </div>
                    <!-- value Ends -->
                </li>
                <li>
                    <div class="lbl">Contact Person Name<span></span></div>
                    <div class="val">
                        <input autocomplete="off" type="text" class="input " placeholder="Enter Contact Person Name" id="txtContactPersonName" name="txtContactPersonName" value='' />
                        <div class="validation">
                            <span style="display:none;"></span>
                        </div>
                    </div>
                    <!-- value Ends -->
                </li>
                <li>
                    <div class="lbl">Phone Number<span></span></div>
                    <div class="val">
                        <input autocomplete="off" type="text" class="input " placeholder="Enter Phone Number" id="txtPhoneNumber" name="txtPhoneNumber" value='' />
                        <div class="validation">
                            <span style="display:none;"></span>
                        </div>
                    </div>
                    <!-- value Ends -->
                </li>
                <li>
                    <div class="lbl">Email Id<span></span></div>
                    <div class="val">
                        <input autocomplete="off" type="email" class="input " placeholder="Enter Email Id" id="txtEmailId" name="txtEmailId" onkeypress="hideErrorMessage('errorEmail');" value='' />
                        <div class="validation">
                            <span style="display:none;" id="errorEmail"></span>
                        </div>
                    </div>
                    <!-- value Ends -->
                </li>
                <li>
                    <div class="lbl">Coverage Information<span></span></div>
                    <div class="val">
                        <textarea id='txtCoverageInfo' name='txtCoverageInfo'></textarea>
                        <div class="validation">
                            <span style="display:none;"></span>
                        </div>
                    </div>
                    <!-- value Ends -->
                </li>
                <li>
                    <div class="lbl">Limit<span></span></div>
                    <div class="val">
                        <input autocomplete="off" type="text" class="input " placeholder="Enter Limit" id="txtLimit" name="txtLimit" value='' />
                        <div class="validation">
                            <span style="display:none;"></span>
                        </div>
                    </div>
                    <!-- value Ends -->
                </li>
                <li>
                    <div class="lbl">Premium Amount<span></span></div>
                    <div class="val">
                        <input autocomplete="off" type="text" class="input " placeholder="Enter Premium Amount" id="txtPremiumAmnt" name="txtPremiumAmnt" value='' />
                        <div class="validation">
                            <span style="display:none;"></span>
                        </div>
                    </div>
                    <!-- value Ends -->
                </li>
                <li>
                    <div class="lbl">Deductible Amount<span></span></div>
                    <div class="val">
                        <input autocomplete="off" type="text" class="input " placeholder="Enter Deductible Amount" id="txtDeductibleAmnt" name="txtDeductibleAmnt" value='' />
                        <div class="validation">
                            <span style="display:none;"></span>
                        </div>
                    </div>
                    <!-- value Ends -->
                </li>
                <li>
                    <div class="lbl">Start Date<span>*</span></div>
                    <div class="val">
                        <input autocomplete="off" type="text" class="input " placeholder="Enter Start Date" id="txtStartDate" name="txtStartDate" onkeypress="hideErrorMessage('errorStartDate');" value='' />
                        <div class="validation">
                            <span style="display:none;" id='errorStartDate'></span>
                        </div>
                    </div>
                    <!-- value Ends -->
                </li>
                <li>
                    <div class="lbl">End Date<span></span></div>
                    <div class="val">
                        <input autocomplete="off" type="text" class="input " placeholder="Enter End Date" id="txtEndDate" name="txtEndDate" value='' />
                        <div class="validation">
                            <span style="display:none;" ></span>
                        </div>
                    </div>
                    <!-- value Ends -->
                </li>
                <li>
                    <div class="lbl">Require Renewal<span></span></div>
                    <div class="val">		
                        <label class="radioBtn" style="display:inline-block; margin-right:20px;">
                            <input type="radio" name="requireRenewel" id="renewalYes" value="1" <?php echo $categoryTypeMain; ?> />
                                <span class="checkmark"></span>Yes
                        </label>
							
                        <label class="radioBtn" style="display:inline-block;">
                            <input type="radio" name="requireRenewel" id="renewalNo" value="0" <?php echo $categoryTypeSub; ?> />
                                <span class="checkmark"></span> No
                        </label>
                    </div>
                </li>
                <li>
                    <div class="lbl">Renewal Duration<span></span></div>
                    <div class="val">
                        <div class="inputLoaderWrapper" style="width:40%;">
                            <select class="input" id="txtRenewalDuration" name ="txtRenewalDuration"> 
                                <option id="" value="">Select Renewal Duration</option>
                                <?php 
                                    foreach($renewalDurationArray as $key=>$value)
                                    {
                                        $displayValue = str_replace('_',' ', $value);
                                ?>
                                <option id="<?php echo $key; ?>" value="<?php echo $value; ?>"><?php echo ucfirst($displayValue); ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="validation">
                            <span style="display:none;"></span> 
                        </div>
                    </div>
                    <!-- value Ends -->
                </li>
                
                <!--File Attachement -->
                <div class="fileAttachments">
	
                    <!--
                            =================
                            Drag & Drop Panel
                            =================
                    -->
                    <div class="addPhotoPanel">

                        <!-- Drag & Drop -->
                        <div class="dragPhoto" id="drop-area" for="uploadFile">			   <p>
                                <strong>
                                    <i class="fa fa-camera"></i> 
                                    <i class="fa fa-file-text-o"></i>
                                </strong>
                                Drag &amp; drop your<br> files here
                            </p>
                                <span>Or</span>
                                <label for="uploadFile">Browse</label>
                                <input type="file" id="uploadFile" name="uploadFile[]" onchange="previewImage(this);" multiple />
                        </div>

                        <!-- Photo Info -->
                        <div class="photoInfo">
                            <h5>Image specification</h5>
                            <p>Image size must be less than 10 MB</p>
                            <p>Image dimensions must be more than 400 X 400 px</p>
                            <p>Image format must be JPG, JPEG, PNG</p>
                            <p>Make sure the image is not blurred</p>

                            <div class="validation" id="validationMsgDiv">
                            </div>
                        </div>
                    </div>
                    <!--
                            =================
                            Display image
                            =================
                    -->
                    <?php
                    $display = 'style="display:none;"';
                    if($numROws > 0)
                    {
                        $display = 'style="display:block;"';
                    }
                    ?>
                    <div class="uploadedImages" id="filePreviewDiv" <?php echo $display;?>>
                    <!--<p>Drag & Drop  to reorder</p>-->
                    <ul id="sortable" >
                    <?php
                    $count = 1;
                    if($numROws > 0)
                    {
                        for($j = 0; $j < count($rsAuditImageData); $j++)
                        {
                            $fetchAuditId = $rsAuditImageData[$j]['audit_id'];
                            $mainImageCommaSeprate = $rsAuditImageData[$j]['user_answer'];

                            if($mainImageCommaSeprate != '')
                            {
                                $explodeAnswers = explode('#',$mainImageCommaSeprate);
                                $mainImageExplode = explode(',',$explodeAnswers[1]);
                                $fetchDisplayOrder = explode(',',$explodeAnswers[0]);

                                $tempCount = 1;

                                //for($i=0;$i<count($mainImageExplode);$i++)
                                foreach($nameAndDisplayOrderArray as $nameAndDisplayOrderData)
                                {
                                    $imageName = $nameAndDisplayOrderData;
                                    $extension = end(explode('.', $imageName));
                                    //echo "Extension >> ".$extension;

                                    if($extension == 'jpg' || $extension == 'png' || $extension == 'jpeg')
                                    {
                                        $imagePath = $codeUrl.'uploads/audit/'.$fetchAuditId.'/'.$imageName;
                                    }
                                    else
                                    {
                                        $imagePath = $codeUrl.'images/doc_icon.png';
                                    }
                                    ?>
                                    <li id="<?php echo $count ; ?>">
                                        <a href="javascript:;" class="remove" onclick="deleteImageInEditMode('<?php echo $count; ?>','<?php echo $imageName; ?>')"><i class="fa fa-close"></i></a>
                                        <div class="thumb">
                                            <img src="<?php echo $imagePath; ?>" width="100px" height="100px" /><br/>  
                                            <span><?php echo $imageName; ?></span>
                                        </div>
                                        <div class="number"><label><?php echo $count ; ?></label>	</div>
                                    </li>
                                    <?php
                                        $count++;
                                        $tempCount++;
                                }
                            }
                        } // End for loop
                    }
                    ?>
                    </ul>
                    </div>

                    <input type="hidden" id="hdnValidImage" name="hdnValidImage" value="<?php echo $imageNameInEditMode; ?>">
                    <input type="hidden" id="hdnDisplayOrder" name="hdnDisplayOrder" value="<?php echo $displayOrder;?>">
                    <input type="hidden" id="hdnDeletedImageName" name="hdnDeletedImageName" value="">
                    <input type="hidden" id="hdnTotalImage" name="hdnTotalImage" value="0">
			
                </div>
            </ul>
        </div>

        <div class="submitBtn submit" style="display:inline-block; margin-left:calc(250px + 20px);">
            <div class="btnLoader" id="addInsuranceLoader" style="display:none;">
                <span class="spinLoader"></span>
            </div>
            <!-- hiddens -->
            <input type="hidden" name="action" value="<?php echo $formAction ?>">
            <input type="hidden" id="hdnInsuranceId" name="hdnInsuranceId" value="<?php echo $hdnInsuranceId; ?>">

            <input value="<?php echo ucfirst($formAction); ?> Insurance" class="btn" type="button" id="addInsurance" name="addInsurance"  onclick="return addInsuranceValidation();">
        </div>

    </form>
    <!-- form end -->
    <script type="text/javascript">
    var action = '<?php echo $action; ?>';
    var codeUrl = '<?php echo $codeUrl; ?>';
    var tempFileListArray= new Array();
    var formData = new FormData();
    var filesToUpload = [];
    var fileNameArray = new Array();
    var pagesIdArray = new Array();
    var newsCutCounter = 0;
    var isGetPages = false;
    var table = '';
    var imageNameInEditMode = '<?php echo $imageNameInEditMode; ?>';
    
    
    $(document).ready(function()
    {	
        $("#section_form_data").show();
        $("#section_display_form").show();
        $("#section_na_div").hide();
        $('#isSectionNotApplicable').prop('checked', false); // checks it

        $("#sortable").sortable({
                update: function (event, ui)
                {
                    var attr = $(this).sortable('toArray');
                    console.log('attr ::: '+attr);
                    $("#hdnDisplayOrder").val(attr);
                    resetNumber();
                },
        }); // end sortable here
        (function($, window, undefined) {
        var hasOnProgress = ("onprogress" in $.ajaxSettings.xhr());

        if (!hasOnProgress) 
        {
            return;
        }

        var oldXHR = $.ajaxSettings.xhr;
        $.ajaxSettings.xhr = function() {
                        var xhr = oldXHR();
                        if(xhr instanceof window.XMLHttpRequest) {
                                        xhr.addEventListener('progress', this.progress, false);
                        }

                        if(xhr.upload) {
                                        xhr.upload.addEventListener('progress', this.progress, false);
                        }

                        return xhr;
                            };
            })(jQuery, window);
    });
	
	
	
    let dropArea = document.getElementById("drop-area");
    //alert(dropArea);
    ;['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => 
    {
        dropArea.addEventListener(eventName, preventDefaults, false)   
        document.body.addEventListener(eventName, preventDefaults, false)
    })

    // Highlight drop area when item is dragged over it
    ;['dragenter', 'dragover'].forEach(eventName => 
    {
        dropArea.addEventListener(eventName, highlight, false)
    })

    ;['dragleave', 'drop'].forEach(eventName => 
    {
        dropArea.addEventListener(eventName, unhighlight, false)
    })
    // Handle dropped files
    dropArea.addEventListener('drop', handleDrop, false)

    function resetNumber()
    {
        $('#sortable li label').each(function (i) {
                var numbering = i + 1;
                $(this).text(numbering);
        });
    }

    function preventDefaults (e) 
    {
        e.preventDefault()
        e.stopPropagation()
    }

    function highlight(e) 
    {
        dropArea.classList.add('highlight')
    }

    function unhighlight(e) 
    {
        dropArea.classList.remove('active')
    }
    
    function handleDrop(e) 
    {
        var dt = e.dataTransfer
        var tempFiles = dt.files;
        //createFormData(tempFiles);	//for upload through ajax
		
        ([...tempFiles]).forEach(setFileHiddenValue)
        
        //$("#imageGallery").val(tempFiles);		
        //$("#drag_files").val(tempFiles);
        previewImage(dt)
    }
    function setFileHiddenValue(file)
    {
        //alert (file);
        tempFileListArray.push(file);
    }
    //Old Code (Works fine only HS problem)
    var imageCounter = 1;
    var docCounter = 1;
    var fileNameLength = 30;
    function previewImage(input)
    {	
        var files = input.files; 
        var documentFileCounter = 0;
        var timeStempFormData = $.now();
        
        //For images variables
        var liLableCount = $("#sortable li").length;
        var len = files.length;
        //var maxSizeInMb = 5242880;// 5 Mb //2097152(2MB);
        var maxSizeInMb = 10000000 //10 mb
        //var maxSizeInMb = 8388608;// 8 Mb //2097152(2MB);
        var minWidth = 400;
        var minHeight = 400;
        
       
        var i = 0;
        var imageErrorCount = 0;
        var imageSizeSum = 0;
        
        var imageTypeArray = ['image/jpg', 'image/jpeg', 'image/png', 'image/gif'];
        $.each(files, function(i, file) 
        {
            var fileType = file.type;

            //This code is use when image/doc selected and then bind into form data
            var actualFileName = file.name;

            var imageExtension = actualFileName.split(".").pop().toLowerCase(); //get last element after split

            var tempImageNameFormData = actualFileName.split(".");
            var newFileExtension = tempImageNameFormData[1];
           
            var fileNameOriginalFormData = tempImageNameFormData[0];	//without extension

            var newFileNameFormData = fileNameOriginalFormData.replaceAll(",", "-");
            if(newFileNameFormData.length > fileNameLength) 
                newFileNameFormData = newFileNameFormData.substring(0,fileNameLength);
            
            var newFileNameWithExtension = newFileNameFormData+'.'+newFileExtension;

            //Check if same image name then add incremental count in file name
            var alreadyStoreNameInHdnForm = $("#hdnValidImage").val();
            //alert("array >> "+alreadyStoreNameInHdnForm+" newFileName >> "+newFileNameFormData);
            var alreadyStoreNameInHdnFormArray = alreadyStoreNameInHdnForm.split(',');
            
            //alert("already >> "+alreadyStoreNameInHdnFormArray);
            //alert("New Name >> "+newFileNameWithExtension);
            var checkImageNameExistForm = alreadyStoreNameInHdnFormArray.includes(newFileNameWithExtension);
            //alert('checkImageNameExistForm >> '+checkImageNameExistForm);
            if(checkImageNameExistForm == true)
            {
                var imageNameFormData = newFileNameFormData+'_'+timeStempFormData+'.'+imageExtension;
            }
            else
            {
                var imageNameFormData = newFileNameFormData+'.'+imageExtension;
            }
            
            
            //This code is for Documents only
            var liLableCountForDoc = $("#sortable li").length;
            
            if(!imageTypeArray.includes(fileType))
            {
                //when upload document codee need then comment below line and uncomment if section
                $("#validationMsgDiv").text("Please Upload .jpg, .jpeg, .png Extentions file only");
            } 
            //Push Data for Images or Documents
            formData.append('uploadFile[]', file, imageNameFormData);
        });
         //This code is for Images only
        (function readFile(n) 
        {
            var reader = new FileReader();
            var f = files[n];
            var displayOrder = $("#hdnDisplayOrder").val();
            
            reader.onload = function(e) 
            {
                //alert("Type Array >> "+imageTypeArray+" type >> "+f.type);
                if(imageTypeArray.includes(f.type))
                {		
                    var image = new Image();
                    //Set the Base64 string return from FileReader as source.
                    image.src = e.target.result;
                    image.onload = function () 
                    {
                        var height = this.height;
                        var width = this.width;
                        var imageSize = f.size;
                        var imageExtension = f.type;
                        //alert("height >> "+height+" width >> "+width+" minHeight >> "+minHeight+" minWidth >> "+minWidth);
                        if(imageExtension == 'image/jpg' || imageExtension == 'image/jpeg' || imageExtension == 'image/png')
                        {
                            if(height >= minHeight && width >= minWidth && imageSize <= maxSizeInMb)
                            {
                                $("#filePreviewDiv").show();
                                //var imageName = f.name;
                                var getImageName = f.name;
                                var imageExtensionReader = getImageName.split(".").pop().toLowerCase();
                                var tempImageName = getImageName.split(".");
                                var fileNameOriginal = tempImageName[0];	//without extension
                                var fileExtensionOriginal = tempImageName[1];

                                var newFileName = fileNameOriginal.replaceAll(",", "-");
                                if(newFileName.length > fileNameLength) 
                                    newFileName = newFileName.substring(0,fileNameLength);

                                var newFileNameWithExtensionOriginal = newFileName+'.'+fileExtensionOriginal;
                                //Check if same image name then add incremental count in file name
                                var alreadyStoreNameInHdn = $("#hdnValidImage").val();
                                //alert("array >> "+alreadyStoreNameInHdn+" newFileName >> "+newFileName);
                                var alreadyStoreNameInHdnArray = alreadyStoreNameInHdn.split(',');
                                var checkImageNameExist = alreadyStoreNameInHdnArray.includes(newFileNameWithExtensionOriginal);
                                
                                if(checkImageNameExist == true)
                                {
                                    var imageName = newFileName+'_'+timeStempFormData+'.'+imageExtensionReader;
                                }
                                else
                                {
                                    var imageName = newFileName+'.'+imageExtensionReader;
                                }


                                //console.log('Final image name >> '+imageName);
                                filesToUpload.push({
                                        id: imageCounter,
                                        file: imageName
                                        });
                                         
                               // alert("imageCounter >> "+imageCounter);
                                
                                var isValuePresent = fileNameArray.includes(imageNameInEditMode);
                                if(isValuePresent == false)
                                {
                                    if(imageNameInEditMode != '')
                                        fileNameArray.push(imageNameInEditMode);
                                }

                                fileNameArray.push(imageName);
                                //alert("After filename array >> "+fileNameArray);
                                
                                var imageArrayLength = fileNameArray.length;
                                //alert('here >> '+imageArrayLength);
                                
                                $("#hdnValidImage").val(fileNameArray.toString());
                                liLableCount++;
                                var html = '<li id="newLi_'+ imageArrayLength +'" isnew="true">';
                                
                                html += '<a href="javascript:;" class="remove" onclick="deleteImage('+ imageArrayLength +')"><i class="fa fa-close"></i></a>';
                                html += '<div class="thumb"><img src="'+e.target.result+'" alt="your image" width="100px" height="100px"/></div>';
                                html += '<div class="number"><label>'+liLableCount+'</label></div>';
                                html += '<span>'+imageName+'</span>';
                                html += '</li>';
                                $("#sortable").append(html);


                                if(displayOrder == "")
                                {
                                    $("#hdnDisplayOrder").val("newLi_"+ imageCounter);
                                }	
                                else
                                {
                                    $("#hdnDisplayOrder").val(displayOrder+","+"newLi_"+ imageCounter);
                                }
                                imageCounter++;
                                
                                imageSizeSum = parseInt(imageSize) + parseInt(imageSizeSum);
                            }   //if ends here
                            else
                            {
                                imageErrorCount++;
                            }   //else ends here
                            
                            $("#hdnTotalImage").val(imageCounter - 1);


                            if (n < len -1) 
                            {
                                //alert("if >> "+isError);
                                readFile(++n);
                            }	
                            else
                            {
                                if(imageErrorCount > 0)
                                {
                                    $("#validationMsgDiv").text('Sorry, '+imageErrorCount+' image(s) dimensions are not in valid range.');
                                }
                            }
                        }
                        else
                        {
                            $("#validationMsgDiv").text("Please Upload .jpg, .jpeg, .png Extentions file only");
                        }
                    }
                }	// file type checking ends here	
            };
            
            //console.log('final all image size in bytes :: '+imageSizeSum+' final all image size in MB :: '+formatBytes(imageSizeSum));
            reader.readAsDataURL(f); // `f` : current `File` object


        }(i)); // `i` : `n` within immediately invoked function expression
		if($("#sortable li").length == 0)
			$("#filePreviewDiv").hide();
    } // End previewImage()*/
    function createImageFormData(image, timeStempFormData)
    {
        $.each(image, function(i, file) 
        {
            var fileType = file.type;

            //This code is use when image/doc selected and then bind into form data
            var actualFileName = file.name;
            var imageExtension = actualFileName.split(".").pop().toLowerCase(); ; //get last element after split
            var tempImageNameFormData = actualFileName.split(".");
            var fileNameOriginalFormData = tempImageNameFormData[0];	//without extension

            var newFileNameFormData = fileNameOriginalFormData.replaceAll(",", "-");
            if(newFileNameFormData.length > fileNameLength) 
                newFileNameFormData = newFileNameFormData.substring(0,fileNameLength);


            //Check if same image name then add incremental count in file name
            var alreadyStoreNameInHdnForm = $("#hdnValidImage").val();
            //alert("array >> "+alreadyStoreNameInHdn+" newFileName >> "+newFileName);
            var checkImageNameExistForm = alreadyStoreNameInHdnForm.includes(newFileNameFormData);
            
            var imageNameFormData = newFileNameFormData+'.'+imageExtension;
            
            //Push Data for Images or Documents
            formData.append('uploadFile[]', file, imageNameFormData);
        });
		
    } // En createFormData();
    
    function formatBytes(a,b=2){if(0===a)return"0 Bytes";const c=0>b?0:b,d=Math.floor(Math.log(a)/Math.log(1024));return parseFloat((a/Math.pow(1024,d)).toFixed(c))+" "+["Bytes","KB","MB","GB","TB","PB","EB","ZB","YB"][d]}
    
    function deleteImage(liId,from)
    {
        if (confirm("Are you sure you want to delete this image?"))
        {
            if($("#newLi_"+liId).attr('isnew'))
            {
                for (var i = 0; i < filesToUpload.length; ++i) 
                {
                    if (filesToUpload[i].id === liId)
                    {
                        var imageName = filesToUpload[i].file;
                        var index = fileNameArray.indexOf(imageName);
                        fileNameArray.splice(index, 1);
                        $("#hdnValidImage").val(fileNameArray.toString());
                        filesToUpload.splice(i, 1);
                    }
                } // end for loop
                $("#hdnTotalImage").val(filesToUpload.length);	
                
                $("#newLi_"+liId).remove(); // remove image
            }   //if close here
            var order = $("#sortable").sortable("toArray");
            $("#hdnDisplayOrder").val(order);
            if($("#sortable li").length == 0)
            {
                $("#filePreviewDiv").hide();
            }
        } // End confirm alert if
        resetNumber();
    } // end funtion here
    function deleteImageInEditMode(imageNumber,imageName)
    {
        if(confirm("Are you sure you want to delete this image?"))
        {
            var deletedImageName  = $("#hdnDeletedImageName").val();
            if(deletedImageName == '')
            {
                $("#hdnDeletedImageName").val(imageName);
            }
            else
            {
                $("#hdnDeletedImageName").val(deletedImageName+','+imageName);
            }
            
            $("#"+imageNumber).remove();
            
            var editValidImageName = $("#hdnValidImage").val();
            var checkComma = editValidImageName.includes(',');
            if(checkComma)
            {
                var editImageNameArray = editValidImageName.split(',');
                /*Remove data from specific array*/
                editImageNameArray.splice(editImageNameArray.indexOf(imageName.toString()), 1);
                $('#hdnValidImage').val(editImageNameArray.toString());
                imageNameInEditMode = editImageNameArray.toString();
            }
            else
            {
                $('#hdnValidImage').val('');
                imageNameInEditMode = '';
            }
            
            var order = $("#sortable").sortable("toArray");
            $("#hdnDisplayOrder").val(order);
        }// End if here
        resetNumber();
    } // End deleteImageInEditMode()
    
    </script>
</section>
<!-- 
	=============== 
	Page Content Ends
	===============
-->

<!-- 
	=============== 
	Footer Start
	===============
-->
<?php require_once ('inc/php/footer.php'); ?>
</body>
</html>