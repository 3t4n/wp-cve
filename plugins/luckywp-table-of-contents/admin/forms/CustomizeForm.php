<?php

namespace luckywp\tableOfContents\admin\forms;

use luckywp\tableOfContents\core\base\Model;
use luckywp\tableOfContents\core\Core;
use luckywp\tableOfContents\core\helpers\ArrayHelper;
use luckywp\tableOfContents\plugin\PostSettings;
use luckywp\tableOfContents\plugin\Settings;

class CustomizeForm extends Model
{

    public $defaultMin;
    public $min;

    public $defaultDepth;
    public $depth;

    public $defaultHierarchical;
    public $hierarchical;

    public $defaultNumeration;
    public $numeration;

    public $defaultNumerationSuffix;
    public $numerationSuffix;

    public $defaultTitle;
    public $title;

    public $defaultToggle;
    public $toggle;

    public $defaultLabelShow;
    public $labelShow;

    public $defaultLabelHide;
    public $labelHide;

    public $defaultHideItems;
    public $hideItems;

    public $defaultSmoothScroll;
    public $smoothScroll;

    public $defaultSmoothScrollOffset;
    public $smoothScrollOffset;

    public $defaultPosition;
    public $position;

    public $defaultWidth;
    public $width;

    public $defaultFloat;
    public $float;

    public $defaultTitleFontSize;
    public $titleFontSize;

    public $defaultTitleFontWeight;
    public $titleFontWeight;

    public $defaultItemsFontSize;
    public $itemsFontSize;

    public $defaultColorScheme;
    public $colorScheme;

    public $defaultBackgroundColor;
    public $backgroundColor;

    public $defaultBorderColor;
    public $borderColor;

    public $defaultTitleColor;
    public $titleColor;

    public $defaultLinkColor;
    public $linkColor;

    public $defaultHoverLinkColor;
    public $hoverLinkColor;

    public $defaultVisitedLinkColor;
    public $visitedLinkColor;

    public $defaultWrapNoindex;
    public $wrapNoindex;

    public $defaultUseNofollow;
    public $useNofollow;

    public $defaultSkipHeadingLevel;
    public $skipHeadingLevel;

    public $defaultSkipHeadingText;
    public $skipHeadingText;

    public $defaultContainerClass;
    public $containerClass;

    /**
     * @var PostSettings|null
     */
    public $postSettings;

    /**
     * @var bool
     */
    public $isPostSettings;

    /**
     * @var bool
     */
    public $isPostOrWidgetSettings;

    protected $vars = [
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
    ];

    /**
     * @param PostSettings|null $postSettings
     * @param array|null $attrs
     * @param array $config
     */
    public function __construct($postSettings, $attrs = null, array $config = [])
    {
        $this->postSettings = $postSettings;
        $this->isPostSettings = $attrs === null;
        $this->isPostOrWidgetSettings = $this->isPostSettings || $postSettings === null;
        if ($this->isPostSettings) {
            $this->vars[] = 'position';
        }

        if (is_array($attrs)) {
            $attrs = array_change_key_case($attrs, CASE_LOWER);
        }

        foreach ($this->vars as $var) {
            $value = $this->isPostSettings ? $postSettings->$var : ArrayHelper::getValue($attrs, strtolower($var));
            $this->{'default' . ucfirst($var)} = ($value === null) ? 1 : 0;
            if (!$this->{'default' . ucfirst($var)}) {
                $this->$var = $value;
            }
        }
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [
                array_map(function ($v) {
                    return 'default' . ucfirst($v);
                }, $this->vars),
                'boolean'
            ],
            ['min', 'filter', 'filter' => 'intval'],
            ['depth', 'filter', 'filter' => 'intval'],
            ['depth', 'in', 'range' => array_keys(Core::$plugin->depthsList)],
            ['hierarchical', 'boolean'],
            ['numeration', 'in', 'range' => array_keys(Core::$plugin->numerationsList)],
            ['numerationSuffix', 'in', 'range' => array_keys(Core::$plugin->getNumerationSuffixsList())],
            ['title', 'filter', 'filter' => 'trim'],
            ['toggle', 'boolean'],
            [['labelShow', 'labelHide'], 'filter', 'filter' => 'trim'],
            [
                'labelShow',
                'required',
                'when' => function () {
                    return !$this->defaultLabelShow;
                }
            ],
            [
                'labelHide',
                'required',
                'when' => function () {
                    return !$this->defaultLabelHide;
                }
            ],
            ['hideItems', 'boolean'],
            ['smoothScroll', 'boolean'],
            ['smoothScrollOffset', 'filter', 'filter' => 'intval'],
            ['position', 'in', 'range' => array_keys(Core::$plugin->positionsList)],
            ['width', 'filter', 'filter' => [Settings::class, 'sanitizeWidth']],
            ['float', 'in', 'range' => array_keys(Core::$plugin->floatsList)],
            ['titleFontSize', 'filter', 'filter' => [Settings::class, 'sanitizeFontSize']],
            ['titleFontWeight', 'in', 'range' => array_keys(Core::$plugin->fontWeightsList)],
            ['itemsFontSize', 'filter', 'filter' => [Settings::class, 'sanitizeFontSize']],
            ['colorScheme', 'in', 'range' => array_keys(Core::$plugin->colorSchemesList)],
            [
                ['backgroundColor', 'borderColor', 'titleColor', 'linkColor', 'hoverLinkColor', 'visitedLinkColor'],
                'filter',
                'filter' => [Core::$plugin->settings, 'sanitizeCallbackColor'],
            ],
            ['wrapNoindex', 'boolean'],
            ['useNofollow', 'boolean'],
            ['skipHeadingLevel', 'filter', 'filter' => [Settings::class, 'sanitizeSkipHeadingLevel']],
            [
                'skipHeadingText',
                'filter',
                'filter' => function ($value) {
                    return Core::$plugin->skipHeadingTextMultipleStringToString((string)$value);
                }
            ],
            ['containerClass', 'filter', 'filter' => 'trim'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'depth' => esc_html__('Depth', 'luckywp-table-of-contents'),
            'numeration' => esc_html__('Numeration', 'luckywp-table-of-contents'),
            'numerationSuffix' => esc_html__('Numeration Suffix', 'luckywp-table-of-contents'),
            'labelShow' => esc_html__('Label Show', 'luckywp-table-of-contents'),
            'labelHide' => esc_html__('Label Hide', 'luckywp-table-of-contents'),
            'position' => esc_html__('Position', 'luckywp-table-of-contents'),
            'float' => esc_html__('Float', 'luckywp-table-of-contents'),
            'titleFontWeight' => esc_html__('Title Font Weight', 'luckywp-table-of-contents'),
            'colorScheme' => esc_html__('Color Scheme', 'luckywp-table-of-contents'),
        ];
    }

    /**
     * @param PostSettings $postSettings
     */
    public function toPostSettings($postSettings)
    {
        $postSettings->position = $this->defaultPosition ? null : $this->position;
        foreach ($this->getAttrs() as $var => $value) {
            $postSettings->$var = $value;
        }
    }

    /**
     * @return string
     */
    public function generateShortcode()
    {
        return Core::$plugin->shortcode->make($this->getAttrs());
    }

    /**
     * @return array
     */
    public function getAttrs()
    {
        return [
            'min' => $this->defaultMin ? null : ($this->min < 0 ? 0 : $this->min),
            'depth' => $this->defaultDepth ? null : $this->depth,
            'hierarchical' => $this->defaultHierarchical ? null : (bool)$this->hierarchical,
            'numeration' => $this->defaultNumeration ? null : $this->numeration,
            'numerationSuffix' => $this->defaultNumerationSuffix ? null : $this->numerationSuffix,
            'title' => $this->defaultTitle ? null : $this->title,
            'toggle' => $this->defaultToggle ? null : (bool)$this->toggle,
            'labelShow' => $this->defaultLabelShow ? null : $this->labelShow,
            'labelHide' => $this->defaultLabelHide ? null : $this->labelHide,
            'hideItems' => $this->defaultHideItems ? null : (bool)$this->hideItems,
            'smoothScroll' => $this->defaultSmoothScroll ? null : (bool)$this->smoothScroll,
            'smoothScrollOffset' => $this->defaultSmoothScrollOffset ? null : (int)$this->smoothScrollOffset,
            'width' => $this->defaultWidth ? null : $this->width,
            'float' => $this->defaultFloat ? null : $this->float,
            'titleFontSize' => $this->defaultTitleFontSize ? null : $this->titleFontSize,
            'titleFontWeight' => $this->defaultTitleFontWeight ? null : $this->titleFontWeight,
            'itemsFontSize' => $this->defaultItemsFontSize ? null : $this->itemsFontSize,
            'colorScheme' => $this->defaultColorScheme ? null : $this->colorScheme,
            'backgroundColor' => $this->defaultBackgroundColor ? null : ($this->backgroundColor ? $this->backgroundColor : ''),
            'borderColor' => $this->defaultBorderColor ? null : ($this->borderColor ? $this->borderColor : ''),
            'titleColor' => $this->defaultTitleColor ? null : ($this->titleColor ? $this->titleColor : ''),
            'linkColor' => $this->defaultLinkColor ? null : ($this->linkColor ? $this->linkColor : ''),
            'hoverLinkColor' => $this->defaultHoverLinkColor ? null : ($this->hoverLinkColor ? $this->hoverLinkColor : ''),
            'visitedLinkColor' => $this->defaultVisitedLinkColor ? null : ($this->visitedLinkColor ? $this->visitedLinkColor : ''),
            'wrapNoindex' => $this->defaultWrapNoindex ? null : (bool)$this->wrapNoindex,
            'useNofollow' => $this->defaultUseNofollow ? null : (bool)$this->useNofollow,
            'skipHeadingLevel' => $this->defaultSkipHeadingLevel ? null : $this->skipHeadingLevel,
            'skipHeadingText' => $this->defaultSkipHeadingText ? null : $this->skipHeadingText,
            'containerClass' => $this->defaultContainerClass ? null : $this->containerClass,
        ];
    }
}
