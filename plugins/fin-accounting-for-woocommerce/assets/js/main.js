(function($) {
	"use strict";

	var finjs = {
		siteurl: '',
		symbol: '',
		init: function() {
			this.ajaxurl = ajax_object.ajaxurl;
			this.siteurl = ajax_object.siteurl;
			this.finurl = ajax_object.finurl;
			this.symbol = ajax_object.symbol;
			this.currency = ajax_object.currency;
		},
		xhr: function(params, callback, block) {
			if(typeof(block) === 'undefined') { block = true; }
			if(block) jQuery.blockUI();
			params.nonce = ajax_object.nonce;
			params.action = "finpose";
			return $.ajax({
				url: ajax_object.ajaxurl,
				type: 'POST',
				data: params,
				success: function( response ) {
					if(block) jQuery.unblockUI();	
					var data = JSON.parse(response);
					data.message ? data.success ? toastr.success(data.message) : toastr.error(data.message) : '';
					if(data.payload) {
						if(data.payload.reload) { location.reload(); }
						if(data.payload.redirect) { window.location.href = data.payload.redirect; }
					}
					callback(data); // JSON data parsed by `data.json()` call
				},
				error: function (xhr, ajaxOptions, thrownError) {
					if(block) jQuery.unblockUI();
					console.log(JSON.stringify(xhr));
					console.log(JSON.stringify(params));
				}
			});
		},
		isValidDate: function(dateString) {
			var regEx = /^\d{4}-\d{2}-\d{2}$/;
			if(!dateString.match(regEx)) return false;  // Invalid format
			var d = new Date(dateString);
			var dNum = d.getTime();
			if(!dNum && dNum !== 0) return false; // NaN value, Invalid date
			return d.toISOString().slice(0,10) === dateString;
		},
		validateForm: function(f) {
			var proceed = true;
			var inputs = $('#'+f).find('[data-validate]');
			$.each(inputs, function(k, obj){
				var valtype = $(obj).attr('data-validate');
				var value = $(obj).val();
				
				if(proceed && valtype=='date' && !fin.isValidDate(value)) {
					toastr.error('Date format provided is invalid');
					proceed = false;
					$(obj).addClass('invalid').on('focus', function(){$(this).removeClass('invalid'); });
				}

				var moneyRegex = /^(?!0\.00)\d{1,3}(,\d{3})*(\.\d\d)?$/
				if(proceed && valtype=='money' && (value!='0.00' && !moneyRegex.test(value))) {
					toastr.error('Money format provided is invalid');
					proceed = false;
					$(obj).addClass('invalid').on('focus', function(){$(this).removeClass('invalid'); });
				}

				if(valtype=='required') {
					if(proceed && typeof value == 'undefined' || (typeof value !== 'undefined' && !value.length)) {
						toastr.error('Field can not be empty');
						proceed = false;
						console.log($(obj));
						$(obj).addClass('invalid').on('focus', function(){$(this).removeClass('invalid'); });
					}
				}

				if(valtype=='selected') {
					if(proceed && value.length<1) {
						toastr.error('Field must be selected');
						proceed = false;
						$(obj).addClass('invalid').on('focus', function(){$(this).removeClass('invalid'); });
					}
				}

				if(proceed && valtype=='name' && value.length<4 || value.length>32) {
					toastr.error('Name should be between 4-32 characters');
					proceed = false;
					$(obj).addClass('invalid').on('focus', function(){$(this).removeClass('invalid'); });
				}

			});
		return proceed;
		},
		openModal: async function(modalID, w, h) {
			if(typeof(w)=='undefined') { var w = 640; }
			if(typeof(h)=='undefined') { var h= 500; }
			await tb_show(
				'Finpose',
				'#TB_inline?width='+w+'&amp;height='+h+'&amp;inlineId='+modalID
			);
		},
		closeModal: function() {
			tb_remove();
		},
		getFormData: function(formID) {
			const element = document.getElementById(formID);
			const data = new FormData(element)
			const form = Array.from(data.entries())
			var rows = {};
			for (const [name, value] of form) {
				var obj = { name, value };
				rows[obj.name] = obj.value;
			}
			return rows;
		},
		setFormData: function(formID, values, key) {
			var form = jQuery('#'+formID);
			$.each(values, function (k, v) {
				var el = form.find('[name='+k+']');
				if(el.length>0) {
					var tag = el.prop("tagName");
					if(tag=='INPUT') {
						el.attr('value', v);
					} else if(tag=='SELECT') {
						el.val(v);
					} else {
						el.attr('data-value', v);
					}
				}
			});

			if(typeof(key) != undefined) {
				var keyEl = form.find('[name=key]');
				keyEl.val(values[key]);
			}
		},
		intlMoney: function(val) {
			//var regex = /^\d{1,3}(,\d{3})*(\.\d+)?$/
			//if(!regex.test(newnum)) { return '';}
			return newnum;
		},
		formatMoney: function(val) {
			if(typeof(val) == 'undefined' || val =='' || val==0) return '0.00';
			if(typeof(val) == 'string') { return parseFloat(val).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'); }
			if(typeof(val) == 'number') { return parseFloat(val).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'); }
		return val;
		},
		flattenMoney: function(val) {
			var data = val.split(".");
			var nums = data[0];
			nums = nums.replaceAll(',','');
			var newnum = nums;
			if(typeof(data[1])!='undefined') {
				newnum = nums + '.' + data[1];
			}
			return newnum;
		},
		formatDate(unixtimestamp, type) {
			if(typeof(type) == undefined) {
				type = 'sortable';
			}
			// Months array
			var months_arr = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
		
			// Convert timestamp to milliseconds
			var date = new Date(unixtimestamp*1000);
		
			// Year
			var year = date.getFullYear();
		
			// Month
			var monthval = date.getMonth();
			var monthnum = monthval<10?'0'+monthval:monthval;
			var month = months_arr[date.getMonth()];
		
			// Day
			var day = date.getDate();
		
			// Hours
			var hours = date.getHours();
		
			// Minutes
			var minutes = "0" + date.getMinutes();
		
			// Seconds
			var seconds = "0" + date.getSeconds();

			if(type=='standard') {
				return year+'-'+monthnum+'-'+day;
			}

			if(type=='yearday') {
				return year+' '+month+' '+day;
			}
		
			if(type=='monthday') {
				return month+' '+day;
			}

			if(type=='dayhour') {
				return month+' '+day+' '+hours + ':' + minutes.substr(-2);
			}

			// Display date time in MM-dd-yyyy h:m:s format
			return month+'-'+day+'-'+year+' '+hours + ':' + minutes.substr(-2) + ':' + seconds.substr(-2);
		}
	}

	finjs.init();
	window.fin = finjs;
})( jQuery );