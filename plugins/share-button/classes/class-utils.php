<?php namespace MBSocial;
defined('ABSPATH') or die('No direct access permitted');

use \MaxButtons\maxField as maxField;


class Utils
{

  public static function imageUploader($args)
  {

    /* Standard function for Image Uploader. Standard compatible w/ MaxButtons Icon.php Image Uploader. Should be unified later.
    */
			$default_args = array(
					'id' => 'icon', // id of the field
					'hide' => false, // hide the image upload button
          'show_image_data' => true,  // show image name / title etc in interface.
					'show_hover_link' => true, // show a link to include a hover image
					'conditional' => false, // show this field only on conditional
					'label' => __('Image', 'maxbuttons-pro'), // the label
          'data' => array(), // where to find the data of the image
          'admin' => null,  // the admin object to add the fields to
			);

			$args = wp_parse_args($args, $default_args);

	     $admin = $args['admin'];

       if (! is_object($admin))
       {
         return false;
       }

			$id = $args['id'];
      $data = $args['data'];

			$icon_id =	isset($data[$id . '_id']) ? $data[$id . '_id'] : false;
			$icon_url = isset($data[$id . '_url']) ? $data[$id . '_url'] : false;
			$icon_alt = isset($data[$id . '_alt']) ? $data[$id . '_alt'] : false;
			$icon_size = isset($data[$id . '_size']) ? $data[$id . '_size'] : false;


		//**Icon section.
			$iconid = new maxField('hidden');
			$iconid->id = $id . '_id';
			$iconid->name = $iconid->id;
			$iconid->value = $icon_id;

			$admin->addField($iconid, '', '');

			$iconurl = new maxField('hidden');
			$iconurl->id = $id . '_url';
			$iconurl->name = $iconurl->id;
			$iconurl->value = $icon_url;
			$admin->addField($iconurl, '','');

			$iconsize = new maxField('hidden');
			$iconsize->id = $id . '_size';
			$iconsize->name = $iconsize->id;
			$iconsize->value = $icon_size;
			$admin->addField($iconsize, '','');

			$iconpreview = new maxField('generic');
			$iconpreview->id = $id . '_option_preview';
			if ($args['hide'])
				$iconpreview->main_class = 'option hidden';
			$iconpreview->label =$args['label'];
			$iconpreview->name = $iconpreview->id;
			$iconpreview->start_conditional = $args['conditional'];
		//	$iconpreview->main_class  = 'option icon_preview';

    if ($icon_id > 0)
    {
      $data = get_post($icon_id);

      if (! is_null($data))
      {
        $icon_title = $data->post_title;
        $icon_alt = get_post_meta( $icon_id, '_wp_attachment_image_alt', true );

        $data = wp_get_attachment_image_src($icon_id, $icon_size);

        $filename = basename($data[0]);
        $width = $data[1];
        $height = $data[2];
      }
      else {
        $icon_id = -1; // error state
        $icon_url = '';
      }
    }
    else {
        $filename = $icon_alt = $icon_title = '';
    }

			$content = '';
			$content .= '<span class="image_' . $id . '_preview the_icon_preview non-fa">';
			if (isset($icon_url) && $icon_url != '')
			{
				$content .= '<img src="' . $icon_url . '">';
			}
      $content .= '<span class="remove_icon dashicons dashicons-dismiss" data-key="' . $id . '"></span></span>';


      if ($args['show_image_data'])
      {
				$content .= "<span class='" . $id . "_data the_icon_data'>";
				$content .= "<label>" .  __("File Name","maxbuttons-pro") . "</label>
									<span class='filename'>" .  $filename . "</span>
									<label>" .  __("Alt","maxbuttons-pro") . "</label>
									<span class='alt'>" . $icon_alt . "</span>
									<label>" .  __("Title","maxbuttons-pro") . "</label>
									<span class='atttitle'>" .  $icon_title . "</span>
									</span>";
      }

			$iconpreview->content = $content;
			$admin->addField($iconpreview, 'start', 'end');

			$image_button = new maxField('generic');
			$image_button->label = '&nbsp;';
			$image_button->id = $id . '_image_button';
			$image_button->name = $image_button->id;
			$image_button->start_conditional = $args['conditional'] ;

			if ($args['hide'])
				$image_button->main_class = 'option hidden';

			$image_button->content = '<input type="button" class="media_upload button" id="image_' . $id . '_button" name="image_' . $id . '_button"
			data-uploader_title="' . __('Select an Image', 'maxbuttons-pro') . '"
			data-uploader_button_text="' .  __('Use Image', 'maxbuttons-pro') . '"
      data-upload-id="' . $id . '"
			 value="' .  __('Select...', 'maxbuttons-pro') . '" />';

			 $end = $args['show_hover_link'] ? '' : 'end';
			 $admin->addField($image_button, 'start', $end);

			 if ($args['show_hover_link'])
		 	 {
			 	$hover_link = new maxField('generic');
				$hover_link->id = $id . '_hover_link';
				$hover_link->name = $hover_link->id;
			 	$hover_link->content = "<p class='add_hover_image'>
			 								 <a href='javascript:void(0);' id='add_hover_image'>" . __("Add a different Hover Image", 'maxbuttons-pro') . "</a>
			 							 </p>";

			 						 $admin->addField($hover_link, '', 'end');
		   }

  }


} // class





















?>
