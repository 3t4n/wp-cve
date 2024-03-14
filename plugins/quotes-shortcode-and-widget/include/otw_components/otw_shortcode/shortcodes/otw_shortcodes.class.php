<?php
class OTW_Shortcodes{
	
	/**
	 * array with labels
	 */
	public $labels = array();
	
	/**
	 * array with settings
	 */
	public $settings = array();
	
	/**
	 * mode
	 */
	public $mode = '';
	
	/**
	 * has custom options
	 */
	public $has_custom_options = false;
	
	/**
	 * has preview
	 */
	public $has_preview = true;
	
	/**
	 * dialog_text
	 */
	public $dialog_text = '';
	
	/**
	 * custom options label
	*/
	public $custom_options_label = '';
	
	/**
	 * Errors
	 * 
	 * @var  array
	 */
	public $errors = array();
	
	/**
	 * has errors
	 * 
	 * @var  boolen
	 */
	public $has_error = false;
	
	/**
	 * component url
	 * 
	 * @var  string
	 */
	public $component_url = '';
	
	/**
	 * component path
	 * 
	 * @var  string
	 */
	public $component_path = '';
	
	/**
	 * external libs
	 *
	 */
	public $external_libs = array();
	
	/**
	 * previews
	 *
	*/
	public $preview = 'iframe';
	
	/**
	 * is live preview requested
	*/
	public $is_live_preview = false;
	
	/**
	 *  Key of the shortcode
	*/
	public $shortcode_key = '';
	
	/**
	 *  Name of the shortcode
	*/
	public $shortcode_name = '';
	
	/**
	 *  Init in front
	*/
	public $init_in_front = false;
	
	/**
	 * google web fonts
	*/
	public $google_fonts = array(
		array('name' => "Abel", 'variant' => ''),
		array('name' => "Abril Fatface", 'variant' => ''),
		array('name' => "Aclonica", 'variant' => ''),
		array('name' => "Actor", 'variant' => ''),
		array('name' => "Adamina", 'variant' => ''),
		array('name' => "Aldrich", 'variant' => ''),
		array('name' => "Alice", 'variant' => ''),
		array('name' => "Alike Angular", 'variant' => ''),
		array('name' => "Alike", 'variant' => ''),
		array('name' => "Allan", 'variant' => ':bold'),
		array('name' => "Allerta Stencil", 'variant' => ''),
		array('name' => "Allerta", 'variant' => ''),
		array('name' => "Amaranth", 'variant' => ''),
		array('name' => "Amatic SC", 'variant' => ''),
		array('name' => "Andada", 'variant' => ''),
		array('name' => "Andika", 'variant' => ''),
		array('name' => "Annie Use Your Telescope", 'variant' => ''),
		array('name' => "Anonymous Pro", 'variant' => ':r,b,i,bi'),
		array('name' => "Antic", 'variant' => ''),
		array('name' => "Anton", 'variant' => ''),
		array('name' => "Arapey", 'variant' => ':r,i'),
		array('name' => "Architects Daughter", 'variant' => ''),
		array('name' => "Arimo", 'variant' => ':r,b,i,bi'),
		array('name' => "Artifika", 'variant' => ''),
		array('name' => "Arvo", 'variant' => ':r,b,i,bi'),
		array('name' => "Asset", 'variant' => ''),
		array('name' => "Astloch", 'variant' => ':b'),
		array('name' => "Atomic Age", 'variant' => ''),
		array('name' => "Aubrey", 'variant' => ''),
		array('name' => "Bangers", 'variant' => ''),
		array('name' => "Bentham", 'variant' => ''),
		array('name' => "Bevan", 'variant' => ''),
		array('name' => "Bigshot One", 'variant' => ''),
		array('name' => "Bitter", 'variant' => ''),
		array('name' => "Black Ops One", 'variant' => ''),
		array('name' => "Bowlby One SC", 'variant' => ''),
		array('name' => "Bowlby One", 'variant' => ''),
		array('name' => "Brawler", 'variant' => ''),
		array('name' => "Buda", 'variant' => ':light'),
		array('name' => "Butcherman Caps", 'variant' => ''),
		array('name' => "Cabin Condensed", 'variant' => ':r,b'),
		array('name' => "Cabin Sketch", 'variant' => ':r,b'),
		array('name' => "Cabin", 'variant' => ':400,400italic,700,700italic,'),
		array('name' => "Calligraffitti", 'variant' => ''),
		array('name' => "Candal", 'variant' => ''),
		array('name' => "Cantarell", 'variant' => ':r,b,i,bi'),
		array('name' => "Cardo", 'variant' => ''),
		array('name' => "Carme", 'variant' => ''),
		array('name' => "Carter One", 'variant' => ''),
		array('name' => "Caudex", 'variant' => ':r,b,i,bi'),
		array('name' => "Cedarville Cursive", 'variant' => ''),
		array('name' => "Changa One", 'variant' => ''),
		array('name' => "Cherry Cream Soda", 'variant' => ''),
		array('name' => "Chewy", 'variant' => ''),
		array('name' => "Chivo", 'variant' => ''),
		array('name' => "Coda", 'variant' => ''),
		array('name' => "Coda", 'variant' => ':800'),
		array('name' => "Comfortaa", 'variant' => ':r,b'),
		array('name' => "Coming Soon", 'variant' => ''),
		array('name' => "Contrail One", 'variant' => ''),
		array('name' => "Convergence", 'variant' => ''),
		array('name' => "Copse", 'variant' => ''),
		array('name' => "Corben", 'variant' => ''),
		array('name' => "Corben", 'variant' => ':b'),
		array('name' => "Cousine", 'variant' => ''),
		array('name' => "Coustard", 'variant' => ':r,b'),
		array('name' => "Covered By Your Grace", 'variant' => ''),
		array('name' => "Crafty Girls", 'variant' => ''),
		array('name' => "Creepster Caps", 'variant' => ''),
		array('name' => "Crimson Text", 'variant' => ''),
		array('name' => "Crushed", 'variant' => ''),
		array('name' => "Cuprum", 'variant' => ''),
		array('name' => "Damion", 'variant' => ''),
		array('name' => "Dancing Script", 'variant' => ''),
		array('name' => "Dawning of a New Day", 'variant' => ''),
		array('name' => "Days One", 'variant' => ''),
		array('name' => "Delius Swash Caps", 'variant' => ''),
		array('name' => "Delius Unicase", 'variant' => ''),
		array('name' => "Delius", 'variant' => ''),
		array('name' => "Didact Gothic", 'variant' => ''),
		array('name' => "Dorsa", 'variant' => ''),
		array('name' => "Droid Sans Mono", 'variant' => ''),
		array('name' => "Droid Sans", 'variant' => ':r,b'),
		array('name' => "Droid Serif", 'variant' => ':r,b,i,bi'),
		array('name' => "Eater Caps", 'variant' => ''),
		array('name' => "EB Garamond", 'variant' => ''),
		array('name' => "Expletus Sans", 'variant' => ':b'),
		array('name' => "Fanwood Text", 'variant' => ''),
		array('name' => "Federant", 'variant' => ''),
		array('name' => "Federo", 'variant' => ''),
		array('name' => "Fjord One", 'variant' => ''),
		array('name' => "Fontdiner Swanky", 'variant' => ''),
		array('name' => "Forum", 'variant' => ''),
		array('name' => "Francois One", 'variant' => ''),
		array('name' => "Gentium Book Basic", 'variant' => ''),
		array('name' => "Geo", 'variant' => ''),
		array('name' => "Geostar Fill", 'variant' => ''),
		array('name' => "Geostar", 'variant' => ''),
		array('name' => "Give You Glory", 'variant' => ''),
		array('name' => "Gloria Hallelujah", 'variant' => ''),
		array('name' => "Goblin One", 'variant' => ''),
		array('name' => "Gochi Hand", 'variant' => ''),
		array('name' => "Goudy Bookletter 1911", 'variant' => ''),
		array('name' => "Gravitas One", 'variant' => ''),
		array('name' => "Gruppo", 'variant' => ''),
		array('name' => "Hammersmith One", 'variant' => ''),
		array('name' => "Holtwood One SC", 'variant' => ''),
		array('name' => "Homemade Apple", 'variant' => ''),
		array('name' => "IM Fell DW Pica", 'variant' => ':r,i'),
		array('name' => "IM Fell English SC", 'variant' => ''),
		array('name' => "IM Fell English", 'variant' => ':r,i'),
		array('name' => "Inconsolata", 'variant' => ''),
		array('name' => "Indie Flower", 'variant' => ''),
		array('name' => "Irish Grover", 'variant' => ''),
		array('name' => "Irish Growler", 'variant' => ''),
		array('name' => "Istok Web", 'variant' => ':r,b,i,bi'),
		array('name' => "Jockey One", 'variant' => ''),
		array('name' => "Josefin Sans", 'variant' => ':400,400italic,700,700italic'),
		array('name' => "Josefin Slab", 'variant' => ':r,b,i,bi'),
		array('name' => "Judson", 'variant' => ':r,ri,b'),
		array('name' => "Julee", 'variant' => ''),
		array('name' => "Jura", 'variant' => ''),
		array('name' => "Just Another Hand", 'variant' => ''),
		array('name' => "Just Me Again Down Here", 'variant' => ''),
		array('name' => "Kameron", 'variant' => ':r,b'),
		array('name' => "Kelly Slab", 'variant' => ''),
		array('name' => "Kenia", 'variant' => ''),
		array('name' => "Kranky", 'variant' => ''),
		array('name' => "Kreon", 'variant' => ':r,b'),
		array('name' => "Kristi", 'variant' => ''),
		array('name' => "La Belle Aurore", 'variant' => ''),
		array('name' => "Lancelot", 'variant' => ''),
		array('name' => "Lato", 'variant' => ':400,700,400italic'),
		array('name' => "League Script", 'variant' => ''),
		array('name' => "Leckerli One", 'variant' => ''),
		array('name' => "Lekton", 'variant' => ''),
		array('name' => "Limelight", 'variant' => ''),
		array('name' => "Linden Hill", 'variant' => ''),
		array('name' => "Lobster Two", 'variant' => ':r,b,i,bi'),
		array('name' => "Lobster", 'variant' => ''),
		array('name' => "Lora", 'variant' => ''),
		array('name' => "Love Ya Like A Sister", 'variant' => ''),
		array('name' => "Loved by the King", 'variant' => ''),
		array('name' => "Luckiest Guy", 'variant' => ''),
		array('name' => "Maiden Orange", 'variant' => ''),
		array('name' => "Mako", 'variant' => ''),
		array('name' => "Marck Script", 'variant' => ''),
		array('name' => "Marvel", 'variant' => ':r,b,i,bi'),
		array('name' => "Mate SC", 'variant' => ''),
		array('name' => "Mate", 'variant' => ':r,i'),
		array('name' => "Maven Pro", 'variant' => ''),
		array('name' => "Meddon", 'variant' => ''),
		array('name' => "MedievalSharp", 'variant' => ''),
		array('name' => "Megrim", 'variant' => ''),
		array('name' => "Merienda One", 'variant' => ''),
		array('name' => "Merriweather", 'variant' => ''),
		array('name' => "Metrophobic", 'variant' => ''),
		array('name' => "Michroma", 'variant' => ''),
		array('name' => "Miltonian Tattoo", 'variant' => ''),
		array('name' => "Miltonian", 'variant' => ''),
		array('name' => "Modern Antiqua", 'variant' => ''),
		array('name' => "Molengo", 'variant' => ''),
		array('name' => "Monofett", 'variant' => ''),
		array('name' => "Monoton", 'variant' => ''),
		array('name' => "Montez", 'variant' => ''),
		array('name' => "Mountains of Christmas", 'variant' => ''),
		array('name' => "Muli", 'variant' => ''),
		array('name' => "Neucha", 'variant' => ''),
		array('name' => "Neuton", 'variant' => ''),
		array('name' => "News Cycle", 'variant' => ''),
		array('name' => "Nixie One", 'variant' => ''),
		array('name' => "Nobile", 'variant' => ':r,b,i,bi'),
		array('name' => "Nosifer Caps", 'variant' => ''),
		array('name' => "Nova Cut", 'variant' => ''),
		array('name' => "Nova Flat", 'variant' => ''),
		array('name' => "Nova Mono", 'variant' => ''),
		array('name' => "Nova Oval", 'variant' => ''),
		array('name' => "Nova Round", 'variant' => ''),
		array('name' => "Nova Script", 'variant' => ''),
		array('name' => "Nova Slim", 'variant' => ''),
		array('name' => "Numans", 'variant' => ''),
		array('name' => "Nunito", 'variant' => ''),
		array('name' => "OFL Sorts Mill Goudy TT", 'variant' => ':r,i'),
		array('name' => "Old Standard TT", 'variant' => ':r,b,i'),
		array('name' => "Open Sans Condensed", 'variant' => ':300,300italic'),
		array('name' => "Open Sans", 'variant' => ':r,i,b,bi'),
		array('name' => "Orbitron", 'variant' => ':r,b,i,bi'),
		array('name' => "Oswald", 'variant' => ''),
		array('name' => "Over the Rainbow", 'variant' => ''),
		array('name' => "Ovo", 'variant' => ''),
		array('name' => "Pacifico", 'variant' => ''),
		array('name' => "Passero One", 'variant' => ''),
		array('name' => "Patrick Hand", 'variant' => ''),
		array('name' => "Paytone One", 'variant' => ''),
		array('name' => "Permanent Marker", 'variant' => ''),
		array('name' => "Petrona", 'variant' => ''),
		array('name' => "Philosopher", 'variant' => ''),
		array('name' => "Pinyon Script", 'variant' => ''),
		array('name' => "Play", 'variant' => ':r,b'),
		array('name' => "Playfair Display", 'variant' => ''),
		array('name' => "Podkova", 'variant' => ''),
		array('name' => "Poller One", 'variant' => ''),
		array('name' => "Poly", 'variant' => ''),
		array('name' => "Pompiere", 'variant' => ''),
		array('name' => "Prata", 'variant' => ''),
		array('name' => "Prociono", 'variant' => ''),
		array('name' => "PT Sans Caption", 'variant' => ':r,b'),
		array('name' => "PT Sans Narrow", 'variant' => ':r,b'),
		array('name' => "PT Sans", 'variant' => ':r,b,i,bi'),
		array('name' => "PT Serif Caption", 'variant' => ':r,i'),
		array('name' => "PT Serif", 'variant' => ':r,b,i,bi'),
		array('name' => "Puritan", 'variant' => ':r,b,i,bi'),
		array('name' => "Quattrocento Sans", 'variant' => ''),
		array('name' => "Quattrocento", 'variant' => ''),
		array('name' => "Questrial", 'variant' => ''),
		array('name' => "Quicksand", 'variant' => ''),
		array('name' => "Radley", 'variant' => ''),
		array('name' => "Raleway", 'variant' => ':100'),
		array('name' => "Rametto One", 'variant' => ''),
		array('name' => "Rancho", 'variant' => ''),
		array('name' => "Rationale", 'variant' => ''),
		array('name' => "Redressed", 'variant' => ''),
		array('name' => "Reenie Beanie", 'variant' => ''),
		array('name' => "Rochester", 'variant' => ''),
		array('name' => "Rock Salt", 'variant' => ''),
		array('name' => "Rokkitt", 'variant' => ':400,700'),
		array('name' => "Rosario", 'variant' => ''),
		array('name' => "Ruslan Display", 'variant' => ''),
		array('name' => "Salsa", 'variant' => ''),
		array('name' => "Sancreek", 'variant' => ''),
		array('name' => "Sansita One", 'variant' => ''),
		array('name' => "Satisfy", 'variant' => ''),
		array('name' => "Schoolbell", 'variant' => ''),
		array('name' => "Shadows Into Light", 'variant' => ''),
		array('name' => "Shanti", 'variant' => ''),
		array('name' => "Short Stack", 'variant' => ''),
		array('name' => "Sigmar One", 'variant' => ''),
		array('name' => "Six Caps", 'variant' => ''),
		array('name' => "Slackey", 'variant' => ''),
		array('name' => "Smokum", 'variant' => ''),
		array('name' => "Smythe", 'variant' => ''),
		array('name' => "Sniglet", 'variant' => ':800'),
		array('name' => "Snippet", 'variant' => ''),
		array('name' => "Sorts Mill Goudy", 'variant' => ''),
		array('name' => "Special Elite", 'variant' => ''),
		array('name' => "Spinnaker", 'variant' => ''),
		array('name' => "Stardos Stencil", 'variant' => ''),
		array('name' => "Sue Ellen Francisco", 'variant' => ''),
		array('name' => "Sunshiney", 'variant' => ''),
		array('name' => "Supermercado One", 'variant' => ''),
		array('name' => "Swanky and Moo Moo", 'variant' => ''),
		array('name' => "Syncopate", 'variant' => ''),
		array('name' => "Tangerine", 'variant' => ':r,b'),
		array('name' => "Tenor Sans", 'variant' => ''),
		array('name' => "Terminal Dosis Light", 'variant' => ''),
		array('name' => "Terminal Dosis", 'variant' => ''),
		array('name' => "The Girl Next Door", 'variant' => ''),
		array('name' => "Tienne", 'variant' => ''),
		array('name' => "Tinos", 'variant' => ':r,b,i,bi'),
		array('name' => "Tulpen One", 'variant' => ''),
		array('name' => "Ubuntu Condensed", 'variant' => ''),
		array('name' => "Ubuntu Mono", 'variant' => ''),
		array('name' => "Ubuntu", 'variant' => ':r,b,i,bi'),
		array('name' => "Ultra", 'variant' => ''),
		array('name' => "UnifrakturCook", 'variant' => ':bold'),
		array('name' => "UnifrakturMaguntia", 'variant' => ''),
		array('name' => "Unkempt", 'variant' => ''),
		array('name' => "Unna", 'variant' => ''),
		array('name' => "Varela Round", 'variant' => ''),
		array('name' => "Varela", 'variant' => ''),
		array('name' => "Vast Shadow", 'variant' => ''),
		array('name' => "Vibur", 'variant' => ''),
		array('name' => "Vidaloka", 'variant' => ''),
		array('name' => "Volkhov", 'variant' => ''),
		array('name' => "Vollkorn", 'variant' => ':r,b'),
		array('name' => "Voltaire", 'variant' => ''),
		array('name' => "VT323", 'variant' => ''),
		array('name' => "Waiting for the Sunrise", 'variant' => ''),
		array('name' => "Wallpoet", 'variant' => ''),
		array('name' => "Walter Turncoat", 'variant' => ''),
		array('name' => "Wire One", 'variant' => ''),
		array('name' => "Yanone Kaffeesatz", 'variant' => ':r,b'),
		array('name' => "Yellowtail", 'variant' => ''),
		array('name' => "Yeseva One", 'variant' => ''),
		array('name' => "Zeyada", 'variant' => '')
	);
	
	/**
	 * construct
	 */
	public function __construct() {
		$this->shortcode_name = strtolower(get_class($this));
	}
	
	/**
	 *  Get Label
	*/
	public function get_label( $label_key ){
		
		if( isset( $this->labels[ $label_key ] ) ){
		
			return $this->labels[ $label_key ];
		}
		
		if( $this->mode == 'dev' ){
			return strtoupper( $label_key );
		}
		
		return $label_key;
	}
	
	/**
	 * Build shortcode editor
	 */
	public function build_shortcode_editor_options(){
		
		return $this->get_label( 'Invalid shortcode' );
		
	}
	
	/**
	 * Build button type add/edit form
	 */
	public static function build_admin_form($button_type){

		return 'Invalid data';

	}
	
	/**
	 * apply predefined settings
	 */
	public function apply_settings(){
		
	}
	
	/**
	 * Build shortcode editor
	 */
	public function build_shortcode_editor_custom_options(){
		
		return $this->get_label( 'Invalid shortcode' );
		
	}
	
	/**
	 * Build shortcode
	 */
	public function build_shortcode_code( $attributes ){
		
		echo $this->get_label( 'Invalid shortcode' );
		
	}
	
	/**
	 * Display shortcode
	 */
	public function display_shortcode( $attributes, $content ){
		return $this->get_label( 'Invalid shortcode' );
	}
	
	/**
	 *  format attribute
	 *  
	 *  @param string name
	 *
	 *  @param string key
	 *
	 *  @param array with attributes
	 *
	 *  @param boolean create attribute if no value
	 *
	 *  @return string
	 */
	public function format_attribute($attribute_name, $attribute_key, $attributes, $show_empty = false, $add_space = '', $htmlentities = false) {
		
		if (isset($attributes[$attribute_key]) && is_array($attributes[$attribute_key])) {
			if ($attribute_name) {
				return ' ' . $attribute_name . '="' . $this->clean_attribute(implode( ',', $attributes[$attribute_key]), $htmlentities) . '"';
			} else {
				if (strlen($add_space)) {
					return ' ' . $this->clean_attribute(implode( ',', $attributes[$attribute_key] ), $htmlentities);
				}
				return $this->clean_attribute($attributes[$attribute_key], $htmlentities);
			}
		}elseif (isset($attributes[$attribute_key]) && strlen(trim($attributes[$attribute_key]))) {
			if ($attribute_name) {
				return ' ' . $attribute_name . '="' . $this->clean_attribute($attributes[$attribute_key], $htmlentities) . '"';
			} else {
				if (strlen($add_space)) {
					return ' ' . $this->clean_attribute($attributes[$attribute_key], $htmlentities);
				}
				return $this->clean_attribute($attributes[$attribute_key], $htmlentities);
			}
		} elseif ($show_empty) {
			if ($attribute_name) {
				return ' ' . $attribute_name . '=""';
			}
		}
		return '';
	}
	
	/** append attribute to existing list with attributes
	 *
	 *  @param string
	 *  @param string
	 *  @return string
	 */
	public function append_attribute( $append_to, $attribute ){
		
		$result = $append_to;
		
		if( strlen( $result ) ){
			$result .= ' '.$attribute;
		}else{
			$result .= $attribute;
		}
		return $result;
	}
	
	/**
	 *  add error
	 */
	public function add_error( $error_string ){
		
		$this->errors[] = $error_string;
		$this->has_error = true;
	}
	
	/**
	 * Return shortcode attributes
	 */
	public function get_shortcode_attributes( $attributes ){
		return array();
	}
	
	/**
	 * Check if is google font
	 */
	public function is_google_font( $font_name )
	{
		foreach( $this->google_fonts as $g_font )
		{
			if( $g_font['name'] == $font_name )
			{
				return $g_font;
			}
		}
		return false;
	}
	
	/**
	 * Include google font
	 */
	public function include_google_font( $font_info )
	{
		$output = '';
		
		$font = urlencode( $font_info['name'].$font_info['variant'] );
		
		$output .= "\n<!-- Google Webfonts -->\n";
		
		$output .= '<link href="//fonts.googleapis.com/css?family=' . $font .'" rel="stylesheet" type="text/css" />'."\n\n";
		
		$output = str_replace( '|"','"',$output);
		
		return $output;
	}
	
	/**
	 * Format the shortcode output
	*/
	public function format_shortcode_output($content) {
		$html = '';
		//process all shortcodes inside our code
		ob_start();
		$html .= do_shortcode($content);
		$html .= ob_get_contents();
		ob_end_clean();
		
		return $html;
	}
	
	/**
	 * Format font face
	 */
	public function format_font_face( $font_name ){
		
		if( $this->is_google_font( $font_name ) ){
			
			switch( $font_name ){
				
				case 'PT Sans':
						$font_name = "'" . $font_name . "', Helvetica Neue, Helvetica, Arial, sans-serif";
					break;
				case 'Droid Serif':
						$font_name = "'" . $font_name . "', Times New Roman, Times, Serif";
					break;
				default:
						$font_name = "'" . $font_name . "', arial, serif";
					break;
			}
		}
		return $font_name;
	}
	
	public function clean_attribute( $attribute_value, $htmlentities ){
		
		if( $htmlentities ){
			$attribute_value = otw_htmlentities( $attribute_value );
		}
		return $attribute_value;
	}
	
	public function add_external_lib($type, $name, $path, $int, $order, $deps = false) {
		$this->external_libs[] = array('type' => $type, 'name' => $name, 'path' => $path, 'int' => $int, 'order' => $order, 'deps' => $deps);
	}
	
	public function register_external_libs(){
	
	}


    /**
     * Set dropdown settings for "Box Animations"
     * @return \Otw_Shortcode_Info_List
     */
    protected function _applyBoxAnimations() {
        $this->settings['box_animations'] = $this->getAnimations();
        $this->settings['default_box_animations'] = '';
        return $this;
    }

    /**
     * Set dropdown settings for "Box Shadow"
     * @return \Otw_Shortcode_Info_List
     */
    protected function _applyBoxShadow() {
        $this->settings['box_shadow'] = $this->getShadows();
        $this->settings['default_box_shadow'] = '';
        return $this;
    }

    /**
     * Set dropdown settings for "Box Rounded Courners"
     * @return \Otw_Shortcode_Info_List
     */
    protected function _applyBoxRoundedCourners() {
        $this->settings['box_rounded_corners'] = $this->getRounds();
        $this->settings['default_box_rounded_corners'] = '';
        return $this;
    }

    /**
     * Set dropdown settings for "Box Border Colors"
     * @return \Otw_Shortcode_Info_List
     */
    protected function _applyBoxBorderColors() {
        $this->settings['box_brd_color'] = $this->getBorderColors();
        $this->settings['default_box_brd_color'] = '';
        return $this;
    }

    /**
     * Set dropdown settings for "Box Border Style"
     * @return \Otw_Shortcode_Info_List
     */
    protected function _applyBoxBorderStyle() {
        $this->settings['box_brd_style'] = $this->getBorderStyle();
        $this->settings['default_box_brd_style'] = 'solid';
        return $this;
    }

    /**
     * Set dropdown settings for "Box Border Width"
     * @return \Otw_Shortcode_Info_List
     */
    protected function _applyBoxBorderWidth() {
        $this->settings['box_brd_width'] = $this->getBorderWidths();
        $this->settings['default_box_brd_width'] = '1';
        return $this;
    }

    /**
     * Set dropdown settings for "Box Border Type"
     * @return \Otw_Shortcode_Info_List
     */
    protected function _applyBoxBorderType() {
        $this->settings['box_brd_type'] = $this->getBorderSideTypes();
        $this->settings['default_box_brd_type'] = '';
        return $this;
    }

    /**
     * Set dropdown settings for "Box Background Pattern"
     * @return \Otw_Shortcode_Info_List
     */
    protected function _applyBoxBackgroundPattern() {
        $this->settings['box_bgr_pattern'] = $this->getPatterns();
        $this->settings['default_box_bgr_pattern'] = '';
        return $this;
    }

    /**
     * Set dropdown settings for "Icon Types"
     * @return \Otw_Shortcode_Info_List
     */
    protected function _applyIconTypes() {
        $this->settings['icon_types'] = $this->getIcons();
        $this->settings['default_icon_types'] = '';
        $this->settings['default_icon_type'] = '';
        return $this;
    }

    /**
     * Set dropdown settings for "Box Type"
     * @return \Otw_Shortcode_Info_List
     */
    protected function _applyBoxType() {
        $this->settings['box_type'] = array(
            '' => $this->get_label('Regular(default)'),
            'otw-b-relative-box' => $this->get_label('Relative'),
        );
        $this->settings['default_box_type'] = 'regular';
        return $this;
    }

    /**
     * Set dropdown settings for "Icon Color Borders"
     * @return \Otw_Shortcode_Info_List
     */
    protected function _applyIconColorBorders() {
        $this->settings['icon_brd_color'] = $this->getBorderColors();
        $this->settings['default_icon_brd_color'] = '';
        return $this;
    }

    /**
     * Set dropdown settings for "Icon Border Width"
     * @return \Otw_Shortcode_Info_List
     */
    protected function _applyIconBorderWidth() {
        $this->settings['icon_brd_width'] = $this->getBorderWidths();
        $this->settings['default_icon_brd_width'] = '1';
        return $this;
    }

    /**
     * Set dropdown settings for "Icon Border Style"
     * @return \Otw_Shortcode_Info_List
     */
    protected function _applyIconBorderStyle() {
        $this->settings['icon_brd_style'] = $this->getBorderStyle();
        $this->settings['default_icon_brd_style'] = 'solid';
        return $this;
    }

    /**
     * Set dropdown settings for "Icon Background Colors"
     * @return \Otw_Shortcode_Info_List
     */
    protected function _applyIconBackgroundColors() {
        $this->settings['icon_bgr_colors'] = $this->getBackgrounds();
        $this->settings['default_icon_bgr_colors'] = '';
        return $this;
    }

    /**
     * Set dropdown settings for "Icon Colors"
     * @return \Otw_Shortcode_Info_List
     */
    protected function _applyIconColors() {
        $this->settings['icon_colors'] = $this->getColors();
        $this->settings['default_icon_colors'] = '';
        return $this;
    }

    /**
     * Set dropdown settings for "Icon Animations"
     * @return \Otw_Shortcode_Info_List
     */
    protected function _applyIconAnimations() {
        $this->settings['icon_animations'] = $this->getAnimations();
        $this->settings['default_icon_animations'] = '';
        return $this;
    }

    /**
     * Set dropdown settings for "Icon Sizes"
     * @return \Otw_Shortcode_Info_List
     */
    protected function _applyIconSizes() {
        $this->settings['icon_sizes'] = array(
            'otw-b-icon-large' => $this->get_label('Large(Default)'),
            'otw-b-icon-small' => $this->get_label('Small'),
            'otw-b-icon-giant' => $this->get_label('Giant')
        );
        $this->settings['default_icon_sizes'] = '';
        return $this;
    }

    /**
     * Set dropdown settings for "Button Backgrounds"
     * @return \Otw_Shortcode_Info_List
     */
    protected function _applyButtonBackgrounds() {
        $this->settings['content_button_background'] = $this->getBackgrounds();
        $this->settings['default_content_button_background'] = '';
        return $this;
    }

    /**
     * Set dropdown settings for "Content Link Color"
     * @return \Otw_Shortcode_Info_List
     */
    protected function _applyContentLinkColor() {
        $this->settings['content_link_color'] = $this->getColors();
        $this->settings['default_content_link_color'] = '';
        return $this;
    }

    /**
     * Set dropdown settings for "Call Actions"
     * @return \Otw_Shortcode_Info_List
     */
    protected function _applyCallActions() {
        $this->settings['call_action'] = array(
            'none' => $this->get_label('None'),
            '' => $this->get_label('Text Link(default)'),
            'otw-button ' => $this->get_label('Button')
        );
        $this->settings['default_call_action'] = '';
        return $this;
    }

    /**
     * Set dropdown settings for ""
     * Apply settings for dropdown of box backgroudns scope of data
     * @return \Otw_Shortcode_Info_List
     */
    protected function _applyBoxBackgrounds() {
        $this->settings['box_bgr'] = $this->getBackgrounds();
        $this->settings['default_box_bgr'] = '';
        return $this;
    }

    protected function _applyIconBoxRounds() {
        $this->settings['icon_brd_round'] = $this->getRounds();
        $this->settings['default_icon_brd_round'] = '';
        return $this;
    }

    /**
     * Return all border colors
     * @return array
     */
    public function getBorderColors() {
        $brd_colors = array(
            '' => $this->get_label('Default'),
            'otw-b-grey-bd' => $this->get_label('Grey Flat'),
            'otw-b-white-bd' => $this->get_label('White Flat'),
            'otw-b-blue-flat-bd' => $this->get_label('Blue Flat'),
            'otw-b-bluesky-flat-bd' => $this->get_label('Bluesky  Flat'),
            'otw-b-darkblue-flat-bd' => $this->get_label('Darkblue Flat'),
            'otw-b-purple-flat-bd' => $this->get_label('Purple Flat'),
            'otw-b-magenta-flat-bd' => $this->get_label('Magenta Flat'),
            'otw-b-pink-flat-bd' => $this->get_label('Pink Flat'),
            'otw-b-yellow-flat-bd' => $this->get_label('Yellow Flat'),
            'otw-b-green-flat-bd' => $this->get_label('Green Flat'),
            'otw-b-greenyellow-flat-bd' => $this->get_label('Greenyellow Flat'),
            'otw-b-orange-flat-bd' => $this->get_label('Orange Flat'),
            'otw-b-red-flat-bd' => $this->get_label('Red Flat')
        );
        return $brd_colors;
    }

    /**
     * Get All allowed animations
     * @return array
     */
    public function getAnimations() {
        return array(
            '' => $this->get_label('None(Default)'),
            'rotate' => $this->get_label('Rotate'),
            'bounce' => $this->get_label('Bounce'),
            'wobble' => $this->get_label('Wobble'),
            'rubberBand' => $this->get_label('RubberBand'),
            'swing' => $this->get_label('Swing'),
            'tada' => $this->get_label('Tada'),
            'rubberBand' => $this->get_label('RubberBand')
        );
    }

    /**
     * Get text colors types
     * @return array
     */
    public function getColors() {
        return array(
            '' => $this->get_label('Default'),
            'otw-b-white-text' => $this->get_label('White Flat'),
            'otw-b-blue-flat-text' => $this->get_label('Blue Flat'),
            'otw-b-bluesky-flat-text' => $this->get_label('Bluesky Flat'),
            'otw-b-darkblue-flat-text' => $this->get_label('Darkblue Flat'),
            'otw-b-purple-flat-text' => $this->get_label('Purple Flat'),
            'otw-b-magenta-flat-text' => $this->get_label('Magenta Flat'),
            'otw-b-pink-flat-text' => $this->get_label('Pink Flat'),
            'otw-b-yellow-flat-text' => $this->get_label('Yellow Flat'),
            'otw-b-green-flat-text' => $this->get_label('Green Flat'),
            'otw-b-greenyellow-flat-text' => $this->get_label('Greenyellow Flat'),
            'otw-b-orange-flat-text' => $this->get_label('Orange Flat'),
            'otw-b-red-flat-text' => $this->get_label('Red Flat')
        );
    }

    /**
     * Get Backgrounds
     * @return array
     */
    public function getBackgrounds() {
        return array(
            '' => $this->get_label('Default'),
            'otw-b-white-bgr' => $this->get_label('White Flat'),
            'otw-b-blue-flat-bgr' => $this->get_label('Blue Flat'),
            'otw-b-bluesky-flat-bgr' => $this->get_label('Bluesky Flat'),
            'otw-b-darkblue-flat-bgr' => $this->get_label('Darkblue Flat'),
            'otw-b-purple-flat-bgr' => $this->get_label('Purple Flat'),
            'otw-b-magenta-flat-bgr' => $this->get_label('Magenta Flat'),
            'otw-b-pink-flat-bgr' => $this->get_label('Pink Flat'),
            'otw-b-yellow-flat-bgr' => $this->get_label('Yellow Flat'),
            'otw-b-green-flat-bgr' => $this->get_label('Green Flat'),
            'otw-b-greenyellow-flat-bgr' => $this->get_label('Greenyellow Flat'),
            'otw-b-orange-flat-bgr' => $this->get_label('Orange Flat'),
            'otw-b-red-flat-bgr' => $this->get_label('Red Flat')
        );
    }

    /**
     * Get all plugin floats
     * @return array
     */
    public function getFloats() {
        return array(
            '' => $this->get_label('Top (default)'),
            'otw-b-float-left' => $this->get_label('Left'),
            'otw-b-float-right' => $this->get_label('Right')
        );
    }

    /**
     * Get all plugin icons
     * @return array
     */
    public function getIcons() {
        return array(
            '' => $this->get_label('None (Default)'),
            'custom' => $this->get_label('Enter Custom Icon Text'),
            'general foundicon-settings' => $this->get_label('Settings'),
            'general foundicon-heart' => $this->get_label('Heart'),
            'general foundicon-star' => $this->get_label('Star'),
            'general foundicon-plus' => $this->get_label('Plus'),
            'general foundicon-minus' => $this->get_label('Minus'),
            'general foundicon-checkmark' => $this->get_label('Checkmark'),
            'general foundicon-remove' => $this->get_label('Remove'),
            'general foundicon-mail' => $this->get_label('Mail'),
            'general foundicon-calendar' => $this->get_label('Calendar'),
            'general foundicon-page' => $this->get_label('Page'),
            'general foundicon-tools' => $this->get_label('Tools'),
            'general foundicon-globe' => $this->get_label('Globe'),
            'general foundicon-cloud' => $this->get_label('Cloud'),
            'general foundicon-error' => $this->get_label('Error'),
            'general foundicon-right-arrow' => $this->get_label('Right arrow'),
            'general foundicon-left-arrow' => $this->get_label('Left arrow'),
            'general foundicon-up-arrow' => $this->get_label('Up arrow'),
            'general foundicon-down-arrow' => $this->get_label('Down arrow'),
            'general foundicon-trash' => $this->get_label('Trash'),
            'general foundicon-add-doc' => $this->get_label('Add Doc'),
            'general foundicon-edit' => $this->get_label('Edit'),
            'general foundicon-lock' => $this->get_label('Lock'),
            'general foundicon-unlock' => $this->get_label('Unlock'),
            'general foundicon-refresh' => $this->get_label('Refresh'),
            'general foundicon-paper-clip' => $this->get_label('Paper clip'),
            'general foundicon-video' => $this->get_label('Video'),
            'general foundicon-photo' => $this->get_label('Photo'),
            'general foundicon-graph' => $this->get_label('Graph'),
            'general foundicon-idea' => $this->get_label('Idea'),
            'general foundicon-mic' => $this->get_label('Mic'),
            'general foundicon-cart' => $this->get_label('Cart'),
            'general foundicon-address-book' => $this->get_label('Address book'),
            'general foundicon-compass' => $this->get_label('Compass'),
            'general foundicon-flag' => $this->get_label('Flag'),
            'general foundicon-location' => $this->get_label('Location'),
            'general foundicon-clock' => $this->get_label('Clock'),
            'general foundicon-folder' => $this->get_label('Folder'),
            'general foundicon-inbox' => $this->get_label('Inbox'),
            'general foundicon-website' => $this->get_label('Website'),
            'general foundicon-smiley' => $this->get_label('Smiley'),
            'general foundicon-search' => $this->get_label('Search'),
            'general foundicon-phone' => $this->get_label('Phone'),
            'social foundicon-thumb-up' => $this->get_label('Thumb up'),
            'social foundicon-thumb-down' => $this->get_label('Thumb down'),
            'social foundicon-rss' => $this->get_label('Rss'),
            'social foundicon-facebook' => $this->get_label('Facebook'),
            'social foundicon-twitter' => $this->get_label('Twitter'),
            'social foundicon-pinterest' => $this->get_label('Pinterest'),
            'social foundicon-github' => $this->get_label('Github'),
            'social foundicon-path' => $this->get_label('Path'),
            'social foundicon-linkedin' => $this->get_label('LinkedIn'),
            'social foundicon-dribbble' => $this->get_label('Dribbble'),
            'social foundicon-stumble-upon' => $this->get_label('Stumble upon'),
            'social foundicon-behance' => $this->get_label('Behance'),
            'social foundicon-reddit' => $this->get_label('Reddit'),
            'social foundicon-google-plus' => $this->get_label('Google plus'),
            'social foundicon-youtube' => $this->get_label('Youtube'),
            'social foundicon-vimeo' => $this->get_label('Vimeo'),
            'social foundicon-clickr' => $this->get_label('Clickr'),
            'social foundicon-slideshare' => $this->get_label('Slideshare'),
            'social foundicon-picassa' => $this->get_label('Picassa'),
            'social foundicon-skype' => $this->get_label('Skype'),
            'social foundicon-instagram' => $this->get_label('instagram'),
            'social foundicon-foursquare' => $this->get_label('Foursquare'),
            'social foundicon-delicious' => $this->get_label('Delicious'),
            'social foundicon-chat' => $this->get_label('Chat'),
            'social foundicon-torso' => $this->get_label('Torso'),
            'social foundicon-tumblr' => $this->get_label('Tumblr'),
            'social foundicon-video-chat' => $this->get_label('Video chat'),
            'social foundicon-digg' => $this->get_label('Digg'),
            'social foundicon-wordpress' => $this->get_label('Wordpress')
        );
    }

    /**
     * Get All patterns
     * @return array
     */
    function getPatterns() {
        return array(
            '' => $this->get_label('None'),
            'pattern-b pattern-b-1' => $this->get_label('Pattern-b-1'),
            'pattern-b pattern-b-2' => $this->get_label('Pattern-b-2'),
            'pattern-b pattern-b-3' => $this->get_label('Pattern-b-3'),
            'pattern-b pattern-b-4' => $this->get_label('Pattern-b-4'),
            'pattern-b pattern-b-5' => $this->get_label('Pattern-b-5'),
            'pattern-b pattern-b-6' => $this->get_label('Pattern-b-6'),
            'pattern-b pattern-b-7' => $this->get_label('Pattern-b-7'),
            'pattern-b pattern-b-8' => $this->get_label('Pattern-b-8'),
            'pattern-b pattern-b-9' => $this->get_label('Pattern-b-9'),
            'pattern-b pattern-b-10' => $this->get_label('Pattern-b-10')
        );
    }

    /**
     * Get general shadows
     * @return array
     */
    function getShadows() {
        return array(
            '' => $this->get_label('None(default)'),
            'shadow-b-small' => $this->get_label('Small'),
            'shadow-b-medium' => $this->get_label('Medium'),
            'shadow-b-large' => $this->get_label('Large'),
            'shadow-b-giant' => $this->get_label('Giant')
        );
    }

    /**
     * Return all rounded classes
     * @return array
     */
    function getRounds() {
        return array(
            '' => $this->get_label('None(default)'),
            'otw-b-rounded-small' => $this->get_label('Small'),
            'otw-b-rounded-medium' => $this->get_label('Medium'),
            'otw-b-rounded-large' => $this->get_label('Large'),
            'otw-b-rounded-circle' => $this->get_label('Circle')
        );
    }

    /**
     * Get general border styles
     * @return array
     */
    function getBorderStyle() {
        return array(
            '' => $this->get_label('None(default)'),
            'otw-b-bd-solid' => $this->get_label('Solid'),
            'otw-b-bd-dashed' => $this->get_label('Dashed'),
            'otw-b-bd-dotted' => $this->get_label('Dotted'),
        );
    }

    /**
     * Get All borders Widths
     * @return array
     */
    function getBorderWidths() {
        return array(
            '' => $this->get_label('None(Default)'),
            'otw-b-bd-1px' => $this->get_label('1px'),
            'otw-b-bd-2px' => $this->get_label('2px'),
            'otw-b-bd-3px' => $this->get_label('3px'),
        );
    }

    /**
     * Get general border side types
     * @return array
     */
    function getBorderSideTypes() {
        return array(
            '' => $this->get_label('None(Default)'),
             'otw-b-bd-all' => $this->get_label('All Sides'),
            'otw-b-bd-left-right' => $this->get_label('Left & Right'),
            'otw-b-bd-top-bottom' => $this->get_label('Top & Bottom')
        );
    }

    /**
     * Get general border side types
     * @return array
     */
    function getGeneralScales() {
        return array(
            '' => $this->get_label('None(Default 100%)'),
            'otw-b-scale10' => $this->get_label('Scale 10%'),
            'otw-b-scale20' => $this->get_label('Scale 20%'),
            'otw-b-scale30' => $this->get_label('Scale 30%'),
            'otw-b-scale40' => $this->get_label('Scale 40%'),
            'otw-b-scale50' => $this->get_label('Scale 50%'),
            'otw-b-scale60' => $this->get_label('Scale 60%'),
            'otw-b-scale70' => $this->get_label('Scale 70%'),
            'otw-b-scale80' => $this->get_label('Scale 80%'),
            'otw-b-scale90' => $this->get_label('Scale 90%'),
        );
    }


    /**
     * 
     * Short creation of text input field
     * 
     * @param string $element_name set the general name for ID 
     * @param string $label set the label for form element
     * @param string $description set the italic description text
     * @param array $source array with all stored info
     * @return string
     */
    protected function _generateText($element_name, $label, $description, $source) {
        return OTW_Form::text_input(array('id' => 'otw-shortcode-element-' . $element_name, 'label' => $this->get_label($label), 'description' => $this->get_label($description), 'parse' => $source));
    }

    /**
     * 
     * Short creation of text area field
     * 
     * @param string $element_name set the general name for ID 
     * @param string $label set the label for form element
     * @param string $description set the italic description text
     * @param array $source array with all stored info
     * @return string
     */
    protected function _generateTextArea($element_name, $label, $description, $source) {
        return OTW_Form::text_area(array('id' => 'otw-shortcode-element-' . $element_name, 'label' => $this->get_label($label), 'description' => $this->get_label($description), 'parse' => $source));
    }

    /**
     * Short creation for dropdown
     * @param string $element_name set the from ID name
     * @param string $label set the element form label
     * @param string $description short description for each form element
     * @param string $source stored info
     * @param string $global_options_key string key for global settings array
     * @param string $default_value_key string for default value key into global settings array
     * @param boolean $data_reload boolean var for ajax relaoad of data
     * @return string
     */
    protected function _generateSelect($element_name, $label, $description, $source, $global_options_key = false, $default_value_key = false, $data_reload = false) {
        if (!$global_options_key || !$default_value_key) {
            throw new Exception('Missing array keys for general settings ARRAY in class: ' . get_called_class());
        }
        return OTW_Form::select(array('id' => 'otw-shortcode-element-' . $element_name, 'label' => $this->get_label($label), 'description' => $this->get_label($description), 'parse' => $source, 'options' => $this->settings[$global_options_key], 'value' => $this->settings[$default_value_key], 'data-reload' => (bool) $data_reload));
    }

    /**
     * Generate uploader file 
     * @param string $element_name set the from ID name
     * @param string $label set the element form label
     * @param string $description short description for each form element
     * @param string $source stored info
     * @return array
     */
    protected function _generateUploader($element_name, $label, $description, $source) {
        return OTW_Form::uploader(array('id' => 'otw-shortcode-element-' . $element_name, 'label' => $this->get_label($label), 'description' => $this->get_label($description), 'parse' => $source));
    }

    /**
     * 
     * Short creation of text area field
     * 
     * @param string $element_name set the general name for ID 
     * @param string $label set the label for form element
     * @param string $description set the italic description text
     * @param array $source array with all stored info
     * @return string
     */
    protected function _generatePicker($element_name, $label, $description, $source) {
        return OTW_Form::color_picker(array('id' => 'otw-shortcode-element-' . $element_name, 'label' => $this->get_label($label), 'description' => $this->get_label($description), 'parse' => $source));
    }

	public function _get_category_options(){
		
		$args = array();
		$args['type']            = 'post';
		$args['hide_empty']      = 0;
		$args['number']          = 0;
		
		if( otw_get( 'otw_options_ids', false ) && strlen( otw_get( 'otw_options_ids', '' ) ) && preg_match( "/^([0-9]+,)*[0-9]+\$|^\$/i", otw_get( 'otw_options_ids', '' ) ) ){
			$args['include'] = explode( ',', otw_get( 'otw_options_ids', '' ) );
		}
		if( otw_get( 'otw_search_term', false ) && strlen( otw_get( 'otw_search_term', '' ) ) ){
			$args['search'] = urldecode( otw_get( 'otw_search_term', '' ) );
		}
		if( otw_get( 'otw_options_limit', false ) && strlen( otw_get( 'otw_options_limit', '' ) ) && preg_match( "/^([0-9]+)$/i", otw_get( 'otw_options_limit', '' ) ) ){
			$args['number'] = otw_get( 'otw_options_limit', '' );
		}
		$all_items = get_categories( $args );
		
		$options  = array();
		$options['results'] = array();
		
		if( is_array( $all_items ) && count( $all_items ) ){
			foreach( $all_items as $item ){
				$o_key = count( $options['results'] );
				$options['results'][ $o_key ] = array();
				$options['results'][ $o_key ]['id'] = $item->term_id;
				$options['results'][ $o_key ]['text'] = $item->name;
			}
		}
		echo json_encode( $options );
		die;
	}
	
	public function _get_author_options(){
		
		$args = array();
		$args['hide_empty']      = 0;
		$args['number']          = 0;
		
		if( otw_get( 'otw_options_ids', false ) && strlen( otw_get( 'otw_options_ids', '' ) ) && preg_match( "/^([0-9]+,)*[0-9]+\$|^\$/i", otw_get( 'otw_options_ids', '' ) ) ){
			$args['include'] = explode( ',', otw_get( 'otw_options_ids', '' ) );
		}
		if( otw_get( 'otw_search_term', false ) && strlen( otw_get( 'otw_search_term', '' ) ) ){
			$args['search'] = '*'.urldecode( otw_get( 'otw_search_term', '' ) ).'*';
		}
		
		if( otw_get( 'otw_options_limit', false ) && strlen( otw_get( 'otw_options_limit', '' ) ) && preg_match( "/^([0-9]+)$/i", otw_get( 'otw_options_limit', '' ) ) ){
			$args['number'] = otw_get( 'otw_options_limit', '' );
		}
		
		$all_items = get_users( $args );
		
		$options  = array();
		$options['results'] = array();
		
		if( is_array( $all_items ) && count( $all_items ) ){
			foreach( $all_items as $item ){
				$o_key = count( $options['results'] );
				$options['results'][ $o_key ] = array();
				$options['results'][ $o_key ]['id'] = $item->ID;
				$options['results'][ $o_key ]['text'] = $item->user_login;
			}
		}
		echo json_encode( $options );
		die;
	}
	
	public function _get_tag_options(){
		
		$args = array();
		$args['hide_empty']      = 0;
		$args['number']          = 0;
		
		if( otw_get( 'otw_options_ids', false ) && strlen( otw_get( 'otw_options_ids', '' ) ) && preg_match( "/^([0-9]+,)*[0-9]+\$|^\$/i", otw_get( 'otw_options_ids', '' ) ) ){
			$args['include'] = explode( ',', otw_get( 'otw_options_ids', '' ) );
		}
		if( otw_get( 'otw_search_term', false ) && strlen( otw_get( 'otw_search_term', '' ) ) ){
			$args['search'] = urldecode( otw_get( 'otw_search_term', '' ) );
		}
		
		if( otw_get( 'otw_options_limit', false ) && strlen( otw_get( 'otw_options_limit', '' ) ) && preg_match( "/^([0-9]+)$/i", otw_get( 'otw_options_limit', '' ) ) ){
			$args['number'] = otw_get( 'otw_options_limit', '' );
		}
		
		$all_items = get_terms( 'post_tag', $args );
		
		$options  = array();
		$options['results'] = array();
		
		if( is_array( $all_items ) && count( $all_items ) ){
			foreach( $all_items as $item ){
				$o_key = count( $options['results'] );
				$options['results'][ $o_key ] = array();
				$options['results'][ $o_key ]['id'] = $item->term_id;
				$options['results'][ $o_key ]['text'] = $item->name;
			}
		}
		echo json_encode( $options );
		die;
	}
	
	public function _get_page_options( $postID = 0, $return_result = false ){
		
		$args = array();
		$args['post_type']   = 'page';
		$args['post_status'] = 'publish';
		
		if( $postID ){
			$args['post__in'] = array( $postID );
		}
		
		wp_reset_query();
		$found_posts = new WP_Query( $args );
		
		$options  = array();
		$options['results'] = array();
		
		if( isset( $found_posts->posts ) && is_array( $found_posts->posts ) ){
			
			foreach( $found_posts->posts as $item ){
				
				$o_key = count( $options['results'] );
				$options['results'][ $o_key ] = array();
				$options['results'][ $o_key ]['id'] = $item->ID;
				$options['results'][ $o_key ]['text'] = $item->post_title;
			}
		}
		
		if( $return_result ){
			return json_encode( $options );
		}else{
			
			echo json_encode( $options );
			die;
		}
	}
	
}

































































































