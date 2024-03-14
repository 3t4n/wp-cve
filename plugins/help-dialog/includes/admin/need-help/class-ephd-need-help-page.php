<?php  if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Display Need Help? admin page
 *
 * @copyright   Copyright (C) 2018, Echo Plugins
 * @license http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */
class EPHD_Need_Help_Page {

	private $widget_url = '';

	/**
	 * Display Need Help page
	 */
	public function display_need_help_page() {

		if ( ! current_user_can( EPHD_Admin_UI_Access::get_context_required_capability( ['admin_ephd_access_admin_pages_read'] ) ) ) {
			echo '<p>' . esc_html__( 'You do not have permission to edit Help Dialog.', 'help-dialog' ) . '</p>';
			return;
		}

		$admin_page_views = $this->get_regular_views_config();

		EPHD_HTML_Admin::admin_page_css_missing_message( true );   ?>

		<div id="ephd-admin-page-wrap">

			<div class="ephd-get-started-page-container">				<?php

				/**
				 * ADMIN HEADER (HD logo and list of HDs dropdown)
				 */
				EPHD_HTML_Admin::admin_header();

				/**
				 * ADMIN TOOLBAR
				 */
				EPHD_HTML_Admin::admin_toolbar( $admin_page_views );

				/**
				 * ADMIN SECONDARY TABS
				 */
				EPHD_HTML_Admin::admin_secondary_tabs( $admin_page_views );

				/**
				 * LIST OF SETTINGS IN TABS
				 */
				EPHD_HTML_Admin::admin_settings_tab_content( $admin_page_views );    ?>

				<div class="ephd-bottom-notice-message"></div>

			</div>

		</div>	    <?php
	}

	/**
	 * Get configuration for regular views
	 *
	 * @return array
	 */
	private function get_regular_views_config() {

		return array(

			// VIEW: Getting Started
			array(

				// Shared
				'active' => true,
				'list_key' => 'getting-started',

				// Top Panel Item
				'label_text' => __( 'Get Started', 'help-dialog' ),
				'icon_class' => 'ephdfa ephdfa-play',

				// Boxes List
				'boxes_list' => array(

					// Box: Getting Started
					array(
						'html' => $this->getting_started_tab(),
					),
				),
			),

			// VIEW: Features
			EPHD_Need_Help_Features::get_page_view_config(),

			// VIEW: Our Free Plugins
			array(

				// Shared
				'list_key' => 'our-free-plugins',

				// Top Panel Item
				'label_text' => __( 'Our Free Plugins', 'help-dialog' ),
				'icon_class' => 'ephdfa ephdfa-download',

				// Boxes List
				'boxes_list' => self::get_our_free_plugins_boxes(),
			),

			// VIEW: Contact Us
			EPHD_Need_Help_Contact_Us::get_page_view_config(),
		);
	}

	/**
	 * Get content for Getting Started tab
	 *
	 * @return false|string
	 */
	private function getting_started_tab() {

		foreach ( ephd_get_instance()->widgets_config_obj->get_config() as $widget ) {

			$this->widget_url = EPHD_Core_Utilities::get_first_widget_page_url( $widget );
			if ( empty( $this->widget_url ) ) {
				continue;
			}
			break;
		}

		ob_start();     ?>

		<div class="ephd-nh__getting-started-container">

            <!-- header -->
            <div class="ephd-nh__gs__header-container">

                <div class="ephd-nh__header__img">
                    <img src="<?php echo esc_url( Echo_Help_Dialog::$plugin_url . 'img/need-help/HD-Banner-1000x324.jpg' ); ?>">
                </div>

                <div class="ephd-nh__header__text">
                    <h2 class="ephd-nh__header__title"><?php esc_html_e( 'Welcome to Help Dialog Chat!', 'help-dialog' ); ?></h2>
	                <p class="ephd-nh__header__desc"><?php esc_html_e( 'Choose pages to show Help Dialog widgets and define the FAQs for each page.', 'help-dialog' ); ?></p>
                </div>

				<div class="ephd-nh__gs__body-container">
	            <?php
	            // <!-- Body -->
	            EPHD_HTML_Forms::call_to_action_box( array(
		            'container_class'   => '',
		            'style' => 'style-1',
		            'title'         => __( 'Step 1: View Help Dialog', 'help-dialog' ),
		            'content'       => '<p>' . __( 'Take a look at the website\'s front end to view how the help dialog has been set up initially.', 'help-dialog' ) . '</p>',
		            'btn_text'      => __( 'View My Help Dialog', 'help-dialog' ),
		            'btn_url'       => esc_url( $this->widget_url ),
		            'btn_target'    => !empty($this->widget_url) ? '__blank' : '',
	            ) );
	            EPHD_HTML_Forms::call_to_action_box( array(
		            'container_class'   => '',
		            'style' => 'style-1',
		            'title'         => __( 'Step 2: Configure Widgets', 'help-dialog' ),
		            'content'       => '<p>' . __( 'We can help you find it or add it to our road map if it is missing.', 'help-dialog' ) . '</p>',
		            'btn_text'      => __( 'Configure', 'help-dialog' ),
		            'btn_url'       => 'https://www.helpdialog.com/contact-us/feature-request/',
		            'btn_target'    => '__blank',
	            ) );
				if ( current_user_can( EPHD_Admin_UI_Access::EPHD_ADMIN_CAPABILITY ) ) {
					EPHD_HTML_Forms::call_to_action_box(array(
						'container_class' => '',
						'style' => 'style-1',
						'title' => __('Step 3: Configure Access', 'help-dialog'),
						'content' => '<p>' . __('We can help you find it or add it to our road map if it is missing.', 'help-dialog') . '</p>',
						'btn_text' => __('Menu Access Control', 'help-dialog'),
						'btn_url' => esc_url( admin_url( 'admin.php?page=ephd-help-dialog-advanced-config#settings' ) ),
						'btn_target' => '__blank',
					));
				} ?>
				</div>
            </div>
			
	            <?php



	            /** EPHD_HTML_Forms::display_step_cta_box( array(
		            //	'content_icon_class'    => empty( $ran_setup_wizard ) ? '' : 'ephdfa ephdfa-check-circle',
		            'icon_img_url'          => 'img/need-help/rocket-2.jpg',
		            'title'                 => '1. ' . __( 'Manage Help Dialog Widgets', 'help-dialog' ),
		            'desc'                  => __( 'Create and update Help Dialog widgets. Each widget is shown on web pages you select.', 'help-dialog' ),
		            'html'                  => EPHD_Core_Utilities::get_admin_page_link( 'page=ephd-help-dialog-widgets', __( 'Manage Widgets', 'help-dialog' ), false ) ) );

	            EPHD_HTML_Forms::display_step_cta_box( array(
		            //	'content_icon_class'    => 'ephdfa ephdfa-check-circle',
		            'icon_img_url'          => 'img/need-help/q-and-a.jpg',
		            'title'                 => '2. ' . __( 'Prepare FAQs For Your Widgets', 'help-dialog' ),
		            'desc'                  => __( 'Create dedicated questions for certain pages. Use generic FAQs for the rest.', 'help-dialog' ),
		            'html'                  => EPHD_Core_Utilities::get_admin_page_link( 'page=ephd-help-dialog-faqs#faqs', __( 'Create FAQs', 'help-dialog' ), false ) ) );    ?>
				*/


				?>

		</div>		<?php

		return ob_get_clean();
	}

	/**
	 * Get Our Free Plugins boxes
	 *
	 * @return array[]
	 */
	private static function get_our_free_plugins_boxes() {

		if ( ! function_exists( 'plugins_api' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
		}

		remove_all_filters( 'plugins_api' );

		$our_free_plugins = array();

		$args_list = array(
			array( 'slug' => 'echo-knowledge-base' ),
			array( 'slug' => 'creative-addons-for-elementor' ),
			array( 'slug' => 'echo-show-ids' ),
		);

		foreach( $args_list as $args ) {
			$args['fields'] = [
				'short_description' => true,
				'icons'             => true,
				'reviews'           => false,
				'banners'           => true,
			];
			$plugin_data = plugins_api( 'plugin_information', $args );
			if ( $plugin_data && ! is_wp_error( $plugin_data ) ) {
				$our_free_plugins[] = $plugin_data;
			}
		}

		ob_start(); ?>
        <div class="wrap recommended-plugins">
            <div class="wp-list-table widefat plugin-install">
                <div class="the-list">  <?php

					foreach( $our_free_plugins as $plugin ) {
						self::display_our_free_plugin_box_html( $plugin );
					}   ?>

                </div>
            </div>
        </div>  <?php

		$boxes_html = ob_get_clean();

		return array(
			array(
				'html' => $boxes_html,
			) );
	}

	/**
	 * Return HTML for a single box on Our Free Plugins tab
	 *
	 * @param $plugin
	 */
	private static function display_our_free_plugin_box_html( $plugin ) {

		$plugins_allowed_tags = array(
			'a'       => array(
				'href'   => array(),
				'title'  => array(),
				'target' => array(),
			),
			'abbr'    => array( 'title' => array() ),
			'acronym' => array( 'title' => array() ),
			'code'    => array(),
			'pre'     => array(),
			'em'      => array(),
			'strong'  => array(),
			'ul'      => array(),
			'ol'      => array(),
			'li'      => array(),
			'p'       => array(),
			'br'      => array(),
		);

		if ( is_object( $plugin ) ) {
			$plugin = (array) $plugin;
		}

		$title = wp_kses( $plugin['name'], $plugins_allowed_tags );

		// remove any HTML from the description.
		$description = strip_tags( $plugin['short_description'] );
		$version = wp_kses( $plugin['version'], $plugins_allowed_tags );

		$name = strip_tags( $title . ' ' . $version );

		$author = wp_kses( $plugin['author'], $plugins_allowed_tags );
		if ( ! empty( $author ) ) {
			/* translators: %s: Plugin author. */
			$author = ' <cite>' . sprintf( __( 'By %s' ), $author ) . '</cite>';
		}

		$requires_php = isset( $plugin['requires_php'] ) ? $plugin['requires_php'] : null;
		$requires_wp  = isset( $plugin['requires'] ) ? $plugin['requires'] : null;

		$compatible_php = is_php_version_compatible( $requires_php );
		$compatible_wp  = is_wp_version_compatible( $requires_wp );
		$tested_wp = empty( $plugin['tested'] ) || version_compare( get_bloginfo( 'version' ), $plugin['tested'], '<=' );

		$details_link = self_admin_url(
			'plugin-install.php?tab=plugin-information&amp;plugin=' . $plugin['slug'] .
			'&amp;TB_iframe=true&amp;width=600&amp;height=550'
		);

		$action_links = self::get_our_free_plugin_action_links( $plugin, $name, $compatible_php, $compatible_wp );

		$action_links[] = sprintf(
			'<a href="%s" class="thickbox open-plugin-details-modal" aria-label="%s" data-title="%s">%s</a>',
			esc_url( $details_link ),
			/* translators: %s: Plugin name and version. */
			esc_attr( sprintf( __( 'More information about %s' ), $name ) ),
			esc_attr( $name ),
			__( 'More Details' )
		);

		if ( ! empty( $plugin['icons']['svg'] ) ) {
			$plugin_icon_url = $plugin['icons']['svg'];
		} elseif ( ! empty( $plugin['icons']['2x'] ) ) {
			$plugin_icon_url = $plugin['icons']['2x'];
		} elseif ( ! empty( $plugin['icons']['1x'] ) ) {
			$plugin_icon_url = $plugin['icons']['1x'];
		} else {
			$plugin_icon_url = $plugin['icons']['default'];
		}

		$action_links = apply_filters( 'plugin_install_action_links', $action_links, $plugin );

		// open 'More Details' link in new tab
		if ( $action_links ) {
			foreach ( $action_links as $key => $link ) {
				if ( strpos( $link, 'tab=plugin-information' ) === false ) {
					continue;
				}
				$action_links[$key] = str_replace( '<a ', '<a target="_blank" ', $link );
			}
		}

		$last_updated_timestamp = strtotime( $plugin['last_updated'] ); ?>

        <div class="plugin-card plugin-card-<?php echo sanitize_html_class( $plugin['slug'] ); ?>"> <?php

			self::display_our_free_plugin_incompatible_links( $compatible_php, $compatible_wp );  ?>

            <div class="plugin-card-top">
                <div class="name column-name">
                    <h3>
                        <a href="<?php echo esc_url( $details_link ); ?>" class="thickbox open-plugin-details-modal">
							<?php echo esc_attr( $title ); ?>
                            <img src="<?php echo esc_attr( $plugin_icon_url ); ?>" class="plugin-icon" alt="" />
                        </a>
                    </h3>
                </div>
                <div class="action-links">  <?php
					if ( $action_links ) {  ?>
                        <ul class="plugin-action-buttons"><li><?php echo implode( '</li><li>', $action_links ); ?></li></ul>   <?php
					}   ?>
                </div>
                <div class="desc column-description">
                    <p><?php echo esc_html( $description ); ?></p>
                    <p class="authors"><?php echo wp_kses( $author, $plugins_allowed_tags ); ?></p>
                </div>
            </div>

            <div class="plugin-card-bottom">
                <div class="vers column-rating">    <?php
					wp_star_rating(
						array(
							'rating' => $plugin['rating'],
							'type'   => 'percent',
							'number' => $plugin['num_ratings'],
						)
					);  ?>
                    <span class="num-ratings" aria-hidden="true">(<?php echo number_format_i18n( $plugin['num_ratings'] ); ?>)</span>
                </div>
                <div class="column-updated">
                    <strong><?php _e( 'Last Updated:' ); ?></strong>    <?php
					/* translators: %s: Human-readable time difference. */
					printf( __( '%s ago' ), human_time_diff( $last_updated_timestamp ) );   ?>
                </div>
                <div class="column-downloaded"> <?php
					if ( $plugin['active_installs'] >= 1000000 ) {
						$active_installs_millions = floor( $plugin['active_installs'] / 1000000 );
						$active_installs_text     = sprintf(
						/* translators: %s: Number of millions. */
							_nx( '%s+ Million', '%s+ Million', $active_installs_millions, 'Active plugin installations' ),
							number_format_i18n( $active_installs_millions )
						);
					} elseif ( 0 == $plugin['active_installs'] ) {
						$active_installs_text = _x( 'Less Than 10', 'Active plugin installations' );
					} else {
						$active_installs_text = number_format_i18n( $plugin['active_installs'] ) . '+';
					}
					/* translators: %s: Number of installations. */
					printf( __( '%s Active Installations' ), $active_installs_text );   ?>
                </div>
                <div class="column-compatibility">  <?php
					if ( ! $tested_wp ) {   ?>
                        <span class="compatibility-untested"><?php _e( 'Untested with your version of WordPress' ); ?></span>   <?php
					} elseif ( ! $compatible_wp ) { ?>
                        <span class="compatibility-incompatible"><?php _e( '<strong>Incompatible</strong> with your version of WordPress' ); ?></span>   <?php
					} else {    ?>
                        <span class="compatibility-compatible"><?php _e( '<strong>Compatible</strong> with your version of WordPress' ); ?></span>   <?php
					}   ?>
                </div>
            </div>
        </div>  <?php
	}

	/**
	 * Display links in case if suggested plugin is incompatible with current WordPress or PHP version
	 *
	 * @param $compatible_php
	 * @param $compatible_wp
	 */
	private static function display_our_free_plugin_incompatible_links( $compatible_php, $compatible_wp ) {

		if ( $compatible_php && $compatible_wp ) {
			return;
		}   ?>

        <div class="notice inline notice-error notice-alt"><p>  <?php

				if ( ! $compatible_php && ! $compatible_wp ) {
					_e( 'This plugin doesn&#8217;t work with your versions of WordPress and PHP.' );
					if ( current_user_can( 'update_core' ) && current_user_can( 'update_php' ) ) {
						/* translators: 1: URL to WordPress Updates screen, 2: URL to Update PHP page. */
						printf(
							' ' . __( '<a href="%1$s">Please update WordPress</a>, and then <a href="%2$s">learn more about updating PHP</a>.' ),
							self_admin_url( 'update-core.php' ),
							esc_url( wp_get_update_php_url() )
						);
						wp_update_php_annotation( '</p><p><em>', '</em>' );
					} elseif ( current_user_can( 'update_core' ) ) {
						printf(
						/* translators: %s: URL to WordPress Updates screen. */
							' ' . __( '<a href="%s">Please update WordPress</a>.' ),
							self_admin_url( 'update-core.php' )
						);
					} elseif ( current_user_can( 'update_php' ) ) {
						printf(
						/* translators: %s: URL to Update PHP page. */
							' ' . __( '<a href="%s">Learn more about updating PHP</a>.' ),
							esc_url( wp_get_update_php_url() )
						);
						wp_update_php_annotation( '</p><p><em>', '</em>' );
					}
				} elseif ( ! $compatible_wp ) {
					_e( 'This plugin doesn&#8217;t work with your version of WordPress.' );
					if ( current_user_can( 'update_core' ) ) {
						printf(
						/* translators: %s: URL to WordPress Updates screen. */
							' ' . __( '<a href="%s">Please update WordPress</a>.' ),
							self_admin_url( 'update-core.php' )
						);
					}
				} elseif ( ! $compatible_php ) {
					__( 'This plugin doesn&#8217;t work with your version of PHP.' );
					if ( current_user_can( 'update_php' ) ) {
						printf(
						/* translators: %s: URL to Update PHP page. */
							' ' . __( '<a href="%s">Learn more about updating PHP</a>.' ),
							esc_url( wp_get_update_php_url() )
						);
						wp_update_php_annotation( '</p><p><em>', '</em>' );
					}
				}   ?>

            </p></div>  <?php
	}

	/**
	 * Get action links for single plugin in Our Free Plugins list
	 *
	 * @param $plugin
	 * @param $name
	 * @param $compatible_php
	 * @param $compatible_wp
	 * @return array
	 */
	private static function get_our_free_plugin_action_links( $plugin, $name, $compatible_php, $compatible_wp ) {

		$action_links = [];

		if ( ! current_user_can( 'install_plugins' ) && ! current_user_can( 'update_plugins' ) ) {
			return $action_links;
		}

		$status = install_plugin_install_status( $plugin );

		// not installed
		if ( $status['status'] == 'install' && $status['url'] ) {

			$action_links[] = $compatible_php && $compatible_wp
				? sprintf(
					'<a class="install-now button" data-slug="%s" href="%s" aria-label="%s" data-name="%s">%s</a>',
					esc_attr( $plugin['slug'] ),
					esc_url( $status['url'] ),
					/* translators: %s: Plugin name and version. */
					esc_attr( sprintf( _x( 'Install %s now', 'plugin' ), $name ) ),
					esc_attr( $name ),
					__( 'Install Now' ) )
				: sprintf(
					'<button type="button" class="button button-disabled" disabled="disabled">%s</button>',
					_x( 'Cannot Install', 'plugin' ) );
		}

		// update is available
		if ( $status['status'] == 'update_available' && $status['url'] ) {

			$action_links[] = $compatible_php && $compatible_wp
				? sprintf(
					'<a class="update-now button aria-button-if-js" data-plugin="%s" data-slug="%s" href="%s" aria-label="%s" data-name="%s">%s</a>',
					esc_attr( $status['file'] ),
					esc_attr( $plugin['slug'] ),
					esc_url( $status['url'] ),
					/* translators: %s: Plugin name and version. */
					esc_attr( sprintf( _x( 'Update %s now', 'plugin' ), $name ) ),
					esc_attr( $name ),
					__( 'Update Now' ) )
				: sprintf(
					'<button type="button" class="button button-disabled" disabled="disabled">%s</button>',
					_x( 'Cannot Update', 'plugin' ) );
		}

		// installed
		if ( $status['status'] == 'latest_installed' || $status['status'] == 'newer_installed' ) {

			if ( is_plugin_active( $status['file'] ) ) {
				$action_links[] = sprintf(
					'<button type="button" class="button button-disabled" disabled="disabled">%s</button>',
					_x( 'Active', 'plugin' )
				);

			} elseif ( current_user_can( 'activate_plugin', $status['file'] ) ) {
				$button_text = __( 'Activate' );
				/* translators: %s: Plugin name. */
				$button_label = _x( 'Activate %s', 'plugin' );
				$activate_url = add_query_arg(
					array(
						'_wpnonce' => wp_create_nonce( 'activate-plugin_' . $status['file'] ),
						'action'   => 'activate',
						'plugin'   => $status['file'],
					),
					network_admin_url( 'plugins.php' )
				);

				if ( is_network_admin() ) {
					$button_text = __( 'Network Activate' );
					/* translators: %s: Plugin name. */
					$button_label = _x( 'Network Activate %s', 'plugin' );
					$activate_url = add_query_arg( array( 'networkwide' => 1 ), $activate_url );
				}

				$action_links[] = sprintf(
					'<a href="%1$s" class="button activate-now" aria-label="%2$s">%3$s</a>',
					esc_url( $activate_url ),
					esc_attr( sprintf( $button_label, $plugin['name'] ) ),
					$button_text
				);

			} else {
				$action_links[] = sprintf(
					'<button type="button" class="button button-disabled" disabled="disabled">%s</button>',
					_x( 'Installed', 'plugin' )
				);
			}
		}

		return $action_links;
	}

}
