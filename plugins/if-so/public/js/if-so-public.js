var ifso_viewed_triggers = {};

var ifso_scope = {
	evalScriptsFromHTML :function(html){
		if(typeof(jQuery) !== 'function')
			return false;

		var el = document.createElement('div');
		jQuery(el).append(html);
		var scripts = '';
		jQuery(el).find('script').each(function(index){
			scripts += this.innerHTML;
		});
		eval.call(null, scripts);	//Indirect eval call - execute in global scope - (1,eval)(scripts);
	},
	createCookie : function(name, value, days) {
		var expires;
		if (days) {
			var date = new Date();
			date.setTime(date.getTime()+(days*24*60*60*1000));
			expires = "; expires="+date.toGMTString();
		}
		else {
			expires = "";
		}
		document.cookie = name+"="+value+expires+"; path=/";
	},
	getCookie : function(c_name) {
		var c_value = document.cookie,
			c_start = c_value.indexOf(" " + c_name + "=");
		if (c_start == -1) c_start = c_value.indexOf(c_name + "=");
		if (c_start == -1) {
			c_value = null;
		} else {
			c_start = c_value.indexOf("=", c_start) + 1;
			var c_end = c_value.indexOf(";", c_start);
			if (c_end == -1) {
				c_end = c_value.length;
			}
			c_value = unescape(c_value.substring(c_start, c_end));
		}
		return c_value;
	},
};

var ajax_triggers_loaded = document.createEvent('Event');
ajax_triggers_loaded.initEvent('ifso_ajax_triggers_loaded', true, true);
ifso_scope.DispatchTriggersLoaded  =  function () {document.dispatchEvent(ajax_triggers_loaded);ifso_scope.DispatchAjaxContentLoaded();};

var ajax_conditions_loaded = document.createEvent('Event');
ajax_conditions_loaded.initEvent('ifso_ajax_conditions_loaded', true, true);
ifso_scope.DispatchStandaloneCondLoaded  =  function () {document.dispatchEvent(ajax_conditions_loaded);ifso_scope.DispatchAjaxContentLoaded();};

var ajax_content_loaded = document.createEvent('Event');
ajax_content_loaded.initEvent('ifso_ajax_content_loaded', true, true);
ifso_scope.DispatchAjaxContentLoaded  =  function () {document.dispatchEvent(ajax_content_loaded);};


(function( $ ) {
	'use strict';

	$(document).ready(function () {
		function if_so_public(){
			for(var ifso_scope_member_name in ifso_scope ){
				if(typeof(ifso_scope_member_name) !=='undefined' && typeof(ifso_scope[ifso_scope_member_name])!=='undefined'){
					this[ifso_scope_member_name] = ifso_scope[ifso_scope_member_name];
				}
			}
		}

		if_so_public.prototype = {
			lookForLoadLaterTriggers : function(defer=false,ignoreAtts=true){
				var _this = this;
				var ret = [];
				var tags = $('IfSoTrigger');
				tags.each(function(index,el){
					var tid = el.getAttribute('tid');
					var ajaxAttsStr = JSON.stringify(_this.lookForAttrsForAjax(el));
					var deferAttr =  el.getAttribute('defer');
					if((deferAttr===null && !defer) ||  (deferAttr!==null && defer)){
						if(null!== tid){
							if($.inArray(tid,ret)<0 && (ignoreAtts || ajaxAttsStr==='{}')){
								ret.push(tid);
							}
							else if(!ignoreAtts && ajaxAttsStr!=='{}' && $.inArray(ajaxAttsStr,ret)<0){
								ret.push({...JSON.parse(ajaxAttsStr), 'id':tid});
							}
						}
					}
				});
				return ret;
			},

			replaceLoadLaterTriggers : function(defer = false){
				var toReplace = this.lookForLoadLaterTriggers(defer,false);
				var _this = this;
				if (toReplace.length>0){
					this.sendAjaxReq('render_ifso_shortcodes',{triggers:toReplace,pageload_referrer:referrer_for_pageload},function(ret){	//Referrer from if-so public
						if(ret && ret!== null){
							try{
								var data = JSON.parse(ret);
								$.each(data, function(tid,tval){
									var tagsInDom = _this.lookForTriggerTags(tid);
									tagsInDom.each(function(i,tag){
										tag.outerHTML = tval;
										_this.evalScriptsFromHTML(tval);
									})
								});
								ifso_scope.DispatchTriggersLoaded();
							}
							catch(e){
								console.error('Error fetching if-so triggers!');
								console.error(e);
							}
						}
					})
				}
			},

			lookForStandaloneConditions : function(tags){
				var ret = [];
				tags.each(function(index,el){
					var _content = el.getAttribute('content');
					var _default = el.getAttribute('default');
					var _rule = el.getAttribute('rule');
					var _hash = el.getAttribute('hash');
					var _data = {'content':_content,'default':_default,'rule':_rule,'hash':_hash};
					ret.push(_data);
				});
				return ret;
			},

			replaceStandaloneConditions : function(){
				var elements = $('IfSoCondition');
				var toReplace = this.lookForStandaloneConditions(elements);
				var _this = this;
				if(toReplace.length>0){
					this.sendAjaxReq('render_ifso_shortcodes',{triggers:JSON.stringify(toReplace),pageload_referrer:referrer_for_pageload,is_standalone_condition:true},function(ret){	//Referrer from if-so public
						try{
							var data = JSON.parse(ret);
							$.each(data, function(id,val){
								elements[id].outerHTML = val;
								_this.evalScriptsFromHTML(val);
							});
							ifso_scope.DispatchStandaloneCondLoaded();
						}
						catch(e){
							console.error('Error fetching if-so standalone conditions!');
							console.error(e);
						}
					});
				}
			},

			lookForDKI : function(tags){
				var ret = [];
				tags.each(function(index,el){
					var _dkiAtts = el.getAttribute('dkiAtts');
					ret.push(_dkiAtts);
				});
				return ret;
			},

			replaceDkiElements : function(){
				var toReplace = this.lookForDKI($('IfSoDKI'));
				if(toReplace.length>0){
					this.sendAjaxReq('render_ifso_shortcodes',{triggers:JSON.stringify(toReplace),pageload_referrer:referrer_for_pageload,is_dki:true},function(ret){	//Referrer from if-so public
						try{
							var data = JSON.parse(ret);
							$.each(data, function(id,val){
								$('IfSoDKI')[id].outerHTML = val;
							});
						}
						catch(e){
							console.error('Error fetching if-so DKI!');
							console.error(e);
						}
					});
				}
			},

			sendAjaxReq : function(action, data, cb) {
				data['action'] = action;
				data['nonce'] = nonce;
				//data['page_url'] = ifso_page_url;
				data['page_url'] = window.location.href;

				$.post(ajaxurl, data, function(response) {
					if (cb)
						cb(response);
				});
			},

			deleteCookie : function( name ) {
				document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT;path=/;";
			},

			initialize_last_viewed_globals : function(){
				var cookie = this.getCookie('ifso_last_viewed');
				try{
					var cookieObj = JSON.parse(cookie);
					ifso_viewed_triggers = cookieObj;
				}
				catch(err){console.log('ERR! ' + err)}
			},

			ifso_analytics_do_conversion : function(conversions){
				var params = {an_action:'doConversion',postid:666, viewed_triggers:JSON.stringify(ifso_viewed_triggers),conversions:JSON.stringify(conversions)};
				this.sendAjaxReq('ifso_analytics_req',params, function(){});
			},

			ifso_google_analytcis_events_run : function(){
				if(!isAnalyticsOn || typeof(gtag)==='undefined' || $('ifsoTriggerAnalyticsEvent').length<=0)
					return;
				$.each($('ifsoTriggerAnalyticsEvent'),function(key,value){
					var event_name = value.getAttribute('event_name');
					try{var el_event_data = JSON.parse(value.getAttribute('event_data'));}
					catch (err){el_event_data = {};}
					var event_data = null;
					if(event_name==='ifso-trigger-viewed'){
						var ev_version_name = typeof(el_event_data['version_name'])!=='undefined' && el_event_data['version_name']!==null  ? el_event_data['version_name'] : '';
						event_data = {ifso_trigger_id:el_event_data['trigger'],ifso_trigger_version:el_event_data['version'],event_label:'ifso-content-viewed'} ;
						if(ev_version_name!=='')
							event_data['ifso_trigger_version_name']  = ev_version_name;
						event_data['event_category'] = 'if-so';
					}
					if(event_name==='custom'){
						var event_data = {};
						$.each(el_event_data,function(e_attr,e_val){
							if(e_attr==='ga4_event_type')
								event_name = e_val;
							else
								event_data[e_attr] = e_val;
						});
					}
					if(event_data!==null)
						gtag('event', event_name,event_data);
					value.remove();
				});
			},
			lookForAttrsForAjax : function (elem){
				var ret = {};
				$.each(ifso_attrs_for_ajax,function (key,value){
					if(elem.getAttribute(value)!==null)
						ret[value] = elem.getAttribute(value);
				});
				return ret;
			},
			lookForTriggerTags : function(trigger){
				if(isNaN(trigger)){
					var trigger_atts = JSON.parse(trigger);
					var tagsSelector = 'IfSoTrigger[tid="'+trigger_atts.id+'"]';
					Object.keys(trigger_atts).forEach(function (val){if(val!=='id') tagsSelector += '[' + val + '="' + trigger_atts[val] + '"]'});
				}
				else{
					var tagsSelector = 'IfSoTrigger[tid="'+trigger+'"]';
					ifso_attrs_for_ajax.forEach(function (val){tagsSelector += ':not([' + val + '])';})
				}
				return $(tagsSelector);
			}
		};

		var ifso_public_instance = new if_so_public();

		ifso_public_instance.replaceLoadLaterTriggers(false);
		var replace_defered  = function(){
			ifso_public_instance.replaceLoadLaterTriggers(true);
			$(document).unbind("click keydown keyup mousemove",replace_defered);
		};
		$(document).on('click keydown keyup mousemove',replace_defered);
		ifso_public_instance.replaceStandaloneConditions();
		$(document).on('ifso_ajax_conditions_loaded',ifso_public_instance.replaceStandaloneConditions.bind(ifso_public_instance));
		ifso_public_instance.replaceDkiElements();

		ifso_public_instance.ifso_google_analytcis_events_run();
		$(document).on('ifso_ajax_content_loaded',ifso_public_instance.ifso_google_analytcis_events_run);


		if(isPageVisitedOn || isVisitCountEnabled){	//Passed form if-so public
			ifso_public_instance.sendAjaxReq('ifso_add_page_visit', {ifso_count_visit:parseInt(isVisitCountEnabled),isfo_save_page_visit:parseInt(isPageVisitedOn)}, function(response){});
		}

		if(isAnalyticsOn){	//Passed from if-so public
			ifso_public_instance.initialize_last_viewed_globals();
			if(ifso_public_instance.getCookie('ifso_viewing_triggers')) ifso_public_instance.sendAjaxReq('ifso_analytics_req',{postid:666,an_action:'ajaxViews',data:ifso_public_instance.getCookie('ifso_viewing_triggers') });

			if($('.ifso-conversion-complete').length>0){
				var conversions = [];
				$.each($('.ifso-conversion-complete'),function(key,value){
					if(value.getAttribute('completed')!==null) return;
					var allowed_attr = value.getAttribute('allowed_triggers');
					var disallowed_attr = value.getAttribute('disallowed_triggers');
					var once_per_attr = value.getAttribute('once_per_time');
					var name_attr = value.getAttribute('ifso_name');
					var conversion = {allowed:[],disallowed:[]};
					if(allowed_attr!=null && allowed_attr != 'all')
						conversion.allowed = allowed_attr.split(',');
					if(disallowed_attr!=null)
						conversion.disallowed = disallowed_attr.split(',');
					if(once_per_attr!==null && name_attr!==null){
						conversion.once_per_time = once_per_attr;
						conversion.name = name_attr
					}
					conversions.push(conversion);
					value.setAttribute('completed',1);
				});
				if(conversions.length>0) ifso_public_instance.ifso_analytics_do_conversion(conversions);
			}
		}


		//Bounce mechanism - not in use for now
		/*if(getCookie('ifso_bounce')){
            sendAjaxReq('ifso_analytics_req',{an_action:'decrementField',postid:ifso_last_viewed_trigger.triggerid, versionid:ifso_last_viewed_trigger.versionid, field:'bounce'}, function(){
                deleteCookie('ifso_bounce')
            });
        }
		window.addEventListener('beforeunload',function(e){
		    if(!getCookie('ifso_bounce')){
				createCookie('ifso_bounce');
                sendAjaxReq('ifso_analytics_req',{an_action:'incrementField',postid:ifso_last_viewed_trigger.triggerid, versionid:ifso_last_viewed_trigger.versionid, field:'bounce'}, function(){});
            }

		});*/
	});
})( jQuery );

