<?php

add_action( 'admin_menu', 'gswps_metabox_for_select2' );
add_action( 'save_post', 'gswps_save_metaboxdata', 10, 2 );
 
/*
 * Add a metabox
 * I hope you're familiar with add_meta_box() function, so, nothing new for you here
 */
function gswps_metabox_for_select2() {
	add_meta_box( 'gs_wps_metabox', 'Shortcode Settings : GS WooCommerce Product Slider', 'gs_wps_display_metabox', 'gs_wps_cpt', 'normal', 'default' );
	add_meta_box( 'gs_wps_shortcode', 'Shortcode Id', 'gs_wps_display_shortcode', 'gs_wps_cpt', 'normal', 'default' );
}

function gs_wps_display_shortcode(){
	global $post;
	$id=$post->ID;
	$meta_template_class = get_post_meta($id, 'gs_template_type', true);
	echo '<div class="shortcode">
            <span>Shortcode :</span> <code id="copy_id">[gs_wps id="' . $id . '" theme="'.$meta_template_class.'"]</code><button type="button" class="btn btn-default btn-copy js-tooltip js-copy tool"  data-tip="Copy to clipboard"><span class="dashicons dashicons-clipboard"></span></button>
        </div>';
}
/*
 * Display the fields inside it
 */
function gs_wps_display_metabox( $post_object ) {
 
	// do not forget about WP Nonces for security purposes
 
	// I decided to write all the metabox html into a variable and then echo it at the end
	
	// always array because we have added [] to our <select> name attribute
	$appended_tags = get_post_meta( $post_object->ID, 'gswps_select2_tags',true );
	$appended_cats = get_post_meta( $post_object->ID, 'gswps_select2_cats',true );
	$appended_cats_exclude = get_post_meta( $post_object->ID, 'gswps_select2_cats_exclude',true );
	$appended_sku = get_post_meta( $post_object->ID, 'gswps_select2_sku',true );
	$appended_attr = get_post_meta( $post_object->ID, 'gswps_select2_attr',true );
	$appended_posts = get_post_meta( $post_object->ID, 'gswps_select2_posts',true );
	$meta_element_class = get_post_meta($post_object->ID, 'gs_product_type', true);
	$meta_template_class = get_post_meta($post_object->ID, 'gs_template_type', true);
	$meta_element_cat = get_post_meta($post_object->ID, 'gs_product_category_type', true);
	
 ?>

 	<p><label for="gs_product_category_type">Category Type :</label><br />
		<select id="gs_product_category_type" style="width: 75%" name="gs_product_category_type">
            <option value="all_category" <?php selected( $meta_element_cat, 'all_category' ); ?>>All Category</option>
            <option value="select_category" <?php selected( $meta_element_cat, 'select_category' ); ?>>Select Category</option>
        </select>
    </p>


    <?php if( $categories = get_terms( 'product_cat' ) ){
		?>

		<div id="gs_cat">
		<p><label for="gswps_select2_cats">Selected Categories :</label><br /><select id="gswps_select2_cats" name="gswps_select2_cats[]" multiple="multiple" style="width:75%">
		<?php foreach( $categories as $cat) { ?>

			<?php $selected = ( is_array( $appended_cats ) && in_array($cat->slug, $appended_cats ) ) ? ' selected="selected"' : '';?>
			
			<option value="<?php echo $cat->slug; ?>"<?php echo $selected  ?>><?php echo $cat->name; ?></option>
		<?php }?>
		<select></p>

		<p><label for="gswps_select2_cats_exclude">Exclude Categories :</label><br /><select id="gswps_select2_cats_exclude" name="gswps_select2_cats_exclude[]" multiple="multiple" style="width:75%">
		<?php foreach( $categories as $cat) { ?>

			<?php $selected = ( is_array( $appended_cats_exclude ) && in_array($cat->slug, $appended_cats_exclude ) ) ? ' selected="selected"' : '';?>
			
			<option value="<?php echo $cat->slug; ?>"<?php echo $selected  ?>><?php echo $cat->name; ?></option>
		<?php }?>
		<select></p>
		</div>
	<?php }?>

	<p><label for="gs_product_type">Products Type :</label><br />
		<select id="gs_product_type" style="width: 75%" name="gs_product_type">
            <option value="all" <?php selected( $meta_element_class, 'all' ); ?>>All Products</option>
            <option disabled value="in_stock" <?php selected( $meta_element_class, 'in_stock'); ?>>In stock (Pro) </option>
            <option disabled value="best_seller" <?php selected( $meta_element_class, 'best_seller' ); ?>>Best Sellers (Pro)</option>
            <option disabled value="free" <?php selected( $meta_element_class, 'free' ); ?>>Free (Pro)</option>
            <option disabled value="featured" <?php selected( $meta_element_class, 'featured' ); ?>>Featured (Pro)</option>
            <option disabled value="top_rated" <?php selected( $meta_element_class, 'top_rated' ); ?>>Top Rated (Pro)</option>
            <option disabled value="custom_select_product" <?php selected( $meta_element_class, 'custom_select_product' ); ?>>Select your product by Name (Pro)</option>
            <option disabled value="custom_select_tag" <?php selected( $meta_element_class, 'custom_select_tag' ); ?>>Select your product by Tag (Pro)</option>
            <option disabled value="custom_select_sku" <?php selected( $meta_element_class, 'custom_select_sku' ); ?>>Select your product by SKU (Pro)</option>
            <!-- <option value="custom_select_attr" <?php selected( $meta_element_class, 'custom_select_attr' ); ?>>Select your product by Attribute</option> -->
        </select>
    </p>


<?php
	if( $tags = get_terms( 'product_tag', 'hide_empty=0' ) ) {
		?>

		<div id="gs_tag">
		<p><label for="gswps_select2_tags">Tags:</label><br /><select id="gswps_select2_tags" name="gswps_select2_tags[]" multiple="multiple" style="width:75%">
		<?php foreach( $tags as $tag ) { ?>

			<?php $selected = ( is_array( $appended_tags ) && in_array( $tag->slug, $appended_tags ) ) ? ' selected="selected"' : '';?>
			<option value="<?php echo $tag->slug; ?>"<?php echo $selected  ?>><?php echo $tag->name; ?></option>
		<?php }?>
		<select></p>
		</div>
	<?php }

	global $wpdb;

	 $sku= $wpdb->get_results( "SELECT * FROM `" . $wpdb->prefix . "postmeta` WHERE meta_key='_sku'" ) ;
	 foreach ($sku as $key => $product_sku) {
	 	if(!empty($product_sku->meta_value)):?>
			
 			<?php $sku[]=$product_sku->meta_value ;?>

		<?php endif;
	 }	

	  if(!empty($sku)){?>
			<div id="gs_sku">
				<p><label for="gswps_select2_sku">SKU :</label><br />
					<?php foreach($sku  as $value ) { ?>

						<?php if(!empty($value->meta_value)): ?>
							<?php $selected = ( is_array( $appended_sku  ) && in_array( $value->meta_value, $appended_sku  ) ) ? 'checked="checked"': '';?>
							<input type="checkbox" value="<?php echo $value->meta_value ;?>" name="gswps_select2_sku[]" <?php echo $selected; ?> /><?php echo $value->meta_value ?>
						<?php endif; ?>

			<?php	}?>
			</p></div>
	 	
	 <?php }
	 
	 $all_product_data = $wpdb->get_results("SELECT ID,post_title FROM `" . $wpdb->prefix . "posts` where post_type='product' and post_status = 'publish'");
	?>

	<div id="gs_products">
		<p><label for="gswps_select2_posts">Products:</label><br /><select id="gswps_select2_posts" name="gswps_select2_posts[]" multiple="multiple" style="width:75%">
			<?php foreach( $all_product_data  as $product ) { ?>

			<?php $selected = ( is_array( $appended_posts ) && in_array( $product->ID, $appended_posts ) ) ? ' selected="selected"' : '';?>
			<option value="<?php echo $product->ID; ?>"<?php echo $selected  ?> ><?php echo $product->post_title; ?></option>
			<?php }?>
			</select></p>
	</div>
	<div class="gs-themes">
		<p><label for="gs_template_type">Template Type :</label><br />
		<select id="gs_template_type" style="width: 75%" name="gs_template_type">
            <option value="gs-effect-1" <?php selected( $meta_template_class, 'gs-effect-1' ); ?>>Effect (Lite 1)</option>
            <option value="gs-effect-2" <?php selected( $meta_template_class, 'gs-effect-2'); ?>>Effect (Lite 2)</option>
            <option value="gs-effect-3" <?php selected( $meta_template_class, 'gs-effect-3' ); ?>>Effect (Lite 3)</option>
            <option value="gs-effect-4" <?php selected( $meta_template_class, 'gs-effect-4' ); ?>>Effect (Lite 4)</option>
            <option value="gs-effect-5" <?php selected( $meta_template_class, 'gs-effect-5' ); ?>>Effect (Lite 5)</option>
            <option disabled value="gs-effect-6" <?php selected( $meta_template_class, 'gs-effect-6' ); ?>>Theme 6 (Vertical) (Pro)</option>
            <option disabled value="gs-effect-7" <?php selected( $meta_template_class, 'gs-effect-7' ); ?>>Theme 7 (Zoom) (Pro)</option>
            <option disabled value="gs-effect-8" <?php selected( $meta_template_class, 'gs-effect-8' ); ?>>Theme 8 (Expand) (Pro)</option>
            <option disabled value="gs-effect-9" <?php selected( $meta_template_class, 'gs-effect-9' ); ?>>Theme 9 (Pair 1) (Pro)</option>
            <option disabled value="gs-effect-10" <?php selected( $meta_template_class, 'gs-effect-10' ); ?>>Theme 10 (Pair 2) (Pro)</option> 
        
        </select>
	</div>
	
<?php }
 
 
function gswps_save_metaboxdata( $post_id, $post ) {
 
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return $post_id;
 
	// if post type is different from our selected one, do nothing
	if ( $post->post_type == 'gs_wps_cpt' ) {
		if( isset( $_POST['gswps_select2_tags'] ) )
			update_post_meta( $post_id, 'gswps_select2_tags', $_POST['gswps_select2_tags'] );

		if( isset( $_POST['gs_product_category_type'] ) ){
			update_post_meta( $post_id, 'gs_product_category_type', $_POST['gs_product_category_type'] );
		}

		if( isset( $_POST['gswps_select2_cats'] ) )
			update_post_meta( $post_id, 'gswps_select2_cats', $_POST['gswps_select2_cats'] );
		else
			delete_post_meta( $post_id, 'gswps_select2_cats' );

		if( isset( $_POST['gswps_select2_cats_exclude'] ) )
			update_post_meta( $post_id, 'gswps_select2_cats_exclude', $_POST['gswps_select2_cats_exclude'] );
		else
			delete_post_meta( $post_id, 'gswps_select2_cats_exclude' );

		if( isset( $_POST['gswps_select2_sku'] ) )
			update_post_meta( $post_id, 'gswps_select2_sku', $_POST['gswps_select2_sku'] );
		else
			delete_post_meta( $post_id, 'gswps_select2_sku' );

		if( isset( $_POST['gswps_select2_attr'] ) )
			update_post_meta( $post_id, 'gswps_select2_attr', $_POST['gswps_select2_attr'] );
		else
			delete_post_meta( $post_id, 'gswps_select2_attr' );

		if( isset( $_POST['gs_product_type'] ) )
			update_post_meta( $post_id, 'gs_product_type', $_POST['gs_product_type'] );
		else
			delete_post_meta( $post_id, 'gs_product_type' );

		if( isset( $_POST['gs_template_type'] ) )
			update_post_meta( $post_id, 'gs_template_type', $_POST['gs_template_type'] );
		else
			delete_post_meta( $post_id, 'gs_template_type' );

		if( isset( $_POST['gswps_select2_posts'] ) )
			update_post_meta( $post_id, 'gswps_select2_posts', $_POST['gswps_select2_posts'] );
		else
			delete_post_meta( $post_id, 'gswps_select2_posts' );
	}
	return $post_id;
}