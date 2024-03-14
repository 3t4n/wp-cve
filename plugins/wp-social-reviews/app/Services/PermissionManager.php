<?php

namespace WPSocialReviews\App\Services;

class PermissionManager
{
	public static function getReadablePermissions()
	{
		return apply_filters('wpsocialreviews/readable_permissions', [
			'wpsn_full_access'         => [
				'title'   => __('Full Access', 'wp-social-reviews'),
				'depends' => [],
				'group'   => 'dashboard',
				'slug'    => 'wpsn_full_access'
			],
            'wpsn_manage_platforms'         => [
				'title'   => __('Manage Platforms', 'wp-social-reviews'),
				'depends' => [],
				'group'   => 'dashboard',
				'slug'    => 'wpsn_manage_platforms'
			],
			'wpsn_manage_reviews'         => [
				'title'   => __('Manage Reviews', 'wp-social-reviews'),
				'depends' => [],
				'group'   => 'dashboard',
				'slug'    => 'wpsn_manage_reviews'
			],
			'wpsn_manage_testimonials'         => [
				'title'   => __('Manage Testimonials', 'wp-social-reviews'),
				'depends' => [],
				'group'   => 'dashboard',
				'slug'    => 'wpsn_manage_testimonials'
			],
			'wpsn_manage_templates'        => [
				'title'   => __('Manage Templates', 'wp-social-reviews'),
				'depends' => [],
				'group'   => 'dashboard',
				'slug'    => 'wpsn_manage_templates'
			],
			'wpsn_manage_notification_popup'        => [
				'title'   => __('Manage Notification Popup', 'wp-social-reviews'),
				'depends' => [],
				'group'   => 'dashboard',
				'slug'    => 'wpsn_manage_notification_popup'
			],
			'wpsn_manage_chat_widgets'          => [
				'title'   => __('Manage Chat Widgets', 'wp-social-reviews'),
				'depends' => [],
				'group'   => 'dashboard',
				'slug'    => 'wpsn_manage_chat_widgets'
			],
			'wpsn_feeds_platforms_settings' => [
				'title'   => __('Manage Feeds Platforms Settings', 'wp-social-reviews'),
				'depends' => [],
				'group'   => 'dashboard',
				'slug'    => 'wpsn_feeds_platforms_settings'
			],
			'wpsn_reviews_platforms_settings' => [
				'title'   => __('Manage Reviews Platforms Settings', 'wp-social-reviews'),
				'depends' => [],
				'group'   => 'dashboard',
				'slug'    => 'wpsn_reviews_platforms_settings'
			],
			'wpsn_shoppable_settings' => [
				'title'   => __('Manage Shoppable Settings', 'wp-social-reviews'),
				'depends' => [],
				'group'   => 'dashboard',
				'slug'    => 'wpsn_shoppable_settings'
			],
			'wpsn_translation_settings' => [
				'title'   => __('Manage Translation Settings', 'wp-social-reviews'),
				'depends' => [],
				'group'   => 'dashboard',
				'slug'    => 'wpsn_translation_settings'
			],
			'wpsn_license_settings' => [
				'title'   => __('Manage License Settings', 'wp-social-reviews'),
				'depends' => [],
				'group'   => 'dashboard',
				'slug'    => 'wpsn_license_settings'
			],
		]);
	}

	public static function pluginPermissions()
	{
		return apply_filters('wpsocialreviews/plugin_permissions', [
            'wpsn_full_access',
			'wpsn_manage_platforms',
			'wpsn_manage_reviews',
			'wpsn_manage_testimonials',
			'wpsn_manage_templates',
			'wpsn_manage_notification_popup',
			'wpsn_manage_chat_widgets',
			'wpsn_feeds_platforms_settings',
			'wpsn_reviews_platforms_settings',
			'wpsn_shoppable_settings',
			'wpsn_translation_settings',
			'wpsn_license_settings',
            'wpsn_feeds_advance_settings'
		]);
	}

	public static function attachPermissions($user, $permissions)
	{
		if (is_numeric($user)) {
			$user = get_user_by('ID', $user);
		}

		if (!$user) {
			return false;
		}

		if (user_can($user, 'manage_options')) {
			return $user;
		}

		$allPermissions = self::pluginPermissions();
		foreach ($allPermissions as $permission) {
			$user->remove_cap($permission);
		}

		$permissions = array_intersect($allPermissions, $permissions);


		foreach ($permissions as $permission) {
			$user->add_cap($permission);
		}

		return $user;
	}

	public static function getUserPermissions($user = false)
	{
		if (is_numeric($user)) {
			$user = get_user_by('ID', $user);
		}

		if (!$user) {
			return [];
		}

		$pluginPermission = self::pluginPermissions();

		if ($user->has_cap('manage_options')) {
			$pluginPermission[] = 'administrator';
			$permissions = $pluginPermission;
		} else {
			$permissions = array_values(array_intersect(array_keys($user->allcaps), $pluginPermission));
		}

		$permissions = apply_filters('wpsocialreviews/user_permissions', $permissions, $user);
		return array_values($permissions);
	}

	public static function currentUserPermissions($cached = true)
	{
		static $permissions;

		if ($permissions && $cached) {
			return $permissions;
		}

		$permissions = self::getUserPermissions(get_current_user_id());

		return $permissions;
	}

	public static function currentUserCan($permission)
	{
		if (current_user_can('manage_options') || 'wpsn_full_access' === $permission) {
			return true;
		}

		if (defined('WPSOCIALREVIEWS_PRO')) {
			return current_user_can($permission);
		}

		return false;
	}
}
