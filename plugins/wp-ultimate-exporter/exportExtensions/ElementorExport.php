<?php
/******************************************************************************************
 * Copyright (C) Smackcoders. - All Rights Reserved under Smackcoders Proprietary License
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * You can contact Smackcoders at email address info@smackcoders.com.
 *******************************************************************************************/
 namespace Smackcoders\SMEXP;

 if ( ! defined( 'ABSPATH' ) )
 exit; // Exit if accessed directly

class ElementorExport {

    public function __construct() {
        $this->templateExport();
    }

    public function templateExport() {
        global $wpdb;
        $args = [
        'post_type' => 'elementor_library',
        'posts_per_page' => -1,
        ];
        $query = new \WP_Query($args);
        $templates = $query->get_posts();
        $output = [];
        $headers = ["ID", "Template title", "Template content", "Style" ,"Template type", "Created time",
        "Created by", "Template status","Category"];
        foreach ($templates as $template) {
        $styles = get_post_meta($template->ID, '_elementor_data', true);
        $styles_decode = json_decode($styles, true);
        $serialize_style = serialize($styles_decode);
        $encode_style = serialize($serialize_style);
        $encode_style = serialize($encode_style);
            $json_string = serialize([
                'content' => $template->post_content,
                ]);
                $json_strings = serialize($json_string);
            $author_data = get_userdata($template->post_author);
            $categories = get_the_terms($template->ID, 'elementor_library_category'); // Replace 'elementor_library_category' with the correct taxonomy name

                $category_names = [];
                if (!empty($categories)) {
                    foreach ($categories as $category) {
                        $category_names[] = $category->name;
                    }
                }

            $output[] = [
                'ID' => $template->ID,
                'Template title' => $template->post_title,
                'Template content' => $json_strings,
                'Style' => $encode_style,
                'Template type' => get_post_meta($template->ID,'_elementor_template_type', true),
                'Created time' => $template->post_date,
                'Created by' => $author_data->display_name,
                'Template status' => $template->post_status,
                'Category' => implode(', ', $category_names),
                ];
        }
        return $output;
    }
}