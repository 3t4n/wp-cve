<?php
/**
 * View: Frontend Event Submission
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/eventprime/events/frontend-submission/venues.php
 *
 */
?>
<?php $selected_venue = isset($args->event) && !empty($args->event->em_venue) ? esc_attr($args->event->em_venue): '';?>
<?php if(isset($args->fes_event_location) && !empty($args->fes_event_location)):?>
    <?php $venue_text = ep_global_settings_button_title('Venue'); ?>
    <div class="ep-fes-section ep-mb-4 ep-border ep-p-4 ep-shadow-sm ep-rounded-1">
        <div class="ep-fes-section-title ep-fs-5 ep-fw-bold ep-mb-3">
            <?php echo esc_html( $venue_text );?>
            <?php if(isset($args->fes_event_location_req) && !empty($args->fes_event_location_req)):?>
                <span class="required">*</span>
            <?php endif;?>
        </div>
        
        <div class="ep-form-row ep-form-group ep-mb-3">
            <select name="em_venue" id="ep_venue" class="ep-form-input ep-input-select ep-form-control" onchange="fes_event_sites_changed(this)">
                <option value=""><?php echo esc_html__('Select', 'eventprime-event-calendar-management') . ' ' . esc_html( $venue_text ) ;?></option>
                <?php if( ! empty( $args->event_venues ) ):
                    foreach($args->event_venues as $event_venue):?>
                        <option value="<?php echo esc_attr($event_venue->id);?>" <?php selected($selected_venue,$event_venue->id);?>><?php echo esc_attr($event_venue->name);?></option><?php 
                    endforeach;
                endif;
                
                if(isset($args->fes_new_event_location) && !empty($args->fes_new_event_location)):?>
                    <option value="new_venue"><?php echo esc_html__('Add New', 'eventprime-event-calendar-management'). ' ' . esc_html( $venue_text ) ;?></option>
                <?php endif;?>
            </select>
        </div>
        <?php if(isset($args->fes_new_event_location) && !empty($args->fes_new_event_location)):?>
            <?php
            $gmap_api_key = ep_get_global_settings('gmap_api_key');
            if(!empty($gmap_api_key)){
                wp_enqueue_script('google_map_key', 'https://maps.googleapis.com/maps/api/js?key='.$gmap_api_key.'&libraries=places', array(), EVENTPRIME_VERSION);
            }?>
            <div class="ep-form-row-child" id="ep_add_new_event_sites_child" style="display:none;">
                <div class="ep-form-row ep-form-group ep-mb-3">
                
                        <label for="ep_new_venue" class="ep-form-label">
                            <?php echo esc_html__( 'New', 'eventprime-event-calendar-management' ) . ' '. esc_html( $venue_text ) . ' '. esc_html__( 'Name', 'eventprime-event-calendar-management' ); ?>
                            <span class="required">*</span>
                        </label>
                        <input type="text" name="new_venue" id="ep_new_venue" class="ep-form-input ep-input-text ep-form-control" value="" />
                    
                </div>
                <?php if(!empty($gmap_api_key)) : ?>
                    <div class="ep-form-row ep-form-group ep-mb-3 ep-venue-admin-address">
                            <label for="ep_address" class="ep-form-label">
                                <?php esc_html_e( 'Address', 'eventprime-event-calendar-management' ); ?>
                            </label>
                            <input id="em-pac-input" name="em_address" class="em-map-controls" type="text">
                            <div id="map" style="height: 200px;"></div>

                            <div id="type-selector" class="em-map-controls" style="display:none">
                                <input type="radio" name="em_type" id="changetype-all" checked="checked">
                                <label for="changetype-all"><?php esc_html_e('All', 'eventprime-event-calendar-management'); ?></label>

                                <input type="radio" name="em_type" id="changetype-establishment">
                                <label for="changetype-establishment"><?php esc_html_e('Establishments', 'eventprime-event-calendar-management'); ?></label>

                                <input type="radio" name="em_type" id="changetype-address">
                                <label for="changetype-address"><?php esc_html_e('Addresses', 'eventprime-event-calendar-management'); ?></label>

                                <input type="radio" name="em_type" id="changetype-geocode">
                                <label for="changetype-geocode"><?php esc_html_e('Geocodes', 'eventprime-event-calendar-management'); ?></label>
                            </div>
                       
                    </div>
                    <div class="ep-form-row ep-form-group ep-mb-3">
                            <div class="emnote emeditor">
                                <i class="fa fa-info-circle" aria-hidden="true"></i>
                                <?php echo esc_html__('Mark map location for the Event', 'eventprime-event-calendar-management' ) . ' ' . esc_html( $venue_text ) . '. '. esc_html__( 'This will be displayed on Event page.', 'eventprime-event-calendar-management' ); ?>
                            </div>
                            <div class="ep-location-hidden" style="display:none;">
                                <input type="text" name="em_lat" id="em_lat" placeholder="<?php esc_html_e('Latitude', 'eventprime-event-calendar-management');?>">
                                <input type="text" name="em_lng" id="em_lng" placeholder="<?php esc_html_e('Longitude', 'eventprime-event-calendar-management');?>">
                                <input type="text" name="em_state" id="em_state" placeholder="<?php esc_html_e('State', 'eventprime-event-calendar-management');?>">
                                <input type="text" name="em_country" id="em_country" placeholder="<?php esc_html_e('Country', 'eventprime-event-calendar-management');?>">
                                <input type="text" name="em_postal_code" id="em_postal_code" placeholder="<?php esc_html_e('Postal Code', 'eventprime-event-calendar-management');?>">
                                <input type="number" name="em_zoom_level" id="em_zoom_level" placeholder="<?php esc_html_e('Zoom Level', 'eventprime-event-calendar-management');?>" min="0" step="1">
                            </div>
                    </div>
                <?php else:?>
                    <p class="emnote emeditor ep-text-danger">
                        <?php esc_html_e( 'Location Map field is not active as Google Map API is not configured. You can configure it from the Settings->General->Third-Party.', 'eventprime-event-calendar-management'); ?>
                    </p>
                    <div class="ep-form-row ep-form-group ep-mb-3">
                            <label for="ep_address" class="ep-form-label">
                                <?php esc_html_e('Address', 'eventprime-event-calendar-management'); ?>
                                <span class="required">*</span>
                            </label>
                            <input id="em-pac-input" name="em_address" class="em-map-controls" type="text">
                    </div>
                <?php endif; ?>
                <div class="ep-form-row ep-form-group ep-mb-3">
                        <label for="display_address_on_frontend" class="ep-form-label">
                            <?php esc_html_e('Display Address', 'eventprime-event-calendar-management'); ?>
                        </label>
                        <input type="checkbox" id="display_address_on_frontend" name="em_display_address_on_frontend" />
                </div>
                <div class="ep-form-row ep-form-group ep-mb-3">
                        <label for="display_address_on_frontend" class="ep-form-label">
                            <?php esc_html_e('Established', 'eventprime-event-calendar-management'); ?>
                        </label>
                        <input type="text" name="em_established" id="em_established" class="ep-form-control ep-mr-3 epDatePicker" placeholder="<?php esc_html_e( 'Established', 'eventprime-event-calendar-management' );?>" autocomplete="off">
                       <!-- <input type="button" class="button" value="<?php esc_html_e( 'Reset', 'eventprime-event-calendar-management' );?>" id="em_venue_esh_reset" /> -->
                        <a href="javascript:void(0)" id="em_venue_esh_reset" style="float:right"><?php esc_html_e( 'Reset', 'eventprime-event-calendar-management' );?></a>
                </div>
                
                <div class="ep-form-row ep-form-group ep-mb-3">
                        <label for="ep_seating_type" class="ep-form-label">
                            <?php esc_html_e('Seating Type', 'eventprime-event-calendar-management'); ?>
                            <span class="required">*</span>
                        </label>
                        <select name="seating_type" id="ep_seating_type" class="ep-form-input ep-input-select ep-form-control" onchange="ep_venue_seating_change(this)">
                            <option value=""><?php esc_html_e('Select Seat Types', 'eventprime-event-calendar-management'); ?></option>
                            <option value="standings"><?php esc_html_e('Standing', 'eventprime-event-calendar-management'); ?></option>
                        </select>
                        <div class="ep-form-row-child" id="ep_seating_type_child" style="display:none;">
                            <label for="ep_seating_type" class="ep-form-label">
                                <?php esc_html_e('Standing Capacity', 'eventprime-event-calendar-management'); ?>
                            </label>
                            <input type="number" name="standing_capacity" id="ep_standing_capacity" class="ep-form-input ep-input-number" value="" />
                        </div>
                   
                </div>
                <div class="ep-form-row ep-form-group ep-mb-3">
                        <label for="em_seating_organizer" class="ep-form-label">
                            <?php esc_html_e('Operator', 'eventprime-event-calendar-management'); ?>
                        </label>
                        <input type="text" name="em_seating_organizer" class="ep-form-control" id="em_seating_organizer" placeholder="<?php esc_html_e('Operator', 'eventprime-event-calendar-management');?>">
                </div>
                <div class="ep-form-row ep-form-group ep-mb-3">
                        <label for="em_facebook_page" class="ep-form-label">
                            <?php esc_html_e('Facebook Page', 'eventprime-event-calendar-management'); ?>
                        </label>
                        <input type="text" name="em_facebook_page" class="ep-form-control" id="em_facebook_page" placeholder="<?php esc_html_e('https://www.facebook.com/XYZ/', 'eventprime-event-calendar-management');?>">
                </div>
                <div class="ep-form-row ep-form-group ep-mb-3">
                        <label for="em_instagram_page" class="ep-form-label">
                            <?php esc_html_e('Instagram Page', 'eventprime-event-calendar-management'); ?>
                        </label>
                        <input type="text" name="em_instagram_page" class="ep-form-control" id="em_instagram_page" placeholder="<?php esc_html_e('https://www.instagram.com/stories/XYZ', 'eventprime-event-calendar-management');?>">
                </div>
                <div class="ep-form-row ep-form-group ep-mb-3">
                        <label for="em_facebook_page" class="ep-form-label">
                            <?php esc_html_e('Image', 'eventprime-event-calendar-management'); ?>
                        </label>
                        <div class="ep-box-col-12">
                            <input type="file" name="venue_attachment" id="ep-venue-featured-file" onchange="upload_file_media(this)" accept="image/png, image/jpeg">
                            <input type="hidden" name="venue_attachment_id" id="venue_attachment_id" class="ep-hidden-attachment-id">
                        </div>
                </div>
            </div>
        <?php endif;?>
    </div>
<?php endif;?>