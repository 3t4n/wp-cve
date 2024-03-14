
const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );

module.exports = {
	...defaultConfig,
    entry: {
        ...module.entry,
        'canvas-admin': [ './assets/js/admin.js', './assets/scss/index.scss' ],
    },
	module: {
		...defaultConfig.module,
	},
};
