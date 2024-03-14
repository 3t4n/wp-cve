<?php

// default codes for our plugins
if ( !class_exists( 'BP_Plugin_Core' ) ) {
	class BP_Plugin_Core {
		public function __construct() {
			add_action( 'admin_enqueue_scripts', array( $this, 'codeixer_admin_scripts' ) );
			add_action( 'admin_menu', array( $this, 'bp_admin_menu' ) );
			add_action( 'admin_menu', array( $this, 'later' ), 99 );
		}

		public function codeixer_admin_scripts() {
			wp_enqueue_style( 'ci-admin', BRIGHT_WDEN_ASSETS . '/css/ci-admin.css' );
		}
		public function later() {
			/* === Remove Codeixer Sub-Links === */
			remove_submenu_page( 'brightplugins', 'brightplugins' );
		}

		public function bp_admin_menu() {
			add_menu_page( 'Bright Plugins', 'Bright Plugins', 'manage_options', 'brightplugins', null, BRIGHT_WDEN_ASSETS . '/img/bp-logo-icon.png', 60 );
			// * == License Activation Page ==

			if ( apply_filters( 'brightplugins_pro', false ) ) {
				add_submenu_page( 'brightplugins', 'Dashboard', 'Dashboard', 'manage_options', 'brightplugins-dashboard', array( $this, 'codeixer_license' ) );
			}

			do_action( 'bp_sub_menu' );
		}

		public function codeixer_license() {?>

            <h2>Bright Plugins License Activation</h2>


        <p class="about-description">Enter your Purchase key here, to activate the product, and get full feature updates and premium support.</p>


        <?php
			do_action( 'brightplugins_license_form' );
			do_action( 'brightplugins_license_data' );

		}
	}
	new BP_Plugin_Core();
}
