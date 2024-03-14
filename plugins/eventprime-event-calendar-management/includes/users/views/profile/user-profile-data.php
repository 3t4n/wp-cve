<div class="ep-tab-content ep-item-hide" id="ep-list-profile" role="tabpanel" aria-labelledby="ep-list-profile">
    <div class="ep-box-row ep-text-small ep-mb-4">
        <div class="ep-box-col-3 ep-m-0 ep-p-0">
            <?php esc_html_e( 'Name', 'eventprime-event-calendar-management' ); ?>:
        </div>
        <div class="ep-box-col-3 ep-m-0 ep-p-0">
            <?php echo esc_html( $args->current_user->display_name ); ?>
        </div>
    </div>

    <div class="ep-box-row ep-text-small ep-mb-4">
        <div class="ep-box-col-3 ep-m-0 ep-p-0">
            <?php esc_html_e( 'Email', 'eventprime-event-calendar-management' ); ?>:
        </div>
        <div class="ep-box-col-3 ep-m-0 ep-p-0">
            <?php echo esc_html( $args->current_user->user_email ); ?>
        </div>
    </div>

    <?php if( isset( $args->current_user->phone ) && ! empty( $args->current_user->phone ) ) { ?>
        <div class="ep-box-row ep-text-small ep-mb-4">
            <div class="ep-box-col-3 ep-m-0 ep-p-0">
                <?php esc_html_e( 'Phone', 'eventprime-event-calendar-management' ); ?>:
            </div>
            <div class="ep-box-col-3 ep-m-0 ep-p-0">
                <?php echo esc_html( $args->current_user->phone ); ?>
            </div>
        </div>
    <?php } ?>
    <div class="ep-box-row ep-text-small ep-mb-4">
        <div class="ep-box-col-3 ep-m-0 ep-p-0">
            <?php esc_html_e( 'Registered On', 'eventprime-event-calendar-management' ); ?>:
        </div>
        <div class="ep-box-col-3 ep-m-0 ep-p-0">
            <?php echo esc_html( $args->current_user->user_registered ); ?>
        </div>
    </div>

    <div class="ep-box-row ep-text-small ep-mb-4">
        <div class="ep-box-col-3 ep-m-0 ep-p-0">
            <?php esc_html_e( 'Timezone', 'eventprime-event-calendar-management' ); ?>:
        </div>
        <div class="ep-box-col-6 ep-m-0 ep-p-0">
            <span id="ep_user_profile_timezone_data">
                <?php 
                $current_timezone = ep_get_current_user_timezone();
                if( empty( $current_timezone ) ) {
                    echo ep_get_site_timezone();
                } else{
                    echo esc_html( $current_timezone );
                }?>
            </span>
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

    <?php if ( current_user_can( 'edit_user', $args->current_user->ID ) ) { ?>
        <div class="ep-box-row ep-text-small ep-mb-4 ep-user-profile-edit-profile">
            <a href="<?php echo admin_url().'profile.php'; ?>">
                <?php esc_html_e( 'Edit Profile', 'eventprime-event-calendar-management' ); ?>
            </a>
        </div><?php 
    } ?>
</div>