<main>

<?php require_once ('inc/php/menu.php'); ?>

<!-- 
    =============================
    contentWrapper:
    =============================
-->
<section class="contentWrapper">

	<!-- ========= Menu Icon -->
	<a href="javascript:;" class="menuIcon" onclick="openMenu()">
		<span></span><span></span><span></span>
	</a>
	
	
	<?php
	//if ($isDevice == 'desktop')
	//{
	?>
		<!-- ========= Login user -->
		<a class="loggedInUser">
			<label>Joh nDoe</label>
			<strong>Admin</strong>
			<span>				
				<img src="images/default_user_avtar.jpg">
			</span>
		</a>
	<?php
	//}
	?>
