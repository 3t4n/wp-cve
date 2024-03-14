<div class="wrap wphr-hr-leave-requests">
    <h2><?php _e( 'Leave Requests', 'wphr' ); ?> <a href="<?php echo add_query_arg( array( 'view' => 'new' ) ); ?>" class="add-new-h2"><?php _e( 'New Request', 'wphr' ); ?></a></h2>

    <div class="wphr-hr-leave-requests-inner">
        <div class="list-table-wrap">
            <div class="list-table-inner">

                <form method="get">
                    <input type="hidden" name="page" value="wphr-leave">
                    <?php
                    $requests_table = new \WPHR\HR_MANAGER\HRM\Leave_Requests_List_Table();
                    $requests_table->prepare_items();
                    $requests_table->views();

                    $requests_table->display();
                    ?>
                </form>

            </div><!-- .list-table-inner -->
        </div><!-- .list-table-wrap -->
    </div><!-- .wphr-hr-leave-requests-inner -->
</div><!-- .wrap -->
