<?php
$event_gallery = ( ! empty( $args->event->em_gallery_image_ids ) ? $args->event->em_gallery_image_ids : '' );
if( ! empty( $args->event->image_url ) || ! empty( $event_gallery ) ) {?>
    <div class="ep-box-row">
        <div class="ep-box-col-12" id="ep_single_event_image">
            <div class="ep-single-event-slide-container ep-text-center"><?php
                if( empty( $event_gallery ) ) {?>
                    <img src="<?php echo esc_url( $args->event->image_url );?>" alt="<?php echo esc_attr( $args->event->name ); ?>" class="ep-d-block" /><?php
                } else{?>
                    <ul class="ep-rslides ep-m-0 ep-p-0" id="ep_single_event_image_gallery">
                        <?php
                        $event_gallery = explode( ',', $event_gallery );
                        if( ! empty( $args->event->image_url ) && ! empty( has_post_thumbnail( $args->event->em_id ) ) ) {?>
                            <li class="ep-m-0 ep-p-0">
                                <img src="<?php echo esc_url( $args->event->image_url );?>" alt="<?php echo esc_attr( $args->event->name );?>" class="ep-d-block" >
                            </li><?php
                        }
                        foreach( $event_gallery as $image ){
                            $gal_url = wp_get_attachment_image_url( $image, 'large' );
                            if( ! empty( $gal_url ) ) {?>
                                <li><img src="<?php echo esc_url( $gal_url );?>" alt="<?php echo esc_attr( $args->event->name );?>" ></li><?php
                            }
                        }?>
                    </ul><?php
                }?>      
                <div class="ep-single-event-nav"></div>
            </div>
        </div>
    </div><?php
}?>