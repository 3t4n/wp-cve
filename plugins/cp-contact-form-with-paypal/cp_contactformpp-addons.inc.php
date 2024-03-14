<?php

if ( !defined('CP_CONTACTFORMPP_AUTH_INCLUDE') ) { echo 'Direct access not allowed.';  exit; }

if ( !is_admin() ) 
{
    echo 'Direct access not allowed.';
    exit;
}

$message = "";


?>
<style>
	.clear{clear:both;}
	.ahb-addons-container {
		border: 1px solid #e6e6e6;
		padding: 20px;
		border-radius: 3px;
		-webkit-box-flex: 1;
		flex: 1;
		margin: 1em 1em 1em 0;
		min-width: 200px;
		background: white;
		position:relative;
	}
	.ahb-addons-container h2{margin:0 0 20px 0;padding:0;}
	.ahb-addon{border-bottom: 1px solid #efefef;padding: 10px 0;}
	.ahb-addon:first-child{border-top: 1px solid #efefef;}
	.ahb-addon:last-child{border-bottom: 0;}
	.ahb-addon label{font-weight:600;}
	.ahb-addon p{font-style:italic;margin:5px 0 0 0;}
	.ahb-first-button{margin-right:10px !important;}
    
    .ahb-buttons-container{margin:1em 1em 1em 0;}
    .ahb-return-link{float:right;}

	.ahb-disabled-addons {
		background: #f9f9f9;
	}
	.ahb-addons-container h2{margin-left:30px;}
	.ahb-disabled-addons *{
		color:#888888;
	}
	.ahb-disabled-addons input{
		pointer-events: none !important;
	}

	/** For Ribbon **/
	.ribbon {
		position: absolute;
		left: -5px; top: -5px;
		z-index: 1;
		overflow: hidden;
		width: 75px; height: 75px;
		text-align: right;
	}
	.ribbon span {
		font-size: 10px;
		font-weight: bold;
		color: #FFF;
		text-transform: uppercase;
		text-align: center;
		line-height: 20px;
		transform: rotate(-45deg);
		-webkit-transform: rotate(-45deg);
		width: 100px;
		display: block;
		background: #79A70A;
		background: linear-gradient(#2989d8 0%, #1e5799 100%);
		box-shadow: 0 3px 10px -5px rgba(0, 0, 0, 1);
		position: absolute;
		top: 19px; left: -21px;
	}
	.ribbon span::before {
		content: "";
		position: absolute; left: 0px; top: 100%;
		z-index: -1;
		border-left: 3px solid #1e5799;
		border-right: 3px solid transparent;
		border-bottom: 3px solid transparent;
		border-top: 3px solid #1e5799;
	}
	.ribbon span::after {
		content: "";
		position: absolute; right: 0px; top: 100%;
		z-index: -1;
		border-left: 3px solid transparent;
		border-right: 3px solid #1e5799;
		border-bottom: 3px solid transparent;
		border-top: 3px solid #1e5799;
	}
</style>


<a id="top"></a>

<h1>CP Contact Form with PayPal - Add Ons</h1>


<div class="ahb-buttons-container">
	<a href="<?php print esc_attr(admin_url('admin.php?page=cp_contact_form_paypal.php'));?>" class="ahb-return-link">&larr;<?php _e('Return to the calendars list','cp-contact-form-with-paypal'); ?></a>
	<div class="clear"></div>
</div>


<!-- Disabled Add Ons -->
<h2><?php _e('Add Ons available in Platinum version of the plugin','cp-contact-form-with-paypal'); ?></h2>

<div class="ahb-addons-container ahb-disabled-addons">
	<div class="ribbon"><span><?php _e('Upgrade','cp-contact-form-with-paypal'); ?></span></div>
	<h2><?php _e('Payment Gateways Integration','cp-contact-form-with-paypal'); ?></h2>
	<div class="ahb-addons-group">
		<div class="ahb-addon">
			<label><input type="checkbox" disabled>Authorize.net Server Integration Method</label>
			<p>The add-on adds support for Authorize.net Server Integration Method payments</p>
		</div>
		<div class="ahb-addon">
			<label><input type="checkbox" disabled>iDeal Mollie</label>
			<p>The add-on adds support for iDeal via Mollie payments</p>
		</div>
		<div class="ahb-addon">
			<label><input type="checkbox" disabled>iDeal TargetPay</label>
			<p>The add-on adds support for iDeal via TargetPay payments</p>
		</div>
		<div class="ahb-addon">
			<label><input type="checkbox" disabled>PayPal Pro</label>
			<p>The add-on adds support for PayPal Payment Pro payments to accept credit cars directly into the website</p>
		</div>
		<div class="ahb-addon">
			<label><input type="checkbox" disabled>RedSys TPV</label>
			<p>The add-on adds support for RedSys TPV payments</p>
		</div>
		<div class="ahb-addon">
			<label><input type="checkbox" disabled>SagePay Payment Gateway</label>
			<p>The add-on adds support for SagePay payments</p>
		</div>
		<div class="ahb-addon">
			<label><input type="checkbox" disabled>SagePayments Payment Gateway</label>
			<p>The add-on adds support for SagePayments payments</p>
		</div>
		<div class="ahb-addon">
			<label><input type="checkbox" disabled>Skrill Payments Integration</label>
			<p>The add-on adds support for Skrill payments</p>
		</div>
		<div class="ahb-addon">
			<label><input type="checkbox" disabled>Stripe</label>
			<p>The add-on adds support for Stripe payments</p>
		</div>
	</div>
</div>
<div class="ahb-to-top"><a href="#top">&uarr; <?php _e('Top','cp-contact-form-with-paypal'); ?></a></div>

<div class="ahb-addons-container ahb-disabled-addons">
	<div class="ribbon"><span><?php _e('Upgrade','cp-contact-form-with-paypal'); ?></span></div>
	<h2><?php _e('Integration with third party plugin','cp-contact-form-with-paypal'); ?>s</h2>
	<div class="ahb-addons-group">
		<div class="ahb-addon">
			<label><input type="checkbox" disabled>WooCommerce</label>
			<p>The add-on allows integrate the forms with WooCommerce products</p>
		</div>
	</div>
</div>
<div class="ahb-to-top"><a href="#top">&uarr; <?php _e('Top','cp-contact-form-with-paypal'); ?></a></div>

<div class="ahb-addons-container ahb-disabled-addons">
	<div class="ribbon"><span><?php _e('Upgrade','cp-contact-form-with-paypal'); ?></span></div>
	<h2><?php _e('Improvements','cp-contact-form-with-paypal'); ?></h2>
	<div class="ahb-addons-group">
		<div class="ahb-addon">
			<label><input type="checkbox" disabled>iCal Export Attached</label>
			<p>The add-on allows to attach an iCal file with the date of a field</p>
		</div> 
      	<div class="ahb-addon">
			<label><input type="checkbox" disabled>IP2Country</label>
			<p>The add-on IP-to-Country identification and links the PayPal country to it</p>
		</div>         
      	<div class="ahb-addon">
			<label><input type="checkbox" disabled>Post Creation</label>
			<p>The add-on adds allows to create and publish a post after processing the form/payment</p>
		</div>    
		<div class="ahb-addon">
			<label><input type="checkbox" disabled>Signature Fields</label>
			<p>The add-on allows to replace form fields with "Signature" fields</p>
		</div>
	</div>
</div>
<div class="ahb-to-top"><a href="#top">&uarr; <?php _e('Top','cp-contact-form-with-paypal'); ?></a></div>

<div class="ahb-addons-container ahb-disabled-addons">
	<div class="ribbon"><span><?php _e('Upgrade','cp-contact-form-with-paypal'); ?></span></div>
	<h2><?php _e('Integration with third party services','cp-contact-form-with-paypal'); ?></h2>
	<div class="ahb-addons-group">
		<div class="ahb-addon">
			<label><input type="checkbox" disabled>MailChimp</label>
			<p>The add-on creates MailChimp List members with the submitted information</p>
		</div>
		<div class="ahb-addon">
			<label><input type="checkbox" disabled>reCAPTCHA</label>
			<p>The add-on allows to protect the forms with reCAPTCHA service of Google</p>
		</div>
		<div class="ahb-addon">
			<label><input type="checkbox" disabled>SalesForce</label>
			<p>The add-on allows create SalesForce leads with the submitted information</p>
		</div>
		<div class="ahb-addon">
			<label><input type="checkbox" disabled>WebHook</label>
			<p>The add-on allows put the submitted information to a webhook URL, and integrate the forms with the Zapier service</p>
		</div>
	</div>
</div>
<div class="ahb-to-top"><a href="#top">&uarr; <?php _e('Top','cp-contact-form-with-paypal'); ?></a></div>

<div class="ahb-addons-container ahb-disabled-addons">
	<div class="ribbon"><span><?php _e('Upgrade','cp-contact-form-with-paypal'); ?></span></div>
	<h2><?php _e('SMS Text Delivery','cp-contact-form-with-paypal'); ?></h2>
	<div class="ahb-addons-group">
		<div class="ahb-addon">
			<label><input type="checkbox" disabled>Twilio</label>
			<p>The add-on allows to send notification and reminder messages (SMS) via Twilio</p>
		</div>
		<div class="ahb-addon">
			<label><input type="checkbox" disabled>Clickatell</label>
			<p>(SMS) via Clickatell</p>
		</div>
	</div>
</div>
<div class="ahb-to-top" style="margin-bottom:10px;"><a href="#top">&uarr; <?php _e('Top','cp-contact-form-with-paypal'); ?></a></div>

<input type="button" value="<?php _e('Get The Full List of Add Ons','cp-contact-form-with-paypal'); ?>" onclick="document.location='?page=cp_contact_form_paypal_upgrade';"class="button button-primary ahb-first-button" />
<div class="clear"></div>