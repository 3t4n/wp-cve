<?php

namespace ImageComply;

class MediaLibrary
{

	public $statusToSVG = array(
    'queued' => '<svg style="width:24px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M480 256A224 224 0 1 1 32 256a224 224 0 1 1 448 0zM0 256a256 256 0 1 0 512 0A256 256 0 1 0 0 256zM240 112V256c0 5.3 2.7 10.3 7.1 13.3l96 64c7.4 4.9 17.3 2.9 22.2-4.4s2.9-17.3-4.4-22.2L272 247.4V112c0-8.8-7.2-16-16-16s-16 7.2-16 16z"/></svg>',
    'complete' => '<svg style="width:24px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M256 32a224 224 0 1 1 0 448 224 224 0 1 1 0-448zm0 480A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM363.3 203.3c6.2-6.2 6.2-16.4 0-22.6s-16.4-6.2-22.6 0L224 297.4l-52.7-52.7c-6.2-6.2-16.4-6.2-22.6 0s-6.2 16.4 0 22.6l64 64c6.2 6.2 16.4 6.2 22.6 0l128-128z"/></svg>',
    "complete-pro" => '<svg style="width:24px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M256 0c36.8 0 68.8 20.7 84.9 51.1C373.8 41 411 49 437 75s34 63.3 23.9 96.1C491.3 187.2 512 219.2 512 256s-20.7 68.8-51.1 84.9C471 373.8 463 411 437 437s-63.3 34-96.1 23.9C324.8 491.3 292.8 512 256 512s-68.8-20.7-84.9-51.1C138.2 471 101 463 75 437s-34-63.3-23.9-96.1C20.7 324.8 0 292.8 0 256s20.7-68.8 51.1-84.9C41 138.2 49 101 75 75s63.3-34 96.1-23.9C187.2 20.7 219.2 0 256 0zM369 209c9.4-9.4 9.4-24.6 0-33.9s-24.6-9.4-33.9 0l-111 111-47-47c-9.4-9.4-24.6-9.4-33.9 0s-9.4 24.6 0 33.9l64 64c9.4 9.4 24.6 9.4 33.9 0L369 209z"/></svg>',
    "complete-manual" =>  '<svg style="width:26px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M128 128a96 96 0 1 1 192 0 96 96 0 1 1 -192 0zM269.7 336c80 0 145 64.3 146.3 144H32c1.2-79.7 66.2-144 146.3-144h91.4zM224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3zm457-116.7c6.2-6.2 6.2-16.4 0-22.6s-16.4-6.2-22.6 0L496 281.4l-52.7-52.7c-6.2-6.2-16.4-6.2-22.6 0s-6.2 16.4 0 22.6l64 64c6.2 6.2 16.4 6.2 22.6 0l128-128z"/></svg>',
    'incomplete' => '<svg style="width:24px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M256 32a224 224 0 1 1 0 448 224 224 0 1 1 0-448zm0 480A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM180.7 180.7c-6.2 6.2-6.2 16.4 0 22.6L233.4 256l-52.7 52.7c-6.2 6.2-6.2 16.4 0 22.6s16.4 6.2 22.6 0L256 278.6l52.7 52.7c6.2 6.2 16.4 6.2 22.6 0s6.2-16.4 0-22.6L278.6 256l52.7-52.7c6.2-6.2 6.2-16.4 0-22.6s-16.4-6.2-22.6 0L256 233.4l-52.7-52.7c-6.2-6.2-16.4-6.2-22.6 0z"/></svg>',
    'error' =>  '<svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M256 32a224 224 0 1 1 0 448 224 224 0 1 1 0-448zm0 480A256 256 0 1 0 256 0a256 256 0 1 0 0 512zm0-384c-8.8 0-16 7.2-16 16V272c0 8.8 7.2 16 16 16s16-7.2 16-16V144c0-8.8-7.2-16-16-16zm24 224a24 24 0 1 0 -48 0 24 24 0 1 0 48 0z"/></svg>',
    'requested' =>  '<svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M296 160c13.3 0 24-10.7 24-24v-8V112 64L480 208 320 352l0-48V288v-8c0-13.3-10.7-24-24-24h-8H192c-70.7 0-128 57.3-128 128c0 8.3 .7 16.1 2 23.2C47.9 383.7 32 350.1 32 304c0-79.5 64.5-144 144-144H288h8zm-8 144v16 32c0 12.6 7.4 24.1 19 29.2s25 3 34.4-5.4l160-144c6.7-6.1 10.6-14.7 10.6-23.8s-3.8-17.7-10.6-23.8l-160-144c-9.4-8.5-22.9-10.6-34.4-5.4s-19 16.6-19 29.2V96v16 16H256 176C78.8 128 0 206.8 0 304C0 417.3 81.5 467.9 100.2 478.1c2.5 1.4 5.3 1.9 8.1 1.9c10.9 0 19.7-8.9 19.7-19.7c0-7.5-4.3-14.4-9.8-19.5C108.8 431.9 96 414.4 96 384c0-53 43-96 96-96h64 32v16z"/></svg>',
  );

  public $statusToLabel = array(
    "complete-pro" => "Pro complete",
    "complete-manual" => "Manual Complete",
    "incomplete" => "Incomplete",
    "queued" => "Queued",
    "complete" => "Complete",
    "error" => "Error",
    "requested" => "Requested",
	);

	public function __construct()
	{
		// Hook into the 'manage_media_columns' filter to add the custom column
		add_filter('manage_media_columns', [$this, 'add_image_comply_columns']);

		// Hook into the 'manage_media_custom_column' action to display the data for the custom column
		$imagecomply_medialibrary_show_status = get_option('imagecomply_medialibrary_show_status', false);
		$imagecomply_medialibrary_show_alt_text = get_option('imagecomply_medialibrary_show_alt_text', false);

		$imagecomply_medialibrary_show_status = ($imagecomply_medialibrary_show_status && $imagecomply_medialibrary_show_status !== 'false') ? true : false;

		$imagecomply_medialibrary_show_alt_text = ($imagecomply_medialibrary_show_alt_text && $imagecomply_medialibrary_show_alt_text !== 'false') ? true : false;

		if($imagecomply_medialibrary_show_status) {
			add_action('manage_media_custom_column', [$this, 'display_image_comply_data'], 10, 2);
		}
		if($imagecomply_medialibrary_show_alt_text) {
			add_action('manage_media_custom_column', [$this, 'display_alt_text'], 10, 2);
		}
	}

	// Add the custom column
	public function add_image_comply_columns($columns)
	{

		$imagecomply_medialibrary_show_status = get_option('imagecomply_medialibrary_show_status', false);
		$imagecomply_medialibrary_show_alt_text = get_option('imagecomply_medialibrary_show_alt_text', false);

		$imagecomply_medialibrary_show_status = ($imagecomply_medialibrary_show_status && $imagecomply_medialibrary_show_status !== 'false') ? true : false;

		$imagecomply_medialibrary_show_alt_text = ($imagecomply_medialibrary_show_alt_text && $imagecomply_medialibrary_show_alt_text !== 'false') ? true : false;

		if($imagecomply_medialibrary_show_status) {
			$columns['imagecomply'] = 'ImageComply';
		}
		if($imagecomply_medialibrary_show_alt_text) {
			$columns['imagecomply_alt_text'] = 'Alt Text';
		}

		return $columns;
	}

	// Display data for the custom column
	public function display_image_comply_data($column_name, $attachment_id)
	{
		if ($column_name === 'imagecomply') {

			// Retrieve the serialized data from the post meta
			$status = get_post_meta($attachment_id, 'imagecomply_alt_text_status', true);

			//var_dump($data_array);

			if ($status) {
				$svg = $this->statusToSVG[$status] ?: $this->statusToSVG['incomplete'];
				$label = $this->statusToLabel[$status] ?: $this->statusToLabel['incomplete'];
			} else {
				$svg = $this->statusToSVG['incomplete'];
				$label = $this->statusToLabel['incomplete'];
			}

			echo wp_kses("<div class='imagecomply-status' style='display:flex;gap:10px;align-items:center;'>" . $svg . "<span class='status'>" . $label . "</span></div>", [
				'div' => [
					'class' => [],
				],
				'span' => [
					'class' => [],
				],
				'svg'  => [
					'xmlns'       => [],
					'fill'        => [],
					'viewbox'     => [],
					'role'        => [],
					'aria-hidden' => [],
					'focusable'   => [],
					'height'      => [],
					'width'       => [],
				],
				'path' => [
					'd'    => [],
					'fill' => [],
				],
			]);
		}
	}

	public function display_alt_text($column_name, $attachment_id) {
		if ($column_name === 'imagecomply_alt_text') {
			$alt_text = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);
			echo esc_html($alt_text);
		}
	}
}

// Instantiate the MediaLibrary class
new MediaLibrary();
