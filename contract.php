<!-- 
    Created by khyati panchal
    file name : contract.php
-->

<?php
    require_once 'inc/php/config.php';
    require_once 'inc/dal/baseclasses/class.database.php';
    require_once 'inc/dal/settings.child.php';
    require_once 'inc/dal/contract_master.child.php';
    require_once 'inc/php/functions.php';

    $codeUrl = getCodeUrl(); // call function and get code url
    
?>

<!doctype html>
<html class="no-js">
<head>
	<title>Manage Contract</title>  
	<?php require_once ('inc/php/head.php'); ?>	

         <script src="inc/js/jquery-ui.js"></script>
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
		
        var tableName = 'contract_master'; // define table name 
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
            $('#searchText_6').daterangepicker({
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
            
            //Message from Add contract
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
            $('#contractListingTable').on('page.dt', function ()
            {	
                $("html, body").animate({ scrollTop: 0 }, 600);
            });
		        
            // Search Data
            var flagForSearchValidation = 0;
            $('#searchButton').click( function () 
            {
                // Reset the datatable before search so that start and limit variables will have the default values to get correct dataset from the DB
                var tableId = "#contractListingTable";
                
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
                $("#contractListingTable").addClass('table-loader').show();
                
                // 3rd reCreate Datatable object
                initDataTable();
                
                isDataFromSearch = true;
                
                $("#contractListingTable thead").show();
                
                $("#searchLoader").show();
                $("#clearAll").show();
                for(var i=1;i <= 8;i++)
                {
                    var searchValue = $("#searchText_"+i).val();
                    //alert("i :: " + i + ", searchValue :: " + searchValue);
                    if(i == 2) //search by suggestive search store
                    {
                        var searchValue = $("#hiddenSelectedSupplier").val();
                    }
                    else
                    {
                        var searchValue = $("#searchText_"+i).val();
                    }
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
            $('#searchText_6').val(start.format('MM-DD-YYYY') + ' to ' + end.format('MM-DD-YYYY'));
        }
        
        // function start here
        function initDataTable()
        {
            table = $('#contractListingTable').DataTable({
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
                                    $('#contractListingTable_paginate').hide();
                                    $('#contractListingTable_length').hide();
                                    $('#contractListingTable_info').hide();
                                    $("#contractListingTable thead").hide();
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
                                "url": codeUrl+"api/contract_master.php?action=contract_listing",	
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
                                    "title": "Title",
                                    "data" : "title",
                                    "name": "title",
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
                                    "title": "Supplier Name",
                                    "data" : "supplier_name",
                                    "name": "supplier_type_id",
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
                                    "title": "Contact Person",
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
                                    "title": "Contact Number",
                                    "data" : "contact_number",
                                    "name": "contact_number",
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
                                    "title": "Cost",
                                    "data" : "cost",
                                    "name": "cost",
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
                                    "title": "Start Date",
                                    "data" : "start_date",
                                    "name": "start_date",
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
                                    "targets" : 7,
                                    "title": "End Date",
                                    "data" : "end_date",
                                    "name": "end_date",
                                    "width" : "25%",
                                    "searchable" : true,
                                    "visible": true,    
                                    "orderable" :true,
                                    "orderData" : [7],		
                                    "orderDataType" : "asc",	
                                    "cellType" : "td",	
                                    "className" : "desktop",	
                                    "createdCell" : "",	
                                    "render": ""
                                },
                                {
                                    "targets": 8,
                                    "title": "Status",	
                                    "data" : "status",	
                                    "name" : "status",
                                    "visible": true,
                                    "width" : "15%",	
                                    "searchable": true,	
                                    "orderable": true,
                                    "orderData" : [8],		
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
                                    "targets": 9,
                                    "title": "",	
                                    "data" : "action",	
                                    "name" : "action",
                                    "visible": true,
                                    "width" : "5%",	
                                    "searchable": false,	
                                    "orderable": false,
                                    "orderData" : [9],		
                                    "orderDataType" : "asc",
                                    "cellType": "td",	
                                    "className":"desktop action",
                                    "createdCell" : "",	
                                    "render":function(data,type,row)
                                    {
                                        var id = row['id'];
                                        var contractId = row['idMd'];
                                        
                                        return '<div class="btn-group"><a class="btn"></a><div class="dropdown"><a href="javascript:;" id="status" onclick="changeContractStatus('+id+');"><i class="fa fa-exchange"></i>Change Status</a><a href="<?php echo $codeUrl;?>add_contract.php?action=edit&id='+contractId+'" id="edit"><i class="fa fa-pencil"></i>Edit</a><div class="divider"></div><a href="javascript:;" id="delete" onclick="deleteContract('+id+');" value="Delete"><i class="fa fa-trash"></i>Delete</a></div></div>';
                                    }
                                }
                            ],
                            "initComplete": function(settings, json) 
                            {
                                //roleWisePermission();
								
                                $("#contractListingTable").removeClass('table-loader').show(); // To Hide Skeleton Loader
                            }
                        });
        }
        // function end here : initDataTable

        
        // function start here
        function deleteContract(contractId)
        {
            var isTempDelete = 0;
            var reomveId = contractId;
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
        function changeContractStatus(contractId)
        {
            var isTempStatus =  0;
            if(contractId != "")
            {
                $.ajax({
                    type: "POST",
                    url: codeUrl+"api/change_status.php",
                    data: 'id='+contractId+'&table='+tableName,
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
        function getSupplierForSearch()
        {
            var alreadyAddedSupId = $("#hiddenSelectedSupplier").val();

            $("#searchText_2").autocomplete({
                source : function(request,response)
                {
                    $.ajax({
                            type : "POST",
                            url : codeUrl+"api/get_all_supplier.php?startWith="+request.term+'&alreadyAddedSupplierId='+alreadyAddedSupId,
                            async : true,
                            cache : true,
                            timeout : 30000,
                            dataType : "json",
                            beforeSend : function()
                            {
                              $("#supLoader").show();  
                            },
                            success : function(result)
                            {
                               response('');
                                if(request.term != '*')
                                {
                                    var term = $.ui.autocomplete.escapeRegex(request.term),startsWithMatcher = new RegExp("^" + term, "i"),
                                    startsWith = $.grep(result, function(value)
                                    {
                                        if (startsWithMatcher.test(value.label))
                                        {
                                            return true;
                                        } 
                                        
                                        return false;
                                    }),
                                    containsMatcher = new RegExp(term, "i"),
                                    contains =  $.grep(result, function (value) 
                                    {
                                        return $.inArray(value, startsWith) < 0 && containsMatcher.test(value.label || value.value || value);
                                    });
                                            
                                    var fullArray = startsWith.concat(contains);
                                    //fullArray.sortOn("type");
                                    fullArray.sortOn();
                                    console.log("full array::"+JSON.stringify(fullArray));
                                    //var matchingRecordArry = customFilter(result, request.term);
                                    var arrAll  = concatArraysUniqueWithSort(fullArray, result);
                                    //alert("aray all::"+JSON.stringify(arrAll));

                                    if(fullArray == '')
                                    {
                                        response(arrAll);	
                                    }
                                    else
                                        response(fullArray); 
                                    //response(checkSearchWordsMatchKeywords(request.term, result));
                                }
                                else
                                {
                                    response(result);
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
                                $("#supLoader").hide();
                            }
                    });
                },
                autoFocus: true,	      	
                minLength: 0,
                appendTo: "#supplierDiv",
                select : function(event,ui)
                {
                    $('#hiddenSelectedSupplier').val(ui.item.id);
                    $("#searchText_2").val(ui.item.label);
                    $("#removeSelectedSupplier").show();
                    return false;
                } })._renderItem = function (ul, item) 
                {
                if(item.label == 'No record found')
                {
                    t = "<div class='noSuggestionFound'>"+item.label+"</div>";
                    return $( "<li></li>" )
                            .data( "item.autocomplete", t )
                            .append( t )	//append <span> tag with display none to get location item id
                            .appendTo("#supplierDiv ul" );
                }
                else
                {
                    var originalValue = item.label;

                    var splitedVal = originalValue;
                    var textValue = $("#searchText_2").val();

                    if(textValue != '*')
                    {
                        var t = String(splitedVal).replace(new RegExp(this.term, "gi"),"<strong>$&</strong>");
                    }
                    else
                    {
                        var t = splitedVal;
                    }
                    var itemVal = t;
                    return $( "<li></li>" )
                            .data( "item.autocomplete", itemVal )
                            .append( "<a>" + itemVal + "</a>" )	//append <span> tag with display none to get location item id
                            .appendTo("#supplierDiv ul");
                }
            };
        }//function ends here.
        
        /**
         * Check that each word in a search string matches at least one keyword in an array
         * E.g. searchWords = 'create use'  and  keywords = ['create', 'add', 'make', 'insert', 'user'] will return true
         */
        function checkSearchWordsMatchKeywords(searchString, keywords)
        {
            var searchWords = searchString.toLowerCase().split(' ');    // Lowercase the search words & break up the search into separate words
            var numOfSearchWords = searchWords.length;                  // Count number of search words
            var numOfKeywords = keywords.length;                        // Count the number of keywords
            var matches = [];                                           // Will contain the keywords that matched the search words

            // For each search word look up the keywords array to see if the search word partially matches the keyword
            for (var i = 0; i < numOfSearchWords; i++)
            {
                // For each keyword
                for (var j = 0; j < numOfKeywords; j++)
                {   
                    // Check search word is part of a keyword
                    if (keywords[j].indexOf(searchWords[i]) != -1)
                    {
                        // Found match, store match, then look for next search word
                        matches.push(keywords[j]);
                        break;
                    }
                }
            }

            // Count the number of matches, and if it equals the number of search words then the search words match the keywords
            if (matches.length == numOfSearchWords)
            {
                return true;
            }

            return false;
        }

        Array.prototype.sortOn = function(key){
        this.sort(function(a, b){
            if(a < b){
                return -1;
            }else if(a > b){
                return 1;
            }
            return 0;
            /*if(a[key] < b[key]){
                return -1;
            }else if(a[key] > b[key]){
                return 1;
            }
            return 0;*/
        });
    }  

    var concatArraysUniqueWithSort = function (thisArray, otherArray) 
    {
        var newArray = thisArray.concat(otherArray).sort(function (a, b) 
        {
            if ((typeof a['store_name'] != "undefined") || (typeof b['store_name'] != "undefined")) 
            {
                    var aValue = a['store_name'];
                    var bValue = b['store_name'];
                    return bValue.toString().localeCompare(aValue);
                    //return aValue.toString().localeCompare(bValue);
            }
            //console.log('a ::: '+aValue[0]+' b::: '+bValue[0]);
            //return a > b ? 1 : a < b ? -1 : 0;
        });

        return newArray.filter(function (item, index)
        {
            return newArray.indexOf(item) === index;
        });
    };

        function customFilter(array, terms) 
        {
            arrayOfTerms = terms.split(" ");
            var term = $.map(arrayOfTerms, function (tm) 
            {
                return $.ui.autocomplete.escapeRegex(tm);
            }).join('|');
            var matcher = new RegExp("\\b" + term, "i");
            return $.grep(array, function (value) {
                return matcher.test(value.label || value.value || value);
            });
        }


        function removeSelectedSupplier()
        {
            $('#searchText_2').val('');
            $('#hiddenSelectedSupplier').val('');
            $("#removeSelectedSupplier").hide();
        } //Function ends here.
        
        function clearSelectedSupplier()
	{
            var storeValue = $("#searchText_2").val();
            $("#hiddenSelectedSupplier").val('');
	} //Function ends here.
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
	<h1>Manage Contract</h1>	
	<a href="<?php echo $codeUrl;?>add_contract.php" class="addBtn">Add Contract</a>
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
                        <h2>Search Contract</h2>
                    </div>
		<?php
		}
		?>
		
        <span class="filed"> 
            <input autocomplete="off" type="text" id="searchText_1" name="searchText_1" placeholder="Search by Contract Title" onkeypress="hideErrorMessage('errorSearch');" />
        </span>
            
        <span class="filed">
            <div class="multiSuggestion" style="width:100%;">
                <div class="inputLoader" style="display:none;" id="supLoader">
                        <span class="spinLoader small"></span>
                </div>
                <input type="text" id="searchText_2" name="searchText_2" class="input" placeholder="Enter * to view all stores" onkeypress="getSupplierForSearch()" onkeyup="clearSelectedSupplier();"/>
                <a href="javascript:;" onclick="removeSelectedSupplier();" class="removeNotificationTag" id='removeSelectedSupplier' style='display:none;'>X</a>
                <div class="suggestion" id="supplierDiv"></div>
            </div>
            <input type="hidden" name="hiddenSelectedSupplier" id="hiddenSelectedSupplier">			
    </span>
            
        <span class="filed"> 
            <input autocomplete="off" type="text" id="searchText_3" name="searchText_3" placeholder="Contact person" onkeypress="hideErrorMessage('errorSearch');" />
        </span>
            
        <span class="filed dateSelections">
            <input type="text" class="input" placeholder="Select Date Range" id="searchText_6" name="searchText_6" onkeypress="hideErrorMessage('errorSearch');" style="width:150px;" />
        </span>
	 
        <span class="filed searchDropdown">
            <select id="searchText_8" name="searchText_8" class="searchInput" onchange="hideErrorMessage('errorSearch');">
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
            <input type="button" value="Clear All" onclick="window.location.href='<?php echo $codeUrl; ?>contract.php'" style="display:none;" id="clearAll">
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
        <p>Looks like there is no contract.</p>
        
		<!-- ==== -->
		<span class="button green">
			<div class="btnLoader" style="display:none;">
				<span class="spinLoader small"></span>
			</div>	
			<a href='<?php echo $codeUrl;?>add_contract.php' id='add'>Add Contract</a>
		</span>
		
        <div class='img'><img src='images/no-data-available.png' /></div>
    </div>

	<!--
		==================
		Datatable :: Listing
		==================
	-->
    <div class="dataTable">
        <table id="contractListingTable" class="table-loader" cellpadding="0" cellspacing="0" border="0" width="100%"></table>
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