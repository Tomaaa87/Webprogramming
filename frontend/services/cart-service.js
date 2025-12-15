//komentar za test pusha na github
var CartService = {
    state: {
        userId: null,
        items: [],
        totals: { total_price: 0, total_items: 0 },
        products: {}
    },

    init: function() {
        this.state.userId = this.resolveUserId();
        
        
        this.setupEventListeners();
        
        
        this.loadProducts(this.refreshCart.bind(this));
    },

    resolveUserId: function() {
        if (window.UserService && typeof UserService.currentUser === 'function') {
            var u = UserService.currentUser();
            if (u && (u.id || u.user_id)) return u.id || u.user_id;
        }
        var token = localStorage.getItem('user_token');
        var parsed = token ? Utils.parseJwt(token) : null;
        if (parsed) {
            if (parsed.user && (parsed.user.id || parsed.user.user_id)) return parsed.user.id || parsed.user.user_id;
            if (parsed.id || parsed.user_id || parsed.uid || parsed.sub) return parsed.id || parsed.user_id || parsed.uid || parsed.sub;
        }
        var legacy = localStorage.getItem('user_id');
        return legacy ? parseInt(legacy) : null;
    },

    
    setupEventListeners: function() {
        var self = this;

        document.body.addEventListener('click', function(e) {
            var target = e.target;

           
            var addBtn = target.closest('.accordion-toggle') || target.closest('[data-product-id]');
            if (addBtn && addBtn.textContent.includes('Add to cart')) {
              
                if(addBtn.hasAttribute('data-product-id')) {
                    e.preventDefault();
                    self.handleAddClick(addBtn);
                    return;
                }
            }

            
            if (target.classList.contains('cart-qty-btn') || target.classList.contains('cart-remove')) {
                e.preventDefault();
                var action = target.getAttribute('data-action');
                var pid = parseInt(target.getAttribute('data-product-id'));
                
                if (action === 'inc') self.updateItem(pid, self.getQty(pid) + 1);
                else if (action === 'dec') {
                    var next = self.getQty(pid) - 1;
                    if (next <= 0) self.removeItem(pid); else self.updateItem(pid, next);
                }
                else if (action === 'remove') self.removeItem(pid);
                return;
            }

            if (target.id === 'cart-clear') {
                e.preventDefault();
                if (!self.state.userId) return;
                self.clear();
            }
       
            if (target.id === 'cart-checkout') {
                e.preventDefault();
                if (!self.state.userId) return;
                
                
                if (self.state.items.length === 0) {
                    alert("Your cart is empty!");
                    return;
                }

                // Simulate Purchase
                if (confirm("Confirm purchase of " + self.state.totals.total_items + " items for " + self.state.totals.grand_total + " Euros?")) {
                    // We reuse the 'clear' endpoint because 'buying' empties the cart
                    RestClient.delete('cart/' + self.state.userId, {}, function() {
                        alert("🎉 Purchase Successful! Thank you for shopping.");
                        self.refreshCart();
                        // Optional: Redirect to shop
                        // window.location.hash = '#shop'; 
                    }, function(err) {
                        console.error(err);
                        alert("Checkout failed. Please try again.");
                    });
                }
            }

        });
    },

    handleAddClick: function(btn) {
        if (!this.state.userId) {
            alert('Please log in to add items to cart.');
            return;
        }
        var pid = parseInt(btn.getAttribute('data-product-id'));
        var product = this.state.products[pid] || {};
        
        // Prioritize getting the image from the button attribute (ShopService logic), then the product list
        var img = btn.getAttribute('data-img') || product.image_url || '';
        var price = parseFloat(btn.getAttribute('data-price')) || parseFloat(product.price || 0);

        this.addToCart(pid, { price: price, image: img });
    },

    addToCart: function(productId, opts) {
        var self = this;
        var img = opts.image || '';
        var price = opts.price || 0;

        var payload = {
            user_id: self.state.userId,
            product_id: productId,
            quantity: 1,
            unit_price: price,
           
            image_url: img 
        };

        RestClient.post('cart', payload, function(){
            self.refreshCart();
         toastr.success('Added to cart');
        }, function(err){
            console.error(err);
        });
    },

    loadProducts: function(done) {
        var self = this;
        RestClient.get('products/public', function(data){
            if (Array.isArray(data)) {
                data.forEach(function(p){ self.state.products[p.id] = p; });
            }
            if (done) done();
        }, function(){ if (done) done(); });
    },

    refreshCart: function() {
        if (!this.state.userId) return;
        this.fetchItems();
        this.fetchTotals();
    },

    fetchItems: function() {
        var self = this;
        RestClient.get('cart/' + self.state.userId, function(data){
            self.state.items = Array.isArray(data) ? data : (data ? [data] : []);
            self.render();
        });
    },

    fetchTotals: function() {
        var self = this;
        RestClient.get('cart/total/' + self.state.userId, function(data){
            self.state.totals = data || { total_price: 0, total_items: 0 };
            self.render();
        });
    },

    
    updateItem: function(productId, quantity) {
        var self = this;
        var payload = { user_id: self.state.userId, product_id: productId, quantity: quantity };
        RestClient.put('cart', payload, function(){ self.refreshCart(); });
    },

    removeItem: function(productId) {
        var self = this;
        RestClient.delete('cart/item/' + self.state.userId + '/' + productId, {}, function(){ self.refreshCart(); });
    },

    clear: function() {
        var self = this;
        RestClient.delete('cart/' + self.state.userId, {}, function(){ self.refreshCart(); });
    },

  getQty: function(pid) {
        var found = this.state.items.find(item => item.product_id == pid);
        return found ? parseInt(found.quantity) : 0;
    },

    
    render: function() {
        
        var listEl = document.getElementById('cart-items');
        var totalEl = document.getElementById('cart-total');
        var emptyEl = document.getElementById('cart-empty');

        
        if (!listEl) return; 

        
        if (!this.state.items.length) {
            listEl.innerHTML = '';
            if (emptyEl) emptyEl.style.display = 'block';
            if (totalEl) totalEl.textContent = 'Total: $0 (0 items)';
            return;
        }

        if (emptyEl) emptyEl.style.display = 'none';

        var html = this.state.items.map(function(item){
       
            var imgSrc = item.image_url || 'https://via.placeholder.com/100?text=No+Img';

            return `
                <div class="cart-item">
                    <img class="cart-img" src="${imgSrc}" 
                         style="width:80px; height:80px; object-fit:cover; margin-right:15px;"
                         onerror="this.src='https://via.placeholder.com/80?text=Error'">
                    
                    <div class="cart-details" style="flex:1">
                        <h3>${item.product_name || 'Product'}</h3>
                        <p>Price: ${item.unit_price} Euros</p>
                        
                        <div class="cart-qty-row" style="margin:10px 0;">
                            <button class="cart-qty-btn" data-action="dec" data-product-id="${item.product_id}">-</button>
                            <span class="cart-qty-val" style="margin:0 10px; font-weight:bold;">${item.quantity}</span>
                            <button class="cart-qty-btn" data-action="inc" data-product-id="${item.product_id}">+</button>
                        </div>
                        
                        <button class="cart-remove btn btn-danger btn-sm" data-action="remove" data-product-id="${item.product_id}">Remove</button>
                    </div>
                    <div class="cart-line-total" style="font-weight:bold;">
                        ${(item.quantity * item.unit_price).toFixed(2)} €
                    </div>
                </div>
            `;
        }).join('');

        listEl.innerHTML = html;

        if (totalEl) {
            var price = this.state.totals.grand_total || 0;
            var count = this.state.totals.items_count || 0;
            totalEl.textContent = `Total: ${parseFloat(price).toFixed(2)} Euros (${count} items)`;
        }
    }
};


document.addEventListener('DOMContentLoaded', function(){
    CartService.init(); 
    window.CartService = CartService;
});