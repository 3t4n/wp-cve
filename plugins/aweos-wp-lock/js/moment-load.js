var vue;
jQuery(document).ready(function () {
	vue = new Vue({
		el: '#vuewplockroot',
		data: {
			option: false,
			enableUntilValue: false,
			disableUntilValue: false,
			enableUntilValueI: false,
			disableUntilValueI: false,
			enableFrom: false,
			enableTo: false,
			disableFrom: false,
			disableTo: false,
			lastUpdated: false,
			permanentlyClicked: false,
		},
		methods: {
			unlockFor2Hours: function() {
				this.option = 2;
				this.disableUntilValueI = 1;
				this.disableUntilValue = 2; // 2 hrs
			},
			unlockFor4Hours: function() {
				this.option = 2;
				this.disableUntilValueI = 1;
				this.disableUntilValue = 4; // 4 hrs
			},
			unlockFor8Hours: function() {
				this.option = 2;
				this.disableUntilValueI = 1;
				this.disableUntilValue = 8; // 8 hrs
			}
		},
		computed: {
			disableForColor: function() {
				return (this.option == 2) ? 'black' : 'grey';
			},
			enableForColor: function () {
				return (this.option == 3) ? 'black' : 'grey';
			},
			disableUntilDate: function() {
				if (this.option != 3 || this.disableUntilValue.length == 0) {return false;}
				switch(this.disableUntilValueI) {
					case '0': return moment().add(this.disableUntilValue, 'minutes').format('lll');
					case '1': return moment().add(this.disableUntilValue, 'hours').format('lll');
					case '2': return moment().add(this.disableUntilValue, 'days').format('lll');
					case '3': return moment().add(this.disableUntilValue, 'weeks').format('lll');
				}
			},
			enableUntilDate: function() {
				if (this.option != 5 || this.enableUntilValue.length == 0) {return false;}
				switch(this.enableUntilValueI) {
					case '0': return moment().add(this.enableUntilValue, 'minutes').format('lll');
					case '1': return moment().add(this.enableUntilValue, 'hours').format('lll');
					case '2': return moment().add(this.enableUntilValue, 'days').format('lll');
					case '3': return moment().add(this.enableUntilValue, 'weeks').format('lll');
				}
			},
		},
		watch: {
			option: function(v) {
				if (v == 2) {
					this.enableUntilValueI = '';
					this.enableUntilValue = '';
				} else if (v == 3) {
					this.disableUntilValueI = '';
					this.disableUntilValue = '';
				} else {
					this.enableUntilValueI = '';
					this.disableUntilValueI = '';
					this.enableUntilValue = '';
					this.disableUntilValue = '';
				}
			}
		},
		ready: function() {
			moment.locale('en');
			this.option = jQuery('#wplock-mode').html();
			this.disableUntilValue = jQuery('#wplock-dFor').html();
			this.disableUntilValueI = jQuery('#wplock-dForI').html();
			this.enableUntilValue = jQuery('#wplock-eFor').html();
			this.enableUntilValueI = jQuery('#wplock-eForI').html();

			this.enableFrom = jQuery('#wplock-eFrom').html();
			this.enableTo = jQuery('#wplock-eTo').html();
			this.disableFrom = jQuery('#wplock-dFrom').html();
			this.disableTo = jQuery('#wplock-dTo').html();

			this.lastUpdated = jQuery('#wplock-lastUpdated').html();
		}
	});
});
