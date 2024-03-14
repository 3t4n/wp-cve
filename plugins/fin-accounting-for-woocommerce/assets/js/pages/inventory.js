var app = new Vue({
	el: '#finapp',
	data: {
		inventory: [],
		summary: {},
		accounts: {},
		vendors: [],
		vendorsObj: {},
		pager: {pages:1, page:1, perpage:25, total:''},
		filters: {term:'', category:0, type:0},
		initial: true,
		row: {finmeta:{}},
		editor: {product:{summary:{}}},
		editorPager: {pages:1, page:1, perpage:10, total:''},
		currencySymbol: '',
		finurl: '',
		siteurl: '',
	},
	mounted: function() {
		this.currencySymbol = fin.symbol;
		this.finurl = fin.finurl;
		this.siteurl = fin.siteurl;
		this.getInventory();
	},
	methods: {
		getInventory: function() {
			var self = this;
			fin.xhr({handler:'inventory', process:'getInventory', pager: JSON.stringify(this.pager), filters: JSON.stringify(this.filters), initial: this.initial}, function (data) { 
				self.inventory = data.payload.products;
				if(self.inventory.length>0) { self.row = self.inventory[0]; }
				self.summary = data.payload.summary;
				self.pager = data.payload.pager;
				if(typeof(data.payload.accounts)!='undefined') { self.accounts = data.payload.accounts; }
				if(typeof(data.payload.vendors)!='undefined') { 
					self.vendors = data.payload.vendors; 
					self.vendors.forEach(obj => {
						self.vendorsObj[obj.vid] = obj.vname
					})
				}
				self.initial = false;
			});
		},
		filterInventory: function() {
			this.pager.page = 1;
			this.getInventory();
		},
		requiresSync: function() {
			jQuery( '#cancelsync' ).show();
			var self = this;
			fin.xhr({handler:'inventory', process:'getNeedSync'}, function (data) { 
				self.inventory = data.payload.products;
				if(self.inventory.length>0) { self.row = self.inventory[0]; }
			});
		},
		cancelSync: function() {
			jQuery( '#cancelsync' ).hide();
			this.getInventory();
		},
		addStockModal: function(pid, pname) {
			var form  = jQuery('#form-addstock');
			form.find('input[name="pid"]').val(pid);
			form.find('.labelName').text(pname);
			fin.openModal('addStock');
		},
		addStock: function() {
			var self = this;
			var fd = fin.getFormData('form-addstock');
			fin.xhr(fd, function (data) { 
				fin.closeModal();
				self.getInventory();
				jQuery('#form-addstock').trigger('reset');
			});
		},
		importStockModal: function(inv) {
			this.row = inv;
			fin.openModal('importStock');
		},
		importStock: function() {
			var self = this;
			var fd = fin.getFormData('form-importstock');
			fin.xhr(fd, function (data) { 
				fin.closeModal();
				self.getInventory();
				jQuery('#form-importstock').trigger('reset');
			});
		},
		editorModal: function(inv) {
			this.row = inv;
			var self = this;

			jQuery( 'body' ).on( 'thickbox:removed', function() {
				self.getInventory();
				jQuery( 'body' ).off( 'thickbox:removed' );
			});

			this.getEditorItems(function(data) {
				self.editor = data.payload.editor;
				self.editorPager = data.payload.pager;
				fin.openModal('itemEditor', 1200, 500);
			});
		},
		getEditorItems: function(callback) {
			fin.xhr({handler:'inventory', process:'getInventoryItems', pid: this.row.pid, pager: JSON.stringify(this.editorPager)}, callback);
		},
		setEditorPage: function(e) {
			var self = this;
			this.editorPager.page = e.target.value;
			this.getEditorItems(function(data) {
				self.editor = data.payload.editor;
				self.editorPager = data.payload.pager;
			});
		},
		searchItem: function(e) {
			if(this.filters.term.length<3) { return; }
			this.pager.page = 1;
			jQuery( '#invsearchcancel' ).show();
			this.getInventory();
		},
		cancelSearch: function() {
			this.filters.term='';
			this.getInventory();
			jQuery( '#invsearchcancel' ).hide();
		},
		removeInventoryUnit: function(iid, pid) {
			var self = this;
			fin.xhr({handler:'inventory', process:'removeInventoryUnit', iid: iid, pid: pid}, function(data) {
				self.getEditorItems(function(data) {
					self.editor = data.payload.editor;
					self.editorPager = data.payload.pager;
				});
			});
		},
		setPage: function(e) {
			this.pager.page = e.target.value;
			this.getInventory();
		},
		setPerPage: function(e) {
			this.pager.page = 1;
			this.pager.perpage = e.target.value;
			this.getInventory();
		},
		checkAllowed: function(e) {
			var val = e.target.value;
			var last = val.slice(-1);
			var allowed = ['0','1','2','3','4','5','6','7','8','9','.'];
			if(!allowed.includes(last)) {
				e.target.value = val.slice(0, -1);
			}
		},
		formatMoney: function(val) {
			if(!val) return '';
			return fin.formatMoney(val);
		},
		round: function(val) {
			return Math.round(val);
		},
		formatMoneyAdd: function(e) {
			e.target.value = fin.formatMoney(e.target.value);
		},
		flattenMoneyAdd: function(e) {
			e.target.value = fin.flattenMoney(e.target.value);
		},
	},
	created() {
		this.$root.$refs.app = this;
	}
});