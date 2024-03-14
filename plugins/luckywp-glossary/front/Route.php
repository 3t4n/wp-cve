<?php

namespace luckywp\glossary\front;

use luckywp\glossary\core\base\BaseObject;
use luckywp\glossary\core\Core;
use luckywp\glossary\core\wp\RewriteRules;
use luckywp\glossary\plugin\Term;

class Route extends BaseObject
{

    /**
     * @var array
     */
    protected $defaultPermalinksConfig = [
        'term_structure' => '%archive%/%term%'
    ];

    /**
     * @var bool
     */
    protected $usedFrontTermStructureQueryFix = false;

    public function init()
    {
        parent::init();
        add_action('init', function () {
            add_rewrite_tag('%' . Term::POST_TYPE . '%', '([^(&|/)]+)');
        });
        add_filter('rewrite_rules_array', [$this, 'modifyRules']);

        // Для корректной работы со структурой ссылок %term%
        add_action('pre_get_posts', function ($query) {
            /** @var \WP_Query $query */
            if ($query->is_main_query() &&
                count($query->query) == 3 &&
                isset($query->query['name']) &&
                isset($query->query['post_type']) &&
                isset($query->query[Term::POST_TYPE]) &&
                $this->getPermalinksConfig('term_structure') == '%term%'
            ) {
                $permalinkStructure = trim(trim((string)get_option('permalink_structure')), '\/');
                if ($permalinkStructure != '') {
                    $postTypes = ['page', Term::POST_TYPE];
                    if ($permalinkStructure == '%postname%') {
                        $postTypes[] = 'post';
                    }
                    $query->set('post_type', $postTypes);
                    $this->usedFrontTermStructureQueryFix = true;
                }
            }
        });
        add_action('wp', function () {
            global $wp_query;
            if ($this->usedFrontTermStructureQueryFix && is_404()) {
                $wp_query->set('post_type', 'page');
            }
        });

        // При сохранении страницы архива обновлять правила роутинга
        add_filter('save_post', function ($postId) {
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                return;
            }
            if ($postId == Core::$plugin->archivePageId) {
                RewriteRules::flushAfterReload();
            }
        });

        // Фильтры для генерации ссылки на архив записей
        add_filter('post_type_archive_link', function ($link, $postType) {
            return $postType == Term::POST_TYPE ? Core::$plugin->archivePageUrl : $link;
        }, 10, 2);

        // Фильтр для генерации ссылки на термин
        add_filter('post_type_link', function ($permalink, $post, $leavename) {
            if (get_post_type($post) !== Term::POST_TYPE ||
                !get_option('permalink_structure') ||
                in_array(get_post_status($post), ['auto-draft', 'draft'])
            ) {
                return $permalink;
            }
            $url = [];
            foreach ($this->permastruct2segments($this->getPermalinksConfig('term_structure')) as $segment) {
                $u = null;
                switch ($segment->type) {
                    case RouteSegment::ARCHIVE:
                        $u = Core::$plugin->archivePageSlug;
                        break;

                    case RouteSegment::TERM:
                        $u = $leavename ? '%' . Term::POST_TYPE . '%' : $post->post_name;
                        break;

                    case RouteSegment::STRING:
                        $u = $segment->var;
                        break;
                }
                if ($u !== null) {
                    $url[] = $segment->prefix . $u . $segment->suffix;
                }
            }
            return home_url(user_trailingslashit(implode('/', $url)));
        }, 10, 3);

        // C архива постов редирект на страницу архива
        add_action('template_redirect', function () {
            if (is_post_type_archive(Term::POST_TYPE)) {
                wp_safe_redirect(get_post_type_archive_link(Term::POST_TYPE));
                exit;
            }
        });
    }

    /**
     * Правила роутинга
     * @param array $_rules
     * @return array
     */
    public function modifyRules($_rules)
    {
        $rules = [];
        $archiveSlug = Core::$plugin->archivePageSlug;

        // Термины
        $items = [];
        foreach ($this->permastruct2segments($this->getPermalinksConfig('term_structure')) as $segment) {
            $item = null;
            switch ($segment->type) {
                case RouteSegment::ARCHIVE:
                    $item = preg_quote($archiveSlug);
                    break;

                case RouteSegment::TERM:
                    $item = '([^/]+)';
                    break;

                case RouteSegment::STRING:
                    if ($segment->var != '') {
                        $item = preg_quote($segment->var);
                    }
                    break;
            }
            if ($item !== null) {
                $items[] = preg_quote($segment->prefix) . $item . preg_quote($segment->suffix);
            }
        }
        $rules[implode('/', $items) . '/?$'] = 'index.php?' . Term::POST_TYPE . '=$matches[1]';

        return array_merge($rules, $_rules);
    }

    /**
     * @param string $struct
     * @return RouteSegment[]
     */
    public function permastruct2segments($struct)
    {
        $segments = [];
        foreach (explode('/', $struct) as $s) {
            $segments[] = new RouteSegment($s);
        }
        return $segments;
    }

    /**
     * Возвращает значение опции или все опции, если не задан параметр $key
     * @param bool $key
     * @param string $data default|values|use
     * @return mixed
     */
    public function getPermalinksConfig($key = false, $data = 'use')
    {
        $data = in_array($data, ['use', 'default', 'values']) ? $data : 'use';

        if ($data == 'default') {
            $config = $this->defaultPermalinksConfig;
        } else {
            $config = [
                'term_structure' => Core::$plugin->settings->getValue('general', 'term_structure'),
            ];
            $config = array_filter($config, function ($v) {
                return is_string($v) && $v != '';
            });
            if ($data == 'use') {
                $config = array_merge($this->defaultPermalinksConfig, $config);
            }
        }

        if ($key === false) {
            return $config;
        }

        return isset($config[$key]) ? $config[$key] : null;
    }
}
