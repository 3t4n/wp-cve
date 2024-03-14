<?php

$add_class = '';

if( $individual ) {
	
	$add_class = 'individual';
	
}

?>

<div class="postbox <?php echo esc_attr( $add_class ); ?>" data-section_id="<?php echo esc_attr( $section_id ); ?>">

	<div class="handlediv" title="<?php esc_attr_e('Click to toggle'); ?>"><br /></div>
	
	<h3 class="hndle"><span><?php _e( 'Sort settings' , $APSC->ltd ); ?> : <?php echo $section_title; ?></span></h3>
	
	<div class="inside">
	
		<?php if( $individual ) : ?>
		
			<label>
				<input type="checkbox" name="data[<?php echo esc_attr( $section_name_field ); ?>][use]" class="change-individual" <?php checked( $section_settings['use'] , 1 ); ?> value="1" />
				<?php printf( __( 'Setting the sort for this %s.' , $APSC->ltd ) , $parent_name ); ?>
			</label>

		<?php else : ?>
		
			<input type="hidden" name="data[<?php echo esc_attr( $section_name_field ); ?>][use]" value="1" />
		
		<?php endif; ?>
		
		<?php
		
		$add_class = '';
		
		if( $individual && ! $section_settings['use'] ) {
			
			$add_class = 'disable';
			
		}
		
		?>

		<div class="setting-section <?php echo esc_attr( $add_class ); ?>">
		
			<table class="form-table">
				<tbody>
					<tr>
						<th><?php _e( 'Number of posts per page' , $APSC->ltd ); ?></th>
						<td>
							<div class="item-posts-per-page">
							
								<?php
								
								$items = array(
									'default' => __( 'Follow Reading Setting' , $APSC->ltd ),
									'all' => __( 'View all posts' , $APSC->ltd ),
									'set' => __( 'Set the number of posts' , $APSC->ltd ),
								);

								?>
								
								<?php foreach( $items as $item_type => $item_label ) : ?>
								
									<div class="posts-per-page-<?php echo esc_attr( $item_type ); ?>">
									
										<p>

											<label>
	
												<input type="radio" name="data[<?php echo esc_attr( $section_name_field ); ?>][posts_per_page]" class="change-posts-per-page" value="<?php echo esc_attr( $item_type ); ?>" <?php checked( $section_settings['posts_per_page'] , $item_type ); ?> />
												<?php echo $item_label; ?>
												
											</label>
											
											<?php if( $item_type == 'default' ) : ?>
											
												<?php $default_post_per_page_num = intval( get_option( 'posts_per_page' ) ); ?>
												
												<strong><?php echo $default_post_per_page_num; ?><?php _e( 'posts' ); ?></strong>
												
												<span class="description">(<?php _e( 'Default' ); ?>)</span>
												
											<?php elseif( $item_type == 'set' ) : ?>
	
												<span class="post-per-page-set-number">
												
													<?php
													
													$disabled = true;
													$add_class = 'disabled';
													
													if( $section_settings['posts_per_page'] == 'set' ) {
														
														$disabled = false;
														$add_class = '';
														
													}
													
													?>
	
													<input type="number" class="small-text <?php echo esc_attr( $add_class ); ?>" name="data[<?php echo esc_attr( $section_name_field ); ?>][posts_per_page_num]" value="<?php echo esc_attr( $section_settings['posts_per_page_num'] ); ?>" <?php disabled( $disabled , true ); ?> />
													<?php _e( 'posts' ); ?>
												
												</span>
	
											<?php endif; ?>
											
										</p>
									
									</div>
								
								<?php endforeach; ?>
								
							</div>
						</td>
					</tr>
					<tr>
						<th><?php _e( 'Sort item' , $APSC->ltd ); ?></th>
						<td>
							<div class="item-orderby">
							
								<div class="orderby">

									<?php
	
									$items = array(
										'date' => sprintf( '%1$s (%2$s)' , __( 'Date' ) , __( 'Default' ) ),
										'title' => __( 'Title' ),
										'author' => __( 'Author' ),
										'comment_count' => __( 'Comments Count' , $APSC->ltd ),
										'id' => 'ID',
										'modified' => __( 'Last Modified' ),
										'menu_order' => sprintf( '%1$s (%2$s)' , __( 'Order' ) , __( 'Page Attributes' ) ),
										'custom_fields' => __( 'Custom Fields' ),
									);
	
									?>
									
									<select name="data[<?php echo esc_attr( $section_name_field ); ?>][orderby]" class="change-orderby">
	
										<?php foreach( $items as $item_type => $item_label ) : ?>
										
											<option value="<?php echo esc_attr( $item_type ); ?>" <?php selected( $section_settings['orderby'] , $item_type ); ?>><?php echo esc_attr( $item_label ); ?></option>
		
										<?php endforeach; ?>
										
									</select>
									
								</div>
								
								<?php
								
								$add_class = 'disable';
								
								if( $section_settings['orderby'] == 'custom_fields' ) {
									
									$add_class = '';
									
								}
								
								?>

								<div class="custom-fields <?php echo esc_attr( $add_class ); ?>">
								
									<p>

										<label>
										
											<?php _e( 'Custom Fields Name' , $APSC->ltd ); ?>
											<input type="text" class="regular-text field-custom-field-name" name="data[<?php echo esc_attr( $section_name_field ); ?>][orderby_set]" value="<?php echo esc_attr( $section_settings['orderby_set'] ); ?>" />
										
										</label>
										
									</p>
									
									<p>
										<span class="description"><?php _e( 'Please find the custom fields name after click here if you do not know of custom fields name.' , $APSC->ltd ); ?></span>
										<a href="javascript:void(0);" class="button button-secondary load-all-custom-fields"><span class="dashicons dashicons-update"></span><?php _e( 'Load all custom fields' , $APSC->ltd ); ?></a>
										<span class="spinner"></span>
									</p>
									
									<div class="all-custom-fields-names"></div>
								
								</div>

								<?php
								
								$add_class = 'disable';
								
								if( $section_settings['orderby'] == 'title' ) {
									
									$add_class = '';
									
								}
								
								?>

								<div class="ignore-words <?php echo esc_attr( $add_class ); ?>">
								
									<p><?php _e( 'Please enter the words if you want to <strong>ignore some words</strong> of post title beginning.' , $APSC->ltd ); ?></p>
									
									<div class="ignore-words-field">
									
										<?php if( !empty( $section_settings['ignore_words'] ) ) : ?>
										
											<?php foreach( $section_settings['ignore_words'] as $key => $ignore_word ) : ?>
											
												<p>
													<input type="text" class="regular-text" name="data[<?php echo esc_attr( $section_name_field ); ?>][ignore_words][]" placeholder="The (space)" value="<?php echo esc_attr( $ignore_word ); ?>" />
													<a href="javascript:void(0);" class="button button-small remove-ignore-word"><span class="dashicons dashicons-no-alt"></span><?php _e( 'Remove' ); ?></a>
												</p>
											
											<?php endforeach; ?>
										
										<?php endif; ?>
									
									</div>
									
									<p>
										<a href="javascript:void(0);" class="button button-secondary add-ignore-word"><span class="dashicons dashicons-plus"></span><?php _e( 'Add New Word' , $APSC->ltd ); ?></a>
									</p>
									
									<div class="ignore-word-add-field">
									
										<p>
											<input type="text" class="regular-text" name="data[<?php echo esc_attr( $section_name_field ); ?>][ignore_words][]" placeholder="The (space)" value="" />
											<a href="javascript:void(0);" class="button button-small remove-ignore-word"><span class="dashicons dashicons-no-alt"></span><?php _e( 'Remove' ); ?></a>
										</p>
										
									</div>
								
								</div>

							</div>
						</td>
					</tr>
					<tr>
						<th><?php _e( 'Order' ); ?></th>
						<td>
							<div class="item-order">
							
								<?php
								
								$items = array(
									'desc' => __( 'Descending' ),
									'asc' => __( 'Ascending' ),
								);

								?>
								
								<?php foreach( $items as $item_type => $item_label ) : ?>
								
									<div class="order-<?php echo esc_attr( $item_type ); ?>">
									
										<p>

											<label>
	
												<input type="radio" name="data[<?php echo esc_attr( $section_name_field ); ?>][order]" value="<?php echo esc_attr( $item_type ); ?>" <?php checked( $section_settings['order'] , $item_type ); ?> />
												<?php echo $item_label; ?>

												<?php if( $item_type == 'desc' ) : ?>
												
													<span class="description">(<?php _e( 'Default' ); ?>)</span>
													
												<?php endif; ?>
												
											</label>
											
										</p>
									
									</div>

								<?php endforeach; ?>
								
								<p>&nbsp;</p>

								<p>
									<strong><?php _e( 'Descending' ); ?></strong>:
									<?php _e( 'From new to old' , $APSC->ltd ); ?> &amp; <?php _e( 'From many to small' , $APSC->ltd ); ?> &amp; Z to A
								</p>
								<p>
									<strong><?php _e( 'Ascending' ); ?></strong>:
									<?php _e( 'From old to new' , $APSC->ltd ); ?> &amp; <?php _e( 'From small to many' , $APSC->ltd ); ?> &amp; A to Z
								</p>

							</div>
						</td>
					</tr>
				</tbody>
			</table>
		
		</div>
		
	</div>

</div>