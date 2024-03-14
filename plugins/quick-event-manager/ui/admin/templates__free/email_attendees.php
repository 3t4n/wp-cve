<?php
/**
 * @var mixed $data Custom data for the template.
 */
?>
    <div class="wrap">
    <h2><?php echo esc_html($data->settings_title ); ?></h2>
    <h3><?php esc_html_e( 'Email selected attendees directly from the dashboard.  Available in the premium version', 'quick-event-manager' ); ?></h3>
    </div>
<?php
$data->template_loader->get_template_part( 'upgrade_cta' );