<div class="qodef-admin-widgets-page">
	<form class="qodef-widgets-list" id="qi_addons_for_elementor_widgets_framework_ajax_form" data-options-name="qi_addons_for_elementor_widgets">
		<div class="qodef-admin-widget-header">
			<div class="qodef-widgets-header-left">
				<div class="qodef-widgets-header-left-inner">
					<?php qi_addons_for_elementor_framework_template_part( QI_ADDONS_FOR_ELEMENTOR_ADMIN_PATH . '/inc', 'admin-pages', 'sub-pages/widgets/templates/parts/search' ); ?>
				</div>
			</div>
			<div class="qodef-widgets-header-right">
				<div class="qodef-widgets-header-right-inner">
					<?php qi_addons_for_elementor_framework_template_part( QI_ADDONS_FOR_ELEMENTOR_ADMIN_PATH . '/inc', 'admin-pages', 'sub-pages/widgets/templates/parts/save' ); ?>
				</div>
			</div>
		</div>
		<?php foreach ( $shortcodes as $shortcode_subcategory => $subcat_shortcodes ) : ?>
			<div class="qodef-widgets-section">
				<?php
				qi_addons_for_elementor_framework_template_part(
					QI_ADDONS_FOR_ELEMENTOR_ADMIN_PATH . '/inc',
					'admin-pages',
					'sub-pages/widgets/templates/parts/section-title',
					'',
					array(
						'shortcode_subcategory' => $shortcode_subcategory,
						'enabled_subcategory'   => $enabled_subcategory,
					)
				);
				?>
				<div class="qodef-widget-grid">
					<div class="qodef-widget-grid-inner">
						<?php
						foreach ( $subcat_shortcodes as $shortcode_key => $shortcode ) :
							?>
							<?php
							qi_addons_for_elementor_framework_template_part(
								QI_ADDONS_FOR_ELEMENTOR_ADMIN_PATH . '/inc',
								'admin-pages',
								'sub-pages/widgets/templates/item',
								'',
								array(
									'disabled'      => $disabled,
									'shortcode_key' => $shortcode_key,
									'shortcode'     => $shortcode,
								)
							);
							?>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
		<?php qi_addons_for_elementor_framework_template_part( QI_ADDONS_FOR_ELEMENTOR_ADMIN_PATH . '/inc', 'admin-pages', 'sub-pages/widgets/templates/parts/no-woocommerce' ); ?>
	</form>
</div>
