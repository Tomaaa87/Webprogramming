var app =$.spapp({
    defaultView:"#main1",
    templateDir:"./views/"
})
var currentCSS = null;

function loadCSS(url) {
    if (currentCSS) {
        document.head.removeChild(currentCSS);
    }
    const link = document.createElement('link');
    link.rel = 'stylesheet';
    link.href = url;
    document.head.appendChild(link);
    currentCSS = link;
}

function loadJS(url, callback) {
    const script = document.createElement('script');
    script.src = url;
    script.onload = callback;
    document.body.appendChild(script);
}
app.route({
    view: "main1",
    onReady: function() {
        console.log("About page created!");
        loadCSS('./assets/css/style.css');
        console.log("About page ready!");
    }
});
app.route({
    view: "reg",
    onReady: function() {
        console.log("About page created!");
        loadCSS('./assets/css/reg.css');
        loadJS('./assets/js/reg.js', function() {
            console.log('test1');
        });

        console.log("About page ready!");
    }
});
app.route({
    view: "login",
    onReady: function() {
        console.log("About page created!");
        loadCSS('./assets/css/reg.css');

        console.log("About page ready!");
    }
});
app.route({
    view: "shop1",
    onReady: function() {
        console.log("About page created!");
        loadCSS('./assets/css/shop.css');
        loadJS('./assets/js/a.js', function() {
            console.log('test1');
        });
           console.log("About page ready!");
    }
});
app.route({
    view: "projects1",
    onReady: function() {
        console.log("About page created!");
        loadCSS('./assets/css/shop.css');
        loadJS('./assets/js/toggleVisibility.js', function() {
            console.log('test1');
        });
        loadJS('./assets/js/projects.js', function() {
            console.log('test1');
        });
        console.log("About page ready!");
    }
});
app.route({
    view: "ourservices1",
    onReady: function() {
        console.log("About page created!");
        loadCSS('./assets/css/services.css');
        loadJS('./assets/js/viewmore.js', function() {
            console.log('test1');
        });
           console.log("About page ready!");
    }
});
app.route({
    view: "gume",
    onReady: function() {
        console.log("About page created!");
        loadCSS('./assets/css/gume.css');
        console.log("About page ready!");
        ShopService.init();
        (function()
{
  if( window.localStorage )
  {
    if( !localStorage.getItem('firstLoad') )
    {
      localStorage['firstLoad'] = true;
      window.location.reload();
    }  
    else
      localStorage.removeItem('firstLoad');
  }
})();
        
    }
});
app.route({
    view: "motor",
    onReady: function() {
        console.log("About page created!");
        loadCSS('./assets/css/motor.css');
        console.log("About page ready!");
        ShopService.init();
        (function()
{
  if( window.localStorage )
  {
    if( !localStorage.getItem('firstLoad') )
    {
      localStorage['firstLoad'] = true;
      window.location.reload();
    }  
    else
      localStorage.removeItem('firstLoad');
  }
})();
        
    }
});
app.route({
    view: "body",
    onReady: function() {
        console.log("About page created!");
        loadCSS('./assets/css/body12.css');
        console.log("About page ready!");
        ShopService.init();
        (function()
{
  if( window.localStorage )
  {
    if( !localStorage.getItem('firstLoad') )
    {
      localStorage['firstLoad'] = true;
      window.location.reload();
    }  
    else
      localStorage.removeItem('firstLoad');
  }
})();
        
    }
});
//https://stackoverflow.com/questions/6985507/one-time-page-refresh-after-first-page-load/28840664#28840664
app.route({
    view: "cart",
    onReady: function() {
        console.log("About page created!");
        loadCSS('./assets/css/shop.css');
        console.log("About page ready!");
        window.CartService.init();
    }
});
app.route({
    view: "custom",
    onReady: function() {
        console.log("About page created!");
        loadCSS('./assets/css/shop.css');
           console.log("About page ready!");
        window.CustomOrderService.init();
        
    }
});
app.route({
    view: "admin",
    onReady: function() {
        console.log("About page created!");
        loadCSS('./assets/css/admin.css');
          loadJS('./assets/js/admin.js');
           console.log("About page ready!");
    }
});
app.route({
    view: "adminUser",
    onReady: function() {
        console.log("About page created!");
        loadCSS('./assets/css/admin.css');
        loadJS('./assets/js/admin.js');
        
        // Re-initialize the service every time the view is loaded
        if (window.AdminServiceUsers) {
            window.AdminServiceUsers.init();
        }
    }

});
app.route({
    view: "adminProducts",
    onReady: function() {
        console.log("About page created!");
        loadCSS('./assets/css/admin.css');
        loadJS('./assets/js/admin.js');
        
         if (window.AdminServiceProducts) {
            window.AdminServiceProducts.init();
        }
    }
});
app.route({
    view: "adminOrder",
    onReady: function() {
        console.log("Admin Order page created!");
        loadCSS('./assets/css/admin.css');
        loadJS('./assets/js/admin.js');
        
        if (window.AdminServiceOrders) {
            window.AdminServiceOrders.init();
        }
    }
});
app.route({
    view: "adminOrderItem",
    onReady: function() {
        console.log("Admin Order Item page created!");
        loadCSS('./assets/css/admin.css');
        loadJS('./assets/js/admin.js');
        
        
        if (window.AdminServiceOrderItems) {
            window.AdminServiceOrderItems.init();
        } 
    }
});
app.route({
    view: "adminCustomOrder",
    onReady: function() {
        console.log("Admin Custom Order page created!");
        loadCSS('./assets/css/admin.css');
        loadJS('./assets/js/admin.js');
        
        if (window.AdminServiceCustomOrders) {
            window.AdminServiceCustomOrders.init();
        } 
    }
});
app.route({
    view: "adminCategory",
    onReady: function() {
        console.log("Admin Category page created!");
        loadCSS('./assets/css/admin.css');
        loadJS('./assets/js/admin.js');
        
        if (window.AdminServiceCategories) {
            window.AdminServiceCategories.init();
        } 
    }
});

app.route({
    view: "adminCart",
    onReady: function() {
        console.log("Admin Cart page created!");
        loadCSS('./assets/css/admin.css');
        loadJS('./assets/js/admin.js');
        
        if (window.AdminServiceCart) {
            window.AdminServiceCart.init();
        } 
    }
});

app.route({
    view: "user",
    onReady: function() {
        console.log("User Dashboard page created!");
        loadCSS('./assets/css/reg.css')
        loadCSS('./assets/css/user.css');
        loadJS('./assets/js/admin.js');
        
        
        if (window.UserService) {
            console.log("Initializing UserService...");
            window.UserService.initDashboard();
        } else {
            console.error("UserService not found!");
        }
    }
});

app.run();

