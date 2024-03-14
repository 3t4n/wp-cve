<?php

namespace luckywp\tableOfContents\front;

use luckywp\tableOfContents\core\Core;
use luckywp\tableOfContents\core\helpers\ArrayHelper;
use luckywp\tableOfContents\core\helpers\ValueHelper;
use luckywp\tableOfContents\plugin\Settings;

class Toc
{

    /**
     * @var int
     */
    public static $currentOutputDepth;

    /**
     * @var array
     */
    public static $overrideColors = [];

    /**
     * @param array $headings
     * @param array|string|null $attrs
     * @return string
     */
    public static function render($headings, $attrs)
    {
        $attrs = ValueHelper::assertArray($attrs);

        $headerStyles = [];
        $titleStyles = [];
        $itemsStyles = [];

        $min = (int)ArrayHelper::getValue($attrs, 'min', Core::$plugin->settings->getGeneralMin());
        if ($min < 1) {
            $min = 1;
        }
        if (count($headings) < $min) {
            return '';
        }

        $skipHeadingLevels = Core::$plugin->skipHeadingLevelToArray(ArrayHelper::getValue($attrs, 'skipheadinglevel', Core::$plugin->settings->getMiscSkipHeadingLevel()));
        $skipHeadingRegex = Core::$plugin->skipHeadingTextToRegex(ArrayHelper::getValue($attrs, 'skipheadingtext', Core::$plugin->settings->getMiscSkipHeadingText()));
        $headings = array_filter($headings, function ($heading) use ($skipHeadingLevels, $skipHeadingRegex) {
            if (in_array('h' . $heading['index'], $skipHeadingLevels)) {
                return false;
            }
            if ($skipHeadingRegex && preg_match($skipHeadingRegex, $heading['label'])) {
                return false;
            }
            return true;
        });
        if (count($headings) < $min) {
            return '';
        }


        if (ValueHelper::assertBool(ArrayHelper::getValue($attrs, 'usenofollow', Core::$plugin->settings->getMiscUseNofollow()))) {
            $relNofollow = ' rel="nofollow"';
        } else {
            $relNofollow = '';
        }

        $items = [];
        $tree = [];
        foreach ($headings as $heading) {
            $node = null;
            while (count($tree) && ($node === null)) {
                end($tree);
                $key = key($tree);
                if ($heading['index'] > $tree[$key]['index']) {
                    $node = $key;
                } else {
                    unset($tree[$key]);
                }
            }

            $item = [
                'id' => $heading['id'],
                'index' => $heading['index'],
                'number' => null,
                'numberSuffix' => null,
                'label' => $heading['label'],
                'relNofollow' => $relNofollow,
                'childrens' => [],
            ];

            if ($node === null) {
                $items[] = $item;
                $tree[] = &$items[count($items) - 1];
            } else {
                $tree[$node]['childrens'][] = $item;
                $tree[] = &$tree[$node]['childrens'][count($tree[$node]['childrens']) - 1];
            }
        }
        unset($tree);

        // Вложенность
        $depth = (int)ArrayHelper::getValue($attrs, 'depth', Core::$plugin->settings->getGeneralDepth());
        if (!array_key_exists($depth, Core::$plugin->getDepthsList())) {
            $depth = 6;
        }

        // Уберём все элменты, не подходящие по вложенности
        $currentDepth = 0;
        $fn = function (&$items, $depth) use (&$fn, &$currentDepth) {
            $currentDepth++;
            foreach ($items as $key => $item) {
                if ($currentDepth == $depth) {
                    $items[$key]['childrens'] = [];
                }
                $fn($items[$key]['childrens'], $depth);
            }
            $currentDepth--;
        };
        $fn($items, $depth);

        // Нумерация
        $numeration = ArrayHelper::getValue($attrs, 'numeration', Core::$plugin->settings->getGeneralNumeration());
        $numeration = str_replace(['_', ' ', '-'], '', strtolower($numeration));
        if (in_array($numeration, ['decimalnested', 'decimal', 'roman', 'romannested'])) {
            if (in_array($numeration, ['decimalnested', 'romannested'])) {
                $fn = function (&$items, $numbers) use (&$fn, $numeration) {
                    foreach ($items as $key => $item) {
                        $numbers[count($numbers) - 1]++;
                        $items[$key]['number'] = implode('.', $numeration == 'decimalnested' ? $numbers : array_map([Toc::class, 'decimalToRoman'], $numbers));
                        $fn($items[$key]['childrens'], array_merge($numbers, [0]));
                    }
                };
                $fn($items, [0]);
            } else {
                $number = 0;
                $fn = function (&$items) use (&$fn, &$number, $numeration) {
                    foreach ($items as $key => $item) {
                        $number++;
                        $items[$key]['number'] = $numeration == 'decimal' ? $number : static::decimalToRoman($number);
                        $fn($items[$key]['childrens']);
                    }
                };
                $fn($items);
            }

            // Суффикс
            $suffix = ArrayHelper::getValue([
                'dot' => '.',
                'roundbracket' => ')',
            ], ArrayHelper::getValue($attrs, 'numerationsuffix', Core::$plugin->settings->getGeneralNumerationSuffix()), '');
            $fn = function (&$items) use (&$fn, $suffix) {
                foreach ($items as $key => $item) {
                    $items[$key]['numberSuffix'] = $suffix;
                    $fn($items[$key]['childrens']);
                }
            };
            $fn($items);
        }

        // Без иерархии
        $hierarchical = ValueHelper::assertBool(ArrayHelper::getValue($attrs, 'hierarchical', Core::$plugin->settings->getGeneralHierarchical()));
        if (!$hierarchical) {
            $newItems = [];
            $fn = function ($items) use (&$fn, &$newItems) {
                foreach ($items as $item) {
                    $newItem = $item;
                    $newItem['childrens'] = [];
                    $newItems[] = $newItem;
                    $fn($item['childrens']);
                }
            };
            $fn($items);
            $items = $newItems;
        }

        $toggle = ValueHelper::assertBool(ArrayHelper::getValue($attrs, 'toggle', Core::$plugin->settings->getGeneralToggle()));
        $labelShow = null;
        $labelHide = null;
        $hideItems = false;
        if ($toggle) {
            $labelShow = ArrayHelper::getValue($attrs, 'labelshow', Core::$plugin->settings->getGeneralLabelShow());
            $labelHide = ArrayHelper::getValue($attrs, 'labelhide', Core::$plugin->settings->getGeneralLabelHide());
            $hideItems = ValueHelper::assertBool(ArrayHelper::getValue($attrs, 'hideitems', Core::$plugin->settings->getGeneralHideItems()));
        }
        if ($hideItems) {
            $itemsStyles[] = 'display:none;';
        }

        $containerOptions = [
            'class' => ['lwptoc'],
            'data' => [],
        ];
        $innerContainerOptions = [
            'class' => ['lwptoc_i'],
        ];

        // Дополнительные классы
        $classes = trim((string)ArrayHelper::getValue($attrs, 'containerclass', Core::$plugin->settings->getMiscContainerClass()));
        if ($classes) {
            $containerOptions['class'][] = $classes;
        }

        // Плавная прокрутка
        $smoothScroll = ValueHelper::assertBool(ArrayHelper::getValue($attrs, 'smoothscroll', Core::$plugin->settings->getGeneralSmoothScroll()));
        $containerOptions['data']['smooth-scroll'] = $smoothScroll ? 1 : 0;
        if ($smoothScroll) {
            $containerOptions['data']['smooth-scroll-offset'] = (int)ArrayHelper::getValue($attrs, 'smoothscrolloffset', Core::$plugin->settings->getGeneralSmoothScrollOffset());
        }

        // Выравнивание
        $float = ArrayHelper::getValue($attrs, 'float', Core::$plugin->settings->getAppearanceFloat());
        $float = strtolower($float);

        // Ширина
        $width = ArrayHelper::getValue($attrs, 'width');
        if ($width === null) {
            $width = Core::$plugin->settings->getAppearanceWidth();
        } else {
            $width = Settings::sanitizeWidth($width);
        }

        // Опции для выравнивания и ширины
        if (in_array($float, ['left', 'right', 'center', 'rightwithoutflow']) &&
            (!in_array($float, ['left', 'right', 'rightwithoutflow']) || $width != 'full')) {
            $containerOptions['class'][] = 'lwptoc-' . $float;
        }
        if ($width != 'full') {
            if ($width == 'auto') {
                $containerOptions['class'][] = 'lwptoc-autoWidth';
            } else {
                if (in_array($float, ['left', 'right'])) {
                    $containerOptions['style'] = 'width:' . $width;
                } else {
                    $innerContainerOptions['style'] = 'width:' . $width;
                }
            }
        }

        // Размер шрифта заголовка
        $titleFontSize = ArrayHelper::getValue($attrs, 'titlefontsize');
        if ($titleFontSize === null) {
            $titleFontSize = Core::$plugin->settings->getAppearanceTitleFontSize();
        } else {
            $titleFontSize = Settings::sanitizeFontSize($titleFontSize);
        }
        if ($titleFontSize != 'default') {
            $headerStyles[] = 'font-size:' . $titleFontSize . ';';
        }

        // Толщина шрифта заголовка
        $titleFontWeight = ArrayHelper::getValue($attrs, 'titlefontweight', Core::$plugin->settings->getAppearanceTitleFontWeight());
        $titleFontWeight = str_replace(['_', ' ', '-'], '', strtolower($titleFontWeight));
        if ($titleFontWeight != 'bold' && array_key_exists($titleFontWeight, Core::$plugin->getFontWeightsList())) {
            $titleStyles[] = 'font-weight:' . Core::$plugin->fontWeightToValue($titleFontWeight) . ';';
        }

        // Размер шрифта элементов
        $itemsFontSize = ArrayHelper::getValue($attrs, 'itemsfontsize');
        if ($itemsFontSize === null) {
            $itemsFontSize = Core::$plugin->settings->getAppearanceItemsFontSize();
        } else {
            $itemsFontSize = Settings::sanitizeFontSize($itemsFontSize);
        }
        if ($itemsFontSize == '90%') {
            $containerOptions['class'][] = 'lwptoc-baseItems';
        } elseif ($itemsFontSize != 'default') {
            $itemsStyles[] = 'font-size:' . $itemsFontSize . ';';
        }

        // Цветовая схема
        $colorScheme = ArrayHelper::getValue($attrs, 'colorscheme', Core::$plugin->settings->getAppearanceColorScheme());
        $colorScheme = str_replace(['_', ' ', '-'], '', strtolower($colorScheme));
        if (array_key_exists($colorScheme, Core::$plugin->getColorSchemesList())) {
            $containerOptions['class'][] = 'lwptoc-' . $colorScheme;
            if ($colorScheme != 'inherit') {
                $containerOptions['class'][] = 'lwptoc-notInherit';
            }
        }

        // Запомним цвета для переопределения
        static::$overrideColors = [];
        foreach ([
                     'backgroundColor',
                     'borderColor',
                     'titleColor',
                     'linkColor',
                     'hoverLinkColor',
                     'visitedLinkColor',
                 ] as $var) {
            $color = ArrayHelper::getValue($attrs, strtolower($var), Core::$plugin->settings->{'appearance' . ucfirst($var)});
            $color = Core::$plugin->settings->sanitizeCallbackColor($color);
            if ($color) {
                static::$overrideColors[$var] = $color;
            }
        }

        $before = (string)apply_filters('lwptoc_before', '');
        $after = (string)apply_filters('lwptoc_after', '');

        if (ValueHelper::assertBool(ArrayHelper::getValue($attrs, 'wrapnoindex', Core::$plugin->settings->getMiscWrapNoindex()))) {
            $before = '<!--noindex-->' . $before;
            $after = $after . '<!--/noindex-->';
        }

        // Подключаем CSS/JS
        Core::$plugin->front->enqueueAssets();

        // Вывод
        static::$currentOutputDepth = -1;
        return Core::$plugin->front->render('body', [
            'title' => ArrayHelper::getValue($attrs, 'title', Core::$plugin->settings->getGeneralTitle()),
            'titleTag' => apply_filters('lwptoc_title_tag', 'b'),
            'toggle' => $toggle,
            'labelShow' => $labelShow,
            'labelHide' => $labelHide,
            'hideItems' => $hideItems,
            'containerOptions' => $containerOptions,
            'innerContainerOptions' => $innerContainerOptions,
            'headerStyles' => $headerStyles,
            'titleStyles' => $titleStyles,
            'itemsStyles' => $itemsStyles,
            'items' => $items,
            'before' => $before,
            'after' => $after,
        ]);
    }

    /**
     * @param int $decimal
     * @return string
     */
    protected static function decimalToRoman($decimal)
    {
        $roman = '';
        $map = [
            'M' => 1000,
            'CM' => 900,
            'D' => 500,
            'CD' => 400,
            'C' => 100,
            'XC' => 90,
            'L' => 50,
            'XL' => 40,
            'X' => 10,
            'IX' => 9,
            'V' => 5,
            'IV' => 4,
            'I' => 1
        ];
        foreach ($map as $number => $value) {
            $matches = intval($decimal / $value);
            $roman .= str_repeat($number, $matches);
            $decimal = $decimal % $value;
        }
        return $roman;
    }
}
