<?php
/**
 * @var mixed $data Custom data for the template.
 * phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped -- template files escaped at output
 */
?>
<div class="eaw-li__wrap">
    <div class="eaw-li__flex eaw-clearfix <?php
	echo esc_attr( $data->utilities->get_element( 'thumb_align', $data->args ) ) . ' ' . $data->event->classes;
	?>">
		<?php $data->template_loader->get_template_part( 'thumb_widget' . $data->event->plan); ?>
        <div class="eaw-content-wrap">
			<?php $data->template_loader->get_template_part( 'title_widget' . $data->event->plan); ?>
			<?php $data->template_loader->get_template_part( 'date_widget' ); ?>
			<?php $data->template_loader->get_template_part( 'venue' . $data->event->plan ); ?>
			<?php $data->template_loader->get_template_part( 'location' . $data->event->plan); ?>
			<?php $data->template_loader->get_template_part( 'prices' . $data->event->plan); ?>
			<?php $data->template_loader->get_template_part( 'availability' . $data->event->plan); ?>
			<?php $data->template_loader->get_template_part( 'excerpt_widget' ); ?>
			<?php $data->template_loader->get_template_part( 'booknow' . $data->event->plan); ?>
        </div>
    </div>
</div>

