<?php
class BSK_GFCV_Dashboard {
    
    public static $_bsk_gfcv_pro_verison_url = 'https://www.bannersky.com/gravity-forms-custom-validation/';
	
    public static $_bsk_gfcv_pages = array(  
                                            'base' => array( 'slug' => 'bsk-forms-cv', 'label' => 'Validaiton List' ),
                                            'blocked_data' => array( 'slug' => 'bsk-forms-cv-blocked-data', 'label' => 'Blocked form data' ),
                                            'settings' => array( 'slug' => 'bsk-forms-cv-settings', 'label' => 'Settings' ),
                                            'license_update' => array( 'slug' => 'bsk-forms-cv-license-update', 'label' => 'License &amp; Update' ),
                                        );
    
    public static $_plugin_settings_option = '_bsk_gfcv_settings_';
    
    public $_bsk_gfcv_OBJ_settings = NULL;
	
    public $_bsk_gfcv_OBJ_list = NULL;
    
    public $_bsk_gfcv_forms_OBJ_gf = NULL;
    public $_bsk_gfcv_forms_OBJ_ff = NULL;
    
	public function __construct() {
		
        require_once( BSK_GFCV_DIR.'classes/dashboard/common.php' );
		require_once( BSK_GFCV_DIR.'classes/dashboard/list.php' );
		require_once( BSK_GFCV_DIR.'classes/dashboard/lists.php' );
		require_once( BSK_GFCV_DIR.'classes/dashboard/items.php' );
        require_once( BSK_GFCV_DIR.'classes/dashboard/list.php' );
        require_once( BSK_GFCV_DIR.'classes/dashboard/list.php' );

		require_once( BSK_GFCV_DIR.'classes/dashboard/gravityforms/gravityforms.php' );
        require_once( BSK_GFCV_DIR.'classes/dashboard/formidable-forms/formidable-forms.php' );
        
        require_once( BSK_GFCV_DIR.'classes/dashboard/dashboard-settings.php' );
        require_once( BSK_GFCV_DIR.'classes/dashboard/entries.php' );
        
		$this->_bsk_gfcv_OBJ_list = new BSK_GFCV_Dashboard_List();
		$this->_bsk_gfcv_forms_OBJ_gf = new BSK_GFCV_Dashboard_GravityForms();
        $this->_bsk_gfcv_forms_OBJ_ff = new BSK_GFCV_Dashboard_Formidable_Forms();
        
        $this->_bsk_gfcv_OBJ_settings = new BSK_GFCV_Dashboard_Settings();

        /*
          * Actions & Filters
          */
		add_action( 'admin_menu', array( $this, 'bsk_gfcv_dashboard_menu' ), 999 );        
        add_action( 'gform_after_delete_form', array( $this, 'bsk_gfcv_delete_entries_fun' ) );
	}
	
	function bsk_gfcv_dashboard_menu() {
		
		$authorized_level = 'manage_options';
		
		add_menu_page(
            'BSK Forms VAL',
            'BSK Forms VAL',
            $authorized_level,
            self::$_bsk_gfcv_pages['base']['slug'],
            '',
            'dashicons-admin-network'
        );

        add_submenu_page(
            self::$_bsk_gfcv_pages['base']['slug'],
            self::$_bsk_gfcv_pages['base']['label'],
            self::$_bsk_gfcv_pages['base']['label'],
            $authorized_level,
            self::$_bsk_gfcv_pages['base']['slug'],
            array($this, 'bsk_gfcv_lists_table')
        );

        add_submenu_page(
            self::$_bsk_gfcv_pages['base']['slug'],
            self::$_bsk_gfcv_pages['blocked_data']['label'],
            self::$_bsk_gfcv_pages['blocked_data']['label'],
            $authorized_level,
            self::$_bsk_gfcv_pages['blocked_data']['slug'],
            array($this, 'bsk_gfcv_entries')
        );

        add_submenu_page(
            self::$_bsk_gfcv_pages['base']['slug'],
            self::$_bsk_gfcv_pages['settings']['label'],
            self::$_bsk_gfcv_pages['settings']['label'],
            $authorized_level,
            self::$_bsk_gfcv_pages['settings']['slug'],
            array($this, 'bsk_gfcv_settings')
        );
	}
	
	function bsk_gfcv_lists_table(){
        
        /*global $wpdb;

        $sql = 'SELECT DISTINCT(`COL 3`) as code, `COL 4` as country FROM `ip2location_lite_db1_csv` WHERE 1 ORDER BY `COL 4` ASC';
        $results = $wpdb->get_results( $sql );
        foreach( $results as $record ){
            echo '\''.$record->code.'\' => \''.$record->country.'\','."\n";
        }
        exit;*/

		$current_view = 'cvlist';
		if(isset($_GET['view']) ){
            $get_view = trim( sanitize_text_field( $_GET['view'] ) );
            if( $get_view ){
                $current_view = $get_view;
            }
		}
		if(isset($_POST['view']) ){
			$post_view = trim( sanitize_text_field( $_POST['view'] ) );
            if( $post_view ){
                $current_view = $post_view;
            }
		}

		$current_base_page = admin_url( 'admin.php?page='.self::$_bsk_gfcv_pages['base']['slug'] );
		if( $current_view == 'cvlist' || $current_view == 'duplicate' ){

            $_bsk_gfcv_OBJ_lists = new BSK_GFCV_Dashboard_Lists();

            //Fetch, prepare, sort, and filter our data...
            $_bsk_gfcv_OBJ_lists->prepare_items();

            $add_new_page_url = add_query_arg( 'view', 'addnew', $current_base_page );
            echo '<div class="wrap">
                    <div id="icon-edit" class="icon32"><br/></div>
                    <h2>Validaiton List<a href="'.$add_new_page_url.'" class="add-new-h2">Add New</a></h2>';
            echo '  <form id="bsk_gfcv_cv_lists_form_id" method="post" action="'.$current_base_page.'">';
                        $_bsk_gfcv_OBJ_lists->display();
            echo '
                        <input type="hidden" name="bsk_gfcv_list_id" id="bsk_gfcv_cv_list_id_to_be_processed_ID" value="0" />
                        <input type="hidden" name="bsk_gfcv_action" id="bsk_gfcv_action_ID" value="" />';
                        wp_nonce_field( 'bsk_gfcv_list_oper_nonce' );
            echo '
                    </form>
                  </div>';
		}else if ( $current_view == 'addnew' || $current_view == 'edit'){
			$list_id = -1;
			if(isset($_GET['id']) && $_GET['id']){
				$list_id = trim( sanitize_text_field( $_GET['id'] ) );
                if( $list_id ){
				    $list_id = absint( $list_id );
                }
			}
            $this->_bsk_gfcv_OBJ_list->bsk_gfcv_list_edit( $list_id,
                                                           $current_view
                                                         );
		}

	}
    
    function bsk_gfcv_entries(){

        $settings_data = get_option( self::$_plugin_settings_option, false );
        $save_blocked_entry = true;
        if( $settings_data && is_array( $settings_data ) && count( $settings_data ) > 0 ){
            if( isset( $settings_data['save_blocked_entry'] ) ){
                $save_blocked_entry = $settings_data['save_blocked_entry'];
            }
        }

        $action_url = admin_url( 'admin.php?page='.self::$_bsk_gfcv_pages['blocked_data']['slug'] );
		?>
        <div class="wrap">
            <div id="icon-edit" class="icon32"><br/></div>
            <h2>Blocked form data</h3>
            <div style="clear: both;"></div>
            <form action="<?php echo $action_url; ?>" method="POST" id="bsk_gfcv_entries_form_ID">
            <div>
                <p class="bsk-gfcv-tips-box">This feature only availabe in <a href="<?php echo BSK_GFCV_Dashboard::$_bsk_gfcv_pro_verison_url; ?>" target="_blank">Pro version</a> with a <span style="font-weight: bold;">BUSINESS</span>( or above ) license.</p>
                <h4 style="margin-top: 40px;">Please select form plugin and form name to filter blocked data</h4>
                <p>
                    <?php
                    $selected_form_plugin = '';
                    $current_selected_form_plugin = '';
                    $form_plugin_list = BSK_GFCV_Dashboard_Common::bsk_gfcv_get_form_plugin();
                    if( isset( $_POST['bsk_gfcv_form_selected_plugin'] ) ){
                        $selected_form_plugin = sanitize_text_field( $_POST['bsk_gfcv_form_selected_plugin'] );
                        $current_selected_form_plugin = sanitize_text_field( $_POST['bsk_gfcv_form_current_selected_plugin'] );
                    }

                    ?>
                    <select name="bsk_gfcv_form_selected_plugin" id="bsk_gfcv_form_selected_plugin_ID" class="bsk-gfcv-entries-filter-select">
                        <option value="">Select form plugin...</option>
                        <?php
                        if( $form_plugin_list ){
                            foreach( $form_plugin_list as $form_plugin_id => $form_plugin_title ){
                                if( $selected_form_plugin == $form_plugin_id ){
                                    echo '<option value="'.$form_plugin_id.'" selected>'.$form_plugin_title.'</option>';
                                }else{
                                    echo '<option value="'.$form_plugin_id.'">'.$form_plugin_title.'</option>';
                                }
                            }
                        }
                        ?>
                    </select>
                    <?php
                    $selected_form_id = 0;
                    if( isset( $_POST['bsk_gfcv_form_select_to_list_entries'] ) && $_POST['bsk_gfcv_form_select_to_list_entries'] > 0 ){
                        if ( $current_selected_form_plugin && $current_selected_form_plugin == $selected_form_plugin ) {
                            $selected_form_id = $_POST['bsk_gfcv_form_select_to_list_entries'];
                        }
                    }
                    ?>
                    <select name="bsk_gfcv_form_select_to_list_entries" id="bsk_gfcv_form_select_to_list_entries_ID">
                        <option value="">Select form...</option>
                        <?php
                        if( $selected_form_plugin ){
                            $forms_list = BSK_GFCV_Dashboard_Common::bsk_gfcv_get_gf_forms( $selected_form_plugin );
                            foreach( $forms_list as $form_id => $form_title ){
                                if( $selected_form_id == $form_id ){
                                    echo '<option value="'.$form_id.'" selected>'.$form_title.'</option>';
                                }else{
                                    echo '<option value="'.$form_id.'">'.$form_title.'</option>';
                                }
                            }
                        }
                        ?>
                    </select>
                </p>
                <div>
                <?php
                $init_args = array();
                $init_args['form_plugin'] = $selected_form_plugin;
                $init_args['form_id'] = $selected_form_id;
                $_bsk_gfcv_OBJ_entries_lists = new BSK_GFCV_Dashboard_Entries_List( $init_args );

                //Fetch, prepare, sort, and filter our data...
                $_bsk_gfcv_OBJ_entries_lists->prepare_items();
                $_bsk_gfcv_OBJ_entries_lists->display();

                ?>
                </div>
            </div>
            <p style="margin-top: 40px;">
                <?php wp_nonce_field( 'bsk_gfbcv_settings_save_oper_nonce' ); ?>
                <input type="hidden" name="bsk_gfcv_form_current_selected_plugin" value="<?php echo $selected_form_plugin; ?>" />
            </p>
            </form>
        </div>
        <?php
    }

    function bsk_gfcv_settings() {
        $this->_bsk_gfcv_OBJ_settings->display();
    }

}
