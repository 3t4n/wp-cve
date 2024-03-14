<?php
	/**
	 * Footer file in case of the elementor way
	 *
	 * @package header-footer-elementor
	 * @since 1.2.0
	 */
	$footer_class = array();
	$footer_attribute = array();

	$footer_class[] = 'stfe-footer';

	$structure = soft_template_core()->locations->get_structure_for_location('footer');
	$template_id = soft_template_core()->conditions->find_matched_conditions( $structure->get_id() );
	$template_meta = get_post_meta( $template_id, '_elementor_page_settings', true );

	$stfe_fixed_footer = isset($template_meta["fixed_footer"]) ? $template_meta["fixed_footer"] : '';
	if( $stfe_fixed_footer == 'yes' ) {
		$footer_class[] = 'stfe-fixed-footer';
	} 
	
	?>
		<footer class="<?php echo esc_attr( join(" ",$footer_class) ); ?>">
			<?php soft_template_core()->locations->do_location( 'footer' ); ?>
		</footer>
	</div><!-- #page -->
    <?php wp_footer(); ?>
</body>
</html>