<?php
/** Create/edit otw sidebar
  *
  */
	global $wp_registered_sidebars, $validate_messages, $wp_sml_int_items;
	
	$otw_sidebar_values = array(
		'sbm_loaded'             =>  false,
		'sbm_title'              =>  '',
		'sbm_description'        =>  '',
		'sbm_replace'            =>  '',
		'sbm_status'             =>  'active',
		'sbm_widget_alignment'   =>  'vertical'
	);
	
	$otw_sidebar_id = '';
	
	$page_title = esc_html__( 'Create New Sidebar' );
	
	$sbm_valid_for_values = array();
	
	if( otw_get( 'sidebar', false ) ){
		
		$otw_sidebar_id = otw_get( 'sidebar', '' );
		$otw_sidebars = get_option( 'otw_sidebars' );
		
		if( is_array( $otw_sidebars ) && isset( $otw_sidebars[ $otw_sidebar_id ] ) && ( $otw_sidebars[ $otw_sidebar_id ]['replace'] != '' ) ){
			
			$otw_sidebar_values['sbm_loaded'] = true;
			$otw_sidebar_values['sbm_title'] = $otw_sidebars[ $otw_sidebar_id ]['title'];
			$otw_sidebar_values['sbm_description'] = $otw_sidebars[ $otw_sidebar_id ]['description'];
			$otw_sidebar_values['sbm_replace'] = $otw_sidebars[ $otw_sidebar_id ]['replace'];
			$otw_sidebar_values['sbm_status'] = $otw_sidebars[ $otw_sidebar_id ]['status'];
			if( isset( $otw_sidebars[ $otw_sidebar_id ]['widget_alignment'] ) ){
				$otw_sidebar_values['sbm_widget_alignment'] = $otw_sidebars[ $otw_sidebar_id ]['widget_alignment'];
			}
			$otw_sidebar_values['sbm_validfor'] = $otw_sidebars[ $otw_sidebar_id ]['validfor'];
			$page_title = esc_html__( 'Edit Sidebar' );
		}
	}
	//apply post values
	if( otw_post( 'otw_sml_action', false ) ){
		foreach( $otw_sidebar_values as $otw_field_key => $otw_field_default_value ){
			if( otw_post( $otw_field_key, false ) ){
				$otw_sidebar_values[ $otw_field_key ] = otw_post( $otw_field_key, '' );
			}
		}
	}
	
	foreach( $wp_sml_int_items as $wp_item_type => $wp_item_data ){
	
		if( !in_array( $wp_item_type, array( 'page', 'wpmllanguages', 'userroles' ) ) ){
			
			unset( $wp_sml_int_items[ $wp_item_type ] );
		}
	}
	foreach( $wp_sml_int_items as $wp_item_type => $wp_item_data ){
	
		if( isset( $otw_sidebar_values['sbm_validfor'][ $wp_item_type ] ) ){
			$sbm_valid_for_values[ $wp_item_type ] = implode(',', array_keys( $otw_sidebar_values['sbm_validfor'][ $wp_item_type ] ) );
		}elseif( !$otw_sidebar_values['sbm_loaded'] && in_array( $wp_item_type, array( 'wpmllanguages', 'userroles' ) ) ){
			$db_full_items = otw_sml_get_filtered_items( $wp_item_type, '', 0, 0 );
			$keys = array( 'all' );
			if( isset( $db_full_items[1] ) && is_array( $db_full_items[1] ) ){
				foreach( $db_full_items[1] as $db_full_wpItem ){
					$key = otw_sml_wp_item_attribute( $wp_item_type, 'ID', $db_full_wpItem );
					$keys[ $key ] = $key;
				}
			}
			$sbm_valid_for_values[ $wp_item_type ] = implode( ",", $keys );
		}else{
			$sbm_valid_for_values[ $wp_item_type ] = '';
		}
		
		if( otw_post( 'otw_smb_'.$wp_item_type.'_validfor', false ) ){
			$sbm_valid_for_values[ $wp_item_type ] = otw_post( 'otw_smb_'.$wp_item_type.'_validfor', '' );
		}
		
	}

	
/** set class name of each item block
  *  @param array
  *  @return void
  */
function otw_sidebar_block_class( $item_type, $sidebar_data ){
	
	if( otw_post( 'otw_sml_action', false ) ){
		if( !otw_post( 'otw_sbi_'.$item_type, false ) || !count( otw_post( 'otw_sbi_'.$item_type, '' ) ) ){
			echo ' open';
		}
	}else{
		if( !isset( $sidebar_data['sbm_validfor'][ $item_type ] ) || !count( $sidebar_data['sbm_validfor'][ $item_type ] ) ){
			echo ' open';
		}
	}
}
?>
<div class="wrap">
	<div id="icon-edit" class="icon32"><br/></div>
	<h2>
		<?php echo esc_html( $page_title ); ?>
		<a class="button add-new-h2" href="admin.php?page=otw-sml">Back To Available Sidebars</a>
	</h2>
	<?php if( isset( $validate_messages ) && count( $validate_messages ) ){?>
		<div id="message" class="error">
			<?php foreach( $validate_messages as $v_message ){
				echo '<p>'.$v_message.'</p>';
			}?>
		</div>
	<?php }?>
	<div class="form-wrap" id="poststuff">
		<form method="post" action="" class="validate">
			<input type="hidden" name="otw_sml_action" value="manage_otw_sidebar" />
			<?php wp_original_referer_field(true, 'previous'); wp_nonce_field('otw-sbm-manage'); ?>

			<div id="post-body">
				<div id="post-body-content">
					<div id="col-right" style="width: 65%;" class="otw_sbm_<?php echo esc_attr( $otw_sidebar_id )?>">
						<?php if( is_array( $wp_sml_int_items ) && count( $wp_sml_int_items ) ){?>
						
							<?php foreach( $wp_sml_int_items as $wp_item_type => $wp_item_data ){?>
							
								
								<div class="meta-box-sortables">
									<div class="postbox">
										<div title="<?php esc_html_e('Click to toggle', 'otw_sml')?>" class="handlediv sitem_toggle"><br></div>
										<h3 class="hndle sitem_header"><span><?php echo esc_html( $wp_item_data[1] )?></span></h3>
										
										<div class="inside sitems<?php otw_sidebar_block_class( $wp_item_type, $otw_sidebar_values, $sbm_valid_for_values[ $wp_item_type ] )?>" id="otw_sbm_type_<?php echo esc_attr( $wp_item_type )?>">
											<div class="otw_sidebar_item_filter" id="otw_type_<?php echo esc_attr( $wp_item_type ) ?>_filter" >
												<div id="otw_type_<?php echo esc_attr( $wp_item_type ) ?>_search" class="otw_sidebar_filter_search">
													<label for="otw_type_<?php echo esc_attr( $wp_item_type ) ?>_search_field"><?php esc_html_e( 'Search', 'otw_sml' )?></label>
													<input type="text" id="otw_type_<?php echo esc_attr( $wp_item_type ) ?>_search_field" class="otw_sbm_q_filter" value=""/>
												</div>
												<div id="otw_type_<?php echo esc_attr( $wp_item_type ) ?>_clear" class="otw_sidebar_filter_clear">
													<a href="javascript:;" id="otw_type_<?php echo esc_attr( $wp_item_type ) ?>_clear"><?php esc_html_e( 'reset', 'otw_sml' )?></a>
												</div>
												<div id="otw_type_<?php echo esc_attr( $wp_item_type ) ?>_order" class="otw_sidebar_filter_order">
													<label for="otw_type_<?php echo esc_attr( $wp_item_type ) ?>_order_field"><?php esc_html_e( 'Order', 'otw_sml' )?></label>
													<select id="otw_type_<?php echo esc_attr( $wp_item_type ) ?>_order_field">
														<?php $sort_options = otw_get_item_sort_options( $wp_item_type);?>
														<?php if( count( $sort_options ) ){?>
															<?php foreach( $sort_options as $s_key => $s_value ){ ?>
																<option value="<?php echo esc_attr( $s_key )?>"><?php echo esc_html( $s_value )?></option>
															<?php }?>
														<?php }?>
													</select>
												</div>
												<div id="otw_type_<?php echo esc_attr( $wp_item_type ) ?>_show" class="otw_sidebar_filter_show">
													<label for="otw_type_<?php echo esc_attr( $wp_item_type ) ?>_show_field"><?php esc_html_e( 'Show', 'otw_sml' )?></label>
													<select id="otw_type_<?php echo esc_attr( $wp_item_type ) ?>_show_field">
														<option value="all"><?php esc_html_e( 'All', 'otw_sml' )?></option>
														<option value="all_selected"><?php esc_html_e( 'All Selected', 'otw_sml' )?></option>
														<option value="all_unselected"><?php esc_html_e( 'All Unselected', 'otw_sml' )?></option>
													</select>
												</div>
											</div>
											<div class="otw_sbm_all_actions">
												<div class="otw_sbm_all_links">
													<a href="javascript:;" class="otw_sbm_select_all_items" rel="<?php echo esc_attr( $wp_item_type )?>"><?php esc_html_e( 'Select All', 'otw_sml' )?></a>
													|
													<a href="javascript:;" class="otw_sbm_unselect_all_items" rel="<?php echo esc_attr( $wp_item_type )?>"><?php esc_html_e( 'Unselect All', 'otw_sml' )?></a>
												</div>
												<div class="otw_sbm_selected_items">
													<span class="otw_selected_items_number"></span>&nbsp;<span class="otw_seleted_items_plural"><?php esc_html_e( 'items are', 'otw_sml' );?></span><span class="otw_selected_items_singular"><?php esc_html_e('item is', 'otw_sml' )?></span>&nbsp;<?php esc_html_e( 'selected', 'otw_sml' )?>
												</div>
											</div>
											<div class="a_item">
											<?php if( is_array( $wp_item_data[0] ) && count( $wp_item_data[0] ) ){?>
												<?php foreach( $wp_item_data[0] as $wpItem ){?>
													<p<?php otw_sml_sidebar_item_attributes( 'p', $wp_item_type, otw_sml_wp_item_attribute( $wp_item_type, 'ID', $wpItem ), $otw_sidebar_values, $wpItem )?>>
														<input type="checkbox" id="otw_sbi_<?php echo esc_attr( $wp_item_type )?>_sbi_<?php echo otw_sml_wp_item_attribute( $wp_item_type, 'ID', $wpItem ) ?>"<?php otw_sml_sidebar_item_attributes( 'c', $wp_item_type, otw_sml_wp_item_attribute( $wp_item_type, 'ID', $wpItem ), $otw_sidebar_values, array() )?> value="<?php echo otw_sml_wp_item_attribute( $wp_item_type, 'ID', $wpItem ) ?>" name="otw_sbi_<?php echo esc_attr( $wp_item_type )?>[<?php echo otw_sml_wp_item_attribute( $wp_item_type, 'ID', $wpItem ) ?>]" /><label for="otw_sbi_<?php echo esc_attr( $wp_item_type )?>_sbi_<?php echo otw_sml_wp_item_attribute( $wp_item_type, 'ID', $wpItem ) ?>"<?php otw_sml_sidebar_item_attributes( 'l', $wp_item_type, otw_sml_wp_item_attribute( $wp_item_type, 'ID', $wpItem ), $otw_sidebar_values, $wpItem )?> ><a href="javascript:;"><?php echo otw_sml_wp_item_attribute( $wp_item_type, 'TITLE', $wpItem ) ?></a></label>
													</p>	
												<?php }?>
											<?php }else{ echo '&nbsp;'; }?>
											</div>
											
											<input type="hidden" id="otw_sbm_type_<?php echo esc_attr( $wp_item_type )?>_validfor" class="otw_sbm_validfor" value="<?php echo esc_html( $sbm_valid_for_values[ $wp_item_type ] )?>" name="otw_smb_<?php echo esc_attr( $wp_item_type )?>_validfor" />
										</div>
									</div>
								</div>
								

								
							<?php }?>
							
						<?php } ?>
					</div>
					<div id="col-left">
						<div class="form-field form-required">
							<label for="sbm_title"><?php esc_html_e( 'Sidebar title' );?></label>
							<input type="text" id="sbm_title" value="<?php echo esc_attr( $otw_sidebar_values['sbm_title'] )?>" tabindex="1" size="30" name="sbm_title"/>
							<p><?php esc_html_e( 'The name is how it appears on your site.' );?></p>
						</div>
						<div class="form-field">
							<label for="sbm_status"><?php esc_html_e( 'Status', 'otw_sml' );?></label>
							<select id="sbm_status" tabindex="2" style="width: 170px;" name="sbm_status">
								<option value=""<?php if( $otw_sidebar_values['sbm_status'] == '' ){ echo ' selected="selected" ';}?>>--/--</option>
								<option value="active"<?php if( $otw_sidebar_values['sbm_status'] == 'active' ){ echo ' selected="selected" ';}?>><?php esc_html_e( 'Active', 'otw_sml' )?></option>
								<option value="inactive"<?php if( $otw_sidebar_values['sbm_status'] == 'inactive' ){ echo ' selected="selected" ';}?>><?php esc_html_e( 'Inactive', 'otw_sml' )?></option>
							</select>
						</div>
						<?php if( is_array( $wp_registered_sidebars ) && count( $wp_registered_sidebars ) ){?>
						<div class="form-field">
							<label for="sbm_replace"><?php esc_html_e( 'Replace Existing SideBar' );?></label>
							<select id="sbm_replace" tabindex="3" style="width: 270px;" name="sbm_replace">
								<?php foreach( $wp_registered_sidebars as $lp_wp_sidebar_id => $lp_wp_sidebar ){?>
									<?php if( !preg_match( '/^otw\-sidebar\-/', $lp_wp_sidebar_id ) && ( $otw_sidebar_id != $lp_wp_sidebar_id ) ){?>
										<?php
											$selected = '';
											if( $otw_sidebar_values['sbm_replace'] == $lp_wp_sidebar_id ){
												$selected = ' selected="selected"';
											}
										?>
										<option value="<?php echo esc_attr( $lp_wp_sidebar_id )?>"<?php echo $selected?>><?php echo esc_html( $lp_wp_sidebar['name'] )?></option>
									<?php }?>
									
								<?php }?>
							</select>
							<p><?php esc_html_e( 'Replace existing sidebar. It will be replaced only for selected templates from the right column.' );?></p>
						</div>
						<?php }?>
						<div class="form-field">
							<label for="sbm_description"><?php esc_html_e( 'Description' )?></label>
							<textarea id="sbm_description" name="sbm_description" tabindex="4" rows="3" cols="10"><?php echo otw_esc_text( $otw_sidebar_values['sbm_description'], 'cont' )?></textarea>
							<p><?php esc_html_e( 'Short description for your reference at the admin panel.')?></p>
						</div>
						<p class="submit">
							<input type="submit" value="<?php esc_html_e( 'Save Sidebar') ?>" name="submit" class="button"/>
						</p>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>