<?php
defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

if ( ! function_exists( 'safelayout_preloader_set_bar' ) ) {

	// Return brand css
	function safelayout_preloader_set_bar( $options ) {
		?>
		.sl-pl-bar-container {
			box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.2);
			box-sizing: content-box;
			left: 50%;
			position: absolute;
			transform: translateX(-50%);
			-webkit-transform: translateX(-50%);
			z-index: 99;
		}
		.sl-pl-bar-bg {
			border-radius: inherit;
			height: 100%;
			left: 0;
			opacity: 0.2;
			position: absolute;
			width: 100%;
		}
		.sl-pl-bar-counter-container {
			height: 100%;
			left: 0;
			overflow: hidden;
			position: absolute;
			top: 0;
			width: 100%;
		}
		#sl-pl-progress,
		#sl-pl-progress-view1,
		#sl-pl-progress-view2 {
			border-radius: inherit;
			height: 100%;
			overflow: hidden;
			position: relative;
			width: 100%;
		}
		#sl-pl-progress-view1 {
			transform: translateX(-100%);
			-webkit-transform: translateX(-100%);
		}
		.sl-pl-bar {
			height: 100%;
			position: absolute;
		}
		<?php
		if ( $options['bar_light'] === 'enable' ) {
			?>
			.sl-pl-light-move-bar {
				animation: sl-pl-light-move-bar-anim 1.5s linear infinite;
				-webkit-animation: sl-pl-light-move-bar-anim 1.5s linear infinite;
				height: 100%;
				position: absolute;
				text-align: initial;
				white-space: nowrap;
				width: 100%;
			}
			html[dir*="rtl"] .sl-pl-light-move-bar {
				animation: sl-pl-light-move-bar-anim-rtl 1.5s linear infinite;
				-webkit-animation: sl-pl-light-move-bar-anim-rtl 1.5s linear infinite;
			}
			.sl-pl-light-move-bar::after {
				background-image: linear-gradient(90deg, rgba(255, 255, 255, 0), rgba(255, 255, 255, 0.2) 40%, rgba(255, 255, 255, 0.3) 60%, rgba(255, 255, 255, 0));
				content: "";
				display: inline-block;
				height: 100%;
				position: absolute;
				width: 150px;
			}
			@-webkit-keyframes sl-pl-light-move-bar-anim {
				0% {
					-webkit-transform: translateX(-150px);
				}
				80%, 100% {
					-webkit-transform: translateX(100%);
				}
			}
			@keyframes sl-pl-light-move-bar-anim {
				0% {
					transform: translateX(-150px);
				}
				80%, 100% {
					transform: translateX(100%);
				}
			}
			@-webkit-keyframes sl-pl-light-move-bar-anim-rtl {
				0% {
					-webkit-transform: translateX(150px);
				}
				80%, 100% {
					-webkit-transform: translateX(-100%);
				}
			}
			@keyframes sl-pl-light-move-bar-anim-rtl {
				0% {
					transform: translateX(150px);
				}
				80%, 100% {
					transform: translateX(-100%);
				}
			}
		<?php
		}
		switch ( $options['bar_shape'] ) {
			case 'border-bar':
				?>
				#sl-pl-border-bar-container {
					border: 1px solid #000;
					box-shadow: none;
					padding: 2px;
				}
				#sl-pl-border-bar-container .sl-pl-bar-bg {
					height: calc(100% - 4px);
					left: 2px;
					width: calc(100% - 4px);
				}
				<?php
				break;
			case 'stripe-bar':
				?>
				#sl-pl-stripe-bar::before {
					background-image: linear-gradient(-45deg,rgba(255, 255, 255, 0.3) 25%, rgba(0, 0, 0, 0) 25%, rgba(0, 0, 0, 0) 50%, rgba(255, 255, 255, 0.3) 50%, rgba(255, 255, 255, 0.3) 75%, #0000 75%, rgba(0, 0, 0, 0));
					background-size: 35px 35px;
					border-radius: inherit;
					content: "";
					height: 100%;
					left: 0;
					position: absolute;
					width: 100%;
				}
				html[dir*="rtl"] #sl-pl-stripe-bar::before {
					background-image: linear-gradient(45deg,rgba(255, 255, 255, 0.3) 25%, rgba(0, 0, 0, 0) 25%, rgba(0, 0, 0, 0) 50%, rgba(255, 255, 255, 0.3) 50%, rgba(255, 255, 255, 0.3) 75%, #0000 75%, rgba(0, 0, 0, 0));
				}
				<?php
				break;
			case 'border-stripe-bar':
				?>
				#sl-pl-border-stripe-bar::before {
					background-image: linear-gradient(-45deg,rgba(255, 255, 255, 0.3) 25%, rgba(0, 0, 0, 0) 25%, rgba(0, 0, 0, 0) 50%, rgba(255, 255, 255, 0.3) 50%, rgba(255, 255, 255, 0.3) 75%, #0000 75%, rgba(0, 0, 0, 0));
					background-size: 35px 35px;
					border-radius: inherit;
					content: "";
					height: 100%;
					left: 0;
					position: absolute;
					width: 100%;
				}
				html[dir*="rtl"] #sl-pl-border-stripe-bar::before {
					background-image: linear-gradient(45deg,rgba(255, 255, 255, 0.3) 25%, rgba(0, 0, 0, 0) 25%, rgba(0, 0, 0, 0) 50%, rgba(255, 255, 255, 0.3) 50%, rgba(255, 255, 255, 0.3) 75%, #0000 75%, rgba(0, 0, 0, 0));
				}
				#sl-pl-border-stripe-bar-container {
					border: 1px solid #000;
					box-shadow: none;
					padding: 2px;
				}
				#sl-pl-border-stripe-bar-container .sl-pl-bar-bg {
					height: calc(100% - 4px);
					left: 2px;
					width: calc(100% - 4px);
				}
				<?php
				break;
			case 'anim-stripe-bar':
				?>
				#sl-pl-anim-stripe-bar::before {
					animation: sl-pl-anim-stripe-anim 0.75s linear infinite;
					-webkit-animation: sl-pl-anim-stripe-anim 0.75s linear infinite;
					background-image: linear-gradient(-45deg,rgba(255, 255, 255, 0.3) 25%, rgba(0, 0, 0, 0) 25%, rgba(0, 0, 0, 0) 50%, rgba(255, 255, 255, 0.3) 50%, rgba(255, 255, 255, 0.3) 75%, #0000 75%, rgba(0, 0, 0, 0));
					background-size: 35px 35px;
					border-radius: inherit;
					content: "";
					height: 100%;
					left: 0;
					position: absolute;
					width: calc(100% + 35px);
				}
				html[dir*="rtl"] #sl-pl-anim-stripe-bar::before {
					animation: sl-pl-anim-stripe-anim-rtl 0.75s linear infinite;
					-webkit-animation: sl-pl-anim-stripe-anim-rtl 0.75s linear infinite;
					background-image: linear-gradient(45deg,rgba(255, 255, 255, 0.3) 25%, rgba(0, 0, 0, 0) 25%, rgba(0, 0, 0, 0) 50%, rgba(255, 255, 255, 0.3) 50%, rgba(255, 255, 255, 0.3) 75%, #0000 75%, rgba(0, 0, 0, 0));
				}
				@-webkit-keyframes sl-pl-anim-stripe-anim {
					0% {
						-webkit-transform: translateX(-35px);
					}
					100% {
						-webkit-transform: translateX(0);
					}
				}
				@keyframes sl-pl-anim-stripe-anim {
					0% {
						transform: translateX(-35px);
					}
					100% {
						transform: translateX(0);
					}
				}
				@-webkit-keyframes sl-pl-anim-stripe-anim-rtl {
					0% {
						-webkit-transform: translateX(0);
					}
					100% {
						-webkit-transform: translateX(-35px);
					}
				}
				@keyframes sl-pl-anim-stripe-anim-rtl {
					0% {
						transform: translateX(0);
					}
					100% {
						transform: translateX(-35px);
					}
				}
				<?php
				break;
			case 'anim-border-stripe-bar':
				?>
				#sl-pl-anim-border-stripe-bar::before {
					animation: sl-pl-anim-stripe-anim 0.75s linear infinite;
					-webkit-animation: sl-pl-anim-stripe-anim 0.75s linear infinite;
					background-image: linear-gradient(-45deg,rgba(255, 255, 255, 0.3) 25%, rgba(0, 0, 0, 0) 25%, rgba(0, 0, 0, 0) 50%, rgba(255, 255, 255, 0.3) 50%, rgba(255, 255, 255, 0.3) 75%, #0000 75%, rgba(0, 0, 0, 0));
					background-size: 35px 35px;
					border-radius: inherit;
					content: "";
					height: 100%;
					left: 0;
					position: absolute;
					width: calc(100% + 35px);
				}
				html[dir*="rtl"] #sl-pl-anim-border-stripe-bar::before {
					animation: sl-pl-anim-stripe-anim-rtl 0.75s linear infinite;
					-webkit-animation: sl-pl-anim-stripe-anim-rtl 0.75s linear infinite;
					background-image: linear-gradient(45deg,rgba(255, 255, 255, 0.3) 25%, rgba(0, 0, 0, 0) 25%, rgba(0, 0, 0, 0) 50%, rgba(255, 255, 255, 0.3) 50%, rgba(255, 255, 255, 0.3) 75%, #0000 75%, rgba(0, 0, 0, 0));
				}
				#sl-pl-anim-border-stripe-bar-container {
					border: 1px solid #000;
					box-shadow: none;
					padding: 2px;
				}
				#sl-pl-anim-border-stripe-bar-container .sl-pl-bar-bg {
					height: calc(100% - 4px);
					left: 2px;
					width: calc(100% - 4px);
				}
				@-webkit-keyframes sl-pl-anim-stripe-anim {
					0% {
						-webkit-transform: translateX(-35px);
					}
					100% {
						-webkit-transform: translateX(0);
					}
				}
				@keyframes sl-pl-anim-stripe-anim {
					0% {
						transform: translateX(-35px);
					}
					100% {
						transform: translateX(0);
					}
				}
				@-webkit-keyframes sl-pl-anim-stripe-anim-rtl {
					0% {
						-webkit-transform: translateX(0);
					}
					100% {
						-webkit-transform: translateX(-35px);
					}
				}
				@keyframes sl-pl-anim-stripe-anim-rtl {
					0% {
						transform: translateX(0);
					}
					100% {
						transform: translateX(-35px);
					}
				}
				<?php
				break;
		}//end of switch
	}
}