<?php
    
    //Exit if directly acess
    defined('ABSPATH') or die('No script kiddies please!');
    
    
    function latest_posts_block_lite_assets()
    {
        wp_enqueue_style(
            'latest-posts-block-fontawesome-front',
            plugins_url('src/assets/fontawesome/css/all.css', dirname(__FILE__)),
            array(),
            filemtime(plugin_dir_path(__FILE__) . 'assets/fontawesome/css/all.css')
        );
    
        // Load the compiled styles.
        wp_enqueue_style(
            'latest-posts-block-frontend-block-style-css',
            plugins_url('dist/blocks.style.build.css', dirname(__FILE__)),
            array()
        );
    
    
    }
    
    add_action('init', 'latest_posts_block_lite_assets');
    
    if (!function_exists('latest_posts_block_lite_create_block')) {
        
        
        function latest_posts_block_lite_create_block()
        {
            
         
            // Register our block script with WordPress
            wp_enqueue_script(
                'latest-posts-block-js',
                LATEST_POSTS_BOX_LITE_PLUGIN_URL . 'dist/blocks.build.js',
                array('wp-blocks', 'wp-i18n', 'wp-element', 'wp-components', 'wp-editor')
            );
            
            
            // Register our block's editor-specific CSS
            if (is_admin()) :
                wp_enqueue_style(
                    'latest-posts-block-edit-style',
                    LATEST_POSTS_BOX_LITE_PLUGIN_URL . 'dist/blocks.editor.build.css',
                    array('wp-edit-blocks')
                );
            endif;
    
            wp_enqueue_style(
                'latest-posts-block-fontawesome',
                plugins_url('src/assets/fontawesome/css/all.css', dirname(__FILE__)),
                array(),
                filemtime(plugin_dir_path(__FILE__) . 'assets/fontawesome/css/all.css')
            );
    
            $taxonomies = get_categories();
            $txnm = array();
    
            foreach( $taxonomies as $type ) {
        
        
                $txnm[] = array(
                    'label' => $type->name,
                    'value' => $type->term_id,
                );
            }
    
           
            
            wp_localize_script(
                'latest-posts-block-js',
                'latest_posts_block_lite_globals',
                array(
                    'srcUrl' => untrailingslashit(plugins_url('/', LATEST_POSTS_BOX_LITE_BASE_DIR . '/dist/')),
                    'rest_url' => esc_url(rest_url()),
                    'taxonomies'=> json_encode($txnm)
                )
            );
        }
        
        add_action('enqueue_block_editor_assets', 'latest_posts_block_lite_create_block');
        
    }
    
    
    function latest_posts_block_lite_loader()
    {
        
        //Load Gutenberg Block php Files
        
        include(LATEST_POSTS_BOX_LITE_BASE_DIR . '/src/blocks/latest-posts-block/index.php');
        
        
    }
    
    add_action('plugins_loaded', 'latest_posts_block_lite_loader');
    
    
    add_filter( 'block_categories', 'latest_posts_block_lite_add_custom_block_category' );
    /**
     * Adds the Magic content  Blocks block category.
     *
     * @param array $categories Existing block categories.
     *
     * @return array Updated block categories.
     */
    function latest_posts_block_lite_add_custom_block_category( $categories ) {
        return array_merge(
            $categories,
            array(
                array(
                    'slug'  => 'latest-posts-block',
                    'title' => esc_html__( 'Latest Posts Block Lite', 'latest-posts-block-lite' ),
                ),
            )
        );
    }
   