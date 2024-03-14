 <?php
// Exit if accessed directly
 if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Settings Page
 *
 * Handle settings
 *
 * @package Loan Calculator
 * @since 1.0.0
 */

global $ww_loan_calculator_model;

$loan_all_setting_data =get_option('ww_loan_option');
$font_family_new_theme = isset( $loan_all_setting_data['font_family_new_theme'] ) ? $loan_all_setting_data['font_family_new_theme'] : "";
$back_ground_color = isset( $loan_all_setting_data['back_ground_color'] ) ? $loan_all_setting_data['back_ground_color'] : "#1f497d";
$selected_color = isset( $loan_all_setting_data['selected_color'] ) ? $loan_all_setting_data['selected_color'] : "#1f497d";
$background_light_color = isset( $loan_all_setting_data['background_light_color'] ) ? $loan_all_setting_data['background_light_color'] : "";
$border_color = isset( $loan_all_setting_data['border_color'] ) ? $loan_all_setting_data['border_color'] : "";
$graph_color = isset( $loan_all_setting_data['graph_color'] ) ? $loan_all_setting_data['graph_color'] : "";
$graph_color_sub = isset( $loan_all_setting_data['graph_color_sub'] ) ? $loan_all_setting_data['graph_color_sub'] : "";
$graph_border_color = isset( $loan_all_setting_data['graph_border_color'] ) ? $loan_all_setting_data['graph_border_color'] : "";
$graph_border_color_sub = isset( $loan_all_setting_data['graph_border_color_sub'] ) ? $loan_all_setting_data['graph_border_color_sub'] : "";


$interested_rate = isset( $loan_all_setting_data['interested_rate'] ) ? $loan_all_setting_data['interested_rate'] : "";
$ballon_per = isset( $loan_all_setting_data['ballon_per'] ) ? floatval( $loan_all_setting_data['ballon_per'] ) : "10";
$loan_term = isset( $loan_all_setting_data['loan_term'] ) ? $loan_all_setting_data['loan_term'] : "";

$loan_amount = isset( $loan_all_setting_data['loan_amount'] ) ? $loan_all_setting_data['loan_amount'] : "10000";
$monthly_rate = isset( $loan_all_setting_data['monthly_rate'] ) ? $loan_all_setting_data['monthly_rate'] : "";
$application_fee = isset( $loan_all_setting_data['application_fee'] ) ? $loan_all_setting_data['application_fee'] : "";
$loan_amount_min_value = isset( $loan_all_setting_data['loan_amount_min_value'] ) ? $loan_all_setting_data['loan_amount_min_value'] : "";
$loan_amount_max_value = isset( $loan_all_setting_data['loan_amount_max_value'] ) ? $loan_all_setting_data['loan_amount_max_value'] : "";
$loan_term_min_value = isset( $loan_all_setting_data['loan_term_min_value'] ) ? $loan_all_setting_data['loan_term_min_value'] : "";
$loan_term_max_value = isset( $loan_all_setting_data['loan_term_max_value'] ) ? $loan_all_setting_data['loan_term_max_value'] : "";
$interest_rate_min_value =isset($loan_all_setting_data['interest_rate_min_value'])?$loan_all_setting_data['interest_rate_min_value']:"";
$interest_rate_max_value =isset($loan_all_setting_data['interest_rate_max_value'])?$loan_all_setting_data['interest_rate_max_value']:"";

$application_fee_heading = isset( $loan_all_setting_data['application_fee_heading'] ) ? $loan_all_setting_data['application_fee_heading'] : "";
$total_regular_fees = isset( $loan_all_setting_data['total_regular_fees'] ) ? $loan_all_setting_data['total_regular_fees'] : "";
$total_fees = isset( $loan_all_setting_data['total_fees'] ) ? $loan_all_setting_data['total_fees'] : "";
$monthly_fee_heading = isset( $loan_all_setting_data['monthly_fee_heading'] ) ? $loan_all_setting_data['monthly_fee_heading'] : "";
$equipment_finance_loan_heading = isset( $loan_all_setting_data['equipment_finance_loan_heading'] ) ? $loan_all_setting_data['equipment_finance_loan_heading'] : "";
$equipment_finance_loan_sub_heading_lbl = isset( $loan_all_setting_data['equipment_finance_loan_sub_heading_lbl'] ) ? $loan_all_setting_data['equipment_finance_loan_sub_heading_lbl'] :"";
$equipment_finance_loan_term1 = isset( $loan_all_setting_data['equipment_finance_loan_term1']) ? $loan_all_setting_data['equipment_finance_loan_term1'] : "";
$equipment_finance_loan_term2 = isset( $loan_all_setting_data['equipment_finance_loan_term2'] ) ? $loan_all_setting_data['equipment_finance_loan_term2'] : "";
$equipment_finance_loan_term3 = isset( $loan_all_setting_data['equipment_finance_loan_term3'] ) ? $loan_all_setting_data['equipment_finance_loan_term3'] : "";
$equipment_finance_loan_term4 = isset( $loan_all_setting_data['equipment_finance_loan_term4'] ) ? $loan_all_setting_data['equipment_finance_loan_term4'] : "";
$equipment_finance_loan_term5 = isset( $loan_all_setting_data['equipment_finance_loan_term5'] ) ? $loan_all_setting_data['equipment_finance_loan_term5'] : "";

/* START : Calculation Result */
$regular_repayment_heading = isset( $loan_all_setting_data['regular_repayment_heading'] ) ? $loan_all_setting_data['regular_repayment_heading'] : "";
$per_month_heading = isset( $loan_all_setting_data['per_month_heading'] ) ? $loan_all_setting_data['per_month_heading'] : "";
$years_heading = isset( $loan_all_setting_data['years_heading'] ) ? $loan_all_setting_data['years_heading'] : "";
$total_interests_payable_heading = isset( $loan_all_setting_data['total_interests_payable_heading'] ) ? $loan_all_setting_data['total_interests_payable_heading'] :"";
$over_heading = isset($loan_all_setting_data['over_heading'])?$loan_all_setting_data['over_heading']:"";
$ballon_amt_heading = isset( $loan_all_setting_data['ballon_amt_heading'] ) ? $loan_all_setting_data['ballon_amt_heading'] : "";
/* END : Calculation Result */

/* START : Tab Field Setting */
$loan_feature_product_heading = isset( $loan_all_setting_data['loan_feature_product_heading'] ) ? $loan_all_setting_data['loan_feature_product_heading'] : "";
$video_heading = isset( $loan_all_setting_data['video_heading'] ) ? $loan_all_setting_data['video_heading'] : "";
$loan_table_heading = isset( $loan_all_setting_data['loan_table_heading'] ) ? $loan_all_setting_data['loan_table_heading'] : "";
$repayment_chart_heading = isset( $loan_all_setting_data['repayment_chart_heading'] ) ? $loan_all_setting_data['repayment_chart_heading'] : "";
$youtube_video_link = isset( $loan_all_setting_data['youtube_video_link'] ) ? $loan_all_setting_data['youtube_video_link'] :"" ;

/* END : Tab Field Setting */

/* START : Calculator Disclaimer Setting*/
$contact_info_heading = isset( $loan_all_setting_data['contact_info_heading'] ) ? $loan_all_setting_data['contact_info_heading'] : "" ;
$contact_popup_button_heading = isset( $loan_all_setting_data['contact_popup_button_heading'] ) ? $loan_all_setting_data['contact_popup_button_heading'] : "";
$calculator_disclaimer_heading = isset( $loan_all_setting_data['calculator_disclaimer_heading'] ) ? $loan_all_setting_data['calculator_disclaimer_heading'] :"";
$calculator_disclaimer_description = isset( $loan_all_setting_data['calculator_disclaimer_description'] ) ? $loan_all_setting_data['calculator_disclaimer_description'] :"";
$contact_popup_content = isset( $loan_all_setting_data['contact_popup_content'] ) ? $loan_all_setting_data['contact_popup_content'] :"";

$contact_type = isset( $loan_all_setting_data['contact_type'] ) ? $loan_all_setting_data['contact_type'] : "popup";
$contact_url = isset( $loan_all_setting_data['contact_url'] ) ? $loan_all_setting_data['contact_url'] : "";


/* END :  Calculator Disclaimer Setting*/

/* START : Tooltip Setting */ 
$loan_amount_tooltip = isset( $loan_all_setting_data['loan_amount_tooltip'] ) ? $loan_all_setting_data['loan_amount_tooltip'] :"";
$loan_terms_tooltip =isset( $loan_all_setting_data['loan_terms_tooltip'] )? $loan_all_setting_data['loan_terms_tooltip'] :"";
$interest_rates_tooltip = isset( $loan_all_setting_data['interest_rates_tooltip'] ) ? $loan_all_setting_data['interest_rates_tooltip'] :"";
/* END : Tooltip Setting */

/* START : Header Link Section*/
$print_label =isset( $loan_all_setting_data['print_label'] ) ? $loan_all_setting_data['print_label'] :"";
$about_this_calculator = isset( $loan_all_setting_data['about_this_calculator'] ) ? $loan_all_setting_data['about_this_calculator'] : "";
$calculator_popup_content = isset( $loan_all_setting_data['calculator_popup_content'] ) ?stripslashes( $loan_all_setting_data['calculator_popup_content'] ) :"" ;
/* END : Header Link Section */
/* START : Calculation Fee Setting Enable */
$calculation_fee_setting_enable = isset( $loan_all_setting_data['calculation_fee_setting_enable'] ) ? $loan_all_setting_data['calculation_fee_setting_enable']:"";
$calculator_heading = isset( $loan_all_setting_data['calculator_heading'] ) ? $loan_all_setting_data['calculator_heading'] : "";
/* END : Calculation Fee Setting Enable */

/* START : Tab Enable Settings */
$enable_repayment_chart = isset( $loan_all_setting_data['enable_repayment_chart'] ) ? $loan_all_setting_data['enable_repayment_chart'] : "";
$enable_video_tab = isset( $loan_all_setting_data['enable_video_tab'] ) ? $loan_all_setting_data['enable_video_tab'] : "";
$enable_loan_mortisation_tab = isset( $loan_all_setting_data['enable_loan_mortisation_tab'] ) ? $loan_all_setting_data['enable_loan_mortisation_tab'] : "";
$print_option_enable = isset( $loan_all_setting_data['print_option_enable'] ) ? $loan_all_setting_data['print_option_enable'] : "";
$print_option_heading = isset( $loan_all_setting_data['print_option_heading'] ) ? $loan_all_setting_data['print_option_heading'] : "";
$ww_loan_currency = isset( $loan_all_setting_data['ww_loan_currency'] ) ? $loan_all_setting_data['ww_loan_currency'] : "";


$currency_symbols = ww_loan_get_currency_symbol();

/* END : Tab Enable Settings */


/* START : NEW SETTINGS ADDED */
    
$disable_ballon_amt = isset( $loan_all_setting_data['disable_ballon_amt'] ) ? $loan_all_setting_data['disable_ballon_amt'] : "";

/* Repayment Frequency options */
$get_repayment_frequency = (isset( $loan_all_setting_data['repayment_frequency'] ) ? $loan_all_setting_data['repayment_frequency'] : "");



$disable_contactus_section=isset( $loan_all_setting_data['disable_contactus_section'] ) ? $loan_all_setting_data['disable_contactus_section'] : "";

$disable_calculator_disclaimer_section=isset( $loan_all_setting_data['disable_calculator_disclaimer_section'] ) ? $loan_all_setting_data['disable_calculator_disclaimer_section'] : "";

$disable_tabs_icon=isset( $loan_all_setting_data['disable_tabs_icon'] ) ? $loan_all_setting_data['disable_tabs_icon'] : "";

/* END : NEW SETTING ADDED */

?>

<style type="text/css">
    :root{

        --calc-font-family-new-theme: <?php echo esc_html( $font_family_new_theme );?>;
        --calc-background-color: <?php echo esc_html( $back_ground_color );?>;
        --calc-select-color: <?php echo esc_html( $selected_color );?>;
        --calc-bg-light-color:<?php echo esc_html( $background_light_color );?>;
        --calc-border-color:<?php echo esc_html( $border_color );?>;
        --calc-graph-color:<?php echo esc_html( $graph_color );?>;
        --calc-graph-color-sub:<?php echo esc_html( $graph_color_sub );?>;
        --calc-graph-border-color:<?php echo esc_html( $graph_border_color );?>;
        --calc-graph-border-color-sub:<?php echo esc_html( $graph_border_color_sub );?>;

    }
</style>
<section class="heading-section">
    <div class="menu-sec-cls">
        <ul class="heading-sec-link">
            <?php
            if( $print_option_enable ) { ?>
                <li>
                    <a href="javascript:;" class="print-table"><i class="fa fa-print" aria-hidden="true"></i><?php echo esc_html( $print_option_heading ); ?></a>
                </li>
            <?php  } ?>
            <li>
                <a href="javascript:;" onclick="jQuery('.about-this-calculator-popup').show();jQuery('body').addClass('body-overflow-hidden');"><i class="fa fa-info-circle" aria-hidden="true"></i><?php echo esc_html( $about_this_calculator ); ?></a>
            </li>
        </ul>
    </div>
    <div class="about-this-calculator-popup" style="display: none;">
        <div class="about-this-calculator-popup-body">
         <a href="javascript:;" class="close-button" onclick="jQuery('.about-this-calculator-popup').hide();jQuery('body').removeClass('body-overflow-hidden');">X</a>
         <?php 
                // very permissive: allows pretty much all HTML to pass - same as what's normally applied to the_content by default
         $allowed_html = wp_kses_allowed_html( 'post' );
         $calculator_popup_content = wp_kses( stripslashes_deep( $calculator_popup_content ), $allowed_html );
         ?>
         <div class="calculator-content"><?php echo $calculator_popup_content;?></div>
     </div>
 </div>
</section>

<section id="main-sec" class="new-theme-template-section">
    <section class="calculator-heading-section calculator-heading-block">
        <div class="calculator-child-heading">
            <h2><center><strong><?php echo esc_html( $calculator_heading );?></strong></h2></center>
        </div>
    </section>

    <section class="loan-option-text-info main-container-new-theme">
        <div class="custom-container loan-option-text-info-section-left-content">
            <div class="custom-container loan-option-text-info-section">
                <div class="loan-option-text-info-block">
                    <div class="first-col">
                        <div class="first-row-sub-child">
                            <div class="loan-text-dis-new-theme-block">
                                <div class="loan-new-theme-inner-block">
                                    <label for="loan_amt" class="loan-text" ><?php esc_html_e( 'Loan Amount', 'loan-calculator-wp' ); ?> <i class="fa fa-info-circle" aria-hidden="true" tabindex="1"></i><span class="text-tooltip-disp"><?php esc_html_e( $loan_amount_tooltip, 'loan-calculator-wp' ); ?></span></label>
                                    <div class="loan-new-theme-range-slider">
                                        <input type="range" min="<?php esc_attr_e( $loan_amount_min_value,'loan-calculator-wp' );?>" max="<?php esc_attr_e( $loan_amount_max_value,'loan-calculator-wp' );?>" value="<?php esc_attr_e( $loan_amount,'loan-calculator-wp' );?>" class="slider" id="loan_amount_range" tabindex="3" step="1000">
                                    </div>
                                </div>
                                <div class="col-columns-20">
                                    <div class="input-container">
                                        <input  type="text" class="loan-right-input" name="loan_amount" id="loan_amount" value="" tabindex="2" >
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="forth-row-sub-child">
                            <div class="loan-text-dis-new-theme-block">
                                <div class="loan-new-theme-inner-block">
                                    <label for="loan_terms" class="loan-text" ><?php esc_html_e( 'Loan Terms', 'loan-calculator-wp' ); ?><i class="fa fa-info-circle" aria-hidden="true" tabindex="4"></i><span class="text-tooltip-disp"><?php esc_html_e( $loan_terms_tooltip, 'loan-calculator-wp' ); ?></span></label>
                                    <div class="loan-new-theme-range-slider">
                                        <input type="range" min="<?php esc_attr_e( $loan_term_min_value,'loan-calculator-wp' );?>" max="<?php esc_attr_e($loan_term_max_value,'loan-calculator-wp');?>" value="<?php esc_attr_e($loan_term,'loan-calculator-wp');?>" class="slider" id="loan_terms_range" tabindex="6" step="1">
                                    </div>
                                </div>
                                <div class="col-columns-20">
                                    <div class="input-container-loan-terms">
                                        <input type="text" class="loan-right-input" name="loan_terms" id="loan_terms" value="" tabindex="5" onkeydown="return onlyNos(event,'loan_terms')"  autocomplete="off"  />
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="second-row-sub-child">  
                            <div class="loan-text-dis-new-theme-block">
                                <div class="loan-new-theme-inner-block">
                                    <label for="loan_amt" class="loan-text" ><?php esc_html_e( 'Interest Rate', 'loan-calculator-wp' ); ?><i class="fa fa-info-circle" aria-hidden="true" tabindex="8"></i><span class="text-tooltip-disp"><?php esc_attr_e( $interest_rates_tooltip,'loan-calculator-wp' );?></span></label>
                                    <div class="loan-new-theme-range-slider">
                                        <input type="range" min="<?php esc_attr_e( $interest_rate_min_value,'loan-calculator-wp' );?>" max="<?php esc_attr_e( $interest_rate_max_value,'loan-calculator-wp' );?>" value="<?php esc_attr_e($interested_rate,'loan-calculator-wp');?>" class="slider" id="interest_rate_range" tabindex="10" step="0.01">
                                    </div>
                                </div>
                                <div class="col-columns-20">
                                    <div class="input-container">
                                        <input type="text" class="loan-right-input" name="interest_rates" id="interest_rates" value="" tabindex="9" onkeydown="return onlyNos(event,'interest_rates')"  autocomplete="off"  />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php if ( $disable_ballon_amt == 1){ ?>
                            <div class="second-row-sub-child">
                                <div class="loan-text-dis">
                                    <input type="hidden" name="ballon_amounts" id="ballon_amounts" value="" tabindex="11" onkeydown="return onlyNos(event,'ballon_amounts')"/>
                                    <input type="hidden" name="ballon_amounts_per" id="ballon_amounts_per" value="<?php esc_attr_e($ballon_per);?>" tabindex="12" />
                                </div>
                                <input type="hidden" id="ballon_amount_range" tabindex="13" step="1" value ="<?php esc_attr_e($ballon_per);?>">
                            </div>
                        <?php } else{
                            ?>
                            <div class="fifth-row-sub-child">
                                <div class="loan-text-dis-new-theme-block">
                                    <div class="loan-new-theme-inner-block">
                                        <label for="loan_amt" class="loan-text" ><?php esc_html_e( 'Balloon Amount', 'loan-calculator-wp' ); ?></label>
                                        <div class="loan-new-theme-range-slider">
                                            <input type="range" min="0" max="50" value="<?php esc_attr_e($ballon_per);?>" class="slider" id="ballon_amount_range" tabindex="13" step="1">
                                        </div>
                                        <div class="ballon_amounts_sign">
                                            <span><medium> <?php echo $currency_symbols;?></medium></span>
                                            <input  name="ballon_amounts" id="ballon_amounts" value="" tabindex="11" onkeydown="return onlyNos(event,'ballon_amounts')"  readonly>
                                        </div>
                                    </div>
                                    <div class="col-columns-20">
                                        <div class="input-container">
                                            <input type="text" class="loan-right-input" name="ballon_amounts_per" id="ballon_amounts_per" value="" tabindex="12" onkeydown="return onlyNos(event,'ballon_amounts_per')" autocomplete="off"  />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php  } ?>

                        <div class="sixth-row-sub-child">
                            <div class="first-row-main-child">
                                <div class="loan-new-theme-inner-block loan-btn-display-value">
                                    <div class="loan-new-theme-inner-block">
                                        <label for="loan_amt" class="loan-text" ><?php esc_html_e(  'Payment Mode', 'loan-calculator-wp' ); ?></label>
                                    </div>

                                    <div class="col-columns-20">
                                       
                                        <div class="input-container">
                                            <select name="payment_type" id="payment_type" class="payment-opt-drop">
                                                <option value="<?php esc_attr_e('In Advance','loan-calculator-wp');?>" selected> <?php esc_html_e( 'In Advance', 'loan-calculator-wp' ); ?></option>
                                                <option value="<?php esc_attr_e( 'In Arrears','loan-calculator-wp' );?>" > <?php esc_html_e( 'In Arrears', 'loan-calculator-wp' ); ?></option>
                                            </select>
                                       
                                            <!-- <input type="text" id="payment_type_input_val" class="js-fake-input" value=""> -->
                                            <span class="select-arrow-main"><span class="select-arrow-wrap">❮</span></span> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                       <!-- Repayment Frequency options -->
                         <div class="sixth-row-sub-child">
                            <div class="first-row-main-child">
                                <div class="loan-new-theme-inner-block loan-btn-display-value">
                                    <div class="loan-new-theme-inner-block">
                                        <label for="loan_amt" class="loan-text" ><?php esc_html_e(  'Repayment Frequency', 'loan-calculator-wp' ); ?></label>
                                    </div>

                                    <div class="col-columns-20">
                                       
                                        <div class="input-container">
                                             <?php if(!empty($get_repayment_frequency)){ ?>


                                                <?php if(count($get_repayment_frequency) > 1){ ?>
                                            <select name="repayment_freq" id="repayment_freq" class="payment-opt-drop">
                                                    <?php
                                                        foreach ($get_repayment_frequency as $key => $value) {
                                                            $selected = ($key==0?'selected':'');
                                                            ?>
                                                             <option value="<?php echo $value;?>" <?php echo $selected; ?> ><?php echo $value; ?></option>    
                                                            <?php
                                                        }
                                                    ?>
                                            </select>
                                            <span class="select-arrow-main"><span class="select-arrow-wrap">❮</span></span>
                                        <?php }else{ ?>
                                             <input type="button" class="loan-right-input" id="repayment_freq" name="repayment_freq"  value="<?php echo current($get_repayment_frequency); ?>" tabindex="7"/>
                                        <?php } ?>





                                        <?php }else{ ?>
                                                <input type="button" class="loan-right-input" id="repayment_freq" name="repayment_freq"  value="<?php esc_attr_e( 'Monthly','loan-calculator-wp' );?>" tabindex="7"/>
                                          <?php } ?>
                                       
                                            <!-- <input type="text" id="payment_type_input_val" class="js-fake-input" value=""> -->
                                             
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Repayment Frequency options -->
                        
                    </div>
                </div>
            </div>
        </div>
        <?php 
        $full_width_cls ='';
        if( $enable_repayment_chart != 1 && $enable_video_tab != 1 && $enable_loan_mortisation_tab != 1) {
            $full_width_cls ='full-width';
        }
        ?>
        <div class="loan-detail-section-right-content">
            <div class="loan-detail-section <?php esc_attr_e( $full_width_cls );?>">
                <div class="loan-detail-section-child">
                    <div class="sub-main-tab">
                        <div class="ww-loan-container">
                            <div class="tabs">
                               <?php
                               $tab1_checked = $tab2_checked =$tab3_checked ="";

                               if( $enable_loan_mortisation_tab == 1  &&  $enable_video_tab == 1 && $enable_repayment_chart == 1 ){

                                $tab1_checked  = "checked";
                                $tab2_checked = "checked";
                                $tab3_checked = "checked";

                                ?> 
                                <style type="text/css">
                                    label.tab2_icon {
                                        margin-left: 10px !important;
                                    }
                                    label.tab3_icon {
                                        border-top-right-radius: 16px !important;
                                        border-bottom-right-radius: 16px !important;
                                        border-top-left-radius: unset !important;
                                        border-bottom-left-radius: unset !important;
                                    }
                                    label.tab2_icon{
                                        border-radius: 16px !important;
                                    }
                                    label.tab1_icon {
                                        border-radius: 16px 0px 0px 16px !important;
                                    }
                                </style>
                                <?php
                            }

                            else if( $enable_repayment_chart == 1  &&  $enable_video_tab == 1 ){
                                $tab1_checked = "checked";
                                $tab3_checked = "checked";
                                ?> 

                                <style type="text/css">
                                    label.tab2_icon {
                                        border-top-left-radius: unset !important;
                                        border-bottom-left-radius: unset !important;
                                    }
                                    label.tab1_icon {
                                        border-radius: 16px 0px 0px 16px !important;
                                    }
                                </style>
                                <?php

                            }  
                            else if( $enable_repayment_chart == 1  &&  $enable_loan_mortisation_tab == 1 ){
                                $tab1_checked  = "checked";
                                $tab2_checked = "checked";
                                ?>
                                <style type="text/css">
                                    label.tab1_icon {
                                        border-radius: 16px 0px 0px 16px !important;
                                    }
                                    label.tab3_icon {
                                        border-radius: 0px 16px 16px 0px !important;
                                    }
                                </style>
                                <?php
                            }  
                            else if( $enable_video_tab == 1  &&  $enable_loan_mortisation_tab == 1 ){

                                $tab2_checked = "checked";
                                $tab3_checked = "checked";

                                ?>
                                <style type="text/css">
                                   label.tab3_icon {
                                    border-top-right-radius: unset !important;
                                    border-bottom-right-radius: unset !important;
                                    border-top-left-radius: 16px !important;
                                    border-bottom-left-radius: 16px !important;
                                }
                                label.tab2_icon {
                                    border-top-right-radius: 16px !important;
                                    border-bottom-right-radius: 16px !important;
                                    border-top-left-radius: unset !important;
                                    border-bottom-left-radius: unset !important;
                                    margin-left: 0 !important;
                                }
                            </style>
                            <?php
                        }  

                        else if( $enable_repayment_chart == 1 ){ 
                            $tab1_checked  = "checked";
                            ?>
                            <style type="text/css">
                                label.tab1_icon {
                                    border-radius: 16px 16px 16px 16px !important;
                                }
                                label.tab3_icon {
                                    border-radius: 16px 16px 16px 16px !important;
                                }
                                label.tab2_icon {
                                    padding: 19px 40px !important;
                                    border-radius: 16px 16px 16px 16px !important;
                                }

                            </style>
                            <?php
                        }
                        else if( $enable_video_tab == 1 ){
                            $tab2_checked = "checked";
                            ?>
                            <style type="text/css">
                                label.tab1_icon {
                                    border-radius: 16px 16px 16px 16px !important;
                                }
                                label.tab3_icon {
                                    border-radius: 16px 16px 16px 16px !important;
                                }
                                label.tab2_icon {
                                    padding: 11px 40px !important;
                                    border-radius: 16px 16px 16px 16px !important;
                                }
                            </style>
                            <?php
                        }  else if( $enable_loan_mortisation_tab == 1 ){
                            $tab3_checked = "checked";
                            ?>
                            <style type="text/css">
                                label.tab1_icon {
                                    border-radius: 16px 16px 16px 16px !important;
                                }
                                label.tab2_icon {
                                    padding: 19px 40px !important;
                                    border-radius: 16px 16px 16px 16px !important;
                                }
                                label.tab3_icon {
                                    border-radius: 16px 16px 16px 16px !important;
                                }
                            </style>
                            <?php
                        }   

                        ?>
                        <?php
                        if( $enable_repayment_chart == 1 ) {
                            ?>
                            <input type="radio" name="tabs" id="tab1" <?php esc_attr_e($tab1_checked);?>>
                            <label for="tab1" class="tab1_icon">
                                <?php if( $disable_tabs_icon == ""){ ?>
                                    <!-- <i class="fa fa-chart-bar"></i> -->
                                    <img src="<?php  echo WW_LOAN_CALCULATOR_URL?>/includes/images/group-4.png">
                                    <span class="tooltip-disp"><?php esc_html_e($repayment_chart_heading,'loan-calculator-wp');?></span>
                                <?php } else { ?>
                                    <span><?php esc_html_e($repayment_chart_heading,'loan-calculator-wp');?></span>
                                <?php } ?>
                            </label>
                        <?php  } ?>
                        <?php
                        if( $enable_loan_mortisation_tab == 1 ) {
                            ?>
                            <input type="radio" name="tabs" id="tab3" <?php esc_attr_e($tab3_checked);?>>
                            <label for="tab3" class="tab3_icon">
                                <?php if( $disable_tabs_icon == ""){ ?>
                                    <!-- <i class="fa fa-tasks"></i> -->
                                    <img src="<?php  echo WW_LOAN_CALCULATOR_URL?>/includes/images/group-5.png">
                                    <span class="tooltip-disp"><?php esc_html_e($loan_table_heading,'loan-calculator-wp');?></span>
                                <?php } else { ?>
                                 <span class=""><?php esc_html_e($loan_table_heading,'loan-calculator-wp');?></span>
                             <?php } ?>   
                         </label>
                     <?php  } ?>
                     <?php
                     if( $enable_video_tab == 1 ) {
                        ?>
                        <input type="radio" name="tabs" id="tab2" <?php esc_attr_e( $tab2_checked);?>>
                        <label for="tab2" class="tab2_icon" >
                            <?php if( $disable_tabs_icon == ""){ ?>
                                <!-- <i class="fa fa-play"></i> -->
                                <img src="<?php  echo WW_LOAN_CALCULATOR_URL?>/includes/images/play-video.png">
                                <span class="tooltip-disp"><?php esc_html_e( $video_heading,'loan-calculator-wp' );?></span>
                            <?php } else { ?>
                             <span><?php esc_html_e( $video_heading,'loan-calculator-wp' );?></span>
                         <?php } ?>   
                     </label>
                 <?php  } ?>
                 <div id="tab-content1" class="tab-content">
                    <!--<div id="loan-process-graph"></div>-->
                    <canvas id="loan-process-graph" width="800" height="1200"></canvas>                   </div>
                    <div id="tab-content2" class="tab-content">
                        <?php 
                        if( !empty( $youtube_video_link ) ) {
                            ?>
                            <iframe height="415" src="<?php echo esc_url($youtube_video_link);?>" style="width:100%;" frameborder="0" allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen=""></iframe>
                        <?php } else { ?>
                            <div class="no-video-exist-main">
                                <div class="video-child-sec">
                                    <h1><?php esc_html_e( 'Sorry', 'loan-calculator-wp' ); ?></h1>
                                    <p><?php esc_html_e( 'This video does not exist.', 'loan-calculator-wp' ); ?></p>
                                </div>
                            </div>
                        <?php } ?>    
                    </div>
                    <div id="tab-content3" class="tab-content">
                        <table id="loan-process-tbl">
                            <thead>
                                <tr>
                                    <th><?php esc_html_e( 'Period', 'loan-calculator-wp' ); ?></th>
                                    <th><?php esc_html_e( 'Payment', 'loan-calculator-wp' ); ?></th>
                                    <th><?php esc_html_e( 'Interest', 'loan-calculator-wp' ); ?></th>
                                    <th><?php esc_html_e( 'Balance', 'loan-calculator-wp' ); ?></th>
                                </tr>
                            </thead>
                            <tbody id="loan_table_data">
                            </tbody>
                        </table>
                    </div>
                    </div>
                    </div>
                     </div>
                </div>


            </div>
        </div>
</section>
<section class="loan-option-text-info main-container-new-theme second-section-new-theme">
    <div class="custom-container loan-option-text-info-section-left-content">
        <div class="custom-container loan-option-text-info-section">
            <div class="loan-option-text-info-block">
                <div class="first-col">
                    <?php if ( $disable_contactus_section == ""){ ?>
                     <div class="contact-us-section-new-theme">
                        <?php if( $contact_type == "popup"){ ?>
                            <button class="contact-book-btn"><?php esc_html_e( $contact_popup_button_heading,'loan-calculator-wp' );?></button>
                        <?php } else {  ?>
                         <a href="<?php echo $contact_url;?>" target="_blank"><?php esc_html_e( $contact_popup_button_heading,'loan-calculator-wp' );?></a>  
                     <?php } ?>
                 </div>
             <?php } ?>
         </div>
     </div>
 </div>
</div>
<?php 
$full_width_cls ='';
if( $enable_repayment_chart != 1 && $enable_video_tab != 1 && $enable_loan_mortisation_tab != 1) {
    $full_width_cls ='full-width';
}
?>
<div class="loan-detail-section-right-content">
    <div class="loan-detail-section <?php esc_attr_e( $full_width_cls );?>">

        <div class="container loan-detail-section-child-container ">
            <div class="loan-detail-section-child">
                <div class="loan-detail-cal-desc">
                    <div class="loan-cal-desc">
                        <div class="loan-cal-desc-heading main-heading">
                            <label><strong></strong></label>
                        </div>
                        <div class="loan-cal-desc-val">
                            <label><span><small><?php echo esc_html($currency_symbols); ?></small><span id="per_month_amount"></span></span> <strong id="loan_amount_term_label"></strong><span id="loan_amount_year"></span> </span></label>
                        </div>
                    </div>
                    <div class="loan-cal-desc">
                        <div class="loan-cal-desc-heading">
                            <label><span><?php esc_html_e( $total_interests_payable_heading, 'loan-calculator-wp' ); ?></span></label>
                        </div>
                        <div class="loan-cal-desc-val">
                            <label><small><?php echo esc_html($currency_symbols); ?></small><span id="total_interests_amt"></span>  <?php esc_html_e( 'over', 'loan-calculator-wp' ); ?> <span id="total_interests_years"></span> </label>
                        </div>
                    </div>
                    <div class="loan-cal-desc" id="ballon_amt_section">
                        <div class="loan-cal-desc-heading" >
                            <label><span><?php esc_html_e( $ballon_amt_heading, 'loan-calculator-wp' ); ?> (<span id="bill_ballon_per"><?php esc_attr_e(number_format( $ballon_per,2 ),'loan-calculator-wp'); ?></span>%)</span></label>
                        </div>
                        <div class="loan-cal-desc-val">
                            <label><small><?php echo esc_html($currency_symbols); ?></small><strong><span id="bill_ballon_amt"><?php esc_attr_e(number_format( ( $loan_amount*$ballon_per/100 ),2 ),'loan-calculator-wp' ); ?></span></strong></label>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            $total_regular_fees_amt = round(floatval( ceil($loan_term) * 120 ) ,2);
            $total_fees_amt = floatval( $total_regular_fees_amt )+floatval( $application_fee );
            ?>
            <?php if( $calculation_fee_setting_enable == 1 ) { ?>
                <div class="loan-detail-fee-desc">
                    <div class="loan-detail-fee-block">
                        <div class="loan-detail-fee-heading">
                            <h5><?php esc_html_e($application_fee_heading,'loan-calculator-wp');?></h5>
                        </div>
                        <div class="loan-detail-fee-val">
                            <p>$<?php esc_html_e( $application_fee,'loan-calculator-wp' );?></p>
                        </div>
                    </div>
                    <div class="loan-detail-fee-block">
                        <div class="loan-detail-fee-heading">
                            <h5><?php esc_html_e( $monthly_fee_heading,'loan-calculator-wp' );?></h5>
                        </div>
                        <div class="loan-detail-fee-val">
                            <p>$<?php esc_html_e( $monthly_rate,'loan-calculator-wp' );?></p>
                        </div>
                    </div>
                    <div class="loan-detail-fee-block">
                        <div class="loan-detail-fee-heading">
                            <h5><?php esc_html_e( $total_regular_fees,'loan-calculator-wp' );?></h5>
                        </div>
                        <div class="loan-detail-fee-val">
                            <p>$<span id="total_regular_fee_amt"><?php esc_html_e( round($total_regular_fees_amt,2),'loan-calculator-wp' );?></span></p>
                        </div>
                    </div>
                    <div class="loan-detail-fee-block">
                        <div class="loan-detail-fee-heading">
                            <h5><?php esc_html_e( $total_fees,'loan-calculator-wp' );?></h5>
                        </div>
                         <div class="loan-detail-fee-val">
                            <p>$<span id="total_fee_amt"><?php esc_html_e( $total_fees_amt,'loan-calculator-wp' );?></span></p>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
</section>

<div class="contact-us-popup" style="display:none;">
    <div class="contact-us-popup-body">
     <a href="javascript:;" class="close-button" onclick="jQuery('.contact-us-popup').hide();jQuery('body').removeClass('body-overflow-hidden');">X</a>
     <?php echo do_shortcode( $contact_popup_content ) ; ?>
 </div>
</div>
</section>
