<section class="navMenu">
	
	<div class="clientLogo">
		<a href="dashboard.php">
			<img id="full" src="images/logo.png" />
			<img id="intial" src="images/logo-intial.png" style="display:none;" />
		</a>
	</div>
	
	
	<!-- ========= Login user 
	<div class="loggedInUser">
		<label>John Doe</label>
		<strong>Admin</strong>
		<span>
			<img src="images/default_user_avtar.jpg">
		</span>
	</div>-->
	
	
	<section class="menuWrapper">
	<nav>

            <ul>
                <li>
                    <a href="dashboard.php" class="dashboardBtn">
                        <i class="dashboardIcon"></i>
                        <span>Dashboard</span>
                    </a>
                </li>


                <!-- ========== -->
                <li class="sap"><h6>Templates</h6></li>
                <li>
                    <a href="template_listing.php">
                        <i class="iconClass"></i>
                        <span>Listing Page</span>
                    </a>
                </li>

                <li>
                    <a href="template_add_form.php">
                        <i class="iconClass"></i>
                        <span>Add Form</span>
                    </a>
                </li>

                <li>
                    <a href="template_view_detail.php">
                        <i class="iconClass"></i>
                        <span>View Page</span>
                    </a>
                </li>

                <li>
                    <a href="widget.php">
                        <i class="iconClass"></i>
                        <span>Widget</span>
                    </a>
                </li>




                <!-- ========== -->
                <li class="sap"><h6>Other</h6></li>    

                <li>
                    <a >
                        <i class="myProfileIcon"></i>
                        <span>My Profile</span>
                    </a>
                </li>
                <li>
                    <a href="index.php">
                        <i class="logoutIcon"></i>
                        <span>Logout</span>
                    </a>
                </li>
                
                
                <!-- ========== -->
                <li class="sap"><h6>Settings</h6></li>    

                <li>
                    <a href='category.php'>
                        <i class="myProfileIcon"></i>
                        <span>Category</span>
                    </a>
                </li>
                <li>
                    <a href="department.php">
                       <i class="myProfileIcon"></i>
                        <span>Department</span>
                    </a>
                </li>
                <li>
                    <a href="supplier.php">
                       <i class="myProfileIcon"></i>
                        <span>Supplier</span>
                    </a>
                </li>
                <li>
                    <a href="insurance.php">
                       <i class="myProfileIcon"></i>
                        <span>Insurance</span>
                    </a>
                </li>
                <li>
                    <a href="contract.php">
                       <i class="myProfileIcon"></i>
                        <span>Contract</span>
                    </a>
                </li>
            </ul>
		
	</nav>
	</section>
	
	<!--
	<div class="menuFooter">
		<p>Powered by</p>
		<img src="images/menu_bottom_logo-full.png" class="full" />
		<img src="images/menu_bottom_logo-intial.png" class="initial" />
	</div>
	-->

</section> <!-- navMenu Ends -->

<div class="overlay" id="menuOverlay" style="display:none;" onClick="openMenu()"></div>
