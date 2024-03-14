<?php

/**
 * 
 * @decription List of post-type Page, Post and Custom Post Type (CPT) from admin side.
 * 
 */
function wshs_get_posts_by_type() {

    if (!current_user_can( 'manage_options' ) ) {
        return wp_send_json( array( 'result' => 'Authentication error' ) );
    }

    check_ajax_referer('ajax-nonce', 'security');

    $type = sanitize_text_field($_POST['type']);
    $orderby = sanitize_text_field($_POST['orderby']);
    $order = sanitize_text_field($_POST['order']);
    $dateformate = sanitize_text_field($_POST['dateformate']);
    $taxonomyname = sanitize_text_field($_POST['taxonomyslug']);
    $termsname = sanitize_text_field($_POST['termsslug']);

    if (!post_type_exists($type)) {
        wp_send_json_error('Invalid post type');
    }

    $type = $GLOBALS['wpdb']->prepare('%s', $type);

    if ($termsname != '') {
        /* Taxonomy name & Terms name */
        $taxquery = array(
            array(
                'taxonomy' => $taxonomyname,
                'field' => 'slug',
                'terms' => $termsname,
            ),
        );
    } else {
        /* Taxonomy name */
        $customtaxonomy = get_terms($taxonomyname);
        foreach ($customtaxonomy as $customtaxonomy) {
            $taxonomyslug = $customtaxonomy->slug;
            $taxonomyarray[] = $taxonomyslug;
        }
        $taxonomyslugarray = implode(', ', $taxonomyarray);

        if ($taxonomyname != '') {
            $taxquery = array(
                array(
                    'taxonomy' => $taxonomyname,
                    'field' => 'slug',
                    'terms' => explode(', ', $taxonomyslugarray),
                ),
            );
        }
    }

    $args = array(
        'post_type' => $type,
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => $orderby,
        'order' => $order,
        'tax_query' => $taxquery,
        'ignore_custom_sort' => true,
    );
    $query = new WP_Query($args);

    $typeallposts = array();
    if ($query->post_count > 0) {
        foreach ($query->posts as $typepost):
            $featureimg = wp_get_attachment_image_src(get_post_thumbnail_id($typepost->ID), 'full');
            $exp = get_the_excerpt($typepost->ID);
            $contentpost = $typepost->post_content;
            $typeallposts[] = array(
                'title' => esc_html($typepost->post_title),
                'ID' => $typepost->ID,
                'post_parent' => $typepost->post_parent,
                'post_date' => date_i18n($dateformate, strtotime($typepost->post_date)),
                'post_excerpt' => esc_html(wshs_truncate_value(strip_tags($exp), 100, ' ')),
                'post_content' => esc_html(wshs_truncate_value(strip_tags(preg_replace('#\[[^\]]+\]#', '', $contentpost)), 100, ' ')),
                'post_image' => esc_url($featureimg[0]),
            );
        endforeach;
    }
    $typeallposts = wshs_build_tree($typeallposts);
    wp_send_json($typeallposts);
}

add_action('wp_ajax_wshs_get_posts_by_type', 'wshs_get_posts_by_type');
add_action('wp_ajax_nopriv_wshs_get_posts_by_type', 'wshs_get_posts_by_type');

/**
 * 
 * @description List of post-type Taxonomy.
 */
function wshs_get_posts_by_taxonomy() {
    if (!current_user_can( 'manage_options' ) ) {
        return wp_send_json( array( 'result' => 'Authentication error' ) );
    }

    check_ajax_referer('ajax-nonce', 'security');

    $type = sanitize_text_field($_POST['type']);

    $taxonomies = get_object_taxonomies($type, 'object');
    // if (!post_type_exists($taxonomies)) {
    //     wp_send_json_error('Invalid post type');
    // }

    $data['data'] .= '<option value="">Select Taxonomy</option>';
    foreach ($taxonomies as $taxonomy) {
        if ($taxonomy->name != 'post_tag' && $taxonomy->name != 'post_format') {
            $data['data'] .= '<option value="' . esc_attr($taxonomy->name) . '" class="texonomyname">' . esc_html($taxonomy->label) . '</option>';
        }
    }
    wp_send_json($data);
}

add_action('wp_ajax_wshs_get_posts_by_taxonomy', 'wshs_get_posts_by_taxonomy');
add_action('wp_ajax_nopriv_wshs_get_posts_by_taxonomy', 'wshs_get_posts_by_taxonomy');

/**
 * 
 * @description Particular Taxonomy post list.
 * 
 */
function wshs_get_posts_by_taxonomy_post() {
    global $post;
    if (!current_user_can( 'manage_options' ) ) {
        return wp_send_json( array( 'result' => 'Authentication error' ) );
    }

    check_ajax_referer('ajax-nonce', 'security');

    $type = sanitize_text_field($_POST['type']);
    $catslug = sanitize_text_field($_POST['catslug']);
    $dateformate = sanitize_text_field($_POST['dateformate']);
    $orderby = sanitize_text_field($_POST['orderby']);

    if (!taxonomy_exists($catslug)) {
        wp_send_json_error('Invalid post type');
    }

    $customtaxonomy = get_terms($catslug);
    foreach ($customtaxonomy as $customtaxonomy) {
        $taxonomyslug = $customtaxonomy->slug;
        $taxonomyarray[] = $taxonomyslug;
    }
    $taxonomyslugarray = implode(', ', $taxonomyarray);

    if ($catslug != '') {
        $taxquery = array(
            array(
                'taxonomy' => $catslug,
                'field' => 'slug',
                'terms' => explode(', ', $taxonomyslugarray),
            ),
        );
    }

    $args = array
        (
        'post_type' => $type,
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'orderby' => $orderby,
        'order' => "ASC",
        'ignore_custom_sort' => true,
        'tax_query' => $taxquery,
    );

    $loop = new WP_Query($args);
    $typeallposts = array();

    while ($loop->have_posts()) : $loop->the_post();

        $featureimg = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
        $exp = get_the_excerpt($post->ID);
        $contentpost = $post->post_content;
        $typeallposts[] = array
            (
            'title' => esc_html($post->post_title),
            'ID' => $post->ID,
            'post_parent' => $post->post_parent,
            'post_date' => date_i18n($dateformate, strtotime($post->post_date)),
            'post_excerpt' => esc_html(wshs_truncate_value(strip_tags($exp), 100, ' ')),
            'post_content' => esc_html(wshs_truncate_value(strip_tags(preg_replace('#\[[^\]]+\]#', '', $contentpost)), 100, ' ')),
            'post_image' => esc_url($featureimg[0]),
        );
    endwhile;

    $typeallposts = wshs_build_tree($typeallposts);
    wp_send_json($typeallposts);
}

add_action('wp_ajax_wshs_get_posts_by_taxonomy_post', 'wshs_get_posts_by_taxonomy_post');
add_action('wp_ajax_nopriv_wshs_get_posts_by_taxonomy_post', 'wshs_get_posts_by_taxonomy_post');

/**
 * 
 * @description List of post-type Taxonomy Terms.
 */
function wshs_get_posts_by_taxonomy_terms() {
    if (!current_user_can( 'manage_options' ) ) {
        return wp_send_json( array( 'result' => 'Authentication error' ) );
    }

    check_ajax_referer('ajax-nonce', 'security');

    $taxonomyname = esc_html($_POST['taxonomyname']);

    // if (!taxonomy_exists($taxonomyname)) {
    //     wp_send_json_error('Invalid taxonomy');
    // }

    $custom_terms = get_terms($taxonomyname);
    $data['data'] .= '<option value="">Select Taxonomy Terms</option>';
    if ($taxonomyname != '') {
        foreach ($custom_terms as $taxonomy) {
            $data['data'] .= '<option value="' . esc_attr($taxonomy->slug) . '">' . esc_html($taxonomy->name) . '</option>';
        }
    }
    wp_send_json($data);
}

add_action('wp_ajax_wshs_get_posts_by_taxonomy_terms', 'wshs_get_posts_by_taxonomy_terms');
add_action('wp_ajax_nopriv_wshs_get_posts_by_taxonomy_terms', 'wshs_get_posts_by_taxonomy_terms');

/**
 * 
 * @description Particular Taxonomy Terms post list.
 * 
 */
function wshs_get_posts_by_taxonomy_terms_posts() {
    global $post;
    if (!current_user_can( 'manage_options' ) ) {
        return wp_send_json( array( 'result' => 'Authentication error' ) );
    }

    check_ajax_referer('ajax-nonce', 'security');

    $type = sanitize_text_field($_POST['type']);
    $taxonomyname = sanitize_text_field($_POST['taxonomyslug']);
    $termsname = sanitize_text_field($_POST['termsslug']);
    $dateformate = sanitize_text_field($_POST['dateformate']);
    $orderby = sanitize_text_field($_POST['orderby']);
    $order = sanitize_text_field($_POST['order']);

    // if (!taxonomy_exists($taxonomyname)) {
    //     wp_send_json_error('Invalid taxonomy');
    // }

    if ($termsname != '') {
        /* Taxonomy name & Terms name */
        $taxquery = array(
            array(
                'taxonomy' => $taxonomyname,
                'field' => 'slug',
                'terms' => $termsname,
            ),
        );
    } else {
        /* Taxonomy name */
        $customtaxonomy = get_terms($taxonomyname);
        foreach ($customtaxonomy as $customtaxonomy) {
            $taxonomyslug = $customtaxonomy->slug;
            $taxonomyarray[] = $taxonomyslug;
        }
        $taxonomyslugarray = implode(', ', $taxonomyarray);

        if ($taxonomyname != '') {
            /* Taxonomy name */
            $taxquery = array(
                array(
                    'taxonomy' => $taxonomyname,
                    'field' => 'slug',
                    'terms' => explode(', ', $taxonomyslugarray),
                ),
            );
        }
    }

    $args = array(
        'post_type' => $type,
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'orderby' => esc_html($orderby),
        'order' => esc_html($order),
        'ignore_custom_sort' => true,
        'tax_query' => $taxquery,
    );
    $loop = new WP_Query($args);
    $typeallposts = array();
    
    while ($loop->have_posts()) : $loop->the_post();
    
    $featureimg = wp_get_attachment_image_src(get_post_thumbnail_id($typepost->ID), 'full');
    $exp = get_the_excerpt($post->ID);
    $contentpost = $post->post_content;
    $typeallposts[] = array(
        'title' => esc_html($post->post_title),
        'ID' => $post->ID,
        'post_parent' => $post->post_parent,
        'post_date' => date_i18n($dateformate, strtotime($post->post_date)),
        'post_excerpt' => esc_html(wshs_truncate_value(strip_tags($exp), 100, ' ')),
        'post_content' => esc_html(wshs_truncate_value(strip_tags(preg_replace('#\[[^\]]+\]#', '', $contentpost)), 100, ' ')),
        'post_image' => esc_url($featureimg[0]),
    );
endwhile;

// $typeallposts = wshs_build_tree($typeallposts);
    wp_send_json($typeallposts);
}

add_action('wp_ajax_wshs_get_posts_by_taxonomy_terms_posts', 'wshs_get_posts_by_taxonomy_terms_posts');
add_action('wp_ajax_nopriv_wshs_get_posts_by_taxonomy_terms_posts', 'wshs_get_posts_by_taxonomy_terms_posts');

/**
 * 
 * @param array $elements
 * @param int $parentid
 * @return array
 */
function wshs_build_tree(array $elements, $parentid = 0) {

    if (!is_array($elements) || !is_int($parentid)) {
        return array();
    }

    $branch = array();
    foreach ($elements as $element) {
        if (!is_object($element)) {
            if ($element['post_parent'] == $parentid) {
                $children = wshs_build_tree($elements, $element['ID']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
            }
        } else {
            if ($element->post_parent == $parentid) {
                $children = wshs_build_tree($elements, $element->ID);
                if ($children) {
                    $element->children = $children;
                }
                $branch[] = (object) $element;
            }
        }
    }
    return $branch;
}

/**
 * 
 * @description Set content limit of the string.
 * @param string $string
 * @param int $limit
 * @param string $break
 * @param string $pad
 * @return string
 */
function wshs_truncate_value($string, $limit, $break = ".", $pad = "...") {

    if (!is_string($string) || !is_int($limit) || !is_string($break) || !is_string($pad)) {
        return ''; // or handle error as needed
    }

    /* Return with no change if string is shorter than $limit */
    if (strlen($string) <= $limit) {
        /* remove visual composer shortcode */
        $shotcodes_tags = array('vc_row', 'vc_column', 'vc_column', 'vc_column_text', 'vc_message', 'vc_section');
        $string = preg_replace('/\[(\/?(' . implode('|', $shotcodes_tags) . ').*?(?=\]))\]/', ' ', $string);
        return $string;
    }
    /* Is $break present between $limit and the end of the string? */
    if (false !== ($breakpoint = strpos($string, $break, $limit))) {
        if ($breakpoint < strlen($string) - 1) {
            /* Remove visual composer shortcode */
            $shotcodes_tags = array('vc_row', 'vc_column', 'vc_column', 'vc_column_text', 'vc_message', 'vc_section');
            $string = preg_replace('/\[(\/?(' . implode('|', $shotcodes_tags) . ').*?(?=\]))\]/', ' ', $string);
            $string = substr($string, 0, $breakpoint) . $pad;
        }
    }
    return $string;
}