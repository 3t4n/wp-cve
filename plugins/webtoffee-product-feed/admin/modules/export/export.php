<?php
/**
 * Export section of the plugin
 *
 * @link            
 *
 * @package  Webtoffee_Product_Feed_Sync_Export
 */
if (!defined('ABSPATH')) {
    exit;
}
if(!class_exists('Webtoffee_Product_Feed_Sync_Export')){
class Webtoffee_Product_Feed_Sync_Export
{
	public $module_id='';
	public static $module_id_static='';
	public $module_base='export';

	public static $export_dir=WP_CONTENT_DIR.'/uploads/webtoffee_product_feed';
	public static $export_dir_name='/webtoffee_product_feed';
	public $steps=array();
	public $allowed_export_file_type=array();
	
	private $to_export='';
	private $to_export_id='';
	private $rerun_id=0;
	public $export_method='';
	public $export_methods=array();
	public $selected_template=0;
	public $default_batch_count=0; /* configure this value in `advanced_setting_fields` method */
	public $selected_template_data=array();
	public $default_export_method='';  /* configure this value in `advanced_setting_fields` method */
	public $use_bom = true;
	public $form_data=array();
	public $validation_rule;
	public $step_need_validation_filter;

	public function __construct()
	{
		$this->module_id=Webtoffee_Product_Feed_Sync::get_module_id($this->module_base);
		self::$module_id_static=$this->module_id;

		/* allowed file types */
		$this->allowed_export_file_type=array(
			'xml'=>__('XML'),			
			'csv'=>__('CSV'),
                        'xlsx'=>__('XLSX'),
                        'tsv'=>__('TSV'),
                        'txt'=>__('TXT')
		);

		/* default step list */
		$this->steps=array
		(
			'post_type'=>array(
				'title'=>__('Create new feed'),
				'description'=>__('Fill the basic feed settings to proceed.'),
			),
			/*
			'method_export'=>array(
				'title'=>__('Select an export method'),
				'description'=>__('Choose from the options below to continue with your export: quick export from DB, based on a pre-saved template or a new export with advanced options.'),
			),
			'filter'=>array(
				'title'=>__('Filter data'),
				'description'=>__('Filter data that needs to be exported as per the below criteria.'),
			), 
			 * 
			 */
			'mapping'=>array(
				'title'=>__('Attribute mapping'),
				'description'=>__('Map the attributes of catalog feed corresponding to woocommerce.'),
			),
			'category_mapping'=>array(
				'title'=>__('Category mapping'),
				'description'=>__('Map the categories of catalog feed corresponding to merchant.'),
			),			
			'advanced'=>array(
				'title'=>__('Generate feed'),
				'description'=> '',
			),
		);


		$this->validation_rule=array(
			'post_type'=>array(), /* no validation rule. So default sanitization text */
			'method_export'=>array(
				'mapping_enabled_fields' => array('type'=>'text_arr') //in case of quick export
			)
		);

		$this->step_need_validation_filter=array('filter', 'advanced');


		$this->export_methods=array(
			'quick'=>array('title'=>__('Quick export'), 'description'=> __('Exports all the basic fields.')),
			'template'=>array('title'=>__('Pre-saved template'), 'description'=> __('Exports data as per the specifications(filters,selective column,mapping etc) from the previously saved file.')),
			'new'=>array('title'=>__('Advanced export'), 'description'=> __('Exports data after a detailed process of filtration, column selection and advanced options. The configured settings can be saved as a template for future exports.')),
		);

		/* advanced plugin settings */
		add_filter('wt_pf_advanced_setting_fields_basic', array($this, 'advanced_setting_fields'), 11);

		/* setting default values, this method must be below of advanced setting filter */
		$this->get_defaults();

		/* main ajax hook. The callback function will decide which is to execute. */

		add_action('wp_ajax_pf_export_ajax_basic', array($this, 'ajax_main'), 11);
		
		/* Admin menu for 
		 * export */
		add_filter('wt_pf_admin_menu_basic', array($this, 'add_admin_pages'), 10, 1);

		/* Download export file via nonce URL */
		add_action('admin_init', array($this, 'download_file'), 11);
                
                
                
                add_filter( 'woocommerce_product_data_tabs', array( $this, 'product_data_tabs' ) );
                add_action( 'woocommerce_product_data_panels', array( $this, 'product_data_panels' ) );
                add_action( 'woocommerce_process_product_meta', array( $this, 'save_product_data' ), 15 );

                add_action( 'woocommerce_product_after_variable_attributes', array( $this, 'wt_feed_variable_custom_meta_fields' ), 10, 3 );
                add_action( 'woocommerce_save_product_variation', array( $this, 'wt_feed_save_variable_metas' ), 10, 1 );
                // Style the tab icons.
                add_action( 'admin_enqueue_scripts', array( $this,'wt_feed_tab_styles'), 11 );
                
                
                add_action('wp_ajax_populate_cat_mapping', array($this, 'populate_cat_mapping'), 11);
                
	}
        
        public function populate_cat_mapping(){
            if(Wt_Pf_Sh::check_write_access(WEBTOFFEE_PRODUCT_FEED_ID)){
                
                $mathc_case = isset($_POST['term']) ? sanitize_text_field( wp_unslash( $_POST['term']) ) : '';
                $channel_type = isset($_POST['to_export']) ? sanitize_text_field( wp_unslash( $_POST['to_export']) ) : 'google';                
                $category_found = self::get_category_array($channel_type, $mathc_case);
                wp_send_json( $category_found );
            }
        }
        
	/**
         * Read txt file which contains google/facebook taxonomy list
         *
         * @return array
         */
        public static function get_category_array($channel_type, $match_cat = false) {
            // Get All Google||FB Taxonomies

            if('facebook' === $channel_type){
                $fileName = WT_PRODUCT_FEED_PLUGIN_PATH . '/admin/modules/facebook/data/fb_taxonomy.txt';
                $exploder = ',';
            }else{
                $fileName = WT_PRODUCT_FEED_PLUGIN_PATH . '/admin/modules/google/data/google_taxonomy.txt';
                $exploder = '-';                
            }
            $customTaxonomyFile = fopen($fileName, 'r');  // phpcs:ignore
            $taxonomy = array();
            $taxonomy[''] = 'Do not map';
            if ($customTaxonomyFile) {
                // First line contains metadata, ignore it
                fgets($customTaxonomyFile);  // phpcs:ignore
                while ($line = fgets($customTaxonomyFile)) {  // phpcs:ignore
                    list( $catId, $cat ) = explode($exploder, $line);
                    $cat_key = absint(trim($catId));
                    $cat_val = trim($cat);
                    $taxonomy[$cat_key] = $cat_val;
                }
            }
            
            $result = preg_grep("/$match_cat/i", $taxonomy);
            return $result;
        }

        public function get_defaults()
	{	
		$this->default_export_method= Webtoffee_Product_Feed_Sync_Common_Helper::get_advanced_settings('default_export_method');
		$this->default_batch_count=Webtoffee_Product_Feed_Sync_Common_Helper::get_advanced_settings('default_export_batch');
		$this->use_bom = (bool)Webtoffee_Product_Feed_Sync_Common_Helper::get_advanced_settings('include_bom');
	}


	
	/**
	*	Fields for advanced settings
	*
	*/
	public function advanced_setting_fields($fields)
	{
		$export_methods=array_map(function($vl){ return $vl['title']; }, $this->export_methods);
		
		
		
		$fields['default_export_batch']=array(
			'label'=>__("Default feed batch count"),
			'type'=>'number',
            'value' =>30,
			'field_name'=>'default_export_batch',
			'help_text'=>__('Provide the default count for the records to be generated in a batch.'),
			'validation_rule'=>array('type'=>'absint'),
			'attr' => array('min' => 1, 'max' => 200),
		);
		$fields['all_shipping_zone']=array(
			'label'=>__("Add shipping costs of all countries to the feed"),
			'type' => 'checkbox',
			'checkbox_fields' => array( 1 => '' ),
			'value' => 0,
			'field_name' => 'all_shipping_zone',
			'css_class'=> 'wt_pf_checkbox_toggler wt_pf_toggler_blue',
			'help_text'=>__( 'Disabling the option will add the shipping cost of only the specified country to the feed.' ),
			'validation_rule'=>array('type'=>'absint'),
		);
                
               $fb_catalogs = get_option(WT_Fb_Catalog_Manager_Settings::OPTION_FB_CATALOG_ID, array());
               if (!empty($fb_catalogs)) {
                   $fields['wt_pf_default_catalog'] = array(
                       'label' => __('Default catalog'),
                       'field_name' => 'default_catalog',
                       'sele_vals' => $fb_catalogs,
                       'help_text' => __('This catalog ID will be used to delete product from facebook when a product is trashed.'),
                       'type' => 'select',
                       'validation_rule' => array('type' => 'text_arr')
                   );
               }                
              
		return $fields;
	}

	/**
	* Adding admin menus
	*/
	public function add_admin_pages($menus)
	{		
		$menu_temp=array(
			$this->module_base=>array(
				'menu',
				__('Create new feed'),
				__('WebToffee Product Feed'),
				apply_filters('wt_import_export_allowed_capability', 'export'),
				$this->module_id,
				array($this,'admin_settings_page'),
				'dashicons-cart',
				56
			),
			$this->module_base.'-sub'=>array(
				'submenu',
				$this->module_id,
				__('Create new feed'),
				__('Create new feed'), 
				apply_filters('wt_import_export_allowed_capability', 'export'),
				$this->module_id,
				array($this, 'admin_settings_page')
			),
		);
		unset($menus['general-settings']);
		$menus=array_merge($menu_temp, $menus);
		return $menus;
	}

	/**
	* 	Export page
	*/
	public function admin_settings_page()
	{
		/**
		*	Check it is a rerun call
		*/
		$requested_rerun_id=(isset($_GET['wt_pf_rerun']) ? absint($_GET['wt_pf_rerun']) : 0);
		$this->_process_rerun($requested_rerun_id);

		$this->enqueue_assets();
		include plugin_dir_path(__FILE__).'views/main.php';
	}

	/**
	* 	Main ajax hook to handle all export related requests
	*/
	public function ajax_main()
	{       

		include_once plugin_dir_path(__FILE__).'classes/class-export-ajax.php';
		if(Wt_Pf_Sh::check_write_access(WEBTOFFEE_PRODUCT_FEED_ID))
		{
			/**
			*	Check it is a rerun call
			*/
			if(!$this->_process_rerun((isset($_POST['rerun_id']) ? absint($_POST['rerun_id']) : 0)))
			{	
				$this->export_method=(isset($_POST['export_method']) ? Wt_Pf_Sh::sanitize_item($_POST['export_method'], 'text') : '');
				$this->to_export=(isset($_POST['to_export']) ? Wt_Pf_Sh::sanitize_item($_POST['to_export'], 'text') : '');
				$this->selected_template=(isset($_POST['selected_template']) ? Wt_Pf_Sh::sanitize_item($_POST['selected_template'], 'int') : 0);
			}		
			$this->get_steps();

			$ajax_obj=new Webtoffee_Product_Feed_Sync_Export_Ajax($this, $this->to_export, $this->steps, $this->export_method, $this->selected_template, $this->rerun_id);
			
			$export_action=Wt_Pf_Sh::sanitize_item($_POST['export_action'], 'text');
			$data_type=Wt_Pf_Sh::sanitize_item($_POST['data_type'], 'text');
			
			$allowed_ajax_actions=array('get_steps', 'get_meta_mapping_fields', 'save_template', 'save_template_as', 'update_template', 'upload', 'export', 'export_image');

			$out=array(
				'status'=>0,
				'msg'=>__('Error'),
			);

			if(method_exists($ajax_obj, $export_action) && in_array($export_action, $allowed_ajax_actions))
			{
				$out=$ajax_obj->{$export_action}($out);
			}

			if($data_type=='json')
			{
				echo json_encode($out);
			}
		}
		exit();
	}

	public function get_filter_screen_fields($filter_form_data) {
            $filter_screen_fields = array(
                'limit' => array(
                    'label' => __("Limit"),
                    'value' => '',
                    'type' => 'number',
                    'field_name' => 'limit',
                    'placeholder' => 'Unlimited',
                    'help_text' => __('The actual number of records you want to export. e.g. A limit of 500 with an offset 10 will export records from 11th to 510th position.'),
                    'attr' => array('step' => 1, 'min' => 0),
                    'validation_rule' => array('type' => 'absint')
                ),
                'offset' => array(
                    'label' => __("Offset"),
                    'value' => '',
                    'field_name' => 'offset',
                    'placeholder' => __('0'),
                    'help_text' => __('Specify the number of records that should be skipped from the beginning of the database. e.g. An offset of 10 skips the first 10 records.'),
                    'type' => 'number',
                    'attr' => array('step' => 1, 'min' => 0),
                    'validation_rule' => array('type' => 'absint')
                ),
            );
            $filter_screen_fields = apply_filters('wt_pf_exporter_alter_filter_fields_basic', $filter_screen_fields, $this->to_export, $filter_form_data);
            return $filter_screen_fields;
        }

        public function get_advanced_screen_fields($advanced_form_data)
	{
		$file_into_arr=array('local'=>__('Local'));

		/* taking available remote adapters */
		$remote_adapter_names=array();
		$remote_adapter_names=apply_filters('wt_pf_exporter_remote_adapter_names_basic', $remote_adapter_names);
		if($remote_adapter_names && is_array($remote_adapter_names))
		{
			foreach($remote_adapter_names as $remote_adapter_key => $remote_adapter_vl)
			{
				$file_into_arr[$remote_adapter_key]=$remote_adapter_vl;
			}
		}

		$delimiter_default = isset($advanced_form_data['wt_pf_delimiter']) ? $advanced_form_data['wt_pf_delimiter'] : ",";
		$advanced_screen_fields=array(

			'batch_count'=>array(
				'label'=>__("Process in batches of"),
				'type'=>'text',
                                'merge_right'=>true,
				'value'=>$this->default_batch_count,
				'field_name'=>'batch_count',
				'help_text'=>sprintf(__('The number of records that the server will process for every iteration within the configured timeout interval. If the process fails due to timeout you can lower this number accordingly and try again. Defaulted to %d records.'), 30),
				'validation_rule'=>array('type'=>'absint'),
			),
			'file_as'=>array(
				'label'=>__("Export file format", 'webtoffee-product-feed'),
				'type'=>'select',
				'sele_vals'=>$this->allowed_export_file_type,
				'field_name'=>'file_as',
				'form_toggler'=>array(
					'type'=>'parent',
					'target'=>'wt_pf_file_as'
				)
			),			
			'delimiter'=>array(
				'label'=>__( 'Delimiter', 'webtoffee-product-feed' ),
				'type'=>'select',
				'value'=>",",
				'css_class'=>"wt_pf_delimiter_preset",
				'tr_id'=>'delimiter_tr',
				'field_name'=>'delimiter_preset',
				'sele_vals'=>Wt_Pf_IE_Basic_Helper::_get_csv_delimiters(),
				'form_toggler'=>array(
					'type'=>'child',
					'id'=>'wt_pf_file_as',
					'val'=>'csv'
				),				
				'help_text'=>__( 'Separator for differentiating the columns in the CSV file. Assumes TAB by default.' ),
				'validation_rule'=>array('type'=>'skip'),
				'after_form_field'=>'<input type="text" class="wt_pf_custom_delimiter" name="wt_pf_delimiter" value="'.$delimiter_default.'" maxlength = "1" />',
			)
		);
                
                $posted_to_export=(isset($_POST['to_export']) ? Wt_Pf_Sh::sanitize_item($_POST['to_export'], 'text') : '');
                if( ($posted_to_export) && $posted_to_export !== $this->to_export){ // If the channel type changes fro rerun, it should reflect on following steps.
                    $this->to_export = $posted_to_export;
                }
                
		/* taking advanced fields from post type modules */
		$advanced_screen_fields=apply_filters('wt_pf_exporter_alter_advanced_fields_basic', $advanced_screen_fields, $this->to_export, $advanced_form_data);
		return $advanced_screen_fields;
	}

	/**
	* Get steps
	*
	*/
	public function get_steps()
	{
		if($this->export_method=='quick') /* if quick export then remove some steps */
		{
			$out=array(
				'post_type'=>$this->steps['post_type'],
				'method_export'=>$this->steps['method_export'],
				'advanced'=>$this->steps['advanced'],
			);
			$this->steps=$out;
		}
		$this->steps=apply_filters('wt_pf_exporter_steps_basic', $this->steps, $this->to_export);
                return $this->steps;
	}
	

	/**
	*	Validating and Processing rerun action
	*/
	protected function _process_rerun($rerun_id)
	{
		if($rerun_id>0)
		{
			/* check the history module is available */
			$history_module_obj=Webtoffee_Product_Feed_Sync::load_modules('history');
			if(!is_null($history_module_obj))
			{
				/* check the history entry is for export and also has form_data */
				$history_data=$history_module_obj->get_history_entry_by_id($rerun_id);

				if($history_data && $history_data['template_type']==$this->module_base)
				{
					$form_data=maybe_unserialize($history_data['data']);

					if($form_data && is_array($form_data))
					{
						$this->to_export=(isset($form_data['post_type_form_data']) && isset($form_data['post_type_form_data']['item_type']) ? $form_data['post_type_form_data']['item_type'] : '');
                                                $posted_to_export=(isset($_POST['to_export']) ? Wt_Pf_Sh::sanitize_item($_POST['to_export'], 'text') : '');
                                                if( ($posted_to_export) && $posted_to_export !== $this->to_export){ // If the channel type changes fro rerun, it should reflect on coming steps.
                                                    $this->to_export = $posted_to_export;
                                                }						
                                                if($this->to_export!="")
						{
							$this->export_method=(isset($form_data['method_export_form_data']) && isset($form_data['method_export_form_data']['method_export']) && $form_data['method_export_form_data']['method_export']!="" ?  $form_data['method_export_form_data']['method_export'] : $this->default_export_method);
							$this->rerun_id=$rerun_id;
							$this->form_data=$form_data;
							//process steps based on the export method in the history entry
							$this->get_steps();

							return true;
						}
					}
				}
			}
		}
		return false;
	}

	protected function enqueue_assets()
	{
            if(Webtoffee_Product_Feed_Sync_Common_Helper::wt_is_screen_allowed()){
		wp_enqueue_script($this->module_id, plugin_dir_url(__FILE__).'assets/js/main.js', array('jquery', 'jquery-ui-sortable', 'jquery-ui-datepicker'), WEBTOFFEE_PRODUCT_FEED_SYNC_VERSION);
		wp_enqueue_style('jquery-ui-datepicker');
		wp_enqueue_style(WEBTOFFEE_PRODUCT_FEED_ID.'-jquery-ui', WT_PRODUCT_FEED_PLUGIN_URL.'admin/css/jquery-ui.css', array(), WEBTOFFEE_PRODUCT_FEED_SYNC_VERSION, 'all');
        
		$file_names = array();
		if(!$this->rerun_id){
		$file_details = Webtoffee_Product_Feed_Sync_History::get_filename_items();
		foreach ($file_details as $key => $value) {
			$file_names[] = str_replace('.csv', '', $value['file_name']);
		}
		}

		$params=array(
			'item_type'=>'',
			'steps'=>$this->steps,
			'rerun_id'=>$this->rerun_id,
			'to_export'=> isset( $_GET['wt_to_export'] ) ? sanitize_text_field( $_GET['wt_to_export'] ) : $this->to_export,
			'export_method'=>$this->export_method,
			'export_file_names' => json_encode($file_names),
			'msgs'=>array(
				'choosed_template'=>__('Choosed template: ', 'webtoffee-product-feed'),
				'choose_export_method'=>__('Please select an export method.', 'webtoffee-product-feed'),
				'choose_template'=>__('Please select an export template.', 'webtoffee-product-feed'),
				'step'=>__('Step', 'webtoffee-product-feed'),
				'choose_ftp_profile'=>__('Please select an FTP profile.', 'webtoffee-product-feed'),
				'export_cancel_warn' => __('Are you sure to stop the product feed generation?', 'webtoffee-product-feed'),
				'export_fill_warn' => __('Please fill the file name', 'webtoffee-product-feed'),
				'file_name_duplicate' => __('File name already exist', 'webtoffee-product-feed'),
			),
		);
		wp_localize_script($this->module_id, 'wt_pf_export_basic_params', $params);

		$this->add_select2_lib(); //adding select2 JS, It checks the availibility of woocommerce
            }
	}

	/**
	* 
	* Enqueue select2 library, if woocommerce available use that
	*/
	protected function add_select2_lib()
	{
		/* enqueue scripts */
		if(!function_exists('is_plugin_active'))
		{
			include_once(ABSPATH.'wp-admin/includes/plugin.php');
		}
		if(is_plugin_active('woocommerce/woocommerce.php'))
		{ 
			wp_enqueue_script('wc-enhanced-select');
			wp_enqueue_style('woocommerce_admin_styles', WC()->plugin_url().'/assets/css/admin.css');
		}else
		{
			wp_enqueue_style(WEBTOFFEE_PRODUCT_FEED_ID.'-select2', WT_PRODUCT_FEED_PLUGIN_URL. 'admin/css/select2.css', array(), WEBTOFFEE_PRODUCT_FEED_SYNC_VERSION, 'all' );
			wp_enqueue_script(WEBTOFFEE_PRODUCT_FEED_ID.'-select2', WT_PRODUCT_FEED_PLUGIN_URL.'admin/js/select2.js', array('jquery'), WEBTOFFEE_PRODUCT_FEED_SYNC_VERSION, false );
		}
	}


	/**
	* Upload data to the user choosed remote method (Eg: FTP)
	* @param   string   $step       the action to perform, here 'upload'
	*
	* @return array 
	*/
	public function process_upload($step, $export_id, $to_export)
	{
		$out=array(
			'response'=>false,
			'export_id'=>0,
			'history_id'=>0, //same as that of export id
			'finished'=>0,
			'file_url'=>'',
			'msg'=>'',
		);

		if($export_id==0) //it may be an error
		{
			return $out;
		}

		//take history data by export_id
		$export_data= Webtoffee_Product_Feed_Sync_History::get_history_entry_by_id($export_id);
		if(is_null($export_data)) //no record found so it may be an error
		{
			return $out;
		}

		$form_data=maybe_unserialize($export_data['data']);

		//taking file name
		$file_name=(isset($export_data['file_name']) ? $export_data['file_name'] : '');
		
		$file_path=$this->get_file_path($file_name);
		if($file_path===false)
		{
			$update_data=array(
				'status'=>Webtoffee_Product_Feed_Sync_History::$status_arr['failed'],
				'status_text'=>'File not found.' //no need to add translation function
			);
			$update_data_type=array(
				'%d',
				'%s',
			);
			Webtoffee_Product_Feed_Sync_History::update_history_entry($export_id, $update_data, $update_data_type);

            return $out;
		}

		/* updating output parameters */
		$out['export_id']=$export_id;
		$out['history_id']=$export_id;
		$out['file_url']='';

		//check where to copy the files
		$file_into='local';
		if(isset($form_data['advanced_form_data']))
		{
			$file_into=(isset($form_data['advanced_form_data']['wt_pf_file_into']) ? $form_data['advanced_form_data']['wt_pf_file_into'] : 'local');
		}

		if('local' != $file_into) /* file not save to local. Initiate the choosed remote profile */
		{
			$remote_adapter=Webtoffee_Product_Feed_Sync::get_remote_adapters('export', $file_into);
			if(is_null($remote_adapter)) /* adapter object not found */
			{
				$msg=sprintf('Unable to initailize %s', $file_into);
				Webtoffee_Product_Feed_Sync_History::record_failure($export_id, $msg);
				$out['msg']=__($msg);
				return $out;
			}

			/* upload the file */
			$upload_out_format = array('response'=>true, 'msg'=>'');

			$advanced_form_data=(isset($form_data['advanced_form_data']) ? $form_data['advanced_form_data'] : array());

			$upload_data = $remote_adapter->upload($file_path, $file_name, $advanced_form_data, $upload_out_format);
			$out['response'] = (isset($upload_data['response']) ? $upload_data['response'] : false);
			$out['msg'] = (isset($upload_data['msg']) ? $upload_data['msg'] : __('Error'));

			//unlink the local file
			@unlink($file_path);
		}else
		{
			$out['response']=true;
			$out['file_url']=html_entity_decode($this->get_file_url($file_name));
		}

		$out['finished']=1;  //if any error then also its finished, but with errors
		if($out['response'] === true) //success
		{
			$out['msg']=__('Finished');
			
			/* updating finished status */
			$update_data=array(
				'status'=>1  //success
			);
			$update_data_type=array(
				'%d'
			);
			Webtoffee_Product_Feed_Sync_History::update_history_entry($export_id, $update_data, $update_data_type);

		}else //failed
		{
			//no need to add translation function in message
			Webtoffee_Product_Feed_Sync_History::record_failure($export_id, 'Failed while uploading');
		}
		return $out;
	}


	/**
	* 	Do the export process
	*/
	public function process_action($form_data, $step, $to_process, $file_name='', $export_id=0, $offset=0)
	{
		$out=array(
			'response'=>false,
			'new_offset'=>0,
			'export_id'=>0,
			'history_id'=>0, //same as that of export id
			'total_records'=>0,
			'finished'=>0,
			'file_url'=>'',
			'msg'=>'',
		);

		/* prepare form_data, If this was not first batch */
		if($export_id>0)
		{
			//take history data by export_id
			$export_data=Webtoffee_Product_Feed_Sync_History::get_history_entry_by_id($export_id);
			if(is_null($export_data)) //no record found so it may be an error
			{
				return $out;
			}

			//processing form data
			$form_data=(isset($export_data['data']) ? maybe_unserialize($export_data['data']) : array());
		}
		$this->to_export=$to_process;
		$default_batch_count=$this->_get_default_batch_count($form_data);
		$batch_count=$default_batch_count;		
		$file_as='csv';
		$csv_delimiter=',';
		$total_records=0;

		if(isset($form_data['advanced_form_data']))
		{
			$batch_count=(isset($form_data['advanced_form_data']['wt_pf_batch_count']) ? $form_data['advanced_form_data']['wt_pf_batch_count'] : $batch_count);
			$file_as=(isset($form_data['advanced_form_data']['wt_pf_file_as']) ? $form_data['advanced_form_data']['wt_pf_file_as'] : 'csv');
			$csv_delimiter=(isset($form_data['advanced_form_data']['wt_pf_delimiter']) ? $form_data['advanced_form_data']['wt_pf_delimiter'] : ',');
			$csv_delimiter=($csv_delimiter=="" ? ',' : $csv_delimiter);
		}		
		$file_as=(isset($this->allowed_export_file_type[$file_as]) ? $file_as : 'csv');

		
		$generated_file_name= sanitize_file_name($form_data['post_type_form_data']['item_filename'].'.'.$file_as);

		if($export_id==0) //first batch then create a history entry
		{
			$file_name=($file_name=="" ? $generated_file_name : sanitize_file_name($file_name.'.'.$file_as));
			$export_id=Webtoffee_Product_Feed_Sync_History::create_history_entry($file_name, $form_data, $this->to_export, $step);
			$offset=0;
		}else
		{
			//taking file name from export data
			$file_name=(isset($export_data['file_name']) ? $export_data['file_name'] : $generated_file_name);		
			$total_records=(isset($export_data['total']) ? $export_data['total'] : 0);
		}

		/* setting history_id in Log section */
		// Webtoffee_Product_Feed_Sync_Basic_Log::$history_id=$export_id;


		$file_path=$this->get_file_path($file_name);
		if($file_path===false)
		{
			$msg='Unable to create backup directory. Please grant write permission for `wp-content` folder.';		
			
			//no need to add translation function in message
			Webtoffee_Product_Feed_Sync_History::record_failure($export_id, $msg);

            $out['msg']=__($msg);
            return $out;
		}

		/* giving full data */
		$form_data=apply_filters('wt_pf_export_full_form_data_basic', $form_data, $to_process, $step, $this->selected_template_data);

		/* hook to get data from corresponding module. Eg: product, order */
		$export_data=array(
			'total'=>100,
			'head_data'=>array("abc"=>"hd1", "bcd"=>"hd2", "cde"=>"hd3", "def"=>"hd4"),
			'body_data'=>array(
				array("abc"=>"Abc1", "bcd"=>"Bcd1", "cde"=>"Cde1", "def"=>"Def1"),
  				array("abc"=>"Abc2", "bcd"=>"Bcd2", "cde"=>"Cde2", "def"=>"Def2")
			),
		); 		

		/* in scheduled export. The export method will not available so we need to take it from form_data */
		$form_data_export_method=(isset($form_data['method_export_form_data']) && isset($form_data['method_export_form_data']['method_export']) ?  $form_data['method_export_form_data']['method_export'] : $this->default_export_method);
		$this->export_method=($this->export_method=="" ? $form_data_export_method : $this->export_method);

		$export_data=apply_filters('wt_pf_exporter_do_export_basic', $export_data, $to_process, $step, $form_data, $this->selected_template_data, $this->export_method, $offset);

		if($offset==0)
		{
			$total_records=intval(isset($export_data['total']) ? $export_data['total'] : 0);
		}
		$this->_update_history_after_export($export_id, $offset, $total_records, $export_data);

		/* checking action is finshed */
		$is_last_offset=false;
		$new_offset=$offset+$batch_count; //increase the offset
		if($new_offset>=$total_records) //finished
		{
			$is_last_offset=true;
		}


		/* no data from corresponding module */
		if(!$export_data) //error !!!
		{
			//return $out;
		}else
		{
			if( 'xml' === $file_as )
			{
				include_once WT_PRODUCT_FEED_PLUGIN_PATH.'admin/classes/class-xmlwriter.php';
				$writer=new Webtoffee_Product_Feed_Sync_Basic_Xmlwriter($file_path);
			}elseif( 'xlsx' === $file_as ){
                            
				include_once WT_PRODUCT_FEED_PLUGIN_PATH.'admin/classes/class-excelwriter.php';
				$writer=new Webtoffee_Product_Feed_Sync_Basic_Excelwriter($file_path, $offset, $csv_delimiter, $this->use_bom);
			}else{
                                if( 'tsv' === $file_as || 'txt' === $file_as ){
                                    $csv_delimiter = "\t";
                                }
				include_once WT_PRODUCT_FEED_PLUGIN_PATH.'admin/classes/class-csvwriter.php';
				$writer=new Webtoffee_Product_Feed_Sync_Basic_Csvwriter($file_path, $offset, $csv_delimiter, $this->use_bom);
			}

                                                
                        /**
			*	Alter export data before writing to file.
			*	@param 	array 	$export_data  		data to export
			*	@param 	int 	$offset  			current offset
			*	@param 	boolean $is_last_offset 	is current offset is last one
			*	@param 	string 	$file_as 			file type to write Eg: XML, CSV
			*	@param 	string 	$to_export 			Post type
			*	@param 	string 	$csv_delimiter 		CSV delimiter. In case of CSV export
			*	@return array 	$export_data 		Altered export data
			*/

			$export_data=apply_filters('wt_pf_alter_export_data_basic', $export_data, $offset, $is_last_offset, $file_as, $this->to_export, $csv_delimiter);       
			if( !empty( $export_data['body_data']) ){
                            $writer->write_to_file($export_data, $offset, $is_last_offset, $this->to_export);
                        }
		}
		
		/* updating output parameters */
		$out['total_records']=$total_records;
		$out['export_id']=$export_id;
		$out['history_id']=$export_id;
		$out['file_url']='';
		$out['response']=true;

		/* updating action is finshed */	
		if($is_last_offset) //finished
		{

			//check where to copy the files
			$file_into='local';
			if(isset($form_data['advanced_form_data']))
			{
				$file_into=(isset($form_data['advanced_form_data']['wt_pf_file_into']) ? $form_data['advanced_form_data']['wt_pf_file_into'] : 'local');
			}
			if('local' != $file_into) /* file not save to local. Initiate the choosed remote profile */
			{
				$out['finished']=2; //file created, next upload it

				$out['msg']=sprintf(__('Uploading to %s'), $file_into);
			}else
			{
				$out['file_url']=html_entity_decode($this->get_file_url($file_name));
				$out['finished']=1; //finished

                                $history_page_url = '#';
                                if(Webtoffee_Product_Feed_Sync_Admin::module_exists('history'))
                                {
                                        $history_module_id= Webtoffee_Product_Feed_Sync::get_module_id('history');
                                        $history_page_url=admin_url('admin.php?page='.$history_module_id);                                        
                                }
                                
                                $raw_file_url = content_url().'/uploads/webtoffee_product_feed/'.($file_name);                                
                                
				$msg = '<span class="wt_pf_popup_close dashicons dashicons-dismiss" style="line-height:10px;width:auto" onclick="wt_pf_basic_export.hide_export_info_box();"></span>'; 
                                $msg.= '<h2><span class="dashicons dashicons-yes" style="background:#20B93E;color:#fff;border-radius:15px; margin-right:10px;padding:5px;"></span><br/><p></p><span style="font-weight:400">' . __('Feed generated successfully!') . '</span></h2>';                                
                                $msg.='<span class="wt_pf_info_box_finished_text" style="font-size: 10px; display:block">';                                
                                $msg.='<a class="button media-button" style="margin-top:10px;margin-right:10px;font-weight:bold;padding:5px 15px" onclick="wt_pf_basic_export.hide_export_info_box();" target="_blank" href="'.$out['file_url'].'" ><span style="margin-top: 7px;margin-right: 4px;" class="dashicons dashicons-download"></span>'.__('Download').'</a>'
                                        .'<a class="button media-butto" style="margin-top:10px;margin-right:10px;font-weight:bold;padding:5px 15px" onclick="wt_pf_basic_export.hide_export_info_box();" target="_blank" href="'.$history_page_url.'" >'.__('Manage feeds').'</a>'
                                        .'<button class="button button-primary wt_pf_copy" style="margin-top:10px;font-weight:bold;padding:5px 15px" data-uri="'.$raw_file_url.'" >'.__('Copy URL').'</a>'
                                        . '</span>';
                               
                                                               
				if( 0 == $total_records && isset( $export_data['no_post'] ) ){
					
					$out['no_post'] = true;
					$msg = $export_data['no_post'];                    
				}
				$out['msg']=$msg;
				
				/* updating finished status */
				$update_data=array(
					'status'=>Webtoffee_Product_Feed_Sync_History::$status_arr['finished'],
					'status_text'=>'Finished', //translation function not needed
					'updated_at'=>time() //updated time
				);
				$update_data_type=array(
					'%d',
					'%s',
					'%d'
					
				);
				Webtoffee_Product_Feed_Sync_History::update_history_entry($export_id,$update_data,$update_data_type);
			}

			// schedule catalog generation as per the interval on the last offset
			if(isset($form_data['post_type_form_data']['item_gen_interval']) && 'manual' !== $form_data['post_type_form_data']['item_gen_interval'] ){
				$cron_obj = new Webtoffee_Product_Feed_Sync_Cron();
				$cron_obj->add_schedule($out, $form_data);
			}
			
			
		}else
		{
			$out['new_offset']=$new_offset;
			$out['msg']=sprintf(__('Exporting...(%d out of %d)'), $new_offset, $total_records);
			
			$export_percentage = (int)(( $new_offset / $total_records ) * 100);
			$out['total_percent'] = $export_percentage;
			$out['total_done'] = $new_offset;
			
		}
		return $out;
	}


	public static function get_file_path($file_name)
	{
		if(!is_dir(self::$export_dir))
        {
            if(!mkdir(self::$export_dir, 0775))
            {
            	return false;
            }else
            {
            	//$files_to_create=array('.htaccess' => 'deny from all', 'index.php'=>'<?php // Silence is golden');
				$files_to_create=array( 'index.php'=>'<?php // Silence is golden');
		        foreach($files_to_create as $file=>$file_content)
		        {
		        	if(!file_exists(self::$export_dir.'/'.$file))
			        {
			            $fh=@fopen(self::$export_dir.'/'.$file, "w");
			            if(is_resource($fh))
			            {
			                fwrite($fh, $file_content);
			                fclose($fh);
			            }
			        }
		        } 
            }
        }
        return self::$export_dir.'/'.$file_name;
	}

	/**
	*  	Download file via a nonce URL
	*/
	public function download_file()
	{
		if(isset($_GET['wt_pf_export_download']))
		{ 
			if(Wt_Pf_Sh::check_write_access(WEBTOFFEE_PRODUCT_FEED_ID)) /* check nonce and role */
			{
				$file_name=(isset($_GET['file']) ? sanitize_file_name($_GET['file']) : '');
				if($file_name!="")
				{
					$file_arr=explode(".", $file_name);
					$file_ext=end($file_arr);
					if(isset($this->allowed_export_file_type[$file_ext]) || $file_ext=='zip') /* Only allowed files. Zip file in image export */
					{
						$file_path=self::$export_dir.'/'.$file_name;
						if(file_exists($file_path) && is_file($file_path)) /* check existence of file */
						{	
							header('Pragma: public');
						    header('Expires: 0');
						    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
						    header('Cache-Control: private', false);
						    header('Content-Transfer-Encoding: binary');
						    header('Content-Disposition: attachment; filename="'.$file_name.'";');
						    header('Content-Description: File Transfer');
						    header('Content-Type: application/octet-stream');
						    //header('Content-Length: '.filesize($file_path));

						    $chunk_size=1024 * 1024;
						    $handle=@fopen($file_path, 'rb');
						    while(!feof($handle))
						    {
						        $buffer = fread($handle, $chunk_size);
						        echo $buffer;
						        ob_flush();
						        flush();
						    }
						    fclose($handle);
						    exit();

						}
					}
				}	
			}
		}
	}

	private function _update_history_after_export($export_id, $offset, $total_records, $export_data)
	{
		/* we need to update total record count on first batch */
		if($offset==0)
		{
			$update_data=array(
				'total'=>$total_records
			);			
		}else
		{
			/* updating completed offset */
			$update_data=array(
				'offset'=>$offset
			);
		}
		$update_data_type=array(
			'%d'
		);
		Webtoffee_Product_Feed_Sync_History::update_history_entry($export_id, $update_data, $update_data_type);
	}

	private function _get_default_batch_count($form_data)
	{
		$default_batch_count=absint(apply_filters('wt_pf_exporter_alter_default_batch_count_basic', $this->default_batch_count, $this->to_export, $form_data));
		$form_data=null;
		unset($form_data);
		return ($default_batch_count==0 ? $this->default_batch_count : $default_batch_count);
	}

	/**
	*	Generating downloadable URL for a file
	*/
	private function get_file_url($file_name)
	{
		return wp_nonce_url(admin_url('admin.php?wt_pf_export_download=true&file='.$file_name), WEBTOFFEE_PRODUCT_FEED_ID);
	}
        
        
        
        public function product_data_tabs( $tabs ) {

		$wt_feed_tab = array(
			'label'    => _x( 'WebToffee Product Feed', 'product data tab', 'webtoffee-product-feed' ),
			'target'   => 'wt_feed_data',
			'class'    => array('hide_if_variable'),
			'priority' => 35,
		);

		$index = array_search( 'linked_product', array_keys( $tabs ), true );

		if ( false === $index ) {
			$tabs['wt_feed'] = $wt_feed_tab;
		} else {
			$tabs = array_merge(
				array_slice( $tabs, 0, $index ),
				array(
					'wt_feed' => $wt_feed_tab,
				),
				array_slice( $tabs, $index )
			);
		}

		return $tabs;
	}
        public function product_data_panels() {

		include 'views/html-product-data-feed.php';
	}
        public function save_product_data( $post_id ) {
		
		// Save product properties.
		$props = array( 
			'discard',
			'brand',
			'condition',
			'gtin',
			'mpn',
                        'han',
                        'ean',                    
			'agegroup',
			'gender',
			'size',
			'color',
			'material',
			'pattern',
			'unit_pricing_measure',
			'unit_pricing_base_measure',
			'energy_efficiency_class',
			'min_energy_efficiency_class',
			'max_energy_efficiency_class',
			'glpi_pickup_method',
			'glpi_pickup_sla',
			'custom_label_0',
			'custom_label_1',
			'custom_label_2',
			'custom_label_3',
			'custom_label_4',			
			'google_product_category'		
			);

		foreach ( $props as $prop ) {
			$key   = "_wt_feed_{$prop}";
			$value = ( ! empty( $_POST[ $key ] ) ? wc_clean( wp_unslash( $_POST[ $key ] ) ) : '' ); // phpcs:ignore WordPress.Security.NonceVerification

			if ( $value ) {
				update_post_meta( $post_id, $key, $value );
			} else {
				delete_post_meta( $post_id, $key );
			}
		}
                
                $channel_based_params = array(
                    '_wt_facebook_fb_product_category',
                    '_wt_google_google_product_category'
                );
		foreach ( $channel_based_params as $chanel_prop ) {
			
			$value = ( ! empty( $_POST[ $chanel_prop ] ) ? wc_clean( wp_unslash( $_POST[ $chanel_prop ] ) ) : '' ); // phpcs:ignore WordPress.Security.NonceVerification

			if ( $value ) {
				update_post_meta( $post_id, $chanel_prop, $value );
			} else {
				delete_post_meta( $post_id, $chanel_prop );
			}
		}
	}
        public function wt_feed_variable_custom_meta_fields( $variation_count, $variation_id, $variation_prod ) {
		
		include 'views/html-product-variation-data-feed.php';
				
	}
        public function wt_feed_save_variable_metas($id) {


			
			if ( empty( $_POST['_wt_feed_variations'] ) || ! wp_verify_nonce( $_POST['_wt_feed_variations'], 'wt_feed_variations' ) || ! isset( $_POST['variable_post_id'] ) || ! is_array( $_POST['variable_post_id'] ) ) {
				return;
			}

			$variable_post_id = isset($_POST['variable_post_id']) ? array_map('sanitize_text_field', wp_unslash($_POST['variable_post_id'])) : array();
			$count = !empty($variable_post_id) ? max(array_keys($variable_post_id)) : 0;
			for ($i = 0; $i <= $count; $i++) {
				if (isset($_POST['variable_post_id'][$i])) {

					$post_id = sanitize_text_field($_POST['variable_post_id'][$i]);
					$props = array(
						'discard',
						'brand',
						'gtin',
						'mpn',
                                                'han',
                                                'ean',                                            
						'condition',
						'agegroup',
						'gender',
						'size',
						'color',
						'material',
						'pattern',
						'unit_pricing_measure',
						'unit_pricing_base_measure',
						'energy_efficiency_class',
						'min_energy_efficiency_class',
						'max_energy_efficiency_class',
						'glpi_pickup_method',
						'glpi_pickup_sla',
						'custom_label_0',
						'custom_label_1',
						'custom_label_2',
						'custom_label_3',
						'custom_label_4',
						'google_product_category'
						);
					foreach ($props as $prop) {
						$key = "_wt_feed_{$prop}";
						$value = (!empty($_POST[$key]) ? wc_clean(wp_unslash($_POST[$key][$i])) : '' ); // phpcs:ignore WordPress.Security.NonceVerification

						if ($value) {
							update_post_meta($post_id, $key, $value);
						} else {
							delete_post_meta($post_id, $key);
						}
					}
                                        $channel_based_params = array(
                                            '_wt_facebook_fb_product_category',
                                            '_wt_google_google_product_category'
                                        );
                                        foreach ( $channel_based_params as $chanel_prop ) {

                                                $value = ( ! empty( $_POST[ $chanel_prop ] ) ? wc_clean( wp_unslash( $_POST[ $chanel_prop ][$i] ) ) : '' ); // phpcs:ignore WordPress.Security.NonceVerification

                                                if ( $value ) {
                                                        update_post_meta( $post_id, $chanel_prop, $value );
                                                } else {
                                                        delete_post_meta( $post_id, $chanel_prop );
                                                }
                                        }                                        
				}
			}
		}
                public function wt_feed_tab_styles() {

			$custom_css = '#woocommerce-product-data ul.wc-tabs li.wt_feed_options a::before{
						font-family: Dashicons;
						font-style: normal;
						font-weight: 600;
						font-variant: normal;
						text-transform: none;
						speak: none;
						display: inline-block;
						text-decoration: inherit;
						-webkit-font-smoothing: antialiased;
						-moz-osx-font-smoothing: grayscale;
						content: "\f312";
					}';
			wp_add_inline_style('woocommerce_admin_styles', $custom_css);
		}                
        
        
}
}
Webtoffee_Product_Feed_Sync::$loaded_modules['export']=new Webtoffee_Product_Feed_Sync_Export();