<div class="ep-tab-content ep-item-hide" id="ep-list-my-list" role="tabpanel" aria-labelledby="#ep-list-wish-list">
    <?php if( ! empty( $args->wishlisted_events ) && count( $args->wishlisted_events ) ) {?>
        <div class="ep-box-row">
            <div class="ep-box-col-12 ep-border-left ep-border-3 ep-ps-3 ep-border-warning">
                <span class="ep-text-uppercase ep-fw-bold ep-text-smal">
                    <?php esc_html_e( 'My Wishlists', 'eventprime-event-calendar-management');?>
                </span>
            </div>
        </div>
        <!-- <div class="ep-box-row ep-mb-4">
            <div class="ep-box-col-12 ep-text-center">
                <div class="ep-btn-group ep-btn-group-sm">
                    <a href="#" class="ep-btn ep-btn-outline-dark active" aria-current="page">Wishlisted</a>
                    <a href="#" class="ep-btn ep-btn-outline-dark">Following</a>
                </div>
            </div>
        </div> -->
        <div class="ep-box-row ep-mb-2">
            <div class="ep-box-col-12 ep-text-small ep-d-flex ep-justify-content-between ep-p-0">
                <div>
                    <!-- <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" checked="">
                    <label class="form-check-label" for="flexCheckDefault"> Show Past Events </label> -->
                </div>
                <div>
                    <span class="ep-fw-bold" id="ep_wishlist_event_count"><?php echo esc_html( count( $args->wishlisted_events ) );?></span>
                    <span class=""><?php esc_html_e( 'events found', 'eventprime-event-calendar-management');?></span>
                </div>
            </div>
        </div>
        <?php foreach( $args->wishlisted_events as $event_data ) {
            if( isset( $event_data['event'] ) && ! empty( $event_data['event'] ) && isset( $event_data['event']->name ) ) {?>
                <div class="ep-box-row ep-my-booking-row ep-border ep-rounded ep-overflow-hidden ep-text-small ep-mb-4" id="ep_profile_wishlist_event_<?php echo esc_attr($event_data['event']->id);?>">
                    <div class="ep-box-col-2 ep-m-0 ep-p-0">
                        <img class="ep-event-card-img" src="<?php echo esc_url( $event_data['event']->image_url );?>" style="width:100%;" alt="<?php echo esc_html( $event_data['event']->name );?>">
                    </div>
                    <div class="ep-box-col-6 ep-ps-4 ep-d-flex ep-align-items-center ep-justify-content-between">
                        <div>
                            <div class=""><a href="<?php echo esc_url($event_data['event']->event_url);?>"><?php echo esc_html( $event_data['event']->name );?></a></div>
                            <?php if( ! empty( $event_data['event']->venue_details ) && isset($event_data['event']->venue_details->em_address) ) {?>
                                <div class="ep-text-muted ep-text-small">
                                    <?php echo esc_html( isset($event_data['event']->venue_details->em_address) ? $event_data['event']->venue_details->em_address : '' );?>
                                </div><?php
                            }?>
                        </div>
                    </div>
                    <div class="ep-box-col-4 ep-d-flex ep-justify-content-between ep-align-items-center">
                        <div class="">
                            <?php if( ! empty( $event_data['booking'] ) ) {?>
                                <div class="ep-mb-1">
                                    <span class="ep-bg-success ep-p-1 ep-text-white ep-rounded-1 ep-text-small"><?php esc_html_e( 'Booked', 'eventprime-event-calendar-management');?></span>
                                </div><?php
                            }?>
                        </div>
                        <div class="ep-text-end">
                            <div class="ep-btn-group">
                                <a href="javascript:void(0);" class="ep-btn ep-text-danger ep-event-action ep_event_wishlist_action ep-px-2" id="ep_event_wishlist_action_<?php echo esc_attr( $event_data['event']->id );?>" data-event_id="<?php echo esc_attr( $event_data['event']->id );?>" data-remove_row="ep_profile_wishlist_event_<?php echo esc_attr( $event_data['event']->id );?>" title="<?php echo esc_attr( ep_global_settings_button_title( 'Remove From Wishlist' ) );?>">
                                    <span class="material-icons-round ep-fs-5">remove_circle</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div><?php
            }
        }
    } else{?>
        <div class="ep-box-row">
            <div class="ep-box-col-12 ep-border-left ep-border-3 ep-ps-3 ep-border-warning">
                <span class="text-uppercase fw-bold small">
                    <?php esc_html_e( 'No events found', 'eventprime-event-calendar-management');?>
                </span>
            </div>
        </div><?php
    }?>
</div>