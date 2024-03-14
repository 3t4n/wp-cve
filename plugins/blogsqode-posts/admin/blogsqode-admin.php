<?php
// it inserts the entry in the admin menu
add_action('admin_menu', 'blogsqode_create_menu_entry');

// creating the menu entries
function blogsqode_create_menu_entry() {
	// icon image path that will appear in the menu
 $icon = plugins_url('../images/setting-icon.png', __FILE__);
	// adding the main manu entry
 add_menu_page(esc_html__('Blogsqode', 'blogsqode'), esc_html__('Blogsqode', 'blogsqode'), 'edit_posts', 'main-blogsqode', 'blogsqode_show_main_page', esc_url($icon));
}

// function triggered in add_menu_page
function blogsqode_show_main_page() {
 include('main-blogsqode.php');
}

    /**
 * Enqueue a script with jQuery as a dependency.
 */
    
    add_action( 'admin_enqueue_scripts', 'add_blogsqode_scripts_func' );
    function add_blogsqode_scripts_func( $hook_suffix ) {
        $version = BLOGSQODE_VERSION;
    // first check that $hook_suffix is appropriate for your admin page
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_media();
        wp_enqueue_style( 'blogsqode-admin-styles', plugins_url( '/assets/css/blogsqode-admin.css', __FILE__ ), array(), $version, 'all' );
        wp_enqueue_script( 'blogsqode-admin-scripts', plugins_url('/assets/js/blogsqode-admin.js', __FILE__ ), array( 'wp-color-picker' ), $version );
    }

    add_action('init', 'include_files_func');
    add_action( 'activate_plugin', 'include_files_func' , 10, 2);
    function include_files_func(){

        include_once dirname( __FILE__ ) . '/class-blogsqode-admin-settings.php';

        $settings = Blogsqode_Admin_Settings::get_settings_pages();

        foreach ( $settings as $section ) {
            if ( ! method_exists( $section, 'get_settings' ) ) {
                continue;
            }
            $subsections = array_unique( array_merge( array( '' ), array_keys( $section->get_sections() ) ) );

            /**
             * We are using 'Blogsqode_Admin_Settings::get_settings' on purpose even thought it's deprecated.
             * See the method documentation for an explanation.
             */

            foreach ( $subsections as $subsection ) {
                foreach ( $section->get_settings( $subsection ) as $value ) {
                    if ( isset( $value['default'] ) && isset( $value['id'] ) ) {
                        $autoload = isset( $value['autoload'] ) ? (bool) $value['autoload'] : true;
                        add_option( $value['id'], $value['default'], '', ( $autoload ? 'yes' : 'no' ) );
                    }
                }
            }
        }

        ini_set("allow_url_include", 'On');
    }

    add_filter( 'plugin_action_links_blogsqode-posts/blogsqode-posts.php', 'blogsqode_settings_link' );
    function blogsqode_settings_link( $links ) {
        // Build and escape the URL.
        $url = esc_url( add_query_arg(
            'page',
            'main-blogsqode',
            get_admin_url() . 'admin.php'
        ) );
        // Create the link.
        $settings_link = "<a href='$url'>" . esc_html__( 'Settings', 'blogsqode' ) . '</a>';
        // Adds the link to the starting of the array.
        array_unshift(
            $links,
            $settings_link
        );
        return $links;
        }    //end blogsqode_settings_link()