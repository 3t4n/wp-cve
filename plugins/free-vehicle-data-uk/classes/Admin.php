<?php
namespace FreeVehicleData;

if ( ! defined( 'ABSPATH' ) )
	exit;

class Admin{

    public function __construct() {
        add_action('admin_menu',[$this,'RegisterOptionsPage']);
        add_action('admin_init',[$this,'RegisterSettings']);
    }

    function RegisterSettings(){
        register_setting( 'FVDUKDisableEndPoint', 'FVDUKDisableEndPoint' );
        register_setting( 'FVDUKCreditUs', 'FVDUKCreditUs' );	
    }
    
    public function RegisterOptionsPage(){
      add_menu_page( 'Free Vehicle Data UK', 'FVD UK', 'manage_options', 'free-vehicle-data-uk', [$this,'AdminPage'], 'dashicons-car');

    }
    function AdminPage(){
        //$DisabledAPIfile = plugin_dir_path(FVD_FILE) . 'DisableEndpoint.txt';
        //$CreditUsFile = plugin_dir_path(FVD_FILE) . 'credit.txt';
        //bugfix for after upgrade of old ver to new;
	if (get_option( 'FVDUKDisableEndPoint' ) == false){
            add_option( 'FVDUKDisableEndPoint', 'yes' );//disable endpoint until signup (avoid errors)
	}
	
	if (get_option( 'FVDUKCreditUs' ) == false){
            add_option( 'FVDUKCreditUs', 'yes' );//Set credit us option
	}
	
	if (get_option( 'FVDUKCreditLink' ) == false){
            $selectone = array("https://www.rapidcarcheck.co.uk/", "https://www.rapidcarcheck.co.uk/developer-api/");	
            $kxx = array_rand($selectone);
            $vxx = $selectone[$kxx];
            add_option( 'FVDUKCreditLink', $vxx );//Set credit us option
	}


        if (isset($_GET["clearapi"])){
            update_option("FVDUKDisableEndPoint", "yes");	
        }


        $CreditUsStats = get_option('FVDUKCreditUs');
        $DisableAPIStats = get_option('FVDUKDisableEndPoint');


        if ($CreditUsStats == 'no'){
            $CurrentSupporter = 'no';	
        }
        else{
            $CurrentSupporter = 'yes';		
        }

        // FUNCTION TO DELETE IMAGE CACHE
        if (isset($_POST['deleteimagecache'])) {
            $imagefiles = glob(plugin_dir_path(FVD_FILE) . 'assets/vehicleimages/*');
            $imageremovalcounter = '0';
            foreach ($imagefiles as $imagefile){
                if (is_file($imagefile)) {
                    unlink($imagefile);
                    ++$imageremovalcounter;
                }
            }
            echo '<div class="notice notice-success is-dismissible">
                    <p><strong>Free Vehicle Data UK:</strong><br>' . $imageremovalcounter . ' Local image cache files have been deleted!</p>
                </div>';	
        }

        // FUNCTION TO WIPE SEACH LogFile
        if (isset($_POST['deletesearchlog'])) 
        {
            unlink(plugin_dir_path(FVD_FILE) . 'assets/log.txt');

            echo '<div class="notice notice-success is-dismissible">
                <p><strong>Free Vehicle Data UK:</strong><br>Vehicle search log was deleted!</p>
            </div>';	
        }


        // demo page creation..
        if (isset($_POST['demopageimport'])) {
            //import 2 column layout demo	
            $twocolumnfile = plugin_dir_path(FVD_FILE) . 'templates/2-col-layout.php';	
            $demo_content_2_column_layout = fopen($twocolumnfile, "r") or die("");
            $twocoldemo = fread($demo_content_2_column_layout,filesize($twocolumnfile));
            fclose($demo_content_2_column_layout);

            $my_demo_post = 
            [
            'post_title'	=> 'Two Column FVD Demo',
            'post_content'	=> $twocoldemo,
            'post_status'	=> 'publish',
            'post_type'		=> 'page'
            ];

            $infopage_id_page = wp_insert_post( $my_demo_post );
            

            //import one column layout demo
            $onecolumnfile = plugin_dir_path(FVD_FILE) . 'templates/1-col-layout.php';	
            $demo_content_1_column_layout = fopen($onecolumnfile, "r") or die("");
            $onecoldemo = fread($demo_content_1_column_layout,filesize($onecolumnfile));
            fclose($demo_content_1_column_layout);

            $one_col_page = 
            [
            'post_title'	=> 'One Column FVD Demo',
            'post_content'	=> $onecoldemo,
            'post_status'	=> 'publish',
            'post_type'		=> 'page'
            ];

            $one_col_pageid = wp_insert_post( $one_col_page );

            //import two column with alt css layout demo
            $twocolumnfilealt = plugin_dir_path(FVD_FILE) . 'templates/2-col-layout-alt.php';	
            $demo_content_2_column_layoutalt = fopen($twocolumnfilealt, "r") or die("");
            $twocoldemoalt = fread($demo_content_2_column_layoutalt,filesize($twocolumnfilealt));
            fclose($demo_content_2_column_layoutalt);

            $two_col_pagealt = 
            [
            'post_title'	=> 'Two Column ALT FVD Demo',
            'post_content'	=> $twocoldemoalt,
            'post_status'	=> 'publish',
            'post_type'		=> 'page'
            ];

            $two_column_alt_demo = wp_insert_post( $two_col_pagealt );

            //import two column with alt css layout demo (no man logo)
            $twocolumnfilealt1 = plugin_dir_path(FVD_FILE) . 'templates/2-col-layout-alt-img.php';	
            $demo_content_2_column_layoutalt1 = fopen($twocolumnfilealt1, "r") or die("");
            $twocoldemoalt = fread($demo_content_2_column_layoutalt1,filesize($twocolumnfilealt1));
            fclose($demo_content_2_column_layoutalt1);

            $two_col_pagealt1 = 
            [
            'post_title'	=> 'Two Column ALT FVD Demo ALT IMG',
            'post_content'	=> $twocoldemoalt,
            'post_status'	=> 'publish',
            'post_type'		=> 'page'
            ];

            $two_column_alt_demo1 = wp_insert_post( $two_col_pagealt1 );


            echo '<div class="notice notice-success is-dismissible">
                    <p><strong>Free Vehicle Data UK:</strong><br>Your demo pages have been imported, Click through the demo pages below to see which one best matches your needs!
                            <br><br>
                            <h3>2 Column Demo Pages Imported:</h3>
                            <strong>[Plain] Two Column Demo Page Created!</strong><br> >> <a href="'.get_permalink( $infopage_id_page ). '?Reg=FY60KGG' . '" target="_blank">View Two Column Plain Demo Page</a> - Vehicle Search Box ShortCode:<strong> [fvd_searchbox page=' . get_permalink( $infopage_id_page ) . ']</strong>	
                            <br><br>
                            <strong>[ALT] Two Column Demo Page Created!</strong><br> >> <a href="'.get_permalink( $two_column_alt_demo ). '?Reg=FY60KGG' . '" target="_blank">View Two Column ALT Demo Page</a>	- Vehicle Search Box ShortCode:<strong> [fvd_searchbox page=' . get_permalink( $two_column_alt_demo ) . ']</strong>
                            <br><br>
                            <strong>[ALT V2] Two Column Demo Page Created!</strong><br> >> <a href="'.get_permalink( $two_column_alt_demo1 ). '?Reg=FY60KGG' . '" target="_blank">View Two Column ALT V2 Demo Page</a> - Vehicle Search Box ShortCode:<strong> [fvd_searchbox page=' . get_permalink( $two_column_alt_demo1 ) . ']</strong> 
                            <br><br><h3>1 Column Demo Pages Imported:</h3>	
                            <strong>One Column Demo Page Created!</strong><br> >> <a href="'.get_permalink( $one_col_pageid ). '?Reg=FY60KGG' . '" target="_blank">View One Column Demo Page</a> - Vehicle Search Box ShortCode:<strong> [fvd_searchbox page=' . get_permalink( $one_col_pageid ) . ']</strong>
                            </p>
                </div>';

        }
// API access status check
$json_api_usage = json_decode(wp_remote_retrieve_body(wp_remote_get( 'https://www.rapidcarcheck.co.uk/FreeAccess/Requests.php?auth=LeCc0xMsd00fnsMF345o3&site=' . '&site=' . get_option( 'siteurl' ))), true);


if ($DisableAPIStats == 'yes') {
    
    if (isset(($json_api_usage["APIAccess"]))){
        update_option( 'FVDUKDisableEndPoint', 'no' );
    }
}



//if (file_exists($DisabledAPIfile)) {
if ($DisableAPIStats == 'yes') {	
	// sign up return url;
	$AdminReturnURLAfterSignup = get_admin_url( null, null, null ) . 'admin.php?page=free-vehicle-data-uk';

if (isset($_GET['first'])) {

        $AdminReturnURLAfterSignup12 = get_admin_url( null, null, null ) . 'admin.php?page=free-vehicle-data-uk';


                echo '<h1 class="fvduk-title">Free Vehicle Data UK</h1><small><i>by RapidCarCheck.co.uk</i></small>';
                echo '<h3>Thank you for signing up to the free API access!</h3>';
                echo '<div style="height:5px" aria-hidden="true" class="admin-spacer"></div>';
                echo '<p class="backend-font-heavy">You have signed up! Click the button below to access your admin backend</p>';
                echo '<div style="height:12px" aria-hidden="true" class="admin-spacer"></div>';	
                echo '<div class="gen_button_sign"><a class="sign-up-button1" href="'.$AdminReturnURLAfterSignup12.'"><b>ACCESS ADMIN BACKEND NOW!</b></a></div>';
                echo '<div style="height:12px" aria-hidden="true" class="admin-spacer"></div>';	


        }else{

                echo '<h1 class="fvduk-title">Free Vehicle Data UK</h1><small><i>by RapidCarCheck.co.uk</i></small>';
                echo '<h3>Thank you for installing Free Vehicle Data UK Plugin!</h3>';
                echo '<div style="height:5px" aria-hidden="true" class="admin-spacer"></div>';
                echo '<p class="backend-font-heavy">To activate plugin/API endpoint sign-up free below (instant):</p>';
                echo '<div style="height:12px" aria-hidden="true" class="admin-spacer"></div>';
                echo '<div class="gen_button_sign"><a class="sign-up-button1" href="https://www.rapidcarcheck.co.uk/login/?site=' . get_option( 'siteurl' ) . '&return=' . $AdminReturnURLAfterSignup . '" target="_blank"><b>SIGN UP FREE NOW!</b></a></div>';
                echo '<div style="height:12px" aria-hidden="true" class="admin-spacer"></div>';
                echo '<p class="backend-font-heavy">Why Do i Need to Sign Up?</p>';
                echo '<p class="backend-font-light">Sign up takes less than 30 seconds and only requires an email address, we require sign up to keep things fair and avoid abuse of the free API service.</p>';
                //echo '<p class="backend-font-light">Once sign up is complete it can take up-to 2 hours for approval, contact support for faster approval: enquiries@rapidcarcheck.co.uk.</p>';
                echo '<div style="height:12px" aria-hidden="true" class="admin-spacer"></div>';
                //echo '<p class="backend-font-heavy">Already Signed Up?</p>';
                //echo '<p class="backend-font-light">Please allow up-to 2 hours for plugin activation, if you are still seeing this screen after 2 hours contact support for further assistance: enquiries@rapidcarcheck.co.uk</p>';

        }

}else{
	echo '<h1 class="fvduk-title">Free Vehicle Data UK</h1><small><i>by RapidCarCheck.co.uk</i></small>';
//	if (file_exists($CreditUsFile)) {
//	$CurrentSupporter = 'no';
//	}else{
//	$CurrentSupporter = 'yes';
//	}
	echo '<div style="height:32px" aria-hidden="true" class="admin-spacer"></div>';

	//API usage based on API level
	if ($CurrentSupporter == 'yes'){
	echo '<p class="backend-font-heavy-big"><img src="' . FVD_URL. '/assets/images/gold-star.png">Monthly API Usage</p>';
	}else{
	echo '<p class="backend-font-heavy-big"><img src="' . FVD_URL. '/assets/images/gold-star.png">Monthly API Usage</p>';	
	}

	echo '<p class="backend-api-stats">Usage statistics for: <strong>' . get_option( 'siteurl' ) . '</strong></p>';
	echo '<p class="backend-api-stats">API package: <strong>' . ($json_api_usage["MonthlyPackage"]) . '</strong></p>';
	echo '<p class="backend-api-stats">Checks remaining: <strong>' . ($json_api_usage["DailyLimit"]) . '</strong></p>';
	
	
	
	
	
	
	
	echo '<div style="height:32px" aria-hidden="true" class="admin-spacer"></div>';
	if ($CurrentSupporter == 'yes') {
	echo '<h2>Last 100 Vehicle Searches</h2>';
	
	//LOG FILE OUTPUT:
	$LogFile = plugin_dir_path(FVD_FILE) . 'assets/log.txt';
	
	if (file_exists($LogFile)){
	
	$fileforlog = file($LogFile);
	$xlogcount = count($fileforlog);	
	$searchfilesize = file($LogFile, FILE_IGNORE_NEW_LINES);
	$limitsearch = 100;
	
	echo '<textarea id="searchlog" class="backendsearchfvd" class="box" rows="10">';
	//return log file..
	foreach($searchfilesize as $searchfilesize){
	if ($limitsearch <=0){
	}else{
	--$limitsearch;	
	--$xlogcount;
	echo $fileforlog[$xlogcount];	
	}
	}
	echo '</textarea>';	
	echo '<br>To view all ' . count($fileforlog) . ' search records, <a href="' . FVD_URL. 'assets/log.txt" target="_blank">View the full search log now</a>';
	echo '<div style="height:32px" aria-hidden="true" class="admin-spacer"></div>';
	}else{
	echo 'Search log will appear here once first search is made!';
	echo '<div style="height:32px" aria-hidden="true" class="admin-spacer"></div>';	
	}
	}
	}
	
//if (file_exists($DisabledAPIfile)) {
	

//if (file_exists($DisabledAPIfile)) {
if ($DisableAPIStats == 'yes') {	
}else{
//vehicle data to form
//VinhDQ remove - 2022.12.06
/*
echo '<div style="height:12px" aria-hidden="true" class="admin-spacer"></div>';
echo '<p class="backend-font-heavy-big"><img src="' . FVD_URL. '/assets/images/gold-star.png"> Need a Custom Solution?</p>';
echo '<p class="clear-font-text">We have worked with many businesses and individuals over the last few years, mainly helping with implementing vehicle data into their websites, forms and custom projects.</p>';
echo '<p class="clear-font-text">So, if you get stuck, just remember we are here to help, Contact us via email (enquiries@rapidcarcheck.co.uk).</p>';
echo '<div style="height:12px" aria-hidden="true" class="admin-spacer"></div>';	
*/	

//demo page importer..
echo '<div style="height:12px" aria-hidden="true" class="admin-spacer"></div>';
echo '<p class="backend-font-heavy-big">Import Demo Pages?</p>';
echo '<p class="clear-font-text">Click the button below to import demo pages and make setup quicker!</p>';
echo '<form id="demopage" method="POST" action="">
	<input name="demopageimport" id="demopageimport" type="hidden" value="yes">
    <div class="gen_button_sign"><button type="submit" class="demo-page-importfvd" id="demopimport">IMPORT DEMO PAGE CONTENT</button></div></form>';
echo '<div style="height:12px" aria-hidden="true" class="admin-spacer"></div>';	

//clear image cache..
echo '<div style="height:12px" aria-hidden="true" class="admin-spacer"></div>';
echo '<p class="backend-font-heavy-big">Cleaning</p>';
echo '<p class="clear-font-text">Over time vehicle image files and logs can build up, to keep things running smoothly, delete logs and locally stored images once in a while.</p>';
echo '<div style="height:1px" aria-hidden="true" class="admin-spacer"></div>';	

echo '<form id="delsearchlog" method="POST" action="">
	<input name="deletesearchlog" id="deletesearchlog" type="hidden" value="yes">
    <div class="gen_button_settings"><button type="submit" class="clear-logs-btn" id="demopimport">DELETE VEHICLE SEARCH LOG</button></div></form>';
echo '<div style="height:12px" aria-hidden="true" class="admin-spacer"></div>';	

echo '<form id="clearimagecache" method="POST" action="">
	<input name="deleteimagecache" id="deleteimagecache" type="hidden" value="yes">
    <div class="gen_button_settings"><button type="submit" class="clear-logs-btn" id="demopimport">CLEAR LOCAL IMAGE CACHE</button></div></form>';
echo '<div style="height:12px" aria-hidden="true" class="admin-spacer"></div>';	
		
	
//help and support...
echo '<div style="height:12px" aria-hidden="true" class="admin-spacer"></div>';
echo '<p class="backend-font-heavy-big">Help and Support?</p>';
echo '<p class="clear-font-text">> <a href="https://www.rapidcarcheck.co.uk/Support/PluginSetupGuide.pdf" target="_blank">PDF Setup Guide</a></p>';
echo '<p class="clear-font-text">> <a href="' . FVD_URL. 'ShortCodeList.csv" target="_blank">Full Shortcode List With Explanation (.csv)</a>';
//echo '<p class="clear-font-text">> <a href="https://youtu.be/4eonzwKmGPI" target="_blank">YouTube Video Setup Guide</a>';
if ($CurrentSupporter == 'yes'){
//echo '<p class="clear-font-text">> Stuck With Something? - Email Us: enquiries@rapidcarcheck.co.uk</a>';
}else{
//echo '<p class="clear-font-text">> Stuck With Something? - Email Us: <strong>[Upgrade API access to reveal]</strong></a>';	
}
$ClearAPAcc = get_admin_url( null, null, null ) . 'admin.php?page=free-vehicle-data-uk&clearapi=true';
echo '<p class="clear-font-text">> <a href="' . $ClearAPAcc . '">Deauthorize API Access (Only do this if you are having access issues)</a></strong></a>';	
echo '<p class="clear-font-text">> <a href="https://www.rapidcarcheck.co.uk/extra-monthly-top-up-vehicle-checks/" target="_blank">Going over your limit? You can buy more checks here: https://www.rapidcarcheck.co.uk/extra-monthly-top-up-vehicle-checks/</a></p>';


} 
    }
    
}





