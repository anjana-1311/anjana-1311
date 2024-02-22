<!-- 
    Created by khyati panchal
    file name : category.php
-->

<?php
    require_once 'inc/php/config.php';
    require_once 'inc/dal/baseclasses/class.database.php';
    require_once 'inc/dal/settings.child.php';
    require_once 'inc/dal/category_master.child.php';
    require_once 'inc/php/functions.php';

    $codeUrl = getCodeUrl(); // call function and get code url
    
    $parentCategory = getAllMainCategory();

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
	<title>Manage Category</title>  
	<?php require_once ('inc/php/head.php'); ?>	

	<script type="text/javascript" src="inc/js/datatables.min.js"></script>
    <script type="text/javascript" src="inc/js/md5.min.js"></script>
    <script type="text/javascript" src="inc/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="inc/js/buttons.flash.min.js"></script>
    <script type="text/javascript" src="inc/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="inc/js/buttons.print.min.js"></script>
    <link rel="stylesheet" type="text/css" href="inc/css/datatables.min.css" />
    
	<!-- For Datatable responsive -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.dataTables.min.css" />	
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.7/js/dataTables.responsive.min.js"></script>

	<script type="text/javascript">
        var codeUrl = '<?php echo $codeUrl; ?>'; // code url
		
        var tableName = 'category_master'; // define table name 
        var table = '';       
        
        var isDataFromSearch = false; // flag for serch

        // onload start here
        $(document).ready(function()
        {	
            //getAllRegion(); // ajax call for get all region list

            $("#clearAllButton").attr("disabled","disabled");
            
            //Message from Add category
            <?php if(isset($_SESSION['successMessage']) && !empty($_SESSION['successMessage']))
            {
            ?>
                $("#successMessageLableId").text('');		
                $("#successMessageLableId").text("<?php echo $_SESSION['successMessage']; ?>");
                $("#successMessageDiv").show();
                $("#successMessageDiv").fadeIn().delay(7000).fadeOut();
            <?php
                unset($_SESSION['successMessage']);
            }
            ?>
          
            initDataTable(); // create data table object
        
            // Datatable Page Change detect and scroll page to top
            // user for pagging click
            $('#categoryListingTable').on('page.dt', function ()
            {	
                $("html, body").animate({ scrollTop: 0 }, 600);
            });
		        
            // Search Data
            var flagForSearchValidation = 0;
            $('#searchButton').click( function () 
            {
                // Reset the datatable before search so that start and limit variables will have the default values to get correct dataset from the DB
                var tableId = "#categoryListingTable";
                
                // clear first
                if(table != null)
                {
                    table.clear();
                    table.destroy();
                }

                // 2nd empty html
                $(tableId + " tbody").empty();
                $(tableId + " thead").empty();

                // Show skeleton loader while search
                $("#categoryListingTable").addClass('table-loader').show();
                
                // 3rd reCreate Datatable object
                initDataTable();
                
                isDataFromSearch = true;
                
                $("#categoryListingTable thead").show();
                
                $("#searchLoader").show();
                $("#clearAll").show();
                for(var i=1;i <= 4;i++)
                {
                    var searchValue = $("#searchText_"+i).val();
                    //alert("i :: " + i + ", searchValue :: " + searchValue);
                    if (searchValue != "")
                    {
                        if(searchValue != 'all')
                        {
                            flagForSearchValidation = 1;
                        }
                    }
                        table.column(i).search(searchValue);
                }	
                
                if(flagForSearchValidation == 1)
                {
                    $("#clearAllButton").removeAttr("disabled","disabled");
                    flagForSearchValidation = 0;
                    table.draw('page');
                    $("#searchLoader").hide();
                }
                else
                {
                    $("#clearAllButton").attr("disabled","disabled");
                    $("#errorSearch").show();
                    return false;
                }
                
            });
        }); 
        //onload end here

        // function start here
        function initDataTable()
        {
            table = $('#categoryListingTable').DataTable({
                            responsive: true,						
                            "pagingType": "full_numbers",
                            "serverSide": true, 
                            "dom": '<"top">rt<"bottom"lip><"clear">',				
                            "lengthChange": true,
                            "lengthMenu": [[20,25,30,50,100,200,500, -1], [20,25,30,50,100,200,500, "All"]],
                            // "oLanguage": 
                            // {
                            //     "sEmptyTable": "<div class='noRecord'><img src='images/no_search_result.png' /><label>No search result found!</label><p>We couldn't find any result.</p></div>",
                            //     'loadingRecords': '&nbsp;',
                            //     "sProcessing": "<div style='display:none'><span class='spinLoader small'></span></div>"
                            //     // sProcessing add custom HTML DataTabel Loader
                            // },
                            "processing": true,
                            drawCallback: function(settings)
                            {  
                                $("#emptyDataDiv").hide();   
                                $(".dataTable").show();
                                if($('.dataTables_empty').length == 1)
                                {
                                    $('#districtListingTable_paginate').hide();
                                    $('#districtListingTable_length').hide();
                                    $('#districtListingTable_info').hide();
                                    $("#categoryListingTable thead").hide();
                                    // This is for show and hide different div when 
                                    // -> No search result found  
                                    // -> There is no record added
                                    if (isDataFromSearch == true)
                                    {							
                                        console.log("No Search Result");
                                        isDataFromSearch = false;
                                    }
                                    $("#emptyDataDiv").show();   
                                    $(".dataTable").hide();
                                    // $(".pageSearch").hide();
                                    // $(".noRecord").hide();								
                                    $(".pageTitle .addBtn").hide();
                                }
                                //roleWisePermission();
                            },
                            "ajax":
                            {	
                                "url": codeUrl+"api/category_master.php?action=category_listing",	
                                "timeout" : 30000,
                                "type" : 'POST',
                                "async" : true,
                                "datatype" : "json",
                                "beforeSend" : function()
                                {
                                    $("#listingLoader").show(); // show loader
                                },
                                "error" : function(XMLHttpRequest, errorStatus, errorThrown) 
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
                                    return false
                                },
                                "complete": function (data) 
                                { 
                                    $("#listingLoader").hide(); // hide loader
                                }
                            },
                            "columnDefs": 
                            [
                                {
                                    "defaultContent": "-",
                                    "targets": "_all"
                                },
                                {
                                    "targets" : 0,
                                    "data" : "count",
                                    "title": "#",
                                    "visible": true,				
                                    "width" : "5%",
                                    "searchable" : false,
                                    "orderable" : false,
                                    "orderData" : "",
                                    "className" : "all",
                                    "render": ""	
                                },
                                {
                                    "targets" : 1,
                                    "title": "Category Name",
                                    "data" : "category_name",
                                    "name": "category_name",
                                    "width" : "25%",
                                    "searchable" : true,
                                    "visible": true,    
                                    "orderable" : true,
                                    "orderData" : [1],
                                    "orderDataType" : "asc",
                                    "cellType" : "td",	
                                    "className" : "all",	
                                    "createdCell" : "",	
                                    "render": ""
                                },
                                {
                                    "targets" : 2,
                                    "title": "Parent Category",
                                    "data" : "parent_category_name",
                                    "name": "parent_category_name",
                                    "width" : "25%",
                                    "searchable" : true,
                                    "visible": true,    
                                    "orderable" :true,
                                    "orderData" : [2],		
                                    "orderDataType" : "asc",	
                                    "cellType" : "td",	
                                    "className" : "desktop",	
                                    "createdCell" : "",	
                                    "render": ""
                                },
                                {
                                    "targets": 3,
                                    "title": "Status",	
                                    "data" : "status",	
                                    "name" : "status",
                                    "visible": true,
                                    "width" : "15%",	
                                    "searchable": true,	
                                    "orderable": true,
                                    "orderData" : [3],		
                                    "orderDataType" : "asc",
                                    "cellType": "td",	
                                    "className":"desktop",
                                    "createdCell" : "",	
                                    "render":function(data,type,row)
                                    {
                                        var status = row['status'];
                                        var upperCaseStatus = status.charAt(0).toUpperCase() + status.slice(1);                       
                                        return '<label class="listStatus '+status+'">'+upperCaseStatus+'</label>';
                                    }
                                },
                                {
                                    "targets": 4,
                                    "title": "",	
                                    "data" : "action",	
                                    "name" : "action",
                                    "visible": true,
                                    "width" : "5%",	
                                    "searchable": false,	
                                    "orderable": false,
                                    "orderData" : [4],		
                                    "orderDataType" : "asc",
                                    "cellType": "td",	
                                    "className":"desktop action",
                                    "createdCell" : "",	
                                    "render":function(data,type,row)
                                    {
                                        var id = row['id'];
                                        var categoryId = row['idMd'];
                                        
                                        return '<div class="btn-group"><a class="btn"></a><div class="dropdown"><a href="javascript:;" id="status" onclick="changeCategoryStatus('+id+');"><i class="fa fa-exchange"></i>Change Status</a><a href="<?php echo $codeUrl;?>add_category.php?action=edit&id='+categoryId+'" id="edit"><i class="fa fa-pencil"></i>Edit</a><div class="divider"></div><a href="javascript:;" id="delete" onclick="deleteCategory('+id+');" value="Delete"><i class="fa fa-trash"></i>Delete</a></div></div>';
                                    }
                                }
                            ],
                            "initComplete": function(settings, json) 
                            {
                                //roleWisePermission();
								
                                $("#categoryListingTable").removeClass('table-loader').show(); // To Hide Skeleton Loader
                            }
                        });
        }
        // function end here : initDataTable

        
        // function start here
        function deleteCategory(categoryId)
        {
            var reomveId = categoryId;
           
            $.ajax({
                type : 'POST',
                url : codeUrl+'api/category_master.php',
                data: 'action=check_category_child_present&id='+reomveId,
                timeout : 30000,
                cache : false,
                async : true,
                beforeSend : function()
                {
                },
                success :function(response)
                {
                    response = JSON.parse(response);

                    var status = response.header.status; // get status code
                    var message = response.header.message; // get message

                    console.log("status :: "+status);
                    console.log("message :: "+message);

                    if(status == 200) //child present
                    {
                        $("#warningForStatusOrDelete").text("Sorry, you can not delete this category.There are sub category records present for the selected category. You first need to delete those sub category");
                        $("#overlayId").show();
                        $("#modelId").show();
                    }
                    else 
                    {
                        //child not present so now can delete category
                        deleteCategoryIfNoChild(categoryId);
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
                complete:function()
                {
                }
            });
        } 
        // function end here : deleteCategory
        // function start here
        function deleteCategoryIfNoChild(categoryId)
        {
            var isTempDelete = 0;
            var reomveId = categoryId;
            if(confirm("Are you sure you want to delete selected record?"))
            {
                $.ajax({
                    type : 'POST',
                    url : codeUrl+'api/soft_delete_from_listing.php',
                    data: 'id='+reomveId+'&table_name='+tableName,
                    timeout : 30000,
                    cache : false,
                    async : true,
                    beforeSend : function()
                    {
                    },
                    success :function(response)
                    {
                        if(response == 1)
                        {
                            isTempDelete = 1;
                            table.row('.selected').remove().draw( false );

                            $("#successMessageLableId").text('');		
                            $("#successMessageLableId").text('Row deleted successfully');
                            $("#successMessageDiv").show();
                        }
                        else
                        {
                            isTempDelete = 0;
                            $("#errorMessageLableId").text('');		
                            $("#errorMessageLableId").text('There is something wrong to delete');
                            $("#errorMessageDiv").show();
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
                    complete:function()
                    {	
                        if(isTempDelete == 1)
                        {
                            $("#successMessageDiv").fadeIn().delay(7000).fadeOut();
                        }   
                        else{
                            $("#errorMessageDiv").fadeIn().delay(7000).fadeOut();
                        }

                        isDataFromSearch = false;
                    }
                });
            }
        } 
        // function end here : deleteCategoryIfNoChild
        
        // function start here
        function changeCategoryStatus(categoryId)
        {
            if(categoryId != "")
            {
                $.ajax({
                    type: "POST",
                    url: codeUrl+"api/category_master.php",
                    data: 'action=check_category_child_present&id='+categoryId,
                    cache: false,
                    async: true,
                    timeout : 30000,
                    beforeSend: function()
                    {
                    },
                    success: function(response)
                    {
                        response = JSON.parse(response);

                        var status = response.header.status; // get status code
                        var message = response.header.message; // get message

                        //console.log("status :: "+status);
                        //console.log("message :: "+message);
                        
                        if(status == 200) //child present
                        {
                            $("#warningForStatusOrDelete").text("Sorry, you can not change status of this category.There are sub category records present for the selected category. You first need to change status of those sub category");
                            $("#overlayId").show();
                            $("#modelId").show();
                        }
                        else 
                        {
                            //child not present so now can change status
                            changeStatusOfCategoryIfNoChild(categoryId);
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
                    complete : function()
                    {	
                    }
                });
            }	
        } 
        // function end here : changeCategoryStatus
        
        // function start here
        function changeStatusOfCategoryIfNoChild(categoryId)
        {
            var isTempStatus =  0;
            if(categoryId != "")
            {
                $.ajax({
                    type: "POST",
                    url: codeUrl+"api/change_status.php",
                    data: 'id='+categoryId+'&table='+tableName,
                    cache: false,
                    async: true,
                    timeout : 30000,
                    beforeSend: function()
                    {
                    },
                    success: function(response)
                    {
                        response = JSON.parse(response);

                        var status = response.header.status; // get status code
                        var message = response.header.message; // get message

                        //console.log("status :: "+status);
                        //console.log("message :: "+message);
                        if(status == 200)
                        {
                            isTempStatus = 1;
                            table.draw('page');	// status update table
                            $("#successMessageLableId").text('');		
                            $("#successMessageLableId").text('Status changed successfully');
                            $("#successMessageDiv").show();
                        }
                        else
                        {
                            isTempStatus = 0;
                            $("#errorMessageLableId").text('');		
                            $("#errorMessageLableId").text('Status not updated');
                            $("#errorMessageDiv").show();
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
                    complete : function()
                    {		
                        if(isTempStatus == 1)
                        {
                            $("#successMessageDiv").fadeIn().delay(7000).fadeOut();
                        }
                        else
                        {
                            $("#errorMessageDiv").fadeIn().delay(7000).fadeOut();
                        }  
                        isDataFromSearch = false;
                    }
                });
            }	
        } 
        // function end here : changeDistrictStatus
        
        // function start here
        function hideErrorMessage(id)
        {
            $("#"+id).hide(); // hide error message
        } 
        // function end here : hideErrorMessage
        function closePopup()
        {
            $("#overlayId").hide();
            $("#modelId").hide();
        }
	</script>	
</head>
<body>

<!-- 
	=============== 
	Header
	=============== 
-->
<?php require_once ('inc/php/header.php'); ?>


<!-- 
	=============== 
	Page Title
	=============== 
-->
<section class="pageTitle">
	<h1>Manage Category</h1>	
	<a href="<?php echo $codeUrl;?>add_category.php" class="addBtn">Add Category</a>
    <div class="clr"></div>
</section> 
<!--
    ==================
    Page Title End
    ==================
-->

<!--
    ==================
    Page Content Start
    ==================
-->
<section class="pageContent">
    
    <!--overlay for Status or Delete Error message-->
    <div class="overlay" id="overlayId" style="display:none;"></div>
    <div class="model" id="modelId" style="display:none;">
	<div class="modelWrapper">

            <h4>
                Oops!!
                <a href="javascript:;" id="closePopup" class="close" onclick="closePopup();"></a>
            </h4>
		
            <div class="modelContent" style="padding:25px 0px;">
                
                <div style="text-align:center;">
                    <span id='warningForStatusOrDelete'></span>
                </div>
				
                <div id="contentDiv"></div>
               
                <div class="submitBtn" style="width:180px;">                    
                    <div class="btnLoader" id="btnLoader" style="display:none">
                        <span class="spinLoader small"></span>
                    </div>
                    <a id="setStatus" href="javascript:;" class="btn" onclick="closePopup();">OK</a>
                </div>

            </div>		
	</div>
    </div>
    
	<?php
	if ($isDevice == "mobile")
	{
	?>
		<div class="searchForMobile">
			<a href="javascript:;" onclick="toggleSearch()">Search...</a>
		</div>
	<?php
	}
	?>
	
    <!--
		==================
		Page Search
		==================
	-->
	<div class="pageSearch" style="grid-template-columns:46% 25% 25%; grid-column-gap:2%; width:70%;"> 
	
		<?php
		if ($isDevice == "mobile")
		{
		?>
                    <div class="searchForMobileHeader">
                        <a href="javascript:;" class="back" onclick="toggleSearch()"></a>
                        <h2>Search Category</h2>
                    </div>
		<?php
		}
		?>
		
        <span class="filed"> 
            <input autocomplete="off" type="text" id="searchText_1" name="searchText_1" placeholder="Search by Category Name" onkeypress="hideErrorMessage('errorSearch');" />
        </span>

        <span class="filed searchDropdown">
            <select id="searchText_2" name="searchText_2" class="searchInput" onchange="hideErrorMessage('errorSearch');">
                <option value="">Select Parent Category</option>
                <?php
                foreach($parentCategory as $categoryData)
                {
                    $parentCatId = $categoryData['id'];
                    $parentCatName = $categoryData['name'];
                ?>
                <option id='<?php echo $parentCatId; ?>' value='<?php echo $parentCatId; ?>'><?php echo ucfirst($parentCatName); ?></option>
                <?php
                }
                ?>
            </select> 
        </span>
	 
        <span class="filed searchDropdown">
            <select id="searchText_3" name="searchText_3" class="searchInput" onchange="hideErrorMessage('errorSearch');">
                <option value="all">Select Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select> 
        </span> 
        
        <span class="searchBtn">
            <div class="searchLoader" style="display:none;" id="searchLoader">
                <span class="spinLoader small"></span>
            </div>
            <input id="searchButton" type="button" value="">
        </span>
        
        <span class="clearBtn">
            <input type="button" value="Clear All" onclick="window.location.href='<?php echo $codeUrl; ?>category.php'" style="display:none;" id="clearAll">
        </span>
            
        <div class="clr"> 
            <span id="errorSearch" style="display: none; color:red;">Enter at least one field to search</span> 
        </div>
    </div>

	<!--
		==================
		Alert :: success
		==================
	-->
	<div  class="alert alert-success" style="display:none;" id="successMessageDiv">
        <img src="images/success-tik.svg" />
		<strong>Success!</strong>
        <label id="successMessageLableId"></label>       
    </div>
	
	<!--
		==================
		Alert :: Error
		==================
	-->
    <div class="alert alert-error" style="display:none;" id="errorMessageDiv">			
		<img src="images/error_x.svg" />
		<strong>Error!</strong> 
        <label id="errorMessageLableId"></label>
    </div>
        
    

	<!--
		==================
		No Record
		==================
	-->
    <div class='card noListingData' id='emptyDataDiv' style='display:none;'>
        <h4>No record found</h4>
        <p>Looks like there is no category.</p>
        
		<!-- ==== -->
		<span class="button green">
			<div class="btnLoader" style="display:none;">
				<span class="spinLoader small"></span>
			</div>	
			<a href='<?php echo $codeUrl;?>add_category.php' id='add'>Add Category</a>
		</span>
		
        <div class='img'><img src='images/no-data-available.png' /></div>
    </div>

	<!--
		==================
		Datatable :: Listing
		==================
	-->
    <div class="dataTable">
        <table id="categoryListingTable" class="table-loader" cellpadding="0" cellspacing="0" border="0" width="100%"></table>
    </div>
  
</section> 
<!-- 
	===============
	Page Content End 
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