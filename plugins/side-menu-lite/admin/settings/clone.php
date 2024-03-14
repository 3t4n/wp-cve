<?php
/**
 * Elements for clone
 *
 * @package     Wow_Plugin
 * @copyright   Copyright (c) 2018, Dmytro Lobov
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

include_once( 'options/clone.php' );
?>

<div class="panel">
    <div class="panel-heading">
        <div class="level-item icon-select" style="background: #00494f; color: #ffffff;">
            <i class="fas fa-hand-point-up"></i>
        </div>
        <div class="level-item">
            <span class="item-label-text">(<?php esc_attr_e( 'no label', 'side-menu' ); ?>)</span>
        </div>
        <div class="level-item element-type">
            Link
        </div>
        <div class="level-item toogle-element">
            <span class="dashicons dashicons-arrow-down is-hidden"></span>
            <span class="dashicons dashicons-arrow-up"></span>
        </div>
    </div>
    <div class="toogle-content">
        <div class="panel-block">
            <div class="field">
                <label class="label is-small">
					<?php esc_attr_e( 'Label Text', 'side-menu' ); ?>
                </label>
				<?php self::option( $menu_1_item_tooltip ); ?>
            </div>
        </div>

        <p class="panel-tabs">
            <a class="is-active" data-tab="1"><?php esc_attr_e("Type",'side-menu' ); ?></a>
            <a data-tab="2"><?php esc_attr_e("Icon",'side-menu' ); ?></a>
            <a data-tab="3"><?php esc_attr_e( "Style", 'side-menu' ); ?></a>
            <a data-tab="4"><?php esc_attr_e("Attributes",'side-menu' ); ?></a>
        </p>
        <div data-tab-content="1" class="tabs-content">
            <div class="panel-block">
                <div class="field">
                    <label class="label">
						<?php esc_attr_e( 'Item type', 'side-menu' ); ?><?php self::tooltip( $menu_1_item_type_help ); ?>

                    </label>
					<?php self::option( $menu_1_item_type ); ?>
                </div>
                <div class="field item-link">
                    <label class="label item-link-text">
						<?php esc_attr_e( 'Link', 'side-menu' ); ?>
                    </label>
					<?php self::option( $menu_1_item_link ); ?>
                </div>

            </div>
            <label class="panel-block item-link-blank">
				<?php self::option( $menu_1_new_tab ); ?><?php esc_attr_e( 'Open link in a new tab', 'side-menu' ); ?>
            </label>
        </div>
        <div data-tab-content="2" class="tabs-content is-hidden">
            <div class="panel-block icon-default">
                <div class="field">
                    <label class="label">
						<?php esc_attr_e( 'Icon', 'side-menu' ); ?>
                    </label>
					<?php self::option( $menu_1_item_icon ); ?>
                </div>
            </div>
        </div>
        <div data-tab-content="3" class="tabs-content is-hidden">
            <div class="panel-block">
                <div class="columns">
                    <div class="field column">
                        <label class="label">
						    <?php esc_attr_e( 'Icon Сolor', 'side-menu' ); ?>
                        </label>
					    <?php self::option( $menu_1_iconcolor ); ?>
                    </div>
                    <div class="field column">
                        <label class="label">
						    <?php esc_attr_e( 'Font Color', 'side-menu' ); ?>
                        </label>
					    <?php self::option( $menu_1_color ); ?>
                    </div>
                </div>
            </div>
            <div class="panel-block">
                <div class="columns">
                    <div class="field column">
                        <label class="label">
						    <?php esc_attr_e( 'Background', 'side-menu' ); ?>
                        </label>
					    <?php self::option( $menu_1_bcolor ); ?>
                    </div>
                    <div class="field column">
                        <label class="label">
						    <?php esc_attr_e( 'Background Hover', 'side-menu' ); ?>
                        </label>
					    <?php self::option( $menu_1_hbcolor ); ?>
                    </div>
                </div>
            </div>
        </div>
        <div data-tab-content="4" class="tabs-content is-hidden">
            <div class="panel-block">
                <div class="field">
                    <label class="label">
						<?php esc_attr_e( 'ID for element', 'side-menu' ); ?>
                    </label>
					<?php self::option( $menu_1_button_id ); ?>
                </div>
            </div>
            <div class="panel-block">
                <div class="field">
                    <label class="label">
						<?php esc_attr_e( 'Class for element', 'side-menu' ); ?>
                    </label>
					<?php self::option( $menu_1_button_class ); ?>
                </div>
            </div>
            <div class="panel-block">
                <div class="field">
                    <label class="label">
				        <?php esc_attr_e( 'Attribute: rel', 'side-menu' ); ?>
                    </label>
			        <?php self::option( $menu_1_link_rel ); ?>
                </div>
            </div>
        </div>
        <div class="panel-block actions">
            <a class="item-delete"><?php esc_attr_e('Remove', 'side-menu');?></a>
        </div>
    </div>
</div>
