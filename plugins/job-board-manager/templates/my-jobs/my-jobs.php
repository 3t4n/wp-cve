<?php
if ( ! defined('ABSPATH')) exit;  // if direct access


do_action('job_bm_my_jobs_before');
?>
<div class="job-bm-my-jobs">
    <?php
    do_action('job_bm_my_jobs');
    ?>
</div>
<?php
do_action('job_bm_my_jobs_after');