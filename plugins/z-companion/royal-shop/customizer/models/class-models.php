<?php
if ( ! defined( 'ABSPATH' ) ) exit; 
/**
 * This file stores all functions that return default content.
 *
 * @package  Royal Shop
 */
/**
 * Class Z_COMPANION_Royal_Shop_Defaults_Models
 *
 * @package  Royal Shop
 */
class Z_COMPANION_Royal_Shop_Defaults_Models extends Z_COMPANION_Royal_Shop_Singleton{
/**
	 * Get default values for features section.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	/**
	 * Get default values for Brands section.

	 * @access public
	 */
public function get_brand_default() {
		return apply_filters(
			'royal_shop_brand_default_content', json_encode(
				array(
					array(
						'image_url' => '',
						'link'       => '#',
					),
					array(
						'image_url' => '',
						'link'       => '#',
					),
					array(
						'image_url' => '',
						'link'       => '#',
					),
					array(
						'image_url' => '',
						'link'       => '#',
					),
					array(
						'image_url' => '',
						'link'       => '#',
					),
					array(
						'image_url' => '',
						'link'       => '#',
					),
				)
			)
		);
	}


	/**
	 * Get default values for features section.

	 * @access public
	 */
	public function get_feature_default() {
		return apply_filters(
			'royal_shop_highlight_default_content', json_encode(
				array(
					array(
						'icon_value' => 'fa-bullseye',
						'title'      => esc_html__( 'Low Rates', 'z-companion' ),
						'subtitle'   => esc_html__( 'Over sale Prducts', 'z-companion' ),
						
					),
					array(
						'icon_value' => 'fa-bullseye',
						'title'      => esc_html__( 'Low Rates', 'z-companion' ),
						'subtitle'   => esc_html__( 'Over sale Prducts', 'z-companion' ),
						
					),
					array(
						'icon_value' => 'fa-bullseye',
						'title'      => esc_html__( 'Low Rates', 'z-companion' ),
						'subtitle'   => esc_html__( 'Over sale Prducts', 'z-companion' ),
						
					),
					array(
						'icon_value' => 'fa-bullseye',
						'title'      => esc_html__( 'Low Rates', 'z-companion' ),
						'subtitle'   => esc_html__( 'Over sale Prducts', 'z-companion' ),
						
					),
				)
			)
		);
	}	


	public function get_faq_default() {
		return apply_filters(
			'royalshop_faq_default_content', json_encode(
				array( 
					array(
						'title'     => esc_html__( 'What do you want to know', 'z-companion' ),
						
						'text'      => esc_html__( 'Nulla et sodales nisl. Nam auctor quis odio eu congue. Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'z-companion' ),
					),

					array(
						'title'     => esc_html__( 'What do you want to know', 'z-companion' ),
						
						'text'      => esc_html__( 'Nulla et sodales nisl. Nam auctor quis odio eu congue. Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'z-companion' ),
					),
					
					array(
						'title'     => esc_html__( 'What do you want to know', 'z-companion' ),
						
						'text'      => esc_html__( 'Nulla et sodales nisl. Nam auctor quis odio eu congue. Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'z-companion' ),
					),

					array(
						'title'     => esc_html__( 'What do you want to know', 'z-companion' ),
						
						'text'      => esc_html__( 'Nulla et sodales nisl. Nam auctor quis odio eu congue. Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'z-companion' ),
					),

					array(
						'title'     => esc_html__( 'What do you want to know', 'z-companion' ),
						
						'text'      => esc_html__( 'Nulla et sodales nisl. Nam auctor quis odio eu congue. Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'z-companion' ),
					),

					array(
						'title'     => esc_html__( 'What do you want to know', 'z-companion' ),
						
						'text'      => esc_html__( 'Nulla et sodales nisl. Nam auctor quis odio eu congue. Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'z-companion' ),
					),

					array(
						'title'     => esc_html__( 'What do you want to know', 'z-companion' ),
						
						'text'      => esc_html__( 'Nulla et sodales nisl. Nam auctor quis odio eu congue. Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'z-companion' ),
					),

					array(
						'title'     => esc_html__( 'What do you want to know', 'z-companion' ),
						
						'text'      => esc_html__( 'Nulla et sodales nisl. Nam auctor quis odio eu congue. Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'z-companion' ),
					),

				)
			)
		);	
	}

	/**
	 * Get default values for features section.

	 * @access public
	 */
	public function get_service_default() {
		return apply_filters(
			'royal_shop_service_default_content', json_encode(
				array(
					array(
						'icon_value' => 'fa-diamond',
						'title'      => esc_html__( 'Development', 'z-companion' ),
						'text'       => esc_html__( 'Nam varius mauris eget sodales tempus. Quisque sollicitudin consectetur accumsan. Ut imperdiet mi velit, ut congue justo sagittis eget',
							'z-companion' ),
						'link'       => '#',
						'color'      => '#ff214f',
					),
					array(
						'icon_value' => 'fa-heart',
						'title'      => esc_html__( 'Design', 'z-companion' ),
						'text'       => esc_html__( 'Nam varius mauris eget sodales tempus. Quisque sollicitudin consectetur accumsan. Ut imperdiet mi velit, ut congue justo sagittis eget',
							'z-companion' ),
						'link'       => '#',
						'color'      => '#00bcd4',
					),
					array(
						'icon_value' => 'fa-globe',
						'title'      => esc_html__( 'Seo', 'z-companion' ),
						'text'       => esc_html__( 'Nam varius mauris eget sodales tempus. Quisque sollicitudin consectetur accumsan. Ut imperdiet mi velit, ut congue justo sagittis eget',
							'z-companion' ),
						'link'       => '#',
						'color'      => '#4caf50',
					),
				)
			)
		);
	}	

	/**
	 * Get default values for Testimonials section.

	 * @access public
	 */
public function get_testimonials_default() {
		return apply_filters(
			'royal_shop_testimonials_default_content', json_encode(
				array(
					array(
						'image_url' =>	royal_shop_THEME_URI . '/image/testimonial1.png',
						'title'     => esc_html__( 'Surbhi', 'z-companion' ),
						'subtitle'  => esc_html__( 'Business Owner', 'z-companion' ),
						'text'      => esc_html__( '"Nunc eu elementum libero. Etiam egestas leo eget urna ultrices, in finibus eros gravida. Donec scelerisque pulvinar dapibus. Nam pretium risus sed metus ultrices blandit. Pellentesque rhoncus est non nunc ultricies accumsan. Nullam gravida turpis et lacinia cursus. Fusce iaculis mattis consectetur."', 'z-companion' ),
						'link'		=>	'#',
						'id'        => 'customizer_repeater_56d7ea7f40d56',
					),
					array(
						'image_url' =>	royal_shop_THEME_URI . '/image/testimonial2.png',
						'title'     => esc_html__( 'Nataliya', 'z-companion' ),
						'subtitle'  => esc_html__( 'Artist', 'z-companion' ),
						'text'      => esc_html__( '"Nunc eu elementum libero. Etiam egestas leo eget urna ultrices, in finibus eros gravida. Donec scelerisque pulvinar dapibus. Nam pretium risus sed metus ultrices blandit. Pellentesque rhoncus est non nunc ultricies accumsan. Nullam gravida turpis et lacinia cursus. Fusce iaculis mattis consectetur."', 'z-companion' ),
						'link'		=>	'#',
						'id'        => 'customizer_repeater_56d7ea7f40d66',
					),

					array(
						'image_url' =>	royal_shop_THEME_URI . '/image/testimonial1.png',
						'title'     => esc_html__( 'Ramedrin', 'z-companion' ),
						'subtitle'  => esc_html__( 'Business Owner', 'z-companion' ),
						'text'      => esc_html__( '"Nunc eu elementum libero. Etiam egestas leo eget urna ultrices, in finibus eros gravida. Donec scelerisque pulvinar dapibus. Nam pretium risus sed metus ultrices blandit. Pellentesque rhoncus est non nunc ultricies accumsan. Nullam gravida turpis et lacinia cursus. Fusce iaculis mattis consectetur."', 'z-companion' ),
						'link'		=>	'#',
						'id'        => 'customizer_repeater_56d7ea7f40d56',
					),
				)
			)
		);
	}


public function get_team_default() {
		return apply_filters(
			'royal_shop_team_default_content', json_encode(
				array( 
					array(
						'title'     => esc_html__( 'Gabriel', 'z-companion' ),					
						'subtitle'  => esc_html__( 'Developer', 'z-companion' ),
						'text'      => esc_html__( 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', 'z-companion' ),
						'image_url' => royal_shop_THEME_URI . 'image/team2.jpg',
						'link'       => '#',
						'social_repeater' => json_encode(
							array(
									array(
									
									'link' => 'youtube.com',
									'icon' => 'fa-youtube',
									),
									array(
									
									'link' => 'twitter.com',
									'icon' => 'fa-twitter',
									),
								array(
									
									'link' => 'linkedin.com',
									'icon' => 'fa-linkedin',
								),
							)
						),
					),

					array(
						'title'     => esc_html__( 'Maurics', 'z-companion' ),					
						'subtitle'  => esc_html__( 'Marketer', 'z-companion' ),
						'text'      => esc_html__( 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', 'z-companion' ),
						'image_url' => royal_shop_THEME_URI . 'image/team2.jpg',
						'link'       => '#',
						'social_repeater' => json_encode(
							array(
									array(
									
									'link' => 'youtube.com',
									'icon' => 'fa-youtube',
									),
									array(
									
									'link' => 'twitter.com',
									'icon' => 'fa-twitter',
									),
								array(
									
									'link' => 'linkedin.com',
									'icon' => 'fa-linkedin',
								),
							)
						),
					),

					array(
						'title'     => esc_html__( 'Ramedrin', 'z-companion' ),					
						'subtitle'  => esc_html__( 'Designer', 'z-companion' ),
						'text'      => esc_html__( 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', 'z-companion' ),
						'image_url' => royal_shop_THEME_URI . 'image/team2.jpg',
						'link'       => '#',
						'social_repeater' => json_encode(
							array(
									array(
									
									'link' => 'youtube.com',
									'icon' => 'fa-youtube',
									),
									array(
									
									'link' => 'twitter.com',
									'icon' => 'fa-twitter',
									),
								array(
									
									'link' => 'linkedin.com',
									'icon' => 'fa-linkedin',
								),
							)
						),
					),	
					array(
						'title'     => esc_html__( 'Ramedrin', 'z-companion' ),					
						'subtitle'  => esc_html__( 'Designer', 'z-companion' ),
						'text'      => esc_html__( 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', 'z-companion' ),
						'image_url' => royal_shop_THEME_URI . 'image/team2.jpg',
						'link'       => '#',
						'social_repeater' => json_encode(
							array(
									array(
									
									'link' => 'youtube.com',
									'icon' => 'fa-youtube',
									),
									array(
									
									'link' => 'twitter.com',
									'icon' => 'fa-twitter',
									),
								array(
									
									'link' => 'linkedin.com',
									'icon' => 'fa-linkedin',
								),
							)
						),
					),	

				)///	
			)	
		);
	}

	/**
	 * Get default values for Counter section.

	 * @access public
	 */
public function get_counter_default() {
		return apply_filters(
			'royal_shop_counter_default_content', json_encode(
				array(
					array(
						
						'title'       => 'Tea Consumed',
						'number' => esc_html__( '1008', 'z-companion' ),
					),
					array(
						'title'       => 'Projects Completed',
						'number' => esc_html__( '1008', 'z-companion' ),
					),
					array(
						'title'       => 'Hours Spent',
						'number' => esc_html__( '1008', 'z-companion' ),
					),
					array(
						'title'       => 'Awards Recieved',
						'number' => esc_html__( '1008', 'z-companion' ),
					),
				)
			)
		);
	}	
}