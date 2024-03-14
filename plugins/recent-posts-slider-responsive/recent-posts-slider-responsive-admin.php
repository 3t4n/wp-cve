<?php 
	if ( $_POST['rpf_opt_hidden'] == 'Y' ) {
		
		$post_per_slide = $_POST['rpf_post_per_slide'];
		update_option('rpf_post_per_slide', $post_per_slide);
		
		$total_posts = $_POST['rpf_total_posts'];
		if ( is_numeric($total_posts) )
			update_option('rpf_total_posts', $total_posts);
		else
			$error[] = __('Please enter total posts in numbers.', 'rpf');
		
		$slider_image_size = $_POST['rpf_slider_image_size'];
		update_option('rpf_slider_image_size', $slider_image_size);
		
		$category_ids = $_POST['rpf_category_ids'];
		update_option('rpf_category_ids', $category_ids);
		
		$post_include_ids = $_POST['rpf_post_include_ids'];
		update_option('rpf_post_include_ids', $post_include_ids);
		
		$post_exclude_ids = $_POST['rpf_post_exclude_ids'];
		update_option('rpf_post_exclude_ids', $post_exclude_ids);
		
		$post_title_color = $_POST['rpf_post_title_color'];
		update_option('rpf_post_title_color', $post_title_color);
		
		$post_title_bg_color = $_POST['rpf_post_title_bg_color'];
		update_option('rpf_post_title_bg_color', $post_title_bg_color);
		
		$slider_speed = $_POST['rpf_slider_speed'];
		update_option('rpf_slider_speed', $slider_speed);
				
		$rps_automatic = $_POST['rps_automatic'];
		update_option('rps_automatic', $rps_automatic);
		
		$custom_css = $_POST['rpf_custom_css'];
		update_option('rpf_custom_css', $custom_css);
		
		?>
		<?php if( empty($error) ){ ?>
		<div class="updated"><p><strong><?php _e('Settings saved.', 'rpf'); ?></strong></p></div>
		<?php }else{ ?>
		<div class="error"><p><strong><?php 
			foreach ( $error as $key=>$val ) {
				_e($val, 'rpf'); 
				echo "<br/>";
			}
		?></strong></p></div>
		<?php }
	} else {
		$post_per_slide = get_option('rpf_post_per_slide');
		$total_posts = get_option('rpf_total_posts');
		$slider_image_size = get_option('rpf_slider_image_size');
		$category_ids = get_option('rpf_category_ids');
		$post_include_ids = get_option('rpf_post_include_ids');
		$post_exclude_ids = get_option('rpf_post_exclude_ids');
		$post_title_color = get_option('rpf_post_title_color');
		$post_title_bg_color = get_option('rpf_post_title_bg_color');
		$slider_speed = get_option('rpf_slider_speed');
		$rps_automatic = get_option('rps_automatic');
		$custom_css = get_option('rpf_custom_css');
	}
?>
<script>
  jQuery(function() {
    jQuery( "#accordion" ).accordion({
    	heightStyle: "content"
    });
  });
  </script>
<div class="wrap">
<?php echo "<h2>" . __( 'Recent Posts Slider Responsive Settings', 'rpf') . "</h2>"; ?>
<form name="rpf_form" method="post" action="<?php echo admin_url('options-general.php').'?page='.$_GET['page']; ?>">
	<input type="hidden" name="rpf_opt_hidden" value="Y">
	<div id="accordion">
		<h3><?php _e('Slider Display Options', 'rpf'); ?></h3>
		<div>
			<div class="slide-opt-left wd1">
				<ul>
					<li>
						<label for="total_posts"><?php _e('Total Posts to show', 'rpf'); ?></label>
						<input type="text" name="rpf_total_posts" value="<?php echo $total_posts; ?>" size="9" />
						<span><?php _e('No of posts to show in a slider', 'rpf'); ?></span>
					</li>
					<li>
						<label for="slider_image_size"><?php _e('Slider Image Size', 'rpf'); ?></label>
						<select name="rpf_slider_image_size">
							<option value="1" <?php if($slider_image_size==1){echo 'selected';} ?>><?php _e('Thumbnail', 'rpf'); ?></option>
							<option value="2" <?php if($slider_image_size==2){echo 'selected';} ?>><?php _e('Medium', 'rpf'); ?></option>
							<option value="3" <?php if($slider_image_size==3){echo 'selected';} ?>><?php _e('Large', 'rpf'); ?></option>
							<option value="4" <?php if($slider_image_size==4){echo 'selected';} ?>><?php _e('Full', 'rpf'); ?></option>
						</select>
					</li>	
				</ul>
			</div>
			<div class="slide-opt-left wd1">
				<ul>
					<li>
						<label for="no_of_posts_per_slide"><?php _e('No. of post to show per slide', 'rpf'); ?></label>
						<select name="rpf_post_per_slide">
							<?php for( $i=1; $i<=6; $i++ ){ ?>
								<option value="<?php echo $i; ?>" <?php if($post_per_slide==$i){echo 'selected';} ?>><?php echo $i; ?></option>
							<?php } ?>
						</select>
					</li>
					<li>	
						<input type="checkbox" name="rps_automatic" value="false" data-value="<?php echo $rps_automatic?>" <?php if ($rps_automatic=="false") { echo 'checked="checked"';}  ?> /><?php _e('Don\'t Auto Slide?', 'rpf'); ?>
						<span><?php _e('Check to disable auto slide', 'rpf'); ?></span>
					</li>
				</ul>
			</div>
			<div class="slide-opt-left">
				<ul>
					<li>
						<label for="slider_speed"><?php _e('Slider Speed', 'rpf'); ?></label>
						<input type="text" name="rpf_slider_speed" value="<?php echo $slider_speed; ?>" size="9" />
						<span><?php _e('ex : 10 (in seconds)', 'rpf'); ?></span>
					</li>
				</ul>
			</div>
			<div class="div-clear"></div>
		</div>
			<h3><?php _e('Slider Post / Category Options', 'rpf'); ?></h3>
		<div>
			<div class="slide-opt-left wd1">
				<ul>
					<li>
						<label for="category_id"><?php _e('Category IDs', 'rpf'); ?></label>
						<input type="text" name="rpf_category_ids" value="<?php echo $category_ids; ?>" size="40" />
						<span><?php _e('ex : 1,2,3,-4 (Use negative id to exclude)', 'rpf'); ?></span>
					</li>
					<li>
						<label for="posts_title_color"><?php _e('Posts Title Color', 'rpf'); ?></label>
						<input type="text" name="rpf_post_title_color" value="<?php echo $post_title_color; ?>" size="40" class="rpf-color-picker" data-default-color="#666666" />
						<span><?php _e('ex', 'rpf'); ?> : ef4534</span>
					</li>
				</ul>
			</div>
			<div class="slide-opt-left wd1">
				<ul>
					<li>
						<label for="posts_to_include"><?php _e('Posts to include', 'rpf'); ?></label>
						<input type="text" name="rpf_post_include_ids" value="<?php echo $post_include_ids; ?>" size="40" />
						<span><?php _e('Seperated by commas', 'rpf'); ?></span>
					</li>
					<li>
						<label for="posts_to_exclude"><?php _e('Posts to exclude', 'rpf'); ?></label>
						<input type="text" name="rpf_post_exclude_ids" value="<?php echo $post_exclude_ids; ?>" size="40" />
						<span><?php _e('Seperated by commas', 'rpf'); ?></span>
					</li>
					<li>
						<label for="posts_title_bg_color"><?php _e('Posts Title Backgroud Color', 'rpf'); ?></label>
						<input type="text" name="rpf_post_title_bg_color" value="<?php echo $post_title_bg_color; ?>" size="40" class="rpf-color-picker" />
						<span><?php _e('ex', 'rpf'); ?> : ef4534</span>
					</li>
				</ul>
			</div>
			<div class="slide-opt-left">
				<ul>			
				</ul>
			</div>
			<div class="div-clear"></div>
		</div>
			<h3><?php _e('Custom CSS', 'rpf'); ?></h3>
		<div>
			<div class="div-left wd2 space">
				<label><?php _e('Write your custom css here:', 'rpf'); ?></label><br/><br/>
				<textarea name="rpf_custom_css" rows="15" cols="70" /><?php echo stripslashes($custom_css); ?></textarea>
			</div>
			<div class="div-clear"></div>
		</div>
		</div><br/><br/>
	<input type="submit" name="Submit" class="button-primary" value="<?php _e('Save Changes','rpf') ?>" />
</form>

</div>