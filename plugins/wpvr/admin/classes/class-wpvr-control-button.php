<?php 
if (!defined('ABSPATH')) exit; // Exit if accessed directly
/**
 * Responsible for managing control button tab content
 *
 * @link       http://rextheme.com/
 * @since      8.0.0
 *
 * @package    Wpvr
 * @subpackage Wpvr/admin/classes
 */

class WPVR_Control_Button extends Singleton {

    /**
     * Render Advanced Control Tab Content
     * 
     * @return void
     * @since 8.0.0
     */
    public static function render($postdata)
    {
        ob_start();
        ?>
        <div class="control-settings-content inner-single-content" id="gen-control">
            <?php self::render_meta_fields($postdata);?>
        </div>
        <?php
        ob_end_flush();
    }


    /**
     * Render Control Button Meta Fields
     * 
     * @return void
     * @since 8.0.0
     */
    public static function render_meta_fields($postdata) {
        ob_start();
        ?>

        <div class="content-wrapper">
            <div class="left">
                <?php WPVR_Meta_Field::render_control_button_left_fields($postdata) ?>
            </div>
            <!-- end left -->

            <div class="right">
                <?php WPVR_Meta_Field::render_control_button_right_fields($postdata) ?>
            </div>
            <!-- end right -->
        </div>
        
        <?php
        ob_end_flush();
    }
}
