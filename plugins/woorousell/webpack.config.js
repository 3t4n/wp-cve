const path = require( 'path' );
const webpack = require( 'webpack' );
const ExtractTextPlugin = require( 'extract-text-webpack-plugin' );
const BrowserSyncPlugin = require( 'browser-sync-webpack-plugin' );

const coreCSSPlugin = new ExtractTextPlugin( {
    filename: './assets/css/core.css'
} );

const backendCSSPlugin = new ExtractTextPlugin( {
    filename: './assets/css/backend.min.css'
} );

// Configuration for the ExtractTextPlugin.
const extractConfig = {
    use: [
        { loader: 'raw-loader' },
        {
            loader: 'postcss-loader',
            options: {
                plugins: [ require( 'autoprefixer' ) ],
            },
        },
        {
            loader: 'sass-loader'
        },
    ],
};

module.exports = {
    entry: {
        './assets/js/core' : './index.js'
    },
    output: {
        path: path.resolve( __dirname ),
        filename: '[name].js',
    },
    watch: true,
    devtool: 'cheap-eval-source-map',
    module: {
        rules: [
            {
                test: /\.js$/,
                exclude: /(node_modules|bower_components|assets)/
            },
            {
                use: ['style-loader', 'css-loader', 'sass-loader'],
                test: /\.scss$/,
                exclude: /node_modules/,
            },
            {
                test: /core\.scss$/,
                use: coreCSSPlugin.extract( extractConfig ),
            },
            {
                test: /backend\.scss$/,
                use: backendCSSPlugin.extract( extractConfig ),
            },
        ],
    },
    plugins: [
        coreCSSPlugin,
        backendCSSPlugin,
        // new BrowserSyncPlugin({
        //     host: 'localhost',
        //     port: '3333'
        // })
    ],
};