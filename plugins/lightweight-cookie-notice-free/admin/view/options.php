<?php

if ( ! current_user_can( 'manage_options' ) ) {
	wp_die( esc_html__( 'You do not have sufficient capabilities to access this page.', 'daextlwcnf' ) );
}

?>

<div class="wrap">

    <h2><?php esc_attr_e( 'Lightweight Cookie Notice - Options', 'daextlwcnf' ); ?></h2>

	<?php

	//settings errors
	if ( isset( $_GET['settings-updated'] ) and $_GET['settings-updated'] == 'true' ) {

		//Download GeoLite2
		require_once( $this->shared->get( 'dir' ) . '/admin/inc/class-daextlwcnf-maxmind-integration.php' );
		$maxmind_integration = new Daextlwcnf_MaxMind_Integration( $this->shared );
		$maxmind_integration->update_maxmind_geolite2();

		//Settings errors
		settings_errors();

	}

	?>

    <div id="daext-options-wrapper">

		<?php
		//get current tab value
		$active_tab = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'general_options';
		?>

        <div class="nav-tab-wrapper">
            <a href="?page=daextlwcnf-options&tab=general_options"
               class="nav-tab <?php echo $active_tab == 'general_options' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'General',
					'daextlwcnf' ); ?></a>
            <a href="?page=daextlwcnf-options&tab=cookie_notice_options"
               class="nav-tab <?php echo $active_tab == 'cookie_notice_options' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'Cookie Notice',
					'daextlwcnf' ); ?></a>
            <a href="?page=daextlwcnf-options&tab=cookie_settings_options"
               class="nav-tab <?php echo $active_tab == 'cookie_settings_options' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'Cookie Settings',
					'daextlwcnf' ); ?></a>
            <a href="?page=daextlwcnf-options&tab=revisit_consent_button_options"
               class="nav-tab <?php echo $active_tab == 'revisit_consent_button_options' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'Revisit Consent',
                    'daextlwcnf' ); ?></a>
            <a href="?page=daextlwcnf-options&tab=geolocation_options"
               class="nav-tab <?php echo $active_tab == 'geolocation_options' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'Geolocation',
					'daextlwcnf' ); ?></a>
            <a href="?page=daextlwcnf-options&tab=advanced_options"
               class="nav-tab <?php echo $active_tab == 'advanced_options' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'Advanced',
					'daextlwcnf' ); ?></a>
        </div>

        <form method="post" action="options.php" autocomplete="off">

			<?php

			if ( $active_tab == 'general_options' ) {

				settings_fields( $this->shared->get( 'slug' ) . '_general_options' );
				do_settings_sections( $this->shared->get( 'slug' ) . '_general_options' );

			}

			if ( $active_tab == 'cookie_notice_options' ) {

				settings_fields( $this->shared->get( 'slug' ) . '_cookie_notice_options' );
				do_settings_sections( $this->shared->get( 'slug' ) . '_cookie_notice_options' );

			}

			if ( $active_tab == 'cookie_settings_options' ) {

				settings_fields( $this->shared->get( 'slug' ) . '_cookie_settings_options' );
				do_settings_sections( $this->shared->get( 'slug' ) . '_cookie_settings_options' );

			}

            if ( $active_tab == 'revisit_consent_button_options' ) {

                settings_fields( $this->shared->get( 'slug' ) . '_revisit_consent_button_options' );
                do_settings_sections( $this->shared->get( 'slug' ) . '_revisit_consent_button_options' );

            }

			if ( $active_tab == 'geolocation_options' ) {

				settings_fields( $this->shared->get( 'slug' ) . '_geolocation_options' );
				do_settings_sections( $this->shared->get( 'slug' ) . '_geolocation_options' );

			}

			if ( $active_tab == 'advanced_options' ) {

				settings_fields( $this->shared->get( 'slug' ) . '_advanced_options' );
				do_settings_sections( $this->shared->get( 'slug' ) . '_advanced_options' );

			}

			?>

            <div class="daext-options-action">
                <input type="submit" name="submit" id="submit" class="button"
                       value="<?php esc_attr_e( 'Save Changes', 'daextlwcnf' ); ?>">
            </div>

        </form>

    </div>

