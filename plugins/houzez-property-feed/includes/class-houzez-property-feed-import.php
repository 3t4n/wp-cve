<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Houzez Property Feed Import Functions
 */
class Houzez_Property_Feed_Import {

	public function __construct() {

        add_action( 'admin_init', array( $this, 'check_not_multiple_if_no_pro') );

        add_action( 'admin_init', array( $this, 'check_clone'), 11 );

        add_action( 'admin_init', array( $this, 'save_import_settings') );

        add_action( 'admin_init', array( $this, 'toggle_import_running_status') );

        add_action( 'admin_init', array( $this, 'delete_import') );

        add_action( "houzez_property_feed_property_imported", array( $this, 'perform_field_mapping' ), 1, 3 );
        add_action( 'houzez_property_feed_property_imported', array( $this, 'set_generic_houzez_property_data'), 1, 3 );

        add_filter( 'houzez_property_feed_xml_mapped_field_value', array( $this, 'get_xml_mapped_field_value' ), 1, 4 );
        add_filter( 'houzez_property_feed_csv_mapped_field_value', array( $this, 'get_csv_mapped_field_value' ), 1, 4 );

        add_action( 'add_meta_boxes', array( $this, 'import_data_meta_box') );

        add_action( "houzez_property_feed_post_import_properties", array( $this, 'set_location_taxonomy_parents' ) );
	}

    public function check_not_multiple_if_no_pro()
    {
        if ( isset($_GET['action']) && ( $_GET['action'] == 'addimport' || $_GET['action'] == 'cloneimport' ) )
        {
            if ( apply_filters( 'houzez_property_feed_pro_active', false ) !== true ) 
            {
                $options = get_option( 'houzez_property_feed', array() );
                $imports = ( isset($options['imports']) && is_array($options['imports']) && !empty($options['imports']) ) ? $options['imports'] : array();

                foreach ( $imports as $key => $import )
                {
                    if ( $imports[$key]['deleted'] && $imports[$key]['deleted'] === true )
                    {
                        unset( $imports[$key] );
                    }
                }

                if ( count($imports) >=1 )
                {
                    wp_redirect( admin_url( 'admin.php?page=houzez-property-feed-import&hpferrormessage=' . urlencode(__( 'Maximum number of imports reached. Upgrade to PRO if wanting to benfit from multiple imports and more', 'houzezpropertyfeed' ) ) ) );
                    die();
                }
            }
        }
    }

    public function check_clone()
    {
        if ( isset($_GET['action']) && $_GET['action'] == 'cloneimport' )
        {
            $import_id = ( isset($_GET['import_id']) && !empty(sanitize_text_field($_GET['import_id'])) ) ? (int)$_GET['import_id'] : false;

            if ( $import_id === false )
            {
                wp_redirect( admin_url( 'admin.php?page=houzez-property-feed-import&hpferrormessage=' . urlencode(__( 'No import ID to clone found', 'houzezpropertyfeed' ) ) ) );
                die();
            }

            $options = get_option( 'houzez_property_feed' , array() );

            $imports = ( isset($options['imports']) && is_array($options['imports']) && !empty($options['imports']) ) ? $options['imports'] : array();
            if ( !isset($imports[$import_id]) )
            {
                wp_redirect( admin_url( 'admin.php?page=houzez-property-feed-import&hpferrormessage=' . urlencode(__( 'Import wanting to clone not found', 'houzezpropertyfeed' ) ) ) );
                die();
            }

            $import_settings = $imports[$import_id];

            $new_import_id = time();

            $options['imports'][$new_import_id] = $import_settings;

            update_option( 'houzez_property_feed', $options );

            wp_redirect( admin_url( 'admin.php?page=houzez-property-feed-import&hpfsuccessmessage=' . __( 'Import cloned successfully', 'houzezpropertyfeed' ) ) );
            die();
        }
    }

    public function save_import_settings()
    {
        if ( !isset($_POST['save_import_settings']) )
        {
            return;
        }

        if ( !isset($_POST['_wpnonce']) || ( isset($_POST['_wpnonce']) && !wp_verify_nonce( $_POST['_wpnonce'], 'save-import-settings' ) ) ) 
        {
            die( __( "Failed security check", 'houzezpropertyfeed' ) );
        }

        // ready to save
        $import_id = !empty($_POST['import_id']) ? (int)$_POST['import_id'] : time();

        $options = get_option( 'houzez_property_feed' , array() );
        if ( !is_array($options) ) { $options = array(); }
        if ( !is_array($options['imports']) ) { $options['imports'] = array(); }
        if ( !isset($options['imports'][$import_id]) ) { $options['imports'][$import_id] = array(); }
        if ( !is_array($options['imports'][$import_id]) ) { $options['imports'][$import_id] = array(); }

        $format = sanitize_text_field($_POST['format']);

        if ( isset($_POST['previous_format']) && $format != sanitize_text_field($_POST['previous_format']) )
        {
            // remove any options we stored about current status
            update_option( 'houzez_property_feed_property_' . $import_id, '', false );
            update_option( 'houzez_property_feed_property_image_media_ids_' . $import_id, '', false );
        }

        $running = ( isset($_POST['running']) && sanitize_text_field($_POST['running']) == 'yes' ) ? true : false;

        $agent_display_option = ( isset($_POST['agent_display_option']) ) ? sanitize_text_field($_POST['agent_display_option']) : 'author_info';

        $import_options = array(
            'running' => $running,
            'format' => $format,
            'frequency' => sanitize_text_field($_POST['frequency']), // might want to validate this is not a pro frequency
            'create_location_taxonomy_terms' => ( isset($_POST['create_location_taxonomy_terms']) && sanitize_text_field($_POST['create_location_taxonomy_terms']) == 'yes' ) ? true : false,
            'property_city_address_field' => ( isset($_POST['property_city_address_field']) ) ? sanitize_text_field($_POST['property_city_address_field']) : true,
            'property_area_address_field' => ( isset($_POST['property_area_address_field']) ) ? sanitize_text_field($_POST['property_area_address_field']) : true,
            'property_state_address_field' => ( isset($_POST['property_state_address_field']) ) ? sanitize_text_field($_POST['property_state_address_field']) : true,
            'agent_display_option' => $agent_display_option,
        );

        $rules = array();
        switch ( $agent_display_option )
        {
            case "author_info":
            case "agent_info":
            case "agency_info":
            {
                if ( 
                    isset($_POST[$agent_display_option . '_rules_field']) && 
                    is_array($_POST[$agent_display_option . '_rules_field']) && 
                    count($_POST[$agent_display_option . '_rules_field']) > 1 // more than 1 to ignore template
                )
                {
                    $rule_i = 0;
                    foreach ( $_POST[$agent_display_option . '_rules_field'] as $j => $field )
                    {
                        if ( $rule_i > 0 )
                        {
                            if ( 
                                !empty($field) && 
                                !empty($_POST[$agent_display_option . '_rules_equal'][$j]) && 
                                !empty($_POST[$agent_display_option . '_rules_result'][$j]) 
                            )
                            {
                                $rules[] = array(
                                    'field' => $field,
                                    'equal' => sanitize_text_field($_POST[$agent_display_option . '_rules_equal'][$j]),
                                    'result' => sanitize_text_field($_POST[$agent_display_option . '_rules_result'][$j]),
                                );
                            }
                        }

                        ++$rule_i;
                    }
                }
                break;
            }
        }
        $import_options['agent_display_option_rules'] = $rules;

        $rules = array();
        if ( 
            isset($_POST['field_mapping_rules']) && 
            is_array($_POST['field_mapping_rules']) && 
            count($_POST['field_mapping_rules']) > 1 // more than 1 to ignore template
        )
        {
            $rule_i = 0;
            foreach ( $_POST['field_mapping_rules'] as $j => $field )
            {
                if ($j !== '{rule_count}') // ignore template
                {
                    $result = stripslashes(sanitize_text_field($field['result']));
                    if ( $field['result_type'] == 'dropdown' )
                    {
                        $result = sanitize_text_field($field['result_option']);
                    }
                    $rules[$rule_i] = array(
                        'houzez_field' => sanitize_text_field($field['houzez_field']),
                        'result' => $result,
                        'delimited' => ( isset($field['delimited']) && $field['delimited'] == '1' ) ? true : false,
                        'delimited_character' => ( isset($field['delimited_character']) ) ? $field['delimited_character'] : '',
                        'rules' => array(),
                    );

                    unset($field['houzez_field']);
                    unset($field['result']);
                    unset($field['result_option']);
                    unset($field['delimited']);
                    unset($field['delimited_character']);
                    unset($field['result_type']);

                    foreach ( $field as $i => $rule_fields )
                    {
                        foreach ( $rule_fields as $k => $rule_field )
                        {
                            $rules[$rule_i]['rules'][$k][$i] = stripslashes(sanitize_text_field($rule_field));
                        }
                    }

                    ++$rule_i;
                }
            }
        }
        $import_options['field_mapping_rules'] = $rules;

        // Save core format fields (API Key, XML URL etc)
        $formats = get_houzez_property_feed_import_formats();
        if ( isset($formats[$format]) )
        {
            if ( isset($formats[$format]['fields']) && !empty($formats[$format]['fields']) )
            {
                foreach ( $formats[$format]['fields'] as $field )
                {   
                    if ( isset($field['id']) && substr($field['id'], 0, 9) == 'previous_' ) // don't save any fields storing previous data
                    {
                        continue;
                    }

                    if ( isset($field['type']) && $field['type'] != 'html' )
                    {
                        $field_value = '';
                        if ( isset($_POST[$format . '_' . $field['id']]) && !empty($_POST[$format . '_' . $field['id']]) )
                        {
                            $field_value = sanitize_text_field($_POST[$format . '_' . $field['id']]);
                        }
                        if ( $field['id'] == 'property_node_options' || $field['id'] == 'property_field_options' )
                        {
                            $field_value = stripslashes($field_value);
                        }
                        $import_options[$field['id']] = $field_value;
                    }
                }
            }
        }

        $import_mappings = array();

        if ( isset($_POST['taxonomy_mapping']) && is_array($_POST['taxonomy_mapping']) && !empty($_POST['taxonomy_mapping']) )
        {
            foreach ( $_POST['taxonomy_mapping'] as $taxonomy => $mappings )
            {
                $taxonomy = sanitize_text_field($taxonomy);

                $import_mappings[$taxonomy] = array();

                if ( is_array($mappings) && !empty($mappings) )
                {
                    foreach ( $mappings as $crm_value => $term_id )
                    {
                        if ( !empty((int)$term_id) )
                        {
                            $import_mappings[$taxonomy][$crm_value] = (int)$term_id;
                        }
                    }
                }

                if ( isset($_POST['custom_mapping'][$taxonomy]) )
                {
                    foreach ( $_POST['custom_mapping'][$taxonomy] as $key => $custom_mapping )
                    {
                        if ( trim($custom_mapping) != '' )
                        {
                            if ( isset($_POST['custom_mapping_value'][$taxonomy][$key]) && trim($_POST['custom_mapping_value'][$taxonomy][$key]) != '' )
                            {
                                $import_mappings[$taxonomy][$custom_mapping] = $_POST['custom_mapping_value'][$taxonomy][$key];
                            }
                        }
                    }
                }
            }
        }

        $import_options['mappings'] = $import_mappings;

        if ( isset($_POST['image_field_arrangement']) && in_array($_POST['image_field_arrangement'], array('', 'comma_delimited')) )
        {
            $import_options['image_field_arrangement'] = sanitize_text_field($_POST['image_field_arrangement']);
        }
        if ( isset($_POST['image_field']) )
        {
            $import_options['image_field'] = sanitize_text_field($_POST['image_field']);
        }
        if ( isset($_POST['image_field_delimiter']) )
        {
            $import_options['image_field_delimiter'] = sanitize_text_field($_POST['image_field_delimiter']);
        }
        if ( isset($_POST['image_fields']) )
        {
            $import_options['image_fields'] = sanitize_textarea_field($_POST['image_fields']);
        }

        if ( isset($_POST['floorplan_field_arrangement']) && in_array($_POST['floorplan_field_arrangement'], array('', 'comma_delimited')) )
        {
            $import_options['floorplan_field_arrangement'] = sanitize_text_field($_POST['floorplan_field_arrangement']);
        }
        if ( isset($_POST['floorplan_field']) )
        {
            $import_options['floorplan_field'] = sanitize_text_field($_POST['floorplan_field']);
        }
        if ( isset($_POST['floorplan_field_delimiter']) )
        {
            $import_options['floorplan_field_delimiter'] = sanitize_text_field($_POST['floorplan_field_delimiter']);
        }
        if ( isset($_POST['floorplan_fields']) )
        {
            $import_options['floorplan_fields'] = sanitize_textarea_field($_POST['floorplan_fields']);
        }

        if ( isset($_POST['document_field_arrangement']) && in_array($_POST['document_field_arrangement'], array('', 'comma_delimited')) )
        {
            $import_options['document_field_arrangement'] = sanitize_text_field($_POST['document_field_arrangement']);
        }
        if ( isset($_POST['document_field']) )
        {
            $import_options['document_field'] = sanitize_text_field($_POST['document_field']);
        }
        if ( isset($_POST['document_field_delimiter']) )
        {
            $import_options['document_field_delimiter'] = sanitize_text_field($_POST['document_field_delimiter']);
        }
        if ( isset($_POST['document_fields']) )
        {
            $import_options['document_fields'] = sanitize_textarea_field($_POST['document_fields']);
        }
        $import_options['media_download_clause'] = ( isset($_POST['media_download_clause']) ? sanitize_text_field($_POST['media_download_clause']) : 'url_change' );

        $export_enquiries_enabled = '';
        if ( isset($_POST['export_enquiries_enabled']) && sanitize_text_field($_POST['export_enquiries_enabled']) == 'yes' )
        {
            $export_enquiries_enabled = 'yes';
        }
        $import_options['export_enquiries_enabled'] = $export_enquiries_enabled;

        $options['imports'][$import_id] = $import_options;

        update_option( 'houzez_property_feed', $options );

        wp_redirect( admin_url( 'admin.php?page=houzez-property-feed-import&hpfsuccessmessage=' . __( 'Import details saved', 'houzezpropertyfeed' ) ) );
        die();
    }

    public function toggle_import_running_status()
    {
        if ( isset($_GET['action']) && in_array($_GET['action'], array("startimport", "pauseimport")) && isset($_GET['import_id']) )
        {
            $import_id = !empty($_GET['import_id']) ? (int)$_GET['import_id'] : '';

            if ( empty($import_id) )
            {
                wp_redirect( admin_url( 'admin.php?page=houzez-property-feed-import&hpferrormessage=' . __( 'No import passed', 'houzezpropertyfeed' ) ) );
                die();
            }

            $options = get_option( 'houzez_property_feed', array() );
            
            if ( !isset($options['imports'][$import_id]) )
            {
                wp_redirect( admin_url( 'admin.php?page=houzez-property-feed-import&hpferrormessage=' . __( 'Import not found', 'houzezpropertyfeed' ) ) );
                die();
            }

            switch ( sanitize_text_field($_GET['action']) )
            {
                case "startimport":
                {   
                    // Check one imports not already active if not using pro
                    if ( apply_filters( 'houzez_property_feed_pro_active', false ) !== true ) 
                    {
                        foreach ( $options['imports'] as $import )
                        {
                            if ( ( !isset($import['deleted']) || ( isset($import['deleted']) && $import['deleted'] !== true ) ) && isset($import['running']) && $import['running'] === true )
                            {
                                wp_redirect( admin_url( 'admin.php?page=houzez-property-feed-import&hpferrormessage=' . urlencode(__( 'Maximum number of running imports reached. Upgrade to PRO if wanting to benfit from multiple imports and more', 'houzezpropertyfeed' ) ) ) );
                                die();
                            }
                        }
                    }

                    $options['imports'][$import_id]['running'] = true;

                    update_option( 'houzez_property_feed', $options );

                    wp_redirect( admin_url( 'admin.php?page=houzez-property-feed-import&hpfsuccessmessage=' . __( 'Import started', 'houzezpropertyfeed' ) ) );
                    die();

                    break;

                }
                case "pauseimport":
                {
                    $options['imports'][$import_id]['running'] = false;

                    update_option( 'houzez_property_feed', $options );

                    wp_redirect( admin_url( 'admin.php?page=houzez-property-feed-import&hpfsuccessmessage=' . __( 'Import paused', 'houzezpropertyfeed' ) ) );
                    die();

                    break;

                }
            }
        }
    }

    public function delete_import()
    {
        if ( isset($_GET['action']) && $_GET['action'] == 'deleteimport' && isset($_GET['import_id']) )
        {
            $import_id = !empty($_GET['import_id']) ? (int)$_GET['import_id'] : '';

            if ( empty($import_id) )
            {
                wp_redirect( admin_url( 'admin.php?page=houzez-property-feed-import&hpferrormessage=' . __( 'No import passed', 'houzezpropertyfeed' ) ) );
                die();
            }

            $options = get_option( 'houzez_property_feed', array() );
            
            if ( !isset($options['imports'][$import_id]) )
            {
                wp_redirect( admin_url( 'admin.php?page=houzez-property-feed-import&hpferrormessage=' . __( 'Import not found', 'houzezpropertyfeed' ) ) );
                die();
            }

            $options['imports'][$import_id]['running'] = false;
            $options['imports'][$import_id]['deleted'] = true;

            update_option( 'houzez_property_feed', $options );

            wp_redirect( admin_url( 'admin.php?page=houzez-property-feed-import&hpfsuccessmessage=' . __( 'Import deleted successfully', 'houzezpropertyfeed' ) ) );
            die();
        }
    }

    public function perform_field_mapping( $post_id, $property, $import_id )
    {
        $import_settings = get_import_settings_from_id( $import_id );

        if ( $import_settings === false )
        {
            return false;
        }

        if ( !isset($import_settings['field_mapping_rules']) )
        {
            return false;
        }

        if ( empty($import_settings['field_mapping_rules']) )
        {
            return false;
        }

        $original_property = $property;

        if ( is_object($property) )
        {
            $property = SimpleXML2ArrayWithCDATASupport($property);
        }

        $import_settings['field_mapping_rules'] = convert_old_field_mapping_to_new( $import_settings['field_mapping_rules'] );

        $post_fields_to_update = array(
            'ID' => $post_id
        );

        $property_node = '';
        if ( isset($import_settings['property_node']) )
        {
            $property_node = $import_settings['property_node'];
            $explode_property_node = explode("/", $property_node);
            $property_node = $explode_property_node[count($explode_property_node)-1];
        }

        $taxonomies_with_multiple_values = array();

        $multiselect_meta = array();

        foreach ( $import_settings['field_mapping_rules'] as $and_rules )
        {
            // field
            // equal
            // houzez_field
            // result
            $rules_met = 0;
            foreach ( $and_rules['rules'] as $i => $rule )
            {
                if ( is_object($original_property) && $original_property instanceof SimpleXMLElement )
                {
                    // Using XPATH syntax
                    $xpath = ( ( !empty($property_node) ) ? '/' : '' ) . $property_node . $rule['field'];
                    $values_to_check = $original_property->xpath( $xpath );

                    if ( $values_to_check === FALSE || empty($values_to_check) )
                    {
                        continue;
                    }

                    $found = false;
                    foreach ( $values_to_check as $value_to_check )
                    {
                        if ( $rule['equal'] == '*' )
                        {
                            $found = true;
                        }
                        elseif (
                            ( ( !isset($rule['operator']) || ( isset($rule['operator']) && $rule['operator'] == '=' ) ) && $value_to_check == $rule['equal'] )
                            ||
                            ( ( isset($rule['operator']) && $rule['operator'] == '!=' ) && $value_to_check != $rule['equal'] )
                        )
                        {
                            $found = true;
                        }
                        
                    }
                    if ( $found )
                    {
                        ++$rules_met;
                    }
                }
                else
                {
                    // loop through all fields in data and see if $rule['field'] is found
                    if ( is_array($property) )
                    {
                        $value_to_check = check_array_for_matching_key( $property, $rule['field'] );

                        if ( $value_to_check === false )
                        {
                            continue;
                        }

                        $found = false;
                    
                        if ( $rule['equal'] == '*' )
                        {
                            $found = true;
                        }
                        elseif (
                            ( ( !isset($rule['operator']) || ( isset($rule['operator']) && $rule['operator'] == '=' ) ) && $value_to_check == $rule['equal'] )
                            ||
                            ( ( isset($rule['operator']) && $rule['operator'] == '!=' ) && $value_to_check != $rule['equal'] )
                        )
                        {
                            $found = true;
                        }

                        if ( $found )
                        {
                            ++$rules_met;
                        }
                    }
                }
            }

            if ( $rules_met == count($and_rules['rules']) )
            {
                $result = $and_rules['result'];

                preg_match_all('/{[^}]*}/', $and_rules['result'], $matches);
                if ( $matches !== FALSE && isset($matches[0]) && is_array($matches[0]) && !empty($matches[0]) )
                {
                    foreach ( $matches[0] as $match )
                    {
                        $field_name = str_replace(array("{", "}"), "", $match);
                        $value_to_check = '';

                        if ( is_object($original_property) && $original_property instanceof SimpleXMLElement )
                        {
                            // Using XPATH syntax
                            $values_to_check = $original_property->xpath(  ( ( !empty($property_node) ) ? '/' : '' ) . $property_node . $field_name );
                            if ( $values_to_check !== false && is_array($values_to_check) && !empty($values_to_check) )
                            {
                                $value_to_check = (string)$values_to_check[0];
                            }
                        }
                        else
                        {
                            $value_to_check = check_array_for_matching_key( $property, $field_name );

                            if ( $value_to_check === false )
                            {
                                $value_to_check = '';
                            }
                        }

                        $result = str_replace($match, $value_to_check, $result);
                    }
                }

                $houzez_fields = get_houzez_fields_for_field_mapping();

                // we found a matching field with the required value
                if ( isset($houzez_fields[$and_rules['houzez_field']]) && $houzez_fields[$and_rules['houzez_field']]['type'] == 'post_field' )
                {
                    $post_fields_to_update[$and_rules['houzez_field']] = $result;
                }
                elseif ( isset($houzez_fields[$and_rules['houzez_field']]) && $houzez_fields[$and_rules['houzez_field']]['type'] == 'taxonomy' )
                {
                    // only do for taxonomies that have a single value, else we'll do multiple ones later
                    preg_match_all('/\[[^\]]*\]/', $and_rules['houzez_field'], $matches);
                    if ( $matches !== FALSE && isset($matches[0]) && is_array($matches[0]) && !empty($matches[0]) )
                    {
                        foreach ( $matches[0] as $match )
                        {
                            $taxonomy = str_replace($match, '', $and_rules['houzez_field']);

                            if ( !isset($taxonomies_with_multiple_values[$taxonomy]) ) { $taxonomies_with_multiple_values[$taxonomy] = array(); }

                            // check term exists and get termID as wp_set_object_terms() requires the ID
                            $term_id = '';
                            if ( $taxonomy == 'property_feature' )
                            {
                                // create if not exists
                                $term = term_exists( $result, $taxonomy );
                                if ( $term !== 0 && $term !== null && isset($term['term_id']) )
                                {
                                    $term_id = (int)$term['term_id'];
                                }
                                else
                                {
                                    $term = wp_insert_term( $result, $taxonomy );
                                    if ( is_array($term) && isset($term['term_id']) )
                                    {
                                        $term_id = (int)$term['term_id'];
                                    }
                                }
                            }
                            else
                            {
                                $term = term_exists( $result, $taxonomy );
                                if ( $term !== 0 && $term !== null && isset($term['term_id']) )
                                {
                                    $term_id = (int)$term['term_id'];
                                }
                            }

                            if ( !empty($term_id) )
                            {
                                $taxonomies_with_multiple_values[$taxonomy][] = $term_id;
                            }
                        }
                    }
                    else
                    {
                        if ( isset($and_rules['delimited']) && $and_rules['delimited'] === true && isset($and_rules['delimited_character']) && !empty($and_rules['delimited_character']) )
                        {
                            $taxonomy = $and_rules['houzez_field'];

                            if ( !isset($taxonomies_with_multiple_values[$taxonomy]) ) { $taxonomies_with_multiple_values[$taxonomy] = array(); }

                            $results = explode($and_rules['delimited_character'], $result);
                            $results = array_map('trim', $results);
                            $results = array_filter($results);

                            foreach ( $results as $result )
                            {
                                // create if not exists
                                $term = term_exists( $result, $taxonomy );
                                if ( $term !== 0 && $term !== null && isset($term['term_id']) )
                                {
                                    $term_id = (int)$term['term_id'];
                                }
                                else
                                {
                                    $term = wp_insert_term( $result, $taxonomy );
                                    if ( is_array($term) && isset($term['term_id']) )
                                    {
                                        $term_id = (int)$term['term_id'];
                                    }
                                }

                                if ( !empty($term_id) )
                                {
                                    $taxonomies_with_multiple_values[$taxonomy][] = $term_id;
                                }
                            }
                        }
                        else
                        {
                            wp_set_object_terms( $post_id, $result, $and_rules['houzez_field'] );
                        }
                    }
                }
                else
                {
                    if ( 
                        $result != '' &&
                        (
                            $and_rules['houzez_field'] == 'houzez_geolocation_lat' ||
                            $and_rules['houzez_field'] == 'houzez_geolocation_long' ||
                            $and_rules['houzez_field'] == 'fave_property_location'
                        )
                    )
                    {
                        // ensure non-numeric characters stripped from any lat/lng based fields
                        $explode_result = explode(",", $result);
                        $new_result = array();
                        foreach ( $explode_result as $result_item )
                        {
                            $result_item = trim($result_item);
                            $result_item = preg_replace("/[^0-9.-]/", "", $result_item);

                            $new_result[] = $result_item;
                        }
                        $result = implode(",", $new_result);
                    }
                    elseif ( $and_rules['houzez_field'] == 'fave_agents' && $result == 'auto' )
                    {
                        foreach ( $and_rules['rules'] as $i => $rule )
                        {
                            if ( is_object($original_property) && $original_property instanceof SimpleXMLElement )
                            {
                                // Using XPATH syntax
                                $values_to_check = $original_property->xpath( ( ( !empty($property_node) ) ? '/' : '' ) . $property_node . $rule['field'] );
                                if ( $values_to_check === FALSE || empty($values_to_check) )
                                {
                                    continue;
                                }

                                foreach ( $values_to_check as $value_to_check )
                                {
                                    // see if an agent exists with this name
                                    $args = array(
                                        'post_type' => 'houzez_agent',
                                        'posts_per_page' => 1,
                                        's' => (string)$value_to_check
                                    );

                                    $agent_query = new WP_Query( $args );

                                    if ( $agent_query->have_posts() )
                                    {
                                        while ( $agent_query->have_posts() )
                                        {
                                            $agent_query->the_post();

                                            $result = get_the_ID();
                                        }
                                    }
                                    wp_reset_postdata();
                                }
                            }
                            else
                            {
                                // loop through all fields in data and see if $rule['field'] is found
                                if ( is_array($property) )
                                {
                                    $value_to_check = check_array_for_matching_key( $property, $rule['field'] );

                                    if ( $value_to_check === false )
                                    {
                                        continue;
                                    }

                                    // see if an agent exists with this name
                                    $args = array(
                                        'post_type' => 'houzez_agent',
                                        'posts_per_page' => 1,
                                        's' => (string)$value_to_check
                                    );

                                    $agent_query = new WP_Query( $args );

                                    if ( $agent_query->have_posts() )
                                    {
                                        while ( $agent_query->have_posts() )
                                        {
                                            $agent_query->the_post();

                                            $result = get_the_ID();
                                        }
                                    }
                                    wp_reset_postdata();
                                }
                            }
                        }
                    }
                    elseif ( $and_rules['houzez_field'] == 'fave_property_agency' && $result == 'auto' )
                    {
                        foreach ( $and_rules['rules'] as $i => $rule )
                        {
                            if ( is_object($original_property) && $original_property instanceof SimpleXMLElement )
                            {
                                // Using XPATH syntax
                                $values_to_check = $original_property->xpath( ( ( !empty($property_node) ) ? '/' : '' ) . $property_node . $rule['field'] );
                                if ( $values_to_check === FALSE || empty($values_to_check) )
                                {
                                    continue;
                                }

                                foreach ( $values_to_check as $value_to_check )
                                {
                                    // see if an agent exists with this name
                                    $args = array(
                                        'post_type' => 'houzez_agency',
                                        'posts_per_page' => 1,
                                        's' => (string)$value_to_check
                                    );

                                    $agent_query = new WP_Query( $args );

                                    if ( $agent_query->have_posts() )
                                    {
                                        while ( $agent_query->have_posts() )
                                        {
                                            $agent_query->the_post();

                                            $result = get_the_ID();
                                        }
                                    }
                                    wp_reset_postdata();
                                }
                            }
                            else
                            {
                                // loop through all fields in data and see if $rule['field'] is found
                                if ( is_array($property) )
                                {
                                    $value_to_check = check_array_for_matching_key( $property, $rule['field'] );

                                    if ( $value_to_check === false )
                                    {
                                        continue;
                                    }

                                    // see if an agent exists with this name
                                    $args = array(
                                        'post_type' => 'houzez_agent',
                                        'posts_per_page' => 1,
                                        's' => (string)$value_to_check
                                    );

                                    $agent_query = new WP_Query( $args );

                                    if ( $agent_query->have_posts() )
                                    {
                                        while ( $agent_query->have_posts() )
                                        {
                                            $agent_query->the_post();

                                            $result = get_the_ID();
                                        }
                                    }
                                    wp_reset_postdata();
                                }
                            }
                        }
                    }

                    if ( isset($houzez_fields[$and_rules['houzez_field']]) && isset($houzez_fields[$and_rules['houzez_field']]['field_type']) && $houzez_fields[$and_rules['houzez_field']]['field_type'] == 'multiselect' )
                    {
                        if ( !isset($multiselect_meta[$and_rules['houzez_field']]) ) { $multiselect_meta[$and_rules['houzez_field']] = array(); }
                        $multiselect_meta[$and_rules['houzez_field']][] = $result;
                    }
                    else
                    {
                        update_post_meta( $post_id, $and_rules['houzez_field'], $result );
                    }
                }
            }
        }

        if ( !empty($multiselect_meta) )
        {
            foreach ( $multiselect_meta as $field_name => $values )
            {
                delete_post_meta( $post_id, $field_name );
                foreach ( $values as $value )
                {
                    add_post_meta( $post_id, $field_name, $value );
                } 
            }
        }

        if ( !empty($taxonomies_with_multiple_values) )
        {
            foreach ( $taxonomies_with_multiple_values as $taxonomy => $taxonomy_values )
            {
                if ( !empty($taxonomy_values) )
                {
                    wp_set_object_terms( $post_id, $taxonomy_values, $taxonomy );
                }
            }
        }

        // not doing for XML format as should be done inside XML import class
        if ( $import_settings['format'] != 'xml' )
        {
            if ( !empty($post_fields_to_update) )
            {
                wp_update_post($post_fields_to_update, TRUE);
            }
        }
    }

    public function set_generic_houzez_property_data( $post_id, $property, $import_id )
    {
        add_post_meta( $post_id, 'fave_loggedintoview', '0', TRUE );
        add_post_meta( $post_id, 'fave_single_content_area', 'global', TRUE );
        add_post_meta( $post_id, 'fave_single_top_area', 'global', TRUE );
        add_post_meta( $post_id, 'fave_prop_homeslider', 'no', TRUE );
        add_post_meta( $post_id, 'fave_featured', '0', TRUE );
    }

    public function get_xml_mapped_field_value( $value, $property, $field_name, $import_id )
    {
        $import_settings = get_import_settings_from_id( $import_id );

        if ( $import_settings === false )
        {
            return $value;
        }

        if ( !isset($import_settings['field_mapping_rules']) )
        {
            return $value;
        }

        if ( empty($import_settings['field_mapping_rules']) )
        {
            return $value;
        }

        $property_node = '';
        if ( isset($import_settings['property_node']) )
        {
            $property_node = $import_settings['property_node'];
            $explode_property_node = explode("/", $property_node);
            $property_node = $explode_property_node[count($explode_property_node)-1];
        }

        foreach ( $import_settings['field_mapping_rules'] as $and_rules )
        {
            if ( $and_rules['houzez_field'] == $field_name )
            {
                // This is the field we're after. Check rules are met
                $rules_met = 0;
                foreach ( $and_rules['rules'] as $i => $rule )
                {
                    if ( is_object($property) && $property instanceof SimpleXMLElement )
                    {
                        // Using XPATH syntax
                        $values_to_check = $property->xpath('/' . $property_node . $rule['field']);
                        if ( $values_to_check === FALSE || empty($values_to_check) )
                        {
                            continue;
                        }

                        $found = false;
                        foreach ( $values_to_check as $value_to_check )
                        {
                            if ( $rule['equal'] == '*' )
                            {
                                $found = true;
                            }
                            elseif (
                                ( ( !isset($rule['operator']) || ( isset($rule['operator']) && $rule['operator'] == '=' ) ) && $value_to_check == $rule['equal'] )
                                ||
                                ( ( isset($rule['operator']) && $rule['operator'] == '!=' ) && $value_to_check != $rule['equal'] )
                            )
                            {
                                $found = true;
                            }
                            
                        }

                        if ( $found )
                        {
                            ++$rules_met;
                        }
                    }
                }

                if ( $rules_met == count($and_rules['rules']) )
                {
                    $result = $and_rules['result'];

                    preg_match_all('/{[^}]*}/', $and_rules['result'], $matches);
                    if ( $matches !== FALSE && isset($matches[0]) && is_array($matches[0]) && !empty($matches[0]) )
                    {
                        foreach ( $matches[0] as $match )
                        {
                            $field_name = str_replace(array("{", "}"), "", $match);
                            $value_to_check = '';

                            if ( substr($field_name, 0, 1) == '/' )
                            {
                                // Using XPATH syntax
                                $values_to_check = $property->xpath('/' . $property_node . $field_name);
                                if ( $values_to_check !== false && is_array($values_to_check) && !empty($values_to_check) )
                                {
                                    $value_to_check = (string)$values_to_check[0];
                                }
                            }

                            $result = str_replace($match, $value_to_check, $result);
                        }
                    }

                    return $result;
                }
            }
        }

        return $value;
    }

    public function get_csv_mapped_field_value( $value, $property, $field_name, $import_id )
    {
        $import_settings = get_import_settings_from_id( $import_id );

        if ( $import_settings === false )
        {
            return $value;
        }

        if ( !isset($import_settings['field_mapping_rules']) )
        {
            return $value;
        }

        if ( empty($import_settings['field_mapping_rules']) )
        {
            return $value;
        }

        foreach ( $import_settings['field_mapping_rules'] as $and_rules )
        {
            if ( $and_rules['houzez_field'] == $field_name )
            {
                // This is the field we're after. Check rules are met
                $rules_met = 0;
                foreach ( $and_rules['rules'] as $i => $rule )
                {
                    $value_to_check = '';
                    if ( isset($property[$rule['field']]) )
                    {
                        $value_to_check = $property[$rule['field']];
                    }

                    $found = false;
                    
                    if ( $rule['equal'] == '*' )
                    {
                        $found = true;
                    }
                    elseif (
                        ( ( !isset($rule['operator']) || ( isset($rule['operator']) && $rule['operator'] == '=' ) ) && $value_to_check == $rule['equal'] )
                        ||
                        ( ( isset($rule['operator']) && $rule['operator'] == '!=' ) && $value_to_check != $rule['equal'] )
                    )
                    {
                        $found = true;
                    }

                    if ( $found )
                    {
                        ++$rules_met;
                    }
                }

                if ( $rules_met == count($and_rules['rules']) )
                {
                    $result = $and_rules['result'];

                    preg_match_all('/{[^}]*}/', $and_rules['result'], $matches);
                    if ( $matches !== FALSE && isset($matches[0]) && is_array($matches[0]) && !empty($matches[0]) )
                    {
                        foreach ( $matches[0] as $match )
                        {
                            $field_name = str_replace(array("{", "}"), "", $match);
                            $value_to_check = '';

                            if ( isset($property[$field_name]) )
                            {
                                $value_to_check = $property[$field_name];
                            }

                            $result = str_replace($match, $value_to_check, $result);
                        }
                    }

                    return $result;
                }
            }
        }

        return $value;
    }

    public function import_data_meta_box()
    {
        $screen = get_current_screen();
        if ( isset($screen->post_type) && $screen->post_type == 'property' )
        {
            if ( isset($screen->action) && $screen->action == 'add' )
            {

            }
            else
            {
                add_meta_box( 'houzezpropertyfeed-import-data', __( 'Import Data', 'houzezpropertyfeed' ), array( $this, 'output_import_data_meta_box'), 'property', 'advanced', 'low' );
            }
        }
    }

    public function output_import_data_meta_box( $post )
    {
        if ( isset($post->ID) )
        {
            if ( get_post_meta( $post->ID, '_property_import_data', TRUE ) != '' )
            {
                echo '<textarea readonly rows="20" style="width:100%;">' . get_post_meta( $post->ID, '_property_import_data', TRUE )  . '</textarea>';
            }
            else
            {
                echo __( 'No import data to display', 'houzezpropertyfeed' );
            }
        }
    }

    public function set_location_taxonomy_parents( $import_id )
    {
        $houzez_tax_settings = get_option('houzez_tax_settings', array() );

        $taxonomies = array(
            array(
                'taxonomy' => 'property_area',
                'parent_taxonomy' => 'property_city',
            ),
            array(
                'taxonomy' => 'property_city',
                'parent_taxonomy' => 'property_state',
            ),
            array(
                'taxonomy' => 'property_state',
                'parent_taxonomy' => 'property_country',
            )
        );

        foreach ( $taxonomies as $taxonomy_data )
        {
            $taxonomy = $taxonomy_data['taxonomy'];
            $parent_taxonomy = $taxonomy_data['parent_taxonomy'];

            if ( !isset($houzez_tax_settings[$taxonomy]) || ( isset($houzez_tax_settings[$taxonomy]) && $houzez_tax_settings[$taxonomy] != 'disabled' ) )
            {
                if ( !isset($houzez_tax_settings[$parent_taxonomy]) || ( isset($houzez_tax_settings[$parent_taxonomy]) && $houzez_tax_settings[$parent_taxonomy] != 'disabled' ) )
                {
                    // get current terms
                    $terms = get_terms( array( 
                        'taxonomy' => $taxonomy,
                        'hide_empty' => true
                    ) );

                    if ( !empty($terms) )
                    {
                        foreach ( $terms as $term ) 
                        {
                            $term_id = $term->term_id;

                            // ensure this term doesn't already have a parent set
                            $current_parent = get_option( '_houzez_' . $taxonomy . '_' . $term_id, '' );

                            if ( !empty($current_parent) && isset($current_parent[$parent_taxonomy]) && !empty($current_parent[$parent_taxonomy]) )
                            {
                                // already has a parent set
                                continue;
                            }

                            $parent_term_ids_found = array();

                            // get all properties with this term set
                            $args = array(
                                'post_type' => 'property',
                                'fields' => 'ids',
                                'tax_query' => array(
                                    array(
                                        'taxonomy' => $taxonomy,
                                        'field' => 'term_id',
                                        'terms' => $term_id
                                    )
                                )
                            );
                            $property_query = new WP_Query( $args );

                            if ( $property_query->have_posts() )
                            {
                                while ( $property_query->have_posts() )
                                {
                                    $property_query->the_post();

                                    // ensure this property only has one taxonomy and parent taxonomy set
                                    $term_list = wp_get_post_terms( get_the_ID(), $taxonomy, array( 'fields' => 'ids' ) );
                                    if ( !empty($term_list) && count($term_list) == 1 )
                                    {
                                        $parent_term_list = wp_get_post_terms( get_the_ID(), $parent_taxonomy, array( 'fields' => 'all' ) );
                                        if ( !empty($parent_term_list) && count($parent_term_list) == 1 && isset($parent_term_list[0]) && isset($parent_term_list[0]->slug) )
                                        {
                                            // we have one taxonomy and one parent taxonomy. Good to continue
                                            if ( !in_array($parent_term_list[0]->slug, $parent_term_ids_found) ) { $parent_term_ids_found[] = $parent_term_list[0]->slug; }
                                        }
                                    }
                                }
                            }
                            wp_reset_postdata();

                            $parent_term_ids_found = array_unique($parent_term_ids_found);
                            $parent_term_ids_found = array_filter($parent_term_ids_found);
                            $parent_term_ids_found = array_values($parent_term_ids_found);

                            if ( count($parent_term_ids_found) == 1 )
                            {
                                // Yes! we found one parent term for this term across all properties
                                update_option( '_houzez_' . $taxonomy . '_' . $term_id, array(str_replace("property_", "parent_", $parent_taxonomy) => $parent_term_ids_found[0]) );
                            }
                        }
                    }
                }
            }
        }
    }
}

new Houzez_Property_Feed_Import();