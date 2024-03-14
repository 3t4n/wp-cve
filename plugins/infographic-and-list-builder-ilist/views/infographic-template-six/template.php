<?php 
wp_enqueue_style( 'ilist_info6_stylesheet', OCOPD_TPL_URL1 . "/$template_code/css/style.css");
wp_enqueue_style( 'ilist_img5_light_css', QCOPD_ASSETS_URL1 . "/css/lightbox.min.css");
wp_enqueue_script( 'ilist_img5_light', QCOPD_ASSETS_URL1 . "/js/lightbox-plus-jquery.min.js", array('jquery'));
wp_enqueue_style( 'ilist_info6_google_font', "https://fonts.googleapis.com/css?family=Oswald");
?>
<?php


	$customCss = get_option( 'sl_custom_style' );

	if( trim($customCss) != "" ) :
?>
	<style>
		<?php echo trim($customCss); ?>
	</style>
	
<?php endif; ?>
<div id="qcld-list-holder" class="qcld-list-hoder info6bgimage" >
	<div id="qcopd-list-<?php echo @$listId; ?>">
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
	<ul class="ilist_info6_listing-twelve" style="list-style:none">
	<?php foreach( $lists as $list_sl ) : ?>
	<?php 
		$cnt=1;
		if( $item_orderby == 'upvote' )
		{
			usort($list_sl, "ilist_custom_sort_by_tpl_upvotes");
		}
	?>
	<?php foreach($list_sl as $list) : ?>
		<li id="qcld_sl_<?php echo get_the_ID()."_".$cnt ?>" class="ilist_info6_list-style-twelve ilist_info6_listy-style-twelve-01">
			<div class="ilist_info6_qcld_style ilist_info6_qcld_column<?php echo $column; ?>">
	
                        <div class="ilist_info6_list-twelve-inner-part info6hovertitlecolor">

                            <h2 class="ilist_info6_list-twelve-title info6tfontsize info6titlefont">
							<?php 
								if( isset( $list['qcld_text_title'] ) && !empty($list['qcld_text_title'])){
									echo ($list['qcld_text_title']);
								}
							?>	
				<?php if( $upvote == 'on' ) : ?>

				<!-- upvote section -->
				<div class="upvote-section" style="display: inline-block;font-size: 16px;cursor:pointer">
					<span data-post-id="<?php echo get_the_ID(); ?>" data-item-title="<?php echo isset( $list['qcld_text_title'] ) ? (trim($list['qcld_text_title'])):''; ?>" data-item-id="<?php echo isset($list['qcld_counter']) ? esc_html__(trim($list['qcld_counter'])):''; ?>" class="upvote-btn-ilist upvote-on" >
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
							<?php 
								if(isset($list['qcld_text_image']) && !empty($list['qcld_text_image'])){
									echo '<div class="ilist_info6_list-twelve-avatar"><div class="ilist_info6_list-twelve-avatar-inner"><a class="example-image-link ilist-image-position" href="'. ($list['qcld_text_image']) .'" data-lightbox="example-'. $cnt.'"><img class="ilist_info6_example-image" src="'. $list['qcld_text_image'].'" alt="image-1" /></a></div></div>';
								}elseif(isset($list['qcld_text_image_fa']) && !empty($list['qcld_text_image_fa'])){
									echo '<div class="ilist_info6_list-twelve-avatar"><div class="ilist_info6_list-twelve-avatar-inner" style="font-size: 100px;"><i class="fa '.$list['qcld_text_image_fa'].'"></i></div></div>';
								}
							?>

                            <div class="ilist_info6_list-twelve-content">
                                <span class="info6subtitlecolor info6hoverdesccolor info6descfont infog6dfontsize">
							<?php 
								if( isset($list['qcld_text_desc']) && !empty($list['qcld_text_desc'])){
									echo ($list['qcld_text_desc']);
								}
							?>								
								</span>
                            </div>
                            <div class="ilist_info6_list-twelve-sl">
                                <h2 <?php echo isset( $itemnumbercolor ) ? $itemnumbercolor : ''; ?>><?php echo isset( $cnt ) ? $cnt : ''; ?></h2>
                            </div>

                        </div>						
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

