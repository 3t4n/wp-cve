"use strict";

(function ($) {
  var map = null;
  var cluster = null;
  var elementInvoking = null;
  var postRequest = null;
  var searchValue = null;
  var markers = [];
  var defaultSettings = {
    autoClose: true,
    sourceUrl: "api/getInpostMachines.xml",
    type: "point",
    title: "Wybierz punkt nadania",
    country: "PL"
  };
  var sources = {
    "pwr": {
      autoClose: true,
      sourceUrl: "api/PwrPoints.xml",
      type: "point",
      title: "Wybierz punkt nadania",
      country: "PL"
    },
    "inpost": {
      autoClose: true,
      sourceUrl: "api/getInpostMachines.xml",
      type: "point",
      title: "Wybierz punkt nadania",
      country: "PL"
    },
    "paczka48": {
      autoClose: true,
      sourceUrl: "api/ppSndPoints.xml",
      type: "point",
      title: "Wybierz punkt nadania",
      country: "PL"
    }
  };
  var scrollToElem = null;
  var refreshing = false;
  var refresh = 0;
  var lastRefresh = 0;
  var settings = {}; // old browser compatible below

  var _extends = Object.assign || function (target) {
    for (var i = 1; i < arguments.length; i++) {
      var source = arguments[i];

      for (var key in source) {
        if (Object.prototype.hasOwnProperty.call(source, key)) {
          target[key] = source[key];
        }
      }
    }

    return target;
  };

  var icons = {
    red: new L.Icon.Default({
      iconUrl: 'marker-icon.png',
      iconRetinaUrl: 'marker-icon-2x.png',
      shadowUrl: '',
      shadowSize: [0, 0]
    })
  };

  window.ShowMap = function (event, courierSource, lat, lng) {
    var autoClose = arguments.length > 4 && arguments[4] !== undefined ? arguments[4] : true;
    return new Promise(function _callee(resolve, reject) {
      var boundingbox, zoom;
      return regeneratorRuntime.async(function _callee$(_context) {
        while (1) {
          switch (_context.prev = _context.next) {
            case 0:
              $("#mapPopup #search-input").val("");
              elementInvoking = null;
              elementInvoking = event.target; // defaultSettings = sources[courier];

              defaultSettings = courierSource;
              settings = _extends({}, defaultSettings, settings);
              boundingbox = ["49.0020468", "55.0336963", "14.1229707", "24.145783"];
              zoom = 10;

              if (lat !== undefined && lng !== undefined) {
                zoom = 12;
              } else {
                zoom = 6;
              }

              if (lat === undefined) lat = 52.209402;
              if (lng === undefined) lng = 19.302979;

              if (!map) {
                map = L.map('placeMapHere', {
                  center: [lat, lng],
                  zoom: zoom,
                  maxBounds: [[boundingbox[0], boundingbox[2]], [boundingbox[1], boundingbox[3]]]
                });
                L.tileLayer('https://b.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                  attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>',
                  maxZoom: 18
                }).addTo(map);
                map.addLayer(cluster = L.markerClusterGroup({
                  maxClusterRadius: 50
                }));
                map.on('zoomend', idle);
                map.on('moveend', idle);
              } else {
                markers.forEach(function (marker) {
                  $("#mapPopup #point-details-" + marker.fullPointData.id).remove();

                  if (marker.fullPointData.source !== undefined) {
                    $("#mapPopup  #point-details-" + marker.fullPointData.id + "---" + marker.fullPointData.source.id).remove();
                  }

                  cluster.removeLayer(marker);
                });
                map.setView(new L.LatLng(lat, lng), zoom, {
                  animation: false
                });
                map.fire('moveend');
              }

              _context.next = 13;
              return regeneratorRuntime.awrap(Promise.all([refreshPoints(lat, lng)]).then(resolve)["catch"](reject));

            case 13:
            case "end":
              return _context.stop();
          }
        }
      });
    });
  };

  function idle() {
    return regeneratorRuntime.async(function idle$(_context2) {
      while (1) {
        switch (_context2.prev = _context2.next) {
          case 0:
            _context2.next = 2;
            return regeneratorRuntime.awrap(Promise.all([refreshPoints(map.getCenter().lat, map.getCenter().lng)]).then(function () {
              if (scrollToElem != null) {
                $(".pointsList").scrollTo($(scrollToElem), 1000);
              }
            }));

          case 2:
          case "end":
            return _context2.stop();
        }
      }
    });
  }

  function refreshPoints(lat, lon
  /*,callback*/
  ) {
    return new Promise(function (resolve, reject) {
      var data = _extends({
        lat: lat,
        lon: lon,
        map: true
      }, settings.additionalData);

      if (postRequest != null) {
        postRequest.abort();
      }

      refreshing = true;
      refresh = new Date().getTime();
      postRequest = $.post(epaka_object.api_endpoint + '/map?endpoint=' + defaultSettings.sourceUrl, data, function _callee2(response) {
        var points;
        return regeneratorRuntime.async(function _callee2$(_context3) {
          while (1) {
            switch (_context3.prev = _context3.next) {
              case 0:
                postRequest = null;
                points = JSON.parse(response);

                if (!(typeof points != "undefined")) {
                  _context3.next = 7;
                  break;
                }

                if (jQuery.isEmptyObject(points)) {
                  _context3.next = 7;
                  break;
                }

                if (!(points.status == "OK")) {
                  _context3.next = 7;
                  break;
                }

                _context3.next = 7;
                return regeneratorRuntime.awrap(Promise.all([refreshPointsFromData(points
                /*,callback*/
                )]));

              case 7:
                resolve();

              case 8:
              case "end":
                return _context3.stop();
            }
          }
        });
      }, "text");
    });
  }

  function filterMap(map, markers) {
    if (markers.length) {
      var bounds = map.getBounds();
      var found = 0;

      for (var i in markers) {
        if (bounds.contains(markers[i].getLatLng())) {
          $('#point-details-' + markers[i].customPointId).show();
          found++;
        } else {
          $('#point-details-' + markers[i].customPointId).hide();
        }
      }

      if (!found && refreshing) {
        displayMessage("searching");
      } else if (!found) {
        displayMessage("empty");
      } else {
        displayMessage("");
      }
    }
  }

  function displayMessage(type) {
    $("#mapPopup .pointsList .choose-point-info").show();
    $("#mapPopup .pointsList .choose-point-info").hide();
    $("#mapPopup .pointsList .choose-point-" + type).show();
  }

  function createSearchPointSuccessHandler() {
    var mode = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 17;
    return function (response) {
      response = JSON.parse(response);
      var isArray = Array.isArray(response);
      var isEmptyArray = isArray ? response.length === 0 : false;

      if (response && !isEmptyArray) {
        if (isArray) {
          response = response[0];
        }

        ;
        var latlng = {
          lat: parseFloat(response.lat),
          lng: parseFloat(response.lon)
        };
        map.setView(latlng, mode);
        setTimeout(function () {
          filterMap(map, markers);
        }, 50);
      } else {
        filterMap(map, markers);
        displayMessage("notfound");
      }
    };
  }

  function searchPoint() {
    var postcode = /([0-9]{2}-[0-9]{3})|([0-9]{5})/;
    var regInpost = /(\P\O\P\-)?[a-zA-Z]{3}[0-9]{1,3}[a-zA-Z]{0,2}/;
    var regPWR = /[a-zA-Z0-9]{2}-[0-9]{6}-[0-9]{2}-[0-9]{2}/;
    var regDPD = /[a-zA-Z]{2}[0-9]{5,6}/;
    var regDHL = /[0-9]{6,8}/;
    var regPP = /(UP|PP|DER|FUP)\W[a-zA-ZęĘóÓąĄśŚłŁżŻźŹćĆńŃ]{3,}/;
    if (searchValue == $('#search-input').val()) return;
    searchValue = $('#search-input').val();
    $('.choose-point-details').css("display", "none");
    $('.choose-point-searching').css("display", "inline-block");

    if (searchValue == "") {
      $('.choose-point-details').css("display", "inline-block");
      $('.choose-point-searching').css("display", "none");
      var latlng = {
        lat: 52.209402,
        lng: 19.302979
      };
      map.setView(latlng, 5);
    } else if (regInpost.test(searchValue)) {
      $.post(epaka_object.api_endpoint + "/map?endpoint=" + encodeURIComponent("map_popup/searchInpost"), {
        q: searchValue
      }, createSearchPointSuccessHandler(), "json");
    } else if (regPWR.test(searchValue)) {
      $.post(epaka_object.api_endpoint + "/map?endpoint=" + encodeURIComponent("map_popup/searchPWR"), {
        q: searchValue
      }, createSearchPointSuccessHandler(), "json");
    } else if (regDPD.test(searchValue)) {
      $.post(epaka_object.api_endpoint + "/map?endpoint=" + encodeURIComponent("map_popup/searchDPD"), {
        q: searchValue
      }, createSearchPointSuccessHandler(), "json");
    } else if (regDHL.test(searchValue)) {
      $.post(epaka_object.api_endpoint + "/map?endpoint=" + encodeURIComponent("map_popup/searchDHLSP"), {
        q: searchValue
      }, createSearchPointSuccessHandler(), "json");
    } else if (regPP.test(searchValue)) {
      $.post(epaka_object.api_endpoint + "/map?endpoint=" + encodeURIComponent("map_popup/searchPP"), {
        q: searchValue
      }, createSearchPointSuccessHandler(), "json");
    } else {
      var params = {
        limit: '1',
        countrycodes: settings.country,
        format: 'json',
        addressdetails: '1'
      };

      if (postcode.test(searchValue)) {
        params.postalcode = postcode.exec(searchValue)[0];
      } else {
        params.q = searchValue;
        params.limit = 10;
      }

      var sanitizedPayload = encodeURIComponent("map.php?" + $.param(params));
      $.ajax({
        type: 'GET',
        dataType: 'json',
        url: epaka_object.api_endpoint + "/map?endpoint=mapSearch&payload=" + sanitizedPayload,
        success: createSearchPointSuccessHandler(14)
      });
    }

    return false;
  }

  function refreshPointsFromData(points, callback) {
    return new Promise(function (resolve, reject) {
      $('.choose-point-searching').css("display", "none");
      var pointsArray = null;

      if (Array.isArray(points.points.point)) {
        pointsArray = points.points.point;
      } else {
        pointsArray = [points.points.point];
      }

      if (pointsArray === undefined) {
        reject();
        return;
      }

      ;

      if (refresh < lastRefresh) {
        reject();
        return;
      }

      lastRefresh = refresh;
      var pointsToDelete = [];
      var pointsToKeep = [];
      var pointsToAdd = [];
      markers.forEach(function (el, index) {
        var test = false;

        for (var i = 0; i < pointsArray.length; i++) {
          if (el.fullPointData.id == pointsArray[i].id) {
            pointsToKeep.push(el);
            test = true;
            break;
          }
        }

        if (!test) {
          pointsToDelete.push(el);
        }
      });
      pointsToDelete.forEach(function (el) {
        $("#mapPopup #point-details-" + el.fullPointData.id).remove();

        if (el.fullPointData.source !== undefined) {
          $("#mapPopup  #point-details-" + el.fullPointData.id + "---" + el.fullPointData.source.id).remove();
        }

        cluster.removeLayer(el);
      });
      markers = [].concat(pointsToKeep);

      for (var i = 0; i < pointsArray.length; i++) {
        var point = pointsArray[i];
        if (point === undefined) continue;
        var newPoint = true;

        for (var x = 0; x < pointsToKeep.length; x++) {
          if (point.id == pointsToKeep[x].fullPointData.id) {
            newPoint = false;
            break;
          }
        }

        if (newPoint) {
          var icon = icons["red"];

          if (point.source !== undefined) {
            if (point.source.marker !== undefined) {
              icon = icons[point.source.marker];
            }
          }

          var marker = L.marker({
            lat: point.lat,
            lng: point.lng
          }, {
            title: point.name,
            icon: icon
          });
          markers.push(marker);

          if (point.source !== undefined) {
            marker.customPointId = point.id + "---" + point.source.id;
          } else {
            marker.customPointId = point.id;
          }

          marker.fullPointData = point;
          cluster.addLayer(marker);
          var choosePointDetailsElement = "<div id='point-details-" + marker.customPointId + "' class='row choose-point-details py-2 font-size-14px cursor-pointer mx-0 px-0'>" + "<div class='col-3 d-flex flex-column align-items-center justify-content-center px-3'>" + "<img class='img-fluid' src=''/>" + // "<span class='mt-2 font-size-12px font-weight-bold text-center'>"+(point.source.id == 0 ? point.name : point.id)+"</span>"+
          "</div>" + "<div class='col-9 d-flex flex-column'>" + "<div class='point-details-content'>" + (!point.street && !point.post_code && !point.other && point.description ? "<div class='font-weight-bold'>" + point.description + "</div>" : "<div class='font-weight-bold'>" + point.name + "</div>") + (point.street ? "<div>" + point.street + " " + point.number + (typeof point.local_number == 'string' ? "/" + point.local_number + " " : " ") + "</div>" : "") + (point.post_code ? "<div>" + point.post_code + " " + point.city + "</div>" : "") + "</div>" + (typeof point.other != 'undefined' && typeof point.other.replace == 'function' ? "<div class='font-size-12px font-size-md-14px font-weight-semibold mt-3'>" + point.other.replace(/;/g, "<br />") + "</div>" : "") + "</div>" + "<div style='display: none;' class='listDbClickButton pointButton'>" + "<span style='text-align: center;'>Wybierz punkt</span>" + "</div>" + "</div>";
          $("#mapPopup .pointsList").append(choosePointDetailsElement);
          marker.bindPopup("<div style='width: 63px;'>" + "<img class='img-fluid' src=''/>" + "</div>" + "<div class='mt-3' style='width: 100%;'>" + "<span class='font-size-13px font-weight-bold text-center'>" + point.name + "</span>" + "<div class='font-size-13px font-weight-regular'>" + (!point.street && !point.post_code && !point.other ? "<div>" + (point.description ? point.description : point.name ? point.name : '') + "</div>" : "") + (point.street ? "<div>" + point.street + " " + point.number + (typeof point.local_number == 'string' ? "/" + point.local_number + " " : " ") + "</div>" : "") + (point.post_code ? "<div>" + point.post_code + " " + point.city + "</div>" : "") + (typeof point.other != 'undefined' && typeof point.other.replace == 'function' ? "<div>" + point.other.replace(/;/g, "<br />") + "</div>" : "") + "</div>" + "<div id='choosePoint' class='pointButton' style='margin-left: auto;margin-right: auto;' data-id='" + marker.customPointId + "'>" + "<span style='text-align: center;'>Wybierz punkt</span>" + "</div>" + "</div>", {
            className: point.source !== undefined ? point.id + "---" + point.source.id : point.id
          });
          var dblClickWindow,
              canDblClick = false;
          marker.on("click", function () {
            var element = $('#point-details-' + this.customPointId);

            if (element.hasClass("active") && canDblClick) {
              $(".accept-point").click();
              canDblClick = false;
            } else {
              window.dontZoomOnPointClick = 1;
              element.click();
              $(document).off("click", "#choosePoint");
              $(document).on("click", "#choosePoint", function () {
                $('#mapPopup .pointsList #point-details-' + $(this).attr("data-id") + ' .listDbClickButton').click();
              });
              this.openPopup();
              setTimeout(function () {
                dblClickWindow = setTimeout(function () {
                  canDblClick = false;
                }, 500);
                canDblClick = true;
              }, 50);
            }
          });
          $("#mapPopup .pointsList .choose-point-details").click(function () {
            $("#mapPopup .pointsList .choose-point-details").removeClass("active");
            $("#mapPopup .pointsList .choose-point-details .listDbClickButton").css('display', 'none');
            var id = $(this).attr("id").split("point-details-")[1];

            if (typeof window.dontZoomOnPointClick == "undefined") {
              var marker = markers.filter(function (element) {
                if (element.customPointId == id) {
                  return true;
                }

                return false;
              });
              marker[0].openPopup();
              map.setView(marker[0]._latlng, 20);
              scrollToElem = $(this);
            }

            window.dontZoomOnPointClick = undefined;
            $(this).addClass("active");
            $(this).find('.listDbClickButton').css('display', 'inline-block');
          });
          $("#mapPopup .pointsList .listDbClickButton").off("click");
          $("#mapPopup .pointsList .listDbClickButton").click(function () {
            var id = $(this).parent().attr("id").split("point-details-")[1];
            var marker = markers.filter(function (element) {
              if (element.customPointId == id) {
                return true;
              }

              return false;
            });
            $($(elementInvoking).siblings()[0]).val(marker[0].fullPointData.id);
            $(elementInvoking).val(marker[0].fullPointData.name);
            $('#epaka_paczkomat').val(marker[0].fullPointData.id);
            $('#epaka_paczkomat_opis').val(marker[0].fullPointData.name);
            $(elementInvoking).change();
            $(".popupCloseButton").click();
          });
        }
      }

      refreshing = false;
      filterMap(map, markers);
      resolve();
    });
  }

  window.mapBind = function () {
    $(".showMapOnClick").off("click");
    $(".showMapOnClick").off("change");
    $(".pointClear").off("click");
    $('.popupCloseButton').off("click");
    $('#search').off("click");
    $('#mapPopup .menu-control-button').off("click");
    $(".showMapOnClick").click(function _callee3(event) {
      var courierSource;
      return regeneratorRuntime.async(function _callee3$(_context4) {
        while (1) {
          switch (_context4.prev = _context4.next) {
            case 0:
              $('#mapPopup').show();
              courierSource = {
                autoClose: true,
                sourceUrl: $(this).attr("data-map-source-url"),
                type: "point",
                title: "Wybierz punkt odbioru " + $(this).attr("data-map-source-name"),
                country: "PL"
              };
              _context4.next = 4;
              return regeneratorRuntime.awrap(Promise.all([window.ShowMap(event, courierSource)]));

            case 4:
            case "end":
              return _context4.stop();
          }
        }
      }, null, this);
    });
    $(".showMapOnClick").each(function () {
      if ($(this).val() != "") {
        $(this).parent().find(".pointClear").show();
      } else {
        $(this).parent().find(".pointClear").hide();
      }
    });
    $(".showMapOnClick").change(function () {
      if ($(this).val() != "") {
        $(this).parent().find(".pointClear").show();
      } else {
        $(this).parent().find(".pointClear").hide();
      }
    });
    $(".pointClear").click(function () {
      $(this).parent().find("input").each(function () {
        $(this).val("");
        $(this).change();
      });
    });
    $('.popupCloseButton').click(function () {
      markers.forEach(function (marker) {
        $("#mapPopup #point-details-" + marker.fullPointData.id).remove();

        if (marker.fullPointData.source !== undefined) {
          $("#mapPopup  #point-details-" + marker.fullPointData.id + "---" + marker.fullPointData.source.id).remove();
        }

        cluster.removeLayer(marker);
      });
      markers = [];
      $('#mapPopup').hide();
    });
    $('#search').click(function () {
      searchPoint();
    });
    $('#mapPopup .menu-control-button').click(function () {
      if ($("#mapPopup .menu").is(":visible")) {
        $("#mapPopup .menu").hide();
      } else {
        $("#mapPopup .menu").show();
      }

      return false;
    });
    $("#mapPopup .localise-me").click(function () {
      if ('geolocation' in navigator) {
        /* geolocation is available */
        navigator.geolocation.getCurrentPosition(function (position) {
          var latlng = {
            lat: position.coords.latitude,
            lng: position.coords.longitude
          };
          map.setView(latlng, 11);
        });
      } else {
        /* geolocation IS NOT available */
        alert("Lokalizacja nie jest wspierana przez twoją przeglądarkę.");
      }
    });
  };

  $(window).load(function () {
    window.mapBind();
  });
})(jQuery);