<?php

if ( !class_exists( 'APSC_Model_Date' ) ) :

final class APSC_Model_Date extends APSC_Model_Archive
{

	public function __construct()
	{
		
		global $APSC;
		
		$this->record = $APSC->main_slug . '_date';
		
		$this->initial_data = array(
			'default' => $this->get_model_fields(),
			'yearly' => $this->get_model_fields(),
			'monthly' => $this->get_model_fields(),
			'daily' => $this->get_model_fields(),
		);
		
		$this->setup_default_data();
		
		parent::__construct();

	}
	
}

endif;
