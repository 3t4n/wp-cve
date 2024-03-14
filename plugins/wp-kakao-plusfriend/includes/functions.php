<?php
/**
 * Core plugin functionality.
 *
 */

namespace KakaoPlusfriend;

use KakaoPlusfriend\Settings;
use \WP_Error as WP_Error;

// Default setup routine
function setup() {
    add_action( 'init', __NAMESPACE__ . '\init' );
    add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_assets' );
    add_action( 'wp_footer', __NAMESPACE__ . '\add_the_script' );
}

// Initializes the plugin
function init() {
}

// Activate the plugin
function activate() {
	// First load the init scripts in case any rewrite functionality is being loaded
    // init();
    // flush_rewrite_rules();
}

// Deactivate the plugin
// !! Uninstall routines should be in uninstall.php
function deactivate() {
}

/**
 * Enqueue scripts for front-end
 *
 */
function enqueue_assets() {
	// Load the Kakao JavaScript SDK.
    wp_enqueue_script( 'kakao-js-sdk', '//developers.kakao.com/sdk/js/kakao.min.js' );

	// Load the plugin front-end style.
	wp_enqueue_style( 'kakao-plusfriend-style', KAKAO_PLUSFRIEND_URL . 'assets/css/style.css', null, null );
}

/**
 * Add Plusfriend JS script
 *
 */
function add_the_script() {
    $settings = Settings\get_settings();
    if ( $settings['friend_btn'] ) echo '<div id="plusfriend-addfriend-button"></div>';    
    if ( $settings['chat_btn'] ) echo '<div id="plusfriend-chat-button"></div>'; 
?>
<script type='text/javascript'>Kakao.init('<?php echo $settings['app_key']; ?>');</script>
<?php
    if ( $settings['friend_btn'] || $settings['chat_btn'] ) { ?>
        <script type='text/javascript'>
            //<![CDATA[
                <?php if ( $settings['friend_btn'] ) { ?>    
                    Kakao.PlusFriend.createAddFriendButton({
                        container: '#plusfriend-addfriend-button',
                        plusFriendId: '<?php echo $settings['plusfriend_id']; ?>',
                        size: '<?php echo $settings['friend_btn_size']; ?>',
                        color: '<?php echo $settings['friend_btn_color']; ?>',
                        supportMultipleDensities: <?php echo ($settings['friend_btn_supportMultipleDensities'] ? 
                            'true' : 'false'); ?>
                    }); 
                <?php } ?>
                <?php if ( $settings['chat_btn'] ) { ?>    
                    Kakao.PlusFriend.createChatButton({
                        container: '#plusfriend-chat-button',
                        plusFriendId: '<?php echo $settings['plusfriend_id']; ?>',
                        title: '<?php echo $settings['chat_btn_title']; ?>',
                        size: '<?php echo $settings['chat_btn_size']; ?>',
                        color: '<?php echo $settings['chat_btn_color']; ?>',
                        shape: '<?php echo $settings['chat_btn_shape']; ?>',
                        supportMultipleDensities: <?php echo ($settings['friend_btn_supportMultipleDensities'] ? 
                            'true' : 'false'); ?>
                    });
                <?php } ?>
            //]]>
        </script>
<?php                    
    }
}


