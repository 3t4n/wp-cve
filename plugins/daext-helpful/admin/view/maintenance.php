<?php

if ( ! current_user_can( get_option( $this->shared->get( 'slug' ) . '_maintenance_menu_capability' ), ) ) {
	wp_die( esc_attr__( 'You do not have sufficient permissions to access this page.', 'daext-helpful' ) );
}

//Initialize variables -------------------------------------------------------------------------------------------------
$dismissible_notice_a = [];

?>

<!-- process data -->

<?php

if ( isset( $_POST['form_submitted'] ) ) {

	//Sanitization -----------------------------------------------------------------------------------------------------
	$task = intval( $_POST['task'], 10 );

	switch ( $task ) {

		//Delete Data
		case 0:

			global $wpdb;
			$table_name            = $wpdb->prefix . $this->shared->get( 'slug' ) . "_feedback";
			$sql                   = "DELETE FROM $table_name";
			$query_result_feedback = $wpdb->query( $sql );

			$table_name           = $wpdb->prefix . $this->shared->get( 'slug' ) . "_archive";
			$sql                  = "DELETE FROM $table_name";
			$query_result_archive = $wpdb->query( $sql );

			if ( $query_result_feedback !== false ) {

				if ( $query_result_feedback > 0 ) {

					$dismissible_notice_a[] = [
						'message' => intval( $query_result_feedback,
								10 ) . ' ' . __( 'records have been successfully deleted.', 'daext-helpful' ),
						'class'   => 'updated'
					];

				} else {

					$dismissible_notice_a[] = [
						'message' => __( 'There are no feedback data.', 'daext-helpful' ),
						'class'   => 'error'
					];

				}

			}

			break;

		//Reset Options
		case 1:

			//Set the default values of the options
			$this->shared->reset_plugin_options();

			//Regenerate the plugin public CSS
			if ( $this->write_custom_css() === false ) {

				$dismissible_notice_a[] = [
					'message' => __( "The plugin can't write files in the upload directory.", 'daext-helpful' ),
					'class'   => 'error'
				];

			}

			$dismissible_notice_a[] = [
				'message' => __( "The plugin options have been successfully set to their default values.",
					'daext-helpful' ),
				'class'   => 'updated'
			];

			break;

	}

}

?>

<!-- output -->

<div class="wrap">

    <div id="daext-header-wrapper" class="daext-clearfix">

        <h2><?php esc_html_e( 'Helpful - Maintenance', 'daext-helpful' ); ?></h2>

    </div>

    <div id="daext-menu-wrapper">

		<?php $this->dismissible_notice( $dismissible_notice_a ); ?>

        <!-- table -->

        <div>

            <form id="form-maintenance" method="POST"
                  action="admin.php?page=<?php echo esc_attr( $this->shared->get( 'slug' ) ); ?>-maintenance"
                  autocomplete="off">

                <input type="hidden" value="1" name="form_submitted">

                <div class="daext-form-container">

                    <div class="daext-form-title"><?php esc_html_e( 'Maintenance', 'daext-helpful' ); ?></div>

                    <table class="daext-form daext-form-table">

                        <!-- Task -->
                        <tr>
                            <th scope="row"><?php esc_html_e( 'Task', 'daext-helpful' ); ?></th>
                            <td>
                                <select id="task" name="task" class="daext-display-none">
                                    <option value="0" selected="selected"><?php esc_html_e( 'Delete Feedback Data',
											'daext-helpful' ); ?></option>
                                    <option value="1"><?php esc_html_e( 'Reset Plugin Options',
											'daext-helpful' ); ?></option>
                                </select>
                                <div class="help-icon"
                                     title='<?php esc_attr_e( 'The task that should be performed.',
									     'daext-helpful' ); ?>'></div>
                            </td>
                        </tr>

                    </table>

                    <!-- submit button -->
                    <div class="daext-form-action">
                        <input id="execute-task" class="button" type="submit"
                               value="<?php esc_attr_e( 'Execute Task', 'daext-helpful' ); ?>">
                    </div>

                </div>

            </form>

        </div>

    </div>

</div>

<!-- Dialog Confirm -->
<div id="dialog-confirm" title="<?php esc_attr_e( 'Execute the task?', 'daext-helpful' ); ?>"
     class="daext-display-none">
    <p><?php esc_html_e( 'Do you really want to proceed?', 'daext-helpful' ); ?></p>
</div>