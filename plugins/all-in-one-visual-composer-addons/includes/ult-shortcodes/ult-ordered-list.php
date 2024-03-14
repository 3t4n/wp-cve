<?php 
	if (class_exists('WPBakeryShortCode')) {
		class WPBakeryShortCode_wdo_ult_ordered_list extends WPBakeryShortCode {

			protected function content( $atts, $content = null ) {

				extract(shortcode_atts( array(
				    "wdo_list_style_type"	=> '',
				    "wdo_list_color"		=> '',
				), $atts));
				wp_enqueue_style( 'wdo-listing-css', ULT_URL.'assets/css/wdo-listings.css');
				$content = wpb_js_remove_wpautop($content, true);
				$unique_id = rand(5, 500);
				ob_start();
				?>
					<style>
						.list-unique-class-<?php echo $unique_id; ?> ol{
							list-style-type: <?php echo $wdo_list_style_type; ?> !important;
						}
						.list-unique-class-<?php echo $unique_id; ?> ol li{
							color: <?php echo $wdo_list_color; ?> !important;
						}
					</style>
					<div class="wdo-list-container list-unique-class-<?php echo $unique_id; ?>">
						<?php echo $content; ?>
					</div>
			<?php
				return ob_get_clean();
			}
		}
	}

	if ( function_exists( "vc_map" ) ) {
		vc_map( array(
			'name'		=> 'Ordered List',
			"description" => __("Add ordered lists.", 'wdo-ultimate-addons'),
			'base'		=> 'wdo_ult_ordered_list',
			'category'	=> 'All in One Addons',
			"icon" 		=> ULT_URL.'icons/order-list-icon.png',
			'params' => array( 
					array(
						"type" 			=> "textarea_html",
						"heading" 		=> __("Add List Items"),
						"param_name" 	=> "content",
						"value"      => '<ol>
											<li>List Item</li>
											<li>List Item</li>
											<li>List Item</li>
                                        </ol>',
						"description" 	=> __("Replace your text with dummy text.Press enter to add new list item."),
						"group" 		=> 'Listing',
					),

					array(
						"type" => "dropdown",
						"heading" => "List Style Type",
						"param_name" => "wdo_list_style_type",
						"value" => array(
							"Default" => "initial",
							"Decimal (1, 2, 3)" => "decimal",
							"Decimal Leading Zero (01, 02, 03)" => "decimal-leading-zero",
							"Lower Alpha (a, b, c)" => "lower-alpha",
							"Lower Roman (i, ii, iii)" => "lower-roman",
							"Upper Alpha (A, B, C)" => "upper-alpha",
							"Upper Roman (I, II, III)" => "upper-roman",
						),
						"description" => "Select marker with list items.",
						"group" 		=> 'Styling',
					),

					array(
						"type"       => "colorpicker",
						"heading"    => __( "Listing Color", "wdo-ultimate-addons" ),
						"param_name" => "wdo_list_color",
						"group" 		=> 'Styling',
					),
					

			)
		) );
	}
 ?>