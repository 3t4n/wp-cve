<?php
/**
 * Declare class Setting_Enum
 *
 * @package Setting_Enum
 */

namespace LassoLite\Classes;

/**
 * MetaKeyEnum
 */
class Setting_Enum {
	const PRETTY_LINK_SLUG         = 'pretty-link';
	const THIRSTYLINK_SLUG         = 'thirstylink';
	const SURL_SLUG                = 'surl';
	const EARNIST_SLUG             = 'earnist';
	const AFFILIATE_URL_SLUG       = 'affiliate_url';
	const AAWP_SLUG                = 'aawp';
	const EASYAZON_SLUG            = 'easyazon';
	const EASY_AFFILIATE_LINK_SLUG = 'easy_affiliate_link';
	const AMA_LINKS_PRO_SLUG       = 'amalinkspro';
	const LASSO_PRO_SLUG           = 'lasso-urls';
	const RECREATE_TABLE_TIME      = 'recreate_table_time';
	const NEXT_TIME_RECREATE_TABLE = 'next_time_recreate_table';

	const SUPPORT_IMPORT_PLUGINS = array(
		self::PRETTY_LINK_SLUG         => 'Pretty Links',
		self::THIRSTYLINK_SLUG         => 'Thirsty Affiliates',
		self::EARNIST_SLUG             => 'Earnist',
		self::AFFILIATE_URL_SLUG       => 'Affiliate URLs',
		self::AAWP_SLUG                => 'AAWP',
		self::EASYAZON_SLUG            => 'EasyAzon',
		self::EASY_AFFILIATE_LINK_SLUG => 'Easy Affiliate Links',
		self::AMA_LINKS_PRO_SLUG       => 'AmaLinks Pro',
		self::LASSO_PRO_SLUG           => 'Lasso Pro',
	);
}
