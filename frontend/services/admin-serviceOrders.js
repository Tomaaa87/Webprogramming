var AdminServiceOrders = {
    init: function() {
        console.log("AdminServiceOrders initialized");
        
        this.attachListener('admin-list-orders', 'click', this.listOrders);
        this.attachListener('admin-orders-by-user', 'click', this.ordersByUser);
        this.attachListener('admin-orders-by-status', 'click', this.ordersByStatus);
        this.attachListener('admin-recent-orders', 'click', this.recentOrders);
        this.attachListener('admin-order-details', 'click', this.orderDetails);
        this.attachListener('admin-insert-order', 'click', this.insertOrder);
        this.attachListener('admin-update-order-status', 'click', this.updateOrderStatus);
        this.attachListener('admin-delete-order', 'click', this.deleteOrder);

        this.attachListener('insert-order-form', 'submit', this.handleInsertOrderSubmit);
        this.attachListener('form-orders-by-user', 'submit', this.handleOrdersByUserSubmit);
        this.attachListener('form-orders-by-status', 'submit', this.handleOrdersByStatusSubmit);
        this.attachListener('form-recent-orders', 'submit', this.handleRecentOrdersSubmit);
        this.attachListener('form-order-details', 'submit', this.handleOrderDetailsSubmit);
        this.attachListener('form-update-status', 'submit', this.handleUpdateStatusSubmit);
        this.attachListener('form-delete-order', 'submit', this.handleDeleteOrderSubmit);

        this.attachListener('add-item-btn', 'click', this.addItemRow);
        
        this.attachListener('close-insert-order-modal', 'click', function() {
             document.getElementById('insert-order-modal').style.display = 'none';
        });

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

    addItemRow: function() {
        const container = document.getElementById('order-items-container');
        const div = document.createElement('div');
        div.className = 'order-item-row';
        div.style.marginTop = '5px';
        div.innerHTML = `
            <input type="number" placeholder="Product ID" class="item-product-id" required style="width: 100px;">
            <input type="number" placeholder="Qty" class="item-quantity" value="1" min="1" required style="width: 60px;">
            <button type="button" class="remove-item-btn admin-btn-danger" style="padding: 2px 5px;" onclick="this.closest('.order-item-row').remove()">X</button>
        `;
        container.appendChild(div);
    },

    handleInsertOrderSubmit: function(e) {
        e.preventDefault();
        AdminServiceOrders.submitInsertOrder();
    },

    handleOrdersByUserSubmit: function(e) {
        e.preventDefault();
        const id = document.getElementById('input-orders-by-user-id').value;
        document.getElementById('modal-orders-by-user').style.display = 'none';
        AdminServiceOrders.fetchOrdersByUser(id);
    },

    handleOrdersByStatusSubmit: function(e) {
        e.preventDefault();
        const status = document.getElementById('input-orders-by-status-val').value;
        document.getElementById('modal-orders-by-status').style.display = 'none';
        AdminServiceOrders.fetchOrdersByStatus(status);
    },

    handleRecentOrdersSubmit: function(e) {
        e.preventDefault();
        const limit = document.getElementById('input-recent-orders-limit').value;
        document.getElementById('modal-recent-orders').style.display = 'none';
        AdminServiceOrders.fetchRecentOrders(limit);
    },

    handleOrderDetailsSubmit: function(e) {
        e.preventDefault();
        const id = document.getElementById('input-order-details-id').value;
        document.getElementById('modal-order-details').style.display = 'none';
        AdminServiceOrders.fetchOrderDetails(id);
    },

    handleUpdateStatusSubmit: function(e) {
        e.preventDefault();
        const id = document.getElementById('input-update-status-id').value;
        const status = document.getElementById('input-update-status-val').value;
        document.getElementById('modal-update-status').style.display = 'none';
        AdminServiceOrders.submitUpdateOrderStatus(id, status);
    },

    handleDeleteOrderSubmit: function(e) {
        e.preventDefault();
        const id = document.getElementById('input-delete-order-id').value;
        document.getElementById('modal-delete-order').style.display = 'none';
        AdminServiceOrders.submitDeleteOrder(id);
    },

    listOrders: function() {
        adminSetStatus('Loading all orders...');
        RestClient.get('orders', function(data) {
            adminSetStatus('');
            AdminServiceOrders.renderTable(data);
        }, function(error) {
            adminSetStatus('Error loading orders', 'error');
            console.error(error);
        });
    },

    ordersByUser: function() {
        document.getElementById('form-orders-by-user').reset();
        document.getElementById('modal-orders-by-user').style.display = 'flex';
    },

    fetchOrdersByUser: function(userId) {
        if (!userId) return;
        adminSetStatus('Loading orders for user...');
        RestClient.get('orders/user/' + userId, function(data) {
            adminSetStatus('');
            AdminServiceOrders.renderTable(data);
        }, function(error) {
            adminSetStatus('Error loading orders', 'error');
            console.error(error);
        });
    },

    ordersByStatus: function() {
        document.getElementById('form-orders-by-status').reset();
        document.getElementById('modal-orders-by-status').style.display = 'flex';
    },

    fetchOrdersByStatus: function(status) {
        if (!status) return;
        adminSetStatus('Loading orders by status...');
        RestClient.get('orders/status/' + status, function(data) {
            adminSetStatus('');
            AdminServiceOrders.renderTable(data);
        }, function(error) {
            adminSetStatus('Error loading orders', 'error');
            console.error(error);
        });
    },

    recentOrders: function() {
        document.getElementById('form-recent-orders').reset();
        document.getElementById('modal-recent-orders').style.display = 'flex';
    },

    fetchRecentOrders: function(limit) {
        if (!limit) limit = 10;
        adminSetStatus('Loading recent orders...');
        RestClient.get('orders/recent?limit=' + limit, function(data) {
            adminSetStatus('');
            AdminServiceOrders.renderTable(data);
        }, function(error) {
            adminSetStatus('Error loading recent orders', 'error');
            console.error(error);
        });
    },

    orderDetails: function() {
        document.getElementById('form-order-details').reset();
        document.getElementById('modal-order-details').style.display = 'flex';
    },

    fetchOrderDetails: function(orderId) {
        if (!orderId) return;
        adminSetStatus('Loading order details...');
        RestClient.get('orders/' + orderId + '/details', function(data) {
            adminSetStatus('');
            AdminServiceOrders.renderTable([data]); 
        }, function(error) {
            adminSetStatus('Error loading order details', 'error');
            console.error(error);
        });
    },

    insertOrder: function() {
        document.getElementById('insert-order-modal').style.display = 'flex';
        // Reset form
        document.getElementById('insert-order-form').reset();
        document.getElementById('order-items-container').innerHTML = `
            <div class="order-item-row">
                <input type="number" placeholder="Product ID" class="item-product-id" required style="width: 100px;">
                <input type="number" placeholder="Qty" class="item-quantity" value="1" min="1" required style="width: 60px;">
                <button type="button" class="remove-item-btn admin-btn-danger" style="padding: 2px 5px;">X</button>
            </div>
        `;
    },

    submitInsertOrder: function() {
        var userId = document.getElementById('order-user-id').value;
        var status = document.getElementById('order-status').value;
        var items = [];

        const rows = document.querySelectorAll('.order-item-row');
        rows.forEach(row => {
            const pid = row.querySelector('.item-product-id').value;
            const qty = row.querySelector('.item-quantity').value;
            if (pid && qty) {
                items.push({
                    product_id: parseInt(pid),
                    quantity: parseInt(qty)
                });
            }
        });

        if (items.length === 0) {
            alert("Please add at least one item.");
            return;
        }

        var data = {
            user_id: parseInt(userId),
            status: status,
            items: items
        };

        adminSetStatus('Inserting order...');
        RestClient.post('orders', data, function(response) {
            adminSetStatus('Order inserted successfully', 'success');
            document.getElementById('insert-order-modal').style.display = 'none';
            AdminServiceOrders.listOrders();
        }, function(error) {
            adminSetStatus('Error inserting order: ' + (error.responseText || 'Unknown error'), 'error');
            console.error(error);
        });
    },

    updateOrderStatus: function() {
        document.getElementById('form-update-status').reset();
        document.getElementById('modal-update-status').style.display = 'flex';
    },

    submitUpdateOrderStatus: function(orderId, status) {
        if (!orderId || !status) return;

        var data = {
            status: status
        };

        adminSetStatus('Updating order status...');
        RestClient.patch('orders/' + orderId + '/status', data, function(response) {
            adminSetStatus('Order status updated successfully', 'success');
            AdminServiceOrders.listOrders();
        }, function(error) {
            adminSetStatus('Error updating order status', 'error');
            console.error(error);
        });
    },

    deleteOrder: function() {
        document.getElementById('form-delete-order').reset();
        document.getElementById('modal-delete-order').style.display = 'flex';
    },

    submitDeleteOrder: function(id) {
        if (!id) return;
        if (!confirm("Are you sure you want to delete order " + id + "?")) return;

        adminSetStatus('Deleting order...');
        RestClient.delete('orders/' + id, {}, function(response) {
            adminSetStatus('Order deleted successfully', 'success');
            AdminServiceOrders.listOrders();
        }, function(error) {
            adminSetStatus('Error deleting order', 'error');
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
                var val = obj[key];
                if (typeof val === 'object' && val !== null) {
                    return JSON.stringify(val); // Handle nested objects like items in details
                }
                return val;
            });
        });

        adminRenderTable(headers, tableRows);
    }
};
