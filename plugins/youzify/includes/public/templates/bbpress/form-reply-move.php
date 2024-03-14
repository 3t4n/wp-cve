<?php

/**
 * Move Reply
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<div id="bbpress-forums">


	<div class="youzify-bbp-topic-head">
		<?php bbp_breadcrumb(); ?>
		<?php youzify_bbp_forum_topic_head() ?>
	</div>

	<?php if ( is_user_logged_in() && current_user_can( 'edit_topic', bbp_get_topic_id() ) ) : ?>

		<div id="move-reply-<?php bbp_topic_id(); ?>" class="bbp-reply-move">

			<form id="move_reply" name="move_reply" method="post" action="<?php the_permalink(); ?>">

				<div class="bbp-form youzify-bbp-box">

					<div class="youzify-bbp-box-title">
						<i class="fas fa-paper-plane"></i>
						<?php printf( __( 'Move reply "%s"', 'youzify' ), bbp_get_reply_title() ); ?>
					</div>

					<div class="youzify-bbp-box-content">

						<div class="bbp-template-notice info">
							<p><?php _e( 'You can either make this reply a new topic with a new title, or merge it into an existing topic.', 'youzify' ); ?></p>
						</div>

						<div class="bbp-template-notice">
							<p><?php _e( 'If you choose an existing topic, replies will be ordered by the time and date they were created.', 'youzify' ); ?></p>
						</div>

						<fieldset class="bbp-form">
							<legend><?php _e( 'Move Method', 'youzify' ); ?></legend>

							<div class="youzify-bbp-fieldset-content">

								<div class="youzify-bbp-form-item youzify-bbp-form-item-radio">
									<input name="bbp_reply_move_option" id="bbp_reply_move_option_reply" type="radio" checked="checked" value="topic" tabindex="<?php bbp_tab_index(); ?>" />
									<label for="bbp_reply_move_option_reply"><?php printf( __( 'New topic in <strong>%s</strong> titled:', 'youzify' ), bbp_get_forum_title( bbp_get_reply_forum_id( bbp_get_reply_id() ) ) ); ?></label>
								</div>
								<div class="youzify-bbp-form-item youzify-bbp-form-item-text">
									<input type="text" id="bbp_reply_move_destination_title" value="<?php printf( __( 'Moved: %s', 'youzify' ), bbp_get_reply_title() ); ?>" tabindex="<?php bbp_tab_index(); ?>" size="35" name="bbp_reply_move_destination_title" />
								</div>

							<?php if ( bbp_has_topics( array( 'show_stickies' => false, 'post_parent' => bbp_get_reply_forum_id( bbp_get_reply_id() ), 'post__not_in' => array( bbp_get_reply_topic_id( bbp_get_reply_id() ) ) ) ) ) : ?>

								<div class="youzify-bbp-form-item youzify-bbp-form-item-radio>
									<input name="bbp_reply_move_option" id="bbp_reply_move_option_existing" type="radio" value="existing" tabindex="<?php bbp_tab_index(); ?>" />
									<label for="bbp_reply_move_option_existing"><?php _e( 'Use an existing topic in this forum:', 'youzify' ); ?></label>
								</div>

								<div class="youzify-bbp-form-item youzify-bbp-form-item-select">

									<?php
										bbp_dropdown( array(
											'post_type'   => bbp_get_topic_post_type(),
											'post_parent' => bbp_get_reply_forum_id( bbp_get_reply_id() ),
											'selected'    => -1,
											'exclude'     => bbp_get_reply_topic_id( bbp_get_reply_id() ),
											'select_id'   => 'bbp_destination_topic'
										) );
									?>

								</div>

							<?php endif; ?>
							</div>
						</fieldset>

						<div class="bbp-template-notice error">
							<p><?php _e( '<strong>WARNING:</strong> This process cannot be undone.', 'youzify' ); ?></p>
						</div>

						<div class="bbp-submit-wrapper">
							<button type="submit" tabindex="<?php bbp_tab_index(); ?>" id="bbp_move_reply_submit" name="bbp_move_reply_submit" class="button submit"><?php _e( 'Submit', 'youzify' ); ?></button>
						</div>
					</div>

					<?php bbp_move_reply_form_fields(); ?>
				</div>
			</form>
		</div>

	<?php else : ?>

		<div id="no-reply-<?php bbp_reply_id(); ?>" class="bbp-no-reply">
			<div class="entry-content"><?php is_user_logged_in() ? _e( 'You do not have the permissions to edit this reply!', 'youzify' ) : _e( 'You cannot edit this reply.', 'youzify' ); ?></div>
		</div>

	<?php endif; ?>

</div>
