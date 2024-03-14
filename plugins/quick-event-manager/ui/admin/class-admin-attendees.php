<?php
/**
 * @copyright (c) 2020.
 * @author            Alan Fuller (support@fullworks)
 * @licence           GPL V3 https://www.gnu.org/licenses/gpl-3.0.en.html
 * @link                  https://fullworks.net
 *
 * This file is part of  a Fullworks plugin.
 *
 *   This plugin is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 *
 *     This plugin is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 *
 *     You should have received a copy of the GNU General Public License
 *     along with  this plugin.  https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace Quick_Event_Manager\Plugin\UI\Admin;

class Admin_Attendees {

	private $plugin_name;
	private $version;
	/**
	 * @param \Freemius $freemius Object for freemius.
	 */
	private $freemius;

	public function __construct( $plugin_name, $version, $freemius ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		$this->freemius    = $freemius;
	}

	public function hooks() {
		add_action( "admin_menu", array( $this, 'admin_menu' ) );
	}

	public function admin_menu() {

		if ( current_user_can( 'edit_events' ) ) {

			add_menu_page(
				esc_html__( 'Event Attendees', 'quick-event-manager' ),
				esc_html__( 'Event Attendees', 'quick-event-manager' ),
				'edit_events',
				'qem-registration',
				null,
				'dashicons-id-alt'
			);
			add_submenu_page(
				'edit.php?post_type=event',
				esc_html__( 'Registrations', 'quick-event-manager' ),
				esc_html__( 'Registrations', 'quick-event-manager' ),
				'edit_events',
				'qem-registration',
				'qem_messages'
			);
			add_submenu_page(
				'qem-registration',
				esc_html__( 'Registrations', 'quick-event-manager' ),
				esc_html__( 'Registrations', 'quick-event-manager' ),
				'edit_events',
				'qem-registration',
				'qem_messages'
			);
			add_submenu_page(
				'qem-registration',
				esc_html__( 'Premium Reports', 'quick-event-manager' ),
				esc_html__( 'Premium Reports', 'quick-event-manager' ),
				'edit_events',
				'qem-premium-reports',
				array( $this, 'premium_reports' )
			);
			add_submenu_page(
				'edit.php?post_type=event',
				esc_html__( 'Premium Reports', 'quick-event-manager' ),
				esc_html__( 'Premium Reports', 'quick-event-manager' ),
				'edit_posts',
				'qem-premium-reports',
				array( $this, 'premium_reports' )
			);
			add_submenu_page(
				'qem-registration',
				esc_html__( 'Email to Attendees', 'quick-event-manager' ),
				esc_html__( 'Email to Attendees', 'quick-event-manager' ),
				'edit_events',
				'qem-email-attendees',
				array( $this, 'email_attendees' )
			);
		}
	}

	public function premium_reports() {
		$admin_attendees = new Admin_Reports_Dashboard( $this->plugin_name, $this->version, $this->freemius );
		$admin_attendees->run();
	}

	public function email_attendees() {
		$email_attendees = new Admin_Email_Attendees( $this->plugin_name, $this->version, $this->freemius );
		$email_attendees->run();
	}


}
