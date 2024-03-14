<?php

if ( ! class_exists( 'WP_Customize_Control' ) ) {
	return;
}

class ThemeRain_Customize_Fonts_Control extends WP_Customize_Control {

	public function render_content() {
	?>
		<?php if ( ! empty( $this->label ) ) : ?>
			<label for="<?php echo esc_attr( $this->id ); ?>" class="customize-control-title"><?php echo esc_html( $this->label ); ?></label>
		<?php endif; ?>

		<select id="<?php echo esc_attr( $this->id ); ?>" <?php $this->link(); ?>>
			<?php
			foreach ( $this->choices as $label => $array ) {
				echo '<optgroup label="' . esc_attr( $label ) . '">';
					foreach ( $array as $value => $label ) {
						echo '<option value="' . esc_attr( $value ) . '"' . selected( $this->value(), $value, false ) . '>' . $label . '</option>';
					}
				echo '</optgroup>';
			}
			?>
		</select>
	<?php
	}
}
