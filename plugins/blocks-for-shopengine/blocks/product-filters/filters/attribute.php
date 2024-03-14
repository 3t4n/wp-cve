<?php 
	defined('ABSPATH') || exit; // Exit if accessed directly
	$uid = uniqid();
	/**
	 * 
	 * Check attribute list is available or not 
	 * 
	 */ 
	
	if (
		!empty($settings['shopengine_attributes_list']) &&
	 	is_array($settings['shopengine_attributes_list'])
	): 
	
	// check if the collapse enabled
	$collapse	   = false;

	if( $settings['shopengine_filter_view_mode']['desktop'] === 'collapse' ) {
		$collapse	   = true;
	}
	
	/**
	 * 
	 * Loop through the attribute list
	 * assign every attribute property into $attribute variable 
	 */
	$taxonomies =  wc_get_attribute_taxonomies();
	
	foreach ($settings['shopengine_attributes_list'] as $attribute):
		$attributes = $attribute['shopengine_attribute']['desktop'];
		$attribute_name = !empty($attributes) ?  $attributes['id']: '';
		$attribute_id = wc_attribute_taxonomy_id_by_name($attribute_name);

	if(isset($taxonomies["id:".$attribute_id]->attribute_type) && $taxonomies["id:".$attribute_id]->attribute_type == 'select'):
	
	$taxonomy	= $attribute_name;
	$variations = get_terms($taxonomy, 'orderby=name&hide_empty=0');

	/**
	 * 
	 * if variations is not empty show filter markup
	 */

	if (!empty($variations) && empty($variations->errors)):
	
		$collapse_expand = '';

		if((isset($_GET['shopengine_filter_attribute_'.$taxonomy]) || $settings['shopengine_filter_attribute_expand_collapse']['desktop'] === true) && !empty($_GET["attribute_nonce"]) && wp_verify_nonce( sanitize_text_field(wp_unslash($_GET["attribute_nonce"])), "attribute_filter" )) {
			$collapse_expand = 'open';
		}
?>

	<div class="shopengine-filter-single <?php echo esc_attr( $collapse ? 'shopengine-collapse' : '' ) ?>">
		<div class="shopengine-filter <?php echo esc_attr( $collapse_expand ) ?>">
			<?php 
				/**
				 * 
				 * show filter title
				 * 
				 */
				if(isset($attribute['shopengine_attribute_title']['desktop'])) : 
			?>
				<h3 class="shopengine-product-filter-title">
					<?php
						echo esc_html($attribute['shopengine_attribute_title']['desktop']);
						if( $collapse ) echo '<i class="eicon-chevron-right shopengine-collapse-icon"></i>';
					?>
					
				</h3>
			<?php 
				endif; // end of filter title 
			?>
		</div>
	
				
		<?php

			if( $collapse ) echo '<div class="shopengine-collapse-body '. esc_attr($collapse_expand) .'">';
				
			/**
			 * 
			 * loop through attribute list item
			 * 
			 */
			foreach ($variations as $variation):
				$prefix 					= 'shopengine_filter_attribute'; // this prefix refers to submited url property / form name
				$id							= 'xs-filter-attribute-'.$uid . '-' . $variation->slug;
				$name						=  $prefix. '_' . $variation->taxonomy;
				$selector_after_page_load	= $name .'-' .$variation->slug;
			?>
			<div class="filter-input-group">
				<input
					class="shopengine-filter-attribute <?php echo esc_attr($selector_after_page_load) ?>"
					name="<?php echo esc_attr($name) ?>"
					type="checkbox"
					id="<?php echo esc_attr( $id ) ?>"
					value="<?php echo esc_attr($variation->slug); ?>"
				/>
				<label class="shopengine-filter-attribute-label" for="<?php echo esc_attr( $id ) ?>">
					<span class="shopengine-checkbox-icon">
						<span>
							<?php render_icon($settings['shopengine_check_icon']['desktop'], ['aria-hidden' => 'true']); ?>
						</span>
					</span>
					<?php echo esc_html($variation->name ); ?>
				</label>
			</div>
			<?php 
				endforeach;
				if( $collapse ) echo '</div>'; // end of collapse body container
			?>

		<form action="" method="get" class="shopengine-filter" id="shopengine_attribute_form">
			<input type="hidden" name="<?php echo esc_attr( $prefix ) ?>" class="shopengine-filter-attribute-value">
			<input type="hidden" name="attribute_nonce" value="<?php echo esc_attr(wp_create_nonce("attribute_filter")) ?>">
		</form>
		
	</div>

<?php 
	endif;
	endif;
	endforeach; 
	endif;
?>