<?php
/**
 * Copyright (c) 2016 Ultra Community (http://www.ultracommunity.com)
 */

namespace InvisibleReCaptcha\MchLib\WordPress;

abstract class CustomPostType
{
	public $PostId    = null;
	public $PostType  = null;
	public $PostSlug  = null;
	public $PostTitle = null;

	public abstract function getAttributes();

	public function __construct($postType, \WP_Post $wpPost = null)
	{
		$this->PostType = $postType;

		if(!empty($wpPost->ID))
		{
			$this->PostId    = $wpPost->ID;
			$this->PostSlug  = $wpPost->post_name;
			$this->PostTitle = $wpPost->post_title;
		}

	}

}