<?php

namespace SiteSEO\Actions\Admin;

if (! defined('ABSPATH')) {
	exit;
}

use SiteSEO\Core\Hooks\ExecuteHooks;

class ModuleMetabox implements ExecuteHooks {
	
	/**
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function hooks(){
		add_action('admin_enqueue_scripts', [$this, 'enqueue']);
		add_action('init', [$this, 'enqueue']);

		if (current_user_can(siteseo_capability('edit_posts'))) {
			add_action('wp_enqueue_scripts', [$this, 'enqueueFrontend']);
		}
	}

	/**
	 * @since 1.0.0
	 *
	 * @return void
	 *
	 * @param mixed $argsLocalize
	 */
	protected function enqueueModule($argsLocalize = []){
		
		if(! siteseo_get_service('EnqueueModuleMetabox')->canEnqueue()){
			return;
		}

		// AMP compatibility
		if( function_exists( 'amp_is_request' ) && amp_is_request() ){
			return;
		}

		// Bricks builder compatibility
		if(function_exists('bricks_is_builder_main') && bricks_is_builder_main() === false){
			return;
		}

		$isGutenberg = false;
		if(function_exists('get_current_screen')){
			$currentScreen = get_current_screen();
			if($currentScreen && method_exists($currentScreen, 'is_block_editor')){
				$isGutenberg = true === $currentScreen->is_block_editor();
			}
		}

		$dependencies = ['jquery-ui-datepicker'];
		if ($isGutenberg) {
			$dependencies = array_merge($dependencies, []);
		}
		wp_enqueue_script('siteseo-metabox', SITESEO_URL_PUBLIC . '/metaboxe.js', $dependencies, SITESEO_VERSION, true);
		$value = wp_create_nonce('siteseo_rest');

		$tags = siteseo_get_service('TagsToString')->getTagsAvailable([
			'without_classes' => [
				'\SiteSEO\Tags\PostThumbnailUrlHeight',
				'\SiteSEO\Tags\PostThumbnailUrlWidth',
			],
			'without_classes_pos' => ['\SiteSEO\Tags\Schema', '\SiteSEOPro\Tags\Schema']
		]);
		
		$getLocale = get_locale();
		if (!empty($getLocale)) {
			$locale = substr($getLocale, 0, 2);
			$country_code = substr($getLocale, -2);
		} else {
			$locale	   = 'en';
			$country_code = 'US';
		}

		$settingsAdvanced = siteseo_get_service('AdvancedOption');
		$user = wp_get_current_user();
		$roles = ( array ) $user->roles;

		$postId = get_the_ID() ? get_the_ID() : null;
		$postType = null;
		if($postId){
			$postType = get_post_type($postId);
		}
		
		$data_attr = [];
		$args = array_merge([
			'SITESEO_URL_PUBLIC'			=> SITESEO_URL_PUBLIC,
			'SITESEO_URL_ASSETS'			=> SITESEO_ASSETS_DIR,
			'SITENAME'				=> get_bloginfo('name'),
			'SITEURL'				=> site_url(),
			'ADMIN_URL_TITLES'			=> admin_url('admin.php?page=siteseo-titles#tab=tab_siteseo_titles_single'),
			'ADMIN_META_URL'			=> admin_url('admin.php?page=siteseo-metabox-wizard'),
			'ADMIN_AJAX'				=> admin_url('admin-ajax.php'),
			'TAGS'					=> array_values($tags),
			'REST_URL'				=> rest_url(),
			'NONCE'					=> wp_create_nonce('wp_rest'),
			'METABOX_NONCE'				=> wp_create_nonce('siteseo_metabox_nonce'),
			'POST_ID'				=> $postId,
			'POST_TYPE'				=> $postType,
			'IS_GUTENBERG'				=> apply_filters('siteseo_module_metabox_is_gutenberg', $isGutenberg),
			'SELECTOR_GUTENBERG'			=> apply_filters('siteseo_module_metabox_selector_gutenberg', '.edit-post-header .edit-post-header-toolbar__left'),
			'TOGGLE_MOBILE_PREVIEW'			=> apply_filters('siteseo_toggle_mobile_preview', 1),
			'GOOGLE_SUGGEST'			=> [
				'ACTIVE' => apply_filters('siteseo_ui_metabox_google_suggest', false),
				'LOCALE' => $locale,
				'COUNTRY_CODE' => $country_code,
			],
			'USER_ROLES'			=> array_values($roles),
			'ROLES_BLOCKED'			=> [
				'GLOBAL' => $settingsAdvanced->getSecurityMetaboxRole(),
				'CONTENT_ANALYSIS' => $settingsAdvanced->getSecurityMetaboxRoleContentAnalysis()
			],
			'OPTIONS'				=> [
				'AI' => siteseo_get_service('ToggleOption')->getToggleAi() === "1" ? true : false,
			],
			'TABS' => [
				'SCHEMAS' => apply_filters('siteseo_active_schemas_manual_universal_metabox', false)
			],
			'SUB_TABS'				=> [
				'GOOGLE_NEWS' => apply_filters('siteseo_active_google_news', false),
				'VIDEO_SITEMAP' => apply_filters('siteseo_active_video_sitemap', false),
				'INSPECT_URL' => apply_filters('siteseo_active_inspect_url', false),
				'INTERNAL_LINKING' => apply_filters('siteseo_active_internal_linking', false),
				'SCHEMA_MANUAL' =>  apply_filters('siteseo_active_schemas', false)
			],
			'FAVICON'				=> get_site_icon_url(32),
			'BEACON_SVG'				=> apply_filters('siteseo_beacon_svg', SITESEO_ASSETS_DIR.'/img/white-logo.svg'),
			'DATA_ATTR'				=> $data_attr, // TODO move in function
		], $argsLocalize);

		wp_localize_script('siteseo-metabox', 'SITESEO_DATA', $args);
		wp_localize_script('siteseo-metabox', 'SITESEO_I18N', siteseo_get_service('I18nUniversalMetabox')->getTranslations());
	}

	/**
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function enqueueFrontend(){
		$this->enqueueModule(['POST_ID' => get_the_ID()]);
	}

	/**
	 * @since 1.0.0
	 *
	 * @param string $page
	 *
	 * @return void
	 */
	public function enqueue($page){
		if (! in_array($page, ['post.php'], true)) {
			return;
		}
		$this->enqueueModule();
	}

	/**
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function enqueueElementor(){
		$this->enqueueModule();
	}
}
