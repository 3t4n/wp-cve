<?php
/*
* @Author 		PickPlugins
* Copyright: 	2015 PickPlugins.com
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 


	$job_id = get_the_ID();

    do_action('job_bm_before_single_job', $job_id);

    ?>
    <div id="job-<?php echo $job_id; ?>" class="job-single">

    <?php do_action('job_bm_single_job_main', $job_id ); ?>

    <div class="clear"></div>
    </div>
    <?php

    do_action('job_bm_after_single_job', $job_id);



