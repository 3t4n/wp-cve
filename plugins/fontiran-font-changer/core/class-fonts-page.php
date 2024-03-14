<?php

/**
 * Class Fontiran_Upload_Page.
 */
class Fontiran_Fonts_Page extends WP_Fontiran_Admin_Page {

	protected function render_inner_content() {
		$this->view( $this->slug . '-page');
	}
	
	
}