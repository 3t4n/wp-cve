<?php

if( !defined( 'ABSPATH') ) exit();

/**
 * Class WjsslBuildConverter
 * @link   https://www.jssor.com
 * @author Neil->zhou
 */
class WjsslSliderBuildConverter
{
    private static  $_Easings = array(
            '$Jease$.$Linear',
            '$Jease$.$Swing',
            '$Jease$.$InQuad',
            '$Jease$.$OutQuad',
            '$Jease$.$InOutQuad',
            '$Jease$.$InCubic',
            '$Jease$.$OutCubic',
            '$Jease$.$InOutCubic',
            '$Jease$.$InQuart',
            '$Jease$.$OutQuart',
            '$Jease$.$InOutQuart',
            '$Jease$.$InQuint',
            '$Jease$.$OutQuint',
            '$Jease$.$InOutQuint',
            '$Jease$.$InSine',
            '$Jease$.$OutSine',
            '$Jease$.$InOutSine',
            '$Jease$.$InExpo',
            '$Jease$.$OutExpo',
            '$Jease$.$InOutExpo',
            '$Jease$.$InCirc',
            '$Jease$.$OutCirc',
            '$Jease$.$InOutCirc',
            '$Jease$.$InElastic',
            '$Jease$.$OutElastic',
            '$Jease$.$InOutElastic',
            '$Jease$.$InBack',
            '$Jease$.$OutBack',
            '$Jease$.$InOutBack',
            '$Jease$.$InBounce',
            '$Jease$.$OutBounce',
            '$Jease$.$InOutBounce',
            '$Jease$.$Early',
            '$Jease$.$Late'//,
            //'$Jease$.$GoBack',
            //'$Jease$.$InWave',
            //'$Jease$.$OutWave',
            //'$Jease$.$OutJump',
            //'$Jease$.$InJump'
        );

    /**
     * undocumented function
     *
     * @return void
     */
    public static function to_runtime_document(WjsslDesignTimeDocument $designTimeDocument)
    {
        $designTimeSlides = $designTimeDocument->slides;
        $designTimeOptions = $designTimeDocument->options;

        if (empty($designTimeOptions)) {
            $designTimeOptions = new WjsslDesignTimeOptions();
        }

        $designTimeLayouts = $designTimeDocument->layouts;

        if (empty($designTimeLayouts))
            $designTimeLayouts = new WjsslDesignTimeLayouts();

        $designTimeLayout = $designTimeLayouts->layout;
        $designTimeBullets = $designTimeLayouts->bullets;
        $designTimeArrows = $designTimeLayouts->arrows;
        $designTimeThumbnails = $designTimeLayouts->thumbnails;
        $designTimeLoading = $designTimeLayouts->loading;

        if (empty($designTimeSlides))
        {
            $designTimeSlides = array();
        }

        if (empty($designTimeLayout))
        {
            $designTimeLayout = new WjsslDesignTimeLayout();
        }

        $runtimeDocument = new WjsslRuntimeDocument();

        $runtimeOptions = new WjsslRuntimeOptions();
        $runtimeLayouts = new WjsslRuntimeLayouts();
        $responsiveOptions = null;


        $runtimeSlideList = array();

        $runtimeLayout = new WjsslRuntimeLayout();
        $captionSlideoOptions = new WjsslCaptionSlideoOptions();
        $runtimeSlideoTransitionLists = new WjsslDocumentNodeCollection();
        $runtimeSlideoBreakLists = new WjsslDocumentNodeCollection();
        $runtimeSlideoMotionControlList = new WjsslDocumentNodeCollection();

        $slidesContainerSize = self::calculateSlidesContainerSize($designTimeLayout);

        //region slides

        foreach ($designTimeSlides as $designTimeSlide)
        {
            $runtimeSlide = self::toRuntimeSlide($runtimeSlideoTransitionLists, $runtimeSlideoBreakLists, $runtimeSlideoMotionControlList, $designTimeSlide);

            $runtimeSlideList[] = $runtimeSlide;
        }

        //endregion

        //region runtimeOptions

        //region common options

        if ($designTimeOptions->arrowKeyNavigation != 1)
            $runtimeOptions->ArrowKeyNavigation = $designTimeOptions->arrowKeyNavigation;

        if (!WjsslSliderBuildHelper::HasValue($designTimeOptions->autoPlayL) && $designTimeOptions->autoPlay == true)
            $designTimeOptions->autoPlayL = 1;

        $runtimeOptions->AutoPlay = $designTimeOptions->autoPlayL;

        if ($designTimeOptions->autoPlaySteps != 1)
            $runtimeOptions->AutoPlaySteps = $designTimeOptions->autoPlaySteps;


        if (WjsslSliderBuildHelper::HasValue($designTimeOptions->dragOrientation) && $designTimeOptions->dragOrientation != WjsslEnumOrientation::Horizontal)
            $runtimeOptions->DragOrientation = $designTimeOptions->dragOrientation;

        if ($designTimeOptions->dragRatio != 1)
            $runtimeOptions->DragRatio = $designTimeOptions->dragRatio;

        if ($designTimeOptions->dragSteps != 1)
            $runtimeOptions->DragSteps = $designTimeOptions->dragSteps;

        //if ($designTimeOptions->hwa != true)
        //    $runtimeOptions->HWA = $designTimeOptions->hwa;

        if ($designTimeLayout->fillMode != WjsslEnumFillMode::Stretch)
            $runtimeOptions->FillMode = $designTimeLayout->fillMode;

        if ($designTimeOptions->idle != WjsslSliderDefaultValues::Idle)
            $runtimeOptions->Idle = $designTimeOptions->idle;

        $runtimeOptions->LazyLoading = $designTimeOptions->otpLazyLoading;
        //runtimeOptions->title = $designTimeOptions->otpTitle;

        if (WjsslSliderBuildHelper::HasValue($designTimeOptions->loop) && $designTimeOptions->loop != WjsslEnumLoop::Loop)
            $runtimeOptions->Loop = $designTimeOptions->loop;

        if ($designTimeOptions->minDragOffsetToSlide != 20)
            $runtimeOptions->MinDragOffsetToSlide = $designTimeOptions->minDragOffsetToSlide;

        //runtimeOptions->Align = $designTimeLayout->slideAlign;

        if (WjsslSliderBuildHelper::HasValue($designTimeOptions->pauseOnHover) && $designTimeOptions->pauseOnHover != WjsslEnumPauseOnHover::PauseOnDesktop)
            $runtimeOptions->PauseOnHover = round($designTimeOptions->pauseOnHover);

        if (WjsslSliderBuildHelper::HasValue($designTimeLayout->slideOrientation) && $designTimeLayout->slideOrientation != WjsslEnumOrientation::Horizontal)
            $runtimeOptions->PlayOrientation = $designTimeLayout->slideOrientation;

        if (WjsslSliderBuildHelper::HasValue($designTimeOptions->slideDuration) && $designTimeOptions->slideDuration != 500)
            $runtimeOptions->SlideDuration = $designTimeOptions->slideDuration;

        if (WjsslSliderBuildHelper::HasValue($designTimeOptions->slideEasing) && $designTimeOptions->slideEasing != 3) {
            if(isset(WjsslSliderBuildConverter::$_Easings[$designTimeOptions->slideEasing])) {
                $runtimeOptions->SlideEasing = new WjsslRawNode(WjsslSliderBuildConverter::$_Easings[$designTimeOptions->slideEasing]);
            }
        }

        //runtimeOptions->slideHeight
        //runtimeOptions->slideWidth

        if (WjsslSliderBuildHelper::HasValue($designTimeLayout->slideSpacing))
            $runtimeOptions->SlideSpacing = round($designTimeLayout->slideSpacing);

        if (WjsslSliderBuildHelper::HasValue($designTimeOptions->startIndex))
        {
            $startIndex = ((int)$designTimeOptions->startIndex) - 1;
            if ($startIndex != 0)
                $runtimeOptions->StartIndex = $startIndex;
        }

        //runtimeOptions->uiSearchMode
        $runtimeOptions->OtpTitle = $designTimeOptions->otpTitle;
        $runtimeOptions->OtpWaterMark = $designTimeOptions->otpWaterMark;

        //region slides container size, slide size calculation

        $slidesWidth = $slidesContainerSize->Width;
        $slidesHeight = $slidesContainerSize->Height;

        $slideWidth = (int)$designTimeLayout->slideWidth;
        $slideHeight = (int)$designTimeLayout->slideHeight;
        if (empty($slideWidth))
            $slideWidth = 600;
        if (empty($slideHeight))
            $slideHeight = 300;

        if ($designTimeLayout->slideWidth != (int)$slidesWidth)
            $runtimeOptions->SlideWidth = $slideWidth;

        if ($designTimeLayout->slideHeight != (int)$slidesHeight)
            $runtimeOptions->SlideHeight = $slideHeight;

        //endregion

        //region calculate slideAlign and slideCols

        //region ignore slideCols calculation

        //$slideColumns = $designTimeLayout->slideCols;

        //$slideSpacing = 0;
        //if (WjsslSliderBuildHelper::HasValue($designTimeLayout->slideSpacing))
        //    $slideSpacing = (int)$designTimeLayout->slideSpacing;
        //$orientation = WjsslEnumOrientation::Horizontal;
        //if (WjsslSliderBuildHelper::HasValue($designTimeLayout->slideOrientation))
        //    $orientation = $designTimeLayout->slideOrientation;

        //$slidesLength = ($orientation  == WjsslEnumOrientation::Horizontal) ? $slidesWidth : $slidesHeight;

        //$slideLength = ($orientation  == WjsslEnumOrientation::Horizontal) ? $slideWidth : $slideHeight;

        //if (empty($slideColumns))
        //{
        //    $slideColumns = (int)ceil(($slidesLength  + $slideSpacing) / $slideLength);
        //}

        //if ($slideColumns  != 1)
        //    $runtimeOptions->SlideCols = $slideColumns;

        //endregion

        //region ignore slideAlign calculation

        //$slideAlign = $designTimeLayout->slideAlign;

        //if (!WjsslSliderBuildHelper::HasValue($slideAlign))
        //    $slideAlign = (int)(($slidesLength  - $slideLength) / 2);

        //$runtimeOptions->Align = $slideAlign;

        //endregion

        $runtimeOptions->Align = $designTimeLayout->slideAlign;

        //endregion

        //endregion

        //region SlideoOptions

        if (count($runtimeSlideoTransitionLists) > 0)
        {
            $captionSlideoOptions->TransitionArray = $runtimeSlideoTransitionLists;

            if (count($runtimeSlideoBreakLists) > 0)
            {
                $captionSlideoOptions->Breaks = $runtimeSlideoBreakLists;
            }

            if (count($runtimeSlideoMotionControlList) > 0)
            {
                $captionSlideoOptions->Controls = $runtimeSlideoMotionControlList;
            }

            //$captionSlideoOptions->Transitions = WjsslSliderBuildHelper::BuildSlideoTransitionVariableName($designTimeOptions->otpId);
            $runtimeOptions->CaptionSliderOptions = $captionSlideoOptions;
        }

        //endregion

        //region SlideshowOptions

        if (WjsslSliderBuildHelper::HasValue($designTimeOptions->sswPlay) && WjsslSliderBuildHelper::HasValue($designTimeOptions->sswTrans))
        {
            $slideshowOptions = new WjsslSlideshowOptions();
            //slideshowOptions->Class = '$JssorSlideshowRunner$';

            //if ($designTimeOptions->sswLink != false)
            //    $slideshowOptions->ShowLink = $designTimeOptions->sswLink;

            if ($designTimeOptions->sswPlay != WjsslEnumSlideshowPlay::Random)
                $slideshowOptions->TransitionOrder = $designTimeOptions->sswPlay;

            $slideshowTransitionList = new WjsslDocumentNodeCollection();

            foreach($designTimeOptions->sswTrans as $tran)
            {
                if(isset(WjsslSlideshowTransition::$Codes[$tran])) {
                    $slideshowTransitionList[] = new WjsslRawNode(WjsslSlideshowTransition::$Codes[$tran]);
                }
            }

            $slideshowOptions->TransitionArray = $slideshowTransitionList;
            //$slideshowOptions->Transitions = WjsslSliderBuildHelper::BuildSlideshowTransitionVariableName($designTimeOptions->otpId);

            $runtimeOptions->SlideshowOptions = $slideshowOptions;
        }

        //endregion

        //endregion

        //region responsiveOptions

        if (WjsslSliderBuildHelper::HasValue($designTimeLayout->rspScaleTo) && $designTimeLayout->rspScaleTo != WjsslEnumResponsiveScaleMode::None)
        {
            $responsiveOptions = new WjsslResponsiveOptions();

            $responsiveOptions->ScaleTo = $designTimeLayout->rspScaleTo;
            $responsiveOptions->Bleeding = $designTimeLayout->rspBleeding;

            //responsiveOptions->Adjust = $designTimeLayout->rspAdjust;

            $responsiveOptions->MaxW = $designTimeLayout->rspMaxW;

            $responsiveOptions->MaxH = $designTimeLayout->rspMaxH;

            //for backward compatibility
            if (WjsslSliderBuildHelper::HasValue($designTimeLayout->rspMax))
            {
                if (($responsiveOptions->ScaleTo & WjsslEnumResponsiveScaleMode::Width) == WjsslEnumResponsiveScaleMode::Width)
                {
                    if(!WjsslSliderBuildHelper::HasValue($responsiveOptions->MaxW))
                    {
                        $responsiveOptions->MaxW = $designTimeLayout->rspMax;
                    }
                }
                if (($responsiveOptions->ScaleTo & WjsslEnumResponsiveScaleMode::Height) == WjsslEnumResponsiveScaleMode::Height)
                {
                    if(!WjsslSliderBuildHelper::HasValue($responsiveOptions->MaxH))
                    {
                        $responsiveOptions->MaxH = $designTimeLayout->rspMax;
                    }
                }
            }

            //responsiveOptions->Max = $designTimeLayout->rspMax;

            //responsiveOptions->Min = $designTimeLayout->rspMin;
        }

        //this is obsolete, but still works for old document compatibility
        //drop old document compatibility
        //if ($designTimeOptions->rspScaleTo != null && $designTimeOptions->rspScaleTo != WjsslEnumResponsiveScaleMode::None)
        //{
        //    $responsiveOptions = new ResponsiveOptions();

        //    $responsiveOptions->ScaleTo = $designTimeOptions->rspScaleTo;

        //    $responsiveOptions->Adjust = $designTimeOptions->rspAdjust;

        //    $responsiveOptions->Max = $designTimeOptions->rspMax;

        //    $responsiveOptions->Min = $designTimeOptions->rspMin;
        //}

        //endregion

        //region runtimeLayouts

        //region layout

        $runtimeLayout->ocBgColor = $designTimeLayout->ocBgColor;
        $runtimeLayout->ocBgImage = $designTimeLayout->ocBgImage;
        $runtimeLayout->ocHeight = $designTimeLayout->ocHeight;
        $runtimeLayout->ocWidth = $designTimeLayout->ocWidth;
        $runtimeLayout->slideHeight = (int)$designTimeLayout->slideHeight;
        $runtimeLayout->slideWidth = (int)$designTimeLayout->slideWidth;
        $runtimeLayout->slidesX = $designTimeLayout->slidesX;
        $runtimeLayout->slidesY = $designTimeLayout->slidesY;
        //runtimeLayout->slidesHeight = $designTimeLayout->slidesHeight;
        //runtimeLayout->slidesWidth = $designTimeLayout->slidesWidth;
        $runtimeLayout->slidesBorderWidth = $designTimeLayout->slidesBorderWidth;
        $runtimeLayout->slidesBorderStyle = $designTimeLayout->slidesBorderStyle;
        $runtimeLayout->slidesBorderColor = $designTimeLayout->slidesBorderColor;
        $runtimeLayout->dirRTL = $designTimeLayout->dirRTL;

        $runtimeLayout->otpId = $designTimeOptions->otpId;
        $runtimeLayout->otpCenter = $designTimeOptions->otpCenter;

        $runtimeLayout->slidesWidth = (int)$slidesContainerSize->Width;
        $runtimeLayout->slidesHeight = (int)$slidesContainerSize->Height;

        if (!WjsslSliderBuildHelper::HasValue($runtimeLayout->ocWidth))
            $runtimeLayout->ocWidth = $runtimeLayout->slidesWidth;
        if (!WjsslSliderBuildHelper::HasValue($runtimeLayout->ocHeight))
            $runtimeLayout->ocHeight = $runtimeLayout->slidesHeight;

        $runtimeLayouts->layout = $runtimeLayout;

        //endregion

        //region bullets

        if (WjsslSliderBuildHelper::HasValue($designTimeBullets) && WjsslSliderBuildHelper::HasValue($designTimeBullets->theme))
        {
            //region runtimeBullets

            $runtimeBullets = new WjsslRuntimeBullets();

            $runtimeBullets->Skin = $designTimeBullets->theme->skin;

            $runtimeBullets->bhvScaleL = $designTimeBullets->bhvScaleL;

            //for backward compatibility
            if ($designTimeBullets->bhvNoScale == true)
            {
                $runtimeBullets->bhvScaleL = 0;
            }
            $runtimeBullets->bhvScalePos = $designTimeBullets->bhvScalePos;

            $runtimeBullets->itemHeight = (!WjsslSliderBuildHelper::HasValue($designTimeBullets->itemHeight)) ? WjsslSliderDefaultValues::BulletHeight : $designTimeBullets->itemHeight;
            $runtimeBullets->itemWidth =  (!WjsslSliderBuildHelper::HasValue($designTimeBullets->itemWidth)) ? WjsslSliderDefaultValues::BulletWidth : $designTimeBullets->itemWidth;
            $runtimeBullets->posAutoCenter = $designTimeBullets->posAutoCenter;
            $runtimeBullets->posBottom = $designTimeBullets->posBottom;
            $runtimeBullets->posLeft = $designTimeBullets->posLeft;
            $runtimeBullets->posRight = $designTimeBullets->posRight;
            $runtimeBullets->posTop = $designTimeBullets->posTop;

            if ( !WjsslSliderBuildHelper::HasValue($runtimeBullets->posTop) && !WjsslSliderBuildHelper::HasValue($runtimeBullets->posBottom))
            {
                $runtimeBullets->posBottom = WjsslSliderDefaultValues::BulletPosition;
            }
            if ( !WjsslSliderBuildHelper::HasValue($runtimeBullets->posLeft) && !WjsslSliderBuildHelper::HasValue($runtimeBullets->posRight))
            {
                $runtimeBullets->posRight = WjsslSliderDefaultValues::BulletPosition;
            }

            $runtimeLayouts->bullets = $runtimeBullets;

            //endregion

            //region BulletNavigatorOptions

            $bulletNavigatorOptions = new WjsslBulletNavigatorOptions();

            if (WjsslSliderBuildHelper::HasValue($designTimeBullets->bhvActionMode) && $designTimeBullets->bhvActionMode != WjsslEnumActionMode::Click)
                $bulletNavigatorOptions->ActionMode = $designTimeBullets->bhvActionMode;

            if (WjsslSliderBuildHelper::HasValue($designTimeBullets->bhvChanceToShow) && $designTimeBullets->bhvChanceToShow != WjsslEnumChanceToShow::Always)
                $bulletNavigatorOptions->ChanceToShow = $designTimeBullets->bhvChanceToShow;

            if (WjsslSliderBuildHelper::HasValue($designTimeBullets->itemOrientation) && $designTimeBullets->itemOrientation != WjsslEnumOrientation::Horizontal)
                $bulletNavigatorOptions->Orientation = $designTimeBullets->itemOrientation;

            if ($designTimeBullets->itemRows != 1)
                $bulletNavigatorOptions->Rows = $designTimeBullets->itemRows;

            $bulletNavigatorOptions->SpacingX = $designTimeBullets->itemSpacingX;

            $bulletNavigatorOptions->SpacingY = $designTimeBullets->itemSpacingY;

            if ($designTimeBullets->bhvSteps != 1)
                $bulletNavigatorOptions->Steps = $designTimeBullets->bhvSteps;

            $runtimeOptions->BulletNavigatorOptions = $bulletNavigatorOptions;

            //endregion
        }

        //endregion

        //region arrows

        if (WjsslSliderBuildHelper::HasValue($designTimeArrows) && WjsslSliderBuildHelper::HasValue($designTimeArrows->theme))
        {
            //region runtimeArrows

            $runtimeArrows = new WjsslRuntimeArrows();

            $runtimeArrows->Skin = $designTimeArrows->theme->skin;

            $runtimeArrows->bhvScaleL = $designTimeArrows->bhvScaleL;
            if ($designTimeArrows->bhvNoScale == true)
            {
                $runtimeArrows->bhvScaleL = 0;
            }
            $runtimeArrows->bhvScalePos = $designTimeArrows->bhvScalePos;

            $runtimeArrows->itemHeight = !WjsslSliderBuildHelper::HasValue($designTimeArrows->itemHeight) ? WjsslSliderDefaultValues::ArrowHeight : $designTimeArrows->itemHeight;
            $runtimeArrows->itemWidth = !WjsslSliderBuildHelper::HasValue($designTimeArrows->itemWidth) ? WjsslSliderDefaultValues::ArrowWidth : $designTimeArrows->itemWidth;
            $runtimeArrows->posAutoCenter =WjsslSliderBuildHelper::HasValue($designTimeArrows->posAutoCenter) ? $designTimeArrows->posAutoCenter : null;
            $runtimeArrows->poslBottom = $designTimeArrows->poslBottom;
            $runtimeArrows->poslLeft = $designTimeArrows->poslLeft;
            $runtimeArrows->poslRight = $designTimeArrows->poslRight;
            $runtimeArrows->poslTop = $designTimeArrows->poslTop;
            $runtimeArrows->posrBottom = $designTimeArrows->posrBottom;
            $runtimeArrows->posrLeft = $designTimeArrows->posrLeft;
            $runtimeArrows->posrRight = $designTimeArrows->posrRight;
            $runtimeArrows->posrTop = $designTimeArrows->posrTop;

            if ( !WjsslSliderBuildHelper::HasValue($runtimeArrows->poslLeft) && !WjsslSliderBuildHelper::HasValue($runtimeArrows->poslRight))
            {
                $runtimeArrows->poslLeft = WjsslSliderDefaultValues::ArrowPosition;
            }
            if ( !WjsslSliderBuildHelper::HasValue($runtimeArrows->poslTop) && !WjsslSliderBuildHelper::HasValue($runtimeArrows->poslBottom))
            {
                $runtimeArrows->poslTop = 0;
            }

            if ( !WjsslSliderBuildHelper::HasValue($runtimeArrows->posrLeft) && !WjsslSliderBuildHelper::HasValue($runtimeArrows->posrRight))
            {
                $runtimeArrows->posrRight = WjsslSliderDefaultValues::ArrowPosition;
            }
            if ( !WjsslSliderBuildHelper::HasValue($runtimeArrows->posrTop) && !WjsslSliderBuildHelper::HasValue($runtimeArrows->posrBottom))
            {
                $runtimeArrows->posrTop = 0;
            }

            $runtimeLayouts->arrows = $runtimeArrows;

            //endregion

            //region ArrowNavigatorOptions

            $arrowNavigatorOptions = new WjsslArrowNavigatorOptions();

            if (WjsslSliderBuildHelper::HasValue($designTimeArrows->bhvChanceToShow) && $designTimeArrows->bhvChanceToShow != WjsslEnumChanceToShow::Always)
                $arrowNavigatorOptions->ChanceToShow = $designTimeArrows->bhvChanceToShow;

            if ($designTimeArrows->bhvSteps != 1)
                $arrowNavigatorOptions->Steps = $designTimeArrows->bhvSteps;

            $runtimeOptions->ArrowNavigatorOptions = $arrowNavigatorOptions;

            //endregion
        }

        //endregion

        //region thumbnails

        if (WjsslSliderBuildHelper::HasValue($designTimeThumbnails) && WjsslSliderBuildHelper::HasValue($designTimeThumbnails->theme))
        {
            $outContainerWidth = $runtimeLayout->slideWidth;
            $outContainerHeight = $runtimeLayout->slideHeight;

            if (WjsslSliderBuildHelper::HasValue($runtimeLayout->slidesWidth))
                $outContainerWidth = $runtimeLayout->slidesWidth;
            if (WjsslSliderBuildHelper::HasValue($runtimeLayout->ocWidth))
                $outContainerWidth = $runtimeLayout->ocWidth;

            if (WjsslSliderBuildHelper::HasValue($runtimeLayout->slidesHeight))
                $outContainerHeight = $runtimeLayout->slidesHeight;
            if (WjsslSliderBuildHelper::HasValue($runtimeLayout->ocHeight))
                $outContainerHeight = $runtimeLayout->ocHeight;

            //region runtimeThumbnails

            $runtimeThumbnails = new WjsslRuntimeThumbnails();

            $runtimeThumbnails->Skin = $designTimeThumbnails->theme->skin;
            $runtimeThumbnails->cntrWidth = !WjsslSliderBuildHelper::HasValue($designTimeThumbnails->cntrWidth) ? $outContainerWidth : $designTimeThumbnails->cntrWidth;
            $runtimeThumbnails->cntrHeight = !WjsslSliderBuildHelper::HasValue($designTimeThumbnails->cntrHeight) ? $outContainerHeight : $designTimeThumbnails->cntrHeight;
            $runtimeThumbnails->cntrBgColor = $designTimeThumbnails->cntrBgColor;

            $runtimeThumbnails->itemHeight = $designTimeThumbnails->itemHeight;
            $runtimeThumbnails->itemWidth = $designTimeThumbnails->itemWidth;

            if(isset($runtimeThumbnails->itemHeight) && $runtimeThumbnails->itemHeight < 1)
            {
                $runtimeThumbnails->itemHeight = WjsslSliderDefaultValues::ThumbnailWidth;
            }

            if(isset($runtimeThumbnails->itemWidth) && $runtimeThumbnails->itemWidth < 1)
            {
                $runtimeThumbnails->itemWidth = WjsslSliderDefaultValues::ThumbnailHeight;
            }

            $runtimeThumbnails->itemSpacingX = $designTimeThumbnails->itemSpacingX;
            $runtimeThumbnails->itemSpacingY = $designTimeThumbnails->itemSpacingY;
            $runtimeThumbnails->itemRows = $designTimeThumbnails->itemRows;
            $runtimeThumbnails->itemCols = $designTimeThumbnails->itemCols;
            $runtimeThumbnails->itemOrientation = $designTimeThumbnails->itemOrientation;

            $runtimeThumbnails->cntrAutoCenter = $designTimeThumbnails->cntrAutoCenter;
            $runtimeThumbnails->cntrBottom = $designTimeThumbnails->cntrBottom;
            $runtimeThumbnails->cntrLeft = $designTimeThumbnails->cntrLeft;
            $runtimeThumbnails->cntrRight = $designTimeThumbnails->cntrRight;
            $runtimeThumbnails->cntrTop = $designTimeThumbnails->cntrTop;

            //calculate thumbnail item size
            if (!WjsslSliderBuildHelper::HasValue($runtimeThumbnails->itemWidth))
            {
                if (($runtimeThumbnails->Skin->itemFullDimension & WjsslEnumOrientation::Horizontal) == WjsslEnumOrientation::Horizontal)
                {
                    $runtimeThumbnails->itemWidth = $runtimeThumbnails->cntrWidth;
                }
                else
                {
                    $runtimeThumbnails->itemWidth = WjsslSliderDefaultValues::ThumbnailWidth;
                }
            }
            if (!WjsslSliderBuildHelper::HasValue($runtimeThumbnails->itemHeight))
            {
                if (($runtimeThumbnails->Skin->itemFullDimension & WjsslEnumOrientation::Vertical) == WjsslEnumOrientation::Vertical)
                {
                    $runtimeThumbnails->itemHeight = $runtimeThumbnails->cntrHeight;
                }
                else
                {
                    $runtimeThumbnails->itemHeight = WjsslSliderDefaultValues::ThumbnailHeight;
                }
            }

            if (!WjsslSliderBuildHelper::HasValue($runtimeThumbnails->cntrLeft) && !WjsslSliderBuildHelper::HasValue($runtimeThumbnails->cntrRight))
            {
                $runtimeThumbnails->cntrLeft = 0;
            }
            if (!WjsslSliderBuildHelper::HasValue($runtimeThumbnails->cntrTop) && !WjsslSliderBuildHelper::HasValue($runtimeThumbnails->cntrBottom))
            {
                $runtimeThumbnails->cntrBottom = 0;
            }

            $runtimeThumbnails->bhvScaleL = $designTimeThumbnails->bhvScaleL;
            if ($designTimeThumbnails->bhvNoScale == true)
            {
                $runtimeThumbnails->bhvScaleL = 0;
            }
            $runtimeThumbnails->bhvScalePos = $designTimeThumbnails->bhvScalePos;

            if (!WjsslSliderBuildHelper::HasValue($runtimeThumbnails->cntrWidth))
                $runtimeThumbnails->cntrWidth = $outContainerWidth;

            if ($runtimeThumbnails->cntrAutoCenter == WjsslEnumOrientation::Horizontal)
            {
                $runtimeThumbnails->cntrLeft = ($outContainerWidth  - $runtimeThumbnails->cntrWidth) / 2;
                $runtimeThumbnails->cntrRight = null;
            }

            if (!WjsslSliderBuildHelper::HasValue($runtimeThumbnails->cntrHeight))
                $runtimeThumbnails->cntrHeight = $outContainerHeight;

            if ($runtimeThumbnails->cntrAutoCenter == WjsslEnumOrientation::Vertical)
            {
                $runtimeThumbnails->cntrTop = ($outContainerHeight  - $runtimeThumbnails->cntrHeight) / 2;
                $runtimeThumbnails->cntrBottom = null;
            }

            if (WjsslSliderBuildHelper::HasValue($runtimeThumbnails->cntrLeft))
                $runtimeThumbnails->cntrRight = null;
            if (WjsslSliderBuildHelper::HasValue($runtimeThumbnails->cntrTop))
                $runtimeThumbnails->cntrBottom = null;

            if (!WjsslSliderBuildHelper::HasValue($runtimeThumbnails->cntrLeft) && !WjsslSliderBuildHelper::HasValue($runtimeThumbnails->cntrRight))
                $runtimeThumbnails->cntrLeft = 0;

            if (!WjsslSliderBuildHelper::HasValue($runtimeThumbnails->cntrBottom) && WjsslSliderBuildHelper::HasValue($runtimeThumbnails->cntrTop))
                $runtimeThumbnails->cntrBottom = 0;

            $runtimeLayouts->thumbnails = $runtimeThumbnails;

            //endregion

            //region ThumbnailNavigatorOptions

            $thumbnailNavigatorOptions = new WjsslThumbnailNavigatorOptions();

            if ($designTimeThumbnails->bhvChanceToShow != WjsslEnumChanceToShow::Always)
                $thumbnailNavigatorOptions->ChanceToShow = $designTimeThumbnails->bhvChanceToShow;

            if ($designTimeThumbnails->bhvActionMode != WjsslEnumActionMode::Click)
                $thumbnailNavigatorOptions->ActionMode = $designTimeThumbnails->bhvActionMode;

            if ($designTimeThumbnails->bhvChanceToShow != WjsslEnumChanceToShow::Always)
                $thumbnailNavigatorOptions->ChanceToShow = $designTimeThumbnails->bhvChanceToShow;

            if ($designTimeThumbnails->itemOrientation != WjsslEnumOrientation::Horizontal)
                $thumbnailNavigatorOptions->Orientation = $designTimeThumbnails->itemOrientation;

            if ($designTimeThumbnails->itemRows != 1)
                $thumbnailNavigatorOptions->Rows = $designTimeThumbnails->itemRows;

            //$thumbnailNavigatorOptions->Cols = $designTimeThumbnails->itemCols;

            $thumbnailNavigatorOptions->SpacingX = $designTimeThumbnails->itemSpacingX;

            $thumbnailNavigatorOptions->SpacingY = $designTimeThumbnails->itemSpacingY;

            $thumbnailNavigatorOptions->Align = $designTimeThumbnails->itemAlign;

            if ($designTimeThumbnails->bhvLoop != WjsslEnumLoop::Loop)
                $thumbnailNavigatorOptions->Loop = $designTimeThumbnails->bhvLoop;

            if ($designTimeThumbnails->bhvNoDrag == true)
                $thumbnailNavigatorOptions->NoDrag = $designTimeThumbnails->bhvNoDrag;

            //region ignore column, item align calculation

            ////calculation begin

            //$spacingX = 0;
            //$spacingY = 0;

            //if (WjsslSliderBuildHelper::HasValue($thumbnailNavigatorOptions->SpacingX))
            //{
            //    $spacingX = $thumbnailNavigatorOptions->SpacingX;
            //}
            //if (WjsslSliderBuildHelper::HasValue($thumbnailNavigatorOptions->SpacingY))
            //{
            //    $spacingY = $thumbnailNavigatorOptions->SpacingY;
            //}

            ////calculate columns
            //if (!WjsslSliderBuildHelper::HasValue($thumbnailNavigatorOptions->Cols))
            //{
            //    if ($thumbnailNavigatorOptions->Orientation == WjsslEnumOrientation::Vertical)
            //    {
            //        $thumbnailNavigatorOptions->Cols = (int)ceil(($runtimeThumbnails->cntrHeight - $spacingY) / ($runtimeThumbnails->itemHeight + $spacingY));
            //    }
            //    else
            //    {
            //        $thumbnailNavigatorOptions->Cols = (int)ceil(($runtimeThumbnails->cntrWidth - $spacingX) / ($runtimeThumbnails->itemWidth + $spacingX));
            //    }

            //    if ($thumbnailNavigatorOptions->Cols == 0)
            //    {
            //        $thumbnailNavigatorOptions->Cols = 1;
            //    }
            //}

            ////calculate align
            //if (!WjsslSliderBuildHelper::HasValue($thumbnailNavigatorOptions->Align))
            //{
            //    if ($thumbnailNavigatorOptions->Orientation == WjsslEnumOrientation::Vertical)
            //    {
            //        $displayHeight = ($runtimeThumbnails->itemHeight + $spacingY) * $thumbnailNavigatorOptions->Cols - $spacingY;
            //        $displayHeight = min($displayHeight, $runtimeThumbnails->cntrHeight);
            //        $thumbnailNavigatorOptions->Align = ($displayHeight  - $runtimeThumbnails->itemHeight) / 2;
            //    }
            //    else
            //    {
            //        $displayWidth = ($runtimeThumbnails->itemWidth + $spacingX) * $thumbnailNavigatorOptions->Cols - $spacingX;
            //        $displayWidth = min($displayWidth, $runtimeThumbnails->cntrWidth);
            //        $thumbnailNavigatorOptions->Align = ($displayWidth  - $runtimeThumbnails->itemWidth) / 2;
            //    }
            //}

            //endregion

            $runtimeOptions->ThumbnailNavigatorOptions = $thumbnailNavigatorOptions;

            //endregion
        }

        //endregion

        //region loading

        if (!WjsslSliderBuildHelper::HasValue($designTimeLoading))
        {
            if (WjsslSliderBuildHelper::HasValue($designTimeLayout->ldTheme))
            {
                $designTimeLoading = new WjsslDesignTimeLoading();
                $designTimeLoading->theme = $designTimeLayout->ldTheme;
            }
        }

        if (WjsslSliderBuildHelper::HasValue($designTimeLoading) && WjsslSliderBuildHelper::HasValue($designTimeLoading->theme))
        {
            $runtimeLoading = new WjsslRuntimeLoading();
            $runtimeLoading->Skin = $designTimeLoading->theme->skin;

            $runtimeLoading->cntrBgColor = $designTimeLoading->cntrBgColor;
            $runtimeLoading->itemHeight = $designTimeLoading->itemHeight;
            $runtimeLoading->itemWidth = $designTimeLoading->itemWidth;

            if (!WjsslSliderBuildHelper::HasValue($runtimeLoading->itemWidth))
            {
                if (WjsslSliderBuildHelper::HasValue($runtimeLoading->Skin) && WjsslSliderBuildHelper::HasValue($runtimeLoading->Skin->defaultValue) && $runtimeLoading->Skin->defaultValue->itemWidth > 0)
                {
                    $runtimeLoading->itemWidth = $runtimeLoading->Skin->defaultValue->itemWidth;
                }
                else
                {
                    $runtimeLoading->itemWidth = WjsslSliderDefaultValues::LoadingIconWidth;
                }
            }

            if (!WjsslSliderBuildHelper::HasValue($runtimeLoading->itemHeight))
            {
                if (WjsslSliderBuildHelper::HasValue($runtimeLoading->Skin) && WjsslSliderBuildHelper::HasValue($runtimeLoading->Skin->defaultValue) && $runtimeLoading->Skin->defaultValue->itemHeight > 0)
                {
                    $runtimeLoading->itemHeight = $runtimeLoading->Skin->defaultValue->itemHeight;
                }
                else
                {
                    $runtimeLoading->itemHeight = WjsslSliderDefaultValues::LoadingIconHeight;
                }
            }

            if (!WjsslSliderBuildHelper::HasValue($runtimeLoading->cntrBgColor))
            {
                if (WjsslSliderBuildHelper::HasValue($runtimeLoading->Skin) && WjsslSliderBuildHelper::HasValue($runtimeLoading->Skin->defaultValue) && WjsslSliderBuildHelper::HasValue($runtimeLoading->Skin->defaultValue->cntrBgColor))
                {
                    $runtimeLoading->cntrBgColor = $runtimeLoading->Skin->defaultValue->cntrBgColor;
                }
                else
                {
                    $runtimeLoading->cntrBgColor = 'rgba(0,0,0,0.7)';
                }
            }

            $runtimeLayouts->loading = $runtimeLoading;
        }

        //endregion

        //endregion

        $runtimeDocument->Options = $runtimeOptions;
        $runtimeDocument->Layouts = $runtimeLayouts;
        $runtimeDocument->ReponsiveOptions = $responsiveOptions;
        $runtimeDocument->Slides = $runtimeSlideList;

        return $runtimeDocument;
    }

    public static function calculateSlidesContainerSize(WjsslDesignTimeLayout $designTimeLayout)
        {
            $size = new stdClass();

           $slidesWidth = $designTimeLayout->slidesWidth;
           $slidesHeight = $designTimeLayout->slidesHeight;

           $slideWidth = (int)$designTimeLayout->slideWidth;
            if ($slideWidth == 0)
                $slideWidth = 600;
           $slideHeight = (int)$designTimeLayout->slideHeight;
            if ($slideHeight == 0)
                $slideHeight = 300;
           $slideColumns = $designTimeLayout->slideCols;
            if (empty($slideColumns))
                $slideColumns = 1;
           $slideSpacing = $designTimeLayout->slideSpacing;
            if (empty($slideSpacing))
                $slideSpacing = 0;

            $orientation = $designTimeLayout->slideOrientation;
            if (empty($orientation))
                $orientation = WjsslEnumOrientation::Horizontal;

           $stepLength = (($orientation == WjsslEnumOrientation::Horizontal) ? $slideWidth : $slideHeight) + $slideSpacing;

            if (!empty($slidesWidth))
            {
                $size->Width = $slidesWidth;
            }
            else
            {
                $size->Width = (($orientation == WjsslEnumOrientation::Horizontal) ? ($stepLength * $slideColumns - $slideSpacing) : $slideWidth);
            }

            if (!empty($slidesHeight))
            {
                $size->Height = $slidesHeight;
            }
            else
            {
                $size->Height = (($orientation == WjsslEnumOrientation::Vertical) ? ($stepLength * $slideColumns - $slideSpacing) : $slideHeight);
            }

            return $size;
        }

        public static function toRuntimeSlide(&$runtimeSlideoTransitionLists, &$runtimeSlideoBreakLists, &$runtimeSlideoMotionControlList, $designTimeSlide)
        {
            $runtimeSlide = new WjsslRuntimeSlide();

            $runtimeSlide->bgColor = $designTimeSlide->bgColor;
            $runtimeSlide->bgGradient = $designTimeSlide->bgGradient;

            $runtimeSlide->image = $designTimeSlide->image;
            $runtimeSlide->fillMode = $designTimeSlide->fillMode;
            $runtimeSlide->opacity = $designTimeSlide->opacity;
            $runtimeSlide->blur = $designTimeSlide->blur;
            $runtimeSlide->grayscale = $designTimeSlide->grayscale;

            $runtimeSlide->image = $designTimeSlide->image;
            $runtimeSlide->link = $designTimeSlide->link;
            $runtimeSlide->tgt = $designTimeSlide->tgt;
            $runtimeSlide->pDepth = $designTimeSlide->pDepth;
            $runtimeSlide->poY = $designTimeSlide->poY;
            $runtimeSlide->poX = $designTimeSlide->poX;
            $runtimeSlide->idle = $designTimeSlide->idle;
            $runtimeSlide->thumb = $designTimeSlide->thumb;

            //region layers

            $designTimeLayers = $designTimeSlide->layers;
            $designTimeLayers = empty($designTimeLayers) ? array() : $designTimeLayers;

            $runtimeLayerList = array();

            if ($designTimeSlide->layers != null)
            {
                foreach ($designTimeSlide->layers as $childDesignTimeLayer)
                {
                    //ignore invisible layer
                    if (($childDesignTimeLayer->ctrls & (int)WjsslEnumLayerControls::Invisible) == (int)WjsslEnumLayerControls::Invisible)
                        continue;

                    $childRuntimeLayer = self::toRuntimeLayer($runtimeSlideoTransitionLists, $runtimeSlideoMotionControlList, $childDesignTimeLayer);

                    if ($childRuntimeLayer->hasNormalAnimation())
                    {
                        if ($childRuntimeLayer->normalMotionBegin != null)
                        {
                            if ($runtimeSlide->normalMotionBegin == null)
                            {
                                $runtimeSlide->normalMotionBegin = $childRuntimeLayer->normalMotionBegin;
                            }

                            $runtimeSlide->normalMotionBegin = min($runtimeSlide->normalMotionBegin, $childRuntimeLayer->normalMotionBegin);
                        }

                        $runtimeSlide->normalMotionEnd = max($runtimeSlide->normalMotionEnd, $childRuntimeLayer->normalMotionEnd);
                    }

                    $runtimeSlide->has3d = $runtimeSlide->has3d || $childRuntimeLayer->has3d || $childRuntimeLayer->has3dChild;
                    $runtimeSlide->has3dSpaceLayer = $runtimeSlide->has3dSpaceLayer || $childRuntimeLayer->has3dSpace || $childRuntimeLayer->has3dSpaceChild;
                    $runtimeSlide->hasTransform = $runtimeSlide->hasTransform || $childRuntimeLayer->hasTransform || $childRuntimeLayer->hasTransformChild;
                    $runtimeSlide->hasRepeat = $runtimeSlide->hasRepeat || $childRuntimeLayer->hasRepeat || $childRuntimeLayer->hasRepeatChild;
                    $runtimeSlide->hasActionAnimationLayer = $runtimeSlide->hasActionAnimationLayer || $childRuntimeLayer->hasActionAnimation() || $childRuntimeLayer->hasActionAnimationChild;

                    array_push($runtimeLayerList, $childRuntimeLayer);
                }

                if (count($runtimeLayerList) > 0)
                {
                    $runtimeSlide->hasLayer = true;
                }
            }

            $runtimeSlide->layers = $runtimeLayerList;

            //endregion

            //region breaks

            $designTimeBreaks = $designTimeSlide->breaks;
            $designTimeBreaks = empty($designTimeBreaks) ? array() : $designTimeBreaks;
            $runtimeSlideoBreakList = new WjsslDocumentNodeCollection();

            foreach ($designTimeBreaks as $designTimeBreak)
            {
                $runtimeBreak = new WjsslRuntimeSlideoBreak();
                $runtimeBreak->b = (int)($designTimeBreak->begin * 2.5);
                $runtimeBreak->d = $designTimeBreak->idle;
                if (WjsslSliderBuildHelper::HasValue($designTimeBreak->type) && $designTimeBreak->type != WjsslEnumBreakPointType::Idle)
                    $runtimeBreak->t = $designTimeBreak->type;

                $runtimeSlideoBreakList[] = $runtimeBreak;
            }

            if (count($runtimeSlideoBreakList) > 0)
            {
                $runtimeSlideoBreakLists[] = $runtimeSlideoBreakList;
                $runtimeSlide->slideoBreakIndex = count($runtimeSlideoBreakLists) - 1;
            }
            //endregion
            return $runtimeSlide;
        }

        private static function toRuntimeLayer(&$runtimeSlideoTransitionLists, &$runtimeSlideoMotionControlList, $designTimeLayer) {
            $runtimeLayer = new WjsslRuntimeLayer();

            $conditionPlayThisLayer = false;
            $conditionPlayChildLayers = false;

            $layerNormalMotionBegin = null;
            $layerNormalMotionEnd = 0;
            $layerActionMotionBegin = null;
            $layerActionMotionEnd = 0;

            $layerHas3d = false;
            $layerHas3dSpace = false;
            $layerHasTransform = false;
            $layerHasRepeat = false;

            //region static properties

            $runtimeLayer->bgColor = $designTimeLayer->bgColor;

            //added since 6->0->0 20170908
            $runtimeLayer->bgImageEx = $designTimeLayer->bgImageEx;
            if (WjsslSliderBuildHelper::HasValue($runtimeLayer->bgImageEx) && !WjsslSliderBuildHelper::EmptyOrWhiteSpace($designTimeLayer->bgImage))
            {
                $backgroundImageInfo = new WjsslBackgroundImageInfo();
                $backgroundImageInfo->image = $designTimeLayer->bgImage;
                $backgroundImageInfo->repeat = $designTimeLayer->bgRepeat;
                $runtimeLayer->bgImageEx = $backgroundImageInfo;
            }

            //moved to $runtimeLayer->bgImageEx since 6->0->0 20170908
            //runtimeLayer->bgImage = $designTimeLayer->bgImage;
            //runtimeLayer->bgRepeat = $designTimeLayer->bgRepeat;

            $runtimeLayer->content = $designTimeLayer->content;
            $runtimeLayer->image = $designTimeLayer->image;
            $runtimeLayer->type = $designTimeLayer->type;
            $runtimeLayer->width = (int)$designTimeLayer->width;
            $runtimeLayer->height = (int)$designTimeLayer->height;

            //added since 6->0->0
            if (!WjsslSliderBuildHelper::HasValue($runtimeLayer->width))
            {
                $runtimeLayer->width = 100;
            }
            if (!WjsslSliderBuildHelper::HasValue($runtimeLayer->height))
            {
                $runtimeLayer->height = 50;
            }

            //runtimeLayer->x = $designTimeLayer->x;
            //runtimeLayer->y = $designTimeLayer->y;

            $runtimeLayer->className = $designTimeLayer->className;

            //region since 8.0.0, 20180305

            $runtimeLayer->id = $designTimeLayer->id;

            //endregion

            $runtimeLayer->ptrEvts = $designTimeLayer->ptrEvts;
            $runtimeLayer->ofX = $designTimeLayer->ofX;
            $runtimeLayer->ofY = $designTimeLayer->ofY;

            //region since 8.0.0, 20180305

            if(!is_null($designTimeLayer->pDepth)) {
                $layerHas3dSpace = true;

                $runtimeLayer->pDepth = $designTimeLayer->pDepth;
                $runtimeLayer->poY = $designTimeLayer->poY;
                $runtimeLayer->poX = $designTimeLayer->poX;
            }

            //endregion

            //added since 6.0.0 20170908
            $runtimeLayer->border = $designTimeLayer->border;
            if (!WjsslSliderBuildHelper::HasValue($runtimeLayer->border) && (WjsslSliderBuildHelper::HasValue($designTimeLayer->borderStyle) || WjsslSliderBuildHelper::HasValue($designTimeLayer->borderRadius)))
            {
                $borderInfo = new WjsslBorderInfo();
                $borderInfo->style = $designTimeLayer->borderStyle;
                $borderInfo->width = $designTimeLayer->borderWidth;
                $borderInfo->color = $designTimeLayer->borderColor;
                $borderInfo->radius = $designTimeLayer->borderRadius;

                $runtimeLayer->border = $borderInfo;
            }

            //moved to $runtimeLayer->border since 6->0->0 20170908
            //runtimeLayer->borderStyle = $designTimeLayer->borderStyle;
            //runtimeLayer->borderWidth = $designTimeLayer->borderWidth;
            //runtimeLayer->borderColor = $designTimeLayer->borderColor;
            //runtimeLayer->borderRadius = $designTimeLayer->borderRadius;

            $runtimeLayer->to = $designTimeLayer->to;
            $runtimeLayer->backVisibility = $designTimeLayer->backVisibility;
            $runtimeLayer->font = $designTimeLayer->font;
            $runtimeLayer->fontEx = $designTimeLayer->fontEx;
            $runtimeLayer->fontSize = $designTimeLayer->fontSize;

            if(!WjsslSliderBuildHelper::EmptyOrWhiteSpace($designTimeLayer->fontWeight))
            {
                $runtimeLayer->fontWeight = $designTimeLayer->fontWeight;
            }

            if(!WjsslSliderBuildHelper::EmptyOrWhiteSpace($designTimeLayer->color))
            {
                $runtimeLayer->color = $designTimeLayer->color;
            }

            $runtimeLayer->italic = $designTimeLayer->italic;

            //added since 6->0->0 20170908
            $runtimeLayer->lineHeightEx = $designTimeLayer->lineHeightEx;
            if (!WjsslSliderBuildHelper::HasValue($runtimeLayer->lineHeightEx) && WjsslSliderBuildHelper::HasValue($designTimeLayer->lineHeight))
            {
                $fontSize = !WjsslSliderBuildHelper::HasValue($designTimeLayer->fontSize) ? 12 : $designTimeLayer->fontSize;
                $runtimeLayer->lineHeightEx = round($designTimeLayer->lineHeight / $fontSize, 2);
            }

            $runtimeLayer->letterSpacing = $designTimeLayer->letterSpacing;

            //moved to $runtimeLayer->lineHeightEx since 6->0->0 20170908
            //runtimeLayer->lineHeight = $designTimeLayer->lineHeight;

            if(!WjsslSliderBuildHelper::EmptyOrWhiteSpace($designTimeLayer->textAlign))
            {
                $runtimeLayer->textAlign = $designTimeLayer->textAlign;
            }

            //changed since 6->0->0 20170908
            if (WjsslSliderBuildHelper::HasValue($designTimeLayer->padding))
            {
                $runtimeLayer->paddingX = $designTimeLayer->padding;
                $runtimeLayer->paddingT = $designTimeLayer->padding;
                $runtimeLayer->paddingY = $designTimeLayer->padding;
                $runtimeLayer->paddingM = $designTimeLayer->padding;
            }
            else
            {
                $runtimeLayer->paddingX = $designTimeLayer->paddingX;
                $runtimeLayer->paddingT = $designTimeLayer->paddingT;
                $runtimeLayer->paddingY = $designTimeLayer->paddingY;
                $runtimeLayer->paddingM = $designTimeLayer->paddingM;
            }

            //added since 6->0->0 20170908
            $runtimeLayer->linkEx = $designTimeLayer->linkEx;
            if (!WjsslSliderBuildHelper::HasValue($runtimeLayer->linkEx) && WjsslSliderBuildHelper::HasValue($designTimeLayer->link))
            {
                $linkInfo = new WjsslLinkInfo();
                $linkInfo->link = $designTimeLayer->link;
                $linkInfo->tgt = $designTimeLayer->tgt;

                $runtimeLayer->linkEx = $linkInfo;
            }

            //moved to $runtimeLayer->linkEx since 6->0->0 20170908
            //runtimeLayer->link = $designTimeLayer->link;
            //runtimeLayer->tgt = $designTimeLayer->tgt;

            $runtimeLayer->blend = $designTimeLayer->blend;
            //$runtimeLayer->isolation = $designTimeLayer->isolation;

            if (!WjsslSliderBuildHelper::HasValue($runtimeLayer->to) && WjsslSliderBuildHelper::HasValue($designTimeLayer->toX) || WjsslSliderBuildHelper::HasValue($designTimeLayer->toY))
            {
                $percentage = WjsslSliderBuildHelper::BuildPercentage($designTimeLayer->toX, $designTimeLayer->toY);
                $runtimeLayer->to = $percentage;
            }

            $runtimeLayer->textShadow = $designTimeLayer->textShadow;

            //added since 8.2 20180715
            $runtimeLayer->boxShadow = $designTimeLayer->boxShadow;

            //added since 8.9.0, 20180831
            $runtimeLayer->acclk = $designTimeLayer->acclk;

            //endregion

            //region action

            if ($designTimeLayer->conditions != null && !empty($designTimeLayer->conditions->play))
            {
                $conditionPlayApply = $designTimeLayer->conditions->play & WjsslEnumConditionPlay::Apply;
                $conditionPlayThisLayer = ($conditionPlayApply & WjsslEnumConditionPlay::ApplyThisLayer) == WjsslEnumConditionPlay::ApplyThisLayer;
                $conditionPlayChildLayers = ($conditionPlayApply & WjsslEnumConditionPlay::ApplyChildLayers) == WjsslEnumConditionPlay::ApplyChildLayers;

                $runtimeLayer->conditions = $designTimeLayer->conditions;
            }

            //endregion

            //region motions

            $designTimeMotions = $designTimeLayer->motions;
            $runtimeSlideoTransitionList = new WjsslDocumentNodeCollection();

            $layerX = 0;
            $layerY = 0;
            $layerIndex = 0;
            $layerSkewX = 0;
            $layerSkewY = 0;
            $layerOpacity = 1;
            $layerRotate = 0;
            $layerRotateX = 0;
            $layerRotateY = 0;
            $layerScaleX = 1;
            $layerScaleY = 1;
            $layerTranslateZ = 0;
            $layerClipTop = 0;
            $layerClipLeft = 0;
            $layerClipBottom = 1;
            $layerClipRight = 1;

            $motionPointBeginTime = 0;

            for ($i=0; $i < count($designTimeMotions); $i++)
            {
                $designTimeMotion = $designTimeMotions[$i];
                $designTimeSlideoTransition = $designTimeMotion->trans;
                $isActualMotion = false;

                if ($designTimeSlideoTransition != null)
                {
                    $runtimeSlideoTransition = new WjsslRuntimeSlideoTransition();
                    $runtimeSlideoTransitionEasing = new WjsslRuntimeSlideoTransitionEase();

                    if ($motionPointBeginTime == 0 && $designTimeMotion->duration == 0)
                    {
                        $runtimeSlideoTransition->b = -1;
                        $runtimeSlideoTransition->d = 1;
                    }
                    else
                    {
                        $runtimeSlideoTransition->b = $motionPointBeginTime;
                        $runtimeSlideoTransition->d = (int)($designTimeMotion->duration * 2.5);
                    }

                    //index
                    if ($designTimeSlideoTransition->i != null)
                    {
                        if ($designTimeSlideoTransition->i->v != $layerIndex)
                        {
                            $isActualMotion = true;
                            $runtimeSlideoTransition->i = $designTimeSlideoTransition->i->v - $layerIndex;
                            $layerIndex = $designTimeSlideoTransition->i->v;

                            if ($designTimeSlideoTransition->i->e != 0)
                                $runtimeSlideoTransitionEasing->i = $designTimeSlideoTransition->i->e;
                        }
                    }

                    //skew x
                    if ($designTimeSlideoTransition->kX != null)
                    {
                        if ($designTimeSlideoTransition->kX->v != $layerSkewX)
                        {
                            $isActualMotion = true;
                            $layerHasTransform = true;

                            $isActualMotion = true;
                            $runtimeSlideoTransition->kX = $designTimeSlideoTransition->kX->v - $layerSkewX;
                            $layerSkewX = $designTimeSlideoTransition->kX->v;

                            if ($designTimeSlideoTransition->kX->e != 0)
                                $runtimeSlideoTransitionEasing->kX = $designTimeSlideoTransition->kX->e;
                        }
                    }

                    //skew y
                    if ($designTimeSlideoTransition->kY != null)
                    {
                        if ($designTimeSlideoTransition->kY->v != $layerSkewY)
                        {
                            $isActualMotion = true;
                            $layerHasTransform = true;

                            $runtimeSlideoTransition->kY = $designTimeSlideoTransition->kY->v - $layerSkewY;
                            $layerSkewY = $designTimeSlideoTransition->kY->v;

                            if ($designTimeSlideoTransition->kY->e != 0)
                                $runtimeSlideoTransitionEasing->kY = $designTimeSlideoTransition->kY->e;
                        }
                    }

                    //opacity
                    if ($designTimeSlideoTransition->o != null)
                    {
                        if ($designTimeSlideoTransition->o->v != $layerOpacity)
                        {
                            $isActualMotion = true;

                            $runtimeSlideoTransition->o = $designTimeSlideoTransition->o->v - $layerOpacity;
                            $layerOpacity = $designTimeSlideoTransition->o->v;

                            if ($designTimeSlideoTransition->o->e != 0)
                                $runtimeSlideoTransitionEasing->o = $designTimeSlideoTransition->o->e;
                        }
                    }

                    //rotate
                    if ($designTimeSlideoTransition->r != null)
                    {
                        if ($designTimeSlideoTransition->r->v != $layerRotate)
                        {
                            $isActualMotion = true;
                            $layerHasTransform = true;

                            $runtimeSlideoTransition->r = $designTimeSlideoTransition->r->v - $layerRotate;
                            $layerRotate = $designTimeSlideoTransition->r->v;

                            if ($designTimeSlideoTransition->r->e != 0)
                                $runtimeSlideoTransitionEasing->r = $designTimeSlideoTransition->r->e;
                        }
                    }

                    if ($designTimeSlideoTransition->rX != null)
                    {
                        if ($designTimeSlideoTransition->rX->v != $layerRotateX)
                        {
                            $isActualMotion = true;
                            $layerHasTransform = true;
                            $layerHas3d = true;

                            $runtimeSlideoTransition->rX = $designTimeSlideoTransition->rX->v - $layerRotateX;
                            $layerRotateX = $designTimeSlideoTransition->rX->v;

                            if ($designTimeSlideoTransition->rX->e != 0)
                                $runtimeSlideoTransitionEasing->rX = $designTimeSlideoTransition->rX->e;
                        }
                    }

                    if ($designTimeSlideoTransition->rY != null)
                    {
                        if ($designTimeSlideoTransition->rY->v != $layerRotateY)
                        {
                            $isActualMotion = true;
                            $layerHasTransform = true;
                            $layerHas3d = true;

                            $runtimeSlideoTransition->rY = $designTimeSlideoTransition->rY->v - $layerRotateY;
                            $layerRotateY = $designTimeSlideoTransition->rY->v;

                            if ($designTimeSlideoTransition->rY->e != 0)
                                $runtimeSlideoTransitionEasing->rY = $designTimeSlideoTransition->rY->e;
                        }
                    }

                    if ($designTimeSlideoTransition->sX != null)
                    {
                        if ($designTimeSlideoTransition->sX->v != $layerScaleX)
                        {
                            $isActualMotion = true;
                            $layerHasTransform = true;
                            //$layerHas3d = true;

                            $runtimeSlideoTransition->sX = $designTimeSlideoTransition->sX->v - $layerScaleX;
                            $layerScaleX = $designTimeSlideoTransition->sX->v;

                            if ($designTimeSlideoTransition->sX->e != 0)
                                $runtimeSlideoTransitionEasing->sX = $designTimeSlideoTransition->sX->e;
                        }
                    }

                    if ($designTimeSlideoTransition->sY != null)
                    {
                        if ($designTimeSlideoTransition->sY->v != $layerScaleY)
                        {
                            $isActualMotion = true;
                            $layerHasTransform = true;
                            //$layerHas3d = true;

                            $runtimeSlideoTransition->sY = $designTimeSlideoTransition->sY->v - $layerScaleY;
                            $layerScaleY = $designTimeSlideoTransition->sY->v;

                            if ($designTimeSlideoTransition->sY->e != 0)
                                $runtimeSlideoTransitionEasing->sY = $designTimeSlideoTransition->sY->e;
                        }
                    }

                    if ($designTimeSlideoTransition->tZ != null)
                    {
                        if ($designTimeSlideoTransition->tZ->v != $layerTranslateZ)
                        {
                            $isActualMotion = true;
                            $layerHasTransform = true;
                            $layerHas3d = true;

                            $runtimeSlideoTransition->tZ = $designTimeSlideoTransition->tZ->v - $layerTranslateZ;
                            $layerTranslateZ = $designTimeSlideoTransition->tZ->v;

                            if ($designTimeSlideoTransition->tZ->e != 0)
                                $runtimeSlideoTransitionEasing->tZ = $designTimeSlideoTransition->tZ->e;
                        }
                    }

                    //x
                    if ($designTimeSlideoTransition->x != null)
                    {
                        if ($motionPointBeginTime == 0 && $designTimeMotion->duration == 0)
                        {
                            $runtimeLayer->x = (int)round($designTimeSlideoTransition->x->v);
                            $layerX = $designTimeSlideoTransition->x->v;
                        }
                        else
                        {
                            if ($designTimeSlideoTransition->x->v != $layerX)
                            {
                                $isActualMotion = true;
                                $layerHasTransform = true;

                                $runtimeSlideoTransition->x = $designTimeSlideoTransition->x->v - $layerX;
                                $layerX = $designTimeSlideoTransition->x->v;

                                if ($designTimeSlideoTransition->x->e != 0)
                                    $runtimeSlideoTransitionEasing->x = $designTimeSlideoTransition->x->e;
                            }
                        }
                    }

                    //y
                    if ($designTimeSlideoTransition->y != null)
                    {
                        if ($motionPointBeginTime == 0 && $designTimeMotion->duration == 0)
                        {
                            $runtimeLayer->y = (int)round($designTimeSlideoTransition->y->v);
                            $layerY = $designTimeSlideoTransition->y->v;
                        }
                        else
                        {
                            if ($designTimeSlideoTransition->y->v != $layerY)
                            {
                                $isActualMotion = true;
                                $layerHasTransform = true;

                                $runtimeSlideoTransition->y = $designTimeSlideoTransition->y->v - $layerY;
                                $layerY = $designTimeSlideoTransition->y->v;

                                if ($designTimeSlideoTransition->y->e != 0)
                                    $runtimeSlideoTransitionEasing->y = $designTimeSlideoTransition->y->e;
                            }
                        }
                    }

                    //region Clip

                    $designTimeTransitionFactorClipHor = $designTimeSlideoTransition->cHor;
                    $designTimeTransitionFactorClipVer = $designTimeSlideoTransition->cVer;
                    if ($designTimeTransitionFactorClipHor != null || $designTimeTransitionFactorClipVer != null)
                    {
                        if ($designTimeTransitionFactorClipHor == null)
                            $designTimeTransitionFactorClipHor = new WjsslDesignTimeTransitionFactorClipHor();

                        if ($designTimeTransitionFactorClipVer == null)
                            $designTimeTransitionFactorClipVer = new WjsslDesignTimeTransitionFactorClipVer();

                        $runtimeSlideoTransitionClip = new WjsslRuntimeSlideoTransitionClip();
                        $runtimeSlideoTransitionClipEasing = new WjsslRuntimeSlideoTransitionClip();
                        $hasClipEasing = false;
                        $hasClip = false;

                        if ($designTimeTransitionFactorClipHor->x != null)
                        {
                            if ($designTimeTransitionFactorClipHor->x->v != $layerClipLeft)
                            {
                                $hasClip = true;
                                $runtimeSlideoTransitionClip->x = ($designTimeTransitionFactorClipHor->x->v - $layerClipLeft) * $runtimeLayer->width;
                                $layerClipLeft = $designTimeTransitionFactorClipHor->x->v;

                                if ($designTimeTransitionFactorClipHor->x->e != 0 && $designTimeTransitionFactorClipHor->x->e != null)
                                {
                                    $hasClipEasing = true;
                                    $runtimeSlideoTransitionClipEasing->x = $designTimeTransitionFactorClipHor->x->e;
                                }
                            }
                        }

                        if ($designTimeTransitionFactorClipHor->t != null)
                        {
                            if ($designTimeTransitionFactorClipHor->t->v != $layerClipRight)
                            {
                                $hasClip = true;
                                $runtimeSlideoTransitionClip->t = ($designTimeTransitionFactorClipHor->t->v - $layerClipRight) * $runtimeLayer->width;
                                $layerClipRight = $designTimeTransitionFactorClipHor->t->v;

                                if ($designTimeTransitionFactorClipHor->t->e != 0 && $designTimeTransitionFactorClipHor->t->e != null)
                                {
                                    $hasClipEasing = true;
                                    $runtimeSlideoTransitionClipEasing->t = $designTimeTransitionFactorClipHor->t->e;
                                }
                            }
                        }

                        if ($designTimeTransitionFactorClipVer->y != null)
                        {
                            if ($designTimeTransitionFactorClipVer->y->v != $layerClipTop)
                            {
                                $hasClip = true;
                                $runtimeSlideoTransitionClip->y = ($designTimeTransitionFactorClipVer->y->v - $layerClipTop) * $runtimeLayer->height;
                                $layerClipTop = $designTimeTransitionFactorClipVer->y->v;

                                if ($designTimeTransitionFactorClipVer->y->e != 0 && $designTimeTransitionFactorClipVer->y->e != null)
                                {
                                    $hasClipEasing = true;
                                    $runtimeSlideoTransitionClipEasing->y = $designTimeTransitionFactorClipVer->y->e;
                                }
                            }
                        }

                        if ($designTimeTransitionFactorClipVer->m != null)
                        {
                            if ($designTimeTransitionFactorClipVer->m->v != $layerClipBottom)
                            {
                                $hasClip = true;
                                $runtimeSlideoTransitionClip->m = ($designTimeTransitionFactorClipVer->m->v - $layerClipBottom) * $runtimeLayer->height;
                                $layerClipBottom = $designTimeTransitionFactorClipVer->m->v;

                                if ($designTimeTransitionFactorClipVer->m->e != 0 && $designTimeTransitionFactorClipVer->m->e != null)
                                {
                                    $hasClipEasing = true;
                                    $runtimeSlideoTransitionClipEasing->m = $designTimeTransitionFactorClipVer->m->e;
                                }
                            }
                        }

                        if ($hasClip)
                        {
                            $isActualMotion = true;
                            $runtimeSlideoTransition->c = $runtimeSlideoTransitionClip;

                            if ($hasClipEasing)
                                $runtimeSlideoTransitionEasing->c = $runtimeSlideoTransitionClipEasing;
                        }
                    }

                    //endregion

                    if ($isActualMotion)
                    {
                        if ($runtimeSlideoTransitionEasing->hasValue())
                            $runtimeSlideoTransition->e = $runtimeSlideoTransitionEasing;

                        $runtimeSlideoTransitionList->append($runtimeSlideoTransition);


                        if($conditionPlayThisLayer)
                        {
                            //action motion
                            if (is_null($layerActionMotionBegin))
                            {
                                $layerActionMotionBegin = (int)$runtimeSlideoTransition->b;
                            }

                            $layerActionMotionEnd = (int)$motionPointBeginTime + (int)$runtimeSlideoTransition->d;
                        }
                        else
                        {
                            //normal motion
                            if(is_null($layerNormalMotionBegin))
                            {
                                $layerNormalMotionBegin = (int)$runtimeSlideoTransition->b;
                            }

                            $layerNormalMotionEnd = (int)$motionPointBeginTime + (int)$runtimeSlideoTransition->d;
                        }
                    }
                }

                $motionPointBeginTime += (int)($designTimeMotion->duration * 2.5);
            }

            if (count($runtimeSlideoTransitionList) > 0)
            {
                $runtimeSlideoTransitionLists->append($runtimeSlideoTransitionList);
                $runtimeLayer->slideoTransitionIndex = count($runtimeSlideoTransitionLists) - 1;
            }

            //endregion

            //region repeat

            if (WjsslSliderBuildHelper::HasValue($designTimeLayer->mctrl) && WjsslSliderBuildHelper::HasValue($designTimeLayer->mctrl->r))
            {
                $repeatPoint = (int)($designTimeLayer->mctrl->r * 2.5);

                //ignore repeat control if no motion available to repeat
                if ($repeatPoint < $layerNormalMotionEnd)
                {
                    $runtimeSlideoMotionControl = new WjsslRuntimeSlideoMotionControl();
                    $runtimeSlideoMotionControl->r = $repeatPoint;

                    if (WjsslSliderBuildHelper::HasValue($designTimeLayer->mctrl->e))
                    {
                        $repeatEndPoint = (int)($designTimeLayer->mctrl->e * 2.5);
                        if ($repeatEndPoint > $layerNormalMotionEnd)
                        {
                            $runtimeSlideoMotionControl->e = $repeatEndPoint;
                        }
                    }

                    $runtimeSlideoMotionControlList->append($runtimeSlideoMotionControl);
                    $runtimeLayer->slideoMotionControlIndex = count($runtimeSlideoMotionControlList) - 1;

                    $layerHasRepeat = true;
                }
            }

            //endregion

            //region layer info

            $runtimeLayer->normalMotionBegin = $layerNormalMotionBegin;
            $runtimeLayer->normalMotionEnd = $layerNormalMotionEnd;
            $runtimeLayer->actionMotionBegin = $layerActionMotionBegin;
            $runtimeLayer->actionMotionEnd = $layerActionMotionEnd;
            $runtimeLayer->has3d = $layerHas3d;
            $runtimeLayer->has3dSpace = $layerHas3dSpace;
            $runtimeLayer->hasTransform = $layerHasTransform;
            $runtimeLayer->hasRepeat = $layerHasRepeat;

            //endregion

            //region children

            if (WjsslSliderBuildHelper::HasValue($designTimeLayer->children))
            {
                $runtimeChildLayerList = array();

                foreach ($designTimeLayer->children as $childDesignTimeLayer) {
                    //ignore invisible layer
                    if (($childDesignTimeLayer->ctrls & (int)WjsslEnumLayerControls::Invisible) == (int)WjsslEnumLayerControls::Invisible)
                        continue;

                    $childRuntimeLayer = self::toRuntimeLayer($runtimeSlideoTransitionLists, $runtimeSlideoMotionControlList, $childDesignTimeLayer);


                    if($childRuntimeLayer->isAnimated())
                    {
                        if($childRuntimeLayer->normalMotionEnd > 0)
                        {
                            if($conditionPlayChildLayers)
                            {
                                //add to action animation
                                if ($runtimeLayer->actionMotionBegin == null)
                                {
                                    $runtimeLayer->actionMotionBegin = $childRuntimeLayer->normalMotionBegin;
                                }

                                $runtimeLayer->actionMotionBegin = min($runtimeLayer->actionMotionBegin, $childRuntimeLayer->normalMotionBegin);
                                $runtimeLayer->actionMotionEnd = max($runtimeLayer->actionMotionEnd, $childRuntimeLayer->normalMotionEnd);
                            }
                            else
                            {
                                //add to normal animation
                                if ($runtimeLayer->normalMotionBegin == null)
                                {
                                    $runtimeLayer->normalMotionBegin = $childRuntimeLayer->normalMotionBegin;
                                }

                                $runtimeLayer->normalMotionBegin = min($runtimeLayer->normalMotionBegin, $childRuntimeLayer->normalMotionBegin);
                                $runtimeLayer->normalMotionEnd = max($runtimeLayer->normalMotionEnd, $childRuntimeLayer->normalMotionEnd);
                            }
                        }
                    }

                    $runtimeLayer->has3dChild = $runtimeLayer->has3dChild || $childRuntimeLayer->has3d || $childRuntimeLayer->has3dChild;
                    $runtimeLayer->has3dSpaceChild = $runtimeLayer->has3dSpaceChild || $childRuntimeLayer->has3dSpace || $childRuntimeLayer->has3dSpaceChild;
                    $runtimeLayer->hasTransformChild = $runtimeLayer->hasTransformChild || $childRuntimeLayer->hasTransform || $childRuntimeLayer->hasTransformChild;
                    $runtimeLayer->hasRepeatChild = $runtimeLayer->hasRepeatChild || $childRuntimeLayer->hasRepeat || $childRuntimeLayer->hasRepeatChild;
                    $runtimeLayer->hasActionAnimationChild = $runtimeLayer->hasActionAnimationChild || $childRuntimeLayer->hasActionAnimation() || $childRuntimeLayer->hasActionAnimationChild;

                    array_push($runtimeChildLayerList, $childRuntimeLayer);
                }

                if(count($runtimeChildLayerList) > 0) {
                    $runtimeLayer->hasChild = true;
                    $runtimeLayer->children = $runtimeChildLayerList;
                }
            }

            //endregion

            return $runtimeLayer;
        }
}
