<?php
/**
 * Helpers Absolute Reviews
 *
 * @package    ABR
 * @subpackage ABR/includes
 */

if ( ! function_exists( 'abr_style' ) ) {
	/**
	 * Processing path of style.
	 *
	 * @param string $path URL to the stylesheet.
	 */
	function abr_style( $path ) {
		// Check RTL.
		if ( is_rtl() ) {
			return $path;
		}

		// Check Dev.
		$dev = ABR_PATH . 'public/css/absolute-reviews-public-dev.css';

		if ( file_exists( $dev ) ) {
			return str_replace( '.css', '-dev.css', $path );
		}

		return $path;
	}
}

if ( ! function_exists( 'abr_powerkit_module_enabled' ) ) {
	/**
	 * Helper function to check the status of powerkit modules
	 *
	 * @param array $name Name of module.
	 */
	function abr_powerkit_module_enabled( $name ) {
		if ( function_exists( 'powerkit_module_enabled' ) && powerkit_module_enabled( $name ) ) {
			return true;
		}
	}
}

if ( ! function_exists( 'abr_post_views_enabled' ) ) {
	/**
	 * Check post views module.
	 *
	 * @return string Type.
	 */
	function abr_post_views_enabled() {

		// Post Views Counter.
		if ( class_exists( 'Post_Views_Counter' ) ) {
			return 'post_views';
		}

		// Powerkit Post Views.
		if ( abr_powerkit_module_enabled( 'post_views' ) ) {
			return 'pk_post_views';
		}
	}
}

if ( ! function_exists( 'abr_get_available_image_sizes' ) ) {
	/**
	 * Get the available image sizes
	 */
	function abr_get_available_image_sizes() {
		$wais = & $GLOBALS['_wp_additional_image_sizes'];

		$sizes       = array();
		$image_sizes = get_intermediate_image_sizes();

		if ( is_array( $image_sizes ) && $image_sizes ) {
			foreach ( $image_sizes as $size ) {
				if ( in_array( $size, array( 'thumbnail', 'medium', 'medium_large', 'large' ), true ) ) {
					$sizes[ $size ] = array(
						'width'  => get_option( "{$size}_size_w" ),
						'height' => get_option( "{$size}_size_h" ),
						'crop'   => (bool) get_option( "{$size}_crop" ),
					);
				} elseif ( isset( $wais[ $size ] ) ) {
					$sizes[ $size ] = array(
						'width'  => $wais[ $size ]['width'],
						'height' => $wais[ $size ]['height'],
						'crop'   => $wais[ $size ]['crop'],
					);
				}

				// Size registered, but has 0 width and height.
				if ( 0 === (int) $sizes[ $size ]['width'] && 0 === (int) $sizes[ $size ]['height'] ) {
					unset( $sizes[ $size ] );
				}
			}
		}

		return $sizes;
	}
}

if ( ! function_exists( 'abr_get_image_size' ) ) {
	/**
	 * Gets the data of a specific image size.
	 *
	 * @param string $size Name of the size.
	 */
	function abr_get_image_size( $size ) {
		if ( ! is_string( $size ) ) {
			return;
		}

		$sizes = abr_get_available_image_sizes();

		return isset( $sizes[ $size ] ) ? $sizes[ $size ] : false;
	}
}

if ( ! function_exists( 'abr_get_list_available_image_sizes' ) ) {
	/**
	 * Get the list available image sizes
	 */
	function abr_get_list_available_image_sizes() {
		$intermediate_image_sizes = get_intermediate_image_sizes();

		$image_sizes = array();

		foreach ( $intermediate_image_sizes as $size ) {
			$image_sizes[ $size ] = $size;

			$data = abr_get_image_size( $size );

			if ( isset( $data['width'] ) || isset( $data['height'] ) ) {

				$width  = '~';
				$height = '~';

				if ( isset( $data['width'] ) && $data['width'] ) {
					$width = $data['width'] . 'px';
				}
				if ( isset( $data['height'] ) && $data['height'] ) {
					$height = $data['height'] . 'px';
				}

				$image_sizes[ $size ] .= sprintf( ' [%s, %s]', $width, $height );
			}
		}

		$image_sizes = apply_filters( 'abr_list_available_image_sizes', $image_sizes );

		return $image_sizes;
	}
}

if ( ! function_exists( 'abr_get_post_metadata' ) ) {
	/**
	 * Retrieves a post meta field for the given post ID.
	 *
	 * @param int    $post_id Post ID.
	 * @param string $key     Optional. The meta key to retrieve. By default, returns
	 *                        data for all keys. Default empty.
	 * @param bool   $single  Optional. If true, returns only the first value for the specified meta key.
	 *                        This parameter has no effect if $key is not specified. Default false.
	 * @param mixed  $default Default value.
	 * @return mixed Will be an array if $single is false. Will be value of the meta
	 *               field if $single is true.
	 */
	function abr_get_post_metadata( $post_id, $key = '', $single = false, $default = null ) {

		if ( ! metadata_exists( 'post', $post_id, $key ) && $default ) {
			return $default;
		}

		return get_metadata( 'post', $post_id, $key, $single );
	}
}

if ( ! function_exists( 'abr_default_post_types' ) ) {
	/**
	 * Return default post types.
	 */
	function abr_default_post_types() {
		$types = array(
			'post' => 'post',
		);

		return apply_filters( 'abr_default_post_types', $types );
	}
}

if ( ! function_exists( 'abr_default_indicators' ) ) {
	/**
	 * Return default indicators.
	 */
	function abr_default_indicators() {
		$indicators = array(
			0  => array(
				'name' => 'None',
			),
			1  => array(
				'name' => 'Awfully',
			),
			2  => array(
				'name' => 'Very bad',
			),
			3  => array(
				'name' => 'Bad',
			),
			4  => array(
				'name' => 'Passably',
			),
			5  => array(
				'name' => 'Neutral',
			),
			6  => array(
				'name' => 'Normal',
			),
			7  => array(
				'name' => 'Good',
			),
			8  => array(
				'name' => 'Very good',
			),
			9  => array(
				'name' => 'Amazing',
			),
			10 => array(
				'name' => 'The best',
			),
		);

		return apply_filters( 'abr_default_indicators', $indicators );
	}
}

if ( ! function_exists( 'abr_list_indicators' ) ) {
	/**
	 * Return list indicators.
	 */
	function abr_list_indicators() {
		$indicators = abr_default_indicators();

		// Disable all indicators.
		if ( get_option( 'abr_review_disable_indicators', false ) ) {
			$indicators = array();
		}

		// Set custom name for indicators.
		foreach ( $indicators as $index => $value ) {
			$indicators[ $index ]['name'] = get_option( "abr_review_indicator_label_{$index}", $indicators[ $index ]['name'] );
		}

		return $indicators;
	}
}

if ( ! function_exists( 'abr_the_review' ) ) {
	/**
	 * Post Review
	 *
	 * @param int $post_id Post ID.
	 */
	function abr_the_review( $post_id = null ) {

		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}

		if ( ! $post_id ) {
			return;
		}

		$post_type = get_post_type( $post_id );

		// Get types of option.
		$option_types = get_option( 'abr_review_post_types', abr_default_post_types() );

		if ( ! in_array( $post_type, $option_types, true ) ) {
			return;
		}

		// Check display.
		if ( ! abr_get_post_metadata( $post_id, '_abr_review_settings', true ) ) {
			return;
		}

		// Params.
		$params = array(
			'type'                 => abr_get_post_metadata( $post_id, '_abr_review_type', true, 'percentage' ),
			'items'                => abr_get_post_metadata( $post_id, '_abr_review_items', true, array() ),
			'heading'              => abr_get_post_metadata( $post_id, '_abr_review_heading', true ),
			'desc'                 => abr_get_post_metadata( $post_id, '_abr_review_desc', true ),
			'main_scale'           => abr_get_post_metadata( $post_id, '_abr_review_main_scale', true, true ),
			'total_score_number'   => abr_get_post_metadata( $post_id, '_abr_review_total_score_number', true ),
			'pros_heading'         => abr_get_post_metadata( $post_id, '_abr_review_pros_heading', true ),
			'pros_items'           => abr_get_post_metadata( $post_id, '_abr_review_pros_items', true, array() ),
			'cons_heading'         => abr_get_post_metadata( $post_id, '_abr_review_cons_heading', true ),
			'cons_items'           => abr_get_post_metadata( $post_id, '_abr_review_cons_items', true, array() ),
			'legend'               => abr_get_post_metadata( $post_id, '_abr_review_legend', true ),
			'schema_heading'       => abr_get_post_metadata( $post_id, '_abr_review_schema_heading', true ),
			'schema_desc'          => abr_get_post_metadata( $post_id, '_abr_review_schema_desc', true ),
			'schema_author'        => abr_get_post_metadata( $post_id, '_abr_review_schema_author', true ),
			'schema_author_custom' => abr_get_post_metadata( $post_id, '_abr_review_schema_author_custom', true ),
		);

		abr_review_display_block( $params );
	}
}

if ( ! function_exists( 'abr_get_review' ) ) {
	/**
	 * Get review of post
	 *
	 * @param bool $format  Do you format the result?.
	 * @param int  $post_id Post ID.
	 */
	function abr_get_review( $format = false, $post_id = null ) {
		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}

		if ( ! $post_id ) {
			return 0;
		}

		if ( ! abr_get_post_metadata( $post_id, '_abr_review_settings', true ) ) {
			return 0;
		}

		$review_type = abr_get_post_metadata( $post_id, '_abr_review_type', true, 'percentage' );

		$total_score = (float) abr_get_post_metadata( $post_id, '_abr_review_total_score_number', true );

		// Vars.
		switch ( $review_type ) {
			case 'percentage':
				$max = 100;
				break;
			case 'point-5':
				$max = 5;
				break;
			case 'point-10':
				$max = 10;
				break;
			case 'star':
				$max = 5;
				break;
		}

		// Formating value.
		if ( $total_score && $format ) {
			if ( 'percentage' === $review_type ) {
				$total_score = sprintf( '%s%%', $total_score );
			} else {
				$total_score = sprintf( '%s/%s', $total_score, $max );
			}
		}

		return $total_score;
	}
}

if ( ! function_exists( 'abr_review_get_val_index' ) ) {
	/**
	 * Get value index for post review
	 *
	 * @param string $type  The type of review.
	 * @param float  $value The value.
	 */
	function abr_review_get_val_index( $type, $value ) {
		$val_index = $value;

		if ( 'star' === $type ) {
			$val_index = round( $value * 2 - 1 );
		}

		if ( 'point-5' === $type ) {
			$val_index = round( $value * 2 - 1 );
		}

		if ( 'percentage' === $type ) {
			$val_index = round( $value / 10 );
		}

		return $val_index;
	}
}

if ( ! function_exists( 'abr_review_get_type' ) ) {
	/**
	 * Get type review of post
	 *
	 * @param int   $post_id Post ID.
	 * @param mixed $default Return default.
	 */
	function abr_review_get_type( $post_id = null, $default = false ) {

		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}

		if ( ! $post_id ) {
			return $default;
		}

		return abr_get_post_metadata( $post_id, '_abr_review_type', true, $default );
	}
}

if ( ! function_exists( 'abr_review_star_rating' ) ) {
	/**
	 * Output a HTML element with a star rating for a given rating.
	 *
	 * @param array $args {
	 *     Optional. Array of star ratings arguments.
	 *
	 *     @type int|float $rating The rating to display, expressed in either a 0.5 rating increment,
	 *                             or percentage. Default 0.
	 *     @type string    $type   Format that the $rating is in. Valid values are 'rating' (default),
	 *                             or, 'percent'. Default 'rating'.
	 *     @type int       $number The number of ratings that makes up this rating. Default 0.
	 *     @type bool      $echo   Whether to echo the generated markup. False to return the markup instead
	 *                             of echoing it. Default true.
	 * }
	 */
	function abr_review_star_rating( $args = array() ) {
		$defaults = array(
			'rating' => 0,
			'type'   => 'rating',
			'number' => 0,
			'echo'   => true,
		);
		$r        = wp_parse_args( $args, $defaults );

		// Non-English decimal places when the $rating is coming from a string.
		$rating = (float) str_replace( ',', '.', $r['rating'] );

		// Convert Percentage to star rating, 0..5 in .5 increments.
		if ( 'percent' === $r['type'] ) {
			$rating = round( $rating / 10, 0 ) / 2;
		}

		// Calculate the number of each type of star needed.
		$full_stars  = floor( $rating );
		$half_stars  = ceil( $rating - $full_stars );
		$empty_stars = 5 - $full_stars - $half_stars;

		if ( $r['number'] ) {
			/* translators: 1: the rating, 2: the number of ratings */
			$format = _n( '%1$s rating based on %2$s rating', '%1$s rating based on %2$s ratings', $r['number'] );
			$title  = sprintf( $format, number_format_i18n( $rating, 1 ), number_format_i18n( $r['number'] ) );
		} else {
			/* translators: %s: the rating */
			$title = sprintf( __( '%s rating' ), number_format_i18n( $rating, 1 ) );
		}

		$output  = '<div class="abr-star-rating">';
		$output .= '<span class="screen-reader-text">' . $title . '</span>';
		$output .= str_repeat( '<div class="abr-star abr-star-full" aria-hidden="true"></div>', $full_stars );
		$output .= str_repeat( '<div class="abr-star abr-star-half" aria-hidden="true"></div>', $half_stars );
		$output .= str_repeat( '<div class="abr-star abr-star-empty" aria-hidden="true"></div>', $empty_stars );
		$output .= '</div>';

		if ( $r['echo'] ) {
			echo (string) $output; // XSS.
		}

		return $output;
	}
}

if ( ! function_exists( 'abr_review_display_rating' ) ) {
	/**
	 * Display review rating info
	 *
	 * @param string $type  The type of review.
	 * @param float  $max   The max value.
	 * @param float  $value The value.
	 * @param string $name  The name.
	 * @param bool   $label Display label.
	 */
	function abr_review_display_rating( $type, $max, $value, $name = null, $label = true ) {

		switch ( $type ) {
			case 'star':
				$value = ( $value <= $max ) ? round( $value, 1 ) : $max;
				break;
			case 'point-5':
			case 'point-10':
			case 'percentage':
				$value = ( $value <= $max ) ? round( $value ) : $max;
				break;
		}

		// Get indicators.
		$indicators = abr_list_indicators();

		// Set value index.
		$val_index = abr_review_get_val_index( $type, $value );
		?>
		<div class="abr-review-data">
			<?php if ( $name ) { ?>
				<div class="abr-review-name">
					<?php echo esc_html( $name ); ?>
				</div>
			<?php } ?>

			<?php if ( 'star' === $type ) : ?>
				<div class="abr-review-stars">
					<?php
					abr_review_star_rating( array(
						'rating' => $value,
						'type'   => 'rating',
						'number' => 0,
					) );
					?>
				</div>
			<?php elseif ( 'point-5' === $type || 'point-10' === $type ) : ?>
				<div class="abr-review-line">
					<?php
					$max_slice = 'point-5' === $type ? 5 : 10;

					for ( $index = 1; $index <= $max_slice; $index++ ) {
						if ( $index <= $value ) {
							$class = 'abr-review-slice-active';
						} else {
							$class = 'abr-review-slice-no-active';
						}
						?>
							<span class="abr-review-slice <?php echo esc_attr( $class ); ?>"></span>
						<?php
					}
					?>
				</div>
			<?php elseif ( 'percentage' === $type ) : ?>
				<div class="abr-review-progress">
					<div class="abr-review-progressbar abr-review-progressbar-<?php echo esc_attr( $val_index ); ?>"></div>
				</div>
			<?php endif; ?>

			<?php if ( $label ) { ?>
				<div class="abr-review-label">
					<span class="abr-review-text">
						<?php
						echo wp_kses_post( sprintf( '<span class="total">%s</span><span class="sep">/</span><span class="max">%s</span>', $value, $max ) );
						?>
					</span>

					<?php if ( $indicators && $indicators[ $val_index ]['name'] ) { ?>
						<span class="abr-badge abr-badge-primary abr-review-badge-<?php echo esc_attr( $val_index ); ?>">
							<?php echo esc_html( $indicators[ $val_index ]['name'] ); ?>
						</span>
					<?php } ?>
				</div>
			<?php } ?>
		</div>
		<?php
	}
}

if ( ! function_exists( 'abr_review_display_block' ) ) {
	/**
	 * Display review rating
	 *
	 * @param array $params The params of review.
	 */
	function abr_review_display_block( $params ) {

		$params = array_merge( array(
			'variant'              => 'default',
			'type'                 => 'percentage',
			'items'                => array(),
			'heading'              => '',
			'desc'                 => '',
			'main_scale'           => true,
			'total_label'          => esc_html__( 'Total Score', 'absolute-reviews' ),
			'total_score_number'   => '',
			'pros_heading'         => '',
			'pros_items'           => array(),
			'cons_heading'         => '',
			'cons_items'           => array(),
			'legend'               => '',
			'schema_heading'       => '',
			'schema_desc'          => '',
			'schema_author'        => '',
			'schema_author_custom' => '',
		), $params );

		// Vars.
		switch ( $params['type'] ) {
			case 'percentage':
				$max = 100;
				break;
			case 'point-5':
				$max = 5;
				break;
			case 'point-10':
				$max = 10;
				break;
			case 'star':
				$max = 5;
				break;
		}

		// Get indicators.
		$indicators = abr_list_indicators();
		?>
		<div class="abr-post-review abr-review-<?php echo esc_attr( $params['variant'] ); ?> abr-review-<?php echo esc_attr( $params['type'] ); ?>" itemprop="reviews" itemscope itemtype="http://schema.org/Review">

			<div class="abr-review-author" itemprop="author" itemscope itemtype="http://schema.org/Person">
				<span itemprop="name">
					<?php echo esc_html( 'custom' === $params['schema_author'] ? $params['schema_author_custom'] : $params['schema_author'] ); ?>
				</span>
			</div>

			<!-- Info -->
			<?php if ( $params['heading'] || $params['desc'] ) { ?>
				<div class="abr-review-info">

					<div class="abr-review-heading" itemprop="itemReviewed" itemscope itemtype="http://schema.org/Product">
						<?php
						// Review title.
						if ( $params['heading'] ) {
							?>
							<h3 class="abr-title abr-review-title"><?php echo esc_html( $params['heading'] ); ?></h3>
							<?php
						}

						if ( $params['heading'] || $params['schema_heading'] ) {
							$heading = $params['schema_heading'] ? $params['schema_heading'] : $params['heading'];
							?>
							<span class="abr-review-scheme-hidden" itemprop="name"><?php echo esc_html( $heading ); ?></span>
							<?php
						}
						?>

						<div class="abr-review-scheme-hidden" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
							<span itemprop="ratingValue"><?php echo esc_html( $params['total_score_number'] ); ?></span>
							<span itemprop="bestRating"><?php echo esc_html( $max ); ?></span>
							<span itemprop="worstRating"><?php echo esc_html( 0 ); ?></span>
							<span itemprop="reviewCount"><?php echo esc_html( 1 ); ?></span>
						</div>
					</div>

					<?php
					// Review desc.
					if ( $params['desc'] ) {
						?>
						<div class="abr-review-description" itemprop="reviewBody"><?php echo wp_kses( $params['desc'], 'post' ); ?></div>
						<?php
					}

					if ( $params['desc'] || $params['schema_desc'] ) {
						$desc = $params['schema_desc'] ? $params['schema_desc'] : $params['desc'];
						?>
						<div class="abr-review-scheme-hidden" itemprop="reviewBody"><?php echo wp_kses( $desc, 'post' ); ?></div>
						<?php
					}
					?>
				</div>
			<?php } ?>

			<!-- Review Total -->
			<div class="abr-review-total">
				<?php
				$total_score = (float) $params['total_score_number'];

				if ( $total_score ) {
					if ( ! $params['main_scale'] && $params['items'] ) {
						?>
							<div class="abr-review-list">
								<ul>
									<?php
									if ( isset( $params['items']['name'] ) && $params['items']['name'] ) {
										foreach ( $params['items']['name'] as $key => $item_name ) {
											$item_desc  = (string) $params['items']['desc'][ $key ];
											$item_value = (float) $params['items']['val'][ $key ];

											if ( ! $item_name ) {
												continue;
											}

											?>
												<li class="abr-review-item">
													<?php abr_review_display_rating( $params['type'], $max, $item_value, $item_name ); ?>

													<div class="abr-review-desc">
														<?php echo esc_html( $item_desc ); ?>
													</div>
												</li>
											<?php
										}
									}
									?>
								</ul>
							</div>
						<?php
					}

					if ( $params['main_scale'] ) {
						abr_review_display_rating( $params['type'], $max, $total_score, null, false );
					}
					?>
						<div class="abr-review-score <?php echo esc_html( ! $params['total_label'] ? 'abr-review-score-row' : '' ); ?>" itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">
							<div class="abr-review-text">
								<?php
								echo wp_kses( sprintf( '<span class="total" itemprop="ratingValue">%s</span><span class="sep">/</span><span class="max" itemprop="bestRating">%s</span>', $total_score, $max ), array(
									'span' => array(
										'class'    => true,
										'itemprop' => true,
									),
								) );
								?>
							</div>

							<?php if ( $params['total_label'] || $params['legend'] ) { ?>
								<div class="abr-review-subtext">
									<?php if ( $params['total_label'] ) { ?>
										<span class="abr-data-label"><?php echo esc_html( $params['total_label'] ); ?></span>
									<?php } ?>

									<?php if ( $params['legend'] ) { ?>
										<span class="abr-data-info">i<span><?php echo wp_kses_post( $params['legend'] ); ?></span></span>
									<?php } ?>
								</div>
							<?php } ?>
						</div>
					<?php
				}
				?>
			</div>

			<!-- Review Indicator -->
			<?php
			if ( 'block' === $params['variant'] ) {
				// Set value index.
				$val_index = abr_review_get_val_index( $params['type'], $total_score );

				if ( $indicators && $indicators[ $val_index ]['name'] ) {
					?>
					<div class="abr-review-indicator">
						<span class="abr-badge abr-badge-primary abr-review-badge-<?php echo esc_attr( $val_index ); ?>">
							<?php echo esc_html( $indicators[ $val_index ]['name'] ); ?>
						</span>
					</div>
					<?php
				}
			}
			?>

			<!-- Items List -->
			<?php if ( $params['items'] && $params['main_scale'] ) { ?>
				<div class="abr-review-list">
					<ul>
						<?php
						if ( isset( $params['items']['name'] ) && $params['items']['name'] ) {
							foreach ( $params['items']['name'] as $key => $item_name ) {
								$item_desc  = (string) $params['items']['desc'][ $key ];
								$item_value = (float) $params['items']['val'][ $key ];

								if ( ! $item_name ) {
									continue;
								}

								?>
									<li class="abr-review-item">
										<?php abr_review_display_rating( $params['type'], $max, $item_value, $item_name ); ?>

										<div class="abr-review-desc">
											<?php echo esc_html( $item_desc ); ?>
										</div>
									</li>
								<?php
							}
						}
						?>
					</ul>
				</div>
			<?php } ?>

			<!-- Items Pros / Cons -->
			<?php if ( $params['pros_items'] || $params['cons_items'] ) { ?>
				<div class="abr-review-details">
					<?php
					if ( $params['pros_items'] ) {

						$params['pros_heading'] = $params['pros_heading'] ? $params['pros_heading'] : esc_html__( 'The Good', 'absolute-reviews' );
						?>
						<div class="abr-review-items abr-review-pros">
							<div class="abr-review-title">
								<h3 class="abr-title"><?php echo esc_html( $params['pros_heading'] ); ?></h3>
							</div>

							<ul>
								<?php
								if ( isset( $params['pros_items']['name'] ) && $params['pros_items']['name'] ) {
									foreach ( $params['pros_items']['name'] as $key => $name ) {
										?>
										<li><?php echo esc_attr( $params['pros_items']['name'][ $key ] ); ?></li>
										<?php
									}
								}
								?>
							</ul>
						</div>
					<?php } ?>

					<?php
					if ( $params['cons_items'] ) {

						$params['cons_heading'] = $params['cons_heading'] ? $params['cons_heading'] : esc_html__( 'The Bad', 'absolute-reviews' );
						?>
						<div class="abr-review-items abr-review-cons">
							<div class="abr-review-title">
								<h3 class="abr-title"><?php echo esc_html( $params['cons_heading'] ); ?></h3>
							</div>

							<ul>
								<?php
								if ( isset( $params['cons_items']['name'] ) && $params['cons_items']['name'] ) {
									foreach ( $params['cons_items']['name'] as $key => $name ) {
										?>
										<li class="abr-item"><?php echo esc_attr( $params['cons_items']['name'][ $key ] ); ?></li>
										<?php
									}
								}
								?>
							</ul>
						</div>
					<?php } ?>
				</div>
			<?php } ?>
		</div>
		<?php
	}
}

if ( ! function_exists( 'abr_reviews_posts_widget_handler' ) ) {
	/**
	 * Widget template handler
	 *
	 * @param string $name     Specific template.
	 * @param array  $posts    Array of posts.
	 * @param array  $params   Array of params.
	 * @param array  $instance Widget instance.
	 */
	function abr_reviews_posts_widget_handler( $name, $posts, $params, $instance ) {
		$templates = apply_filters( 'abr_reviews_posts_templates', array() );

		if ( isset( $templates[ $name ] ) && function_exists( $templates[ $name ]['func'] ) ) {
			call_user_func( $templates[ $name ]['func'], $posts, $params, $instance );
		} else {
			call_user_func( 'abr_reviews_posts_template', $posts, $params, $instance );
		}
	}
}

/**
 * Convert Block Post Meta
 *
 * @param array  $settings Settings of block.
 * @param string $prefix   The prefix.
 */
function abr_block_convert_post_meta( $settings, $prefix ) {

	$meta = array();

	$list = array(
		'category'     => 'showMetaCategory',
		'author'       => 'showMetaAuthor',
		'date'         => 'showMetaDate',
		'comments'     => 'showMetaComments',
		'views'        => 'showMetaViews',
		'reading_time' => 'showMetaReadingTime',
	);

	$list = apply_filters( 'abr_convert_post_meta', $list, $settings, $prefix );

	foreach ( $list as $key => $alt ) {
		if ( isset( $settings[ $prefix . '_' . $alt ] ) && $settings[ $prefix . '_' . $alt ] ) {
			$meta[] = $key;
		}
	}

	return $meta;
}
