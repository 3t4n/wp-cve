<div class="dept-form-wrap">
    <div class="row">
        <?php wphr_html_form_label( __( 'Department Title', 'wphr' ), 'dept-title', true ); ?>

        <span class="field">
            <input type="text" id="dept-title" name="title" value="" required="required">
        </span>
    </div>

    <div class="row">
        <?php wphr_html_form_label( __( 'User Profile Label', 'wphr' ), 'emp_profile_label' ); ?>

        <span class="field">
            <input type="text" id="emp-profile-label" name="emp_profile_label" placeholder="<?php _e( 'Employee', 'wphr' ); ?>" value="">
        </span>
    </div>

    <div class="row">
        <?php wphr_html_form_label( __( 'Description', 'wphr' ), 'dept-desc' ); ?>

        <span class="field">
            <textarea name="dept-desc" id="dept-desc" rows="2" cols="20" placeholder="<?php _e( 'Optional', 'wphr' ); ?>"></textarea>
        </span>
    </div>

    <div class="row">
        <?php wphr_html_form_label( __( 'Department Lead', 'wphr' ), 'dept-lead' ); ?>

        <span class="field">
            <select name="lead" id="dept-lead">
                <?php echo wphr_hr_get_employees_dropdown(); ?>
            </select>
        </span>
    </div>

    <div class="row">
        <?php wphr_html_form_label( __( 'Parent Department', 'wphr' ), 'parent-dept' ); ?>

        <span class="field">
            <select name="parent" id="dept-parent">
                <?php echo wphr_hr_get_departments_dropdown(); ?>
            </select>
        </span>
    </div>

    <?php wp_nonce_field( 'wphr-new-dept' ); ?>
    <input type="hidden" name="action" id="dept-action" value="wphr-hr-new-dept">
    <input type="hidden" name="dept_id" id="dept-id" value="0">
</div>
