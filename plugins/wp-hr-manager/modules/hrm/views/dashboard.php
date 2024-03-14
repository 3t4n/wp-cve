<div class="wrap wphr hrm-dashboard">
    <h2><?php _e( 'HR Management', 'wphr' ); ?></h2>

    <div class="wphr-single-container">

        <div class="wphr-area-left">

            <?php if ( current_user_can('wphr_create_employee') ): ?>

                <?php
                $employees    = wphr_hr_get_employees( [ 'number'=> -1, 'status' => 'active' ] );
                $departments  = wphr_hr_get_departments( [ 'number' => -1 ] );
                $designations = wphr_hr_get_designations( [ 'number'=> -1 ] );
                ?>

                <div class="badge-container">
                    <div class="badge-wrap badge-green">
                        <div class="badge-inner">
                            <h3><?php echo number_format_i18n( count( $employees ), 0 ); ?></h3>
                            <p><?php _e( 'Employees', 'wphr' ); ?></p>
                        </div>

                        <div class="badge-footer wp-ui-highlight">
                            <a href="<?php echo wphr_hr_employee_list_url(); ?>"><?php _e( 'View Employees', 'wphr' ); ?></a>
                        </div>
                    </div><!-- .badge-wrap -->

                    <div class="badge-wrap badge-red">
                        <div class="badge-inner">
                            <h3><?php echo number_format_i18n( count( $departments ), 0 ); ?></h3>
                            <p><?php _e( 'Departments', 'wphr' ); ?></p>
                        </div>
                        <?php
                        if ( is_admin() ) {
                            ?>
                            <div class="badge-footer wp-ui-highlight">
                                <a href="<?php echo admin_url( 'admin.php?page=wphr-hr-depts' ); ?>"><?php _e( 'View Departments', 'wphr' ); ?></a>
                            </div>
                            <?php
                        }
                        ?>

                    </div><!-- .badge-wrap -->

                    <div class="badge-wrap badge-aqua">
                        <div class="badge-inner">
                            <h3><?php echo number_format_i18n( count( $designations ), 0 ); ?></h3>
                            <p><?php _e( 'Role', 'wphr' ); ?></p>
                        </div>
                        <?php
                        if ( is_admin() ) {
                            ?>
                            <div class="badge-footer wp-ui-highlight">
                                <a href="<?php echo admin_url( 'admin.php?page=wphr-hr-designation' ); ?>"><?php _e( 'View Role', 'wphr' ); ?></a>
                            </div>
                            <?php
                        }
                        ?>
                    </div><!-- .badge-wrap -->
                </div><!-- .badge-container -->

            <?php endif ?>

            <?php do_action( 'wphr_hr_dashboard_widgets_left' ); ?>

        </div><!-- .wphr-area-left -->

        <div class="wphr-area-right">

            <?php do_action( 'wphr_hr_dashboard_widgets_right' ); ?>

        </div>
        <div class="clearfix"></div>
        <div class="wphr-area-full">
            <?php do_action( 'wphr_hr_dashboard_widgets_full' ); ?>
        </div>
    </div>
</div>
