<div class="emagic">
    <div class="ep-box-row ep-my-4">
        <div class="ep-box-col-6 ep-fs-5">
            <span><?php echo esc_html__( ep_get_greeting_text() );?>, </span>
            <span class="ep-fw-bold">
                <?php echo esc_html( ep_get_current_user_profile_name() );?>
            </span>
            <div class="ep-fs-6">
                <?php esc_html_e( 'You have', 'eventprime-event-calendar-management');?>
                <?php if( ! empty( $args->upcoming_bookings ) && count( $args->upcoming_bookings ) > 0 ) {?>
                    <span class="ep-bg-warning ep-rounded-5 ep-px-2 ep-py-1 ep-text-small ep-fw-bold"><?php echo absint( count( $args->upcoming_bookings ) );?></span><?php
                } else{
                    esc_html_e( 'No', 'eventprime-event-calendar-management');
                }?>&nbsp;
                <?php esc_html_e('events coming up!', 'eventprime-event-calendar-management');?>
            </div>
        </div>
        <div class="ep-box-col-6 ep-d-flex ep-items-center ep-content-right">
            <div class="ep-text-end">
                <a href="<?php echo wp_logout_url(); ?>">
                    <button type="button" class="ep-btn ep-btn-danger">
                        <span><?php esc_html_e( 'Logout', 'eventprime-event-calendar-management');?></span>
                        <span class="material-icons-round ep-fs-6 ep-align-middle">logout</span>
                    </button>
                </a>
            </div>
        </div>
    </div>
    <div class="ep-box-row ep-text-small ep-g-5">
        <div class="ep-box-col-3">
            <ul class="ep-list-group ep-myaccount-tabs ep-mx-0 ep-mb-3 ep-pl-0 ep-overflow-hidden" role="tablist">
                <li class="ep-list-group-item ep-text-center ep-py-4 ep-user-profile-avatar-tab" role="presentation">
                    <img class="ep-rounded-circle" src="<?php echo esc_url( get_avatar_url( $args->current_user->ID ) ); ?>" style="max-width:50%;">
                </li>
                <li class="ep-list-group-item ep-tab-item ep-user-profile-upcoming-booking-tab" role="presentation">
                    <a href="javascript:void(0);" data-tag="ep-coming-up-bookings" class="ep-list-group-item-light ep-tab-link ep-tab-active">
                        <?php esc_html_e( 'Coming Up', 'eventprime-event-calendar-management');?>
                        <span class="ep-bg-warning ep-rounded-5 ep-px-2 ep-py-1 ep-text-small ep-text-dark ep-fw-bold ep-ml-4"><?php echo absint( count( $args->upcoming_bookings ) );?></span>
                    </a>
                </li>
                <li class="ep-list-group-item ep-tab-item ep-user-profile-my-booking-tab" role="presentation">
                    <a href="javascript:void(0);" data-tag="ep-list-all-bookings" class="ep-list-group-item-ligh ep-tab-link">
                        <?php esc_html_e( 'My Bookings', 'eventprime-event-calendar-management');?>
                    </a>
                </li>
                
                <li class="ep-list-group-item ep-tab-item ep-user-profile-my-event-tab" role="presentation">
                    <a href="javascript:void(0);" data-tag="ep-list-my-events" class="ep-list-group-item-ligh ep-tab-link">
                        <?php esc_html_e( 'My Events', 'eventprime-event-calendar-management');?>
                    </a>
                </li>
                
                <li class="ep-list-group-item ep-tab-item ep-user-profile-my-wishlist-tab" role="presentation">
                    <a href="javascript:void(0);" data-tag="ep-list-my-list" class="ep-list-group-item-ligh ep-tab-link">
                        <?php esc_html_e( 'My Wishlists', 'eventprime-event-calendar-management');?>
                    </a>
                </li>
                
                <li class="ep-list-group-item ep-tab-item ep-user-profile-my-transaction-tab" role="presentation">
                    <a href="javascript:void(0);" data-tag="ep-list-transactions" class="ep-list-group-item-ligh ep-tab-link">
                        <?php esc_html_e( 'My Transactions', 'eventprime-event-calendar-management');?>
                    </a>
                </li>
                
                <?php echo do_action( 'ep_profile_tabs_list', $args->current_user );?>

                <li class="ep-list-group-item ep-tab-item ep-user-profile-my-account-tab" role="presentation">
                    <a href="javascript:void(0);" data-tag="ep-list-profile" class="ep-list-group-item-ligh ep-tab-link">
                        <?php esc_html_e( 'My Account', 'eventprime-event-calendar-management');?>
                    </a>
                </li>
            </ul>
        </div>
        
        <div class="ep-box-col-9">
            <!-- Event Loader -->
            <?php do_action( 'ep_add_loader_section' );?>
            <!-- Event Loader End -->
            <div class="ep-tab-container" id="ep-tab-container">
                <?php ep_get_template_part( 'users/profile/upcoming-bookings', null, $args );?>

                <?php ep_get_template_part( 'users/profile/my-bookings', null, $args );?>
                
                <?php ep_get_template_part( 'users/profile/my-events', null, $args );?>
                
                <?php ep_get_template_part( 'users/profile/my-lists', null, $args );?>
                
                <?php ep_get_template_part( 'users/profile/user-transactions', null, $args );?>
                
                <?php ep_get_template_part( 'users/profile/user-profile-data', null, $args );?>
                
                <?php echo do_action('ep_profile_tabs_list_content', $args->current_user);?>
            </div>
        </div>
    </div>
</div>