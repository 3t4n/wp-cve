<?php 
if( ! empty( $args->event->em_venue ) ) {
    $event_venue = EventM_Factory_Service::ep_get_venue_by_id( $args->event->em_venue );
    if( ! empty( $event_venue ) ) {
        $other_events = EventM_Factory_Service::get_upcoming_event_by_venue_id( $args->event->em_venue, array( $args->event->id ) );?>
        <div class="ep-box-col-12 mt-3" id="ep_sl_event_venue_detail">
            <div class="ep-sl-event-venue-box ep-overflow-hidden ep-py-2">
                <div id="ep-sl-venue-map">
                    <span class="ep-text-small ep-text-muted" id="ep-sl-venue-details">
                        <span class="material-icons-outlined" style="vertical-align:bottom;">place</span>
                        <span class="ep-text-dark ep-fw-bold ep-mr-1">
                            <?php echo esc_html( $event_venue->name );?>
                        </span>
                        <?php if( ! empty( $event_venue->em_address ) && ! empty( $event_venue->em_display_address_on_frontend ) ) {?>
                            <span id="ep_single_event_venue_address">
                                <?php echo esc_html( $event_venue->em_address );?>
                            </span><?php
                        }?>
                        <span id="ep_sl_venue_more" class="material-icons-outlined ep-cursor ep-bg-secondary ep-bg-opacity-10 ep-ml-2 ep-rounded-circle">expand_more</span>
                    </span>
                </div>
                <div id="venue_hidden_details" class="ep-box-row ep-mb-3 ep-mt-3 ep-pt-3 ep-border-top" style="display: none">
                    <div class="ep-box-col-12">
                        <ul class="ep-nav-pills ep-mx-0 ep-p-0 ep-mb-3 ep-venue-details-tabs" role="tablist">
                            <li class="ep-tab-item ep-mx-0" role="presentation"><a href="javascript:void(0)" data-tag="ep-sl-venue" class="ep-tab-link ep-tab-active"><?php esc_html_e( 'Details', 'eventprime-event-calendar-management' );?></a></li>
                            <?php if( ! empty( ep_get_global_settings( 'gmap_api_key' ) ) && empty( ep_get_global_settings( 'hide_map_tab' ) ) ) {?>
                                <li class="ep-tab-item ep-mx-0" role="presentation"><a href="javascript:void(0)" data-tag="ep-sl-address-map" class="ep-tab-link"><?php esc_html_e( 'Map', 'eventprime-event-calendar-management' );?></a></li><?php
                            }?>
                            <?php if(empty(ep_get_global_settings('hide_weather_tab'))){?>
                                <li class="ep-tab-item ep-mx-0" role="presentation"><a href="javascript:void(0)" data-tag="ep-sl-venue-weather" class="ep-tab-link"><?php esc_html_e( 'Weather', 'eventprime-event-calendar-management' );?></a></li><?php 
                            }
                            $other_display = 'style=display:none;';
                            if( count( $other_events ) > 0 ) {
                                $other_display = '';
                            }
                            if(empty(ep_get_global_settings('hide_other_event_tab'))){?>
                                <li class="ep-tab-item ep-mx-0" role="presentation" id="ep_event_venue_other_event_tab" <?php echo esc_attr( $other_display );?>>
                                    <a href="javascript:void(0)" data-tag="ep-sl-other-events" class="ep-tab-link">
                                        <?php esc_html_e( 'Other Events', 'eventprime-event-calendar-management' );?>
                                    </a>
                                </li><?php 
                            } ?>
                        </ul>    

                        <div id="ep-tab-container" class="ep-box-w-100">
                            <div class="ep-tab-content ep-sl-venue" id="ep-sl-venue"  role="tabpanel" >                                        
                                <div class="ep-box-row">
                                    <div class="ep-box-col-4">
                                        <?php 
                                        $image = EP_BASE_URL . 'includes/assets/images/dummy_image.png';
                                        if ( isset( $event_venue->em_gallery_images[0] ) && !empty( $event_venue->em_gallery_images[0] ) ) {
                                            $image = wp_get_attachment_url( $event_venue->em_gallery_images[0] );
                                        }?>
                                        <div class="ep-venue-card-thumb ep-rounded-1">
                                        <img src="<?php echo esc_url( $image );?>" alt="<?php echo esc_attr( $args->event->name ); ?>" class="ep-rounded-1 primary" style="max-width:100%;" id="ep_event_venue_main_image" />
                                        </div>
                                    </div>
                                    <div class="ep-box-col-8">
                                        <?php if( ! empty( $args->event->em_venue ) ) {?>
                                            <div class="ep-fs-6 ep-fw-bold">
                                                <?php 
                                                echo esc_html( $event_venue->name );
                                                $venue_url = $args->event->venue_details->venue_url;
                                                ?>
                                                <a href="<?php echo esc_url( $venue_url );?>" target="_blank" id="ep_event_venue_url">
                                                    <span class="material-icons-outlined ep-fs-6 ep-text-primary ep-align-text-bottom">open_in_new</span>
                                                </a>
                                            </div>
                                            <?php if( ! empty( $event_venue->em_address ) && ! empty( $event_venue->em_address->em_display_address_on_frontend ) ) {?>
                                                <p class="ep-text-muted ep-text-small" id="ep_event_venue_address">
                                                    <?php echo esc_html( $event_venue->em_address );?>
                                                </p><?php
                                            }
                                        }?>
                                        <p class="ep-text-small ep-content-truncate" id="ep_event_venue_description">
                                            <?php echo wp_kses_post( $event_venue->description );?>
                                        </p>
                                    </div>
                                    <!-- Gallery Images -->
                                    <?php $em_venue_gallery = ( ! empty( $event_venue->em_gallery_images ) ? $event_venue->em_gallery_images : array() );
                                    if( ! empty( $em_venue_gallery ) && count( $em_venue_gallery ) > 1 ) {?>
                                        <div class="owl-carousel owl-theme selected ep-box-col-12 thumbnails ep-box-col-12 ep-mt-2 ep-d-flex justify-content-start ep-image-slider"><?php
                                            foreach( $em_venue_gallery as $gal_id ) {
                                                $attachment_image_url = wp_get_attachment_url( $gal_id );
                                                if( $attachment_image_url ) {?>
                                                    <div class="thumbnail ep-d-inline-flex ep-mr-2" data-image_url="<?php echo esc_url( $attachment_image_url );?>">
                                                        <img class="ep-rounded-1" src="<?php echo esc_url( $attachment_image_url );?>" style="max-width:140px;">
                                                    </div><?php
                                                }
                                            }?>
                                        </div><?php
                                    }?>
                                </div>
                            </div>
                            <?php if( ! empty( ep_get_global_settings( 'gmap_api_key' ) ) && empty( ep_get_global_settings( 'hide_map_tab' ) ) ) {?>
                                <div class="ep-tab-content ep-item-hide" id="ep-sl-address-map" role="tabpanel">
                                    <?php if( ! empty( $event_venue->em_address ) ) {?>
                                        <div id="ep-event-venue-map" data-venue_address="<?php echo esc_attr( $event_venue->em_address );?>" data-venue_lat="<?php echo esc_attr( $event_venue->em_lat );?>" data-venue_lng="<?php echo esc_attr( $event_venue->em_lng );?>" data-venue_zoom_level="<?php echo esc_attr( $event_venue->em_zoom_level );?>" style="height: 400px;"></div><?php
                                    } else{
                                        esc_html_e( 'No address found', 'eventprime-event-calendar-management' );
                                    }?>
                                </div><?php
                            }?>
                            <!-- Weather -->
                            <?php if( empty( ep_get_global_settings( 'hide_weather_tab' ) ) ) {?>
                                <div class="ep-tab-content ep-item-hide" id="ep-sl-venue-weather" role="tabpanel">
                                    <?php //do_action( 'ep_event_detail_weather_data', $event_venue );?>
                                </div>
                            <?php }?>
                            <!-- Other Events -->
                            <?php if(empty(ep_get_global_settings('hide_other_event_tab'))){?>
                            <div class="ep-tab-content ep-item-hide" id="ep-sl-other-events" role="tabpanel">
                                <?php if( count( $other_events ) > 0 ) {
                                    $new_window = ( ! empty( ep_get_global_settings( 'open_detail_page_in_new_tab' ) ) ? 'target="_blank"' : '' );
                                    $event_controller = EventM_Factory_Service::ep_get_instance( 'EventM_Event_Controller_List' );?>
                                    <?php foreach( $other_events as $event ) {?>
                                        <div class="ep-box-row ep-align-items-center ep-mb-4 ep-pb-2 ep-border-bottom">
                                            <div class="ep-box-col-2">
                                                <a href="<?php echo esc_url( $event->event_url );?>" <?php echo esc_attr( $new_window );?> class="ep-sl-other-event-link">
                                                    <img class="ep-rounded-circle ep-sl-other-event-img" src="<?php echo esc_url( $event->image_url );?>" width="60px" height="60px">
                                                </a>
                                            </div>
                                            <div class="ep-box-col-7">
                                                <a href="<?php echo esc_url( $event->event_url );?>" <?php echo esc_attr( $new_window );?>>
                                                    <div class="ep-fw-bold ep-text-small"><?php echo esc_html( $event->name );?></div>
                                                </a>
                                                <div class="ep-text-small ep-text-muted ep-desc-truncate"><?php echo wp_trim_words( wp_kses_post( $event->description ), 35 );?></div>
                                            </div>
                                            <div class="ep-box-col-3">
                                                <?php 
                                                $view_details_text = ep_global_settings_button_title('View Details');
                                                if( check_event_has_expired( $event ) ) {?>
                                                    <a href="<?php echo esc_url( $event->event_url );?>" <?php echo esc_attr( $new_window );?>>
                                                        <input type="button" class="ep-btn ep-btn-outline-primary ep-btn-sm" value="<?php echo esc_html( $view_details_text ); ?>">
                                                    </a><?php
                                                } else{
                                                    if( ! empty( $event->em_enable_booking ) ) {
                                                        if( $event->em_enable_booking == 'bookings_off' ) {?>
                                                            <a href="<?php echo esc_url( $event->event_url );?>" <?php echo esc_attr( $new_window );?>>
                                                                <input type="button" class="ep-btn ep-btn-outline-primary ep-btn-sm" value="<?php echo esc_html( $view_details_text ); ?>">
                                                            </a><?php
                                                        } elseif( $event->em_enable_booking == 'external_bookings' ) {
                                                            if( empty( $event->em_custom_link_new_browser ) ) {
                                                                $new_window = '';
                                                            }?>
                                                            <a href="<?php echo esc_url( $event->event_url );?>" <?php echo esc_attr( $new_window );?>>
                                                                <input type="button" class="ep-btn ep-btn-outline-primary ep-btn-sm" value="<?php echo esc_html( $view_details_text ); ?>">
                                                            </a><?php
                                                        } else{
                                                            // check for booking status 
						                                    if( ! empty( $event->all_tickets_data ) ) {
                                                                $check_for_booking_status = $event_controller->check_for_booking_status( $event->all_tickets_data, $event );
                                                                if( ! empty( $check_for_booking_status ) ) {
                                                                    if( $check_for_booking_status['status'] == 'not_started' ) {?>
                                                                        <a href="<?php echo esc_url( $event->event_url );?>" <?php echo esc_attr( $new_window );?>>
                                                                            <input type="button" class="ep-btn ep-btn-outline-primary ep-btn-sm" value="<?php echo esc_html( $check_for_booking_status['message'] );?>">
                                                                        </a><?php
                                                                    } elseif( $check_for_booking_status['status'] == 'off' ) {?>
                                                                        <a href="<?php echo esc_url( $event->event_url );?>" <?php echo esc_attr( $new_window );?>>
                                                                            <input type="button" class="ep-btn ep-btn-outline-primary ep-btn-sm" value="<?php echo esc_html( $check_for_booking_status['message'] );?>">
                                                                        </a><?php
                                                                    } else{?>
                                                                        <a href="<?php echo esc_url( $event->event_url );?>" <?php echo esc_attr( $new_window );?>>
                                                                            <input type="button" class="ep-btn ep-btn-outline-primary ep-btn-sm" data-event_id="<?php echo esc_html( $event->id );?>" value="<?php echo  esc_html__( $check_for_booking_status['message'], 'eventprime-event-calendar-management' );?>">
                                                                        </a><?php
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }?>
                                            </div>
                                        </div><?php
                                    }
                                }?>
                            </div>
                            <?php } ?>
                        </div>   
                    </div>
                </div>
            </div>
        </div><?php
    }
}?>
