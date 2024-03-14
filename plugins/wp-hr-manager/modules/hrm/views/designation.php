<div class="wrap wphr wphr-hr-designation">

    <h2><?php _e( 'Role', 'wphr' ); ?> <a href="#" id="wphr-new-designation" data-single="1" class="add-new-h2"><?php _e( 'Add New', 'wphr' ); ?></a></h2>

    <?php if ( isset( $_GET['desig_delete'] ) ): ?>
        <div id="message" class="error notice is-dismissible below-h2">
            <p><?php _e( 'Some designation doesn\'t delete because those designation assign some employees.', 'wphr' ) ?></p>
            <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
        </div>
    <?php endif ?>

    <div id="wphr-desig-table-wrap">

        <div class="list-table-inner">

            <form method="get">
                <input type="hidden" name="page" value="wphr-hr-designation">
                <?php
                $designation = new \WPHR\HR_MANAGER\HRM\Designation_List_Table();
                $designation->prepare_items();
                $designation->views();

                $designation->display();
                ?>
            </form>

        </div><!-- .list-table-inner -->
    </div><!-- .list-table-wrap -->

</div>
