<?php 
$performers = $args->event->performer_details;
$performers_text = ep_global_settings_button_title('Performers');
$performer_text = ep_global_settings_button_title('Performer');
if( count( $performers ) > 0 && empty( ep_get_global_settings( 'hide_performers_section' ) ) ) {?>
    <div class="ep-box-col-12 ep-my-3" id="ep-sl-event-peformers">
        <div class="ep-fs-6 ep-mt-1 ep-fw-bold">
            <?php echo esc_html( $performers_text ); ?>
        </div>
        <!-- <div class="ep-highlighted-performers ep-d-flex ep-justify-content-start ep-my-3">
        </div>
        <div class="ep-featured-performers ep-d-flex ep-justify-content-start ep-my-3">
        </div> -->
        
        <div class="ep-event-performers ep-flex-wrap ep-d-flex ep-mb-0" id="ep_single_event_performers">
            <?php foreach( $performers as $performer ){
                if( ! empty( $performer ) ) {?>
                    <div class="ep-event-performer ep-d-inline-flex ep-flex-column ep-py-2 ep-mr-3">
                        <a href="<?php echo esc_url( $performer->performer_url );?>" target="_blank" class="ep-performer-pic-wrapper ep-mx-auto">
                            <img class="ep-performer-img ep-rounded-1" src="<?php echo esc_url( $performer->image_url );?>" width="96" height="96">
                        </a>
                        <div class="ep-performer-content-wrapper ep-align-self-center ep-pt-2 ep-text-center ep-text-small">
                            <div class="ep-performer-name ep-fw-bold ep-text-small ep-text-truncate">
                                <a href="<?php echo esc_url( $performer->performer_url );?>" target="_blank" class="ep-button-text-color">
                                    <?php echo esc_html( $performer->name );?>
                                </a>
                            </div>
                            <div class="ep-performer-role ep-text-muted ep-text-small ep-text-truncate"><?php echo esc_html( $performer->em_role );?></div>
                        </div> 
                    </div> <?php
                }
            }?>
        </div>
    </div><?php
}?>