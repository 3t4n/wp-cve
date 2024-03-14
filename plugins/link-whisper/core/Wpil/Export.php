<?php

/**
 * Export controller
 */
class Wpil_Export
{

    private static $instance;

    /**
     * gets the instance via lazy initialization (created on first usage)
     */
    public static function getInstance()
    {
        if (null === self::$instance)
        {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Export data
     */
    function export($post)
    {
        // exit if this isn't the admin
        if(!is_admin()){
            return;
        }

        $data = self::getExportData($post);
        $data = json_encode($data, JSON_PRETTY_PRINT);
        $host = !empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';

        //create filename
        if ($post->type == 'term') {
            $term = get_term($post->id);
            $filename = $post->id . '-' . $host . '-' . $term->slug . '.json';
        } else {
            $post_slug = get_post_field('post_name', $post->id);
            $filename = $post->id . '-' . $host . '-' . $post_slug . '.json';
        }

        //download export file
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-type: application/json');
        echo $data;
        exit;
    }

    /**
     * Get post data, links and settings for export
     *
     * @param $post_id
     * @return array
     */
    public static function getExportData($post)
    {
        // detach any hooks known to cause problems in the loading
        Wpil_Base::remove_problem_hooks(true);

        $thrive_content = get_post_meta($post->id, 'tve_updated_post', true);
        $beaver_content = get_post_meta($post->id, '_fl_builder_data', true);
        $elementor_content = get_post_meta($post->id, '_elementor_data', true);
        $enfold_content = get_post_meta($post->id, '_aviaLayoutBuilderCleanData', true);
        $old_oxygen_content = get_post_meta($post->id, 'ct_builder_shortcodes', true);
        $new_oxygen_content = get_post_meta($post->id, 'ct_builder_json', true);

        set_transient('wpil_transients_enabled', 'true', 600);
        $transient_enabled = (!empty(get_transient('wpil_transients_enabled'))) ? true: false;

        //export settings
        $settings = [];
        foreach (Wpil_Settings::$keys as $key) {
            $settings[$key] = get_option($key, null);
        }
        $settings['ignore_words'] = get_option('wpil_2_ignore_words', null);

        $is_admin = current_user_can('activate_plugins');

        $res = [
            'v' => strip_tags(Wpil_Base::showVersion()),
            'created' => date('c'),
            'post_id' => $post->id,
            'type' => $post->type,
            'wp_post_type' => $post->getRealType(),
            'post_terms' => $post->getPostTerms(),
            'post_links_last_update' => ($post->type === 'post') ? get_post_meta($post->id, 'wpil_sync_report2_time', true): get_term_meta($post->id, 'wpil_sync_report2_time', true),
            'has_run_scan' => get_option('wpil_has_run_initial_scan'),
            'last_scan_run' => get_option('wpil_scan_last_run_time', 'Not Yet Activated'),
            'site_url' => get_site_url(),
            'home_url' => get_home_url(),
            'processable_post_count' => Wpil_Report::get_total_post_count(),
            'metafield_count' => Wpil_Toolbox::get_site_meta_row_count(),
            'total_database_posts' => self::get_database_post_count(),
            'url' => $post->getLinks()->view,
            'title' => $post->getTitle(),
            'content' => $post->getContent(false),
            'processed_content' => Wpil_Report::process_content($post->getContent(false), $post),
            'shortcode_processed' => do_shortcode($post->getContent(false)),
            'clean_content' => $post->getCleanContent(),
            'thrive_content' => $thrive_content,
            'beaver_content' => $beaver_content,
            'elementor_content' => $elementor_content,
            'enfold_content' => $enfold_content,
            'oxygen_shortcodes' => $old_oxygen_content,
            'oxygen_json' => $new_oxygen_content,
            'wp_theme' => ($is_admin) ? print_r(wp_get_theme(), true): 'User not an admin',
            'editor' => $post->editor,
            'transients_enabled' => $transient_enabled,
            'max_execution_time' => ($is_admin) ? ini_get('max_execution_time'): 'User not an admin',
            'max_input_time' => ($is_admin) ? ini_get('max_input_time'): 'User not an admin',
            'max_input_vars' => ($is_admin) ? ini_get('max_input_vars'): 'User not an admin',
            'upload_max_filesize' => ($is_admin) ? ini_get('upload_max_filesize'): 'User not an admin',
            'post_max_size' => ($is_admin) ? ini_get('post_max_size'): 'User not an admin',
            'memory_limit' => ($is_admin) ? ini_get('memory_limit'): 'User not an admin',
            'memory_breakpoint' => Wpil_Report::get_mem_break_point(),
            'php_version' => ($is_admin) ? phpversion(): 'User not an admin',
            'mb_string_active' => extension_loaded('mbstring'),
            'curl_active' => ($is_admin) ? function_exists('curl_init'): 'User not an admin',
            'curl_version' => ($is_admin) ? ((function_exists('curl_version')) ? curl_version(): false): 'User not an admin',
            'relevent_wp_constants' => ($is_admin) ? Wpil_Settings::get_wp_constants(): 'User not an admin',
            'using_custom_htaccess' => ($is_admin) ? Wpil_Toolbox::is_using_custom_htaccess(): 'User not an admin',
            'ACF_active' => class_exists('ACF'),
            'table_statuses' => self::get_table_data(),
            'active_plugins' => ($is_admin) ? get_option('active_plugins', array()): 'User not an admin',
            'settings' => $settings
        ];

        // if we're including meta in the export or ACF is active
        if(!empty(get_option('wpil_include_post_meta_in_support_export')) || class_exists('ACF')){
            $res['post_meta'] = ($post->type === 'post') ? get_post_meta($post->id, '', true) : get_term_meta($post->id, '', true);
        }

        // add reporting data to export
        $keys = [
            WPIL_LINKS_OUTBOUND_INTERNAL_COUNT,
            WPIL_LINKS_INBOUND_INTERNAL_COUNT,
            WPIL_LINKS_OUTBOUND_EXTERNAL_COUNT,
        ];

        $report = [];
        foreach($keys as $key) {
            if ($post->type == 'term') {
                $report[$key] = get_term_meta($post->id, $key, true);
                $report[$key.'_data'] = Wpil_Toolbox::get_encoded_term_meta($post->id, $key.'_data', true);
            } else {
                $report[$key] = get_post_meta($post->id, $key, true);
                $report[$key.'_data'] = Wpil_Toolbox::get_encoded_post_meta($post->id, $key.'_data', true);
            }
        }

        if ($post->type == 'term') {
            $report['wpil_sync_report3'] = get_term_meta($post->id, 'wpil_sync_report3', true);
        } else {
            $report['wpil_sync_report3'] = get_post_meta($post->id, 'wpil_sync_report3', true);
        }

        $res['report'] = $report;
        $res['phrases'] = Wpil_Suggestion::getPostSuggestions($post, null, true, null, null, rand(0, time()));
        $res['site_plugins'] = ($is_admin) ? get_plugins(): 'User not an admin';

        return $res;
    }

    public static function get_table_data(){
        global $wpdb;
        // create a list of all possible tables
        $tables = Wpil_Base::getDatabaseTableList();

        // set up the list for the table data
        $table_results = array();

        $create_table = "Create Table";

        // go over the list of tables
        foreach($tables as $table){
            $table_exists = $wpdb->get_var("SHOW TABLES LIKE '{$table}'");
            if($table_exists === $table){
                $results = $wpdb->get_results("SHOW CREATE TABLE {$table}");
                if(!empty($results) && isset($results[0]) && isset($results[0]->Table) && isset($results[0]->$create_table)){
                    $results = array(
                        "Table" => str_ireplace($wpdb->prefix, 'PREFIX_', $results[0]->Table),
                        "Create Table" => str_ireplace($wpdb->prefix, 'PREFIX_', $results[0]->$create_table)
                    );
                }

                $table_results[] = $results;
            }else{
                $table_results[] = 'The "' . str_ireplace($wpdb->prefix, 'PREFIX_', $table) . '" table doesn\'t exist';
            }
        }

        return $table_results;
    }

    /**
     * Counts how many posts are in the posts table.
     **/
    public static function get_database_post_count(){
        global $wpdb;

        $count = $wpdb->get_var("SELECT COUNT(ID) FROM {$wpdb->posts}");
        return !empty($count) ? (int)$count: 0;
    }

    /**
     * Export table data to CSV
     */
    public static function ajax_csv()
    {
        // be sure to ignore any external object caches
        Wpil_Base::ignore_external_object_cache();

        // Remove any hooks that may interfere with AJAX requests
        Wpil_Base::remove_problem_hooks();

        $type = !empty($_POST['type']) ? $_POST['type'] : null;
        $count = !empty($_POST['count']) ? $_POST['count'] : null;
        $id = !empty($_POST['id']) ? (int) $_POST['id']: 0;
        $capability = apply_filters('wpil_filter_main_permission_check', 'manage_categories');

        if (!$type || !$count || !current_user_can($capability)) {
            wp_send_json([
                    'error' => [
                    'title' => __('Request Error', 'wpil'),
                    'text'  => __('Bad request. Please try again later', 'wpil')
                ]
            ]);
        }

        // get the directory that we'll be writing the export to
        $dir = false;
        $dir_url = false;
        if(is_writable(WP_INTERNAL_LINKING_PLUGIN_DIR)){
            // if it's possible, write to the plugin directory
            $dir = WP_INTERNAL_LINKING_PLUGIN_DIR . 'includes/exports/';
            $dir_url = WP_INTERNAL_LINKING_PLUGIN_URL . 'includes/exports/';
        }else{
            // if writing to the plugin directory isn't possible, try for the uploads folder
            $uploads = wp_upload_dir(null, false);
            if(!empty($uploads) && isset($uploads['basedir']) && is_writable($uploads['basedir'])){
                if(wp_mkdir_p(trailingslashit($uploads['basedir']). 'link-whisper-premium/exports')){
                    $dir = trailingslashit($uploads['basedir']). 'link-whisper-premium/exports/';
                    $dir_url = trailingslashit($uploads['baseurl']). 'link-whisper-premium/exports/';
                }
            }
        }

        // if we aren't able to write to any directories
        if(empty($dir)){
            // tell the user about it
            wp_send_json([
                'error' => [
                    'title' => __('File Permission Error', 'wpil'),
                    'text'  => __('The uploads folder isn\'t writable by Link Whisper. Please contact your host or webmaster about making the "/uploads/link-whisper-premium/" folder writable.', 'wpil') // we're defaulting to the uploads folder here since it's the easiest one to support
                ]
            ]);
        }

        // create the file name that we'll be working with
        $filename = $type . '_' . $id . '_export.csv';

        if ($count == 1) {
            // if this is the first go round, clear any old exports
            $files = glob($dir . '*_export.csv');
            if(!empty($files)){
                foreach($files as $file){
                    unlink($file);
                }
            }

            $fp = fopen($dir . $filename, 'w');
            switch ($type) {
                case 'links':
                    $header = array(
                        'Title',
                        'Type',
                        'Category',
                        'Tags',
                        'Published',
                    );

                    // normally GCS headers go here
                    // hanging onto space for format similarities

                    $header = array_merge($header, array(
                        'Source Page URL - (The page we are linking from)',
                        'Inbound Link Page Source URL',
                        'Inbound Link Anchor',
                        'Outbound Internal Link URL',
                        'Outbound Internal Link Anchor',
                        'Outbound External Link URL',
                        "Outbound External Link Anchor\n",
                    ));

                    $header = implode(',', $header);

                    break;
                case 'links_summary':
                    $header = "Title,URL,Type,Category,Tags,Published,Inbound internal links,Outbound internal links,Outbound external links\n";
                    break;
                case 'domains':
                    $header = "Domain,Post URL,Anchor Text,Anchor URL,Post Edit Link\n";
                    break;
                case 'domains_summary':
                    $header = "Domain,Post Count,Link Count\n";
                    break;
                case 'error':
                    $header = "Post,Broken URL,Type,Status,Discovered\n";
                    break;
            }
            fwrite($fp, $header);
        } else {
            $fp = fopen($dir . $filename, 'a');
        }

        //get data
        $data = '';
        $func = 'csv_' . $type;
        if (method_exists('Wpil_export', $func)) {
            $data = self::$func($count);
        }

        //send finish response
        if (empty($data)) {
            header('Content-type: text/csv');
            header('Content-disposition: attachment; filename=' . $filename);
            header('Pragma: no-cache');
            header('Expires: 0');

            wp_send_json([
                'fileExists' => file_exists($dir . $filename),
                'filename' => $dir_url . $filename
            ]);
        }

        //write to file
        fwrite($fp, $data);

        wp_send_json([
            'filename' => '',
            'type' => $type,
            'count' => $count
        ]);

        die;
    }

    /**
     * Prepare links data for export
     *
     * @return string
     */
    public static function csv_links($count)
    {
        $links = Wpil_Report::getData($count, '', 'ASC', '', 500);
        $redirected_posts = Wpil_Settings::getRedirectedPosts();
        $data = '';
        $post_url_cache = array();
        foreach ($links['data'] as $link) {
            // if the post is a 'post' and it's been redirected away from
            if($link['post']->type === 'post' && !empty($redirected_posts) && in_array($link['post']->id, $redirected_posts)){
                // skip it
                continue;
            }

            if (!empty($link['post']->getTitle())) {
                $inbound_internal  = $link['post']->getInboundInternalLinks();
                $outbound_internal = $link['post']->getOutboundInternalLinks();
                $outbound_external = $link['post']->getOutboundExternalLinks();

                $limit = max(count($inbound_internal), count($outbound_internal), count($outbound_external), 1); // throw in 1 just in case there's no links so we're sure to go around once

                for ($i = 0; $i < $limit; $i++) {
                    $post = $link['post'];
                    $cats = array();
                    foreach($post->getPostTerms(array('hierarchical' => true)) as $term){
                        $cats[] = $term->name;
                    }
                    $category = (!empty($cats)) ? '"' . addslashes(implode(', ', $cats)) . '"' : '';
    
                    // get any terms
                    $tags = array();
                    foreach($post->getPostTerms(array('hierarchical' => false)) as $term){
                        $tags[] = $term->name;
                    }
                    $tag = (!empty($tags)) ? '"' . addslashes(implode(', ', $tags)) . '"' : '';

                    $inbound_post_source_url = '';
                    if(!empty($inbound_internal[$i])){
                        $inbnd_id = $inbound_internal[$i]->post->id;
                        if(!isset($post_url_cache[$inbnd_id])){
                            $post_url_cache[$inbnd_id] = wp_make_link_relative($inbound_internal[$i]->post->getLinks()->view);
                        }
                        $inbound_post_source_url = $post_url_cache[$inbnd_id];
                    }

                    $item = [
                        !$i ? '"' . mb_convert_encoding(addslashes($post->getTitle()), 'UTF-8') . '"' : '',
                        !$i ? $post->getType() : '',
                        !$i ? '"' . $link['date'] . '"' : '',
                        wp_make_link_relative($post->getLinks()->view),
                        $inbound_post_source_url,
                        !empty($inbound_internal[$i]) ? '"' . addslashes(substr(trim(strip_tags($inbound_internal[$i]->anchor)), 0, 100)) . '"' : '',
                        !empty($outbound_internal[$i]) ? $outbound_internal[$i]->url : '',
                        !empty($outbound_internal[$i]) ? '"' . addslashes(substr(trim(strip_tags($outbound_internal[$i]->anchor)), 0, 100)) . '"' : '',
                        !empty($outbound_external[$i]) ? $outbound_external[$i]->url : '',
                        !empty($outbound_external[$i]) ? '"' . addslashes(substr(trim(strip_tags($outbound_external[$i]->anchor)), 0, 100)) . '"' : '',
                    ];

                    $data .= $item[0] . "," . $item[1] . "," . $category . "," . $tag . "," . $item[2] . "," . $item[3] . "," . $item[4] . "," . $item[5] .  "," . $item[6] . "," . $item[7] . "," . $item[8] . "," . $item[9] . "\n";
                }
            }
        }

        return $data;
    }

    public static function csv_links_summary($count)
    {
        $links = Wpil_Report::getData($count, '', 'ASC', '', 500);
        $redirected_posts = Wpil_Settings::getRedirectedPosts();
        $data = '';
        foreach ($links['data'] as $link) {
            // if the post is a 'post' and it's been redirected away from
            if($link['post']->type === 'post' && !empty($redirected_posts) && in_array($link['post']->id, $redirected_posts)){
                // skip it
                continue;
            }

            if (!empty($link['post']->getTitle())) {
                //prepare data
                $post = $link['post'];
                $title = '"' . mb_convert_encoding(addslashes($post->getTitle()), 'UTF-8') . '"';
                $url = wp_make_link_relative($post->getLinks()->view);
                $type = $post->getType();
                // get the post's categories
                $cats = array();
                foreach($post->getPostTerms(array('hierarchical' => true)) as $term){
                    $cats[] = $term->name;
                }
                $category = (!empty($cats)) ? '"' . addslashes(implode(', ', $cats)) . '"' : '';

                // get any terms
                $tags = array();
                foreach($post->getPostTerms(array('hierarchical' => false)) as $term){
                    $tags[] = $term->name;
                }
                $tag = (!empty($tags)) ? '"' . addslashes(implode(', ', $tags)) . '"' : '';

                $date = '"' . $link['date'] . '"';
                $ii_count = $post->getInboundInternalLinks(true);
                $oi_count = $post->getOutboundInternalLinks(true);
                $oe_count = $post->getOutboundExternalLinks(true);
                $data .= $title . "," . $url . "," . $type . "," . $category . "," . $tag . "," . $date . "," . $ii_count . "," . $oi_count . "," . $oe_count . "\n";
            }
        }

        return $data;
    }
}
