<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * The template loader of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Blossom_Recipe
 * @subpackage Blossom_Recipe/admin
 * @author     Blossom <test@test.com>
 */
class Blossom_Recipe_Maker_Functions {


	/**
	 * Hook in methods.
	 */
	function pagination_bar( $custom_query ) {
		$total_pages = $custom_query->max_num_pages;
		$big         = 999999999; // need an unlikely integer

		if ( $total_pages > 1 ) {
			$current_page = max( 1, get_query_var( 'paged' ) );

			echo '<nav class="navigation pagination" role="navigation"><h2 class="screen-reader-text">Posts navigation</h2><div class="nav-links">';

			echo wp_kses_post(
				paginate_links(
					array(
						'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
						'format'    => '?paged=%#%',
						'current'   => absint( $current_page ),
						'total'     => absint( $total_pages ),
						'prev_text' => esc_html__( 'Previous', 'blossom-recipe-maker' ),
						'next_text' => esc_html__( 'Next', 'blossom-recipe-maker' ),
					)
				)
			);

			echo "</div></nav>\n";
		}
	}

	public static function difficulty_levels() {
		$levels = array(
			'Easy'      => esc_html__( 'Easy', 'blossom-recipe-maker' ),
			'Medium'    => esc_html__( 'Medium', 'blossom-recipe-maker' ),
			'Difficult' => esc_html__( 'Difficult', 'blossom-recipe-maker' ),
		);

		$levels = apply_filters( 'br_recipe_difficulty_level_options', $levels );

		return $levels;
	}

	/**
	 * Get difficulty Label
	 */
	public static function get_difficulty_label( $difficulty ) {
		$levels = self::difficulty_levels();

		$difficulty = ( $difficulty ) ? $levels[ $difficulty ] : $difficulty;

		return $difficulty;
	}

	public static function measurements() {
		// Use the "br_recipe_measurement_units" filter to add your own measurements.
		$measurements = array(
			'g'     => array(
				'singular_abbr' => _x( 'g', 'Grams Abbreviation (Singular)', 'blossom-recipe-maker' ),
				'plural_abbr'   => _x( 'g', 'Grams Abbreviation (Plural)', 'blossom-recipe-maker' ),
				'singular'      => __( 'gram', 'blossom-recipe-maker' ),
				'plural'        => __( 'grams', 'blossom-recipe-maker' ),
			),
			'kg'    => array(
				'singular_abbr' => _x( 'kg', 'Kilograms Abbreviation (Singular)', 'blossom-recipe-maker' ),
				'plural_abbr'   => _x( 'kg', 'Kilograms Abbreviation (Plural)', 'blossom-recipe-maker' ),
				'singular'      => __( 'kilogram', 'blossom-recipe-maker' ),
				'plural'        => __( 'kilograms', 'blossom-recipe-maker' ),
			),
			'mg'    => array(
				'singular_abbr' => __( 'mg', 'blossom-recipe-maker' ),
				'plural_abbr'   => __( 'mg', 'blossom-recipe-maker' ),
				'singular'      => __( 'milligram', 'blossom-recipe-maker' ),
				'plural'        => __( 'milligrams', 'blossom-recipe-maker' ),
			),
			'oz'    => array(
				'singular_abbr' => __( 'oz', 'blossom-recipe-maker' ),
				'plural_abbr'   => __( 'oz', 'blossom-recipe-maker' ),
				'singular'      => __( 'ounce', 'blossom-recipe-maker' ),
				'plural'        => __( 'ounces', 'blossom-recipe-maker' ),
			),
			'floz'  => array(
				'singular_abbr' => __( 'fl oz', 'blossom-recipe-maker' ),
				'plural_abbr'   => __( 'fl oz', 'blossom-recipe-maker' ),
				'singular'      => __( 'fluid ounce', 'blossom-recipe-maker' ),
				'plural'        => __( 'fluid ounces', 'blossom-recipe-maker' ),
			),
			'cup'   => array(
				'singular_abbr' => __( 'cup', 'blossom-recipe-maker' ),
				'plural_abbr'   => __( 'cups', 'blossom-recipe-maker' ),
				'singular'      => __( 'cup', 'blossom-recipe-maker' ),
				'plural'        => __( 'cups', 'blossom-recipe-maker' ),
			),
			'tsp'   => array(
				'singular_abbr' => __( 'tsp', 'blossom-recipe-maker' ),
				'plural_abbr'   => __( 'tsp', 'blossom-recipe-maker' ),
				'singular'      => __( 'teaspoon', 'blossom-recipe-maker' ),
				'plural'        => __( 'teaspoons', 'blossom-recipe-maker' ),
			),
			'tbsp'  => array(
				'singular_abbr' => __( 'tbsp', 'blossom-recipe-maker' ),
				'plural_abbr'   => __( 'tbsp', 'blossom-recipe-maker' ),
				'singular'      => __( 'tablespoon', 'blossom-recipe-maker' ),
				'plural'        => __( 'tablespoons', 'blossom-recipe-maker' ),
			),
			'ml'    => array(
				'singular_abbr' => __( 'ml', 'blossom-recipe-maker' ),
				'plural_abbr'   => __( 'ml', 'blossom-recipe-maker' ),
				'singular'      => __( 'milliliter', 'blossom-recipe-maker' ),
				'plural'        => __( 'milliliters', 'blossom-recipe-maker' ),
			),
			'l'     => array(
				'singular_abbr' => __( 'l', 'blossom-recipe-maker' ),
				'plural_abbr'   => __( 'l', 'blossom-recipe-maker' ),
				'singular'      => __( 'liter', 'blossom-recipe-maker' ),
				'plural'        => __( 'liters', 'blossom-recipe-maker' ),
			),
			'stick' => array(
				'singular_abbr' => __( 'stick', 'blossom-recipe-maker' ),
				'plural_abbr'   => __( 'sticks', 'blossom-recipe-maker' ),
				'singular'      => __( 'stick', 'blossom-recipe-maker' ),
				'plural'        => __( 'sticks', 'blossom-recipe-maker' ),
			),
			'lb'    => array(
				'singular_abbr' => __( 'lb', 'blossom-recipe-maker' ),
				'plural_abbr'   => __( 'lbs', 'blossom-recipe-maker' ),
				'singular'      => __( 'pound', 'blossom-recipe-maker' ),
				'plural'        => __( 'pounds', 'blossom-recipe-maker' ),
			),
			'dash'  => array(
				'singular_abbr' => __( 'dash', 'blossom-recipe-maker' ),
				'plural_abbr'   => __( 'dashes', 'blossom-recipe-maker' ),
				'singular'      => __( 'dash', 'blossom-recipe-maker' ),
				'plural'        => __( 'dashes', 'blossom-recipe-maker' ),
			),
			'drop'  => array(
				'singular_abbr' => __( 'drop', 'blossom-recipe-maker' ),
				'plural_abbr'   => __( 'drops', 'blossom-recipe-maker' ),
				'singular'      => __( 'drop', 'blossom-recipe-maker' ),
				'plural'        => __( 'drops', 'blossom-recipe-maker' ),
			),
			'gal'   => array(
				'singular_abbr' => __( 'gal', 'blossom-recipe-maker' ),
				'plural_abbr'   => __( 'gals', 'blossom-recipe-maker' ),
				'singular'      => __( 'gallon', 'blossom-recipe-maker' ),
				'plural'        => __( 'gallons', 'blossom-recipe-maker' ),
			),
			'pinch' => array(
				'singular_abbr' => __( 'pinch', 'blossom-recipe-maker' ),
				'plural_abbr'   => __( 'pinches', 'blossom-recipe-maker' ),
				'singular'      => __( 'pinch', 'blossom-recipe-maker' ),
				'plural'        => __( 'pinches', 'blossom-recipe-maker' ),
			),
			'pt'    => array(
				'singular_abbr' => __( 'pt', 'blossom-recipe-maker' ),
				'plural_abbr'   => __( 'pt', 'blossom-recipe-maker' ),
				'singular'      => __( 'pint', 'blossom-recipe-maker' ),
				'plural'        => __( 'pints', 'blossom-recipe-maker' ),
			),
			'qt'    => array(
				'singular_abbr' => __( 'qt', 'blossom-recipe-maker' ),
				'plural_abbr'   => __( 'qts', 'blossom-recipe-maker' ),
				'singular'      => __( 'quart', 'blossom-recipe-maker' ),
				'plural'        => __( 'quarts', 'blossom-recipe-maker' ),
			),
		);

		$measurements = apply_filters( 'br_recipe_measurement_units', $measurements );

		return $measurements;

	}

	/**
	 * Get measurement Label
	 */
	public static function get_measurement_label( $measurement ) {
		$measurements = self::measurements();

		$measurement = ( $measurement ) ? $measurements[ $measurement ]['plural_abbr'] : $measurement;

		return $measurement;
	}

	public static function time_format( $minutes, $format ) {

		ob_start();

		if ( $minutes < 60 ) :
			if ( $format === 'iso' ) :
				return 'PT0H' . $minutes . 'M';
			endif;

	 elseif ( $minutes < 1440 ) :
		 $hours        = floor( $minutes / 60 );
		 $minutes_left = $minutes - ( $hours * 60 );
		 if ( $format === 'iso' ) :
			 return 'PT' . $hours . 'H' . ( $minutes_left ? $minutes_left : 0 ) . 'M';
		 endif;

	 else :
		 $days         = floor( $minutes / 24 / 60 );
		 $minutes_left = $minutes - ( $days * 24 * 60 );
		 if ( $minutes_left > 60 ) :
			 $hours_left   = floor( $minutes_left / 60 );
			 $minutes_left = $minutes_left - ( $hours_left * 60 );
		 endif;
		 if ( $format === 'iso' ) :
			 return 'P' . $days . 'DT' . ( $hours_left ? $hours_left : 0 ) . 'H' . ( $minutes_left ? $minutes_left : 0 ) . 'M';
		 endif;

	 endif;

	 return ob_get_clean();

	}

	function brm_posted_on( $icon = false ) {

		echo '<span class="posted-on">';

		if ( $icon ) {
			echo '<i class="fa fa-calendar" aria-hidden="true"></i>';
		}

		printf( '<a href="%1$s" rel="bookmark"><time class="entry-date published updated" datetime="%2$s">%3$s</time></a>', esc_url( get_permalink() ), esc_attr( get_the_date( 'c' ) ), esc_html( get_the_date() ) );

		echo '</span>';

	}

	function brm_minify_css( $input ) {
		if ( trim( $input ) === '' ) {
			return $input;
		}
		// Force white-space(s) in `calc()`
		if ( strpos( $input, 'calc(' ) !== false ) {
			$input = preg_replace_callback(
				'#(?<=[\s:])calc\(\s*(.*?)\s*\)#',
				function ( $matches ) {
					return 'calc(' . preg_replace( '#\s+#', "\x1A", $matches[1] ) . ')';
				},
				$input
			);
		}
		return preg_replace(
			array(
				// Remove comment(s)
				'#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')|\/\*(?!\!)(?>.*?\*\/)|^\s*|\s*$#s',
				// Remove unused white-space(s)
				'#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/))|\s*+;\s*+(})\s*+|\s*+([*$~^|]?+=|[{};,>~+]|\s*+-(?![0-9\.])|!important\b)\s*+|([[(:])\s++|\s++([])])|\s++(:)\s*+(?!(?>[^{}"\']++|"(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')*+{)|^\s++|\s++\z|(\s)\s+#si',
				// Replace `0(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)` with `0`
				'#(?<=[\s:])(0)(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)#si',
				// Replace `:0 0 0 0` with `:0`
				'#:(0\s+0|0\s+0\s+0\s+0)(?=[;\}]|\!important)#i',
				// Replace `background-position:0` with `background-position:0 0`
				'#(background-position):0(?=[;\}])#si',
				// Replace `0.6` with `.6`, but only when preceded by a white-space or `=`, `:`, `,`, `(`, `-`
				'#(?<=[\s=:,\(\-]|&\#32;)0+\.(\d+)#s',
				// Minify string value
				'#(\/\*(?>.*?\*\/))|(?<!content\:)([\'"])([a-z_][-\w]*?)\2(?=[\s\{\}\];,])#si',
				'#(\/\*(?>.*?\*\/))|(\burl\()([\'"])([^\s]+?)\3(\))#si',
				// Minify HEX color code
				'#(?<=[\s=:,\(]\#)([a-f0-6]+)\1([a-f0-6]+)\2([a-f0-6]+)\3#i',
				// Replace `(border|outline):none` with `(border|outline):0`
				'#(?<=[\{;])(border|outline):none(?=[;\}\!])#',
				// Remove empty selector(s)
				'#(\/\*(?>.*?\*\/))|(^|[\{\}])(?:[^\s\{\}]+)\{\}#s',
				'#\x1A#',
			),
			array(
				'$1',
				'$1$2$3$4$5$6$7',
				'$1',
				':0',
				'$1:0 0',
				'.$1',
				'$1$3',
				'$1$2$4$5',
				'$1$2$3',
				'$1:0',
				'$1$2',
				' ',
			),
			$input
		);
	}

	function brm_get_fallback_svg( $post_thumbnail ) {
		if ( ! $post_thumbnail ) {
			return;
		}

		$image_size = self::brm_get_image_sizes( $post_thumbnail );
		$svg_fill   = apply_filters( 'brm_fallback_svg_fill', 'fill:#f2f2f2;' );

		if ( $image_size ) { ?>            
			<svg class="fallback-svg" viewBox="0 0 <?php echo esc_attr( $image_size['width'] ); ?> <?php echo esc_attr( $image_size['height'] ); ?>" preserveAspectRatio="none">
					<rect width="<?php echo esc_attr( $image_size['width'] ); ?>" height="<?php echo esc_attr( $image_size['height'] ); ?>" style="<?php echo esc_attr( $svg_fill ); ?>"></rect>
			</svg>
			<?php
		}
	}

	function brm_get_image_sizes( $size = '' ) {

		global $_wp_additional_image_sizes;

		$sizes                        = array();
		$get_intermediate_image_sizes = get_intermediate_image_sizes();

		// Create the full array with sizes and crop info
		foreach ( $get_intermediate_image_sizes as $_size ) {
			if ( in_array( $_size, array( 'thumbnail', 'medium', 'medium_large', 'large' ) ) ) {
				$sizes[ $_size ]['width']  = get_option( $_size . '_size_w' );
				$sizes[ $_size ]['height'] = get_option( $_size . '_size_h' );
				$sizes[ $_size ]['crop']   = (bool) get_option( $_size . '_crop' );
			} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
				$sizes[ $_size ] = array(
					'width'  => $_wp_additional_image_sizes[ $_size ]['width'],
					'height' => $_wp_additional_image_sizes[ $_size ]['height'],
					'crop'   => $_wp_additional_image_sizes[ $_size ]['crop'],
				);
			}
		}
		// Get only 1 size if found
		if ( $size ) {
			if ( isset( $sizes[ $size ] ) ) {
				return $sizes[ $size ];
			} else {
				return false;
			}
		}

		return $sizes;
	}


}
new Blossom_Recipe_Maker_Functions();
