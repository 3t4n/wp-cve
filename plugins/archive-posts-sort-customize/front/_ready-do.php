<?php

if ( !class_exists( 'APSC_Front_Ready_Do' ) ) :

final class APSC_Front_Ready_Do
{

	public function __construct() {}

	public function is_ready_do()
	{
		
		global $APSC;
		
		return true;

	}
	
}

endif;
