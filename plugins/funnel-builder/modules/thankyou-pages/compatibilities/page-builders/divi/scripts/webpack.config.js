const path = require('path');
let settings = {
    // define entry file and output
    entry: path.resolve('./main.js'),
    output: {
        path: path.resolve('./'),
        filename: 'loader.js',
    },
    optimization: {
        minimize: false
    },
    module: {
        rules: [
            {
                test: /\.jsx?$/,
                loader: 'babel-loader',
                exclude: /node_modules/,
                options: {
                    plugins: ['lodash', '@babel/plugin-proposal-class-properties'],
                    presets: ['@babel/preset-env']
                }
            }
        ]
    }
};
module.exports = settings;