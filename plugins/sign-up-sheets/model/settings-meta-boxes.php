<?php
/**
 * Settings Meta Boxes Model
 */

namespace FDSUS\Model;

use FDSUS\Id as Id;
use FDSUS\Model\Settings\SheetOrder;
use FDSUS\Model\SheetCollection as SheetCollectionModel;
use FDSUS\Model\Settings as Settings;
use WP_Roles;
use Exception;

class SettingsMetaBoxes
{
    /** @var Data */
    private $data;

    public function __construct()
    {
        $this->data = new Data();
    }

    /**
     * Get options array
     *
     * $options = array(
     *     'id'      => 'sheet',
     *     'title'   => esc_html__('Sign-up Sheet', 'fdsus'),
     *     'order'   => 10,
     *     'options' => array(
     *         'label'    => 'Display Label',
     *         'name'     => 'field_name',
     *         'type'     => 'text', // Field type
     *         'note'     => 'Optional note',
     *         'options'  => array(), // optional array for select and multi-checbox/radio type fields
     *         'order'    => 10, // sort order
     *         'pro'      => false, // pro feature
     *         'class'    => 'some-class', // adds class to surrounding <tr> element
     *         'disabled' => false, // mark input field as disabled
     *    )
     * );
     *
     * @return array
     * @throws Exception
     */
    public function getData()
    {
        /** @global WP_Roles $wp_roles */
        global $wp_roles;
        $roles = $wp_roles->get_names();
        unset($roles['administrator']);
        unset($roles['signup_sheet_manager']);

        // Sheets Listing
        $sheetSelection = array('' => esc_html__('All', 'fdsus'));
        if (Id::isPro()) {
            $sheetCollection = new SheetCollectionModel();
            $sheets = $sheetCollection->get();
            foreach ($sheets as $sheet) {
                $sheetSelection[$sheet->ID] = '#' . ($sheet->ID) . ': ' . esc_html($sheet->post_title)
                    . (!empty($sheet->dlssus_date)
                        ? ' (' . date(get_option('date_format'), strtotime($sheet->dlssus_date)) . ')'
                        : null);
            }
        }

        // Custom field types
        $fieldTypesTask = array(
            'text'       => 'text',
            'textarea'   => 'textarea',
            'checkboxes' => 'checkboxes',
            'radio'      => 'radio',
            'dropdown'   => 'dropdown'
        );
        $fieldTypesSignup = $fieldTypesTask;
        $fieldTypesSignup['date'] = 'date';

        $sheetOrder = new SheetOrder();

        $options['sheet'] = array(
            'id'      => 'sheet',
            'title'   => esc_html__('Sign-up Sheet', 'fdsus'),
            'order'   => 10,
            'options' => array(
                array(
                    'label'   => esc_html__('Sheet order on Front-end', 'fdsus'),
                    'name'    => 'dls_sus_sheet_order',
                    'type'    => 'dropdown',
                    'options' => $sheetOrder->options(),
                    'order'   => 10
                ),
                array(
                    'label' => esc_html__('Show All Sign-up Data Fields on Front-end', 'fdsus'),
                    'name'  => 'dls_sus_display_all',
                    'type'  => 'checkbox',
                    'note'  => esc_html__('WARNING: Sign-up sheet table will appear much like the table when sign-ups are viewed via the admin. This option will potentially display personal user information on the frontend like email address and phone.  This option is best used if you are using the [sign_up_sheet] short code within a password protected area. (This also overrides the "Front-end Display Names" option and displays all as full names.)', 'fdsus'),
                    'order' => 20
                ),
                array(
                    'label'   => esc_html__('Front-end Display Names', 'fdsus'),
                    'name'    => 'dls_sus_display_name',
                    'type'    => 'dropdown',
                    'note'    => esc_html__('How the user\'s name should be displayed on the front-end after they sign-up', 'fdsus'),
                    'options' => array(
                        'default'   => '"John S." - first name plus first letter of last name',
                        'full'      => '"John Smith" - full name',
                        'anonymous' => '"' . esc_html__('Filled', 'fdsus') . '" - ' . esc_html__('anonymous', 'fdsus'),
                    ),
                    'th-rowspan' => 2,
                    'order'   => 30,
                    'pro'     => true
                ),
                array(
                    'label'   => false,
                    'name'    => 'fdsus_display_name_username_override',
                    'type'    => 'dropdown',
                    'note'    => '<span id="fdsus_display_name_username_override-label">' . esc_html__(
                        'For logged in users, override the Front-end Display Name with their WP username on their sign-ups.', 'fdsus'
                    ) . '</span>',
                    'options' => array(
                        ''             => esc_html__('Not Enabled', 'fdsus'),
                        'display_name' => esc_html__('Public Display Name', 'fdsus'),
                        'nickname'     => esc_html__('Nickname', 'fdsus'),
                        'user_login'   => esc_html__('Username', 'fdsus'),
                    ),
                    'aria-labelledby' => 'fdsus_display_name_username_override-label',
                    'order'   => 32,
                    'pro'     => true
                ),
                array(
                    'label' => esc_html__('Compact Sign-up Mode', 'fdsus'),
                    'name'  => 'dls_sus_compact_signups',
                    'type'  => 'dropdown',
                    'note'  => esc_html__('Show sign-up spots on one line with just # of open spots and a link to sign-up if open. Semi-Compact will also include the names of those who already signed up (assuming "Front-end Display Names" is not set to "anonymous"', 'fdsus'),
                    'options' => array(
                        'false' => esc_html__('Disabled', 'fdsus'),
                        'true'  => esc_html__('Enabled', 'fdsus'),
                        'semi'  => esc_html__('Semi-Compact', 'fdsus'),
                    ),
                    'order' => 40,
                    'pro'   => true
                ),
                array(
                    'label' => esc_html__('Enable task sign-up limit', 'fdsus'),
                    'name'  => 'dls_sus_task_signup_limit',
                    'type'  => 'checkbox',
                    'note'  => esc_html__('Prevent users from being able to sign-up for a task more than once.  This is checked by email address.', 'fdsus'),
                    'order' => 50,
                    'pro'   => true
                ),
                array(
                    'label' => esc_html__('Enable contiguous task sign-up limit', 'fdsus'),
                    'name'  => 'dls_sus_contiguous_task_signup_limit',
                    'type'  => 'checkbox',
                    'note'  => esc_html__('Prevent users from being able to sign-up for a task directly before or after a task for which they have already signed up.  This is checked by email address.', 'fdsus'),
                    'order' => 60,
                    'pro'   => true
                ),
                array(
                    'label' => esc_html__('Enable Task Checkboxes', 'fdsus'),
                    'name'  => 'dls_sus_enable_task_checkbox',
                    'type'  => 'checkbox',
                    'note'  => esc_html__('Allow check boxes on signup line items that allow user to sign up for multiple tasks.', 'fdsus'),
                    'order' => 70,
                    'pro'   => true
                ),
                array(
                    'label' => esc_html__('Enable Spot Lock', 'fdsus'),
                    'name'  => 'dls_sus_spot_lock',
                    'type'  => 'checkbox',
                    'note'  => esc_html__('Spot will be locked and held for current user for 3 minutes when they access the sign-up form page.  Spot Lock is available when signing up for a single task at a time.', 'fdsus'),
                    'order' => 80,
                    'pro'   => true
                ),
                array(
                    'label' => esc_html__('Hide self-removal from Sign-up Sheet', 'fdsus'),
                    'name'  => 'dls_sus_hide_removal',
                    'type'  => 'checkbox',
                    'note' => esc_html__('Hides the "Remove" link from the sign-up form if users were logged in when they signed up. This is always hidden if "Front-end Display Names" is set to "anonymous".', 'fdsus'),
                    'order' => 85,
                    'pro'   => true
                ),
                array(
                    'label' => esc_html__('Number of days before sheet/task date to allow users to edit their own sign-ups', 'fdsus'),
                    'name'  => 'fdsus_user_editable_signups',
                    'type'  => 'number',
                    'note'  => esc_html__('Leave blank to disable the user edit feature. Number entered will calculate based on the task date, if set, otherwise it will use the sheet date.  If no sheet and task date is set, editing will be allowed indefinitely.  Use negative numbers to allow editing after the date has passed', 'fdsus'),
                    'order' => 88,
                    'pro'   => true
                ),
                array(
                    'label' => esc_html__('Show Filled Spots in Admin Edit Sheet', 'fdsus'),
                    'name'  => 'dls_sus_show_filled_spots_admin_edit',
                    'type'  => 'checkbox',
                    'note'  => esc_html__('Show names and count of filled spots in the admin Edit Sheet screen.', 'fdsus'),
                    'order' => 90,
                    'pro'   => true
                ),
                array(
                    'label' => esc_html__('Allow Auto-Clearing Sign-ups Per Sheet', 'fdsus'),
                    'name'  => 'fdsus_allow_autoclear_signups',
                    'type'  => 'checkbox',
                    'note'  =>
                        /* translators: %s is replaced with the timestamp of the next cron scheduled */
                        sprintf(esc_html__('Next scheduled check: %s', 'fdsus'),
                            Settings::getNextScheduledCronCheck('fdsus_autoclear'))
                        . '<ul>'
                            . '<li>'
                                . esc_html__('Enabling this activates the optional setting on each Sheet under "Additional Settings" which provides the ability auto-clear all sign-ups for that sheet on a schedule.', 'fdsus')
                            . '</li><li>'
                                . esc_html__('Your site will check if there are sheets that need to be cleared that need to be sent using the', 'fdsus')
                                . ' <a href="https://developer.wordpress.org/plugins/cron/">' . esc_html__('WordPress Cron', 'fdsus') . '</a>'
                            . '</li><li>'
                                . esc_html__('If you just enabled/disabled this, you may need to refresh this page to see the updated "Next scheduled check"', 'fdsus')
                            . '</li>'
                        . '</ul>',
                    'order' => 100,
                    'pro'   => true
                ),
                array(
                    'label'   => esc_html__('Custom Task Fields', 'fdsus'),
                    'name'    => 'dls_sus_custom_task_fields',
                    'type'    => 'repeater',
                    'note'    => 'To add more fields, save this page and a new blank row will appear.<br />* Options are for checkbox, radio and dropdown fields.  Put multiple values on new lines.<br /><br /><strong>NOTE: Custom Task Fields are for display only on the frontend. To add custom fields that your users fill out, use the Custom Sign-up Fields in the "Sign-up Form" section below.</strong>', // TODO translate
                    'options' => array(
                        array('label' => 'Name', 'name' => 'name', 'type' => 'text'),
                        array('label' => 'Slug', 'name' => 'slug', 'type' => 'text'),
                        array('label' => 'Type', 'name' => 'type', 'type' => 'dropdown', 'options' => $fieldTypesTask),
                        array('label' => 'Options', 'name' => 'options', 'type' => 'textarea', 'note' => '<span aria-describedby="custom-task-fields-options-note">*</span>', 'aria-describedby' => 'custom-task-fields-options-note'),
                        array('label' => 'Sheets', 'name' => 'sheet_ids', 'type' => 'multiselect', 'options' => $sheetSelection),
                    ),
                    'order'   => 110,
                    'pro'     => true
                )
            ),
        );

        $options['form'] = array(
            'id'      => 'form',
            'title'   => esc_html__('Sign-up Form', 'fdsus'),
            'order'   => 20,
            'options' => array(
                array(
                    'label' => esc_html__('Show "Remember Me" checkbox', 'fdsus'),
                    'name'  => 'dls_sus_remember',
                    'type'  => 'checkbox',
                    'order' => 5,
                    'pro'   => true
                ),
                array(
                    'label' => esc_html__('Set Phone as Optional', 'fdsus'),
                    'name'  => 'dls_sus_optional_phone',
                    'type'  => 'checkbox',
                    'order' => 10
                ),
                array(
                    'label' => esc_html__('Set Address as Optional', 'fdsus'),
                    'name' => 'dls_sus_optional_address',
                    'type' => 'checkbox',
                    'order' => 20
                ),
                array(
                    'label' => esc_html__('Hide Email Field', 'fdsus'),
                    'name'  => 'dls_sus_hide_email',
                    'type'  => 'checkbox',
                    'order' => 30
                ),
                array(
                    'label' => esc_html__('Hide Phone Field', 'fdsus'),
                    'name'  => 'dls_sus_hide_phone',
                    'type'  => 'checkbox',
                    'order' => 30
                ),
                array(
                    'label' => esc_html__('Hide Address Fields', 'fdsus'),
                    'name'  => 'dls_sus_hide_address',
                    'type'  => 'checkbox',
                    'order' => 40
                ),
                array(
                    'label' => esc_html__('Disable User Auto-populate', 'fdsus'),
                    'name'  => 'dls_sus_disable_user_autopopulate',
                    'type'  => 'checkbox',
                    'note'  => esc_html__('By default, for users that are logged in, their name and email auto-populates on sign-up form when available. This option disables that behavior.', 'fdsus'),
                    'order' => 45
                ),
                array(
                    'label' => esc_html__('Disable Mail Check Validation', 'fdsus'),
                    'name'  => 'dls_sus_deactivate_email_validation',
                    'type'  => 'checkbox',
                    'note'  => esc_html__('Validation includes a JS check for standard email formatting, possible incorrect domains with suggestions as well as an MX record check on the domain to confirm it is setup to receive emails', 'fdsus'),
                    'order' => 50
                ),
                array(
                    'label' => esc_html__('Sign-up Success Message Receipt', 'fdsus'),
                    'name'  => 'dls_sus_signup_receipt',
                    'type'  => 'checkbox',
                    'note'  => esc_html__('Displays a receipt below the sign-up success message which includes a copy of all the task details and all fields they entered in the sign-up form. Default: `unchecked`', 'fdsus'),
                    'order' => 60,
                    'pro'   => true
                ),
                array(
                    'label'   => esc_html__('Custom Sign-up Fields', 'fdsus'),
                    'name'    => 'dls_sus_custom_fields',
                    'type'    => 'repeater',
                    'note'    => 'To add more fields, save this page and a new blank row will appear.<br /><span id="custom-signup-fields-options-note">* Options are for checkbox, radio and dropdown fields.  Put multiple values on new lines.</a>', // TODO translate
                    'options' => array(
                        array('label' => 'Name', 'name' => 'name', 'type' => 'text'),
                        array('label' => 'Slug', 'name' => 'slug', 'type' => 'text'),
                        array('label' => 'Type', 'name' => 'type', 'type' => 'dropdown', 'options' => $fieldTypesSignup),
                        array('label' => 'Options', 'name' => 'options', 'type' => 'textarea', 'note' => '<span aria-describedby="custom-signup-fields-options-note">*</span>', 'aria-describedby' => 'custom-signup-fields-options-note'),
                        array('label' => 'Sheets', 'name' => 'sheet_ids', 'type' => 'multiselect', 'options' => $sheetSelection),
                        array('label' => 'Required', 'name' => 'required', 'type' => 'checkbox'),
                        array('label' => 'Results on Frontend', 'name' => 'frontend_results', 'type' => 'checkbox'),
                    ),
                    'order'   => 70,
                    'pro'     => true
                ),
            )
        );

        $options['spam'] = array(
            'id'      => 'spam',
            'title'   => esc_html__('Captcha and Spam Prevention', 'fdsus'),
            'order'   => 30,
            'options' => array(
                array(
                    'label' => esc_html__('Disable honeypot', 'fdsus'),
                    'name'  => 'dls_sus_disable_honeypot',
                    'type'  => 'checkbox',
                    'note'  => esc_html__('A honeypot is a less-invasive technique to reduce SPAM submission using a hidden field on the sign-up form.  It can be used in place of or alongside the captcha.', 'fdsus')
                ),
                array(
                    'label' => esc_html__('Disable all Captcha', 'fdsus'),
                    'name'  => 'dls_sus_disable_captcha',
                    'type' => 'checkbox', esc_html__('Will disable all captcha even if you have reCAPTCHA enabled below', 'fdsus')),
                array('Use reCAPTCHA', 'dls_sus_recaptcha', 'checkbox', esc_html__('Will replace the default simple captcha validation', 'fdsus')),
                array('reCAPTCHA Public Key', 'dls_sus_recaptcha_public_key', 'text', esc_html__('From your account at https://www.google.com/recaptcha/', 'fdsus')),
                array('reCAPTCHA Private Key', 'dls_sus_recaptcha_private_key', 'text', esc_html__('From your account at https://www.google.com/recaptcha/', 'fdsus')),
                array('reCAPTCHA Version', 'dls_sus_recaptcha_version', 'dropdown', '', array('v3' => 'v3', 'v2-checkbox' => 'v2 Checkbox', 'v2-invisible' => 'v2 Invisible')),
            )
        );

        $options['confirmation_email'] = array(
            'id'      => 'confirmation_email',
            'title'   => esc_html__('Confirmation E-mail', 'fdsus'),
            'order'   => 40,
            'options' => array(
                array(
                    'label' => esc_html__('Enable', 'fdsus'),
                    'name'  => 'fdsus_enable_confirmation_email',
                    'type'  => 'checkbox',
                    'order' => 10,
                    'pro'   => true,
                    'value' => Settings::isConfirmationEmailEnabled() ? 'true' : ''
                ),
                array(
                    'label' => esc_html__('Subject', 'fdsus'),
                    'name'  => 'dls_sus_email_subject',
                    'type'  => 'text',
                    /* translators: %s is replaced with the default subject */
                    'note'  => esc_html(sprintf(__('If blank, defaults to... "%s"', 'fdsus'), Settings::$defaultMailSubjects['signup'])),
                    'order' => 20
                ),
                array(
                    'label' => esc_html__('From E-mail Address', 'fdsus'),
                    'name'  => 'dls_sus_email_from',
                    'type'  => 'text',
                    'note'  => esc_html__('If blank, defaults to WordPress email on file under Settings > General', 'fdsus'),
                    'order' => 30
                ),
                array(
                    'label'    => esc_html__('BCC', 'fdsus'),
                    'name'     => 'dls_sus_email_bcc',
                    'type'     => 'text',
                    'note'     => esc_html__('Comma separate for multiple email addresses', 'fdsus'),
                    'order'    => 40,
                    'pro'      => true
                ),
                array(
                    'label'    => esc_html__('Message', 'fdsus'),
                    'name'     => 'dls_sus_email_message',
                    'type'     => 'textarea',
                    'note'     => sprintf(
                        '%s<br>
                            <code>{signup_details}</code> - %s<br>
                            <code>{signup_firstname}</code> - %s<br>
                            <code>{signup_lastname}</code> - %s<br>
                            <code>{signup_email}</code> - %s<br>
                            <code>{site_name}</code> - %s<br>
                            <code>{site_url}</code> - %s<br>
                            <code>{removal_link}</code> - %s',
                        esc_html__('Variables that can be used in template...', 'fdsus'),
                        esc_html__('Multi-line list of sign-up details such as date, sheet title, task title', 'fdsus'),
                        esc_html__('First name of user that signed up', 'fdsus'),
                        esc_html__('Last name of user that signed up', 'fdsus'),
                        esc_html__('Email of user that signed up', 'fdsus'),
                        esc_html__('Name of site as defined in Settings > General > Site Title', 'fdsus'),
                        esc_html__('URL of site', 'fdsus'),
                        esc_html__('Link to remove sign-up', 'fdsus')
                    ),
                    'order'    => 50,
                    'pro'      => true
                )
            )
        );

        $options['removal_confirmation_email'] = array(
            'id'      => 'removal_confirmation_email',
            'title'   => esc_html__('Removal Confirmation E-mail', 'fdsus') . (!Id::isPro() ? ' <span class="dls-sus-pro" title="Pro Feature">Pro</span>' : ''),
            'order'   => 50,
            'options' => array(
                array(
                    'label' => esc_html__('Enable', 'fdsus'),
                    'name'  => 'fdsus_enable_removal_confirmation_email',
                    'type'  => 'checkbox',
                    'order' => 10,
                    'pro'   => true,
                    'value' => Settings::isRemovalConfirmationEmailEnabled() ? 'true' : ''
                ),
                array(
                    'label' => esc_html__('Message', 'fdsus'),
                    'name'  => 'dls_sus_removed_email_message',
                    'type'  => 'textarea',
                    'note'  => sprintf(
                        '%s<br>
                            <code>{signup_details}</code> - %s<br>
                            <code>{signup_firstname}</code> - %s<br>
                            <code>{signup_lastname}</code> - %s<br>
                            <code>{signup_email}</code> - %s<br>
                            <code>{site_name}</code> - %s<br>
                            <code>{site_url}</code> - %s',
                        esc_html__('Variables that can be used in template...', 'fdsus'),
                        esc_html__('Multi-line list of sign-up details such as date, sheet title, task title', 'fdsus'),
                        esc_html__('First name of user that signed up', 'fdsus'),
                        esc_html__('Last name of user that signed up', 'fdsus'),
                        esc_html__('Email of user that signed up', 'fdsus'),
                        esc_html__('Name of site as defined in Settings > General > Site Title', 'fdsus'),
                        esc_html__('URL of site', 'fdsus')
                    ),
                    'order' => 20,
                    'pro' => true
                ),
            )
        );

        $options['reminder_email'] = array(
            'id'      => 'reminder_email',
            'title'   => esc_html__('Reminder E-mail', 'fdsus') . (!Id::isPro() ? ' <span class="dls-sus-pro" title="' . esc_html__('Pro Feature', 'fdsus') . '">' . esc_html__('Pro', 'fdsus') . '</span>' : ''),
            'order'   => 60,
            'options' => array(
                array(
                    'label' => esc_html__('Enable Reminders', 'fdsus'),
                    'name'  => 'dls_sus_reminder_email',
                    'type'  => 'checkbox',
                    'note'  =>
                        /* translators: %s is replaced with the timestamp of the next cron scheduled */
                        sprintf(esc_html__('Next scheduled check: %s', 'fdsus'),
                            Settings::getNextScheduledCronCheck('dls_sus_send_reminders'))
                        . '<ul>'
                            . '<li>'
                                . esc_html__('Your site will check hourly to see if there are reminders that need to be sent using the', 'fdsus')
                                . ' <a href="https://developer.wordpress.org/plugins/cron/">' . esc_html__('WordPress Cron', 'fdsus') . '</a>'
                            . '</li><li>'
                                . esc_html__('If you just enabled/disabled this, you may need to refresh this page to see the updated "Next scheduled check"', 'fdsus')
                            . '</li>'
                        . '</ul>',
                    'pro' => true
                ),
                array(
                    'label' => esc_html__('Reminder Schedule', 'fdsus'),
                    'name'  => 'dls_sus_reminder_email_days_before',
                    'type'  => 'text',
                    'note'  => esc_html__('Number of days before the date on the sign-up sheet that the email should be sent.  Use whole numbers, for example, to remind one day before use...', 'fdsus') . ' <code>1</code> ' . esc_html__('This field is required.', 'fdsus'),
                    'pro' => true
                ),
                array(
                    'label' => esc_html__('Subject', 'fdsus'),
                    'name'  => 'dls_sus_reminder_email_subject',
                    'type'  => 'text',
                    /* translators: %s is replaced with the default subject */
                    'note' => esc_html(sprintf(__('If blank, defaults to... "%s"', 'fdsus'), Settings::$defaultMailSubjects['reminder'])),
                    'pro' => true
                ),
                array(
                    'label' => esc_html__('From E-mail Address', 'fdsus'),
                    'name'  => 'dls_sus_reminder_email_from',
                    'type'  => 'text',
                    'note'  => esc_html__('If blank, defaults to WordPress email on file under Settings > General', 'fdsus'),
                    'pro' => true
                ),
                array(
                    'label' => esc_html__('BCC', 'fdsus'),
                    'name'  => 'dls_sus_reminder_email_bcc',
                    'type'  => 'text',
                    'note'  => esc_html__('Comma separate for multiple email addresses', 'fdsus'),
                    'pro' => true
                ),
                array(
                    'label' => esc_html__('Message', 'fdsus'),
                    'name'  => 'dls_sus_reminder_email_message',
                    'type'  => 'textarea',
                    'note'  => sprintf(
                        '%s<br>
                            <code>{signup_details}</code> - %s<br>
                            <code>{signup_firstname}</code> - %s<br>
                            <code>{signup_lastname}</code> - %s<br>
                            <code>{signup_email}</code> - %s<br>
                            <code>{site_name}</code> - %s<br>
                            <code>{site_url}</code> - %s',
                        esc_html__('Variables that can be used in template...', 'fdsus'),
                        esc_html__('Multi-line list of sign-up details such as date, sheet title, task title', 'fdsus'),
                        esc_html__('First name of user that signed up', 'fdsus'),
                        esc_html__('Last name of user that signed up', 'fdsus'),
                        esc_html__('Email of user that signed up', 'fdsus'),
                        esc_html__('Name of site as defined in Settings > General > Site Title', 'fdsus'),
                        esc_html__('URL of site', 'fdsus')
                    ),
                    'pro' => true
                ),
            )
        );

        $options['status_email'] = array(
            'id'      => 'status_email',
            'title'   => esc_html__('Status E-mail', 'fdsus') . (!Id::isPro() ? ' <span class="dls-sus-pro" title="Pro Feature">Pro</span>' : ''),
            'order'   => 70,
            'options' => array(
                array(
                    'label' => esc_html__('Enable Status E-mail', 'fdsus'),
                    'name'  => 'dls_sus_status_email',
                    'type'  => 'checkbox',
                    'note'  => esc_html__('Shows all signups for a sheet.  Sent when a user adds or removes a signup from the frontend.', 'fdsus'),
                    'pro'   => true
                ),
                array(
                    'label' => esc_html__('Subject', 'fdsus'),
                    'name'  => 'dls_sus_status_email_subject',
                    'type'  => 'text',
                    /* translators: %s is replaced with the default subject */
                    'note'  => esc_html(sprintf(__('If blank, defaults to... "%s"', 'fdsus'), Settings::$defaultMailSubjects['status'])),
                    'pro'   => true
                ),
                array(
                    'label' => esc_html__('From E-mail Address', 'fdsus'),
                    'name'  => 'dls_sus_status_email_from',
                    'type'  => 'text',
                    'note'  => esc_html__('If blank, defaults to WordPress email on file under Settings > General', 'fdsus'),
                    'pro'   => true
                ),
                array(
                    'label' => esc_html__('Send to main admin emails', 'fdsus'),
                    'name'  => 'dls_sus_status_to_admin',
                    'type'  => 'checkbox',
                    'note'  => esc_html__('E-mail address specified under Settings > General', 'fdsus'),
                    'pro'   => true
                ),
                array(
                    'label' => esc_html__('Send to "Sheet BCC" recipients', 'fdsus'),
                    'name'  => 'dls_sus_status_to_sheet_bcc',
                    'type'  => 'checkbox',
                    'note'  => esc_html__('These addresses will be added as a recipient only for sheets on which they are assigned.', 'fdsus'),
                    'pro'   => true
                ),
            )
        );

        $options['advanced'] = array(
            'id'      => 'advanced',
            'title'   => esc_html__('Advanced', 'fdsus'),
            'order'   => 80,
            'options' => array(
                array('Sheet URL Slug', 'dls_sus_sheet_slug', 'text', 'Will be used in permalinks for your frontend archive page as well as single sheets pages. Default is <code>sheet</code>  Ex: https://example.com/<code>sheet</code>/my-signup-sheet/'),
                array('User roles that can manage sheets', 'dls_sus_roles', 'checkboxes', '(Note: Administrators and Sign-up Sheet Managers can always manage sheets)', $roles),
                array('Re-run Data Migration', 'dls_sus_rerun_migrate', 'button', '<span id="' . Id::PREFIX . '-rerun-migrate"></span>', array('href' => add_query_arg('migrate', 'rerun-2.1', $this->data->getSettingsUrl()))),
                array('Display Detailed Errors', 'dls_sus_detailed_errors', 'checkbox', '(Not recommended for production sites)'),
                array(
                    'label' => esc_html__('Reset All Settings', 'fdsus'),
                    'name' => 'fdsus_reset',
                    'type' => 'button',
                    'note' => '<span id="fdsus-reset"></span><p>' . esc_html__('This will erase any custom configurations you have made on this page and reset them back to the defaults. This action cannot be undone.', 'fdsus') . '</p>',
                    'options' => array(
                        'href' => add_query_arg('fdsus-reset', 'all',
                            wp_nonce_url($this->data->getSettingsUrl(), 'fdsus-settings-reset', '_fdsus-nonce')),
                        'onclick' => sprintf('return confirm(`%s %s`)',
                            esc_html__('Are you sure?', 'fdsus'),
                            esc_html__('This will erase any custom configurations you have made on this page and reset them back to the defaults. This action cannot be undone.', 'fdsus')
                        ),
                    )
                ),
            )
        );

        $options['text_overrides'] = array(
            'id'      => 'text_overrides',
            'title'   => esc_html__('Text Overrides', 'fdsus'),
            'order'   => 90,
            'options' => array()
        );

        foreach (Settings::$text as $key => $text) {
            $options['text_overrides']['options'][] = array($text['label'], 'dls_sus_text_' . $key, 'text', 'Default: ' . $text['default']);;
        }

        /**
         * Filter admin settings page options
         *
         * @param array $options
         *
         * @return array
         * @since 2.2
         */
        $options = apply_filters('fdsus_settings_page_options', $options);

        // Sort
        usort($options, array(&$this, 'sortByOrder'));
        foreach ($options as $key => $option) {
            // Include Pro settings
            if (!empty($option['options']) && is_array($option['options'])) {
                foreach ($option['options'] as $subKey => $subOption) {
                    if (!empty($subOption['pro'])) {
                        if (!Id::isPro()) {
                            $options[$key]['options'][$subKey]['label'] = !empty($options[$key]['options'][$subKey]['label'])
                                ? '<span class="dls-sus-pro" title="Pro Feature">Pro</span> ' . $options[$key]['options'][$subKey]['label']
                                : $options[$key]['options'][$subKey]['label'];
                            $options[$key]['options'][$subKey]['original_name'] = $options[$key]['options'][$subKey]['name'];
                            $options[$key]['options'][$subKey]['name'] = 'pro_feature_' . (int)$key . '_' . (int)$subKey;
                        }
                        $options[$key]['options'][$subKey]['disabled'] = !Id::isPro();
                        if (!isset($options[$key]['options'][$subKey]['class'])) {
                            $options[$key]['options'][$subKey]['class'] = '';
                        }
                        $options[$key]['options'][$subKey]['class'] .= Id::isPro() ? '' : 'fdsus-pro-setting';
                    }
                    if (isset($subOption['type']) && $subOption['type'] == 'repeater' && !empty($subOption['options']) && is_array($subOption['options'])) {
                        foreach ($subOption['options'] as $subSubKey => $subSubOption) {
                            if (!empty($subOption['pro'])) {
                                if (!Id::isPro()) {
                                    $options[$key]['options'][$subKey]['options'][$subSubKey]['name'] = 'pro_feature_' . (int)$key . '_' . (int)$subKey;
                                }
                                $options[$key]['options'][$subKey]['options'][$subSubKey]['disabled'] = !Id::isPro();
                                if (!isset($options[$key]['options'][$subKey]['options'][$subSubKey]['class'])) {
                                    $options[$key]['options'][$subKey]['options'][$subSubKey]['class'] = '';
                                }
                                $options[$key]['options'][$subKey]['options'][$subSubKey]['class'] .= Id::isPro() ? '' : 'fdsus-pro-setting';
                            }
                        }
                    }
                }
            }
            usort($options[$key]['options'], array(&$this, 'sortByOrder'));
        }
        reset($options);

        return $options;
    }

    /**
     * Sort by order
     *
     * @param array $a
     * @param array $b
     *
     * @return int
     */
    protected function sortByOrder($a, $b)
    {
        if (!isset($a['order'])) {
            $a['order'] = 0;
        }
        if (!isset($b['order'])) {
            $b['order'] = 0;
        }
        $result = 0;
        if ($a['order'] > $b['order']) {
            $result = 1;
        } else {
            if ($a['order'] < $b['order']) {
                $result = -1;
            }
        }
        return $result;
    }
}
