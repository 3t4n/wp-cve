/**
 * block.js - Contains all Gutenberg functionality required by Photonic
 */
var photonicBlockProperties;
(function (wp) {
	var el = wp.element.createElement;
	var __ = wp.i18n.__;
	var components = wp.components;
	var iconEl;
	iconEl = el('svg', {width: 23, height: 24, viewBox: "0 0 24 24"},
		el('g', {transform: "scale(0.046785)"},
			el('circle', {cx: "256", cy: "192", r: "128", fill: "#0085ba"}),
			el('rect', {width: "64", height: "256", x: "128", y: "192", fill: "#0085ba"}),
			el('circle', {cx: "256", cy: "192", r: "64", fill: "white"}),
			el('rect', {width: "16", height: "128", x: "192", y: "192", fill: "white"})
		)
	);

	var tag = Photonic_Gutenberg_JS.shortcode.toLowerCase() === 'gallery' ? 'gallery__photonic_random_314159' : Photonic_Gutenberg_JS.shortcode;
	wp.blocks.registerBlockType('photonic/gallery', {
		title: __('Photonic Gallery', 'photonic'),
		category: 'widgets',
		keywords: ['flickr', 'smugmug', 'google'],
		icon: iconEl,
		supports: {
			html: false,
			align: ['wide', 'full']
		},

		transforms: {
			from: [
				{
					type: 'shortcode',
					tag: tag,
					attributes: {
						shortcode: {
							type: 'string',
							shortcode: function (named) {
								return JSON.stringify(named.named);
							}
						}
					}
				},
				// Works for WP >= 5.4, which is the current release. Will not work for older versions, so this will be uncommented
				// once WP 5.6 is out. Photonic is compatible with WP upto 2 versions old, and WP 4.9.
				{
					type: 'shortcode',
					tag: 'gallery',
					isMatch: function (attr) {
						var layouts = ['square', 'circle', 'random', 'mosaic', 'masonry', 'strip-above', 'strip-below', 'strip-right', 'no-strip'];
						var providers = ['flickr', 'smugmug', 'google', 'zenfolio', 'instagram'];
						return (attr.named.style !== undefined && layouts.indexOf(attr.named.style) >= 0 && attr.named.type === undefined) ||
							(attr.named.type !== undefined && providers.indexOf(attr.named.type) >= 0 && attr.named.layout !== undefined && layouts.indexOf(attr.named.layout) >= 0);
					},
					attributes: {
						shortcode: {
							type: 'string',
							shortcode: function (named) {
								return JSON.stringify(named.named);
							}
						}
					}
				},
				{
					type: 'block',
					blocks: ['core/gallery'],
					transform: function (attributes) {
						var images = attributes.images;
						var ids = '';
						Array.prototype.forEach.call(images, function (image) {
							ids += image.id + ',';
						});
						if (ids.length > 0) {
							ids = ids.slice(0, -1);
						}
						var sc = {
							type: 'wp',
							ids: ids
						};
						return wp.blocks.createBlock('photonic/gallery', {
							shortcode: JSON.stringify(sc)
						});
					}
				}
			]
		},

		attributes: {
			shortcode: {
				type: 'string'
			}
		},

		/**
		 * Called when Gutenberg initially loads the block.
		 */
		edit: function (props) {
			var markup = [], iconClass = '';
			var shortcode = props.attributes.shortcode || '{}';
			shortcode = JSON.parse(shortcode);

			var providers = {
				'wp': 'WordPress',
				'flickr': 'Flickr',
				'smugmug': 'SmugMug',
				'google': 'Google Photos',
				'picasa': 'Picasa',
				'zenfolio': 'Zenfolio',
				'instagram': 'Instagram'
			};
			var source;

			if (JSON.stringify(shortcode) !== JSON.stringify({}) && (shortcode.type === undefined || shortcode.type === 'default')) {
				iconClass = 'photonic-wp';
				source = 'wp';
			}
			else if (shortcode.type !== undefined && ['wp', 'flickr', 'smugmug', 'google', 'picasa', 'zenfolio', 'instagram'].indexOf(shortcode.type) > -1) {
				iconClass = 'photonic-' + shortcode.type;
				source = shortcode.type;
			}

			var title = iconClass === '' ? __('Add Photonic Gallery', 'photonic') : __('Edit Photonic Gallery', 'photonic') + ' (' + __('Source: ', 'photonic') + providers[source] + ')';

			var openFlow = function () {
				photonicBlockProperties = props;
				tb_show(title, Photonic_Gutenberg_JS.flow_url);
			};

			markup.push(
				el('div', {key: 'photonic-placeholder', className: 'photonic-gallery'},
					el('a', {className: 'photonic-placeholder-icon photonic ' + iconClass, onClick: openFlow}),
					title)
			);

			return (markup);
		},

		/**
		 * Called when Gutenberg "saves" the block to post_content
		 */
		save: function (props) {
			return null;
		}
	});
})(window.wp);
