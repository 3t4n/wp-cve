<?php

/**
 * Forums Loop - Single Forum
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<ul id="bbp-forum-<?php bbp_forum_id(); ?>" <?php bbp_forum_class(); ?>>

	<li class="bbp-forum-info">
		<div class="youzify-forums-forum-item">
			<div class="youzify-forums-forum-icon"><?php echo youzify_bbp_get_forum_icon(); ?></div>
			<div class="youzify-forums-forum-head">
				<?php do_action( 'bbp_theme_before_forum_title' ); ?>
				<a class="youzify-forums-forum-title" href="<?php bbp_forum_permalink(); ?>"><?php bbp_forum_title(); ?></a>
				<?php if ( bbp_is_user_home() && bbp_is_subscriptions() ) : ?>

					<span class="bbp-row-actions">

						<?php do_action( 'bbp_theme_before_forum_subscription_action' ); ?>

						<?php bbp_forum_subscription_link( array( 'before' => '', 'subscribe' => '<span  data-youzify-tooltip="' . __( 'Subscribe', 'youzify' ) . '"><i class="fas fa-bell"></i></span>', 'unsubscribe' => '<span  data-youzify-tooltip="' . __( 'Unsubscribe', 'youzify' ) . '"><i class="fas fa-bell-slash"></i></span>' ) ); ?>

						<?php do_action( 'bbp_theme_after_forum_subscription_action' ); ?>

					</span>

				<?php endif; ?>
				<?php do_action( 'bbp_theme_after_forum_title' ); ?>
				<div class="youzify-forums-forum-desc">
					<?php do_action( 'bbp_theme_before_forum_description' ); ?>
					<?php bbp_forum_content(); ?>
					<?php do_action( 'bbp_theme_after_forum_description' ); ?>
				</div>
			</div>
			<?php do_action( 'bbp_theme_before_forum_sub_forums' ); ?>

			<?php bbp_list_forums( array( 'separator' => '' ) ); ?>

			<?php do_action( 'bbp_theme_after_forum_sub_forums' ); ?>
		</div>


		<?php bbp_forum_row_actions(); ?>

	</li>

	<li class="bbp-forum-topic-count"><i class="fas fa-file-alt" area-hidden="true"></i><?php bbp_forum_topic_count(); ?></li>

	<li class="bbp-forum-reply-count"><i class="fas fa-pencil-alt" area-hidden="true"></i><?php bbp_show_lead_topic() ? bbp_forum_reply_count() : bbp_forum_post_count(); ?></li>

	<li class="youzify-bbp-forum-freshness">

		<div class="youzify-bbp-freshness-data">

			<div class="youzify-bbp-freshness-author-img">
				<?php bbp_author_link( array( 'post_id' => bbp_get_forum_last_active_id(), 'size' => 40, 'type' => 'avatar' ) ); ?>
			</div>

			<div class="youzify-bbp-freshness-content">

				<?php do_action( 'bbp_theme_before_topic_author' ); ?>

				<div class="youzify-bbp-freshness-author"><?php bbp_author_link( array( 'post_id' => bbp_get_forum_last_active_id(), 'type' => 'name' ) ); ?></div>

				<?php do_action( 'bbp_theme_after_topic_author' ); ?>

				<?php do_action( 'bbp_theme_before_forum_freshness_link' ); ?>

				<div class="youzify-bbp-freshness-time"><?php bbp_forum_freshness_link(); ?></div>

				<?php do_action( 'bbp_theme_after_forum_freshness_link' ); ?>

			</div>

		</div>

	</li>

</ul><!-- #bbp-forum-<?php bbp_forum_id(); ?> -->
