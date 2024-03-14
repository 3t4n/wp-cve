<?php
defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

if ( ! function_exists( 'safelayout_preloader_set_icon_group2' ) ) {

	// Return icon css
	function safelayout_preloader_set_icon_group2( $options ) {
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
			case 'balloons':
				?>
				#sl-pl-balloons span {
					animation: sl-pl-balloons-anim 1.2s linear infinite;
					-webkit-animation: sl-pl-balloons-anim 1.2s linear infinite;
					border-radius: 50%;
					height: 30%;
					left: 0;
					position: absolute;
					top: 35%;
					width: 30%;
				}
				#sl-pl-balloons span:nth-child(2) {
					animation-delay: -0.3s;
					-webkit-animation-delay: -0.3s;
					left: 35%;
				}
				#sl-pl-balloons span:nth-child(3) {
					animation-delay: -0.6s;
					-webkit-animation-delay: -0.6s;
					left: 70%;
				}
				@-webkit-keyframes sl-pl-balloons-anim {
					50% {
						opacity: 0.3;
						-webkit-transform: scale(0.3);
					}
					0%,
					100% {
						-webkit-transform: scale(1);
					}
				}
				@keyframes sl-pl-balloons-anim {
					50% {
						opacity: 0.3;
						transform: scale(0.3);
					}
					0%,
					100% {
						transform: scale(1);
					}
				}
				<?php
				break;
			case '3d-bar':
				?>
				#sl-pl-3d-bar div {
					height: 44%;
					left: 44%;
					perspective: 85px;
					-webkit-perspective: 85px;
					position: absolute;
					top: 50%;
					width: 12%;
				}
				#sl-pl-3d-bar span {
					animation: sl-pl-3d-bar-anim 1.5s linear infinite;
					-webkit-animation: sl-pl-3d-bar-anim 1.5s linear infinite;
					height: 100%;
					left: 0;
					position: absolute;
					transform-origin: 50% 50% 25px;
					-webkit-transform-origin: 50% 50% 25px;
					width: 100%;
				}
				#sl-pl-3d-bar span:after {
					animation: sl-pl-3d-bar-anim-visible 1.5s steps(1, end) -0.375s infinite;
					-webkit-animation: sl-pl-3d-bar-anim-visible 1.5s steps(1, end) -0.375s infinite;
					background: #8ee4ff;
					content: '';
					height: 100%;
					left: 0;
					position: absolute;
					width: 100%;
				}
				#sl-pl-3d-bar div:nth-child(2) span {
					animation-delay: -0.12s;
					-webkit-animation-delay: -0.12s;
				}
				#sl-pl-3d-bar div:nth-child(2) span:after {
					animation-delay: -0.495s;
					-webkit-animation-delay: -0.495s;
					background: #ffc107;
				}
				#sl-pl-3d-bar div:nth-child(3) span {
					animation-delay: -0.24s;
					-webkit-animation-delay: -0.24s;
				}
				#sl-pl-3d-bar div:nth-child(3) span:after {
					animation-delay: -0.615s;
					-webkit-animation-delay: -0.615s;
					background: #8bc34a;
				}
				#sl-pl-3d-bar div:nth-child(4) span {
					animation-delay: -0.36s;
					-webkit-animation-delay: -0.36s;
				}
				#sl-pl-3d-bar div:nth-child(4) span:after {
					animation-delay: -0.735s;
					-webkit-animation-delay: -0.735s;
					background: #ff5722;
				}
				#sl-pl-3d-bar div:nth-child(5) span {
					animation-delay: -0.48s;
					-webkit-animation-delay: -0.48s;
				}
				#sl-pl-3d-bar div:nth-child(5) span:after {
					animation-delay: -0.855s;
					-webkit-animation-delay: -0.855s;
					background: #0d8bf2;
				}
				@-webkit-keyframes sl-pl-3d-bar-anim {
					0% {
						-webkit-transform: rotateX(15deg) rotateY(0);
					}
					100% {
						-webkit-transform: rotateX(15deg) rotateY(360deg);
					}
				}
				@keyframes sl-pl-3d-bar-anim {
					0% {
						transform: rotateX(15deg) rotateY(0);
					}
					100% {
						transform: rotateX(15deg) rotateY(360deg);
					}
				}
				@-webkit-keyframes sl-pl-3d-bar-anim-visible {
					0%, 100% {
						opacity: 1;
					}
					50% {
						opacity: 0;
					}
				}
				@keyframes sl-pl-3d-bar-anim-visible {
					0%, 100% {
						opacity: 1;
					}
					50% {
						opacity: 0;
					}
				}
				<?php
				break;
			case 'gear':
				?>
				#sl-pl-gear span {
					animation: sl-pl-gear-anim 1.2s steps(12, end) infinite;
					-webkit-animation: sl-pl-gear-anim 1.2s steps(12, end) infinite;
					border-radius: 50%;
					height: 25%;
					left: 47%;
					position: absolute;
					transform-origin: 50% 200%;
					-webkit-transform-origin: 50% 200%;
					width: 6%;
				}
				#sl-pl-gear span:nth-child(2) {
					animation-delay: -0.1s;
					-webkit-animation-delay: -0.1s;
					opacity: 0.1;
				}
				#sl-pl-gear span:nth-child(3) {
					animation-delay: -0.2s;
					-webkit-animation-delay: -0.2s;
					opacity: 0.1;
				}
				#sl-pl-gear span:nth-child(4) {
					animation-delay: -0.3s;
					-webkit-animation-delay: -0.3s;
					opacity: 0.2;
				}
				#sl-pl-gear span:nth-child(5) {
					animation-delay: -0.4s;
					-webkit-animation-delay: -0.4s;
					opacity: 0.3;
				}
				#sl-pl-gear span:nth-child(6) {
					animation-delay: -0.5s;
					-webkit-animation-delay: -0.5s;
					opacity: 0.4;
				}
				#sl-pl-gear span:nth-child(7) {
					animation-delay: -0.6s;
					-webkit-animation-delay: -0.6s;
					opacity: 0.5;
				}
				#sl-pl-gear span:nth-child(8) {
					animation-delay: -0.7s;
					-webkit-animation-delay: -0.7s;
					opacity: 0.6;
				}
				#sl-pl-gear span:nth-child(9) {
					animation-delay: -0.8s;
					-webkit-animation-delay: -0.8s;
					opacity: 0.7;
				}
				#sl-pl-gear span:nth-child(10) {
					animation-delay: -0.9s;
					-webkit-animation-delay: -0.9s;
					opacity: 0.8;
				}
				#sl-pl-gear span:nth-child(11) {
					animation-delay: -1s;
					-webkit-animation-delay: -1s;
					opacity: 0.9;
				}
				#sl-pl-gear span:nth-child(12) {
					animation-delay: -1.1s;
					-webkit-animation-delay: -1.1s;
					opacity: 0.95;
				}
				@-webkit-keyframes sl-pl-gear-anim {
					0% {
						-webkit-transform: rotate(0);
					}
					100% {
						-webkit-transform: rotate(360deg);
					}
				}
				@keyframes sl-pl-gear-anim {
					0% {
						transform: rotate(0);
					}
					100% {
						transform: rotate(360deg);
					}
				}
				<?php
				break;
			case 'trail':
				?>
				#sl-pl-trail span {
					animation: sl-pl-trail-anim 1.2s steps(12, end) infinite;
					-webkit-animation: sl-pl-trail-anim 1.2s steps(12, end) infinite;
					border-radius: 50%;
					height: 16%;
					left: 42%;
					position: absolute;
					transform-origin: 50% 310%;
					-webkit-transform-origin: 50% 310%;
					width: 16%;
				}
				#sl-pl-trail span:nth-child(2) {
					animation-delay: -0.1s;
					-webkit-animation-delay: -0.1s;
					opacity: 0.1;
				}
				#sl-pl-trail span:nth-child(3) {
					animation-delay: -0.2s;
					-webkit-animation-delay: -0.2s;
					opacity: 0.1;
				}
				#sl-pl-trail span:nth-child(4) {
					animation-delay: -0.3s;
					-webkit-animation-delay: -0.3s;
					opacity: 0.2;
				}
				#sl-pl-trail span:nth-child(5) {
					animation-delay: -0.4s;
					-webkit-animation-delay: -0.4s;
					opacity: 0.3;
				}
				#sl-pl-trail span:nth-child(6) {
					animation-delay: -0.5s;
					-webkit-animation-delay: -0.5s;
					opacity: 0.4;
				}
				#sl-pl-trail span:nth-child(7) {
					animation-delay: -0.6s;
					-webkit-animation-delay: -0.6s;
					opacity: 0.5;
				}
				#sl-pl-trail span:nth-child(8) {
					animation-delay: -0.7s;
					-webkit-animation-delay: -0.7s;
					opacity: 0.6;
				}
				#sl-pl-trail span:nth-child(9) {
					animation-delay: -0.8s;
					-webkit-animation-delay: -0.8s;
					opacity: 0.7;
				}
				#sl-pl-trail span:nth-child(10) {
					animation-delay: -0.9s;
					-webkit-animation-delay: -0.9s;
					opacity: 0.8;
				}
				#sl-pl-trail span:nth-child(11) {
					animation-delay: -1s;
					-webkit-animation-delay: -1s;
					opacity: 0.9;
				}
				#sl-pl-trail span:nth-child(12) {
					animation-delay: -1.1s;
					-webkit-animation-delay: -1.1s;
					opacity: 0.95;
				}
				@-webkit-keyframes sl-pl-trail-anim {
					0% {
						-webkit-transform: rotate(0);
					}
					100% {
						-webkit-transform: rotate(360deg);
					}
				}
				@keyframes sl-pl-trail-anim {
					0% {
						transform: rotate(0);
					}
					100% {
						transform: rotate(360deg);
					}
				}
				<?php
				break;
			case 'bubble':
				?>
				#sl-pl-bubble svg {
					height: 140%;
					left: -20%;
					position: absolute;
					top: -20%;
					width: 140%;
				}
				#sl-pl-bubble circle {
					animation: sl-pl-bubble-anim 1.4s cubic-bezier(0.215, 0.61, 0.355, 1) infinite;
					-webkit-animation: sl-pl-bubble-anim 1.4s cubic-bezier(0.215, 0.61, 0.355, 1) infinite;
					fill: none;
					stroke-width: 5px;
					transform-origin: 50% 50%;
					-webkit-transform-origin: 50% 50%;
				}
				#sl-pl-bubble circle:nth-child(2) {
					animation-delay: 0.5s;
					-webkit-animation-delay: 0.5s;
					transform: scale(0);
					-webkit-transform: scale(0);
				}
				@-webkit-keyframes sl-pl-bubble-anim {
					0% {
						opacity: 1;
						stroke-width: 5px;
						-webkit-transform: scale(0);
					}
					30% {
						opacity: 1;
						stroke-width: 5px;
					}
					100% {
						opacity: 0.5;
						stroke-width: 0.1px;
						-webkit-transform: scale(1);
					}
				}
				@keyframes sl-pl-bubble-anim {
					0% {
						opacity: 1;
						stroke-width: 5px;
						transform: scale(0);
					}
					30% {
						opacity: 1;
						stroke-width: 5px;
					}
					100% {
						opacity: 0.5;
						stroke-width: 0.1px;
						transform: scale(1);
					}
				}
				<?php
				break;
			case 'bubble1':
				?>
				#sl-pl-bubble1 span {
					animation: sl-pl-bubble1-anim 1.4s cubic-bezier(0.215, 0.61, 0.355, 1) infinite;
					-webkit-animation: sl-pl-bubble1-anim 1.4s cubic-bezier(0.215, 0.61, 0.355, 1) infinite;
					border-radius: 50%;
					height: 100%;
					left: 0;
					position: absolute;
					transform: scale(0);
					-webkit-transform: scale(0);
					width: 100%;
				}
				#sl-pl-bubble1 span:nth-child(2) {
					animation-delay: 0.5s;
					-webkit-animation-delay: 0.5s;
				}
				@-webkit-keyframes sl-pl-bubble1-anim {
					0% {
						opacity: 1;
						-webkit-transform: scale(0);
					}
					20% {
						opacity: 1;
					}
					100% {
						opacity: 0;
						-webkit-transform: scale(1);
					}
				}
				@keyframes sl-pl-bubble1-anim {
					0% {
						opacity: 1;
						transform: scale(0);
					}
					20% {
						opacity: 1;
					}
					100% {
						opacity: 0;
						transform: scale(1);
					}
				}
				<?php
				break;
			case 'blade-horizontal':
				?>
				#sl-pl-blade-horizontal div {
					height: 12%;
					perspective: 40px;
					-webkit-perspective: 40px;
					position: absolute;
					width: 100%;
				}
				#sl-pl-blade-horizontal span {
					animation: sl-pl-blade-horizontal-anim 1.8s linear infinite;
					-webkit-animation: sl-pl-blade-horizontal-anim 1.8s linear infinite;
					height: 100%;
					left: 0;
					position: absolute;
					width: 100%;
				}
				#sl-pl-blade-horizontal span:before {
					animation: sl-pl-blade-horizontal-anim-visible 1.8s steps(1, end) -0.45s infinite;
					-webkit-animation: sl-pl-blade-horizontal-anim-visible 1.8s steps(1, end) -0.45s infinite;
					background: #34a744;
					content: '';
					height: 100%;
					left: 0;
					position: absolute;
					width: 100%;
					z-index: 1;
				}
				#sl-pl-blade-horizontal div:nth-child(1) {
					top: 6%;
				}
				#sl-pl-blade-horizontal div:nth-child(2) {
					top: 25%;
				}
				#sl-pl-blade-horizontal div:nth-child(3) {
					top: 44%;
				}
				#sl-pl-blade-horizontal div:nth-child(4) {
					top: 63%;
				}
				#sl-pl-blade-horizontal div:nth-child(5) {
					top: 82%;
				}
				#sl-pl-blade-horizontal div:nth-child(2) span {
					animation-delay: -0.15s;
					-webkit-animation-delay: -0.15s;
				}
				#sl-pl-blade-horizontal div:nth-child(2) span:before {
					animation-delay: -0.6s;
					-webkit-animation-delay: -0.6s;
					background: #f27373;
				}
				#sl-pl-blade-horizontal div:nth-child(3) span {
					animation-delay: -0.3s;
					-webkit-animation-delay: -0.3s;
				}
				#sl-pl-blade-horizontal div:nth-child(3) span:before {
					animation-delay: -0.75s;
					-webkit-animation-delay: -0.75s;
					background: #93cb52;
				}
				#sl-pl-blade-horizontal div:nth-child(4) span {
					animation-delay: -0.45s;
					-webkit-animation-delay: -0.45s;
				}
				#sl-pl-blade-horizontal div:nth-child(4) span:before {
					animation-delay: -0.9s;
					-webkit-animation-delay: -0.9s;
					background: #ffce14;
				}
				#sl-pl-blade-horizontal div:nth-child(5) span {
					animation-delay: -0.6s;
					-webkit-animation-delay: -0.6s;
				}
				#sl-pl-blade-horizontal div:nth-child(5) span:before {
					animation-delay: -1.05s;
					-webkit-animation-delay: -1.05s;
					background: #ffff70;
				}
				@-webkit-keyframes sl-pl-blade-horizontal-anim {
					0% {
						-webkit-transform: rotateY(0);
					}
					100% {
						-webkit-transform: rotateY(360deg);
					}
				}
				@keyframes sl-pl-blade-horizontal-anim {
					0% {
						transform: rotateY(0);
					}
					100% {
						transform: rotateY(360deg);
					}
				}
				@-webkit-keyframes sl-pl-blade-horizontal-anim-visible {
					0%, 100% {
						opacity: 1;
					}
					50% {
						opacity: 0;
					}
				}
				@keyframes sl-pl-blade-horizontal-anim-visible {
					0%, 100% {
						opacity: 1;
					}
					50% {
						opacity: 0;
					}
				}
				<?php
				break;
			case 'blade-horizontal1':
				?>
				#sl-pl-blade-horizontal1 div {
					height: 12%;
					perspective: 40px;
					-webkit-perspective: 40px;
					position: absolute;
					width: 100%;
				}
				#sl-pl-blade-horizontal1 span {
					animation: sl-pl-blade-horizontal1-anim 2.4s linear infinite;
					-webkit-animation: sl-pl-blade-horizontal1-anim 2.4s linear infinite;
					height: 100%;
					left: 0;
					overflow: hidden;
					position: absolute;
					width: 100%;
				}
				#sl-pl-blade-horizontal1 span:before {
					animation: sl-pl-blade-horizontal1-anim-visible 2.4s steps(1, end) -0.6s infinite;
					-webkit-animation: sl-pl-blade-horizontal1-anim-visible 2.4s steps(1, end) -0.6s infinite;
					background: #34a744;
					content: '';
					height: 100%;
					left: 0;
					position: absolute;
					width: 100%;
					z-index: 1;
				}
				#sl-pl-blade-horizontal1 span::after {
					animation: sl-pl-blade-horizontal1-anim-shadow 2.4s cubic-bezier(0.22, 0.61, 0.36, 1) infinite;
					-webkit-animation: sl-pl-blade-horizontal1-anim-shadow 2.4s cubic-bezier(0.22, 0.61, 0.36, 1) infinite;
					background-color: #000;
					content: "";
					height: 100%;
					left: 0;
					position: absolute;
					top: -100%;
					width: 5%;
					z-index: 1;
				}
				#sl-pl-blade-horizontal1 div:nth-child(1) {
					top: 6%;
				}
				#sl-pl-blade-horizontal1 div:nth-child(2) {
					top: 25%;
				}
				#sl-pl-blade-horizontal1 div:nth-child(3) {
					top: 44%;
				}
				#sl-pl-blade-horizontal1 div:nth-child(4) {
					top: 63%;
				}
				#sl-pl-blade-horizontal1 div:nth-child(5) {
					top: 82%;
				}
				#sl-pl-blade-horizontal1 div:nth-child(2) span {
					animation-delay: -0.15s;
					-webkit-animation-delay: -0.15s;
				}
				#sl-pl-blade-horizontal1 div:nth-child(3) span {
					animation-delay: -0.3s;
					-webkit-animation-delay: -0.3s;
				}
				#sl-pl-blade-horizontal1 div:nth-child(4) span {
					animation-delay: -0.45s;
					-webkit-animation-delay: -0.45s;
				}
				#sl-pl-blade-horizontal1 div:nth-child(5) span {
					animation-delay: -0.6s;
					-webkit-animation-delay: -0.6s;
				}
				#sl-pl-blade-horizontal1 div:nth-child(1) span:after {
					animation-delay: -0.3s;
					-webkit-animation-delay: -0.3s;
				}
				#sl-pl-blade-horizontal1 div:nth-child(2) span:before {
					animation-delay: -0.75s;
					-webkit-animation-delay: -0.75s;
					background: #f27373;
				}
				#sl-pl-blade-horizontal1 div:nth-child(2) span:after {
					animation-delay: -0.45s;
					-webkit-animation-delay: -0.45s;
				}
				#sl-pl-blade-horizontal1 div:nth-child(3) span:before {
					animation-delay: -0.9s;
					-webkit-animation-delay: -0.9s;
					background: #93cb52;
				}
				#sl-pl-blade-horizontal1 div:nth-child(3) span:after {
					animation-delay: -0.6s;
					-webkit-animation-delay: -0.6s;
				}
				#sl-pl-blade-horizontal1 div:nth-child(4) span:before {
					animation-delay: -1.05s;
					-webkit-animation-delay: -1.05s;
					background: #ffce14;
				}
				#sl-pl-blade-horizontal1 div:nth-child(4) span:after {
					animation-delay: -0.75s;
					-webkit-animation-delay: -0.75s;
				}
				#sl-pl-blade-horizontal1 div:nth-child(5) span:before {
					animation-delay: -1.2s;
					-webkit-animation-delay: -1.2s;
					background: #ffff70;
				}
				#sl-pl-blade-horizontal1 div:nth-child(5) span:after {
					animation-delay: -0.9s;
					-webkit-animation-delay: -0.9s;
				}
				@-webkit-keyframes sl-pl-blade-horizontal1-anim {
					0% {
						-webkit-transform: rotateY(0);
					}
					100% {
						-webkit-transform: rotateY(360deg);
					}
				}
				@keyframes sl-pl-blade-horizontal1-anim {
					0% {
						transform: rotateY(0);
					}
					100% {
						transform: rotateY(360deg);
					}
				}
				@-webkit-keyframes sl-pl-blade-horizontal1-anim-shadow {
					0% {
						box-shadow: -9px 6px #fff, -6px 6px #0f0, -3px 6px #f00;
					}
					50% {
						box-shadow: 50px 6px #fff, 53px 6px #0f0, 56px 6px #f00;
					}
					51% {
						box-shadow: 50px 6px #f00, 53px 6px #0f0, 56px 6px #fff;
					}
					100% {
						box-shadow: -9px 6px #f00, -6px 6px #0f0, -3px 6px #fff;
					}
				}
				@keyframes sl-pl-blade-horizontal1-anim-shadow {
					0% {
						box-shadow: -9px 6px #fff, -6px 6px #0f0, -3px 6px #f00;
					}
					50% {
						box-shadow: 50px 6px #fff, 53px 6px #0f0, 56px 6px #f00;
					}
					51% {
						box-shadow: 50px 6px #f00, 53px 6px #0f0, 56px 6px #fff;
					}
					100% {
						box-shadow: -9px 6px #f00, -6px 6px #0f0, -3px 6px #fff;
					}
				}
				@-webkit-keyframes sl-pl-blade-horizontal1-anim-visible {
					0%, 100% {
						opacity: 1;
					}
					50% {
						opacity: 0;
					}
				}
				@keyframes sl-pl-blade-horizontal1-anim-visible {
					0%, 100% {
						opacity: 1;
					}
					50% {
						opacity: 0;
					}
				}
				<?php
				break;
			case 'dive':
				?>
				#sl-pl-dive div {
					height: 150%;
					left: -25%;
					overflow: hidden;
					position: absolute;
					top: -25%;
					width: 150%;
				}
				#sl-pl-dive div::before {
					animation: sl-pl-dive-anim-path 3s cubic-bezier(0.45, 0.05, 0.55, 0.95) infinite;
					-webkit-animation: sl-pl-dive-anim-path 3s cubic-bezier(0.45, 0.05, 0.55, 0.95) infinite;
					background: linear-gradient(#c3bbff, #3b00b0);
					clip-path: path('M 0.5,34.31 C 9.232,34.8 8.228,38.75 15.08,36.56 24.93,33.42 22.4,38.78 35.71,36.55 43.68,35.22 46.15,36.71 50.02,37.52 54.09,38.37 58.73,34.18 64.09,36.55 66.89,37.79 70.08,37.47 75,35.01 75,35.01 75.72,54.45 63.3,65.85 49.22,78.79 19.49,76.58 9.1,64.22 -0.4729,52.83 0.5,34.31 0.5,34.31 Z');
					-webkit-clip-path: path('M 0.5,34.31 C 9.232,34.8 8.228,38.75 15.08,36.56 24.93,33.42 22.4,38.78 35.71,36.55 43.68,35.22 46.15,36.71 50.02,37.52 54.09,38.37 58.73,34.18 64.09,36.55 66.89,37.79 70.08,37.47 75,35.01 75,35.01 75.72,54.45 63.3,65.85 49.22,78.79 19.49,76.58 9.1,64.22 -0.4729,52.83 0.5,34.31 0.5,34.31 Z');
					content: "";
					height: 100%;
					left: 0;
					position: absolute;
					top: 0;
					width: 100%;
				}
				#sl-pl-dive span {
					background-clip: text !important;
					-webkit-background-clip: text !important;
					height: 50%;
					left: 0;
					overflow: hidden;
					position: absolute;
					width: 100%;
				}
				#sl-pl-dive span:nth-child(2) {
					top: 50%;
				}
				#sl-pl-dive span::before {
					animation: sl-pl-dive-anim 1.7s linear infinite;
					-webkit-animation: sl-pl-dive-anim 1.7s linear infinite;
					background-color: inherit;
					background-image: inherit;
					content: "";
					height: 44%;
					left: 38%;
					position: absolute;
					top: 40%;
					width: 22%;
				}
				#sl-pl-dive span:nth-child(1):before {
					clip-path: polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%);
					-webkit-clip-path: polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%);
					height: 60%;
					left: 31%;
					top: 155%;
					width: 38%;
				}
				#sl-pl-dive span:nth-child(2):before {
					filter: brightness(0.6);
					-webkit-filter: brightness(0.6);
				}
				@-webkit-keyframes sl-pl-dive-anim {
					0% {
						-webkit-animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
						-webkit-transform: translate(0, 0);
					}
					50% {
						-webkit-animation-timing-function: cubic-bezier(0.55, 0.055, 0.675, 0.19);
						-webkit-transform: translate(0, -255%) rotate(180deg);
					}
					75% {
						-webkit-animation-timing-function: ease-out;
						-webkit-transform: translate(0, -160%) rotate(270deg);
					}
					100% {
						-webkit-transform: translate(0, 0) rotate(360deg);
					}
				}
				@keyframes sl-pl-dive-anim {
					0% {
						animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
						transform: translate(0, 0);
					}
					50% {
						animation-timing-function: cubic-bezier(0.55, 0.055, 0.675, 0.19);
						transform: translate(0, -255%) rotate(180deg);
					}
					75% {
						animation-timing-function: ease-out;
						transform: translate(0, -160%) rotate(270deg);
					}
					100% {
						transform: translate(0, 0) rotate(360deg);
					}
				}
				@-webkit-keyframes sl-pl-dive-anim-path {
					0% {
						-webkit-clip-path: path('M 0.5,34.31 C 9.232,34.8 8.228,38.75 15.08,36.56 24.93,33.42 22.4,38.78 35.71,36.55 43.68,35.22 46.15,36.71 50.02,37.52 54.09,38.37 58.73,34.18 64.09,36.55 66.89,37.79 70.08,37.47 75,35.01 75,35.01 75.72,54.45 63.3,65.85 49.22,78.79 19.49,76.58 9.1,64.22 -0.4729,52.83 0.5,34.31 0.5,34.31 Z');
					}
					40% {
						-webkit-clip-path: path('M 0.5,35.61 C 7.196,38.71 6.526,34.53 17.69,37.02 27.19,39.14 28.4,34.2 38.31,37.02 48.03,39.79 52.06,33.97 57.98,35.71 64.97,37.77 66.9,36.89 68.82,36 70.35,35.29 72.46,33.33 75,33.91 75,33.91 75.72,54.45 63.3,65.85 49.22,78.79 20.03,76.1 9.1,64.22 -0.1338,54.18 0.5,35.61 0.5,35.61 Z');
					}
				}
				@keyframes sl-pl-dive-anim-path {
					0% {
						clip-path: path('M 0.5,34.31 C 9.232,34.8 8.228,38.75 15.08,36.56 24.93,33.42 22.4,38.78 35.71,36.55 43.68,35.22 46.15,36.71 50.02,37.52 54.09,38.37 58.73,34.18 64.09,36.55 66.89,37.79 70.08,37.47 75,35.01 75,35.01 75.72,54.45 63.3,65.85 49.22,78.79 19.49,76.58 9.1,64.22 -0.4729,52.83 0.5,34.31 0.5,34.31 Z');
					}
					40% {
						clip-path: path('M 0.5,35.61 C 7.196,38.71 6.526,34.53 17.69,37.02 27.19,39.14 28.4,34.2 38.31,37.02 48.03,39.79 52.06,33.97 57.98,35.71 64.97,37.77 66.9,36.89 68.82,36 70.35,35.29 72.46,33.33 75,33.91 75,33.91 75.72,54.45 63.3,65.85 49.22,78.79 20.03,76.1 9.1,64.22 -0.1338,54.18 0.5,35.61 0.5,35.61 Z');
					}
				}
				<?php
				break;
			case 'circle':
				?>
				#sl-pl-circle span {
					animation: sl-pl-circle-anim 1.2s linear infinite;
					-webkit-animation: sl-pl-circle-anim 1.2s linear infinite;
					border-radius: 50%;
					height: 28%;
					left: -28%;
					position: absolute;
					top: 38%;
					width: 28%;
				}
				#sl-pl-circle span::after {
					animation: sl-pl-circle-anim-visible 2.4s steps(1, end) 0.6s infinite;
					-webkit-animation: sl-pl-circle-anim-visible 2.4s steps(1, end) 0.6s infinite;
					background: #ff6;
					border-radius: 50%;
					content: "";
					height: 100%;
					left: 0;
					opacity: 0;
					position: absolute;
					width: 100%;
				}
				#sl-pl-circle span:nth-child(2) {
					animation-delay: -1.11s;
					-webkit-animation-delay: -1.11s;
					left: 4%;
				}
				#sl-pl-circle span:nth-child(2):after {
					animation-delay: 0.69s;
					-webkit-animation-delay: 0.69s;
					background: #ffc107;
				}
				#sl-pl-circle span:nth-child(3) {
					animation-delay: -1.02s;
					-webkit-animation-delay: -1.02s;
					left: 36%;
				}
				#sl-pl-circle span:nth-child(3):after {
					animation-delay: 0.78s;
					-webkit-animation-delay: 0.78s;
					background: #8bc34a;
				}
				#sl-pl-circle span:nth-child(4) {
					animation-delay: -0.93s;
					-webkit-animation-delay: -0.93s;
					left: 68%;
				}
				#sl-pl-circle span:nth-child(4):after {
					animation-delay: 0.87s;
					-webkit-animation-delay: 0.87s;
					background: #ff5722;
				}
				#sl-pl-circle span:nth-child(5) {
					animation-delay: -0.84s;
					-webkit-animation-delay: -0.84s;
					left: 100%;
				}
				#sl-pl-circle span:nth-child(5):after {
					animation-delay: 0.96s;
					-webkit-animation-delay: 0.96s;
					background: #e040fb;
				}
				@-webkit-keyframes sl-pl-circle-anim {
					50% {
						-webkit-transform: scale(0);
					}
				}
				@keyframes sl-pl-circle-anim {
					50% {
						transform: scale(0);
					}
				}
				@-webkit-keyframes sl-pl-circle-anim-visible {
					0%, 100% {
						opacity: 1;
					}
					50% {
						opacity: 0;
					}
				}
				@keyframes sl-pl-circle-anim-visible {
					0%, 100% {
						opacity: 1;
					}
					50% {
						opacity: 0;
					}
				}
				<?php
				break;
		}//end of switch
	}
}