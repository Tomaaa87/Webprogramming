var UserService = {
 getToken: function() {
   return localStorage.getItem("user_token");
 },
 setToken: function(token) {
   localStorage.setItem("user_token", token);
 },
 clearToken: function() {
   localStorage.removeItem("user_token");
 },
 authHeaders: function() {
   var t = UserService.getToken();
   return t ? { "Authentication": t } : {};
 },
 currentUser: function() {
   var t = UserService.getToken();
   return Utils.parseJwt(t)?.user || null;
 },
 isLoggedIn: function() {
   return !!UserService.getToken();
 },
 isAdmin: function() {
   var u = UserService.currentUser();
   return u && (u.role === 'admin');
 },
 init: function () {
   var token = localStorage.getItem("user_token");
   if (token && token !== undefined) {
     window.location.replace("index.html");
   }
 },
 handleLogin: function() {
   var form = document.getElementById("login-form");
   if (!form) return;
   var entity = Object.fromEntries(new FormData(form).entries());
   UserService.login(entity);
 },
 handleRegister: function() {
   var form = document.getElementById("register-form");
   if (!form) return;
   var entity = Object.fromEntries(new FormData(form).entries());
   console.log("Register form data:", entity);
   UserService.register(entity);
 },
 login: function (entity) {
   $.ajax({
     url: Constants.PROJECT_BASE_URL + "auth/login",
     type: "POST",
     data: entity,
     success: function (result) {
       console.log(result);
       UserService.setToken(result.data.token);
       if (window.NavbarService && typeof window.NavbarService.refreshOnLogin === 'function') {
         window.NavbarService.refreshOnLogin();
       }
       window.location.replace("index.html");
     },
     error: function (XMLHttpRequest, textStatus, errorThrown) {
       toastr.error(XMLHttpRequest?.responseText ?  XMLHttpRequest.responseText : 'Error');
     },
   });
 },
 register: function(entity) {
   $.ajax({
     url: Constants.PROJECT_BASE_URL + "auth/register",
     type: "POST",
     data: entity,
     success: function (result) {
       toastr.success("Registered successfully. You can log in now.");
       localStorage.removeItem("user_token");
       window.location.hash ="login";
     },
     error: function (XMLHttpRequest) {
       toastr.error(XMLHttpRequest?.responseText ? XMLHttpRequest.responseText : 'Error');
     }
   });
 },


 logout: function () {
   localStorage.clear();
   if (window.NavbarService && typeof window.NavbarService.refreshOnLogout === 'function') {
     window.NavbarService.refreshOnLogout();
   }
   window.location.hash ="login";
 },

 // Admin-only helpers (simple wrappers)
 listUsers: function(cb){
   $.ajax({
     url: Constants.PROJECT_BASE_URL + "users",
     type: "GET",
     headers: UserService.authHeaders(),
     success: function(r){ cb && cb(r); },
     error: function(x){ toastr.error(x?.responseText || 'Error'); }
   });
 },
 getUserById: function(id, cb){
   $.ajax({
     url: Constants.PROJECT_BASE_URL + "users/"+id,
     type: "GET",
     headers: UserService.authHeaders(),
     success: function(r){ cb && cb(r); },
     error: function(x){ toastr.error(x?.responseText || 'Error'); }
   });
 },
 getUserByEmail: function(email, cb){
   $.ajax({
     url: Constants.PROJECT_BASE_URL + "users/email/"+encodeURIComponent(email),
     type: "GET",
     headers: UserService.authHeaders(),
     success: function(r){ cb && cb(r); },
     error: function(x){ toastr.error(x?.responseText || 'Error'); }
   });
 },
 getUsersByRole: function(role, cb){
   $.ajax({
     url: Constants.PROJECT_BASE_URL + "users/role/"+encodeURIComponent(role),
     type: "GET",
     headers: UserService.authHeaders(),
     success: function(r){ cb && cb(r); },
     error: function(x){ toastr.error(x?.responseText || 'Error'); }
   });
 },
 searchUsers: function(term, cb){
   $.ajax({
     url: Constants.PROJECT_BASE_URL + "users/search/"+encodeURIComponent(term),
     type: "GET",
     headers: UserService.authHeaders(),
     success: function(r){ cb && cb(r); },
     error: function(x){ toastr.error(x?.responseText || 'Error'); }
   });
 },
 createUser: function(payload, cb){
   $.ajax({
     url: Constants.PROJECT_BASE_URL + "users",
     type: "POST",
     headers: Object.assign({"Content-Type":"application/json"}, UserService.authHeaders()),
     data: JSON.stringify(payload),
     success: function(r){ cb && cb(r); toastr.success("User created"); },
     error: function(x){ toastr.error(x?.responseText || 'Error'); }
   });
 },
 updateUser: function(id, payload, cb){
   $.ajax({
     url: Constants.PROJECT_BASE_URL + "users/"+id,
     type: "PUT",
     headers: Object.assign({"Content-Type":"application/json"}, UserService.authHeaders()),
     data: JSON.stringify(payload),
     success: function(r){ cb && cb(r); toastr.success("User updated"); },
     error: function(x){ toastr.error(x?.responseText || 'Error'); }
   });
 },
 deleteUser: function(id, cb){
   $.ajax({
     url: Constants.PROJECT_BASE_URL + "users/"+id,
     type: "DELETE",
     headers: UserService.authHeaders(),
     success: function(r){ cb && cb(r); toastr.success("User deleted"); },
     error: function(x){ toastr.error(x?.responseText || 'Error'); }
   });
 },

 // User Dashboard Methods
 initDashboard: function() {
    var user = UserService.currentUser();
    if (!user) {
        toastr.error("Please login first.");
        window.location.hash = "login";
        return;
    }

    // Bind buttons
    $('#btn-show-update-form').off('click').on('click', function() {
        $('#update-form-container').fadeIn();
        // Pre-fill form with current user data if possible, or fetch it
        UserService.getUserById(user.id, function(data) {
            $('#update-name').val(data.name);
            $('#update-email').val(data.email);
            $('#update-phone').val(data.phone);
            $('#update-address').val(data.address);
        });
    });

    $('#close-update-form').off('click').on('click', function() {
        $('#update-form-container').fadeOut();
    });

    $('#btn-submit-update').off('click').on('click', function() {
        UserService.submitUpdateProfile(user.id);
    });

    // Helper for confirmation modal
    var showConfirmation = function(message, onConfirm) {
        $('#confirmation-message').text(message);
        $('#confirmation-modal').fadeIn();
        
        $('#btn-confirm-yes').off('click').on('click', function() {
            $('#confirmation-modal').fadeOut();
            if (typeof onConfirm === 'function') onConfirm();
        });

        $('#btn-confirm-no, #close-confirmation-modal').off('click').on('click', function() {
            $('#confirmation-modal').fadeOut();
        });
    };

    $('#btn-delete-profile').off('click').on('click', function() {
        showConfirmation("Are you sure you want to delete your profile? This cannot be undone.", function() {
            UserService.deleteUser(user.id, function() {
                UserService.logout();
            });
        });
    });

    $('#btn-get-orders').off('click').on('click', function() {
        UserService.getMyOrders(user.id);
    });

    // Delegation for delete buttons inside the list
    $('#orders-output').off('click', '.btn-delete-user-order').on('click', '.btn-delete-user-order', function() {
        var orderId = $(this).data('id');
        showConfirmation("Are you sure you want to delete this order?", function() {
            $.ajax({
                url: Constants.PROJECT_BASE_URL + "orders/" + orderId,
                type: "DELETE",
                headers: UserService.authHeaders(),
                success: function(r) { 
                    toastr.success("Order deleted"); 
                    $('#btn-get-orders').click(); // Refresh list
                },
                error: function(x) { toastr.error(x?.responseText || 'Error deleting order'); }
            });
        });
    });

    $('#btn-get-custom-orders').off('click').on('click', function() {
        UserService.getMyCustomOrders(user.id);
    });

    $('#custom-orders-output').off('click', '.btn-delete-user-custom-order').on('click', '.btn-delete-user-custom-order', function() {
        var id = $(this).data('id');
        showConfirmation("Are you sure you want to delete this custom order?", function() {
            $.ajax({
                url: Constants.PROJECT_BASE_URL + "custom-orders/" + id,
                type: "DELETE",
                headers: UserService.authHeaders(),
                success: function(r) { 
                    toastr.success("Custom order deleted"); 
                    $('#btn-get-custom-orders').click(); // Refresh list
                },
                error: function(x) { toastr.error(x?.responseText || 'Error deleting custom order'); }
            });
        });
    });
 },

 submitUpdateProfile: function(userId) {
    var data = {};
    var name = $('#update-name').val();
    var email = $('#update-email').val();
    var phone = $('#update-phone').val();
    var address = $('#update-address').val();
    var password = $('#update-password').val();

    if(name) data.name = name;
    if(email) data.email = email;
    if(phone) data.phone = phone;
    if(address) data.address = address;
    if(password) data.password = password;

    if(Object.keys(data).length === 0) {
        toastr.warning("No changes to update.");
        return;
    }

    UserService.updateUser(userId, data, function() {
        $('#update-form-container').fadeOut();
        // Optionally update local token if critical info changed, but usually requires re-login
        toastr.success("Profile updated successfully.");
    });
 },

 getMyOrders: function(userId) {
    $.ajax({
        url: Constants.PROJECT_BASE_URL + "orders/user/" + userId,
        type: "GET",
        headers: UserService.authHeaders(),
        success: function(data) {
            var html = "";
            if(data && data.length > 0) {
                html += "<ul>";
                data.forEach(function(order) {
                    html += "<li>Order #" + order.id + " - Status: " + order.status + " - Total: " + order.total_amount + 
                            " <button class='btn-delete-user-order user-btn btn-danger' style='padding: 2px 5px; font-size: 0.8rem; margin-left: 10px;' data-id='" + order.id + "'>Delete</button></li>";
                });
                html += "</ul>";
            } else {
                html = "You have no orders.";
            }
            $('#orders-output').html(html);
        },
        error: function(x) { 
            console.log(x?.responseText || 'Error fetching orders');
            $('#orders-output').html("You have no orders.");
        }
    });
 },

 getMyCustomOrders: function(userId) {
    $.ajax({
        url: Constants.PROJECT_BASE_URL + "custom-orders/user/" + userId,
        type: "GET",
        headers: UserService.authHeaders(),
        success: function(data) {
            var html = "";
            if(data && data.length > 0) {
                html += "<ul>";
                data.forEach(function(co) {
                    html += "<li>Custom Order #" + co.id + " - " + co.title + " (" + co.category + ") - Est. Price: " + co.estimated_price + 
                            " <button class='btn-delete-user-custom-order user-btn btn-danger' style='padding: 2px 5px; font-size: 0.8rem; margin-left: 10px;' data-id='" + co.id + "'>Delete</button></li>";
                });
                html += "</ul>";
            } else {
                html = "You have no custom orders.";
            }
            $('#custom-orders-output').html(html);
        },
        error: function(x) { 
            console.log(x?.responseText || 'Error fetching custom orders');
            $('#custom-orders-output').html("You have no custom orders.");
        }
    });
 }
};
