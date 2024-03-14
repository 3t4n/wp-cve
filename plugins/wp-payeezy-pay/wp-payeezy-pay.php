<?php
/*
Plugin Name: WP Payeezy Pay
Version: 3.18
Description: Connects a WordPress site to the Payeezy Gateway using the Payment Page/Hosted Checkout method of integration. 
Author: Richard Rottman
Author URI: https://richard-rottman.com/contact/
License: GPL v3
*/
// creates a new page entitled 
// 'payeezyendpoint' containing
// the shortcode [payeezy_endpoint].
// Data collected through the form
// is sent to this page and then 
// immediately sent to First Data.
function wp_payeezy_pay_activate(){
	$title = 'WP Payeezy Pay Endpoint';
	$slug = 'payeezy-endpoint';
	$author_id = 1;

if( null == get_page_by_title( $title ) ) {
    // Create the page
		wp_insert_post(array(
        	'post_type' => 'page',
        	'post_title' => $title,
        	'post_author' => $author_id,
        	'post_status' => 'publish',
        	'post_content' => '[payeezy_endpoint]',
        	'post_name' => $slug,
    ));

} // end of function 

} 

register_activation_hook(__FILE__, 'wp_payeezy_pay_activate');

function wppayeezypaymentform() {
$x_currency_code = get_option('x_currency_code');
if ($x_currency_code === 'USD') { // United States Dollar
    $x_currency_code_symbol = '$'; 
    }
    if ($x_currency_code === 'AUD') { // Australian Dollar
    $x_currency_code_symbol = '$'; 
    }
    if ($x_currency_code === 'BRL') { // Brazilian Real
    $x_currency_code_symbol = 'R$'; 
    }
    if ($x_currency_code === 'CZK') { // Czech Koruna
    $x_currency_code_symbol = 'Kč'; 
    }
    if ($x_currency_code === 'DKK') { // Danish Krone
    $x_currency_code_symbol = 'kr.'; 
    }
    if ($x_currency_code === 'EUR') { // Euro
    $x_currency_code_symbol = '€'; 
    }
    if ($x_currency_code === 'HKD') { // Hong Kong Dollar
    $x_currency_code_symbol = 'HK$'; 
    }
    if ($x_currency_code === 'HUF') { // Hungarian forint
    $x_currency_code_symbol = 'Ft'; 
    }
    if ($x_currency_code === 'ILS') { // Israeli new shekel
    $x_currency_code_symbol = '₪'; 
    }
    if ($x_currency_code === 'JPY') { // Japanese yen
    $x_currency_code_symbol = '¥'; 
    }
    if ($x_currency_code === 'MYR') { // Malaysian ringgit
    $x_currency_code_symbol = 'RM'; 
    }
    if ($x_currency_code === 'MXN') { // Mexican peso
    $x_currency_code_symbol = 'Mex$'; 
    }
    if ($x_currency_code === 'NOK') { // Norwegian krone
    $x_currency_code_symbol = 'kr'; 
    }
    if ($x_currency_code === 'NZD') { // New Zealand Dollar 
    $x_currency_code_symbol = '$'; 
    }
    if ($x_currency_code === 'PHP') { // Philippine Peso
    $x_currency_code_symbol = '₱'; 
    }
    if ($x_currency_code === 'PLN') { // Polish Zloty
    $x_currency_code_symbol = 'zł '; 
    }
    if ($x_currency_code === 'GBP') { // British Pound 
    $x_currency_code_symbol = '£'; 
    }
    if ($x_currency_code === 'SGD') { // Singapore Dollar
    $x_currency_code_symbol = '$'; 
    }
    if ($x_currency_code === 'SEK') { // Swedish Krona
    $x_currency_code_symbol = 'kr'; 
    }
    if ($x_currency_code === 'CHF') { // Swiss Franc
    $x_currency_code_symbol = 'CHF'; 
    }
    if ($x_currency_code === 'TWD') { // Taiwan New Dollar
    $x_currency_code_symbol = 'NT$'; 
    }
    if ($x_currency_code === 'THB') { // Thai Baht
    $x_currency_code_symbol = '฿'; 
    }
    if ($x_currency_code === 'TRY') { // Turkish Lira
    $x_currency_code_symbol = '₺'; 
    }

$mode2 = get_option ('mode2') ; // payments, donations, or testing
$button_text= get_option ('button_text') ; // 

if ( $button_text == "pay-now") {
  $button = 'Pay Now'; 
}
elseif ( $button_text == "donate-now") {
      $button = 'Donate Now'; 
}
elseif ( $button_text == "continue") {
      $button = 'Continue'; 
}
elseif ( $button_text == "continue-to-secure-donation") {
      $button = 'Continue to Secure Donation Form'; 
}
else {
      $button = 'Continue to Secure Payment Form'; 
}
// This is the Ref. Num that shows in Transactions on the front page.
$x_invoice_num_name = get_option('x_invoice_num_name');

// This is the Cust. Ref. Num that shows in Transactions on the front page. Also referred
// to as Purchase Order or PO number. It's a reference number submitted by the customer
// for their own record keeping.
$x_po_num_name = get_option('x_po_num_name');

// This shows up on the final order form as "Item" unless Invoice Number is used.
// If there is an Invoice Number sent, that overrides the Description. 
$x_description_name = get_option('x_description_name');

// Just an extra reference number if Invoice Number and Customer Reference Number are
// not enough reference numbers for your purposes. 
$x_reference_3_name = get_option('x_reference_3_name');

// Next three are custom fields that if passed over to Payeezy, will show populated on
// the secure order form and the information collected will be passed a long with all the
// other info. 
$x_user1_name = get_option('x_user1_name') ;
$x_user2_name = get_option('x_user2_name') ;
$x_user3_name = get_option('x_user3_name') ;

// If you want to collect the customer's phone number and/or email address, you can do so
// by giving these two fields a name, such as "phone" and "email."
$x_phone_name = get_option('x_phone_name') ;
$x_email_name = get_option('x_email_name') ;

$x_amount = get_option('x_amount') ;
$x_company_name = get_option('x_company_name') ;
$base_url = home_url();
// $base_url = site_url();
$endpoint_url = $base_url . '/payeezy-endpoint/';

ob_start(); // stops the shortcode output from appearing at the very top of the post/page.
?>
<style>
#x_country, #x_state {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    background-color: #fff;
    background: url('../wp-content/plugins/wp-payeezy-pay/blue-caret-down.svg') 96% / 4% no-repeat #fff;
    background-size: 18px 18px;
    cursor: pointer;
    position: relative;
    border-radius: 0;
    }
    
    input[type="text"],input[type="email"],input[type="tel"],select {
	background-color: #fff;
	border: 1px solid #ddd;
	color: #999;
	font-size: 16px;
    line-height: 16px;
	font-weight: 400;
	padding: 0 0 0 15px;
	width: 100%;
	border-radius: 0;
	height: 37px;
	
}

textarea {
    background-color: #fff;
	border: 1px solid #ddd;
	color: #999;
	font-size: 16px;
    line-height: 16px;
	font-weight: 400;
	padding: 15px;
	width: 100%;
	border-radius: 0;

}

    input[type="radio"] {
    -webkit-appearance: radio;
    box-sizing: border-box;
    width: auto;
}
    
    label {
        font-weight: 700;
        display: block
    }
    #x_amount,
    #x_amount2 {
        width: 155px;
        display: inline;
     }

  .currencySymbol {
    margin-left: -14px;
    padding-right: 5px;
    }
    
 .donation-amounts {
    margin-bottom: 30px;
}

 #wp_payeezy_payment_form input[type="submit"] {
        width: auto
      }
    
      labelamount1radio {
    padding-left: 15px;
    text-indent: -15px;
}
.amount1Radio {
    width: 18px;
    height: 18px;
    padding: 0;
    margin: 0;
    vertical-align: middle;
    position: relative;
    top: -1px;
    *overflow: hidden;
}
#price_container {
    margin: 0 0 1.75em;
    overflow: hidden;
    }
.donationButtons {
    display: block;
    margin: 0 auto;
}
.donationButtons ul {
	display: table;
	margin: 0 0 1.5em 0;
}
#other_amount {
    display: block;
    clear: both;
}
#price_container li {
    list-style-type: none;
    float: left;
    width: auto;
    padding-left: 0px;
    padding-right: 10px;
    padding-bottom: 10px;
}
.payeezy-donation-amount {
    border: 1px solid #ccc;
    padding: 0;
    margin-bottom: 20px;
    display: inline-block;
}
.payeezy-donation-amount .payeezy-currency-symbol {
    background-color: #f2f2f2;
    color: #333;
    margin: 0;
    padding: 0 12px;
    height: 35px;
    line-height: 35px;
    font-size: 18px;
    border-top: none;
    border-right: 1px solid #ccc;
    border-bottom: none;
    border-left: none;
    float: left;
}
input#payeezy-amount {
    width: 120px;
    display: block;
    background-color: #fff;
    color: #333;
    margin: 0;
    padding: 0 0 0 12px;
    height: 35px;
    line-height: 35px;
    font-size: 18px;
    border: none;
}
.payeezy-hidden {
    display: none !important;
}
#price_container input[type=radio],#price_container input[type=checkbox] {
    display: none;
}
#price_container input[type=radio] + label,#price_container input[type=checkbox] + label {
    text-transform: uppercase;
    padding: 8px 10px;
    font-weight: 400;
    vertical-align: middle;
    text-align: center;
    cursor: pointer;
    background-color: #EDEDED;
    background-image: -moz-linear-gradient(top,#EDEDED,#EDEDED);
    background-image: -webkit-gradient(linear,0 0,0 100%,from(#EDEDED),to(#EDEDED));
    background-image: -webkit-linear-gradient(top,#EDEDED,#EDEDED);
    background-image: -o-linear-gradient(top,#EDEDED,#EDEDED);
    background-image: linear-gradient(to bottom,#EDEDED,#EDEDED);
    border: 1px solid #ccc;
    border-radius: 3px;
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffffff',endColorstr='#ffe6e6e6',GradientType=0);
    filter: progid:DXImageTransform.Microsoft.gradient(enabled=false);
    -webkit-box-shadow: inset 0 1px 0 rgba(255,255,255,0.2),0 1px 2px rgba(0,0,0,0.05);
    -moz-box-shadow: inset 0 1px 0 rgba(255,255,255,0.2),0 1px 2px rgba(0,0,0,0.05);
    box-shadow: inset 0 1px 0 rgba(255,255,255,0.2),0 1px 2px rgba(0,0,0,0.05);
}
#price_container form[id*=payeezy-form] .payeezy-donation-amount .payeezy-currency-symbol {
    background-color: #f2f2f2;
    border-top: 1px solid #ccc;
    border-bottom: 1px solid #ccc;
    color: #333;
    margin: 0;
    padding: 0 0 0 12px;
    height: 35px;
    line-height: 35px;
    font-size: 18px;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
    float: left;
}
#price_container input[type=radio]:checked + label,#price_container input[type=checkbox]:checked + label {
    background-image: none;
    outline: 0;
    color: #fff;
    background-color: #333;
    border: 1px solid #222;
}
.pay {
    margin: 0 auto;
  }
    span.red {
    color: red;
}

p.donationRadioButton {
    margin-bottom: 7px;
}</style>
<script>
function other() {
    document.getElementById('other_amount').style.display = "block";
}

function not_other() {
    document.getElementById('other_amount').style.display = "none";
}

</script>
<!-- WP Payeezy Pay Version: 3.18  -->
<div id="wp_payeezy_payment_form">
<form action="<?php echo $endpoint_url;?>" method="post">
<input name="mode2" value="<?php echo $mode2;?>" type="hidden" >
<input name="x_currency_code" value="<?php echo $x_currency_code;?>" type="hidden" >
<?php if (isset($x_invoice_num_name)) { ?>
 <input name="x_invoice_num_name" value="<?php echo $x_invoice_num_name;?>" type="hidden" >
<?php } ?>
<p><label>First Name</label><input name="x_first_name" value="" id="x_first_name" type="text" required></p> 
<p><label>Last Name</label><input name="x_last_name" id="x_last_name" value="" type="text" required></p> 
<?php if (!empty($x_company_name)) {
  echo '<p><label>';
  echo $x_company_name;
  echo '</label>';
  echo '<input name="x_company" value=" " type="text" id="x_company" required></p>';  
}
else {
  echo '<input name="x_company" value="" type="hidden" >';
  }?>
<p><label>Street Address</label><input name="x_address" id="x_address" value="" type="text" required></p> 
<p><label>City</label><input name="x_city" id="x_city" value="" type="text" required></p> 
<p><label>State/Province</label><select name="x_state" id="x_state" required>
<option value="" selected="selected">Select a State/Province</option>
    <option value="Alabama">Alabama</option>
    <option value="Alaska">Alaska</option>
    <option value="Arizona">Arizona</option>
    <option value="Arkansas">Arkansas</option>
    <option value="California">California</option>
    <option value="Colorado">Colorado</option>
    <option value="Connecticut">Connecticut</option>
    <option value="Delaware">Delaware</option>
    <option value="District of Columbia">District of Columbia</option>
    <option value="Florida">Florida</option>
    <option value="Georgia">Georgia</option>
    <option value="Hawaii">Hawaii</option>
    <option value="Idaho">Idaho</option>
    <option value="Illinois">Illinois</option>
    <option value="Indiana">Indiana</option>
    <option value="Iowa">Iowa</option>
    <option value="Kansas">Kansas</option>
    <option value="Kentucky">Kentucky</option>
    <option value="Louisiana">Louisiana</option>
    <option value="Maine">Maine</option>
    <option value="Maryland">Maryland</option>
    <option value="Massachusetts">Massachusetts</option>
    <option value="Michigan">Michigan</option>
    <option value="Minnesota">Minnesota</option>
    <option value="Mississippi">Mississippi</option>
    <option value="Missouri">Missouri</option>
    <option value="Montana">Montana</option>
    <option value="Nebraska">Nebraska</option>
    <option value="Nevada">Nevada</option>
    <option value="New Hampshire">New Hampshire</option>
    <option value="New Jersey">New Jersey</option>
    <option value="New Mexico">New Mexico</option>
    <option value="New York">New York</option>
    <option value="North Carolina">North Carolina</option>
    <option value="North Dakota">North Dakota</option>
    <option value="Ohio">Ohio</option>
    <option value="Oklahoma">Oklahoma</option>
    <option value="Oregon">Oregon</option>
    <option value="Pennsylvania">Pennsylvania</option>
    <option value="Puerto Rico">Puerto Rico</option>
    <option value="Rhode Island">Rhode Island</option>
    <option value="South Carolina">South Carolina</option>
    <option value="South Dakota">South Dakota</option>
    <option value="Tennessee">Tennessee</option>
    <option value="Texas">Texas</option>
    <option value="Utah">Utah</option>
    <option value="Vermont">Vermont</option>
    <option value="Virginia">Virginia</option>
    <option value="Washington">Washington</option>
    <option value="West Virginia">West Virginia</option>
    <option value="Wisconsin">Wisconsin</option>
    <option value="Wyoming">Wyoming</option>
    <option value="" disabled="disabled">-------------</option>
    <option value="Alberta">Alberta</option>
    <option value="British Columbia">British Columbia</option>
    <option value="Manitoba">Manitoba</option>
    <option value="New Brunswick">New Brunswick</option>
    <option value="Newfoundland">Newfoundland</option>
    <option value="Northwest Territories">Northwest Territories</option>
    <option value="Nova Scotia">Nova Scotia</option>
    <option value="Nunavut">Nunavut</option>
    <option value="Ontario">Ontario</option>
    <option value="Prince Edward Island">Prince Edward Island</option>
    <option value="Quebec">Quebec</option>
    <option value="Saskatchewan">Saskatchewan</option>
    <option value="Yukon">Yukon</option>
    <option value="" disabled="disabled">-------------</option>
    <option value="N/A">Not Applicable</option>
</select></p>
<p><label>Zip Code</label><input name="x_zip" id="x_zip" value="" type="text" required></p> 
<p><label>Country</label><select id="x_country" name="x_country" required>
<option value="" selected="selected">Select a Country</option>
    <option value="United States">United States</option>
    <option value="Canada">Canada</option>
    <option value="" disabled="disabled">-------------</option>
    <option value="Afghanistan">Afghanistan</option>
    <option value="Aland Islands">Aland Islands</option>
    <option value="Albania">Albania</option>
    <option value="Algeria">Algeria</option>
    <option value="American Samoa">American Samoa</option>
    <option value="Andorra">Andorra</option>
    <option value="Angola">Angola</option>
    <option value="Anguilla">Anguilla</option>
    <option value="Antarctica">Antarctica</option>
    <option value="Antigua and Barbuda">Antigua and Barbuda</option>
    <option value="Argentina">Argentina</option>
    <option value="Armenia">Armenia</option>
    <option value="Aruba">Aruba</option>
    <option value="Australia">Australia</option>
    <option value="Austria">Austria</option>
    <option value="Azerbaijan">Azerbaijan</option>
    <option value="Bahamas">Bahamas</option>
    <option value="Bahrain">Bahrain</option>
    <option value="Bangladesh">Bangladesh</option>
    <option value="Barbados">Barbados</option>
    <option value="Belarus">Belarus</option>
    <option value="Belgium">Belgium</option>
    <option value="Belize">Belize</option>
    <option value="Benin">Benin</option>
    <option value="Bermuda">Bermuda</option>
    <option value="Bhutan">Bhutan</option>
    <option value="Bolivia">Bolivia</option>
    <option value="Bonaire, Sint Eustatius and Saba">Bonaire, Sint Eustatius and Saba</option>
    <option value="Bosnia and Herzegowina">Bosnia and Herzegowina</option>
    <option value="Botswana">Botswana</option>
    <option value="Bouvet Island">Bouvet Island</option>
    <option value="Brazil">Brazil</option>
    <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
    <option value="Brunei Darussalam">Brunei Darussalam</option>
    <option value="Bulgaria">Bulgaria</option>
    <option value="Burkina Faso">Burkina Faso</option>
    <option value="Burundi">Burundi</option>
    <option value="Cambodia">Cambodia</option>
    <option value="Cameroon">Cameroon</option>
    <option value="Canada">Canada</option>
    <option value="Cape Verde">Cape Verde</option>
    <option value="Cayman Islands">Cayman Islands</option>
    <option value="Central African Republic">Central African Republic</option>
    <option value="Chad">Chad</option>
    <option value="Chile">Chile</option>
    <option value="China">China</option>
    <option value="Christmas Island">Christmas Island</option>
    <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
    <option value="Colombia">Colombia</option>
    <option value="Comoros">Comoros</option>
    <option value="Congo">Congo</option>
    <option value="Congo, the Democratic Republic of the">Congo, the Democratic Republic of the</option>
    <option value="Cook Islands">Cook Islands</option>
    <option value="Costa Rica">Costa Rica</option>
    <option value="Cote D&#x27;Ivoire">Cote D&#x27;Ivoire</option>
    <option value="Croatia">Croatia</option>
    <option value="Cuba">Cuba</option>
    <option value="Curacao">Curacao</option>
    <option value="Cyprus">Cyprus</option>
    <option value="Czech Republic">Czech Republic</option>
    <option value="D.P.R. Korea">D.P.R. Korea</option>
    <option value="Denmark">Denmark</option>
    <option value="Djibouti">Djibouti</option>
    <option value="Dominica">Dominica</option>
    <option value="Dominican Republic">Dominican Republic</option>
    <option value="Ecuador">Ecuador</option>
    <option value="Egypt">Egypt</option>
    <option value="El Salvador">El Salvador</option>
    <option value="Equatorial Guinea">Equatorial Guinea</option>
    <option value="Eritrea">Eritrea</option>
    <option value="Estonia">Estonia</option>
    <option value="Ethiopia">Ethiopia</option>
    <option value="Falkland Islands">Falkland Islands</option>
    <option value="Faroe Islands">Faroe Islands</option>
    <option value="Fiji">Fiji</option>
    <option value="Finland">Finland</option>
    <option value="France">France</option>
    <option value="French Guiana">French Guiana</option>
    <option value="French Polynesia">French Polynesia</option>
    <option value="French Southern Territories">French Southern Territories</option>
    <option value="Gabon">Gabon</option>
    <option value="Gambia">Gambia</option>
    <option value="Georgia">Georgia</option>
    <option value="Germany">Germany</option>
    <option value="Ghana">Ghana</option>
    <option value="Gibraltar">Gibraltar</option>
    <option value="Greece">Greece</option>
    <option value="Greenland">Greenland</option>
    <option value="Grenada">Grenada</option>
    <option value="Guadeloupe">Guadeloupe</option>
    <option value="Guam">Guam</option>
    <option value="Guatemala">Guatemala</option>
    <option value="Guernsey">Guernsey</option>
    <option value="Guinea">Guinea</option>
    <option value="Guinea-Bissau">Guinea-Bissau</option>
    <option value="Guyana">Guyana</option>
    <option value="Haiti">Haiti</option>
    <option value="Heard and McDonald Islands">Heard and McDonald Islands</option>
    <option value="Honduras">Honduras</option>
    <option value="Hong Kong SAR, PRC">Hong Kong SAR, PRC</option>
    <option value="Hungary">Hungary</option>
    <option value="Iceland">Iceland</option>
    <option value="India">India</option>
    <option value="Indonesia">Indonesia</option>
    <option value="Iran">Iran</option>
    <option value="Iraq">Iraq</option>
    <option value="Ireland">Ireland</option>
    <option value="Isle of Man">Isle of Man</option>
    <option value="Israel">Israel</option>
    <option value="Italy">Italy</option>
    <option value="Jamaica">Jamaica</option>
    <option value="Japan">Japan</option>
    <option value="Jersey">Jersey</option>
    <option value="Jordan">Jordan</option>
    <option value="Kazakhstan">Kazakhstan</option>
    <option value="Kenya">Kenya</option>
    <option value="Kiribati">Kiribati</option>
    <option value="Korea">Korea</option>
    <option value="Kuwait">Kuwait</option>
    <option value="Kyrgyzstan">Kyrgyzstan</option>
    <option value="Lao People&#x27;s Republic">Lao People&#x27;s Republic</option>
    <option value="Latvia">Latvia</option>
    <option value="Lebanon">Lebanon</option>
    <option value="Lesotho">Lesotho</option>
    <option value="Liberia">Liberia</option>
    <option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
    <option value="Liechtenstein">Liechtenstein</option>
    <option value="Lithuania">Lithuania</option>
    <option value="Luxembourg">Luxembourg</option>
    <option value="Macau">Macau</option>
    <option value="Macedonia">Macedonia</option>
    <option value="Madagascar">Madagascar</option>
    <option value="Malawi">Malawi</option>
    <option value="Malaysia">Malaysia</option>
    <option value="Maldives">Maldives</option>
    <option value="Mali">Mali</option>
    <option value="Malta">Malta</option>
    <option value="Marshall Islands">Marshall Islands</option>
    <option value="Martinique">Martinique</option>
    <option value="Mauritania">Mauritania</option>
    <option value="Mauritius">Mauritius</option>
    <option value="Mayotte">Mayotte</option>
    <option value="Mexico">Mexico</option>
    <option value="Micronesia">Micronesia</option>
    <option value="Moldova">Moldova</option>
    <option value="Monaco">Monaco</option>
    <option value="Mongolia">Mongolia</option>
    <option value="Montenegro">Montenegro</option>
    <option value="Montserrat">Montserrat</option>
    <option value="Morocco">Morocco</option>
    <option value="Mozambique">Mozambique</option>
    <option value="Myanmar">Myanmar</option>
    <option value="Namibia">Namibia</option>
    <option value="Nauru">Nauru</option>
    <option value="Nepal">Nepal</option>
    <option value="Netherlands">Netherlands</option>
    <option value="New Caledonia">New Caledonia</option>
    <option value="New Zealand">New Zealand</option>
    <option value="Nicaragua">Nicaragua</option>
    <option value="Niger">Niger</option>
    <option value="Nigeria">Nigeria</option>
    <option value="Niue">Niue</option>
    <option value="Norfolk Island">Norfolk Island</option>
    <option value="Northern Mariana Islands">Northern Mariana Islands</option>
    <option value="Norway">Norway</option>
    <option value="Not Available">Not Available</option>
    <option value="Oman">Oman</option>
    <option value="Pakistan">Pakistan</option>
    <option value="Palau">Palau</option>
    <option value="Palestine, State of">Palestine, State of</option>
    <option value="Panama">Panama</option>
    <option value="Papua New Guinea">Papua New Guinea</option>
    <option value="Paraguay">Paraguay</option>
    <option value="Peru">Peru</option>
    <option value="Philippines">Philippines</option>
    <option value="Pitcairn">Pitcairn</option>
    <option value="Poland">Poland</option>
    <option value="Portugal">Portugal</option>
    <option value="Puerto Rico">Puerto Rico</option>
    <option value="Qatar">Qatar</option>
    <option value="Reunion">Reunion</option>
    <option value="Romania">Romania</option>
    <option value="Russian Federation">Russian Federation</option>
    <option value="Rwanda">Rwanda</option>
    <option value="Saint Barthelemy">Saint Barthelemy</option>
    <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
    <option value="Saint Lucia">Saint Lucia</option>
    <option value="Saint Martin (French part)">Saint Martin (French part)</option>
    <option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option>
    <option value="Samoa">Samoa</option>
    <option value="San Marino">San Marino</option>
    <option value="Sao Tome and Principe">Sao Tome and Principe</option>
    <option value="Saudi Arabia">Saudi Arabia</option>
    <option value="Senegal">Senegal</option>
    <option value="Serbia">Serbia</option>
    <option value="Seychelles">Seychelles</option>
    <option value="Sierra Leone">Sierra Leone</option>
    <option value="Singapore">Singapore</option>
    <option value="Sint Maarten (Dutch part)">Sint Maarten (Dutch part)</option>
    <option value="Slovakia">Slovakia</option>
    <option value="Slovenia">Slovenia</option>
    <option value="Solomon Islands">Solomon Islands</option>
    <option value="Somalia">Somalia</option>
    <option value="South Africa">South Africa</option>
    <option value="South Georgia and the South Sandwich Islands">South Georgia and the South Sandwich Islands</option>
    <option value="South Sudan">South Sudan</option>
    <option value="Spain">Spain</option>
    <option value="Sri Lanka">Sri Lanka</option>
    <option value="St Helena">St Helena</option>
    <option value="St Pierre and Miquelon">St Pierre and Miquelon</option>
    <option value="Sudan">Sudan</option>
    <option value="Suriname">Suriname</option>
    <option value="Svalbard and Jan Mayen Islands">Svalbard and Jan Mayen Islands</option>
    <option value="Swaziland">Swaziland</option>
    <option value="Sweden">Sweden</option>
    <option value="Switzerland">Switzerland</option>
    <option value="Syrian Arab Republic">Syrian Arab Republic</option>
    <option value="Taiwan Region">Taiwan Region</option>
    <option value="Tajikistan">Tajikistan</option>
    <option value="Tanzania">Tanzania</option>
    <option value="Thailand">Thailand</option>
    <option value="Timor-Leste">Timor-Leste</option>
    <option value="Togo">Togo</option>
    <option value="Tokelau">Tokelau</option>
    <option value="Tonga">Tonga</option>
    <option value="Trinidad and Tobago">Trinidad and Tobago</option>
    <option value="Tunisia">Tunisia</option>
    <option value="Turkey">Turkey</option>
    <option value="Turkmenistan">Turkmenistan</option>
    <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
    <option value="Tuvalu">Tuvalu</option>
    <option value="Uganda">Uganda</option>
    <option value="Ukraine">Ukraine</option>
    <option value="United Arab Emirates">United Arab Emirates</option>
    <option value="United Kingdom">United Kingdom</option>
    <option value="United States">United States</option>
    <option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
    <option value="Uruguay">Uruguay</option>
    <option value="Uzbekistan">Uzbekistan</option>
    <option value="Vanuatu">Vanuatu</option>
    <option value="Vatican City State (Holy See)">Vatican City State (Holy See)</option>
    <option value="Venezuela">Venezuela</option>
    <option value="Viet Nam">Viet Nam</option>
    <option value="Virgin Islands (British)">Virgin Islands (British)</option>
    <option value="Virgin Islands (US)">Virgin Islands (US)</option>
    <option value="Wallis and Futuna Islands">Wallis and Futuna Islands</option>
    <option value="Western Sahara">Western Sahara</option>
    <option value="Yemen">Yemen</option>
    <option value="Zambia">Zambia</option>
    <option value="Zimbabwe">Zimbabwe</option>      
</select></p>     
<?php
//// Invoice ////
if (!empty($x_invoice_num_name)) {
  echo '<p><label>';
  echo $x_invoice_num_name;
  echo '</label>';
  echo '<input name="x_invoice_num" value="" type="text" id="x_invoice_num" required>';
  echo '<input name="x_invoice_num_name" value="';
  echo $x_invoice_num_name;
  echo '" type="hidden">';
  echo '</p>';
}
else {
  echo '<input name="x_invoice_num" value="" type="hidden" >';
  echo '<input name="x_invoice_num_name" value="" type="hidden">';
  }
//// PO Number ////
  if (!empty($x_po_num_name)) {
    echo '<p><label>';
  echo $x_po_num_name;
  echo '</label>';
  echo '<input name="x_po_num" value="" type="text" id="x_po_num" required>';
  echo '<input name="x_po_num_name" value="';
  echo $x_po_num_name;
  echo '" type="hidden">';
  echo '</p>';
}
else {
  echo '<input name="x_po_num" value="" type="hidden">';
  echo '<input name="x_po_num_name" value="" type="hidden">';
  }
//// Reference Number 3 ////
if (!empty($x_reference_3_name)) {
  echo '<p><label>';
  echo $x_reference_3_name;
  echo '</label>';
  echo '<input name="x_reference_3" value="" type="text" id="x_reference_3" required>';
  echo '<input name="x_reference_3_name" value="';
  echo $x_reference_3_name;
  echo '" type="hidden">';
  echo '</p>';
}
else {
  echo '<input name="x_reference_3" value="" type="hidden">';
  echo '<input name="x_reference_3_name" value="" type="hidden">';
  }
//// User Defined 1 //// 
if (!empty($x_user1_name)) {                                                              
  echo '<p><label>';
  echo $x_user1_name;
  echo '</label>';
  echo '<input name="x_user1" value="" type="text" id="x_user_1" required>';
  echo '<input name="x_user1_name" value="';
  echo $x_user1_name;
  echo '" type="hidden">';
  echo '</p>';
}
else {
  echo '<input name="x_user1" value="" type="hidden">';
  echo '<input name="x_user1_name" value="" type="hidden">';
  }
//// User Defined 2 ////
if (!empty($x_user2_name)) {
    echo '<p><label>';
  echo $x_user2_name;
  echo '</label>';
  echo '<input name="x_user2" value="" type="text" id="x_user_2" required>';
  echo '<input name="x_user2_name" value="';
  echo $x_user2_name;
  echo '" type="hidden">';
  echo '</p>';
}
else {
  echo '<input name="x_user2" value="" type="hidden">';
  echo '<input name="x_user2_name" value="" type="hidden">';
  }
//// User Defined 3 ////
if (!empty($x_user3_name)) {
    echo '<p><label>';
  echo $x_user3_name;
  echo '</label>';
  echo '<input name="x_user3" value="" type="text" id="x_user_3" required>';
  echo '<input name="x_user3_name" value="';
  echo $x_user3_name;
  echo '" type="hidden">';
  echo '</p>';
}
else {
  echo '<input name="x_user3" value="" type="hidden">';
  echo '<input name="x_user3_name" value="" type="hidden">';
  }
//// Email ////
if (!empty($x_email_name)) {
  echo '<p><label>';
  echo $x_email_name;
  echo '</label>';
  echo '<input name="x_email" value="" type="email" id="x_email" required>';
  echo '<input name="x_email_name" value="';
  echo $x_email_name;
  echo '" type="hidden">';
  echo '</p>';
}
else {
  echo '<input name="x_email" value="" type="hidden">';
  echo '<input name="x_email_name" value="" type="hidden">';
  }
//// Phone Number ////
if (!empty($x_phone_name)) {
  echo '<p><label>';
  echo $x_phone_name;
  echo '</label>';
  echo '<input name="x_phone" value="" type="tel" id="x_phone" required>';
  echo '<input name="x_phone_name" value="';
  echo $x_phone_name;
  echo '" type="hidden">';
  echo '</p>';
}
else {
  echo '<input name="x_phone" value="" type="hidden">';
   echo '<input name="x_phone_name" value="" type="hidden">';
  }
//// Description ////
if ( !empty( $x_description_name)) {
  echo '<p><label>';
  echo $x_description_name;
  echo '</label>';
  echo '<textarea cols="40" rows="5" name="x_description" id="x_description"></textarea>';
 echo '<input name="x_description_name" value="';
  echo $x_description_name;
  echo '" type="hidden">';
  echo '</p>';
}
else {
  echo '<input name="x_description" value="" type="hidden">';
  echo '<input name="x_description_name" value="" type="hidden">';
} // end of Description 

if (($mode2 == "pay") || ($mode2 == "pay-rec") || ($mode2 == "pay-rec-req")) {
        if (!empty($x_amount)) { // cardholder does not enter an amount 
            echo '<input name="x_amount" value="';
            echo $x_amount;
            echo '" type="hidden" id="x_amount" >';
            }
        else { ?>
        <div id="other_amount" style="display: block;"><label>Enter Payment Amount</label>
<div class="payeezy-donation-amount">
  <span class="payeezy-currency-symbol payeezy-currency-position-before"><?php echo $x_currency_code_symbol;?></span> <label class="payeezy-hidden" for="payeezy-amount">Payment Amount:</label>
  <input class="payeezy-text-input payeezy-amount-top" id="payeezy-amount" name="x_amount" type="number" placeholder="" step=".01" value="" required>
</div><!-- end to .payeezy-donation-amount -->
</div>
       
        <?php }    
}    
        
if (($mode2 == "donate") || ($mode2 == "donate-rec")) {?>
<div id="price_container">
<label><span class="donationAmount">Donation Amount</span></label>  
<div class="donationButtons">  
    <ul> 
        <li>
         <input type="radio" onclick="not_other()" data-price-id="1" id="radio1" name="x_amount1" value="10.00" >
         <label for="radio1"><?php echo $x_currency_code_symbol;?>10</label>
      </li> 
       
       <li>
         <input type="radio" onclick="not_other()" data-price-id="2" id="radio2" name="x_amount1" value="25.00" >
         <label for="radio2"><?php echo $x_currency_code_symbol;?>25</label>
      </li> 
      
      <li>
         <input type="radio" onclick="not_other()" data-price-id="3" id="radio3" name="x_amount1" value="50.00" >
         <label for="radio3"><?php echo $x_currency_code_symbol;?>50</label>
      </li> 
      
      <li>
         <input type="radio" onclick="not_other()" data-price-id="4" id="radio4" name="x_amount1" value="75.00" >
         <label for="radio4"><?php echo $x_currency_code_symbol;?>75</label>
      </li> 
      
      <li>
         <input type="radio" onclick="not_other()" data-price-id="5" id="radio5" name="x_amount1" value="100.00" >
         <label for="radio5"><?php echo $x_currency_code_symbol;?>100</label>
      </li> 
     
      
      <li class="other">
         <input type="radio" onclick="other()" id="radio6" name="x_amount1" value="0.00">
         <label for="radio6">Other</label>
      </li>
     
    </ul>
</div><!-- end of donation buttons-->
<div id="other_amount" style="display:none;">Enter the amount you wish to donate<br>
<div class="payeezy-donation-amount">
  <span class="payeezy-currency-symbol payeezy-currency-position-before"><?php echo $x_currency_code_symbol;?></span> <label class="payeezy-hidden" for="payeezy-amount">Donation Amount:</label>
  <input class="payeezy-text-input payeezy-amount-top" id="payeezy-amount" name="x_amount2" type="number" placeholder="" step=".01" value="" >
</div><!-- end to .payeezy-donation-amount -->
</div><!-- end #other_amount -->
</div><!-- end #price_container -->
        
<?php } 

if ($mode2 == "donate-rec" ) {
      echo '<p><input type="checkbox" name="x_recurring_billing" id="x_recurring_billing" value="TRUE" >&nbsp;Automatically repeat this same donation once a month, beginning in 30 days.</p>';
}
// Pay with optional Recurring
if ($mode2 == "pay-rec" ) {
    echo '<p><input type="checkbox" name="x_recurring_billing" id="x_recurring_billing" value="TRUE" >&nbsp;Automatically repeat this same payment once a month, beginning in 30 days.</p> ';
}
// Pay with required Recurring
if ($mode2 == "pay-rec-req" ) {
    echo '<input type="hidden" name="x_recurring_billing" value="TRUE" >';
}
?>
<p><input type="submit" id="submit" value="<?php echo $button;?>"></p>
</form>
</div><!-- end of wp_payeezy_payment_form -->
<?php
return ob_get_clean();
}
// beginning of function that creates
// the endpoint shortcode that is
// inserted into a new page entitled
// 'payeezyendpoint.'
function wppayeezypaymentpublishedpaypage() {
ob_start();
?>
<style>
.entry-title {
  display: none;
}
.center {
  text-align: center;
}

#payeezyendpoint-container {
  text-align: center;
}

.green {
  color: green;
}
</style>
<?php
$mode = get_option ('mode') ; // production or demo
if ( $mode == "live" ) {
    $post_url = "https://checkout.globalgatewaye4.firstdata.com/payment";
    }
if ( $mode == "demo" ) {
    $post_url = "https://demo.globalgatewaye4.firstdata.com/payment";
    }
if ( $mode == "troubleshooting" ) {
    $post_url = "https://richard-rottman.com/testing.php";
    }
if (isset($_POST["x_amount1"])) { // pre-set donation amount.
  $x_amount1 = stripslashes($_POST["x_amount1"]); // takes the pre-defined amount 
  }

if (isset($_POST["x_amount2"])) { // custom donation amount
  $x_amount2 = stripslashes($_POST["x_amount2"]); // takes the manually entered amount
  }

if (isset($_POST["x_amount"])) { // custom donation amount
  $x_amount = stripslashes($_POST["x_amount"]); // takes the manually entered amount
  }

  if (isset($x_amount1)) {
  if ($x_amount1 > '0.00') {

    $x_amount = $x_amount1;
  }

  if (($x_amount1 === '0.00') && (isset($_POST["x_amount2"])) ) { // take x_amount2 and ignore x_amount1

    $x_amount = $x_amount2;
  } 
  
  }
$x_amount = number_format((float)$x_amount, 2, '.', '');  // 

// If there is not an x_amount
if ( ($x_amount == '0.0') and ( $mode !== 'troubleshooting')) {
echo '<div id="payeezyendpoint-container"><p class="center">Please return to the prior page and enter an amount.</p>';
?>
<script>
function goBack() {
    window.history.back();
}
</script>
<?php
echo '<button type="submit" class="goBack" onclick="goBack()">Go Back</button></div>';
}
// If there is an x_amount
else { 
?>
<form action="<?php echo $post_url ;?>" method="POST" name="gotopayeezy" id="gotopayeezy">
<?php
$x_login                                    = get_option('x_login');
$transaction_key                            = get_option('transaction_key');
$x_currency_code                            = get_option('x_currency_code'); 
$encryption_type                            = get_option('encryption_type'); 
$x_receipt_link_url                         = get_option('x_receipt_link_url');

if (!empty($_POST["x_recurring_billing"])) {
     $x_recurring_billing                   = stripslashes($_POST['x_recurring_billing']);
    }        

else { $x_recurring_billing                 = 'FALSE';}

// Beginning of Recurring
if ($x_recurring_billing === "TRUE") {
     $x_recurring_billing_id                = get_option('x_recurring_billing_id');
     $x_recurring_billing_start_date        = date('Y-m-d', strtotime("+1 month"));
     $x_recurring_billing_end_date          = date('Y-m-d', strtotime("+5 years"));
    } // End of Recurring   

$x_invoice_num                              = stripslashes($_POST['x_invoice_num']);
$x_po_num                                   = stripslashes($_POST['x_po_num']);
$x_reference_3                              = stripslashes($_POST['x_reference_3']);
$x_first_name                               = stripslashes($_POST['x_first_name']);
$x_last_name                                = stripslashes($_POST['x_last_name']);
$x_city                                     = stripslashes($_POST['x_city']);
$x_state                                    = stripslashes($_POST['x_state']);
$x_zip                                      = stripslashes($_POST['x_zip']);
$x_phone                                    = stripslashes($_POST['x_phone']);
$x_email                                    = stripslashes($_POST['x_email']);
$x_company                                  = stripslashes($_POST['x_company']);
$x_country                                  = stripslashes($_POST['x_country']);
$x_description                              = stripslashes($_POST['x_description']);
$x_user1                                    = stripslashes($_POST['x_user1']);
$x_user2                                    = stripslashes($_POST['x_user2']);
$x_user3                                    = stripslashes($_POST['x_user3']);
$x_address_raw                              = stripslashes($_POST['x_address']);
$x_address                                  = substr($x_address_raw, 0, 30); // Limits x_address to 30 characters. 

if (empty($x_invoice_num))  {
  $x_invoice_num = substr($x_first_name, 0, 1) . $x_last_name . "-" . date("zHi");
}

if ( ! empty($x_invoice_num)) {
           $x_invoice_num = $x_invoice_num;
        }
srand(time()); // initialize random generator for x_fp_sequence
$x_fp_sequence = rand(1000, 100000) + 123456;
$x_fp_timestamp = time(); // needs to be in UTC. Make sure webserver produces UTC
// The values that contribute to x_fp_hash
$hmac_data = $x_login . "^" . $x_fp_sequence . "^" . $x_fp_timestamp . "^" . $x_amount . "^" . $x_currency_code;

if ( $encryption_type == 'HMAC-SHA1' ) {
	$x_fp_hash = hash_hmac('SHA1', $hmac_data, $transaction_key); 
}

else { // MD5 
$x_fp_hash = hash_hmac('MD5', $hmac_data, $transaction_key);
}


echo ('<input name="x_login" value="' . $x_login . '" type="hidden">' );
if ( $mode == "troubleshooting" ) { 
echo ('<input name="transaction_key" value="' . $transaction_key . '" type="hidden">' );
} // end troubleshooting
echo ('<input name="x_amount" value="' . $x_amount . '" type="hidden">' );
echo ('<input name="x_fp_sequence" value="' . $x_fp_sequence . '" type="hidden">' );
echo ('<input name="x_fp_timestamp" value="' . $x_fp_timestamp . '" type="hidden">' );
echo ('<input name="x_fp_hash" value="' . $x_fp_hash . '" size="50" type="hidden">' );
echo ('<input name="x_currency_code" value="' . $x_currency_code . '" type="hidden">');
echo ('<input name="x_first_name" value="' . $x_first_name . '" type="hidden">');
echo ('<input name="x_last_name" value="' . $x_last_name . '" type="hidden">');
echo ('<input name="x_address" value="' . $x_address . '" type="hidden">');
echo ('<input name="x_city" value="' . $x_city . '" type="hidden">');
echo ('<input name="x_state" value="' . $x_state . '" type="hidden">');
echo ('<input name="x_country" value="' . $x_country . '" type="hidden">');
echo ('<input name="x_zip" value="' . $x_zip . '" type="hidden">');
if (!empty($x_email)) {
  echo ('<input name="x_email" value="' . $x_email . '" type="hidden">' );
}
if (!empty($x_phone)) {
  echo ('<input name="x_phone" value="' . $x_phone . '" type="hidden">' );
}
if (!empty($x_po_num)) {
  echo ('<input name="x_po_num" value="' . $x_po_num . '" type="hidden">' );
}
if (!empty($x_reference_3)) {
  echo ('<input name="x_reference_3" value="' . $x_reference_3 . '" type="hidden">' );
}
if (!empty($x_description)) {
  echo ('<input name="x_description" value="' . $x_description . '" type="hidden">' );
}
if (!empty($x_user1)) {
  echo ('<input name="x_user1" value="' . $x_user1 . '" type="hidden">' );
}
if (!empty($x_user2)) {
  echo ('<input name="x_user2" value="' . $x_user2 . '" type="hidden">' );
}
if (!empty($x_user3)) {
  echo ('<input name="x_user3" value="' . $x_user3 . '" type="hidden">' );
}
if (!empty($x_company)) {
  echo ('<input name="x_company" value="' . $x_company . '" type="hidden">' );
}
if ( $x_recurring_billing === 'TRUE') { // 
  echo ('<input name="x_recurring_billing" value="TRUE" type="hidden">' );
  echo ('<input name="x_recurring_billing_id" value="' . $x_recurring_billing_id . '" type="hidden">' );
  echo ('<input name="x_recurring_billing_amount" value="' . $x_amount . '" type="hidden">' );
  echo ('<input name="x_recurring_billing_start_date" value="' . $x_recurring_billing_start_date . '" type="hidden">' );
  echo ('<input name="x_recurring_billing_end_date" value="' . $x_recurring_billing_end_date . '" type="hidden">' );
}
if (empty($x_recurring_billing)) {
  echo ('<input name="x_recurring_billing" value="FALSE" type="hidden">' );
}
if (!empty($x_receipt_link_url)) {
  echo ('<input name="x_receipt_link_url" value="' . $x_receipt_link_url . '" type="hidden">' );
  echo '<input name="x_receipt_link_method" value="AUTO-POST" type="hidden">';
} ?>
<input name="x_line_item" value="Payment<|>Payment<|><?php echo $x_invoice_num ;?><|>1<|><?php echo $x_amount;?><|>N<|><|><|><|><|><|>0<|><|><|><?php echo $x_amount;?>"type="hidden">
<input name="x_invoice_num" value="<?php echo $x_invoice_num ;?>" type="hidden">
<input name="x_type" value="AUTH_CAPTURE" type="hidden">
<input type="hidden" name="x_show_form" value="PAYMENT_FORM"/>
</form>
<p class="center">Processing your<br><span class="green">$<?php echo $x_amount;?></span> credit card transaction <?php echo $x_first_name;?>.</p>
<p class="center">One moment please...</p>
<script type='text/javascript'>document.gotopayeezy.submit();</script><!-- Automaticlly sends the final request to the Payeezy Gateway -->
<?php
} // end of else < 0.01
return ob_get_clean();
} // End of function

add_shortcode('payeezy_endpoint', 'wppayeezypaymentpublishedpaypage');

// create custom plugin settings menu
add_action('admin_menu', 'wppayeezypay_create_menu');
function wppayeezypay_create_menu() {
//create new top-level menu
add_menu_page(
  'WP Payeezy Pay', // page title
   'WP Payeezy Pay', // menu title display
    'administrator', // minimum capability to view the menu
     'wp-payeezy-pay/wp-payeezy-pay.php', // the slug
      'wppayeezypay_settings_page', // callback function used to display page content
       plugin_dir_url( __FILE__ ) . 'icon.png');
//call register settings function
add_action( 'admin_init', 'register_wppayeezypay_settings' );
}
add_shortcode('wp_payeezy_payment_form', 'wppayeezypaymentform');
function register_wppayeezypay_settings() {
//register our settings
register_setting( 'wppayeezypay-group', 'x_login' );
register_setting( 'wppayeezypay-group', 'notification_email' );
register_setting( 'wppayeezypay-group', 'notification_email_subject' );
register_setting( 'wppayeezypay-group', 'transaction_key' );
register_setting( 'wppayeezypay-group', 'response_key' );
register_setting( 'wppayeezypay-group', 'x_recurring_billing_id' );
register_setting( 'wppayeezypay-group', 'x_currency_code' );
register_setting( 'wppayeezypay-group', 'x_amount' );
register_setting( 'wppayeezypay-group', 'x_user1_name' );
register_setting( 'wppayeezypay-group', 'x_user2_name' );
register_setting( 'wppayeezypay-group', 'x_user3_name' );
register_setting( 'wppayeezypay-group', 'mode' ); // Production or Demo
register_setting( 'wppayeezypay-group', 'mode2' ); // Payments of Donations
register_setting( 'wppayeezypay-group', 'button_text' );
register_setting( 'wppayeezypay-group', 'x_invoice_num_name' );
register_setting( 'wppayeezypay-group', 'x_po_num_name' );
register_setting( 'wppayeezypay-group', 'x_description_name' );
register_setting( 'wppayeezypay-group', 'x_reference_3_name' );
register_setting( 'wppayeezypay-group', 'x_phone_name' );
register_setting( 'wppayeezypay-group', 'x_email_name' );
register_setting( 'wppayeezypay-group', 'x_company_name' );
register_setting( 'wppayeezypay-group', 'x_receipt_link_url' );
register_setting( 'wppayeezypay-group', 'payment_page_url' );
register_setting( 'wppayeezypay-group', 'encryption_type' );
}

function wppayeezypay_settings_page() {
$readme_wp_payeezy_pay = plugins_url('wp-payeezy-pay/readme.txt');

?>
<div class="wp-payeezy-pay-wrap">
<style>
a {
  text-decoration: none;
}
#payeezyShortcode {
  margin-bottom:20px;
}
input[type=text],
input[type=select],
input[type=url],
input[type=email],
 .wp-admin select {
  width: 100%;
}
.x_amount {
  width: 100px !important;
}
h3 a {
  color: #000;
}
.center-button {
  margin: 0px auto;
}
input, select {
    margin: 1px;
    min-height: 30px;
}
#x_login_input,
#transaction_key_input,
#x_recurring_billing_id_input,
#response_key_input {
  font-family:'Lucida Console', Monaco, monospace;
  }
#bothLeftRight {
  background-color: transparent;
  border: 0px solid purple;
  color: #000;
  margin: 0;
  float:left;
  padding: 0px;
  width: 900px;
  overflow: auto;
  }
#leftColumn {
background-color: transparent;
border: 0px solid green;
color: #000;
margin: 0;
float:left;
padding: 0px;
width:540px; 
display: inline;
}
#rightColumn {
background-color: transparent;
border: 0px solid blue;
color: #000;
margin: 0;
float:right;
padding: none;
width: 340px;
display: inline;
}
#shortcodeDiv {
    background: none repeat scroll 0 0 #fff;
    border: 1px solid #bbb;
    color: #000;
    margin: 10px 20px 0 0;
    padding: 20px;
    width: 497px;
    }
#settingsDiv {
  background: none repeat scroll 0 0 #fff;
    border: 1px solid #bbb;
    color: #000;
    margin: 10px 20px 0 0;
    padding: 20px;
    text-shadow: none;
    width: 497px;
    }
    #blueDiv {
      background: none repeat scroll 0 0 #fff;
      border: 1px solid #bbb;
      color: #444;
      margin: 10px 0 0 0;
      float:left;
      padding: 5px 20px;
      text-shadow: none;
    }
    #blueDiv ul {
    list-style: square;
  }
  #blueDiv li {
    margin-left: 16px;
  }
  #recurringBillingIDWarning {
    font-size: 180%;
    font-weight: 700;
    color: Red;
    margin: 20px 0;
  }
  .wp-core-ui .button-primary {
    width: 100%;
    text-align: center;
}
a.center-button {
    display: block;
    text-align: center;
    border: 1px solid #666;
    color: #666;
    margin: 20px auto;
    cursor: pointer;
    font-weight: 600;
    padding: 10px 20px;
    text-decoration: none;
    width: 115px;
    text-transform: uppercase;
    letter-spacing: 2px;
    background-color: transparent !important;
    transition: all 0.2s ease-in-out;
}
a:hover.center-button {
    border: 1px solid #00a0d2;
    color: #00a0d2;
    text-decoration: none;
    background-color: transparent;
    transition: all 0.2s ease-in-out;
}
input#submit {
    width: 50%;
    margin: 0px auto;
    display: block;
}
.error.notice {
    margin-top: 30px;
    padding: 16px;
}
a#saveSettings {
    font-size: 1px;
    display: none;
}
.paymentPageURL {
  text-align: center;
}
.topHeader,
h2.topHeader,
p.topHeader {
  font-size: 118%;
}
h3.sidebar,
h3.price {
    text-align: center;
}
h3.price {
    color: green;
}

.nowrap {
  white-space:nowrap;
}

h3.pciCompliant {
    text-align: center;
}
img.aligncenter {
    margin: 0 auto;
    display: block;
}
.wp_payeezy_pay_radio {
	min-height: 16px;
}

.notes {
	padding-top: 5px;
}
</style>
<?php
// If one of the recurring modes is selected and there is not a Recurring Plan ID entered,
// a red warning appears stating one needs to be entered. 
     $x_recurring_billing_id = get_option('x_recurring_billing_id');
        if (empty($x_recurring_billing_id)) {
        if (( get_option('mode2') === "pay-rec") || ( get_option('mode2') === "donate-rec" ) || ( get_option('mode2') === "pay-rec-req" )){ 
          echo '<div class="notice error">Please enter a Recurring Billing ID. Recurring transactions will not work without a Recurring Billing ID entered.</div>';
          }
        }
      ?>
  <div class="topHeader">
  <h2>WP Payeezy Pay Version: 3.18</h2>
  <p>Updated 18 June 2023</p>
  <p>By <a href="mailto:rlrottman@gmail.com?subject=WP Payeezy Pay">Richard Rottman &#60;rlrottman@gmail.com&#62;</a></p>
  <p>Buy me a cup of coffee through <a href="https://www.paypal.me/RichardRottman/">PayPal</a> or <a href="https://www.venmo.com/u/RichardRottman/">Venmo</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="https://wordpress.org/support/plugin/wp-payeezy-pay/" target="_blank">Support Forum</a> &nbsp;&nbsp;|&nbsp;&nbsp;<a href="https://globalgatewaye4.firstdata.com/" target="_blank">Log into Payeezy</a></p>
  </div>
  <!-- both left and right columns -->
  <div id="bothLeftRight">
  <!-- left column -->
  <div id="leftColumn">
    <form method="post" action="options.php">
      <?php settings_fields( 'wppayeezypay-group' ); ?>
      <?php do_settings_sections( 'wppayeezypay-group' ); ?>
      <!-- shortcode column -->
       <div id="shortcodeDiv">
       <h3>Shortcode</h3>
      <p>This plugin creates a payment/donation form that integrates with Payeezy. To add this form to a page or post, add the following <a href="https://codex.wordpress.org/Shortcode" target="_blank">shortcode</a> in the body of the page or post:</p>
      <p style="text-align:center;font-size: 120%;font-family:'Lucida Console', Monaco, monospace;">[wp_payeezy_payment_form]</p> 
      <?php 
  $payment_page_url = get_option('payment_page_url') ;
  if (!empty($payment_page_url)) {
        ?>
       <div class="paymentPageURL"><a class="button" href="<?php echo $payment_page_url;?>" target="_blank">Link to published Payment/Donation form</a></div>
        <?php
      }?>   
      </div><!-- end of shortcode column -->
      <!-- settings column -->
      <div id="settingsDiv">
      <br>
       <h3>Required Settings</h3>
      <table class="form-table">
      <tr valign="top">
        <th scope="row"><span class="dashicons dashicons-admin-generic"></span>&nbsp;Payment Page ID</th>
          <td valign="top"><input type="text" id="x_login_input" name="x_login" value="<?php echo esc_attr( get_option('x_login') ); ?>" required></td>
      </tr>
      <tr valign="top">
      <th scope="row"><span class="dashicons dashicons-admin-network"></span>&nbsp;Transaction Key</th>  
        <td valign="top"><input type="text" id="transaction_key_input" name="transaction_key" value="<?php echo esc_attr( get_option('transaction_key') ); ?>" /></td>  
      </tr>
      <tr valign="top">
      <th scope="row"><span class="dashicons dashicons-lock"></span>&nbsp;HMAC Calculation</th>
		     	<td valign="top">
		     		 <input name="encryption_type" type="radio" class="wp_payeezy_pay_radio" value="HMAC-MD5" <?php checked( 'HMAC-MD5', get_option( 'encryption_type' ) ); ?> /> HMAC-MD5&nbsp;&nbsp;&nbsp;&nbsp;
<input name="encryption_type" type="radio" class="wp_payeezy_pay_radio" value="HMAC-SHA1" <?php checked( 'HMAC-SHA1', get_option( 'encryption_type' ) ); ?> /> HMAC-SHA1<br>
        <p class="notes">Match the encryption type the payment page is set to on 
        	<a href="https://globalgatewaye4.firstdata.com/payment_pages/" target="_blank">Payeezy</a> under 9. Security in settings.</p></td>
       </tr>
        <tr valign="top">
      <th scope="row">Currency Code</th>  
       <td><select name="x_currency_code">
        <option value="USD" <?php if( get_option('x_currency_code') == "USD" ): echo 'selected'; endif;?> >USD (United States Dollar)</option>
        <option value="AUD" <?php if( get_option('x_currency_code') == "AUD" ): echo 'selected'; endif;?> >AUD (Australian Dollar)</option>
        <option value="BRL" <?php if( get_option('x_currency_code') == "BRL" ): echo 'selected'; endif;?> >BRL (Brazilian Real)</option>
        <option value="CZK" <?php if( get_option('x_currency_code') == "CZK" ): echo 'selected'; endif;?> >CZK (Czech Koruna)</option>
        <option value="DKK" <?php if( get_option('x_currency_code') == "DKK" ): echo 'selected'; endif;?> >DKK (Danish Krone)</option>
        <option value="EUR" <?php if( get_option('x_currency_code') == "EUR" ): echo 'selected'; endif;?> >EUR (Euro)</option>
        <option value="HKD" <?php if( get_option('x_currency_code') == "HKD" ): echo 'selected'; endif;?> >HKD (Hong Kong Dollar)</option>
        <option value="HUF" <?php if( get_option('x_currency_code') == "HUF" ): echo 'selected'; endif;?> >HUF (Hungarian Forint)</option>
        <option value="ILS" <?php if( get_option('x_currency_code') == "ILS" ): echo 'selected'; endif;?> >ILS (Israeli New Sheqel)</option>
        <option value="JPY" <?php if( get_option('x_currency_code') == "JPY" ): echo 'selected'; endif;?> >JPY (Japanese Yen)</option>
        <option value="MYR" <?php if( get_option('x_currency_code') == "MYR" ): echo 'selected'; endif;?> >MYR (Malaysian Ringgit)</option>
        <option value="MXN" <?php if( get_option('x_currency_code') == "MXN" ): echo 'selected'; endif;?> >MXN (Mexican Peso)</option>
        <option value="NOK" <?php if( get_option('x_currency_code') == "NOK" ): echo 'selected'; endif;?> >NOK (Norwegian Krone)</option>
        <option value="NZD" <?php if( get_option('x_currency_code') == "NZD" ): echo 'selected'; endif;?> >NZD (New Zealand Dollar)</option>
        <option value="PHP" <?php if( get_option('x_currency_code') == "PHP" ): echo 'selected'; endif;?> >PHP (Philippine Peso)</option>
        <option value="PLN" <?php if( get_option('x_currency_code') == "PLN" ): echo 'selected'; endif;?> >PLN (Polish Zloty)</option>
        <option value="CZK" <?php if( get_option('x_currency_code') == "CZK" ): echo 'selected'; endif;?> >CZK (Czech Koruna)</option>
        <option value="GBP" <?php if( get_option('x_currency_code') == "GBP" ): echo 'selected'; endif;?> >GBP (Pound Sterling)</option>
        <option value="SGD" <?php if( get_option('x_currency_code') == "SGD" ): echo 'selected'; endif;?> >SGD (Singapore Dollar)</option>
        <option value="SEK" <?php if( get_option('x_currency_code') == "SEK" ): echo 'selected'; endif;?> >SEK (Swedish Krona)</option>
        <option value="CHF" <?php if( get_option('x_currency_code') == "CHF" ): echo 'selected'; endif;?> >CHF (Swiss Franc)</option>
        <option value="TWD" <?php if( get_option('x_currency_code') == "TWD" ): echo 'selected'; endif;?> >TWD (Taiwan New Dollar)</option>
        <option value="THB" <?php if( get_option('x_currency_code') == "THB" ): echo 'selected'; endif;?> >THB (Thai Baht)</option>
        <option value="TRY" <?php if( get_option('x_currency_code') == "TRY" ): echo 'selected'; endif;?> >TRY (Turkish Lira)</option>
      </select><br>
        <p class="notes">Needs to match the Currency Code of the terminal.</p>
        </td>
      </tr>
      <tr valign="top">
        <th scope="row">Mode</th>  
          <td><select name="mode"/>
            <option value="live" <?php if( get_option('mode') == "live" ): echo 'selected'; endif;?> >Live</option>
            <option value="demo" <?php if( get_option('mode') == "demo" ): echo 'selected'; endif;?> >Demo</option>
            <option value="troubleshooting" <?php if( get_option('mode') == "troubleshooting" ): echo 'selected'; endif;?> >Troubleshooting</option>
            </select>
          </td>
      </tr>
      <tr valign="top">
        <th scope="row">Type of Transactions</th>  
          <td><select name="mode2"/>
            <option value="pay" <?php if( get_option('mode2') == "pay" ): echo 'selected'; endif;?> >Payments</option>
            <option value="pay-rec" <?php if( get_option('mode2') == "pay-rec" ): echo 'selected'; endif;?> >Payments with optional Recurring</option>
            <option value="pay-rec-req" <?php if( get_option('mode2') == "pay-rec-req" ): echo 'selected'; endif;?> >Payments with automatic Recurring</option>
            <option value="donate" <?php if( get_option('mode2') == "donate" ): echo 'selected'; endif;?> >Donations</option>
            <option value="donate-rec" <?php if( get_option('mode2') == "donate-rec" ): echo 'selected'; endif;?> >Donations with optional Recurring</option>
          </select>
          </td>
      </tr>
      <tr valign="top">
        <th scope="row">Button Text</th>  
         <td><select name="button_text"/>
            <option value="pay-now" <?php if( get_option('button_text') == "pay-now" ): echo 'selected'; endif;?> >Pay Now</option>
            <option value="donate-now" <?php if( get_option('button_text') == "donate-now" ): echo 'selected'; endif;?> >Donate Now</option>
            <option value="continue" <?php if( get_option('button_text') == "continue" ): echo 'selected'; endif;?> >Continue</option>
            <option value="continue-to-secure" <?php if( get_option('button_text') == "continue-to-secure" ): echo 'selected'; endif;?> >Continue to Secure Payment Form</option>
            <option value="continue-to-secure-donation" <?php if( get_option('button_text') == "continue-to-secure-donation" ): echo 'selected'; endif;?> >Continue to Secure Donation Form</option>
            </select>
      </tr>
    </table>
    <hr>
      <h3>Optional Settings</h3>
      <table class="form-table">
        <tr valign="top">
        <th scope="row">Payment/Donation Page URL</th>  
          <td><input type="url" name="payment_page_url" value="<?php echo esc_attr( get_option('payment_page_url') ); ?>"/>
               <p class="notes">Optional. Creates a button at the top of this page that takes you to the page/post where the shortcode is used.</p></td> 
      </tr> 
      <tr valign="top">
      <th scope="row">Amount</th>  
        <td valign="top"><span class="large">$</span> <input type="text" class="x_amount" name="x_amount" value="<?php echo esc_attr( get_option('x_amount') ); ?>" /><br><p class="notes">If an amount is entered above, the card holder will not have the option of entering an amount. They will be charged what you enter here.</p></td></tr>
      <tr valign="top">
       <th scope="row">Monthly Recurring Billing ID</th>  
                <td valign="top"><input type="text" id="x_recurring_billing_id_input" name="x_recurring_billing_id" value="<?php echo esc_attr( get_option('x_recurring_billing_id') ); ?>" /><br>
          <p class="notes">Leave blank unless processing recurring transactions. The recurring plan <b>must</b> have the frequency set to "Monthly." Other recurring frequencies (weekly, biweekly, quarterly and yearly) as well as monthly are available with <a href="https://richard-rottman.com/payeezy-pay/" target="_blank">Payeezy Pay</a> and <a href="https://richard-rottman.com/payeezy-donate/" target="_blank">Payeezy Donate</a>.</td>
        </p></tr>
      </table>
      <hr>
        <h3>WP Payeezy Results</h3>
        <table class="form-table">
        <tr valign="top"><p class="notes">The following settings are for <strong><a href="https://richard-rottman.com/wp-payeezy-results/" target="_blank" >WP Payeezy Results</a></strong>. If you are not using this premium add-on plugin for WP Payeezy Pay, leave these fields blank.</p></tr>
        <tr valign="top">
          <th scope="row"><span class="dashicons dashicons-admin-network"></span>&nbsp;Response Key</th>  
          <td valign="top"><input type="text" id="response_key_input" name="response_key" value="<?php echo esc_attr( get_option('response_key') ); ?>"/><br>
          </td> 
      </tr>   
      <tr valign="top">
          <th scope="row"><span class="dashicons dashicons-admin-links"></span>&nbsp;Receipt Page URL</th>  
            <td valign="top"><input type="url" class="x_receipt_link_url" name="x_receipt_link_url" value="<?php echo esc_attr( get_option('x_receipt_link_url') ); ?>" /></td></tr>
        <tr valign="top">
          <th scope="row"><span class="dashicons dashicons-email-alt"></span>&nbsp;Notification Email</th>  
            <td valign="top"><input type="email" class="notification_email" name="notification_email" value="<?php echo esc_attr( get_option('notification_email') ); ?>" /></td></tr>
        <tr valign="top">
          <th scope="row"><span class="dashicons dashicons-email-alt"></span>&nbsp;Notification Email Subject</th>  
            <td valign="top"><input type="text" class="notification_email_subject" name="notification_email_subject" value="<?php echo esc_attr( get_option('notification_email_subject') ); ?>" /></td></tr>
    </table>
    <hr>
    <h3>Optional Payment Form Fields</h3>
    <table class="form-table">
      <tr valign="top"><p class="notes">If you would like to use any of these fields, just assign a name to them
        and they will appear on your form with that name. Do not assign a name, and they will not appear.</p></tr>
      <tr valign="top">
        <th scope="row">x_invoice_num</th>
        <td><input type="text" name="x_invoice_num_name" value="<?php echo esc_attr( get_option('x_invoice_num_name') ); ?>" /></td>
      </tr>
      <tr valign="top">
      <th scope="row">x_po_num</th>
      <td><input type="text" name="x_po_num_name" value="<?php echo esc_attr( get_option('x_po_num_name') ); ?>" /></td>
      </tr>
      <tr valign="top">
      <th scope="row">x_reference_3</th>
      <td><input type="text" name="x_reference_3_name" value="<?php echo esc_attr( get_option('x_reference_3_name') ); ?>" /></td>
      </tr>
      <tr valign="top">
        <th scope="row">x_user1</th>
        <td><input type="text" name="x_user1_name" value="<?php echo esc_attr( get_option('x_user1_name') ); ?>" /></td>
      </tr>
      <tr valign="top">
        <th scope="row">x_user2</th>
        <td><input type="text" name="x_user2_name" value="<?php echo esc_attr( get_option('x_user2_name') ); ?>" /></td>
      </tr>
      <tr valign="top">
        <th scope="row">x_user3</th>
        <td><input type="text" name="x_user3_name" value="<?php echo esc_attr( get_option('x_user3_name') ); ?>" /></td>
      </tr>
      <tr valign="top">
       <th scope="row">x_phone</th>
        <td><input type="text" name="x_phone_name" value="<?php echo esc_attr( get_option('x_phone_name') ); ?>" /></td>
      </tr>
      <tr valign="top">
        <th scope="row">x_email</th>
        <td><input type="text" name="x_email_name" value="<?php echo esc_attr( get_option('x_email_name') ); ?>" /></td>
     </tr>
      <tr valign="top">
      <th scope="row">x_description</th>
      <td><input type="text" name="x_description_name" value="<?php echo esc_attr( get_option('x_description_name') ); ?>" /></td>
      </tr>
      <tr valign="top">
        <th scope="row">x_company</th>
        <td><input type="text" name="x_company_name" value="<?php echo esc_attr( get_option('x_company_name') ); ?>" /></td>
      </tr>
    </table>
    <a id="saveSettings">saveSettings</a>
    <?php submit_button('Save WP Payeezy Pay Settings'); 
   ?>
</form>
 </div><!-- end of settings column -->
 </div><!-- end of left column -->
 <div id="rightColumn">
  <div id="blueDiv">
<h3 class="sidebar"><a class="h3" href="https://richard-rottman.com/wp-payeezy-results/" target="_blank"><span class="dashicons dashicons-admin-plugins"></span>&nbsp;WP Payeezy Results</a></h3>
<h3 class="price">$49.99</h2>
    <p><strong>WP Payeezy Results</strong> is a premium plugin that allows Payeezy to send information back to WordPress after a successful transaction. It displays a receipt for the cardholder on your website that they can print for their records. Information about the transaction is then stored in WordPress.</p>
    <p>WordPress will then send an email containing all of the transaction information you want in an easy-to-read format. </p>
    <h3 class="pciCompliant">100% PCI DCC Compliant</h3>
    <p><a class="center-button" href="https://richard-rottman.com/wp-payeezy-results/" target="_blank">Learn More</a></p>
    </div>
<div id="blueDiv"> 
<h3 class="sidebar"><a href="https://richard-rottman.com/payeezy-pay/" target="_blank"><span class="dashicons dashicons-admin-plugins"></span>&nbsp;Payeezy Pay</a></h3>
<h3 class="price">$49.99</h2>
<ul>
<li>Creates up to six unique and independent payment forms</li>
<li>Supports multiple terminals</li>
<li>Supports weekly, biweekly, monthly, quarterly, and yearly Recurring payments</li>
<li>Supports convenience fees, surcharges, or service fees</li>
<li>Supports Level 3 transactions</li>
<li>Supports Soft Descriptors</li>
<li>Select which fields to display and which fields are required</li>
<li>Can be run with Payeezy Donate and/or WP Payeezy Pay</li>
<li>Comes with lifetime support and free updates</li>
</ul>
</p>
<p><a class="center-button" href="https://richard-rottman.com/payeezy-pay/" target="_blank">Learn More</a></p>
 </div>
<div id="blueDiv">
<h3 class="sidebar"><a href="https://richard-rottman.com/payeezy-donate/" target="_blank"><span class="dashicons dashicons-admin-plugins"></span>&nbsp;Payeezy Donate</a></h3>
<h3 class="price">$49.99</h2>
<ul>
<li>Creates up to six unique and independent donation forms</li>
<li>Choose the donation amounts available to the cardholder. 
<li>Choose to allow a custom donation amount</li>  
<li>Choose standard donation radio buttons or buttons spaced horizontally with the donation amount inside the button
<li>Supports weekly, biweekly, monthly, quarterly, and yearly Recurring donations</li>
<li>Select which optional fields to display and which of them are required</li>
<li>Can be run with Payeezy Pay and/or WP Payeezy Pay</li>
<li>Comes with lifetime support and free updates</li>
</ul>
<p><a class="center-button" href="https://richard-rottman.com/payeezy-donate/" target="_blank">Learn More</a></p>
 </div>
 </div>
 <hr>
</div>
<?php }