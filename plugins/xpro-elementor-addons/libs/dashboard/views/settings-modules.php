<?php
/**
 *Elements Html
 */

use XproElementorAddons\Inc\Xpro_Elementor_Module_List;

$modules_all    = Xpro_Elementor_Module_List::instance()->get_list();
$modules_active = $this->utils->get_option( 'xpro_elementor_module_list', array_keys( $modules_all ) );
$modules_active = ( is_array( $modules_active ) && ! isset( $modules_active[0] ) ? array_keys( $modules_active ) : $modules_active );

?>

<div class="xpro-dashboard-tab-content xpro-dashboard-tab-modules" id="xpro-modules">
	<div class="xpro-dashboard-tab-content-inner">

		<!--Header Area-->
		<div class="xpro-dashboard-tab-content-header">

			<!--Header Title-->
			<h2 class="xpro-dashboard-tab-content-title">
				<?php echo esc_html__( 'Modules', 'xpro-elementor-addons' ); ?>
			</h2>

			<div class="xpro-dashboard-input-switch">
				<input checked="" type="checkbox" value="modules-all" class="xpro-dashboard-module-control-input" name="xpro_elementor_dashboard_module_control_input" id="xpro-dashboard-module-control-input">
				<label class="xpro-dashboard-control-label" for="xpro-dashboard-module-control-input">
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
				<?php
				foreach ( $modules_all as $module => $module_config ) :

					if ( ! did_action( 'xpro_theme_builder_loaded' ) && 'undefined' === $module_config['package'] ) {
						continue;
					}

					?>
					<div class="xpro-col-lg-4">
						<?php
						$this->utils->input(
							array(
								'type'    => 'switch',
								'name'    => 'xpro_elementor_module_list[]',
								'label'   => $module_config['title'],
								'value'   => $module,
								'attr'    => ( 'pro-disabled' !== $module_config['package'] ? array() : array( 'disabled' => 'disabled' ) ),
								'class'   => 'xpro-content-type-' . $module_config['package'],
								'options' => array(
									'checked' => is_array( $modules_active ) && ( in_array( $module, $modules_active, true ) && 'pro-disabled' !== $module_config['package'] ),
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
