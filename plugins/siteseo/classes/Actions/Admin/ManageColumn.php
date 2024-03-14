<?php

namespace SiteSEO\Actions\Admin;

if (! defined('ABSPATH')) {
	exit;
}

use SiteSEO\Core\Hooks\ExecuteHooksBackend;
use SiteSEO\Services\TagsToString;

class ManageColumn implements ExecuteHooksBackend
{
	/**
	 * @var TagsToString
	 */
	protected $tagsToStringService;

	/**
	 * @since 4.4.0
	 */
	public function __construct()
	{
		$this->tagsToStringService = siteseo_get_service('TagsToString');
	}

	/**
	 * @since 4.4.0
	 *
	 * @return void
	 */
	public function hooks()
	{
		if ('1' == siteseo_get_toggle_option('advanced')) {
			add_action('init', [$this, 'setup']);
		}
	}

	public function setup()
	{
		$listPostTypes = siteseo_get_service('WordPressData')->getPostTypes();

		if (empty($listPostTypes)) {
			return;
		}

		foreach ($listPostTypes as $key => $value) {
			add_filter('manage_' . $key . '_posts_columns', [$this, 'addColumn']);
			add_action('manage_' . $key . '_posts_custom_column', [$this, 'displayColumn'], 10, 2);
		}

		add_filter('manage_edit-download_columns', [$this, 'addColumn'], 10, 2);
	}

	public function addColumn($columns)
	{
		if (! empty(siteseo_get_service('AdvancedOption')->getAppearanceTitleCol())) {
			$columns['siteseo_title'] = __('Title tag', 'siteseo');
		}
		if (! empty(siteseo_get_service('AdvancedOption')->getAppearanceMetaDescriptionCol())) {
			$columns['siteseo_desc'] = __('Meta Desc.', 'siteseo');
		}
		if (! empty(siteseo_get_service('AdvancedOption')->getAppearanceRedirectEnableCol())) {
			$columns['siteseo_redirect_enable'] = __('Redirect?', 'siteseo');
		}
		if (! empty(siteseo_get_service('AdvancedOption')->getAppearanceRedirectUrlCol())) {
			$columns['siteseo_redirect_url'] = __('Redirect URL', 'siteseo');
		}
		if (! empty(siteseo_get_service('AdvancedOption')->getAppearanceCanonical())) {
			$columns['siteseo_canonical'] = __('Canonical', 'siteseo');
		}
		if (! empty(siteseo_get_service('AdvancedOption')->getAppearanceTargetKwCol())) {
			$columns['siteseo_tkw'] = __('Target Kw', 'siteseo');
		}
		if (! empty(siteseo_get_service('AdvancedOption')->getAppearanceNoIndexCol())) {
			$columns['siteseo_noindex'] = __('noindex?', 'siteseo');
		}
		if (! empty(siteseo_get_service('AdvancedOption')->getAppearanceNoFollowCol())) {
			$columns['siteseo_nofollow'] = __('nofollow?', 'siteseo');
		}
		if (! empty(siteseo_get_service('AdvancedOption')->getAppearanceScoreCol())) {
			$columns['siteseo_score'] = __('Score', 'siteseo');
		}
		if (! empty(siteseo_get_service('AdvancedOption')->getAppearanceWordsCol())) {
			$columns['siteseo_words'] = __('Words', 'siteseo');
		}

		return $columns;
	}

	/**
	 * @since 4.4.0
	 * @see manage_' . $postType . '_posts_custom_column
	 *
	 * @param string $column
	 * @param int	$post_id
	 *
	 * @return void
	 */
	public function displayColumn($column, $post_id)
	{
		switch ($column) {
			case 'siteseo_title':
				$metaPostTitle = get_post_meta($post_id, '_siteseo_titles_title', true);

				$context = siteseo_get_service('ContextPage')->buildContextWithCurrentId($post_id)->getContext();
				$title   = $this->tagsToStringService->replace($metaPostTitle, $context);
				if (empty($title)) {
					$title = $metaPostTitle;
				}
				printf('<div id="siteseo_title-%s">%s</div>', esc_attr($post_id), wp_kses_post($title));
				printf('<div id="siteseo_title_raw-%s" class="hidden">%s</div>', esc_attr($post_id), wp_kses_post($metaPostTitle));
				break;

			case 'siteseo_desc':
				$metaDescription   = get_post_meta($post_id, '_siteseo_titles_desc', true);
				$context		   = siteseo_get_service('ContextPage')->buildContextWithCurrentId($post_id)->getContext();
				$description	   = $this->tagsToStringService->replace($metaDescription, $context);
				if (empty($description)) {
					$description = $metaDescription;
				}
				printf('<div id="siteseo_desc-%s">%s</div>', esc_attr($post_id), wp_kses_post($description));
				printf('<div id="siteseo_desc_raw-%s" class="hidden">%s</div>', esc_attr($post_id), wp_kses_post($metaDescription));
				break;

			case 'siteseo_redirect_enable':
				if ('yes' == get_post_meta($post_id, '_siteseo_redirections_enabled', true)) {
					echo '<div id="siteseo_redirect_enable-' . esc_attr($post_id) . '"><span class="dashicons dashicons-yes"></span></div>';
				}
				break;
			case 'siteseo_redirect_url':
				echo '<div id="siteseo_redirect_url-' . esc_attr($post_id) . '">' . esc_html(get_post_meta($post_id, '_siteseo_redirections_value', true)) . '</div>';
				break;

			case 'siteseo_canonical':
				echo '<div id="siteseo_canonical-' . esc_attr($post_id) . '">' . esc_html(get_post_meta($post_id, '_siteseo_robots_canonical', true)) . '</div>';
				break;

			case 'siteseo_tkw':
				echo '<div id="siteseo_tkw-' . esc_attr($post_id) . '">' . esc_html(get_post_meta($post_id, '_siteseo_analysis_target_kw', true)) . '</div>';
				break;

			case 'siteseo_noindex':
				if ('yes' == get_post_meta($post_id, '_siteseo_robots_index', true)) {
					echo '<span class="dashicons dashicons-hidden"></span><span class="screen-reader-text">' . esc_html__('noindex is on!', 'siteseo') . '</span>';
				}
				break;

			case 'siteseo_nofollow':
				if ('yes' == get_post_meta($post_id, '_siteseo_robots_follow', true)) {
					echo '<span class="dashicons dashicons-yes"></span><span class="screen-reader-text">' . esc_html__('nofollow is on!', 'siteseo') . '</span>';
				}
				break;

			case 'siteseo_words':
				$dataApiAnalysis = get_post_meta($post_id, '_siteseo_content_analysis_api', true);
				if (isset($dataApiAnalysis['words_counter']) && $dataApiAnalysis['words_counter'] !== null) {
					echo wp_kses_post($dataApiAnalysis['words_counter']);
				} else {
					if ('' != get_the_content()) {
						$siteseo_analysis_data['words_counter'] = preg_match_all("/\p{L}[\p{L}\p{Mn}\p{Pd}'\x{2019}]*/u", strip_tags(wp_filter_nohtml_kses(get_the_content())), $matches);

						echo wp_kses_post($siteseo_analysis_data['words_counter']);
					}
				}
				break;

			case 'siteseo_score':
				$dataApiAnalysis = get_post_meta($post_id, '_siteseo_content_analysis_api', true);
				if (isset($dataApiAnalysis['score']) && $dataApiAnalysis['score'] !== null) {
					echo '<div class="analysis-score">';
					if ($dataApiAnalysis['score'] === 'good') {
						echo '<p><svg role="img" aria-hidden="true" focusable="false" width="100%" height="100%" viewBox="0 0 200 200" version="1.1" xmlns="http://www.w3.org/2000/svg">
						<circle r="90" cx="100" cy="100" fill="transparent" stroke-dasharray="565.48" stroke-dashoffset="0"></circle>
						<circle id="bar" class="good" r="90" cx="100" cy="100" fill="transparent" stroke-dasharray="565.48" stroke-dashoffset="0"></circle>
					</svg><span class="screen-reader-text">' . esc_html__('Good', 'siteseo') . '</span></p>';
					} else {
						echo '<p><svg role="img" aria-hidden="true" focusable="false" width="100%" height="100%" viewBox="0 0 200 200" version="1.1" xmlns="http://www.w3.org/2000/svg">
						<circle r="90" cx="100" cy="100" fill="transparent" stroke-dasharray="565.48" stroke-dashoffset="0"></circle>
						<circle id="bar" class="notgood" r="90" cx="100" cy="100" fill="transparent" stroke-dasharray="565.48" stroke-dashoffset="0" style="stroke-dashoffset: 101.788px;"></circle>
					</svg><span class="screen-reader-text">' . esc_html__('Should be improved', 'siteseo') . '</span></p>';
					}
					echo '</div>';
				} else {
					if (get_post_meta($post_id, '_siteseo_analysis_data')) {
						$ca = get_post_meta($post_id, '_siteseo_analysis_data');
						echo '<div class="analysis-score">';
						if (isset($ca[0]['score']) && 1 == $ca[0]['score']) {
							echo '<p><svg role="img" aria-hidden="true" focusable="false" width="100%" height="100%" viewBox="0 0 200 200" version="1.1" xmlns="http://www.w3.org/2000/svg">
							<circle r="90" cx="100" cy="100" fill="transparent" stroke-dasharray="565.48" stroke-dashoffset="0"></circle>
							<circle id="bar" class="good" r="90" cx="100" cy="100" fill="transparent" stroke-dasharray="565.48" stroke-dashoffset="0"></circle>
						</svg><span class="screen-reader-text">' . esc_html__('Good', 'siteseo') . '</span></p>';
						} elseif (isset($ca[0]['score']) && '' == $ca[0]['score']) {
							echo '<p><svg role="img" aria-hidden="true" focusable="false" width="100%" height="100%" viewBox="0 0 200 200" version="1.1" xmlns="http://www.w3.org/2000/svg">
							<circle r="90" cx="100" cy="100" fill="transparent" stroke-dasharray="565.48" stroke-dashoffset="0"></circle>
							<circle id="bar" class="notgood" r="90" cx="100" cy="100" fill="transparent" stroke-dasharray="565.48" stroke-dashoffset="0" style="stroke-dashoffset: 101.788px;"></circle>
						</svg><span class="screen-reader-text">' . esc_html__('Should be improved', 'siteseo') . '</span></p>';
						}
						echo '</div>';
					}
				}
				break;
		}
	}
}
