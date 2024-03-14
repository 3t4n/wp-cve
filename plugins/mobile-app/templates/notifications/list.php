<!DOCTYPE html>
<html dir="<?php echo( is_rtl() ? 'rtl' : 'ltr' ); ?>">
	<head>
		<meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1, user-scalable=no">
		<?php
		/**
		 * Filters styles for the list.
		 *
		 * @since 3.2
		 *
		 * @var array Styles, associative array with styles.
		 */
		$styles = apply_filters(
			'canvas_list_show_styles',
			// Define custom styles for the list.
			array(
				'font-family-body'    => '"Roboto", "Open Sans", Arial, sans-serif',
				'font-family-heading' => '"Roboto", serif',
				'font-family-meta'    => '"Roboto", "Open Sans", "Arial", sans-serif',
				'font-size-body'      => '1rem',
				'font-size-heading'   => '15px',
				'font-size-meta'      => '14px',
				'color-text-body'     => '#555',
				'color-text-heading'  => '#000',
				'color-text-meta'     => '#333',
			)
		);
		/**
		 * Register stylesheets for lists
		 *
		 * @global list_type
		 */
		function canvas_list_stylesheets() {
			global $list_type;
			wp_enqueue_style( 'onsenui', CANVAS_URL . 'assets/libs/onsen/css/onsenui.min.css', array(), CANVAS_PLUGIN_VERSION );
			wp_enqueue_style( 'onsen-components', CANVAS_URL . 'assets/libs/onsen/css/onsen-css-components.min.css', array(), CANVAS_PLUGIN_VERSION );

			wp_enqueue_style( 'fonts-roboto', 'https://fonts.googleapis.com/css?family=Roboto:300,400,700&display=swap' );

			/**
			* Filter for loop.css url
			*
			* @since 3.2
			*
			* @param string $path
			* @param string $list_type
			*/
			$list_css_url = apply_filters( 'canvas_list_css_custom', CANVAS_URL . 'templates/notifications/list.css', $list_type );
			wp_enqueue_style( 'canvas-list', $list_css_url, array(), CANVAS_PLUGIN_VERSION );
			/**
			* Called when styles enqueued at the loop page.
			*
			* @since 3.2
			*/
			do_action( 'canvas_list_enqueue_style' );
		}

		/**
		 * Register scripts for lists
		 *
		 * @global list_type
		 */
		function canvas_list_scripts() {
			global $list_type;
			wp_enqueue_script( 'onsenui', CANVAS_URL . 'assets/libs/onsen/js/onsenui.min.js', array(), CANVAS_PLUGIN_VERSION, true );

			/**
			* Filter to allow overriding of loop.js file (article list)
			*
			* @since 3.2
			*
			* @param string $path Return custom list.js url.
			* @param string $list_type List type: favorites, search, custom, regular.
			*/
			$list_js_url = apply_filters( 'canvas_list_js_custom', CANVAS_URL . 'templates/notifications/list.js', $list_type );

			wp_enqueue_script( 'canvas-list', $list_js_url, array( 'onsenui' ), CANVAS_PLUGIN_VERSION, true );

			wp_localize_script(
				'canvas-list',
				'canvas_list',
				array(
					'endpoint' => trailingslashit( get_bloginfo( 'url' ) ) . 'canvas-api/notifications/data',
					'limit'    => apply_filters( 'canvas_list_defailt_count', 20 ), // let know about default limits.
				)
			);

			/**
			* Called when scripts enqueued at the loop page.
			*
			* @since 3.2
			*/
			do_action( 'canvas_list_enqueue_script' );
		}

		remove_all_actions( 'wp_head' );
		remove_all_actions( 'wp_footer' );
		remove_all_actions( 'wp_print_styles' );
		remove_all_actions( 'wp_enqueue_scripts' );
		remove_all_actions( 'locale_stylesheet' );
		remove_all_actions( 'wp_print_head_scripts' );
		remove_all_actions( 'wp_print_footer_scripts' );
		remove_all_actions( 'wp_shortlink_wp_head' );

		add_action( 'wp_print_styles', 'canvas_list_stylesheets' );

		add_action( 'wp_head', 'wp_print_styles' );
		add_action( 'wp_print_footer_scripts', 'canvas_list_scripts', 300 );
		add_action( 'wp_print_footer_scripts', '_wp_footer_scripts', 300 );
		add_action( 'wp_footer', 'wp_print_footer_scripts', 200 );

		/**
		 * Prepend "data-cfasync" parameter to scripts.
		 *
		 * @param string $tag
		 * @param string $handle
		 */
		function canvas_list_add_data_attribute( $tag, $handle ) {
			if ( in_array( $handle, array( 'onsenui', 'canvas-list' ), true ) ) {
				return str_replace( ' src', ' data-cfasync="false" src', $tag );
			}
			return $tag;
		}
		add_filter( 'script_loader_tag', 'canvas_list_add_data_attribute', 10, 2 );

		wp_head();
		?>
		<style type="text/css">

			body,
			body p,
			.article-list__content div {
				font-family: <?php echo $styles['font-family-body']; ?>;
				font-size: <?php echo esc_attr( $styles['font-size-body'] ); ?>;
				color: <?php echo esc_attr( $styles['color-text-body'] ); ?>;
			}
			.article-list__content h2 {
				font-family: <?php echo $styles['font-family-heading']; ?>;
				font-size: <?php echo esc_attr( $styles['font-size-heading'] ); ?>;
				color: <?php echo esc_attr( $styles['color-text-heading'] ); ?>;
			}
			.article-list__meta {
				font-family: <?php echo $styles['font-family-meta']; ?>;
				font-size: <?php echo esc_attr( $styles['font-size-meta'] ); ?>;
				color: <?php echo esc_attr( $styles['color-text-meta'] ); ?>;
			}
			<?php
			if ( is_rtl() ) {
				?>
				.page__content {
					direction: rtl;
				}
				<?php
			}
			?>
		</style>
	</head>
	<body class="canvas-notifications-list">

		<ons-page id="load-more-page">

			<ons-pull-hook id="pull-hook">
			</ons-pull-hook>

			<ons-list id="article-list" class="article-list">

				<?php
				for ( $i = 0; $i < 8; $i++ ) {
					?>
					<ons-list-item class="article-list__article is-placeholder">
						<div class="center list-item__center">
							<div class="article-list__wrap">
								<div class="article-list__content">
									<h2></h2>
									<div class="text"></div>
									<div class="date"></div>
								</div>
							</div>
						</div>
					</ons-list-item>
					<?php
				}

				/**
				* Called before </head> at the loop page.
				*
				* @since 3.2
				*/
				do_action( 'canvas_list_end_of_placeholders' );
				?>

			</ons-list>

			<ons-progress-circular id="loading-more" indeterminate></ons-progress-circular>

		</ons-page>

		<?php
		add_action( 'wp_print_footer_scripts', 'canvas_list_scripts', 300 );
		add_action( 'wp_print_footer_scripts', '_wp_footer_scripts', 300 );
		add_action( 'wp_footer', 'wp_print_footer_scripts', 200 );

		do_action( 'wp_footer' );
		?>

	</body>
</html>
