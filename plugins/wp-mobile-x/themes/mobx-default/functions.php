<?php
/**
 * 主题functions功能文件
 * 
 * @author Lomu
 * @since Default - WP Mobile X 1.0
 */
add_action( 'after_setup_theme', 'mobx_default_setup' );
function mobx_default_setup(){
    /**
     * Add text domain
     */
    load_theme_textdomain('wpcom', get_template_directory() . '/lang');
}

add_action('wp_ajax_mobx_load_more', 'mobx_load_more');
add_action('wp_ajax_nopriv_mobx_load_more', 'mobx_load_more');
function mobx_load_more(){
    global $mobx_options;
    $id = isset($_POST['id']) ? $_POST['id'] : '';
    $type = isset($_POST['type']) ? $_POST['type'] : '';
    $page = $_POST['page'];
    $page = $page ? $page : 1;
    $per_page = isset($mobx_options['posts_per_page']) && $mobx_options['posts_per_page'] ? $mobx_options['posts_per_page'] : get_option('posts_per_page');
    if($type=='search'){
        $args = array(
            's' => $id,
            'paged' => $page,
            'posts_per_page' => $per_page,
        );
    }else if($id){
        $args = array(
            'posts_per_page' => $per_page,
            'paged' => $page,
            'post_type' => 'post',
            'post_status' => array( 'publish' ),
            'ignore_sticky_posts' => 0
        );
        if($type=='cat') $args['cat'] = $id;
        if($type=='tag') $args['tag_id'] = $id;
    }else{
        $args = array(
            'posts_per_page' => $per_page,
            'paged' => $page,
            'post_type' => 'post',
            'ignore_sticky_posts' => 0,
            'post_status' => array( 'publish' )
        );
    }

    $posts = new WP_Query($args);

    global $post;
    if($posts->have_posts()) {
        while ( $posts->have_posts() ) : $posts->the_post();
            get_template_part( 'content' , 'list' );
        endwhile;
        wp_reset_postdata();
    }else{
        echo 0;
    }
    exit;
}

add_filter( 'mobx_localize_script', 'mobx_add_localize_script');
function mobx_add_localize_script( $scripts ){
    $scripts['ajax_loaded'] = __('All posts have been loaded', 'wpcom');
    return $scripts;
}

add_action( 'wp_head', 'mobx_style_output', 20 );
if ( ! function_exists( 'mobx_style_output' ) ) :
    function mobx_style_output(){
        global $mobx_options;
        $color = isset($mobx_options['color']) && $mobx_options['color'] ? $mobx_options['color'] : '';
        $hover = isset($mobx_options['hover']) && $mobx_options['hover'] ? $mobx_options['hover'] : $color;
        ?>
        <style>
        <?php if($color){ ?>
            a,.load-more{color:<?php echo $color;?>;}
            a:hover{color:<?php echo $hover;?>;}
            .load-more{border-color:<?php echo $color;?>;}
            .entry-content h3, .entry-content .h3{border-left-color:<?php echo $color;?>;}
            .navbar-nav,.archive-list .item-title a .sticky-post,.form-submit .submit{background-color:<?php echo $color;?>;}
            .navbar-nav .nav>li>a:active, .navbar-nav .nav>li>a:focus,.form-submit .submit:focus, .form-submit .submit:active{background-color:<?php echo $hover;?>;}
        <?php } ?>
            .load-more.loading:after{content: '<?php _e('Loading...','wpcom');?>';}
        </style>
    <?php }
endif;