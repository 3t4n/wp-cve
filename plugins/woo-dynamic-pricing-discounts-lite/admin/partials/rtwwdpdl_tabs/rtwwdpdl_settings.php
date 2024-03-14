<?php
if( isset( $_POST['rtwwdpdl_save_setting'] ) ){
	if( isset( $_POST['rtwwdpdl_setting_field'] ) && wp_verify_nonce( $_POST['rtwwdpdl_setting_field'], 'rtwwdpdl_setting' ) ) 
	{
		$rtwwdpdl_prod = $_POST;
		$rtwwdpdl_products = array();

		foreach( $rtwwdpdl_prod as $key => $val ){
			$rtwwdpdl_products[$key] = sanitize_text_field( $val );
		}
		foreach($rtwwdpdl_prod as $key => $val){
			if( $key == 'rtwwdpdl_enable_message' || $key == 'rtwwdpdl_message_text' || $key == 'rtwwdpdl_message_position' || $key == 'rtwwdpdl_message_pos_propage' )
			{
				$rtwwdpdl_msgs[$key] = $val;
			}else{
				$rtwwdpdl_products[$key] = $val;
			}
		}
		update_option('rtwwdpdl_message_settings',$rtwwdpdl_msgs);
		$rtwwdpdl_products_option = $rtwwdpdl_products;
		update_option('rtwwdpdl_setting_priority',$rtwwdpdl_products_option);

		?><div class="notice notice-success is-dismissible">
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
$rtwwdpdl_codecanyon_link = 'https://codecanyon.net/item/woocommerce-dynamic-pricing-discounts-with-ai/24165502';
?>
<!-- <span class="dashicons dashicons-editor-help"></span> -->
<form method="post" action="" enctype="multipart/form-data">
	<?php  ?>
	<?php wp_nonce_field( 'rtwwdpdl_setting', 'rtwwdpdl_setting_field' ); ?>
	<div class="rtw_setting_order_cls">
		<table id="rtw_setting_tbl">
			<caption class="rtw_set_cap"><b><?php esc_html_e('Set Order for Rules','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></b></caption>
			<thead>
				<tr>
					<th class="rtwtenty"><?php esc_html_e('Set Priority','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></th>
					<th><?php esc_html_e('All Rules','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></th>
					<th><?php esc_html_e('Permission','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></th>
				</tr>
			</thead>
			<tbody id="rtw_set_body_tbl">
				<?php
				$rtwwdpdl_setting_array = array();
				$rtwwdpdl_setting_array = get_option('rtwwdpdl_setting_priority');
				if(!is_array($rtwwdpdl_setting_array) || empty($rtwwdpdl_setting_array))
					{?>
						<tr>
							<td class="rtwupdwn"><img class="rtwdragimg" src="<?php echo esc_url( RTWWDPDL_URL . 'assets/Datatables/images/dragndrop.png' ); ?>"></td>
							<td>
								<?php esc_html_e('Product Rule', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
							</td>
							<td>
								<input name="pro_rule" type='checkbox' value="1"/> 
								<input type="hidden" class="rtwrow_no" value="1" name="pro_rule_row"/>
							</td>
						</tr>
						<tr>
							<td class="rtwupdwn"><img class="rtwdragimg" src="<?php echo esc_url( RTWWDPDL_URL . 'assets/Datatables/images/dragndrop.png' ); ?>"></td>
							<td>
								<?php esc_html_e('Bogo Rule', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
							</td>
							<td>
								<input name="bogo_rule" type='checkbox' value="1"/> 
								<input type="hidden" class="rtwrow_no" value="3" name="bogo_rule_row"/>
							</td>
						</tr>
						<tr>
							<td class="rtwupdwn"><img class="rtwdragimg" src="<?php echo esc_url( RTWWDPDL_URL . 'assets/Datatables/images/dragndrop.png' ); ?>"></td>
							<td>
								<?php esc_html_e('Cart Rule', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
							</td>
							<td>
								<input name="cart_rule" type='checkbox' value="1"/> 
								<input type="hidden" class="rtwrow_no" value="5" name="cart_rule_row"/>
							</td>
						</tr>
						<tr>
							<td class="rtwupdwn"><img class="rtwdragimg" src="<?php echo esc_url( RTWWDPDL_URL . 'assets/Datatables/images/dragndrop.png' ); ?>"></td>
							<td>
								<?php esc_html_e('Category Rule', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
							</td>
							<td>
								<input name="cat_rule" type='checkbox' value="1"/> 
								<input type="hidden" class="rtwrow_no" value="6" name="cat_rule_row"/>
							</td>
						</tr>
						<tr>
							<td class="rtwupdwn"><img class="rtwdragimg" src="<?php echo esc_url( RTWWDPDL_URL . 'assets/Datatables/images/dragndrop.png' ); ?>"></td>
							<td>
								<?php esc_html_e('Tiered Rule', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
							</td>
							<td>
								<input name="tier_rule" type='checkbox' value="1"/>
								<input type="hidden" class="rtwrow_no" value="8" name="tier_rule_row"/> 
							</td>
						</tr>
						<tr>
							<td class="rtwupdwn"><img class="rtwdragimg" src="<?php echo esc_url( RTWWDPDL_URL . 'assets/Datatables/images/dragndrop.png' ); ?>"></td>
							<td>
								<?php esc_html_e('Payment Rule', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
							</td>
							<td>
								<input name="pay_rule" type='checkbox' value="1"/> 
								<input type="hidden" class="rtwrow_no" value="11" name="pay_rule_row"/>
							</td>
						</tr>
						<tr>
							<td class="rtwupdwn"><img class="rtwdragimg" src="<?php echo esc_url( RTWWDPDL_URL . 'assets/Datatables/images/dragndrop.png' ); ?>"></td>
							<td>
								<?php esc_html_e('Product Combination Rule ', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
								<span class="rtwwdpdl_pro_text"><?php esc_html_e('(Available in pro version)', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?><a target="_blank" href="<?php echo esc_url( $rtwwdpdl_codecanyon_link ); ?>"><?php esc_html_e(' Get it now','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></a></span>
							</td>
							<td>
								<input disabled="disabled" name="pro_com_rule" type='checkbox' value="1"/> 
								<input type="hidden" class="rtwrow_no" value="2" name="pro_com_rule_row"/>
							</td>
						</tr>
						<tr>
							<td class="rtwupdwn"><img class="rtwdragimg" src="<?php echo esc_url( RTWWDPDL_URL . 'assets/Datatables/images/dragndrop.png' ); ?>"></td>
							<td>
								<?php esc_html_e('Category Combination Rule', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
								<span class="rtwwdpdl_pro_text"><?php esc_html_e('(Available in pro version)', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?><a target="_blank" href="<?php echo esc_url( $rtwwdpdl_codecanyon_link ); ?>"><?php esc_html_e(' Get it now','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></a></span>
							</td>
							<td>
								<input disabled="disabled" name="cat_com_rule" type='checkbox' value="1"/> 
								<input type="hidden" class="rtwrow_no" value="7" name="cat_com_rule_row"/>
							</td>
						</tr>
						<tr>
							<td class="rtwupdwn"><img class="rtwdragimg" src="<?php echo esc_url( RTWWDPDL_URL . 'assets/Datatables/images/dragndrop.png' ); ?>"></td>
							<td>
								<?php esc_html_e('Bogo Category Rule', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
								<span class="rtwwdpdl_pro_text"><?php esc_html_e('(Available in pro version)', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?><a target="_blank" href="<?php echo esc_url( $rtwwdpdl_codecanyon_link ); ?>"><?php esc_html_e(' Get it now','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></a></span>
							</td>
							<td>
								<input disabled="disabled" name="bogo_cat_rule" type='checkbox' value="1"/> 
								<input type="hidden" class="rtwrow_no" value="4" name="bogo_cat_rule_row"/>
							</td>
						</tr>
						<tr>
							<td class="rtwupdwn"><img class="rtwdragimg" src="<?php echo esc_url( RTWWDPDL_URL . 'assets/Datatables/images/dragndrop.png' ); ?>"></td>
							<td>
								<?php esc_html_e('Tier Category Rule', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
								<span class="rtwwdpdl_pro_text"><?php esc_html_e('(Available in pro version)', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?><a target="_blank" href="<?php echo esc_url( $rtwwdpdl_codecanyon_link ); ?>"><?php esc_html_e(' Get it now','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></a></span>
							</td>
							<td>
								<input disabled="disabled" name="tier_cat_rule" type='checkbox' value="1"/> 
								<input type="hidden" class="rtwrow_no" value="9" name="tier_cat_rule_row"/>
							</td>
						</tr>
						<tr>
							<td class="rtwupdwn"><img class="rtwdragimg" src="<?php echo esc_url( RTWWDPDL_URL . 'assets/Datatables/images/dragndrop.png' ); ?>"></td>
							<td>
								<?php esc_html_e('Variation Rule', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
								<span class="rtwwdpdl_pro_text"><?php esc_html_e('(Available in pro version)', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?><a target="_blank" href="<?php echo esc_url( $rtwwdpdl_codecanyon_link ); ?>"><?php esc_html_e(' Get it now','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></a></span>
							</td>
							<td>
								<input disabled="disabled" name="var_rule" type='checkbox' value="1"/> 
								<input type="hidden" class="rtwrow_no" value="10" name="var_rule_row"/>
							</td>
						</tr>
						<tr>
							<td class="rtwupdwn"><img class="rtwdragimg" src="<?php echo esc_url( RTWWDPDL_URL . 'assets/Datatables/images/dragndrop.png' ); ?>"></td>
							<td>
								<?php esc_html_e('Attribute Rule', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
								<span class="rtwwdpdl_pro_text"><?php esc_html_e('(Available in pro version)', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?><a target="_blank" href="<?php echo esc_url( $rtwwdpdl_codecanyon_link ); ?>"><?php esc_html_e(' Get it now','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></a></span>
							</td>
							<td>
								<input disabled="disabled" name="attr_rule" type='checkbox' value="1"/> 
								<input type="hidden" class="rtwrow_no" value="12" name="attr_rule_row"/>
							</td>
						</tr>
						<tr>
							<td class="rtwupdwn"><img class="rtwdragimg" src="<?php echo esc_url( RTWWDPDL_URL . 'assets/Datatables/images/dragndrop.png' ); ?>"></td>
							<td>
								<?php esc_html_e('Product Tag Rule', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
								<span class="rtwwdpdl_pro_text"><?php esc_html_e('(Available in pro version)', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?><a target="_blank" href="<?php echo esc_url( $rtwwdpdl_codecanyon_link ); ?>"><?php esc_html_e(' Get it now','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></a></span>
							</td>
							<td>
								<input disabled="disabled" name="prod_tag_rule" type='checkbox' value="1"/> 
								<input type="hidden" class="rtwrow_no" value="13" name="prod_tag_rule_row"/>
							</td>
						</tr>
						<tr>
							<td class="rtwupdwn"><img class="rtwdragimg" src="<?php echo esc_url( RTWWDPDL_URL . 'assets/Datatables/images/dragndrop.png' ); ?>"></td>
							<td>
								<?php esc_html_e('Shipping Rule', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
								<span class="rtwwdpdl_pro_text"><?php esc_html_e('(Available in pro version)', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?><a target="_blank" href="<?php echo esc_url( $rtwwdpdl_codecanyon_link ); ?>"><?php esc_html_e(' Get it now','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></a></span>
							</td>
							<td>
								<input disabled="disabled" name="ship_rule" type='checkbox' value="1"/> 
								<input type="hidden" class="rtwrow_no" value="14" name="ship_rule_row"/>
							</td>
						</tr>
					<?php }else{
						foreach ($rtwwdpdl_setting_array as $key => $value) {
							if($value == 'on'){
								$checked = 'checked';
							}
							else{
								$checked ='';
							}
							echo '<tr>';

							if($key == 'var_rule_row'){
								echo '<td class="rtwupdwn"><img class="rtwdragimg" src="'.esc_url( RTWWDPDL_URL . 'assets/Datatables/images/dragndrop.png').'"></td>';
								echo '<td>' .esc_html__('Variation Rule ', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite').'<span class="rtwwdpdl_pro_text">'.esc_html__( '(Available in pro version)', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ).'<a target="_blank" href="'.esc_url( $rtwwdpdl_codecanyon_link ).'">'.esc_html__( ' Get it now', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ).'</a></span></td>'; 

								echo '<td><input disabled="disabled" name="var_rule" type="checkbox" value="1" ';
								if(isset($rtwwdpdl_setting_array['var_rule']) && $rtwwdpdl_setting_array['var_rule'] == 1){
									echo 'checked';
								} 
								echo '/>';

								echo '<input type="hidden" class="rtwrow_no" value="" name="var_rule_row"/></td>';

							}
							elseif($key == 'tier_cat_rule_row'){
								echo '<td class="rtwupdwn"><img class="rtwdragimg" src="'.esc_url( RTWWDPDL_URL . 'assets/Datatables/images/dragndrop.png').'"></td>';
								echo '<td>' .esc_html__('Tier Category Rule ', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite').'<span class="rtwwdpdl_pro_text">'.esc_html__( '(Available in pro version)', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ).'<a target="_blank" href="'.esc_url( $rtwwdpdl_codecanyon_link ).'">'.esc_html__( ' Get it now', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ).'</a></span></td>'; 

								echo '<td><input disabled="disabled" name="tier_cat_rule" type="checkbox" value="1" ';
								if(isset($rtwwdpdl_setting_array['tier_cat_rule']) && $rtwwdpdl_setting_array['tier_cat_rule'] == 1){
									echo 'checked';
								} 
								echo '/>';

								echo '<input type="hidden" class="rtwrow_no" value="" name="tier_cat_rule_row"/></td>';

							}
							elseif($key == 'tier_rule_row'){
								echo '<td class="rtwupdwn"><img class="rtwdragimg" src="'.esc_url( RTWWDPDL_URL . 'assets/Datatables/images/dragndrop.png').'"></td>';
								echo '<td>' .esc_html__('Tiered Rule', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'). '</td>';

								echo '<td><input name="tier_rule" type="checkbox"  value="1" ';
								if(isset($rtwwdpdl_setting_array['tier_rule']) && $rtwwdpdl_setting_array['tier_rule'] == 1){
									echo 'checked';
								} 
								echo '/>';

								echo '<input type="hidden" class="rtwrow_no" value="" name="tier_rule_row"/></td>';

							}
							elseif($key == 'cat_com_rule_row'){
								echo '<td class="rtwupdwn"><img class="rtwdragimg" src="'.esc_url( RTWWDPDL_URL . 'assets/Datatables/images/dragndrop.png').'"></td>';
								echo '<td>' .esc_html__('Category Combination Rule ', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite').'<span class="rtwwdpdl_pro_text">'.esc_html__( '(Available in pro version)', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ).'<a target="_blank" href="'.esc_url( $rtwwdpdl_codecanyon_link ).'">'.esc_html__( ' Get it now', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ).'</a></span></td>'; 

								echo '<td><input disabled="disabled" name="cat_com_rule" type="checkbox" value="1" ';
								if(isset($rtwwdpdl_setting_array['cat_com_rule']) && $rtwwdpdl_setting_array['cat_com_rule'] == 1){
									echo 'checked';
								} 
								echo '/>';

								echo '<input type="hidden" class="rtwrow_no" value="" name="cat_com_rule_row"/></td>';

							}
							elseif($key == 'cat_rule_row'){
								echo '<td class="rtwupdwn"><img class="rtwdragimg" src="'.esc_url( RTWWDPDL_URL . 'assets/Datatables/images/dragndrop.png').'"></td>';
								echo '<td>' .esc_html__('Category Rule', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'). '</td>';

								echo '<td><input name="cat_rule" type="checkbox" value="1" ';
								if(isset($rtwwdpdl_setting_array['cat_rule']) && $rtwwdpdl_setting_array['cat_rule'] == 1){
									echo 'checked';
								} 
								echo '/>';

								echo '<input type="hidden" class="rtwrow_no" value="" name="cat_rule_row"/></td>';

							}
							elseif($key == 'cart_rule_row'){
								echo '<td class="rtwupdwn"><img class="rtwdragimg" src="'.esc_url( RTWWDPDL_URL . 'assets/Datatables/images/dragndrop.png').'"></td>';
								echo '<td>' .esc_html__('Cart Rule', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'). '</td>';

								echo '<td><input name="cart_rule" type="checkbox" value="1" ';
								if(isset($rtwwdpdl_setting_array['cart_rule']) && $rtwwdpdl_setting_array['cart_rule'] == 1){
									echo 'checked';
								} 
								echo '/>';

								echo '<input type="hidden" class="rtwrow_no" value="" name="cart_rule_row"/></td>';

							}
							elseif($key == 'bogo_cat_rule_row'){
								echo '<td class="rtwupdwn"><img class="rtwdragimg" src="'.esc_url( RTWWDPDL_URL . 'assets/Datatables/images/dragndrop.png').'"></td>';
								echo '<td>' .esc_html__('Bogo Category Rule ', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite').'<span class="rtwwdpdl_pro_text">'.esc_html__( '(Available in pro version)', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ).'<a target="_blank" href="'.esc_url( $rtwwdpdl_codecanyon_link ).'">'.esc_html__( ' Get it now', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ).'</a></span></td>';  

								echo '<td><input disabled="disabled" name="bogo_cat_rule" type="checkbox" value="1" ';
								if(isset($rtwwdpdl_setting_array['bogo_cat_rule']) && $rtwwdpdl_setting_array['bogo_cat_rule'] == 1){
									echo 'checked';
								} 
								echo '/>';

								echo '<input type="hidden" class="rtwrow_no" value="" name="bogo_cat_rule_row"/></td>';

							}
							elseif($key == 'bogo_rule_row'){
								echo '<td class="rtwupdwn"><img class="rtwdragimg" src="'.esc_url( RTWWDPDL_URL . 'assets/Datatables/images/dragndrop.png').'"></td>';
								echo '<td>' .esc_html__('Bogo Rule', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'). '</td>';

								echo '<td><input name="bogo_rule" type="checkbox" value="1" ';
								if(isset($rtwwdpdl_setting_array['bogo_rule']) && $rtwwdpdl_setting_array['bogo_rule'] == 1){
									echo 'checked';
								} 
								echo '/>';

								echo '<input type="hidden" class="rtwrow_no" value="" name="bogo_rule_row"/></td>';

							}
							elseif($key == 'pro_com_rule_row'){
								echo '<td class="rtwupdwn"><img class="rtwdragimg" src="'.esc_url( RTWWDPDL_URL . 'assets/Datatables/images/dragndrop.png').'"></td>';
								echo '<td>' . esc_html__('Product Combination Rule ', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite').'<span class="rtwwdpdl_pro_text">'. esc_html__( '(Available in pro version)', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ).'<a target="_blank" href="'.esc_url( $rtwwdpdl_codecanyon_link ).'">'.esc_html__( ' Get it now', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ).'</a></span></td>'; 

								echo '<td><input disabled="disabled" name="pro_com_rule" type="checkbox" value="1" ';
								if(isset($rtwwdpdl_setting_array['pro_com_rule']) && $rtwwdpdl_setting_array['pro_com_rule'] == 1){
									echo 'checked';
								} 
								echo '/>';

								echo '<input type="hidden" class="rtwrow_no" value="" name="pro_com_rule_row"/></td>';

							}
							elseif($key == 'pro_rule_row'){
								echo '<td class="rtwupdwn"><img class="rtwdragimg" src="'.esc_url( RTWWDPDL_URL . 'assets/Datatables/images/dragndrop.png').'"></td>';
								echo '<td>' .esc_html__('Product Rule', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'). '</td>';

								echo '<td><input name="pro_rule" type="checkbox" value="1" ';
								if(isset($rtwwdpdl_setting_array['pro_rule']) && $rtwwdpdl_setting_array['pro_rule'] == 1){
									echo 'checked';
								} 
								echo '/>';

								echo '<input type="hidden" class="rtwrow_no" value="" name="pro_rule_row"/></td>';

							}
							elseif($key == 'pay_rule_row'){
								echo '<td class="rtwupdwn"><img class="rtwdragimg" src="'.esc_url( RTWWDPDL_URL . 'assets/Datatables/images/dragndrop.png').'"></td>';
								echo '<td>' .esc_html__('Payment Rule', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'). '</td>';

								echo '<td><input name="pay_rule" type="checkbox" value="1" ';
								if(isset($rtwwdpdl_setting_array['pay_rule']) && $rtwwdpdl_setting_array['pay_rule'] == 1){
									echo 'checked';
								} 
								echo '/>';

								echo '<input type="hidden" class="rtwrow_no" value="" name="pay_rule_row"/></td>';

							}
							elseif($key == 'ship_rule_row'){
								echo '<td class="rtwupdwn"><img class="rtwdragimg" src="'.esc_url( RTWWDPDL_URL . 'assets/Datatables/images/dragndrop.png').'"></td>';
								echo '<td>' . esc_html__('Shipping Rule ', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite').'<span class="rtwwdpdl_pro_text">'. esc_html__( '(Available in pro version)', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ).'<a target="_blank" href="'.esc_url( $rtwwdpdl_codecanyon_link ).'">'.esc_html__( ' Get it now', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ).'</a></span></td>';  

								echo '<td><input disabled="disabled" name="ship_rule" type="checkbox" value="1" ';
								if(isset($rtwwdpdl_setting_array['ship_rule']) && $rtwwdpdl_setting_array['ship_rule'] == 1){
									echo 'checked';
								} 
								echo '/>';

								echo '<input type="hidden" class="rtwrow_no" value="" name="ship_rule_row"/></td>';

							}
							elseif($key == 'prod_tag_rule_row'){
								echo '<td class="rtwupdwn"><img class="rtwdragimg" src="'.esc_url( RTWWDPDL_URL . 'assets/Datatables/images/dragndrop.png').'"></td>';
								echo '<td>' .esc_html__('Product Tag Rule ', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite').'<span class="rtwwdpdl_pro_text">'.esc_html__( '(Available in pro version)', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ).'<a target="_blank" href="'.esc_url( $rtwwdpdl_codecanyon_link ).'">'.esc_html__( ' Get it now', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ).'</a></span></td>';  

								echo '<td><input disabled="disabled" name="prod_tag_rule" type="checkbox" value="1" ';
								if(isset($rtwwdpdl_setting_array['prod_tag_rule']) && $rtwwdpdl_setting_array['prod_tag_rule'] == 1){
									echo 'checked';
								} 
								echo '/>';

								echo '<input type="hidden" class="rtwrow_no" value="" name="prod_tag_rule_row"/></td>';

							}
							elseif($key == 'attr_rule_row'){
								echo '<td class="rtwupdwn"><img class="rtwdragimg" src="'.esc_url( RTWWDPDL_URL . 'assets/Datatables/images/dragndrop.png').'"></td>';
								echo '<td>' . esc_html__('Attribute Rule ', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite').'<span class="rtwwdpdl_pro_text">'. esc_html__( '(Available in pro version)', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ).'<a target="_blank" href="'.esc_url( $rtwwdpdl_codecanyon_link ).'">'.esc_html__( ' Get it now', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ).'</a></span></td>';  

								echo '<td><input disabled="disabled" name="attr_rule" type="checkbox" value="1" ';
								if(isset($rtwwdpdl_setting_array['attr_rule']) && $rtwwdpdl_setting_array['attr_rule'] == 1){
									echo 'checked';
								} 
								echo '/>';

								echo '<input type="hidden" class="rtwrow_no" value="" name="attr_rule_row"/></td>';
							}
							echo '</tr>';
						}
					}
					?>

				</tbody>
				<tfoot>
					<tr>
						<td></td>
						<td></td>
						<td></td>
					</tr>
				</tfoot>
			</table>
		</div>
		<?php $rtwwdpdl_gnrl_set = get_option('rtwwdpdl_setting_priority'); 
		$message_settings = get_option('rtwwdpdl_message_settings', array());
		?>
		<div class="rtw_general_setting_cls rtwwdpdl_active">
			<h2 class="rtwcenter"><b><?php esc_html_e('General Setting','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></b></h2>
			<div id="woocommerce-product-data" class="postbox ">
				<div class="inside">
					<div class="panel-wrap product_data">
						<ul class="product_data_tabs wc-tabs">
							<li class="rtwwdpdl_active active">
								<a class="rtwwdpdl_link" id="rtwgnrl_set">
									<span><?php esc_html_e('General','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></span>
								</a>
							</li>
							<li>
								<a class="rtwwdpdl_link" id="rtwoffer_set">
									<span><?php esc_html_e('Offer','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></span>
								</a>
							</li>
							<li>
								<a class="rtwwdpdl_link" id="rtwbogo_set">
									<span><?php esc_html_e('BOGO','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></span>
								</a>
							</li>
							<li>
								<a class="rtwwdpdl_link" id="rtwmsg_set">
									<span><?php esc_html_e('Custom Message','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></span>
								</a>
							</li>
						</ul>

						<div class="panel woocommerce_options_panel">
							<div class="options_group rtwwdpdl_active" id="rtwgnrl_set_tab">
								<table class="rtwwdpdl_table_edit">
									<tr>
										<td>
											<label><?php esc_html_e('Apply Offer', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
											</label>
										</td>
										<td>
											<select name="rtw_offer_select">
												<option value="rtw_first_match" <?php selected(isset($rtwwdpdl_gnrl_set['rtw_offer_select']) ? $rtwwdpdl_gnrl_set['rtw_offer_select'] : '' , 'rtw_first_match'); ?>>
													<?php esc_html_e('First Matched Rule', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
												</option>
												<option disabled="disabled" value="rtw_best_discount" <?php selected(isset($rtwwdpdl_gnrl_set['rtw_offer_select']) ? $rtwwdpdl_gnrl_set['rtw_offer_select'] :'' , 'rtw_best_discount'); ?>>
													<?php esc_html_e('Best Discount (Pro)', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></option>
												<option disabled="disabled" value="rtw_all_mtch" <?php selected(isset($rtwwdpdl_gnrl_set['rtw_offer_select']) ? $rtwwdpdl_gnrl_set['rtw_offer_select'] : '' , 'rtw_all_mtch');?>><?php esc_html_e('All Matched Rules (Pro)', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></option>
											</select>
											<div class="rtwwdpdl_description">
												<i><?php sprintf( '%s' ,
													esc_html_e( 'Rule to be applied.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' )
												);?>
												</i>
											</div>
										</td>
									</tr>
									<tr>
										<td>
											<label><?php esc_html_e('Rules Per Page', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
											</label>
										</td>
										<td>
											<input type="number" min="1" value="<?php echo isset($rtwwdpdl_gnrl_set['rtwwdpdl_rule_per_page']) ? $rtwwdpdl_gnrl_set['rtwwdpdl_rule_per_page'] : ''; ?>" name="rtwwdpdl_rule_per_page"/>
											<div class="rtwwdpdl_description">
												<i><?php esc_html_e( 'Number Of rules to be shown per page', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ) ?>
												</i>
											</div>
										</td>
									</tr>
									<tr>
										<td>
											<label><?php esc_html_e('Discount On', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
											</label>
										</td>
										<td>
											<select name="rtw_dscnt_on" value="<?php isset($rtwwdpdl_gnrl_set['rtw_dscnt_on']) ? $rtwwdpdl_gnrl_set['rtw_dscnt_on'] : ''; ?>">
												<option value="rtw_sale_price" 
													<?php selected(isset($rtwwdpdl_gnrl_set['rtw_dscnt_on']) ? $rtwwdpdl_gnrl_set['rtw_dscnt_on'] : '' , 'rtw_sale_price');?>>
													<?php esc_html_e('Sale Price', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
												</option>
												<option value="rtw_regular_price" 
													<?php selected(isset($rtwwdpdl_gnrl_set['rtw_dscnt_on']) ? $rtwwdpdl_gnrl_set['rtw_dscnt_on'] : '' , 'rtw_regular_price');?>>
													<?php esc_html_e('Regular Price', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
												</option>
											</select>
											<div class="rtwwdpdl_description">
												<i>
													<?php sprintf( '%s' ,
													esc_html_e( 'Apply discount on sale price/regular price.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ));?>
												</i>
											</div>
										</td>
									</tr>
								</table>
							</div>
							<div class="options_group rtwwdpdl_inactive" id="rtwoffer_set_tab">
								<table class="rtwwdpdl_table_edit">
									<tr>
										<td>
											<label><?php esc_html_e('Display Offer on Shop Page', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
											</label>
										</td>									
										<td>
											<select name="rtw_offer_show">
												<option value="rtw_price_yes" <?php selected( isset($rtwwdpdl_gnrl_set['rtw_offer_show']) ? $rtwwdpdl_gnrl_set['rtw_offer_show'] : '' , 'rtw_price_yes');?>>
													<?php esc_html_e( 'Yes', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite') ?>
												</option>
												<option value="rtw_price_no" <?php selected( isset($rtwwdpdl_gnrl_set['rtw_offer_show']) ? $rtwwdpdl_gnrl_set['rtw_offer_show'] : '' , 'rtw_price_no');?>>
													<?php esc_html_e( 'No', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite') ?>
												</option>
											</select>
											<div class="rtwwdpdl_description">
												<i>
													<?php sprintf( '<u>%s:</u>' ,
														esc_html_e( 'Display offer table on shop page or not.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' )
													)	;?>
												</i>
											</div>
										</td>
									</tr>
									<tr>
										<td>
											<label><?php esc_html_e('Position of Offer Table on Shop Page', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
											</label>
										</td>
										<td>
											<select name="rtwwdpdl_offer_tbl_pos">
												<option value="rtw_bfore_pro" <?php selected( isset($rtwwdpdl_gnrl_set['rtwwdpdl_offer_tbl_pos']) ? $rtwwdpdl_gnrl_set['rtwwdpdl_offer_tbl_pos'] : '' , 'rtw_bfore_pro'); ?>>
													<?php esc_html_e( 'Before Product', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
												</option>
												<option value="rtw_aftr_pro" <?php selected( isset($rtwwdpdl_gnrl_set['rtwwdpdl_offer_tbl_pos']) ? $rtwwdpdl_gnrl_set['rtwwdpdl_offer_tbl_pos'] : '', 'rtw_aftr_pro'); ?>>
													<?php esc_html_e( 'After Product', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
												</option>
												<option value="rtw_bfore_pro_sum" <?php selected( isset($rtwwdpdl_gnrl_set['rtwwdpdl_offer_tbl_pos']) ? $rtwwdpdl_gnrl_set['rtwwdpdl_offer_tbl_pos'] : '', 'rtw_bfore_pro_sum'); ?>>
													<?php esc_html_e( 'Before Product Summary', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
												</option>
												<option value="rtw_in_pro_sum" <?php selected( isset($rtwwdpdl_gnrl_set['rtwwdpdl_offer_tbl_pos']) ? $rtwwdpdl_gnrl_set['rtwwdpdl_offer_tbl_pos'] : '', 'rtw_in_pro_sum'); ?>>
													<?php esc_html_e( 'In Product Summary', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
												</option>
												<option value="rtw_aftr_pro_sum" <?php selected( isset($rtwwdpdl_gnrl_set['rtwwdpdl_offer_tbl_pos']) ? $rtwwdpdl_gnrl_set['rtwwdpdl_offer_tbl_pos'] : '', 'rtw_aftr_pro_sum'); ?>>
													<?php esc_html_e( 'After Product Summary', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
												</option>
											</select>
											<div class="rtwwdpdl_description">
												<i>
													<?php sprintf( '<u>%s:</u>' ,
													esc_html_e( 'Specify price table position on shop page.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' )
													)	;?>
												</i>
											</div>
										</td>
									</tr>
									<tr>
										<td>
											<label><?php esc_html_e('Display Offer on Product Page', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
											</label>
										</td>									
										<td>
											<select name="rtw_offer_on_product">
												<option value="rtw_price_yes" <?php selected( isset($rtwwdpdl_gnrl_set['rtw_offer_on_product']) ? $rtwwdpdl_gnrl_set['rtw_offer_on_product'] : '' , 'rtw_price_yes');?>>
													<?php esc_html_e( 'Yes', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite') ?>
												</option>
												<option value="rtw_price_no" <?php selected( isset($rtwwdpdl_gnrl_set['rtw_offer_on_product']) ? $rtwwdpdl_gnrl_set['rtw_offer_on_product'] : '', 'rtw_price_no');?>>
													<?php esc_html_e( 'No', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite') ?>
												</option>
											</select>
											<div class="rtwwdpdl_description">
												<i>
													<?php sprintf( '<u>%s:</u>' ,
														esc_html_e( 'Display offer table on shop page or not.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' )
													)	;?>
												</i>
											</div>
										</td>
									</tr>
									<tr>
										<td>
											<label><?php esc_html_e('Position of Offer Table on Product Page', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
											</label>
										</td>
										<td>
											<?php 
											if(!isset($rtwwdpdl_gnrl_set['rtwwdpdl_offer_tbl_prodct']))
											{
												$rtwwdpdl_gnrl_set['rtwwdpdl_offer_tbl_prodct'] = '';
											} 
											?>
											<select name="rtwwdpdl_offer_tbl_prodct">
												<option value="rtw_bfore_pro" <?php selected($rtwwdpdl_gnrl_set['rtwwdpdl_offer_tbl_prodct'] , 'rtw_bfore_pro'); ?>>
													<?php esc_html_e( 'Before Product', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
												</option>
												<option value="rtw_aftr_pro" <?php selected($rtwwdpdl_gnrl_set['rtwwdpdl_offer_tbl_prodct'] , 'rtw_aftr_pro'); ?>>
													<?php esc_html_e( 'After Product', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
												</option>
												<option value="rtw_bfore_pro_sum" <?php selected($rtwwdpdl_gnrl_set['rtwwdpdl_offer_tbl_prodct'] , 'rtw_bfore_pro_sum'); ?>>
													<?php esc_html_e( 'Before Product Summary', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
												</option>
												<option value="rtw_in_pro_sum" <?php selected($rtwwdpdl_gnrl_set['rtwwdpdl_offer_tbl_prodct'] , 'rtw_in_pro_sum'); ?>>
													<?php esc_html_e( 'In Product Summary', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
												</option>
												<option value="rtw_aftr_pro_sum" <?php selected($rtwwdpdl_gnrl_set['rtwwdpdl_offer_tbl_prodct'] , 'rtw_aftr_pro_sum'); ?>>
													<?php esc_html_e( 'After Product Summary', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
												</option>
												<option value="rtw_bfre_add_cart_btn" <?php selected($rtwwdpdl_gnrl_set['rtwwdpdl_offer_tbl_prodct'] , 'rtw_bfre_add_cart_btn'); ?>>
													<?php esc_html_e( 'Before Add To Cart Button', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
												</option>
												<option value="rtw_aftr_add_cart_btn" <?php selected($rtwwdpdl_gnrl_set['rtwwdpdl_offer_tbl_prodct'] , 'rtw_aftr_add_cart_btn'); ?>>
													<?php esc_html_e( 'After Add To Cart Button', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
												</option>
												<option value="rtw_bfre_add_cart_frm" <?php selected($rtwwdpdl_gnrl_set['rtwwdpdl_offer_tbl_prodct'] , 'rtw_bfre_add_cart_frm'); ?>>
													<?php esc_html_e( 'Before Add To Cart Form', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
												</option>
												<option value="rtw_aftr_add_cart_frm" <?php selected($rtwwdpdl_gnrl_set['rtwwdpdl_offer_tbl_prodct'] , 'rtw_aftr_add_cart_frm'); ?>>
													<?php esc_html_e( 'After Add To Cart Form', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
												</option>
												<option value="rtw_pro_meta_strt" <?php selected($rtwwdpdl_gnrl_set['rtwwdpdl_offer_tbl_prodct'] , 'rtw_pro_meta_strt'); ?>>
													<?php esc_html_e( 'Product Meta Start', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
												</option>
												<option value="rtw_pro_meta_end" <?php selected($rtwwdpdl_gnrl_set['rtwwdpdl_offer_tbl_prodct'] , 'rtw_pro_meta_end'); ?>>
													<?php esc_html_e( 'Product Meta End', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
												</option>
											</select>
											<div class="rtwwdpdl_description">
												<i>
													<?php sprintf( '<u>%s:</u>' ,
													esc_html_e( 'Specify price table position on shop page.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' )
													)	;?>
												</i>
											</div>
										</td>
									</tr>
									<tr>
										<td>
											<label><?php esc_html_e('Display Cart Offer on cart Page', 'rtwwdpdl-woo-dynamic-pricing-discounts-with-ai'); ?>
											</label>
										</td>									
										<td>
											<select name="rtw_offer_on_cart">
												<option value="rtw_price_yes" <?php selected( isset($rtwwdpdl_gnrl_set['rtw_offer_on_cart']) ? $rtwwdpdl_gnrl_set['rtw_offer_on_cart'] : '' , 'rtw_price_yes'); ?>>
													<?php esc_html_e( 'Yes', 'rtwwdpdl-woo-dynamic-pricing-discounts-with-ai') ?>
												</option>
												<option value="rtw_price_no" <?php selected( isset($rtwwdpdl_gnrl_set['rtw_offer_on_cart']) ? $rtwwdpdl_gnrl_set['rtw_offer_on_cart'] : '', 'rtw_price_no'); ?>>
													<?php esc_html_e( 'No', 'rtwwdpdl-woo-dynamic-pricing-discounts-with-ai') ?>
												</option>
											</select>
											<div class="rtwwdpdl_description">
												<i>
													<?php sprintf( '<u>%s:</u>' ,
														esc_html_e( 'Display offer table on shop page or not.', 'rtwwdpdl-woo-dynamic-pricing-discounts-with-ai' )
													)	;?>
												</i>
											</div>
										</td>
									</tr>
									<tr>
										<td>
											<label><?php esc_html_e('Display Tier Offer on cart Page', 'rtwwdpdl-woo-dynamic-pricing-discounts-with-ai'); ?>
											</label>
										</td>									
										<td>
											<select name="rtw_tier_offer_on_cart">
												<option value="rtw_price_yes" <?php selected( isset($rtwwdpdl_gnrl_set['rtw_tier_offer_on_cart']) ? $rtwwdpdl_gnrl_set['rtw_tier_offer_on_cart'] : '' , 'rtw_price_yes'); ?>>
													<?php esc_html_e( 'Yes', 'rtwwdpdl-woo-dynamic-pricing-discounts-with-ai') ?>
												</option>
												<option value="rtw_price_no" <?php selected( isset($rtwwdpdl_gnrl_set['rtw_tier_offer_on_cart']) ? $rtwwdpdl_gnrl_set['rtw_tier_offer_on_cart'] : '', 'rtw_price_no'); ?>>
													<?php esc_html_e( 'No', 'rtwwdpdl-woo-dynamic-pricing-discounts-with-ai') ?>
												</option>
											</select>
											<div class="rtwwdpdl_description">
												<i>
													<?php sprintf( '<u>%s:</u>' ,
														esc_html_e( 'Display offer table on shop page or not.', 'rtwwdpdl-woo-dynamic-pricing-discounts-with-ai' )
													)	;?>
												</i>
											</div>
										</td>
									</tr>
									<tr>
										<td>
											<label><?php esc_html_e('Show offer as ', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
											<span class="rtwwdpdl_pro_text"><?php esc_html_e('(Pro)', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></span>
											</label>
										</td>
										<td>
											<textarea disabled="disabled" name="rtwwdpdl_text_to_show"><?php echo esc_attr('Get [discounted] Off'); ?></textarea>
											<div class="rtwwdpdl_description"/>
												<i>
													<?php
													esc_html_e( 'ex. Get [discounted] Off', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' );?>
												</i>
												<p><?php esc_html_e('Use ', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
												<b><?php echo esc_html('[discounted]'); ?></b>
												<?php esc_html_e('as shortcode for discounted value.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
												</p>
											</div>
										</td>
									</tr>
									<tr>
										<td>
											<label><?php esc_html_e('Show Tier Rule offer as', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
											<span class="rtwwdpdl_pro_text"><?php esc_html_e('(Pro)', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></span>
											</label>

										</td>
										<td>
											<textarea disabled="disabled" name="rtwwdpdl_tier_text_show"><?php echo esc_attr('Buy [this_product] from [from_quant] to [to_quant] Get [discounted] Off'); ?></textarea>
											<div class="rtwwdpdl_description"/>
												<i>
													<?php
													esc_html_e( 'ex. Buy [this_product] from [from_quant] to [to_quant] Get [discounted] Off', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' );?>
												</i>
												<p><?php esc_html_e('Use ', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
												<b><?php echo esc_html('[this_product], [from_quant], [to_quant], [discounted]'); ?></b>
												<?php esc_html_e('as shortcode for quantity & discounted value.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
												</p>
											</div>
										</td>
									</tr>
									<tr>
										<td>
											<label><?php esc_html_e('Show Cart Rule offer on Cart Page as ', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
											<span class="rtwwdpdl_pro_text"><?php esc_html_e('(Pro)', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></span>
											</label>

										</td>
										<td>
											<textarea disabled="disabled" name="rtwwdpdl_cart_text_show"><?php echo esc_attr( 'Buy from [from_quant] to [to_quant] Get [discounted] Off'); ?></textarea>
											<div class="rtwwdpdl_description"/>
												<i>
													<?php
													esc_html_e( 'ex. Buy from [from_quant] to [to_quant] Get [discounted] Off', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' );?>
												</i>
												<p><?php esc_html_e('Use ', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
												<b><?php echo esc_html('[from_quant], [to_quant], [discounted]'); ?></b>
												<?php esc_html_e('as shortcode for quantity & discounted value.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
												</p>
											</div>
										</td>
									</tr>
								</table>
							</div>
							<div class="options_group rtwwdpdl_inactive" id="rtwbogo_set_tab">
							<table class="rtwwdpdl_table_edit">
								<tr>
									<td>
										<label><?php esc_html_e('Automatically add free products to cart', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
										</label>
									</td>
									<td>
										<select name="rtw_auto_add_bogo">
											<option value="rtw_yes" 
											<?php selected(isset($rtwwdpdl_gnrl_set['rtw_auto_add_bogo']) ? $rtwwdpdl_gnrl_set['rtw_auto_add_bogo'] : '' , 'rtw_yes'); ?>>
												<?php esc_html_e( 'Yes', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
											</option>
											<option value="rtw_no" 
											<?php selected(isset($rtwwdpdl_gnrl_set['rtw_auto_add_bogo']) ? $rtwwdpdl_gnrl_set['rtw_auto_add_bogo'] : '' , 'rtw_no'); ?>>
												<?php esc_html_e( 'No', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
											</option>
										</select>
										<div class="rtwwdpdl_description">
											<i>
												<?php sprintf( '<u>%s:</u>' ,
												esc_html_e( 'Automatically add free product to cart.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' )
											)	;?>
											</i>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<label><?php esc_html_e('Show offer as', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
										<span class="rtwwdpdl_pro_text"><?php esc_html_e('(Pro)', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></span>
										</label>
									</td>
									<td>
										<textarea disabled="disabled" name="rtwwdpdl_bogo_text"><?php echo esc_attr( 'Buy [quantity1] [the-product] Get [quantity2] [free-product]' ); ?></textarea>
										<div class="rtwwdpdl_description">
											<i>
												<?php
												esc_html_e( 'ex. Buy [quantity1] [the-product] Get [quantity2] [free-product]', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' );?>
											</i>
											<p><?php esc_html_e('Use ', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?><b><?php echo esc_html('
											[quantity1], [the-product], [quantity2], [free-product]' ); ?></b>
											<?php esc_html_e('as shortcode for quantity as well as products.', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
											</p>
										</div>
									</td>
								</tr>
							</table>
						</div>
						<div class="options_group rtwwdpdl_inactive" id="rtwmsg_set_tab">
								<caption>
									<b><?php esc_html_e('Show Message to Logged Out Users about your Offers','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></b>
								</caption>
							<table class="rtwwdpdl_table_edit">
								<tbody id="rtw_set_body_tbls">
									<tr>
										<td><label><?php esc_html_e('Enable','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></label></td>
										<td>
											<input <?php checked(isset($message_settings['rtwwdpdl_enable_message']) ? $message_settings['rtwwdpdl_enable_message'] : 0, 1 ); ?> type="checkbox" name="rtwwdpdl_enable_message" value="1">
										</td>
									</tr>
									<tr>
										<td><label><?php esc_html_e('Enter Message','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></label></td>
										<td>
										<?php 
										$message_content = isset( $message_settings['rtwwdpdl_message_text'] ) ? $message_settings['rtwwdpdl_message_text'] : 'Log In to get the best Offers';
										$rtwwdpdl_setting = array(
											'wpautop' => false,
											'media_buttons' => true,
											'textarea_name' => 'rtwwdpdl_message_text',
											'textarea_rows' => 7
										);

										wp_editor( stripcslashes($message_content), 'rtwwdpdl_editor', $rtwwdpdl_setting );
										?>
										</td>
									</tr>
									<tr>
										<td><label><?php esc_html_e('Message Position (on Shop Page)','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></label></td>
										<td>
											<select name="rtwwdpdl_message_position">
												<option value="0" <?php selected( isset( $message_settings['rtwwdpdl_message_pos_propage'] ) ? $message_settings['rtwwdpdl_message_pos_propage'] : '' , '0'); ?>><?php esc_html_e('None','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></option>
												<option <?php selected( isset( $message_settings['rtwwdpdl_message_position'] ) ? $message_settings['rtwwdpdl_message_position'] : 0, 1 ); ?> value="1"><?php esc_html_e('Before Shop Content','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></option>
												<option <?php selected( isset( $message_settings['rtwwdpdl_message_position'] ) ? $message_settings['rtwwdpdl_message_position'] : 0, 2 ); ?> value="2"><?php esc_html_e('After Shop Content','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></option>
												<option <?php selected( isset( $message_settings['rtwwdpdl_message_position'] ) ? $message_settings['rtwwdpdl_message_position'] : 0, 3 ); ?> value="3"><?php esc_html_e('In Archive Description','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></option>
												<option <?php selected( isset( $message_settings['rtwwdpdl_message_position'] ) ? $message_settings['rtwwdpdl_message_position'] : 0, 4 ); ?> value="4"><?php esc_html_e('After Main Content','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></option>
											</select>
										</td>
									</tr>
									<tr>
										<td><label><?php esc_html_e('Message Position (on Product Page)','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></label></td>
										<td>
											<select name="rtwwdpdl_message_pos_propage">
												<option value="0" <?php selected( isset( $message_settings['rtwwdpdl_message_pos_propage'] ) ? $message_settings['rtwwdpdl_message_pos_propage'] : '' , '0'); ?>><?php esc_html_e('None','rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?></option>
												<option value="rtw_bfore_pro" <?php selected( isset( $message_settings['rtwwdpdl_message_pos_propage'] ) ? $message_settings['rtwwdpdl_message_pos_propage'] : '' , 'rtw_bfore_pro'); ?>>
													<?php esc_html_e( 'Before Product', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
												</option>
												<option value="rtw_aftr_pro" <?php selected( isset( $message_settings['rtwwdpdl_message_pos_propage'] ) ? $message_settings['rtwwdpdl_message_pos_propage'] : '' , 'rtw_aftr_pro'); ?>>
													<?php esc_html_e( 'After Product', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
												</option>
												<option value="rtw_bfore_pro_sum" <?php selected( isset( $message_settings['rtwwdpdl_message_pos_propage'] ) ? $message_settings['rtwwdpdl_message_pos_propage'] : '' , 'rtw_bfore_pro_sum'); ?>>
													<?php esc_html_e( 'Before Product Summary', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
												</option>
												<option value="rtw_in_pro_sum" <?php selected( isset( $message_settings['rtwwdpdl_message_pos_propage'] ) ? $message_settings['rtwwdpdl_message_pos_propage'] : '' , 'rtw_in_pro_sum'); ?>>
													<?php esc_html_e( 'In Product Summary', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
												</option>
												<option value="rtw_aftr_pro_sum" <?php selected( isset( $message_settings['rtwwdpdl_message_pos_propage'] ) ? $message_settings['rtwwdpdl_message_pos_propage'] : '' , 'rtw_aftr_pro_sum'); ?>>
													<?php esc_html_e( 'After Product Summary', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
												</option>
												<option value="rtw_bfre_add_cart_btn" <?php selected( isset( $message_settings['rtwwdpdl_message_pos_propage'] ) ? $message_settings['rtwwdpdl_message_pos_propage'] : '' , 'rtw_bfre_add_cart_btn'); ?>>
													<?php esc_html_e( 'Before Add To Cart Button', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
												</option>
												<option value="rtw_aftr_add_cart_btn" <?php selected( isset( $message_settings['rtwwdpdl_message_pos_propage'] ) ? $message_settings['rtwwdpdl_message_pos_propage'] : '' , 'rtw_aftr_add_cart_btn'); ?>>
													<?php esc_html_e( 'After Add To Cart Button', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
												</option>
												<option value="rtw_bfre_add_cart_frm" <?php selected( isset( $message_settings['rtwwdpdl_message_pos_propage'] ) ? $message_settings['rtwwdpdl_message_pos_propage'] : '' , 'rtw_bfre_add_cart_frm'); ?>>
													<?php esc_html_e( 'Before Add To Cart Form', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
												</option>
												<option value="rtw_aftr_add_cart_frm" <?php selected( isset( $message_settings['rtwwdpdl_message_pos_propage'] ) ? $message_settings['rtwwdpdl_message_pos_propage'] : '' , 'rtw_aftr_add_cart_frm'); ?>>
													<?php esc_html_e( 'After Add To Cart Form', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
												</option>
												<option value="rtw_pro_meta_strt" <?php selected( isset( $message_settings['rtwwdpdl_message_pos_propage'] ) ? $message_settings['rtwwdpdl_message_pos_propage'] : '' , 'rtw_pro_meta_strt'); ?>>
													<?php esc_html_e( 'Product Meta Start', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
												</option>
												<option value="rtw_pro_meta_end" <?php selected( isset( $message_settings['rtwwdpdl_message_pos_propage'] ) ? $message_settings['rtwwdpdl_message_pos_propage'] : '' , 'rtw_pro_meta_end'); ?>>
													<?php esc_html_e( 'Product Meta End', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite'); ?>
												</option>
											</select>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<input class="rtw-button rtw_set_btn" type="submit" name="rtwwdpdl_save_setting" value="<?php esc_attr_e( 'Save Rule', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>" />
</form>
