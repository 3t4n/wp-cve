<?php

namespace luckywp\tableOfContents\plugin;

use luckywp\tableOfContents\core\Core;
use luckywp\tableOfContents\core\helpers\ValueHelper;

/**
 * @property int $generalMin
 * @property int $generalDepth
 * @property bool $generalHierarchical
 * @property string $generalNumeration
 * @property string $generalTitle
 * @property bool $generalToggle
 * @property string $generalLabelShow
 * @property string $generalLabelHide
 * @property bool $generalHideItems
 * @property bool $generalSmoothScroll
 * @property int $generalSmoothScrollOffset
 *
 * @property string $appearanceWidth
 * @property string $appearanceFloat
 * @property string $appearanceTitleFontSize
 * @property string $appearanceTitleFontWeight
 * @property string $appearanceItemsFontSize
 * @property string $appearanceColorScheme
 * @property string|null $appearanceBackgroundColor
 * @property string|null $appearanceBorderColor
 * @property string|null $appearanceTitleColor
 * @property string|null $appearanceLinkColor
 * @property string|null $appearanceHoverLinkColor
 * @property string|null $appearanceVisitedLinkColor
 *
 * @property bool $autoInsertEnable
 * @property string $autoInsertPosition
 * @property array $autoInsertPostTypes
 *
 * @property-read bool $miscWrapNoindex
 */
class Settings extends \luckywp\tableOfContents\core\wp\Settings
{

    /**
     * @return int
     */
    public function getGeneralMin()
    {
        return (int)$this->getValue('general', 'min', 2);
    }

    /**
     * @return int
     */
    public function getGeneralDepth()
    {
        $depth = (int)$this->getValue('general', 'depth');
        if (!array_key_exists($depth, Core::$plugin->depthsList)) {
            $depth = 6;
        }
        return $depth;
    }

    /**
     * @return bool
     */
    public function getGeneralHierarchical()
    {
        return (bool)$this->getValue('general', 'hierarchical', true);
    }

    /**
     * @return string
     */
    public function getGeneralNumeration()
    {
        $numeration = $this->getValue('general', 'numeration');
        if (!array_key_exists($numeration, Core::$plugin->numerationsList)) {
            $numeration = 'decimalnested';
        }
        return $numeration;
    }

    /**
     * @return string
     */
    public function getGeneralNumerationSuffix()
    {
        $suffix = $this->getValue('general', 'numerationSuffix');
        if (!array_key_exists($suffix, Core::$plugin->getNumerationSuffixsList())) {
            $suffix = 'none';
        }
        return $suffix;
    }

    /**
     * @return string
     */
    public function getGeneralTitle()
    {
        return (string)$this->getValue('general', 'title', __('Contents', 'luckywp-table-of-contents'));
    }

    /**
     * @return bool
     */
    public function getGeneralToggle()
    {
        return (bool)$this->getValue('general', 'toggle', true);
    }

    /**
     * @return string
     */
    public function getGeneralLabelShow()
    {
        return (string)$this->getValue('general', 'labelShow', __('show', 'luckywp-table-of-contents'));
    }

    /**
     * @return string
     */
    public function getGeneralLabelHide()
    {
        return (string)$this->getValue('general', 'labelHide', __('hide', 'luckywp-table-of-contents'));
    }

    /**
     * @return bool
     */
    public function getGeneralHideItems()
    {
        return (bool)$this->getValue('general', 'hideItems', false);
    }

    /**
     * @return bool
     */
    public function getGeneralSmoothScroll()
    {
        return (bool)$this->getValue('general', 'smoothScroll', true);
    }

    /**
     * @return int
     */
    public function getGeneralSmoothScrollOffset()
    {
        return (int)$this->getValue('general', 'smoothScrollOffset', 24);
    }

    /**
     * @return string
     */
    public function getAppearanceWidth()
    {
        return static::sanitizeWidth((string)$this->getValue('appearance', 'width', 'auto'));
    }

    /**
     * @return string
     */
    public function getAppearanceFloat()
    {
        $float = $this->getValue('appearance', 'float');
        if (!array_key_exists($float, Core::$plugin->floatsList)) {
            $float = 'none';
        }
        return $float;
    }

    /**
     * @return string
     */
    public function getAppearanceTitleFontSize()
    {
        return static::sanitizeFontSize((string)$this->getValue('appearance', 'titleFontSize', 'default'));
    }

    /**
     * @return string
     */
    public function getAppearanceTitleFontWeight()
    {
        $weight = $this->getValue('appearance', 'titleFontWeight');
        if (!array_key_exists($weight, Core::$plugin->fontWeightsList)) {
            $weight = 'bold';
        }
        return $weight;
    }

    /**
     * @return string
     */
    public function getAppearanceItemsFontSize()
    {
        return static::sanitizeFontSize((string)$this->getValue('appearance', 'itemsFontSize', 'default'));
    }

    /**
     * @return string
     */
    public function getAppearanceColorScheme()
    {
        $scheme = $this->getValue('appearance', 'colorScheme');
        if (!array_key_exists($scheme, Core::$plugin->colorSchemesList)) {
            $scheme = 'light';
        }
        return $scheme;
    }

    /**
     * @return string|null
     */
    public function getAppearanceBackgroundColor()
    {
        return $this->getValue('appearance', 'backgroundColor');
    }

    /**
     * @return string|null
     */
    public function getAppearanceBorderColor()
    {
        return $this->getValue('appearance', 'borderColor');
    }

    /**
     * @return string|null
     */
    public function getAppearanceTitleColor()
    {
        return $this->getValue('appearance', 'titleColor');
    }

    /**
     * @return string|null
     */
    public function getAppearanceLinkColor()
    {
        return $this->getValue('appearance', 'linkColor');
    }

    /**
     * @return string|null
     */
    public function getAppearanceHoverLinkColor()
    {
        return $this->getValue('appearance', 'hoverLinkColor');
    }

    /**
     * @return string|null
     */
    public function getAppearanceVisitedLinkColor()
    {
        return $this->getValue('appearance', 'visitedLinkColor');
    }

    /**
     * @return bool
     */
    public function getAutoInsertEnable()
    {
        return (bool)$this->getValue('autoInsert', 'enable', true);
    }

    /**
     * @return string
     */
    public function getAutoInsertPosition()
    {
        $position = $this->getValue('autoInsert', 'position');
        if (!array_key_exists($position, Core::$plugin->positionsList)) {
            $position = 'beforefirstheading';
        }
        return $position;
    }

    /**
     * @return array
     */
    public function getAutoInsertPostTypes()
    {
        $postTypes = $this->getValue('autoInsert', 'postTypes', []);
        return is_array($postTypes) ? $postTypes : [];
    }

    /**
     * @return array
     */
    public function getProcessingHeadingsAlwaysForPostTypes()
    {
        $postTypes = $this->getValue('processingHeadings', 'postTypes', []);
        return ValueHelper::assertArray($postTypes);
    }

    /**
     * @return string
     */
    public function getMiscHashFormat()
    {
        $hashFormat = $this->getValue('misc', 'hashFormat');
        if (!array_key_exists($hashFormat, Core::$plugin->getHashFormatsList())) {
            $hashFormat = 'asheading';
        }
        return $hashFormat;
    }

    /**
     * @return bool
     */
    public function getMiscHashConvertToLowercase()
    {
        return (bool)$this->getValue('misc', 'hashConvertToLowercase', false);
    }

    /**
     * @return bool
     */
    public function getMiscHashReplaceUnderlinesToDashes()
    {
        return (bool)$this->getValue('misc', 'hashReplaceUnderlinesToDashes', false);
    }

    /**
     * @return bool
     */
    public function getMiscWrapNoindex()
    {
        return (bool)$this->getValue('misc', 'wrapNoindex', false);
    }

    /**
     * @return bool
     */
    public function getMiscUseNofollow()
    {
        return (bool)$this->getValue('misc', 'useNofollow', false);
    }

    /**
     * @return string
     */
    public function getMiscSkipHeadingLevel()
    {
        return static::sanitizeSkipHeadingLevel($this->getValue('misc', 'skipHeadingLevel', false));
    }

    /**
     * @param mixed $value
     * @return string
     */
    public static function sanitizeSkipHeadingLevel($value)
    {
        return implode(',', Core::$plugin->skipHeadingLevelToArray($value));
    }

    /**
     * @return string
     */
    public function getMiscSkipHeadingText()
    {
        return (string)$this->getValue('misc', 'skipHeadingText', false);
    }

    /**
     * @return array
     */
    public function getMiscShowMetaboxPostTypes()
    {
        $postTypes = $this->getValue('misc', 'showMetaboxPostTypes', []);
        return ValueHelper::assertArray($postTypes);
    }

    /**
     * @param string $value
     * @return string
     */
    public static function sanitizeWidth($value)
    {
        return Core::$plugin->settings->prepareWidth($value, $matches);
    }

    /**
     * @param string $value
     * @param mixed $matches
     * @return string
     */
    public function prepareWidth($value, &$matches)
    {
        if (!Core::$plugin->isCustomWidth($value)) {
            return $value;
        }
        if (preg_match('/^(\d+|\d+\.\d+)(' . implode('|', array_keys(Core::$plugin->blockSizeUnitsList)) . ')$/', $value, $matches)) {
            return $value;
        }
        return 'auto';
    }

    /**
     * @param string $value
     * @return string
     */
    public static function sanitizeFontSize($value)
    {
        return Core::$plugin->settings->prepareFontSize($value, $matches);
    }

    /**
     * @param string $value
     * @param null $matches
     * @return string
     */
    public function prepareFontSize($value, &$matches)
    {
        if (preg_match('/^(\d+|\d+\.\d+)(' . implode('|', array_keys(Core::$plugin->fontSizeUnitsList)) . ')$/', $value, $matches)) {
            return $value;
        }
        return 'default';
    }

    /**
     * @return array
     */
    public function getPostTypesForProcessingHeadings()
    {
        $postTypes = [];
        if ($this->getAutoInsertEnable()) {
            $postTypes = $this->getAutoInsertPostTypes();
        }
        $postTypes = array_merge($postTypes, $this->getProcessingHeadingsAlwaysForPostTypes(), $this->getMiscShowMetaboxPostTypes());
        return array_unique($postTypes);
    }

    /**
     * @return string
     */
    public function getListMarkupTags()
    {
        $tags = $this->getValue('misc', 'listMarkupTags');
        if (!array_key_exists($tags, Core::$plugin->getListMarkupTagsList())) {
            $tags = 'div';
        }
        return $tags;
    }

    /**
     * @return string
     */
    public function getMiscContainerClass()
    {
        return (string)$this->getValue('misc', 'containerClass', '');
    }
}
