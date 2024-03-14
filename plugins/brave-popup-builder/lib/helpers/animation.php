<?php

function bravepop_prepare_element_animation($element, $popup=false){
   $animTypes = array('load', 'exit', 'continious', 'custom_1', 'custom_2', 'custom_3');
   $theElement = new stdClass();
   $theElement->animation = new stdClass();
   foreach ($animTypes as $key => $animType) {
      $theElement->animation->$animType = new stdClass();
      if(isset($element->animation->$animType->preset) && $element->animation->$animType->preset === 'none'){ return; }
      if(isset($element->animation->$animType->props) && count((array)$element->animation->$animType->props)){
         $theElement->id = isset($element->id) ? $element->id : ''; if($popup){  $theElement->id = 'popup'; }
         $theElement->top = isset($element->top) ? $element->top : '';
         $theElement->left = isset($element->left) ? $element->left : '';
         $theElement->animation->$animType->props = isset($element->animation->$animType->props) ? $element->animation->$animType->props : new stdClass() ;
         $theElement->animation->$animType->duration = isset($element->animation->$animType->duration) ? $element->animation->$animType->duration : 500;
         $theElement->animation->$animType->delay = isset($element->animation->$animType->delay) ?  $element->animation->$animType->delay: 0;
         $theElement->animation->$animType->easing = isset($element->animation->$animType->easing) ?  $element->animation->$animType->easing: '';
         $theElement->animation->$animType->preset = isset($element->animation->$animType->preset) ?  $element->animation->$animType->preset: '';
         // $theElement->animation->$animType->repeat = isset($element->animation->$animType->repeat) ?  $element->animation->$animType->repeat: 0;
         // $theElement->animation->$animType->repeatDelay = isset($element->animation->$animType->repeatDelay) ? $element->animation->$animType->repeatDelay : 0;
      }
      if(isset($element->animation->$animType) && $animType === 'continious' && isset($element->animation->$animType->enable) && isset($element->animation->$animType->preset) && $element->animation->$animType->enable === true && $element->animation->$animType->preset !== 'none' ){
         $theElement->id = isset($element->id) ? $element->id : ''; if($popup){  $theElement->id = 'popup'; }
         $theElement->animation->$animType->duration = isset($element->animation->$animType->duration) ? $element->animation->$animType->duration : 0;
         $theElement->animation->$animType->delay = isset($element->animation->$animType->delay) ?  $element->animation->$animType->delay: 0;
         $theElement->animation->$animType->preset = isset($element->animation->$animType->preset) ?  $element->animation->$animType->preset: '';
      }
   }
   return $theElement;
}


function bravepop_prepare_animation($popupData) {
   $animationData = array();
   $hasAnimation = false;
   $hasContAnim = false;
   $advancedAnimation = false;
   $animTypes = array('load', 'exit', 'continious', 'custom_1', 'custom_2', 'custom_3');
      if(isset($popupData) && isset($popupData->steps)){
         
         foreach ($popupData->steps as $stepIndex => $step) {
            $animationData[$stepIndex] = new stdClass();
            $animationData[$stepIndex]->desktop = new stdClass(); 
            $animationData[$stepIndex]->mobile = new stdClass(); 
            $animationData[$stepIndex]->desktop->elements = array();
            $animationData[$stepIndex]->mobile->elements = array();
            $animationData[$stepIndex]->desktop->totalDuration = 0;
            $animationData[$stepIndex]->mobile->totalDuration = 0;
            $desktopTotalDuration = array('load'=> [], 'exit'=> [], 'custom_1'=> [], 'custom_1'=> [], 'custom_1'=> []);
            $mobileTotalDuration = array('load'=> [], 'exit'=> [], 'custom_1'=> [], 'custom_1'=> [], 'custom_1'=> []);

   
            if($advancedAnimation === false && !empty($step->desktop->advancedAnimation) && $step->desktop->advancedAnimation === true){ $advancedAnimation = true; }
            if($advancedAnimation === false && !empty($step->desktop->advancedAnimation) && $step->mobile->advancedAnimation === true){ $advancedAnimation = true; }

            //Check if the desktop Popup has animation
            if(isset($step->desktop->animation)){
               $preparedDesktopAnims =bravepop_prepare_element_animation($step->desktop, true);
               if(isset($step->desktop->animation->exit->props) && count((array)$step->desktop->animation->exit->props) > 0){
                  $hasAnimation = true;
               }
               if(isset($step->desktop->animation->custom_1->props) && count((array)$step->desktop->animation->custom_1->props) > 0){   $hasAnimation = true;  }
               if(isset($step->desktop->animation->custom_2->props) && count((array)$step->desktop->animation->custom_2->props) > 0){   $hasAnimation = true;  }
               if(isset($step->desktop->animation->custom_3->props) && count((array)$step->desktop->animation->custom_3->props) > 0){   $hasAnimation = true;  }
               

               if(count((array)$preparedDesktopAnims) > 0 ){    $animationData[$stepIndex]->desktop->elements[] =  $preparedDesktopAnims; }

               foreach ($animTypes as $key => $animType) {
                  if(isset($step->desktop->animation->$animType->props) && count((array)$step->desktop->animation->$animType->props) > 0){
                     $hasAnimation = true;
                     $duration = isset($step->desktop->animation->$animType->duration) ? $step->desktop->animation->$animType->duration : 500;
                     $delay = isset($step->desktop->animation->$animType->delay) ? $step->desktop->animation->$animType->delay : 500;
                     $desktopTotalDuration[$animType][] = $duration + $delay;
                  }
               }

               if(isset($step->desktop->animation->continious->enable) && $step->desktop->animation->continious->enable === true){  $hasContAnim = true;   }
            }
            //Check if the mobile Popup has animation
            if(isset($step->mobile->animation)){
               $preparedMobileAnims =bravepop_prepare_element_animation($step->mobile, true);
               if(isset($step->mobile->animation->exit->props) && count((array)$step->mobile->animation->exit->props) > 0){
                  $hasAnimation = true;
               }
               if(isset($step->mobile->animation->custom_1->props) && count((array)$step->mobile->animation->custom_1->props) > 0){   $hasAnimation = true;  }
               if(isset($step->mobile->animation->custom_2->props) && count((array)$step->mobile->animation->custom_2->props) > 0){   $hasAnimation = true;  }
               if(isset($step->mobile->animation->custom_3->props) && count((array)$step->mobile->animation->custom_3->props) > 0){   $hasAnimation = true;  }

               if(count((array)$preparedMobileAnims) > 0 ){    $animationData[$stepIndex]->mobile->elements[] =  $preparedMobileAnims; }

               foreach ($animTypes as $key => $animType) {
                  if(isset($step->mobile->animation->$animType->props) && count((array)$step->mobile->animation->$animType->props) > 0){
                     $hasAnimation = true;
                     $duration = isset($step->mobile->animation->$animType->duration) ? $step->mobile->animation->$animType->duration : 500;
                     $delay = isset($step->mobile->animation->$animType->delay) ? $step->mobile->animation->$animType->delay : 500;
                     $mobileTotalDuration[$animType][]= $duration + $delay;
                  }
               }

               if(isset($step->mobile->animation->continious->enable) && $step->mobile->animation->continious->enable === true){  $hasContAnim = true;   }
            }
            

            //ELEMENTS-----------------------------

            //Check if Any of the desktop Elements has animation
            if(isset($step->desktop) && isset($step->desktop->content)){
               foreach ($step->desktop->content as $index => $element) {
                  if(isset($element->animation)){
                     $preparedDesktopElmAnims =bravepop_prepare_element_animation($element, false);
                     if(count((array)$preparedDesktopElmAnims) > 0 ){    $animationData[$stepIndex]->desktop->elements[] = $preparedDesktopElmAnims; }

                     foreach ($animTypes as $key => $animType) {
                        if(isset($element->animation->$animType) && isset($element->animation->$animType->props) && count((array)$element->animation->$animType->props) > 0){
                           $hasAnimation = true;
                           $duration = isset($element->animation->$animType->duration) ? $element->animation->$animType->duration : 500;
                           $delay = isset($element->animation->$animType->delay) ? $element->animation->$animType->delay : 500;
                           $desktopTotalDuration[$animType][] = $duration + $delay;
                        }
                     }

                     if(isset($element->animation->continious->enable) && $element->animation->continious->enable === true){  $hasContAnim = true;   }
                  }
               }
            }
            //Check if Any of the Mobile Elements has animation
            if(isset($step->mobile) && isset($step->mobile->content)){
               foreach ($step->mobile->content as $index => $element) {
                  if(isset($element->animation)){
                     $preparedMobileElmAnims = bravepop_prepare_element_animation($element, false);
                     if(count((array)$preparedMobileElmAnims) > 0 ){    $animationData[$stepIndex]->mobile->elements[] = $preparedMobileElmAnims; }

                     foreach ($animTypes as $key => $animType) {
                        if(isset($element->animation->$animType) && isset($element->animation->$animType->props) && count((array)$element->animation->$animType->props) > 0){
                           $hasAnimation = true;
                           $duration = isset($element->animation->$animType->duration) ? $element->animation->$animType->duration : 500;
                           $delay = isset($element->animation->$animType->delay) ? $element->animation->$animType->delay : 500;
                           $mobileTotalDuration[$animType][] = $duration + $delay;
                        }
                     }

                     if(isset($element->animation->continious->enable) && $element->animation->continious->enable === true){  $hasContAnim = true;   }
                  }
               }
            }
            foreach ($animTypes as $key => $animType) {
               if(isset($desktopTotalDuration[$animType]) && is_array($desktopTotalDuration[$animType]) && count($desktopTotalDuration[$animType]) > 0){ 
                  $animationData[$stepIndex]->desktop->totalDuration = array($animType=> max($desktopTotalDuration[$animType]) ); 
               }
               if(isset($mobileTotalDuration[$animType]) && is_array($mobileTotalDuration[$animType]) && count($mobileTotalDuration[$animType]) > 0){ 
                  $animationData[$stepIndex]->mobile->totalDuration = array($animType=> max($mobileTotalDuration[$animType])); 
               }
            }


         }
         
      }

      return array('animationData' => $animationData, 'hasAnimation' => $hasAnimation, 'hasContAnim' => $hasContAnim, 'advancedAnimation' => $advancedAnimation);
   
}