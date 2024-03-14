/*  
 * Robo Maps            http://robosoft.co/wordpress-google-maps
 * Version:             1.0.6 - 19837
 * Author:              Robosoft
 * Author URI:          http://robosoft.co
 * License:             GPL-2.0+
 * License URI:         http://www.gnu.org/licenses/gpl-2.0.txt
 * Date:                Thu, 18 May 2017 11:11:10 GMT
 */


var roboMap = (function() {

    function roboMap() {
        this.typePosition = '';
        this.address = '';
        this.latitude = '';
        this.longitude = '';
        this.marker = 'show';
        this.caption = '';
        this.sizeW = '';
        this.sizeH = '';
        this.map = '';
        this.zoom = '';
        this.tagHTML = '';
        this.error = 0;
        if( !this.readData() ) this.error = 1;
    };
    //----------------------------------------
    roboMap.prototype.getCorrectSize = function(inputSize) {
        if (inputSize == '') return false;
        if (inputSize.indexOf('%') == -1 && inputSize.indexOf('px') == -1) inputSize += 'px';
        return inputSize;
    };
    roboMap.prototype.getCorrectBool = function(nameVal) {
        var returnVal = jQuery('[name="robo-map-' + nameVal + '"]:checked').val();
        this.tagHTML += ' ' + nameVal + '="' + returnVal + '"';
    };
    roboMap.prototype.readData = function() {
        this.typePosition = jQuery('[name="robo-map-type-position"]:checked').val();
        if (this.typePosition == 'address') {
            this.address = jQuery('[name="robo-map-address"]').val();
            if(!this.address) return false;
            this.tagHTML += ' address="' + this.address + '"';
        } else {
            this.latitude = jQuery('[name="robo-map-latitude"]').val();
            this.longitude = jQuery('[name="robo-map-longitude"]').val();
             if(!this.latitude || !this.longitude) return false;
            this.tagHTML += ' lat="' + this.latitude + '" lng="' + this.longitude + '"';
        }
        this.marker = jQuery('[name="robo-map-marker"]:checked').val();
        this.caption = jQuery('[name="robo-map-caption"]').val();
        switch (this.marker) {
            case 'click':
                this.tagHTML += ' marker="2"';
                if(this.caption) this.tagHTML += ' caption="' + this.caption + '"';
                break;
            case 'show':
                this.tagHTML += ' marker="1"';
                if(this.caption) this.tagHTML += ' caption="' + this.caption + '"';
                break;
        }
        this.sizeW = this.getCorrectSize(jQuery('[name="robo-map-width"]').val());
        if( this.sizeW ) this.tagHTML += ' sizeW="' + this.sizeW + '"';

        this.sizeH = this.getCorrectSize(jQuery('[name="robo-map-height"]').val());
        if( this.sizeH ) this.tagHTML += ' sizeH="' + this.sizeH + '"';

        this.map = jQuery('[name="robo-map-type-view"]:checked').val();
        if( this.map ) this.tagHTML += ' map="' + this.map + '"';

        this.zoom = jQuery('[name="robo-map-zoom"]').val();
        this.tagHTML += ' zoom="' + this.zoom + '"';

        this.getCorrectBool('scroll');
        this.getCorrectBool('street');
        this.getCorrectBool('zoomcontrol');
        this.getCorrectBool('pan');
        this.getCorrectBool('mapcontrol');
        this.getCorrectBool('overview');
        return true;
    };
    //--------------------------------------
    roboMap.prototype.getTag = function() {
        return '[showmap' + this.tagHTML + ']';;
    };
    return roboMap;
})();

jQuery(document).ready(function() {
	
    var addressRow  = jQuery('#robo-map-row-address'),
        coordRow    = jQuery('#robo-map-row-coord'),
        captionRow  = jQuery('#robo-map-row-caption'),
        preview     = jQuery('#robo-map-preview');

    jQuery('[name="robo-map-type-position"]').change(function(event) {
        addressRow.toggleClass('hidden');
        coordRow.toggleClass('hidden');
    });

    jQuery('[name="robo-map-marker"]').change(function(event) {
        if (jQuery(this).val() != 'off') {
            captionRow.removeClass('hidden');
        } else {
            captionRow.addClass('hidden');
        }
    });

    jQuery('#robo-map-insert-button').click(function() {
        var mapObj = new roboMap();
        if(!mapObj.error){
        	window.parent.send_to_editor(mapObj.getTag());
        	window.parent.tb_remove();
        	roboMapDialog.dialog('close');
        } else {
        	alert(robo_maps_trans.inputAddress);
        }
    });

    jQuery('#robo-map-preview-header').click(gotoPro);
    jQuery('#robo-map-add-marker').click(gotoPro);
    jQuery('#robo-map-save-map').click(gotoPro);
    jQuery('#robo-map-import-markers').click(gotoPro);
    jQuery('#robo-map-type-map-osm').click(gotoPro);
    
    function gotoPro() {
        jQuery('#robomap-tab-label-pro').tab('show');
        return false;
        // window.location.href = "https://robosoft.co/products_info/goto.php?content=desc";
    };

    jQuery('#robo-map-tab-header a').on('shown.bs.tab', function(e) {
        var target = jQuery(e.target).attr("href") // activated tab
        if (target == '#robo-map-tab-pro') {
            preview.hide();
            jQuery('#robo-map-buy-button').show();
            roboDialogButtonPane.hide();
        } else {
        	preview.show();
        	jQuery('#robo-map-buy-button').hide();
        	roboDialogButtonPane.show();
        }
    });
  

  var roboMapDialog = jQuery("#robo-map-modal").appendTo("body");

	roboMapDialog.dialog({
		'dialogClass' : 'wp-dialog',
		'title': robo_maps_trans.roboMapsTitle,
		'modal' : true,
		'autoOpen' : false,
		'width': 'auto', // overcomes width:'auto' and maxWidth bug
	    'maxWidth': 700,
	    'height': 'auto',
	    'fluid': true, 
	    'resizable': false,
		'responsive': true,
		'draggable': false,
		'closeOnEscape' : true,
		'buttons' : [{
				'text' : robo_maps_trans.closeButton,
				'class' : 'button-default',
				'click' : function() { jQuery(this).dialog('close'); }
		}],
		open: function( event, ui ) {
			var options = {};
	        options.address = 'DC USA';
	        options.zoom = 10;
	        jQuery('#robo-map-preview-block').gMap(options);
		}
	});


	jQuery('#robo-map-tag').click( function(){
		roboMapDialog.dialog('open');  
		return false;
	});

	//jQuery('#robo-map-tag').click();

	var roboDialogButtonPane = roboMapDialog.next('.ui-dialog-buttonpane');

	jQuery('#robo-map-dialog-button').contents().prependTo( roboDialogButtonPane );

	jQuery(window).resize(function () {  fluidDialog(); });
	jQuery(document).on("dialogopen", ".ui-dialog", function (event, ui) { fluidDialog(); });

	function fluidDialog() {
	    var $visible = jQuery(".ui-dialog:visible");
	    $visible.each(function () {
	        var $this = jQuery(this);
	        var dialog = $this.find(".ui-dialog-content").data("ui-dialog");
	        if (dialog.options.fluid) {
	            var wWidth = jQuery(window).width();
	            if (wWidth < (parseInt(dialog.options.maxWidth) + 50))  {
	                $this.css("max-width", "90%");
	            } else {
	                $this.css("max-width", dialog.options.maxWidth + "px");
	            }
	            dialog.option("position", dialog.options.position);
	        }
	    });
   	}
});
