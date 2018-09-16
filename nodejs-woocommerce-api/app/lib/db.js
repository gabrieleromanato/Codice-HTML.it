'use strict';

class db {
    constructor() {
        this.client = require('mongodb').MongoClient;
    }

    connect() {
        return this.client.connect('mongodb://user:password@127.0.0.1/database');
    }
}

module.exports = db;