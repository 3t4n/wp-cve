function InfoBox(a){a=a||{},google.maps.OverlayView.apply(this,arguments),this.content_=a.content||"",this.disableAutoPan_=a.disableAutoPan||!1,this.maxWidth_=a.maxWidth||0,this.pixelOffset_=a.pixelOffset||new google.maps.Size(0,0),this.position_=a.position||new google.maps.LatLng(0,0),this.zIndex_=a.zIndex||null,this.boxClass_=a.boxClass||"infoBox",this.boxStyle_=a.boxStyle||{},this.closeBoxMargin_=a.closeBoxMargin||"2px",this.closeBoxURL_=a.closeBoxURL||"http://www.google.com/intl/en_us/mapfiles/close.gif",""===a.closeBoxURL&&(this.closeBoxURL_=""),this.infoBoxClearance_=a.infoBoxClearance||new google.maps.Size(1,1),void 0===a.visible&&(void 0===a.isHidden?a.visible=!0:a.visible=!a.isHidden),this.isHidden_=!a.visible,this.alignBottom_=a.alignBottom||!1,this.pane_=a.pane||"floatPane",this.enableEventPropagation_=a.enableEventPropagation||!1,this.div_=null,this.closeListener_=null,this.moveListener_=null,this.mapListener_=null,this.contextListener_=null,this.eventListeners_=null,this.fixedWidthSet_=null}InfoBox.prototype=new google.maps.OverlayView,InfoBox.prototype.createInfoBoxDiv_=function(){var a,b,c,d=this,e=function(a){a.cancelBubble=!0,a.stopPropagation&&a.stopPropagation()},f=function(a){a.returnValue=!1,a.preventDefault&&a.preventDefault(),d.enableEventPropagation_||e(a)};if(!this.div_){if(this.div_=document.createElement("div"),this.setBoxStyle_(),void 0===this.content_.nodeType?this.div_.innerHTML=this.getCloseBoxImg_()+this.content_:(this.div_.innerHTML=this.getCloseBoxImg_(),this.div_.appendChild(this.content_)),this.getPanes()[this.pane_].appendChild(this.div_),this.addClickHandler_(),this.div_.style.width?this.fixedWidthSet_=!0:0!==this.maxWidth_&&this.div_.offsetWidth>this.maxWidth_?(this.div_.style.width=this.maxWidth_,this.div_.style.overflow="auto",this.fixedWidthSet_=!0):(c=this.getBoxWidths_(),this.div_.style.width=this.div_.offsetWidth-c.left-c.right+"px",this.fixedWidthSet_=!1),this.panBox_(this.disableAutoPan_),!this.enableEventPropagation_){for(this.eventListeners_=[],b=["mousedown","mouseover","mouseout","mouseup","click","dblclick","touchstart","touchend","touchmove"],a=0;a<b.length;a++)this.eventListeners_.push(google.maps.event.addDomListener(this.div_,b[a],e));this.eventListeners_.push(google.maps.event.addDomListener(this.div_,"mouseover",function(a){this.style.cursor="default"}))}this.contextListener_=google.maps.event.addDomListener(this.div_,"contextmenu",f),google.maps.event.trigger(this,"domready")}},InfoBox.prototype.getCloseBoxImg_=function(){var a="";return""!==this.closeBoxURL_&&(a="<img",a+=" src='"+this.closeBoxURL_+"'",a+=" align=right",a+=" style='",a+=" position: relative;",a+=" cursor: pointer;",a+=" margin: "+this.closeBoxMargin_+";",a+="'>"),a},InfoBox.prototype.addClickHandler_=function(){var a;""!==this.closeBoxURL_?(a=this.div_.firstChild,this.closeListener_=google.maps.event.addDomListener(a,"click",this.getCloseClickHandler_())):this.closeListener_=null},InfoBox.prototype.getCloseClickHandler_=function(){var a=this;return function(b){b.cancelBubble=!0,b.stopPropagation&&b.stopPropagation(),google.maps.event.trigger(a,"closeclick"),a.close()}},InfoBox.prototype.panBox_=function(a){var b,d=0,e=0;if(!a&&(b=this.getMap())instanceof google.maps.Map){b.getBounds().contains(this.position_)||b.setCenter(this.position_),b.getBounds();var f=b.getDiv(),g=f.offsetWidth,h=f.offsetHeight,i=this.pixelOffset_.width,j=this.pixelOffset_.height,k=this.div_.offsetWidth,l=this.div_.offsetHeight,m=this.infoBoxClearance_.width,n=this.infoBoxClearance_.height,o=this.getProjection().fromLatLngToContainerPixel(this.position_);if(o.x<-i+m?d=o.x+i-m:o.x+k+i+m>g&&(d=o.x+k+i+m-g),this.alignBottom_?o.y<-j+n+l?e=o.y+j-n-l:o.y+j+n>h&&(e=o.y+j+n-h):o.y<-j+n?e=o.y+j-n:o.y+l+j+n>h&&(e=o.y+l+j+n-h),0!==d||0!==e){b.getCenter();b.panBy(d,e)}}},InfoBox.prototype.setBoxStyle_=function(){var a,b;if(this.div_){this.div_.className=this.boxClass_,this.div_.style.cssText="",b=this.boxStyle_;for(a in b)b.hasOwnProperty(a)&&(this.div_.style[a]=b[a]);this.div_.style.WebkitTransform="translateZ(0)",void 0!==this.div_.style.opacity&&""!==this.div_.style.opacity&&(this.div_.style.MsFilter='"progid:DXImageTransform.Microsoft.Alpha(Opacity='+100*this.div_.style.opacity+')"',this.div_.style.filter="alpha(opacity="+100*this.div_.style.opacity+")"),this.div_.style.position="absolute",this.div_.style.visibility="hidden",null!==this.zIndex_&&(this.div_.style.zIndex=this.zIndex_)}},InfoBox.prototype.getBoxWidths_=function(){var a,b={top:0,bottom:0,left:0,right:0},c=this.div_;return document.defaultView&&document.defaultView.getComputedStyle?(a=c.ownerDocument.defaultView.getComputedStyle(c,""))&&(b.top=parseInt(a.borderTopWidth,10)||0,b.bottom=parseInt(a.borderBottomWidth,10)||0,b.left=parseInt(a.borderLeftWidth,10)||0,b.right=parseInt(a.borderRightWidth,10)||0):document.documentElement.currentStyle&&c.currentStyle&&(b.top=parseInt(c.currentStyle.borderTopWidth,10)||0,b.bottom=parseInt(c.currentStyle.borderBottomWidth,10)||0,b.left=parseInt(c.currentStyle.borderLeftWidth,10)||0,b.right=parseInt(c.currentStyle.borderRightWidth,10)||0),b},InfoBox.prototype.onRemove=function(){this.div_&&(this.div_.parentNode.removeChild(this.div_),this.div_=null)},InfoBox.prototype.draw=function(){this.createInfoBoxDiv_();var a=this.getProjection().fromLatLngToDivPixel(this.position_);this.div_.style.left=a.x+this.pixelOffset_.width+"px",this.alignBottom_?this.div_.style.bottom=-(a.y+this.pixelOffset_.height)+"px":this.div_.style.top=a.y+this.pixelOffset_.height+"px",this.isHidden_?this.div_.style.visibility="hidden":this.div_.style.visibility="visible"},InfoBox.prototype.setOptions=function(a){void 0!==a.boxClass&&(this.boxClass_=a.boxClass,this.setBoxStyle_()),void 0!==a.boxStyle&&(this.boxStyle_=a.boxStyle,this.setBoxStyle_()),void 0!==a.content&&this.setContent(a.content),void 0!==a.disableAutoPan&&(this.disableAutoPan_=a.disableAutoPan),void 0!==a.maxWidth&&(this.maxWidth_=a.maxWidth),void 0!==a.pixelOffset&&(this.pixelOffset_=a.pixelOffset),void 0!==a.alignBottom&&(this.alignBottom_=a.alignBottom),void 0!==a.position&&this.setPosition(a.position),void 0!==a.zIndex&&this.setZIndex(a.zIndex),void 0!==a.closeBoxMargin&&(this.closeBoxMargin_=a.closeBoxMargin),void 0!==a.closeBoxURL&&(this.closeBoxURL_=a.closeBoxURL),void 0!==a.infoBoxClearance&&(this.infoBoxClearance_=a.infoBoxClearance),void 0!==a.isHidden&&(this.isHidden_=a.isHidden),void 0!==a.visible&&(this.isHidden_=!a.visible),void 0!==a.enableEventPropagation&&(this.enableEventPropagation_=a.enableEventPropagation),this.div_&&this.draw()},InfoBox.prototype.setContent=function(a){this.content_=a,this.div_&&(this.closeListener_&&(google.maps.event.removeListener(this.closeListener_),this.closeListener_=null),this.fixedWidthSet_||(this.div_.style.width=""),void 0===a.nodeType?this.div_.innerHTML=this.getCloseBoxImg_()+a:(this.div_.innerHTML=this.getCloseBoxImg_(),this.div_.appendChild(a)),this.fixedWidthSet_||(this.div_.style.width=this.div_.offsetWidth+"px",void 0===a.nodeType?this.div_.innerHTML=this.getCloseBoxImg_()+a:(this.div_.innerHTML=this.getCloseBoxImg_(),this.div_.appendChild(a))),this.addClickHandler_()),google.maps.event.trigger(this,"content_changed")},InfoBox.prototype.setPosition=function(a){this.position_=a,this.div_&&this.draw(),google.maps.event.trigger(this,"position_changed")},InfoBox.prototype.setZIndex=function(a){this.zIndex_=a,this.div_&&(this.div_.style.zIndex=a),google.maps.event.trigger(this,"zindex_changed")},InfoBox.prototype.setVisible=function(a){this.isHidden_=!a,this.div_&&(this.div_.style.visibility=this.isHidden_?"hidden":"visible")},InfoBox.prototype.getContent=function(){return this.content_},InfoBox.prototype.getPosition=function(){return this.position_},InfoBox.prototype.getZIndex=function(){return this.zIndex_},InfoBox.prototype.getVisible=function(){return void 0!==this.getMap()&&null!==this.getMap()&&!this.isHidden_},InfoBox.prototype.show=function(){this.isHidden_=!1,this.div_&&(this.div_.style.visibility="visible")},InfoBox.prototype.hide=function(){this.isHidden_=!0,this.div_&&(this.div_.style.visibility="hidden")},InfoBox.prototype.open=function(a,b){var c=this;b&&(this.position_=b.getPosition(),this.moveListener_=google.maps.event.addListener(b,"position_changed",function(){c.setPosition(this.getPosition())}),this.mapListener_=google.maps.event.addListener(b,"map_changed",function(){c.setMap(this.map)})),this.setMap(a),this.div_&&this.panBox_()},InfoBox.prototype.close=function(){var a;if(this.closeListener_&&(google.maps.event.removeListener(this.closeListener_),this.closeListener_=null),this.eventListeners_){for(a=0;a<this.eventListeners_.length;a++)google.maps.event.removeListener(this.eventListeners_[a]);this.eventListeners_=null}this.moveListener_&&(google.maps.event.removeListener(this.moveListener_),this.moveListener_=null),this.mapListener_&&(google.maps.event.removeListener(this.mapListener_),this.mapListener_=null),this.contextListener_&&(google.maps.event.removeListener(this.contextListener_),this.contextListener_=null),this.setMap(null)};
(function($){
    $(document).ready(function(){

        var dvls_city = $.parseJSON(devvn_localstore_array.local_address);
        var citySelect = $('#dvls_city');
        var districtSelect = $('#dvls_district');
        var oldValueCity = citySelect.data('value');
        var oldValueDistrict = districtSelect.data('value');
        $(dvls_city).each(function(index, value){
            var thisChecked = '';
            if(oldValueCity == value.id) {
                thisChecked = 'selected="selected"';
                $(value.district).each(function(index, value) {
                    var thisChecked = '';
                    if(oldValueDistrict == value.id) {
                        thisChecked = 'selected="selected"';
                    }
                    districtSelect.append('<option value="' + value.id + '" '+thisChecked+'>' + value.name + '</option>');
                });
            }
            citySelect.append('<option value="'+value.id+'" '+thisChecked+'>'+value.name+'</option>');
        });
        $(citySelect).on('change',function(){
            var thisval = $(this).val();
            districtSelect.html('<option value="null" selected>'+devvn_localstore_array.select_text+'</option>');
            $(dvls_city).each(function(index, value){
                if(thisval == value.id){
                    $(value.district).each(function(index, value) {
                        districtSelect.append('<option value="' + value.id + '">' + value.name + '</option>');
                    });
                    return false;
                }
            });
        });

        var autocomplete,map,markers = [],infoWindow,geocoder,dvls_loading = false;
        var mapDiv = $('#dvls_maps');
        
        function dvls_initMap() {
            var dvls_center = {lat: mapDiv.data('lat'), lng: mapDiv.data('lng')};
            map = new google.maps.Map(document.getElementById('dvls_maps'), {
                zoom: parseInt(devvn_localstore_array.maps_zoom),
                center: dvls_center,
                //scrollwheel: false,
            });
            /*var marker = new google.maps.Marker({
                position: dvls_center,
                map: map
            });*/
            infoWindow = new google.maps.InfoWindow();

            dvls_lastest_store();
        }
        dvls_initMap();
        $('.dvls_near_you').on('click',function () {
            var thisDisallow = $(this).data('disallow');
            if(thisDisallow == 1) return false;
            devvn_findstore_nearyou();
            return false;
        });
        function dvls_notsupport_geocoder(){
            $('.dvls_near_you').remove();
        }
        function dvls_disallow_geocoder(){
            $('.dvls_near_you').attr('data-disallow','1').html(devvn_localstore_array.labels.disallow_labels);
        }
        function devvn_findstore_nearyou(){
            if (navigator.geolocation) {
                dvls_before_load();
                navigator.geolocation.getCurrentPosition(successFunction, errorFunction);
            } else {
                dvls_notsupport_geocoder();
                dvls_lastest_store();
            }
            geocoder = new google.maps.Geocoder();
        }
        function errorFunction() {
            dvls_disallow_geocoder();
            dvls_lastest_store();
        }
        function successFunction(position) {
            var lat = position.coords.latitude;
            var lng = position.coords.longitude;
            var data = [];
            data['lat'] = lat;
            data['lng'] = lng;
            data['near'] = true;
            dvls_firstload_store(data);
        }

        function dvls_lastest_store() {
            var data = [];
            data['near'] = false;
            dvls_firstload_store(data);
        }

        function dvls_before_load(){
            dvls_loading = true;
            $('.dvls_maps_body').addClass('devvn_loading');
        }


        function dvls_ajax_load_success(response){
            if(response.success) {
                var maps_data = response.data;
                var bounds = new google.maps.LatLngBounds();
                var dataMarker = [];
                clearLocations(maps_data.length);
                $('.dvls_result_wrap').html('');
                for (var i = 0; i < maps_data.length; i++)
                {
                    var lat = (maps_data[i].maps_lat) ? maps_data[i].maps_lat : '';
                    var lng = (maps_data[i].maps_lng) ? maps_data[i].maps_lng : '';
                    var latlng = new google.maps.LatLng(lat,lng);
                    dataMarker['stt'] = i;
                    dataMarker['id'] = (maps_data[i].id) ? parseInt(maps_data[i].id) : '';
                    dataMarker['title'] = (maps_data[i].title) ? maps_data[i].title : '';
                    dataMarker['name'] = (maps_data[i].name) ? maps_data[i].name : '';
                    dataMarker['thumb'] = (maps_data[i].thumb) ? maps_data[i].thumb : '';
                    dataMarker['address'] = (maps_data[i].address) ? maps_data[i].address : '';
                    dataMarker['city'] = (maps_data[i].city) ? parseInt(maps_data[i].city) : '';
                    dataMarker['district'] = (maps_data[i].district) ? parseInt(maps_data[i].district) : '';
                    dataMarker['phone1'] = (maps_data[i].phone1) ? maps_data[i].phone1 : '';
                    dataMarker['phone2'] = (maps_data[i].phone2) ? maps_data[i].phone2 : '';
                    dataMarker['hotline1'] = (maps_data[i].hotline1) ? maps_data[i].hotline1 : '';
                    dataMarker['hotline2'] = (maps_data[i].hotline2) ? maps_data[i].hotline2 : '';
                    dataMarker['email'] = (maps_data[i].email) ? maps_data[i].email : '';
                    dataMarker['open'] = (maps_data[i].open) ? maps_data[i].open : '';
                    var marker_defaultURL = (devvn_localstore_array.marker_default[0]) ? devvn_localstore_array.marker_default[0] : '';
                    var marker_defaultH = (devvn_localstore_array.marker_default[2]) ? parseInt(devvn_localstore_array.marker_default[2]) : '';
                    dataMarker['marker'] = (maps_data[i].marker && maps_data[i].marker[0]) ? maps_data[i].marker[0] : marker_defaultURL ;
                    dataMarker['h_marker'] = (maps_data[i].marker && maps_data[i].marker[2]) ? parseInt(maps_data[i].marker[2]) : marker_defaultH;
                    dataMarker['maps_lat'] = lat;
                    dataMarker['maps_lng'] = lng;
                    dataMarker['latlng'] = latlng;
                    createMarker(dataMarker);
                    bounds.extend(latlng);
                    var has_thumb = '';
                    if(dataMarker['thumb']) has_thumb = 'has_thumb';

                    $html = '<div data-id="'+i+'" data-lat="'+lat+'" data-lng="'+lng+'" class="dvls_result_item '+has_thumb+'">';
                    if(dataMarker['thumb']) {
                        $html += '<div class="dvls_result_thumb"><img src="'+dataMarker['thumb']+'" alt=""></div>';
                    }
                    $html += '<div class="dvls_result_infor">';
                    if(dataMarker['name']) {
                        $html += '<h3>'+dataMarker['name']+'</h3>';
                    }else{
                        $html += '<h3>'+dataMarker['title']+'</h3>';
                    }
                    $html += '<p>'+dataMarker['address']+'</p>';
                    $html += '<p>';
                    if(dataMarker['hotline1']) {
                        $html += '<a href="tel:'+dataMarker['hotline1']+'">'+dataMarker['hotline1']+'</a>';
                    }
                    if(dataMarker['hotline1'] && dataMarker['hotline2']) {
                        $html += ' - ';
                    }
                    if(dataMarker['hotline2']) {
                        $html += '<a href="tel:'+dataMarker['hotline2']+'">'+dataMarker['hotline2']+'</a>';
                    }
                    $html += '</p>';
                    $html += '<a href="https://maps.google.com?saddr=Current+Location&daddr='+lat+','+lng+'" target="_blank" rel="nofollow">'+devvn_localstore_array.labels.get_directions+'</a>';
                    $html += '</div>';
                    $html += '</div>';

                    $('.dvls_result_wrap').append($html);
                }
                if (maps_data.length){
                    var listener = google.maps.event.addListener(map, "idle", function() {
                        google.maps.event.removeListener(listener);
                    });
                    map.fitBounds(bounds);
                    if(maps_data.length == 1){
                        map.setZoom(parseInt(devvn_localstore_array.maps_zoom));
                    }
                    $(".dvls_result_wrap").on('click','.dvls_result_item',function() {
                        var markerNum = $(this).data('id');
                        var latsvl = $(this).data('lat');
                        var lngsvl = $(this).data('lng');
                        google.maps.event.trigger(markers[markerNum], 'click');
                        map.setZoom(parseInt(devvn_localstore_array.maps_zoom));
                        var b = new google.maps.LatLng(latsvl,lngsvl);
                        map.setCenter(b);
                        $(".dvls_result_wrap .dvls_result_item").removeClass('active');
                        $(this).addClass('active');
                    });
                    /*$(".dvls_result_wrap").on('hover','.dvls_result_item',function() {
                        var markerNum = $(this).data('id');
                        var latsvl = $(this).data('lat');
                        var lngsvl = $(this).data('lng');
                        google.maps.event.trigger(markers[markerNum], 'click');
                    });*/

                    $('.dvls_result_status strong').html(maps_data.length);
                    $('.dvls_result_status').addClass('show');
                }
            }else {
                clearAllData();
            }
            $('.dvls_maps_body').removeClass('devvn_loading');
        }

        function dvls_firstload_store(data) {
            var near = (data['near']) ? true : false;
            var lat = (data['lat']) ? data['lat'] : '';
            var lng = (data['lng']) ? data['lng'] : '';
            var nonce = $('#dvls_nonce').val();
            var action = 'dvls_loadlastest_store';
            $.ajax({
                type: "post",
                dataType: "json",
                url: devvn_localstore_array.ajaxurl,
                data: {
                    action: action,
                    lat: lat,
                    lng: lng,
                    near: near,
                    nonce: nonce
                },
                context: this,
                beforeSend: function () {
                    dvls_before_load();
                },
                success: function (response) {
                    dvls_loading = false;
                    dvls_ajax_load_success(response)
                },
                error: function () {
                    dvls_loading = false;
                    $('.dvls_maps_body').removeClass('devvn_loading');
                }
            });
        }

        function clearAllData(){
            $('.dvls_result_wrap').html('');
            clearLocations();
            $('.dvls_result_status strong').html('0');
            $('.dvls_result_status').addClass('show');
            var b = new google.maps.LatLng(devvn_localstore_array.lat_default,devvn_localstore_array.lng_default);
            map.setCenter(b);
        }
        function dvls_loadresult(){
            var nonce = $('#dvls_nonce').val();
            var cityid = $('#dvls_city').val();
            var districtid = $('#dvls_district').val();
            if(!dvls_loading) {
                $.ajax({
                    type: "post",
                    dataType: "json",
                    url: devvn_localstore_array.ajaxurl,
                    data: {
                        action: "dvls_load_localstores",
                        cityid: cityid,
                        districtid: districtid,
                        nonce: nonce
                    },
                    context: this,
                    beforeSend: function () {
                        dvls_before_load();
                    },
                    success: function (response) {
                        dvls_loading = false;
                        dvls_ajax_load_success(response);
                    },
                    error: function () {
                        $('.dvls_maps_body').removeClass('devvn_loading');
                        dvls_loading = false;
                    }
                });
            }
        }
        var lastInfobox;
        var lastClickedMarker;
        function createMarker(dataMarker){
            var i = dataMarker.stt;
            var h_marker = (dataMarker.h_marker) ? dataMarker.h_marker : 35;
            var html = '';
            html += '<div class="item infobox" data-id="'+i+'" >';
            if(dataMarker.thumb) {
                html += '<div class="item_infobox_thumb"><img src="'+dataMarker.thumb+'" alt=""></div>';
            }
            html += '<div class="item_infobox_infor">';
            if(dataMarker.name) {
                html += '<h3>'+dataMarker.name+'</h3>';
            }else{
                html += '<h3>'+dataMarker.title+'</h3>';
            }
            html += '<p>'+dataMarker.address+'</p>';
            //tel
            if(dataMarker.hotline1 || dataMarker.hotline2) {
                html += '<p>'+devvn_localstore_array.labels.text_phone+': ';
                if (dataMarker.phone1) {
                    html += '<a href="tel:' + dataMarker.phone1 + '">' + dataMarker.phone1 + '</a>';
                }
                if (dataMarker.phone1 && dataMarker.phone2) {
                    html += ' - ';
                }
                if (dataMarker.phone2) {
                    html += '<a href="tel:' + dataMarker.phone2 + '">' + dataMarker.phone2 + '</a>';
                }
                html += '</p>';
            }
            //Hotline
            if(dataMarker.hotline1 || dataMarker.hotline2) {
                html += '<p>'+devvn_localstore_array.labels.text_hotline+': ';
                if (dataMarker.hotline1) {
                    html += '<a href="tel:' + dataMarker.hotline1 + '">' + dataMarker.hotline1 + '</a>';
                }
                if (dataMarker.hotline1 && dataMarker.hotline2) {
                    html += ' - ';
                }
                if (dataMarker.hotline2) {
                    html += '<a href="tel:' + dataMarker.hotline2 + '">' + dataMarker.hotline2 + '</a>';
                }
                html += '</p>';
            }
            //Email
            html += '<p>';
            if(dataMarker.email) {
                html += devvn_localstore_array.labels.text_email+': <a href="mailto:'+dataMarker.email+'">'+dataMarker.email+'</a>';
            }
            html += '</p>';
            //Open
            if(dataMarker.open) {
                html += '<p>'+devvn_localstore_array.labels.text_open+': '+dataMarker.open+'</p>';
            }
            html += '<a href="https://maps.google.com?saddr=Current+Location&daddr='+dataMarker.maps_lat+','+dataMarker.maps_lng+'" target="_blank" rel="nofollow">'+devvn_localstore_array.labels.get_directions+'</a>';
            html += '</div>';

            html += '</div>';
            //Thêm icon map cho mỗi địa chỉ
            var icon_image = "";
            if(dataMarker.marker != "")
                icon_image = new google.maps.MarkerImage(dataMarker.marker);
            else
                icon_image = new google.maps.MarkerImage(dataMarker.marker);

            var marker = '';

            if (dataMarker.marker)
                marker = new google.maps.Marker({map: map, icon: icon_image, animation: google.maps.Animation.DROP, position: dataMarker.latlng});
            else
                marker = new google.maps.Marker({map: map, position: dataMarker.latlng, animation: google.maps.Animation.DROP});
            var sWidth = -150;
            if (matchMedia('only screen and (max-width: 500px)').matches) {
                sWidth = -110;
            }
            google.maps.event.addListener(marker, 'click', function() {
                infoboxOptions = {
                    content: html,
                    disableAutoPan: false,
                    pixelOffset: new google.maps.Size(sWidth, -h_marker),
                    zIndex: null,
                    alignBottom: true,
                    boxClass: "infobox-wrapper",
                    closeBoxMargin: "15px 0px 0px 0px",
                    closeBoxURL: devvn_localstore_array.close_icon,
                    infoBoxClearance: new google.maps.Size(1, 1),
                    enableEventPropagation: false
                };

                if( lastInfobox != undefined ){
                    lastInfobox.close();
                }
                markers[i].infobox = new InfoBox(infoboxOptions);
                markers[i].infobox.open(map, this);
                lastInfobox = markers[i].infobox;
                $('.dvls_result_item.active').removeClass("active");
                $('.dvls_result_item[data-id='+i+']').addClass("active");
                google.maps.event.addListener(markers[i].infobox,'closeclick',function(){
                    $('.dvls_result_item.active').removeClass("active");
                });
            });

            markers.push(marker);
        }
        function clearLocations(n){
            infoWindow.close();
            for (var i = 0; i < markers.length; i++)
                markers[i].setMap(null);

            markers.length = 0;
        }
        $('.dvls-submit').on('click',function(){
            dvls_loadresult();
            return false;
        });
    });
})(jQuery);