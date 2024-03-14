<?php
namespace OACS\SolidPostLikes\Controllers;
if ( ! defined( 'WPINC' ) ) { die; }
/** loads the like count and formats it */

class SolidPostLikesCounter
{

		public function oacs_spl_get_like_count( $like_count )
		{

			/** Load Admin Options so we know how to style the output */
			$oacs_spl_counter_active_setting  = carbon_get_theme_option( 'oacs_spl_show_counter' ) ?? '';
			$oacs_spl_counter_size_setting    = carbon_get_theme_option('oacs_spl_counter_size') ?? '';
			$oacs_spl_counter_color_setting   = carbon_get_theme_option('oacs_spl_counter_color') ?? '';
			$oacs_spl_counter_padding_setting = carbon_get_theme_option('oacs_spl_counter_padding') ?? '';

			if(empty($oacs_spl_counter_size_setting)) {

				$oacs_spl_counter_size_setting = 'inherit';

			}

			if(empty($oacs_spl_counter_color_setting)) {

				$oacs_spl_counter_color_setting = 'inherit';

			}

			if(empty($oacs_spl_counter_padding_setting)) {

				$oacs_spl_counter_padding_setting = 'inherit';

			}

			if (!empty($oacs_spl_counter_size_setting) OR !empty($oacs_spl_counter_color_setting) OR !empty($oacs_spl_counter_padding_setting)) { $oacs_spl_style_tag = ' style="'; $oacs_spl_style_tag_end = '">'; } else { $oacs_spl_style_tag = '' ;}


			if ( is_numeric( $like_count ) && $like_count > 0 ) {

				$number = $this->oacs_spl_format_count( $like_count );

			} else {

				$number = $this->oacs_spl_format_count( $like_count );

			}

			$count = '<div class="oacs-spl-counter"' .
			$oacs_spl_style_tag .
			(!empty($oacs_spl_counter_color_setting) ? 'color: ' . esc_attr($oacs_spl_counter_color_setting)  . '; ': '') .
			(!empty($oacs_spl_counter_size_setting) ? 'font-size: ' . esc_attr($oacs_spl_counter_size_setting)   . '; ': '') .
			(!empty($oacs_spl_counter_padding_setting) ? 'padding: ' . esc_attr($oacs_spl_counter_padding_setting)   . ';': '') .
			$oacs_spl_style_tag_end . esc_html($number) . '</div>';

			if ($oacs_spl_counter_active_setting) {return apply_filters('oacs_spl_counter', $count);}
		}

		/**
		 * To format the button count,
		 * appending "K" if one thousand or greater,
		 * "M" if one million or greater,
		 * and "B" if one billion or greater (unlikely).
		 * $precision = how many decimal points to display (1.25K)
		 */
		public function oacs_spl_format_count( $number )
		{
			$precision = 2;
			if ( $number >= 1000 && $number < 1000000 ) {
				$formatted = number_format( $number/1000, $precision ).'K';
			} elseif ( $number >= 1000000 && $number < 1000000000 ) {
				$formatted = number_format( $number/1000000, $precision ).'M';
			} elseif ( $number >= 1000000000 ) {
				$formatted = number_format( $number/1000000000, $precision ).'B';
			} else {
				$formatted = $number;  // Number is less than 1000
			}
			// Don't display zero decimals.
			$formatted = str_replace( '.00', '', $formatted );
			return apply_filters('oacs_spl_formatted_number', $formatted);
		}
}