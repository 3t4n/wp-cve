<?php

/**
 *       @file  qtrFilesScanner.php
 *      @brief  This module contains implementation of file scanner
 *
 *     @author  Quttera (qtr), contactus@quttera.com
 *
 *   @internal
 *     Created  01/16/2016
 *    Compiler  gcc/g++
 *     Company  Quttera
 *   Copyright  Copyright (c) 2016, Quttera
 *
 * This source code is released for free distribution under the terms of the
 * GNU General Public License as published by the Free Software Foundation.
 * =====================================================================================
 */


require_once('qtrOptions.php');
require_once('qtrConfig.php');
require_once('qtrLogger.php');
require_once('qtrPatternsDb.php');
require_once('qtrReport.php');
require_once('qtrIgnoreList.php');
require_once('qtrStats.php');
require_once('qtrExecSemaphore.php');
require_once('qtrScanLock.php');
require_once('qtrFilesWhiteList.php');
require_once('qtrThreatsWhiteList.php');
require_once('qtrMimetype.php');
require_once('qtrUtils.php');


include( ABSPATH . 'wp-includes/version.php' );

@ini_set('max_execution_time', 30000 );
@ini_set('max_input_time', 30000 );
@ini_set('memory_limit', '2024M');
@set_time_limit(30000);

define('QTR_SCANNER_MAX_FILE_SIZE', 256*1024);

class CQtrFilesScanner
{
    protected $_logger;
    protected $_patterns_db;
    protected $_report;
    protected $_config;
    protected $_stats;
    protected $_exec_sem;
    protected $_files_white_list; 
    protected $_heuristic;

    public function __construct($heuristic=false)
    {
        $this->_logger              = new CQtrLogger();
        $this->_patterns_db         = new CQtrPatternsDatabase();
        $this->_report              = new CQtrReport();
        $this->_config              = new CQtrConfig(); 
        $this->_stats               = new CQtrStats();
        $this->_ignore_list         = new CQtrIgnoreList();
        $this->_files_white_list    = new CQtrFilesWhiteList();
        $this->_mime_filer          = new CQtrMimetype(); 
        $this->_last_report_dump    = time();
        $this->_core_files_map      = array();
        $this->_checksum_available  = NULL;
        $this->_heuristic           = $heuristic;
    }


    public function Initialize( $args = NULL )
    {
        $dbpath = dirname(__FILE__) . DIRECTORY_SEPARATOR . $this->_config->PatternsDbName();
        if( is_file($dbpath))
        {
            $rc = $this->_patterns_db->Load($dbpath);
            if( !$rc ){
                $this->_logger->Error(sprintf("Failed to load pattern database, File %s not found", $dbpath));
            }else{
                $this->_logger->Info(sprintf("Patterns database %s loaded successfully", $dbpath));
            }
        }else{
            $this->_logger->Error( "Failed to locate name of patterns database" );
        }

        $this->_files_white_list->Load();
        return TRUE;
    }

    public function Finalize( $argv = NULL )
    {
        $this->_report->Finalize();
        return TRUE;
    }


    public function Scan( $path )
    {
        @ini_set('max_execution_time', 30000 );
        @ini_set('max_input_time', 30000 );
        @ini_set('memory_limit', '2024M');
        @set_time_limit(30000);

        $this->_report->Reset ( );
        //$this->_ignore_list->Clean( );

        $exec_sem = new CQtrExecSem();
        $exec_sem->ScannerPid( getmypid() );
        $this->_stats->Reset();
        
        $this->_logger->Info(sprintf("Start investigation of %s", $path));

        if( is_dir($path))
        {
            $this->ScanDirectory($path);
        }
        else if( is_file( $path ))
        {
            $this->ScanFile($path);
        }
        else
        {
            $this->_logger->Error( "Provided invalid path" ); 
        }
        
        $this->_logger->Info( sprintf("Investigation of %s done",$path));
            
        $exec_sem = new CQtrExecSem();
        $exec_sem->ShouldStop('DONE');
        CQtrScanLock::Release();
        return TRUE;
    }

    public function ScanWordPress( $root_path )
    {
        $this->_report->Reset ( );
        //$this->_ignore_list->Clean( );

        $exec_sem = new CQtrExecSem();
        $exec_sem->ScannerPid( getmypid() );
        $this->_stats->Reset();
        

        /*
         * 1 - Scan wp-includes dir
         * 2 - Scan wp-admin dir
         * 3 - Scan themes dir
         * 3 - Scan root dir not-recursive
         * 4 - Scan root sub-dirs
         */
        $includes_path = $root_path . DIRECTORY_SEPARATOR . "wp-includes";
        $admin_path = $root_path . DIRECTORY_SEPARATOR . "wp-admin";
        $content_path = $root_path . DIRECTORY_SEPARATOR . "wp-content";
        $themes_path = $content_path . DIRECTORY_SEPARATOR . "themes";

        if( is_dir($includes_path)){
            $this->_logger->Info(sprintf("Start investigation of %s directory", $includes_path));
            $this->_ScanDirectory($includes_path);
        }else{
            $this->_logger->Info(sprintf("Failed to locate wp-includes dir at", $root_path));
        }

        if( is_dir($admin_path)){
            $this->_logger->Info(sprintf("Start investigation of %s directory", $admin_path));
            $this->_ScanDirectory($admin_path);
        }else{
            $this->_logger->Info(sprintf("Failed to locate wp-admin dir at", $root_path));
        }

        if( is_dir($themes_path)){
            $this->_logger->Info(sprintf("Start investigation of %s directory", $themes_path));
            $this->_ScanDirectory($themes_path);
        }else{
            $this->_logger->Info(sprintf("Failed to locate themes dir at", $root_path));
        }

        $this->_logger->Info(sprintf("Start investigation of %s directory", $root_path));

        $this->_ScanDirectory($root_path, false /*not recursive*/);

        $this->_ScanDirectory($root_path, true /*recursive*/);
        
        $this->_logger->Info( sprintf("Investigation of %s done",$root_path));
            
        $exec_sem = new CQtrExecSem();
        $exec_sem->ShouldStop('DONE');
        CQtrScanLock::Release();
        return TRUE;
    }


    public function ScanDirectory($path, $recursive=true)
    {
        //@set_time_limit(0);

        $this->_logger->Info(
            sprintf("Start investigation of directory %s", $path));

        if($this->_files_white_list->IsIgnored( $path ))
        {
            $this->_logger->Info(sprintf("Directory %s is ignored", $path));
            return NULL;
        }

        $files = scandir($path);

        $this->_logger->Info(
            sprintf("Directory %s contains %d elements", $path,count($files)));

        foreach($files as $file )
        {
            if( $file == "." or $file == ".."){
                continue;
            }

            $curr_path = $path . DIRECTORY_SEPARATOR .$file;

            if( $recursive and is_dir( $curr_path )){

                $this->ScanDirectory( $curr_path );

            } else if( is_file( $curr_path ) ) {

                $this->ScanFile( $curr_path );
            } else {
                $this->_logger->Info(sprintf("Skipping %s", $curr_path ));
            }
        }

        $this->_logger->Info(sprintf("Investigation of %s done", $path));
    }


    public function ScanFile($path)
    {
        if(!is_file($path)){
            return NULL;
        }
        
        $this->_logger->Info("Starting scan of $path");

        if( strpos($path,"runtime.log") or strpos($path,"quttera_wp_report.txt")){
            /*
             * skip investigation generated report
             */
            $this->_stats->IncClean();
            $this->_logger->Info(sprintf("%s is clean", $path));
            return FALSE;
        }
 
        if($this->_files_white_list->IsIgnored( $path ))
        {
            /*
             * This is ignored file
             */
            $this->_stats->IncClean();
            $this->_logger->Info(sprintf("%s is ignored", $path));
            return FALSE;
        }

        $fmd5 = md5_file($path);
        if( $this->_files_white_list->IsWhiteListed( $fmd5 ) ){
            /*
             * This is whitelisted file
             */
            $this->_stats->IncClean();
            $this->_logger->Info(sprintf("%s is clean", $path));
            return FALSE;
        }

        if($this->_ShouldTestCoreIntegrity())
        {
            $core_dirs = array(
                ABSPATH . "wp-content/themes/twentysixteen",
                ABSPATH . "wp-content/themes/twentyseventeen",
                ABSPATH . "wp-content/themes/twentyfifteen",
                ABSPATH . "wp-content/plugins/akismet",
                ABSPATH . "wp-includes",
                ABSPATH . "wp-admin");
            /*
             * Check if core file is modified
            */
            $coremd5 = $this->_IsCoreFile($path);
            if($coremd5 != NULL){
                $this->_logger->Info("$path is core file");
                /*
                * This is core file
                */
                if($coremd5 != $fmd5 ){                
                    $this->_report->AddFileReport (
                        "fscanner", 
                        "enSuspiciousThreatType",
                        $path,
                        $coremd5,   //md5 of original core file
                        $fmd5,      //md5 of the changed core file
                        "Modified core file", 
                        "Detected modified core file",
                        "Heur.CoreFile.gen"
                    );
                
                    $this->_stats->IncSusp();
                    $this->_logger->Info(sprintf("Detected modified core file %s", $path));
                    return TRUE;
                }else{
                    $this->_stats->IncClean();
                    $this->_logger->Info(sprintf("%s has not been modified.", $path));
                    return FALSE;
                }
            }
            /*
             * check if this alien file in WP core directory
             */
            foreach( $core_dirs as $core_dir )
            {
                if(strpos($path,$core_dir) !== FALSE ){
                    /*
                     * This file locats in WP core directory but it is not WP core file
                     */
                    $this->_report->AddFileReport (
                        "fscanner", 
                        "enSuspiciousThreatType",
                        $path,
                        md5_file( $path ),
                        $fmd5, 
                        "Unknown file in core directory", 
                        "Detected unknown file in core directory",
                        "Heur.AlienFile.gen"
                    );
                
                    $this->_logger->Info(sprintf("Detected unknown file %s in core directory", $path));
                    return TRUE;
                }
            }

        } //should test core files integrity

        /*
         * If this is not text file return
         */
        if( strcmp($this->_mime_filer->CheckMimeType($path), "textfile") !== 0 )
        {
            $this->_stats->IncClean();
            $this->_logger->Info(sprintf("BIN file [%s] is clean", $path));
            return FALSE;
        }
   
        $size = filesize($path);
        //$this->_logger->Info("[$path] size $size bytes");
        if( $size > QTR_SCANNER_MAX_FILE_SIZE){
            $this->_stats->IncClean();
            $this->_logger->Info(sprintf("[%s] is clean", $path));
            return FALSE;
        }

        /*
         * matches is map between pattern (CQtrPattern) and found match
         */
        $matches = $this->_patterns_db->Scan($path, $this->_heuristic);
        /*
         * remove wordpress location part for logging purposes
         */
        if( $matches ) {
            $logged = FALSE;
            foreach($matches as $match ){
                $pattern = $match[0];
                $md5 = md5($match[1]);

                /*
                if( $this->_threats_white_list->Get($fmd5,$md5) != NULL ){
                    // This threat whitelisted
                    $this->_logger->Info("$fmd5:$md5 is whitelisted");
                    continue;
                }*/

                $this->_report->AddFileReport (
                    "fscanner", 
                    $pattern->severity(),
                    $path,
                    md5_file($path),
                    $md5, 
                    $match[1], 
                    $pattern->details(),
                    $pattern->name()
                );

                $this->_report->StoreFileReport();
                $logged = TRUE;
                $this->_logger->Info( sprintf( "TXT %s is %s", $path,$pattern->severity()) );
                $this->_stats->Increment($pattern->severity());
            }

            if( $logged == FALSE ){
                /*
                 * all threats from this file where whitelisted
                 * report this file as clean
                 */
                $this->_stats->IncClean();
                $this->_logger->Info(sprintf("TXT %s is clean", $path));
                return FALSE;
            }
            return TRUE;

        }else{
            $this->_stats->IncClean();
            $this->_logger->Info(sprintf("TXT %s is clean", $path));
            return FALSE;
        }
    }

	public function IsIgnored($path)
	{
        return $this->_files_white_list->IsIgnored( $path );
	}

    /*************************************************************************
     *          PROTECTED METHODS
     ************************************************************************/ 
    protected function _ScanDirectory($path, $recursive=true)
    {
        @set_time_limit(0);

        $this->_logger->Info(
            sprintf("Start investigation of directory %s", $path));

        if($this->_files_white_list->IsIgnored( $path ))
        {
            $this->_logger->Info(sprintf("Directory %s is ignored", $path));
            return NULL;
        }

        $files = scandir($path);

        $this->_logger->Info(
            sprintf("Directory %s contains %d elements", $path,count($files)));

        foreach($files as $file )
        {
            if( $file == "." or $file == ".."){
                continue;
            }

            if( $this->_ShouldTerminate() )
            {
                $this->_logger->Info(
                    sprintf("%s noticed termination flag. Terminating.",$path));
                return NULL;
            }

            $curr_path = $path . DIRECTORY_SEPARATOR .$file;

            if( $recursive and is_dir( $curr_path ))
            {
                $this->_ScanDirectory( $curr_path );
            }
            else if( is_file( $curr_path ) )
            {
                $this->_ScanFile( $curr_path );
            }
            else
            {
                $this->_logger->Info(sprintf("Skipping %s", $curr_path ));
            }
        }

        $this->_logger->Info(sprintf("Investigation of %s done", $path));
    }


    protected function _ScanFile($path)
    {
        @set_time_limit(0);
        $core_dirs = array(
            ABSPATH . "wp-content/themes/twentysixteen",
            ABSPATH . "wp-content/themes/twentyseventeen",
            ABSPATH . "wp-content/themes/twentyfifteen",
            ABSPATH . "wp-content/plugins/akismet",
            ABSPATH . "wp-includes",
            ABSPATH . "wp-admin");

        if( $this->_last_report_dump + 30 < time() ){
            /*
             * Regenerate report to take last changes
             */
            $this->_report->StoreFileReport();
            $this->_last_report_dump = time();
        }

        $this->_logger->Info(sprintf("Starting scan of %s", $path ));

        if( $this->_ShouldTerminate() )
        {
            /*
             * someone raised termination flag
             */
            return NULL;
        }

        if( strpos($path,"quttera_wp_report") ){
            /*
             * skip investigation generated report
             */
            $this->_stats->IncClean();
            $this->_logger->Info(sprintf("%s is clean", $path));
            return FALSE;
        }

        if( !is_file($path)){
            $this->_logger->Error(sprintf("Path %s is not a file", $path));
            return NULL;
        }

        if($this->_files_white_list->IsIgnored( $path ))
        {
            /*
             * This is ignored file
             */
            $this->_stats->IncClean();
            $this->_logger->Info(sprintf("%s is ignored", $path));
            return FALSE;
        }

        $fmd5 = md5_file($path);
        
        if( $this->_files_white_list->IsWhiteListed( $fmd5 ) ){
            /*
             * This is whitelisted file
             */
            $this->_stats->IncClean();
            $this->_logger->Info(sprintf("%s is clean", $path));
            return FALSE;
        }

        /*
         * Check if core file is modified
         */
        $coremd5 = $this->_IsCoreFile($path);
        if($coremd5 != NULL){
            $this->_logger->Info("$path is core file");
            /*
             * This is core file
             */
            if($coremd5 != $fmd5 ){                
                $this->_report->AddFileReport (
                    "fscanner", 
                    "enSuspiciousThreatType",
                    $path,
                    md5_file($path),
                    $fmd5, 
                    "Modified core file", 
                    "Detected modified core file",
                    "Heur.CoreFile.gen"
                );
                
                $this->_logger->Info(sprintf("Detected modified core file %s", $path));
                return TRUE;
            }
        }

        /*
         * check if this alien file in WP core directory
         */
        foreach( $core_dirs as $core_dir ){
            if(strpos($path,$core_dir) !== FALSE ){
                /*
                 * This file locats in WP core directory but it is not WP core file
                 */
                $this->_report->AddFileReport (
                    "fscanner", 
                    "enSuspiciousThreatType",
                    $path,
                    md5_file($path),
                    $fmd5, 
                    "Unknown file in core directory", 
                    "Detected unknown file in core directory",
                    "Heur.AlienFile.gen"
                );
                
                $this->_logger->Info(sprintf("Detected unknown file %s in core directory", $path));
                return TRUE;
            }
        }
        /*
         * If this is not text file return
         */
        if( strcmp($this->_mime_filer->CheckMimeType($path), "textfile") !== 0 )
        {
            $this->_stats->IncClean();
            $this->_logger->Info(sprintf("BIN file [%s] is clean", $path));
            return FALSE;
        }
   
        /*
         * matches is map between pattern (CQtrPattern) and found match
         */
        $matches = $this->_patterns_db->Scan($path, $this->_heuristic);
        /*
         * remove wordpress location part for logging purposes
         */
        if( $matches ) {
            $logged = FALSE;
            foreach($matches as $match ){
                $pattern = $match[0];
                $md5 = md5($match[1]); 
                $this->_report->AddFileReport (
                    "fscanner", 
                    $pattern->severity(),
                    $path,
                    md5_file($path),
                    $md5, 
                    $match[1], 
                    $pattern->details(),
                    $pattern->name()
                );

                $logged = TRUE;
                $this->_logger->Info( sprintf( "TXT %s is %s", $path,$pattern->severity()) );
                $this->_stats->Increment($pattern->severity());
            }

            if( $logged == FALSE ){
                /*
                 * all threats from this file where whitelisted
                 * report this file as clean
                 */
                $this->_stats->IncClean();
                $this->_logger->Info(sprintf("TXT %s is clean", $path));
                return FALSE;
            }
            return TRUE;

        }else{
            $this->_stats->IncClean();
            $this->_logger->Info(sprintf("TXT %s is clean", $path));
            return FALSE;
        }
    }


    private function _IsCoreFile($path){
        if(count($this->_core_files_map) == 0 ){
            $this->_ReloadCoreMap();
        }

        if(array_key_exists($path, $this->_core_files_map)){
            /*
             * return checksum for this core file
             */
            return $this->_core_files_map[$path];
        }

        return NULL;
    }


    private function _ReloadCoreMap(){
        global $wp_version, $wp_local_package;
        $this->_core_files_map = array();
        $wp_locale = isset( $wp_local_package ) ? $wp_local_package : 'en_US';
        $this->_logger->Info("WP version: $wp_version WP local: $wp_locale");

        //$apiurl = 'https://api.wordpress.org/core/checksums/1.0/?version=' . $wp_version . '&locale=' .  $wp_locale;
        $apiurl = $this->_GetChecksumUrl($wp_version, $wp_locale);

        $checksums_data = CQtrUtils::GetUrlContent($apiurl);

        if(!$checksums_data){
            $this->_logger->Error("Failed to retrieve core files checksum. Skip investigation");
            $this->_core_files_map = array();
            return FALSE;
        }
          
        $map = json_decode($checksums_data);
        if(!$map or $map->checksums === FALSE or (is_array($map->checksums) == FALSE and is_object($map->checksums) == FALSE))
	    {
            $this->_logger->Error("Cannot decode core files map. Invalid checksum data [" . gettype($map->checksums) . "]");
            $this->_core_files_map = array();
            return FALSE;
        }
           
        $checksums = $map->checksums;

	#848856
        if( is_array($checksums) ){ $this->_logger->Info(sprintf("Loaded %d core files", count($checksums))); }

        foreach( $checksums as $file => $checksum ){
            $file_path = ABSPATH . $file;
            $this->_core_files_map[$file_path] = $checksum;
            //$this->_logger->Info("$file_path <==> $checksum added to core files map");
        }

        $this->_logger->Info(sprintf("Stored %d core hashes", count($this->_core_files_map)));
    }

    private function _GetChecksumUrl( $version, $locale ) {
        $url = 'http://api.wordpress.org/core/checksums/1.0/?' . http_build_query( compact( 'version', 'locale' ), null, '&' );
        return $url;
    }

    private function _ShouldTestCoreIntegrity()
    {
        if($this->_checksum_available !== NULL){
            return $this->_checksum_available;
        }

        if(count($this->_core_files_map) > 0){
            $this->_checksum_available = TRUE;
            return TRUE;
        }
        //
        //_core_files_map is empty, try to reload it
        //
        $this->_ReloadCoreMap();
        if(count($this->_core_files_map) > 0){
            $this->_checksum_available = TRUE;
            return TRUE;
        }
       
        $this->_checksum_available = FALSE;
        return FALSE;
    }

    public function _ShouldTerminate()
    {
        $rc = CQtrScanLock::IsLocked();
        if( $rc ){
            #$this->_logger->Info("ScanLock is set. Continuing.");
            return FALSE;
        }else{
            #$this->_logger->Info("ScanLock is missing. Terminating.");
            return TRUE;
        }
    }
}


?>
