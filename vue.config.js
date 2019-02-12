const webpack = require('webpack');

module.exports = {
    baseUrl: '/',
    configureWebpack: {
        plugins: [
            new webpack.ProvidePlugin({
                jQuery: 'jquery'
            })
        ]
    }
};
