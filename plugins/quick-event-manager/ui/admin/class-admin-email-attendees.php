<?php

namespace Quick_Event_Manager\Plugin\UI\Admin;

use Quick_Event_Manager\Plugin\Control\Admin_Template_Loader;

class Admin_Email_Attendees {

	public $admin_page_title;
	protected $plugin_name;
	protected $version;
	/**
	 * @param \Freemius $freemius Object for freemius.
	 */
	private $freemius;

	public function __construct( $plugin_name, $version, $freemius ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		$this->freemius    = $freemius;
	}

	public function run() {
		$this->admin_page_title = esc_html__( 'QEM Email Attendees', 'quick-event-manager' );
		$this->admin_page();
	}

	public function admin_page() {
		$template_loader = new Admin_Template_Loader();

		$template_loader->set_template_data(
			array(
				'settings_title'  => $this->admin_page_title,
				'template_loader' => $template_loader,
				'freemius'        => $this->freemius,
			)
		);
		$template_loader->get_template_part( 'email_attendees' );

	}
}
