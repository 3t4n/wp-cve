<?php

final class Xlwcty_Dynamic_Component {
	public $page_id = 0;
	public $current_index = 1;
	public $slug = '';

	public function __construct( $slug, $page_id ) {
		$this->slug    = $slug;
		$this->page_id = $page_id;
		add_shortcode( $this->slug, array( $this, 'xlwcty_multiple_comp_html' ) );
	}

	public function xlwcty_multiple_comp_html() {
		$multiple_comp = XLWCTY_Component::get_multiple_components( $this->page_id );
		if ( empty( $multiple_comp ) ) {
			return '';
		}
		if ( ! isset( $multiple_comp[ $this->slug ] ) ) {
			return;
		}

		$component_class = XLWCTY_Components::get_components( $multiple_comp[ $this->slug ]['component'] );

		$component_class->current_index = $this->current_index;
		if ( $component_class->is_enable( $component_class->current_index ) ) {
			ob_start();
			echo '<div class="xlwcty_wrap xlwcty_shortcode" data-component="' . $multiple_comp[ $this->slug ]['slug'] . '">';
			echo $component_class->get_view();
			echo '</div>';

			return ob_get_clean();

		}
	}
}