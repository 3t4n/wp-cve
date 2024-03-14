<?php
class MaxGalleriaGalleryThumbWidget extends WP_Widget {
	public function __construct() {
		$widget_id = 'maxgalleria-gallery-thumb-widget';
		$widget_name = esc_html__('MaxGalleria Gallery Thumbnail', 'maxgalleria');
		$widget_args = array('description' => esc_html__('Show thumbnail for a MaxGalleria gallery.', 'maxgalleria'));
		
		parent::__construct($widget_id, $widget_name, $widget_args);
	}
	
	// Outputs the contents of the widget
	public function widget($args, $instance) {
		$output = '';
		
		$title = esc_html(apply_filters('widget_title', $instance['title']));
		$gallery_id = esc_attr((isset($instance['gallery_id'])) ? $instance['gallery_id'] : '');
		$thumb_width = esc_attr((isset($instance['thumb_width'])) ? $instance['thumb_width'] : '');
		$thumb_height = esc_attr((isset($instance['thumb_height'])) ? $instance['thumb_height'] : '');
		$url = esc_url((isset($instance['url'])) ? $instance['url'] : '');
		
		$output .= wp_kses_post($args['before_widget']);
		
		if (isset($title) && $title != '') {
			$output .= wp_kses_post($args['before_title'] . $title . $args['after_title']);
		}
		
		if (isset($gallery_id) && $gallery_id != '') {
			$output .= wp_kses_post(apply_filters(MAXGALLERIA_FILTER_GALLERY_WIDGET_BEFORE_THUMB_OUTPUT, '', $gallery_id));
			$output .= do_shortcode('[maxgallery_thumb id="' . esc_attr($gallery_id) . '" width="' . esc_attr($thumb_width) . '" height="' . esc_attr($thumb_height) . '" url="' . esc_url($url) . '"]');
			$output .= wp_kses_post(apply_filters(MAXGALLERIA_FILTER_GALLERY_WIDGET_AFTER_THUMB_OUTPUT, '', $gallery_id));
		}

		$output .= wp_kses_post($args['after_widget']);
		
    $allowed_html = array(
      'a' => array(
        'href' => array(),
        'title' => array(),
        'class' => array(),
        'id' => array(),
        'target' => array()
      ),
      'h3' => array(
        'class' => array(),
      ),        
      'img' => array(
        'src' => array(),
        'alt' => array(),
        'class' => array(),
        'loading' => array(),
        'srcset' => array(),
        'srcset' => array(),
        'sizes' => array(),
        'width' => array(),
        'height' => array()
      ),
      'div' => array(
        'class' => array()
      )
    );    

    echo wp_kses($output, $allowed_html);
	}
	
	// Outputs the options in the admin
	public function form($instance) {
		$output = '';
		$galleries = get_posts(array('post_type' => MAXGALLERIA_POST_TYPE, 'post_status' => 'publish', 'numberposts' => -1));
		
		// Form values
		$title = esc_html((isset($instance['title'])) ? $instance['title'] : esc_html__('Gallery Thumb Widget', 'maxgalleria'));
		$gallery_id = esc_attr((isset($instance['gallery_id'])) ? $instance['gallery_id'] : '');
		$thumb_width = esc_attr((isset($instance['thumb_width'])) ? $instance['thumb_width'] : '');
		$thumb_height = esc_attr((isset($instance['thumb_height'])) ? $instance['thumb_height'] : '');
		$url = esc_url((isset($instance['url'])) ? $instance['url'] : '');
		
		// Form layout
		$output .= '<table cellpadding="5" style="width: 100%; padding-top: 10px; padding-bottom: 10px;">';
		$output .= '	<tr>';
		$output .= '		<td width="100">' . esc_html__('Title: ', 'maxgalleria') . '</td>';
		$output .= '		<td>';
		$output .= '			<input class="widefat" type="text" id="' . esc_attr($this->get_field_id('title')) . '" name="' . esc_attr($this->get_field_name('title')) . '" value="' . esc_attr($title) . '" />';
		$output .= '		</td>';
		$output .= '	</tr>';
		$output .= '	<tr>';
		$output .= '		<td>' . esc_html__('Gallery: ', 'maxgalleria') . '</td>';
		$output .= '		<td>';
		$output .= '			<select class="widefat" id="' . esc_attr($this->get_field_id('gallery_id')) . '" name="' . esc_attr($this->get_field_name('gallery_id')) . '">';
		$output .= '				<option value="">-- ' . esc_html__('Select Gallery', 'maxgalleria') . ' --</option>';
									foreach ($galleries as $g) {
										$selected = ($gallery_id == $g->ID) ? 'selected=selected' : '';
										$output .= '<option value="' . esc_attr($g->ID) . '" ' . esc_attr($selected) . '>' . esc_attr($g->post_title) . '</option>';
									}
		$output .= '			</select>';
		$output .= '		</td>';
		$output .= '	</tr>';
		$output .= '	<tr>';
		$output .= '		<td>' . esc_html__('Thumb Width: ', 'maxgalleria') . '</td>';
		$output .= '		<td>';
		$output .= '			<input style="width: 50px;" type="text" id="' . esc_attr($this->get_field_id('thumb_width')) . '" name="' . esc_attr($this->get_field_name('thumb_width')) . '" value="' . esc_attr($thumb_width) . '" /> px';
		$output .= '		</td>';
		$output .= '	</tr>';
		$output .= '	<tr>';
		$output .= '		<td>' . esc_html__('Thumb Height: ', 'maxgalleria') . '</td>';
		$output .= '		<td>';
		$output .= '			<input style="width: 50px;" type="text" id="' . esc_attr($this->get_field_id('thumb_height')) . '" name="' . esc_attr($this->get_field_name('thumb_height')) . '" value="' . esc_attr($thumb_height) . '" /> px';
		$output .= '		</td>';
		$output .= '	</tr>';
		$output .= '	<tr>';
		$output .= '		<td>' . esc_html__('URL: ', 'maxgalleria') . '</td>';
		$output .= '		<td>';
		$output .= '			<input class="widefat" type="text" id="' . esc_attr($this->get_field_id('url')) . '" name="' . esc_attr($this->get_field_name('url')) . '" value="' . esc_url($url) . '" />';
		$output .= '		</td>';
		$output .= '	</tr>';
		$output .= '</table>';
		
    $allowed_html = array(
      'a' => array(
        'href' => array(),
        'title' => array(),
        'class' => array(),
        'id' => array()
      ),
      'img' => array(
        'src' => array(),
        'alt' => array(),
        'class' => array(),
        'loading' => array(),
        'srcset' => array(),
        'srcset' => array(),
        'sizes' => array(),
        'width' => array(),
        'height' => array()
      ),
      'select' => array(
        'class' => array(),
        'selected' => array(), 
        'name'  => array()
      ),
      'option' => array(
        'value' => array(),
        'selected' => array()
      ),
      'input' => array(
        'id' => array(),
        'class' => array(),
        'value' => array(),
        'name' => array(),
        'type' => array()
      ),
      'div' => array(
        'class' => array()
      ),
      'br' => array(),
      'em' => array(),
      'strong' => array(),
      'table' => array(
        'style' => array(),
        'id' => array(),
        'cellpadding' => array(),
        'cellspacing' => array()
      ),
      'tr' => array(),
      'td' => array(
        'width' => array(), 
        'class' => array()
      )
    );    

    echo wp_kses($output, $allowed_html);
	}
	
	// Saves the widget options
	public function update($new_instance, $old_instance) {
		$instance = array();
		$instance['title'] = (isset($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
		$instance['gallery_id'] = (isset($new_instance['gallery_id'])) ? sanitize_text_field($new_instance['gallery_id']) : '';
		$instance['thumb_width'] = (isset($new_instance['thumb_width'])) ? sanitize_text_field($new_instance['thumb_width']) : '';
		$instance['thumb_height'] = (isset($new_instance['thumb_height'])) ? sanitize_text_field($new_instance['thumb_height']) : '';
		$instance['url'] = (isset($new_instance['url'])) ? esc_url_raw($new_instance['url']) : '';
		
		return $instance;
	}
}
?>