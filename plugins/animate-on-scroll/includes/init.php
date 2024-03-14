<?php

if (!defined('ABSPATH')) exit;

include_once(AOSWP_VENDOR_DIR . 'ori-dom-parser.php');

/**
 * Initialize Animate on Scroll plugin functionality.
 */

class AOSWP_init {

    public function aoswp_rewrite_buffer($html) {
        
        try {
            // Process only GET requests
            if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
                return $html;
            }

            // check empty
            if (!isset($html) || trim($html) === '') {
                return $html;
            }

            // return if content is XML
            if (strcasecmp(substr($html, 0, 5), '<?xml') === 0) {
                return $html;
            }

            // Check if the code is HTML, otherwise return
            if (trim($html)[0] !== "<") {
                return $html;
            }

            // Parse HTML
            $AosWpHtml = str_get_html($html);

            // Not HTML, return original
            if (!is_object($AosWpHtml)) {
                return $html;
            }

            // Start processing the AOS Attributes
	        
	        /*
	         * fade animation 
	         *
	         */
	 		
	 		// basic fade animation
	 		
	 		foreach ($AosWpHtml->find(".aos-fade") as $AosFadeHtml) {
				
				$AosFadeHtml->setAttribute("data-aos", "fade");
				
			} // end basic fade animation foreach
			
			// fade-up animation
	 		
	 		foreach ($AosWpHtml->find(".aos-fade-up") as $AosFadeUpHtml) {
				
				$AosFadeUpHtml->setAttribute("data-aos", "fade-up");
				
			} // end fade-up animation foreach
			
			// fade-down animation
	 		
	 		foreach ($AosWpHtml->find(".aos-fade-down") as $AosFadeDownHtml) {
				
				$AosFadeDownHtml->setAttribute("data-aos", "fade-down");
				
			} // end fade-down animation foreach
			
			// fade-right animation
	 		
	 		foreach ($AosWpHtml->find(".aos-fade-right") as $AosFadeRightHtml) {
				
				$AosFadeRightHtml->setAttribute("data-aos", "fade-right");
				
			} // end fade-right animation foreach
			
			// fade-left animation /* since 1.0.3 */
	 		
	 		foreach ($AosWpHtml->find(".aos-fade-left") as $AosFadeLeftHtml) {
				
				$AosFadeLeftHtml->setAttribute("data-aos", "fade-left");
				
			} // end fade-left animation foreach
			
			// fade-up-right animation
	 		
	 		foreach ($AosWpHtml->find(".aos-fade-up-right") as $AosFadeUpRightHtml) {
				
				$AosFadeUpRightHtml->setAttribute("data-aos", "fade-up-right");
				
			} // end fade-up-right animation foreach
			
			// fade-up-left animation
	 		
	 		foreach ($AosWpHtml->find(".aos-fade-up-left") as $AosFadeUpLeftHtml) {
				
				$AosFadeUpLeftHtml->setAttribute("data-aos", "fade-up-left");
				
			} // end fade-up-left animation foreach
			
			// fade-down-right animation
	 		
	 		foreach ($AosWpHtml->find(".aos-fade-down-right") as $AosFadeDownRightHtml) {
				
				$AosFadeDownRightHtml->setAttribute("data-aos", "fade-down-right");
				
			} // end fade-down-right animation foreach
			
			// fade-down-left animation
	 		
	 		foreach ($AosWpHtml->find(".aos-fade-down-left") as $AosFadeDownLeftHtml) {
				
				$AosFadeDownLeftHtml->setAttribute("data-aos", "fade-down-left");
				
			} // end fade-down-left animation foreach
			
			
			/*
	         * flip animation 
	         *
	         */
	         
	        // flip-up animation
	 		
	 		foreach ($AosWpHtml->find(".aos-flip-up") as $AosFlipUpHtml) {
				
				$AosFlipUpHtml->setAttribute("data-aos", "flip-up");
				
			} // end flip-up animation foreach
			
			// flip-down animation
	 		
	 		foreach ($AosWpHtml->find(".aos-flip-down") as $AosFlipDownHtml) {
				
				$AosFlipDownHtml->setAttribute("data-aos", "flip-down");
				
			} // end flip-down animation foreach
			
			// flip-left animation
	 		
	 		foreach ($AosWpHtml->find(".aos-flip-left") as $AosFlipLeftHtml) {
				
				$AosFlipLeftHtml->setAttribute("data-aos", "flip-left");
				
			} // end flip-left animation foreach
			
			// flip-right animation
	 		
	 		foreach ($AosWpHtml->find(".aos-flip-right") as $AosFlipRightHtml) {
				
				$AosFlipRightHtml->setAttribute("data-aos", "flip-right");
				
			} // end flip-right animation foreach
			
			
			/*
	         * slide animation 
	         *
	         */
	         
	        // slide-up animation
	 		
	 		foreach ($AosWpHtml->find(".aos-slide-up") as $AosSlideUpHtml) {
				
				$AosSlideUpHtml->setAttribute("data-aos", "slide-up");
				
			} // end slide-up animation foreach
			
			// slide-down animation
	 		
	 		foreach ($AosWpHtml->find(".aos-slide-down") as $AosSlideDownHtml) {
				
				$AosSlideDownHtml->setAttribute("data-aos", "slide-down");
				
			} // end slide-down animation foreach
			
			// slide-left animation
	 		
	 		foreach ($AosWpHtml->find(".aos-slide-left") as $AosSlideLeftHtml) {
				
				$AosSlideLeftHtml->setAttribute("data-aos", "slide-left");
				
			} // end slide-left animation foreach
			
			// slide-right animation
	 		
	 		foreach ($AosWpHtml->find(".aos-slide-right") as $AosSlideRightHtml) {
				
				$AosSlideRightHtml->setAttribute("data-aos", "slide-right");
				
			} // end slide-right animation foreach
			
			
			/*
	         * zoom animation 
	         *
	         */
	         
	        // zoom-in animation
	 		
	 		foreach ($AosWpHtml->find(".aos-zoom-in") as $AosZoomInHtml) {
				
				$AosZoomInHtml->setAttribute("data-aos", "zoom-in");
				
			} // end zoom-in animation foreach
			
			// zoom-in-up animation
	         
	        foreach ($AosWpHtml->find(".aos-zoom-in-up") as $AosZoomInUpHtml) {
				
				$AosZoomInUpHtml->setAttribute("data-aos", "zoom-in-up");
				
			} // end zoom-in-up animation foreach
			
			// zoom-in-down animation
	 		
	 		foreach ($AosWpHtml->find(".aos-zoom-in-down") as $AosZoomInDownHtml) {
				
				$AosZoomInDownHtml->setAttribute("data-aos", "zoom-in-down");
				
			} // end zoom-in-down animation foreach
			
			// zoom-in-left animation
	 		
	 		foreach ($AosWpHtml->find(".aos-zoom-in-left") as $AosZoomInLeftHtml) {
				
				$AosZoomInLeftHtml->setAttribute("data-aos", "zoom-in-left");
				
			} // end zoom-in-left animation foreach
			
			// zoom-in-right animation
	 		
	 		foreach ($AosWpHtml->find(".aos-zoom-in-right") as $AosZoomInRightHtml) {
				
				$AosZoomInRightHtml->setAttribute("data-aos", "zoom-in-right");
				
			} // end zoom-in-right animation foreach
			
			
			// zoom-out animation
	 		
	 		foreach ($AosWpHtml->find(".aos-zoom-out") as $AosZoomOutHtml) {
				
				$AosZoomOutHtml->setAttribute("data-aos", "zoom-out");
				
			} // end zoom-out animation foreach
			
			// zoom-out-up animation
	         
	        foreach ($AosWpHtml->find(".aos-zoom-out-up") as $AosZoomOutUpHtml) {
				
				$AosZoomOutUpHtml->setAttribute("data-aos", "zoom-out-up");
				
			} // end zoom-out-up animation foreach
			
			// zoom-out-down animation
	 		
	 		foreach ($AosWpHtml->find(".aos-zoom-out-down") as $AosZoomOutDownHtml) {
				
				$AosZoomOutDownHtml->setAttribute("data-aos", "zoom-out-down");
				
			} // end zoom-out-down animation foreach
			
			// zoom-out-left animation
	 		
	 		foreach ($AosWpHtml->find(".aos-zoom-out-left") as $AosZoomOutLeftHtml) {
				
				$AosZoomOutLeftHtml->setAttribute("data-aos", "zoom-out-left");
				
			} // end zoom-out-left animation foreach
			
			// zoom-out-right animation
	 		
	 		foreach ($AosWpHtml->find(".aos-zoom-out-right") as $AosZoomOutRightHtml) {
				
				$AosZoomOutRightHtml->setAttribute("data-aos", "zoom-out-right");
				
			} // end zoom-out-right animation foreach
			
			
			/*
	         * individual settings 
	         *
	         */
	         
	        // aos-once-false animation
	 		
	 		foreach ($AosWpHtml->find(".aos-once-false") as $AosOnceFalseHtml) {
				
				$AosOnceFalseHtml->setAttribute("data-aos-once", "false");
				
			} // end aos-once-false animation foreach
			
			// aos-once-true animation
	 		
	 		foreach ($AosWpHtml->find(".aos-once-true") as $AosOncetrueHtml) {
				
				$AosOncetrueHtml->setAttribute("data-aos-once", "true");
				
			} // end aos-once-true animation foreach
			
			// aos-easing-linear animation
	 		
	 		foreach ($AosWpHtml->find(".aos-easing-linear") as $AosEasingLinearHtml) {
				
				$AosEasingLinearHtml->setAttribute("data-aos-easing", "linear");
				
			} // end aos-easing-linear animation foreach
			
			// aos-easing-ease-in animation
	 		
	 		foreach ($AosWpHtml->find(".aos-easing-ease-in") as $AosEasingEaseInHtml) {
				
				$AosEasingEaseInHtml->setAttribute("data-aos-easing", "ease-in");
				
			} // end aos-easing-ease-in animation foreach
			
			// aos-easing-ease-out animation
	 		
	 		foreach ($AosWpHtml->find(".aos-easing-ease-out") as $AosEasingEaseOutHtml) {
				
				$AosEasingEaseOutHtml->setAttribute("data-aos-easing", "ease-out");
				
			} // end aos-easing-ease-out animation foreach
			
			// aos-easing-ease-in-out animation
	 		
	 		foreach ($AosWpHtml->find(".aos-easing-ease-in-out") as $AosEasingEaseInOutHtml) {
				
				$AosEasingEaseInOutHtml->setAttribute("data-aos-easing", "ease-in-out");
				
			} // end aos-easing-ease-in-out animation foreach
			
			// aos-easing-ease-in-back animation
	 		
	 		foreach ($AosWpHtml->find(".aos-easing-ease-in-back") as $AosEasingEaseInBackHtml) {
				
				$AosEasingEaseInBackHtml->setAttribute("data-aos-easing", "ease-in-back");
				
			} // end aos-easing-ease-in-back animation foreach
			
			// aos-easing-ease-out-back animation
	 		
	 		foreach ($AosWpHtml->find(".aos-easing-ease-out-back") as $AosEasingEaseOutBackHtml) {
				
				$AosEasingEaseOutBackHtml->setAttribute("data-aos-easing", "ease-out-back");
				
			} // end aos-easing-ease-out-back animation foreach
			
			// aos-easing-ease-in-out-back animation
	 		
	 		foreach ($AosWpHtml->find(".aos-easing-ease-in-out-back") as $AosEasingEaseInOutBackHtml) {
				
				$AosEasingEaseInOutBackHtml->setAttribute("data-aos-easing", "ease-in-out-back");
				
			} // end aos-easing-ease-in-out-back animation foreach
			
			// aos-easing-ease-in-sine animation
	 		
	 		foreach ($AosWpHtml->find(".aos-easing-ease-in-sine") as $AosEasingEaseInSineHtml) {
				
				$AosEasingEaseInSineHtml->setAttribute("data-aos-easing", "ease-in-sine");
				
			} // end aos-easing-ease-in-sine animation foreach
			
			// aos-easing-ease-out-sine animation
	 		
	 		foreach ($AosWpHtml->find(".aos-easing-ease-out-sine") as $AosEasingEaseOutSineHtml) {
				
				$AosEasingEaseOutSineHtml->setAttribute("data-aos-easing", "ease-out-sine");
				
			} // end aos-easing-ease-out-sine animation foreach
			
			// aos-easing-ease-in-out-sine animation
	 		
	 		foreach ($AosWpHtml->find(".aos-easing-ease-in-out-sine") as $AosEasingEaseInOutSineHtml) {
				
				$AosEasingEaseInOutSineHtml->setAttribute("data-aos-easing", "ease-in-out-sine");
				
			} // end aos-easing-ease-in-out-sine animation foreach
			
			
			// aos-easing-ease-in-quad animation
	 		
	 		foreach ($AosWpHtml->find(".aos-easing-ease-in-quad") as $AosEasingEaseInQuadHtml) {
				
				$AosEasingEaseInQuadHtml->setAttribute("data-aos-easing", "ease-in-quad");
				
			} // end aos-easing-ease-in-quad animation foreach
			
			// aos-easing-ease-out-quad animation
	 		
	 		foreach ($AosWpHtml->find(".aos-easing-ease-out-quad") as $AosEasingEaseOutQuadHtml) {
				
				$AosEasingEaseOutQuadHtml->setAttribute("data-aos-easing", "ease-out-quad");
				
			} // end aos-easing-ease-out-quad animation foreach
			
			// aos-easing-ease-in-out-quad animation
	 		
	 		foreach ($AosWpHtml->find(".aos-easing-ease-in-out-quad") as $AosEasingEaseInOutQuadHtml) {
				
				$AosEasingEaseInOutQuadHtml->setAttribute("data-aos-easing", "ease-in-out-quad");
				
			} // end aos-easing-ease-in-out-quad animation foreach
			
			// aos-easing-ease-in-cubic animation
	 		
	 		foreach ($AosWpHtml->find(".aos-easing-ease-in-cubic") as $AosEasingEaseInCubicHtml) {
				
				$AosEasingEaseInCubicHtml->setAttribute("data-aos-easing", "ease-in-cubic");
				
			} // end aos-easing-ease-in-cubic animation foreach
			
			// aos-easing-ease-out-cubic animation
	 		
	 		foreach ($AosWpHtml->find(".aos-easing-ease-out-cubic") as $AosEasingEaseOutCubicHtml) {
				
				$AosEasingEaseOutCubicHtml->setAttribute("data-aos-easing", "ease-out-cubic");
				
			} // end aos-easing-ease-out-cubic animation foreach
			
			// aos-easing-ease-in-out-cubic animation
	 		
	 		foreach ($AosWpHtml->find(".aos-easing-ease-in-out-cubic") as $AosEasingEaseInOutCubicHtml) {
				
				$AosEasingEaseInOutCubicHtml->setAttribute("data-aos-easing", "ease-in-out-cubic");
				
			} // end aos-easing-ease-in-out-cubic animation foreach
			
			// aos-easing-ease-in-quart animation
	 		
	 		foreach ($AosWpHtml->find(".aos-easing-ease-in-quart") as $AosEasingEaseInQuartHtml) {
				
				$AosEasingEaseInQuartHtml->setAttribute("data-aos-easing", "ease-in-quart");
				
			} // end aos-easing-ease-in-quart animation foreach
			
			// aos-easing-ease-out-quart animation
	 		
	 		foreach ($AosWpHtml->find(".aos-easing-ease-out-quart") as $AosEasingEaseOutQuartHtml) {
				
				$AosEasingEaseOutQuartHtml->setAttribute("data-aos-easing", "ease-out-quart");
				
			} // end aos-easing-ease-out-quart animation foreach
			
			// aos-easing-ease-in-out-quart animation
	 		
	 		foreach ($AosWpHtml->find(".aos-easing-ease-in-out-quart") as $AosEasingEaseInOutQuartHtml) {
				
				$AosEasingEaseInOutQuartHtml->setAttribute("data-aos-easing", "ease-in-out-quart");
				
			} // end aos-easing-ease-in-out-quart animation foreach
			
			
			/*
	         * other individual settings 
	         *
	         */
			
			/** Animation Duration **/
	        
	        // animation duration 100ms
	 		
	 		foreach ($AosWpHtml->find(".aos-duration-100") as $AosDuration100Html) {
				
				$AosDuration100Html->setAttribute("data-aos-duration", "100");
				
			} // end animation duration 100ms foreach
	        
	        // animation duration 200ms
	 		
	 		foreach ($AosWpHtml->find(".aos-duration-200") as $AosDuration200Html) {
				
				$AosDuration200Html->setAttribute("data-aos-duration", "200");
				
			} // end animation duration 200ms foreach
	        
	        // animation duration 300ms
	 		
	 		foreach ($AosWpHtml->find(".aos-duration-300") as $AosDuration300Html) {
				
				$AosDuration300Html->setAttribute("data-aos-duration", "300");
				
			} // end animation duration 300ms foreach
	        
	        // animation duration 400ms
	 		
	 		foreach ($AosWpHtml->find(".aos-duration-400") as $AosDuration400Html) {
				
				$AosDuration400Html->setAttribute("data-aos-duration", "400");
				
			} // end animation duration 400ms foreach
	        
	        // animation duration 500ms
	 		
	 		foreach ($AosWpHtml->find(".aos-duration-500") as $AosDuration500Html) {
				
				$AosDuration500Html->setAttribute("data-aos-duration", "500");
				
			} // end animation duration 500ms foreach
	         
	        // animation duration 600ms
	 		
	 		foreach ($AosWpHtml->find(".aos-duration-600") as $AosDuration600Html) {
				
				$AosDuration600Html->setAttribute("data-aos-duration", "600");
				
			} // end animation duration 600ms foreach
			
			// animation duration 700ms
	 		
	 		foreach ($AosWpHtml->find(".aos-duration-700") as $AosDuration700Html) {
				
				$AosDuration700Html->setAttribute("data-aos-duration", "700");
				
			} // end animation duration 700ms foreach
			
			// animation duration 800ms
	 		
	 		foreach ($AosWpHtml->find(".aos-duration-800") as $AosDuration800Html) {
				
				$AosDuration800Html->setAttribute("data-aos-duration", "800");
				
			} // end animation duration 800ms foreach
			
			// animation duration 900ms
	 		
	 		foreach ($AosWpHtml->find(".aos-duration-900") as $AosDuration900Html) {
				
				$AosDuration900Html->setAttribute("data-aos-duration", "900");
				
			} // end animation duration 900ms foreach
			
			// animation duration 1000ms
	 		
	 		foreach ($AosWpHtml->find(".aos-duration-1000") as $AosDuration1000Html) {
				
				$AosDuration1000Html->setAttribute("data-aos-duration", "1000");
				
			} // end animation duration 1000ms foreach
			
			// animation duration 1100ms
	 		
	 		foreach ($AosWpHtml->find(".aos-duration-1100") as $AosDuration1100Html) {
				
				$AosDuration1100Html->setAttribute("data-aos-duration", "1100");
				
			} // end animation duration 1100ms foreach
			
			// animation duration 1200ms
	 		
	 		foreach ($AosWpHtml->find(".aos-duration-1200") as $AosDuration1200Html) {
				
				$AosDuration1200Html->setAttribute("data-aos-duration", "1200");
				
			} // end animation duration 1200ms foreach
			
			// animation duration 1300ms
	 		
	 		foreach ($AosWpHtml->find(".aos-duration-1300") as $AosDuration1300Html) {
				
				$AosDuration1300Html->setAttribute("data-aos-duration", "1300");
				
			} // end animation duration 1300ms foreach
			
			// animation duration 1400ms
	 		
	 		foreach ($AosWpHtml->find(".aos-duration-1400") as $AosDuration1400Html) {
				
				$AosDuration1400Html->setAttribute("data-aos-duration", "1400");
				
			} // end animation duration 1400ms foreach
			
			// animation duration 1500ms
	 		
	 		foreach ($AosWpHtml->find(".aos-duration-1500") as $AosDuration1500Html) {
				
				$AosDuration1500Html->setAttribute("data-aos-duration", "1500");
				
			} // end animation duration 1500ms foreach
			
			// animation duration 1600ms
	 		
	 		foreach ($AosWpHtml->find(".aos-duration-1600") as $AosDuration1600Html) {
				
				$AosDuration1600Html->setAttribute("data-aos-duration", "1600");
				
			} // end animation duration 1600ms foreach
			
			// animation duration 1700ms
	 		
	 		foreach ($AosWpHtml->find(".aos-duration-1700") as $AosDuration1700Html) {
				
				$AosDuration1700Html->setAttribute("data-aos-duration", "1700");
				
			} // end animation duration 1700ms foreach
			
			// animation duration 1800ms
	 		
	 		foreach ($AosWpHtml->find(".aos-duration-1800") as $AosDuration1800Html) {
				
				$AosDuration1800Html->setAttribute("data-aos-duration", "1800");
				
			} // end animation duration 1800ms foreach
			
			// animation duration 1900ms
	 		
	 		foreach ($AosWpHtml->find(".aos-duration-1900") as $AosDuration1900Html) {
				
				$AosDuration1900Html->setAttribute("data-aos-duration", "1900");
				
			} // end animation duration 1900ms foreach
			
			// animation duration 2000ms
	 		
	 		foreach ($AosWpHtml->find(".aos-duration-2000") as $AosDuration2000Html) {
				
				$AosDuration2000Html->setAttribute("data-aos-duration", "2000");
				
			} // end animation duration 2000ms foreach
			
			// animation duration 2100ms
	 		
	 		foreach ($AosWpHtml->find(".aos-duration-2100") as $AosDuration2100Html) {
				
				$AosDuration2100Html->setAttribute("data-aos-duration", "2100");
				
			} // end animation duration 2100ms foreach
			
			// animation duration 2200ms
	 		
	 		foreach ($AosWpHtml->find(".aos-duration-2200") as $AosDuration2200Html) {
				
				$AosDuration2200Html->setAttribute("data-aos-duration", "2200");
				
			} // end animation duration 2200ms foreach
			
			// animation duration 2300ms
	 		
	 		foreach ($AosWpHtml->find(".aos-duration-2300") as $AosDuration2300Html) {
				
				$AosDuration2300Html->setAttribute("data-aos-duration", "2300");
				
			} // end animation duration 2300ms foreach
			
			// animation duration 2400ms
	 		
	 		foreach ($AosWpHtml->find(".aos-duration-2400") as $AosDuration2400Html) {
				
				$AosDuration2400Html->setAttribute("data-aos-duration", "2400");
				
			} // end animation duration 2400ms foreach
			
			// animation duration 2500ms
	 		
	 		foreach ($AosWpHtml->find(".aos-duration-2500") as $AosDuration2500Html) {
				
				$AosDuration2500Html->setAttribute("data-aos-duration", "2500");
				
			} // end animation duration 2500ms foreach
			
			// animation duration 2600ms
	 		
	 		foreach ($AosWpHtml->find(".aos-duration-2600") as $AosDuration2600Html) {
				
				$AosDuration2600Html->setAttribute("data-aos-duration", "2600");
				
			} // end animation duration 2600ms foreach
			
			// animation duration 2700ms
	 		
	 		foreach ($AosWpHtml->find(".aos-duration-2700") as $AosDuration2700Html) {
				
				$AosDuration2700Html->setAttribute("data-aos-duration", "2700");
				
			} // end animation duration 2700ms foreach
			
			// animation duration 2800ms
	 		
	 		foreach ($AosWpHtml->find(".aos-duration-2800") as $AosDuration2800Html) {
				
				$AosDuration2800Html->setAttribute("data-aos-duration", "2800");
				
			} // end animation duration 2800ms foreach
			
			// animation duration 2900ms
	 		
	 		foreach ($AosWpHtml->find(".aos-duration-2900") as $AosDuration2900Html) {
				
				$AosDuration2900Html->setAttribute("data-aos-duration", "2900");
				
			} // end animation duration 2900ms foreach
			
			// animation duration 3000ms
	 		
	 		foreach ($AosWpHtml->find(".aos-duration-3000") as $AosDuration3000Html) {
				
				$AosDuration3000Html->setAttribute("data-aos-duration", "3000");
				
			} // end animation duration 3000ms foreach
			
			
			/** Animation Delay **/
			
			// animation delay 100ms
	 		
	 		foreach ($AosWpHtml->find(".aos-delay-100") as $AosDelay100Html) {
				
				$AosDelay100Html->setAttribute("data-aos-delay", "100");
				
			} // end animation delay 100ms foreach
	        
	        // animation delay 200ms
	 		
	 		foreach ($AosWpHtml->find(".aos-delay-200") as $AosDelay200Html) {
				
				$AosDelay200Html->setAttribute("data-aos-delay", "200");
				
			} // end animation delay 200ms foreach
	        
	        // animation delay 300ms
	 		
	 		foreach ($AosWpHtml->find(".aos-delay-300") as $AosDelay300Html) {
				
				$AosDelay300Html->setAttribute("data-aos-delay", "300");
				
			} // end animation delay 300ms foreach
	        
	        // animation delay 400ms
	 		
	 		foreach ($AosWpHtml->find(".aos-delay-400") as $AosDelay400Html) {
				
				$AosDelay400Html->setAttribute("data-aos-delay", "400");
				
			} // end animation delay 400ms foreach
	        
	        // animation delay 500ms
	 		
	 		foreach ($AosWpHtml->find(".aos-delay-500") as $AosDelay500Html) {
				
				$AosDelay500Html->setAttribute("data-aos-delay", "500");
				
			} // end animation delay 500ms foreach
	         
	        // animation delay 600ms
	 		
	 		foreach ($AosWpHtml->find(".aos-delay-600") as $AosDelay600Html) {
				
				$AosDelay600Html->setAttribute("data-aos-delay", "600");
				
			} // end animation delay 600ms foreach
			
			// animation delay 700ms
	 		
	 		foreach ($AosWpHtml->find(".aos-delay-700") as $AosDelay700Html) {
				
				$AosDelay700Html->setAttribute("data-aos-delay", "700");
				
			} // end animation delay 700ms foreach
			
			// animation delay 800ms
	 		
	 		foreach ($AosWpHtml->find(".aos-delay-800") as $AosDelay800Html) {
				
				$AosDelay800Html->setAttribute("data-aos-delay", "800");
				
			} // end animation delay 800ms foreach
			
			// animation delay 900ms
	 		
	 		foreach ($AosWpHtml->find(".aos-delay-900") as $AosDelay900Html) {
				
				$AosDelay900Html->setAttribute("data-aos-delay", "900");
				
			} // end animation delay 900ms foreach
			
			// animation delay 1000ms
	 		
	 		foreach ($AosWpHtml->find(".aos-delay-1000") as $AosDelay1000Html) {
				
				$AosDelay1000Html->setAttribute("data-aos-delay", "1000");
				
			} // end animation delay 1000ms foreach
			
			// animation delay 1100ms
	 		
	 		foreach ($AosWpHtml->find(".aos-delay-1100") as $AosDelay1100Html) {
				
				$AosDelay1100Html->setAttribute("data-aos-delay", "1100");
				
			} // end animation delay 1100ms foreach
			
			// animation delay 1200ms
	 		
	 		foreach ($AosWpHtml->find(".aos-delay-1200") as $AosDelay1200Html) {
				
				$AosDelay1200Html->setAttribute("data-aos-delay", "1200");
				
			} // end animation delay 1200ms foreach
			
			// animation delay 1300ms
	 		
	 		foreach ($AosWpHtml->find(".aos-delay-1300") as $AosDelay1300Html) {
				
				$AosDelay1300Html->setAttribute("data-aos-delay", "1300");
				
			} // end animation delay 1300ms foreach
			
			// animation delay 1400ms
	 		
	 		foreach ($AosWpHtml->find(".aos-delay-1400") as $AosDelay1400Html) {
				
				$AosDelay1400Html->setAttribute("data-aos-delay", "1400");
				
			} // end animation delay 1400ms foreach
			
			// animation delay 1500ms
	 		
	 		foreach ($AosWpHtml->find(".aos-delay-1500") as $AosDelay1500Html) {
				
				$AosDelay1500Html->setAttribute("data-aos-delay", "1500");
				
			} // end animation delay 1500ms foreach
			
			// animation delay 1600ms
	 		
	 		foreach ($AosWpHtml->find(".aos-delay-1600") as $AosDelay1600Html) {
				
				$AosDelay1600Html->setAttribute("data-aos-delay", "1600");
				
			} // end animation delay 1600ms foreach
			
			// animation delay 1700ms
	 		
	 		foreach ($AosWpHtml->find(".aos-delay-1700") as $AosDelay1700Html) {
				
				$AosDelay1700Html->setAttribute("data-aos-delay", "1700");
				
			} // end animation delay 1700ms foreach
			
			// animation delay 1800ms
	 		
	 		foreach ($AosWpHtml->find(".aos-delay-1800") as $AosDelay1800Html) {
				
				$AosDelay1800Html->setAttribute("data-aos-delay", "1800");
				
			} // end animation delay 1800ms foreach
			
			// animation delay 1900ms
	 		
	 		foreach ($AosWpHtml->find(".aos-delay-1900") as $AosDelay1900Html) {
				
				$AosDelay1900Html->setAttribute("data-aos-delay", "1900");
				
			} // end animation delay 1900ms foreach
			
			// animation delay 2000ms
	 		
	 		foreach ($AosWpHtml->find(".aos-delay-2000") as $AosDelay2000Html) {
				
				$AosDelay2000Html->setAttribute("data-aos-delay", "2000");
				
			} // end animation delay 2000ms foreach
			
			// animation delay 2100ms
	 		
	 		foreach ($AosWpHtml->find(".aos-delay-2100") as $AosDelay2100Html) {
				
				$AosDelay2100Html->setAttribute("data-aos-delay", "2100");
				
			} // end animation delay 2100ms foreach
			
			// animation delay 2200ms
	 		
	 		foreach ($AosWpHtml->find(".aos-delay-2200") as $AosDelay2200Html) {
				
				$AosDelay2200Html->setAttribute("data-aos-delay", "2200");
				
			} // end animation delay 2200ms foreach
			
			// animation delay 2300ms
	 		
	 		foreach ($AosWpHtml->find(".aos-delay-2300") as $AosDelay2300Html) {
				
				$AosDelay2300Html->setAttribute("data-aos-delay", "2300");
				
			} // end animation delay 2300ms foreach
			
			// animation delay 2400ms
	 		
	 		foreach ($AosWpHtml->find(".aos-delay-2400") as $AosDelay2400Html) {
				
				$AosDelay2400Html->setAttribute("data-aos-delay", "2400");
				
			} // end animation delay 2400ms foreach
			
			// animation delay 2500ms
	 		
	 		foreach ($AosWpHtml->find(".aos-delay-2500") as $AosDelay2500Html) {
				
				$AosDelay2500Html->setAttribute("data-aos-delay", "2500");
				
			} // end animation delay 2500ms foreach
			
			// animation delay 2600ms
	 		
	 		foreach ($AosWpHtml->find(".aos-delay-2600") as $AosDelay2600Html) {
				
				$AosDelay2600Html->setAttribute("data-aos-delay", "2600");
				
			} // end animation delay 2600ms foreach
			
			// animation delay 2700ms
	 		
	 		foreach ($AosWpHtml->find(".aos-delay-2700") as $AosDelay2700Html) {
				
				$AosDelay2700Html->setAttribute("data-aos-delay", "2700");
				
			} // end animation delay 2700ms foreach
			
			// animation delay 2800ms
	 		
	 		foreach ($AosWpHtml->find(".aos-delay-2800") as $AosDelay2800Html) {
				
				$AosDelay2800Html->setAttribute("data-aos-delay", "2800");
				
			} // end animation delay 2800ms foreach
			
			// animation delay 2900ms
	 		
	 		foreach ($AosWpHtml->find(".aos-delay-2900") as $AosDelay2900Html) {
				
				$AosDelay2900Html->setAttribute("data-aos-delay", "2900");
				
			} // end animation delay 2900ms foreach
			
			// animation delay 3000ms
	 		
	 		foreach ($AosWpHtml->find(".aos-delay-3000") as $AosDelay3000Html) {
				
				$AosDelay3000Html->setAttribute("data-aos-delay", "3000");
				
			} // end animation delay 3000ms foreach
			

            return $AosWpHtml;
			
        } catch (Exception $e) {
            return $html;
        }
    }
	
	// preparation to enqueue the assets conditionally
	public function add_aoswp_enabled_body_class($classes) {	
		global $post;
		if ($this->has_aos($post)) {
			$classes[] = 'aoswp-enabled';
		}
		return $classes;
	}

	public static function has_aos($post) {
		if ($post) {
			$post_content = get_post_field('post_content', $post);
			if (strpos($post_content, 'aos-') !== false) {
				return true;
			}
		}
		return false;
	}
	
	// register the assets
	public function aoswp_register_assets() {
		// register the assets first
		wp_register_style( AOSWP_HANDLER.'-style', AOSWP_PUBLIC_URL . 'css/aos.css', [], AOSWP_VERSION);
        wp_register_script(AOSWP_HANDLER.'-script', AOSWP_PUBLIC_URL . 'js/aos.js', [], AOSWP_VERSION, true, PHP_INT_MAX);
	}
	
	// enqueue the assets conditionally
	public function aoswp_enqueue_scripts() {
		
        // check if the page has the 'aoswp-enabled' class and enqueue the scripts
        $classes = get_body_class();
	    if (!in_array('aoswp-enabled',$classes)) {
	        return;
	    }
		
		wp_enqueue_script( AOSWP_HANDLER.'-script' );
		wp_enqueue_style( AOSWP_HANDLER.'-style' );
		
		// default aos init
		$aos_init = apply_filters( 'aos_init',
			'var aoswp_params = {
			"offset":"200",
			"duration":"1200",
			"easing":"ease",
			"delay":"0",
			"once":true};'
		);
		
		// minify the aos init inline script before inject
		$aos_init = preg_replace(['/(?:(?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:(?<!\:|\\\|\'|\")\/\/.*))/','/\>[^\S ]+/s','/[^\S ]+\</s','#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/))|\s*+;\s*+(})\s*+|\s*+([*$~^|]?+=|[{};,>~]|\s(?![0-9\.])|!important\b)\s*+|([[(:])\s++|\s++([])])|\s++(:)\s*+(?!(?>[^{}"\']++|"(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')*+{)|^\s++|\s++\z|(\s)\s+#si'],['','>','<','$1$2$3$4$5$6$7'], $aos_init);
		
		// inject aos init inline script
		wp_add_inline_script( AOSWP_HANDLER.'-script', wp_kses_data($aos_init), 'before' );
		
		$aoswp_inline_style = "
		
			@media (min-width: 768px) {
				html:not(.no-js) .aoswp-enabled [data-aos].aoswp-disable-desktop {
					opacity: 1!important;
					-webkit-transform: none!important;
					transform: none!important;
					transition: none!important;
					transition-delay: 0!important;
					transition-timing-function: unset!important;
					transition-duration: unset!important;
					transition-property: none!important;
				}
			}
			
			@media (max-width: 767px) {
				html:not(.no-js) .aoswp-enabled [data-aos].aoswp-disable-mobile {
					opacity: 1!important;
					-webkit-transform: none!important;
					transform: none!important;
					transition: none!important;
					transition-delay: 0!important;
					transition-timing-function: unset!important;
					transition-duration: unset!important;
					transition-property: none!important;
				}
			}
			
		";
		
		// minify the aoswp inline style before inject
		$aoswp_inline_style = preg_replace(['#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')|\/\*(?!\!)(?>.*?\*\/)|^\s*|\s*$#s','#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/))|\s*+;\s*+(})\s*+|\s*+([*$~^|]?+=|[{};,>~]|\s(?![0-9\.])|!important\b)\s*+|([[(:])\s++|\s++([])])|\s++(:)\s*+(?!(?>[^{}"\']++|"(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')*+{)|^\s++|\s++\z|(\s)\s+#si','#(?<=[\s:])(0)(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)#si','#(?<=[\s:,\-])0+\.(\d+)#s',],['$1','$1$2$3$4$5$6$7','$1','.$1',], $aoswp_inline_style);
		
		// inject aos init inline style
		wp_add_inline_style( AOSWP_HANDLER.'-style', '' . wp_strip_all_tags( $aoswp_inline_style ) . '' );
		
    }

    public function init() {
        add_action('template_redirect', array($this, 'aoswp_start_ob_buffering'));
		add_filter('body_class', array($this, 'add_aoswp_enabled_body_class'));
		add_action('wp_enqueue_scripts', array($this, 'aoswp_register_assets'));
		add_action('wp_enqueue_scripts', array($this, 'aoswp_enqueue_scripts'), PHP_INT_MAX);
		
    }

    public function aoswp_start_ob_buffering() {
        // start rewrites
        ob_start(array($this, 'aoswp_rewrite_buffer'));
        // Register a shutdown function to flush the buffer and process the modified content
        register_shutdown_function(array($this, 'end_aoswp_rewrite_buffer'));
    }
	
	public function end_aoswp_rewrite_buffer() {
	    // Get the contents of the output buffer
	    $this->buffer = ob_get_contents();
	    // Clean the output buffer if it's active
	    if (ob_get_length() > 0) {
	        ob_end_clean();
	    }
	    // Process the modified content
	    echo $this->buffer;
	    // Flush the output buffer if it's active
	    if (ob_get_length() > 0) {
	        ob_end_flush();
	    }
	}
}

$aoswpHtmlRewrite = new AOSWP_init();
$aoswpHtmlRewrite->init();