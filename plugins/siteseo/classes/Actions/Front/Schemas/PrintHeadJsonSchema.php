<?php

namespace SiteSEO\Actions\Front\Schemas;

if ( ! defined('ABSPATH')) {
	exit;
}

use SiteSEO\Core\Hooks\ExecuteHooksFrontend;

class PrintHeadJsonSchema implements ExecuteHooksFrontend {
	public function hooks() {
		if (apply_filters('siteseo_old_social_accounts_jsonld_hook', false)) {
			return;
		}

		add_action('wp_head', [$this, 'render'], 2);
	}

	public function render() {
		/**
		 * Check if Social toggle is ON
		 *
		 * @since 5.3
		 * author Softaculous
		 */
		if (siteseo_get_toggle_option('social') !=='1') {
			return;
		}

		/**
		 * Check if is homepage
		 *
		 * @since 5.3
		 * author Softaculous
		 */
		if (!is_front_page()) {
			return;
		}

		if ('none' === siteseo_get_service('SocialOption')->getSocialKnowledgeType()) {
			return;
		}

		$jsons = siteseo_get_service('JsonSchemaGenerator')->getJsonsEncoded([
			'organization'
		]);

		echo wp_kses('<script type="application/ld+json">'.apply_filters('siteseo_schemas_organization_html', $jsons[0]).'</script>', ['script' => ['type' => true]]);
	}
}
