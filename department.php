<!-- 
    Created by khyati panchal
    file name : department.php
-->

<?php
    require_once 'inc/php/config.php';
    require_once 'inc/dal/baseclasses/class.database.php';
    require_once 'inc/dal/settings.child.php';
    require_once 'inc/dal/department_master.child.php';
    require_once 'inc/php/functions.php';

    $codeUrl = getCodeUrl(); // call function and get code url
?>

<!doctype html>
<html class="no-js">
<head>
	<title>Manage Department</title>  
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
		
        var tableName = 'department_master'; // define table name 
        var table = '';       
        
        var isDataFromSearch = false; // flag for serch

        // onload start here
        $(document).ready(function()
        {	
            $("#action").val('add');
            $("#modelTitle").text('Add Department');
            $("#addDepartment").val('Add Department');
            
            $("#clearAllButton").attr("disabled","disabled");
            
            //Message from Add Department
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
            $('#departmentListingTable').on('page.dt', function ()
            {	
                $("html, body").animate({ scrollTop: 0 }, 600);
            });
		        
            // Search Data
            var flagForSearchValidation = 0;
            $('#searchButton').click( function () 
            {
                // Reset the datatable before search so that start and limit variables will have the default values to get correct dataset from the DB
                var tableId = "#departmentListingTable";
                
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
                $("#departmentListingTable").addClass('table-loader').show();
                
                // 3rd reCreate Datatable object
                initDataTable();
                
                isDataFromSearch = true;
                
                $("#departmentListingTable thead").show();
                
                $("#searchLoader").show();
                $("#clearAll").show();
                for(var i=1;i <= 3;i++)
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
            table = $('#departmentListingTable').DataTable({
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
                                    $('#departmentListingTable_paginate').hide();
                                    $('#departmentListingTable_length').hide();
                                    $('#departmentListingTable_info').hide();
                                    $("#departmentListingTable thead").hide();
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
                                "url": codeUrl+"api/department_master.php?action=department_listing",	
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
                                    "title": "Department Name",
                                    "data" : "department_name",
                                    "name": "department_name",
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
                                    "targets": 2,
                                    "title": "Status",	
                                    "data" : "status",	
                                    "name" : "status",
                                    "visible": true,
                                    "width" : "15%",	
                                    "searchable": true,	
                                    "orderable": true,
                                    "orderData" : [2],		
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
                                    "targets": 3,
                                    "title": "",	
                                    "data" : "action",	
                                    "name" : "action",
                                    "visible": true,
                                    "width" : "5%",	
                                    "searchable": false,	
                                    "orderable": false,
                                    "orderData" : [3],		
                                    "orderDataType" : "asc",
                                    "cellType": "td",	
                                    "className":"desktop action",
                                    "createdCell" : "",	
                                    "render":function(data,type,row)
                                    {
                                        var id = row['id'];
                                        var departmentId = row['idMd'];
                                        var departmentName = row['department_name'];
                                        
                                        return '<div class="btn-group"><a class="btn"></a><div class="dropdown"><a href="javascript:;" id="status" onclick="changeDepartmentStatus('+id+');"><i class="fa fa-exchange"></i>Change Status</a><a href="javascript:;" onclick="editDepartment(`'+departmentName+'`,'+id+');" id="edit"><i class="fa fa-pencil"></i>Edit</a><div class="divider"></div><a href="javascript:;" id="delete" onclick="deleteDepartment('+id+');" value="Delete"><i class="fa fa-trash"></i>Delete</a></div></div>';
                                    }
                                }
                            ],
                            "initComplete": function(settings, json) 
                            {
                                //roleWisePermission();
								
                                $("#departmentListingTable").removeClass('table-loader').show(); // To Hide Skeleton Loader
                            }
                        });
        }
        // function end here : initDataTable

        // function start here
        function deleteDepartment(departmentId)
        {
            var isTempDelete = 0;
            var reomveId = departmentId;
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
        // function end here : deleteDepartment
        
        
        // function start here
        function changeDepartmentStatus(departmentId)
        {
            var isTempStatus =  0;
            if(departmentId != "")
            {
                $.ajax({
                    type: "POST",
                    url: codeUrl+"api/change_status.php",
                    data: 'id='+departmentId+'&table='+tableName,
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
        
        //Add Department Funcitons starts from here
        function addDepartment()
        {
            $("#overlayId").show();
            $("#modelId").show();
        }
        
        function editDepartment(departmentName,editId)
        {
            $("#overlayId").show();
            $("#modelId").show();
            $("#txtDepartmentName").val(departmentName);
            $("#action").val('edit');
            $("#hdnDepartmentId").val(editId);
            $("#modelTitle").text('Edit Department');
            $("#addDepartment").val('Edit Department');
            
        }
        
        function addDepartmentValidation()
        {
            var isFormValidate = true; // set flag
            var txtDepartmentName = $("#txtDepartmentName").val();

            if (txtDepartmentName == '' || txtDepartmentName == null || txtDepartmentName == undefined) 
            {
                $("#errorDepartmentName").text('Department name is required');
                $("#errorDepartmentName").show();
                isFormValidate = false;
            }

            navigateToErrorMessage(); // call function for navigate user to specific error message location

            if (isFormValidate) 
            {
                $("#addDepartmentLoader").show(); // show loader
                submitDataThroughAjax();
            } 
            else 
            {
                $("#addDepartmentLoader").hide(); // hide loader
                return false;
            }
        }
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
            var allRequestData = $('#addDepartmentForm').serializeArray();
            
            // alert("allRequestData >> "+JSON.stringify(allRequestData));

            $.each(allRequestData,function(key,input)
            {
                // alert("name >> "+input.name+" value >> "+input.value);
                formData.append(input.name,input.value);
            });
            
            //alert("formData >> "+JSON.stringify(formData));

            var finalUrl = codeUrl+"api/department_master.php"; // finalUrl
            
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
                    $("#addDepartmentLoader").show(); // show loader
                },
                success: function(response)
                {
                    var status = response.header.status; // get status code
                    var message = response.header.message; // get message

                    //alert("status ::"+status);
                    //console.log("message ::"+message);
                    
                    if(status == 200)
                    {
                        window.location.href="department.php"; // redirect on page
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
                    $("#addDepartmentLoader").hide(); // hide loader
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
	<h1>Manage Department</h1>	
	<a href="javascript:;" onclick='addDepartment();' class="addBtn">Add Department</a>
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
                <span id="modelTitle"></span>
                <a href="javascript:;" id="closePopup" class="close" onclick="closePopup();"></a>
            </h4>
		
            <div class="modelContent" style="padding:25px 0px;">
                
                <div style="text-align:center;">
                    <form id="addDepartmentForm" name="addDepartmentForm" method="post">
                        <div class="card">
                        <ul class="form">
                            <li>
                                <div class="lbl">Department Name<span>*</span></div>
                                <div class="val">
                                    <input autocomplete="off" type="text" class="input " placeholder="Enter Department Name" id="txtDepartmentName" name="txtDepartmentName" onkeypress="hideErrorMessage('errorDepartmentName');" value='' />
                                    <div class="validation">
                                        <span style="display:none;" id="errorDepartmentName"></span>
                                    </div>
                                </div>
                                <!-- value Ends -->
                            </li>
                        </ul>
                        </div>
                        
                        <div class="submitBtn submit" style="display:inline-block; margin-left:calc(250px + 20px);">
                            <div class="btnLoader" id="addDepartmentLoader" style="display:none;">
                                <span class="spinLoader"></span>
                            </div>
                            <!-- hiddens -->
                            <input type="hidden" id="action" name="action" value="">
                            <input type="hidden" id="hdnDepartmentId" name="hdnDepartmentId" value="">

                            <input value="" class="btn" type="button" id="addDepartment" name="addDepartment"  onclick="return addDepartmentValidation();">
                        </div>
                    </form>
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
			
        <span class="filed"> 
            <input autocomplete="off" type="text" id="searchText_1" name="searchText_1" placeholder="Search by Department Name" onkeypress="hideErrorMessage('errorSearch');" />
        </span>

        <span class="filed searchDropdown">
            <select id="searchText_2" name="searchText_2" class="searchInput" onchange="hideErrorMessage('errorSearch');">
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
            <input type="button" value="Clear All" onclick="window.location.href='<?php echo $codeUrl; ?>department.php'" style="display:none;" id="clearAll">
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
        <p>Looks like there is no department.</p>
        
		<!-- ==== -->
		<span class="button green">
			<div class="btnLoader" style="display:none;">
				<span class="spinLoader small"></span>
			</div>	
			<a href='javascript:;' onclick='addDepartment();' id='add'>Add Department</a>
		</span>
		
        <div class='img'><img src='images/no-data-available.png' /></div>
    </div>

	<!--
		==================
		Datatable :: Listing
		==================
	-->
    <div class="dataTable">
        <table id="departmentListingTable" class="table-loader" cellpadding="0" cellspacing="0" border="0" width="100%"></table>
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