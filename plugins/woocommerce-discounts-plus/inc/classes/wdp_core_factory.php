<?php



	if ( !class_exists( 'wdp_core_factory' ) ) {
		abstract class wdp_core_factory
		{
			function __construct()
			{
			}
			abstract function gj_logic();
		}
	}