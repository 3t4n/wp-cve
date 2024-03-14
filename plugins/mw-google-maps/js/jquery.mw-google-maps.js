/**
 * Name: jquery.mw-google-maps.js
 * Plugin URI: http://2inc.org/blog/category/products/wordpress_plugins/mw-google-maps/
 * Description: Google Maps API v3 操作
 * Version: 1.2.1
 * Author: Takashi Kitajima
 * Author URI: http://2inc.org
 * Created : August 28, 2013
 * Modified: February 25, 2015
 * License: GPL2
 *
 * Copyright 2013 Takashi Kitajima (email : inc@2inc.org)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * ========== 使い方 ==========
 *	var gmap = $( '#map' ).mw_google_maps();
 *	// マーカーの追加
 *	gmap.mw_google_maps( 'addMarker', {
 *		latitude : 11111111,
 *		longitude: 11111111,
 *		title    : 'hoge',
 *		draggable: false
 *	} );
 *	// ジオコード
 *	gmap.mw_google_maps( 'geocode', {
 *		btn      : $( 'btn' ),
 *		address  : $( '#address' ),
 *		latitude : $( '#latitude' ),
 *		longitude: $( '#longitude' )
 *	} );
 *	// レンダリング
 *	gmap.mw_google_maps( 'render' );
 */
;( function( $ ) {

	var plugname = 'mw_google_maps';
	var useRoute = false;

	var methods = {
		/**
		 * init
		 * 初期化
		 */
		init   : function( params ) {
			return this.each( function() {
				var data = $( this ).data( plugname );
				if ( !data ) {
					var defaults = {
						latitude : 35.71012566481748,
						longitude: 139.81149673461914,
						zoom     : 13
					};
					$( this ).data( plugname, {
						config : $.extend( defaults, params ),
						points : [],
						geocode: {}
					} );
				}
			} );
		},

		/**
		 * addMarker
		 * マーカーの追加
		 */
		addMarker: function( params ) {
			return this.each( function() {
				var data = $( this ).data( plugname );
				var defaults = {
					latitude : 35.71012566481748,
					longitude: 139.81149673461914,
					title    : '',
					draggable: false
				}
				data.points.push( $.extend( defaults, params ) );
			} );
		},

		/**
		 * render
		 * レンダリング
		 */
		render: function( params ) {
			return this.each( function() {
				var gmap = $( this );
				var data = gmap.data( plugname );

				// 初期位置設定
				var position = new google.maps.LatLng(
					data.config.latitude,
					data.config.longitude
				);
				// マップ表示
				var map = new google.maps.Map( gmap.get( 0 ), {
					center: position,
					zoom: data.config.zoom,
					mapTypeId: google.maps.MapTypeId.ROADMAP,
					scrollwheel: false,
					scaleControl: true
				} );
				// マーカー設置
				if ( data.points.length < 1 ||
					 typeof( data.points[0].latitude ) == 'undefined' ||
					 typeof( data.points[0].longitude ) == 'undefined' )
					return true; // = jQuery's continue.

				if ( useRoute === true ) {
					// ルートを表示するマップを設定
					var directionsRenderer = new google.maps.DirectionsRenderer();
					directionsRenderer.setMap( map );

					// 経由地点を設定（無料版は最大8箇所）
					var wayPoints = [];
					$.each( data.points, function( key, val ) {
						wayPoints.push( {
							location: new google.maps.LatLng(
								val.latitude,
								val.longitude
							)
						} );
					} );

					// 開始地点と終了地点、ルーティングの種類の設定
					var request = {
						origin: wayPoints[0].location,
						destination: wayPoints.pop().location,
						travelMode: google.maps.DirectionsTravelMode.DRIVING, // or BICYCLING, TRANSIT, WALKING
						waypoints: wayPoints
					};

					// ルート検索を行う
					var directionsService = new google.maps.DirectionsService();
					directionsService.route( request, function( result, status ) {
						if ( status == google.maps.DirectionsStatus.OK ) {
							directionsRenderer.setDirections( result );
							directionsRenderer.setOptions( {
								suppressMarkers: true
							} );
						}
					} );
				}

				// アイコン
				var getIcon = function( key ) {
					if ( useRoute === true ) {
						return 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=' + ( key + 1 ) + '|F96757|000000';
					}
					return '';
				}

				var minLat = data.points[0].latitude;
				var maxLat = data.points[0].latitude;
				var minLng = data.points[0].longitude;
				var maxLng = data.points[0].longitude;

				var currentInfoWindow = null;
				$.each( data.points, function( key, val ) {
					var marker = new google.maps.Marker( {
						position: new google.maps.LatLng(
							val.latitude,
							val.longitude
						),
						draggable: val.draggable,
						map: map,
						icon: getIcon( key )
					} );

					if ( typeof( val.title != 'undefined' ) ) {
						var infowindow = new google.maps.InfoWindow( {
							content: val.title
						} );
						google.maps.event.addListener( marker, 'click', function() {
							if ( currentInfoWindow ) {
								currentInfoWindow.close();
							}
							infowindow.open( map, marker );
							currentInfoWindow = infowindow;
						} );
					}

					if ( data.points.length > 1 ) {
						var Lat = val.latitude;
						var Lng = val.longitude;
						//中心座標を取得するため、緯度経度の最小値と最大値を取得する
						if ( Lat < minLat ) minLat = Lat;
						if ( Lat > maxLat ) maxLat = Lat;
						if ( Lng < minLng ) minLng = Lng;
						if ( Lng > maxLng ) maxLng = Lng;
					}

					render_geocode( data, map, marker );
				} );

				if ( data.points.length > 1 ) {
					//北西端の座標を設定
					var sw = new google.maps.LatLng( maxLat, minLng );
					//東南端の座標を設定
					var ne = new google.maps.LatLng( minLat, maxLng );
					//範囲を設定
					var bounds = new google.maps.LatLngBounds( sw, ne );
					//マーカーが全て収まるように地図の中心とズームを調整して表示
					map.fitBounds( bounds );
				} else {
					var latlng = new google.maps.LatLng( data.points[0].latitude, data.points[0].longitude );
					map.setCenter( latlng );
				}

			} );
		},

		/**
		 * geocode
		 * geocode用のテキストフィールド等を定義
		 * テキストボックスから住所を取得
		 * 検索ボタン, 住所フィールド, 緯度フィールド, 経度フィールドが必要
		 * config.points.length == 1 のときのみ
		 */
		geocode: function( params ) {
			return this.each( function() {
				var data = $( this ).data( plugname );
				var defaults = {
					btn      : '',
					address  : '',
					latitude : '',
					longitude: ''
				}
				data.geocode = $.extend( defaults, params );
			})
		},

		/**
		 * useRoute
		 * ルートサービスを使用する
		 */
		useRoute: function() {
			useRoute = true;
		}
	};

	function render_geocode( data, map, marker ) {
		if ( !(
			 typeof( data.geocode.btn ) !== 'undefined' &&
			 typeof( data.geocode.address ) !== 'undefined' &&
			 typeof( data.geocode.latitude ) !== 'undefined' &&
			 typeof( data.geocode.longitude ) !== 'undefined' &&
			 typeof( data.geocode.zoom ) !== 'undefined' &&
			 data.geocode.btn.length &&
			 data.geocode.address.length &&
			 data.geocode.latitude.length &&
			 data.geocode.longitude.length &&
			 data.geocode.zoom.length &&
			 data.points.length == 1 )
			)
			return true;

		data.geocode.btn.click( function() {
			var geocoder = new google.maps.Geocoder();
			// ジオコーディングを依頼する
			geocoder.geocode(
				{
					address: data.geocode.address.val()
				},
				function( results, status ) {
					if ( status == google.maps.GeocoderStatus.OK ) {
						map.setCenter(results[0].geometry.location);
						marker.setOptions({
							position: results[0].geometry.location
						});
						setInputValueLatLng( results[0].geometry.location.lat(), results[0].geometry.location.lng() );
					} else {
						alert( 'Failed!' );
					}
				}
			);
		} );

		// マーカーのドロップ（ドラッグ終了）時のイベント
		google.maps.event.addListener( marker, 'dragend', function( event ) {
			var latlng = new google.maps.LatLng( event.latLng.lat(), event.latLng.lng() );
			map.setCenter( latlng );
			data.geocode.address.val( '' );
			setInputValueLatLng( event.latLng.lat(), event.latLng.lng() );
		} );

		google.maps.event.addListener( map, 'zoom_changed', function() {
			var zoom = map.getZoom();
			data.geocode.zoom.val( zoom );
		} );

		function setInputValueLatLng( lat, lng ) {
			data.geocode.latitude.val( lat );
			data.geocode.longitude.val( lng );
		}
	}

	$.fn[plugname] = function( method ) {
		if ( methods[method] ) {
			return methods[method]
			.apply( this, Array.prototype.slice.call( arguments, 1 ) );
		} else if ( typeof method === 'object' || ! method ) {
			return methods.init.apply( this, arguments );
		} else {
			$.error( 'Method ' +  method + ' does not exist on jQuery.' + plugname );
			return this;
		}
	};
} )( jQuery );