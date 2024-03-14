<?php

class AdminBarMenu {
	/**
	 * @var Handler
	 */
	private $handler;

	public function __construct(Handler $handler) {
		$this->handler = $handler;
	}

	public function display() {
		global $wp_admin_bar;
		$wp_admin_bar->add_menu(array(
			'id' => 'wp-lock-admin-bar-notice',
			'title' => "WP Lock is ".(($this->handler->isActive()) ? 'active' : 'inactive'),
			'parent' => 'top-secondary',
			'href' => admin_url().'admin.php?page=wp-lock',
			'meta' => array('class' => (($this->handler->isActive()) ? 'wp-lock-isActive' : 'wp-lock-isInactive'))
		));
	}
}
