<?php
/**
 * Dashboard features tab template
 */

defined( 'ABSPATH' ) || die();

$features = self::get_features();
$inactive_features = \Skt_Addons_Elementor\Elementor\Extensions_Manager::get_inactive_features();
$has_pro = skt_addons_elementor_has_pro();

$total_features_count = count( $features );
?>
<div class="skt-dashboard-panel">
    <div class="skt-dashboard-panel__header">
        <div class="skt-dashboard-panel__header-content">
            <p class="f16"><?php printf( esc_html__( 'Here is the list of all %s features. You can enable or disable features from here to optimize loading speed and Elementor editor experience. %sAfter enabling or disabling any feature make sure to click the Save Changes button.%s', 'skt-addons-elementor' ), $total_features_count, '<strong>', '</strong>' ); ?></p>

            <div class="skt-action-list">
                <button type="button" class="skt-action--btn" data-action="enable_feature"><?php esc_html_e( 'Enable All', 'skt-addons-elementor' ); ?></button>
                <button type="button" class="skt-action--btn" data-action="disable_feature"><?php esc_html_e( 'Disable All', 'skt-addons-elementor' ); ?></button>
            </div>
        </div>
    </div>

    <div class="skt-dashboard-widgets">
        <?php
        foreach ( $features as $feature_key => $feature_data ) :
            $title = isset( $feature_data['title'] ) ? $feature_data['title'] : '';
            $icon = isset( $feature_data['icon'] ) ? $feature_data['icon'] : '';
            $is_pro = isset( $feature_data['is_pro'] ) && $feature_data['is_pro'] ? true : false;
            $demo_url = isset( $feature_data['demo'] ) && $feature_data['demo'] ? $feature_data['demo'] : '';
            $is_placeholder = $is_pro && ! skt_addons_elementor_has_pro();
            $class_attr = 'skt-dashboard-widgets__item';

            if ( $is_pro ) {
                $class_attr .= ' item--is-pro';
            }

            $checked = '';

            if ( ! in_array( $feature_key, $inactive_features ) ) {
                $checked = 'checked="checked"';
            }

            if ( $is_placeholder ) {
                $class_attr .= ' item--is-placeholder';
                $checked = 'disabled="disabled"';
            }
            ?>
            <div class="<?php echo esc_attr($class_attr); ?>">
                <?php if ( $is_pro ) : ?>
                    <span class="skt-dashboard-widgets__item-badge"><?php esc_html_e( 'Pro', 'skt-addons-elementor' ); ?></span>
                <?php endif; ?>
                <span class="skt-dashboard-widgets__item-icon"><i class="<?php echo esc_attr($icon); ?>"></i></span>
                <h3 class="skt-dashboard-widgets__item-title">
                    <label for="skt-widget-<?php echo esc_attr($feature_key); ?>" <?php echo esc_attr($is_placeholder) ? 'data-tooltip="Get pro"' : ''; ?>><?php echo esc_html($title); ?></label>
                    <?php if ( $demo_url ) : ?>
                        <a href="<?php echo esc_url( $demo_url ); ?>" target="_blank" rel="noopener" data-tooltip="<?php esc_attr_e( 'Click to view demo', 'skt-addons-elementor' ); ?>" class="skt-dashboard-widgets__item-preview"><i aria-hidden="true" class="eicon-device-desktop"></i></a>
                    <?php endif; ?>
                </h3>
                <div class="skt-dashboard-widgets__item-toggle skt-toggle">
                    <input id="skt-widget-<?php echo esc_attr($feature_key); ?>" <?php echo esc_attr($checked); ?> type="checkbox" class="skt-toggle__check skt-feature" name="features[]" value="<?php echo esc_attr($feature_key); ?>">
                    <b class="skt-toggle__switch"></b>
                    <b class="skt-toggle__track"></b>
                </div>
            </div>
        <?php
        endforeach;
        ?>
    </div>
    <div class="skt-dashboard-panel__footer">
        <button disabled class="skt-dashboard-btn skt-dashboard-btn--save" type="submit"><?php esc_html_e( 'Save Settings', 'skt-addons-elementor' ); ?></button>
    </div>
</div>