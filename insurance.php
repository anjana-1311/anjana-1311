<!-- 
    Created by khyati panchal
    file name : insurance.php
-->

<?php
    require_once 'inc/php/config.php';
    require_once 'inc/dal/baseclasses/class.database.php';
    require_once 'inc/dal/settings.child.php';
    require_once 'inc/dal/insurance_master.child.php';
    require_once 'inc/php/functions.php';

    $codeUrl = getCodeUrl(); // call function and get code url
    
?>

<!doctype html>
<html class="no-js">
<head>
	<title>Manage Insurance</title>  
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
    
    <link rel="stylesheet" type="text/css" href="<?php echo $codeUrl; ?>inc/css/daterangepicker.css" />
    <script type="text/javascript" src="<?php echo $codeUrl; ?>inc/js/daterangepicker/moment.min.js"></script>
    <script type="text/javascript" src="<?php echo $codeUrl; ?>inc/js/daterangepicker/daterangepicker.min.js"></script> 

	<script type="text/javascript">
        var codeUrl = '<?php echo $codeUrl; ?>'; // code url
		
        var tableName = 'insurance_master'; // define table name 
        var table = '';       
        
        var isDataFromSearch = false; // flag for serch
        
        // date range picker
        var now = new Date();
        //var end = new Date(now.getFullYear(), now.getMonth(), 0);
        var start = new Date(now.getFullYear() - (now.getMonth() > 0 ? 0 : 1), (now.getMonth() - 1 + 12) % 12, 1);
        var end = new Date(now.getFullYear(), now.getMonth() + 1, 0);
        var quarter = moment().quarter();
        var thisYear = new Date().getFullYear();    
        var startDateForYear = new Date(new Date().getFullYear(), 0, 1);
        var previousYear = thisYear - 1;
        var firstDayPreviousYear = new Date(previousYear, 0, 1);
        var lastDayPreviousYear = new Date(previousYear, 11, 31);
        
        // onload start here
        $(document).ready(function()
        {	
            $('#searchText_5').daterangepicker({
                alwaysShowCalendars: true,
                autoUpdateInput: false,
                //startDate: start,
                //endDate: end, 
                locale: 
                {
                    format: 'MM-DD-YYYY',
                },
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                    'This Quarter': [moment().quarter(quarter).startOf('quarter'), moment().quarter(quarter).endOf('quarter')],
                    'Last Quarter': [moment().subtract(1, 'quarter').startOf('quarter'), moment().subtract(1, 'quarter').endOf('quarter')],
                    'This Year': [startDateForYear, moment().clone().endOf('year')],
                    'Last Year': [firstDayPreviousYear, lastDayPreviousYear]
                },
                autoApply: true //Hide the apply and cancel buttons, and automatically apply a new date range as soon as two dates are clicked.
            },selectedDate);
            
            
            

            $("#clearAllButton").attr("disabled","disabled");
            
            //Message from Add insurance
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
            $('#insuranceListingTable').on('page.dt', function ()
            {	
                $("html, body").animate({ scrollTop: 0 }, 600);
            });
		        
            // Search Data
            var flagForSearchValidation = 0;
            $('#searchButton').click( function () 
            {
                // Reset the datatable before search so that start and limit variables will have the default values to get correct dataset from the DB
                var tableId = "#insuranceListingTable";
                
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
                $("#insuranceListingTable").addClass('table-loader').show();
                
                // 3rd reCreate Datatable object
                initDataTable();
                
                isDataFromSearch = true;
                
                $("#insuranceListingTable thead").show();
                
                $("#searchLoader").show();
                $("#clearAll").show();
                for(var i=1;i <= 7;i++)
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
        
        function selectedDate(start, end) 
        {
            $('#searchText_5').val(start.format('MM-DD-YYYY') + ' to ' + end.format('MM-DD-YYYY'));
        }
        
        // function start here
        function initDataTable()
        {
            table = $('#insuranceListingTable').DataTable({
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
                                    $('#insuranceListingTable_paginate').hide();
                                    $('#insuranceListingTable_length').hide();
                                    $('#insuranceListingTable_info').hide();
                                    $("#insuranceListingTable thead").hide();
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
                                "url": codeUrl+"api/insurance_master.php?action=insurance_listing",	
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
                                    "title": "Insurance Name",
                                    "data" : "insurance_name",
                                    "name": "insurance_name",
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
                                    "title": "Insurance Company Name",
                                    "data" : "insurance_company_name",
                                    "name": "insurance_company_name",
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
                                    "targets" : 3,
                                    "title": "Policy Number",
                                    "data" : "policy_number",
                                    "name": "policy_number",
                                    "width" : "25%",
                                    "searchable" : true,
                                    "visible": true,    
                                    "orderable" :true,
                                    "orderData" : [3],		
                                    "orderDataType" : "asc",	
                                    "cellType" : "td",	
                                    "className" : "desktop",	
                                    "createdCell" : "",	
                                    "render": ""
                                },
                                {
                                    "targets" : 4,
                                    "title": "Contact Person",
                                    "data" : "contact_person",
                                    "name": "contact_person",
                                    "width" : "25%",
                                    "searchable" : true,
                                    "visible": true,    
                                    "orderable" :true,
                                    "orderData" : [4],		
                                    "orderDataType" : "asc",	
                                    "cellType" : "td",	
                                    "className" : "desktop",	
                                    "createdCell" : "",	
                                    "render": ""
                                },
                                {
                                    "targets" : 5,
                                    "title": "Start Date",
                                    "data" : "start_date",
                                    "name": "start_date",
                                    "width" : "25%",
                                    "searchable" : true,
                                    "visible": true,    
                                    "orderable" :true,
                                    "orderData" : [5],		
                                    "orderDataType" : "asc",	
                                    "cellType" : "td",	
                                    "className" : "desktop",	
                                    "createdCell" : "",	
                                    "render": ""
                                },
                                {
                                    "targets" : 6,
                                    "title": "End Date",
                                    "data" : "end_date",
                                    "name": "end_date",
                                    "width" : "25%",
                                    "searchable" : true,
                                    "visible": true,    
                                    "orderable" :true,
                                    "orderData" : [6],		
                                    "orderDataType" : "asc",	
                                    "cellType" : "td",	
                                    "className" : "desktop",	
                                    "createdCell" : "",	
                                    "render": ""
                                },
                                {
                                    "targets": 7,
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
                                    "targets": 8,
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
                                        
                                        return '<div class="btn-group"><a class="btn"></a><div class="dropdown"><a href="javascript:;" id="status" onclick="changeInsuranceStatus('+id+');"><i class="fa fa-exchange"></i>Change Status</a><a href="<?php echo $codeUrl;?>add_insurance.php?action=edit&id='+categoryId+'" id="edit"><i class="fa fa-pencil"></i>Edit</a><div class="divider"></div><a href="javascript:;" id="delete" onclick="deleteInsurance('+id+');" value="Delete"><i class="fa fa-trash"></i>Delete</a></div></div>';
                                    }
                                }
                            ],
                            "initComplete": function(settings, json) 
                            {
                                //roleWisePermission();
								
                                $("#insuranceListingTable").removeClass('table-loader').show(); // To Hide Skeleton Loader
                            }
                        });
        }
        // function end here : initDataTable

        
        // function start here
        function deleteInsurance(insuranceId)
        {
            var isTempDelete = 0;
            var reomveId = insuranceId;
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
        // function end here : deleteInsurance
        
        
        // function start here
        function changeInsuranceStatus(insuranceId)
        {
            var isTempStatus =  0;
            if(insuranceId != "")
            {
                $.ajax({
                    type: "POST",
                    url: codeUrl+"api/change_status.php",
                    data: 'id='+insuranceId+'&table='+tableName,
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
        // function end here : changeInsuranceStatus
        
        // function start here
        function hideErrorMessage(id)
        {
            $("#"+id).hide(); // hide error message
        } 
        // function end here : hideErrorMessage
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
	<h1>Manage Insurance</h1>	
	<a href="<?php echo $codeUrl;?>add_insurance.php" class="addBtn">Add Insurance</a>
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
                        <h2>Search Insurance</h2>
                    </div>
		<?php
		}
		?>
		
        <span class="filed"> 
            <input autocomplete="off" type="text" id="searchText_1" name="searchText_1" placeholder="Search by Insurance Name" onkeypress="hideErrorMessage('errorSearch');" />
        </span>

        <span class="filed"> 
            <input autocomplete="off" type="text" id="searchText_2" name="searchText_2" placeholder="Insurance Company Name" onkeypress="hideErrorMessage('errorSearch');" />
        </span>
            
        <span class="filed"> 
            <input autocomplete="off" type="text" id="searchText_3" name="searchText_3" placeholder="Policy Number" onkeypress="hideErrorMessage('errorSearch');" />
        </span>
            
        <span class="filed"> 
            <input autocomplete="off" type="text" id="searchText_4" name="searchText_4" placeholder="Contact person" onkeypress="hideErrorMessage('errorSearch');" />
        </span>
            
        <span class="filed dateSelections">
            <input type="text" class="input" placeholder="Select Date Range" id="searchText_5" name="searchText_5" onkeypress="hideErrorMessage('errorSearch');" style="width:150px;" />
        </span>
	 
        <span class="filed searchDropdown">
            <select id="searchText_7" name="searchText_7" class="searchInput" onchange="hideErrorMessage('errorSearch');">
                <option value="all">Select Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="expired">Expired</option>
            </select> 
        </span> 
        
        <span class="searchBtn">
            <div class="searchLoader" style="display:none;" id="searchLoader">
                <span class="spinLoader small"></span>
            </div>
            <input id="searchButton" type="button" value="">
        </span>
        
        <span class="clearBtn">
            <input type="button" value="Clear All" onclick="window.location.href='<?php echo $codeUrl; ?>insurance.php'" style="display:none;" id="clearAll">
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
        <p>Looks like there is no insurance.</p>
        
		<!-- ==== -->
		<span class="button green">
			<div class="btnLoader" style="display:none;">
				<span class="spinLoader small"></span>
			</div>	
			<a href='<?php echo $codeUrl;?>add_insurance.php' id='add'>Add Insurance</a>
		</span>
		
        <div class='img'><img src='images/no-data-available.png' /></div>
    </div>

	<!--
		==================
		Datatable :: Listing
		==================
	-->
    <div class="dataTable">
        <table id="insuranceListingTable" class="table-loader" cellpadding="0" cellspacing="0" border="0" width="100%"></table>
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