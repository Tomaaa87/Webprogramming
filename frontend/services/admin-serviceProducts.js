var AdminServiceProducts = {
    init: function() {
        console.log("AdminServiceProducts initialized");
        
        this.attachListener('admin-products-by-category', 'click', this.openProductsByCategoryModal);
        this.attachListener('admin-products-in-stock', 'click', this.productsInStock);
        this.attachListener('admin-product-by-id', 'click', this.openProductByIdModal);
        this.attachListener('admin-products-all', 'click', this.getAllProducts);
        this.attachListener('admin-add-product', 'click', this.openAddProductModal);
        this.attachListener('admin-update-product', 'click', this.openUpdateProductModal);
        this.attachListener('admin-update-stock', 'click', this.openUpdateStockModal);
        this.attachListener('admin-delete-product', 'click', this.openDeleteProductModal);

        this.attachListener('form-products-by-category', 'submit', this.submitProductsByCategory);
        this.attachListener('form-product-by-id', 'submit', this.submitProductById);
        this.attachListener('form-add-product', 'submit', this.submitAddProduct);
        this.attachListener('form-update-product', 'submit', this.submitUpdateProduct);
        this.attachListener('form-update-stock', 'submit', this.submitUpdateStock);
        this.attachListener('form-delete-product', 'submit', this.submitDeleteProduct);

        // Modal closing logic
        var closeBtns = document.querySelectorAll('.close-modal');
        closeBtns.forEach(function(btn) {
            btn.onclick = function() {
                var modalId = this.getAttribute('data-modal');
                if (modalId) {
                    document.getElementById(modalId).style.display = 'none';
                } else {
                    var overlay = this.closest('.modal-overlay');
                    if (overlay) overlay.style.display = 'none';
                }
            };
        });

        var overlays = document.querySelectorAll('.modal-overlay');
        overlays.forEach(function(overlay) {
            overlay.onclick = function(e) {
                if (e.target === this) {
                    this.style.display = 'none';
                }
            };
        });
    },

    attachListener: function(id, event, handler) {
        const el = document.getElementById(id);
        if (el) {
            el.removeEventListener(event, handler);
            el.addEventListener(event, handler);
        }
    },

    openProductsByCategoryModal: function() { AdminServiceProducts.openModal('modal-products-by-category'); },
    openProductByIdModal: function() { AdminServiceProducts.openModal('modal-product-by-id'); },
    openAddProductModal: function() { AdminServiceProducts.openModal('modal-add-product'); },
    openUpdateProductModal: function() { AdminServiceProducts.openModal('modal-update-product'); },
    openUpdateStockModal: function() { AdminServiceProducts.openModal('modal-update-stock'); },
    openDeleteProductModal: function() { AdminServiceProducts.openModal('modal-delete-product'); },

    submitProductsByCategory: function(e) {
        e.preventDefault();
        const id = document.getElementById('input-products-by-category-id').value;
        AdminServiceProducts.productsByCategory(id);
        document.getElementById('modal-products-by-category').style.display = 'none';
    },

    submitProductById: function(e) {
        e.preventDefault();
        const id = document.getElementById('input-product-by-id-val').value;
        AdminServiceProducts.getProductById(id);
        document.getElementById('modal-product-by-id').style.display = 'none';
    },

    submitAddProduct: function(e) {
        e.preventDefault();
        const data = {
            name: document.getElementById('input-add-product-name').value,
            price: parseFloat(document.getElementById('input-add-product-price').value),
            description: document.getElementById('input-add-product-description').value,
            category_id: parseInt(document.getElementById('input-add-product-category-id').value),
            image_url: document.getElementById('input-add-product-image-url').value || ""
        };
        AdminServiceProducts.addProduct(data);
        document.getElementById('modal-add-product').style.display = 'none';
    },

    submitUpdateProduct: function(e) {
        e.preventDefault();
        const id = document.getElementById('input-update-product-id').value;
        const data = {};
        
        const name = document.getElementById('input-update-product-name').value;
        if (name) data.name = name;

        const price = document.getElementById('input-update-product-price').value;
        if (price) data.price = parseFloat(price);

        const description = document.getElementById('input-update-product-description').value;
        if (description) data.description = description;

        const category_id = document.getElementById('input-update-product-category-id').value;
        if (category_id) data.category_id = parseInt(category_id);

        const image_url = document.getElementById('input-update-product-image-url').value;
        if (image_url) data.image_url = image_url;

        if (Object.keys(data).length === 0) {
            alert("No changes entered.");
            return;
        }

        AdminServiceProducts.updateProduct(id, data);
        document.getElementById('modal-update-product').style.display = 'none';
    },

    submitUpdateStock: function(e) {
        e.preventDefault();
        const id = document.getElementById('input-update-stock-id').value;
        const stock = document.getElementById('input-update-stock-val').value;
        const data = {
            stock: parseInt(stock)
        };
        AdminServiceProducts.updateStock(id, data);
        document.getElementById('modal-update-stock').style.display = 'none';
    },

    submitDeleteProduct: function(e) {
        e.preventDefault();
        const id = document.getElementById('input-delete-product-id').value;
        AdminServiceProducts.deleteProduct(id);
        document.getElementById('modal-delete-product').style.display = 'none';
    },

    openModal: function(modalId) {
        const modal = document.getElementById(modalId);
        const inputs = modal.querySelectorAll('input');
        inputs.forEach(input => input.value = '');
        modal.style.display = 'flex';
    },

    getAllProducts: function() {
        adminSetStatus('Loading all products...');
        RestClient.get('products/all', function(data) {
            adminSetStatus('');
            AdminServiceProducts.renderTable(data);
        }, function(error) {
            adminSetStatus('Error loading products', 'error');
            console.error(error);
        });
    },

    productsByCategory: function(id) {
        adminSetStatus('Loading products by category...');
        RestClient.get('products/' + id, function(data) {
            adminSetStatus('');
            AdminServiceProducts.renderTable(data);
        }, function(error) {
            adminSetStatus('Error loading products', 'error');
            console.error(error);
        });
    },

    productsInStock: function() {
        adminSetStatus('Loading products in stock...');
        RestClient.get('products/in-stock', function(data) {
            adminSetStatus('');
            AdminServiceProducts.renderTable(data);
        }, function(error) {
            adminSetStatus('Error loading products', 'error');
            console.error(error);
        });
    },

    getProductById: function(id) {
        adminSetStatus('Loading product...');
        RestClient.get('product/' + id, function(data) {
            adminSetStatus('');
            AdminServiceProducts.renderTable([data]);
        }, function(error) {
            adminSetStatus('Error loading product', 'error');
            console.error(error);
        });
    },

    addProduct: function(data) {
        adminSetStatus('Adding product...');
        RestClient.post('products', data, function(response) {
            adminSetStatus('Product added successfully', 'success');
            AdminServiceProducts.getAllProducts();
        }, function(error) {
            adminSetStatus('Error adding product', 'error');
            console.error(error);
        });
    },

    updateProduct: function(id, data) {
        adminSetStatus('Updating product...');
        RestClient.patch('products/' + id, data, function(response) {
            adminSetStatus('Product updated successfully', 'success');
            AdminServiceProducts.getAllProducts();
        }, function(error) {
            adminSetStatus('Error updating product', 'error');
            console.error(error);
        });
    },

    updateStock: function(id, data) {
        adminSetStatus('Updating stock...');
        RestClient.patch('products/' + id + '/stock', data, function(response) {
            adminSetStatus('Stock updated successfully', 'success');
            AdminServiceProducts.getAllProducts();
        }, function(error) {
            adminSetStatus('Error updating stock', 'error');
            console.error(error);
        });
    },

    deleteProduct: function(id) {
        // Note: The confirmation is now implicit in the modal action, 
        // but we could add another confirmation step if desired. 
        // For now, clicking "Delete" in the modal is the confirmation.
        
        adminSetStatus('Deleting product...');
        RestClient.delete('products/' + id, {}, function(response) {
            adminSetStatus('Product deleted successfully', 'success');
            AdminServiceProducts.getAllProducts();
        }, function(error) {
            adminSetStatus('Error deleting product', 'error');
            console.error(error);
        });
    },

    renderTable: function(data) {
        if (!data) {
            adminRenderTable([], []);
            return;
        }
        
        var rows = [];
        if (Array.isArray(data)) {
            rows = data;
        } else {
            rows = [data];
        }

        if (rows.length === 0) {
            adminRenderTable([], []);
            adminSetStatus('No results found');
            return;
        }

        // Get headers from the first object
        var headers = Object.keys(rows[0]);
        
        // Convert objects to arrays of values for adminRenderTable
        var tableRows = rows.map(function(obj) {
            return headers.map(function(key) {
                return obj[key];
            });
        });

        adminRenderTable(headers, tableRows);
    }
};
