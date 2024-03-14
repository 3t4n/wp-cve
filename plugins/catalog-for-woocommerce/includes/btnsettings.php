<?php if ( ! defined( 'ABSPATH' ) ) exit; 

	if ( ! empty( $_POST ) && check_admin_referer( 'phoen_woo_btncreate_action', 'phoen_woo_btncreate_action_field' ) ) {

		if(sanitize_text_field( $_POST['createbtn_mode'] ) == 'Save'){
			
			$btn_title    = (isset($_POST['btn_title']))?sanitize_text_field( $_POST['btn_title'] ):'Click Here';
			
			$btn_url    = (isset($_POST['btn_url']))?sanitize_text_field( $_POST['btn_url'] ):'' ;
			
			$btn_new_win    = (isset($_POST['btn_new_win']))?sanitize_text_field( $_POST['btn_new_win'] ):'';
			
			$topmargin    = (isset($_POST['topmargin']))?sanitize_text_field( $_POST['topmargin'] ):'0';
			
			$rightmargin    = (isset($_POST['rightmargin']))?sanitize_text_field( $_POST['rightmargin'] ):'0';
			
			$bottommargin    = (isset($_POST['bottommargin']))?sanitize_text_field( $_POST['bottommargin'] ):'0';
			
			$leftmargin    = (isset($_POST['leftmargin']))?sanitize_text_field( $_POST['leftmargin'] ):'0';
							
			$btn_bg_col    = (isset($_POST['btn_bg_col']))?sanitize_text_field( $_POST['btn_bg_col'] ):'#a46497';
			
			$btn_txt_col    = (isset($_POST['btn_txt_col']))?sanitize_text_field( $_POST['btn_txt_col'] ):'#fff';
			
			$btn_hov_col    = (isset($_POST['btn_hov_col']))?sanitize_text_field( $_POST['btn_hov_col'] ):'#935386';
			
			$btn_border_style    = (isset($_POST['btn_border_style']))?sanitize_text_field( $_POST['btn_border_style'] ):'none';
			
			$btn_border    = (isset($_POST['btn_border']))?sanitize_text_field( $_POST['btn_border'] ):'0';
			
			$btn_bor_col    = (isset($_POST['btn_bor_col']))?sanitize_text_field( $_POST['btn_bor_col'] ):'';
			
			$btn_rad    = (isset($_POST['btn_rad']))?sanitize_text_field( $_POST['btn_rad'] ):'0';
			
			$show_shop    = (isset($_POST['show_shop']))?sanitize_text_field( $_POST['show_shop'] ):'';
			
			$show_prod    = (isset($_POST['show_prod']))?sanitize_text_field( $_POST['show_prod'] ):'0';
			
			$btn_settings=array(
				
					'btn_title'		=>		$btn_title,
					
					'btn_url'		=>		$btn_url,
					
					'btn_new_win'	=>		$btn_new_win,
					
					'topmargin'		=>		$topmargin,
					
					'rightmargin'	=>		$rightmargin,
					
					'bottommargin'	=>		$bottommargin,
					
					'leftmargin'	=>		$leftmargin,
					
					'btn_bg_col'	=>		$btn_bg_col,
					
					'btn_txt_col'	=>		$btn_txt_col,
					
					'btn_hov_col'	=>		$btn_hov_col,
					
					'btn_border_style'=>	$btn_border_style,
					
					'btn_border'	=>		$btn_border,
					
					'btn_bor_col'	=>		$btn_bor_col,
					
					'btn_rad'		=>		$btn_rad,
					
					'show_shop'		=>		$show_shop,
					
					'show_prod'		=>		$show_prod
									
			);
			
			update_option('phoeniixx_create_custom_btn',$btn_settings);
			
		}
	}
	
	$plugin_dir_url =  plugin_dir_url( __FILE__ );
	
?>
<style>
	
	a:focus { box-shadow: none;}
	
	.postbox {
		background: #fff none repeat scroll 0 0;
		border: 1px solid #e5e5e5;
		box-shadow: 0 1px 1px rgba(0, 0, 0, 0.04);
		min-width: 255px;
	}
	
	

	.postbox .inside {
		margin: 11px 0;
		position: relative;
	}
	
	
	.pho-upgrade-btn {
		margin-top: 30px;
	}

	</style>
	<div class="cat_mode">
	
		
		<div id="normal-sortables" class="meta-box-sortables">
				<div id="pho_wcpc_box" class="postbox ">

					<div class="inside">
						<div class="pho_check_pin">

							<div class="column two">
								<!----<h2>Get access to Pro Features</h2>----->

								
									<div class="pho-upgrade-btn">

										<a href="https://www.phoeniixx.com/product/catalog-for-woocommerce/" target="_blank"><img src="<?php echo $plugin_dir_url; ?>../assets/images/premium-btn.png" /></a>
										<a href="http://catalog.phoeniixxdemo.com/shop/" target="_blank"><img src="<?php echo $plugin_dir_url; ?>../assets/images/demo-btn.png"></a>

									</div>
							</div>
						</div>
					</div>
				</div>
		</div>
		
			
		<form method="post" name="phoen_woo_btncreate">
			
			<?php $gen_settings=get_option('phoeniixx_create_custom_btn');
			
			wp_nonce_field( 'phoen_woo_btncreate_action', 'phoen_woo_btncreate_action_field'  ); ?>
					
			<table class="form-table">
				
				<tr>
					<th>
					
						<?php _e('Enable custom Button on Product page','woocommerce-extension'); ?>
						
					</th>
					
					<td>
					
						<input type="checkbox" name="show_prod" class="btn_new_win" value="1" <?php echo(isset($gen_settings['show_prod']) && $gen_settings['show_prod'] == '1')?'checked':'';?> >
					
					</td>	
					
				</tr>
				
				<tr>
					
					<th>
					
						<?php _e('Enable custom Button on Shop page','woocommerce-extension'); ?>
					
					</th>	
					
					<td>
					
						<input type="checkbox" name="show_shop" class="btn_new_win" value="1" <?php echo(isset($gen_settings['show_shop']) && $gen_settings['show_shop'] == '1')?'checked':'';?> >
					
					</td>
				
				</tr>							
				
				<tr>
					
					<th>
					
						<?php _e('Button title','woocommerce-extension'); ?>
						
					</th>
					
					<td>
						
						<input type="text" class="btn_title" name="btn_title" value="<?php echo(isset($gen_settings['btn_title'])) ?$gen_settings['btn_title']:'Click Here';?>">
					
					</td>
				
				</tr>
				
				<tr>
				
					<th>
					
						<?php _e('Button URL','woocommerce-extension'); ?>
						
					</th>
					
					<td>
						
						<input type="text" name="btn_url" class="btn_url" value="<?php echo(isset($gen_settings['btn_url'])) ?$gen_settings['btn_url']:'';?>">
					
					</td>
					
				</tr>
				
				<tr>
					<th>
						
						<?php _e('Open in new window','woocommerce-extension'); ?>
					
					</th>
					
					<td>
						
						<input type="checkbox" name="btn_new_win" class="btn_new_win" value="1" <?php echo(isset($gen_settings['btn_new_win']) && $gen_settings['btn_new_win'] == '1')?'checked':'';?> >
					
					</td>
				
				</tr>
			
				<tr>

				<th> 
				
					<?php _e('Padding','woocommerce-extension'); ?>
					
				</th>
					
					<td>
					
						<input class="btn_num"   placeholder="TOP" style="max-width:60px;font-size:12px;" min="0" name="topmargin" 	type="number" value="<?php echo(isset($gen_settings['topmargin'])) ?$gen_settings['topmargin']:'0';?>">
							
						<input class="btn_num"  placeholder="RIGHT" style="max-width:65px;font-size:12px;" min="0" name="rightmargin" 	type="number" value="<?php echo(isset($gen_settings['rightmargin'])) ?$gen_settings['rightmargin']:'0';?>">

						<input class="btn_num"  placeholder="BOTTOM" style="max-width:65px;font-size:12px;" min="0" name="bottommargin" 	type="number" value="<?php echo(isset($gen_settings['bottommargin'])) ?$gen_settings['bottommargin']:'0';?>">
							
						<input class="btn_num"   placeholder="LEFT" style="max-width:65px;font-size:12px;" min="0" name="leftmargin" 	type="number" value="<?php echo(isset($gen_settings['leftmargin'])) ?$gen_settings['leftmargin']:'0';?>"><span class="pixel-11">px</span>

					</td>

				</tr>
				
				<tr>
					
					<th>
					
						<?php _e('Button Color','woocommerce-extension'); ?>
						
					</th>
					
					<td>
						
						<input class="btn_color_picker btn_bg_col" type="text" name="btn_bg_col" value="<?php echo(isset($gen_settings['btn_bg_col'])) ?$gen_settings['btn_bg_col']:'#a46497';?>">
					
					</td>
				
				</tr>
				
				<tr>
					<th>
					
						<?php _e('Button Text color','woocommerce-extension'); ?>
						
					</th>
					
					<td>
						
						<input class="btn_color_picker btn_txt_col" type="text" name="btn_txt_col" value="<?php echo(isset($gen_settings['btn_txt_col'])) ?$gen_settings['btn_txt_col']:'#fff';?>">
					
					</td>
					
				</tr>
				
				<tr>
					<th>
					
						<?php _e('Button Hover color','woocommerce-extension'); ?>
						
					</th>
					
					<td>
					
						<input class="btn_color_picker btn_hov_col" type="text" name="btn_hov_col" value="<?php echo(isset($gen_settings['btn_hov_col'])) ?$gen_settings['btn_hov_col']:'#935386';?>">
					
					</td>
					
				</tr>
				
				<tr>
				
					<th>
					
						<?php _e('Border style','woocommerce-extension'); ?>
						
					</th>
					
					<td>
					
						<?php $st = (isset($gen_settings['btn_border_style'])) ? $gen_settings['btn_border_style'] : 'none'; ?>
						
						<select name="btn_border_style" class="btn_border_style">
							
							<option value="none" <?php if($st=='solid') echo 'selected';?>>None</option>
							
							<option value="solid" <?php if($st=='solid') echo 'selected';?>>Solid</option>
							
							<option value="dashed" <?php if($st=='dashed') echo 'selected';?>>Dashed</option>
							
							<option value="dotted" <?php if($st=='dotted') echo 'selected';?>>Dotted</option>
							
							<option value="double" <?php if($st=='double') echo 'selected';?>>Double</option>

						</select>
						
					</td>
					
				</tr>
				
				<tr>
					<th>
					
						<?php _e('Button Border','woocommerce-extension'); ?>
						
					</th>
					
					<td>
					
						<input class="btn_num"  type="number" name="btn_border" style="max-width:105px;" value="<?php echo(isset($gen_settings['btn_border'])) ?$gen_settings['btn_border']:'0';?>">px
					
					</td>
					
				</tr>
								
				<tr>
					<th>
					
						<?php _e('Button border color','woocommerce-extension'); ?>
						
					</th>
					
					<td>
						<input class="btn_color_picker btn_bor_col"  type="text" name="btn_bor_col" value="<?php echo(isset($gen_settings['btn_bor_col'])) ?$gen_settings['btn_bor_col']:'';?>">
					
					</td>
					
				</tr>
				
				<tr>
				
					<th>
					
						<?php _e('Button Radius','woocommerce-extension'); ?>
						
					</th>
					
					<td>
					
						<input  class="btn_num" type="number" style="max-width:105px;" name="btn_rad" value="<?php echo(isset($gen_settings['btn_rad'])) ?$gen_settings['btn_rad']:'0';?>">px
					
					</td>
					
				</tr>
				
				<tr>
				
					<td>
					
						<input type="submit" class="button button-primary" value="Save" name="createbtn_mode">
						
						<input type="button" class="reset_button button-primary" value="Reset" name="reset_mode">
					
					</td>
					
				</tr>
						
			</table>
		
		</form>
		
	</div>