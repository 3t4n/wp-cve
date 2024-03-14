const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );
const path = require("path");

//[ 'plugin-admin', 'plugin-block', 'plugin-public', 'plugin-settings' ].forEach(
//	( script ) =>
//		( entry[ script ] = path.resolve(
//			process.cwd(),
//			`assets/src/${ script }.js`
//		) )
//);

module.exports = {
	...defaultConfig,
	entry: "/V5/src/index.js",
	output: {
		filename: "index.js",
		path: path.resolve(__dirname, "admin/js/V5JS"),
	},
	externals: {
		react: 'React',
		'react-dom': 'ReactDOM',
	},
};
