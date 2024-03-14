<?php
header('Content-Type: application/json');
echo json_encode(get_option('woocommerce_mypos_virtual_settings', array()));
