<?php
/**
 * Outputs Settings View for a Post Type
 *
 * @since    3.0.0
 *
 * @package WP_To_Social_Pro
 * @author  WP Zinc
 */

?>

<!-- Post Type -->
<div class="postbox wpzinc-vertical-tabbed-ui">

	<!-- Profile Tabs -->
	<ul class="wpzinc-nav-tabs wpzinc-js-tabs" data-panels-container="#profiles-container" data-panel=".profile" data-active="wpzinc-nav-tab-vertical-active">
		<!-- Default Settings -->
		<li class="wpzinc-nav-tab default">
			<a href="#profile-default" class="wpzinc-nav-tab-vertical-active">
				<?php esc_html_e( 'Defaults', 'wp-to-hootsuite' ); ?>
			</a>
		</li>

		<?php
		// Account tabs.
		if ( ! is_wp_error( $profiles ) ) {
			foreach ( $profiles as $key => $profile ) {
				$profile_enabled = $this->get_setting( $post_type, '[' . $profile['id'] . '][enabled]', 0 );
				?>
				<li class="wpzinc-nav-tab <?php echo esc_attr( $profile['service'] ); ?>">
					<a href="#profile-<?php echo esc_attr( $profile['id'] ); ?>"<?php echo ( $profile_enabled ? ' class="enabled"' : '' ); ?> title="<?php echo esc_attr( $profile['formatted_service'] . ': ' . $profile['formatted_username'] ); ?>">
						<?php
						echo esc_html( $profile['formatted_username'] );
						?>
						<span class="dashicons dashicons-yes"></span>
					</a>
				</li>
				<?php

			}
		}
		unset( $profile );
		?>
	</ul>

	<!-- Content -->
	<div id="profiles-container" class="wpzinc-nav-tabs-content no-padding">
		<!-- Defaults -->
		<?php
		$profile_id = 'default';
		?>
		<div id="profile-<?php echo esc_attr( $profile_id ); ?>" class="profile">
			<!-- Action Tabs -->
			<ul class="wpzinc-nav-tabs-horizontal wpzinc-js-tabs" data-panels-container="#profile-<?php echo esc_attr( $profile_id ); ?>-actions-container" data-panel=".action" data-active="wpzinc-nav-tab-horizontal-active">
				<?php
				foreach ( $post_actions as $post_action => $action_label ) {
					$action_enabled = $this->get_setting( $post_type, '[' . $profile_id . '][' . $post_action . '][enabled]', 0 );
					?>
					<li class="wpzinc-nav-tab-horizontal <?php echo esc_attr( $post_action ); ?>">
						<a href="#profile-<?php echo esc_attr( $profile_id ); ?>-<?php echo esc_attr( $post_action ); ?>" class="<?php echo esc_attr( $action_enabled ? ' enabled' : '' ) . ( $post_action === 'publish' ? ' wpzinc-nav-tab-horizontal-active' : '' ); ?>">
							<?php
							echo esc_html( $action_label );
							?>
							<span class="dashicons dashicons-yes"></span>
						</a>
					</li>
					<?php
				}
				?>
			</ul>

			<div id="profile-<?php echo esc_attr( $profile_id ); ?>-actions-container">
				<?php
				// Iterate through Post Actions (Publish, Update etc).
				foreach ( $post_actions as $post_action => $action_label ) {
					?>
					<div id="profile-<?php echo esc_attr( $profile_id ); ?>-<?php echo esc_attr( $post_action ); ?>" class="action">
						<?php
						require $this->base->plugin->folder . 'lib/views/settings-post-action.php';
						?>
					</div>
					<?php
				}
				?>
			</div>
		</div>
		<!-- /Defaults -->

		<!-- Profiles -->
		<?php
		if ( is_wp_error( $profiles ) ) {
			?>
			<div>
				<?php esc_html_e( 'Hmm, we couldn\'t fetch your social media profiles.  Please refresh the Page.', 'wp-to-hootsuite' ); ?>
			</div>
			<?php
		} else {
			foreach ( $profiles as $key => $profile ) {
				$profile_id = $profile['id'];
				?>
				<div id="profile-<?php echo esc_attr( $profile_id ); ?>" class="profile <?php echo esc_attr( $profile['service'] ); ?>">
					<?php
					require $this->base->plugin->folder . 'lib/views/settings-post-actionheader.php';
					?>
				</div>
				<?php
			}
		}
		?>
		<!-- /Profiles -->

		<!-- Status Editor -->
		<?php
		require $this->base->plugin->folder . 'lib/views/settings-post-action-status.php';
		?>

		<!-- Submitted Form Data -->
		<input type="hidden" name="<?php echo esc_attr( $this->base->plugin->name ); ?>[statuses]" value='<?php echo wp_json_encode( $original_statuses, JSON_HEX_APOS ); ?>' />
	</div>
</div>
<!-- /post_type -->
