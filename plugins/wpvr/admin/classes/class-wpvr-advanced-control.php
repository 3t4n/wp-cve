<?php 
if (!defined('ABSPATH')) exit; // Exit if accessed directly
/**
 * Responsible for managing advanced control setting
 *
 * @link       http://rextheme.com/
 * @since      8.0.0
 *
 * @package    Wpvr
 * @subpackage Wpvr/admin/classes
 */

class WPVR_Advanced_Control extends Singleton {

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
        <div class="advanced-settings-content inner-single-content" id="gen-advanced">
            <?php self::render_meta_fields($postdata);?>
        </div>
        <?php
        ob_end_flush();
    }
    

    /**
     * Render Advanced Setting Meta Fields
     * 
     * @return void
     * @since 8.0.0
     */
    public static function render_meta_fields($postdata) {
        ob_start();
        ?>
        
        <div class="content-wrapper">
            <div class="left">
                <?php WPVR_Meta_Field::render_advanced_settings_left_fields($postdata) ?>
            </div>
            <!-- end left -->

            <div class="right">
                <?php WPVR_Meta_Field::render_advanced_settings_right_fields($postdata) ?>
            </div>
            <!-- end right -->
        </div>
        
        <?php
        ob_end_flush();
    }
}
