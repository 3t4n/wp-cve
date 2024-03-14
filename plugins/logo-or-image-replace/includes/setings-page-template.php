<div class="wrap qc_lpp_settings_options ">
	<h1>Settings</h1>
	<form action="options.php" class="form" method="post">
		<?php
			settings_fields( 'qc_lpp_settings_options' );
			$selected_post_types = get_option('qc_lpp_selected_post_types');
			if( !is_array($selected_post_types) || empty($selected_post_types) ){
				$selected_post_types = array();
			}
		?>
		<table class="form-table">
			<tr>
				<th scope="row">
					<label>Choose Post Types</label>
				</th>
				<td>
					<?php foreach (qc_lpp_get_post_types() as $key => $value) { ?>
						<fieldset>
							<label>
								<input name="qc_lpp_selected_post_types[]" type="checkbox" value="<?php echo esc_attr($key); ?>" <?php if(in_array($key, $selected_post_types)){ echo 'checked="true"'; } ?> >
								<?php echo $value; ?>
							</label>
						</fieldset>
					<?php } ?>
					<?php
						$args = array(
						   'public'   => true,
						   '_builtin' => false
						);
						
						$output = 'objects'; // 'names' or 'objects' (default: 'names')
						$operator = 'and'; // 'and' or 'or' (default: 'and')
						 $post_type_lists = [];
						$post_types = get_post_types( $args, $output, $operator );
						if( !empty($post_types) ){
							foreach ($post_types as $key) {
								$post_type_lists[$key->name] = $key->label;
							}
						}
						foreach ($post_type_lists as $key => $value) { ?>
							<fieldset>
								<label style="opacity: 0.7; pointer-events: none;">
									<input type="checkbox" value="<?php echo esc_attr($key); ?>" >
									<?php echo $value; ?>
								</label>
							</fieldset>
						<?php } ?>
						<strong><a href="https://www.quantumcloud.com/products/image-tools-for-wordpress" target="_blank">Upgrade to Pro</a></strong> to add <strong>Custom Post Type Support</strong> and use <strong>Multiple Image Replace Field</strong>.
				</td>
			</tr>
		</table>
		<?php submit_button(); ?>
	</form>
</div>