
<!doctype html>
<html class="no-js">
<head>
	<title>View Details</title>  
	<?php require_once ('inc/php/head.php'); ?>	

	<script type="text/javascript">
		
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
	==================
	Page Ttile Start
	==================
-->
<section class="pageTitle">
	<h1>View Details</h1>	
	<a href="" class="back">Back</a>	
	<div class="clr"></div>
</section>
<!--
	==================
	Page Ttile End
	==================
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
	<div class="alert alert-success" style="display:none;" id="successMessageDiv">
		<img src="images/success-tik.svg" />
		<strong>Success!</strong>
		<label id="successMessageLableId"></label>
	</div>
<!--
	===================
	Alert :: Error
	===================
-->		
	<div class="alert alert-error" style="display:none;" id="errorMessageDiv"> 			
		<img src="images/error_x.svg" />
		<strong>Error!</strong> 
		<label id="errorMessageLableId"></label>
	</div>
	
	<!--
		==================
		Business Information
		==================
	-->
	<div class="card" style="padding: 8px;">
		<div class="viewCard">
			<h2>Business Information</h2>
			<ul class="viewData" style="margin-bottom:0px;">
				<li><label>Business Name</label> <span id="txtBusinessName">-</span> </li>
				<li><label>Client Category</label> <span id="txtCategoryName">-</span></li>
				<li><label>Business Code</label> <span id="txtBusinessCode">-</span></li>
			</ul>	
		</div>	

		<!--
			==================
			Contact Details
			==================
		-->
		<div class="viewCard">
			<h2>Contact Details</h2>
			<ul class="viewData">
				<li><label>Owner / Partner's Name</label><span id="txtOwnerPartnerName">-</span></li>
				<li><label>Client Representative Name</label><span id="txtClientRepresentativeName">-</span> </li>
				<li><label>Client Representative Number</label><span id="txtClientRepresentativeNumber">-</span> </li>
				<li><label>Other Contat Number</label><span id="txtContactNumber">-</span></li>
			</ul>		
		</div>

		<!--
			==================
			Collection Details
			==================
		-->
		<div class="viewCard">
			<h2>Collection Details</h2>
			<ul class="viewData">
				<li><label>Collection / Supply Frequency</label><span id="txtCollectionSupplyFrequency">-</span> </li>
				<li><label>Collection / Supply Day</label><span id="txtCollectionSupplyDay">-</span> </li>
				<li><label>Credit Frequency / Day</label><span id="txtCreditFrequencyDay">-</span></li>
				<li><label>Credit Collection Day</label><span id="txtCreditCollectionDay">-</span></li>
			</ul>		
		</div>
		
		<!--
			==================
			Additional Information
			==================
		-->
		<div class="viewCard">
			<ul class="viewData">
				<li><label>Additional Information</label><span class="pre" id="txtAdditionalInformation">-</span> </li>
			</ul>
		</div>
		
		<!--
			========================
			Portal Login Credentials
			========================
		-->
		<div class="viewCard">
			<h2>Portal Login Credentials</h2>
			<ul class="viewData" style="margin-bottom:30px;">
				<li><label>Email ID (Username)</label> <span id="txtPortalUserName">-</span> </li>
				<li>
					<label class="">Password</label>
					<span>
						<div class="passwordField">
							<span id="txtPortalPasswordSecure" style="display:none; padding:0px;"></span>
							<span id="txtPortalPasswordNormal" style="display:none; padding:0px;"></span>
							<a href="javascript:;" id="passwordEye"> 
								<span id="showPass" style="display:none;">
									<img src="images/eye_active.png">
								</span> 
								<span id="hidePass">
									<img src="images/eye_inactive.png">
								</span> 
							</a>
						</div>
					</span>
				</li>
			</ul>	
		</div>
	</div>
</section> 
<!--
	==================
	Page Content End
	==================
-->

<!-- 
	=============== 
	Footer Start
	===============
-->
<?php require_once ('inc/php/footer.php'); ?>
</body>
</html>