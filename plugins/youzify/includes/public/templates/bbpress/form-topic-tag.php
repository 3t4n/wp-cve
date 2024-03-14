<?php

/**
 * Edit Topic Tag
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<?php if ( current_user_can( 'edit_topic_tags' ) ) : ?>

	<div id="edit-topic-tag-<?php bbp_topic_tag_id(); ?>" class="bbp-topic-tag-form">

		<div class="bbp-form youzify-bbp-box" id="bbp-edit-topic-tag">

			<div class="youzify-bbp-box-title">
				<i class="fas fa-tag"></i>
				<?php printf( __( 'Manage Tag: "%s"', 'youzify' ), bbp_get_topic_tag_name() ); ?>
			</div>

			<div class="youzify-bbp-box-content">

			<fieldset class="bbp-form" id="tag-rename">

				<legend><?php _e( 'Rename', 'youzify' ); ?></legend>

				<div class="bbp-template-notice info">
					<p><?php _e( 'Leave the slug empty to have one automatically generated.', 'youzify' ); ?></p>
				</div>

				<div class="bbp-template-notice">
					<p><?php _e( 'Changing the slug affects its permalink. Any links to the old slug will stop working.', 'youzify' ); ?></p>
				</div>

				<form id="rename_tag" name="rename_tag" method="post" action="<?php the_permalink(); ?>">

					<div class="youzify-bbp-form-item youzify-bbp-form-item-text">
						<label for="tag-name"><?php _e( 'Name :', 'youzify' ); ?></label>
						<input type="text" id="tag-name" name="tag-name" size="20" maxlength="40" tabindex="<?php bbp_tab_index(); ?>" value="<?php echo esc_attr( bbp_get_topic_tag_name() ); ?>" />
					</div>

					<div class="youzify-bbp-form-item youzify-bbp-form-item-text">
						<label for="tag-slug"><?php _e( 'Slug :', 'youzify' ); ?></label>
						<input type="text" id="tag-slug" name="tag-slug" size="20" maxlength="40" tabindex="<?php bbp_tab_index(); ?>" value="<?php echo esc_attr( apply_filters( 'editable_slug', bbp_get_topic_tag_slug() ) ); ?>" />
					</div>

					<div class="bbp-submit-wrapper">
						<button type="submit" tabindex="<?php bbp_tab_index(); ?>" class="button submit"><i class="fas fa-refresh"></i><?php esc_attr_e( 'Update', 'youzify' ); ?></button>

						<input type="hidden" name="tag-id" value="<?php bbp_topic_tag_id(); ?>" />
						<input type="hidden" name="action" value="bbp-update-topic-tag" />

						<?php wp_nonce_field( 'update-tag_' . bbp_get_topic_tag_id() ); ?>

					</div>
				</form>

			</fieldset>

			<fieldset class="bbp-form" id="tag-merge">

				<legend><?php _e( 'Merge', 'youzify' ); ?></legend>

				<div class="bbp-template-notice">
					<p><?php _e( 'Merging tags together cannot be undone.', 'youzify' ); ?></p>
				</div>

				<form id="merge_tag" name="merge_tag" method="post" action="<?php the_permalink(); ?>">

					<div class="youzify-bbp-form-item youzify-bbp-form-item-text">
						<label for="tag-existing-name"><?php _e( 'Existing tag :', 'youzify' ); ?></label>
						<input type="text" id="tag-existing-name" name="tag-existing-name" size="22" tabindex="<?php bbp_tab_index(); ?>" maxlength="40" />
					</div>

					<div class="bbp-submit-wrapper">
						<button type="submit" tabindex="<?php bbp_tab_index(); ?>" class="button submit" onclick="return confirm('<?php echo esc_js( sprintf( __( 'Are you sure you want to merge the "%s" tag into the tag you specified?', 'youzify' ), bbp_get_topic_tag_name() ) ); ?>');"><i class="fas fa-random"></i><?php esc_attr_e( 'Merge', 'youzify' ); ?></button>

						<input type="hidden" name="tag-id" value="<?php bbp_topic_tag_id(); ?>" />
						<input type="hidden" name="action" value="bbp-merge-topic-tag" />

						<?php wp_nonce_field( 'merge-tag_' . bbp_get_topic_tag_id() ); ?>
					</div>
				</form>

			</fieldset>

			<?php if ( current_user_can( 'delete_topic_tags' ) ) : ?>

				<fieldset class="bbp-form" id="delete-tag">

					<legend><?php _e( 'Delete', 'youzify' ); ?></legend>

					<div class="bbp-template-notice info">
						<p><?php _e( 'This does not delete your topics. Only the tag itself is deleted.', 'youzify' ); ?></p>
					</div>
					<div class="bbp-template-notice">
						<p><?php _e( 'Deleting a tag cannot be undone.', 'youzify' ); ?></p>
						<p><?php _e( 'Any links to this tag will no longer function.', 'youzify' ); ?></p>
					</div>

					<form id="delete_tag" name="delete_tag" method="post" action="<?php the_permalink(); ?>">

						<div class="bbp-submit-wrapper">
							<button type="submit" tabindex="<?php bbp_tab_index(); ?>" class="button submit" onclick="return confirm('<?php echo esc_js( sprintf( __( 'Are you sure you want to delete the "%s" tag? This is permanent and cannot be undone.', 'youzify' ), bbp_get_topic_tag_name() ) ); ?>');"><i class="fas fa-trash-alt"></i><?php esc_attr_e( 'Delete', 'youzify' ); ?></button>

							<input type="hidden" name="tag-id" value="<?php bbp_topic_tag_id(); ?>" />
							<input type="hidden" name="action" value="bbp-delete-topic-tag" />

							<?php wp_nonce_field( 'delete-tag_' . bbp_get_topic_tag_id() ); ?>
						</div>
					</form>

				</fieldset>
			</div>
			<?php endif; ?>

		</div>
	</div>

<?php endif; ?>
