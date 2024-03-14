<?php
	namespace sv_provenexpert;

	class common_settings extends modules {
		public function __construct() {

		}
		/**
		 * @desc			initialize actions and filters
		 * @return	void
		 * @author			Matthias Bathke
		 * @since			1.0
		 */
		public function init() {
			$this->set_section_title( __('API Settings', $this->get_root()->get_prefix()) );
			$this->set_section_desc( sprintf(__('Fill out the API settings and add the widget or shortcode %s to your site.', $this->get_root()->get_prefix()), '<strong>[sv_provenexpert]</strong>') );
			$this->set_section_type('settings');
			$this->get_root()->add_section( $this );

			$this->load_settings();
		}

		public function load_settings(): common_settings {
			$this->get_setting('api_id')
				->set_title( __( 'API ID', $this->get_root()->get_prefix() ) )
				->set_description( __( 'See API Username on', $this->get_root()->get_prefix() ) . ' <a href="https://www.provenexpert.com/de/personalisierte-umfragelinks/" target="_blank">ProvenExpert.com</a>' )
				->set_maxlength( 32 )
				->set_minlength( 32 )
				->set_required( true )
				->load_type( 'text' );

			$this->get_setting('api_key')
				->set_title( __( 'API Key', $this->get_root()->get_prefix() ) )
				->set_description( __( 'See API Key on', $this->get_root()->get_prefix() ) . ' <a href="https://www.provenexpert.com/de/personalisierte-umfragelinks/" target="_blank">ProvenExpert.com</a>' )
				->set_maxlength( 43 )
				->set_minlength( 43 )
				->set_required( true )
				->load_type( 'text' );

			return $this;
		}
	}