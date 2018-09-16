'use strict';

const request = require('request');
const fs = require('fs');
const path = require('path');
const ABSPATH = path.dirname(process.mainModule.filename);
const UPLOAD_DIR = ABSPATH + '/public/images/products/';

module.exports = {
    importProductImage: (product) => {
        let featuredImageSrc = product.featured_src;
        let imageName = featuredImageSrc.substring(featuredImageSrc.lastIndexOf('/') + 1);
        let imageSrc = '/public/images/products/' + imageName;

        try {
            request(featuredImageSrc).pipe(fs.createWriteStream(UPLOAD_DIR + imageName));
            return imageSrc;
        } catch(e) {
            return null;
        }
    }
};