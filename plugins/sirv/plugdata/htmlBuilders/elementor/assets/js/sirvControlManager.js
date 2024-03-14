/*jQuery( window ).on( 'elementor:init', function() {
    var ControlSirvView = elementor.modules.controls.BaseData.extend( {
        onReady: function() {
            var self = this,
                sirv_data = this.model.get( 'sirv_data' );

                console.log(sirv_data);
                var test = _.extend(sirv_data, {'key1': Math.random()});
                console.log(sirv_data);
                window.tstelementor = elementor;
                console.log(elementor);
                //this.model.set('sirv_data', sirv_data);
                this.saveValue();

        },

        saveValue: function() {
            this.setValue( this.sirv_data );
        },

        onBeforeDestroy: function() {
            this.saveValue();
            //this.ui.textarea.emojioneArea().destroy();
        }
    } );
    elementor.addControlView( 'sirvcontrol', ControlSirvView );
} );*/

jQuery(function($){
    window.updateElementorSirvControl = function(jsonStr, isOnLoadRender){
        let $showShContainer = $('.sirv-data-elementor');
        let $hiddenFieldEl = $('input[data-setting=sirv-data-string]');

        //console.log('jsonStr => ', jsonStr);

        let jStr = (jsonStr == '' && $hiddenFieldEl.val() !== '') ? $hiddenFieldEl.val() : jsonStr;

        //console.log('$hiddenFieldEl.val() =>', $hiddenFieldEl.val());

        //console.log('jStr => ', jStr);

        renderElementorControlShData($showShContainer, jStr);

        if(!isOnLoadRender) $hiddenFieldEl.val(jStr).trigger('input');
    }


    function renderElementorControlShData(selector, jsonStr){
        if(jsonStr !== '' && isJsonString(jsonStr)){
            let elementorObj = JSON.parse(jsonStr);
            let renderTemplate = '';

            //console.log(jsonStr);
            if ((Object.keys(elementorObj.shortcode)).length > 0){
                let id = elementorObj.shortcode.id;
                let count = elementorObj.shortcode.count;
                let type = elementorObj.shortcode.type;
                let images = elementorObj.shortcode.images;
                let templateStart = '<div class="sirv-sc-view data-id-'+ id +'" data-id="'+ id +'" data-shortcode="%5Bsirv-gallery%20id%3D'+ id +'%5D">' +
                                        '<div class="sirv-overlay" data-id="'+ id +'">' +
                                            '<span class="sirv-overlay-text">'+ type +': '+ count +' images</span>' +
                                            '<a href="#" title="Delete gallery" class="sirv-delete-sc-view sc-view-button sc-buttons-hide dashicons dashicons-no" data-id="'+ id +'" data-mce-href="#">Delete Gallery</a>' +
                                            '<a href="#" data-id="'+ id +'" title="Edit gallery" class="sirv-edit-sc-view sc-view-button sc-buttons-hide dashicons dashicons-admin-generic" data-mce-href="#">Edit Gallery</a>' +
                                            '<br data-mce-bogus="1">' +
                                        '</div>';
                let templateEnd = '</div>';
                let templateImages = '';

                images.forEach( function(imageSrc, index) {
                    // statements
                    templateImages +='<img src="'+ imageSrc +'" alt="" data-mce-src="'+ imageSrc +'">';
                });

                renderTemplate =  templateStart + templateImages + templateEnd;

                }else{
                    let images = elementorObj.images;
                    let type = images.full.type;
                    let count = images.full.count;
                    let thumbs = images.thumbs;

                    let templateStart = '<div class="sirv-sc-view">' +
                                            '<div class="sirv-overlay">' +
                                                '<span class="sirv-overlay-text">'+ type +': '+ count +' images</span>' +
                                                '<a href="#" title="Delete gallery" class="sirv-delete-sc-view sc-view-button sc-buttons-hide dashicons dashicons-no" data-mce-href="#">Delete Gallery</a>' +
                                                '<br>' +
                                            '</div>';
                    let templateEnd = '</div>';
                    let templateImages = '';

                    thumbs.forEach( function(imageSrc, index) {
                    // statements
                    templateImages +='<img src="'+ imageSrc +'" alt="" data-mce-src="'+ imageSrc +'">';
                });

                renderTemplate =  templateStart + templateImages + templateEnd;


            }

            $(selector).empty();
            $(selector).append(renderTemplate);
            bindEventstoShControl();
        }
    }


    function bindEventstoShControl(){
        $('.sirv-delete-sc-view').on('click', deleteShortcode);
        $('.sirv-edit-sc-view').on('click', editShortcode);
    }


    function deleteShortcode(selector){
        $('input[data-setting=sirv-data-string]').val('').trigger('input');
        $('.sirv-data-elementor').empty();
    }


    function editShortcode(){
        let shData = getShortcodeData();
        let id = shData.shortcode.id;

        window.isSirvElementor = true;

        renderSirvModalWindowWithParams(id, true, false, false, function(){
        });

    }


    function getShortcodeData(){
        let $hiddenFieldEl = $('input[data-setting=sirv-data-string]');
        let jsonStr = $hiddenFieldEl.val();
        let shObj = {};

        if(jsonStr !== '' && isJsonString(jsonStr)){
            shObj = JSON.parse(jsonStr);
        }

        return shObj;
    }


    function isJsonString(str) {
        try {
            JSON.parse(str);
        } catch (e) {
            return false;
        }
        return true;
    }

    $(window).on('renderShPanel', function(){
        setTimeout(function(){updateElementorSirvControl('', true);}, 300);
    });


    $(document).ready(function(){
        $('body').on('click', '.sirv-elementor-add-media', function(event){
            window.isSirvElementor = true;
        });

    }); // dom ready end

}); //closure end
