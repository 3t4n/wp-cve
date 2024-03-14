<div class="wrap wphr-hr-employees" id="wp-hr">

    <div class="list-table-wrap wphr-hr-employees-wrap">
        <div class="list-table-inner wphr-hr-employees-wrap-inner">

            <form method="get">
                <input type="hidden" name="page" value="wphr-hr-employee">
                <?php
                $employee_table = new \WPHR\HR_MANAGER\HR\Frontend\Frontend_Employee_List_Table();
                $employee_table->prepare_items();
                $employee_table->search_box( __( 'Search Employee', 'wphr' ), 'wphr-employee-search' );

                $employee_table->display();
                ?>
            </form>

        </div><!-- .list-table-inner -->
    </div><!-- .list-table-wrap -->

</div>
