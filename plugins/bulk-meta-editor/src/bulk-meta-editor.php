<?php

namespace BulkMetaEditor;

use BulkMetaEditor\Notices;

class BulkMetaEditor
{   
    public function __construct()
    {
        register_activation_hook(BME_MAIN_FILE, [$this, 'activate']);
        register_deactivation_hook(BME_MAIN_FILE, [$this, 'deactivate']);
        add_action('admin_menu', [$this, 'createAdminMenu']);
        add_action('admin_post_arva_submit', [$this, 'processBulkData']);
        
        if(isset($_GET['message']) && $_GET['message'] == 'bme-message') {
            add_action('admin_notices', function() {
                Notices::get();
            });
        }
    }

    public function loadMenuPageView()
    {
        require_once BME_PLUGIN_PATH . 'views/admin-settings.php';
    }

    public function createAdminMenu()
    {
        add_menu_page(
            'Bulk Meta Editor', 
            'Bulk Meta Editor', 
            'manage_options', 
            'bulk-meta-editor',
            [$this, 'loadMenuPageView'],
            'dashicons-edit-large'
        );
    }

    public function isYoastActive()
    {
        $yoast_variants = [
            'Yoast SEO'         => 'wordpress-seo/wp-seo.php', 
            'Yoast SEO Premium' => 'wordpress-seo-premium/wp-seo-premium.php',
        ];

        foreach($yoast_variants as $value) {

            if(!in_array($value, apply_filters('active_plugins', get_option('active_plugins'))) ) {             
                return false;
            }

            return true;
        }
    }

    public function isProVersionActive()
    {
        $pro_version = 'bulk-meta-editor-pro/bulk-meta-editor-pro.php';
        
        if(is_plugin_active($pro_version)) {
            return true;
        }

        return false;
    }

    public function getPostId($url)
    {
        $post_id = url_to_postid($url);

        return $post_id;
    }

    public function sanitize($raw, $type)
    {
        switch($type) {

            case 'url':
                $sanitized = esc_url_raw($raw);
                break;
            
            case 'text':
                $sanitized = sanitize_text_field($raw);
                break;

            case 'number':
                $sanitized = intval($raw);
                break;
        }

        return $sanitized;
    }

    public function validate($raw, $type)
    {
        switch($type) {
            case 'csv':
                $acceptable_mimes = [
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    'application/x-vnd.oasis.opendocument.spreadsheet',
                    'application/vnd.ms-excel',
                    'text/csv',
                ];

                if(in_array($raw, $acceptable_mimes)) {
                    return true;
                }
                return false;
                break;

            case 'number':
                if(is_numeric($raw)) {
                    return true;
                }
                break;

            case 'url':
                if(esc_url_raw($raw) === $raw) {
                    return true;
                }
                return false;
                break;
        }
    }

    public function activate()
    {
        $pro_version_active = $this->isProVersionActive();
        
        if($pro_version_active) {
            deactivate_plugins('bulk-meta-editor-pro/bulk-meta-editor-pro.php', true);
        }
    }

    public function deactivate()
    {
        delete_option('arva_bme_notices');
    }

    public function redirect()
    {
        wp_redirect(admin_url('admin.php?page=bulk-meta-editor&message=bme-message'));
        exit;
    }

    public function processBulkData()
    {
        if(current_user_can('manage_options') && current_user_can('upload_files')) {

            $file = $this->sanitize(realpath($_FILES['file_upload']['tmp_name']), 'text');
            
            // Lets open the file, data are validated invidually after converted to array by the fgetcsv function
            $handle  = fopen($file, "r");
            
            // Checks if the file exists, redirect if not
            if(empty($handle) || !$this->validate($_FILES['file_upload']['type'], 'csv')) {
                Notices::set('No file attached or the uploaded file is not in CSV format', 'notice-error');
                $this->redirect();
            }

            $headers = fgetcsv($handle);
            
            while (($data = fgetcsv($handle)) !== FALSE) {

                // validate if URL is valid for conversion to post ID
                if($this->validate($data[0], 'url')) {
                    $post_id = $this->getPostId($this->sanitize($data[0], 'url'));
                }
                
                // Checks if there is a value in the Meta Title colum of the CSV, then updates the key with the new data.
                if(!empty($data[1])) {
                    update_post_meta($post_id, '_yoast_wpseo_title', $this->sanitize($data[1], 'text'));
                }

                // Checks if there is a value in the Meta Description column of the CSV, then updates the key with the new data.
                if(!empty($data[2])) {
                    update_post_meta($post_id, '_yoast_wpseo_metadesc', $this->sanitize($data[2], 'text'));
                }

                // Checks if there is a value in the Canonical URL column of the CSV and is a valid URL, then updates the key with the new data.
                if(!empty($data[3]) && $this->validate($data[3], 'url')) {
                    update_post_meta($post_id, '_yoast_wpseo_canonical', $this->sanitize($data[3], 'url'));
                }

                // Checks if there is a value in the Noindex column of the CSV and is valid number, then updates the key with the new data.
                if(!empty($data[4]) && $this->validate($data[4], 'number')) {
                    update_post_meta($post_id, '_yoast_wpseo_meta-robots-noindex', $this->sanitize($data[4], 'number'));
                }

                // Checks if there is a value in the Nofollow column of the CSV and is valid number, then updates the key with the new data.
                if(!empty($data[5]) && $this->validate($data[4], 'number')) {
                    update_post_meta($post_id, '_yoast_wpseo_meta-robots-nofollow', $this->sanitizeText($data[5], 'number'));
                }

            }

            fclose($handle);

            Notices::set('Metadata updated.', 'notice-success', false);
            $this->redirect();

        } else {

            Notices::set('Action not permitted', 'notice-error', false);
            $this->redirect();

        }
    }
}