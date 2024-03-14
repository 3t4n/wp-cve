<?php
/**
 * Adds a box to the main column on the Poll edit screens.
 */

if(!function_exists('it_epoll_opinion_metaboxes')) {

	function it_epoll_opinion_metaboxes() {

		add_meta_box(
			'it_epoll_',
			__( 'Add Poll Options', 'it_epoll' ),
			'it_epoll_opinion_metabox_forms',
			'it_epoll_opinion',
			'normal',
			'high'
		);
	}

	add_action( 'add_meta_boxes', 'it_epoll_opinion_metaboxes' );
}


/**
 * Prints the box content.
 * 
 * @param WP_Post $post The object for the current post/page.
 */

if(!function_exists('it_epoll_opinion_metabox_forms')){

function it_epoll_opinion_metabox_forms( $post ) {

	// Add an nonce field so we can check for it later.
	wp_nonce_field( 'it_epoll_poll_metabox_id', 'it_epoll_poll_metabox_id_nonce' );

	/*
	 * Use get_post_meta() to retrieve an existing value
	 * from the database and use the value for the form.
	 */
	$poll_id =$post->ID;
	if(get_post_meta($poll_id, 'it_epoll_poll_option', true )){
		$it_epoll_poll_option = get_post_meta($poll_id, 'it_epoll_poll_option', true );	
	}
	$it_epoll_poll_status = get_post_meta($poll_id, 'it_epoll_poll_status', true );
	$it_epoll_poll_description = get_post_meta($poll_id, 'it_epoll_poll_description', true );
	$it_epoll_poll_theme = get_post_meta($poll_id, 'it_epoll_poll_theme', true );
	$it_epoll_poll_option_id = get_post_meta($poll_id, 'it_epoll_poll_option_id', true );
	$it_epoll_poll_vote_total_count = (int)get_post_meta($post->ID, 'it_epoll_vote_total_count',true);
	$it_epoll_vote_end_date_time = get_post_meta($poll_id, 'it_epoll_vote_end_date_time', true );
	$it_epoll_social_sharing_opt = get_post_meta($poll_id,'it_epoll_social_sharing_opt',true);
   //color scheme
	$it_epoll_poll_option_text_color = get_post_meta($poll_id,'it_epoll_poll_option_text_color',true);
	$it_epoll_poll_button_text_color = get_post_meta($poll_id,'it_epoll_poll_button_text_color',true);
	$it_epoll_poll_color_primary = get_post_meta($poll_id,'it_epoll_poll_color_primary',true);
	$it_epoll_poll_color_secondary = get_post_meta($poll_id,'it_epoll_poll_color_secondary',true);
	$it_epoll_poll_color_mouseover = get_post_meta($poll_id,'it_epoll_poll_color_mouseover',true);
	$it_epoll_poll_color_result_color = get_post_meta($poll_id,'it_epoll_poll_color_result_color',true);
     
	if(!$it_epoll_poll_option_text_color)   $it_epoll_poll_option_text_color	="#6a7795";
	if(!$it_epoll_poll_button_text_color)   $it_epoll_poll_button_text_color    ="#ffffff";
	
	if(!$it_epoll_poll_color_primary)   	$it_epoll_poll_color_primary    	="#3d7afe";
	if(!$it_epoll_poll_color_secondary)   	$it_epoll_poll_color_secondary    	="#f1f5ff";
	if(!$it_epoll_poll_color_mouseover)   	$it_epoll_poll_color_mouseover    	="#ceddff";  
	if(!$it_epoll_poll_color_result_color)  $it_epoll_poll_color_result_color   ="#e8effe";	
	?>
	
	<?php if(($post->post_type == 'it_epoll_opinion') && isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit'){?>
		<div class="it_epoll_short_code">
			<?php echo esc_attr(sprintf('Shortcode for this poll is : <code>[IT_EPOLL_POLL id="%d"][/IT_EPOLL_POLL]</code> (Insert it anywhere in your post/page and show your poll)',$post->ID),'it_epoll');?>
		</div>
	<?php }?>
	<table class="form-table it_epoll_meta_table">
		<tr>
		<td><?php esc_attr_e('Poll Theme','it_epoll');?></td>
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
	</tr>
	<tr>
		<td><?php esc_attr_e('Poll Status','it_epoll');?></td>
		<td>
			<select class="widefat" id="it_epoll_poll_status" name="it_epoll_poll_status" required>
				<option value="live" <?php if($it_epoll_poll_status == 'live') echo esc_attr('selected','it_epoll');?>><?php esc_attr_e('Live','it_epoll');?></option>
				<option value="end" <?php if($it_epoll_poll_status == 'end') echo esc_attr('selected','it_epoll');?>><?php esc_attr_e('End','it_epoll');?></option>
			</select>
		</td>
		<td><?php esc_attr_e('Enable OTP Voting','it_epoll');?>
			<span class="it_epolladmin_pro_badge" style="top: 2px; position: relative;"><i class="dashicons dashicons-star-empty"></i> <?php esc_attr_e('Premium Only','it_epoll');?></span></td>
		<td>	
			<select class="widefat" id="it_epoll_poll_unique_vote" name="it_epoll_poll_unique_vote"  disabled>
				<option value="yes"><?php esc_attr_e('No','it_epoll');?></option>
				<option value="no"><?php esc_attr_e('Yes','it_epoll');?></option>
			</select>
		</td>
		</tr>
		
	<tr>
	<td><?php esc_attr_e('Social Sharing','it_epoll');?></td>
		<td>
			<select name="it_epoll_social_sharing_opt" id="it_epoll_social_sharing_opt" class="widefat">
				<option value="1"<?php if($it_epoll_social_sharing_opt == '1') echo esc_attr(' selected','it_epoll');?>><?php esc_attr_e('Yes','it_epoll');?></option>
				<option value="0"<?php if($it_epoll_social_sharing_opt != '1') echo esc_attr(' selected','it_epoll');?>><?php esc_attr_e('No','it_epoll');?></option>
			</select>
		</td>
		<td><?php esc_attr_e('Multiple Choice','it_epoll');?>
		<span class="it_epolladmin_pro_badge" style="top: 2px; position: relative;"><i class="dashicons dashicons-star-empty"></i> <?php esc_attr_e('Premium Only','it_epoll');?></span></td>
			<td>
			<select name="it_epoll_multivoting" class="widefat" disabled>
				<option><?php esc_attr_e('No','it_epoll');?></option>
				<option><?php esc_attr_e('Yes','it_epoll');?></option>
			</select>
		</td>
		</tr>
	<tr>
		<td colspan="1"><?php esc_attr_e('Poll Description','it_epoll');?></td>
		<td  colspan="3">
			<textarea class="widefat" rows="3" id="it_epoll_poll_description" name="it_epoll_poll_description"><?php echo esc_attr($it_epoll_poll_description,'it_epoll');?></textarea>

		</td>
	</tr>
	</table>
	<table class="form-table">
                <thead>
                    <tr>
                        <th colspan="4">
                                <label><?php esc_attr_e('Poll Color Scheme','it_epoll');?></label>
                            </th>
                        </tr>
                </thead>
                    <tbody>
                    <tr>
				     <td><?php esc_attr_e('Primary & Secondary Color','it_epoll');?></td>
                        <td> 
                            <input type="text" class="widefat it_epoll_color-field" name="it_epoll_poll_color_primary" data-default-color="#3d7afe" value="<?php echo esc_attr($it_epoll_poll_color_primary,'it_epoll');?>"/>
							<input type="text" class="widefat it_epoll_color-field" name="it_epoll_poll_color_secondary" data-default-color="#f1f5ff" value="<?php echo esc_attr($it_epoll_poll_color_secondary,'it_epoll');?>"/>
                        </td>
						<td><?php esc_attr_e('Extra Colors','it_epoll');?></td>
                        <td>
						
							<input type="text" class="widefat it_epoll_color-field" name="it_epoll_poll_color_result_color" data-default-color="#e8effe" value="<?php echo esc_attr($it_epoll_poll_color_result_color,'it_epoll');?>"/>
                            <input type="text" class="widefat it_epoll_color-field" name="it_epoll_poll_color_mouseover" data-default-color="#c0d4ff" value="<?php echo esc_attr($it_epoll_poll_color_mouseover,'it_epoll');?>"/>
                        </td>
                    </tr>
					<tr>
                        <td><?php esc_attr_e('Options Text Color','it_epoll');?></td>
                        <td>
							<input type="text" class="widefat it_epoll_color-field" name="it_epoll_poll_option_text_color"  value="<?php echo esc_attr($it_epoll_poll_option_text_color,'it_epoll');?>"  data-default-color="#6a7795"/>
                        </td>
                        <td><?php esc_attr_e('Button Text Color','it_epoll');?></td>
                        <td>
                            <input type="text" class="widefat it_epoll_color-field" name="it_epoll_poll_button_text_color"  data-default-color="#ffffff" value="<?php echo esc_attr($it_epoll_poll_button_text_color,'it_epoll');?>"/>
                        </td>
                    </tr>
					</tbody>
        	</table>
	
	<?php do_action('it_epoll_opinion_option_meta_ui',array('poll_id'=>$poll_id)); // add extra meta option here?>
	
	<table class="form-table" id="it_epoll_append_option_filed">
	<?php if(!empty($it_epoll_poll_option)):
	$i=0;
	foreach($it_epoll_poll_option as $it_epoll_poll_opt):
			$pollKEYIt = (float)$it_epoll_poll_option_id[$i];
			$it_epoll_poll_vote_count = (int)get_post_meta($post->ID, 'it_epoll_vote_count_'.$pollKEYIt,true);
			
			if(!$it_epoll_poll_vote_count){
				$it_epoll_poll_vote_count = 0;
			}
	?>
	
	<tr class="it_epoll_append_option_filed_tr">
		<td>
			<table class="form-table">
				<tr>
					<td><?php esc_attr_e('Option / Answer','it_epoll');?></td>
					<td>
						<input type="text" class="widefat" id="it_epoll_poll_option" name="it_epoll_poll_option[]" value="<?php echo esc_attr($it_epoll_poll_opt,'it_epoll');?>" required/>
						<input type="hidden" name="it_epoll_poll_option_id[]" id="it_epoll_poll_option_id" value="<?php echo esc_attr($it_epoll_poll_option_id[$i],'it_epoll');?>"/>
					
                    </td>
                    <td>
                    <input type="button" class="button" id="it_epoll_poll_option_rm_btn" name="it_epoll_poll_option_rm_btn" value="Remove">
					
        </td>
				</tr>
				<?php  do_action('it_epoll_opinion_option_meta_ui_option_fields',array('option_index'=>$i,'poll_id'=>$poll_id)); // add extra fields here ?>
			
				<tr>
					<td><?php esc_attr_e('Edit Vote Count','it_epoll');?> 
		<span class="it_epolladmin_pro_badge" style="top: 2px; position: relative;"><i class="dashicons dashicons-star-empty"></i> <?php esc_attr_e('Premium Only','it_epoll');?></span></td>
					<td><input type="number" class="widefat" id="it_epoll_indi_vote" name="it_epoll_indi_vote[]" value="<?php echo esc_attr($it_epoll_poll_vote_count,'it_epoll');?>" disabled=""/>
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
			<td><button type="button" name="it_epoll_form_add_option_btn" class="button it_epoll_add_option_btn" id="it_epoll_opinion_answer_btn"><i class="dashicons-before dashicons-plus-alt"></i> <?php esc_attr_e('Add Answer','it_epoll');?></button></td>
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
if(!function_exists('it_epoll_opinion_save_options')){
function it_epoll_opinion_save_options( $post_id ) {

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
	if ( isset( $_POST['post_type'] ) && 'it_epoll_opinion' == $_POST['post_type'] ) {

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

	//Updating Poll status
	if(isset($_POST['it_epoll_poll_theme'])){
		$it_epoll_poll_theme =  sanitize_text_field($_POST['it_epoll_poll_theme']);
		update_post_meta( $post_id, 'it_epoll_poll_theme', $it_epoll_poll_theme );
	}

	//Updating Poll Description
	if(isset($_POST['it_epoll_poll_description'])){
		$it_epoll_poll_description =  sanitize_text_field($_POST['it_epoll_poll_description']);
		update_post_meta( $post_id, 'it_epoll_poll_description', $it_epoll_poll_description );
	}
	    

	//Updating Poll Social Sharing
	if(isset($_POST['it_epoll_social_sharing_opt'])){
		$it_epoll_social_sharing_opt =  sanitize_text_field($_POST['it_epoll_social_sharing_opt']);
		update_post_meta( $post_id, 'it_epoll_social_sharing_opt', $it_epoll_social_sharing_opt );
	}
	    
	//Updating Poll option color primary
	if(isset($_POST['it_epoll_poll_color_primary'])){
		$it_epoll_poll_color_primary =  sanitize_text_field($_POST['it_epoll_poll_color_primary']);
		update_post_meta( $post_id, 'it_epoll_poll_color_primary', $it_epoll_poll_color_primary );
	}

	//Updating Poll option color secondary
	if(isset($_POST['it_epoll_poll_color_secondary'])){
		$it_epoll_poll_color_secondary =  sanitize_text_field($_POST['it_epoll_poll_color_secondary']);
		update_post_meta( $post_id, 'it_epoll_poll_color_secondary', $it_epoll_poll_color_secondary );
	}
	
	//Updating Poll mouseover color
	if(isset($_POST['it_epoll_poll_color_mouseover'])){
		$it_epoll_poll_color_mouseover =  sanitize_text_field($_POST['it_epoll_poll_color_mouseover']);
		update_post_meta( $post_id, 'it_epoll_poll_color_mouseover', $it_epoll_poll_color_mouseover );
	}


	//Updating Poll result color
	if(isset($_POST['it_epoll_poll_color_result_color'])){
		$it_epoll_poll_color_result_color =  sanitize_text_field($_POST['it_epoll_poll_color_result_color']);
		update_post_meta( $post_id, 'it_epoll_poll_color_result_color', $it_epoll_poll_color_result_color );
	}

	//Updating Poll button text color
	if(isset($_POST['it_epoll_poll_button_text_color'])){
		$it_epoll_poll_button_text_color =  sanitize_text_field($_POST['it_epoll_poll_button_text_color']);
		update_post_meta( $post_id, 'it_epoll_poll_button_text_color', $it_epoll_poll_button_text_color );
	}

	//Updating Poll option text color
	if(isset($_POST['it_epoll_poll_option_text_color'])){
		$it_epoll_poll_option_text_color =  sanitize_text_field($_POST['it_epoll_poll_option_text_color']);
		update_post_meta( $post_id, 'it_epoll_poll_option_text_color', $it_epoll_poll_option_text_color );
	}

	
	//Update Poll Options Name
	if(isset($_POST['it_epoll_poll_option'])){
		$it_epoll_poll_option = array();
		$it_epoll_poll_option = array_map('sanitize_text_field', $_POST['it_epoll_poll_option'] );
		update_post_meta( $post_id, 'it_epoll_poll_option', $it_epoll_poll_option);
	}else{
		update_post_meta( $post_id, 'it_epoll_poll_option', array());
		update_post_meta( $post_id, 'it_epoll_poll_option_id', array());
	}

	
	//Update Poll Options Id
	if(isset($_POST['it_epoll_poll_option_id'])){
		$it_epoll_poll_option_id = array_map('sanitize_text_field', $_POST['it_epoll_poll_option_id']);
		
		update_post_meta( $post_id, 'it_epoll_poll_option_id', $it_epoll_poll_option_id );
	}
	
	do_action('it_epoll_opinion_option_meta_save',array('posted_fields'=>$_POST,'poll_id'=>$post_id)); //option to save custom meta data;
}
add_action( 'save_post', 'it_epoll_opinion_save_options' );
}