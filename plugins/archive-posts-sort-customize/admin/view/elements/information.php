<div class="wrap <?php echo $APSC->main_slug; ?>">

	<div id="<?php echo $APSC->main_slug; ?>-plugin-information" class="metabox-holder">
	
		<div class="meta-box-sortables">
	
			<div class="postbox closed">
		
				<div class="handlediv" title="<?php esc_attr_e('Click to toggle'); ?>"><br /></div>
				<h3 class="hndle"><span><?php echo $APSC->name; ?></span></h3>
				
				<div class="inside">
				
					<div id="about-box">
					
						<p class="author-image">
						
							<a href="<?php echo $APSC->Helper->get_author_link( array( 'tp' => 'use_plugin' , 'lc' => 'footer' ) ); ?>" target="_blank">
								<span class="gravatar"></span>
								gqevu6bsiz
							</a>
						
						</p>
						
						<h4><?php _e( 'About plugin' , $APSC->ltd ); ?></h4>
	
						<p>
							<?php _e( 'Version Checked' , $APSC->ltd ); ?>:
							<code><?php echo $APSC->Helper->get_plugin_version_checked(); ?></code>
						</p>
	
						<ul>
							<li><span class="dashicons dashicons-admin-plugins"></span> <a href="<?php echo $APSC->Link->plugin; ?>" target="_blank"><?php echo $APSC->name; ?></a></li>
							<li><span class="dashicons dashicons-format-chat"></span> <a href="<?php echo $APSC->Link->forum; ?>" target="_blank"><?php _e( 'Support Forums' ); ?></a></li>
							<li><span class="dashicons dashicons-star-half"></span> <a href="<?php echo $APSC->Link->review; ?>" target="_blank"><?php _e( 'Reviews' , $APSC->ltd ); ?></a></li>
						</ul>
	
						<ul>
							<li><span class="dashicons dashicons-smiley"></span> <a href="<?php echo $APSC->Helper->get_author_link( array( 'tp' => 'use_plugin' , 'lc' => 'footer' ) ); ?>" target="_blank"><?php _e( 'Developer\'s site' , $APSC->ltd ); ?></a></li>
							<li><span class="dashicons dashicons-email"></span> <a href="<?php echo $APSC->Helper->get_author_link( array( 'contact' => 1 , 'tp' => 'use_plugin' , 'lc' => 'footer' ) ); ?>" target="_blank"><?php _e( 'Contact' , $APSC->ltd ); ?></a></li>
							<li><span class="dashicons dashicons-twitter"></span> <a href="https://twitter.com/gqevu6bsiz" target="_blank">twitter</a></li>
							<li><span class="dashicons dashicons-facebook-alt"></span> <a href="http://www.facebook.com/pages/Gqevu6bsiz/499584376749601" target="_blank">facebook</a></li>
						</ul>
						
						<p>&nbsp;</p>
						
						<h4><?php _e( 'Useful plugins' , $APSC->ltd ); ?> <span class="dashicons dashicons-admin-plugins"></span> <a href="<?php echo $APSC->Link->profile; ?>" target="_blank"><?php _e( 'Plugins' ); ?></a></h4>
	
						<ul>
							<li>
								<span class="dashicons dashicons-admin-plugins"></span>
								<a href="http://wpadminuicustomize.com/<?php echo $APSC->Helper->get_utm_link( array( 'tp' => 'use_plugin' , 'lc' => 'side' ) ); ?>" target="_blank">WP Admin UI Customize</a>:
								<span class="description"><?php _e( 'Customize a variety of screen management.' , $APSC->ltd ); ?></span>
							</li>
							<li>
								<span class="dashicons dashicons-admin-plugins"></span>
								<a href="http://wordpress.org/extend/plugins/post-lists-view-custom/" target="_blank">Post Lists View Custom</a>:
								<span class="description"><?php _e( 'Customize the list of the post and page. custom post type page, too. You can customize the column display items freely.' , $APSC->ltd ); ?></span>
							</li>
							<li>
								<span class="dashicons dashicons-admin-plugins"></span>
								<a href="https://wordpress.org/plugins/announce-from-the-dashboard/" target="_blank">Announce From the Dashboard</a>:
								<span class="description"><?php _e( 'You will can put your message on the Dashboard, message is show per user roles group.' , $APSC->ltd ); ?></span>
							</li>
						</ul>
						
						<p>&nbsp;</p>
						
					</div>

				</div>
			
			</div>
		
		</div>
	
	</div>

</div>
<style>
#<?php echo $APSC->main_slug; ?>-plugin-information {
    margin-top: 50px;
}
#<?php echo $APSC->main_slug; ?>-plugin-information .postbox .hndle {
    cursor: pointer;
}
#<?php echo $APSC->main_slug; ?>-plugin-information .author-image {
    float: right;
    width: 200px;
    text-align: right;
}
#<?php echo $APSC->main_slug; ?>-plugin-information .author-image .gravatar {
    -webkit-transition: all 0.2s linear;
    transition: all 0.2s linear;
    border-radius: 10%;
    background: url(<?php echo $APSC->Env->schema; ?>www.gravatar.com/avatar/7e05137c5a859aa987a809190b979ed4?s=72) no-repeat right top;
    width: 72px;
    height: 72px;
    margin-left: auto;
    display: block;
}
#<?php echo $APSC->main_slug; ?>-plugin-information .author-image .gravatar:hover {
    box-shadow: inset 0 0 0 7px rgba(0,0,0,0.5), 0 1px 2px rgba(0,0,0,0.1);
}
</style>
<script>
jQuery(document).ready( function($) {

	$('#<?php echo $APSC->ltd; ?>-plugin-information .handlediv, #<?php echo $APSC->ltd; ?>-plugin-information .hndle').on('click', function( ev ) {
		
		$(this).parent().toggleClass('closed');
		
	});

});
</script>
