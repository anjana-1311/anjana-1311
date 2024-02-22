 <?php 
	require_once 'inc/php/functions.php';
?>
<!doctype html>
<html class="no-js">
<head>
	<title>Widgets</title>  
	<?php require_once ('inc/php/head.php'); ?>	
	<script type="text/javascript">
	
		function openModal(){
			
			$("#overlayId").show();
			$("#modelId").show();
		}
		
		function closeModal(){
			
			$("#overlayId").hide();
			$("#modelId").hide();
		}
	
	</script>	
</head>
<body>

<!-- 
	=============== 
	Ligt Model Box :: 
	=============== 
-->
<div class="overlay" id="overlayId" style="display:none;"></div>
<div class="model" id="modelId" style="display:none;">
<div class="modelWrapper" style="width:700px">    
	
	<h4>
		<strong>Modal Title</strong>
		<a href="javascript:;" class="close" id="closePopup" onclick="closeModal();"></a>
	</h4>
	
	<div class="modelContent" style="padding:25px;">
		<h1>Notes >></h1>
		<ol style="font-size:15px;">
			<li style="padding-bottom:15px;">
				<strong>Modal Scrollable ::</strong> <br>
				- By Default modal is fixed<br>
				- Add ".scroll" class in ".model" Div  if you want model to scroll
				
			</li>
			
			<li style="padding-bottom:15px;">
				<strong>Modal width :: </strong>  <br>
				- You can change width in ".modelWrapper" div style tag
			</li>
			
			<li style="padding-bottom:15px;">
				<strong>Modal Content Pedding: : </strong>  <br>
				- You can add padding in ".modelContent" divs style tag as you want<br>
				- By Default ".modelContent" has no padding
			</li>
			
			<li style="padding-bottom:15px; color:red;">
				<strong>Most Important :: </strong>  <br>
				- Modal HTML has to add just after body tag start <br>
				- And above header.php include
			</li>
		</ol>
	</div>		
	
</div>
</div>

<!-- =============== Header Start -->
<?php require_once ('inc/php/header.php'); ?>

<!-- =============== Page Title -->
<section class="pageTitle">
	
	<h1>Widgets</h1>	
    <div class="clr"></div>
	
</section> <!-- propertySearch Ends -->

<!-- =============== Page Content Start -->
<section class="pageContent widget">

	<!-- 
		====================
		Basic Alerts
		=====================
	-->
	<div class="alert alert-success">
		<img src="images/success-tik.svg" />
		<strong>Success!</strong>
		Message has been sent.
		<button type="button" class="close h-100 btn"><span><i class="fa fa-times"></i></span></button>
	</div>	
	
	<div  class="alert alert-info">			
		<img src="images/info_i.svg" />
		<strong>Info!</strong> 
		You have got 5 new email.
		<button type="button" class="close h-100 btn"><span><i class="fa fa-times"></i></span></button>
	</div>
	
	<div  class="alert alert-warning">			
		<img src="images/warning_ico.svg" />
		<strong>Warning!</strong> 
		Warning message here..
		<button type="button" class="close h-100 btn"><span><i class="fa fa-times"></i></span></button>
	</div>
	
	<div class="alert alert-error">			
		<img src="images/error_x.svg" />
		<strong>Error!</strong> 
		Message sending failed.
		<button type="button" class="close h-100 btn"><span><i class="fa fa-times"></i></span></button>
	</div>
	
	<!-- 
		====================
		Buttons
		=====================
	-->
	<section class="card">
		<h2>Achor tag Buttons</h2>
		<p>All Button have <strong>loader div</strong> by default it is hide</p>
		
		<!-- ==== -->
		<span class="button green">
			<div class="btnLoader" style="display:none;">
				<span class="spinLoader small"></span>
			</div>	
			<a href="#">Button</a>
		</span>
		
		<!-- ==== -->
		<span class="button green border">
			<div class="btnLoader" style="display:none;">
				<span class="spinLoader small"></span>
			</div>	
			<a href="#">Button</a>
		</span>
		
		<!-- ==== -->
		<span class="button blue">
			<div class="btnLoader" style="display:none;">
				<span class="spinLoader small"></span>
			</div>	
			<a href="#">Button</a>
		</span>
		
		<!-- ==== -->
		<span class="button blue border">
			<div class="btnLoader" style="display:none;">
				<span class="spinLoader small"></span>
			</div>	
			<a href="#">Button</a>
		</span>
		
		<!-- ==== -->
		<span class="button red">
			<div class="btnLoader" style="display:none;">
				<span class="spinLoader small"></span>
			</div>	
			<a href="#">Button</a>
		</span>
		
		<!-- ==== -->
		<span class="button red border">
			<div class="btnLoader" style="display:none;">
				<span class="spinLoader small"></span>
			</div>	
			<a href="#">Button</a>
		</span>
		
		<!-- ==== -->
		<span class="button yellow">
			<div class="btnLoader" style="display:none;">
				<span class="spinLoader small"></span>
			</div>	
			<a href="#">Button</a>
		</span>
		
		<!-- ==== -->
		<span class="button yellow border">
			<div class="btnLoader" style="display:none;">
				<span class="spinLoader small"></span>
			</div>	
			<a href="#">Button</a>
		</span>
		
		<!-- ==== -->
		<span class="button orrange">
			<div class="btnLoader" style="display:none;">
				<span class="spinLoader small"></span>
			</div>	
			<a href="#">Button</a>
		</span>
		
		<!-- ==== -->
		<span class="button orrange border">
			<div class="btnLoader" style="display:none;">
				<span class="spinLoader small"></span>
			</div>	
			<a href="#">Button</a>
		</span>
		
		
		
	</section>
	
	<!-- 
		====================
		Light Box Modal
		=====================
	-->
	<section class="card">
		<h2>Light Box Modal</h2>
		<p>Copy the function and html of the modal</p>
		<a href="javascript:;" onclick="openModal()">Click here to Open</a>
	</section>
	
	<!-- 
		====================
		Form
		=====================
	-->
	<section class="card">
		<h2>Form Fields </h2>
		<p>You have to copy form field structure.</p>
		<ul class="form">
			
			<li>
				<div class="lbl">Textbox<span>*</span></div>
				<div class="val">
					
					<input type="text" class="input " placeholder="Textbox Placeholder" style="width:40%;"/> 
					
					<!-- Add Filed Notes -->
					<div class="inputNotes">
						<a class="ico"><i class="fa  fa-question-circle"></i></a>
						<div class="notes">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</div>
					</div>
					
					
					<div class="validation"><span style="display:block;">Validation message in this span</span> </div>
				</div>
			</li>
			
			<li>
				<div class="lbl">Textbox with loader<span>*</span></div>
				<div class="val">
					
					<div class="inputLoaderWrapper" style="width:40%;">
						<input type="text" class="input" placeholder="Placeholder">						
						<div class="inputLoader" style="display:block;">
							<span class="spinLoader small"></span>
						</div>					
					</div>
					
					<div class="validation"><span style="display:block;">Validation message in this span</span> </div>
				</div>
			</li>
			
			<li>
				<div class="lbl">Dropdown<span>*</span></div>
				<div class="val">
					<select class="input" style="width:40%;">
						<option>Select Option</option>
					</select>					
					<div class="validation"><span style="display:block;">Validation message in this span</span> </div>
				</div>
			</li>
			
			<li>
				<div class="lbl">Dropdown with loader<span>*</span></div>
				<div class="val">
					
					<div class="inputLoaderWrapper" style="width:40%;">
						<select class="input">
							<option>Select Option</option>
						</select>					
						<div class="inputLoader" style="display:block;">
							<span class="spinLoader small"></span>
						</div>					
					</div>
					
					<div class="validation"><span style="display:block;">Validation message in this span</span> </div>
				</div>
			</li>
			
			<li>
				<div class="lbl">Textarea<span>*</span></div>
				<div class="val">
					<textarea class="input" placeholder="Textarea Placeholder" style="width:70%;"></textarea>
					<div class="validation"><span style="display:block;">Validation message in this span</span> </div>
				</div>
			</li>
			
			<li>
				<div class="lbl">Singel Select Suugestive<span>*</span></div>
				<div class="val">
					<div class="multiSuggestion" style="width:40%;">
						<div class="inputLoader" style="display: block;">
							
							<span class="spinLoader small"></span>
						</div>
						<input type="text" class="input " placeholder="Enter * to View All Region">	
						<div class="suggestion">
							bind suugestive search in this div
						</div>						
					</div>					
					<div class="validation"><span style="display:block;">Validation message in this span</span> </div>
				</div>
			</li>
			
			<li>
				<div class="lbl">Multiple Select Suugestive<span>*</span></div>
				<div class="val">
					
					<div class="multiSuggestion" style="width:40%;">
						<a href="javascript:;"class="remove" style="display:block;"></a>
						<div class="inputLoader" style="display: block;">
							<span class="spinLoader small"></span>
						</div>
						
						<input type="text" class="input" placeholder="Enter * to view all options">
						
						<div class="selectedValue"> 
							<span>Option 1<a href="javascript:;"  class="remove"></a></span>
							<span>Option 2<a href="javascript:;" class="remove"></a></a></span>
						</div>
						
						<div class="suggestion">
							bind suugestive search in this div
						</div>
					</div>	
					
					<div class="validation"><span style="display:block;">Validation message in this span</span> </div>
				</div>
			</li>
			
			<li>
				<div class="lbl">Username<span>*</span></div>
				<div class="val">
					
					<div class="inputLoaderWrapper">
						<input type="text" class="input imp" placeholder="Username">						
						<div class="inputLoader" style="display:block;">
							<span class="spinLoader small"></span>
						</div>					
					</div>
					<div class="validation"><span style="display:block;">Validation message in this span</span> </div>
				</div>
			</li>
			
			<li>
				<div class="lbl">Password<span>*</span></div>
				<div class="val">
				
					<div class="passwordField" style="width:40%;">
						<input type="password" class="input imp" placeholder="Enter Password">
						<a href="javascript:;" id="eye"> 
							<span id="showPass" style="display:none;"><img src="images/eye_active.png"></span> 
							<span id="hidePass"><img src="images/eye_inactive.png"></span>
						</a>
						
						<div class="inputLoader" style="display:block;">
							<span class="spinLoader small"></span>
						</div>	
					</div>	
					
					<div class="validation"><span style="display:block;">Validation message in this span</span> </div>
				</div>
			</li>
			
			<li>
				<div class="lbl">Date Field<span>*</span></div>
				<div class="val">
					<div class="dateSelections" style="width:40%;">
						<input type="text" class="input" placeholder="Placeholder"> 						
					</div>				
					<div class="validation"><span style="display:block;">Validation message in this span</span> </div>
				</div>
			</li>
			
			<li>
				<div class="lbl">Date Field With Loader<span>*</span></div>
				<div class="val">
				
					<div class="dateSelections" style="width:40%;">
						<input type="text" class="input" placeholder="Placeholder"> 
						<div class="inputLoader" style="display:block;">
							<span class="spinLoader small"></span>
						</div>	
					</div>	
					
					<div class="validation"><span style="display:block;">Validation message in this span</span> </div>
				</div>
			</li>
			
			<li>
				<div class="lbl">Time Field<span>*</span></div>
				<div class="val">
					<div class="timeSelections" style="width:40%;">
						<input type="text" class="input" placeholder="Placeholder"> 						
					</div>				
					<div class="validation"><span style="display:block;">Validation message in this span</span> </div>
				</div>
			</li>
			
			<li>
				<div class="lbl">Time Field With Loader<span>*</span></div>
				<div class="val">
					<div class="timeSelections" style="width:40%;">
						<input type="text" class="input" placeholder="Placeholder"> 
						<div class="inputLoader" style="display:block;">
							<span class="spinLoader small"></span>
						</div>						
					</div>				
					<div class="validation"><span style="display:block;">Validation message in this span</span> </div>
				</div>
			</li>
			
			<li>
				<div class="lbl">Radio Horizontal Inline<span>*</span></div>
				<div class="val">
				
					<div class="horizontalField">
						<label class="radioBtn">
							<input type="radio"><span class="checkmark"></span>Radio 1
						</label>
						
						<label class="radioBtn">
							<input type="radio"><span class="checkmark"></span>Radio 2
						</label>
						
						<label class="radioBtn">
							<input type="radio"><span class="checkmark"></span>Radio 3
						</label>
					</div>
					
					<div class="validation"><span style="display:block;">Validation message in this span</span> </div>
				</div>
			</li>
			
			<li>
				<div class="lbl">Radio Vertical<span>*</span></div>
				<div class="val">
					<div class="verticalField">
						<label class="radioBtn">
							<input type="radio"><span class="checkmark"></span>Radio 1
						</label>
						
						<label class="radioBtn">
							<input type="radio"><span class="checkmark"></span>Radio 2
						</label>
						
						<label class="radioBtn">
							<input type="radio"><span class="checkmark"></span>Radio 3
						</label>
					</div>
					
					<div class="validation"><span style="display:block;">Validation message in this span</span> </div>
				</div>
			</li>
			
			<li>
				<div class="lbl">Checkbox Horizontal Inline<span>*</span></div>
				<div class="val">
				
					<div class="horizontalField">
						<label class="checkbox">
							<input type="checkbox"><span class="checkmark"></span>Checkbox 1
						</label>
						
						<label class="checkbox">
							<input type="checkbox"><span class="checkmark"></span>Checkbox 2
						</label>
						
						<label class="checkbox">
							<input type="checkbox"><span class="checkmark"></span>Checkbox 3
						</label>
					</div>
					
					<div class="validation"><span style="display:block;">Validation message in this span</span> </div>
				</div>
			</li>
			
			<li>
				<div class="lbl">Checkbox Vertical<span>*</span></div>
				<div class="val">
				
					<div class="verticalField">
						<label class="checkbox">
							<input type="checkbox"><span class="checkmark"></span>Checkbox 1
						</label>
						
						<label class="checkbox">
							<input type="checkbox"><span class="checkmark"></span>Checkbox 2
						</label>
						
						<label class="checkbox">
							<input type="checkbox"><span class="checkmark"></span>Checkbox 3
						</label>
					</div>
					
					<div class="validation"><span style="display:block;">Validation message in this span</span> </div>
				</div>
			</li>
			
			<li>
				<div class="lbl">Segment Controll<span>*</span></div>
				<div class="val">
				
					<div class="segment flexi">
						<label>
							<input type="radio">
							<span class="selection">Value 1</span>
						</label>
						
						<label>
							<input type="radio">
							<span class="selection">Value 2</span>
						</label>
						
						<label>
							<input type="radio">
							<span class="selection">Value 3</span>
						</label>	
						
						<label>
							<input type="radio">
							<span class="selection">Value 4</span>
						</label>						
					</div>
					
					<div class="validation"><span style="display:block;">Validation message in this span</span> </div>
				</div>
			</li>
			
			
			<li>
				<div class="lbl">Switch<span>*</span></div>
				<div class="val">					
					<input type="checkbox" class="switch" />					
					<div class="validation"><span style="display:block;">Validation message in this span</span> </div>
				</div>
			</li>
			
			
			<li>
				<div class="lbl">Textbox Max Value<span>*</span></div>
				<div class="val">
					
					<div class="inputMaxValue" style="width:40%;">
						<input type="text" class="input" placeholder="Placeholder">						
						<span class="max">10</span>				
					</div>
					
					<div class="validation"><span style="display:block;">Validation message in this span</span> </div>
				</div>
			</li>
			
			<li>
				<div class="lbl">Textarea Max Value<span>*</span></div>
				<div class="val">
					
					<div class="textareaMaxValue" style="width:60%;">
						<textarea class="input" placeholder="Textarea Placeholder"></textarea>	
						<span class="max">500 charecter</span>				
					</div>
					
					<div class="validation">
					<span style="display:block;">Validation message in this span</span></div>
				</div>
			</li>
			
			<li>
				<div class="lbl">Upload Image<span>*</span></div>
				
				<div class="val"> 
					
					<div class="importFile">					
						<div class="dropArea" id="importFileDropArea"> 	<!-- DropArea Start -->
							<div class="dropLoader" style="display:none;" id="dropFileLoader">
								<span class="spinLoader small"></span>
							</div>
											
							<input type="file" ref="file" accept=".xls,.xlsx,.csv" name="uploaded_file" id="uploaded_file" />
							
							<div class="element elementMob">
								<img src="images/excel.png" />
								<h5>Drag & Drop File Here</h5>
								<p><strong>OR</strong></p>
								<span class="button green border">
									<a>Browse</a>
								</span>								
							</div>
					
							<div class="element text">
								<p>
									- Accept .XLS, .XLSX, .CSV format<br>
									- Minimum image size 300px X 300px<br>
									- Maximum image size 800px X 800px<br>
									- Max image resolution 1MB
								</p>
								<div class="validation">
									<span>Validation message in this span</span>
								</div>
							</div>		

						</div>	<!-- Drop Area End -->

						<div class="dropPreview" id="dropPreview"> <!-- Drop Preview Start	-->
							<div class="extExcelDiv" id="extExcelDiv">
								<img src="images/allan-profile-pic.png"/>
							</div>
								
							<div class="fileName">
								<span id="uploadFileName">import_request_file.csv</span>
								<a href="javascript:;">Remove</a>
							</div>
						</div> <!-- Drop Preview End -->						
					</div>
					
				</div>
			</li>
			
		</ul>
	</section>
	
	<!-- 
		====================
		Submit Buttons
		=====================
	-->
	<section class="card">
		<h2>Input type Submit / Button With Loader</h2>
		<p></p>
		
		<div class="submitBtn">
			<div class="btnLoader" style="display:block;">
				<span class="spinLoader"></span>
			</div>
			<input value="Add Role" class="btn" type="submit">
		</div>
	</section>
	
	
	
	
</section> <!-- pageContent Ends -->


<!-- =============== Footer Start -->
<?php require_once ('inc/php/footer.php'); ?>
</body>
</html>