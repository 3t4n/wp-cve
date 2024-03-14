<?php
defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

if ( ! function_exists( 'safelayout_preloader_set_brand' ) ) {

	// Return brand css
	function safelayout_preloader_set_brand( $options ) {
		?>
		.sl-pl-brand {
			display: inline-block;
			height: auto;
			max-width: 100%;
		}
		.sl-pl-brand-container div {
			display: inline-block;
		}
		.sl-pl-brand-container,
		.sl-pl-brand-container div {
			perspective: 200px;
			-webkit-perspective: 200px;
			position: relative;
			text-align: center;
		}
		#sl-pl-brand-parent {
			width: 100%;
		}
		.sl-pl-brand-progress {
			background-repeat: no-repeat;
			display: inline-block;
			height: 100%;
			left: 0;
			overflow: hidden;
			position: absolute !important;
			top: 0;
			width: 100%;
		}
		<?php
		switch ( $options ) {
			case 'bounce':
				?>
				#sl-pl-brand-bounce {
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
			case 'yoyo':
				?>
				#sl-pl-brand-yoyo {
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
			case 'swing':
				?>
				#sl-pl-brand-swing {
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
			case 'flash':
				?>
				#sl-pl-brand-flash {
					animation: sl-pl-flash-anim 4s cubic-bezier(0.4, 0, 0.2, 1) infinite;
					-webkit-animation: sl-pl-flash-anim 4s cubic-bezier(0.4, 0, 0.2, 1) infinite;
				}
				@-webkit-keyframes sl-pl-flash-anim {
					24%, 43% {
						opacity: 0.2;
					}
					20%, 30%, 35%, 49% {
						opacity: 1;
					}
				}
				@keyframes sl-pl-flash-anim {
					24%, 43% {
						opacity: 0.2;
					}
					20%, 30%, 35%, 49% {
						opacity: 1;
					}
				}
				<?php
				break;
			case 'rotate-2D':
				?>
				#sl-pl-brand-rotate-2D {
					animation: sl-pl-rotate-2D-anim 3s cubic-bezier(0.2, 0.95, 0.45, 1.15) infinite;
					-webkit-animation: sl-pl-rotate-2D-anim 3s cubic-bezier(0.2, 0.95, 0.45, 1.15) infinite;
				}
				@-webkit-keyframes sl-pl-rotate-2D-anim {
					0% {
						-webkit-transform: rotate(0);
					}
					60%, 100% {
						-webkit-transform: rotate(360deg);
					}
				}
				@keyframes sl-pl-rotate-2D-anim {
					0% {
						transform: rotate(0);
					}
					60%, 100% {
						transform: rotate(360deg);
					}
				}
				<?php
				break;
			case 'rotate-3D-X':
				?>
				#sl-pl-brand-rotate-3D-X {
					animation: sl-pl-rotate-3D-X-anim 2.2s cubic-bezier(0.3, 0, 0.45, 1) infinite;
					-webkit-animation: sl-pl-rotate-3D-X-anim 2.2s cubic-bezier(0.3, 0, 0.45, 1) infinite;
				}
				@-webkit-keyframes sl-pl-rotate-3D-X-anim {
					0% {
						-webkit-transform: rotateX(360deg);
					}
					60%, 100% {
						-webkit-transform: rotateX(0);
					}
				}
				@keyframes sl-pl-rotate-3D-X-anim {
					0% {
						transform: rotateX(360deg);
					}
					60%, 100% {
						transform: rotateX(0);
					}
				}
				<?php
				break;
			case 'rotate-3D-Y':
				?>
				#sl-pl-brand-rotate-3D-Y {
					animation: sl-pl-rotate-3D-Y-anim 2.2s cubic-bezier(0.3, 0, 0.45, 1) infinite;
					-webkit-animation: sl-pl-rotate-3D-Y-anim 2.2s cubic-bezier(0.3, 0, 0.45, 1) infinite;
				}
				@-webkit-keyframes sl-pl-rotate-3D-Y-anim {
					0% {
						-webkit-transform: rotateY(360deg);
					}
					60%, 100% {
						-webkit-transform: rotateY(0);
					}
				}
				@keyframes sl-pl-rotate-3D-Y-anim {
					0% {
						transform: rotateY(360deg);
					}
					60%, 100% {
						transform: rotateY(0);
					}
				}
				<?php
				break;
			case 'wrest-X':
				?>
				.sl-pl-brand-wrest-X {
					animation: sl-pl-wrest-X-anim 2.4s linear infinite;
					-webkit-animation: sl-pl-wrest-X-anim 2.4s linear infinite;
				}
				@-webkit-keyframes sl-pl-wrest-X-anim {
					0% {
						-webkit-transform: scale(1) rotateX(360deg);
					}
					25% {
						-webkit-transform: scale(1.3) rotateX(180deg);
					}
					55%, 100% {
						-webkit-transform: scale(1) rotateX(0);
					}
				}
				@keyframes sl-pl-wrest-X-anim {
					0% {
						transform: scale(1) rotateX(360deg);
					}
					25% {
						transform: scale(1.3) rotateX(180deg);
					}
					55%, 100% {
						transform: scale(1) rotateX(0);
					}
				}
				<?php
				break;
			case 'wrest-Y':
				?>
				.sl-pl-brand-wrest-Y {
					animation: sl-pl-wrest-Y-anim 2.4s linear infinite;
					-webkit-animation: sl-pl-wrest-Y-anim 2.4s linear infinite;
				}
				@-webkit-keyframes sl-pl-wrest-Y-anim {
					0% {
						-webkit-transform: scale(1) rotateY(360deg);
					}
					25% {
						-webkit-transform: scale(1.3) rotateY(180deg);
					}
					55%, 100% {
						-webkit-transform: scale(1) rotateY(0);
					}
				}
				@keyframes sl-pl-wrest-Y-anim {
					0% {
						transform: scale(1) rotateY(360deg);
					}
					25% {
						transform: scale(1.3) rotateY(180deg);
					}
					55%, 100% {
						transform: scale(1) rotateY(0);
					}
				}
				<?php
				break;
			case 'roll':
				?>
				.sl-pl-brand-roll {
					animation: sl-pl-roll-anim 2.9s cubic-bezier(0.39, 0.58, 0.57, 1) infinite;
					-webkit-animation: sl-pl-roll-anim 2.9s cubic-bezier(0.39, 0.58, 0.57, 1) infinite;
					transform-origin: -10% 15% -35px;
					-webkit-transform-origin: -10% 15% -35px;
				}
				@-webkit-keyframes sl-pl-roll-anim {
					0% {
						-webkit-transform: translate(20px ,-20px) rotateX(-720deg) scale(2,2);
					}
					45%, 100% {
						-webkit-transform: translate(0 ,0) rotateX(0) scale(1, 1);
					}
				}
				@keyframes sl-pl-roll-anim {
					0% {
						transform: translate(20px ,-20px) rotateX(-720deg) scale(2,2);
					}
					45%, 100% {
						transform: translate(0 ,0) rotateX(0) scale(1, 1);
					}
				}
				<?php
				break;
			case 'pipe':
				?>
				.sl-pl-brand-pipe {
					animation: sl-pl-pipe-anim 2.9s ease-in-out infinite;
					-webkit-animation: sl-pl-pipe-anim 2.9s ease-in-out infinite;
					transform-origin: 70% 14% -40px;
					-webkit-transform-origin: 70% 14% -40px;
				}
				@-webkit-keyframes sl-pl-pipe-anim {
					0% {
						-webkit-transform: translate(20px ,-20px) rotateZ(-10deg) rotateX(720deg) rotateY(-50deg) scale(2,2);
					}
					45%, 100% {
						-webkit-transform: translate(0 ,0) rotateZ(0) rotateX(0) rotateY(0) scale(1, 1);
					}
				}
				@keyframes sl-pl-pipe-anim {
					0% {
						transform: translate(20px ,-20px) rotateZ(-10deg) rotateX(720deg) rotateY(-50deg) scale(2,2);
					}
					45%, 100% {
						transform: translate(0 ,0) rotateZ(0) rotateX(0) rotateY(0) scale(1, 1);
					}
				}
				<?php
				break;
			case 'swirl':
				?>
				.sl-pl-brand-swirl {
					animation: sl-pl-swirl-anim 2.9s ease-in-out infinite;
					-webkit-animation: sl-pl-swirl-anim 2.9s ease-in-out infinite;
					transform-origin: 30% 10% 50px;
					-webkit-transform-origin: 30% 10% 50px;
				}
				@-webkit-keyframes sl-pl-swirl-anim {
					0% {
						-webkit-transform: translate(20px ,-20px) rotateZ(-360deg) rotateY(50deg) scale(0.5,0.5);
					}
					45%, 100% {
						-webkit-transform: translate(0 ,0) rotateZ(0) rotateY(0) scale(1, 1);
					}
				}
				@keyframes sl-pl-swirl-anim {
					0% {
						transform: translate(20px ,-20px) rotateZ(-360deg) rotateY(50deg) scale(0.5,0.5);
					}
					45%, 100% {
						transform: translate(0 ,0) rotateZ(0) rotateY(0) scale(1, 1);
					}
				}
				<?php
				break;
			case 'sheet':
				?>
				.sl-pl-brand-sheet {
					animation: sl-pl-sheet-anim 2.9s cubic-bezier(0.4, 0, 0.2, 1) infinite;
					-webkit-animation: sl-pl-sheet-anim 2.9s cubic-bezier(0.4, 0, 0.2, 1) infinite;
					transform-origin: 90% 0 -40px;
					-webkit-transform-origin: 90% 0 -40px;
				}
				@-webkit-keyframes sl-pl-sheet-anim {
					0% {
						-webkit-transform: rotateX(0deg) scale(0.1, 1);
					}
					45%, 100% {
						-webkit-transform: rotateX(-360deg) scale(1, 1);
					}
				}
				@keyframes sl-pl-sheet-anim {
					0% {
						transform: rotateX(0deg) scale(0.1, 1);
					}
					45%, 100% {
						transform: rotateX(-360deg) scale(1, 1);
					}
				}
				<?php
				break;
		}//end of switch
	}
}