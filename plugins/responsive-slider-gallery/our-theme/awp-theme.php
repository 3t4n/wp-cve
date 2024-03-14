<?php
//js
wp_enqueue_script('awl-theme-bootstrap-js', plugin_dir_url( __FILE__ ) .'../js/bootstrap.min.js', array('jquery'), '' , true);

//css
wp_enqueue_style('awl-theme-bootstrap-css', plugin_dir_url( __FILE__ ) .'css/bootstrap.min.css');
wp_enqueue_style('awl-theme-css', plugin_dir_url( __FILE__ ) .'css/our-theme.css');
wp_enqueue_style('awl-theme-font-awesome-css', plugin_dir_url( __FILE__ ) .'css/font-awesome.min.css');

?>
<style>
.awl_theme_container {
	 background-color: #E5E5E5;
	 padding:24px;
}
.theme_spacing {
	margin-bottom:20px;
	margin-top:20px;
}
.theme_spacing_md {
	margin-bottom:70px;
	margin-top:70px;
}
</style>
<div class="welcome-panel">
	<div class="awl_theme_container">
		<div class="container">
			<div class="row">
				<div class="col-md-6 col-sm-6">
					<img src="<?php echo plugin_dir_url( __FILE__ ) ?>img/aneeq-premium.png" class="img-responsive">
				</div>
				<div class="col-md-6 col-sm-6 aneeq_theme_desc">
					<h1 class="theme_spacing"><?php esc_html_e('ANEEQ PREMIUM', 'responsive-slider-gallery'); ?> <span><?php esc_html_e('WORDPRESS THEME', 'responsive-slider-gallery'); ?></span></h1>
					<h4><?php esc_html_e('Aneeq is premium WordPress theme for multi-purpose use. Clean & clear typography with the visually attractive responsive design. 
					Aneeq theme comes with multiple page templates which are completely configurable using Theme Options Panel.', 'responsive-slider-gallery'); ?></h4>
					<hr style="border-color: #b3aeae;">
					<a href="<?php echo esc_url('https://awplife.com/demo/aneeq-premium/'); ?>" target="_blank" class="button button-primary button-hero load-customize hide-if-no-customize"><?php esc_html_e('LIVE DEMO', 'responsive-slider-gallery'); ?></a>
					<a href="<?php echo esc_url('https://awplife.com/wordpress-themes/aneeq-premium/'); ?>" target="_blank"  class="button button-primary button-hero load-customize hide-if-no-customize"><?php esc_html_e('BUY NOW', 'responsive-slider-gallery'); ?></a>
				</div>
			</div>
			<div class="row theme_spacing text-center">
				<div class="col-md-4 col-sm-6">
					<div class="serviceBox">
						<h3 class="title"><?php esc_html_e('Responsive Design', 'responsive-slider-gallery'); ?></h3>
						<div class="service-icon">
							<i class="fa fa-mobile"></i>
						</div>
					</div>
				</div>
		 
				<div class="col-md-4 col-sm-6">
					<div class="serviceBox">
						<h3 class="title"><?php esc_html_e('Multi Purpose', 'responsive-slider-gallery'); ?></h3>
						<div class="service-icon">
							<i class="fa fa-clone"></i>
						</div>
					</div>
				</div>
				
				<div class="col-md-4 col-sm-6">
					<div class="serviceBox">
						<h3 class="title"><?php esc_html_e('High Performance', 'responsive-slider-gallery'); ?></h3>
						<div class="service-icon">
							<i class="fa fa-tachometer"></i>
						</div>
					</div>
				</div>
			</div>
			<div class="row theme_spacing text-center">
				<div class="col-md-4 col-sm-6">
					<div class="serviceBox box effect5">
						<h3 class="title"><?php esc_html_e('Theme Option Panel', 'responsive-slider-gallery'); ?></h3>
						<div class="service-icon">
							<i class="fa fa-cogs"></i>
						</div>
					</div>
				</div>
		 
				<div class="col-md-4 col-sm-6">
					<div class="serviceBox">
						<h3 class="title"><?php esc_html_e('Translation Ready', 'responsive-slider-gallery'); ?></h3>
						<div class="service-icon">
							<i class="fa fa-language"></i>
						</div>
					</div>
				</div>
				
				<div class="col-md-4 col-sm-6">
					<div class="serviceBox">
						<h3 class="title"><?php esc_html_e('Font Awesome Icons', 'responsive-slider-gallery'); ?></h3>
						<div class="service-icon">
							<i class="fa fa-fort-awesome"></i>
						</div>
					</div>
				</div>
			</div>
			
		</div>
	</div>
</div>