<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class RSS_Retriever_Feed {
	private $feed_items 	= array(); // each feed post item
	private $settings 		= array(); // shortcode attributes
	private $transient_key 	= ''; // used as a key to check cached feeds
	private $is_cached 		= false;

	function __construct($data) {
		// wp_rss_retriever_debug($data);
		if(!is_array($data)) {
			wp_rss_retriever_error("Unable to construct " . get_class($this) . " with variable type: " . gettype($data));
		} else if (count($data) > 0) {
			// generate an encrypted key with the settings array
			$this->set_transient_key($data);

			if (!$this->get_cached_feed()) {
				foreach ($data as $name => $value) {
					$this->settings[$name] = $value;
				}
				// wp_rss_retriever_debug($this->settings);
				if ($this->settings['ajax'] === 'false') {
					$this->validate_settings();
					$this->retrieve_feed();
				}
			}
		} else {
			wp_rss_retriever_error("Not enough data.");
		}
	}

	private function retrieve_feed() {
		// fetch the feed
		$rss = new SimplePie();
		$rss->set_feed_url($this->settings['url']);	
		$rss->set_useragent('Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.100 Safari/537.36');	
		$rss->force_feed(true);
		$rss->enable_cache(false);
		$rss->enable_order_by_date(false);
		$rss->init();
    	$rss->set_output_encoding( get_option( 'blog_charset' ) );
		$rss->handle_content_type();

    	if (!$rss || !is_wp_error( $rss )) {
    		// if the feed doesn't work, try the WP built-in method fetch_feed
    		if (@$rss->get_item_quantity( $this->settings['items'] ) == 0) {
    			// reset the built-in cache to 1 second as we'll be using our own caching
    			add_filter( 'wp_feed_cache_transient_lifetime' , array(__CLASS__, 'return_1') );
    			// UPDATE: The WP Built-in method currently causes PHP error in PHP 8.0+
    			// $rss = fetch_feed($this->settings['url']);
    			remove_filter( 'wp_feed_cache_transient_lifetime' , array(__CLASS__, 'return_1') );
    		}
    		// use original feed order
    		if ($this->settings['orderby'] === 'date' || $this->settings['orderby'] === 'date_reverse') {
                $rss->enable_order_by_date(true);
            }
            // suppress "non-numeric value warning for Date"
		    if (!is_wp_error( $rss ) && !@$rss->get_item_quantity( $this->settings['items'] ) == 0) {
		    	$rss_items = $rss->get_items( 0, $this->settings['items'] );
		    	$rss_items = $this->orderby_sort($rss_items);

		    	foreach($rss_items as $item) {
		    		$item_object = new RSS_Retriever_Feed_Item($item, $this->settings);
		    		$this->feed_items[] = $item_object;
		    	}

		    	$this->set_cached_feed();
		    } else {
		    	// include the rss error message from the object if it exists
		    	if (isset($rss->error[0])) {
		    		$more_details = ' More details: ' . $rss->error[0];
		    	} else {
		    		$more_details = '';
		    	}
		    	if (is_array($this->settings['url'])) {
		    		$return_url = implode(',', $this->settings['url']);
		    	} else {
		    		$return_url = $this->settings['url'];
		    	}
		    	wp_rss_retriever_error('No RSS items found with URL: <strong>' . $return_url . '</strong>.' . $more_details);
		    }
		} else {			
			if (is_array($this->settings['url'])) {
				$return_url = implode(',', $this->settings['url']);
			} else {
				$return_url = $this->settings['url'];
			}
			wp_rss_retriever_error('Unable to fetch RSS feed with URL: <strong>' . $return_url . '</strong>.');
		}
	}

	public function return_1() {
		return 1;
	}

	private function set_transient_key($data) {
		// always reset ajax setting to false, this way the data is cached regardless of the request method
		$data['ajax'] = 'false';
		// set the transient to an encrypted key (ex. rss_retriever_feed_96f040501c24f0cbe83a95ec2b148b62)
		$this->transient_key = 'rss_retriever_feed_' . md5(json_encode($data));
	}

	private function get_cached_feed() {
		// check if a cached version of the feed exists
		if ($cache = get_transient($this->transient_key)) {
			$this->feed_items = $cache->feed_items;
			$this->settings = $cache->settings;
			$this->is_cached = true;
			return true;
		} else {
			return false;
		}
	}

	private function set_cached_feed() {
		// store the feed into transient cache
		set_transient( $this->transient_key, $value = $this, $expires = $this->settings['cache'] );
	}

	public function display_feed() {
		if (!$this->settings['ajax'] || $this->is_cached) :
	        $output = '<div' . $this->get_wrapper_classes() . '>';
	            $output .= '<ul class="wp_rss_retriever_list">';
	            	foreach($this->feed_items as $item) {
	                    $output .= '<li' . $this->get_item_inline_css() . ' class="wp_rss_retriever_item">';
		                    $output .= '<div class="wp_rss_retriever_item_wrapper">';
		                    	$default_layout = array(
		                    		'title', 
		                    		'thumbnail', 
		                    		'content', 
		                    		'postdata'
		                    	);
		                    	$layout = apply_filters( 'wp_rss_retriever_layout',  $default_layout);

		                    	// set the default layout values (title, thumbnail, content, postdata)
		                    	foreach($default_layout as $default_layout_item => $value) {
		                    		if (false !== $key = array_search($value, $layout)) {
		                    			$func_name = 'get_' . $value;
		                    			if (method_exists($this, $func_name)) {
			                    			$layout[$key] = $this->$func_name($item);
			                    		}
		                    		}
		                    	}
		                    	// add layout items to the output
		                    	for ($i=0; $i < count($layout); $i++) { 
		                    		$output .= $layout[$i];
		                    	}
		            		$output .= '</div>';
	            		$output .= '</li>';
	            	}
	            $output .= '</ul>';
	            $output .= $this->get_credits();
	        $output .= '</div>';
	    else :
	    	$id = 'rss' . substr(str_shuffle(MD5(microtime())), 0, 10);
	    	$loading_gif = WP_RSS_RETRIEVER_PLUGIN_URL . 'inc/imgs/ajax-loader.gif';

	    	$output  = '<div class="wprss_ajax" data-id="' . $id . '">';
	    		$output .= '<img src="' . $loading_gif . '" alt="Loading RSS Feed" width="16" height="16">';
	    	$output .= '</div>';


	    	$this->settings['nonce'] = wp_create_nonce('rss-retriever-ajax-nonce');
	    	$this->settings['ajax_url'] = admin_url( 'admin-ajax.php' );

    		wp_register_script('rss-retriever-ajax', WP_RSS_RETRIEVER_PLUGIN_URL . 'inc/js/rss-retriever-ajax.js', $deps = array('jquery'), $ver = WP_RSS_RETRIEVER_VER, $in_footer = true);
    		wp_enqueue_script('rss-retriever-ajax');
	    	wp_localize_script( 'rss-retriever-ajax', $id, $this->settings );
	    endif;

		return $output;
	}

	private function orderby_sort($items) {
		if ($this->settings['orderby'] == 'date_reverse') {
		    $items = array_reverse($items);
		}

		if ($this->settings['orderby'] == 'random') {
		    shuffle($items);
		}

		return $items;
	}

	private function get_title($item) {
		if ($this->settings['title']) {
		    $output = '<a class="wp_rss_retriever_title"' . $this->get_link_target() . ' href="' . esc_url($item->permalink) . '"' .
		        $this->get_link_dofollow() .
		        'title="' . esc_attr($item->title) . '">';
		        $output .= wp_specialchars_decode(apply_filters( 'wp_rss_retriever_title', esc_html($item->title)));
		    $output .= '</a>'; 

		    return $output;  
		} else {
			return null;
		}
	}

	private function get_thumbnail($item) {
		if (property_exists($item, 'thumbnail') && $this->settings['thumbnail'] && (isset($item->thumbnail) && strlen($item->thumbnail)) > 0) {
			$output = '<a class="wp_rss_retriever_image"' . $this->get_thumbnail_inline_styles() . $this->get_link_target()  . $this->get_link_dofollow() . ' href="' . esc_url($item->permalink) . '">';
				$output .= '<img class="portrait" src="' . esc_attr($item->thumbnail) . '" alt="' . esc_attr($item->title) . '" onerror="this.parentNode.style.display=\'none\'"/>';
			$output .= '</a>'; 
		} else {
			$output = null;
		}

		return $output;
	}

	private function get_content($item) {
		if ($this->settings['excerpt']) {
			$output = '<div class="wp_rss_retriever_container">';
			    $output .= esc_html($item->content);

			    // read more link
			    if($this->settings['read_more']) {
			        $output .= ' <a class="wp_rss_retriever_readmore"' . $this->get_link_target() . 
			        	' href="' . esc_url($item->permalink) . '"' .
			            $this->get_link_dofollow() .
			            'title="' . esc_attr($item->title) . '">';
			            	$output .= __( 'Read more', 'wp-rss-retriever' ) . '&nbsp;&raquo;';
			        $output .= '</a>';
			    }
			$output .= '</div>';
		} else {
			$output = null;
		}

		return $output;
	}

	private function get_postdata($item) {
		if ($this->settings['source'] || $this->settings['date']) {
		    $output = '<div class="wp_rss_retriever_metadata' .'">';
		        // source
		        if ($this->settings['source'] && $item->source) {
		            $label = __( 'Source', 'wp-rss-retriever' ) . ': ';
		            $output .= '<span class="wp_rss_retriever_source">' . $label . '<span>' . esc_html($item->source) . '</span></span>';
		        }
		        // separator
		        if ($this->settings['source'] && $this->settings['date']) {
		            $output .= ' | ';
		        }
		        // date
		        if ($this->settings['date'] && $item->date) {
		            $label = __( 'Published', 'wp-rss-retriever' ) . ': ';
		            $output .= '<span class="wp_rss_retriever_date">' . $label . '<span>' . esc_html($item->date) . '</span></span>';
		        }
		    $output .= '</div>';
		} else {
			$output = null;
		}

		return $output;
	}

	private function get_thumbnail_inline_styles() {
	    if ($this->settings['thumbnail']){
	        $output = ' style="width:' . esc_attr($this->settings['thumbnail']['width']) . '; height:' . esc_attr($this->settings['thumbnail']['height']) . ';"';
	    } else {
	        $output = '';
	    }
	    return $output;
	}

	private function get_link_target() {
		if ($this->settings['new_window']) {
			return ' target="_blank"';
		} else {
			return null;
		}
	}

	private function get_link_dofollow() {
		if (!$this->settings['dofollow']) {
			return ' rel="nofollow"';
		} else {
			return null;
		}
	}

	private function get_wrapper_classes() {
		$layout_classes = '';
		$output = ' class="wp_rss_retriever' . $layout_classes . '"';
		
		return $output;
	}

	private function get_item_inline_css() {
		$output = '';

		return $output;	
	}

	private function get_credits() {
		if ($this->settings['credits']) {
		    $lang = array(
		        'Theme Mason'                   => __('Theme Mason', 'wp-rss-retriever'),
		        'thememason.com'                => __('thememason.com', 'wp-rss-retriever'),
		        'WordPress RSS Feed Retriever'  => __('WordPress RSS Feed Retriever', 'wp-rss-retriever'),
		        'WordPress RSS Feed'            => __('WordPress RSS Feed', 'wp-rss-retriever'),
		        'WordPress RSS'                 => __('WordPress RSS', 'wp-rss-retriever'),
		        'WordPress Feed'                => __('WordPress Feed', 'wp-rss-retriever'),
		        'RSS Feed WordPress'            => __('RSS Feed WordPress', 'wp-rss-retriever'),
		        'WordPress RSS Feed Plugin'     => __('WordPress RSS Feed Plugin', 'wp-rss-retriever'),
		        'RSS Feed Aggregator'           => __('RSS Feed Aggregator', 'wp-rss-retriever'),
		        'RSS Aggregator'                => __('RSS Aggregator', 'wp-rss-retriever'),
		        'RSS Feed Plugin'               => __('RSS Feed Plugin', 'wp-rss-retriever'),
		        'Custom RSS Feed'               => __('Custom RSS Feed', 'wp-rss-retriever'),
		        'Custom News Feed'              => __('Custom News Feed', 'wp-rss-retriever'),
		        'Powered'                       => __('Powered', 'wp-rss-retriever'),
		        'by'                            => __('by', 'wp-rss-retriever'),
		    );

		    $plugin = array(
		        array('19'  => $this->concat_credit($lang['WordPress RSS Feed Retriever'] . ' ' . $lang['by'], $lang['Theme Mason'])),
		        array('10'  => $this->concat_credit($lang['WordPress RSS Feed'] . ' ' . $lang['by'], $lang['Theme Mason'])),
		        array('9'   => $this->concat_credit($lang['Powered'] . ' ' . $lang['by'], $lang['Theme Mason'])),
		        array('9'   => $this->concat_credit($lang['WordPress RSS Feed Retriever'] . ' ' . $lang['by'], $lang['thememason.com'])),
		        array('5'   => $this->concat_credit($lang['WordPress RSS Feed'] . ' ' . $lang['by'], $lang['thememason.com'])),
		        array('17'  => $this->concat_credit($lang['Powered'] . ' ' . $lang['by'], $lang['WordPress RSS Feed Retriever'])),
		        array('7'   => $this->concat_credit($lang['Powered'] . ' ' . $lang['by'], $lang['WordPress RSS Feed'])),
		        array('2'   => $this->concat_credit($lang['Powered'] . ' ' . $lang['by'], $lang['WordPress RSS'])),
		        array('1'   => $this->concat_credit($lang['Powered'] . ' ' . $lang['by'], $lang['WordPress Feed'])),
		        array('2'   => $this->concat_credit($lang['Powered'] . ' ' . $lang['by'], $lang['RSS Feed WordPress'])),
		        array('5'   => $this->concat_credit($lang['Powered'] . ' ' . $lang['by'], $lang['WordPress RSS Feed Plugin'])),
		        array('4'   => $this->concat_credit($lang['Powered'] . ' ' . $lang['by'], $lang['RSS Feed Aggregator'])),
		        array('1'   => $this->concat_credit($lang['Powered'] . ' ' . $lang['by'], $lang['RSS Aggregator'])),
		        array('4'   => $this->concat_credit($lang['Powered'] . ' ' . $lang['by'], $lang['RSS Feed Plugin'])),
		        array('3'   => $this->concat_credit($lang['Powered'] . ' ' . $lang['by'], $lang['Custom RSS Feed'])),
		        array('2'   => $this->concat_credit($lang['Powered'] . ' ' . $lang['by'], $lang['Custom News Feed'])),
		    );

		    $newPlugin = array();
		    foreach ($plugin as $array) {
		        $newPlugin = array_merge($newPlugin, array_fill(0, key($array), $array[key($array)]));
		    }

		    mt_srand(crc32(get_bloginfo('url')));
		    $num = mt_rand(0, count($newPlugin) - 1);

		    $output  = '<div class="wp_rss_retriever_credits">';
		        $output .= $newPlugin[$num];
		    $output .= '</div>';
		} else {
			$output = null;
		}

	    return $output;
	}

	private function concat_credit($prepend, $title) {
	    $url = 'https://thememason.com/plugins/rss-retriever/';
	    return $prepend . ' <a href="' . $url . '" title="' . $title . '">' . $title . '</a>';
	}

	/**
	 * Checks each shortcode attribute for validation and displays
	 * errors if the input is invalid. Converts each attribute into 
	 * an appropriate data type (ie. boolean, array, integer, etc.)
	 */
	private function validate_settings() {
		// convert open/closing double quotes
		foreach($this->settings as $attr => $value) {
			$remove_characters = array('“', '”', '‘', '’', '″');
			$value = str_replace($remove_characters, '', $value);
			$this->settings[$attr] = $value;
		}

		// Setting: URL
		// convert comma separated urls into array
		if (isset($this->settings['url'])) {
			if (strpos($this->settings['url'], ',') !== false ) {
				$urls = explode(',', $this->settings['url']);
				foreach($urls as $url) {
					$this->validate_url($url);
				}
				$this->settings['url'] = $urls;
			} else {
				$this->validate_url($this->settings['url']);
				// always use an array
				$this->settings['url'] = array($this->settings['url']);
			}
		}

		// Setting: ITEMS
		if (isset($this->settings['items'])) {
			if (!is_numeric($this->settings['items']) || intval($this->settings['items']) < 1) { 
				$this->validation_error('items');
			} else {
				$this->settings['items'] = intval($this->settings['items']);
			}
		}

		// Setting: ORDERBY
		$acceptable_values = array(
			'default',
			'date',
			'date_reverse',
			'random'
		);
		if (isset($this->settings['orderby'])) {
			if (!in_array($this->settings['orderby'], $acceptable_values)) {
				$this->validation_error('orderby');
			}
		}

		// Setting: TITLE
		$this->validate_str_bool_setting('title');

		// Setting: EXCERPT
		if (isset($this->settings['excerpt'])) {
			if (!is_numeric($this->settings['excerpt']) && $this->settings['excerpt'] !== 'none') {
				$this->validation_error('excerpt');
			} else {
				if (is_numeric($this->settings['excerpt'])) {
					// convert to integer
					$this->settings['excerpt'] = intval($this->settings['excerpt']);
				} else {
					$this->settings['excerpt'] = false;
				}
			}
		}

		// Setting: READ_MORE
		$this->validate_str_bool_setting('read_more');

		// Setting: NEW_WINDOW
		$this->validate_str_bool_setting('new_window');

		// Setting: THUMBNAIL
		// always remove 'px' from the input
		if (isset($this->settings['thumbnail'])) {
			$this->settings['thumbnail'] = str_replace('px', '', $this->settings['thumbnail']);
			if (!$this->is_str_bool($this->settings['thumbnail']) && 
				!is_numeric(str_replace(array('x', '%'), '', $this->settings['thumbnail']))) {
				$this->validation_error('thumbnail');
			} else {
				// "true" or "false"
				if ($this->is_str_bool($this->settings['thumbnail'])) {
					$this->validate_str_bool_setting('thumbnail');

					if ($this->settings['thumbnail'] === true) {
						$this->settings['thumbnail'] = array (
							'width' => '150px',
							'height' => '150px'
						);
					}
				} else {
					// setting has an x (ie 100x200)
					if (strpos($this->settings['thumbnail'], 'x') !== false) {
						$size = explode('x', $this->settings['thumbnail']);
						// make sure both values exist (x99, 99x will fail)
						if (count($size) > 1 && strlen($size[0]) > 0 && strlen($size[1]) > 0) {
							$width = $size[0];
							$height = $size[1];
						} else {
							$this->validation_error('thumbnail');
						}
					} else {
						$width = $this->settings['thumbnail'];
						$height = $this->settings['thumbnail'];
					}

					// add 'px' if '%' is missing
					$width 	= (strpos($width, '%')) ? $width : $width . 'px';
					$height = (strpos($height, '%')) ? $height : $height . 'px';

					$this->settings['thumbnail'] = array (
						'width' => $width,
						'height' => $height
					);
				}
			}
		}

		// Setting: SOURCE
		$this->validate_str_bool_setting('source');

		// Setting: DATE
		$this->validate_str_bool_setting('date');

		// Setting: CACHE
		if (isset($this->settings['cache'])) {
			if (!is_numeric(strtotime($this->settings['cache'], 0)) && !is_numeric($this->settings['cache'])) {
				$this->validation_error('cache');
			} else {
				// convert the cache to seconds if it is a string (ie. 1 hour, 1 day, etc.)
				if (!is_numeric($this->settings['cache'])) {
					$this->settings['cache'] = strtotime($this->settings['cache'], 0);
				}

				if ($this->settings['cache'] === 0) {
					// Do not allow 0 seconds, this will create a cached feed that never expires
					$this->settings['cache'] = -1;
				}
			}
		}

		// Setting: DOFOLLOW
		$this->validate_str_bool_setting('dofollow');

		// Setting: CREDITS
		$this->validate_str_bool_setting('credits');

		// Setting: AJAX
		$this->validate_str_bool_setting('ajax');

	}

	private function validate_str_bool_setting($setting) {
		if (isset($this->settings[$setting])) {
			if (!$this->is_str_bool($this->settings[$setting])) {
				// not 'true' or 'false'
				$this->validation_error($setting);
			} else {
				// convert the value to an actual boolean
				$this->settings[$setting] = ($this->settings[$setting] === 'true') ? true : false;
			}
		}
	}

	private function is_str_bool($value) {
		if ($value !== 'true' && $value !== 'false') {
			return false;
		} else {
			return true;
		}
	}

	private function validate_url($url) {
		$url = filter_var($url, FILTER_SANITIZE_URL);
		if (filter_var($url, FILTER_VALIDATE_URL) !== false) {
			return true;
		} else {
			$this->validation_error('url');
		}
	}

	private function validation_error($setting) {
		wp_rss_retriever_error('Invalid <strong>' . $setting . '</strong> value: <strong>' . $this->settings[$setting] . '</strong>. Please check your shortcode.');
	}
}


// function my_enqueue_scripts() {
//     // wp_enqueue_script('jquery');
//     // wp_localize_script( 'jquery', 'MS_Ajax', array(
//     //     'ajaxurl'       => admin_url( 'admin-ajax.php' ),
//     //     'nextNonce'     => wp_create_nonce( 'myajax-next-nonce' ))
//     // );

//     wp_localize_script( 'rss-retriever-ajax', 'testing', array('test' => 'test') );
// 	wp_enqueue_script('rss-retriever-ajax');
// }
// add_action('wp_enqueue_scripts','my_enqueue_scripts');