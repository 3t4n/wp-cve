<?php

namespace WilokeEmailCreator\Templates\Shared;

use WilokeEmailCreator\Illuminate\Prefix\AutoPrefix;
use WP_Query;

trait TraitGetPostsTemplates
{
	public function getTemplatesOfEmailType(array $aRawArgs): array
	{
		$aArgs = wp_parse_args($aRawArgs, [
			'posts_per_page' => 100,
			'post_type'      => AutoPrefix::namePrefix('templates'),
			'post_status'    => ['publish']
		]);
		$aIds = [];
		$aFeIds = [];
		$aTitles = [];
		$oQuery = new WP_Query($aArgs);
		if ($oQuery->have_posts()) :
			while ($oQuery->have_posts()) : $oQuery->the_post();
				$aIds[] = get_the_ID();
				$aFeIds[] = get_post_meta(get_the_ID(), AutoPrefix::namePrefix('feId'), true);
				$aTitles []= get_the_title();
			endwhile;
		endif;
		wp_reset_postdata();
		return [
			'isExist' => !empty($aIds),
			'ids'     => implode(',', $aIds),
			'feIds'   => implode(',', $aFeIds),
			'titles'  => implode(',', $aTitles)
		];
	}
}
