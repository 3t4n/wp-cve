<?php
if ( ! defined('ABSPATH')) exit;  // if direct access

?>



<div class="job-bm-job-submit">

    <div id="paypal-button-container"></div>

    <?php
    if(!empty($_POST)){
        do_action('job_bm_job_submit_data', $_POST);
    }
    ?>
    <?php do_action('job_bm_job_submit_before'); ?>
    <form enctype="multipart/form-data" method="post" action="<?php echo str_replace( '%7E', '~', esc_url_raw($_SERVER['REQUEST_URI'])); ?>">
        <?php
		do_action('job_bm_job_submit_form');
		?>
    </form>
	<?php do_action('job_bm_job_submit_after'); ?>
</div>