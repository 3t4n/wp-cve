<?php

if ( ! current_user_can( 'manage_options' ) ) {
	wp_die( esc_attr__( 'You do not have sufficient capabilities to access this page.', 'daext-interlinks-manager') );
}

//Sanitization -------------------------------------------------------------------------------------------------
$data['settings_updated'] = isset( $_GET['settings-updated'] ) ? sanitize_key( $_GET['settings-updated'], 10 ) : null;
$data['active_tab']       = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'optimization_options';

?>

<div class="wrap">

    <h2><?php esc_html_e( 'Interlinks Manager - Options', 'daext-interlinks-manager'); ?></h2>

	<?php

	//settings errors
	if ( ! is_null( $data['settings_updated'] ) ) {
		if ( $data['settings_updated'] == 'true' ) {
			settings_errors();
		}
	}

	?>

    <div id="daext-options-wrapper">

        <div class="nav-tab-wrapper">
            <a href="?page=daextinma-options&tab=optimization_options"
               class="nav-tab <?php echo $data['active_tab'] == 'optimization_options' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'Optimization', 'daext-interlinks-manager'); ?></a>
            <a href="?page=daextinma-options&tab=juice_options"
               class="nav-tab <?php echo $data['active_tab'] == 'juice_options' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'Juice', 'daext-interlinks-manager'); ?></a>
            <a href="?page=daextinma-options&tab=analysis_options"
               class="nav-tab <?php echo $data['active_tab'] == 'analysis_options' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'Analysis', 'daext-interlinks-manager'); ?></a>
            <a href="?page=daextinma-options&tab=metaboxes_options"
               class="nav-tab <?php echo $data['active_tab'] == 'metaboxes_options' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'Meta Boxes', 'daext-interlinks-manager'); ?></a>
        </div>

        <form method='post' action='options.php' autocomplete="off">

			<?php

			if ( $data['active_tab'] == 'optimization_options' ) {

				settings_fields( $this->shared->get( 'slug' ) . '_optimization_options' );
				do_settings_sections( $this->shared->get( 'slug' ) . '_optimization_options' );

			}

			if ( $data['active_tab'] == 'juice_options' ) {

				settings_fields( $this->shared->get( 'slug' ) . '_juice_options' );
				do_settings_sections( $this->shared->get( 'slug' ) . '_juice_options' );

			}

			if ( $data['active_tab'] == 'analysis_options' ) {

				settings_fields( $this->shared->get( 'slug' ) . '_analysis_options' );
				do_settings_sections( $this->shared->get( 'slug' ) . '_analysis_options' );

			}

			if ( $data['active_tab'] == 'metaboxes_options' ) {

				settings_fields( $this->shared->get( 'slug' ) . '_metaboxes_options' );
				do_settings_sections( $this->shared->get( 'slug' ) . '_metaboxes_options' );

			}

			?>

            <div class="daext-options-action">
                <input type="submit" name="submit" id="submit" class="button"
                       value="<?php esc_attr_e( 'Save Changes', 'daext-interlinks-manager'); ?>">
            </div>

        </form>

    </div>

</div>