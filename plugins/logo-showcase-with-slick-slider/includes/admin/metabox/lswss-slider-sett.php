<?php
/**
 * Logo Showcase Slider Metabox Setting
 *
 * @package Logo Showcase with Slick Slider
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="lswssp-vtab-wrap lswssp-vtab-sett-wrap lswssp-vtab-slider-sett-wrap <?php if( $display_type != 'slider' ) { echo 'lswssp-hide'; } ?>">

	<div class="lswssp-vtab-heading"><i class="dashicons dashicons-admin-generic"></i> <?php _e('Logo Showcase Carousel Settings', 'logo-showcase-with-slick-slider'); ?></div>

	<ul class="lswssp-vtab-nav-wrap">
		<li class="lswssp-vtab-nav lswssp-vtab-nav-slider-general-sett lswssp-active-vtab">
			<a class="lswssp-slider-general-sett-nav" href="#lswss_slider_general_sett"><i class="dashicons dashicons-format-gallery" aria-hidden="true"></i> <?php esc_html_e('General Settings', 'logo-showcase-with-slick-slider'); ?></a>
		</li>
		<li class="lswssp-vtab-nav lswssp-vtab-nav-slider-slider-sett">
			<a class="lswssp-slider-slider-sett-nav" href="#lswss_slider_slider_sett"><i class="dashicons dashicons-slides" aria-hidden="true"></i> <?php esc_html_e('Carousel Settings', 'logo-showcase-with-slick-slider'); ?></a>
		</li>
			<li class="lswssp-vtab-nav lswssp-vtab-nav-slider-mobile-sett">
			<a class="lswssp-slider-mobile-sett-nav" href="#lswss_slider_mobile_sett"><i class="dashicons dashicons-smartphone" aria-hidden="true"></i> <?php esc_html_e('Mobile Settings', 'logo-showcase-with-slick-slider'); ?></a>
		</li>
		<li class="lswssp-vtab-nav lswssp-vtab-nav-slider-general-sett">
			<a class="lswssp-slider-query-sett-nav" href="#lswss_slider_query_sett"><i class="dashicons dashicons-database-view" aria-hidden="true"></i> <?php esc_html_e('Query Settings', 'logo-showcase-with-slick-slider'); ?> <span class="lswssp-badge">Pro</span></a>
		</li>
		<li class="lswssp-vtab-nav lswssp-vtab-nav-slider-tooltip-sett">
			<a class="lswssp-slider-tooltip-sett-nav" href="#lswss_slider_tooltip_sett"><i class="dashicons dashicons-testimonial" aria-hidden="true"></i> <?php esc_html_e('Tooltip Settings', 'logo-showcase-with-slick-slider'); ?> <span class="lswssp-badge">Pro</span></a>
		</li>
	</ul>

	<div class="lswssp-vtab-cnt-wrp">
		<!-- General Settings -->
		<div class="lswssp-vtab-cnt lswssp-slider-general-sett lswssp-clearfix" id="lswss_slider_general_sett">
			<table class="form-table lswssp-tbl">
				<tbody>
					<tr>
						<th>
							<label for="lswssp-slider-logo-design"><?php _e('Logo Showcase Design', 'logo-showcase-with-slick-slider'); ?></label>
						</th>
						<td>
							<select name="<?php echo esc_attr( $prefix ); ?>sett[slider][design]" class="lswssp-select-box lswssp-slider-logo-design" id="lswssp-slider-logo-design">
								<?php
								if( ! empty( $logo_slider_designs ) ) {
									foreach ( $logo_slider_designs as $key => $value ) {
										
										$disable_opt = ( $key == 'design-1') ? '' : 'disabled="disabled"';
										
										echo '<option value="'.esc_attr( $key ).'" '.selected( $post_sett['slider']['design'], $key).' '.$disable_opt.'>'.esc_html( $value ).'</option>';
									}
								}
								?>
							</select><br/>
							<span class="description"><?php _e('Select logo showcase design.', 'logo-showcase-with-slick-slider'); ?></span><br />
							<span class="description"><i class="dashicons dashicons-lock"></i> <?php echo sprintf( __('For more designs, please take a look at %spremium demo%s.', 'logo-showcase-with-slick-slider'), '<a href="https://premium.infornweb.com/logo-slider-designs/" target="_blank">', '</a>' ); ?></span>
						</td>
					</tr>
					<tr>
						<th>
							<label for="lswssp-slider-logo-title"><?php _e('Logo Title', 'logo-showcase-with-slick-slider'); ?></label>
						</th>
						<td>
							<select name="<?php echo esc_attr( $prefix ); ?>sett[slider][show_title]" id="lswssp-slider-logo-title" class="lswssp-select-box lswssp-slider-logo-title">
								<option value="true"><?php esc_html_e('True', 'logo-showcase-with-slick-slider'); ?></option>
								<option value="false" <?php selected( 'false', $post_sett['slider']['show_title'] ); ?>><?php esc_html_e('False', 'logo-showcase-with-slick-slider'); ?></option>
							</select><br/>
							<span class="description"><?php _e('Display logo title.', 'logo-showcase-with-slick-slider'); ?></span>
						</td>
					</tr>
					<tr>
						<th>
							<label for="lswssp-slider-logo-desc"><?php _e('Logo Description', 'logo-showcase-with-slick-slider'); ?></label>
						</th>
						<td>
							<select name="<?php echo esc_attr( $prefix ); ?>sett[slider][show_desc]" id="lswssp-slider-logo-desc" class="lswssp-select-box lswssp-slider-logo-desc">
								<option value="true"><?php esc_html_e('True', 'logo-showcase-with-slick-slider'); ?></option>
								<option value="false" <?php selected( 'false', $post_sett['slider']['show_desc'] ); ?>><?php esc_html_e('False', 'logo-showcase-with-slick-slider'); ?></option>
							</select><br/>
							<span class="description"><?php _e('Display logo description.', 'logo-showcase-with-slick-slider'); ?></span>
						</td>
					</tr>
					
					<tr>
						<th>
							<label for="lswssp-slider-link-behv"><?php _e('Logo Link Behavior', 'logo-showcase-with-slick-slider'); ?></label>
						</th>
						<td>
							<select name="<?php echo esc_attr( $prefix ); ?>sett[slider][link_target]" id="lswssp-slider-link-behv" class="lswssp-select-box lswssp-slider-link-behv">
								<option value="_blank"><?php esc_html_e('Open in New Tab', 'logo-showcase-with-slick-slider'); ?></option>
								<option value="_self" <?php selected( '_self', $post_sett['slider']['link_target'] ); ?>><?php esc_html_e('Open in Same Tab', 'logo-showcase-with-slick-slider'); ?></option>
							</select><br/>
							<span class="description"><?php _e('Choose logo link behavior.', 'logo-showcase-with-slick-slider'); ?></span>
						</td>
					</tr>
					<tr>
						<th>
							<label for="lswssp-slider-logo-min-height"><?php _e('Logo Wrap Minimum Height', 'logo-showcase-with-slick-slider'); ?></label>
						</th>
						<td>
							<input type="text" name="<?php echo esc_attr( $prefix ); ?>sett[slider][min_height]" value="<?php echo esc_attr( $post_sett['slider']['min_height'] ); ?>" class="lswssp-input lswssp-slider-logo-min-height" id="lswssp-slider-logo-min-height" /> <?php _e('Px', 'logo-showcase-with-slick-slider'); ?><br/>
							<span class="description"><?php _e('Set minimum height for logo wrapper. It will display all logo box with the same height. e.g. 200. Leave it empty for default.', 'logo-showcase-with-slick-slider'); ?></span>
						</td>
					</tr>
					
					<tr>
						<th>
							<label for="lswssp-slider-logo-max-height"><?php _e('Logo Image Maximum Height', 'logo-showcase-with-slick-slider'); ?></label>
						</th>
						<td>
							<input type="text" name="<?php echo esc_attr( $prefix ); ?>sett[slider][max_height]" value="<?php echo esc_attr( $post_sett['slider']['max_height'] ); ?>" class="lswssp-input lswssp-slider-logo-max-height" id="lswssp-slider-logo-max-height" /> <?php _e('Px', 'logo-showcase-with-slick-slider'); ?><br/>
							<span class="description"><?php _e('Set maximum height for logo image. e.g. 200. Leave it empty for default.', 'logo-showcase-with-slick-slider'); ?></span>
						</td>
					</tr>
					
					<tr>
						<th colspan="2">
							<div class="lswssp-sub-title"><i class="dashicons dashicons-admin-generic"></i> Upgrade to pro for more settings</div>
						</th>
					</tr>				
					
					<tr>
						<td class="lswssp-pro-btn-wrp" colspan="2">
							<a class="lswssp-pro-btn" href="<?php echo esc_url($upgrade_link); ?>"><?php esc_html_e('Upgrade To Premium', 'logo-showcase-with-slick-slider'); ?></a>
							<div class="lswssp-pro-img">
								<img src="<?php echo LSWSS_URL; ?>/assets/images/common-pro-settings.png" alt="Common Settings" />
							</div>
						</td>
					</tr>
				</tbody>
			</table>
		</div>


		<!-- Slider Settings -->
		<div class="lswssp-vtab-cnt lswssp-slider-slider-sett lswssp-clearfix" id="lswss_slider_slider_sett">
			<table class="form-table lswssp-tbl">
				<tbody>
					<tr>
						<th>
							<label for="lswssp-slider-logo-clmns"><?php _e('Number of Slides', 'logo-showcase-with-slick-slider'); ?></label>
						</th>
						<td>
							<input type="text" name="<?php echo esc_attr( $prefix ); ?>sett[slider][slides_show]" value="<?php echo esc_attr( $post_sett['slider']['slides_show'] ); ?>" class="lswssp-input lswssp-slider-logo-clmns" id="lswssp-slider-logo-clmns" /><br/>
							<span class="description"><?php _e('Set number of logos slides at a time.', 'logo-showcase-with-slick-slider'); ?></span>
						</td>
					</tr>
					<tr>
						<th>
							<label for="lswssp-slider-slides-scroll"><?php _e('Slides To Scroll', 'logo-showcase-with-slick-slider'); ?></label>
						</th>
						<td>
							<input type="text" name="<?php echo esc_attr( $prefix ); ?>sett[slider][slides_scroll]" value="<?php echo esc_attr( $post_sett['slider']['slides_scroll'] ); ?>" class="lswssp-input lswssp-slider-slides-scroll" id="lswssp-slider-slides-scroll" /><br/>
							<span class="description"><?php _e('Set numbers of logos to scroll at a time.', 'logo-showcase-with-slick-slider'); ?></span>
						</td>
					</tr>
					
					<tr>
						<th>
							<label for="lswssp-slider-arrow"><?php _e('Arrows', 'logo-showcase-with-slick-slider'); ?></label>
						</th>
						<td>
							<select name="<?php echo esc_attr( $prefix ); ?>sett[slider][arrow]" id="lswssp-slider-arrow" class="lswssp-select-box lswssp-slider-arrow">
								<option value="true"><?php esc_html_e('True', 'logo-showcase-with-slick-slider'); ?></option>
								<option value="false" <?php selected( 'false', $post_sett['slider']['arrow'] ); ?>><?php esc_html_e('False', 'logo-showcase-with-slick-slider'); ?></option>
							</select><br/>
							<span class="description"><?php _e('Enable arrow navigation for logos slider.', 'logo-showcase-with-slick-slider'); ?></span>
						</td>
					</tr>
					<tr>
						<th>
							<label for="lswssp-slider-dots"><?php _e('Dots', 'logo-showcase-with-slick-slider'); ?></label>
						</th>
						<td>
							<select name="<?php echo esc_attr( $prefix ); ?>sett[slider][dots]" id="lswssp-slider-dots" class="lswssp-select-box lswssp-slider-dots">
								<option value="true"><?php esc_html_e('True', 'logo-showcase-with-slick-slider'); ?></option>
								<option value="false" <?php selected( 'false', $post_sett['slider']['dots'] ); ?>><?php esc_html_e('False', 'logo-showcase-with-slick-slider'); ?></option>
							</select><br/>
							<span class="description"><?php _e('Enable dots navigation for logo slider.', 'logo-showcase-with-slick-slider'); ?></span>
						</td>
					</tr>

					<tr>
						<th colspan="2">
							<div class="lswssp-sub-title"><i class="dashicons dashicons-admin-generic"></i> <?php _e('Autoplay and Speed Settings', 'logo-showcase-with-slick-slider'); ?></div>
						</th>
					</tr>
					<tr>
						<th>
							<label for="lswssp-slider-autoplay"><?php _e('Autoplay', 'logo-showcase-with-slick-slider'); ?></label>
						</th>
						<td>
							<select name="<?php echo esc_attr( $prefix ); ?>sett[slider][autoplay]" id="lswssp-slider-autoplay" class="lswssp-select-box lswssp-slider-autoplay">
								<option value="true"><?php esc_html_e('True', 'logo-showcase-with-slick-slider'); ?></option>
								<option value="false" <?php selected( 'false', $post_sett['slider']['autoplay'] ); ?>><?php esc_html_e('False', 'logo-showcase-with-slick-slider'); ?></option>
							</select><br/>
							<span class="description"><?php _e('Enable autoplay for logo slider.', 'logo-showcase-with-slick-slider'); ?></span>
						</td>
					</tr>
					<tr>
						<th>
							<label for="lswssp-slider-autoplay-speed"><?php _e('Autoplay Speed', 'logo-showcase-with-slick-slider'); ?></label>
						</th>
						<td>
							<input type="text" name="<?php echo esc_attr( $prefix ); ?>sett[slider][autoplay_speed]" value="<?php echo esc_attr( $post_sett['slider']['autoplay_speed'] ); ?>" class="lswssp-input lswssp-slider-autoplay-speed" id="lswssp-slider-autoplay-speed" /><br/>
							<span class="description"><?php _e('Enter slider autoplay speed. Default value is 3000. 1000 = 1 sec.', 'logo-showcase-with-slick-slider'); ?></span>
						</td>
					</tr>
					<tr>
						<th>
							<label for="lswssp-slider-speed"><?php _e('Speed', 'logo-showcase-with-slick-slider'); ?></label>
						</th>
						<td>
							<input type="text" name="<?php echo esc_attr( $prefix ); ?>sett[slider][speed]" value="<?php echo esc_attr( $post_sett['slider']['speed'] ); ?>" class="lswssp-input lswssp-slider-speed" id="lswssp-slider-speed" /><br/>
							<span class="description"><?php _e('Enter slider slide speed. Default value is 600. 1000 = 1 sec.','logo-showcase-with-slick-slider'); ?></span>
						</td>
					</tr>
					<tr>
						<th>
							<label for="lswssp-slider-loop"><?php _e('Loop', 'logo-showcase-with-slick-slider'); ?></label>
						</th>
						<td>
							<select name="<?php echo esc_attr( $prefix ); ?>sett[slider][loop]" id="lswssp-slider-loop" class="lswssp-select-box lswssp-slider-loop">
								<option value="true"><?php esc_html_e('True', 'logo-showcase-with-slick-slider'); ?></option>
								<option value="false" <?php selected( 'false', $post_sett['slider']['loop'] ); ?>><?php esc_html_e('False', 'logo-showcase-with-slick-slider'); ?></option>
							</select><br/>
							<span class="description"><?php _e('Enable continuous loop for logo slider.', 'logo-showcase-with-slick-slider'); ?></span>
						</td>
					</tr>
					
					<tr>
						<th>
							<label for="lswssp-slider-pause-on-hover"><?php _e('Pause Slider On Hover', 'logo-showcase-with-slick-slider'); ?></label>
						</th>
						<td>
							<select name="<?php echo esc_attr( $prefix ); ?>sett[slider][pause_on_hover]" id="lswssp-slider-pause-on-hover" class="lswssp-select-box lswssp-slider-pause-on-hover">
								<option value="true"><?php esc_html_e('True', 'logo-showcase-with-slick-slider'); ?></option>
								<option value="false" <?php selected( 'false', $post_sett['slider']['pause_on_hover'] ); ?>><?php esc_html_e('False', 'logo-showcase-with-slick-slider'); ?></option>
							</select><br/>
							<span class="description"><?php _e('Pause slider on mouse hover.', 'logo-showcase-with-slick-slider'); ?></span>
						</td>
					</tr>

					<tr>
						<th colspan="2">
							<div class="lswssp-sub-title"><i class="dashicons dashicons-admin-generic"></i> <?php _e('Center Mode Settings', 'logo-showcase-with-slick-slider'); ?></div>
						</th>
					</tr>
					<tr>
						<th>
							<label for="lswssp-slider-centermode"><?php _e('Center Mode', 'logo-showcase-with-slick-slider'); ?></label>
						</th>
						<td>
							<select name="<?php echo esc_attr( $prefix ); ?>sett[slider][centermode]" id="lswssp-slider-centermode" class="lswssp-select-box lswssp-slider-centermode">
								<option value="false"><?php esc_html_e('False', 'logo-showcase-with-slick-slider'); ?></option>
								<option value="true" <?php selected( 'true', $post_sett['slider']['centermode'] ); ?>><?php esc_html_e('True', 'logo-showcase-with-slick-slider'); ?></option>
							</select><br/>
							<span class="description"><?php _e('Enable center mode effect for logo slider.', 'logo-showcase-with-slick-slider'); ?></span><br/>
							<span class="description"><?php _e('Note: Center mode effect works better with odd number of "Number of Slides". e.g. 1,3 and so on.', 'logo-showcase-with-slick-slider'); ?></span>
						</td>
					</tr>
					<tr>
						<th>
							<label for="lswssp-slider-center-padding"><?php _e('Center Mode Padding', 'logo-showcase-with-slick-slider'); ?></label>
						</th>
						<td>
							<input type="text" name="<?php echo esc_attr( $prefix ); ?>sett[slider][center_padding]" value="<?php echo esc_attr( $post_sett['slider']['center_padding'] ); ?>" class="lswssp-input lswssp-slider-center-padding" id="lswssp-slider-center-padding" /> <?php _e('PX', 'logo-showcase-with-slick-slider'); ?><br/>
							<span class="description"><?php _e('Enter center padding value for partial visible slide from left and right.', 'logo-showcase-with-slick-slider'); ?></span>
						</td>
					</tr>
					
					<tr>
						<th colspan="2">
							<div class="lswssp-sub-title"><i class="dashicons dashicons-admin-generic"></i> Upgrade to pro for more settings</div>
						</th>
					</tr>
					<tr>
						<td class="lswssp-pro-btn-wrp" colspan="2">
								<a class="lswssp-pro-btn" href="<?php echo esc_url($upgrade_link); ?>"><?php esc_html_e('Upgrade To Premium', 'logo-showcase-with-slick-slider'); ?></a>							
						</td>
					</tr>	
					<tr>
						<th>
							<label for="lswssp-slider-slides-row"><?php _e('Slider Row', 'logo-showcase-with-slick-slider'); ?></label>
						</th>
						<td>
							<input type="text" name="" value="" class="lswssp-input lswssp-slider-slides-row" id="lswssp-slider-slides-row" disabled /><br/>
							<span class="description"><?php _e('Set number of slider row to be visible at a time.', 'logo-showcase-with-slick-slider'); ?></span>
						</td>
					</tr>
					<tr>
						<th>
							<label for="lswssp-slider-swipemode"><?php _e('Free Swipe Mode', 'logo-showcase-with-slick-slider'); ?></label>
						</th>
						<td>
							<select name="" id="lswssp-slider-swipemode" class="lswssp-select-box lswssp-slider-swipemode disabled">							
								
								
							</select><br/>
							<span class="description"><?php _e('Allow users to drag or swipe directly to a slide irrespective of Slides To Scroll.', 'logo-showcase-with-slick-slider'); ?></span>
							
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		
		<!-- Query Settings -->
		<div class="lswssp-vtab-cnt lswssp-slider-query-sett lswssp-clearfix" id="lswss_slider_query_sett">
			<table class="form-table lswssp-tbl">
				<tbody>
					<tr>
						<td class="lswssp-pro-btn-wrp" colspan="2">
							<a class="lswssp-pro-btn" href="<?php echo esc_url($upgrade_link); ?>"><?php esc_html_e('Upgrade To Premium', 'logo-showcase-with-slick-slider'); ?></a>
							<div class="lswssp-pro-img">	
								<img src="<?php echo LSWSS_URL; ?>/assets/images/query-settings.png" alt="Query Settings" />
							</div>
						</td>
					</tr>
				</tbody>
			</table>
		</div>


		<!-- Tooltip Settings -->
		<div class="lswssp-vtab-cnt lswssp-slider-tooltip-sett lswssp-clearfix" id="lswss_slider_tooltip_sett">
			<table class="form-table lswssp-tbl">
				<tbody>
					<tr>					
						<td class="lswssp-pro-btn-wrp" colspan="2">
							<a class="lswssp-pro-btn" href="<?php echo esc_url($upgrade_link); ?>"><?php esc_html_e('Upgrade To Premium', 'logo-showcase-with-slick-slider'); ?></a>
							<div class="lswssp-pro-img">
								<img src="<?php echo LSWSS_URL; ?>/assets/images/tooltip-settings.png" alt="Tooltip Settings" />
							</div>							
						</td>
					</tr>
				</tbody>
			</table>
		</div>


		<!-- Mobile Settings -->
		<div class="lswssp-vtab-cnt lswssp-slider-mobile-sett lswssp-clearfix" id="lswss_slider_mobile_sett">
			<table class="form-table lswssp-tbl">
				<tbody>
					<tr>
						<th>
							<label for="lswssp-slider-logo-mobile"><?php _e('Logo in Mobile', 'logo-showcase-with-slick-slider'); ?></label>
						</th>
						<td>
							<input type="text" name="<?php echo esc_attr( $prefix ); ?>sett[slider][mobile]" value="<?php echo esc_attr( $post_sett['slider']['mobile'] ); ?>" class="lswssp-slider-logo-mobile" id="lswssp-slider-logo-mobile" /><br/>
							<span class="description"><?php _e('Set number of logos to be visible in mobile at a time. Mobile screen is 480px and below it.', 'logo-showcase-with-slick-slider'); ?></span>
						</td>
					</tr>
					<tr>
						<th>
							<label for="lswssp-slider-logo-tablet"><?php _e('Logo in Tablet', 'logo-showcase-with-slick-slider'); ?></label>
						</th>
						<td>
							<input type="text" name="<?php echo esc_attr( $prefix ); ?>sett[slider][tablet]" value="<?php echo esc_attr( $post_sett['slider']['tablet'] ); ?>" class="lswssp-slider-logo-tablet" id="lswssp-slider-logo-tablet" /><br/>
							<span class="description"><?php _e('Set number of logos to be visible in tablet at a time. Tablet screen is 481px to 640px.', 'logo-showcase-with-slick-slider'); ?></span>
						</td>
					</tr>
					<tr>
						<th>
							<label for="lswssp-slider-logo-ipad"><?php _e('Logo in iPad', 'logo-showcase-with-slick-slider'); ?></label>
						</th>
						<td>
							<input type="text" name="<?php echo esc_attr( $prefix ); ?>sett[slider][ipad]" value="<?php echo esc_attr( $post_sett['slider']['ipad'] ); ?>" class="lswssp-slider-logo-ipad" id="lswssp-slider-logo-ipad" /><br/>
							<span class="description"><?php _e('Set number of logos to be visible in iPad at a time. iPad screen is 641px to 768px.', 'logo-showcase-with-slick-slider'); ?></span>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div><!-- end .lswssp-vtab-cnt-wrp -->
</div><!-- end .lswssp-vtab-wrap -->