<!-- 
    Created by Khyati panchal 23 Feb 2022
    file name : add_category.php
 -->

<?php
    require_once 'inc/php/config.php';
    require_once 'inc/dal/baseclasses/class.database.php';
    require_once 'inc/dal/category_master.child.php';
    require_once 'inc/dal/settings.child.php';
    require_once 'inc/php/functions.php';

    $codeUrl = getCodeUrl(); // call function and get code url
    $formAction = 'add'; // action = add
    
    if((isset($_REQUEST['action']) && !empty($_REQUEST['action'])) && isset($_REQUEST['id']) && !empty($_REQUEST['id']))
    {
        $formAction = $_REQUEST['action']; // action = edit
        $categoryId = $_REQUEST['id'];
        $hdnCategoryId = $categoryId; // update $hdnCategoryId
    }   
    
    $parentCategory = getAllMainCategory();
    $countOfParentCateogry = count($parentCategory);

function getAllMainCategory()
{ 
    $paramArrayForParentCategory = array(0, 0, 'active');
    $objParentCategory = new categoryMasterChild();
    $objParentCategory->selectColumn = 'id, name';
    $objParentCategory->param = $paramArrayForParentCategory;
    $objParentCategory->condition = 'parent_category_id = ? AND is_deleted = ? AND status = ? ORDER BY name asc';
    $rsParentCategory = $objParentCategory->selectByColumn();
    
    if(count($rsParentCategory > 0))
    {
        $parentCategoryArray = $rsParentCategory;
    }
    else
    {
        $parentCategoryArray = array();
    }
    return $parentCategoryArray;
}
?>

<!doctype html>
<html class="no-js">

<head>
    <title><?php echo ucfirst($formAction); ?> Category</title>
    <?php require_once ('inc/php/head.php'); ?>

    <script type="text/javascript">
        var codeUrl = '<?php echo $codeUrl; ?>'; // code url
        var formAction = '<?php echo $formAction;?>'; // action = add, edit
        var countOfParentCategory = '<?php echo $countOfParentCateogry; ?>';
        
        // onload start
        $(document).ready(function() 
        {
            if(formAction == 'edit')
            {
                var categoryId = '<?php echo $categoryId; ?>'; 
                getCategoryDataThroughAjax(categoryId); 
            }
        });
        // onload end

        // function start here
        function getCategoryDataThroughAjax(categoryId)
        {
            var finalUrl = codeUrl + "api/category_master.php" ; // final url
            // ajax start
            $.ajax({
                    type: "POST",
                    url: finalUrl,
                    data : "action=get_category_data&category_id="+categoryId,
                    cache: true,
                    async: true,
                    timeout: 30000,
                    dataType : "json",
                    beforeSend: function() 
                    {
                        $("#addCategoryLoader").show(); // start loader
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
                                var categoryName = data[0].categoryName; 
                                var parentCategoryId = data[0].parentCategoryId; 

                                $("#txtCategoryName").val(categoryName);
                                if(parentCategoryId == 0)
                                {
                                    $("#cattype").attr('checked', 'checked');
                                }
                                else
                                {
                                    $("#subcattype").attr('checked', 'checked');
                                    $('#selectCategoryLi').show();
                                    $("#categoryId").val(parentCategoryId);
                                }   
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
                        $("#addCategoryLoader").hide(); // hide loader
                    }
            });
    // ajax end here
        }
        // function end here : getCategoryDataThroughAjax
        
        // function start here
        function addCategoryValidation() 
        {
            var isFormValidate = true; // set flag
            var txtCategoryType = $('input[name="categoryType"]:checked').val();
            var txtCategoryName = $("#txtCategoryName").val();
            
            if(txtCategoryType == 'subcategory')
            {
                var txtSelectedCategory = $('#categoryId').val();
                if(txtSelectedCategory == ''|| txtSelectedCategory == null || txtSelectedCategory == undefined) 
                {
                    $("#errorSelectCategory").text('Select category');
                    $("#errorSelectCategory").show();
                    isFormValidate = false;            
                }
            }
            
            if (txtCategoryName == '' || txtCategoryName == null || txtCategoryName == undefined) 
            {
                $("#errorCategoryName").text('Category name is required');
                $("#errorCategoryName").show();
                isFormValidate = false;
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
            var allRequestData = $('#addCategoryForm').serializeArray();
            
            // alert("allRequestData >> "+JSON.stringify(allRequestData));

            $.each(allRequestData,function(key,input)
            {
                // alert("name >> "+input.name+" value >> "+input.value);
                formData.append(input.name,input.value);
            });
            
            //alert("formData >> "+JSON.stringify(formData));

            var finalUrl = codeUrl+"api/category_master.php"; // finalUrl
            
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
                    $("#addCategoryLoader").show(); // show loader
                },
                success: function(response)
                {
                    var status = response.header.status; // get status code
                    var message = response.header.message; // get message

                    console.log("status ::"+status);
                    console.log("message ::"+message);
                    
                    if(status == 200)
                    {
                        window.location.href="category.php"; // redirect on page
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
                    $("#addCategoryLoader").hide(); // hide loader
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
        
        function subcategoryShowHide(type)
        {
            if(type == 'sub')
            {
                if ($("input[name='categoryType']").is(':checked'))
                {
                    $("#selectCategoryLi").hide();
                    if(countOfParentCategory == 0)
                    {
                        $("#errorMessageDiv").show();
                        $("#errorMessageLableId").text('There is no parent category. Please enter parent category first !!');
                    }
                    else
                    {
                        $("#errorMessageDiv").hide();
                        $("#errorMessageLableId").text('');
                        $("#selectCategoryLi").show();
                    }
                }
                else
                {
                    $("#errorMessageDiv").hide();
                    $("#errorMessageLableId").text('');
                    $("#selectCategoryLi").hide();
                }
            }
            else
            {
                $("#errorMessageDiv").hide();
                $("#errorMessageLableId").text('');
                $("#selectCategoryLi").hide();
            }
        }
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
    <h1><?php echo ucfirst($formAction); ?> Category</h1>
    <a href="category.php" class="back">Back</a>
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
	
	<!-- form stat -->	
    <form id="addCategoryForm" name="addCategoryForm" method="post">
        <div class="card">
            <ul class="form">
                <li>
                    <div class="lbl">Category Name<span>*</span></div>
                    <div class="val">
                        <input autocomplete="off" type="text" class="input " placeholder="Enter Category Name" id="txtCategoryName" name="txtCategoryName" onkeypress="hideErrorMessage('errorCategoryName');" value='<?php if(!empty($fetchedCategoryName)){echo $fetchedCategoryName;} ?>' />
                        <div class="validation">
                            <span style="display:none;" id="errorCategoryName"></span>
                        </div>
                    </div>
                    <!-- value Ends -->
                </li>
                <li>
                    <?php 
                    if($formAction == 'add')
                    {
                        $categoryTypeMain = 'checked=checked';
                    }
                    else
                    {
                        if($fetchedParentCategoryName != '')
                        {
                            if($fetchedParentCategoryName == 'category')
                            {
                                $categoryTypeMain = 'checked=checked';
                            }
                            else
                            {
                                $categoryTypeSub = 'checked=checked';
                            }
                        }
                    }
                    ?>
                    <div class="lbl">Category Type<span></span></div>
                    <div class="val">		
                        <label class="radioBtn" style="display:inline-block; margin-right:20px;">
                            <input type="radio" name="categoryType" id="cattype" value="category" onclick="subcategoryShowHide('main');" <?php echo $categoryTypeMain; ?> />
                                <span class="checkmark"></span>Main Category
                        </label>
							
                        <label class="radioBtn" style="display:inline-block;">
                            <input type="radio" name="categoryType" id="subcattype" value="subcategory" onclick="subcategoryShowHide('sub');" <?php echo $categoryTypeSub; ?> />
                                <span class="checkmark"></span> Sub Category
                        </label>
                    </div>
                </li>
                
                <li id="selectCategoryLi" style='display:none;'>
                    <div class="lbl">Select Category<span>*</span></div>
                    <div class="val">
                        <div class="inputLoaderWrapper" style="width:40%;">
                            <select class="input" id="categoryId" name ="categoryId" onchange="hideErrorMessage('errorSelectCategory')"> 
                                <option id="" value="">Select Category</option>
                                <?php 
                                foreach($parentCategory as $parentCategoryData)
                                {
                                    $categoryId = $parentCategoryData['id'];
                                    $categoryName = $parentCategoryData['name'];
                                ?>
                                <option id="<?php echo $categoryId; ?>" value="<?php echo $categoryId; ?>"><?php echo ucfirst($categoryName); ?></option>
                                <?php
                                }
                                ?>
                            </select>
                            <div class="inputLoader" style="display:none;" id="selectRegionLoader">
                                <span class="spinLoader small"></span>
                            </div>
                        </div>
                        <div class="validation">
                            <span style="display:none;" id="errorSelectCategory"></span> 
                        </div>
                    </div>
                    <!-- value Ends -->
                </li>
                
            </ul>
        </div>

        <div class="submitBtn submit" style="display:inline-block; margin-left:calc(250px + 20px);">
            <div class="btnLoader" id="addCategoryLoader" style="display:none;">
                <span class="spinLoader"></span>
            </div>
            <!-- hiddens -->
            <input type="hidden" name="action" value="<?php echo $formAction ?>">
            <input type="hidden" id="hdnCategoryId" name="hdnCategoryId" value="<?php echo $hdnCategoryId; ?>">
            
            <input value="<?php echo ucfirst($formAction); ?> Category" class="btn" type="button" id="addCategory" name="addCategory"  onclick="return addCategoryValidation();">
        </div>

    </form>
    <!-- form end -->	

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