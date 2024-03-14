<?php

namespace luckywp\tableOfContents\plugin;

use luckywp\tableOfContents\core\base\BaseObject;
use luckywp\tableOfContents\core\Core;
use luckywp\tableOfContents\core\helpers\ArrayHelper;

class PostSettings extends BaseObject
{

    /**
     * @var bool
     */
    public $enabled;

    /**
     * @var bool
     */
    protected $defaultEnabled;

    /**
     * @var bool
     */
    public $processHeadings;

    /**
     * @var bool
     */
    protected $defaulProcessHeadings;

    /**
     * @var int|null
     */
    public $min;

    /**
     * @var int|null
     */
    public $depth;

    /**
     * @var bool|null
     */
    public $hierarchical;

    /**
     * @var string|null
     */
    public $numeration;

    /**
     * @var string|null
     */
    public $numerationSuffix;

    /**
     * @var string|null
     */
    public $title;

    /**
     * @var bool|null
     */
    public $toggle;

    /**
     * @var string|null
     */
    public $labelShow;

    /**
     * @var string|null
     */
    public $labelHide;

    /**
     * @var bool|null
     */
    public $hideItems;

    /**
     * @var bool|null
     */
    public $smoothScroll;

    /**
     * @var int|null
     */
    public $smoothScrollOffset;

    /**
     * @var string|null
     */
    public $position;

    /**
     * @var string|null
     */
    public $width;

    /**
     * @var string|null
     */
    public $float;

    /**
     * @var string|null
     */
    public $titleFontSize;

    /**
     * @var string|null
     */
    public $titleFontWeight;

    /**
     * @var string|null
     */
    public $itemsFontSize;

    /**
     * @var string|null
     */
    public $colorScheme;

    /**
     * @var string|null
     */
    public $backgroundColor;

    /**
     * @var string|null
     */
    public $borderColor;

    /**
     * @var string|null
     */
    public $titleColor;

    /**
     * @var string|null
     */
    public $linkColor;

    /**
     * @var string|null
     */
    public $hoverLinkColor;

    /**
     * @var string|null
     */
    public $visitedLinkColor;

    /**
     * @var bool
     */
    public $wrapNoindex;

    /**
     * @var bool
     */
    public $useNofollow;

    /**
     * @var string|null
     */
    public $skipHeadingLevel;

    /**
     * @var string|null
     */
    public $skipHeadingText;

    /**
     * @var string|null
     */
    public $containerClass;

    /**
     * @var int
     */
    protected $postId;

    /**
     * @param int $postId
     */
    public function __construct($postId)
    {
        parent::__construct();
        $postType = get_post_type($postId);
        $this->enabled = $this->defaultEnabled = Core::$plugin->settings->getAutoInsertEnable() ? in_array($postType, Core::$plugin->settings->getAutoInsertPostTypes()) : false;
        $this->processHeadings = $this->defaulProcessHeadings = in_array($postType, Core::$plugin->settings->getProcessingHeadingsAlwaysForPostTypes());
        if (in_array($postType, Core::$plugin->admin->getMetaboxPostTypes())) {
            $this->postId = $postId;
            $data = get_post_meta($postId, '_lwptoc_settings', true);
            if ($data && is_array($data)) {
                $this->enabled = ArrayHelper::getValue($data, 'enabled', $this->enabled);
                $this->processHeadings = ArrayHelper::getValue($data, 'processHeadings', $this->processHeadings);
                $this->min = ArrayHelper::getValue($data, 'min');
                $this->depth = ArrayHelper::getValue($data, 'depth');
                $this->hierarchical = ArrayHelper::getValue($data, 'hierarchical');
                $this->numeration = ArrayHelper::getValue($data, 'numeration');
                $this->numerationSuffix = ArrayHelper::getValue($data, 'numerationSuffix');
                $this->title = ArrayHelper::getValue($data, 'title');
                $this->toggle = ArrayHelper::getValue($data, 'toggle');
                $this->labelShow = ArrayHelper::getValue($data, 'labelShow');
                $this->labelHide = ArrayHelper::getValue($data, 'labelHide');
                $this->hideItems = ArrayHelper::getValue($data, 'hideItems');
                $this->smoothScroll = ArrayHelper::getValue($data, 'smoothScroll');
                $this->smoothScrollOffset = ArrayHelper::getValue($data, 'smoothScrollOffset');
                $this->position = ArrayHelper::getValue($data, 'position');
                $this->width = ArrayHelper::getValue($data, 'width');
                $this->float = ArrayHelper::getValue($data, 'float');
                $this->titleFontSize = ArrayHelper::getValue($data, 'titleFontSize');
                $this->titleFontWeight = ArrayHelper::getValue($data, 'titleFontWeight');
                $this->itemsFontSize = ArrayHelper::getValue($data, 'itemsFontSize');
                $this->colorScheme = ArrayHelper::getValue($data, 'colorScheme');
                $this->backgroundColor = ArrayHelper::getValue($data, 'backgroundColor');
                $this->borderColor = ArrayHelper::getValue($data, 'borderColor');
                $this->titleColor = ArrayHelper::getValue($data, 'titleColor');
                $this->linkColor = ArrayHelper::getValue($data, 'linkColor');
                $this->hoverLinkColor = ArrayHelper::getValue($data, 'hoverLinkColor');
                $this->visitedLinkColor = ArrayHelper::getValue($data, 'visitedLinkColor');
                $this->wrapNoindex = ArrayHelper::getValue($data, 'wrapNoindex');
                $this->useNofollow = ArrayHelper::getValue($data, 'useNofollow');
                $this->skipHeadingLevel = ArrayHelper::getValue($data, 'skipHeadingLevel');
                $this->skipHeadingText = ArrayHelper::getValue($data, 'skipHeadingText');
                $this->containerClass = ArrayHelper::getValue($data, 'containerClass');
            }
        }
    }

    public function save()
    {
        $data = [];
        if ($this->min !== null) {
            $data['min'] = (int)$this->min;
        }
        if ($this->depth !== null) {
            $data['depth'] = (int)$this->depth;
        }
        if ($this->hierarchical !== null) {
            $data['hierarchical'] = (bool)$this->hierarchical;
        }
        if ($this->numeration !== null) {
            $data['numeration'] = $this->numeration;
        }
        if ($this->numerationSuffix !== null) {
            $data['numerationSuffix'] = $this->numerationSuffix;
        }
        if ($this->title !== null) {
            $data['title'] = wp_slash($this->title);
        }
        if ($this->toggle !== null) {
            $data['toggle'] = (bool)$this->toggle;
        }
        if ($this->labelShow !== null) {
            $data['labelShow'] = wp_slash($this->labelShow);
        }
        if ($this->labelHide !== null) {
            $data['labelHide'] = wp_slash($this->labelHide);
        }
        if ($this->hideItems !== null) {
            $data['hideItems'] = (bool)$this->hideItems;
        }
        if ($this->smoothScroll !== null) {
            $data['smoothScroll'] = (bool)$this->smoothScroll;
        }
        if ($this->smoothScrollOffset !== null) {
            $data['smoothScrollOffset'] = (int)$this->smoothScrollOffset;
        }
        if ($this->position !== null) {
            $data['position'] = $this->position;
        }
        if ($this->width !== null) {
            $data['width'] = $this->width;
        }
        if ($this->float !== null) {
            $data['float'] = $this->float;
        }
        if ($this->titleFontSize !== null) {
            $data['titleFontSize'] = $this->titleFontSize;
        }
        if ($this->titleFontWeight !== null) {
            $data['titleFontWeight'] = $this->titleFontWeight;
        }
        if ($this->itemsFontSize !== null) {
            $data['itemsFontSize'] = $this->itemsFontSize;
        }
        if ($this->colorScheme !== null) {
            $data['colorScheme'] = $this->colorScheme;
        }
        if ($this->backgroundColor !== null) {
            $data['backgroundColor'] = $this->backgroundColor;
        }
        if ($this->borderColor !== null) {
            $data['borderColor'] = $this->borderColor;
        }
        if ($this->titleColor !== null) {
            $data['titleColor'] = $this->titleColor;
        }
        if ($this->linkColor !== null) {
            $data['linkColor'] = $this->linkColor;
        }
        if ($this->hoverLinkColor !== null) {
            $data['hoverLinkColor'] = $this->hoverLinkColor;
        }
        if ($this->visitedLinkColor !== null) {
            $data['visitedLinkColor'] = $this->visitedLinkColor;
        }
        if ($this->wrapNoindex !== null) {
            $data['wrapNoindex'] = (bool)$this->wrapNoindex;
        }
        if ($this->useNofollow !== null) {
            $data['useNofollow'] = (bool)$this->useNofollow;
        }
        if ($this->skipHeadingLevel !== null) {
            $data['skipHeadingLevel'] = $this->skipHeadingLevel;
        }
        if ($this->skipHeadingText !== null) {
            $data['skipHeadingText'] = wp_slash($this->skipHeadingText);
        }
        if ($this->containerClass !== null) {
            $data['containerClass'] = wp_slash($this->containerClass);
        }
        if ($data ||
            $this->enabled != $this->defaultEnabled ||
            $this->processHeadings != $this->defaulProcessHeadings
        ) {
            $data['enabled'] = $this->enabled;
            $data['processHeadings'] = $this->processHeadings;
            update_post_meta($this->postId, '_lwptoc_settings', $data);
        } else {
            delete_post_meta($this->postId, '_lwptoc_settings');
        }
    }
}
