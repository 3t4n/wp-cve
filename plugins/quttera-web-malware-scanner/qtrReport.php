<?php 
/**
 *       @file  qtrReport.php
 *      @brief  This module contains investigation report
 *
 *     @author  Quttera (qtr), contactus@quttera.com
 *
 *   @internal
 *     Created  01/14/2016
 *    Compiler  gcc/g++
 *     Company  Quttera
 *   Copyright  Copyright (c) 2016, Quttera
 *
 * This source code is released for free distribution under the terms of the
 * GNU General Public License as published by the Free Software Foundation.
 * =====================================================================================
 */

// require_once( ABSPATH . WPINC . '/ms-functions.php' );

require_once('qtrConfig.php');
require_once('qtrOptions.php');
require_once('qtrLogger.php');
require_once('qtrStats.php');
require_once('qtrIgnoreList.php');
require_once('qtrThreatsWhiteList.php');
require_once('qtrFilesWhiteList.php');

define( 'QTR_REPORT','quttera_wp_report');

class CQtrReport
{
    protected $_report;
    protected $_config;

    public function __construct( $type = NULL )
    {
        $this->_config      = new CQtrConfig();
        $this->_logger      = new CQtrLogger();
        $this->_report      = array();
        $this->_report_file = dirname(__FILE__) . DIRECTORY_SEPARATOR . QTR_REPORT . ".txt";
        $this->_ignore_list          = new CQtrIgnoreList();
        $this->_files_white_list     = new CQtrFilesWhiteList();
        $this->_threats_white_list   = new CQtrThreatsWhiteList();
        $this->_LoadReport();
 
    }

    public function AddFileReport( $engine,$severity,$file_path,$file_sig,$threat_sig, $threat, $details, $name )
    {
        $entry = array();
        $entry["ENGINE"]    = $engine;
        $entry["SEVERITY"]  = $severity;

        if( !$file_sig )
        {
            $entry["FILE_MD5"] = md5_file( $file_path );
        }
        else
        {
            $entry["FILE_MD5"] = $file_sig;
        }

        $file = substr($file_path ,strlen(ABSPATH));
        $entry["FILE"] = $file;

        $threat = preg_replace("/\s\s*/"," ", $threat);
        $threat = preg_replace("/\r\n/","", $threat);
        $threat = preg_replace("/<\?/","&#60;?", $threat);
        $threat = preg_replace("/\?>/","?&#62;", $threat);
        $threat = substr($threat,0,60) . "...";

        $entry["THREAT_NAME"]   = $name;
        $entry["THREAT_SIG"]    = $threat_sig;
        $entry["THREAT"]        = $threat;
        $entry["DETAILS"]       = $details;

        if( $this->_IsWhiteListed($entry) == FALSE ){
            array_push( $this->_report,$entry );
            $this->_StoreReport();
        }
        return TRUE; 
    }


    public function AddDbReport( $endine,$severity,$database,$table,$row,$threat_sig,$threat )
    {
        $entry = array();
        $entry["ENGINE"]    = $engine;
        $entry["SEVERITY"]  = $severity;
        $entry["DATABASE"]  = $database;
        $entry["TABLE"]     = $table;
        $entry["ROW"]       = $row;
        $entry["THREAT_SIG"] = $threat_sig;
        $threat = str_replace("\n","",$threat);
        $threat = preg_replace("/\s\s*/"," ", $threat);
        $threat = preg_replace("/\r\n/","", $threat);
        $threat = substr($threat,0,60) . "...";

        $entry["THREAT"]     = trim($threat);

        if($this->_IsWhiteListed($entry) == FALSE ){
            array_push( $this->_report, $entry );
            $this->_StoreReport();
        }
        return TRUE; 
    }

    public function GetThreat( $file_sig,$threat_sig )
    {
        $this->_LoadReport();
        $index = 0;
        foreach( $this->_report as $entry )
        {
            if( $entry["FILE_MD5"] == $file_sig && $entry["THREAT_SIG"] == $threat_sig )
            {
                return $entry;
            }
        }
        /*
         * Threat not found
         */
        return NULL;
    }

    public function RemoveThreat( $file_sig,$threat_sig )
    {
        $this->_LoadReport();
        $index = 0;
        foreach( $this->_report as $entry )
        {
            if( $entry["FILE_MD5"] == $file_sig && $entry["THREAT_SIG"] == $threat_sig )
            {
                unset($this->_report[$index]);
                $this->_StoreReport();
                return TRUE;
            }
            else
            {
                $index++;
            }
        }
        /*
         * Threat not found
         */
        return FALSE;
    }

    public function Reset(){    
        $this->_report = array();
        $this->_StoreReport();
        return TRUE;
    }


    public function GenerateMeta(){
        return $this->_GenerateHeader();
    }


    public function GetStats(){

        $stats              = new CQtrStats();
        $clean              = $stats->Total();
        $suspicious         = 0;
        $psuspicious        = 0;
        $malicious          = 0;
        $dump               = $this->GetDetectedThreats();
        $detected_files     = array();

        foreach ($dump  as $entry){

            if( array_key_exists("FILE_MD5", $entry) and 
                array_key_exists($entry["FILE_MD5"], $detected_files) ){
                /*
                 * This file already handled
                 */
                continue;
            }

            $severity = $entry["SEVERITY"];
            $sev = strtolower($severity);
            if(strpos($sev,"mal") !== FALSE ){
                $malicious += 1;
                $clean -= 1;
                if(array_key_exists("FILE_MD5", $entry)){
                    $detected_files[$entry["FILE_MD5"]] = "malicious";
                }
            }
            else if(strpos($sev,"pot") !== FALSE ){
                $psuspicious += 1;
                $clean -= 1;
                if(array_key_exists("FILE_MD5", $entry)){
                    $detected_files[$entry["FILE_MD5"]] = "psuspicious";
                }
            }
            else if(strpos($sev,"susp") !== FALSE ){
                $suspicious += 1;
                $clean -= 1;
                if(array_key_exists("FILE_MD5", $entry)){
                    $detected_files[$entry["FILE_MD5"]] = "suspicious";
                }
            }
        }

        //$this->_logger->Info("Stats clean: $clean, ps: $psuspicious, suspicious: $suspicious, malicious: $malicious");
        $stats->Clean($clean);
        $stats->Suspicious($suspicious);
        $stats->PotSuspicious($psuspicious);
        $stats->Malicious($malicious);
        return $stats;
    }


    public function GetDetectedThreats(){
        $output             = array();
        /* 
         * remove all ignored threats 
         */
        foreach($this->_report as $threat ){

            if($this->_IsWhiteListed( $threat ) == FALSE ){
                /*
                * This is not ignored threat 
                */
                array_push($output,$threat);
            }
        }

        return $output;
    }


    protected function DumpToString(){
        $header = $this->_GenerateHeader();
        $body   = $this->_GenerateBody();
        return $header . "\r\n" . $body;
    }

    public function Get(){
        return $this->_report;
    }


    public function StoreFileReport(){
        return $this->_StoreToFile();
    }

    public function Finalize(){
        $this->_StoreToFile();
        return TRUE;
    }


    /***************************************************************************
     *
     *      PROTECTED METHODS
     *
     **************************************************************************/
    protected function _LoadReport()
    {
        $body   = CQtrOptions::GetOption( QTR_REPORT );

        if( $body )
        {
            $this->_report = CQtrOptions::Unserialize( $body );

            if( !is_array( $this->_report ) ){
                /* 
                 * something gone wrong, reset report
                 */
                $this->_report = array();
            }
        }else{
            /*
             * nothing found
             */
            $this->_report = array();
        }

        return TRUE;
    }

    protected function _StoreReport(){
        /*
         * Overwrite report in file
         */
        $this->_StoreToFile();

        $body = CQtrOptions::Serialize( $this->_report );

        if ( CQtrOptions::GetOption( QTR_REPORT ) !== false ) 
        {
            $rc = CQtrOptions::UpdateOption( QTR_REPORT , $body );
        }
        else 
        {
            $deprecated = null;
            $autoload   = 'no';
            return CQtrOptions::AddOption( QTR_REPORT , $body ,$deprecated, $autoload );
        }
    }   

    protected function _StoreToFile(){

        if(!defined("QTR_DEBUG")){
            return FALSE;
        }

        $header = $this->_GenerateHeader();
        $body   = $this->_GenerateBody();
        $file   = fopen( $this->_report_file,"w");
        fwrite($file,$header);
        fwrite($file,$body);
        fflush($file);
        fclose($file);
        return TRUE;
    }

    protected function _GenerateHeader(){
        /*
         * Loads statistics
         */
        $stats      = $this->GetStats(); 
        $site       = $this->_GetCurrentSite();
        $timestr    = date('Y-m-d H:i'); 
        $itimestr   = date('Y-m-d H:i', $stats->StartTime());
        $total      = $stats->Total();
        $clean      = $stats->Clean();
        $susp       = $stats->Suspicious();
        $psusp      = $stats->PotSuspicious();
        $malicious  = $stats->Malicious();
        $header     = <<<HEADER

=======================================================================
    Quttera Web Malware Scanner plugin for WordPress
    Website Malware Scan Report 
    
    Scanned Website:        $site    
    Scan type:              Internal
    Report generation time: $timestr

    Scan launch time:       $itimestr    
    Scanned files:          $total
    Clean:                  $clean
    Potentially Suspicious: $psusp
    Suspicious:             $susp
    Malicious:              $malicious
    
    © 2024 Quttera Ltd. All rights reserved.
    For any questions about this report: support@quttera.com
=======================================================================
\r\n
HEADER;
        return $header;
    }

    protected function _GenerateBody()
    {
        $body = "";
        foreach ( $this->_report  as $entry){
            $threat = $entry["THREAT"];

            $threat = preg_replace("/\s\s*/"," ", $threat);
            $threat = preg_replace("/\r\n/","", $threat);
            $threat = substr($threat,0,60) . "...";
            $body .= "\r\n\r\n";
            $body .= "FILE:         " . $entry["FILE"] . "\r\n";
            $body .= "FILE_MD5:     " . $entry["FILE_MD5"] . "\r\n";
            $body .= "SEVERITY:     " . $entry["SEVERITY"] . "\r\n";
            $body .= "ENGINE:       " . $entry["ENGINE"] . "\r\n";
            $body .= "THREAT_SIG:   " . $entry["THREAT_SIG"] . "\r\n";
            $body .= "THREAT_NAME:  " . $entry["THREAT_NAME"] . "\r\n";
            $body .= "THREAT:       " . $threat . "\r\n";
            $body .= "DETAILS:      " . $entry["DETAILS"] . "\r\n"; 

            //foreach( $entry as $key => $value ){
            //    $body .= sprintf("%s: %s\r\n",$key,$value);
            //}
        }

        return $body;
    }

    protected function _GetCurrentSite(){

        if(!function_exists('get_site_url') ){
            /*
             * running outside of WP
             */
            return "example.com";
        }else{
            //return get_current_site()->site_name;
            return get_site_url();
        }
    }


    public function _IsWhiteListed( $threat ){
        if($this->_files_white_list->IsLoaded() == FALSE ){
            $this->_files_white_list->Load();
        }

        if( $this->_ignore_list->Get( $threat["FILE_MD5"], $threat["THREAT_SIG"] ) != NULL ){
            return TRUE;
        }

        if( $this->_threats_white_list->Get( $threat["FILE_MD5"], $threat["THREAT_SIG"] ) != NULL ){
            return TRUE;
        }

        if( $this->_files_white_list->IsWhiteListed( $threat["FILE_MD5"] ) ){
            return TRUE;
        }
        
        return FALSE;
    }

}


?>
