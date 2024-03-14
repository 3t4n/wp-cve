
<div class="wptd-settings-wrap">
	<div class="wptd-header-bar">
		<div class="wptd-header-left">
			<div class="wptd-admin-logo-inline">
			<a href="https://wpthemedevelopers.com/wptd-video-popup/" target="_blank"><img src="<?php echo esc_url( WPTD_EVP_URL . 'assets/images/logo.png' ); ?>" alt="wptd-logo"></a>
			</div><!-- .wptd-admin-logo-inline -->
			<h2 class="title"><?php esc_html_e( 'WPTD Video Popup', 'wptd-video-popup' ); ?><span class="wptd-version"><?php echo esc_attr( WPTD_Elementor_Video_Popup::$version ); ?></span></h2>
		</div><!-- .wptd-header-left -->
		<div class="wptd-header-right">
			<a href="https://wpthemedevelopers.com/wptd-video-popup/" target="_blank" class="button wptd-btn"><?php esc_html_e( 'Live Demo', 'wptd-video-popup' ); ?></a>
		</div><!-- .wptd-header-right -->
	</div><!-- .wptd-header-bar -->

</div>
<div class="wrap">
	<div class="admin-box">
		<h3><?php esc_html_e( 'Video Popup Elementor Shortcode', 'wptd-video-popup' ); ?></h3>
		<div class="wptd-shortcode">
			<ul class="wptd-shortcode-inner">
				<li><?php esc_html_e( 'Go to page with Elementor Editor', 'wptd-video-popup' ); ?></li>
				<li><?php esc_html_e( 'Search "video popup" this keyword on left side shortcodes search box', 'wptd-video-popup' ); ?></li>
				<li><?php esc_html_e( 'You can see shortcode like "Video Popup WPTD"', 'wptd-video-popup' ); ?></li>
				<li><?php esc_html_e( 'Use that shortcode to show video popup on your page', 'wptd-video-popup' ); ?></li>
				<li><?php esc_html_e( 'You can style background overlay, video width and height also', 'wptd-video-popup' ); ?></li>
			</ul>
		</div>
	</div>
	<div class="admin-box">
		<h3><?php esc_html_e( 'Video Popup Normal Shortcode & Options', 'wptd-video-popup' ); ?></h3>		
		<div class="wptd-shortcode"><span class="wptd-shortcode-inner">[wptd_video_popup url="https://www.youtube.com/watch?v=LXb3EKWsInQ"]</span></div>
		<table class="wptd-table">		
			<thead>
				<tr>
					<th><?php esc_html_e( 'Option', 'wptd-video-popup' ); ?></th>
					<th><?php esc_html_e( 'Description', 'wptd-video-popup' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><?php esc_html_e( 'url', 'wptd-video-popup' ); ?></td>
					<td><?php esc_html_e( 'Enter here URL of youtube or vimeo video. Example: https://www.youtube.com/watch?v=LXb3EKWsInQ', 'wptd-video-popup' ); ?></td>
				</tr>
				<tr>
					<td><?php esc_html_e( 'width', 'wptd-video-popup' ); ?></td>
					<td><?php esc_html_e( 'Enter width of the video frame. Example: 900', 'wptd-video-popup' ); ?></td>
				</tr>
				<tr>
					<td><?php esc_html_e( 'trigger', 'wptd-video-popup' ); ?></td>
					<td><?php esc_html_e( 'Enter trigger type(text/icon/img). Default type is text. Example: "text"', 'wptd-video-popup' ); ?></td>
				</tr>
				<tr>
					<td><?php esc_html_e( 'text', 'wptd-video-popup' ); ?></td>
					<td><?php esc_html_e( 'Enter trigger text. Example: "Click"', 'wptd-video-popup' ); ?></td>
				</tr>
				<tr>
					<td><?php esc_html_e( 'icon', 'wptd-video-popup' ); ?></td>
					<td><?php esc_html_e( 'Enter trigger icon class. There is no fonts available with our plugin. Example: "fa fa-play"', 'wptd-video-popup' ); ?></td>
				</tr>
				<tr>
					<td><?php esc_html_e( 'img', 'wptd-video-popup' ); ?></td>
					<td><?php esc_html_e( 'Enter trigger image url.', 'wptd-video-popup' ); ?></td>
				</tr>
				<tr>
					<td><?php esc_html_e( 'bg_color', 'wptd-video-popup' ); ?></td>
					<td><?php esc_html_e( 'Enter background video color. Example rgba(0,0,0,0.5)', 'wptd-video-popup' ); ?></td>
				</tr>
				<tr>
					<td><?php esc_html_e( 'extra_class', 'wptd-video-popup' ); ?></td>
					<td><?php esc_html_e( 'You can give css class name with this attribute.', 'wptd-video-popup' ); ?></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>

