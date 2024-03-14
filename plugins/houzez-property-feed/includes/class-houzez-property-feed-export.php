<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Houzez Property Feed Export Functions
 */
class Houzez_Property_Feed_Export {

	public function __construct() {

        add_action( 'admin_init', array( $this, 'check_not_multiple_if_no_pro') );

        add_action( 'admin_init', array( $this, 'save_export_settings') );

        add_action( 'admin_init', array( $this, 'toggle_export_running_status') );

        add_action( 'admin_init', array( $this, 'delete_export') );

        add_filter( 'houzez_property_feed_export_property_data', array( $this, 'perform_field_mapping' ), 1, 3 );
	}

    public function check_not_multiple_if_no_pro()
    {
        if ( isset($_GET['action']) && $_GET['action'] == 'addexport' )
        {
            if ( apply_filters( 'houzez_property_feed_pro_active', false ) !== true ) 
            {
                $options = get_option( 'houzez_property_feed', array() );
                $exports = ( isset($options['exports']) && is_array($options['exports']) && !empty($options['exports']) ) ? $options['exports'] : array();

                foreach ( $exports as $key => $export )
                {
                    if ( $exports[$key]['deleted'] && $exports[$key]['deleted'] === true )
                    {
                        unset( $exports[$key] );
                    }
                }

                if ( count($exports) >=1 )
                {
                    wp_redirect( admin_url( 'admin.php?page=houzez-property-feed-export&hpferrormessage=' . urlencode(__( 'Maximum number of exports reached. Upgrade to PRO if wanting to benfit from multiple exports and more', 'houzezpropertyfeed' ) ) ) );
                    die();
                }
            }
        }
    }

    public function save_export_settings()
    {
        if ( !isset($_POST['save_export_settings']) )
        {
            return;
        }

        if ( !isset($_POST['_wpnonce']) || ( isset($_POST['_wpnonce']) && !wp_verify_nonce( $_POST['_wpnonce'], 'save-export-settings' ) ) ) 
        {
            die( __( "Failed security check", 'houzezpropertyfeed' ) );
        }

        // ready to save
        $export_id = !empty($_POST['export_id']) ? (int)$_POST['export_id'] : time();

        $options = get_option( 'houzez_property_feed' , array() );
        if ( !is_array($options) ) { $options = array(); }
        if ( !is_array($options['exports']) ) { $options['exports'] = array(); }
        if ( !is_array($options['exports'][$export_id]) ) { $options['exports'][$export_id] = array(); }

        $format = sanitize_text_field($_POST['format']);

        $running = ( isset($_POST['running']) && sanitize_text_field($_POST['running']) == 'yes' ) ? true : false;

        $export_options = array(
            'running' => $running,
            'format' => $format,
            'name' => sanitize_text_field($_POST['export_name']),
            'frequency' => sanitize_text_field($_POST['frequency']), // might want to validate this is not a pro frequency
        );

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
                    $rules[$rule_i] = array(
                        'field' => sanitize_text_field($field['field']),
                        'result' => sanitize_text_field($field['result']),
                        'rules' => array(),
                    );

                    unset($field['field']);
                    unset($field['result']);

                    foreach ( $field as $i => $rule_fields )
                    {   
                        foreach ( $rule_fields as $k => $rule_field )
                        {
                            $rules[$rule_i]['rules'][$k][$i] = sanitize_text_field($rule_field);
                        }
                    }

                    ++$rule_i;
                }
            }
        }
        $export_options['field_mapping_rules'] = $rules;

        // Save core format fields (FTP Details etc)
        $formats = get_houzez_property_feed_export_formats();
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

                    if ( isset($field['type']) && $field['type'] == 'file' )
                    {
                        $uploaded_file_name = isset($options['exports'][$export_id][$field['id']]) ? $options['exports'][$export_id][$field['id']] : '';

                        if ( !isset($_FILES[$format . '_' . $field['id']]) || $_FILES[$format . '_' . $field['id']]['size'] == 0 )
                        {
                            // No file uploaded
                        }
                        else
                        {
                            $error = '';
                            try 
                            {
                                // Check $_FILES['upfile']['error'] value.
                                switch ($_FILES[$format . '_' . $field['id']]['error']) {
                                    case UPLOAD_ERR_OK:
                                        break;
                                    case UPLOAD_ERR_NO_FILE:
                                        throw new RuntimeException('No file sent.');
                                    case UPLOAD_ERR_INI_SIZE:
                                    case UPLOAD_ERR_FORM_SIZE:
                                        $error = __( 'File exceeded filesize limit.', 'propertyhive' );
                                    default:
                                        $error = __( 'Unknown error when uploading file.', 'propertyhive' );
                                }

                                if ($error == '')
                                {  
                                    // You should also check filesize here. 
                                    if ($_FILES[$format . '_' . $field['id']]['size'] > 1000000) {
                                        $error = __( 'Exceeded filesize limit.', 'propertyhive' );
                                    }

                                    if ($error == '')
                                    {  
                                        $ext = pathinfo($_FILES[$format . '_' . $field['id']]['name'], PATHINFO_EXTENSION);
                                        // Check if the extension is active on the server
                                        /*if (class_exists('finfo'))
                                        {
                                            // DO NOT TRUST $_FILES['upfile']['mime'] VALUE !!
                                            // Check MIME Type by yourself.
                                            $finfo = new finfo(FILEINFO_MIME_TYPE);
                                            if (false === $ext = array_search(
                                                $finfo->file($_FILES[$format . '_' . $field['id']]['tmp_name']),
                                                array(
                                                    'pem' => 'application/octet-stream',
                                                    'pem' => 'text/plain'
                                                ),
                                                true
                                            )) {
                                                $error = __( 'Certificate file must be of type .pem', 'propertyhive' );
                                            }
                                        }*/

                                        if ($error == '')
                                        { 
                                            $uploads_dir = wp_upload_dir();
                                            $uploads_dir = $uploads_dir['basedir'] . '/houzez_property_feed_export/';

                                            $uploaded_file_name = sha1_file($_FILES[$format . '_' . $field['id']]['tmp_name']) . '.' . $ext;

                                            // You should name it uniquely.
                                            // DO NOT USE $_FILES['upfile']['name'] WITHOUT ANY VALIDATION !!
                                            // On this example, obtain safe unique name from its binary data.
                                            if (!move_uploaded_file(
                                                $_FILES[$format . '_' . $field['id']]['tmp_name'],
                                                sprintf(
                                                    $uploads_dir . '%s',
                                                    $uploaded_file_name
                                                )
                                            )) {
                                                $error = __( 'Failed to move uploaded file.', 'propertyhive' );
                                                $uploaded_file_name = '';
                                            }
                                        }

                                    }

                                }

                            } catch (RuntimeException $e) 
                            {
                                $error = $e->getMessage();
                            }
                        }

                        $export_options[$field['id']] = $uploaded_file_name;
                    }
                    elseif ( isset($field['type']) && $field['type'] != 'html' )
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
                        $export_options[$field['id']] = $field_value;

                        // Clear any old SHA1s if the API urls have changed
                        if ( 
                            $field['id'] == 'send_property_url' && 
                            isset($_POST[$format . '_previous_' . $field['id']]) && 
                            !empty(sanitize_text_field($_POST[$format . '_previous_' . $field['id']])) &&
                            sanitize_text_field($_POST[$format . '_' . $field['id']]) != sanitize_text_field($_POST[$format . '_previous_' . $field['id']])
                        )
                        {
                            // we got a different URL. Clear the SHA1s
                            delete_post_meta_by_key( '_realtime_sha1_' . $export_id );
                            delete_post_meta_by_key( '_zoopla_sha1_' . $export_id );
                        }
                    }
                }
            }
        }

        $export_mappings = array();

        if ( isset($_POST['taxonomy_mapping']) && is_array($_POST['taxonomy_mapping']) && !empty($_POST['taxonomy_mapping']) )
        {
            foreach ( $_POST['taxonomy_mapping'] as $taxonomy => $mappings )
            {
                $taxonomy = sanitize_text_field($taxonomy);

                $export_mappings[$taxonomy] = array();

                if ( is_array($mappings) && !empty($mappings) )
                {
                    foreach ( $mappings as $term_id => $crm_id )
                    {
                        if ( $crm_id != '' )
                        {
                            $export_mappings[$taxonomy][$term_id] = $crm_id;
                        }
                    }
                }
            }
        }

        $export_options['mappings'] = $export_mappings;

        $options['exports'][$export_id] = $export_options;

        update_option( 'houzez_property_feed', $options );

        wp_redirect( admin_url( 'admin.php?page=houzez-property-feed-export&hpfsuccessmessage=' . __( 'Export details saved', 'houzezpropertyfeed' ) ) );
        die();
    }

    public function toggle_export_running_status()
    {
        if ( isset($_GET['action']) && in_array($_GET['action'], array("startexport", "pauseexport")) && isset($_GET['export_id']) )
        {
            $export_id = !empty($_GET['export_id']) ? (int)$_GET['export_id'] : '';

            if ( empty($export_id) )
            {
                wp_redirect( admin_url( 'admin.php?page=houzez-property-feed-export&hpferrormessage=' . __( 'No export passed', 'houzezpropertyfeed' ) ) );
                die();
            }

            $options = get_option( 'houzez_property_feed', array() );
            
            if ( !isset($options['exports'][$export_id]) )
            {
                wp_redirect( admin_url( 'admin.php?page=houzez-property-feed-export&hpferrormessage=' . __( 'Export not found', 'houzezpropertyfeed' ) ) );
                die();
            }

            switch ( sanitize_text_field($_GET['action']) )
            {
                case "startexport":
                {   
                    // Check one exports not already active if not using pro
                    if ( apply_filters( 'houzez_property_feed_pro_active', false ) !== true ) 
                    {
                        foreach ( $options['exports'] as $export )
                        {
                            if ( ( !isset($export['deleted']) || ( isset($export['deleted']) && $export['deleted'] !== true ) ) && isset($export['running']) && $export['running'] === true )
                            {
                                wp_redirect( admin_url( 'admin.php?page=houzez-property-feed-export&hpferrormessage=' . urlencode(__( 'Maximum number of running exports reached. Upgrade to PRO if wanting to benfit from multiple exports and more', 'houzezpropertyfeed' ) ) ) );
                                die();
                            }
                        }
                    }

                    $options['exports'][$export_id]['running'] = true;

                    update_option( 'houzez_property_feed', $options );

                    wp_redirect( admin_url( 'admin.php?page=houzez-property-feed-export&hpfsuccessmessage=' . __( 'Export started', 'houzezpropertyfeed' ) ) );
                    die();

                    break;

                }
                case "pauseexport":
                {
                    $options['exports'][$export_id]['running'] = false;

                    update_option( 'houzez_property_feed', $options );

                    wp_redirect( admin_url( 'admin.php?page=houzez-property-feed-export&hpfsuccessmessage=' . __( 'Export paused', 'houzezpropertyfeed' ) ) );
                    die();

                    break;

                }
            }
        }
    }

    public function delete_export()
    {
        if ( isset($_GET['action']) && $_GET['action'] == 'deleteexport' && isset($_GET['export_id']) )
        {
            $export_id = !empty($_GET['export_id']) ? (int)$_GET['export_id'] : '';

            if ( empty($export_id) )
            {
                wp_redirect( admin_url( 'admin.php?page=houzez-property-feed-export&hpferrormessage=' . __( 'No export passed', 'houzezpropertyfeed' ) ) );
                die();
            }

            $options = get_option( 'houzez_property_feed', array() );
            
            if ( !isset($options['exports'][$export_id]) )
            {
                wp_redirect( admin_url( 'admin.php?page=houzez-property-feed-export&hpferrormessage=' . __( 'Export not found', 'houzezpropertyfeed' ) ) );
                die();
            }

            $options['exports'][$export_id]['running'] = false;
            $options['exports'][$export_id]['deleted'] = true;

            update_option( 'houzez_property_feed', $options );

            wp_redirect( admin_url( 'admin.php?page=houzez-property-feed-export&hpfsuccessmessage=' . __( 'Export deleted successfully', 'houzezpropertyfeed' ) ) );
            die();
        }
    }

    public function perform_field_mapping( $property, $post_id, $export_id )
    {
        $export_settings = get_export_settings_from_id( $export_id );

        if ( $export_settings === false )
        {
            return $property;
        }

        if ( !isset($export_settings['field_mapping_rules']) )
        {
            return $property;
        }

        if ( empty($export_settings['field_mapping_rules']) )
        {
            return $property;
        }

        $houzez_fields = get_houzez_fields_for_field_mapping();

        foreach ( $export_settings['field_mapping_rules'] as $and_rules )
        {
            // houzez_field
            // equal
            // field
            // result
            $rules_met = 0;
            foreach ( $and_rules['rules'] as $i => $rule )
            {
                if ( $rule['equal'] == '*' || get_post_meta( $post_id, $rule['houzez_field'], TRUE ) == $rule['equal'] )
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

                        $value_to_check = get_post_meta( $post_id, $field_name, TRUE );
                        if ( empty($value_to_check) )
                        {
                            foreach ( $houzez_fields as $houzez_field_key => $houzez_field )
                            {
                                if ( isset($houzez_field['type']) && $houzez_field['type'] == 'meta' )
                                {
                                    if ( isset($houzez_field['label']) && $houzez_field['label'] == $field_name )
                                    {
                                        $value_to_check = get_post_meta( $post_id, $houzez_field_key, TRUE );
                                    }
                                }
                                if ( isset($houzez_field['type']) && $houzez_field['type'] == 'post_field' )
                                {
                                    if ( isset($houzez_field['label']) && $houzez_field['label'] == $field_name )
                                    {
                                        $temp_post = get_post($post_id, ARRAY_A);
                                        if ( isset($temp_post[$houzez_field_key]) )
                                        {
                                            $value_to_check = $temp_post[$houzez_field_key];
                                        }
                                    }
                                }
                            }
                        }
                        $result = str_replace($match, $value_to_check, $result);
                    }
                }

                if ( is_object($property) )
                {
                    // must be SimpleXML like Kyero
                    $property_nodes_to_update = $property->xpath($and_rules['field']);
                    if ( $property_nodes_to_update !== FALSE && !empty($property_nodes_to_update) )
                    {
                        if ( isset($property_nodes_to_update[0][0]) )
                        {
                            $property_nodes_to_update[0][0] = $result;
                        }
                    }
                }
                else
                {
                    $property[$and_rules['field']] = $result;
                }
            }
        }

        return $property;
    }
}

new Houzez_Property_Feed_Export();