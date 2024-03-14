<?php

$features_all    = Xpro_Beaver_Features_List::instance()->get_list();
$features_active = Xpro_Beaver_Dashboard_Utils::instance()->get_option( 'xpro_beaver_features_list', array_keys( $features_all ) );
$features_active = ( ! isset( $features_active[0] ) ? array_keys( $features_active ) : $features_active );

?>

<div class="xpro-bb-tab-content" id="bb-features">
	<div class="xpro-row">
		<div class="xpro-col-lg-9">
			<div class="xpro-bb-tab-content-wrapper xpro-bb-dashboard-tab-features-content">
				<div class="xpro-bb-dashboard-tab-content-inner">
					<div class="xpro-bb-dashboard-intro">
						<div class="xpro-bb-dashboard-intro-content">
							<h2 class="xpro-bb-dashboard-title">widgets</h2>
							<div class="xpro-bb-dashboard-input-switch">
								<input checked type="checkbox" value="feature-all" class="xpro-bb-dashboard-widget-control-input" name="xpro_bb_dashboard_module_control_input" id="xpro-dashboard-feature-control-input">
								<label class="xpro-bb-dashboard-control-label" for="xpro-dashboard-feature-control-input">
									<?php echo esc_html__( 'Disable All', 'xpro-bb-addons' ); ?>
									<span class="xpro-bb-dashboard-control-label-switch" data-active="ON" data-inactive="OFF"></span>
									<?php echo esc_html__( 'Enable All', 'xpro-bb-addons' ); ?>
								</label>
							</div>
							<button class="xpro-bb-dashboard-btn xpro-dashboard-save-button">
								<i class="dashicons dashicons-update"></i>
								<?php echo esc_html__( 'Save Changes', 'xpro-bb-addons' ); ?>
							</button>
						</div>

					</div>
					<div class="xpro-row">
						<?php foreach ( $features_all as $feature => $feature_config ) : ?>
							<div class="xpro-col-lg-4">
								<div class="xpro-bb-dashboard-widget-item xpro-bb-dashboard-input-switch xpro-bb-content-type-<?php echo esc_attr( $feature_config['package'] ); ?>">
									<input type="checkbox" <?php echo esc_attr( ( ( in_array( $feature, $features_active, true ) ) ? 'checked=checked' : '' ) ); ?> value="<?php echo esc_attr( $feature ); ?>" class="xpro-bb-dashboard-widget-control-input" name="xpro_beaver_features_list[]" id="xpro-dashboard-modules-switch-<?php echo esc_attr( $feature_config['slug'] ); ?>"<?php echo $feature_config['package'] === 'pro-disabled' ? ' disabled' : ''; ?>>
									<label class="xpro-bb-dashboard-control-label" for="xpro-dashboard-modules-switch-<?php echo esc_attr( $feature_config['slug'] ); ?>">
										<?php echo esc_attr( $feature_config['title'] ); ?>
										<span class="xpro-bb-dashboard-control-label-switch" data-active="ON" data-inactive="OFF"></span>
									</label>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
		<div class="xpro-col-lg-3">
			<div class="xpro-bb-dashboard-sidebar-wrapper">
				<div class="xpro-bb-dashboard-sidebar xpro-sidebar">
					<div class="xpro-bb-dashboard-widget-count-wrapper">
						<h2 class="xpro-bb-dashboard-title xpro-bb-dashboard-widget-count-title">Perfect <span class="xpro-pink">Toolkit</span> for Beaver Builder</h2>
						<p class="xpro-bb-dashboard-txt">
							Step up your design game with premium plugins & templates your competitors wish they had.
						</p>
						<ul class="xpro-bb-dashboard-widget-count-list">
							<li>
								<span class="xpro-bb-dashboard-widget-count">300+</span>
								<span class="xpro-bb-dashboard-widget-count-text">Templates</span>
							</li>
							<li>
								<span class="xpro-bb-dashboard-widget-count">50+</span>
								<span class="xpro-bb-dashboard-widget-count-text">Modules</span>
							</li>

							<li>
								<span class="xpro-bb-dashboard-widget-count">100+</span>
								<span class="xpro-bb-dashboard-widget-count-text">Full Themes</span>
							</li>
							<li>
								<span class="xpro-bb-dashboard-widget-count">500+</span>
								<span class="xpro-bb-dashboard-widget-count-text">Sections</span>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
