<?php
/**
 * Adding shortcode for displaying all published stories on any page via shortcode
 */
add_shortcode( 'ms_get_published_post', 'ms_get_published_post_via_shortcode' );

function ms_get_published_post_via_shortcode() {
    $default_posts_per_page = get_option( 'posts_per_page' );
    $getAjaxUrl =  admin_url('admin-ajax.php');
    $postCount = wp_count_posts(MS_POST_TYPE);
    $postCount = $postCount->publish;
    $config = ms_get_options();
    $design = isset($config['design']) ? $config['design'] : "1";
    ob_start();
    ?>
    <section class="ms-default-stories">
    <?php if ($postCount > 0) { ?>
    <div id="ajax-posts" class="ms-stories-group row" data-posts="<?php echo esc_attr($default_posts_per_page); ?>" data-ajax="<?php echo esc_attr($getAjaxUrl); ?>">
        <?php
        $postsPerPage = $default_posts_per_page;

        $args = [
            'post_type' => MS_POST_TYPE,
            'posts_per_page' => $postsPerPage,
            'post_status' => 'publish',
        ];

        $loops = get_posts($args);
        $postChunks = array_chunk($loops,8);
        foreach($postChunks as $key=>$value) {
            ?>
            <div class="ms-grid stories-showcase-block <?php if ($design == "2") { echo "design-2"; } else { echo "design-1"; } ?>" id="listing-grid" data-design="<?php echo $design; ?>">
            <?php
                foreach($value as $index=>$post) {
                    include mscpt_getTemplatePath("prepare-story-vars.php");
                    if ($design == "2") {
                        include mscpt_getTemplatePath("listing-story-grid.php");
                    } else {
                        include mscpt_getTemplatePath("listing-story-masonry.php");
                    }
                }
            ?>
            </div>
            <?php
        }
        ?>
    </div>
    <div id="d-one" style="display: none;"></div>
        <?php if ($postCount > $postsPerPage) { ?>
        <div class="ms-load-more-wrap">
            <span id="ms-loading-spinner"></span>
            <a id="more_posts">Load More</a>
        </div>
    <?php
        }
    ?>
    <div id="script-block">
        <?php require_once mscpt_getTemplatePath("story-player-model.php"); ?>
    </div>
    <?php } else { ?>
            <p class="no-stories">No Stories Found</p>
    <?php } ?>
</section>
<?php
return ob_get_clean();
}

/**
 * Adding shortcode for displaying list of published stories of perticular CATEGORY
 */
add_shortcode( 'ms_get_post_by_category', 'ms_get_post_by_category' );

function ms_get_post_by_category($attr) {
    $default_posts_per_page = get_option( 'posts_per_page' );
    $getAjaxUrl =  admin_url('admin-ajax.php');
    $cat_id = $attr['category_id'];
    $int_cat = (int)$cat_id;
    $term = get_term($int_cat,MS_TAXONOMY);
    $config = ms_get_options();
    $design = isset($config['design']) ? $config['design'] : "1";
    $args = [
        'post_type' => MS_POST_TYPE,
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'offset'=> 0,
        "tax_query" => array(
            array(
                "taxonomy" => MS_TAXONOMY,
                "field" => "term_id",
                "terms" =>  $int_cat
            ))
    ];
    // $loop = new WP_Query($args);
    $loops = get_posts($args);
    $postChunks = array_chunk($loops,8);
    ob_start();
    require(mscpt_getTemplatePath('ms-post-by-category.php'));
    
    return ob_get_clean();
}
/**
 * Adding shortcode for displaying single published story on any page via shortcode 
 */
add_shortcode( 'ms_get_single_post', 'ms_get_single_post_via_shortcode' );
add_shortcode( 'ms_get_single_post_shortcode', 'ms_get_single_post_via_shortcode' );
add_shortcode( 'ms_get_single_post_via_shortcode', 'ms_get_single_post_via_shortcode' );

function ms_get_single_post_via_shortcode($attr) {
    $args = [
        "post_type" => MS_POST_TYPE,
        "numberposts" => -1
    ];
    $posts = get_posts($args);
    $config = ms_get_options();
    $design = isset($config['design']) ? $config['design'] : "1";
    ob_start();
    foreach ($posts as $post){
        $postId = $post->ID;
        if($postId == $attr['post_id']) {

            include mscpt_getTemplatePath("prepare-story-vars.php");
            if ($design == "2") {
                require(mscpt_getTemplatePath('ms-single-post.php'));
            } else {
                require(mscpt_getTemplatePath('ms-single-masonry-post.php'));
            }
        }
    }
    return ob_get_clean();
}

/**
 * Adding shortcode for displaying published widget on any page via shortcode
 */

add_shortcode( 'ms_get_single_widget', 'ms_get_single_widget_via_shortcode' );

function ms_get_single_widget_via_shortcode($attr) {
    $args = [
        "post_type" => MS_POST_WIDGET_TYPE,
        "numberposts" => -1
    ];
    $posts = get_posts($args);
    ob_start();
    foreach ($posts as $post){
        $postId = $post->ID;
        if($postId == $attr['widget_id']) { 
            $meta = get_post_meta($postId);
            require(mscpt_getTemplatePath('ms-single-widget.php'));
        }
    }
    return ob_get_clean();
}