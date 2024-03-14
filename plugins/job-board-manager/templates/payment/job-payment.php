<?php
if ( ! defined('ABSPATH')) exit;  // if direct access


$job_id = isset($_GET['job_id']) ? sanitize_text_field($_GET['job_id']) : '';



?>

<div class="job-bm-payment">
    <?php
    do_action('job_bm_payment');
    ?>
</div>