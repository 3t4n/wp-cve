<?php
// wpcom setup
add_action( 'after_setup_theme', 'mobx_setup' );
if ( ! function_exists( 'mobx_setup' ) ) :
    function mobx_setup() {
        if ( function_exists( 'show_admin_bar' ) ) {
            add_filter( 'show_admin_bar', '__return_false', 100 );
        }

        remove_action('wp_head', 'wp_generator');
    }
endif;

add_filter('pre_option_show_on_front', 'mobx_show_on_front');
function mobx_show_on_front() {
    return 'posts';
}

// 加载静态资源
if ( ! function_exists( 'mobx_scripts' ) ) :
    function mobx_scripts() {
        // 载入主样式
        $css = is_child_theme() ? '/style.css' : '/css/style.css';
        wp_enqueue_style( 'mobile-x', get_stylesheet_directory_uri() . $css, array(), MobX_VERSION );

        // 载入js文件
        wp_enqueue_script( 'mobile-x', get_template_directory_uri() . '/js/main.js', array( 'jquery' ), MobX_VERSION, true );
        $mobx_localize_script = apply_filters('mobx_localize_script', array('ajaxurl' => admin_url( 'admin-ajax.php')));
        wp_localize_script( 'mobile-x', 'mobile_x_js', $mobx_localize_script );
    }
endif;
add_action( 'wp_enqueue_scripts', 'mobx_scripts' );
/* 静态资源结束 */


// wp title
add_filter( 'wp_title_parts', 'mobx_title_parts', 20 );
if ( ! function_exists( 'mobx_title_parts' ) ) :
    function mobx_title_parts( $parts ){
        global $post, $wp_title_parts;
        if ( is_tax() && get_queried_object()) {
            $parts = array( single_term_title( '', false ) );
        }
        $title_array = array();
        foreach ( $parts as $t ){
            if(trim($t)) $title_array[] = $t;
        }
        if ( is_singular() ) {
            $seo_title = trim(strip_tags(get_post_meta($post->ID, 'wpcom_seo_title', true)));
            if ($seo_title != '') $title_array[0] = $seo_title;
        } else if ( is_category() || is_tag() || is_tax() ) {
            $term = get_queried_object();
            $seo_title = get_term_meta($term->term_id, 'wpcom_seo_title', true);
            $seo_title = $seo_title != '' ? $seo_title : '';
            if ($seo_title != '') $title_array[0] = $seo_title;
        }
        $wp_title_parts = $title_array;

        return $wp_title_parts;
    }
endif;

add_filter( 'wp_title', 'mobx_title', 10, 3 );
if ( ! function_exists( 'mobx_title' ) ) :
    function mobx_title( $title, $sep, $seplocation) {
        global $paged, $page, $wp_title_parts, $mobx_options;

        if ((is_home() || is_front_page()) && isset($mobx_options['home-title']) && $mobx_options['home-title']) {
            return $mobx_options['home-title'];
        }

        $prefix = !empty($title) ? $sep : '';
        $title = $seplocation == 'right' ? implode($sep, array_reverse($wp_title_parts)).$prefix : $prefix.implode($sep, $wp_title_parts);

        // 首页标题
        if ( empty($title) && (is_home() || is_front_page()) ) {
            $desc = get_bloginfo('description');
            if ($desc) {
                $title = get_option('blogname') . (isset($mobx_options['title_sep_home']) && $mobx_options['title_sep_home'] ? $mobx_options['title_sep_home'] : $sep) . $desc;
            } else {
                $title = get_option('blogname');
            }
        } else {
            if ($paged >= 2 || $page >= 2) // 增加页数
                $title = $title . sprintf(__('Page %s', 'wp-mobile-x'), max($paged, $page)) . $sep;
            if ('right' == $seplocation) {
                $title = $title . get_option('blogname');
            } else {
                $title = get_option('blogname') . $title;
            }
        }
        return $title;
    }
endif;

add_action('mobx_copyright', 'mobx_copyright');
if ( ! function_exists( 'mobx_copyright' ) ) :
    function mobx_copyright(){
        global $mobx_options;
        echo isset($mobx_options['copyright']) ? $mobx_options['copyright'] : '';
        echo isset($mobx_options['tongji']) ? $mobx_options['tongji'] : '';
    }
endif;

if ( ! function_exists( 'mobx_sticky_posts_query' ) && !class_exists('SCPO_Engine') ) :
    add_action( 'pre_get_posts', 'mobx_sticky_posts_query', 10 );
    function mobx_sticky_posts_query( $q ) {
        if( !isset( $q->query_vars[ 'ignore_sticky_posts' ] ) ){
            $q->query_vars[ 'ignore_sticky_posts' ] = 1;
        }
        if( is_home() && $q->is_main_query() ){
            $q->query_vars[ 'ignore_sticky_posts' ] = 0;
        }
        if ( isset( $q->query_vars[ 'ignore_sticky_posts' ] ) && !$q->query_vars[ 'ignore_sticky_posts' ] ){
            $q->query_vars[ 'ignore_sticky_posts' ] = 1;
            if(isset($q->query_vars[ 'orderby' ]) && $q->query_vars[ 'orderby' ]) {
                $q->query_vars[ 'orderby' ] .= ' menu_order';
            }else{
                $q->query_vars[ 'orderby' ] = 'menu_order date';
            }
        }
        return $q;
    }
endif;

add_action('pre_get_posts', 'mobx_posts_per_page' );
function mobx_posts_per_page( $query ) {
    global $mobx_options;
    if( isset($mobx_options['posts_per_page']) && $mobx_options['posts_per_page'] && $query->is_main_query() && ! is_admin() ) {
        $query->set( 'posts_per_page', $mobx_options['posts_per_page'] );
    }
}

if ( ! function_exists( 'mobx_related_post' ) ) :
function mobx_related_post( $tpl, $showposts = 10, $title = '', $class=''){
    // todo: 相关文章获取方式
    global $post, $options;

    $args = array(
        'post__not_in' => array($post->ID),
        'showposts' => $showposts,
        'ignore_sticky_posts' => 1,
        'orderby' => 'rand'
    );

    if(isset($options['related_by']) && $options['related_by']=='1'){
        $tag_list = array();
        $tags = get_the_tags($post->ID);
        if($tags) {
            foreach ($tags as $tag) {
                $tid = $tag->term_id;
                if (!in_array($tid, $tag_list)) {
                    $tag_list[] = $tid;
                }
            }
        }
        $args['tag__in'] = $tag_list;
    }else{
        $cat_list = array();
        $categories = get_the_category($post->ID);
        if($categories) {
            foreach ($categories as $category) {
                $cid = $category->term_id;
                if (!in_array($cid, $cat_list)) {
                    $cat_list[] = $cid;
                }
            }
        }
        $args['category'] = join(',', $cat_list);
    }

    $posts = get_posts($args);
    $output = '';
    $title = $title ? $title : __('Related posts', 'wp-mobile-x');
    if( $posts ) {
        $output .= '<h3 class="entry-related-title">'.$title.'</h3>';
        $output .=  '<ul class="entry-related '.$class.' clearfix">';
        ob_start();
        foreach ( $posts as $post ) { setup_postdata($post);
            get_template_part( $tpl );
        }
        $output .= ob_get_clean();
        $output .= '</ul>';
    }
    wp_reset_postdata();
    echo $output;
}
endif;

if ( ! function_exists( 'mobx_comment' ) ) :
    function mobx_comment($comment, $args, $depth) {
        $GLOBALS['comment'] = $comment;
        extract($args, EXTR_SKIP);

        if ( 'div' == $args['style'] ) {
            $tag = 'div';
            $add_below = 'comment';
        } else {
            $tag = 'li';
            $add_below = 'div-comment';
        }
        if($comment->user_id && function_exists('um_get_core_page')){
            um_fetch_user( $comment->user_id );
            $author = um_user('display_name');
        }else{
            $author = get_comment_author();
        }
        ?>
        <<?php echo $tag ?> <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ) ?> id="comment-<?php comment_ID() ?>">
        <div id="div-comment-<?php comment_ID() ?>">
            <div class="comment-author vcard">
                <?php if ( $args['avatar_size'] != 0 ) echo get_avatar( $comment, $args['avatar_size'] ); ?>
            </div>
            <div class="comment-body">
                <div class="nickname"><?php echo $author;?>
                    <span class="comment-time"><?php echo get_comment_date().' '.get_comment_time(); ?></span>
                </div>
                <?php if ( $comment->comment_approved == '0' ) : ?>
                    <div class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'wp-mobile-x' ); ?></div>
                <?php endif; ?>
                <div class="comment-text"><?php comment_text(); ?></div>
            </div>

            <div class="reply">
                <?php comment_reply_link( array_merge( $args, array( 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
            </div>
        </div>
    <?php
    }
endif;