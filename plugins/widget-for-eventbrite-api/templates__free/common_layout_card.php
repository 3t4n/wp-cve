<?php
/**
 * Front end display of shortcode loop
 * can be overridden in child themes / themes or in wp-content/widget-for-eventbrite-api folder if you don't have a child theme and you don't want to lose changes due to themes updates
 *
 * To customise create a folder in your theme directory called widget-for-eventbrite-api and a modified version of this file or any template_parts renamed as appropriate
 *
 * The main structure is in get_template_part( 'loop__free_widget' );
 *
 * @var mixed $data Custom data for the template.
 * phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped -- template files escaped at output
 */

$data->template_loader->get_template_part( 'paginate_links_top' . $data->event->plan ); ?>
    <section <?php echo $data->utilities->get_section_attrs( $data ); ?>>
		<?php
		$data->args['readmore'] = false;
		if ( false !== $data->events && $data->events->have_posts() ) {
			while ( $data->events->have_posts() ) {
				$data->events->the_post();
				$data->event->booknow  = $data->utilities->get_booknow_link( $data->args );
				$data->event->cta      = $data->utilities->get_cta( $data->args );
				$data->event->classes  = $data->utilities->get_event_classes();
				$data->event->classes  = ' ' . $data->event->cta->availability_class;
				$data->template_loader->get_template_part( 'loop_card' );
			}
		} else {
			$data->template_loader->get_template_part( 'not_found' . $data->event->plan );
		}
		?>
    </section>
<?php $data->template_loader->get_template_part( 'paginate_links_bottom' . $data->event->plan );
$data->template_loader->get_template_part( 'full_modal' );
