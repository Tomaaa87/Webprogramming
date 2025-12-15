var AdminServiceCategories = {
    init: function() {
        console.log("AdminServiceCategories initialized");
        
        this.attachListener('admin-list-categories', 'click', this.listCategories);
        this.attachListener('admin-search-categories', 'click', this.openSearchCategoriesModal);
        this.attachListener('admin-category-by-id', 'click', this.openCategoryByIdModal);
        this.attachListener('admin-add-category', 'click', this.openAddCategoryModal);
        this.attachListener('admin-update-category', 'click', this.openUpdateCategoryModal);
        this.attachListener('admin-delete-category', 'click', this.openDeleteCategoryModal);

        this.attachListener('form-search-categories', 'submit', this.submitSearchCategories);
        this.attachListener('form-category-by-id', 'submit', this.submitCategoryById);
        this.attachListener('form-add-category', 'submit', this.submitAddCategory);
        this.attachListener('form-update-category', 'submit', this.submitUpdateCategory);
        this.attachListener('form-delete-category', 'submit', this.submitDeleteCategory);

        // zatvaranje modala 
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

    openSearchCategoriesModal: function() { AdminServiceCategories.openModal('modal-search-categories'); },
    openCategoryByIdModal: function() { AdminServiceCategories.openModal('modal-category-by-id'); },
    openAddCategoryModal: function() { AdminServiceCategories.openModal('modal-add-category'); },
    openUpdateCategoryModal: function() { AdminServiceCategories.openModal('modal-update-category'); },
    openDeleteCategoryModal: function() { AdminServiceCategories.openModal('modal-delete-category'); },

    submitSearchCategories: function(e) {
        e.preventDefault();
        const query = document.getElementById('input-search-categories-query').value;
        AdminServiceCategories.searchCategories(query);
        document.getElementById('modal-search-categories').style.display = 'none';
    },

    submitCategoryById: function(e) {
        e.preventDefault();
        const id = document.getElementById('input-category-by-id-val').value;
        AdminServiceCategories.getCategoryById(id);
        document.getElementById('modal-category-by-id').style.display = 'none';
    },

    submitAddCategory: function(e) {
        e.preventDefault();
        var file = document.getElementById('input-add-category-image-file').files[0];
        var data = {
            category_name: document.getElementById('input-add-category-name').value,
            description: document.getElementById('input-add-category-description').value
        };

        var finish = function(payload) {
            AdminServiceCategories.addCategory(payload);
            document.getElementById('modal-add-category').style.display = 'none';
        };

        if (file) {
            Utils.parseImageWithType(file).then(function(result){
                data.image_base64 = result.base64;
                data.image_type = result.type;
                finish(data);
            }).catch(function(err){
                console.error('Image parse failed', err);
                finish(data);
            });
        } else {
            finish(data);
        }
    },

    submitUpdateCategory: function(e) {
        e.preventDefault();
        const id = document.getElementById('input-update-category-id').value;
        const data = {};
        
        const name = document.getElementById('input-update-category-name').value;
        if (name) data.category_name = name;

        const description = document.getElementById('input-update-category-description').value;
        if (description) data.description = description;

        var file = document.getElementById('input-update-category-image-file').files[0];
        if (!file && Object.keys(data).length === 0) {
            alert("No changes entered.");
            return;
        }

        var applyUpdate = function(payload) {
            AdminServiceCategories.updateCategory(id, payload);
            document.getElementById('modal-update-category').style.display = 'none';
        };

        if (file) {
            Utils.parseImageWithType(file).then(function(result){
                data.image_base64 = result.base64;
                data.image_type = result.type;
                applyUpdate(data);
            }).catch(function(err){
                console.error('Image parse failed', err);
                applyUpdate(data);
            });
            return;
        }

        applyUpdate(data);
    },

    submitDeleteCategory: function(e) {
        e.preventDefault();
        const id = document.getElementById('input-delete-category-id').value;
        AdminServiceCategories.deleteCategory(id);
        document.getElementById('modal-delete-category').style.display = 'none';
    },

    openModal: function(modalId) {
        const modal = document.getElementById(modalId);
        const inputs = modal.querySelectorAll('input');
        inputs.forEach(input => input.value = '');
        modal.style.display = 'flex';
    },

    listCategories: function() {
        adminSetStatus('Loading all categories...');
        RestClient.get('categories', function(data) {
            adminSetStatus('');
            AdminServiceCategories.renderTable(data);
        }, function(error) {
            adminSetStatus('Error loading categories', 'error');
            console.error(error);
        });
    },

    searchCategories: function(query) {
        adminSetStatus('Searching categories...');
        RestClient.get('categories/search/' + query, function(data) {
            adminSetStatus('');
            AdminServiceCategories.renderTable(data);
        }, function(error) {
            adminSetStatus('Error searching categories', 'error');
            console.error(error);
        });
    },

    getCategoryById: function(id) {
        adminSetStatus('Loading category...');
        RestClient.get('categories/' + id, function(data) {
            adminSetStatus('');
            AdminServiceCategories.renderTable(data);
        }, function(error) {
            adminSetStatus('Error loading category', 'error');
            console.error(error);
        });
    },

    addCategory: function(data) {
        adminSetStatus('Adding category...');
        RestClient.post('categories', data, function(response) {
            adminSetStatus('Category added successfully', 'success');
            AdminServiceCategories.listCategories();
        }, function(error) {
            if (error && error.status === 200) {
                adminSetStatus('Category added successfully', 'success');
                AdminServiceCategories.listCategories();
                return;
            }
            var msg = error && error.responseJSON && error.responseJSON.message ? error.responseJSON.message : 'Error adding category';
            adminSetStatus(msg, 'error');
            console.error(error);
        });
    },

    updateCategory: function(id, data) {
        adminSetStatus('Updating category...');
        RestClient.put('categories/' + id, data, function(response) {
            adminSetStatus('Category updated successfully', 'success');
            AdminServiceCategories.listCategories();
        }, function(error) {
            if (error && error.status === 200) {
                adminSetStatus('Category updated successfully', 'success');
                AdminServiceCategories.listCategories();
                return;
            }
            var msg = error && error.responseJSON && error.responseJSON.message ? error.responseJSON.message : 'Error updating category';
            adminSetStatus(msg, 'error');
            console.error(error);
        });
    },

    deleteCategory: function(id) {
        adminSetStatus('Deleting category...');
        RestClient.delete('categories/' + id, {}, function(response) {
            adminSetStatus('Category deleted successfully', 'success');
            AdminServiceCategories.listCategories();
        }, function(error) {
            adminSetStatus('Error deleting category', 'error');
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

        var headers = Object.keys(rows[0]);
        var tableRows = rows.map(function(obj) {
            return headers.map(function(key) {
                return obj[key];
            });
        });

        adminRenderTable(headers, tableRows);
    }
};
