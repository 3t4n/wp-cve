<?php 
wp_enqueue_style( 'ilist_sl3_stylesheet', OCOPD_TPL_URL1 . "/$template_code/css/style.css");
wp_enqueue_style( 'ilist_sl3_stylesheet_responsive', OCOPD_TPL_URL1 . "/$template_code/css/responsive.css");
wp_enqueue_style( 'ilist_infog215_light_css', QCOPD_ASSETS_URL1 . "/css/lightbox.min.css");
wp_enqueue_script( 'ilist_infog125_light', QCOPD_ASSETS_URL1 . "/js/lightbox-plus-jquery.min.js", array('jquery'));
wp_enqueue_style( 'ilist_sl3_google_font', "https://fonts.googleapis.com/css?family=Montserrat");
?>
<?php
if($column>8){
	$column = 8;
}
 
	$customCss = get_option( 'sl_custom_style' );

	if( trim($customCss) != "" ) :
?>
	<style>
		<?php echo trim($customCss); ?>
	</style>
	
<?php endif; ?>
<?php 
	$rtlSettings = get_option( 'sl_enable_rtl' );
	$rtlClass = "";

	if( $rtlSettings == 1 )
	{
	   $rtlClass = "ilist_sl3_direction";
	}
?>
<?php
if($column>8){
	$column = 8;
}

?>


<div id="qcld-list-holder" class="qcld-list-hoder sl3bgimage" >
<div id="qcopd-list-<?php echo $listId; ?>" class=>
		<div class="qcopd-single-list">
			<?php 
				do_action('qcsl_after_add_btn', $shortcodeAtts);
			?>
	<h3 style="height: 40px;">
		<?php 
			
				echo get_the_title();
			
		?>
	</h3>
	<div style="clear:both;margin-bottom:20px"></div>
		
	<?php
	if(isset($ilist_chart[0])&&!empty($ilist_chart[0])&&$show_chart_position[0]=='top'){
	?>
	<div class="ilist_chart_map_wrap">
		<?php echo do_shortcode($ilist_chart[0]);?>
	</div>
	<?php
	}
	?>
	<ul style="padding-left: 30px !important;">
	<?php foreach( $lists as $list_sl ) : ?>
	<?php 
		$cnt=1;
		if( $item_orderby == 'upvote' )
		{
			usort($list_sl, "ilist_custom_sort_by_tpl_upvotes");
		}
	?>
	<?php foreach($list_sl as $list) : ?>
		<li id="qcld_sl_<?php echo get_the_ID()."_".$cnt ?>" class="ilist_sl3_list-style-three ilist_sl3_listy-style-three-01 ilist_column<?php echo $column; ?>">
			<div class="ilist_sl3_qcld_style <?php echo $rtlClass; ?>"><!--col-md-6 col-sm-6-->

			   <div class="ilist_sl3_list-inner-part-three sl3borderbg sl3hoveritem">
					<div class="ilist_sl3_left-part-three sl3bordercolor"><h2 <?php echo isset($itemnumbercolor) ? $itemnumbercolor:''; ?>><?php echo isset($cnt) ? $cnt :''; ?></h2></div>
					<div class="ilist_sl3_rt-part-three sl3bordercolor">
						<?php
						if(isset($list['qcld_text_image']) && !empty($list['qcld_text_image'])){
						 echo '<a class="example-image-link ilist-image-position" href="'. ($list['qcld_text_image']) .'" data-lightbox="example-'. $cnt.'"><img  src="'. ($list['qcld_text_image']).'" alt="image-1" /></a>';
						 }elseif(isset($list['qcld_text_image_fa']) && !empty($list['qcld_text_image_fa'])){
									echo '<div style="font-size: 35px;margin-top: 7px;"><i class="fa '.$list['qcld_text_image_fa'].'"></i></div>';
								}
						?>							
					</div>
					
					<div class="ilist_sl3_right-part-three sl3hovertextcolor">
						<?php 
							if(isset($list['qcld_text_title']) && !empty($list['qcld_text_title'])){
						?>
							<h3 class="sl3titlefont sl3tfontsize">
								<?php 
									echo ($list['qcld_text_title']);
								?>
							</h3>
						<?php
							}
						?>
				<?php if( $upvote == 'on' ) : ?>

				<!-- upvote section -->
				<div class="upvote-section" style="cursor:pointer">
					<span data-post-id="<?php echo get_the_ID(); ?>" data-item-title="<?php echo isset($list['qcld_text_title']) ? trim($list['qcld_text_title']):''; ?>" data-item-id="<?php echo isset($list['qcld_counter']) ? esc_html__(trim(@$list['qcld_counter'])):''; ?>" class="upvote-btn-ilist upvote-on">
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
				<!-- /upvote section -->

				<?php endif; ?>						
						<?php 
							if(isset($list['qcld_text_desc']) && !empty($list['qcld_text_desc'])){
						?>
							<span class="sl3descfont infog3dfontsize">
								<?php 
									
									echo ($list['qcld_text_desc']);
								?>
							</span>						
						<?php
							}
						?>
					   
					</div>
			   </div>
			</div><!--/col-md-6 col-sm-6-->
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
<div style="clear:both"></div>

