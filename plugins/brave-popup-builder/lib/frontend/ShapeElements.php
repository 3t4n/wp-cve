<?php

function renderShape ($id, $shape, $shapeData, $fillImage=null, $border=null, $shadow=null ) {
   $shapeData = $shapeData ? $shapeData : '';
   $width = isset($shapeData->width) ? $shapeData->width :'100px';
   $height = isset($shapeData->height) ? $shapeData->height :'100px';
   $fill = $shapeData->fillColor; $fillImageUrl = '';
   $shadowVal ='';
   $bgImage = '';
   $borderSize = '';
   $borderColor = '';
   if($fillImage && isset($fillImage->image)){
      $fillImageUrl = 'url(#'.$id.'__shape__image)';
      $filImageImg = $fillImage->image;
      $filImageSize = isset($fillImage->size) ? $fillImage->size.'%':'100%';
      $filImagePosX = isset($fillImage->posX) ? $fillImage->posX.'%' : 0;
      $filImagePosY = isset($fillImage->posY) ? $fillImage->posY.'%' : 0;

      $bgImage = '<defs><pattern id="'.$id.'__shape__image" patternUnits="userSpaceOnUse" width="'.$width.'" height="'.$height.'"><image xlink:href="'.$filImageImg.'" x="'.$filImagePosX.'" y="'.$filImagePosY.'" width="'.$filImageSize.'" height="'.$filImageSize.'" /></pattern></defs>';
   }

   if($border && isset($border->size)){
      $borderSize = isset($border->size) ? (float)$border->size :0;
      $borderColorRGB = isset($border->color->rgb) ? $border->color->rgb :'0,0,0';
      $borderColorOpacity = isset($border->color->opacity) ? $border->color->opacity :'1';
      $borderColor = 'rgba('.$borderColorRGB.', '.$borderColorOpacity.')';
   }
   if($shadow){
      $shadowSize = isset($shadow->size) ? $shadow->size : 20; 
      $shadowColorRGB = isset($shadow->color->rgb) ? $shadow->color->rgb :'0,0,0';
      $shadowColorOpacity = isset($shadow->color->opacity) ? $shadow->color->opacity :'0.12';
      $shadowColor = 'rgba('.$shadowColorRGB.', '.$shadowColorOpacity.')';
      $shadowVal = 'filter: drop-shadow(0px 0px '.$shadowSize.'px '.$shadowColor.');';
   }

   $shapeStyle = 'style="width:'.$width.';height:'.$height.'; '.$shadowVal.'"';
   $svgMarkup = 'version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" preserveAspectRatio="none" x="0px" y="0px"';

   switch($shape) {
       case 'square':
            $shapeContent = isset($border) && $borderSize && $borderColor ? '<rect stroke="'.$borderColor.'" stroke-width="'.$borderSize.'" vector-effect="non-scaling-stroke" fill="'.$fillImageUrl.'" x="2" y="2" width="36" height="36"/>'  :  '<rect width="40px" height="40px" fill="'.$fillImageUrl.'" />';
            return  renderSVG($id, $fill, $shapeStyle, $bgImage, $shapeContent);
       case 'rounded':
            $shapeContent = isset($border) && $borderSize && $borderColor ? '<rect stroke="'.$borderColor.'" stroke-width="'.$borderSize.'" vector-effect="non-scaling-stroke" fill="'.$fillImageUrl.'" x="2" y="2" rx="2" ry="2" width="36" height="36"/>'  :  '<rect width="40px" height="40px" rx="2" ry="2" fill="'.$fillImageUrl.'" />';
            return  renderSVG($id, $fill, $shapeStyle, $bgImage, $shapeContent);
      case 'square_dashed':
            $shapeContent = '<rect stroke="'.$fill.'" stroke-width="4" stroke-dasharray="10, 10" vector-effect="non-scaling-stroke" x="2" y="2" width="36" height="36"/>';
            return  renderSVG($id, 'transparent', $shapeStyle, $bgImage, $shapeContent);
       case 'circle':
            $shapeContent = isset($border) && $borderSize && $borderColor ? '<circle stroke="'.$borderColor.'" stroke-width="'.$borderSize.'" fill="'.$fillImageUrl.'" vector-effect="non-scaling-stroke" cy="20" cx="20" r="18"/>' : '<circle fill="'.$fillImageUrl.'" cx="20" cy="20" r="20" />';
            return  renderSVG($id, $fill, $shapeStyle, $bgImage, $shapeContent);
       case 'diamond':
            $shapeContent = isset($border) && $borderSize && $borderColor ? '<rect stroke="'.$borderColor.'" stroke-width="'.$borderSize.'" fill="'.$fillImageUrl.'" x="8" y="8" vector-effect="non-scaling-stroke" transform="matrix(-0.7071 0.7071 -0.7071 -0.7071 48.2845 19.999)" width="23.858" height="23.858"/>' : '<rect fill="'.$fillImageUrl.'" x="6.288" y="6.289" transform="matrix(0.7071 -0.7071 0.7071 0.7071 -8.285 20.0001)" width="27.424" height="27.424"/>';
            return  renderSVG($id, $fill, $shapeStyle, $bgImage, $shapeContent);
       case 'hexagon':
            $shapeContent = isset($border) && $borderSize && $borderColor ? '<polygon stroke="'.$borderColor.'" stroke-width="'.$borderSize.'" fill="'.$fillImageUrl.'" vector-effect="non-scaling-stroke" points="11.25,34.354 2.5,20.354 11.25,6.354 28.749,6.354 37.5,20.354 28.749,34.354 "/>' : '<polygon fill="'.$fillImageUrl.'" points="10.023,37.28 0.046,20 10.023,2.72 29.977,2.72 39.954,20 29.977,37.28" />';
            return  renderSVG($id, $fill, $shapeStyle, $bgImage, $shapeContent);

       case 'hexagon2':
            $shapeContent = isset($border) && $borderSize && $borderColor ? '<polygon stroke="'.$borderColor.'" stroke-width="'.$borderSize.'" fill="'.$fillImageUrl.'" vector-effect="non-scaling-stroke" points="35,28.75 20.001,37.5 5,28.75 5,11.251 20.001,2.5 35,11.251 "/>' : '<polygon fill="'.$fillImageUrl.'" points="37,29.501 20,39 3,29.501 3,10.501 20,1 37,10.501 " />';
            return  renderSVG($id, $fill, $shapeStyle, $bgImage, $shapeContent);
       case 'triangle':
            $shapeContent = isset($border) && $borderSize && $borderColor ? '<polygon stroke="'.$borderColor.'" stroke-width="'.$borderSize.'" fill="'.$fillImageUrl.'" vector-effect="non-scaling-stroke" points="3.557,33.647 20.001,5.166 36.443,33.647 "/>' : '<polygon fill="'.$fillImageUrl.'" points="0,36.961 20,2.32 40,36.961" />';
            return  renderSVG($id, $fill, $shapeStyle, $bgImage, $shapeContent);
       case 'parallelogram_right':
            $shapeContent = isset($border) && $borderSize && $borderColor ? '<polygon stroke="'.$borderColor.'" stroke-width="'.$borderSize.'" fill="'.$fillImageUrl.'" vector-effect="non-scaling-stroke" points="32.346,38.625 1.023,38.625 7.617,1.375 38.938,1.375 " />' : '<polygon fill="'.$fillImageUrl.'" points="33.063,40 0.105,40 7.044,0 40,0 " />';
            return  renderSVG($id, $fill, $shapeStyle, $bgImage, $shapeContent);
       case 'parallelogram_left':
            $shapeContent = isset($border) && $borderSize && $borderColor ? '<polygon stroke="'.$borderColor.'" stroke-width="'.$borderSize.'" fill="'.$fillImageUrl.'" vector-effect="non-scaling-stroke" points="7.615,38.625 38.938,38.625 32.344,1.375 1.023,1.375 " />' : '<polygon fill="'.$fillImageUrl.'" points="7.042,40 40,40 33.062,0 0.105,0 " />';
            return renderSVG($id, $fill, $shapeStyle, $bgImage, $shapeContent);
       case 'line':
            return  '<svg id="'.$id.'__shapeSvg"  fill="'.$fill.'" vector-effect="non-scaling-stroke" '.$shapeStyle.' '.$svgMarkup.' width="40px" height="3px" viewBox="0 0 40 2.75" enable-background="new 0 0 40 2.75">
                     <line fill="none" stroke="'.$fill.'" stroke-width="0.10" stroke-miterlimit="10" x1="0" y1="1.377" x2="40" y2="1.377"></line>
                </svg>';
      case 'line2':
            return  '<svg id="'.$id.'__shapeSvg"  fill="'.$fill.'" vector-effect="non-scaling-stroke" '.$shapeStyle.' '.$svgMarkup.' height="40px" width="3px" viewBox="0 0 2.75 40" enable-background="new 0 0 2.75 40">
                     <line fill="none" stroke="'.$fill.'" stroke-width="0.10" stroke-miterlimit="10" x1="1.5" y1="40" x2="1.5" y2="0"></line>
               </svg>';
       case 'dashedline':
            return  '<svg id="'.$id.'__shapeSvg"  fill="'.$fill.'" vector-effect="non-scaling-stroke" '.$shapeStyle.' '.$svgMarkup.' width="40px" height="3px" viewBox="0 0 40 2.75" enable-background="new 0 0 40 2.75">
                     <line fill="none" stroke-dasharray="1.5,1.5" stroke="'.$fill.'" stroke-width="0.10" stroke-miterlimit="10" x1="0" y1="1.377" x2="40" y2="1.377"></line>
                </svg>';
       case 'star':
            $shapeContent = isset($border) && $borderSize && $borderColor ? '<polygon stroke="'.$borderColor.'" stroke-width="'.$borderSize.'" fill="'.$fillImageUrl.'" vector-effect="non-scaling-stroke" points="12.277,24.119 4.429,16.486 15.262,14.899 20.098,5.077 24.953,14.89 35.788,16.452 27.957,24.104 29.819,34.892 20.123,29.809 10.438,34.912 "/>' : '<polygon fill="'.$fillImageUrl.'" points="10.194,25.164 0.369,15.608 13.932,13.621 19.986,1.323 26.065,13.609 39.631,15.565 29.826,25.145 32.158,38.652 20.018,32.287 7.892,38.678 "/>';
            return renderSVG($id, $fill, $shapeStyle, $bgImage, $shapeContent);                
       case 'corner':
             $shapeContent = isset($border) && $borderSize && $borderColor ? '<polygon stroke="'.$borderColor.'" stroke-width="'.$borderSize.'" fill="'.$fillImageUrl.'" points="2,34.849 2,1.896 35.221,1.896 " />' : '<polygon fill="'.$fillImageUrl.'" points="39.998,0.001 -0.002,40.001 -0.002,0" />';
            return  renderSVG($id, $fill, $shapeStyle, $bgImage, $shapeContent);
       case 'heart':
            $shapeContent = isset($border) && $borderSize && $borderColor ? '
                           <g>
                                    <path stroke="'.$borderColor.'" stroke-width="'.$borderSize.'" fill="'.$fillImageUrl.'" vector-effect="non-scaling-stroke" d="M37.719,15.622c0-4.999-4.159-9.065-9.271-9.065c-3.752,0-6.988,2.189-8.448,5.329c-1.457-3.14-4.694-5.329-8.445-5.329
                                       c-5.113,0-9.272,4.067-9.272,9.065c0,1.055,0.185,2.066,0.525,3.009c0.388,1.072,0.977,2.053,1.722,2.897
                                       c0.317,0.363,0.662,0.698,1.035,1.007l14.143,13.735c0.087,0.083,0.2,0.127,0.315,0.127c0.115,0,0.229-0.044,0.317-0.127
                                       l14.67-14.25l-0.002-0.002c0.949-0.931,1.695-2.061,2.161-3.322C37.524,17.736,37.719,16.701,37.719,15.622z"/>
                                 </g>'
                           :
                           '<g>
                                 <path fill="'.$fillImageUrl.'" d="M39,14.756c0-5.36-4.461-9.722-9.942-9.722c-4.023,0-7.494,2.348-9.059,5.714c-1.563-3.367-5.034-5.714-9.055-5.714
                                    C5.459,5.035,1,9.396,1,14.756c0,1.131,0.198,2.215,0.563,3.227c0.417,1.15,1.046,2.202,1.846,3.107
                                    c0.341,0.389,0.71,0.749,1.11,1.08l15.167,14.728c0.093,0.091,0.215,0.138,0.338,0.138c0.124,0,0.246-0.047,0.34-0.138
                                    l15.73-15.279l-0.001-0.002c1.019-0.999,1.818-2.211,2.318-3.563C38.791,17.023,39,15.913,39,14.756z"/>
                           </g>';
            return  renderSVG($id, $fill, $shapeStyle, $bgImage, $shapeContent);
      case 'badge1':
            $badge1Val = "20,5.076 22.615,3.49 24.611,5.806 27.588,5.106 28.773,7.926 31.82,8.18 32.074,11.228 34.895,12.412 34.195,15.389 36.51,17.386 34.924,20.001 36.51,22.615 34.195,24.612 34.895,27.59 32.074,28.773 31.82,31.82 28.773,32.075 27.588,34.895 24.611,34.195 22.615,36.511 20,34.925 17.385,36.511 15.388,34.195 12.411,34.895 11.227,32.075 8.18,31.82 7.925,28.773 5.106,27.59 5.806,24.612 3.49,22.615 5.075,20.001 3.49,17.386 5.806,15.389 5.106,12.412 7.925,11.228 8.18,8.18 11.227,7.926 12.411,5.106 15.388,5.806 17.385,3.49";
            $shapeContent = isset($border) && $borderSize && $borderColor ? '<polygon stroke="'.$borderColor.'" stroke-width="'.$borderSize.'" fill="'.$fillImageUrl.'" vector-effect="non-scaling-stroke" points="'.$badge1Val.'" />' : '<polygon fill="'.$fillImageUrl.'" points="'.$badge1Val.'" />';
            return  renderSVG($id, $fill, $shapeStyle, $bgImage, $shapeContent);
      case 'badge2':
            $badge2Val = "M21.618,4.639L21.618,4.639c1.622-0.831,3.61-0.298,4.601,1.233l0,0 c0.619,0.959,1.662,1.562,2.801,1.618l0,0c1.822,0.091,3.279,1.547,3.369,3.367l0,0c0.059,1.141,0.66,2.184,1.619,2.804l0,0 c1.531,0.989,2.063,2.978,1.232,4.6l0,0c-0.521,1.017-0.521,2.221,0,3.238l0,0c0.83,1.621,0.299,3.609-1.232,4.598l0,0 c-0.959,0.621-1.561,1.664-1.619,2.805l0,0c-0.09,1.82-1.547,3.275-3.369,3.367l0,0c-1.139,0.059-2.182,0.66-2.801,1.619l0,0 c-0.99,1.531-2.979,2.063-4.601,1.23l0,0c-1.016-0.52-2.221-0.52-3.237,0l0,0c-1.623,0.832-3.611,0.301-4.6-1.23l0,0 c-0.62-0.959-1.663-1.561-2.804-1.619l0,0c-1.82-0.092-3.276-1.547-3.367-3.367l0,0c-0.057-1.141-0.659-2.184-1.619-2.805l0,0 c-1.531-0.988-2.063-2.977-1.233-4.598l0,0c0.521-1.018,0.521-2.222,0-3.238l0,0c-0.831-1.623-0.298-3.611,1.233-4.6l0,0 c0.959-0.62,1.562-1.663,1.619-2.804l0,0c0.091-1.82,1.547-3.276,3.367-3.367l0,0c1.141-0.057,2.184-0.659,2.804-1.618l0,0 c0.989-1.531,2.978-2.064,4.6-1.233l0,0C19.397,5.16,20.602,5.16,21.618,4.639z";
            $shapeContent = isset($border) && $borderSize && $borderColor ? '<path stroke="'.$borderColor.'" stroke-width="'.$borderSize.'" fill="'.$fillImageUrl.'" vector-effect="non-scaling-stroke" d="'.$badge2Val.'" />' : '<path fill="'.$fillImageUrl.'" d="'.$badge2Val.'" />';
            return  renderSVG($id, $fill, $shapeStyle, $bgImage, $shapeContent);
       default:
           return '<div></div>';
   }
}

function renderSVG($id, $fill, $shapeStyle, $bgImage, $shapeContent){
   $svgMarkup = 'version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" preserveAspectRatio="none" x="0px" y="0px"';
   return '<svg id="'.$id.'__shapeSvg" fill="'.$fill.'" '.$shapeStyle.' '.$svgMarkup.' width="40px" height="40px" viewBox="0 0 40 40" enable-background="new 0 0 40 40">'.$bgImage . $shapeContent.'</svg>';
}