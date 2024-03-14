<?php

/**
 * The file that defines the functions to load demo contents and theme info
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://everestthemes.com
 * @since      1.0.0
 *
 * @package    Everest_Toolkit
 * @subpackage Everest_Toolkit/includes
 */

/**
 * Function that gets currently activated theme.
 */
if ( ! function_exists( 'everest_toolkit_theme' ) ) :

	function everest_toolkit_theme() {

		$theme = wp_get_theme();

		return $theme;
	}
endif;


/**
 * Function that gets text-domain of currently activated theme.
 */
if ( ! function_exists( 'everest_toolkit_theme_text_domain' ) ) :

	function everest_toolkit_theme_text_domain() {

		$theme = everest_toolkit_theme();

		$theme_text_domain = $theme->get( 'TextDomain' );

		return $theme_text_domain;
	}
endif;

/**
 * Function to load theme info
 */
if ( ! function_exists( 'everest_toolkit_theme_info_demo_loader' ) ) {

	function everest_toolkit_theme_info_demo_loader() {

		$theme_text_domain = everest_toolkit_theme_text_domain();

		if ( $theme_text_domain == 'everest-news' ) {

			require_once plugin_dir_path( __FILE__ ) . 'theme-info/configs/everest-news-config.php';

			add_action( 'admin_notices', 'everest_toolkit_admin_notice' );

			add_action( 'admin_init', 'everest_toolkit_notice_ignore' );
		}

		if ( $theme_text_domain == 'viable-blog' ) {

			require_once plugin_dir_path( __FILE__ ) . 'theme-info/configs/viable-blog-config.php';

			add_action( 'admin_notices', 'everest_toolkit_admin_notice' );

			add_action( 'admin_init', 'everest_toolkit_notice_ignore' );
		}

		if ( $theme_text_domain == 'mocho-blog' ) {

			require_once plugin_dir_path( __FILE__ ) . 'theme-info/configs/mocho-blog-config.php';

			add_action( 'admin_notices', 'everest_toolkit_admin_notice' );

			add_action( 'admin_init', 'everest_toolkit_notice_ignore' );
		}

		if ( $theme_text_domain == 'arya-multipurpose' ) {

			require_once plugin_dir_path( __FILE__ ) . 'theme-info/configs/arya-multipurpose-config.php';

			add_action( 'admin_notices', 'everest_toolkit_admin_notice' );

			add_action( 'admin_init', 'everest_toolkit_notice_ignore' );

			require_once plugin_dir_path( __FILE__ ) . 'elementor/arya-multipurpose/class-elementor-extension.php';
		}

		if ( $theme_text_domain == 'everest-news-lite' ) {

			require_once plugin_dir_path( __FILE__ ) . 'theme-info/configs/everest-news-lite-config.php';

			add_action( 'admin_notices', 'everest_toolkit_admin_notice' );

			add_action( 'admin_init', 'everest_toolkit_notice_ignore' );
		}

		if ( $theme_text_domain == 'ultra-lite-blog' ) {

			require_once plugin_dir_path( __FILE__ ) . 'theme-info/configs/ultra-lite-blog-config.php';

			add_action( 'admin_notices', 'everest_toolkit_admin_notice' );

			add_action( 'admin_init', 'everest_toolkit_notice_ignore' );
		}

		if ( $theme_text_domain == 'gucherry-blog' ) {

			require_once plugin_dir_path( __FILE__ ) . 'theme-info/configs/gucherry-blog-config.php';

			add_action( 'admin_notices', 'everest_toolkit_admin_notice' );

			add_action( 'admin_init', 'everest_toolkit_notice_ignore' );
		}

		if ( $theme_text_domain == 'gucherry-blog-pro' ) {

			require_once plugin_dir_path( __FILE__ ) . 'theme-info/configs/gucherry-blog-pro-config.php';

			add_action( 'admin_notices', 'everest_toolkit_admin_notice' );

			add_action( 'admin_init', 'everest_toolkit_notice_ignore' );
		}

		if ( $theme_text_domain == 'gucherry-lite' ) {

			require_once plugin_dir_path( __FILE__ ) . 'theme-info/configs/gucherry-lite-config.php';

			add_action( 'admin_notices', 'everest_toolkit_admin_notice' );

			add_action( 'admin_init', 'everest_toolkit_notice_ignore' );
		}

		if ( $theme_text_domain == 'grace-mag' ) {

			require_once plugin_dir_path( __FILE__ ) . 'theme-info/configs/grace-mag-config.php';

			add_action( 'admin_notices', 'everest_toolkit_admin_notice' );

			add_action( 'admin_init', 'everest_toolkit_notice_ignore' );
		}
		if ( $theme_text_domain == 'grace-mag-pro' ) {

			require_once plugin_dir_path( __FILE__ ) . 'theme-info/configs/grace-mag-pro-config.php';

			add_action( 'admin_notices', 'everest_toolkit_admin_notice' );

			add_action( 'admin_init', 'everest_toolkit_notice_ignore' );
		}
		if ( $theme_text_domain == 'influence-blog' ) {

			require_once plugin_dir_path( __FILE__ ) . 'theme-info/configs/influence-blog-config.php';

			add_action( 'admin_notices', 'everest_toolkit_admin_notice' );

			add_action( 'admin_init', 'everest_toolkit_notice_ignore' );
		}
		if ( $theme_text_domain == 'influence-blog-pro' ) {

			require_once plugin_dir_path( __FILE__ ) . 'theme-info/configs/influence-blog-pro-config.php';

			add_action( 'admin_notices', 'everest_toolkit_admin_notice' );

			add_action( 'admin_init', 'everest_toolkit_notice_ignore' );
		}
		if ( $theme_text_domain == 'sports-highlight' ) {

			require_once plugin_dir_path( __FILE__ ) . 'theme-info/configs/sports-highlight-config.php';

			add_action( 'admin_notices', 'everest_toolkit_admin_notice' );

			add_action( 'admin_init', 'everest_toolkit_notice_ignore' );
		}
	}
}

add_action( 'everest_toolkit_load_theme_info_demo', 'everest_toolkit_theme_info_demo_loader' );


/**
 * Add admin notice when active theme, just show one time
 *
 * @return bool|null
 */
if ( ! function_exists( 'everest_toolkit_admin_notice' ) ) {

	function everest_toolkit_admin_notice() {

		global $current_user;

		$user_id = $current_user->ID;

		$theme = everest_toolkit_theme();

		$theme_textDomain = $theme->get( 'TextDomain' );

		if ( $theme_textDomain === 'grace-mag' ) {

			$admin_url = 'themes.php?page=' . esc_html( 'welcome-page' );

		} else {

			$admin_url = 'themes.php?page=' . esc_html( $theme->get( 'TextDomain' ) ) . '-about';
		}

		if ( ! get_user_meta( $user_id, esc_html( $theme->get( 'TextDomain' ) ) . '_notice_ignore' ) ) {
			?>
			<div class="notice everest-toolkit-notice">

				<h1>
					<?php
					/* translators: %1$s: theme name, %2$s theme version */
					printf( esc_html__( 'Welcome to %1$s - Version %2$s', 'everest-toolkit' ), esc_html( $theme->Name ), esc_html( $theme->Version ) );
					?>
				</h1>

				<p>
					<?php
					/* translators: %1$s: theme name, %2$s link */
					printf( __( 'Welcome! Thank you for choosing %1$s! To fully take advantage of the best our theme can offer please make sure you visit our <a href="%2$s">Welcome page</a>', 'everest-toolkit' ), esc_html( $theme->Name ), esc_url( admin_url( $admin_url ) ) );
					printf( '<a href="%1$s" class="notice-dismiss dashicons dashicons-dismiss dashicons-dismiss-icon"></a>', '?' . esc_html( $theme->get( 'TextDomain' ) ) . '_notice_ignore=0' );
					?>
				</p>
				<p>
					<a href="<?php echo esc_url( admin_url( $admin_url ) ); ?>" class="button button-primary button-hero" style="text-decoration: none;">
						<?php
						/* translators: %s theme name */
						printf( esc_html__( 'Get started with %s', 'everest-toolkit' ), esc_html( $theme->Name ) )
						?>
					</a>
				</p>
			</div>
			<?php
		}
	}
}


if ( ! function_exists( 'everest_toolkit_notice_ignore' ) ) {

	function everest_toolkit_notice_ignore() {

		global $current_user;

		$theme = everest_toolkit_theme();

		$user_id = $current_user->ID;

		/* If user clicks to ignore the notice, add that to their user meta */
		if ( isset( $_GET[ esc_html( $theme->get( 'TextDomain' ) ) . '_notice_ignore' ] ) && '0' == $_GET[ esc_html( $theme->get( 'TextDomain' ) ) . '_notice_ignore' ] ) {
			add_user_meta( $user_id, esc_html( $theme->get( 'TextDomain' ) ) . '_notice_ignore', 'true', true );
		}
	}
}

/**
 * Function that gets list of all themes.
 */
if ( ! function_exists( 'everest_toolkit_theme_array' ) ) :

	function everest_toolkit_theme_array() {

		return array( 'sports-highlight', 'viable-blog', 'viable-blog-pro', 'mocho-blog', 'mocho-blog-pro', 'everest-news', 'everest-news-pro', 'arya-multipurpose', 'arya-multipurpose-pro', 'everest-news-lite', 'ultra-lite-blog', 'gucherry-blog', 'gucherry-blog-pro', 'gucherry-lite', 'grace-mag', 'grace-mag-pro', 'influence-blog', 'influence-blog-pro' );
	}
endif;
