<?php
namespace MBSocial;
defined('ABSPATH') or die('No direct access permitted');

// defined $post_type and $post in function
use MaxButtons\maxButton as maxButton;
$button = MB()->getClass('button'); // this loads the maxblocks template files 

$collection_ids = collections::getCollections();

if (count($collection_ids) == 0)
	return; // no collection no meta

foreach ($collection_ids as $collection_id)
{
	$collection = new collection($collection_id);

	$content_blocks = $collection->do_meta_boxes($post_type, $post);  // see if we have anything to metabox.


	if (! is_array($content_blocks) || count($content_blocks) == 0)
		return;

	add_meta_box('mbsocial-box',
        __( 'MaxButtons Social Share', 'mbsocial' ),
        MBSocial()->admin()->namespaceit('render_meta_box'),
        null,
        'advanced',
        'default',
        array('content_blocks' => $content_blocks)
      );
}

function render_meta_box($post, $args)
{
	$content_blocks = isset($args['args']['content_blocks']) ? $args['args']['content_blocks'] : array();

	if ( ! is_array($content_blocks) || count($content_blocks) == 0)
		return;

	wp_nonce_field('save', 'mbsocial_save');
	wp_enqueue_style('mbsocial-global');


	foreach($content_blocks as $index => $item)
	{
		$icon = $item['icon'];
		$title = $item['title'];
		$content = $item['content'];

		echo "<p><strong> <span class='fa $icon'></span> $title </strong></p> <div class='meta_box_content'> $content </div> ";
	}

}
