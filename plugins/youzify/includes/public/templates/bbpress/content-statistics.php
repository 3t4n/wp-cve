<?php

/**
 * Statistics Content Part
 *
 * @package bbPress
 * @subpackage Theme
 */

// Get the statistics
$stats = bbp_get_statistics(); ?>

<div class="youzify-forums-statistics-items" role="main">

	<?php do_action( 'bbp_before_statistics' ); ?>

	<div class="youzify-forums-statistics-item youzify-statistics-registered-user">
		<div class="youzify-forums-statistics-icon">
			<i class="fas fa-users"></i>
		</div>
		<div class="youzify-forums-statistics-content">
			<div class="youzify-forums-statistics-nbr"><?php echo esc_html( $stats['user_count'] ); ?></div>
			<div class="youzify-forums-statistics-desc"><?php _e( 'Registered Users', 'youzify' ); ?></div>
		</div>
	</div>

	<div class="youzify-forums-statistics-item youzify-statistics-forums">
		<div class="youzify-forums-statistics-icon">
			<i class="far fa-comments"></i>
		</div>
		<div class="youzify-forums-statistics-content">
			<div class="youzify-forums-statistics-nbr"><?php echo esc_html( $stats['forum_count'] ); ?></div>
			<div class="youzify-forums-statistics-desc"><?php _e( 'Forums', 'youzify' ); ?></div>
		</div>
	</div>

	<div class="youzify-forums-statistics-item youzify-statistics-topics">
		<div class="youzify-forums-statistics-icon">
			<i class="fas fa-pencil-alt"></i>
		</div>
		<div class="youzify-forums-statistics-content">
			<div class="youzify-forums-statistics-nbr"><?php echo esc_html( $stats['topic_count'] ); ?></div>
			<div class="youzify-forums-statistics-desc"><?php _e( 'Topics', 'youzify' ); ?></div>
		</div>
	</div>

	<div class="youzify-forums-statistics-item youzify-statistics-replies">
		<div class="youzify-forums-statistics-icon">
			<i class="fas fa-comment-dots"></i>
		</div>
		<div class="youzify-forums-statistics-content">
			<div class="youzify-forums-statistics-nbr"><?php echo esc_html( $stats['reply_count'] ); ?></div>
			<div class="youzify-forums-statistics-desc"><?php _e( 'Replies', 'youzify' ); ?></div>
		</div>
	</div>

	<div class="youzify-forums-statistics-item youzify-statistics-topic-tags">
		<div class="youzify-forums-statistics-icon">
			<i class="fas fa-tags"></i>
		</div>
		<div class="youzify-forums-statistics-content">
			<div class="youzify-forums-statistics-nbr"><?php echo esc_html( $stats['topic_tag_count'] ); ?></div>
			<div class="youzify-forums-statistics-desc"><?php _e( 'Topic Tags', 'youzify' ); ?></div>
		</div>
	</div>

	<?php if ( ! empty( $stats['empty_topic_tag_count'] ) ) : ?>

		<div class="youzify-forums-statistics-item youzify-statistics-empty-topic-tags">
			<div class="youzify-forums-statistics-icon">
				<i class="fas fa-tag"></i>
			</div>
			<div class="youzify-forums-statistics-content">
				<div class="youzify-forums-statistics-nbr"><?php echo esc_html( $stats['empty_topic_tag_count'] ); ?></div>
				<div class="youzify-forums-statistics-desc"><?php _e( 'Empty Topic Tags', 'youzify' ); ?></div>
			</div>
		</div>

	<?php endif; ?>

	<?php if ( !empty( $stats['topic_count_hidden'] ) ) : ?>

		<div class="youzify-forums-statistics-item youzify-statistics-hidden-topics">
			<div class="youzify-forums-statistics-icon">
				<i class="fas fa-file-alt"></i>
			</div>
			<div class="youzify-forums-statistics-content">
				<div class="youzify-forums-statistics-nbr"><?php echo esc_html( $stats['topic_count_hidden'] ); ?></div>
				<div class="youzify-forums-statistics-desc"><?php _e( 'Hidden Topics', 'youzify' ); ?></div>
			</div>
		</div>

	<?php endif; ?>

	<?php if ( !empty( $stats['reply_count_hidden'] ) ) : ?>

		<div class="youzify-forums-statistics-item youzify-statistics-hidden-replies">
			<div class="youzify-forums-statistics-icon">
				<i class="far fa-comment"></i>
			</div>
			<div class="youzify-forums-statistics-content">
				<div class="youzify-forums-statistics-nbr"><?php echo esc_html( $stats['reply_count_hidden'] ); ?></div>
				<div class="youzify-forums-statistics-desc"><?php _e( 'Hidden Replies', 'youzify' ); ?></div>
			</div>
		</div>

	<?php endif; ?>

	<?php do_action( 'bbp_after_statistics' ); ?>

</div>

<?php unset( $stats );