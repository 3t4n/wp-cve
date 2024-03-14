/* Pins javascript */
var bmp_custom_pins_rendered = false;
var bmp_bing_map_pins, bmp_new_pin;
var bmp_custom_pins_array = [ "2hand.png","360degrees.png","abduction.png","aboriginal.png","accesdenied.png","acupuncture.png","administration.png","administrativeboundary.png","aed-2.png","agritourism.png",
                              "air_fixwing.png","aircraftcarrier.png","aircraftsmall.png","airport.png","airport_apron.png","airport_runway.png","airport_terminal.png","airshow-2.png",
                              "algae.png","alien.png","alligator.png","amphitheater-2.png","amphitheater.png","anchorpier.png","anemometer_mono.png","animal-shelter-export.png","anniversary.png",
                              "ant-export.png","anthropo.png","apartment-3.png","apple.png","aquarium.png","arch.png","archery.png","army.png","art-museum-2.png","artgallery.png","atm-2.png","atv.png",
                              "audio.png","australianfootball.png","avalanche1.png","award.png","badminton-2.png","bags.png","bank.png","bar.png","bar_coktail.png","bar_juice.png",
                              "barbecue.png","barber.png","barrier.png","baseball.png","basketball.png","bats.png","battlefield.png","battleship-3.png","beach.png","beachvolleyball.png",
                              "beautifulview.png","beautysalon.png","bed_breakfast1-2.png","beergarden.png","bicycle_shop.png","bigcity.png","bike_downhill.png","bike_rising.png",
                              "billiard-2.png","binoculars.png","birds-2.png","blast.png","boardercross.png","boat.png","boatcrane.png","bobsleigh.png","bollie.png","bomb.png","bomber-2.png",
                              "bouddha.png","bowling.png","boxing.png","bread.png","brewery1.png","bridge_modern.png","bridge_old.png","bulldozer.png","bullfight.png","bunker-2-2.png","bus.png",
                              "busstop.png","bustour.png","butcher-2.png","butterfly-2.png","cabin-2.png","cablecar.png","cafetaria.png","calendar-3.png","campfire-2.png","camping-2.png",
                              "candy.png","canyon-2.png","car.png","caraccident.png","carrental.png","carwash.png","casino-2.png","castle-2.png","cathedral.png","catholicgrave.png",
                              "caution.png","cave-2.png","cctv.png","cemetary.png","channelchange.png","chapel-2.png","chart-2.png","cheese.png","chemistry-2.png","chicken-2.png",
                              "childmuseum01.png","chiropractor.png","christmasmarket.png","church-2.png","cinema.png","circus.png","citysquare.png","citywalls.png","climbing.png",
                              "clock.png","closedroad.png","clothers_female.png","clothers_male.png","cloudy.png","cloudysunny.png","coffee.png","coins.png","comedyclub.png",
                              "comics.png","comment-map-icon.png","communitycentre.png","company.png","compost.png","computers.png","condominium.png","conference.png","congress.png",
                              "construction.png","constructioncrane.png","contract.png","conveniencestore.png","convent-2.png","conversation-map-icon.png","convertible.png","corral.png",
                              "country.png","court.png","cow-export.png","cowabduction.png","craftstore.png","cramschool.png","cricket.png","crimescene.png","cromlech.png","cropcircles.png",
                              "cross-2.png","crossingguard.png","cruiseship.png","cup.png","curling-2.png","currencyexchange.png","customs.png","cycling.png","cycling_feed.png","cycling_sprint.png",
                              "dam.png","dance_class.png","dancinghall.png","database.png","daycare.png","deepseafishing.png","deer.png","dentist.png","departmentstore.png","desert-2.png",
                              "dinopark.png","direction_down.png","disability.png","diving.png","dogs_leash.png","dolphins.png","doublebendright.png","downloadicon.png","drinkingfountain.png",
                              "drinkingwater.png","drugstore.png","duck-export.png","earthquake-3.png","eggs.png","elephants.png","elevator.png","elevator_down.png","elevator_up.png",
                              "embassy.png","entrance.png","exchequer.png","exit.png","expert.png","factory.png","fallingrocks.png","family.png","farm-2.png","farmstand.png","fastfood.png",
                              "female-2.png","ferriswheel.png","ferry.png","festival.png","fetalalcoholsyndrom.png","field.png","fillingstation.png","findajob.png","finish.png",
                              "fire-hydrant-2.png","fire.png","fireexstinguisher.png","firemen.png","fireworks.png","firstaid.png","fishchips.png","fishing.png","fishingboat.png",
                              "fishingstore.png","fitness.png","fjord-2.png","flag-export.png","flood.png","flowers.png","folder-2.png","fooddeliveryservice.png","foodtruck.png","footprint.png",
                              "ford-2.png","forest.png","forest2.png","fossils.png","foundry-2.png","fountain-2.png","fourbyfour.png","freqchg.png","frog-2.png","fruits.png","ft.png",
                              "funicolar-22x22.png","gas_cylinder1.png","gay-female.png","gay-male.png","geocaching-3.png","geothermal-site.png","geyser-2.png","ghosttown.png","gifts.png",
                              "glacier-2.png","glasses.png","golfing.png","gondola-2.png","gourmet_0star.png","grass.png","grocery.png","group-2.png","gumball_machine.png","handball.png",
                              "hanggliding.png","harbor.png","hare1.png","hats.png","haybale.png","headstone-2.png","helicopter.png","highhills.png","highschool.png",                              
                              "highway.png","hiking.png","historical_museum.png","historicalquarter.png","hoergeraeteakustiker_22px.png","home-2.png","homecenter.png","honeycomb.png",
                              "hookah_final.png","horseriding.png","hospital-building.png","hostel_0star.png","hotairbaloon.png","hotel_0star.png","hotspring.png","house.png","hunting.png",
                              "hut.png","icecream.png","icehockey.png","iceskating.png","icy_road.png","indoor-arena.png","information.png","iobridge.png","jacuzzi.png","japanese-food.png",
                              "japanese-lantern.png","japanese-sake.png","japanese-sweet-2.png","japanese-temple.png","jazzclub.png","jeep.png","jetfighter.png","jewelry.png","jewishgrave.png",
                              "jewishquarter.png","jogging.png","judo.png","junction.png","karate.png","karting.png","kayak1.png","kayaking.png","kebab.png","kingair.png","kiosk.png","kitesurfing.png",
                              "laboratory.png","lake.png","landfill.png","landmark.png","laterne.png","laundromat.png","levelcrossing.png","library.png","lifeguard-2.png","lighthouse-2.png",
                              "linedown.png","lingerie.png","liquor.png","lobster-export.png","lock.png","lockerrental.png","lodging_0star.png","love_date.png","loveinterest.png",
                              "magicshow.png","mainroad.png","male-2.png","mall.png","map.png","mapicon.png","marina-2.png","market.png","massage.png","mastcrane1.png","medicalstore.png",
                              "medicine.png","megalith.png","memorial.png","metronetwork.png","military.png","mine.png","missile-2.png","mobilephonetower.png","modernmonument.png",
                              "moderntower.png","monkey-export.png","monument-historique-icon-white-22x22.png","monument.png","moonstar.png","mosquee.png","mosquito-2.png","motel-2.png",
                              "motorbike.png","motorcycle.png","mountain-pass-locator-diagonal-reverse-export.png","mountainbiking-3.png","mountains.png","movierental.png","moving-walkway-enter-export.png",
                              "muffin_bagle.png","mural.png","museum_archeological.png","museum_art.png","museum_crafts.png","museum_industry.png","museum_naval.png","museum_openair.png",
                              "museum_science.png","museum_war.png","mushroom.png","music.png","music_choral.png","music_classical.png","music_hiphop.png","music_live.png","music_rock.png",
                              "nanny.png","ne_barn-2.png","newsagent.png","no-nuke-export.png","nordicski.png","notvisited.png","nursery.png","nursing_home_icon.png","observatory.png","office-building.png",
                              "oil-2.png","oilpumpjack.png","oilrig2.png","olympicsite.png","ophthalmologist.png","outlet2.png","oyster-3.png","pagoda-2.png","paint.png","paintball.png","palace-2.png",
                              "palm-tree-export.png","panoramicview.png","paragliding.png","parasailing.png","parkandride.png","parking-meter-export.png","parkinggarage.png","party-2.png","patisserie.png",
                              "peace.png","pedestriancrossing.png","penguin-2.png","pens.png","perfumery.png","petanque.png","petroglyphs-2.png","pets.png","phantom.png","phones.png","photo.png",
                              "photography.png","picnic-2.png","pig.png","pin-export.png","pirates.png","pizzaria.png","planecrash.png","planetarium-2.png","playground.png","pleasurepier.png",
                              "poker.png","police.png","postal.png","powerlinepole.png","poweroutage.png","powerplant.png","powersubstation.png","prayer.png","presentation.png","price-tag-export.png",
                              "printer-2.png","prison.png","publicart.png","pyramid.png","quadrifoglio.png","radar.png","radiation.png","radio-control-model-car.png","radio-station-2.png",
                              "rainy.png","rape.png","reatorlogowhite-22x22.png","recycle.png","regroup.png","repair.png","rescue-2.png","resort.png","restaurant.png","restaurant_african.png",
                              "restaurant_breakfast.png","restaurant_buffet.png","restaurant_chinese.png","restaurant_fish.png","restaurant_greek.png","restaurant_indian.png","restaurant_italian.png","restaurant_korean.png",
                              "restaurant_mediterranean.png","restaurant_mexican.png","restaurant_romantic.png","restaurant_steakhouse.png","restaurant_tapas.png","restaurant_thai.png",
                              "restaurant_turkish.png","restaurant_vegetarian.png","revolt.png","riparianhabitat.png","river-2.png","road.png","roadtype_gravel.png","rockhouse.png",
                              "rodent.png","rollerskate.png","ropescourse.png","rowboat.png","rugbyfield.png","ruins-2.png","sailing.png","sandwich-2.png","sauna.png","sawmill-2.png",
                              "school.png","schreibwaren_web.png","scoutgroup.png","scubadiving.png","seals.png","segway.png","seniorsite.png","septic_tank.png","share.png","shark-export.png",
                              "shintoshrine.png","shipwreck.png","shoes.png","shooting.png","shootingrange.png","shore-2.png","shower.png","sight-2.png","signpost-2.png","sikh.png",
                              "ski_shoe1.png","skiing.png","skijump.png","skilifting.png","skis.png","skull.png","sledge.png","sledge_summer.png","sledgerental.png","slipway.png",
                              "smallcity.png","smiley_happy.png","smoking.png","snail.png","snakes.png","sneakers.png","snorkeling.png","snowboarding.png","snowmobiling.png","snowpark_arc.png",
                              "snowshoeing.png","snowy-2.png","soccer.png","solarenergy.png","sozialeeinrichtung.png","spa.png","spaceport-2.png","speed_50.png","speedhump.png","speedriding.png",
                              "spelunking.png","spider.png","splice.png","sportscar.png","sportutilityvehicle.png","square-compass.png","squash-2.png","stadium.png","stairs.png",
                              "star-3.png","stargate-raw.png","start-race-2.png","statue-2.png","steamtrain.png","stop.png","strike.png","stripclub2.png","submarine-2.png","sugar-shack.png",
                              "summercamp.png","sumo-2.png","sunny.png","sunsetland.png","supermarket.png","surfacelift.png","surfing.png","surfpaddle.png","surveying-2.png","swimming.png","synagogue-2.png",
                              "taekwondo-2.png","tailor.png","takeaway.png","targ.png","taxi.png","taxiboat.png","taxiway.png","teahouse.png","tebletennis.png","telephone.png","temple-2.png","templehindu.png",
                              "tennis.png","terrace.png","text.png","textiles.png","theater.png","theft.png","themepark.png","therapy.png","theravadapagoda.png","theravadatemple.png",
                              "thunderstorm.png","ticket_office2.png","tidaldiamond.png","tiger-2.png","tires.png","toilets.png","tollstation.png","tools.png","tornado-2.png","torture.png",
                              "tower.png","townhouse.png","toys.png","trafficcamera.png","trafficlight.png","train.png","tramway.png","trash.png","travel_agency.png","treasure-mark.png",
                              "treedown.png","triskelion.png","trolley.png","truck3.png","tsunami.png","tunnel.png","turtle-2.png","tweet.png","u-pick_stand.png","ufo.png","umbrella-2.png","underground.png",
                              "university.png","usfootball.png","van.png","vespa.png","veterinary.png","video.png","videogames.png","villa.png","vineyard-2.png","volcano-2.png",
                              "volleyball.png","waiting.png","walkingtour.png","war.png","warehouse-2.png","water.png","watercraft.png","waterfall-2.png","watermill-2.png","waterpark.png",
                              "waterskiing.png","watertower.png","waterwell.png","waterwellpump.png","webcam.png","wedding.png","weights.png","wetlands.png","whale-2.png","wifi.png","wiki-export.png","wildlifecrossing.png",
                              "wind-2.png","windmill-2.png","windsurfing.png","windturbine.png","winebar.png","winetasting.png","woodshed.png","workoffice.png","workshop.png",
                              "world.png","worldwildway.png","wrestling-2.png","yoga.png","yooner.png","you-are-here-2.png","youthhostel.png","zombie-outbreak1.png","zoo.png","zoom.png"
                            ];
                           
var bmpX = jQuery.noConflict();
var bmp_custom_icon_is_valid = false;
var bmp_pin_info = {
    type        : 'simple',
    title       : '',
    desc        : '',
    html        : '',
    radio       : 'none'
}
var bmp_wp_media_modal;
var bmp_dragThePin = false;

bmpX(function(bmpX){

    var bmp_selected_pin_infobox = bmpX('input[name=radio_bmp_pin_use]:checked').attr('data-value');
    bmp_pin_info.radio = bmp_selected_pin_infobox;   
    

    bmpX('#bmp_new_pin_icon').on('change', function(){                 
        var pinVal =  bmpX('#bmp_new_pin_icon option:selected').val();       
        bmpX('#pin_icon_img img').attr('src', bmpIconsDefaultUrl + 'pin-' + pinVal + '.png');
      
       var bmp_pin_obj =  bmp_get_pin_data();
       bmp_show_pin_on_map( bmp_pin_obj );
    });

    bmpX('input[name=radio_bmp_pin_use]').on('change', function(){
        bmp_pin_info.radio = bmpX('input[name=radio_bmp_pin_use]:checked').attr('data-value');

        if( typeof(infobox) !== 'undefined' )
            infobox.setOptions({ visible: false });
        bmpX('#bmp_view_new_pin').trigger('click');
    });

    //===============================================
    /* Pins page */

    bmpX('#bmp_new_pin img').on('click', function(){
        bmpX('#bmp_save_and_new_pin').show();        
        bmpX('#bmp_modal_new_edit_pin').modal({
            show: true,
            backdrop: 'static'
        });
        bmpX('.bmp-modal-new-edit-pin input[type="text"]').removeClass('required_input');
    });

    bmpX('#bmp_dragdrop_pin').on('change', function(){
        bmp_dragThePin = bmpX( this ).prop('checked');
        bmp_pinDraggableHandler( bmp_new_pin );
    });

    bmpX('#bmp_custom_pin_img').hide();
    bmpX('#bmp_new_pin_name').on('keyup', function(){
        if( bmpX(this).val().trim().length > 0 ){
            bmpX(this).removeClass('required_input');
        }else{
            bmpX(this).addClass('required_input'); 
        }
    });
    bmpX('#bmp_new_pin_lat').on('keyup', function(){
        if( bmpX(this).val().trim().length > 0 ){
            bmpX(this).removeClass('required_input');
        }else{
            bmpX(this).addClass('required_input'); 
        }
    });
    bmpX('#bmp_new_pin_long').on('keyup', function(){
        if( bmpX(this).val().trim().length > 0 ){
            bmpX(this).removeClass('required_input');
        }else{
            bmpX(this).addClass('required_input'); 
        }
    });

    bmpX('#bmp_btn_del_pin').on('click', function(){
        var $pin_id =  bmpX('#bmp_pin_hidden_pin_id').val();
        var data = {
            action : 'delete-pin',
            pin_id : $pin_id 
        };

        bmp_pin_action( data );
    });



    bmpX('#bmp_view_new_pin').on('click', function(){ 
        var bmp_pin_obj =  bmp_get_pin_data();
        bmp_show_pin_on_map( bmp_pin_obj );
    }); 


    bmpX('#bmp_cancel_edit_pin').on('click', function(){      
        bmp_clear_pin_inputs('new');
        bmpX('#pin_action_id').val('');
        bmpX('#pin_action').val('new-pin');
        bmpClearPinsFromMap();
        bmpX('#bmp_modal_new_edit_pin').modal('hide');
    });
    
    RefreshMapPin();

    bmpX('[data-toggle="tooltip"]').tooltip({
        container : 'body'
    });  

    bmpX('#bmp_wp_library_icon').on('click', function(e){        
        e.preventDefault();
       // bmp_wp_media_modal

        var frame;
 
        frame = wp.media({
            multiple: false
        });

        frame.on( 'select', function(){
            var attachment = frame.state().get('selection').first().toJSON();
         
            var bmp_img_url = attachment.url.split('/');
            var img_alt_text = attachment.alt;
            var img_title = attachment.description;
            var img_library = bmp_img_url[ bmp_img_url.length - 1 ];

            bmpX('#bmp_custom_pin_img').attr('src', attachment.url ).css('display', 'block');
            bmpX('#bmp_pin_custom_url').val( attachment.url );
            bmpX('#bmp_in_desc_img').val( img_library ).attr('data-url', attachment.url ); 
            bmpX('#bmp_view_new_pin').click();
        });

       frame.open();
    });
 
    bmpX( '#btn_bmp_pin_info_simple').on('click', function(){
        bmp_pin_info.type = 'simple';
        bmp_create_visual_pin_info();  

        bmpX('.bmp-modal-infobox-simple').modal({            
            show: true,
            backdrop: 'static'
        });  
    });

    bmpX( '#btn_bmp_pin_info_advanced').on('click', function(){
        bmp_pin_info.type = 'advanced';
        try {
            if( typeof tinyMCE.editors.bmp_infobox_editor_wp !== 'undefined' ){
                bmp_create_visual_pin_info();  
    
                bmpX('.bmp-modal-infobox-advanced').modal({
                    show: true,
                    backdrop: 'static'
                });  
            }else{ //show info modal error
                
                bmpX('.bmp-modal-infobox-error').modal({
                    show: true                
                });
            }  
        } catch (error) {
            bmpX('.bmp-modal-infobox-error').modal({
                show: true                
            });
        }       

    });

    bmpX('#bmp_btn_infobox_advanced_update').on('click', function(){       
        bmpX('#bmp_infobox_editor_wp-tmce').trigger('click');
        bmp_pin_info.html = tinyMCE.editors.bmp_infobox_editor_wp.getContent();  //{ format : 'html' }
        bmpX('.bmp-modal-infobox-advanced').modal('hide');    
        bmpX('#bmp_view_new_pin').trigger('click');
    });

    bmpX('#bmp_custom_icon_select').on('click', function(){
        var bmp_selected_icon = bmpX('#bmp_custom_pin_content .bordered-img');
        if( bmp_selected_icon.length > 0 ){
            var bmp_icon_title = bmpX( bmp_selected_icon ).attr('title');
            var bmp_icon_src   = bmpX( bmp_selected_icon ).attr('src');
            bmpX('#bmp_pin_custom_url').val( bmp_icon_title );
            bmpX('#bmp_pin_custom_url').trigger( 'change' );
            bmpX('.bmp-modal-custom-pin').modal('hide');
        }else{
            console.error( 'not bigger than 0 ');
        }
       
    });

    bmpX('#bmp_btn_infobox_simple_save').on('click', function(){
        bmp_pin_info.title = bmpX('#bmp_infobox_title').val();
        bmp_pin_info.desc  = bmpX('#bmp_infobox_description').val();
        bmp_pin_info.desc  = bmp_pin_info.desc.replace(/\n/g, 'bmp_nl');
        
        bmpX('.bmp-modal-infobox-simple').modal('hide');
        bmpX('#bmp_view_new_pin').trigger('click');
    });

    bmpX('#bmp_libary_pin_img').on('click', function(){
        
        if( ! bmp_custom_pins_rendered ){          
            bmp_create_custom_pin_content( bmp_show_custom_pin_modal );                             
        }else{
            bmp_show_custom_pin_modal();
            bmp_filter_custom_pin_imgs('');
            bmpX( '#bmp_custom_pin_content img').removeClass( 'bordered-img');
        }
        
    });
    bmpX('#bmp_custom_pin_search').on('keyup', function(){
        var bmp_search_text = bmpX( this ).val();
        bmp_filter_custom_pin_imgs(bmp_search_text );
    });

    bmpX('#bmp_pin_custom_url').on('change input', function(){
        //check if the image is valid, 
        var bmp_value = bmpX( this ).val().trim();
        if( bmp_value.length > 0 ){
            var bmp_value_array = bmp_value.trim().split('.');           
            if ( (bmp_value.indexOf('http') !== -1 )  && (bmp_value_array[bmp_value_array.length - 1 ].length >= 3) ){ // from http
                bmpX('#bmp_custom_pin_img').show(); 
                bmpX('#bmp_custom_pin_img').attr('src', bmp_value ); 
                bmp_custom_icon_is_valid = true;    
            }else if ( bmp_value_array[bmp_value_array.length - 1 ] == 'png')  { // from library 
                bmpX('#bmp_custom_pin_img').show(); 
                bmpX('#bmp_custom_pin_img').attr('src', bmpIconsCustomUrl + bmp_value ); 
                bmp_custom_icon_is_valid = true;                 
            }else{
                bmpX('#bmp_custom_pin_img').hide();
                bmp_custom_icon_is_valid = false;
            }
        }else{
            bmp_custom_icon_is_valid = false;
            bmpX('#bmp_custom_pin_img').hide();
        }

        if( bmp_custom_icon_is_valid &&  bmpX('#bmp_new_pin_lat').val().trim() !== '' && bmpX('#bmp_new_pin_long').val().trim() !== '' ){ 
          bmpX('#bmp_view_new_pin').click();
        }
    });

    bmpX('#bmp_btn_pin_info_update').on('click', function(){
        let bmp_pin_title = bmpX('#bmp_new_pin_title').val().trim();
        let bmp_pin_desc  = bmpX('#bmp_new_pin_description').val().trim();
        if( bmp_pin_title.length === 0 ){
            bmpX('#bmp_new_pin_title').addClass('required_input');
            return;
        }else if( bmp_pin_desc.length === 0 ){
            bmpX('#bmp_new_pin_description').addClass('required_input');
            return; 
        }else{
            update_bmp_pin_info();
            bmpX('.bmp-modal-edit-pin-info').modal('hide');
            bmp_create_visual_pin_info();
        }

        bmpX('#bmp_view_new_pin').trigger('click');
        
    });
    
    bmpX('#bmp_pin_info_remove').on('click', function(){
       bmpX('#bmp_in_desc_img').val('').attr('data-url', '');
       bmpX('#bmp_in_desc_img_title').val('');
       bmpX('#bmp_in_desc_img_alt').val('');
    });

    bmpX('#bmp_new_pin_title').on('input', function(){
        if( bmpX( this ).val().trim().length === 0 ){
            bmpX( this ).addClass('required_input');
        }else{
            bmpX( this ).removeClass('required_input');
        }
    });

    bmpX('#bmp_new_pin_description').on('input', function(){
        if( bmpX( this ).val().trim().length === 0 ){
            bmpX( this ).addClass('required_input');
        }else{
            bmpX( this ).removeClass('required_input');
        }
    });
    
    bmpX( "#ul_pins_assigned, #ul_all_maps" ).sortable({
        connectWith: ".connectedSortable"
      }).disableSelection();

    bmpX( "#ul_pins_assigned" ).sortable({
        receive: function( event, ui ) {         
           let map_id = bmpX( ui.item[0] ).data('mapid');          
           let pin_id = bmpX('#bmp_assign_pin_id').val();
           //add to pins_obj_arr            
           
           var $data = {
                pin_id : parseInt( pin_id),
                map_id : parseInt( map_id ),
                action : 'add_pin_to_map'
            }

            bmp_assign_pin_action( $data, bmp_action_pin_map );
        }
      });

    bmpX( "#ul_all_maps" ).sortable({
        receive: function( event, ui ) {
            let map_id = bmpX( ui.item[0] ).data('mapid');
            let pin_id = bmpX('#bmp_assign_pin_id').val();
            //remove from pins_obj_arr

            var $data = {
                pin_id : parseInt( pin_id),
                map_id : parseInt( map_id ),
                action : 'remove_pin_from_map'
            }

            bmp_assign_pin_action( $data, bmp_action_pin_map );
        }
      });

    bmpX('.bmp-modal-assign-pin').on('hidden.bs.modal', function(){
        //update used on maps count
        let pin_id = bmpX('#bmp_assign_pin_id').val();
        let pin_count = bmpDataPinMaps[pin_id].length || 0;
        bmpX('#pin_' + pin_id + ' #pin_used_on_maps').text( pin_count );
    });
});

function bmp_action_pin_map( $pin_id, $map_id, $action ){
    $map_id = $map_id+'';
    $pin_id = $pin_id+'';

    if( $action == 'add_pin_to_map'){
        bmpDataPinMaps[ $pin_id ].push( $map_id ); 
        
    }else if( $action == 'remove_pin_from_map'){
        let index_map = bmpDataPinMaps[ $pin_id ].indexOf( $map_id );
        if( index_map > - 1){
            bmpDataPinMaps[ $pin_id ].splice( index_map, 1 );
        }
    }
    
}

function bmp_show_custom_pin_modal(){
    bmpX('.bmp-modal-custom-pin').modal({
        show: true,
        backdrop: 'static'            
    });
    bmpX('#bmp_selected_custom_pin').text('');
    bmpX('#bmp_custom_pin_search').val('');
    bmpX('#bmp_custom_pin_search').focus();      
}

function bmp_assign_pin_action( $data, $callback ){
    $data.nonce_bing_map_pro = bmpX('#nonce_bing_map_pro').val();
    var data_ajax = {
        action  : 'bmp_pin_actions',
        type    : 'POST',
        data    :  $data,
        dataType : 'json',
        contentType : 'application/json'  
    };
  
    bmpX.ajax({
        type    : 'POST',
        url     : ajaxurl,
        data    : data_ajax,
        beforeSend : function(){
            bmpX('.loaderImg').show();
        },
        success : function( data ){
            data = JSON.parse( data );
            if( data.result == 1){               
                $callback( $data.pin_id, $data.map_id, $data.action );
            }

        }, 
        error : function( request, status, error ){
            bmpX('#ajaxError').show();  
            console.error( 'Request ' + request + ' - Status: ' + status + ' - Error: ' + error +' Action: ' + $data.action );   
        },
        complete : function( response){
            bmpX('.loaderImg').hide();  
        }
    });
}

function BuildDOMAssignPinToMap( $pin_id ){
    bmpX('#ul_pins_assigned, #ul_all_maps').empty();
    //bmpDataPinMaps
    if( typeof bmpDataPinMaps[ $pin_id.toString() ] === 'undefined'){
        bmpDataPinMaps[ $pin_id.toString() ] = [];
    }
    var array_maps = bmpDataPinMaps[ $pin_id ];
    bmpDataMaps.forEach( function( map, index ){       
        let el_ul = bmpX('<li></li>').addClass('ui-state-default').text( map.map_title ).attr('data-mapid', map.id );
        if( array_maps.indexOf( map.id ) > -1 ){ //this is for the left - pin to map           
            bmpX('#ul_pins_assigned').append( el_ul );
        }else{ // this is for the right - all maps
            bmpX('#ul_all_maps').append( el_ul );
        }
    });
}

function bmp_create_visual_pin_info(){
    
    if( bmp_pin_info.type === 'simple'){
        let infobox_desc = bmp_pin_info.desc.replace(/bmp_nl/g, '\n');
        bmpX('#bmp_infobox_title').val( bmp_pin_info.title );
        bmpX('#bmp_infobox_description').val( infobox_desc );   
    }else if( bmp_pin_info.type === 'advanced' ){   
        try {
            tinyMCE.setActive( tinyMCE.editors.bmp_infobox_editor_wp );
            if( bmp_pin_info.html.trim().length !== 0 )    
                tinyMCE.editors.bmp_infobox_editor_wp.setContent( bmp_pin_info.html.replace(/\\/g, '' )); 
            else
                tinyMCE.editors.bmp_infobox_editor_wp.setContent( '' );     
        } catch (error) {
            bmpX('.bmp-modal-infobox-error').modal({
                show: true                
            });
        }
 
    }   

    
}

function bmp_filter_custom_pin_imgs( bmp_search_text ){   
    bmpX( '#bmp_custom_pin_content img').each( function( index, item ){
        if( bmpX(item).attr('title').indexOf( bmp_search_text ) !== -1 ){
            bmpX(item).show();
        }else{
            bmpX(item).hide();
        }
    });
}

function bmp_update_bmp_pin_info( attr, value ){
    if( bmp_pin_info.hasOwnProperty(attr) ){
        bmp_pin_info[attr] = value;
    }
}


function bmp_modal_ok_edit_pin(el ){
    var pin_id =  bmpX(el).attr('data-id');
    bmpX('#pin_action_id').val(pin_id);
    bmpX('#pin_action').val('edit-pin');
    bmp_show_edited_pin( pin_id  );
    bmpX('#bmp_view_new_pin').trigger('click'); 
}

function BmpAssignPin( el ){
    let pin_id = el.getAttribute( 'data-id');

    bmpX('#bmp_assign_pin_id').val( pin_id );
    BuildDOMAssignPinToMap( pin_id );
    bmpX('#bmp_modal_assign_pin').modal({
        show : true,
        backdrop: 'static'
    });

    bmpX('#assign_pin_name').text( bmpX('#pin_' + pin_id +' td:first').text() );

}

function bmp_create_custom_pin_content( fn_show_modal ){
    bmpX('.loaderImg').show();
    bmp_custom_pins_rendered = true;
    var array_imgs = [];
    bmp_custom_pins_array.forEach( function(item) {
        var img = "<img src='"+ bmpIconsCustomUrl + item +"' title='"+ item +"'  />";
        array_imgs.push( img );
    });    
    var bmp_appended = array_imgs.join(' ');
    bmpX('#bmp_custom_pin_content').append( bmp_appended );

    bmpX('#bmp_custom_pin_content img').on('click', function(){       
        bmpX( '#bmp_custom_pin_content img').removeClass( 'bordered-img');
        bmpX( this ).addClass('bordered-img');
        var title_arr =  bmpX(this).attr('title').split('.');
        if( title_arr.length > 0 )
            bmpX('#bmp_selected_custom_pin').text( title_arr[0] );
    });
    setTimeout(function(){
        fn_show_modal();
        bmpX('.loaderImg').hide();
    }, 1000 );

}


function bmp_clear_pin_inputs( action ){
    bmpX('#bmp_new_pin_name').val('');
    bmpX('#bmp_new_pin_address').val('');
    bmpX('#bmp_new_pin_lat').val('');
    bmpX('#bmp_new_pin_long').val('');
    bmpX('#bmp_pin_custom_url').val('');
    bmpX('#bmp_new_pin_icon option').prop('selected', false );
    //bmpX('#bmp_libary_pin_img').hide();
    bmpX('#bmp_dis_pin_title p').html('');
    bmpX('#bmp_dis_pin_desc_img, #bmp_dis_pin_desc_text, #bmp_dis_pin_footer').empty();
    bmpX('#bmp_custom_pin_img').attr('src', '').hide();
    
    bmp_custom_icon_is_valid = false;
    bmp_pin_info = {
        type        : 'simple',
        title       : '',
        desc        : '',
        html        : '',
        radio       : 'none'
    }
    bmpX('#radio_bmp_pin_none').prop('checked', true );
    

    if( action === 'new' ){
        bmpX('#bmp_new_pin_icon option:first').prop('selected', true );
        bmpX('#pin_icon_img img').attr('src', bmpIconsDefaultUrl + 'pin-0.png');
    }


}

function BmpCreatePinRow( bmp_pin_obj, obj_to_prepend, action='prepend' ){
    var $pin_id, $pin_name, $pin_address, $pin_lat, $pin_long, $pin_icon, $pin_desc, pin_text_type;
   
    if( bmp_pin_obj !== null ){  
        
        if( bmpAllPins.length == 0 ){
            bmpX(obj_to_prepend).empty();
        }  
        
        $pin_id         = bmp_pin_obj.id;       
        $pin_name       = bmp_pin_obj.pin_name;

        $pin_name       = $pin_name.replace(/\\/g, '' );
        $pin_address    = bmp_pin_obj.pin_address.replace(/\\/g, '');

        $pin_lat        = bmp_pin_obj.pin_lat;
        $pin_long       = bmp_pin_obj.pin_long;
        
        pin_text_type       = bmp_pin_obj.pin_image_two;

        $pin_icon = bmp_pin_obj.icon_link;
        if( bmp_pin_obj.icon_link == '' )
            $pin_icon   =  bmpIconsDefaultUrl + "pin-" + bmp_pin_obj.icon + ".png";
        else{
            if( ! bmp_pin_obj.icon_link.includes('http') )
                $pin_icon   =  bmpIconsCustomUrl + bmp_pin_obj.icon_link;
        }

        if( pin_text_type == 'none')
            pin_text_type = s_none;
        else if ( pin_text_type == 'simple')
            in_text_type = s_simple;
        else 
            pin_text_type = s_advanced;
        
        $pin_title      = bmp_pin_obj.pin_title.replace(/\\/g, '');
        $pin_title      = $pin_title.replace(/'/g, '&#39' );
        $pin_title      = $pin_title.replace(/"/g, '&#34' );

        $pin_desc       = bmp_pin_obj.pin_desc.replace(/\\/g, '');
        $pin_desc       = $pin_desc.replace(/bmp_nl/g, '<br/>');    
        $pin_desc       = $pin_desc.replace(/'/g, '&#39' );
        $pin_desc       = $pin_desc.replace(/"/g, '&#34' );

        var $pin_row        = bmpX('<tr/>').attr('data-id', $pin_id).attr('id', 'pin_' + $pin_id );
        var $pin_td_name    = bmpX('<td/>').text( $pin_name ).css('font-weight', 'bold');
        var $pin_td_address = bmpX('<td/>').text( $pin_address );
        var $pin_lat_long   = bmpX('<td/>').text($pin_lat + ' || ' + $pin_long );
        var $pin_type       = bmpX('<td/>').text( pin_text_type );
        var $pin_html       = bmp_pin_obj.data_json.replace(/'/g, '&#39');
        var $pin_html       = $pin_html.replace(/"/g, '&#34');
        var $pin_count_maps = ( (bmpDataPinMaps[$pin_id] || []).length || 0 );
        var $pin_td_count_maps = bmpX('<td/>').text( $pin_count_maps ).attr('id', 'pin_used_on_maps' ).addClass('center-pos');
        
        var $pin_tooltip =  "<img style='cursor:pointer;' id='bmp_pin_image' width='28' height='28'  src='" + $pin_icon +"' />";

        if( (bmp_pin_obj.pin_image_two === 'simple') && ($pin_title !== '') && ($pin_desc !== '') ){
            $pin_tooltip =  "<img style='cursor:pointer;' id='bmp_pin_image' data-toogle='tooltip' data-html='true' data-placement='left' " +
                    " title='" + $pin_title + "<hr/>" + $pin_desc + "' width='28' height='28'  src='" + $pin_icon +"' />";
        }else if( (bmp_pin_obj.pin_image_two === 'advanced') && (bmp_pin_obj.data_json !== '') ){
            $pin_tooltip =  "<img style='cursor:pointer;' id='bmp_pin_image' data-toogle='tooltip' data-html='true' data-placement='left' " +
                " title='<div class=\"bmp_pin_icon_html\"> " + $pin_html + " </div>' width='28' height='28'  src='" + $pin_icon +"' />";
        }

        var $pin_td_icon    = bmpX('<td/>').append( $pin_tooltip );
        var $pin_td_actions = bmpX('<td/>').append( '<button type="button" data-id='+$pin_id+' onclick="BmpAssignPin(this);"  id="assign_bmp_pin" ' +
                                                    ' title="' + s_assign_to_map + '" data-toggle="tooltip" data-placement="bottom" class="button btn-info assign-bmp-map"> '+
                                                    '<i class="fa fa-map-marked"></i> </button> <span class="spacer"></span>' +
                                                    '<button type="button" data-id='+ $pin_id + ' onclick="BmpEditPin(this);"  id="edit_bmp_pin" ' +
                                                    ' title="' + s_edit + '" data-toggle="tooltip" data-placement="bottom" class="button btn-success edit-bmp-map"> ' +
                                                    '<i class="fa fa-edit"></i> </button> <span class="spacer"></span>' +
                                                    '<button type="button" data-id=' + $pin_id + ' onclick="BmpDeletePin(this);"  id="delete_bmp_pin" ' + 
                                                    ' title="' + s_delete + '" data-toggle="tooltip" data-placement="bottom" class="button btn-danger delete-bmp-map"> ' +
                                                 '<i class="fa fa-trash"></i> </button>' )
                                            .addClass('center-pos td-action-pins');

        $pin_row.append( $pin_td_name );
        $pin_row.append( $pin_td_address );
        $pin_row.append( $pin_lat_long );
        $pin_row.append( $pin_type );
        $pin_row.append( $pin_td_icon );
        $pin_row.append( $pin_td_count_maps );
        $pin_row.append( $pin_td_actions );

        if( action === 'prepend' )
            obj_to_prepend.prepend( $pin_row );  
        else
            obj_to_prepend.insertAfter( $pin_row );
        
        bmpX('#bmp_pin_image').tooltip({
            boundary: 'window',
            delay: { 'show' : 300, 
                     'hide' : 200 }
        });

        bmpX('[data-toggle="tooltip"]').tooltip({
            container : 'body'
        }); 
        
    }
}

function Bmp_Encode_Str( rawStr ){
    var encodedStr = rawStr.replace(/[\u00A0-\u9999<>\&]/gim, function(i) {
        return '&#'+i.charCodeAt(0)+';';
    });
    return encodedStr;
}


function RefreshMapPin(){
    
    if( bmpX('#bmp_admin_show_pin').length > 0 ){ 
        var bmp_api_key     = bmpDataApiKey.trim(); 
        var mapRequest      = "https://www.bing.com/api/maps/mapcontrol?key="+ bmp_api_key + "&callback=bmp_admin_load_map_pin";
        CallRestService( mapRequest, bmp_admin_load_map_pin );

    }   
}

function CallRestService(request) {
            
    var script = document.createElement("script");
    script.setAttribute("type", "text/javascript");
    script.setAttribute("src", request);
    document.body.appendChild(script);
    
}

function bmp_admin_load_map_pin(){
    var bmp_admin_show_map      = document.getElementById('bmp_admin_show_pin'); 
        bmp_admin_show_map.style.width = '100%';
        bmp_admin_show_map.style.height = '400px';
    var bmp_pin_selected = parseInt( bmpX('#bmp_new_pin_icon option:selected').val() );
    var bmp_pin_lat     = bmpX('#bmp_new_pin_lat').val().trim();
    var bmp_pin_long    = bmpX('#bmp_new_pin_long').val().trim();
//    var bmp_pin_title   = bmpX('#bmp_new_pin_title').val();
//    var bmp_pin_desc    = bmpX( '#bmp_new_pin_description').val();
    var bmp_pin_img_src = bmpX('#pin_icon_img img').attr('src');
    var bmp_map_zoom    = parseInt( bmpX('#bmp_map_zoom').val() );  

    bmp_pin_lat     = ( bmp_pin_lat.length > 0 ) ? bmp_pin_lat : '54.5260';
    bmp_pin_long     = ( bmp_pin_long.length > 0 ) ? bmp_pin_long : '15.2551';  
        
    
    bmp_bing_map_pins = new Microsoft.Maps.Map( bmp_admin_show_map, {
        center: new Microsoft.Maps.Location( bmp_pin_lat, bmp_pin_long ),
        zoom: bmp_map_zoom
    }); 
        

    if( bmp_pin_lat.length > 0 && bmp_pin_long.length > 0 ){
        if( (bmp_pin_selected == 0) && ( ! bmp_isValidCustomPin() ) )
            bmp_new_pin = new Microsoft.Maps.Pushpin( new Microsoft.Maps.Location(bmp_pin_lat, bmp_pin_long ));
        else
            bmp_new_pin = new Microsoft.Maps.Pushpin( new Microsoft.Maps.Location(bmp_pin_lat, bmp_pin_long ),{
                icon : bmp_pin_img_src,                
            });     
        if( (typeof bmp_pin_title !== 'undefined') && typeof (bmp_pin_desc !== 'undefined') ){
            bmp_new_pin.metadata = {
                title : bmp_pin_title,
                description : bmp_pin_desc
            };
        }

        bmp_pinDraggableHandler( bmp_new_pin );      

        bmp_bing_map_pins.entities.push( bmp_new_pin );
    }

    Microsoft.Maps.loadModule('Microsoft.Maps.AutoSuggest', function () {
        var options = {
            maxResults: 5,
            map: bmp_bing_map_pins          
        };
        var manager = new Microsoft.Maps.AutosuggestManager(options);
        manager.attachAutosuggest('#bmp_new_pin_address', '#searchBoxContainer', selectedSuggestion);
    });

    Microsoft.Maps.Events.addHandler( bmp_bing_map_pins, 'viewchangeend', function(e){        
        bmpX('#bmp_map_zoom').val( bmp_bing_map_pins.getZoom() );
    });

    function selectedSuggestion(suggestionResult) {
        bmpClearPinsFromMap();
        bmp_bing_map_pins.setView({ bounds: suggestionResult.bestView });
        var bmp_pin_selected = parseInt( bmpX('#bmp_new_pin_icon option:selected').val() );
        var bmp_pin_img_src = '';
        if( bmp_isValidCustomPin() ){
            bmp_pin_img_src = bmpX('#bmp_custom_pin_img').attr('src'); 
        }else{
            bmp_pin_img_src = bmpX('#pin_icon_img img').attr('src');
        }
             
        if( (typeof suggestionResult.location.latitude  !== 'undefined' ) && 
            (typeof suggestionResult.location.longitude !== 'undefined' )  ){

            if( (bmp_pin_selected == 0) && (! bmp_isValidCustomPin() ))
                bmp_new_pin = new Microsoft.Maps.Pushpin( new Microsoft.Maps.Location(suggestionResult.location.latitude, suggestionResult.location.longitude));
            else
                bmp_new_pin = new Microsoft.Maps.Pushpin( new Microsoft.Maps.Location(suggestionResult.location.latitude, suggestionResult.location.longitude),{
                    icon : bmp_pin_img_src
                });

            bmp_pinDraggableHandler( bmp_new_pin );

            bmp_bing_map_pins.entities.push( bmp_new_pin );
            bmp_bing_map_pins.setView({
                center: new Microsoft.Maps.Location(suggestionResult.location.latitude, suggestionResult.location.latitude),
                zoom: bmp_map_zoom
            });

        }else{
            bmp_new_pin = new Microsoft.Maps.Pushpin(suggestionResult.location);
            bmp_bing_map_pins.entities.push(bmp_new_pin);
        }
        document.getElementById('bmp_new_pin_lat').value = suggestionResult.location.latitude;
        document.getElementById('bmp_new_pin_long').value = suggestionResult.location.longitude;

    }

}

function bmp_pinDraggableHandler( pin_obj ){
    if( pin_obj == null || ( typeof pin_obj === 'undefined' ) )
        return;

    pin_obj.setOptions({
        draggable : bmp_dragThePin
    });

    if( ! Microsoft.Maps.Events.hasHandler( pin_obj, 'dragend') ){
       
        Microsoft.Maps.Events.addHandler( pin_obj, 'dragend', function(){
            bmpX( '#bmp_new_pin_lat').val( pin_obj.getLocation().latitude );
            bmpX( '#bmp_new_pin_long').val( pin_obj.getLocation().longitude );
        });
    }
}

function bmp_isValidCustomPin(){
    let result = false;
    let val_el = bmpX('#bmp_pin_custom_url').val().trim();
    if( val_el != '' )
        result = true;

    return result;
}

function bmp_show_pin_on_map( bmp_pin_obj ){
    var bmp_pin_lat     = bmp_pin_obj.pin_lat;
    var bmp_pin_long    = bmp_pin_obj.pin_long;

    var bmp_pin_img_src = '';
    if( bmp_pin_obj.icon_link.length > 0 )
        bmp_pin_img_src = bmp_pin_obj.icon_link;
    else
        bmp_pin_img_src = bmpIconsDefaultUrl +'pin-' + bmp_pin_obj.icon +'.png';
        
    let bmp_pin_title, bmp_pin_desc, bmp_pin_html = '';   
    let pin_type = 0; 
    if( bmp_pin_info.radio == 'simple'){
        bmp_pin_title = bmp_pin_info.title;
        bmp_pin_desc = bmp_pin_info.desc.replace(/\n|bmp_nl/g, '<br/>');
        bmp_pin_desc = bmp_pin_desc.replace(/\\/g, '');
        pin_type = 1;
    }else if( bmp_pin_info.radio == 'advanced' ){
        bmp_pin_html = bmp_pin_info.html.replace(/\\/g, '');
        pin_type = 2;
    }   
    
    var bmp_zoom        = bmpX('#bmp_map_zoom').val();

    if( bmp_pin_lat.length > 0 && bmp_pin_long.length > 0 ){
       
        bmpClearPinsFromMap();

        if( bmp_pin_obj.icon == 0 && ( (! bmp_isValidCustomPin()) )  )
            bmp_new_pin = new Microsoft.Maps.Pushpin( new Microsoft.Maps.Location(bmp_pin_lat, bmp_pin_long ));
        else
            bmp_new_pin = new Microsoft.Maps.Pushpin( new Microsoft.Maps.Location(bmp_pin_lat, bmp_pin_long ),{
                icon : bmp_pin_img_src
            });

        infobox = new Microsoft.Maps.Infobox(bmp_bing_map_pins.getCenter(), {
            maxWidth: bmpPinSizes.width,
            maxHeight: bmpPinSizes.height,
            visible: false        
        });
        
    
        bmp_new_pin.metadata = {
            title : bmp_pin_title,
            description : bmp_pin_desc,
            html : bmp_pin_html,
            pintype : pin_type,
            width : getBmpInfoboxWidth(),
            height: getBmpInfoboxHeight()
        };

        bmp_pinDraggableHandler( bmp_new_pin );

        infobox.setMap( bmp_bing_map_pins );
        
        Microsoft.Maps.Events.addHandler(bmp_new_pin, 'click', pushpinClicked);

        bmp_bing_map_pins.entities.push( bmp_new_pin );

        bmp_bing_map_pins.setView({ center: new Microsoft.Maps.Location( bmp_pin_lat, bmp_pin_long ), zoom: 12 }); 
    }
}

function pushpinClicked(e) {
    //Make sure the infobox has metadata to display.
    if (e.target.metadata) {
        //Set the infobox options with the metadata of the pushpin.
        let location = e.location;
        let shape = e.target;
        if( shape instanceof Microsoft.Maps.Pushpin )
            location = shape.getLocation();

        if( e.target.metadata.pintype === 1 ){ //simple
            if( ( e.target.metadata.title.length > 0 ) &&
                ( e.target.metadata.description.length > 0 ) ){
                    infobox.setOptions({
                            location: location,
                            title : e.target.metadata.title,
                            description: e.target.metadata.description,
                            visible: true
                    });     
            }
        }else if( e.target.metadata.pintype === 2 ){// advanced
            infobox.setOptions({
                location: location,
                htmlContent: '<div class="bmp_pin_info_wrapper"   >' +   
                                '<div class="bmp_pin_info_container" >' +    
                                    '<div class="bmp_pin_info_header">   <div id="bmp_pin_info_close_img"> '+
                                        '<img onclick="bmpInfoboxHideMe(this);" src="'+ bmpIconsUrl +'bmp-infobox-close.svg" /> </div></div>' +
                                    
                                    '<div class="bmp_pin_info_body" style="width:' + e.target.metadata.width + 'px; height: '+ e.target.metadata.height+'px"  >' + e.target.metadata.html +  
                                '</div>' +                               
                                '</div>' +
                                '<div class="bmp_pin_info_down_arrow"></div>' +
                             '</div>',
                visible: true,
                showPointer: true, 
                showCloseButton: true,
                offset: new Microsoft.Maps.Point( ( e.target.metadata.width / 2 ) * -1.05  , 12 )
            })
        }

    }
}

function bmpClearPinsFromMap(){
    if( typeof bmp_bing_map_pins.entities !== 'undefined' )
        bmp_bing_map_pins.entities.clear(); 
    var bmp_pin_info_container = document.getElementsByClassName('bmp_pin_info_wrapper'); //.style.display = 'none';
    //bmp_pin_info_container[0].style.display = 'none';
    for( var i = 0; i < bmp_pin_info_container.length; i++ ){
        bmp_pin_info_container[i].style.display = 'none';
    }
}

function bmpInfoboxHideMe(el){
    var bmp_pin_info_container = document.getElementsByClassName('bmp_pin_info_wrapper'); //.style.display = 'none';
    //bmp_pin_info_container[0].style.display = 'none';
    for( var i = 0; i < bmp_pin_info_container.length; i++ ){
        bmp_pin_info_container[i].style.display = 'none';
    }
    infobox.setOptions({
        visible: false
    }); 
}

function bmp_get_pin_data(){
    var pin_obj = {};
    pin_obj.pin_name    = bmpX('#bmp_new_pin_name').val().trim(); 
    pin_obj.pin_address = bmpX('#bmp_new_pin_address').val().trim();
    pin_obj.pin_lat     = bmpX('#bmp_new_pin_lat').val().trim();
    pin_obj.pin_long    = bmpX('#bmp_new_pin_long').val().trim();
    pin_obj.icon        = bmpX('#bmp_new_pin_icon option:selected').val();
    pin_obj.icon_link   = bmpX('#bmp_pin_custom_url').val().trim();

    if( pin_obj.icon_link.length > 0 && bmpX('#bmp_custom_pin_img').is(':visible') )
        pin_obj.icon_link = bmpX('#bmp_custom_pin_img').attr('src');
    else 
        pin_obj.icon_link = '';

    pin_obj.pin_info    = bmp_pin_info;

    return pin_obj;
}

function bmp_is_new_pin_changed(){
    var pin_obj = bmp_get_pin_data();
    var pin_icon = parseInt( bmpX('#bmp_new_pin_icon option:selected').val() );
    var aChanged = false;
    if( pin_obj.pin_name.length > 0 || pin_obj.pin_address.length > 0 || pin_obj.pin_lat.length > 0 || pin_obj.pin_long.length > 0 )
        aChanged = true;
        
    return aChanged;
}

function BmpEditPin( el ){
    var pin_id = el.getAttribute('data-id');
    if ( bmp_is_new_pin_changed() ){
        bmpX('#bmp_btn_edit_pin').attr('data-id', pin_id );
    //    bmpX('#bmp_modal_edit_pin').modal('show');             
    }else{                      
        bmpX('#pin_action_id').val( pin_id );
        bmpX('#pin_action').val('edit-pin');           
        bmpX('#bmp_pin_hidden_pin_id').val( pin_id );
        bmp_show_edited_pin( pin_id );   
        bmpX('#bmp_view_new_pin').trigger('click');
        bmpX('#bmp_save_and_new_pin').hide();  
    }
    
}


function bmp_show_edited_pin( pin_id  ){   
    var $edit_pin = bmp_get_pin_obj( pin_id );
  
    if( $edit_pin !== null ){
        bmp_update_pin_inputs( $edit_pin );
    //    bmp_show_pin_on_map( $edit_pin )

        if( ! bmpX('.new-pin-block').hasClass('show-new-pin-block') ){
            bmpX('#bmp_new_pin img').trigger('click');  
        }
    }
}

function bmp_clear_pin_info_modal(){
    bmpX('#bmp_new_pin_title, #bmp_new_pin_description, #bmp_in_desc_img, #bmp_in_desc_link, #bmp_in_desc_link').val('');
    bmpX('#bmp_in_desc_img').attr('data-url', '');
    $('#bmp_pin_link_open_new').bootstrapToggle('off');
    $('#bmp_in_desc_img_top').prop('checked', true );
}


function bmp_get_pin_obj( $pin_id ){
    var $found = false;
    var $i = 0;
    var $pin_index = 0; 

    while( $i < bmpAllPins.length && ( ! $found ) ){
        if( bmpAllPins[$i].id == $pin_id ){
            $found = true;
            $pin_index = $i;
        }

        $i++;
    }
    
    if( $found ){
        bmp_pin_info = {
            type        : bmpAllPins[ $pin_index ].pin_image_one,
            title       : bmpAllPins[ $pin_index ].pin_title.replace(/\\/g, ''),
            desc        : bmpAllPins[ $pin_index ].pin_desc.replace(/\\/g, ''),
            html        : bmpAllPins[ $pin_index ].data_json,
            radio       : bmpAllPins[ $pin_index ].pin_image_two
        }
        bmpX('#radio_bmp_pin_' + bmp_pin_info.radio ).attr('checked', true );

        return bmpAllPins[ $pin_index ];
    }else{
        return null;
    }
}


function bmp_update_pin_inputs( $pin_obj ){   

    var pin_name, pin_address, pin_title, pin_desc;
    var newline = String.fromCharCode(13, 10);
    pin_name    = $pin_obj.pin_name.replace(/\\/g, '');
    pin_address = $pin_obj.pin_address.replace(/\\/g, '' );
    pin_title   = $pin_obj.pin_title.replace(/\\/g, '' );
    pin_desc    = $pin_obj.pin_desc.replace(/\\/g, '');
    
    pin_desc    = pin_desc.replace(/bmp_nl/g, newline );  

    bmpX('#bmp_new_pin_name').val( pin_name );
    bmpX('#bmp_new_pin_address').val( pin_address );
    bmpX('#bmp_new_pin_lat').val( $pin_obj.pin_lat );
    bmpX('#bmp_new_pin_long').val( $pin_obj.pin_long );
    bmpX('#bmp_new_pin_icon option').prop('selected', false );
    bmpX('#bmp_new_pin_icon #sel-' + $pin_obj.icon +' ' ).prop('selected', true);
    bmpX('#pin_icon_img img').attr('src', bmpIconsDefaultUrl + 'pin-' + $pin_obj.icon + '.png' );
    bmpX('#bmp_pin_custom_url').val( $pin_obj.icon_link );
    bmpX('#radio_bmp_pin_' + $pin_obj.pin_image_two).prop('checked', true );

    
    if( $pin_obj.icon_link !== '' ){
        if( $pin_obj.icon_link.includes('http')  )
            bmpX('#bmp_custom_pin_img').attr('src', $pin_obj.icon_link).show();        
        else
            bmpX('#bmp_custom_pin_img').attr('src', bmpIconsCustomUrl + $pin_obj.icon_link ).show();  
    }else{
        bmpX('#bmp_custom_pin_img').hide();   
    } 

   
}
