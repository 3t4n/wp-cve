<?php
/**
 *       @file  qtrAjaxHandler.php
 *      @brief  This module contains AJAX callbacks
 *
 *     @author  Quttera (qtr), contactus@quttera.com
 *
 *   @internal
 *     Created  01/17/2016
 *     Company  Quttera
 *   Copyright  Copyright (c) 2016, Quttera
 *
 * This source code is released for free distribution under the terms of the
 * GNU General Public License as published by the Free Software Foundation.
 * =====================================================================================
 */


require_once('qtrOptions.php');
require_once('qtrExternalScanner.php');
require_once('qtrUtils.php');
require_once('qtrLogger.php');
require_once('qtrFilesScanner.php');
require_once('qtrStats.php');
require_once('qtrScanLock.php');
require_once('qtrIgnoreList.php');
require_once('qtrThreatsWhiteList.php');
require_once('qtrFilesWhiteList.php');
require_once('qtrFsSnapShot.php');

define( 'QTR_SCAN_CRON_ARGS', 'qtr_scan_cron_args');

define( 'QTR_SCAN_CRON_PERIOD', 5*60 );

define( 'QTR_SCAN_CRON_TIMEOUT',  QTR_SCAN_CRON_PERIOD - 5 );

/*
 * add filter to add 30 seconds cron period
 */
add_filter( 'cron_schedules', 'qtr_scanner_custom_cron_schedule' );

/*
 * maps cron hook to appropriate callback to be invoked for internal scan
 */
add_action( 'qtr_internal_scan_cron_hook', 'on_qtr_scanner_internal_scan_cron_event' );

add_action( 'qtr_heur_internal_scan_cron_hook', 'on_qtr_scanner_heur_internal_scan_cron_event' );


/**
 * Adds a custom cron schedule for every 5 minutes.
 *
 * @param array $schedules An array of non-default cron schedules.
 * @return array Filtered array of non-default cron schedules.
 */
function qtr_scanner_custom_cron_schedule( $schedules ) {
    $schedules[ 'qtrScanPeriod' ] = array( 'interval' => QTR_SCAN_CRON_PERIOD, 'display' => __( sprintf("Every %d seconds",QTR_SCAN_CRON_PERIOD)));
    return $schedules;
}


/**
 * @brief   removes all instances of registered cron job
 * @return  nothing 
 */
function clean_internal_scan_hook()
{
    wp_cache_flush();
    do {
        $timestamp = wp_next_scheduled( 'qtr_internal_scan_cron_hook' );
        wp_unschedule_event( $timestamp, 'qtr_internal_scan_cron_hook' );
    }while(($timestamp = wp_next_scheduled( 'qtr_internal_scan_cron_hook' )));
    wp_cache_flush(); 
}

/**
 * @brief   removes all instances of registered cron job
 * @return  nothing 
 */
function clean_heur_internal_scan_hook()
{
    wp_cache_flush();
    do {
        $timestamp = wp_next_scheduled( 'qtr_heur_internal_scan_cron_hook' );
        wp_unschedule_event( $timestamp, 'qtr_heur_internal_scan_cron_hook' );
    }while(($timestamp = wp_next_scheduled( 'qtr_heur_internal_scan_cron_hook' )));
    wp_cache_flush(); 
}


function schedule_internal_scan_hook()
{
    /*
     * submit cron job event to run internal scan
     */
    wp_schedule_event( time() + 10, 'qtrScanPeriod', 'qtr_internal_scan_cron_hook');

    $logger = new CQtrLogger();
    $logger->Info(
        sprintf("Internal scan scheduled. Next run %s", 
            gmdate("H:i:s", 
                wp_next_scheduled('qtr_internal_scan_cron_hook'))));
}


function schedule_heur_internal_scan_hook()
{
    /*
     * submit cron job event to run internal scan
     */
    wp_schedule_event( time() + 10, 'qtrScanPeriod', 'qtr_heur_internal_scan_cron_hook');

    $logger = new CQtrLogger();
    $logger->Info(
        sprintf("High sensitive internal scan scheduled. Next run %s", 
            gmdate("H:i:s", 
                wp_next_scheduled('qtr_heur_internal_scan_cron_hook'))));
}


/**
 * @brief       stores provided file system snapshot (list of files to scan)
 * @param[in]   $snapshot - snapshot object to store
 * @return      FALSE on failure and TRUE on success
 */
function store_snapshot($snapshot, $logger )
{
    $json = $snapshot->ToString();
    $rc = FALSE;
    $deprecated = null;
    $autoload = 'no';

    if( !$json && $snapshot->FilesCount() > 0){
        $logger->Error("Failed to serialize filesystem snapshot");
    }

    if( defined("QTR_FS_SNAPSHOT") and QTR_FS_SNAPSHOT ){
        $logger->Info("Storing file based snapshot");
        $rc = CQtrOptions::SaveOption( QTR_SCAN_CRON_ARGS, $json, $deprecated, $autoload, $logger);
        if(!$rc){ $logger->Error("Failed to store filesystem snapshot"); }
        else{ $logger->Info("Filesystem snapshot stored successfully"); }
    }else if ( CQtrOptions::GetOption( QTR_SCAN_CRON_ARGS,$logger) !== false ) {
        $rc = CQtrOptions::UpdateOption( QTR_SCAN_CRON_ARGS, $json, $logger );
    }else{
        $deprecated = null;
        $autoload = 'no';
        $rc = CQtrOptions::AddOption( QTR_SCAN_CRON_ARGS, $json, $deprecated, $autoload, $logger);
    }

    return $rc;
}


/**
 * @brief   loads previously stored 
 * @return  on error returns NULL, on success returns loaded and initialized snapshot object 
 */
function load_snapshot($logger)
{
    $json = NULL;
    if( defined("QTR_FS_SNAPSHOT") and QTR_FS_SNAPSHOT ){
        $logger->Info("Loading file based snapshot");
        $json = CQtrOptions::LoadOption( QTR_SCAN_CRON_ARGS, false, $logger);
        /*
        $logger->Info(sprintf("Loaded json [%s]",$json));
        */
    }else{
        $json = CQtrOptions::GetOption( QTR_SCAN_CRON_ARGS, $logger);
    }

    if( !$json ){
        return NULL;
    }

    $fs = new CQtrFsSnapShot();
    $fs->FromString($json);
    return $fs;
}


function force_next_cron_job(){
    spawn_cron();
}


/**
 * @brief       cron job callback procedure
 * @param[in]   $args - list of input arguments 
 * @return      nothing
 */
function on_qtr_scanner_internal_scan_cron_event($args=NULL)
{
    @set_time_limit(0);

    $logger = new CQtrLogger();
    $snapshot = load_snapshot($logger);
    if( !$snapshot ){
        $logger->Error("Failed to locate filesystem snapshot");
        return;
    }
    $logger->Info(sprintf("Snapshot %d elements", $snapshot->FilesCount()));
    $stime = time();
    $etime = $stime + QTR_SCAN_CRON_TIMEOUT;
    $scanner = new CQtrFilesScanner(FALSE/*not-heuristic*/);
    $scanner->Initialize();

    while(time() < $etime){
        /*
         * runing limited period of time to prevent system overload
         */
        $item = $snapshot->Pop();

        if($item == NULL ){
            /*
             * snapshot is empty, all scanned
             */
            $scanner->Finalize();
            /*
             * remove callback registration
             */
            clean_internal_scan_hook();
            $logger->Info(sprintf("Investigation of %s done", ABSPATH));
            return;
        }

        /*
         * In case scan of next file will take too much and this session will be killed
         */
        store_snapshot($snapshot, $logger);

	    if(is_file($item)){
            #$logger->Info(sprintf("%s Scanning $item", __FUNCTION__));
			$scanner->ScanFile($item);
		}else if($scanner->IsIgnored($item)){
			$logger->Info(sprintf("Skipping %s"));
	    }else{
            /*
             *  populate snapshot with more info 
             */
            $logger->Info("populate snapshot from $item");
            /*
             * add to snapshot all files/dirs from current location
             */
		    $snapshot->Populate($item);
            /*
             * store changes after population
             */
            store_snapshot($snapshot, $logger);
	    }
    }

    if(wp_next_scheduled('qtr_internal_scan_cron_hook')){
    	force_next_cron_job();
    }else{
        /*
         * This case may occure when used terminated scan from the dashboard
         */
        $logger->Info(sprintf("Investigation of %s done", ABSPATH));
    }
    return;
}


/**
 * @brief       cron job callback procedure
 * @param[in]   $args - list of input arguments 
 * @return      nothing
 */
function on_qtr_scanner_heur_internal_scan_cron_event($args=NULL)
{
    @set_time_limit(0);
    $logger = new CQtrLogger();
    $snapshot = load_snapshot($logger);
    if( !$snapshot ){
        $logger->Error("Failed to locate filesystem snapshot");
        return;
    }
    $logger->Info(sprintf("Snapshot %d elements", $snapshot->FilesCount()));
    $stime = time();
    $etime = $stime + QTR_SCAN_CRON_TIMEOUT;
    $scanner = new CQtrFilesScanner(TRUE/*heuristic*/);
    $scanner->Initialize();

    while(time() < $etime){
        /*
         * runing limited period of time to prevent system overload
         */
        $item = $snapshot->Pop();

        if($item == NULL ){
            /*
             * snapshot is empty, all scanned
             */
            $scanner->Finalize();
            /*
             * remove callback registration
             */
            clean_heur_internal_scan_hook();
            $logger->Info(sprintf("Investigation of %s done", ABSPATH));
            return;
        }

        /*
         * In case scan of next file will take too much and this session will be killed
         */
        store_snapshot($snapshot, $logger);

	    if(is_file($item)){
            #$logger->Info(sprintf("%s Scanning $item", __FUNCTION__));
            $scanner->ScanFile($item);
		}else if($scanner->IsIgnored($item)){
			$logger->Info(sprintf("Skipping %s"));
	    }else{
            /*
             *  populate snapshot with more info 
             */
            $logger->Info("populate snapshot from $item");
		    $snapshot->Populate($item);
            /*
             * store changes after population
             */
            store_snapshot($snapshot, $logger);
	    }
    }

    if(wp_next_scheduled('qtr_heur_internal_scan_cron_hook')){
    	force_next_cron_job();
    }else{
        /*
         * This case may occure when used terminated scan from the dashboard
         */
        $logger->Info(sprintf("High Sensitive Investigation of %s done", ABSPATH));
    }
    return;
}


class CQtrAjaxHandler
{

    private static function __can_access(){
        $nonce = $_REQUEST['_wpnonce'];
        if (!wp_verify_nonce( $nonce, 'quttera' ) ) {
            wp_die(__('You do not have sufficient permissions to access this page.') );
        }
        if(!current_user_can('manage_options')){
            wp_die(__('You do not have sufficient permissions to access this page.') );
        }
    }


    public static function RunExternalScan()
    {
        self::__can_access();        
        //check_ajax_referer( 'qtr_wm_scanner-scan' );
        $this_url = trim($_POST['_this']);        /* domain name of this server */
        $qtr_url  = trim($_POST['_qtr_url']);     /* quttera investigation server name */
        
        if( empty($this_url) )
        {
            $this_url = CQtrUtils::GetDomainName();
        }
        else if( empty($qtr_url) )
        {
            $qtr_url = "http://wp.quttera.com";
        }

        if( strpos($this_url, "://" ) != false )
        {
            $parse      = parse_url($this_url);
            $this_url   = $parse['host'];
        }
        /* 
        * validate input of host name 
        */
        if(filter_var(gethostbyname($this_url), FILTER_VALIDATE_IP) === FALSE )
        {
            /* send error to frontend */
            echo json_encode( array( 'content' => array("state" => "Failed to access local server",
                                                        "age" => time(),
                                                        "url" => "localhost" )));
            exit;

        }
        
        /* validate quttera server address */
        if( filter_var($qtr_url, FILTER_VALIDATE_URL) === FALSE )
        {
            /* send error to fronend */
            echo json_encode( array( 'content' => array("state" => "Failed to access remote server",
                                                        "age" => time(),
                                                        "url" => $this_url )));
            exit;
        }

        $investigation_url =  $qtr_url . "/wp_scan/" . $this_url;

        if( filter_var($investigation_url, FILTER_VALIDATE_URL) === FALSE )
        {
            echo json_encode( array( 'content' => array( "state" => "Remote server address is invalid",
                                                        "age" => time(),
                                                        "url" => "<undefined>" )));
            exit;
        }

        usleep(1000000); //sleep for a second

        $output = CQtrExternalScanner::SendQuery( $investigation_url );
        
        if( empty($output) )
        {
            $output = CQtrExternalScanner::SendQuery( $investigation_url );
        
            if( $output == false )
            {
                echo json_encode( array( 'content' => array("state" => "Failed to access investigation server [ " . $qtr_url . " ]",
                                                            "age" => time(),
                                                            "url" => $this_url,
                                                            "img" => plugins_url( 'loader.gif', __FILE__ )) 
                                        ) 
                                );
                exit;
            }
        }
        
        $output = json_decode($output);
        
        //if state is not finished sleep for a second
        echo json_encode( array( 'content' => $output ) );
        exit;
    }


    public static function IsInternalScanNowRunning()
    {
        self::__can_access(); 
        if( self::IsInternalScanRunning() ){
	        echo "yes";
	    }else{
            echo "no";
        }
        exit();
    }

    public static function RunInternalScan()
    {
        self::__can_access();
        wp_cache_flush();
        flush();

        $logger = new CQtrLogger();
        $logger->Info(sprintf("Starting investigation of %s",ABSPATH));

        /*
         * Check if any internal scan is running
         */
        if( self::IsInternalScanRunning() ){
            $logger->Info("Error, internal scan process already running");
            $output = $logger->GetAllLines();
            echo json_encode($output);
            exit();
        }
        
        $stats  = new CQtrStats();
        $stats->Reset(); 

        $report = new CQtrReport();
        $report->Reset();

        $scanner = new CQtrFilesScanner(FALSE/*non-heuristic*/);
        $scanner->Initialize();

        $path = ABSPATH;
        $fs = new CQtrFsSnapShot();
        $fs->Populate(ABSPATH); 
        /*
         * store initial filesystem snapshot to be scanned
         */
        store_snapshot($fs, $logger);
        /*
         * clean previous cron jobs if exist
         */
        clean_internal_scan_hook();

        /*
         * Starting next scan slot
         */
        schedule_internal_scan_hook();
        $logger->Info("Starting internal scan of [$path]");
        $output = $logger->GetAllLines();
        echo json_encode($output);
        exit();
    }


    public static function RunHeurInternalScan()
    {
        self::__can_access();
        wp_cache_flush();
        flush();

        $logger = new CQtrLogger();
        $logger->Info(sprintf("Starting investigation of %s",ABSPATH));

        /*
         * Check if any internal scan is running
         */
        if( self::IsInternalScanRunning() ){
            $logger->Info("Error, internal scan process already running");
            $output = $logger->GetAllLines();
            echo json_encode($output);
            exit();
        }
        
        $stats  = new CQtrStats();
        $stats->Reset(); 

        $report = new CQtrReport();
        $report->Reset();

        $scanner = new CQtrFilesScanner(TRUE/*heuristic*/);
        $scanner->Initialize();

        $path = ABSPATH;
        $fs = new CQtrFsSnapShot();
        $fs->Populate(ABSPATH); 
        /*
         * store initial filesystem snapshot to be scanned
         */
        store_snapshot($fs, $logger);
        /*
         * clean previous cron jobs if exist
         */
        clean_heur_internal_scan_hook();

        schedule_heur_internal_scan_hook();
        $logger->Info("Starting high sensitive internal scan of [$path]");
        $output = $logger->GetAllLines();
        echo json_encode($output);
        exit();
    }


    public static function StopInternalScan()
    {
        self::__can_access();
        $logger = new CQtrLogger();
        $logger->Info("Handling request to terminate internal scan");
        $logger->Info("Remove cron job from scheduler");

        wp_cache_flush();

        clean_internal_scan_hook();

        clean_heur_internal_scan_hook();

        if(wp_next_scheduled('qtr_internal_scan_cron_hook')){
            $logger->Error("Failed to remove scanner event from cron schedule");
        }else{
            $logger->Info("Cron job cleared");
        }

        exit();
    }

    public static function IsInternalScanRunning()
    {
        self::__can_access();
        wp_cache_flush();

        $timestamp = wp_next_scheduled('qtr_internal_scan_cron_hook');
        if($timestamp){
            return TRUE;
        }

        $timestamp = wp_next_scheduled('qtr_heur_internal_scan_cron_hook');
        if(!$timestamp){
            return FALSE;
        }

        return TRUE;
    }

    public static function GetLogLines()
    {
        self::__can_access();
        $index  = 0;
        $logger = new CQtrLogger();
        
        if( isset( $_GET['start_line']) ) 
        {
            $index = intval( $_GET['start_line']);
        }

        else if( isset( $_POST['start_line']) ) 
        {
            $index = intval( $_POST['start_line']);
        }

        $lines = $logger->GetAllLines();
        echo json_encode($lines);
        exit();
    }

    public static function CleanLogLines()
    {
        self::__can_access();
        $index  = 0;
        $logger = new CQtrLogger();
        $logger->Clean();        
        #echo __FUNCTION__ . " called\n";
        exit();
    }


    public static function GetStats()
    {
        self::__can_access();
        wp_cache_flush();
        $report = new CQtrReport();
        $stats  = $report->GetStats();
        $counters = $stats->GetCounters();
        echo json_encode($counters);
        exit();
    }

    public static function ScannerReport()
    {
        self::__can_access();
        $report = new CQtrReport();
        $header = $report->GenerateMeta();
        $dump   = $report->GetDetectedThreats();
        $body = "";
        foreach ( $dump  as $entry){
            $threat = $entry["THREAT"];
            $threat = preg_replace("/\s\s*/"," ", $threat);
            $threat = preg_replace("/\r\n/","", $threat);
            $body .= "\r\n\r\n";
            $body .= "FILE:         " . $entry["FILE"] . "\r\n";
            $body .= "FILE_MD5:     " . $entry["FILE_MD5"] . "\r\n";
            $body .= "SEVERITY:     " . $entry["SEVERITY"] . "\r\n";
            $body .= "ENGINE:       " . $entry["ENGINE"] . "\r\n";
            $body .= "THREAT_SIG:   " . $entry["THREAT_SIG"] . "\r\n";
            $body .= "THREAT_NAME:  " . $entry["THREAT_NAME"] . "\r\n";
            $body .= "THREAT:       " . $threat . "\r\n";
            $body .= "DETAILS:      " . $entry["DETAILS"] . "\r\n";
        }

        echo $header . "\r\n" . $body;
        exit();
    }

    public static function GetDetectedThreatsReport()
    {
        self::__can_access();
        $report = new CQtrReport();
        $output = $report->GetDetectedThreats();
        echo json_encode($output);
        exit();
    }

    public static function ShowFile()
    {
        self::__can_access();
        $file   = "";
        $output = "";

        $logger = new CQtrLogger();

        if( isset( $_POST["FILE_PATH"] ) ){
            $file = ABSPATH . $_POST["FILE_PATH"];
        }

        $logger->Info("Showing file $file");

        if(!is_file($file)){
            $logger->Error("Failed to locate required file [$file]");
            exit();
        }

        //verifing that referenced file is part of the WP installation
        $real_path = realpath($file);
        if(strpos($real_path,ABSPATH,0) !== 0){
            $logger->Error("Permission denied [$real_path]");
            wp_die(__('You do not have sufficient permissions to access this page.'));         
        }

        $output = file_get_contents($file);
        echo $output;
        exit();
    } 

    public static function GetIgnoredThreatsReport()
    {
        self::__can_access();
        $ignore_list    = new CQtrIgnoreList();
        $report         = new CQtrReport( );
        $threats        = $report->Get();
        $output         = array();

        /* 
         * remove all not-ignored threats 
         */
        foreach( $threats as $threat )
        {
            /*
             * if threat is not part of the ignored list, remove it from output
             */
            if( $ignore_list->Get( $threat["FILE_MD5"], $threat["THREAT_SIG"] ) )
            {
                array_push($output,$threat);
            }
        }

        echo json_encode($output);
        exit();
    }


    public static function IgnoreThreat()
    {
        self::__can_access();
        $file   = "";
        $threat = "";

        if( isset( $_POST["FILE_MD5"] ) ){
            $file = $_POST["FILE_MD5"];
        }

        if( isset( $_POST["THREAT_SIG"] ) ){
            $threat = $_POST["THREAT_SIG"];
        }

        $ignore_list = new CQtrIgnoreList();
        if( !$ignore_list->Add($file,$threat) ){
            echo json_encode("Operation failed");
        }else if( $ignore_list->Get($file,$threat) == NULL ) {
            echo json_encode("Failed to retrieve just added threat for $file:$threat");
        }else{
            echo json_encode("Operation succeeded"); 
        }
        exit();
    }


    public static function RemoveFromIgnoreList()
    {
        self::__can_access();
        $file   = "";
        $threat = "";

        if( isset( $_POST["FILE_MD5"] ) ){
            $file = $_POST["FILE_MD5"];
        }

        if( isset( $_POST["THREAT_SIG"] ) ){
            $threat = $_POST["THREAT_SIG"];
        }

        $ignore_list = new CQtrIgnoreList();
        if( !$ignore_list->Remove($file,$threat) ){
            echo json_encode("Operation failed");
        }else{
            echo json_encode("Operation succeeded"); 
        }
        exit();
    }

    public static function CleanIgnoreList()
    {
        self::__can_access();
        $ignore_list = new CQtrIgnoreList();
        $ignore_list->Clean();
        echo json_encode("Operation succeeded");
        exit();
    }


    public static function WhiteListThreat()
    {
        self::__can_access();
        $file   = "";
        $threat = "";

        if( isset( $_POST["FILE_MD5"] ) ){
            $file = $_POST["FILE_MD5"];
        }

        if( isset( $_POST["THREAT_SIG"] ) ){
            $threat = $_POST["THREAT_SIG"];
        }

        $ignore_list = new CQtrIgnoreList();
        $white_list  = new CQtrThreatsWhiteList();
        /*
         * Remove threat from ignored list if it is there
         */
        $ignore_list->Remove($file,$threat);

        if( !$white_list->Add($file,$threat) ){
            echo json_encode("Operation failed");
        }else if( $white_list->Get($file,$threat) == NULL ) {
            echo json_encode("Failed to retrieve just added threat for $file:$threat");
        }else{
            echo json_encode("Operation succeeded for $file:$threat"); 
        }
        exit();
    }


    public static function CleanThreatsWhiteList()
    {
        self::__can_access();
        $white_list = new CQtrThreatsWhiteList();
        $white_list->Clean();
        echo json_encode("Operation succeeded");
        exit();
    }


    public static function WhiteListFile()
    {
        self::__can_access();
        $file_path  = NULL;
        $file_sig   = NULL;
        if( isset($_POST["FILE_MD5"] ) ) {
            $file_sig = trim($_POST["FILE_MD5"]);
        }
        if( isset($_POST["FILE"]) ){
            $file_path = trim( $_POST["FILE"] );
        }

        if( $file_path == NULL && $file_sig == NULL ){
            echo json_encode("provided invalid input");
            exit();
        }

        $rc = FALSE;
        $white_list = new CQtrFilesWhiteList();
        $white_list->Load();
        $error = "operation succeeded";
        if( $file_sig ){
            $rc = $white_list->AddBySig( $file_sig );
            if( !$rc ){

                if( $white_list->IsWhiteListed( $file_sig ) ){
                    $error = "$file_sig already whitelisted";
                }else{
                    $error = "$file_sig failed due to invalid input";
                }
            }
        }else{
            $rc = $white_list->AddByPath( $file_path );
            if( !$rc ){
                if( $white_list->IsWhiteListedFile( $file_path ) ){
                    $error = "$file_path already whitelisted";
                }else{
                    $error = "$file_path failed due to invalid input";
                }
            }
        }

        echo json_encode($error);
        exit();
    }


    public static function  CleanFilesWhiteList()
    {   
        self::__can_access();
        $white_list = new CQtrFilesWhiteList();
        if( $white_list->Clean() ){
            echo json_encode("Operation succeeded");
        } else {
            echo json_encode("Operation failed");
        }
        exit();
    }

}

?>
