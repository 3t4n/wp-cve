<?php

function cps_plugins_page() {
?>
<div class="cps-admin cps-plugins-page">
	<?php cps_admin_header(); ?>
	<div class="wrap">
		<h2 class="uk-text-center uk-margin-medium-top" style="font-size: 30px;"><strong>Check out other great plugins from CherryPick Studios & Surbma!</strong></h2>
		<p class="uk-text-center uk-margin-remove-top uk-margin-large-bottom">If you like CherryPick Studios & Surbma plugins, take a look at our other plugins! <br>We are sure, you will find useful solutions for your website.</p>
		<div class="uk-child-width-1-2@s uk-child-width-1-3@m uk-child-width-1-3@l" uk-grid="masonry: false;" uk-height-match="target: > div > .uk-card > .uk-card-body">
		<?php
			$response = wp_remote_get( 'https://www.cherrypickstudios.com/cps-plugins.json' );
			if( is_wp_error( $response ) ) return false;
			$json = wp_remote_retrieve_body( $response );
			$plugins = json_decode( $json, true );

			foreach ( $plugins as $plugin ) {
				$title = isset( $plugin['title'] ) && $plugin['title'] != '' ? $plugin['title'] : '';
				$img = isset( $plugin['img'] ) && $plugin['img'] != '' ? $plugin['img'] : false;
				$badge = isset( $plugin['badge'] ) && $plugin['badge'] != '' ? $plugin['badge'] : false;
				$description = isset( $plugin['description'] ) && $plugin['description'] != '' ? $plugin['description'] : false;
				$alert = isset( $plugin['alert'] ) && $plugin['alert'] != '' ? $plugin['alert'] : false;
				$url = isset( $plugin['url'] ) && $plugin['url'] != '' ? $plugin['url'] : false;
				$button = isset( $plugin['button'] ) && $plugin['button'] != '' ? $plugin['button'] : 'Visit Plugin Page';

				if( $title != '' ) { ?>
				<div>
					<div class="uk-card uk-card-default uk-card-hover">
						<div class="uk-card-media-top uk-hidden" style="overflow: hidden;max-height: 200px;">
							<?php if( $img != false ) { ?>
							<img src="<?php echo $img; ?>" alt="<?php echo $title; ?>">
							<?php } else { ?>
							<img src="<?php echo CPS_URL; ?>/images/cps-logo.svg" alt="<?php echo $title; ?>" style="width: 200px;display: block;margin: 0 auto;padding: 50px;">
							<?php } ?>
						</div>
						<div class="uk-card-body">
							<?php if( $badge != false ) { ?>
							<div class="uk-card-badge uk-label" style="padding: 5px 10px;top: -10px;"><?php echo $badge; ?></div>
							<?php } ?>
							<h3 class="uk-card-title"><strong><?php echo $title; ?></strong></h3>
							<?php if( $description != false ) echo $description; ?>
							<?php if( $alert != false ) { ?>
							<div class="uk-alert-primary uk-margin-top" uk-alert>
								<?php echo $alert; ?>
							</div>
							<?php } ?>
							<a id="purchase" class="uk-button uk-button-primary uk-width-1-1 uk-hidden" href="<?php echo $url; ?>" target="_blank"><?php _e( $button, 'cps-sdk' ); ?></a>
						</div>
						<?php if( $url != false ) { ?>
						<div class="uk-card-footer uk-background-muted">
							<a id="purchase" class="uk-button uk-button-primary uk-width-1-1" href="<?php echo $url; ?>" target="_blank"><?php _e( $button, 'cps-sdk' ); ?></a>
						</div>
						<?php } ?>
					</div>
				</div>
				<?php }
			}
		?>
		</div>
		<div class="uk-margin-large-bottom" id="bottom"></div>
		<p class="uk-text-center">CherryPick Studios is created by Surbma <br>Eventually all Surbma branded plugins will be renamed with our new brand: CherryPick Studios</p>
	</div>
	<?php cps_admin_footer(); ?>
</div>
<?php
}
