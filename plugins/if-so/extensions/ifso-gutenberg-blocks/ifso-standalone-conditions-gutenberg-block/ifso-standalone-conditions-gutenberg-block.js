( function( blocks, editor,  element ) {
    var el = wp.element.createElement;
    var InspectorControls = editor.InspectorControls;
    var PanelRow = wp.components.PanelRow;
    var PanelBody = wp.components.PanelBody;

    window.ifsoLocGenPipe = {
        changing_input : null,
        open: function (url,el) {
            var input_name = jQuery(el).closest('label').attr('for');
            if(typeof (input_name)!=='undefined'){
                var inp =jQuery(el).closest('form').find('[name="' + input_name + '"]');
                this.changing_input = inp[0];
            }
            window.open(url + "&ui_type=adder", 'newwindow', 'width=800,height=600');
        },
        accept: function (data) {
            if(typeof(this.changing_input)!=='undefined' && this.changing_input!==null){
                this.changing_input.focus();
                this.changing_input.value = data;
                this.changing_input.dispatchEvent(new KeyboardEvent('keyup', {code: 'Enter', key: 'Enter', charCode: 13, keyCode: 13, view: window, bubbles: true}));
                this.changing_input.blur();
            }
        }
    }

    var multibox = function(){
        this.data_separator = '!!';
        this.version_separator = '^^';
        this.geo_symbols = ['CITY','COUNTRY','STATE','CONTINENT','TIMEZONE'];
        this.symInputKeyupCallback = this.symInputKeyup.bind(this);
        this.deleteVersionButtonPressedCallback = this.deleteVersionButtonPressed.bind(this);
        this.country_cache = {};
    };
    multibox.prototype = {
        getVersions : function(data=''){
            return (data==='') ? [] : data.split(this.version_separator);
        },
        addVersion : function (data,toAdd){
            if(!data.includes(toAdd)){
                data += (data!=='') ? this.version_separator : '';
                data += toAdd;
                return data;
            }
            return data;
        },
        removeVersion : function (data,removeId){
            var versions = this.getVersions(data);
            versions.splice(removeId,1);
            return versions.join(this.version_separator);
        },
        createNewLocation : function (locationType, behindSceneLocationData, visualLocationData) {
            var data = [locationType, visualLocationData, behindSceneLocationData];
            return data.join(this.data_separator);
        },
        symInputKeyup : function (event,props,forceaAllow=false){
            if(event.which===13 || forceaAllow){               //enter was pressed
                var _this = this;
                var focused_input = event.target;
                if((focused_input.tagName!=='input' && focused_input.getAttribute('type')!=='text') && !forceaAllow) return false;    //Only for text inputs
                var newVal = focused_input.value;
                var symbol = focused_input.getAttribute('symbol');
                var interacted_form  = jQuery(focused_input).closest('form');
                var data_input = interacted_form.find('[multidata]')[0];
                var data_input_name = data_input.name;
                var versionData;
                if(symbol && newVal!==''){
                    var newVals = this.parseInputValue(newVal,symbol);
                    var current_data_value = props.attributes.ifso_condition_rules[data_input_name] || '';
                    newVals.forEach(function(newVal){
                        if(typeof(newVal['loc_type'])!=='undefined' && typeof(newVal['loc_val'])!=='undefined'){
                            versionData = _this.createVersionData(newVal['loc_type'],newVal['loc_val'],interacted_form);
                            current_data_value = _this.addVersion(current_data_value,versionData);
                            if(symbol==='COUNTRY'){
                                _this.country_cache[newVal['loc_val']] = focused_input.querySelector('option[value="'+ newVal['loc_val'] +'"]').innerHTML;
                            }
                        }
                    });
                    focused_input.value = '';
                    var rules_copy = Object.assign({},props.attributes.ifso_condition_rules);
                    rules_copy[focused_input.getAttribute('name')] = '';
                    rules_copy[data_input_name] = current_data_value;
                    data_input.value = current_data_value;
                    props.setAttributes({ifso_condition_rules:rules_copy});
                }
            }
        },
        createVersionData : function(symbol,newVal,interacted_form){
            var versionData;
            var _this = this;
            if(_this.geo_symbols.includes(symbol)){
                versionData = _this.createNewLocation(symbol,newVal,newVal);
            }
            else{
                var symbol_inputs = interacted_form.find('[symbol='+symbol+']');
                versionData = _this.createNewLocation(symbol,jQuery(symbol_inputs[0]).val(),jQuery(symbol_inputs[1]).val());
            }
            return versionData;
        },
        parseInputValue : function(val,symbol=''){
            try{return JSON.parse(val);}
            catch(e){return [{loc_type:symbol,loc_val:val}];}
        },
        deleteVersionButtonPressed : function (event,props,field){
            if(event.clientX===0 && event.clientY===0) return;     //Make sure it was actually called by click on delete(not while rendering still)
            var newData = this.removeVersion(props.attributes.ifso_condition_rules[field],jQuery(event.target).closest('[version_number]').attr('version_number'));
            var new_rules = {...props.attributes.ifso_condition_rules};
            new_rules[field] = newData;
            props.setAttributes({ifso_condition_rules:new_rules});
        },
        searchDRModelForCountryName : function (countryCode){
            for (var i = 0; i < data_rules_model['Geolocation']['fields']['geolocation_country_input']['options'].length; i++){
                var countryOpt = data_rules_model['Geolocation']['fields']['geolocation_country_input']['options'][i];
                if(countryOpt.value === countryCode){
                    return countryOpt.display_value;
                }
            }
        },
        renderMultiboxVersions : function(props,field){
            var _this = this;
            var data = props.attributes.ifso_condition_rules[field];
            var versions = this.getVersions(data);
            var ret;
            if(versions.length!==0){
                ret = [];
                for(var i=0;i<versions.length;i++){
                    var v_data = versions[i].split(this.data_separator);
                    if(typeof(v_data[1])!=='undefined') {
                        var label = '';
                        var display_value = v_data[1];
                        switch (props.attributes.ifso_condition_type) {
                            case 'Geolocation':
                                label = v_data[0];
                                if(label==='COUNTRY'){
                                    if(typeof(this.country_cache[display_value])==='undefined')
                                        this.country_cache[display_value] = this.searchDRModelForCountryName(display_value);
                                    if(typeof(this.country_cache[display_value])!=='undefined')
                                        display_value = this.country_cache[display_value];
                                }
                                break;
                            case 'PageVisit':
                                label = v_data[2];
                                break;
                        }
                        ret.push(el('div',{key:'multibox-version-'+i,className:'ifso-multibox-version',version_number:i}
                            ,el('div',{key:'content-label',className:'content-label'}, [label.toLowerCase() + '\u00A0:\u00A0',el('span',{key:'content-display'},display_value)])
                            ,el('button',{key:'delete-btn',className:'ifso-mb-del',onClick:function(e){_this.deleteVersionButtonPressedCallback(e,props,field)}},'X')
                        ));
                    }
                }
            }
            else{
                ret = el('span',{className:'no-versions-text'},'No targets selected')
            }
            return ret;
        },
    };


    var iconEl = el('svg', {width:20, height:20,  viewBox: '0 0 1080 1080', className:'ifso-block-icon' },[ el('path', { key:'icon-path-1', d: "M418.9,499.8c-32.2,0-61.5,0-92.2,0c0-46.7,0-92.6,0-140c29.8,0,59.6,0,91.9,0c0-7.6-0.7-14,0.1-20.1c4.6-32.2,5.5-65.6,15.3-96.2c19.4-60.5,67.6-90.1,127.1-102.1c67.4-13.6,135.3-6.5,204.2-3c0,51.9,0,102.8,0,155.4c-15.7-1.8-30.7-3.7-45.6-5.2c-7.5-0.8-15.2-1.7-22.7-1.2c-43.8,3.2-61,25.8-53.6,71.6c38.1,0,76.5,0,116.2,0c0,47,0,92.5,0,139.9c-37.1,0-74.3,0-113.2,0c0,152.1,0,302.3,0,453.7c-76.3,0-151,0-227.5,0C418.9,802.1,418.9,652,418.9,499.8z", className:'st0'})
        ,el('path', { key:'icon-path-2', d: "M0,134.5c83.7,0,166.3,0,250,0c0,272.8,0,544.9,0,818.3c-82.8,0-165.8,0-250,0C0,680.8,0,408.3,0,134.5z", className:'st0'}),
        el('path', {key:'icon-path-3s',style: {fill:'#FD5B56'},  d: "M893.5,392.3c62.2,44.4,123.4,88.1,185.8,132.7c-62.2,44.4-123.3,88-185.8,132.7C893.5,568.8,893.5,481.5,893.5,392.3z", className:'st1'})]);

    var data_rules_model = (typeof(data_rules_model_json)==='string') ? JSON.parse(data_rules_model_json) : data_rules_model_json;
    var license_status_object = (typeof(license_status)==='string') ? JSON.parse(license_status) : license_status;
    var pages_links = (typeof(ifso_pages_links)==='string') ? JSON.parse(ifso_pages_links) : ifso_pages_links;
    var ajax_loaders_names = (typeof(ajax_loaders_json)==='string') ? JSON.parse(ajax_loaders_json) : ajax_loaders_json;
    var ajax_loaders_names_opts = function(){var ret = [];Object.keys(ajax_loaders_names).forEach(function (opt){ret.push({'value':opt,'display_value':ajax_loaders_names[opt]});});return ret;}();
    var multibox_control = new multibox();
    console.log(data_rules_model);

    function get_form_data(form){
        var arr = jQuery(form).serializeArray();
        var ret ={};
        arr.forEach(function(field){
            ret[field.name] = field.value;
        });
        return ret;
    }

    function save_form_data(form,props){
        var interacted_form_data = get_form_data(form);
        props.setAttributes({ifso_condition_rules:interacted_form_data});
    }

    function create_sidebar_condition_ui_elements(props){
        var title = null;

        var trigger_type_select = el('select',{key:'trigger-type-select',value:props.attributes.ifso_condition_type,onChange:function(e){
                hard_reset_form(e.target.parentElement.parentElement.querySelector('.ifso-standalone-condition-form-wrap [formtype="'+props.attributes.ifso_condition_type+'"]'));
                var selected_value = e.target.selectedOptions[0].value;
                props.setAttributes({ifso_condition_type : selected_value, ifso_condition_rules : get_form_data(e.target.parentElement.parentElement.querySelector('.ifso-standalone-condition-form-wrap [formtype="'+selected_value+'"]'))});
            }},
            function(){
                var ret = [el('option',{key:'blank-type', value:''},null,'Select a Condition')];
                Object.keys(data_rules_model).forEach(function(type){if(type==='general' || type==='AB-Testing') return;var not_allowed_marker = (!license_status_object['is_license_valid'] && !in_array(license_status_object['free_conditions'],type)) ? '*' : '';ret.push(el('option',{key:type, value:type},data_rules_model[type]['name']+not_allowed_marker))});
                return ret;
            }());
        var trigger_type_wrap = el('div',{key:'trigger-type-wrap',className:'ifso-standalone-condition-trigger-type-wrap'},[el('label',{key:'trigger-type-select-label'},null,'Only show this block if: '),trigger_type_select]);

        var trigger_rules_form = el('div',{key:'trigger-rules-form',className:'ifso-standalone-condition-form-wrap'},create_data_rules_forms(data_rules_model,props));

        var default_content_wrap = el('div',{key:'default-content-wrap',className:'default-content-wrap'},[el('input',{key:'checkbox',type:'checkbox',className:'default-content-exists-input input-control',checked:props.attributes.ifso_default_exists,onChange:function(e){props.setAttributes({ifso_default_exists: e.target.checked})}}),
            el('label',{key:'label',className:(props.attributes.ifso_default_exists) ? '' : 'ifso-gray'},null,'Default Content:'), el(wp.blockEditor.RichText,{key:'content',value: props.attributes.ifso_default_content, className:((props.attributes.ifso_default_exists) ? '' : 'nodisplay ') + 'default-content-input block-editor-plain-text input-control',placeholder:'Type here (HTML)',onChange:function(e){props.setAttributes({ifso_default_content : e});}})
        ]);

        var aud_add_rm_wrap = el('div',{key:'audience-wrap',className:'audiences-addrm-wrap'},[el('input',{key:'checkbox',type:'checkbox',className:'audiences-addrm-exists-input',checked:!(is_empty(props.attributes.ifso_aud_addrm)),onChange:function(e){var toSet = (e.target.checked) ? {add:[],rm:[]} : {}; props.setAttributes({ifso_aud_addrm : toSet})} }),
        el('label',{key:'label',className:(is_empty(props.attributes.ifso_aud_addrm)) ? 'ifso-gray' : ''},null,'Audiences'),create_audience_addrm_ui(props)]);

        var render_with_ajax_wrap = el('div',{key:'render-with-ajax-ui-wrap',className:'ifso-render-with-ajax-wrap'},[el('input',{key:'checkbox',type:'checkbox',className:'ifso-render-with-ajax-input input-control',checked:props.attributes.ifso_render_with_ajax,onChange:function(e){props.setAttributes({ifso_render_with_ajax: e.target.checked,ajax_loader_type: ''});}}),el('label',{key:'label',className:(props.attributes.ifso_render_with_ajax) ? '' : 'ifso-gray'},null,'Load with Ajax')]);

        var ajax_loader_wrap = props.attributes.ifso_render_with_ajax ? el('div',{key:'ajax-loader-wrap',className:'ifso-ajax-loader-type-wrap'},[el('label',{key:'label'},null,'Loader type'),el('select',{key:'select',className:'loader-type-select input-control',value:props.attributes.ajax_loader_type,onChange:function(e){props.setAttributes({ajax_loader_type: e.target.value});}},null,create_ifso_ui_select_options(ajax_loaders_names_opts,'ajax-ui'))]) : null

        var base_div = el('div',{key:'ifso-widget-ui-base-div',className:'custom-condition-base-div'},[title,trigger_type_wrap,trigger_rules_form,default_content_wrap,aud_add_rm_wrap,render_with_ajax_wrap,ajax_loader_wrap]);

        return base_div;
    }

    function create_data_rules_forms(model,props){
        var ret = [];
        if(model && typeof(model)==='object'){
            Object.keys(model).forEach(function(condition){
                var form = create_data_rules_form(model,condition,props);
                ret.push(form);
            });
        }
        return ret;
    }

    function create_data_rules_form(model,condition,props){
        if(model && typeof(model)==='object' && condition){
            var form_elements = [];
            form_elements.push(create_license_condition_message(condition));
            var selected_form = (condition===props.attributes.ifso_condition_type);
            var form_classname = function (){var ret = 'ifso-standalone-condition-form';if(selected_form)ret+= ' selected';return ret;};
            var contains_subgroups = false;
            var switcher_value = null;
            if(model[condition]){
                Object.keys(model[condition]['fields']).forEach(function(index){
                    var elObj = model[condition]['fields'][index];
                    if (elObj.subgroup) contains_subgroups = true;
                    if (elObj.is_switcher) switcher_value = props.attributes.ifso_condition_rules[elObj['name']];
                });
                Object.keys(model[condition]['fields']).forEach(function(index){
                    var created_element = createElementFromModel(model[condition]['fields'][index],props,selected_form,switcher_value);
                    form_elements.push(created_element);
                });
            }
            return el('form',{key:'form-'+condition,className:form_classname(),formtype:condition,onSubmit:function(e){e.preventDefault();}},form_elements);
        }
    }

    function createElementFromModel(elObj,props,fillWitData=false,selectedSubgroup=null){
        if(elObj && typeof(elObj)==='object'){
            var ret;
            var element;
            var label = null;
            var saveInteractedFormData = function(e){
                if(elObj['type']==='select' && elObj['symbol'] && elObj['extraClasses']!=='nosubmit') multibox_control.symInputKeyupCallback(e,props,true);
                var interacted_form = jQuery(e.target).closest('form');
                save_form_data(interacted_form,props);
            };
            var makeElementValue = function(){
                if(fillWitData && props.attributes.ifso_condition_rules[elObj.name]){
                    if(elObj['type']!=='checkbox')
                        return props.attributes.ifso_condition_rules[elObj.name];
                    if(elObj['type']==='checkbox')  //this sets "checked" instead of the value
                        return ( 'on' === props.attributes.ifso_condition_rules[elObj.name]);
                }
            }

            var base_options = {name:elObj['name'],className:elObj['extraClasses'],key:elObj['name'],value:makeElementValue(),onChange:saveInteractedFormData};
            if(elObj['symbol']){
                if( elObj['type']!=='select') {
                    base_options.onKeyUp = function (e) {multibox_control.symInputKeyupCallback(e, props);}
                    base_options.onBlur = function (e) {multibox_control.symInputKeyupCallback(e, props, true);}
                }
                base_options = {...base_options,symbol:elObj['symbol'],title:'Press Enter to insert an entry'};
            }

            if(elObj['type'] === 'noticebox'){
                return create_noticebox(elObj,selectedSubgroup);
            }

            if(elObj['type']==='text'){
                element = el('input',{...base_options, type:'text',required:elObj['required']})
            }

            if(elObj['type']==='select'){
                var select_options = create_ifso_ui_select_options(elObj['options'],elObj['name']);
                element = el('select',{...base_options,required:elObj['required']},select_options);
            }

            if(elObj['type']==='checkbox'){
                element= el('input',{...base_options,type:'checkbox',value:'on',checked:makeElementValue()});
            }

            if(in_array(['text','select','checkbox'],elObj['type']))
                label = el('label',{key:elObj['name']+'-label',htmlFor:elObj['name'],dangerouslySetInnerHTML: {__html: elObj['prettyName']}});

            if(elObj['type']==='multi'){
                var multibox_versions = !fillWitData ? null : multibox_control.renderMultiboxVersions(props,elObj['name']);
                var multibox_description = !fillWitData ? null : data_rules_model[props.attributes.ifso_condition_type]['multibox']['description'];
                element = el('div',{key:elObj['name'],className:'ifso-multibox-wrap ' + elObj['extraClasses'],symbol:elObj['symbol'] ? elObj['symbol'] : ''},[el('input',{type:'text', name:elObj['name'], hidden:true, multidata:'true', value:function(){return props.attributes.ifso_condition_rules[elObj['name']]; }() ,key:elObj['name']+'-mbox-input', onChange:saveInteractedFormData}),
                    el('div',{className:'ifso-multibox-wrapper',key:elObj['name']+'-mbox-wrapper'},[
                        el('div',{key:elObj['name']+'-mbox-descr',className:'ifso-multibox-description',dangerouslySetInnerHTML: {__html: multibox_description}}),
                        el('div',{key:elObj['name']+'-mbox-versions',className:'ifso-multibox-versions'},multibox_versions)
                    ])]);
            }

            var elsOrder = elObj['type']==='checkbox' ? [element,label] : [label,element];
            var extraClasses ='';
            var extraOptions = {};
            extraClasses += elObj['type']==='checkbox' ? ' ifso-widget-checkbox' : '';
            if(elObj['is_switcher']){
                extraClasses += ' is_switcher';
                extraOptions = {is_switcher:+true,switcher_init_value:element.props.value};
            }
            if(elObj['subgroup'] && selectedSubgroup!==null && elObj['subgroup']!==selectedSubgroup)
                extraClasses += ' nodisplay';

            ret = el('div',{...extraOptions, key:'cond-ctrl-'+elObj['name'],className:'ifso-standalone-condition-control-wrap'+extraClasses,subgroup:elObj['subgroup']},null,elsOrder);

            return ret;
        }
    }

    function create_ifso_ui_select_options(optionsArr,context){
        var ret = [];
        if(optionsArr && optionsArr.length>0){
            optionsArr.forEach(function(opt){
                ret.push(el('option',{key:context+'-'+opt['value'],value:opt['value']},null,opt['display_value']));
            })
        }
        return ret;
    }

    function create_license_condition_message(condition){
        var ret = null;
        var get_license_url = 'https://www.if-so.com/plans/?utm_source=Plugin&utm_medium=direct&utm_campaign=getFree&utm_term=lockedConditon&utm_content=Gutenberg';
        if(!license_status_object['is_license_valid'] && !in_array(license_status_object['free_conditions'],condition)){
            ret = el('div',{key:'license-error-message',error_message:'1',className:'ifso-stantalone-error-message'},null,[
                el('a',{href:get_license_url, target:'_blank'},'This condition is only available upon license activation. Click here to get a free license if you do not have one.')
            ]);
        }
        return ret;
    }

    function hard_reset_form(form){
        if(form===null) return;
        form.reset();
        jQuery(form).find(':input').each(function() {
            switch(this.type){
                case 'textarea':
                case 'text':
                    jQuery(this).val('');
            }
        });
    }

    function in_array(array,member){
        if(array.indexOf(member)===-1){
            return false;
        }
        return true;
    }

    function is_empty(obj) {
        for(var prop in obj) {
            if(obj.hasOwnProperty(prop)) {
                return false;
            }
        }
        return JSON.stringify(obj) === JSON.stringify({});
    }

    function create_noticebox(elObj,selectedSubgroup=null){
        var subgroupClassName = selectedSubgroup!==null && selectedSubgroup!==elObj['subgroup'] ? 'nodisplay' : '';
        var closingX = (elObj['closeable']) ? el('span',{key:'closingX',className:'closingX',onClick:function(e){e.target.parentElement.classList.add('nodisplay');}},'X') : null;
        return el('div',{key:'noticebox-'+elObj['name'],className:'ifso-standalone-condition-noticebox ' + subgroupClassName,subgroup:elObj['subgroup'],style:{color:elObj['color'],backgroundColor:elObj['bgcolor'],border:'1px solid' +elObj['color']}}
            ,[el('p',{key:'notice-content',className:'notice-content',dangerouslySetInnerHTML:{__html:elObj['content']}}),closingX]);
    }

    function create_audience_addrm_ui(props){
        if(data_rules_model['Groups']['fields']['group-name']['options'] ){
            var groupsList = data_rules_model['Groups']['fields']['group-name']['options'];

            var updateStatus = function(e){
                var statusType = (e.target.name === 'ifso-aud-add') ? 'add' : 'rm' ;
                var otherStatusType = (statusType==='add') ? 'rm' : 'add';
                var statusUpdate = jQuery(e.target.parentElement).find('input').serializeArray().map(function(val){return val['value']});
                var full_status = {};
                full_status[statusType] = statusUpdate;
                full_status[otherStatusType] = props.attributes.ifso_aud_addrm[otherStatusType] || [];

                props.setAttributes({ifso_aud_addrm:full_status});
            };

            var create_addrm_form = function(type='add'){
                var checkSelects = groupsList.map( function(val){return [el('input',{key:'aud-'+val['value'],type:'checkbox',checked : (props.attributes.ifso_aud_addrm && props.attributes.ifso_aud_addrm !== null && !is_empty(props.attributes.ifso_aud_addrm) && Object.prototype.toString.call(props.attributes.ifso_aud_addrm[type])==='[object Array]' && in_array(props.attributes.ifso_aud_addrm[type],val['value'])), name:'ifso-aud-'+type,value:val['value'],onChange:updateStatus}),el('label',{key:'label'},null,val['display_value']),el('br',{key:'br'})] });
                return el('form',{key:type+'-aud-form',className:'ifso-aud-addrm-form'},checkSelects);
            };

            var aud_addrm_ui = el('div',{key:'aud-addrm-ui',className:'ifso-aud-addrm-ui-wrap '+((is_empty(props.attributes.ifso_aud_addrm)) ? 'nodisplay' : '')}, (groupsList && groupsList.length > 0) ?
                [el('p',{key:'instructions'},null,['Add or remove users from the following audiences when the version is displayed. ',el('a',{key:'link',href:'https://www.if-so.com/help/documentation/segments/?utm_source=Plugin&utm_medium=Micro&utm_campaign=GutenbergGroups', target:'_blank'},'Learn More')]),
                    el('h4',{key:'add-label',},null,'Add to audiences:'),create_addrm_form('add'), el('h4',{key:'remove-label',},null,'Remove from audiences:'),create_addrm_form('rm')]
                :
                el('p', {className: 'ifso-no-aud-error'}, 'You haven\'t created any audiences yet. ', el('a', {key:'create-aud-link',href: pages_links['gropus_page'], target: '_blank'}, 'Create an audience'), el('span', {key:'label'},' (and refresh).')));


            return aud_addrm_ui;
        }
    }


    wp.hooks.addFilter( 'blocks.registerBlockType', 'ifso/ifso-standalone-conditions-block-filter', function(opts,name){
        opts.attributes = {
            ...opts.attributes,
            ifso_condition_type:{
                type:'string',
                default:''
            },
            ifso_condition_rules:{
                type:'object',
                default:{}
            },
            ifso_default_exists:{
                type:'boolean',
                default:false
            },
            ifso_default_content:{
                type:'string',
                default:''
            },
            ifso_aud_addrm: {
                type:'object',
                default: {}
            },
            ifso_render_with_ajax:{
                type:'boolean',
                default:false
            },
            ajax_loader_type:{
                type:'string',
                default:'same-as-global'
            }
        }

        return opts;
    } );

    var withIfSoSidebar = wp.compose.createHigherOrderComponent( function( BlockEdit ) {
        return function( props ) {
            var isOpen = (props.attributes.ifso_condition_type !== '');
            if(!props.isSelected) return el(BlockEdit,props);
            return el(
                wp.element.Fragment,
                {},
                [
                    el(
                        BlockEdit,
                        {...props,key:'blockEdit'}
                    ),
                    el(wp.blockEditor.InspectorControls,
                        {key:'ifso-standalone-cond-widget'},
                        el(
                            PanelBody,
                            {className:'ifso-condition-sidebar-wrap',initialOpen:isOpen,title:el('span',{className:'title'},iconEl,'Dynamic Content')},
                            el(PanelRow,{},create_sidebar_condition_ui_elements(props))
                        )
                    )
                ]
            );
        };
    }, 'withIfSoSidebar' );

    var withIfsoBorder = wp.compose.createHigherOrderComponent( function( BlockListBlock ) {
        return function( props ) {
            var isOpen = (props.attributes.ifso_condition_type !== '');
            var newProps = {...props,className: (isOpen) ? 'ifso-widget-inuse' : ''};
            return el( BlockListBlock, newProps );
        }
    }, 'withIfsoBorder' );

    wp.hooks.addFilter( 'editor.BlockEdit', 'ifso/ifso-standalone-conditions-block-filter-edit',withIfSoSidebar);
    wp.hooks.addFilter( 'editor.BlockListBlock', 'ifso/ifso-standalone-conditions-label-edit',withIfsoBorder);

} )( window.wp.blocks, window.wp.editor, window.wp.element );