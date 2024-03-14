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
			<h1 class="theme_spacing"><?php _e('ANEEQ PREMIUM', GGP_TXTDM); ?> <span><?php _e('WORDPRESS THEME', GGP_TXTDM); ?></span></h1>
			<h4><?php _e('Aneeq is premium WordPress theme for multi-purpose use. Clean & clear typography with the visually attractive responsive design. 
			Aneeq theme comes with multiple page templates which are completely configurable using Theme Options Panel.', GGP_TXTDM); ?></h4>
			<hr style="border-color: #b3aeae;">
			<a href="http://awplife.com/demo/aneeq-premium/" target="_blank" class="button button-primary button-hero load-customize hide-if-no-customize"><?php _e('LIVE DEMO', GGP_TXTDM); ?></a>
			<a href="http://awplife.com/wordpress-themes/aneeq-premium/" target="_blank"  class="button button-primary button-hero load-customize hide-if-no-customize"><?php _e('BUY NOW', GGP_TXTDM); ?></a>
		</div>
	</div>
    <div class="row theme_spacing text-center">
		<div class="col-md-4 col-sm-6">
            <div class="serviceBox">
                <h3 class="title"><?php _e('Responsive Design', GGP_TXTDM); ?></h3>
                <div class="service-icon">
                    <i class="fa fa-mobile"></i>
                </div>
            </div>
        </div>
 
        <div class="col-md-4 col-sm-6">
            <div class="serviceBox">
                <h3 class="title"><?php _e('Multi Purpose', GGP_TXTDM); ?></h3>
                <div class="service-icon">
                    <i class="fa fa-clone"></i>
                </div>
            </div>
        </div>
		
		<div class="col-md-4 col-sm-6">
            <div class="serviceBox">
                <h3 class="title"><?php _e('High Performance', GGP_TXTDM); ?></h3>
                <div class="service-icon">
                    <i class="fa fa-tachometer"></i>
                </div>
            </div>
        </div>
    </div>
	<div class="row theme_spacing text-center">
		<div class="col-md-4 col-sm-6">
            <div class="serviceBox box effect5">
                <h3 class="title"><?php _e('Theme Option Panel', GGP_TXTDM); ?></h3>
                <div class="service-icon">
                    <i class="fa fa-cogs"></i>
                </div>
            </div>
        </div>
 
        <div class="col-md-4 col-sm-6">
            <div class="serviceBox">
                <h3 class="title"><?php _e('Translation Ready', GGP_TXTDM); ?></h3>
                <div class="service-icon">
                    <i class="fa fa-language"></i>
                </div>
            </div>
        </div>
		
		<div class="col-md-4 col-sm-6">
            <div class="serviceBox">
                <h3 class="title"><?php _e('Font Awesome Icons', GGP_TXTDM); ?></h3>
                <div class="service-icon">
                    <i class="fa fa-fort-awesome"></i>
                </div>
            </div>
        </div>
    </div>
	
</div>
</div>
</div>