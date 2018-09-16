'use strict';

const WC = require('woocommerce');

const WooCommerce = new WC({
    url: 'https://site.tld',
    ssl: true,
    consumerKey: 'key',
    secret: 'secret'
});


class API {
    static products() {
       return WooCommerce.get('/products');
    }
    static categories() {
        return WooCommerce.get('/products/categories');
    }

    static getInstance() {
        return WooCommerce;
    }

    static product(data) {
        return WooCommerce.post('products', data);
    }
}    

module.exports = API;