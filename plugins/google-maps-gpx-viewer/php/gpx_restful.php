<?php
/*
restful_gpx.php, V 1.01, altm, 22.11.2013
Author: ATLsoft, Bernd Altmeier
Author URI: http://www.atlsoft.de
released under GNU General Public License
*/
if ( !defined('ABSPATH'))
    die('You are not allowed to call this page directly.');
if(!class_exists('gpx_db_REST')){
    class gpx_db_REST{
        // define class variabels
         var  $_callback;
         var  $_count;
         var  $_output; 
         var  $_format;                 
         var  $_restful;                 
         var  $_bounds;                 
         var  $_post_id;                 
		/**
		 * PHP4 compatibility layer for calling the PHP5 constructor.
		 */
		function gpx_db_REST() {
			return $this->__construct();
		}

		/**
		 * 	 automate everything
		 * 
		 */ 
		function __construct() {
            $this->_output = array(); // setup the output for json
            $this->_bounds = array(); // setup the output for json
            $this->_callback = false;  // set callback to false just incase a jsonp callback request is not made                      
            $this->_format = 'json'; // default to json replies, will be changed if callback used
            if( $this->check_url_vars() ){
                $this->save_url_vars();
            }
			if ($this->check_request())
				$this->send_output();
        }      
         
         function check_url_vars(){
            if(!empty($_GET)){ // check to see if the GET variables are set at all, this could more specific if wanted. 
                return true;
            }else{
                return false;
            }
        }
         
         function sanitize_vars($var){ // clean nastie input
            return strip_tags(html_entity_decode(urldecode($var)));        
        }
         
         function save_url_vars(){
			if(isset($_GET['callback']) ){
				$this->_format = 'jsonp'; // as there is a callback, setting the output to be jsonp
				$this->_callback = $this->sanitize_vars($_GET['callback']); // defining the output
			}
			if(isset($_GET['count']) ){
				$this->_count = $this->sanitize_vars($_GET['count']); 
			}
			if(isset($_GET['restful']) ){ // check if we have a restful request
				$this->_restful = $this->sanitize_vars($_GET['restful']); 
				if(isset($_GET['neLat']) ){
					$this->_bounds[] = $this->sanitize_vars($_GET['neLat']);
				}
				if(isset($_GET['neLon']) ){
					$this->_bounds[] = $this->sanitize_vars($_GET['neLon']);
				}
				if(isset($_GET['swLat']) ){
					$this->_bounds[] = $this->sanitize_vars($_GET['swLat']);
				}
				if(isset($_GET['swLon']) ){
					$this->_bounds[] = $this->sanitize_vars($_GET['swLon']);
				}
				if(isset($_GET['post_id']) ){
					$this->_post_id = $this->sanitize_vars($_GET['post_id']);
				}
			}
        }

         function error($type, $errorMessage){
            $this->_output['status'] = $type; // message value
            $this->_output['data'] = $errorMessage; // message text
            $this->_format = 'jsonp'; // setting to jsonp so that we can force a callback to a error handler
            $this->_callback = 'errorReply';  // will send errors back to errorReply function.
        }
        
         function check_request(){
			if($this->_restful){
				$postRest = false;
				if($this->_restful == "gpx_rest_bounds")
					$tracks = post_get_pois_bounds($this->_bounds);
				else if($this->_restful == "gpx_rest_post")	
					$tracks = post_get_all($this->_post_id);
				else if($this->_restful == "gpx_rest_all_post_title")	{
					$tracks = gpx_rest_all_post_title();
					$postRest = true;
				}
				else if($this->_restful == "gpx_rest_all")	
					$tracks = post_get_pois_all();
					
				$array_out = array();  // setup an array to save the results    
				if($postRest) {
					$cmp = -1;
					for ($i = 0; $i < count($tracks); $i++) {
						if($cmp != $tracks[$i]->ID){
							$array_out[] = array(   // add a new array set
								'post_id'	=> $tracks[$i]->ID,// the icon type e.g hiking, biking, riding
								'item_type'	=> $tracks[$i]->item_type,   
								'description' 	=> $tracks[$i]->description,
								'post_title'	=> $tracks[$i]->post_title   
							);      
						}    
					$cmp = $tracks[$i]->ID;
					}
				} else {				
					for ($i = 0; $i < count($tracks); $i++) {
						$array_out[] = array(   // add a new array set
							'type'	=> $tracks[$i]->item_type,// the icon type e.g hiking, biking, riding
							'title'	=> $tracks[$i]->item_name,
							'description'	=> $tracks[$i]->description,
							'lat' 	=> $tracks[$i]->lat, 
							'lng'  	=> $tracks[$i]->lng, 
							'url' 	=> $tracks[$i]->item_url  // the location url    
						);                  
					}
				}	
				$this->_output['status'] = '200'; // set a good status
                $this->_output['data'] = $array_out; // add the array to the data ready to json encode
				return true;
			} else 
				return false;
        }
         
		 function send_output(){
            header("Content-type: application/json"); // set content type to be json        
 
			switch($this->_format){
            case 'json':
                echo json_encode($this->_output);  // if json, echo json encoded data to buffer
                break;
            case 'jsonp':
                echo $this->_callback."(".json_encode($this->_output).");";    // if jsonp, echo json encode with callback to buffer            
                break;
            }
			exit;
       }
    }
}
 
// Start the class on load and get reply
if ( ! isset($GLOBALS['gpx_db_REST']) ) {

    unset($GLOBALS['gpx_db_REST']);
    $GLOBALS['gpx_db_REST'] = new gpx_db_REST() ;
}
?>