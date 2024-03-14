<?php
require_once MobX_DIR . 'includes/class-post-meta.php';
require_once MobX_DIR . 'includes/class-term-meta.php';

// wpcom setup
add_action( 'after_setup_theme', 'mobx_setup_admin' );
if ( ! function_exists( 'mobx_setup_admin' ) ) :
    function mobx_setup_admin() {
        // 缩略图设置
        add_theme_support( 'post-thumbnails' );

        // menu
        register_nav_menus( array(
            'mobile_x'   => __('Menu for mobile theme', 'wp-mobile-x'),
        ) );
    }
endif;

add_filter( 'wp_prepare_themes_for_js', 'mobx_prepare_themes_for_js' );
function mobx_prepare_themes_for_js( $prepared_themes ){
    $new_themes = array();

    if($prepared_themes){
        foreach ($prepared_themes as $theme) {
            $index = strpos($theme['tags'], 'WP Mobile X');
            if( ($index||$index===0) && isset($theme['actions']) && isset($theme['actions']['activate'])){
                $theme['actions']['activate'] = "javascript:mobx_alert('".$theme['name']."');";
            }
            $new_themes[] = $theme;
        }
    }
    return $new_themes;
}

add_filter( 'pre_update_option_sticky_posts', 'wpcom_fix_sticky_posts' );
if ( ! function_exists( 'wpcom_fix_sticky_posts' ) ) :
    function wpcom_fix_sticky_posts( $stickies ) {
        if( !class_exists('SCPO_Engine') ) {
            global $wpdb;
            $menu_order = 1;
            $count = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->posts WHERE `post_type` = 'post' AND `menu_order` not IN (0,1)" );
            if( $count>0 ) {
                // 先预处理防止插件设置的menu_order，主要是SCPOrder插件
                $wpdb->update($wpdb->posts, array('menu_order' => 0), array('post_type' => 'post'));
            }
        }else{
            $menu_order = -1;
        }

        $old_stickies = array_diff( get_option( 'sticky_posts' ), $stickies );
        foreach( $stickies as $sticky )
            wp_update_post( array( 'ID' => $sticky, 'menu_order' => $menu_order ) );
        foreach( $old_stickies as $sticky )
            wp_update_post( array( 'ID' => $sticky, 'menu_order' => 0 ) );
        return $stickies;
    }
endif;

add_action('admin_footer', 'mobx_themes_alert');
function mobx_themes_alert(){
    global $pagenow;
    if(isset($pagenow) && $pagenow == 'themes.php'){
        $msg = sprintf(__('The theme [%1$s] you have activated is for WP Mobile X plugin, Please go to [Mobile Theme] page to activate it.', 'wp-mobile-x'), "'+theme+'");
        ?>
    <script>
        function mobx_alert(theme){
            alert('<?php echo $msg;?>')
        }
    </script>
<?php } }

add_action( 'admin_init', 'mobx_admin_init_setup' );
function mobx_admin_init_setup() {
    if( !class_exists('WPCOM_Meta') ) {
        global $pagenow;
        if( $pagenow == 'post.php' || $pagenow == 'post-new.php' ) {
            new MobX_Post_Meta();
        }

        if( ($pagenow == 'edit-tags.php' || $pagenow == 'term.php' || (isset($_POST['action']) && $_POST['action']=='add-tag')) ) {
            $settings = apply_filters( 'mobx_tax_options', array() );
            $metas = array();
            if($settings){
                foreach ( $settings as $tax => $meta ){
                    $taxs = explode(',', $tax);
                    if( $taxs && count($taxs)>1 ){
                        foreach ($taxs as $t){
                            $t = trim($t);
                            if( isset($metas[$t]) ){
                                $metas[$t] = array_merge($metas[$t], $meta);
                            }else{
                                $metas[$t] = $meta;
                            }
                        }
                    }else if( isset($metas[$tax]) ){
                        $metas[$tax] = array_merge($metas[$tax], $meta);
                    }else{
                        $metas[$tax] = $meta;
                    }
                }
            }
            $mobx_tax_metas = apply_filters('mobx_tax_metas', $metas);
            foreach ($mobx_tax_metas as $tax => $meta) {
                new MobX_Term_Meta($tax, $meta);
            }
        }
    }
}

add_filter('mobx_tax_metas', 'mobx_tax_metas', 20);
function mobx_tax_metas( $metas ){
    if( class_exists('WPCOM_Meta') ) {
        $settings = apply_filters( 'mobx_tax_options', array() );
        if($settings){
            foreach ( $settings as $tax => $meta ){
                $taxs = explode(',', $tax);
                if( $taxs && count($taxs)>1 ){
                    foreach ($taxs as $t){
                        $t = trim($t);
                        if( isset($metas[$t]) ){
                            $metas[$t] = array_merge($metas[$t], $meta);
                        }else{
                            $metas[$t] = $meta;
                        }
                    }
                }else if( isset($metas[$tax]) ){
                    $metas[$tax] = array_merge($metas[$tax], $meta);
                }else{
                    $metas[$tax] = $meta;
                }
            }
        }
    }
    return $metas;
}

// add seo options
add_filter( 'wpcom_post_metas', 'mobx_post_metas', 10 );
function mobx_post_metas( $metas ){
    if( class_exists('WPCOM_Meta') ) {
        $options = apply_filters('mobx_meta_options', new stdClass());
        $options = json_decode(json_encode($options), true);
        if($options){
            foreach ($options as $key => $value) {
                if(isset($metas[$key]) && is_array($metas[$key])){
                    foreach ($value as $v) {
                        $metas[$key][] = $v;
                    }
                }else{
                    $metas[$key] = $value;
                }
            }
        }
    }
    return $metas;
}

add_filter('wp-mobile-x_form_options', 'mobx_add_form_options', 1);
function mobx_add_form_options( $options ){
    $all_themes = wp_prepare_themes_for_js();
    $themes = array('' => __('-- Please select --', 'wp-mobile-x'));
    if($all_themes){
        foreach ( $all_themes as $theme ) {
            $index = strpos($theme['tags'], 'WP Mobile X');
            if( ($index||$index===0)){
                $themes[$theme['id']] = $theme['name'];
            }
        }
    }

    $options = array(
        array(
            'title' => __('General', 'wp-mobile-x'),
            'icon' => '&#xe8b8;',
            'options' => array(
                array(
                    'title' => __('General Settings', 'wp-mobile-x'),
                    'type' => 'title'
                ),
                array(
                    'title' => __('Enable Theme', 'wp-mobile-x'),
                    'name' => 'theme',
                    'desc' => __('Select what theme you want to activate', 'wp-mobile-x'),
                    'type' => 'select',
                    'options' => $themes
                ),
                array(
                    'name' => 'logo',
                    'title' => 'Logo',
                    'type' => 'upload'
                ),
                array(
                    'name' => 'posts_per_page',
                    'title' => __('Posts per page', 'wp-mobile-x'),
                    'desc' => __('Number of Posts displayed per page on the mobile device', 'wp-mobile-x'),
                    'std' => '10',
                    'type' => 'text'
                ),
                array(
                    'name' => 'copyright',
                    'title' => __('Copyright', 'wp-mobile-x'),
                    'desc' => __('Copyright information in footer', 'wp-mobile-x'),
                    'type' => 'editor'
                ),
                array(
                    'name' => 'tongji',
                    'title' => __('Analytics code', 'wp-mobile-x'),
                    'desc' => __('You can put Google Analytics code here', 'wp-mobile-x'),
                    'std' => '',
                    'rows' => 4,
                    'type' => 'textarea'
                )
            )
        )
    );

    return $options;
}