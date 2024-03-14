<?php
/*
Plugin Name: Document Management System
Plugin URI: http://blitzenware.com
Description: The complete document management solution.
Version: 1.24
Author: Brian E. Reifsnyder
Author URI: http://blitzenware.com
*/


/*  Copyright 2017  Brian E. Reifsnyder
    GNU GPL V2
*/

//  DEV NOTES

//  dms_help_system(), in i_dms_functions, is currently disabled.
//  URL capability disabled.
//  Batch Import Disabled.
//  Message Box Disabled.


//  KNOWN BUG:  The user_id is sometimes lost when exiting a page (i.e.  Create Folder) and going back to the main ui.  It is re-set in the session variables as well as the globals.
//              This bug may not manifest itself frequently.  Either way, it needs tracked down.


//  To Do:
//  Improve handling of session time out.
//  Change the "Empty" message such that it is displayed when there are no folders _and_ files.
//  Find and fix the outline bar (on right) that is displayed on empty folders.
//  Updates for DMS Pro customers.


// don't load directly
if (!function_exists('is_admin')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

define( 'DMS_VERSION', '1.24' );
define( 'DMS_RELEASE_DATE', date_i18n( 'F j, Y', strtotime( '01/05/2018' ) ) );
define( 'DMS_DIR', plugin_dir_path( __FILE__ ) );
define( 'DMS_URL', plugin_dir_url( __FILE__ ) );
define( 'DMS_ICONS', plugin_dir_URL(__FILE__)."images" );

define( 'DMS_DB_PREFIX', "");

//  Database installation and initial configuration
require_once( DMS_DIR . "/install/database.php" );
register_activation_hook( __FILE__, 'dms_install_db' );
register_activation_hook( __FILE__, 'dms_install_data' );


//  Set up pal global variables for pal
$dms_pal_wp_url = "";
$dms_wp_user_id = 0;
$dms_wp_admin_flag = FALSE;

$dms_global = array();   //  Use to pass variables for port to Wordpress
$dms_global["first_separator"] = "";

require_once( DMS_DIR . "/includes/general/i_defines.php" );
require_once( DMS_DIR . "/includes/general/i_pal.php" );
require_once( DMS_DIR . "/includes/general/i_dms_functions.php" );
require_once( DMS_DIR . "/includes/languages/english.php" );



function dms()
    {
/*
    $wp_upload_dir = wp_upload_dir();
    print "wp_upload_dir() path = " . $wp_upload_dir['basedir'] . "<BR>";
*/

    global $dmsdb;
    global $dms_config,$dms_users,$dms_wp_admin_flag, $dms_wp_user_id;
    global $dms_pal_wp_url, $wpdb;
    global $dms_user_id;

    global $dms_global;

//print "DB_USER = ". DB_USER . "<BR>";
    //  Connect to the database.
    $dmsdb->connect();

    $dms_user_id = $dms_users->get_current_user_id();

    dms_initialize();

    $dms_pal_wp_url = get_page_link();  //  Only seems to work like this.


    //  Set the URL to the DMS as a config variable for ease of use throughout the DMS.
    $dms_config['dms_url'] = $dms_pal_wp_url;  //  Is this needed now??

    //  Setup variables to be transferred to the iframe.

    $dms_global['dms_user_id'] = $dms_user_id;
    $dms_global['dms_admin_flag'] = $dms_wp_admin_flag;
    $dms_global['dms_wp_css_uri'] = get_stylesheet_directory_uri();
    $dms_global['dms_wp_css_dir'] = get_stylesheet_directory();
    $dms_global['dms_url'] = DMS_URL . "pages/dms_frame.php";
    $dms_global['dms_url_root'] = DMS_URL;
    $dms_global['dms_root_dir'] = DMS_DIR;
    $dms_global['doc_path'] = $dms_config['doc_path'];
    $dms_global['wpdb_prefix'] = $wpdb->prefix;
    $dms_global['wp_timezone'] = date_default_timezone_get();
    $dms_global['wp_uploads'] = ABSPATH . "wp-content/uploads";
    $dms_global['normal_init'] = true;

    if(!defined("DMS_PRO"))
        {
        $dms_global['dms_pro_dir'] = "FALSE";
        $dms_global['dms_pro_url'] = "FALSE";
        }

    if(defined("DMS_PRO")) require_once( DMS_PRO_DIR . "includes/i_dms.php" );

    $_SESSION['dms_global'] = $dms_global;

    //  Upgrade System
    if (DMS_VERSION != $dms_config['version'])
        {
        global $dmsdb;

        require_once( DMS_DIR . "/includes/general/i_db_upgrade.php");
        require_once( DMS_DIR . "/includes/general/i_set_config.php");

        $redirect_url = $dms_config['dms_url'].$dms_global["first_separator"]."dms_page=main";

        dms_redirect($redirect_url);

        print "</div>";
        exit(0);
        }

    $folder_id = dms_get_var("folder_id");
    if($folder_id != FALSE) dms_set_folder_id($folder_id);

    $file_id = dms_get_var("file_id");
    if($file_id != FALSE)
        {
        $location = DMS_URL."pages/file_retrieve.php?function=view&obj_id=".$file_id;
        dms_redirect($location);
        exit(0);
        }

    switch( dms_get_var("dms_page") )
        {
        case "config":
            print "<div id=\"dms2015wp\">\r";
            require_once( DMS_DIR . "/pages/config.php" );
            print "</div>\r";
            break;

        case "set_config":
            print "<div id=\"dms2015wp\">\r";
            require_once( DMS_DIR . "/pages/set_config.php" );
            print "</div>\r";
            break;

        case "config_fts":
            if(defined("DMS_FTS"))
                {
                print "<div id=\"dms2015wp\">\r";
                require_once( DMS_FTS_DIR . "/pages/config.php" );
                print "</div>\r";
                }
            break;

        case "set_config_fts":
            if(defined("DMS_FTS"))
                {
                print "<div id=\"dms2015wp\">\r";
                require_once( DMS_FTS_DIR . "/pages/set_config.php" );
                print "</div>\r";
                }
            break;

        case "diags":
            print "<div id=\"dms2015wp\">\r";
            require_once( DMS_DIR . "/pages/diags.php" );
            print "</div>\r";
            break;


        default:
            {
            print "<iframe src=\"". DMS_URL ."pages/dms_frame.php\" width=\"".$dms_config['frame_width']."\" height=\"".$dms_config['frame_height']."\" scrolling=\"yes\"></iframe>\r";

            if($dms_global['dms_admin_flag'] == 1)
                {
                print "<BR>\r";
                print "<div id=\"dms2015wp_config\">\r";
                $button  = "<input type='button' name='btn_config' value='"._DMS_L_CONFIG."' class='dms_config_button ";
                $button .= "'onclick='location=\"".$dms_config['dms_url'].$dms_global["first_separator"]."dms_page=set_config\";'>";
                print $button;
                print "</div>\r";

                if(defined("DMS_FTS"))
                    {
                    require_once ( DMS_FTS_DIR . "/buttons/config.php");
                    }

//                print "&nbsp;&nbsp;";

//                print "<BR>\r";
                print "<div id=\"dms2015wp_config\">\r";
                $button  = "<input type='button' name='btn_config' value='"._DMS_L_DIAGNOSTICS."' class='dms_config_button ";
                $button .= "'onclick='location=\"".$dms_config['dms_url'].$dms_global["first_separator"]."dms_page=diags\";'>";
                print $button;
                print "</div>\r";
                }
            }
        }
    }


//  Install the DMS Plugin
add_shortcode("document_management_system","dms");

//  User ID is available after plugins are loaded.  Load User ID for use by DMS.
function dms_wp_user_info()
    {
    global $current_user;
    global $dms_wp_user_id;
    global $dms_wp_admin_flag;

    $dms_wp_user_id = $current_user->ID;
    $_SESSION['dms_wp_var']['dms_wp_user_id']= $dms_wp_user_id;

    if(current_user_can("administrator")) $dms_wp_admin_flag = TRUE;
    }
add_action('wp_loaded', 'dms_wp_user_info');



/**
 * Register style sheet.
*/
function dms_register_style_sheet()
    {
    wp_register_style( 'dms', plugins_url( 'dms/css/dms.css' ) );
    wp_enqueue_style( 'dms' );
    }
add_action( 'wp_enqueue_scripts', 'dms_register_style_sheet' );




/**
 * Session handling functions.
*/
add_action('init', 'dmsStartSession', 1);
add_action('wp_logout', 'dmsEndSession');
add_action('wp_login', 'dmsStartSession');

function dmsStartSession()
    {
    if(!session_id())
        {
        session_start();
/*
        global $dms_wp_user_id;
        global $dms_user_id;
        global $dms_users;
        global $dms_global;

        $dms_user_id = $dms_users->get_current_user_id();
        $dms_global['dms_user_id'] = $dms_user_id;
        $_SESSION['dms_global'] = $dms_global;
*/
// ///////////////////////////////
        global $dmsdb;
        global $dms_config,$dms_users,$dms_wp_admin_flag, $dms_wp_user_id;
        global $dms_pal_wp_url, $wpdb;
        global $dms_user_id;

        global $dms_global;


        //  Connect to the database.
        $dmsdb->connect();

        $dms_user_id = $dms_users->get_current_user_id();

        dms_initialize();

        $dms_pal_wp_url = get_page_link();  //  Only seems to work like this.


        //  Set the URL to the DMS as a config variable for ease of use throughout the DMS.
        $dms_config['dms_url'] = $dms_pal_wp_url;  //  Is this needed now??

        //  Setup variables to be transferred to the iframe.

        $dms_global['dms_user_id'] = $dms_user_id;
        $dms_global['dms_admin_flag'] = $dms_wp_admin_flag;
        $dms_global['dms_wp_css_uri'] = get_stylesheet_directory_uri();
        $dms_global['dms_wp_css_dir'] = get_stylesheet_directory();
        $dms_global['dms_url'] = DMS_URL . "pages/dms_frame.php";
        $dms_global['dms_url_root'] = DMS_URL;
        $dms_global['dms_root_dir'] = DMS_DIR;
        $dms_global['doc_path'] = $dms_config['doc_path'];
        $dms_global['wpdb_prefix'] = $wpdb->prefix;
        $dms_global['wp_timezone'] = date_default_timezone_get();
        $dms_global['normal_init'] = true;

        if(!defined("DMS_PRO"))
            {
            $dms_global['dms_pro_dir'] = "FALSE";
            $dms_global['dms_pro_url'] = "FALSE";
            }

        if(defined("DMS_PRO")) require_once( DMS_PRO_DIR . "includes/i_dms.php" );

        $_SESSION['dms_global'] = $dms_global;
// /////////////////////////////////////
        }
    }

function dmsEndSession()
    {
    session_destroy ();
    }



//  User deletion
add_action ('delete_user', 'dms_delete_user');

function dms_delete_user( $wp_user_id )
    {
    global $dmsdb, $dms_wp_user_id;

    //  Delete entries in dms_groups_users_link
    $query  = "DELETE FROM ".$dmsdb->prefix("dms_groups_users_link")." ";
    $query .= "WHERE user_id='".$wp_user_id."'";
    $dmsdb->query($query);

    //  Set the administrator (or whoever is deleting this user) to owner of all objects owned by the deleted user in dms_object_perms
    $query  = "UPDATE ".$dmsdb->prefix("dms_object_perms")." ";
    $query .= "SET user_id = '" . $dms_wp_user_id . "' ";
    $query .= "WHERE user_id = '" . $wp_user_id . "'";
    $dmsdb->query($query);

    //  Delete all other entries, in dms_object_perms that have this user_id
    $query  = "DELETE FROM ".$dmsdb->prefix("dms_object_perms")." ";
    $query .= "WHERE user_id='".$wp_user_id."'";
    $dmsdb->query($query);
    }


//  Hide title on page (also sets $dms_global["first_separator"])
add_action('wp_head','dms_hide_page_title');

function dms_hide_page_title()
    {
    global $dmsdb, $dms_global;

    $dms_page_flag = false;

    //  Connect to the database.
    $dmsdb->connect();

    $query = "SELECT data FROM ".$dmsdb->prefix("dms_config")." WHERE name='wordpress_page'";
    $dms_config_wp_page = $dmsdb->query($query,"data");

    if(strstr($_SERVER["REQUEST_URI"],"page_id") != false)
        {
        // Permalinks are off
        $page_id = dms_get_var("page_id");
        if ($page_id == $dms_config_wp_page) $dms_page_flag = true;
        $dms_global["first_separator"] = "&";
        }
    else
        {
        // Permalinks are on
        if(strstr($_SERVER["REQUEST_URI"],$dms_config_wp_page) != false) $dms_page_flag = true;
        $dms_global["first_separator"] = "?";
        }

    if( $dms_page_flag == true )
        {
        //  Remove Wordpress Page Title
        $output="<style> .entry-title {display: none; } </style>";
        echo $output;

        //  Set width of DMS display to 90%
        $output  = "<style>";
        $output .= ".site-content .entry-header,";
        $output .= ".site-content .entry-content,";
        $output .= ".site-content .entry-summary,";
        $output .= ".site-content .entry-meta,";
        $output .= ".page-content {margin: 0 auto; max-width: 90%; }";
        $output .= "</style>\r";
        echo $output;
        }
    }





//  DMS Installation Instructions In Dashboard
add_action( 'admin_menu', 'dms_plugin_options_menu' );

function dms_plugin_options_menu()
    {
	add_menu_page( 'DMS Installation Instructions', 'DMS Plugin', 'manage_options', 'dms_plugin_purge_menu', 'dms_plugin_instructions' );
    }

function dms_plugin_instructions()
    {
    print "<div class=\"wrap\">";
    print "<h2>Document Management System Installation Instructions</h2>";
    print "<BR><BR>";
/*
    1. Upload the 'dms' folder to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress
*/

    print "<table width = 80%>";

    print "<tr><td width=3%><b>1</b></td><td>Create a new page for the DMS Plugin through the 'Pages' menu.</td></tr>";
    print "<tr><td width=3%><b>2</b></td><td>Place the shortcode '[document_management_system]' on the page created in number 1, above.</td></tr>";
    print "<tr><td width=3%><b>3</b></td><td>In the Appearance/Menus screen add the DMS Plugin page to the menu of your choice.</td></tr>";
    print "<tr><td width=3%><b>4</b></td><td>Navigate to the DMS Plugin page.  If there are any messages, resolve them on the server.  They can usually be resolved by ensuring that the DMS Plugin can write to the locations in the message.  This is done by ensuring that the web server can write to these locations.</td></tr>";
    print "<tr><td width=3%><b>5</b></td><td>[VERY IMPORTANT] Click on the \"Configure\" Button at the bottom of the screen.  You can either make configuration changes here or exit this screen and the system will be ready for use.  This step is critical as some settings are automatically set and DMS module will not function without it.</td></tr>";

    print "</table>";

/*
	if ( !current_user_can( 'manage_options' ) )
        {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
        }
*/
//    print "<form>\r";

/*
    print "<div class=\"wrap\">";
    print "<h2>Document Management System Purge</h2>";
    print "<BR><BR>";
    print "Warning:  These functions will delete information in the DMS database tables and/or document repository.  Use with care.<BR>";
    print "<BR>";
    print "<button name='btn_reset' onclick='dms_reset_tables();'>Reset DMS Database Tables</button>&nbsp;&nbsp;";
    print "<button name='btn_drop' onclick='dms_drop_tables();'>Drop DMS Database Tables</button>";
    print "<BR><BR>";
    print "<button name='btn_delete_doc_repository' onclick='dms_purge_repository();'>Delete Document Repository</button>&nbsp;&nbsp;";
    print "</div>";
    print "<input type='hidden' id='dms_hdn_return_url' value=''>\r";
    print "<input type='hidden' id='dms_hdn_purge_function' value=''>\r";
//    print "</form>\r";

    print "<SCRIPT LANGUAGE='Javascript'>\r";
    print "dms_hdn_return_url.value = window.location.href;\r";
    print "function dms_reset_tables()\r";
    print "     {\r";
    print "     if(confirm('Warning:  This function will reset the DMS Database.'));\r";
    print "          {\r";
    print "          }\r";
    print "     }\r";
    print "function dms_drop_tables()\r";
    print "     {\r";
    print "     if(confirm('Warning:  This function will remove the DMS Database tables.'));\r";
    print "          {\r";
    print "          }\r";
    print "     }\r";
    print "function dms_purge_repository()\r";
    print "     {\r";
    print "     if(confirm('Warning:  This function will delete all documents in the repository.'));\r";
    print "          {\r";
    print "          }\r";
    print "     }\r";
    print "</SCRIPT>\r";
*/
    }







//  DMS functions for loading the DMS with options.

function dms_set_folder_id($folder_id)
    {
    global $dmsdb, $dms_user_id, $dms_var_cache;

	if($dms_user_id == 0)
        {
        //  User is not logged in.
        $dms_var_cache['public_user_folder'] = (int) $folder_id;
        }
    else
        {
        //  User is logged in.
        dms_set_inbox_status($folder_id);

        $query = "DELETE FROM ".$dmsdb->prefix("dms_active_folder")." WHERE user_id='".$dms_user_id."'";
        $dmsdb->query($query);

        // Set the folder as active
        $query = "INSERT INTO ".$dmsdb->prefix("dms_active_folder")." (user_id,folder_id) VALUES ('".$dms_user_id."','".$folder_id."')";
        $dmsdb->query($query);
        }

    $dms_var_cache['doc_alpha_sort'] = "ALL";
    dms_var_cache_save();
    }

?>
