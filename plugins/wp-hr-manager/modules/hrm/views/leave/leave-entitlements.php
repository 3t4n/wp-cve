<?php
$cur_year = date('Y');
$active_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : '';
?>
<div class="wrap wphr-hr-employees" id="wp-wphr">

    <h2>
        <?php _e('Leave Entitlements', 'wphr');?>
        <?php if ('assignment' == $active_tab): ?>
            <a href="<?php echo admin_url('admin.php?page=wphr-leave-assign'); ?>" id="wphr-new-leave-request" class="add-new-h2"><?php _e('Back to Entitlement list', 'wphr');?></a>
        <?php else: ?>
        <!-- Mostrar Boton-->
              <a href="<?php echo add_query_arg(array('tab' => 'assignment'), admin_url('admin.php?page=wphr-leave-assign')); ?>" id="wphr-new-leave-request" class="add-new-h2"><?php _e('Add New ', 'wphr');?></a>
        <?php endif?>
    </h2>

    <?php if ('assignment' == $active_tab) {
	?>

        <p class="description">
            <?php _e('Assign a leave policy to employees.', 'wphr');?>
        </p>

        <?php
$errors = array(
		'invalid-policy' => __('Error: Please select a leave policy.', 'wphr'),
		'invalid-period' => __('Error: Please select a valid period.', 'wphr'),
		'invalid-employee' => __('Error: Please select an employee.', 'wphr'),
	);

	if (isset($_GET['affected'])) {
		wphr_html_show_notice(sprintf(__('%d Employee(s) has been entitled to this leave policy.', 'wphr'), $_GET['affected']));
	}

	if (isset($_GET['error']) && array_key_exists(sanitize_text_field($_GET['error']), $errors)) {
		wphr_html_show_notice($errors[$_GET['error']], 'error');
	}
	?>

        <form action="" method="post">

            <ul class="wphr-list separated">
            <?php
wphr_html_form_input(array(
		'label' => __('Assignment', 'wphr'),
		'name' => 'assignment_to',
		'type' => 'checkbox',
		'help' => __('Assign to multiple employees', 'wphr'),
		'tag' => 'li',
	));

	wphr_html_form_input(array(
		'label' => __('Leave Policy', 'wphr'),
		'name' => 'leave_policy',
		'type' => 'select',
		'class' => 'leave-policy-select',
		'tag' => 'li',
		'required' => true,
		'options' => array(0 => __('- Select -', 'wphr')) + wphr_hr_leave_get_policies_dropdown_raw(),
	));

	wphr_html_form_input(array(
		'label' => __('Leave Period', 'wphr'),
		'name' => 'leave_period',
		'type' => 'select',
		'tag' => 'li',
		'required' => true,
		'class' => 'leave-period-select',
		'options' => wphr_hr_leave_period(),
	));

	wphr_html_form_input(array(
		'label' => __('Employee', 'wphr'),
		'name' => 'single_employee',
		'type' => 'select',
		'class' => 'wphr-select2 show-if-single',
		'tag' => 'li',
		'required' => true,
		'options' => wphr_hr_get_employees_dropdown_raw(),
	));

	wphr_html_form_input(array(
		'label' => __('Location', 'wphr'),
		'name' => 'location',
		'type' => 'select',
		'class' => 'wphr-select2 show-if-multiple',
		'tag' => 'li',
		'options' => wphr_company_get_location_dropdown_raw(__('All Locations', 'wphr')),
	));

	wphr_html_form_input(array(
		'label' => __('Department', 'wphr'),
		'name' => 'department',
		'type' => 'select',
		'class' => 'wphr-select2 show-if-multiple',
		'tag' => 'li',
		'options' => wphr_hr_get_departments_dropdown_raw(__('All Departments', 'wphr')),
	));

	wphr_html_form_input(array(
		'label' => __('Comment', 'wphr'),
		'name' => 'comment',
		'type' => 'textarea',
		'tag' => 'li',
		'placeholder' => __('Optional Comment', 'wphr'),
	));

	?>
            </ul>

            <input type="hidden" name="wphr-action" value="hr-leave-assign-policy">

            <?php wp_nonce_field('wphr-hr-leave-assign');?>
            <?php submit_button(__('Assign Policies', 'wphr'), 'primary');?>
        </form>

        <script type="text/javascript">
            jQuery(function($) {
                $( '#assignment_to' ).on('change', function() {
                    if ( $(this).is(':checked') ) {
                        $( '.department_field, .location_field' ).show();
                        $( '.single_employee_field' ).hide();
                    } else {
                        $( '.department_field, .location_field' ).hide();
                        $( '.single_employee_field' ).show();
                    }
                });

                $( '#assignment_to' ).change();
            });
        </script>

    <?php } else {
	?>

        <div id="wphr-entitlement-table-wrap">

            <div class="list-table-inner">

                <form method="get">
                    <input type="hidden" name="page" value="wphr-leave-assign">
                    <input type="hidden" name="tab" value="entitlements">
                    <?php
$entitlement = new \WPHR\HR_MANAGER\HRM\Entitlement_List_Table();
	$entitlement->prepare_items();
	$entitlement->views();

	$entitlement->display();
	?>
                </form>

            </div><!-- .list-table-inner -->
        </div><!-- .list-table-wrap -->
    <?php }?>
</div>
