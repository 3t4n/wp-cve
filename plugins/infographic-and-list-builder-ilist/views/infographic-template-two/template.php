<?php 
wp_enqueue_style( 'ilist_sl2_stylesheet', OCOPD_TPL_URL1 . "/$template_code/css/style.css");
wp_enqueue_style( 'ilist_sl2_stylesheet_responsive', OCOPD_TPL_URL1 . "/$template_code/css/responsive.css");
wp_enqueue_style( 'ilist_infog15_light_css', QCOPD_ASSETS_URL1 . "/css/lightbox.min.css");
wp_enqueue_script( 'ilist_infog15_light', QCOPD_ASSETS_URL1 . "/js/lightbox-plus-jquery.min.js", array('jquery'));
wp_enqueue_style( 'ilist_sl2_google_font', "https://fonts.googleapis.com/css?family=Montserrat");

?>
<?php 
	$customCss = get_option( 'sl_custom_style' );

	if( trim($customCss) != "" ) :
?>
	<style>
		<?php echo trim($customCss); ?>
	</style>
	
<?php endif; ?>
<?php 

if($column>8){
	$column = 8;
}

?>

<?php 
	$rtlSettings = get_option( 'sl_enable_rtl' );
	$rtlClass = "";

	if( $rtlSettings == 1 )
	{
	   $rtlClass = "ilist_direction";
	}
?>


<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">

<div id="qcld-list-holder" class="qcld-list-hoder sl2bgimage" >
	<div id="qcopd-list-<?php echo $listId; ?>" class="">
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
		<?php 

			echo do_shortcode($ilist_chart[0]);
		?>
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
		<li id="qcld_sl_<?php echo get_the_ID()."_".$cnt ?>" class="ilist_sl2_list-style ilist_sl2_listy-style-03 ilist_sl2_qcld_column<?php echo $column; ?>">
			<div class="ilist_sl2_qcld_style <?php echo $rtlClass; ?>" ><!--col-md-6 col-sm-6-->

			   <div class="ilist_sl2_list-inner-part-two sl2hoveritem sl2hovertextcolor" >
					<div class="ilist_sl2_left-part ilist_sl2_left-three" >
					<?php if( $upvote == 'on' ) : ?>

					<!-- upvote section -->
					<div class="upvote-section" style="cursor:pointer">
						<span data-post-id="<?php echo get_the_ID(); ?>" data-item-title="<?php echo isset($list['qcld_text_title']) ? (trim($list['qcld_text_title'])):''; ?>" data-item-id="<?php echo isset($list['qcld_counter']) ? esc_html__(trim(@$list['qcld_counter'])):''; ?>" class="upvote-btn-ilist upvote-on">
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
						<h2 >
							<div class="infog5_title_image">
								<?php
								if(isset($list['qcld_text_image']) && !empty($list['qcld_text_image'])){
								 echo '<a class="example-image-link" href="'. ($list['qcld_text_image']) .'" data-lightbox="example-'. $cnt.'"><img class="title_image_infog5" src="'. ($list['qcld_text_image']).'" alt="image-1" /></a>';
								}
								?>								
							</div>	
							
						</h2>
					</div>
					<div class="ilist_sl2_right-part">
				
						<?php 
							if( isset($list['qcld_text_title']) && !empty($list['qcld_text_title'])){
						?>
							<h3  class="sl2titlefont sl2tfontsize">
								<?php 
									
									echo ($list['qcld_text_title']);
								?>
							</h3>
						<?php
							}
						?>
						<?php 
							if( isset($list['qcld_text_desc']) && !empty($list['qcld_text_desc'])){
						?>
							<span  class="sl2descfont infog2dfontsize">
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

