
var CustomOrderService = {
		init: function() {
			this.form = document.getElementById('customOrderForm');
			this.msg = document.getElementById('responseMsg');
			if (!this.form) return;
			this.bind();
		},

		
		resolveUserContext: function() {
			var tokenUser = (window.UserService && typeof UserService.currentUser === 'function') ? UserService.currentUser() : null;
			var parsed = Utils.parseJwt(localStorage.getItem('user_token')) || {};
			var candidates = [tokenUser, parsed, parsed.user];
			var keys = ['id', 'user_id', 'uid', 'sub'];
			for (var i = 0; i < candidates.length; i++) {
				var c = candidates[i];
				if (!c) continue;
				for (var j = 0; j < keys.length; j++) {
					if (c[keys[j]]) {
						return { userId: c[keys[j]], parsed: parsed };
					}
				}
			}
			return { userId: null, parsed: parsed };
		},

		bind: function() {
			var self = this;
			this.form.addEventListener('submit', function(e){
				e.preventDefault();
				self.submit();
			});
		},

			submit: function() {
			var category = (document.getElementById('categorySelect') || {}).value || '';
			var title = (document.getElementById('orderTitle') || {}).value || '';
			var details = (document.getElementById('details') || {}).value || '';
			var estimated = (document.getElementById('estimatedPrice') || {}).value || '';

			var price = parseFloat(estimated);
			if (isNaN(price) || price <= 0) {
				return this.setMessage('Estimated price must be greater than zero.', true);
			}
			if (title.trim().length < 5) {
				return this.setMessage('Title must be at least 5 characters.', true);
			}
			if (details.trim().length < 20) {
				return this.setMessage('Details must be at least 20 characters.', true);
			}

				var ctx = this.resolveUserContext();
				if (!ctx.userId) {
					console.warn('CustomOrderService: missing user id in token payload', ctx.parsed);
				return this.setMessage('Please log in to submit a custom order.', true);
			}

				var payload = {
				title: title.trim(),
				details: details.trim(),
				estimated_price: price,
				category: category || null,
					user_id: ctx.userId
			};

			var self = this;
			RestClient.post('custom-orders', payload, function(){
				self.setMessage('Custom order submitted. We will review it soon.', false);
				self.form.reset();
			}, function(err){
				var msg = (err && err.responseJSON && err.responseJSON.message) || 'Failed to submit custom order.';
				self.setMessage(msg, true);
			});
		},

		setMessage: function(text, isError) {
			if (!this.msg) return;
			this.msg.textContent = text;
			this.msg.style.color = isError ? 'red' : 'green';
		}
	};

document.addEventListener('DOMContentLoaded', function(){
	CustomOrderService.init(); 
    window.CustomOrderService = CustomOrderService;
});
