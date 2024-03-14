<?php

	function KimsGetDefaultOptions() {
	
	    $arDefaultOptions = array(
	        'api_key' => ''
	    );
	
	    return $arDefaultOptions;
	}
	
	function KimsGetOptions() {
	
	    $options = get_option( KIMS_OPTION_NAME );
	
	    if ( !$options ) {
	        update_option( KIMS_OPTION_NAME, KimsGetDefaultOptions() );
	        $settings = KimsGetDefaultOptions();
	    }
	
	    return $options;
	}
	
	function KimsGetDefaultOption( $index ) {
	
	    global $g_arKimsDefaultOptions;
	    return $g_arKimsDefaultOptions[ $index ];
	}
	
	function KimsSetDefaultOptions() {
	
	    $options = get_option( KIMS_OPTION_NAME );
	
	    if ( !$options ) {
	        update_option( KIMS_OPTION_NAME, KimsGetDefaultOptions() );
	    }
	}

