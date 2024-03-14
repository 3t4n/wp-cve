<?php
/**
 * Search form template
 */
$settings = $this->get_settings();
$search_post_type = $this->get_settings_for_display('search_post_type');
$search_tax_dropdown = $this->get_settings_for_display('search_tax_dropdown');
$search_tax_dropdown_opt_all = $this->get_settings_for_display('search_tax_dropdown_opt_all');
?>
<form role="search" method="get" class="lakit-search__form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <?php
    if(!empty($search_tax_dropdown)){
        $show_dd_extra = true;
        $frm_name = 'lakit_term';
        $value_field = 'slug';
        if($search_tax_dropdown === 'product_cat'){
            $frm_name = 'product_cat';
            $show_dd_extra = false;
        }
        if($search_tax_dropdown === 'category'){
            $frm_name = 'cat';
            $value_field = 'term_id';
            $show_dd_extra = false;
        }
        wp_dropdown_categories( [
            'show_option_all'   => $search_tax_dropdown_opt_all ?? 'All',
            'name'              => $frm_name,
            'taxonomy'          => $search_tax_dropdown,
            'value_field'       => $value_field,
            'hierarchical'      => true,
            'selected'          => $_GET[$frm_name] ?? '',
            'class'             => 'lakit-search__dropdown'
        ] );
        if($show_dd_extra){
            echo sprintf('<input type="hidden" name="lakit_tax" value="%1$s" />', $search_tax_dropdown);
        }
    }
    ?>
	<label class="lakit-search__label">
		<input type="search" class="lakit-search__field" placeholder="<?php echo esc_attr( $settings['search_placeholder'] ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
	</label>
	<?php if ( 'true' ===  $settings['show_search_submit'] ) : ?>
	<button type="submit" class="lakit-search__submit main-color"><?php
		$this->_icon( 'search_submit_icon', '<span class="lakit-search__submit-icon lakit-blocks-icon">%s</span>' );
		$this->_html( 'search_submit_label', '<div class="lakit-search__submit-label">%s</div>' );
	?></button>
	<?php endif; ?>
	<?php if ( !empty($search_post_type) ) : ?>
		<input type="hidden" name="post_type" value="<?php echo $search_post_type; ?>" />
	<?php endif; ?>
</form>