<?php
/**
 *Elements Html
 */

use XproElementorAddons\Inc\Xpro_Elementor_Widget_List;

$widgets_all    = Xpro_Elementor_Widget_List::instance()->get_list();
$widgets_active = $this->utils->get_option( 'xpro_elementor_widget_list', array_keys( $widgets_all ) );
$widgets_active = ( is_array( $widgets_active ) && ! isset( $widgets_active[0] ) && is_array( $widgets_active[0] ) ? array_keys( $widgets_active ) : $widgets_active );

?>

<div class="xpro-dashboard-tab-content xpro-dashboard-tab-widgets" id="xpro-widgets">
	<div class="xpro-dashboard-tab-content-inner">

		<!--Header Area-->
		<div class="xpro-dashboard-tab-content-header">

			<!--Header Title-->
			<h2 class="xpro-dashboard-tab-content-title">
				<?php echo esc_html__( 'Widgets', 'xpro-elementor-addons' ); ?>
			</h2>

			<div class="xpro-dashboard-input-switch">
				<input checked="" type="checkbox" value="widgets-all" class="xpro-dashboard-widget-control-input" name="xpro_elementor_dashboard_widget_control_input" id="xpro-dashboard-widget-control-input">
				<label class="xpro-dashboard-control-label" for="xpro-dashboard-widget-control-input">
					<?php echo esc_html__( 'Disable All', 'xpro-elementor-addons' ); ?>
					<span class="xpro-dashboard-control-label-switch" data-active="ON" data-inactive="OFF"></span>
					<?php echo esc_html__( 'Enable All', 'xpro-elementor-addons' ); ?>
				</label>
			</div>

			<!--Button Save Changes-->
			<button class="xpro-dashboard-save-button">
				<i class="dashicons dashicons-update"></i>
				<?php echo esc_html__( 'Save Changes', 'xpro-elementor-addons' ); ?>
			</button>

		</div>

		<div class="xpro-dashboard-item-wrapper">
			<div class="xpro-row">
				<?php foreach ( $widgets_all as $widget => $widget_config ) : ?>
					<div class="xpro-col-lg-4">
						<?php
						$this->utils->input(
							array(
								'type'    => 'switch',
								'name'    => 'xpro_elementor_widget_list[]',
								'label'   => $widget_config['title'],
								'value'   => $widget,
								'attr'    => ( 'pro-disabled' !== $widget_config['package'] ? array() : array( 'disabled' => 'disabled' ) ),
								'class'   => 'xpro-content-type-' . $widget_config['package'],
								'options' => array(
									'checked' => is_array( $widgets_active ) && ( in_array( $widget, $widgets_active, true ) && 'pro-disabled' !== $widget_config['package'] ),
								),
							)
						);
						?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>

	</div>
</div>
