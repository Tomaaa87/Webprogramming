var ShopService = {
    state: {
        products: [],
        page: 'all', 
        container: null 
    },

    categoryMap: {
        'gume': 1,
        'motor': 2,
        'body': 3
    },

    init: function() {
        this.state.page = this.detectPage();
        this.state.container = this.pickContainer();
        
        if (!this.state.container) return;
        this.fetchProducts();
    },

    detectPage: function() {
        var path = window.location.pathname.toLowerCase();
        var heading = document.querySelector('.shop-section h2');
        var text = heading ? heading.textContent.toLowerCase() : '';

        if (path.includes('gume') || text.includes('tire')) return 'gume';
        if (path.includes('motor') || text.includes('engine')) return 'motor';
        if (path.includes('body') || text.includes('body')) return 'body';

        return 'all';
    },

    pickContainer: function() {
        var selector = this.state.page === 'gume' ? '#categories-gume' :
                       this.state.page === 'motor' ? '#categories-motor' :
                       this.state.page === 'body' ? '#categories-body' : null;
        
        return document.querySelector(selector) || document.querySelector('.categories');
    },

    fetchProducts: function() {
        var self = this;
        if (typeof RestClient === 'undefined') return;

        RestClient.get('products/public', function(data) {
            self.state.products = Array.isArray(data) ? data : [];
            self.render();
        }, function(err) {
            if (err.status === 200 && err.responseText) {
                try {
                    var data = JSON.parse(err.responseText.trim());
                    self.state.products = Array.isArray(data) ? data : [];
                    self.render();
                    return; 
                } catch (parseError) {
                    
                }
            }
            self.state.container.innerHTML = '<div class="col-12"><p>Unable to load products.</p></div>';
        });
    },

    filterForPage: function() {
        var currentPage = this.state.page;
        if (currentPage === 'all') return this.state.products;

        var targetId = this.categoryMap[currentPage];
        if (!targetId) return this.state.products;

        return this.state.products.filter(function(p){ 
            return p.category_id == targetId; 
        });
    },

    render: function() {
        var container = this.state.container;
        var items = this.filterForPage();

        if (!items || !items.length) {
            container.innerHTML = '<div class="col-12"><p>No products available in this category.</p></div>';
            return;
        }

        var html = items.map(function(p){
            var price = p.price || 0;
            
            var imgSrc = p.image_url;

            var imgHtml = `<img src="${imgSrc}">`;
                                 

            return `
                <div class="category" data-product-card="${p.id}">
                    ${imgHtml}
                    <h3>${p.name || 'Product'}</h3>
                    <p>${p.description || ''}</p>
                    <button class="accordion-toggle" 
                            data-product-id="${p.id}" 
                            data-price="${price}" data-img="${imgSrc || ''}">
                        Add to cart: ${price} Euros
                    </button>
                </div>
            `;
        }).join('');

        container.innerHTML = html;

        if (window.CartService && typeof window.CartService.attachAddToCartButtons === 'function') {
            window.CartService.attachAddToCartButtons();
        }
    }
};

document.addEventListener('DOMContentLoaded', function(){
    ShopService.init(); 
    window.ShopService = ShopService;
});