<?php

// don't load directly
defined( 'ABSPATH' ) || exit;


/*-----------------------------------------------------------------------------------*/
/*  *.  Borderless Dashboard
/*-----------------------------------------------------------------------------------*/

class Borderless_Dashboard {
    
    public function __construct() {
        
        add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
        add_action( 'admin_init', array( $this, 'init_settings'  ) );
        
    }
    
    public function add_admin_menu() {
        
        add_menu_page(
            esc_html__( 'Borderless', 'borderless' ),
            esc_html__( 'Borderless', 'borderless' ),
            'manage_options',
            'borderless.php',
            array( $this, 'page_layout' ),
            BORDERLESS__URL . '/assets/img/borderless.svg',
            3
        );
        
        add_submenu_page(
            'borderless.php',                         // parent slug
            esc_html__( 'Settings', 'borderless' ),   // page title
            esc_html__( 'Settings', 'borderless' ),   // menu title
            'manage_options',                         // capability
            'borderless.php'                          // slug
        );

        add_submenu_page(
            'borderless.php',                         
            esc_html__( 'Post Types', 'borderless' ),   
            esc_html__( 'Post Types', 'borderless' ),  
            'manage_options',                         
            'edit.php?post_type=borderless_cpt'                          
        );

        add_action('admin_enqueue_scripts', 'borderless_dashboard_style');
        
        function borderless_dashboard_style($hook)
        {
            
            $current_screen = get_current_screen();
            
            if ( strpos($current_screen->base, 'toplevel_page_borderless') === false) {
                return;
            } else {
                
                wp_enqueue_style('borderless_backend_style', BORDERLESS__URL . '/assets/styles/dashboard.min.css', array(), BORDERLESS__VERSION );
            }
        }
        
        add_action( 'admin_menu', 'borderless_icon_fonts_submenu', 50 );
        
        if ( ! function_exists( 'borderless_icon_fonts_submenu' ) ) {
            function borderless_icon_fonts_submenu() {
                $icon_manager_page = add_submenu_page(
                    'borderless.php',
                    esc_html__( "Icon Fonts", "borderless" ),
                    esc_html__( "Icon Fonts", "borderless" ),
                    'manage_options',
                    'borderless-fonts',
                    'borderless_custom_icons_menu'
                );
                $Borderless_IF  = new Borderless_IF;
                add_action('admin_print_styles-' . $icon_manager_page, array( $Borderless_IF, 'admin_scripts' ) );
            }
        }

        add_submenu_page(
            'borderless.php',
            esc_html__( 'System Info', 'borderless' ),
            esc_html__( 'System Info', 'borderless' ),
            'manage_options',                        
            'borderless-system-info',  
            'Borderless_System_Info', 
            99                      
        );
        
    }
    
    public function init_settings() {
        
        register_setting(
            'settings_group',
            'borderless'
        );
        
        add_settings_section(
            'borderless_section',
            '',
            false,
            'borderless'
        );
        
        add_settings_field(
            'primary_color',
            __( 'Primary Color', 'borderless' ),
            array( $this, 'render_primary_color_field' ),
            'borderless',
            'borderless_section'
        );
        add_settings_field(
            'secondary_color',
            __( 'Secondary Color', 'borderless' ),
            array( $this, 'render_secondary_color_field' ),
            'borderless',
            'borderless_section'
        );
        add_settings_field(
            'tertiary_color',
            __( 'Tertiary Color', 'borderless' ),
            array( $this, 'render_tertiary_color_field' ),
            'borderless',
            'borderless_section'
        );
        add_settings_field(
            'text_color',
            __( 'Text Color', 'borderless' ),
            array( $this, 'render_text_color_field' ),
            'borderless',
            'borderless_section'
        );
        add_settings_field(
            'related_posts',
            __( 'Related Posts', 'borderless' ),
            array( $this, 'render_related_posts_field' ),
            'borderless',
            'borderless_section'
        );
        
    }
    
    public function page_layout() {
        
        // Check required user capability
        if ( !current_user_can( 'manage_options' ) )  {
            wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'borderless' ) );
        }
        
        $current_user	= wp_get_current_user();
        $time			= date('H');
        $timezone		= date('e');
        $hi				= __('Good Evening, ', 'borderless');
        $avatar = wp_get_current_user();
        if($time < '12'){
            $hi = __('Good Morning, ', 'borderless');
        }elseif($time >= '12' && $time < '17'){
            $hi = __('Good Afternoon, ', 'borderless');
        }
        
        // Admin Page Layout
        
        ?><div class="wrap borderless-page-welcome about-wrap">
        
        <!-- Welcome to Borderless -->
        <div class="borderless-dashboard-header">
        <div class="borderless-dashboard-howdy">
        <div class="borderless-dashboard-avatar"><?php echo get_avatar( $current_user->ID, 64 ); ?></div>
        <div class="borderless-dashboard-title-subtitle">
        <h1 class="borderless-dashboard-title"><?php echo $hi .'<strong>'.$current_user->display_name.'</strong>'; ?></h1>
        <h2 class="borderless-dashboard-subtitle"><?php _e('You are running Borderless ', 'borderless'); echo BORDERLESS__VERSION; ?></h2>
        </div>	
        </div>	
        <p class="about-text"><?php _e('Within minutes you can build complex layouts on the basis of our content elements and without touching a single line of code.', 'borderless') ?></p>
        </div>
        <div class="wp-badge borderless-page-logo">
        <?php echo sprintf( __( 'Version %s', 'borderless' ), BORDERLESS__VERSION ) ?>
        </div>
        <p class="borderless-page-actions">
        <a href="https://twitter.com/share" class="twitter-share-button"
        data-via="visualmodo"
        data-text="Take full control over your WordPress site with Borderless"
        data-url="https://visualmodo.com" data-size="large">Tweet</a>
        <script>! function ( d, s, id ) {
            var js, fjs = d.getElementsByTagName( s )[ 0 ], p = /^http:/.test( d.location ) ? 'http' : 'https';
            if ( ! d.getElementById( id ) ) {
                js = d.createElement( s );
                js.id = id;
                js.src = p + '://platform.twitter.com/widgets.js';
                fjs.parentNode.insertBefore( js, fjs );
            }
        }( document, 'script', 'twitter-wjs' );</script>
        </p>
        
        <?php
        echo '<div class="borderless-dashboard-settings">' . "\n";
        echo '<h3>Settings</h3>' . "\n";
        echo '	<form action="options.php" method="post">' . "\n";
        
        settings_fields( 'settings_group' );
        do_settings_sections( 'borderless' );
        submit_button();
        
        echo '</form>' . "\n";
        echo '</div>' . "\n";
        echo '</div>' . "\n";
        
        ?></div><?php
        
    }
    
    function render_primary_color_field() {
        
        // Retrieve data from the database.
        $options = get_option( 'borderless' );
        
        // Set default value.
        $value = isset( $options['primary_color'] ) ? $options['primary_color'] : '#0000FF';
        
        // Field output.
        echo '<input type="color"  name="borderless[primary_color]" class="regular-text primary_color_field" placeholder="' . esc_attr__( '', 'borderless' ) . '" value="' . esc_attr( $value ) . '">';
        echo '<span class="description">' . __( 'Pick a primary color for the elements.', 'borderless' ) . '</span>';
        
    }
    
    function render_secondary_color_field() {
        
        // Retrieve data from the database.
        $options = get_option( 'borderless' );
        
        // Set default value.
        $value = isset( $options['secondary_color'] ) ? $options['secondary_color'] : '#FF6819';
        
        // Field output.
        echo '<input type="color" name="borderless[secondary_color]" class="regular-text secondary_color_field" placeholder="' . esc_attr__( '', 'borderless' ) . '" value="' . esc_attr( $value ) . '">';
        echo '<span class="description">' . __( 'Pick a secondary color for the elements.', 'borderless' ) . '</span>';
        
    }

    function render_tertiary_color_field() {
        
        // Retrieve data from the database.
        $options = get_option( 'borderless' );
        
        // Set default value.
        $value = isset( $options['tertiary_color'] ) ? $options['tertiary_color'] : '#3FCC14';
        
        // Field output.
        echo '<input type="color" name="borderless[tertiary_color]" class="regular-text tertiary_color_field" placeholder="' . esc_attr__( '', 'borderless' ) . '" value="' . esc_attr( $value ) . '">';
        echo '<span class="description">' . __( 'Pick a tertiary color for the elements.', 'borderless' ) . '</span>';
        
    }
    
    function render_text_color_field() {
        
        // Retrieve data from the database.
        $options = get_option( 'borderless' );
        
        // Set default value.
        $value = isset( $options['text_color'] ) ? $options['text_color'] : '';
        
        // Field output.
        echo '<input type="color" name="borderless[text_color]" class="regular-text text_color_field" placeholder="' . esc_attr__( '', 'borderless' ) . '" value="' . esc_attr( $value ) . '">';
        echo '<span class="description">' . __( 'Pick a text color for the elements.', 'borderless' ) . '</span>';
        
    }
    
    function render_related_posts_field() {
        
        // Retrieve data from the database.
        $options = get_option( 'borderless' );
        
        // Set default value.
        $value = isset( $options['related_posts'] ) ? $options['related_posts'] : '';
        
        // Field output.
        echo '<input type="checkbox" name="borderless[related_posts]" class="switch related_posts_field" value="checked" ' . checked( $value, 'checked', false ) . '> ' . __( '', 'borderless' );
        echo '<span class="description">' . __( 'Related Posts.', 'borderless' ) . '</span>';
        
    }
    
}

new Borderless_Dashboard;