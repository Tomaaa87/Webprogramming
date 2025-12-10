var AdminServiceProducts = {
    init: function() {
        console.log("AdminServiceProducts initialized");
        // Attach event listeners to buttons using delegation
        $(document).off('click', '#admin-products-by-category').on('click', '#admin-products-by-category', function() {
            AdminServiceProducts.productsByCategory();
        });
        $(document).off('click', '#admin-products-in-stock').on('click', '#admin-products-in-stock', function() {
            AdminServiceProducts.productsInStock();
        });
        $(document).off('click', '#admin-product-by-id').on('click', '#admin-product-by-id', function() {
            AdminServiceProducts.getProductById();
        });
        $(document).off('click', '#admin-products-all').on('click', '#admin-products-all', function() {
            AdminServiceProducts.getAllProducts();
        });
        $(document).off('click', '#admin-add-product').on('click', '#admin-add-product', function() {
            AdminServiceProducts.addProduct();
        });
        $(document).off('click', '#admin-update-product').on('click', '#admin-update-product', function() {
            AdminServiceProducts.updateProduct();
        });
        $(document).off('click', '#admin-update-stock').on('click', '#admin-update-stock', function() {
            AdminServiceProducts.updateStock();
        });
        $(document).off('click', '#admin-delete-product').on('click', '#admin-delete-product', function() {
            AdminServiceProducts.deleteProduct();
        });
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

    productsByCategory: function() {
        var id = prompt("Enter Category ID:");
        if (!id) return;

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

    getProductById: function() {
        var id = prompt("Enter Product ID:");
        if (!id) return;

        adminSetStatus('Loading product...');
        RestClient.get('product/' + id, function(data) {
            adminSetStatus('');
            AdminServiceProducts.renderTable([data]);
        }, function(error) {
            adminSetStatus('Error loading product', 'error');
            console.error(error);
        });
    },

    addProduct: function() {
        var name = prompt("Enter Product Name:");
        if (!name) return;

        var price = prompt("Enter Price:");
        if (!price) return;

        var description = prompt("Enter Description:");
        if (!description) return;

        var category_id = prompt("Enter Category ID:");
        if (!category_id) return;

        var image_url = prompt("Enter Image URL (optional):");

        var data = {
            name: name,
            price: parseFloat(price),
            description: description,
            category_id: parseInt(category_id),
            image_url: image_url || ""
        };

        adminSetStatus('Adding product...');
        RestClient.post('products', data, function(response) {
            adminSetStatus('Product added successfully', 'success');
            AdminServiceProducts.getAllProducts();
        }, function(error) {
            adminSetStatus('Error adding product', 'error');
            console.error(error);
        });
    },

    updateProduct: function() {
        var id = prompt("Enter Product ID to update:");
        if (!id) return;

        var data = {};
        var name = prompt("Enter new Name (leave empty to keep current):");
        if (name) data.name = name;

        var price = prompt("Enter new Price (leave empty to keep current):");
        if (price) data.price = parseFloat(price);

        var description = prompt("Enter new Description (leave empty to keep current):");
        if (description) data.description = description;

        var category_id = prompt("Enter new Category ID (leave empty to keep current):");
        if (category_id) data.category_id = parseInt(category_id);

        var image_url = prompt("Enter new Image URL (leave empty to keep current):");
        if (image_url) data.image_url = image_url;

        if (Object.keys(data).length === 0) {
            alert("No changes entered.");
            return;
        }

        adminSetStatus('Updating product...');
        RestClient.patch('products/' + id, data, function(response) {
            adminSetStatus('Product updated successfully', 'success');
            AdminServiceProducts.getAllProducts();
        }, function(error) {
            adminSetStatus('Error updating product', 'error');
            console.error(error);
        });
    },

    updateStock: function() {
        var id = prompt("Enter Product ID to update stock:");
        if (!id) return;

        var stock = prompt("Enter new Stock quantity:");
        if (!stock) return;

        var data = {
            stock: parseInt(stock)
        };

        adminSetStatus('Updating stock...');
        RestClient.patch('products/' + id + '/stock', data, function(response) {
            adminSetStatus('Stock updated successfully', 'success');
            AdminServiceProducts.getAllProducts();
        }, function(error) {
            adminSetStatus('Error updating stock', 'error');
            console.error(error);
        });
    },

    deleteProduct: function() {
        var id = prompt("Enter Product ID to delete:");
        if (!id) return;

        if (!confirm("Are you sure you want to delete product " + id + "?")) return;

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
