<!-- 
    Created by khyati panchal
    file name : supplier.php
-->

<?php
    require_once 'inc/php/config.php';
    require_once 'inc/dal/baseclasses/class.database.php';
    require_once 'inc/dal/settings.child.php';
    require_once 'inc/dal/supplier_master.child.php';
    require_once 'inc/php/functions.php';

    $codeUrl = getCodeUrl(); // call function and get code url
    
?>

<!doctype html>
<html class="no-js">
<head>
	<title>Manage Supplier</title>  
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
		
        var tableName = 'supplier_master'; // define table name 
        var table = '';       
        
        var isDataFromSearch = false; // flag for serch

        // onload start here
        $(document).ready(function()
        {	
            //getAllRegion(); // ajax call for get all region list

            $("#clearAllButton").attr("disabled","disabled");
            
            //Message from Add Supplier
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
            $('#supplierListingTable').on('page.dt', function ()
            {	
                $("html, body").animate({ scrollTop: 0 }, 600);
            });
		        
            // Search Data
            var flagForSearchValidation = 0;
            $('#searchButton').click( function () 
            {
                // Reset the datatable before search so that start and limit variables will have the default values to get correct dataset from the DB
                var tableId = "#supplierListingTable";
                
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
                $("#supplierListingTable").addClass('table-loader').show();
                
                // 3rd reCreate Datatable object
                initDataTable();
                
                isDataFromSearch = true;
                
                $("#supplierListingTable thead").show();
                
                $("#searchLoader").show();
                $("#clearAll").show();
                for(var i=1;i <= 6;i++)
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
            table = $('#supplierListingTable').DataTable({
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
                                    $('#supplierListingTable_paginate').hide();
                                    $('#supplierListingTable_length').hide();
                                    $('#supplierListingTable_info').hide();
                                    $("#supplierListingTable thead").hide();
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
                                "url": codeUrl+"api/supplier_master.php?action=supplier_listing",	
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
                                    "title": "Supplier Name",
                                    "data" : "supplier_name",
                                    "name": "supplier_name",
                                    "width" : "25%",
                                    "searchable" : true,
                                    "visible": true,    
                                    "orderable" :true,
                                    "orderData" : [1],		
                                    "orderDataType" : "asc",	
                                    "cellType" : "td",	
                                    "className" : "desktop",	
                                    "createdCell" : "",	
                                    "render": ""
                                },
                                {
                                    "targets" : 2,
                                    "title": "Supplier Type",
                                    "data" : "supplier_type",
                                    "name": "supplier_type",
                                    "width" : "25%",
                                    "searchable" : true,
                                    "visible": true,    
                                    "orderable" : true,
                                    "orderData" : [2],
                                    "orderDataType" : "asc",
                                    "cellType" : "td",	
                                    "className" : "all",	
                                    "createdCell" : "",	
                                    "render": ""
                                },
                                {
                                    "targets" : 3,
                                    "title": "Contact Person Name",
                                    "data" : "contact_person_name",
                                    "name": "contact_person_name",
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
                                    "title": "Mobile Number",
                                    "data" : "mobile_number",
                                    "name": "mobile_number",
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
                                    "targets": 5,
                                    "title": "Status",	
                                    "data" : "status",	
                                    "name" : "status",
                                    "visible": true,
                                    "width" : "15%",	
                                    "searchable": true,	
                                    "orderable": true,
                                    "orderData" : [5],		
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
                                    "targets": 6,
                                    "title": "",	
                                    "data" : "action",	
                                    "name" : "action",
                                    "visible": true,
                                    "width" : "5%",	
                                    "searchable": false,	
                                    "orderable": false,
                                    "orderData" : [6],		
                                    "orderDataType" : "asc",
                                    "cellType": "td",	
                                    "className":"desktop action",
                                    "createdCell" : "",	
                                    "render":function(data,type,row)
                                    {
                                        var id = row['id'];
                                        var supplierId = row['idMd'];
                                        
                                        return '<div class="btn-group"><a class="btn"></a><div class="dropdown"><a href="javascript:;" id="status" onclick="changeSupplierStatus('+id+');"><i class="fa fa-exchange"></i>Change Status</a><a href="<?php echo $codeUrl;?>add_supplier.php?action=edit&id='+supplierId+'" id="edit"><i class="fa fa-pencil"></i>Edit</a><div class="divider"></div><a href="javascript:;" id="delete" onclick="deleteSupplier('+id+');" value="Delete"><i class="fa fa-trash"></i>Delete</a></div></div>';
                                    }
                                }
                            ],
                            "initComplete": function(settings, json) 
                            {
                                //roleWisePermission();
								
                                $("#supplierListingTable").removeClass('table-loader').show(); // To Hide Skeleton Loader
                            }
                        });
        }
        // function end here : initDataTable

        // function start here
        function deleteSupplier(supplierId)
        {
            var isTempDelete = 0;
            var reomveId = supplierId;
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
        // function end here : deleteSupplier
         
        // function start here
        function changeSupplierStatus(supplierId)
        {
            var isTempStatus =  0;
            if(supplierId != "")
            {
                $.ajax({
                    type: "POST",
                    url: codeUrl+"api/change_status.php",
                    data: 'id='+supplierId+'&table='+tableName,
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
        // function end here : changeSupplierStatus
        
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
	<h1>Manage Supplier</h1>	
	<a href="<?php echo $codeUrl;?>add_supplier.php" class="addBtn">Add Supplier</a>
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
                        <h2>Search Supplier</h2>
                    </div>
		<?php
		}
		?>
        
        
            
        <span class="filed"> 
            <input autocomplete="off" type="text" id="searchText_1" name="searchText_1" placeholder="Search by Supplier Name" onkeypress="hideErrorMessage('errorSearch');" />
        </span>
            
            <span class="filed searchDropdown">
            <select id="searchText_2" name="searchText_2" class="searchInput" onchange="hideErrorMessage('errorSearch');">
                <option value="">Select Type</option>on>
                <?php 
                    foreach($supplierTypeArray as $key=>$value)
                    {
                        $displayValue = str_replace('_',' ', $value);
                ?>
                <option id="<?php echo $key; ?>" value="<?php echo $value; ?>"><?php echo ucfirst($displayValue); ?></option>
                <?php
                }
                ?>
            </select> 
        </span>

	 
        <span class="filed searchDropdown">
            <select id="searchText_5" name="searchText_5" class="searchInput" onchange="hideErrorMessage('errorSearch');">
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
            <input type="button" value="Clear All" onclick="window.location.href='<?php echo $codeUrl; ?>supplier.php'" style="display:none;" id="clearAll">
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
        <p>Looks like there is no supplier.</p>
        
		<!-- ==== -->
		<span class="button green">
			<div class="btnLoader" style="display:none;">
				<span class="spinLoader small"></span>
			</div>	
			<a href='<?php echo $codeUrl;?>add_supplier.php' id='add'>Add Supplier</a>
		</span>
		
        <div class='img'><img src='images/no-data-available.png' /></div>
    </div>

	<!--
		==================
		Datatable :: Listing
		==================
	-->
    <div class="dataTable">
        <table id="supplierListingTable" class="table-loader" cellpadding="0" cellspacing="0" border="0" width="100%"></table>
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