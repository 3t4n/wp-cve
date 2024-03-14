// JavaScript Document
//by BMo 2013
//orientiert an http://tikku.com/scripts/websites/tikku/css_inline_transformer_simplified.js // MIT License
(function($) {
	$.fn.inlineCSS = function(target){//Target gibt an, welche css dafür genommen werden. Target enthält ein URL teil, der in den URLs der CSS im href vorkommen muss. Diese Funktion holt diese CSS und macht deren Inhalt inline. Wichtig ist, dass diese CSS gut geschrieben sind. Also mehrere Regeln durch Komma und Leerzeichen getrennt. Kein Umbruch in Regeln. Kein @Charste und so sachen.
		
		this.each(function () {
			$(this).getCSSStyles(target);
		});
	};
	
	$.fn.getCSSStyles = function(target){
		var sheets = document.styleSheets; // typ sheetlist
		var element = $(this);
		var styles = [];
		
		//erzeuge container, damit nur hierrein Styles angewendet werden
		element.wrap('<div class="inline_container"></div');
		
		for(var i in sheets) {//alle sheets durchgehen.
			var stylesheet = sheets[i]; 
			var check=false;
			if(typeof(stylesheet.href)!==undefined){
				if(stylesheet.href!=null&&stylesheet.href.indexOf(target)>-1)//nur relevante stylesheets nehmen, die die target url enthalten
					check=true;
			}
			if(typeof(stylesheet.src)!==undefined){//glaub für ie
				if(stylesheet.src!=null&&stylesheet.src.indexOf(target)>-1)//nur relevante stylesheets nehmen, die die target url enthalten
					check=true;
			}
			if(check){
			//	console.log(stylesheet);
				for(r in stylesheet.cssRules){//regeln
					try{
						var rule = stylesheet.cssRules[r];//regel
						if(!isNaN(rule))break; // make sure the rule exists
						//console.log(rule);
						var $destObj = element.parent(".inline_container").find(rule.selectorText);//passendes html-obj zu regel finden. Muss eine Ebene darüber beginnen um sich selbst zu finden. -> Fange eine Ebene darüber an.
						var cssobj = rule.cssText.replace(rule.selectorText, '');//hole das CSS das angewendet werden soll
						cssobj = cssobj.replace('{','').replace('}',''); // clean up the { and }'s
						var styles = cssobj.split(";"); // separate each 
						$destObj.css(inlineCSSRuleObj(styles));//schriebe es inline
					//  console.log($destObj);
					//	console.log(inlineCSSRuleObj(styles));
					} catch (e) { console.log("error in "+stylesheet.cssRules[r]+" Error: "+e);}
				}
			}
		}
		
		//lösche den inline_container
		element.unwrap();
	};
	
	
	function inlineCSSRuleObj(stylesArray) {//Macht die einzelnen Regelelemente Inline
    	var cssObj = {};
	   // console.log(stylesArray);
    	for(var i =0; i<stylesArray.length;i++){
	 		var _s = stylesArray[i];
			if(typeof _s === 'string'){
    			var S = _s.split(":");
	    		if(S[0].trim()==""||S[1].trim()=="")continue;
				var cssKey = S.shift().trim();
	    		cssObj[cssKey] = S.join(":").trim();//nimm das erste als css key weg und baue evtl. weitere wieder mit : zusammen. Wichtig, falls in Background Url evtl ein : vorkommt 
	    		//prüfe ob url("../imgs...") drin ist und ersetze pfad
				if(cssObj[cssKey].trim().indexOf('url("../') >= 0){ cssObj[cssKey] = cssObj[cssKey].split('url("../').join('url("'+$.data(document.body,"BMo_Expo_Path")+'css/');
				}else if(cssObj[cssKey].trim().indexOf("url('../") >= 0){ cssObj[cssKey] = cssObj[cssKey].split("url('../").join("url('"+$.data(document.body,"BMo_Expo_Path")+"css/");
				}
			}
		}
		//console.log(cssObj);
    	return cssObj;
    }
	
})(jQuery);