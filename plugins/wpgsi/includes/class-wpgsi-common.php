<?php
/**
 * This is a Common utility Methods class.
 * All those Methods are used in many classes
 * @link       javmah.com
 * @since      3.6.0
 * @package    Wpgsi
 * @subpackage Wpgsi/admin
 * @author     javmah <jaedmah@gmail.com>
 */
class Wpgsi_Common {
	/**
	 * The ID of this plugin.
	 * @since    3.6.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 * @since    3.6.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	*/
	private $version;
	/**
	 * The common object.
	 * @since    3.6.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	*/
	public function __construct($plugin_name, $version){
		$this->plugin_name 	= $plugin_name;				# Name of the Plugin setting for this Class
		$this->version 		= $version;					# Version of this Plugin setting for this Class
	}

	/**
	 * LOG ! For Good , This the log Method 
	 * @since      3.6.0
	 * @param      string    $file_name       	File Name . Use  [ get_class($this) ]
	 * @param      string    $function_name     Function name.	 [  __METHOD__  ]
	 * @param      string    $status_code       The name of this plugin.
	 * @param      string    $status_message    The version of this plugin.
	*/
	public function wpgsi_log($file_name = '', $function_name = '', $status_code = '', $status_message = ''){
		# Log status
		$logStatusOption = get_option('wpgsi_logStatus', false);
		# check log status 
		if($logStatusOption  AND  $logStatusOption == 'disable'){
			return  array(FALSE, "ERROR: Log is disable."); 
		} 
		# Check and Balance 
		if(empty($status_code) or empty($status_message)){
			return  array(FALSE, "ERROR: status_code OR status_message is Empty");
		}
		# Post Excerpt 
		$post_excerpt  = json_encode(array("file_name" => esc_sql($file_name), "function_name" => esc_sql($function_name)));
		# Inserting into the DB
		global $wpdb;
		$sql 	 = "INSERT INTO {$wpdb->prefix}posts (post_content, post_title, post_excerpt, post_type) VALUES ( '" . esc_sql($status_message) . "','" . esc_sql($status_code) . "','" . esc_sql($post_excerpt) . "', 'wpgsi_log')";
		$results = $wpdb->get_results($sql);
		# return coin
		return  array(TRUE, "SUCCESS: Successfully inserted to the Log"); 
	}

	/**
     * Testing Common Class; this Method is a variadic Method so it can get all kind of data;
     * @param array  		Data  or Data array optional.
     * @param string  		Data  or Data array optional.
     * @param int  			Data  or Data array optional.
     * @uses 			    Wp Admin Footer Hook
    */
	public function wpgsi_common_test(...$data){
		?>
			<div class="notice notice-success is-dismissible">
				<?php
					if(! empty($data)){
						echo"<pre>";
							print_r($data);
						echo"</pre>";
					}else{	
						echo"<br>Common test function successfully called.<br><br>";
					}
				?>
			</div>
    	<?php
	}

	/**
	 * This Function will create a relation between data and the Integration key || Its a Helper Function 
	 * @since     3.7.0
	 * @return    array   	it will not return  array of relation
	*/
	public function relationToValue($data = [], $relations = []){
		# data array empty check,
		if(empty($data)){
			return array(FALSE, 'ERROR: data array is empty. ' . json_encode($data, TRUE));
		}
		# relations array empty check 
		if(empty($relations)){
			return array(FALSE, 'ERROR: relation array is empty.');
		}
		# Empty Array Holder 
		$rtnArr = array();
		# Looping starts 
		foreach($relations as $key => $value){
			if(isset($data[$value])){
				$rtnArr[str_replace(array('{{','}}'),'', $key )] = ($data[$value] == '--' )? '' : trim($data[$value] );
			}
		}
		# This is The return 
		if(! empty($rtnArr)){
			return array(TRUE, $rtnArr);
		} else {
			return array(FALSE, "Empty array!");
		}
	}

	/**
	 * This is a Helper function to check Table is Exist or Not 
	 * If DB table Exist it will return True if Not it will return False
	 * @since      3.7.0
	 * @param      string    $data_source    Which platform call this function s
	*/
	public function wpgsi_dbTableExists($tableName = null, $prefix = FALSE){
		if(empty($tableName)){
			return FALSE;
		}
		# database Global object 
		global $wpdb;
		# testing Prefix
		if($prefix){
			$r = $wpdb->get_results("SHOW TABLES LIKE '" . $tableName . "'");
		} else {
			$r = $wpdb->get_results("SHOW TABLES LIKE '" . $wpdb->prefix . $tableName . "'");
		}
		
		if($r){
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	/**
	 * This Function Will return all the Save integrations from database 
	 * @since      3.4.0
	 * @return     array   	 This Function Will return an array 
	*/
	public function wpgsi_getIntegrations() {
		# Setting Empty Array
		$integrationsArray 	 = array();
		# Getting All Posts
		$listOfConnections   = get_posts(array(
			'post_type'   	 => 'wpgsiIntegration',
			'post_status' 	 => array('publish', 'pending'),
			'posts_per_page' => -1
		));

		# integration loop starts
		foreach($listOfConnections as $key => $value){
			# Compiled to JSON String 
			$post_excerpt =@ json_decode($value->post_excerpt, TRUE);
			# if JSON Compiled successfully 
			if(is_array($post_excerpt) AND ! empty($post_excerpt)){
				$integrationsArray[$key]["IntegrationID"] = isset($value->ID)                    ? $value->ID                     : "";
				$integrationsArray[$key]["DataSource"] 	  = isset($post_excerpt["DataSource"])   ? $post_excerpt["DataSource"]    : "";
				$integrationsArray[$key]["DataSourceID"]  = isset($post_excerpt["DataSourceID"]) ? $post_excerpt["DataSourceID"]  : "";
				$integrationsArray[$key]["Worksheet"] 	  = isset($post_excerpt["Worksheet"])    ? $post_excerpt["Worksheet"]     : "";
				$integrationsArray[$key]["WorksheetID"]   = isset($post_excerpt["WorksheetID"])  ? $post_excerpt["WorksheetID"]   : "";
				$integrationsArray[$key]["Spreadsheet"]   = isset($post_excerpt["Spreadsheet"])  ? $post_excerpt["Spreadsheet"]   : "";
				$integrationsArray[$key]["SpreadsheetID"] = isset($post_excerpt["SpreadsheetID"])? $post_excerpt["SpreadsheetID"] : "";
				$integrationsArray[$key]["Status"] 		  = isset($value->post_status)           ? $value->post_status            : "";
			} else {
				# keeping error log 
                $this->wpgsi_log(get_class($this), __METHOD__,"104", "ERROR: invalid JSON string. JSON decode error on post_excerpt, POST ID IS : " . $value->ID);
			}
		}
		# integration loop Ends
		# return  array with First Value as Bool and second one is integrationsArray array
		if(count($integrationsArray)){
			return array(TRUE, $integrationsArray);
		} else {
			return array(FALSE, $integrationsArray);
		}
	}

	# class ends 
}
