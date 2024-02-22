<?php
$cssVersion = 1.0
?>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=1">

<!--For Favicon-->
<link rel="icon"  type="image/png"  href="">

<!-- #Include FontAwsome Css 
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">-->
	
<!-- @Include Defult Theme Css 
<link rel="stylesheet"  type="text/css" href="inc/css/theme.css?v=<?php echo $cssVersion ?>" />
<link rel="stylesheet"  type="text/css" href="inc/css/widget.css?v=<?php echo $cssVersion ?>" />
<link rel="stylesheet" type="text/css" href="inc/css/style.css?v=<?php echo $cssVersion ?>" />-->

<!-- @Include Responsive Css 
<link rel="stylesheet" type="text/css" href="inc/css/style_responsive.css?v=<?php echo $cssVersion ?>" />-->

<link href="inc/css/minified.css.php?v=<?php echo $cssVersion ?>" rel="stylesheet">


<!-- #Include Jquery Min -->
<script src="inc/js/jquery.min.js" type="text/javascript"></script>


<script type="text/javascript">	
	
	// #Create Html 5 Element for Backward Browser compatiable
	document.createElement("header");
	document.createElement("footer");
	document.createElement("nav");
	document.createElement("menu");
	document.createElement("article");
	document.createElement("section");
	document.createElement("aside");
	
	var currentWindowWidth;
	$(document).ready(function()
	{
		currentWindowWidth = $(window).width();
		// switchery CLICK EVENT
		/*
		$("a.switchery").click(function(){
			$(this).toggleClass("active");
		});
		*/
		
		/* == For Responsive Table ==== */	
		var hasResponsiveTable = $(".responsiveTable").length;
		//alert(hasResponsiveTable);
		if (hasResponsiveTable != 0)
		{
			var head_col_count = $('.responsiveTable table thead tr th').size();
			for (j=1; j <= head_col_count; j++)
			{			
				var head_col_label = $('.responsiveTable table thead th:nth-child('+ j +')').html();

				$('.responsiveTable table tr td:nth-child('+ j +')').replaceWith(
				function()
				{
					return $('<td data-title="'+ head_col_label +'">').append($(this).contents());				
				}
				);
			}
		}
		
	});
	
	function responsiveTable(){
		var head_col_count = $('.responsiveTable table thead tr th').size();	
		for (j=1; j <= head_col_count; j++)
		{			
			var head_col_label = $('.responsiveTable table thead th:nth-child('+ j +')').html();

			$('.responsiveTable table tr td:nth-child('+ j +')').replaceWith(
			function()
			{
				return $('<td data-title="'+ head_col_label +'">').append($(this).contents());				
			}
			);
		}
	}
	
	
	// ===>> Device Mobile = Menu Show Hide
    function openMenu()
	{	
		if(currentWindowWidth > 1050)
		{
			$("main").toggleClass("collapseMenu");
			$(".navMenu").toggleClass("collapse");
		}
		else
		{
			$(".navMenu").toggleClass("activeView");
			$("#menuOverlay").toggle();			
		}
	}
	
	function toggleSearch(){
		$(".pageSearch").toggleClass("activeView");
	}
	
</script>