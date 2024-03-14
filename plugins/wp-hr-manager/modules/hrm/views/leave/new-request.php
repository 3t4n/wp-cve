<?php
$time_slot = get_office_timing();
$employee_details = wp_get_current_user();
$limited_users = array();
if( $employee_details->ID ){
	$employee_id = $employee_details->ID;
	if( !in_array( wphr_hr_get_manager_role(), $employee_details->roles ) && in_array( wphr_hr_get_employee_role(), $employee_details->roles ) ){
		$limited_users = get_users_under_line_manager($employee_id);
	}
}
?>
<div class="wrap wphr wphr-hr-leave-request-new wphr-hr-leave-reqs-wrap">
    <div class="postbox">
        <h3 class="hndle"><?php _e( 'New Leave Request', 'wphr' ); ?></h3>
        <div class="inside">
            <?php if ( isset( $_GET['msg'] ) ) {

                if ( sanitize_text_field($_GET['msg']) == 'submitted' ) {
                    wphr_html_show_notice( __( 'Leave request has been submitted successfully.', 'wphr' ) );
                } elseif ( sanitize_text_field($_GET['msg']) == 'error' ) {
                    wphr_html_show_notice( __( 'Something went wrong.', 'wphr' ), 'error' );
                }
                elseif ( sanitize_text_field($_GET['msg']) == 'leave_exist' ) {
                    wphr_html_show_notice( __( 'Existing Leave Record found within selected range!', 'wphr' ), 'error' );
                }

            } ?>

            <form action="" method="post">

                <?php if ( current_user_can( 'wphr_leave_create_request' ) ) { ?>
                    <div class="row">
                        <?php wphr_html_form_input( array(
                            'label'    => __( 'Employee', 'wphr' ),
                            'name'     => 'employee_id',
                            'id'       => 'wphr-hr-leave-req-employee-id',
                            'value'    => '',
                            'required' => true,
                            'type'     => 'select',
                            'options'  => wphr_hr_get_employees_dropdown_raw(null, $limited_users)
                        ) ); ?>
                    </div>
                <?php } ?>

                <div class="row wphr-hide wphr-hr-leave-type-wrapper"></div>

                <div class="row two-col">
                    <div class="cols">
                        <?php wphr_html_form_input( array(
                            'label'    => __( 'From', 'wphr' ),
                            'name'     => 'leave_from',
                            'id'       => 'wphr-hr-leave-req-from-date',
                            'value'    => '',
                            'required' => true,
                            'class'    => 'wphr-leave-date-field',
                            'custom_attr' => [ 'disabled' => 'disabled' ]
                        ) ); ?>
                    </div>

                    <div class="cols last">
                        <?php wphr_html_form_input( array(
                            'label'    => __( 'To', 'wphr' ),
                            'name'     => 'leave_to',
                            'id'       => 'wphr-hr-leave-req-to-date',
                            'value'    => '',
                            'required' => true,
                            'class'    => 'wphr-leave-date-field',
                            'custom_attr' => [ 'disabled' => 'disabled' ]
                        ) ); ?>
                    </div>
                </div>

                <div class="row" style="display: none;">
                    <?php wphr_html_form_input( array(
                        'label'    => __( 'Hourly Leave', 'wphr' ),
                        'name'     => 'hourly_req',
                        'id'       => 'wphr-hr-leave-houly-req',
                        'value'    => '',
                        'type'     => 'checkbox',
                    ) ); ?>
                </div>
                
                <div class="row" style="display: none;">
                    <?php wphr_html_form_input( array(
                        'label'    => __( 'From (Time)', 'wphr' ),
                        'name'     => 'from_time',
                        'id'       => 'wphr-hr-leave-req-from-time',
                        'value'    => '',
                        'class'    => 'wphr-leave-time-slot',
                        'type'     => 'select',
                        'options'  => array( '' => __( '- Select -', 'wphr' ) ) + $time_slot
                    ) ); ?>
                </div>

                <div class="row" style="display: none;">
                    <?php wphr_html_form_input( array(
                        'label'    => __( 'To (Time)', 'wphr' ),
                        'name'     => 'to_time',
                        'id'       => 'wphr-hr-leave-req-to-time',
                        'value'    => '',
                        'class'    => 'wphr-leave-time-slot',
                        'type'     => 'select',
                        'options'  => array( '' => __( '- Select -', 'wphr' ) ) + $time_slot
                    ) ); ?>
                </div>

                <div class="row wphr-hr-leave-req-show-days show-days"></div>

                <div class="row">
                    <?php wphr_html_form_input( array(
                        'label'       => __( 'Reason', 'wphr' ),
                        'name'        => 'leave_reason',
                        'type'        => 'textarea',
                        'custom_attr' => array( 'cols' => 30, 'rows' => 3, 'disabled' => 'disabled' )
                    ) ); ?>
                </div>
                <input type="hidden" name="wphr-action" value="hr-leave-req-new">
                <?php wp_nonce_field( 'wphr-leave-req-new' ); ?>
                <?php submit_button( __( 'Submit Request', 'wphr' ), 'primary', 'submit', true, array( 'disabled' => 'disabled' )  ); ?>

            </form>
        </div><!-- .inside-->
    </div><!-- .postbox-->
</div><!-- .wrap -->

<?php wphr_get_js_template( WPHR_HRM_JS_TMPL . '/leave-days.php', 'wphr-leave-days' ); ?>
