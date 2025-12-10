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
 }};
