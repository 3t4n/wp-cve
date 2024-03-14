<?php 
$organizers = $args->event->organizer_details;
if( count( $organizers ) > 0 && empty( ep_get_global_settings( 'hide_organizers_section' ) ) ) {
    $organized_by_label = ep_global_settings_button_title( 'Organized by' );?>
    <div class="ep-box-col-12 ep-my-3 ep-d-flex ep-items-center" id="ep-sl-event-meta">
        <span class="ep-fw-bold ep-text-small ep-white-space ep-mr-1"><?php echo esc_html( $organized_by_label );?></span>
        <span class="material-icons-outlined ep-fs-6 ep-mr-1 ep-align-middle ep-text-warning">arrow_forward_ios</span>
        <span class="ep-text-smalll ep-d-inline-flex ep-items-center ep-flex-wrap" id="ep_single_event_organizers">
            <?php foreach( $organizers as $organizer ) {
                if( ! empty( $organizer ) ) {?>
                    <a href="<?php echo esc_url( $organizer->organizer_url );?>" target="_blank" class="ep-text-dark">
                        <span class="ep-text-small ep-my-2 ep-mr-4 ep-d-flex ep-items-center ">
                            <img src="<?php echo esc_url( $organizer->image_url ); ?>" alt="<?php esc_attr( $organizer->name ); ?>" class="ep-inline-block ep-rounded-circle ep-object-cover-fit ep-mr-1" style="width:24px; height: 24px;">
                            <span class="ep-align-middle">
                                <?php echo esc_html( $organizer->name );?>
                            </span>
                        </span>
                    </a><?php
                }
            }?>
        </span>
    </div><?php
}?>