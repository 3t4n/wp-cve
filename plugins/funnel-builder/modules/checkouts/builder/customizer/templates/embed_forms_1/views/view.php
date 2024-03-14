<?php
if ( ! defined( 'WFACP_TEMPLATE_DIR' ) ) {
	return '';
}
/**
 * @var $instance WFACP_Template_Custom_Page
 */
$instance          = wfacp_template();
$is_customizer     = WFACP_Common::is_customizer();
$is_wfacp_checkout = WFACP_Core()->template_loader->is_valid_state_for_data_setup();
$checkout          = WC()->checkout();
$wp_head_included  = did_action( 'wp_head' ) > 0 ? true : false;
if ( true == $is_customizer ) {
	$exclude_header_footer = false;
}
// customizer header
if ( false == $wp_head_included && ( true == $is_customizer || true == $is_wfacp_checkout ) && apply_filters( 'wfacp_embed_form_allow_header', true, $instance ) && false == $exclude_header_footer ) {
	include( $instance->wfacp_get_header() );
}
do_action( 'wfacpef_before_form' );
$formData          = $instance->get_form_step_data();
$step_form_data    = $formData['wfacp_form']['step_form'];
$disable_steps_bar = '';
if ( isset( $formData['wfacp_form']['layout']['disable_steps_bar'] ) && $formData['wfacp_form']['layout']['disable_steps_bar'] == 1 ) {
	$disable_steps_bar = $formData['wfacp_form']['layout']['disable_steps_bar'];
}
if ( $formData['wfacp_form']['layout']['step_form_max_width'] < 550 ) {
	$wrap_width_cls = 'wfacp_mob_emb_wrap wfacp_enable_mb_style';
} else {
	$wrap_width_cls = '';
}
/** Removing no index meta tag */
remove_action( 'wfacp_header_print_in_head', [ $instance, 'no_follow_no_index' ] );
do_action( 'wfacp_header_print_in_head' );
?>
<!--main panel wrapper open -->
<div id="wfacp-e-form">
    <div class="wrapper wfacp-main-container wfacp_form_steps_wrap <?php echo $wrap_width_cls ?>">
        <div class="wfacp-wrapper-decoration">
            <!-- container wrapper open -->
            <div class="wfacp-panel-wrapper">
                <div class="wfacp-container wfacp-contenter-inner-wrapper clearfix">
                    <!--wfacp-form panel -->
                    <div class="wfacp-form wfacp_form clearfix">
                        <div class="wfacp-comm-wrapper clearfix">
							<?php
							$number_of_steps = $instance->get_step_count();
							$tab_active      = true;
							if ( isset( $formData['wfacp_form']['layout']['disable_steps_bar'] ) ) {
								$tab_active = wc_string_to_bool( $formData['wfacp_form']['layout']['disable_steps_bar'] );
							}
							if ( ( is_array( $step_form_data ) && count( $step_form_data ) > 0 ) && $tab_active !== true ) {
								?>
                                <div class="wfacp-payment-title wfacp-hg-by-box wfacp_embed_step_<?php echo $number_of_steps; ?>">
                                    <div class="wfacp-payment-tab-wrapper clearfix">
										<?php
										$count          = 1;
										$count_of_steps = sizeof( $step_form_data );
										$steps          = [ 'single_step', 'two_step', 'third_step' ];
										$addfull_width  = "full_width_cls";
										if ( $count_of_steps == 2 ) {
											$addfull_width = "wfacpef_two_step";
										}
										if ( $count_of_steps == 3 ) {
											$addfull_width = "wfacpef_third_step";
										}
										foreach ( $step_form_data as $key => $value ) {
											$activeClass      = '';
											$steps_count_here = $steps[ $key ];
											$activeClass1     = '';
											if ( $count == 1 ) {
												$page_class   = 'single_step';
												$activeClass1 = 'wfacp-active';
											}
											if ( $count == 2 ) {
												$page_class = 'two_step';
											}
											if ( $count == 3 ) {
												$page_class = 'third_step';
											}
											$activeClass = apply_filters( 'wfacp_embed_active_progress_bar', $activeClass1, $count, $number_of_steps );
											?>
                                            <div class="wfacp-payment-tab-list <?php echo $activeClass . ' ' . $page_class . " " . $addfull_width; ?>  wfacp-tab<?php echo $count; ?>" step="<?php echo $steps_count_here; ?>">
                                                <div class="wfacp-order2StepNumber"><?php echo $count; ?></div>
                                                <div class="wfacp-order2StepHeaderText">
                                                    <div class="wfacp-order2StepTitle wfacp-order2StepTitleS1 wfacp_tcolor"><?php echo $value[ 'name_' . $key ]; ?></div>
                                                    <div class="wfacp-order2StepSubTitle wfacp-order2StepSubTitleS1 wfacp_tcolor"><?php echo $value[ 'headline_' . $key ]; ?></div>
                                                </div>
                                            </div>
											<?php
											$count ++;
										}
										?>
                                    </div>
                                </div>
								<?php
							}
							?>
                            <div class="wfacp-inner-form-detail-wrap wfacp_step_count_<?php echo $number_of_steps; ?>">
								<?php include( $instance->wfacp_get_form() ); ?>
                            </div>
                        </div>
                    </div>
                    <!-- wfacp-form panel close-->
                </div>
            </div>
        </div>
    </div>
</div>
<?php
if ( false == $wp_head_included && ( true == $is_customizer || true == $is_wfacp_checkout ) && false == $exclude_header_footer ) {
	include( $instance->wfacp_get_footer() );
}
?>
