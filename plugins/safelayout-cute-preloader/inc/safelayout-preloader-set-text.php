<?php
defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

if ( ! function_exists( 'safelayout_preloader_set_text' ) ) {

	// Return text css
	function safelayout_preloader_set_text( $options ) {
		?>
		.sl-pl-text {
			position: relative;
		}
		.sl-pl-text span {
			display: inline-block;
		}
		<?php
		switch ( $options ) {
			case 'shadow':
				?>
				#sl-pl-shadow span {
					animation: sl-pl-shadow-anim 1.2s linear infinite;
					-webkit-animation: sl-pl-shadow-anim 1.2s linear infinite;
				}
				@-webkit-keyframes sl-pl-shadow-anim {
					50% {
						text-shadow: 0 1px 0 #dba1a1, 0 2px 0 #d89999, 0 3px 0 #d59292, 0 4px 0 #d28a8a,
							0 5px 0 #cf8383, 0 6px 0 #cd7c7c, 0 7px 0 #ca7474, 0 8px 0 #c76d6d, 0 0 5px rgba(230, 139, 139, 0.05),
							0 -1px 3px rgba(230, 139, 139, 0.2), 0 9px 9px rgba(230, 139, 139, 0.3), 0 12px 12px rgba(230, 139, 139, 0.4),
							0 15px 15px rgba(230, 139, 139, 0.4);
					}
					0%,
					100% {
						text-shadow: none;
					}
				}
				@keyframes sl-pl-shadow-anim {
					50% {
						text-shadow: 0 1px 0 #dba1a1, 0 2px 0 #d89999, 0 3px 0 #d59292, 0 4px 0 #d28a8a,
							0 5px 0 #cf8383, 0 6px 0 #cd7c7c, 0 7px 0 #ca7474, 0 8px 0 #c76d6d, 0 0 5px rgba(230, 139, 139, 0.05),
							0 -1px 3px rgba(230, 139, 139, 0.2), 0 9px 9px rgba(230, 139, 139, 0.3), 0 12px 12px rgba(230, 139, 139, 0.4),
							0 15px 15px rgba(230, 139, 139, 0.4);
					}
					0%,
					100% {
						text-shadow: none;
					}
				}
				<?php
				break;
			case 'glow':
				?>
				#sl-pl-glow span {
					animation: sl-pl-glow-anim 1s ease-in infinite;
					-webkit-animation: sl-pl-glow-anim 1s ease-in infinite;
				}
				@-webkit-keyframes sl-pl-glow-anim {
					50% {
						text-shadow: 0 0 5px yellow;
					}
				}
				@-webkit-keyframes sl-pl-glow-anim {
					50% {
						text-shadow: 0 0 5px yellow;
					}
				}
				<?php
				break;
			case 'yoyo':
				?>
				#sl-pl-yoyo span {
					animation: sl-pl-yoyo-anim 1s ease-in infinite;
					-webkit-animation: sl-pl-yoyo-anim 1s ease-in infinite;
				}
				@-webkit-keyframes sl-pl-yoyo-anim {
					50% {
						-webkit-transform: translate(-2px, -2px);
					}
					0%,
					100% {
						-webkit-transform: translate(0, 0);
					}
				}
				@keyframes sl-pl-yoyo-anim {
					50% {
						transform: translate(-2px, -2px);
					}
					0%,
					100% {
						transform: translate(0, 0);
					}
				}
				<?php
				break;
			case 'spring':
				?>
				#sl-pl-spring span {
					animation: sl-pl-spring-anim 1.2s ease infinite;
					-webkit-animation: sl-pl-spring-anim 1.2s ease infinite;
					transform: scaleY(0.8);
					-webkit-transform: scaleY(0.8);
					transform-origin: 50% 100%;
					-webkit-transform-origin: 50% 100%;
				}
				@-webkit-keyframes sl-pl-spring-anim {
					0% {
						-webkit-transform: scaleY(0.8);
					}
					40% {
						-webkit-transform: scaleY(1.4);
					}
					100% {
						-webkit-transform: scaleY(0.8);
					}
				}
				@keyframes sl-pl-spring-anim {
					0% {
						transform: scaleY(0.8);
					}
					40% {
						transform: scaleY(1.4);
					}
					100% {
						transform: scaleY(0.8);
					}
				}
				<?php
				break;
			case 'bounce':
				?>
				#sl-pl-bounce span {
					animation: sl-pl-bounce-anim 1.2s linear infinite;
					-webkit-animation: sl-pl-bounce-anim 1.2s linear infinite;
					transform-origin: 50% 100%;
					-webkit-transform-origin: 50% 100%;
				}
				@-webkit-keyframes sl-pl-bounce-anim {
					0% {
						-webkit-animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
						-webkit-transform: translate(0, 0) scale(1, 1);
					}
					40% {
						-webkit-animation-timing-function: cubic-bezier(0.55, 0.055, 0.675, 0.19);
						-webkit-transform: translate(0, -150%) scale(0.8, 1.2);
					}
					70% {
						-webkit-transform: scale(1, 1);
					}
					85% {
						-webkit-transform: translate(0, 0) scale(1.4, 0.6);
					}
					95% {
						-webkit-transform: scale(1, 1);
					}
				}
				@keyframes sl-pl-bounce-anim {
					0% {
						animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
						transform: translate(0, 0) scale(1, 1);
					}
					40% {
						animation-timing-function: cubic-bezier(0.55, 0.055, 0.675, 0.19);
						transform: translate(0, -150%) scale(0.8, 1.2);
					}
					70% {
						transform: scale(1, 1);
					}
					85% {
						transform: translate(0, 0) scale(1.4, 0.6);
					}
					95% {
						transform: scale(1, 1);
					}
				}
				<?php
				break;
			case 'zoom':
				?>
				#sl-pl-zoom span {
					animation: sl-pl-zoom-anim 1.2s linear infinite;
					-webkit-animation: sl-pl-zoom-anim 1.2s linear infinite;
				}
				@-webkit-keyframes sl-pl-zoom-anim {
					50% {
						opacity: 0.3;
						-webkit-transform: scale(0.4);
					}
					0%,
					100% {
						-webkit-transform: scale(1);
					}
				}
				@keyframes sl-pl-zoom-anim {
					50% {
						opacity: 0.3;
						transform: scale(0.4);
					}
					0%,
					100% {
						transform: scale(1);
					}
				}
				<?php
				break;
			case 'wave':
				?>
				#sl-pl-wave span {
					animation: sl-pl-wave-anim 1s linear infinite;
					-webkit-animation: sl-pl-wave-anim 1s linear infinite;
				}
				@-webkit-keyframes sl-pl-wave-anim {
					0% {
						-webkit-transform: scaleY(1);
					}
					50% {
						-webkit-transform: scaleY(0.4);
					}
					100% {
						-webkit-transform: scaleY(1);
					}
				}
				@keyframes sl-pl-wave-anim {
					0% {
						transform: scaleY(1);
					}
					50% {
						transform: scaleY(0.4);
					}
					100% {
						transform: scaleY(1);
					}
				}
				<?php
				break;
			case 'swing':
				?>
				#sl-pl-swing span {
					animation: sl-pl-swing-anim 1s linear infinite;
					-webkit-animation: sl-pl-swing-anim 1s linear infinite;
				}
				@-webkit-keyframes sl-pl-swing-anim {
					20% {
						-webkit-transform: rotate(15deg);
					}
					40% {
						-webkit-transform: rotate(-10deg);
					}
					60% {
						-webkit-transform: rotate(5deg);
					}
					80% {
						-webkit-transform: rotate(-5deg);
					}
					100% {
						-webkit-transform: rotate(0);
					}
				}
				@keyframes sl-pl-swing-anim {
					20% {
						transform: rotate(15deg);
					}
					40% {
						transform: rotate(-10deg);
					}
					60% {
						transform: rotate(5deg);
					}
					80% {
						transform: rotate(-5deg);
					}
					100% {
						transform: rotate(0);
					}
				}
				<?php
				break;
		}//end of switch
	}
}