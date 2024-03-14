<?php
/**
 * Logo Showcase Grid Metabox Setting
 *
 * @package Logo Showcase with Slick Slider
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="lswssp-vtab-wrap lswssp-vtab-sett-wrap lswssp-vtab-grid-sett-wrap <?php if( $display_type != 'grid' ) { echo 'lswssp-hide'; } ?>">

	<div class="lswssp-vtab-heading"><i class="dashicons dashicons-admin-generic"></i> <?php _e('Logo Showcase Grid Settings', 'logo-showcase-with-slick-slider'); ?></div>

	<ul class="lswssp-vtab-nav-wrap">
		<li class="lswssp-vtab-nav lswssp-vtab-nav-grid-general-sett lswssp-active-vtab">
			<a class="lswssp-grid-general-sett-nav" href="#lswss_grid_general_sett"><i class="dashicons dashicons-format-gallery" aria-hidden="true"></i> <?php esc_html_e('General Settings', 'logo-showcase-with-slick-slider'); ?></a>
		</li>
		
		<li class="lswssp-vtab-nav lswssp-vtab-nav-grid-mobile-sett">
			<a class="lswssp-grid-mobile-sett-nav" href="#lswss_grid_mobile_sett"><i class="dashicons dashicons-smartphone" aria-hidden="true"></i> <?php esc_html_e('Mobile Settings', 'logo-showcase-with-slick-slider'); ?></a>
		</li>
		<li class="lswssp-vtab-nav lswssp-vtab-nav-grid-general-sett">
			<a class="lswssp-grid-query-sett-nav" href="#lswss_grid_query_sett"><i class="dashicons dashicons-database-view" aria-hidden="true"></i> <?php esc_html_e('Query Setting ', 'logo-showcase-with-slick-slider'); ?> <span class="lswssp-badge">Pro</span></a>
		</li>
		<li class="lswssp-vtab-nav lswssp-vtab-nav-grid-tooltip-sett">
			<a class="lswssp-grid-tooltip-sett-nav" href="#lswss_grid_tooltip_sett"><i class="dashicons dashicons-testimonial" aria-hidden="true"></i> <?php esc_html_e('Tooltip Settings', 'logo-showcase-with-slick-slider'); ?> <span class="lswssp-badge">Pro</span></a>
		</li>
	</ul>

	<div class="lswssp-vtab-cnt-wrp">
		<!-- General Settings -->
		<div class="lswssp-vtab-cnt lswssp-grid-general-sett lswssp-clearfix" id="lswss_grid_general_sett">
			<table class="form-table lswssp-tbl">
				<tbody>
					<tr>
						<th>
							<label for="lswssp-grid-logo-design"><?php _e('Logo Showcase Design', 'logo-showcase-with-slick-slider'); ?></label>
						</th>
						<td>
							<select name="<?php echo esc_attr( $prefix ); ?>sett[grid][design]" class="lswssp-select-box lswssp-grid-logo-design" id="lswssp-grid-logo-design">
								<?php
								if( ! empty( $logo_grid_designs ) ) {
									foreach ( $logo_grid_designs as $key => $value ) {
										
										$disable_opt = ( $key == 'design-1') ? '' : 'disabled="disabled"';
										
										echo '<option value="'.esc_attr( $key ).'" '.selected( $post_sett['grid']['design'], $key).' '.$disable_opt.'>'. esc_html( $value ).'</option>';
										
									}
								}
								?>
							</select><br/>
							<span class="description"><?php _e('Select logo showcase design.', 'logo-showcase-with-slick-slider'); ?></span><br />
							<span class="description"><i class="dashicons dashicons-lock"></i> <?php echo sprintf( __('For more designs, please take a look at %spremium demo%s.', 'logo-showcase-with-slick-slider'), '<a href="https://premium.infornweb.com/logo-grid-designs/" target="_blank">', '</a>' ); ?></span>
						</td>
					</tr>
					<tr>
						<th>
							<label for="lswssp-grid-logo-title"><?php _e('Logo Title', 'logo-showcase-with-slick-slider'); ?></label>
						</th>
						<td>
							<select name="<?php echo esc_attr( $prefix ); ?>sett[grid][show_title]" id="lswssp-grid-logo-title" class="lswssp-select-box lswssp-grid-logo-title">
								<option value="true"><?php esc_html_e('True', 'logo-showcase-with-slick-slider'); ?></option>
								<option value="false" <?php selected( 'false', $post_sett['grid']['show_title'] ); ?>><?php esc_html_e('False', 'logo-showcase-with-slick-slider'); ?></option>
							</select><br/>
							<span class="description"><?php _e('Display logo title.', 'logo-showcase-with-slick-slider'); ?></span>
						</td>
					</tr>
					<tr>
						<th>
							<label for="lswssp-grid-logo-desc"><?php _e('Logo Description', 'logo-showcase-with-slick-slider'); ?></label>
						</th>
						<td>
							<select name="<?php echo esc_attr( $prefix ); ?>sett[grid][show_desc]" id="lswssp-grid-logo-desc" class="lswssp-select-box lswssp-grid-logo-desc">
								<option value="true"><?php esc_html_e('True', 'logo-showcase-with-slick-slider'); ?></option>
								<option value="false" <?php selected( 'false', $post_sett['grid']['show_desc'] ); ?>><?php esc_html_e('False', 'logo-showcase-with-slick-slider'); ?></option>
							</select><br/>
							<span class="description"><?php _e('Display logo description.', 'logo-showcase-with-slick-slider'); ?></span>
						</td>
					</tr>
					<tr>
						<th>
							<label for="lswssp-grid-logo-clmns"><?php _e('Number of Columns', 'logo-showcase-with-slick-slider'); ?></label>
						</th>
						<td>
							<input type="text" name="<?php echo esc_attr( $prefix ); ?>sett[grid][grid]" value="<?php echo esc_attr( $post_sett['grid']['grid'] ); ?>" class="lswssp-input lswssp-grid-logo-clmns" id="lswssp-grid-logo-clmns" /><br/>
							<span class="description"><?php _e('Set number of logos grid.', 'logo-showcase-with-slick-slider'); ?></span>
						</td>
					</tr>
					<tr>
						<th>
							<label for="lswssp-grid-link-behv"><?php _e('Logo Link Behavior', 'logo-showcase-with-slick-slider'); ?></label>
						</th>
						<td>
							<select name="<?php echo esc_attr( $prefix ); ?>sett[grid][link_target]" id="lswssp-grid-link-behv" class="lswssp-select-box lswssp-grid-link-behv">
								<option value="_blank"><?php esc_html_e('Open in New Tab', 'logo-showcase-with-slick-slider'); ?></option>
								<option value="_self" <?php selected( '_self', $post_sett['grid']['link_target'] ); ?>><?php esc_html_e('Open in Same Tab', 'logo-showcase-with-slick-slider'); ?></option>
							</select><br/>
							<span class="description"><?php _e('Choose logo link behavior.', 'logo-showcase-with-slick-slider'); ?></span>
						</td>
					</tr>
					<tr>
						<th>
							<label for="lswssp-grid-logo-min-height"><?php _e('Logo Wrap Minimum Height', 'logo-showcase-with-slick-slider'); ?></label>
						</th>
						<td>
							<input type="text" name="<?php echo esc_attr( $prefix ); ?>sett[grid][min_height]" value="<?php echo esc_attr( $post_sett['grid']['min_height'] ); ?>" class="lswssp-input lswssp-grid-logo-min-height" id="lswssp-grid-logo-min-height" /> <?php _e('Px', 'logo-showcase-with-slick-slider'); ?><br/>
							<span class="description"><?php _e('Set minimum height for logo wrapper. It will display all logo box with the same height. e.g. 200. Leave it empty for default.', 'logo-showcase-with-slick-slider'); ?></span>
						</td>
					</tr>
					
					<tr>
						<th>
							<label for="lswssp-grid-logo-max-height"><?php _e('Logo Image Maximum Height', 'logo-showcase-with-slick-slider'); ?></label>
						</th>
						<td>
							<input type="text" name="<?php echo esc_attr( $prefix ); ?>sett[grid][max_height]" value="<?php echo esc_attr( $post_sett['grid']['max_height'] ); ?>" class="lswssp-input lswssp-grid-logo-max-height" id="lswssp-grid-logo-max-height" /> <?php _e('Px', 'logo-showcase-with-slick-slider'); ?><br/>
							<span class="description"><?php _e('Set maximum height for logo image. e.g. 200. Leave it empty for default.', 'logo-showcase-with-slick-slider'); ?></span>
						</td>
					</tr>
					
					<tr>
						<th colspan="2">
							<div class="lswssp-sub-title"><i class="dashicons dashicons-admin-generic"></i> <?php esc_html_e('Premium Settings', 'logo-showcase-with-slick-slider'); ?></div>
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


		<!-- Query Settings -->
		<div class="lswssp-vtab-cnt lswssp-grid-query-sett lswssp-clearfix" id="lswss_grid_query_sett">
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
		<div class="lswssp-vtab-cnt lswssp-grid-tooltip-sett lswssp-clearfix" id="lswss_grid_tooltip_sett">
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
		<div class="lswssp-vtab-cnt lswssp-grid-mobile-sett lswssp-clearfix" id="lswss_grid_mobile_sett">
			<table class="form-table lswssp-tbl">
				<tbody>
					<tr>
						<th>
							<label for="lswssp-grid-logo-mobile"><?php _e('Logo in Mobile', 'logo-showcase-with-slick-slider'); ?></label>
						</th>
						<td>
							<input type="text" name="<?php echo esc_attr( $prefix ); ?>sett[grid][mobile]" value="<?php echo esc_attr( $post_sett['grid']['mobile'] ); ?>" class="lswssp-grid-logo-mobile" id="lswssp-grid-logo-mobile" /><br/>
							<span class="description"><?php _e('Set number of logos to be visible in mobile at a time. Mobile screen is 480px and below it.', 'logo-showcase-with-slick-slider'); ?></span>
						</td>
					</tr>
					<tr>
						<th>
							<label for="lswssp-grid-logo-tablet"><?php _e('Logo in Tablet', 'logo-showcase-with-slick-slider'); ?></label>
						</th>
						<td>
							<input type="text" name="<?php echo esc_attr( $prefix ); ?>sett[grid][tablet]" value="<?php echo esc_attr( $post_sett['grid']['tablet'] ); ?>" class="lswssp-grid-logo-tablet" id="lswssp-grid-logo-tablet" /><br/>
							<span class="description"><?php _e('Set number of logos to be visible in tablet at a time. Tablet screen is 481px to 640px.', 'logo-showcase-with-slick-slider'); ?></span>
						</td>
					</tr>
					<tr>
						<th>
							<label for="lswssp-grid-logo-ipad"><?php _e('Logo in iPad', 'logo-showcase-with-slick-slider'); ?></label>
						</th>
						<td>
							<input type="text" name="<?php echo esc_attr( $prefix ); ?>sett[grid][ipad]" value="<?php echo esc_attr( $post_sett['grid']['ipad'] ); ?>" class="lswssp-grid-logo-ipad" id="lswssp-grid-logo-ipad" /><br/>
							<span class="description"><?php _e('Set number of logos to be visible in iPad at a time. iPad screen is 641px to 768px.', 'logo-showcase-with-slick-slider'); ?></span>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div><!-- end .lswssp-vtab-cnt-wrp -->
</div><!-- end .lswssp-vtab-wrap -->