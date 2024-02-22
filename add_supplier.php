<!-- 
    Created by Khyati panchal 23 Feb 2022
    file name : add_supplier.php
 -->

<?php
    require_once 'inc/php/config.php';
    require_once 'inc/dal/baseclasses/class.database.php';
    require_once 'inc/dal/supplier_master.child.php';
    require_once 'inc/dal/country.child.php';
    require_once 'inc/dal/state.child.php';
    require_once 'inc/dal/city.child.php';
    require_once 'inc/dal/settings.child.php';
    require_once 'inc/php/functions.php';

    $codeUrl = getCodeUrl(); // call function and get code url
    $formAction = 'add'; // action = add
    
    
    $countryId = 101; // india
    /*Fetch country value from Database*/
    $countryData = getCountryData();
    
    

    if((isset($_REQUEST['action']) && !empty($_REQUEST['action'])) && isset($_REQUEST['id']) && !empty($_REQUEST['id']))
    {
        $formAction = $_REQUEST['action']; // action = edit
        $supplierId = $_REQUEST['id'];
        $hdnSupplierId = $supplierId; // update $hdnCategoryId
    } 
    
function getCountryData()
{
    /*Fetch country value from Database*/
    $objcountryChild = new countryChild();
    $objcountryChild->selectColumn = 'id,name';
    $objcountryChild->condition = 'id>0';
    $countryRS = $objcountryChild->selectByColumn();
    
    if(count($countryRS) > 0)
    {
        $countryDataArray = $countryRS;
    }
    else
    {
        $countryDataArray = array();
    }
    return $countryDataArray;
}
    
?>

<!doctype html>
<html class="no-js">

<head>
    <title><?php echo ucfirst($formAction); ?> Supplier</title>
    <?php require_once ('inc/php/head.php'); ?>

    <script type="text/javascript">
        var codeUrl = '<?php echo $codeUrl; ?>'; // code url
        var formAction = '<?php echo $formAction;?>'; // action = add, edit
        
        // onload start
        $(document).ready(function() 
        {
            var countryId = '<?php echo $countryId;?>';
            fetchState(countryId,'');
            
            if(formAction == 'edit')
            {
                var supplierId = '<?php echo $supplierId; ?>'; 
                getSupplierDataThroughAjax(supplierId); 
            }
        });
        // onload end
        
        /********** Start FETCH STATE ****************/
        function fetchState(countryId,stateId)
        {	
            $.ajax({
                type : 'POST',
                url : 'api/fetch_state.php?countryId='+countryId+'&stateId='+stateId,
                timeout : 30000,
                cache : false,
                async : true,
                beforeSend : function()
                {
                    $("#stateLoader").show();
                },
                success :function(data)
                {
                    $("#txtState").html(data);
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
                complete:function()
                {
                    $("#stateLoader").hide();
                }
            });
        }
        /********** END FETCH STATE ****************/

        /********* FTECH CITY USING AJAX **********/
        function fetchCity(stateId,cityId)
        {	
            //alert ("Fetch City >> StateID: "+stateId+" City ID: "+cityId);
            $.ajax({
            type : 'POST',
            url : 'api/fetch_city.php?stateId='+stateId+'&cityId='+cityId,
            timeout : 30000,
            cache : false,
            async : true,
            beforeSend : function()
            {
                $("#cityLoader").show();
            },
            success :function(data)
            {
                $("#txtCity").html(data);
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
            complete:function()
            {
                $("#cityLoader").hide();
            }
        });
        }
        /*********END FTECH CITY USING AJAX **********/    

        // function start here
        function getSupplierDataThroughAjax(supplierId)
        {
            var finalUrl = codeUrl + "api/supplier_master.php" ; // final url
            // ajax start
            $.ajax({
                    type: "POST",
                    url: finalUrl,
                    data : "action=get_supplier_data&supplier_id="+supplierId,
                    cache: true,
                    async: true,
                    timeout: 30000,
                    dataType : "json",
                    beforeSend: function() 
                    {
                        $("#addSupplierLoader").show(); // start loader
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
                            //console.log("data :: " + JSON.stringify(data));

                            if(data)
                            {
                                var supplierType = data[0].categoryType; 
                                var supplierName = data[0].categoryName; 
                                var address = data[0].address; 
                                var contactPersonName = data[0].contactPersonName;
                                var mobileNumber = data[0].mobileNumber; 
                                var emailId = data[0].emailId; 
                                var otherContactNumbers = data[0].otherContactNumbers;
                                var countryId = data[0].countryId; 
                                var stateId = data[0].stateId; 
                                var cityId = data[0].cityId; 
                                fetchState(countryId,stateId);
                                fetchCity(stateId,cityId);
                                
                                $("#txtTypeId").val(supplierType);
                                $("#txtName").val(supplierName);
                                $("#txtAddress").text(address);
                                $("#txtCountry").val(countryId);
                                $("#txtState").val(stateId);
                                $("#txtCity").val(cityId);
                                $("#txtContactPersonName").val(contactPersonName);
                                $("#txtMobileNumber").val(mobileNumber);
                                $("#txtEmailId").val(emailId);
                                $("#txtContactNumbers").val(otherContactNumbers);
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
                        $("#addSupplierLoader").hide(); // hide loader
                    }
            });
    // ajax end here
        }
        // function end here : getCategoryDataThroughAjax
        
        // function start here
        function addSupplierValidation() 
        {
            var isFormValidate = true; // set flag
            var txtType = $("#txtTypeId").val();
            var txtName = $('#txtName').val();
            var txtEmail = $("#txtEmailId").val();
            var txtMobileNumber = $("#txtMobileNumber").val();
            var txtContactNumbers = $("#txtContactNumbers").val();
            
            var emailId = /\S+@\S+\.\S+/;
            var numeric = /^[0-9.]{1,10}$/;
            var contactCheckNumeric = /^\d{1,10}((\s)|(,(\s|\S)\d{1,10}))*$/;
            
            if(txtType == ''|| txtType == null || txtType == undefined) 
            {
                $("#errorSelectType").text('Select type');
                $("#errorSelectType").show();
                isFormValidate = false;            
            }
            if (txtName == '' || txtName == null || txtName == undefined) 
            {
                $("#errorName").text('Name is required');
                $("#errorName").show();
                isFormValidate = false;
            }
            if(txtMobileNumber != '')
            {
                if(!txtMobileNumber.match(numeric))
                {
                    $("#errormobileNumber").text('');
                    $("#errormobileNumber").text('Please enter valid mobile number');
                    $("#errormobileNumber").show();
                    isFormValidate =  false;
                }
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
            if(txtContactNumbers != '')
            {
                if(!txtContactNumbers.match(contactCheckNumeric))
                {
                    $("#errorContactNumbers").text('');
                    $("#errorContactNumbers").text('Only comma seperated numeric value allowed');
                    $("#errorContactNumbers").show();
                    isFormValidate=false;
                }
            }

            navigateToErrorMessage(); // call function for navigate user to specific error message location

            if (isFormValidate) 
            {
                $("#addCategoryLoader").show(); // show loader
                submitDataThroughAjax();
            } 
            else 
            {
                $("#addCategoryLoader").hide(); // hide loader
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
            var allRequestData = $('#addSupplierForm').serializeArray();
            
            // alert("allRequestData >> "+JSON.stringify(allRequestData));

            $.each(allRequestData,function(key,input)
            {
                // alert("name >> "+input.name+" value >> "+input.value);
                formData.append(input.name,input.value);
            });
            
            //alert("formData >> "+JSON.stringify(formData));

            var finalUrl = codeUrl+"api/supplier_master.php"; // finalUrl
            
            $.ajax({
                type: "POST",
                url: finalUrl,
                data: formData,
                dataType: "json",
                contentType:false,
                cache: false,
                processData: false,
                timeout : 30000, 
                beforeSend : function()
                {
                    $("#addSupplierLoader").show(); // show loader
                },
                success: function(response)
                {
                    var status = response.header.status; // get status code
                    var message = response.header.message; // get message

                    console.log("status ::"+status);
                    console.log("message ::"+message);
                    
                    if(status == 200)
                    {
                        window.location.href="supplier.php"; // redirect on page
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
                    $("#addSupplierLoader").hide(); // hide loader
                }
            });
            return false;
        }
        // function ends here : submitDataThroughAjax

        // function start here
        function hideErrorMessage(id) 
        {
            //console.log("id >> "+id);
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
    <h1><?php echo ucfirst($formAction); ?> Supplier</h1>
    <a href="supplier.php" class="back">Back</a>
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
	
	<!-- Form is here -->
        <?php
        require_once 'supplier_form.php';
        ?>

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