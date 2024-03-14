<?php 
wp_enqueue_style( 'ilist_preinfolist9_stylesheet', OCOPD_TPL_URL1 . "/$template_code/css/style-26.css");
wp_enqueue_style( 'ilist_img5_light_css', QCOPD_ASSETS_URL1 . "/css/lightbox.min.css");
wp_enqueue_script( 'ilist_img5_light', QCOPD_ASSETS_URL1 . "/js/lightbox-plus-jquery.min.js", array('jquery'));
wp_enqueue_style( 'ilist_preinfolist7_stylesheet_google_font2', "https://fonts.googleapis.com/css?family=Lobster|Quicksand");

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
	$rtlSettings = get_option( 'sl_enable_rtl' );
	$rtlClass = "";

	if( $rtlSettings == 1 )
	{
	   $rtlClass = "ilist_sl4_direction";
	}
?>
<div id="qcld-list-holder" class="qcld-list-hoder pinfo9bgimage" >
<div id="qcopd-list-<?php echo $listId; ?>">
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
	<ul class="listing-twentySix">
	<?php foreach( $lists as $list_sl ) : ?>
	<?php 
		$cnt=1;
		if( $item_orderby == 'upvote' )
		{
			usort($list_sl, "ilist_custom_sort_by_tpl_upvotes");
		}
	?>
	<?php foreach($list_sl as $list) : ?>
		<li id="qcld_sl_<?php echo get_the_ID()."_".$cnt ?>" class="list-style-twentySix list-style-twentySix-01 np_column<?php echo $column; ?>">
	
			<div class="list-twentySix-inner-part">

				<div class="list-twentySix-content pinfol9hoverdesccolor pinfol9hovertitlecolor">
					<div class="list-twentySix-avatar">
							<?php 
								if(isset($list['qcld_text_image']) && !empty($list['qcld_text_image'])){
									echo '<a class="example-image-link ilist_image_position" href="'. ($list['qcld_text_image']) .'" data-lightbox="example-'. ($cnt).'"><img class="ilist_info1_example-image" src="'. ($list['qcld_text_image']).'" alt="image-1" /></a>';
								}elseif(isset($list['qcld_text_image_fa']) && !empty($list['qcld_text_image_fa'])){
									echo '<div style="font-size: 47px;color: #6e6969;margin-top: 2px;"><i class="fa '.$list['qcld_text_image_fa'].'"></i></div>';
								}
							?>
					</div>
					<div class="list-twentySix-upvote">
						<?php if( $upvote == 'on' ) : ?>
							<div class="upvote-section" style="cursor:pointer">
								<span data-post-id="<?php echo get_the_ID(); ?>" data-item-title="<?php echo isset($list['qcld_text_title']) ? (trim($list['qcld_text_title'])):''; ?>" data-item-id="<?php echo isset( $list['qcld_counter'] ) ? esc_html__(trim($list['qcld_counter'])):''; ?>" class="upvote-btn-ilist upvote-on" style="color: #7b4141;">
									<i class="fa fa-thumbs-up"></i>
								</span>
								<span class="upvote-count-ilist" style="color: #7b4141;">
									<?php 
									  if( isset($list['sl_thumbs_up']) && (int)$list['sl_thumbs_up'] > 0 ){
										echo (int)$list['sl_thumbs_up'];
									  }
									?>
								</span>
							</div>
						<!-- /upvote section -->

						<?php endif; ?>
					</div>
					<?php 
						if(isset($list['qcld_text_title']) && !empty($list['qcld_text_title'])){
					?>
						<h2 class="pinfol9titlefont pinfol9tfontsize">
							<?php 
									echo ($list['qcld_text_title']);
							?>
						</h2>
					<?php
						}
					?>

					<div style="clear:both"></div>				
					<span class="pinfol9descfont pinfol9subtitlecolor pinfo9dfontsize" style="color: #000;">							
						<?php 
							if(isset($list['qcld_text_desc']) && !empty($list['qcld_text_desc'])){
								echo ($list['qcld_text_desc']);
							}
						?>
					</span>
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

