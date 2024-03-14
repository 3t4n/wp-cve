<?php

/**
 *
 *
 * @author CMSHelplive
 */
class RM_Payments_Service extends RM_Services
{
    public function __construct($model = null) {
        parent::__construct($model);
    }
    
    public function output_pdf_for_invoice(RM_Submissions $submission, $outputconf = array('name' => 'invoice.pdf', 'type' => 'D')) {
        
        if(defined('REGMAGIC_ADDON') && class_exists('RM_Payments_Service_Addon')){
            $addon_service = new RM_Payments_Service_Addon;
            $addon_service->output_pdf_for_invoice($submission, $outputconf, $this);
        }
    }
    
    public function rm_user_payments_details($user_email, $start_date ='', $end_date ='',$status=''){
        $user_payments = RM_DBManager::get_recents_payments_by_email_date($user_email, $start_date, $end_date, $status);
        
        if(!empty($user_payments)):?>
        <div class="rmagic">
            <div class="rmagic-table">
                <table class="rm-user-data">
                    <thead>
                        <tr>
                            <th class="rm-bg-lt"><?php _e('Date','custom-registration-form-builder-with-submission-manager');?></th>
                            <th class="rm-bg-lt"><?php _e('Form','custom-registration-form-builder-with-submission-manager');?></th>
                            <th class="rm-bg-lt"><?php _e('Unique ID','custom-registration-form-builder-with-submission-manager');?></th>
                            <th class="rm-bg-lt"><?php _e('Amount','custom-registration-form-builder-with-submission-manager');?></th>
                            <th class="rm-bg-lt"><?php _e('Invoice','custom-registration-form-builder-with-submission-manager');?></th>
                            <th class="rm-bg-lt"><?php _e('Status','custom-registration-form-builder-with-submission-manager');?></th>
                            <th class="rm-bg-lt"><?php _e('Payment Method','custom-registration-form-builder-with-submission-manager');?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        foreach( $user_payments as $user_payment ){ ?>
                        <tr>
                            <td><?php echo wp_kses_post((string)RM_Utilities::localize_time($user_payment->submitted_on,'j M, Y'));?></td>
                            <td><?php echo wp_kses_post((string)$user_payment->form_name);?></td>
                            <td><?php echo wp_kses_post((string)$user_payment->unique_token);?></td>
                            <td><?php echo wp_kses_post((string)RM_Utilities::get_formatted_price($user_payment->total_amount));?></td>
                            <td><?php echo wp_kses_post((string)$user_payment->invoice);?></td>
                            <td><?php 
                                if(strtolower($user_payment->status) == 'succeeded'){
                                    echo _e('Completed','custom-registration-form-builder-with-submission-manager');
                                }else{
                                    echo wp_kses_post((string)$user_payment->status);
                                }
                            ?></td>
                            <td>
                                <?php echo wp_kses_post((string)ucfirst($user_payment->pay_proc));?>
                            </td>
                        </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="rm-no-payment-found"><?php esc_html_e('No payment history found.','custom-registration-form-builder-with-submission-manager');?></div>
            <?php endif;?>
        </div>
        <?php
    }
    
}