<!-- Push keys -->
<div class="cas--settings cas-settings--wide canvas-block">
	<div class="cas--settings__title">Push Keys</div>
	<div class="cas--settings__content">

		<div class="cas--settings__layout-row">
			<div class="cas--settings__layout-row-item">
				<label for="">
					<span class="cas--label__text"><?php esc_html_e( 'Push App ID', 'canvas' ); ?></span>
				</label>
				<p class="cas--description"><?php esc_html_e( 'App ID provided by your Push Service', 'canvas' ); ?></p>
			</div>
			<div class="cas--settings__layout-row-item">
				<input name="canvas_push_app_id" type="text" id="canvas_push_app_id" value="<?php echo esc_attr( Canvas::get_option( 'push_app_id', '' ) ); ?>">
			</div>
		</div>

		<div class="cas--settings__layout-row">
			<div class="cas--settings__layout-row-item">
				<label for="">
					<span class="cas--label__text"><?php esc_html_e( 'Secret Key', 'canvas' ); ?></span>
				</label>
				<p class="cas--description"><?php esc_html_e( 'Secret Key provided by your Push Service', 'canvas' ); ?></p>
			</div>
			<div class="cas--settings__layout-row-item">
				<input name="canvas_push_key" type="text" id="canvas_push_key" value="<?php echo esc_attr( Canvas::get_option( 'push_key', '' ) ); ?>">
			</div>
		</div>

		<div class="cas--settings__layout-row-item">
			<p>Can't find your keys? <a href='mailto:support@mobiloud.com?subject=Push%20keys'>Request your keys</a> from our support team.</p>
		</div>

		<div class="clearfix"></div>
	</div>
</div>

<!-- Notifications history -->
<div class="cas--settings cas-settings--wide canvas-block">
	<div class="cas--settings__title">Notifications History</div>
	<div class="cas--settings__content">

		<div class="cas--settings__layout-row">
			<div class="cas--settings__layout-row-item">
				<label for="">
					<span class="cas--label__text"><?php esc_html_e( 'Clear list of notifications', 'canvas' ); ?></span>
				</label>
				<p class="cas--description"><?php esc_html_e( 'Clean the whole messages history for all users at once.', 'canvas' ); ?></p>
			</div>
			<div class="cas--settings__layout-row-item">
				<a class="button button-large" id="canvas_push_clean_history" href="#">Clean history</a>
				<?php
					wp_nonce_field( 'canvas-clean-history', 'canvas-clean-history-nonce' );
				?>
			</div>
		</div>


		<div class="clearfix"></div>
	</div>
</div>

<!-- Clean Notifications Log -->
<div class="cas--settings cas-settings--wide canvas-block">
	<div class="cas--settings__title">Notifications Log</div>
	<div class="cas--settings__content">
		<div class="cas--settings__layout-row">
			<div class="cas--settings__layout-row-item">
				<label for="">
					<span class="cas--label__text"><?php esc_html_e( 'Clear log File', 'canvas' ); ?></span>
				</label>
				<p class="cas--description"><?php esc_html_e( 'Clear the whole logs at once.', 'canvas' ); ?></p>
			</div>
			<div class="cas--settings__layout-row-item">
				<a class="button button-large" id="canvas_push_clean_log" href="#">Clean Log</a>
				<?php
					wp_nonce_field( 'canvas-clean-log', 'canvas-clean-log-nonce' );
				?>
			</div>
		</div>
		<div class="clearfix"></div>
	</div>
</div>

<!-- BuddyPress Integration -->
<?php
$bp_active = is_plugin_active( 'buddypress/bp-loader.php' ) || class_exists( 'BuddyPress' );
?>
<div class="cas--settings cas-settings--wide canvas-block">
	<div class="cas--settings__title">BuddyPress Integration</div>
	<div class="cas--settings__content">
		<?php
		if ( ! $bp_active ) {
			?>
			<div style="margin-bottom: 24px;">To enable BuddyPress notifications please make sure to install BuddyPress</div>
			<?php
		}
		$bp_options = array(
			'bp_private_messages'       => array(
				'h4'    => 'Private Messages',
				'p'     => 'Send notifications to users whenever a private message is received',
				'label' => 'Enable notifications for private messages',
				'class' => 'canvas-other-options-checkbox',
			),
			'bp_private_messages_title' => array(
				'h4'         => 'Private message notification title',
				'p'          => 'Title of push notifications that will be sent to users when they receive a private message. You can use the following variables: %sender%, %receiver%',
				// 'label' => '',
				'text-input' => true,
				'wrap-class' => 'canvas-other-options',
			),
			'bp_global_messages'        => array(
				'h4'    => 'Global Messages',
				'p'     => 'Send notifications to users when a global message is received',
				'label' => 'Enable notifications for global messages',
			),
			'bp_friends'                => array(
				'h4'    => 'Friend Requests',
				'p'     => 'Send notifications to users when a friend request is received',
				'label' => 'Enable notifications for friend requests',
			),
			'bp_other_notitications'    => array(
				'h4'    => 'Other notifications',
				'p'     => 'Send notifications for activities from BP modules, comment replies and activity updates',
				'label' => 'Enable other notifications',
			),
		);
		foreach ( $bp_options as $key => $values ) {
			$name = "canvas_$key";
			?>

			<div class="canvas-line
			<?php
			if ( ! $bp_active ) {
				echo ' disabled';
			}
			?>
			<?php
			if ( isset( $values['wrap-class'] ) ) {
				echo " {$values[ 'wrap-class' ]}";
			}
			?>
">

				<div class="cas--settings__layout-row">
					<div class="cas--settings__layout-row-item">
						<label for="">
							<span class="cas--label__text"><?php echo $values['h4']; ?></span>
						</label>
						<p class="cas--description"><?php echo $values['p']; ?></p>
					</div>

					<div class="cas--settings__layout-row-item">
						<?php if ( isset( $values['text-input'] ) ) : ?>
							<input name="<?php echo $name; ?>" type="text" maxlength="1000" id="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( Canvas::get_option( $key ) ); ?>">
						<?php else : ?>
							<input name="<?php echo $name; ?>" type="checkbox" id="<?php echo esc_attr( $name ); ?>" value="1"<?php disabled( ! $bp_active ); ?><?php checked( Canvas::get_option( $key ) ); ?>
								<?php
								if ( isset( $values['class'] ) ) {
									echo " class=\"{$values[ 'class' ]}\"";
								}
								?>
								>
							<label for="<?php echo esc_attr( $name ); ?>"><?php echo esc_html( $values['label'] ); ?></label>
							<?php
						endif;
						?>
					</div>
				</div>
			</div>
			<?php
		}
		?>
	</div>
</div>

<?php
$bb_active = is_plugin_active( 'bbpress/bbpress.php' ) || class_exists( 'bbPress' );
?>
<div class="cas--settings cas-settings--wide canvas-block">
	<div class="cas--settings__title">bbPress Integration</div>
	<div class="cas--settings__content">
		<?php
		if ( ! $bb_active ) {
			?>
			<div style="margin-bottom: 24px;">To enable bbPress notifications please make sure to install bbPress</div>
			<?php
		}
		$bb_press_options = array(
			'bb_comment' => array(
				'h4'    => 'All comment',
				'p'     => 'Send notifications to subscribed users whenever a comment is made',
				'label' => 'Enable notifications for comment',
			),
			'bb_reply' => array(
				'h4'    => 'All reply',
				'p'     => 'Send notifications to subscribed users whenever a reply is made',
				'label' => 'Enable notifications for reply',
			),
		);
		foreach ( $bb_press_options as $key => $values ) {
			$name = "canvas_$key";
			?>

			<div class="canvas-line
			<?php
				echo ! $bb_active ? ' disabled' : '';
				echo isset( $values['wrap-class'] ) ? " {$values[ 'wrap-class' ]}" : '';
			?>
			">
				<div class="cas--settings__layout-row">
					<div class="cas--settings__layout-row-item">
						<label for="">
							<span class="cas--label__text"><?php echo $values['h4']; ?></span>
						</label>
						<p class="cas--description"><?php echo $values['p']; ?></p>
					</div>
					<div class="cas--settings__layout-row-item">
						<?php
						if ( isset( $values['text-input'] ) ) :
							?>
							<input name="<?php echo $name; ?>" type="text" maxlength="1000" id="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( Canvas::get_option( $key ) ); ?>">
							<?php
						else :
							?>
							<input name="<?php echo $name; ?>" type="checkbox" id="<?php echo esc_attr( $name ); ?>" value="1"<?php disabled( ! $bb_active ); ?><?php checked( Canvas::get_option( $key ) ); ?>
								<?php
								if ( isset( $values['class'] ) ) {
									echo " class=\"{$values[ 'class' ]}\"";
								}
								?>
							>
							<label for="<?php echo esc_attr( $name ); ?>"><?php echo esc_html( $values['label'] ); ?></label>
							<?php
						endif;
						?>
					</div>
				</div>
			</div>
			<?php
		}
		?>
	</div>
</div>
<?php
$peepso_core_active    = is_plugin_active( 'peepso-core/peepso.php' ) || class_exists( 'PeepSo' );
$peepso_friend_active  = is_plugin_active( 'peepso-friends/peepsofriends.php' ) || class_exists( 'PeepSoFriendsPlugin' );
$peepso_message_active = is_plugin_active( 'peepso-messages/peepsomessages.php' ) || class_exists( 'PeepSoMessagesPlugin' );
?>
<div class="cas--settings cas-settings--wide canvas-block">
	<div class="cas--settings__title">PeepSo Integration</div>
	<div class="cas--settings__content">
		<?php
		if ( ! $peepso_core_active || ! $peepso_friend_active || ! $peepso_message_active ) {
			?>
			<div style="margin-bottom: 24px;">To enable PeepSo notifications please make sure to install PeepSo and its related extensions</div>
			<?php
		}
		$ps_options = array(
			'ps_mentions_comments' => array(
				'h4'    => 'Mentions in comment',
				'p'     => 'Send notifications to users when they are mentioned in a comment',
				'label' => 'Enable notifications for mentions in comment',
			),
			'ps_mentions_posts'    => array(
				'h4'    => 'Mentions in post',
				'p'     => 'Send notifications to users when they are mentioned in a post',
				'label' => 'Enable notifications for mentions in post',
			),
			'ps_private_messages'  => array(
				'h4'    => 'Private Messages',
				'p'     => 'Send notifications to users whenever a private message is received',
				'label' => 'Enable notifications for private messages',
				'class' => 'canvas-other-options-checkbox',
			),
			'ps_friends'           => array(
				'h4'    => 'Friend Requests',
				'p'     => 'Send notifications to users when a friend request is received',
				'label' => 'Enable notifications for friend requests',
			),
		);
		foreach ( $ps_options as $key => $values ) {
			$input_custom_attr = '';
			$name              = "canvas_$key";
			$class             = 'canvas-line';
			switch ( $key ) {
				case 'ps_private_messages':
					if ( ! $peepso_core_active || ! $peepso_message_active ) {
						$class            .= ' disabled';
						$input_custom_attr = ' disabled="disabled"';
					}
					break;
				case 'ps_friends':
					if ( ! $peepso_core_active || ! $peepso_friend_active ) {
						$class            .= ' disabled';
						$input_custom_attr = ' disabled="disabled"';
					}
					break;
				case 'ps_mentions_comments':
				case 'ps_mentions_posts':
					if ( ! $peepso_core_active ) {
						$class            .= ' disabled';
						$input_custom_attr = ' disabled="disabled"';
					}
					break;
			}
			$class             .= isset( $values['wrap-class'] ) ? " {$values[ 'wrap-class' ]}" : '';
			$input_custom_attr .= Canvas::get_option( $key ) ? ' checked="checked"' : '';
			$input_custom_attr .= isset( $values['class'] ) ? ' class="' . $values['class'] . '"' : '';
			?>
			<div class="<?php echo $class; ?>">
				<div class="cas--settings__layout-row">
					<div class="cas--settings__layout-row-item">
						<label for="">
							<span class="cas--label__text"><?php echo $values['h4']; ?></span>
						</label>
						<p class="cas--description"><?php echo $values['p']; ?></p>
					</div>
					<div class="cas--settings__layout-row-item">
						<input name="<?php echo $name; ?>" type="checkbox" id="<?php echo $name; ?>"
								value="1"<?php echo $input_custom_attr; ?>>
						<label for="<?php echo $name; ?>"><?php echo $values['label']; ?></label>
					</div>
				</div>
			</div>
			<?php
		}
		?>
	</div>
</div>

<?php
$ld_active = is_plugin_active( 'sfwd-lms/sfwd_lms.php' ) || class_exists( 'SFWD_LMS' );
?>
<div class="cas--settings cas-settings--wide canvas-block">
	<div class="cas--settings__title">LearnDash Integration</div>
	<div class="cas--settings__content">
		<?php
		if ( ! $ld_active ) {
			?>
			<p>To enable LearnDash notifications please make sure to install LearnDash</p>
			<?php
		}
		$ld_options = array(
			'ld_approved_assignments'   => array(
				'h4'    => 'Approved Assignments',
				'p'     => 'Send notifications to assignment author whenever an assignment is approved',
				'label' => 'Enable notifications for approved assignments',
				'class' => '',
			),
			'ld_new_assignment_comment' => array(
				'h4'    => 'New Assignment Comment',
				'p'     => 'Send notifications to assignment author whenever an assignment get a comment',
				'label' => 'Enable notifications for new comments',
			),
		);
		foreach ( $ld_options as $key => $values ) {
			$name = "canvas_$key";
			?>

			<div class="canvas-line
			<?php
			if ( ! $ld_active ) {
				echo ' disabled';
			}
			?>
			<?php
			if ( isset( $values['wrap-class'] ) ) {
				echo " {$values[ 'wrap-class' ]}";
			}
			?>
">

				<div class="cas--settings__layout-row">
					<div class="cas--settings__layout-row-item">
						<label for="">
							<span class="cas--label__text"><?php echo $values['h4']; ?></span>
						</label>
						<p class="cas--description"><?php echo $values['p']; ?></p>
					</div>
					<div class="cas--settings__layout-row-item">
						<?php
						if ( isset( $values['text-input'] ) ) :
							?>
							<input name="<?php echo $name; ?>" type="text" maxlength="1000" id="<?php echo $name; ?>" value="<?php echo esc_attr( Canvas::get_option( $key ) ); ?>">
							<?php
						else :
							?>
							<input name="<?php echo $name; ?>" type="checkbox" id="<?php echo $name; ?>" value="1"<?php disabled( ! $ld_active ); ?><?php checked( Canvas::get_option( $key ) ); ?>
								<?php
								if ( isset( $values['class'] ) ) {
									echo " class=\"{$values[ 'class' ]}\"";
								}
								?>
								>
							<label for="<?php echo $name; ?>"><?php echo $values['label']; ?></label>
							<?php
						endif;
						?>
					</div>
				</div>
			</div>
			<?php
		}
		?>
	</div>
</div>

<?php $is_wooCommerce_active = class_exists( 'WooCommerce' ); ?>

<div class="cas--settings cas-settings--wide canvas-block">
	<div class="cas--settings__title">WooCommerce Notifications</div>
	<div class="cas--settings__content">
		<div class="canvas-line <?php echo $is_wooCommerce_active ? '' : 'disabled'; ?>">
			<div class="cas--settings__layout-row">
				<div class="cas--settings__layout-row-item">
					<label for="">
						<span class="cas--label__text"><?php esc_html_e( 'WooCommerce Email Notifications', 'canvas' ); ?></span>
					</label>
					<p class="cas--description"><?php esc_html_e( 'Select which email types should trigger a push notification.', 'canvas' ); ?></p>
				</div>
				<?php if ( ! $is_wooCommerce_active ) : ?>
					<div><?php esc_html_e( 'Activate WooCommerce to load settings.', 'canvas' ); ?></div>
				<?php else: ?>
					<div class="cas--settings__layout-row-item">
						<?php
							$mailer            = WC()->mailer();
							$email_templates   = $mailer->get_emails();
							$woo_email_options = Canvas::get_option( 'push_woo_email_type', array() );

							foreach ( $email_templates as $email_key => $email ) :
								if ( ! $email->is_customer_email() ) {
									continue;
								}
								$checked = in_array( $email->id, $woo_email_options, true ) ? 'checked' : '';
						?>
							<div class="cas--checkbox-group">
								<input
									type="checkbox"
									id='canvas_push_woo_email_types_<?php echo esc_attr( $email->id ); ?>'
									name="canvas_push_woo_email_type[]"
									value="<?php echo esc_attr( $email->id ); ?>" <?php echo $checked; ?>/>
								<label for="canvas_push_woo_email_type<?php echo esc_attr( $email->id ); ?>"><?php echo esc_html( $email->get_title() ); ?></label>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>


<div class="cas--settings__layout-row">
	<div class="cas--settings__layout-row-item">
		<label for="">
			<span class="cas--label__text"><?php esc_html_e( '', 'canvas' ); ?></span>
		</label>
		<p class="cas--description"><?php esc_html_e( '', 'canvas' ); ?></p>
	</div>
</div>


<div class="cas--settings cas-settings--wide canvas-block">
	<div class="cas--settings__title">Automatic Push Notifications</div>
	<div class="cas--settings__content">

		<div class="cas--settings__layout-row">
			<div class="cas--settings__layout-row-item">
				<label for="">
					<span class="cas--label__text"><?php esc_html_e( 'Automatic Push Notifications', 'canvas' ); ?></span>
				</label>
				<p class="cas--description"><?php esc_html_e( 'Automatically send push notifications when a new post is published', 'canvas' ); ?></p>
			</div>
			<div class="cas--settings__layout-row-item">
				<input name="canvas_push_auto_enabled" type="checkbox" id="canvas_push_auto_enabled" value="1"<?php checked( Canvas::get_option( 'push_auto_enabled' ) ); ?>>
				<label for="canvas_push_auto_enabled">Send notifications automatically</label>
			</div>
		</div>

		<div class="cas--settings__layout-row">
			<div class="cas--settings__layout-row-item">
				<label for="">
					<span class="cas--label__text"><?php esc_html_e( 'Categories for Push Notifications', 'canvas' ); ?></span>
				</label>
				<p class="cas--description"><?php esc_html_e( 'Select which categories will generate a push notification (empty for all)', 'canvas' ); ?></p>
			</div>
			<div class="cas--settings__layout-row-item">
				<select id="canvas_push_categories" name='canvas_push_categories[]'
					data-placeholder="Select Categories..." style="width:100%;" multiple class="canvas-chosen-select">
					<option></option>
					<?php
					$categories     = get_categories( array( 'hide_empty' => 0 ) );
					$pushCategories = CanvasAdmin::push_notification_taxonomies_get();

					foreach ( $categories as $c ) {
						$selected = ( in_array( $c->cat_ID, $pushCategories ) ) ? ' selected' : '';
						echo "<option value='$c->cat_ID'$selected>Category: $c->cat_name</option>";
					}

					$tax_list   = CanvasAdmin::push_notification_taxonomies_get( 'taxonomy' ); // current tax list
					$taxonomies = get_taxonomies( array( '_builtin' => false ), 'objects' );

					foreach ( $taxonomies as $tax ) {
						$terms = get_terms( $tax->query_var, array( 'hide_empty' => false ) );
						if ( ! is_wp_error( $terms ) && is_array( $terms ) && count( $terms ) ) {
							foreach ( $terms as $term ) {
								$parent_name = '';
								if ( $term->parent ) {
									$parent_term = get_term_by( 'id', $term->parent, $tax->query_var );
									if ( $parent_term ) {
										$parent_name = $parent_term->name . ' - ';
									}
								}
								$selected = in_array( $term->term_id, $tax_list ) ? ' selected="selected"' : '';
								echo "<option value='tax:{$term->term_id}'$selected>{$tax->label}: {$parent_name}{$term->name}</option>";
							}
						}
					}
					?>
				</select>
			</div>
		</div>

		<div class="cas--settings__layout-row">
			<div class="cas--settings__layout-row-item">
				<label for="">
					<span class="cas--label__text"><?php esc_html_e( 'Post types for Push Notifications', 'canvas' ); ?></span>
				</label>
				<p class="cas--description"><?php esc_html_e( 'Select which post types will generate a push notification', 'canvas' ); ?></p>
			</div>
			<div class="cas--settings__layout-row-item">
				<?php
				$posttypes         = get_post_types( '', 'names' );
				$includedPostTypes = explode( ',', Canvas::get_option( 'push_post_types', 'post' ) );
				foreach ( $posttypes as $v ) {
					if ( $v != 'attachment' && $v != 'revision' && $v != 'nav_menu_item' ) {
						$checked = '';
						if ( in_array( $v, $includedPostTypes ) ) {
							$checked = 'checked';
						}
						?>
						<div class="cas--checkbox-group">
							<input type="checkbox" id='canvas_push_post_types_<?php echo esc_attr( $v ); ?>' name="canvas_push_post_types[]"
								value="<?php echo esc_attr( $v ); ?>" <?php echo $checked; ?>/>
							<label for="canvas_push_post_types_<?php echo esc_attr( $v ); ?>"><?php echo esc_html( $v ); ?></label>
						</div>
						<?php
					}
				}
				?>
			</div>
		</div>

		<div class="cas--settings__layout-row">
			<div class="cas--settings__layout-row-item">
				<label for="">
					<span class="cas--label__text"><?php esc_html_e( 'Tags', 'canvas' ); ?></span>
				</label>
				<p class="cas--description"><?php esc_html_e( 'Use category name as tags for automatic notifications', 'canvas' ); ?></p>
			</div>
			<div class="cas--settings__layout-row-item">
				<input name="canvas_push_auto_use_cat" type="checkbox" id="canvas_push_auto_use_cat" value="1"<?php checked( Canvas::get_option( 'push_auto_use_cat' ) ); ?>>
				<label for="canvas_push_auto_use_cat">Use category name as tags</label>
			</div>
		</div>

		<div class="cas--settings__layout-row">
			<div class="cas--settings__layout-row-item">
				<p>Always use these tags for automatic notifications</p>
			</div>
			<div class="cas--settings__layout-row-item">
				<input name="canvas_push_auto_tags" type="text" id="canvas_push_auto_tags" value="<?php echo esc_attr( implode( ',', Canvas::get_option( 'push_auto_tags', array() ) ) ); ?>">
				<p class="description canvas-tags">The field values must be placed in a comma separated list.</p>
			</div>
		</div>

		<div class="cas--settings__layout-row">
			<div class="cas--settings__layout-row-item">
				<label for="">
					<span class="cas--label__text"><?php esc_html_e( 'Include featured image in push notifications', 'canvas' ); ?></span>
				</label>
				<p class="cas--description"><?php esc_html_e( 'This affects whether you pass the image field or not to the push provider.', 'canvas' ); ?></p>
			</div>
			<div class="cas--settings__layout-row-item">
				<input name="canvas_push_include_image" type="checkbox" id="canvas_push_include_image" value="1"<?php checked( Canvas::get_option( 'push_include_image' ) ); ?>>
				<label for="canvas_push_include_image">Include featured image</label>
			</div>
		</div>

		<div class="cas--settings__layout-row">
			<div class="cas--settings__layout-row-item">
				<label for="">
					<span class="cas--label__text"><?php esc_html_e( 'Enable logging for debugging', 'canvas' ); ?></span>
				</label>
				<p class="cas--description"><?php esc_html_e( 'Store a log of the requests and responses received from the push server, in the order for us to debug any issues with push notifications. Logs will be saved to a file on your server.', 'canvas' ); ?></p>
			</div>
			<div class="cas--settings__layout-row-item">
				<div>
					<input name="canvas_push_log_enable" type="checkbox" id="canvas_push_log_enable" value="1"<?php checked( Canvas::get_option( 'push_log_enable' ) ); ?>>
					<label for="canvas_push_log_enable">Enable Push Logging</label>
				</div>
				<p id="canvas_push_log_name_block"<?php echo Canvas::get_option( 'push_log_enable' ) ? '' : ' style="display:none;"'; ?>>
					<input type="text" value="<?php echo esc_attr( CanvasAdmin::get_push_log_name( true ) ); ?>" readonly="readonly">
				</p>
			</div>
		</div>

		<div class="cas--settings__layout-row">
			<div class="cas--settings__layout-row-item">
				<label for="">
					<span class="cas--label__text"><?php esc_html_e( 'User profile in OneSignal', 'canvas' ); ?></span>
				</label>
				<p class="cas--description"><?php esc_html_e( 'Select the user information that will be saved to OneSignal when the user logs-in', 'canvas' ); ?></p>
			</div>
			<div class="cas--settings__layout-row-item">
				<p>
					<input type="radio" name="canvas_user_profile" id="user_id" value="user_id" <?php checked( 'user_id', Canvas::get_option( 'user_profile', 'user_id' ) ); ?>>
					<label for="user_id">User ID</label><br>
					<input type="radio" name="canvas_user_profile" id="user_email" value="user_email" <?php checked( 'user_email', Canvas::get_option( 'user_profile' ) ); ?>>
					<label for="user_email">User Email</label><br>
				</p>
			</div>
		</div>

		<div class="clearfix"></div>
	</div>
</div>
