<div class="wrap wphr-hr-depts">

    <h2><?php _e( 'Departments', 'wphr' ); ?> <a href="#" id="wphr-new-dept" class="add-new-h2" data-single="1"><?php _e( 'Add New', 'wphr' ); ?></a></h2>

    <?php if ( isset( $_GET['department_delete'] ) ): ?>
        <div id="message" class="error notice is-dismissible below-h2">
            <p><?php _e( 'Some department doesn\'t delete because some employees work under those department', 'wphr' ) ?></p>
            <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
        </div>
    <?php endif ?>

    <div id="wphr-dept-table-wrap">

        <div class="list-table-inner">

            <form method="get">
                <input type="hidden" name="page" value="wphr-hr-depts">
                <?php
                $department_table = new \WPHR\HR_MANAGER\HRM\Department_List_Table();
                $department_table->prepare_items();
                $department_table->views();

                $department_table->display();
                ?>
            </form>

        </div><!-- .list-table-inner -->
    </div><!-- .list-table-wrap -->

</div>
