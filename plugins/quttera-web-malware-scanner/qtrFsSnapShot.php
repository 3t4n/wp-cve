<?php
/**
 *       @file  qtrFsSnapShot.php
 *      @brief  This file contains implementation of file system snapshot for further use
 *
  *     @author  Quttera (qtr), contactus@quttera.com
 *
 *   @internal
 *     Created  03/09/18
 *     Company  Quttera
 *   Copyright  Copyright (c) 2018, Quttera
 *
 * This source code is released for free distribution under the terms of the
 * GNU General Public License as published by the Free Software Foundation.
 * =====================================================================================
 */


require_once('qtrLogger.php');

class CQtrFsSnapShot
{

    function __construct() {
        $this->_fsmap = array();
    }


    /**
     * @brief   returns next file according to FS iteration order
     * @return  NULL if nothing to return or path to file 
     */
    public function Pop(){
        /* return NULL if array is empty */
        return array_shift($this->_fsmap);
    
    }


    /**
     * @brief       appends provided file path at the end of snapshot
     * @param[in]   $path - file path to add
     * @return      return FALSE on failure or number of snapshot elements on success      
     */
    public function Push($path){
        if(is_file($path)){
			/* add file to begining of the list */
        	array_unshift($this->_fsmap,$path);
			return TRUE;
        }else if(is_dir($path)){
			/* add directory at the end of the list */
	        array_push($this->_fsmap,$path);
			return TRUE;
		}
    }


    /**
     * @brief   returns number of elements in file system
     * @return  number of elements in file system snapshot 
     */
    public function FilesCount(){
        return count($this->_fsmap);
    }


	/**
	 * @brief  		iterates over provided path and builds file system snapshot (all files added in one run)
	 * @param[in] 	$path - directory path to iterate
	 * @return  	on failure returns FALSE, on success TRUE	
	 */
    public function BuildSnapShot($path){
        if(!is_dir($path)){
            return FALSE;
        }

        $this->_fsmap = array();
		$iter = new RecursiveIteratorIterator(
    		new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS),
		    RecursiveIteratorIterator::SELF_FIRST,
		    RecursiveIteratorIterator::CATCH_GET_CHILD // Ignore "Permission denied"
		);

		foreach ($iter as $fpath => $object) {
			if ($object->isFile()) {
				/* adds files only */
				$this->Push(realpath($fpath));
			}
		}	

		return TRUE;
    }

	
	/**
	 * @brief  		converts snapshot to JSON and (if path provided store it in a file)
	 * @param[$in] 	$path to file to store the content
	 * @return  	if file path provided return TRUE or FALSE, if not returns JSON dump as a string
	 */
	public function ToString($path = NULL){
        //$dump = json_encode($this->_fsmap, JSON_UNESCAPED_UNICODE );
        $dump = serialize($this->_fsmap);
		if( $path == NULL ){
			/*
			 * return output as string
			 */
			return $dump;
		}else{
			/* 
			 * store in a file
             */
			if( is_file($path)){
				if(unlink($path) == FALSE){
					/*
                     * Failed to remove old file
  					 */
					return FALSE;
				}
			}
			$handle = fopen($path, "w");
			if( $handle == FALSE ){
				return FALSE;
			}
			fwrite($handle,$dump);
			fflush($handle);
			fclose($handle);
			return TRUE;
		}
	}


	/**
	 * @brief  	loads snapshot content from provided JSON dump
	 * @param[in]  $input - either path to load JSON or JSON string to parse
	 * @return 	if input points to file path, this method will read it and try to convert to snapshot
	 */
	public function FromString($input){
		$dump = NULL;
		if(is_file($input)){
			/* load from dump file */
			$handle = fopen($input, "r");
			if( $handle == FALSE ){
				return FALSE;
			}
			$dump = fread($handle, filesize($input));
			fclose($handle);
		}else{
			/* input provided as JSON string */
			$dump = $input;
		}

        //$list = json_decode($dump, JSON_INVALID_UTF8_IGNORE);
        $list = unserialize($dump);
		if( !is_array($list)){
			return FALSE;
		}
		
		$this->_fsmap = array();
		foreach ($list as $fname) {
			$this->Push($fname);
		}
		return TRUE;
	}


	/**
	 * @brief	List content of provided directory (not recursively) and returns list of directories and files
	 * @param[in]	$path - directory path to list 
	 * @return  	array of found file system objects
	 */
	public function ListPath($path){
		if(!is_dir($path)){
			return array();
		}
   		$result = array(); 
		$cdir = scandir($path); 

        if( substr($path, -1) != DIRECTORY_SEPARATOR ){
            $path .= DIRECTORY_SEPARATOR;
        }

   		foreach ($cdir as $key => $value) { 
      		if (!in_array($value,array(".",".."))) { 
	            $result[] = $path . $value; 
    	     }
		}
		return $result;
   	} 

	
	/**
	 * @brief   populate snapshot content with files/directories from the provided input
	 * @param[in]	$path - directory to list
	 * @return  	number of elements in snapshot
	 */
	public function Populate($path){
		$items = $this->ListPath($path);
		foreach($items as $fpath){
 			/* 
			 * push adds files to begin of list 
             * and directories at the end of list
			 */
			$this->Push($fpath);
		}
		return $this->FilesCount();
	} 
}

/*
// How to use
 
$fs = new CQtrFsSnapShot();
$fs->Populate($argv[1]);
while(($item = $fs->Pop()) != NULL ){
	
	if(rand() % 2 ){
		$json = $fs->ToJSON();
		$fs->_fsmap = array();
		$fs->FromJSON($json);
	}

	if(is_file($item)){
		print $item . "\n";
	}else{
		// populate snapshot with more info 
		$fs->Populate($item);
	}
}
*/
?>
