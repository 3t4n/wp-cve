<?php

if ( !defined( 'ABSPATH' ) ) {

	exit; // Exit if accessed directly

}

?>

<div class="wrap">

	<div id="<?php echo esc_attr( $Afd->ltd ); ?>-plugin-information" class="metabox-holder">

		<div class="meta-box-sortables">

			<div class="postbox">

				<div class="handlediv" title="<?php esc_attr_e( 'Click to toggle' ); ?>"><br /></div>
				<h3 class="hndle"><span><?php echo esc_html( $Afd->name ); ?></span></h3>

				<div class="inside">

					<div id="abount-box">

						<p class="author-image">

							<a href="<?php echo esc_url( $Afd->Helper->get_author_link( array( 'tp' => 'use_plugin' , 'lc' => 'side' ) ) ); ?>" target="_blank">
								<span class="gravatar"></span>
								gqevu6bsiz
							</a>

						</p>

						<h4><?php _e( 'About plugin' , $Afd->ltd ); ?></h4>

						<p>
							<?php _e( 'Version checked' , $Afd->ltd ); ?>:
							<code><?php echo esc_html( $Afd->Helper->get_plugin_version_checked() ); ?></code>
						</p>

						<ul>
							<li><span class="dashicons dashicons-admin-plugins"></span> <a href="https://wordpress.org/plugins/announce-from-the-dashboard/" target="_blank"><?php echo esc_html( $Afd->name ); ?></a></li>
							<li><span class="dashicons dashicons-format-chat"></span> <a href="<?php echo esc_url( $Afd->Links->forum ); ?>" target="_blank"><?php _e( 'Support Forums' ); ?></a></li>
							<li><span class="dashicons dashicons-star-half"></span> <a href="<?php echo esc_url( $Afd->Links->review ); ?>" target="_blank"><?php _e( 'Reviews' , $Afd->ltd ); ?></a></li>
						</ul>

						<ul>
							<li><span class="dashicons dashicons-smiley"></span><a href="<?php echo esc_url( $Afd->Helper->get_author_link( array( 'tp' => 'use_plugin' , 'lc' => 'footer' ) ) ); ?>" target="_blank"><?php _e( 'Developer\'s site' , $Afd->ltd ); ?></a></li>
							<li><span class="dashicons dashicons-twitter"></span> <a href="https://twitter.com/gqevu6bsiz" target="_blank">twitter</a></li>
						</ul>

						<p>&nbsp;</p>

						<h4><?php _e( 'Useful plugins' , $Afd->ltd ); ?></h4>

						<ul>
							<li>
								<span class="dashicons dashicons-admin-plugins"></span>
								<a href="https://wordpress.org/plugins/my-wp/" target="_blank">My WP Customize Admin/Frontend</a>:
								<span class="description"><?php _e( 'Simply and easy-to-use the customize for Admin and Frontend.' , $Afd->ltd ); ?></span>
							</li>
							<li>
								<span class="dashicons dashicons-admin-plugins"></span>
								<a href="https://mywpcustomize.com/add_ons/add-on-announce/" target="_blank">My WP Add-on Announce</a>:
								<span class="description"><?php _e( 'Announcement for users on admin Dashboard.' , $Afd->ltd ); ?></span>
							</li>
						</ul>

						<p>&nbsp;</p>

						<p><span class="dashicons dashicons-admin-plugins"></span> <a href="<?php echo esc_url( $Afd->Links->profile ); ?>" target="_blank"><?php _e( 'Plugins' ); ?></a></p>

					</div>

				</div>

			</div>

		</div>

	</div>

</div>
<style>
#afd-plugin-information {
    margin-top: 50px;
}
#afd-plugin-information .postbox .hndle {
    cursor: default;
}
#afd-plugin-information .author-image {
    float: right;
    width: 200px;
    text-align: right;
}
#afd-plugin-information .author-image .gravatar {
    -webkit-transition: all 0.2s linear;
    transition: all 0.2s linear;
    border-radius: 10%;
    background: url(<?php echo esc_attr( $Afd->Env->schema ); ?>www.gravatar.com/avatar/7e05137c5a859aa987a809190b979ed4?s=72) no-repeat right top;
    width: 72px;
    height: 72px;
    margin-left: auto;
    display: block;
}
#afd-plugin-information .author-image .gravatar:hover {
    box-shadow: inset 0 0 0 7px rgba(0,0,0,0.5), 0 1px 2px rgba(0,0,0,0.1);
}
</style>
<script>
jQuery(document).ready( function($) {

	$('#afd-plugin-information .handlediv').on('click', function( ev ) {

		$(this).parent().toggleClass('closed');

	});

});
</script>
