<?php
/*
Plugin Name: Rumble
Description: Embed a responsive rumble video.
Version:     1.0.8
Author:      Rumble
Author URI:  http://www.rumble.com
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Rumble is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Rumble is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Rumble. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

// Recomended by WP for security reasons
defined('ABSPATH') or die('Denied');

/**
 * Plugin class.
 */
class Rumble
{
    // Caching
    const CACHE_EDITOR_PICKS = 'rumbleeditorpicks';
    const CACHE_EDITOR_PICKS_EXPIRE = 'rumbleeditorpicksset';
    const EDITOR_PICKS_EXPIRE = 3600;

    // Settings
    const OPTIONS = 'rumblesettings';
    const OPTIONS_PUBLISHER_ID = 'publisherid';
    const OPTIONS_DEFAULT_SEARCH_OPTION = 'defaultsearchoption';
    const OPTIONS_RUMBLE_SETTINGS = 'rumblesettingssave';
    const OPTIONS_PLAYER_TYPE = 'playertype';
    const API_DEFAULT_KEY = 'oG13vhEf.91bqxYOk';

    public function __construct()
    {
        // Registers a button to open a search window for rumble videos.
        add_action('media_buttons', 'Rumble::media_button_wizard', 1000);

        // Registers ajax method for getting videos from rumble api.
        add_action('wp_ajax_get_videos', 'Rumble::get_videos_callback');

    	$options = get_option(self::OPTIONS);
	if(empty($options[self::OPTIONS_PLAYER_TYPE]) || $options[self::OPTIONS_PLAYER_TYPE]!='js'){
            // Registers javascript to make rumble video responsive.
            add_action('wp_enqueue_scripts', 'Rumble::make_responsive_video');
	}

	// Registers admin javascript
	add_action('admin_enqueue_scripts', 'Rumble::add_admin_javascript_and_css');

        // Adds plugin page to the menu
        add_action('admin_menu', 'Rumble::plugin_menu');

        // Will be called before post is saved to convert iframe to short code.
        add_filter('wp_insert_post_data', 'Rumble::convert_iframe_to_short_code');

        // Registers short code for embedding rumble video
        add_shortcode('rumble', 'Rumble::register_shortcode');
    }

    public static function media_button_wizard()
    {
        $options = get_option(self::OPTIONS);

        $publisherId = $options[self::OPTIONS_PUBLISHER_ID];

        require 'admin' . DIRECTORY_SEPARATOR . 'media_button_wizard.php';
    }

    public static function get_videos_callback()
    {
        $options = get_option(self::OPTIONS);

        $publisherId = $options[self::OPTIONS_PUBLISHER_ID];

        if (empty($publisherId) === true) {
            $publisherId = self::API_DEFAULT_KEY;
        }

        $tab = urlencode($_POST['tab']);

	$searchParamValue = "";
	$search = urlencode($_POST['search']);
	if (preg_match('/^https?:\/\//', trim(urldecode($search))) === 1) {
	    $searchParamValue = "&url=$search";
	} elseif(!empty($search)) {
	    $searchParamValue = "&q=$search";
	}

	$query_url = "https://rumble.com/api/v0/Media.Search.json?_p=".$publisherId.$searchParamValue;

        $page = $_POST['page'];

        $response = false;
        if ($tab === 'rumble-results') {
		$query_url .= "&criteria=pg=$page,sort=rumblerank";
        } else if ($tab === 'rumble-editor-picks') {
	    if(empty($searchParamValue)){
	            if (($data = get_option(self::CACHE_EDITOR_PICKS)) !== false && time() - (int) get_option(self::CACHE_EDITOR_PICKS_EXPIRE) <= self::EDITOR_PICKS_EXPIRE) {
	                $response = $data;
	            } else {
	                $response = wp_remote_retrieve_body(wp_remote_get("https://rumble.com/api/v0/Media.Search.json?_p=$publisherId&$searchParamValue&criteria=sort=date,editorpicks"));
	                update_option(self::CACHE_EDITOR_PICKS, $response);
	                update_option(self::CACHE_EDITOR_PICKS_EXPIRE, time());
	            }
            }else{
		$query_url .= "&criteria=pg=$page,sort=date,editorpicks";
	    }
        } else if ($tab === 'rumble-newest-videos') {
	    $query_url .= "&criteria=pg=$page,sort=date";
        } else if ($tab === 'rumble-your-videos') {
	    $query_url .= "&criteria=pg=$page&user=me";
	}
	if(!$response){
		$response = wp_remote_retrieve_body(wp_remote_get($query_url));
	}

        $responseObj = json_decode($response);

        if (is_array($responseObj->results) === true) {
            foreach ($responseObj->results as &$result) {
                $result->description = strip_tags($result->description);
            }
        }
	$responseObj->serverURL = $query_url;

        echo json_encode($responseObj);

	    wp_die();
    }

    public static function make_responsive_video()
    {
        wp_enqueue_style('rumble-style', plugins_url('css' . DIRECTORY_SEPARATOR . 'rumble.css', __FILE__));
        wp_enqueue_script('rumble-script', plugins_url('js' . DIRECTORY_SEPARATOR . 'rumble.js', __FILE__), array('jquery'));
    }

    public static function add_admin_javascript_and_css()
    {
        wp_enqueue_script('rumble-javascript', plugins_url('admin' . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'rumble.js', __FILE__), array('jquery'));
        wp_enqueue_style('rumble-css', plugins_url('admin' . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'rumble.css', __FILE__));
    }

    public static function plugin_menu()
    {
        add_submenu_page(
            'options-general.php',
            'Rumble Settings',
            'Rumble',
            'manage_options',
            'rumble-settings',
            'Rumble::show_settings'
        );
    }

    public static function show_settings()
    {
        $optionValues = get_option(self::OPTIONS);
        $keySet = false;

        // Determines whether to save settings or not.
        if (isset($_POST[self::OPTIONS_RUMBLE_SETTINGS]) && $_POST[self::OPTIONS_RUMBLE_SETTINGS] == 'R')
        {
            $publisherId = $_POST[self::OPTIONS_PUBLISHER_ID];
	    $playerType = $_POST[self::OPTIONS_PLAYER_TYPE];
            if (empty($publisherId) === false) {
                $keySet = true;
                $response = wp_remote_retrieve_body(wp_remote_get("https://rumble.com/api/v0/Media.Search.json?_p=$publisherId"));

                $responseData = json_decode($response);

                $keyValid = !(isset($responseData->error) === true);

                if ($keyValid) {
		    $optionValues[self::OPTIONS_PLAYER_TYPE] = $playerType;
                    $optionValues[self::OPTIONS_PUBLISHER_ID] = $publisherId;
                    update_option(self::OPTIONS, $optionValues);
                }
            } else {
                $optionValues[self::OPTIONS_PUBLISHER_ID] = $publisherId;
		$optionValues[self::OPTIONS_PLAYER_TYPE] = $playerType;
                update_option(self::OPTIONS, $optionValues);
            }
        }

        $options = get_option(self::OPTIONS);

        $publisherId = $options[self::OPTIONS_PUBLISHER_ID];

        require 'admin' . DIRECTORY_SEPARATOR . 'settings.php';
    }

    public static function convert_iframe_to_short_code($data)
    {
        $replaced = preg_replace_callback(
                        '/<iframe.*src=.?"(.*?).?".*?><\/iframe>/',
                        function ($matches) {
                            if(stripos($matches[0],'rumble')!==false)
                                return '[rumble]'.$matches[1].'[/rumble]';
                            else
                                return $matches[0];
                        },
                        $data['post_content']
                    );

        if ($replaced !== null) {
            $data['post_content'] = $replaced;
        }

        return $data;
    }

    public static function isInstantArticle($set = null){
        static $inIA = null;
	if($set!==null) $inIA = $set;
	if($inIA===null){
		if(defined( 'FIREFLY_DOING_INSTANT_ARTICLE' ) && FIREFLY_DOING_INSTANT_ARTICLE){
			return 1;
		}
		if(defined( 'INSTANT_ARTICLE' ) && INSTANT_ARTICLE){
			return 1;
		}
		if(function_exists('is_transforming_instant_article')){
			return is_transforming_instant_article()?2:false;
		}
	}
	return $inIA;
    }

    public static function register_shortcode($attr, $content = '')
    {
    	$options = get_option(self::OPTIONS);

	$playerType = $options[self::OPTIONS_PLAYER_TYPE];
	if ( function_exists( 'ampforwp_is_amp_endpoint' ) && ampforwp_is_amp_endpoint() ) {
		$playerType = 'iframe';
	}

	switch($playerType){
		case 'js':
			return self::register_shortcode_js($attr, $content);
		default:
			return self::register_shortcode_iframe($attr, $content);
	}
    }

    public static function register_shortcode_iframe($attr, $content = '')
    {
	$inIA = self::isInstantArticle();
	if($inIA===2){
	        $str = "<p><iframe src='{$content}' width=\"360\" height=\"360\" frameborder=\"0\" allowfullscreen=\"1\"></iframe></p>";
	}else{
	        $str = "<div class='videoWrapper'>
	            <iframe src='{$content}' frameborder='0' allowfullscreen></iframe>
	        </div>";
	}
        return $str;
    }

   
    public static function register_shortcode_js($attr, $content = '')
    {
    	static $playerCount = 0;

        if(!preg_match('/http[s]?:\/\/([^\.]+\.)?rumble\.com\/embed(JS)?\/([up][a-z0-9]+)\.([gv][A-Za-z0-9]+)/', $content, $m)){
			return self::register_shortcode_iframe($attr, $content);
		}
		$playerCount ++;
		$divId = 'rumblePlayer'.$playerCount;

		$inIA = self::isInstantArticle();
		$playerSettings = array(
			'video'	=> $m[4],
			'div'	=> $divId,
		);
		$playerSettingsDefault = array(
			'rel'	=> 5
		);
		if(!$attr) $attr = array();
		$playerSettings = array_merge($playerSettingsDefault, $attr, $playerSettings);
	
		if($inIA) $playerSettings['ia'] = 1;

		$content = str_replace('rumble.com/embed/','rumble.com/embedJS/', $content);
		$ps = 'Rumble("play", '.json_encode($playerSettings).');'; 
		$str = '<div id="'.$divId.'"></div>';
		$str .= '<script type="text/javascript" src="'.$content.'"></script>';
		$str .= '<script type="text/javascript">'.$ps.'</script>';
		if($inIA===2){
			$str = '<iframe>'.$str.'</iframe>';
		}

        return $str;
    }
}

new Rumble();
