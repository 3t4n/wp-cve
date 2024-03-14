var app = new Vue({
	el: '#finapp',
	data: {
		form: {fiscal:'standard', dateformat:'usa'},
		settings: [],
		currencies: [],
		timezones: [],
		tzkeys: [],
		wc_timezone: '',
		currencySymbol: '',
		finurl: '',
		siteurl: '',
	},
	mounted: function() {
		this.currencySymbol = fin.symbol;
		this.finurl = fin.finurl;
		this.siteurl = fin.siteurl;
		this.currency = fin.currency;
		this.getSettings();
		jQuery('.datepicker').datepicker({dateFormat : 'yy-mm-dd'});
	},
	methods: {
		getSettings: function() {
			var self = this;
			fin.xhr({handler:'settings', process:'getSettings', filters: JSON.stringify(this.filters)}, function (data) { 
				if(data.payload.settings) {
					self.form = data.payload.settings;
					self.timezones = data.payload.timezones;
					self.tzkeys = Object.keys(data.payload.timezones)
					console.log(self.tzkeys)
					self.wc_timezone = data.payload.wc_timezone;
				}
			});
		},
		saveSettings() {
			if(!fin.validateForm('form-savesettings')) return false;
			var self = this; 
			var fd = fin.getFormData('form-savesettings');
			fin.xhr(fd, function (data) { 
				if(data.success) {
					self.form = data.payload.settings;
				}
			});
		}
	}
})