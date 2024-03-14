<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<div class="wrap">
	<h1 class="wp-heading-inline">
    	<?php (!isset($_GET['action'])) ? esc_html_e('Add Pincode','pho-pincode-zipcode-cod') : esc_html_e('Update Pincode','pho-pincode-zipcode-cod'); ?>
    </h1>
	<form method="post">	
		<table class="form-table">
			<tbody>

				<tr>
					<input type="hidden" value="<?= wp_create_nonce( 'add_pincode_form' ) ?>" name="_wpnonce_add_pincode_form" id="_wpnonce_add_pincode_form" />
				</tr>

				<tr class="user-user-login-wrap">
					<th>
						<label for="pincode"><?php esc_html_e('Pincode','pho-pincode-zipcode-cod'); ?></label>
					</th>

					<td>
						<input type="text"  pattern="[a-zA-Z0-9\s]+" required="required" class="regular-text" id="pincode" name="pincode" value="<?= isset($get_data['pincode']) ? sanitize_text_field($get_data['pincode']) : '' ?>">
					</td>
				</tr>

				<tr class="user-user-login-wrap">
					<th>
						<label for="city"><?php esc_html_e('City','pho-pincode-zipcode-cod'); ?></label>
					</th>

					<td>
						<input type="text" required="required" class="regular-text" id="city" name="city"  value="<?= isset($get_data['city']) ? sanitize_text_field($get_data['city']) : '' ?>">
					</td>
				</tr>

				<tr class="user-last-name-wrap">
					<th><label for="state"><?php esc_html_e('State','pho-pincode-zipcode-cod'); ?></label></th>

					<td>
						<select name="state" id="state" class="regular-text" required>
							<?php if(isset($_GET['action']) && $_GET['action'] === 'edit'): ?>
									<option>Select State</option>
									<?php foreach ($this->phoenixx_pincodeonshiping_get_country_and_state($country) as $code => $name) { ?>
										<option value="<?= $code ?>" <?= isset($get_data['state']) && $get_data['state'] == $code ? 'selected' : '' ?> ><?= $name ?></option>
									<?php } ?>
								
							<?php else: ?>
								<option>Select State</option>
							<?php endif;?>
						</select>
					</td>
				</tr>

				<tr>
					<th><label for="country"><?php esc_html_e('Country','pho-pincode-zipcode-cod'); ?></label></th>
					<td>
						<select name="country" id="country" class="regular-text" required>
							<option value="">Select Country</option>
							<?php foreach ($this->phoenixx_pincodeonshiping_get_country_and_state() as $code => $name) { ?>
								<option value="<?= $code ?>" <?= $country == $code ? 'selected' : '' ?> ><?= $name ?></option>
							<?php } ?>
						</select>
					</td>
				</tr>

				<tr class="user-nickname-wrap">
					<th><label for="dod"><?php esc_html_e('Delivery within days','pho-pincode-zipcode-cod'); ?></label></th>

					<td><input type="number" min="1" max="365" step="1" value="1" class="regular-text" id="dod" name="dod" value="<?= isset($get_data['dod']) ? sanitize_text_field($get_data['dod']) : '' ?>"></td>
				</tr>

				<tr class="user-nickname-wrap">
					<th>
						<label for="cod"><?php esc_html_e('Enable COD','pho-pincode-zipcode-cod'); ?></label>
					</th>
					<td>
						<label for="cod">
							<input type="radio" <?php if(isset($get_data['cod']) && $get_data['cod'] == 'yes') { ?> checked <?php } ?> name="cod" value="yes"><?php esc_html_e('ON','pho-pincode-zipcode-cod'); ?>
						</label>

						<label for="cod">
							<input type="radio" <?php if(isset($get_data['cod']) && $get_data['cod'] == 'no') { ?> checked <?php } ?> name="cod" value="no"><?php esc_html_e('OFF','pho-pincode-zipcode-cod'); ?>
						</label>
					</td>
				</tr>
			</tbody>
		</table>
		
		<p class="submit">
			<a class="button" href="?page=phoeniixx-zipcode-pincode"><?php esc_html_e('Back','pho-pincode-zipcode-cod'); ?></a>&nbsp;&nbsp;
			<?php if(isset($_GET['action']) && $_GET['action'] === 'edit'): ?>
				<input type="submit" value="Update" class="button button-primary" id="submit" name="submit">
			<?php else: ?>
				<input type="submit" value="Add" class="button button-primary" id="submit" name="submit">
			<?php endif; ?>
		</p>
	
	</form>
</div>
<script>
jQuery(document).on('change','#country',function(event){
	jQuery.ajax({ 
        type    : 'POST',
        url     : '<?php echo admin_url('admin-ajax.php') ?>', 
        data    : {
            action          : 'phoenixx_pincodeonshiping_get_state',
            country_code    : event.target.value,
        },
        success : function(response){
            if(response.status == '1'){
                jQuery('#state').empty();
                jQuery.each(response.state, function(key, value) {   
                    jQuery('#state').append(jQuery("<option></option>").attr("value", key).text(value)); 
                	if(state != '') {
                		jQuery('#state').val(state);
                	}
                });
            }else{
                jQuery('#state').append("<option value = 'no' >No State Found</option>");
            }
        }
    });
})
</script>