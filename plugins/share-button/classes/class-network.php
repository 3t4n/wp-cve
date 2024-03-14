<?php
namespace MBSocial;
defined('ABSPATH') or die('No direct access permitted');

use \MaxButtons\MaxUtils as MaxUtils;
use \MaxButtons\simple_html_dom as simple_html_dom;

abstract class mbNetwork
{

	protected $network; // internal name of network
	protected $priority = 'unselected'; // Default priority of network - selected / unselected / readmore / hidden

	//protected $api_url;  // URL of API to request information
	protected $countable = false;  // If network supports count of likes / shares / favs
	protected $count_api = '';   // API URL for requesting counts
	protected $count_check_time = 14400; // (PHP 5.3)  4 * HOUR_IN_SECONDS;  // Transient time, how often to check network for new counts
	protected $share_url = null;  // URL to link to when sharing something
	protected $alternate_url = null; // if another URL needs to be
	protected $alternate_label = null;  // Label for the other option (checkbox)
	protected $is_popup = true;  // Open in Popup or not
	protected $popup_dimensions = array(400,300);  // Default popup dimensions
	protected $return_var;  // Count API return var ( JSON format )

	// mobile or not settings - not yet developed
	protected $displayMobile = true; // network should be displayed on mobiles
	protected $displayDesktop = true; // network should be displayed on desktop

	/** Options for Share Icons */
	protected $profile_url = null; // URL to the network's profiles.
	protected $profile_placeholder; // Placeholder for profile input text field [aka 'user name' or so ]
	protected $profile_label;  // Not in use at the moment?

	protected $nice_name; // Nice name for display in the Editor interface

	protected $label; // Label (default) Text. This will be in the button, e.g. on hover

	protected $icon_type = 'fab'; // Icon library set
	protected $icon = 'fa-circle'; // Icon
	protected $icon_image_id; // for svg type icons.
	protected $icon_image_url;
	protected $icon_image_size;
//	protected $icon_image_alt = '';

	protected $color = '#fff';

	// extra options [internal]
	protected $is_native = true; // is this network built-in, or imported? Relevant for network settings
	protected $is_editable = true; // should this network be user-editable?
	protected $is_active = true;
	protected $is_limitedpro = false; // the network is only intended for MB PRO
	protected $forcesamewindow = false; // never open this in a new window / i.e. email / print type networks

	protected $default_options = null;

	public function __construct()
	{
		if (is_null($this->profile_placeholder))
			$this->profile_placeholder = __('User Name','mbsocial');
		if (is_null($this->profile_label))
			$this->profile_label = __('Follow', 'mbsocial');

		$this->default_options = $this->get_all_options(); // init them as default. For save function
	}

	public function get($name)
	{
		if (isset($this->$name))
			return $this->$name;
		else
			return null;
	}

	public function get_all_options()
	{
			$popup_width = isset($this->popup_dimensions[0]) ? $this->popup_dimensions[0] : 0;
			$popup_height = isset($this->popup_dimensions[1]) ? $this->popup_dimensions[1] : 0;


			// iterate on this next time perhaps.
			//$attrs = get_object_vars($this);

			$options = array(
				'active' => $this->is_active,  // no nw setting for this
				'popup' => $this->is_popup,
				'label' => $this->label,
				'share_url' => $this->share_url,
				'profile_url' => $this->profile_url,
				'popup_width' => $popup_width,
				'popup_height' => $popup_height,
				'icon_type' => $this->icon_type,
				'icon' => $this->icon,
				'icon_image_id' => $this->icon_image_id,
				'icon_image_url' => $this->icon_image_url,
		//		'icon_image_alt' => $this->icon_image_alt,
				'icon_image_size' => $this->icon_image_size,
				'color' => $this->color,
				'displayMobile' => $this->displayMobile,
				'displayDesktop' => $this->displayDesktop,
			);

			return $options;
	}

	public function get_all_defaults()
	{
		return $this->default_options;
	}

	public function load_settings($settings)
	{
		if (! $settings)
			return;
		foreach($settings as $setting => $value)
		{
			if (is_null($value) || strlen($value) == 0)
				continue;

 			switch($setting)
			{
					case 'active':
						$this->is_active  = ($value == 1) ? true : false;
					break;
					case 'popup':
						$this->is_popup = ($value == 1) ? true : false;
 					break;
					case 'popup_width':
						$this->popup_dimensions[0] = $value;
					break;
					case 'popup_height':
						$this->popup_dimensions[1] = $value;
					break;
					default:
						$this->{$setting} = $value;
					break;
			}
		}
	}


	public function forMobile()
	{
		return $this->displayMobile;
	}

	public function forDesktop()
	{
		return $this->displayDesktop;
	}

	/** If current running setting of network should display count
	*
	*	 Even if network supports displaying a counter, this should not be the case when showing the 'profile'
	*  @return boolean
	*/
	public function isCountable()
	{
		$use_profile = MBSocial()->whistle()->ask('editor/profile/use');
		if ($use_profile == 1)
				return false;

 		return $this->countable;
	}

	// If network is capable of sharing profile
	public function is_share_icon()
	{
			if (is_null($this->profile_url))
					return false;

			return true;
	}

	/** If network is capable of sharing site page */
	public function is_social_share()
	{
			 if (is_null($this->share_url))
			 	return false;

		return true;
	}

	public function has_alternate()
	{
		   if (is_null($this->alternate_url))
			 	return false;

			return true;
	}

	/** Use this function to check if popup link should be used **/
	public function is_popup()
	{
		$use_profile = MBSocial()->whistle()->ask('editor/profile/use');
				if ($use_profile == 1)
					return false;
				else {
						return $this->is_popup;
				}
	}

	/** Return the label for this network and use **/
	public function get_label()
	{
		$use_profile = MBSocial()->whistle()->ask('editor/profile/use');
		if ($use_profile == 1 && strlen($this->profile_label) > 0)
				return $this->profile_label;
			else
				return $this->label;
	}

	public function get_nice_name()
	{
		if (isset($this->nice_name))
			return $this->nice_name;
		else
			return ucfirst($this->network);
	}

	/** Find and get the proper URL to link to.
	* This can be the network link to sharing a page
	* or the network link to linking the profile.
	* Try to find out automatically or whistle ask */
	public function get_url()
	{
		 	$is_profile = $this->is_share_icon();
			$is_share = $this->is_social_share();

			if ($is_profile && ! $is_share) // profile only network
			{
				return $this->profile_url;
			}
			elseif (! $is_profile && $is_share) // share page only network
			{
				return $this->share_url;
			}
			elseif ($is_profile && $is_share)
			{
				$use_profile = MBSocial()->whistle()->ask('editor/profile/use');
				$use_alternate = MBSocial()->whistle()->ask('editor/profile/alternate');

				if ($use_profile == 1)
				{
					if ($use_alternate == 1)
						return $this->alternate_url;
					else
						return $this->profile_url;
				}
				else {
						return $this->share_url;
				}

			}
	}

	/* Network dependent creating of a button. Called from collection class */
	public function createButton($args = array())
	{
			$defaults = array('link' => 'javascript:void(0)',
						'preview' => false,
						'index' => -1,
						'name' => '',
						'data' => array(),
						'collection_count' => 0,
				);

		$item_index = $args['index'];
		if ($args['collection_count'] > 1)
		{
			$item_index = $args['collection_count'] . "_" . $item_index;
		}

		$args = wp_parse_args($args, $defaults);
		$html = "<span class='mb-item item-" . $item_index  . "'>
								<a href='" . $args['link'] . "' class='mb-social '>
						";

		$html .= "</a></span>";

		$button = \MaxButtons\str_get_html($html);
		return $button;
	}

	public function getRemoteShareCount($share_url)
	{
		if (! $this->countable)
			return false;

		$count_api = $this->count_api;

 		if ($count_api == '') return false; // no api

		$network = $this->network;
		$timeout = 60; // prevent the same requests from running multiple times ( i.e. one page, many collections on same url ) .
		$locked = maxUtils::get_transient('shares-' . $network . '-' . $share_url. '-lock');

		if ($locked == true)
			return 'locked';  // try again on next refresh.

		//lock out next request while this one is still running.
		maxUtils::set_transient('shares-' . $network . '-' . $share_url . '-lock', true, $timeout );

 		$count_api = str_replace("{url}", $share_url, $count_api);

 		$count = $this->remoteRequest($count_api);

 		if (defined('MAXBUTTONS_DEBUG') && MAXBUTTONS_DEBUG)
 		{
 			$admin = MB()->getClass("admin");
 			$admin->log("Get Remote Share", "Call: $count_api - Network : " . $this->network . " - Count: $count \n ");
 		}

		if ($count !== false)
		{
			$network = $this->network;
			$check_time = $this->count_check_time;

			// set count
			maxUtils::set_transient('shares-' . $network . '-' . $share_url, $count, $check_time );

		}

		// remove lock
		maxUtils::delete_transient('shares-' . $network . '-' . $share_url . '-lock');

 		return $count;
 	}

	protected function remoteRequest($url)
	{
		$response = wp_remote_get($url);
		$result_path = $this->return_var;
 		$result_array = explode("|",$result_path);
 		if (count($result_array) == 0)
 			$result_array = array($result_path);

		if (is_wp_error($response) || $response['response']['code'] != 200) {
			return false;
		}
		else {
			$result = wp_remote_retrieve_body($response);
		}
			$result = json_decode($result, true);


 		foreach($result_array as $result_val)
 		{
			if (isset($result[$result_val]))
	 			$result = $result[$result_val];

 		}
 		if (is_int($result))
 			return $result;

		return 0; // some networks don't return the json return var. Only return false on network errors

	}

	public function getShareCount($args = array())
	{
		if ( $this->count_api == '')
			return 0; // no api - count always zero.

		$defaults = array(
				"url" => "",
				"preview" => false,
				"force_share_update" => false,

		);

		$args = wp_parse_args($args,$defaults);

		$share_url = esc_url($args["url"]);
		$network = $this->network;
		//$count = get_transient('mb-col-' . $network . '-' . $share_url . '-shares');
		$count = maxUtils::get_transient('shares-' . $network . '-' . $share_url);

		if ($args["force_share_update"])
			$count = -1; // force an update

		if ( ($count === false || $count == -1) && ! $args["preview"])
		{	// request from external - this is done via ajax on runtime.
			return false;
		}

		return $count;
	}


	/** Function to display additional network-specific options.
	* @return String Option Output.
	**/
	public function admin() {
		return '';

	}


}
