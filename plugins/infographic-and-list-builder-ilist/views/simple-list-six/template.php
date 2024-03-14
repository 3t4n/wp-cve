<!--Adding Template Specific Style -->
<?php 
wp_enqueue_style( 'ilist_sl4_stylesheet_responsive', OCOPD_TPL_URL1 . "/$template_code/template.css");
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
			<ul class="ca-menu">
					<?php foreach( $lists as $list_sl ) : ?>
					<?php 
						$cnt=1;
						if( $item_orderby == 'upvote' )
		{
			usort($list_sl, "ilist_custom_sort_by_tpl_upvotes");
		}
					?>
					<?php foreach($list_sl as $list) : ?>
					
						<li id="qcld_sl_<?php echo get_the_ID()."_".$cnt ?>" class="ilist_sl6_qcld_column<?php echo esc_html__($column); ?>">

								<div class="ca-content">
									<?php if( $upvote == 'on' ) : ?>

									<!-- upvote section -->
									<div class="upvote-section">
										<span data-post-id="<?php echo get_the_ID(); ?>" data-item-title="<?php echo isset($list['qcld_text_title']) ? esc_html__(trim($list['qcld_text_title'])):''; ?>" data-item-id="<?php echo isset($list['qcld_counter']) ? esc_html__(trim($list['qcld_counter'])):''; ?>" class="upvote-btn-ilist upvote-on">
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

								
									<?php if( isset($list['qcld_text_title']) && !empty( $list['qcld_text_title'] ) ) : ?>
									<h2 class="ca-main"><?php echo esc_html__($list['qcld_text_title']); ?></h2>
									<?php endif; ?>
									<h3 class="ca-sub"><?php echo isset( $list['qcld_text_desc'] ) ? ($list['qcld_text_desc']):''; ?></h3>
								

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
	
