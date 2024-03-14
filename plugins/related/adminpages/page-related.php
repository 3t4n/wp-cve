<?php
/*
 * Settings page for Related plugin.
 */



/*
 * Adds an option page to Settings
 */
function related_options() {
	add_options_page( esc_html__('Related Posts', 'related'), esc_html__('Related Posts', 'related'), 'manage_options', 'related.php', 'related_options_page');
}
add_action( 'admin_menu', 'related_options', 11 );


function related_options_page() {
	// Handle the POST
	$active_tab = 'related_show'; /* default tab */
	if ( isset( $_POST['form'] ) ) {
		if ( ! current_user_can('manage_options') ) {
			die( esc_html__('You need a higher level of permission.', 'related' ) );
		}

		if ( $_POST['form'] === 'related_show' ) {

			/* Check Nonce */
			$verified = false;
			if ( isset($_POST['related_show_nonce']) ) {
				$verified = wp_verify_nonce( $_POST['related_show_nonce'], 'related_show_nonce' );
			}
			if ( $verified == false ) {
				// Nonce is invalid.
				echo '<div id="message" class="error fade notice is-dismissible"><p>' . esc_html__('The Nonce did not validate. Please try again.', 'related') . '</p></div>';
			} else {
				$showkeys = array();
				foreach ($_POST as $key => $value) {
					if ( $key === 'form' ) {
						continue;
					}
					$showkeys[] = str_replace('show_', '', sanitize_text_field($key));
				}
				$showkeys = json_encode($showkeys);
				update_option( 'related_show', $showkeys );
				echo '<div id="message" class="updated fade notice is-dismissible"><p>' . esc_html__('Settings updated successfully.', 'related') . '</p></div>';
			}

		} else if ( $_POST['form'] === 'related_list' ) {

			/* Check Nonce */
			$verified = false;
			if ( isset($_POST['related_list_nonce']) ) {
				$verified = wp_verify_nonce( $_POST['related_list_nonce'], 'related_list_nonce' );
			}
			if ( $verified == false ) {
				// Nonce is invalid.
				echo '<div id="message" class="error fade notice is-dismissible"><p>' . esc_html__('The Nonce did not validate. Please try again.', 'related') . '</p></div>';
			} else {
				$listkeys = array();
				foreach ($_POST as $key => $value) {
					if ( $key === 'form' ) {
						continue;
					}
					$listkeys[] = str_replace('list_', '', sanitize_text_field($key));
				}
				$listkeys = json_encode($listkeys);
				update_option( 'related_list', $listkeys );
				echo '<div id="message" class="updated fade notice is-dismissible"><p>' . esc_html__('Settings updated successfully.', 'related') . '</p></div>';
			}
			$active_tab = 'related_list';

		} else if ( $_POST['form'] === 'related_content' ) {

			/* Check Nonce */
			$verified = false;
			if ( isset($_POST['related_content_nonce']) ) {
				$verified = wp_verify_nonce( $_POST['related_content_nonce'], 'related_content_nonce' );
			}
			if ( $verified == false ) {
				// Nonce is invalid.
				echo '<div id="message" class="error fade notice is-dismissible"><p>' . esc_html__('The Nonce did not validate. Please try again.', 'related') . '</p></div>';
			} else {
				if ( isset( $_POST['related_content'] ) ) {
					if ($_POST['related_content'] === 'on') {
						update_option('related_content', 1);
					} else {
						update_option('related_content', 0);
					}
				} else {
					update_option('related_content', 0);
				}
				if ( isset( $_POST['related_content_all'] ) ) {
					if ($_POST['related_content_all'] === 'on') {
						update_option('related_content_all', 1);
					} else {
						update_option('related_content_all', 0);
					}
				} else {
					update_option('related_content_all', 0);
				}
				if ( isset( $_POST['related_content_rss'] ) ) {
					if ($_POST['related_content_rss'] === 'on') {
						update_option('related_content_rss', 1);
					} else {
						update_option('related_content_rss', 0);
					}
				} else {
					update_option('related_content_rss', 0);
				}
				if ( isset( $_POST['related_content_title'] ) && $_POST['related_content_title'] !== '' ) {
					update_option( 'related_content_title', sanitize_text_field($_POST['related_content_title']) );
				} else {
					delete_option( 'related_content_title' );
				}
				if ( isset( $_POST['related_content_extended'] ) ) {
					if ($_POST['related_content_extended'] === 'on') {
						update_option('related_content_extended', 1);
					} else {
						update_option('related_content_extended', 0);
					}
				} else {
					update_option('related_content_extended', 0);
				}

				echo '<div id="message" class="updated fade notice is-dismissible"><p>' . esc_html__('Settings updated successfully.', 'related') . '</p></div>';
			}
			$active_tab = 'related_content';

		} else if ( $_POST['form'] === 'related_double' ) {

			/* Check Nonce */
			$verified = false;
			if ( isset($_POST['related_double_nonce']) ) {
				$verified = wp_verify_nonce( $_POST['related_double_nonce'], 'related_double_nonce' );
			}
			if ( $verified == false ) {
				// Nonce is invalid.
				echo '<div id="message" class="error fade notice is-dismissible"><p>' . esc_html__('The Nonce did not validate. Please try again.', 'related') . '</p></div>';
			} else {
				if ( isset( $_POST['related_double_plugin'] ) ) {
					if ($_POST['related_double_plugin'] === 'on') {
						update_option('related_double_plugin', 1);
					} else {
						update_option('related_double_plugin', 0);
					}
				} else {
					update_option('related_double_plugin', 0);
				}

				echo '<div id="message" class="updated fade notice is-dismissible"><p>' . esc_html__('Settings updated successfully.', 'related') . '</p></div>';
			}
			$active_tab = 'related_double';
		} else if ( $_POST['form'] === 'related_uninstall' ) {

			/* Check Nonce */
			$verified = false;
			if ( isset($_POST['related_uninstall_nonce']) ) {
				$verified = wp_verify_nonce( $_POST['related_uninstall_nonce'], 'related_uninstall_nonce' );
			}
			if ( $verified == false ) {
				// Nonce is invalid.
				echo '<div id="message" class="error fade notice is-dismissible"><p>' . esc_html__('The Nonce did not validate. Please try again.', 'related') . '</p></div>';
			} else {
				if ( isset( $_POST['related_uninstall_confirmed'] ) && $_POST['related_uninstall_confirmed'] === 'on' && isset( $_POST['related_uninstall'] )  ) {
					// uninstall the plugin -> delete all settings and post-meta of the plugin.
					related_uninstall();

					$dashboard = '<a href="' . esc_attr( admin_url( '/index.php' ) ) . '">' . esc_html__('Dashboard', 'related') . '</a>';
					/* translators: %s is a link to the dashboard */
					echo '
						<div id="message" class="updated fade notice is-dismissible">
							<p>' . esc_html__('Relations in post meta have been removed.', 'related') . '</p>
							<p>' . esc_html__('Settings have been removed.', 'related') . '</p>
							<p>' . esc_html__('The plugin is deactivated.', 'related') . '</p>
							<p>' . sprintf( esc_html__('You can now go to your %s.', 'related'), $dashboard ) . '</p>
						</div>
						';
				} else {
					// Uninstallation not confirmed.
				}
			}
			$active_tab = 'related_uninstall';
		}
	} ?>

	<div class="wrap">

	<h1><?php esc_html_e('Related Posts', 'related'); ?></h1>

	<?php /* Do not use nav but h2, since it is using (in)visible content, not real navigation. */ ?>
	<h2 class="nav-tab-wrapper related-nav-tab-wrapper" role="tablist">
		<a href="#" role="tab" class="nav-tab <?php if ($active_tab === 'related_show') { echo 'nav-tab-active';} ?>" rel="related_post_types"><?php esc_html_e('Post types', 'related'); ?></a>
		<a href="#" role="tab" class="nav-tab <?php if ($active_tab === 'related_list') { echo 'nav-tab-active';} ?>" rel="related_form"><?php esc_html_e('Form', 'related'); ?></a>
		<a href="#" role="tab" class="nav-tab <?php if ($active_tab === 'related_content') { echo 'nav-tab-active';} ?>" rel="related_content"><?php esc_html_e('Content', 'related'); ?></a>
		<a href="#" role="tab" class="nav-tab <?php if ($active_tab === 'related_double') { echo 'nav-tab-active';} ?>" rel="related_double"><?php esc_html_e('Double Up', 'related'); ?></a>
		<a href="#" role="tab" class="nav-tab <?php if ($active_tab === 'related_uninstall') { echo 'nav-tab-active';} ?>" rel="related_uninstall"><?php esc_html_e('Uninstall', 'related'); ?></a>
		<a href="#" role="tab" class="nav-tab" rel="related_about"><?php esc_html_e('About', 'related'); ?></a>
	</h2>

	<div role="tabpanel" class="related_options related_post_types <?php if ($active_tab === 'related_show') { echo 'active';} ?>">
		<div class="poststuff metabox-holder">
			<div class="related-widget">
				<h3><?php esc_html_e('Post Types to show the Related Posts form on.', 'related'); ?></h3>
				<?php
				$related_show = get_option('related_show');
				$related_show = json_decode( $related_show );
				$any = '';
				if ( empty( $related_show ) ) {
					$related_show = array();
					$related_show[] = 'any';
					$any = 'checked="checked"';
				} else {
					foreach ( $related_show as $key ) {
						if ( $key === 'any' ) {
							$any = 'checked="checked"';
						}
					}
				}
				?>

				<div class="misc-pub-section">
					<p><?php esc_html_e('If Any is selected, it will show on any Post Type. If none are selected, Any will still apply.', 'related'); ?></p>
					<form name="related_options_page_show" action="" method="POST">
						<?php
						/* Nonce */
						$nonce = wp_create_nonce( 'related_show_nonce' );
						echo '<input type="hidden" id="related_show_nonce" name="related_show_nonce" value="' . esc_attr( $nonce ) . '" />'; ?>
						<ul>
							<li><label for="show_any">
								<input name="show_any" type="checkbox" id="show_any" <?php echo $any; ?>  />
								<?php esc_html_e( 'any', 'related' ); ?>
							</label></li>
							<?php
							$post_types = get_post_types( '', 'names' );
							$checked = '';
							foreach ( $post_types as $post_type ) {
								if ( $post_type === 'revision' || $post_type === 'nav_menu_item' ) {
									continue;
								}

								foreach ( $related_show as $key ) {
									if ( $key === $post_type ) {
										$checked = 'checked="checked"';
									}
								}
								?>
								<li><label for="show_<?php echo esc_attr( $post_type ); ?>">
									<input name="show_<?php echo esc_attr( $post_type ); ?>" type="checkbox" id="show_<?php echo esc_attr( $post_type ); ?>" <?php echo $checked; ?>  />
									<?php echo $post_type; ?>
								</label></li>
								<?php
								$checked = ''; // reset
							} ?>
							<li><input type="hidden" class="form" value="related_show" name="form" />
								<input type="submit" class="button button-primary" value="<?php esc_attr_e( 'Submit','related' ); ?>"/></li>
						</ul>
					</form>
				</div> <!-- .misc-pub-section -->
			</div> <!-- .related-widget -->
		</div> <!-- metabox-holder -->
	</div> <!-- .related_post_types -->


	<div role="tabpanel" class="related_options related_form <?php if ($active_tab === 'related_list') { echo "active";} ?>">
		<div class="poststuff metabox-holder">
			<div class="related-widget">
				<h3><?php esc_html_e('Post Types to list on the Related Posts forms.', 'related'); ?></h3>
				<?php
				$any = ''; // reset
				$related_list = get_option('related_list');
				$related_list = json_decode( $related_list );
				if ( empty( $related_list ) ) {
					$related_list = array();
					$related_list[] = 'any';
					$any = 'checked';
				} else {
					foreach ( $related_list as $key ) {
						if ( $key === 'any' ) {
							$any = 'checked="checked"';
						}
					}
				} ?>

				<div class="misc-pub-section">
				<p><?php esc_html_e('If Any is selected, it will list any Post Type. If none are selected, it will still list any Post Type.', 'related'); ?></p>
				<form name="related_options_page_listed" action="" method="POST">
					<?php
					/* Nonce */
					$nonce = wp_create_nonce( 'related_list_nonce' );
					echo '<input type="hidden" id="related_list_nonce" name="related_list_nonce" value="' . esc_attr( $nonce ) . '" />'; ?>
					<ul>
						<li><label for="list_any">
							<input name="list_any" type="checkbox" id="list_any" <?php echo $any; ?>  />
							<?php esc_html_e( 'any', 'related' ); ?>
						</label></li>
						<?php
						$post_types = get_post_types( '', 'names' );
						foreach ( $post_types as $post_type ) {
							if ( $post_type === 'revision' || $post_type === 'nav_menu_item' ) {
								continue;
							}

							foreach ( $related_list as $key ) {
								if ( $key === $post_type ) {
									$checked = 'checked="checked"';
								}
							}
							?>
							<li><label for="list_<?php echo esc_attr( $post_type ); ?>">
								<input name="list_<?php echo esc_attr( $post_type ); ?>" type="checkbox" id="list_<?php echo esc_attr( $post_type ); ?>" <?php echo $checked; ?>  />
								<?php echo $post_type; ?>
							</label></li>
							<?php
							$checked = ''; // reset
						} ?>
						<li><input type="hidden" class="form" value="related_list" name="form" />
							<input type="submit" class="button button-primary" value="<?php esc_attr_e( 'Submit', 'related' ); ?>"/></li>
						</ul>
					</form>
				</div>
			</div>
		</div>
	</div> <!-- .related_post_types -->


	<div role="tabpanel" class="related_options related_content <?php if ($active_tab === 'related_content') { echo 'active';} ?>">
		<div class="poststuff metabox-holder">
			<div class="related-widget">
				<h3><?php esc_html_e('Add the Related Posts to the content.', 'related'); ?></h3>
				<div class="misc-pub-section">
					<form name="related_options_page_content" action="" method="POST">
						<?php
						/* Nonce */
						$nonce = wp_create_nonce( 'related_content_nonce' );
						echo '<input type="hidden" id="related_content_nonce" name="related_content_nonce" value="' . esc_attr( $nonce ) . '" />'; ?>
						<ul>
							<li>
								<h4><?php esc_html_e('If you select to add the Related Posts below the content, it will be added to every display of the content.', 'related'); ?></h4>
								<label for="related_content">
									<input name="related_content" type="checkbox" id="related_content" <?php checked(1, get_option('related_content', 0) ); ?> />
									<?php esc_html_e('Add to content on single view.', 'related'); ?>
								</label>
							</li>
							<li>
								<label for="related_content_all">
									<input name="related_content_all" type="checkbox" id="related_content_all" <?php checked(1, get_option('related_content_all', 0) ); ?> />
									<?php esc_html_e('Add to content on all views.', 'related'); ?>
								</label>
							</li>
							<li>
								<label for="related_content_rss">
									<input name="related_content_rss" type="checkbox" id="related_content_rss" <?php checked(1, get_option('related_content_rss', 0) ); ?> />
									<?php esc_html_e('Add to content on RSS Feed.', 'related'); ?>
								</label>
							</li>
							<li>
								<h4><?php esc_html_e('Title of related list.', 'related'); ?></h4>
								<?php $related_content_title = get_option('related_content_title'); ?>
								<label for="related_content_title"><?php esc_html_e('Title to show above the related posts: ', 'related'); ?><br />
									<input name="related_content_title" type="text" id="related_content_title" value="<?php echo esc_attr(stripslashes(get_option('related_content_title', esc_html__('Related Posts', 'related')))); ?>" />
								</label>
							</li>
							<li>
								<h4><?php esc_html_e('Extended view.', 'related'); ?></h4>
								<label for="related_content_extended">
									<input name="related_content_extended" type="checkbox" id="related_content_extended" <?php checked(1, get_option('related_content_extended', 0) ); ?> />
									<?php esc_html_e('Show extended content in list, like featured image.', 'related'); ?>
								</label>
							</li>
							<li>
								<input type="hidden" class="form" value="related_content" name="form" />
								<input type="submit" class="button button-primary" value="<?php esc_attr_e( 'Submit', 'related' ); ?>"/>
							</li>
						</ul>
					</form>
				</div>
			</div>
		</div>
	</div> <!-- .related_content -->


	<div role="tabpanel" class="related_options related_double <?php if ($active_tab === 'related_double') { echo 'active';} ?>">
		<div class="poststuff metabox-holder">
			<div class="related-widget">
				<h3><?php esc_html_e('Double Up plugin for a second list.', 'related'); ?></h3>
				<?php
				$related_double = get_option('related_double_plugin');
				$checked = '';
				if ( $related_double ) {
					$checked = 'checked';
				}
				?>

				<div class="misc-pub-section">
					<form name="related_options_page_double" action="" method="POST">
						<?php
						/* Nonce */
						$nonce = wp_create_nonce( 'related_double_nonce' );
						echo '<input type="hidden" id="related_double_nonce" name="related_double_nonce" value="' . esc_attr( $nonce ) . '" />'; ?>

						<p>
							<label for="related_double_plugin">
								<input name="related_double_plugin" type="checkbox" id="related_double_plugin" <?php echo $checked; ?>  />
								<?php esc_html_e('Activate Double Up plugin for a second list.', 'related'); ?>
							</label>
						</p>

						<input type="hidden" class="form" value="related_double" name="form" />
						<input type="submit" class="button button-primary" value="<?php esc_attr_e( 'Submit','related' ); ?>"/></li>
					</form>
				</div> <!-- .misc-pub-section -->
			</div> <!-- .related-widget -->
		</div> <!-- metabox-holder -->
	</div> <!-- .related_post_types -->


	<div role="tabpanel" class="related_options related_uninstall <?php if ($active_tab === 'related_uninstall') { echo 'active';} ?>">
		<div class="poststuff metabox-holder">
			<div class="related-widget">
				<h3><?php esc_html_e('Uninstall this plugin.', 'related'); ?></h3>

				<p><?php esc_html_e('Uninstalling means that all database entries are removed (settings and post relations).', 'related'); echo '<br />';
				/* translators: %s is a <strong> element. */
				echo sprintf( esc_html__('This can %snot%s be undone.', 'related'), '<strong>', '</strong>' ) . '<br />';
				esc_html_e('It is a good idea to make a backup of your website before you touch this button.', 'related');
				?></p>

				<div class="misc-pub-section">
					<form name="related_options_page_uninstall" action="" method="POST">
						<?php
						/* Nonce */
						$nonce = wp_create_nonce( 'related_uninstall_nonce' );
						echo '<input type="hidden" id="related_uninstall_nonce" name="related_uninstall_nonce" value="' . esc_attr( $nonce ) . '" />'; ?>

						<input type="checkbox" name="related_uninstall_confirmed" id="related_uninstall_confirmed">
						<label for="related_uninstall_confirmed"><?php esc_html_e("Yes, I'm absolutely sure of this. Proceed!", 'related'); ?></label>
						<input type="hidden" class="form" value="related_uninstall" name="form" />

						<p class="submit">
							<input type="submit" name="related_uninstall" id="related_uninstall" class="button" disabled value="<?php esc_attr_e('Uninstall', 'related'); ?>" />
						</p>
					</form>
				</div>

			</div> <!-- .related-widget -->
		</div> <!-- metabox-holder -->
	</div> <!-- .related_post_types -->


	<div role="tabpanel" class="related_options related_about">
		<div class="poststuff metabox-holder">
			<div class="related-widget">

				<h3><?php esc_html_e('Support', 'related'); ?></h3>
				<p><?php
					$support = '<a href="https://wordpress.org/support/plugin/related" target="_blank">';
					/* translators: %1$s and %2$s is a link */
					echo sprintf( esc_html__( 'If you have a problem or a feature request, please post it on the %1$ssupport forum at wordpress.org%2$s.', 'related' ), $support, '</a>' ); ?>
					<?php esc_html_e('I will do my best to respond as soon as possible.', 'related'); ?><br />
					<?php esc_html_e('If you send me an email, I will not reply. Please use the support forum.', 'related'); ?>
				</p>


				<h3><?php esc_html_e('Review this plugin.', 'related'); ?></h3>
				<p><?php
					$review = '<a href="https://wordpress.org/support/view/plugin-reviews/related?rate=5#postform" target="_blank">';
					/* translators: %1$s and %2$s is a link */
					echo sprintf( esc_html__( 'If this plugin has any value to you, then please leave a review at %1$sthe plugin page%2$s at wordpress.org.', 'related' ), $review, '</a>' ); ?>
				</p>

				<h3><?php esc_html_e('Translations', 'related'); ?></h3>
				<p><?php
					$link = '<a href="https://translate.wordpress.org/projects/wp-plugins/related" target="_blank">';
					/* translators: %1$s and %2$s is a link */
					echo sprintf( esc_html__( 'Translations can be added very easily through %1$sGlotPress%2$s.', 'related' ), $link, '</a>' ) . '<br />';
					/* translators: %1$s and %2$s is a link */
					echo sprintf( esc_html__( 'You can start translating strings there for your locale. They need to be validated though, so if there is no validator yet, and you want to apply for being validator (PTE), please post it on the %1$ssupport forum%2$s.', 'related' ), $support, '</a>' ); echo '<br />';
					$make = '<a href="https://make.wordpress.org/polyglots/" target="_blank">';
					/* translators: %1$s and %2$s is a link */
					echo sprintf( esc_html__( 'I will make a request on %1$smake/polyglots%2$s to have you added as validator for this plugin/locale.', 'related' ), $make, '</a>' ); ?>
				</p>

			</div>
		</div>
	</div> <!-- .related_about -->


	</div> <!-- .wrap -->
	<?php
}


/*
 * Uninstall plugin structure.
 * Called from the settingspage.
 * @since 3.1.4
 */
function related_uninstall() {

	$query = new WP_Query( array(
		'numberposts'      => -1,
		'nopaging'         => true,
		'meta_key'         => 'related_posts',
		'post_type'        => 'any',
		'fields'           => 'ids',
		'suppress_filters' => true,
	));
	if ( $query->have_posts() ) {
		foreach ( $query->posts as $postid ) {
			delete_post_meta( $postid, 'related_posts' );
		}
	}

	$query = new WP_Query( array(
		'numberposts'      => -1,
		'nopaging'         => true,
		'meta_key'         => 'related_du_posts',
		'post_type'        => 'any',
		'fields'           => 'ids',
		'suppress_filters' => true,
	));
	if ( $query->have_posts() ) {
		foreach ( $query->posts as $postid ) {
			delete_post_meta( $postid, 'related_du_posts' );
		}
	}


	$option_names = array(
			'related_show',
			'related_list',
			'related_content',
			'related_content_all',
			'related_content_rss',
			'related_content_title',
			'related_content_extended',
			'related_double_plugin',
			'related_du_show',
			'related_du_list',
			'related_du_content',
			'related_du_content_all',
			'related_du_content_rss',
			'related_du_content_title',
			'related_du_content_extended',
		);

	foreach ( $option_names as $option_name ) {

		delete_option( $option_name );

		// for site options in Multisite
		delete_site_option( $option_name );

	}

	// Deactivate ourselves
	deactivate_plugins( RELATED_FILE . '/related.php' );

}

/*
 * Add the options to WordPress if they don't exist.
 */
add_option( 'related_show', json_encode(array( 'post' )) );
add_option( 'related_list', json_encode(array( 'post' )) );
add_option( 'related_du_show', json_encode(array( 'post' )) );
add_option( 'related_du_list', json_encode(array( 'post' )) );

