<?php
/**
 * EventPrime plugin default hooks Class.
 */
defined( 'ABSPATH' ) || exit;

class EventM_Action_Service {

    /**
	 * Initilize for class
	 */
	public function __construct() {
        add_action( 'ep_events_list_before_render_content', array( $this, 'ep_show_timezone_related_message' ), 10 );
        add_action( 'ep_events_list_before_render_content', array( $this, 'ep_event_add_hidden_variables' ), 20  );
        // wishlist icon
        add_action( 'ep_event_view_wishlist_icon', array( $this, 'ep_event_add_wishlist_icon' ), 10, 2 );
        // social sharing icon
        add_action( 'ep_event_view_social_sharing_icon', array( $this, 'ep_event_add_social_sharing_icon' ), 10, 2 );
        add_action( 'ep_event_view_event_dates', array( $this, 'ep_event_add_event_dates' ), 10, 2 );
        add_action( 'ep_event_view_event_price', array( $this, 'ep_event_add_event_price' ), 10, 2 );
        add_action( 'ep_event_view_event_booking_button', array( $this, 'ep_event_add_event_booking_button' ), 10, 1 );
        // weather widget on the detail page
        add_action( 'ep_event_detail_weather_data', array( $this, 'ep_event_detail_add_weather_widget' ), 10, 1 );
        add_action( 'body_class', array( $this, 'ep_add_body_class' ), 1 );
        add_action( 'ep_events_booking_count_slider', array($this, 'ep_event_booking_count'), 10, 1);
        // modify post message
        add_filter( 'bulk_post_updated_messages', array($this, 'ep_bulk_post_updated_messages_filter'), 10, 2 );
        // single event page event dates
        add_action( 'ep_event_detail_right_event_dates_section', array( $this, 'ep_event_detail_right_event_dates_section' ), 10 );
        // dequeue scripts
        add_action( 'ep_dequeue_event_scripts', array( $this, 'ep_dequeue_event_scripts' ), 10 );
        // loader
        add_action( 'ep_add_loader_section', array( $this, 'ep_add_loader_section' ), 10 );
        add_action( 'wp_head', array( $this, 'ep_custom_styles' ), 100 );
        // after update parent event status
        add_action( 'ep_update_parent_event_status', array( $this, 'ep_update_parent_event_status' ), 10, 2 );
        // after save event data
        add_action( 'ep_after_save_event_data', array( $this, 'ep_update_event_data_after_save' ), 10, 2 );

        // template include
        add_filter( 'template_include', array( $this, 'ep_load_single_template' ), 1000 );
        
        add_filter( 'the_content', array( $this, 'ep_load_single_template_dynamic' ),1000000000 );
        
        add_filter('post_thumbnail_html',  array( $this,'remove_thumbnail_on_event_post_type'), 10, 5);
        
        //add_filter( 'template_redirect', array( $this, 'ep_load_single_template_redirect' ), 99 );

        // add statisticts in the event
        add_action( 'ep_event_stats_list', array( $this, 'ep_add_event_statisticts_data' ), 10, 1 );
        // calendar icon
        add_action( 'ep_event_view_calendar_icon', array( $this, 'ep_event_add_calendar_icon' ), 10, 2 );

        // add premium banner
        add_action( 'ep_add_custom_banner', array( $this, 'ep_add_custom_banner' ) );
    }

    /**
     * Add hidden variabled on the event views section
     * 
     * @param object $args Event data.
     * 
     * @return void
     */
    
    public function remove_thumbnail_on_event_post_type($html, $post_id, $post_thumbnail_id, $size, $attr) {
    // Check if it's the custom post type you want to target
    if ('em_event' === get_post_type($post_id) && is_single()) {
        // If you want to completely remove the thumbnail, you can return an empty string
        return '';
        
        // If you want to modify the HTML in some way, you can do so here
        // Example: return '<div class="custom-thumbnail">' . $html . '</div>';
    }

    // If it's not the custom post type, return the original HTML
    return $html;
}

    
    public function ep_event_add_hidden_variables( $args ) {?>
        <input type="hidden" id="ep-events-style" value="<?php echo esc_attr( $args->display_style );?>"/><?php 
    }

    /**
     * Add wishlist icon on the event
     * 
     * @param object $event Event data.
     * 
     * @return void
     */
    public function ep_event_add_wishlist_icon( $event, $page ) {
        if( $event && ! empty( $event->id ) && empty( ep_get_global_settings( 'hide_wishlist_icon' ) )) {
            $add_to_wishlist_text = ep_global_settings_button_title( 'Add To Wishlist' );
            $remove_from_wishlist_text = ep_global_settings_button_title( 'Remove From Wishlist' );
            $wish_title = esc_html__( 'Add To Wishlist', 'eventprime-event-calendar-management' );
            if( ! empty( $add_to_wishlist_text ) ) {
                $wish_title = $add_to_wishlist_text;
            }
            if( $event->event_in_user_wishlist == true ) { 
                $wish_title = esc_html__( 'Remove From Wishlist', 'eventprime-event-calendar-management' );
                if( ! empty( $remove_from_wishlist_text ) ) {
                    $wish_title = $remove_from_wishlist_text;
                }
            }
            if( $page == 'event_detail' ) {?>
                <div class="ep-event-action ep_event_wishlist_action ep-px-2" id="ep_event_wishlist_action_<?php echo esc_attr( $event->id );?>" data-event_id="<?php echo esc_attr( $event->id );?>" title="<?php echo $wish_title;?>">
                    <span class="material-icons-outlined ep-handle-fav ep-cursor ep-button-text-color ep-mr-3 <?php if( $event->event_in_user_wishlist == true ) { echo esc_html( 'ep-text-danger' ); }?>"><?php if( $event->event_in_user_wishlist == true ) { echo trim( esc_html('favorite') ); } else{ echo trim( esc_html('favorite_border') ); }?></span>
                </div><?php
            } else{?>
                <div class="ep-wishlist-action-wrap">
                    <div class="ep-event-action ep_event_wishlist_action ep-px-2" id="ep_event_wishlist_action_<?php echo esc_attr( $event->id );?>" data-event_id="<?php echo esc_attr( $event->id );?>" title="<?php echo $wish_title;?>">
                        <span class="material-icons-outlined ep-handle-fav ep-cursor ep-button-text-color ep-fs-6 <?php if( $event->event_in_user_wishlist == true ) { echo esc_html( 'ep-text-danger' ); }?>"><?php if( $event->event_in_user_wishlist == true ) { echo trim( esc_html('favorite') ); } else{ echo trim( esc_html('favorite_border') ); }?></span>
                    </div>
                </div><?php
            }
        }
    }

    /**
     * Add social sharing icon on the event
     * 
     * @param object $event Event data.
     * 
     * @return void
     */
    public function ep_event_add_social_sharing_icon( $event, $page ) {
        if ( ! empty( ep_get_global_settings( 'social_sharing' ) ) ) {
            if( $event && ! empty( $event->id ) ) { 
                if( $page == 'event_detail' ) {?>
                    <div class="ep-sl-event-action ep-cursor ep-position-relative">
                        <span class="material-icons-outlined ep-handle-share ep-button-text-color ep-mr-3 ep-cursor">share</span>
                        <?php
                        $social_links_url = $event->event_url;?>
                        <ul class="ep-event-share ep-m-0 ep-p-0" style="display:none;">
                            <li class="ep-event-social-icon" title="">
                                <a class="facebook" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $social_links_url; ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=500,width=600'); return false;" target="_blank" title="<?php esc_html_e('Share on Facebook', 'eventprime-event-calendar-management'); ?>">
                                    <span class="ep-social-title" title="Facebook"> <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><!-- Font Awesome Pro 5.15.4 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) --><path d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z"/></svg> Facebook</span>
                                </a>
                            </li>
                            <li class="ep-event-social-icon">
                                <a class="twitter" href="https://twitter.com/share?url=<?php echo $social_links_url; ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=500'); return false;" target="_blank" title="<?php esc_html_e('Share on Twitter', 'eventprime-event-calendar-management'); ?>">
                                    <span class="ep-social-title"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!-- Font Awesome Pro 5.15.4 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) --><path d="M459.37 151.716c.325 4.548.325 9.097.325 13.645 0 138.72-105.583 298.558-298.558 298.558-59.452 0-114.68-17.219-161.137-47.106 8.447.974 16.568 1.299 25.34 1.299 49.055 0 94.213-16.568 130.274-44.832-46.132-.975-84.792-31.188-98.112-72.772 6.498.974 12.995 1.624 19.818 1.624 9.421 0 18.843-1.3 27.614-3.573-48.081-9.747-84.143-51.98-84.143-102.985v-1.299c13.969 7.797 30.214 12.67 47.431 13.319-28.264-18.843-46.781-51.005-46.781-87.391 0-19.492 5.197-37.36 14.294-52.954 51.655 63.675 129.3 105.258 216.365 109.807-1.624-7.797-2.599-15.918-2.599-24.04 0-57.828 46.782-104.934 104.934-104.934 30.213 0 57.502 12.67 76.67 33.137 23.715-4.548 46.456-13.32 66.599-25.34-7.798 24.366-24.366 44.833-46.132 57.827 21.117-2.273 41.584-8.122 60.426-16.243-14.292 20.791-32.161 39.308-52.628 54.253z"/></svg>Twitter</span>
                                </a>
                            </li>

                            <li class="ep-event-social-icon">
                                <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo $social_links_url; ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=500'); return false;" target="_blank" title="<?php esc_html_e('Share on Linkedin', 'eventprime-event-calendar-management'); ?>">
                                    <span class="ep-social-title"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!-- Font Awesome Pro 5.15.4 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) --><path d="M100.28 448H7.4V148.9h92.88zM53.79 108.1C24.09 108.1 0 83.5 0 53.8a53.79 53.79 0 0 1 107.58 0c0 29.7-24.1 54.3-53.79 54.3zM447.9 448h-92.68V302.4c0-34.7-.7-79.2-48.29-79.2-48.29 0-55.69 37.7-55.69 76.7V448h-92.78V148.9h89.08v40.8h1.3c12.4-23.5 42.69-48.3 87.88-48.3 94 0 111.28 61.9 111.28 142.3V448z"/></svg>Linkedin</span>
                                </a>
                            </li>
                            <li class="ep-event-social-icon">
                                <a href="https://api.whatsapp.com/send?text=<?php echo $social_links_url; ?>" target="_blank" title="<?php esc_html_e('Share on Whatsapp', 'eventprime-event-calendar-management'); ?>">
                                    <span class="ep-social-title"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!-- Font Awesome Pro 5.15.4 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) --><path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"/></svg>Whatsapp</span>
                                </a>
                            </li>
                        </ul>
                    </div><?php
                } else{?>
                    <div class="ep-social-share-action-wrap">
                        <div class="ep-event-action ep-cursor ep-position-relative ep-px-2">
                            <span class="material-icons-outlined ep-handle-share ep-button-text-color ep-fs-6">share</span>
                            <?php
                            $social_links_url = $event->event_url;?>
                            <ul class="ep-event-share ep-m-0 ep-px-0" style="display:none;">
                                <li class="ep-event-social-icon" title="">
                                    <a class="facebook" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $social_links_url; ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=500,width=600'); return false;" target="_blank" title="<?php esc_html_e('Share on Facebook', 'eventprime-event-calendar-management'); ?>">
                                        <span class="ep-social-title" title="Facebook"> <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><!-- Font Awesome Pro 5.15.4 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) --><path d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z"/></svg> Facebook</span>
                                    </a>
                                </li>
                                <li class="ep-event-social-icon">
                                    <a class="twitter" href="https://twitter.com/share?url=<?php echo $social_links_url; ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=500'); return false;" target="_blank" title="<?php esc_html_e('Share on Twitter', 'eventprime-event-calendar-management'); ?>">
                                        <span class="ep-social-title"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!-- Font Awesome Pro 5.15.4 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) --><path d="M459.37 151.716c.325 4.548.325 9.097.325 13.645 0 138.72-105.583 298.558-298.558 298.558-59.452 0-114.68-17.219-161.137-47.106 8.447.974 16.568 1.299 25.34 1.299 49.055 0 94.213-16.568 130.274-44.832-46.132-.975-84.792-31.188-98.112-72.772 6.498.974 12.995 1.624 19.818 1.624 9.421 0 18.843-1.3 27.614-3.573-48.081-9.747-84.143-51.98-84.143-102.985v-1.299c13.969 7.797 30.214 12.67 47.431 13.319-28.264-18.843-46.781-51.005-46.781-87.391 0-19.492 5.197-37.36 14.294-52.954 51.655 63.675 129.3 105.258 216.365 109.807-1.624-7.797-2.599-15.918-2.599-24.04 0-57.828 46.782-104.934 104.934-104.934 30.213 0 57.502 12.67 76.67 33.137 23.715-4.548 46.456-13.32 66.599-25.34-7.798 24.366-24.366 44.833-46.132 57.827 21.117-2.273 41.584-8.122 60.426-16.243-14.292 20.791-32.161 39.308-52.628 54.253z"/></svg>Twitter</span>
                                    </a>
                                </li>

                                <li class="ep-event-social-icon">
                                    <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo $social_links_url; ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=500'); return false;" target="_blank" title="<?php esc_html_e('Share on Linkedin', 'eventprime-event-calendar-management'); ?>">
                                        <span class="ep-social-title"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!-- Font Awesome Pro 5.15.4 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) --><path d="M100.28 448H7.4V148.9h92.88zM53.79 108.1C24.09 108.1 0 83.5 0 53.8a53.79 53.79 0 0 1 107.58 0c0 29.7-24.1 54.3-53.79 54.3zM447.9 448h-92.68V302.4c0-34.7-.7-79.2-48.29-79.2-48.29 0-55.69 37.7-55.69 76.7V448h-92.78V148.9h89.08v40.8h1.3c12.4-23.5 42.69-48.3 87.88-48.3 94 0 111.28 61.9 111.28 142.3V448z"/></svg>Linkedin</span>
                                    </a>
                                </li>
                                <li class="ep-event-social-icon">
                                    <a href="https://api.whatsapp.com/send?text=<?php echo $social_links_url; ?>" target="_blank" title="<?php esc_html_e('Share on Whatsapp', 'eventprime-event-calendar-management'); ?>">
                                        <span class="ep-social-title"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!-- Font Awesome Pro 5.15.4 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) --><path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"/></svg>Whatsapp</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div><?php
                }
            }
        }
    }

    /**
     * Add event date on the event
     * 
     * @param object $event Event data.
     * 
     * @return void
     */
    public function ep_event_add_event_dates( $event, $view ) {
        if( $event && ! empty( $event->id ) ) { 
            if ( ! empty( $event->em_start_date ) ) {
                $event_date_time = ep_convert_event_date_time_from_timezone( $event );
                $start_date = $event->em_start_date;
                if( ! empty( $event->em_end_date ) ) {
                    $end_date = $event->em_end_date;
                    if( $view == 'list' ) {
                        if( ! ep_show_event_date_time( 'em_start_date', $event ) ) {
                            if( ! empty( $event->em_event_date_placeholder ) ) {
                                if( $event->em_event_date_placeholder == 'tbd' ) {
                                    $tbd_icon_file = EP_BASE_URL .'/includes/assets/images/tbd-icon.png';?>
                                    <span class="ep-card-event-date-start ep-text-primary">
                                        <img src="<?php echo esc_url( $tbd_icon_file );?>" width="35" />
                                    </span><?php
                                } else{
                                    if( ! empty( $event->em_event_date_placeholder_custom_note ) ) {?>
                                        <span class="ep-card-event-date-start ep-text-primary">
                                            <?php echo esc_html( $event->em_event_date_placeholder_custom_note );?>
                                        </span><?php
                                    }
                                }
                            }
                        } else{
                            if( $start_date == $end_date ) {?>
                                <span class="ep-event-date ep-fw-bold ep-text-dark"><?php
                                    echo esc_html__( date( 'D', $start_date ), 'eventprime-event-calendar-management' ) . esc_html( ', ' . $event->fstart_date );
                                    if( ! empty( $event->em_all_day ) || ( ep_show_event_date_time( 'em_start_time', $event ) && ( ! empty( $event->em_start_time ) ) ) ) {
                                        echo ',' . '&nbsp;';
                                    }?>
                                </span><?php
                                if( ! empty( $event->em_all_day ) ) {?>
                                    <span><?php echo esc_html__( 'All Day', 'eventprime-event-calendar-management' );?></span><?php
                                }
                                else{
                                    if( ep_show_event_date_time( 'em_start_time', $event ) && ( ! empty( $event->em_start_time ) ) ) {
                                        $event_change_start_time = ep_convert_event_time_from_timezone( $event );
                                        if( ! empty( $event_change_start_time ) ) {?>
                                            <span><?php echo esc_html( ep_convert_time_with_format( $event_change_start_time ) );?><?php
                                                if( ep_show_event_date_time( 'em_end_time', $event ) && ! empty( $event->em_end_time ) ) {
                                                    $event_change_end_time = ep_convert_event_time_from_timezone( $event, 1 );?>
                                                    <?php echo ' - ' . esc_html( ep_convert_time_with_format( $event_change_end_time ) );?><?php
                                                }?>
                                            </span><?php
                                        } else{?>
                                            <span><?php echo esc_html( ep_convert_time_with_format( $event->em_start_time ) );?><?php
                                                if( ep_show_event_date_time( 'em_end_time', $event ) && ! empty( $event->em_end_time ) ) {?>
                                                    <?php echo ' - ' . esc_html( ep_convert_time_with_format( $event->em_end_time ) );?><?php
                                                }?>
                                            </span><?php
                                        }
                                    }
                                }
                            } else{?>
                                <span class="ep-fw-bold ep-text-dark"><?php
                                    echo esc_html__( date( 'D', $start_date ), 'eventprime-event-calendar-management' ) . esc_html( ', ' . $event->fstart_date );
                                    if( ep_show_event_date_time( 'em_end_date', $event ) && ( ! empty( $event->em_end_date ) ) ) {?>
                                        <span><?php echo ' - ' . esc_html( $event->fend_date );?></span><?php
                                    }?>
                                </span><?php
                            }
                        }
                    } else{
                        if( ! ep_show_event_date_time( 'em_start_date', $event ) ) {
                            if( ! empty( $event->em_event_date_placeholder ) ) {
                                if( $event->em_event_date_placeholder == 'tbd' ) {
                                    $tbd_icon_file = EP_BASE_URL .'/includes/assets/images/tbd-icon.png';?>
                                    <span class="ep-card-event-date-start ep-text-primary">
                                        <img src="<?php echo esc_url( $tbd_icon_file );?>" width="35" />
                                    </span><?php
                                } else{
                                    if( ! empty( $event->em_event_date_placeholder_custom_note ) ) {?>
                                        <span class="ep-card-event-date-start ep-text-primary">
                                            <?php echo esc_html( $event->em_event_date_placeholder_custom_note );?>
                                        </span><?php
                                    }
                                }
                            }
                        } else{
                            if( empty( $event->em_start_time ) || ! ep_show_event_date_time( 'em_start_time', $event ) ) {?>
                                <span class="ep-card-event-date-start ep-text-primary hunny">
                                    <?php echo esc_html__( date( 'D', $event->em_start_date ), 'eventprime-event-calendar-management' ) . esc_html( ', ' . $event->fstart_date );?>
                                </span><?php
                                if( ! empty( $event->em_all_day ) ) {?>
                                    <span> <?php echo ', ' . esc_html__( 'All Day', 'eventprime-event-calendar-management' );?></span><?php
                                }
                            } else{
                                if( ! empty( $event_date_time ) ) {?>
                                    <span class="ep-card-event-date-start ep-text-primary">
                                        <?php echo esc_html__( $event_date_time, 'eventprime-event-calendar-management' );?>
                                    </span><?php
                                } else{?>
                                    <span class="ep-card-event-date-start ep-text-primary">
                                        <?php echo esc_html__( date( 'D', $event->em_start_date ), 'eventprime-event-calendar-management' ) . esc_html( ', ' . $event->fstart_date );?>
                                    </span>
                                    <span class="ep-card-event-time-start ep-text-primary">
                                        <?php echo ', ' . esc_html( ep_convert_time_with_format( $event->em_start_time ) );?>
                                    </span><?php
                                }
                            }
                        }
                    }
                } else{
                    if( $view == 'list' ) {
                        if( ! ep_show_event_date_time( 'em_start_date', $event ) ) {
                            if( ! empty( $event->em_event_date_placeholder ) ) {
                                if( $event->em_event_date_placeholder == 'tbd' ) {
                                    $tbd_icon_file = EP_BASE_URL .'/includes/assets/images/tbd-icon.png';?>
                                    <span class="ep-card-event-date-start ep-text-primary">
                                        <img src="<?php echo esc_url( $tbd_icon_file );?>" width="35" />
                                    </span><?php
                                } else{
                                    if( ! empty( $event->em_event_date_placeholder_custom_note ) ) {?>
                                        <span class="ep-card-event-date-start ep-text-primary">
                                            <?php echo esc_html( $event->em_event_date_placeholder_custom_note );?>
                                        </span><?php
                                    }
                                }
                            }
                        } else{?>
                            <span class="ep-fw-bold ep-text-dark">
                                <?php echo esc_html__( date( 'D', $start_date ), 'eventprime-event-calendar-management' ) . esc_html( ', ' . $event->fstart_date );?>
                            </span><?php
                        }
                    } else{
                        if( ! ep_show_event_date_time( 'em_start_date', $event ) ) {
                            if( ! empty( $event->em_event_date_placeholder ) ) {
                                if( $event->em_event_date_placeholder == 'tbd' ) {
                                    $tbd_icon_file = EP_BASE_URL .'/includes/assets/images/tbd-icon.png';?>
                                    <span class="ep-card-event-date-start ep-text-primary">
                                        <img src="<?php echo esc_url( $tbd_icon_file );?>" width="35" />
                                    </span><?php
                                } else{
                                    if( ! empty( $event->em_event_date_placeholder_custom_note ) ) {?>
                                        <span class="ep-card-event-date-start ep-text-primary">
                                            <?php echo esc_html( $event->em_event_date_placeholder_custom_note );?>
                                        </span><?php
                                    }
                                }
                            }
                        } else{
                            if( empty( $event->em_start_time ) || ! ep_show_event_date_time( 'em_start_time', $event ) ) {?>
                                <span class="ep-card-event-date-start ep-text-primary hb">
                                    <?php echo esc_html__( date( 'D', $event->em_start_date ), 'eventprime-event-calendar-management' ) . esc_html( ', ' . $event->fstart_date );?>
                                </span><?php
                            } else{
                                if( ! empty( $event_date_time ) ) {?>
                                    <span class="ep-card-event-date-start ep-text-primary ll">
                                        <?php echo esc_html( $event_date_time );?>
                                    </span><?php
                                } else{?>
                                    <span class="ep-card-event-date-start ep-text-primary mm">
                                        <?php echo esc_html__( date( 'D', $event->em_start_date ), 'eventprime-event-calendar-management' ) . esc_html( ', ' . $event->fstart_date );?>
                                    </span>
                                    <span class="ep-card-event-time-start ep-text-primary">
                                        <?php echo ', ' . esc_html( ep_convert_time_with_format( $event->em_start_time ) );?>
                                    </span><?php
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Add event price on the event
     * 
     * @param object $event Event data.
     * 
     * @return void
     */
    public function ep_event_add_event_price( $event, $view = '' ) {
        if( ! empty( $view ) && $view == 'card' ) {?>
            <div class="ep-event-list-price ep-text-dark ep-di-flex ep-align-items-center ep-mt-auto"><?php
                if( $event && ! empty( $event->id ) && $event->em_enable_booking != 'external_bookings' ) { 
                    if ( ! empty( $event->ticket_price_range ) ) {?>
                        <span class="material-icons-outlined ep-align-middle ep-fs-5 ep-mr-1">confirmation_number</span><?php
                        if ( isset( $event->ticket_price_range['multiple'] ) && $event->ticket_price_range['multiple'] == 1 ) { 
                            if( $event->ticket_price_range['min'] == $event->ticket_price_range['max'] ) {?>
                                <span class="ep-fw-bold ep-ml-1"><?php 
                                    echo ' ';
                                    if( ! empty( $event->ticket_price_range['min'] ) ) {
                                        echo esc_html( ep_price_with_position( $event->ticket_price_range['min'] ) );
                                    } else{
                                        ep_show_free_event_price( $event->ticket_price_range['min'] );
                                    }?>
                                </span><?php
                            } else{?>
                                <?php esc_html_e( 'Starting', 'eventprime-event-calendar-management' );?>
                                <span class="ep-fw-bold ep-ml-1">
                                    <?php echo ' ' . esc_html( ep_price_with_position( $event->ticket_price_range['min'] ) ); ?>
                                </span><?php
                            }
                        } else { ?>
                            <span class="ep-fw-bold ep-ml-1"><?php
                                echo ' ';
                                if( ! empty( $event->ticket_price_range['price'] ) ){
                                    echo esc_html( ep_price_with_position( $event->ticket_price_range['price'] ) );
                                } else{
                                    ep_show_free_event_price( $event->ticket_price_range['price'] );
                                } ?>
                            </span><?php
                        }
                    } else{
                        echo '&nbsp;';
                    }
                }?>
            </div><?php
        } else{
            if( $event && ! empty( $event->id ) && $event->em_enable_booking != 'external_bookings' ) { 
                if ( ! empty( $event->ticket_price_range ) ) {?>
                    <div class="ep-event-list-price ep-my-2 ep-text-dark ep-align-items-center ep-d-flex">
                        <span class="material-icons-outlined ep-align-middle ep-fs-5 ep-mr-1">confirmation_number</span><?php
                        if ( isset( $event->ticket_price_range['multiple'] ) && $event->ticket_price_range['multiple'] == 1 ) { 
                            if( $event->ticket_price_range['min'] == $event->ticket_price_range['max'] ) {?>
                                <span class="ep-fw-bold ep-lh-0 ep-ml-1"><?php 
                                    echo ' ';
                                    if( ! empty( $event->ticket_price_range['min'] ) ) {
                                        echo esc_html( ep_price_with_position( $event->ticket_price_range['min'] ) );
                                    } else{
                                        ep_show_free_event_price( $event->ticket_price_range['min'] );
                                    }?>
                                </span><?php
                            } else{?>
                                <?php esc_html_e( 'Starting', 'eventprime-event-calendar-management' );?>
                                <span class="ep-fw-bold ep-lh-0 ep-ml-1">
                                    <?php echo ' ' . esc_html( ep_price_with_position( $event->ticket_price_range['min'] ) ); ?>
                                </span><?php
                            }
                        } else { ?>
                            <span class="ep-fw-bold ep-lh-0 ep-ml-1"><?php
                                echo ' ';
                                if( ! empty( $event->ticket_price_range['price'] ) ) {
                                    echo esc_html( ep_price_with_position( $event->ticket_price_range['price'] ) );
                                } else{
                                    ep_show_free_event_price( $event->ticket_price_range['price'] );
                                } ?>
                            </span><?php
                        } ?>
                    </div><?php
                }
            }
        }
    }

    /**
     * Add event booking button on the event
     * 
     * @param object $event Event data.
     * 
     * @return void
     */
    public function ep_event_add_event_booking_button( $event ) {
        $options = array();
        $settings          = EventM_Factory_Service::ep_get_instance( 'EventM_Admin_Model_Settings' );
        $options['global'] = $settings->ep_get_settings();
        $global_options = $options['global'];
        $em_custom_link   = get_post_meta( $event->id, 'em_custom_link', true );
        if ( $global_options->redirect_third_party == 1 && $event->em_enable_booking == 'external_bookings' ) {
            $url = esc_url( $em_custom_link );
        } else {
            $url = esc_url( $event->event_url );
        }
        if( $event && ! empty( $event->id ) ) { 
            $view_details_text = ep_global_settings_button_title('View Details');
            $new_window = ( ! empty( ep_get_global_settings( 'open_detail_page_in_new_tab' ) ) ? 'target="_blank"' : '' );
            if( check_event_has_expired( $event ) ) {
				// means event has ended. So user can only view the event detail.?>
				<a href="<?php echo esc_url( $event->event_url );?>" <?php echo esc_attr( $new_window );?>>
					<div class="ep-btn ep-btn-dark ep-box-w-100 ep-my-0 ep-py-2">
						<span class="ep-fw-bold ep-text-small">
						    <?php echo esc_html( $view_details_text ); ?>
						</span>
					</div>
				</a><?php
			} else{
				$event_controller = EventM_Factory_Service::ep_get_instance( 'EventM_Event_Controller_List' );
				if( ! empty( $event->em_enable_booking ) ) {
					if( $event->em_enable_booking == 'bookings_off' ) {?>
                        <a href="<?php echo esc_url( $event->event_url );?>" <?php echo esc_attr( $new_window );?>>
							<div class="ep-btn ep-btn-dark ep-box-w-100 ep-my-0 ep-py-2">
								<span class="ep-fw-bold ep-text-small">
                                    <?php echo esc_html( $view_details_text ); ?>
								</span>
							</div>
						</a><?php
					} elseif( $event->em_enable_booking == 'external_bookings' ) {
						if( empty( $event->em_custom_link_new_browser ) ) {
							$new_window = '';
						}?>
						<a href="<?php echo $url ;?>" <?php echo esc_attr( $new_window );?>>
							<div class="ep-btn ep-btn-dark ep-box-w-100 ep-my-0 ep-py-2">
								<span class="ep-fw-bold ep-text-small">
                                    <?php echo esc_html( $view_details_text ); ?>
								</span>
							</div>
						</a><?php
					} else{
						// check for booking status 
						if( ! empty( $event->all_tickets_data ) ) {
							$check_for_booking_status = $event_controller->check_for_booking_status( $event->all_tickets_data, $event );
							if( ! empty( $check_for_booking_status ) ) {
								if( $check_for_booking_status['status'] == 'not_started' ) {?>
									<div class="ep-btn ep-btn-light ep-box-w-100 ep-my-0 ep-py-2">
										<span class="material-icons-outlined ep-align-middle ep-text-muted ep-fs-6">history_toggle_off</span>
										<span class="ep-text-muted ep-text-smaller"><em>
                                            <?php echo esc_html( $check_for_booking_status['message'] );?>
										</em></span>
									</div><?php
								} elseif( $check_for_booking_status['status'] == 'off' ) {?>
									<div class="ep-btn ep-btn-light ep-box-w-100 ep-my-0 ep-py-2">
										<span class="material-icons-outlined ep-align-middle ep-text-muted ep-fs-6">block</span>
										<span class="ep-text-muted ep-text-small"><em>
                                            <?php echo esc_html( $check_for_booking_status['message'] );?>
										</em></span>
									</div><?php
								} else{?>
									<a href="<?php echo esc_url( $event->event_url );?>" <?php echo esc_attr( $new_window );?>><?php
										if( $check_for_booking_status['message'] == 'Free' ) {?>
											<div class="ep-btn ep-btn-dark ep-box-w-100 ep-my-0 ep-p-2"><?php
										} else{?>
											<div class="ep-btn ep-btn-warning ep-box-w-100 ep-my-0 ep-p-2"><?php
										}?>
											<span class="ep-fw-bold ep-text-small">
                                                <?php echo  esc_html__( $check_for_booking_status['message'], 'eventprime-event-calendar-management' );?>
											</span>
										</div>
									</a><?php
								}
							}
						} else{?>
                            <a href="<?php echo esc_url( $event->event_url );?>" <?php echo esc_attr( $new_window );?>>
                                <div class="ep-btn ep-btn-dark ep-box-w-100 ep-my-0 ep-py-2">
                                    <span class="ep-fw-bold ep-text-small">
                                        <?php echo esc_html( $view_details_text ); ?>
                                    </span>
                                </div>
                            </a><?php
                        }
					}
                } else{?>
                    <a href="<?php echo esc_url( $event->event_url );?>" <?php echo esc_attr( $new_window );?>>
                        <div class="ep-btn ep-btn-dark ep-box-w-100 ep-my-0 ep-py-2">
                            <span class="ep-fw-bold ep-text-small">
                                <?php echo esc_html( $view_details_text ); ?>
                            </span>
                        </div>
                    </a><?php
                }
            }
        }
    }

    /**
     * Add weather widget on the event detail page
     * 
     * @param object $venue Venue data.
     * 
     * @return void
     */
    public function ep_event_detail_add_weather_widget( $venue ) {
        if( ! empty( $venue ) && ! empty( $venue->em_place_id ) ) {
            $place_url = @file_get_contents( 'https://forecast7.com/api/getUrl/' . $venue->em_place_id );
            
            if( empty( $place_url ) ) {
                // call autocomplete api with state and country
                if( ! empty( $venue->em_state ) && ! empty( $venue->em_country ) ) {
                    $autocomplete_api = 'https://forecast7.com/api/autocomplete/'.$venue->em_state.'/'.$venue->em_country.'/';
                    if( empty( $autocomplete_api ) ) {
                        $autocomplete_api = 'https://forecast7.com/api/autocomplete/'.$venue->em_state.', '.$venue->em_country.'/';
                    }
                    if( $autocomplete_api ) {
                        $autocomplete_data = @file_get_contents( $autocomplete_api );
                        if( ! empty( $autocomplete_data ) ) {
                            $place_data = json_decode( $autocomplete_data );
                            if( ! empty( $place_data ) && ! empty( $place_data[0] ) ) {
                                if( ! empty( $place_data[0]->place_id ) ) {
                                    $place_url = @file_get_contents( 'https://forecast7.com/api/getUrl/' . $place_data[0]->place_id );
                                }
                            }
                        }
                    }
                }
            }
            $temp_unit = ep_get_global_settings('weather_unit_fahrenheit');
            if(!empty($temp_unit) && $temp_unit == 1){
                $place_url .='/?unit=us';
            }else{
                $place_url .='/';
            }
            
            //echo 'https://forecast7.com/en/'.$place_url;
            if( ! empty( $place_url ) ) {?>
                <a class="weatherwidget-io" href="https://forecast7.com/en/<?php echo esc_html( $place_url );?>" data-label_1="<?php echo esc_html( $venue->em_locality );?>" data-label_2="WEATHER" data-theme="pure" ><?php echo esc_html( $venue->em_locality );?> <?php esc_html_e( 'WEATHER', 'eventprime-event-calendar-management' ); ?></a>
                <script>
                    !function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src='https://weatherwidget.io/js/widget.min.js';fjs.parentNode.insertBefore(js,fjs);}}(document,'script','weatherwidget-io-js');
                </script><?php
            } else{?>
                <div class="ep-alert ep-alert-warning ep-mt-3">
                    <?php esc_html_e( 'No data found.', 'eventprime-event-calendar-management' ); ?>
                </div><?php
            }
        } else{?>
            <div class="ep-alert ep-alert-warning ep-mt-3">
                <?php esc_html_e( 'No data found.', 'eventprime-event-calendar-management' ); ?>
            </div><?php
        }
    }

    /**
     * Show timezone related content before event list
     */
    public function ep_show_timezone_related_message( $args ) {
        $enable_event_time_to_user_timezone  = ep_get_global_settings( 'enable_event_time_to_user_timezone' );
        if( ! empty( $enable_event_time_to_user_timezone ) ) { 
            $show_timezone_message_on_event_page = ep_get_global_settings( 'show_timezone_message_on_event_page' );
            if( ! empty( $show_timezone_message_on_event_page ) ) {
                $timezone_related_message = ep_get_global_settings( 'timezone_related_message' );
                if( empty( $timezone_related_message ) ) {
                    $timezone_related_message = esc_html__( 'All the event times coming as per {{$timezone}} timezone.', 'eventprime-event-calendar-management' );
                }
                if( strpos( $timezone_related_message, '{{$timezone}}' ) !== false ) {
                    $current_timezone = ep_get_current_user_timezone();
                    if( empty( $current_timezone ) ) {
                        $current_timezone = ep_get_site_timezone();
                    }
                    // replace the variable to timezone
                    $timezone_related_message = str_replace( '{{$timezone}}', $current_timezone, $timezone_related_message );
                }?>
                <div class="ep-timezone-wrap ep-box-wrap">
                    <div class="ep-box-row ep-mb-3">
                        <div class="ep-box-col-12">
                            <?php echo esc_html( $timezone_related_message );?>
                            <span class="ep-user-profile-timezone-wrap">
                                <span class="material-icons-round ep-fs-6 ep-align-middle" id="ep-user-profile-timezone-edit">edit</span>&nbsp;&nbsp;
                                <span class="ep-user-profile-timezone-list" style="display: none;">
                                    <select name="ep_user_timezone" id="ep_user_profile_timezone_list" class="ep-form-input ep-input-text">
                                        <?php echo wp_timezone_choice( $current_timezone );?>
                                    </select>
                                    <button type="button" class="ep-btn ep-btn-primary ep-btn-sm" id="ep_user_profile_timezone_save"><?php esc_html_e( 'Save', 'eventprime-event-calendar-management' ); ?></button>
                                </span>
                            </span>
                        </div>
                    </div>
                </div><?php
            }
        }
    }

    public function ep_add_body_class( $classes ) {
    	$class = 'theme-' . get_template();
    	if( is_array( $classes ) ) {
    		$classes[] = $class;
    	} else{
    		$classes .= ' ' . $class . ' ';
    	}
    	return $classes;
    }
    
    /**
     * Show booking status on the event views
     */
    public function ep_event_booking_count( $event ) {
        if( ! empty( $event ) ) {
            $event_booking_status_option = ep_get_global_settings( 'event_booking_status_option' );
            if( ! empty( $event_booking_status_option ) ) {?>
                <div class="ep-text-small ep-event-booking-status ep-text-dark ep-d-flex ep-align-items-center ep-mt-2 ep-mb-3"><?php
                    if( ! empty( $event->em_enable_booking ) && $event->em_enable_booking != 'external_bookings' ) {
                        if( ! check_event_has_expired( $event ) && ! empty( $event->all_tickets_data ) ) {
                            $enable_status = isset( $event->em_hide_booking_status ) ? $event->em_hide_booking_status : 0;
                            if( empty( $enable_status ) ) {
                                // show bragraphs
                                if( $event_booking_status_option == 'bargraph' ) {
                                    $total_tickets = $attendee_count = $width = 0; 
                                    $total_booking = EventM_Factory_Service::get_total_booking_number_by_event_id( $event->em_id );
                                    if( ! empty( $event->all_tickets_data ) ) {
                                        foreach( $event->all_tickets_data as $tickets ) {
                                            //$check_ticket_available = EventM_Factory_Service::check_for_ticket_available_for_booking( $tickets, $event );
                                            $total_tickets = $total_tickets + $tickets->capacity;
                                        }
                                    }
                                    if( $total_tickets > 0 ) {
                                        if( $total_booking > 0  && $total_tickets > 0 ) {
                                            $width = ( $total_booking / $total_tickets ) * 100;
                                            $width = number_format( (float)$width, 2 );
                                        }?>
                                        <div class="ep-event-ticket-progress ep-box-w-100">
                                            <div class="ep-event-ticket-count">
                                                <?php echo $total_booking.'/'.$total_tickets;?>
                                            </div>
                                            <div class="ep-event-ticket-progress-bar ep-progress">
                                                <div class="ep-progress-bar" style="width:<?php echo $width.'%';?>"></div>
                                            </div>
                                        </div><?php
                                    }
                                }
                                // show ticket left
                                if( $event_booking_status_option == 'ticket_left' ) {
                                    $available_tickets = EventM_Factory_Service::get_event_available_tickets( $event );
                                    if( ! empty( $available_tickets ) ) {?>
                                        <span class="ep-text-muted">
                                            <?php esc_html_e( 'Hurry', 'eventprime-event-calendar-management' ); ?>, 
                                            <?php echo absint( $available_tickets );?> 
                                            <?php esc_html_e( 'tickets left!', 'eventprime-event-calendar-management' ); ?>
                                        </span><?php
                                    }
                                }
                            }
                        } else{
                            echo '';
                        }
                    } else{
                        echo '';
                    }?>
                </div><?php
            }
        }
    }

    public function ep_bulk_post_updated_messages_filter( $bulk_messages, $bulk_counts ) {
        $bulk_messages['em_event'] = array(
            'updated'   => _n( '%s event updated.', '%s events updated.', $bulk_counts['updated'] ),
            'deleted'   => _n( '%s event permanently deleted.', '%s events permanently deleted.', $bulk_counts['deleted'] ),
            'trashed'   => _n( '%s event moved to the Trash.', '%s events moved to the Trash.', $bulk_counts['trashed'] ),
            'untrashed' => _n( '%s event restored from the Trash.', '%s events restored from the Trash.', $bulk_counts['untrashed'] ),
        );
    
        return $bulk_messages;
    }

    /**
     * Load event dates section on the event detail page
     * 
     * @param object $event Event Data.
     */
    public function ep_event_detail_right_event_dates_section( $event ) {
        if( ! empty( $event ) ) {?>
            <div class="ep-btn-group ep-ticket-btn-radio" role="group" aria-label="ep-ticket-radio"><?php
                if( empty( $event->post_parent ) ) {
                    $no_load = 'no-load';
                    if( count( $event->child_events ) > 0 ) {
                        $no_load = '';
                    }?>
                    <input type="radio" class="ep-btn-check" name="em_single_event_ticket_date" id="ep_single_event_date1" autocomplete="off" data-event_id="<?php echo esc_attr( $event->id );?>" checked data-no_load="<?php echo esc_attr( $no_load );?>">
                    <label class="ep-btn ep-text-small ep-fw-bold ep-py-1 ep-px-2 ep-btn-outline-secondary ep-border-2 ep-rounded-1 ep_event_ticket_date_option" id="ep_child_event_id_<?php echo esc_attr( $event->id );?>" for="ep_single_event_date1"><?php echo esc_html( ep_timestamp_to_date( $event->em_start_date, 'd M', 1 ) );?></label>
                    <?php if( count( $event->child_events ) > 0 ) {
                        $ev = 2;
                        foreach( $event->child_events as $events ) {?>
                            <input type="radio" class="ep-btn-check" name="em_single_event_ticket_date" id="ep_single_event_date<?php echo esc_attr( $ev );?>" autocomplete="off" data-event_id="<?php echo esc_attr( $events->id );?>">
                            <label class="ep-btn ep-text-small ep-fw-bold ep-py-1 ep-px-2 ep-btn-outline-secondary ep-border-2 ep-rounded-1 ep_event_ticket_date_option" id="ep_child_event_id_<?php echo esc_attr( $events->em_id );?>" for="ep_single_event_date<?php echo esc_attr( $ev );?>"><?php echo esc_html( ep_timestamp_to_date( $events->em_start_date, 'd M', 1 ) );?></label><?php
                            $ev++;
                        }
                    }
                } else{
                    $event_controller = EventM_Factory_Service::ep_get_instance( 'EventM_Event_Controller_List' );
                    //$parent_event_data = $event_controller->get_single_event( $event->post_parent );
                    $parent_event_id = $event->post_parent;
                    $parent_event_start_date = get_post_meta( $parent_event_id, 'em_start_date', true );
                    $all_child_event_data = $event_controller->get_event_child_data_by_parent_id( $parent_event_id, array( 'em_start_date' ) );
                    if( ! empty( $all_child_event_data ) && count( $all_child_event_data ) > 0 ) {?>
                        <input type="radio" class="ep-btn-check" name="em_single_event_ticket_date" id="ep_single_event_date1" autocomplete="off" data-event_id="<?php echo esc_attr( $parent_event_id );?>">
                        <label class="ep-btn ep-text-small ep-fw-bold ep-py-1 ep-px-2 ep-btn-outline-secondary ep-border-2 ep-rounded-1 ep_event_ticket_date_option" id="ep_child_event_id_<?php echo esc_attr( $parent_event_id );?>" for="ep_single_event_date1"><?php echo esc_html( ep_timestamp_to_date( $parent_event_start_date, 'd M', 1 ) );?></label><?php
                        $ev = 2;
                        foreach( $all_child_event_data as $child_events ) {
                            $checked = '';
                            if( $child_events->ID == $event->id ) $checked = 'checked';?>
                            <input type="radio" class="ep-btn-check" name="em_single_event_ticket_date" id="ep_single_event_date<?php echo esc_attr( $ev );?>" autocomplete="off" data-event_id="<?php echo esc_attr( $child_events->ID );?>" <?php echo esc_attr( $checked );?>>
                            <label class="ep-btn ep-text-small ep-fw-bold ep-py-1 ep-px-2 ep-btn-outline-secondary ep-border-2 ep-rounded-1 ep_event_ticket_date_option" id="ep_child_event_id_<?php echo esc_attr( $child_events->ID );?>" for="ep_single_event_date<?php echo esc_attr( $ev );?>">
                                <?php echo esc_html( ep_timestamp_to_date( $child_events->em_start_date, 'd M', 1 ) );?>
                            </label><?php
                            $ev++;
                        }
                    }
                }?>
            </div><?php
            if( empty( $no_load ) ) {?>
                <div class="ep-move-left ep-position-absolute ep-cursor"><span class="material-icons-outlined">chevron_left</span></div>
                <div class="ep-move-right ep-position-absolute ep-cursor"><span class="material-icons-outlined">chevron_right</span></div><?php
            }
        }
    }

    // dequeue already enqueues scripts
    public function ep_dequeue_event_scripts( $scripts ) {
        if( ! empty( $scripts ) ) {
            foreach( $scripts as $script ) {
                wp_deregister_script( $script );
                wp_dequeue_script( $script );
            }
        }
    }

    // add loader on the page
    public function ep_add_loader_section( $default = 'none' ) {
        $style = 'style=display:none;';
        if( $default == 'show' ) {
            $style = 'style=display:flex;';
        }?>
        <div class="ep-event-loader" role="alert" aria-live="polite" <?php echo esc_attr( $style );?>>
            <div class="ep-event-loader-circles-wrap">
                <svg class="ep-event-loader-circle-icon ep-event-loader-circle-icon-dot ep-event-loader-circle-dot ep-event-loader-first" viewBox="0 0 15 15" xmlns="http://www.w3.org/2000/svg"><circle cx="7.5" cy="7.5" r="7.5"></circle></svg>
                <svg class="ep-event-loader-circle-icon ep-event-loader-circle-icon-dot ep-event-loader-circle-dot ep-event-loader-second" viewBox="0 0 15 15" xmlns="http://www.w3.org/2000/svg"><circle cx="7.5" cy="7.5" r="7.5"></circle></svg>
                <svg class="ep-event-loader-circle-icon ep-event-loader-circle-icon-dot ep-event-loader-circle-dot ep-event-loader-third" viewBox="0 0 15 15" xmlns="http://www.w3.org/2000/svg"><circle cx="7.5" cy="7.5" r="7.5"></circle></svg>
            </div>
        </div><?php
    }

    /**
	 * Custom Style
	 */
	public function ep_custom_styles() {
		$custom_css = ep_get_global_settings( 'custom_css' );
		if ( false !== $custom_css ) {
			echo '<style type="text/css">' . esc_attr( $custom_css ) . '</style>';
		}
	}

    /**
     * Update parent event status
     */
    public function ep_update_parent_event_status( $post_id, $post ) {
        if( $post->post_type == 'em_event' && $post->post_status !== 'trash') {
            if( $post->post_status !== 'publish' ) {
                $postData = [ 'ID' => $post->ID, 'post_status' => 'publish' ];
                wp_update_post( $postData );
                if( get_post_meta( $post_id, 'em_frontend_submission', true ) == 1 && get_post_meta( $post_id, 'em_user_submitted', true ) == 1){
                    EventM_Notification_Service::event_approved($post_id);
                }
            }
        }
    }

    /**
     * Update event data after save
     */
    public function ep_update_event_data_after_save( $post_id, $post ) {
        if( $post->post_type == 'em_event' && $post->post_status !== 'trash') {
            if( $post->post_status !== 'publish' ) {
                $postData = [ 'ID' => $post->ID, 'post_status' => 'publish' ];
                wp_update_post( $postData );
                if( get_post_meta( $post_id, 'em_frontend_submission', true ) == 1 && get_post_meta( $post_id, 'em_user_submitted', true ) == 1){
                    EventM_Notification_Service::event_approved($post_id);
                }
            }
            if( $post->post_status == 'private' ) {
                $postData = [ 'ID' => $post->ID, 'post_status' => 'private' ];
                wp_update_post( $postData );
            }
        }
    }

    /**
	 * Load single template
	 */
        public function ep_load_single_template( $template ) 
        {
            if (is_tax(EM_EVENT_TYPE_TAX) || is_tax(EM_EVENT_VENUE_TAX) || is_tax(EM_EVENT_ORGANIZER_TAX)  )
            {
                if(is_tax(EM_EVENT_TYPE_TAX)) 
                {
                    //print_r($template);die;
                    $template_file = 'event_types/views/single-ep-event-type.php';
                }
                elseif(is_tax(EM_EVENT_VENUE_TAX)) 
                {
                    $template_file = 'venues/views/single-ep-venue.php';
                } 
                elseif(is_tax(EM_EVENT_ORGANIZER_TAX)) 
                {
                    $template_file = 'organizers/views/single-ep-event-organizer.php';
                }

                // Check if the template file exists in the plugin directory
                $plugin_template = EP_BASE_DIR . 'templates/' . $template_file;
                
                if (file_exists($plugin_template)) {
                    return $plugin_template;
                }
            }
        // If the template file doesn't exist in the plugin, return the original template
        return $template;
        

	}
        
        public function ep_load_single_template_backup( $template ) {
		if( is_single() ) {
			if( get_post_type() == EM_EVENT_POST_TYPE ) {
				if( function_exists('wp_is_block_theme') && wp_is_block_theme() ) {
					add_filter( 'the_content', array( $this, 'ep_block_theme_single_event_content' ) );
					return $template;
				}
				$template = locate_template( 'single-' . EM_EVENT_POST_TYPE . '.php' );
				if( $template == '' ) {
					$template = ep_get_template_part( 'events/single-ep-event' );
				}
			} elseif( get_post_type() == EM_PERFORMER_POST_TYPE ) {
                if( function_exists('wp_is_block_theme') && wp_is_block_theme() ) {
					add_filter( 'the_content', array( $this, 'ep_block_theme_single_performer_content' ) );
					return $template;
				}
				$template = locate_template( 'single-' . EM_PERFORMER_POST_TYPE . '.php' );
				if( $template == '' ) {
					$template = ep_get_template_part( 'performers/single-ep-performer' );
				}
			} else{
				$extensions = (array)EP()->extensions;
				if( ! empty( $extensions ) && in_array( 'sponsor', $extensions ) ) {
					if( get_post_type() == EM_SPONSOR_POST_TYPE ) {
                        if( function_exists('wp_is_block_theme') && wp_is_block_theme() ) {
                            add_filter( 'the_content', array( $this, 'ep_block_theme_single_sponsor_content' ) );
                            return $template;
                        }
						$template = locate_template( 'single-' . EM_SPONSOR_POST_TYPE . '.php' );
						if( $template == '' && defined( 'EMSPS_BASE_DIR' ) ) {
							$template = ep_get_template_part( 'single-ep-sponsor', null, null, EMSPS_BASE_DIR.'/includes' );
						}
					}
				}
			}
		} elseif( is_tax( EM_EVENT_TYPE_TAX ) ) {
			$template = locate_template('taxonomy-em-event-type.php');
			if( $template == '' ) {
				$template = ep_get_template_part( 'event_types/single-ep-event-type' );
			}
		} elseif( is_tax( EM_EVENT_VENUE_TAX ) ) {
			$template = locate_template('taxonomy-em-venue.php');
			if( $template == '' ) {
				$template = ep_get_template_part( 'venues/single-ep-venue' );
			}
		} elseif( is_tax( EM_EVENT_ORGANIZER_TAX ) ) {
			$template = locate_template('taxonomy-em-event-organizer.php');
			if( $template == '' ) {
				$template = ep_get_template_part( 'organizers/single-ep-event-organizer' );
			}
		}
		
		return $template;
	}
    
	

        public function ep_load_single_template_dynamic($content)
        {
            remove_filter( 'the_content', array( $this, 'ep_load_single_template_dynamic') );
            if( is_single() ) 
            {
                if( get_post_type() == EM_EVENT_POST_TYPE ) {
                    $content = $this->ep_block_theme_single_event_content($content);
                } 
                elseif( get_post_type() == EM_PERFORMER_POST_TYPE ) {
                    $content = $this->ep_block_theme_single_performer_content($content);
                } 
                elseif(defined('EM_SPONSOR_POST_TYPE') &&  get_post_type() == EM_SPONSOR_POST_TYPE )
                {
                    $extensions = (array)EP()->extensions;
                    if( ! empty( $extensions ) && in_array( 'sponsor', $extensions ) ) 
                    {
                        $content = $this->ep_block_theme_single_sponsor_content($content);
                    }
                }
            } 

               return $content;
        }
         
    public function ep_load_single_template_redirect(){
        if( get_post_type() == EM_EVENT_POST_TYPE ) {
            $template = locate_template( 'single-' . EM_EVENT_POST_TYPE . '.php' );
            if( $template == '' ) {
                $template = ep_get_template_part( 'events/single-ep-event' );
            }
            exit;
        }
        if( get_post_type() == EM_PERFORMER_POST_TYPE ) {
            $template = locate_template( 'single-' . EM_PERFORMER_POST_TYPE . '.php' );
            if( $template == '' ) {
                $template = ep_get_template_part( 'performers/single-ep-performer' );
            }
            exit;
        }
    }
    

    /**
     * Load single event content for block theme
     * 
     * @param string $content
     * 
     * @return string
     */
    public function ep_block_theme_single_event_content( $content ) {
        remove_filter( 'the_content', array( $this, 'ep_block_theme_single_event_content') );
        $post = get_post();
        if( $post->post_status !== 'trash' ) {
            $events = EventM_Factory_Service::ep_get_instance( 'EventM_Event_Controller_List' );
            $event_detail_page = $events->render_detail_template( array( 'id' => get_the_ID() ) );
            return $event_detail_page;
        } else{
            return;
        }
    }

    /**
     * Load single performer content for block theme
     * 
     * @param string $content
     * 
     * @return string
     */
    public function ep_block_theme_single_performer_content( $content ) {
        remove_filter( 'the_content', array( $this, 'ep_block_theme_single_performer_content') );
        $post = get_post();
        if( $post->post_status !== 'trash' ) {
            $performers = EventM_Factory_Service::ep_get_instance( 'EventM_Performer_Controller_List' );
            return $performers->render_detail_template( array( 'id' => get_the_ID() ) );
        } else{
            return;
        }
    }
    
    /**
     * Load single sponsor content for block theme
     * 
     * @param string $content
     * 
     * @return string
     */
    public function ep_block_theme_single_sponsor_content( $content ) {
        remove_filter( 'the_content', array( $this, 'ep_block_theme_single_sponsor_content') );
        $post = get_post();
        if( $post->post_status !== 'trash' ) {
            $sponsors = EventM_Factory_Service::ep_get_instance( 'EventM_Sponsor_Controller_List' );
            return $sponsors->render_detail_template( array( 'id' => get_the_ID() ) );
        } else{
            return;
        }
    }

    /**
     * Add event data like no. of booking, no. of attendees
     */
    public function ep_add_event_statisticts_data( $post ) {
        $event_id = $post->ID;
        // get total bookings data
        $booking_controller = EventM_Factory_Service::ep_get_instance( 'EventM_Booking_Controller_List' );
        $event_bookings = $booking_controller->get_event_bookings_by_event_id( $event_id );
        $event_booking_count = count( $event_bookings );?>
        <div class="ep-event-summary-data-list">
            <label><?php esc_html_e( 'Total Bookings:', 'eventprime-event-calendar-management' );?></label>
            <label>
                <?php esc_attr_e( $event_booking_count );
                if( ! empty( $event_booking_count ) ) {
                    $event_booking_url = admin_url( 'edit.php?s&post_status=all&post_type=em_booking&event_id=' . esc_attr( $event_id ) );?> 
                    <a href="<?php echo esc_url( $event_booking_url );?>" target="__blank">
                        <?php esc_html_e( 'View', 'eventprime-event-calendar-management' );?>
                    </a><?php
                }?>
            </label>
        </div><?php
        // get total attendees
        $total_booking_numbers = EventM_Factory_Service::get_total_booking_number_by_event_id( $event_id );?>
        <div class="ep-event-summary-data-list">
            <label><?php esc_html_e( 'Total Attendees:', 'eventprime-event-calendar-management' );?></label>
            <label>
                <?php esc_attr_e( $total_booking_numbers );
                if( ! empty( $total_booking_numbers ) ) {
                    $event_attendee_page_url = admin_url( 'admin.php?page=ep-event-attendees-list&event_id=' . esc_attr( $event_id ) );?> 
                    <a href="<?php echo esc_url( $event_attendee_page_url );?>" target="__blank">
                        <?php esc_html_e( 'View', 'eventprime-event-calendar-management' );?>
                    </a><?php
                }?>
            </label>
        </div><?php
    }

    /**
     * Add calendar icon
     */
    public function ep_event_add_calendar_icon( $event, $pag ) {?>
        <div class="ep-sl-event-action ep-cursor ep-position-relative ep-event-ical-action">
            <span class="material-icons-outlined ep-handle-share ep-button-text-color ep-mr-3 ep-cursor">calendar_month</span>
            <ul class="ep-event-share ep-m-0 ep-p-0" style="display:none;">
                <li class="ep-event-social-icon">
                    <a href="javascript:void(;);" title="<?php esc_html_e( '+ iCal Export','eventprime-event-calendar-management' ); ?>" id="ep_event_ical_export" data-event_id="<?php echo esc_attr( $event->em_id );?>">
                        <?php esc_html_e( '+ iCal Export','eventprime-event-calendar-management' ); ?>
                    </a>
                </li>
            </ul>
        </div><?php
    }

    /**
     * Premium banner
     */
    public function ep_add_custom_banner() {
        include_once EP_BASE_DIR . 'includes/core/admin/template/custom-banner.php';
    }
}

new EventM_Action_Service();