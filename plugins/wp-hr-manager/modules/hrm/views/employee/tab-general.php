<?php do_action( 'wphr-hr-employee-single-top', $employee ); ?>

<div class="postbox leads-actions">
    <div class="handlediv" title="<?php _e( 'Click to toggle', 'wphr' ); ?>"><br></div>
    <h3 class="hndle"><span><?php _e( 'Basic Info', 'wphr' ); ?></span></h3>
    <div class="inside">
        <ul class="wphr-list two-col separated">
            <li><?php wphr_print_key_value( __( 'First Name', 'wphr' ), $employee->first_name ); ?></li>
            <li><?php wphr_print_key_value( __( 'Last Name', 'wphr' ), $employee->last_name ); ?></li>
            <li><?php wphr_print_key_value( __( 'Employee ID', 'wphr' ), $employee->employee_id ); ?></li>
            <li><?php wphr_print_key_value( __( 'Email', 'wphr' ), wphr_get_clickable( 'email', $employee->user_email ) ); ?></li>

            <?php do_action( 'wphr-hr-employee-single-basic', $employee ); ?>
        </ul>
    </div>
</div><!-- .postbox -->
<?php if ( current_user_can( 'wphr_edit_employee' ) || get_current_user_id() == $employee->id ) : ?>

    <div class="postbox leads-actions">
        <div class="handlediv" title="<?php _e( 'Click to toggle', 'wphr' ); ?>"><br></div>
        <h3 class="hndle"><span><?php _e( 'Work', 'wphr' ); ?></span></h3>
        <div class="inside">
            <ul class="wphr-list two-col separated">
                <li><?php wphr_print_key_value( __( 'Department', 'wphr' ), $employee->get_department_title() ); ?></li>
                <li><?php wphr_print_key_value( __( 'Title', 'wphr' ), $employee->get_job_title() ); ?></li>
                <li><?php wphr_print_key_value( __( 'Detailed Title', 'wphr' ), $employee->get_job_title_detail() ); ?></li>

                <?php
                $reporting_to = $employee->get_reporting_to();
                $reporting_to = $reporting_to ? $reporting_to->get_link() : '-';
                ?>
                <li><?php wphr_print_key_value( __( 'Reporting To', 'wphr' ), $reporting_to ); ?></li>
                <li><?php wphr_print_key_value( __( 'Date of Hire', 'wphr' ), $employee->get_joined_date() ); ?></li>
                <li><?php wphr_print_key_value( __( 'Source of Hire', 'wphr' ), $employee->get_hiring_source() ); ?></li>
                <li><?php wphr_print_key_value( __( 'Employee Status', 'wphr' ), $employee->get_status() ); ?></li>
                <li><?php wphr_print_key_value( __( 'Work Phone', 'wphr' ), wphr_get_clickable( 'phone', $employee->get_phone( 'work' ) ) ); ?></li>
                <li><?php wphr_print_key_value( __( 'Employee Type', 'wphr' ), $employee->get_type() ); ?></li>

                <?php do_action( 'wphr-hr-employee-single-work', $employee ); ?>
            </ul>
        </div>
    </div><!-- .postbox -->

    <div class="postbox leads-actions">
        <div class="handlediv" title="<?php _e( 'Click to toggle', 'wphr' ); ?>"><br></div>
        <h3 class="hndle"><span><?php _e( 'Personal Details', 'wphr' ); ?></span></h3>
        <div class="inside">
            <ul class="wphr-list two-col separated">
                <li><?php wphr_print_key_value( __( 'Address 1', 'wphr' ), $employee->get_street_1() ); ?></li>
                <li><?php wphr_print_key_value( __( 'Address 2', 'wphr' ), $employee->get_street_2() ); ?></li>
                <li><?php wphr_print_key_value( __( 'City', 'wphr' ), $employee->get_city() ); ?></li>
                <li><?php wphr_print_key_value( __( 'Country', 'wphr' ), $employee->get_country() ); ?></li>
                <li><?php wphr_print_key_value( __( 'State', 'wphr' ), $employee->get_state() ); ?></li>
                <li><?php wphr_print_key_value( __( 'Postal Code', 'wphr' ), $employee->get_postal_code() ); ?></li>

                <li><?php wphr_print_key_value( __( 'Mobile', 'wphr' ), wphr_get_clickable( 'phone', $employee->get_phone( 'mobile' ) ) ); ?></li>
                <li><?php wphr_print_key_value( __( 'Other Email', 'wphr' ), wphr_get_clickable( 'email', $employee->other_email ) ); ?></li>
                <li><?php wphr_print_key_value( __( 'Date of Birth', 'wphr' ), $employee->get_birthday() ); ?></li>
                <li><?php wphr_print_key_value( __( 'Gender', 'wphr' ), $employee->get_gender() ); ?></li>
                <li><?php wphr_print_key_value( __( 'Nationality', 'wphr' ), $employee->get_nationality() ); ?></li>
                <li><?php wphr_print_key_value( __( 'Marital Status', 'wphr' ), $employee->get_marital_status() ); ?></li>
                <li><?php wphr_print_key_value( __( 'Driving License', 'wphr' ), $employee->driving_license ); ?></li>
                <li><?php wphr_print_key_value( __( 'Hobbies', 'wphr' ), $employee->hobbies ); ?></li>

                <?php do_action( 'wphr-hr-employee-single-personal', $employee ); ?>
            </ul>
        </div>
    </div><!-- .postbox -->

    <?php do_action( 'wphr-hr-employee-single-after-personal', $employee ); ?>

    <div class="postbox leads-actions wphr-work-experience-wrap">
        <div class="handlediv" title="<?php _e( 'Click to toggle', 'wphr' ); ?>"><br></div>
        <h3 class="hndle"><span><?php _e( 'Work Experience', 'wphr' ); ?></span></h3>
        <div class="inside">

            <?php
            $experiences = $employee->get_experiences();

            if ( ! $experiences->isEmpty() ) {
                ?>
                <table class="widefat" style="margin-bottom: 15px;">
                    <thead>
                        <tr>
                            <th><?php _e( 'Previous Company', 'wphr' ); ?></th>
                            <th><?php _e( 'Role', 'wphr' ); ?></th>
                            <th><?php _e( 'From', 'wphr' ); ?></th>
                            <th><?php _e( 'To', 'wphr' ); ?></th>
                            <th><?php _e( 'Job Description', 'wphr' ); ?></th>
                            <th width="10%">&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($experiences as $key => $experience) { ?>
                            <?php
                                $experience->from = wphr_format_date( $experience->from );
                                $experience->to = wphr_format_date( $experience->to );
                            ?>
                            <tr class="<?php echo $key % 2 == 0 ? 'alternate' : 'odd'; ?>">
                                <td><?php echo esc_html( $experience->company_name ); ?></td>
                                <td><?php echo esc_html( $experience->job_title ); ?></td>
                                <td><?php echo $experience->from; ?></td>
                                <td><?php echo $experience->to; ?></td>
                                <td><?php echo esc_html( $experience->description ); ?></td>
                                <td width="10%">
                                    <?php if ( current_user_can( 'wphr_edit_employee', $employee->id ) ) : ?>
                                        <div class="row-actions wphr-hide-print">
                                            <a href="#" class="work-experience-edit" data-template="wphr-employment-work-experience" data-title="<?php esc_attr_e( 'Work Experience', 'wphr' ); ?>" data-data='<?php echo json_encode( $experience ); ?>' data-button="<?php esc_attr_e( 'Update Experience', 'wphr' ); ?>"><span class="dashicons dashicons-edit"></span></a>
                                            <a href="#" class="work-experience-delete" data-employee_id="<?php echo $employee->id; ?>" data-id="<?php echo $experience->id; ?>" data-action="wphr-hr-emp-delete-exp"><span class="dashicons dashicons-trash"></span></a>
                                        </div>
                                    <?php endif; ?>
                                </td>
                            </tr>

                        <?php } ?>
                    </tbody>
                </table>
            <?php } else { ?>

                <?php _e( 'No work experiences found.', 'wphr' ); ?>

            <?php } ?>
        </div>
            <?php if ( current_user_can( 'wphr_edit_employee', $employee->id ) ) : ?>
                <a class="button button-secondary wphr-hide-print" id="wphr-empl-add-exp" href="#" data-data='<?php echo json_encode( [ 'employee_id' => $employee->id ] ); ?>' data-button="<?php esc_attr_e( 'Create Experience', 'wphr' ); ?>"  data-template="wphr-employment-work-experience" data-title="<?php esc_attr_e( 'Work Experience', 'wphr' ); ?>"><?php _e( '+ Add Experience', 'wphr' ); ?></a>
            <?php endif; ?>
    </div><!-- .postbox -->

    <div class="postbox leads-actions">
        <div class="handlediv" title="<?php _e( 'Click to toggle', 'wphr' ); ?>"><br></div>
        <h3 class="hndle"><span><?php _e( 'Education', 'wphr' ); ?></span></h3>
        <div class="inside">
            <?php
            $educations = $employee->get_educations();

            if ( ! $educations->isEmpty() ) {
                ?>
                <table class="widefat" style="margin-bottom: 15px;">
                    <thead>
                        <tr>
                            <th><?php _e( 'School Name', 'wphr' ); ?></th>
                            <th><?php _e( 'Degree', 'wphr' ); ?></th>
                            <th><?php _e( 'Field(s) of Study', 'wphr' ); ?></th>
                            <th><?php _e( 'Year of Completion', 'wphr' ); ?></th>
                            <th><?php _e( 'Additional Notes', 'wphr' ); ?></th>
                            <th><?php _e( 'Interests', 'wphr' ); ?></th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($educations as $key => $education) { ?>

                            <tr class="<?php echo $key % 2 == 0 ? 'alternate' : 'odd'; ?>">
                                <td><?php echo esc_html( $education->school ); ?></td>
                                <td><?php echo esc_html( $education->degree ); ?></td>
                                <td><?php echo esc_html( $education->field ); ?></td>
                                <td><?php echo $education->finished; ?></td>
                                <td><?php echo $education->notes ? esc_html( $education->notes ) : '-'; ?></td>
                                <td><?php echo $education->interest ? esc_html( $education->interest ) : '-'; ?></td>
                                <td width="10%">
                                    <?php if ( current_user_can( 'wphr_edit_employee', $employee->id ) ) : ?>
                                        <div class="row-actions wphr-hide-print">
                                            <a href="#" class="education-edit" data-template="wphr-employment-education" data-title="<?php esc_attr_e( 'Education', 'wphr' ); ?>" data-data='<?php echo json_encode( $education ); ?>' data-button="<?php esc_attr_e( 'Update Info', 'wphr' ); ?>"><span class="dashicons dashicons-edit"></span></a>
                                            <a href="#" class="education-delete" data-employee_id="<?php echo $employee->id; ?>" data-id="<?php echo $education->id; ?>" data-action="wphr-hr-emp-delete-education"><span class="dashicons dashicons-trash"></span></a>
                                        </div>
                                    <?php endif; ?>
                                </td>
                            </tr>

                        <?php } ?>
                    </tbody>
                </table>
            <?php } else { ?>

                <?php _e( 'No education information found.', 'wphr' ); ?>

            <?php } ?>

        </div>
            <?php if ( current_user_can( 'wphr_edit_employee', $employee->id ) ) : ?>
                <a class="button button-secondary wphr-hide-print" id="wphr-empl-add-education" href="#" data-data='<?php echo json_encode( array( 'employee_id' => $employee->id ) ); ?>'  data-template="wphr-employment-education" data-title="<?php esc_attr_e( 'Education', 'wphr' ); ?>" data-button="<?php esc_attr_e( 'Add Education', 'wphr' ); ?>"><?php _e( '+ Add Education', 'wphr' ); ?></a>
            <?php endif; ?>
    </div><!-- .postbox -->

    <div class="postbox leads-actions">
        <div class="handlediv" title="<?php _e( 'Click to toggle', 'wphr' ); ?>"><br></div>
        <h3 class="hndle"><span><?php _e( 'Dependents', 'wphr' ); ?></span></h3>
        <div class="inside">

            <?php
            $dependents = $employee->get_dependents();

            if ( ! $dependents->isEmpty() ) {
                ?>
                <table class="widefat" style="margin-bottom: 15px;">
                    <thead>
                        <tr>
                            <th><?php _e( 'Name', 'wphr' ); ?></th>
                            <th><?php _e( 'Relationship', 'wphr' ); ?></th>
                            <th><?php _e( 'Date of Birth', 'wphr' ); ?></th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dependents as $key => $dependent) { ?>
                            <?php //$dependent->dob = wphr_format_date( $dependent->dob ); ?>
                            <tr class="<?php echo $key % 2 == 0 ? 'alternate' : 'odd'; ?>">
                                <td><?php echo esc_html( $dependent->name ); ?></td>
                                <td><?php echo esc_html( $dependent->relation ); ?></td>
                                <td><?php echo $dependent->dob; ?></td>
                                <td width="10%">
                                    <?php if ( current_user_can( 'wphr_edit_employee', $employee->id ) ) : ?>
                                        <div class="row-actions wphr-hide-print">
                                            <a href="#" class="dependent-edit" data-template="wphr-employment-dependent" data-title="<?php esc_attr_e( 'Dependents', 'wphr' ); ?>" data-data='<?php echo json_encode( $dependent ); ?>' data-button="<?php esc_attr_e( 'Update Dependent', 'wphr' ); ?>"><span class="dashicons dashicons-edit"></span></a>
                                            <a href="#" class="dependent-delete" data-employee_id="<?php echo $employee->id; ?>" data-id="<?php echo $dependent->id; ?>" data-action="wphr-hr-emp-delete-dependent"><span class="dashicons dashicons-trash"></span></a>
                                        </div>
                                    <?php endif; ?>
                                </td>
                            </tr>

                        <?php } ?>
                    </tbody>
                </table>
            <?php } else { ?>

                <?php _e( 'No dependent information found.', 'wphr' ); ?>

            <?php } ?>

        </div>
            <?php if ( current_user_can( 'wphr_edit_employee', $employee->id ) ) : ?>
                <a class="button button-secondary wphr-hide-print" id="wphr-empl-add-dependent" href="#" data-data='<?php echo json_encode( array( 'employee_id' => $employee->id ) ); ?>'  data-template="wphr-employment-dependent" data-title="<?php esc_attr_e( 'Dependent', 'wphr' ); ?>" data-button="<?php esc_attr_e( 'Add Dependent', 'wphr' ); ?>"><?php _e( '+ Add Dependents', 'wphr' ); ?></a>
            <?php endif; ?>
    </div><!-- .postbox -->

    <?php if ( $employee->get_status() == 'Terminated' ): ?>

        <div class="postbox leads-actions">
            <div class="handlediv" title="<?php _e( 'Click to toggle', 'wphr' ); ?>"><br></div>
            <h3 class="hndle"><span><?php _e( 'Termination', 'wphr' ); ?></span></h3>
            <div class="inside">

                <?php $termination_data = get_user_meta( $employee->id, '_wphr_hr_termination', true ); ?>

                <p><?php _e( 'Termination Date', 'wphr' ); ?> : <?php echo isset( $termination_data['terminate_date'] ) ? wphr_format_date( $termination_data['terminate_date'] ) : ''; ?></p>
                <p><?php _e( 'Termination Type', 'wphr' ); ?> : <?php echo isset( $termination_data['termination_type'] ) ? wphr_hr_get_terminate_type( $termination_data['termination_type'] ) : ''; ?></p>
                <p><?php _e( 'Termination Reason', 'wphr' ); ?> : <?php echo isset( $termination_data['termination_reason'] ) ? wphr_hr_get_terminate_reason( $termination_data['termination_reason'] ) : ''; ?></p>
                <p><?php _e( 'Eligible for Hire', 'wphr' ); ?> : <?php echo isset( $termination_data['eligible_for_rehire'] ) ? wphr_hr_get_terminate_rehire_options( $termination_data['eligible_for_rehire'] ) : ''; ?></p>

                <?php if ( current_user_can( 'wphr_edit_employee', $employee->id ) ) : ?>
                    <a class="button button-secondary wphr-hide-print" id="wphr-employee-terminate" href="#" data-id="<?php echo $employee->id; ?>" data-template="wphr-employment-terminate" data-data='<?php echo json_encode( $termination_data ); ?>' data-title="<?php esc_attr_e( 'Update Termination', 'wphr' ); ?>" data-button="<?php esc_attr_e( 'Change Termination', 'wphr' ); ?>"><?php _e( 'Change Termination', 'wphr' ); ?></a>
                <?php endif; ?>
            </div>
        </div><!-- .postbox -->

    <?php endif; ?>

<?php endif; ?>

<?php do_action( 'wphr-hr-employee-single-bottom', $employee ); ?>
