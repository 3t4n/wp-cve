const path = require('path');

const isProduction = process.argv.includes('-p');
const baseConfig = {
  entry: {
    main: path.resolve(__dirname, './src/blocks.js'),
    post: path.resolve(__dirname, './src/post.js'),
  },
  mode: isProduction ? 'production' : 'development',
  output: {
    filename: '[name].js',
  },
  module: {
    rules: [
      {
        test: /\.jsx?$/,
        exclude: /(node_modules)/,
        use: {
          loader: 'babel-loader',
          options: {
            cacheDirectory: true,
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

module.exports = isProduction
  ? baseConfig
  : {
    ...baseConfig,
    devServer: {
      contentBase: 'https://wordpressdefault.lndo.site',
      host: 'wordpressdefault.lndo.site',
      proxy: {
        '/': 'http://wordpressdefault.lndo.site',
      },
    },
    devtool: 'inline-source-map',
  };
