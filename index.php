<?php

require_once("inc/php/functions.php");
?>

<!doctype html>
<html class="no-js">
<head>
	
    <title>Login</title>   
    <?php include("inc/php/head.php"); ?>		

    <link rel="stylesheet" type="text/css" href="inc/css/login.css?v=<?php echo $cssVersion ?>" />

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@900&display=swap" rel="stylesheet"> 

    <!-- #Page Functions -->
    <script type="application/javascript">
          
    </script>

</head>
<body class="accessSystem">

    <!--
            ===========
            Graphic
            ===========
    -->	
    <div class="graphic">
        <div class="label">
			<h1><span>Admin</span> Portal System</h1>
        </div>
    </div>
	
	
    <!-- ======= Form -->
    <div class="userForm">
		
        <!-- 
                ============== 
                User Login
                ============== 
        -->
		
        <div class="gradientBox" id="login" style="">
        <form id="frmLogin" name="frmLogin" method="post" autocomplete="off">

            <h3>Admin<span></span></h3>
            <h4>Sing-In to your account below</h4>
            
            
            <div class="alert alert-error" style="display:block;">			
                Message sending error.
            </div>
		
            <div  class="alert alert-warning" style="display:block;">	
				Message sending warning.			
            </div>
           

            <ul>
                <li>
				<div class="emailUsername">
                    <input type="email" autocomplete="off" placeholder="Email (Username)" tabindex="1" />					
                    <div class="validation">
                        <span style="display:block;">Validation message in this span</span>
                    </div>
				</div>
                </li>

                <li>
                    <div class="passwordField">
                        <input  class="password" autocomplete="off" type="password" placeholder="Password" value="" tabindex="2" autocomplete='off'/>
						<a href="javascript:;" id="eye">
                            <span id="showPass" style="display:none;"><img src="images/eye_active.png" /></span>
                            <span id="hidePass"><img src="images/eye_inactive.png" /></span>
                        </a>
					</div>
                    <div class="validation">
                       <span style="display:block;">Validation message in this span</span>
                    </div>
                </li>

                <li class="loginButtonPanel">
                    <div class="buttonLeft">
                        <label class="checkbox rememberMe">
                            <input type="checkbox" name="rememberMe" tabindex="3"> 
                            <span class="checkmark"></span>Remember Me
                        </label>
                        <a href="javascript:;" style="opacity:0.4; pointer-events:none;">Forgot Password?</a> 						
                    </div>					
                    <div class="submitBtn">
                        <div class="btnLoader" id="btnLoader" >
							<span class="spinLoader"></span>
                        </div>			
                        <input type="button" class="btn" tabindex="4" id="btnLoginSubmit" name="btnLoginSubmit" Value="Sign In"/>
                    </div>					
                </li>
            </ul>
            
        </form>
		
		
        
		</div>
	
        <!-- 
                ============== 
                Forgot Password
                ============== 
        -->
        <div class="gradientBox" id="forgotPassFormDiv" style="display:block;">
        <form method="post" name="userForgotPasswordForm" id="userForgotPasswordForm">

            <h3>Admin<span></span></h3>	
            <h4>Forgot Password?</h4>

            <div class="alert alert-error" style="display:block;">			
                Message sending error.
            </div>
		
            <div  class="alert alert-warning" style="display:block;">	
				Message sending warning.			
            </div>
            <ul>

                <li>
                    <input class="email" type="text" placeholder="Enter your email ID"/>
                    <div class="validation">
                        <span style="display:block;">Validation message in this span</span>
					</div>
                </li>

                <li id="resendEmail" style="display:block;">
                    <div class="btnLoader" style="display:block">
                        <span class="spinLoader small"></span>
                    </div>
                    <a href="javascript:;">Resend Email Verification</a>
                </li>

                <li class="loginButtonPanel">
                        <div class="buttonLeft">
							 <a href="javascript:;">Back to Login</a>
                        </div>

                         <div class="submitBtn">
                            <div class="btnLoader" style="display:block">
                                <span class="spinLoader"></span>
                            </div>                            
                            <input type="button"  Value="Submit" class="btn" onClick="return formValidationForForgotPassword();"/>
                        </div>
                </li>

            </ul>
        </form>
		
        </div>
		
		
    </div>

</body>
</html>
