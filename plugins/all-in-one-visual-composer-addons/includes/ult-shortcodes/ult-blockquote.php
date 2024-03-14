<?php 
	if (class_exists('WPBakeryShortCode')) {
		class WPBakeryShortCode_wdo_ult_blockquotes extends WPBakeryShortCode {

			protected function content( $atts, $content = null ) {

				extract(shortcode_atts( array(
				    "wdo_blockquote_text"			=> '',
				    "wdo_quote_width"				=> '', 
				    "wdo_quote_icon"				=> '',
				    "wdo_icon_color"				=> '',
				    "wdo_text_color"				=> '',
				    "wdo_bg_color"					=> '',
				), $atts));

				wp_enqueue_style( 'wdo-blockqoute-css', ULT_URL.'assets/css/blockquote.css');
				$unique_id = rand(5, 500);
				$blockquote_text = preg_replace('#^<\/p>|<p>$#', '', $wdo_blockquote_text);
				ob_start();
				
				?>
				<style>
					.unique-class-<?php echo $unique_id; ?> blockquote::before{
						color: <?php echo $wdo_icon_color; ?> !important;
					}
				</style>
				<div class="ult-blockquote-container unique-class-<?php echo $unique_id; ?>">
					<blockquote style="background: <?php echo $wdo_bg_color; ?>;color: <?php echo $wdo_text_color; ?>;border-left: 8px solid <?php echo $wdo_icon_color; ?>;width: <?php echo $wdo_quote_width; ?>;">
					  <?php echo $blockquote_text; ?>
					</blockquote>
				</div>
				
		<?php
			return ob_get_clean();
			}
		}
	}

	if ( function_exists( "vc_map" ) ) {
		vc_map( array(
			'name'		=> 'Blockquote',
			"description" => __("Show quoted text.", 'wdo-ultimate-addons'),
			'base'		=> 'wdo_ult_blockquotes',
			'category'	=> 'All in One Addons',
			"icon" 		=> ULT_URL.'icons/blockquote-icon.png',
			'allowed_container_element' => 'vc_row',
			'params' => array(
					array(
						"type" => "textarea",
						"heading" => "Text",
						"param_name" => "wdo_blockquote_text",
						"value" => "Blockquote Text",
					),

					array(
						"type" => "textfield",
						"heading" => "Width",
						"param_name" => "wdo_quote_width",
						"description" => __("Give width in % .", 'wdo-ultimate-addons'),
					),
					


					/**** Styles Group Start ******/

				    array(
				        "type" => "colorpicker",
				        "heading" => "Quote Text Color",
				        "param_name" => "wdo_text_color",
				        "group" 		=> 'Styles',
				    ),

				    array(
				        "type" => "colorpicker",
				        "heading" => "Quote Icon Color",
				        "param_name" => "wdo_icon_color",
				        "group" 		=> 'Styles',
				    ),

					array(
						"type" => "colorpicker",
						"heading" => "Background Color",
						"param_name" => "wdo_bg_color",
						"group" 		=> 'Styles',
					),

				
				    /**** On Hover Styles End ***/

			)
		) );
	}
 ?>