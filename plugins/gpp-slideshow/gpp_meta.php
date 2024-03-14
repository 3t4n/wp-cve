<?php

/*-----------------------------------------------------------------------------------*/
/* Create custom meta boxes for the new post type */
/*-----------------------------------------------------------------------------------*/

$prefix = 'gpp_gallery_';

$gpp_gallery_meta_boxes = array();

// first meta box
$gpp_gallery_meta_boxes[] = array(
	'id' => 'details',
	'title' => 'Gallery',
	'pages' => array('gallery'),
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array(
			'name' => 'Images',
			'desc' => 'Click the button to select images. After images appear below, click on them to edit the gallery.',
			'id' => $prefix . 'images',
			'type' => 'image', // image upload
			'std' => ''
		),
		array(
			'name' => '',
			'desc' => 'Briefly describe this gallery.',
			'id' => $prefix . 'hiddenids',
			'type' => 'hidden', // text area
			'std' => ''
		),
		array(
			'name' => 'Description',
			'desc' => 'Briefly describe this gallery.',
			'id' => $prefix . 'description',
			'type' => 'textarea', // text area
			'std' => ''
		)

//		array(
//		'name' => 'Price Group',
//		'desc' => 'If you plan on selling prints of the photos in this gallery, select a price group from the above options.',
//		'id' => $prefix . 'price-group',
//		'type' => 'select', // select box
//		'std' => '',
//		'options' => array('not-for-sale'=>'Not For Sale','price-group-1'=>'Price Group 1','price-group-2'=>'Price Group 2','price-group-3'=>'Price Group 3')
//		)
	)
);


/*-----------------------------------------------------------------------------------*/
/* DO NOT EDIT BELOW THIS LINE */
/*-----------------------------------------------------------------------------------*/

foreach ($gpp_gallery_meta_boxes as $gpp_gallery_meta_box) {
	$my_box = new gpp_gallery_meta_box($gpp_gallery_meta_box);
}

class gpp_gallery_meta_box {

	protected $_meta_box;

	// create meta box based on given data
	function __construct($gpp_gallery_meta_box) {
		if (!is_admin()) return;

		$this->_meta_box = $gpp_gallery_meta_box;

		add_action('admin_menu', array(&$this, 'add'));

		add_action('save_post', array(&$this, 'save'));
	}

	/// Add meta box for multiple post types
	function add() {
		foreach ($this->_meta_box['pages'] as $page) {
			add_meta_box($this->_meta_box['id'], $this->_meta_box['title'], array(&$this, 'show'), $page, $this->_meta_box['context'], $this->_meta_box['priority']);
		}
	}

	// Callback function to show fields in meta box
	function show() {
		global $post;

$imgmeta = get_post_meta($post->ID, 'gpp_gallery_hiddenids', true);

if( isset( $imgmeta ) && $imgmeta != "" ) {
	$arrImages = explode( ',', $imgmeta );
} else {
	$arrImages = get_children('post_type=attachment&post_mime_type=image&post_parent='.$post->ID.'&order=DESC&orderby=ID' );
}
		// Use nonce for verification
		echo '<input type="hidden" name="wp_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';

		echo '<table class="form-table">';

		foreach ($this->_meta_box['fields'] as $field) {

			// get current post meta data
			$meta = get_post_meta($post->ID, $field['id'], true);

			echo '<tr>',
					'<th style="width:20%"><label for="', $field['id'], '">', $field['name'], '</label></th>',
					'<td>';
			switch ($field['type']) {
				case 'text':
					echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" style="width:97%" />',
						'<p class="description">', $field['desc'], '</p>';
					break;
				case 'hidden':
					echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" style="width:97%;display:none;" />';
					break;

				case 'textarea':
					echo '<textarea name="', $field['id'], '" id="', $field['id'], '" cols="60" rows="4" style="width:97%">', $meta ? $meta : $field['std'], '</textarea>',
						'<p class="description">', $field['desc'], '</p>';
					break;
				case 'date':
					echo '<input type="text" name="', $field['id'], '" id="datepicker" value="', $meta ? $meta : $field['std'], '" size="30" style="width:33%;margin-right:10px;" />',
						'<p class="description">', $field['desc'], '</p>';
					break;
				case 'select':
					echo '<select name="', $field['id'], '" id="', $field['id'], '">';
					foreach ($field['options'] as $option) {
						echo '<option', $meta == $option ? ' selected="selected"' : '', '>', $option, '</option>';
					}
					echo '</select>',
						'<p class="description">', $field['desc'], '</p>';
					break;
				case 'radio':
					foreach ($field['options'] as $option) {
						echo '<input type="radio" name="', $field['id'], '" value="', $option['value'], '"', $meta == $option['value'] ? ' checked="checked"' : '', ' />', $option['name'];
					}
					break;
				case 'checkbox':
					echo '<input type="checkbox" name="', $field['id'], '" id="', $field['id'], '"', $meta ? ' checked="checked"' : '', ' />';
					break;
				case 'image':
					echo $meta ? "<img src=\"$meta\" width=\"150\" height=\"100\" /><br />$meta<br />" : '', '<a href="#" id="', $field['id'], '_button" class="button">Upload Images</a>',
						'&nbsp;&nbsp;&nbsp;&nbsp;<span class="description">', $field['desc'], '</span>';
					//echo '<input id="upload_image" type="text" size="36" name="upload_image" value="" />';
					echo '<div id="gallerythumbs" style="margin:10px 0;">';
					//print_r($arrImages);
					foreach( $arrImages as $image ) {
						if( isset( $imgmeta ) && $imgmeta != "" ) {
							$src = wp_get_attachment_image_src( $image, 'thumbnail' );
						} else {
							$src = wp_get_attachment_image_src( $image->ID, 'thumbnail' );
						}

						echo '<img class="eachthumbs" src="'. $src[0] .'" style="cursor:pointer;height:60px;width:auto;;margin:5px 5px 0 0;"/>';
					}
					echo '</div>';
					break;
			}
			echo 	'<td>',
				'</tr>';
		}

		echo '</table>';
	}

	// Save data from meta box
	function save($post_id) {

 		$real_post_id = isset($_POST['post_ID']) ? $_POST['post_ID'] : NULL ;
		// verify nonce
		if( isset( $_POST['wp_meta_box_nonce'] ) ) {
		    if ( !wp_verify_nonce( $_POST['wp_meta_box_nonce'], basename(__FILE__) ) )
		        return $post_id;
		} else { return $post_id; }

		// check autosave
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $post_id;
		}

		// check permissions
		if ('page' == $_POST['post_type']) {
			if (!current_user_can('edit_page', $post_id)) {
				return $post_id;
			}
		} elseif (!current_user_can('edit_post', $post_id)) {
			return $post_id;
		}

		foreach ($this->_meta_box['fields'] as $field) {
			$name = $field['id'];

			$old = get_post_meta($real_post_id, $name, true);

			if( isset( $_POST[$field['id']] ) ) {
				$new = $_POST[$field['id']];

				if ($new && $new != $old) {
					update_post_meta($real_post_id, $name, $new);
				}
			}
		}
	}

}