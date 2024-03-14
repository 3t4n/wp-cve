<?php

if ( !class_exists( 'APSC_Model_Search' ) ) :

final class APSC_Model_Search extends APSC_Model_Archive
{

	public function __construct()
	{
		
		global $APSC;
		
		$this->record = $APSC->main_slug . '_search';
		
		$this->initial_data = array(
			'default' => $this->get_model_fields(),
		);
		
		$this->setup_default_data();
		
		parent::__construct();

	}
	
}

endif;
