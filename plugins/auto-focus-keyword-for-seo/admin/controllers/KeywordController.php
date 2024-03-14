<?php
namespace Pagup\AutoFocusKeyword\Controllers;

use Pagup\AutoFocusKeyword\Traits\Helpers;

class KeywordController
{
	use Helpers;
	
	/**
     * Ajax request to bulk fetch items (sync request)
    */
    public function bulk_fetch() {

        global $wpdb;
    
        if ( check_ajax_referer( 'autokeywords', 'nonce', false ) == false ) {
            wp_send_json_error( "Invalid nonce", 401 );
            wp_die();
        }

        if (!current_user_can('manage_options')) {
            wp_send_json_error( "Unauthorized user", 403 );
            wp_die();
        }
    
        $page = intval($_POST['page']);
        $totalPages = intval($_POST['totalPages']);
        $batchSize = intval($_POST['batchSize']); // Set the batch size
        $offset = intval($_POST['offset']); // Calculate the offset
    
        if ( $this->meta_key() === '') {
            return;
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
    
        $query = $wpdb->prepare("
            SELECT ID, post_title
            FROM {$wpdb->posts} p
            LEFT JOIN {$wpdb->postmeta} pm 
            ON p.ID = pm.post_id AND pm.meta_key = %s
            WHERE p.post_type IN ($post_types)
            AND p.post_status = 'publish'
            AND (pm.meta_key IS NULL OR pm.meta_value = '')
            AND p.post_title != ''
            {$exclude_condition}
            ORDER BY pm.meta_id ASC
            LIMIT %d OFFSET %d
        ", $this->meta_key(), $batchSize, $offset);
    
        $posts = $wpdb->get_results($query);
    
        if (empty($posts)) {
            return;
        }
    
        $progress = ($page / $totalPages) * 100; // Calculate the progress percentage
    
        wp_send_json_success([
            'posts' => $posts,
            'progress' => $progress
        ]);
    
        wp_die();
    
    }
        
    /**
     * Ajax request to bulk add items to post meta
    */

    public function bulk_add() {

        // check the nonce
        if ( check_ajax_referer( 'autokeywords', 'nonce', false ) == false ) {
            wp_send_json_error( "Invalid nonce", 401 );
            wp_die();
        }

        if (!current_user_can('manage_options')) {
            wp_send_json_error( "Unauthorized user", 403 );
            wp_die();
        }
    
        $post_id = sanitize_text_field($_POST['post_id']);
        $post_title = sanitize_text_field($_POST['post_title']);
    
        if ( $this->meta_key() === '') {
            wp_send_json_error("It seems Yoast SEO or Rank Math SEO plugin is not activated");
            wp_die();
        }
    
        $result = add_post_meta($post_id, $this->meta_key(), $post_title, true);
    
        if ($result) {
            // Get the existing options array
            $options = get_option('afkw_autokeyword_logs');

            // Check if the options array is empty
            if (empty($options)) {
                // Initialize options as an empty array
                $options = array();
            }
    
    
            $current_time = current_time('timestamp');
    
            // Search for the index of the post in the options array
            $index = array_search($post_id, array_column($options, 'post_id'));

            if ($index !== false) {
                // Update the existing post title and updated_at timestamp
                $options[$index]['post_title'] = $post_title;
                $options[$index]['updated_at'] = $current_time;
            } else {
                // Add the new post ID, title, and timestamps to the array
                $options[] = array(
                    'post_id' => $post_id,
                    'post_title' => $post_title,
                    'created_at' => $current_time,
                    'updated_at' => $current_time
                );
            }
    
            // Save the updated options array
            update_option('afkw_autokeyword_logs', $options);
    
            wp_send_json_success($post_title . " successfully added as focus keyword.");
            } else {
                wp_send_json_error($post_title . " failed to add as focus keyword.");
            }
    
        wp_die();
    }

    /**
     * Ajax request to delete item (manual internal links)
    */
    public function delete_item() {
        // check the nonce
        if (check_ajax_referer('autokeywords', 'nonce', false) == false) {
            wp_send_json_error("Invalid nonce", 401);
            wp_die();
        }

        if (!current_user_can('manage_options')) {
            wp_send_json_error( "Unauthorized user", 403 );
            wp_die();
        }
    
        $id = sanitize_text_field(intval($_POST['id']));
    
        if (isset($id) && !empty($id)) {
            $options = get_option('afkw_autokeyword_logs');
    
            // Check if the post ID exists in the array
            $index = array_search($id, array_column($options, 'post_id'));
    
            if ($index !== false) {
                // Remove the item from the array
                unset($options[$index]);
    
                // Reset array keys
                $options = array_values($options);
    
                // Update the option
                update_option('afkw_autokeyword_logs', $options);

                // Check supported SEO plugin is installed and activated
                if ( $this->meta_key() === '') {
                    wp_send_json_error("It seems Yoast SEO or Rank Math SEO plugin is not activated");
                } else {

                    // Get the post title
                    $post_title = get_the_title($id);
                    $post_title = html_entity_decode($post_title, ENT_QUOTES | ENT_HTML5, 'UTF-8');

                    // Get the focus keyword value from post meta
                    $focus_keyword = get_post_meta($id, $this->meta_key(), true);

                    // Compare the focus keyword with the post title
                    if ($focus_keyword === $post_title) {
                        // Remove the focus keyword value from post meta
                        delete_post_meta($id, $this->meta_key());
                    }
                }
                
                // Send a success response
                wp_send_json_success([
                    'id' => $id,
                    'message' => "Item with ID {$id} has been successfully deleted.",
                    'focus_keyword' => $focus_keyword,
                    'post_title' => $post_title
                ]);
            } else {
                // Send an error response if the item was not found
                wp_send_json_error("Item with ID {$id} not found.");
            }
        }
    
        wp_die();
    }

    /**
     * Ajax request to update Sync Date option & Delete transient cache
    */
    public function sync_date()
    {
        if ( check_ajax_referer( 'autokeywords', 'nonce', false ) == false ) {
            wp_send_json_error( "Invalid nonce", 401 );
            wp_die();
        }

        if (!current_user_can('manage_options')) {
            wp_send_json_error( "Unauthorized user", 403 );
            wp_die();
        }

        if (isset($_POST['alldone']) && !empty($_POST['alldone'])) {
            $date = current_time('F d, Y h:i:sa');
            update_option("afkw_autokeyword_sync", $date);
        }

        wp_die();
    }

}