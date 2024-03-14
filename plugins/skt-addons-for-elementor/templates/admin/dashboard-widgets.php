<?php
/**
 * Dashboard widgets tab template
 */

defined( 'ABSPATH' ) || die();

$widgets = self::get_widgets();
$catwise_widgets = self::get_widget_map_catwise();
$inactive_widgets = \Skt_Addons_Elementor\Elementor\Widgets_Manager::get_inactive_widgets();

$total_widgets_count = count( $widgets );

?>
<div class="skt-dashboard-panel">
    <div class="skt-dashboard-panel__header">
        <div class="skt-dashboard-panel__header-content">
            <p class="f16"><?php printf( esc_html__( 'Here is the list of all %s widgets. You can enable or disable widgets from here to optimize loading speed and Elementor editor experience. %sAfter enabling or disabling any widget make sure to click the Save Changes button.%s', 'skt-addons-elementor' ), $total_widgets_count, '<strong>', '</strong>' ); ?></p>

            <div class="skt-action-list">
                <button type="button" class="skt-action--btn" data-action="enable"><?php esc_html_e( 'Enable All', 'skt-addons-elementor' ); ?></button>
                <button type="button" class="skt-action--btn" data-action="disable"><?php esc_html_e( 'Disable All', 'skt-addons-elementor' ); ?></button>
            </div>
        </div>
    </div>

    <div class="skt-dashboard-widgets">
        <?php
		if( $catwise_widgets ):
			foreach( $catwise_widgets as $cat => $widgets) :
				if( $widgets ):
					printf('<h2 %s>%s %s</h2><br>',
						"style='width: 100%; margin-left: 10px;'",
						ucwords(str_replace('-', ' ', $cat)),
						__( 'Widgets', 'skt-addons-elementor' )
					);
					foreach ( $widgets as $widget_key => $widget_data ) :
						$title = isset( $widget_data['title'] ) ? $widget_data['title'] : '';
						$icon = isset( $widget_data['icon'] ) ? $widget_data['icon'] : '';
						$is_pro = isset( $widget_data['is_pro'] ) && $widget_data['is_pro'] ? true : false;
						$demo_url = isset( $widget_data['demo'] ) && $widget_data['demo'] ? $widget_data['demo'] : '';
						$is_placeholder = $is_pro && ! skt_addons_elementor_has_pro();
						$class_attr = 'skt-dashboard-widgets__item';

						if ( $is_pro ) {
							$class_attr .= ' item--is-pro';
						}

						$checked = '';

						if ( ! in_array( $widget_key, $inactive_widgets ) ) {
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
								<label for="skt-widget-<?php echo esc_attr($widget_key); ?>" <?php echo esc_attr($is_placeholder) ? 'data-tooltip="Get pro"' : ''; ?>><?php echo esc_html($title); ?></label>
								<?php if ( $demo_url ) : ?>
									<a href="<?php echo esc_url( $demo_url ); ?>" target="_blank" rel="noopener" data-tooltip="<?php esc_attr_e( 'Click to view demo', 'skt-addons-elementor' ); ?>" class="skt-dashboard-widgets__item-preview"><i aria-hidden="true" class="eicon-device-desktop"></i></a>
								<?php endif; ?>
							</h3>
							<div class="skt-dashboard-widgets__item-toggle skt-toggle">
								<input id="skt-widget-<?php echo esc_attr($widget_key); ?>" <?php echo esc_attr($checked); ?> type="checkbox" class="skt-toggle__check skt-widget" name="widgets[]" value="<?php echo esc_attr($widget_key); ?>">
								<b class="skt-toggle__switch"></b>
								<b class="skt-toggle__track"></b>
							</div>
						</div>
					<?php
					endforeach;
				endif;
			endforeach;
		endif;
        ?>
    </div>
    <div class="skt-dashboard-panel__footer">
        <button disabled class="skt-dashboard-btn skt-dashboard-btn--save" type="submit"><?php esc_html_e( 'Save Settings', 'skt-addons-elementor' ); ?></button>
    </div>
</div>