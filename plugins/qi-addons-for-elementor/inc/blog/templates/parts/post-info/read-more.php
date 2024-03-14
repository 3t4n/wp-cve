<?php if ( ! post_password_required() ) { ?>
	<div class="qodef-e-read-more">
		<?php
		echo QiAddonsForElementor_Button_Shortcode::call_shortcode(
			array_merge(
				$button_params,
				array(
					'button_link' => array(
						'url'               => get_the_permalink(),
						'is_external'       => '',
						'nofollow'          => '',
						'custom_attributes' => '',
					),
				)
			)
		);
		?>
	</div>
<?php } ?>
