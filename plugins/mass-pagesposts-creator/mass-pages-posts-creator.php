<?php

/**
 * Plugin Name: Mass Pages/Posts Creator
 * Plugin URI: https://wordpress.org/plugins/mass-pagesposts-creator/
 * Description: Mass Pages/Posts Creator is a plugin which provide a simplest interface by which user can create multiple Pages/Posts at a time.
 * Version: 2.1.8
 * Author: theDotstore
 * Author URI: https://www.thedotstore.com
 * Text Domain: mass-pages-posts-creator
 * Domain Path: /languages/
 * 
 * WP tested up to:      6.4.1
 * Requires PHP:         5.6
 * Requires at least:    5.0
 */
// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
    die;
}
if ( !defined( 'MPPC_PLUGIN_VERSION' ) ) {
    define( 'MPPC_PLUGIN_VERSION', '2.1.8' );
}
if ( !defined( 'MPPC_PLUGIN_URL' ) ) {
    define( 'MPPC_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

if ( !function_exists( 'mppcp_fs' ) ) {
    // Create a helper function for easy SDK access.
    function mppcp_fs()
    {
        global  $mppcp_fs ;
        
        if ( !isset( $mppcp_fs ) ) {
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/freemius/start.php';
            $mppcp_fs = fs_dynamic_init( array(
                'id'              => '3481',
                'slug'            => 'mass-pages-posts-creator',
                'type'            => 'plugin',
                'public_key'      => 'pk_d515579f040a86a51afd9f721dfed',
                'is_premium'      => false,
                'premium_suffix'  => 'Premium',
                'has_addons'      => false,
                'has_paid_plans'  => true,
                'has_affiliation' => 'selected',
                'menu'            => array(
                'slug'       => 'mass-pages-posts-creator',
                'first-path' => 'admin.php?page=mass-pages-posts-creator',
                'contact'    => false,
                'support'    => false,
            ),
                'is_live'         => true,
            ) );
        }
        
        return $mppcp_fs;
    }
    
    // Init Freemius.
    mppcp_fs();
    // Signal that SDK was initiated.
    do_action( 'mppcp_fs_loaded' );
}

if ( !defined( 'MPPC_PLUGIN_NAME' ) ) {
    define( 'MPPC_PLUGIN_NAME', __( 'Mass Pages/Posts Creator For WordPress', 'mass-pages-posts-creator' ) );
}
if ( !defined( 'MPPC_PLUGIN_VERSION_LABEL' ) ) {
    define( 'MPPC_PLUGIN_VERSION_LABEL', __( 'Free Version', 'mass-pages-posts-creator' ) );
}
$menu_page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_SPECIAL_CHARS );
$menu_tab = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_SPECIAL_CHARS );
if ( isset( $menu_page ) && (!empty($menu_page) && $menu_page === 'mass-pages-posts-creator' || $menu_page === 'mass-pagesposts-creator' || $menu_page === 'mppc-get-started' || $menu_page === 'mppc-information') || isset( $menu_tab ) && (!empty($menu_tab) || $menu_tab === 'other_plugins') ) {
    add_action( 'admin_enqueue_scripts', 'mpc_load_my_script' );
}
if ( !function_exists( 'mpc_load_my_script' ) ) {
    function mpc_load_my_script()
    {
        wp_enqueue_script( 'jquery' );
        wp_enqueue_script( 'jquery-ui-dialog' );
        wp_enqueue_script(
            'mpc-select2-jquery',
            plugin_dir_url( __FILE__ ) . 'js/select2.min.js',
            array( 'jquery' ),
            false
        );
        wp_enqueue_style(
            'mpc-select2-css',
            plugin_dir_url( __FILE__ ) . 'css/select2.min.css',
            array(),
            'all'
        );
        wp_enqueue_style(
            'jquery-ui-min',
            plugin_dir_url( __FILE__ ) . 'css/jquery-ui.css',
            array(),
            'all'
        );
        wp_enqueue_script(
            'custom',
            plugin_dir_url( __FILE__ ) . 'js/custom.js',
            array(),
            'all'
        );
        wp_localize_script( 'custom', 'adminajax', array(
            'ajaxurl'   => admin_url( 'admin-ajax.php' ),
            'ajax_icon' => plugin_dir_url( __FILE__ ) . '/images/ajax-loader.gif',
        ) );
        wp_localize_script( 'custom', 'plugin_vars', array(
            'plugin_url' => plugin_dir_url( __FILE__ ),
        ) );
    }

}
$menu_page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_SPECIAL_CHARS );
if ( isset( $menu_page ) && !empty($menu_page) && ($menu_page === 'mass-pages-posts-creator' || $menu_page === 'mass-pagesposts-creator' || $menu_page === 'mppc-get-started' || $menu_page === 'mppc-information') ) {
    add_action( 'admin_enqueue_scripts', 'mpc_styles' );
}
add_action( 'admin_init', 'mpc_welcome_mass_page_creator_screen_do_activation_redirect' );
add_action( 'admin_print_footer_scripts', 'mpc_mass_page_creator_pointers_footer' );
add_action( 'wp_ajax_page_finder_ajax', 'mppc_page_finder_ajax' );
if ( !function_exists( 'convert_array_to_json' ) ) {
    function convert_array_to_json( $arr )
    {
        $filter_data = [];
        foreach ( $arr as $key => $value ) {
            $option = [];
            $option['name'] = $value;
            $option['attributes']['value'] = $key;
            $filter_data[] = $option;
        }
        return $filter_data;
    }

}
if ( !function_exists( 'mppc_page_finder_ajax' ) ) {
    function mppc_page_finder_ajax()
    {
        // Verify nonce
        check_ajax_referer( 'mass_pages_posts_creator_nonce', 'security' );
        // List pages
        $json = true;
        $request_value = filter_input( INPUT_GET, 'value', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $post_value = ( isset( $request_value ) ? sanitize_text_field( $request_value ) : '' );
        $query = new WP_Query( array(
            'post_parent' => 0,
            'post_type'   => "page",
            'post_status' => 'publish',
            's'           => $post_value,
            'showposts'   => -1,
        ) );
        $parent_pages_num = $query->found_posts;
        $options = [];
        $html = '';
        if ( $parent_pages_num > 0 ) {
            while ( $query->have_posts() ) {
                $query->the_post();
                $html .= '<option value="' . esc_attr( $query->post->ID ) . '">' . '#' . esc_html( $query->post->ID ) . ' - ' . esc_html( get_the_title( $query->post->ID ) ) . '</option>';
                $options[] = array( $query->post->ID, esc_html( $query->post->post_title ) );
            }
        }
        
        if ( $json ) {
            echo  wp_json_encode( $options ) ;
            wp_die();
        }
        
        echo  wp_kses( $html, mppc_allowed_html_tags() ) ;
        wp_die();
    }

}
if ( !function_exists( 'mpc_welcome_mass_page_creator_screen_do_activation_redirect' ) ) {
    function mpc_welcome_mass_page_creator_screen_do_activation_redirect()
    {
        if ( !get_transient( '_mass_page_post_creator_welcome_screen' ) ) {
            return;
        }
        // Delete the redirect transient
        delete_transient( '_mass_page_post_creator_welcome_screen' );
        // if activating from network, or bulk
        $is_activate = filter_input( INPUT_GET, 'activate-multi', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        if ( is_network_admin() || isset( $is_activate ) ) {
            return;
        }
        // Redirect to extra cost welcome  page
        wp_safe_redirect( add_query_arg( array(
            'page' => 'mass-pages-posts-creator',
        ), admin_url( 'admin.php' ) ) );
        exit;
    }

}
if ( !function_exists( 'mpc_mass_page_creator_pointers_footer' ) ) {
    function mpc_mass_page_creator_pointers_footer()
    {
        $admin_pointers = mpc_mass_page_creator_pointers_admin_pointers();
        ?>
        <script type="text/javascript">
                /* <![CDATA[ */
                (function( $ ) {
                    if ( 'undefined' !== typeof(jQuery().pointer) ) {
                <?php 
        foreach ( $admin_pointers as $pointer => $array ) {
            
            if ( $array['active'] ) {
                ?>
                        $( '<?php 
                echo  esc_html( $array['anchor_id'] ) ;
                ?>' ).pointer( {
                            content: '<?php 
                echo  esc_html( $array['content'] ) ;
                ?>',
                            position: {
                                edge: '<?php 
                echo  esc_html( $array['edge'] ) ;
                ?>',
                                align: '<?php 
                echo  esc_html( $array['align'] ) ;
                ?>'
                            },
                            close: function() {
                                $.post( ajaxurl, {
                                    pointer: '<?php 
                echo  esc_html( $pointer ) ;
                ?>',
                                    action: 'dismiss-wp-pointer'
                                } );
                            }
                        } ).pointer( 'open' );
                <?php 
            }
        
        }
        ?>
                    }
                })( jQuery );
                /* ]]> */
        </script>
        <?php 
    }

}
if ( !function_exists( 'mpc_mass_page_creator_pointers_admin_pointers' ) ) {
    function mpc_mass_page_creator_pointers_admin_pointers()
    {
        $dismissed = explode( ',', (string) get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );
        $version = '1_0';
        // replace all periods in 1.0 with an underscore
        $prefix = 'mpc_mass_page_creator_pointers_admin_pointers' . $version . '_';
        $new_pointer_content = '<h3>' . __( 'Welcome to  Mass Pages/Posts Creator', 'mass-pages-posts-creator' ) . '</h3>';
        $new_pointer_content .= '<p>' . __( 'Mass Pages/Posts Creator is a plugin which provide a simplest interface by which user can create multiple Pages/Posts at a time.', 'mass-pages-posts-creator' ) . '</p>';
        return array(
            $prefix . 'mpc_mass_page_creator_pointers_admin_pointers' => array(
            'content'   => $new_pointer_content,
            'anchor_id' => '#toplevel_page_mass-pages-posts-creator',
            'edge'      => 'left',
            'align'     => 'left',
            'active'    => !in_array( $prefix . 'mpc_mass_page_creator_pointers_admin_pointers', $dismissed, true ),
        ),
        );
    }

}
if ( !function_exists( 'mpc_styles' ) ) {
    function mpc_styles()
    {
        wp_enqueue_style(
            'select2-min-style',
            plugin_dir_url( __FILE__ ) . 'admin/css/select2.min.css',
            array(),
            'all'
        );
        wp_enqueue_style(
            'jquery-ui-min-style',
            plugin_dir_url( __FILE__ ) . 'admin/css/jquery-ui.min.css',
            array(),
            'all'
        );
        wp_enqueue_style(
            'jquery-timepicker-style',
            plugin_dir_url( __FILE__ ) . 'admin/css/jquery.timepicker.min.css',
            array(),
            'all'
        );
        wp_enqueue_style(
            'font-awesome-style',
            plugin_dir_url( __FILE__ ) . 'admin/css/font-awesome.min.css',
            array(),
            'all'
        );
        wp_enqueue_style(
            'style-css',
            plugin_dir_url( __FILE__ ) . 'css/style.css',
            array( 'wp-jquery-ui-dialog' ),
            '1.1.1',
            'all'
        );
        wp_enqueue_style(
            'main-style',
            plugin_dir_url( __FILE__ ) . 'admin/css/style.css',
            array(),
            'all'
        );
        wp_enqueue_style(
            'media-style',
            plugin_dir_url( __FILE__ ) . 'admin/css/media.css',
            array(),
            'all'
        );
    }

}
if ( !function_exists( 'mpc_pages_posts_creator' ) ) {
    function mpc_pages_posts_creator()
    {
        global  $GLOBALS ;
        if ( empty($GLOBALS['admin_page_hooks']['dots_store']) ) {
            add_menu_page(
                'DotStore Plugins',
                __( 'DotStore Plugins', 'mass-pages-posts-creator' ),
                'null',
                'dots_store',
                'dot_store_menu_page',
                MPPC_PLUGIN_URL . 'images/menu-icon.png',
                25
            );
        }
        add_submenu_page(
            'dots_store',
            'Mass Pages/Posts Creator',
            __( 'Mass Pages/Posts Creator for WordPress', 'mass-pages-posts-creator' ),
            'manage_options',
            'mass-pages-posts-creator',
            'mppc_admin_settings_page'
        );
        add_submenu_page(
            'dots_store',
            'Getting Started',
            __( 'Getting Started', 'mass-pages-posts-creator' ),
            'manage_options',
            'mppc-get-started',
            'mppc_get_started_page'
        );
        add_submenu_page(
            'dots_store',
            'Quick info',
            __( 'Quick info', 'mass-pages-posts-creator' ),
            'manage_options',
            'mppc-information',
            'mppc_information_page'
        );
    }

}
if ( !function_exists( 'mppc_remove_admin_submenus' ) ) {
    function mppc_remove_admin_submenus()
    {
        remove_submenu_page( 'dots_store', 'mppc-get-started' );
        remove_submenu_page( 'dots_store', 'mppc-information' );
    }

}
/**
 * Quick guide page
 *
 * @since    1.0.0
 */
if ( !function_exists( 'mppc_get_started_page' ) ) {
    function mppc_get_started_page()
    {
        require_once plugin_dir_path( __FILE__ ) . 'admin/partials/mppc-get-started-page.php';
    }

}
/**
 * Plugin information page
 *
 * @since    1.0.0
 */
if ( !function_exists( 'mppc_information_page' ) ) {
    function mppc_information_page()
    {
        require_once plugin_dir_path( __FILE__ ) . 'admin/partials/mppc-information-page.php';
    }

}
/**
 * Plugin information page
 *
 * @since    1.0.0
 */
if ( !function_exists( 'mppc_admin_settings_page' ) ) {
    function mppc_admin_settings_page()
    {
        require_once plugin_dir_path( __FILE__ ) . 'admin/partials/mppc-admin-settings-page.php';
    }

}
if ( !function_exists( 'mpc_ajax_action' ) ) {
    function mpc_ajax_action()
    {
        // Verify nonce
        check_ajax_referer( 'mass_pages_posts_creator_nonce', 'security' );
        // Create mass posts/pages
        $prefix_word = filter_input( INPUT_POST, 'prefix_word', FILTER_SANITIZE_SPECIAL_CHARS );
        $postfix_word = filter_input( INPUT_POST, 'postfix_word', FILTER_SANITIZE_SPECIAL_CHARS );
        $pages_content = filter_input( INPUT_POST, 'pages_content', FILTER_SANITIZE_SPECIAL_CHARS );
        $parent_page_id = filter_input( INPUT_POST, 'parent_page_id', FILTER_SANITIZE_SPECIAL_CHARS );
        $template_name = filter_input( INPUT_POST, 'template_name', FILTER_SANITIZE_SPECIAL_CHARS );
        $type = filter_input( INPUT_POST, 'type', FILTER_SANITIZE_SPECIAL_CHARS );
        $page_status = filter_input( INPUT_POST, 'page_status', FILTER_SANITIZE_SPECIAL_CHARS );
        $authors = filter_input( INPUT_POST, 'authors', FILTER_SANITIZE_SPECIAL_CHARS );
        $excerpt_content = filter_input( INPUT_POST, 'excerpt_content', FILTER_SANITIZE_SPECIAL_CHARS );
        $no_post_add = filter_input( INPUT_POST, 'no_post_add', FILTER_SANITIZE_SPECIAL_CHARS );
        $comment_status = filter_input( INPUT_POST, 'comment_status', FILTER_SANITIZE_SPECIAL_CHARS );
        $pages_list = filter_input( INPUT_POST, 'pages_list', FILTER_SANITIZE_SPECIAL_CHARS );
        $prefix_word = sanitize_text_field( wp_unslash( $prefix_word ) );
        $postfix_word = sanitize_text_field( wp_unslash( $postfix_word ) );
        $pages_content = htmlspecialchars_decode( $pages_content );
        $parent_page_id = sanitize_text_field( wp_unslash( $parent_page_id ) );
        $template_name = sanitize_text_field( wp_unslash( $template_name ) );
        $type = sanitize_text_field( wp_unslash( $type ) );
        $page_status = sanitize_text_field( wp_unslash( $page_status ) );
        $authors = sanitize_text_field( wp_unslash( $authors ) );
        $excerpt_content = sanitize_textarea_field( $excerpt_content );
        $no_post_add = sanitize_text_field( wp_unslash( $no_post_add ) );
        $comment_status = sanitize_text_field( wp_unslash( $comment_status ) );
        $pages_list = sanitize_textarea_field( $pages_list );
        $page_list = explode( ",", $pages_list );
        
        if ( $no_post_add === '' ) {
            $no_post_count = 1;
        } else {
            $no_post_count = $no_post_add;
        }
        
        $responsedata = [];
        foreach ( range( 1, $no_post_count ) as $i ) {
            foreach ( $page_list as $page_name ) {
                $my_post = array(
                    'post_title'     => $prefix_word . ' ' . $page_name . ' ' . $postfix_word,
                    'post_type'      => $type,
                    'post_content'   => $pages_content,
                    'post_author'    => $authors,
                    'post_parent'    => $parent_page_id,
                    'post_status'    => $page_status,
                    'post_excerpt'   => $excerpt_content,
                    'comment_status' => $comment_status,
                );
                $last_insert_id = wp_insert_post( $my_post );
                update_post_meta( $last_insert_id, 'post_number', $i );
                
                if ( 'draft' === $page_status ) {
                    $url = get_permalink( $last_insert_id ) . '&preview=true';
                } else {
                    
                    if ( 'auto-draft' === $page_status ) {
                        $url = '-';
                    } else {
                        $url = get_permalink( $last_insert_id );
                    }
                
                }
                
                $data = [];
                $data['id'] = esc_html( $last_insert_id );
                $data['pagename'] = esc_html( $page_name );
                $data['status'] = esc_html( "Ok" );
                
                if ( 'auto-draft' === $page_status || 'trash' === $page_status ) {
                    $data['url'] = __( "-", 'mass-pages-posts-creator' );
                } else {
                    $data['url'] = $url;
                }
                
                $responsedata[] = $data;
                add_post_meta( $last_insert_id, '_wp_page_template', $template_name );
            }
        }
        echo  wp_json_encode( $responsedata ) ;
        wp_die();
    }

}
add_action( 'wp_ajax_mpc_ajax_action', 'mpc_ajax_action' );
add_action( 'wp_ajax_nopriv_mpc_ajax_action', 'mpc_ajax_action' );
if ( !function_exists( 'mpc_activate' ) ) {
    function mpc_activate()
    {
        set_transient( '_mass_page_post_creator_welcome_screen', true, 30 );
    }

}
register_activation_hook( __FILE__, 'mpc_activate' );
if ( !function_exists( 'mpc_deactivate' ) ) {
    function mpc_deactivate()
    {
    }

}
register_deactivation_hook( __FILE__, 'mpc_deactivate' );
$menu_page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_SPECIAL_CHARS );
$menu_tab = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_SPECIAL_CHARS );
if ( isset( $menu_page ) && (!empty($menu_page) || $menu_page === 'mass-pages-posts-creator' || $menu_page === 'mass-pagesposts-creator') || isset( $menu_tab ) && (!empty($menu_tab) || $menu_tab === 'other_plugins') ) {
    add_filter( 'admin_footer_text', 'mppc_admin_footer_review' );
}
if ( !function_exists( 'mppc_admin_footer_review' ) ) {
    function mppc_admin_footer_review()
    {
        
        if ( mppcp_fs()->is__premium_only() || mppcp_fs()->is_trial() ) {
            if ( mppcp_fs()->can_use_premium_code() || mppcp_fs()->is_trial() ) {
                echo  sprintf( wp_kses( __( 'If you like <strong>Mass Pages/Posts Creator Pro</strong> plugin, please leave us ★★★★★ ratings on <a href="%1$s" target="_blank">DotStore</a>.', 'mass-pages-posts-creator' ), array(
                    'strong' => array(),
                    'a'      => array(
                    'href'   => array(),
                    'target' => 'blank',
                ),
                ) ), esc_url( 'https://www.thedotstore.com/mass-pages-posts-creator/#tab-reviews' ) ) ;
            }
        } else {
            echo  sprintf( wp_kses( __( 'If you like <strong>Mass Pages/Posts Creator</strong> plugin, please leave us ★★★★★ ratings on <a href="%1$s" target="_blank">DotStore</a>.', 'mass-pages-posts-creator' ), array(
                'strong' => array(),
                'a'      => array(
                'href'   => array(),
                'target' => 'blank',
            ),
            ) ), esc_url( 'https://wordpress.org/plugins/mass-pagesposts-creator/#reviews' ) ) ;
        }
        
        return '';
    }

}
if ( !function_exists( 'mppc_allowed_html_tags' ) ) {
    function mppc_allowed_html_tags( $tags = array() )
    {
        $allowed_tags = array(
            'a'        => array(
            'href'  => array(),
            'title' => array(),
            'class' => array(),
        ),
            'ul'       => array(
            'class' => array(),
        ),
            'li'       => array(
            'class' => array(),
        ),
            'div'      => array(
            'class' => array(),
            'id'    => array(),
        ),
            'select'   => array(
            'id'       => array(),
            'name'     => array(),
            'class'    => array(),
            'multiple' => array(),
            'style'    => array(),
        ),
            'input'    => array(
            'id'    => array(),
            'value' => array(),
            'name'  => array(),
            'class' => array(),
            'type'  => array(),
        ),
            'textarea' => array(
            'id'    => array(),
            'name'  => array(),
            'class' => array(),
        ),
            'option'   => array(
            'id'       => array(),
            'selected' => array(),
            'name'     => array(),
            'value'    => array(),
        ),
            'br'       => array(),
            'em'       => array(),
            'strong'   => array(),
        );
        if ( !empty($tags) ) {
            foreach ( $tags as $key => $value ) {
                $allowed_tags[$key] = $value;
            }
        }
        return $allowed_tags;
    }

}
require plugin_dir_path( __FILE__ ) . 'includes/mass-pages-posts-creator-user-feedback.php';
/**
 * Check Initialize plugin in case of WooCommerce plugin is missing.
 *
 * @since    1.0.0
 */
if ( !function_exists( 'mass_page_post_creator_initialize_plugin' ) ) {
    function mass_page_post_creator_initialize_plugin()
    {
        add_action( 'admin_menu', 'mpc_pages_posts_creator' );
        add_action( 'admin_head', 'mppc_remove_admin_submenus' );
        // Load the plugin text domain for translation.
        load_plugin_textdomain( 'mass-pages-posts-creator', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    }

}
add_action( 'plugins_loaded', 'mass_page_post_creator_initialize_plugin' );