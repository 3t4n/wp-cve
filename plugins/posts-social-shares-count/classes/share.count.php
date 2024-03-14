<?php
/**
 * Get Social Shares class
 *
 * @author  Bishoy A. <hi@bishoy.me>
 */

class PsscShareCount {
	/**
	 * URL to check it's shares
	 * @var string
	 */
	private $url;

	/**
	 * Timeout (Maximum time for CURL request)
	 * @var integer
	 */
	private $timeout;

	/**
	 * The constructor
	 * @param string  $url
	 * @param integer $timeout
	 */
	public function __construct( $url, $timeout = 10 ) {
		$this->url     = rawurlencode( $url );
		$this->timeout = $timeout;
	}

	/**
	 * @deprecated 1.4.1
	 * Get Twitter Tweets
	 * @return integer Tweets count
	 */
	public function pssc_twitter() { 
		return;
	}

	/**
	 * Get Linked In Shares
	 * @return integer
	 */
	public function pssc_linkedin() { 
		$json_string = $this->file_get_contents_curl( "http://www.linkedin.com/countserv/count/share?url=$this->url&format=json" );
		$json = json_decode( $json_string, true );
		return isset( $json['count'] ) ? intval( $json['count'] ) : 0;
	}

	/**
	 * Get Facebook Shares
	 * @return integer
	 */
	public function pssc_facebook() {
		$json_string = $this->file_get_contents_curl( 'http://api.facebook.com/restserver.php?method=links.getStats&format=json&urls='.$this->url );
		$json = json_decode( $json_string, true );
		return isset( $json[0]['share_count'] ) ? intval( $json[0]['share_count'] ) : 0;
	}

	/**
	 * Get Goolge+ ones
	 * @return integer
	 */
	public function pssc_gplus() {
		$json_string = $this->file_get_contents_curl( 'https://clients6.google.com/rpc', '[{"method":"pos.plusones.get","id":"p","params":{"nolog":true,"id":"'.rawurldecode( $this->url ).'","source":"widget","userId":"@viewer","groupId":"@self"},"jsonrpc":"2.0","key":"p","apiVersion":"v1"}]', array( 'Content-type: application/json' ) );
		$json = json_decode( $json_string, true );
		return isset( $json[0]['result']['metadata']['globalCounts']['count'] ) ? intval( $json[0]['result']['metadata']['globalCounts']['count'] ) : 0;
	}

	/**
	 * Get Stumble Views
	 * @return integer
	 */
	public function pssc_stumble() {
		$json_string = $this->file_get_contents_curl( 'http://www.stumbleupon.com/services/1.01/badge.getinfo?url='.$this->url );
		$json = json_decode( $json_string, true );
		return isset( $json['result']['views'] ) ? intval( $json['result']['views'] ) : 0;
	}

	/**
	 * Get Delicious shares
	 * @return integer
	 */
	public function pssc_delicious() {
		$json_string = $this->file_get_contents_curl( 'http://feeds.delicious.com/v2/json/urlinfo/data?url='.$this->url );
		$json = json_decode( $json_string, true );
		return isset( $json[0]['total_posts'] ) ? intval( $json[0]['total_posts'] ) : 0;
	}

	/**
	 * Get pinterest Pins
	 * @return integer
	 */
	public function pssc_pinterest() {
		$return_data = $this->file_get_contents_curl( 'http://api.pinterest.com/v1/urls/count.json?url='.$this->url );
		
		if ( ! is_wp_error( $return_data ) ) {
			$json_string = preg_replace( "/[^(]*\((.*)\)/", "$1", $return_data );
			$json = json_decode( $json_string, true );
		}

		return isset( $json['count'] ) ? intval( $json['count'] ) : 0;
	}

	/**
	 * File Get Content by Curl
	 * @param  string $url
	 * @return mixed
	 */
	private function file_get_contents_curl( $url, $post_fields = '', $http_header = array() ) {
		
		// support location redirects to future-proof script
		// Thanks to Ryan https://wordpress.org/support/topic/fix-curlopt_followlocation-error-with-safe_mode-and-open_dir?replies=2#post-7575577

		$max_redirs = (ini_get('open_basedir') == '' && ini_get('safe_mode' == 'Off')) ? 2 : 0;

		$ch = curl_init();

		$opt_arr = array(
			CURLOPT_URL => $url,
			CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],
			CURLOPT_FAILONERROR => 1,
			CURLOPT_FOLLOWLOCATION => $max_redirs > 0,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_TIMEOUT => $this->timeout,			
		);

		if ( ! empty( $post_fields ) )
			$opt_arr[CURLOPT_POSTFIELDS] = $post_fields;

		if ( ! empty( $http_header ) )
			$opt_arr[CURLOPT_HTTPHEADER] = $http_header;

		curl_setopt_array( $ch, $opt_arr );

		$cont = curl_exec( $ch );

		if ( curl_error( $ch ) ) {
			return new WP_Error( 'pssc_curl_error', curl_error( $ch ) );
		}

		return $cont;
	}

	/**
	 * Get all counts
	 * @return integer total count
	 */
	public function pssc_all() {
		$count = 0;

		$fb = $this->pssc_facebook();
		$li = $this->pssc_linkedin();
		$gp = $this->pssc_gplus();
		$dl = $this->pssc_delicious();
		$st = $this->pssc_stumble();
		$pi = $this->pssc_pinterest();

		$count = $fb + $li + $gp + $dl + $st + $pi;

		return $count;
	}
}