<?php







/**







 * Plugin Name: AI Post Generator | AutoWriter







 * Description: With this plugin you can generate posts written by AI







 * Version:     3.3







 * Author:      AutoWriter







 * Author URI:  https://autowriter.tech







 * Text Domain:  ai-post-generator







 *







 *







 * @package    AIPostGenerator







 * @since      1.0.0







 * @copyright  Copyright (c) 2021, Kekotron







 * @license    GPL-2.0+







 */















// Plugin directory







define( 'AI_POST_GENERATOR_PLUGIN_DIR' , plugin_dir_path( __FILE__ ) );







define( 'AI_POST_GENERATOR_PLUGIN_URL' , plugin_dir_url( __FILE__ ) );








register_activation_hook( __FILE__, 'ai_post_generator_activation_hook' );

function ai_post_generator_activation_hook() {
    set_transient( 'ai_post_generator_transient', true, 5 );
}

add_action( 'admin_notices', 'ai_post_generator_notice' );

function ai_post_generator_notice(){

    /* Check transient, if available display notice */
    if( get_transient( 'ai_post_generator_transient' ) ){

        ?>
        <div class="updated notice is-dismissible">
            <p>Thank you for using AutoWriter! <strong>You are awesome</strong>.</p>
        </div>
        <script type="text/javascript">
            window.location.href= "<?php echo get_admin_url(); ?>admin.php?page=ai_post_generator"
        </script>
        <?php
        /* Delete transient, only display this notice once. */
        delete_transient( 'ai_post_generator_transient' );
    }
}






// Plugin files







require_once( AI_POST_GENERATOR_PLUGIN_DIR . '/inc/insert-head.php' );	// Insert code (head)







require_once( AI_POST_GENERATOR_PLUGIN_DIR . '/inc/insert-body.php' );	// Insert code (body)
require_once( AI_POST_GENERATOR_PLUGIN_DIR . '/inc/insert-body-video-academy.php' );	// Insert code (body)
require_once( AI_POST_GENERATOR_PLUGIN_DIR . '/inc/insert-body-buy-tokens.php' );	// Insert code (body)
require_once( AI_POST_GENERATOR_PLUGIN_DIR . '/inc/insert-body-training-model.php' );	// Insert code (body)
require_once( AI_POST_GENERATOR_PLUGIN_DIR . '/inc/insert-body-settings.php' );	// Insert code (body)
require_once( AI_POST_GENERATOR_PLUGIN_DIR . '/inc/new-plugin.php' );	// Insert code (body)























