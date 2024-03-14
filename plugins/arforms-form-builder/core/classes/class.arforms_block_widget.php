<?php

class arforms_block_widget{

    public $attributes = array(
		'form_id' => array(
			'type' => 'string',
			'default' => 0
		),
		'disable_multicolumn' => array(
			'type' => 'boolean',
			'default' => true
		),
		'is_widget_form' => array(
			'type' => 'boolean',
			'default' => false
		)
	);

    function __construct(){

        add_action( 'init', array( $this, 'arforms_register_gutenberg_blocks') );

		if (! empty($GLOBALS['wp_version']) && version_compare($GLOBALS['wp_version'], '5.7.2', '>') ) {
			add_filter('block_categories_all', array( $this, 'arforms_gutenberg_category' ), 10, 2);
		} else {
			add_filter('block_categories', array( $this, 'arforms_gutenberg_category' ), 10, 2);
		}

        add_action( 'enqueue_block_editor_assets', array( $this, 'arflite_enqueue_gutenberg_assets' ) );
    }

    function arforms_gutenberg_category( $category, $post ){
		$new_category     = array(
            array(
            	'slug'  => 'arforms',
            	'title' => 'ARForms Blocks',
            ),
		);
		$final_categories = array_merge($category, $new_category);
		return $final_categories;
	}

    

	protected function get_block_properties() {
		return array(
			'render_callback' => array( $this, 'render_block' ),
			'attributes'      => $this->attributes,
		);
	}

    public function render_block( $attributes = array() ) {
        
        global $tbl_arf_forms, $arformsmain;
        $form_id = !empty( $attributes['form_id'] ) ? $attributes['form_id'] : '';
		$is_widget_form = !empty( $attributes['is_widget_form'] ) ? $attributes['is_widget_form'] : false;
		$disable_multicol = isset( $attributes['disable_multicolumn'] ) ? $attributes['disable_multicolumn'] : true;

        if(empty($form_id)) {
            return esc_html__("Please select valid form",'arforms-form-builder');
        }

        global $wpdb, $ARFLiteMdlDb,$arfliteform, $arflitemainhelper, $arfliterecordcontroller;

        $arfliterecordcontroller->arflite_register_scripts();

		if( $arformsmain->arforms_is_pro_active() ){
			if( version_compare( $arformsmain->arforms_get_premium_version(), '6.1', '<' ) ){
				$form_data = $wpdb->get_row( $wpdb->prepare( "SELECT id,form_key FROM {$tbl_arf_forms} WHERE arf_lite_form_id = %d OR ( arforms_is_migrated_form = %d AND arf_is_lite_form = %d )", $form_id, 1, 0 ) ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_arf_forms is table name defined globally. False Positive alarm
				if( !empty( $form_data ) ){
					$form_key = $form_data->form_key;
					$form_id = $form_data->id;
				}
			} else {
				$form_key = $wpdb->get_var( $wpdb->prepare( "SELECT form_key FROM {$tbl_arf_forms} WHERE id = %d", $form_id ) ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_arf_forms is table name defined globally. False Positive alarm
			}
		} else {
			$form_key = $wpdb->get_var( $wpdb->prepare( "SELECT form_key FROM {$tbl_arf_forms} WHERE id = %d AND arf_is_lite_form = %d", $form_id, 1 ) ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_arf_forms is table name defined globally. False Positive alarm
		}

        if( empty( $form_id ) || empty( $form_key ) ){
            return esc_html__( 'Please select valid form', 'arforms-form-builder' );
        }

        $params = '';

        if( !empty( $_REQUEST['context'] ) && 'edit' == $_REQUEST['context'] ){
            $params = ' is_gutenberg="true" ';
        }
		
		if( true == $is_widget_form && empty( $_REQUEST['context'] ) ){
			$res = wp_cache_get( 'arforms_form_data_' . $form_id );
			if ( false == $res ) {
				$res = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . $tbl_arf_forms . ' WHERE id = %d AND arf_is_lite_form = %d', $form_id, 1 ), 'ARRAY_A' );//phpcs:ignore
				wp_cache_set( 'arforms_form_data_' . $form_id, $res );
			}

			$form_data = ( isset( $res[0] ) && is_array( $res ) && count( $res ) > 0 ) ? $res[0] : $res;

			if( empty( $form_data ) ){
				return esc_html__( 'Please select valid form', 'arforms-form-builder' );
			}
			
			//$formoptions = maybe_unserialize( $res['options'] );

			if ( is_ssl() ) {
				$upload_main_url = str_replace( 'http://', 'https://', ARFLITE_UPLOAD_URL . '/maincss' );
			} else {
				$upload_main_url = ARFLITE_UPLOAD_URL . '/maincss';
			}

			$is_material = false;
			$handler     = '';
			if ( isset( $form_data ) && is_array( $form_data ) && ! empty( $form_data ) && count( $form_data ) ) {
				$form_css = maybe_unserialize( $form_data['form_css'] );
				if ( isset( $form_css ) && is_array( $form_css ) && ! empty( $form_css ) ) {
					$input_style = $form_css['arfinputstyle'];
					if ( $input_style == 'material' ) {
						$is_material = true;
						$handler     = '_maincss_materialize';
					}
				}
			}

			global $arflitemainhelper, $arfliterecordcontroller, $arfliteformcontroller, $arfliteversion;
			if ( $is_material ) {
				if ( isset( $form_id ) ) {
					$fid = $upload_main_url . '/maincss_materialize_' . $form_id . '.css';
				}
			} else {
				if ( isset( $form_id ) ) {
					$fid = $upload_main_url . '/maincss_' . $form_id . '.css';
				}
			}
			$arflite_data_uniq_id = rand( 1, 99999 );
			if ( empty( $arflite_data_uniq_id ) || $arflite_data_uniq_id == '' ) {
				if ( isset( $form_id ) ) {
					$arflite_data_uniq_id = $form_id;
				}
			}

			if ( isset( $form_id ) ) {
				wp_register_style( 'arfliteformscss' . $handler . '_' . $form_id, $fid, array(), $arfliteversion );
			}
			$arflite_func_val = '';
			if ( isset( $form_id ) ) {
				$arflite_func_val = apply_filters( 'arflite_hide_forms', $arfliteformcontroller->arflite_class_to_hide_form( $form_id ), $form_id );
			}

			if ( $arflite_func_val == '' ) {
				if ( isset( $form_id ) ) {
					$arflitemainhelper->arflite_load_styles( array( 'arfliteformscss' . $handler . '_' . $form_id, 'arflitedisplaycss', 'bootstrap' ), true );
				}
			} else {
				if ( isset( $form_id ) ) {
					$arflitemainhelper->arflite_load_styles( array( 'arfliteformscss' . $handler . '_' . $form_id ), true );
				}
			}

			echo $arfliteformcontroller->arflite_get_form_style( $form_id, $arflite_data_uniq_id ); //phpcs:ignore
		}

        //$params = apply_filters( 'arforms_modify_form_shortcode_params', $params, $attributes );

        if ( is_plugin_active( 'arforms/arforms.php' ) ) {
            $content = do_shortcode( '[ARForms id='.$form_id.' '.$params.' ]' );
        } else {
            $content = do_shortcode( '[ARForms id='.$form_id.' '.$params.' ]' );
        }

		if( true == $is_widget_form ){
			$disable_multicol_class = ( false == $disable_multicol ) ? 'arf_multicolumn_widget_form' : '';
			$new_content = '<div class="arf_widget_form '.$disable_multicol_class.'">';
			$new_content .= $content;
			$new_content .= '</div>';

			$content = $new_content;
		}

        return $content;
    }


	function arforms_register_gutenberg_blocks(){

		register_block_type( ARFLITE_FORMPATH . '/js/build/arforms-form', $this->get_block_properties() );

		$script_handle = generate_block_asset_handle( 'arforms-form-builder/arforms-form', 'editorScript' );

		wp_set_script_translations( $script_handle, 'arforms-form-builder', ARFLITE_FORMPATH . '/js/build/arforms-form/languages/' );

	}

    function arflite_enqueue_gutenberg_assets() {

		global $arfliteversion, $wpdb, $tbl_arf_forms, $arfliteformhelper, $arformsmain;

		$page = isset( $_SERVER['PHP_SELF'] ) ? basename( sanitize_text_field( $_SERVER['PHP_SELF'] ) ) : '';

		if ( in_array( $page, array( 'post.php', 'page.php', 'page-new.php', 'post-new.php' ) ) || ( isset( $_GET ) && isset( $_GET['page'] ) && sanitize_text_field( $_GET['page'] ) == 'ARForms-Lite-entry-templates' ) ) {

			$where_clause = ' AND arf_is_lite_form = 1';
			if( $arformsmain->arforms_is_pro_active() ){
				$where_clause = ' AND arf_is_lite_form = 0';
			}

			$arforms_forms_lite_data = $wpdb->get_results( 'SELECT * FROM `' . $tbl_arf_forms . "` WHERE is_template=0 AND (status is NULL OR status = '' OR status = 'published') {$where_clause} ORDER BY id DESC" );//phpcs:ignore

			$arforms_forms_lite_list = array();
			$n                       = 0;
			foreach ( $arforms_forms_lite_data as $k => $value ) {
				$arforms_forms_lite_list[ $n ]['id']    = $value->id;
				$arforms_forms_lite_list[ $n ]['label'] = $value->name . ' (id: ' . $value->id . ')';
				$n++;
			}

			$arflite_gutenberg_show_previewdata_nonce = wp_create_nonce('arflite_gutenberg_show_previewdata_nonce');

			wp_localize_script(
				'wp-block-editor', 
				'arformslite_gutenberg_script_objects', 
				array(
					'arflite_form_list' => $arforms_forms_lite_list,
					'ajax_url'=>admin_url('admin-ajax.php'),
					'is_widget_page' => false,
					'arforms_edit_gutenberg_adminurl'=>admin_url("admin.php?page=ARForms&arfaction=edit")
				)
			);

		} elseif ( in_array( $page, array( 'widgets.php', 'customize.php' ) ) ) {

			wp_enqueue_style( 'arformslite_selectpicker', ARFLITEURL . '/css/arflite_selectpicker.css', array(), $arfliteversion );
			wp_enqueue_style( 'arflite-insert-form-css', ARFLITEURL . '/css/arflite_insert_form_style.css', array(), $arfliteversion );

			$arforms_forms_lite_data = $wpdb->get_results( 'SELECT * FROM `' . $tbl_arf_forms . "` WHERE is_template=0 AND (status is NULL OR status = '' OR status = 'published') ORDER BY id DESC" );//phpcs:ignore

			$arforms_forms_lite_list = array();
			$n                       = 0;
			foreach ( $arforms_forms_lite_data as $k => $value ) {
				$arforms_forms_lite_list[ $n ]['id']    = $value->id;
				$arforms_forms_lite_list[ $n ]['label'] = $value->name . ' (id: ' . $value->id . ')';
				$n++;
			}

			wp_localize_script(
				'wp-block-editor', 
				'arformslite_gutenberg_script_objects', 
				array(
					'arflite_form_list' => $arforms_forms_lite_list,
					'ajax_url'=>admin_url('admin-ajax.php'),
					'is_widget_page' => true,
					'arforms_edit_gutenberg_adminurl'=>admin_url("admin.php?page=ARForms&arfaction=edit")
				)
			);

		}

	}

}

global $arforms_block_widget;
$arforms_block_widget = new arforms_block_widget();