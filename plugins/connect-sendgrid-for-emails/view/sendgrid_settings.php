<div class="wrap">
  <h2>Connect SendGrid for Emails</h2>

  <?php
    $sg_tabs = array( 'general' => 'General', 'marketing' => 'Subscription Widget' );

    // If network settings display settings for subsites
    if ( is_multisite() and is_main_site() ) {
      $sg_tabs['multisite'] = 'Multisite Settings';
    }

    $active_tab = current( array_keys( $sg_tabs ) );
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
    if ( isset( $_GET['tab'] ) ) {
	   // phpcs:ignore WordPress.Security.NonceVerification.Recommended
      $selected_tab = sanitize_text_field($_GET['tab']);
      if ( array_key_exists( $selected_tab, $sg_tabs ) ) {
        $active_tab = $selected_tab;
      }
    }
  ?>

  <?php if ( isset( $status ) and ( 'updated' == $status or 'error' == $status or 'notice notice-warning' == $status ) ): ?>
    <div id="message" class="<?php echo esc_attr($status) ?>">
      <p>
        <strong><?php echo esc_html($message) ?></strong>
      </p>
    </div>
  <?php endif; ?>

   <?php if ( isset( $warning_status ) and isset( $warning_message ) ): ?>
    <?php if ( ! isset( $warning_exclude_tab ) or $warning_exclude_tab != $active_tab ): ?>
      <div id="message" class="<?php echo esc_attr($warning_status) ?>">
        <p>
          <strong><?php echo esc_html($warning_message) ?></strong>
        </p>
      </div>
    <?php endif; ?>
  <?php endif; ?>

  <?php
    require_once plugin_dir_path( __FILE__ ) . 'sendgrid_settings_nav.php';
    require_once plugin_dir_path( __FILE__ ) . 'sendgrid_settings_general.php';
    require_once plugin_dir_path( __FILE__ ) . 'sendgrid_settings_test_email.php';
    require_once plugin_dir_path( __FILE__ ) . 'sendgrid_settings_nlvx.php';
    require_once plugin_dir_path( __FILE__ ) . 'sendgrid_settings_test_contact.php';
    require_once plugin_dir_path( __FILE__ ) . 'sendgrid_settings_multisite.php';
  ?>
</div>
