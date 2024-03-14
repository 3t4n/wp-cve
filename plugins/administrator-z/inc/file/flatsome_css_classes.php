<?php 
return [
	'Row'=> [
		['align-middle', 'align-top', 'align-bottom', 'align-center', 'align-right', 'align-equal'],
		['row-collapse', 'row-small', 'row-large', 'row-reverse', 'row-isotope', 'row-grid', 'row-masonry', 'row-divided'],
		['row-box-shadow-1', 'row-box-shadow-2', 'row-box-shadow-3', 'row-box-shadow-4', 'row-box-shadow-5'],
		['row-box-shadow-1-hover', 'row-box-shadow-2-hover', 'row-box-shadow-3-hover', 'row-box-shadow-4-hover', 'row-box-shadow-5-hover'],
		['row-dashed','row-solid'],
		['is-full-height', 'row-full-width'],
		['dark'],
		['flex-row','flex-row-start','flex-row-center','flex-row-col','flex-wrap','flex-grow']
	],
	'Col'=> [
		['col-fit', 'col-first', 'col-last', 'col-border', 'col-divided'],
		['large-1', 'large-2', 'large-3', '...' ,'large-12'],
		['small-1', 'small-2', 'small-3', '...', 'small-12 '],
		['medium-1', 'medium-2', 'medium-3', '...', 'medium -12 '],
		['pull-right','pull-left'],
		['flex-col','flex-left','flex-center','flex-right'],	        			
	],
	'Size'=> [
		['is-xxxlarge','is-xxlarge','is-xlarge','is-larger','is-large','is-small','is-smaller','is-xsmall','is-xxsmall']
	],
	'Font'=> [
		['is-normal','is-bold','is-thin','is-italic','is-uppercase','is-alt-font']
	],
	'Button'=> [
		['button','is-form','is-link','is-outline','is-underline','checkout','alt','disabled']
	],
	'Margin'=> [
		['mb', 'mt', 'mr', 'ml'],
		['mb-0', 'ml-0', 'mr-0', 'mt-0', 'mb-half', 'mt-half', 'mr-half', 'ml-half']
	],
	'Padding'=> [
		['pb', 'pt'],
		['pb-half', 'pt-half', 'pb-0', 'pt-0', 'no-margin', 'no-padding']
	],
	'Text'=> [
		['text-shadow','text-shadow-1','text-shadow-2','text-shadow-3','text-shadow-4','text-shadow-5'],
		['text-center','text-right','text-left'],
		['text-box','text-box-square','text-box-circle'],
		['text-bordered-white','text-bordered-primary','text-bordered-dark'],
		['text-boarder-top-bottom-white','text-boarder-top-bottom-dark','text-boarder-top-bottom-white','text-boarder-top-bottom-dark']
	],
	'Border'=>[
		['has-border','round','bb','bt','bl','br'],
		['is-border','is-dashed','is-dotted'],
		['has-border','dashed-border','success-border']
	],
	'Color'=> [
		['primary-color','secondary-color','success-color','alert-color']
	],
	'Background'=> [
		['bg-fill','bg-top'],
		['bg-primary-color','bg-secondary-color','bg-success-color','bg-alert-color','is-transparent']
	],
	'Position'=> [
		['relative','absolute','fixed'],
		['top','right','left','bottom','v-center','h-center'],
		[
			'lg-x5','lg-x15','lg-x25','...','lg-x95',
			'lg-y5','lg-y15','lg-y25','...','lg-y95',
			'lg-x0','lg-x10','lg-x20','...','lg-x100',
			'lg-y0','lg-y10','lg-y20','...','lg-y100',
			'lg-x50','lg-y50'
		],
		[
			'md-x5','md-x15','md-x25','...','md-x95',
			'md-y5','md-y15','md-y25','...','md-y95',
			'md-x0','md-x10','md-x20','...','md-x100',
			'md-y0','md-y10','md-y20','...','md-y100',
			'md-x50','md-y50'
		],
		[
			'x5','x15','x25','...','x95',
			'y5','y15','y25','...','y95',
			'x0','x10','x20','...','x100',
			'y0','y10','y20','...','y100',
			'x50','y50'
		],
		['z-1', 'z-2', 'z-3', 'z-4', 'z-5', 'z-top', 'z-top-2', 'z-top-3']
	],
	'Opacity' => [
		['op-4','op-5','op-6','op-7','op-8']
	],
	'Icon'=> [
		['icon-lock', 'icon-user-o', 'icon-line', 'icon-chat', 'icon-user', 'icon-shopping-cart', 'icon-tumblr', 'icon-gift', 'icon-phone', 'icon-play', 'icon-menu', 'icon-equalizer', 'icon-shopping-basket', 'icon-shopping-bag', 'icon-google-plus', 'icon-heart-o', 'icon-heart', 'icon-500px', 'icon-vk', 'icon-angle-left', 'icon-angle-right', 'icon-angle-up', 'icon-angle-down', 'icon-twitter', 'icon-envelop', 'icon-tag', 'icon-star', 'icon-star-o', 'icon-facebook', 'icon-feed', 'icon-checkmark', 'icon-plus', 'icon-instagram', 'icon-tiktok', 'icon-pinterest', 'icon-search', 'icon-skype', 'icon-dribbble', 'icon-certificate', 'icon-expand', 'icon-linkedin', 'icon-map-pin-fill', 'icon-pen-alt-fill', 'icon-youtube', 'icon-flickr', 'icon-clock', 'icon-snapchat', 'icon-whatsapp', 'icon-telegram', 'icon-twitch', 'icon-discord']
	],
	'Custom'=> [
		['--secondary-color', '--success-color', '--alert-color'],
		['text-primary', 'text-secondary', 'text-success', 'alert-color'],
		['row-nopaddingbottom','nopaddingbottom'],
		['sliderbot'],
		['bgr-size-auto']
	]
];