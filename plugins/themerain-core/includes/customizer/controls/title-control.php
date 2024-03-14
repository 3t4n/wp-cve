<?php
if ( ! class_exists( 'WP_Customize_Control' ) ) {
	return;
}

class TRC_Customize_Title_Control extends WP_Customize_Control {
	public function render_content() {
		if ( ! empty( $this->label ) ) :
			echo '<h4 class="customize-control-title">' . esc_html( $this->label ) . '</h4>';
		endif;

		if ( ! empty( $this->description ) ) :
			echo '<p class="customize-control-description">' . esc_html( $this->description ) . '</p>';
		endif;
	}
}
