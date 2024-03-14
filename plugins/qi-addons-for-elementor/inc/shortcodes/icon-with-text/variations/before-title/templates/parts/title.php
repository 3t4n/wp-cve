<?php if ( ! empty( $title ) ) { ?>
	<<?php echo qi_addons_for_elementor_framework_sanitize_tags( $title_tag ); ?> class="qodef-m-title">
		<?php if ( ! empty( $link['url'] ) ) : ?>
			<a itemprop="url" href="<?php echo esc_url( $link['url'] ); ?>" <?php echo qi_addons_for_elementor_framework_get_inline_attrs( qi_addons_for_elementor_get_link_attributes( $link ) ); ?>>
		<?php endif; ?>
			<span class="qodef-m-title-inner">
				<?php if ( isset( $icon_type ) && ! empty( $icon_type['value'] ) ) { ?>
					<span class="qodef-m-icon-wrapper">
						<?php qi_addons_for_elementor_template_part( 'shortcodes/icon-with-text/variations/before-title', 'templates/parts/icon', '', $params ); ?>
					</span>
				<?php } ?>
				<span class="qodef-m-title-text">
					<?php echo esc_html( $title ); ?>
				</span>
			</span>
		<?php if ( ! empty( $link['url'] ) ) : ?>
			</a>
		<?php endif; ?>
	</<?php echo qi_addons_for_elementor_framework_sanitize_tags( $title_tag ); ?>>
<?php } ?>
