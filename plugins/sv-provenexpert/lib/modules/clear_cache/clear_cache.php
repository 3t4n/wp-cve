<?php
namespace sv_provenexpert;

class clear_cache extends widget {
	public function __construct() {

	}
	/**
	 * @desc			initialize actions and filters
	 * @return	void
	 * @author			Matthias Bathke
	 * @since			1.0
	 */
	public function init() {

		if( isset( $_POST['clear_cache'] ) ) {
			delete_transient( 'sv_provenexpert' );

			error_log('SV ProvenExpert - CACHE - cleared successfully.');
		}

		$this->get_root()->add_section( $this )
			->set_section_template_path('lib/backend/tpl/tools.php')
			->set_section_title( 'Tools' )
			->set_section_desc( 'Some helpfull tools, to manage the SV ProvenExpert Plugin.' )
			->set_section_type('tools');;
	}
}