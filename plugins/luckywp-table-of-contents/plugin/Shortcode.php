<?php

namespace luckywp\tableOfContents\plugin;

use luckywp\tableOfContents\core\base\BaseObject;
use luckywp\tableOfContents\core\Core;
use luckywp\tableOfContents\core\helpers\Json;
use luckywp\tableOfContents\core\helpers\ValueHelper;
use luckywp\tableOfContents\front\Toc;
use luckywp\tableOfContents\plugin\contentHandling\ContentHandling;
use luckywp\tableOfContents\plugin\contentHandling\ContentHandlingDto;
use WP_Post;

class Shortcode extends BaseObject
{

    /**
     * Инициализация
     */
    public function init()
    {
        parent::init();
        add_action('init', function () {
            add_shortcode($this->getTag(), [$this, 'shortcode']);
        });
        add_filter('the_content', [$this, 'theContent'], 9999);
    }

    /**
     * @var bool
     */
    protected $isTheContentEmulate = false;

    /**
     * @var bool
     */
    protected $theContentApplied = false;

    /**
     * @var array|null
     */
    protected $headingsCache;

    /**
     * @var array
     */
    protected $skipLevels = [];

    /**
     * @var array
     */
    protected $skipText = [];

    /**
     * Обработка шорткода
     * @param $attrs
     * @return string
     */
    public function shortcode($attrs)
    {
        global $post;
        if ($this->isDiactivated() ||
            ($this->theContentApplied && $this->headingsCache === null) ||
            !static::allow()
        ) {
            return '';
        }

        $attrs = ValueHelper::assertArray($attrs);
        if ($post instanceof WP_Post) {
            $postSettings = new PostSettings($post->ID);
            if ($postSettings->enabled || $postSettings->processHeadings) {
                foreach ([
                             'min',
                             'depth',
                             'hierarchical',
                             'numeration',
                             'numerationSuffix',
                             'title',
                             'toggle',
                             'labelShow',
                             'labelHide',
                             'hideItems',
                             'smoothScroll',
                             'smoothScrollOffset',
                             'width',
                             'float',
                             'titleFontSize',
                             'titleFontWeight',
                             'itemsFontSize',
                             'colorScheme',
                             'backgroundColor',
                             'borderColor',
                             'titleColor',
                             'linkColor',
                             'hoverLinkColor',
                             'visitedLinkColor',
                             'wrapNoindex',
                             'useNofollow',
                             'skipHeadingLevel',
                             'skipHeadingText',
                             'containerClass',
                         ] as $var) {
                    if (!array_key_exists(strtolower($var), $attrs) &&
                        $postSettings->$var !== null
                    ) {
                        $attrs[strtolower($var)] = $postSettings->$var;
                    }
                }
            }
        }

        if (Core::$plugin->isTheContent) {
            return $this->make($attrs, true);
        }

        if (!$this->theContentApplied) {
            $this->skipLevels[] = Core::$plugin->skipHeadingLevelToArray(array_key_exists('skipheadinglevel', $attrs) ? $attrs['skipheadinglevel'] : Core::$plugin->settings->getMiscSkipHeadingLevel());
            $this->skipText[] = Core::$plugin->skipHeadingTextToArray(array_key_exists('skipheadingtext', $attrs) ? $attrs['skipheadingtext'] : Core::$plugin->settings->getMiscSkipHeadingText());
        }

        if ($this->headingsCache === null) {
            $dto = $this->makeContentHandlingDto();
            $dto->modify = false;

            $this->isTheContentEmulate = true;
            $dto->content = apply_filters('the_content', $post->post_content);
            $this->isTheContentEmulate = false;

            $this->headingsCache = ContentHandling::go($dto)->headings;
        }

        return Toc::render($this->headingsCache, $attrs);
    }

    /**
     * @param string $content
     * @return string
     */
    public function theContent($content)
    {
        if ($this->isDiactivated() ||
            !apply_filters('lwptoc_need_processing_headings', $this->needProcessHeadings($content), $content)
        ) {
            return $content;
        }

        $dto = $this->makeContentHandlingDto();
        $dto->content = $content;
        $dto->modify = true;

        $shortcodesAttrs = [];
        preg_match_all($this->getShortcodeRegex(), $this->decodeShorcode($content), $matches);
        foreach ($matches[3] as $match) {
            $shortcodesAttrs[] = ValueHelper::assertArray(shortcode_parse_atts($match));
        }

        $skipLevels = $this->skipLevels;
        foreach ($shortcodesAttrs as $attrs) {
            if (array_key_exists('skipheadinglevel', $attrs)) {
                $skipLevels[] = Core::$plugin->skipHeadingLevelToArray($attrs['skipheadinglevel']);
            } else {
                $skipLevels[] = Core::$plugin->skipHeadingLevelToArray(Core::$plugin->settings->getMiscSkipHeadingLevel());
            }
        }
        if (!$skipLevels) {
            $skipLevels[] = Core::$plugin->skipHeadingLevelToArray(Core::$plugin->settings->getMiscSkipHeadingLevel());
        }
        if (count($skipLevels) > 1) {
            $dto->skipLevels = call_user_func_array('array_intersect', $skipLevels);
        } else {
            $dto->skipLevels = reset($skipLevels);
        }

        $skipText = $this->skipText;
        foreach ($shortcodesAttrs as $attrs) {
            if (array_key_exists('skipheadingtext', $attrs)) {
                $skipText[] = Core::$plugin->skipHeadingTextToArray($attrs['skipheadingtext']);
            } else {
                $skipText[] = Core::$plugin->skipHeadingTextToArray(Core::$plugin->settings->getMiscSkipHeadingText());
            }
        }
        if (!$skipText) {
            $skipText[] = Core::$plugin->skipHeadingTextToArray(Core::$plugin->settings->getMiscSkipHeadingText());
        }
        if (count($skipText) > 1) {
            $dto->skipText = call_user_func_array('array_intersect', $skipText);
        } else {
            $dto->skipText = reset($skipText);
        }

        $result = ContentHandling::go($dto);
        $this->headingsCache = $result->headings;

        $this->theContentApplied = true;

        return preg_replace_callback($this->getShortcodeRegex(), function ($m) use ($result) {
            return Toc::render($result->headings, shortcode_parse_atts($m[3]));
        }, $this->decodeShorcode($result->content));
    }

    /**
     * @return ContentHandlingDto
     */
    protected function makeContentHandlingDto()
    {
        $dto = new ContentHandlingDto();
        $dto->hashFormat = Core::$plugin->settings->getMiscHashFormat();
        $dto->hashConvertToLowercase = Core::$plugin->settings->getMiscHashConvertToLowercase();
        $dto->hashReplaceUnderlinesToDashes = Core::$plugin->settings->getMiscHashReplaceUnderlinesToDashes();
        return $dto;
    }

    /**
     * @param string $content
     * @return bool
     */
    protected function needProcessHeadings($content)
    {
        global $post;

        if (!static::allow()) {
            return false;
        }

        if ($this->headingsCache !== null) {
            return true;
        }

        if ($this->isTheContentEmulate ||
            !is_singular()
        ) {
            return false;
        }

        if ($this->hasShorcode($content)) {
            return true;
        }

        return (new PostSettings($post->ID))->processHeadings;
    }

    /**
     * @return bool
     */
    public static function allow()
    {
        global $post, $wp_query;
        if (!($post instanceof WP_Post)) {
            return false;
        }

        $allow = (is_single($post->ID) || is_page($post->ID)) && // Это страница записи
            ($post->ID == $wp_query->get_queried_object_id()); // Это главный запрос на странице

        return apply_filters('lwptoc_allow', $allow, $post);
    }

    private $_tag;

    /**
     * @return string
     */
    public function getTag()
    {
        if ($this->_tag === null) {
            $this->_tag = apply_filters('lwptoc_shortcode_tag', 'lwptoc');
        }
        return $this->_tag;
    }

    /**
     * @param array $attrs
     * @param bool $encode
     * @return string
     */
    public function make($attrs, $encode = false)
    {
        $shortcode = '[' . $this->getTag();
        foreach ($attrs as $k => $v) {
            if ($v !== null) {
                if (is_string($v)) {
                    $v = wp_slash(str_replace('"', '&quot;', $v));
                } elseif (is_bool($v)) {
                    $v = $v ? 1 : 0;
                }
                $shortcode .= ' ' . $k . '="' . $v . '"';
            }
        }
        $shortcode .= ']';
        return $encode ? '<!-- lwptocEncodedToc ' . base64_encode($shortcode) . ' -->' : $shortcode;
    }

    /**
     * @param string $content
     * @return string
     */
    protected function decodeShorcode($content)
    {
        return preg_replace_callback('#<!-- lwptocEncodedToc (.*?) -->#imsu', function ($matches) {
            return base64_decode($matches[1]);
        }, $content);
    }

    /**
     * @param string $content
     * @return bool
     */
    public function hasShorcode($content)
    {
        return has_shortcode($content, $this->getTag()) ||
            preg_match('#<!-- lwptocEncodedToc (.*?) -->#imsu', $content) === 1;
    }

    /**
     * @return string
     */
    protected function getShortcodeRegex()
    {
        return '/' . get_shortcode_regex([$this->getTag()]) . '/s';
    }

    /**
     * @param string $json
     * @return array
     */
    public static function attrsFromJson($json)
    {
        return ValueHelper::assertArray(Json::decode($json));
    }

    /**
     * @return bool
     */
    protected function isDiactivated()
    {
        return !apply_filters('lwptoc_active', true);
    }
}
