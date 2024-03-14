<?php
/**
 * CTA section for the homepage.
 */
if ( ! function_exists( 'spiceb_cloudpress_cta' ) ) :
function spiceb_cloudpress_cta() {

$cta_section_enable = get_theme_mod('cta_section_enable','on');
if($cta_section_enable !='off')
{
 $cta_open_new_tab = get_theme_mod('home_call_out_btn_link_target',false);		
?>
<section class="section-module call-to-action-one" id="features">
	<div class="container">
		<?php
		$home_cta_section_title = get_theme_mod('home_cta_section_title',__('Lorem ipsum dolor sit amet?','spicebox'));
		$home_cta_section_discription = get_theme_mod('home_cta_section_discription', __('Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.','spicebox'));
		$home_cta_btn= get_theme_mod('home_cta_btn',__('Nemo enim','spicebox'));?>
				<div class="row">
					<div class="col-md-9 col-sm-9 col-xs-12">
						<div class="text-left">
							<?php if(!empty($home_cta_section_title)):?><h3 class="title"><?php echo $home_cta_section_title; ?></h3><?php endif;?>
							<?php if(!empty($home_cta_section_discription)):?><p><?php echo $home_cta_section_discription; ?></p><?php endif;?>
						</div>
					</div>
					<?php if(!empty($home_cta_btn)):?>
					<div class="col-md-3 col-sm-3 col-xs-12">
						<div class="ptop-15 pbottom-5 text-right"><a href="<?php echo get_theme_mod('home_cta_btn_link','#');?>"  class="btn-small btn-animate border btn-shadow-lg"  <?php if($cta_open_new_tab==true) { ?> target="_blank"<?php } ?>><?php echo $home_cta_btn; ?></a></div>						
					</div>
				<?php endif;?>
				</div>
			

	</div>	
</section>
<?php } 
}
endif;
	if ( function_exists( 'spiceb_cloudpress_cta' ) ) {
	$section_priority = apply_filters( 'cloudpress_section_priority', 2, 'spiceb_cloudpress_cta' );
	add_action( 'spiceb_cloudpress_sections', 'spiceb_cloudpress_cta', absint( $section_priority ) );
}