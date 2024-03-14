<?php
function custom_customize_css(){
	global $default_setting;
	if (get_theme_mod( 'our_portfolio_bg_image')) {
    	?>
		<style type="text/css">
		.our_portfolio_info{
			background: url(<?php echo esc_attr(get_theme_mod('our_portfolio_bg_image'))?>) rgb(0 0 0 / 0.75);
			background-position: <?php echo esc_attr(get_theme_mod('our_portfolio_bg_position','center center')); ?>;
		    background-attachment: <?php echo esc_attr(get_theme_mod('our_portfolio_design_callback','scroll'));?>;
		    background-size: <?php echo esc_attr(get_theme_mod('our_portfolio_bg_size','cover'));?>;
		    background-blend-mode: multiply;
		}
		</style>
		<?php
    }else{
    	?>
		<style type="text/css">
    	.our_portfolio_info{
			background-color: <?php echo esc_attr(get_theme_mod('our_portfolio_bg_color',$default_setting['our_portfolio_bg_color'],)); ?>;
		}
		</style>
		<?php
    }
    if(get_theme_mod( 'our_services_bg_image')){
		?>
		<style type="text/css">
		.our_services_section{
			background: url(<?php echo esc_attr(get_theme_mod('our_services_bg_image'))?>) rgb(0 0 0 / 0.75);
			background-position: <?php echo esc_attr(get_theme_mod('our_services_bg_position','center center')); ?>;
		    background-attachment: <?php echo esc_attr(get_theme_mod('our_services_bg_attachment','scroll'));?>;
		    background-size: <?php echo esc_attr(get_theme_mod('our_services_bg_size','cover'));?>;
		    background-blend-mode: multiply;
		}
		</style>
		<?php
	}else{
		?>
		<style type="text/css">
    	.our_services_section{
			background: <?php echo esc_attr(get_theme_mod('our_services_bg_color',$default_setting['our_services_bg_color'])); ?>;
		}
		</style>
		<?php
	}
	if(get_theme_mod('our_testimonial_background_image')){
    	?>
		<style>	
		.our_testimonial_section {			
    		background:url(<?php echo  esc_attr(get_theme_mod('our_testimonial_background_image'));?>) rgb(0 0 0 / 0.75);
    		background-position: <?php echo esc_attr(get_theme_mod('our_testimonial_bg_position','center center')); ?>;
    		background-size: <?php echo esc_attr(get_theme_mod('our_testimonial_bg_size','cover')); ?>;
    		background-attachment: <?php echo esc_attr(get_theme_mod('our_testimonial_bg_attachment','fixed')); ?>;
    		background-blend-mode: multiply;
		}
		</style>
		<?php
    }else{
    	?>
		<style>	
		.our_testimonial_section {
			background: <?php echo esc_attr(get_theme_mod('our_team_testimonial_bg_color',$default_setting['our_team_testimonial_bg_color'])); ?>;
		}
		</style>
		<?php
    }
    if(get_theme_mod( 'our_team_bg_image')){
		?>
		<style type="text/css">
		.our_team_section{
			background: url(<?php echo esc_attr(get_theme_mod('our_team_bg_image'))?>) rgb(0 0 0 / 0.75);
			background-position: <?php echo esc_attr(get_theme_mod('our_team_bg_position','center center')); ?>;
		    background-attachment: <?php echo esc_attr(get_theme_mod('our_team_bg_attachment','scroll'));?>;
		    background-size: <?php echo esc_attr(get_theme_mod('our_team_bg_size','cover'));?>;
		    background-blend-mode: multiply;
		}
		</style>
		<?php
	}else{
		?>
		<style type="text/css">
    	.our_team_section{
			background: <?php echo esc_attr(get_theme_mod('our_team_bg_color',$default_setting['our_team_bg_color'])); ?>;
		}
		</style>
		<?php
	}
	?>
	<style type="text/css">
	/*--------------------------------------------------------------
	#  featured slider start
	--------------------------------------------------------------*/
		.featured_slider_disc, .featured_slider_title h1 {
			color: <?php echo esc_attr(get_theme_mod('featured_slider_text_color',$default_setting['featured_slider_text_color'])); ?>;
		}
		.featured_slider_image button.owl-prev, .featured_slider_image button.owl-next{
		    background: <?php echo esc_attr(get_theme_mod('featured_slider_arrow_bg_color',$default_setting['featured_slider_arrow_bg_color'])); ?> !important;
			color: <?php echo esc_attr(get_theme_mod('featured_slider_arrow_text_color',$default_setting['featured_slider_arrow_text_color'])); ?> !important;
		}
		.featured_slider_image button.owl-prev:hover, .featured_slider_image button.owl-next:hover{
		    background: <?php echo esc_attr(get_theme_mod('featured_slider_arrow_bghover_color',$default_setting['featured_slider_arrow_bghover_color'])); ?> !important;
			color: <?php echo esc_attr(get_theme_mod('featured_slider_arrow_texthover_color',$default_setting['featured_slider_arrow_texthover_color'])); ?> !important;
		}
		.featured_slider_image .hentry-inner .entry-container{
			padding: 40px 40px !important;
		}
	/*--------------------------------------------------------------
	#  featured slider end
	--------------------------------------------------------------*/

	/*--------------------------------------------------------------
	# featured section start
	--------------------------------------------------------------*/
		.section-featured-wrep{
			background: <?php echo esc_attr(get_theme_mod('featured_section_bg_color',$default_setting['featured_section_bg_color'])); ?>;	
			color: <?php echo esc_attr(get_theme_mod('featured_section_color',$default_setting['featured_section_color'])); ?>;	
		}
		.section-featured-wrep:hover {
			background: <?php echo esc_attr(get_theme_mod('featured_section_bg_hover_color',$default_setting['featured_section_bg_hover_color'])); ?>;	
			color: <?php echo esc_attr(get_theme_mod('featured_section_text_hover_color',$default_setting['featured_section_text_hover_color'])); ?>;	
		}
		.featured-section_data{
			background: <?php echo esc_attr(get_theme_mod('featured_section_main_bg_color',$default_setting['featured_section_main_bg_color'])); ?>;
			margin: <?php echo esc_attr(get_theme_mod('featured_section_margin','0px 0px 0px 0px')); ?>;
		}
		.featured-thumbnail i {
		    font-size: <?php echo esc_attr(get_theme_mod('featured_section_icon_size','35'));?>px;
		}
		.section-featured-wrep i {
			border: 1px solid <?php echo esc_attr(get_theme_mod('featured_section_icon_bg_color',$default_setting['featured_section_icon_bg_color'])); ?>;
			background: <?php echo esc_attr(get_theme_mod('featured_section_icon_bg_color',$default_setting['featured_section_icon_bg_color'])); ?>;	
			color: <?php echo esc_attr(get_theme_mod('featured_section_icon_color',$default_setting['featured_section_icon_color'])); ?>;
		}
		.section-featured-wrep:hover i {
			background: <?php echo esc_attr(get_theme_mod('featured_section_icon_bg_hover_color',$default_setting['featured_section_icon_bg_hover_color'])); ?>;	
			color: <?php echo esc_attr(get_theme_mod('featured_section_icon_hover_color',$default_setting['featured_section_icon_hover_color'])); ?>;
		}
	/*--------------------------------------------------------------
	# featured section end
	--------------------------------------------------------------*/

	/*--------------------------------------------------------------
	#  About section start
	--------------------------------------------------------------*/
		.about_section_info{
			background-color: <?php echo esc_attr(get_theme_mod('about_bg_color',$default_setting['about_bg_color'])); ?>;
			color: <?php echo esc_attr(get_theme_mod('about_text_color',$default_setting['about_text_color'])); ?>;
		}
		.about_main_title{
			color: <?php echo esc_attr(get_theme_mod('about_title_text_color',$default_setting['about_title_text_color'])); ?>;
		}
		.about_title a{
			color: <?php echo esc_attr(get_theme_mod('about_link_color',$default_setting['about_link_color'])); ?>;
		}
		.about_title a:hover{
			color: <?php echo esc_attr(get_theme_mod('about_link_hover_color',$default_setting['about_link_hover_color'])); ?>;
		}
	/*--------------------------------------------------------------
	#  About section end
	--------------------------------------------------------------*/

	/*--------------------------------------------------------------
	# our portfolio section
	--------------------------------------------------------------*/
		.our_portfolio_info{
			color: <?php echo esc_attr(get_theme_mod('our_portfolio_text_color',$default_setting['our_portfolio_text_color'])); ?>;	
		}
		.our_portfolio_main_title h2{
			color: <?php echo esc_attr(get_theme_mod('our_portfolio_title_color',$default_setting['our_portfolio_title_color'])); ?>;
		}
		.our_portfolio_title{
			color: <?php echo esc_attr(get_theme_mod('our_portfolio_container_text_color',$default_setting['our_portfolio_container_text_color'])); ?>;	
		}
		.our_portfolio_btn a{
			background: <?php echo esc_attr(get_theme_mod('our_portfolio_icon_bg_color',$default_setting['our_portfolio_icon_bg_color'])); ?>;
		}
		.our_portfolio_btn i{
			color: <?php echo esc_attr(get_theme_mod('our_portfolio_icon_color',$default_setting['our_portfolio_icon_color'])); ?>;
		}
		.our_port_containe:before {
			background-color: <?php echo esc_attr(get_theme_mod('our_portfolio_container_bg_color',$default_setting['our_portfolio_container_bg_color'])); ?>;
		}
	/*--------------------------------------------------------------
	# our portfolio section end
	--------------------------------------------------------------*/

	/*--------------------------------------------------------------
	# our services
	--------------------------------------------------------------*/
		.our_services_section{
			color: <?php echo esc_attr(get_theme_mod('our_services_text_color',$default_setting['our_services_text_color'])); ?>;			
		}
		.our_services_section .card{
			color: <?php echo esc_attr(get_theme_mod('our_services_contain_text_color',$default_setting['our_services_contain_text_color'])); ?>;
			background-color: <?php echo esc_attr(get_theme_mod('our_services_contain_bg_color',$default_setting['our_services_contain_bg_color'])); ?>;
		}
		.card .our_services_img i {
		    color: <?php echo esc_attr(get_theme_mod('our_services_icon_color',$default_setting['our_services_icon_color'])); ?>;
		}
		.our_services_section .card:hover {
		    background-color: <?php echo esc_attr(get_theme_mod('our_services_contain_bg_hover_color',$default_setting['our_services_contain_bg_hover_color']));?>;
		}
		.our_services_section .card:hover .back .our_services_img i {
			color: <?php echo esc_attr(get_theme_mod('our_services_icon_hover_color',$default_setting['our_services_icon_hover_color'])); ?>;
		}
		.our_services_data a{
			color: <?php echo esc_attr(get_theme_mod('our_services_link_color',$default_setting['our_services_link_color'])); ?>;
		}
		.our_services_data a:hover{
			color: <?php echo esc_attr(get_theme_mod('our_services_link_hover_color',$default_setting['our_services_link_hover_color'])); ?>;
		}
	/*--------------------------------------------------------------
	# our services ends
	--------------------------------------------------------------*/

	/*--------------------------------------------------------------
	# our team start
	--------------------------------------------------------------*/
		/*.our_team_container{
			background: <?php echo esc_attr(get_theme_mod('our_team_container_bg_color','#eeeeee')); ?>;	
			color: <?php echo esc_attr(get_theme_mod('our_team_container_text_color','#455d58')); ?>;	
		}*/
		.our_teams_contain:hover .our_team_title h3, .our_teams_contain:hover .our_team_headline p {
		    color: <?php echo esc_attr(get_theme_mod('our_team_text_hover_color',$default_setting['our_team_text_hover_color'])); ?>;	;
		}
		.our_team_social_icon i {
		    background: <?php echo esc_attr(get_theme_mod('our_team_icon_background_color',$default_setting['our_team_icon_background_color'])); ?>;
		    color: <?php echo esc_attr(get_theme_mod('our_team_icon_color',$default_setting['our_team_icon_color'])); ?>;
		}
		.our_social_icon i:hover {
		    background: <?php echo esc_attr(get_theme_mod('our_team_icon_bg_hover_color',$default_setting['our_team_icon_bg_hover_color'])); ?>;
		    color:  <?php echo esc_attr(get_theme_mod('our_team_icon_hover_color',$default_setting['our_team_icon_hover_color'])); ?>;
		}
		.our_team_section {
		    color:  <?php echo esc_attr(get_theme_mod('our_team_text_color',$default_setting['our_team_text_color'])); ?>;
		}
		.our_team_title a{
			color:  <?php echo esc_attr(get_theme_mod('our_team_link_color',$default_setting['our_team_link_color'])); ?>;
		}
		.our_team_title a:hover{
			color:  <?php echo esc_attr(get_theme_mod('our_team_link_hover_color',$default_setting['our_team_link_hover_color'])); ?>;
		}
	/*--------------------------------------------------------------
	# our team end
	--------------------------------------------------------------*/

	/*--------------------------------------------------------------
	# our testimonial
	--------------------------------------------------------------*/
		.our_testimonial_section{			
			color:  <?php echo esc_attr(get_theme_mod('our_testimonial_text_color',$default_setting['our_testimonial_text_color'])); ?>;
		}
		.our_testimonials_container {
		    background: <?php echo esc_attr(get_theme_mod('our_testimonial_alpha_color_setting',$default_setting['our_testimonial_alpha_color_setting'])); ?>;
		    color: <?php echo esc_attr(get_theme_mod('our_team_testimonial_text_color',$default_setting['our_team_testimonial_text_color'])); ?>;
		}
		.our_testimonial_section .owl-carousel .owl-nav button.owl-prev, .our_testimonial_section .owl-carousel .owl-nav .owl-next{
			background-color: <?php echo esc_attr(get_theme_mod('our_team_testimonial_arrow_bg_color',$default_setting['our_team_testimonial_arrow_bg_color']));?> !important;
			color:<?php echo esc_attr(get_theme_mod('our_team_testimonial_arrow_text_color',$default_setting['our_team_testimonial_arrow_text_color'])); ?> !important;
		}
		.image_testimonials{
			background: <?php echo esc_attr(get_theme_mod('our_team_testimonial_image_bg_color',$default_setting['our_team_testimonial_image_bg_color']));?> 
		}
	/*--------------------------------------------------------------
	# our testimonial
	--------------------------------------------------------------*/

	/*--------------------------------------------------------------
	# our Sponsors start
	--------------------------------------------------------------*/	
		.our_sponsors_section {
		    background: <?php echo esc_attr(get_theme_mod('our_sponsors_bg_color',$default_setting['our_sponsors_bg_color'])); ?>;
		    color: <?php echo esc_attr(get_theme_mod('our_sponsors_text_color',$default_setting['our_sponsors_text_color'])); ?>;
		}
		.our_sponsors_img:hover{
			background-color: <?php echo esc_attr(get_theme_mod('our_sponsors_img_hover_bg_color',$default_setting['our_sponsors_img_hover_bg_color'])); ?>;
		}
		.our_sponsors_section .our_sponsors_contain:hover .owl-carousel .owl-nav button.owl-prev, .our_sponsors_section .our_sponsors_contain:hover .owl-carousel .owl-nav button.owl-next {
			background: <?php echo esc_attr(get_theme_mod('our_sponsors_arrow_bg_color',$default_setting['our_sponsors_arrow_bg_color'])); ?>;
		    color: <?php echo esc_attr(get_theme_mod('our_sponsors_arrow_color',$default_setting['our_sponsors_arrow_color'])); ?> !important;
		}
	/*--------------------------------------------------------------
	# our Sponsors end
	--------------------------------------------------------------*/
	</style>
	<?php
}
add_action( 'wp_head', 'custom_customize_css');
?>