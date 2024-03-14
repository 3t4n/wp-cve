<?php
defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

if ( ! class_exists( 'Safelayout_Preloader_Front' ) ) {

	class Safelayout_Preloader_Front {
		protected $options = null;
		protected $active_loader = false;
		protected $main_code = '';

		public function __construct() {
			add_action( 'template_redirect', array( $this, 'set_cache_callback' ), 3 );
			add_action( 'wp_head', array( $this, 'set_header' ), 7 );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			add_action( 'wp_footer', array( $this, 'set_footer' ) );
     
			add_filter( 'safe_style_css', function( $styles ) {
				$styles[] = 'top';
				$styles[] = 'left';
				$styles[] = 'opacity';
				$styles[] = 'position';
				$styles[] = 'animation-delay';
				$styles[] = '-webkit-animation-delay';
				return $styles;
			} );
		}

		// Add link preload to header
		public function set_header() {
			$this->check_active_loader();
			if ( $this->active_loader ) {
				$options = $this->options;
				if ( trim( $options['brand_url'] ) !== '' && strpos( $options['brand_url'], 'data:image' ) === false ) {
					echo "\n" . '<link rel="preload" fetchpriority="high" as="image" href="' . esc_url( $options['brand_url'] ) . '">';
				}
				if ( $options['icon'] === 'Custom' && strpos( $options['custom_icon'], 'data:image' ) === false ) {
					echo "\n" . '<link rel="preload" fetchpriority="high" as="image" href="' . esc_url( $options['custom_icon'] ) . '">';
				}
			}
		}

		// Set cache callback
		public function set_cache_callback() {
			if ( ! wp_doing_ajax() ) {
				ob_start( array( $this, 'add_preloader_code' ) );
			}
		}

		// Add main html code
		public function add_preloader_code( $html ) {
			if ( $this->main_code && $html ) {
				return preg_replace( '/<body[^>]*>\K/i', $this->main_code, $html, 1 );
			}
			return $html;
		}

		// Add css style and js script
		public function enqueue_scripts() {
			$this->check_active_loader();
			if ( $this->active_loader ) {
				$options = $this->options;
				$this->set_main_code();

				wp_enqueue_script(
					'safelayout-cute-preloader-script',
					SAFELAYOUT_PRELOADER_URL . 'assets/js/safelayout-cute-preloader.min.js',
					array(),
					SAFELAYOUT_PRELOADER_VERSION,
					true
				);
				$temp_obj = array(
					'showingLoader'		=> true,
					'pageLoaded'		=> false,
					'minShowTime'		=> esc_html( $options['minimum_time'] ) * 1000,
					'maxShowTime'		=> esc_html( $options['maximum_time'] ) * 1000,
					'showCloseButton'	=> esc_html( $options['close_button'] ) * 1000,
				);
				wp_localize_script( 'safelayout-cute-preloader-script', 'slplPreLoader', $temp_obj );
			}
		}

		// Add inline script
		public function set_footer() {
			if ( $this->active_loader ) {
				$options = $this->options;
				if ( $options['bar_shape'] != 'No' ||
					$options['counter'] === 'enable' ) {
						$this->set_progress_bar_script();
				}
			}
		}

		// Add progress bar script
		public function set_progress_bar_script() {
			$options = $this->options;
			$pos = $options['counter_position'];
			$pos = $pos === 'center' ? 0.5 : ( $pos === 'left' ? 1 : ( $pos === 'right' ? 0 : 2 ) );
			?>
			<script id = "safelayout-cute-preloader-progress-bar-script-js" type = "text/javascript">
				function slplSetTimer( time, max ) {
					return setInterval( function () {
							var slplTemp = Math.min( Math.floor( slplPercent ), 100 );
							var slplTrans = Math.abs( 100 - slplTemp );
							var slplPos = <?php echo esc_html( $pos ); ?>;
							if ( slplProgress1 ) {
								slplProgress1.style.transform = "translateX(" + ( -slplDir * slplTrans ) + "%)";
								slplProgress2.style.transform = "translateX(" + ( slplDir * slplTrans ) + "%)";
							}
							if ( slplCounter ) {
								if ( slplPos != 2 && slplProgress1 ) {
									slplCounter.style.transform = "translate(" + ( -( Math.min( slplDir + 1, 1 ) - slplPos ) * slplTrans ) + "%, -50%)";
								}
								slplCounter.textContent = slplTemp + "<?php echo esc_html( $options['counter_text'] ); ?>";
							}
							if ( slplPercent >= max ) {
								clearInterval( slplInterval );
								return;
							}
							slplPercent += slplStep;
						}, time );
				}
				function slplResourceComplete() {
					if ( slplPercent < 100 ) {
						clearInterval( slplInterval );
						slplStep = ( slplPercent < 40 ) ? 3 : ( slplPercent < 70 ) ? 2.5 :( slplPercent < 80 ) ? 2 : 1;
						slplInterval = slplSetTimer( 5, 100 );
					}
					return slplPercent;
				}
				document.addEventListener( 'readystatechange', function() {
					if ( document.readyState === 'complete' ) {
						slplResourceComplete();
					}
				});
				var slplImgs = document.images,
					slplVids = document.getElementsByTagName('video'),
					slplDir = document.dir === 'rtl' ? -1 : 1,
					slplProgress1 = document.getElementById('sl-pl-progress-view1'),
					slplProgress2 = document.getElementById('sl-pl-progress-view2'),
					slplCounter = document.getElementById('sl-pl-counter'),
					slplMax = Math.floor(Math.random() * 10 + 80),
					slplResource = 0,
					slplPercent = 0,
					slplStep = 1,
					slplInterval;
				for ( var i = 0, len = slplImgs.length; i < len; i++ ) {
					if ( slplImgs[i].loading != 'lazy' && ! slplImgs[i].complete && slplImgs[i].src ) {
						slplResource++;
					}
				}
				for ( var i = 0, len = slplVids.length; i < len; i++ ) {
					if ( slplVids[i].poster ) {
						slplResource++;
					}
					if ( slplVids[i].preload != 'none' ) {
						slplResource++;
					}
				}
				if ( slplResource <= 20 ) {
					slplInterval = slplSetTimer( 1000 / slplMax, slplMax );
				} else {
					slplInterval = slplSetTimer( Math.min( 15 * slplResource + 700, 4200 ) / slplMax, slplMax );
				}
			</script>
			<?php
		}

		// Checks if the page is being rendered via editors
		public function check_is_in_editor() {
			$page_builders = array(
				'elementor-preview',//elementor
				'fl_builder',//beaverbuilder
				'vc_action',//WPBakery
				'vc_editable',//WPBakery
				'et_fb',//divi
				'tve',
				'ct_builder',//oxygen
				'fb-edit',//avada
				'siteorigin_panels_live_editor',
				'bricks',//bricks builder
				'vcv-action',
			);

			$ret = false;
			foreach ( $page_builders as $page_builder ) {
				if ( array_key_exists( $page_builder, $_GET ) ) {
					$ret = true;
					break;
				}
			}
			return is_customize_preview() || $ret;
		}

		// Check if preloader must be shown on this page.
		public function check_active_loader() {
			if ( $this->options === null ) {
				$active = false;
				$this->options = $options = safelayout_preloader_get_options();

				if ( $this->check_is_in_editor() || $options['enable_preloader'] != 'enable' ) {
						$this->active_loader = false;
						return;
				}

				$meta = '';
				$id = $this->get_id();
				if ( $id != 0 ) {
					$meta = trim( get_post_meta( $id, 'safelayout_preloader_shortcode', true ) );
				}
				if ( $meta === '' || substr( $meta, 1, 20 ) != 'safelayout_preloader' ) {
					if ( $options['display_on'] === 'full' ||
						( $options['display_on'] === 'home' && is_front_page() ) ||
						( $options['display_on'] === 'posts' && is_single() ) ||
						( $options['display_on'] === 'pages' && is_page() ) ||
						( $options['display_on'] === 'search' && is_search() ) ||
						( $options['display_on'] === 'archive' && is_archive() ) ) {
							$active = true;
					}

					$type = get_post_type();
					if ( $options['display_on'] === 'custom-id' ) {
						if ( $id != 0 ) {
							$active = $this->check_specific_posts( $options['specific_IDs'], $id );
						}
					}

					if ( $options['display_on'] === 'custom-name' ) {
						if ( $type ) {
							$active = $this->check_specific_posts( $options['specific_names'], $type );
						}
					}
				} else {
					$op = wp_parse_args( shortcode_parse_atts( substr( $meta, 22, -1 ) ), safelayout_preloader_get_default_options() );
					$code = get_option( 'safelayout_preloader_special_post' . $id );
					if ( $code ) {
						$this->options = $options = $op;
						$this->options['code_CSS_HTML'] = $code;
						$this->options['id'] = $id . $this->options['id'];
					}
					$active = true;
				}

				$this->active_loader = $active;
			}
		}

		// Return curent page, post id
		public function get_id() {
			$id = get_queried_object_id();
			if ( $id === 0 ) {
				global $wp;
				$page = get_page_by_path( $wp->request );
				if ( isset( $page->ID ) ) {
					$id = $page->ID;
				}
			}
			return $id;
		}

		// Return true if id is in specific_posts options
		public function check_specific_posts( $ids, $id ) {
			$ids = explode( ',', $ids );
			$ids = array_map('trim', $ids);
			return in_array( $id, $ids );
		}

		// Set main code(html)
		public function set_main_code() {
			$allowed_tags = array(
				'noscript'		=> [],
				'femerge'		=> [],
				'femergenode'	=> [ 'in'			=> 1, ],
				'span'			=> [ 'style'		=> 1, ],
				'style'			=> [ 'id'			=> 1, ],
				'defs'			=> [ 'id'			=> 1, ],
				'mask'			=> [ 'id'			=> 1, ],
				'fegaussianblur'=> [ 'stddeviation'	=> 1, ],
				'div'			=> [
					'class'			=> 1,
					'id'			=> 1,
					'style'			=> 1,
				],
				'img'			=> [
					'class'			=> 1,
					'data-*'		=> 1,
					'id'			=> 1,
					'style'			=> 1,
					'alt'			=> 1,
					'src'			=> 1,
					'width'			=> 1,
					'height'		=> 1,
				],
				'svg'			=> [
					'class'			=> 1,
					'viewbox'		=> 1,
				],
				'symbol'		=> [
					'id'			=> 1,
					'viewbox'		=> 1,
				],
				'use'			=> [
					'id'			=> 1,
					'xlink:href'	=> 1,
				],
				'g'				=> [
					'filter'		=> 1,
					'class'			=> 1,
				],
				'lineargradient'=> [
					'id'			=> 1,
					'x1'			=> 1,
					'y1'			=> 1,
					'x2'			=> 1,
					'y2'			=> 1,
				],
				'stop'			=> [
					'stop-color'	=> 1,
					'stop-opacity'	=> 1,
					'offset'		=> 1,
				],
				'path'			=> [
					'class'			=> 1,
					'stroke'		=> 1,
					'fill'			=> 1,
					'mask'			=> 1,
					'id'			=> 1,
					'd'				=> 1,
					'stroke-width'	=> 1,
				],
				'circle'		=> [
					'class'			=> 1,
					'stroke'		=> 1,
					'style'			=> 1,
					'mask'			=> 1,
					'cx'			=> 1,
					'cy'			=> 1,
					'r'				=> 1,
				],
				'rect'			=> [
					'x'				=> 1,
					'y'				=> 1,
					'width'			=> 1,
					'height'		=> 1,
					'fill'			=> 1,
				],
				'fecolormatrix'	=> [
					'type'			=> 1,
					'values'		=> 1,
				],
				'feflood'		=> [
					'flood-color'	=> 1,
					'flood-opacity'	=> 1,
				],
				'fecomposite'	=> [
					'in2'			=> 1,
					'operator'		=> 1,
				],
				'fefunca'		=> [
					'type'			=> 1,
					'tablevalues'	=> 1,
				],
				'feoffset'		=> [
					'dx'			=> 1,
					'dy'			=> 1,
				],
				'filter'		=> [
					'x'				=> 1,
					'y'				=> 1,
					'width'			=> 1,
					'height'		=> 1,
					'id'			=> 1,
					'color-interpolation-filters'	=> 1,
				],
				'fecomponenttransfer'	=> [],
			);

			$options = $this->options;
			$id = 'safelayout_cute_preloader_escaped_code_' . $options['id'];
			$code = get_transient( $id );

			if ( false === $code ) {
				$code = wp_kses( stripslashes( $options['code_CSS_HTML'] ), $allowed_tags );
				set_transient( $id, $code, DAY_IN_SECONDS ); 
			}
			$this->main_code = $code;
			if ( trim( $options['brand_url'] ) !== '' ) {
				$temp1 = array( 'wrest-X', 'wrest-Y', 'roll', 'pipe', 'swirl', 'sheet', );
				if ( in_array( $options['brand_anim'], $temp1 ) ) {
					ob_start();
					echo '<script id = "safelayout-cute-preloader-brand-anim-synchro" type = "text/javascript">' .
						 "\n\tvar childs = document.getElementById('sl-pl-brand-parent').children;" .
						 "\n\tvar name = 'sl-pl-brand-" . esc_html( $options['brand_anim'] ) . "';" .
						 "\n\tfor ( var i = 0 ; i < childs.length ; i++ ) {\n\t\tif ( childs[i].classList ) {\n\t\t\tchilds[i].classList.add( name );" .
						 "\n\t\t} else {\n\t\t\tchilds[i].className += ' ' + name;\n\t\t}\n\t}" . "\n</script>";
					$this->main_code .= ob_get_clean();
				}
			}
		}
	}
	new Safelayout_Preloader_Front();
}
