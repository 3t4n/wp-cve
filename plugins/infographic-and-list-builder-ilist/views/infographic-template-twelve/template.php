<?php 
wp_enqueue_style( 'ilist_preinfolist4_stylesheet', OCOPD_TPL_URL1 . "/$template_code/css/style-21.css");
wp_enqueue_style( 'ilist_img5_light_css', QCOPD_ASSETS_URL1 . "/css/lightbox.min.css");
wp_enqueue_script( 'ilist_img5_light', QCOPD_ASSETS_URL1 . "/js/lightbox-plus-jquery.min.js", array('jquery'));
wp_enqueue_style( 'ilist_preinfolist2_stylesheet_google_font2', "https://fonts.googleapis.com/css?family=Open+Sans");

?>

<?php
$itembg = ''; 
if(isset($ilist_item_bgcolor[0]) && $ilist_item_bgcolor[0]!=''){
	$itembg = 'style="background:'.$ilist_item_bgcolor[0].'"';
}
$hoderbg = ''; 
if(isset($ilist_holder_color[0]) && $ilist_holder_color[0]!=''){
	$hoderbg = 'style="background:'.$ilist_holder_color[0].';overflow: hidden;"';
}
/*
if(isset($ilist_holder_bg_image[0]) && $ilist_holder_bg_image[0]!=''){
	echo '<style type="text/css">

	.pinfo5bgimage {
		background-image:url('.$ilist_holder_bg_image[0].') !important;
		background-repeat: no-repeat !important;
		background-size: 100% 100% !important;
	}
	</style>';
}
*/
$titlecolor = 'style=""'; 
if(isset($ilist_title_color[0]) && $ilist_title_color[0]!=''){
	$titlecolor = 'style="color:'.$ilist_title_color[0].';"';
}


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
<div id="qcld-list-holder" class="qcld-list-hoder pinfo5bgimage">
<div id="qcopd-list-<?php echo $listId; ?>" class=>
		<div class="qcopd-single-list">
			<?php 
				if($embed_option==1){do_action('qcsl_after_add_btn', $shortcodeAtts);}
			?>

		<h3 style="height: 40px;">
		<?php echo get_the_title(); ?>
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
	<ul class="listing-twentyOne">
	<?php foreach( $lists as $list_sl ) : ?>
	<?php 
		$cnt=1;
		if( $item_orderby == 'upvote' )
		{
			usort($list_sl, "ilist_custom_sort_by_tpl_upvotes");
		}
	?>
	<?php foreach($list_sl as $list) : ?>
		<li id="qcld_sl_<?php echo get_the_ID()."_".$cnt ?>" class="list-style-twentyOne list-style-twentyOne-01 ilist_pinfol4_<?php echo $column ?>">
				<div class="list-twentyOne-inner-part">

					<div class="list-twentyOne-content pinfol4hoverdesccolor pinfol4hovertitlecolor">
						<div class="list-twentyOne-upvote">
							<?php if( $upvote == 'on' ) : ?>
							<div class="upvote-section" style="cursor:pointer;align-self: center;">
								<span data-post-id="<?php echo get_the_ID(); ?>" data-item-title="<?php echo isset($list['qcld_text_title']) ? trim($list['qcld_text_title']):''; ?>" data-item-id="<?php echo isset($list['qcld_counter']) ? esc_html__(trim(@$list['qcld_counter'])):''; ?>" class="upvote-btn-ilist upvote-on" style="color:#fff">
									<i class="fa fa-thumbs-up"></i>
								</span>
								<span class="upvote-count-ilist" style="color:#fff">
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
						
						<div class="list-twentyOne-sl pinfo4borderbg">
							<h2 class="sl-title"><?php echo ($cnt>9?$cnt:'0'.$cnt); ?></h2>
						</div>
						<?php if(isset($list['qcld_text_image']) && !empty($list['qcld_text_image'])){ ?>
							
							<?php 
								
							echo '<div class="list-twentyOne-avatar"><a class="example-image-link ilist_image_position" href="'. ($list['qcld_text_image']) .'" data-lightbox="example-'. ($cnt).'"><img class="ilist_info1_example-image" src="'. ($list['qcld_text_image']).'" alt="image-1" /></a></div>';
								
							?>
							
						<?php } elseif(isset($list['qcld_text_image_fa']) && !empty($list['qcld_text_image_fa'])){
							echo '<div class="list-twentyOne-avatar"><div style="font-size: 47px;color: #6e6969;margin-top: 7px;"><i class="fa '.$list['qcld_text_image_fa'].'"></i></div></div>';
						}else{
							echo '<div class="list-twentyOne-avatar"><div style="font-size: 47px;color: #6e6969;margin-top: 7px;"><i class="fa fa-check"></i></div></div>';
						}
						
						?>
			<?php 
					if(isset($list['qcld_text_title']) && !empty($list['qcld_text_title'])){
				?>
					<h2 class="pinfol4titlefont pinfo4tfontsize" >
						<?php 
								echo $list['qcld_text_title'];
						?>
					</h2>
				<?php
					}
				?>


				<span style="display: block;height: 180px;overflow: hidden;" class="pinfol4descfont pinfol4subtitlecolor pinfo4dfontsize">							
					<?php 
						if( isset($list['qcld_text_desc']) && !empty($list['qcld_text_desc'])){
							echo $list['qcld_text_desc'];
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

