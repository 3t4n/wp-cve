<?php
defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

if ( ! function_exists( 'safelayout_preloader_set_icon_group1' ) ) {

	// Return icon css
	function safelayout_preloader_set_icon_group1( $options ) {
		?>
		.sl-pl-spin-container {
			left: 50%;
			position: absolute;
			text-align: center;
			top: 50%;
			transform: translate(-50%, -50%);
			-webkit-transform: translate(-50%, -50%);
			width: 100%;
		}
		.sl-pl-spin {
			display: inline-block;
			height: 50px;
			position: relative;
			width: 50px;
		}
		.sl-pl-icon-effect {
			display: inline-block;
			height: 0;
			position: absolute;
			visibility: hidden;
			width: 0;
		}
		.sl-pl-custom {
			display: inline-block;
			height: auto;
			max-width: 100%;
		}
		<?php
		switch ( $options ) {
			case 'crawl':
				?>
				#sl-pl-crawl span {
					animation: sl-pl-crawl-anim 1.3s ease-in-out infinite;
					-webkit-animation: sl-pl-crawl-anim 1.3s ease-in-out infinite;
					border-radius: 50%;
					height: 20%;
					left: 50%;
					position: absolute;
					top: 50%;
					transform-origin: 0 50%;
					-webkit-transform-origin: 0 50%;
					width: 20%;
				}
				@-webkit-keyframes sl-pl-crawl-anim {
					0% {
						-webkit-transform: translate(-375%, -50%) scale(0.9, 1.2);
					}
					15% {
						-webkit-transform: translate(-375%, -50%) scale(2.5, 0.7);
					}
					30% {
						-webkit-transform: translate(125%, -50%) scale(2.5, 0.7);
					}
					50% {
						-webkit-transform: translate(275%, -50%) scale(0.9, 1.2);
					}
					65% {
						-webkit-transform: translate(125%, -50%) scale(2.5, 0.7);
					}
					80% {
						-webkit-transform: translate(-375%, -50%) scale(2.5, 0.7);
					}
					100% {
						-webkit-transform: translate(-375%, -50%) scale(0.9, 1.2);
					}
				}
				@keyframes sl-pl-crawl-anim {
					0% {
						transform: translate(-375%, -50%) scale(0.9, 1.2);
					}
					15% {
						transform: translate(-375%, -50%) scale(2.5, 0.7);
					}
					30% {
						transform: translate(125%, -50%) scale(2.5, 0.7);
					}
					50% {
						transform: translate(275%, -50%) scale(0.9, 1.2);
					}
					65% {
						transform: translate(125%, -50%) scale(2.5, 0.7);
					}
					80% {
						transform: translate(-375%, -50%) scale(2.5, 0.7);
					}
					100% {
						transform: translate(-375%, -50%) scale(0.9, 1.2);
					}
				}
				<?php
				break;
			case '3d-plate':
				?>
				#sl-pl-3d-plate {
					perspective: 150px;
					-webkit-perspective: 150px;
				}
				#sl-pl-3d-plate span {
					animation: sl-pl-3d-plate-anim 3s ease-in-out infinite;
					-webkit-animation: sl-pl-3d-plate-anim 3s ease-in-out infinite;
					height: 100%;
					left: 0;
					position: absolute;
					width: 100%;
				}
				@-webkit-keyframes sl-pl-3d-plate-anim {
					0% {
						-webkit-transform: rotateX(0) rotateY(0);
					}
					25% {
						-webkit-transform: rotateX(-180deg) rotateY(0);
					}
					50% {
						-webkit-transform: rotateX(-180deg) rotateY(-180deg);
					}
					75% {
						-webkit-transform: rotateX(-360deg) rotateY(-180deg);
					}
					100% {
						-webkit-transform: rotateX(-360deg) rotateY(0);
					}
				}
				@keyframes sl-pl-3d-plate-anim {
					0% {
						transform: rotateX(0) rotateY(0);
					}
					25% {
						transform: rotateX(-180deg) rotateY(0);
					}
					50% {
						transform: rotateX(-180deg) rotateY(-180deg);
					}
					75% {
						transform: rotateX(-360deg) rotateY(-180deg);
					}
					100% {
						transform: rotateX(-360deg) rotateY(0);
					}
				}
				<?php
				break;
			case 'wheel':
				?>
				#sl-pl-wheel div {
					animation: sl-pl-wheel-anim-depth 1.5s linear infinite;
					-webkit-animation: sl-pl-wheel-anim-depth 1.5s linear infinite;
					height: 100%;
					position: absolute;
					transform-origin: 50% 50% 25px;
					-webkit-transform-origin: 50% 50% 25px;
					width: 100%;
				}
				#sl-pl-wheel svg {
					animation: sl-pl-wheel-anim 1.5s linear infinite;
					-webkit-animation: sl-pl-wheel-anim 1.5s linear infinite;
					height: 160%;
					left: -30%;
					position: absolute;
					top: -30%;
					transform-origin: 50% 50% 25px;
					-webkit-transform-origin: 50% 50% 25px;
					width: 160%;
				}
				#sl-pl-wheel path{
					fill: none;
					stroke-width: 2px;
				}
				@-webkit-keyframes sl-pl-wheel-anim {
					0% {
						-webkit-transform: rotate(360deg);
					}
					100% {
						-webkit-transform: rotate(0);
					}
				}
				@keyframes sl-pl-wheel-anim {
					0% {
						transform: rotate(360deg);
					}
					100% {
						transform: rotate(0);
					}
				}
				@-webkit-keyframes sl-pl-wheel-anim-depth {
					0% {
						-webkit-transform: rotateX(15deg) rotateY(0);
					}
					100% {
						-webkit-transform: rotateX(15deg) rotateY(360deg);
					}
				}
				@keyframes sl-pl-wheel-anim-depth {
					0% {
						transform: rotateX(15deg) rotateY(0);
					}
					100% {
						transform: rotateX(15deg) rotateY(360deg);
					}
				}
				<?php
				break;
			case 'spinner':
				?>
				#sl-pl-spinner span {
					animation: sl-pl-spinner-anim 2.8s cubic-bezier(0.65, 0.05, 0.36, 1) infinite;
					-webkit-animation: sl-pl-spinner-anim 2.8s cubic-bezier(0.65, 0.05, 0.36, 1) infinite;
					height: 100%;
					left: 0;
					position: absolute;
					width: 100%;
				}
				#sl-pl-spinner span:nth-child(2) {
					animation-delay: -0.07s;
					-webkit-animation-delay: -0.07s;
					border: 1px solid yellow;
					opacity: 0.6;
				}
				@-webkit-keyframes sl-pl-spinner-anim {
					0% {
						-webkit-transform: rotate(0);
					}
					100% {
						-webkit-transform: rotate(720deg);
					}
				}
				@keyframes sl-pl-spinner-anim {
					0% {
						transform: rotate(0);
					}
					100% {
						transform: rotate(720deg);
					}
				}
				<?php
				break;
			case 'turn':
				?>
				#sl-pl-turn svg {
					height: 150%;
					left: -26%;
					position: absolute;
					top: -26%;
					width: 150%;
				}
				#sl-pl-turn circle {
					fill: none;
					stroke: #3a3aff;
					stroke-width: 4px;
				}
				#sl-pl-turn path {
					animation: sl-pl-turn-anim 1.5s cubic-bezier(0, 0.7, 1, 0.3) infinite;
					-webkit-animation: sl-pl-turn-anim 1.5s cubic-bezier(0, 0.7, 1, 0.3) infinite;
					fill: none;
					stroke-width: 4px;
					transform-origin: 50% 50%;
					-webkit-transform-origin: 50% 50%;
				}
				#sl-pl-turn #sl-pl-turn-path01 {
					animation: sl-pl-turn-anim 1.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) infinite;
					-webkit-animation: sl-pl-turn-anim 1.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) infinite;
				}
				@-webkit-keyframes sl-pl-turn-anim {
					0% {
						-webkit-transform: rotate(0);
					}
					100% {
						-webkit-transform: rotate(360deg);
					}
					30%,
					60% {
						opacity: 0.8;
					}
				}
				@keyframes sl-pl-turn-anim {
					0% {
						transform: rotate(0);
					}
					100% {
						transform: rotate(360deg);
					}
					30%,
					60% {
						opacity: 0.8;
					}
				}
				<?php
				break;
			case 'turn1':
				?>
				#sl-pl-turn1 svg {
					height: 150%;
					left: -26%;
					position: absolute;
					top: -26%;
					width: 150%;
				}
				#sl-pl-turn1 circle {
					fill: none;
					stroke: #3a3aff;
					stroke-width: 4px;
				}
				#sl-pl-turn1 path {
					animation: sl-pl-turn1-anim 1s cubic-bezier(0.58, 0.35, 0.35, 0.58) infinite;
					-webkit-animation: sl-pl-turn1-anim 1s cubic-bezier(0.58, 0.35, 0.35, 0.58) infinite;
					fill: none;
					stroke-width: 4px;
					transform-origin: 50% 50%;
					-webkit-transform-origin: 50% 50%;
				}
				#sl-pl-turn1 #sl-pl-turn1-path01 {
					display: none;
				}
				@-webkit-keyframes sl-pl-turn1-anim {
					0% {
						-webkit-transform: rotate(0);
					}
					100% {
						-webkit-transform: rotate(360deg);
					}
					30%,
					60% {
						opacity: 0.8;
					}
				}
				@keyframes sl-pl-turn1-anim {
					0% {
						transform: rotate(0);
					}
					100% {
						transform: rotate(360deg);
					}
					30%,
					60% {
						opacity: 0.8;
					}
				}
				<?php
				break;
			case 'jump':
				?>
				#sl-pl-jump span {
					animation: sl-pl-jump-anim 2.2s cubic-bezier(0.6, -0.28, 0.735, 0.045) infinite;
					-webkit-animation: sl-pl-jump-anim 2.2s cubic-bezier(0.6, -0.28, 0.735, 0.045) infinite;
					border-radius: 50%;
					height: 20%;
					left: 10%;
					position: absolute;
					top: 80%;
					width: 20%;
				}
				#sl-pl-jump span:nth-child(2) {
					animation-delay: 0.15s;
					-webkit-animation-delay: 0.15s;
					left: 40%;
				}
				#sl-pl-jump span:nth-child(3) {
					animation-delay: 0.3s;
					-webkit-animation-delay: 0.3s;
					left: 70%;
				}
				@-webkit-keyframes sl-pl-jump-anim {
					0% {
						-webkit-transform: translateY(0);
					}
					15% {
						-webkit-transform: translateY(-400%);
					}
					30% {
						-webkit-transform: translateY(0);
					}
					37% {
						-webkit-transform: translateY(-70%);
					}
					44% {
						-webkit-transform: translateY(0);
					}
					49% {
						-webkit-transform: translateY(-30%);
					}
					54% {
						-webkit-transform: translateY(0);
					}
					59% {
						-webkit-transform: translateY(-20%);
					}
					64% {
						-webkit-transform: translateY(0);
					}
					100% {
						-webkit-transform: translateY(0);
					}
				}
				@keyframes sl-pl-jump-anim {
					0% {
						transform: translateY(0);
					}
					15% {
						transform: translateY(-400%);
					}
					30% {
						transform: translateY(0);
					}
					37% {
						transform: translateY(-70%);
					}
					44% {
						transform: translateY(0);
					}
					49% {
						transform: translateY(-30%);
					}
					54% {
						transform: translateY(0);
					}
					59% {
						transform: translateY(-20%);
					}
					64% {
						transform: translateY(0);
					}
					100% {
						transform: translateY(0);
					}
				}
				<?php
				break;
			case 'infinite':
				?>
				#sl-pl-infinite span {
					animation: sl-pl-infinite-anim 1.2s linear infinite;
					-webkit-animation: sl-pl-infinite-anim 1.2s linear infinite;
					border-radius: 50%;
					height: 16%;
					left: 50%;
					position: absolute;
					top: 50%;
					width: 16%;
				}
				#sl-pl-infinite span:nth-child(2) {
					animation-delay: -0.15s;
					-webkit-animation-delay: -0.15s;
				}
				#sl-pl-infinite span:nth-child(3) {
					animation-delay: -0.3s;
					-webkit-animation-delay: -0.3s;
				}
				@-webkit-keyframes sl-pl-infinite-anim {
					0% {
						-webkit-transform-origin: -180% 50%;
						-webkit-transform: translate(-75%, -50%) rotate(10deg);
					}
					7% {
						-webkit-transform-origin: -180% 50%;
						-webkit-transform: translate(-75%, -50%) rotate(105deg);
					}
					20% {
						-webkit-transform-origin: -180% 50%;
						-webkit-transform: translate(-75%, -50%) rotate(165deg);
					}
					40% {
						-webkit-transform-origin: -180% 50%;
						-webkit-transform: translate(-75%, -50%) rotate(245deg);
					}
					45% {
						-webkit-transform-origin: -180% 50%;
						-webkit-transform: translate(-75%, -50%) rotate(305deg);
					}
					50% {
						-webkit-transform-origin: -180% 50%;
						-webkit-transform: translate(-75%, -50%) rotate(360deg);
					}
					50.1% {
						-webkit-transform-origin: 280% 50%;
						-webkit-transform: translate(-25%, -50%) rotate(360deg);
					}
					57% {
						-webkit-transform-origin: 280% 50%;
						-webkit-transform: translate(-25%, -50%) rotate(245deg);
					}
					70% {
						-webkit-transform-origin: 280% 50%;
						-webkit-transform: translate(-25%, -50%) rotate(165deg);
					}
					80% {
						-webkit-transform-origin: 280% 50%;
						-webkit-transform: translate(-25%, -50%) rotate(105deg);
					}
					90% {
						-webkit-transform-origin: 280% 50%;
						-webkit-transform: translate(-25%, -50%) rotate(65deg);
					}
					100% {
						-webkit-transform-origin: 280% 50%;
						-webkit-transform: translate(-25%, -50%) rotate(10deg);
					}
				}
				@keyframes sl-pl-infinite-anim {
					0% {
						transform-origin: -180% 50%;
						transform: translate(-75%, -50%) rotate(10deg);
					}
					7% {
						transform-origin: -180% 50%;
						transform: translate(-75%, -50%) rotate(105deg);
					}
					20% {
						transform-origin: -180% 50%;
						transform: translate(-75%, -50%) rotate(165deg);
					}
					40% {
						transform-origin: -180% 50%;
						transform: translate(-75%, -50%) rotate(245deg);
					}
					45% {
						transform-origin: -180% 50%;
						transform: translate(-75%, -50%) rotate(305deg);
					}
					50% {
						transform-origin: -180% 50%;
						transform: translate(-75%, -50%) rotate(360deg);
					}
					50.1% {
						transform-origin: 280% 50%;
						transform: translate(-25%, -50%) rotate(360deg);
					}
					57% {
						transform-origin: 280% 50%;
						transform: translate(-25%, -50%) rotate(245deg);
					}
					70% {
						transform-origin: 280% 50%;
						transform: translate(-25%, -50%) rotate(165deg);
					}
					80% {
						transform-origin: 280% 50%;
						transform: translate(-25%, -50%) rotate(105deg);
					}
					90% {
						transform-origin: 280% 50%;
						transform: translate(-25%, -50%) rotate(65deg);
					}
					100% {
						transform-origin: 280% 50%;
						transform: translate(-25%, -50%) rotate(10deg);
					}
				}
				<?php
				break;
			case 'blade-vertical':
				?>
				#sl-pl-blade-vertical span {
					animation: sl-pl-blade-vertical-anim 1.2s ease-in-out infinite;
					-webkit-animation: sl-pl-blade-vertical-anim 1.2s ease-in-out infinite;
					height: 100%;
					position: absolute;
					width: 12%;
				}
				#sl-pl-blade-vertical span:nth-child(1) {
					left: 0;
				}
				#sl-pl-blade-vertical span:nth-child(2) {
					animation-delay: -0.15s;
					-webkit-animation-delay: -0.15s;
					left: 20%;
				}
				#sl-pl-blade-vertical span:nth-child(3) {
					animation-delay: -0.3s;
					-webkit-animation-delay: -0.3s;
					left: 40%;
				}
				#sl-pl-blade-vertical span:nth-child(4) {
					animation-delay: -0.45s;
					-webkit-animation-delay: -0.45s;
					left: 60%;
				}
				#sl-pl-blade-vertical span:nth-child(5) {
					animation-delay: -0.6s;
					-webkit-animation-delay: -0.6s;
					left: 80%;
				}
				@-webkit-keyframes sl-pl-blade-vertical-anim {
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
				@keyframes sl-pl-blade-vertical-anim {
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
			case 'blade-vertical1':
				?>
				#sl-pl-blade-vertical1 span {
					animation: sl-pl-blade-vertical1-anim 1.2s ease-in-out infinite;
					-webkit-animation: sl-pl-blade-vertical1-anim 1.2s ease-in-out infinite;
					height: 100%;
					position: absolute;
					width: 12%;
				}
				#sl-pl-blade-vertical1 span:nth-child(1) {
					animation-delay: -0.5s;
					-webkit-animation-delay: -0.5s;
					left: 0;
				}
				#sl-pl-blade-vertical1 span:nth-child(2) {
					animation-delay: -0.4s;
					-webkit-animation-delay: -0.4s;
					left: 20%;
				}
				#sl-pl-blade-vertical1 span:nth-child(3) {
					animation-delay: -0.3s;
					-webkit-animation-delay: -0.3s;
					left: 40%;
				}
				#sl-pl-blade-vertical1 span:nth-child(4) {
					animation-delay: -0.2s;
					-webkit-animation-delay: -0.2s;
					left: 60%;
				}
				#sl-pl-blade-vertical1 span:nth-child(5) {
					animation-delay: -0.1s;
					-webkit-animation-delay: -0.1s;
					left: 80%;
				}
				@-webkit-keyframes sl-pl-blade-vertical1-anim {
					0%, 40%, 100% {
						-webkit-transform: scaleY(0.4);
					}
					20% {
						-webkit-transform: scaleY(1);
					}
				}
				@keyframes sl-pl-blade-vertical1-anim {
					0%, 40%, 100% {
						transform: scaleY(0.4);
					}
					20% {
						transform: scaleY(1);
					}
				}
				<?php
				break;
			case '3d-square':
				?>
				#sl-pl-3d-square div {
					height: 10px;
					left: -4px;
					perspective: 85px;
					-webkit-perspective: 85px;
					position: absolute;
					top: 20px;
					width: 10px;
				}
				#sl-pl-3d-square div:nth-child(2) {
					left: 12px;
				}
				#sl-pl-3d-square div:nth-child(3) {
					left: 28px;
				}
				#sl-pl-3d-square div:nth-child(4) {
					left: 44px;
				}
				#sl-pl-3d-square span {
					animation: sl-pl-3d-square-anim 1.5s ease-in-out infinite;
					-webkit-animation: sl-pl-3d-square-anim 1.5s ease-in-out infinite;
					height: 100%;
					left: 0;
					position: absolute;
					transform-origin: 50% 50% 19px;
					-webkit-transform-origin: 50% 50% 19px;
					width: 100%;
				}
				#sl-pl-3d-square div span:nth-child(1) {
					backface-visibility: hidden;
					-webkit-backface-visibility: hidden;
					z-index: 1;
				}
				#sl-pl-3d-square div:nth-child(1) span:nth-child(2) {
					background: #8ee4ff !important;
				}
				#sl-pl-3d-square div:nth-child(2) span {
					animation-delay: -0.12s;
					-webkit-animation-delay: -0.12s;
				}
				#sl-pl-3d-square div:nth-child(2) span:nth-child(2) {
					background: #ffc107 !important;
				}
				#sl-pl-3d-square div:nth-child(3) span {
					animation-delay: -0.24s;
					-webkit-animation-delay: -0.24s;
				}
				#sl-pl-3d-square div:nth-child(3) span:nth-child(2) {
					background: #8bc34a !important;
				}
				#sl-pl-3d-square div:nth-child(4) span {
					animation-delay: -0.36s;
					-webkit-animation-delay: -0.36s;
				}
				#sl-pl-3d-square div:nth-child(4) span:nth-child(2) {
					background: #ff5722 !important;
				}
				@-webkit-keyframes sl-pl-3d-square-anim {
					0% {
						-webkit-transform: rotate3d(-1, -0.06, 0, 0);
					}
					100% {
						-webkit-transform: rotate3d(-1, -0.06, 0, -360deg);
					}
				}
				@keyframes sl-pl-3d-square-anim {
					0% {
						transform: rotate3d(-1, -0.06, 0, 0);
					}
					100% {
						transform: rotate3d(-1, -0.06, 0, -360deg);
					}
				}
				<?php
				break;
			case 'flight':
				?>
				#sl-pl-flight span {
					animation: sl-pl-flight-anim 1.7s ease-in-out infinite, sl-pl-flight-anim-shadow 1.7s ease-in-out infinite;
					-webkit-animation: sl-pl-flight-anim 1.7s ease-in-out infinite, sl-pl-flight-anim-shadow 1.7s ease-in-out infinite;
					height: 8px;
					left: -11px;
					position: absolute;
					top: 8px;
					width: 12px;
				}
				#sl-pl-flight span:nth-child(2) {
					animation-delay: -0.12s;
					-webkit-animation-delay: -0.12s;
					left: 4px;
				}
				#sl-pl-flight span:nth-child(3) {
					animation-delay: -0.24s;
					-webkit-animation-delay: -0.24s;
					left: 19px;
				}
				#sl-pl-flight span:nth-child(4) {
					animation-delay: -0.12s;
					-webkit-animation-delay: -0.12s;
					left: 34px;
				}
				#sl-pl-flight span:nth-child(5) {
					left: 49px;
				}
				@-webkit-keyframes sl-pl-flight-anim {
					50% {
						-webkit-transform: translate(0, 25px);
					}
				}
				@keyframes sl-pl-flight-anim {
					50% {
						transform: translate(0, 25px);
					}
				}
				@-webkit-keyframes sl-pl-flight-anim-shadow {
					35% {
						box-shadow: 0 -1px #ffeb3b, 0 -3px #ffa31a, 0 -6px #54bb57, 0 -9px 0.5px -0.2px #ff3377, 0 -12px 1px -0.2px #bd2fd6;
					}
					75% {
						box-shadow: 0 1px #ffeb3b, 0 3px #ffa31a, 0 6px #54bb57, 0 9px 0.5px -0.2px #ff3377, 0 12px 1px -0.2px #bd2fd6;
					}
				}
				@keyframes sl-pl-flight-anim-shadow {
					35% {
						box-shadow: 0 -1px #ffeb3b, 0 -3px #ffa31a, 0 -6px #54bb57, 0 -9px 0.5px -0.2px #ff3377, 0 -12px 1px -0.2px #bd2fd6;
					}
					75% {
						box-shadow: 0 1px #ffeb3b, 0 3px #ffa31a, 0 6px #54bb57, 0 9px 0.5px -0.2px #ff3377, 0 12px 1px -0.2px #bd2fd6;
					}
				}
				<?php
				break;
			case 'fold':
				?>
				#sl-pl-fold div {
					height: 100%;
					position: absolute;
					transform: rotate(45deg);
					-webkit-transform: rotate(45deg);
					width: 100%;
				}
				#sl-pl-fold span {
					background-clip: text !important;
					-webkit-background-clip: text !important;
					float: left;
					height: 50%;
					perspective: 190px;
					-webkit-perspective: 190px;
					position: relative;
					width: 50%;
				}
				#sl-pl-fold span::before,
				#sl-pl-fold span::after {
					animation: sl-pl-fold-anim 2.4s linear infinite, sl-pl-fold-anim-visible 4.8s steps(1, end) infinite;
					-webkit-animation: sl-pl-fold-anim 2.4s linear infinite, sl-pl-fold-anim-visible 4.8s steps(1, end) infinite;
					background-color: inherit;
					background-image: inherit;
					content: '';
					height: 96%;
					left: 0;
					opacity: 0;
					position: absolute;
					transform-origin: 100% 100%;
					-webkit-transform-origin: 100% 100%;
					width: 100%;
				}
				#sl-pl-fold span::before {
					animation-delay: 0s, 2.4s;
					-webkit-animation-delay: 0s, 2.4s;
					background: #00bfff;
					border: 1px solid #007399;
					box-sizing: border-box;
				}
				#sl-pl-fold span:nth-child(2){
					transform: rotate(90deg);
					-webkit-transform: rotate(90deg);
				}
				#sl-pl-fold span:nth-child(3){
					transform: rotate(270deg);
					-webkit-transform: rotate(270deg);
				}
				#sl-pl-fold span:nth-child(4){
					transform: rotate(180deg);
					-webkit-transform: rotate(180deg);
				}
				#sl-pl-fold span:nth-child(2):after {
					animation-delay: 0.3s;
					-webkit-animation-delay: 0.3s;
				}
				#sl-pl-fold span:nth-child(2):before {
					animation-delay: 0.3s, 2.7s;
					-webkit-animation-delay: 0.3s, 2.7s;
					background: #ff8000;
					border-color: #994d00;
				}
				#sl-pl-fold span:nth-child(3):after {
					animation-delay: 0.9s;
					-webkit-animation-delay: 0.9s;
				}
				#sl-pl-fold span:nth-child(3):before {
					animation-delay: 0.9s, 3.3s;
					-webkit-animation-delay: 0.9s, 3.3s;
					background: #ffcc80;
					border-color: #bd7100;
				}
				#sl-pl-fold span:nth-child(4):after {
					animation-delay: 0.6s;
					-webkit-animation-delay: 0.6s;
				}
				#sl-pl-fold span:nth-child(4):before {
					animation-delay: 0.6s, 3s;
					-webkit-animation-delay: 0.6s, 3s;
					background: #c2c2a3;
					border-color: #7a7a52;
				}
				@-webkit-keyframes sl-pl-fold-anim {
					0%, 10% {
						opacity: 0;
						-webkit-transform: rotateX(-180deg);
					}
					25%, 75% {
						opacity: 1;
						-webkit-transform: rotateX(0);
					}
					90%, 100% {
						opacity: 0;
						-webkit-transform: rotateY(180deg);
					}
				}
				@keyframes sl-pl-fold-anim {
					0%, 10% {
						opacity: 0;
						transform: rotateX(-180deg);
					}
					25%, 75% {
						opacity: 1;
						transform: rotateX(0);
					}
					90%, 100% {
						opacity: 0;
						transform: rotateY(180deg);
					}
				}
				@-webkit-keyframes sl-pl-fold-anim-visible {
					50% {
						opacity: 0;
					}
				}
				@keyframes sl-pl-fold-anim-visible {
					50% {
						opacity: 0;
					}
				}
				<?php
				break;
			case 'triple-spinner':
				?>
				#sl-pl-triple-spinner svg {
					animation: sl-pl-triple-spinner-anim 2.7s cubic-bezier(0.59, 0.42, 0.24, 0.47) -1.62s infinite;
					-webkit-animation: sl-pl-triple-spinner-anim 2.7s cubic-bezier(0.59, 0.42, 0.24, 0.47) -1.62s infinite;
					height: 100%;
					left: 0;
					position: absolute;
					top: 0;
					width: 100%;
				}
				#sl-pl-t-s-path {
					animation: sl-pl-triple-spinner-anim-move 5.4s linear infinite;
					-webkit-animation: sl-pl-triple-spinner-anim-move 5.4s linear infinite;
				}
				#sl-pl-t-s1,
				#sl-pl-t-s2 {
					transform: rotate(120deg);
					-webkit-transform: rotate(120deg);
					transform-origin: 50% 50%;
					-webkit-transform-origin: 50% 50%;
				}
				#sl-pl-t-s2 {
					transform: rotate(240deg);
					-webkit-transform: rotate(240deg);
				}
				@-webkit-keyframes sl-pl-triple-spinner-anim {
					0% {
						-webkit-transform: rotate(0);
					}
					100% {
						-webkit-transform: rotate(1080deg);
					}
				}
				@keyframes sl-pl-triple-spinner-anim {
					0% {
						transform: rotate(0);
					}
					100% {
						transform: rotate(1080deg);
					}
				}
				@-webkit-keyframes sl-pl-triple-spinner-anim-move {
					40% {
						-webkit-transform: translate(0, 4px);
					}
					20%, 60% {
						-webkit-transform: translate(0, 0);
					}
				}
				@keyframes sl-pl-triple-spinner-anim-move {
					40% {
						transform: translate(0, 4px);
					}
					20%, 60% {
						transform: translate(0, 0);
					}
				}
				<?php
				break;
		}//end of switch
	}
}