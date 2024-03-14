<?php
/**
 * @var mixed $data Custom data for the template.
 */
$output = '<div class="qem_registration_closed">'. $data->message.'</div>';
$data->template_loader->set_output( $output );
