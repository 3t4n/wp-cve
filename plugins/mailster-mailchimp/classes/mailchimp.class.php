<?php

class MailsterMailchimp {



	private $plugin_path;
	private $plugin_url;

	/**
	 *
	 */
	public function __construct() {

		$this->plugin_path = plugin_dir_path( MAILSTER_MAILCHIMP_FILE );
		$this->plugin_url  = plugin_dir_url( MAILSTER_MAILCHIMP_FILE );

		load_plugin_textdomain( 'mailster-mailchimp' );

		add_filter( 'mailster_importer', array( &$this, 'register_importer' ) );

	}



	public function register_importer( $importer ) {

		$importer['MailsterImportMailchimp'] = $this->plugin_path . 'classes/manage.import.mailchimp.php';

		return $importer;
	}


}
