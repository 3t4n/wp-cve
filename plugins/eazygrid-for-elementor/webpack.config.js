const fs = require("fs");
const path = require("path");

const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const CssMinimizerPlugin = require("css-minimizer-webpack-plugin");
const RemoveEmptyScriptsPlugin = require("webpack-remove-empty-scripts");
const TerserPlugin = require("terser-webpack-plugin");
const WebpackObfuscator = require("webpack-obfuscator");

// change these variables to fit your project
const jsPath = "./assets/dev/js";
const cssPath = "./assets/dev/sass";

const jsPathAdmin = "./assets/dev/admin/js";
const cssPathAdmin = "./assets/dev/admin/sass";

const outputPath = "./assets";

let entryPoints = {};
let excludesWebpackObfuscator = [];

// Load front-end JS
fs.readdirSync(jsPath).forEach((file) => {
  const filename = file.split(".").slice(0, -1).join(".");
  const fileExt = path.extname(file);
  if (fileExt === ".js" && !filename.startsWith("_")) {
    entryPoints["js/" + filename] = jsPath + "/" + file;
    entryPoints["js/" + filename + ".min"] = jsPath + "/" + file;
	excludesWebpackObfuscator.push("js/" + filename + ".js");
  }
});

// Load forn-end CSS
entryPoints["css/main"] = [];
entryPoints["css/main.min"] = [];
fs.readdirSync(cssPath).forEach((file) => {
  const filename = file.split(".").slice(0, -1).join(".");
  const fileExt = path.extname(file);
  if (fileExt === ".scss" && !filename.startsWith("_")) {
    // entryPoints["css/widgets/" + filename] = cssPath + "/" + file;
    // entryPoints["css/widgets/" + filename + ".min"] = cssPath + "/" + file;
    entryPoints["css/" + filename] = cssPath + "/" + file;
    entryPoints["css/" + filename + ".min"] = cssPath + "/" + file;
    entryPoints["css/main"].push(cssPath + "/" + file);
    entryPoints["css/main.min"].push(cssPath + "/" + file);
  }
});

// Load Admin JS
fs.readdirSync(jsPathAdmin).forEach((file) => {
  const filename = file.split(".").slice(0, -1).join(".");
  const fileExt = path.extname(file);
  if (fileExt === ".js" && !filename.startsWith("_")) {
    entryPoints["admin/js/" + filename] = jsPathAdmin + "/" + file;
    entryPoints["admin/js/" + filename + ".min"] = jsPathAdmin + "/" + file;
	excludesWebpackObfuscator.push("admin/js/" + filename + ".js");
  }
});

// Load Admin CSS
// let editor = [];
fs.readdirSync(cssPathAdmin).forEach((file) => {
  const filename = file.split(".").slice(0, -1).join(".");
  const fileExt = path.extname(file);
  if (fileExt === ".scss" && !filename.startsWith("_")) {
    entryPoints["admin/css/" + filename] = cssPathAdmin + "/" + file;
    entryPoints["admin/css/" + filename + ".min"] = cssPathAdmin + "/" + file;
    // editor.push(cssPathAdmin + "/" + file);
  }
});
// entryPoints["admin/css/editor"] = editor;

// Hard code this to production but can be adapted to accept args to change env.
// const mode = 'development';
module.exports = (env, argv) => {
  const config = [
    {
      mode: argv.mode,
	//   devtool: 'eval-source-map',
      entry: entryPoints,
      output: {
        path: path.resolve(__dirname, outputPath),
        filename: "[name].js",
      },
      module: {
        rules: [
          {
            test: /\.js$/,
            exclude: /(node_modules|bower_components)/,
            use: {
              loader: "babel-loader",
              options: {
                presets: ["@babel/preset-env"],
              },
            },
          },
          {
            // regex to match scss and css files
            test: /\.s?[c]ss$/i,
            use: [
              MiniCssExtractPlugin.loader,
              {
                loader: "css-loader",
                options: { url: false },
              },
              "postcss-loader",
              "sass-loader",
            ],
          },
          {
            // regex to match sass files
            test: /\.sass$/i,
            use: [
              MiniCssExtractPlugin.loader,
              {
                loader: "css-loader",
                options: { url: false },
              },
              "postcss-loader",
              "sass-loader",
            ],
          },
        ],
      },
      optimization: {
        minimize: true,
        minimizer: [
          new CssMinimizerPlugin({
            test: /\.min\.css$/,
            minimizerOptions: {
              preset: [
                "default",
                {
                  discardComments: { removeAll: true },
                },
              ],
            },
          }),
          new TerserPlugin({
            parallel: 4,
            extractComments: false,
            // test: /\.min.js(\?.*)?$/i, //only minify min.js file
            test: /\.js(\?.*)?$/i, //minify all js file
          }),
        ],
      },
      plugins: [
        new RemoveEmptyScriptsPlugin(),
        new MiniCssExtractPlugin({
          filename: "[name].css",
        }),
        // TODO: Need to think again because of wp org rules
        // new WebpackObfuscator(
        //   {
        //     rotateStringArray: true,
        //   },
		//   excludesWebpackObfuscator
		// //   ["js/extension-advanced-tooltip.js", "admin/js/admin.js"]
        // //   ["!js/**.min.js", "!admin/js/**.min.js"]
        // ),
      ],
      externals: {
        jquery: "jQuery",
      },
    },
  ];

  return config;
};

// module.exports =
