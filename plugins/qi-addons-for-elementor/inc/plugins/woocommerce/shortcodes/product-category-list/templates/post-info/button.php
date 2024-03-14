<?php

$button_params = $this_shortcode->generate_button_params( $params, $category_slug );

if ( ! empty( $button_params ) ) { ?>
	<div class="qodef-m-button">
		<?php echo QiAddonsForElementor_Button_Shortcode::call_shortcode( $button_params ); ?>
	</div>
<?php } ?>
