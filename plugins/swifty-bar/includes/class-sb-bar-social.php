<?php

/**
 * Social sharing class.
 *
 * @package    sb_bar
 * @subpackage sb_bar/public
 * @author     Danijel Predojevic <predojevic.danijel@gmail.com>
 */
class sb_bar_Social {

	public $post_id; 

	protected $current_url;

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.6
	 * @access   private
	 * @var      string    $sb_bar    The ID of this plugin.
	 */
	private $sb_bar;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.6
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Plugin options
	 *
	 * @since    1.0.6
	 * @access   private
	 * @var      array    $options    Current plugin options
	 */
	private $options;

	/**
	 * The name of the transient settings
	 *
	 * @since    1.0.6
	 * @access   private
	 * @var      string    $transient_name    The name of the transient settings
	 */
	private $transient_name;


	function __construct($post_id) {
		
		$this->current_url = get_permalink($post_id);

		$this->transient_name = 'sb_bar_' . $post_id . '_shares';
		$this->post_id = $post_id;

	}


	public function get_shares_all() {
		$activeNetworks = array();
		$options = (get_option('sb_bar_enable_options') ? array_keys (get_option('sb_bar_enable_options')) : array());
		$networks = array('facebook', 'linkedin', 'pinterest', 'googleplus'); // 'twitter' removed - https://blog.twitter.com/2015/hard-decisions-for-a-sustainable-platform
		
		//Filter disabled networks
		foreach ($networks as $network) {
			$net = array_search('disable-'.$network, $options);
			if (!$net) $activeNetworks[] = $network;
		}

		$shares = $this->get_post_transient($this->transient_name);

		if(empty($shares)) {
			
			foreach($activeNetworks as $network) {
				$shares[$network] = $this->{'get_shares_'.$network}($this->post_id);
				$shares[$network] = $this->share_count_pretty($shares[$network]);
			}
			$this->set_post_transient($shares);
		}
		return $shares;

	}

	public function get_shares_twitter($post_id) {

		$twitter = wp_remote_get('http://cdn.api.twitter.com/1/urls/count.json?url=' . $this->current_url);
		if($twitter['response']['code'] === 200) {
			
			$twitter = json_decode($twitter['body'], true);
			$twitter = $twitter['count'] == NULL ? 0 : $twitter['count'];
		}
		else $twitter = 0;
		
		return $twitter;
	}

	public function get_shares_facebook($post_id) {
		
		$response = wp_remote_get('http://graph.facebook.com/?id=' . $this->current_url,array('timeout'=>20));
		if( !is_wp_error( $response ) ) {
			$json = json_decode( wp_remote_retrieve_body( $response ) );
			return isset( $json->shares ) ? $json->shares : 0;
			// Notice: Undefined index: shares in /home/itsgoran/public_html/wp/wp-content/plugins/swifty-bar/includes/class-sb-bar-social.php on line 106
		} else {
			return 0;
		}

	}

	public function get_shares_linkedin($post_id) {
		
		//LinkedIn  doesn't return clean JSON so we use regex
		$linkedin = wp_remote_get('http://www.linkedin.com/countserv/count/share?format=json&url=' . $this->current_url);
		if (is_wp_error($linkedin)) {
			return 0;
		} else {
			$json = json_decode($linkedin['body'], true);
			if (isset($json['count'])) {
				return $json['count'];
			} else {
				return '0';
			}
		}

	}

	public function get_shares_pinterest($post_id) { 

		//Pinterest  doesn't return clean JSON so we use regex
		$pinterest = wp_remote_get('http://api.pinterest.com/v1/urls/count.json?callback=receiveCount&url=' . $this->current_url);
		if( !is_wp_error( $pinterest ) ) {
			
			$pinterest = json_decode(preg_replace('/^receiveCount\((.*)\)$/', "\\1", $pinterest['body']),true);
			$pinterest = $pinterest['count'] == NULL ? 0 : $pinterest['count'];
		} else {
			$pinterest = 0;
		} 
		
		return $pinterest;
	
	}

	public function get_shares_googleplus($post_id) {

		$data = array(
			'method' => 'pos.plusones.get', 
			'id' => 'p', 
			'params' => array(
				'nolog' => true, 
				'id' => $this->current_url, 
				'source' => 'widget', 
				'userId' => '@viewer', 
				'groupId' => '@self'
			), 
			'jsonrpc' => '2.0', 
			'key' => 'p', 
			'apiVersion' => 'v1'
		);
		
		$googleplus = wp_remote_post(
			'https://clients6.google.com/rpc?key=AIzaSyCKSbrvQasunBoV16zDH9R33D88CeLr9gQ', array(
				'method' => 'POST', 
				'headers'  => "Content-type: application/json\r\n",
				'body' => json_encode($data)
			)
		);
		
		if( !is_wp_error( $googleplus ) ) {
			
			$googleplus = json_decode($googleplus['body'],true);
			$googleplus = $googleplus['result']['metadata']['globalCounts']['count'];
		} else {
			$googleplus = 0;
		}
		
		return $googleplus;
	
	}

	/**
	 * Format the share count number to eg. 1k, 2.2k etc. 
	 *
	 * @since    1.0.6
	 */
	public function share_count_pretty($ugly_count) {

		$count = (int)$ugly_count;

		if($count < 1000) { 
		   return $count;
		} else if ($count < 9999){
		   $count = round($count / 1000, 1) .'k';
		} else if ($count < 99999){
		   $count = round($count / 1000, 0) .'k';
		} else {
		   $count = round($count / 1000000, 1) .'m';
		}

		return $count;
		
	}


	private function get_post_transient($transient_name) {

		$transient = get_transient($transient_name);
		if(!empty($transient)) 
			return $transient;
		else 
			return FALSE;

	}

	private function set_post_transient($new_transient) {
		set_transient($this->transient_name, $new_transient, 60*60*24);
	}


}
