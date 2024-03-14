<div class="wrap wphr-hr-employees" id="wp-wphr">

    <h2>
        <?php
        _e( 'HR Managers', 'wphr' );
        ?>
    </h2>
	<?php
	if( isset( $_GET['status'] ) && sanitize_text_field($_GET['status']) ){
		echo sprintf( '<div class="notice notice-success"><p>%s</p></div>', __('Updated user capabilities successfully.', 'wphr') );
	}
	?>
	<div class="notice notice-info">
		<p>Here you can select which HR Managers receive email notifications of employee leave requests and enable them to accept or reject the leave request.</p>
		<p>You can assign ‘HR Manager’ status to an employee in the Employee Profile on the ‘Permission’ tab or from the WordPress User Profile screen.</p>
	</div>
    <div class="list-table-wrap wphr-hr-managers-wrap">
        <div class="list-table-inner wphr-hr-employees-wrap-inner">

            <form method="get">
                <input type="hidden" name="page" value="wphr-hr-managers">
                <?php
                $employee_table = new \WPHR\HR_MANAGER\HRM\HR_Manager_List_Table();
                $employee_table->prepare_items();
                $employee_table->search_box( __( 'Search Employee', 'wphr' ), 'wphr-employee-search' );
				?>
			</form>
			<form method="post">
				<?php
                if ( current_user_can( wphr_hr_get_manager_role() ) ) {
                    $employee_table->views();
                }

                $employee_table->display();
				wp_nonce_field( 'wphr_nonce', 'wphr_hr_manager' ); ?>
				<input class="button-primary" type="submit" name="wphr_manager_update"  value="<?php echo __('Save', 'wphr'); ?>">
			</form>

        </div><!-- .list-table-inner -->
    </div><!-- .list-table-wrap -->
</div>
