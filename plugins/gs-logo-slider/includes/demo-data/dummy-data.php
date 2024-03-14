<?php
namespace GSLOGO;

/**
 * Protect direct access
 */
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'GS_Logo_Slider_Dummy_Data' ) ) {

    final class GS_Logo_Slider_Dummy_Data {

        private static $_instance = null;
        
        public static function get_instance() {

            if ( is_null( self::$_instance ) ) {
                self::$_instance = new GS_Logo_Slider_Dummy_Data();
            }

            return self::$_instance;
            
        }

        public function __construct() {

            if ( ! is_admin() ) return;

            add_action( 'admin_notices', array($this, 'gslogo_dummy_data_admin_notice') );

            add_action( 'wp_ajax_gslogo_dismiss_demo_data_notice', array($this, 'gslogo_dismiss_demo_data_notice') );

            add_action( 'wp_ajax_gslogo_import_logo_data', array($this, 'import_logo_data') );

            add_action( 'wp_ajax_gslogo_remove_logo_data', array($this, 'remove_logo_data') );

            add_action( 'wp_ajax_gslogo_import_shortcode_data', array($this, 'import_shortcode_data') );

            add_action( 'wp_ajax_gslogo_remove_shortcode_data', array($this, 'remove_shortcode_data') );

            add_action( 'wp_ajax_gslogo_import_all_data', array($this, 'import_all_data') );

            add_action( 'wp_ajax_gslogo_remove_all_data', array($this, 'remove_all_data') );

            // Remove dummy indicator
            add_action( 'edit_post_gs_logo_slider', array($this, 'remove_dummy_indicator'), 10 );

            // Import Process
            add_action( 'gslogo_dummy_attachments_process_start', function() {

                // Force delete option if have any
                delete_option( 'gslogo_dummy_logo_data_created' );

                // Force update the process
                set_transient( 'gslogo_dummy_logo_data_creating', 1, 3 * MINUTE_IN_SECONDS );

            });
            
            add_action( 'gslogo_dummy_attachments_process_finished', function() {

                $this->create_dummy_terms();

            });
            
            add_action( 'gslogo_dummy_terms_process_finished', function() {

                $this->create_dummy_logos();

            });
            
            add_action( 'gslogo_dummy_logos_process_finished', function() {

                // clean the record that we have started a process
                delete_transient( 'gslogo_dummy_logo_data_creating' );

                // Add a track so we never duplicate the process
                update_option( 'gslogo_dummy_logo_data_created', 1 );

            });
            
            // Shortcodes
            add_action( 'gslogo_dummy_shortcodes_process_start', function() {

                // Force delete option if have any
                delete_option( 'gslogo_dummy_shortcode_data_created' );

                // Force update the process
                set_transient( 'gslogo_dummy_shortcode_data_creating', 1, 3 * MINUTE_IN_SECONDS );

            });

            add_action( 'gslogo_dummy_shortcodes_process_finished', function() {

                // clean the record that we have started a process
                delete_transient( 'gslogo_dummy_shortcode_data_creating' );

                // Add a track so we never duplicate the process
                update_option( 'gslogo_dummy_shortcode_data_created', 1 );

            });
            
        }

        public function get_taxonomy_list() {

            return ['logo-category'];

        }

        public function remove_dummy_indicator( $post_id ) {

            if ( empty( get_post_meta($post_id, 'gslogo-demo_data', true) ) ) return;
            
            $taxonomies = $this->get_taxonomy_list();

            // Remove dummy indicator from texonomies
            $dummy_terms = wp_get_post_terms( $post_id, $taxonomies, [
                'fields' => 'ids',
                'meta_key' => 'gslogo-demo_data',
                'meta_value' => 1,
            ]);

            if ( !empty($dummy_terms) ) {
                foreach( $dummy_terms as $term_id ) {
                    delete_term_meta( $term_id, 'gslogo-demo_data', 1 );
                }
                delete_transient( 'gslogo_dummy_terms' );
            }

            // Remove dummy indicator from attachments
            $thumbnail_id = get_post_meta( $post_id, '_thumbnail_id', true );
            if ( !empty($thumbnail_id) ) delete_post_meta( $thumbnail_id, 'gslogo-demo_data', 1 );
            delete_transient( 'gslogo_dummy_attachments' );
            
            // Remove dummy indicator from post
            delete_post_meta( $post_id, 'gslogo-demo_data', 1 );
            delete_transient( 'gslogo_dummy_logos' );

        }

        public function import_all_data() {

            // Validate nonce && check permission
            if ( !check_admin_referer('_gslogo_simport_gslogo_demo_gs_') || !current_user_can('manage_options') ) wp_send_json_error( __('Unauthorised Request', 'gslogo'), 401 );

            // Hide the notice
            update_option( 'gslogo_dismiss_demo_data_notice', 1 );

            $response = [
                'logo' => $this->_import_logo_data( false ),
                'shortcode' => $this->_import_shortcode_data( false )
            ];

            if ( wp_doing_ajax() ) wp_send_json_success( $response, 200 );

            return $response;

        }

        public function remove_all_data() {

            // Validate nonce && check permission
            if ( !check_admin_referer('_gslogo_simport_gslogo_demo_gs_') || !current_user_can('manage_options') ) wp_send_json_error( __('Unauthorised Request', 'gslogo'), 401 );

            // Hide the notice
            update_option( 'gslogo_dismiss_demo_data_notice', 1 );

            $response = [
                'logo' => $this->_remove_logo_data( false ),
                'shortcode' => $this->_remove_shortcode_data( false )
            ];

            if ( wp_doing_ajax() ) wp_send_json_success( $response, 200 );

            return $response;

        }

        public function import_logo_data() {

            // Validate nonce && check permission
            if ( !check_admin_referer('_gslogo_simport_gslogo_demo_gs_') || !current_user_can('manage_options') ) wp_send_json_error( __('Unauthorised Request', 'gslogo'), 401 );

            // Hide the notice
            update_option( 'gslogo_dismiss_demo_data_notice', 1 );

            // Start importing
            $this->_import_logo_data();

        }

        public function remove_logo_data() {

            // Validate nonce && check permission
            if ( !check_admin_referer('_gslogo_simport_gslogo_demo_gs_') || !current_user_can('manage_options') ) wp_send_json_error( __('Unauthorised Request', 'gslogo'), 401 );

            // Hide the notice
            update_option( 'gslogo_dismiss_demo_data_notice', 1 );

            // Remove logo data
            $this->_remove_logo_data();

        }

        public function import_shortcode_data() {

            // Validate nonce && check permission
            if ( !check_admin_referer('_gslogo_simport_gslogo_demo_gs_') || !current_user_can('manage_options') ) wp_send_json_error( __('Unauthorised Request', 'gslogo'), 401 );

            // Hide the notice
            update_option( 'gslogo_dismiss_demo_data_notice', 1 );

            // Start importing
            $this->_import_shortcode_data();

        }

        public function remove_shortcode_data() {

            // Validate nonce && check permission
            if ( !check_admin_referer('_gslogo_simport_gslogo_demo_gs_') || !current_user_can('manage_options') ) wp_send_json_error( __('Unauthorised Request', 'gslogo'), 401 );

            // Hide the notice
            update_option( 'gslogo_dismiss_demo_data_notice', 1 );

            // Remove logo data
            $this->_remove_shortcode_data();

        }

        public function _import_logo_data( $is_ajax = null ) {

            if ( $is_ajax === null ) $is_ajax = wp_doing_ajax();

            // Data already imported
            if ( get_option('gslogo_dummy_logo_data_created') !== false || get_transient('gslogo_dummy_logo_data_creating') !== false ) {

                $message_202 = __( 'Dummy Logos already imported', 'gslogo' );

                if ( $is_ajax ) wp_send_json_success( $message_202, 202 );
                
                return [
                    'status' => 202,
                    'message' => $message_202
                ];

            }
            
            // Importing demo data
            $this->create_dummy_attachments();

            $message = __( 'Dummy Logos imported', 'gslogo' );

            if ( $is_ajax ) wp_send_json_success( $message, 200 );

            return [
                'status' => 200,
                'message' => $message
            ];

        }

        public function _remove_logo_data( $is_ajax = null ) {

            if ( $is_ajax === null ) $is_ajax = wp_doing_ajax();

            $this->delete_dummy_attachments();
            $this->delete_dummy_terms();
            $this->delete_dummy_logos();

            delete_option( 'gslogo_dummy_logo_data_created' );
            delete_transient( 'gslogo_dummy_logo_data_creating' );

            $message = __( 'Dummy Logos deleted', 'gslogo' );

            if ( $is_ajax ) wp_send_json_success( $message, 200 );

            return [
                'status' => 200,
                'message' => $message
            ];

        }

        public function _import_shortcode_data( $is_ajax = null ) {

            if ( $is_ajax === null ) $is_ajax = wp_doing_ajax();

            // Data already imported
            if ( get_option('gslogo_dummy_shortcode_data_created') !== false || get_transient('gslogo_dummy_shortcode_data_creating') !== false ) {

                $message_202 = __( 'Dummy Shortcodes already imported', 'gslogo' );

                if ( $is_ajax ) wp_send_json_success( $message_202, 202 );
                
                return [
                    'status' => 202,
                    'message' => $message_202
                ];

            }
            
            // Importing demo shortcodes
            $this->create_dummy_shortcodes();

            $message = __( 'Dummy Shortcodes imported', 'gslogo' );

            if ( $is_ajax ) wp_send_json_success( $message, 200 );

            return [
                'status' => 200,
                'message' => $message
            ];

        }

        public function _remove_shortcode_data( $is_ajax = null ) {

            if ( $is_ajax === null ) $is_ajax = wp_doing_ajax();

            $this->delete_dummy_shortcodes();

            delete_option( 'gslogo_dummy_shortcode_data_created' );
            delete_transient( 'gslogo_dummy_shortcode_data_creating' );

            $message = __( 'Dummy Shortcodes deleted', 'gslogo' );

            if ( $is_ajax ) wp_send_json_success( $message, 200 );

            return [
                'status' => 200,
                'message' => $message
            ];

        }

        public function get_taxonomy_ids_by_slugs( $taxonomy_group, $taxonomy_slugs = [] ) {

            $_terms = $this->get_dummy_terms();

            if ( empty($_terms) ) return [];
            
            $_terms = wp_filter_object_list( $_terms, [ 'taxonomy' => $taxonomy_group ] );
            $_terms = array_values( $_terms );      // reset the keys
            
            if ( empty($_terms) ) return [];
            
            $term_ids = [];
            
            foreach ( $taxonomy_slugs as $slug ) {
                $key = array_search( $slug, array_column($_terms, 'slug') );
                if ( $key !== false ) $term_ids[] = $_terms[$key]['term_id'];
            }

            return $term_ids;

        }

        public function get_attachment_id_by_filename( $filename ) {

            $attachments = $this->get_dummy_attachments();
            
            if ( empty($attachments) ) return '';
            
            $attachments = wp_filter_object_list( $attachments, [ 'post_name' => $filename ] );
            if ( empty($attachments) ) return '';
            
            $attachments = array_values( $attachments );
            
            return $attachments[0]->ID;

        }

        public function get_tax_inputs( $tax_inputs = [] ) {

            if ( empty($tax_inputs) ) return $tax_inputs;

            foreach( $tax_inputs as $tax_input => $tax_params ) {

                $tax_inputs[$tax_input] = $this->get_taxonomy_ids_by_slugs( $tax_input, $tax_params );

            }

            return $tax_inputs;

        }

        public function get_meta_inputs( $meta_inputs = [] ) {

            $meta_inputs['_thumbnail_id'] = $this->get_attachment_id_by_filename( $meta_inputs['_thumbnail_id'] );

            return $meta_inputs;

        }

        // Logos
        public function create_dummy_logos() {

            do_action( 'gslogo_dummy_logos_process_start' );

            $post_status = 'publish';
            $post_type = 'gs-logo-slider';

            $logos = [];

            $logos[] = array(
                'post_title'    => 'GS Logo One',
                'post_content'  => 'Suspendisse faucibus, nunc et pellentesque egestas, lacus ante convallis tellus, vitae iaculis lacus elit id tortor. Fusce fermentum. Cras non dolor. Donec interdum, metus et hendrerit aliquet, dolor diam sagittis ligula, eget egestas libero turpis vel mi.',
                'post_status'   => $post_status,
                'post_type' => $post_type,
                'post_date' => '2020-08-15 07:01:44',
                'tax_input' => $this->get_tax_inputs([
                    'logo-category' => ['category-one'],
                ]),
                'meta_input' => $this->get_meta_inputs([
                    '_thumbnail_id' => 'gs-logo-slider-1',
                    'client_url' => 'https://gsplugins.com/',
                ])
            );

            $logos[] = array(
                'post_title'    => 'GS Logo Two',
                'post_content'  => 'Suspendisse faucibus, nunc et pellentesque egestas, lacus ante convallis tellus, vitae iaculis lacus elit id tortor. Fusce fermentum. Cras non dolor. Donec interdum, metus et hendrerit aliquet, dolor diam sagittis ligula, eget egestas libero turpis vel mi.',
                'post_status'   => $post_status,
                'post_type' => $post_type,
                'post_date' => '2020-08-15 07:01:44',
                'tax_input' => $this->get_tax_inputs([
                    'logo-category' => ['category-one', 'category-three'],
                ]),
                'meta_input' => $this->get_meta_inputs([
                    '_thumbnail_id' => 'gs-logo-slider-2',
                    'client_url' => 'https://gsplugins.com/',
                ])
            );

            $logos[] = array(
                'post_title'    => 'GS Logo Three',
                'post_content'  => 'Suspendisse faucibus, nunc et pellentesque egestas, lacus ante convallis tellus, vitae iaculis lacus elit id tortor. Fusce fermentum. Cras non dolor. Donec interdum, metus et hendrerit aliquet, dolor diam sagittis ligula, eget egestas libero turpis vel mi.',
                'post_status'   => $post_status,
                'post_type' => $post_type,
                'post_date' => '2020-08-15 07:01:44',
                'tax_input' => $this->get_tax_inputs([
                    'logo-category' => ['category-two', 'category-three'],
                ]),
                'meta_input' => $this->get_meta_inputs([
                    '_thumbnail_id' => 'gs-logo-slider-3',
                    'client_url' => 'https://gsplugins.com/',
                ])
            );

            $logos[] = array(
                'post_title'    => 'GS Logo Four',
                'post_content'  => 'Suspendisse faucibus, nunc et pellentesque egestas, lacus ante convallis tellus, vitae iaculis lacus elit id tortor. Fusce fermentum. Cras non dolor. Donec interdum, metus et hendrerit aliquet, dolor diam sagittis ligula, eget egestas libero turpis vel mi.',
                'post_status'   => $post_status,
                'post_type' => $post_type,
                'post_date' => '2020-08-15 07:01:44',
                'tax_input' => $this->get_tax_inputs([
                    'logo-category' => ['category-two'],
                ]),
                'meta_input' => $this->get_meta_inputs([
                    '_thumbnail_id' => 'gs-logo-slider-4',
                    'client_url' => 'https://gsplugins.com/',
                ])
            );

            $logos[] = array(
                'post_title'    => 'GS Logo Five',
                'post_content'  => 'Suspendisse faucibus, nunc et pellentesque egestas, lacus ante convallis tellus, vitae iaculis lacus elit id tortor. Fusce fermentum. Cras non dolor. Donec interdum, metus et hendrerit aliquet, dolor diam sagittis ligula, eget egestas libero turpis vel mi.',
                'post_status'   => $post_status,
                'post_type' => $post_type,
                'post_date' => '2020-08-15 07:01:44',
                'tax_input' => $this->get_tax_inputs([
                    'logo-category' => ['category-two', 'category-three'],
                ]),
                'meta_input' => $this->get_meta_inputs([
                    '_thumbnail_id' => 'gs-logo-slider-5',
                    'client_url' => 'https://gsplugins.com/',
                ])
            );

            $logos[] = array(
                'post_title'    => 'GS Logo Six',
                'post_content'  => 'Suspendisse faucibus, nunc et pellentesque egestas, lacus ante convallis tellus, vitae iaculis lacus elit id tortor. Fusce fermentum. Cras non dolor. Donec interdum, metus et hendrerit aliquet, dolor diam sagittis ligula, eget egestas libero turpis vel mi.',
                'post_status'   => $post_status,
                'post_type' => $post_type,
                'post_date' => '2020-08-15 07:01:44',
                'tax_input' => $this->get_tax_inputs([
                    'logo-category' => ['category-one', 'category-three'],
                ]),
                'meta_input' => $this->get_meta_inputs([
                    '_thumbnail_id' => 'gs-logo-slider-6',
                    'client_url' => 'https://gsplugins.com/',
                ])
            );

            $logos[] = array(
                'post_title'    => 'GS Logo Seven',
                'post_content'  => 'Suspendisse faucibus, nunc et pellentesque egestas, lacus ante convallis tellus, vitae iaculis lacus elit id tortor. Fusce fermentum. Cras non dolor. Donec interdum, metus et hendrerit aliquet, dolor diam sagittis ligula, eget egestas libero turpis vel mi.',
                'post_status'   => $post_status,
                'post_type' => $post_type,
                'post_date' => '2020-08-15 07:01:44',
                'tax_input' => $this->get_tax_inputs([
                    'logo-category' => ['category-two', 'category-three'],
                ]),
                'meta_input' => $this->get_meta_inputs([
                    '_thumbnail_id' => 'gs-logo-slider-7',
                    'client_url' => 'https://gsplugins.com/',
                ])
            );

            $logos[] = array(
                'post_title'    => 'GS Logo Eight',
                'post_content'  => 'Suspendisse faucibus, nunc et pellentesque egestas, lacus ante convallis tellus, vitae iaculis lacus elit id tortor. Fusce fermentum. Cras non dolor. Donec interdum, metus et hendrerit aliquet, dolor diam sagittis ligula, eget egestas libero turpis vel mi.',
                'post_status'   => $post_status,
                'post_type' => $post_type,
                'post_date' => '2020-08-15 07:01:44',
                'tax_input' => $this->get_tax_inputs([
                    'logo-category' => ['category-three'],
                ]),
                'meta_input' => $this->get_meta_inputs([
                    '_thumbnail_id' => 'gs-logo-slider-8',
                    'client_url' => 'https://gsplugins.com/',
                ])
            );

            $logos[] = array(
                'post_title'    => 'GS Logo Nine',
                'post_content'  => 'Suspendisse faucibus, nunc et pellentesque egestas, lacus ante convallis tellus, vitae iaculis lacus elit id tortor. Fusce fermentum. Cras non dolor. Donec interdum, metus et hendrerit aliquet, dolor diam sagittis ligula, eget egestas libero turpis vel mi.',
                'post_status'   => $post_status,
                'post_type' => $post_type,
                'post_date' => '2020-08-15 07:01:44',
                'tax_input' => $this->get_tax_inputs([
                    'logo-category' => ['category-one', 'category-two', 'category-three'],
                ]),
                'meta_input' => $this->get_meta_inputs([
                    '_thumbnail_id' => 'gs-logo-slider-9',
                    'client_url' => 'https://gsplugins.com/',
                ])
            );

            $logos[] = array(
                'post_title'    => 'GS Logo Ten',
                'post_content'  => 'Suspendisse faucibus, nunc et pellentesque egestas, lacus ante convallis tellus, vitae iaculis lacus elit id tortor. Fusce fermentum. Cras non dolor. Donec interdum, metus et hendrerit aliquet, dolor diam sagittis ligula, eget egestas libero turpis vel mi.',
                'post_status'   => $post_status,
                'post_type' => $post_type,
                'post_date' => '2020-08-15 07:01:44',
                'tax_input' => $this->get_tax_inputs([
                    'logo-category' => ['category-one', 'category-two'],
                ]),
                'meta_input' => $this->get_meta_inputs([
                    '_thumbnail_id' => 'gs-logo-slider-10',
                    'client_url' => 'https://gsplugins.com/',
                ])
            );

            $logos[] = array(
                'post_title'    => 'GS Logo Eleven',
                'post_content'  => 'Suspendisse faucibus, nunc et pellentesque egestas, lacus ante convallis tellus, vitae iaculis lacus elit id tortor. Fusce fermentum. Cras non dolor. Donec interdum, metus et hendrerit aliquet, dolor diam sagittis ligula, eget egestas libero turpis vel mi.',
                'post_status'   => $post_status,
                'post_type' => $post_type,
                'post_date' => '2020-08-15 07:01:44',
                'tax_input' => $this->get_tax_inputs([
                    'logo-category' => ['category-two', 'category-three'],
                ]),
                'meta_input' => $this->get_meta_inputs([
                    '_thumbnail_id' => 'gs-logo-slider-11',
                    'client_url' => 'https://gsplugins.com/',
                ])
            );

            $logos[] = array(
                'post_title'    => 'GS Logo Twelve',
                'post_content'  => 'Suspendisse faucibus, nunc et pellentesque egestas, lacus ante convallis tellus, vitae iaculis lacus elit id tortor. Fusce fermentum. Cras non dolor. Donec interdum, metus et hendrerit aliquet, dolor diam sagittis ligula, eget egestas libero turpis vel mi.',
                'post_status'   => $post_status,
                'post_type' => $post_type,
                'post_date' => '2020-08-15 07:01:44',
                'tax_input' => $this->get_tax_inputs([
                    'logo-category' => ['category-one', 'category-three'],
                ]),
                'meta_input' => $this->get_meta_inputs([
                    '_thumbnail_id' => 'gs-logo-slider-12',
                    'client_url' => 'https://gsplugins.com/',
                ])
            );

            foreach ( $logos as $logo ) {
                // Insert the post into the database
                $post_id = wp_insert_post( $logo );
                // Add meta value for demo
                if ( $post_id ) add_post_meta( $post_id, 'gslogo-demo_data', 1 );
            }

            do_action( 'gslogo_dummy_logos_process_finished' );

        }

        public function delete_dummy_logos() {
            
            $logos = $this->get_dummy_logos();

            if ( empty($logos) ) return;

            foreach ($logos as $logo) {
                wp_delete_post( $logo->ID, true );
            }

            delete_transient( 'gslogo_dummy_logos' );

        }

        public function get_dummy_logos() {

            $logos = get_transient( 'gslogo_dummy_logos' );

            if ( false !== $logos ) return $logos;

            $logos = get_posts( array(
                'numberposts' => -1,
                'post_type'   => 'gs-logo-slider',
                'meta_key' => 'gslogo-demo_data',
                'meta_value' => 1,
            ));
            
            if ( is_wp_error($logos) || empty($logos) ) {
                delete_transient( 'gslogo_dummy_logos' );
                return [];
            }
            
            set_transient( 'gslogo_dummy_logos', $logos, 3 * MINUTE_IN_SECONDS );

            return $logos;

        }

        public function http_request_args( $args ) {
            
            $args['sslverify'] = false;

            return $args;

        }

        // Attachments
        public function create_dummy_attachments() {

            do_action( 'gslogo_dummy_attachments_process_start' );

            require_once( ABSPATH . 'wp-admin/includes/image.php' );

            $attachment_files = [
                'gs-logo-slider-1.png',
                'gs-logo-slider-2.png',
                'gs-logo-slider-3.png',
                'gs-logo-slider-4.png',
                'gs-logo-slider-5.png',
                'gs-logo-slider-6.png',
                'gs-logo-slider-7.png',
                'gs-logo-slider-8.png',
                'gs-logo-slider-9.png',
                'gs-logo-slider-10.png',
                'gs-logo-slider-11.png',
                'gs-logo-slider-12.png',
            ];

            add_filter( 'http_request_args', [ $this, 'http_request_args' ] );

            wp_raise_memory_limit( 'image' );

            foreach ( $attachment_files as $file ) {

                $file = GSL_PLUGIN_URI . 'assets/img/dummy-data/' . $file;

                $filename = basename($file);

                $get = wp_remote_get( $file );
                $type = wp_remote_retrieve_header( $get, 'content-type' );
                $mirror = wp_upload_bits( $filename, null, wp_remote_retrieve_body( $get ) );
                
                // Prepare an array of post data for the attachment.
                $attachment = array(
                    'guid'           => $mirror['url'],
                    'post_mime_type' => $type,
                    'post_title'     => preg_replace( '/\.[^.]+$/', '', $filename ),
                    'post_content'   => '',
                    'post_status'    => 'inherit'
                );
                
                // Insert the attachment.
                $attach_id = wp_insert_attachment( $attachment, $mirror['file'] );
                
                // Generate the metadata for the attachment, and update the database record.
                $attach_data = wp_generate_attachment_metadata( $attach_id, $mirror['file'] );
                wp_update_attachment_metadata( $attach_id, $attach_data );

                add_post_meta( $attach_id, 'gslogo-demo_data', 1 );

            }

            remove_filter( 'http_request_args', [ $this, 'http_request_args' ] );

            do_action( 'gslogo_dummy_attachments_process_finished' );

        }

        public function delete_dummy_attachments() {
            
            $attachments = $this->get_dummy_attachments();

            if ( empty($attachments) ) return;

            foreach ($attachments as $attachment) {
                wp_delete_attachment( $attachment->ID, true );
            }

            delete_transient( 'gslogo_dummy_attachments' );

        }

        public function get_dummy_attachments() {

            $attachments = get_transient( 'gslogo_dummy_attachments' );

            if ( false !== $attachments ) return $attachments;

            $attachments = get_posts( array(
                'numberposts' => -1,
                'post_type'   => 'attachment',
                'post_status' => 'inherit',
                'meta_key' => 'gslogo-demo_data',
                'meta_value' => 1,
            ));
            
            if ( is_wp_error($attachments) || empty($attachments) ) {
                delete_transient( 'gslogo_dummy_attachments' );
                return [];
            }
            
            set_transient( 'gslogo_dummy_attachments', $attachments, 3 * MINUTE_IN_SECONDS );

            return $attachments;

        }
        
        // Terms
        public function create_dummy_terms() {

            do_action( 'gslogo_dummy_terms_process_start' );
            
            $terms = [
                // 3 Groups
                [
                    'name' => 'Category One',
                    'slug' => 'category-one',
                    'group' => 'logo-category'
                ],
                [
                    'name' => 'Category Two',
                    'slug' => 'category-two',
                    'group' => 'logo-category'
                ],
                [
                    'name' => 'Category Three',
                    'slug' => 'category-three',
                    'group' => 'logo-category'
                ]
            ];

            foreach( $terms as $term ) {

                $response = wp_insert_term( $term['name'], $term['group'], array('slug' => $term['slug']) );
    
                if ( ! is_wp_error($response) ) {
                    add_term_meta( $response['term_id'], 'gslogo-demo_data', 1 );
                }

            }

            do_action( 'gslogo_dummy_terms_process_finished' );

        }
        
        public function delete_dummy_terms() {
            
            $terms = $this->get_dummy_terms();

            if ( empty($terms) ) return;
    
            foreach ( $terms as $term ) {
                wp_delete_term( $term['term_id'], $term['taxonomy'] );
            }

            delete_transient( 'gslogo_dummy_terms' );

        }

        public function get_dummy_terms() {

            $terms = get_transient( 'gslogo_dummy_terms' );

            if ( false !== $terms ) return $terms;

            $taxonomies = $this->get_taxonomy_list();

            $terms = get_terms( array(
                'taxonomy' => $taxonomies,
                'hide_empty' => false,
                'meta_key' => 'gslogo-demo_data',
                'meta_value' => 1,
            ));

            $terms = json_decode( json_encode( $terms ), true ); // Object to Array
            
            if ( is_wp_error($terms) || empty($terms) ) {
                delete_transient( 'gslogo_dummy_terms' );
                return [];
            }

            set_transient( 'gslogo_dummy_terms', $terms, 3 * MINUTE_IN_SECONDS );

            return $terms;

        }

        // Shortcode
        public function create_dummy_shortcodes() {

            do_action( 'gslogo_dummy_shortcodes_process_start' );

            plugin()->builder->create_dummy_shortcodes();

            do_action( 'gslogo_dummy_shortcodes_process_finished' );

        }

        public function delete_dummy_shortcodes() {
            
            plugin()->builder->delete_dummy_shortcodes();

        }

        // Notice
        function gslogo_dummy_data_admin_notice() {

            // delete_option('gslogo_dismiss_demo_data_notice');

            if ( get_option('gslogo_dismiss_demo_data_notice') ) return;

            if ( get_current_screen()->id == 'gs-logo-slider_page_gs-logo-shortcode' ) return;

            ?>

            <div id="gslogo-dummy-data-install--notice" class="notice notice-success is-dismissible">

                <h3>GS Logo Slider - Install Demo Data!</h3>

                <p><b>GS Logo Slider</b> plugin offers to install <b>demo data</b> with just one click.</p>
                <p>You can remove the data anytime if you want by another click.</p>

                <p style="margin-top: 16px; margin-bottom: 18px;">

                    <a href="<?php echo admin_url( 'edit.php?post_type=gs-logo-slider&page=gs-logo-shortcode#/demo-data' ); ?>" class="button button-primary" style="margin-right: 10px;">Install Demo Data</a>

                    <a href="javascript:void(0)" onclick="jQuery('#gslogo-dummy-data-install--notice').slideUp(); jQuery.post(ajaxurl, {action: 'gslogo_dismiss_demo_data_notice', nonce: '<?php echo wp_create_nonce('_gslogo_dismiss_demo_data_notice_gs_'); ?>' });">
                        <?php _e( "Don't show this message again", 'gslogo'); ?>
                    </a>

                </p>

            </div>
            <?php

        }

        function gslogo_dismiss_demo_data_notice() {

            $nonce = isset($_REQUEST['nonce']) ? $_REQUEST['nonce'] : null;

            if ( ! wp_verify_nonce( $nonce, '_gslogo_dismiss_demo_data_notice_gs_') ) {

                wp_send_json_error( __('Unauthorised Request', 'gslogo'), 401 );

            }

            update_option( 'gslogo_dismiss_demo_data_notice', 1 );

        }

    }

}

GS_Logo_Slider_Dummy_Data::get_instance();