<?php

class WPPP_CDN_Support_Advanced extends WPPP_Admin_Renderer {
	public function add_help_tab () {
		$screen = get_current_screen();
		$screen->add_help_tab( array(
			'id'	=> 'wppp_advanced_cdn',
			'title'	=>	__( 'Overview', 'wp-performance-pack' ),
			'content'	=> '<p>' . __( "CDN support allows to serve images through a CDN, both on Front- and Backend. This eliminates the need to save intermediate images locally, thus reducing web space usage. Use of dynamic image linking is highly recommended when using WPPP CDN support for Frontend.", 'wp-performance-pack' ) . '</p>',
		) );

		$screen->add_help_tab( array(
			'id'	=> 'wppp_advanced_dynlinks',
			'title'	=>	__( 'Dynamic image linking', 'wp-performance-pack' ),
			'content'	=> '<p>' . __( "By default WordPress inserts fixed URLs to images in posts and pages. Activating this option will create those links dynamically so they will change when your blog URL changes or when you use a CDN for serving images. Substitution improves the speed of this replacement by replacing image URLs with a placeholder in your posts and pages. Substitution should be reverted, when you deactivate WPPP (or by using <em>Restore static links</em>), but there's a chance your image links will be broken when you no longer use WPPP. See plugin page on WordPress.com for further details on how to fix broken image links manually.", 'wp-performance-pack' ) . '</p>',
		) );

		$screen->add_help_tab( array(
			'id'	=> 'wppp_advanced_substitution',
			'title'	=>	__( 'Link substituion', 'wp-performance-pack' ),
			'content'	=> '<p>' . __( "To improve performance of dynamic links you can use link substituion. If activated image URLs in your posts will be replaced with <em>{{wpppdynamic}}</em> which allows a faster string replace instead of using regular expressions when setting the correct image URL. Be aware that this will alter your post contents! You can revert the changes using <em>Restore static links</em>. This should be executed automatically when deactivating this feature or deactivating WPPP.", 'wp-performance-pack' ) . '</p>',
		) );

	}

	public function render_options () {
	?>
		<input id="cdn-url" type="hidden" <?php $this->e_opt_name( 'cdnurl' ); ?> value="<?php echo $this->wppp->options['cdnurl']; ?>"/>

		<h3 class="title"><?php _e( 'CDN Support', 'wp-performance-pack' );?></h3>

		<?php
			if ( $this->wppp->options['cdn'] ) {
				$cdn_test = get_transient( 'wppp_cdntest' );
				if ( false !== $cdn_test ) {
					if ( 'ok' === $cdn_test ) { ?>
						<div class="ui-state-highlight ui-corner-all" style="padding:.5em; background: #fff; border: thin solid #7ad03a;"><span class="ui-icon ui-icon-check" style="float:left; margin-top:.2ex; margin-right:.5ex;"></span><?php _e( 'CDN active and working.', 'wp-performance-pack' );?></div>
						<?php
					} else {
						?>
						<div class="ui-state-error ui-corner-all" style="padding:.5em"><span class="ui-icon ui-icon-alert" style="float:left; margin-right:.3em;"></span><strong><?php _e( 'CDN error!', 'wp-performance-pack' );?></strong> <?php printf( __( "Either the CDN is down or CDN configuration isn't working. CDN will be retested every 15 minutes until the configuration is changed or the CDN is back up. CDN test error message: <em>%s</em>", 'wp-performance-pack' ), $cdn_test ); ?></div>
						<?php
					}
				}
			}
		?>

		<table class="form-table" style="clear:none">
			<tr valign="top">
				<th scope="row"><?php _e( 'Select CDN provider', 'wp-performance-pack' ); ?></th>
				<td>
					<select id="wppp-cdn-select" <?php $this->e_opt_name( 'cdn' ) ?> >
						<option value="false" <?php echo $this->wppp->options['cdn'] === false ? 'selected="selected"' : ''; ?>><?php _e( 'None', 'wp-performance-pack' );?></option>
						<option value="coralcdn" <?php echo $this->wppp->options['cdn'] === 'coralcdn' ? 'selected="selected"' : ''; ?>>CoralCDN</option>
						<option value="maxcdn" <?php echo $this->wppp->options['cdn'] === 'maxcdn' ? 'selected="selected"' : ''; ?>>MaxCDN</option>
						<option value="customcdn" <?php echo $this->wppp->options['cdn'] === 'customcdn' ? 'selected="selected"' : ''; ?>><?php _e( 'Custom', 'wp-performance-pack' );?></option>
					</select>
					<span id="wppp-maxcdn-signup" <?php echo $this->wppp->options['cdn'] === 'maxcdn' ? '' : 'style="display:none;"'; ?> ><a class="button" href="http://tracking.maxcdn.com/c/92472/3982/378" target="_blank"><?php _e( 'Sign up with MaxCDN', 'wp-performance-pack' );?></a> <?php _e( '<strong>Use <em>WPPP</em> as coupon code to save 25%!</strong>', 'wp-performance-pack' );?></span>
					<div id="wppp-nocdn" class="wppp-cdn-div" <?php echo $this->wppp->options['cdn'] !== false ? 'style="display:none"' : ''; ?>>
						<p class="description"><?php _e( 'CDN support is disabled. Choose a CDN provider to activate serving images through the selected CDN.', 'wp-performance-pack' );?></p>
					</div>
					<div id="wppp-coralcdn" class="wppp-cdn-div" <?php echo $this->wppp->options['cdn'] !== 'coralcdn' ? 'style="display:none"' : ''; ?>>
						<p class="description"><?php _e( '<a href="http://www.coralcdn.org" target="_blank">CoralCDN</a> does not require any additional settings.', 'wp-performance-pack' );?></p>
					</div>
					<div id="wppp-maxcdn"  class="wppp-cdn-div" <?php echo $this->wppp->options['cdn'] !== 'maxcdn' ? 'style="display:none"' : ''; ?>>
						<p><label for="cdn-url"><?php _e( 'MaxCDN Pull Zone URL:', 'wp-performance-pack' );?><br/><input id="maxcdn-url" type="text" value="<?php echo $this->wppp->options['cdnurl']; ?>" style="width:80%"/></label></p>
						<p class="description"><?php _e( '<a href="https://cp.maxcdn.com" target="_blank">Log in</a> to your <a href="http://www.maxcdn.com" target="_blank">MaxCDN</a> account, create a pull zone for your WordPress site and enter the CDN URL for that zone.', 'wp-performance-pack' );?></p>
					</div>
					<div id="wppp-customcdn" class="wppp-cdn-div" <?php echo $this->wppp->options['cdn'] !== 'customcdn' ? 'style="display:none"' : ''; ?>>
						<p><label for="cdn-url"><?php _e( 'CDN URL:', 'wp-performance-pack' );?><br/><input id="customcdn-url" type="text" value="<?php echo $this->wppp->options['cdnurl']; ?>" style="width:80%"/></label></p>
						<p class="description"><?php _e( 'Enter your CDN URL. This will be used to substitute the host name in image links.', 'wp-performance-pack' );?></p>
					</div>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e( 'Use CDN for images', 'wp-performance-pack' ); ?></th>
				<td>
					<?php _e( 'Use on', 'wp-performance-pack' );?> <input type="radio" <?php $this->e_opt_name( 'cdn_images' ); ?> <?php echo $this->wppp->options['cdn_images'] === 'front' ? 'checked="checked"' : ''; ?> value="front"><?php _e( 'Frontend', 'wp-performance-pack' );?>&nbsp;
					<input type="radio" <?php $this->e_opt_name( 'cdn_images' ); ?> <?php echo $this->wppp->options['cdn_images'] === 'back' ? 'checked="checked"' : ''; ?> value="back"><?php _e( 'Backend', 'wp-performance-pack' );?>&nbsp;
					<input type="radio" <?php $this->e_opt_name( 'cdn_images' ); ?> <?php echo $this->wppp->options['cdn_images'] === 'both' ? 'checked="checked"' : ''; ?> value="both"><?php _e( 'both', 'wp-performance-pack' );?><br/>
					<p class="description"><?php _e( 'Select if CDN should be used for Frontend and/or Backend images. You can deactivate Frontend CDN to avoid conflicts with other CDN plugins.', 'wp-performance-pack' );?></p>
				</td>
			<tr valign="top">
				<th scope="row"><?php _e( 'Dynamic image linking', 'wp-performance-pack' ); ?></th>
				<td>
					<?php $this->e_switchButton( 'dyn_links' ); ?>
					<p class="description"><?php _e( 'Instead of inserting fixed image urls into posts, urls get build dynamically when displaying the content. <strong>Highly recommended when using a CDN for Frontend images.</strong>', 'wp-performance-pack' );?></p>
					<br>
					<?php $this->e_checkbox( 'dynlinksubst', 'dyn_links_subst', __( 'Use substitution for faster dynamic links', 'wp-performance-pack' ) ); ?>
					<p class="description"><?php _e( 'Image links will be substituted by a placeholder in your posts to improve performance of dynamic links. <strong>This will alter your post content and might break your image links!</strong> To revert the changes see "<em>Restore static links</em>" below.', 'wp-performance-pack' ); ?></p>
					<br>
					<p><a class="thickbox button" href="admin-ajax.php?action=wppp_restore_all_links&width=600&height=550" title="Restore static links"><?php _e( 'Restore static links', 'wp-performance-pack' );?></a></p>
					<p class="description"><?php _e('Use this to restore all dynamic links to static links if you deactivate dynamic linking. Links will be automatically restored when WPPP gets deactivated.', 'wp-performance-pack' );?></p>
				</td>
			</tr>
		</table>

		<hr/>
	<?php
	}
}