<?php

class BSK_GFCV_Dashboard_List {
	
	public function __construct() {
		
        require_once( 'items.php' );
		
		add_action( 'bsk_gfcv_save_cv_list', array($this, 'bsk_gfcv_save_list_fun') );
		add_action( 'bsk_gfcv_save_rule', array($this, 'bsk_gfcv_save_rule_fun') );
		add_action( 'bsk_gfcv_delete_rule', array($this, 'bsk_gfcv_delete_rule_fun') );
		add_action( 'bsk_gfcv_delete_cv_list_by_id', array($this, 'bsk_gfcv_delete_list_by_id_fun') );
        add_action( 'bsk_gfcv_duplicate_list', array($this, 'bsk_gfcv_duplicate_cv_list_fun') );
	}
	
	function bsk_gfcv_list_edit( $list_id, $current_view ){
		global $wpdb;
		
        $list_type = 'CV_LIST';
        $list_table = $wpdb->prefix.BSK_GFCV::$_bsk_gfcv_list_tbl_name;
		$list_name = '';
		if ($list_id > 0){
			$sql = 'SELECT * FROM '.$list_table.' WHERE id = %d AND `list_type` = %s';
			$sql = $wpdb->prepare( $sql, $list_id, $list_type );
			$list_obj_array = $wpdb->get_results( $sql );
			if (count($list_obj_array) > 0){
				$list_name = $list_obj_array[0]->list_name;
				$list_date = date( 'Y-m-d', strtotime($list_obj_array[0]->date) );
			}
		}
		
        $base_page_url = admin_url( 'admin.php?page='.BSK_GFCV_Dashboard::$_bsk_gfcv_pages['base']['slug'] );
		$page_url = add_query_arg( array('view' => 'edit', 'id' => $list_id ), $base_page_url );
		?>
        <div class="wrap">
        	<div id="icon-edit" class="icon32"><br/></div>
            <h2>Validation List</h2>
            <div class="bsk-gfcv-edit-list-container">
                <form id="bsk_gfcv_list_edit_form_id" method="post" action="<?php echo $page_url; ?>">
				<?php if( isset($_GET['list_save']) && sanitize_text_field( $_GET['list_save'] ) == 'succ' ){ ?>
                <div class="notice notice-success is-dismissible inline">
                    <p>Validation List saved successfully</p>
                </div>
                <?php } ?>
                <?php if( $list_id < 1 ){ ?>
                <h3>Add New Validation List</h3>
                <?php }else{ ?>
                <h3>Edit Validation List</h3>
                <?php } ?>
                <p>
                    <label class="bsk-gfcv-admin-label">List Name: </label>
                    <input type="text" class="bsk-gfcv-add-list-input" name="bsk_gfcv_list_name" id="bsk_gfcv_list_name_ID" value="<?php echo $list_name; ?>" maxlength="512" />
                    <a class="bsk-gfcv-action-anchor" id="bsk_gfcv_blacklist_list_save_ID" style="margin-left:20px;">Save</a>
                </p>
                <p>
                    <input type="hidden" name="bsk_gfcv_list_id" value="<?php echo $list_id; ?>" />
                    <input type="hidden" name="bsk_gfcv_list_type" value="<?php echo $list_type; ?>" />
                    <input type="hidden" name="bsk_gfcv_action" value="save_cv_list" />
                    <?php wp_nonce_field( plugin_basename( __FILE__ ), 'bsk_gfcv_list_save_cv_list_oper_nonce' ); ?>
                </p>
                </form>
            </div>
            <?php if( $list_id > 0 ){ ?>
            <p style="margin-top: 20px;">&nbsp;</p>
			<a id="bsk_gfcv_edit_items_conteianer_anchor">&nbsp;</a>
            <div class="bsk-gfcv-edit-rule-container">
            	<?php if( isset($_GET['item_action']) && trim( sanitize_text_field( $_GET['item_action'] ) ) != "" ){ ?>
                <script type="text/javascript">
					jQuery(document).ready( function($) {
						$('html, body').animate({
						  scrollTop: $("#bsk_gfcv_edit_items_conteianer_anchor").offset().top
						}, 1000);
					});
				</script>
                <?php
					$notice_message = 'Successfully!';
					$notice_class 	 = 'notice-success';
					switch( sanitize_text_field( $_GET['item_action'] ) ){
						case 'save_succ':
							$notice_message = 'Rule saved successfully';
						break;
						case 'del_succ':
							$notice_message = 'Rule deleted';
						break;
					}
				?>
                <div class="notice <?php echo $notice_class; ?> is-dismissible inline">
                    <p><?php echo $notice_message; ?></p>
                </div>
                <?php } ?>
                <h3>Rule:</h3>
                <form id="bsk_gfcv_rules_form_id" method="post" action="<?php echo $page_url; ?>" enctype="multipart/form-data">
                <p style="margin-top:20px;" id="bsk_gfcv_add_rule_select_container_ID">
                    <label class="bsk-gfcv-admin-label">Add rule of:</label> 
                    <select class="bsk-gfcv-add-cv-rule" name="bsk_gfcv_add_cv_rule_name">
                        <option value="">Select...</option>
                        <?php
                        $rules = BSK_GFCV_Rules::get_system_rules_list();
                        if( $rules && is_array($rules) && count($rules) > 0 ){
                            foreach( $rules as $rule_slug => $rule_name ){
                                echo '<option value="'.$rule_slug.'">'.$rule_name.'</option>';
                            }
                        }
                        ?>
                    </select>
                    <span class="bsk-gfcv-rule-select-ajax-loader" style="display: none; margin-left: 10px;">
                        <?php echo BSK_GFCV::$ajax_loader; ?>
                    </span>
                </p>
                <p class="bsk-gfcv-rule-settings-error-message" style="display: none;"></p>
                <div class="bsk-gfcv-rule-settings-container"></div>
                <?php 
                $ajax_nonce = wp_create_nonce( "bsk-gfcv-rule-ajax-oper" );
                ?>
                <input type="hidden" class="bsk-gfcv-rule-ajax-nonce" value="<?php echo $ajax_nonce; ?>" />
                <p class="bsk-gfcv-rule-save-conatiner" style="display: none;">
                    <input type="button" class="bsk-gfcv-rule-save button-primary" value="Save rule" />
                </p>
                <p style="margin-top: 20px;">&nbsp;</p>
                <?php
                $_bsk_gfcv_OBJ_items = new BSK_GFCV_Dashboard_CV_Items( $list_id );
                
                //Fetch, prepare, sort, and filter our data...
                $_bsk_gfcv_OBJ_items->prepare_items();
                $_bsk_gfcv_OBJ_items->search_box( 'search', 'bsk-gfcv-rules-serch' );
				$_bsk_gfcv_OBJ_items->views();
				$_bsk_gfcv_OBJ_items->display();
                ?>
                <input type="hidden" name="bsk_gfcv_list_id" value="<?php echo $list_id; ?>" />
                <input type="hidden" name="bsk_gfcv_action" id="bsk_gfcv_action_ID" value="" />
                <input type="hidden" name="bsk_gfcv_rule_id" id="bsk_gfcv_rule_id_ID" value="0" />
                <input type="hidden" name="bsk_gfcv_items_list_type" id="bsk_gfcv_items_list_type_ID" value="<?php echo $list_type; ?>" />
                    
                <?php wp_nonce_field( plugin_basename( __FILE__ ), 'bsk_gfbcv_rule_save_oper_nonce' ); ?>
                </form>
            </div>
            <?php } ?>
        </div>
        <?php
	}

	function bsk_gfcv_save_list_fun( $data ){
		global $wpdb;

		//check nonce field
		if ( !wp_verify_nonce( $data['bsk_gfcv_list_save_cv_list_oper_nonce'], plugin_basename( __FILE__ ) ) ){
			die( 'Security check!' );
			return;
		}

		if ( !isset($data['bsk_gfcv_list_id']) ){
			return;
		}
        $list_table = $wpdb->prefix.BSK_GFCV::$_bsk_gfcv_list_tbl_name;
            
		$id = sanitize_text_field($data['bsk_gfcv_list_id']);
		$name = sanitize_text_field($data['bsk_gfcv_list_name']);
		$list_type = sanitize_text_field($data['bsk_gfcv_list_type']);
		$date = wp_date( 'Y-m-d H:i:s' );
		$page_name = sanitize_text_field($data['page']);

		$name = wp_unslash($name); 
		if ( $id > 0 ){
			$wpdb->update( $list_table, array( 'list_name' => $name, 'date' => $date), array( 'id' => $id ) );
		}else if($id == -1){
			//insert
			$wpdb->insert( $list_table, array( 'list_name' => $name, 'date' => $date, 'list_type' => $list_type ) );
			$id = $wpdb->insert_id;
		}
		
		add_action( 'admin_notices', array( $this, 'bsk_gfcv_save_list_successfully_fun') );
		
		$redirect_to = add_query_arg( 
                                    array( 'view' => 'edit', 'id' => $id, 'list_save' => 'succ'), 
                                    admin_url( 'admin.php?page='.BSK_GFCV_Dashboard::$_bsk_gfcv_pages['base']['slug'] ) 
                                    );
		wp_redirect( $redirect_to );
		exit;
	}
	
	function bsk_gfcv_save_rule_fun( $data ){
		global $wpdb;

		//check nonce field
		if ( ! wp_verify_nonce( sanitize_text_field($data['bsk_gfbcv_rule_save_oper_nonce']), plugin_basename( __FILE__ ) ) ){
			wp_die( 'Security check!' );
			return;
		}

        if( ! current_user_can( 'manage_options' ) ) {
            wp_die( 'You are not allowed to do this' );
        }

		if ( !isset($data['bsk_gfcv_list_id']) ){
			return;
		}
        
        $list_id = sanitize_text_field($data['bsk_gfcv_list_id']);
		$rule_name = sanitize_text_field($data['bsk_gfcv_add_cv_rule_name']);
		$list_type = sanitize_text_field($data['bsk_gfcv_items_list_type']);
		
        $rule_sysytem_settings = BSK_GFCV_Rules::get_rule_settings_by_slug( $rule_name );
        if( !$rule_sysytem_settings || !is_array($rule_sysytem_settings) || count($rule_sysytem_settings) < 1 ){
            return;
        }
        $rule_settings_to_save = array( 'slug' => $rule_name );
        if( isset($rule_sysytem_settings['MIN']) ){
            $rule_settings_to_save['MIN'] = sanitize_text_field($data['bsk_gfcv_BSK_CV_MIN']);
        }
        if( isset($rule_sysytem_settings['MIN_OPER']) ){
            $rule_settings_to_save['MIN_OPER'] = sanitize_text_field($data['bsk_gfcv_BSK_CV_MIN_OPER']);
        }
        if( isset($rule_sysytem_settings['MAX']) ){
            $rule_settings_to_save['MAX'] = sanitize_text_field($data['bsk_gfcv_BSK_CV_MAX']);
        }
        if( isset($rule_sysytem_settings['MAX_OPER']) ){
            $rule_settings_to_save['MAX_OPER'] = sanitize_text_field($data['bsk_gfcv_BSK_CV_MAX_OPER']);
        }
        if( isset($rule_sysytem_settings['TEXT']) ){
            $rule_settings_to_save['TEXT'] = wp_unslash(sanitize_text_field($data['bsk_gfcv_BSK_CV_TEXT']));
        }
		if( isset($rule_sysytem_settings['NUMBER']) ){
            $rule_settings_to_save['NUMBER'] = sanitize_text_field($data['bsk_gfcv_BSK_CV_NUMBER']);
        }
        if( isset($rule_sysytem_settings['ALLOW_PLUS']) ){
            $rule_settings_to_save['ALLOW_PLUS'] = wp_unslash(sanitize_text_field($data['bsk_gfcv_BSK_CV_ALLOW_PLUS']));
        }
        if( isset($rule_sysytem_settings['ALLOW_MINUS']) ){
            $rule_settings_to_save['ALLOW_MINUS'] = wp_unslash(sanitize_text_field($data['bsk_gfcv_BSK_CV_ALLOW_MINUS']));
        }
        if( isset($rule_sysytem_settings['message']) ){
            $rule_settings_to_save['message'] = wp_unslash(sanitize_text_field($data['bsk_gfcv_rule_message']));
        }
        
		
        $items_table = $wpdb->prefix.BSK_GFCV::$_bsk_gfcv_items_tbl_name;
		//insert
		$wpdb->insert( $items_table, array( 'list_id' => $list_id, 'value' => serialize($rule_settings_to_save) ) );
		
		$redirect_to = add_query_arg( 
                                    array( 'view' => 'edit', 'id' => $list_id, 'item_action' => 'save_succ' ), 
                                    admin_url( 'admin.php?page='.BSK_GFCV_Dashboard::$_bsk_gfcv_pages['base']['slug'] )
                                    );
		wp_redirect( $redirect_to );
		exit;
	}
	
	function bsk_gfcv_delete_rule_fun( $data ){
		global $wpdb;

		//check nonce field
		if ( !wp_verify_nonce( sanitize_text_field($data['bsk_gfbcv_rule_save_oper_nonce']), plugin_basename( __FILE__ ) ) ){
			die( 'Security check!' );
			return;
		}

        if( ! current_user_can( 'manage_options' ) ) {
            wp_die( 'You are not allowed to do this' );
        }

		if ( !isset($data['bsk_gfcv_rule_id']) ){
			return;
		}
        $items_table = $wpdb->prefix.BSK_GFCV::$_bsk_gfcv_items_tbl_name;
        
		$list_id = sanitize_text_field($data['bsk_gfcv_list_id']);
		$id = sanitize_text_field($data['bsk_gfcv_rule_id']) + 0;
		$page_name = sanitize_text_field($data['page']);
		$list_type = 'CV_LIST';
		
		$sql = 'DELETE FROM `'.$items_table.'` WHERE `id` = %d';
		$sql = $wpdb->prepare( $sql, $id );
		
		$wpdb->query( $sql );
		
		$redirect_to = add_query_arg( 
                                    array( 'view' => 'edit', 'id' => $list_id, 'item_action' => 'del_succ' ), 
                                    admin_url( 'admin.php?page='.BSK_GFCV_Dashboard::$_bsk_gfcv_pages['base']['slug'] ) 
                                    );
		wp_redirect( $redirect_to );
		exit;
	}
	
	function bsk_gfcv_delete_list_by_id_fun( $data ){
		//check nonce field
		if ( !wp_verify_nonce( sanitize_text_field($data['_wpnonce']), 'bsk_gfcv_list_oper_nonce' ) ){
			die( 'Security check!' );
			return;
		}
		
        if( ! current_user_can( 'manage_options' ) ) {
            wp_die( 'You are not allowed to do this' );
        }
		
		$list_id = absint(sanitize_text_field($data['bsk_gfcv_list_id']));
		if( $list_id < 1 ){
			add_action( 'admin_notices', array($this, 'bsk_gfcv_delete_list_invlaid_id_fun') );
		}
		
		global $wpdb;
		
        $list_table = $wpdb->prefix.BSK_GFCV::$_bsk_gfcv_list_tbl_name;
        $items_table = $wpdb->prefix.BSK_GFCV::$_bsk_gfcv_items_tbl_name;
        
		//delete items
		$items_count = $wpdb->get_var( $wpdb->prepare( 'SELECT COUNT(*) FROM `'.$items_table.'` WHERE `list_id` = %d', $list_id) );
		if( $items_count > 0 ){
			$sql = 'DELETE FROM `'.$items_table.'` WHERE `list_id` = %d';
			$sql = $wpdb->prepare( $sql, $list_id );
			$wpdb->query( $sql );
		}
		
		//delete list
		$sql = 'DELETE FROM `'.$list_table.'` WHERE `id` = %d';
		$sql = $wpdb->prepare( $sql, $list_id );
		$wpdb->query( $sql );
		
		add_action( 'admin_notices', array($this, 'bsk_gfcv_delete_list_successfully_fun') );
	}
	
	function bsk_gfcv_delete_list_invlaid_id_fun(){
		?>
        <div class="notice notice-error is-dismissible">
            <p>Delete list failed: Invalid list id</p>
        </div>
        <?php
	}
	
	function bsk_gfcv_delete_list_successfully_fun(){
		?>
        <div class="notice notice-success is-dismissible">
            <p>The list and all rules in it have been deleted</p>
        </div>
        <?php
	}
    
    function bsk_gfcv_duplicate_cv_list_fun( $data ){
        
        //check nonce field
		if ( ! check_admin_referer( 'duplicate-list-160' ) ){
			wp_die( 'Security check!' );
		}
        
        if( ! current_user_can( 'manage_options' ) ) {
            wp_die( 'You are not allowed to do this' );
        }
        
        if( ! isset( $data['id'] ) ) {
            return;
        }
        
        $list_id = absint( sanitize_text_field( $data['id'] ) );
        if ( $list_id < 1 ) {
            add_action( 'admin_notices', array($this, 'bsk_gfcv_invalid_list_id_to_duplicate_fun') );
            return;
        }
        
        global $wpdb;
		
        $list_table = $wpdb->prefix.BSK_GFCV::$_bsk_gfcv_list_tbl_name;
        $items_table = $wpdb->prefix.BSK_GFCV::$_bsk_gfcv_items_tbl_name;
        
        //duplicate list
		$sql = 'SELECT * FROM `'.$list_table.'` WHERE `id` = %d';
		$sql = $wpdb->prepare( $sql, $list_id );
		$results = $wpdb->get_results( $sql );
        if( ! $results || ! is_array( $results ) || count( $results ) < 1 ) {
            add_action( 'admin_notices', array($this, 'bsk_gfcv_invalid_list_id_to_duplicate_fun') );
            return;
        }
        $list_obj = $results[0];
        $data_to_insert = array();
        $data_to_insert['list_name'] = $list_obj->list_name.' - Draft';
        $data_to_insert['list_type'] = $list_obj->list_type;
        $data_to_insert['check_way'] = $list_obj->check_way;
        $data_to_insert['extra'] = $list_obj->extra;
        $data_to_insert['date'] = wp_date( 'Y-m-d H:i:s' );
        
        $wpdb->insert( $list_table, $data_to_insert, array( '%s', '%s', '%s', '%s', '%s', '%s' ) );
        $new_list_id = $wpdb->insert_id;
        if ( $new_list_id < 1 ) {
            add_action( 'admin_notices', array($this, 'bsk_gfcv_invalid_list_id_to_duplicate_fun') );
            return;
        }
        
		//duplicate items
		$sql = 'SELECT * FROM `'.$items_table.'` WHERE `list_id` = %d';
        $sql = $wpdb->prepare( $sql, $list_id );
        $results = $wpdb->get_results( $sql );
		if ( $results && is_array( $results ) && count( $results ) ) {
            foreach ( $results as $item_obj ) {
                $item_data_to_insert = array();
                $item_data_to_insert['list_id'] = $new_list_id;
                $item_data_to_insert['value'] = $item_obj->value;
                $item_data_to_insert['hits'] = 0;
                
                $wpdb->insert( $items_table, $item_data_to_insert, array( '%d', '%s', '%d' ) );
            }
        }
		
		add_action( 'admin_notices', array($this, 'bsk_gfcv_duplicate_list_successfully_fun') );
    }
    
    function bsk_gfcv_invalid_list_id_to_duplicate_fun() {
        ?>
        <div class="notice notice-error is-dismissible">
            <p>Invalid list id to duplicate</p>
        </div>
        <?php
    }
    
    function bsk_gfcv_duplicate_list_successfully_fun() {
        ?>
        <div class="notice notice-success is-dismissible">
            <p>Duplicate list successfully</p>
        </div>
        <?php
    }
}