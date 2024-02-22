
<!doctype html>
<html class="no-js">
<head>
	<title>App User</title>  
	
	<?php require_once("./inc/php/head.php"); ?>
	
	

	
	
</head>
<body>
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
	<h1>App Users</h1>	
	<a href="javascript:;" class="addBtn">Add Form</a>
    <div class="clr"></div>
</section>
<!-- 
	=============== 
	Page Title End
	=============== 
-->

<!--
	==================
	Page Content Start
	==================
-->
<section class="pageContent">
	
	<!--
		===================
		Page Search
		===================
	-->
	<div class="pageSearch" style="grid-template-columns:24% 18% 40% 15%; grid-column-gap:1%; width:80%;"> 
				
		<span class="filed"> 
			<input autocomplete="off" type="text" placeholder="Search by Business Name">
		</span>
		
		<span class="filed searchDropdown">
			<select class="searchInput" >
				<option value="">Select Category</option>
			</select> 
            <div class="inputLoader" style="display:block;">
                <span class="spinLoader small"></span>
            </div>
		</span>
			
		<span class="filed ">
			<input type="text" placeholder="Search by Owner Name / Mobile No.">
		</span> 
		
		<span class="filed searchDropdown">
            <select class="searchInput" >
				<option value="all">Select Status</option>
			</select> 
		</span>
		
		<span class="searchBtn">
			<div class="searchLoader" style="display:block;">
				<span class="spinLoader small"></span>
			</div>
			<input id="searchButton" type="button" value="">
		</span>
		
		<span class="clearBtn">
			<input type="button" value="Clear All" style="display:block;" id="clearAll">
		</span>

		<div class="clr"> 
			<span id="errorSearch" style="display: none; color:red;">Enter at least one field to search</span> 
		</div>
		
	</div>
	
	<!--
		===================
		Alert :: Success
		===================
	-->	
	<div class="alert alert-success" style="display:block;" id="successMessageDiv">
		<img src="images/success-tik.svg" />
		<strong>Success!</strong>
	</div>
	
	
	<!--
		===================
		Alert :: Error
		===================
	-->	
	<div class="alert alert-error" style="display:block;" id="errorMessageDiv"> 			
		<img src="images/error_x.svg" />
		<strong>Error!</strong>
	</div>
	
	
	<!--
		==================
		Datatable :: Listing
		==================
	-->	
    <div class="dataTable">
       Datatable  in this div...
    </div>
	
</div>
  
</section>
<!--
	==================
	Page Content End
	==================
-->

<!--
	==================
	Footer Start
	==================
-->
<?php require_once ('inc/php/footer.php'); ?>
</body>

</html>