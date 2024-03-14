module.exports = {
	entry: './lib/modules/block/block.js',
	output: {
		path: __dirname,
		filename: 'lib/modules/block/build/block.build.js',
	},
	module: {
		rules: [
			{
				test: /\.js$/,
				exclude: /(node_modules)/,
				use: {
					loader: 'babel-loader',
					options: {
						presets: [ '@wordpress/babel-preset-default' ],
					},
				},
			},
		],
	},
	externals: {
		react: 'React',
		'react-dom': 'ReactDOM',
	},
};