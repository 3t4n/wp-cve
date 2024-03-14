<tr class="webshipper_drop_point">
   <th class="webshipper_drop_point_selector_title" style="word-break: break-word">
      <?php echo esc_html(___('pickup location')) ?>
   </th>

   <td class="webshipper_drop_point_container">
      <input type="hidden" name="ws_drop_point_blob" id="ws_drop_point_blob">

      <input type="button" class="button" style="float: none;" value="<?php echo esc_html(___('select pickup point')) ?>" id="webshipper-modal-open">

      <div id="webshipper-overlay" style="display: none"></div>
      <div id="webshipper-modal" style="display: none">
         <div class="webshipper-header">
            <span id="webshipper-modal-close" onclick="window.webshipper.modal.close()">×</span>
            <h2><?php echo esc_html(___('select pickup point')) ?></h2>
            <div class="webshipper-input-field-container large">
               <input class="webshipper-address-input" type="text" required="true" name="address" id="webshipper_address">
               <div class="input-focused-indicator"></div>
               <label for="webshipper_address"><?php echo esc_html(___('address')) ?></label>
            </div>
            <div class="webshipper-input-field-container">
               <input class="webshipper-address-input" type="text" required="true" name="zip" id="webshipper_zip">
               <div class="input-focused-indicator"></div>
               <label for="webshipper_zip"><?php echo esc_html(___('zip')) ?></label>
            </div>
            <div class="webshipper-input-field-container">
               <input class="webshipper-address-input" type="text" required="true" name="city" id="webshipper_city">
               <div class="input-focused-indicator"></div>
               <label for="webshipper_city"><?php echo esc_html(___('city')) ?></label>
            </div>
            <button type="button" id="webshipper-address-search" onclick="window.webshipper.modal.search()"><?php echo esc_html(___('search')) ?></button>
         </div>
         <div class="webshipper-body">
            <div id="webshipper-loader" class="webshipper-hidden">
               <img src="<?php echo esc_url(plugins_url('img/ajax.gif', dirname(__FILE__))) ?>">
            </div>
            <div id="webshipper-drop-points">
               <div id="webshipper-left">
                  <div id="webshipper-map" style="box-sizing: content-box;"></div>
               </div>
               <div id="webshipper-right">
                  <ul id="webshipper-drop-points-list"></ul>
               </div>
            </div>
         </div>
         <div class="webshipper-footer"></div>
      </div>

      <div id="webshipper-selected-drop-point" class="webshipper-hidden webshipper-selected-drop-point">
         <h3 id="webshipper-selected-name" class="webshipper-selected-name"></h3>
         <p id="webshipper-selected-address" class="webshipper-selected-address"></p>
         <p id="webshipper-selected-zip" class="webshipper-selected-zip"></p>
      </div>


      <style>
         @import url('https://fonts.googleapis.com/css?family=Lato&display=swap');

         :root {
            --color-dark-grey: #788598;
            --color-webshipper-yellow: #e0a681;
            --large-padding: 20px;
         }

         #webshipper-overlay {
            background: rgba(0, 0, 0, .2);
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            z-index: 999;
         }

         #webshipper-modal {
            background: #fff;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            border-radius: 8px;
            width: 920px;
            max-width: 90%;
            max-height: 90%;
            overflow: hidden;
            font-family: 'Lato', sans-serif;
            z-index: 1000;
         }

         #webshipper-modal-close {
            position: absolute;
            right: 10px;
            top: -10px;
            font-size: 30px;
            cursor: pointer;
         }

         #webshipper-loader {
            height: 600px;
         }

         #webshipper-loader img {
            margin: 0 auto;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
         }

         .webshipper-no-scroll {
            overflow: hidden;
            position: relative;
            height: 100%;
         }

         .webshipper-header {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 30px;
         }

         .webshipper-header h2 {
            flex: 1 0 300px;
            font-weight: 300;
            margin-top: 0;
            margin-bottom: 0;
            text-transform: capitalize;
            vertical-align: middle;
            display: inline-block;
         }

         .webshipper-input-field-container {
            position: relative;
            height: 54px;
            background-color: #fff;
            border: 1px solid #e2e5e6;
            width: 100%;
            border-radius: 6px;
            outline: none;
            overflow: hidden;
         }

         .webshipper-input-field-container label {
            position: absolute;
            top: 15px;
            padding-left: var(--large-padding);
            font-size: 14px;
            font-weight: normal;
            color: var(--color-dark-grey);
            transition: 0.2s ease all;
            pointer-events: none;
         }

         .webshipper-input-field-container input:focus~label,
         .webshipper-input-field-container input:valid~label {
            top: 8px;
            color: var(--color-dark-grey);
            font-size: 11px;
            text-transform: uppercase;
         }

         .webshipper-input-field-container .input-focused-indicator {
            position: absolute;
            opacity: 0;
            left: 0;
            height: 0;
            width: 0;
            bottom: 0;
            background-color: var(--color-webshipper-yellow);
            transition: height 0.2s ease-in-out;
         }

         /* Set active state on bottom of input-field */
         .webshipper-input-field-container input:focus {
            outline: none;
         }

         .webshipper-input-field-container input:focus~.input-focused-indicator {
            opacity: 1;
            height: 3px;
            width: 100%;
         }

         .webshipper-address-input {
            display: block;
            width: 100%;
            height: 100%;
            padding-top: 11px;
            padding-left: var(--large-padding);
            color: #0b2770;
            font-size: 14px;
            background: transparent;
            border: none;
            border-radius: 6px;
         }

         .webshipper-input-field-container.large {
            flex: 1 0 180px;
         }

         .webshipper-address-input:focus {
            outline: none
         }

         /* Copy-paste of Storybook "secondary" rendered action-button */
         #webshipper-address-search {
            margin-right: 30px;
            margin-left: 10px;
            height: 36px;
            padding: 10px 20px 10px 20px;
            font-size: 12px;
            color: #fff;
            background-color: #032447;
            box-shadow: 0 0 0 1px #fff;
            transition-duration: .2s;
            position: relative;
            display: inline-flex;
            align-items: center;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
            font-size: 12px;
            font-weight: 400;
            line-height: 1;
            border: 0;
            border-radius: 18px;
            cursor: pointer;
         }


         #webshipper-address-search:hover {
            background-color: #0b2770;
            transition-duration: .2s;
         }

         .webshipper-body {
            border-top: 1px solid #999;
         }

         #webshipper-left {
            width: 60%;
            height: 600px;
            display: inline-block;
         }

         #webshipper-right {
            width: 40%;
            height: 90vh;
            display: inline-block;
            float: right;
            overflow: auto;
         }

         #webshipper-map {
            height: 100%
         }

         #webshipper-infowindow {
            min-width: 250px
         }

         #webshipper-infowindow h2 {
            margin: 0;
         }

         #webshipper-infowindow p {
            margin-top: 0;
            margin-bottom: 0
         }

         #webshipper-infowindow table {
            border: 1px solid #333;
            border-collapse: collapse;
            margin-top: 10px;
            width: 100%;
            text-align: center;
         }

         #webshipper-infowindow table tr,
         #webshipper-infowindow table td {
            border: 1px solid #333;
         }

         #webshipper-infowindow table tr td {
            padding: 2px;
         }

         #webshipper-infowindow button {
            border: 0;
            outline: 0;
            margin-top: 5px;
            padding: 5px 10px;
            border-radius: 2px;
            font-size: 1rem;
            width: 100%;

            background: #00779B;
            color: #fff;
            cursor: pointer;
         }

         #webshipper-infowindow button:active {
            background: #0097AB;
         }

         #webshipper-drop-points-list {
            list-style: none;
            width: 100%;
            margin: 0;
            padding: 0;
         }

         #webshipper-drop-points-list li {
            width: 100%
         }

         #webshipper-drop-points-list li label {
            width: 100%;
         }

         @media(max-width: 600px) {
            #webshipper-modal {
               top: 10%;
               transform: translate(-50%, -10%);
               max-height: 80%;
               overflow: auto;
            }

            #webshipper-left {
               display: none;
            }

            #webshipper-right {
               width: 100%;
               height: 100%;
            }

            .webshipper-header h2 {
               display: block;
            }

            #webshipper-address-search {
               margin-top: 15px;
            }
         }

         @media(min-width: 601px) and (max-width: 919px) {
            #webshipper-left {
               height: 90vh;
            }
         }

         @media(min-height: 800px) {

            #webshipper-left,
            #webshipper-right {
               height: 600px;
            }
         }

         .webshipper-drop-point-shop-title {
            font-size: 1.2rem;
            font-weight: bold
         }

         .webshipper-drop-point-shop p {
            margin: 0;
            display: inline-block
         }

         #webshipper-selected-drop-point {
            text-align: center;
         }

         #webshipper-selected-drop-point h3 {
            font-size: 1.3rem;
            font-weight: 300;
            margin-bottom: 0;
            margin-top: 5px;
         }

         #webshipper-selected-drop-point p {
            margin: 0;
            padding: 0;
         }
      </style>
      <style>
         .webshipper-drop-point-shop {
            cursor: pointer !important;
            padding-left: 50px;
            width: 100%;
            padding-bottom: 25px;
         }

         .webshipper-drop-point-radio {
            width: 0px;
            float: left
         }

         .webshipper-radio {
            position: relative;
            cursor: pointer;
            line-height: 20px;
            font-size: 14px;
            margin: 15px;
         }

         .webshipper-radio .webshipper-label {
            position: relative;
            display: block;
            float: left;
            margin-right: 10px;
            width: 24px;
            height: 24px;
            border: 2px solid #c8ccd4;
            border-radius: 100%;
            -webkit-tap-highlight-color: transparent;
         }

         .webshipper-radio .webshipper-label:after {
            content: '';
            position: absolute;
            top: 5px;
            left: 5px;
            width: 14px;
            height: 14px;
            border-radius: 100%;
            background: #225cff;
            transform: scale(0);
            transition: all 0.2s ease;
            opacity: 0.08;
            pointer-events: none;
         }

         .webshipper-radio:hover .webshipper-label:after,
         .webshipper-drop-point-container:hover .webshipper-label:after {
            transform: scale(3.6);
         }

         #webshipper-modal input[type="radio"]:checked+.webshipper-label {
            border-color: #225cff;
         }

         #webshipper-modal input[type="radio"]:checked+.webshipper-label:after {
            transform: scale(1);
            transition: all 0.2s cubic-bezier(0.35, 0.9, 0.4, 0.9);
            opacity: 1;
         }

         .webshipper-hidden {
            display: none !important;
         }
      </style>

      <script>
         document.body.appendChild(document.getElementById('webshipper-overlay'));
         document.body.appendChild(document.getElementById('webshipper-modal'));
      </script>

      <script>
         window.webshipper_lang = <?php echo webshipper_js_lang() ?>;
         window.webshipper_lang = <?php echo webshipper_js_lang() ?>;
         jQuery('body').on('click', '#webshipper-modal-open', function() {
            window.webshipper.modal.open()
         })

         window.webshipper.icon_base = "<?php echo esc_url(plugins_url('img/icons', dirname(__FILE__))) ?>";
         window.webshipper.icons = {
            bring: webshipper.icon_base + '/bring.png',
            postnord: webshipper.icon_base + '/postnord.png',
            dao: webshipper.icon_base + '/dao.png',
            gls: webshipper.icon_base + '/gls.png',
            ups: webshipper.icon_base + '/ups.png'
         }

         window.webshipper.modal = {
            open: function() {
               jQuery('#webshipper-modal').fadeIn('fast')
               jQuery('#webshipper-overlay').fadeIn('fast')
               document.querySelector('html').classList.add('webshipper-no-scroll')
               document.body.classList.add('webshipper-no-scroll')

               if (jQuery("#webshipper_address").val().length < 1) {
                  // Use delivery address if avaliable, else billing address
                  if (Boolean(jQuery("#shipping_address_1").val()) && Boolean(jQuery("#shipping_postcode").val())) {
                     jQuery("#webshipper_address").val(jQuery("#shipping_address_1").val());
                     jQuery("#webshipper_zip").val(jQuery("#shipping_postcode").val());
                     jQuery("#webshipper_city").val(jQuery("#shipping_city").val());
                  } else {
                     jQuery("#webshipper_address").val(jQuery("#billing_address_1").val());
                     jQuery("#webshipper_zip").val(jQuery("#billing_postcode").val());
                     jQuery("#webshipper_city").val(jQuery("#billing_city").val());
                  }
               }

               window.webshipper.modal.search()

               window.webshipper.modal.set_radio()
            },
            // Search for new drop points. While searching, spinner will be visible
            search() {
               document.getElementById('webshipper-loader').classList.remove('webshipper-hidden')
               document.getElementById('webshipper-drop-points').classList.add('webshipper-hidden')

               window.webshipper.search(jQuery("#webshipper_zip").val(), jQuery("#webshipper_address").val(), jQuery("#webshipper_city").val(), function() {
                  window.webshipper.prepare_list()

                  if (typeof google !== 'undefined') {
                     webshipper.prepare_map()
                  }

                  window.webshipper.modal.set_radio()
                  document.getElementById('webshipper-loader').classList.add('webshipper-hidden')
                  document.getElementById('webshipper-drop-points').classList.remove('webshipper-hidden')
               })
            },
            set_radio() {
               if (window.webshipper.drop_point && window.webshipper.drop_point.drop_point_id) {
                  var input = document.querySelector(`input[data-id="${window.webshipper.drop_point.drop_point_id}"]`)

                  if (input) {
                     jQuery(input).prop('checked', true)
                  }
               }
            },
            close() {
               jQuery('#webshipper-modal').fadeOut('fast')
               jQuery('#webshipper-overlay').fadeOut('fast')
               document.querySelector('html').classList.remove('webshipper-no-scroll')
               document.body.classList.remove('webshipper-no-scroll')
            }
         }
         window.webshipper.prepare_list = function prepare_list() {
               var elem = document.getElementById('webshipper-drop-points-list')
               elem.innerHTML = ''

               for (var z = 0; z < window.webshipper.drop_points.length; z++) {
                  var shop = window.webshipper.drop_points[z]

                  var e = document.createElement('li')
                  e.classList.add('webshipper-drop-point-selector')

                  e.innerHTML = `
                  <div class="webshipper-drop-point-container">
                     <label for="webshipper-drop-point-${z}">
                        <div class="webshipper-drop-point-radio">
                           <div class="webshipper-radio">
                              <input type="radio" id="webshipper-drop-point-${z}" name="webshipper-drop-point" class="webshipper-hidden" data-id="${shop.drop_point_id}">
                              <span class="webshipper-label"></span>
                           </div>
                        </div>

                        <div class="webshipper-drop-point-shop">
                           <p class="webshipper-drop-point-shop-title">${shop.name}</p>
                           <br>
                           <p>${shop.address_1}</p>
                           <br>
                           <p>${shop.zip} ${shop.city}</p>
                        </div>
                     </label>
                  </div>`

                  elem.append(e)
               }

               document.querySelectorAll('#webshipper-drop-points-list input[type="radio"]').forEach(x => {
                  jQuery(x).unbind('change')

                  jQuery(x).bind('change', function(y) {
                     window.webshipper.select_drop_point(y.target.getAttribute('data-id').toString())
                  })
               })
            },
            window.webshipper.prepare_map = function prepare_map() {
               if (window.webshipper.drop_points.length < 1) return

               var place = new google.maps.LatLng(window.webshipper.drop_points[0].latitude, window.webshipper.drop_points[0].longitude);

               // Initiate Map
               var settings = {
                  mapTypeId: google.maps.MapTypeId.ROADMAP,
                  zoom: 13,
                  streetViewControl: false,
                  fullscreenControl: false,
                  rotateControl: false,
                  mapTypeControl: false,
                  center: place
               };

               // Make variables globally available
               window.ws_infowindow = new google.maps.InfoWindow();
               window.ws_map = new google.maps.Map(document.getElementById("webshipper-map"), settings);
               window.ws_map.setZoom(window.ws_map.getZoom());

               function place_marker(shop) {
                  var icon = false;

                  switch (shop.carrier_code) {
                     case "POSTNORD":
                        icon = window.webshipper.icons.postnord
                        break;
                     case "UPS":
                        icon = window.webshipper.icons.ups
                        break;
                     case "BRING":
                        icon = window.webshipper.icons.bring
                        break;
                     case "GLS":
                        icon = window.webshipper.icons.gls
                        break;
                     case "DAO":
                        icon = window.webshipper.icons.dao
                        break;
                  }

                  var position = new google.maps.LatLng(shop.latitude, shop.longitude);

                  var m = {
                     position: position,
                     map: window.ws_map,
                     title: shop.name,
                     visible: true,
                  }

                  if (icon) {
                     m.icon = {
                        url: icon,
                        scaledSize: new google.maps.Size(35, 35), // scaled size
                        origin: new google.maps.Point(0, 0), // origin
                        anchor: new google.maps.Point(0, 0) // anchor
                     }
                  }

                  var marker = new google.maps.Marker(m)

                  // Prepare the content for the InfoWindow
                  content = `<div id="webshipper-infowindow">
               <h2>${shop.name}</h2>
               <p>${shop.address_1}</p>
               <p>${shop.zip} ${shop.city}</p>
               <button type="button" onClick="window.webshipper.select_drop_point('${shop.drop_point_id}')">${webshipper.translate('select')}</button>`

                  // Format opening hours
                  if (shop.opening_hours && shop.opening_hours.length > 0) {
                     content += `<table><tbody>`
                     for (var x = 0; x < shop.opening_hours.length; x++) {
                        var day = shop.opening_hours[x]

                        content += `<tr>
                        <td>${webshipper.translate("day_"+day.day)}</td>
                        <td>${day.opens_at} - ${day.closes_at}</td>
                     </tr>`
                     }
                     content += `</tbody></table>`
                  }

                  content += `</div>`

                  // Required to be marker.content
                  // for some Godforsaken reason
                  marker.content = content

                  // Replace the active infoWindow with a new one
                  // when a marker is clicked
                  google.maps.event.addListener(marker, 'click', function() {
                     window.ws_infowindow.close()
                     window.ws_infowindow.setContent(marker.content)
                     window.ws_infowindow.open(window.ws_map, marker)
                  })
               }

               // Place all the markers
               for (var i = 0; i < window.webshipper.drop_points.length; i++) {
                  var shop = window.webshipper.drop_points[i]

                  place_marker(shop)
               }
            }

         function gm_authFailure() {
            document.getElementById('webshipper-map').classList.add('webshipper-hidden')
         };
      </script>
   </td>
</tr>