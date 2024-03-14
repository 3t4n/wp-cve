(function ($) {

    // Additional features that are needed
    //
    // allow specification of how attributes are shown

    // Data Model:
    //
    //   One of the following must be supplied
    //      xml                 - the XML object to be shown
    //      xmlString           - the XML string to be shown
    //
    //   Options:
    //      collapsedText       - the text shown when the node is collapsed. Defaults to '...'
    $.fn.simpleXML = function (options) {

        // This is the easiest way to have default options.
        var settings = $.extend({
            // These are the defaults.
            collapsedText: "...",
        }, options);

        if (settings.xml == undefined && settings.xmlString == undefined)
            throw "No XML to be displayed was supplied";

        if (settings.xml != undefined && settings.xmlString != undefined)
            throw "Only one of xml and xmlString may be supplied";

        var xml = settings.xml;
        if (xml == undefined)
            xml = $.parseXML(settings.xmlString);

        var result = this.each(function () {
            var wrapperNode = document.createElement("span");
            $(wrapperNode).addClass("simpleXML");

            showNode(wrapperNode, xml, settings);

            this.appendChild(wrapperNode);

            $(wrapperNode).find(".simpleXML-expanderHeader").click(function () {

                var expanderHeader = $(this).closest(".simpleXML-expanderHeader");

                var expander = expanderHeader.find(".simpleXML-expander");

                var content = expanderHeader.parent().find(".simpleXML-content").first();
                var collapsedText = expanderHeader.parent().children(".simpleXML-collapsedText").first();
                var closeExpander = expanderHeader.parent().children(".simpleXML-expanderClose").first();

                if (expander.hasClass("simpleXML-expander-expanded")) {
                    // Already Expanded, therefore collapse time...
                    expander.removeClass("simpleXML-expander-expanded").addClass("simpleXML-expander-collapsed");

                    collapsedText.attr("style", "display: inline;");
                    content.attr("style", "display: none;");
                    closeExpander.attr("style", "display: none");
                }
                else {
                    // Time to expand..
                    expander.addClass("simpleXML-expander-expanded").removeClass("simpleXML-expander-collapsed");
                    collapsedText.attr("style", "display: none;");
                    content.attr("style", "");
                    closeExpander.attr("style", "");
                }
            });
        });

        $(this).append(result);

        if(settings.callback != undefined){
            settings.callback();
        }
    };

    function showNode(parent, xml, settings) {
        if (xml.nodeType == 9) {
            for (var i = 0 ; i < xml.childNodes.length ; i++)
                showNode(parent, xml.childNodes[i], settings);

            return;
        }

        switch (xml.nodeType) {
            case 1: // Simple element
                {
                    var hasChildNodes = xml.childNodes.length > 0;
                    var expandingNode = hasChildNodes && (xml.childNodes.length > 1 || xml.childNodes[0].nodeType != 3);

                    var expanderHeader = expandingNode ? makeSpan("", "simpleXML-expanderHeader") : parent;

                    var expanderSpan = makeSpan("", "simpleXML-expander");
                    if (expandingNode)
                        $(expanderSpan).addClass("simpleXML-expander-expanded");
                    expanderHeader.appendChild(expanderSpan);

                    expanderHeader.appendChild(makeSpan("<", "simpleXML-tagHeader"));
                    expanderHeader.appendChild(makeSpan(xml.nodeName, "simpleXML-tagValue", {name: 'xpath', value: getXpath( xml )}));

                    if( expandingNode)
                        parent.appendChild(expanderHeader);

                    // Handle attributes
					var attributes = xml.attributes;
					for( var attrIdx = 0 ; attrIdx < attributes.length ; attrIdx++ ) {
						expanderHeader.appendChild( makeSpan( " "));
						expanderHeader.appendChild( makeSpan( attributes [attrIdx].name, "simpleXML-attrName", {name: 'xpath', value: getXpath( xml ) + '/@' + attributes [attrIdx].name }));
						expanderHeader.appendChild( makeSpan( '="' ));
						expanderHeader.appendChild( makeSpan( attributes [attrIdx].value, "simpleXML-attrValue" ));
						expanderHeader.appendChild( makeSpan( '"' ));
					}

                    // Handle child nodes
                    if (hasChildNodes) {

                        parent.appendChild(makeSpan(">", "simpleXML-tagHeader"));

                        if (expandingNode) {
                            var ulElement = document.createElement("ul");
                            for (var i = 0 ; i < xml.childNodes.length ; i++) {
                                var liElement = document.createElement("li");
                                showNode(liElement, xml.childNodes[i], settings);
                                ulElement.appendChild(liElement);
                            }

                            var collapsedTextSpan = makeSpan(settings.collapsedText, "simpleXML-collapsedText");
                            collapsedTextSpan.setAttribute("style", "display: none;");
                            ulElement.setAttribute("class", "simpleXML-content");
                            parent.appendChild(collapsedTextSpan);
                            parent.appendChild(ulElement);

                            parent.appendChild(makeSpan("", "simpleXML-expanderClose"));
                        }
                        else {
                            parent.appendChild(makeSpan(xml.childNodes[0].nodeValue));
                        }

                        // Closing tag
                        parent.appendChild(makeSpan("</", "simpleXML-tagHeader"));
                        parent.appendChild(makeSpan(xml.nodeName, "simpleXML-tagValue", {name: 'xpath', value: getXpath( xml )}));
                        parent.appendChild(makeSpan(">", "simpleXML-tagHeader"));
                    } else {
                        var closingSpan = document.createElement("span");
                        closingSpan.innerText = "/>";
                        parent.appendChild(closingSpan);
                    }
                }
                break;

            case 3: // text
                {
					if( xml.nodeValue.trim() !== "" ) {
						parent.appendChild(makeSpan("", "simpleXML-expander"));
						parent.appendChild(makeSpan(xml.nodeValue));
					}
                }
                break;

            case 4: // cdata
                {
                    parent.appendChild(makeSpan("", "simpleXML-expander"));
                    parent.appendChild(makeSpan("<![CDATA[", "simpleXML-tagHeader"));
                    parent.appendChild(makeSpan(xml.nodeValue, "simpleXML-cdata"));
                    parent.appendChild(makeSpan("]]>", "simpleXML-tagHeader"));
                }
                break;

            case 8: // comment
                {
                	let node_value = xml.nodeValue;
					// node_value = node_value.replace(new RegExp('{open_html_tag}', 'g'), '<');
					// node_value = node_value.replace(new RegExp('{close_html_tag}', 'g'), '>');
                    parent.appendChild(makeSpan("", "simpleXML-expander"));
                    parent.appendChild(makeSpan("<!--" + node_value + "-->", "simpleXML-comment"));
                }
                break;

            default:
                {
                    var item = document.createElement("span");
                    item.innerText = "" + xml.nodeType + " - " + xml.name;
                    parent.appendChild(item);
                }
                break;
        }

        function makeSpan(innerText, classes, attribute) {
            var span = document.createElement("span");
            span.innerText = innerText;

            if (classes != undefined)
                span.setAttribute("class", classes);

			if (attribute != undefined)
				span.setAttribute(attribute.name, attribute.value);

            return span;
        }

        function getXpath(el){
        	return '/' + xpath(el);
		}

		function xpath(el) {
			if (typeof el == "string") return document.evaluate(el, document, null, 0, null)
			if (!el || el.nodeType != 1) return ''
			//if (el.id) return "//*[@id='" + el.id + "']"
			var sames = [].filter.call(el.parentNode.children, function (x) { return x.tagName == el.tagName })
			return xpath(el.parentNode) + '/' + el.tagName + (sames.length > 1 ? '['+([].indexOf.call(sames, el)+1)+']' : '')
		}
    }

}(jQuery));
