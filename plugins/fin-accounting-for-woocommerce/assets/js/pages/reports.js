var balance = Vue.component('balance', {
	template: '#balance',
	data: function () {
			return {
				bdata: {},
				selyear: new Date().getFullYear(),
				thisyear: new Date().getFullYear(),
				yearloaded: ''
			}
	},
	beforeRouteEnter (to, from, next) {
		fin.xhr({ handler:'reporting', process:'getBalanceSheet' }, function(data) {
			var dt = data;
			next(vm => { 
				vm.bdata = dt.payload.data;
			})
		});
  },
	mounted: function() {
	},
	methods: {
		getBalanceSheet : function() {
			fin.xhr({ handler:'reporting', process:'getBalanceSheet' }, function(data) {
				var dt = data;
				this.yearloaded = this.selyear
			});
		},
		exportCSV: function() {
			jQuery(".balancesheet").tableToCSV();
		},
	}
});


var pl = Vue.component('pl', {
	template: '#pl',
	data: function () {
			return {
				users: [],
			}
	},
	mounted: function() {
		this.getUsers();
	},
	methods: {
		getUsers: function() {
			
		},
		exportCSV: function() {
			jQuery(".plreport").tableToCSV();
		},
	}
});

const router = new VueRouter({
	mode: 'hash',
	routes: [
		{path: '/', name:'pl', component: pl},
		{path: '/balance', name:'balance', component: balance},
	],
});

var app = new Vue({
	router,
	el: '#finapp',
	data: {
		tab: 'pl',
	},
	mounted: function() {
		this.getUsers();
	},
	methods: {
		getUsers: function() {
			
		},
		exportCSV: function() {
			jQuery(".plreport").tableToCSV();
		},
	},
	created() {
		this.$root.$refs.app = this;
	}
});