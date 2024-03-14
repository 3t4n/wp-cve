(function( $ ) {
	window.parseXML = function parseXML(XML){
		var base = document.createElement('div');
		$(base).append(XML);
		// console.log(base.children);
		return parseElement(base);
	}

	function parseElement(DOM){
		if(DOM.children){
			if(DOM.children.length){
				var object = {};
				// console.log(DOM.children);
				var temp_arr = {};
				for(var propertyName in DOM.children){
					if(propertyName !== 'length' && propertyName !== 'item' && propertyName !== 'namedItem' ){
						temp_arr[DOM.children[propertyName].localName] = true;
					}
				}
				for(var propertyName in DOM.children){
					if(propertyName !== 'length' && propertyName !== 'item' && propertyName !== 'namedItem'){
						if(Object.keys(temp_arr).length == DOM.children.length){
							object[DOM.children[propertyName].localName] = parseElement(DOM.children[propertyName]);
						} else {
							if(object[DOM.children[propertyName].localName]===undefined){
								object[DOM.children[propertyName].localName] = [];
							}
							object[DOM.children[propertyName].localName][propertyName] = parseElement(DOM.children[propertyName]);
						}
					}
				}
				return object;
			} else {
				return DOM.innerHTML.replace('<!--[CDATA[','').replace(']]-->','');
			}
		}
	}
})( jQuery );