<?php
/**
 * Cron section of the plugin
 *
 * @link            
 *
 * @package  Webtoffee_Product_Feed_Sync 
 */
if (!defined('ABSPATH')) {
    exit;
}

class Webtoffee_Product_Feed_Sync_Cron
{
	public $module_id='';
	public static $module_id_static='';
	public $module_base='cron';

	/* modules associated with action types */
	public $action_modules=array('export'=>'export');

	public static $status_arr=array();
	public static $status_label_arr=array();
	public static $status_color_arr=array();

	public $to_cron='';
	private $cron_url_salt='Dyeb(DjCr<}P2c#s';

	protected $export_obj=null;
	
	public $step_description = '';
	
	public function __construct()
	{
		$this->module_id=Webtoffee_Product_Feed_Sync::get_module_id($this->module_base);
		self::$module_id_static=$this->module_id;
		self::$status_arr=array(
			'not_started'=>0, //not started yet
			'finished'=>1, //at least one completed
			'disabled'=>2, //disabled
			'running'=>3, //cron on running, eg: at least one batch completed
			'uploading'=>4, //uploading exported file
			'downloading'=>5, //downloading the file to import
		);
		self::$status_label_arr=array(
			0=>__('Not started', 'webtoffee-product-feed'),
			1=>__('Finished', 'webtoffee-product-feed'),
			2=>__('Disabled', 'webtoffee-product-feed'),
			3=>__('Running', 'webtoffee-product-feed'),
			4=>__('Uploading', 'webtoffee-product-feed'),
			5=>__('Downloading', 'webtoffee-product-feed'),
		);
		self::$status_color_arr=array(
			0=>'#337ab7', //dark blue
			1=>'#5cb85c', //green
			2=>'#f0ad4e', //orange
			3=>'#5bc0de', //light blue
			4=>'#5bc0de', //light blue
			5=>'#5bc0de', //light blue
		);		
		add_action('admin_enqueue_scripts',array($this, 'enqueue_assets'),10,1);
		
		/* altering footer buttons */
		add_filter('wt_productfeed_exporter_alter_footer_btns', array($this, 'exporter_alter_footer_btns'),10,3);
		add_filter('wt_productfeed_importer_alter_footer_btns', array($this, 'importer_alter_footer_btns'),10,3);

		/* toggling the Export, Export/Schedule button based on `Export to` option */
		add_action('wt_productfeed_toggle_schedule_btn', array($this, 'toggle_schedule_btn'), 10, 1);
		
		/* hook for `schedule now` JS action */
		add_action('wt_productfeed_custom_action', array($this, 'schedule_now'));
		
		
		/* schedule now popup html */ 
		add_action('wt_productfeed_exporter_before_head', array($this, 'schedule_now_popup_export'));
		add_action('wt_productfeed_importer_before_head', array($this, 'schedule_now_popup_import'));

		/* advanced plugin settings */
		add_filter('wt_productfeed_advanced_setting_fields', array($this, 'advanced_setting_fields'), 11);

		/* schedule main ajax hook */
		add_action('wp_ajax_pf_schedule_ajax', array($this, 'ajax_main'));
		
		add_action('wp_ajax_pf_schedule_refresh', array($this, 'refresh_catalog'));


		/* add interval time for cron */
		add_filter('cron_schedules', array($this, 'set_cron_interval'));

		/* Hook cron based on action types */
		$this->prepare_cron();

		//hook for scheduling cron
		add_action('init', array($this, 'schedule_cron'));

		/**
		* Hook for URL cron (Server cron)
		*/
		add_action('init', array($this, 'do_url_cron'));

		/* Admin menu for cron listing */
		add_filter('wt_productfeed_admin_menu', array($this, 'add_admin_pages'), 10, 1);


		add_action('init', array($this, 'test_cron'));

	}

	
	public function refresh_catalog() {
		$cron_id = (isset($_POST['cron_id']) ? absint($_POST['cron_id']) : 0);
		if ($cron_id > 0) {
			$this->do_cron('export', $cron_id);

			$out = array(
				'status' => 1,
				'msg' => __('Catalog refresh has been initiated and processing in the background', 'webtoffee-product-feed'),
			);
			echo json_encode($out);
			exit();
		}
	}

	public function test_cron()
	{
		if(defined('WT_PF_DEBUG_BASIC') && WT_PF_DEBUG_BASIC)
		{
			$action_type=(isset($_GET['action_type']) ? sanitize_text_field($_GET['action_type']) : '');
			$trigger_cron=(isset($_GET['wt_productfeed_test_cron']) ? absint($_GET['wt_productfeed_test_cron']) : 0);
			if(($action_type=="import" || $action_type=="export") && $trigger_cron==1)
			{   //echo time();
				$this->do_cron($action_type);
				exit();
			}
		}
	}

	
	/**
	*	Fields for advanced settings
	*
	*/
	public function advanced_setting_fields($fields)
	{
		$fields['default_time_zone']=array(
			'label'=>__("Switch to website timezone", 'webtoffee-product-feed'),
			'type' => 'checkbox',
			'checkbox_fields' => array( 1 => '' ),
			'value' => 0,
			'field_name' => 'default_time_zone',
			'css_class'=> 'wt_productfeed_checkbox_toggler wt_ier_toggler_blue',
			'help_text'=>__("Turn on to switch to your wesbite\'s timezone (local timezone). By default the timezone will be in UTC.", 'webtoffee-product-feed'),
			'validation_rule'=>array('type'=>'absint'),
		);
		
		return $fields;
	}	
	
	
	
	/**
	* 	Main ajax hook for all ajax actions
 	*
	*/
	public function ajax_main()
	{
		$out=array(
			'response'=>false,
			'out'=>array(),
			'msg'=>__('Error', 'webtoffee-product-feed'),
		);
		$schedule_action=(isset($_REQUEST['pf_schedule_action']) ? sanitize_text_field($_REQUEST['pf_schedule_action']) : '');
		
		if(Wt_Pf_Sh::check_write_access(WEBTOFFEE_PRODUCT_FEED_ID))
		{
			$json_actions=array('save_schedule', 'update_schedule', 'edit_schedule');
			$allowed_actions=array('save_schedule', 'list_cron', 'update_schedule', 'edit_schedule');
			if(method_exists($this, $schedule_action) && in_array($schedule_action, $allowed_actions))
			{
				$out=$this->{$schedule_action}($out);
			}
		}
		if(in_array($schedule_action, $json_actions))
		{
			echo json_encode($out);
		}
		exit();
	}

	/**
	* Adding admin menus
	*/
	public function add_admin_pages($menus)
	{
		$menus[$this->module_base]=array(
			'submenu',
			WEBTOFFEE_PRODUCT_FEED_ID,
			__('Scheduled Actions', 'webtoffee-product-feed'),
			__('Scheduled Actions', 'webtoffee-product-feed'),
			apply_filters('wt_import_export_allowed_capability', 'import'),
			$this->module_id,
			array($this, 'admin_settings_page')
		);
		return $menus;
	}

	/**
	* List cron schedules
	*
	*/
	public function list_cron()
	{
		global $wpdb;
		$tb=$wpdb->prefix.Webtoffee_Product_Feed_Sync::$cron_tb;
		$cron_list=$wpdb->get_results("SELECT * FROM $tb ORDER BY id DESC", ARRAY_A);
		$cron_list=($cron_list ? $cron_list : array());
		include plugin_dir_path(__FILE__).'views/_schedule_list.php';
	}

	/** 
	*  Schedule list page
	*/
	public function admin_settings_page($args)
	{
		if(isset($_GET['wt_productfeed_change_schedule_status']) || isset($_GET['wt_productfeed_delete_schedule'])) 
		{
			if(Wt_Pf_Sh::check_write_access(WT_IEW_PLUGIN_ID))
			{
				$cron_id=absint($_GET['wt_productfeed_cron_id']);
				if($cron_id>0)
				{
					$cron_data=self::get_cron_by_id($cron_id);
					if($cron_data)
					{
						if(isset($_GET['wt_productfeed_delete_schedule'])) /* delete schedule action */
						{
							/* deleting history entries */
							$history_arr=($cron_data['history_id_list']!="" ? maybe_unserialize($cron_data['history_id_list']) : array());
							$history_arr=(is_array($history_arr) ? $history_arr : array());
							if(count($history_arr)>0)
							{
								$history_module_obj=Webtoffee_Product_Feed_Sync::load_modules('history');
								if(!is_null($history_module_obj))
								{
									$history_module_obj->delete_history_by_id($history_arr);
								}
							}
							self::delete_cron_by_id($cron_id);
						}else
						{
							$action=sanitize_text_field($_GET['wt_productfeed_change_schedule_status']);
							if($action=='enable')
							{	
								//checking its disabled						
								if($cron_data['status']==self::$status_arr['disabled'])
								{
									$update_data=array(
										'status'=>absint($cron_data['old_status']),
									);
									$update_data_type=array('%d');
									self::updateCron($cron_id, $update_data, $update_data_type);
								}	
							}else
							{
								//checking it is already not disabled
								if($cron_data['status']!=self::$status_arr['disabled'])
								{
									$update_data=array(
										'status'=>self::$status_arr['disabled'],
										'old_status'=>$cron_data['status'],
									);
									$update_data_type=array('%d', '%d');
									self::updateCron($cron_id, $update_data, $update_data_type);
								}
							}
						}
					}
				}			
			}
		}
		include plugin_dir_path( __FILE__ ).'views/settings.php';
	}

	/**
	*  Delete cron entry from DB
	*/
	public static function delete_cron_by_id($id)
	{
		global $wpdb;
		$tb=$wpdb->prefix.Webtoffee_Product_Feed_Sync::$cron_tb;
		if(is_array($id))
		{
			$where=" IN(".implode(",", array_fill(0, count($id), '%d')).")";
			$where_data=$id;
		}else
		{
			$where="=%d";
			$where_data=array($id);
		}

		$wpdb->query( 
		    $wpdb->prepare("DELETE FROM $tb WHERE id".$where, $where_data)
		);
	}

	/**
	* Schedule cron on action types.
	*
	*/
	public function schedule_cron()
	{		
		foreach ($this->action_modules as $key => $value) 
		{
			if($this->is_cron_scheduled($key)) /* cron exists */
			{ 
				if(!wp_next_scheduled('wt_productfeed_do_cron_'.$key)) 
				{
		            $start_time=Webtoffee_Product_Feed_Sync_Common_Helper::wt_strtotimetz("now +1 minutes");
		            wp_schedule_event($start_time, 'wt_productfeed_cron_interval', 'wt_productfeed_do_cron_'.$key);
				}
			}else
			{
				if(wp_next_scheduled('wt_productfeed_do_cron_'.$key)) //its already scheduled then remove
				{
					wp_clear_scheduled_hook('wt_productfeed_do_cron_'.$key);
				}
			}
		}
	}

	/**
	* Hook cron on action types. Declare action for cron
	*
	*/
	public function prepare_cron()
	{		
		foreach ($this->action_modules as $key => $value) 
		{
			if($this->is_cron_scheduled($key)) /* cron exists */
			{
				$method_name='do_cron_'.$key;
				if(method_exists($this, $method_name)) /* method exists */
				{
					add_action('wt_productfeed_do_cron_'.$key, array($this, $method_name));
				}
			}
		}
	}
	
	/**
	*	Initiate import cron
	*/
	public function do_cron_import()
	{
		$this->do_cron('import');
	}

	/**
	*	Initiate export cron
	*/
	public function do_cron_export()
	{
		$this->do_cron('export');
	}

	/**
	* 	Registering new time interval for cron
	*/
	public function set_cron_interval($schedules)
	{		
		if($this->is_cron_scheduled()) /* cron exists */
		{
			$schedules['wt_productfeed_cron_interval'] = array(
		        'interval' => (5), //5 second
		        'display'  =>__('Every 5 second', 'webtoffee-product-feed'),
		    );
		}
		return apply_filters('wt_productfeed_cron_interval_details', $schedules);
	}

	/**
	* Checks any cron is available in the database
	*/
	private function is_cron_scheduled($action_type='')
	{
		global $wpdb;
		$tb=$wpdb->prefix.Webtoffee_Product_Feed_Sync::$cron_tb;
		$status_check_arr=self::$status_arr;
		unset($status_check_arr['disabled']);

		$db_data_arr=array_values($status_check_arr);
		$status_check_format_arr=array_fill(0, count($db_data_arr), '%d');
		
		/* preparing condition for action specified cron */
		$sql_condition='';	
		if($action_type!="")
		{
			$sql_condition=($action_type!="" ? ' AND action_type=%s' : '');
			$db_data_arr[]=$action_type;
		}
	
		$sql_condition.=' AND schedule_type=%s';
		$db_data_arr[]='wordpress_cron'; //only wordpress cron
		$qry=$wpdb->prepare(
			"SELECT COUNT(id) AS ttl FROM $tb WHERE status IN(".implode(", ", $status_check_format_arr).")".$sql_condition, 
			$db_data_arr
		);

		//taking count of available crons. 
		$cron_count_arr=$wpdb->get_row($qry, ARRAY_A);
		$cron_count=0;
		if(!is_wp_error($cron_count_arr))
		{
			$cron_count=intval(isset($cron_count_arr['ttl']) ? $cron_count_arr['ttl'] : 0);
		}
		return $cron_count;
	}

	/**
	* Popup HTML for export.
	*
	*/
	public function schedule_now_popup_export()
	{
		$this->to_cron='export';
		$this->schedule_now_popup();
	}

	/**
	* Popup HTML for export.
	*
	*/
	public function schedule_now_popup_import()
	{
		$this->to_cron='import';
		$this->schedule_now_popup();
	}

	/**
	* Popup HTML for schedule now.
	*
	*/
	public function schedule_now_popup()
	{
            if (isset($_REQUEST['wt_productfeed_cron_edit_id']) && absint($_REQUEST['wt_productfeed_cron_edit_id']) > 0) {
                            $requested_cron_edit_id = absint($_REQUEST['wt_productfeed_cron_edit_id']);
                            $cron_module_obj = Webtoffee_Product_Feed_Sync::load_modules('cron');
                            if (!is_null($cron_module_obj)) {
                                $cron_data = $cron_module_obj->get_cron_by_id($requested_cron_edit_id);
                                $cron_data=maybe_unserialize($cron_data['cron_data']);
                                include plugin_dir_path(__FILE__).'views/_schedule_update.php';
                            }
                    wp_enqueue_script($this->module_id.'_js', plugin_dir_url(__FILE__).'assets/js/main.js', array('jquery'), WT_IEW_VERSION, false);                            
            }else{
		include plugin_dir_path(__FILE__).'views/_schedule_now.php';
            }
	}

	public function enqueue_assets()
	{
		if(isset($_GET['page']))
		{
			if($_GET['page']==Webtoffee_Product_Feed_Sync::get_module_id('export') || $_GET['page']==Webtoffee_Product_Feed_Sync::get_module_id('import') || $_GET['page']==$this->module_id)
			{
				wp_enqueue_script($this->module_id, plugin_dir_url(__FILE__).'assets/js/cron.js', array('jquery'), WEBTOFFEE_PRODUCT_FEED_SYNC_VERSION, false);

				$wt_time_zone = Webtoffee_Product_Feed_Sync_Common_Helper::get_advanced_settings('default_time_zone');
				
				$params=array(
					'msgs'=>array(
						'invalid_date'=>__('Chosen date is invalid', 'webtoffee-product-feed'),
						'date_selected_info'=>__(sprintf('You have selected %d as the date but this date is not available in all months. In that case, last date of the month will be taken. Proceed?', 30), 'webtoffee-product-feed'),
						'specify_file_name'=>__('Please specify a file name.', 'webtoffee-product-feed'),
						'saving'=>__('Saving', 'webtoffee-product-feed'),
						'sure'=>__('Are you sure?', 'webtoffee-product-feed'),
						'invalid_custom_interval'=>__('Please enter a valid interval.', 'webtoffee-product-feed'),
                        'invalid_time_hr'=>__('Please enter a valid time in hours(1-12).', 'webtoffee-product-feed'),
                        'invalid_time_mnt'=>__('Please enter a valid time in minutes(0-60).', 'webtoffee-product-feed'),
						'use_url'=>__('Use the generated URL to run cron.', 'webtoffee-product-feed'),
					),
					'timestamp'=> ($wt_time_zone) ? date_i18n('Y M d h:i:s A') : date('Y M d h:i:s A'),
					'action_types'=>array_keys($this->action_modules)
				);
				wp_localize_script($this->module_id, 'wt_productfeed_cron_params', $params); 
			}

			if($_GET['page']==$this->module_id)
			{
				wp_enqueue_script($this->module_id.'_js', plugin_dir_url(__FILE__).'assets/js/main.js', array('jquery'), WT_IEW_VERSION, false);
			}
		}			
	}

	public function importer_alter_footer_btns($step_btns, $step, $steps)
	{
		if( 'advanced' !== $step ) //last step
		{
			return $step_btns;
		}
		$out=array();
		foreach($step_btns as $step_btnk=>$step_btnv)
		{
			if($step_btnk=='download') /* in import download is the primary step before import */
			{
				$out['import_schedule']=array(
					'key'=>'import_schedule',
					'icon'=>'',
					'type'=>'dropdown_button',
					'class'=>'iew_import_schedule_drp_btn',
					'text'=>__('Import/Schedule', 'webtoffee-product-feed'), 
					'items'=>array(
						$step_btnk=>$step_btnv,
						'schedule'=>array(
							'key'=>'schedule_import',
							'text'=>__('Schedule', 'webtoffee-product-feed'), //popups
						)
					)
				);
			}else
			{
				$out[$step_btnk]=$step_btnv;
			}
		}
		return $out;
	}

	/**
	*	Filter callback for schedule now/Export now button toggle
	*
	*/
	public function exporter_alter_footer_btns($step_btns, $step, $steps)
	{
		if( 'advanced' !== $step ) //last step
		{
			return $step_btns;
		}

		$out=array();
		foreach($step_btns as $step_btnk=>$step_btnv)
		{
			$out[$step_btnk]=$step_btnv;
			if($step_btnk=='export')
			{
				$out['export_schedule']=array(
					'key'=>'export_schedule',
					'icon'=>'',
					'type'=>'dropdown_button',
					'class'=>'iew_export_schedule_drp_btn',
					'text'=>__('Export/Schedule', 'webtoffee-product-feed'), 
					'items'=>array(
						$step_btnk=>$step_btnv,
						'schedule'=>array(
							'key'=>'schedule_export',
							'text'=>__('Schedule', 'webtoffee-product-feed'), //popups
						)
					)
				);
			}
		}
		return $out;
	}

	/**
	*	Javascript callback for schedule now/Export now button toggle
	*
	*/
	public function toggle_schedule_btn()
	{
		?>
		wt_productfeed_cron.toggle_schedule_btn(state);
		<?php
	}

	/**
	*	Javascript callback for schedule now
	*
	*/
	public function schedule_now()
	{
		?>
		wt_productfeed_cron.schedule_now(ajx_dta, action, id);
		<?php
	}

	/**
	*  Do the cron
	*/
	public function do_cron($action_type, $cron_id=0)
	{	

		global $wpdb;
		if($action_type=='')
		{
			return '';
		}

		/* modules associated with action types */
		$action_modules=$this->action_modules;
		
		/* checking corresponding module exists */
		if(!isset($action_modules[$action_type]))
		{
			return '';
		}

		/* checking corresponding module available/active */
		if(!Webtoffee_Product_Feed_Sync_Admin::module_exists($action_modules[$action_type])) 
		{
			return;
		}
                
                /**
                 * @since 1.1.2
                 * To control the cron run
                 */
                $args = array('action_type'=>$action_type,'cron_id'=>$cron_id);
                if(!apply_filters('wt_productfeed_run_cron',true,$args)){ 
                    return;
                }

		$tb=$wpdb->prefix.Webtoffee_Product_Feed_Sync::$cron_tb;
		
		$tme=Webtoffee_Product_Feed_Sync_Common_Helper::wt_strtotimetz('now');
		$is_parallel=0; //allow parallel cron on single request
		$limit_sql=($is_parallel==0 ? ' LIMIT 1' : '');

		/* 	taking cron details from db. 
		*	Takes all data that have status running
		*	Takes data that have status not started/finshed will take based on the startime
		*	If id given then take that record only with above condition
		*/
		if($cron_id==0)
		{
			$qry=$wpdb->prepare(
				"SELECT * FROM $tb WHERE ( ( (status= %d OR  status= %d) AND start_time <= %d ) OR status IN(%d, %d, %d) ) AND action_type=%s AND schedule_type=%s ORDER BY start_time ASC".$limit_sql, 
				array(
					self::$status_arr['not_started'],
					self::$status_arr['finished'],
					$tme,
					self::$status_arr['running'],
					self::$status_arr['uploading'],
					self::$status_arr['downloading'],
					$action_type,
					'wordpress_cron',
				)
			);
		}
		else /* cron id exists */
		{
                        $force_refresh = isset($_POST['force_refresh']) ? (bool)($_POST['force_refresh']) : 0;
                        if($force_refresh){
                            $qry=$wpdb->prepare(
                                    "SELECT * FROM $tb WHERE ( ( status= %d OR  status= %d ) OR status IN(%d, %d, %d) ) AND action_type=%s AND history_id=%d", 
                                    array(
                                            self::$status_arr['not_started'],
                                            self::$status_arr['finished'],
                                            self::$status_arr['running'],
                                            self::$status_arr['uploading'],
                                            self::$status_arr['downloading'],
                                            $action_type,
                                            $cron_id,
                                    )
                            );
                        }else{
                            $qry=$wpdb->prepare(
                                    "SELECT * FROM $tb WHERE ( ( (status= %d OR  status= %d) AND start_time <= %d ) OR status IN(%d, %d, %d) ) AND action_type=%s AND history_id=%d", 
                                    array(
                                            self::$status_arr['not_started'],
                                            self::$status_arr['finished'],
                                            $tme,
                                            self::$status_arr['running'],
                                            self::$status_arr['uploading'],
                                            self::$status_arr['downloading'],
                                            $action_type,
                                            $cron_id,
                                    )
                            );                            
                        }
		}

		//taking list of available crons. 
		$cron_list=$wpdb->get_results($qry, ARRAY_A);	
                
		//if cron found
		if($cron_list)
		{ 
			$action_module=Webtoffee_Product_Feed_Sync::load_modules($action_modules[$action_type]);
			
			if(!defined( 'WT_PRODUCT_FEED_CRON' )) /* cron is running, this is used in log module to add prefix to identify cron log */
			{
			    define ( 'WT_PRODUCT_FEED_CRON', true );
			}

			foreach($cron_list as $cron_listv) 
			{
				if(defined('WT_PF_DEBUG_BASIC') && WT_PF_DEBUG_BASIC) /* debug */
				{
					// phpcs:ignore
					echo "<pre>";
					print_r($cron_listv);
					echo "</pre><br />"; 					
				}

				if($cron_listv['history_id']>0)
				{
					/* no need to send formdata. It will take from history table by `process_action` method */
					$form_data=array();
				}else
				{
					$form_data=maybe_unserialize($cron_listv['data']);
				}

				$cron_data=maybe_unserialize($cron_listv['cron_data']);
				$file_name=(isset($cron_data['file_name']) ?  $cron_data['file_name'] : '');
				if($cron_listv['status']==self::$status_arr['finished'] || $cron_listv['status']==self::$status_arr['not_started'])
				{
					if($cron_listv['action_type']=='import')
					{
						$out=$action_module->process_download($form_data, 'download', $cron_listv['item_type'], $cron_listv['history_id'], $cron_listv['next_offset']);
					}else
					{
						$out=$action_module->process_action($form_data, $action_type, $cron_listv['item_type'], $file_name, $cron_listv['history_id'], $cron_listv['next_offset']);
					}

				}elseif($cron_listv['status']==self::$status_arr['running'])
				{
					$out=$action_module->process_action($form_data, $action_type, $cron_listv['item_type'], $file_name, $cron_listv['history_id'], $cron_listv['next_offset']);
				
				}elseif($cron_listv['status']==self::$status_arr['uploading'])
				{
					$out=$action_module->process_upload('upload', $cron_listv['history_id'], $cron_listv['item_type']);
				
				}elseif($cron_listv['status']==self::$status_arr['downloading'])
				{
					$out=$action_module->process_download($form_data, $action_type, $cron_listv['item_type'], $cron_listv['history_id'], $cron_listv['next_offset']);
				}				


				/**
				* 	Prepare for next run
				*/			
				$update_data=array(
						'last_run'=> Webtoffee_Product_Feed_Sync_Common_Helper::wt_strtotimetz('now'),
						'history_id'=>$out['history_id']
					);
				$update_data_type=array('%d','%d','%d','%d');

				if($out['response']===false) //An error. Skip this cron and prepare for next run
				{
					$this->prepare_for_next_run($update_data, $update_data_type, $cron_listv, $out);
				}else
				{					
					if(isset($out['finished']) && $out['finished']==1) //finshed the export/import batching
					{
                                                do_action('wt_ier_scheduled_action_finished', $out);
						$this->prepare_for_next_run($update_data, $update_data_type, $cron_listv, $out);
					}
					elseif(isset($out['finished']) && $out['finished']==2) //finshed the export, now need uploading
					{
						//update the status and reset the offset
						$update_data['status']=self::$status_arr['uploading']; //upload the exported data
						$update_data['next_offset']=0; //reset the offset
					}
					elseif(isset($out['finished']) && $out['finished']==3) //starting the import, file to download and processing was done
					{
						//update the status and reset the offset
						$update_data['status']=self::$status_arr['running']; //do import
						$update_data['next_offset']=0; //reset the offset
					}
					else //not finished, more batches are pending
					{	
						if($cron_listv['action_type']=='export')
						{
							$new_status=self::$status_arr['running'];
						}else
						{
							if($cron_listv['status']==self::$status_arr['running'])
							{
								$new_status=self::$status_arr['running']; //continue import
							}else
							{
								$new_status=self::$status_arr['downloading']; //continue download
							}
						}

						//update the status and reset the offset
						$update_data['status']=$new_status; //waiting for next batch
						$update_data['next_offset']=$out['new_offset']; //save the next offset
					}


					/* first execution, then update the ID in history id list */
					if($cron_listv['history_id']==0) 
					{
						$history_id_list=($cron_listv['history_id_list']!="" ? maybe_unserialize($cron_listv['history_id_list']) : array());
						$history_id_list=(!is_array($history_id_list) ? array() : $history_id_list);
						$history_id_list[]=$out['history_id']; //history id from import/export module

						$update_data['history_id_list']=maybe_serialize($history_id_list);
						$update_data_type[]='%s';
					}
				}

				if(defined('WT_PF_DEBUG_BASIC') && WT_PF_DEBUG_BASIC) /* debug */
				{
					// phpcs:ignore
					echo "<pre>";
					print_r($out);
					echo "</pre><br />"; 
					
				}

				/**
				* 	Update cron DB entry
				*/
				$this->updateCron($cron_listv['id'], $update_data, $update_data_type);
			}
		}
	}

	/**
	*	Prepare for next cron (Not batch)
	*	@param array $update_data data to be updated in cron table
	*	@param array $update_data_type for data be updated in cron table
	*	@param array $cron_listv cron DB record
	*	@param array $action_module_out output from action module Eg: export response from export module
	*/
	private function prepare_for_next_run(&$update_data, &$update_data_type, $cron_listv, $action_module_out)
	{
		//update the status and reset the offset
		$update_data['status']=self::$status_arr['finished']; //waiting for next run
		$update_data['next_offset']=0; //reset the offset

		//add next start time based on interval type
		$cron_data=maybe_unserialize($cron_listv['cron_data']);
		$prev_start_time=$cron_listv['start_time'];
		$update_data['start_time']=self::prepare_start_time($cron_data, $prev_start_time);
		$update_data_type[]='%d';
		/*						
		$update_data['history_id']=0; //resetting the hostory id, Otherwise next cron will use same history entry

		//clear formdata from history table to avoid data duplication
		$history_update_data=array( 
			'data'=>''
		);
		$history_update_data_type=array(
			'%s'
		);
		Webtoffee_Product_Feed_Sync_History::update_history_entry($action_module_out['history_id'], $history_update_data, $history_update_data_type);
		 * 
		 */
	}

	public static function get_cron_by_id($cron_id)
	{
		global $wpdb;
		$tb=$wpdb->prefix.Webtoffee_Product_Feed_Sync::$cron_tb;
		$qry=$wpdb->prepare(
			"SELECT * FROM $tb WHERE id=%d", 
			array($cron_id)
		);

		//taking cron data. 
		$cron_arr=$wpdb->get_row($qry, ARRAY_A);
		if(!is_wp_error($cron_arr))
		{
			return $cron_arr;
		}else
		{
			return false;
		}
	}
	
	public static function get_cron_by_history_id($history_id)
	{
		global $wpdb;
		$tb=$wpdb->prefix.Webtoffee_Product_Feed_Sync::$cron_tb;
		$qry=$wpdb->prepare(
			"SELECT * FROM $tb WHERE history_id=%d", 
			array($history_id)
		);

		//taking cron data. 
		$cron_arr=$wpdb->get_row($qry, ARRAY_A);
		if(!is_wp_error($cron_arr))
		{
			return $cron_arr;
		}else
		{
			return false;
		}
	}

	/**
	*	Update the cron data when running
	*
	*/
	public static function updateCron($cron_id, $update_data, $update_data_type)
	{
		global $wpdb;
		$tb=$wpdb->prefix.Webtoffee_Product_Feed_Sync::$cron_tb;
		$update_where=array(
			'id'=>$cron_id
		);
		$update_where_type=array(
			'%d'
		);
		if($wpdb->update($tb, $update_data, $update_where, $update_data_type, $update_where_type)!==false)
		{
			return true;
		}
		return false;
	}

	/**
	* 	Prepare start time timestamp
	*
	*/
	private static function prepare_start_time($cron_data, $last_start_time=0)
	{
		$time_vl=$cron_data['start_time'];
		$tme=Webtoffee_Product_Feed_Sync_Common_Helper::wt_strtotimetz('now');
		//$m=date('n');
		$M= date_i18n('M');
		$y=date_i18n('Y');
		$d=date_i18n('d');
		//$t=date('t');
		$out=0;
		if($cron_data['interval']=='monthly')
		{
			if($cron_data['date_vl']=='last_day')
			{
				$time_stamp=Webtoffee_Product_Feed_Sync_Common_Helper::wt_strtotimetz("$time_vl Last day of +0 Month");
				if($time_stamp<$tme)
				{
					$out=Webtoffee_Product_Feed_Sync_Common_Helper::wt_strtotimetz("$time_vl Last day of +1 Month");
				}else
				{
					$out=$time_stamp;	
				}
			}else
			{		
				$date_vl=$cron_data['date_vl'];
				$time_stamp=Webtoffee_Product_Feed_Sync_Common_Helper::wt_strtotimetz("$time_vl $y-$M-$date_vl");
				if($time_stamp<$tme)
				{ 
					$out=Webtoffee_Product_Feed_Sync_Common_Helper::wt_strtotimetz("+1 Month", $time_stamp);
				}else
				{
					$out=$time_stamp;
				}
			}
		}elseif($cron_data['interval']=='weekly')
		{
			$day_vl=$cron_data['day_vl'];
			$time_stamp=Webtoffee_Product_Feed_Sync_Common_Helper::wt_strtotimetz("This week $day_vl $time_vl");
			if($time_stamp<$tme)
			{
				$out=Webtoffee_Product_Feed_Sync_Common_Helper::wt_strtotimetz("Next week $day_vl $time_vl");
			}else
			{
				$out=$time_stamp;	
			}
		}elseif($cron_data['interval']=='daily')
		{
			$time_stamp=Webtoffee_Product_Feed_Sync_Common_Helper::wt_strtotimetz($time_vl);
			if($time_stamp<$tme)
			{
				$out=Webtoffee_Product_Feed_Sync_Common_Helper::wt_strtotimetz("+1 day $time_vl");
			}else
			{
				$out=$time_stamp;
			}
		}elseif($cron_data['interval']=='hourly')
		{
			$time_stamp=Webtoffee_Product_Feed_Sync_Common_Helper::wt_strtotimetz($time_vl);
			if($time_stamp<$tme)
			{
				$out=Webtoffee_Product_Feed_Sync_Common_Helper::wt_strtotimetz("+1 hour $time_vl");
			}else
			{
				$out=$time_stamp;
			}
		}else
		{
			$custom_interval=$cron_data['custom_interval']; //in minutes
			$custom_interval_sec=($custom_interval*60); //in seconds
			if($last_start_time==0) //first time
			{
				$time_stamp=Webtoffee_Product_Feed_Sync_Common_Helper::wt_strtotimetz($time_vl);
				if($time_stamp < $tme) 
				{
                                    $out=Webtoffee_Product_Feed_Sync_Common_Helper::wt_strtotimetz("+1 day $time_vl");

				}else
				{	
					$out=$time_stamp;
				}
			}else
			{		
				$next_start_time=($last_start_time+$custom_interval_sec);
				if($next_start_time < $tme)
				{
					$interval_diff=($tme-$next_start_time);
					$out=$next_start_time+((ceil($interval_diff/$custom_interval_sec)-1)*$custom_interval_sec);
				}else
				{
					$out=$next_start_time;
				}
			}
		}

		return $out;
	}

	/**
	*  Save the cron data
	*
	*/
	public function save_schedule($out)
	{
		global $wpdb;
		$cron_data=(isset($_POST['schedule_data']) ? Wt_Pf_Sh::sanitize_item($_POST['schedule_data'], 'text_arr') : null );
		if(!$cron_data)
		{
			return $out;
		}

		/* sanitize the file name */
		$cron_data['file_name']=(isset($cron_data['file_name']) ? sanitize_file_name($cron_data['file_name']) : '');

		$tb=$wpdb->prefix.Webtoffee_Product_Feed_Sync::$cron_tb;
		$start_time=self::prepare_start_time($cron_data);
		if($start_time==0)
		{
			return $out;
		}

		$item_type=sanitize_text_field($_POST['item_type']);
		$action_type=sanitize_text_field($_POST['schedule_action']);

		if(!isset($this->action_modules[$action_type])) //not in the allowed action list
		{
			return $out;
		}

		/* process form data */
		$form_data=(isset($_POST['form_data']) ? Webtoffee_Product_Feed_Sync_Common_Helper::process_formdata(maybe_unserialize(($_POST['form_data']))) : array());

		/* loading export module class object */
		$this->module_obj=Webtoffee_Product_Feed_Sync::load_modules($action_type);
		
		if(!is_null($this->module_obj))
		{
			//sanitize form data
			$form_data=Wt_Iew_IE_Helper::sanitize_formdata($form_data, $this->module_obj);
		}		

		$insert_data=array(
			'action_type'=>$action_type,
			'item_type'=>$item_type,
			'schedule_type'=>$cron_data['schedule_type'],
			'data'=>maybe_serialize($form_data),
			'start_time'=>$start_time,  //next cron start time
			'cron_data'=>maybe_serialize($cron_data),  //cron settings data Eg: Cron interval type
			'last_run'=>0, //first time, not started yet
			'history_id'=>0, //first time, not started yet, it will added on first run
			'status'=>self::$status_arr['not_started'], //not started yet status
			'next_offset'=>0,
			'history_id_list'=>maybe_serialize(array()),
		);
		$insert_data_type=array('%s', '%s', '%s', '%s', '%d', '%s', '%d', '%d', '%d', '%d', '%s');

		if($wpdb->insert($tb, $insert_data, $insert_data_type)) //success
		{
			$cron_id=$wpdb->insert_id;
			$out=array(
				'response'=>true,
				'out'=>array(),
				'msg'=>__('Success'),
			);
			if($cron_data['schedule_type']=='server_cron')
			{
				$out['cron_url']=$this->generate_cron_url($cron_id, $action_type, $item_type);
			}		
		}
		return $out;
	}
	
	
	
	/**
	*  Save the cron data
	*
	*/
	public function add_schedule($out, $form_data)
	{

		$cron_data = array();
		$cron_data['file_name'] = sanitize_file_name($form_data['post_type_form_data']['item_filename']);
		$cron_data['start_time'] = date("g:i a", strtotime("now"));
		$cron_data['interval'] = $form_data['post_type_form_data']['item_gen_interval'];
		$cron_data['schedule_type'] = 'wordpress_cron';
		$cron_data['date_vl']= date('j');
		$cron_data['day_vl'] = strtolower(date('D'));
		
		global $wpdb;

		$tb=$wpdb->prefix.Webtoffee_Product_Feed_Sync::$cron_tb;
		$start_time=self::prepare_start_time($cron_data);
		if($start_time==0)
		{
			return $out;
		}

		$item_type=$form_data['post_type_form_data']['item_type'];
		$action_type= 'export';

		if(!isset($this->action_modules[$action_type])) //not in the allowed action list
		{
			return $out;
		}

		/* process form data */
		
		$insert_data=array(
			'action_type'=>'export',
			'item_type'=>$form_data['post_type_form_data']['item_type'],
			'schedule_type'=>'wordpress_cron',
			'data'=>maybe_serialize($form_data),
			'start_time'=>$start_time,  //next cron start time
			'cron_data'=>maybe_serialize($cron_data),  //cron settings data Eg: Cron interval type
			'last_run'=>0, //first time, not started yet
			'history_id'=> isset($out['history_id']) ? $out['history_id'] : 0, //first time, not started yet, it will added on first run
			'status'=>self::$status_arr['not_started'], //not started yet status
			'next_offset'=>0,
			'history_id_list'=>maybe_serialize(array()),
		);
		$insert_data_type=array('%s', '%s', '%s', '%s', '%d', '%s', '%d', '%d', '%d', '%d', '%s');

				
		if(isset($out['history_id']) && $out['history_id'] > 0){
			$cron_details = self::get_cron_by_history_id($out['history_id']);
			if($cron_details){
				$insert_data['id'] = $cron_details[ 'id' ];
				$wpdb->update($tb, $insert_data, array('id' => $cron_details[ 'id' ]));
				return $out;
			}
		}
		
		
		if($wpdb->insert($tb, $insert_data, $insert_data_type)) //success
		{
			$cron_id=$wpdb->insert_id;
			$out=array(
				'response'=>true,
				'out'=>array(),
				'msg'=>__('Success'),
			);
			if($cron_data['schedule_type']=='server_cron')
			{
				$out['cron_url']=$this->generate_cron_url($cron_id, $action_type, $item_type);
			}		
		}
		
		return $out;
	}
	
	
	/**
	 *  populate the cron data
	 *
	 */
	public function edit_schedule( $out ) {

		global $wpdb;				
		$cron_id = (isset($_POST['cron_id']) ? Wt_Pf_Sh::sanitize_item($_POST['cron_id'], 'int') : null );
		if(!$cron_id)
		{
			return $out;
		}
		$cron_details = self::get_cron_by_id( $cron_id );
		if ( $cron_details ) {

			$cron_form_data		 = maybe_unserialize( $cron_details[ 'data' ] ); //cron settings data Eg: Cron interval type
			$advanced_form_data= $cron_form_data[ 'advanced_form_data' ];
			$action_type = $cron_details['action_type'];
			$method_action_type_form_data_holder = "method_{$action_type}_form_data";
			$method_action_type_form_data =  $cron_form_data[ $method_action_type_form_data_holder ];
			$update_data = array(
				'id'			 => $cron_details[ 'id' ],
				'action_type'	 => $action_type,
				'item_type'		 => $cron_details['item_type'],
				'schedule_type'	 => $cron_details[ 'schedule_type' ],
				'cron_data'	 => maybe_unserialize( $cron_details[ 'cron_data' ] ),
				"method_{$action_type}_form_data"		 => $method_action_type_form_data,
				'advanced_form_data'		 =>  $advanced_form_data ,
			);
			
			$step_info = array( 'title' => ' ', 'description' => ' ' );
			
			$action_type_base_holder = ucfirst($action_type);
			$action_type_base_class = 	"Webtoffee_Product_Feed_Sync_{$action_type_base_holder}";			
			$action_type_base_object = 	new $action_type_base_class();
			
			if( is_object( $action_type_base_object )){
			if($action_type == 'export'){
			$action_type_base_object->to_export = $cron_details['item_type'];
			}else{
				$action_type_base_object->to_import = $cron_details['item_type'];
			}

			$advanced_screen_fields=$action_type_base_object->get_advanced_screen_fields($advanced_form_data);
			
			ob_start();		
			include_once dirname(plugin_dir_path(__FILE__))."/{$action_type}/views/_{$action_type}_advanced_page.php";
			$advanced_form_edit =  ob_get_clean();
			$out['advanced_form_edit_html'] = $advanced_form_edit;	
			$out['data'] = $update_data;
		}
		}
		return $out;
	}
	
	
	/**
	 *  update the cron data
	 *
	 */
	public function update_schedule($out) {


        global $wpdb;
        $cron_data = (isset($_POST['schedule_data']) ? Wt_Pf_Sh::sanitize_item($_POST['schedule_data'], 'text_arr') : null );
        if (!$cron_data) {
            return $out;
        }
        $cron_id = absint($_POST['cron_id']);
        $cron_details = self::get_cron_by_id($cron_id);
        if (!$cron_details) {

            $out = array(
                'msg' => __('Couldn\'t find selected schedule.'),
            );
            return $out;
        }

        /* sanitize the file name */
        $cron_data['file_name'] = (isset($cron_data['file_name']) ? sanitize_file_name($cron_data['file_name']) : '');

        $tb = $wpdb->prefix . Webtoffee_Product_Feed_Sync::$cron_tb;
        $start_time = self::prepare_start_time($cron_data);

        if ($start_time == 0) {
            return $out;
        }

        $item_type = sanitize_text_field($cron_details['item_type']);
        $action_type = sanitize_text_field($cron_details['action_type']);

        if (!isset($this->action_modules[$action_type])) { //not in the allowed action list
            return $out;
        }
        $cron_form_details = maybe_unserialize($cron_details['data']);

        /* process form data */
        $form_data = (isset($_POST['form_data']) ? Webtoffee_Product_Feed_Sync_Common_Helper::process_formdata(maybe_unserialize(($_POST['form_data']))) : array());

        /* loading export module class object */
        $this->module_obj = Webtoffee_Product_Feed_Sync::load_modules($action_type);

        if (!is_null($this->module_obj)) {
            //sanitize form data
            $form_data = Wt_Iew_IE_Helper::sanitize_formdata($form_data, $this->module_obj);
        }

        if ($action_type == 'export') {
            $method_from_data = $cron_form_details['method_export_form_data'];
            $form_data['method_export_form_data'] = $method_from_data;
        } else {
            $method_from_data = $cron_form_details['method_import_form_data'];
            $form_data['method_import_form_data'] = $method_from_data;
        }

        $update_data = array(
            'id' => $cron_id,
            'schedule_type' => $cron_data['schedule_type'],
            'data' => maybe_serialize($form_data),
            'start_time' => $start_time, //next cron start time
            'cron_data' => maybe_serialize($cron_data),
        );

        $out = array(
            'response' => true,
            'out' => array(),
            'msg' => __('Schedule updated successfully'),
        );
        if ($wpdb->update($tb, $update_data, array('id' => $cron_id))) { //success
            if ($cron_data['schedule_type'] == 'server_cron') {

                $out['cron_url'] = $this->generate_cron_url($cron_id, $action_type, $item_type);
            }
        }

        return $out;
    }

    public function do_url_cron()
	{
		if(isset($_GET['wt_productfeed_url_cron']))
		{
			$cron_id=absint($_GET['wt_productfeed_url_cron']);
			$action_type=(isset($_GET['a']) ? sanitize_text_field($_GET['a']) : '');
			$item_type=(isset($_GET['i']) ? sanitize_text_field($_GET['i']) : '');
			$hash=(isset($_GET['h']) ? sanitize_text_field($_GET['h']) : '');
			$tme=(isset($_GET['t']) ? absint($_GET['t']) : '');
			
			if($cron_id>0 && $action_type!="" && $item_type!="" && $hash!="" && $tme>0)
			{
				/* check the hash is matching */
				$expected_hash=$this->generate_hash_for_url($cron_id, $tme, $action_type);
				if($expected_hash==$hash) 
				{
					global $wpdb;
					$tb=$wpdb->prefix.Webtoffee_Product_Feed_Sync::$cron_tb;
					$db_data_arr=array(self::$status_arr['disabled'], $cron_id, $action_type, $item_type);
					$qry=$wpdb->prepare(
						"SELECT * FROM $tb WHERE status!=%d AND id=%d AND action_type=%s AND item_type=%s", 
						$db_data_arr
					);
					//checking cron exists. 
					$cron_count_arr=$wpdb->get_row($qry, ARRAY_A);
					if(!is_wp_error($cron_count_arr))
					{
						$this->do_cron($action_type, $cron_id);
					}
				}
			}
			exit();
		}
	}

	/**
	*
	*	Generate hash for URL cron.
	*/
	private function generate_hash_for_url($id, $tme, $action_type)
	{
		return md5($tme.'_'.$this->cron_url_salt.'-'.$id.$action_type);
	}

	/**
	*
	*	Generate URL for URL cron.
	*/
	private function generate_cron_url($id, $action_type, $item_type)
	{

		$tme=Webtoffee_Product_Feed_Sync_Common_Helper::wt_strtotimetz('now');
		$hash=$this->generate_hash_for_url($id, $tme, $action_type);
		return site_url('?wt_productfeed_url_cron='.$id.'&a='.$action_type.'&i='.$item_type.'&h='.$hash.'&t='.$tme);
	}
}
Webtoffee_Product_Feed_Sync::$loaded_modules['cron']=new Webtoffee_Product_Feed_Sync_Cron();