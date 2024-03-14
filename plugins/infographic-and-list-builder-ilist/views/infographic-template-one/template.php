<?php 
wp_enqueue_style( 'ilist_info1_stylesheet', OCOPD_TPL_URL1 . "/$template_code/css/style.css");
wp_enqueue_style( 'ilist_img5_light_css', QCOPD_ASSETS_URL1 . "/css/lightbox.min.css");
wp_enqueue_script( 'ilist_img5_light', QCOPD_ASSETS_URL1 . "/js/lightbox-plus-jquery.min.js", array('jquery'));
wp_enqueue_style( 'ilist_info1_google_font', "https://fonts.googleapis.com/css?family=Oswald");
?>

<?php 
	$customCss = get_option( 'sl_custom_style' );

	if( trim($customCss) != "" ) :
?>
	<style>
		<?php echo trim($customCss); ?>
	</style>
	
<?php endif; ?>
<div id="qcld-list-holder" class="qcld-list-hoder">
	<div id="qcopd-list-<?php echo (int)$listId; ?>">
		<div class="qcopd-single-list">
			<?php 
				do_action('qcsl_after_add_btn', $shortcodeAtts);
			?>
	<h3><?php echo get_the_title(); ?></h3>
	<div style="clear:both;margin-bottom:10px"></div>
	<?php
	if(isset($ilist_chart[0])&&!empty($ilist_chart[0])&&$show_chart_position[0]=='top'){
	?>
	<div class="ilist_chart_map_wrap">
		<?php echo do_shortcode($ilist_chart[0]);?>
	</div>
	<?php
	}
	?>
	<ul class="ilist_info1_list-menu">
	<?php foreach( $lists as $list_sl ) : ?>
	<?php 
		$cnt=1;
		if( $item_orderby == 'upvote' )
		{
			usort($list_sl, "ilist_custom_sort_by_tpl_upvotes");
		}
	?>
	<?php foreach($list_sl as $list) : ?>
			<?php 
				if($cnt%2==1){
			?>
			<li id="qcld_sl_<?php echo get_the_ID()."_".$cnt ?>" style="width: 100%;">
			<?php
				}else{
			?>
			<li id="qcld_sl_<?php echo get_the_ID()."_".$cnt ?>" class="ilist_info1_right-image" style="width: 100%;">
			<?php
				}
			?>
																		
																					
						<div class="ilist_info1_list-content ilist_info1_one-column">
							<div class="ilist_info1_title">
								<div class="ilist_info1_title-border">
									<h2 class="ilist_info1_item-title" style="margin-top: 0px;">
										<?php 
											if(isset($list['qcld_text_title']) && !empty($list['qcld_text_title'])){
										?>
											
											<?php 
												echo esc_html__($list['qcld_text_title']);
											?>
											
										<?php
											}
										?>
									<div class="upvote-section" style="margin-top: 9px;font-size: 14px;">
										<span data-post-id="<?php echo get_the_ID(); ?>" data-item-title="<?php echo isset($list['qcld_text_title']) ? esc_html__(trim($list['qcld_text_title'])):''; ?>" data-item-desc="<?php echo isset($list['qcld_text_desc']) ? esc_html__(trim($list['qcld_text_desc'])):''; ?>" data-item-id="<?php echo isset($list['qcld_text_desc']) ? esc_html__(trim($list['qcld_counter'])):''; ?>" class="upvote-btn-ilist upvote-on">
											<i class="fa fa-thumbs-up"></i>
										</span>
										<span class="upvote-count-ilist">
											<?php 
											  if( isset($list['sl_thumbs_up']) && (int)$list['sl_thumbs_up'] > 0 ){
												echo (int)$list['sl_thumbs_up'];
											  }
											?>
										</span>
									</div>										
									</h2>
								</div>
							</div>

			<?php 
				if($cnt%2==1){
			?>
							<div class="ilist_info1_left_content">
							
							<?php 
								if(isset($list['qcld_text_image']) && !empty($list['qcld_text_image'])){
									echo '<a class="example-image-link" href="'. esc_url(@$list['qcld_text_image']) .'" data-lightbox="example-'. $cnt.'"><img class="ilist_info1_example-image" src="'. esc_url(@$list['qcld_text_image']).'" alt="image-1" /></a>';
								}else{
									echo '<img src="'.OCOPD_TPL_URL1.'/'.$template_code.'/images/list-image.JPG" alt=""/>';
								}
							?>
								
							</div>
							<div class="ilist_info1_right_content">
								<p class="ilist_info1_list-sub-title">
								<?php 
									if(isset($list['qcld_text_desc']) && !empty($list['qcld_text_desc'])){
								?>
									
									<?php 
										echo ($list['qcld_text_desc']);
									?>
															
								<?php
									}
								?>
								</p>
							</div>
			<?php
				}else{
			?>
							<div class="ilist_info1_right_content">
								<p class="ilist_info1_list-sub-title">
								<?php 
									if( isset($list['qcld_text_desc']) && !empty($list['qcld_text_desc']) ){
								?>
									
									<?php 
										echo ($list['qcld_text_desc']);
									?>
															
								<?php
									}
								?>
								</p>
							</div>
							<div class="ilist_info1_left_content">
							<?php 
								if(isset($list['qcld_text_image']) && !empty($list['qcld_text_image'])){
									echo '<a class="example-image-link" href="'. esc_url(@$list['qcld_text_image']) .'" data-lightbox="example-'. $cnt.'"><img class="ilist_info1_example-image" src="'. esc_url(@$list['qcld_text_image']).'" alt="image-1" /></a>';
								}else{
									echo '<img src="'.OCOPD_TPL_URL1.'/'.$template_code.'/images/list-image.JPG" alt=""/>';
								}
							?>
								
							</div>
			<?php
				}
			?>

							

						</div>
					
					
				</li>
		<?php $cnt++; ?>
		<?php endforeach; ?>
		<?php endforeach; ?>
	</ul>
		<div style="clear:both;margin-bottom:10px"></div>
	<?php
	if(isset($ilist_chart[0])&&!empty($ilist_chart[0])&&$show_chart_position[0]=='bottom'){
	?>
	<div class="ilist_chart_map_wrap">
		<?php echo do_shortcode($ilist_chart[0]);?>
	</div>
	<?php
	}
	?>
	</div>
	</div>
</div>
<div></div>

