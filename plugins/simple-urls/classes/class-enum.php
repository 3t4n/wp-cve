<?php
/**
 * Declare class Enum
 *
 * @package Enum
 */

namespace LassoLite\Classes;

use LassoLite\Admin\Constant;

/**
 * Enum
 */
class Enum {
	const PAGE_DASHBOARD     = 'dashboard';
	const PAGE_OPPORTUNITIES = 'opportunities';
	const PAGE_IMPORT        = 'import';
	const PAGE_TABLES        = 'tables';
	const PAGE_URL_DETAILS   = 'url-details';
	const PAGE_GROUPS        = 'groups';
	const PAGE_GROUP_DETAIL  = 'group-detail';
	const PAGE_ONBOARDING    = 'onboarding';

	const PAGE_SETTINGS_GENERAL = 'settings-general';
	const PAGE_SETTINGS_DISPLAY = 'settings-display';
	const PAGE_SETTINGS_AMAZON  = 'settings-amazon';

	const LASSO_LITE_ACTIVE       = 'is_active_lasso_lite';
	const SWITCH_TO_NEW_UI        = 'is_lasso_live_switch_to_new_ui';
	const IS_VISITED_WELCOME_PAGE = 'is_visited_welcome_page';
	const RESET_WELCOME_PAGE      = 'reset-onboarding';
	const RESET_REQUEST_REVIEW    = 'reset-review';

	const LIMIT_ON_PAGE        = 10;
	const REWRITE_SLUG_DEFAULT = 'go';

	const THEME_CACTUS = 'Cactus';
	const THEME_CUTTER = 'Cutter';
	const THEME_FLOW   = 'Flow';
	const THEME_GEEK   = 'Geek';
	const THEME_LAB    = 'Lab';
	const THEME_LLAMA  = 'Llama';
	const THEME_MONEY  = 'Money';
	const THEME_SPLASH = 'Splash';

	const FOLLOW_ON_TWITTER = 'lasso_lite_follow_on_twitter';
	const SHARE_ON_TWITTER  = 'lasso_lite_share_on_twitter';

	const LEAVE_A_REVIEW        = 'lasso_lite_leave_a_review';
	const SETUP_AMZ_TRACKING_ID = 'lasso_lite_setup_amz_tracking_id';

	const TWITTER_URL       = 'https://twitter.com/lassowp';
	const TWITTER_SHARE_URL = 'https://twitter.com/intent/tweet?text=I%27m%20using%20@LassoWP%20to%20improve%20the%20conversions%20on%20my%20niche%20site.%20I%20also%20got%20a%20discount%20upgrading%20to%20Pro%20%F0%9F%98%8E';
	const LASSO_REVIEW_URL  = 'https://wordpress.org/plugins/simple-urls/#reviews';

	const SLUG_CLOAK_FOLLOW_TWITTER   = Constant::LASSO_POST_TYPE . '=follow-lassowp';
	const SLUG_CLOAK_SHARE_TWITTER    = Constant::LASSO_POST_TYPE . '=share-lassowp';
	const SLUG_CLOAK_LASSO_REVIEW_URL = Constant::LASSO_POST_TYPE . '=leave-a-review-lassowp';

	const SUPPORT_ENABLED             = 'support_enabled';
	const SUPPORT_ENABLED_TIME        = 'support_enabled_time';
	const USER_HASH                   = 'user_hash';
	const IS_SUBSCRIBE                = 'is_subscribe';
	const EMAIL_SUPPORT               = 'email_support';
	const IS_PRE_POPULATED_AMAZON_API = 'is_pre_populated_amazon_api';
	const CUSTOMER_FLOW_ENABLED       = 'customer_flow_enabled';

	const SUB_PAGE_GROUP_DETAIL = 'detail';
	const SUB_PAGE_GROUP_URLS   = 'urls';
	const SUB_PAGE_GROUP_ADD    = 'add';
}
