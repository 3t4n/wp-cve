<?php
if ( ! defined('ABSPATH')) exit;  // if direct access 



add_action('job_bm_payment', 'job_bm_payment_notice');

if(!function_exists('job_bm_payment_notice')){
    function job_bm_payment_notice(){

        /**
         * by default "job-bm-notice" class hidden
         * add class "has-notice" to display
         * status class:  success, fail, error
         */
        ?>
        <div id="job-bm-notice" class="job-bm-notice <?php echo apply_filters('job_bm_notice_classes',''); ?>"><?php echo apply_filters('job_bm_notice_message',''); ?></div>

        <?php
    }
}




add_action('job_bm_payment', 'job_bm_payment_form');

if(!function_exists('job_bm_payment_form')){
    function job_bm_payment_form(){
        $job_bm_payment_methods = get_option('job_bm_payment_methods');

        $paymentData = array();

        ?>

        <form action="" method="post">

            <div class="payment-details">
                <div>Job: This is job title</div>
                <div>Amount: $45</div>
                <div>Job limit: 1</div>
                <div>Featured limit: 1</div>

            </div>

            <ul class="payment-methods">

                <?php

                foreach ($job_bm_payment_methods as $method_index => $methodName){
                    ?>
                    <li>
                        <?php

                        do_action('job_bm_payment_method_input_'.$method_index,  $paymentData);

                        ?>

                    </li>
                    <?php
                }

                ?>

            </ul>
        </form>

        <?php
    }
}


function job_bm_payment_method_input_paypal($paymentData){

}

