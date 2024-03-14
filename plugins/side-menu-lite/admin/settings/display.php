<?php
/**
 * Display
 *
 * @package     Wow_Pluign
 * @copyright   Copyright (c) 2018, Dmytro Lobov
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
include_once( 'options/display.php' );

?>
<fieldset class="itembox">
    <legend>
        <span class="set-icon fa-solid fa-laptop"></span>
		<?php esc_attr_e( 'Show on devices', 'side-menu' ); ?>
    </legend>
    <div class="columns is-multiline has-borderbox">

        <div class="column is-6">
            <label class="checkbox label checkLabel">
				<?php self::option( $include_more_screen ); ?><?php esc_attr_e( "Don't show on screens more", 'side-menu' ); ?><?php self::tooltip( $show_screen_help ); ?>
            </label>
            <div class="field has-addons">
				<?php self::option( $screen_more ); ?>
                <div class="control">
                    <span class="addon">px</span>
                </div>
            </div>
        </div>

        <div class="column is-6">
            <label class="checkbox label checkLabel">
				<?php self::option( $include_mobile ); ?><?php esc_attr_e( "Don't show on screens less", 'side-menu' ); ?><?php self::tooltip( $include_mobile_help ); ?>
            </label>
            <div class="field has-addons">
				<?php self::option( $screen ); ?>
                <div class="control">
                    <span class="addon">px</span>
                </div>
            </div>
        </div>

    </div>
</fieldset>

<fieldset class="itembox">
    <legend>
        <i class="set-icon fa-solid fa-font-awesome"></i>
		<?php esc_attr_e( 'Font Awesome 6 style', 'side-menu' ); ?>
    </legend>
    <div class="columns is-multiline has-borderbox">
        <div class="column is-4">
            <label class="checkbox label">
				<?php self::option( $disable_fontawesome ); ?><?php esc_attr_e( "Disable", 'side-menu' ); ?><?php self::tooltip( $disable_fontawesome_help ); ?>
            </label>
        </div>
    </div>
</fieldset>