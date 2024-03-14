<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


// admin table
function cf7rzp_settings() {

	// get options
	$options = get_option('cf7rzp_options');

	if ( !current_user_can( "manage_options" ) )  {
	wp_die( __( "You do not have sufficient permissions to access this page." ) );
	}



	// save and update options
	if (isset($_POST['update'])) {

		$options['mode'] = 						sanitize_text_field($_POST['mode']);
		if (empty($options['mode'])) { 			$options['mode'] = '2'; }

        $options['rzp_key_id'] = 				sanitize_text_field($_POST['rzp_key_id']);
		if (empty($options['rzp_key_id'])) { 	$options['rzp_key_id'] = ''; }

		$options['rzp_key_secret'] = 			sanitize_text_field($_POST['rzp_key_secret']);
		if (empty($options['rzp_key_secret'])) { $options['rzp_key_secret'] = ''; }

        $options['rzp_cmp_name'] = 			    sanitize_text_field($_POST['rzp_cmp_name']);
		if (empty($options['rzp_cmp_name'])) { $options['rzp_cmp_name'] = ''; }

        /*$options['rzp_cmp_logo'] = 			    sanitize_text_field($_POST['rzp_cmp_logo']);
		if (empty($options['rzp_cmp_logo'])) { $options['rzp_cmp_logo'] = ''; }*/

		$options['return_url'] = 					sanitize_text_field($_POST['return_url']);
		if (empty($options['return_url'])) { 		$options['return_url'] = ''; }

		$options['cancel_url'] = 					sanitize_text_field($_POST['cancel_url']);
		if (empty($options['cancel_url'])) { 		$options['cancel_url'] = ''; }

		/*$options = apply_filters("cf7rzp_admin_rzp_settings_options", $options, $_POST);*/

		$options_old = $options;
		
		array_merge($options, $options_old);
		
		update_option("cf7rzp_options", $options);

		echo "<br /><div class='updated'><p><strong>"; _e("Settings Updated."); echo "</strong></p></div>";

	}









	if (empty($options['mode'])) { 						$options['mode'] = '2'; }
	if (empty($options['rzp_key_id'])) { 				$options['rzp_key_id'] = ''; }
	if (empty($options['rzp_key_secret'])) {			$options['rzp_key_secret'] = ''; }
	if (empty($options['return_url'])) { 			    $options['return_url'] = ''; }
	if (empty($options['cancel_url'])) { 				$options['cancel_url'] = ''; }
	
	$siteurl = get_site_url();

	?>


<form method='post'>

	<table width='100%'><tr><td>
	<div class='wrap'><h2>Razorpay Settings</h2></div><br /></td><td><br />
	</td></tr></table>

	<table width='100%'>
        <tr><td valign='top'>
            
            <div style="background-color:#E4E4E4;padding:8px;color:#000;font-size:15px;color:#464646;font-weight: 700;border-bottom: 1px solid #CCCCCC;">
            &nbsp; Razorpay Account
            </div>
            <div style="background-color:#fff;padding:8px;">

                <table width='100%'>
                    <tr>
                        <td class='cf7rzp_width'><b>Sandbox Mode:</b></td>
                        <td>
                            <input <?php if ($options['mode'] == "1") { echo "checked='checked'"; } ?> type='radio' name='mode' value='1'>On (Sandbox mode)
                            <input <?php if ($options['mode'] == "2") { echo "checked='checked'"; } ?> type='radio' name='mode' value='2'>Off (Live mode)
                        </td>
                    </tr>
                    <tr>
                        </td><td>
                        <td><br /></td>
                    </tr>
                    <tr>
                        <td class='cf7rzp_width'>
                            <b>Key ID: </b></td><td><input type='text' size=40 name='rzp_key_id' value='<?php echo esc_attr($options['rzp_key_id']); ?>'> Required to use Razorpay
                        </td>
                    </tr>
                    <tr>
                        </td><td>
                        <td><br /></td>
                    </tr>
                    <tr>
                        <td class='cf7rzp_width'>
                            <b>Key Secret: </b>
                        </td>
                        <td>
                            <input type='text' size=40 name='rzp_key_secret' value='<?php echo esc_attr($options['rzp_key_secret']); ?>'> Required to use Razorpay
                        </td>
                    </tr>
                    <tr>
                        </td><td>
                        <td><br />
                        The key Id and key secret can be generated from "API Keys" section of Razorpay Dashboard. Use test or live for test or live mode.
                            <br /><br />
                        </td>
                    </tr>
                </table>

            </div>
            <!-- ***************************************** -->
            <div style="background-color:#E4E4E4;padding:8px;font-size:15px;color:#464646;font-weight: 700;border-bottom: 1px solid #CCCCCC;">
			&nbsp; Other Settings
            </div>
            <div style="background-color:#fff;padding:8px;">

                <table style="width: 100%;">

                    <tr>
                        <td class='cf7rzp_width'>
                            <b>Company Name: </b>
                        </td>
                        <td>
                            <input type='text' size=40 name='rzp_cmp_name' value='<?php echo esc_attr($options['rzp_cmp_name']); ?>'> Optional
                        </td>
                    </tr>
                    <tr>
                        <td class='cf7rzp_width'></td>
                        <td>This will be displayed in Razorpay Payment Popup. <br/>Example: Acme Corp. </td>
                    </tr>
                    <tr><td><br /></td></tr>
                    <!--<tr>
                        <td class='cf7rzp_width'>
                            <b>Company Logo(URL): </b>
                        </td>
                        <td>
                            <input type='text' size=40 name='rzp_cmp_logo' value='<?php /*echo $options['rzp_cmp_logo'];*/ ?>'> Optional
                        </td>
                    </tr>
                    <tr>
                        <td class='cf7rzp_width'></td>
                        <td>This will be displayed in Razorpay Payment Popup. Choose a square image of minimum dimensions 256x256 px. <br/>Example: https://cdn.razorpay.com/logos/FFATTsJeURNMxx_medium.png </td>
                    </tr>
                    <tr><td><br /></td></tr>-->
                    <tr><td class='cf7rzp_width'><b>Return URL: </b></td><td><input type='text' size=40 name='return_url' value='<?php echo esc_attr($options['return_url']); ?>'> Optional <br /></td></tr>
                    <tr><td class='cf7rzp_width'></td><td>If the customer makes succesful Razorpay Payment, where are they redirected to after. <br/>Example: http://example.com/thankyou. </td></tr>

                    <tr><td>
                    <br />
                    </td></tr>

                    <!--<tr><td class='cf7rzp_width'><b>Razorpay Cancel URL: </b></td><td><input type='text' name='cancel_url' value='<?php /*echo $options['cancel_url'];*/ ?>'> Optional <br /></td></tr>
                    <tr><td class='cf7rzp_width'></td><td>If the customer goes to PayPal and clicks the cancel button, where do they go. Example: http://example.com/cancel. Max length: 1,024. </td></tr>
                          
                    <tr><td>
                    <br />
                    </td></tr>-->

                </table>

            </div>    
            <!-- ***************************************** -->     

	<input type='hidden' name='update' value='1'>

    <br/>
    <input type='submit' name='btn2' class='button-primary' style='font-size: 13px;line-height: 28px;height: 32px;' value='Save Settings'>
</form>
<?php do_action("cf7rzp_admin_rzp_settings_out",$options); ?>


	<td width="2%" valign="top">



	</td></tr></table>

	<?php

}
