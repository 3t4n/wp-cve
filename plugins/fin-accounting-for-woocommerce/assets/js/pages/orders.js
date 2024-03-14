var app = new Vue({
	el: '#finapp',
	data: {
		add_wcpdf: 0,
		orders: [],
		totals: [],
		filters: {datestart:'', dateend:'', totalthan: 'lower', total:'', datetype:'date_created', gateway:'', status: 'all', currency:'',customeremail:'',customerfname:'',customerlname:''},
		currencySymbol: '',
		currencies: [],
		statuses: {},
		currencySymbol: '',
		finurl: '',
		siteurl: '',
	},
	mounted: function() {
		this.currencySymbol = fin.symbol;
		this.finurl = fin.finurl;
		this.siteurl = fin.siteurl;
		this.currency = fin.currency;
		this.getOrders();
		jQuery('.datepicker').datepicker({dateFormat : 'yy-mm-dd'});
	},
	methods: {
		getOrders: function() {
			var self = this;
			fin.xhr({handler:'orders', process:'getOrders', filters: JSON.stringify(this.filters)}, function (data) { 
				self.filters = data.payload.filters;
				self.orders = data.payload.orders;
				self.totals = data.payload.totals;
				self.add_wcpdf = data.payload.add_wcpdf;
				self.currencies = data.payload.currencies;
				self.statuses = data.payload.statuses;
			});
		},
		filterOrders: function() {
			this.filters.datestart = jQuery('#datestart').val();
			this.filters.dateend = jQuery('#dateend').val();
			this.getOrders();
		},
		printStatus: function(str) {
			var string = str.replace('-', ' ');
			return string.charAt(0).toUpperCase() + string.slice(1);
		},
		exportCSV: function() {
			jQuery(".fin-table").tableToCSV();
		},
		formatMoney: function(val) {
			return fin.formatMoney(val);
		},
	},
	created() {
		this.$root.$refs.app = this;
	}
});