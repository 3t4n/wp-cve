<?php class EModal_View_Modal extends EModal_View {
	public function output()
	{
		extract($this->values);
		$classes = implode(' ', apply_filters('emodal_modal_class_attr', array(/*'modal',*/ 'emodal', 'theme-'.$modal['theme_id']), $modal));
		$data = apply_filters('emodal_modal_data_attr', $modal);
		$output = 
		'<div '
			.'id="eModal-'. esc_attr($modal['id']) .'" '
			.'class="'. esc_attr($classes) .'" '
			.'data-emodal="'. esc_attr(json_encode($data)) .'"'
		.'>';
		$output .= apply_filters('emodal_modal_inner', '', $modal);
		$output .= '</div>';
		return $output;
	}
    public function render()
    {
    	$this->pre_render();
    	echo $this->output();
    	$this->post_render();
    }
}
add_filter('emodal_modal_class_attr', 'emodal_modal_class_attr', 5, 2);
function emodal_modal_class_attr($classes, $modal)
{
	if(in_array($modal['meta']['display']['size'], array('normal','nano','tiny','small','medium','large','x-large')))
	{
		$classes[] = 'responsive';
		$classes[] = $modal['meta']['display']['size'];
	}
	elseif($modal['meta']['display']['size'] == 'custom')
	{
		$classes[] = 'custom';
	}
	return $classes;
}

add_filter('emodal_modal_data_attr', 'emodal_modal_data_attr', 1000);
function emodal_modal_data_attr($modal)
{
	unset(
		$modal['name'],
		$modal['title'],
		$modal['content'],
		$modal['is_sitewide'],
		$modal['is_system'],
		$modal['is_trash'],
		$modal['created'],
		$modal['modified']
	);
	return $modal;
}

add_filter('emodal_modal_inner', 'emodal_modal_inner_title', 5, 2);
function emodal_modal_inner_title($output, $modal)
{
	$output .= apply_filters('emodal_before_modal_title', '', $modal);
	if($modal['title'] != '')
		$output .= '<div class="'. esc_attr( apply_filters('emodal_modal_title_class_attr', 'emodal-title', $modal) ) .'">'. esc_html( apply_filters('emodal_modal_title', $modal['title'], $modal) ) .'</div>';
	$output .= apply_filters('emodal_after_modal_title', '', $modal);
	return $output;
}

add_filter('emodal_modal_inner', 'emodal_modal_inner_content', 10, 2);
function emodal_modal_inner_content($output, $modal)
{
	$output .= apply_filters('emodal_before_modal_content', '', $modal);
	$output .= '<div class="'. esc_attr( apply_filters('emodal_modal_content_class_attr', 'emodal-content', $modal) ) .'">'. apply_filters('emodal_modal_content', $modal['content'], $modal) .'</div>';
	$output .= apply_filters('emodal_after_modal_content', '', $modal);
	return $output;
}

add_filter('emodal_modal_inner', 'emodal_modal_inner_close', 15, 2);
function emodal_modal_inner_close($output, $modal)
{
	$output .= apply_filters('emodal_before_modal_close', '', $modal);
	$output .= '<a class="'. esc_attr( apply_filters('emodal_modal_close_class_attr', 'emodal-close', $modal) ) .'">'. apply_filters('emodal_modal_close', __("&#215;", 'easy-modal' ), $modal) .'</a>';
	$output .= apply_filters('emodal_after_modal_close', '', $modal);
	return $output;
}