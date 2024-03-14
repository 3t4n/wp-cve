<?php
//  ------------------------------------------------------------------------ //
//                     Document Management System                            //
//                   Written By:  Brian E. Reifsnyder                        //
//                                                                           //
//                See License.txt for copyright information.                 //
// ------------------------------------------------------------------------- //



if(strstr($_SERVER["REQUEST_URI"],"page_id") != false)
//if(strstr($dms_global['server_uri'],"page_id") != false)
    {
    //  Permalinks are not in use (default)
    $page_id = dms_get_var("page_id");

    $query = "UPDATE ".$dmsdb->prefix("dms_config")." SET data='".$page_id."' WHERE name='wordpress_page'";
    $dmsdb->query($query);
    }
else
    {
    //  Permalinks are in use
    if( strpos($_SERVER["REQUEST_URI"],"?") != false)
//    if( strpos($dms_global['server_uri'],"?") != false)
        {
        //  Store everything before the "?".
        $wordpress_page = substr($_SERVER["REQUEST_URI"],0,strpos($_SERVER["REQUEST_URI"],"?") );

        $query = "UPDATE ".$dmsdb->prefix("dms_config")." SET data='".$wordpress_page."' WHERE name='wordpress_page'";
        $dmsdb->query($query);
        }
    }


//  Setup config/config.php for /pages/file_retrieve.php

$file = DMS_DIR."config/config.php";


//  Test to see if the file can be accessed.  If not, display an error message.
if(!is_writeable($file))
    {
    print "<table width='100%'><tr>";
        print "<td style='width: 25%'><font color='red'>Permission Required</font></td>";
        print "<td>The DMS plugin is unable to write to the config.php file at ".$dms_global['dms_root_dir']."config/config.php</td>";
    print "</tr></table><BR>";

    exit(0);
    }


$fp = fopen($file,'w') or die("<BR><BR>Unable to open $file.  Most likely, the server does not have write access.");

$line = "<?php\n";
fputs($fp,$line);

$line = "define('DMS_VERSION', '".DMS_VERSION."');\n";
fputs($fp,$line);
$line = "define('DMS_RELEASE_DATE', '".DMS_RELEASE_DATE."');\n";
fputs($fp,$line);
$line = "define('DMS_DIR', '".DMS_DIR."');\n";
fputs($fp,$line);
$line = "define('DMS_URL', '".DMS_URL."');\n";
fputs($fp,$line);
$line = "define('DMS_ICONS', '".DMS_ICONS."');\n";
fputs($fp,$line);

$line = "define('DMS_DB_PREFIX', '');\n";
fputs($fp,$line);
$line = "define('DB_NAME', '".DB_NAME."');\n";
fputs($fp,$line);
$line = "define('DB_USER', '".DB_USER."');\n";
fputs($fp,$line);
$line = "define('DB_PASSWORD', '".DB_PASSWORD."');\n";
fputs($fp,$line);
$line = "define('DB_HOST', '".DB_HOST."');\n";
fputs($fp,$line);

$line = "?>\n";
fputs($fp,$line);

fclose($fp);

//print "!! DB_USER = ". DB_USER . "<BR>";

?>

