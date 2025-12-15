var AdminServiceUsers = {
    init: function() {
        console.log("AdminServiceUsers initialized");
        
        this.attachListener('admin-list-users', 'click', this.listUsers);
        this.attachListener('admin-search-users', 'click', this.openSearchModal);
        this.attachListener('admin-users-by-role', 'click', this.openRoleModal);
        this.attachListener('admin-user-by-id', 'click', this.openUserByIdModal);
        this.attachListener('admin-user-by-email', 'click', this.openUserByEmailModal);
        this.attachListener('admin-add-user', 'click', this.openAddUserModal);
        this.attachListener('admin-delete-user', 'click', this.openDeleteUserModal);
        
        this.attachListener('form-search-users', 'submit', this.submitSearchUsers);
        this.attachListener('form-users-by-role', 'submit', this.submitUsersByRole);
        this.attachListener('form-user-by-id', 'submit', this.submitUserById);
        this.attachListener('form-user-by-email', 'submit', this.submitUserByEmail);
        this.attachListener('form-add-user', 'submit', this.submitAddUser);
        this.attachListener('form-delete-user', 'submit', this.submitDeleteUser);

        
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

    openSearchModal: function() { AdminServiceUsers.openModal('modal-search-users'); },
    openRoleModal: function() { AdminServiceUsers.openModal('modal-users-by-role'); },
    openUserByIdModal: function() { AdminServiceUsers.openModal('modal-user-by-id'); },
    openUserByEmailModal: function() { AdminServiceUsers.openModal('modal-user-by-email'); },
    openAddUserModal: function() { AdminServiceUsers.openModal('modal-add-user'); },
    openDeleteUserModal: function() { AdminServiceUsers.openModal('modal-delete-user'); },

    submitSearchUsers: function(e) {
        e.preventDefault();
        const term = document.getElementById('input-search-users-term').value;
        AdminServiceUsers.searchUsers(term);
        document.getElementById('modal-search-users').style.display = 'none';
    },

    submitUsersByRole: function(e) {
        e.preventDefault();
        const role = document.getElementById('input-users-by-role-val').value;
        AdminServiceUsers.usersByRole(role);
        document.getElementById('modal-users-by-role').style.display = 'none';
    },

    submitUserById: function(e) {
        e.preventDefault();
        const id = document.getElementById('input-user-by-id-val').value;
        AdminServiceUsers.getUserById(id);
        document.getElementById('modal-user-by-id').style.display = 'none';
    },

    submitUserByEmail: function(e) {
        e.preventDefault();
        const email = document.getElementById('input-user-by-email-val').value;
        AdminServiceUsers.getUserByEmail(email);
        document.getElementById('modal-user-by-email').style.display = 'none';
    },

    submitAddUser: function(e) {
        e.preventDefault();
        const data = {
            name: document.getElementById('input-add-user-name').value,
            email: document.getElementById('input-add-user-email').value,
            password: document.getElementById('input-add-user-password').value,
            role: document.getElementById('input-add-user-role').value
        };
        AdminServiceUsers.addUser(data);
        document.getElementById('modal-add-user').style.display = 'none';
    },

    submitDeleteUser: function(e) {
        e.preventDefault();
        const id = document.getElementById('input-delete-user-id').value;
        AdminServiceUsers.deleteUser(id);
        document.getElementById('modal-delete-user').style.display = 'none';
    },

    openModal: function(modalId) {
        const modal = document.getElementById(modalId);
        const inputs = modal.querySelectorAll('input');
        inputs.forEach(input => input.value = '');
        const selects = modal.querySelectorAll('select');
        selects.forEach(select => select.selectedIndex = 0);
        modal.style.display = 'flex';
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

    searchUsers: function(term) {
        adminSetStatus('Searching users...');
        RestClient.get('users/search/' + term, function(data) {
            adminSetStatus('');
            AdminServiceUsers.renderTable(data);
        }, function(error) {
            adminSetStatus('Error searching users', 'error');
            console.error(error);
        });
    },

    usersByRole: function(role) {
        adminSetStatus('Loading users by role...');
        RestClient.get('users/role/' + role, function(data) {
            adminSetStatus('');
            AdminServiceUsers.renderTable(data);
        }, function(error) {
            adminSetStatus('Error loading users by role', 'error');
            console.error(error);
        });
    },

    getUserById: function(id) {
        adminSetStatus('Loading user...');
        RestClient.get('users/' + id, function(data) {
            adminSetStatus('');
            AdminServiceUsers.renderTable([data]);
        }, function(error) {
            adminSetStatus('Error loading user', 'error');
            console.error(error);
        });
    },

    getUserByEmail: function(email) {
        adminSetStatus('Loading user...');
        RestClient.get('users/email/' + email, function(data) {
            adminSetStatus('');
            AdminServiceUsers.renderTable([data]);
        }, function(error) {
            adminSetStatus('Error loading user', 'error');
            console.error(error);
        });
    },

    addUser: function(data) {
        adminSetStatus('Adding user...');
        RestClient.post('users', data, function(response) {
            adminSetStatus('User added successfully', 'success');
            AdminServiceUsers.listUsers();
        }, function(error) {
            adminSetStatus('Error adding user', 'error');
            console.error(error);
        });
    },

    deleteUser: function(id) {
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
