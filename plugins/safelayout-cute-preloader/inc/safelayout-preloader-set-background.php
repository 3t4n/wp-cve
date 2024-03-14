<?php
defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

if ( ! function_exists( 'safelayout_preloader_set_background' ) ) {

	// Return background css
	function safelayout_preloader_set_background( $options ) {
		?>
		.sl-pl-bg {
			pointer-events: auto;
			position: fixed;
			transition: all 0.5s cubic-bezier(0.645, 0.045, 0.355, 1) 0s;
		}
		<?php
		switch ( $options ) {
			case 'fade':
				?>
				.sl-pl-bg-fade {
					height: 100%;
					left: 0;
					top: 0;
					width: 100%;
				}
				.sl-pl-loaded .sl-pl-bg-fade {
					opacity: 0 !important;
				}
				<?php
				break;
			case 'to-left':
				?>
				.sl-pl-bg-to-left {
					height: 100%;
					left: 0;
					top: 0;
					width: 100%;
				}
				.sl-pl-loaded .sl-pl-bg-to-left {
					transform: translateX(-101vw);
					-webkit-transform: translateX(-101vw);
				}
				<?php
				break;
			case 'to-right':
				?>
				.sl-pl-bg-to-right {
					height: 100%;
					left: 0;
					top: 0;
					width: 100%;
				}
				.sl-pl-loaded .sl-pl-bg-to-right {
					transform: translateX(101vw);
					-webkit-transform: translateX(101vw);
				}
				<?php
				break;
			case 'to-top':
				?>
				.sl-pl-bg-to-top {
					height: 100%;
					left: 0;
					top: 0;
					width: 100%;
				}
				.sl-pl-loaded .sl-pl-bg-to-top {
					transform: translateY(-101vh);
					-webkit-transform: translateY(-101vh);
				}
				<?php
				break;
			case 'to-bottom':
				?>
				.sl-pl-bg-to-bottom {
					height: 100%;
					left: 0;
					top: 0;
					width: 100%;
				}
				.sl-pl-loaded .sl-pl-bg-to-bottom {
					transform: translateY(101vh);
					-webkit-transform: translateY(101vh);
				}
				<?php
				break;
			case 'ellipse-bottom':
				?>
				.sl-pl-bg-ellipse-bottom {
					height: 100%;
					left: 0;
					top: 0;
					width: 100%;
				}
				.sl-pl-bg-ellipse-bottom {
					clip-path: ellipse(150% 150% at 100% 100%);
					-webkit-clip-path: ellipse(150% 150% at 100% 100%);
				}
				.sl-pl-loaded .sl-pl-bg-ellipse-bottom {
					clip-path: ellipse(0 0 at 100% 100%);
					-webkit-clip-path: ellipse(0 0 at 100% 100%);
				}
				<?php
				break;
			case 'ellipse-top':
				?>
				.sl-pl-bg-ellipse-top {
					height: 100%;
					left: 0;
					top: 0;
					width: 100%;
				}
				.sl-pl-bg-ellipse-top {
					clip-path: ellipse(150% 150% at 0 0);
					-webkit-clip-path: ellipse(150% 150% at 0 0);
				}
				.sl-pl-loaded .sl-pl-bg-ellipse-top {
					clip-path: ellipse(0 0 at 0 0);
					-webkit-clip-path: ellipse(0 0 at 0 0);
				}
				<?php
				break;
			case 'ellipse-left':
				?>
				.sl-pl-bg-ellipse-left {
					height: 100%;
					left: 0;
					top: 0;
					width: 100%;
				}
				.sl-pl-bg-ellipse-left {
					clip-path: ellipse(150% 150% at 0 100%);
					-webkit-clip-path: ellipse(150% 150% at 0 100%);
				}
				.sl-pl-loaded .sl-pl-bg-ellipse-left {
					clip-path: ellipse(0 0 at 0 100%);
					-webkit-clip-path: ellipse(0 0 at 0 100%);
				}
				<?php
				break;
			case 'ellipse-right':
				?>
				.sl-pl-bg-ellipse-right {
					height: 100%;
					left: 0;
					top: 0;
					width: 100%;
				}
				.sl-pl-bg-ellipse-right {
					clip-path: ellipse(150% 150% at 100% 0);
					-webkit-clip-path: ellipse(150% 150% at 100% 0);
				}
				.sl-pl-loaded .sl-pl-bg-ellipse-right {
					clip-path: ellipse(0 0 at 100% 0);
					-webkit-clip-path: ellipse(0 0 at 100% 0);
				}
				<?php
				break;
			case 'rect':
				?>
				.sl-pl-bg-rect {
					height: 100%;
					left: 0;
					top: 0;
					width: 100%;
				}
				.sl-pl-loaded .sl-pl-bg-rect {
					transform: scale(0);
					-webkit-transform: scale(0);
				}
				<?php
				break;
			case 'diamond':
				?>
				.sl-pl-bg-diamond {
					height: 100%;
					left: 0;
					top: 0;
					width: 100%;
				}
				.sl-pl-bg-diamond {
					clip-path: polygon(-50% 50%, 50% -50%, 150% 50%, 50% 150%);
					-webkit-clip-path: polygon(-50% 50%, 50% -50%, 150% 50%, 50% 150%);
				}
				.sl-pl-loaded .sl-pl-bg-diamond {
					clip-path: polygon(50% 50%, 50% 50%, 50% 50%, 50% 50%);
					-webkit-clip-path: polygon(50% 50%, 50% 50%, 50% 50%, 50% 50%);
				}
				<?php
				break;
			case 'circle':
				?>
				.sl-pl-bg-circle {
					height: 100%;
					left: 0;
					top: 0;
					width: 100%;
				}
				.sl-pl-bg-circle {
					clip-path: circle(75%);
					-webkit-clip-path: circle(75%);
				}
				.sl-pl-loaded .sl-pl-bg-circle {
					clip-path: circle(0);
					-webkit-clip-path: circle(0);
				}
				<?php
				break;
			case 'tear-vertical':
				?>
				.sl-pl-bg-tear-vertical-left {
					height: 100%;
					left: 0;
					top: 0;
					width: 50%;
				}
				.sl-pl-bg-tear-vertical-right {
					height: 100%;
					left: 50%;
					top: 0;
					width: 50%;
				}
				.sl-pl-loaded .sl-pl-bg-tear-vertical-left {
					transform: translateY(-101vh);
					-webkit-transform: translateY(-101vh);
				}
				.sl-pl-loaded .sl-pl-bg-tear-vertical-right {
					transform: translateY(101vh);
					-webkit-transform: translateY(101vh);
				}
				<?php
				break;
			case 'split-horizontal':
				?>
				.sl-pl-bg-split-horizontal-left {
					height: 100%;
					left: 0;
					top: 0;
					width: 50%;
				}
				.sl-pl-bg-split-horizontal-right {
					height: 100%;
					left: 50%;
					top: 0;
					width: 50%;
				}
				.sl-pl-loaded .sl-pl-bg-split-horizontal-left {
					transform: translateX(-51vw);
					-webkit-transform: translateX(-51vw);
				}
				.sl-pl-loaded .sl-pl-bg-split-horizontal-right {
					transform: translateX(51vw);
					-webkit-transform: translateX(51vw);
				}
				<?php
				break;
			case 'tear-horizontal':
				?>
				.sl-pl-bg-tear-horizontal-top {
					height: 50%;
					left: 0;
					top: 0;
					width: 100%;
				}
				.sl-pl-bg-tear-horizontal-bottom {
					height: 50%;
					left: 0;
					top: 50%;
					width: 100%;
				}
				.sl-pl-loaded .sl-pl-bg-tear-horizontal-top {
					transform: translateX(-101vw);
					-webkit-transform: translateX(-101vw);
				}
				.sl-pl-loaded .sl-pl-bg-tear-horizontal-bottom {
					transform: translateX(101vw);
					-webkit-transform: translateX(101vw);
				}
				<?php
				break;
			case 'split-vertical':
				?>
				.sl-pl-bg-split-vertical-top {
					height: 50%;
					left: 0;
					top: 0;
					width: 100%;
				}
				.sl-pl-bg-split-vertical-bottom {
					height: 50%;
					left: 0;
					top: 50%;
					width: 100%;
				}
				.sl-pl-loaded .sl-pl-bg-split-vertical-top {
					transform: translateY(-51vh);
					-webkit-transform: translateY(-51vh);
				}
				.sl-pl-loaded .sl-pl-bg-split-vertical-bottom {
					transform: translateY(51vh);
					-webkit-transform: translateY(51vh);
				}
				<?php
				break;
			case 'linear-left':
				?>
				.sl-pl-bg-linear-left {
					height: 100%;
					left: 0;
					top: 0;
					width: 100%;
				}
				.sl-pl-bg-linear-left div {
					display: inline-block;
					height: 100%;
					transition: all 0.3s cubic-bezier(0.645, 0.045, 0.355, 1) 0s, background 0s;
					width: 10%;
				}
				.sl-pl-loaded .sl-pl-bg-linear-left div {
					opacity: 0 !important;
				}
				.sl-pl-loaded .sl-pl-bg-linear-left div:nth-child(2) {
					transition-delay: 0.025s;
				}
				.sl-pl-loaded .sl-pl-bg-linear-left div:nth-child(3) {
					transition-delay: 0.05s;
				}
				.sl-pl-loaded .sl-pl-bg-linear-left div:nth-child(4) {
					transition-delay: 0.075s;
				}
				.sl-pl-loaded .sl-pl-bg-linear-left div:nth-child(5) {
					transition-delay: 0.1s;
				}
				.sl-pl-loaded .sl-pl-bg-linear-left div:nth-child(6) {
					transition-delay: 0.125s;
				}
				.sl-pl-loaded .sl-pl-bg-linear-left div:nth-child(7) {
					transition-delay: 0.15s;
				}
				.sl-pl-loaded .sl-pl-bg-linear-left div:nth-child(8) {
					transition-delay: 0.175s;
				}
				.sl-pl-loaded .sl-pl-bg-linear-left div:nth-child(9) {
					transition-delay: 0.2s;
				}
				.sl-pl-loaded .sl-pl-bg-linear-left div:nth-child(10) {
					transition-delay: 0.225s;
				}
				<?php
				break;
			case 'linear-right':
				?>
				.sl-pl-bg-linear-right {
					height: 100%;
					left: 0;
					top: 0;
					width: 100%;
				}
				.sl-pl-bg-linear-right div {
					display: inline-block;
					height: 100%;
					transition: all 0.3s cubic-bezier(0.645, 0.045, 0.355, 1) 0s, background 0s;
					width: 10%;
				}
				.sl-pl-loaded .sl-pl-bg-linear-right div {
					opacity: 0 !important;
				}
				.sl-pl-loaded .sl-pl-bg-linear-right div:nth-child(9) {
					transition-delay: 0.025s;
				}
				.sl-pl-loaded .sl-pl-bg-linear-right div:nth-child(8) {
					transition-delay: 0.05s;
				}
				.sl-pl-loaded .sl-pl-bg-linear-right div:nth-child(7) {
					transition-delay: 0.075s;
				}
				.sl-pl-loaded .sl-pl-bg-linear-right div:nth-child(6) {
					transition-delay: 0.1s;
				}
				.sl-pl-loaded .sl-pl-bg-linear-right div:nth-child(5) {
					transition-delay: 0.125s;
				}
				.sl-pl-loaded .sl-pl-bg-linear-right div:nth-child(4) {
					transition-delay: 0.15s;
				}
				.sl-pl-loaded .sl-pl-bg-linear-right div:nth-child(3) {
					transition-delay: 0.175s;
				}
				.sl-pl-loaded .sl-pl-bg-linear-right div:nth-child(2) {
					transition-delay: 0.2s;
				}
				.sl-pl-loaded .sl-pl-bg-linear-right div:nth-child(1) {
					transition-delay: 0.225s;
				}
				<?php
				break;
		}//end of switch
	}
}