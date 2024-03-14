<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://myworks.software/
 * @since      1.0.0
 *
 * @package    MyWorks_WC_Xero_Sync
 * @subpackage MyWorks_WC_Xero_Sync/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define custom plugin functions.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    MyWorks_WC_Xero_Sync
 * @subpackage MyWorks_WC_Xero_Sync/includes
 * @author     MyWorks Software <support@myworks.design>
 */

class MyWorks_WC_Xero_Sync_Core{
	#Variables
	public $session_prefix;	
	public $per_page_keyword;	
	public $default_show_per_page;	
	public $show_per_page;
	public $log_save_days;
	public $db_table_prefix;
	
	private $session = null;
	
	protected $server_timezone;

	#Options
	private $use_php_session = false;
	
	public function __construct(){
		$this->session_prefix = MWXS_C_Settings::Get_C_Setting('session_prefix');
		$this->per_page_keyword = MWXS_C_Settings::Get_C_Setting('per_page_keyword');
		$this->default_show_per_page = MWXS_C_Settings::Get_C_Setting('default_show_per_page');
		$this->show_per_page = MWXS_C_Settings::Get_C_Setting('show_per_page');
		$this->log_save_days = MWXS_C_Settings::Get_C_Setting('log_save_days');
		$this->db_table_prefix = MWXS_C_Settings::Get_C_Setting('db_table_prefix');

		if(function_exists('date_default_timezone_get')){
			$this->server_timezone = date_default_timezone_get();
		}
		
	}
	
	private function use_php_session(){
		# PHP session not supported now
		return (bool) $this->use_php_session;
	}
	
	public function initialize_session(){
		# New
		if(!is_user_logged_in()){
			return false;
		}
		
		if(is_null($this->session) && !empty($this->session_prefix)){
			$this->wc_session_includes();
			$session_class = 'MyWorks_WC_Xero_Sync_Lib_Session_Handler';
			if(is_null($this->session ) || !$this->session instanceof $session_class){
				$this->session = new $session_class($this->gdtn('sessions'));
				$this->session->init();
			}
		}		
	}
	
	private function wc_session_includes(){
		if(!class_exists('WC_Session')){
			require_once WC_ABSPATH . 'includes/abstracts/abstract-wc-session.php';
		}		
		require_once plugin_dir_path( __FILE__ ) . 'class-session-handler.php';
	}
	
	/*Plugin setting related functions*/
	public function update_option($option,$value,$autoload=null){
		if(is_null($autoload)){
			$autoload = false;
		}
		
		return update_option($option,$value,$autoload);
	}
	
	public function get_option($key,$default=''){
		$ov = get_option($key);
		if(empty($ov)){
			$ov = $default;
		}
		return $ov;
	}
	
	public function option_checked($option,$df=false){
		$ov = $this->get_option($option);
		
		if($ov == 'true'){
			return true;
		}
		
		if($df && empty($ov)){
			return true;
		}
		
		return false;
	}
	
	public function plugin_get_all_options(){
		global $wpdb;
		$option_arr = array();
		
		$whr = '';
		
		# "'{FN}'"
		$ignore_opts = array();
		
		if(!empty($ignore_opts)){			
			$io_q = implode(",",$ignore_opts);
			$whr = " AND `option_name` NOT IN({$io_q}) ";
		}
		
		$option_data = $this->get_data("SELECT * FROM ".$wpdb->options." WHERE `option_name` LIKE '{$this->get_s_o_p()}%'{$whr}");
		if(is_array($option_data) && count($option_data)){
			foreach($option_data as $Option){
				$option_arr[$Option['option_name']] = $Option['option_value'];
			}
		}
		
		return $option_arr;
	}
	
	public function get_s_o_p(){
		return 'mw_wc_xero_sync_';
	}
	
	public function is_s2_dd(){
		if($this->option_checked('mw_wc_xero_sync_enable_select2_dd',true)){
			return true;
		}
	}
	
	public function is_s2_ajax_dd(){		
		if(!$this->is_s2_dd()){
			return false;
		}
		
		if($this->option_checked('mw_wc_xero_sync_select2_ajax_dd')){
			return true;
		}
	}
	
	/*DB base functions*/

	# Get all plugin DB table names
	public function get_plugin_db_tbl_list(){
		$tl_q = "SHOW TABLES LIKE '{$this->db_table_prefix}%'";
		$tbl_list = $this->get_data($tl_q);

		$p_tbls = array();
		if(is_array($tbl_list) && count($tbl_list)){
			foreach($tbl_list as $tl){
				if(is_array($tl) && count($tl)){
					$tl_v = current($tl);$tl_v = (string) $tl_v;$tl_v = trim($tl_v);
					if($tl_v!=''){
						$p_tbls[] = $tl_v;
					}
				}
			}
		}
		return $p_tbls;
	}

	# Get all plugin DB tables with fields
	public function db_check_get_fields_details($s_tbf_list=array()){
		$tb_f_list = array();
		$tbls = $this->get_plugin_db_tbl_list();
		if(is_array($tbls) && count($tbls)){
			foreach($tbls as $tln){
				$tcq = "SHOW COLUMNS FROM {$tln}";
				$tc_list = $this->get_data($tcq);
				$tc_tmp_arr = array();
				if(is_array($tc_list) && count($tc_list)){
					foreach($tc_list as $tc_l){
						$tc_tmp_arr[$tc_l['Field']] = $tc_l;
					}
				}				
				$tb_f_list[$tln] = $tc_tmp_arr;
			}
		}		
		return $tb_f_list;
	}
	
	#Get plugin db table name
	public function gdtn($t){
		return $this->db_table_prefix.$t;
	}
	
	# All data
	public function get_data($query){
		global $wpdb;
		$query = trim($query);
		if($query!=''){
			return $wpdb->get_results($query,ARRAY_A);
		}
	}
	
	# Single row
	public function get_row($query){
		global $wpdb;
		$query = trim($query);
		if($query!=''){
			return $wpdb->get_row($query,ARRAY_A);
		}
	}
	
	# Key field row by value field
	public function get_row_by_val($tbl,$field,$field_val){
        global $wpdb;
        if($tbl!='' && $field!='' && $field_val!=''){
            $tbl_q = "SELECT * FROM $tbl WHERE $field= '%s'";
            $tbl_data = $this->get_row($wpdb->prepare($tbl_q,$field_val));
            return $tbl_data;
        }
        
		return array();
    }
	
	# Key field data by value field
	public function get_field_by_val($tbl,$get_field,$field,$field_val){
        global $wpdb;
        if($tbl!='' && $get_field!='' && $field!='' && $field_val!=''){
            $tbl_q = "SELECT $get_field FROM $tbl WHERE $field= '%s'";
            $tbl_data = $this->get_row($wpdb->prepare($tbl_q,$field_val));
            return (isset($tbl_data[$get_field]))?$tbl_data[$get_field]:'';
        }
        else{
            return '';
        }
    }
	
	# Table data
	public function get_tbl($tbl,$fields='*',$whr='',$orderby='',$limit='',$group_by='',$having=''){
		if($tbl!=''){

			if(trim($fields)==''){$fields='*';}

			$tl_q = "SELECT $fields FROM $tbl ";

			if($whr!=''){
				$tl_q.="WHERE $whr ";
			}

			if($group_by!=''){
				$tl_q.="GROUP BY $group_by ";
			}

			if($having!=''){
				$tl_q.="HAVING $having ";
			}

			if($orderby!=''){
				$tl_q.="ORDER BY $orderby ";
			}

			if($limit!=''){
				$tl_q.="LIMIT $limit ";
			}


			return $this->get_data($tl_q);
		}
	}
	
	/*HTML elements functions*/
	
	# Select option values from an array
	public function only_option($selected='',$opt_arr = array(),$s_key='',$s_val='',$return=false,$dsbl_arr=array()){
		$options='';
		if(is_array($opt_arr) && count($opt_arr)>0){
			foreach ($opt_arr as $key => $value) {
				$sel_text = '';

				if($s_key!='' && $s_val!=''){
                    #Multi
                    if(is_array($selected) && !empty($selected)){
                        if(in_array($value[$s_key],$selected)){$sel_text = ' selected';}
                    }else{
                        if($value[$s_key] == $selected){$sel_text = ' selected';}
                    }
					
					$odsbl = '';
					/*
					if(is_array($dsbl_arr) && isset($dsbl_arr[$value[$s_key]])){
						$odsbl = ' disabled';
					}
					*/
					
					if($return){
						$options.='<option'.$this->escape($odsbl).' value="'.$this->escape($value[$s_key]).'" '.$this->escape($sel_text).'>'.stripslashes($this->escape($value[$s_val])).'</option>';
					}else{
						echo '<option'.$this->escape($odsbl).' value="'.$this->escape($value[$s_key]).'" '.$this->escape($sel_text).'>'.stripslashes($this->escape($value[$s_val])).'</option>';
					}

				}else{
                    #Multi
                    if(is_array($selected) && !empty($selected)){
                        if(in_array($key,$selected)){$sel_text = ' selected';}
                    }else{
                        if($key == $selected){$sel_text = ' selected';}
                    }
					
					$odsbl = '';					
					if(is_array($dsbl_arr) && isset($dsbl_arr[$key])){
						$odsbl = ' disabled';
					}
					
					if($return){
						$options.='<option'.$this->escape($odsbl).' value="'.$this->escape($key).'" '.$this->escape($sel_text).'>'.stripslashes($this->escape($value)).'</option>';
					}else{
						echo '<option'.$this->escape($odsbl).' value="'.$this->escape($key).'" '.$this->escape($sel_text).'>'.stripslashes($this->escape($value)).'</option>';
					}

				}
			}
		}
		if($return){
			return $options;
		}
	}
	
	# Select option values from db table
	public function option_html($selected='',$t_name='',$key_field='',$val_field='',$whr='',$orderby='',$limit='',$return=false){
		if($t_name!='' && $key_field!='' && $val_field!=''){
			$op_fields = "$key_field,$val_field";
			$op_data = $this->get_tbl($t_name,$op_fields,$whr,$orderby,$limit);			
			
			if($this->start_with($val_field,'CONCAT(') || $this->start_with($val_field,'CONCAT_WS(')){
				$vfa = preg_split('/\s+/', $val_field);
				$val_field = end($vfa);
			}
			
			if($return){
				return $this->only_option($selected,$op_data,$key_field,$val_field,$return);
			}
			$this->only_option($selected,$op_data,$key_field,$val_field,$return);
		}
	}
	
	/*Query functions*/
	
	# Key value pair from table into an array
	public function get_key_value_options_from_table($blank_option=false,$t_name='',$key_field='',$val_field='',$whr='',$orderby='',$limit=''){
		$kv_arr = array();
		if($t_name!='' && $key_field!='' && $val_field!=''){
			$op_fields = "$key_field,$val_field";
			$op_data = $this->get_tbl($t_name,$op_fields,$whr,$orderby,$limit);
			
			if($this->start_with($val_field,'CONCAT(') || $this->start_with($val_field,'CONCAT_WS(')){
				$vfa = preg_split('/\s+/', $val_field);
				$val_field = end($vfa);
			}
			
			if(is_array($op_data) && count($op_data)>0){
				if($blank_option){
					$kv_arr[''] = '';
				}
				foreach ($op_data as $key => $value) {
					$kv_arr[$value[$key_field]] = $value[$val_field];
				}
			}
		}
		return $kv_arr;
	}
	
	/*Log functions*/
	public function add_test_log($log_txt,$clear_last_day=true,$append=true){
		if(trim($log_txt)==''){
			return;
		}
		$f_log_txt = trim($log_txt).PHP_EOL;
		$log_file_name = plugin_dir_path( dirname( __FILE__ ) ) .'log'.DIRECTORY_SEPARATOR.'dev-test.log';
		$f_ot = ($append)?'a':'w';
		
		if($clear_last_day && $append && file_exists($log_file_name)){
			if((time()-filemtime($log_file_name)) > 86400){
				$f_ot = 'w';
			}
		}

		$log_file = fopen($log_file_name, $f_ot);
		fwrite($log_file, "\n". $f_log_txt);
		fclose($log_file);
	}
	
	/*Print-debug functions*/
	public function _p($item='',$dump=false){
		echo '<pre>';
		if(is_object($item) || is_array($item)){
			if($dump){
				var_dump($item);
			}else{
				print_r($item);
			}
		}else{
			if($dump){
				var_dump($this->escape($item));
			}else{
				echo $this->escape($item);
			}

		}
		echo '</pre>';
	}
	
	/*Encryption functions - using openssl*/
	private function get_encryption_key(){
		$key = '';//52
		return $key;
	}
	
	private function get_encryption_iv(){
		$iv = '';//16
		return $iv;
	}
	
	public function encrypt($input_string, $key=''){
		$key = $this->get_encryption_key();
		$iv = $this->get_encryption_iv();
		
		if(empty($key) || empty($iv)){
			return $input_string;
		}
		
		$encrypt_method = "AES-256-CBC";
		$key = hash( 'sha256', $key );
		$iv = substr( hash( 'sha256', $iv ), 0, 16 );
		return base64_encode( openssl_encrypt( $input_string, $encrypt_method, $key, 0, $iv ) );
	}
	
	public function decrypt($input_string, $key=''){
		$key = $this->get_encryption_key();
		$iv = $this->get_encryption_iv();
		
		if(empty($key) || empty($iv)){
			return $input_string;
		}
		
		$encrypt_method = "AES-256-CBC";
		$key = hash( 'sha256', $key );
		$iv = substr( hash( 'sha256', $iv ), 0, 16 );
		return openssl_decrypt( base64_decode( $input_string ), $encrypt_method, $key, 0, $iv );
	}
	
	/*Datetime functions*/
	public function get_wp_timezone(){
		$tz = $this->get_option('timezone_string');
		if(empty($tz)){
			$tz = $this->server_timezone;
		}
		return $tz;
	}

	public function get_xero_timezone(){
		return 'UTC';
	}
	
	public function get_cdt($timezone='',$format='Y-m-d H:i:s'){
		if(empty($timezone)){			
			$timezone = $this->get_wp_timezone();
		}
		
		if($timezone!=''){
			$now = new DateTime('now', new DateTimeZone($timezone));
			$datetime = $now->format($format);
			return $datetime;
		}
		return date($format);
	}
	
	public function now($format='Y-m-d H:i:s',$timezone=''){
		return $this->get_cdt($timezone,$format);
	}
	
	function convert_dt_timezone($d_time,$toTz,$fromTz=''){
		if(!empty($toTz) || !empty($d_time)){
			return $d_time;
		}
		
		if(empty($fromTz)){
			$fromTz = $this->server_timezone;
		}

        $date = new DateTime($d_time, new DateTimeZone($fromTz));
        $date->setTimezone(new DateTimeZone($toTz));
        $d_time= $date->format('Y-m-d H:i:s');
        return $d_time;
    }
	
	public function format_date($date,$format="Y-m-d"){
		if($date!='' && $date!=NULL && $date!='0000-00-00 00:00:00'){
			$date = strtotime($date);
			return date($format,$date);
		}
	}

	# Xero API Response Date
	public function format_xero_date($str,$format='Y-m-d H:i:s',$convert_to_wp_date=true){
		$formated_date = '';
		if(!empty($str)){			
			$match = preg_match( '/([\d]{13})/', $str, $date );
			if($match && is_array($date) && isset($date[1]) && !empty($date[1])){
				$timestamp = $date[1]/1000;
				if($timestamp > 0){
					$datetime = new \DateTime();
					$datetime->setTimestamp($timestamp);
					$formated_date = $datetime->format('Y-m-d H:i:s');
				}
			}
			
			if(!empty($formated_date)){
				if($convert_to_wp_date){
					$formated_date = $this->convert_dt_timezone($formated_date,$this->get_wp_timezone(),$this->get_xero_timezone());
				}
			}
		}
		return $formated_date;
	}
	
	# Get array value by key
	public function get_array_isset($data,$kw,$df='',$decode=true,$trim=0,$as=false,$ra=array(),$ss=false){
		$return = $df;
		if(is_array($data) && count($data)){
			if(isset($data[$kw])){
				$return = $data[$kw];
				$return = trim($return);
				if($decode){					
					$return= strip_tags($return);					
					if($ss){
						$return = stripslashes($return);
					}					
					
					$return = htmlspecialchars_decode($return,ENT_QUOTES);					
					$return = html_entity_decode($return,ENT_QUOTES);
				}
				if($trim){
					if(strlen($return) > $trim){
						$return = substr($return,0,$trim);
					}
				}
				if($as){
					$return = addslashes($return);
				}
				if(is_array($ra) && count($ra)){
					$return = str_replace($ra,'',$return);
				}
			}
		}
		return $return;
	}
	
	public function get_plugin_domain(){
		$siteurl = $this->get_option('siteurl'); #get_site_url
		if(!empty($siteurl)){
			$psurl = parse_url($siteurl);
			if(is_array($psurl) && isset($psurl['host'])){
				return $psurl['host'];
			}
		}
		
		return (isset($_SERVER['SERVER_NAME']))?$this->sanitize($_SERVER['SERVER_NAME']):'';
	}
	
	public function get_plugin_ip(){
		$s_laddr = (isset($_SERVER['LOCAL_ADDR']))?$this->sanitize($_SERVER['LOCAL_ADDR']):'';
		$usersip = isset($_SERVER['SERVER_ADDR']) ? $this->sanitize($_SERVER['SERVER_ADDR']) : $s_laddr;
		
		$sname = $this->get_plugin_domain();
		if(empty($usersip) && !empty($sname)){
			$usersip = gethostbyname($sname);
		}
		return $usersip;
	}
	
	public function get_plugin_connection_dir(){
		$dirpath = dirname(__FILE__);
		return $dirpath;
	}
	
	# Session Functions	
	public function set_and_get($keyword){
		if(!empty($keyword) && isset($_GET[$keyword])){
			$this->set_session_val($keyword,$this->var_g($keyword));
		}
	}
	
	public function set_and_post($keyword){
		if(!empty($keyword) && isset($_POST[$keyword])){
			$this->set_session_val($keyword,$this->var_p($keyword));
		}
	}
	
	public function set_session_val($keyword,$value){
		if(is_null($this->session)){
			return false;
		}
		
		$this->session->set($this->session_prefix.$keyword, $value);
	}
	
	public function get_session_val($keyword,$default='',$reset=false){
		if(is_null($this->session)){
			return $default;
		}
		
		$val = $default;
		if(!empty($keyword) && $this->session->__isset($this->session_prefix.$keyword)){
			$val = $this->session->get($this->session_prefix.$keyword,$default);
			if($reset){
				$this->session->__unset($this->session_prefix.$keyword);
			}
		}
		return $val;
	}
	
	public function isset_session($keyword){
		if(is_null($this->session)){
			return false;
		}
		
		if(!empty($keyword) && $this->session->__isset($this->session_prefix.$keyword)){
			return true;
		}
		return false;
	}
	
	public function unset_session($keyword){
		if(is_null($this->session)){
			return false;
		}
		
		if(!empty($keyword) &&$this->session->__isset($this->session_prefix.$keyword)){
			$this->session->__unset($this->session_prefix.$keyword);
		}
	}
	
	# Pagination Functions
	public function default_show_per_page(){
		$dspp = (int) $this->default_show_per_page;
		if($dspp < 1){
			$dspp = 20;
		}
		
		if($dspp > 500){
			$dspp = 500;
		}
		
		return $dspp;
	}
	
	public function get_offset($page, $items_per_page){
		return ( $page * $items_per_page ) - $items_per_page;
	}
	
	public function set_per_page_from_url($unique=''){
		if(isset($_GET[$this->per_page_keyword]) && (int) $_GET[$this->per_page_keyword]>0){
			$pp = (int) $_GET[$this->per_page_keyword];
			if(!$pp){$pp=$this->default_show_per_page();}
			$this->set_session_val('item_per_page'.$unique,$pp);
		}
	}
	
	public function get_item_per_page($unique='',$default=0){
		$dspp = $this->default_show_per_page();
		
		$default = (int) $default;
		if($default > 0){
			$dspp = $default;
		}		
		
		$itemPerPage = $this->get_session_val('item_per_page'.$unique,$dspp);
		return $itemPerPage;
	}
	
	public function get_page_var(){
		#$page = (get_query_var('paged')) ? (int) get_query_var('paged') : 1;
		$page = isset($_GET['paged']) ? (int) $_GET['paged'] : 1;
		if(!$page){$page=1;}
		return $page;
	}
	
	public  function get_paginate_links($total_records=0,$items_per_page=20,$show_total=true,$page=''){
		if($page==''){
			$page = $this->get_page_var();
		}

		if($total_records>0){
			echo '<div class="mwqs_paginate_div mwqbd_pd">';
			$i_text = ($total_records>1)?'items':'item';
			if($show_total){				
				$total_pages = ceil($total_records / $items_per_page);
				$pgn_txt = $this->get_pagination_count_txt($page,$total_pages,$total_records,$items_per_page);

				echo '<div>'.$this->escape($pgn_txt).'</div>';
			}
			
			if($total_records>$items_per_page){
				echo'<div class="pagination">';
			
				echo paginate_links( array(
					'base' => add_query_arg( 'paged', '%#%' ),
					'format' => '',
					'prev_text' => '&laquo;',
					'next_text' => '&raquo;',
					'total' => ceil($total_records / $items_per_page),
					'current' => $page,
					'end_size' =>2,
					'mid_size' =>3

				));

				echo '</div>';
			}

			echo '</div>';
		}
	}
	
	public function get_pagination_count_txt($page,$total_pages,$count,$itemPerPage){
		$cur_page = ($page==0)?1:$page;
		if ($page != 0) $page--;

		$txt = '';
		if($cur_page<=$total_pages){
			$e_text = ($count>1)?'entries':'entry';
			$txt = 'Showing '.($page*$itemPerPage+1).' to '.(($total_pages==$cur_page || $itemPerPage>=$count)?$count:($page+1)*$itemPerPage).' of '.$count.' '.$e_text;
		}
		return $txt;
	}
	
	#Check if user can manage woocommerce
	public function if_user_m_wc(){
		if(current_user_can('manage_woocommerce') || current_user_can('view_woocommerce_report')){
			return true;
		}
		
		return false;
	}
	
	/*Country*/
	public function get_wc_country_list(){
		$countries_obj   = new WC_Countries();
		$countries   = $countries_obj->__get('countries');
		return $countries;
	}
	
	public function get_country_name_from_code($code=''){
		if($code!=''){			 
			 $countries   = $this->get_wc_country_list();
			 if(is_array($countries) && isset($countries[$code])){
				 return $countries[$code];
			 }
		}
		return $code;
	}
	
	/*Currency*/
	
	#Other Functions
	public function get_html_msg($title,$body){
		echo '
		<html>
			<head>
				<title>'.$this->escape($title).'</title>
			</head>

			<body>
				<h1>'.$this->escape($body).'</h1>
			</body>
		</html>';		
	}
	
	public function var_p($key,$default=''){
		if($key!=''){
			if(isset($_POST[$key])){
				if(!is_array($_POST[$key])){
					return $this->sanitize($_POST[$key]);
				}
				else{
					return $this->array_sanitize($_POST[$key]);
				}
			}
		}

		return $default;
	}

	public function var_g($key,$default=''){
		if($key!=''){
			if(isset($_GET[$key])){
				return $this->sanitize($_GET[$key]);
			}
		}

		return $default;
	}
	
	public function show_sync_window_message($id, $message, $progress=0, $tot=0) {		
		$d = array('message' => $message , 'progress' => $progress,'total' => $tot,'cur' => $id);
		echo json_encode($d);
		ob_flush();
		flush();
		die();
	}

	public function get_current_request_protocol(){
		if(!empty($_SERVER['HTTP_X_FORWARDED_PROTO'])){
			return $this->sanitize($_SERVER['HTTP_X_FORWARDED_PROTO']);
		}
		return (isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS']!='OFF') ? "https" : "http";
	}

	public function get_sync_window_url(){
		$request_protocol = $this->get_current_request_protocol();

		$current_url = $request_protocol.'://'.$this->sanitize($_SERVER['HTTP_HOST']).$this->sanitize($_SERVER['SCRIPT_NAME']);		
		$current_url = esc_url($current_url);
		
		$sync_window_url = site_url('index.php?mw_xero_sync_public_sync_window=1');

		if(strpos($current_url, 's://')===false){
			$sync_window_url = str_replace('s://','://',$sync_window_url);
		}else{
			if(strpos($sync_window_url, 's://')===false){
				$sync_window_url = str_replace('://','s://',$sync_window_url);
			}
		}

		if(strpos($current_url, '://www.')===false){
			$sync_window_url = str_replace('://www.','://',$sync_window_url);
		}else{
			if(strpos($sync_window_url, '://www.')===false){
				$sync_window_url = str_replace('://','://www.',$sync_window_url);
			}
		}

		return esc_url($sync_window_url);
	}
	
	public function redirect($url){
		/*
		if($url!=''){
			$url = esc_url_raw($url);
			wp_redirect( $url );
			exit;
		}
		*/
	}
	
	public function start_with($haystack, $needle){
		return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
	}

	public function array_sanitize($data){		
		if (is_array($data) && !empty($data)) {
			foreach ($data as $key => $value) {
				$data[$key] = $this->array_sanitize($value);
			}
		} else {
			# For empty array - It will return array
			if(is_array($data)){
				return $data;
			}
			
			$data = $this->sanitize($data);
		}

		return $data;		  
	}
	
	public function sanitize($txt,$textarea=false){
		if($txt != ''){
			$txt = trim($txt);
		}
		
		if(empty($txt)){
			return $txt;
		}

		$txt = esc_sql( $txt );
		
		if($textarea){
			$txt = sanitize_textarea_field( $txt );
		}else{
			$txt = sanitize_text_field( $txt );
		}

		return $txt;
	}

	public function escape($str){
		if(!empty($str)){
			$str = esc_html( $str );
			$str = esc_js( $str );			
		}

		return $str;
	}
	
	public function trim_add_slash($str){
		return addslashes(trim($str));
	}
	public function is_plugin_active($plugin,$diff_filename=''){
		$active = false;
		$plugin = trim($plugin);
		$diff_filename = trim($diff_filename);
		$plugin_file = ($diff_filename!='')?$diff_filename:$plugin;
		
		if(function_exists('is_plugin_active')){
			if( is_plugin_active( $plugin.'/'.$plugin_file.'.php' ) ) {
				$active = true;
			}
		}else{
			$active = in_array( $plugin.'/'.$plugin_file.'.php', (array) get_option( 'active_plugins', array() ) );
		}
		
		return $active;
	}
	
	public function is_plugin_admin_page($pn='',$param=''){
		if(!is_admin() || empty($_SERVER['QUERY_STRING'])){
			return false;
		}

		$qsc = 'myworks-wc-xero-sync';
		if(!empty($pn)){
			$qsc .= '-'.$pn;
		}
		
		$query_string = explode('=',$this->sanitize($_SERVER['QUERY_STRING']));
		
		if(is_array($query_string) && isset($query_string[1]) && !empty($query_string[1]) && $query_string[0] == 'page'){
			if( strpos( $query_string[1], $qsc ) !== false ) {
				if(empty($param)){
					return true;
				}else{
					if( !is_array($param) && $param == 'dashboard' && empty($pn) && ($query_string[1] == $qsc || strpos( $query_string[1], $qsc ) !== false)){					
						return true;
					}
					
					if(isset($query_string[2]) && !empty($query_string[2])){
						if(is_array($param)){
							if(in_array($query_string[2],$param)){
								return true;
							}
							
							foreach($param as $v){
								if( strpos( $query_string[2], $v ) !== false ) {
									return true;
								}
							}
						}else{
							if($query_string[2] == $param){
								return true;
							}
							
							if( strpos( $query_string[2], $param ) !== false ) {
								return true;
							}
						}						
					}
				}				
			}
		}

		return false;
	}
	
	public function gptd(){
		return 'myworks-sync-for-xero';
	}
	
	#Automap fields
	public function wc_customer_automap_fields(){
		return (array) MWXS_C_Settings::Get_C_Setting('wc_customer_automap_fields');
	}
	
	public function xero_customer_automap_fields(){
		return (array) MWXS_C_Settings::Get_C_Setting('xero_customer_automap_fields');
	}
	
	public function wc_product_automap_fields(){
		return (array) MWXS_C_Settings::Get_C_Setting('wc_product_automap_fields');
	}
	
	public function xero_product_automap_fields(){
		return (array) MWXS_C_Settings::Get_C_Setting('xero_product_automap_fields');
	}
	
	public function wc_variation_automap_fields(){
		return (array) MWXS_C_Settings::Get_C_Setting('wc_variation_automap_fields');
	}
	
	public function xero_variation_automap_fields(){
		return (array) MWXS_C_Settings::Get_C_Setting('xero_variation_automap_fields');
	}
	
	public function stripslash_get_data($q_data,$ss_fields){
		$qd_n = array();
		if(is_array($q_data) && !empty($q_data) && is_array($ss_fields) && !empty($ss_fields)){
			foreach($q_data as $k => $v){
				
				if(is_array($v) && !empty($v)){
					$v = array_map('stripslashes',$v);
				}
				
				$qd_n[$k] = $v;
			}
		}
		if(empty($qd_n)){
			$qd_n = $q_data;
		}
		return $qd_n;
	}
	
	#WC Data Functions
	public function cus_meta_usk(){
		global $wpdb;
		$us_k = array();
		$us_k[] = 'rich_editing';
		$us_k[] = 'syntax_highlighting';
		$us_k[] = 'comment_shortcuts';
		$us_k[] = 'admin_color';
		$us_k[] = 'use_ssl';
		$us_k[] = 'show_admin_bar_front';
		$us_k[] = 'locale';
		$us_k[] = $wpdb->prefix.'capabilities';
		$us_k[] = $wpdb->prefix.'user_level';
		$us_k[] = 'session_tokens';
		$us_k[] = 'last_update';
		$us_k[] = '_stripe_customer_id';
		$us_k[] = '_woocommerce_persistent_cart_1';
		
		$us_k[] = '_woocommerce_tracks_anon_id';
		$us_k[] = $wpdb->prefix.'dashboard_quick_press_last_post_id';
		$us_k[] = 'community-events-location';
		$us_k[] = $wpdb->prefix.'user-settings';
		$us_k[] = $wpdb->prefix.'user-settings-time';
		
		$us_k[] = 'meta-box-order_product';
		$us_k[] = 'closedpostboxes_dashboard';
		$us_k[] = 'metaboxhidden_dashboard';
		
		return $us_k ;
	}
	
	# Decimal, Round Etc
	public function bcdiv_m($_ro, $_lo, $_scale=0) {
		return round($_ro/$_lo, $_scale);
	}
	
	public function trim_after_decimal_place($amount,$dp=2){
		$amount = trim($amount);
		$dp = (int) $dp;		
		if ($amount!='' && $d_pos = strpos($amount, '.') !== false && $dp>0) {
			$a_dp = strlen(substr(strrchr($amount, "."), 1));
			if($a_dp > $dp){
				$amount = floatval($amount);
				$amount = $this->bcdiv_m($amount, 1, $dp);			
			}
		}		
		return $amount;
	}
	
	# Push , Pull Check
	public function check_if_real_time_push_enable_for_item($item=''){
		if(!empty($item)){
			#if($item == 'Inventory'){return true;}
			
			$rt_push_items = (string) $this->get_option('mw_wc_xero_sync_rt_push_items');
			if(!empty($rt_push_items)){
				$rt_push_items = explode(',',$rt_push_items);
				if(is_array($rt_push_items) && !empty($rt_push_items)){
					if(in_array($item,$rt_push_items)){
						return true;
					}
				}
			}
		}
		
		return false;
	}
	
	public function check_if_real_time_pull_enable_for_item($item=''){
		if(!empty($item)){
			$rt_pull_items = (string) $this->get_option('mw_wc_xero_sync_rt_pull_items');
			if(!empty($rt_pull_items)){
				$rt_pull_items = explode(',',$rt_pull_items);
				if(is_array($rt_pull_items) && !empty($rt_pull_items)){
					if(in_array($item,$rt_pull_items)){
						return true;
					}
				}
			}
		}
		
		return false;
	}
	
	# WC user role by ID
	public function get_wc_user_role_by_id($user_id,$user_data=null){
		$user_id = (int) $user_id;
		if($user_id > 0){
			if(is_null($user_data)){
				$user_data = get_userdata($user_id);
			}
			
			if(is_object($user_data) && isset($user_data->roles) && is_array($user_data->roles)){
				return $user_data->roles[0];
			}
		}
		
		return '';
	}
	
	#Log Entry
	public function save_log($ld,$add_into_loggly=false){
		if(is_array($ld) && !empty($ld) && isset($ld['status']) && isset($ld['title']) && !empty($ld['title']) && isset($ld['details'])){
			global $wpdb;
			$table = $this->gdtn('log');
			
			$save_log_for_days = $this->get_option('mw_wc_xero_sync_save_log_for_days');
			#$this->log_save_days			
			
			$save_log_for_days = (empty($save_log_for_days))?30:$save_log_for_days;
			
			if($save_log_for_days != 'NL'){
				$save_log_for_days = (int) $save_log_for_days;
				if($save_log_for_days > 0){
					$log_last_date = date('Y-m-d',strtotime("-{$save_log_for_days} days",strtotime($this->now())));
					$log_last_date = $log_last_date.' 23:59:59';
					
					$wpdb->query(
						$wpdb->prepare(
							"
							DELETE FROM $table
							WHERE `added_date` < %s
							",
							$log_last_date
						)
					);
				}
			}
			
			$sld = array();
			$log_type = (isset($ld['type']) && !empty($ld['type']))?trim($ld['type']):'Other';
			$sld['log_type'] = $log_type;
			$sld['log_title'] = $this->sanitize($ld['title']);
			$sld['details'] = $this->sanitize($ld['details']);
			$status = (int) $ld['status'];
			$status = abs($status);			
			$sld['status'] = $status;
			$sld['wc_id'] = (isset($ld['wc_id']))?(int) $ld['wc_id']:0;
			$sld['xero_id'] = (isset($ld['xero_id']))?$this->sanitize($ld['xero_id']):'';
			$sld['added_date'] = $this->now();
			$sld = array_map('trim',$sld);
			
			$wpdb->insert($table, $sld);
			
			#Loggly
			if($add_into_loggly){
				$ls_type = ($status < 1) ? 'error' : (($status == 1) ? 'success' : 'other');
				if($this->start_with($sld['log_title'],'Import')){
					$ls_type = 'refreshdata';
				}
				
				$loggly_msg = array();
				$loggly_msg['type'] = $ls_type;
				
				$licensekey = $this->get_option('mw_wc_xero_license');
				$loggly_msg['licensekey'] = $licensekey;
				
				$loggly_msg['url'] = get_site_url();
				$loggly_msg['title'] = $sld['log_title'];
				$loggly_msg['message'] = $sld['details'];
				$loggly_msg['log_type'] =$sld['log_type'];
				
				$loggly_msg['product'] = 'WOOXERO';

				#$this->loggly_api_add_log($loggly_msg);
			}
		}
	}
	
	public function loggly_api_add_log($log_data){
		if(is_array($log_data) && !empty($log_data)){
			$log_data = json_encode($log_data);
			
			$requestHeader = array(
				'content-type' => 'text/plain',
			);
			
			$api_key = '00000000-0000-0000-000000000000'; # Not using for now
			$api_url = "http://logs-01.loggly.com/inputs/".$api_key."/tag/http/";			
			
			$response = wp_remote_post($api_url, [
				'timeout' => 30,
				'headers' => $requestHeader,
				'body' => $log_data,
			]);
			
			#$this->_p($response);
		}
	}
	
	# WooCommerce version
	public function get_woo_version_number(){
		if(!function_exists('get_plugins')){
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}
		
		$p_folder = get_plugins( '/' . 'woocommerce' );
		$p_file = 'woocommerce.php';		
		
		if(is_array($p_folder) && isset($p_folder[$p_file]['Version'])){
			return $p_folder[$p_file]['Version'];
		}else{		
			return NULL;
		}
	}
	
	# WooCommerce variation name trim
	public function get_woo_v_name_trimmed($v_name){
		$v_name = trim($v_name);		
		if(!empty($v_name) && strlen($v_name) > 50){
			$fs = substr($v_name, 0, 24);
			$ls = substr($v_name, -25);
			$v_name = $fs.' '.$ls;
		}
		return $v_name;
	}
	
	# Queue add function
	public function wx_queue_add($item_type,$wx_id,$item_action,$priority=1,$wc_hook='',$ext_data=array(),$note=''){
		$a_w_id = false;
		$item_id = 0; $xero_id = '';
		if(!is_int($wx_id) && strlen($wx_id) == 36){
			$xero_id = trim($wx_id);			
		}else{
			$item_id = (int) $wx_id;			
		}
		
		if(!empty($item_type) && ($item_id > 0 || !empty($xero_id) || $a_w_id) && !empty($item_action)){
			global $wpdb;
			$table = $this->gdtn('queue');
			if(!empty($xero_id)){
				$eq_q = $wpdb->prepare("SELECT id FROM `{$table}` WHERE `run` = 0 AND `item_type` = %s AND `item_action` = %s AND `xero_id` = %s ",$item_type,$item_action,$xero_id);
			}else{
				$eq_q = $wpdb->prepare("SELECT id FROM `{$table}` WHERE `run` = 0 AND `item_type` = %s AND `item_action` = %s AND `item_id` = %d ",$item_type,$item_action,$item_id);
			}
			
			$eq_d = $this->get_row($eq_q);
			if(empty($eq_d)){
				$save_queue_data = array();
				$ext_data_s = '';
				if(is_array($ext_data) && !empty($ext_data)){
					$ext_data_s = serialize($ext_data);
				}
				
				$save_queue_data['item_type'] = $item_type;
				$save_queue_data['item_action'] = $item_action;
				$save_queue_data['item_id'] = $item_id;
				$save_queue_data['xero_id'] = $xero_id;
				$save_queue_data['priority'] = intval($priority);
				$save_queue_data['woocommerce_hook'] = $wc_hook;
				$save_queue_data['ext_data'] = $ext_data_s;
				$save_queue_data['run'] = 0;
				$save_queue_data['note'] = $note;		
				$save_queue_data['status'] = 'q';
				
				$save_queue_data['added_date'] = $this->now();
				
				$wpdb->insert($table, $save_queue_data);
				return $wpdb->insert_id;
			}else{
				return $eq_d['id'];
			}
		}
		
		return false;
	}
	
	public function wx_queue_remove($item_type,$wx_id,$item_action,$run=0){
		#-> Will be added later if needed
	}
	
	# Xero Unit Decimal Places
	public function x_unitdp(){
		return 4;
	}
	
	# Xero Fields Char Limits
	public function xfcl($f){
		if(!empty($f)){
			#->
		}
	}
	
	#Test Dev Log File Entry
	public function add_text_into_log_file($log_txt,$clear_last_day=true,$append=true,$lf='dev.log'){
		if(empty($log_txt)){
			return;
		}
		
		if(is_object($log_txt) || is_array($log_txt)){
			$log_txt = print_r($log_txt,true);
		}
		
		if(empty($lf)){
			$lf = 'dev.log';
		}
		
		$f_log_txt = trim($log_txt).PHP_EOL;
		
		#$log_file_name = plugin_dir_path( dirname( dirname(__FILE__) ) ) .'log'.DIRECTORY_SEPARATOR.$lf;
		$log_file_name = MW_WC_XERO_SYNC_P_DIR_P.'log'.DIRECTORY_SEPARATOR.$lf;
		$f_ot = ($append)?'a':'w';
		
		if($clear_last_day && $append && file_exists($log_file_name)){
			if((time()-filemtime($log_file_name)) > 86400){
				$f_ot = 'w';
			}
		}

		$log_file = fopen($log_file_name, $f_ot);
		fwrite($log_file, "\n". $f_log_txt);
		fclose($log_file);		
	}
	
	#Xero sync success log file
	public function add_xs_sd_into_log_file($log_txt,$clear_last_day=true,$append=true){
		if(!$this->option_checked('mw_wc_xero_sync_add_sync_success_data_into_log_file')){
			return false;
		}
			
		$this->add_text_into_log_file($log_txt,$clear_last_day,$append,'x-success.log');
	}
	
	#Xero sync error log file
	public function add_xs_ed_into_log_file($log_txt,$clear_last_day=true,$append=true){
		if(!$this->option_checked('mw_wc_xero_sync_add_sync_error_data_into_log_file')){
			return false;
		}
			
		$this->add_text_into_log_file($log_txt,$clear_last_day,$append,'x-error.log');
	}
	
	public function is_update_enabled($entity){
		return false;
	}
	
	public function null_to_string($v){
		if(is_null($v)){
			$v = (string) $v;
		}
		
		return $v;
	}
	
	public function arr_nts($arr){
		if(!is_array($arr)){
			return $arr;
		}
		
		$n_arr = array();
		if(!empty($arr)){
			foreach($arr as $k => $v){
				$n_arr[$k] = $this->null_to_string($v);
			}
		}
		return $n_arr;
		#array_map(array($this,'null_to_string'),$arr);
	}
	
	public function get_customer_formated_display_name_for_xs($customer_data,$name=''){
		if(is_array($customer_data) && !empty($customer_data)){
			$format = $this->get_option('mw_wc_xero_sync_new_customer_dname_format');
			if(empty($format)){
				$format = '{firstname} {lastname}';
			}
			
			$format = str_replace(
				array('{firstname}','{lastname}','{companyname}','{id}','{phone_number}'),
				array('{first_name}','{last_name}','{company}','{wc_cus_id}','{billing_phone}'),
				$format
			);
			
			$wc_cus_id = (int) $this->get_array_isset($customer_data,'wc_cus_id','');
			
			$s_arr = array('{first_name}','{last_name}','{company}','{wc_cus_id}','{email}','{display_name}','{billing_phone}');
			$r_arr = array(
				$customer_data['first_name'],
				$customer_data['last_name'],
				$customer_data['company'],
				$wc_cus_id,
				$customer_data['email'],			
				(isset($customer_data['display_name']))?$customer_data['display_name']:'',
				(isset($customer_data['billing_phone']))?$customer_data['billing_phone']:'',
			);
			
			$nr_chars = $this->x_nrc('customer');
			
			$x_f_n = str_replace($s_arr,$r_arr,$format);
			$x_f_n = $this->get_array_isset(array('x_f_n'=>$x_f_n),'x_f_n','',true,255,false,$nr_chars);
			return $x_f_n;
		}
		
		return $name;
	}
	
	# Xero name replace char for customer, product etc.
	public function x_nrc($t=''){
		$chars = array(':','\t','\n');
		return $chars;
	}

	# WC get customer currency by id
	public function get_wc_customer_currency($wc_cus_id){
		$wc_cus_id = (int) $wc_cus_id;
		if($wc_cus_id){
			global $wpdb;
			$om = $this->get_row("SELECT `post_id` FROM `{$wpdb->postmeta}` WHERE `meta_key` = '_customer_user' AND `meta_value` = '{$wc_cus_id}' LIMIT 0,1 ");
			if(is_array($om) && count($om)){
				$order_id = (int) $om['post_id'];
				if($order_id){
					$om = $this->get_row("SELECT `meta_value` FROM `{$wpdb->postmeta}` WHERE `meta_key` = '_order_currency' AND `post_id` = {$order_id} LIMIT 0,1 ");
					if(is_array($om) && count($om)){
						return $om['meta_value'];
					}
				}
			}
		}
	}
	
	# Xero error object to formatted array
	private function format_xero_errors($error){
		$e_arr = array();
		if(is_object($error) && !empty($error)){
			$e_arr = array(
				'error_number' => $error->getErrorNumber(),
				'type' => $error->getType(),
				'message' => $error->getMessage(),
				'eml' => array(),
			);

			$elements = $error->getElements();
			if(is_array($elements) && isset($elements[0]['validation_errors'])){
				$validation_errors = $elements[0]['validation_errors'];
				if(is_array($validation_errors) && !empty($validation_errors)){
					foreach($validation_errors as $ve){
						$e_arr['eml'][] = $ve['message'];
					}
				}
			}
		}
		return $e_arr;		
	}

	# For error log entry
	protected function get_error_message_from_xero_error_object($error){
		$error_message = '';
		$e_arr = $this->format_xero_errors($error);
		if(is_array($e_arr) && !empty($e_arr)){
			$error_message .= 'Error Type: '.$e_arr['type'].'('.$e_arr['error_number'].')'.PHP_EOL;			
			$eml = $e_arr['message'];
			if(is_array($e_arr['eml']) && !empty($e_arr['eml'])){
				foreach($e_arr['eml'] as $k => $v){
					$eml .= PHP_EOL.$v;
				}
			}

			$error_message .= 'Error Message: '.$eml;
		}

		return $error_message;
	}

	protected function get_error_message_from_xero_validation_errors($validation_errors){
		$v_err_msg = '';
		if(is_array($validation_errors) && !empty($validation_errors)){
			foreach($validation_errors as $ve_k => $ve_v){
				$message = $ve_v->getMessage();
				if(!empty($message)){
					$v_err_msg .= $message.PHP_EOL;
				}				
			}
		}

		if(!empty($v_err_msg)){
			$v_err_msg = 'Error Type: Validation Error'.PHP_EOL.'Error Message: '.$v_err_msg;
			trim($v_err_msg);
		}

		return $v_err_msg;
	}
	
	# Log page view in xero link 
	public function get_log_page_view_in_xero_link($data){
		$xero_view_link = '';
		if(is_array($data) && !empty($data) && isset($data['log_type']) && !empty($data['log_type'])){
			$xero_view_items = array(
				'Customer',
				'Product',
				'Variation',
				'Order',
			);
			
			if(in_array($data['log_type'],$xero_view_items) && $data['status']==1 && $data['wc_id'] > 0 && !empty($data['xero_id'])){				
				$chk_extra_options = true;

				if($chk_extra_options){
					$xero_url = 'https://go.xero.com/';
					$xero_id = $data['xero_id'];

					switch ($data['log_type']) {
						case "Customer":
							$xero_view_link = $xero_url.'Contacts/View/'.$xero_id;
							break;
						case "Order":
							if(strpos($data['details'],'Quote')!==false){
								$xero_view_link = '';
							}else{
								$xero_view_link = $this->get_xero_view_invoice_link_by_id($xero_id);
							}
							
							break;
						case "Product":
							$xero_view_link = $xero_url.'app/products-and-services/'.$xero_id;
							break;
						case "Variation":
							$xero_view_link = $xero_url.'app/products-and-services/'.$xero_id;
							break;
						default:

					}
				}
			}
		}
		
		if(!empty($xero_view_link)){
			echo '<a target="_blank" class="lg_qb_view" href="'.esc_url($xero_view_link).'" title="View in Xero">i</a>';
		}		
	}
	
	public function get_xero_view_invoice_link_by_id($InvoiceID){
		$InvoiceID = (string) $InvoiceID;
		if(!empty($InvoiceID) && strlen($InvoiceID) == 36){
			return esc_url('https://go.xero.com/AccountsReceivable/Edit.aspx?InvoiceID='.$InvoiceID);
		}
		
		return '';
	}
	
	public function get_log_page_details_field_data_formatted($data){
		if(is_array($data) && isset($data['details']) && !empty($data['details'])){
			$details = $data['details'];
			if($data['status']==1 && $data['wc_id'] > 0 && !empty($data['xero_id'])){
				return str_replace(
					array('#'.$data['wc_id'],'#'.$data['xero_id'],'\n'),
					array(
						'{LPDW_S}#'.$this->escape($data['wc_id']).'{LPDW_E}',
						'{LPDX_S}#'.$this->escape($data['xero_id']).'{LPDX_E}',
						'{LB}',
					),
					$details
				);
			}else{
				return str_replace(
					array('\n'),
					array('{LB}'),
					$details
				);
			}
		}

		return $data['details'];
	}
	
	# Get mapped payment method data by order payment method and currency
	public function get_mapped_payment_method_data($wc_payment_method,$wc_currency=''){
		$wc_payment_method = $this->sanitize($wc_payment_method);
		if(!empty($wc_payment_method)){
			$wc_currency = (!empty($wc_currency))?$this->sanitize($wc_currency):$wc_currency;
			global $wpdb;
			$map_data = $this->get_row($wpdb->prepare("SELECT * FROM `".$this->gdtn('map_payment_method')."` WHERE `wc_payment_method` = %s AND `currency` = %s ",$wc_payment_method,$wc_currency));
			return $map_data;
		}
	}
	
	# Order sync helper functions
	public function get_xosa_arr(){
		return array('Invoice','Quote');
	}
	
	protected function is_sync_fee_line(){
		return true;
	}
	
	protected function is_sync_txn_fee($invoice_data,$pmm_data=array()){
		return true;
	}
	
	#Tax related functions
	protected function get_xero_tax_code_from_line_tax_data($line_data,$line_type='line_item',$xntc=true){
		$tr1_id = 0;
		$tr2_id = 0;

		if(is_array($line_data) && !empty($line_data) && !empty($line_type)){
			$ltd_arr = array();
			$ltd = '';

			if($line_type == 'line_item'){
				$ltd = $line_data['line_tax_data'];
			}

			if($line_type == 'shipping'){
				$ltd = $line_data['taxes'];
			}

			if(!empty($ltd)){
				$ltd = @unserialize($ltd);
				if(is_array($ltd) && !empty($ltd)){
					if(isset($ltd['total']) && is_array($ltd['total']) && !empty($ltd['total'])){
						$ltd_arr = $ltd['total'];
					}
				}
			}

			if(is_array($ltd_arr) && !empty($ltd_arr)){
				$i=1;
				foreach($ltd_arr as $k=>$v){
					if($i==1){
						$tr1_id = (int) $k;
					}
					if($i==2){
						$tr2_id = (int) $k;
					}
					$i++;
				}
			}
		}

		$xero_tax_code = '';
		if($tr1_id>0 || $tr2_id>0){
			$xero_tax_code = $this->get_xero_mapped_tax_code($tr1_id,$tr2_id);
		}

		if(empty($xero_tax_code) && $xntc){
			$xero_tax_code = $this->get_xero_non_taxable_tax_code();
		}

		return $xero_tax_code;
	}
	
	protected function get_xero_mapped_tax_code($tax_rate_id,$tax_rate_id_2=0){
		$xero_tax_code = '';
		$tax_rate_id = (int) $tax_rate_id;
		$tax_rate_id_2 = (int) $tax_rate_id_2;
		if($tax_rate_id > 0){
			global $wpdb;
			$tax_map_table = $this->gdtn('map_tax');
			$tax_map_data = $this->get_row($wpdb->prepare("SELECT `xero_tax` FROM ".$tax_map_table." WHERE `wc_tax_id` = %d AND `wc_tax_id_2` = %d ",$tax_rate_id,$tax_rate_id_2));
			if(is_array($tax_map_data) && count($tax_map_data)){
				$xero_tax_code = $tax_map_data['xero_tax'];
			}
		}

		return $xero_tax_code;
	}

	public function get_xero_non_taxable_tax_code(){
		return $this->get_option('mw_wc_xero_sync_non_taxable_rate');
	}
	
	public function invoice_due_days_options(){
		return array_combine(range(1,100), range(1,100));
	}

	# Txn fee data from Wc order
	public function get_txn_fee_data_from_order($invoice_data){
		$t_f_desc = 'Transaction Fee';
		$t_f_amnt = 0;
		$tfk = '';
		
		if(is_array($invoice_data) && !empty($invoice_data)){
			$pm_c = $this->get_array_isset($invoice_data,'_payment_method','',true);
			$_p_m_t = $this->get_array_isset($invoice_data,'_payment_method_title','',true);
			
			if(!empty($pm_c)){$pm_c = strtolower($pm_c);}
			if(!empty($_p_m_t)){$_p_m_t = strtolower($_p_m_t);}
			
			#New
			$skip_pm_pmt_c = true;
			
			if($skip_pm_pmt_c || strpos($pm_c, 'stripe') !== false || strpos($_p_m_t, 'stripe') !== false){
				$isf = false;
				if(isset($invoice_data['_stripe_fee'])){
					$isf = true;
					$tfk = '_stripe_fee';
				}else{
					if(isset($invoice_data['Stripe Fee'])){
						$isf = true;
						$tfk = 'Stripe Fee';
					}
				}
				
				if($isf){
					$t_f_desc = 'Stripe Fee';
				}				
			}			
			
			if($skip_pm_pmt_c || strpos($pm_c, 'paypal') !== false || strpos($_p_m_t, 'paypal') !== false){
				$ipf = false;
				
				#New WooCommerce PayPal gateway fee support
				if(isset($invoice_data['_ppcp_paypal_fees']) && !empty($invoice_data['_ppcp_paypal_fees'])){
					$_ppcp_paypal_fees = unserialize($invoice_data['_ppcp_paypal_fees']);
					#$this->_p($_ppcp_paypal_fees);
					
					#New
					if(is_array($_ppcp_paypal_fees) && !empty($_ppcp_paypal_fees) && isset($_ppcp_paypal_fees[0]['paypal_fee'])){
						$_ppcp_paypal_fees = $_ppcp_paypal_fees[0];
					}
					
					if(is_array($_ppcp_paypal_fees) && isset($_ppcp_paypal_fees['paypal_fee']) && !empty($_ppcp_paypal_fees['paypal_fee'])){
						if($_ppcp_paypal_fees['paypal_fee']['currency_code'] == $invoice_data['_order_currency']){
							$invoice_data['_paypal_transaction_fee'] = floatval($_ppcp_paypal_fees['paypal_fee']['value']);
							$ipf = true;
							$tfk = '_paypal_transaction_fee';
						}
					}
				}

				if(isset($invoice_data['_paypal_transaction_fee'])){
					$ipf = true;
					$tfk = '_paypal_transaction_fee';
				}else{
					if(isset($invoice_data['_paypal_fee'])){
						$ipf = true;
						$tfk = '_paypal_fee';
					}else{
						if(isset($invoice_data['PayPal Transaction Fee'])){
							$ipf = true;
							$tfk = 'PayPal Transaction Fee';
						}
					}
				}
				
				if($ipf){
					$t_f_desc = 'PayPal Transaction Fee';
				}				
			}
			
			if(empty($tfk) && isset($invoice_data['_transaction_fee'])){
				$tfk = '_transaction_fee';
			}
			
			if(empty($tfk) && isset($invoice_data['transaction_fee'])){
				$tfk = 'transaction_fee';
			}
			
			if(!empty($tfk) && isset($invoice_data[$tfk])){
				$t_f_amnt = (float) $this->get_array_isset($invoice_data,$tfk,0);
			}
		}		
		
		$t_f_a = array(
			't_f_desc' => $t_f_desc,
			't_f_amnt' => $t_f_amnt,
		);
		
		return $t_f_a;
	}
	
	# For order sync line item
	public function get_xero_product_line_item_data($xero_item_id,$xero_product_data=null){
		$r_arr = array(
			'ItemID' => $xero_item_id,
			'Name' => '',
			'Code' => '',
			'IsTrackedAsInventory' => 0,
			'SD_AccountCode' => '',
			'SD_TaxType' => '',
		);
		
		if(!empty($xero_item_id)){
			if(is_null($xero_product_data)){
				$xero_product_data = $this->get_row_by_val($this->gdtn('products'),'ItemID',$xero_item_id);
			}

			if(is_array($xero_product_data) && !empty($xero_product_data)){
				$X_Data = $xero_product_data['X_Data'];
				if(!empty($X_Data)){
					$X_Data = @unserialize($X_Data);
				}

				foreach($r_arr as $k => $v){
					if(isset($xero_product_data[$k])){
						$r_arr[$k] = $xero_product_data[$k];
					}
				}

				if(is_array($X_Data) && !empty($X_Data)){
					foreach($X_Data as $x_k => $x_v){
						if(isset($r_arr[$x_k]) && !isset($xero_product_data[$x_k])){
							$r_arr[$x_k] = $x_v;
						}
					}
				}
			}
		}
		
		if(empty($r_arr['SD_AccountCode'])){
			$r_arr['SD_AccountCode'] = $this->get_option('mw_wc_xero_sync_default_xero_account_foli');
		}
		
		return $r_arr;
	}
	
	protected function add_order_note($order_id,$note){
		if($order_id > 0 && !empty($note)){
			$order = new WC_Order( $order_id );
			if(!empty($order)){
				$order->add_order_note($note);
			}
		}
	}

	# For product pull
	public function if_xero_item_exists_in_woo($product_data, $omc=false){
		if(is_array($product_data) && !empty($product_data)){
			$X_P_ID = $this->get_array_isset($product_data,'X_P_ID','',false);
			if(!empty($X_P_ID)){
				global $wpdb;				
				$query = $wpdb->prepare("SELECT `W_P_ID` FROM `{$this->gdtn('map_products')}` WHERE `X_P_ID` = %s AND `W_P_ID` > 0 ",$X_P_ID);
				$query_product = $this->get_row($query);				
				$r_arr = array();
				if(empty($query_product)){
					$query = $wpdb->prepare("SELECT `W_V_ID` FROM `{$this->gdtn('map_variations')}` WHERE `X_P_ID` = %s AND `W_V_ID` > 0 ",$X_P_ID);
					$query_product = $this->get_row($query);
					if(!empty($query_product)){
						$r_arr['ID'] = $query_product['W_V_ID'];
						$r_arr['is_variation'] = true;
					}
				}else{
					$r_arr['ID'] = $query_product['W_P_ID'];
				}
				
				if(!empty($r_arr)){
					$r_arr['mapped'] = true;
					return $r_arr;
				}else{
					if(!$omc && isset($product_data['Name']) && isset($product_data['Code'])){
						# Name / SKU Check
						$nr_chars = $this->x_nrc('product');
						$Name = $this->get_array_isset($product_data,'Name','',true,50,false,$nr_chars);
						$Code = $this->get_array_isset($product_data,'Code','',true,30);
						
						$post_id = 0;
						if(!empty($Code)){							
							$q = $wpdb->prepare("SELECT `post_id` FROM {$wpdb->postmeta} WHERE `meta_key` = '_sku' AND `meta_value` = %s AND `post_id` > 0 ",$Code);
							$row = $this->get_row($q);
							if(!empty($row)){
								$post_id = (int) $row['post_id'];
								
							}else{
								if(is_int($Code)){
									$post_id = $this->get_field_by_val($wpdb->posts,'ID','ID',$Code);
								}								
							}							
						}

						# Name
						if(!$post_id && !empty($Name)){
							$post_id = $this->get_field_by_val($wpdb->posts,'ID','post_title',$Name);
						}

						if($post_id > 0){
							$post_type = $this->get_field_by_val($wpdb->posts,'post_type','ID',$post_id);							
							if($post_type == 'product' || $post_type == 'product_variation'){
								$r_arr['ID'] = $post_id;
								if($post_type == 'product_variation'){
									$r_arr['is_variation'] = true;
								}								
								return $r_arr;
							}
						}
					}
				}
			}
		}
		return false;
	}
	
	public function save_wp_post($post_type,$post_data,$post_meta_arr=array(),$tax_input=array()){
		if($post_type!='' && is_array($post_data) && count($post_data)){
			$wp_error = $this->get_array_isset($post_data,'wp_error',false);
			$post_data['post_type'] = $post_type;
			
			if(isset($post_data['ID']) && (int) $post_data['ID']){
				$return = wp_update_post( $post_data ,$wp_error );
			}else{
				$return = wp_insert_post( $post_data ,$wp_error );
			}

			if((int) $return && is_array($post_meta_arr) && count($post_meta_arr)){
				$post_id = (int) $return;
				foreach($post_meta_arr as $key => $val){
					update_post_meta($post_id, $key, $val);
				}				
				
				if(is_array($tax_input) && isset($tax_input['product_cat']) && is_array($tax_input['product_cat']) && !empty($tax_input['product_cat'])){					
					wp_set_object_terms($post_id, $tax_input['product_cat'], 'product_cat',true);
					unset($tax_input['product_cat']);
				}
				
				$_product_attributes = array();
				if(is_array($tax_input) && !empty($tax_input)){
					foreach($tax_input as $tx_k => $tx_v){						
						wp_set_object_terms($post_id, $tx_v, $tx_k,true);
						
						if($this->start_with($tx_k,'pa_')){
							$_product_attributes[$tx_k] = array( 
								'name'=>$tx_k, 
								'value'=>$tx_v,
								'is_visible' => '1',
								'is_variation' => '0',
								'is_taxonomy' => '1'
							);							
						}						
					}
					
					if(!empty($_product_attributes)){
						update_post_meta( $post_id,'_product_attributes',$_product_attributes);
					}
				}
			}

			return $return;
		}
	}

	# Wc order number meta key
	public function get_woo_ord_number_meta_key(){
		$onk_f = '';
		# WooCommerce Sequential Order Numbers Pro
		if($this->is_plugin_active('woocommerce-sequential-order-numbers-pro') || $this->is_plugin_active('woocommerce-sequential-order-numbers')){
			if($this->option_checked('mw_wc_xero_sync_compt_p_wsnop')){
				if($this->is_plugin_active('woocommerce-sequential-order-numbers-pro')){
					$onk_f = '_order_number_formatted';
				}else{
					$onk_f = '_order_number';
				}
			}
		}

		return $onk_f;
	}

	# Wc order number
	public function get_woo_ord_number_from_order($order_id,$invoice_data=array()){
		$Number = '';
		$order_id = (int) $order_id;
		if($order_id > 0){
			$onk_f = $this->get_woo_ord_number_meta_key();

			if(!empty($onk_f)){
				$Number = get_post_meta($order_id,$onk_f,true);
			}

			$Number = (!empty($Number))?trim($Number):'';
		}		
		
		return $Number;
	}
	
	# Next Xero Order Number
	public function use_next_xero_order_number(){
		return $this->option_checked('mw_wc_xero_sync_use_next_xero_order_number');
	}
	
	public function get_next_xero_order_number($order_id){
		$order_id = (int) $order_id;
		if($order_id > 0){
			$Number = get_post_meta($order_id,'_myworks_xero_sync_order_number',true);
			$Number = (!empty($Number))?trim($Number):'';
			return $Number;
		}
		return '';		
	}
	
	public function get_x_f_ms_filter_datetime(){
		$datetime_m = '1969-04-05 11:23:45';
		return date('Y-m-d', strtotime($datetime_m)) . 'T' . date('H:i:s', strtotime($datetime_m));
	}
	
	# Check Woocommerce Order Exists By Xero Invoice Number
	public function check_if_woocommerce_order_exists_by_xero_iq_number($iq_number){
		if(!empty($iq_number)){			
			$meta_key_c_f = '';
			if($this->use_next_xero_order_number()){
				$meta_key_c_f = '_myworks_xero_sync_order_number';
			}else{
				$meta_key_c_f = $this->get_woo_ord_number_meta_key();
			}

			global $wpdb;
			$sql = '';
			if(!empty($meta_key_c_f)){
				$meta_key_c_f = $this->sanitize($meta_key_c_f);
				$sql = "SELECT p.ID FROM `{$wpdb->posts}` p, `{$wpdb->postmeta}` pm WHERE pm.meta_key = '{$meta_key_c_f}' AND pm.meta_value = %s AND pm.post_id = p.ID AND p.post_type = 'shop_order' ";
			}else{
				$iq_number = (int) $iq_number;
				if($iq_number > 0){
					$sql = "SELECT `ID` FROM `{$wpdb->posts}` WHERE `ID` = %d AND `post_type` = 'shop_order' ";
				}
			}

			if(!empty($sql)){
				$sql = $wpdb->prepare($sql,$iq_number);
				$wc_ord_data = $this->get_row($sql);
				if(is_array($wc_ord_data) && !empty($wc_ord_data)){
					$wc_inv_id = (int) $wc_ord_data['ID'];
					return $wc_inv_id;
				}
			}
		}

		return false;
	}

	public function get_mapping_data_from_table_multiple($wc_type,$x_type,$wc_id=0,$x_id=''){
		$map_data = array();
		$wc_id = (int) $wc_id;
		$x_id = ($x_id!='')?$this->sanitize($x_id):'';
		if(!empty($wc_type) && !empty($x_type) && ($wc_id > 0 || !empty($x_id))){
			global $wpdb;
			$m_table = $this->gdtn('map_multiple');
			$ec_q = "SELECT * FROM {$m_table} WHERE `wc_type` = %s AND `x_type` = %s";
			$ec_q = $wpdb->prepare($ec_q,$wc_type,$x_type);
			if($wc_id > 0){
				$ec_q .= " AND `wc_id` = %d";
				$ec_q = $wpdb->prepare($ec_q,$wc_id);
			}

			if(!empty($x_id)){
				$ec_q .= " AND `x_id` = %s";
				$ec_q = $wpdb->prepare($ec_q,$x_id);
			}

			$ec_q .= " LIMIT 0,1";
						
			$map_data = $this->get_row($ec_q);
		}
		
		return $map_data;
	}

	public function get_wc_product_cat_arr(){
		$cl = get_categories(array('taxonomy'=>'product_cat','orderby'=>'name','hide_empty'=>0));
		#$this->_p($cl);
		$cl_arr = array();		
		if(!empty($cl)){
			foreach($cl as $pc){
				$cl_arr[$pc->term_id] = $pc->name;
			}
		}
		return $cl_arr;
	}

	##-->[End]<--##
}