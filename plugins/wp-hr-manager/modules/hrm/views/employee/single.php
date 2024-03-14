<div class="wrap wphr wphr-hr-employees wphr-employee-single">

    <?php
    if ( is_admin() ) {
        ?>
        <h2 class="wphr-hide-print"><?php empty( $is_my_profile_page ) ? _e( 'Employee', 'wphr' ) : _e( 'My Profile', 'wphr' );

        if ( empty( $is_my_profile_page ) && current_user_can( 'wphr_create_employee' ) ) {
            ?>
            <a href="#" id="wphr-employee-new" class="add-new-h2 wphr-hide-print"><?php _e( 'Add New', 'wphr' ); ?></a>
            <?php
        }
    }
    ?>
    </h2>
    <div class="wphr-single-container wphr-hr-employees-wrap" id="wphr-single-container-wrap">
        <div class="wphr-area-left full-width wphr-hr-employees-wrap-inner">
            <div id="wphr-area-left-inner">

                <script type="text/javascript">
                    window.wpHrCurrentEmployee = <?php echo json_encode( $employee->to_array() ); ?>
                </script>

                <div class="wphr-profile-top">
                    <div class="wphr-avatar">
                        <?php echo $employee->get_avatar( 150 ); ?>

                        <?php if ( $employee->get_status() == 'Terminated' ): ?>
                            <span class="inactive"></span>
                        <?php endif ?>
                    </div>

                    <div class="wphr-user-info">
                        <h3><span class="title"><?php echo $employee->get_full_name(); ?></span></h3>

                        <ul class="lead-info">
                            <li>
                                <?php echo $employee->get_job_title(); ?> - <?php echo $employee->get_department_title(); ?>
                            </li>

                            <li>
                                <a href="mailto:<?php echo $employee->user_email; ?>"><?php echo $employee->user_email; ?></a>
                            </li>

                            <?php
                            $phones = array();
                            if ( $work_phone = $employee->get_phone( 'work' ) ) {
                                $phones[] = $work_phone;
                            }
                            if ( $mobile_phone = $employee->get_phone( 'mobile' ) ) {
                                $phones[] = $mobile_phone;
                            }

                            if ( $phones ) { ?>
                                <li>
                                    <ul class="wphr-list list-inline">
                                        <?php foreach( $phones as $phone ) { ?>
                                            <li><a href="tel:<?php echo $phone; ?>"><span class="dashicons dashicons-smartphone"></span></a><?php echo $phone; ?></li>
                                        <?php } ?>
                                    </ul>
                                </li>
                            <?php } ?>
                        </ul>
                    </div><!-- .wphr-user-info -->

                    <div class="wphr-area-right wphr-hide-print">
                        <div class="postbox leads-actions">
                            <h3 class="hndle"><span><?php _e( 'Actions', 'wphr' ); ?></span></h3>
                            <div class="inside">
							    <?php if ( absint( $employee->id ) === get_current_user_id() && wphr_hr_get_assign_policy_from_entitlement( $employee->id ) ): ?>
							        <div class="wphr-hr-new-leave-request-wrap">
							            <a href="#" class="button button-primary" id="wphr-hr-new-leave-req"><?php _e( 'Take a Leave', 'wphr' ); ?></a>
							        </div>
							    <?php endif; ?>
                                <?php
                                if ( current_user_can( 'wphr_edit_employee', $employee->id ) ) {
                                    ?>
                                    <span class="edit"><a class="button button-primary" data-id="<?php echo $employee->id; ?>" data-single="true" href="#"><?php _e( 'Edit', 'wphr' ); ?></a></span>
                                    <?php
                                }
                                ?>

                                <?php if ( $employee->get_status() != 'Terminated' && current_user_can( 'wphr_create_employee' ) ): ?>
                                    <a class="button" href="#" id="wphr-employee-terminate" data-id="<?php echo $employee->id; ?>" data-template="wphr-employment-terminate" data-title="<?php _e( 'Terminate Employee', 'wphr' ); ?>"><?php _e( 'Terminate', 'wphr' ); ?></a>
                                <?php endif; ?>

                                <?php if ( ( isset( $_GET['tab'] ) && sanitize_text_field($_GET['tab']) == 'general' ) || !isset( $_GET['tab'] )  ): ?>
                                    <a class="button" id="wphr-employee-print" href="#"><?php _e( 'Print', 'wphr' ); ?></a>
                                <?php endif ?>
                            </div>
                        </div><!-- .postbox -->
                    </div><!-- .leads-right -->

                    <?php do_action( 'wphr_hr_employee_single_after_info', $employee ); ?>

                </div><!-- .wphr-profile-top -->

                <?php
                $active_tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'general';
                $tabs       = apply_filters( 'wphr_hr_employee_single_tabs', array(
                    'general' => array(
                        'title'    => __( 'General Info', 'wphr' ),
                        'callback' => 'wphr_hr_employee_single_tab_general'
                    ),
                    'job' => array(
                        'title'    => __( 'Job', 'wphr' ),
                        'callback' => 'wphr_hr_employee_single_tab_job'
                    ),
                    'leave' => array(
                        'title'    => __( 'Leave', 'wphr' ),
                        'callback' => 'wphr_hr_employee_single_tab_leave'
                    ),
                    'notes' => array(
                        'title'    => __( 'Notes', 'wphr' ),
                        'callback' => 'wphr_hr_employee_single_tab_notes'
                    ),
                    'performance' => array(
                        'title'    => __( 'Performance', 'wphr' ),
                        'callback' => 'wphr_hr_employee_single_tab_performance'
                    ),
                    'permission' => array(
                        'title'    => __( 'Permission', 'wphr' ),
                        'callback' => 'wphr_hr_employee_single_tab_permission'
                    ),
                ), $employee );

                if ( ! current_user_can( 'wphr_create_review' ) && isset( $tabs['permission'] ) && isset( $tabs['performance'] ) && isset( $tabs['notes'] ) ) {
                    unset( $tabs['permission'] );
                    unset( $tabs['performance'] );
                    unset( $tabs['notes'] );
                }

                if ( ! current_user_can( 'wphr_edit_employee', $employee->id ) ) {
                    unset( $tabs['leave'] );
                    unset( $tabs['job'] );
                }

                if ( absint( $employee->id ) === get_current_user_id() ) {
                    unset( $tabs['permission'] );
                }
                ?>

                <h2 class="nav-tab-wrapper wphr-hide-print" style="margin-bottom: 15px;">
                    <?php foreach ($tabs as $key => $tab) {
                        $active_class = ( $key == $active_tab ) ? ' nav-tab-active' : '';
                        ?>
                        <a href="<?php echo wphr_hr_employee_tab_url( $key, $employee->id ); ?>" class="nav-tab<?php echo $active_class; ?>"><?php echo $tab['title'] ?></a>
                    <?php } ?>
                </h2>

                <?php
                // call the tab callback function
                if ( array_key_exists( $active_tab, $tabs ) && is_callable( $tabs[$active_tab]['callback'] ) ) {
                    call_user_func_array( $tabs[$active_tab]['callback'], array( $employee ) );
                }
                ?>

                <?php do_action( 'wphr_hr_employee_single_bottom', $employee ); ?>

            </div><!-- #wphr-area-left-inner -->
        </div><!-- .leads-left -->
    </div><!-- .wphr-leads-wrap -->
</div>
