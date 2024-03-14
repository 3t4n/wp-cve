<?php
namespace OACS\SolidPostLikes\Controllers;

/** Loads the like / unlike text.  */
if ( ! defined( 'WPINC' ) ) { die; }

class SolidPostLikesText
{

		public function oacs_spl_get_like_text()
		{
			/** Load Admin Options so we know how to style the output */
			$oacs_spl_like_text_size_setting       = carbon_get_theme_option('oacs_spl_like_text_size') ?? '';
			$oacs_spl_like_text_color_setting      = carbon_get_theme_option('oacs_spl_like_text_color') ?? '';
			$oacs_spl_like_text_padding_setting    = carbon_get_theme_option('oacs_spl_like_text_padding') ?? '';
			$oacs_spl_like_text_setting            = esc_html__(carbon_get_theme_option('oacs_spl_like_text')) ?? '';

			if(empty($oacs_spl_like_text_size_setting)) {

				$oacs_spl_like_text_size_setting = 'inherit';

			}

			if(empty($oacs_spl_like_text_color_setting)) {

				$oacs_spl_like_text_color_setting = 'inherit';

			}

			if(empty($oacs_spl_like_text_padding_setting)) {

				$oacs_spl_like_text_padding_setting = 'inherit';

			}


			if (!empty($oacs_spl_like_text_size_setting) OR !empty($oacs_spl_like_text_color_setting) OR !empty($oacs_spl_like_text_padding_setting)) { $oacs_spl_style_tag = ' style="'; $oacs_spl_style_tag_end = '">'; } else { $oacs_spl_style_tag = '>' ;}


			$oacs_spl_like_text = '<div class="oacs-spl-like-text" ' .
			$oacs_spl_style_tag .
			(!empty($oacs_spl_like_text_color_setting) ? 'color: ' . $oacs_spl_like_text_color_setting : '') .
			(!empty($oacs_spl_like_text_size_setting) ? '; font-size: ' . $oacs_spl_like_text_size_setting : '') .
			(!empty($oacs_spl_like_text_padding_setting) ? '; padding: ' . $oacs_spl_like_text_padding_setting : '') .
			$oacs_spl_style_tag_end . $oacs_spl_like_text_setting . '</div>';


			if (!empty($oacs_spl_like_text)) {return $oacs_spl_like_text;}

			return;
		}


		public function oacs_spl_get_unlike_text()
		{
			/** Load Admin Options so we know how to style the output */
			$oacs_spl_unlike_text_size_setting       = carbon_get_theme_option('oacs_spl_unlike_text_size') ?? '';
			$oacs_spl_unlike_text_color_setting      = carbon_get_theme_option('oacs_spl_unlike_text_color') ?? '';
			$oacs_spl_unlike_text_padding_setting    = carbon_get_theme_option('oacs_spl_unlike_text_padding') ?? '';
			$oacs_spl_unlike_text_setting            = esc_html__(carbon_get_theme_option('oacs_spl_unlike_text')) ?? '';

			if(empty($oacs_spl_unlike_text_size_setting)) {

				$oacs_spl_unlike_text_size_setting = 'inherit';

			}

			if(empty($oacs_spl_unlike_text_color_setting)) {

				$oacs_spl_unlike_text_color_setting = 'inherit';

			}

			if(empty($oacs_spl_unlike_text_padding_setting)) {

				$oacs_spl_unlike_text_padding_setting = 'inherit';

			}

			if (!empty($oacs_spl_unlike_text_size_setting) OR !empty($oacs_spl_unlike_text_color_setting) OR !empty($oacs_spl_unlike_text_padding_setting)) { $oacs_spl_style_tag = ' style="'; $oacs_spl_style_tag_end = '">';} else { $oacs_spl_style_tag = '>' ;}


			$oacs_spl_unlike_text = '<div class="oacs-spl-unlike-text" ' .
			$oacs_spl_style_tag .
			(!empty($oacs_spl_unlike_text_color_setting) ? 'color: ' . $oacs_spl_unlike_text_color_setting : '') .
			(!empty($oacs_spl_unlike_text_size_setting) ? '; font-size: ' . $oacs_spl_unlike_text_size_setting : '') .
			(!empty($oacs_spl_unlike_text_padding_setting) ? '; padding: ' . $oacs_spl_unlike_text_padding_setting : '') .
			$oacs_spl_style_tag_end . $oacs_spl_unlike_text_setting . '</div>';

			if (!empty($oacs_spl_unlike_text)) {return $oacs_spl_unlike_text;}

			return;
		}
}