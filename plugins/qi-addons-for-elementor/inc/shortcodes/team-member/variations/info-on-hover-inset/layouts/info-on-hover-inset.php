<div <?php qi_addons_for_elementor_framework_class_attribute( $holder_classes ); ?>>
	<div class="qodef-m-inner">
		<div class="qodef-m-image">
			<?php qi_addons_for_elementor_template_part( 'shortcodes/team-member', 'templates/post-info/image', '', $params ); ?>
		</div>
		<div class="qodef-m-content">
			<?php qi_addons_for_elementor_template_part( 'shortcodes/team-member', 'templates/post-info/title', '', $params ); ?>
			<?php qi_addons_for_elementor_template_part( 'shortcodes/team-member', 'templates/post-info/role', '', $params ); ?>
			<?php
			qi_addons_for_elementor_template_part(
				'shortcodes/team-member',
				'templates/post-info/text',
				'',
				array_merge(
					$params,
					array(
						'text' => $info_on_hover_inset_text,
					)
				)
			);
			?>
			<?php qi_addons_for_elementor_template_part( 'shortcodes/team-member', 'templates/post-info/social-icons', '', $params ); ?>
		</div>
	</div>
</div>
