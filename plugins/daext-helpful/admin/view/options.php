<?php

if ( ! current_user_can( 'manage_options' ) ) {
	wp_die( esc_attr__( 'You do not have sufficient capabilities to access this page.', 'daext-helpful' ) );
}

//Sanitization -------------------------------------------------------------------------------------------------
$data['settings_updated'] = isset( $_GET['settings-updated'] ) ? sanitize_key( $_GET['settings-updated'], 10 ) : null;
$data['active_tab']       = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'content_options';

?>

<div class="wrap">

    <h2><?php esc_html_e( 'Helpful - Options', 'daext-helpful' ); ?></h2>

	<?php

	//settings errors
	if ( ! is_null( $data['settings_updated'] ) and $data['settings_updated'] == 'true' ) {

		if ( $this->write_custom_css() === false ) {
			?>
            <div id="setting-error-settings_updated" class="error settings-error notice is-dismissible below-h2">
                <p><strong><?php esc_html_e( "The plugin can't write files in the upload directory.",
							'daext-helpful' ); ?></strong></p>
                <button type="button" class="notice-dismiss"><span
                            class="screen-reader-text"><?php esc_html_e( 'Dismiss this notice.',
							'daext-helpful' ); ?></span></button>
            </div>
			<?php
		}

		//Settings errors
		settings_errors();

	}

	?>

    <div id="daext-options-wrapper">

        <div class="nav-tab-wrapper">
            <a href="?page=daexthefu-options&tab=content_options"
               class="nav-tab <?php echo $data['active_tab'] == 'content_options' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'Content',
					'daext-helpful' ); ?></a>
            <a href="?page=daexthefu-options&tab=fonts_options"
               class="nav-tab <?php echo $data['active_tab'] == 'fonts_options' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'Fonts',
					'daext-helpful' ); ?></a>
            <a href="?page=daexthefu-options&tab=colors_options"
               class="nav-tab <?php echo $data['active_tab'] == 'colors_options' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'Colors',
					'daext-helpful' ); ?></a>
            <a href="?page=daexthefu-options&tab=spacing_options"
               class="nav-tab <?php echo $data['active_tab'] == 'spacing_options' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'Spacing',
					'daext-helpful' ); ?></a>
            <a href="?page=daexthefu-options&tab=analysis_options"
               class="nav-tab <?php echo $data['active_tab'] == 'analysis_options' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'Analysis',
					'daext-helpful' ); ?></a>
            <a href="?page=daexthefu-options&tab=capabilities_options"
               class="nav-tab <?php echo $data['active_tab'] == 'capabilities_options' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'Capabilities',
					'daext-helpful' ); ?></a>
            <a href="?page=daexthefu-options&tab=advanced_options"
               class="nav-tab <?php echo $data['active_tab'] == 'advanced_options' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'Advanced',
					'daext-helpful' ); ?></a>
        </div>

        <form method='post' action='options.php' autocomplete="off">

			<?php

			if ( $data['active_tab'] == 'content_options' ) {

				settings_fields( $this->shared->get( 'slug' ) . '_content_options' );
				do_settings_sections( $this->shared->get( 'slug' ) . '_content_options' );

			}

			if ( $data['active_tab'] == 'fonts_options' ) {

				settings_fields( $this->shared->get( 'slug' ) . '_fonts_options' );
				do_settings_sections( $this->shared->get( 'slug' ) . '_fonts_options' );

			}

			if ( $data['active_tab'] == 'colors_options' ) {

				settings_fields( $this->shared->get( 'slug' ) . '_colors_options' );
				do_settings_sections( $this->shared->get( 'slug' ) . '_colors_options' );

			}

			if ( $data['active_tab'] == 'spacing_options' ) {

				settings_fields( $this->shared->get( 'slug' ) . '_spacing_options' );
				do_settings_sections( $this->shared->get( 'slug' ) . '_spacing_options' );

			}

			if ( $data['active_tab'] == 'analysis_options' ) {

				settings_fields( $this->shared->get( 'slug' ) . '_analysis_options' );
				do_settings_sections( $this->shared->get( 'slug' ) . '_analysis_options' );

			}

			if ( $data['active_tab'] == 'capabilities_options' ) {

				settings_fields( $this->shared->get( 'slug' ) . '_capabilities_options' );
				do_settings_sections( $this->shared->get( 'slug' ) . '_capabilities_options' );

			}

			if ( $data['active_tab'] == 'advanced_options' ) {

				settings_fields( $this->shared->get( 'slug' ) . '_advanced_options' );
				do_settings_sections( $this->shared->get( 'slug' ) . '_advanced_options' );

			}

			?>

            <div class="daext-options-action">
                <input type="submit" name="submit" id="submit" class="button"
                       value="<?php esc_attr_e( 'Save Changes', 'daext-helpful' ); ?>">
            </div>

        </form>

    </div>

</div>