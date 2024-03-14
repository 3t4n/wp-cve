<div class="ep-setting-tab-content">
    <h2><?php esc_html_e( 'Frontend Event Submission', 'eventprime-event-calendar-management' );?></h2>
    <input type="hidden" name="em_setting_type" value="front_event_submission_settings">
</div>
<table class="form-table">
    <tbody>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="ues_confirm_message">
                    <?php esc_html_e( 'Confirmation Message', 'eventprime-event-calendar-management' );?>
                    <span class="ep-help-tip" tooltip="<?php echo esc_attr( 'This message will display to the user after event has been submitted.' );?>"></span>
                </label>
            </th>
            <td class="forminp forminp-text">
                <input name="ues_confirm_message" id="ues_confirm_message" class="regular-text" type="text" value="<?php echo esc_attr( $options['global']->ues_confirm_message ) ;?>">
            </td>
        </tr>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="allow_submission_by_anonymous_user">
                    <?php esc_html_e( 'Allow Submission By Anonymous Users', 'eventprime-event-calendar-management' );?>
                    <span class="ep-help-tip" tooltip="<?php echo esc_attr( 'This will allow frontend user to submit event without login.' );?>"></span>
                </label>
            </th>
            <td class="forminp forminp-text">
                <input name="allow_submission_by_anonymous_user" class="regular-text" id="allow_submission_by_anonymous_user" type="checkbox" <?php if( $options['global']->allow_submission_by_anonymous_user == 1 ) { echo 'selected="selected"'; }?> >
            </td>
        </tr>
        <tr valign="top" id="ep-use-login-message" <?php if( $options['global']->allow_submission_by_anonymous_user == 1 ) { echo 'style="display:none;"'; }?>>
            <th scope="row" class="titledesc">
                <label for="ues_login_message">
                    <?php esc_html_e( 'Login Message', 'eventprime-event-calendar-management' );?>
                    <span class="ep-help-tip" tooltip="<?php echo esc_attr( 'This message will display to the user if he/she is not logged in to submit event.' );?>"></span>
                </label>
            </th>
            <td class="forminp forminp-text">
                <input name="ues_login_message" id="ues_login_message" class="regular-text" type="text" value="<?php echo esc_attr( $options['global']->ues_login_message ) ;?>">
            </td>
        </tr>
        <tr valign="top" >
            <th scope="row" class="titledesc">
                <label for="ues_default_status">
                    <?php esc_html_e( 'Status', 'eventprime-event-calendar-management' );?>
                    <span class="ep-help-tip" tooltip="<?php echo esc_attr( 'This will be the status of the event once it is submitted from the frontend. Setting this to "Active" will publish the event on frontend as soon as it is submitted.' );?>"></span>
                </label>
            </th>
            <td class="forminp forminp-text">
                <select name="ues_default_status" id="ues_default_status" class="ep-form-control">
                    <option value=""><?php esc_html_e( 'Select Status', 'eventprime-event-calendar-management' );?></option>
                    <?php foreach( $options['status_list'] as $key => $status ){?>
                        <option value="<?php echo esc_attr( $key );?>" <?php if( $options['global']->ues_default_status == $key ) { echo 'selected="selected"'; } ?>>
                            <?php echo $status;?>
                        </option><?php
                    }?>
                </select>
            </td>
        </tr>
        <tr valign="top" >
            <th scope="row" class="titledesc">
                <label for="frontend_submission_roles">
                    <?php esc_html_e( 'Restricted Submission By User Roles', 'eventprime-event-calendar-management' );?>
                    <span class="ep-help-tip" tooltip="<?php echo esc_attr( 'Only selected users will be allow to do submission of frontend event.' );?>"></span>
                </label>
            </th>
            <td class="forminp forminp-text">
                <select name="frontend_submission_roles" id="frontend_submission_roles" multiple="multiple" class="ep-form-control">
                    <?php foreach( ep_get_all_user_roles() as $key => $role ){?>
                        <option value="<?php echo esc_attr( $key );?>" <?php if( $options['global']->frontend_submission_roles == $key ) { echo 'selected="selected"'; } ?>>
                            <?php echo $role;?>
                        </option><?php
                    }?>
                </select>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="ues_restricted_submission_message">
                    <?php esc_html_e( 'Restricted Message', 'eventprime-event-calendar-management' );?>
                    <span class="ep-help-tip" tooltip="<?php echo esc_attr( 'This message will display if user is restricted.' );?>"></span>
                </label>
            </th>
            <td class="forminp forminp-text">
                <input name="ues_restricted_submission_message" id="ues_restricted_submission_message" type="text" class="regular-text" value="<?php echo esc_attr( $options['global']->ues_restricted_submission_message ) ;?>">
            </td>
        </tr>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="frontend_submission_sections">
                    <?php esc_html_e( 'Frontend Event Submission Sections', 'eventprime-event-calendar-management' );?>
                    <span class="ep-help-tip" tooltip="<?php echo esc_attr( 'Select the fields which you wish to appear on frontend event submission form.' );?>"></span>
                </label>
            </th>
            <td class="forminp forminp-text">
                <?php foreach( $options['fes_sections'] as $key => $section ){ ?>
                    <label for="frontend_submission_sections_<?php echo $key;?>">
                        <input type="checkbox" name="frontend_submission_sections[<?php echo $key;?>" id="frontend_submission_sections_<?php echo $key;?>">
                        <?php echo $section;?>
                    </label><?php
                }?>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="frontend_submission_required">
                    <?php esc_html_e( 'Required Fields', 'eventprime-event-calendar-management' );?>
                    <span class="ep-help-tip" tooltip="<?php echo esc_attr( 'Select the fields which you wish to make required on frontend even submission form.' );?>"></span>
                </label>
            </th>
            <td class="forminp forminp-text">
                <?php foreach( $options['fes_required'] as $key => $section ){ ?>
                    <label for="frontend_submission_required_<?php echo $key;?>">
                        <input type="checkbox" name="frontend_submission_required[<?php echo $key;?>" id="frontend_submission_required_<?php echo $key;?>">
                        <?php echo $section;?>
                    </label><?php
                }?>
            </td>
        </tr>
    </tbody>
</table>