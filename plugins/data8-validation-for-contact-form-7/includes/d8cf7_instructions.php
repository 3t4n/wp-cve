<table class="wp-list-table widefat fixed bookmarks">
    <thead>
		<tr style="background-color:#005b94; padding:10px;">
            <th style="font-size:18px; color:white;"><strong>Instructions</strong></th>
        </tr>
    </thead>
    <tbody>
    <tr>
        <td>
			<ol>
				<li>
					Enter your Data8 Server API key to use the enhanced validation for email, telephone number and name fields.
					Enter your Data8 Client API key to use our address capture and validation service, PredictiveAddress.
					Change the options above to your preferences and remember to Save Changes.
				</li>
				<li>
					Standard Data8 validation will be applied to all email and telephone number fields in Gravity Forms and Contact
					Form 7. PredictiveAddress&trade; will be applied to all address fields in Woocommerce and Gravity Forms, and can
					be selectively applied to fields in Contact Form 7 and Elementor Pro. See the Validation Rules section below for details on how to refine the rules.
				</li>
				<li>
					The additional settings for some validation methods such as the level of Email validation and some customisation options for Phone validation, can be overriden on a field by field basis by specifying a value for "level" when creating the field. For examples see below.
				</li>
			</ol>
		</td>
	</tr>
	</tbody>
</table>
<br/>

<table class="wp-list-table widefat fixed bookmarks">
    <thead>
		<tr style="background-color:#4b8db4; padding:10px;">
            <th style="font-size:18px; color:white;"><strong>Gravity Forms Validation Rules</strong></th>
        </tr>
    </thead>
    <tbody>
    <tr>
        <td>  
			<ol class="validation_rules">
				<li>
					<strong>Email validation</strong>
					Just use the standard Gravity Forms email field. For further control, include a "d8level_<i>xxx</i>" custom CSS class:
					<ul>
						<li><strong>d8level_Syntax</strong> - validates the syntax of the email address (quickest)</li>
						<li><strong>d8level_MX</strong> - validates the domain name (right hand part) the email address (default)</li>
						<li><strong>d8level_Server</strong> - validates the mail servers for the domain are alive</li>
						<li><strong>d8level_Address</strong> - validates the full email address (slowest)</li>
					</ul>
				</li>
				
				<li>
					<strong>Phone validation</strong>
					Just use the standard Gravity Forms phone field with the format set to 'International'. For further control, include "d8country_<i>XX</i>", "d8AllowedPrefixes_<i>XXX_XXX</i>", and "d8BarredPrefixes_<i>XXXXX_XXX</i>" custom CSS classes:
					<ul>
						<li><strong>d8country_US</strong> - if the telephone number does not include an explicit country code prefix, use the country specified in this option instead. Defaults to GB</li>
						<li><strong>d8AllowedPrefixes_441_442</strong> - if specified any prefixes (without the leading plus "+" and seperated by an underscore "_") are the only ones allowed</li>
						<li><strong>d8BarredPrefixes_44151_442</strong> - if specified any prefixes (without the leading plus "+" and seperated by an underscore "_") are barred</li>
					</ul>
				</li>
				
				<li>
					<strong>PredictiveAddress&trade;</strong>
					Just use the standard Gravity Forms address field.
				</li>
				
				<li>
					<strong>Unusuable name</strong>
					Just use the standard Gravity Forms name field.
				</li>

				<li>
					<strong>Bank Validation</strong>
					Use single line text field with CSS classes set to values shown below
					<ul>
						<li><strong>d8-sortcode</strong> - include this css class in the advanced field options for the field containing sort code.</li>
						<li><strong>d8-account-number</strong> - include this css class in the advanced field options for the field containing account number.</li>
					</ul>
				</li>
			</ol>
		</td>
	</tr>
	</tbody>
</table>
<br/>

<table class="wp-list-table widefat fixed bookmarks">
    <thead>
		<tr style="background-color:#4b8db4; padding:10px;">
            <th style="font-size:18px; color:white;"><strong>Contact Form 7 Validation Rules</strong></th>
        </tr>
    </thead>
    <tbody>
    <tr>
        <td>  
			<ol class="validation_rules">
				<li>
					<strong>Email validation</strong>
					Just use the standard Contact Form 7 email field. For further control, include a "level" setting:
					<ul>
						<li><strong>[email* your-email level:Syntax]</strong> - validates the syntax of the email address (quickest). Setting this option will override any settings set in the settings page above.</li>
						<li><strong>[email* your-email level:MX]</strong> - validates the domain name (right hand part) the email address (default). Setting this option will override any settings set in the settings page above.</li>
						<li><strong>[email* your-email level:Server]</strong> - validates the mail servers for the domain are alive. Setting this option will override any settings set in the settings page above.</li>
						<li><strong>[email* your-email level:Address]</strong> - validates the full email address (slowest). Setting this option will override any settings set in the settings page above.</li>
					</ul>
				</li>
				
				<li>
					<strong>Phone validation</strong>
					Just use the standard Contact Form 7 tel field. For further control, include "country", "allowedPrefixes" and "barredPrefixes" settings:
					<ul>
						<li><strong>[tel* your-tel country:US]</strong> - if the telephone number does not include an explicit country code prefix, use the country specified in this option instead. Defaults to GB</li>
						<li><strong>[tel* your-tel allowedPrefixes:+441_+442]</strong> - if included only these prefixes will be allowed</li>
						<li><strong>[tel* your-tel barredPrefixes:+01_+447]</strong> - if included these prefixes will NOT be allowed</li>
					</ul>
				</li>
				
				<li>
					<strong>PredictiveAddress&trade;</strong>
					Use standard Contact Form 7 text fields to store your customer's address details. Add classes to indicate which fields should have PredictiveAddress&trade; functionality added to them:
					<ul>
						<li><strong>[text add1 class:d8pa_search class:d8pa_line1]</strong> - d8pa_search converts the text box into an auto-completing address box, and d8pa_line1 ensures the first line of the address is stored in this field once a full address is selected.</li>
						<li><strong>[text add2 class:d8pa_line2]</strong> - d8pa_line2 ensures the second line of the address is stored in this field once a full address is selected. Repeat with increasing numbers as required.</li>
						<li><strong>[text town class:d8pa_town]</strong> - d8pa_town ensures the town name from the selected address is stored in this field. Ignore this option if you do not mind which field holds the town name - it will be placed in the first available address line instead.</li>
						<li><strong>[text county class:d8pa_county]</strong> - d8pa_county ensures the county/state name from the selected address is stored in this field. Ignore this option if you do not mind which field holds the county name - it will be placed in the first available address line instead.</li>
						<li><strong>[text postcode class:d8pa_postcode]</strong> - d8pa_postcode ensures the postcode/zip code from the selected address is stored in this field.</li>
						<li><strong>[text country class:d8pa_country]</strong> - d8pa_country ensures the country name for the selected address is stored in this field.</li>
					</ul>
					If you have more than one set of address entry fields in the same form, keep them separate by using a unique number
					after d8pa, e.g. your billing address fields might be d8pa1_add1, d8pa1_add2, d8pa1_town, d8pa1_county and d8pa1_postcode
					while your shipping address fields might be d8pa2_add1, d8pa2_add2, d8pa2_town, d8pa2_county and d8pa2_postcode.
				</li>
				
				<li>
					<strong>Unusable name</strong>
					Use standard Contact Form 7 text fields with the setting "name_type:FullName".
					<ul>
						<li><strong>[text txt_name name_type:FullName]</strong> - This indicates what field to apply the Unusable name check on.</li>
					</ul>
				</li>

				<li>
					<strong>bank Validation</strong>
					Use standard Contact Form 7 text fields with names set as indicated below.
					<ul>
						<li><strong>[text* account-number bank_type:d8-account-number]</strong> - The bank_type option indicates what field to get account number from.</li>
					</ul>
					<ul>
						<li><strong>[text* sort-code bank_type:d8-sort-code]</strong> - The bank_type option indicates what field to get sort code from.</li>
					</ul>
				</li>
			</ol>
		</td>
	</tr>
	</tbody>
</table>
<br/>

<table class="wp-list-table widefat fixed bookmarks">
    <thead>
		<tr style="background-color:#4b8db4; padding:10px;">
            <th style="font-size:18px; color:white;"><strong>WPForms Validation Rules</strong></th>
        </tr>
    </thead>
    <tbody>
    <tr>
        <td>  
			<ol class="validation_rules">
				<li>
					<strong>Email validation</strong>
					Just use the standard WPForm email field. Validation level can be set above.
				</li>
				
				<li>
					<strong>Phone validation</strong>
					Just use the standard WPForm phone field, set to international format. further options can be added above.
				</li>

				<li>
					<strong>Name validation</strong>
					Just use the standard WPForm name field, it will work with any format of the name field.
				</li>

				<li>
					<strong>Bank validation</strong>
					For sort code and account number validation, add a single line text field, go to field options, advanced, and in the CSS classes field copy and paste in "d8-account-number"
					for the account number field and "d8-sortcode" for the sort code field.
				</li>
				<li>
					<strong>Predictive Address</strong>
					Use the standard WPForms address field and set the scheme to Data8 in field settings on the WPForms form builder. You may also want to set the default country value to your 
					preferred country of choice in the Advanced field settings tab.
				</li>
			</ol>
		</td>
	</tr>
	</tbody>
</table>
<br/>

<table class="wp-list-table widefat fixed bookmarks">
    <thead>
		<tr style="background-color:#4b8db4; padding:10px;">
            <th style="font-size:18px; color:white;"><strong>Elementor Pro Validation Rules</strong></th>
        </tr>
    </thead>
    <tbody>
    <tr>
        <td>  
			<ol class="validation_rules">
				<li>
					<strong>Email validation</strong>
					Include d8_email as part of the field ID to apply email validation to that field. 
				</li>
				
				<li>
					<strong>Phone validation</strong>
					Any field with the field type 'tel' will have phone validation automatically applied.
				</li>

				<li>
					<strong>Name validation</strong>
					Include d8_name as part of the field ID to apply name validation to that field. If using two seperate text fields for first name and last name include d8_first_name
					as part of the field ID for first name field and d8_last_name as part of the field ID for the last name field.
				</li>

				<li>
					<strong>Bank validation</strong>
					Include d8_account_number as part of the field ID for the field containing the bank account number of the bank details you want to validate. Likewise, include
					d8_sort_code as part of the field ID for the field containing the sort code of the bank details you want to validate. 
				</li>
				<li>
					<strong>Predictive Address</strong>
					Use standard Elementor Pro text fields to store your customers address details. Set the field ID exactly as shown below to indicate which fields should have PredictiveAddress
					functionality added to them:
					<ul>
						<li><strong>d8_address1</strong> - d8_address1 converts the text box into an auto-completing address box and ensures the first line of the address is stored in this field once a full address is selected.</li>
						<li><strong>d8_address2</strong> - d8_address2 ensures the second line of the address is stored in this field once a full address is selected.</li>
						<li><strong>d8_town</strong> - d8_town ensures the town name from the selected address is stored in this field. Ignore this option if you do not mind which field holds the town name - it will be placed in the first available address line instead.</li>
						<li><strong>d8_county</strong> - d8_county ensures the county/state name from the selected address is stored in this field. Ignore this option if you do not mind which field holds the county name - it will be placed in the first available address line instead.</li>
						<li><strong>d8_postcode</strong> - d8_postcode ensures the postcode/zip code from the selected address is stored in this field.</li>
						<li><strong>d8_country</strong> - d8_country ensures the country name for the selected address is stored in this field.</li>
					</ul>
					If you wish to add another address field in the same form, you can append '_2' to each of the field ID's e.g. d8_address1_2, d8_address2_2, d8_town_2 etc.
					You can repeat this with increasing numbers as required. If multiple forms with field addresses exist on one page increase this number over all forms accordingly.
				</li>
			</ol>
		</td>
	</tr>
	</tbody>
</table>
<br/>