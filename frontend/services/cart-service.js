// Simple cart service (no async/promises). Uses existing REST endpoints and works with image_url when available.
(function(global){
    var CartService = {
        state: {
            userId: null,
            items: [],
            totals: { total_price: 0, total_items: 0 }
        },

        init: function(opts) {
            this.state.userId = opts.userId;
            this.listSelector = opts.listSelector || '#cart-items';
            this.totalSelector = opts.totalSelector || '#cart-total';
            this.emptySelector = opts.emptySelector || '#cart-empty';
            this.onChange = opts.onChange;
            this.bindButtons();
            this.load();
        },
            
        attachListener: function(id, event, handler) {
            var el = document.getElementById(id);
            if (el) {
                el.removeEventListener(event, handler);
                el.addEventListener(event, handler);
            }
        },

        load: function() {
            var self = this;
            if (!self.state.userId) {
                console.warn('CartService: userId not provided');
                return;
            }

            // Load items
            RestClient.get('cart/' + self.state.userId, function(data){
                self.state.items = Array.isArray(data) ? data : (data ? [data] : []);
                self.render();
                if (self.onChange) self.onChange(self.state);
            }, function(err){ console.error(err); });

            // Load totals
            RestClient.get('cart/total/' + self.state.userId, function(data){
                self.state.totals = data || { total_price: 0, total_items: 0 };
                self.render();
                if (self.onChange) self.onChange(self.state);
            }, function(err){ console.error(err); });
        },

        addItem: function(productId, quantity, unitPrice, imageUrl) {
            var self = this;
            var payload = {
                user_id: self.state.userId,
                product_id: productId,
                quantity: quantity,
                unit_price: unitPrice
            };
            if (imageUrl) payload.image_url = imageUrl; // forward-compatible if backend stores it

            RestClient.post('cart', payload, function(){
                self.load();
                if (window.toastr) toastr.success('Added to cart');
            }, function(err){
                console.error(err);
                if (window.toastr) toastr.error('Could not add to cart');
            });
        },

        updateItem: function(productId, quantity) {
            var self = this;
            var payload = {
                user_id: self.state.userId,
                product_id: productId,
                quantity: quantity
            };
            RestClient.put('cart', payload, function(){ self.load(); }, function(err){ console.error(err); });
        },

        removeItem: function(productId) {
            var self = this;
            RestClient.delete('cart/item/' + self.state.userId + '/' + productId, {}, function(){ self.load(); }, function(err){ console.error(err); });
        },

        clear: function() {
            var self = this;
            RestClient.delete('cart/' + self.state.userId, {}, function(){ self.load(); }, function(err){ console.error(err); });
        },

        render: function() {
            var listEl = document.querySelector(this.listSelector);
            var totalEl = document.querySelector(this.totalSelector);
            var emptyEl = document.querySelector(this.emptySelector);
            if (!listEl) return;

            if (!this.state.items.length) {
                listEl.innerHTML = '';
                if (emptyEl) emptyEl.style.display = 'block';
                if (totalEl) totalEl.textContent = 'Total: $0.00 (0 items)';
                return;
            }

            if (emptyEl) emptyEl.style.display = 'none';

            var html = '';
            for (var i = 0; i < this.state.items.length; i++) {
                var item = this.state.items[i];
                var img = item.image_url ? '<img class="cart-img" src="' + item.image_url + '" alt="' + (item.product_name || 'Product') + '">' : '';
                html += '<div class="cart-item">' +
                        img +
                        '<div class="cart-details">' +
                            '<h3>' + (item.product_name || ('Product #' + item.product_id)) + '</h3>' +
                            '<p>Quantity: ' + (item.quantity || 1) + '</p>' +
                            '<p>Price: $' + (item.unit_price || item.price || 0) + '</p>' +
                        '</div>' +
                    '</div>';
            }
            listEl.innerHTML = html;

            if (totalEl) {
                var price = this.state.totals.total_price || 0;
                var count = this.state.totals.total_items || this.state.items.length;
                totalEl.textContent = 'Total: $' + price + ' (' + count + ' items)';
            }
        },

        bindButtons: function() {
            var self = this;
            var buttons = [
                { id: 'btn-cart-pirelli', productId: 1, price: 330, img: './assets/images/pirelli.webp' },
                { id: 'btn-cart-continental', productId: 2, price: 200, img: './assets/images/continental.webp' },
                { id: 'btn-cart-kumho', productId: 3, price: 150, img: './assets/images/kumho.webp' },
                { id: 'btn-cart-turbo', productId: 4, price: 1890, img: './assets/images/turbo.webp' },
                { id: 'btn-cart-intake', productId: 5, price: 985, img: './assets/images/intake.webp' },
                { id: 'btn-cart-exhaust', productId: 6, price: 2750, img: './assets/images/exhaust.webp' },
                { id: 'btn-cart-spoiler', productId: 7, price: 750, img: './assets/images/spoiler.webp' },
                { id: 'btn-cart-frontlip', productId: 8, price: 450, img: './assets/images/frontlip.webp' },
                { id: 'btn-cart-rearlip', productId: 9, price: 750, img: './assets/images/rearlip.webp' }
            ];

            var clickHandler = function(config) {
                return function(e) {
                    e.preventDefault();
                    if (!self.state.userId) {
                        alert('Please log in to add items to cart.');
                        return;
                    }
                    self.addItem(config.productId, 1, config.price, config.img);
                };
            };

            for (var i = 0; i < buttons.length; i++) {
                var cfg = buttons[i];
                this.attachListener(cfg.id, 'click', clickHandler(cfg));
            }
        }
    };

    // Auto-init when DOM is ready
    document.addEventListener('DOMContentLoaded', function(){
        var userId = parseInt(localStorage.getItem('user_id'));
        var service = Object.create(CartService);
        service.init({ userId: userId });
        global.CartService = service;
    });

})(window);
