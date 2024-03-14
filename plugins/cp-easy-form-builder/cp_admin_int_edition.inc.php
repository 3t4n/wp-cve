<?php

if ( !is_admin() ) 
{
    echo 'Direct access not allowed.';
    exit;
}

$nonce = wp_create_nonce( 'uname_cpefb' );

global $wpdb;

$cpid = 'CP_EFB';
$plugslug = 'cp_easy_form_builder';

if ( 'POST' == $_SERVER['REQUEST_METHOD'] && isset( $_POST[$cpid.'_post_edition'] ) )
    echo "<div id='setting-error-settings_updated' class='updated settings-error'> <p><strong>Settings saved.</strong></p></div>";


?>
<div class="wrap">
<h1>Customization / Edit Page</h1>  



<input type="button" name="backbtn" value="Back to items list..." onclick="document.location='admin.php?page=<?php echo esc_attr($plugslug); ?>';">
<br /><br />

   
<div id="normal-sortables" class="meta-box-sortables">

Note: This section has been modified to improve security. Please edit the custom CSS in the theme. You can <a href="https://wordpress.dwbooster.com/contact-us">contact us for support and assistance</a>.
  
  
</div> 



</form>
</div>













