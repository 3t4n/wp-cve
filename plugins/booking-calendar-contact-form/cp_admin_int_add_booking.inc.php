<?php

if ( !is_admin() ) 
{
    echo 'Direct access not allowed.';
    exit;
}

global $wpdb;

$message = '';
$cpid = 'CP_BCCF';
$plugslug = 'dex_bccf.php';

if ( 'POST' == $_SERVER['REQUEST_METHOD'] && isset( $_POST[$cpid.'_post_edition'] ) )
    echo "<div id='setting-error-settings_updated' class='updated settings-error'> <p><strong>Settings saved.</strong></p></div>";

define ('DEX_BCCF_CALENDAR_FIXED_ID',intval($_GET["cal"]));

$nonce = wp_create_nonce( 'bccf_update_actions_custom' );

if (isset($_POST["dex_bccf_post"]))
    echo "<div id='setting-error-settings_updated' class='updated settings-error'><p><strong>".'Booking added. It appears now in the <a href="?page=dex_bccf.php&cal='.DEX_BCCF_CALENDAR_FIXED_ID.'&list=1">bookings list</a>.'."</strong></p></div>";

?>
<div class="wrap">
<h1>Add Booking</h1>  

<p>This page is for adding bookings from the administration area. The captcha and payment process are disabled in order to allow the website
manager easily adding bookings.</p> 
<input type="button" name="backbtn" value="Back to items list..." onclick="document.location='admin.php?page=<?php echo esc_attr($plugslug); ?>';">
<br /><br />

<?php dex_bccf_get_public_form(); ?>

</div>













