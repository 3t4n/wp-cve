<?php
defined( 'ABSPATH' ) || exit;

class MOBILE_X {
    public $mobile_theme;
    function __construct() {
        register_theme_directory( MobX_DIR . 'themes' );
        add_action( 'plugins_loaded', array(&$this, 'panel') );
        $this->init();
    }

    function init(){
        $this->mobile_theme = $this->init_theme();
        $theme = wp_get_theme( $this->mobile_theme );
        $parent_theme = $theme && $theme->get('Template') ? $theme->template : '';

        if( is_admin() ) {
            require_once MobX_DIR . 'includes/functions-admin.php';
            if( $parent_theme && file_exists( $parent_file = get_theme_root( $parent_theme ) . '/' . $this->mobile_theme . '/functions-admin.php' ) )
                require_once $parent_file;

            if( $this->mobile_theme && file_exists( $theme_file = get_theme_root( $this->mobile_theme ) . '/' . $this->mobile_theme . '/functions-admin.php' ) )
                require_once $theme_file;
        }else{
            if( $parent_theme && file_exists( $parent_file = get_theme_root( $parent_theme ) . '/' . $this->mobile_theme . '/functions-common.php' ) )
                require_once $parent_file;

            if( $this->mobile_theme && file_exists( $theme_file = get_theme_root( $this->mobile_theme ) . '/' . $this->mobile_theme . '/functions-common.php' ) )
                require_once $theme_file;
        }

        if( !$this->is_mobile() || !$this->mobile_theme || $this->is_rest() ) return; // 移动端主题，只在移动端启用；并且需要启用了手机主题
        add_action( 'setup_theme', array( &$this, 'setup_theme' ), 20 );
        add_filter( 'stylesheet', array( &$this, 'stylesheet' ), 20 );
        add_filter( 'template', array( &$this, 'template' ), 20 );
    }

    function panel() {
        require_once WPCOM_ADMIN_FREE_PATH . 'load.php';
        $mobx_info = array(
            'slug' => 'wp-mobile-x',
            'name' => __('Mobile Theme', 'wp-mobile-x'),
            'ver' => MobX_VERSION,
            'title' => __('Mobile Theme', 'wp-mobile-x'),
            'icon' => 'dashicons-smartphone',
            'position' => 88,
            'key' => 'mobx_options',
            'plugin_id' => 'mobx',
            'basename' => plugin_basename(__FILE__)
        );
        $GLOBALS['mobx'] = new WPCOM_PLUGIN_PANEL_FREE($mobx_info);
    }

    function is_rest(){
        $prefix = rest_get_url_prefix();
        $rest_url = wp_parse_url( site_url( $prefix ) );
        $current_url = wp_parse_url( add_query_arg( array( ) ) );
        $rest = strpos( $current_url['path'], $rest_url['path'], 0 ) === 0;
        if(!$rest) $rest = isset($current_url['query']) && strpos( $current_url['query'], 'rest_route=', 0 ) === 0;
        return $rest;
    }

    function is_mobile() {
        if ( isset( $_SERVER['HTTP_SEC_CH_UA_MOBILE'] ) ) {
            $is_mobile = ( '?1' === $_SERVER['HTTP_SEC_CH_UA_MOBILE'] );
        } elseif ( empty( $_SERVER['HTTP_USER_AGENT'] ) ) {
            $is_mobile = false;
        } elseif ( str_contains( $_SERVER['HTTP_USER_AGENT'], 'Mobile' ) // Many mobile devices (all iPhone, iPad, etc.)
            || str_contains( $_SERVER['HTTP_USER_AGENT'], 'Android' )
            || str_contains( $_SERVER['HTTP_USER_AGENT'], 'Silk/' )
            || str_contains( $_SERVER['HTTP_USER_AGENT'], 'Kindle' )
            || str_contains( $_SERVER['HTTP_USER_AGENT'], 'BlackBerry' )
            || str_contains( $_SERVER['HTTP_USER_AGENT'], 'Opera Mini' )
            || str_contains( $_SERVER['HTTP_USER_AGENT'], 'Opera Mobi' ) ) {
                $is_mobile = true;
        } else {
            $is_mobile = false;
        }

        return apply_filters( 'wp_is_mobile', $is_mobile );
    }

    function init_theme(){
        global $mobx_options;
        if(empty($mobx_options)) $mobx_options = get_option('mobx_options');
        if(is_string($mobx_options)) $mobx_options = json_decode($mobx_options, true);
        if(isset($mobx_options['theme']) && $mobx_options['theme']){
            return $mobx_options['theme'];
        }
    }

    function setup_theme(){
        if($this->mobile_theme) require_once MobX_DIR . 'includes/functions-theme.php';
    }

    function stylesheet( $stylesheet='' ){
        if($this->mobile_theme) $stylesheet = $this->mobile_theme;
        return $stylesheet;
    }

    function template( $template='' ){
        if($this->mobile_theme){
            $theme = wp_get_theme( $this->mobile_theme );
            $template = $theme && $theme->get('Template') ? $theme->template : $this->mobile_theme;
        }
        return $template;
    }
}

new MOBILE_X();