<?php
/*
* @Author 		PickPlugins
* Copyright: 	2015 PickPlugins.com
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

$current_user_id = get_current_user_id();

$application_id = get_the_ID();
$application_author_id = (int) get_the_author_meta('ID');

$job_id = (int) get_post_meta($application_id, 'job_bm_am_job_id', true);
$job_data = get_post($job_id);
$job_author_id = (int) $job_data->post_author;


do_action('job_bm_before_single_application');

?>
<div id="application-single-<?php $application_id; ?>" class="application-single">

<?php

if($current_user_id ==$application_author_id || $current_user_id == $job_author_id ){
    do_action('job_bm_single_application_main');
}else{
    do_action('job_bm_single_application_main_no_access');
}

 ?>

<div class="clear"></div>
</div>
<?php

do_action('job_bm_after_single_application');



