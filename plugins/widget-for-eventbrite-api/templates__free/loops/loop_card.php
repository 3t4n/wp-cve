<?php
/**
 * @var mixed $data Custom data for the template.
 * phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped -- template files escaped at output
 */
?>

<article class="wfea-card-list-item">
    <div class="wfea-card-item">
    	<?php $data->template_loader->get_template_part( 'thumb_widget' . $data->event->plan ); ?>
        <div class="eaw-content-wrap">
            <?php $data->template_loader->get_template_part( 'calendar_date__free' ); ?>
            <div class="eaw-content-block">
                <?php $data->template_loader->get_template_part( 'title_widget' . $data->event->plan ); ?>
                <?php $data->template_loader->get_template_part( 'date_widget'  ); ?>
	            <?php $data->template_loader->get_template_part( 'venue' . $data->event->plan ); ?>
	            <?php $data->template_loader->get_template_part( 'location' . $data->event->plan ); ?>
                <div class="eaw-buttons">
                    <?php if ( $data->utilities->get_element('long_description_modal', $data->args) ) {
                        $data->template_loader->get_template_part( 'full_modal_details_button' );
                     } else { ?>
                    <button class="eaw-button-details"><?php echo apply_filters('wfea_layout_card_details',esc_html__( 'Details', 'widget-for-eventbrite-api')) ; ?>
                    <div class="eaw-card-details">
	                    <?php  $data->template_loader->get_template_part( 'excerpt_widget' ); ?>
                    </div>
                    </button>
                    <?php } ?>
                    <?php $data->template_loader->get_template_part( 'booknow' . $data->event->plan ); ?>
                </div>
            </div>
        </div>
    </div>
</article>


