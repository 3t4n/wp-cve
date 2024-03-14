<?php 
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once( plugin_dir_path( __FILE__ ) . 'header/plugin-header.php' );
?>
<div class="mmqw-section-left">

	<form id="createForm" method="post" class="mmqw-main-table res-cl">
		<?php 
		$nonce = wp_create_nonce( 'mass_pages_posts_creator_nonce' );
		?>
		<input type="hidden" name="mass_pages_posts_creator" id="mass_pages_posts_creator"
		       value="<?php echo  esc_attr( $nonce ) ; ?>" />
		<h2><?php esc_html_e( 'Mass Pages/Posts Creator', 'mass-pages-posts-creator' ); ?></h2>
		<table class="form-table">
			<tr class="page_prefix_tr">
				<th class="titledesc"><?php esc_html_e( 'Prefix of Pages/Posts', 'mass-pages-posts-creator' ); ?></th>
				<td><input type="text" class="regular-text" value="" id="page_prefix" name="page_prefix">
				</td>
			</tr>
			<tr class="page_post_tr">
				<th class="titledesc"><?php esc_html_e( 'Postfix of Pages/Posts', 'mass-pages-posts-creator' ); ?></th>
				<td><input type="text" class="regular-text" value="" id="page_postfix" name="page_postfix">
				</td>
			</tr>
			<tr class="pages_list_tr">
				<th class="titledesc"><?php esc_html_e( 'List of Pages/Posts', 'mass-pages-posts-creator' ); ?>
					</br><?php esc_html_e( '(Comma Separated)', 'mass-pages-posts-creator' ); ?>
					<b>(*)</b>
				</th>
				<td>
					<textarea class="code" id="pages_list" cols="60" rows="5" name="pages_list"></textarea>
					<p class="description"><?php esc_html_e( 'eg. Test1, Test2, test3, test4, test5', 'mass-pages-posts-creator' ); ?></p>
				</td>
			</tr>
			<tr class="pages_content_tr">
				<th class="titledesc"><?php esc_html_e( 'Content of Pages/Posts', 'mass-pages-posts-creator' ); ?></th>
				<td>
					<?php 
					$pages_content = filter_input( INPUT_POST, 'pages_content', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
					$content = ( isset( $pages_content ) ? htmlspecialchars_decode( $pages_content ) : '' );
					?>
										<?php 
					wp_editor( $content, 'pages_content', array(
					    'textarea_name' => 'pages_content',
					    'editor_class'  => 'requiredField',
					    'textarea_rows' => '6',
					    'media_buttons' => true,
					    'tinymce'       => true,
					) );
					?>
					<p class="description"><?php esc_html_e( 'eg. It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.', 'mass-pages-posts-creator' ); ?></p>
				</td>
			</tr>
			<tr class="excerpt_content_tr">
				<th class="titledesc"><?php esc_html_e( 'Excerpt Content', 'mass-pages-posts-creator' ); ?></th>
				<td>
					<textarea class="code" id="excerpt_content" cols="60" rows="5" name="excerpt_content"></textarea>
					<p class="description"><?php esc_html_e( 'eg. It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.', 'mass-pages-posts-creator' ); ?></p>
				</td>
			</tr>
			<tr class="page_prefix_tr">
				<th class="titledesc"><?php esc_html_e( 'Number of posts', 'mass-pages-posts-creator' ); ?></th>
				<td><input type="number" id="no_post_add" name="no_post_add" value="1" min="1"></td>
			</tr>
			<tr class="type_tr">
				<th class="titledesc"><?php esc_html_e( 'Type', 'mass-pages-posts-creator' ); ?> <b>(*)</b></th>
				<td>
					<?php 
					if ( mppcp_fs()->is__premium_only() || mppcp_fs()->is_trial() ) {
	    				if ( mppcp_fs()->can_use_premium_code() || mppcp_fs()->is_trial() ) {
	    					$get_post_types = get_post_types(
								[
									'public' => true,
								], 'objects'
							);
	    				}
	    			}
					?>
					<select id="type">
						<option value="none"><?php esc_html_e( 'Select Type', 'mass-pages-posts-creator' ); ?></option>
						<option value="page"><?php esc_html_e( 'Page', 'mass-pages-posts-creator' ); ?></option>
						<option value="post"><?php esc_html_e( 'Post', 'mass-pages-posts-creator' ); ?></option>
						<?php 
						if ( mppcp_fs()->is__premium_only() || mppcp_fs()->is_trial() ) {
		    				if ( mppcp_fs()->can_use_premium_code() || mppcp_fs()->is_trial() ) {
		    					foreach ($get_post_types as $post_type_list) {

		    						$exclude = array( 'page', 'post', 'attachment', 'elementor_library' );

		    						if( TRUE === in_array( $post_type_list->name, $exclude, true ) )
		    							continue;

		    						?><option value="<?php esc_attr_e( $post_type_list->name, 'mass-pages-posts-creator' ); ?>"><?php esc_html_e( $post_type_list->label, 'mass-pages-posts-creator' ); ?></option><?php
		    					}
		    				}
		    			}
						?>
					</select>
				</td>
			</tr>
			<?php 

			if ( mppcp_fs()->is__premium_only() || mppcp_fs()->is_trial() ) {
			    
			    if ( mppcp_fs()->can_use_premium_code() || mppcp_fs()->is_trial() ) {
       			?>
					<tr class="template_name_tr">
						<th class="titledesc"><?php esc_html_e( 'Templates', 'mass-pages-posts-creator' ); ?></th>
						<td>
							<?php $templates = get_page_templates(); ?>
							<select id="template_name">
								<option value=""><?php esc_html_e( '-- Select Template --', 'mass-pages-posts-creator' ); ?></option>
								<?php 
        
						        if ( isset( $templates ) || !empty($templates) ) {
						            foreach ( $templates as $template_name => $template_filename ) {
						                ?>
										<option value="<?php echo  esc_attr( $template_filename ); ?>"><?php echo  wp_kses_post( $template_name ); ?></option>
										<?php 
									}
								} else {
								?>
									<option value=""><?php esc_html_e( 'No templates available', 'mass-pages-posts-creator' ); ?></option>
									<?php 
								}
								?>
							</select>
						</td>
					</tr>
				<?php 
			} else {
	    	?>
					<tr class="template_name_tr">
						<th class="titledesc"><?php esc_html_e( 'Templates - Available in Pro', 'mass-pages-posts-creator' ); ?></th>
						<td>
							<select id="template_name">
								<option value=""><?php esc_html_e( 'Select Template', 'mass-pages-posts-creator' ); ?></option>
								<option value="" disabled><?php esc_html_e( 'In Pro', 'mass-pages-posts-creator' ); ?></option>
							</select>
						</td>
					</tr>
				<?php 
			}
		} else {
    	?>
				<tr class="template_name_tr">
					<th class="titledesc"><?php esc_html_e( 'Templates - Available in Pro', 'mass-pages-posts-creator' ); ?></th>
					<td>
						<select id="template_name">
							<option value=""><?php esc_html_e( 'Select Template', 'mass-pages-posts-creator' ); ?></option>
							<option value="" disabled><?php esc_html_e( 'In Pro', 'mass-pages-posts-creator' ); ?></option>
						</select>
					</td>
				</tr>
			<?php 
		}
		?>
			<tr class="page_status_tr">
				<th class="titledesc"><?php esc_html_e( 'Pages/Posts Status', 'mass-pages-posts-creator' ); ?></th>
				<td>
					<select id="page_status">
						<?php 

						if ( mppcp_fs()->is__premium_only() || mppcp_fs()->is_trial() ) {
						    
						    if ( mppcp_fs()->can_use_premium_code() || mppcp_fs()->is_trial() ) {
						        ?>
								<option value="publish"><?php esc_html_e( 'Publish', 'mass-pages-posts-creator' ); ?></option>
								<?php 
							} else {
								?>
								<option value="publish" disabled><?php esc_html_e( 'Publish - In Pro', 'mass-pages-posts-creator' ); ?></option>
								<?php 
							}
						} else {
							?>
							<option value="publish" disabled><?php esc_html_e( 'Publish - In Pro', 'mass-pages-posts-creator' ); ?></option>
							<?php 
						}
						?>
						<option value="pending"><?php esc_html_e( 'Pending', 'mass-pages-posts-creator' ); ?></option>
						<option value="draft"><?php esc_html_e( 'Draft', 'mass-pages-posts-creator' ); ?></option>
						<option value="auto-draft"><?php esc_html_e( 'Auto Draft', 'mass-pages-posts-creator' ); ?></option>
						<option value="private"><?php esc_html_e( 'Private', 'mass-pages-posts-creator' ); ?></option>
						<option value="trash"><?php esc_html_e( 'Trash', 'mass-pages-posts-creator' ); ?></option>
					</select>
				</td>
			</tr>
			<?php 

			if ( mppcp_fs()->is__premium_only() || mppcp_fs()->is_trial() ) {
			    
			    if ( mppcp_fs()->can_use_premium_code() || mppcp_fs()->is_trial() ) {
        			?>
					<tr class="comment_status_tr">
						<th class="titledesc"><?php esc_html_e( 'Pages/Posts Comment Status', 'mass-pages-posts-creator' ); ?></th>
						<td>
							<select id="comment_status">
								<option value=""><?php esc_html_e( 'Select Comment Status', 'mass-pages-posts-creator' ); ?></option>
								<option value="open"><?php esc_html_e( 'Open', 'mass-pages-posts-creator' ); ?></option>
								<option value="closed"><?php esc_html_e( 'Closed', 'mass-pages-posts-creator' ); ?></option>
							</select>
						</td>
					</tr>
				<?php 
			} else {
    	?>
				<tr class="comment_status_tr">
					<th class="titledesc"><?php esc_html_e( 'Pages/Posts Comment Status - Available in Pro', 'mass-pages-posts-creator' ); ?></th>
					<td>
						<select id="comment_status">
							<option value=""><?php esc_html_e( 'Select Comment Status', 'mass-pages-posts-creator' ); ?></option>
							<option value="open" disabled><?php esc_html_e( 'Open - In Pro', 'mass-pages-posts-creator' ); ?></option>
							<option value="closed" disabled><?php esc_html_e( 'Closed - In Pro', 'mass-pages-posts-creator' ); ?></option>
						</select>
					</td>
				</tr>
				<?php 
			}
		} else {
    	?>
				<tr class="comment_status_tr">
					<th class="titledesc"><?php esc_html_e( 'Pages/Posts Comment Status - Available in Pro', 'mass-pages-posts-creator' ); ?></th>
					<td>
						<select id="comment_status">
							<option value=""><?php esc_html_e( 'Select Comment Status', 'mass-pages-posts-creator' ); ?></option>
							<option value="open" disabled><?php esc_html_e( 'Open - In Pro', 'mass-pages-posts-creator' ); ?></option>
							<option value="closed" disabled><?php esc_html_e( 'Closed - In Pro', 'mass-pages-posts-creator' ); ?></option>
						</select>
					</td>
				</tr>
			<?php 
		}
		?>
		<?php 

		if ( mppcp_fs()->is__premium_only() || mppcp_fs()->is_trial() ) {
		    
		    if ( mppcp_fs()->can_use_premium_code() || mppcp_fs()->is_trial() ) {
        		?>
				<tr class="authors_tr">
					<th class="titledesc"><?php esc_html_e( 'Author', 'mass-pages-posts-creator' ); ?></th>
					<td>
						<?php $authors = get_users(); ?>
						<select id="authors">
							<option value=""><?php esc_html_e( 'Select Author', 'mass-pages-posts-creator' ); ?></option>
							<?php 
					        if ( isset( $authors ) || !empty($authors) ) {
					            foreach ( $authors as $single_user ) {
					                ?>
									<option value="<?php echo  esc_attr( $single_user->ID ); ?>"><?php echo  wp_kses_post( $single_user->user_login ); ?></option>
									<?php 
								}
							}
							?>
						</select>
					</td>
				</tr>
				<?php 
		    } else {
    	?>
				<tr class="authors_tr">
					<th class="titledesc"><?php esc_html_e( 'Author - Available in Pro', 'mass-pages-posts-creator' ); ?></th>
					<td>
						<select id="authors">
							<option value=""><?php esc_html_e( 'Select Author', 'mass-pages-posts-creator' ); ?></option>
							<option value="" disabled><?php esc_html_e( 'In Pro', 'mass-pages-posts-creator' ); ?></option>
						</select>
					</td>
				</tr>
				<?php 
			}
		} else {
    	?>
				<tr class="authors_tr">
					<th class="titledesc"><?php esc_html_e( 'Author - Available in Pro', 'mass-pages-posts-creator' ); ?></th>
					<td>
						<select id="authors">
							<option value=""><?php esc_html_e( 'Select Author', 'mass-pages-posts-creator' ); ?></option>
							<option value="" disabled><?php esc_html_e( 'In Pro', 'mass-pages-posts-creator' ); ?></option>
						</select>
					</td>
				</tr>
			<?php 
		}
		?>
		<?php 

		if ( mppcp_fs()->is__premium_only() || mppcp_fs()->is_trial() ) {
		    
		    if ( mppcp_fs()->can_use_premium_code() || mppcp_fs()->is_trial() ) {
        	?>
				<tr class="parent_page_id_tr">
					<th class="titledesc"><?php esc_html_e( 'Parent Pages', 'mass-pages-posts-creator' ); ?></th>
					<td>
						<?php 
					    $html = '<select id="page-filter" name="pages[]" class="pages_values">';
					    $html .= '<option value="">Select</option>';
					    $html .= '</select>';
					    echo  wp_kses( $html, mppc_allowed_html_tags() ) ;
					    ?>

					</td>
				</tr>
				<?php 
			} else {
    	?>
				<tr class="parent_page_id_tr">
					<th class="titledesc"><?php esc_html_e( 'Parent Pages - Available in Pro', 'mass-pages-posts-creator' ); ?></th>
					<td>
						<?php 
					    $html = '<select id="page-filter-free" name="pages[]" class="pages_values">';
					    $html .= '<option value="">In Pro</option>';
					    $html .= '</select>';
					    echo  wp_kses( $html, mppc_allowed_html_tags() ) ;
					    ?>

					</td>
				</tr>
				<?php 
			}
		} else {
    	?>
				<tr class="parent_page_id_tr">
					<th class="titledesc"><?php esc_html_e( 'Parent Pages - Available in Pro', 'mass-pages-posts-creator' ); ?></th>
					<td>
						<?php 
					    $html = '<select id="page-filter-free" name="pages[]" class="pages_values">';
					    $html .= '<option value="">In Pro</option>';
					    $html .= '</select>';
					    echo  wp_kses( $html, mppc_allowed_html_tags() ) ;
					    ?>

					</td>
				</tr>
			<?php 
		}
		?>

		</table>
		<p class="submit">
			<input type="button" id="btn_submit" class="button button-primary" name="btn_submit" value="<?php esc_attr_e( 'Create', 'mass-pages-posts-creator' ); ?>" />
		</p>
	</form>
	<div id="message"></div>
	<div id="result"></div>
</div>
<?php
require_once( plugin_dir_path( __FILE__ ) . 'header/plugin-sidebar.php' );