var webshipper = {

	// Variables
	wp_url: webshipper_ajax_object.ajax_url,
	drop_points: [],
	address: {},
	drop_point: {},

	translate: function(key){
		return window.webshipper_lang[key];
	},

	select_drop_point: function(id) {
		var drop_point = webshipper.drop_points.find(function(x) {
			if (!x.drop_point_id) {
				return false
			}

			return x.drop_point_id.toString() === id
		})

		webshipper.drop_point = drop_point

		if(webshipper.modal) {
			webshipper.modal.close()

      var drop_point_containers = document.getElementsByClassName('webshipper-selected-drop-point')
      Array.prototype.forEach.call(drop_point_containers, function(container) {
        container.classList.remove('webshipper-hidden')
      })

      var drop_point_info_fields = [
        {
          key: 'webshipper-selected-name',
          value: webshipper.drop_point.name
        },
        {
          key: 'webshipper-selected-address',
          value: webshipper.drop_point.address_1
        },
        {
          key: 'webshipper-selected-zip',
          value: webshipper.drop_point.zip + ' ' + webshipper.drop_point.city
        }
      ]

      drop_point_info_fields.forEach(function(field) {
        elements = document.getElementsByClassName(field.key)
        Array.prototype.forEach.call(elements, function(element) {
          element.innerHTML = field.value
        })
      })
		}

		var dp = window.webshipper.drop_point;
		var blob = encodeURIComponent(JSON.stringify({
			drop_point_id: dp.drop_point_id,
			address_1: dp.address_1,
			zip: dp.zip,
			city: dp.city,
			country_code: dp.country_code,
			name: dp.name,
			routing_code: dp.routing_code
		}))

    ws_drop_point_blobs = document.getElementsByName('ws_drop_point_blob')
    Array.prototype.forEach.call(ws_drop_point_blobs, function(drop_point_blob) {
      drop_point_blob.value = blob
    })
	},

	// Get and set shops by address
	search: function(zip, adr, city, callback){
		// Make the call
		jQuery.post( webshipper.wp_url, {
			action: "get_shops",
			address: adr,
			city: city,
			zip: zip,
		}, function( response ) {
			if(! response.data || ! response.data.drop_points) {
				window.webshipper.drop_points = []
			} else {
				webshipper.drop_points = response.data.drop_points;
			}

			if(typeof callback !== 'undefined') {
				callback()
			}
		}, "json").fail(function() {
				webshipper.drop_points = [];
		});
	}
};