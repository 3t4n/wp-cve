<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;} // Exit if accessed directly ?>

<div class="sf-panel p-3 m-3" style="">
	<div class="row mb-3 bg-dark bg-gradient" style="--bs-gutter-x: 0rem;">
		<div class="col-6">
			<div class="p-3">
				<h1 class="sf-heading d-inline"><?php esc_html_e( 'SLIDER LAYOUTS', 'slider-factory' ); ?></h1>
				<p class="sf-heading p-0 mx-3 d-inline"><?php esc_html_e( 'Slider Factory Version', 'slider-factory' ); ?> <?php echo esc_html(get_option( 'wpfrank_sf_current_version' )); ?></p>
			</div>
		</div>
		<div class="col-6">
			<div class="p-3">
				<button class="btn btn-lg btn-danger bg-gradient float-end" data-bs-toggle="modal" data-bs-target="#sf-sliders"><strong><?php esc_html_e( 'All Slider Shortcode', 'slider-factory' ); ?></strong></button>
			</div>
		</div>
	</div>
	
	<?php
	$sf_create_nonce = wp_create_nonce( 'sf-create-nonce' );
	?>
	<div class="row" style="--bs-gutter-x: 0rem;">
		<!--1 Flickity-->
		<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
			<div class="sf-inner-box shadow-sm m-2 text-center border-end border-top border-bottom box">
				<div class="ribbon"><span><?php esc_html_e( 'LAYOUT', 'slider-factory' ); ?> 1</span></div>
				<img class="img-fluid" src="<?php echo esc_url(plugin_dir_url( __FILE__ ).'assets/banners/layout-1.jpg'); ?>">
				<div class="p-3">
					<button type="button" class="slider-info btn btn-danger bg-gradient" style="background-color: #e76f51; border-color: #e76f51;" data-bs-toggle="tooltip" data-bs-html="true" title="<b>Layout 1 Features</b><br>&bull;AutoPay<br>&bull;Touch Swipe<br>&bull;Navigation Control<br>&bull;Pagination Dots<br>&bull;Slide Title<br>&bull;Slide Description<br>&bull;Custom Width<br>&bull;Custom Height<br>&bull;Slide Sorting">
					<i class="fas fa-info"></i> <?php esc_html_e( 'Info', 'slider-factory' ); ?>
					</button>
					<a href="https://wpfrank.com/demo/slider-factory-free-wordpress-plugin/layout-1/" target="_blank" id="slider-demo" class="slider-demo btn btn-danger bg-gradient" style="background-color: #e76f51; border-color: #e76f51;">
						<i class="fas fa-desktop"></i> <?php esc_html_e( 'Demo', 'slider-factory' ); ?>
					</a>
					<a class="btn btn-danger bg-gradient" style="background-color: #e76f51; border-color: #e76f51;" href="admin.php?page=sf-manage-slider&sf-slider-action=create&sf-slider-layout=1&sf-create-nonce=<?php echo esc_attr( $sf_create_nonce ); ?>">
						<i class="fas fa-plus"></i> <?php esc_html_e( 'Create', 'slider-factory' ); ?>
					</a>
				</div>
			</div>
		</div>
		
		<!--2 PhotoRoller-->
		<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
			<div class="sf-inner-box shadow-sm m-2 text-center border-end border-top border-bottom box">
				<div class="ribbon"><span><?php esc_html_e( 'LAYOUT', 'slider-factory' ); ?> 2</span></div>
				<img class="img-fluid" src="<?php echo esc_url(plugin_dir_url( __FILE__ ).'assets/banners/layout-2.jpg'); ?>">
				<div class="p-3">
					<button type="button" class="slider-info btn btn-danger bg-gradient" style="background-color: #e76f51; border-color: #e76f51;" data-bs-toggle="tooltip" data-bs-html="true" title="<b>Layout 2 Features</b><br>&bull;Mouse Hover Slide Show<br>&bull;Slide Title<br>&bull;Slide Description<br>&bull;Custom Width<br>&bull;Custom Height<br>&bull;Slide Sorting">
					<i class="fas fa-info"></i> <?php esc_html_e( 'Info', 'slider-factory' ); ?>
					</button>
					<a href="https://wpfrank.com/demo/slider-factory-free-wordpress-plugin/layout-2/" target="_blank" id="slider-demo" class="slider-demo btn btn-danger bg-gradient" style="background-color: #e76f51; border-color: #e76f51;">
						<i class="fas fa-desktop"></i> <?php esc_html_e( 'Demo', 'slider-factory' ); ?>
					</a>
					<a class="btn btn-danger bg-gradient" style="background-color: #e76f51; border-color: #e76f51;" href="admin.php?page=sf-manage-slider&sf-slider-action=create&sf-slider-layout=2&sf-create-nonce=<?php echo esc_attr( $sf_create_nonce ); ?>">
						<i class="fas fa-plus"></i> <?php esc_html_e( 'Create', 'slider-factory' ); ?>
					</a>
				</div>
			</div>
		</div>
		
		<!--3 Accordion Carousel Blue Slider-->
		<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
			<div class="sf-inner-box shadow-sm m-2 text-center border-end border-top border-bottom box">
				<div class="ribbon"><span><?php esc_html_e( 'LAYOUT', 'slider-factory' ); ?> 3</span></div>
				<img class="img-fluid" src="<?php echo esc_url(plugin_dir_url( __FILE__ ).'assets/banners/layout-3.jpg'); ?>">
				<div class="p-3">
					<button type="button" class="slider-info btn btn-danger bg-gradient" style="background-color: #e76f51; border-color: #e76f51;" data-bs-toggle="tooltip" data-bs-html="true" title="<b>Layout 3 Features</b><br>&bull;AutoPay<br>&bull;Touch Swipe<br>&bull;Navigation Control<br>&bull;Slide Title - NO<br>&bull;Slide Description - NO <br>&bull;Custom Width<br>&bull;Custom Height<br>&bull;Slide Sorting">
					<i class="fas fa-info"></i> <?php esc_html_e( 'Info', 'slider-factory' ); ?>
					</button>
					<a href="https://wpfrank.com/demo/slider-factory-free-wordpress-plugin/layout-3/" target="_blank" id="slider-demo" class="slider-demo btn btn-danger bg-gradient" style="background-color: #e76f51; border-color: #e76f51;">
						<i class="fas fa-desktop"></i> <?php esc_html_e( 'Demo', 'slider-factory' ); ?>
					</a>
					<a class="btn btn-danger bg-gradient" style="background-color: #e76f51; border-color: #e76f51;" href="admin.php?page=sf-manage-slider&sf-slider-action=create&sf-slider-layout=3&sf-create-nonce=<?php echo esc_attr( $sf_create_nonce ); ?>">
						<i class="fas fa-plus"></i> <?php esc_html_e( 'Create', 'slider-factory' ); ?>
					</a>
				</div>
			</div>
		</div>
		
		<!--4 Camera Slider Master-->
		<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
			<div class="sf-inner-box shadow-sm m-2 text-center border-end border-top border-bottom box">
				<div class="ribbon"><span><?php esc_html_e( 'LAYOUT', 'slider-factory' ); ?> 4</span></div>
				<img class="img-fluid" src="<?php echo esc_url(plugin_dir_url( __FILE__ ).'assets/banners/layout-4.jpg'); ?>">
				<div class="p-3">
					<button type="button" class="slider-info btn btn-danger bg-gradient" style="background-color: #e76f51; border-color: #e76f51;" data-bs-toggle="tooltip" data-bs-html="true" title="<b>Layout 4 Features</b><br>&bull;AutoPay<br>&bull;Navigation Control<br>&bull;Slide Loading Bar<br>&bull;Slide Title<br>&bull;Slide Description<br>&bull;Custom Width<br>&bull;Custom Height<br>&bull;Slide Sorting">
					<i class="fas fa-info"></i> <?php esc_html_e( 'Info', 'slider-factory' ); ?>
					</button>
					<a href="https://wpfrank.com/demo/slider-factory-free-wordpress-plugin/layout-4/" target="_blank" id="slider-demo" class="slider-demo btn btn-danger bg-gradient" style="background-color: #e76f51; border-color: #e76f51;">
						<i class="fas fa-desktop"></i> <?php esc_html_e( 'Demo', 'slider-factory' ); ?>
					</a>
					<a class="btn btn-danger bg-gradient" style="background-color: #e76f51; border-color: #e76f51;" href="admin.php?page=sf-manage-slider&sf-slider-action=create&sf-slider-layout=4&sf-create-nonce=<?php echo esc_attr( $sf_create_nonce ); ?>">
						<i class="fas fa-plus"></i> <?php esc_html_e( 'Create', 'slider-factory' ); ?>
					</a>
				</div>
			</div>
		</div>
		
		<!--5 Cover Flow Flipster-->
		<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
			<div class="sf-inner-box shadow-sm m-2 text-center border-end border-top border-bottom box">
				<div class="ribbon"><span><?php esc_html_e( 'LAYOUT', 'slider-factory' ); ?> 5</span></div>
				<img class="img-fluid" src="<?php echo esc_url(plugin_dir_url( __FILE__ ).'assets/banners/layout-5.jpg'); ?>">
				<div class="p-3">
					<button type="button" class="slider-info btn btn-danger bg-gradient" style="background-color: #e76f51; border-color: #e76f51;" data-bs-toggle="tooltip" data-bs-html="true" title="<b>Layout 5 Features</b><br>&bull;Mouse Wheel Slide Show<br>&bull;AutoPay<br>&bull;Navigation Control<br>&bull;Slide Title - NO<br>&bull;Slide Description - NO <br>&bull;Custom Width<br>&bull;Custom Height<br>&bull;Slide Sorting">
					<i class="fas fa-info"></i> <?php esc_html_e( 'Info', 'slider-factory' ); ?>
					</button>
					<a href="https://wpfrank.com/demo/slider-factory-free-wordpress-plugin/layout-5/" target="_blank" id="slider-demo" class="slider-demo btn btn-danger bg-gradient" style="background-color: #e76f51; border-color: #e76f51;">
						<i class="fas fa-desktop"></i> <?php esc_html_e( 'Demo', 'slider-factory' ); ?>
					</a>
					<a class="btn btn-danger bg-gradient" style="background-color: #e76f51; border-color: #e76f51;" href="admin.php?page=sf-manage-slider&sf-slider-action=create&sf-slider-layout=5&sf-create-nonce=<?php echo esc_attr( $sf_create_nonce ); ?>">
						<i class="fas fa-plus"></i> <?php esc_html_e( 'Create', 'slider-factory' ); ?>
					</a>
				</div>
			</div>
		</div>
		
		<!--6 Carousel Wipe-->
		<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
			<div class="sf-inner-box shadow-sm m-2 text-center border-end border-top border-bottom box">
				<div class="ribbon"><span><?php esc_html_e( 'LAYOUT', 'slider-factory' ); ?> 6</span></div>
				<img class="img-fluid" src="<?php echo esc_url(plugin_dir_url( __FILE__ ).'assets/banners/layout-6.jpg'); ?>">
				<div class="p-3">
					<button type="button" class="slider-info btn btn-danger bg-gradient" style="background-color: #e76f51; border-color: #e76f51;" data-bs-toggle="tooltip" data-bs-html="true" title="<b>Layout 6 Features</b><br>&bull;AutoPay<br>&bull;Navigation Control<br>&bull;Pagination Dots<br>&bull;Slide Title<br>&bull;Slide Description<br>&bull;Custom Width<br>&bull;Custom Height<br>&bull;Slide Sorting">
					<i class="fas fa-info"></i> <?php esc_html_e( 'Info', 'slider-factory' ); ?>
					</button>
					<a href="https://wpfrank.com/demo/slider-factory-free-wordpress-plugin/layout-6/" target="_blank" id="slider-demo" class="slider-demo btn btn-danger bg-gradient" style="background-color: #e76f51; border-color: #e76f51;">
						<i class="fas fa-desktop"></i> <?php esc_html_e( 'Demo', 'slider-factory' ); ?>
					</a>
					<a class="btn btn-danger bg-gradient" style="background-color: #e76f51; border-color: #e76f51;" href="admin.php?page=sf-manage-slider&sf-slider-action=create&sf-slider-layout=6&sf-create-nonce=<?php echo esc_attr( $sf_create_nonce ); ?>">
						<i class="fas fa-plus"></i> <?php esc_html_e( 'Create', 'slider-factory' ); ?>
					</a>
				</div>
			</div>
		</div>
		
		<!--7 Rotating Slider-->
		<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
			<div class="sf-inner-box shadow-sm m-2 text-center border-end border-bottom box">
				<div class="ribbon"><span><?php esc_html_e( 'LAYOUT', 'slider-factory' ); ?> 7</span></div>
				<img class="img-fluid" src="<?php echo esc_url(plugin_dir_url( __FILE__ ).'assets/banners/layout-7.jpg'); ?>">
				<div class="p-3">
					<button type="button" id="slider-info" class="slider-info btn btn-danger bg-gradient" style="background-color: #e76f51; border-color: #e76f51;" data-bs-toggle="tooltip" data-bs-html="true" title="<b>Layout 7 Features</b><br>&bull;Rotating style Slider<br>&bull;Custom Height and Width<br>&bull;Auto Play ON/OFF<br>&bull;Slider Navigation Arrows<br>&bull;Slide Title<br>&bull;Slide Description<br>&bull;Sorting Order By Id<br>&bull;Responsive Slider">
					<i class="fas fa-info"></i> <?php esc_html_e( 'Info', 'slider-factory' ); ?>
					</button>
					<a type="button" href="https://wpfrank.com/demo/slider-factory-free-wordpress-plugin/layout-7/" target="_blank" id="slider-demo" class="slider-demo btn btn-danger bg-gradient" style="background-color: #e76f51; border-color: #e76f51;">
						<i class="fas fa-desktop"></i> <?php esc_html_e( 'Demo', 'slider-factory' ); ?>
					</a>
					<a class="btn btn-danger bg-gradient" style="background-color: #e76f51; border-color: #e76f51;" href="admin.php?page=sf-manage-slider&sf-slider-action=create&sf-slider-layout=7&sf-create-nonce=<?php echo esc_attr( $sf_create_nonce ); ?>">
						<i class="fas fa-plus"></i> <?php esc_html_e( 'Create', 'slider-factory' ); ?>
					</a>
				</div>
			</div>
		</div>
		
		<!--8 Infinite Scroll Slider-->
		<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
			<div class="sf-inner-box shadow-sm m-2 text-center border-end border-bottom box">
				<div class="ribbon"><span><?php esc_html_e( 'LAYOUT', 'slider-factory' ); ?> 8</span></div>
				<img class="img-fluid" src="<?php echo esc_url(plugin_dir_url( __FILE__ ).'assets/banners/layout-8.jpg'); ?>">
				<div class="p-3">
					<button type="button" id="slider-info" class="slider-info btn btn-danger bg-gradient" style="background-color: #e76f51; border-color: #e76f51;" data-bs-toggle="tooltip" data-bs-html="true" title="<b>Layout 8 Features</b><br>&bull;Infinite Scroll style Slider<br>&bull;AutoPlay<br>&bull;Slide Title<br>&bull;Slide Description<br>&bull;Custom Width<br>&bull;Custom Height<br>&bull;Slide Sorting">
					<i class="fas fa-info"></i> <?php esc_html_e( 'Info', 'slider-factory' ); ?>
					</button>
					<a type="button" href="https://wpfrank.com/demo/slider-factory-free-wordpress-plugin/layout-8/" target="_blank" id="slider-demo" class="slider-demo btn btn-danger bg-gradient" style="background-color: #e76f51; border-color: #e76f51;">
						<i class="fas fa-desktop"></i> <?php esc_html_e( 'Demo', 'slider-factory' ); ?>
					</a>
					<a class="btn btn-danger bg-gradient" style="background-color: #e76f51; border-color: #e76f51;" href="admin.php?page=sf-manage-slider&sf-slider-action=create&sf-slider-layout=8&sf-create-nonce=<?php echo esc_attr( $sf_create_nonce ); ?>">
						<i class="fas fa-plus"></i> <?php esc_html_e( 'Create', 'slider-factory' ); ?>
					</a>
				</div>
			</div>
		</div>
		
		
		<!--9 Photo View Slider 9-->
		<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
			<div class="sf-inner-box shadow-sm m-2 text-center border-end border-bottom box">
				<div class="ribbon"><span><?php esc_html_e( 'LAYOUT', 'slider-factory' ); ?> 9</span></div>
				<img class="img-fluid" src="<?php echo esc_url(plugin_dir_url( __FILE__ ).'assets/banners/layout-9.jpg'); ?>">
				<div class="p-3">
					<button type="button" id="slider-info" class="slider-info btn btn-danger bg-gradient" style="background-color: #e76f51; border-color: #e76f51;" data-bs-toggle="tooltip" data-bs-html="true" title="<b>Layout 9 Features</b><br>&bull;Photo Style Slider<br>&bull;AutoPay<br>&bull;Slide Title<br>&bull;Custom Width<br>&bull;Custom Height<br>&bull;Slide Sorting">
					<i class="fas fa-info"></i> <?php esc_html_e( 'Info', 'slider-factory' ); ?>
					</button>
					<a type="button" href="https://wpfrank.com/demo/slider-factory-free-wordpress-plugin/layout-9/" target="_blank" id="slider-demo" class="slider-demo btn btn-danger bg-gradient" style="background-color: #e76f51; border-color: #e76f51;">
						<i class="fas fa-desktop"></i> <?php esc_html_e( 'Demo', 'slider-factory' ); ?>
					</a>
					<a class="btn btn-danger bg-gradient" style="background-color: #e76f51; border-color: #e76f51;" href="admin.php?page=sf-manage-slider&sf-slider-action=create&sf-slider-layout=9&sf-create-nonce=<?php echo esc_attr( $sf_create_nonce ); ?>">
						<i class="fas fa-plus"></i> <?php esc_html_e( 'Create', 'slider-factory' ); ?>
					</a>
				</div>
			</div>
		</div>
		
		<!--Snap Slider 10-->
		<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
			<div class="sf-inner-box shadow-sm m-2 text-center border-end border-bottom box">
				<div class="ribbon"><span><?php esc_html_e( 'LAYOUT', 'slider-factory' ); ?> 10</span></div>
				<img class="img-fluid" src="<?php echo esc_url(plugin_dir_url( __FILE__ ).'assets/banners/layout-10.jpg'); ?>">
				<div class="p-3">
					<button type="button" id="slider-info" class="slider-info btn btn-danger bg-gradient" style="background-color: #e76f51; border-color: #e76f51;" data-bs-toggle="tooltip" data-bs-html="true" title="<b>Layout 10 Features</b><br>&bull;Full Page Style Slider<br>&bull;Navigation Dots<br>&bull;Slide Title<br>&bull;Slide Description<br>&bull;Custom Width<br>&bull;Custom Height<br>&bull;Slide Sorting<br>&bull;Responsive">
					<i class="fas fa-info"></i> <?php esc_html_e( 'Info', 'slider-factory' ); ?>
					</button>
					<a type="button" href="https://wpfrank.com/demo/slider-factory-free-wordpress-plugin/layout-10/" target="_blank" id="slider-demo" class="slider-demo btn btn-danger bg-gradient" style="background-color: #e76f51; border-color: #e76f51;">
						<i class="fas fa-desktop"></i> <?php esc_html_e( 'Demo', 'slider-factory' ); ?>
					</a>
					<a class="btn btn-danger bg-gradient" style="background-color: #e76f51; border-color: #e76f51;" href="admin.php?page=sf-manage-slider&sf-slider-action=create&sf-slider-layout=10&sf-create-nonce=<?php echo esc_attr( $sf_create_nonce ); ?>">
						<i class="fas fa-plus"></i> <?php esc_html_e( 'Create', 'slider-factory' ); ?>
					</a>
				</div>
			</div>
		</div>
		
		<!--Product Slider 11-->
		<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
			<div class="sf-inner-box shadow-sm m-2 text-center border-end border-bottom box">
				<div class="ribbon"><span><?php esc_html_e( 'LAYOUT', 'slider-factory' ); ?> 11</span></div>
				<img class="img-fluid" src="<?php echo esc_url(plugin_dir_url( __FILE__ ).'assets/banners/layout-11.jpg'); ?>">
				<div class="p-3">
					<button type="button" id="slider-info" class="slider-info btn btn-danger bg-gradient" style="background-color: #e76f51; border-color: #e76f51;" data-bs-toggle="tooltip" data-bs-html="true" title="<b>Layout 11 Features</b><br>&bull;Product Slider<br>&bull;Product Title/Description<br>&bull;Custom Height/Width<br>&bull;Slide Sorting">
					<i class="fas fa-info"></i> <?php esc_html_e( 'Info', 'slider-factory' ); ?>
					</button>
					<a type="button" href="https://wpfrank.com/demo/slider-factory-free-wordpress-plugin/layout-11/" target="_blank" id="slider-demo" class="slider-demo btn btn-danger bg-gradient" style="background-color: #e76f51; border-color: #e76f51;">
						<i class="fas fa-desktop"></i> <?php esc_html_e( 'Demo', 'slider-factory' ); ?>
					</a>
					<a class="btn btn-danger bg-gradient" style="background-color: #e76f51; border-color: #e76f51;" href="admin.php?page=sf-manage-slider&sf-slider-action=create&sf-slider-layout=11&sf-create-nonce=<?php echo esc_attr( $sf_create_nonce ); ?>">
						<i class="fas fa-plus"></i> <?php esc_html_e( 'Create', 'slider-factory' ); ?>
					</a>
				</div>
			</div>
		</div>
		
		<!--Before-After Slider 12-->
		<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
			<div class="sf-inner-box shadow-sm m-2 text-center border-end border-bottom box">
				<div class="ribbon"><span><?php esc_html_e( 'LAYOUT', 'slider-factory' ); ?> 12</span></div>
				<img class="img-fluid" src="<?php echo esc_url(plugin_dir_url( __FILE__ ).'assets/banners/layout-12.jpg'); ?>">
				<div class="p-3">
					<button type="button" id="slider-info" class="slider-info btn btn-danger bg-gradient" style="background-color: #e76f51; border-color: #e76f51;" data-bs-toggle="tooltip" data-bs-html="true" title="<b>Layout 12 Features</b><br>&bull;Before-After Slider<br>&bull;Custom Width/Height">
					<i class="fas fa-info"></i> <?php esc_html_e( 'Info', 'slider-factory' ); ?>
					</button>
					<a type="button" href="https://wpfrank.com/demo/slider-factory-free-wordpress-plugin/layout-12/" target="_blank" id="slider-demo" class="slider-demo btn btn-danger bg-gradient" style="background-color: #e76f51; border-color: #e76f51;">
						<i class="fas fa-desktop"></i> <?php esc_html_e( 'Demo', 'slider-factory' ); ?>
					</a>
					<a class="btn btn-danger bg-gradient" style="background-color: #e76f51; border-color: #e76f51;" href="admin.php?page=sf-manage-slider&sf-slider-action=create&sf-slider-layout=12&sf-create-nonce=<?php echo esc_attr( $sf_create_nonce ); ?>">
						<i class="fas fa-plus"></i> <?php esc_html_e( 'Create', 'slider-factory' ); ?>
					</a>
				</div>
			</div>
		</div>
		
		<!-- Slider-Pro 13-->
		<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
			<div class="sf-inner-box shadow-sm m-2 text-center border-end border-bottom box">
				<div class="ribbon"><span><?php esc_html_e( 'LAYOUT', 'slider-factory' ); ?> 13</span></div>
				<img class="img-fluid" src="<?php echo esc_url(plugin_dir_url( __FILE__ ).'assets/banners/layout-13.jpg'); ?>">
				<div class="p-3">
					<button type="button" id="slider-info" class="slider-info btn btn-danger bg-gradient" style="background-color: #e76f51; border-color: #e76f51;" data-bs-toggle="tooltip" data-bs-html="true" title="<b>Layout 13 Features</b><br>&bull;Supports Videos/Images<br>&bull;Youtube<br>&bull;Vimeo<br>&bull;Local Uploaded Videos<br>&bull;FancyBox/LightBox<br>&bull;Vertical/Horizontal Orientation<br>&bull;Animated Text<br>&bull;Title and Description<br>&bull;Link Buttons<br>&bull;Full Screen with Thumbnails<br>&bull;Custom Width/Height<br>&bull;Touch Compitable<br>&bull;Custom CSS<br>">
					<i class="fas fa-info"></i> <?php esc_html_e( 'Info', 'slider-factory' ); ?>
					</button>
					<a type="button" href="https://wpfrank.com/demo/slider-factory-pro/layout-13/" target="_blank" id="slider-demo" class="slider-demo btn btn-danger bg-gradient" style="background-color: #e76f51; border-color: #e76f51;">
						<i class="fas fa-desktop"></i> <?php esc_html_e( 'Demo', 'slider-factory' ); ?>
					</a>
					<a class="btn btn-danger bg-gradient" style="background-color: #e76f51; border-color: #e76f51;" target="_blank" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/">
						<i class="fas fa-cart-plus"></i> <?php esc_html_e( 'Buy Pro', 'slider-factory' ); ?>
					</a>
				</div>
			</div>
		</div>
		
		<!--Slidewiz Slider 14 -->
		<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
			<div class="sf-inner-box shadow-sm m-2 text-center border-end border-bottom box">
				<div class="ribbon"><span><?php esc_html_e( 'LAYOUT', 'slider-factory' ); ?> 14</span></div>
				<img class="img-fluid" src="<?php echo esc_url(plugin_dir_url( __FILE__ ).'assets/banners/layout-14.jpg'); ?>">
				<div class="p-3">
					<button type="button" id="slider-info" class="slider-info btn btn-danger bg-gradient" style="background-color: #e76f51; border-color: #e76f51;" data-bs-toggle="tooltip" data-bs-html="true" title="<b>Layout 14 Features</b><br>&bull;Custom Height/Width<br>&bull;Autoplay<br>&bull;Slide Title/Description<br>&bull;Link Buttons<br>&bull;Amazing animations<br>&bull;Custom CSS<br>&bull;Slide Sorting">
					<i class="fas fa-info"></i> <?php esc_html_e( 'Info', 'slider-factory' ); ?>
					</button>
					<a type="button" href="https://wpfrank.com/demo/slider-factory-pro/layout-14/" target="_blank" id="slider-demo" class="slider-demo btn btn-danger bg-gradient" style="background-color: #e76f51; border-color: #e76f51;">
						<i class="fas fa-desktop"></i> <?php esc_html_e( 'Demo', 'slider-factory' ); ?>
					</a>
					<a class="btn btn-danger bg-gradient" style="background-color: #e76f51; border-color: #e76f51;" target="_blank" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/">
						<i class="fas fa-cart-plus"></i> <?php esc_html_e( 'Buy Pro', 'slider-factory' ); ?>
					</a>
				</div>
			</div>
		</div>
		
		<!--Desoslide Slider 15 -->
		<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
			<div class="sf-inner-box shadow-sm m-2 text-center border-end border-bottom box">
				<div class="ribbon"><span><?php esc_html_e( 'LAYOUT', 'slider-factory' ); ?> 15</span></div>
				<img class="img-fluid" src="<?php echo esc_url(plugin_dir_url( __FILE__ ).'assets/banners/layout-15.jpg'); ?>">
				<div class="p-3">
					<button type="button" id="slider-info" class="slider-info btn btn-danger bg-gradient" style="background-color: #e76f51; border-color: #e76f51;" data-bs-toggle="tooltip" data-bs-html="true" title="<b>Layout 15 Features</b><br>&bull;Vertical Thumbs<br>&bull;AutoPlay<br>&bull;Thumbnail controlled on click/hover<br>&bull;Slide Title/Description<br>&bull;Amazing animations<br>&bull;Slide Controls<br>&bull;Keyboard Control<br>&bull;Responsive">
					<i class="fas fa-info"></i> <?php esc_html_e( 'Info', 'slider-factory' ); ?>
					</button>
					<a type="button" href="https://wpfrank.com/demo/slider-factory-pro/layout-15/" target="_blank" id="slider-demo" class="slider-demo btn btn-danger bg-gradient" style="background-color: #e76f51; border-color: #e76f51;">
						<i class="fas fa-desktop"></i> <?php esc_html_e( 'Demo', 'slider-factory' ); ?>
					</a>
					<a class="btn btn-danger bg-gradient" style="background-color: #e76f51; border-color: #e76f51;" target="_blank" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/">
						<i class="fas fa-cart-plus"></i> <?php esc_html_e( 'Buy Pro', 'slider-factory' ); ?>
					</a>
				</div>
			</div>
		</div>
		
		<!-- full page 16-->
		<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
			<div class="sf-inner-box shadow-sm m-2 text-center border-end border-bottom box">
				<div class="ribbon"><span><?php esc_html_e( 'LAYOUT', 'slider-factory' ); ?> 16</span></div>
				<img class="img-fluid" src="<?php echo esc_url(plugin_dir_url( __FILE__ ).'assets/banners/layout-16.jpg'); ?>">
				<div class="p-3">
					<button type="button" id="slider-info" class="slider-info btn btn-danger bg-gradient" style="background-color: #e76f51; border-color: #e76f51;" data-bs-toggle="tooltip" data-bs-html="true" title="<b>Layout 16 Features</b><br>&bull;Full Page Slider<br>&bull;Autoplay<br>&bull;Navigation Buttons<br>&bull;Slide Title/Description<br>&bull;Link Buttons<br>&bull;Custom Width<br>&bull;Custom Height<br>&bull;Slide Sorting">
					<i class="fas fa-info"></i> <?php esc_html_e( 'Info', 'slider-factory' ); ?>
					</button>
					<a type="button" href="https://wpfrank.com/demo/slider-factory-pro/layout-16/" target="_blank" id="slider-demo" class="slider-demo btn btn-danger bg-gradient" style="background-color: #e76f51; border-color: #e76f51;">
						<i class="fas fa-desktop"></i> <?php esc_html_e( 'Demo', 'slider-factory' ); ?>
					</a>
					<a class="btn btn-danger bg-gradient" style="background-color: #e76f51; border-color: #e76f51;" target="_blank" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/">
						<i class="fas fa-cart-plus"></i> <?php esc_html_e( 'Buy Pro', 'slider-factory' ); ?>
					</a>
				</div>
			</div>
		</div>
		
		<!--Adaptor Cool 3d 17-->
		<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
			<div class="sf-inner-box shadow-sm m-2 text-center border-end border-bottom box">
				<div class="ribbon"><span><?php esc_html_e( 'LAYOUT', 'slider-factory' ); ?> 17</span></div>
				<img class="img-fluid" src="<?php echo esc_url(plugin_dir_url( __FILE__ ).'assets/banners/layout-17.jpg'); ?>">
				<div class="p-3">
					<button type="button" id="slider-info" class="slider-info btn btn-danger bg-gradient" style="background-color: #e76f51; border-color: #e76f51;" data-bs-toggle="tooltip" data-bs-html="true" title="<b>Layout 17 Features</b><br>&bull;3D Cube Style Slider<br>&bull;Auto Play<br>&bull;Horizontal and Vertical Orientation<br>&bull;Slide Title/Description<br>&bull;Link Buttons<br>&bull;Custom Width<br>&bull;Custom Height<br>&bull;Slide Sorting">
					<i class="fas fa-info"></i> <?php esc_html_e( 'Info', 'slider-factory' ); ?>
					</button>
					<a type="button" href="https://wpfrank.com/demo/slider-factory-pro/layout-17/" target="_blank" id="slider-demo" class="slider-demo btn btn-danger bg-gradient" style="background-color: #e76f51; border-color: #e76f51;">
						<i class="fas fa-desktop"></i> <?php esc_html_e( 'Demo', 'slider-factory' ); ?>
					</a>
					<a class="btn btn-danger bg-gradient" style="background-color: #e76f51; border-color: #e76f51;" target="_blank" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/">
						<i class="fas fa-cart-plus"></i> <?php esc_html_e( 'Buy Pro', 'slider-factory' ); ?>
					</a>
				</div>
			</div>
		</div>
		
		<!--Horizontal Mouse Hover Scroll Slider 18-->
		<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
			<div class="sf-inner-box shadow-sm m-2 text-center border-end border-bottom box">
				<div class="ribbon"><span><?php esc_html_e( 'LAYOUT', 'slider-factory' ); ?> 18</span></div>
				<img class="img-fluid" src="<?php echo esc_url(plugin_dir_url( __FILE__ ).'assets/banners/layout-18.jpg'); ?>">
				<div class="p-3">
					<button type="button" id="slider-info" class="slider-info btn btn-danger bg-gradient" style="background-color: #e76f51; border-color: #e76f51;" data-bs-toggle="tooltip" data-bs-html="true" title="<b>Layout 18 Features</b><br>&bull;Horizontal Mouse Hover Slider<br>&bull;Full Screen<br>&bull;Slide Title/Description<br>&bull;Link Buttons<br>&bull;Slide Sorting">
					<i class="fas fa-info"></i> <?php esc_html_e( 'Info', 'slider-factory' ); ?>
					</button>
					<a type="button" href="https://wpfrank.com/demo/slider-factory-pro/layout-18/" target="_blank" id="slider-demo" class="slider-demo btn btn-danger bg-gradient" style="background-color: #e76f51; border-color: #e76f51;">
						<i class="fas fa-desktop"></i> <?php esc_html_e( 'Demo', 'slider-factory' ); ?>
					</a>
					<a class="btn btn-danger bg-gradient" style="background-color: #e76f51; border-color: #e76f51;" target="_blank" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/">
						<i class="fas fa-cart-plus"></i> <?php esc_html_e( 'Buy Pro', 'slider-factory' ); ?>
					</a>
				</div>
			</div>
		</div>
		
		<!--BQ Accordian slider 19 -->
		<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
			<div class="sf-inner-box shadow-sm m-2 text-center border-end border-bottom box">
				<div class="ribbon"><span><?php esc_html_e( 'LAYOUT', 'slider-factory' ); ?> 19</span></div>
				<img class="img-fluid" src="<?php echo esc_url(plugin_dir_url( __FILE__ ).'assets/banners/layout-19.jpg'); ?>">
				<div class="p-3">
					<button type="button" id="slider-info" class="slider-info btn btn-danger bg-gradient" style="background-color: #e76f51; border-color: #e76f51;" data-bs-toggle="tooltip" data-bs-html="true" title="<b>Layout 19 Features</b><br>&bull;Accordian Style Slider<br>&bull;Supports Videos/Images<br>&bull;Youtube<br>&bull;Vimeo<br>&bull;Local Uploaded Videos<br>&bull;FancyBox/LightBox<br>&bull;Vertical/Horizontal Orientation<br>&bull;Animated Text<br>&bull;Title and Description<br>&bull;Link Buttons<br>&bull;Custom Width/Height<br>&bull;Touch Compitable<br>&bull;Custom CSS<br>">
					<i class="fas fa-info"></i> <?php esc_html_e( 'Info', 'slider-factory' ); ?>
					</button>
					<a type="button" href="https://wpfrank.com/demo/slider-factory-pro/layout-19/" target="_blank" id="slider-demo" class="slider-demo btn btn-danger bg-gradient" style="background-color: #e76f51; border-color: #e76f51;">
						<i class="fas fa-desktop"></i> <?php esc_html_e( 'Demo', 'slider-factory' ); ?>
					</a>
					<a class="btn btn-danger bg-gradient" style="background-color: #e76f51; border-color: #e76f51;" target="_blank" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/">
						<i class="fas fa-cart-plus"></i> <?php esc_html_e( 'Buy Pro', 'slider-factory' ); ?>
					</a>
				</div>
			</div>
		</div>
		
		<!--BQ Grid Accordian slider 20 -->
		<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
			<div class="sf-inner-box shadow-sm m-2 text-center border-end border-bottom box">
				<div class="ribbon"><span><?php esc_html_e( 'LAYOUT', 'slider-factory' ); ?> 20</span></div>
				<img class="img-fluid" src="<?php echo esc_url(plugin_dir_url( __FILE__ ).'assets/banners/layout-20.jpg'); ?>">
				<div class="p-3">
					<button type="button" id="slider-info" class="slider-info btn btn-danger bg-gradient" style="background-color: #e76f51; border-color: #e76f51;" data-bs-toggle="tooltip" data-bs-html="true" title="<b>Layout 20 Features</b><br>&bull;Grid Accordian Slider<br>&bull;Supports Videos/Images<br>&bull;Youtube<br>&bull;Vimeo<br>&bull;Local Uploaded Videos<br>&bull;FancyBox/LightBox<br>&bull;Vertical/Horizontal Orientation<br>&bull;Animated Text<br>&bull;Title and Description<br>&bull;Link Buttons<br>&bull;Custom Width/Height<br>&bull;Touch Compitable<br>&bull;Custom CSS<br>">
					<i class="fas fa-info"></i> <?php esc_html_e( 'Info', 'slider-factory' ); ?>
					</button>
					<a type="button" href="https://wpfrank.com/demo/slider-factory-pro/layout-20/" target="_blank" id="slider-demo" class="slider-demo btn btn-danger bg-gradient" style="background-color: #e76f51; border-color: #e76f51;">
						<i class="fas fa-desktop"></i> <?php esc_html_e( 'Demo', 'slider-factory' ); ?>
					</a>
					<a class="btn btn-danger bg-gradient" style="background-color: #e76f51; border-color: #e76f51;" target="_blank" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/">
						<i class="fas fa-cart-plus"></i> <?php esc_html_e( 'Buy Pro', 'slider-factory' ); ?>
					</a>
				</div>
			</div>
		</div>
		
	</div>

	<!-- UPGRADE TO PRO-->
	<div class="row my-3">
		<!--Upgrade To Pro-->
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<div class="sf-inner-box shadow-sm m-2 text-center border-end border-top border-bottom box">
				<div class="ribbon"><span><?php esc_html_e( 'GET PRO', 'slider-factory' ); ?></span></div>
				<div class="p-3">
					<img class="img-fluid" style="height: 391px;" src="<?php echo esc_url(plugin_dir_url( __FILE__ ).'assets/images/slider-factory-pro-banner.png'); ?>">
				</div>
			</div>
		</div>
		
		<div class="col-lg-6 col-md-6 col-sm-6">
			<div class="sf-inner-box shadow-sm m-2 text-center border-end border-top border-bottom box">
				<div class="ribbon"><span><?php esc_html_e( 'FEATURES', 'slider-factory' ); ?></span></div>
				<div class="p-3">
					<h3 class="py-3 text-secondary"><?php esc_html_e( 'Upgrade To Pro Version', 'slider-factory' ); ?></h3>
					<ul class="list-group">
						<li class="list-group-item">20 Slider Layouts & Presets</li>
						<li class="list-group-item">Heavily Customizable Settings</li>
						<li class="list-group-item">Apply Own Design Using Custom CSS</li>
						<li class="list-group-item">Responsive Bootstrap 5 Slider Dashboard</li>
						<li class="list-group-item">Priority Customer Support</li>
					</ul>
				</div>
				<div class="p-3">
					<a href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank" class="slider-info btn btn-lg btn-danger bg-gradient" style="background-color: #e76f51; border-color: #e76f51;">
						<i class="fas fa-info"></i> <?php esc_html_e( 'Slider Factory Pro', 'slider-factory' ); ?>
					</a>
					<a href="https://wpfrank.com/demo/slider-factory-pro/" target="_blank" id="slider-demo" class="slider-demo btn btn-lg btn-danger bg-gradient" style="background-color: #e76f51; border-color: #e76f51;">
						<i class="fas fa-desktop"></i> <?php esc_html_e( 'Check Pro Demo', 'slider-factory' ); ?>
					</a>
				</div>
			</div>
		</div>
			
		<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
			<div class="sf-inner-box shadow-sm m-2 text-center border-end border-top border-bottom box">
				<div class="ribbon"><span><?php esc_html_e( 'Video Docs', 'slider-factory' ); ?></span></div>
				<div class="p-3">
					<div>
						<h2 class="m-3"><?php esc_html_e( 'Watch Video Documentation', 'slider-factory' ); ?></h2>
						<iframe width="100%" height="550" src="https://www.youtube.com/embed/UC0Ru2L4mFk" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
					</div>
					<div class="p-3">
						<a href="https://wpfrank.com/slider-factory-free-wordpress-plugin-documentation/" target="_blank" class="btn btn-lg btn-danger bg-gradient" style="background-color: #e76f51; border-color: #e76f51;">
							<?php esc_html_e( 'Read Text Documentation', 'slider-factory' ); ?>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row bg-dark bg-gradient" style="--bs-gutter-x: 0rem;">
		<div class="col-12">
			<div class="text-center p-1">
				<p class="sf-heading p-0 mx-3 d-inline"><?php esc_html_e( 'Slider Factory Version ', 'slider-factory' ); ?><?php echo esc_html(get_option( 'wpfrank_sf_current_version' )); ?></p>
			</div>
		</div>
	</div>
</div>

<?php
global $wpdb;
$sf_options_table_name = "{$wpdb->prefix}options";
$slider_key            = 'sf_slider_';
$all_sliders           = $wpdb->get_results(
	$wpdb->prepare( "SELECT option_name FROM $wpdb->options WHERE `option_name` LIKE %s ORDER BY option_id ASC", '%' . $slider_key . '%' )
);
?>
<!--slider list modal start-->
<div class="modal fade" id="sf-sliders" tabindex="-1" aria-labelledby="sf-sliders-label" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="sf-sliders-label"><?php esc_html_e( 'Slider Shortcodes', 'slider-factory' ); ?></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<table class="table">
					<thead class="table-dark">
						<tr>
							<th scope="col"><?php esc_html_e( 'Title', 'slider-factory' ); ?></th>
							<th scope="col"><?php esc_html_e( 'Shortcode', 'slider-factory' ); ?></th>
							<th scope="col"><?php esc_html_e( 'Action', 'slider-factory' ); ?></th>
							<th scope="col" class="text-center"><input type="checkbox" id="sf-select-all" title="Select All Sliders"></th>
						</tr>
					</thead>
					<tbody id="sf-tbody">
						<?php
						if ( $wpdb->num_rows ) {
							$sf_counter    = 1;
							$sf_edit_nonce = wp_create_nonce( 'sf-edit-nonce' );
							foreach ( $all_sliders as $slider ) {
								$slider_key        = $slider->option_name;
								$sf_underscore_pos = strrpos( $slider_key, '_' );
								$sf_slider_id      = substr( $slider_key, ( $sf_underscore_pos + 1 ) );

								// load slider data
								$slider = get_option( 'sf_slider_' . $sf_slider_id );
								// print_r($slider);
								if ( isset( $slider['sf_slider_id'] ) ) {
									$sf_slider_id = $slider['sf_slider_id'];
								} else {
									$sf_slider_id = '';
								}
								if ( isset( $slider['sf_slider_title'] ) ) {
									$sf_slider_title = $slider['sf_slider_title'];
								} else {
									$sf_slider_title = '';
								}
								if ( isset( $slider['sf_slider_layout'] ) ) {
									$sf_slider_layout = $slider['sf_slider_layout'];
								} else {
									$sf_slider_layout = '';
								}
								$sf_slider_shortcode = '[sf id=' . $sf_slider_id . ' layout=' . $sf_slider_layout . ']';
								if ( $sf_slider_id && $sf_slider_layout ) {
									?>
						<tr id="<?php echo esc_attr( $sf_slider_id ); ?>">
							<td><?php echo esc_html( $sf_slider_title ); ?></td>
							<td>
								<input type="text" id="sf-slider-shortcode-<?php echo esc_attr( $sf_slider_id ); ?>" class="btn btn-info btn-sm" value="<?php echo esc_attr( $sf_slider_shortcode ); ?>">
								<button type="button" id="sf-copy-shortcode-<?php echo esc_attr( $sf_slider_id ); ?>" class="btn btn-info btn-sm" title="<?php esc_html_e( 'Click To Copy Slider Shortcode', 'slider-factory' ); ?>" onclick="return WpfrankSFCopyShortcode('<?php echo esc_attr( $sf_slider_id ); ?>');"><?php esc_html_e( 'Copy', 'slider-factory' ); ?></button>
								<button class="btn btn-sm btn-success d-none sf-copied-<?php echo esc_attr( $sf_slider_id ); ?>"><?php esc_html_e( 'Copied', 'slider-factory' ); ?></button>
							</td>
							<td>
								<button type="button" id="sf-clone-slider" class="btn btn-warning btn-sm" title="<?php esc_html_e( 'Clone Slider', 'slider-factory' ); ?>" value="<?php esc_attr( $sf_slider_id ); ?>" onclick="return WpfrankSFCloneSlider('<?php echo esc_attr( $sf_slider_id ); ?>', '<?php echo esc_attr( $sf_counter ); ?>');"><i class="fas fa-copy"></i></button>
								<a href="admin.php?page=sf-manage-slider&sf-slider-action=edit&sf-slider-id=<?php echo esc_attr( $sf_slider_id ); ?>&sf-slider-layout=<?php echo esc_attr( $sf_slider_layout ); ?>&sf-edit-nonce=<?php echo esc_attr( $sf_edit_nonce ); ?>" id="sf-edit-slider" class="btn btn-warning btn-sm" title="<?php esc_html_e( 'Edit Slider', 'slider-factory' ); ?>"><i class="fas fa-edit"></i></a>
								<button id="sf-delete-slider" class="btn btn-warning btn-sm" title="<?php esc_html_e( 'Delete Slider', 'slider-factory' ); ?>" value="<?php echo esc_attr( $sf_slider_id ); ?>" onclick="return WpfrankSFremoveSlider('<?php echo esc_attr( $sf_slider_id ); ?>', 'single');"><i class="fas fa-trash-alt"></i></button>
							</td>
							<td class="text-center">
								<input type="checkbox" id="sf-slider-id" name="sf-slider-id" value="<?php echo esc_attr( $sf_slider_id ); ?>" title="<?php esc_html_e( 'Select Slider Shortcode', 'slider-factory' ); ?>">
							</td>
						</tr>
									<?php
									$sf_counter++;
								}
							} // end of for each
						} // end of count
						?>
					</tbody>
					<thead class="table-dark">
						<tr>
							<th scope="col"><?php esc_html_e( 'Title', 'slider-factory' ); ?></th>
							<th scope="col"><?php esc_html_e( 'Shortcode', 'slider-factory' ); ?></th>
							<th scope="col"><?php esc_html_e( 'Action', 'slider-factory' ); ?></th>
							<th scope="col" class="text-center"><button type="button" id="sf-delete-selected" class="btn btn-danger btn-sm" title="<?php esc_html_e( 'Delete Selected Sliders', 'slider-factory' ); ?>" onclick="return WpfrankSFremoveSlider('', 'multiple');"><i class="fas fa-trash-alt"></i></button></th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>
<!--slider list modal end-->

<script>
jQuery(document).ready(function(){
	//tooltip
	jQuery('.slider-info').tooltip({trigger: "click"});

	//modal
	var sf_sliders = new bootstrap.Modal(document.getElementById('sf-sliders'), { });
});


// copy shortcode to clipboard
function WpfrankSFCopyShortcode(id) {
	/* Get the text field */
	var copyShortcode = document.getElementById('sf-slider-shortcode-' + id);
	copyShortcode.select();
	document.execCommand('copy');

	//fade in and out copied message
	jQuery('.sf-copied-' + id).removeClass('d-none');
	jQuery('.sf-copied-' + id).fadeIn('2000', 'linear');
	jQuery('.sf-copied-' + id).fadeOut(3000,'swing');
}

// clone slide start
function WpfrankSFCloneSlider(sf_slider_id, sf_slider_counter){
	console.log(sf_slider_id + sf_slider_counter);
	jQuery.ajax({
		type: 'POST',
		url: ajaxurl,
		data: {
			'action': 'sf_clone_slider', //this is the name of the AJAX method called in WordPress
			'nonce': '<?php echo esc_js( wp_create_nonce( "sf-clone-slider" ) ); ?>',
			//slider info
			'sf_slider_id': sf_slider_id,
			'sf_slider_counter': sf_slider_counter,
		}, 
		success: function (result) {
			//alert(result);
			jQuery('tbody#sf-tbody').append(result);
		},
		error: function () {
		}
	});
}
// clone slide end


//select all sliders
jQuery('#sf-select-all').click(function () {
	jQuery('input:checkbox').not(this).prop('checked', this.checked);
});
// remove slider/sliders start
function WpfrankSFremoveSlider(sf_slider_id, do_action){
	console.log(sf_slider_id);
	if(do_action == 'multiple'){
		var sf_slider_id = [];
		jQuery('input:checkbox[name=sf-slider-id]:checked').each(function() { 
			sf_slider_id.push(jQuery(this).val());
			//hide selected table row on multiple slider delete
			jQuery('tr#' + jQuery(this).val()).fadeOut('1500');
			//delay after fadeOut table row
			jQuery(function() {
				setTimeout(function() {
					jQuery('tr#' + jQuery(this).val()).remove();
				}, 1000);
			});
		});
	}
	jQuery.ajax({
		type: 'POST',
		url: ajaxurl,
		data: {
			'action': 'sf_remove_slider', //this is the name of the AJAX method called in WordPress
			'do_action': do_action, //this is the name of the AJAX method called in WordPress
			'nonce': '<?php echo esc_js( wp_create_nonce( 'sf-remove-slider' ) ); ?>',
			//slider info
			'sf_slider_id': sf_slider_id,
		}, 
		success: function (result) {
			//hide table row on slide slider delete
			if(do_action == 'single'){
				jQuery('tr#' + sf_slider_id).fadeOut('1500');
				
				//delay after fadeOut table row
				jQuery(function() {
					setTimeout(function() {
						jQuery('tr#' + sf_slider_id).remove();
					}, 1000);
				});
			}
		},
		error: function () {
		}
	});
}
// remove slider/sliders end
</script>
