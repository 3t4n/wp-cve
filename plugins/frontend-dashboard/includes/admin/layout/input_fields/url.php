<?php
/**
 * URL.
 *
 * @package Frontend Dashboard.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Input Fields URL.
 *
 * @param  array  $row  Row.
 * @param  string $action  Action.
 * @param  array  $menu_options  Menu Options.
 */
function fed_admin_input_fields_url( $row, $action, $menu_options ) {
	?>
    <div class="row fed_input_type_container fed_input_url_container hide">
        <form method="post"
              class="fed_admin_menu fed_ajax"
              action="<?php echo esc_url( admin_url( 'admin-ajax.php?action=fed_admin_setting_up_form' ) ) ?>">

			<?php fed_wp_nonce_field( 'fed_nonce', 'fed_nonce' ) ?>

			<?php echo fed_loader(); ?>

            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <b><?php _e( 'URL', 'frontend-dashboard' ) ?></b>
                        </h3>
                    </div>
                    <div class="panel-body">
                        <div class="fed_input_text">
							<?php fed_get_admin_up_label_input_order( $row ); ?>
                            <div class="row">
								<?php fed_get_admin_up_input_meta( $row ) ?>

                                <div class="form-group col-md-6">
									<?php fed_get_placeholder_field( $row ) ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
									<?php fed_get_class_field( $row ) ?>
                                </div>

                                <div class="form-group col-md-6">
									<?php fed_get_id_field( $row ) ?>
                                </div>
                            </div>
							<?php
							fed_get_admin_up_display_permission( $row, $action );

							fed_get_admin_up_role_based( $row, $action, $menu_options );

							fed_get_input_type_and_submit_btn( 'url', $action );
							?>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
	<?php
}
