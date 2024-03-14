<div class="hpf-admin-settings-body wrap">

	<div class="hpf-admin-settings-pro">

		<?php include( dirname(HOUZEZ_PROPERTY_FEED_PLUGIN_FILE) . '/includes/views/admin-settings-notice.php' ); ?>

		<h1><?php echo __( 'Upgrade to PRO', 'houzezpropertyfeed' ); ?></h1>

		<p>Unlock more functionality by upgrading to <a href="https://houzezpropertyfeed.com/#pricing" target="_blank">Houzez Property Feed PRO</a>. Some of the added benefits of using PRO can be seen below:</p>

		<div class="features">

			<?php
				foreach ( $features as $feature )
				{
			?>
			<div class="feature">

				<div class="inner">

					<h3><span class="<?php echo esc_attr($feature['icon']); ?>"></span> <?php echo esc_html($feature['title']); ?></h3>

					<p><?php echo esc_html($feature['description']); ?></p>

				</div>

			</div>
			<?php
				}
			?>

		</div>

		<div class="cta"><a href="https://houzezpropertyfeed.com/#pricing" target="_blank" class="button button-primary button-hero"><?php echo __( 'Upgrade to PRO', 'houzezpropertyfeed' ); ?></a></div>

	</div>

</div>