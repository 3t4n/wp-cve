<?php
defined( 'ABSPATH' ) || die( 'No Direct Access Sir!' );
use WPAdminify\Inc\Utils;

$adminify_video_type = $this->options['jltwp_adminify_login_bg_video_type'];

if ( $adminify_video_type == 'youtube' ) {
	$adminify_source = $this->options['jltwp_adminify_login_bg_video_youtube'];
} else {
	$adminify_source = $this->options['jltwp_adminify_login_bg_video_self_hosted']['url'];
}

if ( empty( $adminify_source ) ) {
	return;
}

if ( $adminify_video_type ) {
	$adminify_video_autoloop = $this->options['jltwp_adminify_login_bg_video_loop'];
	$adminify_video_poster   = '';

	if ( ! empty( $this->options['jltwp_adminify_login_bg_video_poster'] ) && ! empty( $this->options['jltwp_adminify_login_bg_video_poster']['url'] ) ) {
		$this->options['jltwp_adminify_login_bg_video_poster']['url'];
	}

	ob_start(); ?>
	<script type='text/javascript' src='<?php echo esc_js( WP_ADMINIFY_ASSETS ) . 'vendors/vidim/vidim.min.js'; ?>?ver=<?php echo esc_html( WP_ADMINIFY_VER ); ?>'></script>
	<script>
		<?php
		switch ( $adminify_video_type ) {
			case 'youtube':
				?>
				var src = '<?php echo esc_url( $adminify_source ); ?>';
				new vidim('.login-background', {
					src: src,
					type: 'YouTube',
					quality: 'hd1080',
					muted: true,
					startAt: 0,
					poster: '<?php echo esc_js( $adminify_video_poster ); ?>',
					loop: '<?php echo esc_js( $adminify_video_autoloop ); ?>',
					showPosterBeforePlay: '<?php echo esc_js( ! empty( $adminify_video_poster ) ); ?>'
				});
				<?php
				break;

			case 'self_hosted':
				?>
			   new vidim('.login-background', {
					src: [{
						type: 'video/mp4',
						src: '<?php echo esc_js( $adminify_source ); ?>',
					}],
					poster: '<?php echo esc_js( $adminify_video_poster ); ?>',
					loop: '<?php echo esc_js( $adminify_video_autoloop ); ?>',
					showPosterBeforePlay: '<?php echo esc_js( ! empty( $adminify_video_poster ) ); ?>'
				});
				<?php
				break;

			default:
				break;
		}
		?>
	</script>
	<?php

	$output = ob_get_clean();
	echo Utils::wp_kses_custom( $output );
}
