<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/*
* Widget modueles
* @since 1.0
*/

	$settings_id      = 'shop_ready_modules';
	$switch_js_target = $settings_id . '_modules';

	$nonce_field_val  = '_shop_ready_modules';
	$action_key       = 'shop_ready_modules_options';
	$option_key       = 'shop_ready_modules';
	$label_identifier = 'quomodo-modules-';

	$heading             = esc_html__( 'Modules', 'shopready-elementor-addon' );
	$components_settings = shop_ready_get_transform_options( shop_ready_modules_config()->all(), $settings_id );
	$total_modules       = is_array( $components_settings ) ? count( $components_settings ) : 0;
?>
<!-- Modules Swicher  -->
<form id="woo-ready-admin-module-form" class="woo-ready-components-action quomodo-module-data"
    action="<?php echo esc_url(admin_url( 'admin-post.php' )); ?>" method="post">
    <div class="quomodo-container-wrapper">
        <div class="quomodo-row-wrapper">
            <div class="woo-ready-component-form-wrapper modules">
                <div class="woo-ready-components-topbar">
                    <div class="woo-ready-title">
                        <h3 class="title"><i class="dashicons dashicons-arrow-left-alt woo-ready-offcanvas"></i>
                            <?php
							echo esc_html( $heading );
							echo wp_kses_post('( ' . esc_html( $total_modules ) . ' )');
							?>
                        </h3>
                    </div>
                    <div class="woo-ready-savechanges">
                        <div class="woo-ready-admin-search">
                            <input data-target="<?php echo esc_attr( $switch_js_target ); ?>"
                                placeholder="<?php echo esc_attr__( 'Search here', 'shopready-elementor-addon' ); ?>"
                                class="quomodo_text woo-ready-element-search" id="woo-ready-module-search"
                                type="search">
                        </div>
                        <div class="woo-ready-check-all">
                            <div class="quomodo_switch_common woo-ready-common">
                                <div data-target="<?php echo esc_attr( $switch_js_target ); ?>"
                                    class="quomodo_sm_switch woo-ready-enable-all-switch">
                                    <strong>
                                        <?php echo esc_html__( 'Enable All', 'shopready-elementor-addon' ); ?>
                                    </strong>
                                </div>
                            </div>
                        </div>
                        <div class="woo-ready-check-all">
                            <div class="quomodo_switch_common woo-ready-common">
                                <div data-target="<?php echo esc_attr( $switch_js_target ); ?>"
                                    class="quomodo_sm_switch woo-ready-disable-all-switch"
                                    id="quomodo-components-all-disable">
                                    <strong>
                                        <?php echo esc_html__( 'Disable All', 'shopready-elementor-addon' ); ?>
                                    </strong>

                                </div>
                            </div>
                        </div>
                        <div class="woo-ready-admin-button">
                            <button type="submit" class="woo-ready-component-submit button woo-ready-submit-btn">
                                <i class="dashicons dashicons-yes"></i>
                                <?php echo esc_html__( 'Save Change', 'shopready-elementor-addon' ); ?>
                            </button>
                        </div>

                    </div>
                </div>
                <div class="quomodo-container">
                    <div class="quomodo-row woo-ready-component-row">
                        <?php if ( is_array( $components_settings ) ) : ?>
                        <?php foreach ( $components_settings as $item_key => $item ) : ?>
                        <div
                            class="woo-ready-col quomodo-col-xl-3 quomodo-col-lg-3 quomodo-col-md-5 <?php echo esc_attr( $item['is_pro'] ? 'shop-ready-pro-orde' : '' ); ?>">
                            <div
                                class="quomodo_switch_common woo-ready-common <?php echo esc_attr( $item['is_pro'] ? 'woo-ready-pro woo-ready-dash-modal-open-btn' : '' ); ?>">

                                <div data-targetee="<?php echo esc_attr( $switch_js_target ); ?>"
                                    class="quomodo_sm_switch">
                                    <?php if ( isset( $item['demo_link'] ) && $item['demo_link'] != '' ) : ?>
                                    <a target="_blank" href="<?php echo esc_url( $item['demo_link'] ); ?>"
                                        class="woo-ready-data-tooltip"><?php echo esc_html__( 'view demo', 'shopready-elementor-addon' ); ?></a>
                                    <?php endif; ?>
                                    <strong><?php echo esc_html( $item['lavel'] ); ?>
                                        <?php if ( $item['is_pro'] ) : ?>
                                        <span>
                                            <?php echo isset( $item['upcomming'] ) ? esc_html__( 'Upcomming', 'shopready-elementor-addon' ) : esc_html__( 'PRO', 'shopready-elementor-addon' ); ?>
                                        </span>
                                        <?php endif; ?>
                                    </strong>
                                    <input <?php echo esc_attr( $item['is_pro'] ? 'readonly disabled' : '' ); ?>
                                        <?php echo esc_attr( $item['default'] == 1 ? 'checked' : '' ); ?>
                                        name="<?php echo esc_attr( $option_key ); ?>[<?php echo esc_attr( $item_key ); ?>]"
                                        class="quomodo_switch <?php echo esc_attr( $item_key ); ?>"
                                        id="<?php echo esc_attr( $label_identifier ); ?><?php echo esc_attr( $item_key ); ?>"
                                        type="checkbox">
                                    <label
                                        for="<?php echo esc_attr( $label_identifier ); ?><?php echo esc_attr( $item_key ); ?>"></label>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <input type="hidden" name="action" value="<?php echo esc_attr( $action_key ); ?>">
            <?php echo wp_kses_post(wp_nonce_field( $action_key, $nonce_field_val )); ?>
        </div>
    </div> <!-- container end -->
</form>
<!-- Widget swicher form end -->