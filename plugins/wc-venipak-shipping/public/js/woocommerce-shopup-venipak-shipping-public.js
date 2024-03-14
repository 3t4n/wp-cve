(function( $ ) {
  'use strict';

  window.venipakShipping = {
    markers: new Map(),
    init,
  };


  async function init() {
    await fetchCheckoutSettings();
    let pickupPointsCollection = await fetchPickupPoints();
    const isPickupFilter = $('#is_pickup_filter').length ? $('#is_pickup_filter').is(":checked") : true;
    const isLockerFilter = $('#is_locker_filter').length ? $('#is_locker_filter').is(":checked") : true;
    if (!isPickupFilter) {
      pickupPointsCollection = pickupPointsCollection.filter(row => row.type === 3);
    }
    if (!isLockerFilter) {
      pickupPointsCollection = pickupPointsCollection.filter(row => row.type === 1);
    }
    if (!window.initGoogleMap && window.venipakShipping.settings.is_map_enabled) {
      await initMap(pickupPointsCollection);
    } else if (window.venipakShipping.settings.is_map_enabled) {
      window.initGoogleMap();
    }
    initPickupSelect(pickupPointsCollection);
    if (window.venipakShipping.settings.is_map_enabled) {
      drawPickupMarkers(pickupPointsCollection);
      initHomeDisplayButton();
      $('#venipak-map').show();
      showAllMarkers();
      setTimeout(() => {
        const lspp = localStorage.getItem('lspp');
        const map = window.venipakShipping.map;
        if (map && lspp) {
          window.venipakShipping.activeMarker = window.venipakShipping.markers.get(+lspp);
          map.setCenter(window.venipakShipping.activeMarker.getPosition());
          map.setZoom(15);
          map.setMapTypeId(google.maps.MapTypeId.TERRAIN);
          map.setMapTypeId(google.maps.MapTypeId.ROADMAP);
        }
      }, 2000);
    }
    if ($('.points-filter').length) {
      $('.points-filter').change(() => {
        $('.points-filter').off('change');
        $('.venipak_pickup_point').empty();
        init();
      });
    }
  }

  function showAllMarkers() {
    window.venipakShipping.map.setCenter(window.venipakShipping.mapBounds.getCenter());
    window.venipakShipping.map.fitBounds(window.venipakShipping.mapBounds);
  }

  function drawHomeMarker() {
    draw();
    async function draw() {
      const map = window.venipakShipping.map;
      if (!map) return;
      const address = $('#billing_address_1').val();
      const city = $('#billing_city').val();
      const postcode = $('#billing_postcode').val();
      let position = await getUserLocation(address, city, postcode);
      if (!position && !position.lat && !position.lng) {
        position = await getLocationByIp();
      }
      if (window.venipakShipping.homeMarker) {
        window.venipakShipping.homeMarker.setMap(null);
      }
      window.venipakShipping.homeMarker = new google.maps.Marker({
        position,
        map,
      });
      map.setCenter(position);
      map.setZoom(15);
      map.setMapTypeId(google.maps.MapTypeId.TERRAIN);
      map.setMapTypeId(google.maps.MapTypeId.ROADMAP);
    }
  }

  function drawPickupMarkers(pickupPointsCollection) {
    const map = window.venipakShipping.map;
    const pickup_image = window.venipakShipping.settings.pickup_marker;
    const locker_image = window.venipakShipping.settings.locker_marker;
    const markers = [];
    window.venipakShipping.mapBounds = new google.maps.LatLngBounds();
    for (let i = 0; i < pickupPointsCollection.length; i++) {
      const marker = pickupPointsCollection[i];
      const markerEntity = new google.maps.Marker({
        position: { lat: parseFloat(marker.lat), lng: parseFloat(marker.lng) },
        map,
        title: marker.display_name,
        icon: marker.type === 1 ? pickup_image : locker_image,
      });
      window.venipakShipping.markers.set(marker.id, markerEntity);
      markers.push(markerEntity);
      markerEntity.addListener("click", () => {
        $('.venipak_pickup_point').val(marker.id).trigger('change');
        window.venipakShipping.activeMarker = markerEntity;
        setSelectedPickupInfo(marker);
      });
      const infoWindow = getInfoWindow(marker);
      markerEntity.addListener('mouseover', function() {
        infoWindow.open(map, this);
      });

      // assuming you also want to hide the infowindow when user mouses-out
      markerEntity.addListener('mouseout', function() {
        infoWindow.close();
      });
      window.venipakShipping.mapBounds.extend(markerEntity.getPosition());
    }
    new markerClusterer.MarkerClusterer({ map, markers });
  }

  function getInfoWindow(marker) {
    const { display_name, address, city, zip, working_hours } = marker;
    let contentString = `<span>${display_name}<br /><small>${address}, ${city}, ${zip}</small></span>`;
    if (working_hours) {
      contentString += getWorkingHours(working_hours);
    }

    return new google.maps.InfoWindow({
      content: contentString,
    });
  }

  function getWorkingHours(data) {
    let result = '';
    const hoursCollection = JSON.parse(data);
    let dayFrom = 0;
    let dayTo = 0;
    let i = 0;
    while (dayTo < hoursCollection.length - 1) {
      if (i === 0) {
        dayFrom = i;
        dayTo = i;
        i++;
        continue
      }
      if (i < hoursCollection.length - 1 && JSON.stringify(hoursCollection[i]) === JSON.stringify(hoursCollection[dayFrom])) {
        dayTo = i;
        i++;
        continue;
      } else {
        if (i === hoursCollection.length - 1) {
          dayTo++;
        }
        result += `<div>${getDaysString(dayFrom, dayTo)}: ${hoursCollection[dayFrom].from_h.padStart(2, "0")}:${hoursCollection[dayFrom].from_m.padStart(2, "0")} - ${hoursCollection[dayTo].to_h.padStart(2, "0")}:${hoursCollection[dayTo].to_m.padStart(2, "0")}</div>`;
        dayFrom = dayTo + 1;
        dayTo = dayFrom;
        i++;
      }
    }
    return result;
  }

  function getDaysString(dayFrom, dayTo) {
    if (dayFrom === dayTo) {
      return getName(dayFrom);
    }
    return `${getName(dayFrom)}-${getName(dayTo)}`;

    function getName(day) {
      switch (day) {
        case 0:
          return 'I';
          break;
        case 1:
          return 'II';
          break;
        case 2:
          return 'III';
          break;
        case 3:
          return 'IV';
          break;
        case 4:
          return 'V';
          break;
        case 5:
          return 'VI';
          break;
        case 6:
          return 'VII';
          break;
      }
    }
  }

  function getUserLocation(address, city, postcode) {
    return new Promise(async (resolve, reject) => {
      if (!address) {
        const position = await getLocationByIp();
        resolve(position);
      } else  {
        $.get(
          `https://maps.googleapis.com/maps/api/geocode/json?address=${address}+${city}+${postcode}&key=${window.venipakShipping.settings.googlemap_api_key}`,
          function(data) {
            if (data.results.length === 0) {
              resolve(null);
            }
            if (data.results[0] && data.results[0].geometry) {
              return resolve(data.results[0].geometry.location);
            }
            return resolve();
          },'json');
      }
    });
  }

  function getLocationByIp() {
    return new Promise ((resolve) => {
      navigator.geolocation.getCurrentPosition(
        function(position) {
          return resolve({ lat: position.coords.latitude, lng: position.coords.longitude });
        }
      );
    });
  }

  function initMap() {
    if (window.initGoogleMap) {
      return new Promise((resolve) => {
        resolve(window.venipakShipping.map);
      })
    }
    return new Promise((resolve, reject) => {
      const script = document.createElement('script');
      script.src = `https://maps.googleapis.com/maps/api/js?key=${window.venipakShipping.settings.googlemap_api_key}&callback=initGoogleMap`;
      script.defer = true;
      document.head.appendChild(script);
      window.initGoogleMap = function() {
        if (typeof google === undefined) return;
        window.venipakShipping.map = new google.maps.Map(document.getElementById("venipak-map"));
        return resolve(window.venipakShipping.map);
      }
    });
  }

  function setSelectedPickupInfo(data) {
    const { id, display_name, address, city, working_hours } = data;
    let result = `<div><div><b>${display_name}</b></div><div>${address}, ${city}</div></div>`;
    if (working_hours) {
      result += getWorkingHours(working_hours);
    }
    $('#selected-pickup-info').html(result);
    localStorage.setItem('lspp', id);
  }

  async function findAddressByInputText() {
    const map = window.venipakShipping.map;
    if (!map) return;
    const text = $('.select2-search__field').val();
    const position = await getCustomLocation(text);
    if (position && position.lat && position.lng) {
      map.setCenter(position);
      map.setZoom(15);
    }
  }

  function getCustomLocation(keyword) {
    return new Promise((resolve, reject) => {
      $.get(
        `https://maps.googleapis.com/maps/api/geocode/json?address=${keyword}&key=${window.venipakShipping.settings.googlemap_api_key}`,
        (data) => {
          if (data.results.length === 0) {
            resolve(null);
          }
          if (data.results[0] && data.results[0].geometry.location) {
            return resolve(data.results[0].geometry.location);
          }
          return data.results;
        },
        'json',
      );
    });
  }

  function initPickupSelect(collection) {
    const map = window.venipakShipping.map;
    $('.venipak_pickup_point').select2({
      data: collection.map(value => ({
        id: value.id,
        text: `${value.display_name}|${value.address}|${value.city}|${value.zip}`,
      })),
      // matcher: matchCustom,
      templateResult,
      templateSelection,
      width: 'resolve'
    });
    const lspp = localStorage.getItem('lspp');
    if (lspp) {
      $('.venipak_pickup_point').val(lspp).trigger('change');
    }
    if (map) {
      $('.venipak_pickup_point').on('select2:select', function (e) {
        fetchPickupPoints().then((markersCollection) => {
          const markerData = markersCollection.find(row => row.id === +e.params.data.id);
          window.venipakShipping.activeMarker = window.venipakShipping.markers.get(markerData.id);
          map.setCenter(window.venipakShipping.activeMarker.getPosition());
          map.setZoom(15);
          map.setMapTypeId(google.maps.MapTypeId.TERRAIN);
          map.setMapTypeId(google.maps.MapTypeId.ROADMAP);
          setSelectedPickupInfo(markerData);
        });
      });

      $('body').on('DOMSubtreeModified', '.select2-results', debounce(function () {
        const markers = [];
        const filteredRows = $('.select2-results__options li');
        if (filteredRows.length === 1 && filteredRows[0].attributes.role.value === 'alert') {
          findAddressByInputText();
          return;
        }
        if (filteredRows.length - 1 === window.venipakShipping.markers.size) {
          return;
        }
        filteredRows.map(function() {
          if (!this.id) return;
          const marker_id = this.id.substr(this.id.length - 4);
          const markerEntity = window.venipakShipping.markers.get(+marker_id);
          markers.push(markerEntity);
        });
        if (markers.length > 0) {
          const bounds = new google.maps.LatLngBounds();
          for (var i = 0; i < markers.length; i++) {
            bounds.extend(markers[i].getPosition());
          }
          window.venipakShipping.map.setCenter(bounds.getCenter());
          window.venipakShipping.map.fitBounds(bounds);
        }
      }, 1000));
    }

    function templateResult (item) {
      if (!item.id) return item.text;
      const [text, address, city, zip] = item.text.split('|');
      return $(`<span>${text}<br /><small>${address}, ${city}, ${zip}</small></span>`);
    }
    function templateSelection (item) {
      if (!item.id) return item.text;
      const [text] = item.text.split('|');
      return text;
    }

    function matchCustom(params, data) {
      // If there are no search terms, return all of the data
      if ($.trim(params.term) === '') {
        return data;
      }

      // Do not display the item if there is no 'text' property
      if (typeof data.text === 'undefined') {
        return null;
      }

      if (data.text.toLowerCase().includes(params.term.toLowerCase())) {
        return data;
      }

      // Return `null` if the term should not be displayed
      return null;
    }
  }

  function fetchPickupPoints() {
    return new Promise((resolve, reject) => {
      if (window.venipakShipping.pickupPoints && $('#billing_country').val() === window.venipakShipping.selectedCountry) return resolve(window.venipakShipping.pickupPoints);

      $.get(window.adminUrl + 'admin-ajax.php', { 'action': 'woocommerce_venipak_shipping_pickup_points' }, function(data) {
        window.venipakShipping.pickupPoints = data;
        window.venipakShipping.selectedCountry = $('#billing_country').val();
        return resolve(window.venipakShipping.pickupPoints);
      }, 'json');
    });
  }

  function fetchCheckoutSettings() {
    return new Promise((resolve, reject) => {
      if (window.venipakShipping.settings) return resolve(window.venipakShipping.settings);

      $.get(window.adminUrl + 'admin-ajax.php', { 'action': 'woocommerce_venipak_shipping_checkout_settings' }, function(data) {
        window.venipakShipping.settings = data;
        return resolve(window.venipakShipping.settings);
      }, 'json');
    });
  }

  function debounce(func, wait, immediate) {
    var timeout;
    return function() {
      var context = this, args = arguments;
      var later = function() {
        timeout = null;
        if (!immediate) func.apply(context, args);
      };
      var callNow = immediate && !timeout;
      clearTimeout(timeout);
      timeout = setTimeout(later, wait);
      if (callNow) func.apply(context, args);
    };
  };

  function initHomeDisplayButton() {
    const map = window.venipakShipping.map;
    $( '#billing_address_1' ).change(function() {
      const address = $('#billing_address_1').val();
      if (address !== '' && window.venipakShipping.settings.is_map_enabled) {
        $('#show-venipak-map').show();
      } else {
        $('#show-venipak-map').hide();
      }
    });
    $('#show-venipak-map').on('click', function() {
      drawHomeMarker(map);
    });
    $( "#billing_address_1" ).trigger( "change" );
  }
})( jQuery );
