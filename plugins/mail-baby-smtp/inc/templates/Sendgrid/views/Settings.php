<div class = 'wp_mailplus_settings_notification'> </div>
<div class = 'container-fluid add-top-margin'>
    <div class = 'row'>
	<div class = 'col-md-5'>
	    <h3 class = 'wp_mp_h3'>
		<span class="dashicons dashicons-email dash-email-icon"></span>
		<span class = 'mc_settings_header'> Mail SendGrid Plus </span>
	    </h3>
	</div>
    </div>
    <hr class = 'dashed'>
    <div class = 'service-selection'>
	<form name = 'wp-email-services' method = 'POST' action = ''>
	    <?php echo wp_nonce_field( 'MailPlusSettings', '_mailplus_wpnonce' ); ?>
	    <div class = 'form-group'>
		<label for = "from_name"> From Name </label>
		<input type = "text" class = "form-control" name = "from_name" id = "from_name" placeholder = "From Name" style = 'width: 300px;' value = '<?php echo esc_attr($from_info['from_name']); ?>' >
	    </div>
	    <div class = 'form-group'>
		<label for = "from_email"> From Email </label>
		<input type = "text" class = "form-control" name = "from_email" id = "from_email" placeholder = "From Email" style = 'width: 300px;' value = '<?php echo esc_attr($from_info['from_email']); ?>' >
	    </div>
	    <div class = 'form-group'>
		<label class = 'service-selection-label'> Select Email Service: </label>
	    </div>
	    <div class = 'form-group'>

		<div class="radio">
		    <label> <input type="radio" onclick = 'getServiceDetails(this.value)' class = 'settings-radio' name="email_service" id="smtp" value="smtp" <?php if($email_service == 'smtp') { echo 'checked'; } ?> > SMTP </label>
		</div>
		<div class="radio">
		    <label> <input type="radio" onclick = 'getServiceDetails(this.value)' class = 'settings-radio' name="email_service" id="sendgrid" value="sendgrid" <?php if($email_service == 'sendgrid') { echo 'checked'; } ?> > Sendgrid </label>
		</div>
	    </div>
	    <!-- SMTP -->
	    <div id = 'smtp-form' class = 'service-forms' style = 'display: <?php if($email_service == 'smtp') { echo 'show'; } else { echo 'none'; } ?>;'>
		<div class = 'row'>
		    <div class = 'col-md-5'>
			<h3 class = 'wp_mp_h3'>
			    <span class = 'mc_settings_header'> SMTP Details </span>
			</h3>
		    </div>
		</div>
		<hr class = 'dashed'>
		<div class = 'form-group'>
		    <div class="form-group">
			<label for = "smtp_host"> Host </label>
			<input type = "text" class = "form-control" name = 'smtp_host' id = "smtp_host" placeholder = "smtp.gmail.com" style = 'width: 250px;' value = '<?php echo esc_attr($data['smtp_host']); ?>' >
		    </div>
		    <div class="form-group">
			<label for = "smtp_port"> Port </label>
			<input type = "text" class = "form-control" name = 'smtp_port' id = "smtp_port" placeholder = "465" style = 'width: 50px;' value = '<?php echo esc_attr($data['smtp_port']); ?>' >
		    </div>
		    <div class="form-group">
			<label for = "smtp_encrption"> Encryption </label>
			<div class = 'radio'>
			    <label> <input type = "radio" class = 'settings-radio' name = "smtp_encryption" value = 'none' <?php if($data['smtp_encryption'] == 'none') { echo 'checked'; } ?> > No Encryption </label>
			</div>
			<div class = 'radio'>
			    <label> <input type = "radio" class = 'settings-radio' name = "smtp_encryption" value = 'ssl' <?php if($data['smtp_encryption'] == 'ssl') { echo 'checked'; } ?> > SSL </label>
			</div>
			<div class = 'radio'>
			    <label> <input type = "radio" class = 'settings-radio' name = "smtp_encryption" value = 'tls' <?php if($data['smtp_encryption'] == 'tls') { echo 'checked'; } ?> > TLS </label>
			</div>
		    </div>
		    <div class="form-group">
			<label for = "smtp_username"> Username </label>
			<input type = "text" class = "form-control" name = "smtp_username" id = "smtp_username" placeholder = "Username" style = 'width: 250px;' value = '<?php echo esc_attr($data['smtp_username']); ?>' >
		    </div>
		    <div class="form-group">
			<label for = "smtp_password"> Password </label>
			<input type = "password" class = "form-control" name = "smtp_password" id = "smtp_password" placeholder = "Password" style = 'width: 250px;' value = '<?php echo esc_attr($data['smtp_password']); ?>' >
		    </div>
		</div>
	    </div>
	    <!-- Sendgrid -->
	    <div id = 'sendgrid-form' class = 'service-forms' style = 'display: <?php if($email_service == 'sendgrid') { echo 'show'; } else { echo 'none'; } ?>;'>
		<div class = 'row'>
		    <div class = 'col-md-5'>
			<h3 class = 'wp_mp_h3'>
			    <span class = 'mc_settings_header'> Sendgrid </span>
			</h3>
		    </div>
		</div>
		<hr class = 'dashed'>
		<div class = 'form-group'>
		    <label for = "sendgrid_api_key"> API key </label>
		    <input type = "text" class = "form-control" name = 'sendgrid_api_key' id = "sendgrid_api_key" placeholder = "API key" style = 'width: 650px;' value = '<?php echo esc_attr($data['sendgrid_api_key']); ?>' >
		    <i class = 'sendgrid-apikey-generation-link'> <a target = '_blank' href = 'https://app.sendgrid.com/settings/api_keys'> Click here to get API key </a> </i>
		    <div class = 'form-group'>

		</div>
	    </div>
	    <div class = 'form-group'>
		<div> <button type = 'submit'  class = 'button button-primary button-large' onclick="myFunction()"> Save </button> </div>
<script>
function myFunction() {
	swal('Success!', 'Settings Saved Successfully', 'success')
}
</script>   
	    </div>
	</form>
    </div>
</div>
<?php
if(isset($_POST['swcm_target_emailId'])){
	$subject='This is a TestMail from SendGrid.net';
	$to=sanitize_text_field($_POST['swcm_target_emailId']);
	$from=sanitize_email($_POST['from_email']);
	$message='WP SendGrid Mail is Working successfully';
	$headers = "From: " . $subject. "<$from>" . "\r\n";
	$headers.= 'MIME-Version: 1.0' . "\r\n";
	$headers.= "Content-type: text/html; charset=iso-8859-1 \r\n";
	$send= new WPMailPlus\Integrations\SendGridService();
	$testmail=$send->send_mail($to,$subject,$message ,$headers);
}
