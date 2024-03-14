<?php

class TBThemes_Demo_Content_Progress {


    private $config_data= array();
    private $tgmpa;

    function __construct()
    {
        if ( TBThemes_Demo_Content::php_support() ) {
            add_action('wp_ajax_demo_content__import', array($this, 'ajax_import'));
        }
    }

    /**
     * @see https://github.com/devinsays/edd-theme-updater/blob/master/updater/theme-updater.php
     */
    function ajax_import() {

        if ( ! class_exists( 'Merlin_WXR_Parser' ) ) {
            require DEMO_CONTENT_PATH. 'includes/merlin-wp/class-merlin-xml-parser.php' ;
        }

        if ( ! class_exists( 'Merlin_Importer' ) ) {
            require DEMO_CONTENT_PATH .'includes/merlin-wp/class-merlin-importer.php';
        }

        if ( ! current_user_can( 'import' ) ) {
            wp_send_json_error( __( "You have not permissions to import.", 'tbthemes-demo-import' ) );
        }

        $doing = isset( $_REQUEST['doing'] ) ? sanitize_text_field( $_REQUEST['doing'] ) : '';
        if ( ! $doing ) {
            wp_send_json_error( __( "No actions to do", 'tbthemes-demo-import' ) );
        }

        // Current item for import
        $current_item = isset( $_REQUEST['current_item'] ) ? $_REQUEST['current_item'] : false;

        $current_item = wp_parse_args( $current_item, array(
            'theme' => '',
            'tier' => '',
            'slug' => '',
            'name' => '',
            'xml_id' => '',
            'json_id' => ''
        ) );
        
        // Check if request contains all necessary parameters
        if ( ! $current_item || ! is_array( $current_item ) ||
             ! isset( $current_item['theme'] ) || ! $current_item['theme'] ||
             ! isset( $current_item['tier'] ) || ! $current_item['tier'] || 
             ! isset( $current_item['slug'] ) || ! $current_item['slug'] ) {
            wp_send_json_error( __( 'Not item selected for import', 'tbthemes-demo-import' ) );
        }

        $current_item_slug = false;
        $current_item_slug = sanitize_text_field( $current_item['slug'] );

        $demos = TBThemes_Demo_Content::get_instance()->dashboard->setup_demos();

        if ( $doing == 'checking_resources' ) {
            $file_data = $this->fetch_demo_content( $current_item );
            if ( ! $file_data || empty( $file_data ) ) {
                wp_send_json_error( sprintf( __( 'Demo data not found for <strong>%s</strong>. However you can import demo content by upload your demo files below.', 'tbthemes-demo-import' ) , $demos[ $current_item_slug ]['name'] ) );
            } else {
                wp_send_json_success( __( 'Demo data ready for import.', 'tbthemes-demo-import' ) );
            }
        }

        // Check if demo item exists
        if ( ! isset( $demos[ $current_item_slug ] ) ) {
            wp_send_json_error( __( 'This demo has not been found.', 'tbthemes-demo-import' ) );
        }

        $file_data = $this->fetch_demo_content( $current_item );
        if ( ! $file_data || empty( $file_data ) ) {
            wp_send_json_error( array(
                'type' => 'no-files',
                'msg' => __( 'Demo content files not found', 'tbthemes-demo-import' ),
                'files' => $file_data
            ) );
        }

        $transient_key = 'tbthemes_demo_content_'.$current_item_slug;

        // Import data
        $importer = new Merlin_Importer();
        $content = get_transient( $transient_key );
        if ( ! $content ) {
            $parser = new Merlin_WXR_Parser();
            $content = $parser->parse( $file_data['xml'] );
            set_transient( $transient_key, $content, DAY_IN_SECONDS );
        }

        if ( is_wp_error( $content ) ) {
            wp_send_json_error( __( 'Demo content empty', 'tbthemes-demo-import' ) );
        }

        // Setup config
        $option_config = get_transient( $transient_key.'-json' );
        if ( $option_config === false ) {
            if ( file_exists( $file_data['xml']  ) ) {
                global $wp_filesystem;
                WP_Filesystem();
                $file_contents = $wp_filesystem->get_contents( $file_data['json'] );
                $option_config = json_decode( $file_contents, true );
                set_transient( $transient_key.'-json',  $option_config, DAY_IN_SECONDS ) ;
            }
        }

        switch ( $doing ) {
            case 'import_users':
                if ( ! empty( $content['users'] ) ) {
                    $importer->import_users( $content['users'] );
                }
                break;
            case 'import_categories':
                if ( ! empty( $content['categories'] ) ) {
                    $importer->importTerms( $content['categories'] );
                }
                break;
            case 'import_tags':
                if ( ! empty( $content['tags'] ) ) {
                    $importer->importTerms( $content['tags'] );
                }
                break;
            case 'import_taxs':
                if ( ! empty( $content['terms'] ) ) {
                    $importer->importTerms( $content['terms'] );
                }
                break;
            case 'import_posts':
                if ( ! empty( $content['posts'] ) ) {
                    $importer->importPosts( $content['posts'] );
                }
                $importer->remapImportedData();
                do_action( 'demo_contents_import_posts_completed', $this, $importer );
                break;
            case 'import_theme_options':
                if ( isset( $option_config['options'] ) ){
                    $this->importOptions( $option_config['options'] );
                }
                // Setup Pages
                $processed_posts = get_transient('_wxr_imported_posts') ? get_transient('_wxr_imported_posts') : array();
                if ( isset( $option_config['pages'] ) ){
                    foreach ( $option_config['pages']  as $key => $id ) {
                        $val = isset( $processed_posts[ $id ] )  ? $processed_posts[ $id ] : null ;
                        update_option( $key, $val );
                    }
                }
                do_action( 'demo_contents_import_theme_options_completed', $this, $importer );
                break;
            case 'import_widgets':
                $this->config_data = $option_config;
                if ( isset( $option_config['widgets'] ) ){
                   // print_r( $option_config['widgets'] );
                    if ( ! isset( $this->config_data['widgets_config'] ) ) {
                        $this->config_data['widgets_config'] = array();
                    }
                    $importer->importWidgets( $option_config['widgets'], $this->config_data['widgets_config'] );
                    do_action( 'demo_contents_import_widgets_completed', $this, $importer );
                }
                break;
            case 'import_customize':
                if ( isset( $option_config['theme_mods'] ) ){
                    $importer->importThemeOptions( $option_config['theme_mods'] );
                    if ( isset( $option_config['customizer_keys'] ) ) {
                        foreach ( ( array ) $option_config['customizer_keys'] as $k => $list_key ) {
                            $this->resetup_repeater_page_ids( $k, $list_key );
                        }
                    }
                }
                do_action( 'demo_contents_import_customize_completed', $this, $importer );

                // Remove transient data if is live mod
                if ( ! defined( 'WP_DEBUG' ) || ! WP_DEBUG ) {
                    $importer->importEnd();
                    // Delete file
                    $file_key = 'tbthemes_demo_content_file_'.$current_item_slug;
                    $files = get_transient( $file_key );
                    if ( is_array( $files ) ) {
                        foreach ( $files as $file_id ) {
                            wp_delete_attachment( $file_id );
                        }
                    }
                    delete_transient( $transient_key );
                    delete_transient( $transient_key.'-json' );
                }
                break;

        } // end switch action

        // Inform theme about Import success
        $theme_domain = TBThemes_Demo_Content::get_instance()->dashboard->get_active_theme_slug();
        update_option($theme_domain.'-demo_content_imported', true);

        wp_send_json_success( );
    }


    function importOptions( $options ) {
        if ( empty( $options ) ) {
            return;
        }
        foreach ( $options as $option_name => $ops ) {
            update_option( $option_name, $ops );
        }
    }


    function resetup_repeater_page_ids( $theme_mod_name, $list_keys, $url ='', $option_type = 'theme_mod' ){

        $processed_posts = get_transient('_wxr_imported_posts') ? get_transient('_wxr_imported_posts') : array();
        if ( ! is_array( $processed_posts ) ) {
            $processed_posts = array();
        }

        // Setup service
        $data = get_theme_mod( $theme_mod_name );
        if (  is_string( $list_keys ) ) {
            switch( $list_keys ) {
                case 'media':
                    $new_data = $processed_posts[ $data ];
                    if ( $option_type == 'option' ) {
                        update_option( $theme_mod_name , $new_data );
                    } else {
                        set_theme_mod( $theme_mod_name , $new_data );
                    }
                    break;
            }
            return;
        }

        if ( is_string( $data ) ) {
            $data = json_decode( $data, true );
        }
        if ( ! is_array( $data ) ) {
            return false;
        }
        if ( ! is_array( $processed_posts ) ) {
            return false;
        }

        if ( $url ) {
            $url = trailingslashit( $this->config_data['home_url'] );
        }

        $home = home_url('/');


        foreach ($list_keys as $key_info) {
            if ($key_info['type'] == 'post' || $key_info['type'] == 'page') {
                foreach ($data as $k => $item) {
                    if (isset($item[$key_info['key']]) && isset ($processed_posts[$item[$key_info['key']]])) {
                        $data[$k][$key_info['key']] = $processed_posts[ $item[$key_info['key']] ];
                    }
                }
            } elseif ($key_info['type'] == 'media') {

                $main_key = $key_info['key'];
                $sub_key_id = 'id';
                $sub_key_url = 'url';
                if ($main_key) {

                    foreach ($data as $k => $item) {
                        if ( isset ($item[$main_key]) && is_array($item[$main_key])) {
                            if (isset ($item[$main_key][$sub_key_id]) ) {
                                $data[$k][$main_key][$sub_key_id] = $processed_posts[ $item[$main_key] [$sub_key_id] ];
                            }
                            if (isset ($item[$main_key][$sub_key_url])) {
                                $data[$k][$main_key][$sub_key_url] = str_replace($url, $home, $item[$main_key][$sub_key_url]);
                            }
                        }
                    }

                }
            }
        }

        if ( $option_type == 'option' ) {
            update_option( $theme_mod_name , $data );
        } else {
            set_theme_mod( $theme_mod_name , $data );
        }

    }


    /*
     * Fetch Demo content for given Demo item from Github REST API
     */
    function fetch_demo_content( $args = array() ) {

        $args = wp_parse_args( $args, array(
            'theme' => '',
            'tier' => '',
            'slug' => '',
            'xml_id' => '',
            'json_id' => ''
        ) );

        if ( $args['xml_id'] ) {
            return array(
                'xml' => get_attached_file( $args['xml_id'] ),
                'json' => get_attached_file( $args['json_id'] )
            );
        }

        $theme_name = $args['theme'];
        $demo_tier = $args['tier'];
        $demo_name = $args['slug'];
        if ( !$theme_name || !$demo_tier || !$demo_name ) {
            return false;
        }

        $repo = TBThemes_Demo_Content::get_github_repo();
        $remote_folder = 'https://raw.githubusercontent.com/'.$repo.'/master/';
        $sub_path = $theme_name.'/'.$demo_tier.'/'.$demo_name;

        $xml_file = false;
        $config_file = false;

        $files_data = get_transient( 'tbthemes_demo_content_file_'.$sub_path );
        
        // If have cache
        if ( ! empty( $files_data ) ) {
            $files_data = wp_parse_args( $files_data, array( 'xml' => '', 'json' => '' ) );
            $xml_file = get_attached_file( $files_data['xml'] );
            $config_file = get_attached_file( $files_data['json']  );
            if ( ! empty( $xml_file ) ) {
                return  array( 'xml' => $xml_file, 'json' => $config_file );
            }
        }

        $xml_url = $remote_folder . $sub_path . '/data.xml';
        $xml_file_name =  $sub_path .'/data.xml';
        $xml_id = TBThemes_Demo_Content::download_file( $xml_url, $xml_file_name );
        if ( $xml_id ) {
            $xml_file = get_attached_file( $xml_id );
        }

        $config_url = $remote_folder . $sub_path . '/config.json';
        $config_file_name = $sub_path .'/config.json';
        $config_id = TBThemes_Demo_Content::download_file( $config_url, $config_file_name );
        if ( $config_id ) {
            $config_file = get_attached_file( $config_id );
        }

        if ( ! empty( $xml_file ) ) {
            set_transient(
                'tbthemes_demo_content_file_'.$sub_path,
                array( 'xml' => $xml_id, 'json' => $config_id )
            );
            return  array( 'xml' => $xml_file, 'json' => $config_file );
        }

        return false;

    }

}
