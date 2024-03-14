<?php
/**
 * Template for FooEvents Calendar Event Shortcode
 *
 * @file    FooEvents Calendar event shortcode output
 * @link    https://www.fooevents.com
 * @package fooevents-calendar
 */

?>
<?php if ( ! empty( $thumbnail ) ) : ?>
<img src="<?php echo esc_attr( $thumbnail ); ?>" />
<?php endif; ?>
<a href="
<?php
$permalink = get_the_permalink( $event->ID );
echo esc_attr( $permalink );
?>
"><h3><?php echo esc_attr( $event->post_title ); ?></h3></a>
<?php if ( ! empty( $event->post_excerpt ) ) : ?>
	<?php echo wp_kses_post( $event->post_excerpt ); ?>
<?php endif; ?>
<p><a class="button" href="<?php echo esc_attr( $permalink ); ?>" rel="nofollow"><?php echo esc_attr( $ticket_term ); ?></a></p>
