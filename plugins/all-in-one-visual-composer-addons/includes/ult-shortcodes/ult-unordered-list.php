<?php 
	if (class_exists('WPBakeryShortCode')) {
		class WPBakeryShortCode_wdo_ult_unordered_list extends WPBakeryShortCode {

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
						.list-unique-class-<?php echo $unique_id; ?> ul{
							list-style-type: <?php echo $wdo_list_style_type; ?>;
						}
						.list-unique-class-<?php echo $unique_id; ?> ul li{
							color: <?php echo $wdo_list_color; ?>;
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
			'name'		=> 'Un-Ordered List',
			"description" => __("Add un-ordered lists.", 'wdo-ultimate-addons'),
			'base'		=> 'wdo_ult_unordered_list',
			'category'	=> 'All in One Addons',
			"icon" 		=> ULT_URL.'icons/unorder-list-icon.png',
			'params' => array( 
					array(
						"type" 			=> "textarea_html",
						"heading" 		=> __("Add List Items"),
						"param_name" 	=> "content",
						"value"      => '<ul>
											<li>List Item</li>
											<li>List Item</li>
											<li>List Item</li>
                                        </ul>',
						"description" 	=> __("Replace your text with dummy text.Press enter to add new list item."),
						"group" 		=> 'Listing',
					),

					array(
						"type" => "dropdown",
						"heading" => "List Style Type",
						"param_name" => "wdo_list_style_type",
						"value" => array(
							"Default" => "initial",
							"Disc" => "disc",
							"Circle" => "circle",
							"Square" => "square",
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