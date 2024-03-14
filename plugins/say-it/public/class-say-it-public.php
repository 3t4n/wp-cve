<?php
/**
 * The public-facing functionality of the plugin.
 */
class Say_It_Public {

	private $plugin_name;
	private $version;
	private $options;
	private $api_error;

	private $google_tts;
	private $amazon_polly;

	/**
	 * Initialize the class and set its properties.
	 */
	public function __construct( $plugin_name, $version, $options ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->api_error = null;
		$this->options = $options;

		// if( isset($this->options['mode']) && $this->options['mode'] == 'amazon' ){
			$this->amazon_polly = new Say_It_Amazon_Polly( $this->plugin_name, $this->options );
		// }
		// if ( isset($this->options['mode']) && $this->options['mode'] == 'google' ){
			$this->google_tts = new Say_It_Google_TTS( $this->plugin_name, $this->options );
		// }
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		if (is_array($this->options) && array_key_exists("skin", $this->options) && isset($this->options['skin'])){
			$skin = $this->options['skin'];
		}else{
			$skin = 'theme1.css';
		}
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . "css/themes/$skin", array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/jquery.sayit.js', array( 'jquery', 'wp-util' ), $this->version, false );
	}

	/**
	 * Ajax route for getting mp3
	 * @since    1.0.0
	 */
	function sayit_get_mp3_ajax() {
		$words = $_POST['words'];
		$response = [
			'mp3' => $this->amazon_polly->get_mp3($words)
		];
		wp_send_json_success($response);
		die(); 
	}

	/**
	 * Ajax route for bulk getting mp3
	 * @since    1.0.0
	 */
	function sayit_get_mp3_ajax_bulk() {
		@ini_set( 'display_errors', 1 );
		$words = $_POST['words'];
		$response = [];
		foreach($words as $word){
			array_push($response, $this->get_mp3_file($word));
		}
		wp_send_json_success($response);
		die(); 
	}

	/**
	 * Register main shortcode
	 * @since    3.0.1
	 */
	public function shortcode_function( $atts, $content = null ) {
		/* Say it not enable, we just return the content */
		if(isset($this->options['mode']) && $this->options['mode'] == 'disabled'){
			return $content;
		}

		/* Get the parameters */
		$args = $this->format_shortcode_attributes($atts);

		/* Choose between default or alternative text */
		$text = (isset($args['alt']))?$args['alt']:strip_shortcodes(wp_strip_all_tags(html_entity_decode($content)));

		/* Choose between inline or block type */
		$blocktype = ($args['block'])?'div':'span';

		/* Get the mp3 attributes */
		try{
			$mp3_file = $this->get_mp3_file($text, $args);
			$mp3_attribute = 'data-mp3-file="' . $mp3_file . '"';
		} catch (Throwable $e) {
			$api_error = $e->getMessage();
			$mp3_attribute = 'data-error="' . $api_error . '"';
		}

		/* Return everything we need for the javascript companion */
		$return = '<'.$blocktype.' class="sayit" data-say-content="'.esc_attr($text).'" data-speed="'.$args['speed'].'" data-lang="'.$args['lang'].'" '.$mp3_attribute.'>';
		$return .= $content;
		$return .= '</'.$blocktype.'>';
		return $return;
	}


	/**
	 * Register mp3 player shortcode
	 * @since    3.0.1
	 */
	public function shortcode_mp3_player_function( $atts ) {
		if(isset($this->options['mode']) && $this->options['mode'] == 'disabled'){
			return null;
		}

		/* Get the content */
		$text = wp_strip_all_tags(html_entity_decode(get_the_content()));
		$text = preg_replace("~(?:\[/?)[^/\]]+/?\]~s", '', $text);
		$text = preg_replace('/\s+/', ' ', $text);

		/* Get the mp3 url */
		try{
			$mp3_file = $this->get_mp3_file($text);
		} catch (Throwable $e) {
			$api_error = $e->getMessage();
			echo $api_error;
		}

		ob_start();
		$attr = array(
			'src'      => $mp3_file,
			'loop'     => '',
			'autoplay' => '',
			'preload' => 'none'
			);
		?>
			<div class="audio-player">
				<?php echo wp_audio_shortcode( $attr ); ?>
			</div>
			<style>
				/* Wrapper */
				.audio-player{
					background: #57a3bc;
					padding: 5px;
					border-radius: 10px;
					margin-top: 30px;
				}
		
				/* Transparent background */
				.audio-player .mejs-container,
				.audio-player .mejs-container .mejs-controls,
				.audio-player .mejs-embed, .mejs-embed body {
					background: transparent !important;
				}
		
				/* Remove padding */
				.audio-player .mejs-controls{
					padding: 0;
				}
		
				/* Fix button style */
				.mejs-button > button,
				.mejs-button > button:hover,
				.mejs-button > button:focus{
					border-radius: 0;
					background-color: transparent;
				}
		
				/* Player time current color */
				.audio-player .mejs-controls .mejs-time-rail .mejs-time-current {
					background-color: #37768b;
				}
		
				/* Prevent flickering */
				.audio-player {
					display:none;
				}
			</style>
			<script type="text/javascript">
			jQuery(document).ready(function() { 
				jQuery('.audio-player').show(); 
			});
			</script>
		<?php
    	return ob_get_clean();
	}
	
	/**
	 * Register player shortcode
	 * @since    3.0.1
	 */
	public function shortcode_player_function( $atts, $content = null ) {
		/* Say it not enable, we just return the content */
		if(isset($this->options['mode']) && $this->options['mode'] == 'disabled'){
			return $content;
		}

		/* Get the parameters */
		$args = $this->format_shortcode_attributes($atts);

		/* Get the content */
		$text = wp_strip_all_tags(html_entity_decode(get_the_content()));
		$text = preg_replace("~(?:\[/?)[^/\]]+/?\]~s", '', $text);
		$text = preg_replace('/\s+/', ' ', $text);

		/* Get the mp3 url */
		try{
			$mp3_file = $this->get_mp3_file($text, $args);
		} catch (Throwable $e) {
			$api_error = $e->getMessage();
		}

		// Create DOM
		$dom = new DOMDocument();
		$node = $dom->createElement('span', 'Play audio');
		$newnode = $dom->appendChild($node);
		
		// Set the attribute
		$newnode->setAttribute("class", "sayit");
		$newnode->setAttribute("data-say-content", esc_attr($text));
		$newnode->setAttribute("data-tooltip", $this->options['tooltip_text']);
		$newnode->setAttribute("data-speed", $args['speed']);
		$newnode->setAttribute("data-lang", $args['lang']);
		$newnode->setAttribute("data-mode", $args['mode']);
		if(isset($mp3_file)) $newnode->setAttribute("data-mp3-file", $mp3_file);
		if(isset($api_error)) $newnode->setAttribute("data-error", $api_error);
		
		return $dom->saveHTML();
	}


	/**
	 * Get proper shortcode attributes
	 * @since    1.0.0
	 */
	public function format_shortcode_attributes($atts){
		return shortcode_atts(
			array(
				'lang' => $this->options['default_language'],
				'speed' => $this->options['default_speed'],
				'google_language' => $this->options['google_language'],
				'google_gender' => $this->options['google_gender'],
				'google_speed' => $this->options['google_speed'],
				'google_voice' => $this->options['google_custom_voice'],
				'amazon_voice' => $this->options['amazon_voice'],
				'mode' => $this->options['mode'],
				'alt' => null,
				'mp3' => null,
				'block' => false
			),
			$atts
		);
	}


	/**
	 * Get the proper MP3 file
	 * @since    1.0.0
	 */
	public function get_mp3_file($text, $args = null) {

		if($args == null){
			$args = $this->format_shortcode_attributes($this->options);
		}

		/* Set alternative mp3 if option enable */
		if(isset($args['mp3'])){
			return $args['mp3'];
		}

		/* Get mp3 url if google TTS is enabled */
		if($args['mode'] == 'google' && isset($this->google_tts->enabled)){
			return $this->google_tts->get_google_mp3($text, $args['google_language'], $args['google_gender'], $args['google_speed'], $args['google_voice']);
		}

		/* Get mp3 url if Amazon Polly is enabled */
		if($args['mode'] == 'amazon' && isset($this->amazon_polly->enabled)){
			return $this->amazon_polly->get_mp3($text, $args['amazon_voice']);
		}

		return null;
	}

}
