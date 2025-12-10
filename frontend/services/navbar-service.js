var NavbarService = {
    init: function() {
        this.updateNavbar();
    },

    updateNavbar: function() {
        var token = localStorage.getItem("user_token");
        var role = null;
        
        if (token) {
            var payload = Utils.parseJwt(token);
            if (payload && payload.user) {
                role = payload.user.role;
            }
        }

        var html = '';
        html += '<a href="#main1">Main</a>';
        
        if (!token) {
            html += '<a href="#login">Login</a>';
        }

        html += '<a href="#shop1">Shop</a>';
        html += '<a href="#projects1">Projects</a>';
        html += '<a href="#ourservices1">Service</a>';
        html += '<a href="#cart">Cart</a>';

        if (role === 'admin') {
            html += '<a href="#admin">Admin</a>';
        }

        if (token) {
            html += '<button class="nav-btn" onclick="UserService.logout()" style="background:none; border:none; color:white; font-size:1.2rem; font-weight:bold; text-transform:uppercase; cursor:pointer; padding:12px 20px; font-family:inherit;">LOGOUT</button>';
        }

        $('.hamburger-menu').html(html);
        $('.desktop-menu').html(html);
    },
    
    refreshOnLogin: function() {
        this.updateNavbar();
    },
    
    refreshOnLogout: function() {
        this.updateNavbar();
    }
};

$(document).ready(function() {
    NavbarService.init();
});