<?php
if (!defined('ABSPATH'))
{
	exit;
}

// shortocode [postdirectory catid='38']
// shortocode [postdirectory limit=2]
// shortocode [postdirectory catname='directory']


function post_table_shortcode($atts)
{
    global $table_prefix, $wpdb, $post;

    $args = array('post_type' => 'post', 'post_status' => 'publish');

    $postarray = array();
    $m_single = array();

    $return_content = '';
    $return_content .= '<div class="tooltips_directory">';

    $limit_sql = '';
    if (isset($atts['limit'])) {
        $limit_number = sanitize_text_field($atts['limit']);
        $limit_sql = " limit %d ";
        $limit_sql = $wpdb->prepare($limit_sql, $limit_number);
    }

    $post_type = 'post';
    $user_args_catid_query = '';

    $user_args_catid = '';

	if ((isset($atts)) && (is_array($atts)) && (count($atts) > 0) && ((isset($atts['catid'])) || (isset($atts['catname']))))
	{
        if (isset($atts['catid'])) {
            $user_args_catid = sanitize_text_field($atts['catid']);
            $user_args_catid_array = explode(",", trim($user_args_catid));

            if ((!(empty($user_args_catid_array))) && (is_array($user_args_catid_array)) && (count($user_args_catid_array) > 0)) {
                $user_args_catid_array = array_filter($user_args_catid_array);
            }

            if ((is_array($user_args_catid_array)) && (count($user_args_catid_array) > 0)) {
                $user_args_catid_query = implode(',', $user_args_catid_array);

                $sql = $wpdb->prepare("
                    SELECT ID, post_title, post_content, post_excerpt
                    FROM $wpdb->posts wposts
                    LEFT JOIN $wpdb->term_relationships ON (wposts.ID = $wpdb->term_relationships.object_id)
                    LEFT JOIN $wpdb->term_taxonomy ON ($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id)
                    WHERE wposts.post_type = 'post'
                        AND post_status='publish'
                        AND $wpdb->term_taxonomy.taxonomy = 'category'
                        AND $wpdb->term_taxonomy.term_id IN(%s)
                    ORDER BY wposts.post_title ASC $limit_sql 
                ", $user_args_catid_query );
            }
        }

        if (isset($atts['catname'])) {
            $user_args_catid = sanitize_text_field($atts['catname']);
            $user_args_catid_array = explode(",", trim($user_args_catid));

            if ((!(empty($user_args_catid_array))) && (is_array($user_args_catid_array)) && (count($user_args_catid_array) > 0)) {
                $user_args_catid_array = array_filter($user_args_catid_array);
            }

            if ((is_array($user_args_catid_array)) && (count($user_args_catid_array) > 0)) {
                $user_args_catid_query = implode("','", $user_args_catid_array);
                $sql = $wpdb->prepare("
                    SELECT ID, post_title, post_content, post_excerpt
                    FROM $wpdb->posts wposts
                    LEFT JOIN $wpdb->term_relationships ON (wposts.ID = $wpdb->term_relationships.object_id)
                    LEFT JOIN $wpdb->term_taxonomy ON ($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id)
                    LEFT JOIN $wpdb->terms ON($wpdb->term_taxonomy.term_id = $wpdb->terms.term_id)
                    WHERE wposts.post_type = 'post'
                        AND post_status='publish'
                        AND $wpdb->term_taxonomy.taxonomy = 'category'
                        AND $wpdb->terms.name IN('%s')
                    ORDER BY wposts.post_title ASC $limit_sql 
                ", $user_args_catid_query );
            }
        }
    } 
	else 
	{

		$sql = $wpdb->prepare("
			SELECT ID, post_title, post_content, post_excerpt FROM $wpdb->posts WHERE post_type=%s AND post_status='publish' order by post_title ASC $limit_sql 
		", $post_type );
    }


    $results = $wpdb->get_results($sql);

    $show_glossary_page_current_result = $results;

    $return_content .= '<div class="tooltips_list_start">';

    if ((!(empty($show_glossary_page_current_result))) && (is_array($show_glossary_page_current_result)) && (count($show_glossary_page_current_result) > 0)) {
        $m_single = array();
        foreach ($show_glossary_page_current_result as $single) {
            if ($post->ID == $single->ID) {
                continue;
            }

            if (empty($single->post_title)) {
                continue;
            }

            $return_content .= '<div class="tooltips_list">';
            $return_content .= '<span class="tooltips_table_items">';
            $return_content .= '<div class="tooltips_table">';
            $return_content .= '<div class="tooltips_table_title">';
            $enabGlossaryIndexPage = get_option("enabGlossaryIndexPage");
            if (empty($enabGlossaryIndexPage)) {
                $enabGlossaryIndexPage = 'YES';
            }

            if ($enabGlossaryIndexPage == 'YES') {
                $return_content .= '<a href="' . esc_url(get_permalink($single->ID)) . '">' . $single->post_title . '</a>';
            } else {
                $return_content .= $single->post_title;
            }


            $return_content .= '</div>';
            $return_content .= '<div class="tooltips_table_content">';

            $glossaryExcerptOrContentSelect = get_option("glossaryExcerptOrContentSelect");

            if ($glossaryExcerptOrContentSelect == 'glossaryexcerpt') {
                $m_content = $single->post_excerpt;
            }

            if ($glossaryExcerptOrContentSelect == 'glossarycontent') {

                $m_content = $single->post_content;
            }

            if (empty($glossaryExcerptOrContentSelect)) {

                $m_content = $single->post_content;
            }

            $return_content .= $m_content;
            $return_content .= '</div>';
            $return_content .= '</div>';
            $return_content .= '</span>';
            $return_content .= '</div>';
        }
    }
    $return_content .= '</div>';

    $return_content .= '</div>';
    $css_content = '';
    $return_content = $css_content . $return_content;

    return $return_content;
}

add_shortcode('posttable', 'post_table_shortcode', 10);
add_shortcode('postdirectory', 'post_table_shortcode', 10);

