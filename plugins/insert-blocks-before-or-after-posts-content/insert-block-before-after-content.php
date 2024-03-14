<?php
/*
 * Plugin name: Insert Blocks Before or After Posts Content
 * Description: Automatically insert blocks of content before and/or after each posts/page content.
 * Plugin URI: https://jeanbaptisteaudras.com/en/insert-blocks-before-or-after-posts-content-wordpress
 * Requires at least: 5.3
 * Requires PHP: 5.6
 * Author: audrasjb
 * Author URI: https://jeanbaptisteaudras.com
 * Version: 0.3
 * Tested up to: 5.6
 * License: GPLv2
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text-domain: insert-blocks-before-or-after-posts-content
 */


/**
 * Force Block editor for Reusable Blocks even when Classic editor plugin is activated.
 */
if ( is_admin() ) {
	add_filter( 'use_block_editor_for_post', 'ibbac_enable_gutenberg_post', 1000, 2 );
	add_filter( 'use_block_editor_for_post_type', 'ibbac_enable_gutenberg_post_type', 1000, 2 );
}
function ibbac_enable_gutenberg_post( $can_edit, $post ) {
	if ( empty( $post->ID ) ) return $can_edit;
	if ( 'wp_block' === get_post_type( $post->ID ) ) return true;
	return $can_edit;
}
function ibbac_enable_gutenberg_post_type( $can_edit, $post_type ) {
	if ( 'wp_block' === $post_type ) return true;
	return $can_edit;
}

/**
 * Maybe insert blocks before/after the content.
 */
function ibbac_insert_content_into_posts( $template ) {

	$current_post_id = get_the_ID();
	$existing_before = '';
	$existing_after  = '';

	if ( is_single() || is_singular() ) {

		$current_post_type = get_post_type();
		// Get existing settings
		$existing_ibbac_settings = get_option( 'ibbac_settings', array() );
		if ( ! empty( $existing_ibbac_settings ) ) {
			
			// Get before content
			$existing_before = isset( $existing_ibbac_settings[$current_post_type]['before'] ) ? $existing_ibbac_settings[$current_post_type]['before'] : '';
			$existing_before_optout = isset( $existing_ibbac_settings[$current_post_type]['optout'] ) ? $existing_ibbac_settings[$current_post_type]['optout']: '';
			
			if ( ! empty( $existing_before ) && 0 !== intval( $existing_before ) ) {
				
				$get_optout_before = intval( get_post_meta( $current_post_id, '_ibbac_meta_box_optout_before', true ) );
				
				if ( 1 !== $get_optout_before ) {

					$before_content = get_post( $existing_before );
					$before_content = apply_filters('the_content', $before_content->post_content ) ;

					add_filter( 'the_content', function( $content ) use ( $before_content ){ 

						if ( ( is_single() && in_the_loop() && is_main_query() ) || ( is_singular() && in_the_loop() && is_main_query() ) ) {
							$content = $before_content . $content;
						}

						return $content ;
					});

				}
			}

			// Get after content
			$existing_after = isset( $existing_ibbac_settings[$current_post_type]['after'] ) ? $existing_ibbac_settings[$current_post_type]['after'] : '';
			$existing_after_optout = isset( $existing_ibbac_settings[$current_post_type]['optout'] ) ? $existing_ibbac_settings[$current_post_type]['optout'] : '';
			
			if ( ! empty( $existing_after ) && 0 !== intval( $existing_after ) ) {
				
				$get_optout_after = intval( get_post_meta( $current_post_id, '_ibbac_meta_box_optout_after', true ) );

				if ( 1 !== $get_optout_after ) {

					$after_content = get_post( $existing_after );
					$after_content = apply_filters('the_content', $after_content->post_content );

					add_filter( 'the_content', function( $content ) use ( $after_content ){ 

						if ( ( is_single() && in_the_loop() && is_main_query() ) || ( is_singular() && in_the_loop() && is_main_query() ) ) {
							$content = $content . $after_content ;
						}

						return $content ;
					});
				}
			} 
		}
	}
	
	return $template;
}

add_filter( 'template_include', 'ibbac_insert_content_into_posts' );


/**
 * Metabox settings for Post by Post exclusion.
 */
function ibbac_add_meta_box() {
	$existing_ibbac_settings = get_option( 'ibbac_settings', array() );

	if ( ! empty( $existing_ibbac_settings ) ) {
		foreach ( $existing_ibbac_settings as $post_type => $values ) {
			if ( true === $existing_ibbac_settings[$post_type]['optout'] ) {
				add_meta_box( 'ibbac_meta_box', 'Content before/after the post', 'ibbac_add_meta_box_callback', $post_type, 'side', 'low' );
			}
		}
	}
}
add_action( 'add_meta_boxes', 'ibbac_add_meta_box' );

function ibbac_add_meta_box_callback( $post ) {
	wp_nonce_field( 'ibbac_meta_box', 'ibbac_meta_box_nonce' );
	
	$get_optout_before = intval( get_post_meta( $post->ID, '_ibbac_meta_box_optout_before', true ) );
	$before_checked = ( $get_optout_before === 1 ) ? ' checked' : '';
	$get_optout_after = intval( get_post_meta( $post->ID, '_ibbac_meta_box_optout_after', true ) );
	$after_checked = ( $get_optout_after === 1 ) ? ' checked' : '';
	?>
	<p>
		<?php esc_html_e( 'There is currently content blocks that are automatically generated before and/or after this post content.', 'insert-blocks-before-or-after-posts-content' ); ?>
		<?php esc_html_e( 'You can disable these blocks for this specific post using the following options.', 'insert-blocks-before-or-after-posts-content' ); ?>
	</p>
	<p>
		<input type="checkbox" id="ibbac_meta_box_optout_before" name="ibbac_meta_box_optout_before" value="1" <?php echo $before_checked; ?> />
		<label for="ibbac_meta_box_optout_before">
			<?php esc_html_e( 'Remove blocks before the content', 'insert-blocks-before-or-after-posts-content' ); ?>
		</label>
	</p>
	<p>
		<input type="checkbox" id="ibbac_meta_box_optout_after" name="ibbac_meta_box_optout_after" value="1" <?php echo $after_checked; ?> />
		<label for="ibbac_meta_box_optout_after">
			<?php esc_html_e( 'Remove blocks after the content', 'insert-blocks-before-or-after-posts-content' ); ?>
		</label>
	</p>
	<?php if ( current_user_can( 'manage_options' ) ) : ?>
	<p class="alignright">
		<a target="_blank" href="<?php echo get_admin_url(); ?>/themes.php?page=insert-blocks-before-or-after-posts-content">
			<?php esc_html_e( 'General before/after settings', 'insert-blocks-before-or-after-posts-content' ); ?>
			<span class="screen-reader-text"><?php esc_html_e( '(opens in a new tab)', 'insert-blocks-before-or-after-posts-content' ); ?></span>
		</a>
	</p>
	<br class="clear" />
	<?php endif; ?>

	<?php
}

function ibbac_save_meta_box( $post_id ) {
	if ( ! isset( $_POST['ibbac_meta_box_nonce'] ) ) {
		return $post_id;
	}
	$nonce = $_POST['ibbac_meta_box_nonce'];
	if ( ! wp_verify_nonce( $nonce, 'ibbac_meta_box' ) ) {
		return $post_id;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return $post_id;
	}
	// Check user’s permissions
	$post_type = $_POST['post_type'];
	$existing_ibbac_settings = get_option( 'ibbac_settings', array() );
	if ( ! empty( $existing_ibbac_settings ) ) {
		if ( true !== $existing_ibbac_settings[$post_type]['optout'] ) {
			return $post_id;
		}
	}
	if ( 'page' === $post_type ) {
    	if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return $post_id;
		}
	} else {
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}
	}
	
	$get_optout_before = intval( $_POST['ibbac_meta_box_optout_before'] );
	$optout_before = ( $get_optout_before === 1 ) ? 1 : 0;
	update_post_meta( $post_id, '_ibbac_meta_box_optout_before', $optout_before );
	
	$get_optout_after = intval( $_POST['ibbac_meta_box_optout_after'] );
	$optout_after = ( $get_optout_after === 1 ) ? 1 : 0;
	update_post_meta( $post_id, '_ibbac_meta_box_optout_after', $optout_after );
}
add_action( 'save_post', 'ibbac_save_meta_box' );


/**
 * Plugin settings page.
 */
function ibbac_add_submenu_page() { 
	add_submenu_page( 'themes.php', esc_html__( 'Before/after content blocks', 'insert-blocks-before-or-after-posts-content' ), esc_html__( 'Before/after content', 'insert-blocks-before-or-after-posts-content' ), 'manage_options', 'insert-blocks-before-or-after-posts-content', 'ibbac_add_submenu_page_callback' );
}
add_action( 'admin_menu', 'ibbac_add_submenu_page' );

function ibbac_add_submenu_page_callback() {
	?>
	<div class="wrap contextual_adminbar_color_submenu_page">
		<form action="" method="post">
			<?php
			// Get wp_block editor URLs
			$wp_block_edit_url = get_admin_url() . 'post-new.php?post_type=wp_block';
			$wp_blocks_list_url = get_admin_url() . 'edit.php?post_type=wp_block';
			
			// Update options
			$new_ibbac_settings = array();
			if ( isset( $_POST ) && ! empty( $_POST ) ) {
				if ( wp_verify_nonce( $_POST['nonce'], 'ibbac_settings_nonce' ) ) {
					$ibbac_post_types = isset( $_POST['ibbac_post_types'] ) ? ( array ) $_POST['ibbac_post_types'] : array();
					if ( ! empty( $ibbac_post_types ) ) {
						$ibbac_post_types = array_map( 'esc_attr', $ibbac_post_types );
						foreach( $ibbac_post_types as $ibbac_post_type ) {
							$before = '';
							if ( isset( $_POST['ibbac_block_before_' . $ibbac_post_type] ) ) {
								$before = intval( $_POST['ibbac_block_before_' . $ibbac_post_type] );
							}
							$after = '';
							if ( isset( $_POST['ibbac_block_after_' . $ibbac_post_type] ) ) {
								$after = intval( $_POST['ibbac_block_after_' . $ibbac_post_type] );
							}
							$optout = false;
							if ( isset( $_POST['ibbac_block_optout_' . $ibbac_post_type] ) ) {
								$get_optout = intval( $_POST['ibbac_block_optout_' . $ibbac_post_type] );
								$optout = ( $get_optout === 1 ) ? true : false;
							}
							$new_ibbac_settings[$ibbac_post_type] = array(
								'before' => $before,
								'after' => $after,
								'optout' => $optout,
							);
						}
					}
					update_option( 'ibbac_settings', $new_ibbac_settings );
					?>
					<div class="notice notice-success settings-error is-dismissible"> 
						<p>
							<?php esc_html_e( 'Settings saved.', 'insert-blocks-before-or-after-posts-content' ); ?>
						</p>
					</div>
					<?php
				}
			}
			// Get existing settings
			if ( ! empty( get_option( 'ibbac_settings' ) ) ) {
				$existing_ibbac_settings = get_option( 'ibbac_settings' );
			} else {
				$existing_ibbac_settings = array();
			}
			?>

			<input type="hidden" name="nonce" value="<?php echo wp_create_nonce( 'ibbac_settings_nonce' ) ?>">

			<h1>
				<?php esc_html_e( 'Insert Blocks Before/After Post Content', 'insert-blocks-before-or-after-posts-content' ); ?>
			</h1>
			<p>
				<a class="button button-primary" href="<?php echo $wp_block_edit_url; ?>" target="_blank">
					<?php esc_html_e( 'Create a new before/after block', 'insert-blocks-before-or-after-posts-content' );	?> 
					<span class="screen-reader-text"><?php esc_html_e( '(opens in a new tab)', 'insert-blocks-before-or-after-posts-content' );	?></span>
					<span aria-hidden="true" class="dashicons dashicons-external" style="position: relative; top: 2px;"></span>
				</a> 
				<a class="button button-secondary" href="<?php echo $wp_blocks_list_url; ?>" target="_blank">
					<?php esc_html_e( 'Edit existing blocks', 'insert-blocks-before-or-after-posts-content' );	?> 
					<span class="screen-reader-text"><?php esc_html_e( '(opens in a new tab)', 'insert-blocks-before-or-after-posts-content' );	?></span>
					<span aria-hidden="true" class="dashicons dashicons-external" style="position: relative; top: 2px;"></span>
				</a>
			</p>
			
		<?php
		$args = array(
			'post_type' => 'wp_block',
			'posts_per_page' => -1,
		);
		$available_blocks = array();
		$wp_blocks = new WP_Query( $args );
		if ( $wp_blocks->have_posts() ) {
			while ( $wp_blocks->have_posts() ) {
				$wp_blocks->the_post();
				$available_blocks[] = array(
					'id' => get_the_ID(),
					'title' => get_the_title(),
					'edit_link' => get_edit_post_link( get_the_ID() ),
				);
			}
		} else {
			?>
			<div class="notice notice-warning">
				<p>
					<?php esc_html_e( 'You don’t have any content block available yet.', 'insert-blocks-before-or-after-posts-content' ); ?>
				</p>
				<p>
					<a class="button button-secondary" href="<?php echo $wp_block_edit_url; ?>" target="_blank">
						<?php esc_html_e( 'Create a new before/after block', 'insert-blocks-before-or-after-posts-content' );	?> 
						<span class="screen-reader-text"><?php esc_html_e( '(opens in a new tab)', 'insert-blocks-before-or-after-posts-content' ); ?></span>
						<span aria-hidden="true" class="dashicons dashicons-external" style="position: relative; top: 2px;"></span>
					</a>
					<span class="description"><?php esc_html_e( 'This button will open a dedicated block editor to create a new content block fully dedicated to your before/after sections.', 'insert-blocks-before-or-after-posts-content' );	?></span>
				</p>
			</div>
			<?php
		}

		$available_post_types = get_post_types( array( 'public' => true ), 'objects' );
		foreach ( $available_post_types as $available_post_type ) {
			if ( 'attachment' === $available_post_type->name ) {
				continue;
			}
			$dashicon = '';
			if ( 'post' === $available_post_type->name ) {
				$dashicon = 'dashicons-admin-post';
			} elseif ( 'page' === $available_post_type->name ) {
				$dashicon = 'dashicons-admin-page';
			} elseif ( ! empty( $available_post_type->menu_icon ) ) {
				$dashicon = $available_post_type->menu_icon;
			}
			$dashicon_class = ' class="dashicons ' . $dashicon . '"';
			?>
			<input type="hidden" name="ibbac_post_types[]" value="<?php echo $available_post_type->name; ?>" />
			<h2>
				<span<?php echo $dashicon_class; ?>></span> 
				<?php echo ucfirst( $available_post_type->name ); ?>
			</h2>
			<table class="form-table" role="presentation">
				<tbody>
					<tr>
						<th scope="row">
							<label for="ibbac_block_before_<?php echo $available_post_type->name; ?>">
								<?php esc_html_e( 'Before the content', 'insert-blocks-before-or-after-posts-content' ); ?>
							</label>
						</th>
						<?php $disabled = empty( $available_blocks ) ? ' disabled' : ''; ?>
						<td>
							<select id="ibbac_block_before_<?php echo $available_post_type->name; ?>" name="ibbac_block_before_<?php echo $available_post_type->name; ?>" <?php echo $disabled; ?>>
								<option value="">— <?php esc_html_e( 'Select a block', 'insert-blocks-before-or-after-posts-content' ); ?> —</option>
								<?php foreach ( $available_blocks as $available_block ) : ?>
									<?php
									$selected = '';
									if ( isset( $existing_ibbac_settings[$available_post_type->name] ) ) {
										if ( ! empty( $existing_ibbac_settings[$available_post_type->name]['before'] ) && ( $available_block['id'] === $existing_ibbac_settings[$available_post_type->name]['before'] ) ) {
											$selected = ' selected';
										}
									}
									?>
									<option value="<?php echo $available_block['id']; ?>" <?php echo $selected; ?>>
										<?php
										echo sprintf(
											/* translators: 1: Name of the block. 2: ID of the block. */
											__( '%1$s (ID: %2$s)', 'insert-blocks-before-or-after-posts-content' ),
											$available_block['title'],
											$available_block['id']
										);
										?>
									</option>
								<?php endforeach; ?>
							</select>
							<p class="description">
								<?php
								echo sprintf( 
									/* translators: name of the post type. */
									__( 'Select a block that have to be added before the content of each %s.', 'insert-blocks-before-or-after-posts-content' ),
									$available_post_type->name
								);
								?>
							</p>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="ibbac_block_after_<?php echo $available_post_type->name; ?>">
								<?php esc_html_e( 'After the content', 'insert-blocks-before-or-after-posts-content' ); ?>
							</label>
						</th>
						<?php $disabled = empty( $available_blocks ) ? ' disabled' : ''; ?>
						<td>
							<select id="ibbac_block_after_<?php echo $available_post_type->name; ?>" name="ibbac_block_after_<?php echo $available_post_type->name; ?>" <?php echo $disabled; ?>>
								<option value="">— <?php esc_html_e( 'Select a block', 'insert-blocks-before-or-after-posts-content' ); ?> —</option>
								<?php foreach ( $available_blocks as $available_block ) : ?>
									<?php
									$selected = '';
									if ( isset( $existing_ibbac_settings[$available_post_type->name] ) ) {
										if ( ! empty( $existing_ibbac_settings[$available_post_type->name]['after'] ) && ( $available_block['id'] === $existing_ibbac_settings[$available_post_type->name]['after'] ) ) {
											$selected = ' selected';
										}
									}
									?>
									<option value="<?php echo $available_block['id']; ?>" <?php echo $selected; ?>>
										<?php
										echo sprintf(
											/* translators: 1: Name of the block. 2: ID of the block. */
											__( '%1$s (ID: %2$s)', 'insert-blocks-before-or-after-posts-content' ),
											$available_block['title'],
											$available_block['id']
										);
										?>
									</option>
								<?php endforeach; ?>
							</select>
							<p class="description">
								<?php
								echo sprintf( 
									/* translators: name of the post type. */
									__( 'Select a block that have to be added after the content of each %s.', 'insert-blocks-before-or-after-posts-content' ),
									$available_post_type->name
								);
								?>
							</p>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="ibbac_block_optout_<?php echo $available_post_type->name; ?>">
								<?php esc_html_e( 'Allow opt-out', 'insert-blocks-before-or-after-posts-content' ); ?>
							</label>
						</th>
						<?php
						$disabled = empty( $available_blocks ) ? ' disabled' : '';
						$checked = '';
						if ( isset( $existing_ibbac_settings[$available_post_type->name] ) ) {
							if ( true === $existing_ibbac_settings[$available_post_type->name]['optout'] ) {
								$checked = ' checked';
							}
						}
						?>
						<td>
							<input type="checkbox" name="ibbac_block_optout_<?php echo $available_post_type->name; ?>" id="ibbac_block_optout_<?php echo $available_post_type->name; ?>" value="1" <?php echo $disabled . $checked; ?> />
							<span class="description">
								<?php
								echo sprintf( 
									/* translators: name of the post type. */
									__( 'If checked, authors will have the capability to opt-out these settings for each %s.', 'insert-blocks-before-or-after-posts-content' ),
									$available_post_type->name
								);
								?>
							</span>
						</td>
					</tr>
				</tbody>
			</table>

			<?php
		}
		?>
			<input type="submit" class="button button-primary" value="<?php esc_attr_e( 'Save changes', 'insert-blocks-before-or-after-posts-content' ); ?>" />
		</form>
	</div>
	<?php
}