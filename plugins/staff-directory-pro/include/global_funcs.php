<?php

if ( !function_exists('cd_get_staff_search_form') )
{
	function cd_get_staff_search_form($advanced = false, $order_by = 'last_name', $order = 'ASC')
	{
		// TODO: share this var with the corresponding member variable
		$allowed_order_by_keys = array('first_name', 'last_name', 'title', 'phone', 'email', 'address', 'website', 'department', 'staff_category', 'menu_order');
		$mode 		= ($advanced) ? 'advanced' : 'basic';
		$order_by 	= in_array($order_by, $allowed_order_by_keys) ? $order_by : 'last_name';
		$order 		= in_array(strtoupper($order), array('ASC', 'DESC')) ? strtoupper($order) : 'ASC';
		$sc 		= sprintf('[search_staff_members mode="%s" order_by="%s" order="%s"]', $mode, $order_by, $order);
		return do_shortcode($sc);
	}
} // if !function_exists

if ( !function_exists('cd_get_staff_metadata') )
{
	function cd_get_staff_metadata($id, $options = array())
	{

		$r['my_phone'] = get_post_meta($id, '_ikcf_phone', true);
		$r['my_email'] = get_post_meta($id, '_ikcf_email', true);
		$r['my_title'] = get_post_meta($id, '_ikcf_title', true);
		$r['my_website'] = htmlspecialchars( get_post_meta($id, '_ikcf_website', true) );
		$r['my_department'] = htmlspecialchars( cd_get_staff_departments($id, true) );
		$r['my_address'] = htmlspecialchars( get_post_meta($id, '_ikcf_address', true) );
		$r['show_title'] = isset($options['show_title']) ? $options['show_title'] : true;
		$r['show_address'] = isset($options['show_address']) ? $options['show_address'] : true;
		$r['show_phone'] = isset($options['show_phone']) ? $options['show_phone'] : true;
		$r['show_name'] = isset($options['show_name']) ? $options['show_name'] : true;
		$r['show_bio'] = isset($options['show_bio']) ? $options['show_bio'] : true;
		$r['show_photo'] = isset($options['show_photo']) ? $options['show_photo'] : true;
		$r['show_email'] = isset($options['show_email']) ? $options['show_email'] : true;
		$r['show_website'] = isset($options['show_website']) ? $options['show_website'] : true;
		$r['show_department'] = isset($options['show_department']) ? $options['show_department'] : true;

		return $r;
	}
} // if !function_exists

if ( !function_exists('cd_get_staff_departments') )
{
	function cd_get_staff_departments($id, $format_as_string = false)
	{
		$dept_terms = get_the_terms($id, 'staff-member-category');
		$found_depts = array();

		if ( !empty($dept_terms) ) {
			foreach($dept_terms as $dept_term) {
				$found_depts[] = $dept_term->name;
			}
		}

		return $format_as_string
			   ? implode(', ', $found_depts)
			   : $found_depts;
	}
} // if !function_exists

if ( !function_exists('cd_get_staff_member_photo') )
{
	function cd_get_staff_member_photo($id, $image_size = 'post-thumbnail', $image_tag = false)
	{
		// first, look for a thumbnail
		$placeholder_image_url = get_the_post_thumbnail_url($id, $image_size);
		$alt_text = get_the_title($id);

		// if no thumbnail, check the enable_placeholder_images options
		if ( empty($placeholder_image_url) ) {
			$alt_text .= sprintf( ' (%s)', __('no photo', 'staff-directory-pro') );
			$options = get_option( 'sd_options' );
			$placeholder_setting = !empty($options['enable_placeholder_images'])
								   ? $options['enable_placeholder_images']
								   : 'off';

			if ( 'default' == $placeholder_setting ) {
				// use default image
				$placeholder_image_url = plugins_url('../assets/img/default-person.png', __FILE__);
			}
			else if ( 'custom' == $placeholder_setting ) {
			// if: use custom placeholder images, look up the URL via the placeholder_image_path option
				$placeholder_image_url = !empty($options['placeholder_image_path'])
										 ? $options['placeholder_image_path']
										 : '';
			}
			else {
				// do not use any placeholder images
				$placeholder_image_url = '';
			}
		}

		if ( empty($placeholder_image_url) ) {
			return '';
		}

		// if wrap in image tag, return the image tag. if its a real featured image, use the persons name as the alt text
		if ( $image_tag ) {
			$css_class = 'company_directory_staff_member_photo';
			$img_w = get_option( 'thumbnail_size_w' ) . 'px';
			$img_h = get_option( 'thumbnail_size_h' ) . 'px';
			return sprintf( '<img src="%s" alt="%s" class="%s" width="%s" height="%s" />',
							esc_attr($placeholder_image_url),
							esc_attr($alt_text),
							esc_attr($css_class),
							esc_attr($img_w),
							esc_attr($img_h)
					);
		}
		else {
			return $placeholder_image_url;
		}

	}
} // if !function_exists

