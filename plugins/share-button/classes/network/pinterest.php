<?php
namespace MBSocial;


class pinterestNetwork extends mbNetwork
{
	protected $network = 'pinterest';
	protected $icon = 'fa-pinterest';
	protected $priority = 'readmore';
	protected $color = '#cb2027';

	public function __construct()
	{
		$this->label = __('Share', 'mbsocial');
		$this->share_url = 'https://www.pinterest.com/pin/create/bookmarklet/?media={img}&url={url}&is_video=false&description={title}';
		$this->count_api = 'https://api.pinterest.com/v1/urls/count.json?url={url}';
		$this->profile_url = 'http://www.pinterest.com/{profile}';
		$this->countable = true;
		$this->return_var = 'count';
		$this->popup_dimensions = array(750, 500);
		parent::__construct();

	}

	public function remoteRequest($url)
	{
		$response = wp_remote_get($url);

		if (is_wp_error($response) || $response['response']['code'] != 200) {
				return false;
			}
		else {
				$result = wp_remote_retrieve_body($response);
		}

	 	// remove the callback wrapper.
	 	$result = str_replace("receiveCount(","",$result);
	 	$result = substr($result,0,(strlen($result) -1) ); // remove last char.
	 	$json = json_decode($result, true);

	 	if (isset($json["count"]))
	 		return $json["count"];

		return 0;
	}

}
