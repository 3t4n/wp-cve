<?php

/**
 * Protect direct access
 */
if ( ! defined( 'ABSPATH' ) ) die;

if ( ! class_exists( 'GS_Plugins_Common_Pages' ) ) {

    class GS_Plugins_Common_Pages {

		public $data = [];
        
        public function __construct( $data ) {
			$this->data = array_merge( $this->get_default_data(), $data );
            add_action( 'admin_menu', array( $this, 'admin_menu' ) );
            add_action( 'admin_enqueue_scripts', array( $this, 'scripts') );
        }

        public function get_default_data() {
			return [
				'parent_slug' 		=> null,
				'lite_page_title' 	=> __('Lite Plugins by GS Plugins'),
				'pro_page_title' 	=> __('Premium Plugins by GS Plugins'),
				'help_page_title' 	=> __('Support & Documentation by Gs Plugins'),
				'lite_page_slug' 	=> 'gs-plugins-premium',
				'pro_page_slug' 	=> 'gs-plugins-lite',
				'help_page_slug' 	=> 'gs-plugins-help',
				'links' => []
			];
        }

        public function scripts( $hook ) {
			$pages = [ $this->data['pro_page_slug'], $this->data['lite_page_slug'], $this->data['help_page_slug'] ];
			foreach ( $pages as $page ) {
				if ( ! str_contains( $hook, '_' . $page ) ) continue;
				wp_enqueue_style( 'gs-plugins-common-pages', plugin_dir_url( __FILE__ ) . 'assets/gs-plugins-common-pages.min.css' );
				break;
			}
        }
                
        public function admin_menu() {

			

            // Pro Plugins
			add_submenu_page( $this->data['parent_slug'], $this->data['pro_page_title'], __('GS Plugins Pro'), 'manage_options', $this->data['pro_page_slug'], array( $this, 'display_pro_plugins' ) );
            
			// Free Plugins
			add_submenu_page( $this->data['parent_slug'], $this->data['lite_page_title'], __('GS Plugins Lite'), 'manage_options', $this->data['lite_page_slug'], array( $this, 'display_free_plugins' ) );
            
			// Help Page
			add_submenu_page( $this->data['parent_slug'], $this->data['help_page_title'],  __('Help & Usage'), 'manage_options', $this->data['help_page_slug'], array( $this, 'display_help_page' ) );

        }
        
        public function get_pro_plugins() {
			
			$plugins = get_transient( 'gsplugins-pro-plugins' );

			if ( $plugins !== false ) return (array) $plugins;

			$response = wp_remote_get( 'https://gsplugins.com/gs_plugins_list/products.json' );

			if ( ! is_wp_error($response) ) {
				if ( !empty($response) && $response['response']['code'] == 200 ) {
					$plugins = json_decode( $response['body'], true );
					set_transient( 'gsplugins-pro-plugins', $plugins, WEEK_IN_SECONDS / 2 );
				}
			}

			return (array) $plugins;

		}

		public function wp_star_rating( $args = [] ) {
			$defaults = [
				'rating' 	=> 0,
				'type' 		=> 'rating',
				'number' 	=> 0,
			];
			$r = wp_parse_args( $args, $defaults );
	
			// Non-english decimal places when the $rating is coming from a string
			$rating = str_replace( ',', '.', $r['rating'] );
	
			// Convert Percentage to star rating, 0..5 in .5 increments
			if ( 'percent' == $r['type'] ) {
				$rating = round( $rating / 10, 0 ) / 2;
			}
	
			// Calculate the number of each type of star needed
			$full_stars = floor( $rating );
			$half_stars = ceil( $rating - $full_stars );
			$empty_stars = 5 - $full_stars - $half_stars;
	
			if ( $r['number'] ) {
				/* translators: 1: The rating, 2: The number of ratings */
				$format = _n( '%1$s rating based on %2$s rating', '%1$s rating based on %2$s ratings', $r['number'] );
				$title = sprintf( $format, number_format_i18n( $rating, 1 ), number_format_i18n( $r['number'] ) );
			} else {
				/* translators: 1: The rating */
				$title = sprintf( __( '%s rating' ), number_format_i18n( $rating, 1 ) );
			}
	
			echo '<div class="star-rating" title="' . esc_attr( $title ) . '">';
			echo '<span class="screen-reader-text">' . esc_html( $title ) . '</span>';
			echo str_repeat( '<div class="star star-full"></div>', (int) $full_stars );
			echo str_repeat( '<div class="star star-half"></div>', (int) $half_stars );
			echo str_repeat( '<div class="star star-empty"></div>', (int) $empty_stars);
			echo '</div>';
		}

		public function plugin_single__compatibility( $plugin ) {
			if ( !empty($plugin["tested"]) && version_compare(substr($GLOBALS["wp_version"], 0, strlen($plugin["tested"])), $plugin["tested"], ">")) {
				echo '<span class="gs-plugins--com_untested">' . __( "<strong>Untested</strong> with your version of WordPress" ) . '</span>';
			} elseif (!empty($plugin["requires"]) && version_compare(substr($GLOBALS["wp_version"], 0, strlen($plugin["requires"])), $plugin["requires"], "<"))  {
				echo '<span class="gs-plugins--com_incompatible">' . __("Incompatible with your version of WordPress") . '</span>';
			} else {
				echo '<span class="gs-plugins--com_compatible">' . __("Compatible with your version of WordPress") . '</span>';
			}
		}

		public function plugin_single__action_links( $plugin, $details_link, $name ) {
			
			/* translators: 1: Plugin name and version. */
			$action_links[] = '<a href="' . esc_url( $details_link ) . '" aria-label="' . esc_attr( sprintf("More information about %s", $name ) ) . '" data-title="' . esc_attr( $name ) . '">' . __( 'More Details' ) . '</a>';
			$action_links = array();

			if ( current_user_can( "install_plugins") || current_user_can("update_plugins") ) {
				$status = install_plugin_install_status( $plugin );
				switch ($status["status"]) {
					case "install":
						if ( $status["url"] ) {
							/* translators: 1: Plugin name and version. */
							$action_links[] = '<a class="install-now button" href="' . esc_url($status['url']) . '" aria-label="' . esc_attr( sprintf("Install %s now", $name ) ) . '">' . __( 'Install Now' ) . '</a>';
						}
					break;
					case "update_available":
						if ($status["url"]) {
							/* translators: 1: Plugin name and version */
							$action_links[] = '<a class="button" href="' . esc_url($status['url']) . '" aria-label="' . esc_attr( sprintf( "Update %s now", $name ) ) . '">' . __( 'Update Now' ) . '</a>';
						}
					break;
					case "latest_installed":
					case "newer_installed":
						$action_links[] = '<span class="button button-disabled" title="' . esc_attr__( "This plugin is already installed and is up to date" ) . ' ">' . _x( 'Installed', 'plugin' ) . '</span>';
					break;
				}
			}

			return $action_links;
		}

		public function thousands_currency_format($num) {

			if($num>1000) {
		  
				  $x = round($num);
				  $x_number_format = number_format($x);
				  $x_array = explode(',', $x_number_format);
				  $x_parts = array('k', 'm', 'b', 't');
				  $x_count_parts = count($x_array) - 1;
				  $x_display = $x;
				  $x_display = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
				  $x_display .= $x_parts[$x_count_parts - 1];
		  
				  return $x_display;
		  
			}
		  
			return $num;
		}

		public function plugin_single__random_color() {
			$colors = ['gs-plugin--color-one', 'gs-plugin--color-two', 'gs-plugin--color-three', 'gs-plugin--color-four' ];
			return $colors[ array_rand( $colors ) ];
		}

		public function plugin_single__icon_url( $plugin ) {
			if ( !empty($plugin["icons"]["svg"]) ) return $plugin["icons"]["svg"];
			if ( !empty( $plugin["icons"]["2x"]) ) return $plugin["icons"]["2x"];
			if ( !empty( $plugin["icons"]["1x"]) ) return $plugin["icons"]["1x"];
			return $plugin["icons"]["default"];
        }

		public function plugin_single( $plugin ) {
																			
			if ( is_object( $plugin) ) $plugin = (array) $plugin;
			
			$title 		= wp_kses_post( $plugin["name"] );
			$desc 		= wp_kses_post( $plugin["short_description"] );
			$author 	= wp_kses_post( $plugin["author"] );
			$version 	= wp_kses_post( $plugin["version"] );
			$name 		= strip_tags( $title . " " . $version );
			
			$plugin_icon_url 	= $this->plugin_single__icon_url( $plugin );
			$details_link 		= self_admin_url( "plugin-install.php?tab=plugin-information&amp;plugin=" . $plugin["slug"] . "&amp;TB_iframe=true&amp;width=600&amp;height=550" );
			$action_links 		= $this->plugin_single__action_links( $plugin, $details_link, $name );
			
			?>

            <div class="gs-plugins--single_wrapper">

				<div class="gs-plugins--single <?php echo esc_attr( $this->plugin_single__random_color() ); ?>">

					<div class="gs-plugins--single_top">

						<a href="<?php echo esc_url( $details_link ); ?>" class="gs-plugins--single_icon" target="_blank"><img src="<?php echo esc_url( $plugin_icon_url ) ?>" /></a>
						
						<h4 class="gs-plugins--single_name"><a href="<?php echo esc_url( $details_link ); ?>"><?php echo esc_html( $title ); ?></a></h4>

						<div class="gs-plugins--single_details">
							<div class="gs-plugins--single_authors"><cite>By <?php echo wp_kses_post( $author );?></cite></div>
							<div class="gs-plugins--single_desc"><?php echo wp_kses_post( $desc ); ?></div>
							<ul class="gs-plugins--single_actions">
								<?php if ( $action_links ) echo wp_kses_post( '<li>' . implode("</li><li>", $action_links) . '</li>' ); ?>
							</ul>
						</div>
						
					</div>
	
					<div class="gs-plugins--single_bottom">
	
						<div class="gs-plugins--single_info">
	
							<div class="vers column-rating">
								<?php $this->wp_star_rating( array( "rating" => $plugin["rating"], "type" => "percent", "number" => $plugin["num_ratings"] ) ); ?>
								<span class="num-ratings">
									(<?php echo number_format_i18n( $plugin["num_ratings"] ); ?>)
								</span>
							</div>
							
							<div class="gs-plugins--single_update">
								<strong><?php _e("Last Updated:"); ?></strong>
								<span title="<?php echo esc_attr($plugin["last_updated"]); ?>">
									<?php echo esc_html( sprintf( "%s ago", human_time_diff( strtotime( $plugin["last_updated"] ) ) ) ); ?>
								</span>
							</div>
		
							<div class="gs-plugins--single_compatibility">
								<?php $this->plugin_single__compatibility( $plugin ); ?>
							</div>
	
						</div>
	
						<div class="gs-plugins--single_downloaded">
							<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="10px" height="14px"><path fill-rule="evenodd" fill-opacity="0.251" fill="rgb(24, 24, 24)" d="M-0.000,14.000 L-0.000,12.000 L10.000,12.000 L10.000,14.000 L-0.000,14.000 ZM0.293,6.477 L1.862,4.908 L4.000,7.000 L4.000,-0.000 L6.000,-0.000 L6.000,7.000 L8.138,4.908 L9.707,6.477 L5.000,11.000 L0.293,6.477 Z"/></svg>
							<span><?php echo esc_html( $this->thousands_currency_format($plugin["downloaded"]) ); ?></span>
						</div>
	
					</div>

				</div>

            </div>

			<?php

		}

        public function get_free_plugins() {

			include( ABSPATH . "wp-admin/includes/plugin-install.php" );

			$plugins = get_transient( 'gsplugins_free_plugins' );
			if ( ! empty($plugins) ) return $plugins;

			global $tabs, $tab, $paged;

			$tabs 		= array();
			$tab 		= "search";
			$per_page 	= 20;
			$args 		= array(
				"author" 	=> "samdani",
				"page" 		=> $paged,
				"per_page" 	=> $per_page,
				"fields" 	=> array( "last_updated" => true, "downloaded" => true, "icons" => true ),
				"locale" 	=> get_locale(),
			);
			$arges 	= apply_filters( "install_plugins_table_api_args_$tab", $args );
			$api 	= plugins_api( "query_plugins", $arges );
			
			$plugins = $api->plugins;

			set_transient( 'gsplugins_free_plugins', $plugins, WEEK_IN_SECONDS / 2 );

			return $plugins;

		}

        public function get_logo() {
			include_once plugin_dir_path( __FILE__ ) . 'assets/gs-plugins-logo.svg';
		}

		public function plugin_single__pro( $plugin ) {

			$ribbon = '';
			$ribbon_class = '';
			
			if ( !empty( $plugin['ribbon'] ) ) {
				$ribbon = $plugin['ribbon'];
				$ribbon_class = 'gs-plugins--cat-' . sanitize_title( $ribbon );
			}
			
			?>

			<div class="gs-plugins--single_wrapper <?php echo esc_attr( $ribbon_class ); ?>">
				<div class="gs-plugins--single">

					<div class="gs-plugins--single_top">
						<a href="<?php echo esc_url( $plugin['permalink'] ); ?>" class="gs-plugins--single_icon" target="_blank"><img src="<?php echo esc_url( $plugin['thumbnail'] ); ?>" alt="Thumbnail"></a>
						<h4 class="gs-plugins--single_name"><a href="<?php echo esc_url( $plugin['permalink'] ); ?>" target="_blank"><?php echo esc_html( $plugin['title'] ); ?></a></h4>
						<div class="gs-plugins--single_desc"><?php echo wp_kses_post( $plugin['description'] ); ?></div>
						<?php if ( ! empty( $ribbon ) ) : ?>
							<div class="gs-plugins--single_ribbon"><?php echo esc_html( $ribbon ); ?></div>
						<?php endif; ?>
					</div>

					<div class="gs-plugins--single_bottom">
						<div class="gs-plugins--single_left">
							<a class="button button_demo" href="<?php echo esc_url( $plugin['demo_link'] ); ?>" target="_blank">
								<svg width="20" enable-background="new -0.709 -32.081 141.732 141.732" id="Livello_1" version="1.1" viewBox="-0.709 -32.081 141.732 141.732" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><path fill="currentColor" d="M89.668,38.786c0-10.773-8.731-19.512-19.51-19.512S50.646,28.01,50.646,38.786c0,10.774,8.732,19.511,19.512,19.511   C80.934,58.297,89.668,49.561,89.668,38.786 M128.352,38.727c-13.315,17.599-34.426,28.972-58.193,28.972   c-23.77,0-44.879-11.373-58.194-28.972C25.279,21.129,46.389,9.756,70.158,9.756C93.927,9.756,115.036,21.129,128.352,38.727    M140.314,38.76C125.666,15.478,99.725,0,70.158,0S14.648,15.478,0,38.76c14.648,23.312,40.591,38.81,70.158,38.81   S125.666,62.072,140.314,38.76"/></svg>
								<span>Demo</span>
							</a>
							<a class="button button_docs" href="<?php echo esc_url( $plugin['docs_link'] ); ?>" target="_blank">
								<svg width="20" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path fill="currentColor" d="M22.78,10.37A1,1,0,0,0,22,10H20V9a3,3,0,0,0-3-3H10.72l-.32-1A3,3,0,0,0,7.56,3H4A3,3,0,0,0,1,6V18a3,3,0,0,0,3,3H18.4a3,3,0,0,0,2.92-2.35L23,11.22A1,1,0,0,0,22.78,10.37ZM5.37,18.22a1,1,0,0,1-1,.78H4a1,1,0,0,1-1-1V6A1,1,0,0,1,4,5H7.56a1,1,0,0,1,1,.68l.54,1.64A1,1,0,0,0,10,8h7a1,1,0,0,1,1,1v1H8a1,1,0,0,0-1,.78Zm14,0a1,1,0,0,1-1,.78H7.21a1.42,1.42,0,0,0,.11-.35L8.8,12h12Z"/></svg>
								<span>Docs</span>
							</a>
						</div>
						<div class="gs-plugins--single_right">
							<span class="gs-plugins--single_price"><?php echo esc_html( $plugin['display_price'] ); ?></span>
							<a class="button button_buy" href="<?php echo esc_url( $plugin['permalink'] ); ?>" target="_blank">
								<svg width="20" class="feather feather-shopping-cart" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
								<span>Buy Now</span>
							</a>
						</div>
					</div>
					
				</div>
			</div>

			<?php

		}

        public function display_free_plugins() {

			?>

			<div class="gs-plugins--list gs-plugins--list_free">

				<div class="gs-plugins--list_inner">

					<div class="gs-plugins--list_header">
	
						<div class="gs-plugins--list_logo"><?php $this->get_logo(); ?></div>
	
						<div class="gs-plugins--list_info">
							<h2 class="gs-plugins--list_brand">GS Plugins</h3>
							<p class="gs-plugins--list_desc">Recommended more free plugins</p>
						</div>
	
					</div>
	
					<div class="gs-plugins--list_items">
						<div class="gs-plugins--list_items_row">
							<?php foreach ( $this->get_free_plugins() as $plugin ) $this->plugin_single( $plugin ); ?>
						</div>
					</div>

				</div>

			</div>

			<?php

		}

		public function display_pro_plugins() {

			?>

			<div class="gs-plugins--list gs-plugins--list_pro">

				<div class="gs-plugins--list_inner">

					<div class="gs-plugins--list_header">

						<div class="gs-plugins--list_logo"><?php $this->get_logo(); ?></div>

						<div class="gs-plugins--list_info">
							<h2 class="gs-plugins--list_brand">GS Plugins</h3>
							<p class="gs-plugins--list_desc">Unlock the power with Pro plugins.</p>
						</div>

					</div>

					<div class="gs-plugins--list_items">
						<div class="gs-plugins--list_items_row">
							<?php foreach ( $this->get_pro_plugins() as $plugin ) $this->plugin_single__pro( $plugin ); ?>
						</div>
					</div>

				</div>

			</div>

			<?php

        }

		public function display_help_page() {

			?>

			<div class="gs-plugins--list gs-plugins--help">

				<div class="gs-plugins--list_inner">

					<div class="gs-plugins--list_header">

						<div class="gs-plugins--list_logo"><?php $this->get_logo(); ?></div>

						<div class="gs-plugins--list_info">
							<h2 class="gs-plugins--list_brand">GS Plugins</h3>
							<p class="gs-plugins--list_desc">Have a question? Need any help?</p>
						</div>

					</div>

					<div class="gs-plugins--list_items">
						<div class="gs-plugins--list_items_row">

							<?php if ( ! empty( $this->data['links']['docs_link'] ) ) : ?>
							<div class="gs-plugins--single_wrapper">
								<div class="gs-plugins--single gs-plugin--color-one">
									<div class="gs-plugins--single_top">
										<div class="gs-plugins--single_icon"><svg xmlns="http://www.w3.org/2000/svg" width="28.344" height="31.437" viewBox="0 0 28.344 31.437"><path fill="#28be82" fill-rule="evenodd" d="M407.569,489.515a1.035,1.035,0,0,1-1.117,1.117h-4.469a4.144,4.144,0,0,1-4.468-4.468V463.823a4.144,4.144,0,0,1,4.468-4.469h15.639a4.144,4.144,0,0,1,4.469,4.469v7.819a1.118,1.118,0,0,1-2.235,0v-7.819a2.072,2.072,0,0,0-2.234-2.235H401.983a2.072,2.072,0,0,0-2.234,2.235v22.341a2.072,2.072,0,0,0,2.234,2.234h4.469A1.035,1.035,0,0,1,407.569,489.515Zm8.936-22.341H403.1a1.117,1.117,0,0,0,0,2.234h13.4A1.117,1.117,0,0,0,416.505,467.174Zm1.117,5.585a1.035,1.035,0,0,0-1.117-1.117H403.1a1.117,1.117,0,0,0,0,2.234h13.4A1.035,1.035,0,0,0,417.622,472.759ZM403.1,476.11a1.117,1.117,0,1,0,0,2.234h7.82a1.117,1.117,0,1,0,0-2.234H403.1Zm21.639,5.641-7.109,7.109a3.941,3.941,0,0,1-.79.79l-5.671,1.131,0.932-5.871a4.276,4.276,0,0,1,.79-0.789L420,477.012A3.351,3.351,0,1,1,424.739,481.751Zm-3.949.79-1.58-1.58-4.739,4.739-0.79,2.37,2.37-.79Zm2.37-3.95a1.037,1.037,0,0,0-1.58,0l-0.79.79,1.58,1.58,0.79-.79A1.038,1.038,0,0,0,423.16,478.591Z" transform="translate(-397.5 -459.344)"/></svg></div>
										<h4 class="gs-plugins--single_name">Check Documentation</h4>
										<div class="gs-plugins--single_details">
											<div class="gs-plugins--single_desc">Our plugins are developed by maintaining WordPress standards, our docs will help you to understand the basic & advances usage.</div>
											<a class="button button_color_one" href="<?php echo esc_url_raw( $this->data['links']['docs_link'] ); ?>" target="_blank">Documentation</a>
										</div>
									</div>
								</div>
							</div>
							<?php endif; ?>

							<div class="gs-plugins--single_wrapper">
								<div class="gs-plugins--single gs-plugin--color-two">
									<div class="gs-plugins--single_top">
										<div class="gs-plugins--single_icon"><svg xmlns="http://www.w3.org/2000/svg" width="36.344" height="35.84" viewBox="0 0 36.344 35.84"><path fill="#fc9b1d" fill-rule="evenodd" d="M429.9,1091.45l-2.113-6.15a15.4,15.4,0,0,0-13.559-22.14,15.386,15.386,0,0,0-15.616,15.18,11.213,11.213,0,0,0-3.4,13.77l-1.456,4.24a1.995,1.995,0,0,0,1.885,2.65,1.872,1.872,0,0,0,.656-0.11l4.236-1.45a11.291,11.291,0,0,0,4.788,1.08h0.017a11.183,11.183,0,0,0,9.042-4.6,15.349,15.349,0,0,0,6.35-1.56l6.15,2.12a2.628,2.628,0,0,0,.779.13A2.384,2.384,0,0,0,429.9,1091.45Zm-24.561,4.9h-0.014a9.039,9.039,0,0,1-4.207-1.04,1.071,1.071,0,0,0-.858-0.07l-4.343,1.49,1.492-4.34a1.084,1.084,0,0,0-.067-0.86,9.048,9.048,0,0,1,1.472-10.48,15.42,15.42,0,0,0,12.86,12.7A9.018,9.018,0,0,1,405.343,1096.35Zm22.463-3.97a0.216,0.216,0,0,1-.217.05l-6.584-2.27a1.063,1.063,0,0,0-.352-0.06,1.125,1.125,0,0,0-.506.13,13.238,13.238,0,0,1-6.143,1.52h-0.021a13.253,13.253,0,0,1-13.2-12.99,13.21,13.21,0,0,1,13.416-13.43,13.217,13.217,0,0,1,11.462,19.38,1.09,1.09,0,0,0-.066.86l2.262,6.59A0.207,0.207,0,0,1,427.806,1092.38Zm-7.372-19.58h-12.9a1.08,1.08,0,1,0,0,2.16h12.9A1.08,1.08,0,1,0,420.434,1072.8Zm0,4.45h-12.9a1.085,1.085,0,0,0,0,2.17h12.9A1.085,1.085,0,0,0,420.434,1077.25Zm-4.966,4.46h-7.936a1.085,1.085,0,0,0,0,2.17h7.935A1.085,1.085,0,0,0,415.468,1081.71Z" transform="translate(-393.656 -1063.16)"/></svg></div>
										<h4 class="gs-plugins--single_name">Get Customer Support</h4>
										<div class="gs-plugins--single_details">
											<div class="gs-plugins--single_desc">We are happy to help you with any questions or issues you may have with our plugin. We look forward to helping you.</div>
											<a class="button button_color_two" href="https://www.gsplugins.com/contact/" target="_blank">Get Support</a>
										</div>
									</div>
								</div>
							</div>
							
							<?php if ( ! empty( $this->data['links']['rating_link'] ) ) : ?>
							<div class="gs-plugins--single_wrapper">
								<div class="gs-plugins--single gs-plugin--color-four">
									<div class="gs-plugins--single_top">
										<div class="gs-plugins--single_icon"><svg xmlns="http://www.w3.org/2000/svg" width="52.965" height="31.438" viewBox="0 0 52.965 31.438"><path fill="#ed1c24" fill-rule="evenodd" d="M990.28,782.436l8.01-7.031a0.9,0.9,0,0,0-.514-1.58l-10.616-.984-1.013-2.351a0.905,0.905,0,0,0-1.663.715l1.226,2.845a0.9,0.9,0,0,0,.748.542l9.06,0.84-6.836,6a0.9,0.9,0,0,0-.285.878l2,8.869-7.823-4.642a0.912,0.912,0,0,0-.925,0l-7.823,4.642,2-8.868a0.9,0.9,0,0,0-.286-0.879l-6.836-6,9.06-.839a0.906,0.906,0,0,0,.748-0.543l3.6-8.349,0.921,2.138a0.9,0.9,0,0,0,1.662-.716l-1.752-4.065a0.905,0.905,0,0,0-1.663,0l-4.217,9.783-10.617.984a0.9,0.9,0,0,0-.514,1.58l8.01,7.031L971.6,792.828a0.9,0.9,0,0,0,1.345.977l9.168-5.439,9.168,5.439a0.905,0.905,0,0,0,1.345-.977Zm0,0-19.617,1.441-4.492-.416-1.784-4.139a0.9,0.9,0,0,0-1.662,0l-1.786,4.139-4.492.416a0.9,0.9,0,0,0-.514,1.578l1.635,1.435a0.9,0.9,0,1,0,1.2-1.356l-0.057-.05,2.935-.271a0.9,0.9,0,0,0,.747-0.543l1.166-2.7,1.165,2.7a0.9,0.9,0,0,0,.748.543l2.934,0.271-2.214,1.944a0.9,0.9,0,0,0-.286.878l0.648,2.872-2.534-1.5a0.905,0.905,0,0,0-.924,0l-2.535,1.5,0.65-2.873a0.905,0.905,0,0,0-1.765-.4l-1.11,4.92a0.905,0.905,0,0,0,1.345.977l3.878-2.3,3.879,2.3a0.9,0.9,0,0,0,1.345-.977l-0.991-4.4,3.389-2.976a0.9,0.9,0,0,0-.513-1.578h0Zm0,0,37.887,0.621a0.894,0.894,0,0,0-.77-0.621l-4.5-.416-1.78-4.139a0.9,0.9,0,0,0-1.662,0l-1.785,4.139-4.492.416a0.9,0.9,0,0,0-.513,1.578l1.794,1.574a0.9,0.9,0,1,0,1.194-1.357l-0.215-.188,2.934-.271a0.9,0.9,0,0,0,.748-0.543l1.167-2.7,1.16,2.7a0.906,0.906,0,0,0,.75.543l2.94,0.271-2.22,1.944a0.882,0.882,0,0,0-.28.878l0.64,2.873-2.53-1.5a0.9,0.9,0,0,0-.92,0l-2.539,1.5,0.648-2.873a0.9,0.9,0,0,0-1.764-.4l-1.11,4.919a0.905,0.905,0,0,0,1.345.977l3.88-2.3,3.88,2.3a0.9,0.9,0,0,0,.99-0.046,0.906,0.906,0,0,0,.35-0.93l-0.99-4.4,3.39-2.975a0.891,0.891,0,0,0,.26-0.959h0Zm0,0" transform="translate(-955.625 -762.5)"/></svg></div>
										<h4 class="gs-plugins--single_name">Show Your Love</h4>
										<div class="gs-plugins--single_details">
											<div class="gs-plugins--single_desc">We would greatly appreciate it if you could take the time to rate and review our plugin. Your feedback helps us improve and provide the best experience possible for our customers.</div>
											<a class="button button_color_four" href="<?php echo esc_url_raw( $this->data['links']['rating_link'] ); ?>" target="_blank">Rate Us Now</a>
										</div>
									</div>
								</div>
							</div>
							<?php endif; ?>

							<div class="gs-plugins--single_wrapper">
								<div class="gs-plugins--single gs-plugin--color-three">
									<div class="gs-plugins--single_top">
										<div class="gs-plugins--single_icon"><svg xmlns="http://www.w3.org/2000/svg" width="31.5" height="32.188" viewBox="0 0 31.5 32.188"><path fill-rule="evenodd" fill="#1e90ff" d="M423.629,777.9h-1.958v-3.573a1.153,1.153,0,0,0-1.152-1.153H398.635a1.153,1.153,0,0,0-1.152,1.153v7.416a12.1,12.1,0,0,0,5,9.8h-4.734a1.152,1.152,0,0,0-1.152,1.152A1.529,1.529,0,0,0,398,794h23a1.378,1.378,0,0,0,1.247-1.308,1.152,1.152,0,0,0-1.152-1.152h-4.426a12.184,12.184,0,0,0,3.887-4.726h3.073A4.458,4.458,0,0,0,423.629,777.9Zm-4.262,3.843a9.79,9.79,0,1,1-19.58,0v-6.264h19.58v6.264Zm4.262,2.766h-2.277a12.129,12.129,0,0,0,.319-2.766v-1.537h1.958A2.152,2.152,0,0,1,423.629,784.508ZM409.577,769.6a1.153,1.153,0,0,0,1.152-1.153v-5.5a1.152,1.152,0,1,0-2.3,0v5.5A1.153,1.153,0,0,0,409.577,769.6Zm5.49,1.844a1.153,1.153,0,0,0,1.152-1.153v-5.5a1.152,1.152,0,1,0-2.3,0v5.5A1.152,1.152,0,0,0,415.067,771.444Zm-10.98,0a1.153,1.153,0,0,0,1.152-1.153v-5.5a1.152,1.152,0,1,0-2.3,0v5.5A1.153,1.153,0,0,0,404.087,771.444Z" transform="translate(-396.594 -761.812)"/></svg></div>
										<h4 class="gs-plugins--single_name">Buy Us A Coffee</h4>
										<div class="gs-plugins--single_details">
											<div class="gs-plugins--single_desc">We hope you are enjoying our plugin! We work hard to provide the best experience, we would love it if you could consider buying us a coffee as a way of saying thank you.</div>
											<a class="button button_color_three" href="https://www.paypal.com/donate/?hosted_button_id=K7K8YF4U3SCNQ" target="_blank">Donate Now</a>
										</div>
									</div>
								</div>
							</div>

							<div class="gs-plugins--single_wrapper">
								<div class="gs-plugins--single gs-plugin--color-five">
									<div class="gs-plugins--single_top">
										<div class="gs-plugins--single_icon"><svg xmlns="http://www.w3.org/2000/svg" width="35.125" height="35.12" viewBox="0 0 35.125 35.12"><path fill="#31d2f9" fill-rule="evenodd" d="M996.246,1076.31H994.5l-9.822-8.25a3.085,3.085,0,1,0-5.783,0l-9.822,8.25h-1.75a3.094,3.094,0,0,0-3.085,3.09v11.08a3.069,3.069,0,0,0,1.705,2.76c0.029,0.01.058,0.03,0.088,0.04L980,1098.7a0.018,0.018,0,0,0,.019.01,5.105,5.105,0,0,0,3.531,0,0.018,0.018,0,0,0,.019-0.01l13.971-5.42c0.03-.01.059-0.03,0.088-0.04a3.069,3.069,0,0,0,1.705-2.76V1079.4A3.093,3.093,0,0,0,996.246,1076.31Zm-14.464-10.36a1.03,1.03,0,1,1-1.029,1.03A1.029,1.029,0,0,1,981.782,1065.95Zm-1.572,3.69a3.088,3.088,0,0,0,3.144,0l7.943,6.67H972.266Zm17.064,20.84a1.018,1.018,0,0,1-.533.9l-13.908,5.4a3.039,3.039,0,0,1-2.1,0l-13.909-5.4a1.018,1.018,0,0,1-.533-0.9V1079.4a1.03,1.03,0,0,1,1.029-1.03h28.928a1.029,1.029,0,0,1,1.028,1.03v11.08Zm-21.662-10.05h-3.086a1.03,1.03,0,0,0,0,2.06h2.058v5.14a1.029,1.029,0,1,1-2.057,0,1.028,1.028,0,1,0-2.056,0,3.085,3.085,0,1,0,6.169,0v-6.17A1.028,1.028,0,0,0,975.612,1080.43Zm6.17,0a3.087,3.087,0,0,0-3.085,3.09v4.11a3.085,3.085,0,1,0,6.169,0v-4.11A3.086,3.086,0,0,0,981.782,1080.43Zm1.028,7.2a1.029,1.029,0,1,1-2.057,0v-4.11a1.029,1.029,0,1,1,2.057,0v4.11Zm7.2-7.2h-2.057a1.028,1.028,0,0,0-1.028,1.03v8.23a1.028,1.028,0,0,0,1.028,1.03h2.057a3.086,3.086,0,0,0,3.084-3.09,3.04,3.04,0,0,0-.788-2.05A3.089,3.089,0,0,0,990.008,1080.43Zm-1.029,2.06h1.029a1.03,1.03,0,0,1,0,2.06h-1.029v-2.06Zm1.029,6.17h-1.029v-2.06h1.029A1.03,1.03,0,0,1,990.008,1088.66Z" transform="translate(-964.219 -1063.91)"/></svg></div>
										<h4 class="gs-plugins--single_name">Hire Us For Work</h4>
										<div class="gs-plugins--single_details">
											<div class="gs-plugins--single_desc">We specialize in the WordPress industry, and we are always open to custom projects. Whether you have a specific project in mind or need guidance on improving the business, we are here to help.</div>
											<a class="button button_color_five" href="https://www.gsplugins.com/services/" target="_blank">Contact Us</a>
										</div>
									</div>
								</div>
							</div>
							
							<?php if ( ! empty( $this->data['links']['tutorial_link'] ) ) : ?>
							<div class="gs-plugins--single_wrapper">
								<div class="gs-plugins--single gs-plugin--color-four">
									<div class="gs-plugins--single_top">
										<div class="gs-plugins--single_icon"><svg xmlns="http://www.w3.org/2000/svg" width="36.5" height="21.38" viewBox="0 0 36.5 21.38"><path fill="#ed1c24" fill-rule="evenodd" d="M982.534,1383.57l-6.411-3.7a1.072,1.072,0,0,0-1.6.93v7.4a1.072,1.072,0,0,0,1.6.93l6.411-3.7a1.077,1.077,0,0,0,0-1.86h0Zm-5.877,2.78v-3.7l3.206,1.85Zm22.036-10.29-7.076,3.51v-4.69a1.07,1.07,0,0,0-1.069-1.07H964.832a1.07,1.07,0,0,0-1.068,1.07v19.24a1.07,1.07,0,0,0,1.068,1.07h25.716a1.07,1.07,0,0,0,1.069-1.07v-4.63l7.082,3.45a1.07,1.07,0,0,0,1.541-.96v-14.96a1.074,1.074,0,0,0-1.547-.96h0ZM965.9,1393.05v-17.1H989.48v17.1H965.9Zm32.2-2.78-6.482-3.16v-5.15l6.482-3.22v11.53Z" transform="translate(-963.75 -1373.81)"/></svg></div>
										<h4 class="gs-plugins--single_name">Watch Video Tutorials</h4>
										<div class="gs-plugins--single_details">
											<div class="gs-plugins--single_desc">If you have any questions or need assistance, we have a series of video tutorials that can help you learn & understand how to use our plugin to its full potential.</div>
											<a class="button button_color_six" href="<?php echo esc_url_raw( $this->data['links']['tutorial_link'] ); ?>" target="_blank">Watch Tutorials</a>
										</div>
									</div>
								</div>
							</div>
							<?php endif; ?>

						</div>
					</div>

				</div>

			</div>

			<?php

        }

    }
    
}