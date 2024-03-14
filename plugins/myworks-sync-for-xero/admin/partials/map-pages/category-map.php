<?php
if(!defined( 'ABSPATH' )){
	exit;
}

global $wpdb;
$page_url = $UP.'category';

$table = $MWXS_L->gdtn('map_categories');

# POST Action
if ( ! empty( $_POST ) && check_admin_referer( 'myworks_wc_xero_sync_map_wc_xero_category', 'map_wc_xero_category' ) ) {    
    $is_mapping_data_saved = false;
    foreach ($_POST as $key=>$value){
        $key = $MWXS_L->sanitize($key);
		$value = $MWXS_L->array_sanitize($value);

        if ($MWXS_L->start_with($key, "map_product_")){
            $id = (int) str_replace("map_product_", "", $key);
            if($id > 0){
                $save_data = array();
                $save_data['X_P_ID'] = $MWXS_L->var_p('map_product_'.$id,'');
                $save_data['X_ACC_CODE'] = $MWXS_L->var_p('map_account_'.$id,'');

                if($MWXS_L->get_field_by_val($table,'id','W_CAT_ID',$id)){
                    $wpdb->update($table,$save_data,array('W_CAT_ID'=>$id),'',array('%d'));
                }else{
                    $save_data['W_CAT_ID'] = $id;
				    $wpdb->insert($table, $save_data);
                }

                $is_mapping_data_saved = true;
            }
        }        
    }

    if($is_mapping_data_saved){
        $MWXS_L->set_session_val('map_page_update_message',__('Categories mapped successfully.','myworks-sync-for-xero'));
    }

    $wpdb->query("DELETE FROM `".$table."` WHERE `X_P_ID` = '' AND `X_ACC_CODE` = '' ");
    $MWXS_L->redirect($page_url);
}

$wc_cat_list = $MWXS_L->get_wc_product_cat_arr();
#$MWXS_L->_p($wc_cat_list);
$total_records = (is_array($wc_cat_list))?count($wc_cat_list):0;

$is_ajax_dd = $MWXS_L->is_s2_ajax_dd();
# Xero Data
$MWXS_L->xero_connect();

$xero_products_options = '';
if(!$is_ajax_dd){
	$xpsb = 'Name';	
}

$xaa = $MWXS_L->xero_get_accounts_kva();

# JS
$s_o_s_arr = array();

$cmd_kva = array();
$category_map_data = $MWXS_L->get_tbl($table);

if(is_array($category_map_data) && !empty($category_map_data)){
    foreach($category_map_data as $cmd){
        if(!empty($cmd['X_P_ID']) || !empty($cmd['X_ACC_CODE'])){
            $cmd_kva[$cmd['W_CAT_ID']] = $cmd;
        }
    }
}
#$MWXS_L->_p($cmd_kva);

require_once plugin_dir_path( __FILE__ ) . 'map-nav.php';
?>

<div class="container map-product-responsive">
    <div class="page_title">
		<h4><?php _e( 'Category Mappings', 'myworks-sync-for-xero' );?></h4>
	</div>
    
    <br>
	<div class="card">
		<div class="card-content">
			<div class="row">
				<form method="POST" class="col s12 m12 l12">
					<div class="row">
						<div class="col s12 m12 l12">
							<div class="myworks-wc-qbo-sync-table-responsive">
								<table class="mw-qbo-sync-settings-table menu-blue-bg menu-bg-a new-table">
									<thead>
										<tr>
											<th width="10%">&nbsp; <?php _e( 'ID', 'myworks-sync-for-xero' );?></th>
											<th width="30%">WooCommerce Category</th>

                                            <th width="30%" class="title-description mwxs_tsns">
												<?php _e( 'Xero Product', 'myworks-sync-for-xero' );?>
											</th>

											<th width="30%" class="title-description mwxs_tsns">
												<?php _e( 'Xero Account', 'myworks-sync-for-xero' );?>
											</th>
                                        </tr>
                                    </thead>

                                    <tbody>
									<?php if(!empty($wc_cat_list)):?>
									<?php foreach($wc_cat_list as $k => $v):?>
                                        <tr>
                                            <td><?php echo (int) $k;?></td>
                                            <td><?php echo  $MWXS_L->escape($v)?></td>
                                            <?php
                                                $data = array(
                                                    'ID' => (int) $k,
                                                    'X_ItemID' => '',
                                                    'X_Name' => '',
                                                );
                                                
                                                if(is_array($cmd_kva) && !empty($cmd_kva) && isset($cmd_kva[$data['ID']])){
                                                    $data['X_ItemID'] = $cmd_kva[$data['ID']]['X_P_ID'];
                                                    if(!empty($data['X_ItemID'])){
                                                        $data['X_Name'] = $MWXS_L->get_field_by_val($MWXS_L->gdtn('products'),'Name','ItemID',$data['X_ItemID']);
                                                    }

                                                    if(!empty($cmd_kva[$data['ID']]['X_ACC_CODE'])){
                                                        $s_o_s_arr['#map_account_'.$data['ID']] = $cmd_kva[$data['ID']]['X_ACC_CODE'];
                                                    }
                                                }
                                                
												$dd_ext_class = '';												
												if($is_ajax_dd){
													$dd_ext_class = 'mwqs_dynamic_select';													
												}else{													
													if(!empty($data['X_ItemID'])){														
														$s_o_s_arr['#map_product_'.$data['ID']] = $data['X_ItemID'];
													}
												}											
												
											?>
											
											<td>
												<select class="mw_wc_qbo_sync_select2 <?php echo esc_attr($dd_ext_class);?>" name="map_product_<?php echo esc_attr($data['ID'])?>" id="map_product_<?php echo esc_attr($data['ID'])?>">													
													<?php 
														if($is_ajax_dd){
															if(!empty($data['X_ItemID'])){
																echo '<option value="'.$MWXS_L->escape($data['X_ItemID']).'">'.stripslashes($MWXS_L->escape($data['X_Name'])).'</option>';
															}else{
																echo '<option value=""></option>';
															}
														}else{
															echo '<option value=""></option>';
															$MWXS_L->option_html('', $MWXS_L->gdtn('products'),'ItemID','Name','',$xpsb.' ASC');
														}
													?>
												</select>
											</td>
											
											<td>
												<select class="mw_wc_qbo_sync_select2" name="map_account_<?php echo esc_attr($data['ID'])?>" id="map_account_<?php echo esc_attr($data['ID'])?>">
													<option value=""></option>
													<?php $MWXS_L->only_option('', $xaa);?>
												</select>
											</td>
                                        </tr>
                                    <?php endforeach;?>
									<?php else:?>
										<tr>
											<td colspan="4">
												<span class="mwxs_tnd">
													<?php _e( 'No categories found.', 'myworks-sync-for-xero' );?>
												</span>
											</td>
										</tr>
									<?php endif;?>
                                    </tbody>
                                </table>
                                
                                <?php if($total_records > 0):?>
                                <!--Pagination-->
                                <div class="mwqs_paginate_div mwqbd_pd">
                                    <div>Showing 1 to <?php echo $MWXS_L->escape($total_records)?> of <?php echo $MWXS_L->escape($total_records)?> entries</div>
                                </div>
                                <?php endif;?>

                            </div>
                        </div>
                    </div>
                    <?php if($total_records > 0):?>
					<div class="row">
						<?php wp_nonce_field( 'myworks_wc_xero_sync_map_wc_xero_category', 'map_wc_xero_category' ); ?>
						<div class="input-field col s12 m6 l4">
							<button class="waves-effect waves-light btn save-btn mw-qbo-sync-green">
								<?php _e( 'Save', 'myworks-sync-for-xero' );?>
							</button>
						</div>
					</div>
					<?php endif;?>
                </form>
            </div>
        </div>
    </div>

</div>

<?php myworks_woo_sync_for_xero_get_tablesorter_js();?>
<script type="text/javascript">
    jQuery(document).ready(function($){
        <?php 
			if(is_array($s_o_s_arr) && !empty($s_o_s_arr)){
				foreach($s_o_s_arr as $k => $v){
					echo '$(\''.$MWXS_L->escape($k).'\').val(\''.$MWXS_L->escape($v).'\');';
				}
			}
		?>
    });
</script>

<?php myworks_woo_sync_for_xero_get_select2_js('.mw_wc_qbo_sync_select2','xero_product');?>