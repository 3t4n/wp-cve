<?php
/**
 * Initialize the post-type 'review' and metabox to save rating informations.
 */
add_action('init', 'csr_post_reviewinit');
add_action('add_meta_boxes', 'csr_meta_addinputbox');
add_action('save_post', 'csr_meta_save_ratinginfo');
add_action('manage_posts_custom_column', 'csr_columns_content', 10, 2);

add_filter('the_content', 'csr_add_schema');
add_filter('manage_reviews_posts_columns', 'csr_columns_head');

add_shortcode('csr-add-rating', 'csr_add_rating_shortcode');
add_shortcode('csr-form', 'csr_add_rating_shortcode');
add_shortcode('csr-overall', 'csr_get_overall_rating_shortcode');

function csr_post_reviewinit() {
    
    $labels = [
        "name" => __("Reviews", "csr_starrating"),
        "singular_name" => __("Review", "csr_starrating"),
        //"add_new" => __("Add New Review", "csr_starrating"),        
        "add_new_item" => __("Add New Review", "csr_starrating"),        
        "edit_item" => __("Edit Review", "csr_starrating"),
        "new_item" => __("New Review", "csr_starrating"),
        "view_item" => __("View Review", "csr_starrating"),
        "view_items" => __("View Reviews", "csr_starrating"),
        "featured_image" => __("Review Image", "csr_starrating"),
        "set_featured_image" => __("Set review image", "csr_starrating"),
        "remove_featured_image" => __("Remove review image", "csr_starrating"),
        "use_featured_image" => __("Use as review image", "csr_starrating"),
    ];
    
    $args = array(
        'public' => true,
        'label' => 'Reviews',
        'labels' => $labels,
        'exclude_from_search' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => null,
        'query_var' => 'reviews',
        'rewrite' => array('slug' => 'reviews'),
        "supports" => ["title", "editor", "thumbnail"],//"editor", "thumbnail"
        'menu_icon' => 'dashicons-star-filled'
    );
    register_post_type('reviews', $args);
}

function csr_meta_addinputbox() {
    $screens = array('reviews');
    foreach ($screens as $screen) {
        /*         * add_meta_box(
          'starrating_sectionid', __('Review Info', 'starrating_textdomain'), 'csr_meta_reviewedby', $screen
          );* */
        add_meta_box(
                'starrating_stars', __('Rate This', 'starrating_textdomain'), 'csr_meta_starrating', $screen, 'side', 'high'
        );
    }
}

function csr_meta_starrating($post) {

    global $wpdb;

    wp_nonce_field('starrating_meta_box', 'starrating_meta_box_nonce');
    $post_id = $post->ID;
    $result = $wpdb->get_row("SELECT * FROM " . CSRVOTESTBL . " WHERE post_id=$post_id");
    $overall_rating = 0;
    if($result)
        $overall_rating = $result->overall_rating;

    $overall_rating = (empty($overall_rating)) ? 5 : $overall_rating;

    echo '<label for="customer_review">';
    _e('Current Rating ', 'starrating_textdomain');
    echo '<span id="customer_rate">' . $overall_rating . '</span></label>';
    echo '<input type="hidden" name="customer_review" value="' . $overall_rating . '" id="customer_review" required />';
    echo '<div id="admin-csr-customerrating" data-rating="' . $overall_rating . '"></div>';
}

function csr_meta_save_ratinginfo($post_id) {
    global $wpdb;
    if (!isset($_POST['starrating_meta_box_nonce'])) {
        return;
    }

    if (!wp_verify_nonce($_POST['starrating_meta_box_nonce'], 'starrating_meta_box')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (isset($_POST['post_type']) && 'page' == $_POST['post_type']) {

        if (!current_user_can('edit_page', $post_id)) {
            return;
        }
    } else {

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
    }
    if (!isset($_POST['customer_review'])) {
        return;
    }

    $my_rating = $_POST['customer_review'];

    $user_rating = (empty($my_rating)) ? 0 : $my_rating;
    $current_user = wp_get_current_user();
    $user_ID = $current_user->ID;

    $result = $wpdb->get_row("SELECT * FROM " . CSRVOTESTBL . " WHERE post_id=$post_id");
    if ($result) {
        $wpdb->update(CSRVOTESTBL, array('overall_rating' => number_format($user_rating, 1)), array('id' => $result->id));
    } else {
        $wpdb->insert(CSRVOTESTBL, array(
            'post_id' => $post_id,
            'reviewer_id' => $user_ID,
            'overall_rating' => number_format($user_rating, 1),
            'number_of_votes' => 0,
            'sum_votes' => 0.0,
            'review_type' => 'Other'
                )
        );
    }
}

function csr_add_schema($content) {
    global $wpdb, $post;

    $post_id = get_the_ID();
    $post_type = get_post_type($post_id);

    if ('reviews' == $post_type) {
        $schema = csr_get_rating();
        $content = $content . $schema;
    }

    return $content;
}

function csr_get_rating($post_id = null) {
    global $wpdb, $post;

    if (empty($post_id)) {
        $post_id = get_the_ID();
    }
    $csr_votes_table = CSRVOTESTBL;
    
    $overall_rating = 0;
    $result = $wpdb->get_row("SELECT * FROM " . $csr_votes_table . " WHERE post_id=$post_id");
    if($result)        
        $overall_rating = $result->overall_rating;
    
    $overall_rating = (empty($overall_rating)) ? 5 : $overall_rating;
    
    $post_type = get_post_type($post_id);

    $schema = "<div class=\"csr-ratings rateit\" id=\"csr_rate_$post_id\"  data-rating=\"$overall_rating\"></div>";
    return $schema;
}

function csr_get_overall_rating() {
    echo csr_get_overall_rating_shortcode();
}

function csr_get_overall_rating_shortcode() {
    global $wpdb;

    $csr_votes_table = CSRVOTESTBL;
    $overall_rating = $wpdb->get_row("SELECT count({$csr_votes_table}.id) as total, ROUND(avg(overall_rating), 1) as average FROM {$csr_votes_table}, {$wpdb->posts} WHERE {$wpdb->posts}.ID={$csr_votes_table}.post_id and {$wpdb->posts}.post_status='publish' ");
    ob_start();
    ?>
    <a id="csr-overall-link" href="<?php echo home_url('reviews'); ?>">
        <div id="csr-aggreegate-rate"> 
            <div id="<?php echo home_url(); ?>" itemprop="itemReviewed" itemscope itemtype="http://schema.org/LocalBusiness">                
                <div class="csr-ratings" id="csr-ratings-overall" data-rating="<?php echo $overall_rating->average; ?>"></div>
                <span style="display: none;" itemprop="name"><?php bloginfo( 'name' ); ?></span>
                <span style="display: none;" itemprop="url"><?php bloginfo( 'url' ); ?></span>
                <span style="display: none;" itemprop="description"><?php bloginfo( 'description' ); ?></span>
                <div id="csr-ratings-meta">
                    <div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
                        <span>
                            <span itemprop="ratingValue"><?php echo $overall_rating->average; ?></span>
                            <span style="display: none;" itemprop="reviewCount"><?php echo $overall_rating->total; ?></span>
                        </span>
                        based on <span itemprop="ratingCount"><?php echo $overall_rating->total; ?></span> ratings.
                    </div>
                </div>
            </div>
        </div>
    </a>
    <?php
    $output = ob_get_contents();
    ob_end_clean();
    return $output;
}

function csr_add_rating() {
    echo csr_add_rating_shortcode();
}

function csr_add_rating_shortcode() {
    global $wpdb;
    $message = '';
    if (is_user_logged_in()) {
        $current_user = wp_get_current_user();
        $display_name = $current_user->display_name;
        $user_ID = $current_user->ID;
    } else {
        $user_ID = 1;
        $display_name = '';
    }
    if (isset($_POST['customer_review'])) {
        $reviewed_by = filter_input(INPUT_POST, 'reviewed_by');
        $reviewed_message = filter_input(INPUT_POST, 'review_message');
        $customer_review = filter_input(INPUT_POST, 'customer_review');

        $customer_review_post = array(
            'post_title' => $reviewed_by,
            'post_content' => $reviewed_message,
            'post_status' => 'draft',
            'post_type' => 'reviews',
            'post_author' => $user_ID
        );

        $review_ID = wp_insert_post($customer_review_post);

        if ($review_ID) {
            //update_post_meta($review_ID, '_reviewed_by', $reviewed_by);
            $wpdb->insert(CSRVOTESTBL, array(
                'post_id' => $review_ID,
                'reviewer_id' => $user_ID,
                'overall_rating' => number_format($customer_review, 1),
                'number_of_votes' => 0,
                'sum_votes' => 0.0,
                'review_type' => 'Other'
                    )
            );
            $csr_frm_success_message = esc_attr(get_option('csr_frm_success_message', 'Your review submitted successfully'));
            $message = "<div class='review-success'> $csr_frm_success_message </div>";
        } else {
            $csr_frm_failure_message = esc_attr(get_option('csr_frm_failure_message', 'Please try again after sometime.!!!'));
            $message = "<div class='review-error'> $csr_frm_failure_message </div>";
        }
    }
    ob_start();
    ?>
    <div class="csr-add-rate-form" id="csr-add-rate-form-wrapper">            
        <form method="post" enctype="multipart/form-data" id="gtestform_2" action="">
            <div class="review_form_heading">
                <h3 class="review_form_title" style="margin-top: 0;"><?php echo esc_attr(get_option('csr_frm_title', 'Leave us your review')); ?></h3>
                <span class="review_form_description"><?php echo esc_attr(get_option('csr_frm_info', 'If you are a past, present, or future customer of ours, we value your opinion and please take a second to leave us your feedback.')); ?></span>
            </div>
            <div id="review_form_fields_2" class="review_form_body">
                <?php echo $message; ?>
                <div class="form-group">
                    <label for="reviewed_by"><?php echo esc_attr(get_option('csr_frm_label_name', 'Name or Company')); ?></label>
                    <input type="text" name="reviewed_by" class="form-control" id="reviewed_by" required>
                </div>
                <div class="form-group">
                    <label for="review_message"><?php echo esc_attr(get_option('csr_frm_label_review', 'Review')); ?></label>
                    <textarea name="review_message" class="form-control" rows="10" id="review_message" required></textarea>
                </div>
                <div class="form-group">
                    <label for="ratingInput"><?php echo esc_attr(get_option('csr_frm_label_select_rating', 'Select Your Rating')); ?> (<span id="customer_rate">0</span>)</label>
                    <input type="hidden" name="customer_review" value="5" id="customer_review" required />
                    <div id="csr-customerrating"></div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary"><?php echo esc_attr(get_option('csr_frm_label_submit', 'Submit')); ?></button>
        </form>
    </div>
    <?php
    $output = ob_get_contents();
    ob_end_clean();
    return $output;
}

function csr_columns_head($defaults) {
    $defaults['star_rating'] = 'Rating';
    return $defaults;
}

function csr_columns_content($column_name, $post_id) {

    global $wpdb;

    if ('star_rating' == $column_name) {
        $result = $wpdb->get_row("SELECT * FROM " . CSRVOTESTBL . " WHERE post_id=$post_id");
        $overall_rating = 0;
        
        if($result)        
            $overall_rating = $result->overall_rating;
    
        $overall_rating = (empty($overall_rating)) ? 5 : $overall_rating;
        
        echo "<div class=\"csr-ratings rateit\" id=\"csr_rate_$post_id\"  data-rating=\"$overall_rating\"></div>";
    }
}

/**
 * Deprecated Functions.
 */
function cosmick_get_overall_rating() {
    csr_get_overall_rating();
}

function submit_rating() {
    csr_add_rating();
}
