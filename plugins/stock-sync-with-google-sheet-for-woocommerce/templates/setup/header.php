<?php

/**
 * Header template for setup.
 *
 * @package StockSyncWithGoogleSheetForWooCommerce
 * @since   1.0.0
 */

// Exit if accessed directly.
defined('ABSPATH') || exit(); ?>
<div class="ssgs-tab__nav text-center">
	<ul class="ssgs-tab__nav-tab">
		<template x-for="(step, index) in state.steps">
			<li class="step" :class="{'active' : isStep(index + 1), 'disabled' : state.currentStep <= index}" @click.prevent="setStep(index + 1)"><a href="#" x-text="step"></a></li>
		</template>
	</ul>
</div><!-- /Tab Header Controls -->
