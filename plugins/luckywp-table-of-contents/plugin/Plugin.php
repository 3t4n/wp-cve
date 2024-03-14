<?php

namespace luckywp\tableOfContents\plugin;

use luckywp\tableOfContents\admin\Admin;
use luckywp\tableOfContents\admin\Rate;
use luckywp\tableOfContents\core\base\BasePlugin;
use luckywp\tableOfContents\core\base\Request;
use luckywp\tableOfContents\core\base\View;
use luckywp\tableOfContents\core\Core;
use luckywp\tableOfContents\core\helpers\ArrayHelper;
use luckywp\tableOfContents\core\wp\Options;
use luckywp\tableOfContents\front\Front;
use luckywp\tableOfContents\integrations\BeaverBuilder;
use luckywp\tableOfContents\integrations\elementor\Elementor;
use luckywp\tableOfContents\integrations\Oxygen;
use luckywp\tableOfContents\integrations\RankMath;
use luckywp\tableOfContents\integrations\ToolsetViews;
use luckywp\tableOfContents\integrations\TwentyTwentyTheme;
use luckywp\tableOfContents\integrations\Wpml;
use luckywp\tableOfContents\plugin\editorBlock\EditorBlock;
use luckywp\tableOfContents\plugin\mcePlugin\McePlugin;
use WP_Post_Type;

/**
 * @property Admin $admin
 * @property Front $front
 * @property Request $request
 * @property Settings $settings
 * @property EditorBlock $editorBlock
 * @property McePlugin $mcePlugin
 * @property Options $options
 * @property Rate $rate
 * @property Shortcode $shortcode
 * @property View $view
 *
 * @property WP_Post_Type[] $postTypes
 * @property array $depthsList
 * @property array $numerationsList
 * @property array $positionsList
 * @property array $blockSizeUnitsList
 * @property array $fontSizeUnitsList
 * @property array $fontWeightsList
 * @property array $floatsList
 * @property array $colorSchemesList
 */
class Plugin extends BasePlugin
{

    /**
     * @var int
     */
    public $isTheContent = 0;

    /**
     * Инициализация
     */
    public function init()
    {
        add_filter('the_content', [$this, 'onTheContentTrue'], -PHP_INT_MAX);
        add_filter('the_content', [$this, 'onTheContentFalse'], 10000);
        add_action('widgets_init', function () {
            register_widget(WpWidget::class);
        });

        // Интеграции с другими плагинами
        add_action('plugins_loaded', function () {
            if (defined('FL_BUILDER_VERSION')) {
                Core::createObject(BeaverBuilder::class);
            }
            if (class_exists('\RankMath')) {
                Core::createObject(RankMath::class);
            }
            if (defined('WPV_VERSION')) {
                Core::createObject(ToolsetViews::class);
            }
            if (defined('ELEMENTOR_VERSION')) {
                Core::createObject(Elementor::class);
            }
            if (class_exists('\SitePress')) {
                Core::createObject(Wpml::class);
            }
            if (defined('CT_VERSION')) {
                Core::createObject(Oxygen::class);
            }
        });

        // Интеграция с темами
        add_action('init', function () {
            if (get_template() == 'twentytwenty') {
                Core::createObject(TwentyTwentyTheme::class);
            }
        });
    }

    /**
     * @param string $content
     * @return string
     */
    public function onTheContentTrue($content)
    {
        $this->isTheContent++;
        return $content;
    }

    /**
     * @param string $content
     * @return string
     */
    public function onTheContentFalse($content)
    {
        $this->isTheContent--;
        return $content;
    }

    private $_postTypes;

    /**
     * @return WP_Post_Type[]
     */
    public function getPostTypes()
    {
        if ($this->_postTypes === null) {
            $this->_postTypes = get_post_types([
                'public' => true,
            ], 'objects');
        }
        return $this->_postTypes;
    }

    /**
     * @return array
     */
    public function getDepthsList()
    {
        return [
            1 => '1',
            2 => '2',
            3 => '3',
            4 => '4',
            5 => '5',
            6 => '6',
        ];
    }

    /**
     * @return array
     */
    public function getNumerationsList()
    {
        return [
            'none' => esc_html__('Without numeration', 'luckywp-table-of-contents'),
            'decimalnested' => esc_html__('Decimal numbers (nested)', 'luckywp-table-of-contents'),
            'decimal' => esc_html__('Decimal numbers', 'luckywp-table-of-contents'),
            'romannested' => esc_html__('Roman numbers (nested)', 'luckywp-table-of-contents'),
            'roman' => esc_html__('Roman numbers', 'luckywp-table-of-contents'),
        ];
    }

    /**
     * @return array
     */
    public function getNumerationSuffixsList()
    {
        return [
            'none' => esc_html__('None', 'luckywp-table-of-contents'),
            'dot' => '.',
            'roundbracket' => ')',
        ];
    }

    /**
     * @return array
     */
    public function getPositionsList()
    {
        return [
            'beforefirstheading' => esc_html__('Before first heading', 'luckywp-table-of-contents'),
            'afterfirstheading' => esc_html__('After first heading', 'luckywp-table-of-contents'),
            'afterfirstblock' => esc_html__('After first block (paragraph or heading)', 'luckywp-table-of-contents'),
            'top' => esc_html__('Top', 'luckywp-table-of-contents'),
            'bottom' => esc_html__('Bottom', 'luckywp-table-of-contents'),
        ];
    }

    /**
     * @return array
     */
    public function getBlockSizeUnitsList()
    {
        return [
            'px' => 'px',
            '%' => '%',
        ];
    }

    /**
     * @return array
     */
    public function getFontSizeUnitsList()
    {
        return [
            '%' => '%',
            'em' => 'em',
            'pt' => 'pt',
            'px' => 'px',
        ];
    }

    /**
     * @return array
     */
    public function getFontWeightsList()
    {
        return [
            'thin' => esc_html__('Thin', 'luckywp-table-of-contents'),
            'extralight' => esc_html__('Extra Light', 'luckywp-table-of-contents'),
            'light' => esc_html__('Light', 'luckywp-table-of-contents'),
            'normal' => esc_html__('Normal', 'luckywp-table-of-contents'),
            'medium' => esc_html__('Medium', 'luckywp-table-of-contents'),
            'semibold' => esc_html__('Semi Bold', 'luckywp-table-of-contents'),
            'bold' => esc_html__('Bold', 'luckywp-table-of-contents'),
            'extrabold' => esc_html__('Extra Bold', 'luckywp-table-of-contents'),
            'heavy' => esc_html__('Heavy', 'luckywp-table-of-contents'),
        ];
    }

    /**
     * @param string $id
     * @return string|null
     */
    public function fontWeightToValue($id)
    {
        return ArrayHelper::getValue([
            'thin' => '100',
            'extralight' => '200',
            'light' => '300',
            'normal' => 'normal',
            'medium' => '500',
            'semibold' => '600',
            'bold' => 'bold',
            'extrabold' => '800',
            'heavy' => '900',
        ], $id);
    }

    /**
     * @return array
     */
    public function getFloatsList()
    {
        return [
            'none' => esc_html__('None', 'luckywp-table-of-contents'),
            'left' => esc_html__('Left', 'luckywp-table-of-contents'),
            'right' => esc_html__('Right', 'luckywp-table-of-contents'),
            'rightwithoutflow' => esc_html__('Right without flow', 'luckywp-table-of-contents'),
            'center' => esc_html__('Center', 'luckywp-table-of-contents'),
        ];
    }

    /**
     * @return array
     */
    public function getColorSchemesList()
    {
        return [
            'light' => esc_html__('Light Colors', 'luckywp-table-of-contents'),
            'dark' => esc_html__('Dark Colors', 'luckywp-table-of-contents'),
            'white' => esc_html__('White', 'luckywp-table-of-contents'),
            'transparent' => esc_html__('Transparent', 'luckywp-table-of-contents'),
            'inherit' => esc_html__('Inherit from theme', 'luckywp-table-of-contents'),
        ];
    }

    /**
     * @return array
     */
    public function getHashFormatsList()
    {
        return [
            'asheading' => esc_html__('As heading (#Example_Heading_Text)', 'luckywp-table-of-contents'),
            'asheadingwotransliterate' => esc_html__('As heading without transliterate (#Example_Heading_Text)', 'luckywp-table-of-contents'),
            'counter' => sprintf(
            /* translators: %s: (#lwptoc1, #lwptoc2, …) */
                esc_html__('Counter %s', 'luckywp-table-of-contents'),
                '(#lwptoc1, #lwptoc2, …)'
            ),
        ];
    }

    /**
     * @param bool $withCustom
     * @return array
     */
    public function getWidthsList($withCustom = true)
    {
        $widths = [
            'auto' => esc_html__('Auto', 'luckywp-table-of-contents'),
            'full' => esc_html__('Full Width', 'luckywp-table-of-contents'),
            'custom' => esc_html__('Custom Value', 'luckywp-table-of-contents'),
        ];
        if (!$withCustom) {
            unset($widths['custom']);
        }
        return $widths;
    }

    /**
     * @param string $width
     * @return bool
     */
    public function isCustomWidth($width)
    {
        return !array_key_exists($width, $this->getWidthsList(false));
    }

    /**
     * @param string $width
     * @return string
     */
    public function widthToLabel($width)
    {
        if ($this->isCustomWidth($width)) {
            return $width;
        }
        return $this->getWidthsList()[$width];
    }

    /**
     * @param string $fontSize
     * @return string
     */
    public function fontSizeToLabel($fontSize)
    {
        return $fontSize == 'default' ? esc_html__('Default', 'luckywp-table-of-contents') : $fontSize;
    }

    /**
     * @return array
     */
    public function getHeadingsList()
    {
        return [
            'h1' => 'H1',
            'h2' => 'H2',
            'h3' => 'H3',
            'h4' => 'H4',
            'h5' => 'H5',
            'h6' => 'H6',
        ];
    }

    /**
     * @return array
     */
    public function getListMarkupTagsList()
    {
        return [
            'div' => 'DIV',
            'ul' => 'UL/LI',
            'ol' => 'OL/LI',
        ];
    }

    /**
     * @param string|array $value
     * @return array
     */
    public function skipHeadingLevelToArray($value)
    {
        if (is_array($value)) {
            $ids = $value;
        } else {
            $ids = explode(',', (string)$value);
            $ids = array_map('trim', $ids);
            $ids = array_map('strtolower', $ids);
        }
        $list = $this->getHeadingsList();
        return array_filter($ids, function ($id) use ($list) {
            return array_key_exists($id, $list);
        });
    }

    /**
     * @param string $value
     * @return string
     */
    public function skipHeadingLevelToLabel($value)
    {
        $ids = $this->skipHeadingLevelToArray($value);
        $labels = [];
        if ($ids) {
            $list = $this->getHeadingsList();
            foreach ($ids as $id) {
                $labels[] = $list[$id];
            }
        }
        return $labels ? implode(', ', $labels) : esc_html__('None', 'luckywp-table-of-contents');
    }

    /**
     * @param string $string
     * @return array
     */
    public function skipHeadingTextToArray($string)
    {
        $string = str_replace('\|', '%%lwptocplug%%', $string);
        $els = explode('|', $string);
        $els = array_map('trim', $els);
        $els = array_filter($els, function ($el) {
            return $el != '';
        });
        $els = array_map(function ($el) {
            return str_replace('%%lwptocplug%%', '|', $el);
        }, $els);
        return $els;
    }

    /**
     * @param string $string
     * @return string
     */
    public function skipHeadingTextToMultipleString($string)
    {
        return implode(PHP_EOL, $this->skipHeadingTextToArray($string));
    }

    /**
     * @param $string
     * @return string
     */
    public function skipHeadingTextMultipleStringToString($string)
    {
        $els = explode(PHP_EOL, $string);
        $els = array_map('trim', $els);
        $els = array_filter($els, function ($el) {
            return $el != '';
        });
        $els = array_map(function ($el) {
            return str_replace('|', '\|', $el);
        }, $els);
        return implode('|', $els);
    }

    /**
     * @param string|array $src
     * @return string|false
     */
    public function skipHeadingTextToRegex($src)
    {
        if (!is_array($src)) {
            $src = $this->skipHeadingTextToArray($src);
        }
        $regex = [];
        foreach ($src as $t) {
            $t = trim(preg_replace('/\s+/u', ' ', $t));
            if ($t == '') {
                continue;
            }
            $t = strtr($t, [
                '\\' => '%%lwptocslash%%',
                '\*' => '%%lwptocstar%%',
            ]);
            $t = str_replace('*', '___lwptocany___', $t);
            $t = strtr($t, [
                '%%lwptocslash%%' => '\\',
                '%%lwptocstar%%' => '*',
            ]);
            $t = preg_quote($t);
            $t = str_replace('___lwptocany___', '.*', $t);
            $regex[] = $t;
        }
        return $regex ? '#^(' . implode('|', $regex) . ')$#i' : false;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'LuckyWP ' . esc_html__('Table of Contents', 'luckywp-table-of-contents');
    }

    private function pluginI18n()
    {
        __('Creates a table of contents for your posts/pages. Works automatically or manually (via shortcode, Gutenberg block or widget).', 'luckywp-table-of-contents');
    }
}
