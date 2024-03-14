<?php

if( isset( $_POST['data'] ) && isset( $_POST['reset_button'] ) ){
    //Reset 
    $data = WC_MMQ::getDefaults();
    //var_dump($value);
    update_option( WC_MMQ_KEY, $data );
    echo '<div class="updated"><p>Reset Successfully</p></div>';
}else if( isset( $_POST['data'] ) && isset( $_POST['configure_submit'] ) ){

    //configure_submit
    $values = ( is_array( $_POST['data'] ) ? $_POST['data'] : false );
    
    $data = $final_data = array();
    if( is_array( $values ) && count( $values ) > 0 ){
        foreach( $values as $key=>$value ){
            if( empty( $value ) ){
               $data[$key] = false; 
            }else{
               $data[$key] = $value;  
            }
        }
    }else{
        $data = WC_MMQ::getDefaults();
    }
    
    if( !$data[WC_MMQ_PREFIX . 'min_quantity'] && $data[WC_MMQ_PREFIX . 'min_quantity'] != 0 &&  $data[WC_MMQ_PREFIX . 'min_quantity'] !=1 && $data[WC_MMQ_PREFIX . 'max_quantity'] <= $data[WC_MMQ_PREFIX . 'min_quantity'] ){
        $data[WC_MMQ_PREFIX . 'max_quantity'] = $data[WC_MMQ_PREFIX . 'min_quantity'] + 5;
        echo '<div class="error notice"><p>Maximum Quantity can not be smaller, So we have added 5</p></div>';
    }
    if( !$data[WC_MMQ_PREFIX . 'product_step'] || $data[WC_MMQ_PREFIX . 'product_step'] == '0' || $data[WC_MMQ_PREFIX . 'product_step'] == 0 ){
       $data[WC_MMQ_PREFIX . 'product_step'] = 1; 
    }
    
    if( !$data[WC_MMQ_PREFIX . 'min_quantity'] || $data[WC_MMQ_PREFIX . 'min_quantity'] == '0' || $data[WC_MMQ_PREFIX . 'min_quantity'] == 0 ){
       $data[WC_MMQ_PREFIX . 'min_quantity'] = '0'; 
    }
    $data[WC_MMQ_PREFIX . 'default_quantity'] = isset( $data[WC_MMQ_PREFIX . 'default_quantity'] ) && $data[WC_MMQ_PREFIX . 'default_quantity'] >= $data[WC_MMQ_PREFIX . 'min_quantity'] && ( empty( $data[WC_MMQ_PREFIX . 'max_quantity'] ) || $data[WC_MMQ_PREFIX . 'default_quantity'] <= $data[WC_MMQ_PREFIX . 'max_quantity'] ) ? $data[WC_MMQ_PREFIX . 'default_quantity'] : false;
    
    //plus minus checkbox data fixer
    $data[ WC_MMQ_PREFIX . 'qty_plus_minus_btn' ] = !isset( $data[ WC_MMQ_PREFIX . 'qty_plus_minus_btn' ] ) ? 0 : 1;
    
    if(is_array( $data ) && count( $data ) > 0 ){
        foreach($data as $key=>$value){
            if( is_string( $value ) ){
                $val = str_replace('\\', '', $value );
            }else{
                $val = $value;
            }
            
            $final_data[$key] = $val;
        }
    }
    
    
    //set default value false for _cat_ids
    $final_data['_cat_ids'] = isset( $final_data['_cat_ids'] ) ? $final_data['_cat_ids'] : false;
    update_option( WC_MMQ_KEY, $final_data);
    echo '<div class="updated"><p>Successfully Updated</p></div>';
    //echo  ! $data[WC_MMQ_PREFIX . 'default_quantity'] ? '<div class="error warning"><p>But Default Quanity should gatter then Min Quantity And less then Max Quantity. <b>Only is you set Default Quantity</b></p></div>' : false;
}

$saved_data = WC_MMQ::getOptions();



//TOPBAR INCLUDE HERE
include $this->topbar_file;
$is_pro = $this->is_pro;
if( ! $is_pro ){
    include 'main-page/premium-link-header.php'; 
}
?>





<div class="wrap wcmmq_wrap wcmmq-content">

    <h1 class="wp-heading "></h1>
    <div class="fieldwrap">
        <?php
        $randN = rand(1,2);
        $wcmmq_recomm = get_option('wcmmq_recomm', 1);
        $wcmmq_recomm++;
        update_option('wcmmq_recomm', $wcmmq_recomm);
        if($wcmmq_recomm <= 30 && $randN == 1){
        ?>
        <div id="wcmmq-recomendation-area" class="wcmmq-section-panel">
            <?php do_action( 'wcmmq_plugin_recommend_top' ); ?>
        </div>
        <?php } ?>
        <?php
            // do_action( 'wcmmq_before_form' );
        ?>
        <!-- <div class="wcmmq-section-panel no-background">
            <a class="wcmmq-btn wcmmq-has-icon" href="#"><span><i class="wcmmq_icon-ok"></i></span>Link</a>
            <button class="wcmmq-btn wcmmq-has-icon"><span><i class="wcmmq_icon-ok"></i></span>Save Change</button>
            <button class="wcmmq-btn reset wcmmq-has-icon"><span><i class="wcmmq_icon-ok"></i></span>Save Change</button>
            <button class="wcmmq-btn round reset wcmmq-has-icon"><span><i class="wcmmq_icon-ok"></i></span>Round Button</button>
            
        </div> -->
        
        <form class="" action="" method="POST" id="wcmmq-main-configuration-form">
            
            <div class="wcmmq-configure-tab-wrapper wcmmq-section-panel no-background"></div>
        
            <div class="wcmmq-section-panel universal-settings" id="wcmmq-universal-settings">
                <?php include 'main-page/universal-settings.php'; ?>
            </div>
        
            <?php 
            
            /**
             * @Hook Action: wcmmq_form_panel
             * To add new panel in Forms
             * @since 1.8.6
             */
            do_action( 'wcmmq_form_panel', $saved_data );
            ?>
            

        
            <div class="wcmmq-section-panel supported-terms" id="wcmmq-supported-terms">
                <?php include 'main-page/supported-terms.php'; ?>
            
            </div>
            
            <div class="wcmmq-section-panel inside-panel edit-terms">
                <?php include 'main-page/edit-terms.php'; ?>
            </div>
            
            
            <?php 
            
            /**
             * @Hook Action: wcmmq_form_panel
             * To add new panel in Forms
             * @since 1.8.6
             */
            do_action( 'wcmmq_form_panel_before_message', $saved_data );

            $fields_arr = [
                'msg_min_limit' => [
                    'title' => __('Minimum Quantity Validation Message','wcmmq' ),
                    'desc'  => __('Available shortcode [min_quantity],[max_quantity],[product_name],[step_quantity],[inputed_quantity],[variation_name]','wcmmq' ),
                ],
                
                'msg_max_limit' => [
                    'title' => __('Maximum Quantity Validation Message','wcmmq' ),
                    'desc'  => __('Available shortcode [current_quantity][min_quantity],[max_quantity],[product_name],[step_quantity],[inputed_quantity],[variation_name]','wcmmq' ),
                ],
                'msg_max_limit_with_already' => [
                    'title' => __('Already Quantity Validation Message','wcmmq' ),
                    'desc'  => __('Available shortcode [current_quantity][min_quantity],[max_quantity],[product_name],[step_quantity],[variation_name]','wcmmq' ),
                ],
                'min_qty_msg_in_loop' => [
                    'title' => __('Minimum Quantity message for shop page','wcmmq' ),
                    'desc'  => __('Available shortcode [min_quantity],[max_quantity],[product_name],[step_quantity],[variation_name]','wcmmq' ),
                ],
                'step_error_valiation' => [
                    'title' => __('Step validation error message','wcmmq' ),
                    'desc'  => __('Available shortcode [should_min],[should_next],[product_name],[variation_name],[quantity],[min_quantity],[step_quantity]','wcmmq' ),
                ],
        
            ];
        
            wcmmq_message_field_generator($fields_arr, $saved_data);
            
            /**
             * @Hook Action: wcmmq_form_panel
             * To add new panel in Forms
             * @since 1.8.6
             */
            do_action( 'wcmmq_form_panel_bottom', $saved_data );
            ?>
            <div class="wcmmq-section-panel live-support" id="wcmmq-live-support-area">
                <?php include 'main-page/live-support.php'; ?>
            </div>

            <?php 
            if( ! $this->is_pro ){
                include 'main-page/premium-placeholder.php';
            }
            ?>

            <div class="wcmmq-section-panel no-background wcmmq-full-form-submit-wrapper">
                
                <button name="configure_submit" type="submit"
                    class="wcmmq-btn wcmmq-has-icon configure_submit">
                    <span><i class="wcmmq_icon-floppy"></i></span>
                    <strong class="form-submit-text">
                    <?php echo esc_html__('Save Change','wcmmq');?>
                    </strong>
                </button>
                <button name="reset_button" 
                    class="wcmmq-btn reset wcmmq-has-icon reset_button"
                    onclick="return confirm('If you continue with this action, you will reset all options in this page.\nAre you sure?');">
                    <span><i class="wcmmq_icon-arrows-cw "></i></span>
                    <?php echo esc_html__( 'Reset Settings', 'wcmmq' ); ?>
                </button>
                
            </div>

            

                    
        </form>
        <div class="wcmmq-section-panel supported-terms wcmmq-recomendation-area" id="wcmmq-recomendation-area">
            <table class="wcmmq-table universal-setting">
                <thead>
                    <tr>
                        <th class="wcmmq-inside">
                            <div class="wcmmq-table-header-inside">
                                <h3><?php echo esc_html__('Recommendation Area', 'wpt'); ?> <small class="wcmmq-small-title">To increase Sale</small></h3>
                            </div>

                        </th>
                        <th>
                            <div class="wcmmq-table-header-right-side"></div>
                        </th>
                    </tr>
                </thead>

                <tbody>
                <tr>
                    <td>
                        <div class="wcmmq-form-control">
                            <div class="form-label col-lg-12">
                            <?php do_action( 'wcmmq_plugin_recommend_here' ); ?>
                            </div>
                            <div class="form-label col-lg-12">
                                <?php wcmmq_submit_issue_link(); ?>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="wqpmb-form-info">
                            
                            <?php wcmmq_social_links(); ?>
                            <p>Highly Recommeded these plugin. Which will help you to increase your WooCommerce sale.</p>
                        </div> 
                    </td>
                </tr>
                </tbody>
            </table>

        </div> <!--/.wcmmq-recomendation-area -->
    </div>
</div> 