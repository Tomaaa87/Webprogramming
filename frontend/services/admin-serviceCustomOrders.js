var AdminServiceCustomOrders = {
    init: function() {
        console.log("AdminServiceCustomOrders initialized");
        
        this.attachListener('admin-list-custom-orders', 'click', this.listCustomOrders);
        this.attachListener('admin-custom-orders-by-user', 'click', this.openCustomOrdersByUserModal);
        this.attachListener('admin-custom-order-by-id', 'click', this.openCustomOrderByIdModal);
        this.attachListener('admin-insert-custom-order', 'click', this.openInsertCustomOrderModal);
        this.attachListener('admin-update-custom-order', 'click', this.openUpdateCustomOrderModal);
        this.attachListener('admin-update-custom-order-status', 'click', this.openUpdateCustomOrderStatusModal);
        this.attachListener('admin-delete-custom-order', 'click', this.openDeleteCustomOrderModal);

        this.attachListener('form-custom-orders-by-user', 'submit', this.submitCustomOrdersByUser);
        this.attachListener('form-custom-order-by-id', 'submit', this.submitCustomOrderById);
        this.attachListener('form-insert-custom-order', 'submit', this.submitInsertCustomOrder);
        this.attachListener('form-update-custom-order', 'submit', this.submitUpdateCustomOrder);
        this.attachListener('form-update-custom-order-status', 'submit', this.submitUpdateCustomOrderStatus);
        this.attachListener('form-delete-custom-order', 'submit', this.submitDeleteCustomOrder);

        // zatvaranje modala 
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

    openCustomOrdersByUserModal: function() { AdminServiceCustomOrders.openModal('modal-custom-orders-by-user'); },
    openCustomOrderByIdModal: function() { AdminServiceCustomOrders.openModal('modal-custom-order-by-id'); },
    openInsertCustomOrderModal: function() { AdminServiceCustomOrders.openModal('modal-insert-custom-order'); },
    openUpdateCustomOrderModal: function() { AdminServiceCustomOrders.openModal('modal-update-custom-order'); },
    openUpdateCustomOrderStatusModal: function() { AdminServiceCustomOrders.openModal('modal-update-custom-order-status'); },
    openDeleteCustomOrderModal: function() { AdminServiceCustomOrders.openModal('modal-delete-custom-order'); },

    submitCustomOrdersByUser: function(e) {
        e.preventDefault();
        const userId = document.getElementById('input-custom-orders-user-id').value;
        AdminServiceCustomOrders.customOrdersByUser(userId);
        document.getElementById('modal-custom-orders-by-user').style.display = 'none';
    },

    submitCustomOrderById: function(e) {
        e.preventDefault();
        const id = document.getElementById('input-custom-order-id-val').value;
        AdminServiceCustomOrders.getCustomOrderById(id);
        document.getElementById('modal-custom-order-by-id').style.display = 'none';
    },

    submitInsertCustomOrder: function(e) {
        e.preventDefault();
        const data = {
            user_id: parseInt(document.getElementById('input-insert-co-user-id').value),
            title: document.getElementById('input-insert-co-title').value,
            details: document.getElementById('input-insert-co-details').value,
            estimated_price: parseFloat(document.getElementById('input-insert-co-price').value),
            category: document.getElementById('input-insert-co-category').value
        };
        AdminServiceCustomOrders.insertCustomOrder(data);
        document.getElementById('modal-insert-custom-order').style.display = 'none';
    },

    submitUpdateCustomOrder: function(e) {
        e.preventDefault();
        const id = document.getElementById('input-update-co-id').value;
        const data = {};
        
        const title = document.getElementById('input-update-co-title').value;
        if (title) data.title = title;

        const details = document.getElementById('input-update-co-details').value;
        if (details) data.details = details;

        const price = document.getElementById('input-update-co-price').value;
        if (price) data.estimated_price = parseFloat(price);

        if (Object.keys(data).length === 0) {
            alert("No changes entered.");
            return;
        }

        AdminServiceCustomOrders.updateCustomOrder(id, data);
        document.getElementById('modal-update-custom-order').style.display = 'none';
    },

    submitUpdateCustomOrderStatus: function(e) {
        e.preventDefault();
        const id = document.getElementById('input-update-status-id').value;
        const status = document.getElementById('input-update-status-val').value;
        const data = {
            status: status
        };
        AdminServiceCustomOrders.updateCustomOrderStatus(id, data);
        document.getElementById('modal-update-custom-order-status').style.display = 'none';
    },

    submitDeleteCustomOrder: function(e) {
        e.preventDefault();
        const id = document.getElementById('input-delete-co-id').value;
        AdminServiceCustomOrders.deleteCustomOrder(id);
        document.getElementById('modal-delete-custom-order').style.display = 'none';
    },

    openModal: function(modalId) {
        const modal = document.getElementById(modalId);
        const inputs = modal.querySelectorAll('input');
        inputs.forEach(input => input.value = '');
        const textareas = modal.querySelectorAll('textarea');
        textareas.forEach(textarea => textarea.value = '');
        const selects = modal.querySelectorAll('select');
        selects.forEach(select => select.selectedIndex = 0);
        modal.style.display = 'flex';
    },

    listCustomOrders: function() {
        adminSetStatus('Loading all custom orders...');
        RestClient.get('custom-orders', function(data) {
            adminSetStatus('');
            AdminServiceCustomOrders.renderTable(data);
        }, function(error) {
            adminSetStatus('Error loading custom orders', 'error');
            console.error(error);
        });
    },

    customOrdersByUser: function(userId) {
        adminSetStatus('Loading custom orders for user...');
        RestClient.get('custom-orders/user/' + userId, function(data) {
            adminSetStatus('');
            AdminServiceCustomOrders.renderTable(data);
        }, function(error) {
            adminSetStatus('Error loading custom orders', 'error');
            console.error(error);
        });
    },

    getCustomOrderById: function(id) {
        adminSetStatus('Loading custom order...');
        RestClient.get('custom-orders/' + id, function(data) {
            adminSetStatus('');
            AdminServiceCustomOrders.renderTable(data);
        }, function(error) {
            adminSetStatus('Error loading custom order', 'error');
            console.error(error);
        });
    },

    insertCustomOrder: function(data) {
        adminSetStatus('Inserting custom order...');
        RestClient.post('custom-orders', data, function(response) {
            adminSetStatus('Custom order inserted successfully', 'success');
            AdminServiceCustomOrders.listCustomOrders();
        }, function(error) {
            adminSetStatus('Error inserting custom order', 'error');
            console.error(error);
        });
    },

    updateCustomOrder: function(id, data) {
        adminSetStatus('Updating custom order...');
        RestClient.put('custom-orders/' + id, data, function(response) {
            adminSetStatus('Custom order updated successfully', 'success');
            AdminServiceCustomOrders.listCustomOrders();
        }, function(error) {
            adminSetStatus('Error updating custom order', 'error');
            console.error(error);
        });
    },

    updateCustomOrderStatus: function(id, data) {
        adminSetStatus('Updating status...');
        RestClient.patch('custom-orders/' + id + '/status', data, function(response) {
            adminSetStatus('Status updated successfully', 'success');
            AdminServiceCustomOrders.listCustomOrders();
        }, function(error) {
            adminSetStatus('Error updating status', 'error');
            console.error(error);
        });
    },

    deleteCustomOrder: function(id) {
        adminSetStatus('Deleting custom order...');
        RestClient.delete('custom-orders/' + id, {}, function(response) {
            adminSetStatus('Custom order deleted successfully', 'success');
            AdminServiceCustomOrders.listCustomOrders();
        }, function(error) {
            adminSetStatus('Error deleting custom order', 'error');
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
