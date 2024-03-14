<?php

if (!class_exists('Reon')) {
    return;
}

if ( !class_exists( 'WModes_Admin_Data_List_MetaBox_Variations' ) ) {

    class WModes_Admin_Data_List_MetaBox_Variations {

        public static function init() {
            add_filter('wmodes-admin/get-data-list-variations', array(new self(), 'get_data_list'), 10, 2);
        }

        public static function get_data_list($result, $data_args) {

            global $wpdb;

            try {

                $posts_per_page = 25;

                if (isset($args['items']) && is_array($args['items'])) {
                    $posts_per_page = count($args['items']);
                }


                $post_q = array(
                    'post_type' => 'product_variation',
                    'posts_per_page' => $posts_per_page,
                    'offset' => 0,
                    'category' => '',
                    'category_name' => '',
                    'orderby' => 'title',
                    'order' => 'ASC',
                    'include' => '',
                    'exclude' => '',
                    'meta_key' => '',
                    'meta_value' => '',
                    'post_mime_type' => '',
                    'post_parent' => $data_args['source'],
                    'author' => '',
                    'author_name' => '',
                    'post_status' => 'publish',
                    'suppress_filters' => true
                );

                if (isset($args['pagesize'])) {
                    $post_q['posts_per_page'] = $args['pagesize'];
                }

                if (isset($args['search_term'])) {
                    $sql = "SELECT ID FROM {$wpdb->posts} "
                            . "WHERE (post_status=%s) AND (post_type=%s) AND (post_title LIKE %s) ORDER BY post_title";

                    $sql = $wpdb->prepare($sql, 'publish', $args['post_type'], '%' . $args['search_term'] . '%');

                    $rows = $wpdb->get_results($sql, ARRAY_A);
                    if (is_array($rows)) {
                        $post_ids = array();
                        foreach ($rows as $row) {
                            $post_ids[] = $row['ID'];
                        }
                        $post_q['post__in'] = $post_ids;
                    }
                } else if (isset($args['items']) && is_array($args['items'])) {
                    $post_q['post__in'] = $args['items'];
                    $post_q['posts_per_page'] = count($args['items']);
                }



                $rn_posts = get_posts($post_q);

                foreach ($rn_posts as $rn_post) {

                    $vl = '';
                    $result[$rn_post->post_name] = $rn_post->post_title . $vl;
                }
            } catch (Exception $ex) {
                
            }




            return $result;
        }

    }

    WModes_Admin_Data_List_MetaBox_Variations::init();
}