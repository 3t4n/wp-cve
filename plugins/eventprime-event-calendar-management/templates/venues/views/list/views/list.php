<?php
/**
 * View: Venues List - List View
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/eventprime/venues/list/views/list.php
 *
 */
?>
<?php foreach ( $args->venues->terms as $venue ) {?>
    <div class="ep-box-col-12 ep-list-view-main ep-mb-4">
        <div class="ep-box-row ep-bg-white ep-border ep-rounded ep-text-small ep-overflow-hidden">
            <div class="ep-box-col-3 ep-p-0 ep-bg-light ep-border-right ep-position-relative ">
                <a href="<?php echo esc_url( $venue->venue_url ); ?>" class="ep-img-link ep-d-flex">
                    <img src="<?php echo esc_url( $venue->image_url ); ?>" alt="<?php echo esc_attr( $venue->name ); ?>">
                </a>
            </div>
            <div class="ep-box-col-6 ep-p-4 ep-text-small">
                <div class="ep-box-list-items">
                    <div class="ep-box-title ep-box-list-title">
                        <a class="ep-color-hover" data-venue-id="<?php echo esc_attr( $venue->id ); ?>" href="<?php echo esc_url( $venue->venue_url ); ?>" target="_self" rel="noopener">
                            <?php echo esc_html( $venue->name ); ?>
                        </a>
                    </div>
                    
                    <div class="ep-mb-2 ep-text-small ep-text-muted ep-text-truncate"><?php 
                        if ( ! empty( $venue->em_address ) && ! empty( $venue->em_display_address_on_frontend ) ) {
                            echo wp_trim_words( $venue->em_address, 10 );
                        }?>
                    </div>

                    <?php if ( ! empty( $venue->description ) ) { ?>
                        <div class="ep-venue-description ep-content-truncate ep-content-truncate-line-3">
                            <?php echo wpautop( wp_kses_post( $venue->description ) ); ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="ep-box-col-3 ep-px-0 ep-pt-4 ep-border-left ep-position-relative">
                <div class="ep-venue-seating-capacity ep-mb-2 ep-px-2 ep-align-right">
                    <?php if ( !empty( $venue->em_type ) ) {?>
                        <div class="ep-event-attr-name ep-fw-bold"><?php echo esc_html__( 'Type', 'eventprime-event-calendar-management' ). ' : '. esc_html__( ep_get_venue_type_label( $venue->em_type ), 'eventprime-event-calendar-management'); ?></div>
                        <?php
                    }?>
                </div>
                <ul class="ep-box-social-links ep-px-2 ep-text-end ep-d-inline-flex ep-justify-content-end ep-box-w-100 ep-m-0 ">
                    <?php if ( isset( $venue->em_facebook_page ) && ! empty( $venue->em_facebook_page ) ) { ?>
                            <li class="ep-event-social-icon ep-mr-2">
                                <a class="facebook" href="<?php echo esc_url( $venue->em_facebook_page );?>" target="_blank" title="Facebook">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18px" viewBox="0 0 512 512">
                                        <path d="M504 256C504 119 393 8 256 8S8 119 8 256c0 123.78 90.69 226.38 209.25 245V327.69h-63V256h63v-54.64c0-62.15 37-96.48 93.67-96.48 27.14 0 55.52 4.84 55.52 4.84v61h-31.28c-30.8 0-40.41 19.12-40.41 38.73V256h68.78l-11 71.69h-57.78V501C413.31 482.38 504 379.78 504 256z"/>
                                    </svg>
                                </a>
                            </li>
                    <?php } ?>
                    <?php if ( isset( $venue->em_instagram_page ) && ! empty( $venue->em_instagram_page ) ) { ?>
                            <li class="ep-event-social-icon ep-mr-2">
                                <a class="instagram" href="<?php echo esc_url( $venue->em_instagram_page );?>" target="_blank" title="Instagram">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18px" viewBox="0 0 448 512">
                                        <path d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"/>
                                    </svg>
                                </a>
                            </li>
                    <?php } ?>                              
                </ul>
                    <div class="ep-align-self-end ep-position-absolute ep-p-2 ep-bg-white ep-box-w-100"  style="bottom:0">
                        <a class="ep-view-details-button" data-event-id="<?php echo esc_attr($venue->id); ?>" href="<?php echo esc_url($venue->venue_url); ?>">
                            <div class="ep-btn ep-btn-dark ep-box-w-100 ep-my-0 ep-py-2">
                                <span class="ep-fw-bold ep-text-small">
                                  <?php echo esc_html_e('View Detail', 'eventprime-event-calendar-management'); ?>								
                                </span>
                            </div>
                        </a>              
                    </div>
                
                
            </div>
        </div>
    </div><?php 
} ?>