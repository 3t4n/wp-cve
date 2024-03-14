<?php
if ( ! defined('ABSPATH')) exit;  // if direct access


do_action('job_bm_registration_form_before');
?>
<div class="job-bm-registration-form">

    <?php

    if(!empty($_POST['job_bm_registration_nonce'])){
        do_action('job_bm_registration_submit', $_POST);
    }



    ?>
    <form action="<?php echo esc_url_raw($_SERVER['REQUEST_URI']); ?>" method="post">
        <?php
        do_action('job_bm_registration_form');
        ?>
        <?php wp_nonce_field( 'job_bm_registration_nonce','job_bm_registration_nonce' ); ?>
        <input type="submit" name="submit" value="<?php echo __('Register','job-board-manager'); ?>"/>
    </form>

</div>
<?php
do_action('job_bm_registration_form_after');


