<?php
/**
 * Plugin Name: CSS Optimizer - Remove Unused CSS
 * Plugin URI: https://zippisite.com
 * Description: Cleans up and removes unused CSS from your website. Also generates Critical CSS to improve PageSpeed Score.
 * Version: 1.7
 * Author: ZippiSite
 * Text Domain: css_optimizer
 * License: GPLv2
 */

if ( ! defined( 'CSSOPTIMIZER_CACHE_URL' ) ) {
    if ( function_exists( 'domain_mapping_siteurl' ) ) {
        $site_url = domain_mapping_siteurl( get_current_blog_id() );
    } else {
        $site_url = site_url();
    }
    if ( function_exists( 'get_original_url' ) ) {
        $content_url = str_replace( get_original_url( $site_url ), $site_url, content_url() );
    } else {
        $content_url = content_url();
    }

    if ( is_multisite() ) {
        $blog_id = get_current_blog_id();
        define( 'CSSOPTIMIZER_CACHE_URL', $content_url . '/cache/cssoptimizer/' . $blog_id . '/' );
    } else {
        define( 'CSSOPTIMIZER_CACHE_URL', $content_url . '/cache/cssoptimizer/' );
    }
}

if ( ! defined( 'CSSOPTIMIZER_CACHE_DIR' ) ) {
    if ( is_multisite() ) {
        $blog_id   = get_current_blog_id();
        define( 'CSSOPTIMIZER_CACHE_DIR', WP_CONTENT_DIR . '/cache/cssoptimizer/' . $blog_id . '/' );
    } else {
        define( 'CSSOPTIMIZER_CACHE_DIR', WP_CONTENT_DIR . '/cache/cssoptimizer/' );
    }
}

// add wordpress options and menu
function cssoptimizer_settings() {
    register_setting('cssoptimizer_options', 'cssoptimizer_options_enable');
    register_setting('cssoptimizer_options', 'cssoptimizer_options_token');
    register_setting('cssoptimizer_options', 'cssoptimizer_options_generateccss');
    register_setting('cssoptimizer_options', 'cssoptimizer_options_safelist');
    register_setting('cssoptimizer_options', 'cssoptimizer_options_limitpagepost');
    register_setting('cssoptimizer_options', 'cssoptimizer_options_exclude');
    register_setting('cssoptimizer_options', 'cssoptimizer_options_exclude_css');
    register_setting('cssoptimizer_options', 'cssoptimizer_cache_clean');

    if ( is_admin() && cssop_get_option( 'cssoptimizer_cache_clean' ) ) {
        cssop_write_log('[' . date('m/d/Y h:i:s a', time()) . '] Begin clear cache.');
        if (is_plugin_active( 'autoptimize/autoptimize.php' ))
            autoptimizeCache::clearall();

        if (is_plugin_active( 'wp-rocket/wp-rocket.php' )) {
            // Clear all caching files.
            rocket_clean_domain();
        }

        if (is_plugin_active( 'wp-fastest-cache/wpFastestCache.php' )) {
            do_action('wp_ajax_wpfc_delete_cache');
        }

        if (is_plugin_active( 'sg-cachepress/sg-cachepress.php' )) {
            do_action('wp_ajax_admin_bar_purge_cache');
        }

        if (is_plugin_active( 'wp-optimize/wp-optimize.php' )) {
            wpo_cache_flush();
        }    
    
        if (is_plugin_active( 'w3-total-cache/w3-total-cache.php' )) {    
            do_action( 'w3tc_flush_all' );
        }

        $path = CSSOPTIMIZER_CACHE_DIR;
        $files = glob($path."/*.css");
        if (!empty($files)) {
            foreach ( $files as $file ) {
                unlink( $file ); 
            }
        }

        // clear log file
        file_put_contents(CSSOPTIMIZER_CACHE_DIR . 'css_optimizer_log.txt', "Clear log...");
        cssop_write_log("");

        cssop_update_option( 'cssoptimizer_cache_clean', 0 );
        cssop_write_log('[' . date('m/d/Y h:i:s a', time()) . '] Finish clear cache.');

        cssop_update_option( 'cssoptimizer_first_time_guide', 0 );
    }

    if ( !file_exists( CSSOPTIMIZER_CACHE_DIR ) ) {
        mkdir( CSSOPTIMIZER_CACHE_DIR, 0755, true );
    }

    if ( !file_exists( CSSOPTIMIZER_CACHE_DIR . 'css_optimizer_log.txt' ) ) {
        cssop_write_log("");
    }

    if ( cssop_get_option('cssoptimizer_activation_redirect', false) ) {
        delete_option('cssoptimizer_activation_redirect');
        if (is_multisite() && is_network_admin()) {
        } else {
            exit( wp_redirect("options-general.php?page=cssoptimizer") );
        }
    }

    if ( !file_exists( CSSOPTIMIZER_CACHE_DIR . 'css_job_queue.txt' ) ) {
        touch(CSSOPTIMIZER_CACHE_DIR . 'css_job_queue.txt');
    }
}

// set default value when activating 
function cssoptimizer_activation(){
    do_action( 'cssoptimizer_default_options' );

    add_option('cssoptimizer_activation_redirect', true);

    if ( empty( cssop_get_option('cssoptimizer_first_time_guide') ) ) {
        add_option('cssoptimizer_first_time_guide', true);
    }

    if ( !cssop_get_option('cssoptimizer_first_time_guide') ) {
        cssop_update_option( 'cssoptimizer_first_time_guide', 1 );
    }
}

// add css file
function cssop_setting_up_css() {
    wp_register_style( 'cssoptimizer-styles', plugin_dir_url( __FILE__ ) . 'css/css_optimizer.css' );
    wp_enqueue_style( 'cssoptimizer-styles' );

    wp_enqueue_script( 'jquery-ui-accordion' );
}

// check if CSS Optimizer is active for network
function is_cssop_active_for_network() {
    static $_is_cssop_active_for_network = null;
    if ( null === $_is_cssop_active_for_network ) {
        if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
            include_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        if ( is_plugin_active_for_network( 'css_optimizer/css_optimizer.php' ) || is_plugin_active_for_network( 'css_optimizer-1.7.2/css_optimizer.php' ) ) {
            $_is_cssop_active_for_network = true;
        } else {
            $_is_cssop_active_for_network = false;
        }
    }
    return $_is_cssop_active_for_network;
}

// assign default value for option when installing plugin.
function cssoptimizer_default_values(){
    if(empty(cssop_get_option('cssoptimizer_options_safelist'))) {	
		cssop_update_option('cssoptimizer_options_safelist', 'slide|menu|nav|arrow|rs-|owl');
	}

    if(empty(cssop_get_option('cssoptimizer_options_exclude_css'))) {	
		cssop_update_option('cssoptimizer_options_exclude_css', 'wp-content/uploads/, admin-bar.min.css, dashicons.min.css');
	}
}

function cssoptimizer_add_settings_page() {
    add_options_page( __( 'CSS Optimizer Options', 'cssoptimizer' ), 'CSS Optimizer', 'manage_options', 'cssoptimizer', 'cssoptimizer_settings_page' );
    add_submenu_page(
        null,
        __( 'CSS Optimizer Job Queue', 'cssoptimizer' ),
        'CSS Optimizer',
        'manage_options',
        'cssoptimizer_jobqueue',
        'cssoptimizer_job_queue_page'
    );
    add_submenu_page(
        null,
        __( 'CSS Optimizer Free Support', 'cssoptimizer' ),
        'CSS Optimizer',
        'manage_options',
        'cssoptimizer_support',
        'cssoptimizer_support_page'
    );
}

// setting page for default
function cssoptimizer_settings_page() {
    ?>
    <div class="wrap">
    <h1><?php _e( 'CSS Optimizer', 'cssoptimizer_options' ); ?></h1>
    <?php echo wp_kses_post( cssop_show_first_time_guide() ); ?>
    <?php echo wp_kses_post( cssop_admin_tabs() ); ?>
    <div class="cssop-main">
    <form action="<?php echo admin_url( 'options.php' ); ?>" method="post">
    <?php settings_fields( 'cssoptimizer_options' ); ?>
    <h2 class="itemTitle"><?php _e( 'CSS Optimizer Options', 'cssoptimizer_options' ); ?></h2>
    <?php echo wp_kses_post( cssop_show_job_queue_status() ); ?>
    <table class="form-table">
    <tr valign="top">
    <th scope="row"><?php _e( 'Enable', 'cssoptimizer_options' ); ?></th>
    <td><input type="checkbox" id="cssoptimizer_options_enable" name="cssoptimizer_options_enable" <?php echo cssop_get_option( 'cssoptimizer_options_enable' ) ? 'checked="checked" ' : ''; ?>/>
    </tr>
    <tr valign="top">
    <th scope="row"><?php _e( 'Token', 'cssoptimizer_options' ); ?></th>
    <td><label><input type="text" style="width:100%;" name="cssoptimizer_options_token" value="<?php echo trim( esc_html( cssop_get_option( 'cssoptimizer_options_token' ) ) ) ; ?>"/></label>
    <?php echo wp_kses_post( cssop_validate_token( cssop_get_option( 'cssoptimizer_options_token' ) ) ) ; ?></td>
    </tr>
    <tr valign="top">
    <th scope="row"><?php _e( 'Generate Critical CSS', 'cssoptimizer_options' ); ?></th>
    <td><input type="checkbox" id="cssoptimizer_options_generateccss" name="cssoptimizer_options_generateccss" <?php echo cssop_get_option( 'cssoptimizer_options_generateccss' ) ? 'checked="checked" ' : ''; ?> />
    </tr>
    <tr valign="top">
    <th scope="row"><?php _e( 'Safelist', 'cssoptimizer_options' ); ?><?php _e( '</br><i><a href="https://zippisite.com/css-optimizer-safelist/">What is safelist?</a></i>' ); ?></th>
    <td><label><textarea rows="10" cols="10" style="width:100%;" name="cssoptimizer_options_safelist"><?php echo trim( esc_textarea( cssop_get_option( 'cssoptimizer_options_safelist' ) ) ) ; ?></textarea></label></td>
    </tr>
    <tr valign="top" id="limit_page_post">
    <th scope="row"><?php _e( 'Limit to pages / posts', 'cssoptimizer_options' ); ?><?php _e( '</br><i><a href="https://zippisite.com/css-optimizer-limit-to-exclude/">What is limit/exclude to pages/posts?</a></i>' ); ?></th>
    <td><label><textarea rows="10" id="cssoptimizer_options_limitpagepost" cols="10" style="width:100%;" name="cssoptimizer_options_limitpagepost"><?php echo trim( esc_textarea( cssop_get_option( 'cssoptimizer_options_limitpagepost' ) ) ) ; ?></textarea></label></td>
    </tr>
    <tr valign="top" class="exclude_page_post">
    <th scope="row"><?php _e( 'Exclude pages / posts', 'cssoptimizer_options' ); ?></th>
    <td><label><textarea rows="10" id="cssoptimizer_options_exclude" cols="10" style="width:100%;" name="cssoptimizer_options_exclude"><?php echo trim( esc_textarea( cssop_get_option( 'cssoptimizer_options_exclude' ) ) ) ; ?></textarea></label></td>
    </tr>
    <tr valign="top" class="exclude_css">
    <th scope="row"><?php _e( 'Exclude CSS from CSS Optimizer', 'cssoptimizer_options' ); ?></th>
    <td><label><textarea rows="10" id="cssoptimizer_options_exclude_css" cols="10" style="width:100%;" name="cssoptimizer_options_exclude_css"><?php echo trim( esc_textarea( cssop_get_option( 'cssoptimizer_options_exclude_css' ) ) ) ; ?></textarea></label></td>
    </tr>
    </table>
    <input type="submit" class="button button-primary" name="cssoptimizer_cache_clean" value="<?php _e( 'Save Changes', 'cssoptimizer_options' ); ?>" />
    </form>
    <br>
    <input type="button" onclick="location.href = '<?php echo esc_url(CSSOPTIMIZER_CACHE_URL . 'css_optimizer_log.txt'); ?>'" class="button button-secondary" value="<?php _e( 'View Logs', 'cssoptimizer_options' ); ?>" />
    </div>
    <div class="cssop-faq">
    <h2 class="itemTitle"><?php _e( 'CSS Optimizer FAQs', 'cssoptimizer_options' ); ?></h2>
    <div id="cssop_accordion">
    <h3>Is this compatible with CloudFlare?</h3>
    <div>
    <p>
    Yes, but make sure you clear the cache. For maximum compatibility with CloudFlare, make sure you turn off speed optimization features such as Auto Minify and Rocket Loader.
    </p>
    </div>
    <h3>Is this compatible with NitroPack?</h3>
    <div>
    <p>
    No. The fact is, if you are already using NitroPack, you do not need our plugin as they have conflicting features. If you want to try our plugin, please make sure you deactivate NitroPack first.
    </p>
    </div>
    <h3>Why am I not seeing an improved Google PageSpeed Score?</h3>
    <div>
    <p>
    Note that there can be a delay of around 2 - 3 minutes in running the &quot;Remove Unused CSS&quot; and &quot;Generate Critical CSS&quot; tasks. As we have hundreds of API requests hitting our server every minute, we can't possibly run them all at the same time. So optimization tasks are being queued to wait for their turn. So please re-run PageSpeed Insights tests after 3 minutes.
    </p>
    </div>
    <h3>Why is my site still running slow after using this plugin?</h3>
    <div>
    <p>
    Note that this plugin only solves 2 problems - &quot;Remove Unused CSS&quot; and &quot;Generate Critical CSS&quot;. Both of these issues are frequently flagged in Google PageSpeed Insights. However, they are not the only issues that can make your site slow. If you want to find out what other issues are slowing down your site, please run a free WordPress Speed Analysis here: <a href="https://zippisite.com" target="_blank">https://zippisite.com</a>
    </p>
    </div>
    <h3>What is a job queue?</h3>
    <div>
    <p>
    Referring to the question &quot;Why am I not seeing an improved Google PageSpeed Score&quot; above, a job queue is added for every page where the CSS will be optimized. A job queue will be in pending state for around 2 - 3 minutes, before it'll get run.
    </p>
    </div>
    <h3>Do I need Cron Jobs for this plugin to work properly?</h3>
    <div>
    <p>
    Yes you need. This plugin relies on Cron Jobs to run scheduled tasks.
    </p>
    </div>
    <h3>I have inspected the CSS files, I am not seeing much difference in the optimized CSS, why is that?</h3>
    <div>
    <p>
    There can be a few reasons:
    </p>
    <p>
    1) The job queue is pending to be processed. Please refer to the questions &quot;Why am I not seeing an improved Google PageSpeed Score&quot; and &quot;What is a job queue&quot;.
    </p>
    <p>
    2) CDNs are caching your CSS files. If you are behind CDN such as CloudFlare, CloudFront, StackPath and etc, or you are using Cloud Hosting that comes with their own CDN, it can happend that the CDN is caching your CSS files. So please make sure you clear your CDN's cache for the optimized files to show.
    </p>
    <p>
    3) Job queue not able to be processed. If there are any coding error in your CSS files, our API will fail to process your request. Check the &quot;Job Queue&quot; tab to see if there are any failed requests. Feel free to  contact the support at <a href="mailto:cssopsupport@panorazzi.com" target="_blank">cssopsupport@panorazzi.com</a> for further investigation.
    </p>
    </div>
    <h3>My site is broken after using CSS Optimizer</h3>
    <div>
    <p>
    Please disable the plugin first, and get in touch with the support for further investigation. You may e-mail the support at <a href="mailto:cssopsupport@panorazzi.com" target="_blank">cssopsupport@panorazzi.com</a>.
    </p>
    </div>
    <h3>Why does mine CSS optimizer hit usage limits so fast?</h3>
    <div>
    <p>
    By default, CSS Optimizer will run on all front-end pages of your website. In most of the cases, 1 page = 1 link = 1 usage. If you have 500 pages, then will be 500 usage at once. Using either <strong>Limit to pages or posts</strong> or <string>Exclude pages or posts</strong>, you can tell CSS Optimizer to run on selected pages only. Please check <a href="https://zippisite.com/css-optimizer-limit-to-exclude/" target="_blank">this link</a> for further information.
    </p>
    </div>
    <h3>Why does CSS optimizer has a large numbers of failed calls?</h3>
    <div>
    <p>
    Please contact support at <a href="mailto:cssopsupport@panorazzi.com" target="_blank">cssopsupport@panorazzi.com</a> for further investigation.
    </p>
    </div>
    <h3>I want a speedy WordPress site, and I am tired of doing it myself. Can you help?</h3>
    <div>
    <p>
    Sure! Please contact us at <a href="mailto:cssopsupport@panorazzi.com" target="_blank">cssopsupport@panorazzi.com</a>, we can discuss!
    </p>
    </div>
    
    </div>    
    </div>

    <script type="text/javascript">
        jQuery(document).ready(function() {
            if ( jQuery("#cssoptimizer_options_enable").is(':checked') ) {
                jQuery(".form-table").find('input, textarea, checkbox').attr("disabled", false);
            } else {
                jQuery(".form-table").find('input, textarea, checkbox').attr("disabled", true);
                jQuery("#cssoptimizer_options_enable").attr("disabled", false);
            }

            jQuery('#cssoptimizer_options_enable').change(function() {
                if(this.checked) {
                    jQuery(".form-table").find('input, textarea, checkbox').attr("disabled", false);

                    if ( jQuery.trim(jQuery( "#cssoptimizer_options_limitpagepost" ).val()).length > 0 ) { 
                        jQuery("#cssoptimizer_options_exclude").attr('disabled',true);
                    }

                    if ( jQuery.trim(jQuery( "#cssoptimizer_options_exclude" ).val()).length > 0 ) { 
                        jQuery("#cssoptimizer_options_limitpagepost").attr('disabled',true);
                    }

                } else {
                    jQuery(".form-table").find('input, textarea, checkbox').attr("disabled", true);
                    jQuery("#cssoptimizer_options_enable").attr("disabled", false);
                }
            });

            if ( jQuery.trim(jQuery( "#cssoptimizer_options_limitpagepost" ).val()).length > 0 ) { 
                jQuery("#exclude_page_post").fadeTo("fast",.33);
                jQuery("#cssoptimizer_options_exclude").attr('disabled',true);
            }

            if ( jQuery.trim(jQuery( "#cssoptimizer_options_exclude" ).val()).length > 0 ) { 
                jQuery("#limit_page_post").fadeTo("fast",.33);
                jQuery("#cssoptimizer_options_limitpagepost").attr('disabled',true);
            }

            jQuery( "#cssoptimizer_options_limitpagepost" ).blur(function() {
                if ( this.value.length > 0 ) {
                    jQuery("#exclude_page_post").fadeTo("fast",.33);
                    jQuery("#cssoptimizer_options_exclude").attr('disabled',true);
                } else {
                    jQuery("#exclude_page_post").fadeTo("fast",1);
                    jQuery("#cssoptimizer_options_exclude").attr('disabled',false);
                }
            });

            jQuery( "#cssoptimizer_options_exclude" ).blur(function() {
                if ( this.value.length > 0 ) { 
                    jQuery("#limit_page_post").fadeTo("fast",.33);
                    jQuery("#cssoptimizer_options_limitpagepost").attr('disabled',true);
                } else {
                    jQuery("#limit_page_post").fadeTo("fast",1);
                    jQuery("#cssoptimizer_options_limitpagepost").attr('disabled',false);
                }
            });

            jQuery( "form" ).submit(function() {
                jQuery(".form-table").find('input, textarea, checkbox').attr("disabled", false);                
            });

            jQuery( "#cssop_accordion" ).accordion();
        });

        // var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
        // (function(){
        // var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
        // s1.async=true;
        // s1.src='https://embed.tawk.to/61e7ac50f7cf527e84d2dec4/1fpofi7ak';
        // s1.charset='UTF-8';
        // s1.setAttribute('crossorigin','*');
        // s0.parentNode.insertBefore(s1,s0);
        // })();
    </script>
    <?php
}

function cssoptimizer_job_queue_page() {
    ?>
    <div class="wrap">
    <h1><?php _e( 'CSS Optimizer', 'cssoptimizer_options' ); ?></h1>
    <?php echo wp_kses_post( cssop_admin_tabs() ); ?>
    <h2 class="itemTitle">Refresh page to update Job Queue status. <?php _e( 'CSS Optimizer Job Queue', 'cssoptimizer_options' ); ?><?php _e( '<p><i><a href="https://zippisite.com/job-queue-tab/">What is Job Queue?</a></i></p>' ); ?></h2>
    <?php echo wp_kses_post(cssop_build_table()) ; ?>
    </div>

    <!--Start of Tawk.to Script-->
    <!-- <script type="text/javascript">
    var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
    (function(){
    var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
    s1.async=true;
    s1.src='https://embed.tawk.to/61e7ac50f7cf527e84d2dec4/1fpofi7ak';
    s1.charset='UTF-8';
    s1.setAttribute('crossorigin','*');
    s0.parentNode.insertBefore(s1,s0);
    })();
    </script> -->
    <!--End of Tawk.to Script-->
    <?php
}

function cssoptimizer_support_page() {
    ?>
    <div class="wrap">
    <h1><?php _e( 'CSS Optimizer', 'cssoptimizer_options' ); ?></h1>
    <?php echo wp_kses_post( cssop_admin_tabs() ); ?>
    <h2 class="itemTitle"><?php _e( 'CSS Optimizer Free Support', 'cssoptimizer_options' ); ?></h2>
    <?php _e( '<p>We provide free support to help you use this plugin.</p><p> Make sure you read the quick start guide before using the plugin: <a href="https://zippisite.com/css-optimizer-installation-1-3/">https://zippisite.com/css-optimizer-installation-1-3/</a></p> <p>If you encounter any problem, or simply want us to help you setup this plugin, please e-mail us at <a href="mailto:cssopsupport@panorazzi.com">cssopsupport@panorazzi.com</a>.</p>', 'cssoptimizer_options' ); ?>
    </div>

    <!--Start of Tawk.to Script-->
    <!-- <script type="text/javascript">
    var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
    (function(){
    var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
    s1.async=true;
    s1.src='https://embed.tawk.to/61e7ac50f7cf527e84d2dec4/1fpofi7ak';
    s1.charset='UTF-8';
    s1.setAttribute('crossorigin','*');
    s0.parentNode.insertBefore(s1,s0);
    })();
    </script> -->
    <!--End of Tawk.to Script-->
    <?php
}

function cssop_admin_tabs() {
    $tabs        = array( 'cssoptimizer' => __( 'Options', 'cssoptimizer' ),  'cssoptimizer_jobqueue' => __( 'Job Queue', 'cssoptimizer' ),  'cssoptimizer_support' => __( 'Free Support', 'cssoptimizer' ) );
    $tab_content = '';
    $tabs_count  = count( $tabs );
    if ( $tabs_count > 1 ) {
        if ( isset( $_GET['page'] ) ) {
            $current_id = $_GET['page'];
        } else {
            $current_id = 'cssoptimizer';
        }
        $tab_content .= '<h2 class="nav-tab-wrapper">';
        foreach ( $tabs as $tab_id => $tab_name ) {
            if ( $current_id == $tab_id ) {
                $class = ' nav-tab-active';
            } else {
                $class = '';
            }
            $tab_content .= '<a class="nav-tab' . $class . '" href="?page=' . $tab_id . '">' . $tab_name . '</a>';
        }
        $tab_content .= '</h2>';
    } else {
        $tab_content = '<hr/>';
    }

    return $tab_content;
}

function cssop_show_job_queue_status() {
    $filename = CSSOPTIMIZER_CACHE_DIR . 'css_job_queue.txt';
    $job_array = array();
    if (file_exists($filename) && filesize($filename) > 0 ) {
        $fp = fopen( $filename, "r");
        if (flock($fp, LOCK_SH)) {
            $job_array_str = fread($fp, filesize($filename));
            $job_array = json_decode($job_array_str, true);
        
            flock($fp, LOCK_UN); 
        } 
    
        $pending_job = 0;
        if (is_array($job_array)) {
            foreach($job_array as $key=>$value) {
                if($value['status'] === 'Pending' ){
                    $pending_job++;
        
                }
            }
        }
            
        if ( $pending_job > 1 ) {
            ?>
            <div class="job-queue-status"><p>
            <?php
            _e( $pending_job . " <a href=\"options-general.php?page=cssoptimizer_jobqueue\">jobs</a> are pending to be optimized." );
            ?>
            </p></div>
            <?php
        } elseif ( $pending_job == 1 ) {
            ?>
            <div class="job-queue-status"><p>
            <?php
            _e( $pending_job . " <a href=\"options-general.php?page=cssoptimizer_jobqueue\">jobs</a> is pending to be optimized." );
            ?>
            </p></div>
            <?php
        }
    } 
}

function cssop_show_first_time_guide() {
    if ( cssop_get_option('cssoptimizer_first_time_guide') ) {
        ?>
        <div class="notice notice-info is-dismissible" data-dismissible="cssop_guide_dismissible"><p>
        <?php
        _e( 'Guide for first time user: <a href=\"https://zippisite.com/css-optimizer-installation-1-3/\">https://zippisite.com/css-optimizer-installation-1-3/</a>', 'cssoptimizer' );
        ?>
        </p></div>
        <?php
    }
}

function cssop_show_message_if_not_enabled() {
    if ( !cssop_get_option('cssoptimizer_options_enable') ) {
        ?>
        <div class="notice-warning notice"><p>
        <?php
        _e( 'CSS Optimizer is not enabled, thus "Reduce Unused CSS" is not working. Please check "Enable" to turn on the "Reduce Unused CSS" feature.', 'cssoptimizer' );
        ?>
        </p></div>
        <?php
    } else {
        $domain = get_home_url();
        $input = cssop_get_option("cssoptimizer_options_token");
        $check_at_link = 'https://dashboard.zippisite.com/validate_auth_token?siteurl=' . $domain . '&authtoken=' . $input;
        $response = wp_remote_get( $check_at_link );
        $body = wp_remote_retrieve_body( $response );
    
        if ( !is_wp_error( $response ) ) {
            $data = json_decode($body);
    
            if ($data) {
                if (!$data->Success) {
                    if ($data->Message == "Overusage") {
                        ?>
                        <div class="notice-error notice"><p>
                        <?php
                        _e( 'CSS Optimizer: You have reached your API Request quota. Please consider upgrading your plan. <a href="https://dashboard.zippisite.com/pricing-plan">Click here for more info.</a>', 'cssoptimizer' );
                        ?>
                        </p></div>
                        <?php
                    }
                } else {
                    if ($data->FailCall > 20) {
                        ?>
                        <div class="notice-warning notice"><p>
                        <?php
                        _e( 'CSS Optimizer - There are a large numbers of error calls to our API. Please contact support at <a href="mailto:cssopsupport@panorazzi.com">cssopsupport@panorazzi.com</a> for further investigation.', 'cssoptimizer' );
                        ?>
                        </p></div>
                        <?php
                    }
                }
            } 
        }
    }

    if ( !cssop_get_option('cssoptimizer_options_token') ) {
        ?>
        <div class="notice-warning notice"><p>
        <?php
        _e( '"Reduce Unused CSS" is not working as the Authentication Token is empty. Please go to CSS Optimizer’s setting page to enter an Authentication Token.', 'cssoptimizer' );
        ?>
        </p></div>
        <?php
    }
}

function cssop_validate_token( $input ) {
    $newinput = trim( $input );

    if (empty($input)) {
        return '<a href="https://dashboard.zippisite.com/customer/sites">Get Authentication Token</a>';
    }

    if (!preg_match('/^[a-z0-9]{32}$/i', $newinput)) {
        //add_settings_error('cssoptimizer_options_token', 'cssoptimizer_options_token', __('Token Invalid - Wrong Authentication Token Format', 'cssoptimizer_options_token'));
        return '<p style="color: red;">Token Invalid - Wrong Authentication Token Format</p>';
    }

    $domain = get_home_url();
    $check_at_link = 'https://dashboard.zippisite.com/validate_auth_token?siteurl=' . $domain . '&authtoken=' . $input;
    $response = wp_remote_get( $check_at_link );
    $body = wp_remote_retrieve_body( $response );

    if ( is_wp_error( $response ) ) {
        $error_message = $response->get_error_message();
        cssop_write_log('[' . date('m/d/Y h:i:s a', time()) . '] Error getting authentication token result: '. PHP_EOL . $error_message);
    }

    $data = json_decode($body);

    if($data) {
        if ($data->Success) {
            return '<p style="color: green;">Token Valid - Current usage for this site: ' . $data->SiteUsage . ' | <a href="https://dashboard.zippisite.com/customer/sites">View Full Usage</a></p>';
        }
        else {
            if ($data->Message == "Expired") {
                return '<p style="color: red;">Token Invalid - <a href="https://dashboard.zippisite.com/pricing-plan">Renew Plan Now</a></p>';
            }
            else if ($data->Message == "Overusage") {
                return '<p style="color: red;">Token Invalid - <a href="https://dashboard.zippisite.com/customer/sites">Current usage more then allowed usage per month</a></p>';
            }
            else {
                return '<p style="color: red;">Token Invalid - <a href="https://dashboard.zippisite.com/customer/sites">Get New Authentication Token</a></p>';
            }
        }
    } else {
        return '<p style="color: red;">Token Invalid - <a href="https://dashboard.zippisite.com/customer/sites">Get New Authentication Token</a></p>';
    }

    return '';
}

// create job queue table
function cssop_build_table(){

    // start table
    $html = '<table class="cssop-list-table widefat striped">';
    // header row
    $html .= '<tr>';
    $html .= '<th>CSS Name</th>';
    $html .= '<th>Page URL</th>';
    $html .= '<th>Status</th>';
    $html .= '<th>Created On UTC</th>';
    $html .= '</tr>';
    
    $filename = CSSOPTIMIZER_CACHE_DIR . 'css_job_queue.txt';
    $array = array();
    if (file_exists($filename) && filesize($filename) > 0 ) {
        $fp = fopen( $filename, "r");
        if (flock($fp, LOCK_SH)) {
            $job_array_str = fread($fp, filesize($filename));
            $array = json_decode($job_array_str, true);
        
            flock($fp, LOCK_UN); 
        } 
    
        if (!is_array($array) || empty($array)) {
            $html .= '<tr><td colspan="4" style="text-align: center;">Empty record</td></tr>';
        } else {
            foreach( $array as $key=>$value){
                $html .= '<tr>';
                foreach($value as $key2=>$value2){
                    $add_html = '<td>' . htmlspecialchars($value2) . '</td>';

                    if ($key2 === 'run_in') {
                        // do nothing
                    }
                    else {
                        if ($key2 === 'created_on') {
                            $add_html = '<td>' . htmlspecialchars(date('m/d/Y h:i:s a', $value2)) . '</td>';
                        }

                        if ($key2 === 'css_name') {
                            if (strpos($value2, ', ') !== false){
                                $file_name_array = explode(', ', $value2);

                                if (count($file_name_array) === 1) {
                                    $add_html = '<td>' . htmlspecialchars($value2) . '</td>';
                                } elseif (count($file_name_array) === 2) {
                                    $add_html = '<td>' . htmlspecialchars($file_name_array[0]) . ' and ' . htmlspecialchars($file_name_array[1]) . '</td>';
                                } else {
                                    $add_html = '<td>' . htmlspecialchars($file_name_array[0]) . ' , ' . htmlspecialchars($file_name_array[1]) . ' and ' . htmlspecialchars(count($file_name_array) - 2) . ' other CSS files</td>';
                                }
                            }
                        }
            
                        $html .= $add_html;
                    }
                }
                $html .= '</tr>';
            }
        }    
    } else {
        $html .= '<tr><td colspan="4" style="text-align: center;">Empty record</td></tr>';
    }
    $html .= '</table>';
    return $html;
}

// settings link in plugins table
function cssoptimizer_action_links($actions, $plugin_file) {
	if(plugin_basename(__FILE__) == $plugin_file) {

        $settings_url = admin_url('options-general.php?page=cssoptimizer');

		$settings_link = array('settings' => '<a href="' . $settings_url . '">' . __('Settings', 'cssoptimizer') . '</a>');
		$actions = array_merge($settings_link, $actions);
	}
	return $actions;
}

function cssop_get_option( $option, $default = false ) {

    //if ( 'cssoptimizer_enable_site_config' === $option ) {
    //    return get_site_option( 'cssoptimizer_enable_site_config' );
    //}

    //$configuration_per_site = get_site_option( 'cssoptimizer_enable_site_config' );
    //if ( is_cssop_active_for_network() && ( 'on' !== $configuration_per_site || is_network_admin() ) ) {
    //    return get_site_option( $option, $default );
    //}

    return get_option( $option, $default );
}

function cssop_update_option( $option, $value, $autoload = null ) {
    //if ( is_cssop_active_for_network() && is_network_admin() ) {
    //    return update_site_option( $option, $value );
    //} elseif ( 'cssoptimizer_enable_site_config' !== $option ) {
    //    return update_option( $option, $value, $autoload );
    //}
    return update_option( $option, $value, $autoload );
}

add_action( 'cssoptimizer_default_options', 'cssoptimizer_default_values' );
add_action( 'admin_enqueue_scripts', 'cssop_setting_up_css' );

if ( is_cssop_active_for_network() ) {
    add_action( 'network_admin_menu', 'cssoptimizer_add_settings_page' );
}

add_action( 'admin_menu', 'cssoptimizer_add_settings_page' );
add_action( 'admin_init', 'cssoptimizer_settings' );
add_action( 'admin_notices', 'cssop_show_message_if_not_enabled' );
add_filter('plugin_action_links', 'cssoptimizer_action_links', 10, 5);
register_activation_hook( __FILE__, 'cssoptimizer_activation' );




/* Utilities */

// Transform relative URL to full URL
function cssop_get_full_url ( $url, $post_url ) {
    $site_host = parse_url( $post_url, PHP_URL_HOST );

    if ( false === strpos( $url, $site_host ) ) {
        if ( is_ssl() ) {
            $url = get_home_url(null, '', 'https') . $url;
        } else {
            $url = get_home_url(null, '', 'http') . $url;
        }
    } else {
        if ( is_ssl() ) {
            $url = 'https:' . $url;
        } else {
            $url = 'http:' . $url;
        }
    }

    return $url;
}

if (!function_exists('cssop_write_log')) {

    function cssop_write_log($log) {
        $cssop_log_file = CSSOPTIMIZER_CACHE_DIR . 'css_optimizer_log.txt';

        if (is_array($log) || is_object($log)) {
            file_put_contents($cssop_log_file, print_r($log, true) . PHP_EOL, FILE_APPEND);
        } else {
            file_put_contents($cssop_log_file, $log . PHP_EOL, FILE_APPEND);
        }
    }
}

function cssop_download_Css($link) {
    $purged_link = 'http://api.zippisite.com/' . $link;

    $args = array(
        'timeout'     => 6000,
        'headers'     => array(
            'Content-Type'  => 'text/css',
        )
    );

    $response = wp_remote_get( $purged_link, $args );
    $purged_css = wp_remote_retrieve_body( $response );

    if ( is_wp_error( $response ) ) {
        $error_message = $response->get_error_message();
        cssop_write_log('[' . date('m/d/Y h:i:s a', time()) . '] Error downloading Purged CSS file: '. PHP_EOL . $error_message);
        return false;
    }
    
    return $purged_css;
}

function cssop_add_criticalcss ( $content, $ccss_path ) {

    if ( file_exists( $ccss_path ) ) {
        $ccss_content = file_get_contents( $ccss_path );
        if ( ! empty( $ccss_content ) ) {
            $content_without_comment = preg_replace( '/<!--(.*)-->/Uis', '', $content );

            $selectedMatch = preg_match_all( '/(<link[^>]*stylesheet[^>]*>)/', $content_without_comment, $matches );

                
            // temporary changes, need checking
            if ( $selectedMatch > 0 ) {
                $is_first = true;
                foreach ($matches[0] as $key => $match) {
                    $inline_ccss = $match;

                    if ( strpos( $match, 'cache/cssoptimizer/' ) !== false ) {
                        if (in_array( 'wp-rocket/wp-rocket.php', apply_filters( 'active_plugins', cssop_get_option( 'active_plugins' ) ) ) && 1 === get_rocket_option( 'minify_css' ) && 1 === get_rocket_option( 'minify_concatenate_css' ) ) {
                            $inline_ccss = str_replace('data-minify=', 'media="print" onload="this.onload=null;this.media=\'all\';" ', $inline_ccss);
                        }
                        else {
                            $inline_ccss = str_replace( "'all'", "'print'", $inline_ccss );
                            $inline_ccss = str_replace( '"all"', '"print"', $inline_ccss );
                            $inline_ccss = str_replace( "/>", "", $inline_ccss);
                            $inline_ccss = $inline_ccss . 'onload="this.onload=null;this.media=\'all\';" />' ;
                        }
                    }

                    if ( $is_first ) {
                        $inline_ccss = '<style id="cssop_ccss" media="all">' . $ccss_content . '</style>' . $inline_ccss;
                        $is_first = false;
                    }
                    $content = str_replace( $match, $inline_ccss, $content );    
                }
            }
        }
        else {
            cssop_write_log('[' . date('m/d/Y h:i:s a', time()) . '] Critical CSS failed. Execution ended for ' . $post_url);
        }
    } 

    return $content;
}

// check authentication token
function cssop_check_token( ) { 
    $domain = get_home_url();
    $check_at_link = 'https://dashboard.zippisite.com/validate_auth_token?siteurl=' . $domain . '&authtoken=' . cssop_get_option('cssoptimizer_options_token');
    $response = wp_remote_get( $check_at_link );
    $body = wp_remote_retrieve_body( $response );

    if ( is_wp_error( $response ) ) {
        $error_message = $response->get_error_message();
        cssop_write_log('[' . date('m/d/Y h:i:s a', time()) . '] Error getting authentication token result: '. PHP_EOL . $error_message);
        return false;
    }

    $data = json_decode($body);

    return $data->Success;
}

// Keep all job queue records for 24h. Remove all records that are more than 24h.
function cssop_filter_job_queue( ) {
    $filename = CSSOPTIMIZER_CACHE_DIR . 'css_job_queue.txt';
    $fp = fopen( $filename, "r+");
    if (filesize($filename) > 0 && flock($fp, LOCK_EX)) {
        $job_array_str = fread($fp, filesize($filename));;
        $job_array = json_decode($job_array_str, true);

        if ( is_array($job_array) ) {
            foreach($job_array as $key=>$value) {
                if(time() - 86400 >= $value['created_on'] ) {
                    unset($job_array[$key]);
                }
            }
            $job_array_str = json_encode($job_array);

            ftruncate($fp, 0);
            rewind($fp);    
            fwrite($fp, $job_array_str); 
        } 

        flock($fp, LOCK_UN); 
    } 
}

// Replace job queue record status for purge process.
function cssop_array_replace_status($file_name, $status) {
    $filename = CSSOPTIMIZER_CACHE_DIR . 'css_job_queue.txt';
    $fp = fopen( $filename, "r+");
    if (filesize($filename) > 0 && flock($fp, LOCK_EX)) {
        $job_array_str = fread($fp, filesize($filename));;
        $job_array = json_decode($job_array_str, true);

        if (is_array($job_array)) {
            if (!is_array($file_name)) {
                $file_css_name = $file_name;
                foreach($job_array as $key=>$value) {
                    if ($job_array[$key]['css_name'] === $file_css_name){
                        $job_array[$key]['status'] = $status;
                        break; // Stop the loop after we've found the item
                    }
                }
                $job_array_str = json_encode($job_array);

                ftruncate($fp, 0);
                rewind($fp);        
                fwrite($fp, $job_array_str);
            } else {
                foreach($job_array as $key=>$value) {
                    $job_queue_file_names = explode(', ', $job_array[$key]['css_name']);
                    sort($job_queue_file_names);
                    sort($file_name);
                    if($job_queue_file_names == $file_name ){
                        $job_array[$key]['status'] = $status;
                        break; // Stop the loop after we've found the item
                    }
                }
                $job_array_str = json_encode($job_array);

                ftruncate($fp, 0);
                rewind($fp);        
                fwrite($fp, $job_array_str);
            }
        }

        flock($fp, LOCK_UN); 
    } 
}

// (Cron job) Check if CCSS is generated for each page that failed to get CCSS content from API url
function cssop_check_ccss() {
    $job_array = get_transient( 'cssop_ccss_queue' );

    if ( false !== $job_array ) {
        if (empty($job_array)) {
            delete_transient( 'cssop_ccss_queue' );
        } else {
            foreach($job_array as $key=>$value) {
                $ccss = cssop_download_Css($value['api_ccss_url']);

                if (strpos($ccss, '"error":"Not Found"') === false) {
                    if ($ccss) {
                        wp_delete_file( $value['ccss_dir'] );

                        file_put_contents( str_replace('.css', '.purged.css', $value['ccss_dir']), $ccss );

                        unset($job_array[$key]);
                    }
                }
            }

            set_transient( 'cssop_ccss_queue', $job_array, DAY_IN_SECONDS );
        }
    } 
}

function cssop_clear_all_plugin_cache() {
    if (is_plugin_active( 'wp-rocket/wp-rocket.php' )) {
        rocket_clean_domain();
    }

    if (is_plugin_active( 'wp-fastest-cache/wpFastestCache.php' )) {
        do_action('wp_ajax_wpfc_delete_cache');
    }

    if (is_plugin_active( 'sg-cachepress/sg-cachepress.php' )) {
        do_action('wp_ajax_admin_bar_purge_cache');
    }

    if (is_plugin_active( 'wp-optimize/wp-optimize.php' )) {
        wpo_cache_flush();
    }

    if (is_plugin_active( 'w3-total-cache/w3-total-cache.php' )) {
        do_action( 'w3tc_flush_all' );
    }    
}

// transform relative url to absolute url
function cssop_change_relative_url( $rel_url, $base ) {
    if (parse_url($rel_url, PHP_URL_SCHEME) != '') 
        return $rel_url;

    if ($rel_url[0]=='#' || $rel_url[0]=='?')
        return $base.$rel_url;

    extract(parse_url($base));

    $path = preg_replace('#/[^/]*$#', '', $path);

    if ($rel_url[0] == '/') {
        $path = '';
    }

    $abs = "$host$path/$rel_url";

    $re = array('#(/\.?/)#', '#/(?!\.\.)[^/]+/\.\./#');
    for ( $n=1; $n>0; $abs=preg_replace($re, '/', $abs, -1, $n) ) {}

    return '//'.$abs;
}

// add cron schedule
add_filter( 'cron_schedules', 'cssop_cron_interval' );

function cssop_cron_interval( $schedules ) { 
    $schedules['cssop_purge_job'] = array(
        'interval' => 6,
        'display'  => esc_html__( 'CSS Optimizer' ), );
    return $schedules;
}

// make sure cssoptimizer_autoptimize_queue is scheduled OK if cssop is enabled.
if ( cssop_get_option('cssoptimizer_options_enable') && cssop_check_token() && ! wp_next_scheduled( 'cssoptimizer_purge_queue' ) ) {
    wp_schedule_event( time(), 'cssop_purge_job', 'cssoptimizer_purge_queue');
}
add_action( 'cssoptimizer_purge_queue', 'cssop_noao_run_purgecss' );

// add schedule event for deleting job queue records that are more than 24h.
if ( cssop_get_option('cssoptimizer_options_enable') && cssop_check_token() && ! wp_next_scheduled( 'cssop_filter_job_queue_job' ) ) {
    wp_schedule_event( time(), 'hourly', 'cssop_filter_job_queue_job');
}
add_action( 'cssop_filter_job_queue_job', 'cssop_filter_job_queue' );



/* CSS Optimizer Combine CSS */
function cssop_combine_css( $content, $css_name, $data ) {
    if ( empty( $data ) ) {
        return '';
    }

    foreach ( $data as $url ) {
        $new_content[ $url ] = file_get_contents( $url );
    }

    $css_name = md5($css_name);

    $new_url   = WP_CONTENT_DIR . '/cache/cssoptimizer/cssop-combined-css-' . $css_name . '.css';

    $data = array(
        'handle' => 'cssop-combined-css-' . $css_name,
        'url'    => $new_url,
    );
    
    if ( !file_exists( $new_url ) ) {
        file_put_contents( $new_url, cssop_get_content_with_replacements( $new_content ));
    }

    return $new_url;
}

function cssop_get_content_with_replacements( $contents ) {
    // Set the new content var.
    $new_content = array();

    foreach ( $contents as $url => $content ) {
        $dir = trailingslashit( dirname( $url ) );

        $content = cssop_check_for_imports( $content, $url );
        // Remove source maps urls.
        $content = preg_replace(
            '~^(\/\/|\/\*)(#|@)\s(sourceURL|sourceMappingURL)=(.*)(\*\/)?$~m',
            '',
            $content
        );

        $regex = '/url\s*\(\s*(?!["\']?data:)(?![\'|\"]?[\#|\%|])([^)]+)\s*\)([^;},\s]*)/i';

        $replacements = array();

        preg_match_all( $regex, $content, $matches );

        if ( ! empty( $matches ) ) {
            foreach ( $matches[1] as $index => $match ) {
                $match = trim( $match, " \t\n\r\0\x0B\"'" );

                // Bail if the url is valid.
                if ( false == preg_match( '~(http(?:s)?:)?\/\/(?:[\w-]+\.)*([\w-]{1,63})(?:\.(?:\w{2,}))(?:$|\/)~', $match ) ) {
                    if ( substr ($match,0,2) == '//' )
                        continue;

                    $temp_dir = cssop_change_relative_url($match, $url);
                    $replacement = str_replace( $match, $temp_dir, $matches[0][ $index ] );

                    $replacements[ $matches[0][ $index ] ] = $replacement;
                }
            }
        }

        $keys = array_map( 'strlen', array_keys( $replacements ) );
        array_multisort( $keys, SORT_DESC, $replacements );

        $new_content[] = str_replace( array_keys( $replacements ), array_values( $replacements ), $content );
    }

    return implode( "\n", $new_content );
}

function cssop_check_for_imports( $content, $url ) {
    // Get the file dir.
    $dir = trailingslashit( dirname( $url ) );
    // Check for imports in the style.
    preg_match_all( '/@import\s+(["\'](.+?)["\']|url\(["\'](.*)["\']\))/i', $content, $matches );

    // Return the content if there are no matches.
    if ( empty( $matches ) ) {
        return $content;
    }

    $home_url = get_home_url();
    $home_url = str_replace( 'http://', '', $home_url );
    $home_url = str_replace( 'https://', '', $home_url );

    // Loop through all matches and get the imported css.
    foreach ( $matches[1] as $match ) {
        // Check if url is external url
        if ( strpos( $match, '//' ) !== false && strpos( $match, $home_url ) === false ) {
            continue;
        }
        
        $import_content = cssop_get_content_with_replacements(
            array(
                $url => file_get_contents( $dir . $match ),
            )
        );

        // Replace the @import with the css.
        $content = str_replace( $matches[0], $import_content, $content );
    }

    // Finally return the content.
    return $content;
}


 
/* Extract CSS Classes from JS */
function cssop_extract_css_from_js( $content ) {
    $content_without_comments = preg_replace( '/<!--(.*)-->/Uis', '', $content );

    $regex = '/src=[\'\"](.*?\.js)[\?\#\'\"]/';
    preg_match_all( $regex, $content_without_comments, $scripts, PREG_SET_ORDER );

    //cssop_write_log('[' . date('m/d/Y h:i:s a', time()) . '] $scripts: ' . print_r($scripts));

    foreach ( $scripts as $s ) {
        //cssop_write_log('[' . date('m/d/Y h:i:s a', time()) . '] $s: ' . $s[0] . ' ' . $s[1]);
    }

    $js_content[ 'inline' ] = $content_without_comments;

    if ( !empty( $scripts ) ) {        
        foreach ( $scripts as $script ) {
            $is_external = true;

            $host = parse_url( $script[1], PHP_URL_HOST ); //domain.com
            if ( strpos( $_SERVER['HTTP_HOST'], $host ) >= 0  ||
                strpos( $script[1], '/wp-includes' ) == 0 ||
                strpos( $script[1], '/wp-content' ) == 0 ) {
                $is_external = false;
            }

            if ( $is_external ) {
                // Try to fetch the file.
                $request = wp_remote_get( $script[1] );

                // Bail if the request fails.
                if ( is_wp_error( $request ) ) {
                    $js_content[ $script[1] ] = false;
                }

                if ( 200 !== wp_remote_retrieve_response_code( $request ) ) {
                    $js_content[ $script[1] ] = false;
                }

                // Get the file content from the request.
                $file_content = wp_remote_retrieve_body( $request );
            } else {
                $file_content = file_get_contents( $script[1] );
            }
    
            $js_content[ $script[0] ] = $file_content;
        }
    }

    $data = array_filter( $js_content );

    if ( !empty( $data ) ) {
        $saved_exclude_css = explode( '|', cssop_get_option('cssoptimizer_options_safelist') ); 
        $saved_exclude_css = array_filter($saved_exclude_css);

        foreach ( $data as $script => $js_content ) {
            preg_match_all( '/((add)|(remove)|(has))Class\(\s?+[\'"]([-_a-zA-Z0-9]+)[\'"]/', $js_content, $css_name_matches, PREG_SET_ORDER );

            if (!empty($css_name_matches)) {
                foreach ($css_name_matches as $css) {
                    if ( !in_array( $css[1], $saved_exclude_css ) ) {
                        array_push($saved_exclude_css, $css[1]);
                    }
                }
            }
        }

        if (!empty($saved_exclude_css)) {
            $new_exclude_css = implode('|', $saved_exclude_css);
            //cssop_write_log('[' . date('m/d/Y h:i:s a', time()) . '] Safelist: ' . $new_exclude_css);
            return $new_exclude_css;
        }
    }

    return '';
}





/* Standard CSS Optimizer */

function cssop_noao_execute_purgecss( $buffer ) {
    if ( is_user_logged_in() ) {
        return $buffer;
    }

    $post_url = get_permalink();
    if (empty($post_url))
        return $buffer;

    if (empty($buffer)) 
        return $buffer;

    
    $filename = CSSOPTIMIZER_CACHE_DIR . 'css_job_queue.txt';
    $job_array = array();
    $fp = fopen( $filename, "r+");
    if (filesize($filename) > 0 && flock($fp, LOCK_EX)) {
        $job_array_str = fread($fp, filesize($filename));
        $job_array = json_decode($job_array_str, true);
    
        // flock($fp, LOCK_UN); 
    } 

    if (is_array($job_array)) {
        foreach($job_array as $key=>$value) {
            if ($job_array[$key]['post_url'] === $post_url && ($job_array[$key]['status'] === 'Pending' || $job_array[$key]['status'] === 'Running')) {
                
                flock($fp, LOCK_UN);
                return $buffer;
            }
        }
    }

    cssop_write_log('[' . date('m/d/Y h:i:s a', time()) . '] Begin execute CSS Optimizer on ' . $post_url);

    $enable = cssop_get_option('cssoptimizer_options_enable');
    if (!$enable){
        cssop_write_log('[' . date('m/d/Y h:i:s a', time()) . '] CSS Optimizer is not activated. Execution ended on ' . $post_url);

        flock($fp, LOCK_UN);
        return $buffer;
    }

    // check post id or slug
    $exclude = cssop_get_option('cssoptimizer_options_exclude');
    if (!empty($exclude)){
        global $post;
        $post_slug = $post -> post_name;
        $post_id = get_the_ID();
        $exclude = str_replace(array("\r\n", "\n\r", "\r", "\n"), '|', $exclude);
        $exclude = strtolower(str_replace(' ', '', $exclude));
        $lines = explode('|', $exclude);
        foreach ($lines as $line) {
            if (strpos($line, $post_slug) !== false && !is_front_page()){
                cssop_write_log('[' . date('m/d/Y h:i:s a', time()) . '] Similar pages / posts slug (' . $post_slug . ') is found in "Exclude pages / posts" option. Execution ended for ' . $post_url);

                flock($fp, LOCK_UN);
                return $buffer;
            }
            else if ('id=' . $post_id == $line && !is_front_page()){
                cssop_write_log('[' . date('m/d/Y h:i:s a', time()) . '] Similar pages / posts ID (id =' . $post_id . ') is found in "Exclude pages / posts" option. Execution ended for ' . $post_url);

                flock($fp, LOCK_UN);
                return $buffer;
            }
            else if (strpos($line, '[home]') !== false) {
                if (is_front_page()){
                    cssop_write_log('[' . date('m/d/Y h:i:s a', time()) . '] Home page is found in "Exclude pages / posts" option. Execution ended for ' . $post_url);

                    flock($fp, LOCK_UN);
                    return $buffer;
                }
            }
        }
    }    

    $limit = cssop_get_option('cssoptimizer_options_limitpagepost');
    if (!empty($limit)){
        $found = false;
        global $post;
        $post_slug = $post -> post_name;
        $post_id = get_the_ID();
        $limit = str_replace(array("\r\n", "\n\r", "\r", "\n"), '|', $limit);
        $limit = strtolower(str_replace(' ', '', $limit));
        $lines = explode('|', $limit);
        foreach ($lines as $line) {
            if (strpos($line, $post_slug) !== false && !is_front_page()){
                $found = true;
            }
            else if ('id=' . $post_id == $line && !is_front_page()){
                $found = true;
            }
            else if (strpos($line, '[home]') !== false) {
                if (is_front_page()){
                    $found = true;
                }
            }
        }

        if (!$found){
            cssop_write_log('[' . date('m/d/Y h:i:s a', time()) . '] This page / post is not included in "Limit to pages / posts" option. Execution ended for ' . $post_url);

            flock($fp, LOCK_UN);
            return $buffer;
        }
    }    

    // Get <style> and <link>
    $css_url = array();
    $for_ccss_url = array();
    $home_url = get_home_url();
    $home_url = str_replace( 'http://', '', $home_url );
    $home_url = str_replace( 'https://', '', $home_url );
    $buffer_without_comment = preg_replace( '/<!--(.*)-->/Uis', '', $buffer );
    if ( preg_match_all( '#<link[^>]*stylesheet[^>]*>#Usmi', $buffer_without_comment, $matches ) ) { 
        foreach ( $matches[0] as $tag ) {
            $run_save_css = true;
            $exclude_css = array_filter( array_map( 'trim', explode( ',', cssop_get_option('cssoptimizer_options_exclude_css') ) ) ); //'wp-content/plugins/, wp-content/cache/, wp-content/uploads/, admin-bar.min.css, dashicons.min.css'
            if ( is_array( $exclude_css ) && ! empty( $exclude_css ) ) {
                foreach ( $exclude_css as $match ) {
                    if ( false !== strpos( $tag, $match ) ) {
                        $run_save_css = false;
                    }
                }
            }

            if ( preg_match( '#<link.*href=("|\')([^\'"]+\.css(?:\?[^\'"]*)?)("|\')#Usmi', $tag, $source ) && $run_save_css ) {
                $url  = current( explode( '?', $source[2], 2 ) );

                // for excluded autoptimize single
                if (in_array( 'autoptimize/autoptimize.php', apply_filters( 'active_plugins', cssop_get_option( 'active_plugins' ) ) ) && 'on' === cssop_get_option( 'autoptimize_css', false ) &&
                 'on' === cssop_get_option( 'autoptimize_css_aggregate', false ) && 'on' === cssop_get_option( 'autoptimize_minify_excluded', false )) {
                    if (strpos( $url, $home_url ) !== false && strpos( $url, '_single_' ) === false) {
                        array_push($css_url, $url);
                        array_push($for_ccss_url, $url);
                    } else {
                        if (strpos( $url, '_single_' ) === false && $url[0] == '/' && $url[1] != '/') {
                            array_push($css_url, $url);

                            $full_url = $url;
                            if ( filter_var($url, FILTER_VALIDATE_URL) === false )
                                $full_url = cssop_get_full_url($url, $post_url);
                    
                            array_push($for_ccss_url, $full_url);
                        }
                    }

                    continue;
                }

                if (strpos( $url, $home_url ) !== false && strpos( $url, '/cssoptimizer' ) === false) {
                    array_push($css_url, $url);
                    array_push($for_ccss_url, $url);
                } else {
                    if (strpos( $url, '/cssoptimizer' ) !== false)
                        continue;
                        
                    if ($url[0] == '/' && $url[1] != '/') {
                        array_push($css_url, $url);
                        $full_url = $url;

                        if ( filter_var($url, FILTER_VALIDATE_URL) === false )
                            $full_url = cssop_get_full_url($url, $post_url);

                        array_push($for_ccss_url, $full_url);
                    }
                }
            }
        }
    }
    
    if (empty($css_url)) {
        cssop_write_log('[' . date('m/d/Y h:i:s a', time()) . '] Empty css url. Execution ended for ' . $post_url);

        flock($fp, LOCK_UN);
        return $buffer;
    }

    // check authentication token first
    $domain = get_home_url();
    $check_at_link = 'https://dashboard.zippisite.com/validate_auth_token?siteurl=' . $domain . '&authtoken=' . cssop_get_option('cssoptimizer_options_token');
    $response = wp_remote_get( $check_at_link );
    $body = wp_remote_retrieve_body( $response );

    if ( is_wp_error( $response ) ) {
        $error_message = $response->get_error_message();
        cssop_write_log('[' . date('m/d/Y h:i:s a', time()) . '] Error getting authentication token result: '. PHP_EOL . $error_message);
    }

    $data = json_decode($body);

    if ($data) {
        if (!$data->Success) {

            flock($fp, LOCK_UN);
            return $buffer;
        }
    }

    // check if there’s any file of the same name in css optimizer cache
    $run_purge_job = false;
    $cache_exist = false;
    $post_slug_name = md5($post_url);
    $job_queue_css_name = array();
    foreach ( $css_url as $url) {
        
        $full_url = $url;
        if ( filter_var($url, FILTER_VALIDATE_URL) === false )
            $full_url = cssop_get_full_url($url, $post_url);

        $css_old_path = str_replace( get_home_url(null, '', 'https') . '/', '', $full_url);
        $css_old_path = str_replace( get_home_url(null, '', 'http') . '/', '', $css_old_path);
        $css_old_path = str_replace( '_', '+', $css_old_path);
        $css_new_name = str_replace( '/' , '_', $css_old_path);
        $css_new_name = $post_slug_name . '_' . $css_new_name;

        if (file_exists(CSSOPTIMIZER_CACHE_DIR . $css_new_name)) {
            $buffer = str_replace( $url, CSSOPTIMIZER_CACHE_URL . $css_new_name, $buffer );
            $cache_exist = true;
        }
        else {

            $response = wp_remote_get( $full_url );
            if ( is_wp_error( $response ) ) {
                $error_message = $response->get_error_message();
                cssop_write_log('[' . date('m/d/Y h:i:s a', time()) . '] Error getting css content from ' . $url . ' : '. PHP_EOL . $error_message);
            }
            else {
                $response_code = wp_remote_retrieve_response_code( $response );
                if ($response_code == 200)
                {
                    $body = wp_remote_retrieve_body( $response );
            
                    $new_content = array();
                    $new_content[ $full_url ] = $body;
                    file_put_contents(CSSOPTIMIZER_CACHE_DIR . $css_new_name, cssop_get_content_with_replacements($new_content));
                    $buffer = str_replace( $url, CSSOPTIMIZER_CACHE_URL . $css_new_name, $buffer );
                    $run_purge_job = true;

                    array_push($job_queue_css_name, $css_new_name);
                } else {
                    cssop_write_log('[' . date('m/d/Y h:i:s a', time()) . '] Empty css content from ' . $url);
                }
            }
        }
    }

    if ($run_purge_job) {
        // save job
        $queue_name = implode(', ', $job_queue_css_name);

        // $filename = CSSOPTIMIZER_CACHE_DIR . 'css_job_queue.txt';
        // $fp = fopen( $filename, "r+");
        //if (flock($fp, LOCK_EX)) 
        {
            // $job_array_str = fread($fp, filesize($filename));
            $previous_job_array = json_decode($job_array_str, true);

            if (is_array($previous_job_array))
                $previous_job_array = array_merge(array( array('css_name' => $queue_name, 'post_url' => $post_url, 'status' => 'Pending', 'run_in' => '-', 'created_on' => time())), $previous_job_array);
            else
                $previous_job_array = array( array('css_name' => $queue_name, 'post_url' => $post_url, 'status' => 'Pending', 'run_in' => '-', 'created_on' => time()));
            $job_array_str = json_encode($previous_job_array);

            ftruncate($fp, 0);
            rewind($fp);        
            fwrite($fp, $job_array_str);
    
            // flock($fp, LOCK_UN);
        } 
    
        // critical css
        if ( cssop_get_option('cssoptimizer_options_generateccss') ) {
            $new_combined_css = cssop_combine_css( $buffer_without_comment, $post_url, $for_ccss_url );
            if (empty($new_combined_css)) {
                cssop_write_log('[' . date('m/d/Y h:i:s a', time()) . '] Failed to combine css file. (' . $post_url . ')');
            }
        }
        
        cssop_write_log('[' . date('m/d/Y h:i:s a', time()) . '] Add Purge CSS job to queue. (' . $post_url . ')');
    } elseif ($cache_exist) {
        // critical css
        if ( cssop_get_option('cssoptimizer_options_generateccss') ) {
            $buffer = cssop_add_criticalcss( $buffer, WP_CONTENT_DIR . '/cache/cssoptimizer/cssop-combined-css-' . md5($post_url) . '.purged.css' );
        }
        
        cssop_write_log('[' . date('m/d/Y h:i:s a', time()) . '] Cache exists. The purged css file is exist for this pages / posts. Execution ended for ' . $post_url);
    } else {
        // do nothing
    }

    flock($fp, LOCK_UN);
    return $buffer;
}

function cssop_noao_run_purgecss( ) {
    $filename = CSSOPTIMIZER_CACHE_DIR . 'css_job_queue.txt';
    $job_array = array();
    $fp = fopen( $filename, "r");
    if (filesize($filename) > 0 && flock($fp, LOCK_SH)) {
        $job_array_str = fread($fp, filesize($filename));
        $job_array = json_decode($job_array_str, true);
    
        flock($fp, LOCK_UN); 
    } 

    if ( is_array($job_array) ) {
        $keys = array_keys(array_column($job_array, 'status'), 'Pending');
        if (!empty($keys)) {
            $array_values_keys = array_values($keys);
            $selected_key = end($array_values_keys);
            $rand_page_url = $job_array[$selected_key]['post_url'];
            $selected_cssname = $job_array[$selected_key]['css_name'];
            $job_queue_css_name = explode(', ', $selected_cssname);

            foreach($job_queue_css_name as $runque_file) {
                $files = CSSOPTIMIZER_CACHE_DIR . '/' . $runque_file;
                if (!file_exists( $files )) {
                    cssop_array_replace_status($job_queue_css_name, 'Cancelled');
                    return false;
                }
            }

            $css_url = array();

            foreach ($job_queue_css_name as $file) {
                array_push($css_url, CSSOPTIMIZER_CACHE_URL . $file);
            }
    
            cssop_write_log('[' . date('m/d/Y h:i:s a', time()) . '] Begin execute Purge CSS on ' . $rand_page_url);
            // save running job
            cssop_array_replace_status($job_queue_css_name, 'Running');
    
            $html_content = file_get_contents($rand_page_url);
            $content_safelist = cssop_extract_css_from_js($html_content);
    
            $token = cssop_get_option('cssoptimizer_options_token');
            $safelist = $content_safelist;
            $safelist = str_replace(array("\r\n", "\n\r", "\r", "\n"), '|', $safelist);
    
            $purgeMethod = apply_filters('purge_css_method', 'purge');
            if ($purgeMethod === 'purge' || $purgeMethod === 'uncss') {  
                // do nothing
            } else {
                $purgeMethod = 'purge';
            }
            $purgeMethod = 'purge_1.3'; // panorazzi
            $post_url = "http://api.zippisite.com/$purgeMethod?pageUrl=" . urlencode($rand_page_url);
    
            $purgeSafelist = apply_filters('get_purge_safelist', $safelist);
            if (isset($purgeSafelist) && !empty($purgeSafelist)) {
                $post_url = $post_url . '&safelist=' . $purgeSafelist;
            }
        
            $purgeToken = apply_filters('get_purge_token', $token);
            if (isset($purgeToken) && !empty($purgeToken)) {
                $post_url = $post_url . "&token=$purgeToken";
            }
            
            $args = array(
                'method'      => 'POST',
                'timeout'     => 25000,
                'headers'     => array(
                    'Content-Type'  => 'text/plain',
                ),
                'body'        => json_encode($css_url)
            );
        
            $response = wp_remote_post( $post_url, $args );
            $body = wp_remote_retrieve_body( $response );
            $data = json_decode($body);
        
            if ( is_wp_error( $response ) ) {
                $error_message = $response->get_error_message();
                cssop_write_log('[' . date('m/d/Y h:i:s a', time()) . '] Error getting result: '. PHP_EOL . $error_message);
            }
    
            $purged_files = explode(',', $data->purge_css);
    
            for ( $i = 0; $i < count($purged_files); $i++ ) {
    
                if (strpos($purged_files[$i], 'purged.css') !== false || strpos($purged_files[$i], 'uncss.css') !== false) {
                    $purified_css = cssop_download_Css($purged_files[$i]);
    
                    if ($purified_css) {
                        $pic_root_url = preg_replace( '/[-a-zA-Z0-9]+_/', '', basename($css_url[$i]), 1 );
                        $pic_root_url = str_replace( '_', '/', $pic_root_url );
                        $pic_root_url = str_replace( '+', '_', $pic_root_url );
    
                        $new_content = array();
                        $new_content[ get_home_url() . '/' . $pic_root_url ] = $purified_css;
                        file_put_contents( CSSOPTIMIZER_CACHE_DIR . basename($css_url[$i]) , cssop_get_content_with_replacements($new_content) );
                    }
                } else {
                    if ($data->statusCode) {
                       cssop_write_log('[' . date('m/d/Y h:i:s a', time()) . '] Purge CSS fail for ' . $rand_page_url . ': Error ' .  $data->statusCode . ' - ' . $data->message );
                    } else {
                       cssop_write_log('[' . date('m/d/Y h:i:s a', time()) . '] Purge CSS fail for ' . $rand_page_url . ': Error - ' . $body );
                    }
                }
            }
    
            // generate critical css
            if (cssop_get_option('cssoptimizer_options_generateccss')) {
                $post_slug = md5($rand_page_url);
            
                $combined_css = WP_CONTENT_URL . '/cache/cssoptimizer/cssop-combined-css-' . $post_slug . '.css';
                if (file_exists(WP_CONTENT_DIR . '/cache/cssoptimizer/cssop-combined-css-' . $post_slug . '.css')) {
                    cssop_write_log('[' . date('m/d/Y h:i:s a', time()) . '] Begin execute Critical CSS on ' . $rand_page_url);
    
                    $ccss_post_url = "http://api.zippisite.com/criticalcss_1.5?cssUrl=" . urlencode($combined_css) . "&pageUrl=" . urlencode($rand_page_url);
                    if (isset($purgeSafelist) && !empty($purgeSafelist)) {
                        $ccss_post_url = $ccss_post_url . '&safelist=' . $purgeSafelist;
                    }
    
                    if (isset($purgeToken) && !empty($purgeToken)) {
                        $ccss_post_url = $ccss_post_url . "&token=$purgeToken";
                    }
            
                    $css_args = array(
                        'method'      => 'POST',
                        'timeout'     => 25000,
                        'headers'     => array(
                            'Content-Type'  => 'text/plain',
                        )
                    );
    
                    $response = wp_remote_post( $ccss_post_url, $css_args );
                    $body = wp_remote_retrieve_body( $response );
                    $data = json_decode($body);
    
                    if ( is_wp_error( $response ) ) {
                        $error_message = $response->get_error_message();
                        cssop_write_log('[' . date('m/d/Y h:i:s a', time()) . '] Error getting result: '. PHP_EOL . $error_message);
                    }        
    
    
                    if ( strpos($data->critical_css, 'critical.css') !== false ) {
                        //sleep(5);
                        $ccss = cssop_download_Css($data->critical_css);

                        if (strpos($ccss, '"error":"Not Found"') !== false) {
                            if ( false !== ( $job_queue = get_transient( 'cssop_ccss_queue' ) ) ) {
                                $ccss_job_array = array_merge(array( array('ccss_dir' => WP_CONTENT_DIR . '/cache/cssoptimizer/cssop-combined-css-' . $post_slug . '.css', 'api_ccss_url' => $data->critical_css)), $job_queue);
                            } else {
                                $ccss_job_array = array( array('ccss_dir' => WP_CONTENT_DIR . '/cache/cssoptimizer/cssop-combined-css-' . $post_slug . '.css', 'api_ccss_url' => $data->critical_css));
                            }
                    
                            set_transient( 'cssop_ccss_queue', $ccss_job_array, DAY_IN_SECONDS );                        
                        } else {
                            if ($ccss) {
                                wp_delete_file( WP_CONTENT_DIR . '/cache/cssoptimizer/cssop-combined-css-' . $post_slug . '.css' );
        
                                file_put_contents(WP_CONTENT_DIR . '/cache/cssoptimizer/cssop-combined-css-' . $post_slug . '.purged.css', $ccss);
                            }
                        }
                    } else {
                        if ($data->statusCode) {
                            cssop_write_log('[' . date('m/d/Y h:i:s a', time()) . '] Critical CSS fail for ' . $rand_page_url . ': Error ' .  $data->statusCode . ' - ' . $data->message );
                        } else {
                            cssop_write_log('[' . date('m/d/Y h:i:s a', time()) . '] Critical CSS fail for ' . $rand_page_url . ': Error - ' .  $body );
                        }
                    }
        
                    cssop_write_log('[' . date('m/d/Y h:i:s a', time()) . '] Finish execute Critical CSS on ' . $rand_page_url);
                }
            }        
            
            // save running job
            cssop_array_replace_status( $job_queue_css_name, 'Completed');
    
            cssop_write_log('[' . date('m/d/Y h:i:s a', time()) . '] Finish execute Purge CSS on ' . $rand_page_url);
            cssop_write_log('[' . date('m/d/Y h:i:s a', time()) . '] Finish execute CSS Optimizer on ' . $rand_page_url);
    
            cssop_clear_all_plugin_cache();
        }
    }

    cssop_check_ccss();
}

function cssop_template_redirect() {

    if ((in_array( 'autoptimize/autoptimize.php', apply_filters( 'active_plugins', cssop_get_option( 'active_plugins' ) ) ) && 'on' === cssop_get_option( 'autoptimize_css', false )) ||
    (in_array( 'wp-rocket/wp-rocket.php', apply_filters( 'active_plugins', cssop_get_option( 'active_plugins' ) ) ) && 1 === get_rocket_option( 'minify_css' )) ||
    (in_array( 'wp-fastest-cache/wpFastestCache.php', apply_filters( 'active_plugins', cssop_get_option( 'active_plugins' ) ) ) && isset($GLOBALS["wp_fastest_cache_options"]->wpFastestCacheMinifyCss) ) ||
    ( in_array( 'sg-cachepress/sg-cachepress.php', apply_filters( 'active_plugins', cssop_get_option( 'active_plugins' ) ) ) && 1 === (int) cssop_get_option( 'siteground_optimizer_optimize_css' ) ) ) {
        // do nothing
    } else if ( in_array( 'w3-total-cache/w3-total-cache.php', apply_filters( 'active_plugins', cssop_get_option( 'active_plugins' ) ) ) ) {
        $w3tcConfig     = file_get_contents( WP_CONTENT_DIR . '/w3tc-config/master.php' );
        $w3_minify_enabled = strpos( $w3tcConfig, '"minify.enabled": true' );
        $w3_minify_css_enabled = strpos( $w3tcConfig, '"minify.css.enable": true' );
        if (!$w3_minify_enabled || !$w3_minify_css_enabled) {
            ob_start();
            ob_start( 'cssop_noao_execute_purgecss' );
        }
    } else {
        ob_start();
        ob_start( 'cssop_noao_execute_purgecss' );
    }
}
add_action( 'template_redirect', 'cssop_template_redirect' );

function cssop_plugins_loaded() {
    if ( in_array( 'sg-cachepress/sg-cachepress.php', apply_filters( 'active_plugins', cssop_get_option( 'active_plugins' ) ) ) && 
        1 === (int) cssop_get_option( 'siteground_optimizer_optimize_css' ) ) {
            ob_start( 'cssop_noao_execute_purgecss' );
    }
}
add_action( 'plugins_loaded' , 'cssop_plugins_loaded' );


// wooncherk

function cssop_test_connection() {
    cssop_write_log('[' . date('m/d/Y h:i:s a', time()) . '] Begin execute test connection for CSS Optimizer.');

    $test_link = 'http://api.zippisite.com/test_post';

    $args = array(
        'method'      => 'POST',
        'timeout'     => 45,
        'headers'     => array(
            'Content-Type'  => 'text/plain',
        )
    );
    
    $response = wp_remote_post( $test_link, $args );

    if ( is_wp_error( $response ) ) {
        $error_message = $response->get_error_message();
        cssop_write_log('[' . date('m/d/Y h:i:s a', time()) . '] Error getting test result: '. PHP_EOL . $error_message);
        return false;
    }
    
    cssop_write_log('[' . date('m/d/Y h:i:s a', time()) . '] Finish execute test connection for CSS Optimizer.');
}

add_filter( 'autoptimize_html_after_minify', 'cssop_noao_execute_purgecss', 10, 1 );

// wooncherk

add_filter( 'rocket_buffer', 'cssop_noao_execute_purgecss', 50, 1 );

add_filter( 'wpfc_buffer_callback_filter', 'cssop_noao_execute_purgecss', 10, 1 );

add_filter( 'w3tc_minify_processed', 'cssop_noao_execute_purgecss', 10, 1 );
