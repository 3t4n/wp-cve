<?php
$MediaId = get_option('specia_media_id');
$title  			= 'Make a Great Website with <span>Specia</span>';
$content			= '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever.</p>';
$title2 			= 'We Create <span>Value &amp; Build</span> Confidence';
$title3 			= 'World Digital Resolutions for <span>Business Leaders</span>';
$title4 			= 'Specia well suited for any types of websites';
$cta_content		= 'Avira,Proficient,HeroPress,MagZee,Fabify';
$cta_pg_ttl1		= 'Trusted Services';
$cta_content1		= 'We are trusted our customers';
$cta_pg_ttl2		= '24x7 Supports';
$cta_content2		= '014 1265 478 – 123 4567 890';
$cta_pg_ttl3		= 'Well Experienced';
$cta_content3		= '25 years of experience';
$cta_pg_ttl4		= 'Process Improved';
$cta_content4		= 'Believe in process improvements';
$service_pg_ttl1	= 'Easy Customizable';
$service_pg_ttl2	= 'WooCommerce Ready';
$service_pg_ttl3	= 'Elementor Plugin';
$service_data		= 'Customize everything from the theme Appearance customize.';
$portfolio_pg_ttl1	= 'Responsive Design';
$portfolio_pg_ttl2	= 'Ethics Features';
$portfolio_pg_ttl3	= 'Plugin Supports';
$blog_ttl1			= 'The most popular page builder included';
$blog_ttl2			= 'Presentations play role in market';
$blog_ttl3			= 'A digital prescription for business';
$postData = array(
				array(
					'post_title' => $title,
					'post_status' => 'publish',
					'post_content' => $content,
					'post_author' => 1,
					'post_type'         =>   'page',
					'post_date' => date('Y-m-d H:i:s'),
					'post_name' => $title,
					'meta_input'   => array(
						'slider_caption_align' => 'text-left',
						'slider_subtitle' => 'Digital Marketing Starategy',
						'slidebutton' => 'Buy Now',
					),
				),
				array(
					'post_title' => $title2,
					'post_status' => 'publish',
					'post_content' => $content,
					'post_author' => 1,
					'post_type'         =>   'page',
					'post_date' => date('Y-m-d H:i:s'),
					'post_name' => $title2,
					'meta_input'   => array(
						'slider_caption_align' => 'text-center',
						'slider_subtitle' => 'Succesful Goal & Plans',
						'slidebutton' => 'Buy Now',
					),
				),
				array(
					'post_title' => $title3,
					'post_status' => 'publish',
					'post_content' => $content,
					'post_author' => 1,
					'post_type'         =>   'page',
					'post_date' => date('Y-m-d H:i:s'),
					'post_name' => $title3,
					'meta_input'   => array(
						'slider_caption_align' => 'text-right',
						'slider_subtitle' => 'Think accurately for new business',
						'slidebutton' => 'Buy Now',
					),
				),
				array(
					'post_title' => $title4,
					'post_status' => 'publish',
					'post_content' => $cta_content,
					'post_author' => 1,
					'post_type'         =>   'page',
					'post_date' => date('Y-m-d H:i:s'),
					'post_name' => $cta_content,
				),
				array(
					'post_title' => $service_pg_ttl1,
					'post_status' => 'publish',
					'post_content' => $service_data,
					'post_author' => 1,
					'post_type'         =>   'page',
					'post_date' => date('Y-m-d H:i:s'),
					'post_name' => $service_pg_ttl1,
					'meta_input'   => array(
						'icons' => 'fa-file-text-o',
					),
				),
				array(
					'post_title' => $service_pg_ttl2,
					'post_status' => 'publish',
					'post_content' => $service_data,
					'post_author' => 1,
					'post_type'         =>   'page',
					'post_date' => date('Y-m-d H:i:s'),
					'post_name' => $service_pg_ttl2,
					'meta_input'   => array(
						'icons' => 'fa-cart-plus',
					),
				),
				array(
					'post_title' => $service_pg_ttl3,
					'post_status' => 'publish',
					'post_content' => $service_data,
					'post_author' => 1,
					'post_type'         =>   'page',
					'post_date' => date('Y-m-d H:i:s'),
					'post_name' => $service_pg_ttl3,
					'meta_input'   => array(
						'icons' => 'fa-columns',
					),
				),
				array(
					'post_title' => $portfolio_pg_ttl1,
					'post_status' => 'publish',
					'post_content' => $service_data,
					'post_author' => 1,
					'post_type'         =>   'page',
					'post_date' => date('Y-m-d H:i:s'),
					'post_name' => $portfolio_pg_ttl1,
					'meta_input'   => array(
						'icons' => 'fa-columns',
					),
				),
				array(
					'post_title' => $portfolio_pg_ttl2,
					'post_status' => 'publish',
					'post_content' => $service_data,
					'post_author' => 1,
					'post_type'         =>   'page',
					'post_date' => date('Y-m-d H:i:s'),
					'post_name' => $portfolio_pg_ttl2,
					'meta_input'   => array(
						'icons' => 'fa-columns',
					),
				),
				array(
					'post_title' => $portfolio_pg_ttl3,
					'post_status' => 'publish',
					'post_content' => $service_data,
					'post_author' => 1,
					'post_type'         =>   'page',
					'post_date' => date('Y-m-d H:i:s'),
					'post_name' => $portfolio_pg_ttl3,
					'meta_input'   => array(
						'icons' => 'fa-columns',
					),
				),
				array(
					'post_title' => $blog_ttl1,
					'post_status' => 'publish',
					'post_content' => $content,
					'post_author' => 1,
					'post_type'         =>   'post',
					'post_category' => array('latest Post','News'),
				),
				array(
					'post_title' => $blog_ttl2,
					'post_status' => 'publish',
					'post_content' => $content,
					'post_author' => 1,
					'post_type'         =>   'post',
					'post_category' => array('latest Post','News'),
				),
				array(
					'post_title' => $blog_ttl3,
					'post_status' => 'publish',
					'post_content' => $content,
					'post_author' => 1,
					'post_type'         =>   'post',
					'post_category' => array('latest Post','News'),
				),
				array(
					'post_title' => $cta_pg_ttl1,
					'post_status' => 'publish',
					'post_content' => $cta_content1,
					'post_author' => 1,
					'post_type'         =>   'page',
					'post_date' => date('Y-m-d H:i:s'),
					'post_name' => $cta_pg_ttl1,
					'meta_input'   => array(
						'icons' => 'fa-file-text-o',
					),
				),
				array(
					'post_title' => $cta_pg_ttl2,
					'post_status' => 'publish',
					'post_content' => $cta_content2,
					'post_author' => 1,
					'post_type'         =>   'page',
					'post_date' => date('Y-m-d H:i:s'),
					'post_name' => $cta_pg_ttl2,
					'meta_input'   => array(
						'icons' => 'fa-transgender-alt',
					),
				),
				array(
					'post_title' => $cta_pg_ttl3,
					'post_status' => 'publish',
					'post_content' => $cta_content3,
					'post_author' => 1,
					'post_type'         =>   'page',
					'post_date' => date('Y-m-d H:i:s'),
					'post_name' => $cta_pg_ttl3,
					'meta_input'   => array(
						'icons' => 'fa-columns',
					),
				),
				array(
					'post_title' => $cta_pg_ttl4,
					'post_status' => 'publish',
					'post_content' => $cta_content4,
					'post_author' => 1,
					'post_type'         =>   'page',
					'post_date' => date('Y-m-d H:i:s'),
					'post_name' => $cta_pg_ttl4,
					'meta_input'   => array(
						'icons' => 'fa-bar-chart',
					),
				),
			);

kses_remove_filters();
//foreach ( $MediaId as $media) :
foreach ( $postData as $i => $postData1) : 
	$id = wp_insert_post($postData1);
	
	if($i==0){
		set_post_thumbnail( $id, $MediaId[$i + 1] );
		set_theme_mod('slider-page1',$id);
	}
	
	if($i==1){
		set_post_thumbnail( $id, $MediaId[$i + 1] );
		set_theme_mod('slider-page2',$id);
	}
	
	if($i==2){
		set_post_thumbnail( $id, $MediaId[$i + 1] );
		set_theme_mod('slider-page3',$id);
	}
	
	if($i==3){
		set_theme_mod('call_action_page',$id);
	}	
	
	if($i==4) {
		set_theme_mod('service-page1',$id);
	}
	
	if($i==5) {
		set_theme_mod('service-page2',$id);
	}
	
	if($i==6) {
		set_theme_mod('service-page3',$id);
	}
	
	if($i==7) {
		set_post_thumbnail( $id, $MediaId[4] );
		set_theme_mod('portfolio-page1',$id);
	}
	
	if($i==8) {
		set_post_thumbnail( $id, $MediaId[5] );
		set_theme_mod('portfolio-page2',$id);
	}
	
	if($i==9) {
		set_post_thumbnail( $id, $MediaId[6] );
		set_theme_mod('portfolio-page3',$id);
	}
	
	if($i==10){
		set_post_thumbnail( $id, $MediaId['1'] );
	}
	
	if($i==11){
		set_post_thumbnail( $id, $MediaId['2'] );
	}
	
	if($i==12){
		set_post_thumbnail( $id, $MediaId['3'] );
	}
	
	if($i==13){
		set_theme_mod('call_action_page1',$id);
	}
	
	if($i==14){
		set_theme_mod('call_action_page2',$id);
	}
	
	if($i==15){
		set_theme_mod('call_action_page3',$id);
	}
	
	if($i==16){
		set_theme_mod('call_action_page4',$id);
	}
	
endforeach;
//endforeach;
	
kses_init_filters();
