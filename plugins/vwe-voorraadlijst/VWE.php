<?php
/**
 * Plugin Name: VWE-voorraadlijst
 * Plugin URI: https://www.VWE.nl
 * Description: Via VWE heeft u de ideale service in handen om snel en makkelijk uw occasionbedrijf in te richten. Deze plugin biedt u de mogelijkheid uw occasions binnen Wordpress weer te geven. Kijk voor meer informatie op <a href="https://www.VWE.nl" target="_blank">www.VWE.nl</a>.
 * Version: 2.1.8
 * Tested up to: 6.2 
 * Author: VWE Web development
 * Author URI: https://VWE.nl/
*/

defined( 'ABSPATH' ) or die( 'Plugin file cannot be accessed directly.' );

if ( ! class_exists( 'VWE' ) ) {
    // Include helpers
    require_once 'helpers/uri.php';

	class VWE {
		/**
		 * Tag identifier used by file includes and selector attributes.
		 * @var string
		 */
		protected $tag = 'VWE-plugin';

		/**
		 * User friendly name used to identify the plugin.
		 * @var string
		 */
		protected $name = 'VWE';

		/**
		 * Current version of the plugin.
		 * @var string
		 */
		protected $version = '2.1.8';

        /**
		 * Is replaced with the stock
		 * @var string
		 */
        protected $stock_shortcode = "ad-voorraadlijst";

        /**
		 * Plugin uri
		 * @var string
		 */
        protected $stock_uri = "https://svl.autodealers.nl/jsVoorraadPlugin.ashx";

        /**
		 * Is replaced with the carousel
		 * @var string
		 */
        protected $carousel_shortcode = "ad-carousel";

        /**
		 * Plugin uri
		 * @var string
		 */
        protected $carousel_uri = "https://svl.autodealers.nl/jsSlideshow.ashx";

        /**
		 * Is replaced with the search_block
		 * @var string
		 */
        protected $search_block_shortcode = "ad-search-block";

        /**
		 * Plugin uri
		 * @var string
		 */
        protected $search_block_uri = "https://svl.autodealers.nl/wordpressFilters.ashx";

        /**
		 * Is replaced with the insurance page
		 * @var string
		 */
        protected $insurance_shortcode = "ad-verzekeren";

        /**
		 * Plugin uri
		 * @var string
		 */
        protected $insurance_uri = "https://www.dealerservices.eu/Insurance/Frame/";

		/**
		 * List of options to determine plugin behaviour.
		 * @var array
		 */
		protected $options = array();

		/**
		 * List of settings displayed on the admin settings page.
		 * @var array
		 */
		protected $settings = array(
			'did' => array(
                'name' => 'Dealer ID',
				'description' => 'Uw VWE klantnummer (neem contact op met VWE als u deze niet weet)',
				'validator' => 'numeric',
				'placeholder' => 1234
			),
			'defaultHeight' => array(
                'name' => 'Minimale hoogte',
				'description' => 'De terugval hoogte die de voorraadlijst krijgt',
				'validator' => 'numeric',
				'placeholder' => 1000
			),
            'baseurl' => array(
                'name' => 'Basis url',
				'description' => 'Pagina lokatie van de [ad-voorraadlijst] shortcode. (Voorbeeld bij /occasions/) > /occasions/?AdFrameSource=//svl.autodealers.nl/' ,
				'placeholder' => '/occasions/'
			),
            'querystring' => array(
                'name' => 'Parameters',
				'description' => '(Optioneel) Door VWE aangeleverde code',
				'placeholder' => '?var=name&var=name'
			),
		);

		/**
		 * Initiate the plugin by setting the default values and assigning any
		 * required actions and filters.
		 *
		 * @access public
		 */
		public function __construct(){
			if ($options = get_option($this->tag)) {
				$this->options = $options;
			}

			add_shortcode($this->stock_shortcode, array(&$this, 'stock_shortcode'));
            add_shortcode($this->search_block_shortcode, array(&$this, 'search_block_shortcode'));
            add_shortcode($this->insurance_shortcode, array(&$this, 'insurance_shortcode'));
			add_shortcode($this->carousel_shortcode, array($this, 'carousel_shortcode'));

			if (is_admin() ) {
				add_action('admin_menu', array(&$this, 'create_settings_page' ));
                add_action('admin_init', array( $this, 'register_settings'));
			}
		}

		/**
		 * Allow the shortcode to be used.
		 *
		 * @access public
		 * @return string
		 */
		public function stock_shortcode(){
			if(!empty($this->options["querystring"])){
				$uri = new Uri($this->stock_uri . $this->options["querystring"]);
			}else{
				$uri = new Uri($this->stock_uri);
			}

            foreach ($this->options as $key => $value) {
                if ($value != "" && $key != "querystring" && $key != "baseurl") {
                    $uri->addVar($key, $value);
                }
            }

            // Are there params to add?
            if (!empty($this->options["querystring"])) {
                $location = new Uri();
                $params = $location->get_params();

                // Is there a new source and are we not already redirected?
                if (isset($params["AdFrameSource"]) && !isset($params["redirected"])) {
                    // Add params to frame location
                    $frameLocation = new Uri(urldecode($params["AdFrameSource"]));
                    $frameLocation->parseQuerystring($this->options["querystring"]);

                    // Indicate that we are redirected
                    $location->addVar("redirected", "true");

                    // Replace frame location
                    $location->removeVar("AdFrameSource");
                    $location->addVar("AdFrameSource", "replaceme");
                    $redirectTo = str_replace("replaceme", urlencode($frameLocation->toString()), $location->toString());

                    // Redirect to new location
                    return '<script>location.href = "'.$redirectTo.'";</script>';
                }
            }

            return '<script src="'.$uri->toString().'"></script>';
		}

        /**
		 * Allow the shortcode to be used.
		 *
		 * @access public
		 * @return string
		 */
		public function carousel_shortcode($atts){
            extract(shortcode_atts(array(
                'baseurl' => false
            ), $atts));

            if (!$baseurl || $baseurl == "") {
                if ($this->options["baseurl"] != "") {
                    $baseurl = $this->options["baseurl"];
                } else {
                    return "<b>Let op!</b> Het attribuut 'baseurl' mist of is ongeldig";
                }
            }

			$uri = new Uri($this->carousel_uri);
            $uri->addVar("did", $this->options["did"]);
            $uri->addVar("action", "wordpress-slideshow");
            $uri->addVar("baselink", $baseurl);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, str_replace('&amp;','&', $uri->toString()));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            $content = curl_exec($ch);
            curl_close($ch);

            return str_replace("$", "jQuery", $content);
		}

        /**
		 * Allow the shortcode to be used.
		 *
		 * @access public
		 * @return string
		 */
		public function search_block_shortcode($atts){
            extract(shortcode_atts(array(
                'baseurl' => false
            ), $atts));

            if (!$baseurl || $baseurl == "") {
                if ($this->options["baseurl"] != "") {
                    $baseurl = $this->options["baseurl"];
                } else {
                    return "<b>Let op!</b> Het attribuut 'baseurl' mist of is ongeldig";
                }
            }

			$uri = new Uri($this->search_block_uri);
            $uri->addVar("did", $this->options["did"]);
            $uri->addVar("baselink", $baseurl);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, str_replace('&amp;','&', $uri->toString()));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            $content = curl_exec($ch);
            curl_close($ch);

            return $content;
		}

        /**
		 * Allow the shortcode to be used.
		 *
		 * @access public
		 * @return string
		 */
		public function insurance_shortcode(){
			$uri = new Uri($this->insurance_uri);
            $uri->addVar("DealerId", $this->options["did"]);

            return '<script type="text/javascript" src="'.$uri->toString().'"></script>';
		}

        /**
		 * Add the settings page in the admin section
		 *
		 * @access public
		 * @return string
		 */
        public function create_settings_page() {
            add_options_page(
                'Settings - Autodealers',
                'Autodealers',
                'manage_options',
                $this->tag,
                array($this, 'print_settings_page') );
        }

        /**
		 * Prints the settings page
		 *
		 * @access public
		 * @return string
		 */
        public function print_settings_page() {
            ?>
            <div class="wrap">
                <h2>VWE settings</h2>
                <form method="post" action="options.php">
                <?php
                    // This prints out all hidden setting fields
                    // settings_fields( $option_group )
                    settings_fields($this->tag);

                    // do_settings_sections( $page )
                    do_settings_sections($this->tag);
                ?>
                <?php submit_button('Opslaan'); ?>
                </form>
            </div>
            <?php
        }

		/**
		 * Add the setting fields to the Reading settings page.
		 *
		 * @access public
		 */
		public function register_settings() {
			$section = $this->tag;

			add_settings_section(
				$this->tag . '_settings_section',
				$this->name . ' Plugin v' . $this->version,
				function () {
					echo '<h2>Uw occasionlijst binnen uw Wordpress website</h2>'.
                         '<div style="background:#fff;padding:10px 20px;border:dotted 2px #ccc;"><p>Via VWE heeft u de ideale service in handen om snel en makkelijk uw occasionbedrijf in te richten.'.
                         '<li>Kijk voor meer informatie op <a href="//www.vwe.nl/" target="_blank">www.vwe.nl</a> of <a href="//www.vwe.nl/Aanmelden-als-klant" target="_blank">meld u aan via deze link</a>.</li>'.
                         '<li>Stel uw vraag via <a href="mailto:info@vwe.nl?Subject=VWE Wordpress Plugin vraag">info@vwe.nl</a> of via onze helpdesk: <b>088 - 8937001</b></li>'.
                         '<br /><hr />'.
                         '<p>Na aanmelden ontvangt u uw Dealer ID om uw voorraad te tonen. U dient op uw gewenste pagina gebruik te maken van de volgende shortcode teksten:</p>'.
                         '<p><b><span style="color:darkgrey;">Tonen van uw occasionlijst:</span> [ad-voorraadlijst]</b></p>'.
                         '<p><b><span style="color:darkgrey;">Tonen van uw occasioncarousel:</span> [ad-carousel]</b></p>'.
                         '<p><b><span style="color:darkgrey;">Voor de zoekbalk:</span> [ad-search-block]</b></p>'.
                         //'<p><b>Voor verzekeringen:</b> [ad-verzekeren]</b></p>'.
                         '</div>';
                },
				$section
			);

			foreach ( $this->settings AS $id => $options ) {
				$options['id'] = $id;
				add_settings_field(
					$this->tag . '_' . $id . '_settings',
					$options['name'],
					array( &$this, 'settings_field' ),
					$section,
					$this->tag . '_settings_section',
					$options
				);
			}

			register_setting(
				$section,
				$this->tag,
				array( &$this, 'settings_validate')
			);
		}

		/**
		 * Append a settings field to the the fields section.
		 *
		 * @access public
		 * @param array $args
		 */
		public function settings_field(array $options = array()){
			$atts = array(
				'id' => $this->tag . '_' . $options['id'],
				'name' => $this->tag . '[' . $options['id'] . ']',
				'type' => ( isset( $options['type'] ) ? $options['type'] : 'text' ),
				'class' => 'large-text',
				'value' => ( array_key_exists( 'default', $options ) ? $options['default'] : null )
			);

			if ( isset( $this->options[$options['id']] ) ) {
				$atts['value'] = $this->options[$options['id']];
			}

			if ( isset( $options['placeholder'] ) ) {
				$atts['placeholder'] = $options['placeholder'];
			}

			if ( isset( $options['type'] ) && $options['type'] == 'checkbox' ) {
				if ( $atts['value'] ) {
					$atts['checked'] = 'checked';
				}
				$atts['value'] = true;
			}

			array_walk( $atts, function( &$item, $key ) {
				$item = esc_attr( $key ) . '="' . esc_attr( $item ) . '"';
			} );
			?>
			<label>
				<input <?php echo implode( ' ', $atts ); ?> />
				<?php if ( array_key_exists( 'description', $options ) ) : ?>
				<?php esc_html_e( $options['description'] ); ?>
				<?php endif; ?>
			</label>
			<?php
		}

		/**
		 * Validate the settings saved.
		 *
		 * @access public
		 * @param array $input
		 * @return array
		 */
		public function settings_validate( $input ) {
			$errors = array();
			foreach ( $input AS $key => $value ) {
				if ( $value == '' ) {
					unset( $input[$key] );
					continue;
				}

				$validator = false;

				if ( isset( $this->settings[$key]['validator'] ) ) {
					$validator = $this->settings[$key]['validator'];
				}

				switch ( $validator ) {
					case 'numeric':
						if ( is_numeric( $value ) ) {
							$input[$key] = intval( $value );
						} else {
							$errors[] = $key . ' moet een nummer zijn.';
							unset( $input[$key] );
						}
					break;
					default:
						 $input[$key] = strip_tags( $value );
					break;
				}
			}

			if ( count( $errors ) > 0 ) {
				add_settings_error(
					$this->tag,
					$this->tag,
					implode( '<br />', $errors ),
					'error'
				);
			}

			return $input;
		}
	}

	new VWE;
}

// register jquery and style on initialization
add_action('init', 'register_files');
function register_files() {
}

// use the registered jquery and style above
add_action('wp_enqueue_scripts', 'enqueue_script');

function enqueue_script(){
}

// Add settings link on plugin page
function your_plugin_settings_link($links) {
    $settings_link = '<a href="options-general.php?page=VWE-plugin">Instellingen</a>';
    array_unshift($links, $settings_link);
    return $links;
}

$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'your_plugin_settings_link' );