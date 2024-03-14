<?php 
	if (class_exists('WPBakeryShortCode')) {
		class WPBakeryShortCode_wdo_ult_banner extends WPBakeryShortCode {

			protected function content( $atts, $content = null ) {

				extract( shortcode_atts( array(
					'ult_banner_image'		=> '',
					'ult_banner_link'		=> '',
					'ult_banner_target'		=> '_self',
					'ult_banner_vertical_alignment'	=> 'center'
				), $atts ) );

				wp_enqueue_style( 'wdo-banners-css', ULT_URL.'assets/css/ult-banner.css');

				$content = preg_replace('#^<\/p>|<p>$#', '', $content);
				$image_src = wp_get_attachment_url($ult_banner_image);
				
				ob_start(); 
				?>
				<div class="ult-banner ult-banner-va-<?php echo $ult_banner_vertical_alignment; ?>">
					<a class="ult-banner-link" href="<?php echo $ult_banner_link; ?>" target="<?php echo $ult_banner_target; ?>"></a>
					<div class="ult-banner-image">
						<img itemprop="image" src="<?php echo $image_src; ?>">
					</div>
					<div class="ult-banner-content">
						<div class="ult-banner-content-inner">
							<div class="ult-banner-text-holder">
								<?php echo $content; ?>
							</div>
						</div>
					</div>
				</div>

		<?php
			$output = ob_get_clean();

            return $output;
			}
		}
	}

	if ( function_exists( "vc_map" ) ) {
		vc_map( array(
			'name'		=> 'Banners',
			"description" => __("Displays banner information with image.", 'wdo-button'),
			'base'		=> 'wdo_ult_banner',
			'category'	=> 'All in One Addons',
			"icon" 		=> ULT_URL.'icons/banner-icon.png',
			'allowed_container_element' => 'vc_row',
			'params' => array(
				array(
					'type'			=> 'attach_image',
					'heading'		=> 'Image',
					'param_name'	=> 'ult_banner_image',
					'description'	=> 'Use image according to your banner size.',
					'admin_label'	=> true
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> 'Link',
					'param_name'	=> 'ult_banner_link'
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> 'Target',
					'param_name'	=> 'ult_banner_target',
					'value'			=> array(
						'Self'          => '_self',
						'Blank'         => '_blank',
						'Parent'        => '_parent'
					)
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> 'Vertical Alignment',
					'param_name'	=> 'ult_banner_vertical_alignment',
					'value'			=> array(
						'Center'		=> 'center',
						'Top'			=> 'top',
						'Bottom'		=> 'bottom'
					)
				),
				array(
					'type'			=> 'textarea_html',
					'heading'		=> 'Content',
					'value'			=> '<h2 style="text-align:center;">Banner Heading</h2><p>Compellingly re-engineer future-proof growth strategies whereas granular infomediaries. Quickly procrastinate technically sound.</p>',
					'param_name'	=> 'content',
				),

				array(
					"type" => "html",
					"group" => "Demo",
					"heading" => "<h3 style='padding: 10px;background: #2b4b80;text-align:center;'><a style='color: #fff;text-decoration:none;' target='_blank' href='https://demo.webdevocean.com/banners/' >Click to See Demo</a>",
					"param_name" => "demo",
				),

			)
		) );
	}
 ?>