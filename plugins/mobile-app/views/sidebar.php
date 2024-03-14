<div class="canvas-sidebar">

	<?php if ( CanvasAdmin::welcome_screen_is_avalaible() ) : ?>
		<?php $demo_url = 'https://www.mobiloud.com/demo/?utm_source=' . CanvasAdmin::$utm_source . '&utm_medium=sidebar'; ?>
		<div class="canvas-side-block ml-open-welcome">
			<a href="<?php echo esc_attr( $demo_url ); ?>"
				class="button button-hero button-primary canvas-fullwidth">
				Request a demo
			</a>
		</div>
	<?php endif; ?>

	<div class="canvas-side-block">
		<div class="canvas-side-header">Help & Support</div>
		<div class="canvas-side-body">
			<p>Make sure to check our Help Center for more details on how to build your app.</p>
			<p><a href="https://www.mobiloud.com/help/article-categories/canvas/" target="_blank">MobiLoud Help Center</a></p>
			<p><a href="mailto:support@mobiloud.com">Send us an email</a></p>
		</div>
	</div>

	<div class="canvas-side-block">
		<div class="canvas-side-header">Like our plugin?</div>
		<div class="canvas-side-body">
			<p>If you enjoy our service, don't forget to rate it with 5 stars on WordPress.org!</p>
			<p><a href="https://wordpress.org/support/plugin/mobile-app/reviews/#new-post" target="_blank">Rate this plugin</a></p>
		</div>
	</div>
</div>
