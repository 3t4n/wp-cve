<?php

namespace SiteSEO\Services\Metas;

if (! defined('ABSPATH')) {
	exit;
}

use SiteSEO\Helpers\Metas\SocialSettings;

class SocialMeta
{
	protected function getTypeSocial($meta){
		switch ($meta) {
			case '_siteseo_social_fb_title':
			case '_siteseo_social_fb_desc':
			case '_siteseo_social_fb_img':
			case '_siteseo_social_fb_img_attachment_id':
			case '_siteseo_social_fb_img_width':
			case '_siteseo_social_fb_img_height':
				return 'og';

			case '_siteseo_social_twitter_title':
			case '_siteseo_social_twitter_desc':
			case '_siteseo_social_twitter_img':
			case '_siteseo_social_twitter_img_attachment_id':
			case '_siteseo_social_twitter_img_width':
			case '_siteseo_social_twitter_img_height':
				return "twitter";
		}
	}
	protected function getKeySocial($meta){
		switch ($meta) {
			case '_siteseo_social_fb_title':
			case '_siteseo_social_twitter_title':
				return 'title';
			case '_siteseo_social_fb_desc':
			case '_siteseo_social_twitter_desc':
				return 'description';

			case '_siteseo_social_fb_img':
			case '_siteseo_social_twitter_img':
				return "image";
			case '_siteseo_social_fb_img_attachment_id':
			case '_siteseo_social_twitter_img_attachment_id':
				return "attachment_id";
			case '_siteseo_social_fb_img_width':
			case '_siteseo_social_twitter_img_width':
				return "image_width";
			case '_siteseo_social_fb_img_height':
			case '_siteseo_social_twitter_img_height':
				return "image_height";
		}
	}

	/**
	 *
	 * @param array $context
	 * @return string|null
	 */
	public function getValue($context)
	{
		$data = ["og" => [], "twitter" => []];

		$callback = 'get_post_meta';
		$id = null;
		if(isset($context['post'])){
			$id = $context['post']->ID;
		}
		else if(isset($context['term_id'])){
			$id = $context['term_id'];
			$callback = 'get_term_meta';
		}

		if($id === null){
			return $data;
		}

		$metas = SocialSettings::getMetaKeys($id);

		foreach ($metas as $key => $value) {
			$type = $this->getTypeSocial($value['key']);
			$result = $callback($id, $value['key'], true);
			$keySocial = $this->getKeySocial($value['key']);

			$data[$type][$keySocial] = $result;
		}

		return $data;
	}
}



