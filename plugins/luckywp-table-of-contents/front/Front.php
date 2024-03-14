<?php

namespace luckywp\tableOfContents\front;

use DOMXPath;
use luckywp\tableOfContents\core\Core;
use luckywp\tableOfContents\core\front\BaseFront;
use luckywp\tableOfContents\core\helpers\ArrayHelper;
use luckywp\tableOfContents\plugin\dom\Dom;
use luckywp\tableOfContents\plugin\PostSettings;
use luckywp\tableOfContents\plugin\Shortcode;

class Front extends BaseFront
{

    public $defaultThemeViewsDir = 'luckywp-table-of-contents';

    public function init()
    {
        parent::init();
        if (Core::isFront()) {
            add_action('wp_enqueue_scripts', [$this, 'registerAssets']);
            add_action('init', function () {
                if (Core::$plugin->settings->getPostTypesForProcessingHeadings()) {
                    add_filter('the_content', [$this, 'autoInsert'], 998);
                }
            });
            add_action('wp_footer', [$this, 'overrideColors'], 21);
        }
    }

    private $_assetsRegistred = false;

    public function registerAssets()
    {
        if (!$this->_assetsRegistred) {
            wp_register_style('lwptoc-main', Core::$plugin->url . '/front/assets/main.min.css', [], Core::$plugin->version);
            wp_register_script('lwptoc-main', Core::$plugin->url . '/front/assets/main.min.js', [], Core::$plugin->version);
            $this->_assetsRegistred = true;
        }
    }

    private $_assetsEnqueued = false;

    public function enqueueAssets()
    {
        if (!$this->_assetsEnqueued) {
            $this->registerAssets();
            if (apply_filters('lwptoc_enqueue_style', true)) {
                wp_enqueue_style('lwptoc-main');
            }
            if (apply_filters('lwptoc_enqueue_script', true)) {
                wp_enqueue_script('lwptoc-main');
            }
            $this->_assetsEnqueued = true;
        }
    }

    public function overrideColors()
    {
        if (Toc::$overrideColors) {
            $styles = [];

            $iStyles = [];
            if ($color = ArrayHelper::getValue(Toc::$overrideColors, 'backgroundColor')) {
                $iStyles[] = 'background-color:' . $color . ';';
            }
            if ($color = ArrayHelper::getValue(Toc::$overrideColors, 'borderColor')) {
                $iStyles[] = 'border:1px solid ' . $color . ';';
            }
            if ($iStyles) {
                $styles[] = '.lwptoc .lwptoc_i{' . implode($iStyles) . '}';
            }

            if ($color = ArrayHelper::getValue(Toc::$overrideColors, 'titleColor')) {
                $styles[] = '.lwptoc_header{color:' . $color . ';}';
            }

            if ($color = ArrayHelper::getValue(Toc::$overrideColors, 'linkColor')) {
                $styles[] = '.lwptoc .lwptoc_i A{color:' . $color . ';}';
            }
            if ($color = ArrayHelper::getValue(Toc::$overrideColors, 'hoverLinkColor')) {
                $styles[] = '.lwptoc .lwptoc_i A:hover,.lwptoc .lwptoc_i A:focus,.lwptoc .lwptoc_i A:active{color:' . $color . ';border-color:' . $color . ';}';
            }
            if ($color = ArrayHelper::getValue(Toc::$overrideColors, 'visitedLinkColor')) {
                $styles[] = '.lwptoc .lwptoc_i A:visited{color:' . $color . ';}';
            }

            if ($styles) {
                echo '<style>' . implode('', $styles) . '</style>';
            }
        }
    }

    /**
     * @param string $content
     * @return string
     */
    public function autoInsert($content)
    {
        global $post;

        if (!Shortcode::allow()) {
            return $content;
        }

        if (!in_array($post->post_type, Core::$plugin->settings->getPostTypesForProcessingHeadings())) {
            return $content;
        }

        if (Core::$plugin->shortcode->hasShorcode($content)) {
            return $content;
        }

        if (apply_filters('lwptoc_disable_autoinsert', false)) {
            return $content;
        }

        $settings = new PostSettings($post->ID);
        if (!$settings->enabled) {
            return $content;
        }

        $attrs = [];
        $attrs['min'] = $settings->min;
        $attrs['depth'] = $settings->depth;
        $attrs['hierarchical'] = $settings->hierarchical;
        $attrs['numeration'] = $settings->numeration;
        $attrs['numerationSuffix'] = $settings->numerationSuffix;
        $attrs['title'] = $settings->title;
        $attrs['toggle'] = $settings->toggle;
        $attrs['labelShow'] = $settings->labelShow;
        $attrs['labelHide'] = $settings->labelHide;
        $attrs['hideItems'] = $settings->hideItems;
        $attrs['smoothScroll'] = $settings->smoothScroll;
        $attrs['smoothScrollOffset'] = $settings->smoothScrollOffset;
        $attrs['width'] = $settings->width;
        $attrs['float'] = $settings->float;
        $attrs['titleFontSize'] = $settings->titleFontSize;
        $attrs['titleFontWeight'] = $settings->titleFontWeight;
        $attrs['itemsFontSize'] = $settings->itemsFontSize;
        $attrs['colorScheme'] = $settings->colorScheme;
        $attrs['backgroundColor'] = $settings->backgroundColor;
        $attrs['borderColor'] = $settings->borderColor;
        $attrs['titleColor'] = $settings->titleColor;
        $attrs['linkColor'] = $settings->linkColor;
        $attrs['hoverLinkColor'] = $settings->hoverLinkColor;
        $attrs['visitedLinkColor'] = $settings->visitedLinkColor;
        $attrs['wrapNoindex'] = $settings->wrapNoindex;
        $attrs['useNofollow'] = $settings->useNofollow;
        $attrs['skipHeadingLevel'] = $settings->skipHeadingLevel;
        $attrs['skipHeadingText'] = $settings->skipHeadingText;
        $attrs['containerClass'] = $settings->containerClass;

        $shortcode = Core::$plugin->shortcode->make($attrs, true);

        $position = $settings->position ? $settings->position : Core::$plugin->settings->autoInsertPosition;
        switch ($position) {
            case 'beforefirstheading':
            case 'afterfirstheading':
            default:
                $dom = Dom::make($content);
                if ($dom === false) {
                    return $shortcode . $content;
                }

                $xpath = new DOMXPath($dom);
                $nodes = $xpath->query('//h1|//h2|//h3|//h4|//h5|//h6');
                if (!$nodes->length) {
                    return $shortcode . $content;
                }

                if ($position == 'afterfirstheading') {
                    Dom::afterNodeInsertShortcode($nodes->item(0), $shortcode);
                } else {
                    Dom::beforeNodeInsertShortcode($nodes->item(0), $shortcode);
                }

                return Dom::getBody($dom);

            case 'afterfirstblock':
                $dom = Dom::make($content);
                if ($dom === false) {
                    return $shortcode . $content;
                }

                $xpath = new DOMXPath($dom);
                $nodes = $xpath->query('//h1|//h2|//h3|//h4|//h5|//h6|//p');
                if (!$nodes->length) {
                    return $shortcode . $content;
                }

                Dom::afterNodeInsertShortcode($nodes->item(0), $shortcode);

                return Dom::getBody($dom);

            case 'bottom':
                return $content . $shortcode;

            case 'top':
                return $shortcode . $content;
        }
    }
}
