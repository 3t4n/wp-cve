const path = require('path');
//require('webpack');

// Create the entry points - one per lightbox
const entryPoints = {
	solo: {
		baguettebox: ['../include/js/front-end/src/Entries/BaguetteBox.js'],
		bigpicture: ['../include/js/front-end/src/Entries/BigPicture.js'],
		colorbox: ['../include/js/front-end/src/Entries/Colorbox.js'],
		fancybox: ['../include/js/front-end/src/Entries/Fancybox.js'],
		fancybox2: ['../include/js/front-end/src/Entries/Fancybox2.js'],
		fancybox3: ['../include/js/front-end/src/Entries/Fancybox3.js'],
		fancybox4: ['../include/js/front-end/src/Entries/Fancybox4.js'],
		featherlight: ['../include/js/front-end/src/Entries/Featherlight.js'],
		glightbox: ['../include/js/front-end/src/Entries/GLightbox.js'],
		imagelightbox: ['../include/js/front-end/src/Entries/ImageLightbox.js'],
		lightcase: ['../include/js/front-end/src/Entries/Lightcase.js'],
		lightgallery: ['../include/js/front-end/src/Entries/LightGallery.js'],
		magnific: ['../include/js/front-end/src/Entries/Magnific.js'],
		none: ['../include/js/front-end/src/Entries/None.js'],
		photoswipe: ['../include/js/front-end/src/Entries/PhotoSwipe.js'],
		photoswipe5: ['../include/js/front-end/src/Entries/PhotoSwipe5.js'],
		prettyphoto: ['../include/js/front-end/src/Entries/PrettyPhoto.js'],
		spotlight: ['../include/js/front-end/src/Entries/Spotlight.js'],
		strip: ['../include/js/front-end/src/Entries/Strip.js'],
		swipebox: ['../include/js/front-end/src/Entries/Swipebox.js'],
		thickbox: ['../include/js/front-end/src/Entries/Thickbox.js'],
		venobox: ['../include/js/front-end/src/Entries/VenoBox.js'],
	}
};

const esmEntryPoints = {
/*
	esm: {
		photoswipe5: ['../include/js/front-end/src/Entries/PhotoSwipe5.js'],
	}
*/
};

const toImport = ['baguettebox', 'photoswipe', 'spotlight'];
const toIgnore = ['none', 'fancybox2', 'thickbox'];

// Plugins
const plugins = [
//	"@babel/plugin-transform-arrow-functions",
	"@babel/plugin-transform-async-to-generator",
	"@babel/plugin-transform-modules-commonjs",
	"@babel/plugin-transform-runtime",
	"@babel/plugin-proposal-class-properties",
	"@babel/plugin-syntax-class-properties"
];

const providerPlugins = {};
providerPlugins['solo'] = {};
providerPlugins['solo-slider'] = {
	Splide: '../../../../ext/splide/splide.js',
};
providerPlugins['combo'] = {
	baguetteBox: '../../../../ext/baguettebox/baguettebox.js',
	BigPicture: '../../../../ext/bigpicture/bigpicture.js',
	GLightbox: '../../../../ext/glightbox/glightbox.js',
	PhotoSwipe: '../../../../ext/photoswipe/photoswipe.js',
	PhotoSwipeUI_Default: '../../../../ext/photoswipe/photoswipe-ui-default.js',
	Spotlight: '../../../../ext/spotlight/spotlight.js',
};
providerPlugins['combo-slider'] = {... providerPlugins['combo']};
providerPlugins['combo-slider'].Splide = '../../../../ext/splide/splide.js';

const moduleTypes = [
	{
		type: 'es8',
		module: {
			rules: [{
				test: /\.js$/, // Look for any .js files.
				// exclude: /node_modules/, // Exclude the node_modules folder.
				// Use babel loader to transpile the JS files.
				use: {
					loader: 'babel-loader',
					options: {
						presets: [
							["@babel/preset-env", {
								targets: {
									esmodules: true
								},
								// useBuiltIns: "entry"
							}]
						],
						plugins: plugins
					}
				}
			}],
		},
		folder: 'out',
		target: 'es8',
	}
];

const environments = [
	{
		type: 'dev',
		mode: 'development',
		extension: '',
		// sourceMap: 'source-map',
		sourceMap: false,
	},
	{
		type: 'prod',
		mode: 'production',
		extension: '.min',
		sourceMap: false,
	}
];

/**
 * For each lightbox, we generate ES8 files that combine the Photonic scripts. The lightbox and slider scripts are excluded from this.
 *
 * The DEV file is unminified, while the PROD file is minified.
 *
 */
const config = [];
Object.entries(entryPoints).forEach(([combination, entry]) => {
	moduleTypes.forEach((module) => {
		environments.forEach((env) => {
			// Configuration object
			// 	-	entry:				Lists all entry points
			//	-	output:				Generates an output file per entry
			//	-		.filename:		[name] uses an object key from "entry" as a file name
			//	-		.path:			Specifies where the file should be written
			config.push({
				name: module.type + '-' + env.type + '-' + combination,
				mode: env.mode,
				devtool: env.sourceMap,// 'source-map',
				entry: entry,
				module: module.module,
				target: module.target,
				output: {
					filename: 'photonic-[name]' + env.extension + '.js',
					path: path.join(__dirname, '/../include/js/front-end/' + module.folder)
				},
				plugins: [
//					new webpack.ProvidePlugin(providerPlugins[combination]),
				],
				externals: {
					jquery: 'jQuery',
				},
			});
		});
	});
});

Object.entries(esmEntryPoints).forEach(([combination, entry]) => {
	moduleTypes.forEach((module) => {
		environments.forEach((env) => {
			config.push({
				name: module.type + '-' + env.type + '-' + combination,
				mode: env.mode,
				devtool: env.sourceMap,// 'source-map',
				entry: entry,
				module: module.module,
				target: module.target,
				output: {
					filename: 'photonic-[name]' + env.extension + '.js',
					path: path.join(__dirname, '/../include/js/front-end/' + module.folder),
				},
				resolve: {
					alias: {
						"photoswipe": '/../include/ext/photoswipe5/photoswipe5.js',
						"photoswipeLightbox": '/../include/ext/photoswipe5/photoswipe5-lightbox.js',
					}
				},
			});
		});
	});
});

// Export the config object.
module.exports = config;
