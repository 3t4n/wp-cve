<?php 
wp_enqueue_style( 'ilist_img1_stylesheet', OCOPD_TPL_URL1 . "/$template_code/css/style.css");
wp_enqueue_style( 'ilist_img1_stylesheet_responsive', OCOPD_TPL_URL1 . "/$template_code/css/responsive.css");
wp_enqueue_style( 'ilist_img5_light_css', QCOPD_ASSETS_URL1 . "/css/lightbox.min.css");
wp_enqueue_script( 'ilist_img5_light', QCOPD_ASSETS_URL1 . "/js/lightbox-plus-jquery.min.js", array('jquery'));
wp_enqueue_style( 'ilist_img1_google_font2', "https://fonts.googleapis.com/css?family=Montserrat");
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
		<li id="qcld_sl_<?php echo get_the_ID()."_".$cnt ?>" class="ilist_img1_list-style-five ilist_img1_listy-style-five-01 ilist_img1_qcld_column<?php echo esc_html__($column); ?>" >
			<div class="ilist_img1_qcld_style"><!--col-md-6 col-sm-6-->
				<?php if( $upvote == 'on' ) : ?>

				<!-- upvote section -->
				<div class="upvote-section">
					<span data-post-id="<?php echo get_the_ID(); ?>" data-item-title="<?php echo isset($list['qcld_text_title']) ? esc_html__(trim($list['qcld_text_title'])):''; ?>" data-item-id="<?php echo isset( $list['qcld_counter'] ) ? esc_html__(trim($list['qcld_counter'])):''; ?>" class="upvote-btn-ilist upvote-on">
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
			   <a class="example-image-link" href="<?php echo isset($list['qcld_text_image']) ? esc_url($list['qcld_text_image']):''; ?>" data-lightbox="example-<?php echo $cnt;?>"><div class="ilist_img1_list-inner-part-five">
			  
					<div class="ilist_img1_effect-style-one"></div>
					<div class="ilist_img1_effect-style-two"></div>
					<div class="ilist_img1_effect-style-three"></div>
					<div class="ilist_img1_effect-style-four"><p class="ilist_img1_qcldtitle"><?php echo isset($list['qcld_text_title']) ? $list['qcld_text_title']:''; ?></p></div>
					<div class="ilist_img1_effect-style-five"></div>
					<?php if( isset( $list['qcld_text_image'] ) && !empty( $list['qcld_text_image'] ) ){ ?> 
					<img class="ilist_img1_example-image" src="<?php echo esc_url(@$list['qcld_text_image']); ?>" alt="image-1" />
					<?php } ?>
				</div></a>
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

