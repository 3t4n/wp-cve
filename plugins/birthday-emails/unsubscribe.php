<?php
/***************************************************************************************
 * This function attempts to load translations for the country/language set in Settings/General
 ***************************************************************************************/
if ( ! function_exists( 'cjl_bdemail_load_plugin_textdomain' ) ) {
    function cjl_bdemail_load_plugin_textdomain()
    {
        load_plugin_textdomain('birthday-emails', FALSE, basename(dirname(__FILE__)) . '/languages/');
    }
}
require_once("../../../wp-load.php");// load WordPress
add_action( 'plugins_loaded', 'cjl_bdemail_load_plugin_textdomain' );
global $wpdb; //make the database object available
$uid = '';
$userID = '';
if (isset($_GET['uid'])) { //get the uid from the invoking URL
    $uid = $_GET['uid'];
}
if ($uid){// if got uid from the invoking URL, lookup that uid to find a correspoinding userID
    $uid = sanitize_text_field($uid);
    $table_name = $wpdb->prefix.'cjl_bdemail_unsubscribe';
    $query = 'SELECT userid FROM ' . $table_name . ' WHERE hash = "' . $uid . '"';
    $resultset = $wpdb->get_results($query);
    foreach ($resultset as $rec) {
        $userID = $rec->userid;
        break;
    }
    $rslt = '';
    if ( $userID ) { //having got the userID for this unsubscribe request, set a user meta field to 'true' for that user
        if ($userID == -999999) $rslt = true;
        else $rslt = update_user_meta($userID, 'cjl_bdemailUnsubscribed', 'true');
    } else { //if couldn't find a userID, suggest that the uid record may have been deleted, because older than 90 days
        ?>
        <html>
        <head></head>
        <body>
        <h1>&nbsp;</h1>
        <h2 style="text-align: center;"><?php echo __('Unsubscribe NOT successful.','birthday-emails'); ?></h2>
        <p style="text-align: center;"><?php echo __('(UserID not found for uid in unsubscribe request. Your unsubscribe link may be too old.)','birthday-emails'); ?></p>
        </body>
        </html>
        <?php
    }
    if ($rslt === false) {// if update of user meta field failed, there was a database error OR the user meta field was already set.
        $unsubval = get_user_meta($userID, 'cjl_bdemailUnsubscribed', true); // see if the user meta field was already set
        if (!$unsubval) {// if user meta field was not already set, then there was a database error
            ?>
            <html>
            <head></head>
            <body>
            <h1>&nbsp;</h1>
            <h2 style="text-align: center;"><?php echo __('Unsubscribe NOT successful.','birthday-emails'); ?>.</h2>
            <p style="text-align: center;"><?php echo __('(Database error. Please try again later.)','birthday-emails'); ?></p>
            </body>
            </html>
            <?php
        } else {// if user meta field was already set, then say so
            ?>
            <html>
            <head></head>
            <body>
            <h1>&nbsp;</h1>
            <h2 style="text-align: center;"><?php echo __('Unsubscribe successful.','birthday-emails'); ?></h2>
            <p style="text-align: center;"><?php echo __('(Already unsubscribed.)','birthday-emails'); ?></p>
            </body>
            </html>
            <?php
        }
    }
    if ($rslt) {// if update of user meta field worked, then say so
        ?>
        <html>
        <head></head>
        <body>
        <h1>&nbsp;</h1>
        <h2 style="text-align: center;"><?php echo __('Unsubscribe successful.','birthday-emails'); ?></h2>
        </body>
        </html>
        <?php
    }
} else {//no uid found on the invoking URL
    ?>
    <html>
    <head></head>
    <body>
    <h1>&nbsp;</h1>
    <h2 style="text-align: center;"><?php echo __('Unsubscribe NOT successful.','birthday-emails'); ?></h2>
    <p style="text-align: center;"><?php echo __('(No uid supplied in unsubscribe request.)','birthday-emails'); ?></p>
    </body>
    </html>
    <?php
}
