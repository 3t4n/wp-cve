<?php
/**
 * Settings
 *
 * @package     Wow_Plugin
 * @copyright   Copyright (c) 2018, Dmytro Lobov
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

include_once( 'options/menu.php' );
?>
<div class="menu-items">
	<?php if ( $count_i > 0 ) : ?>

		<?php for ( $i = 0; $i < $count_i; $i ++ ) : ?>
            <div class="panel">
                <div class="panel-heading">
                    <div class="level-item icon-select" style="background: #00494f; color: #ffffff;">
                    </div>
                    <div class="level-item">
						<span class="item-label-text"><?php $item_header = ! empty( $param['menu_1']['item_tooltip'][ $i ] ) ? $param['menu_1']['item_tooltip'][ $i ] : '(' . esc_attr__( 'no label', 'side-menu' ) . ')';
                            echo esc_html( $item_header ); ?></span>
                    </div>
                    <div class="level-item element-type">
                    </div>
                    <div class="level-item toogle-element">
                        <span class="dashicons dashicons-arrow-down"></span>
                        <span class="dashicons dashicons-arrow-up is-hidden"></span>
                    </div>
                </div>
                <div class="toogle-content is-hidden">
                    <div class="panel-block">
                        <div class="field">
                            <label class="label is-small">
								<?php esc_attr_e( 'Label Text', 'side-menu' ); ?><?php self::tooltip( $item_tooltip_help ); ?>
                            </label>
							<?php self::option( $item_tooltip_[ $i ] ); ?>
                        </div>
                    </div>

                    <p class="panel-tabs">
                        <a class="is-active" data-tab="1"><?php esc_attr_e("Type",'side-menu' ); ?></a>
                        <a data-tab="2"><?php esc_attr_e("Icon",'side-menu' ); ?></a>
                        <a data-tab="3"><?php esc_attr_e("Style",'side-menu' ); ?></a>
                        <a data-tab="4"><?php esc_attr_e("Attributes",'side-menu' ); ?></a>
                    </p>
                    <div data-tab-content="1" class="tabs-content">
                        <div class="panel-block">
                            <div class="field">
                                <label class="label">
									<?php esc_attr_e( 'Item type', 'side-menu' ); ?><?php self::tooltip( $item_type_help ); ?>

                                </label>
								<?php self::option( $item_type_[ $i ] ); ?>
                            </div>
                            <div class="field item-link">
                                <label class="label item-link-text">
									<?php esc_attr_e( 'Link', 'side-menu' ); ?>
                                </label>
								<?php self::option( $item_link_[ $i ] ); ?>
                            </div>

                        </div>
                        <label class="panel-block item-link-blank">
							<?php self::option( $new_tab_[ $i ] ); ?><?php esc_attr_e( 'Open link in a new tab', 'side-menu' ); ?>
                        </label>
                    </div>
                    <div data-tab-content="2" class="tabs-content is-hidden">
                        <div class="panel-block icon-default">
                            <div class="field">
                                <label class="label">
									<?php esc_attr_e( 'Icon', 'side-menu' ); ?>
                                </label>
								<?php self::option( $item_icon_[ $i ] ); ?>
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
					                <?php self::option( $iconcolor_[ $i ] ); ?>
                                </div>
                                <div class="field column">
                                    <label class="label">
						                <?php esc_attr_e( 'Font Color', 'side-menu' ); ?>
                                    </label>
					                <?php self::option( $color_[ $i ] ); ?>
                                </div>
                            </div>
                        </div>
                        <div class="panel-block">
                            <div class="columns">
                                <div class="field column">
                                    <label class="label">
						                <?php esc_attr_e( 'Background', 'side-menu' ); ?>
                                    </label>
					                <?php self::option( $bcolor_[ $i ] ); ?>
                                </div>

                                <div class="field column">
                                    <label class="label">
						                <?php esc_attr_e( 'Background Hover', 'side-menu' ); ?>
                                    </label>
					                <?php self::option( $hbcolor_[ $i ] ); ?>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div data-tab-content="4" class="tabs-content is-hidden">
                        <div class="panel-block">
                            <div class="field">
                                <label class="label">
									<?php esc_attr_e( 'ID for element', 'side-menu' ); ?><?php self::tooltip( $button_id_help ); ?>
                                </label>
								<?php self::option( $button_id_[ $i ] ); ?>
                            </div>
                        </div>
                        <div class="panel-block">
                            <div class="field">
                                <label class="label">
									<?php esc_attr_e( 'Class for element', 'side-menu' ); ?><?php self::tooltip( $button_class_help ); ?>
                                </label>
								<?php self::option( $button_class_[ $i ] ); ?>
                            </div>
                        </div>
                        <div class="panel-block">
                            <div class="field">
                                <label class="label">
				                    <?php esc_attr_e( 'Attribute: rel', 'side-menu' ); ?>
                                </label>
			                    <?php self::option( $link_rel_[ $i ] ); ?>
                            </div>
                        </div>
                    </div>
                    <div class="panel-block actions">
                        <a class="item-delete">Remove</a>
                    </div>
                </div>
            </div>

		<?php endfor; ?>

	<?php endif; ?>
</div>

<div class="submit-bottom">
    <input type="button" value="<?php esc_attr_e( 'Add item', 'side-menu' ); ?>" class="add-item">
</div>
