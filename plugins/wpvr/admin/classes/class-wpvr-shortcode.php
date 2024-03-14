<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
/**
 * Responsible for managing shortcode content on Setup metabox
 *
 * @link       http://rextheme.com/
 * @since      1.0.0
 *
 * @package    Wpvr
 * @subpackage Wpvr/admin/classes
 */

class WPVR_Shortcode_TEST {

    function __construct()
    {
    }

    /**
     * Render shortcode content on metabox
     * @return null
     */
    public function render_shortcode()
    {
        ob_start();
        ?>
        
        <?php    
        $post = get_post();
        $id = $post->ID;
        ?>
        <h4 class="area-title"><?php echo __('Using this Tour', 'wpvr'); ?></h4>

        <div class="shortcode-wrapper">
            <div class="single-shortcode classic">
                <span class="shortcode-title"><?php echo __('For Classic Editor:', 'wpvr'); ?></span>

                <div class="field-wapper">
                    <span><?php echo __('To use this WP VR tour in your posts or pages use the following shortcode ', 'wpvr'); ?></span>

                    <div class="shortcode-field">
                        <p class="copycode" id="copy-shortcode-video">[wpvr id="<?php echo $id; ?>"]</p>
                        <span id="wpvr-copy-shortcode-video" class="wpvr-copy-shortcode">
                            <img loading="lazy" src=" <?php echo WPVR_PLUGIN_DIR_URL; ?>admin/icon/copy.png" alt="icon" />
                        </span>
                    </div>

                    <span id="wpvr-copied-notice-video" class="wpvr-copied-notice"></span>

                </div>
            </div>
        </div>

        <?php
        ob_end_flush();
    }

}