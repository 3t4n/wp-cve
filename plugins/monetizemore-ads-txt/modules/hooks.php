<?php 
add_action( 'init', 'wat_init' );
function wat_init( $buttons ) {
    $config = get_option('wat_editor'); 
	if( $_SERVER["REQUEST_URI"] == '/ads.txt' || $_SERVER["REQUEST_URI"] == 'ads.txt'  ){
		
		if( is_array($config['domain'])&& count( $config['domain'] ) > 0 ){
			for( $i=1; $i < count($config['domain'] ); $i++ ){
				//vaR_dump( $config['domain'][$i] );
				
				$curent_line = array( $config['domain'][$i], $config['account_id'][$i], $config['type'][$i], $config['autority'][$i] );
				$curent_line = array_filter($curent_line);
				$out_lines[] = implode(',', $curent_line );
			}
		}
		header('Content-Type: text/plain; charset=utf-8');
		header("HTTP/1.1 200 Ok");
		echo implode("\n", $out_lines);
		die();
	}
	
}
?>