const payments = Vue.component('payments', {	
	template: '#payments',
	components: {},
	data: function () {
			return {
					payments: [],
					allcats: [],
					type: 'all',
					totals: {amount:0, tr:0},
					vendor: {},
					pager: {pages:1, page:1, perpage:25, total:''},
					currencySymbol: '',
					finurl: '',
					siteurl: '',
			}
	},
	beforeRouteEnter (to, from, next) {
		if(!to.params.vid) {return false;}
		var self = this;

		fin.xhr({ handler:'vendors', process:'getVendorPayments', vid: to.params.vid }, function(data) {
			var dt = data;
			next(vm => { 
				vm.vendor = to.params
				vm.payments = dt.payload.payments;
				vm.totals = dt.payload.totals;
				if(typeof(dt.payload.categories) != 'undefined') { vm.allcats = dt.payload.categories; }
			})
		});
  },
	mounted() {
		this.currencySymbol = fin.symbol;
		this.finurl = fin.finurl;
		this.siteurl = fin.siteurl;
	},
	methods: {
		categoryName: function(item) {
			if(typeof(this.allcats[item.type]) != 'undefined' && typeof(this.allcats[item.type][item.cat]) != 'undefined') {
				return this.allcats[item.type][item.cat].name;
			}
			return '';
		},
		categoryCode: function(item) {
			if(typeof(this.allcats[item.type]) != 'undefined' && typeof(this.allcats[item.type][item.cat]) != 'undefined') {
				return this.allcats[item.type][item.cat].jcode;
			}
			return '';
		},
		capitalizeFirstLetter(string) {
			if(!string) return '';
			return string.charAt(0).toUpperCase() + string.slice(1);
		},
		formatDate: function(ut, format) {
			return fin.formatDate(ut, format);
		},
		exportCSV: function() {
			jQuery(".fin-table").tableToCSV();
		},
		columnTotal: function(col, digits) {
			if(typeof(digits)=='undefined') { digits = 0; }
			var total = 0;
			this.tlist.forEach(function(obj, k) {
				total += obj[col];		
			});
			if(digits>0) {
				return (Math.round(total * 100) / 100).toFixed(2);
			} else {
				return parseInt(total);
			}
		},
		setPage(e) {
			this.pager.page = e.target.value;
			this.getPurchaseOrders();
		},
		setPerPage(e) {
			this.pager.page = 1;
			this.pager.perpage = e.target.value;
			this.getPurchaseOrders();
		},
		back() {
			this.$root.$refs.app.$router.push({ name: 'vendors', params: { } });
		},
	}
});

const purchases = Vue.component('purchases', {
	template: '#purchases',
	data: function () {
		return {
			vendor: {},
			porders: [],
			accounts: [],
			pager: {pages:1, page:1, perpage:25, total:''},
			currencySymbol: '',
			cats: [],
			catlist: [],
			form: {}
		}
	},
	beforeRouteEnter (to, from, next) {
		if(!to.params.vid) {return false;}
		var self = this;
		fin.xhr({ handler:'vendors', process:'getPurchaseOrders',vid: to.params.vid, pager: JSON.stringify({pages:1, page:1, perpage:25, total:''}) }, function(data) {
			var dt = data;
			next(vm => { 
				vm.vendor = to.params;
				vm.porders = dt.payload.porders;
				vm.cats = dt.payload.categories;
				vm.pager = dt.payload.pager;
				vm.accounts = data.payload.accounts;
			})
		});
  },
	mounted: function() {
		this.currencySymbol = fin.symbol;
		this.finurl = fin.finurl;
		this.siteurl = fin.siteurl;
		jQuery('.datepicker').datepicker({dateFormat : 'yy-mm-dd'});
	},
	methods: {
		getPurchaseOrders() {
			var self = this;
			fin.xhr({ handler:'vendors', process:'getPurchaseOrders', vid: this.vendor.vid, pager: JSON.stringify(this.pager) }, function(data) {
					self.porders = data.payload.porders;
					self.cats = data.payload.categories;
					self.pager = data.payload.pager;
					self.accounts = data.payload.accounts;
			});
		},
		addPOModal() {
			fin.openModal('addporder');
		},
		addPurchaseOrder() {
			if(!fin.validateForm('form-addporder')) return false;
			var self = this; 
			var fd = fin.getFormData('form-addporder');
			fin.xhr(fd, function (data) { 
				if(data.success) {
					self.getPurchaseOrders();
					fin.closeModal();
					jQuery('#form-addporder').trigger('reset');
				}
			});
		},
		editPOModal(po){
			this.catlist = this.cats[po.stype];
			this.form = po;
			fin.openModal('editporder');
		},
		editPurchaseOrder() {
			if(!fin.validateForm('form-editporder')) return false;
			var self = this; 
			var fd = fin.getFormData('form-editporder');
			fin.xhr(fd, function (data) { 
				if(data.success) {
					self.getPurchaseOrders();
					fin.closeModal();
				}
			});
		},
		deletePO: function(index, po) {
			this.index = index;
			var self = this; 
			var r = confirm("Remove purchase order from records?");
			if (r === true) {
				fin.xhr({handler: 'vendors', process:"deletePurchaseOrder", key:po.poid},function() {
					self.porders.splice(index,1);
				});
			}
		},
		addPaymentModal(po) {
			if(this.accounts.length<1) {
				toastr.error('You must create an account before adding payment.');
				return;
			}
			this.catlist = this.cats[po.stype];
			po.balance = fin.formatMoney(po.amount - po.amount_paid);
			var pwkey = Object.keys(this.accounts)[0];
			po.paidwith = pwkey;
			console.log(po);
			this.form = po;
			fin.openModal('addpayment');
		},
		addPayment() {
			if(!fin.validateForm('form-addpayment')) return false;
			var self = this; 
			var fd = fin.getFormData('form-addpayment');
			fin.xhr(fd, function (data) { 
				if(data.success) {
					self.getPurchaseOrders();
					fin.closeModal();
					jQuery('#form-addpayment').trigger('reset');
				}
			});
		},
		back() {
			this.$root.$refs.app.$router.push({ name: 'vendors', params: { } });
		},
		setPage(e) {
			this.pager.page = e.target.value;
			this.getPurchaseOrders();
		},
		setPerPage(e) {
			this.pager.page = 1;
			this.pager.perpage = e.target.value;
			this.getPurchaseOrders();
		},
		setCatlist() {
			var type = this.form.stype;
			this.catlist = this.cats[type];
		},
		checkAllowed(e) {
			var val = e.target.value;
			var last = val.slice(-1);
			var allowed = ['0','1','2','3','4','5','6','7','8','9','.'];
			if(!allowed.includes(last)) {
				e.target.value = val.slice(0, -1);
			}
		},
		formatAdd(e) {
			e.target.value = fin.formatMoney(e.target.value);
		},
		flattenAdd(e) {
			e.target.value = fin.flattenMoney(e.target.value);
		},
		formatMoney(val) {
			return fin.formatMoney(val);
		},
		statusName(slug) {
			if(slug=='partial') { return 'Partially Paid'; } else { return slug.charAt(0).toUpperCase() + slug.slice(1); }
		},
		formatDate(ut, format) {
			return fin.formatDate(ut, format);
		},
		rotateStatus(poid, status) {
			var self = this;
			fin.xhr({ handler:'vendors', process:'rotateStatus', poid: poid, status: status }, function(data) { self.getPurchaseOrders(); });
		},
		displayUploader(podata) {
			this.form = podata;
			fin.openModal('attachmentModal');
		},
		uploadAttachment() {
			var self = this;
			//if(!fin.validateForm(form)) { return false; }
				var file_data = jQuery('#upfile').prop('files')[0];
				var form_data = new FormData();
				form_data.append('file', file_data);
        form_data.append('action', 'finpose');
				form_data.append('process', 'attachFile');
				form_data.append('handler', 'vendors');
				form_data.append('nonce', jQuery('#nonce').val());
				form_data.append('poid', this.form.poid);
				jQuery.ajax({
						url: ajax_object.ajaxurl,
						type: 'post',
						contentType: false,
						processData: false,
						data: form_data,
						success: function (response) {
							var data = JSON.parse(response);
							data.message ? data.success ? toastr.success(data.message) : toastr.error(data.message) : '';
							if(data.success) {
								self.getPurchaseOrders();
								fin.closeModal();
							}
						},  
						error: function (response) {
							console.log('error', response);
						}
				});
		},
	},
	computed: {
		format_date: {
			get: function (val) {
				return fin.formatDate(this.form.timedue, 'standard');
			},
			set: function (val) {
				return this.form.timedue;
			}
		}
	},
});

const vendors = Vue.component('vendors', {
	template: '#vendors',
	data: function () {
		return {
			vendors: [],
			filters: {},
			pager: {pages:1, page:1, perpage:25, total:''},
			row: {}
		}
	},
	mounted: function() {
		this.currencySymbol = fin.symbol;
		this.finurl = fin.finurl;
		this.siteurl = fin.siteurl;
		this.getVendors();
	},
	methods: {
		getVendors: function() {
			var self = this;
			fin.xhr({ handler:'vendors', process:'getVendors', pager: JSON.stringify(this.pager) }, function(data) {
				self.vendors = data.payload.vendors;
				self.pager = data.payload.pager;
				//self.$root.$refs.app.$router.push({ name: 'purchases', params: self.vendors[0] });
			});
		},
		addVendorModal: function() {
			fin.openModal('addvendor', 400, 260);
		},
		addVendor: function() {
			if(!fin.validateForm('form-addvendor')) return false;
			var self = this; 
			var fd = fin.getFormData('form-addvendor');
			fin.xhr(fd, function (data) { 
				if(data.success) {
					self.getVendors();
					fin.closeModal();
					jQuery('#form-addvendor').trigger('reset');
				}
			});
		},
		editVendor: function(vdata) {
			this.row = vdata;
			fin.openModal('editvendor', 400, 260);
		},
		submitEditVendor: function(vdata) {
			if(!fin.validateForm('form-editvendor')) return false;
			var self = this; 
			var fd = fin.getFormData('form-editvendor');
			fin.xhr(fd, function (data) { 
				if(data.success) {
					self.getVendors();
					fin.closeModal();
				}
			});
		},
		setPage: function(e) {
			this.pager.page = e.target.value;
			this.getVendors();
		},
		setPerPage: function(e) {
			this.pager.page = 1;
			this.pager.perpage = e.target.value;
			this.getVendors();
		},
		showPurchaseOrders: function(vendor) {
			this.$root.$refs.app.$router.push({ name: 'purchases', params: vendor });
			this.$root.$refs.app.tab='purchases';
		},
		showPayments(vendor) {
			var self = this;
			this.$root.$refs.app.$router.push({ name: 'payments', params: vendor });
			this.$root.$refs.app.tab='payments';
		},
		formatMoney(val) {
			if(!val || val == 0) return '0.00';
			return fin.formatMoney(val);
		},
	}
});


const router = new VueRouter({
	mode: 'hash',
	routes: [
		{path: '/', name:'vendors', component: vendors},
		{path: '/purchases', name:'purchases', component: purchases},
		{path: '/payments', name:'payments', component: payments}
	],
});

var app = new Vue({
	router,
	el: '#finapp',
	data: {
		tab: 'vendors'
	},
	methods: {
	},
	created() {
			this.$root.$refs.app = this;
	}
});