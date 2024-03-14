<?php
global $wp;
if( isset( $_GET['delplusmem'] ) )
{
	$rtwwdpdl_products_option = get_option( 'rtwwdpdl_add_member' );
	$rtwwdpdl_row_no = sanitize_text_field( $_GET['delplusmem'] );
	array_splice( $rtwwdpdl_products_option, $rtwwdpdl_row_no, 1 );
	update_option( 'rtwwdpdl_add_member', $rtwwdpdl_products_option );
	$rtwwdpdl_new_url = esc_url( admin_url().'admin.php?page=rtwwdpdl&rtwwdpdl_tab=rtwwdpdl_plus_member' );
	header('Location: '. $rtwwdpdl_new_url);

    die();
}

if(isset($_POST['rtwwdpdl_add_member'])){
	if( isset( $_POST['rtwwdpd_plusm_field'] ) && wp_verify_nonce( $_POST['rtwwdpd_plusm_field'], 'rtwwdpd_plusm' ) ) 
	{
		$rtwwdpdl_prod = $_POST;
		$rtwwdpdl_option_no = sanitize_text_field( $rtwwdpdl_prod['edit_plusmem'] );

		$rtwwdpdl_products_option = get_option('rtwwdpdl_add_member');
		if($rtwwdpdl_products_option == '')
		{
			$rtwwdpdl_products_option = array();
		}
		$rtwwdpdl_products = array();
		$rtwwdpdl_products_array = array();

		foreach($rtwwdpdl_prod as $key => $val){
			$rtwwdpdl_products[$key] = $val;
		}
		if($rtwwdpdl_option_no != 'save'){
			$rtw_edit_row = isset( $_REQUEST['edit_plusmem'] ) ? sanitize_text_field( $_REQUEST['edit_plusmem'] ) : '';
			unset( $rtw_edit_row );
			$rtwwdpdl_products_option[$rtwwdpdl_option_no] = $rtwwdpdl_products;
		}
		else{
			$rtwwdpdl_products_option[] = $rtwwdpdl_products;
		}
		update_option('rtwwdpdl_add_member',$rtwwdpdl_products_option);

		?>
		<div class="notice notice-success is-dismissible">
			<p><strong><?php esc_html_e('Settings saved.','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></strong></p>
			<button type="button" class="notice-dismiss">
				<span class="screen-reader-text"><?php esc_html_e('Dismiss this notices.','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></span>
			</button>
		</div>
	<?php
	}
	else {
		esc_html_e( 'Sorry, your are not allowed to access this page.' , 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' );
   		exit;
	}
}

if(isset($_GET['edit_plusmem']))
{ 
	$rtwwdpdl_url = esc_url( admin_url('admin.php').add_query_arg($_GET,$wp->request));
	
	$rtwwdpdl_prev_opt = get_option('rtwwdpdl_add_member');
	$rtwwdpdl_prev_prod = $rtwwdpdl_prev_opt[ sanitize_text_field( $_GET['edit_plusmem'] ) ];
	$key = 'edit_plusmem';
	$filteredURL = preg_replace('~(\?|&)'.$key.'=[^&]*~', '$1', $rtwwdpdl_url);
	$rtwwdpdl_new_url = esc_url( admin_url().'admin.php?page=rtwwdpdl&rtwwdpdl_tab=rtwwdpdl_plus_member');
?>

<div class="rtwwdpdl_add_combi_rule_tab rtwwdpdl_active rtwwdpdl_form_layout_wrapper">
	<form action="<?php echo esc_url($rtwwdpdl_new_url); ?>" method="POST" accept-charset="utf-8">
		<?php wp_nonce_field( 'rtwwdpd_plusm', 'rtwwdpd_plusm_field' ); ?>
		<div id="woocommerce-product-data" class="postbox ">
			<div class="options_group rtwwdpdl_active" id="rtwwdpdl_plus">
				<input type="hidden" name="edit_plusmem" id="edit_plusmem" value="<?php echo esc_attr( sanitize_text_field( $_GET['edit_plusmem'] )); ?>">
        			<table class='rtw_plus_member'>
	        			<tr>
	            		<th class="tr1"><?php esc_html_e('Minimum Previous Orders', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite');?></th>
							<td class="tr2">
								<input type="number" name="rtwwdpdl_min_orders"
								value="<?php echo esc_attr( $rtwwdpdl_prev_prod['rtwwdpdl_min_orders'] ); ?>" min="0" />
								<div class="descr"><?php esc_html_e('Minimum number of previous orders done by a customer to be eligible to become a plus member.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite');?></div>
							</td>
						</tr>
						<tr>
							<th class="tr1"><?php esc_html_e('Minimum Purchase Amount', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite');?></th>
							<td class="tr2">
								<input type="number" name="rtwwdpdl_purchase_amt"
								value="<?php echo esc_attr(isset($rtwwdpdl_prev_prod['rtwwdpdl_purchase_amt']) ? $rtwwdpdl_prev_prod['rtwwdpdl_purchase_amt'] : ''); ?>" min="0" />
								<div class="descr"><?php esc_html_e('Minimum amount spent by a customer to be eligible to become a plus member.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite');?></div>
							</td>
						</tr>
						<tr>
							<th class="tr1"><?php esc_html_e('Minimum Purschased Products', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite');?></th>
							<td class="tr2">
								<input type="number" name="rtwwdpdl_purchase_prodt"
								value="<?php echo esc_attr($rtwwdpdl_prev_prod['rtwwdpdl_purchase_prodt']); ?>" min="0" />
								<div class="descr"><?php esc_html_e('Minimum number of purchased product done by a customer to be eligible to become a plus member.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite');?></div>
							</td>
						</tr>
					
						<?php
		            	global $wp_roles;
		            	$rtwwdpdl_roles 	= $wp_roles->get_names();
		            	$rtwwdpdl_role_all 	= esc_html__( 'All', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' );
		            	$rtwwdpdl_roles 	= array_merge( array( 'all' => $rtwwdpdl_role_all ), $rtwwdpdl_roles );
		            	$rtwwdpdl_selected_role =  $rtwwdpdl_prev_prod['rtwwdpdl_roles'];
	            		?>
            		
						<tr>
							<th class="tr1"><?php esc_html_e('Allowed Roles', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite');?></th>
							<td class="tr2">
								<select multiple="multiple" class="rtwwdpdl_select_roles" name="rtwwdpdl_roles[]">
									
									<?php
									foreach ($rtwwdpdl_roles as $roles => $role) {
										if(is_array($rtwwdpdl_selected_role) && !empty($rtwwdpdl_selected_role))
										{
											?>
											<option value="<?php echo esc_attr($roles); ?>"<?php
											foreach ($rtwwdpdl_selected_role as $ids => $roleid) {
												selected($roles, $roleid);
											}
											?> >
											<?php esc_html_e( $role, 'rtwwdpd-woo-dynamic-pricing-discounts-with-ai' ); ?>
											</option>
											<?php
										}
										else{
											?>
											<option value="<?php echo esc_attr($roles); ?>">
												<?php esc_html_e( $role, 'rtwwdpd-woo-dynamic-pricing-discounts-with-ai' ); ?>
											</option>
											<?php
										}
									}
									?>
								</select>
								<div class="descr"><?php esc_html_e('Role of the customer to become plus member', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite');?></div>
							</td>
						</tr>
						<tr>
							<th class="tr1"><?php esc_html_e('User is registered for', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite');?></th>
							<td class="tr2">
								<select name="rtw_user_regis_for">
									<option <?php selected($rtwwdpdl_prev_prod['rtw_user_regis_for'], 'less3mnth') ?> value="less3mnth"><?php esc_html_e('Less than 3 months', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite');?></option>
									<option <?php selected($rtwwdpdl_prev_prod['rtw_user_regis_for'], 'more3mnth') ?>  value="more3mnth"><?php esc_html_e('More than 3 months', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite');?></option>
									<option <?php selected($rtwwdpdl_prev_prod['rtw_user_regis_for'], 'frm6mnth') ?> value="more6mnth"><?php esc_html_e('More than 6 months', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite');?></option>
									<option <?php selected($rtwwdpdl_prev_prod['rtw_user_regis_for'], 'more1yr') ?> value="more1yr"><?php esc_html_e('More than 1 year', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite');?></option>
								</select>
								<div class="descr"><?php esc_html_e('User registered for minimum this time to become a plus member.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite');?></div>
							</td>
						</tr>
					</table>
				</div>
			</div>
			<div class="rtwwdpdl_prod_single_save_cancel">
				<input class="rtw-button rtwwdpdl_save_member" type="submit" name="rtwwdpdl_add_member" value="<?php esc_attr_e( 'Update Rule', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>" />
				<input class="rtw-button rtwwdpdl_cancel_rule" type="submit" name="rtwwdpdl_cancel_add_mem" value="<?php esc_attr_e( 'Cancel', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>" />
			</div>
		</form>
	</div>
<?php }
else {?>
<div class="rtwwdpdl_add_combi_rule_tab rtwwdpdl_form_layout_wrapper">
	<form action="" method="POST" accept-charset="utf-8">
		<?php wp_nonce_field( 'rtwwdpd_plusm', 'rtwwdpd_plusm_field' ); ?>
		<div id="woocommerce-product-data" class="postbox ">
			<div class="options_group rtwwdpdl_actives" id="rtwwdpdl_plus">
				<input type="hidden" name="edit_plusmem" id="edit_plusmem" value="save">
         		<table class='rtw_plus_member'>
         			<tr>
            			<th class="tr1"><?php esc_html_e('Minimum Previous Orders', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite');?></th>
						<td class="tr2">
							<input type="number" name="rtwwdpdl_min_orders"
							value="0" min="0" />
							<div class="descr"><?php esc_html_e('Minimum number of previous orders done by a customer to be eligible to become a plus member.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite');?></div>
						</td>
					</tr>
					<tr>
						<th class="tr1"><?php esc_html_e('Minimum Purchase Amount', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite');?></th>
						<td class="tr2">
							<input type="number" name="rtwwdpdl_purchase_amt"
							value="0" min="0" />
							<div class="descr"><?php esc_html_e('Minimum amount spent by a customer to be eligible to become a plus member.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite');?></div>
						</td>
					</tr>
					<tr>
						<th class="tr1"><?php esc_html_e('Minimum Purschased Products', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite');?></th>
						<td class="tr2">
							<input type="number" name="rtwwdpdl_purchase_prodt"
							value="0" min="0" />
							<div class="descr"><?php esc_html_e('Minimum number of purchased product done by a customer to be eligible to become a plus member.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite');?></div>
						</td>
					</tr>
						<?php
	            	global $wp_roles;
	            	$rtwwdpdl_roles 	= $wp_roles->get_names();
	            	$rtwwdpdl_role_all 	= esc_html__( 'All', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' );
	            	$rtwwdpdl_roles 	= array_merge( array( 'all' => $rtwwdpdl_role_all ), $rtwwdpdl_roles );
	            		?>
	            		
					<tr>
						<th class="tr1"><?php esc_html_e('Allowed Roles', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite');?></th>
						<td class="tr2">
							<select multiple="multiple" class="rtwwdpdl_select_roles" name="rtwwdpdl_roles[]">
								<?php
								foreach ( $rtwwdpdl_roles as $key => $value ) 
								{
									?>
									<option value="<?php echo esc_attr($key);?>">
										<?php echo esc_html( $value);?>
									</option>
									<?php
								}
								?>
							</select>
							<div class="descr"><?php esc_html_e('Role of the customer to become plus member', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite');?></div>
						</td>
					</tr>
					<tr>
						<th class="tr1"><?php esc_html_e('User is registered for', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite');?></th>
						<td class="tr2">
							<select name="rtw_user_regis_for">
								<option value="less3mnth"><?php esc_html_e('Less than 3 months', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite');?></option>
								<option value="more3mnth"><?php esc_html_e('More than 3 months', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite');?></option>
								<option value="more6mnth"><?php esc_html_e('More than 6 months', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite');?></option>
								<option value="more1yr"><?php esc_html_e('More than 1 year', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite');?></option>
							</select>
							<div class="descr"><?php esc_html_e('User registered for minimum this time to become a plus member.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite');?></div>
						</td>
					</tr>
				</table>
			</div>
		</div>
		<div class="rtwwdpdl_prod_single_save_cancel">
			<input class="rtw-button rtwwdpdl_save_member" type="submit" name="rtwwdpdl_add_member" value="<?php esc_attr_e( 'Save Rule', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>" />
			<input class="rtw-button rtwwdpdl_cancel_rule" type="button" name="rtwwdpdl_cancel_add_mem" value="<?php esc_attr_e( 'Cancel', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>" />
		</div>
	</form>
</div>

<?php  }
if(isset($_GET['editplusmem']))
{
	echo '<div class="rtwwdpdl_prod_c_table_edit">';
}
else{
	echo '<div class="rtwwdpdl_prod_c_table">';
}
?>
	<table class="rtwtable table table-striped table-bordered dt-responsive nowrap" cellspacing="0">
		<thead>
			<tr>
		    	<th><?php esc_html_e( 'Rule No.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'Drag', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'Min Order Done', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'Min Purchase Amt', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'Min Purchased Product', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'User Role', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'Registered from', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'Actions', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		  	</tr>
		</thead>
		<?php $rtwwdpdl_products_option = get_option('rtwwdpdl_add_member');
		$rtwwdpdl_absolute_url = esc_url( admin_url('admin.php').add_query_arg( $_GET, $wp->request));
		global $wp_roles;
    	$rtwwdpdl_roles 	= $wp_roles->get_names();
    	$rtwwdpdl_role_all 	= esc_html__( 'All', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' );
    	$rtwwdpdl_roles 	= array_merge( array( 'all' => $rtwwdpdl_role_all ), $rtwwdpdl_roles );
		if(is_array($rtwwdpdl_products_option) && !empty($rtwwdpdl_products_option)){	?>
		<tbody>
			<?php
			foreach ( $rtwwdpdl_products_option as $key => $value ) {
				echo '<tr>';
				echo '<td>'.esc_html( $key+1 ).'</td>';
				echo '<td class="rtw_drag"><img class="rtwdragimg" src="'.esc_url( RTWWDPDL_URL . 'assets/Datatables/images/dragndrop.png').'"/></td>';
				
				echo '<td>'.esc_html( isset( $value['rtwwdpdl_min_orders'] ) ).'</td>';
				
				echo '<td>'.esc_html( isset( $value['rtwwdpdl_purchase_amt'] ) ? $value['rtwwdpdl_purchase_amt'] : '' ).'</td>';
				
				echo '<td>'.esc_html( isset( $value['rtwwdpdl_purchase_prodt'] ) ? $value['rtwwdpdl_purchase_prodt'] : '' ).'</td>';

				echo '<td>';
				if( isset( $value['rtwwdpdl_roles'] ) && is_array( $value['rtwwdpdl_roles'] ) && !empty( $value['rtwwdpdl_roles'] ) )
				{
					foreach ( $value['rtwwdpdl_roles'] as $val )
					{
						echo esc_html( $rtwwdpdl_roles[$val] ).'<br>';
					}
				}
				echo '</td>';
				
				echo '<td>'.esc_html( isset( $value['rtw_user_regis_for'] ) ? $value['rtw_user_regis_for'] : '' ).'</td>';

				echo '<td><a href="'.esc_url( $rtwwdpdl_absolute_url .'&edit_plusmem='.$key ).'"><input type="button" class="rtw_plus_member rtwwdpdl_edit_dt_row" value="Edit" /></a>
						<a href="'.esc_url( $rtwwdpdl_absolute_url .'&delplusmem='.$key ).'"><input type="button" class="rtw_delete_row rtwwdpdl_delete_dt_row" value="Delete"/></a></td>';
				echo '</tr>';
			}
			?>		
		</tbody>
		<?php } ?>
		<tfoot>
			<tr>
		    	<th><?php esc_html_e( 'Rule No.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'Drag', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'Min Order Done', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'Min Purchase Amt', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'Min Purchased Product', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'User Role', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'Registered from', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		    	<th><?php esc_html_e( 'Actions', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></th>
		  	</tr>
		</tfoot>
	</table>
</div>