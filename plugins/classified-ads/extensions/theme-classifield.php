<?php

namespace Classified_Ads\Extensions\Themes;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class Classified
{
    /**
     * data array
     *
     * @var array
     */
    public $data = array();
    
    public function __construct($data = array(), $args = null)
    {

        add_filter( 'wdk/settings/import/multipurpose_values', array($this, 'multipurpose_values'));
        add_filter( 'wdk/settings/import/run/fields', array($this, 'add_theme'));
        add_filter( 'wdk/settings/import/run/post', array($this, 'update_post_data'));
        add_filter( 'wdk/settings/import/run/import_images_dir', array($this, 'update_images_dir'), 10, 2);
        add_filter( 'wdk/settings/import/run/import_xml_file', array($this, 'update_import_xml_file'), 10, 2);
        add_action( 'wdk/settings/import/run', array($this, 'install_pages'), 10, 2);
        add_action( 'wdk/settings/import/api_run', array($this, 'api_install_pages'), 10, 2);
        add_filter( 'wdk/settings/import/api_run/import_images_dir', array($this, 'api_update_images_dir'), 10, 2);
        add_filter( 'wdk/settings/import/api_run/import_xml_file', array($this, 'api_update_import_xml_file'), 10, 2);
        add_filter( 'wdk/settings/import/run/info_log_message', array($this, 'info_log_message'), 10, 2);
    }

    public function info_log_message ($info_log_message, $data){
        if($data['multipurpose'] == 'classified.xml') {
            return '<div class="alert alert-info" role="alert">'.esc_html__('Import completed successfully', 'wpdirectorykit').', <a href="'. home_url().'">'.__('Check your page now', 'wpdirectorykit').'</a></div>';
        }

        return $info_log_message;
    }

    public function add_theme ($fields){
        $fields['multipurpose']['values']['classified.xml'] = esc_html__('Classified Ads', 'classified-ads');
        return $fields;
    }

    public function multipurpose_values ($multipurpose_values){
        $multipurpose_values['classified.xml'] = esc_html__('Classified Ads', 'classified-ads');
        return $multipurpose_values;
    }

    public function update_post_data ($data){
        return $data;
    }

    public function update_images_dir ($import_images_dir, $data){
        if($data['multipurpose'] == 'classified.xml') {
            return CLASSIFIED_ADS_PATH . 'demo-data/images/';
        }

        return $import_images_dir;
    }

    public function update_import_xml_file ($xml_file, $data){
        if($data['multipurpose'] == 'classified.xml') {
            return CLASSIFIED_ADS_PATH . 'demo-data/classified.xml';
        }

        return $xml_file;
    }


    public function install_pages ($data){
        if($data['multipurpose'] == 'classified.xml') {
            $WMVC = &wdk_get_instance();
            $WMVC->load_controller('wdk_settings','create_custom_page', array(CLASSIFIED_ADS_PATH . 'demo-data/page-classified.json', 'Home'));

            // Assign front page
            $front_page_id = wdk_page_by_title( 'Home' );
            update_option( 'show_on_front', 'page' );
            update_option( 'page_on_front', $front_page_id->ID );

            $this->replace_data();
        }
    }

    public function api_install_pages ($multipurpose){
        if($multipurpose == 'classified.xml') {
            if(get_option('wdk_listing_page') || get_option('wdk_results_page')) return true;
            $WMVC = &wdk_get_instance();
            $WMVC->load_controller('wdk_settings','create_custom_page', array(CLASSIFIED_ADS_PATH . 'demo-data/page-classified.json', 'Home'));

            // Assign front page
            $front_page_id = wdk_page_by_title( 'Home' );
            update_option( 'show_on_front', 'page' );
            update_option( 'page_on_front', $front_page_id->ID );

            $this->replace_data();
        }
    }

    public function api_update_images_dir ($import_images_dir, $multipurpose){
        if($multipurpose == 'classified.xml') {
            return CLASSIFIED_ADS_PATH . 'demo-data/images/';
        }

        return $import_images_dir;
    }

    public function api_update_import_xml_file ($xml_file, $multipurpose){
        if($multipurpose == 'classified.xml') {
            return CLASSIFIED_ADS_PATH . 'demo-data/classified.xml';
        }

        return $xml_file;
    }

    private function replace_data() {
        $this->replace_string('WordPress Real Estate Theme', 'WordPress Classified Ads Theme');
        $this->replace_string('Featured Properties', 'Featured Listings');
        $this->replace_string('Property Types', 'Listing Categories');
        $this->replace_string('Popular House Types', 'Popular Categories');
        $this->replace_string('Find The Perfect House', 'Find The Perfect Listing');
        $this->replace_string('Popular Properties', 'Popular Listings');
        $this->replace_string('Add Property', 'Post Ad');
    }

    private function replace_string($from='', $to='') {
        global $wpdb;       
        // @codingStandardsIgnoreStart cannot use `$wpdb->prepare` because it remove's the backslashes
        $rows_affected = $wpdb->query(
            "UPDATE ".esc_sql($wpdb->postmeta)." " .
            "SET `meta_value` = REPLACE(`meta_value`, '" . str_replace( '/', '\\\/', esc_sql($from) ) . "', '" . str_replace( '/', '\\\/', esc_sql($to) ) . "') " .
            "WHERE `meta_key` = '_elementor_data' AND `meta_value` LIKE '[%' ;" );
    }
}
