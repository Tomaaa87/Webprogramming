var AdminServiceUsers = {
    init: function() {
        // Attach event listeners to buttons
        $('#admin-list-users').off('click').on('click', function() {
            AdminServiceUsers.listUsers();
        });
        $('#admin-search-users').off('click').on('click', function() {
            AdminServiceUsers.searchUsers();
        });
        $('#admin-users-by-role').off('click').on('click', function() {
            AdminServiceUsers.usersByRole();
        });
        $('#admin-user-by-id').off('click').on('click', function() {
            AdminServiceUsers.getUserById();
        });
        $('#admin-user-by-email').off('click').on('click', function() {
            AdminServiceUsers.getUserByEmail();
        });
        $('#admin-add-user').off('click').on('click', function() {
            AdminServiceUsers.addUser();
        });
        $('#admin-update-user').off('click').on('click', function() {
            AdminServiceUsers.updateUser();
        });
        $('#admin-delete-user').off('click').on('click', function() {
            AdminServiceUsers.deleteUser();
        });
    },

    listUsers: function() {
        adminSetStatus('Loading users...');
        RestClient.get('users', function(data) {
            adminSetStatus('');
            AdminServiceUsers.renderTable(data);
        }, function(error) {
            adminSetStatus('Error loading users', 'error');
            console.error(error);
        });
    },

    searchUsers: function() {
        var term = prompt("Enter search term (name or email):");
        if (!term) return;
        
        adminSetStatus('Searching users...');
        RestClient.get('users/search/' + term, function(data) {
            adminSetStatus('');
            AdminServiceUsers.renderTable(data);
        }, function(error) {
            adminSetStatus('Error searching users', 'error');
            console.error(error);
        });
    },

    usersByRole: function() {
        var role = prompt("Enter role (e.g., user, admin):");
        if (!role) return;

        adminSetStatus('Loading users by role...');
        RestClient.get('users/role/' + role, function(data) {
            adminSetStatus('');
            AdminServiceUsers.renderTable(data);
        }, function(error) {
            adminSetStatus('Error loading users by role', 'error');
            console.error(error);
        });
    },

    getUserById: function() {
        var id = prompt("Enter User ID:");
        if (!id) return;

        adminSetStatus('Loading user...');
        RestClient.get('users/' + id, function(data) {
            adminSetStatus('');
            AdminServiceUsers.renderTable([data]);
        }, function(error) {
            adminSetStatus('Error loading user', 'error');
            console.error(error);
        });
    },

    getUserByEmail: function() {
        var email = prompt("Enter User Email:");
        if (!email) return;

        adminSetStatus('Loading user...');
        RestClient.get('users/email/' + email, function(data) {
            adminSetStatus('');
            AdminServiceUsers.renderTable([data]);
        }, function(error) {
            adminSetStatus('Error loading user', 'error');
            console.error(error);
        });
    },

    addUser: function() {
        var name = prompt("Enter Name:");
        if (!name) return;

        var email = prompt("Enter Email:");
        if (!email) return;

        var password = prompt("Enter Password:");
        if (!password) return;

        var role = prompt("Enter Role (user/admin):", "user");
        if (!role) role = "user";

        var data = {
            name: name,
            email: email,
            password: password,
            role: role
        };

        adminSetStatus('Adding user...');
        RestClient.post('users', data, function(response) {
            adminSetStatus('User added successfully', 'success');
            AdminServiceUsers.listUsers();
        }, function(error) {
            adminSetStatus('Error adding user', 'error');
            console.error(error);
        });
    },

    updateUser: function() {
        var id = prompt("Enter User ID to update:");
        if (!id) return;

        var data = {};
        var name = prompt("Enter new Name (leave empty to keep current):");
        if (name) data.name = name;

        var email = prompt("Enter new Email (leave empty to keep current):");
        if (email) data.email = email;

        var password = prompt("Enter new Password (leave empty to keep current):");
        if (password) data.password = password;

        var role = prompt("Enter new Role (leave empty to keep current):");
        if (role) data.role = role;

        if (Object.keys(data).length === 0) {
            alert("No changes entered.");
            return;
        }

        adminSetStatus('Updating user...');
        RestClient.put('users/' + id, data, function(response) {
            adminSetStatus('User updated successfully', 'success');
            AdminServiceUsers.listUsers();
        }, function(error) {
            adminSetStatus('Error updating user', 'error');
            console.error(error);
        });
    },

    deleteUser: function() {
        var id = prompt("Enter User ID to delete:");
        if (!id) return;

        if (!confirm("Are you sure you want to delete user " + id + "?")) return;

        adminSetStatus('Deleting user...');
        RestClient.delete('users/' + id, {}, function(response) {
            adminSetStatus('User deleted successfully', 'success');
            AdminServiceUsers.listUsers();
        }, function(error) {
            adminSetStatus('Error deleting user', 'error');
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

        // Get headers from the first object
        var headers = Object.keys(rows[0]);
        
        // Convert objects to arrays of values for adminRenderTable
        var tableRows = rows.map(function(obj) {
            return headers.map(function(key) {
                return obj[key];
            });
        });

        adminRenderTable(headers, tableRows);
    }
};
