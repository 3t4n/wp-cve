<?php
if(!defined( 'ABSPATH' )){
	exit;
}

global $MWXS_L;
$page_url = admin_url('admin.php?page=myworks-wc-xero-sync-compatibility');

# Save
if ( ! empty( $_POST ) && check_admin_referer( 'myworks_wc_xero_sync_save_compt_nonce', 'wc_xero_save_compt_s' ) ) {
	$compt_s_saved = false;

	# WooCommerce Sequential Order Numbers Pro
	if(isset($_POST['comp_wsnop'])){
		$compt_s_saved = true;
		$compt_p_wsnop = '';
		if(isset($_POST['mw_wc_xero_sync_compt_p_wsnop'])){
			$compt_p_wsnop = 'true';
		}

		$MWXS_L->update_option('mw_wc_xero_sync_compt_p_wsnop',$compt_p_wsnop);
	}

	if($compt_s_saved){
		$MWXS_L->set_session_val('compt_save_status','Compatibility settings saved successfully.');
		$MWXS_L->redirect($page_url);
	}	
}

$compt_save_status = $MWXS_L->get_session_val('compt_save_status','',true);

$is_compt = false;
$is_order_num_compt = false;
?>

<h2 class="compt_addon_heading"><?php _e( 'Compatibility Included / Addons', 'myworks-sync-for-xero');?></h2>
<div class="container map-coupon-code-outer qo-compatibility-addons">
	<form method="post" action="<?php echo esc_url($page_url);?>">
		<?php wp_nonce_field( 'myworks_wc_xero_sync_save_compt_nonce', 'wc_xero_save_compt_s'); ?>

		<?php if($MWXS_L->is_plugin_active('woocommerce-sequential-order-numbers-pro') || $MWXS_L->is_plugin_active('woocommerce-sequential-order-numbers')):?>
		<?php
			$is_compt = true;
			$is_order_num_compt = true;
			$son_p_n = 'WooCommerce Sequential Order Numbers Pro';
			$son_p_f = 'woocommerce-sequential-order-numbers-pro';
			if(!$MWXS_L->is_plugin_active($son_p_f)){
				$son_p_n = 'WooCommerce Sequential Order Numbers';
				$son_p_f = 'woocommerce-sequential-order-numbers';
			}
		?>
		<div class="page_title">
			<h4 title="<?php echo $MWXS_L->escape($son_p_f);?>"><?php echo $MWXS_L->escape($son_p_n);?></h4>
		</div>

		<div class="card">
			<div class="card-content">
				<div class="col s12 m12 l12">
					<div class="myworks-wc-qbo-sync-table-responsive">
						<table class="mw-qbo-sync-settings-table menu-blue-bg" width="100%">
							<tr>
								<td colspan="3">
									<b><?php _e( 'Settings', 'myworks-sync-for-xero' );?></b>
								</td>
							</tr>

							<tr>
								<td width="60%"><?php echo $MWXS_L->escape( 'Enable '.$son_p_n.' Support');?>:</td>
								<td>
									<?php myworks_woo_sync_for_xero_compt_page_option_check_f('compt_p_wsnop');?>
								</td>
								<td>
									<?php myworks_woo_sync_for_xero_set_tooltip('When enabled, orders will sync into QuickBooks using the "pretty" order number created by '.$son_p_n.' - instead of the WooCommerce Order ID.');?>
								</td>
							</tr>
							
							<tr>
								<td colspan="3">
									<input type="submit" name="comp_wsnop" class="waves-effect waves-light btn save-btn mw-qbo-sync-green" value="Save">
								</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
		<?php endif;?>

		<!--If No Compatibility-->
		<?php if(!$is_compt):?>
		<table width="100%">
			<tr>
				<td colspan="3">
					<b><?php _e( 'No Compatibility Found.', 'myworks-sync-for-xero' );?></b>
				</td>
			</tr>
		</table>
		<?php endif;?>
		
	</form>
</div>

<script type="text/javascript">
	jQuery(document).ready(function($){
		/*Bootstrap Switch*/
		$('input.mwqs_st_chk').attr('data-size','small');
		$('input.mwqs_st_chk').bootstrapSwitch();
	});
</script>

<?php 
	if(!empty($compt_save_status)){
		myworks_woo_sync_for_xero_set_admin_sweet_alert($compt_save_status);
	}
?>