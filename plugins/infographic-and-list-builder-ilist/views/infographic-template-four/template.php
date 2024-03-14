<?php 
wp_enqueue_style( 'ilist_info4_stylesheet', OCOPD_TPL_URL1 . "/$template_code/css/style.css");
wp_enqueue_style( 'ilist_info4_stylesheet_responsive', OCOPD_TPL_URL1 . "/$template_code/css/responsive.css");
wp_enqueue_style( 'ilist_info4_stylesheet_animate', OCOPD_TPL_URL1 . "/$template_code/css/animate.css");
wp_enqueue_style( 'ilist_img5_light_css', QCOPD_ASSETS_URL1 . "/css/lightbox.min.css");
wp_enqueue_script( 'ilist_img5_light', QCOPD_ASSETS_URL1 . "/js/lightbox-plus-jquery.min.js", array('jquery'));
wp_enqueue_style( 'ilist_info4_google_font1', "https://fonts.googleapis.com/css?family=Oswald|Raleway");
wp_enqueue_style( 'ilist_info4_google_font2', "https://fonts.googleapis.com/css?family=Oswald");
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
	<div id="qcopd-list-<?php echo (int)$listId; ?>" >
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
	<ul>
	<?php foreach( $lists as $list_sl ) : ?>
	<?php 
		$cnt=1;
		if( $item_orderby == 'upvote' )
		{
			usort($list_sl, "ilist_custom_sort_by_tpl_upvotes");
		}
	?>
	<?php foreach($list_sl as $list) : ?>
		<li id="qcld_sl_<?php echo get_the_ID()."_".$cnt ?>" class="ilist_info4_list-style-14 ilist_info4_listy-style-14-01">
			<?php 
				if($cnt%2==1){
			?>
				<div class="ilist_info4_col-6"><!--col-6-->
				   <div class="ilist_info4_list-inner-part-14">
						<div class="ilist_info4_left-part-14">
							<div class="ilist_info4_top-14">
								<h2>
									<?php 
										if( isset($list['qcld_text_title']) && !empty($list['qcld_text_title'])){
											echo esc_html__($list['qcld_text_title']);
										}
									?>
									<div class="upvote-section" style="font-size: 14px;margin-right: 21px;float: right;">
										<span data-post-id="<?php echo get_the_ID(); ?>" data-item-title="<?php echo isset($list['qcld_text_title']) ? esc_html__(trim($list['qcld_text_title'])):''; ?>" data-item-desc="<?php echo isset($list['qcld_text_desc']) ? esc_html(trim($list['qcld_text_desc'])):''; ?>" data-item-id="<?php echo isset($list['qcld_counter']) ? esc_html__(trim($list['qcld_counter'])):''; ?>" class="upvote-btn-ilist upvote-on" style="overflow: hidden;width: 21px;display: block;float: left;">
											<i class="fa fa-thumbs-up"></i>
										</span>
										<span class="upvote-count-ilist" style="line-height: 26px;">
											<?php 
											  if( isset($list['sl_thumbs_up']) && (int)$list['sl_thumbs_up'] > 0 ){
												echo (int)$list['sl_thumbs_up'];
											  }
											?>
										</span>
									</div>									
								</h2>
								
							</div>
							<div class="ilist_info4_bottom-14">
								<div class="ilist_info4_decription-14">
								<?php 
									if( isset($list['qcld_text_desc']) && !empty($list['qcld_text_desc'])){
										echo ($list['qcld_text_desc']);
									}
								?>
								</div>
							</div>
							<div class="clear"></div>
						</div>
				   </div>
				</div><!--col-6-->
				
				<div class="ilist_info4_col-6"><!--col-4-->
				   <div class="ilist_info4_list-inner-part-14">
						<div class="ilist_info4_middle-part-step">
							<h4><span><?php echo ($cnt>9?$cnt:'0'.$cnt); ?></span></h4>
						</div>
						<div class="ilist_info4_icon-image">
							<?php if( isset($list['qcld_text_image']) && !empty($list['qcld_text_image'])){
								echo '<a class="example-image-link" href="'. esc_url(@$list['qcld_text_image']) .'" data-lightbox="example-'. $cnt.'"><img style="max-width: 100%;margin-left: 13px;" class="ilist_info4_example-image" src="'. esc_url(@$list['qcld_text_image']).'" alt="image-1" /></a>';
							}else{
								echo '<img src="'.OCOPD_TPL_URL1.'/'.$template_code.'/images/2.png" alt=""/>';
							}
							?>
						</div>
				   </div>
				</div><!--col-4-->			
			<?php
				}else{
			?>
				<div class="ilist_info4_col-6"><!--col-6-->
				   <div class="ilist_info4_list-inner-part-14">
						<div class="ilist_info4_middle-part-step ilist_info4_left-side-heading">
							<h4><span><?php echo ($cnt>9?$cnt:'0'.$cnt); ?></span></h4>
						</div>
						<div class="ilist_info4_icon-image ilist_info4_left-side">
							<?php if( isset($list['qcld_text_image']) && !empty($list['qcld_text_image'])){
								echo '<a class="example-image-link" href="'. esc_url(@$list['qcld_text_image']) .'" data-lightbox="example-'. $cnt.'"><img style="max-width: 100%;margin: 0 0 0 -20px;" class="ilist_info4_example-image" src="'. esc_url(@$list['qcld_text_image']).'" alt="image-1" /></a>';
							}else{
								echo '<img src="'.OCOPD_TPL_URL1.'/'.$template_code.'/images/1.png" alt=""/>';
							}
							?>						
						</div>
				   </div>
				</div><!--col-6-->
				
				<div class="ilist_info4_col-6"><!--col-6-->
				   <div class="ilist_info4_list-inner-part-14">
						<div class="ilist_info4_left-part-14 ilist_info4_right-part-14">
							<div class="ilist_info4_top-14">
								<h2>
							
									<?php 
										if( isset($list['qcld_text_title']) && !empty($list['qcld_text_title'])){
											echo esc_html__($list['qcld_text_title']);
										}
									?>	
									<div class="upvote-section" style="font-size: 14px;margin-right: 21px;float: left;">
										<span data-post-id="<?php echo get_the_ID(); ?>" data-item-title="<?php echo isset($list['qcld_text_title']) ? esc_html__(trim($list['qcld_text_title'])):''; ?>" data-item-desc="<?php echo isset($list['qcld_text_desc']) ? esc_html($list['qcld_text_desc']):''; ?>" data-item-id="<?php echo isset($list['qcld_counter']) ? esc_html__(trim(@$list['qcld_counter'])):''; ?>" class="upvote-btn-ilist upvote-on" style="overflow: hidden;width: 21px;display: block;float: left;">
											<i class="fa fa-thumbs-up"></i>
										</span>
										<span class="upvote-count-ilist" style="line-height: 26px;">
											<?php 
											  if( isset($list['sl_thumbs_up']) && (int)$list['sl_thumbs_up'] > 0 ){
												echo (int)$list['sl_thumbs_up'];
											  }
											?>
										</span>
									</div>									
								</h2>
								
							</div>
							<div class="ilist_info4_bottom-14">
								<div class="ilist_info4_decription-14 ilist_info4_right-description">
								<?php 
									if( isset($list['qcld_text_desc']) && !empty($list['qcld_text_desc'])){
										echo ($list['qcld_text_desc']);
									}
								?>
								</div>
							</div>
							<div class="clear"></div>
						</div>
				   </div>
				</div><!--col-6-->			
			<?php
				}
			?>
		</li>
		<div class="clear"></div>
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
<div class="clear"></div>

