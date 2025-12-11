var AdminServiceOrderItems = {
    init: function() {
        console.log("AdminServiceOrderItems initialized");
        
        this.attachListener('admin-list-order-items', 'click', this.openListItemsModal);
        this.attachListener('admin-delete-order-items', 'click', this.openDeleteItemsModal);
        this.attachListener('admin-update-item-quantity', 'click', this.openUpdateItemQtyModal);
        this.attachListener('admin-delete-single-item', 'click', this.openDeleteSingleItemModal);
        this.attachListener('admin-calc-order-total', 'click', this.openCalcTotalModal);
        this.attachListener('admin-item-qty-by-product', 'click', this.openItemQtyByProductModal);

        this.attachListener('form-list-items-by-order', 'submit', this.submitListItemsByOrder);
        this.attachListener('form-delete-items-by-order', 'submit', this.submitDeleteItemsByOrder);
        this.attachListener('form-update-item-quantity', 'submit', this.submitUpdateItemQuantity);
        this.attachListener('form-delete-single-item', 'submit', this.submitDeleteSingleItem);
        this.attachListener('form-calc-order-total', 'submit', this.submitCalcOrderTotal);
        this.attachListener('form-item-qty-by-product', 'submit', this.submitItemQtyByProduct);

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

    openListItemsModal: function() { AdminServiceOrderItems.openModal('modal-list-items-by-order'); },
    openDeleteItemsModal: function() { AdminServiceOrderItems.openModal('modal-delete-items-by-order'); },
    openUpdateItemQtyModal: function() { AdminServiceOrderItems.openModal('modal-update-item-quantity'); },
    openDeleteSingleItemModal: function() { AdminServiceOrderItems.openModal('modal-delete-single-item'); },
    openCalcTotalModal: function() { AdminServiceOrderItems.openModal('modal-calc-order-total'); },
    openItemQtyByProductModal: function() { AdminServiceOrderItems.openModal('modal-item-qty-by-product'); },

    submitListItemsByOrder: function(e) {
        e.preventDefault();
        const orderId = document.getElementById('input-list-items-order-id').value;
        AdminServiceOrderItems.listItemsByOrder(orderId);
        document.getElementById('modal-list-items-by-order').style.display = 'none';
    },

    submitDeleteItemsByOrder: function(e) {
        e.preventDefault();
        const orderId = document.getElementById('input-delete-items-order-id').value;
        AdminServiceOrderItems.deleteItemsByOrder(orderId);
        document.getElementById('modal-delete-items-by-order').style.display = 'none';
    },

    submitUpdateItemQuantity: function(e) {
        e.preventDefault();
        const orderId = document.getElementById('input-update-qty-order-id').value;
        const productName = document.getElementById('input-update-qty-product-name').value;
        const quantity = document.getElementById('input-update-qty-val').value;
        AdminServiceOrderItems.updateItemQuantity(orderId, productName, quantity);
        document.getElementById('modal-update-item-quantity').style.display = 'none';
    },

    submitDeleteSingleItem: function(e) {
        e.preventDefault();
        const itemId = document.getElementById('input-delete-single-item-id').value;
        AdminServiceOrderItems.deleteSingleItem(itemId);
        document.getElementById('modal-delete-single-item').style.display = 'none';
    },

    submitCalcOrderTotal: function(e) {
        e.preventDefault();
        const orderId = document.getElementById('input-calc-total-order-id').value;
        AdminServiceOrderItems.calculateOrderTotal(orderId);
        document.getElementById('modal-calc-order-total').style.display = 'none';
    },

    submitItemQtyByProduct: function(e) {
        e.preventDefault();
        const productId = document.getElementById('input-qty-by-product-id').value;
        AdminServiceOrderItems.itemQtyByProduct(productId);
        document.getElementById('modal-item-qty-by-product').style.display = 'none';
    },

    openModal: function(modalId) {
        const modal = document.getElementById(modalId);
        const inputs = modal.querySelectorAll('input');
        inputs.forEach(input => input.value = '');
        modal.style.display = 'flex';
    },

    listItemsByOrder: function(orderId) {
        adminSetStatus('Loading items for order...');
        RestClient.get('order-items/details/' + orderId, function(data) {
            adminSetStatus('');
            AdminServiceOrderItems.renderTable(data);
        }, function(error) {
            adminSetStatus('Error loading order items', 'error');
            console.error(error);
        });
    },

    deleteItemsByOrder: function(orderId) {
        adminSetStatus('Deleting items...');
        RestClient.delete('order-items/' + orderId, {}, function(response) {
            adminSetStatus('Items deleted successfully', 'success');
            AdminServiceOrderItems.listItemsByOrder(orderId); // Refresh if we were looking at this order
        }, function(error) {
            adminSetStatus('Error deleting items', 'error');
            console.error(error);
        });
    },

    updateItemQuantity: function(orderId, productName, quantity) {
        adminSetStatus('Verifying product in order...');
        
        // Fetch items for this order to find the ID and verify product name
        RestClient.get('order-items/details/' + orderId, function(data) {
            var items = Array.isArray(data) ? data : [data];
            var targetItem = items.find(function(item) {
                return item.product_name.toLowerCase() === productName.toLowerCase();
            });

            if (targetItem) {
                var data = {
                    quantity: parseInt(quantity)
                };

                adminSetStatus('Updating quantity for ' + targetItem.product_name + '...');
                RestClient.patch('order-items/' + targetItem.id, data, function(response) {
                    adminSetStatus('Quantity updated successfully', 'success');
                    AdminServiceOrderItems.listItemsByOrder(orderId);
                }, function(error) {
                    adminSetStatus('Error updating quantity', 'error');
                    console.error(error);
                });
            } else {
                alert("Product '" + productName + "' not found in Order " + orderId);
                adminSetStatus('Product not found in order', 'error');
            }
        }, function(error) {
            adminSetStatus('Error fetching order details', 'error');
            console.error(error);
        });
    },

    deleteSingleItem: function(itemId) {
        adminSetStatus('Deleting item...');
        RestClient.delete('order-items/item/' + itemId, {}, function(response) {
            adminSetStatus('Item deleted successfully', 'success');
        }, function(error) {
            adminSetStatus('Error deleting item', 'error');
            console.error(error);
        });
    },

    calculateOrderTotal: function(orderId) {
        adminSetStatus('Calculating total...');
        RestClient.get('order-items/total/' + orderId, function(data) {
            adminSetStatus('');
            // data should be { total: 123.45 }
            if (data && data.total !== undefined) {
                alert("Total for Order " + orderId + ": " + data.total);
                adminSetStatus("Total: " + data.total, 'success');
            } else {
                adminSetStatus('Could not calculate total', 'error');
            }
        }, function(error) {
            adminSetStatus('Error calculating total', 'error');
            console.error(error);
        });
    },

    itemQtyByProduct: function(productId) {
        adminSetStatus('Getting quantity...');
        RestClient.get('order-items/product/' + productId + '/quantity', function(data) {
            adminSetStatus('');
            // data should be { total_quantity: 50 }
            if (data && data.total_quantity !== undefined) {
                alert("Total Quantity for Product " + productId + ": " + data.total_quantity);
                adminSetStatus("Total Quantity: " + data.total_quantity, 'success');
            } else {
                adminSetStatus('Could not get quantity', 'error');
            }
        }, function(error) {
            adminSetStatus('Error getting quantity', 'error');
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

        var headers = Object.keys(rows[0]);
        var tableRows = rows.map(function(obj) {
            return headers.map(function(key) {
                return obj[key];
            });
        });

        adminRenderTable(headers, tableRows);
    }
};
