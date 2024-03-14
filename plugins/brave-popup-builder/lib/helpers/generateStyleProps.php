<?php 

function bravepop_generate_style_props($data, $type, $defaultOne='', $defaultTwo=''){
   if(!$type){ return ''; }
   if($type === 'raw'){
      $defaultRGB = $defaultOne ? $defaultOne : '0,0,0';  $defaultOpacity = isset($defaultTwo) ? $defaultTwo : '1';
      $fontColorRGB = $data && isset($data->rgb) ? $data->rgb : $defaultRGB;
      $fontColorOpacity = $data && isset($data->opacity) ? $data->opacity : $defaultOpacity;
      return'rgba('.$fontColorRGB.', '.$fontColorOpacity.')';
   }
   if($type === 'color'){
      $defaultRGB = $defaultOne ? $defaultOne : '0,0,0';  $defaultOpacity = isset($defaultTwo) ? $defaultTwo : '1';
      $fontColorRGB = $data && isset($data->rgb) ? $data->rgb : $defaultRGB;
      $fontColorOpacity = $data && isset($data->opacity) ? $data->opacity : $defaultOpacity;
      return'color: rgba('.$fontColorRGB.', '.$fontColorOpacity.');';
   }
   if($type === 'background-color'){
      $defaultRGB = $defaultOne ? $defaultOne : '0,0,0';  $defaultOpacity = isset($defaultTwo) ? $defaultTwo : '1';
      $fontColorRGB = $data && isset($data->rgb) ? $data->rgb : $defaultRGB;
      $fontColorOpacity = $data && isset($data->opacity) ? $data->opacity : $defaultOpacity;
      return 'background-color: rgba('.$fontColorRGB.', '.$fontColorOpacity.');';
   }
   if($type === 'fill'){
      $defaultRGB = $defaultOne ? $defaultOne : '0,0,0';  $defaultOpacity = isset($defaultTwo) ? $defaultTwo : '1';
      $fontColorRGB = $data && isset($data->rgb) ? $data->rgb : $defaultRGB;
      $fontColorOpacity = $data && isset($data->opacity) ? $data->opacity : $defaultOpacity;
      return 'fill: rgba('.$fontColorRGB.', '.$fontColorOpacity.');';
   }
   if($type === 'border-color'){
      $defaultRGB = $defaultOne ? $defaultOne : '0,0,0';  $defaultOpacity = isset($defaultTwo) ? $defaultTwo : '1';
      $fontColorRGB = $data && isset($data->rgb) ? $data->rgb : $defaultRGB;
      $fontColorOpacity = $data && isset($data->opacity) ? $data->opacity : $defaultOpacity;
      return 'border-color: rgba('.$fontColorRGB.', '.$fontColorOpacity.');';
   }
   if($type === 'font-size'){
      $defaultFontSize = $defaultOne ? 'font-size: '.$defaultOne.'px;' : ''; 
      return $data ? 'font-size: '.$data.'px;' : $defaultFontSize;
   }

   if($type === 'font-family'){
      $defaultFontFamily = 'font-family: '.$defaultOne ? $defaultOne : ''; 
      return $data ?  'font-family: '.$data.';' : $defaultFontFamily;
   }

   if($type === 'text-align'){
      $defaultTextAlign = $defaultOne ? $defaultOne : '';
      return $data ?  'text-align: '.$data.';' : $defaultTextAlign;
   }

}