<?php
Class LBI_Exchange_Rates_Data {
	private $cache_file;
	public function __construct() {
	    $this->env_url = 'https://www.tcmb.gov.tr/kurlar/today.xml';
		$this->cache_file = LBI_PLUGIN_DIR . LBI_PLUGIN_CACHE_DIR . DIRECTORY_SEPARATOR . LBI_PLUGIN_CACHE_FILE;
	}

	public function xml_data() {
	    $the_cache_time = LBI_PLUGIN_CACHE_TIME;
    	if(has_filter('lbi_excache_time')) { $the_cache_time= apply_filters('lbi_excache_time', $the_cache_time); }

		if( file_exists( $this->cache_file ) && ( current_time('timestamp') - filemtime( $this->cache_file ) ) < $the_cache_time ) {
			return simplexml_load_file( $this->cache_file );
		} else {
			return $this->get_xml_data();
		}
	}

	private function get_xml_data() {
		$args = array(
            'body'      => null,
            'timeout'   => 10,
            'sslverify' => false
		);
		$response = wp_remote_get($this->env_url , $args );

		if( wp_remote_retrieve_response_code( $response ) == 200 ) {
		    $response_body = wp_remote_retrieve_body( $response );
			$xml_data = simplexml_load_string($response_body);

			$fp = fopen( $this->cache_file, "w");
			fwrite( $fp, $response_body );
			fclose( $fp );
		} else {
			if ( file_exists( $this->cache_file ) ) {
				$xml_data = simplexml_load_file( $this->cache_dosyasi );
			} else {
				$xml_data = false;
			}
		}
		return $xml_data;
	}
}
?>