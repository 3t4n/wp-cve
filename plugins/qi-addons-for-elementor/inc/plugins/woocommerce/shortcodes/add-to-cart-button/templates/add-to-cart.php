<?php

if ( ! empty( $button_params ) ) { ?>
	<div <?php qi_addons_for_elementor_framework_class_attribute( $holder_classes ); ?>>
		<?php echo QiAddonsForElementor_Button_Shortcode::call_shortcode( $button_params ); ?>
	</div>
	<?php
}
