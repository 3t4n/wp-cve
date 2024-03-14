<?php
/**
 * FB Setting Page
 *
 * @package WordPress
 * @package User Registration using Contact Form 7 
 * @since 1.0
 */
?>
<!-- Table heading-->
<table class="form-table form-table-heading">
	<tbody>
    	<tr>
      		<th><?php echo __('Facebook Sign Up Setting','zeal-user-reg-cf7');?> :</th>
      		<td></td>
    	</tr>
  </tbody>
</table>
<!-- Table Content-->
<table class="form-table" id="form-settings">
	<tbody>
    <tr>
        <td style="color:red;"><?php echo __('Note : SSL certificate is required for Facebook custom app','zeal-user-reg-cf7'); ?></td>
    </tr>
    <tr>
        <td colspan="2">
        <?php 
        $domain_name = $callback_fb = $site_url_callback_fb = $zurcf7_fb_signup_app_id = $zurcf7_fb_app_secret = '';
        $domain_name = sanitize_text_field($_SERVER['HTTP_HOST']); 
        $callback_fb = '?socialsignup=facebook';
        $site_url_callback_fb = get_site_url().$callback_fb;
        $zurcf7_fb_signup_app_id = (get_option( 'zurcf7_fb_signup_app_id')) ? get_option( 'zurcf7_fb_signup_app_id') : "";
        $zurcf7_fb_app_secret = (get_option( 'zurcf7_fb_app_secret')) ? get_option( 'zurcf7_fb_app_secret') : "";
        ?>
            <ol>
                <li><?php echo __( "Go to Facebook developers console <a href='https://developers.facebook.com/apps/' target='_blank'>https://developers.facebook.com/apps/.</a> </li>", "zeal-user-reg-cf7" ); ?>
                <li><?php echo __( "Click on Create a New App/Create App. Select Consumer on the Select App type pop-up Click on Continue.</li>", "zeal-user-reg-cf7" ); ?>
                <li><?php echo __( "Enter App Display Name, App Contact Email.</li>", "zeal-user-reg-cf7" ); ?>
                <li><?php echo __( "Click on Create App button and complete the Security Check.</li>", "zeal-user-reg-cf7" ); ?>
                <li><?php echo __( "On add products to your app page click on setup button under facebook login option.</li>", "zeal-user-reg-cf7" ); ?>
                <li><?php echo __( "Enter ".$domain_name." in App Domain. Enter your Privacy Policy URL</li>", "zeal-user-reg-cf7" ); ?>
                <li><?php echo __( "Under User Data Deletion click on the drop down, Select Data Deletion Instruction URl (Enter the URL of your page with the instructions on how users can delete their accounts on your site).</li>", "zeal-user-reg-cf7" ); ?>
                <li><?php echo __( "Select Category of your website. Then click on Save Changes.</li>", "zeal-user-reg-cf7" ); ?>
                <li><?php echo __( "On the Left side panel, Click on Facebook Login and select Settings option.</li>", "zeal-user-reg-cf7" ); ?>
                <li><?php echo __( "Scroll down and add the following URL to the Valid OAuth redirect URIs field <a>".$site_url_callback_fb."</a> and click on Save Changes button.</li>", "zeal-user-reg-cf7" ); ?>
                <li><?php echo __( "Click on the App review tab from the left hand side menu and click on Permissions and Request</li>", "zeal-user-reg-cf7" ); ?>
                <li><?php echo __( "Now click on Request Advance Access for public_profile and email. If you want any extra data to be returned you can request permission for those scopes.</li>", "zeal-user-reg-cf7" ); ?>
                <li><?php echo __( "In the toolbar Change your app status from In Development to Live by clicking on the toggle button and further Click on Switch Mode.</li>", "zeal-user-reg-cf7" ); ?>
                <li><?php echo __( "Go to Settings > Basic. Copy your App ID and App Secret provided by Facebook and paste them into the fields above</li>", "zeal-user-reg-cf7" ); ?>
                <li><?php echo __( "Input email, public_profile as scope.</li>", "zeal-user-reg-cf7" ); ?>
                <li><?php echo __( "NOTE: If you are asked to Complete Data Use Checkup. Click on the Start Checkup button. Certify Data Use for public_profile and email. Provide consent to Facebook Developer’s Policy and click on submit.</li>", "zeal-user-reg-cf7" ); ?>
                <li><?php echo __( "[Optional: Extra attributes] If you want to access the user_birthday, user_hometown, user_location of a Facebook user, you need to send your app for review to Facebook. For submitting an app for review, click <a href='https://developers.facebook.com/docs/app-review/submission-guide' target='_blank'>here.</a> After your app is reviewed, you can add the scopes you have sent for review in the scope above. If your app is not approved or is in the process of getting approved, let the scope be email, public_profile.</li>", "zeal-user-reg-cf7" ); ?>
            </ol>
        </td>
    </tr>
    <tr>
        <th scope="row">
            <label for="zurcf7_fb_signup_app_id"><?php echo __('App ID', 'zeal-user-reg-cf7' ); ?></label><span class="zwt-zurcf7-tooltip" id="zurcf7_fb_signup_app_id_tool"></span>
        </th>
        <td>
            <input name="zurcf7_fb_signup_app_id" id="zurcf7_fb_signup_app_id" type="text" value="<?php echo $zurcf7_fb_signup_app_id;?>" class="regular-text zurcf7_alltag"/></br>
        </td>    
    </tr>
    <tr>
        <th scope="row">
            <label for="zurcf7_fb_app_secret"><?php echo __('App Secret', 'zeal-user-reg-cf7' ); ?></label><span class="zwt-zurcf7-tooltip" id="zurcf7_fb_app_secret_tool"></span>
        </th>
        <td>
            <input name="zurcf7_fb_app_secret" id="zurcf7_fb_app_secret" type="text" value="<?php echo $zurcf7_fb_app_secret;?>" class="regular-text zurcf7_alltag"/></br>
        </td>
    </tr>
    </tbody>
</table>
