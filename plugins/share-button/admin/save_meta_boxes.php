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

	$collection->save_meta_boxes($post_id, $_POST);


}
