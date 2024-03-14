<?php 
wp_enqueue_style( 'ilist_preinfo2_stylesheet', OCOPD_TPL_URL1 . "/$template_code/css/style-14.css");
wp_enqueue_style( 'ilist_img5_light_css', QCOPD_ASSETS_URL1 . "/css/lightbox.min.css");
wp_enqueue_script( 'ilist_img5_light', QCOPD_ASSETS_URL1 . "/js/lightbox-plus-jquery.min.js", array('jquery'));
wp_enqueue_style( 'ilist_preinfo2_stylesheet_google_font2', "https://fonts.googleapis.com/css?family=Open+Sans");
?>

<?php
$itembg = ''; 
if(isset($ilist_item_bgcolor[0]) && $ilist_item_bgcolor[0]!=''){
	echo '<style type="text/css">

	.ori7itembgcolor {
		background:'.$ilist_item_bgcolor[0].';
	}
	</style>';	
}
$hoderbg = ''; 
if(isset($ilist_holder_color[0]) && $ilist_holder_color[0]!=''){
	$hoderbg = 'style="background:'.$ilist_holder_color[0].';overflow: hidden;"';
}
/*
if(isset($ilist_holder_bg_image[0]) && $ilist_holder_bg_image[0]!=''){
	echo '<style type="text/css">

	.pinfog2bgimage {
		background-image:url('.$ilist_holder_bg_image[0].') !important;
		background-repeat: no-repeat !important;
		background-size: 100% 100% !important;
	}
	</style>';
}
*/
$titlecolor = 'style="float:left;margin-right:10px;"'; 
if(isset($ilist_title_color[0]) && $ilist_title_color[0]!=''){
	$titlecolor = 'style="color:'.$ilist_title_color[0].';float:left;margin-right:10px;"';
}
$subtitlecolor = ''; 
if(isset($ilist_subtitle_color[0]) && $ilist_subtitle_color[0]!=''){
	$subtitlecolor = 'style="color:'.$ilist_subtitle_color[0].'"';
}
$bordercolor = ''; 
if(isset($ilist_border_color[0]) && $ilist_border_color[0]!=''){
	echo '<style type="text/css">

		.sl4borderbg{
			background:'.$ilist_border_color[0].';
		}
		</style>';
}
//title font size
if(isset($ilist_title_font_size[0]) && $ilist_title_font_size[0]!=''){
	echo '<style type="text/css">

		.pinfog2tfontsize{
			font-size:'.$ilist_title_font_size[0].'px!important;
		}
		</style>';
}
//description font size//
if(isset($ilist_desc_font_size[0]) && $ilist_desc_font_size[0]!=''){
	echo '<style type="text/css">

		.pinfog2dfontsize p{
			font-size:'.$ilist_desc_font_size[0].'px!important;
		}
		</style>';
}

$itemnumbercolor = ''; 
if(isset($ilist_itemnumber_color[0]) && $ilist_itemnumber_color[0]!=''){
	$itemnumbercolor = 'style="color:'.$ilist_itemnumber_color[0].'"';
}

if(isset($ilist_hoverbg_color[0]) && $ilist_hoverbg_color[0]!=''){
echo '<style type="text/css">
.pinfo2hoveritem:hover{
	background:'.$ilist_hoverbg_color[0].'!important;
}
</style>';
}
if(isset($ilist_hover_title_color[0])){
echo '<style type="text/css">
.pinfo2hovertitlecolor:hover >h1{
	color:'.$ilist_hover_title_color[0].' !important;
}
</style>';
}
if(isset($ilist_hover_desc_color[0])){
echo '<style type="text/css">
.pinfo2hoverdesccolor:hover>span>p{
	color:'.$ilist_hover_desc_color[0].' !important;
}
</style>';
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
<div id="qcld-list-holder" class="qcld-list-hoder pinfog2bgimage" <?php echo $hoderbg; ?>>
<div id="qcopd-list-<?php echo $listId; ?>" class=>
		<div class="qcopd-single-list">
			

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

	<ul class="listing-fourteen">
	<?php foreach( $lists as $list_sl ) : ?>
	<?php 
		$cnt=1;
		if( $item_orderby == 'upvote' )
		{
			usort($list_sl, "ilist_custom_sort_by_tpl_upvotes");
		}
	?>
	<?php foreach($list_sl as $list) : ?>
		<li id="qcld_sl_<?php echo get_the_ID()."_".$cnt ?>" class="list-style-fourteen list-style-fourteen-01 ilist_pinfog2_<?php echo $column; ?>">
                        <div class="list-fourteen-inner-part">
                            <div class="list-fourteen-sl">
                                <h2 <?php echo isset($itemnumbercolor) ? $itemnumbercolor :''; ?>><?php echo ($cnt>9?$cnt:'0'.$cnt); ?></h2>
                            </div>
                            <div class="list-fourteen-content pinfo2hoveritem pinfo2hovertitlecolor pinfo2hoverdesccolor ori7itembgcolor" >
                                
							<?php 
								if(isset($list['qcld_text_title']) && !empty($list['qcld_text_title'])){
							?>
								<h2 <?php echo $titlecolor; ?> class="pinfo2titlefont pinfog2tfontsize" >
									<?php 
											echo ($list['qcld_text_title']);
									?>
								</h2>
							<?php
								}
							?>								
				<?php if( $upvote == 'on' ) : ?>
				<div class="upvote-section" style="cursor:pointer">
					<span data-post-id="<?php echo get_the_ID(); ?>" data-item-title="<?php echo isset($list['qcld_text_title']) ? trim($list['qcld_text_title']):''; ?>" data-item-id="<?php echo  isset($list['qcld_text_title']) ? esc_html__(trim(@$list['qcld_counter'])):''; ?>" class="upvote-btn-ilist upvote-on" style="">
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
				<div style="clear:both"></div>								
                   	<span <?php echo isset($subtitlecolor) ? $subtitlecolor :''; ?> class="pinfo2descfont pinfog2dfontsize">							
					<?php 
						if(isset($list['qcld_text_desc']) && !empty($list['qcld_text_desc'])){
							
							echo apply_filters('the_content',$list['qcld_text_desc']);
						}
					?>
				</span>
                </div>
                <!--list-fourteen-content-->
				<?php if(isset($list['qcld_text_image']) && !empty($list['qcld_text_image'])){ ?>
                
				<?php 
				 echo '<div class="list-fourteen-avatar"><a style="align-self: center;" class="example-image-link" href="'. ($list['qcld_text_image']) .'" data-lightbox="example-'. $cnt.'"><img src="'. ($list['qcld_text_image']).'" alt="image-1" /></a></div>';
				?>
                
				<?php }elseif(isset($list['qcld_text_image_fa']) and !empty($list['qcld_text_image_fa'])){
				echo '<div class="list-fourteen-avatar"><div style="font-size: 46px;color: #545050;align-self: center;"><i class="fa '.$list['qcld_text_image_fa'].'"></i></div></div>';
			}else{
				echo '<div class="list-fourteen-avatar"><div style="font-size: 46px;color: #545050;align-self: center;"><i class="fa fa-check"></i></div></div>';
			} 
			?>

            </div>
		</li>

		<?php $cnt++; ?>
		<?php endforeach; ?>
		<?php endforeach; ?>
	</ul>

	</div>
	</div>
</div>
<div style="clear:both"></div>

