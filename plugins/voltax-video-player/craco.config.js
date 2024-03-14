module.exports = {
    webpack: {
        configure: {
            output: {
                filename: 'static/js/[name].[hash].js',
            },
            optimization: {
                runtimeChunk: false,
                splitChunks: {
                    chunks() {
                        return false
                    },
                },
            },
        },
    },
    plugins: [
        {
            plugin: {
                overrideWebpackConfig: ({ webpackConfig }) => {
                    webpackConfig.plugins[5].options.filename = 'static/css/[name].[hash].css'
                    return webpackConfig
                },
            },
            options: {},
        },
    ],
}
