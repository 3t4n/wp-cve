<?php 

/**
 * @package    DirectoryPress
 * @subpackage DirectoryPress/public/single-listing
 * @author     Designinvento <developers@designinvento.net>
*/
?>
<div class="directorypress-listing-category-single">
	<span class="single-listing-meta-label"><?php echo esc_html__('Categories:', 'DIRECTORYPRESS'); ?></span>
	<?php
		$terms = get_the_terms($listing->post->ID, DIRECTORYPRESS_CATEGORIES_TAX);
		if(is_array($terms)){
			foreach ($terms AS $key=>$term):
				$term_link = get_term_link( $term );
				if ($term->parent == 0){ 
					echo '<a class="directorypress-cat" href="' . esc_url($term_link) . '" title="'. esc_attr($term->name) .'">'. esc_html($term->name).'</a>';
				}
			endforeach;	
		}
	?>								
</div>

 