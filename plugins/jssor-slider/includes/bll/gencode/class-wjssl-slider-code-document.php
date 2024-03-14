<?php
// Exit if accessed directly
if( !defined( 'ABSPATH') ) exit();
require_once WP_JSSOR_SLIDER_PATH . 'lib/css/Parser.php';
/**
 * build slider output
 * @link   https://www.jssor.com
 * @version 1.0
 * @author jssor
 */
class WjsslSliderCodeDocument
{
    #region private member variables
    private $_DesignTimeDocument;
    private $_RuntimeDocument;
    private $_HtmlDocument;
    //settings
    private $_UniqueId;
    private $_SliderId;
    private $_SliderName;
    private $_IncludeHtmlBodyCss;
    private $_IncludeFullSizeWrapperNode;
    private $_IncludeFullWidthWrapperNode;
    //array of web font used
    private $_WebFonts;
    //generated nodes
    private $_ArraySlideNodes;          //an array of slide nodes
    private $_ArraySkinCssBlocks;       //an array of skin css blocks, including loading, bullet, arrow, thumbnail skin
    private $_CommentSliderBeginNode;
    //private $_CommentGeneratorNode;
    //private $_JssorSliderLibraryScriptNode;
    private $_JssorSliderInitializeScriptNode;
    private $_CssNode;
    private $_FullSizeWrapperNode;
    private $_FullWidthWrapperNode;
    private $_SliderNode;
    private $_LoadingScreenNodes;
    private $_SlidesNode;
    private $_ThumbnailNavigatorNodes;
    private $_BulletNavigatorNodes;
    private $_ArrowNavigatorNodes;
    private $_JssorSliderStatementScriptNode;
    private $_CommentSliderEndNode;
    private $_DisableScrollingBounceEffect;
    #endregion
    #region constructor
    public function __construct(WjsslDesignTimeDocument $designTimeDocument, WjsslRuntimeDocument $runtimeDocument, $unique_id, $slider_id, $slider_name)
    {
        $this->_DesignTimeDocument = $designTimeDocument;
        $this->_RuntimeDocument = $runtimeDocument;
        $this->_UniqueId = $unique_id;
        $this->_SliderId = $slider_id;
        $this->_SliderName = $slider_name;
        $this->_HtmlDocument = new DOMDocument('1.0', 'UTF-8');
        $this->_WebFonts = array();
        if ($designTimeDocument->layouts != null && $designTimeDocument->layouts->layout != null && $designTimeDocument->layouts->layout->rspScaleTo != null)
        {
            if (($designTimeDocument->layouts->layout->rspScaleTo & WjsslEnumResponsiveScaleMode::HtmlBodyCss) == WjsslEnumResponsiveScaleMode::HtmlBodyCss)
            {
                $this->_IncludeHtmlBodyCss = true;
            }
            if (($designTimeDocument->layouts->layout->rspScaleTo & WjsslEnumResponsiveScaleMode::Wrapper) == WjsslEnumResponsiveScaleMode::Wrapper)
            {
                $this->_IncludeFullSizeWrapperNode = true;
            }
            else if(($designTimeDocument->layouts->layout->rspScaleTo & WjsslEnumResponsiveScaleMode::Height) == 0) {
                $this->_IncludeFullWidthWrapperNode = true;
            }
        }
        if ($designTimeDocument->options != null)
        {
            $this->_Title = $designTimeDocument->options->otpTitle;
        }
    }
    public function getHtmlDocument()
    {
        return $this->_HtmlDocument;
    }
    #endregion
    #region properties
    private function getContainerId()
    {
        return WjsslSliderBuildHelper::BuildSliderIdString($this->_UniqueId, WjsslEnumSliderIdType::ContainerId);
    }
    private function getInstanceVariableId()
    {
        return WjsslSliderBuildHelper::BuildSliderIdString($this->_UniqueId, WjsslEnumSliderIdType::InstanceVariableId);
    }
    private function getOptionsId()
    {
        return WjsslSliderBuildHelper::BuildSliderIdString($this->_UniqueId, WjsslEnumSliderIdType::OptionsId);
    }
    private function getInitStatementId()
    {
        return WjsslSliderBuildHelper::BuildSliderIdString($this->_UniqueId, WjsslEnumSliderIdType::InitStatementId);
    }
    private function getSlideshowTransitionVariableId()
    {
        return WjsslSliderBuildHelper::BuildSliderIdString($this->_UniqueId, WjsslEnumSliderIdType::SlideshowTransitionVariableId);
    }
    private function getCaptionTransitionVariableId()
    {
        return WjsslSliderBuildHelper::BuildSliderIdString($this->_UniqueId, WjsslEnumSliderIdType::CaptionTransitionVariableId);
    }
    #endregion
    #region build slider
    private function BuildPercentAttribute(DOMElement $htmlNode, $attributeName, $percentX = null, $percentY = null)
    {
        $percentage = WjsslSliderBuildHelper::BuildPercentage($percentX, $percentY);
        if (!empty($percentage))
        {
            $htmlNode->setAttribute($attributeName, $percentage);
        }
        return $percentage;
    }
    private function BuildImageNode($url, $mainImage, WjsslRuntimeDocument $runtimeDocument, DOMDocument $htmlDocument)
    {
        $imageNode = null;
        if (!WjsslSliderBuildHelper::EmptyOrWhiteSpace($url))
        {
            $imageNode = $htmlDocument->createElement("img");
            if ($mainImage)
            {
                $imageNode->setAttribute("data-u", "image");
            }
            $srcAttributeName = !WjsslSliderBuildHelper::HasValue($runtimeDocument->Options->LazyLoading) ? "src" : "data-src2";
            WjsslSliderBuildHelper::AlterJssorRes_Url($url, true);
            $imageNode->setAttribute($srcAttributeName, $url);
        }
        return $imageNode;
    }
    private function BuildLayerNode(WjsslRuntimeLayer $runtimeLayer, $allowLink, WjsslRuntimeDocument $runtimeDocument, DOMDocument $htmlDocument)
    {
        $layerNode = null;
        $layerContainerNode = null;

        $isLinkLayer = $allowLink && WjsslSliderBuildHelper::HasValue($runtimeLayer->linkEx);
        $allowLinkContent = $allowLink && !$isLinkLayer;
        #region create layer
        switch ($runtimeLayer->type)
        {
            case WjsslEnumLayerType::Image:
                $imageNode = $this->BuildImageNode($runtimeLayer->image, false, $runtimeDocument, $htmlDocument);
                if ($isLinkLayer)
                {
                    $layerNode = $htmlDocument->createElement("a");
                    if (!empty($imageNode))
                    {
                        $imageNode->setAttribute("style", "width:100%;height:100%;");
                        $imageNode->setAttribute("border", "0");
                        $layerNode->appendChild($imageNode);
                    }
                }
                else
                {
                    $layerNode = $imageNode;
                }
                break;
            case WjsslEnumLayerType::Panel:
                $layerNode = $htmlDocument->createElement($isLinkLayer ? "a" : "div");
                break;
            case WjsslEnumLayerType::Content:
                $layerNode = $htmlDocument->createElement($isLinkLayer ? "a" : "div");
                $htmlNodes = WjsslSliderBuildHelper::LoadHTML($runtimeLayer->content, $htmlDocument);
                if(!$allowLinkContent) {
                    //clear link inside link
                    $htmlNodes = WjsslSliderBuildHelper::ClearContentLink($htmlNodes, $htmlDocument);
                }
                foreach($htmlNodes as $htmlNode)
                {
                    WjsslSliderBuildHelper::AlterJssorRes_Html($htmlNode, true);
                    $layerNode->appendChild($htmlNode);
                }
                break;
        }

        $layerContainerNode = $layerNode;

        //if(runtimeLayer.pDepth != null)
        //{
        //    layerContainerNode = htmlDocument.CreateElement("div");
        //    layerContainerNode.SetAttributeValue("data-ts", "preserve-3d");
        //    string css = string.Format("position:absolute;top:0px;left:0px;width:{0}px;height:{1}px;", runtimeLayer.width, runtimeLayer.height);
        //    layerContainerNode.SetAttributeValue("style", css);
        //    layerNode.AppendChild(layerContainerNode);
        //}

        #endregion
        if (!empty($layerNode))
        {
            #region layer spec
            if ($isLinkLayer)
            {
                $layerNode->setAttribute("href", $runtimeLayer->linkEx->link);
                if (!WjsslSliderBuildHelper::EmptyOrWhiteSpace($runtimeLayer->linkEx->tgt))
                {
                    $layerNode->setAttribute("target", $runtimeLayer->linkEx->tgt);
                }
            }

            //class name
            if (!WjsslSliderBuildHelper::EmptyOrWhiteSpace($runtimeLayer->className))
            {
                $layerNode->setAttribute("class", $runtimeLayer->className);
            }

            //id
            if (!WjsslSliderBuildHelper::EmptyOrWhiteSpace($runtimeLayer->id))
            {
                $layerNode->setAttribute("id", $runtimeLayer->id);
            }

            #region attributes

            //transition
            if (WjsslSliderBuildHelper::HasValue($runtimeLayer->slideoTransitionIndex))
            {
                $layerNode->setAttribute("data-u", "caption");
                $layerNode->setAttribute("data-t", $runtimeLayer->slideoTransitionIndex);
                //if ($runtimeLayer->has3d)
                //{
                //    $layerNode->setAttribute("data-3d", "1");
                //}
            }
            if (WjsslSliderBuildHelper::HasValue($runtimeLayer->slideoMotionControlIndex))
            {
                $layerNode->setAttribute("data-c", $runtimeLayer->slideoMotionControlIndex);
            }
            //backface-visibility
            if (!WjsslSliderBuildHelper::EmptyOrWhiteSpace($runtimeLayer->backVisibility))
            {
                $layerNode->setAttribute("data-bf", $runtimeLayer->backVisibility);
            }
            //transform-origin
            if (!WjsslSliderBuildHelper::EmptyOrWhiteSpace($runtimeLayer->to))
            {
                $layerNode->setAttribute("data-to", $runtimeLayer->to);
            }

            //transform-style: preserve-3d

            if (WjsslSliderBuildHelper::HasValue($runtimeLayer->pDepth))
            {
                $layerNode->setAttribute("data-ts", "flat");
            }
            else if($runtimeLayer->has3dChild || $runtimeLayer->hasTransformChild)
            {
                $layerNode->setAttribute("data-ts", "preserve-3d");
            }

            #region animation conditions

            if($runtimeLayer->hasActionAnimation())
            {
                if($runtimeLayer->conditions->play != null)
                {
                    $layerNode->setAttribute("data-play", $runtimeLayer->conditions->play);

                    #region rollback

                    if ($runtimeLayer->conditions->rollback != null)
                    {
                        $layerNode->setAttribute("data-rollback", $runtimeLayer->conditions->rollback);
                    }

                    if ($runtimeLayer->conditions->idle != null)
                    {
                        $layerNode->setAttribute("data-idle", $runtimeLayer->conditions->idle);
                    }

                    if ($runtimeLayer->conditions->group != null)
                    {
                        $layerNode->setAttribute("data-group", $runtimeLayer->conditions->group);
                    }

                    #endregion

                    #region misc

                    if ($runtimeLayer->conditions->rbSpeed != null)
                    {
                        $layerNode->setAttribute("data-speed", $runtimeLayer->conditions->rbSpeed);
                    }

                    if ($runtimeLayer->conditions->initial != null)
                    {
                        $layerNode->setAttribute("data-initial", $runtimeLayer->conditions->initial);
                    }

                    if ($runtimeLayer->conditions->pause != null)
                    {
                        $layerNode->setAttribute("data-pause", "1");
                    }

                    #endregion
                }
            }

            #endregion

            #region perspective/perspective origin attributes

            //perspective
            //perspective origin
            if (WjsslSliderBuildHelper::HasValue($runtimeLayer->pDepth)/* && $runtimeLayer->has3d*/)
            {
                $perspective = ($runtimeLayer->width + $runtimeLayer->height) * $runtimeLayer->pDepth / 2;
                $layerContainerNode->setAttribute("data-p", $perspective);
                $this->BuildPercentAttribute($layerContainerNode, "data-po", $runtimeLayer->poX, $runtimeLayer->poY);
            }

            #endregion

            //added since 8.9.0, 20180831
            if (!WjsslSliderBuildHelper::EmptyOrWhiteSpace($runtimeLayer->acclk))
            {
                $layerContainerNode->setAttribute("data-jssor-click", $runtimeLayer->acclk);
            }

            #endregion

            #region css
            {
                $borderBox = false;
                $css = $isLinkLayer ? "display:block; " : "";
                $css .= 'position:absolute;top:' . $runtimeLayer->y . 'px;left:' . $runtimeLayer->x . 'px;width:' . $runtimeLayer->width . 'px;height:' . $runtimeLayer->height . 'px;';

                //image max-width
                if($runtimeLayer->type == WjsslEnumLayerType::Image) {
                    $css .= 'max-width:' . $runtimeLayer->width . 'px;';
                }

                //blend
                if (!WjsslSliderBuildHelper::EmptyOrWhiteSpace($runtimeLayer->blend))
                {
                    $css .= 'mix-blend-mode:' . $runtimeLayer->blend . ';';
                }
                //isolation, obsolete
                //if (!WjsslSliderBuildHelper::EmptyOrWhiteSpace($runtimeLayer->isolation))
                //{
                //    $css .= 'isolation:' . $runtimeLayer->isolation . ';';
                //}
                //background color
                if (!WjsslSliderBuildHelper::EmptyOrWhiteSpace($runtimeLayer->bgColor))
                {
                    $css .= 'background-color:' . $runtimeLayer->bgColor . ';';
                }
                //border
                if (WjsslSliderBuildHelper::HasValue($runtimeLayer->border))
                {
                    $borderStyle = $runtimeLayer->border->style;
                    if(!WjsslSliderBuildHelper::EmptyOrWhiteSpace($borderStyle))
                    {
                        $borderBox = true;
                        $borderWidth = $runtimeLayer->border->width;
                        if(!WjsslSliderBuildHelper::HasValue($borderWidth))
                        {
                            $borderWidth = 1;
                        }
                        $borderColor = $runtimeLayer->border->color;
                        if (empty($borderColor) || ctype_space($borderColor))
                        {
                            $borderColor = "#000";
                        }
                        $css .= 'border:' . $borderWidth . 'px ' . $borderStyle . ' ' . $borderColor . ';';
                    }
                    //border-radius
                    $borderRadius = $runtimeLayer->border->radius;
                    if(WjsslSliderBuildHelper::HasValue($borderRadius))
                    {
                        $css .= 'border-radius:' . $borderRadius . 'px;';
                    }
                }
                //font-family
                if (WjsslSliderBuildHelper::HasValue($runtimeLayer->fontEx))
                {
                    $fontInfo = $runtimeLayer->fontEx;
                    $fontName = $fontInfo->family;
                    if (!WjsslSliderBuildHelper::EmptyOrWhiteSpace($fontName))
                    {
                        if (!WjsslSliderBuildHelper::EmptyOrWhiteSpace($fontInfo->url))
                        {
                            $this->_WebFonts[strtolower($fontName)] = $fontInfo;
                        }
                        $fontName = WjsslSliderBuildHelper::ToSafeFontName($fontInfo);
                        $css .= 'font-family:' . $fontName . ';';
                    }
                }
                else if (!WjsslSliderBuildHelper::EmptyOrWhiteSpace($runtimeLayer->font))
                {
                    $css .= 'font-family:' . $runtimeLayer->font . ';';
                }
                //font-size
                if (WjsslSliderBuildHelper::HasValue($runtimeLayer->fontSize))
                {
                    $css .= 'font-size:' . $runtimeLayer->fontSize . 'px;';
                }
                //font-weight
                if (!WjsslSliderBuildHelper::EmptyOrWhiteSpace($runtimeLayer->fontWeight))
                {
                    $css .= 'font-weight:' . $runtimeLayer->fontWeight . ';';
                }
                //color
                if (!WjsslSliderBuildHelper::EmptyOrWhiteSpace($runtimeLayer->color))
                {
                    $css .= 'color:' . $runtimeLayer->color . ';';
                }
                if($runtimeLayer->italic === true)
                {
                    $css .= "font-style:italic;";
                }
                //line-height
                if (WjsslSliderBuildHelper::HasValue($runtimeLayer->lineHeightEx))
                {
                    $css .= 'line-height:' . $runtimeLayer->lineHeightEx . ';';
                }
                //letter-spacing
                if (WjsslSliderBuildHelper::HasValue($runtimeLayer->letterSpacing))
                {
                    $css .= 'letter-spacing:' . $runtimeLayer->letterSpacing . 'em;';
                }
                //text-align
                if (!WjsslSliderBuildHelper::EmptyOrWhiteSpace($runtimeLayer->textAlign))
                {
                    $css .= 'text-align:' . $runtimeLayer->textAlign . ';';
                }
                //changed since 6->0->0 20170908
                //padding
                $paddingY = $runtimeLayer->paddingY;
                $paddingT = $runtimeLayer->paddingT;
                $paddingM = $runtimeLayer->paddingM;
                $paddingX = $runtimeLayer->paddingX;
                if (!empty($paddingY) || !empty($paddingT) || !empty($paddingM) || !empty($paddingX))
                {
                    $borderBox = true;
                    if(empty($paddingY))
                    {
                        $paddingY = 0;
                    }
                    if(empty($paddingT))
                    {
                        $paddingT = 0;
                    }
                    if(empty($paddingM))
                    {
                        $paddingM = 0;
                    }
                    if(empty($paddingX))
                    {
                        $paddingX = 0;
                    }
                    $css .= 'padding:' . $paddingY . 'px ' . $paddingT . 'px ' . $paddingM . 'px ' . $paddingX . 'px;';
                }

                //added since 8.1.0, 20180629
                //text shadow
                if (WjsslSliderBuildHelper::HasValue($runtimeLayer->textShadow))
                {
                    $css .= 'text-shadow:' . WjsslSliderBuildHelper::BuildShadowCss($runtimeLayer->textShadow) . ';';
                }

                //added since 8.3.0, 20180712
                if (WjsslSliderBuildHelper::HasValue($runtimeLayer->boxShadow))
                {
                    $css .= 'box-shadow:' . WjsslSliderBuildHelper::BuildShadowCss($runtimeLayer->boxShadow) . ';';
                }

                //pointer events
                if (!WjsslSliderBuildHelper::EmptyOrWhiteSpace($runtimeLayer->ptrEvts))
                {
                    $css .= 'pointer-events:' . $runtimeLayer->ptrEvts . ';';
                }
                if ($borderBox)
                {
                    $css .= "box-sizing:border-box;";
                }
                //overflow
                $overflowX = $runtimeLayer->ofX;
                $overflowY = $runtimeLayer->ofY;
                if ($overflowX == $overflowY)
                {
                    if (!WjsslSliderBuildHelper::EmptyOrWhiteSpace($overflowX))
                    {
                        $css .= 'overflow:' . $overflowX . ';';
                    }
                }
                else {
                    if (!WjsslSliderBuildHelper::EmptyOrWhiteSpace($overflowX))
                    {
                        $css .= 'overflow-x:' . $overflowX . ';';
                    }
                    if (!WjsslSliderBuildHelper::EmptyOrWhiteSpace($overflowY))
                    {
                        $css .= 'overflow-y:' . $overflowY . ';';
                    }
                }
                //background image
                if (WjsslSliderBuildHelper::HasValue($runtimeLayer->bgImageEx))
                {
                    if(!WjsslSliderBuildHelper::EmptyOrWhiteSpace($runtimeLayer->bgImageEx->image)) {
                        $backgroundImageUrl = $runtimeLayer->bgImageEx->image;
                        WjsslSliderBuildHelper::AlterJssorRes_Url($backgroundImageUrl, true);
                        $css .= 'background-image:url(' . $backgroundImageUrl . ');';
                        if (!WjsslSliderBuildHelper::EmptyOrWhiteSpace($runtimeLayer->bgImageEx->repeat))
                        {
                            $css .= 'background-repeat:' . $runtimeLayer->bgImageEx->repeat . ';';
                        }
                    }
                    else if (WjsslSliderBuildHelper::HasValue($runtimeLayer->bgImageEx->gradient) && !WjsslSliderBuildHelper::EmptyOrWhiteSpace($runtimeLayer->bgImageEx->gradient->raw))
                    {
                        $css .= 'background-image:' . $runtimeLayer->bgImageEx->gradient->raw . ';';
                    }
                }

                if($borderBox && (WjsslSliderBuildHelper::HasValue($runtimeLayer->bgImageEx) || WjsslSliderBuildHelper::HasValue($runtimeLayer->bgColor)))
                {
                    $css .= 'background-clip:padding-box;';
                }

                //added since 8.9.0, 20180831
                if (!WjsslSliderBuildHelper::EmptyOrWhiteSpace($runtimeLayer->acclk))
                {
                    $css .= 'cursor:pointer;';
                }

                $layerNode->setAttribute("style", $css);
            }
            #endregion

            #region child elements

            if (!empty($runtimeLayer->children))
            {
                foreach ($runtimeLayer->children as $childRuntimeLayer)
                {
                    $childLayerNode = $this->BuildLayerNode($childRuntimeLayer, $allowLinkContent, $runtimeDocument, $htmlDocument);
                    if (!empty($childLayerNode))
                    {
                        $layerContainerNode->appendChild($childLayerNode);
                    }
                }
            }

            #endregion

            #endregion
        }
        return $layerNode;
    }
    private function BuildSlideNode($slideIndex, WjsslRuntimeSlide $runtimeSlide, WjsslRuntimeDocument $runtimeDocument, DOMDocument $htmlDocument)
    {
        $slideNode = null;
        $isLinkSlide = false;
        #region slide spec
        if (!WjsslSliderBuildHelper::EmptyOrWhiteSpace($runtimeSlide->link))
        {
            $isLinkSlide = true;
            $slideNode = $htmlDocument->createElement("a");
            $slideNode->setAttribute("href", $runtimeSlide->link);
            if (!WjsslSliderBuildHelper::EmptyOrWhiteSpace($runtimeSlide->tgt))
            {
                $slideNode->setAttribute("target", $runtimeSlide->tgt);
            }
        }
        else
        {
            $slideNode = $htmlDocument->createElement("div");
        }
        #region main image
        if (!WjsslSliderBuildHelper::EmptyOrWhiteSpace($runtimeSlide->image))
        {
            if (!is_null($runtimeSlide->fillMode))
            {
                $slideNode->setAttribute("data-fillmode", $runtimeSlide->fillMode);
            }


            #region imageNode

            $imageNode = $this->BuildImageNode($runtimeSlide->image, true, $runtimeDocument, $htmlDocument);

            if ($runtimeSlide->blur > 0)
            {
                $imageNode->setAttribute('data-expand', $runtimeSlide->blur * 2);
            }

            $imageCss = '';

            #region opacity

            if($runtimeSlide->opacity != null)
            {
                $imageCss .= 'opacity:' . $runtimeSlide->opacity . ';';
            }

            #endregion

            #region filter

            $filterValue = '';
            if ($runtimeSlide->blur > 0)
            {
                $filterValue = 'blur(' . $runtimeSlide->blur . 'px)';
            }

            if ($runtimeSlide->grayscale > 0)
            {
                if (!empty($filterValue))
                {
                    $filterValue .= ' ';
                }

                $filterValue .= 'grayscale(' . $runtimeSlide->grayscale * 100 . '%)';
            }

            if (!empty($filterValue))
            {
                $imageCss .= "filter:$filterValue;-webkit-filter:$filterValue;";
            }

            #endregion

            if(!empty($imageCss))
            {
                $imageNode->setAttribute('style', $imageCss);
            }

            $slideNode->appendChild($imageNode);

            #endregion
        }
        #endregion
        #region attributes
        //break index
        if (WjsslSliderBuildHelper::HasValue($runtimeSlide->slideoBreakIndex))
        {
            $slideNode->setAttribute("data-b", $runtimeSlide->slideoBreakIndex);
        }
        if (WjsslSliderBuildHelper::HasValue($runtimeSlide->idle))
        {
            $slideNode->setAttribute("data-idle", $runtimeSlide->idle);
        }
        #endregion

        #region background color
        $slideCss = '';
        //background-color & display css
        if (!WjsslSliderBuildHelper::EmptyOrWhiteSpace($runtimeSlide->bgColor))
        {
            $slideCss .= 'background-color:' . $runtimeSlide->bgColor . ';';
        }

        if (WjsslSliderBuildHelper::HasValue($runtimeSlide->bgGradient) && !WjsslSliderBuildHelper::EmptyOrWhiteSpace($runtimeSlide->bgGradient->raw))
        {
            $slideCss .= 'background-image:' . $runtimeSlide->bgGradient->raw . ';';
        }

        if (!WjsslSliderBuildHelper::EmptyOrWhiteSpace($slideCss))
        {
            $slideNode->setAttribute("style", $slideCss);
        }
        #endregion

        #endregion

        #region child elements

        $has3d = false;

        if(WjsslSliderBuildHelper::HasValue($runtimeSlide->layers)) {
            foreach ($runtimeSlide->layers as $runtimeLayer)
            {
                $layerNode = $this->BuildLayerNode($runtimeLayer, !$isLinkSlide, $runtimeDocument, $htmlDocument);
                if (!empty($layerNode))
                {
                    $slideNode->appendChild($layerNode);

                    $has3d = $has3d || $runtimeLayer->has3d || $runtimeLayer->has3dChild;
                }
            }
        }

        $runtimeThumbnails = $runtimeDocument->Layouts->thumbnails;

        if (!empty($runtimeThumbnails))
        {
            $thumbnailSkin = $runtimeThumbnails->Skin;
            if (!empty($thumbnailSkin))
            {
                $images = array( null );
                $contents = array( null );
                if (WjsslSliderBuildHelper::HasValue($runtimeSlide->thumb))
                {
                    if(WjsslSliderBuildHelper::HasValue($runtimeSlide->thumb->images))
                    {
                        $images = $runtimeSlide->thumb->images;
                    }
                    if(WjsslSliderBuildHelper::HasValue($runtimeSlide->thumb->contents))
                    {
                        $contents = $runtimeSlide->thumb->contents;
                    }
                }

                if (!WjsslSliderBuildHelper::EmptyOrWhiteSpace($runtimeSlide->image))
                {
                    foreach ($images as $i=>$image)
                    {
                        if (empty($image) || ctype_space($image))
                        {
                            $images[$i] = $image = $runtimeSlide->image;
                        }
                    }
                }

                $thumbnailNodeString = null;
                if (!WjsslSliderBuildHelper::EmptyOrWhiteSpace($thumbnailSkin->itemHtml)) {
                    $skinId = WjsslSliderBuildHelper::GetSkinId($runtimeThumbnails, $thumbnailSkin);
                    $thumbnailNodeString = WjsslSliderBuildHelper::BuildFromThumbnailItemTemplate($htmlDocument, $thumbnailSkin->itemHtml, array($skinId, $runtimeThumbnails->itemWidth, $runtimeThumbnails->itemHeight), $images, $contents);
                }

                $slideThumbnailNodes = WjsslSliderBuildHelper::LoadHTML($thumbnailNodeString, $htmlDocument);
                foreach($slideThumbnailNodes as $node)
                {
                    //alter thumbnail image res url with size
                    WjsslSliderBuildHelper::AlterJssorRes_Html($node, true);
                    $slideNode->appendChild($node);
                }
            }
        }

        #endregion

        #region perspective/perspective origin attribute

        if (WjsslSliderBuildHelper::HasValue($runtimeSlide->pDepth)/* && $has3d*/)
        {
            $perspective = ($runtimeDocument->Layouts->layout->slideWidth + $runtimeDocument->Layouts->layout->slideHeight) * $runtimeSlide->pDepth / 2;
            $slideNode->setAttribute("data-p", $perspective);
            $this->BuildPercentAttribute($slideNode, "data-po", $runtimeSlide->poX, $runtimeSlide->poY);
        }

        #endregion

        return $slideNode;
    }
    private function BuildSlidesNode(WjsslRuntimeLayout $runtimeLayout, WjsslRuntimeDocument $runtimeDocument, DOMDocument $htmlDocument)
    {
        $slidesNode = $htmlDocument->createElement("div");
        #region slides node $css
        $slidesContainerWidth = $runtimeLayout->slideWidth;
        $slidesContainerHeight = $runtimeLayout->slideHeight;
        if (WjsslSliderBuildHelper::HasValue($runtimeLayout->slidesWidth))
            $slidesContainerWidth = $runtimeLayout->slidesWidth;
        if (WjsslSliderBuildHelper::HasValue($runtimeLayout->slidesHeight))
            $slidesContainerHeight = $runtimeLayout->slidesHeight;
        $slidesContainerX = 0;
        $slidesContainerY = 0;
        if (WjsslSliderBuildHelper::HasValue($runtimeLayout->slidesX))
            $slidesContainerX = $runtimeLayout->slidesX;
        if (WjsslSliderBuildHelper::HasValue($runtimeLayout->slidesY))
            $slidesContainerY = $runtimeLayout->slidesY;
        $slidesNode->setAttribute("data-u", "slides");
        $slidesNodeCss = 'cursor:default;position:relative;top:' . $slidesContainerY . 'px;left:' . $slidesContainerX . 'px;width:' . $slidesContainerWidth . 'px;height:' . $slidesContainerHeight . 'px;overflow:hidden;';
        $slidesNode->setAttribute("style", $slidesNodeCss);
        #endregion

        #region child elements

        $this->_ArraySlideNodes = array();

        //slides elements
        if(isset($runtimeDocument->Slides) && !empty($runtimeDocument->Slides)) {
            foreach ($runtimeDocument->Slides as $i=>$runtimeSlide)
            {
                $slideNode = $this->BuildSlideNode($i, $runtimeSlide, $runtimeDocument, $htmlDocument);
                //WjsslSliderBuildHelper::AlterJssorRes_Html($slideNode);
                array_push($this->_ArraySlideNodes, $slideNode);
                $slidesNode->appendChild($slideNode);
            }
        }

        #endregion

        $this->_SlidesNode = $slidesNode;
    }
    private function BuildLoadingScreenNodes(WjsslRuntimeLoading $runtimeLoading, WjsslRuntimeDocument $runtimeDocument, DOMDocument $htmlDocument)
    {
        $htmlNodes = array();
        if (!empty($runtimeLoading))
        {
            $loadingSkin = $runtimeLoading->Skin;
            if ($loadingSkin != null)
            {
                $skinId = WjsslSliderBuildHelper::GetSkinId($runtimeLoading, $loadingSkin);
                $loadingSkinHtml = WjsslSliderBuildHelper::BuildFromSkinTemplate($loadingSkin->html, array ($skinId, $runtimeLoading->itemWidth, $runtimeLoading->itemHeight ));
                $htmlNodes = WjsslSliderBuildHelper::LoadHTML($loadingSkinHtml, $htmlDocument);
                if (count($htmlNodes) > 0)
                {
                    $loadingScreenNode = null;
                    foreach ($htmlNodes as $htmlNode)
                    {
                        if ($htmlNode->nodeType == 1)   //XML_ELEMENT_NODE
                        {
                            $loadingScreenNode = $htmlNode;
                            WjsslSliderBuildHelper::AlterJssorRes_Html($htmlNode);
                            break;
                        }
                    }
                    if ($loadingScreenNode != null && !WjsslSliderBuildHelper::EmptyOrWhiteSpace($runtimeLoading->cntrBgColor))
                    {
                        $cssText = $loadingScreenNode->getAttribute("style");
                        if(empty($cssText)) {
                            $cssText = '';
                        }
                        if(!empty($cssText) && preg_match('/background-color/i', $cssText)) {
                            $cssDeclarations = WjsslCssParser::ParseDeclarations($cssText);
                            WjsslSliderBuildHelper::SetCssValue($cssDeclarations, "background-color", $runtimeLoading->cntrBgColor);
                            if (count($cssDeclarations) > 0)
                            {
                                $loadingScreenNode->setAttribute("style", WjsslSliderBuildHelper::ToCssText($cssDeclarations));
                            }
                        }
                        else {
                            if(!empty($cssText) && substr($cssText, -1) != ';') {
                                $cssText .= ';';
                            }
                            $cssText .= 'background-color:' . $runtimeLoading->cntrBgColor;
                        }
                    }
                }
            }
        }
        return $htmlNodes;
    }
    private function BuildBulletNavigatorNodes(WjsslRuntimeBullets $runtimeBullets, WjsslRuntimeDocument $runtimeDocument, DOMDocument $htmlDocument)
    {
        $htmlNodes = array();
        if (!empty($runtimeBullets))
        {
            $bulletSkin = $runtimeBullets->Skin;
            if (!empty($bulletSkin))
            {
                $skinId = WjsslSliderBuildHelper::GetSkinId($runtimeBullets, $bulletSkin);
                $bulletNavigatorHtml = WjsslSliderBuildHelper::BuildFromSkinTemplate($bulletSkin->html, array( $skinId, $runtimeBullets->itemWidth, $runtimeBullets->itemHeight ));
                $htmlNodes = WjsslSliderBuildHelper::LoadHTML($bulletNavigatorHtml, $htmlDocument);
                //set $css text for navigator node
                $navigatorNode = WjsslSliderBuildHelper::FindNode($htmlNodes, "navigator");
                if (!empty($navigatorNode))
                {
                    WjsslSliderBuildHelper::AlterJssorRes_Html($navigatorNode);
                    if (WjsslSliderBuildHelper::HasValue($runtimeBullets->posAutoCenter))
                    {
                        $navigatorNode->setAttribute("data-autocenter", round($runtimeBullets->posAutoCenter));
                    }
                    if (WjsslSliderBuildHelper::HasValue($runtimeBullets->bhvScaleL))
                    {
                        $navigatorNode->setAttribute("data-scale", $runtimeBullets->bhvScaleL);
                    }
                    //set bullet navigator scale position
                    WjsslSliderBuildHelper::SetScalePos($navigatorNode, $runtimeBullets->bhvScalePos, $runtimeBullets->posAutoCenter, $runtimeBullets->posTop, $runtimeBullets->posRight, $runtimeBullets->posBottom, $runtimeBullets->posLeft);
                    $cssText = $navigatorNode->getAttribute("style");
                    $cssDeclarations = WjsslCssParser::ParseDeclarations($cssText);
                    WjsslSliderBuildHelper::SetCssPixel($cssDeclarations, "bottom", $runtimeBullets->posBottom);
                    WjsslSliderBuildHelper::SetCssPixel($cssDeclarations, "left", $runtimeBullets->posLeft);
                    WjsslSliderBuildHelper::SetCssPixel($cssDeclarations, "right", $runtimeBullets->posRight);
                    WjsslSliderBuildHelper::SetCssPixel($cssDeclarations, "top", $runtimeBullets->posTop);
                    if (count($cssDeclarations) > 0)
                    {
                        $navigatorNode->setAttribute("style", WjsslSliderBuildHelper::ToCssText($cssDeclarations));
                    }
                    //set $css text for prototype node
                    $prototypeNode = WjsslSliderBuildHelper::FindNode($navigatorNode->childNodes, "prototype");
                    if (!empty($prototypeNode))
                    {
                        $cssText = $prototypeNode->getAttribute("style");
                        $cssDeclarations = WjsslCssParser::ParseDeclarations($cssText);
                        WjsslSliderBuildHelper::SetCssPixel($cssDeclarations, "width", $runtimeBullets->itemWidth);
                        WjsslSliderBuildHelper::SetCssPixel($cssDeclarations, "height", $runtimeBullets->itemHeight);
                        if (count($cssDeclarations) > 0)
                        {
                            $prototypeNode->setAttribute("style", WjsslSliderBuildHelper::ToCssText($cssDeclarations));
                        }
                    }
                }
            }
        }
        return $htmlNodes;
    }
    private function BuildArrowNavigatorNodes(WjsslRuntimeArrows $runtimeArrows, WjsslRuntimeDocument $runtimeDocument, DOMDocument $htmlDocument)
    {
        $htmlNodes = array();
        if (!empty($runtimeArrows))
        {
            $arrowSkin = $runtimeArrows->Skin;
            if (!empty($arrowSkin))
            {
                $htmlNodes = WjsslSliderBuildHelper::LoadHTML($arrowSkin->html, $htmlDocument);
                //set $css text for arrowleft node
                $arrowleftNode = WjsslSliderBuildHelper::FindNode($htmlNodes, "arrowleft");
                if (!empty($arrowleftNode))
                {
                    WjsslSliderBuildHelper::AlterJssorRes_Html($arrowleftNode);
                    if (WjsslSliderBuildHelper::HasValue($runtimeArrows->posAutoCenter))
                    {
                        $arrowleftNode->setAttribute("data-autocenter", round($runtimeArrows->posAutoCenter));
                    }
                    if (WjsslSliderBuildHelper::HasValue($runtimeArrows->bhvScaleL))
                    {
                        $arrowleftNode->setAttribute("data-scale", $runtimeArrows->bhvScaleL);
                    }
                    //set arrow left scale position
                    WjsslSliderBuildHelper::SetScalePos($arrowleftNode, $runtimeArrows->bhvScalePos, $runtimeArrows->posAutoCenter, $runtimeArrows->poslTop, $runtimeArrows->poslRight, $runtimeArrows->poslBottom, $runtimeArrows->poslLeft);
                    $cssText = $arrowleftNode->getAttribute("style");
                    $cssDeclarations = WjsslCssParser::ParseDeclarations($cssText);
                    WjsslSliderBuildHelper::SetCssPixel($cssDeclarations, "bottom", $runtimeArrows->poslBottom);
                    WjsslSliderBuildHelper::SetCssPixel($cssDeclarations, "left", $runtimeArrows->poslLeft);
                    WjsslSliderBuildHelper::SetCssPixel($cssDeclarations, "right", $runtimeArrows->poslRight);
                    WjsslSliderBuildHelper::SetCssPixel($cssDeclarations, "top", $runtimeArrows->poslTop);
                    WjsslSliderBuildHelper::SetCssPixel($cssDeclarations, "width", $runtimeArrows->itemWidth);
                    WjsslSliderBuildHelper::SetCssPixel($cssDeclarations, "height", $runtimeArrows->itemHeight);
                    if (count($cssDeclarations) > 0)
                    {
                        $arrowleftNode->setAttribute("style", WjsslSliderBuildHelper::ToCssText($cssDeclarations));
                    }
                }
                //set $css text for arrowright node
                $arrowrightNode = WjsslSliderBuildHelper::FindNode($htmlNodes, "arrowright");
                if (!empty($arrowrightNode))
                {
                    WjsslSliderBuildHelper::AlterJssorRes_Html($arrowrightNode);
                    if (WjsslSliderBuildHelper::HasValue($runtimeArrows->posAutoCenter))
                    {
                        $arrowrightNode->setAttribute("data-autocenter", round($runtimeArrows->posAutoCenter));
                    }
                    if (WjsslSliderBuildHelper::HasValue($runtimeArrows->bhvScaleL))
                    {
                        $arrowrightNode->setAttribute("data-scale", $runtimeArrows->bhvScaleL);
                    }
                    //set arrow right navigator scale position
                    WjsslSliderBuildHelper::SetScalePos($arrowrightNode, $runtimeArrows->bhvScalePos, $runtimeArrows->posAutoCenter, $runtimeArrows->posrTop, $runtimeArrows->posrRight, $runtimeArrows->posrBottom, $runtimeArrows->posrLeft);
                    $cssText = $arrowrightNode->getAttribute("style");
                    $cssDeclarations = WjsslCssParser::ParseDeclarations($cssText);
                    WjsslSliderBuildHelper::SetCssPixel($cssDeclarations, "bottom", $runtimeArrows->posrBottom);
                    WjsslSliderBuildHelper::SetCssPixel($cssDeclarations, "left", $runtimeArrows->posrLeft);
                    WjsslSliderBuildHelper::SetCssPixel($cssDeclarations, "right", $runtimeArrows->posrRight);
                    WjsslSliderBuildHelper::SetCssPixel($cssDeclarations, "top", $runtimeArrows->posrTop);
                    WjsslSliderBuildHelper::SetCssPixel($cssDeclarations, "width", $runtimeArrows->itemWidth);
                    WjsslSliderBuildHelper::SetCssPixel($cssDeclarations, "height", $runtimeArrows->itemHeight);
                    if (count($cssDeclarations) > 0)
                    {
                        $arrowrightNode->setAttribute("style", WjsslSliderBuildHelper::ToCssText($cssDeclarations));
                    }
                }
            }
        }
        return $htmlNodes;
    }
    private function BuildThumbnailNavigatorNodes($runtimeThumbnails, WjsslRuntimeDocument $runtimeDocument, DOMDocument $htmlDocument)
    {
        $htmlNodes = array();
        if (!empty($runtimeThumbnails))
        {
            $thumbnailSkin = $runtimeThumbnails->Skin;
            if (!empty($thumbnailSkin))
            {
                $skinId = WjsslSliderBuildHelper::GetSkinId($runtimeThumbnails, $thumbnailSkin);
                $thumbnailNavigatorHtml = WjsslSliderBuildHelper::BuildFromSkinTemplate($thumbnailSkin->html, array( $skinId, $runtimeThumbnails->itemWidth, $runtimeThumbnails->itemHeight ));
                $htmlNodes = WjsslSliderBuildHelper::LoadHTML($thumbnailNavigatorHtml, $htmlDocument);
                //set $css text for navigator node
                $thumbnailNavigatorNode = WjsslSliderBuildHelper::FindNode($htmlNodes, "thumbnavigator");
                if (!empty($thumbnailNavigatorNode))
                {
                    WjsslSliderBuildHelper::AlterJssorRes_Html($thumbnailNavigatorNode);
                    if (WjsslSliderBuildHelper::HasValue($runtimeThumbnails->cntrAutoCenter))
                    {
                        $thumbnailNavigatorNode->setAttribute("data-autocenter", round($runtimeThumbnails->cntrAutoCenter));
                    }
                    if (WjsslSliderBuildHelper::HasValue($runtimeThumbnails->bhvScaleL))
                    {
                        $thumbnailNavigatorNode->setAttribute("data-scale", $runtimeThumbnails->bhvScaleL);
                    }
                    //set thumbnail navigator scale position
                    WjsslSliderBuildHelper::SetScalePos($thumbnailNavigatorNode, $runtimeThumbnails->bhvScalePos, $runtimeThumbnails->cntrAutoCenter, $runtimeThumbnails->cntrTop, $runtimeThumbnails->cntrRight, $runtimeThumbnails->cntrBottom, $runtimeThumbnails->cntrLeft);
                    $cssText = $thumbnailNavigatorNode->getAttribute("style");
                    $cssDeclarations = WjsslCssParser::ParseDeclarations($cssText);
                    WjsslSliderBuildHelper::SetCssPixel($cssDeclarations, "bottom", $runtimeThumbnails->cntrBottom);
                    WjsslSliderBuildHelper::SetCssPixel($cssDeclarations, "left", $runtimeThumbnails->cntrLeft);
                    WjsslSliderBuildHelper::SetCssPixel($cssDeclarations, "right", $runtimeThumbnails->cntrRight);
                    WjsslSliderBuildHelper::SetCssPixel($cssDeclarations, "top", $runtimeThumbnails->cntrTop);
                    WjsslSliderBuildHelper::SetCssPixel($cssDeclarations, "width", $runtimeThumbnails->cntrWidth);
                    WjsslSliderBuildHelper::SetCssPixel($cssDeclarations, "height", $runtimeThumbnails->cntrHeight);
                    if (WjsslSliderBuildHelper::HasValue($runtimeThumbnails->cntrBgColor))
                    {
                        WjsslSliderBuildHelper::SetCssValue($cssDeclarations, "background-color", $runtimeThumbnails->cntrBgColor);
                    }
                    if (count($cssDeclarations) > 0)
                    {
                        $thumbnailNavigatorNode->setAttribute("style", WjsslSliderBuildHelper::ToCssText($cssDeclarations));
                    }
                }
            }
        }
        return $htmlNodes;
    }
    private function BuildFullSizeWrapperNode()
    {
        $wrapperNode = $this->_HtmlDocument->createElement("div");
        //$wrapperNode->setAttribute("u", "slider-wrapper");
        $wrapperNode->setAttribute("style", "position:relative;top:0;left:0;width:100%;height:100%;overflow:hidden;");
        return $wrapperNode;
    }
    private function BuildFullWidthWrapperNode()
    {
        $wrapperNode = $this->_HtmlDocument->createElement("div");
        return $wrapperNode;
    }
    private function BuildSliderNode()
    {
        $runtimeDocument = $this->_RuntimeDocument;
        $htmlDocument = $this->_HtmlDocument;
        $runtimeLayout = $runtimeDocument->Layouts->layout;
        $sliderNode = $htmlDocument->createElement("div");

        #region id

        $id = $this->getContainerId();
        $sliderNode->setAttribute("id", $id);
        $sliderNode->setAttribute("class", $id);

        #endregion

        #region rtl

        if($runtimeLayout->dirRTL)
        {
            $sliderNode->SetAttribute("dir", "rtl");
        }

        #endregion

        #region $css

        $sliderContainerWidth = $runtimeLayout->slideWidth;
        $sliderContainerHeight = $runtimeLayout->slideHeight;
        if (WjsslSliderBuildHelper::HasValue($runtimeLayout->ocWidth))
            $sliderContainerWidth = $runtimeLayout->ocWidth;
        else if (WjsslSliderBuildHelper::HasValue($runtimeLayout->slidesWidth))
            $sliderContainerWidth = $runtimeLayout->slidesWidth;
        if (WjsslSliderBuildHelper::HasValue($runtimeLayout->ocHeight))
            $sliderContainerHeight = $runtimeLayout->ocHeight;
        else if (WjsslSliderBuildHelper::HasValue($runtimeLayout->slidesHeight))
            $sliderContainerHeight = $runtimeLayout->slidesHeight;
        $styleMargin = "";
        if ($runtimeLayout->otpCenter == true)
            $styleMargin = "margin:0 auto;";
        $sliderNodeCss = 'position:relative;' . $styleMargin . 'top:0px;left:0px;width:' . $sliderContainerWidth. 'px;height:' . $sliderContainerHeight . 'px;overflow:hidden;visibility:hidden;';
        if (WjsslSliderBuildHelper::HasValue($runtimeLayout->ocBgImage))
        {
            $backgroundImageUrl = $runtimeLayout->ocBgImage;
            WjsslSliderBuildHelper::AlterJssorRes_Url($backgroundImageUrl, true);
            $sliderNodeCss .= 'background:url(' . $backgroundImageUrl . ') 50% 50% no-repeat;background-size:cover;';
        }
        if (WjsslSliderBuildHelper::HasValue($runtimeLayout->ocBgColor))
        {
            $sliderNodeCss .= 'background-color:' . $runtimeLayout->ocBgColor . ';';
        }
        //sliderNodeCss .= "-webkit-user-select:none;-moz-user-select:none;user-select:none;-webkit-touch-callout:none;";
        $sliderNode->SetAttribute("style", $sliderNodeCss);

        #endregion

        #region child elements

        #region loading screen

        if(WjsslSliderBuildHelper::HasValue($runtimeDocument->Layouts->loading))
        {
            $this->_LoadingScreenNodes = $this->BuildLoadingScreenNodes($runtimeDocument->Layouts->loading, $runtimeDocument, $htmlDocument);
        }
        if (WjsslSliderBuildHelper::HasValue($this->_LoadingScreenNodes))
        {
            $sliderNode->appendChild($htmlDocument->createComment(' Loading Screen '));
            foreach($this->_LoadingScreenNodes as $node)
            {
                $sliderNode->appendChild($node);
            }
        }

        #endregion

        #region slides node

        $this->BuildSlidesNode($runtimeLayout, $runtimeDocument, $htmlDocument);
        $sliderNode->appendChild($this->_SlidesNode);

        #endregion

        #region thumbnail navigator

        if($runtimeDocument->Layouts->thumbnails != null)
        {
            $this->_ThumbnailNavigatorNodes = $this->BuildThumbnailNavigatorNodes($runtimeDocument->Layouts->thumbnails, $runtimeDocument, $htmlDocument);
        }
        if (WjsslSliderBuildHelper::HasValue($this->_ThumbnailNavigatorNodes))
        {
            $sliderNode->appendChild($htmlDocument->createComment(' Thumbnail Navigator '));
            foreach($this->_ThumbnailNavigatorNodes as $node)
            {
                $sliderNode->appendChild($node);
            }
        }

        #endregion

        #region bullet navigator

        if(WjsslSliderBuildHelper::HasValue($runtimeDocument->Layouts->bullets))
        {
          $this->_BulletNavigatorNodes = $this->BuildBulletNavigatorNodes($runtimeDocument->Layouts->bullets, $runtimeDocument, $htmlDocument);
        }
        if (WjsslSliderBuildHelper::HasValue($this->_BulletNavigatorNodes))
        {
            $sliderNode->appendChild($htmlDocument->createComment(' Bullet Navigator '));
            foreach($this->_BulletNavigatorNodes as $node)
            {
                $sliderNode->appendChild($node);
            }
        }

        #endregion

        #region arrow navigator

        if($runtimeDocument->Layouts->arrows != null)
        {
            $this->_ArrowNavigatorNodes = $this->BuildArrowNavigatorNodes($runtimeDocument->Layouts->arrows, $runtimeDocument, $htmlDocument);
        }
        if (WjsslSliderBuildHelper::HasValue($this->_ArrowNavigatorNodes))
        {
            $sliderNode->appendChild($htmlDocument->createComment(' Arrow Navigator '));
            foreach($this->_ArrowNavigatorNodes as $node)
            {
                $sliderNode->appendChild($node);
            }
        }

        #endregion

        #endregion

        $this->_SliderNode = $sliderNode;
    }
    private function BuildJssorSliderLibraryScriptNode()
    {
        $upload = wp_upload_dir();
        $script_name = 'jssor.slider';
        $src_prefix = $upload['baseurl'] . WP_Jssor_Slider_Globals::UPLOAD_SCRIPTS;
        $min_js_path = $src_prefix . '/' . $script_name . '-' . WP_JSSOR_MIN_JS_VERSION . '.min.js';

        $local_res_info = WP_Jssor_Slider_Utils::to_local_res_info($min_js_path);
        if($local_res_info->is_valid) {
            $min_js_path = $local_res_info->path;
        }

        $scriptNode = $this->_HtmlDocument->createElement("script");
        $scriptNode->setAttribute("src", $min_js_path);
        return $scriptNode;
    }
    private function BuildJssorSliderResponsiveCode(WjsslRuntimeDocument $runtimeDocument)
    {
        $responsiveCode = '';
        //responsive
        $responsiveOptions = $runtimeDocument->ReponsiveOptions;
        if (WjsslSliderBuildHelper::HasValue($responsiveOptions) && WjsslSliderBuildHelper::HasValue($responsiveOptions->ScaleTo))
        {
            $scaleMode = $responsiveOptions->ScaleTo;
            $isToScaleHeight = ($scaleMode & WjsslEnumResponsiveScaleMode::Height) != WjsslEnumResponsiveScaleMode::None;
            $isToScaleWidth = ($scaleMode & WjsslEnumResponsiveScaleMode::Width) != WjsslEnumResponsiveScaleMode::None;
            $isToScaleSize = $isToScaleHeight && $isToScaleWidth;
            $isConstrainMode = ($scaleMode & WjsslEnumResponsiveScaleMode::Constrain) != WjsslEnumResponsiveScaleMode::None;
            //$isFlexMode = ($scaleMode & WjsslEnumResponsiveScaleMode::Flex) != WjsslEnumResponsiveScaleMode::None;
            $maxBleeding = null;
            if ($isToScaleSize)
            {
                $maxBleeding = $responsiveOptions->Bleeding;
                if(!WjsslSliderBuildHelper::HasValue($maxBleeding))
                {
                    $maxBleeding = .128;
                }
            }
            $instanceVariableId = $this->getInstanceVariableId();
            if ($isToScaleSize)
            {
                $responsiveCode .= "//make sure to clear margin of the slider container element";
                $responsiveCode .= "\n";
                $responsiveCode .= "$instanceVariableId.\$Elmt.style.margin = \"\";";
                $responsiveCode .= "\n";
                $responsiveCode .= "\n";
            }
            $responsiveCode .= '/*responsive code begin*/';
            $responsiveCode .= "\n";
//            if ($isToScaleSize)
//            {
//                $responsiveCode .= '
///*
//parameters to scale jssor slider to fill parent container
//MAX_WIDTH
//    prevent slider from scaling too wide
//MAX_HEIGHT
//    prevent slider from scaling too high, default value is original height
//MAX_BLEEDING
//    prevent slider from bleeding outside too much, default value is 1
//    0: contain mode, allow up to 0% to bleed outside, the slider will be all inside parent container
//    1: cover mode, allow up to 100% to bleed outside, the slider will cover full area of parent container
//    0.1: flex mode, allow up to 10% to bleed outside, this is better way to make full window slider, especially for mobile devices
//*/';
//            }
            if ($isToScaleWidth)
            {
                $maxWidth = $responsiveOptions->MaxW;
                if(!WjsslSliderBuildHelper::HasValue($maxWidth))
                {
                    $maxWidth = $runtimeDocument->Layouts->layout->ocWidth;
                }
                $responsiveCode .= "var MAX_WIDTH = $maxWidth;";
                $responsiveCode .= "\n";
            }
            if ($isToScaleHeight)
            {
                $maxHeight = $responsiveOptions->MaxH;
                if(!WjsslSliderBuildHelper::HasValue($maxHeight))
                {
                    $maxHeight = $runtimeDocument->Layouts->layout->ocHeight;
                }
                $responsiveCode .= "var MAX_HEIGHT = $maxHeight;";
                $responsiveCode .= "\n";
            }
            if (WjsslSliderBuildHelper::HasValue($maxBleeding))
            {
                $responsiveCode .= "var MAX_BLEEDING = $maxBleeding;";
                $responsiveCode .= "\n";
            }
            $responsiveCode .= "\n";
            $responsiveCode .= "function ScaleSlider() {";
            $responsiveCode .= "\n";
            $responsiveCode .= "    var containerElement = $instanceVariableId.\$Elmt.parentNode;";
            $responsiveCode .= "\n";
            $responsiveCode .= "    var containerWidth = containerElement.clientWidth;";
            $responsiveCode .= "\n";
            $responsiveCode .= "\n";
            $responsiveCode .="    if (containerWidth) {";
            $responsiveCode .= "\n";
            if ($isToScaleHeight)
            {
                if ($isToScaleWidth)
                {
                    $responsiveCode .= "        var originalWidth = $instanceVariableId.\$OriginalWidth();";
                $responsiveCode .= "\n";
                }
                $responsiveCode .= "        var originalHeight = $instanceVariableId.\$OriginalHeight();";
                $responsiveCode .= "\n";
                $responsiveCode .= "\n";
                $responsiveCode .= "        var containerHeight = containerElement.clientHeight || originalHeight;";
                $responsiveCode .= "\n";
            }
            $responsiveCode .= "\n";
            if ($isToScaleWidth)
            {
                $responsiveCode .= "        var expectedWidth = Math.min(MAX_WIDTH || containerWidth, containerWidth);";
                $responsiveCode .= "\n";
            }
            if ($isToScaleHeight)
            {
                $responsiveCode .= "        var expectedHeight = Math.min(MAX_HEIGHT || containerHeight, containerHeight);";
                $responsiveCode .= "\n";
            }
            if ($isToScaleSize)
            {
                if ($isConstrainMode)
                {
                    $responsiveCode .= '
    //constrain bullets, arrows inside slider area, it\'s optional, remove it if not necessary
    if (MAX_BLEEDING >= 0 && MAX_BLEEDING < 1) {
        var widthRatio = expectedWidth / originalWidth;
        var heightRatio = expectedHeight / originalHeight;
        var maxScaleRatio = Math.max(widthRatio, heightRatio);
        var minScaleRatio = Math.min(widthRatio, heightRatio);
        maxScaleRatio = Math.min(maxScaleRatio / minScaleRatio, 1 / (1 - MAX_BLEEDING)) * minScaleRatio;
        expectedWidth = Math.min(expectedWidth, originalWidth * maxScaleRatio);
        expectedHeight = Math.min(expectedHeight, originalHeight * maxScaleRatio);
    }';
                    $responsiveCode .= "\n";
                }
                $responsiveCode .= "\n";
                $responsiveCode .= "        //scale the slider to expected size";
                $responsiveCode .= "\n";
                $responsiveCode .= "        $instanceVariableId.\$ScaleSize(expectedWidth, expectedHeight, MAX_BLEEDING);";
                $responsiveCode .= "\n";
                $responsiveCode .= "\n";
                $responsiveCode .= "        //position slider at center in vertical orientation";
                $responsiveCode .= "\n";
                $responsiveCode .= "        $instanceVariableId.\$Elmt.style.top = ((containerHeight - expectedHeight) / 2) + \"px\";";
                $responsiveCode .= "\n";
                $responsiveCode .= "\n";
                $responsiveCode .= "        //position slider at center in horizontal orientation";
                $responsiveCode .= "\n";
                $responsiveCode .= "        $instanceVariableId.\$Elmt.style.left = ((containerWidth - expectedWidth) / 2) + \"px\";";
                $responsiveCode .= "\n";
            }
            else
            {
                $responsiveCode .= "\n";
                if ($isToScaleWidth)
                {
                    //scale width
                    $responsiveCode .= "        $instanceVariableId.\$ScaleWidth(expectedWidth);";
                    $responsiveCode .= "\n";
                }
                else if ($isToScaleHeight)
                {
                    //scale height
                    $responsiveCode .= "        $instanceVariableId.\$ScaleHeight(expectedHeight);";
                    $responsiveCode .= "\n";
                }
            }
            $responsiveCode .= "    }";
            $responsiveCode .= "\n";
            $responsiveCode .= "    else {";
            $responsiveCode .= "\n";
            $responsiveCode .= "        window.setTimeout(ScaleSlider, 30);";
            $responsiveCode .= "\n";
            $responsiveCode .= "    }";
            $responsiveCode .= "\n";
            $responsiveCode .= "}";
            $responsiveCode .= "\n";
            $orientationEventChangeEventHandlerFunctionName = "ScaleSlider";
            //if ($this->_DisableScrollingBounceEffect)
            //{
            //    $responsiveCode .= "\n";
            //    $responsiveCode .= 'function OrientationChangeEventHandler() {';
            //    $responsiveCode .= "\n";
            //    $responsiveCode .= '    ScaleSlider();';
            //    $responsiveCode .= "\n";
            //    //$responsiveCode .= '    window.setTimeout(ScaleSlider, 500);';
            //    //$responsiveCode .= "\n";
            //    $responsiveCode .= '}';
            //    $responsiveCode .= "\n";
            //    $orientationEventChangeEventHandlerFunctionName = "OrientationChangeEventHandler";
            //}
            if ($this->_DisableScrollingBounceEffect)
            {
                $responsiveCode .= "\n";
                $responsiveCode .= '/*ios disable scrolling and bounce effect*/';
                $responsiveCode .= "\n";
                $responsiveCode .= '$Jssor$.$AddEvent(document, "touchmove", function(event){event.touches.length < 2 && $Jssor$.$CancelEvent(event);});';
                $responsiveCode .= "\n";
            }
            $responsiveCode .= "\n";
            $responsiveCode .= "ScaleSlider();";
            $responsiveCode .= "\n";
            $responsiveCode .= "\n";
            $responsiveCode .= '$Jssor$.$AddEvent(window, "load", ScaleSlider);';
            $responsiveCode .= "\n";
            $responsiveCode .= '$Jssor$.$AddEvent(window, "resize", ScaleSlider);';
            $responsiveCode .= "\n";
            $responsiveCode .= "\$Jssor\$.\$AddEvent(window, \"orientationchange\", $orientationEventChangeEventHandlerFunctionName);";
            $responsiveCode .= "\n";
            $responsiveCode .= '/*responsive code end*/';
        }
        return $responsiveCode;
    }
    private function BuildJssorSliderInitializeScriptNode(WjsslRuntimeOptions $runtimeOptions)
    {
        $instanceVariableId = $this->getInstanceVariableId();
        $containerId = $this->getContainerId();
        $optionsId = $this->getOptionsId();
        $text = "\n" . $this->getInitStatementId() . " = function() {";
        //slideshow options
        if (WjsslSliderBuildHelper::HasValue($runtimeOptions->SlideshowOptions))
        {
            $text .= "\n";
            $slideshowTransitionsVariableId = $this->getSlideshowTransitionVariableId();
            $runtimeOptions->SlideshowOptions->Transitions = new WjsslRawNode($slideshowTransitionsVariableId);
            $text .= "var " . $slideshowTransitionsVariableId . " = ";
            $text .= $runtimeOptions->SlideshowOptions->TransitionArray->toJson();
            $text .= ";";
        }
        //caption options
        if (WjsslSliderBuildHelper::HasValue($runtimeOptions->CaptionSliderOptions))
        {
            $text .= "\n\n";
            $captionTransitionsVariableId = $this->getCaptionTransitionVariableId();
            $runtimeOptions->CaptionSliderOptions->Transitions = new WjsslRawNode($captionTransitionsVariableId);
            $text .= "var " . $captionTransitionsVariableId . " = ";
            $text .= $runtimeOptions->CaptionSliderOptions->TransitionArray->toJson();
            $text .= ";";
        }
        //options
        $text .= "\n\n";
        $text .= "var $optionsId = ";
        $text .= $runtimeOptions->toJson();
        $text .= ";";
        //create instance
        $text .= "\n";
        $text .= "\nvar containerElement = document.getElementById(\"$containerId\");";
        $text .= "\ncontainerElement.removeAttribute(\"id\");";
        $text .= "\n$instanceVariableId = new \$JssorSlider\$(containerElement, $optionsId);";
        //responsive code
        $text .= "\n\n";
        $responsiveCode = $this->BuildJssorSliderResponsiveCode($this->_RuntimeDocument);
        $text .= $responsiveCode;
        $text .= "\n};\n";
        $scriptNode = $this->_HtmlDocument->createElement("script");
        $textNode = $this->_HtmlDocument->createTextNode($text);
        $scriptNode->appendChild($textNode);
        return $scriptNode;
    }
    private function BuildJssorSliderInitializeStatementNode()
    {
        $scriptNode = $this->_HtmlDocument->createElement("script");
        $textNode = $this->_HtmlDocument->createTextNode(WjsslSliderBuildHelper::BuildSliderIdString($this->_UniqueId, WjsslEnumSliderIdType::InitStatementId) . '();');
        $scriptNode->appendChild($textNode);
        return $scriptNode;
    }
    private function BuildCssNode()
    {
        $arraySkinCssBlocks = WjsslSliderBuildHelper::GetCssBlocks($this->_RuntimeDocument->Layouts);
        $this->_ArraySkinCssBlocks = $arraySkinCssBlocks;
        $css = '';
        if($this->_IncludeHtmlBodyCss)
        {
            $css = 'html, body {
    position:absolute;
    margin: 0;
    padding: 0;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}';
            $css .= "\n";
        }
        foreach($arraySkinCssBlocks as $skinCssBlock)
        {
            $css .= $skinCssBlock;
        }
        $cssNode = $this->_HtmlDocument->createElement('style');
        $textNode = $this->_HtmlDocument->createTextNode($css);
        $cssNode->appendChild($textNode);
        return $cssNode;
    }
    #endregion
    #region public methods
    public function build_slider()
    {
        //<-- Jssor Slider Begin -->
        $comment_header = Jssor_Slider_Bll::format_slider_html_code_comment_header(WP_JSSOR_MIN_JS_VERSION, $this->_SliderId, $this->_SliderName);
        $this->_CommentSliderBeginNode = $this->_HtmlDocument->createComment($comment_header);
        //<-- Generator: Jssor Slider WrodPress Plugin -->
        //$this->_CommentGeneratorNode = $this->_HtmlDocument->createComment('generator: jssor slider wrodpress plugin');
        //jssor slider library script node
        $this->_JssorSliderLibraryScriptNode = $this->BuildJssorSliderLibraryScriptNode();
        //jssor slider initialize script node
        $this->_JssorSliderInitializeScriptNode = $this->BuildJssorSliderInitializeScriptNode($this->_RuntimeDocument->Options);
        //css node
        $this->_CssNode = $this->BuildCssNode();
        //full size wrapper node
        $this->_FullSizeWrapperNode = $this->BuildFullSizeWrapperNode();
        //full width wrapper node
        $this->_FullWidthWrapperNode = $this->BuildFullWidthWrapperNode();
        //slider node
        $this->BuildSliderNode();
        //jssor slider initialize statement node
        $this->_JssorSliderStatementScriptNode = $this->BuildJssorSliderInitializeStatementNode();
        //<-- Jssor Slider Begin -->
        $comment_footer = Jssor_Slider_Bll::format_slider_html_code_comment_footer();
        $this->_CommentSliderEndNode = $this->_HtmlDocument->createComment($comment_footer);
    }
    public function get_html_document()
    {
        //<-- Jssor Slider Begin -->
        // array_push($sliderNodes, $this->_CommentSliderBeginNode);
        $this->_HtmlDocument->appendChild($this->_CommentSliderBeginNode);
        //<-- Generator: Jssor Slider WrodPress Plugin -->
        //$this->_HtmlDocument->appendChild($this->_CommentGeneratorNode);
        //jssor slider library script node
        // array_push($sliderNodes, $this->_JssorSliderLibraryScriptNode);
        $this->_HtmlDocument->appendChild($this->_JssorSliderLibraryScriptNode);
        //jssor slider initialize script node
        // array_push($sliderNodes, $this->_JssorSliderInitializeScriptNode);
        $this->_HtmlDocument->appendChild($this->_JssorSliderInitializeScriptNode);
        foreach($this->_WebFonts as $fontInfo)
        {
            $fontUrl = $fontInfo->url;

            if(!empty($fontUrl)) {
                $fontUrl = preg_replace('/^http(s)?\:\/\//', '//', $fontUrl);
                $linkNode = $this->_HtmlDocument->createElement("link");
                $linkNode->setAttribute("href", $fontUrl);
                $linkNode->setAttribute("rel", "stylesheet");
                $linkNode->setAttribute("type", "text/css");
                // array_push($sliderNodes, $linkNode);
                $this->_HtmlDocument->appendChild($linkNode);
            }
        }
        //css node
        // array_push($sliderNodes, $this->_CssNode);
        $this->_HtmlDocument->appendChild($this->_CssNode);
        if($this->_IncludeFullSizeWrapperNode)
        {
            //insert slider node into wrapper node
            $this->_FullSizeWrapperNode->appendChild($this->_SliderNode);
            //wrapper node
            // array_push($sliderNodes, $this->_FullSizeWrapperNode);
            $this->_HtmlDocument->appendChild($this->_FullSizeWrapperNode);
        }
        else if($this->_IncludeFullWidthWrapperNode) {
            //insert slider node into wrapper node
            $this->_FullWidthWrapperNode->appendChild($this->_SliderNode);
            //wrapper node
            $this->_HtmlDocument->appendChild($this->_FullWidthWrapperNode);
        }
        else {
            //slider node
            // array_push($sliderNodes, $this->_SliderNode);
            $this->_HtmlDocument->appendChild($this->_SliderNode);
        }
        //<-- Jssor Slider Begin -->
        // array_push($sliderNodes, $this->_JssorSliderStatementScriptNode);
        $this->_HtmlDocument->appendChild($this->_JssorSliderStatementScriptNode);
        //jssor slider initialize statement node
        // array_push($sliderNodes, $this->_CommentSliderEndNode);
        $this->_HtmlDocument->appendChild($this->_CommentSliderEndNode);
        return $this->_HtmlDocument;
    }
    #endregion
}
//usage
//$sliderCodeDocument = new WjsslSliderCodeDocument($designTimeDocument, $runtimeDocument, $unique_id, $slider_id, $slider_name);
//$sliderCodeDocument->build_slider();
//$html_document = $sliderCodeDocument->get_html_document();
//$html_code = $html_document->saveHTML();
