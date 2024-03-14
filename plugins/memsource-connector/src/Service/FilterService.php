<?php

namespace Memsource\Service;

use Memsource\Service\TranslationPlugin\ITranslationPlugin;
use Memsource\Utils\ActionUtils;
use Memsource\Utils\DatabaseUtils;
use WP_Query;

class FilterService
{
    /** @var ITranslationPlugin */
    private $translationPlugin;

    public function __construct(ITranslationPlugin $translationPlugin)
    {
        $this->translationPlugin = $translationPlugin;
        add_filter('pre_get_posts', [$this, 'addQueryFilters']);
    }

    public function addQueryFilters(WP_Query $query, $forceWhere = false)
    {
        if (!$this->translationPlugin->isPluginActive()) {
            // do not filter on a single post page (non-admin)
            if ($forceWhere || $query->is_main_query() && !is_singular()) {
                add_filter('posts_where', [$this, 'filterByLanguage']);
            }
        }

        return $query;
    }

    public function filterByLanguage($where = '')
    {
        global $wpdb;
        $language = $this->getSelectedLanguageCode();

        if ($language !== 'all') {
            $postsTableName = $wpdb->prefix . DatabaseUtils::TABLE_POSTS;
            $translationsTableName = $wpdb->prefix . DatabaseUtils::TABLE_TRANSLATIONS;
            $where .= ' and ' . $postsTableName . '.ID in (select item_id from ' . $translationsTableName . ' where target_language = \'' . $language . '\')';
        }

        return $where;
    }

    private function getSelectedLanguageCode()
    {
        $language = $this->translationPlugin->getSourceLanguage();
        $requestLanguage = ActionUtils::getParameter('lang');

        if ($requestLanguage && preg_match('/^[A-Za-z-_]+$/', $requestLanguage)) {
            return $requestLanguage;
        }

        return $language;
    }
}
