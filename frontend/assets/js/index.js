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
        loadJS('./assets/js/weather.js', function() {
            console.log('test1');
        });

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
        loadCSS('./assets/css/projects.css');
        loadJS('./assets/js/toggleVisibility.js', function() {
            console.log('test1');
        });
        loadJS('./assets/js/projects.js', function() {
            console.log('test1');
        });
        loadJS('./assets/js/lighttheme.js', function() {
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
    }
});
app.route({
    view: "motor",
    onReady: function() {
        console.log("About page created!");
        loadCSS('./assets/css/motor.css');
   
           console.log("About page ready!");
    }
});
app.route({
    view: "body",
    onReady: function() {
        console.log("About page created!");
        loadCSS('./assets/css/body12.css');
     
           console.log("About page ready!");
    }
});
app.route({
    view: "cart",
    onReady: function() {
        console.log("About page created!");
        loadCSS('./assets/css/shop.css');
           console.log("About page ready!");
    }
});
app.route({
    view: "custom",
    onReady: function() {
        console.log("About page created!");
        loadCSS('./assets/css/shop.css');
           console.log("About page ready!");
    }
});
app.run();

