<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$string = '';
$locale = get_locale();
$settings = wcpt_get_settings_data();

if( ! empty( $settings['no_results'] ) ){
	$translations = array();
	$translation = false;

	foreach( preg_split ('/$\R?^/m', trim( $settings['no_results']['label'] )) as $translation_rule ){
		$array = explode( ':', $translation_rule );      
		
		// rule with locale code: translation
		if( ! empty( $array[1] ) ){
			$translations[ trim( $array[0] ) ] = stripslashes( trim( $array[1] ) );
		
		// rule with just default translation
		}else{
			$translations[ 'default' ] = stripslashes( trim( $array[0] ) );
		}
	}

	// maybe use defaults
	if( empty( $translations[ $locale ] ) ){

		if( ! empty( $translations[ 'default' ] ) ){
			$translation = $translations[ 'default' ];			
		}else if( ! empty( $translations[ 'en_US' ] ) ){
			$translation = $translations[ 'en_US' ];			
		}

	}else{
		$translation = $translations[$locale];

	}
		
	if( ! $translation ){
		$string = 'No results found. [link]Clear filters[/link] and try again?';
	}else{
		$string = $translation; 
	}
}

// no results message via table shortcode
$table_data = wcpt_get_table_data();
$sc_attrs = $table_data['query']['sc_attrs'];

if( 
	! empty( $sc_attrs['no_results_message'] ) ||
	! empty( $sc_attrs['no_results_message_' . strtolower( $locale )] )
){

	if( ! empty( $sc_attrs['no_results_message'] ) ){
		$string = $sc_attrs['no_results_message'];
	}

	if( ! empty( $sc_attrs['no_results_message_' . strtolower( $locale )] ) ){
		$string = $sc_attrs['no_results_message_' . strtolower( $locale )];
	}

	if( $string == "*empty*" ){
		$string = "";
	}	

	if( substr( $string, 0, 8 ) === 'page_id:' ){
		$page_id = substr( $string, 8 );
		if( is_numeric( $page_id ) ){
			$content = get_the_content( false, false, $page_id );
			$content = apply_filters( 'the_content', $content );
			$content = str_replace( ']]>', ']]&gt;', $content );
			$string = $content;
		}
	}else if( substr( $string, 0, 7 ) === 'option:' ){
		$option = substr( $string, 7 );
		$string = get_option( $option );
	}
}

?>
<div class="wcpt-no-results  wcpt-device-<?php echo $device; ?>" data-wcpt-device="<?php echo $device; ?>">
	<?php echo str_replace(array( '[link]', '[/link]' ), array( '<a href="." class="wcpt-clear-filters">', '</a>' ), $string); ?>
</div>
