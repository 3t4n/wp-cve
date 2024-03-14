<?php
class MaxGalleriaGalleryWidget extends WP_Widget {
	public function __construct() {
		$widget_id = 'maxgalleria-gallery-widget';
		$widget_name = esc_html__('MaxGalleria Gallery', 'maxgalleria');
		$widget_args = array('description' => esc_html__('Show images from a MaxGalleria gallery.', 'maxgalleria'));
		
		parent::__construct($widget_id, $widget_name, $widget_args);
	}
	
	// Outputs the contents of the widget
	public function widget($args, $instance) {
		$output = '';
		
		$title = apply_filters('widget_title', $instance['title']);
		$gallery_id = (isset($instance['gallery_id'])) ? $instance['gallery_id'] : '';
		$columns = (isset($instance['columns'])) ? $instance['columns'] : '';
		$rows = (isset($instance['rows'])) ? $instance['rows'] : '';
		$shape = (isset($instance['shape'])) ? $instance['shape'] : '';
		$view_more_text = (isset($instance['view_more_text'])) ? $instance['view_more_text'] : '';
		$view_more_url = (isset($instance['view_more_url'])) ? $instance['view_more_url'] : '';
		
		$output .= wp_kses_post($args['before_widget']);
		
		if (isset($title) && $title != '') {
			$output .= wp_kses_post($args['before_title'] . esc_html($title) . $args['after_title']);
		}
		
		if (isset($gallery_id) && $gallery_id != '') {
			$output .= $this->get_output(esc_attr($gallery_id), esc_attr($columns), esc_attr($rows), esc_attr($shape), wp_kses_post($view_more_text), esc_url($view_more_url));
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
		
		$thumbs_per_row = array(1 => 1, 2 => 2, 3 => 3);
		$number_of_rows = array(1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5);
		$thumb_shapes = array(
			'landscape' => esc_html__('Landscape', 'maxgalleria'),
			'portrait' => esc_html__('Portrait', 'maxgalleria'),
			'square' => esc_html__('Square', 'maxgalleria')
		);
		
		// Form values
		$title = (isset($instance['title'])) ? $instance['title'] : esc_html__('Gallery Widget', 'maxgalleria');
		$gallery_id = (isset($instance['gallery_id'])) ? $instance['gallery_id'] : '';
		$columns = (isset($instance['columns'])) ? $instance['columns'] : '';
		$rows = (isset($instance['rows'])) ? $instance['rows'] : '';
		$shape = (isset($instance['shape'])) ? $instance['shape'] : '';
		$view_more_text = (isset($instance['view_more_text'])) ? $instance['view_more_text'] : esc_html__('View More', 'maxgalleria');
		$view_more_url = (isset($instance['view_more_url'])) ? $instance['view_more_url'] : '';
		
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
		$output .= '		<td>' . esc_html__('Thumbs/Row: ', 'maxgalleria') . '</td>';
		$output .= '		<td>';
		$output .= '			<select class="widefat" id="' . esc_attr($this->get_field_id('columns')) . '" name="' . esc_attr($this->get_field_name('columns')) . '">';
		$output .= '				<option value="">-- ' . esc_html__('Select Thumbs per Row', 'maxgalleria') . ' --</option>';
									foreach ($thumbs_per_row as $key => $name) {
										$selected = ($columns == $key) ? 'selected=selected' : '';
										$output .= '<option value="' . esc_attr($key) . '" ' . esc_attr($selected) . '>' . esc_attr($name) . '</option>';
									}
		$output .= '			</select>';
		$output .= '		</td>';
		$output .= '	</tr>';
		$output .= '	<tr>';
		$output .= '		<td>' . esc_html__('# of Rows: ', 'maxgalleria') . '</td>';
		$output .= '		<td>';
		$output .= '			<select class="widefat" id="' . esc_attr($this->get_field_id('rows')) . '" name="' . esc_attr($this->get_field_name('rows')) . '">';
		$output .= '				<option value="">-- ' . esc_html__('Select # of Rows', 'maxgalleria') . ' --</option>';
									foreach ($number_of_rows as $key => $name) {
										$selected = ($rows == $key) ? 'selected=selected' : '';
										$output .= '<option value="' . esc_attr($key) . '" ' . esc_attr($selected) . '>' . esc_attr($name) . '</option>';
									}
		$output .= '			</select>';
		$output .= '		</td>';
		$output .= '	</tr>';
		$output .= '	<tr>';
		$output .= '		<td>' . esc_html__('Thumb Shape: ', 'maxgalleria') . '</td>';
		$output .= '		<td>';
		$output .= '			<select class="widefat" id="' . esc_attr($this->get_field_id('shape')) . '" name="' . esc_attr($this->get_field_name('shape')) . '">';
		$output .= '				<option value="">-- ' . esc_html__('Select Thumb Shape', 'maxgalleria') . ' --</option>';
									foreach ($thumb_shapes as $key => $name) {
										$selected = ($shape == $key) ? 'selected=selected' : '';
										$output .= '<option value="' . esc_attr($key) . '" ' . esc_attr($selected) . '>' . esc_attr($name) . '</option>';
									}
		$output .= '			</select>';
		$output .= '		</td>';
		$output .= '	</tr>';
		$output .= '	<tr>';
		$output .= '		<td width="100">' . esc_html__('View More Text: ', 'maxgalleria') . '</td>';
		$output .= '		<td>';
		$output .= '			<input class="widefat" type="text" id="' . esc_attr($this->get_field_id('view_more_text')) . '" name="' . esc_attr($this->get_field_name('view_more_text')) . '" value="' . wp_kses_post($view_more_text) . '" />';
		$output .= '		</td>';
		$output .= '	</tr>';
		$output .= '	<tr>';
		$output .= '		<td>' . esc_html__('View More URL: ', 'maxgalleria') . '</td>';
		$output .= '		<td>';
		$output .= '			<input class="widefat" type="text" id="' . esc_attr($this->get_field_id('view_more_url')) . '" name="' . esc_attr($this->get_field_name('view_more_url')) . '" value="' . esc_url($view_more_url) . '" />';
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
		$instance['title'] = (isset($new_instance['title'])) ? sanitize_title($new_instance['title']) : '';
		$instance['gallery_id'] = (isset($new_instance['gallery_id'])) ? sanitize_text_field($new_instance['gallery_id']) : '';
		$instance['columns'] = (isset($new_instance['columns'])) ? sanitize_text_field($new_instance['columns']) : '';
		$instance['rows'] = (isset($new_instance['rows'])) ? sanitize_text_field($new_instance['rows']) : '';
		$instance['shape'] = (isset($new_instance['shape'])) ? sanitize_text_field($new_instance['shape']) : '';
		$instance['view_more_text'] = (isset($new_instance['view_more_text'])) ? wp_filter_post_kses($new_instance['view_more_text']) : '';
		$instance['view_more_url'] = (isset($new_instance['view_more_url'])) ? esc_url_raw($new_instance['view_more_url']) : '';
		
		return $instance;
	}
	
	private function get_output($gallery_id, $columns, $rows, $shape, $view_more_text, $view_more_url) {
		$output = '';
		$gallery = get_post($gallery_id);
		
		if (isset($gallery) && $gallery->post_status == 'publish') {
			global $maxgalleria;
			
			// Check to use defaults
			if ($columns == '') $columns = 3;
			if ($rows == '') $rows = 3;
			if ($shape == '') $shape = 'square';
			if ($view_more_text == '') $view_more_text = esc_html__('View More', 'maxgalleria');
			if ($view_more_url == '') $view_more_url = get_permalink($gallery->ID);
			
			// Set columns class
			$columns_class = '';
			if ($columns == 1) $columns_class = 'mg-onecol';
			if ($columns == 2) $columns_class = 'mg-twocol';
			if ($columns == 3) $columns_class = 'mg-threecol';
			
			// Determine how many attachments to retrieve
			$number_attachments = $columns * $rows;
			
			// Get gallery attachments
			$args = array(
				'post_parent' => $gallery->ID,
				'post_type' => 'attachment',
				'orderby' => 'menu_order',
				'order' => 'asc',
				'numberposts' => $number_attachments
			);
			$attachments = get_posts($args);
			
			// Add the stylesheet
			$widget_stylesheet = apply_filters(MAXGALLERIA_FILTER_GALLERY_WIDGET_STYLESHEET, MAXGALLERIA_PLUGIN_URL . '/widgets/gallery-widget.css');
			wp_enqueue_style('maxgalleria-gallery-widget', $widget_stylesheet);
			
			// Build the output
			$output .= apply_filters(MAXGALLERIA_FILTER_GALLERY_WIDGET_BEFORE_OUTPUT, '', $gallery->ID);
			$output .= '<div id="maxgalleria-gallery-widget-' . esc_attr($gallery->ID) . '" class="mg-gallery-widget">';
			$output .= '	<div class="mg-thumbs ' . esc_attr($columns_class) . '">';
			$output .= '		<ul>';

			foreach ($attachments as $attachment) {
				$excluded = get_post_meta($attachment->ID, 'maxgallery_attachment_image_exclude', true);
				if (!$excluded) {
					$title = $attachment->post_title;
					$caption = $attachment->post_excerpt;
					$alt = get_post_meta($attachment->ID, '_wp_attachment_image_alt', true);
					
					$thumb_image = $maxgalleria->image_gallery->get_thumb_image($attachment, $shape, $columns);
					$thumb_image_element = '<img src="' . esc_url($thumb_image['url']) . '" width="' . esc_attr($thumb_image['width']) . '" height="' . esc_attr($thumb_image['height']) . '" alt="' . esc_attr($alt) . '" title="' . esc_attr($title) . '" />';
					
					$output .= '	<li>';
					$output .= 			wp_kses_post(apply_filters(MAXGALLERIA_FILTER_GALLERY_WIDGET_BEFORE_THUMB, ''));
					$output .=			wp_kses_post(apply_filters(MAXGALLERIA_FILTER_GALLERY_WIDGET_THUMB, $thumb_image_element, esc_url($thumb_image['url']), esc_attr($alt), esc_html($title)));
					$output .= 			wp_kses_post(apply_filters(MAXGALLERIA_FILTER_GALLERY_WIDGET_AFTER_THUMB, ''));
					$output .= '	</li>';
				}
			}

			$output .= '		</ul>';
			$output .= '		<div class="clear"></div>';
			$output .= '	</div>';
			
			// View more
			$view_more = '<a href="' . esc_url($view_more_url) . '">' . wp_kses_post($view_more_text) . '</a>';
			$output .= wp_kses_post(apply_filters(MAXGALLERIA_FILTER_GALLERY_WIDGET_BEFORE_VIEW_MORE, '<p>'));
			$output .= wp_kses_post(apply_filters(MAXGALLERIA_FILTER_GALLERY_WIDGET_VIEW_MORE, $view_more, $view_more_url, $view_more_text));
			$output .= wp_kses_post(apply_filters(MAXGALLERIA_FILTER_GALLERY_WIDGET_AFTER_VIEW_MORE, '</p>'));
			
			$output .= '</div>';
			$output .= wp_kses_post(apply_filters(MAXGALLERIA_FILTER_GALLERY_WIDGET_AFTER_OUTPUT, '', esc_attr($gallery->ID)));
		}
		
		return apply_filters(MAXGALLERIA_FILTER_GALLERY_WIDGET_OUTPUT, $output, esc_attr($gallery->ID));
	}
}
?>