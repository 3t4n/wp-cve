<?php
/**
 * This file is the template to configure the shortcode
 *
 * @package YITH WooCommerce Ajax Search
 * @since   2.0.0
 * @author  YITH <plugins@yithemes.com>
 *
 * @var string $slug The shortcode id
 * @var array $shortcode The shortcode
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$settings_tabs = ywcas()->settings->get_shortcode_tabs();
$i             = $i ?? 2;
$can_be_cloned = defined( 'YITH_WCAS_PREMIUM' );
$options       = $shortcode['options'];
$default = ywcas()->settings->get_default_shortcode_options();
?>
<div id="<?php echo esc_attr( $slug ); ?>" class="ywcas-row">
    <div class="ywcas-preview">
        <div class="column-name"
             data-colname="<?php esc_html_e( 'Name', 'yith-woocommerce-ajax-search' ); ?>"><?php echo esc_html( $shortcode['name'] ); ?></div>
        <div class="column-code" data-colname="<?php esc_html_e( 'Code', 'yith-woocommerce-ajax-search' ); ?>">
			<?php
			yith_plugin_fw_get_field(
				array(
					'type'  => 'copy-to-clipboard',
					'value' => $shortcode['code'],
				),
				true
			);
			?>
        </div>
        <div class="actions column-actions"
             data-colname="<?php echo esc_html_x( 'Actions', 'title of hidden actions column', 'yith-woocommerce-ajax-search' ); ?>">
			<?php
			$actions = array(
				'edit' => array(
					'type'   => 'action-button',
					'title'  => _x( 'Edit', 'Edit the shortcode', 'yith-woocommerce-ajax-search' ),
					'action' => 'edit',
				),
			);

			if ( $i > 0 ) {
				$actions['trash'] = array(
					'type'         => 'action-button',
					'title'        => _x( 'Trash', 'Shortcode action', 'yith-woocommerce-ajax-search' ),
					'action'       => 'trash',
					'url'          => '',
					'confirm_data' => array(
						'title'               => __( 'Move to trash?', 'yith-woocommerce-ajax-search' ),
						'message'             => __( 'Are you sure you want to delete this shortcode?', 'yith-woocommerce-ajax-search' ),
						'cancel-button'       => __( 'No', 'yith-woocommerce-ajax-search' ),
						'confirm-button'      => _x( 'Yes, move to trash', 'Trash confirmation action', 'yith-woocommerce-ajax-search' ),
						'confirm-button-type' => 'delete',
					),
				);
			}
			if ( $can_be_cloned ) {
				$actions['duplicate'] = array(
					'type'   => 'action-button',
					'title'  => _x( 'Duplicate', 'Shortcode action', 'yith-woocommerce-ajax-search' ),
					'action' => 'duplicate',
					'icon'   => 'clone',
					'url'    => '',
				);
			}
			yith_plugin_fw_get_action_buttons(
				$actions,
				true
			);
			?>
        </div>
    </div>
    <div class="ywcas-edit" data-target="<?php echo esc_attr( $slug ); ?>">
        <form class="ywcas-shortcode__options__form" data-preset="<?php echo esc_attr( $slug ); ?>">
            <ul class="yith-plugin-fw__tabs">
				<?php foreach ( $settings_tabs as $key => $label ) : ?>
                    <li class="yith-plugin-fw__tab <?php echo esc_attr( $key ); ?>">
                        <a class="yith-plugin-fw__tab__handler"
                           href="#tab-panel-<?php echo esc_attr( $slug ) . '-' . esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?></a>
                    </li>
				<?php endforeach; ?>
            </ul>
            <div class="yith-plugin-ui yith-plugin-fw ywcas_shortcode__options__container">
				<?php foreach ( $settings_tabs as $tab_key => $tab_label ) : ?>
                    <div class="yith-plugin-fw__tab-panel yith-plugin-fw__panel__section__content"
                         id="tab-panel-<?php echo esc_attr( $slug ) . '-' . esc_attr( $tab_key ); ?>">
						<?php foreach ( ywcas()->settings->get_shortcode_fields( $tab_key, $slug ) as $key => $field ) : ?>
							<?php
							$field['value'] = $options[ $tab_key ][ $key ] ?? $default[$tab_key][$key];
							$field['name']  = "ywcas_shortcode[{$slug}][{$tab_key}][{$key}]";
							?>
                            <div class="yith-plugin-fw__panel__option__content" <?php echo yith_field_deps_data( $field ); ?>>
                                <div class="yith-plugin-fw__panel__option yith-plugin-fw__panel__option--<?php echo esc_attr( $field['type'] ); ?>">
                                    <div class="yith-plugin-fw__panel__option__label">
                                        <label for="<?php echo esc_attr( $field['id'] ); ?>">
											<?php echo esc_html( $field['label'] ?? '' ); ?>
                                        </label>
                                    </div>
                                    <div class="ywcas-options--container">
										<?php yith_plugin_fw_get_field( $field, true ); ?>
										<?php if ( isset( $field['desc'] ) ) : ?>
                                            <div class="yith-plugin-fw__panel__option__description">
												<?php echo wp_kses_post( $field['desc'] ); ?>
                                            </div>
										<?php endif; ?>
                                    </div>
                                </div>
                            </div>
						<?php endforeach; ?>
                    </div>
				<?php endforeach; ?>
            </div>
                <div class="ywcas-save-shortcode">
                    <button class="yith-plugin-fw__button--primary yith-plugin-fw__button--xl"><?php esc_html_e( 'Save', 'yith-woocommerce-ajax-search' ); ?></button>
                </div>
        </form>
    </div>
</div>
