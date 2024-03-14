<?php
// toggle button CSS
wp_enqueue_style( 'awl-toogle-button-css', RSG_PLUGIN_URL . 'css/toogle-button.css' );

// css dropdown toggle
wp_enqueue_style( 'nig-admin-bootstrap-css', RSG_PLUGIN_URL . 'css/admin-bootstrap.css' );
wp_enqueue_style( 'awl-font-awesome-css', RSG_PLUGIN_URL . 'css/font-awesome.css' );
wp_enqueue_style( 'awl-styles-css', RSG_PLUGIN_URL . 'css/styles.css' );
wp_enqueue_style( 'awl-metabox-css', RSG_PLUGIN_URL . 'css/metabox.css' );


// js
wp_enqueue_script( 'jquery' );
wp_enqueue_script( 'awl-bootstrap-js', RSG_PLUGIN_URL . 'js/bootstrap.min.js', array( 'jquery' ), '', true );

?>
<style>
	.col-1, .col-2, .col-3, .col-4, .col-5, .col-6, .col-7, .col-8, .col-9, .col-10, .col-11, .col-12, .col, .col-auto, .col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12, .col-sm, .col-sm-auto, .col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12, .col-md, .col-md-auto, .col-lg-1, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9, .col-lg-10, .col-lg-11, .col-lg-12, .col-lg, .col-lg-auto, .col-xl-1, .col-xl-2, .col-xl-3, .col-xl-4, .col-xl-5, .col-xl-6, .col-xl-7, .col-xl-8, .col-xl-9, .col-xl-10, .col-xl-11, .col-xl-12, .col-xl, .col-xl-auto {
		float: left;
	}	
	.input_width {
		margin-left: 18px !important;
		border-width: 1px 1px 1px 6px !important;
		border-color: #3366ff !important;
		width: 30% !important; 
	}
	#comment-link-box, #edit-slug-box {
		display: none;
	}
</style>

<div class="row">
		<div class="col-lg-12 bhoechie-tab-container">
			<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 bhoechie-tab-menu">
				<div class="list-group">
					<a href="#" class="list-group-item active text-center">
						<span class="dashicons dashicons-editor-table"></span><br/><?php esc_html_e( 'Add Images', 'responsive-slider-gallery' ); ?>
					</a>
					<a href="#" class="list-group-item text-center">
						<span class="dashicons dashicons-admin-generic"></span><br/><?php esc_html_e( 'Configure', 'responsive-slider-gallery' ); ?>
					</a>
					<a href="#" class="list-group-item text-center">
						<span class="dashicons dashicons-admin-appearance"></span><br/><?php esc_html_e( 'Auto Play & Transition Effect', 'responsive-slider-gallery' ); ?>
					</a>
					<a href="#" class="list-group-item text-center">
						<span class="dashicons dashicons-admin-customizer"></span><br/><?php esc_html_e( 'Navigation Settings', 'responsive-slider-gallery' ); ?>
					</a>
					<a href="#" class="list-group-item text-center">
						<span class="dashicons dashicons-cart"></span><br/><?php esc_html_e( 'Upgrade To Pro', 'responsive-slider-gallery' ); ?>
					</a>
				</div>
			</div>
			<div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 bhoechie-tab">
				<div class="bhoechie-tab-content active">
					<h1><?php esc_html_e( 'Add Images', 'new-image-gallery' ); ?></h1>
					<hr>
					<div id="slider-gallery">
						<input type="button" id="remove-all-slides" name="remove-all-slides" class="button button-large" rel="" value="<?php esc_html_e( 'Delete All Slide', 'responsive-slider-gallery' ); ?>">
						<ul id="remove-slides" class="sbox">
						<?php
						$allslidesetting = unserialize( base64_decode( get_post_meta( $post->ID, 'awl_slider_settings_' . $post->ID, true ) ) );
						if ( isset( $allslidesetting['slide-ids'] ) ) {
							foreach ( $allslidesetting['slide-ids'] as $id ) {
								$thumbnail  = wp_get_attachment_image_src( $id, 'thumbnail', true );
								$attachment = get_post( $id );
								?>
							<li class="slide">
								<img class="new-slide" src="<?php echo esc_url( $thumbnail[0] ); ?>" alt="" style="height: 150px; width: 98%; border-radius: 8px;">
								<input type="hidden" id="slide-ids[]" name="slide-ids[]" value="<?php echo esc_attr( $id ); ?>" />
								<!-- Slide Title-->
								<input type="text" name="slide-title[]" id="slide-title[]" style="width: 98%;"  placeholder="<?php _e( 'Slide Title', 'responsive-slider-gallery' ); ?>" readonly value="<?php echo esc_attr( get_the_title( $id ) ); ?>">
								<a class="pw-trash-icon" name="remove-slide" id="remove-slide" href="#"><span class="dashicons dashicons-trash"></span></a>
							</li>
								<?php
							} // end of foreach
						} //end of if
						?>
						</ul>
					</div>
				</div>
				<div class="bhoechie-tab-content">
					<h1><?php esc_html_e( 'Configure settings', 'responsive-slider-gallery' ); ?></h1>
					<hr>
					
					<div class="col-md-4">
						<div class="ma_field_discription">
							<h6><?php esc_html_e( 'Slider Text', 'responsive-slider-gallery' ); ?></h6>
							<p><?php esc_html_e( 'Set slider text visibility on slider', 'responsive-slider-gallery' ); ?></p> 
						</div>
					</div>
					<div class="col-md-8">
						<div class="ma_field p-4 switch-field em_size_field">
							<?php
							if ( isset( $allslidesetting['slide-text'] ) ) {
								$slidetext = $allslidesetting['slide-text'];
							} else {
								$slidetext = 'false';
							}
							?>
							<input type="radio" name="slide-text" id="slide-text1" value="true" 
							<?php
							if ( $slidetext == 'true' ) {
								esc_html_e( 'checked=checked', 'responsive-slider-gallery' );}
							?>
							>
							<label for="slide-text1"><?php esc_html_e( 'Yes', 'responsive-slider-gallery' ); ?></label>
							<input type="radio" name="slide-text" id="slide-text2" value="false" 
							<?php
							if ( $slidetext == 'false' ) {
								esc_html_e( 'checked=checked', 'responsive-slider-gallery' );}
							?>
							>
							<label for="slide-text2"><?php esc_html_e( 'No', 'responsive-slider-gallery' ); ?></label>
						</div>
					</div>
					<div class="col-md-4">
						<div class="ma_field_discription">
							<h6><?php esc_html_e( 'Fit Slides', 'responsive-slider-gallery' ); ?></h6>
							<p><?php esc_html_e( 'Set how to fit slides into slider frame', 'responsive-slider-gallery' ); ?></p> 
						</div>
					</div>
					<div class="col-md-8">
						<div class="ma_field p-4 switch-field em_size_field">
							<?php
							if ( isset( $allslidesetting['fit-slides'] ) ) {
								$fitslides = $allslidesetting['fit-slides'];
							} else {
								$fitslides = 'cover';
							}
							?>
							<input type="radio" name="fit-slides" id="fit-slides2" value="cover" 
							<?php
							if ( $fitslides == 'cover' ) {
								esc_html_e( 'checked=checked', 'responsive-slider-gallery' );}
							?>
							>
							<label for="fit-slides2"><?php esc_html_e( 'Cover', 'responsive-slider-gallery' ); ?></label>
							<input type="radio" name="fit-slides" id="fit-slides4" value="none" 
							<?php
							if ( $fitslides == 'none' ) {
								esc_html_e( 'checked=checked', 'responsive-slider-gallery' );}
							?>
							>
							<label for="fit-slides4"><?php esc_html_e( 'None', 'responsive-slider-gallery' ); ?></label>
						</div>
					</div>
					
					<div class="col-md-4">
						<div class="ma_field_discription">
							<h6><?php esc_html_e( 'Full Screen Slider', 'responsive-slider-gallery' ); ?></h6>
							<p><?php esc_html_e( 'Set full screen view of slider like True / False', 'responsive-slider-gallery' ); ?></p> 
						</div>
					</div>
					<div class="col-md-8">
						<div class="ma_field p-4 switch-field em_size_field">
							<?php
								if ( isset( $allslidesetting['fullscreen'] ) ) {
									$fullscreen = $allslidesetting['fullscreen'];
								} else {
									$fullscreen = 'true';
								}
								?>
							<input type="radio" name="fullscreen" id="fullscreen1" value="true" 
							<?php
							if ( $fullscreen == 'true' ) {
								esc_html_e( 'checked=checked', 'responsive-slider-gallery' );}
							?>
							>
							<label for="fullscreen1"><?php esc_html_e( 'True', 'responsive-slider-gallery' ); ?></label>
							<input type="radio" name="fullscreen" id="fullscreen2" value="false" 
							<?php
							if ( $fullscreen == 'false' ) {
								esc_html_e( 'checked=checked', 'responsive-slider-gallery' );}
							?>
							>
							<label for="fullscreen2"><?php esc_html_e( 'False', 'responsive-slider-gallery' ); ?></label>
						</div>
					</div>
					
					<div class="col-md-4">
						<div class="ma_field_discription">
							<h6><?php esc_html_e( 'Width', 'responsive-slider-gallery' ); ?></h6>
							<p><?php esc_html_e( 'Set slider width in pixels and percents like 300px / 600px / 800px OR 25% / 50% / 100%', 'responsive-slider-gallery' ); ?></p> 
						</div>
					</div>
					<div class="col-md-8">
						<div class="ma_field p-4">
							<?php if ( isset( $allslidesetting['width'] ) ) {
								$width = $allslidesetting['width'];
							} else {
								$width = '100%';
							}
							?>
							<input class="input_width" type="text" name="width" id="width" value="<?php echo esc_attr( $width ); ?>">
						</div>
					</div>
					<div class="col-md-4">
						<div class="ma_field_discription">
							<h6><?php esc_html_e( 'Height', 'responsive-slider-gallery' ); ?></h6>
							<p><?php esc_html_e( 'Set slider height in pixels and percents like 300px / 600px / 800px OR 25% / 50% / 100%', 'responsive-slider-gallery' ); ?></p> 
						</div>
					</div>
					<div class="col-md-8">
						<div class="ma_field p-4">
							<?php
							if ( isset( $allslidesetting['height'] ) ) {
								$height = $allslidesetting['height'];
							} else {
								$height = '';
							}
							?>
							<input class="input_width" type="text" name="height" id="height" value="<?php echo esc_attr( $height ); ?>">
						</div>
					</div>
				</div>
				<div class="bhoechie-tab-content">
					<h1><?php esc_html_e( 'Auto Play & Transition Effect', 'responsive-slider-gallery' ); ?></h1>
					<hr>
					
					<div class="col-md-4">
						<div class="ma_field_discription">
							<h6><?php esc_html_e( 'Auto Play', 'responsive-slider-gallery' ); ?></h6>
							<p><?php esc_html_e( 'Set auto play to slides automatically', 'responsive-slider-gallery' ); ?></p> 
						</div>
					</div>
					<div class="col-md-8">
						<div class="ma_field p-4 switch-field em_size_field">
							<?php
							if ( isset( $allslidesetting['autoplay'] ) ) {
								$autoplay = $allslidesetting['autoplay'];
							} else {
								$autoplay = 'true';
							}
							?>
							<input type="radio" name="autoplay" id="autoplay1" value="true" 
							<?php
							if ( $autoplay == 'true' ) {
								esc_html_e( 'checked=checked', 'responsive-slider-gallery' );}
							?>
							>
							<label for="autoplay1"><?php esc_html_e( 'Yes', 'responsive-slider-gallery' ); ?></label>
							<input type="radio" name="autoplay" id="autoplay2" value="false" 
							<?php
							if ( $autoplay == 'false' ) {
								esc_html_e( 'checked=checked', 'responsive-slider-gallery' );}
							?>
							>
							<label for="autoplay2"><?php esc_html_e( 'No', 'responsive-slider-gallery' ); ?></label>
						</div>
					</div>
					<div class="col-md-4">
						<div class="ma_field_discription">
							<h6><?php esc_html_e( 'Loop', 'responsive-slider-gallery' ); ?></h6>
							<p><?php esc_html_e( 'Set loop to slides continuously', 'responsive-slider-gallery' ); ?></p> 
						</div>
					</div>
					<div class="col-md-8">
						<div class="ma_field p-4 switch-field em_size_field">
							<?php
							if ( isset( $allslidesetting['loop'] ) ) {
								$loop = $allslidesetting['loop'];
							} else {
								$loop = 'true';
							}
							?>
							<input type="radio" name="loop" id="loop1" value="true" 
							<?php
							if ( $loop == 'true' ) {
								esc_html_e( 'checked=checked', 'responsive-slider-gallery' );}
							?>
							>
							<label for="loop1"><?php esc_html_e( 'Yes', 'responsive-slider-gallery' ); ?></label>
							<input type="radio" name="loop" id="loop2" value="false" 
							<?php
							if ( $loop == 'false' ) {
								esc_html_e( 'checked=checked', 'responsive-slider-gallery' );}
							?>
							>
							<label for="loop2"><?php esc_html_e( 'No', 'responsive-slider-gallery' ); ?></label>
						</div>
					</div>
					<div class="col-md-4">
						<div class="ma_field_discription">
							<h6><?php esc_html_e( 'Transition Effect Duration', 'responsive-slider-gallery' ); ?></h6>
							<p><?php esc_html_e( 'Set transition effect duration in millisecond between slides like 50 / 100 / 250 / 500', 'responsive-slider-gallery' ); ?></p> 
						</div>
					</div>
					<div class="col-md-8">
						<div class="ma_field p-4">
							<?php
							if ( isset( $allslidesetting['transition-duration'] ) ) {
								$transitionduration = $allslidesetting['transition-duration'];
							} else {
								$transitionduration = '300';
							}
							?>
							<input class="input_width" type="text" name="transition-duration" id="transition-duration" value="<?php echo esc_html( $transitionduration ); ?>"><br>
						</div>
					</div>
				</div>
				<div class="bhoechie-tab-content">
					<h1><?php esc_html_e( 'Navigation settings', 'responsive-slider-gallery' ); ?></h1>
					<hr>
					<div class="col-md-4">
						<div class="ma_field_discription">
							<h6><?php esc_html_e( 'Navigation Style', 'responsive-slider-gallery' ); ?></h6>
							<p><?php esc_html_e( 'Set a navigation style like dots / none', 'responsive-slider-gallery' ); ?></p> 
						</div>
					</div>
					<div class="col-md-8">
						<div class="ma_field p-4 switch-field em_size_field">
							<?php if ( isset( $allslidesetting['nav-style'] ) ) { $navstyle = $allslidesetting['nav-style']; } else { $navstyle = 'dots'; } ?>
							<input type="radio" name="nav-style" id="nav-style1" value="dots" 
								<?php if ( $navstyle == 'dots' ) { esc_html_e( 'checked=checked', 'responsive-slider-gallery' );} ?>
							>
							<label for="nav-style1"><?php esc_html_e( 'Dots', 'responsive-slider-gallery' ); ?></label>
							<input type="radio" name="nav-style" id="nav-style3" value="false" 
								<?php if ( $navstyle == 'false' ) { esc_html_e( 'checked=checked', 'responsive-slider-gallery' );}?>
							>
							<label for="nav-style3"><?php esc_html_e( 'None', 'responsive-slider-gallery' ); ?></label>
							
							
						
						</div>
					</div>
					<div class="dots_hs">
						<div class="col-md-4">
							<div class="ma_field_discription">
								<h6><?php esc_html_e( ' Navigation Width', 'responsive-slider-gallery' ); ?></h6>
								<p><?php esc_html_e( 'Set navigation width in pixels or percent', 'responsive-slider-gallery' ); ?></p> 
							</div>
						</div>
						<div class="col-md-8">
							<div class="ma_field p-4">
								<?php
									if ( isset( $allslidesetting['nav-width'] ) ) {
										$navwidth = $allslidesetting['nav-width'];
									} else {
										$navwidth = '';
									}
									?>
								<input class="input_width" type="text" name="nav-width" id="nav-width" value="<?php echo esc_attr( $navwidth ); ?>"><br>
							</div>
						</div>
					</div>
		
					<div class="col-md-4">
						<div class="ma_field_discription">
							<h6><?php esc_html_e( 'Navigation Arrow', 'responsive-slider-gallery' ); ?></h6>
							<p><?php esc_html_e( 'Set navigation arrow display options', 'responsive-slider-gallery' ); ?></p> 
						</div>
					</div>
					<div class="col-md-8">
						<div class="ma_field p-4 switch-field em_size_field">
							<?php
							if ( isset( $allslidesetting['nav-arrow'] ) ) {
								$navarrow = $allslidesetting['nav-arrow'];
							} else {
								$navarrow = 'true';
							}
							?>
							<input type="radio" name="nav-arrow" id="nav-arrow2" value="true" 
							<?php
							if ( $navarrow == 'true' ) {
								esc_html_e( 'checked=checked', 'responsive-slider-gallery' );}
							?>
							>
							<label for="nav-arrow2"><?php esc_html_e( 'Show', 'responsive-slider-gallery' ); ?></label>
							<input type="radio" name="nav-arrow" id="nav-arrow3" value="false" 
							<?php
							if ( $navarrow == 'false' ) {
								esc_html_e( 'checked=checked', 'responsive-slider-gallery' );}
							?>
							>
							<label for="nav-arrow3"><?php esc_html_e( 'Hide', 'responsive-slider-gallery' ); ?></label>
						</div>
					</div>
					<div class="col-md-4">
						<div class="ma_field_discription">
							<h6><?php esc_html_e( 'Touch Slide', 'responsive-slider-gallery' ); ?></h6>
							<p><?php esc_html_e( 'Set touch slide to slide images using mouse touch or swipe action', 'responsive-slider-gallery' ); ?></p> 
						</div>
					</div>
					<div class="col-md-8">
						<div class="ma_field p-4 switch-field em_size_field">
							<?php
							if ( isset( $allslidesetting['touch-slide'] ) ) {
								$touchslide = $allslidesetting['touch-slide'];
							} else {
								$touchslide = 'true';
							}
							?>
							<input type="radio" name="touch-slide" id="touch-slide1" value="true" 
							<?php
							if ( $touchslide == 'true' ) {
								esc_html_e( 'checked=checked', 'responsive-slider-gallery' );}
							?>
							>
							<label for="touch-slide1"><?php esc_html_e( 'Yes', 'responsive-slider-gallery' ); ?></label>
							<input type="radio" name="touch-slide" id="touch-slide2" value="false" 
							<?php
							if ( $touchslide == 'false' ) {
								esc_html_e( 'checked=checked', 'responsive-slider-gallery' );}
							?>
							>
							<label for="touch-slide2"><?php esc_html_e( 'No', 'responsive-slider-gallery' ); ?></label>
						</div>
					</div>
					<div class="col-md-4">
						<div class="ma_field_discription">
							<h6><?php esc_html_e( 'Slide Loading Spinner', 'responsive-slider-gallery' ); ?></h6>
							<p><?php esc_html_e( 'Set loading spinner option to show spinner while loading slides', 'responsive-slider-gallery' ); ?></p> 
						</div>
					</div>
					<div class="col-md-8">
						<div class="ma_field p-4 switch-field em_size_field">
							<?php
							if ( isset( $allslidesetting['spinner'] ) ) {
								$spinner = $allslidesetting['spinner'];
							} else {
								$spinner = 'true';
							}
							?>
							<input type="radio" name="spinner" id="spinner1" value="true" 
							<?php
							if ( $spinner == 'true' ) {
								esc_html_e( 'checked=checked', 'responsive-slider-gallery' );}
							?>
							>
							<label for="spinner1"><?php esc_html_e( 'Yes', 'responsive-slider-gallery' ); ?></label>
							<input type="radio" name="spinner" id="spinner2" value="false" 
							<?php
							if ( $spinner == 'false' ) {
								esc_html_e( 'checked=checked', 'responsive-slider-gallery' );}
							?>
							>
							<label for="spinner2"><?php esc_html_e( 'No', 'responsive-slider-gallery' ); ?></label>
						</div>
					</div>
				</div>	
				<div class="bhoechie-tab-content">
					<h1><?php esc_html_e( 'Upgrade To Pro', 'responsive-slider-gallery' ); ?></h1>
					<hr>
					<!--Grid-->
					<div class="" style="padding-left: 10px;">
						<p class="ms-title"><?php esc_html_e( 'Upgrade To Premium For Unloack More Features & Settings', 'responsive-slider-gallery' ); ?></p>
					</div>

					<div class="">
						<h1><strong><?php esc_html_e( 'Offer:', 'responsive-slider-gallery' ); ?></strong> <?php esc_html_e( 'Upgrade To Premium Just In Half Price', 'responsive-slider-gallery' ); ?> <strike>$20</strike> <strong>$15</strong></h1>
						<br>
						<a href="<?php echo esc_url( 'https://awplife.com/demo/responsive-slider-gallery-premium/' ); ?>" target="_blank" class="button button-primary button-hero load-customize hide-if-no-customize"><?php esc_html_e( 'Check Premium Version Live Demo', 'responsive-slider-gallery' ); ?></a>
						<a href="<?php echo esc_url( 'https://awplife.com/wordpress-plugins/responsive-slider-gallery-premium/' ); ?>" target="_blank" class="button button-primary button-hero load-customize hide-if-no-customize"><?php esc_html_e( 'Buy Premium Version', 'responsive-slider-gallery' ); ?></a>
					</div>
				</div>
			</div>
		</div>
	</div>
			
	<!-- Return to Top -->
	<a href="javascript:" id="return-to-top"><i class="fa fa-chevron-up"></i></a>	
	<?php
		// syntax: wp_nonce_field( 'name_of_my_action', 'name_of_nonce_field' );
		wp_nonce_field( 'save_settings', 'rsg_save_nonce' );
	?>
		
<script>
	// ===== Scroll to Top ==== 
	jQuery(window).scroll(function() {
		if (jQuery(this).scrollTop() >= 50) {        // If page is scrolled more than 50px
			jQuery('#return-to-top').fadeIn(200);    // Fade in the arrow
		} else {
			jQuery('#return-to-top').fadeOut(200);   // Else fade out the arrow
		}
	});
	jQuery('#return-to-top').click(function() {      // When arrow is clicked
		jQuery('body,html').animate({
			scrollTop : 0                       // Scroll to top of body
		}, 500);
	});
	
// Show Hide Settings
	// Navigation settings start
	var nav_style = jQuery('input[name="nav-style"]:checked').val();
		//on change to enable & disable navigation Setting
		if(nav_style == "dots") {
			jQuery('.dots_hs').show();
		}
		if(nav_style == "false") {
			jQuery('.dots_hs').hide();
		}

		//on change to enable & disable navigation Setting
		jQuery(document).ready(function() {
			jQuery('input[name="nav-style"]').change(function(){
				var nav_style = jQuery('input[name="nav-style"]:checked').val();
				if(nav_style == "dots") {
					jQuery('.dots_hs').show();
				}
				if(nav_style == "false") {
					jQuery('.dots_hs').hide();
				}
			});
		});
	// Navigation Setting End
	
	// Auto Play settings start
	var autoplay = jQuery('input[name="autoplay"]:checked').val();
		//on change to enable & disable navigation Setting
		if(autoplay == "true") {
			jQuery('.auto_sh').show();
		}
		if(autoplay == "false") {
			jQuery('.auto_sh').hide();
		}

		//on change to enable & disable Auto Play Setting
		jQuery(document).ready(function() {
			jQuery('input[name="autoplay"]').change(function(){
				var autoplay = jQuery('input[name="autoplay"]:checked').val();
				if(autoplay == "true") {
					jQuery('.auto_sh').show();
				}
				if(autoplay == "false") {
					jQuery('.auto_sh').hide();
				}
			});
		});
	// Auto Play Setting End
//show hide settings end

	//dropdown toggle on change effect
	jQuery(document).ready(function() {
		//accordion icon
		jQuery(function() {
			function toggleSign(e) {
				jQuery(e.target)
				.prev('.panel-heading')
				.find('i')
				.toggleClass('fa fa-chevron-down fa fa-chevron-up');
			}
			jQuery('#accordion').on('hidden.bs.collapse', toggleSign);
			jQuery('#accordion').on('shown.bs.collapse', toggleSign);

			});
		});
	
	// start pulse on page load
	function pulseEff() {
	   jQuery('#shortcode').fadeOut(600).fadeIn(600);
	};
	var Interval;
	Interval = setInterval(pulseEff,1500);

	// stop pulse
	function pulseOff() {
		clearInterval(Interval);
	}
	// start pulse
	function pulseStart() {
		Interval = setInterval(pulseEff,1500);
	}
	// tab
	jQuery("div.bhoechie-tab-menu>div.list-group>a").click(function(e) {
		e.preventDefault();
		jQuery(this).siblings('a.active').removeClass("active");
		jQuery(this).addClass("active");
		var index = jQuery(this).index();
		jQuery("div.bhoechie-tab>div.bhoechie-tab-content").removeClass("active");
		jQuery("div.bhoechie-tab>div.bhoechie-tab-content").eq(index).addClass("active");
	});
</script>		
