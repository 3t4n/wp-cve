<div class="wrap wphr-hr-employees" id="wp-wphr">

    <h2>
        <?php
        _e( 'Employee', 'wphr' );

        if ( current_user_can( 'wphr_create_employee' ) ) {
            ?>
                <a href="#" id="wphr-employee-new" class="add-new-h2"><?php _e( 'Add New', 'wphr' ); ?></a>
            <?php
        }
        ?>
    </h2>

    <div class="list-table-wrap wphr-hr-employees-wrap">
        <div class="list-table-inner wphr-hr-employees-wrap-inner">

            <form method="get">
                <input type="hidden" name="page" value="wphr-hr-employee">
                <?php
                $employee_table = new \WPHR\HR_MANAGER\HRM\Employee_List_Table();
                $employee_table->prepare_items();
                $employee_table->search_box( __( 'Search Employee', 'wphr' ), 'wphr-employee-search' );

                if ( current_user_can( wphr_hr_get_manager_role() ) ) {
                    $employee_table->views();
                }

                $employee_table->display();
                ?>
            </form>

        </div><!-- .list-table-inner -->
    </div><!-- .list-table-wrap -->

</div>
