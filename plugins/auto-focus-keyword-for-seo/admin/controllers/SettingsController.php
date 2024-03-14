<?php

namespace Pagup\AutoFocusKeyword\Controllers;

use Pagup\AutoFocusKeyword\Core\Option;
use Pagup\AutoFocusKeyword\Core\Plugin;
use Pagup\AutoFocusKeyword\Core\Request;
use Pagup\AutoFocusKeyword\Traits\Helpers;

class SettingsController 
{
    use Helpers;

    public $batch_size = 20;

    public function add_settings()
    {
        add_menu_page (
			__( 'Auto Focus Keyword', 'auto-focus-keyword-for-seo' ),
			__( 'Auto Focus Keyword','auto-focus-keyword-for-seo' ),
			'manage_options',
			AFKW_NAME,
			array( &$this, 'page' ),
			'dashicons-yes-alt'
		);
    }

    public function page()
    {
        global $wpdb;

        $safe = [ "allow", "settings", "logs", "faq" ];
        $updated = "";

        if (isset($_POST['update'])) {
            // check if user is authorised
            if ( function_exists( 'current_user_can' ) && !current_user_can( 'manage_options' ) ) {
                die( 'Sorry, not allowed...' );
            }
        
            check_admin_referer( 'afkw__settings', 'afkw__nonce' );
        
            $options = [
                'post_types' => array_map('sanitize_key', $_POST['post_types']),
                'blacklist' => sanitize_textarea_field(trim($_POST['blacklist'])),
                'disable_auto_sync' => Request::safe($_POST['disable_auto_sync'] ?? null, $safe),
                'remove_settings' => Request::safe($_POST['remove_settings'] ?? null, $safe),
            ];
            
            update_option( 'afkw_auto-focus-keyword-for-seo', $options );
        
            // update options
            $updated = '<div class="afkw-alert afkw-success" style="display: block; width: 100%;margin: 20px 0 0;"><strong>' . esc_html__( 'Settings saved.', 'auto-focus-keyword-for-seo' ) . '</strong></div>' ;
        }

        $options = new Option;

        //Set active class for navigation tabs
        $active_tab = ( isset( $_GET['tab'] ) && in_array( $_GET['tab'], $safe ) ? sanitize_key($_GET['tab']) : 'settings' );

        $get_pro = sprintf( wp_kses( __( '<a href="%s">Get Pro version</a> to enable', 'auto-focus-keyword-for-seo' ), array(  'a' => array( 'href' => array(), 'target' => array() ) ) ), esc_url( 'admin.php?page='.AFKW_NAME.'-pricing' ) );

        $total_items_require_sync = $this->get_total_pages_and_items();
        $nonce = wp_create_nonce( 'autokeywords' );

        $sync_logs = get_option('afkw_autokeyword_logs');

        if (is_array($sync_logs)) {
            // Sort the array by the 'updated_at' field
            usort($sync_logs, function($a, $b) {
                return $b['updated_at'] <=> $a['updated_at'];
            });
        } else {
            $sync_logs = [];
        }

        $allowed_post_types = Option::check('post_types') ? Option::get('post_types') : [];
        $posts = $this->get_items( get_posts(array(
            'post_type' => $allowed_post_types,
            'orderby'   => 'title',
            'order'   => 'ASC',
            'fields' => 'ids',
            'numberposts' => -1
        )), true);
        
        wp_localize_script( 'afkw__script', 'data', array(
            'total_pages_and_items' => $total_items_require_sync,
            'batch_size' => $this->batch_size,
            'syncDate' => get_option( "afkw_autokeyword_sync" ),
            'sync_logs' => $sync_logs,
            'posts' => $posts,
            'blacklist' => $this->blacklist(),
            'nonce' => $nonce,
        ));

        // var_dump($options->all());
        // var_dump($this->blacklist());

        $post_types = $this->cpts( ['attachment'] );
        
        if( $active_tab == 'settings' ) {

            return Plugin::view('settings', compact('active_tab', 'updated', 'options', 'total_items_require_sync', 'post_types', 'get_pro'));

        }

        if ( $active_tab == 'logs' ) {

            return Plugin::view("logs", compact('active_tab'));

        }

        if ( $active_tab == 'faq' ) {

            return Plugin::view("faq", compact('active_tab'));

        }
    }

    public function get_total_pages_and_items(): array
    {
        global $wpdb;

        if ( $this->meta_key() === '') {
            return [
                'pages' => (int) 0,
                'items' => (int) 0
            ];
        }

        $exclude = $this->blacklist();

        if (!empty($exclude)) {
            $exclude = array_filter($exclude, 'is_numeric');
            $exclude_ids = array_map(function($id) {
                return (int) $id;
            }, $exclude);
            $exclude_ids_placeholder = implode(', ', array_fill(0, count($exclude_ids), '%d'));
            $exclude_condition = $wpdb->prepare("AND p.ID NOT IN ({$exclude_ids_placeholder})", ...$exclude_ids);
        } else {
            $exclude_condition = "";
        }

        $post_types = $this->post_types();
        $meta_key = $this->meta_key();

        $totalRows = $wpdb->get_var($wpdb->prepare("
            SELECT COUNT(*) 
            FROM {$wpdb->posts} p
            LEFT JOIN {$wpdb->postmeta} pm 
            ON p.ID = pm.post_id AND pm.meta_key = %s
            WHERE p.post_type IN ($post_types)
            AND p.post_status = 'publish'
            AND p.post_title != ''
            {$exclude_condition}
            AND (pm.meta_key IS NULL OR pm.meta_value = '')
        ", $meta_key));

        $totalPages = ceil($totalRows / $this->batch_size); // Calculate the total number of pages

        // Return total pages for batch processing and total number of rows to show whats need to synced
        return [
            'pages' => (int) $totalPages,
            'items' => (int) $totalRows
        ];
    }

    
}