<?php 
wp_enqueue_style( 'ilist_info1_stylesheet', OCOPD_TPL_URL1 . "/$template_code/css/style.css");
wp_enqueue_style( 'ilist_info5_google_font2', "https://fonts.googleapis.com/css?family=Montserrat");
wp_enqueue_style( 'ilist_infog5_light_css', QCOPD_ASSETS_URL1 . "/css/lightbox.min.css");
wp_enqueue_script( 'ilist_infog5_light', QCOPD_ASSETS_URL1 . "/js/lightbox-plus-jquery.min.js", array('jquery'));
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
	   $rtlClass = "ilist_info5_direction";
	}
?>
<div id="qcld-list-holder" class="qcld-list-hoder info5bgimage" >
	<div id="qcopd-list-<?php echo $listId; ?>" >
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
	<ul class="ilist_sl5_list-menu">
	<?php foreach( $lists as $list_sl ) : ?>
	<?php 
		$cnt=1;
		if( $item_orderby == 'upvote' )
		{
			usort($list_sl, "ilist_custom_sort_by_tpl_upvotes");
		}
	?>
	<?php foreach($list_sl as $list) : ?>
		<li id="qcld_sl_<?php echo get_the_ID()."_".$cnt ?>" class="ilist_sl5_qcld_column<?php echo $column; ?>">
			<div class="ilist_sl5_qcld_style <?php echo $rtlClass; ?>">
							
						<div class="ilist_sl5_list-number ilist_sl5_list_number2 info5borderbg">
							<?php
								if(isset($list['qcld_text_image']) && !empty($list['qcld_text_image'])){
								 echo '<a style="display: flex;justify-content: center;" class="example-image-link" href="'. ($list['qcld_text_image']) .'" data-lightbox="example-'. $cnt.'"><img style="align-self:center" src="'. ($list['qcld_text_image']).'" alt="image-1" /></a>';
								}elseif(isset($list['qcld_text_image_fa']) && !empty($list['qcld_text_image_fa'])){
									echo '<div class="infog_free_5"><i class="fa '.$list['qcld_text_image_fa'].'"></i></div>';
								}
							?>
						</div>
						
						<div class="ilist_sl5_list-content info5hoveritem info5hovertitlecolor" >
							<div class="info5_title_image">
								<span <?php echo isset($itemnumbercolor) ? ($itemnumbercolor):''; ?>> <?php echo ($cnt); ?> </span>								
							</div>
							<h2 class="ilist_sl5_list-title info1tfontsize info5titlefont" >
							<?php 
								if(isset($list['qcld_text_title']) && !empty($list['qcld_text_title'])){
									echo ($list['qcld_text_title']);
								}
							?>
				<?php if( $upvote == 'on' ) : ?>

				<!-- upvote section -->
				<div class="upvote-section" style="cursor:pointer">
					<span data-post-id="<?php echo get_the_ID(); ?>" data-item-title="<?php echo isset( $list['qcld_text_title'] ) ? (trim($list['qcld_text_title'])):''; ?>" data-item-id="<?php echo isset( $list['qcld_counter'] ) ? esc_html__(trim(@$list['qcld_counter'])):''; ?>" class="upvote-btn-ilist upvote-on">
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
							</h2>

							<h3 class="ilist_sl5_list-sub-title infol5subtitlecolor info5hoverdesccolor info5descfont infog5dfontsize">
							<?php 
								if( isset($list['qcld_text_desc']) && !empty($list['qcld_text_desc'])){
									echo ($list['qcld_text_desc']);
								}
							?>							
							</h3>
						</div>
			</div>
		</li>
		
	<?php $cnt++; ?>
	<?php endforeach; ?>
	<?php endforeach; ?>
	</ul>
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

