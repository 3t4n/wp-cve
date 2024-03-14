<?php
/**
 * @package    DirectoryPress
 * @subpackage DirectoryPress/includes
 * @author     Designinvento <developers@designinvento.net>
 */

// fields acctions
add_action( 'directorypress-category_add_form_fields', 'directorypress_category_add_fields', 10, 2 ); 
add_action( 'directorypress-category_edit_form_fields', 'directorypress_category_edit_fields', 10, 2 );
add_action( 'edited_directorypress-category', 'directorypress_save_category_fields', 10, 2 );
add_action( 'create_directorypress-category', 'directorypress_save_category_fields', 10, 2 );
add_action( 'after-directorypress-category-table', 'directorypress_terms_configuration_modal');

add_action('wp_ajax_directorypress_terms_configuration_html', 'directorypress_terms_configuration_html');
add_action('wp_ajax_directorypress_save_category_fields_ajax', 'directorypress_save_category_fields_ajax');


// Add Category Fields
function directorypress_category_add_fields( $term ) {
	
	?>
	<div class="form-field term-ct_cat_icon-wrap directorypress-term-form-field">
		<label for="directorypress_category_icon"><?php _e( 'Image Icon (Display on Category)', 'geodirectory' ); ?></label>
        <?php echo wp_kses_post(directorypress_render_cat_icon()); ?>
    </div>
	<div class="form-field term-ct_cat_icon-wrap directorypress-term-form-field">
		<label for="directorypress_category_icon_for_listing"><?php _e( 'Image Icon (Display on Listings)', 'geodirectory' ); ?></label>
        <?php echo wp_kses_post(directorypress_render_cat_icon_for_listing()); ?>
    </div>
	<div class="form-field term-ct_cat_icon-wrap directorypress-term-form-field">
		<label for="directorypress_category_icon_for_map"><?php _e( 'Image Icon (Display on Map)', 'geodirectory' ); ?></label>
        <?php echo wp_kses_post(directorypress_render_cat_icon_for_map()); ?>
    </div>
	<div class="form-field term-ct_cat_icon-wrap directorypress-term-form-field">
		<label for="directorypress_category_background_image"><?php _e( 'Background Image', 'geodirectory' ); ?></label>
        <?php echo wp_kses_post(directorypress_render_cat_bg_image()); ?>
    </div>
	<div class="form-field term-ct_cat_icon-wrap directorypress-term-form-field">
		<label for="directorypress_category_font_icon"><?php _e( 'Font Icon (Display on Map or Categories)', 'geodirectory' ); ?></label>
        <input type="text" name="directorypress_category_font_icon" placeholder="<?php _e( 'Insert Icon Class', 'geodirectory' ); ?>" />
    </div>
	<div class="form-field term-colorpicker-wrap">
        <label for="term-colorpicker"><?php _e( 'Category Color to use with font icons', 'geodirectory' ); ?></label>
        <input name="marker_color" value="" class="colorpicker" id="term-colorpicker" />
    </div>
	<?php
}

// Edit Category Fields
function directorypress_terms_configuration_html($term_id) {
	$term_id = sanitize_text_field($_POST['term_id']);
	$term = get_term_by('id', esc_attr($term_id), DIRECTORYPRESS_CATEGORIES_TAX);
	
	$directorypress_category_icon = get_term_meta( $term->term_id, 'directorypress_category_icon', true );
	$directorypress_category_icon_for_listing = get_term_meta( $term->term_id, 'directorypress_category_icon_for_listing', true );
	$directorypress_category_icon_for_map = get_term_meta( $term->term_id, 'directorypress_category_icon_for_map', true );
	$directorypress_category_background_image = get_term_meta( $term->term_id, 'category-image-id', true );
	$directorypress_category_font_icon = get_term_meta( $term->term_id, 'directorypress_category_font_icon', true );
	$directorypress_category_color = get_term_meta( $term->term_id, 'marker_color', true );
	if ( !empty( $directorypress_category_icon['id'] ) ) {
		$directorypress_category_icon['full'] = wp_get_attachment_image ( $directorypress_category_icon, 'full' );
	}
	if ( !empty( $directorypress_category_icon_for_listing['id'] ) ) {
		$directorypress_category_icon_for_listing['full'] = wp_get_attachment_image ( $directorypress_category_icon_for_listing, 'full' );
	}
	if ( !empty( $directorypress_category_icon_for_map['id'] ) ) {
		$directorypress_category_icon_for_map['full'] = wp_get_attachment_image ( $directorypress_category_icon_for_map, 'full' );
	}
	if ( !empty( $directorypress_category_background_image['id'] ) ) {
		$directorypress_category_background_image['full'] = wp_get_attachment_image ( $directorypress_category_background_image, 'full' );
	}
	if ( !empty( $directorypress_category_font_icon['id'] ) ) {
		$directorypress_category_font_icon = $directorypress_category_font_icon;
	}
	if ( !empty( $directorypress_category_color['id'] ) ) {
		$directorypress_category_color = $directorypress_category_color;
	}
	
	?>
	<div class="directorypress-modal-content wp-clearfix">
		<ul class="nav nav-tabs" id="tabContent">
			<li class="active"><a href="#term-icon" data-toggle="tab"><?php _e('Term Icon', 'DIRECTORYPRESS'); ?></a></li>
			<li><a href="#listing-icon" data-toggle="tab"><?php _e('Listing Icon', 'DIRECTORYPRESS'); ?></a></li>
			<li><a href="#map-icon" data-toggle="tab"><?php _e('Map Icon', 'DIRECTORYPRESS'); ?></a></li>
			<li><a href="#backgroubd-image" data-toggle="tab"><?php _e('Background', 'DIRECTORYPRESS'); ?></a></li>
			<li><a href="#term-color" data-toggle="tab"><?php _e('Color', 'DIRECTORYPRESS'); ?></a></li>
		</ul>
		<div class="tab-content">
			<form class="directorypress-terms-configuration-form" method="POST" action="">
				<div class="tab-pane fade active in" id="term-icon">
					<div class="form-field term-ct_cat_icon-wrap directorypress-term-form-field">
						<div scope="row"><label for="directorypress_category_icon"><?php _e( 'Image Icon (Display on Category)', 'DIRECTORYPRESS' ); ?></label></div>
						 <div><?php echo directorypress_render_cat_icon( $directorypress_category_icon ); ?></div>
					</div>
				</div>
				<div class="tab-pane fade" id="listing-icon">
					<div class="form-field term-ct_cat_icon-wrap directorypress-term-form-field">
						<div scope="row"><label for="directorypress_category_icon_for_listing"><?php _e( 'Image Icon (Display on Listings)', 'DIRECTORYPRESS' ); ?></label></div>
						 <div><?php echo directorypress_render_cat_icon_for_listing( $directorypress_category_icon_for_listing ); ?></div>
					</div>
				</div>
				<div class="tab-pane fade" id="map-icon">
					<div class="form-field term-ct_cat_icon-wrap directorypress-term-form-field">
						<div scope="row"><label for="directorypress_category_icon_for_map"><?php _e( 'Image Icon (Display on Map)', 'DIRECTORYPRESS' ); ?></label></div>
						 <div><?php echo directorypress_render_cat_icon_for_map( $directorypress_category_icon_for_map ); ?></div>
					</div>
					<div class="form-field term-ct_cat_icon-wrap directorypress-term-form-field">
						<div scope="row"><label for="directorypress_category_font_icon"><?php _e( 'Font Icon (Display on Map or Categories)', 'DIRECTORYPRESS' ); ?></label></div>
						<div><input type="text" name="directorypress_category_font_icon" placeholder="<?php _e( 'Insert Icon Class', 'DIRECTORYPRESS' ); ?>" value="<?php echo esc_attr($directorypress_category_font_icon); ?>" /></div>
					</div>
				</div>
				<div class="tab-pane fade" id="backgroubd-image">
					<div class="form-field term-ct_cat_icon-wrap directorypress-term-form-field">
						<div scope="row"><label for="directorypress_category_background_image"><?php _e( 'Background Image', 'DIRECTORYPRESS' ); ?></label></div>
						 <div><?php echo directorypress_render_cat_bg_image( $directorypress_category_background_image ); ?></div>
					</div>
				</div>
				<div class="tab-pane fade" id="term-color">
					<div class="form-field term-colorpicker-wrap directorypress-term-form-field">
						<div scope="row"><label for="term-colorpicker"><?php _e( 'Category Color to use with font icons', 'DIRECTORYPRESS' ); ?></label></div>
						<div><input name="marker_color" value="<?php echo esc_attr($directorypress_category_color); ?>" class="colorpicker" id="term-colorpicker" /></div>
					</div>
				</div>
				<div class="id">
					<input type="hidden" name="term_id" value="<?php echo esc_attr($term_id); ?>">
				</div>
			</form>
			<div class="response"></div>
		</div>
	</div>
	<?php
	die();
}
function directorypress_category_edit_fields($term, $taxonomy ) {
	$directorypress_category_icon = get_term_meta( $term->term_id, 'directorypress_category_icon', true );
	$directorypress_category_icon_for_listing = get_term_meta( $term->term_id, 'directorypress_category_icon_for_listing', true );
	$directorypress_category_icon_for_map = get_term_meta( $term->term_id, 'directorypress_category_icon_for_map', true );
	$directorypress_category_background_image = get_term_meta( $term->term_id, 'category-image-id', true );
	$directorypress_category_font_icon = get_term_meta( $term->term_id, 'directorypress_category_font_icon', true );
	$directorypress_category_color = get_term_meta( $term->term_id, 'marker_color', true );
	if ( !empty( $directorypress_category_icon['id'] ) ) {
		$directorypress_category_icon['full'] = wp_get_attachment_image ( $directorypress_category_icon, 'full' );
	}
	if ( !empty( $directorypress_category_icon_for_listing['id'] ) ) {
		$directorypress_category_icon_for_listing['full'] = wp_get_attachment_image ( $directorypress_category_icon_for_listing, 'full' );
	}
	if ( !empty( $directorypress_category_icon_for_map['id'] ) ) {
		$directorypress_category_icon_for_map['full'] = wp_get_attachment_image ( $directorypress_category_icon_for_map, 'full' );
	}
	if ( !empty( $directorypress_category_background_image['id'] ) ) {
		$directorypress_category_background_image['full'] = wp_get_attachment_image ( $directorypress_category_background_image, 'full' );
	}
	if ( !empty( $directorypress_category_font_icon['id'] ) ) {
		$directorypress_category_font_icon = $directorypress_category_font_icon;
	}
	if ( !empty( $directorypress_category_color['id'] ) ) {
		$directorypress_category_color = $directorypress_category_color;
	}
	
	?>
	<tr class="form-field term-ct_cat_icon-wrap directorypress-term-form-field">
        <th scope="row"><label for="directorypress_category_icon"><?php _e( 'Image Icon (Display on Category)', 'DIRECTORYPRESS' ); ?></label></th>
         <td><?php echo directorypress_render_cat_icon( $directorypress_category_icon ); ?></td>
    </tr>
	<tr class="form-field term-ct_cat_icon-wrap directorypress-term-form-field">
        <th scope="row"><label for="directorypress_category_icon_for_listing"><?php _e( 'Image Icon (Display on Listings)', 'DIRECTORYPRESS' ); ?></label></th>
         <td><?php echo directorypress_render_cat_icon_for_listing( $directorypress_category_icon_for_listing ); ?></td>
    </tr>
	<tr class="form-field term-ct_cat_icon-wrap directorypress-term-form-field">
        <th scope="row"><label for="directorypress_category_icon_for_map"><?php _e( 'Image Icon (Display on Map)', 'DIRECTORYPRESS' ); ?></label></th>
         <td><?php echo directorypress_render_cat_icon_for_map( $directorypress_category_icon_for_map ); ?></td>
    </tr>
	<tr class="form-field term-ct_cat_icon-wrap directorypress-term-form-field">
        <th scope="row"><label for="directorypress_category_background_image"><?php _e( 'Background Image', 'DIRECTORYPRESS' ); ?></label></th>
         <td><?php echo directorypress_render_cat_bg_image( $directorypress_category_background_image ); ?></td>
    </tr>
	<tr class="form-field term-ct_cat_icon-wrap directorypress-term-form-field">
		<th for="directorypress_category_font_icon"><?php _e( 'Font Icon (Display on Map or Categories)', 'DIRECTORYPRESS' ); ?></th>
        <td><input type="text" name="directorypress_category_font_icon" placeholder="<?php _e( 'Insert Icon Class', 'DIRECTORYPRESS' ); ?>" value="<?php echo esc_attr($directorypress_category_font_icon); ?>" /></td>
    </tr>
	<tr class="form-field term-colorpicker-wrap">
        <th scope="row"><label for="term-colorpicker"><?php _e( 'Category Color to use with font icons', 'DIRECTORYPRESS' ); ?></label></th>
        <td>
            <input name="marker_color" value="<?php echo esc_attr($directorypress_category_color); ?>" class="colorpicker" id="term-colorpicker" />
        </td>
    </tr>
<?php } ?>
<?php function directorypress_terms_configuration_modal(){
	
	$output = '<div id="directorypress_terms_configure" class="modal fade directorypress-admin-modal" role="dialog">';
		$output .= '<div class="modal-dialog modal-dialog-centered">';
			//Modal content
			$output .= '<div class="modal-content">';
				$output .= '<div class="topline"></div>';
				$output .= '<div class="modal-body"></div>';
				$output .= '<div class="modal-footer">';
					$output .= '<button type="button" class="btn btn-primary update-btn">'. esc_html__('Update', 'DIRECTORYPRESS') .'</button>';
					$output .= '<button type="button" class="btn btn-default cancel-btn" data-dismiss="modal">'. esc_html__('Close', 'DIRECTORYPRESS') .'</button>';
				$output .= '</div>';
			$output .= '</div>';
		$output .= '</div>';
	$output .= '</div>';
	echo wp_kses_post($output);
} ?>
<?php
// Render Icons 
function directorypress_render_cat_icon( $cat_icon = array(), $id = 'directorypress_category_icon', $name = '' ) {
        if ( empty( $name ) ) {
            $name = $id;
        }
        
        $img_id = !empty( $cat_icon['id'] ) ? $cat_icon['id'] : '';
        $img_src = !empty( $cat_icon['src'] ) ? $cat_icon['src'] : '';
        $show_img = !empty( $cat_icon['thumbnail'] ) ? $cat_icon['thumbnail'] : admin_url( 'images/media-button-image.gif' );
         
        ob_start();
        ?>
        <div class="directorypress-upload-img" data-field="<?php echo esc_attr($name); ?>">
            <div class="directorypress-upload-display thumbnail"><div class="centered"><img src="<?php echo esc_url($img_src); ?>" /></div></div>
            <div class="directorypress-upload-fields">
                <input type="hidden" id="<?php echo esc_attr($id); ?>[id]" name="<?php echo esc_attr($name); ?>[id]" value="<?php echo esc_attr($img_id); ?>" />
                <input type="text" id="<?php echo esc_attr($id); ?>[src]" name="<?php echo esc_attr($name); ?>[src]" value="<?php echo esc_url($img_src); ?>" style="position:absolute;left:-500px;width:50px;" />
                <button type="button" class="directorypress_upload_image_button button"><?php _e( 'Select Icon', 'DIRECTORYPRESS' ); ?></button>
                <button type="button" class="directorypress_remove_image_button button"><?php _e( 'Remove Icon', 'DIRECTORYPRESS' ); ?></button>
            </div>
        </div>
        <p class="description clear"><?php _e( 'Select a categoory icon to show on categories', 'DIRECTORYPRESS' ); ?></p>
        <?php
        return ob_get_clean();
}
// Render Listing Icons 
function directorypress_render_cat_icon_for_listing( $cat_icon = array(), $id = 'directorypress_category_icon_for_listing', $name = '' ) {
        if ( empty( $name ) ) {
            $name = $id;
        }
        
        $img_id = !empty( $cat_icon['id'] ) ? $cat_icon['id'] : '';
        $img_src = !empty( $cat_icon['src'] ) ? $cat_icon['src'] : '';
        $show_img = !empty( $cat_icon['full'] ) ? $cat_icon['full'] : admin_url( 'images/media-button-image.gif' );
         
        ob_start();
        ?>
        <div class="directorypress-upload-img" data-field="<?php echo esc_attr($name); ?>">
            <div class="directorypress-upload-display thumbnail"><div class="centered"><img src="<?php echo esc_url($img_src); ?>" /></div></div>
            <div class="directorypress-upload-fields">
                <input type="hidden" id="<?php echo esc_attr($id); ?>[id]" name="<?php echo esc_attr($name); ?>[id]" value="<?php echo esc_attr($img_id); ?>" />
                <input type="text" id="<?php echo esc_attr($id); ?>[src]" name="<?php echo esc_attr($name); ?>[src]" value="<?php echo esc_url($img_src); ?>" style="position:absolute;left:-500px;width:50px;" />
                <button type="button" class="directorypress_upload_image_button button"><?php _e( 'Select Icon', 'DIRECTORYPRESS' ); ?></button>
                <button type="button" class="directorypress_remove_image_button button"><?php _e( 'Remove Icon', 'DIRECTORYPRESS' ); ?></button>
            </div>
        </div>
        <p class="description clear"><?php _e( 'Select a categoory icon to show on Listing', 'DIRECTORYPRESS' ); ?></p>
        <?php
        return ob_get_clean();
}
// Render Map Icons 
function directorypress_render_cat_icon_for_map( $cat_icon = array(), $id = 'directorypress_category_icon_for_map', $name = '' ) {
        if ( empty( $name ) ) {
            $name = $id;
        }
        
        $img_id = !empty( $cat_icon['id'] ) ? $cat_icon['id'] : '';
        $img_src = !empty( $cat_icon['src'] ) ? $cat_icon['src'] : '';
        $show_img = !empty( $cat_icon['full'] ) ? $cat_icon['full'] : admin_url( 'images/media-button-image.gif' );
         
        ob_start();
        ?>
        <div class="directorypress-upload-img" data-field="<?php echo esc_attr($name); ?>">
            <div class="directorypress-upload-display thumbnail"><div class="centered"><img src="<?php echo esc_url($img_src); ?>" /></div></div>
            <div class="directorypress-upload-fields">
                <input type="hidden" id="<?php echo esc_attr($id); ?>[id]" name="<?php echo esc_attr($name); ?>[id]" value="<?php echo esc_attr($img_id); ?>" />
                <input type="text" id="<?php echo esc_attr($id); ?>[src]" name="<?php echo esc_attr($name); ?>[src]" value="<?php echo esc_url($img_src); ?>" style="position:absolute;left:-500px;width:50px;" />
                <button type="button" class="directorypress_upload_image_button button"><?php _e( 'Select Icon', 'DIRECTORYPRESS' ); ?></button>
                <button type="button" class="directorypress_remove_image_button button"><?php _e( 'Remove Icon', 'DIRECTORYPRESS' ); ?></button>
            </div>
        </div>
        <p class="description clear"><?php _e( 'Select a categoory icon to show on map', 'DIRECTORYPRESS' ); ?></p>
        <?php
        return ob_get_clean();
}
// Render Background Image
function directorypress_render_cat_bg_image( $cat_icon = array(), $id = 'category-image-id', $name = '' ) {
        if ( empty( $name ) ) {
            $name = $id;
        }
        
        $img_id = !empty( $cat_icon['id'] ) ? $cat_icon['id'] : '';
        $img_src = !empty( $cat_icon['src'] ) ? $cat_icon['src'] : '';
        $show_img = !empty( $cat_icon['full'] ) ? $cat_icon['full'] : admin_url( 'images/media-button-image.gif' );
         
        ob_start();
        ?>
        <div class="directorypress-upload-img" data-field="<?php echo esc_attr($name); ?>">
            <div class="directorypress-upload-display thumbnail"><div class="centered"><img src="<?php echo esc_url($img_src); ?>" /></div></div>
            <div class="directorypress-upload-fields">
                <input type="hidden" id="<?php echo esc_attr($id); ?>[id]" name="<?php echo esc_attr($name); ?>[id]" value="<?php echo esc_attr($img_id); ?>" />
                <input type="text" id="<?php echo esc_attr($id); ?>[src]" name="<?php echo esc_attr($name); ?>[src]" value="<?php echo esc_url($img_src); ?>" style="position:absolute;left:-500px;width:50px;" />
                <button type="button" class="directorypress_upload_image_button button"><?php _e( 'Select Icon', 'DIRECTORYPRESS' ); ?></button>
                <button type="button" class="directorypress_remove_image_button button"><?php _e( 'Remove Icon', 'DIRECTORYPRESS' ); ?></button>
            </div>
        </div>
        <p class="description clear"><?php _e( 'Select a categoory icon to show on map', 'DIRECTORYPRESS' ); ?></p>
        <?php
        return ob_get_clean();
}

// Save Category Fields
function directorypress_save_category_fields_ajax() {
	$response = array();
	$term_id = sanitize_text_field($_POST['term_id']);
	
	if ( isset( $_POST['directorypress_category_icon'] ) ) {
            
		update_term_meta( esc_attr($term_id), 'directorypress_category_icon', $_POST['directorypress_category_icon'] );
	}
	if ( isset( $_POST['directorypress_category_icon_for_listing'] ) ) {
		update_term_meta( esc_attr($term_id), 'directorypress_category_icon_for_listing', $_POST['directorypress_category_icon_for_listing'] );
	}
	if ( isset( $_POST['directorypress_category_icon_for_map'] ) ) {
            
		update_term_meta( esc_attr($term_id), 'directorypress_category_icon_for_map', $_POST['directorypress_category_icon_for_map'] );
	}
	if ( isset( $_POST['category-image-id'] ) ) {
            
			update_term_meta( esc_attr($term_id), 'category-image-id', $_POST['category-image-id'] );
	}
	if ( isset( $_POST['directorypress_category_font_icon'] ) ) {
            $directorypress_category_font_icon = sanitize_text_field($_POST['directorypress_category_font_icon']);
			 update_term_meta( esc_attr($term_id), 'directorypress_category_font_icon', $directorypress_category_font_icon );
	}
	if ( isset( $_POST['marker_color'] ) ) {
            $directorypress_category_color = sanitize_text_field($_POST['marker_color']);
			 update_term_meta(esc_attr($term_id), 'marker_color', $directorypress_category_color );
	}
	
	$response['type'] = 'success';
	$response['message'] = esc_html__('updated successfully', 'DIRECTORYPRESS');
	
	wp_send_json($response); 
	
}
function directorypress_save_category_fields( $term_id, $tt_id = '', $taxonomy = '' ) {
	if ( isset( $_POST['directorypress_category_icon'] ) ) {
            
		update_term_meta( $term_id, 'directorypress_category_icon', $_POST['directorypress_category_icon'] );
	}
	if ( isset( $_POST['directorypress_category_icon_for_listing'] ) ) {
            
		update_term_meta( $term_id, 'directorypress_category_icon_for_listing', $_POST['directorypress_category_icon_for_listing'] );
	}
	if ( isset( $_POST['directorypress_category_icon_for_map'] ) ) {
        
		update_term_meta( $term_id, 'directorypress_category_icon_for_map', $_POST['directorypress_category_icon_for_map'] );
	}
	if ( isset( $_POST['category-image-id'] ) ) {
		update_term_meta( $term_id, 'category-image-id', $_POST['category-image-id'] );
	}
	if ( isset( $_POST['directorypress_category_font_icon'] ) ) {
            $directorypress_category_font_icon = sanitize_text_field($_POST['directorypress_category_font_icon']);
			 update_term_meta( $term_id, 'directorypress_category_font_icon', $directorypress_category_font_icon );
	}
	if ( isset( $_POST['marker_color'] ) ) {
            $directorypress_category_color = sanitize_text_field($_POST['marker_color']);
			 update_term_meta( $term_id, 'marker_color', $directorypress_category_color );
	}
	
}

// get fields icons

function get_listing_category_icon_url($term_id){
	global $DIRECTORYPRESS_ADIMN_SETTINGS;
	$icon = get_term_meta($term_id, 'directorypress_category_icon', true );
	$get_term = get_term_by('id', $term_id, DIRECTORYPRESS_CATEGORIES_TAX);
	if(is_object($get_term)){
		if(($get_term->parent == 0 && $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_parent_category__default_icon']) || ($get_term->parent != 0 && $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_child_category_icons'])){
			if(!empty($icon['src'])){
				$icon_src = $icon['src'];	
			}else{
				$icon_src = DIRECTORYPRESS_RESOURCES_URL .'images/default-category-icon.png';
			}
		}else{
			$icon_src = '';
		}
	}else{
		$icon_src = '';
	}
	return $icon_src;
	
}
function get_listing_location_icon_url($term_id){
	
	$icon_src = DIRECTORYPRESS_RESOURCES_URL .'images/marker.png';
	
	return $icon_src;
}
function get_listing_category_icon_url_for_listing($term_id){
	$term = get_term($term_id, DIRECTORYPRESS_CATEGORIES_TAX);
	if(is_object($term)){
		if($term->parent == 0){
			$term_object = $term;
			$icon = get_term_meta($term_object->term_id, 'directorypress_category_icon_for_listing', true );
		}else{
			$term_object = get_term($term->parent, DIRECTORYPRESS_CATEGORIES_TAX);
			$icon = get_term_meta($term_object->term_id, 'directorypress_category_icon_for_listing', true );
		}
	}else{
		$icon = '';
	}
	
	if(!empty($icon)){
		if(!empty($icon['src'])){
			$icon_src = $icon['src'];	
		}else{
			$icon_src = DIRECTORYPRESS_RESOURCES_URL .'images/default-category-icon.png';
		}
	}else{
		$icon_src = DIRECTORYPRESS_RESOURCES_URL .'images/default-category-icon.png';
	}
	return $icon_src;
}
function get_listing_category_icon_url_for_map($term_id){
	$icon = get_term_meta($term_id, 'directorypress_category_icon_for_map', true );
	if(!empty($icon['src'])){
		$icon_src = $icon['src'];	
	}else{
		$icon_src = DIRECTORYPRESS_RESOURCES_URL .'images/marker-default.png';
	}
	return $icon_src;
}
function get_listing_category_background_image_url($term_id){
	$icon = get_term_meta($term_id, 'category-image-id', true );
	if(!empty($icon['src'])){
		$icon_src = $icon['src'];	
	}else{
		$icon_src = '';
	}
	return $icon_src;
}
function get_listing_category_font_icon($term_id){
	$icon = get_term_meta($term_id, 'directorypress_category_font_icon', true );
	if(!empty($icon)){
		$font_icon = $icon;	
	}else{
		$font_icon = '';
	}
	return $font_icon;
}
function get_listing_category_font_marker_icon($term_id){
	global $DIRECTORYPRESS_ADIMN_SETTINGS;
	$icon = get_term_meta($term_id, 'directorypress_category_font_icon', true );
	if(!empty($icon)){
		$font_icon = $icon;	
	}elseif(!empty($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_default_marker_icon'])){
		$font_icon = $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_default_marker_icon'];
	}else{
		$font_icon = 'dicode-material-icons dicode-material-icons-map-marker-outline';
	}
	return $font_icon;
}
function get_listing_category_color($term_id){
	
	$color = get_term_meta($term_id, 'marker_color', true );
	if(!empty($color)){
		$font_color = $color;	
	}else{
		$font_color = '';
	}
	return $font_color;
}
function get_listing_category_marker_color($term_id){
	global $DIRECTORYPRESS_ADIMN_SETTINGS;
	$color = get_term_meta($term_id, 'marker_color', true );
	if(!empty($color)){
		$font_color = $color;	
	}elseif(!empty($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_default_marker_color'])){
		$font_color = $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_default_marker_color'];
	}else{
		$font_color = '#5580ff';
	}
	return $font_color;
}

// add scripts

add_action('admin_enqueue_scripts', 'load_wp_media_files');

function load_wp_media_files(){
	
	wp_enqueue_media();
	wp_enqueue_style('wp-color-picker');
	wp_enqueue_script('wp-color-picker');
	add_action( 'admin_footer', 'add_script' );
}
function add_script(){
?>
<script>
jQuery(document).ready(function($) {
	$( '.colorpicker' ).wpColorPicker();
	// image uploads
    $('.directorypress-upload-img').each(function() {
        var $wrap = $(this);
        var field = $wrap.data('field');
        if ($('[name="' + field + '[id]"]').length && !$('[name="' + field + '[id]"]').val()) {
            $('.directorypress_remove_image_button', $wrap).hide();
        }
    });

    var media_frame = [];
    $(document).on('click', '.directorypress_upload_image_button', function(e) {
        e.preventDefault();

        var $this = $(this);
        var $wrap = $this.closest('.directorypress-upload-img');
        var field = $wrap.data('field');

        if ( !field ) {
            return
        }

        if (media_frame && media_frame[field]) {
            media_frame[field].open();
            return;
        }

        media_frame[field] = wp.media.frames.downloadable_file = wp.media({
            title: 'select image',
            button: {
                text: 'select image'
            },
            multiple: false
        });

        // When an image is selected, run a callback.
        media_frame[field].on('select', function() {
            var attachment = media_frame[field].state().get('selection').first().toJSON();

            var thumbnail = attachment.sizes.medium || attachment.sizes.full;
            if (field) {
                if($('[name="' + field + '[id]"]').length){
                    $('[name="' + field + '[id]"]').val(attachment.id);
                }
                if($('[name="' + field + '[src]"]').length){
                    $('[name="' + field + '[src]"]').val(attachment.url);
                }
                if($('[name="' + field + '"]').length){
                    $('[name="' + field + '"]').val(attachment.id);
                }


            }
            $wrap.closest('.form-field.form-invalid').removeClass('form-invalid');
            $('.directorypress-upload-display', $wrap).find('img').attr('src', thumbnail.url);
            $('.directorypress_remove_image_button').show();
        });
        // Finally, open the modal.
        media_frame[field].open();
    });

    $(document).on('click', '.directorypress_remove_image_button', function() {
        var $this = $(this);
        var $wrap = $this.closest('.directorypress-upload-img');
        var field = $wrap.data('field');
        $('.directorypress-upload-display', $wrap).find('img').attr('src', directorypress_js_instance.img_spacer).removeAttr('width height sizes alt class srcset');
		if (field) {
			if ($('[name="' + field + '[id]"]').length > 0) {
				$('[name="' + field + '[id]"]').val('');
				$('[name="' + field + '[src]"]').val('');
			}
			if ($('[name="' + field + '"]').length > 0) {
				$('[name="' + field + '"]').val('');
			}
		}
        $this.hide();
        return false;
    });
});
	</script>
	<?php
}