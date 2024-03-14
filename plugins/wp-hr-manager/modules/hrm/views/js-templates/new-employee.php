<div class="wphr-employee-form">

    <?php do_action('wphr-hr-employee-form-top');?>

    <fieldset class="no-border">
        <ol class="form-fields">
            <li>
                <?php wphr_html_form_label(__('Employee Photo', 'wphr'), 'full-name');?>

                <div class="photo-container">
                    <input type="hidden" name="personal[photo_id]" id="emp-photo-id" value="{{ data.avatar.id }}">

                    <# if ( data.avatar.id ) { #>
                        <img src="{{ data.avatar.url }}" alt="" />
                        <a href="#" class="wphr-remove-photo">&times;</a>
                    <# } else { #>
                        <a href="#" id="wphr-set-emp-photo" class="button button-small"><?php _e('Upload Employee Photo', 'wphr');?></a>
                    <# } #>
                </div>
            </li>

            <li class="full-width name-container clearfix">
                <?php wphr_html_form_label(__('Full Name', 'wphr'), 'full-name', true);?>

                <ol class="fields-inline">
                    <li>
                        <?php wphr_html_form_input(array(
	'label' => __('First Name', 'wphr'),
	'name' => 'personal[first_name]',
	'id' => 'first_name',
	'value' => '{{ data.name.first_name }}',
	'required' => true,
	'custom_attr' => array('maxlength' => 30),
));?>
                    </li>
                    <li class="middle-name">
                        <?php wphr_html_form_input(array(
	'label' => __('Middle Name', 'wphr'),
	'name' => 'personal[middle_name]',
	'id' => 'middle_name',
	'value' => '{{ data.name.middle_name }}',
	'custom_attr' => array('maxlength' => 30),
));?>
                    </li>
                    <li>
                        <?php wphr_html_form_input(array(
	'label' => __('Last Name', 'wphr'),
	'name' => 'personal[last_name]',
	'id' => 'last_name',
	'value' => '{{ data.name.last_name }}',
	'required' => true,
	'custom_attr' => array('maxlength' => 30),
));?>
                    </li>
                </ol>
            </li>
        </ol>

        <ol class="form-fields two-col">
            <?php if (current_user_can('wphr_edit_employee')): ?>
                <li>
                    <?php wphr_html_form_input(array(
	'label' => __('Employee ID', 'wphr'),
	'name' => 'personal[employee_id]',
	'value' => '{{ data.employee_id }}',
));?>
                </li>
            <?php else: ?>
                <input type="hidden" name="personal[employee_id]" value="{{ data.employee_id }}">
            <?php endif;?>
            <li>
                <?php wphr_html_form_input(array(
	'label' => __('Email', 'wphr'),
	'name' => 'user_email',
	'value' => '{{ data.user_email }}',
	'id' => 'wphr-hr-user-email',
	'required' => true,
	'type' => 'email',
));?>
            </li>
            <?php do_action('wphr-hr-employee-form-basic');?>
        </ol>

    </fieldset>
    <?php if (current_user_can('wphr_edit_employee')): ?>
        <fieldset>
            <legend><?php _e('Work', 'wphr')?></legend>

            <ol class="form-fields two-col">

            <# if ( ! data.id ) { #>
                <li class="wphr-hr-js-department" data-selected="{{ data.work.department }}">
                    <?php wphr_html_form_input(array(
	'label' => __('Department', 'wphr'),
	'name' => 'work[department]',
	'value' => '',
	'class' => 'wphr-hrm-select2-add-more wphr-hr-dept-drop-down',
	'custom_attr' => array('data-id' => 'wphr-new-dept'),
	'type' => 'select',
	'options' => wphr_hr_get_departments_dropdown_raw(),
));?>
                </li>

                <li data-selected="{{ data.work.designation }}">
                    <?php wphr_html_form_input(array(
	'label' => __('Role', 'wphr'),
	'name' => 'work[designation]',
	'value' => '{{ data.work.designation }}',
	'class' => 'wphr-hrm-select2-add-more wphr-hr-desi-drop-down',
	'custom_attr' => array('data-id' => 'wphr-new-designation'),
	'type' => 'select',
	'options' => wphr_hr_get_designation_dropdown_raw(),
));?>
                </li>
                <li>
                    <?php wphr_html_form_input(array(
	'label' => __('Detailed Title', 'wphr'),
	'name' => 'work[job_title_detail]',
	'value' => '{{ data.work.job_title_detail }}',
	'type' => 'text',
));?>
                </li>

                <li data-selected="{{ data.work.type }}">
                    <?php
wphr_html_form_input(array(
	'label' => __('Employee Type', 'wphr'),
	'name' => 'work[type]',
	'value' => '{{ data.work.type }}',
	'class' => 'wphr-hrm-select2',
	'type' => 'select',
	'required' => true,
	'options' => array('' => __('- Select -', 'wphr')) + wphr_hr_get_employee_types(),
));
?>
                </li>

                <li data-selected="{{ data.work.status }}">
                    <?php
wphr_html_form_input(array(
	'label' => __('Employee Status', 'wphr'),
	'name' => 'work[status]',
	'value' => '{{ data.work.status }}',
	'class' => 'wphr-hrm-select2',
	'type' => 'select',
	'required' => true,
	'options' => array('' => __('- Select -', 'wphr')) + wphr_hr_get_employee_statuses(),
));
?>
                </li>

            <# } #>

                <li data-selected="{{ data.work.hiring_source }}">
                    <?php wphr_html_form_input(array(
	'label' => __('Source of Hire', 'wphr'),
	'name' => 'work[hiring_source]',
	'value' => '{{ data.work.hiring_source }}',
	'class' => 'wphr-hrm-select2',
	'type' => 'select',
	'options' => array('-1' => __('- Select -', 'wphr')) + wphr_hr_get_employee_sources(),
));?>
                </li>

                <li>
                    <?php
wphr_html_form_input(array(
	'label' => __('Date of Hire', 'wphr'),
	'name' => 'work[hiring_date]',
	'value' => '{{ data.work.hiring_date }}',
	'required' => true,
	'type' => 'text',
	'class' => 'wphr-date-field',
));
?>
                </li>

                <# if ( ! data.id ) { #>

                    <li>
                        <?php wphr_html_form_input(array(
	'label' => __('Pay Rate', 'wphr'),
	'name' => 'work[pay_rate]',
	'value' => '{{ data.work.pay_rate }}',
	'type' => 'text',
));?>
                    </li>

                    <li data-selected="{{ data.work.pay_type }}">
                        <?php wphr_html_form_input(array(
	'label' => __('Pay Type', 'wphr'),
	'name' => 'work[pay_type]',
	'value' => '{{ data.work.pay_type }}',
	'class' => 'wphr-hrm-select2',
	'type' => 'select',
	'options' => array('-1' => __('- Select -', 'wphr')) + wphr_hr_get_pay_type(),
));?>
                    </li>

                <# } #>

                <li data-selected="{{ data.work.location }}">
                    <?php wphr_html_form_input(array(
	'label' => __('Location', 'wphr'),
	'name' => 'work[location]',
	'value' => '{{ data.work.location }}',
	'custom_attr' => array('data-id' => 'wphr-company-new-location'),
	// 'class'   => 'wphr-hrm-select2-add-more wphr-hr-location-drop-down',
	'class' => 'wphr-hrm-select2',
	'type' => 'select',
	'options' => wphr_company_get_location_dropdown_raw(),
));?>
                </li>

                <li>
                    <?php wphr_html_form_input(array(
	'label' => __('Work Phone', 'wphr'),
	'name' => 'personal[work_phone]',
	'value' => '{{ data.personal.work_phone }}',
));?>
                </li>

                <?php do_action('wphr-hr-employee-form-work');?>


            </ol>
            <ol class="form-fields">
                <li class="full-width reporting-to-container clearfix">
                    <?php wphr_html_form_label(__('Reporting To', 'wphr'), 'reporting-to', true);?>

                    <ol class="fields-inline">
                        <li data-selected="{{ data.work.reporting_to }}">
                            <?php wphr_html_form_input(array(
	'label' => '',
	'name' => 'work[reporting_to]',
	'value' => '{{ data.work.reporting_to }}',
	'class' => 'wphr-hrm-select2',
	'type' => 'select',
	'id' => 'work_reporting_to',
	'options' => wphr_hr_get_employees_dropdown_raw(),
));?>
                        </li>
                        <li data-selected="{{ data.work.send_mail_to_reporter }}">
                            <?php wphr_html_form_input(array(
	'label' => __('Receive E-mail Notification', 'wphr'),
	'name' => 'work[send_mail_to_reporter]',
	'id' => 'send_mail_to_reporter',
	'type' => 'checkbox',
));?>
                        </li>
                        <li data-selected="{{ data.work.manage_leave_by_reporter }}">
                            <?php wphr_html_form_input(array(
	'label' => __('Manage Leave', 'wphr'),
	'name' => 'work[manage_leave_by_reporter]',
	'id' => 'manage_leave_by_reporter',
	'type' => 'checkbox',
));?>
                        </li>

                        <?php
$wed_birthday_option = wphr_get_option('ebirthday_id', 'wphr_settings_widget', 1);
if ($wed_birthday_option == 'yes') {
	?>

                         <li data-selected="{{ data.work.anniversary_permission}}">
                            <?php wphr_html_form_input(array(
		'label' => __('Annual Anniversary', 'wphr'),
		'name' => 'work[anniversary_permission]',
		'id' => 'anniversary_permission',
		'type' => 'checkbox',
	));?>
                        </li>
                     <?php }
?>

                </li>
                 <?php
$wed_work_option = wphr_get_option('ework_id', 'wphr_settings_widget', 1);
if ($wed_work_option == 'yes') {
	?>

                         <li data-selected="{{ data.work.work_permission}}">
                            <?php wphr_html_form_input(array(
		'label' => __('Work Anniversary', 'wphr'),
		'name' => 'work[work_permission]',
		'id' => 'work_permission',
		'type' => 'checkbox',
	));?>
                        </li>
                     <?php }
?>

                </li>

                 <?php
$wed_office_option = wphr_get_option('inout_id', 'wphr_settings_widget', 1);
if ($wed_office_option == 'yes') {
	?>

                         <li data-selected="{{ data.work.inout_office}}">
                            <?php wphr_html_form_input(array(
		'label' => __('InOut office', 'wphr'),
		'name' => 'work[inout_office]',
		'id' => 'inout_office',
		'type' => 'checkbox',
	));?>
                        </li>
                     <?php }
?>

                </li>
            </ol>
        </fieldset>
    <?php endif;?>
    <fieldset>
        <legend><?php _e('Personal Details', 'wphr')?></legend>

        <ol class="form-fields two-col">
            <li>
                <?php wphr_html_form_input(array(
	'label' => __('Mobile', 'wphr'),
	'name' => 'personal[mobile]',
	'value' => '{{ data.personal.mobile }}',
));?>
            </li>

            <li>
                <?php wphr_html_form_input(array(
	'label' => __('Phone', 'wphr'),
	'name' => 'personal[phone]',
	'value' => '{{ data.personal.phone }}',
));?>
            </li>

            <li>
                <?php wphr_html_form_input(array(
	'label' => __('Other Email', 'wphr'),
	'name' => 'personal[other_email]',
	'value' => '{{ data.personal.other_email }}',
	'type' => 'email',
));?>
            </li>

            <li>
                <?php wphr_html_form_input(array(
	'label' => __('Date of Birth', 'wphr'),
	'name' => 'work[date_of_birth]',
	'value' => '{{ data.work.date_of_birth }}',
	'class' => 'wphr-date-field',
));?>
            </li>

            <li data-selected="{{ data.personal.nationality }}">
                <?php wphr_html_form_input(array(
	'label' => __('Nationality', 'wphr'),
	'name' => 'personal[nationality]',
	'value' => '{{ data.personal.nationality }}',
	'class' => 'wphr-hrm-select2',
	'type' => 'select',
	'options' => \WPHR\HR_MANAGER\Countries::instance()->get_countries('-1'),
));?>
            </li>

            <li data-selected="{{ data.personal.gender }}">
                <?php wphr_html_form_input(array(
	'label' => __('Gender', 'wphr'),
	'name' => 'personal[gender]',
	'value' => '{{ data.personal.gender }}',
	'class' => 'wphr-hrm-select2',
	'type' => 'select',
	'options' => wphr_hr_get_genders(),
));?>
            </li>

            <li data-selected="{{ data.personal.marital_status }}">
                <?php wphr_html_form_input(array(
	'label' => __('Marital Status', 'wphr'),
	'name' => 'personal[marital_status]',
	'value' => '{{ data.personal.marital_status }}',
	'class' => 'wphr-hrm-select2',
	'type' => 'select',
	'options' => wphr_hr_get_marital_statuses(),
));?>
            </li>

            <li>
                <?php wphr_html_form_input(array(
	'label' => __('Driving License', 'wphr'),
	'name' => 'personal[driving_license]',
	'value' => '{{ data.personal.driving_license }}',
));?>
            </li>

            <li>
                <?php wphr_html_form_input(array(
	'label' => __('Hobbies', 'wphr'),
	'name' => 'personal[hobbies]',
	'value' => '{{ data.personal.hobbies }}',
));?>
            </li>

            <li>
                <?php wphr_html_form_input(array(
	'label' => __('Website', 'wphr'),
	'name' => 'personal[user_url]',
	'value' => '{{ data.personal.user_url }}',
	'type' => 'url',
));?>
            </li>

            <li>
                <?php wphr_html_form_input(array(
	'label' => __('Address 1', 'wphr'),
	'name' => 'personal[street_1]',
	'value' => '{{ data.personal.street_1 }}',
));?>
            </li>

            <li>
                <?php wphr_html_form_input(array(
	'label' => __('Address 2', 'wphr'),
	'name' => 'personal[street_2]',
	'value' => '{{ data.personal.street_2 }}',
));?>
            </li>

            <li>
                <?php wphr_html_form_input(array(
	'label' => __('City', 'wphr'),
	'name' => 'personal[city]',
	'value' => '{{ data.personal.city }}',
));?>
            </li>

            <li data-selected="{{ data.personal.country }}">
                <label for="wphr-popup-country"><?php _e('Country', 'wphr');?></label>
                <select name="personal[country]" id="wphr-popup-country" class="wphr-country-select select2" data-parent="ol">
                    <?php $country = \WPHR\HR_MANAGER\Countries::instance();?>
                    <?php echo $country->country_dropdown(); ?>
                </select>
            </li>

             <li data-selected="{{ data.personal.state }}">
                <?php wphr_html_form_input(array(
	'label' => __('Province / State', 'wphr'),
	'name' => 'personal[state]',
	'value' => '{{data.personal.state}}',
	//  'id'      => 'wphr-state',
	// 'type'    => 'select',
	// 'class'   => 'wphr-state-select',
	// 'options' => array( '' => __( '- Select -', 'wphr' ) )
));?>
            </li>

            <!-- <li data-selected="{{ data.personal.state }}">
                <?php wphr_html_form_input(array(
	'label' => __('Province / State', 'wphr'),
	'name' => 'personal[state]',
	'id' => 'wphr-state',
	'type' => 'select',
	'class' => 'wphr-state-select',
	'options' => array('' => __('- Select -', 'wphr')),
));?>
            </li> -->

            <li>
                <?php wphr_html_form_input(array(
	'label' => __('Post Code/Zip Code', 'wphr'),
	'name' => 'personal[postal_code]',
	'value' => '{{ data.personal.postal_code }}',
));?>
            </li>

            <li>
                <?php wphr_html_form_input(array(
	'label' => __('Biography', 'wphr'),
	'name' => 'personal[description]',
	'value' => '{{ data.personal.description }}',
	'type' => 'textarea',
));?>
            </li>

            <?php do_action('wphr-hr-employee-form-personal');?>

        </ol>
    </fieldset>


    <# if ( ! data.id ) { #>

        <fieldset>
            <ol class="form-fields">
                <li>
                    <?php wphr_html_form_input(array(
	'label' => __('Notification', 'wphr'),
	'name' => 'user_notification',
	'help' => __('Send the employee an welcome email.', 'wphr'),
	'type' => 'checkbox',
));?>
                </li>

                <li class="show-if-notification" style="display:none">
                    <?php wphr_html_form_input(array(
	'label' => '&nbsp;',
	'name' => 'login_info',
	'help' => __('Send the login details as well. If <code>{login_info}</code> present.', 'wphr'),
	'type' => 'checkbox',
));?>
                </li>
            </ol>


        </fieldset>

    <# } #>

    <?php do_action('wphr-hr-employee-form-bottom');?>



    <input type="hidden" name="user_id" id="wphr-employee-id" value="{{ data.id }}">
    <input type="hidden" name="action" id="wphr-employee-action" value="wphr-hr-employee-new">
    <?php wp_nonce_field('wp-wphr-hr-employee-nonce');?>
    <?php do_action('wphr_hr_employee_form');?>

    <fieldset>
            <ul class="form-fields">
                <h3>Update Leave Tab Extra Field</h3>
                <li>
      <?php do_action('wphr-hr-employee-leave-balance');?></li>
     <li><?php do_action('wphr-hr-employee-leave-history');?>
 </li>
       </ul>
        </fieldset>




</div>
