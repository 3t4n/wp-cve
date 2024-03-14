<?php
if ( ! defined('ABSPATH')) exit;  // if direct access

$job_id = isset($_GET['job_id']) ? sanitize_text_field($_GET['job_id']) : '';


?>
<div class="job-bm-job-submit">
    <?php
    if(!empty($_POST)){
        do_action('job_bm_job_edit_data', $job_id, $_POST);
    }
    ?>
    <?php do_action('job_bm_job_edit_before', $job_id); ?>
    <form enctype="multipart/form-data" method="post" action="<?php echo str_replace( '%7E', '~', esc_url_raw($_SERVER['REQUEST_URI'])); ?>">
        <?php
		do_action('job_bm_job_edit_form', $job_id);
		?>
    </form>
	<?php do_action('job_bm_job_edit_after', $job_id); ?>
</div>