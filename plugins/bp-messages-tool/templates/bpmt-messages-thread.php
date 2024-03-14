<?php

/**
 * Single Message Thread
 * Custom template for BuddyPress Messages Tool
 * Cannot be overloaded
 *
 */

$back_link = bpmt_view_delete_back_link('back');

?>

<span class="highlight"><a href="<?php echo $back_link; ?>">&#8592; BACK</a></span>

<div id="message-thread">

	<?php $bpmt_get_thread = 'thread_id=' . $thread_id; ?>

	<?php if ( bp_thread_has_messages( $bpmt_get_thread ) ) : ?>

		<h3 id="message-subject"><?php bp_the_thread_subject(); ?></h3>

		<?php while ( bp_thread_messages() ) : bp_thread_the_message(); ?>

			<div class="message-box <?php bp_the_thread_message_css_class(); ?>">

				<div class="message-metadata">

					<?php bp_the_thread_message_sender_avatar( 'type=thumb&width=30&height=30' ); ?>

					<?php if ( bp_get_the_thread_message_sender_link() ) : ?>

						<strong><a href="<?php bp_the_thread_message_sender_link(); ?>" title="<?php bp_the_thread_message_sender_name(); ?>"><?php bp_the_thread_message_sender_name(); ?></a></strong>

					<?php else : ?>

						<strong><?php bp_the_thread_message_sender_name(); ?></strong>

					<?php endif; ?>

					<span class="activity"><?php bp_the_thread_message_time_since(); ?></span>


				</div><!-- .message-metadata -->

				<div class="message-content">

					<?php bp_the_thread_message_content(); ?>

				</div><!-- .message-content -->

				<div class="clear"></div>

			</div><!-- .message-box -->

		<?php endwhile; ?>


	<?php endif; ?>

</div>

<span class="highlight"><a href="<?php echo $back_link; ?>">&#8592; BACK</a></span>
