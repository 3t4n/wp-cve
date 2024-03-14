<!-- 
title blue = #005b94 
heading blue = #4b8db4
logo orange = #f1511b
website orange = #ff5416
 -->

<form method="post" action="options.php">
<?php settings_fields( 'd8cf7-settings-group' );?>
<style>
	.switch {
		position: relative;
		display: inline-block;
		width: 60px;
		height: 34px;
	}

	.switch input { 
		opacity: 0;
		width: 0;
		height: 0;
	}

	.slider {
		position: absolute;
		cursor: pointer;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		background-color: #ccc;
		-webkit-transition: .4s;
		transition: .4s;
	}

	.slider:before {
		position: absolute;
		content: "";
		height: 26px;
		width: 26px;
		left: 4px;
		bottom: 4px;
		background-color: white;
		-webkit-transition: .4s;
		transition: .4s;
	}

	input:checked + .slider {
		background-color: #4b8db4;
	}

	input:focus + .slider {
		box-shadow: 0 0 1px #4b8db4;
	}

	input:checked + .slider:before {
		-webkit-transform: translateX(26px);
		-ms-transform: translateX(26px);
		transform: translateX(26px);
		.phoneOption{
			visibility: visible;
		}
	}

	/* Rounded sliders */
	.slider.round {
		border-radius: 34px;
	}

	.slider.round:before {
		border-radius: 50%;
	}
</style>
<table class="wp-list-table widefat fixed bookmarks">
    <thead>
        <tr style="background-color:#005b94; padding:10px;">
            <th style="font-size:18px; color:white;"><strong>Settings</strong></th>
        </tr>
    </thead>
    <tbody>
    <tr>
        <td>
            <table>
				<tr>
					<td>
						This plugin uses the Data8 Email Validation, Phone Validation, Unusable Name Validation, Bank Validation and PredictiveAddress&trade;
						services to ensure you are capturing valid contact details in Woocommerce, Gravity Forms, Contact Form 7, Elementor Pro and WPForms
						and improve the user experience. This requires credits for the relevant services. 
						</td>
				</tr>
				<tr>
					<td>
						Please enter your <a href="https://www.data-8.co.uk/dashboard/api-keys/" target="_blank">Data8 API keys below</a>. <strong>Haven't got a Data8 account yet? <a href="https://www.data-8.co.uk/ValidationAdmin/FreeTrial/ContactForm7" target="_blank">Get a free trial now</a></strong>
					</td>
				</tr>
			</table>
			<table class="form-table d8cf7_form" style="width:100%;">
                <tr valign="top">
                    <td style="padding:10px;"><strong><span style="color:red;">*</span> Data8 Server API Key</strong></td>
                    <td style="text-align:center; padding:10px; width:20%;">
                        <input type="text" name="d8cf7_ajax_key" style="width:180px; height:34px;" value="<?php echo esc_attr(get_option('d8cf7_ajax_key')); ?>" />
                    </td>
                </tr>
				<tr valign="top">
                    <td style="padding:10px;"><strong><span style="color:red;">*</span> Data8 Client API Key</strong></td>
                    <td style="text-align:center; padding:10px;">
                        <input type="text" name="d8cf7_client_api_key" style="width:180px; height:34px;" value="<?php echo esc_attr(get_option('d8cf7_client_api_key')); ?>" />
                    </td>
                </tr>	
		
				<?php
				if (get_option('d8cf7_error')) {
				?>
				<tr>
					<td class="notice notice-error">
						<?php echo esc_attr(get_option('d8cf7_error')); ?>
					</td>
				</tr>
				<?php } ?>
				<tr><td></td></tr>
				<tr style="background-color:#4b8db4; height:40px;">
					<td style="font-size:16px; color:white;"><strong>Address Capture Services</strong></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><strong>Use Data8 <a href="https://www.data-8.co.uk/services/data-validation/validation-services/address-validation" target="_blank">PredictiveAddress&trade;</a></strong></td>
					<td style="text-align:center;">
						<label class="switch">
							<?php if(get_option('d8cf7_predictiveaddress')){ ?>
								<input type="checkbox" name="d8cf7_predictiveaddress" id="d8cf7_predictiveaddress" value="true" checked="checked"/> 
							<?php }else { ?>
								<input type="checkbox" name="d8cf7_predictiveaddress" id="d8cf7_predictiveaddress" value="true"/> 
							<?php } ?>
							<span class="slider round"></span>
						</label>
					</td>
				</tr>
				<tr id="pa-options">
					<td><strong>PredictiveAddress Options</strong></td>
					<td style="text-align:center;">
						<textarea name="d8cf7_predictiveaddress_options" rows="10" columns="50" style="width: 100%;" spellcheck="false"><?php echo str_replace('\"', '"', get_option('d8cf7_predictiveaddress_options'))?></textarea>
					</td>
					<td style="text-align:left;"><div style="padding-bottom: 10px;">Enter any customisation options for PredictiveAddress here, for example:</div>
					<div>allowedCountries: ["GB", "US"]</div>
					<div>includeNYB: true</div>
					<div>includeMR: true</div>
					<div style="padding-top: 10px;"><a href="https://www.data-8.co.uk/resources/code-samples/predictiveaddress/" target="_blank">See the full list of options</a>.</div></td>
				</tr>
				<tr><td></td></tr>
				<tr style="background-color:#4b8db4;">
					<td style="font-size:16px; color:white;"><strong>Validation Services</strong></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td style="width:35%; padding:10px;"><strong>Data8 <a href="https://www.data-8.co.uk/services/data-validation/validation-services/email-validation" target="_blank">Email Validation</a> level</strong></td>
					<td style="width:15%; padding:10px; text-align:center;">
						<select name="d8cf7_email_validation_level" style="height:34px;">
						<?php if(get_option('d8cf7_email_validation_level') === "None" || null === (get_option('d8cf7_email_validation_level'))){ ?>
							<option name="None" value="None" selected>None</option>
						<?php }else { ?>
							<option name="None" value="None">None</option>
						<?php }
						if(get_option('d8cf7_email_validation_level') === "Syntax"){ ?>
							<option name="Syntax" value="Syntax" selected>Syntax (Lowest)</option>
						<?php }else { ?>
							<option name="Syntax" value="Syntax">Syntax (Lowest)</option>
						<?php }
						 if(get_option('d8cf7_email_validation_level') === "MX"){ ?>
							<option name="MX" value="MX" selected>Domain</option>
						<?php }else { ?>
							<option name="MX" value="MX">Domain</option>
						<?php }
						 if(get_option('d8cf7_email_validation_level') === "Server"){ ?>
							<option name="Server" value="Server" selected>Server</option>
						<?php }else { ?>
							<option name="Server" value="Server">Server</option>
						<?php }
						 if(get_option('d8cf7_email_validation_level') === "Address"){ ?>
							<option name="Address" value="Address" selected>Address (Highest)</option>
						<?php }else { ?>
							<option name="Address" value="Address">Address (Highest)</option>
						<?php } ?>
						</select>
					</td>	
					<?php if(get_option('d8cf7_email_validation_level') === "Syntax"){ ?>
						<td style="text-align:left;">The supplied email is checked to ensure that it meets the standard email address format.</td>
					<?php }else if(get_option('d8cf7_email_validation_level') === "MX"){ ?>
						<td style="text-align:left;">The supplied email is checked to ensure that the domain name (the part to the right of the @ sign) exists and is set up to receive email.</td>
					<?php }else if(get_option('d8cf7_email_validation_level') === "Server"){ ?>
						<td style="text-align:left;">In addition to the Domain level checks, Server level validation ensures that at least one of the mail servers advertised for the domain is actually live.</td>
					<?php }else if(get_option('d8cf7_email_validation_level') === "Address"){ ?>
						<td style="text-align:left;">In addition to the Server level checks, Address level validation ensures that the mail server accepts mail for the full email address.</td>
					<?php } else { ?>
						<td style="text-align:left;">No Data8 email validation will occur on your site</td>
					<?php } ?>
				</tr>
				<tr>
					<td><strong>Use Data8 <a href="https://www.data-8.co.uk/data-validation/phone-validation/" target="_blank">Phone Validation</a></strong></td>
					<td style="text-align:center;">
						<label class="switch">
							<?php if(get_option('d8cf7_telephone_validation')){ ?>
								<input type="checkbox" name="d8cf7_telephone_validation" id="d8cf7_telephone_validation" value="true" checked="checked"/> 
							<?php }else { ?>
								<input type="checkbox" name="d8cf7_telephone_validation" id="d8cf7_telephone_validation" value="true"/> 
							<?php } ?>
							<span class="slider round"></span>
						</label>
					</td>
				</tr>
				<tr>				
				 	<td style="padding-left:40px;"><strong>Default Country Code</strong></td>
					<td style="text-align:center; padding:10px;">
							<input type="text" name="d8cf7_telephone_default_country" id="d8cf7_telephone_default_country" style="width:150px; height:34px;" value="<?php echo esc_attr(get_option('d8cf7_telephone_default_country')); ?>" placeholder="44" />
					</td>
					<td style="text-align:left;">*Optional. Default = '44'. The ISO 2-character country code or international dialling code of the country to validate the telephoneNumber in, unless that number contains an explicit country code prefix.</td>
				</tr>
				<tr>				
				 	<td style="padding-left:40px;"><strong>Required Country</strong></td>
					<td style="text-align:center; padding:10px;">
						<input type="text" name="d8cf7_telephone_required_country" id="d8cf7_telephone_required_country" value="<?php echo esc_attr(get_option('d8cf7_telephone_required_country')); ?>" placeholder="i.e. 'GB' or 'US'." /> 
					</td>
					<td style="text-align:left;">*Optional. Indicates the country that the number must be in to be considered valid. This should be provided as the ISO 2-character country code.</td>
				</tr>
				<tr>				
				 	<td style="padding-left:40px;"><strong>Allowed Prefixes</strong></td>
					<td style="text-align:center; padding:10px;">
						<input type="text" name="d8cf7_telephone_allowed_prefixes" id="d8cf7_telephone_allowed_prefixes" value="<?php echo esc_attr(get_option('d8cf7_telephone_allowed_prefixes')); ?>" placeholder="i.e. '+441,+442'" /> 
					</td>
					<td style="text-align:left;">*Optional. A comma-separated list of prefixes in standard international format that the number must start with to be treated as valid. For example, use "+441,+442" to allow only standard UK landline numbers.</td>
				</tr>
				<tr>				
				 	<td style="padding-left:40px;"><strong>Barred Prefixes</strong></td>
					<td style="text-align:center; padding:10px;">
						<input type="text" name="d8cf7_telephone_barred_prefixes" id="d8cf7_telephone_barred_prefixes" value="<?php echo esc_attr(get_option('d8cf7_telephone_barred_prefixes')); ?>" placeholder="i.e. '+90,+447781'" /> 
					</td>
					<td style="text-align:left;">*Optional. A comma-separated list of prefixes in standard international format that will cause the number to be treated as invalid. For example, use "+90,+447781" to block any Indian numbers or numbers allocated to C&W Guernsey.</td>
				</tr>
				<tr>
					<td style="padding-left:40px;"><strong>Advanced Options</strong></td>
					<td style="text-align:center; padding:10px;">
						<textarea name="d8cf7_telephone_advanced_options" id="d8cf7_telephone_advanced_options" rows="5" columns="50" style="width: 100%;" spellcheck="false"><?php echo str_replace('\"', '"', get_option('d8cf7_telephone_advanced_options'))?></textarea>
					</td>
					<td style="text-align:left;"><div style="padding-bottom: 10px;">Enter any advanced options for Phone Validation here, for example:</div>
						<div>TreatUnavailableMobileAsInvalid: true</div>
						<div>ExcludeUnlikelyNumbers: true</div>
						<div style="padding-top: 10px;"><a href="https://www.data-8.co.uk/resources/code-samples/predictiveaddress/" target="_blank">See the full list of options</a>.</div></td>
				</tr>
				<tr>
					<td><strong>Use Data8 Unusable Name Validation</strong></td>
					<td style="text-align:center;">
						<label class="switch">
							<?php if(get_option('d8cf7_salaciousName')){ ?>
								<input type="checkbox" name="d8cf7_salaciousName" value="true" checked="checked"/> 
							<?php }else { ?>
								<input type="checkbox" name="d8cf7_salaciousName" value="true"/> 
							<?php } ?>
							<span class="slider round"></span>
						</label>
					</td>
				</tr>
				<tr>
					<td><strong>Use Data8 Bank Validation (WooCommerce Not Supported)</strong></td>
					<td style="text-align:center;">
						<label class="switch">
							<?php if(get_option('d8cf7_bank_validation')){ ?>
								<input type="checkbox" name="d8cf7_bank_validation" value="true" checked="checked"/> 
							<?php }else { ?>
								<input type="checkbox" name="d8cf7_bank_validation" value="true"/> 
							<?php } ?>
							<span class="slider round"></span>
						</label>
					</td>
				</tr>	
			</table>
            <br/>
			<br/>
            <p class="submit">
				<input type="submit" name="submit-d8cf7" class="button-primary" value="<?php _e('Save Changes') ?>" />
            </p>
            
        </td>
        
    </tr>
    </tbody>
</table>
<br/>
</form>
<script type="text/javascript">
if(window.jQuery){
    var $ = window.jQuery;

    $(document).ready(function() {
        data8SetupAnimations();
    });
}
else{
    if (document.readyState === "complete")
    data8SetupAnimations();
    else
        document.addEventListener("readystatechange", data8SetupAnimations);
}

function data8SetupAnimations(){
	var _telephoneValidation = document.getElementById("d8cf7_telephone_validation");
	
	_telephoneValidation.addEventListener('click', function (){
			showHidePhoneOptions();
	});

	function showHidePhoneOptions (){
		if($){
			var closestGroupCountry = $('#d8cf7_telephone_default_country').closest("tr");
			var closestGroupRequiredCountry = $('#d8cf7_telephone_required_country').closest("tr");
			var closestGroupAllowedPrefixes = $('#d8cf7_telephone_allowed_prefixes').closest("tr");
			var closestGroupBarredPrefixes = $('#d8cf7_telephone_barred_prefixes').closest("tr");
			var closestGroupAdvancedOptions = $('#d8cf7_telephone_advanced_options').closest("tr");

				if(_telephoneValidation.checked){
					closestGroupCountry.fadeIn("200");
					closestGroupRequiredCountry.fadeIn("200");
					closestGroupAllowedPrefixes.fadeIn("200");
					closestGroupBarredPrefixes.fadeIn("200");
					closestGroupAdvancedOptions.fadeIn("200");
				}
				else {
					closestGroupCountry.fadeOut("200");
					closestGroupRequiredCountry.fadeOut("200");
					closestGroupAllowedPrefixes.fadeOut("200");
					closestGroupBarredPrefixes.fadeOut("200");
					closestGroupAdvancedOptions.fadeOut("200");
				}
		}
		else{
			var closestGroupCountry = document.getElementById('d8cf7_telephone_default_country').closest("tr");
			var closestGroupRequiredCountry = document.getElementById('d8cf7_telephone_required_country').closest("tr");
			var closestGroupAllowedPrefixes = document.getElementById('d8cf7_telephone_allowed_prefixes').closest("tr");
			var closestGroupBarredPrefixes = document.getElementById('d8cf7_telephone_barred_prefixes').closest("tr");
			var closestGroupAdvancedOptions = document.getElementById('d8cf7_telephone_advanced_options').closest("tr");

			if(_telephoneValidation.checked){
				closestGroupCountry.setAttribute('style', 'display:table-row');
				closestGroupRequiredCountry.setAttribute('style', 'display:table-row');
				closestGroupAllowedPrefixes.setAttribute('style', 'display:table-row');
				closestGroupBarredPrefixes.setAttribute('style', 'display:table-row');
				closestGroupAdvancedOptions.setAttribute('style', 'display:table-row');
			}
			else{
				closestGroupCountry.setAttribute('style', 'display:none;');
				closestGroupRequiredCountry.setAttribute('style', 'display:none');
				closestGroupAllowedPrefixes.setAttribute('style', 'display:none');
				closestGroupBarredPrefixes.setAttribute('style', 'display:none');
				closestGroupAdvancedOptions.setAttribute('style', 'display:none');
			}
		}
	}

	var _paToggle = document.getElementById("d8cf7_predictiveaddress");
    
    _paToggle.addEventListener('click', function (){
        showHidePAOptions();
    });

	function showHidePAOptions (){
		var paOptions = document.getElementById("pa-options");

		if(_paToggle.checked)
			paOptions.setAttribute('style', 'display:table-row');
		else
			paOptions.setAttribute('style', 'display:none;');
	}

	showHidePAOptions();

    showHidePhoneOptions();
}
</script>