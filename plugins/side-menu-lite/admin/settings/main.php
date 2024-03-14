<?php
/**
 * Main Settings
 *
 * @package     Wow_Plugin
 * @copyright   Copyright (c) 2018, Dmytro Lobov
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
include_once( 'options/main.php' );

?>

<fieldset>
    <legend>
        <i class="set-icon fa-solid fa-sliders"></i>
		<?php esc_html_e( 'General', 'side-menu' ); ?>
    </legend>

<div class="columns is-multiline has-borderbox">
    <div class="column is-4">
        <div class="field">
            <label class="label">
				<?php esc_attr_e( 'Position', 'side-menu' ); ?><?php echo self::tooltip( $menu_help ); ?>
            </label>
			<?php self::option( $menu ); ?>
        </div>

    </div>


    <div class="column is-4">
        <div class="field">
            <label class="label">
				<?php esc_attr_e( 'Item Height', 'side-menu' ); ?><?php echo self::tooltip( $height_help ); ?>
            </label>
            <div class="field has-addons">
				<?php self::option( $height ); ?>
                <div class="control">
                    <span class="addon">px</span>
                </div>
            </div>
        </div>
    </div>
    <div class="column is-4">
        <div class="field">
            <label class="label">
				<?php esc_attr_e( 'Space between items', 'side-menu' ); ?><?php echo self::tooltip( $gap_help ); ?>
            </label>
            <div class="field has-addons">
				<?php self::option( $gap ); ?>
                <div class="control">
                    <span class="addon">px</span>
                </div>
            </div>
        </div>
    </div>


    <div class="column is-4">
        <div class="field">
            <label class="label">
				<?php esc_attr_e( 'Font size', 'side-menu' ); ?><?php echo self::tooltip( $fontsize_help ); ?>
            </label>
            <div class="field has-addons">
				<?php self::option( $fontsize ); ?>
                <div class="control">
                    <span class="addon">px</span>
                </div>
            </div>
        </div>
    </div>


    <div class="column is-4">
        <div class="field">
            <label class="label">
				<?php esc_attr_e( 'Icon size', 'side-menu' ); ?><?php echo self::tooltip( $iconsize_help ); ?>
            </label>
            <div class="field has-addons">
				<?php self::option( $iconsize ); ?>
                <div class="control">
                    <span class="addon">px</span>
                </div>
            </div>
        </div>
    </div>
    <div class="column is-4">
        <div class="field">
            <label class="label">
				<?php esc_attr_e( 'Z-index', 'side-menu' ); ?><?php echo self::tooltip( $zindex_help ); ?>
            </label>
			<?php self::option( $zindex ); ?>
        </div>
    </div>
    <div class="column is-4">
        <div class="field">
            <label class="label">
				<?php esc_attr_e( 'Border width', 'side-menu' ); ?><?php echo self::tooltip( $bwidth_help ); ?>
            </label>
            <div class="field has-addons">
				<?php self::option( $bwidth ); ?>
                <div class="control">
                    <span class="addon">px</span>
                </div>
            </div>
        </div>
    </div>

    <div class="column is-4">
        <div class="field">
            <label class="label">
				<?php esc_attr_e( 'Border color', 'side-menu' ); ?><?php echo self::tooltip( $bcolor_help ); ?>
            </label>
			<?php self::option( $bcolor ); ?>
        </div>
    </div>

</div>

</fieldset>