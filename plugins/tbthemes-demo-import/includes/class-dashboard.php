<?php

class TBThemes_Demo_Content_Dashboard {

    private $cache_time = 3600;
    private $items = array();

    function __construct() {

        if ( TBThemes_Demo_Content::php_support() ) {
            add_action('admin_footer', array($this, 'render_preview_template'));
        }
        add_action('admin_enqueue_scripts', array($this, 'scripts'));

        add_action( 'bootstrapthemes_demo_import_tab', array( $this, 'render_wellcome' ), 10 );
        add_action( 'bootstrapthemes_demo_import_tab', array( $this, 'render_content' ), 35 );
    }

    function scripts ( $hook = '' ) {
        
        $load_script = false;
        if ( strpos( $hook, 'appearance_' ) === 0 ) {
            $load_script = true;
        }
        if ( ! $load_script ) {
            if ( strpos( $hook, 'demo_import' ) !== false ) {
                $load_script = true;
            }
        }

        wp_enqueue_style(
            'tbthemes-demo-import',
            DEMO_CONTENT_URL . 'assets/style.css',
            false
        );
        
        if ( ! TBThemes_Demo_Content::php_support() ) {
            return;
        }

        wp_enqueue_script( 'underscore' );
        wp_enqueue_script(
            'tbthemes-demo-import',
            DEMO_CONTENT_URL.'assets/importer.js',
            array( 'jquery', 'underscore' )
        );

        wp_enqueue_media();

        $run = isset( $_REQUEST['import_now'] ) && $_REQUEST['import_now'] == 1 ? 'run' : 'no';
        $themes = $this->setup_demos();
        $tgm_url = '';

        wp_localize_script( 'tbthemes-demo-import', 'tbthemes_demo_content_params', array(
            'ajaxurl'      		    => admin_url( 'admin-ajax.php' ),
            'theme_url'      		=> admin_url( 'themes.php' ),
            'wpnonce'      		    => wp_create_nonce( 'merlin_nonce' ),
            'home'                  => home_url('/'),
            'btn_done_label'        => __( 'All Done! View Site', 'tbthemes-demo-content' ),
            'failed_msg'            => __( 'Import Failed!', 'tbthemes-demo-content' ),
            'import_now'            => __( 'Start Import', 'tbthemes-demo-content' ),
            'importing'             => __( 'Importing...', 'tbthemes-demo-content' ),
            'checking_resource'     => __( 'Checking resource', 'tbthemes-demo-content' ),
            'confirm_leave'         => __( 'Importing demo content..., are you sure want to cancel ?', 'tbthemes-demo-content' )
        ) );

    }

    /**
     * Get active theme object
     */
    function get_active_theme() {
        $slug = get_option( 'template' );
        return wp_get_theme( $slug );
    }

    /**
     * Get active theme slug without -pro suffix
     */
    function get_active_theme_slug() {
        $slug = get_option( 'template' );
        return str_replace('-pro', '', $slug);
    }

    /**
     * Get Freemius helper name of the active theme
     */
    function get_active_theme_helper() {
        $theme_slug = $this->get_active_theme_slug();
        return 'fs_'.str_replace('-', '_', $theme_slug);
    }

    /**
     * Checks whether given theme is supported by this plugin
     */
    function is_theme_supported( $theme ) {
        $supported = false;
        $author = $theme->get('Author');
        if ( $author ) {
            $author = strtolower( sanitize_text_field( $author ) );
            if ($author === 'thebootstrapthemes') {
                $supported = true;
            } else {
                $supported = false;
            }
        }
        return $supported;
    }

    /**
     * Checks whether active theme has Freemius Pro plan activated
     */
    function is_theme_pro_plan() {
        $theme = $this->get_active_theme();
        if ( $this->is_theme_supported( $theme ) ) {
            $helper = $this->get_active_theme_helper();
            if ( function_exists( $helper ) ) {
                if (! $helper()->is_free_plan() ) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Render welcome message on the top of the tab
     */
    function render_wellcome() {
        if ( TBThemes_Demo_Content::php_support() ) {
            ?>
            <div class="demo-contents-import-box demo-contents-import-welcome">
                <h3><?php esc_html_e('Welcome to the Demo Importer!', 'tbthemes-demo-content'); ?></h3>
                <p>
                    <?php esc_html_e('Importing demo data is the simplest way to set up your theme. It lets you import the most important content (posts, pages, images, theme settings, etc.), instead of starting from scratch. During the import:', 'tbthemes-demo-content'); ?>
                </p>
                <ul>
                    <li><?php esc_html_e('All current posts, pages, categories, images, custom post types, and other data will remain unchanged.', 'tbthemes-demo-content'); ?></li>
                    <li><?php esc_html_e('Posts, pages, images, widgets and menus will get imported.', 'tbthemes-demo-content'); ?></li>
                    <li><?php esc_html_e('Choose your template, and click "Import Content". The process can take a couple of minutes.', 'tbthemes-demo-content'); ?></li>
                </ul>
                <p><?php esc_html_e('Notice: If your website already has content, remember to backup your database and WordPress files before importing demo data.', 'tbthemes-demo-content'); ?></p>
            </div>
            <?php
        }
    }

    /**
     * Render Demo listings
     */
    function render_content() {

        if ( ! TBThemes_Demo_Content::php_support() ) {
            $this->php_not_support_message();
            return ;
        }

        $this->setup_demos();
        if (!empty($this->items)) {
            $number_items = count( $this->items );
        } else {
            $number_items = 0;
        }
        ?>
        <div class="theme-browser rendered demo-content-themes-listing">
            <div class="themes wp-clearfix">
                <?php
                if ( $number_items > 0 ) {
                    foreach (( array ) $this->items as $item_slug => $item ) {
                        $this->render_item_card( $item );
                    }
                } else { ?>
                    <div class="demo-contents-no-themes">
                        <?php _e( 'No Demo Content was found!', 'tbthemes-demo-content' ); ?>
                    </div>
                <?php } ?>
            </div>
        </div>
        <?php
    }

    /**
     * Render card of given Demo item
     */
    function render_item_card( $item ) {
        ?>
        <div class="theme" tabindex="0">
            <div class="theme-screenshot">
                <img src="<?php echo esc_url($item['screenshot_url']); ?>" alt="<?php echo _e($item['name'], 'tbthemes-demo-import'); ?>">
                <?php if ( $item['tier'] === 'pro' ) : ?>
                <span class="premium-label"><?php _e('Premium', 'tbthemes-demo-import'); ?></span>
                <?php endif; ?>
            </div>
            <div class="theme-name"><?php echo esc_html($item['name']); ?></div>
            <?php if ( $item['tier'] === 'pro' ) {
                if ( $this->is_theme_pro_plan() ) { ?>
                <button type="button" class="more-details demo-content-import-button" data-name="<?php echo esc_attr( $item['name'] ); ?>" data-theme="<?php echo esc_attr( $item['theme'] ); ?>" data-tier="<?php echo esc_attr( $item['tier'] ); ?>" data-slug="<?php echo esc_attr( $item['slug'] ); ?>" data-demo-url="<?php echo esc_attr( $item['demo_url'] ); ?>"><span class="dashicons dashicons-download"></span><?php _e('Import Content', 'tbthemes-demo-import'); ?></a>
                <?php } else { ?>
                <button type="button" class="more-details demo-content-import-button" data-name="<?php echo esc_attr( $item['name'] ); ?>" data-theme="<?php echo esc_attr( $item['theme'] ); ?>" data-tier="<?php echo esc_attr( $item['tier'] ); ?>" data-slug="<?php echo esc_attr( $item['slug'] ); ?>" data-product-url="<?php echo esc_attr( $item['product_url'] ); ?>"><span class="dashicons dashicons-awards"></span><?php _e('Upgrade to Pro', 'tbthemes-demo-import'); ?></a>
                <?php } ?>
            <?php } else { ?>
            <button type="button" class="more-details demo-content-import-button" data-name="<?php echo esc_attr( $item['name'] ); ?>" data-theme="<?php echo esc_attr( $item['theme'] ); ?>" data-tier="<?php echo esc_attr( $item['tier'] ); ?>" data-slug="<?php echo esc_attr( $item['slug'] ); ?>" data-demo-url="<?php echo esc_attr( $item['demo_url'] ); ?>"><span class="dashicons dashicons-download"></span><?php _e('Import Content', 'tbthemes-demo-import'); ?></a>
            <?php } ?>
        </div>
        <?php
    }

    /**
     * Render preview template of given Demo item
     */
    function  render_preview_template() {
        ?>
        <script id="template-demo-content--preview" type="text/html">
            <div id="tbthemes-demo-content--preview">

                <span type="button" class="demo-contents-collapse-sidebar button" aria-expanded="true">
                    <span class="collapse-sidebar-arrow"></span>
                    <span class="collapse-sidebar-label"><?php _e( 'Collapse', 'tbthemes-demo-import' ); ?></span>
                </span>

                <div id="demo-contents-sidebar">
                    <span class="demo-contents-close"><span class="screen-reader-text"><?php _e( 'Close', 'tbthemes-demo-import' ); ?></span></span>

                    <div id="demo-contents-sidebar-topbar">
                        <span class="demo-name">{{ data.name }}</span>
                    </div>

                    <div id="demo-contents-sidebar-content">
                        <# if ( data.screenshot_url ) { #>
                            <div class="demo-contents--theme-thumbnail"><img src="{{ data.screenshot_url }}" alt="{{ data.name }}"/></div>
                        <# } #>

                        <div class="demo-contents--activate-notice resources-not-found demo-contents-hide">
                            <p class="demo-contents--msg"></p>
                            <div class="demo-contents---upload">
                                <p><button type="button" class="demo-contents--upload-xml button-secondary"><?php _e( 'Upload XML file .xml', 'tbthemes-demo-import' ); ?></button></p>
                                <p><button type="button" class="demo-contents--upload-json button-secondary"><?php _e( 'Upload config file .json or .txt', 'tbthemes-demo-import' ); ?></button></p>
                            </div>
                        </div>

                        <div class="demo-contents-import-progress">

                            <div class="demo-contents--step demo-contents-import-users demo-contents--waiting">
                                <div class="demo-contents--step-heading"><?php _e( 'Import Users', 'tbthemes-demo-import' ); ?></div>
                                <div class="demo-contents--status demo-contents--waiting"></div>
                                <div class="demo-contents--child-steps"></div>
                            </div>

                            <div class="demo-contents--step demo-contents-import-categories demo-contents--waiting">
                                <div class="demo-contents--step-heading"><?php _e( 'Import Categories', 'tbthemes-demo-import' ); ?></div>
                                <div class="demo-contents--status demo-contents--completed"></div>
                                <div class="demo-contents--child-steps"></div>
                            </div>

                            <div class="demo-contents--step demo-contents-import-tags demo-contents--waiting">
                                <div class="demo-contents--step-heading"><?php _e( 'Import Tags', 'tbthemes-demo-import' ); ?></div>
                                <div class="demo-contents--status demo-contents--completed"></div>
                                <div class="demo-contents--child-steps"></div>
                            </div>

                            <div class="demo-contents--step demo-contents-import-taxs demo-contents--waiting">
                                <div class="demo-contents--step-heading"><?php _e( 'Import Taxonomies', 'tbthemes-demo-import' ); ?></div>
                                <div class="demo-contents--status demo-contents--waiting"></div>
                                <div class="demo-contents--child-steps"></div>
                            </div>

                            <div class="demo-contents--step  demo-contents-import-posts demo-contents--waiting">
                                <div class="demo-contents--step-heading"><?php _e( 'Import Posts & Media', 'tbthemes-demo-import' ); ?></div>
                                <div class="demo-contents--status demo-contents--waiting"></div>
                                <div class="demo-contents--child-steps"></div>
                            </div>

                            <div class="demo-contents--step demo-contents-import-theme-options demo-contents--waiting">
                                <div class="demo-contents--step-heading"><?php _e( 'Import Options', 'tbthemes-demo-import' ); ?></div>
                                <div class="demo-contents--status demo-contents--waiting"></div>
                                <div class="demo-contents--child-steps"></div>
                            </div>

                            <div class="demo-contents--step demo-contents-import-widgets demo-contents--waiting">
                                <div class="demo-contents--step-heading"><?php _e( 'Import Widgets', 'tbthemes-demo-import' ); ?></div>
                                <div class="demo-contents--status demo-contents--waiting"></div>
                                <div class="demo-contents--child-steps"></div>
                            </div>

                            <div class="demo-contents--step  demo-contents-import-customize demo-contents--waiting">
                                <div class="demo-contents--step-heading"><?php _e( 'Import Customize Settings', 'tbthemes-demo-import' ) ?></div>
                                <div class="demo-contents--status demo-contents--waiting"></div>
                                <div class="demo-contents--child-steps"></div>
                            </div>
                        </div>

                    </div><!-- /.demo-contents-sidebar-content -->

                    <div id="demo-contents-sidebar-footer">
                        <a href="#" class="demo-contents--import-now button button-primary"><?php _e( 'Import Now', 'tbthemes-demo-import' ); ?></a>
                    </div>

                </div>
                <div id="demo-contents-viewing">
                    <iframe src="{{ data.demo_url }}"></iframe>
                </div>
            </div>
        </script>
        <?php
    }

    /**
     * Get available Demo items to list 
     */
    function setup_demos() {

        $cache = wp_cache_get('tbthemes_demo_content_get_demos');
        //wp_cache_delete('tbthemes_demo_content_get_demos');
        if ( $cache !== false ) {
            $this->items = $cache;
        }
        // If already setup
        if ( ! empty( $this->items) ) {
            // return $this->items;
        }

        $demos = array();
        $active_theme = $this->get_active_theme();
        $active_theme_slug = $this->get_active_theme_slug();
        if ( $this->is_theme_supported( $active_theme ) ) {
            $demos = $this->get_demos( $active_theme_slug );
        }

        $this->items = $demos;
        wp_cache_set( 'tbthemes_demo_content_get_demos', $this->items );

        return $this->items;
    }

    /**
     * Fetch list of Demo items for given theme and tier from Github REST API
     * Return parsed body of the API response
     */
    function fetch_demo_items( $repo_name, $theme_slug, $demo_tier ) {

        $url = sprintf(
            'https://api.github.com/repos/%1$s/contents/%2$s/%3$s',
            $repo_name,
            $theme_slug,
            $demo_tier
        );

        // oAuth tokens
        $url_token = add_query_arg( array(
            'client_id' => '8bfb61825ddd1a3f589f',
            'client_secret' => 'c35a907bb3894200449c938ab1f9a18041569959',
        ), $url );

        $res = wp_remote_get( $url_token, array() );
        if ( wp_remote_retrieve_response_code( $res ) !== 200 ) {
            $res = wp_remote_get( $url, array() );
            if ( wp_remote_retrieve_response_code( $res ) !== 200 ) {
                return array();
            }
        }

        $body = wp_remote_retrieve_body( $res );

        return json_decode( $body, true );
    }

    /**
     * Get Demo items of given theme
     */
    function get_demos( $theme_slug ) {

        $repo_name = TBThemes_Demo_Content::get_github_repo();
        $key = $repo_name.'/'.$theme_slug;
        //delete_transient($key);
        if ( $this->cache_time > 0 ) {
            $cache = get_transient($key);
            if (false !== $cache) {
                return $cache;
            }
        }

        $files = array();

        $files_free = $this->fetch_demo_items($repo_name, $theme_slug, 'free');
        if ( $files_free ) {
            $files = array_merge($files, $files_free);
        }

        $files_pro = $this->fetch_demo_items($repo_name, $theme_slug, 'pro');
        if ( $files_pro ) {
            $files = array_merge($files, $files_pro);
        }

        $demos = array();
        if ( !$files ) {
            set_transient( $key, $demos, $this->cache_time );
            return false;
        }

        foreach ( $files as $file ) {
            if ( $file['type'] != 'dir' ) {
                continue;
            }
            $path = $file['path'];
            $slug = $file['name'];
            $name = $file['name'];
            $name = str_replace( '-', ' ', $name );
            $name = ucwords( $name );
            $tier = strpos($path, '/pro/') ? 'pro' : 'free';
            $screenshot_url = 'https://raw.githubusercontent.com/'.$repo_name.'/master/'.$path.'/screenshot.png';
            $demo = array(
                'theme'             => $theme_slug,
                'tier'              => $tier,
                'slug'              => $slug,
                'name'              => $name,
                'screenshot_url'    => $screenshot_url,
                'product_url'       => 'https://thebootstrapthemes.com/'.$theme_slug.'/#free-vs-pro',
                'demo_url'          => 'https://thebootstrapthemes.com/previews/'.$slug.'/'
            );
            $demos[ $slug ] = $demo;
        }

        set_transient( $key, $demos, $this->cache_time ); // cache 2 hours

       return $demos;
    }

    /**
     * Render message of unsupported PHP
     */
    function php_not_support_message() {
        ?>
        <div class="demo-contents-notice"><?php
            printf( __( "PHP version is not support. You're using PHP version %s, please upgrade to version 5.6.20 or higher.",  'tbthemes-demo-import' ), PHP_VERSION );
            ?></div>
        <?php
    }
}
