<?php
/**
 * Adds a box to the main column on the Poll edit screens.
 */

if(!function_exists('it_epoll_poll_metaboxes')) {

	function it_epoll_poll_metaboxes() {

		add_meta_box(
			'it_epoll_',
			__( 'Add Voting Poll Options', 'it_epoll' ),
			'it_epoll_poll_metabox_forms',
			'it_epoll_poll',
			'normal',
			'high'
		);
	}

	add_action( 'add_meta_boxes', 'it_epoll_poll_metaboxes' );
}


/**
 * Prints the box content.
 * 
 * @param WP_Post $post The object for the current post/page.
 */

if(!function_exists('it_epoll_poll_metabox_forms')){

function it_epoll_poll_metabox_forms( $post ) {

	// Add an nonce field so we can check for it later.
	wp_nonce_field( 'it_epoll_poll_metabox_id', 'it_epoll_poll_metabox_id_nonce' );

	/*
	 * Use get_post_meta() to retrieve an existing value
	 * from the database and use the value for the form.
	 */

	$poll_id = $post->ID;

	$it_epoll_poll_status = get_post_meta( $poll_id, 'it_epoll_poll_status', true );
	if(get_post_meta( $poll_id, 'it_epoll_poll_option', true )){
		$it_epoll_poll_option = get_post_meta( $poll_id, 'it_epoll_poll_option', true );	
	}
	
	$it_epoll_poll_theme = get_post_meta( $poll_id, 'it_epoll_poll_theme', true );
	$it_epoll_poll_option_img = get_post_meta( $poll_id, 'it_epoll_poll_option_img', true );
	$it_epoll_poll_option_cover_img = get_post_meta( $poll_id, 'it_epoll_poll_option_cover_img', true );
	$it_epoll_poll_option_id = get_post_meta( $poll_id, 'it_epoll_poll_option_id', true );
	$it_epoll_poll_style = get_post_meta( $poll_id, 'it_epoll_poll_style', true );
	$it_epoll_poll_ui = get_post_meta( $poll_id, 'it_epoll_poll_ui', true );
	$it_epoll_poll_vote_total_count = (int)get_post_meta($poll_id, 'it_epoll_vote_total_count',true);

	//Color Scheme
	$it_epoll_poll_container_color_primary = get_post_meta( $poll_id, 'it_epoll_poll_container_color_primary', true );
	$it_epoll_poll_container_color_secondary   = get_post_meta( $poll_id, 'it_epoll_poll_container_color_secondary', true );
	$it_epoll_poll_button_color_primary  = get_post_meta( $poll_id, 'it_epoll_poll_button_color_primary', true );
	$it_epoll_poll_button_color_secondary = get_post_meta( $poll_id, 'it_epoll_poll_button_color_secondary', true );
	$it_epoll_poll_container_text_color = get_post_meta( $poll_id, 'it_epoll_poll_container_text_color', true );
	$it_epoll_poll_button_text_color = get_post_meta( $poll_id, 'it_epoll_poll_button_text_color', true );
	$it_epoll_social_sharing_opt = get_post_meta($poll_id,'it_epoll_social_sharing_opt',true);
	if(!$it_epoll_poll_container_color_primary) 	$it_epoll_poll_container_color_primary = "#eeeeee";
	if(!$it_epoll_poll_container_color_secondary) 	$it_epoll_poll_container_color_secondary = "#0c57a5";
	if(!$it_epoll_poll_button_color_primary) 		$it_epoll_poll_button_color_primary = "#ffd86f";
	if(!$it_epoll_poll_button_color_secondary) 		$it_epoll_poll_button_color_secondary = "#fc6262";
	if(!$it_epoll_poll_container_text_color) 		$it_epoll_poll_container_text_color = "#ffffff";
	if(!$it_epoll_poll_button_text_color) 			$it_epoll_poll_button_text_color = "#ffffff";
	?>
	<?php if(($post->post_type == 'it_epoll_poll') && isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit'){?>
		<div class="it_epoll_short_code">
			<?php echo esc_attr(sprintf('Shortcode for this poll is : <code>[IT_EPOLL_VOTING id="%d"][/IT_EPOLL_VOTING]</code> (Insert it anywhere in your post/page and show your poll)',$post->ID),'it_epoll');?>
		</div>
	<?php }?>

	

	<table class="form-table it_epoll_meta_table">
		
	<tr>
		<td colspan="1"><?php esc_attr_e('Voting Poll Theme / Design','it_epoll');?></td>
		<td>

		
			<select class="widefat" id="it_epoll_poll_theme" name="it_epoll_poll_theme" value="" required>
			<?php $themes_available = get_it_epoll_local_themes_data();
					if($themes_available){
						$active_themes = array('default');
						if(get_option('it_epoll_active_theme')){
							$active_themes = get_option('it_epoll_active_theme');
						}
						
						$themes = json_decode($themes_available,TRUE);
						foreach( $themes as $theme  ){
							
							if(in_array($theme['Id'],$active_themes)){
								$theme_id = $theme['Id'];
								$theme_name = $theme['Name'];
								?>
								<option value="<?php echo esc_attr($theme_id,'it_epoll');?>"<?php if($it_epoll_poll_theme == $theme_id) echo esc_attr(' checked','it_epoll');?>><?php echo esc_attr($theme_name,'it_epoll');?></option>
							<?php }else{?>
								<option value="<?php echo esc_attr($theme_id,'it_epoll');?>" disabled><?php echo esc_attr($theme_name,'it_epoll');?></option>
								
							<?php }
						}
					}
					?>	
			</select>
		</td>
		<td><?php esc_attr_e('Social Sharing','it_epoll');?></td>
		<td>
			<select name="it_epoll_social_sharing_opt" id="it_epoll_social_sharing_opt" class="widefat">
				<option value="1"<?php if($it_epoll_social_sharing_opt == '1') echo esc_attr(' selected','it_epoll');?>><?php esc_attr_e('Yes','it_epoll');?></option>
				<option value="0"<?php if($it_epoll_social_sharing_opt != '1') echo esc_attr(' selected','it_epoll');?>><?php esc_attr_e('No','it_epoll');?></option>
			</select>
		</td>
	</tr>
	<tr>
		<td><?php esc_attr_e('Voting Poll Status','it_epoll');?></td>
		<td>
			<select class="widefat" id="it_epoll_poll_status" name="it_epoll_poll_status" value="" required>
				<option value="live" <?php if($it_epoll_poll_status == 'live') echo esc_attr('selected','it_epoll');?>><?php esc_attr_e('Live','it_epoll');?></option>
				<option value="end" <?php if($it_epoll_poll_status == 'end') echo esc_attr('selected','it_epoll');?>><?php esc_attr_e('End','it_epoll');?></option>
			</select>
		</td>
		<td><?php esc_attr_e('Enable OTP Voting','it_epoll');?>
			<span class="it_epolladmin_pro_badge" style="top: 2px; position: relative;"><i class="dashicons dashicons-star-empty"></i> <?php esc_attr_e('Premium Only','it_epoll');?></span></td>
		<td>	
			<select class="widefat" id="it_epoll_poll_ui" name="it_epoll_poll_ui"  disabled>
				<option><?php esc_attr_e('No','it_epoll');?></option>
				<option><?php esc_attr_e('Yes','it_epoll');?></option>
			</select>
		</td>
		</tr>
		<tr>
		<td><?php esc_attr_e('Voting Poll Style','it_epoll');?></td>
		<td>

			<select class="widefat" id="it_epoll_poll_style" name="it_epoll_poll_style" value="" required>
				<option value="grid" <?php if($it_epoll_poll_style == 'grid') echo esc_attr('selected','it_epoll');?>><?php esc_attr_e('Grid','it_epoll');?></option>
				<option value="list" <?php if($it_epoll_poll_style == 'list') echo esc_attr('selected','it_epoll');?>><?php esc_attr_e('List','it_epoll');?></option>
			</select>
		</td>
		<td><?php esc_attr_e('Multi Voting ','it_epoll');?>
		<span class="it_epolladmin_pro_badge" style="top: 2px; position: relative;"><i class="dashicons dashicons-star-empty"></i> <?php esc_attr_e('Premium Only','it_epoll');?></span></td>
			<td>
			<select name="it_epoll_multivoting" class="widefat" disabled="">
				<option><?php esc_attr_e('No','it_epoll');?></option>
				<option><?php esc_attr_e('Yes','it_epoll');?></option>
			</select>
		</td>
		</tr>
	</table>
	
	<table class="form-table">
                <thead>
                    <tr>
                        <th colspan="4">
                                <label><?php esc_attr_e('Contest Color Scheme','it_epoll');?></label>
                            </th>
                        </tr>
                </thead>
                    <tbody>
                    <tr>
                        <td><?php esc_attr_e('Container Background Color','it_epoll');?></td>
						<td>
                            <input type="text" class="widefat it_epoll_color-field" name="it_epoll_poll_container_color_primary" value="<?php echo esc_attr($it_epoll_poll_container_color_primary,'it_epoll');?>"/>
							<input type="text" class="widefat it_epoll_color-field" name="it_epoll_poll_container_color_secondary" value="<?php echo esc_attr($it_epoll_poll_container_color_secondary,'it_epoll');?>"/>
                           
						</td>
                        <td><?php esc_attr_e('Button Background Color','it_epoll');?></td>
                        <td>
                            <input type="text" class="widefat it_epoll_color-field" name="it_epoll_poll_button_color_primary" value="<?php echo esc_attr($it_epoll_poll_button_color_primary,'it_epoll');?>"/>
                            <input type="text" class="widefat it_epoll_color-field" name="it_epoll_poll_button_color_secondary" value="<?php echo esc_attr($it_epoll_poll_button_color_secondary,'it_epoll');?>"/>
                           
						</td>
						
                    </tr>
					<tr>
                        <td><?php esc_attr_e('Contest Title Color','it_epoll');?></td>
						<td>
                            <input type="text" class="widefat it_epoll_color-field" name="it_epoll_poll_container_text_color" value="<?php echo esc_attr($it_epoll_poll_container_text_color,'it_epoll');?>"/>
                        </td>
                        <td><?php esc_attr_e('Button Text Color','it_epoll');?></td>
                        <td>
                            <input type="text" class="widefat it_epoll_color-field" name="it_epoll_poll_button_text_color" value="<?php echo esc_attr($it_epoll_poll_button_text_color,'it_epoll');?>"/>
                        </td>
						<td colspan="2"></td>
                    </tr>
            </tbody>
        </table>
		
	<?php do_action('it_epoll_poll_option_meta_after_color_scheme',array('poll_id'=>$poll_id));?>
	
	<?php do_action('it_epoll_poll_option_meta_ui',array('poll_id'=>$poll_id));?>
	<table class="form-table" id="it_epoll_append_option_filed">
	<?php if(!empty($it_epoll_poll_option)):
	$i=0;
	foreach($it_epoll_poll_option as $it_epoll_poll_opt):
			$pollKEYIt = (float)$it_epoll_poll_option_id[$i];
			$it_epoll_poll_vote_count = (int)get_post_meta($poll_id, 'it_epoll_vote_count_'.$pollKEYIt,true);
			
			if(!$it_epoll_poll_vote_count){
				$it_epoll_poll_vote_count = 0;
			}
	?>
	<tr class="it_epoll_append_option_filed_tr">
		<td>
			<table class="form-table">
				<tr>
					<td><?php esc_attr_e('Candidate Name','it_epoll');?></td>
					<td>
						<input type="text" class="widefat" id="it_epoll_poll_option" name="it_epoll_poll_option[]" value="<?php echo esc_attr($it_epoll_poll_opt,'it_epoll');?>" required/>
					</td>
				</tr>
				<tr>
					<td><?php esc_attr_e('Candidate Image/Photo','it_epoll');?></td>
					<td><input type="url" class="widefat" id="it_epoll_poll_option_img" name="it_epoll_poll_option_img[]" value="<?php if(!empty($it_epoll_poll_option_img)){ echo esc_attr($it_epoll_poll_option_img[$i],'it_epoll');}?>"/>
						<input type="hidden" name="it_epoll_poll_option_id[]" id="it_epoll_poll_option_id" value="<?php echo esc_attr($it_epoll_poll_option_id[$i],'it_epoll');?>"/>
					</td>
					<td>
						<input type="button" class="button" id="it_epoll_poll_option_btn" name="it_epoll_poll_option_btn" value="<?php esc_attr_e('Upload','it_epoll');?>">
					</td>
				</tr>
				<tr>
					<td><?php esc_attr_e('Candidate Cover Image/Photo','it_epoll');?></td>
					<td><input type="url" class="widefat" id="it_epoll_poll_option_cover_img" name="it_epoll_poll_option_cover_img[]" value="<?php if(!empty($it_epoll_poll_option_cover_img)){ echo esc_attr($it_epoll_poll_option_cover_img[$i],'it_epoll');}?>"/>
					</td>
					<td>
						<input type="button" class="button" id="it_epoll_poll_option_ci_btn" name="it_epoll_poll_option_ci_btn" value="<?php esc_attr_e('Upload','it_epoll');?>">
					</td>
				</tr>
				<tr>
					<td><?php esc_attr_e('Edit Vote Count / Result','it_epoll');?> 
		<span class="it_epolladmin_pro_badge" style="top: 2px; position: relative;"><i class="dashicons dashicons-star-empty"></i> <?php esc_attr_e('Premium Only','it_epoll');?></span></td>
					<td><input type="number" class="widefat" id="it_epoll_indi_vote" name="it_epoll_indi_vote[]" value="<?php echo esc_attr($it_epoll_poll_vote_count,'it_epoll');?>" disabled=""/>
					</td>
				</tr>
				<?php do_action('it_epoll_poll_option_meta_ui_option_fields',array('option_index'=>$i,'poll_id'=>$poll_id)); // add extra fields here ?>
				<tr>
					<td colspan="2">
						<input type="button" class="button" id="it_epoll_poll_option_rm_btn" name="it_epoll_poll_option_rm_btn" value="Remove This Option">
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<?php 
	$i++;
	endforeach;
	endif; ?>
	</table>
	
	<table class="form-table">
		<tr>
			<td><button type="button" name="it_epoll_form_add_option_btn" class="button it_epoll_add_option_btn" id="it_epoll_add_option_btn"><i class="dashicons-before dashicons-plus-alt"></i> <?php esc_attr_e('Add Option','it_epoll');?></button></td>
		</tr>
	</table>
	
	<table class="form-table">
		<tr>
			<td class="it_epoll_short_code">
			<?php esc_attr_e('Developed & Designed By','it_epoll');?>
				<a href="<?php echo esc_url('https://www.infotheme.in','it_epoll');?>"><?php esc_attr_e('InfoTheme Inc.','it_epoll');?></a> 
				| <?php esc_attr_e('For Customization ','it_epoll');?><a href="<?php echo esc_url('https://infotheme.in/#contact','it_epoll');?>"><?php esc_attr_e('Hire Us Today','it_epoll');?></a>
				| <a href="<?php echo esc_url('http://infotheme.in/products/plugins/epoll-wp-voting-system/#forum','it_epoll');?>"><?php esc_attr_e('Support / Live Chat','it_epoll');?></a> 
				| <a href="<?php echo esc_url('http://infotheme.in/products/plugins/epoll-wp-voting-system/#docs','it_epoll');?>"><?php esc_attr_e('Documentation','it_epoll');?></a>
		
			</td>
		</tr>
	</table>
	
	<?php
	}
}


/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
if(!function_exists('it_epoll_poll_save_options')){
function it_epoll_poll_save_options( $post_id ) {

	/*
	 * We need to verify this came from our screen and with proper authorization,
	 * because the save_post action can be triggered at other times.
	 */

	// Check if our nonce is set.
	if ( ! isset( $_POST['it_epoll_poll_metabox_id_nonce'] ) ) {
		return;
	}

	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST['it_epoll_poll_metabox_id_nonce'], 'it_epoll_poll_metabox_id' ) ) {
		return;
	}

	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// Check the user's permissions.
	if ( isset( $_POST['post_type'] ) && 'it_epoll_poll' == $_POST['post_type'] ) {

		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}

	} else {

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}

	
	// Sanitize user input & Update the meta field in the database.
	
	//Updating Poll status
	if(isset($_POST['it_epoll_poll_status'])){
		$it_epoll_poll_status =  sanitize_text_field($_POST['it_epoll_poll_status']);
		update_post_meta( $post_id, 'it_epoll_poll_status', $it_epoll_poll_status );
	}

	//Updating Poll theme
	if(isset($_POST['it_epoll_poll_theme'])){
		$it_epoll_poll_theme =  sanitize_text_field($_POST['it_epoll_poll_theme']);
		update_post_meta( $post_id, 'it_epoll_poll_theme', $it_epoll_poll_theme );
	}

	//Updating Poll UI
	if(isset($_POST['it_epoll_poll_ui'])){
		$it_epoll_poll_ui =  sanitize_text_field($_POST['it_epoll_poll_ui']);
		update_post_meta( $post_id, 'it_epoll_poll_ui', $it_epoll_poll_ui );
	}

	//Updating Poll Style
	if(isset($_POST['it_epoll_poll_style'])){
		$it_epoll_poll_style =  sanitize_text_field($_POST['it_epoll_poll_style']);
		update_post_meta( $post_id, 'it_epoll_poll_style', $it_epoll_poll_style );
	}


	//Updating Poll Container Primary Color
	if(isset($_POST['it_epoll_poll_container_color_primary'])){
		$it_epoll_poll_container_color_primary =  sanitize_text_field($_POST['it_epoll_poll_container_color_primary']);
		update_post_meta( $post_id, 'it_epoll_poll_container_color_primary', $it_epoll_poll_container_color_primary );
	}

	   
	//Updating Poll Social Sharing
	if(isset($_POST['it_epoll_social_sharing_opt'])){
		$it_epoll_social_sharing_opt =  sanitize_text_field($_POST['it_epoll_social_sharing_opt']);
		update_post_meta( $post_id, 'it_epoll_social_sharing_opt', $it_epoll_social_sharing_opt );
	}

	//Updating Poll Container Secondary Color
	if(isset($_POST['it_epoll_poll_container_color_secondary'])){
		$it_epoll_poll_container_color_secondary =  sanitize_text_field($_POST['it_epoll_poll_container_color_secondary']);
		update_post_meta( $post_id, 'it_epoll_poll_container_color_secondary', $it_epoll_poll_container_color_secondary );
	}

	//Updating Poll Container Text Color
	if(isset($_POST['it_epoll_poll_container_text_color'])){
		$it_epoll_poll_container_text_color =  sanitize_text_field($_POST['it_epoll_poll_container_text_color']);
		update_post_meta( $post_id, 'it_epoll_poll_container_text_color', $it_epoll_poll_container_text_color );
	}
	//Updating Poll Button Text Color
	if(isset($_POST['it_epoll_poll_button_text_color'])){
		$it_epoll_poll_button_text_color =  sanitize_text_field($_POST['it_epoll_poll_button_text_color']);
		update_post_meta( $post_id, 'it_epoll_poll_button_text_color', $it_epoll_poll_button_text_color );
	}

	//Updating Poll Button Background primary Color
	if(isset($_POST['it_epoll_poll_button_color_primary'])){
		$it_epoll_poll_button_color_primary =  sanitize_text_field($_POST['it_epoll_poll_button_color_primary']);
		update_post_meta( $post_id, 'it_epoll_poll_button_color_primary', $it_epoll_poll_button_color_primary );
	}

	//Updating Poll Button Background Secondary Color
	if(isset($_POST['it_epoll_poll_button_color_secondary'])){
		$it_epoll_poll_button_color_secondary =  sanitize_text_field($_POST['it_epoll_poll_button_color_secondary']);
		update_post_meta( $post_id, 'it_epoll_poll_button_color_secondary', $it_epoll_poll_button_color_secondary );
	}


	//Update Poll Options Name
	if(isset($_POST['it_epoll_poll_option'])){
		$it_epoll_poll_option = array();
		$it_epoll_poll_option = array_map('sanitize_text_field', $_POST['it_epoll_poll_option'] );
		update_post_meta( $post_id, 'it_epoll_poll_option', $it_epoll_poll_option);
	}else{
		update_post_meta( $post_id, 'it_epoll_poll_option', array());
		update_post_meta( $post_id, 'it_epoll_poll_option_img', array());
		update_post_meta( $post_id, 'it_epoll_poll_option_cover_img', array());
		update_post_meta( $post_id, 'it_epoll_poll_option_id', array());
	}
	
	//Update Poll Options Image
	if(isset($_POST['it_epoll_poll_option_img'])){
		$it_epoll_poll_option_img = array_map('sanitize_text_field', $_POST['it_epoll_poll_option_img']);
		update_post_meta( $post_id, 'it_epoll_poll_option_img', $it_epoll_poll_option_img );
	}

	//Update Poll Options Cover
	if(isset($_POST['it_epoll_poll_option_cover_img'])){
		
		$it_epoll_poll_option_cover_img = array_map('sanitize_text_field', $_POST['it_epoll_poll_option_cover_img']);
		
		update_post_meta( $post_id, 'it_epoll_poll_option_cover_img', $it_epoll_poll_option_cover_img );
	}
	
	//Update Poll Options Id
	if(isset($_POST['it_epoll_poll_option_id'])){
		$it_epoll_poll_option_id = array_map('sanitize_text_field', $_POST['it_epoll_poll_option_id']);
		
		update_post_meta( $post_id, 'it_epoll_poll_option_id', $it_epoll_poll_option_id );
	}

	do_action('it_epoll_poll_option_meta_save',array('posted_fields'=>$_POST,'poll_id'=>$post_id));
}
add_action( 'save_post', 'it_epoll_poll_save_options' );
}