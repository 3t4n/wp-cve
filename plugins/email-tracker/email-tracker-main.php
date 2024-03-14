<?php

// Autoloading Vendor
$autoloader = plugin_dir_path( EMTR_FILE ) . '/vendor/autoload.php';
// When WordPress is loaded as composer dependecy, There will not be __DIR__/vendor/autoload.php pressent.
// so we need to check
if ( is_readable( $autoloader ) ) {
    require $autoloader;
}
require_once plugin_dir_path( EMTR_FILE ) . '/defines.php';
require_once plugin_dir_path( EMTR_FILE ) . '/src/class-email-tracker-invoker.php';
\PrashantWP\Email_Tracker\Email_Tracker_Invoker::register();
/*
 * includes  all functions which required by plugin
 */
if ( is_admin() ) {
    /*
     * Back-end (visible for admin only)
     */
    require_once EMTR_PLUGIN_PATH . 'et-admin.php';
}
/**
 * This is database version which is useful when we upgrade plugin
 */
define( 'EMTR_DB_VERSION', '1.2.1' );
define( 'EMTR_VERSION', '5.3.2' );
/*
* Upgrade Script
*/
if ( version_compare( get_option( 'emtr_db_version' ), EMTR_DB_VERSION, '<' ) ) {
    add_action( 'admin_init', 'emtr_create_tables', 1 );
}
/**
 * This function and hook set default value of setting
 * This function will run when plugin will activated
 */
function emtr_create_tables()
{
    global  $wpdb ;
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    $tableName = \PrashantWP\Email_Tracker\Util::emtr_get_table_name( 'email' );
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE {$tableName}\r\n                (\r\n                    email_id int(30) unsigned NOT NULL AUTO_INCREMENT,\r\n                    date_time datetime NOT NULL DEFAULT '0000-00-00 00:00:00', \r\n                    `to` varchar(255) NOT NULL DEFAULT '',\r\n                    subject varchar(255) NOT NULL DEFAULT '',\r\n                    message text,\r\n                    message_plain text,\r\n                    headers text,\r\n                    attachments text,\r\n                    PRIMARY KEY  (email_id),\r\n                    KEY date_time (date_time),\r\n                    KEY `to` (`to`),\r\n                    KEY subject (subject)\r\n                ) {$charset_collate};";
    dbDelta( $sql );
    $tableName = \PrashantWP\Email_Tracker\Util::emtr_get_table_name( 'track_email_open_log' );
    $sql = "CREATE TABLE {$tableName}\r\n                    (\r\n                        trkemail_id int(30) unsigned NOT NULL AUTO_INCREMENT,\r\n                        trkemail_email_id int(10) unsigned NOT NULL COMMENT 'FK (emtr_mail => email_id)',\r\n                        trkemail_date_time datetime NOT NULL,\r\n                        trkemail_http_user_agent varchar(255) NOT NULL,\r\n                        trkemail_ip_address varchar(255) NOT NULL,\r\n                        trkemail_tacked_by varchar(60) NOT NULL DEFAULT 'Image' COMMENT '[Image] , [Link]',\r\n                        PRIMARY KEY  (trkemail_id),\r\n                        KEY trkemail_email_id (trkemail_email_id),\r\n                        KEY trkemail_date_time (trkemail_date_time)\r\n                    ) {$charset_collate};";
    dbDelta( $sql );
    $tableName = \PrashantWP\Email_Tracker\Util::emtr_get_table_name( 'track_email_open_log' );
    $sql = "CREATE TABLE IF NOT EXISTS {$tableName}\r\n                    (\r\n                        trklink_id int(30) unsigned NOT NULL AUTO_INCREMENT,\r\n                        trklink_user_id int(30) unsigned NOT NULL COMMENT '(FK user_master => user_id)',\r\n                        trklink_email_id int(30) unsigned NOT NULL COMMENT 'FK (lead_mail => email_id)',\r\n                        trklink_link varchar(255) NOT NULL,\r\n                        PRIMARY KEY  (trklink_id),\r\n                        KEY trklink_user_id (trklink_user_id),\r\n                        KEY trklink_email_id (trklink_email_id)\r\n                    ) {$charset_collate};";
    dbDelta( $sql );
    $tableName = \PrashantWP\Email_Tracker\Util::emtr_get_table_name( 'track_email_link_master' );
    $sql = "CREATE TABLE {$tableName}\r\n                    (\r\n                        trklink_id int(30) unsigned NOT NULL AUTO_INCREMENT,\r\n                        trklink_user_id int(30) unsigned NOT NULL COMMENT '(FK user_master => user_id)',\r\n                        trklink_email_id int(30) unsigned NOT NULL COMMENT 'FK (lead_mail => email_id)',\r\n                        trklink_link varchar(255) NOT NULL,\r\n                        PRIMARY KEY  (trklink_id),\r\n                        KEY trklink_user_id (trklink_user_id),\r\n                        KEY trklink_email_id (trklink_email_id)\r\n                    ) {$charset_collate};";
    dbDelta( $sql );
    $tableName = \PrashantWP\Email_Tracker\Util::emtr_get_table_name( 'track_email_link_click_log' );
    $sql = "CREATE TABLE IF NOT EXISTS {$tableName}\r\n                (\r\n                    trklinkclick_id int(30) unsigned NOT NULL AUTO_INCREMENT,\r\n                    trklinkclick_trklink_id int(30) unsigned NOT NULL COMMENT 'FK (track_email_link_master  => trklink_id)',\r\n                    trklinkclick_email_id int(30) unsigned NOT NULL DEFAULT '0',\r\n                    trklinkclick_date_time datetime NOT NULL,\r\n                    trklinkclick_http_user_agent varchar(255) NOT NULL,\r\n                    trklinkclick_ip_address varchar(200) NOT NULL,\r\n                    PRIMARY KEY  (trklinkclick_id),\r\n                    KEY trklinkclick_trklink_id (trklinkclick_trklink_id),\r\n                    KEY trklinkclick_email_id (trklinkclick_email_id)\r\n                ) {$charset_collate};";
    dbDelta( $sql );
    update_option( 'emtr_db_version', EMTR_DB_VERSION );
    update_option( 'emtr_version', EMTR_VERSION );
    // For add rewite rule of email tracking
    flush_rewrite_rules();
}

function emtr_plugin_activate( $network_wide )
{
    global  $wp_rewrite ;
    $wp_rewrite->set_permalink_structure( '/%postname%/' );
    $wp_rewrite->flush_rules();
    
    if ( function_exists( 'is_multisite' ) && is_multisite() && $network_wide ) {
        global  $wpdb ;
        $old_blog = $wpdb->blogid;
        // Get all blog ids
        $blogids = $wpdb->get_col( "SELECT blog_id FROM {$wpdb->blogs}" );
        foreach ( $blogids as $blog_id ) {
            switch_to_blog( $blog_id );
            // Create database table if not exists
            emtr_create_tables();
        }
        switch_to_blog( $old_blog );
        return;
    }
    
    \PrashantWP\Email_Tracker\Util::get_factory()->get( '\\PrashantWP\\Email_Tracker\\Admin\\Email_List\\Setup' )->set_up_capability_to_administrator();
    // Create database table if not exists
    emtr_create_tables();
}

register_activation_hook( EMTR_BASE_FILE_PATH, 'emtr_plugin_activate' );
function emtr_uninstall_cleanup()
{
    require_once EMTR_PLUGIN_PATH . '/emtr-uninstall.php';
}

emtr()->add_action( 'after_uninstall', 'emtr_uninstall_cleanup' );
function emtr_email_before_send( $orig_email )
{
    // multiple email address
    if ( is_string( $orig_email['to'] ) && false !== stripos( $orig_email['to'], ',' ) ) {
        return $orig_email;
    }
    // multiple email address
    if ( is_array( $orig_email['to'] ) ) {
        return $orig_email;
    }
    global  $wpdb ;
    
    if ( false === stripos( $orig_email['message'], '</' ) ) {
        $orig_email['message'] = str_replace( array( '<', '>' ), '', $orig_email['message'] );
        $orig_email['message'] = nl2br( $orig_email['message'] );
    }
    
    if ( function_exists( 'make_clickable' ) ) {
        $orig_email['message'] = make_clickable( $orig_email['message'] );
    }
    $email = $orig_email;
    require_once EMTR_PLUGIN_PATH . 'vendor/autoload.php';
    try {
        $message_plain = \Soundasleep\Html2Text::convert( $email['message'], array(
            'ignore_errors' => true,
        ) );
    } catch ( Exception $e ) {
        // silently hide
    } catch ( Error $e ) {
        // silently hide
    }
    $email_db_data = array(
        'to'            => emtr_extract_email_field( $email['to'] ),
        'subject'       => $email['subject'],
        'message'       => $email['message'],
        'message_plain' => $message_plain,
        'headers'       => emtr_extract_email_field( $email['headers'] ),
        'attachments'   => \PrashantWP\Email_Tracker\Util::emtr_extract_attachments( $email['attachments'] ),
        'date_time'     => gmdate( 'Y-m-d H:i:s' ),
    );
    $ret = $wpdb->insert( \PrashantWP\Email_Tracker\Util::emtr_get_table_name( 'email' ), $email_db_data, array(
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s'
    ) );
    $model_trackemail = \PrashantWP\Email_Tracker\Factory::get( '\\PrashantWP\\Email_Tracker\\Model\\TrackEmail' );
    
    if ( $ret ) {
        $email_id = $wpdb->insert_id;
        $orig_email['message'] .= $model_trackemail->get_track_code( $email_id );
    }
    
    return $orig_email;
}

add_filter( 'wp_mail', 'emtr_email_before_send', PHP_INT_MAX );
function emtr_extract_email_field( $email_field )
{
    return ( is_array( $email_field ) ? implode( ', ', $email_field ) : $email_field );
}

function emtr_pre_wp_mail( $ret, $atts )
{
    $forbid_mail = false;
    // error_log( '$atts: ' . var_export( $atts, true ) );
    // multiple email address
    
    if ( is_string( $atts['to'] ) && false !== stripos( $atts['to'], ',' ) ) {
        $forbid_mail = true;
        $to_arr = explode( ',', $atts['to'] );
    }
    
    // multiple email address
    
    if ( is_array( $atts['to'] ) ) {
        $forbid_mail = true;
        $to_arr = $atts['to'];
    }
    
    
    if ( $forbid_mail ) {
        $to_arr = array_map( 'trim', $to_arr );
        $to_arr = array_filter( $to_arr );
        if ( !isset( $atts['headers'] ) ) {
            $atts['headers'] = '';
        }
        if ( !isset( $atts['attachments'] ) ) {
            $atts['attachments'] = array();
        }
        foreach ( $to_arr as $to ) {
            wp_mail(
                $to,
                $atts['subject'],
                $atts['message'],
                $atts['headers'],
                $atts['attachments']
            );
        }
        return $forbid_mail;
    }
    
    return $ret;
}

add_filter(
    'pre_wp_mail',
    'emtr_pre_wp_mail',
    10,
    2
);
/**
 * Filter the mail content type.
 */
add_filter( 'wp_mail_content_type', 'emtr_set_html_mail_content_type', PHP_INT_MAX - 10 );
function emtr_set_html_mail_content_type( $content_type )
{
    global  $emtr_is_plain ;
    
    if ( $content_type == 'text/plain' ) {
        $emtr_is_plain = true;
        return 'text/html';
    } else {
        $emtr_is_plain = false;
        return $content_type;
    }

}

function emtr_custom_rewrite_basic( $wp_rewrite )
{
    $new_rules = array(
        '^track/e/(o|l)/([^/]+).*$' => 'index.php?main_action=track-email.php&action=$matches[1]&pk=$matches[2]',
    );
    $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
}

add_action( 'generate_rewrite_rules', 'emtr_custom_rewrite_basic' );
function query_vars( $public_query_vars )
{
    $public_query_vars[] = 'main_action';
    $public_query_vars[] = 'action';
    $public_query_vars[] = 'pk';
    return $public_query_vars;
}

add_filter( 'query_vars', 'query_vars' );
add_action( 'parse_request', 'emtr_parse_request' );
function emtr_parse_request( &$wp )
{
    
    if ( array_key_exists( 'main_action', $wp->query_vars ) ) {
        set_query_var( 'action', $wp->query_vars['action'] );
        set_query_var( 'pk', $wp->query_vars['pk'] );
        include EMTR_PLUGIN_PATH . 'track-email.php';
        exit;
    }

}

add_filter(
    'retrieve_password_message',
    'emtr_retrieve_password_message',
    PHP_INT_MAX - 20,
    2
);
function emtr_retrieve_password_message( $message, $key )
{
    $message = str_replace( array( '<', '>' ), '', $message );
    return $message;
}

/*
Code taken from Add Plain Text Email
Plugin URI: http://dannyvankooten.com/wordpress-plugins/mailchimp-for-wordpress/
Description: Adds a text/plain email to text/html emails to decrease the chance of emails being tagged as spam.
Version: 1.1.2
Author: Danny van Kooten
Author URI: http://dannyvanKooten.com
*/
class Emtr_Mailer
{
    /**
     * @var string
     */
    protected  $previous_altbody ;
    /**
     * Add hooks
     */
    public function add_hooks()
    {
        // add action so function actually runs
        add_action( 'phpmailer_init', array( $this, 'set_plaintext_body' ) );
    }
    
    /**
     * @param PHPMailer $phpmailer
     */
    public function set_plaintext_body( $phpmailer )
    {
        // don't run if sending plain text email already
        if ( $phpmailer->ContentType === 'text/plain' ) {
            return;
        }
        // don't run if altbody is set (by other plugin)
        if ( !empty($phpmailer->AltBody) && $phpmailer->AltBody !== $this->previous_altbody ) {
            return;
        }
        // set AltBody
        $text_message = $this->strip_html_tags( $phpmailer->Body );
        $phpmailer->AltBody = $text_message;
        $this->previous_altbody = $text_message;
    }
    
    /**
     * Remove HTML tags, including invisible text such as style and
     * script code, and embedded objects.  Add line breaks around
     * block-level tags to prevent word joining after tag removal.
     */
    private function strip_html_tags( $text )
    {
        $text = preg_replace( array(
            // Remove invisible content
            '@<head[^>]*?>.*?</head>@siu',
            '@<style[^>]*?>.*?</style>@siu',
            '@<script[^>]*?.*?</script>@siu',
            '@<object[^>]*?.*?</object>@siu',
            '@<embed[^>]*?.*?</embed>@siu',
            '@<noscript[^>]*?.*?</noscript>@siu',
            '@<noembed[^>]*?.*?</noembed>@siu',
            '@\\t+@siu',
            '@\\n+@siu',
        ), '', $text );
        // replace certain elements with a line-break
        $text = preg_replace( array( '@</?((div)|(h[1-9])|(/tr)|(p)|(pre))@iu' ), "\n\$0", $text );
        // replace other elements with a space
        $text = preg_replace( array( '@</((td)|(th))@iu' ), ' $0', $text );
        // strip all remaining HTML tags
        $text = strip_tags( $text );
        // trim text
        $text = trim( $text );
        return $text;
    }

}
$Emtr_Mailer = new Emtr_Mailer();
$Emtr_Mailer->add_hooks();