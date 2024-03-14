<?php

// Exit if accessed directly
if( !defined( 'ABSPATH') ) exit();

require_once WP_JSSOR_SLIDER_PATH . 'lib/css/Parser.php';

/**
 * help methods to build slider
 * @link   https://www.jssor.com
 * @version 1.0
 * @author jssor
 */
class WjsslSliderBuildHelper
{
    const RegSkinTemplate = '/\{\{(?:(?!\{\{).)*[^}]}}/';
    const RegRgbaColor = '/rgba\s*\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*((\d+(\.\d+)?)|(\.\d+))\s*\)/';
    const RegRgbColor = '/rgb\s*\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})\s*\)/';

    const RegMultiSlash = '/\/[\/]+/';

    private static $FONT_CATEGORY_MAP = array('display' => 'cursive', 'handwriting' => 'cursive');

    public static function ToSafeFontCategory($category)
    {
        $category = strtolower($category);

        if (array_key_exists($category, self::$FONT_CATEGORY_MAP))
        {
            $category = self::$FONT_CATEGORY_MAP[$category];
        }

        return $category;
    }

    public static function ToSafeFontName(WjsslFontInfo $fontInfo)
    {
        $fontName = $fontInfo->family;

        if(preg_match_all('/\'/', $fontName, $matches)) {
            if(count($matches[0]) % 2 !== 0) {
                $fontName = str_replace('\'', '\\\'', $fontName);
            }
        }

        if(preg_match_all('/"/', $fontName, $matches)) {
            if(count($matches[0]) % 2 !== 0) {
                $fontName = str_replace('"', '\\\'', $fontName);
            }
            else {
                $fontName = str_replace('"', '\'', $fontName);
            }
        }

        if (strpos($fontName, ' ') !== false && strpos($fontName, '\'') === false)
        {
            $fontName = '\'' . $fontName . '\'';
        }

        if (!WjsslSliderBuildHelper::EmptyOrWhiteSpace($fontInfo->category))
        {
            $fontCategory = WjsslSliderBuildHelper::ToSafeFontCategory($fontInfo->category);
            $fontName = $fontName . ',' . $fontCategory;
        }

        return $fontName;
    }

    public static function HasValue($value)
    {
        return isset($value) && !is_null($value);
    }

    public static function EmptyOrWhiteSpace($value)
    {
        return !isset($value) || empty($value) || ctype_space($value);
    }

    public static function BuildSlideoTransitionVariableName($otpId)
    {
        return self::BuildSliderIdString($otpId, WjsslEnumSliderIdType::ContainerId) . "_SlideoTransitions";
    }

    public static function BuildSlideshowTransitionVariableName($otpId)
    {
        return self::BuildSliderIdString($otpId, WjsslEnumSliderIdType::ContainerId) . "_SlideshowTransitions";
    }

    private static function BuildSafeName($name)
    {
        if (empty($name))
            throw new InvalidArgumentException('Invalid argument $name');

        return preg_replace('/^0-9A-Za-z/', '_', $name);
    }

    public static function BuildPercentage($percentX, $percentY)
    {
        $percentage = null;

        $x = 50;
        $y = 50;

        if (isset($percentX) && !is_null($percentX))
        {
            $x = $percentX;
        }
        if (isset($percentY) && !is_null($percentY))
        {
            $y = $percentY;
        }

        if ($x != 50 || $y != 50)
        {
            $percentage = "$x% $y%";
        }

        return $percentage;
    }

    public static function BuildShadowCss($shadowInfo)
    {
        $shadowOffsetX = $shadowInfo->x;
        $shadowOffsetY = $shadowInfo->y;
        $shadowBlur = $shadowInfo->blur;

        if(empty($shadowOffsetX)) {
            $shadowOffsetX = 0;
        }

        if(empty($shadowOffsetY)) {
            $shadowOffsetY = 0;
        }

        if(empty($shadowBlur)) {
            $shadowBlur = 0;
        }

        $css = '';

        $css .= $shadowOffsetX . 'px ' . $shadowOffsetY . 'px';

        if (!empty($shadowBlur))
        {
            $css .= ' ' . $shadowBlur . 'px';
        }

        if(WjsslSliderBuildHelper::HasValue($shadowInfo->color))
        {
            $css .= ' ' . $shadowInfo->color;
        }

        if(WjsslSliderBuildHelper::HasValue($shadowInfo->type))
        {
            $css .= ' ' . $shadowInfo->type;
        }

        return $css;
    }


    public static function BuildSliderIdString($otpId, $idType)
    {
        $id = "wp_jssor_";

        if (!empty($otpId))
        {
            $id .= WjsslSliderBuildHelper::BuildSafeName($otpId); ;
        }
        else
        {
            $id .= "1";
        }

        switch ($idType)
        {
            case WjsslEnumSliderIdType::OptionsId:
                $id .= "_options";
                break;
            case WjsslEnumSliderIdType::InstanceVariableId:
                $id .= "_slider";
                break;
            case WjsslEnumSliderIdType::InitStatementId:
                $id .= "_slider_init";
                break;
            case WjsslEnumSliderIdType::SlideshowTransitionVariableId:
                $id .= "_SlideshowTransitions";
                break;
            case WjsslEnumSliderIdType::CaptionTransitionVariableId:
                $id .= "_CaptionTransitions";
                break;
        }

        return $id;
    }

    public static function ToSafeHtml($html)
    {
        $htmlDocument = new DOMDocument('1.0', 'UTF-8');

        $nodes = self::LoadHTML($html, $htmlDocument);

        foreach($nodes as $node)
        {
            $htmlDocument->appendChild($node);
        }

        return $htmlDocument->saveHTML();
    }

    private static function CalculateTemplateItemValue($skinTemplateItemInfo, array $values, array $images = null, array $contents = null)
    {
        $templateItemValue = null;

        if (isset($skinTemplateItemInfo['v']) && !is_null($skinTemplateItemInfo['v']))
        {
            $templateItemValue = $values[$skinTemplateItemInfo['v']];

            if (
                (isset($skinTemplateItemInfo['a']) && !is_null($skinTemplateItemInfo['a']))
                ||
                (isset($skinTemplateItemInfo['b']) && !is_null($skinTemplateItemInfo['b']))
                ||
                (isset($skinTemplateItemInfo['f']) && !is_null($skinTemplateItemInfo['f']))
            ) {
                if (empty($skinTemplateItemInfo['a']))
                    $skinTemplateItemInfo['a'] = 0;

                if (empty($skinTemplateItemInfo['b']))
                    $skinTemplateItemInfo['b'] = 0;

                if (!isset($skinTemplateItemInfo['f']) || is_null($skinTemplateItemInfo['f']))
                    $skinTemplateItemInfo['f'] = 1;

                $templateItemValueDecimal = (float)$templateItemValue;

                $beforeAdjustValue = $skinTemplateItemInfo['b'];
                $afterAdjustValue = $skinTemplateItemInfo['a'];
                $adjustFactor = $skinTemplateItemInfo['f'];

                $templateItemValueDecimal = ($templateItemValueDecimal + $beforeAdjustValue) * $adjustFactor + $afterAdjustValue;

                if (isset($skinTemplateItemInfo['t']) && $skinTemplateItemInfo['t'] == 1)
                {
                    $templateItemValueDecimal = round($templateItemValueDecimal);
                }
                else
                {
                    $templateItemValueDecimal = round($templateItemValueDecimal, 1);
                }

                $templateItemValue = $templateItemValueDecimal;
            }
        }
        else if (isset($skinTemplateItemInfo['i']) && !is_null($skinTemplateItemInfo['i']))
        {
            $image = null;
            $index = $skinTemplateItemInfo['i'];
            if ($index < count($images))
                $image = $images[$index];

            $templateItemValue = $image;
        }
        else if (isset($skinTemplateItemInfo['c']) && !is_null($skinTemplateItemInfo['c']))
        {
            $content = '';
            $index = $skinTemplateItemInfo['c'];
            if ($index < count($contents))
                $content = $contents[$index];

            $templateItemValue = WjsslSliderBuildHelper::ToSafeHtml($content);
        }

        return $templateItemValue;
    }

    public static function BuildFromSkinTemplate($template, array $values)
    {
        $stringBuilder = '';

        preg_match_all(WjsslSliderBuildHelper::RegSkinTemplate, $template, $matches, PREG_OFFSET_CAPTURE, 0);

        $countWrote = 0;

        foreach ($matches[0] as $match)
        {
            $value = $match[0];

            $stringBuilder .= substr($template, $countWrote, $match[1] - $countWrote);
            $countWrote = $match[1] + strlen($value);

            $json = substr($value, 1, strlen($value) - 2);

            // change '{v:0}' to '{"v":0}'
            $json = self::convert_to_json_string($json);

            $skinTemplateItemInfo = json_decode($json, true);

            $templateItemValue = WjsslSliderBuildHelper::CalculateTemplateItemValue($skinTemplateItemInfo, $values);

            if (WjsslSliderBuildHelper::HasValue($templateItemValue))
            {
                $stringBuilder .= $templateItemValue;
            }
        }

        $stringBuilder .= substr($template, $countWrote, strlen($template) - $countWrote);

        return $stringBuilder;
    }

    public static function LoadHTML($html, DOMDocument $htmlDocument)
    {
        $nodes = array();

        if(!empty($html))
        {
            $tempDocument = new DOMDocument('1.0', 'UTF-8');
            @$tempDocument->loadHTML('<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"/></head><body>' . $html . '</body></html>');

            $bodyNode = $tempDocument->getElementsByTagName("body")->item(0);
            $bodyNode = $htmlDocument->importNode($bodyNode, true);

            foreach($bodyNode->childNodes as $childNode)
            {
                array_push($nodes, $childNode->cloneNode(true));
            }
        }

        return $nodes;
    }

    public static function ClearContentLink($htmlNodes, DOMDocument $htmlDocument) {
        $newHtmlNodes = array();

        foreach($htmlNodes as $htmlNode)
        {
            //XML_ELEMENT_NODE
            if($htmlNode->nodeType == 1 && strtolower($htmlNode->tagName) == 'a') {
                $spanNode = $htmlDocument->createElement('span');

                foreach($htmlNode->attributes as $attribute) {
                    $attributeName = strtolower($attribute->nodeName);
                    if($attributeName != "href" && $attributeName != "target") {
                        $spanNode->setAttribute($attributeName, $attribute->nodeValue);
                    }
                }

                foreach($htmlNode->childNodes as $childNode) {
                    $spanNode->appendChild($childNode);
                }

                $newHtmlNodes[] = $spanNode;
            }
            else {
                $newHtmlNodes[] = $htmlNode;
            }
        }

        return $newHtmlNodes;
    }

    public static function BuildFromThumbnailItemTemplate(DOMDocument $htmlDocument, $template, array $values, array $images, array $contents)
    {
        if (empty($images))
            $images = array();

        if (empty($contents))
            $contents = array();

        $stringBuilder = '';

        preg_match_all(WjsslSliderBuildHelper::RegSkinTemplate, $template, $matches, PREG_OFFSET_CAPTURE, 0);

        $countWrote = 0;

        foreach ($matches[0] as $match)
        {
            $value = $match[0];

            $stringBuilder .= substr($template, $countWrote, $match[1] - $countWrote);
            $countWrote = $match[1] + strlen($value);

            $json = substr($value, 1, strlen($value) - 2);

            // change '{v:0}' to '{"v":0}'
            $json = self::convert_to_json_string($json);

            $skinTemplateItemInfo = json_decode($json, true);

            $templateItemValue = WjsslSliderBuildHelper::CalculateTemplateItemValue($skinTemplateItemInfo, $values, $images, $contents);

            if (WjsslSliderBuildHelper::HasValue($templateItemValue))
            {
                $stringBuilder .= $templateItemValue;
            }
        }

        $stringBuilder .= substr($template, $countWrote, strlen($template) - $countWrote);

        return $stringBuilder;
    }

    private static function convert_to_json_string($json)
    {
        // change '{v:0}' to '{"v":0}'
        $json = str_replace(array("\n","\r"),"",$json);
        $json = preg_replace('/([{,]+)(\s*)([^"]+?)\s*:/','$1"$3":',$json);
        $json = preg_replace('/:\s*(\.\d+?\s*[},\]])/', ':0$1', $json);
        $json = preg_replace('/:\s*-(\.\d+?\s*[},\]])/', ':-0$1', $json);
        return $json;
    }

    public static function FindNode($nodes, $attrValue, $attrName = "u", $deep = true)
    {
        $htmlNode = null;

        foreach ($nodes as $tempNode)
        {
            if($tempNode->hasAttributes())
            {
                if ($tempNode->getAttribute($attrName) == $attrValue)
                {
                    $htmlNode = $tempNode;
                }
                else if ($tempNode->getAttribute('data-' . $attrName) == $attrValue)
                {
                    $htmlNode = $tempNode;
                }

                if (WjsslSliderBuildHelper::HasValue($htmlNode))
                    break;
            }

            if($htmlNode == null && $deep && $tempNode->hasChildNodes())
            {
                $htmlNode = WjsslSliderBuildHelper::FindNode($tempNode->childNodes, $attrValue, $attrName, $deep);
            }
        }

        return $htmlNode;
    }

    public static function SetScalePos(DOMElement $htmlNode, $scalePos, $excludeOrientation, $posTop, $posRight, $posBottom, $posLeft)
    {
        if (WjsslSliderBuildHelper::HasValue($scalePos))
        {
            if (empty($excludeDirection))
                $excludeDirection = WjsslEnumOrientation::None;

            if (($excludeDirection & WjsslEnumOrientation::Horizontal) == WjsslEnumOrientation::None)
            {
                if (WjsslSliderBuildHelper::HasValue($posRight))
                {
                    $htmlNode->setAttribute("data-scale-right", $scalePos);
                }
                else if (WjsslSliderBuildHelper::HasValue($posLeft))
                {
                    $htmlNode->setAttribute("data-scale-left", $scalePos);
                }
            }

            if (($excludeDirection & WjsslEnumOrientation::Vertical) == WjsslEnumOrientation::None)
            {
                if (WjsslSliderBuildHelper::HasValue($posBottom))
                {
                    $htmlNode->setAttribute("data-scale-bottom", $scalePos);
                }
                else if (WjsslSliderBuildHelper::HasValue($posTop))
                {
                    $htmlNode->setAttribute("data-scale-top", $scalePos);
                }
            }
        }
    }

    public static function ToCssText(WjsslCssDeclarationBlock $cssDeclarations)
    {
        return $cssDeclarations->renderDeclarations(new WjsslCssOutputFormat());
    }

    public static function SetCssPixel(WjsslCssDeclarationBlock $cssDeclarations, $name, $value)
    {
        $cssDeclarations->removeRule($name);

        if (WjsslSliderBuildHelper::HasValue($value))
        {
            WjsslSliderBuildHelper::SetCssValue($cssDeclarations, $name, $value . 'px');
        }
    }

    public static function SetCssValue(WjsslCssDeclarationBlock $cssDeclarations, $name, $value)
    {
        $cssDeclarations->removeRule($name);

        if (!empty($value) && !ctype_space($value))
        {
            $rule = new WjsslCssRule($name);
            $rule->setValue($value);
            $cssDeclarations->addRule($rule);
        }
    }

    #region css

    public static function GeCssBlock($skin)
    {
        $cssBlock = null;

        if (WjsslSliderBuildHelper::HasValue($skin))
        {
            $cssBlock = $skin->css;
        }
        return $cssBlock;
    }

    public static function GetSkinId($runtimeSkinDefinition, $skin)
    {
        $skinId = $skin->id;

        if(isset($skin->defaultValue))
        {
            if ($runtimeSkinDefinition->itemWidth != $skin->defaultValue->itemWidth || $runtimeSkinDefinition->itemHeight != $skin->defaultValue->itemHeight)
            {
                $skinId = $skinId . '-' . $runtimeSkinDefinition->itemWidth . '-' . $runtimeSkinDefinition->itemHeight;
            }
        }

        return $skinId;
    }

    /**
     * Alter '@Import/' format url to jssorres format url for all urls in the slider
     * Alter '/path/to/file.ext' format url to jssorres format url, or local url for all urls in skin templates
     * @param string $url
     * @param boolean $importOnly
     * @return boolean
     */
    public static function AlterJssorRes_Url(&$url, $importOnly) {
        $altered = false;

        if(!empty($url)) {
            if(strpos($url, '@Import/') === 0) {
                $url = WP_Jssor_Slider_Utils::import_url_to_jssorres_url($url);
                $altered = true;
            }
            else if(!$importOnly) {
                //alter urls in skin templates
                //ignore kind or url like //domain.com/path/filename.ext
                if(preg_match('/^\/[^\/]/', $url)) {
                    //Alter '/path/to/file.ext' format url to jssorres format url, or local url for all urls in skin templates
                    $jssor_res_info = WP_Jssor_Slider_Utils::to_jssor_res_info('https://www.jssor.com' . $url);
                    if($jssor_res_info->is_valid) {
                        $local_res_info = WP_Jssor_Slider_Utils::to_local_res_info($jssor_res_info->local_url);

                        if($local_res_info->exists()) {
                            $url = $local_res_info->path;
                        }
                        else {
                            $url = WP_Jssor_Slider_Utils::import_url_to_jssorres_url('@Import/' . $jssor_res_info->remote_url);
                        }

                        $altered = true;
                    }
                }
            }
        }

        return $altered;
    }

    private static function AlterJssorRes_CssValue($cssValue, $importOnly = false)
    {
        $altered = false;

        if($cssValue instanceof WjsslCssURL)
        {
            $cssUrl = $cssValue->getURL();
            $url = $cssUrl->getString();
            if(WjsslSliderBuildHelper::AlterJssorRes_Url($url, $importOnly))
            {
                $altered = true;
                $cssUrl->setString($url);
            }
        }
        else if(is_array($cssValue))
        {
            foreach($cssValue as $cssValueNested)
            {
                $altered = $altered || WjsslSliderBuildHelper::AlterJssorRes_CssValue($cssValueNested, $importOnly);
            }
        }

        return $altered;
    }

    public static function AlterJssorRes_CssBlocks($cssDeclarationBlocks, $importOnly = false)
    {
        $altered = false;

        foreach($cssDeclarationBlocks as $cssDeclarationBlock)
        {
            $cssRules = $cssDeclarationBlock->getRules();

            foreach($cssRules as $cssRule)
            {
                $cssValues = $cssRule->getValues();

                foreach($cssValues as $cssValue)
                {
                    $altered = $altered || WjsslSliderBuildHelper::AlterJssorRes_CssValue($cssValue, $importOnly);
                }
            }
        }

        return $altered;
    }

    public static function AlterJssorRes_Css($cssText, $importOnly = false)
    {
        $newCssText = $cssText;

        if(WP_Jssor_Slider_Utils::is_css_contains_url($cssText)) {
            $cssParser = new WjsslCssParser($cssText);
            $cssDocument = $cssParser->parse();

            $declarationBlocks = $cssDocument->getAllDeclarationBlocks();

            //alter image urls
            if(WjsslSliderBuildHelper::AlterJssorRes_CssBlocks($declarationBlocks, $importOnly))
            {
                $newCssText = $cssDocument->render();
            }
        }

        return $newCssText;
    }

    private static function AlterJssorRes_HtmlAttribute(DOMElement $htmlElement, $attributeName, $importOnly)
    {
        $src = $htmlElement->getAttribute($attributeName);

        if(!empty($src))
        {
            $dataThumbnailSize = $htmlElement->getAttribute('data-tsize');
            if (!WjsslSliderBuildHelper::EmptyOrWhiteSpace($dataThumbnailSize))
            {
                $dataThumbnailSize = self::convert_to_json_string($dataThumbnailSize);
                $thumbnailSize = json_decode($dataThumbnailSize, true);
                $width = $thumbnailSize['w'];
                $height = $thumbnailSize['h'];

                $htmlElement->removeAttribute('data-tsize');

                //alter thumbnail url with size
                $canAlter = false;

                if(strpos($src, '@Import/') === 0)
                {
                    //can alter if src is @Import url
                    $src = substr($src, 8);
                    $canAlter = true;
                }
                else
                {
                    //can alter if src is self site url
                    $canAlter = true;
                    $local_res_info = WP_Jssor_Slider_Utils::to_local_res_info($src);
                    if ($local_res_info->is_valid) {
                        $src = $local_res_info->local_url;

                        if($local_res_info->under_upload_dir) {
                            $upload = wp_upload_dir();
                            $thumb_rel_path = WP_Jssor_Slider_Utils::get_thumb_path($local_res_info->upload_rel_path, $width, $height);

                            $thumb_url = $upload['baseurl'] . '/' . $thumb_rel_path;
                            $thumb_local_res_info = WP_Jssor_Slider_Utils::to_local_res_info($thumb_url);

                            if($thumb_local_res_info->exists()) {
                                $htmlElement->setAttribute($attributeName, $thumb_local_res_info->path);
                                return true;
                            }

                            //if(file_exists($upload['basedir'] . '/' . $thumb_rel_path)) {
                            //    //actual thumbnail image found
                            //    $thumb_url = $upload['baseurl'] . '/' . $thumb_rel_path;

                            //    $thumb_local_res_info = WP_Jssor_Slider_Utils::to_local_res_info($thumb_url);

                            //    if($thumb_local_res_info->is_valid) {

                            //    }

                            //    $htmlElement->setAttribute($attributeName, $thumb_url);

                            //    return true;
                            //}
                        }
                    }
                }

                if($canAlter)
                {
                    //convert to thumbnail url
                    $src = WP_Jssor_Slider_Utils::format_crop_img_url($src, $width, $height);
                    $htmlElement->setAttribute($attributeName, $src);

                    return true;
                }
            }
            else if(WjsslSliderBuildHelper::AlterJssorRes_Url($src, $importOnly))
            {
                //alter jssor res url
                $htmlElement->setAttribute($attributeName, $src);

                return true;
            }
        }

        return false;
    }

    public static function AlterJssorRes_Html(DOMNode $htmlNode, $importOnly = false)
    {
        if($htmlNode->hasAttributes())    //XML_ELEMENT_NODE
        {
            //alter image src urls
            $srcAltered = WjsslSliderBuildHelper::AlterJssorRes_HtmlAttribute($htmlNode, 'src', $importOnly)
            || WjsslSliderBuildHelper::AlterJssorRes_HtmlAttribute($htmlNode, 'data-src', $importOnly)
            || WjsslSliderBuildHelper::AlterJssorRes_HtmlAttribute($htmlNode, 'src2', $importOnly)
            || WjsslSliderBuildHelper::AlterJssorRes_HtmlAttribute($htmlNode, 'data-src2', $importOnly)
            || WjsslSliderBuildHelper::AlterJssorRes_HtmlAttribute($htmlNode, 'xlink:href', $importOnly);

            //alter style
            $cssText = $htmlNode->getAttribute("style");

            if(WP_Jssor_Slider_Utils::is_css_contains_url($cssText)) {
                $cssDeclarations = WjsslCssParser::ParseDeclarations($cssText);

                if(WjsslSliderBuildHelper::AlterJssorRes_CssBlocks(array($cssDeclarations), $importOnly))
                {
                    $htmlNode->setAttribute("style", WjsslSliderBuildHelper::ToCssText($cssDeclarations));
                }
            }
        }

        if($htmlNode->hasChildNodes())
        {
            foreach($htmlNode->childNodes as $childNode)
            {
                WjsslSliderBuildHelper::AlterJssorRes_Html($childNode, $importOnly);
            }
        }
    }

    public static function GetCssBlocks(WjsslRuntimeLayouts $runtimeLayouts)
    {
        $cssBlocks = array();

        //loading screen css
        if(WjsslSliderBuildHelper::HasValue($runtimeLayouts->loading))
        {
            $cssBlock = WjsslSliderBuildHelper::GeCssBlock($runtimeLayouts->loading->Skin);
            if (!empty($cssBlock))
            {
                $skinId = WjsslSliderBuildHelper::GetSkinId($runtimeLayouts->loading, $runtimeLayouts->loading->Skin);
                $cssBlock = WjsslSliderBuildHelper::BuildFromSkinTemplate($cssBlock, array($skinId, $runtimeLayouts->loading->itemWidth, $runtimeLayouts->loading->itemHeight));

                $cssBlock = WjsslSliderBuildHelper::AlterJssorRes_Css($cssBlock);
                array_push($cssBlocks, $cssBlock);
            }
        }

        //bullets css
        if (WjsslSliderBuildHelper::HasValue($runtimeLayouts->bullets))
        {
            $cssBlock = WjsslSliderBuildHelper::GeCssBlock($runtimeLayouts->bullets->Skin);
            if (!empty($cssBlock))
            {
                $cssBlock = WjsslSliderBuildHelper::AlterJssorRes_Css($cssBlock);
                array_push($cssBlocks, $cssBlock);
            }
        }

        //arrows css
        if (WjsslSliderBuildHelper::HasValue($runtimeLayouts->arrows))
        {
            $cssBlock = WjsslSliderBuildHelper::GeCssBlock($runtimeLayouts->arrows->Skin);
            if (!empty($cssBlock))
            {
                $cssBlock = WjsslSliderBuildHelper::AlterJssorRes_Css($cssBlock);
                array_push($cssBlocks, $cssBlock);
            }
        }

        //thumbnails css
        if (WjsslSliderBuildHelper::HasValue($runtimeLayouts->thumbnails))
        {
            $cssBlock = WjsslSliderBuildHelper::GeCssBlock($runtimeLayouts->thumbnails->Skin);
            if (!empty($cssBlock))
            {
                $skinId = WjsslSliderBuildHelper::GetSkinId($runtimeLayouts->thumbnails, $runtimeLayouts->thumbnails->Skin);
                $cssBlock = WjsslSliderBuildHelper::BuildFromSkinTemplate($cssBlock, array($skinId, $runtimeLayouts->thumbnails->itemWidth, $runtimeLayouts->thumbnails->itemHeight));

                $cssBlock = WjsslSliderBuildHelper::AlterJssorRes_Css($cssBlock);
                array_push($cssBlocks, $cssBlock);
            }
        }

        return $cssBlocks;
    }

    #endregion
}

abstract class WjsslEnumSliderIdType
{
    const Identity = 0;
    const ContainerId = 1;
    const InstanceVariableId = 2;
    const OptionsId = 3;
    const InitStatementId = 4;
    const SlideshowTransitionVariableId = 5;
    const CaptionTransitionVariableId = 6;
}
