<?php 
require_once("inc/php/functions.php");
?>

<!doctype html>
<html class="no-js">
<head>
	<title>Dashboard</title> 
	    
	<?php require_once ('inc/php/head.php'); ?>	
	<script type="text/javascript"></script>	
	
</head>
<body class="pageDashboard">

<!-- =============== Header Start -->
<?php require_once ('inc/php/header.php'); ?>

<!-- 
    ===================
    Page Title
    ===================
-->
<section class="pageTitle">
    <h1>Dashboard</h1>
</section>
<!-- 
    ===================
    Page Content
    ===================
-->
<section class="pageContent">
    
    <!--
        ========
        Gradient Bar Chart
        ========
    -->
    <section class="gradientBarChart">
        
		<div class="graphTabs">
            <a id="wtdSelected" href="javascript:;" onclick="getBarChartAuditData('wtd');" class="active">WTD</a>
            <a id="mtdSelected" href="javascript:;" onclick="getBarChartAuditData('mtd');">MTD</a>
            <a id="qtdSelected" href="javascript:;" onclick="getBarChartAuditData('qtd');">QTD</a>
            <a id="ytdSelected" href="javascript:;" onclick="getBarChartAuditData('ytd');">YTD</a>
        </div>
        
		Graph herer...
		
	</section>
	
	
	sdsasdasd
	
</section> <!-- pageContent Ends -->
<!-- =============== Footer Start -->
<?php require_once ('inc/php/footer.php'); ?>
</body>
</html>