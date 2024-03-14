module.exports = {
    chainWebpack: config => {
        config.module.rule('html').test(/\.html$/).use('raw-loader').loader('raw-loader')
    }
}