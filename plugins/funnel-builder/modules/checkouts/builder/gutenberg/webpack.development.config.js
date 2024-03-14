const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const LodashModuleReplacementPlugin = require('lodash-webpack-plugin');
const DependencyExtractionWebpackPlugin = require('@wordpress/dependency-extraction-webpack-plugin');
const webpack = require("webpack");

module.exports = {
    // define entry file and output
    mode: 'development',
    entry: {
        'wfacp-block-editor': '/src/index.js',
		'wfacp-block-front': '/src/frontend.js',
    },
    output: {
        path: path.resolve('dist'),
        filename: '[name].js',
        publicPath: 'http://localhost:9011/'
    },
    plugins: [
        new MiniCssExtractPlugin({
            filename: '[name].css',
            ignoreOrder: true,
        }),
        new LodashModuleReplacementPlugin({
            shorthands: true
        }),
        new webpack.DefinePlugin({envMode: 'development'}),
        new DependencyExtractionWebpackPlugin({injectPolyfill: true}),
    ],
    module: {
        rules: [
            {
                test: /\.jsx?$/,
                loader: 'babel-loader',
                exclude: /node_modules/,
                options: {
                    plugins: ['lodash'],
                    presets: ['@babel/preset-env']
                }
            },
            {
                test: /\.svg$/i,
                loader: 'html-loader',
            },
            {
                test: /\.s?css$/,
                use: [
                    MiniCssExtractPlugin.loader,
                    'css-loader',
                    {
                        loader: 'sass-loader',
                    },
                ]
            },
            {
                test: /\.(woff|woff2|eot|ttf|otf)$/,
                use: [
                    'file-loader',
                ],
            },
        ]
    },
    resolve: {
        alias: {
            WFACP: path.resolve(__dirname, 'src/'),
        },
    },
    devServer: {
        disableHostCheck: true,
        port: 9011,
        headers: {
            "Access-Control-Allow-Origin": "*",
            "Access-Control-Allow-Methods": "GET, POST, PUT, DELETE, PATCH, OPTIONS",
            "Access-Control-Allow-Headers": "X-Requested-With, content-type, Authorization"
        }
    }
};