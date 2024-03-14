<?php 
wp_enqueue_style( 'ilist_sl2_stylesheet', OCOPD_TPL_URL1 . "/$template_code/css/style.css");
wp_enqueue_style( 'ilist_sl2_stylesheet_responsive', OCOPD_TPL_URL1 . "/$template_code/css/responsive.css");
wp_enqueue_style( 'ilist_sl2_google_font', "https://fonts.googleapis.com/css?family=Montserrat");
?>

<?php 
	$customCss = get_option( 'sl_custom_style' );

	if( trim($customCss) != "" ) :
?>
	<style>
		<?php echo esc_html__(trim($customCss)); ?>
	</style>
	
<?php endif; ?>

<?php 
	$rtlSettings = get_option( 'sl_enable_rtl' );
	$rtlClass = "";

	if( $rtlSettings == 'on' )
	{
	   $rtlClass = "ilist_direction";
	}
?>


<div id="qcld-list-holder" class="qcld-list-hoder">
	<div id="qcopd-list-<?php echo (int)$listId; ?>" class="">
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
	<ul style="overflow:hiddenwidth: 100%;margin-top: 31px;">
	<?php foreach( $lists as $list_sl ) : ?>
	<?php 
		$cnt=1;
		if( $item_orderby == 'upvote' )
		{
			usort($list_sl, "ilist_custom_sort_by_tpl_upvotes");
		}
	?>
	<?php foreach($list_sl as $list) : ?>
		<li id="qcld_sl_<?php echo get_the_ID()."_".(int)$cnt ?>" class="ilist_sl2_list-style ilist_sl2_listy-style-03 ilist_sl2_qcld_column<?php echo esc_html__($column); ?>">
			<div class="ilist_sl2_qcld_style <?php echo $rtlClass; ?>"><!--col-md-6 col-sm-6-->

			   <div class="ilist_sl2_list-inner-part-two">
					<div class="ilist_sl2_left-part ilist_sl2_left-three">
					<?php if( $upvote == 'on' ) : ?>

					<!-- upvote section -->
					<div class="upvote-section">
						<span data-post-id="<?php echo get_the_ID(); ?>" data-item-title="<?php echo isset($list['qcld_text_title']) ? esc_html__(trim($list['qcld_text_title'])):''; ?>" data-item-id="<?php echo isset($list['qcld_counter']) ? esc_html__(trim(@$list['qcld_counter'])):''; ?>" class="upvote-btn-ilist upvote-on">
							<i class="fa fa-thumbs-up"></i>
						</span>
						<span class="upvote-count-ilist">
							<?php 
							  if( isset($list['sl_thumbs_up']) && (int)$list['sl_thumbs_up'] > 0 ){
								echo esc_html__((int)$list['sl_thumbs_up']);
							  }
							?>
						</span>
					</div>
					<!-- /upvote section -->

					<?php endif; ?>					
						<h2><span style="font-size:40px">0</span><?php echo $cnt ?></h2>
					</div>
					<div class="ilist_sl2_right-part">
						<?php 
							if( isset($list['qcld_text_title']) && !empty($list['qcld_text_title'])){
						?>
							<h3>
								<?php 
									
									echo esc_html__($list['qcld_text_title']);
								?>
							</h3>
						<?php
							}
						?>
						<?php 
							if( isset($list['qcld_text_desc']) && !empty($list['qcld_text_desc'])){
						?>
							<p>
								<?php 
									
									echo ($list['qcld_text_desc']);
								?>
							</p>						
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

