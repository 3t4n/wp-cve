
document.addEventListener("DOMContentLoaded", function() {
    var itemCount = jQuery(".displaying-num").first().text();
    var selectAllHtml = "<div id='select-everything-container' style='margin: 10px'> \
        <label >\
            <input id='select-everything-checkbox' name='plty-select-everything' type='checkbox' style='margin-right: 10px;'>Select all " + itemCount + " \
        </label> \
    </div>";
    jQuery(".check-column input[type=checkbox]").on('change', function(ev){
        var selectAllcheckbox = jQuery("#select-everything-checkbox");
        if(selectAllcheckbox.length){
            selectAllcheckbox.prop("checked", () => false);
            selectAllcheckbox.value = false;
        }
    })
	jQuery('select#bulk-action-selector-top').on('change', function (e) {
		var optionSelected = jQuery("option:selected", this);
        var valueSelected = this.value;
        if(valueSelected=="platy-syncer-etsy"){

            jQuery(".tablenav.top").after(selectAllHtml);
            jQuery("#select-everything-checkbox").on('change', function(e){
                
                if(this.checked){
                    this.value = true;
                    jQuery('.check-column input[type=checkbox]').prop('checked', function(i, v) { return true; });
                }else{
                    this.value = false;
                }
            });

        }else{
            jQuery('#select-everything-container').remove();
        }
	});

});