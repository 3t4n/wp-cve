<?php

	/* ----------------------------------------------------------------------------------------------------

		BEAMER API -> METABOX
		Adds API metabox options for posts and custom styles

	---------------------------------------------------------------------------------------------------- */

	// STYLES AND SCRIPTS  ---------------------------------------------------------------------------
	function bmr_api_styles() {
		$pagenow = get_current_screen();
		if( $pagenow->post_type == 'post' OR bmr_get_setting('api_types') == true OR bmr_get_setting('api_page') == true ){
			bmr_enqueue_styles();
		}
	}
	add_action('admin_enqueue_scripts', 'bmr_api_styles');

	function bmr_api_scripts() {
		$pagenow = get_current_screen();
		if( $pagenow->post_type == 'post' OR bmr_get_setting('api_types') == true OR bmr_get_setting('api_page') == true ){
			wp_enqueue_script( 'beamer-api', plugins_url('js/beamer-api-scripts.js',__FILE__), array('jquery'), null, true );
		}
	}
	add_action('admin_enqueue_scripts', 'bmr_api_scripts');

	// THE METABOX  ---------------------------------------------------------------------------

	// Add beamer api metabox
	function bmr_get_meta( $value ) {
		global $post;
		$field = get_post_meta( $post->ID, $value, true );
		if ( ! empty( $field ) ) {
			return is_array( $field ) ? stripslashes_deep( $field ) : stripslashes( wp_kses_decode_entities( $field ) );
		} else {
			return false;
		}
	}

	function bmr_add_meta_box() {
		$pagenow = get_current_screen();
		$types = get_post_types(array(), 'object');
		$public = array();

		// All custom post types
		if( bmr_get_setting('api_types') == true ){
			foreach( $types as $type ){
				if( $type->public == 1 ){
					if( $type->name != 'page' ){ $public[] = $type->name; }
				}
			}
		}
		// Pages
		if( bmr_get_setting('api_page') ){
			$public[] = 'page';
		}

		if( bmr_get_setting('api_types') == true OR bmr_get_setting('api_page') == true ){
			$enabled = $public;
		}else{
			$enabled = 'post';
		}
		add_meta_box(
			'beamer_options-beamer-options',
			__( 'Beamer Options', 'beamer_options' ),
			'bmr_html',
			$enabled,
			'side',
			'high'
		);
	}
	add_action( 'add_meta_boxes', 'bmr_add_meta_box' );

	function bmr_html( $post) {
		wp_nonce_field( '_bmr_nonce', 'bmr_nonce' ); ?>
		<div class="bmr-field-group bmr-hero">You're using the <strong>Beamer API</strong> for WP. You can change your configuration in the <a href="options-general.php?page=beamer-settings">Beamer Settings page.</a></div>
		<?php
			// Check if the special custom post type default is activated for this post
			$types_default = bmr_get_setting('api_typedefault');
			$types_only = bmr_get_setting('api_typeonly');
			$types_atp = bmr_get_setting('api_autopost');
			$types_atpold = bmr_get_setting('api_autopostold');

			if( $types_atp == true && get_post_status( get_the_ID() ) != 'publish' ){
				$default = 'off';
			}elseif( $types_atpold == true && get_post_status( get_the_ID() ) == 'publish' ){
				$default = 'off';
			}else{
				if( $types_only != '' && get_post_status( get_the_ID() ) == 'auto-draft' ){
					$type_current = get_post_type(get_the_ID());
					$types_parsed = str_replace(' ', '', $types_only);
					$types_list = explode(',', $types_parsed);
					if( in_array($type_current, $types_list) ) {
						$default = 'on';
					}else{
						$default = 'off';
					}
				}elseif( $types_default != '' && get_post_status( get_the_ID() ) == 'auto-draft' ){
					$type_current = get_post_type(get_the_ID());
					$types_parsed = str_replace(' ', '', $types_default);
					$types_list = explode(',', $types_parsed);
					if( in_array($type_current, $types_list) ) {
						$default = 'off';
					}else{
						$default = 'on';
					}
				}else{
					$default = 'on';
				}
			}

		?>
		<div class="bmr-field-group">
			<div class="bmr-switch">
				<div class="bmr-checkbox-text">
					<strong>Publish on Beamer. </strong>
					<?php if( $default == 'on' ): ?>
						<span<?php echo ( bmr_get_meta( 'bmr_ignore' ) == null ) ? ' class="on"' : ''; ?>>This post <strong class="main">will show</strong> on Beamer</span>
						<span<?php echo ( bmr_get_meta( 'bmr_ignore' ) === 'ignore' ) ? ' class="on"' : ''; ?>>This post <strong class="red">will not show</strong> on Beamer</span>
					<?php else: ?>
						<span>This post <strong class="main">will show</strong> on Beamer</span>
						<span class="on">This post <strong class="red">will not show</strong> on Beamer</span>
					<?php endif; ?>
				</div>
				<?php if( $default == 'on' ): ?>
					<div class="bmr-checkbox main disabler<?php echo ( bmr_get_meta( 'bmr_ignore' ) === 'ignore' ) ? ' checked' : ''; ?>"></div>
					<input type="checkbox" name="bmr_ignore" id="bmr_ignore" value="bmr_ignore" <?php echo ( bmr_get_meta( 'bmr_ignore' ) === 'ignore' ) ? 'checked' : ''; ?>>
				<?php else: ?>
					<div class="bmr-checkbox main disabler checked"></div>
					<input type="checkbox" name="bmr_ignore" id="bmr_ignore" value="bmr_ignore" checked>
				<?php endif; ?>
			</div>
		</div>
		<div class="bmr-field-group">
			<label for="bmr_category"><?php _e( 'Category.', 'beamer_options' ); ?></label>
			<select name="bmr_category" id="bmr_category">
				<option value="new" <?php echo (bmr_get_meta( 'bmr_category' ) === 'new' ) ? 'selected' : '' ?>>New</option>
				<option value="improvement" <?php echo (bmr_get_meta( 'bmr_category' ) === 'improvement' ) ? 'selected' : '' ?>>Improvement</option>
				<option value="fix" <?php echo (bmr_get_meta( 'bmr_category' ) === 'fix' ) ? 'selected' : '' ?>>Fix</option>
				<option value="comingsoon" <?php echo (bmr_get_meta( 'bmr_category' ) === 'comingsoon' ) ? 'selected' : '' ?>>Coming Soon</option>
				<option value="announcement" <?php echo (bmr_get_meta( 'bmr_category' ) === 'announcement' ) ? 'selected' : '' ?>>Announcement</option>
				<?php
					$pagenow = get_current_screen();
					if( $pagenow->post_type == 'post' ):
						$args = array(
						    'orderby'           => 'count',
						    'order'             => 'DESC',
						    'number'            => '',
						    'fields'            => 'all',
						    'hierarchical'      => true,
						    'hide_empty'		=> false
						);
						$this_terms = get_terms('category', $args);
						foreach($this_terms as $term):
				?>
					<option value="<?php echo($term->name); ?>" <?php echo (bmr_get_meta( 'bmr_category' ) === $term->name ) ? 'selected' : '' ?>><?php echo($term->name); ?> (Custom)</option>
				<?php
						endforeach;
					endif;
				?>
			</select>
		</div>
		<div class="bmr-field-group bmr-action-buttons">
			<a class="bmr-action bmr-action-more">Advanced Options</a>
		</div>
		<div class="bmr-advanced-opts bmr-extra-options">
			<div class="bmr-field-group">
				<label for="bmr_link_text"><?php _e( 'Link text.', 'beamer_options' ); ?></label>
				<input type="text" name="bmr_link_text" id="bmr_link_text" placeholder="<?php echo bmr_get_setting('api_readmore') ?: 'Read more'; ?>" value="<?php echo bmr_get_meta( 'bmr_link_text' ); ?>">
			</div>
			<div class="bmr-field-group">
				<div class="bmr-switch">
					<div class="bmr-checkbox-text">
						<label for="bmr_feedback"><?php _e( 'Feedback.', 'beamer_options' ); ?></label>
						<span<?php echo ( bmr_get_meta( 'bmr_feedback' ) == null ) ? ' class="on"' : ''; ?>><strong class="green">Feedback Enabled</strong></span>
						<span<?php echo ( bmr_get_meta( 'bmr_feedback' ) === 'off' ) ? ' class="on"' : ''; ?>><strong class="red">Feedback Disabled</strong></span>
					</div>
					<div class="bmr-checkbox enabler disabler<?php echo ( bmr_get_meta( 'bmr_feedback' ) === 'off' ) ? ' checked' : ''; ?>"></div>
					<input type="checkbox" name="bmr_feedback" id="bmr_feedback" value="feedback" <?php echo ( bmr_get_meta( 'bmr_feedback' ) === 'off' ) ? 'checked' : ''; ?>>
				</div>
			</div>
			<div class="bmr-field-group">
				<div class="bmr-switch">
					<div class="bmr-checkbox-text">
						<label for="bmr_reactions"><?php _e( 'Reactions.', 'beamer_options' ); ?></label>
						<span<?php echo ( bmr_get_meta( 'bmr_reactions' ) == null ) ? ' class="on"' : ''; ?>><strong class="green">Reactions Enabled</strong></span>
						<span<?php echo ( bmr_get_meta( 'bmr_reactions' ) === 'off' ) ? ' class="on"' : ''; ?>><strong class="red">Reactions Disabled</strong></span>
					</div>
					<div class="bmr-checkbox enabler disabler<?php echo ( bmr_get_meta( 'bmr_reactions' ) === 'off' ) ? ' checked' : ''; ?>"></div>
					<input type="checkbox" name="bmr_reactions" id="bmr_reactions" value="reactions" <?php echo ( bmr_get_meta( 'bmr_reactions' ) === 'off' ) ? 'checked' : ''; ?>>
				</div>
			</div>
		</div>
		<?php
	}

	// Save metabox values
	function bmr_save( $post_id ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
		if ( ! isset( $_POST['bmr_nonce'] ) || ! wp_verify_nonce( $_POST['bmr_nonce'], '_bmr_nonce' ) ) return;
		if ( ! current_user_can( 'edit_post', $post_id ) ) return;

		if ( isset( $_POST['bmr_ignore'] ) )
			update_post_meta( $post_id, 'bmr_ignore', 'ignore');
		else
			update_post_meta( $post_id, 'bmr_ignore', null);
		if ( isset( $_POST['bmr_category'] ) )
			update_post_meta( $post_id, 'bmr_category', sanitize_text_field( $_POST['bmr_category'] ) );
		if ( isset( $_POST['bmr_link_text'] ) )
			update_post_meta( $post_id, 'bmr_link_text', sanitize_text_field( $_POST['bmr_link_text'] ) );
		if ( isset( $_POST['bmr_feedback'] ) )
			update_post_meta( $post_id, 'bmr_feedback', 'off');
		else
			update_post_meta( $post_id, 'bmr_feedback', null);
		if ( isset( $_POST['bmr_reactions'] ) )
			update_post_meta( $post_id, 'bmr_reactions', 'off');
		else
			update_post_meta( $post_id, 'bmr_reactions', null);
	}
	add_action( 'post_updated', 'bmr_save', 10 );

?>