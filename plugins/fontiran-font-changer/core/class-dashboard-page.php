<?php

/**
 * Class Fontiran_Dashboard_Page.
 */
class Fontiran_Dashboard_Page extends WP_Fontiran_Admin_Page {

	protected function render_inner_content() {
		$this->view( $this->slug . '-page');
	}

}