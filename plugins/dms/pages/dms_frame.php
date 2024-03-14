<?php
//  ------------------------------------------------------------------------ //
//                     Document Management System                            //
//                   Written By:  Brian E. Reifsnyder                        //
//                                                                           //
//                See License.txt for copyright information.                 //
// ------------------------------------------------------------------------- //


session_start();

$dms_wp_user_id = 0;

require_once( "../config/config.php" );

//  Load global variables that are to be loaded in Wordpress and passed into the frame.
$dms_global = $_SESSION['dms_global'];
$dms_global["first_separator"] = "?";

$dms_wp_user_id = $dms_global['dms_user_id'];
$dms_admin_flag = $dms_global['dms_admin_flag'];
$dms_wp_admin_flag = $dms_global['dms_admin_flag'];

if( $dms_global['dms_pro_dir'] != "FALSE" )
    {
    define("DMS_PRO", "INSTALLED");
    define("DMS_PRO_DIR", $dms_global['dms_pro_dir'] );
    define("DMS_PRO_URL", $dms_global['dms_pro_url'] );
    }

if( defined("DMS_PRO") && (DMS_PRO == "INSTALLED") )
    {
    require_once( DMS_PRO_DIR . "includes/i_dms_frame.php" );
    }

/*
if( $dms_global['dms_fts_dir'] != "FALSE")
    {
    define("DMS_FTS", "INSTALLED");
    define("DMS_FTS_DIR", $dms_global['dms_fts_dir'] );
    define("DMS_FTS_URL", $dms_global['dms_fts_url'] );
    }
*/


date_default_timezone_set($dms_global['wp_timezone']);

//  Check conditions to make sure the module is configured.  If it is not configured, display errors and exit.
$dms_run = true;


//  Check to see if the uploads directory exists.
$uploads_exist = true;
if(!file_exists($dms_global['wp_uploads']))
    {
    $dms_run = false;
    $uploads_exist = false;
    }

//  If the document repository path does not exist, try to create it.
if($uploads_exist == true)
    {
    if(!file_exists($dms_global['doc_path']))
        {
/*
        try{
            @mkdir($dms_global['doc_path']);
        }catch (Exception $e){
            $dms_run = false;
        }
*/

        if(! @mkdir($dms_global['doc_path']))
            {
            $dms_run = false;
            }

        }
    }

if($dms_admin_flag == 1)
    {
    //  Check dms/config/config.php
    $file = $dms_global['dms_root_dir']."config/config.php";
    if(!is_writeable($file))
        {
        print "<table width='100%'><tr>";
            print "<td style='width: 25%'><font color='red'>Permission Required</font></td>";
            print "<td>The DMS plugin is unable to write to the config.php file at ".$dms_global['dms_root_dir']."config/config.php</td>";
        print "</tr></table><BR>";

        $dms_run = false;
        }

    if($uploads_exist == false)
        {
        print "<table width='100%'><tr>";
            print "<td style='width: 25%'><font color='red'>Permissions or Directory Required</font></td>";
            print "<td>The DMS plugin is unable to write to the uploads directory at ".$dms_global['wp_uploads']."</td>";
        print "</tr></table><BR>";

        $dms_run = false;
        }

    if(!is_writeable($dms_global['doc_path']))
        {
        print "<table width='100%'><tr>";
            print "<td style='width: 25%'><font color='red'>Permissions or Directory Required</font></td>";
            print "<td>The DMS plugin is unable to write to the document repository at ".$dms_global['doc_path']."</td>";
        print "</tr></table>";

        $dms_run = false;
        }
    }

if (!defined('DB_HOST') )
    {
    print "<table width='100%'><tr>";
        print "<td style='width: 25%'><font color='blue'>Configuration Required</font></td>";
        print "<td>Please click on the Configuration button, below.</td>";
    print "</tr></table><BR>";

    $dms_run = false;
    }


if($dms_run == false) exit(0);

//  These must be loaded after the check for config.php has been made.

$includes_dir = DMS_DIR . "includes/general/";

if( defined("DMS_PRO_DIR") ) $includes_dir = DMS_PRO_DIR . "includes/";

require_once( DMS_DIR . "/includes/general/i_defines.php" );
require_once( DMS_DIR . "/includes/general/i_pal.php" );
require_once( $includes_dir . "i_dms_functions.php" );
require_once( DMS_DIR . "/includes/languages/english.php" );


//  Connect to the database.
//$conn = mysql_connect(DB_HOST,DB_USER,DB_PASSWORD);
//mysql_select_db(DB_NAME,$conn);

$dmsdb->connect();

$dms_user_id = $dms_global['dms_user_id'];
$dms_admin_flag = $dms_global['dms_admin_flag'];

dms_initialize();

//var_dump($_SESSION);

//  EVENTUALLY MOVE THIS TO $dms_global['dms_url']
$dms_config['dms_url'] = $dms_global['dms_url'];

//  Add Cascading Style Sheets
print "<head>\r";

print "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . DMS_URL . "css/dms.css\">\r";

//  Add stylesheet links only if file exists as file does not always exist.
if(file_exists ( $dms_global['dms_wp_css_dir']."/css/ie.css" ) )
    print "<link rel=\"stylesheet\" type=\"text/css\" href=\"".$dms_global['dms_wp_css_uri']."/css/ie.css\">\r";

if(file_exists ( $dms_global['dms_wp_css_dir']."/css/editor-style.css" ) )
    print "<link rel=\"stylesheet\" type=\"text/css\" href=\"".$dms_global['dms_wp_css_uri']."/css/editor-style.css\">\r";

if(file_exists ( $dms_global['dms_wp_css_dir']."/dashicons.min.css" ) )
    print "<link rel=\"stylesheet\" type=\"text/css\" href=\"".$dms_global['dms_wp_css_uri']."/dashicons.min.css\">\r";

if(file_exists ( $dms_global['dms_wp_css_dir']."/editor.css" ) )
    print "<link rel=\"stylesheet\" type=\"text/css\" href=\"".$dms_global['dms_wp_css_uri']."/editor.css\">\r";

print "</head>\r";


print "<div id=\"dms2015wp\">";

//  Page Dispatcher

$page_dir = DMS_DIR;
if(defined("DMS_PRO_DIR")) $page_dir = DMS_PRO_DIR;

switch( dms_get_var("dms_page") )
    {
    case "admin":
        require_once( $page_dir . "/pages/admin.php" );
        break;

    case "audit_log_detail":
        require_once( $page_dir . "/pages/audit_log_detail.php" );
        break;

    case "audit_log_obj":
        require_once( $page_dir . "/pages/audit_log_obj.php" );
        break;

    case "audit_log_select_user":
        require_once( $page_dir . "/pages/audit_log_select_user.php" );
        break;

    case "audit_log_tree":
        require_once( $page_dir . "/pages/audit_log_tree.php" );
        break;

    case "audit_log_user":
        require_once( $page_dir . "/pages/audit_log_user.php" );
        break;

    case "auto_folder_creation":
        require_once( $page_dir . "/pages/auto_folder_creation.php" );
        break;

    case "config":
        require_once( $page_dir . "/pages/config.php" );
        break;

    case "file_checkin":
        require_once( $page_dir . "/pages/file_checkin.php" );
        break;

    case "file_checkout":
        require_once( $page_dir . "/pages/file_checkout.php" );
        break;

    case "file_checkout_cancel":
        require_once( $page_dir . "/pages/file_checkout_cancel.php" );
        break;

    case "file_copy":
        require_once( $page_dir . "/pages/file_copy.php" );
        break;

    case "file_import":
        require_once( $page_dir . "/pages/file_import.php" );
        break;

    case "file_move":
        require_once( $page_dir . "/pages/file_move.php" );
        break;

    case "file_options":
        require_once( $page_dir . "pages/file_options.php" );
        break;

    case "file_recall":
        require_once( $page_dir . "pages/file_recall.php" );
        break;

    case "file_route":
        require_once( $page_dir . "pages/file_route.php" );
        break;

    case "file_revert":
        require_once( $page_dir . "pages/file_revert.php" );
        break;

    case "folder_close_all":
        require_once( $page_dir . "/pages/folder_close_all.php" );
        break;

    case "folder_config_change":
        require_once( $page_dir . "/pages/folder_config_change.php" );
        break;

    case "folder_content_report":
        require_once( $page_dir . "/pages/folder_content_report.php" );
        break;

    case "folder_prop_perms":
        require_once( $page_dir . "/pages/folder_prop_perms.php" );
        break;

    case "folder_expand":
        require_once( $page_dir . "/pages/folder_expand.php" );
        break;

    case "folder_move":
        require_once( $page_dir . "/pages/folder_move.php" );
        break;

    case "folder_new":
        require_once( $page_dir . "/pages/folder_new.php" );
        break;

    case "folder_options":
        require_once( $page_dir . "/pages/folder_options.php" );
        break;

    case "group_editor":
        require_once( $page_dir . "/pages/group_editor.php" );
        break;

   case "lifecycle_apply":
        require_once( $page_dir . "pages/lifecycle_apply.php" );
        break;

   case "lifecycle_demote":
        require_once( $page_dir . "pages/lifecycle_demote.php" );
        break;

    case "lifecycle_editor":
        require_once( $page_dir . "/pages/lifecycle_editor.php" );
        break;

    case "lifecycle_manager":
        require_once( $page_dir . "/pages/lifecycle_manager.php" );
        break;

    case "lifecycle_stage_editor":
        require_once( $page_dir . "pages/lifecycle_stage_editor.php" );
        break;

    case "lifecycle_promote":
        require_once( $page_dir . "pages/lifecycle_promote.php" );
        break;

    case "link_options":
        require_once( $page_dir . "/pages/link_options.php" );
        break;

    case "main":
        require_once( $page_dir . "/pages/main.php" );
        break;

    case "obj_delete":
        require_once( $page_dir . "/pages/obj_delete.php" );
        break;

    case "obj_restore":
        require_once( $page_dir . "/pages/obj_restore.php" );
        break;

    case "perms_xfer_ownership":
        require_once( $page_dir . "/pages/perms_xfer_ownership.php" );
        break;

    case "search_prop":
        require_once( $page_dir . "/pages/search_prop.php" );
        break;

    case "search_fts":
        if( defined("DMS_FTS") )
            {
            require_once( DMS_FTS_DIR . "/pages/search_fts.php" );
            }
        else
            {
            require_once( $page_dir . "/pages/main.php" );
            }

        break;

    case "set_config":
        require_once( $page_dir . "/pages/set_config.php" );
        break;

    case "statistics":
        require_once( $page_dir . "/pages/statistics.php" );
        break;

    default:
        require_once( $page_dir . "/pages/main.php" );
    }

print "</div>";

?>

