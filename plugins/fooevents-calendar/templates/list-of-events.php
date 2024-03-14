<?php
/**
 * Template for FooEvents Calendar event list
 *
 * @file    FooEvents Calendar event list shortcode
 * @link    https://www.fooevents.com
 * @package fooevents-calendar
 */

?>
<div class="fooevents-calendar-list">
	<?php if ( ! empty( $events ) ) : ?>    
		<?php foreach ( $events as $event ) : ?>
			<?php if ( is_array( $event ) ) : ?>
				<?php $thumbnail = get_the_post_thumbnail_url( $event['post_id'] ); ?>
		<div class="fooevents-calendar-list-item">
			<h3 class="fooevents-shortcode-title"><a href="<?php echo $event['url']; ?>"><?php echo esc_html( $event['title'] ); ?></a></h3>
				<?php if ( empty( $event['unformated_end_date'] ) ) : ?>
			<p class="fooevents-shortcode-date"><?php echo esc_attr( $event['unformated_date'] ); ?>
					<?php if ( isset( $event['time'] ) ) : ?>
						<?php echo ' ' . esc_attr( $event['time'] ); ?>
					<?php endif; ?>
			</p>
			<?php else : ?>
			<p class="fooevents-shortcode-date"><?php echo esc_attr( $event['unformated_date'] ); ?> - <?php echo esc_attr( $event['unformated_end_date'] ); ?></p>
			<?php endif; ?>
				<?php if ( ! empty( $thumbnail ) ) : ?>
			<img src="<?php echo esc_attr( $thumbnail ); ?>" class="fooevents-calendar-list-thumb"/>
			<?php endif; ?>
				<?php if ( ! empty( $event['desc'] ) ) : ?>
			<p class="fooevents-calendar-list-desc"><?php echo wp_kses_post( $event['desc'] ); ?></p>
			<?php endif; ?>
				<?php if ( ! empty( $event['in_stock'] ) && 'yes' === $event['in_stock'] ) : ?>
			<p><a class="button" href="<?php echo esc_attr( $event['url'] ); ?>" rel="nofollow"><?php echo esc_attr( $event['ticketTerm'] ); ?></a></p>
			<?php endif; ?>
			<div class="foo-clear"></div>
		</div>
		<div class="fooevents-calendar-clearfix"></div>
		<?php endif; ?>
	<?php endforeach; ?>
<?php else : ?>
	<?php esc_attr_e( 'No upcoming events.', 'fooevents-calendar' ); ?>
<?php endif; ?>    
</div>
