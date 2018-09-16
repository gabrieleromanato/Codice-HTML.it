'use strict';

module.exports = {
    index: (req, res) => {
      if(!req.cookies.authenticated) {  
        res.render('index', {
            title: 'Login | WooCommerce Node'
        });
       } else {
           res.redirect('/dashboard');
       }  
    },
    login: (req, res) => {
        let email = req.body.email;
        let password = req.body.password;
        let pwdHash = req.crypto.createHash('md5').update(password).digest('hex');

        
        req.db.connect().then(client => {
            let db = client.db('database');    
            let users = db.collection('users');    
            users.count({email: email, password: pwdHash}, (err, num) => {
                if(!err) {
                    if( num === 1 ) {
                        let expires = 7 * 24 * 3600000;
                        res.cookie('authenticated', '1', {maxAge: expires, httpOnly: true});
                        res.redirect('/dashboard'); 
                    } else {
                        res.redirect('/');
                    }    
                }
            });
        }).catch(err => {
            console.log(err);    
        });
    },
    logout: (req, res) => {
        if(req.cookies.authenticated) {  
            res.clearCookie('authenticated');
            res.redirect('/');
        } else {
            res.redirect('/');
        }
    },
    dashboard: (req, res) => {
        if(req.cookies.authenticated) {  
            res.render('dashboard', {
                title: 'Dashboard | WooCommerce Node'
            });
           } else {
               res.redirect('/');
           }  
    },
    products: async (req, res) => {
        if(req.cookies.authenticated) {
            try {
                let data = await req.API.products();
                res.render('products', {
                    title: 'Products | WooCommerce Node',
                    products: data.products
                });
            } catch(err) {
                res.redirect('/dashboard');
            }
        } else {
            res.redirect('/');
        }
    },
    product: async (req, res) => {
        if(req.cookies.authenticated) {
            try {
                let data = await req.API.categories();
                res.render('product', {
                    title: 'New product | WooCommerce Node',
                    categories: data.product_categories
                });
            } catch(err) {
                res.redirect('/dashboard');
            }
        } else {
            res.redirect('/');
        }
    },
    createProduct: (req, res) => {
        let form = req.body;
        let data = {
            name: form.name,
            type: 'simple',
            regular_price: form.regular_price,
            description: form.description,
            short_description: form.short_description,
            categories: [
                {
                    id: parseInt(form.category, 10)
                }
            ]
        };
        req.API.product(data).then(success => {
            res.redirect('/dashboard/products');
        }).catch(err => {
           res.json(err);
        });
    },
    singleProduct: (req, res) => {
        if(req.cookies.authenticated) {
            let id = parseInt(req.params.id, 10);
            req.db.connect().then(client => {
                let db = client.db('database');
                let products = db.collection('products');
                products.findOne({id: id}).then(prod => {
                    res.render('single-product', {
                        title: prod.title + ' | WooCommerce Node',
                        product: prod
                    });
                }).catch(err => {
                    res.redirect('/dashboard');
                });
            }).catch(err => {
               res.redirect('/dashboard');
            });
        } else {
            res.redirect('/');
        }
    },
    importPage: (req, res) => {
        if(req.cookies.authenticated) {
            let imported = (req.query.imported && parseInt(req.query.imported, 10) === 1);
            req.db.connect().then(client => {
                let db = client.db('database');
                let products = db.collection('products');
                products.find({}).toArray((err, results) => {
                    if(!err) {
                        res.render('import', {
                            title: 'Import | WooCommerce Node',
                            imported: imported,
                            products: results
                        });
                    } else {
                        res.redirect('/dashboard');
                    }
                });
            }).catch(err => {
               res.redirect('/dashboard');
            });
        } else {
            res.redirect('/');
        }
    },
    importProducts: async (req, res) => {
        try {
            let data = await req.API.products();
            let products = data.products;
            req.db.connect().then(client => {
                let db = client.db('database');
                let prods = db.collection('products');
                products.forEach(product => {
                    let id = product.id;
                    prods.count({id: id}, (err, num) => {
                        if(!err) {
                            if( num === 0 ) {
                                let importedImage = req.utils.importProductImage(product);
                                if(importedImage !== null) {
                                    product.featured_src = importedImage;
                                }
                                prods.insert(product);
                            }
                        }
                    });

                });
                res.redirect('/dashboard/import/?imported=1');
            });
        } catch(err) {
            res.redirect('/dashboard/import');
        }
    }
};