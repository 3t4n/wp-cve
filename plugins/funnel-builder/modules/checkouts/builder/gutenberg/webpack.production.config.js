const MiniCSSExtractPlugin = require('mini-css-extract-plugin');
const BundleAnalyzerPlugin = require('webpack-bundle-analyzer').BundleAnalyzerPlugin;
const TerserPlugin = require('terser-webpack-plugin');
const DependencyExtractionWebpackPlugin = require('@wordpress/dependency-extraction-webpack-plugin');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const path = require('path');
const webpack = require("webpack");

module.exports = {
    // define entry file and output
    mode: 'production',
    entry: {
        'wfacp-block-editor': '/src/index.js',
		'wfacp-block-front': '/src/frontend.js',
    },
    output: {
        path: path.resolve('dist'),
        filename: '[name].js',
        chunkFilename: `[name].[contenthash].js`,
    },
    plugins: [
        new CleanWebpackPlugin(),
        new MiniCSSExtractPlugin({
            filename: '[name].css',
            ignoreOrder: true,
            chunkFilename: '[id].[contenthash].css',
        }),
        new BundleAnalyzerPlugin(),
        new DependencyExtractionWebpackPlugin({injectPolyfill: true}),
        new webpack.DefinePlugin({envMode: 'production'})
    ],
    optimization: {
        minimizer: [
            new TerserPlugin({
                cache: true,
                parallel: true,
                sourceMap: false,
                terserOptions: {
                    output: {
                        comments: /translators:/i,
                    },
                },
                extractComments: false,
            }),
        ],
        splitChunks: {
            automaticNameDelimiter: '--',
            cacheGroups: {
                styles: {
                    name: 'styles',
                    test: /\.css$/,
                    chunks: 'all',
                    enforce: true,
                },
            },
        }
    },
    resolve: {
        alias: {
            WFACP: path.resolve(__dirname, 'src/'),
        },
    },
    module: {
        rules: [
            {
                test: /\.jsx?$/,
                loader: 'babel-loader',
                exclude: /node_modules/,
                options: {
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
                    MiniCSSExtractPlugin.loader,
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
};