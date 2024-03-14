<?php
/**
 * Masonry Callback
 */

 function migb_callback($attributes){
    $handle = 'migb_'.$attributes['galleryId'];
    $migb_css = '';
    /**
     * Normal CSS
     */

    // zindex
    if(isset($attributes['zIndex'])){
        $migb_css .= '.wp-block-migb-masonry-gallery.'.$handle.'{';
            $migb_css .= 'z-index: '.$attributes['zIndex'].';';
        $migb_css .= '}';
    }

    // image 
    $migb_css .= '.wp-block-migb-masonry-gallery.'.$handle.' .single-gallery-image img{';
        if( isset( $attributes['enablePhotoLinkedBorderRadius'] ) ) {
            $migb_css .= 'border-radius: '.$attributes['photoLinkedBorderRadius'].'px;';
        } else {
            $migb_css .= 'border-radius: '.$attributes['photoTopBorderRadius'].'px '.$attributes['photoRightBorderRadius'].'px '.$attributes['photoBottomBorderRadius'].'px '.$attributes['photoLeftBorderRadius'].'px;';
        }

        if($attributes['photoBorderStyle'] != 'none'){
            if( isset( $attributes['photoBorderColor'] ) ) {
                $migb_css .= 'border-color: '.$attributes['photoBorderColor'].';';
            }
            $migb_css .= 'border-style: '.$attributes['photoBorderStyle'].';';
            if( $attributes['enablePhotoLinkedBorder']){
                $migb_css .= 'border-width: '.$attributes['photoLinkedBorderWidth'].'px;';
            }else {
                $migb_css .= 'border-width: '.$attributes['containerTopBorderWidth'].'px' . ' ' . $attributes['photoRightBorderWidth'].'px' . ' ' . $attributes['photoBottomBorderWidth'].'px' . ' ' . $attributes['photoLeftBorderWidth'].'px;';
            }
        }

    $migb_css .= '}';

    // image caption 
    $migb_css .= '.wp-block-migb-masonry-gallery.'.$handle.' .single-gallery-image figcaption{';
        if( $attributes['captionColor']) {
            $migb_css .= 'color: '.$attributes['captionColor'].';';
        }
        if( $attributes['enablePhotoLinkedBorderRadius']) {
            $migb_css .= 'border-radius: '.$attributes['photoLinkedBorderRadius'].'px;';
        } else {
            $migb_css .= 'border-radius: '.$attributes['photoTopBorderRadius'].'px '.$attributes['photoRightBorderRadius'].'px '.$attributes['photoBottomBorderRadius'].'px '.$attributes['photoLeftBorderRadius'].'px;';
        }
    $migb_css .= '}';

    /**
     * Desktop View
     */

    $migb_css .= '@media only screen and (min-width: 1025px) {';
        // columns count
        $migb_css .= '.wp-block-migb-masonry-gallery.'.$handle.'{';
            $migb_css .= 'column-count: '.$attributes['deskCol'].';';
            $migb_css .= 'gap: '.$attributes['deskGap'].'px;';
        $migb_css .= '}';

        // single item
        $migb_css .= '.wp-block-migb-masonry-gallery.'.$handle.' .single-gallery-image{';
            $migb_css .= 'margin-bottom: '.$attributes['deskGap'].'px;';
        $migb_css .= '}';

        // image caption 
        $migb_css .= '.wp-block-migb-masonry-gallery.'.$handle.' .single-gallery-image figcaption{';
            $migb_css .= 'font-size: '.$attributes['captionDeskFontSize'].'px;';
        $migb_css .= '}';

    $migb_css .= '}';

    /**
     * Tablet View
     */
    
    $migb_css .= '@media only screen and (min-width: 768px) and (max-width: 1024px) {';
        // columns count
        $migb_css .= '.wp-block-migb-masonry-gallery.'.$handle.'{';
            $migb_css .= 'column-count: '.$attributes['tabCol'].';';
            $migb_css .= 'gap: '.$attributes['tabGap'].'px;';
        $migb_css .= '}';

        // single item
        $migb_css .= '.wp-block-migb-masonry-gallery.'.$handle.' .single-gallery-image{';
            $migb_css .= 'margin-bottom: '.$attributes['tabGap'].'px;';
        $migb_css .= '}';

        // image caption 
        $migb_css .= '.wp-block-migb-masonry-gallery.'.$handle.' .single-gallery-image figcaption{';
            $migb_css .= 'font-size: '.$attributes['captionTabFontSize'].'px;';
        $migb_css .= '}';

    $migb_css .= '}';

    /** 
     * Mobile View
     */

    $migb_css .= '@media only screen and (max-width: 767px) {';
        // columns count
        $migb_css .= '.wp-block-migb-masonry-gallery.'.$handle.'{';
            $migb_css .= 'column-count: '.$attributes['phoneCol'].';';
            $migb_css .= 'gap: '.$attributes['phoneGap'].'px;';
        $migb_css .= '}';

        // single item
        $migb_css .= '.wp-block-migb-masonry-gallery.'.$handle.' .single-gallery-image{';
            $migb_css .= 'margin-bottom: '.$attributes['phoneGap'].'px;';
        $migb_css .= '}';

        // image caption 
        $migb_css .= '.wp-block-migb-masonry-gallery.'.$handle.' .single-gallery-image figcaption{';
            $migb_css .= 'font-size: '.$attributes['captionPhoneFontSize'].'px;';
        $migb_css .= '}';

    $migb_css .= '}';

    return $migb_css;
 }