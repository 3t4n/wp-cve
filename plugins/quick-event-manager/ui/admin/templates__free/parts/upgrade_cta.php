<?php
/**
 * @var mixed $data Custom data for the template.
 */
$output = '
<div class="qemupgrade"><a href="' . esc_url( $data->freemius->get_upgrade_url() ) . '">
        <h3>' . esc_html__( 'Upgrade QEM from just $4.16 / month', 'quick-event-manager' ) . '<sup>*</sup></h3>
        <p>' . esc_html__( 'Upgrading gives you access the  CSV uploader, a range of registration reports and downloads, Mailchimp subscriber, Guest Event creator, and Stripe Checkout.', 'quick-event-manager' ) . ' </p>
        <p>' . esc_html__( 'Click to find out more', 'quick-event-manager' ) . '</p>
        <p>* ' . esc_html__( 'bronze plan, single site, when paid annually, excludes taxes. Higher plans may be required for all features', 'quick-event-manager_upsell' ) . '</p>
    </a>
</div>';

$data->template_loader->set_output( $output );
