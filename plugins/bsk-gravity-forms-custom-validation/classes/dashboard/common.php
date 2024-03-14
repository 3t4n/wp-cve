<?php
class BSK_GFCV_Dashboard_Common {
	
	public function __construct( $args ) {
		
	}
	
	
    public static function bsk_gfcv_get_form_plugin() {
		global $wpdb;
        
        $entries_table_name = $wpdb->prefix . BSK_GFCV::$_bsk_gfcv_entries_tbl_name;
        $results = $wpdb->get_results( 'SELECT DISTINCT( `forms` ) from `'.$entries_table_name.'` ORDER BY `forms` ASC' );
        if( !$results || !is_array( $results ) || count( $results ) < 1 ){
            return false;
        }
        
        $data_to_return = array();
        foreach( $results as $form_name_obj ){
            $form_name_title = '';
            if ( isset( BSK_GFCV::$_supported_plugins[$form_name_obj->forms] ) ){
                $form_name_title = BSK_GFCV::$_supported_plugins[$form_name_obj->forms]['title'];
            }else{
                switch ( $form_name_obj->forms ) {
                    case 'FF':
                        $form_name_title = 'Formidable Forms';
                    break;
                    case 'GF':
                        $form_name_title = 'Gravity Forms';
                    break;
                    case 'WPF':
                        $form_name_title = 'WPForms';
                    break;
                    case 'NJF':
                        $form_name_title = 'Ninja Forms';
                    break;
                    case 'CF7':
                        $form_name_title = 'Contact Form 7';
                    break;
                }
            }
            $data_to_return[$form_name_obj->forms] = $form_name_title;
        }
        
		return $data_to_return;
	}
	
    public static function bsk_gfcv_get_gf_forms( $form_plugin ) {
		global $wpdb;
        
        $entries_table_name = $wpdb->prefix . BSK_GFCV::$_bsk_gfcv_entries_tbl_name;
        
        $data_to_return = array();
        //Gravity Forms
        if ( $form_plugin == 'GF' && isset( BSK_GFCV::$_supported_plugins['GF'] ) ){
            if ( ! function_exists( 'get_plugins' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}
            $gf_table_name = 'rg_form';
            if ( version_compare( BSK_GFCV::$_supported_plugins['GF']['version'], '2.2', '>=' ) ){
                 $gf_table_name = 'gf_form';
            }
            
            $gf_table_name = $wpdb->prefix . $gf_table_name;
            
            $sql = 'SELECT DISTINCT( E.`form_id` ), G.`title` from `'.$entries_table_name.'` AS E '.
                   'LEFT JOIN `'.$gf_table_name.'` AS G ON E.`form_id` = G.`id` '.
                   'WHERE E.`forms` LIKE "GF" ORDER BY G.`title` ASC';
            $results = $wpdb->get_results( $sql );
            if( !$results || !is_array( $results ) || count( $results ) < 1 ){
                return false;
            }
            
            foreach( $results as $gf_form_data ){
                $data_to_return[$gf_form_data->form_id] = $gf_form_data->title;
            }
        }
        
        if ( $form_plugin == 'FF' && isset( BSK_GFCV::$_supported_plugins['FF'] ) ){
            $forms = FrmForm::getAll(
                                        array(
                                            'is_template' => 0,
                                            'status'      => 'published',
                                            array(
                                                'or'               => 1,
                                                'parent_form_id'   => null,
                                                'parent_form_id <' => 1,
                                            ),
                                        ),
                                        'name'
                                    );
            if ( $forms && is_array( $forms ) && count( $forms ) > 0 ) {
                $sql = 'SELECT DISTINCT( E.`form_id` ) from `'.$entries_table_name.'` AS E '.
                       'WHERE E.`forms` LIKE "FF"';
                $results = $wpdb->get_results( $sql );
                if( !$results || !is_array( $results ) || count( $results ) < 1 ){
                    return false;
                }
                $entries_existing_form_ids = array();
                foreach( $results as $ff_form_existing ){
                    $entries_existing_form_ids[] = $ff_form_existing->form_id;
                }
                
                $forms_data_array = array();
                foreach( $forms as $ff_form_data ){
                    if( in_array($ff_form_data->id, $entries_existing_form_ids ) ){
                        $data_to_return[$ff_form_data->id] = $ff_form_data->name;
                    }
                }
            }
        }
        
        if ( $form_plugin == 'WPF' && isset( BSK_GFCV::$_supported_plugins['WPF'] ) ){
            
            $forms = wpforms()->form->get();
            
            if ( $forms && is_array( $forms ) && count( $forms ) > 0 ) {
                $sql = 'SELECT DISTINCT( E.`form_id` ) from `'.$entries_table_name.'` AS E '.
                       'WHERE E.`forms` LIKE "WPF"';
                $results = $wpdb->get_results( $sql );
                if( !$results || !is_array( $results ) || count( $results ) < 1 ){
                    return false;
                }
                $entries_existing_form_ids = array();
                foreach( $results as $wpf_form_existing ){
                    $entries_existing_form_ids[] = $wpf_form_existing->form_id;
                }
                
                $forms_data_array = array();
                foreach( $forms as $wpf_form_data ){
                    if( in_array($wpf_form_data->ID, $entries_existing_form_ids ) ){
                        $data_to_return[$wpf_form_data->ID] = $wpf_form_data->post_title;
                    }
                }
            }
        }

        if ( $form_plugin == 'CF7' && isset( BSK_GFCV::$_supported_plugins['CF7'] ) ){
            $sql = 'SELECT `ID`, `post_title` FROM `'.$wpdb->posts.'` WHERE `post_type` LIKE "wpcf7_contact_form" '.
                   'AND `ID` IN( SELECT DISTINCT( E.`form_id` ) from `'.$entries_table_name.'` AS E '.
                   'WHERE E.`forms` LIKE "CF7" )';
            $results = $wpdb->get_results( $sql );
            if( !$results || !is_array( $results ) || count( $results ) < 1 ){
                return false;
            }

            foreach( $results as $cf7_form_existing ){
                $data_to_return[$cf7_form_existing->ID] = $cf7_form_existing->post_title;
            }
            
        }
        
		return $data_to_return;
	}
    
    public static function bsk_gfcv_render_entry_html( $form_submit_data, $hits_data, $entry_id, $ip, $form_plugin ) {
		global $wpdb;
                
        $hits_tbl = $wpdb->prefix.BSK_GFCV::$_bsk_gfcv_hits_tbl_name;
        $list_tbl = $wpdb->prefix.BSK_GFCV::$_bsk_gfcv_list_tbl_name;
        $items_tbl = $wpdb->prefix.BSK_GFCV::$_bsk_gfcv_items_tbl_name;

        if( $hits_data == false && $entry_id ){
            //get hits data
            $sql = 'SELECT H.*, L.`list_name`, L.`list_type`, L.`check_way`, L.`extra` AS list_extra, I.`value` '.
                   'FROM `'.$hits_tbl.'` AS H LEFT JOIN `'.$list_tbl.'` AS L ON H.list_id = L.id '.
                   'LEFT JOIN `'.$items_tbl.'` AS I ON H.`item_id` = I.id '.
                   'WHERE H.`entry_id` = %d';
            $sql = $wpdb->prepare( $sql, $entry_id );
            $hits_data_results = $wpdb->get_results( $sql );
            if( $hits_data_results && is_array( $hits_data_results ) && count( $hits_data_results ) > 0 ){
                $hits_data = array();
                foreach( $hits_data_results as $hit_data_obj ){
                    if( !isset( $hits_data[$hit_data_obj->field_id] ) ){
                        $hits_data[$hit_data_obj->field_id] = array();
                    }
                    $hits_data[$hit_data_obj->field_id]['list_id'] = $hit_data_obj->list_id;
                    $hits_data[$hit_data_obj->field_id]['list_name'] = $hit_data_obj->list_name;
                    $hits_data[$hit_data_obj->field_id]['list_type'] = $hit_data_obj->list_type;
                    $hits_data[$hit_data_obj->field_id]['list_check_way'] = $hit_data_obj->check_way;
                    $hits_data[$hit_data_obj->field_id]['list_extra'] = $hit_data_obj->list_extra;
                    $hits_data[$hit_data_obj->field_id]['extra_data'] = $hit_data_obj->extra_data;
                    if( !isset( $hits_data[$hit_data_obj->field_id]['items_value'] ) ){
                        $hits_data[$hit_data_obj->field_id]['items_value'] = array();
                    }
                    $hits_data[$hit_data_obj->field_id]['items_value'][] = ( $hit_data_obj->item_id == -1 || $hit_data_obj->item_id == -2 ) ? 'NO_ITEM_MATCH' : $hit_data_obj->value;
                }
            }
        }

        if( !$hits_data || !is_array( $hits_data ) || count( $hits_data ) < 1 ){
            return '<p>No valid keywords hit data</p>';
        }
        
        ob_start();
        
        $form_data = maybe_unserialize( $form_submit_data );
        if( $form_data && is_array( $form_data ) && count( $form_data ) > 0 ){
        ?>
        <div class="bsk-gfcv-entry-form-data-container">
            <table class="widefat striped">
                <thead>
                    <?php if ( $form_plugin == 'CF7' ) { ?>
                    <th>Field Name</th>
                    <?php } else { ?>
                    <th>Field ID</th>
                    <th>Field label</th>
                    <?php } ?>
                    <th>Field value</th>
                    <th>&nbsp;</th>
                </thead>
                <tbody>

        <?php
        foreach( $form_data as $field_ID => $field_data ){
            if( $field_ID == 'form_id' ){
                continue;
            }
            $blocked_info = '';
            if( $hits_data && isset( $hits_data[$field_ID] ) ){
                $blocked_data = $hits_data[$field_ID];
                $blocked_item_extra_data = maybe_unserialize( $hits_data[$field_ID]['extra_data'] );

                $blocked_info .= 'blocked by list: ';


                $blocked_list_id = intval( $hits_data[$field_ID]['list_id'] );
                $list_edit_url = admin_url( 'admin.php?page='.BSK_GFCV_Dashboard::$_bsk_gfcv_pages['base']['slug'].'&view=edit&id='.$blocked_list_id );

                $blocked_info .= '<a href="'.$list_edit_url.'">'.$hits_data[$field_ID]['list_name'].'</a>';
                $blocked_info .= '<span class="bsk-gfcv-entry-blocked-keyword" style="display: block;">'.$blocked_item_extra_data['CV_message'].'</span>';
            }
        ?>
                <tr>
                    <?php if ( $form_plugin == 'CF7' ) { ?>
                    <td class="bsk-gfcv-column-ID"><?php echo $field_ID; ?></td>
                    <?php } else { ?>
                    <td class="bsk-gfcv-column-ID"><?php echo $field_ID; ?></td>
                    <td class="bsk-gfcv-column-label"><label><?php echo $field_data['label']; ?></label></td>
                    <?php } ?>
                    <td class="bsk-gfcv-column-value"><?php echo $field_data['value']; ?></td>
                    <td class="bsk-gfcv-column-blocked-info"><?php echo $blocked_info; ?></td>
                </tr>
        <?php
        }
        ?>
                </tbody>
            </table>
        </div>
        <?php
        }
        
        $entry_html = ob_get_contents();
        ob_end_clean();
        
        return $entry_html;
	}
    
    public static function bsk_gfcv_get_mail_tmpl(){
        require_once( 'common-tmpl.php' );
        
        return $email_html_tmpl;
    }

    public static function bsk_gfcv_is_form_plugin_supported( $form_plugin ){
        
        //check license type

        $settings_data = get_option( BSK_GFCV_Dashboard::$_plugin_settings_option, false );
        $supported_form_plugins = array( 'GF' );
        if( $settings_data && is_array( $settings_data ) && count( $settings_data ) > 0 ){
            if( isset( $settings_data['supported_form_plugins'] ) && count( $settings_data['supported_form_plugins'] ) > 0 ){
                $supported_form_plugins = $settings_data['supported_form_plugins'];
            }
        }

        return ( in_array( $form_plugin, $supported_form_plugins ) && isset( BSK_GFCV::$_supported_plugins[$form_plugin] ) );
    }

    public static function bsk_gfcv_get_list_by_type( $list_type, $selected ){
		global $wpdb;
		
		if( $list_type == "" ){
			return '';
		}
		
        $list_table = $wpdb->prefix.BSK_GFCV::$_bsk_gfcv_list_tbl_name;
		$options_str = '';
		
		$sql = 'SELECT * FROM `'.$list_table.'` WHERE `list_type` = %s ORDER BY `list_name` ASC';
		$sql = $wpdb->prepare( $sql, $list_type );
		$results = $wpdb->get_results( $sql );
		if( $results && is_array($results) && count($results) > 0 ){
			foreach( $results as $list_obj ){
				$checked_str = $list_obj->id == $selected ? ' selected' : '';
				$options_str .= '<option value="'.$list_obj->id.'"'.$checked_str.'>'.$list_obj->list_name.'</option>';
			}
		}
		
		return $options_str;
	}

    public static function get_ip() {

		$ip = $_SERVER['REMOTE_ADDR'];

		// HTTP_X_FORWARDED_FOR can return a comma separated list of IPs; use the first one
		$ips = explode( ',', $ip );

		return $ips[0];
	}

}
