var AdminServiceCart = {
    init: function() {
        console.log("AdminServiceCart initialized");
        
        this.attachListener('admin-list-carts', 'click', this.listCarts);
        this.attachListener('admin-cart-by-user', 'click', this.openCartByUserModal);
        this.attachListener('admin-add-to-cart', 'click', this.openAddToCartModal);
        this.attachListener('admin-update-cart-item', 'click', this.openUpdateCartItemModal);
        this.attachListener('admin-remove-cart-item', 'click', this.openRemoveCartItemModal);
        this.attachListener('admin-clear-cart', 'click', this.openClearCartModal);

        this.attachListener('form-cart-by-user', 'submit', this.submitCartByUser);
        this.attachListener('form-add-to-cart', 'submit', this.submitAddToCart);
        this.attachListener('form-update-cart-item', 'submit', this.submitUpdateCartItem);
        this.attachListener('form-remove-cart-item', 'submit', this.submitRemoveCartItem);
        this.attachListener('form-clear-cart', 'submit', this.submitClearCart);

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

    openCartByUserModal: function() { AdminServiceCart.openModal('modal-cart-by-user'); },
    openAddToCartModal: function() { AdminServiceCart.openModal('modal-add-to-cart'); },
    openUpdateCartItemModal: function() { AdminServiceCart.openModal('modal-update-cart-item'); },
    openRemoveCartItemModal: function() { AdminServiceCart.openModal('modal-remove-cart-item'); },
    openClearCartModal: function() { AdminServiceCart.openModal('modal-clear-cart'); },

    submitCartByUser: function(e) {
        e.preventDefault();
        const userId = document.getElementById('input-cart-user-id').value;
        AdminServiceCart.cartByUser(userId);
        document.getElementById('modal-cart-by-user').style.display = 'none';
    },

    submitAddToCart: function(e) {
        e.preventDefault();
        const data = {
            user_id: parseInt(document.getElementById('input-add-cart-user-id').value),
            product_id: parseInt(document.getElementById('input-add-cart-product-id').value),
            quantity: parseInt(document.getElementById('input-add-cart-quantity').value)
        };
        const price = document.getElementById('input-add-cart-price').value;
        if (price) {
            data.unit_price = parseFloat(price);
        }
        AdminServiceCart.addToCart(data);
        document.getElementById('modal-add-to-cart').style.display = 'none';
    },

    submitUpdateCartItem: function(e) {
        e.preventDefault();
        const data = {
            user_id: parseInt(document.getElementById('input-update-cart-user-id').value),
            product_id: parseInt(document.getElementById('input-update-cart-product-id').value),
            quantity: parseInt(document.getElementById('input-update-cart-quantity').value)
        };
        AdminServiceCart.updateCartItem(data);
        document.getElementById('modal-update-cart-item').style.display = 'none';
    },

    submitRemoveCartItem: function(e) {
        e.preventDefault();
        const userId = document.getElementById('input-remove-cart-user-id').value;
        const productId = document.getElementById('input-remove-cart-product-id').value;
        AdminServiceCart.removeCartItem(userId, productId);
        document.getElementById('modal-remove-cart-item').style.display = 'none';
    },

    submitClearCart: function(e) {
        e.preventDefault();
        const userId = document.getElementById('input-clear-cart-user-id').value;
        AdminServiceCart.clearCart(userId);
        document.getElementById('modal-clear-cart').style.display = 'none';
    },

    openModal: function(modalId) {
        const modal = document.getElementById(modalId);
        const inputs = modal.querySelectorAll('input');
        inputs.forEach(input => input.value = '');
        if (modalId === 'modal-add-to-cart') {
            document.getElementById('input-add-cart-quantity').value = '1';
        }
        modal.style.display = 'flex';
    },

    listCarts: function() {
        adminSetStatus('Loading all carts...');
        RestClient.get('cart', function(data) {
            adminSetStatus('');
            AdminServiceCart.renderTable(data);
        }, function(error) {
            adminSetStatus('Error loading carts', 'error');
            console.error(error);
        });
    },

    cartByUser: function(userId) {
        adminSetStatus('Loading cart for user...');
        RestClient.get('cart/' + userId, function(data) {
            adminSetStatus('');
            AdminServiceCart.renderTable(data);
        }, function(error) {
            adminSetStatus('Error loading user cart', 'error');
            console.error(error);
        });
    },

    addToCart: function(data) {
        adminSetStatus('Adding to cart...');
        RestClient.post('cart', data, function(response) {
            adminSetStatus('Item added to cart successfully', 'success');
            AdminServiceCart.cartByUser(data.user_id);
        }, function(error) {
            var msg = error.responseJSON && error.responseJSON.message ? error.responseJSON.message : 'Error adding to cart';
            adminSetStatus(msg, 'error');
            console.error(error);
        });
    },

    updateCartItem: function(data) {
        adminSetStatus('Updating cart item...');
        RestClient.put('cart', data, function(response) {
            adminSetStatus('Cart item updated successfully', 'success');
            AdminServiceCart.cartByUser(data.user_id);
        }, function(error) {
            var msg = error.responseJSON && error.responseJSON.message ? error.responseJSON.message : 'Error updating cart item';
            adminSetStatus(msg, 'error');
            console.error(error);
        });
    },

    removeCartItem: function(userId, productId) {
        adminSetStatus('Removing item...');
        RestClient.delete('cart/item/' + userId + '/' + productId, {}, function(response) {
            adminSetStatus('Item removed successfully', 'success');
            AdminServiceCart.cartByUser(userId);
        }, function(error) {
            adminSetStatus('Error removing item', 'error');
            console.error(error);
        });
    },

    clearCart: function(userId) {
        adminSetStatus('Clearing cart...');
        RestClient.delete('cart/' + userId, {}, function(response) {
            adminSetStatus('Cart cleared successfully', 'success');
            AdminServiceCart.cartByUser(userId);
        }, function(error) {
            adminSetStatus('Error clearing cart', 'error');
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
