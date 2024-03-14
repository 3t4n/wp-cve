let config = {
    module: {},
};
let viewDefaultTemplateCSS = Object.assign({}, config, {
    entry: ['./views/templates/default.scss'],
    module: {
        rules: [
            {
                test: /\.scss$/,
                exclude: /node_modules/,
                use: [ { loader: 'file-loader', options: { outputPath: '../../resources/views/templates/default/', name: '[name].css'} }, 'sass-loader' ]
            }
        ]
    }
})
let viewBasicTemplateCSS = Object.assign({}, config, {
    entry: ['./views/templates/basic.scss'],
    module: {
        rules: [
            {
                test: /\.scss$/,
                exclude: /node_modules/,
                use: [ { loader: 'file-loader', options: { outputPath: '../../resources/views/templates/basic/', name: '[name].css'} }, 'sass-loader' ]
            }
        ]
    }
})
let modernPollsIconFont = Object.assign({}, config, {
    entry: ['./assets/scss/mpp_iconfont.scss'],
    module: {
        rules: [
            {
                test: /\.scss$/,
                exclude: /node_modules/,
                use: [ { loader: 'file-loader', options: { outputPath: '../../resources/assets/css/', name: '[name].css'} }, 'sass-loader' ]
            }
        ]
    }
})
let modernPollsBackendCSS = Object.assign({}, config, {
    entry: ['./assets/scss/modern-polls-backend.scss'],
    module: {
        rules: [
            {
                test: /\.scss$/,
                exclude: /node_modules/,
                use: [ { loader: 'file-loader', options: { outputPath: '../../resources/assets/css/', name: '[name].css'} }, 'sass-loader' ]
            }
        ]
    }
})

module.exports = [
    viewDefaultTemplateCSS, viewBasicTemplateCSS, modernPollsIconFont, modernPollsBackendCSS,
];