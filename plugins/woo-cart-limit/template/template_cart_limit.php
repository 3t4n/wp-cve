<div class="WMAMC_export_wrapper" >
	<?php echo $this->WMAMC_get_msgbox();  	
		$enable_cart = $this->WMAMC_get_cart_limit_options('wmamc_enable_cartlimit');		
	 ?>	
	<div class="tab"></div>		
	<div id="review_export" class="tabcontent " <?php echo  (!isset($active_tab)) || ((isset($active_tab) && $active_tab =='review')) ? ' style="display: block;"' : ''; ?>>
				
		<div class="wrap woocommerce">	
			<div class="">
				<div class="col-md-12">
					<legend> <?php _e("Enter cart limit values","wmamc-cart-limit");?></legend>
				</div>
				
				<div class="col-md-12">
					<form method="post" id="cartlimit_mainform" action="admin-post.php" class="form-horizontal">					
						<input type="hidden" name="action" value="wmamc_cart_limitf">
						<div class="form-group">
							<label class="col-md-2 control-label"> <?php _e('Enable Limits: ','wmamc-cart-limit'); ?></label>
							<label class="switch"> 
							  <input type="checkbox" name="enable_cart_limit[]" value="true" <?php echo ($enable_cart =='true') ? 'checked':''; ?>>
							  <span class="slider round"></span>
							</label>
						</div>
						<div class="form-group" >
							<label class="col-md-4 control-label" for="wmamc_cat_max_quantity">Allow Categories Limit:</label>
							<div class="col-md-4">
								<input type="checkbox" id="wmamc_cat_max_quantity" name="wmamc_cat_max_quantity" value="true" <?php echo ($this->WMAMC_get_cart_limit_options('wmamc_cat_max_quantity') == 'true') ? 'checked' : ''; ?>/>								
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-4 control-label" for="wmamc_cart_max_quanity">Cart Max Quantity:</label>
							<div class="col-md-4">							
								<input type="input" id="wmamc_cart_max_quanity" name="wmamc_cart_max_quanity" class="form-control input-md" value="<?php echo $this->WMAMC_get_cart_limit_options('wmamc_cart_max_quanity') ?>"  />
							</div>
							<span class="glyphicon glyphicon-question-sign"></span>							
						</div>
						<div class="form-group">
							<label class="col-md-4 control-label" for="wmamc_cart_min_quanity">Cart Min Quantity:</label>					
							<div class="col-md-4">
								<input type="input" id="wmamc_cart_min_quanity" name="wmamc_cart_min_quanity" class="form-control input-md" value="<?php echo $this->WMAMC_get_cart_limit_options('wmamc_cart_min_quanity') ?>"  />
							</div>							
						</div>
						
						<div class="form-group">
							<label class="col-md-4 control-label" for="wmamc_cart_max_total">Cart Max SubTotal:</label>					
							<div class="col-md-4">
								<input type="input" id="wmamc_cart_max_total" name="wmamc_cart_max_total" class="form-control input-md" value="<?php echo $this->WMAMC_get_cart_limit_options('wmamc_cart_max_total') ?>"  />
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-md-4 control-label" for="wmamc_cart_min_total">Cart Min SubTotal:</label>					
							<div class="col-md-4">
								<input type="input" id="wmamc_cart_min_total" name="wmamc_cart_min_total" class="form-control input-md" value="<?php echo $this->WMAMC_get_cart_limit_options('wmamc_cart_min_total') ?>"  />
							</div>								
						</div>
						
						<div class="form-group">
							<label class="col-md-4 control-label" for="wmamc_cart_min_diff_item">Cart Min Different Items:</label>					
							<div class="col-md-4">
								<input type="input" id="wmamc_cart_min_diff_item" name="wmamc_cart_min_diff_item" class="form-control input-md" value="<?php echo $this->WMAMC_get_cart_limit_options('wmamc_cart_min_diff_item') ?>"  />
							</div>	
						</div>
						
						<div class="form-group">
							<div class="col-md-4">						
								<input type="submit" id="wmamc_cart_limit_call" name="wmamc_cart_limit_call" class="btn btn-primary" value="<?php _e("Save","wmamc-cart-limit") ?>">
							</div>
							<?php wp_nonce_field( 'wmamc_cart_limit_nonce', 'wmamc_cart_limit_nonce' ); ?>
						</div>
					</form>			
				</div>
			</div>		
		</div>
	</div>	
</div>