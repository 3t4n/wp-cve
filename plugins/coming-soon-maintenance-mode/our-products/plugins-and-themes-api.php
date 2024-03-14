<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>



<div class="container-fluid">

	<div class="avantex-extras-page shadow-lg bg-light h-100 p-3">
		<h1>Our Free Themes and Plugins</h1>
		<p>Thanks for choosing us. Please have a look at our various builds!</p>
	</div>

	<div class="avantex-extras-title mt-3 mb-0">
		<h1>Plugins</h1>
	</div>
	<div class="row row-cols-1 row-cols-md-4 justify-content-center gap-2">
		<?php
		$plugin_button      = '';
		$frank_plugin_slugs = array( 
			'ultimate-portfolio', 'customizer-login-page', 'testimonial-maker', 
			'weather-effect', 'responsive-slider-gallery', 'team-builder-member-showcase',
			'modal-popup-box', 'wp-instagram-feed-awplife', 'abc-pricing-table',
			//'wp-flickr-gallery', 'new-album-gallery', 'blog-filter', 'event-monster', 'new-grid-gallery',
			//'new-image-gallery', 'media-slider', 'new-photo-gallery', 
			//  'slider-responsive-slideshow',
			// 'new-video-gallery', 'portfolio-filter-gallery',
		);

		// Required Info to Fetch Plugin Data.
		require ABSPATH . 'wp-admin/includes/plugin-install.php';
		require ABSPATH . 'wp-admin/includes/theme-install.php';

		foreach ( $frank_plugin_slugs as $plugin_slug ) {
			// Define the arguments for the plugins_api function.
			$args = array(
				'slug'   => $plugin_slug,
				'fields' => array(
					'name'              => true,
					'short_description' => true,
					'active_installs'   => true,
					'icons'             => true,
					'last_updated'      => true,
					'num_ratings'       => true,
					'rating'            => true,
					'ratings'           => true,
					'screenshots'       => true,
					'slug'              => true,
					'version'           => true,
					'versions'          => true,
					'downloaded'        => true,
				),
			);

			// Fetch plugin data using plugins_api.
			$extras_info = plugins_api( 'plugin_information', $args );

			// Proceed if plugin data is available.
			if ( ! is_wp_error( $extras_info ) ) {
				$extras_name              = $extras_info->name;
				$extras_rating            = $extras_info->rating;
				$extras_installs          = $extras_info->active_installs;
				$extras_last_updated      = $extras_info->last_updated;
				$extras_icons             = $extras_info->icons;
				$extras_author            = $extras_info->author;
				$extras_ratings           = $extras_info->ratings;
				$extras_downloaded        = $extras_info->downloaded;
				$extras_short_description = $extras_info->short_description;

				// Fetch image source and name from icons.
				$src = isset( $extras_icons['2x'] ) ? $extras_icons['2x'] : ( isset( $extras_icons['1x'] ) ? $extras_icons['1x'] : '' );
				$alt = esc_html( $extras_name ) . ( isset( $extras_icons['2x'] ) ? ' 2x' : ( isset( $extras_icons['1x'] ) ? ' 1x' : '' ) );

				// Iterate through the ratings and construct the star rating HTML.
				// Calculate the average rating.
				$total_ratings = 0;
				$total_count   = 0;

				foreach ( $extras_ratings as $rating => $count ) {
					$total_ratings += ( $rating * $count );
					$total_count   += $count;
				}

				$average_rating = round( $total_count > 0 ? $total_ratings / $total_count : 0 );

				// Determine the number of filled stars and half star based on the average rating.
				$filled_stars = floor( $average_rating );
				$half_star    = round( $average_rating - $filled_stars, 1 ) >= 0.5;

				// Construct the star rating HTML.
				$star_rating_html = '<div class="wporg-ratings" title="' . $average_rating . ' out of 5 stars" style="color:#ffb900;">';

				// Filled stars.
				for ( $i = 0; $i < $filled_stars; $i++ ) {
					$star_rating_html .= '<span class="dashicons dashicons-star-filled"></span>';
				}

				// Half star.
				if ( $half_star ) {
					$star_rating_html .= '<span class="dashicons dashicons-star-half"></span>';
				}

				// Empty stars.
				for ( $i = 0; $i < 5 - $filled_stars - $half_star; $i++ ) {
					$star_rating_html .= '<span class="dashicons dashicons-star-empty"></span>';
				}

				$star_rating_html .= '</div>';
				// Finished Stars.

				if ( $plugin_slug === 'slider-factory' ) {
					// Trim the name to 7 words.
					$name_words  = explode( ' ', $extras_name );
					$extras_name = implode( ' ', array_slice( $name_words, 0, 2 ) );
				}


				/** Install Update Button */
				// Set the plugin slug and other installation information.
				$plugin_info = $extras_info;

				// Check if the plugin information is available.
				if ( ! is_wp_error( $plugin_info ) ) {
					// Check the installation status.
					$plugin_install_status = install_plugin_install_status( $plugin_info );

					// Generate installation link based on the installation status.
					switch ( $plugin_install_status['status'] ) {
						case 'install':
							$plugin_button = '<button href="#" class="btn btn-lg btn-lg btn-dark" onclick="extrasAjaxRequest(\'' . esc_html( $plugin_slug ) . '\', \'extras_plugin_install\', this)">Install Now</button>';
							break;
						case 'update_available':
							$plugin_button = '<button href="#" class="btn btn-lg btn-dark" onclick="extrasAjaxRequest(\'' . esc_html( $plugin_slug ) . '\', \'extras_plugin_update\', this)">Update Now</button>';
							break;
						case 'latest_installed':
						case 'newer_installed':
							// Plugin is already installed and up to date.
							$plugin_name = $plugin_slug . '/' . $plugin_slug . '.php';
							if ( is_plugin_active( $plugin_name ) ) {
								$plugin_button = '<button type="button" class="btn btn-lg btn-success" disabled>Installed/Activated</button>';
							} else {
								$plugin_button = '<button href="#" class="btn btn-lg btn-dark" onclick="extrasAjaxRequest(\'' . esc_html( $plugin_slug ) . '\', \'extras_plugin_activate\', this)">Activate Now</button>';
							}
							break;
					}
				}
				/** End Install Update Button */
				?>
				<div class="col">
					<div class="card shadow-lg bg-light h-100">
					<img class="img-thumbnail rounded m-5 p-5" src="<?php echo esc_url( $src ); ?>" alt="<?php echo esc_attr( $alt ); ?>">
						<div class="card-body">
							<h5 class="card-title"><?php echo esc_html( $extras_name ); ?></h5>
							<?php echo wp_kses_post( $star_rating_html ); ?>
							<p class="card-text card-formating"><?php echo esc_html( $extras_short_description ); ?></p>
							<p class="card-text card-formating"><b>Total Downloads :</b> <?php echo esc_html( number_format_i18n( $extras_downloaded ) ); ?>+</p>
							<!--<p class="card-text card-formating"><b>Author :</b> <?php //echo wp_kses_post( $extras_author, ); ?></p>-->
						</div>
						<?php
						$allowed_tags = array(
							'button' => array(
								'href'    => array(),
								'onclick' => array(),
								'class'   => array(),
							),
						);
						?>
						<p class="card-text text-center mt-1"> <?php echo wp_kses( $plugin_button, $allowed_tags ); ?></p>
						<div class="card-footer">
							<small class="text-muted">Last Updated: <?php echo esc_html( $extras_last_updated ); ?></small>
						</div>
					</div>
				</div>
				<?php
			} else {
				echo 'Failed to fetch plugin information for ' . esc_html( $plugin_slug ) . '<br>';
			}
			?>

			<?php
		};
		?>
	</div>
	<br>
	<div class="avantex-extras-title mt-5 mb-0">
		<h1>Themes</h1>
	</div>
	<div class="row row-cols-1 row-cols-md-4 justify-content-center gap-2">
		<?php
		// Plugins Loop End.

		// Themes Start.
		$theme_button      = '';
		$frank_theme_slugs = array( 
		'webenvo', 'metaverse', 'medihealth',
		'formula', 'formula-dark', 'formula-light',
		'medical-formula', 'business-campaign', 'cryptocurrency-exchange',
		//'nature-formula', 'home-interior', 'education-formula', 'dental-hospital', 
		//'hospital-health-care', 'awp-marketing-agency', 'aneeq', 'dental-hospital', 'awpbusinesspress', 
		//'bloglane', 'blush', 'business-campaign',  'timelineblog', 'coin-market', 
		//'cryptostore', 'newsstreet', 'business-blogs', 'daron'
		);
		// Fetch theme data.
		foreach ( $frank_theme_slugs as $theme_slug ) {
			$thm_args = array(
				'slug'   => $theme_slug,
				'fields' => array(
					'name'            => true,
					'downloaded'      => true,
					'theme_url'       => true,
					'active_installs' => true,
					'screenshot_url'  => true,
					'last_updated'    => true,
					'num_ratings'     => true,
					'rating'          => true,
					'ratings'         => true,
					'version'         => true,
					'preview_url'     => true,
					'parent'          => true,
					'author'          => true,
					'sections'        => true,
				),
			);

			$thm_extras_info = themes_api( 'theme_information', $thm_args );

			// Proceed if theme data is available.
			if ( ! is_wp_error( $thm_extras_info ) ) {
				$thm_name           = $thm_extras_info->name;
				$thm_downloaded     = $thm_extras_info->downloaded;
				$thm_theme_url      = $thm_extras_info->theme_url;
				$thm_installs       = $thm_extras_info->active_installs;
				$thm_screenshot_url = $thm_extras_info->screenshot_url;
				$thm_last_updated   = $thm_extras_info->last_updated;
				$thm_num_ratings    = $thm_extras_info->num_ratings;
				$thm_rating         = $thm_extras_info->rating;
				$thm_ratings        = $thm_extras_info->ratings;
				$thm_version        = $thm_extras_info->version;
				$thm_preview_url    = $thm_extras_info->preview_url;
				$thm_author         = $thm_extras_info->author;
				// var_dump( $thm_preview_url  );
				if ( isset( $thm_extras_info->parent ) ) {
					// Parent property exists.
					$thm_parent = $thm_extras_info->parent;
				}

				// Iterate through the ratings and construct the star rating HTML.
				// Calculate the average rating.
				$total_ratings = 0;
				$total_count   = 0;

				foreach ( $thm_ratings as $rating => $count ) {
					$total_ratings += ( $rating * $count );
					$total_count   += $count;
				}

				$average_rating = $total_count > 0 ? $total_ratings / $total_count : 0;

				// Determine the number of filled stars and half star based on the average rating.
				$filled_stars = floor( $average_rating );
				$half_star    = round( $average_rating - $filled_stars, 1 ) >= 0.5;

				// Construct the star rating HTML.
				$star_rating_html = '<div class="wporg-ratings" title="' . $average_rating . ' out of 5 stars" style="color:#ffb900;">';

				// Filled stars.
				for ( $i = 0; $i < $filled_stars; $i++ ) {
					$star_rating_html .= '<span class="dashicons dashicons-star-filled"></span>';
				}

				// Half star.
				if ( $half_star ) {
					$star_rating_html .= '<span class="dashicons dashicons-star-half"></span>';
				}

				// Empty stars.
				for ( $i = 0; $i < 5 - $filled_stars - $half_star; $i++ ) {
					$star_rating_html .= '<span class="dashicons dashicons-star-empty"></span>';
				}

				$star_rating_html .= '</div>';
				// Finished Stars.

				// Get the theme information.
				$theme_info = wp_get_theme( $theme_slug );

				// Check if the theme directory exists.
				$is_installed = is_dir( get_theme_root() . '/' . $theme_slug );

				// Check if the theme information is available and the theme is installed.
				if ( $theme_info->exists() && $is_installed ) {
					$current_theme   = wp_get_theme();
					$is_active       = $current_theme->get_stylesheet() === $theme_info->get_stylesheet();
					$current_version = $theme_info->get( 'Version' );
					$latest_version  = $thm_version;

					// Generate buttons based on the installation, update, and activation status.
					if ( version_compare( $current_version, $latest_version, '<' ) ) {
						$theme_button = '<button href="#" class="btn btn-dark" onclick="extrasAjaxRequest(\'' . esc_html( $theme_slug ) . '\', \'extras_theme_update\', this)">Update Now</button>';
					} elseif ( $is_active ) {
						$theme_button = '<button type="button" class="btn btn-success" disabled>Active</button>';
					} else {
						$theme_button = '<a href="' . esc_url( admin_url( 'themes.php' ) ) . '" class="btn btn-dark">Activate Now</a>';
					}
				} else {
					$theme_button = '<button href="#" class="btn btn-dark" onclick="extrasAjaxRequest(\'' . esc_html( $theme_slug ) . '\', \'extras_theme_install\', this)">Install Free</button>';
				}
				// Construct the theme card HTML.
				?>
				<div class="col">
					<div class="card shadow-lg bg-light h-100">
						<img class="card-img-top rounded" src="<?php echo esc_url( $thm_screenshot_url ); ?>" alt="<?php echo esc_attr( $thm_name ); ?>">
						<div class="card-body">
							<h5 class="card-title"><?php echo esc_html( $thm_name ); ?></h5>
							<?php //if ( ! empty( $thm_parent ) ) { ?>
								<!--<p class="card-text card-formating"><b>Child theme of : </b><?php echo esc_html( $thm_parent['name'] ); ?></p>-->
							<?php //} ?>
							<?php echo wp_kses_post( $star_rating_html ); ?>
							<p class="card-text card-formating"><b>Total Downloads : </b><?php echo esc_html( number_format_i18n( $thm_downloaded ) ); ?>+</p>
							<!--<p class="card-text card-formating"><b>Author :</b> <a href="<?php echo esc_url( $thm_author['profile'] ); ?>" ><?php echo esc_html( $thm_author['display_name'], ); ?></a> </p>-->
							<!--<p class="card-text card-formating"><b>Author Web:</b> <a href="<?php echo esc_url( $thm_author['author_url'] ); ?>" ><?php echo esc_html( $thm_author['author_url'], ); ?></a> </p>-->
						</div>
								<?php
								$thm_allowed_tags = array(
									'a'      => array(
										'href'    => array(),
										'onclick' => array(),
										'class'   => array(),
										'type'    => array(),
									),

									'button' => array(
										'href'    => array(),
										'onclick' => array(),
										'class'   => array(),
										'type'    => array(),
									),
								);
								?>
						<p class="card-text text-center btn-formatting mt-1">
							<?php echo wp_kses( $theme_button, $thm_allowed_tags ); ?>
							<?php
							switch ( $thm_name ) {
								case 'Webenvo':
									$thm_demo_link = 'https://webenvo.com/premium-themes/webenvo-pro/';
									$thm_buy_link  = 'https://webenvo.com/member/signup/webenvo-premium';
									break;
								case 'Formula':
									$thm_demo_link = 'https://awplife.com/wordpress-themes/formula-premium/';
									$thm_buy_link  = 'https://awplife.com/account/signup/formula-pro';
									break;
								case 'Formula Dark':
									$thm_demo_link = 'https://awplife.com/wordpress-themes/formula-premium/';
									$thm_buy_link  = 'https://awplife.com/account/signup/formula-pro';
									break;
								case 'Formula Light':
									$thm_demo_link = 'https://awplife.com/wordpress-themes/formula-premium/';
									$thm_buy_link  = 'https://awplife.com/account/signup/formula-pro';
									break;
								case 'Medical Formula':
									$thm_demo_link = 'https://awplife.com/wordpress-themes/formula-premium/';
									$thm_buy_link  = 'https://awplife.com/account/signup/formula-pro';
									break;
								
								case 'Metaverse':
									$thm_demo_link = 'https://awplife.com/wordpress-themes/formula-premium/';
									$thm_buy_link  = 'https://awplife.com/account/signup/formula-pro';
									break;
								case 'MediHealth':
									$thm_demo_link = 'https://awplife.com/wordpress-themes/medihealth-premium/';
									$thm_buy_link  = 'https://awplife.com/account/signup/medihealth-premium';
									break;
								case 'Business Campaign':
									$thm_demo_link = 'https://awplife.com/wordpress-themes/wpbusinesspress-premium/';
									$thm_buy_link  = 'https://awplife.com/account/signup/wpbusinesspress-premium';
									break;
								case 'Cryptocurrency Exchange':
									$thm_demo_link = 'https://awplife.com/wordpress-themes/crypto-premium/';
									$thm_buy_link  = 'https://awplife.com/account/signup/crypto-premium';
									break;
								
							}
							?>
							<a href="<?php echo esc_url( $thm_demo_link ); ?>" type="button" class="btn dm-btn-clr" target="_blank" >Pro Demo</a>
							<a href="<?php echo esc_url( $thm_buy_link ); ?>" type="button" class="btn buy-btn-clr" target="_blank" >Buy Now</a>
						</p>
						<div class="card-footer">
							<small class="text-muted">Last Updated: <?php echo esc_html( $thm_last_updated ); ?></small>
						</div>
					</div>
				</div>
				<?php
			} else {
				echo 'Failed to fetch theme information for ' . esc_html( $theme_slug ) . '<br>';
			}
		}
			// var_dump( $thm_extras_info );
		?>
	</div>
</div>