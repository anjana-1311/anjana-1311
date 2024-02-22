<!-- 
    Created by Khyati panchal 25 Feb 2022
    file name : supplier_form.php
 -->
<!-- form stat -->
<form id="addSupplierForm" name="addSupplierForm" method="post">
    <div class="card">
        <ul class="form">
            <li>
                <div class="lbl">Name<span>*</span></div>
                <div class="val">
                    <input autocomplete="off" type="text" class="input " placeholder="Enter Name" id="txtName" name="txtName" onkeypress="hideErrorMessage('errorName');" value='' />
                    <div class="validation">
                        <span style="display:none;" id="errorName"></span>
                    </div>
                </div>
                <!-- value Ends -->
            </li>
            <li>
                <div class="lbl">Select Type<span>*</span></div>
                <div class="val">
                    <div class="inputLoaderWrapper" style="width:40%;">
                        <select class="input" id="txtTypeId" name ="txtTypeId" onchange="hideErrorMessage('errorSelectType')"> 
                            <option id="" value="">Select Type</option>
                            <?php 
                                foreach($supplierTypeArray as $key=>$value)
                                {
                                    $displayValue = str_replace('_',' ', $value);
                            ?>
                            <option id="<?php echo $key; ?>" value="<?php echo $value; ?>"><?php echo ucfirst($displayValue); ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="validation">
                        <span style="display:none;" id="errorSelectType"></span> 
                    </div>
                </div>
                <!-- value Ends -->
            </li>
            <li>
                <div class="lbl">Address<span></span></div>
                <div class="val">
                    <textarea id='txtAddress' name='txtAddress'></textarea>
                    <div class="validation">
                        <span style="display:none;" id="errorAddress"></span>
                    </div>
                </div>
                <!-- value Ends -->
            </li>
            <li>
                <div class="lbl">Select Country<span></span></div>
                <div class="val">
                    <div class="inputLoader" style="display:none">
                        <span class="spinLoader small"></span>
                    </div>

                    <select class="input" id='txtCountry' name='txtCountry' onchange="fetchState(this.value);">
                        <option>Country</option>
                        <?php
                            foreach($countryData as $countryData)
                            {
                            ?>
                                <option value="<?php echo $countryData['id'];?>"
                                <?php  
                                if($countryId == $countryData['id'])
                                {
                                    echo "selected='selected'";
                                } 
                                ?>> <?php echo $countryData['name'];?></option>

                            <?php
                            } // end foreach
                        ?>
                    </select>

                    <div class="validation">
                        <span id="errorCountry"></span>
                    </div>
                </div>
        </li>

        <li>
            <div class="lbl">Select State<span></span></div>
            <div class="val">
                <!--State -->
                <div class="inputLoader" id="stateLoader" style="display: none;">
                        <span class="spinLoader small"></span>
                </div>

                <select class="input" id='txtState' name='txtState' onchange="fetchCity(this.value);">
                        <option value="">State</option>
                </select>

                <div class="validation">
                        <span id="errorState"></span>
                </div>
            </div>
        </li>	

        <li>
            <div class="lbl">Select City<span></span></div>
            <div class="val">
                <!--City -->
                <div class="inputLoader" id="cityLoader" style="display: none;">
                        <span class="spinLoader small"></span>
                </div>

                <select class="input" id='txtCity' name='txtCity'>
                        <option value="">Select City</option>
                </select>

                <div class="validation">
                        <span id="errorCity"></span>
                </div>
            </div>	
            </li>
            <li>
                <div class="lbl">Contact Person Name<span></span></div>
                <div class="val">
                    <input autocomplete="off" type="text" class="input " placeholder="Enter Contact Person Name" id="txtContactPersonName" name="txtContactPersonName" value='' />
                    <div class="validation">
                        <span style="display:none;" id="errorContactPersonName"></span>
                    </div>
                </div>
                <!-- value Ends -->
            </li>
            <li>
                <div class="lbl">Mobile Number<span></span></div>
                <div class="val">
                    <input autocomplete="off" type="text" class="input " placeholder="Enter Mobile Number" id="txtMobileNumber" name="txtMobileNumber" onkeypress="hideErrorMessage('errormobileNumber');" value='' maxlength='10'/>
                    <div class="validation">
                        <span style="display:none;" id="errormobileNumber"></span>
                    </div>
                </div>
                <!-- value Ends -->
            </li>
            <li>
                <div class="lbl">Email Id<span></span></div>
                <div class="val">
                    <input autocomplete="off" type="email" class="input " placeholder="Enter Email Id" id="txtEmailId" name="txtEmailId" onkeypress="hideErrorMessage('errorEmail');" value='' />
                    <div class="validation">
                        <span style="display:none;" id="errorEmail"></span>
                    </div>
                </div>
                <!-- value Ends -->
            </li>
            <li>
                <div class="lbl">Other Contact Detail (Comma seprated Mobile numbers)<span></span></div>
                <div class="val">
                    <input autocomplete="off" type="email" class="input " placeholder="Enter Contact Number" id="txtContactNumbers" name="txtContactNumbers" onkeypress="javascript:hideErrorMessage('errorContactNumbers');"  value='' />
                    <div class="validation">
                        <span style="display:none;" id="errorContactNumbers"></span>
                    </div>
                </div>
                <!-- value Ends -->
            </li>
        </ul>
    </div>

    <div class="submitBtn submit" style="display:inline-block; margin-left:calc(250px + 20px);">
        <div class="btnLoader" id="addSupplierLoader" style="display:none;">
            <span class="spinLoader"></span>
        </div>
        <!-- hiddens -->
        <input type="hidden" name="action" value="<?php echo $formAction ?>">
        <input type="hidden" id="hdnSupplierId" name="hdnSupplierId" value="<?php echo $hdnSupplierId; ?>">

        <input value="<?php echo ucfirst($formAction); ?> Supplier" class="btn" type="button" id="addSupplier" name="addSupplier"  onclick="return addSupplierValidation();">
    </div>

</form>
<!-- form end -->