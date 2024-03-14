<?php

namespace WilokeEmailCreator\Templates\Model;

use WilokeEmailCreator\Illuminate\Prefix\AutoPrefix;

class TemplateModel
{
	public static function IsTemplateExists(int $templateId): bool
	{
		global $wpdb;
		$sql = $wpdb->prepare(
			"SELECT ID FROM {$wpdb->posts} as posts WHERE posts.ID = %d AND posts.post_type = %s",
			$templateId,
			AutoPrefix::namePrefix('templates')
		);
		$postID = (int)$wpdb->get_var($sql);
		return !empty($postID);
	}

	public static function getListIdTemplateWishTypeEmail(string $typeEmail): array
	{
		global $wpdb;
		$aIDs = [];
		$sql = $wpdb->prepare(
			"SELECT posts.ID FROM {$wpdb->posts} as posts join {$wpdb->postmeta} as postmeta on posts.ID = postmeta.post_id WHERE postmeta.meta_value = %s AND postmeta.meta_key = %s",
			$typeEmail,
			AutoPrefix::namePrefix('topicEmailType')
		);
		$aQuery = $wpdb->get_results($sql,'ARRAY_A');
		if (!empty($aQuery)){
			foreach ($aQuery as $aPost){
				$aIDs[]= (int) $aPost['ID'];
			}
		}
		return $aIDs;
	}
}
