<?php
    $ui_type = (!empty($_REQUEST['ui_type']) && $_REQUEST['ui_type']==='adder') ? 'adder' : 'finder';
    $type = (!empty($_REQUEST['type'])) ? sanitize_text_field($_REQUEST['type']) : '';
?>
<style>
    html{
        padding:0!important;
    }
    #wpcontent{
        margin: 0;
    }
    #wpadminbar,#adminmenumain,.notice{
        display: none;
    }
    .page-wrap .page-title+hr{
        padding-bottom: 30px;
    }
    .ifso-autocomplete-generator .row{
        position: relative;
        width: 49%;
    }
    .ifso-autocomplete-generator .row.inputs-row{
        float: left;
    }
    .ifso-autocomplete-generator .inputs-row .instructions span{
        padding: 0 6px;
        border: 1px solid;
        border-radius: 50%;
        font-size: 0.8em;
        margin-right: 4px;
    }
    .ifso-autocomplete-generator .inputs-row .input-wrapper{
        margin-bottom:5px;
    }
    .ifso-autocomplete-generator .inputs-row .input-wrapper input, .ifso-autocomplete-generator .inputs-row .input-wrapper select{
        width:100%;
    }
    .ifso-autocomplete-generator .inputs-row .input-wrapper input{
        padding: 4px;
        border: 1px solid #8c8f94;
        border-radius: 4px;
    }
    .ifso-autocomplete-generator .row.results-row{
        float: right;
        padding: 0px 20px;
        box-sizing: border-box
    }
    .results_display_wrap .results_display .locations-type{
        text-transform: capitalize;
    }
    .results_display_wrap .results_display{
        margin-bottom: 20px;
    }
    .results_display_wrap .copy_results_wrap{
        text-align: center;
    }
    .results_display_wrap .copy_results_wrap .reset_btn{
        border-color:#b32d2e;
        color:#b32d2e;
    }
    .results_display_wrap .copy_results_wrap .reset_btn:focus{
        box-shadow: 0 0 0 1px #b32d2e;
    }
    .results_display_wrap .copy_results_wrap .results_copied_notification{
        color: #008a20;
        left: -32px;
        position: relative;
        top: -10px;
        display:none;
    }
    .results_display_wrap .location-version-wrap{
        position: relative;
        margin-bottom: 2px;
    }
    .results_display_wrap .results_display .remove-location{
        cursor:pointer;
    }
    .instructions_display_wrap{
        padding-bottom: 10px;
        line-height: 1.5;
        margin-top: 30px;
    }
    .instructions_display_wrap .inst-title{
        text-decoration: underline;margin-bottom: 20px;
    }

</style>
    
<div class="page-wrap">
    <h1 clasx="page-title">Select Locations</h1>
    <hr>
    <form class="ifso-autocomplete-generator">
        <div class="row inputs-row">
            <div class="input-wrapper">
                <select id="location_type_select" name="location_type">
                    <option <?php if($type==='country') echo "SELECTED" ?> value="country">Country</option>
                    <option <?php if($type==='city') echo "SELECTED" ?> value="city">City</option>
                    <option <?php if($type==='state') echo "SELECTED" ?> value="state">State</option>
                </select>
            </div>
            <div class="input-wrapper">
                <input placeholder="Start Typing..." class="autocomplete-field countries-autocomplete" ac_type="country" name="country_input_val">
            </div>
            <div class="input-wrapper">
                <input placeholder="Start Typing..." class="autocomplete-field " ac_type="city" name="city_input_val">
            </div>
            <div class="input-wrapper">
                <input  placeholder="Start Typing..." class="autocomplete-field" ac_type="state" name="state_input_val">
            </div>
        </div>

        <div class="row results-row">
            <div class="results_display_wrap">
                <div class="results_display">
                    <div class="locations-description">Targeted locations:</div>
                    <div class="locations-display"></div>
                </div>
                <div class="copy_results_wrap">
                    <button class="copy_btn button button-large button-primary" type="button">Copy</button>
                    <button class="reset_btn button button-large" type="button">Reset </button>
                    <p class="results_copied_notification">Copied!</p>
                </div>
                <input hidden readonly="readonly" id="generated-location-result">
            </div>
            <?php if($ui_type==='finder'){ ?>
            <div class="instructions_display_wrap" >
                <p class="inst-title">How to apply the targeted locations to your condtion</p>
                <ol>
                    <li>Add locations to the list</li>
                    <li>Click Copy</li>
                    <li>Back on your page, paste the values in the location field of your geolocation condition and click Enter</li>
                </ol>
                <a href="https://www.if-so.com/manual-geolocation-targeting/?utm_source=Plugin&utm_medium=Elementor&utm_campaign=inlineHelp">Learn more</a>
            </div>
            <?php } ?>
        </div>
    </form>
</div>

<script>
    jQuery(document).ready(function () {
        var myGen = new LocationGenerator(document.querySelector('form'));
    });

    var LocationGenerator = function(form){
        this.form = form;
        this.typeSelect = this.form.querySelector('#location_type_select');
        this.type=this.typeSelect.value;
        this.locations = [];
        this.fields = this.getAutocompleteFields();
        this.results_field = this.form.querySelector('#generated-location-result');

        this.changeLocationType(this.typeSelect.value);
        this.initListeners();
    }
    LocationGenerator.prototype = {
        initListeners : function(){
            var _this = this;
            this.typeSelect.addEventListener('change',function(e){
                _this.changeLocationType(e.target.value);
            })
            jQuery.each(this.fields,function (key,val){
                jQuery(val).on('change',function (e){
                    if(key==='country') {
                        var data = jQuery(this).getSelectedItemData();
                        if (!data || data == -1) return;
                        if(key==='country')
                            _this.addLocation(data.code,'COUNTRY');
                        val.value = '';
                    }
                });
            });
            initCityAutocomplete(this.fields.city,function (elem,ac){_this.selectedPlace('city',elem,ac)});
            initStateAutocomplete(this.fields.state,function (elem,ac){_this.selectedPlace('state',elem,ac)});
            this.form.querySelector('.copy_results_wrap .copy_btn').addEventListener('click',function (){
                _this.copyResults();
                if(_this.results_field.value!=='' && _this.results_field.value!=='[]')
                    _this.flashCopyLabel();
            });
            this.form.querySelector('.copy_results_wrap .reset_btn').addEventListener('click',function (){_this.changeLocationType(this.type,true);});
            if(this.openerHasPipe())
                this.form.querySelector('.copy_results_wrap .copy_btn').innerHTML = 'Insert';
        },
        getAutocompleteFields : function (){
            var ret = {};
            jQuery('.autocomplete-field').each(function (){ret[this.getAttribute('ac_type')] = this;});
            return ret;
        },
        changeLocationType : function(type,reset=false){
            if(reset){
                this.form.reset();
                this.locations = [];
            }
            this.typeSelect.value = type;
            this.type = type;
            jQuery.each(this.fields,function (key,val){
                var el_wrap = jQuery(val).closest('.input-wrapper');
                if(key!==type) el_wrap.hide();
                else el_wrap.show();
            });
            this.renderResults();
        },
        addLocation : function (location,location_type,extra_fields){
            var location_obj = {loc_type:location_type,loc_val:location,extra_fields:extra_fields};
            if(this.locationExists(location_obj)===false)
                this.locations.push(location_obj);

            this.renderResults();
        },
        removeLocation : function (location){
            var locationIndex = this.locationExists(location)
            if(locationIndex !== false)
                this.locations.splice(locationIndex,1);
            this.renderResults();
        },
        locationExists : function(test_location){
            var ret = false;
            this.locations.forEach(function(location,i){
                if(location.loc_type===test_location.loc_type && location.loc_val===test_location.loc_val)
                    ret = i;
            });
            return ret;
        },
        selectedPlace : function (type,elem,autocomplete){
            var place = autocomplete.getPlace();
            var address_components = place.address_components;
            var extra_fields = {};
            if(type==='city')
                var selectedThing = (place.vicinity) ? place.vicinity : "";
            else if(type==='state')
                var selectedThing = address_components.length>0 && address_components[0].long_name ? address_components[0].long_name : "";
            if(selectedThing!==''){
                extra_fields.countryCode = address_components[address_components.length-1].short_name;
                extra_fields.countryName = address_components[address_components.length-1].long_name;
                if(type==='city' && address_components[address_components.length-2].types.includes('administrative_area_level_1'))
                    extra_fields.stateProv = address_components[address_components.length-2].long_name;
                var locType = type==='state' ? 'STATE' : 'CITY';
                this.addLocation(selectedThing,locType,extra_fields);
                elem.value = '';
            }
        },
        renderResultField : function (){
            this.results_field.value = JSON.stringify(this.locations);
        },
        copyResults:function (){
            this.results_field.select();
            this.results_field.setSelectionRange(0, 99999); // For mobile devices
            navigator.clipboard.writeText(this.results_field.value);
            if(this.openerHasPipe()){
                window.opener.ifsoLocGenPipe.accept(this.results_field.value);
                window.close();
            }
        },
        renderResultsDisplay : function (){
            var _this = this;
            var display = this.form.querySelector('.results_display .locations-display');
            display.innerHTML = '';
            jQuery.each(this.locations,function (key,location){
                var type = location.loc_type;
                var loc = location.loc_val;
                var el = document.createElement('div');
                el.innerHTML = '<span class="location-name specific-location">' + type + ' : ' + loc  + '</span><button type="button" class="remove-location"><i class="fa fa-times-circle-o" aria-hidden="true"></i></button>'
                el.classList.add('location-version-wrap');
                el.setAttribute('location_val',loc);
                var remove_btn = el.querySelector('.remove-location');
                remove_btn.addEventListener('click',function (){_this.removeLocation(location);})
                display.appendChild(el);
            });
            //if(display.innerHTML==='')jQuery(display).closest('.results_display_wrap').hide();
            //else jQuery(jQuery(display).closest('.results_display_wrap')).show();
        },
        renderResults : function (){
            this.renderResultField();
            this.renderResultsDisplay();
        },
        flashCopyLabel : function(){
            var _this = this;
            this.form.querySelector('.results_copied_notification').style.display = 'block';
            setTimeout(function(){_this.form.querySelector('.results_copied_notification').style.display = 'none';},1000);
        },
        openerHasPipe : function (){
            return (typeof(window.opener.ifsoLocGenPipe)!=='undefined' && typeof (window.opener.ifsoLocGenPipe.accept)==='function');
        }
    };

</script>