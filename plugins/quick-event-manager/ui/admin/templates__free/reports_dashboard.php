<?php
/**
 * @var mixed $data Custom data for the template.
 */
$output = '
    <div class="wrap">
        <h2>' . esc_html( $data->settings_title ) . '</h2>
        <h3>' . esc_html__( 'These reports are available in the premium version', 'quick-event-manager' ) . '</h3>
        <div class="qem-options">
            <h2>' . esc_html__( 'Registration Report', 'quick-event-manager' ) . '</h2>
            <p>' . esc_html__( 'Displays all events and registrations.', 'quick-event-manager' ) . '</p>
            <p>' . esc_html__( 'Shortcode', 'quick-event-manager' ) . ': [qemreport]</p>
        </div>
        <div class="qem-options">
            <h2>' . esc_html__( 'Registrations by Name', 'quick-event-manager' ) . '</h2>
            <p>' . esc_html__( 'Displays the registrations sorted by name.', 'quick-event-manager' ) . '</p>
            <p>' . esc_html__( 'Shortcode', 'quick-event-manager' ) . ': [qemnames]</p>
        </div>
        <div class="qem-options">
            <h2>' . esc_html__( 'Registrations by Email', 'quick-event-manager' ) . '</h2>
            <p>' . esc_html__( 'Displays the registrations sorted by email address.', 'quick-event-manager' ) . '</p>
            <p>' . esc_html__( 'Shortcode', 'quick-event-manager' ) . ': [qememail]</p>
        </div>
        <div class="qem-options">
            <h2>' . esc_html__( 'Not Attending', 'quick-event-manager' ) . '</h2>
            <p>' . esc_html__( 'Displays a list of everyone who is not coming.', 'quick-event-manager' ) . '</p>
            <p>' . esc_html__( 'Shortcode', 'quick-event-manager' ) . '
                : ' . esc_html__( 'None', 'quick-event-manager' ) . '</p>
        </div>
    </div>
    <div class="clearfix"></div>';

$data->template_loader->set_output( $output );
$data->template_loader->get_template_part( 'upgrade_cta' );
