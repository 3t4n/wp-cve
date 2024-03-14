<?php 
$log_in_text = ep_global_settings_button_title('Log-In');
?>
<div class="ep-setting-tab-content">
    <h2><?php esc_html_e( 'Frontend Event Submission Form Settings', 'eventprime-event-calendar-management' );?></h2>
    <input type="hidden" name="em_setting_type" value="front_event_submission_settings">
    <p class="ep-global-back-btn">
        <?php $back_url = remove_query_arg( 'section' ) ;?>
        <a href="<?php echo esc_url( $back_url );?>">
            <?php esc_html_e( 'Back', 'eventprime-event-calendar-management' );?>
        </a>
    </p>
</div>
<table class="form-table">
    <tbody>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="ues_confirm_message">
                    <?php esc_html_e( 'Confirmation Message', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <input name="ues_confirm_message" id="ues_confirm_message" class="regular-text" type="text" value="<?php echo esc_attr( $options['global']->ues_confirm_message ) ;?>">
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'Users will see this message after the form has been successfully submitted.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="allow_submission_by_anonymous_user">
                    <?php esc_html_e( 'Guest Submissions', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <label class="ep-toggle-btn">
                    <input name="allow_submission_by_anonymous_user" class="regular-text" id="allow_submission_by_anonymous_user" type="checkbox" <?php if( $options['global']->allow_submission_by_anonymous_user == 1 ) { echo 'checked'; }?> >
                    <span class="ep-toogle-slider round"></span>
                </label>
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'If enabled, users will be able to submit events without logging-in.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
        <tr valign="top" id="ep-use-login-message" <?php if( $options['global']->allow_submission_by_anonymous_user == 1 ) { echo 'style="display:none;"'; }?>>
            <th scope="row" class="titledesc">
                <label for="ues_login_message">
                    <?php echo esc_html( $log_in_text ) . ' '.esc_html__( 'Error', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <input name="ues_login_message" id="ues_login_message" class="regular-text" type="text" value="<?php echo esc_attr( $options['global']->ues_login_message ) ;?>">
            <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'Users will see this message if they open the form without logging-in.', 'eventprime-event-calendar-management' );?></div></td>
        </tr>
        <tr valign="top" >
            <th scope="row" class="titledesc">
                <label for="ues_default_status">
                    <?php esc_html_e( 'Default State', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <select name="ues_default_status" id="ues_default_status" class="ep-form-control">
                    <option value=""><?php esc_html_e( 'Select State', 'eventprime-event-calendar-management' );?></option>
                    <?php foreach( $options['status_list'] as $key => $status ){?>
                        <option value="<?php echo esc_attr( $key );?>" <?php if( $options['global']->ues_default_status == $key ) { echo 'selected="selected"'; } ?>>
                            <?php echo $status;?>
                        </option><?php
                    }?>
                </select>
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'Choose default state of the user events when they are first submitted. Select \'Draft\' if you wish to review the events before making them live. \'Active\' will make them live as soon as they are submitted.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
        <tr valign="top" >
            <th scope="row" class="titledesc">
                <label for="frontend_submission_roles">
                    <?php esc_html_e( 'Restrict by Roles', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <select name="frontend_submission_roles[]" id="frontend_submission_roles" multiple="multiple" class="ep-form-control">
                    <?php 
                    $global_fes_user_role_sections = (array)$options['global']->frontend_submission_roles;
                    foreach( ep_get_all_user_roles() as $key => $role ){ echo $key;?>
                        <option value="<?php echo esc_attr( $key );?>" <?php if( in_array( $key, $global_fes_user_role_sections, true ) ) { echo 'selected="selected"'; } ?>>
                            <?php echo $role;?>
                        </option><?php
                    }?>
                </select>
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'If empty, users from any role will be able to submit the form. Otherwise, only users with selected roles will be allowed to submit.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="ues_restricted_submission_message">
                    <?php esc_html_e( 'Restriction Error', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <input name="ues_restricted_submission_message" id="ues_restricted_submission_message" type="text" class="regular-text" value="<?php echo esc_attr( $options['global']->ues_restricted_submission_message ) ;?>">
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'Users will see this message instead of the the form, when their role is excluded from submission.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="frontend_submission_sections">
                    <?php esc_html_e( 'Form Sections', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <?php 
                $global_fes_sections = (array)$options['global']->frontend_submission_sections;
                foreach( $options['fes_sections'] as $key => $section ){ ?>
                    <div for="frontend_submission_sections_<?php echo $key;?>" class="ep-mb-2">
                        <input type="checkbox" name="frontend_submission_sections[<?php echo $key;?>]" id="frontend_submission_sections_<?php echo $key;?>" <?php if( isset( $global_fes_sections[$key] ) && ! empty( $global_fes_sections[$key] ) ) { echo 'checked'; } ?>>
                        <?php echo $section;?>
                    </div><?php 
                }?>
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'Choose which sections or features will be included in the form. These sections allow setting additional event properties.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="frontend_submission_required">
                    <?php esc_html_e( 'Required Fields', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <?php 
                $global_fes_require = (array)$options['global']->frontend_submission_required;
                foreach( $options['fes_required'] as $key => $section ){ ?>
                    <label for="frontend_submission_required_<?php echo $key;?>">
                        <input type="checkbox" name="frontend_submission_required[<?php echo $key;?>]" id="frontend_submission_required_<?php echo $key;?>" <?php if( isset( $global_fes_require[$key] ) && ! empty( $global_fes_require[$key] ) ) { echo 'checked'; } ?>>
                        <?php echo $section;?>
                    </label><?php
                }?>
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'Choose which fields will be marked mandatory in the form.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="fes_allow_media_library">
                    <?php esc_html_e( 'Allow Media Library', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <label class="ep-toggle-btn">
                    <input name="fes_allow_media_library" class="regular-text" id="fes_allow_media_library" type="checkbox" <?php if( $options['global']->fes_allow_media_library == 1 ) { echo 'checked'; }?> >
                    <span class="ep-toogle-slider round"></span>
                </label>
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'Enable it to allow users access to the WordPress Media Library to select event images. If this is disabled, users can only upload new images for events.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="fes_show_add_event_in_profile">
                    <?php esc_html_e( 'Show Add Event Button', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <label class="ep-toggle-btn">
                    <input name="fes_show_add_event_in_profile" class="regular-text" id="fes_show_add_event_in_profile" type="checkbox" <?php if( $options['global']->fes_show_add_event_in_profile == 1 ) { echo 'checked'; }?> >
                    <span class="ep-toogle-slider round"></span>
                </label>
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'Enable to display an \'Add Event\' button inside the user area allowing users to create events.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="fes_allow_user_to_delete_event">
                    <?php esc_html_e( 'Allow Users to Delete Events', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <label class="ep-toggle-btn">
                    <input name="fes_allow_user_to_delete_event" class="regular-text" id="fes_allow_user_to_delete_event" type="checkbox" <?php if( $options['global']->fes_allow_user_to_delete_event == 1 ) { echo 'checked'; }?> >
                    <span class="ep-toogle-slider round"></span>
                </label>
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'Enable to allow users to delete events they have created from frontend.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
    </tbody>
</table>