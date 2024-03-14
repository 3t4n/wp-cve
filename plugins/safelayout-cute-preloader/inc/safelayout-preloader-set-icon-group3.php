<?php
defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

if ( ! function_exists( 'safelayout_preloader_set_icon_group3' ) ) {

	// Return icon css
	function safelayout_preloader_set_icon_group3( $options ) {
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
			case 'cycle':
				?>
				#sl-pl-cycle svg {
					animation: sl-pl-rotate-anim 5.1s linear infinite;
					-webkit-animation: sl-pl-rotate-anim 5.1s linear infinite;
					height: 136%;
					left: -18%;
					position: absolute;
					top: -18%;
					width: 136%;
				}
				#sl-pl-cycle path {
					animation: sl-pl-cycle-anim 1.7s ease-out infinite;
					-webkit-animation: sl-pl-cycle-anim 1.7s ease-out infinite;
					fill: none;
					position: absolute;
					stroke-dasharray: 110 135;
					stroke-dashoffset: -140;
					stroke-linecap: round;
					stroke-width: 5px;
					transform-origin: 50% 50%;
					-webkit-transform-origin: 50% 50%;
					vector-effect: non-scaling-stroke;
				}
				@-webkit-keyframes sl-pl-cycle-anim {
					50% {
						stroke-dashoffset: -27;
					}
					0% {
						-webkit-transform: scale(0.75) rotate(0);
					}
					100% {
						-webkit-transform: scale(0.75) rotate(720deg);
					}
				}
				@keyframes sl-pl-cycle-anim {
					50% {
						stroke-dashoffset: -27;
					}
					0% {
						transform: scale(0.75) rotate(0);
					}
					100% {
						transform: scale(0.75) rotate(720deg);
					}
				}
				@-webkit-keyframes sl-pl-rotate-anim {
					0% {
						-webkit-transform: rotate(0);
					}
					100% {
						-webkit-transform: rotate(360deg);
					}
				}
				@keyframes sl-pl-rotate-anim {
					0% {
						transform: rotate(0);
					}
					100% {
						transform: rotate(360deg);
					}
				}
				<?php
				break;
			case 'grid':
				?>
				#sl-pl-grid div {
					height: 100%;
					position: absolute;
					width: 100%;
				}
				#sl-pl-grid span {
					animation: sl-pl-grid-anim 1.6s ease-in-out infinite;
					-webkit-animation: sl-pl-grid-anim 1.6s ease-in-out infinite;
					float: left;
					height: 16px;
					width: 16px;
				}
				#sl-pl-grid span:nth-child(1),
				#sl-pl-grid span:nth-child(5),
				#sl-pl-grid span:nth-child(9) {
					animation-delay: 0.2s;
					-webkit-animation-delay: 0.2s;
				}
				#sl-pl-grid span:nth-child(2),
				#sl-pl-grid span:nth-child(6) {
					animation-delay: 0.3s;
					-webkit-animation-delay: 0.3s;
				}
				#sl-pl-grid span:nth-child(3) {
					animation-delay: 0.4s;
					-webkit-animation-delay: 0.4s;
				}
				#sl-pl-grid span:nth-child(4),
				#sl-pl-grid span:nth-child(8) {
					animation-delay: 0.1s;
					-webkit-animation-delay: 0.1s;
				}
				#sl-pl-grid span:after {
					animation: sl-pl-grid-anim-visible 3.2s steps(1, end) 0.7s infinite;
					-webkit-animation: sl-pl-grid-anim-visible 3.2s steps(1, end) 0.7s infinite;
					background: #e0e085;
					content: "";
					height: 16px;
					left: 0;
					opacity: 0;
					position: absolute;
					top: 0;
					width: 16px;
				}
				#sl-pl-grid span:nth-child(2):after {
					animation-delay: 0.8s;
					-webkit-animation-delay: 0.8s;
					background: #d1d194;
				}
				#sl-pl-grid span:nth-child(3):after {
					animation-delay: 0.9s;
					-webkit-animation-delay: 0.9s;
					background: #c2c2a3;
				}
				#sl-pl-grid span:nth-child(4):after {
					animation-delay: 0.6s;
					-webkit-animation-delay: 0.6s;
					background: #ffbf00;
				}
				#sl-pl-grid span:nth-child(5):after {
					background: #ff9900;
				}
				#sl-pl-grid span:nth-child(6):after {
					animation-delay: 0.8s;
					-webkit-animation-delay: 0.8s;
					background: #ff7300;
				}
				#sl-pl-grid span:nth-child(7):after {
					animation-delay: 0.5s;
					-webkit-animation-delay: 0.5s;
					background: #e38fba;
				}
				#sl-pl-grid span:nth-child(8):after {
					animation-delay: 0.6s;
					-webkit-animation-delay: 0.6s;
					background: #db70d1;
				}
				#sl-pl-grid span:nth-child(9):after {
					background: #d452e8;
				}
				@-webkit-keyframes sl-pl-grid-anim {
					0%, 60%, 100% {
						-webkit-transform: scale(1);
					}
					30% {
						-webkit-transform: scale(0);
					}
				}
				@keyframes sl-pl-grid-anim {
					0%, 60%, 100% {
						transform: scale(1);
					}
					30% {
						transform: scale(0);
					}
				}
				@-webkit-keyframes sl-pl-grid-anim-visible {
					0%, 100% {
						opacity: 1;
					}
					50% {
						opacity: 0;
					}
				}
				@keyframes sl-pl-grid-anim-visible {
					0%, 100% {
						opacity: 1;
					}
					50% {
						opacity: 0;
					}
				}
				<?php
				break;
			case 'planet':
				?>
				#sl-pl-planet div {
					animation: sl-pl-planet-anim-rotate 2s linear infinite;
					-webkit-animation: sl-pl-planet-anim-rotate 2s linear infinite;
					height: 100%;
					position: absolute;
					width: 100%;
				}
				#sl-pl-planet span {
					animation: sl-pl-planet-anim 2s ease-in-out infinite;
					-webkit-animation: sl-pl-planet-anim 2s ease-in-out infinite;
					border-radius: 50%;
					height: 60%;
					position: absolute;
					width: 60%;
				}
				#sl-pl-planet span:after {
					animation: sl-pl-planet-anim-visible 4s steps(1, end) -2s infinite;
					-webkit-animation: sl-pl-planet-anim-visible 4s steps(1, end) -2s infinite;
					background: linear-gradient(#93cb52, #93cb52 20%, #ffce14 20%, #ffce14 40%, #ffff70 40%, #ffff70 60%, #ffce14 60%, #ffce14 80%, #93cb52 80%, #93cb52);
					border-radius: 50%;
					content: '';
					height: 100%;
					left: 0;
					position: absolute;
					width: 100%;
				}
				#sl-pl-planet span:nth-child(2){
					animation-delay: -1s;
					-webkit-animation-delay: -1s;
					bottom: 0;
					top: auto;
				}
				#sl-pl-planet span:nth-child(2):after{
					animation-delay: -3s;
					-webkit-animation-delay: -3s;
				}
				@-webkit-keyframes sl-pl-planet-anim {
					0%, 100% {
						-webkit-transform: scale(0);
					}
					50% {
						-webkit-transform: scale(1);
					}
				}
				@keyframes sl-pl-planet-anim {
					0%, 100% {
						transform: scale(0);
					}
					50% {
						transform: scale(1);
					}
				}
				@-webkit-keyframes sl-pl-planet-anim-rotate {
					0% {
						-webkit-transform: rotate(0);
					}
					100% {
						-webkit-transform: rotate(360deg);
					}
				}
				@keyframes sl-pl-planet-anim-rotate {
					0% {
						transform: rotate(0);
					}
					100% {
						transform: rotate(360deg);
					}
				}
				@-webkit-keyframes sl-pl-planet-anim-visible {
					0%, 100% {
						opacity: 1;
					}
					50% {
						opacity: 0;
					}
				}
				@keyframes sl-pl-planet-anim-visible {
					0%, 100% {
						opacity: 1;
					}
					50% {
						opacity: 0;
					}
				}
				<?php
				break;
			case 'cube':
				?>
				#sl-pl-cube {
					perspective: 600px;
					-webkit-perspective: 600px;
				}
				#sl-pl-cube div {
					animation: sl-pl-cube-anim 3s linear infinite;
					-webkit-animation: sl-pl-cube-anim 3s linear infinite;
					height: 100%;
					position: absolute;
					transform-style: preserve-3d;
					-webkit-transform-style: preserve-3d;
					width: 100%;
				}
				#sl-pl-cube span {
					border-radius: 15%;
					height: 80%;
					left: 10%;
					opacity: 0.8;
					position: absolute;
					top: 10%;
					width: 80%;
				}
				#sl-pl-cube span:nth-child(1) {
					transform: rotateY(-90deg) translateX(51%) rotateY(90deg);
					-webkit-transform: rotateY(-90deg) translateX(51%) rotateY(90deg);
				}
				#sl-pl-cube span:nth-child(2) {
					transform: rotateY(90deg) rotateY(-90deg) translateX(51%) rotateY(90deg);
					-webkit-transform: rotateY(90deg) rotateY(-90deg) translateX(51%) rotateY(90deg);
				}
				#sl-pl-cube span:nth-child(3) {
					transform: rotateY(180deg) rotateY(-90deg) translateX(51%) rotateY(90deg);
					-webkit-transform: rotateY(180deg) rotateY(-90deg) translateX(51%) rotateY(90deg);
				}
				#sl-pl-cube span:nth-child(4) {
					transform: rotateY(-90deg) rotateY(-90deg) translateX(51%) rotateY(90deg);
					-webkit-transform: rotateY(-90deg) rotateY(-90deg) translateX(51%) rotateY(90deg);
				}
				#sl-pl-cube span:nth-child(5) {
					transform: rotateX(90deg) rotateY(-90deg) translateX(51%) rotateY(90deg);
					-webkit-transform: rotateX(90deg) rotateY(-90deg) translateX(51%) rotateY(90deg);
				}
				#sl-pl-cube span:nth-child(6) {
					transform: rotateX(-90deg) rotateY(-90deg) translateX(51%) rotateY(90deg);
					-webkit-transform: rotateX(-90deg) rotateY(-90deg) translateX(51%) rotateY(90deg);
				}
				@-webkit-keyframes sl-pl-cube-anim {
					0% {
						-webkit-transform: rotate3d(1, 1, 1, 0);
					}
					100% {
						-webkit-transform: rotate3d(1, 1, 1, 360deg);
					}
				}
				@keyframes sl-pl-cube-anim {
					0% {
						transform: rotate3d(1, 1, 1, 0);
					}
					100% {
						transform: rotate3d(1, 1, 1, 360deg);
					}
				}
				<?php
				break;
			case 'cube1':
				?>
				#sl-pl-cube1 {
					perspective: 600px;
					-webkit-perspective: 600px;
				}
				#sl-pl-cube1 div {
					animation: sl-pl-cube1-anim 3s linear infinite;
					-webkit-animation: sl-pl-cube1-anim 3s linear infinite;
					height: 100%;
					position: absolute;
					transform-style: preserve-3d;
					-webkit-transform-style: preserve-3d;
					width: 100%;
				}
				#sl-pl-cube1 span {
					border-radius: 15%;
					height: 80%;
					left: 10%;
					opacity: 0.8;
					overflow: hidden;
					position: absolute;
					top: 10%;
					width: 80%;
				}
				#sl-pl-cube1 span::after {
					animation: sl-pl-cube1-anim-rotate 3s linear infinite;
					-webkit-animation: sl-pl-cube1-anim-rotate 3s linear infinite;
					background-color: #000;
					border-radius: 50%;
					box-shadow: 17px 12px #f00, 25px 15px #fff, 28px 23px #0f0, 25px 31px #00f, 17px 34px #f00, 9px 31px #fff, 6px 23px #0f0, 9px 15px #00f;
					content: "";
					height: 15%;
					left: 0;
					position: absolute;
					top: -15%;
					transform-origin: 333% 433%;
					-webkit-transform-origin: 333% 433%;
					width: 15%;
				}
				#sl-pl-cube1 span:nth-child(1) {
					transform: rotateY(-90deg) translateX(51%) rotateY(90deg);
					-webkit-transform: rotateY(-90deg) translateX(51%) rotateY(90deg);
				}
				#sl-pl-cube1 span:nth-child(2) {
					transform: rotateY(90deg) rotateY(-90deg) translateX(51%) rotateY(90deg);
					-webkit-transform: rotateY(90deg) rotateY(-90deg) translateX(51%) rotateY(90deg);
				}
				#sl-pl-cube1 span:nth-child(3) {
					transform: rotateY(180deg) rotateY(-90deg) translateX(51%) rotateY(90deg);
					-webkit-transform: rotateY(180deg) rotateY(-90deg) translateX(51%) rotateY(90deg);
				}
				#sl-pl-cube1 span:nth-child(4) {
					transform: rotateY(-90deg) rotateY(-90deg) translateX(51%) rotateY(90deg);
					-webkit-transform: rotateY(-90deg) rotateY(-90deg) translateX(51%) rotateY(90deg);
				}
				#sl-pl-cube1 span:nth-child(5) {
					transform: rotateX(90deg) rotateY(-90deg) translateX(51%) rotateY(90deg);
					-webkit-transform: rotateX(90deg) rotateY(-90deg) translateX(51%) rotateY(90deg);
				}
				#sl-pl-cube1 span:nth-child(6) {
					transform: rotateX(-90deg) rotateY(-90deg) translateX(51%) rotateY(90deg);
					-webkit-transform: rotateX(-90deg) rotateY(-90deg) translateX(51%) rotateY(90deg);
				}
				@-webkit-keyframes sl-pl-cube1-anim {
					0% {
						-webkit-transform: rotate3d(1, 1, 1, 0);
					}
					100% {
						-webkit-transform: rotate3d(1, 1, 1, 360deg);
					}
				}
				@keyframes sl-pl-cube1-anim {
					0% {
						transform: rotate3d(1, 1, 1, 0);
					}
					100% {
						transform: rotate3d(1, 1, 1, 360deg);
					}
				}
				@-webkit-keyframes sl-pl-cube1-anim-rotate {
					0% {
						-webkit-transform: rotate(0);
					}
					100% {
						-webkit-transform: rotate(360deg);
					}
				}
				@keyframes sl-pl-cube1-anim-rotate {
					0% {
						transform: rotate(0);
					}
					100% {
						transform: rotate(360deg);
					}
				}
				<?php
				break;
			case 'queue':
				?>
				#sl-pl-queue div {
					height: 100%;
					left: -102%;
					position: absolute;
					width: 300%;
				}
				#sl-pl-queue span {
					animation: sl-pl-queue-anim 2.2s cubic-bezier(0.25, 0.5, 0.75, 0.5) infinite;
					-webkit-animation: sl-pl-queue-anim 2.2s cubic-bezier(0.25, 0.5, 0.75, 0.5) infinite;
					height: 12%;
					left: 39%;
					position: absolute;
					top: 50%;
					width: 4%;
				}
				#sl-pl-queue span:nth-child(2) {
					animation-delay: -0.1s;
					-webkit-animation-delay: -0.1s;
					left: 45%;
				}
				#sl-pl-queue span:nth-child(3) {
					animation-delay: -0.2s;
					-webkit-animation-delay: -0.2s;
					left: 51%;
				}
				#sl-pl-queue span:nth-child(4) {
					animation-delay: -0.3s;
					-webkit-animation-delay: -0.3s;
					left: 57%;
				}
				@-webkit-keyframes sl-pl-queue-anim {
					0%, 15% {
						-webkit-transform: translateX(-65vw);
					}
					30% {
						-webkit-transform: translateX(-500%);
						-webkit-animation-timing-function: cubic-bezier(0.4, 0.65, 0.69, 0.44);
					}
					73% {
						-webkit-transform: translateX(300%);
					}
					85%, 100% {
						-webkit-transform: translateX(65vw);
					}
				}
				@keyframes sl-pl-queue-anim {
					0%, 15% {
						transform: translateX(-65vw);
					}
					30% {
						transform: translateX(-500%);
						animation-timing-function: cubic-bezier(0.4, 0.65, 0.69, 0.44);
					}
					73% {
						transform: translateX(300%);
					}
					85%, 100% {
						transform: translateX(65vw);
					}
				}
				<?php
				break;
			case 'leap':
				?>
				#sl-pl-leap span {
					animation: sl-pl-leap-anim 1s linear infinite;
					-webkit-animation: sl-pl-leap-anim 1s linear infinite;
					height: 12%;
					left: 6px;
					position: absolute;
					top: 50%;
					transform-origin: 50% 100%;
					-webkit-transform-origin: 50% 100%;
					width: 12%;
				}
				#sl-pl-leap span:after {
					animation: sl-pl-leap-anim-visible 2s steps(1, end) 0.8s infinite;
					-webkit-animation: sl-pl-leap-anim-visible 2s steps(1, end) 0.8s infinite;
					background: #6670ff;
					content: "";
					height: 100%;
					left: 0;
					opacity: 0;
					position: absolute;
					width: 100%;
				}
				#sl-pl-leap span:nth-child(2) {
					animation-delay: 0.1s;
					-webkit-animation-delay: 0.1s;
					left: 17px;
				}
				#sl-pl-leap span:nth-child(2):after {
					animation-delay: 0.9s;
					-webkit-animation-delay: 0.9s;
					background: #f44336;
				}
				#sl-pl-leap span:nth-child(3) {
					animation-delay: 0.2s;
					-webkit-animation-delay: 0.2s;
					left: 28px;
				}
				#sl-pl-leap span:nth-child(3):after {
					animation-delay: 1s;
					-webkit-animation-delay: 1s;
					background: #89c148;
				}
				#sl-pl-leap span:nth-child(4) {
					animation-delay: 0.3s;
					-webkit-animation-delay: 0.3s;
					left: 39px;
				}
				#sl-pl-leap span:nth-child(4):after {
					animation-delay: 1.1s;
					-webkit-animation-delay: 1.1s;
					background: #fac90f;
				}
				@-webkit-keyframes sl-pl-leap-anim {
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
				@keyframes sl-pl-leap-anim {
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
				@-webkit-keyframes sl-pl-leap-anim-visible {
					0%, 100% {
						opacity: 1;
					}
					50% {
						opacity: 0;
					}
				}
				@keyframes sl-pl-leap-anim-visible {
					0%, 100% {
						opacity: 1;
					}
					50% {
						opacity: 0;
					}
				}
				<?php
				break;
			case 'moons':
				?>
				#sl-pl-moons span {
					animation: sl-pl-moons-anim 2.6s infinite;
					-webkit-animation: sl-pl-moons-anim 2.6s infinite;
					border-radius: 50%;
					height: 14%;
					left: 50%;
					position: absolute;
					top: 100%;
					transform: translate(-50%, -50%);
					-webkit-transform: translate(-50%, -50%);
					transform-origin: 50% -300%;
					-webkit-transform-origin: 50% -300%;
					width: 14%;
				}
				#sl-pl-moons span:after {
					animation: sl-pl-moons-anim-visible 5.2s steps(1, end) -2.6s infinite;
					-webkit-animation: sl-pl-moons-anim-visible 5.2s steps(1, end) -2.6s infinite;
					background: #2196f3;
					border-radius: 50%;
					content: "";
					height: 100%;
					left: 0;
					position: absolute;
					width: 100%;
				}
				#sl-pl-moons span:nth-child(2) {
					animation-delay: 0.1s;
					-webkit-animation-delay: 0.1s;
				}
				#sl-pl-moons span:nth-child(2):after {
					background: #ff5722;
				}
				#sl-pl-moons span:nth-child(3) {
					animation-delay: 0.2s;
					-webkit-animation-delay: 0.2s;
				}
				#sl-pl-moons span:nth-child(3):after {
					background: #8bc34a;
				}
				#sl-pl-moons span:nth-child(4) {
					animation-delay: 0.3s;
					-webkit-animation-delay: 0.3s;
				}
				#sl-pl-moons span:nth-child(4):after {
					background: #ffc107;
				}
				#sl-pl-moons span:nth-child(5) {
					animation-delay: 0.4s;
					-webkit-animation-delay: 0.4s;
				}
				#sl-pl-moons span:nth-child(5):after {
					background: #e7de9d;
				}
				@-webkit-keyframes sl-pl-moons-anim {
					0% {
						-webkit-animation-timing-function: cubic-bezier(0.19, 0.68, 0.75, 0.33);
						-webkit-transform: translate(-50%, -50%) rotate(0);
					}
					40% {
						-webkit-animation-timing-function: cubic-bezier(0, 0, 0.45, 1);
						-webkit-transform: translate(-50%, -50%) rotate(360deg);
					}
					100% {
						-webkit-transform: translate(-50%, -50%) rotate(720deg);
					}
				}
				@keyframes sl-pl-moons-anim {
					0% {
						animation-timing-function: cubic-bezier(0.19, 0.68, 0.75, 0.33);
						transform: translate(-50%, -50%) rotate(0);
					}
					40% {
						animation-timing-function: cubic-bezier(0, 0, 0.45, 1);
						transform: translate(-50%, -50%) rotate(360deg);
					}
					100% {
						transform: translate(-50%, -50%) rotate(720deg);
					}
				}
				@-webkit-keyframes sl-pl-moons-anim-visible {
					0%, 100% {
						opacity: 1;
					}
					50% {
						opacity: 0;
					}
				}
				@keyframes sl-pl-moons-anim-visible {
					0%, 100% {
						opacity: 1;
					}
					50% {
						opacity: 0;
					}
				}
				<?php
				break;
			case 'stream':
				?>
				#sl-pl-stream svg {
					animation: sl-pl-stream-anim 1s linear infinite;
					-webkit-animation: sl-pl-stream-anim 1s linear infinite;
					height: 100%;
					width: 100%;
				}
				#sl-pl-stream circle {
					fill: none;
					stroke-width: 5px;
				}
				@-webkit-keyframes sl-pl-stream-anim {
					0% {
						-webkit-transform: rotate(0);
					}
					100% {
						-webkit-transform: rotate(360deg);
					}
				}
				@keyframes sl-pl-stream-anim {
					0% {
						transform: rotate(0);
					}
					100% {
						transform: rotate(360deg);
					}
				}
				<?php
				break;
			case 'tube':
				?>
				#sl-pl-tube svg {
					animation: sl-pl-tube-anim 1s linear infinite;
					-webkit-animation: sl-pl-tube-anim 1s linear infinite;
					height: 140%;
					left: -20%;
					position: absolute;
					top: -20%;
					width: 140%;
				}
				#sl-pl-tube circle {
					fill: none;
					stroke: red;
				}
				@-webkit-keyframes sl-pl-tube-anim {
					0% {
						-webkit-transform: rotate(0);
					}
					100% {
						-webkit-transform: rotate(360deg);
					}
				}
				@keyframes sl-pl-tube-anim {
					0% {
						transform: rotate(0);
					}
					100% {
						transform: rotate(360deg);
					}
				}
				<?php
				break;
			case 'window':
				?>
				#sl-pl-window div {
					animation: sl-pl-window-anim 2.5s ease infinite;
					-webkit-animation: sl-pl-window-anim 2.5s ease infinite;
					height: 48%;
					left: 0;
					position: absolute;
					transform-origin: 102% 102%;
					-webkit-transform-origin: 102% 102%;
					width: 48%;
				}
				#sl-pl-window span {
					border-radius: 20%;
					height: 100%;
					left: 0;
					position: absolute;
					width: 100%;
				}
				#sl-pl-window span:after {
					animation: sl-pl-window-anim-visible 5s steps(1, end) -2.5s infinite;
					-webkit-animation: sl-pl-window-anim-visible 5s steps(1, end) -2.5s infinite;
					background: #0f0;
					border: 1px solid #00b300;
					box-sizing: border-box;
					border-radius: 20%;
					content: "";
					height: 100%;
					left: 0;
					position: absolute;
					width: 100%;
				}
				#sl-pl-window div:nth-child(2) {
					animation-delay: -0.1s;
					-webkit-animation-delay: -0.1s;
					left: 0;
					top: 52%;
					transform-origin: 102% -2%;
					-webkit-transform-origin: 102% -2%;
				}
				#sl-pl-window div:nth-child(3) {
					animation-delay: -0.2s;
					-webkit-animation-delay: -0.2s;
					left: 52%;
					top: 52%;
					transform-origin: -2% -2%;
					-webkit-transform-origin: -2% -2%;
				}
				#sl-pl-window div:nth-child(4) {
					animation-delay: -0.3s;
					-webkit-animation-delay: -0.3s;
					left: 52%;
					transform-origin: -2% 102%;
					-webkit-transform-origin: -2% 102%;
				}
				#sl-pl-window div:nth-child(2) span {
					transform: scale(1, -1);
					-webkit-transform: scale(1, -1);
				}
				#sl-pl-window div:nth-child(2) span:after {
					background: #ff9800;
					border-color: #b36b00;
				}
				#sl-pl-window div:nth-child(3) span {
					transform: scale(-1, -1);
					-webkit-transform: scale(-1, -1);
				}
				#sl-pl-window div:nth-child(3) span:after {
					background: #e7519f;
					border-color: #772751;
				}
				#sl-pl-window div:nth-child(4) span {
					transform: scale(-1, 1);
					-webkit-transform: scale(-1, 1);
				}
				#sl-pl-window div:nth-child(4) span:after {
					background: #4e59ff;
					border-color: #232877;
				}
				@-webkit-keyframes sl-pl-window-anim {
					0% {
						opacity: 0;
						-webkit-transform: rotate(0);
					}
					50% {
						-webkit-transform: rotate(360deg);
					}
					100% {
						opacity: 1;
						-webkit-transform:rotate(360deg);
					}
				}
				@keyframes sl-pl-window-anim {
					0% {
						opacity: 0;
						transform: rotate(0);
					}
					50% {
						transform: rotate(360deg);
					}
					100% {
						opacity: 1;
						transform: rotate(360deg);
					}
				}
				@-webkit-keyframes sl-pl-window-anim-visible {
					0%, 100% {
						opacity: 1;
					}
					50% {
						opacity: 0;
					}
				}
				@keyframes sl-pl-window-anim-visible {
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