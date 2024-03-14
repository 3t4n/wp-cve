<?php
/**
 *       @file  qtrPatternsDb.php
 *      @brief  This function contain patterns database implementation
 *
 *
 *     @author  Quttera (qtr), contactus@quttera.com
 *
 *   @internal
 *     Created  01/15/2016
 *    Compiler  gcc/g++
 *     Company  Quttera
 *   Copyright  Copyright (c) 2016, Quttera
 *
 * This source code is released for free distribution under the terms of the
 * GNU General Public License as published by the Free Software Foundation.
 * =====================================================================================
 */

if(!defined("CLEANUP_OPERATION_UNDEF")){
    define("CLEANUP_OPERATION_UNDEF",0);
    define("CLEANUP_OPERATION_CURE",1);
    define("CLEANUP_OPERATION_QUARANTINE",2);
}

class CQtrPattern
{
    protected $_severity;
    protected $_pattern;
    protected $_details;
    protected $_name;
    protected $_curable;

    public function __construct($severity,$pattern,$details,$name, $curable=0){
        $this->_severity = $severity;
        $this->_pattern  = $pattern;
        $this->_details  = $details;
        $this->_name     = $name;
        $this->_curable  = $curable;
    }

    public function severity(){
        return $this->_severity;
    }

    public function pattern(){
        return $this->_pattern;
    }

    public function details(){
        return $this->_details;
    }

    public function name(){
        return $this->_name;
    }

    public function is_curable(){
        return ($this->_curable > 0)?(true):(false);
    }

    public function find_match($str)
    {
        $matches = array();
        try 
        {
            $match = preg_match("/" . $this->_pattern . "/m", $str, $group);

            if ($match > 0) 
            {
                array_push($matches, array($this,$group[0]));
            }
        }
        catch (Exception $e) 
        {
            //print "Error in" . $e->getMessage();
        }

        if( count($matches) == 0 )
        {
            return NULL;
        }

        return $matches;
    }
}



class CQtrPatternsDatabase
{
    protected $_database;

    public function __construct()
    {
        $this->_database = array();
    }

    public function Load($path)
    {
        if(!is_file($path)){
            return FALSE;
        }
        $file = fopen($path,"r");
        if( !$file ){
            return FALSE;
        }
        $body   = fread($file,filesize($path));
        fclose($file);
        $step1  = base64_decode($body);
        $step2  = str_rot13($step1);
        $patterns = json_decode($step2);
        foreach($patterns  as $entry ){
            $pattern = new CQtrPattern( 
                $entry[0],  /* severity */
                $entry[1],  /* pattern  */
                $entry[2],  /* details  */
                $entry[3],  /* name     */
                $entry[4]   /* curable  */
            );
        
            array_push($this->_database, $pattern );
        }

        return TRUE;
    }

    public function Scan($file_path, $heuristic=false)
    {
        $matches = array();

        if( !is_file($file_path)){
            return NULL;
        }

        $file = fopen($file_path,"r");
        if( !$file ){
            return NULL;
        }

        if( filesize( $file_path ) <= 0 ){
            return NULL;
        }

        $body = fread($file,filesize($file_path));
        fclose($file);
        foreach( $this->_database as $pattern ){

            if($heuristic == false and $pattern->is_curable() == false ){
                /*
                 * this is heuristic pattern, skipping 
                 */
                continue;
            }

            $match = $pattern->find_match($body);
            if( $match != NULL ){
                $matches = array_merge($matches, $match);
            }
        }

        if( count($matches) == 0 ){
            return NULL;
        }

        return $matches;
    }
}


?>
