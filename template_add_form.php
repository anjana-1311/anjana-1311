<!doctype html>
<html class="no-js">
<head>
	<title>Add User</title>  
	<?php require_once ('inc/php/head.php'); ?>	
	<link rel="stylesheet" href="inc/css/jquery-ui.css">
	<script type="text/javascript">
		
	</script>	
</head>

<body class="noMenu">
<!--
	============
	Header Start
	============
-->
<?php require_once ('inc/php/header.php'); ?>

<!--
	================
	Page Title Start
	================
-->
<section class="pageTitle">
	<h1>Add User</h1>	
	<a href="listing.php" class="back">Back</a>
    <div class="clr"></div>
</section>
<!--
	==============
	Page Title End
	==============
-->

<!--
	==================
	Page Content Start
	==================
-->
<section class="pageContent">

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
		==========
		Form Start
		==========
	-->
	<form>
		
		<!--
			====================
			Business Information
			====================
		-->
		<div class="card">
			<h2>Business Information</h2>
			<ul class="form">
				<li>
					<div class="lbl">Business Name<span>*</span></div>
					<div class="val">
						<input type="text" class="input" maxlength="70" placeholder="Enter Business Name" style="width:40%;" > 
						<div class="validation">
							<span style="display:block">Validation message in this Span</span>
						</div>
					</div>
					<!-- value Ends -->
				</li>	
				
				<li>
					<div class="lbl">Client Category<span>*</span></div>
					<div class="val">
						<div class="inputLoaderWrapper" style="width:40%;">
							<select class="input">
								<option id="" value="">Select Client Category</option>
							</select>
							<div class="inputLoader" style="display:block;">
								<span class="spinLoader small"></span>
							</div>
						</div>
						<div class="validation">
							<span style="display:block">Validation message in this Span</span>
						</div>
					</div>
					<!-- value Ends -->
				</li>
				
				<li>
					<div class="lbl">Business Code<span></span></div>
					<div class="val">
						<input type="text" class="input" placeholder="Enter Business Code" style="width:40%;"> 
						<div class="validation">
							<span style="display:block">Validation message in this Span</span>
						</div>
					</div>
					<!-- value Ends -->
				</li>
			</ul>
		</div>

		<!--
			====================
			Contact Details
			====================
		-->
		<div class="card">
			<h2>Contact Details</h2>
			<ul class="form">
				<li>
					<div class="lbl">Owner / Partner's Name<span>*</span></div>
					<div class="val">
						<input type="text" class="input" maxlength="70" placeholder="Enter Owner / Partner's Name" style="width:40%;"> 
						<div class="validation">
							<span style="display:block">Validation message in this Span</span>
						</div>
					</div>
					<!-- value Ends -->
				</li>	

				<li>
					<div class="lbl">Client Representative Name<span>*</span></div>
					<div class="val">
						<input type="text" class="input" maxlength="50" placeholder="Enter Client Representative Name" style="width:40%;"> 
						<div class="validation">
							<span style="display:block">Validation message in this Span</span>
						</div>
					</div>
					<!-- value Ends -->	
				</li>
				
				<li>
					<div class="lbl">Client Representative Number<span>*</span></div>
					<div class="val">
						<input type="text" class="input"placeholder="Enter Client Representative Number" style="width:40%;"> 
						<div class="validation">
							<span style="display:block">Validation message in this Span</span>
						</div>
					</div>
					<!-- value Ends -->	
				</li>
				
				<li>
					<div class="lbl">Other Contact Number<span></span></div>
					<div class="val">
						<input type="text" class="input" maxlength="15" placeholder="Enter Other Contact Number" style="width:40%;"> 
						<div class="validation">
							<span style="display:block">Validation message in this Span</span>
						</div>
					</div>
					<!-- value Ends -->
				</li>
			</ul>
		</div>

		<!--
			=================
			Collection Detail
			=================
		-->
		<div class="card">
			<h2>Collection Details</h2>
			<ul class="form">
				<li>
					<div class="lbl">Collection / Supply Frequency<span></span></div>
					<div class="val">
						<input type="text" class="input" maxlength="50" placeholder="Enter Collection / Supply Frequency" style="width:40%;"> 
						<div class="validation">
							<span style="display:block">Validation message in this Span</span>
						</div>
					</div>
					<!-- value Ends -->
				</li>	

				<li>
					<div class="lbl">Collection / Supply Day<span></span></div>
					<div class="val">
						<input type="text" class="input" maxlength="50" placeholder="Enter Collection / Supply Day" style="width:40%;" > 
						<div class="validation">
							<span style="display:block">Validation message in this Span</span>
						</div>
					</div>
					<!-- value Ends -->	
				</li>

				<li>
					<div class="lbl">Credit Frequency / Day<span></span></div>
					<div class="val">
						<input type="text" class="input" maxlength="50" placeholder="Enter Credit Frequency / Day" style="width:40%;"> 
						<div class="validation">
							<span style="display:block">Validation message in this Span</span>
						</div>
					</div>
					<!-- value Ends -->	
				</li>
				
				<li>
					<div class="lbl">Credit Collection Day<span></span></div>
					<div class="val">
						<input type="text" class="input" maxlength="50" placeholder="Enter Credit Collection Day" style="width:40%;"> 
						<div class="validation">
							<span style="display:block">Validation message in this Span</span>
						</div>
					</div>
					<!-- value Ends -->
				</li>
			</ul>
		</div>

		<!--
			==================
			Additional Information
			==================
		-->
		<div class="card">
		 <ul class="form">
			<li>
				<div class="lbl">Additional Information<span></span></div>
				<div class="val">
					<textarea class="input " autocomplete="off" maxlength="500" placeholder="Enter Additional Information" style="width:70%;" ></textarea>
					<div class="validation">
						<span style="display:block">Validation message in this Span</span>
					</div>
				</div>
			</li>
			</div>
		</div>

		<!--
			========================
			Portal Login Credentials
			========================
		-->
		<div class="card">
			<h2>Portal Login Credentials</h2>
			<ul class="form">		
				<li>
					<div class="lbl">Email ID (Username)<span>*</span></div>
					<div class="val">
						<div class="inputLoaderWrapper" style="width:40%; ">
							<input autocomplete="off" type="text" class="input imp" maxlength="50" placeholder="Enter Email" >
							<div class="inputLoader" style="display:none" id="emailIdLoader"> 
								<span class="spinLoader small"></span> 
							</div>
						</div>
						<div class="validation">
							<span style="display:block">Validation message in this Span</span>
						</div>
					</div>
					<!-- value Ends -->
				</li>
				
				<li>
					<div class="lbl">Password<span>*</span></div>
					<div class="val">
						<div class="passwordField" style="width:40%;">
						<input autocomplete="off" type="password" class="input imp" maxlength="20" placeholder="Enter Password">
						<a href="javascript:;" id="passwordEye"> 
							<span id="showPass" style="display:none;">
								<img src="images/eye_active.png">
							</span> 
							<span id="hidePass">
								<img src="images/eye_inactive.png">
							</span> 
						</a>
					</div>
					<div class="validation">
							<span style="display:block">Validation message in this Span</span>
						</div>
					</div>
					<!-- value Ends -->
				</li>
				
				<li>
					<div class="lbl"></div>
					<div class="label">
						<label class="checkbox">							
							<input type="checkbox" id="checkSendEmail" name="checkSendEmail" value="0"> 
							<span class="checkmark"></span>Send email notification for login details
						</label>				
					</div>
					
				</li>

			</ul>
		</div>

		<div class="submitBtn submit" style="display: inline-block; margin-left: calc(250px + 20px);">
			<div class="btnLoader" style="display:block;">
				<span class="spinLoader"></span>
			</div>
			<input value="Add Button" class="btn" type="button">
		</div>
	</form>	
	<!--
		========
		Form End
		========
	-->
  
</section>
<!--
	================
	Page Content End
	================
-->

<!-- 
	============
	Footer Start
	============
-->
<?php require_once ('inc/php/footer.php'); ?>
</body>
</html>
