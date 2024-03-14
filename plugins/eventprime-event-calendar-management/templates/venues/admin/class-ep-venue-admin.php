<?php
defined( 'ABSPATH' ) || exit;

class EventM_Venues_Admin {
	/**
	 * Constructor
	 */
	public function __construct() {
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_venue_scripts' ) );
        // add and edit form fields
		add_action('em_venue_add_form_fields', array($this, 'add_event_venue_fields') );
		add_action('em_venue_edit_form_fields', array($this, 'edit_event_venue_fields'), 10);
        // save event venue
        add_action( 'created_em_venue', array( $this, 'em_create_event_venue_data') );
        // edit event venue
		add_action( 'edited_em_venue', array( $this, 'em_create_event_venue_data') );
        // add custom column
        add_filter( 'manage_edit-em_venue_columns', array( $this, 'add_venue_custom_columns' ) );
        add_filter( 'manage_em_venue_custom_column', array( $this, 'add_venue_custom_column' ), 10, 3 );
        // sorting for ID column
        add_filter( 'manage_edit-em_venue_sortable_columns', array( $this, 'add_venue_sortable_custom_columns' ) );
        add_filter( 'pre_get_terms', array( $this, 'add_venue_sortable_columns_callback' ) );

        // add banner
		add_action( 'load-edit-tags.php', function(){
			$screen = get_current_screen();
			if( 'edit-em_venue' === $screen->id ) {
				add_action( 'after-em_venue-table', function(){
					do_action( 'ep_add_custom_banner' );
				});
			}
		});
	}

	/**
	 * Add meta field to form
	 */
	public function add_event_venue_fields() { 
        $em = EP();
        $gmap_api_key = ep_get_global_settings( 'gmap_api_key' );
        if( ! empty( $gmap_api_key ) ) {?>
            <div class="form-field ep-venue-admin-address">
                <label for="em_address">
                    <?php esc_html_e( 'Address', 'eventprime-event-calendar-management' ); ?>
                </label>
                <input id="em-pac-input" name="em_address" class="em-map-controls" type="text">
                <div id="map"></div>

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
            <p class="emnote emeditor">
                <?php esc_html_e('This is used for displaying map marker on the event page.', 'eventprime-event-calendar-management'); ?>
            </p>

            <div class="form-field ep-venue-admin-field ep-venue-admin-location ep-d-flex">
                <div class="ep-venue-admin-lat ep-venue-left-field ep-box-w-50">
                    <input type="text" name="em_lat" id="em_lat" placeholder="<?php esc_html_e('Latitude', 'eventprime-event-calendar-management');?>">
                </div>
                <div class="ep-venue-admin-lang ep-venue-right-field ep-box-w-50">
                    <input type="text" name="em_lng" id="em_lng" placeholder="<?php esc_html_e('Longitude', 'eventprime-event-calendar-management');?>">
                </div>
            </div>

            <div class="form-field ep-venue-admin-field ep-venue-admin-location ep-d-flex">
                <div class="ep-venue-admin-locality ep-venue-left-field ep-box-w-50">
                    <input type="text" name="em_locality" id="em_locality" value="" placeholder="<?php esc_html_e('Locality', 'eventprime-event-calendar-management');?>">
                </div>
                <div class="ep-venue-admin-state ep-venue-left-field ep-box-w-50">
                    <input type="text" name="em_state" id="em_state" placeholder="<?php esc_html_e('State', 'eventprime-event-calendar-management');?>">
                </div>
            </div>

            <div class="form-field ep-venue-admin-field ep-venue-admin-location2 ep-d-flex">
                <div class="ep-venue-admin-country ep-venue-right-field ep-box-w-50">
                    <input type="text" name="em_country" id="em_country" placeholder="<?php esc_html_e('Country', 'eventprime-event-calendar-management');?>">
                </div>
                <div class="ep-venue-admin-postal-code ep-venue-left-field ep-box-w-50">
                    <input type="text" name="em_postal_code" id="em_postal_code" placeholder="<?php esc_html_e('Postal Code', 'eventprime-event-calendar-management');?>">
                </div>
            </div>

            <div class="form-field ep-venue-admin-field ep-venue-admin-location3 ep-d-flex">
                <div class="ep-venue-admin-zoom ep-venue-right-field">
                    <input type="number" name="em_zoom_level" id="em_zoom_level" placeholder="<?php esc_html_e('Zoom Level', 'eventprime-event-calendar-management');?>" min="0" step="1">
                    <p class="emnote emeditor">
                        <?php esc_html_e('Define how zoomed-in the map is when first loaded. Default is 1. Users will be able to zoom in and out using Google map controls.', 'eventprime-event-calendar-management'); ?>
                    </p>
                </div>
            </div>
            

            <!-- hidden field to get place id -->
            <input type="hidden" name="em_place_id" id="em_place_id" value=""><?php
        } else{?>
            <div class="form-field ep-venue-admin-address">
                <p class="emnote emeditor ep-text-danger">
                    <?php esc_html_e( 'Location Map field is not active as Google Map API is not configured. You can configure it from the Settings->General->Third-Party.', 'eventprime-event-calendar-management'); ?>
                </p>
                <label for="em_address">
                    <?php esc_html_e( 'Address', 'eventprime-event-calendar-management' ); ?>
                </label>
                <input id="em-pac-input" name="em_address" class="em-map-controls" type="text">
            </div><?php
        }?>

        <div class="form-field ep-venue-admin-display-address">
            <label for="display_address_on_frontend">
                <?php esc_html_e( 'Display Address', 'eventprime-event-calendar-management' ); ?>
                <label class="ep-toggle-btn">
                    <input type="checkbox" id="display_address_on_frontend" name="em_display_address_on_frontend" value="1" checked />
                    <span class="ep-toogle-slider round"></span>
                </label>
                <p class="emnote emeditor">
                    <?php esc_html_e( 'Display Address On Frontend.', 'eventprime-event-calendar-management' ); ?>
                </p>
            </label>
        </div>

        <div class="form-field ep-venue-admin-established ">
            <input type="text" name="em_established" id="em_established" class="" placeholder="<?php esc_html_e( 'Established', 'eventprime-event-calendar-management' );?>" autocomplete="off">
        </div>
        <div class="form-field ep-venue-admin-reset-bt">
            <input type="button" class="button" value="<?php esc_html_e( 'Reset', 'eventprime-event-calendar-management' );?>" id="em_venue_esh_reset" />
            <p class="emnote emeditor">
                <?php esc_html_e( 'When the Venue opened for public.', 'eventprime-event-calendar-management' ); ?>
            </p>
        </div>

        <div class="form-field ep-venue-admin-type">
            <label for="em_type">
                <?php esc_html_e( 'Seating Type', 'eventprime-event-calendar-management' ); ?>
            </label>
            <select required name="em_type" class="ep-box-w-100">
                <option value="standings"><?php esc_html_e( 'Standing', 'eventprime-event-calendar-management' );?></option>
                <?php 
                if ( ! empty( $em->extensions ) && in_array( 'live_seating', $em->extensions ) ) {?>
                    <option value="seats"><?php esc_html_e( 'Seating', 'eventprime-event-calendar-management' );?></option><?php
                } else{?>
                    <option value="seats" disabled="disabled"><?php esc_html_e( 'Seating - requires seating extension', 'eventprime-event-calendar-management' );?></option><?php
                }?>
            </select>
            <p class="emnote emeditor">
                <?php esc_html_e('Type of seating arrangement- Standing or Seating.', 'eventprime-event-calendar-management'); ?>
            </p>
        </div>

        <div class="form-field ep-venue-admin-operator">
            <label for="em_seating_organizer">
                <?php esc_html_e( 'Operator', 'eventprime-event-calendar-management' ); ?>
            </label>
            <input type="text" name="em_seating_organizer" id="em_seating_organizer" placeholder="<?php esc_html_e('Operator', 'eventprime-event-calendar-management');?>">
            <p class="emnote emeditor">
                <?php esc_html_e( 'Venue coordinator name or contact details.', 'eventprime-event-calendar-management'); ?>
            </p>
        </div>

        <div class="form-field ep-venue-admin-facebook">
            <label for="em_facebook_page">
                <?php esc_html_e( 'Facebook Page', 'eventprime-event-calendar-management' ); ?>
            </label>
            <input type="text" name="em_facebook_page" id="em_facebook_page" placeholder="<?php esc_html_e('https://www.facebook.com/XYZ/', 'eventprime-event-calendar-management');?>">
            <p class="emnote emeditor">
                <?php esc_html_e('Facebook page URL of the Venue, if available. Eg.:https://www.facebook.com/XYZ/', 'eventprime-event-calendar-management'); ?>
            </p>
        </div>

        <div class="form-field ep-venue-admin-instagram">
            <label for="em_instagram_page">
                <?php esc_html_e( 'Instagram Page', 'eventprime-event-calendar-management' ); ?>
            </label>
            <input type="text" name="em_instagram_page" id="em_instagram_page" placeholder="<?php esc_html_e('https://www.instagram.com/stories/XYZ', 'eventprime-event-calendar-management');?>">
            <p class="emnote emeditor">
                <?php esc_html_e('Instagram page URL of the Venue, if available. Eg.:https://www.instagram.com/stories/XYZ', 'eventprime-event-calendar-management'); ?>
            </p>
        </div>

        <div class="form-field ep-venue-admin-image-wrap">
            <label><?php esc_html_e( 'Image', 'eventprime-event-calendar-management' ); ?></label>
            <div id="ep-venue-admin-image" class="ep-d-flex ep-flex-wrap ep-mb-3"></div>
            <div class="ep-box-w-100">
                <input type="hidden" id="ep_venue_image_id" name="em_gallery_images" />
                <button type="button" class="upload_image_button button"><?php esc_html_e( 'Upload/Add image', 'eventprime-event-calendar-management' ); ?></button>
                <p class="emnote emeditor">
                    <?php esc_html_e(' Image or icon of the Venue. Will be displayed on the Venue directory page.', 'eventprime-event-calendar-management'); ?>
                </p>
            </div>
        </div>
        <div class="form-field ep-venue-admin-featured">
            <label for="is_featured">
                <?php esc_html_e( 'Featured', 'eventprime-event-calendar-management' ); ?>
                <label class="ep-toggle-btn">
                    <input type="checkbox" id="is_featured" name="em_is_featured" />
                    <span class="ep-toogle-slider round"></span>
                </label>
                <p class="emnote emeditor">
                    <?php esc_html_e('Check if you want to make this Venue featured.', 'eventprime-event-calendar-management'); ?>
                </p>
            </label>
        </div><?php
	}

	/**
	 * Edit meta fields to form
     *
     * @param mixed $term Term
	 */

    public function edit_event_venue_fields( $term ) {
        $em = EP();
        $em_address           = get_term_meta( $term->term_id, 'em_address', true );
        $em_lat               = get_term_meta( $term->term_id, 'em_lat', true );
        $em_lng               = get_term_meta( $term->term_id, 'em_lng', true );
        $em_locality          = get_term_meta( $term->term_id, 'em_locality', true );
        $em_state             = get_term_meta( $term->term_id, 'em_state', true );
        $em_country           = get_term_meta( $term->term_id, 'em_country', true );
        $em_postal_code       = get_term_meta( $term->term_id, 'em_postal_code', true );
        $em_zoom_level        = get_term_meta( $term->term_id, 'em_zoom_level', true );
        $em_place_id          = get_term_meta( $term->term_id, 'em_place_id', true );
        $em_established       = get_term_meta( $term->term_id, 'em_established', true );
        $em_type              = get_term_meta( $term->term_id, 'em_type', true );
        $em_seating_organizer = get_term_meta( $term->term_id, 'em_seating_organizer', true );
        $em_facebook_page     = get_term_meta( $term->term_id, 'em_facebook_page', true );
        $em_instagram_page    = get_term_meta( $term->term_id, 'em_instagram_page', true );
        $em_gallery_images    = get_term_meta( $term->term_id, 'em_gallery_images', true );
        $em_display_address_on_frontend = get_term_meta( $term->term_id, 'em_display_address_on_frontend', true );
        $em_is_featured       = get_term_meta( $term->term_id, 'em_is_featured', true );
        $formated_image_ids   = ( is_array( $em_gallery_images ) && count( $em_gallery_images ) ) ? implode( ',', $em_gallery_images ): '';
        $gmap_api_key = ep_get_global_settings( 'gmap_api_key' );
        if( ! empty( $gmap_api_key ) ) {?>
            <tr class="form-field ep-venue-admin-address">
                <th scope="row">
                    <label><?php esc_html_e( 'Address', 'eventprime-event-calendar-management' ); ?></label>
                </th>
                <td>
                    <input id="em-pac-input" name="em_address" class="em-map-controls" type="text" value="<?php echo esc_html($em_address); ?>" >
                    <div id="map"></div>

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
                    <p class="description">
                        <?php esc_html_e( 'This is used for displaying map marker on the event page.', 'eventprime-event-calendar-management' ); ?>
                    </p> 
                </td>
            </tr>
            <tr class="form-field ep-venue-admin-lat">
                <th scope="row">
                    <label><?php esc_html_e( 'Latitude', 'eventprime-event-calendar-management' ); ?></label>
                </th>
                <td>
                    <input value="<?php echo esc_html($em_lat); ?>" type="text" id="em_lat" name="em_lat" />
                    <p class="description">
                        <?php esc_html_e( 'Latitude.', 'eventprime-event-calendar-management' ); ?>
                    </p>
                </td>
            </tr>
            <tr class="form-field ep-venue-admin-lng">
                <th scope="row">
                    <label><?php esc_html_e( 'Longitude', 'eventprime-event-calendar-management' ); ?></label>
                </th>
                <td>
                    <input value="<?php echo esc_html($em_lng); ?>" type="text" id="em_lng" name="em_lng" />
                    <p class="description">
                        <?php esc_html_e( 'Longitude', 'eventprime-event-calendar-management' ); ?>
                    </p>
                </td>
            </tr>
            <tr class="form-field ep-venue-admin-locality">
                <th scope="row">
                    <label><?php esc_html_e( 'Locality', 'eventprime-event-calendar-management' ); ?></label>
                </th>
                <td>
                    <input value="<?php echo esc_html($em_locality); ?>" type="text" id="em_locality" name="em_locality" />
                    <p class="description">
                        <?php esc_html_e( 'Locality', 'eventprime-event-calendar-management' ); ?>
                    </p>
                </td>
            </tr>
            <tr class="form-field ep-venue-admin-state"> 
                <th scope="row">
                    <label><?php esc_html_e( 'State', 'eventprime-event-calendar-management' ); ?></label>
                </th>
                <td>
                    <input value="<?php echo esc_html($em_state); ?>" type="text" id="em_state" name="em_state" />
                    <p class="description">
                        <?php esc_html_e( 'State', 'eventprime-event-calendar-management' ); ?>
                    </p>
                </td>
            </tr>
            <tr class="form-field ep-venue-admin-country">
                <th scope="row">
                    <label><?php esc_html_e( 'Country', 'eventprime-event-calendar-management' ); ?></label>
                </th>
                <td>
                    <input value="<?php echo esc_html($em_country); ?>" type="text" id="em_country" name="em_country" />
                    <p class="description">
                        <?php esc_html_e( 'Country.', 'eventprime-event-calendar-management' ); ?>
                    </p>
                </td>
            </tr>
            <tr class="form-field ep-venue-admin-postal">
                <th scope="row">
                    <label><?php esc_html_e( 'Postal Code', 'eventprime-event-calendar-management' ); ?></label>
                </th>
                <td>
                    <input value="<?php echo esc_html($em_postal_code); ?>" type="text" id="em_postal_code" name="em_postal_code" />
                    <p class="description">
                        <?php esc_html_e( 'Postal Code', 'eventprime-event-calendar-management' ); ?>
                    </p>
                </td>
            </tr>
            <tr class="form-field ep-venue-admin-zoom">
                <th scope="row">
                    <label><?php esc_html_e( 'Zoom Level', 'eventprime-event-calendar-management' ); ?></label>
                </th>
                <td>
                    <input value="<?php echo esc_html($em_zoom_level); ?>" type="text" id="em_zoom_level" name="em_zoom_level" />
                    <input value="<?php echo esc_html( $em_place_id ); ?>" type="hidden" id="em_place_id" name="em_place_id" />
                    <p class="description">
                        <?php esc_html_e( 'Define how zoomed-in the map is when first loaded. Default is 1. Users will be able to zoom in and out using Google map controls.', 'eventprime-event-calendar-management' ); ?>
                    </p>
                </td>
            </tr><?php
        } else{?>
            <tr class="form-field ep-venue-admin-address">
                <th scope="row">
                    <label><?php esc_html_e( 'Address', 'eventprime-event-calendar-management' ); ?></label>
                </th>
                <td>
                    <input id="em-pac-input" name="em_address" class="em-map-controls" type="text" value="<?php echo esc_html($em_address); ?>" >
                    <p class="description ep-text-danger">
                        <?php esc_html_e( 'Location Map field is not active as Google Map API is not configured. You can configure it from the Settings->General->Third-Party.', 'eventprime-event-calendar-management' ); ?>
                    </p>
                </td>
            </tr><?php
        }?>
        <tr class="form-field ep-venue-admin-display-address">
            <th scope="row">
                <label><?php esc_html_e( 'Display Address', 'eventprime-event-calendar-management' ); ?></label>
            </th>
            <td>
                <div class="form-field ep-venue-admin-display-address">
                    <label class="ep-toggle-btn">
                        <input type="checkbox" id="is_display_address" name="em_display_address_on_frontend" value="<?php echo esc_html( $em_display_address_on_frontend ); ?>" <?php if( $em_display_address_on_frontend == 1 ){ echo 'checked="checked"'; }?> />
                        <span class="ep-toogle-slider round"></span>
                    </label>
                    <p class="description">
                        <?php esc_html_e( 'Display Address On Frontend.', 'eventprime-event-calendar-management' ); ?>
                    </p>
                </div>
            </td>
        </tr>
        <tr class="form-field ep-venue-admin-established">
            <th scope="row">
                <label><?php esc_html_e( 'Established', 'eventprime-event-calendar-management' ); ?></label>
            </th>
            <td>
                <input value="<?php echo esc_html( $em_established ); ?>" type="text" id="em_established" name="em_established" />
                <p class="description">
                    <?php esc_html_e( 'When the Venue opened for public.', 'eventprime-event-calendar-management' ); ?>
                </p>
            </td>
        </tr>

        <tr class="form-field ep-venue-admin-type">
            <th scope="row">
                <label><?php esc_html_e( 'Seating Type', 'eventprime-event-calendar-management' ); ?></label>
            </th>
            <td>
                <select required name="em_type" class="ep-box-w-100">
                    <option value="standings" <?php if($em_type == 'standings'){ echo 'selected="selected"';}?> ><?php esc_html_e( 'Standing', 'eventprime-event-calendar-management' );?></option>
                    <?php 
                    $seating_disabled = 'disabled';
                    if ( ! empty( $em->extensions ) && in_array( 'live_seating', $em->extensions ) ) {
                        $seating_disabled = '';
                    } 
                    if ( ! empty( $em->extensions ) && in_array( 'live_seating', $em->extensions ) ) {?>
                        <option value="seats" <?php if($em_type == 'seats'){ echo 'selected="selected"';}?>><?php esc_html_e( 'Seating', 'eventprime-event-calendar-management' );?></option><?php
                    } else{?>
                        <option value="seats" disabled="disabled"><?php esc_html_e( 'Seating - requires seating extension', 'eventprime-event-calendar-management' );?></option><?php
                    }?>
                </select>
                <p class="description">
                    <?php esc_html_e( 'Type of seating arrangement- Standing or Seating.', 'eventprime-event-calendar-management' ); ?>
                </p>
            </td>
        </tr>
        <tr class="form-field ep-venue-admin-operator">
            <th scope="row">
                <label><?php esc_html_e( 'Operator', 'eventprime-event-calendar-management' ); ?></label>
            </th>
            <td>
                <input value="<?php echo esc_html($em_seating_organizer); ?>" type="text" id="em_seating_organizer" name="em_seating_organizer" />
                <p class="description">
                    <?php esc_html_e( 'Venue coordinator name or contact details.', 'eventprime-event-calendar-management' ); ?>
                </p>
            </td>
        </tr>
        <tr class="form-field ep-venue-admin-facebook">
            <th scope="row">
                <label><?php esc_html_e( 'Facebook Page', 'eventprime-event-calendar-management' ); ?></label>
            </th>
            <td>
                <input value="<?php echo esc_html($em_facebook_page); ?>" type="text" id="em_facebook_page" name="em_facebook_page" placeholder="<?php esc_html_e('https://www.facebook.com/XYZ/', 'eventprime-event-calendar-management');?>" />
                <p class="description">
                    <?php esc_html_e( 'Facebook page URL of the Venue, if available. Eg.:https://www.facebook.com/XYZ/', 'eventprime-event-calendar-management' ); ?>
                </p>
            </td>
        </tr>
        <tr class="form-field ep-venue-admin-instagram">
            <th scope="row">
                <label><?php esc_html_e( 'Instagram Page', 'eventprime-event-calendar-management' ); ?></label>
            </th>
            <td>
                <input value="<?php echo esc_html($em_instagram_page); ?>" type="text" id="em_instagram_page" name="em_instagram_page" placeholder="<?php esc_html_e('https://www.instagram.com/stories/XYZ', 'eventprime-event-calendar-management');?>" />
                <p class="description">
                    <?php esc_html_e( 'Instagram page URL of the Venue, if available. Eg.:https://www.instagram.com/stories/XYZ', 'eventprime-event-calendar-management' ); ?>
                </p>
            </td>
        </tr>

        <tr class="form-field ep-venue-admin-image-wrap">
            <th scope="row">
                <label><?php esc_html_e( 'Image', 'eventprime-event-calendar-management' ); ?></label>
            </th>
            <td>
                <div id="ep-venue-admin-image" style="float: left; margin-right: 10px;">
                    <?php if( is_array( $em_gallery_images ) && count( $em_gallery_images ) ) {
                        foreach( $em_gallery_images as $image_id ) {
                            if( ! empty( $image_id ) ) {
                                $attach_url = wp_get_attachment_image_url( $image_id );
                                if( ! empty( $attach_url ) ) {?>
                                    <span class="ep-venue-gallery"><i class="remove-gallery-venue dashicons dashicons-trash ep-text-danger"></i>
                                        <img src="<?php echo esc_url( $attach_url );?>" data-image_id="<?php echo $image_id;?>"/>
                                    </span><?php
                                }
                            }
                        }
                    }?>
                </div>
                <div>
                    <input type="hidden" id="ep_venue_image_id" name="em_gallery_images" value="<?php echo esc_html( $formated_image_ids ); ?>" />
                    <button type="button" class="upload_image_button button"><?php esc_html_e( 'Upload/Add image', 'eventprime-event-calendar-management' ); ?></button>
                </div>
            </td>
        </tr>
        <tr class="form-field ep-venue-admin-featured">
            <th scope="row">
                <label><?php esc_html_e( 'Featured', 'eventprime-event-calendar-management' ); ?></label>
            </th>
            <td>
                <div class="form-field ep-venue-admin-featured">
                    <label class="ep-toggle-btn">
                        <input type="checkbox" id="is_featured" name="em_is_featured" value="<?php echo esc_html($em_is_featured); ?>" <?php if($em_is_featured == 1){ echo 'checked="checked"'; }?> />
                        <span class="ep-toogle-slider round"></span>
                    </label>
                    <p class="description">
                        <?php esc_html_e( 'Check if you want to make this Venue featured.', 'eventprime-event-calendar-management' ); ?>
                    </p>
                </div>
            </td>
        </tr>
        <?php
    }

    /**
     * Enqueue venue scripts
     */

    public function enqueue_admin_venue_scripts() {
	    wp_enqueue_media();
        wp_enqueue_script('jquery-ui-datepicker');

	    wp_enqueue_script(
		    'em-venue-admin-custom-js',
		    EP_BASE_URL . '/includes/venues/assets/js/em-venue-admin-custom.js',
		    false, EVENTPRIME_VERSION
        );

        wp_localize_script(
            'em-venue-admin-custom-js', 
            'em_venue_object', 
            array(
                'media_title'  => esc_html__('Choose Image', 'eventprime-event-calendar-management'),
                'media_button' => esc_html__('Use image', 'eventprime-event-calendar-management')  
            )
        );

        wp_enqueue_style(
            'em-venue-admin-custom-css',
            EP_BASE_URL . '/includes/venues/assets/css/em-venue-admin-custom.css',
            false, EVENTPRIME_VERSION
        );

	    wp_enqueue_style(
		    'em-venue-admin-jquery-ui',
		    EP_BASE_URL . '/includes/assets/css/jquery-ui.min.css',
		    false, EVENTPRIME_VERSION
        );

        wp_register_script('em-google-map', EP_BASE_URL . '/includes/assets/js/em-map.js', array('jquery'), EVENTPRIME_VERSION);
        $gmap_api_key = ep_get_global_settings( 'gmap_api_key' );
        if($gmap_api_key) {
            wp_enqueue_script(
                'google_map_key', 
                'https://maps.googleapis.com/maps/api/js?key='.$gmap_api_key.'&libraries=places&callback=Function.prototype', 
                array(), EVENTPRIME_VERSION
            );
        }
    }

    /**
     * Create venue meta data
     *
     * @param int $term_id
     */

    public function em_create_event_venue_data( $term_id ) {
        if( isset( $_POST['tax_ID'] ) && ! empty( $_POST['tax_ID'] ) ) return; 
        $em_address = isset( $_POST['em_address'] ) ? sanitize_text_field( $_POST['em_address'] ) : '';
        $em_lat = isset( $_POST['em_lat'] ) ? sanitize_text_field( $_POST['em_lat'] ) : '';
        $em_lng = isset( $_POST['em_lng'] ) ? sanitize_text_field( $_POST['em_lng'] ) : '';
        $em_locality = isset( $_POST['em_locality'] ) ? sanitize_text_field( $_POST['em_locality'] ) : '';
        $em_state = isset( $_POST['em_state'] ) ? sanitize_text_field( $_POST['em_state'] ) : '';
        $em_country = isset( $_POST['em_country'] ) ? sanitize_text_field( $_POST['em_country'] ) : '';
        $em_postal_code = isset( $_POST['em_postal_code'] ) ? sanitize_text_field( $_POST['em_postal_code'] ) : '';
        $em_zoom_level = isset( $_POST['em_zoom_level'] ) ? sanitize_text_field( $_POST['em_zoom_level'] ) : '';
        $em_place_id   = isset( $_POST['em_place_id'] ) ? sanitize_text_field( $_POST['em_place_id'] ) : '';
        $em_established = isset( $_POST['em_established'] ) ? sanitize_text_field( $_POST['em_established'] ) : '';
        $em_type = isset( $_POST['em_type'] ) ? sanitize_text_field( $_POST['em_type'] ) : '';
        $em_seating_organizer = isset( $_POST['em_seating_organizer'] ) ? sanitize_text_field( $_POST['em_seating_organizer'] ) : '';
        $em_facebook_page = isset( $_POST['em_facebook_page'] ) ? sanitize_text_field( $_POST['em_facebook_page'] ) : '';
        $em_instagram_page = isset( $_POST['em_instagram_page'] ) ? sanitize_text_field( $_POST['em_instagram_page'] ) : '';
        $em_gallery_images = isset( $_POST['em_gallery_images'] ) ? explode(',', sanitize_text_field( $_POST['em_gallery_images']) ) : '';
        $em_is_featured = isset( $_POST['em_is_featured'] ) ? 1 : '0';
        $em_display_address_on_frontend = isset( $_POST['em_display_address_on_frontend'] ) ? 1 : 0;
        
        update_term_meta( $term_id, 'em_address', $em_address );
        update_term_meta( $term_id, 'em_lat', $em_lat );
        update_term_meta( $term_id, 'em_lng', $em_lng );
        update_term_meta( $term_id, 'em_locality', $em_locality );
        update_term_meta( $term_id, 'em_state', $em_state );
        update_term_meta( $term_id, 'em_country', $em_country );
        update_term_meta( $term_id, 'em_postal_code', $em_postal_code );
        update_term_meta( $term_id, 'em_zoom_level', $em_zoom_level );
        update_term_meta( $term_id, 'em_place_id', $em_place_id );
        update_term_meta( $term_id, 'em_established', $em_established );
        update_term_meta( $term_id, 'em_type', $em_type );
        update_term_meta( $term_id, 'em_seating_organizer', $em_seating_organizer );
        update_term_meta( $term_id, 'em_facebook_page', $em_facebook_page );
        update_term_meta( $term_id, 'em_instagram_page', $em_instagram_page );
        update_term_meta( $term_id, 'em_gallery_images', $em_gallery_images );
        update_term_meta( $term_id, 'em_is_featured', $em_is_featured );
        update_term_meta( $term_id, 'em_display_address_on_frontend', $em_display_address_on_frontend );
        if ( ! metadata_exists( 'term', $term_id, 'em_status' ) ) {
            update_term_meta( $term_id, 'em_status', 1 );
        }
    }

    /**
     * Custom column added to venue admin
     *
     * @param mixed $columns Columns array.
     * @return array
     */
    public function add_venue_custom_columns( $columns ) {
        $new_columns = array();

	    if ( isset( $columns['cb'] ) ) {
		    $new_columns['cb'] = $columns['cb'];
		    unset( $columns['cb'] );
	    }

        $new_columns['id'] = esc_html__( 'ID', 'eventprime-event-calendar-management' );

        $new_columns['image_id'] = esc_html__( 'Image', 'eventprime-event-calendar-management' );

        $columns = array_merge( $new_columns, $columns );

        // rename Count to Events
        $columns['posts'] = esc_html__( 'Events', 'eventprime-event-calendar-management' );

	    return $columns;
    }

     /**
     * Custom column value added to event type admin
     *
     * @param string $columns Column HTML output.
     * @param string $column Column name.
     * @param int    $id Term ID.
     *
     * @return string
     */
    public function add_venue_custom_column( $columns, $column, $id ) {
        if( 'id' === $column ) {
            $columns .= '<span class="id-block">'.esc_html( $id ).'</span>';
        }

        if( 'image_id' === $column ) {
            $image_id = get_term_meta( $id, 'em_gallery_images', true );
            $image_id = (is_array( $image_id ) && count( $image_id ) > 0 ) ? $image_id[0] : $image_id;
            if( $image_id ) {
                $image    = wp_get_attachment_thumb_url( $image_id );
	            $image    = str_replace( ' ', '%20', $image );
	            $columns .= '<img src="' . esc_url( $image ) . '" alt="' . esc_attr__( 'Image', 'eventprime-event-calendar-management' ) . '" class="wp-post-image" height="48" width="48" />';
            }
        }

        return $columns;
    }

    /**
     * Add sorting on the ID column
     * 
     * @param mixed $columns Columns array.
     * @return array
     */
    public function add_venue_sortable_custom_columns( $columns ) {
        add_filter('pre_get_terms', 'callback_filter_terms_clauses');
        $columns['id'] = 'id';
        return $columns;
    }

    // callbak for sorting
    public function add_venue_sortable_columns_callback( $term_query ) {
        global $pagenow;
        if( ! is_admin() ) return $term_query;

        if( is_admin() && $pagenow == 'edit-tags.php' && ( ! isset( $_GET['orderby'] ) || $_GET['orderby'] == 'id' ) ) {
            $term_query->query_vars['orderby'] = 'term_id';
            $term_query->query_vars['order'] = isset( $_GET['order'] ) ? sanitize_text_field( $_GET['order'] ) : "DESC";
        }
        return $term_query;
    }

}

new EventM_Venues_Admin();