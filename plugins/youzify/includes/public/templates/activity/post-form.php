<?php

/**
 * BuddyPress - Activity Post Form
 */

do_action( 'bp_activity_before_post_form' );

if ( youzify_is_wall_posting_form_active() ) :

?>

<form action="<?php bp_activity_post_form_action(); ?>" method="post" id="youzify-wall-form" class="youzify-wall-form" name="whats-new-form" enctype="multipart/form-data">

	<div class="youzify-wall-options"><?php do_action( 'youzify_activity_form_post_types' );  ?></div>

	<div id="whats-new-content" class="youzify-wall-content">

		<div class="youzify-wall-author" href="<?php echo bp_loggedin_user_domain(); ?>"><?php bp_loggedin_user_avatar(); ?></div>

			<textarea name="status" class="youzify-wall-textarea bp-suggestions" id="whats-new" placeholder="<?php if ( bp_is_group() )
		printf( __( "What's new in %s, %s?", 'youzify' ), bp_get_group_name(), bp_get_user_firstname( bp_get_loggedin_user_fullname() ) );
	else
		printf( __( "What's new, %s?", 'youzify' ), bp_get_user_firstname( bp_get_loggedin_user_fullname() ) );
	?>" <?php if ( bp_is_group() ) : ?> data-suggestions-group-id="<?php echo esc_attr( (int) bp_get_current_group_id() ); ?>" <?php endif; ?>
			><?php if ( isset( $_GET['r'] ) ) : ?>@<?php echo sanitize_textarea_field( $_GET['r'] ); ?> <?php endif; ?></textarea>


		<?php if ( 'on' == youzify_option( 'youzify_enable_posts_emoji', 'on' ) ) : ?><div class="youzify-load-emojis youzify-load-posts-emojis"><i class="far fa-smile"></i></div><?php endif; ?>

		<?php if ( 'on' == youzify_option( 'youzify_enable_wall_url_preview', 'on' ) ) : ?>
			<div class="youzify-lp-prepost" data-loaded="false">
				<div class="lp-prepost-container">

			    <button class="lp-button-cancel"type="button"><i class="fas fa-times"></i></button>

			    <div class="lp-preview-image">

			        <span class="lp-preview-video-icon"><i class="fas fa-play lp-play"></i></span>
			    </div>

			    <div class="lp-prepost-wrap">

			        <div class="lp-preview-title-wrap"><span class="lp-preview-title">{{preview.title}}</span></div>

			        <div class="lp-preview-replace-title-wrap"></div>

			        <div class="lp-preview-canonical-url">{{preview.site}}</div>

			        <div class="lp-preview-description-wrap">
			            <div class="lp-preview-description">{{preview.description}}</div>
			        </div>

			        <div class="lp-preview-replace-description-wrap"></div>

			        <div class="clearfix lp-preview-pagination">

			            <span class="lp-preview-thubmnail-buttons">
			                <div class="youzify-lp-previous-image"><i class="fas fa-caret-left"></i></div>
			                <div class="youzify-lp-next-image"><i class="fas fa-caret-right"></i></div>
			            </span>

			            <span class="lp-preview-thubmnail-pagination">{{thumbnailPaginationText}}</span><span class="lp-pagination-of"><?php _e( 'of', 'youzify' ); ?></span><span class="lp-preview-thubmnail-text">{{thumbnailText}}</span>

			        </div>

			        <div class="lp-preview-no-thubmnail">
			            <label class="lp-preview-no-thubmnail-text">
			                <input name="url_preview_use_thumbnail" type="checkbox" class="youzify-lp-use-thumbnail">
			                <span><?php _e( 'No thumbnail', 'youzify' ) ?></span>
			            </label>
			        </div>

			    </div>

				</div>

				<div class="clearfix lp-button lp-loading-text"><i class="fas fa-spinner fa-spin"></i></div>

			</div>

    	<?php endif; ?>

		<?php $unallowed_activities = youzify_option( 'youzify_unallowed_activities' ); ?>

		<?php if ( empty( $unallowed_activities) || ! isset( $unallowed_activities['activity_link'] ) ) : ?>
		<div class="youzify-wall-custom-form youzify-wall-link-form" data-post-type="activity_link">

			<div class="youzify-wall-cf-item">
				<input type="text" class="youzify-wall-cf-input" name="link_url" placeholder="<?php _e( 'Add Link URL', 'youzify' ); ?>">
			</div>

			<div class="youzify-wall-cf-item">
				<input type="text" class="youzify-wall-cf-input" name="link_title" placeholder="<?php _e( 'Add Link Title', 'youzify' ); ?>">
			</div>

			<div class="youzify-wall-cf-item">
				<textarea name="link_desc" class="youzify-wall-cf-input" placeholder="<?php _e( 'Brief Link Description', 'youzify' ); ?>"></textarea>
			</div>

		</div>
		<?php endif; ?>

		<?php if ( empty( $unallowed_activities) || ! isset( $unallowed_activities['activity_quote'] ) ) : ?>
		<div class="youzify-wall-custom-form youzify-wall-quote-form" data-post-type="activity_quote">

			<div class="youzify-wall-cf-item">
				<input type="text" class="youzify-wall-cf-input" name="quote_owner" placeholder="<?php _e( 'Add Quote Owner', 'youzify' ); ?>">
			</div>

			<div class="youzify-wall-cf-item">
				<textarea name="quote_text" class="youzify-wall-cf-input" placeholder="<?php _e( 'Add Quote Text', 'youzify' ); ?>"></textarea>
			</div>

		</div>
		<?php endif; ?>

		<?php if ( empty( $unallowed_activities) || ! isset( $unallowed_activities['activity_giphy'] ) ) : ?>
		<div class="youzify-wall-custom-form youzify-wall-giphy-form" data-post-type="activity_giphy">

			<div class="youzify-giphy-loading-preview"><i class="fas fa-spin fa-spinner"></i></div>

			<div class="youzify-selected-giphy-item">
				<input type="hidden" name="giphy_image">
				<i class="fas fa-trash youzify-delete-giphy-item"></i>
			</div>

			<div class="youzify-wall-cf-item">
				<div class="youzify-giphy-search-form">
					<input type="text" class="youzify-wall-cf-input youzify-giphy-search-input" name="giphy_search" placeholder="<?php _e( 'Search for GIFs...', 'youzify' ); ?>">
				</div>
				<i class="fas fa-spin fa-spinner youzify-cf-input-loader"></i>
				<div class="youzify-giphy-items-content">
					<div class="youzify-load-more-giphys" data-page="2"><i class="fas fa-ellipsis-h"></i></div>
					<div class="youzify-no-gifs-found"><i class="far fa-frown"></i><?php _e( 'No GIFs found', 'youzify' ); ?></div>
				</div>
			</div>

		</div>
		<?php endif; ?>

		<?php do_action( 'youzify_after_wall_post_form_textarea' ); ?>

	</div>

	<div class="youzify-wall-actions" id="youzify-wall-actions">

		<?php do_action( 'youzify_before_wall_post_form_actions' ); ?>

		<div class="youzify-form-tools">

			<?php do_action( 'bp_activity_post_form_tools' ); ?>

			<?php do_action( 'bp_activity_before_post_form_tools' ); ?>

			<?php if ( apply_filters( 'youzify_allow_wall_upload_attachments', true ) ) : ?>
				<div class="youzify-wall-upload-btn youzify-form-tool" data-youzify-tooltip="<?php _e( 'Upload Attachment', 'youzify' ); ?>"><i class="fas fa-paperclip"></i></div>
			<?php endif; ?>

			<?php do_action( 'bp_activity_after_post_form_tools' ); ?>

		</div>
		<div class="youzify-posting-form-actions">

			<?php if ( bp_is_active( 'groups' ) && ! bp_is_my_profile() && ! bp_is_group() ) : ?>

				<div id="whats-new-post-in-box">

					<label for="whats-new-post-in" ><?php _e( 'Post in:', 'youzify' ); ?></label>
					<select id="whats-new-post-in" name="whats-new-post-in">
						<option selected="selected" value="0"><?php _e( 'My Profile', 'youzify' ); ?></option>

						<?php if ( bp_has_groups( 'user_id=' . bp_loggedin_user_id() . '&type=alphabetical&max=100&per_page=100&populate_extras=0&update_meta_cache=0' ) ) :
							while ( bp_groups() ) : bp_the_group(); ?>

								<option value="<?php bp_group_id(); ?>"><?php bp_group_name(); ?></option>

							<?php endwhile;
						endif; ?>

					</select>
				</div>
				<input type="hidden" id="whats-new-post-object" name="whats-new-post-object" value="groups">

			<?php elseif ( bp_is_group_activity() ) : ?>
				<?php if ( apply_filters( 'youzify_show_group_postin_hidden_fields', true ) ) : ?>
					<input type="hidden" id="whats-new-post-object" name="whats-new-post-object" value="groups">
					<input type="hidden" id="whats-new-post-in" name="whats-new-post-in" value="<?php bp_group_id(); ?>">
				<?php endif; ?>
			<?php endif; ?>

			<?php do_action( 'youzify_wall_before_submit_form_action' ); ?>

			<button type="submit" name="aw-whats-new-submit" class="youzify-wall-post"><?php esc_attr_e( 'Post', 'youzify' ); ?></button>

			<?php do_action( 'youzify_wall_after_submit_form_action' ); ?>

		</div>

			<?php

			/**
			 * Fires at the end of the activity post form markup.
			 *
			 * @since 1.2.0
			 */
			do_action( 'bp_activity_post_form_options' ); ?>

	</div>

	<?php do_action( 'bp_activity_post_form_after_actions' ) ?>

	<div class="youzify-wall-attachments youzify-attachments">
		<input hidden="true" class="youzify-upload-attachments" type="file" name="attachments[]" multiple>
		<div class="youzify-form-attachments"></div>
	</div>

	<?php wp_nonce_field( 'youzify_post_update', '_youzify_wpnonce_post_update' ); ?>

	<?php

	/**
	 * Fires after the activity post form.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_after_activity_post_form' ); ?>

</form>

<?php endif; ?>