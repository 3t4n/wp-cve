<?php

namespace FSPoster\App\Pages\Share\Controllers;

class Action
{
	public function get_share ()
	{
		return [
			'message'  => '',
			'link'     => '',
			'imageURL' => '',
			'post_id'  => 0,
			'imageId'  => 0,
			'posts'    => []
		];
	}
}
