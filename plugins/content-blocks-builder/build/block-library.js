!function(){var e={2485:function(e,t){var n;!function(){"use strict";var r={}.hasOwnProperty;function a(){for(var e=[],t=0;t<arguments.length;t++){var n=arguments[t];if(n){var i=typeof n;if("string"===i||"number"===i)e.push(n);else if(Array.isArray(n)){if(n.length){var o=a.apply(null,n);o&&e.push(o)}}else if("object"===i){if(n.toString!==Object.prototype.toString&&!n.toString.toString().includes("[native code]")){e.push(n.toString());continue}for(var l in n)r.call(n,l)&&n[l]&&e.push(l)}}}return e.join(" ")}e.exports?(a.default=a,e.exports=a):void 0===(n=function(){return a}.apply(t,[]))||(e.exports=n)}()},2838:function(e){e.exports=function(){"use strict";function e(t){return e="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},e(t)}function t(e,n){return t=Object.setPrototypeOf||function(e,t){return e.__proto__=t,e},t(e,n)}function n(e,r,a){return n=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Boolean.prototype.valueOf.call(Reflect.construct(Boolean,[],(function(){}))),!0}catch(e){return!1}}()?Reflect.construct:function(e,n,r){var a=[null];a.push.apply(a,n);var i=new(Function.bind.apply(e,a));return r&&t(i,r.prototype),i},n.apply(null,arguments)}function r(e){return function(e){if(Array.isArray(e))return a(e)}(e)||function(e){if("undefined"!=typeof Symbol&&null!=e[Symbol.iterator]||null!=e["@@iterator"])return Array.from(e)}(e)||function(e,t){if(e){if("string"==typeof e)return a(e,t);var n=Object.prototype.toString.call(e).slice(8,-1);return"Object"===n&&e.constructor&&(n=e.constructor.name),"Map"===n||"Set"===n?Array.from(e):"Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)?a(e,t):void 0}}(e)||function(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function a(e,t){(null==t||t>e.length)&&(t=e.length);for(var n=0,r=new Array(t);n<t;n++)r[n]=e[n];return r}var i=Object.hasOwnProperty,o=Object.setPrototypeOf,l=Object.isFrozen,s=Object.getPrototypeOf,c=Object.getOwnPropertyDescriptor,d=Object.freeze,u=Object.seal,p=Object.create,m="undefined"!=typeof Reflect&&Reflect,f=m.apply,g=m.construct;f||(f=function(e,t,n){return e.apply(t,n)}),d||(d=function(e){return e}),u||(u=function(e){return e}),g||(g=function(e,t){return n(e,r(t))});var h,b=C(Array.prototype.forEach),y=C(Array.prototype.pop),v=C(Array.prototype.push),_=C(String.prototype.toLowerCase),w=C(String.prototype.toString),k=C(String.prototype.match),x=C(String.prototype.replace),E=C(String.prototype.indexOf),S=C(String.prototype.trim),T=C(RegExp.prototype.test),A=(h=TypeError,function(){for(var e=arguments.length,t=new Array(e),n=0;n<e;n++)t[n]=arguments[n];return g(h,t)});function C(e){return function(t){for(var n=arguments.length,r=new Array(n>1?n-1:0),a=1;a<n;a++)r[a-1]=arguments[a];return f(e,t,r)}}function N(e,t,n){var r;n=null!==(r=n)&&void 0!==r?r:_,o&&o(e,null);for(var a=t.length;a--;){var i=t[a];if("string"==typeof i){var s=n(i);s!==i&&(l(t)||(t[a]=s),i=s)}e[i]=!0}return e}function O(e){var t,n=p(null);for(t in e)!0===f(i,e,[t])&&(n[t]=e[t]);return n}function P(e,t){for(;null!==e;){var n=c(e,t);if(n){if(n.get)return C(n.get);if("function"==typeof n.value)return C(n.value)}e=s(e)}return function(e){return console.warn("fallback value for",e),null}}var L=d(["a","abbr","acronym","address","area","article","aside","audio","b","bdi","bdo","big","blink","blockquote","body","br","button","canvas","caption","center","cite","code","col","colgroup","content","data","datalist","dd","decorator","del","details","dfn","dialog","dir","div","dl","dt","element","em","fieldset","figcaption","figure","font","footer","form","h1","h2","h3","h4","h5","h6","head","header","hgroup","hr","html","i","img","input","ins","kbd","label","legend","li","main","map","mark","marquee","menu","menuitem","meter","nav","nobr","ol","optgroup","option","output","p","picture","pre","progress","q","rp","rt","ruby","s","samp","section","select","shadow","small","source","spacer","span","strike","strong","style","sub","summary","sup","table","tbody","td","template","textarea","tfoot","th","thead","time","tr","track","tt","u","ul","var","video","wbr"]),I=d(["svg","a","altglyph","altglyphdef","altglyphitem","animatecolor","animatemotion","animatetransform","circle","clippath","defs","desc","ellipse","filter","font","g","glyph","glyphref","hkern","image","line","lineargradient","marker","mask","metadata","mpath","path","pattern","polygon","polyline","radialgradient","rect","stop","style","switch","symbol","text","textpath","title","tref","tspan","view","vkern"]),F=d(["feBlend","feColorMatrix","feComponentTransfer","feComposite","feConvolveMatrix","feDiffuseLighting","feDisplacementMap","feDistantLight","feFlood","feFuncA","feFuncB","feFuncG","feFuncR","feGaussianBlur","feImage","feMerge","feMergeNode","feMorphology","feOffset","fePointLight","feSpecularLighting","feSpotLight","feTile","feTurbulence"]),R=d(["animate","color-profile","cursor","discard","fedropshadow","font-face","font-face-format","font-face-name","font-face-src","font-face-uri","foreignobject","hatch","hatchpath","mesh","meshgradient","meshpatch","meshrow","missing-glyph","script","set","solidcolor","unknown","use"]),M=d(["math","menclose","merror","mfenced","mfrac","mglyph","mi","mlabeledtr","mmultiscripts","mn","mo","mover","mpadded","mphantom","mroot","mrow","ms","mspace","msqrt","mstyle","msub","msup","msubsup","mtable","mtd","mtext","mtr","munder","munderover"]),B=d(["maction","maligngroup","malignmark","mlongdiv","mscarries","mscarry","msgroup","mstack","msline","msrow","semantics","annotation","annotation-xml","mprescripts","none"]),D=d(["#text"]),z=d(["accept","action","align","alt","autocapitalize","autocomplete","autopictureinpicture","autoplay","background","bgcolor","border","capture","cellpadding","cellspacing","checked","cite","class","clear","color","cols","colspan","controls","controlslist","coords","crossorigin","datetime","decoding","default","dir","disabled","disablepictureinpicture","disableremoteplayback","download","draggable","enctype","enterkeyhint","face","for","headers","height","hidden","high","href","hreflang","id","inputmode","integrity","ismap","kind","label","lang","list","loading","loop","low","max","maxlength","media","method","min","minlength","multiple","muted","name","nonce","noshade","novalidate","nowrap","open","optimum","pattern","placeholder","playsinline","poster","preload","pubdate","radiogroup","readonly","rel","required","rev","reversed","role","rows","rowspan","spellcheck","scope","selected","shape","size","sizes","span","srclang","start","src","srcset","step","style","summary","tabindex","title","translate","type","usemap","valign","value","width","xmlns","slot"]),U=d(["accent-height","accumulate","additive","alignment-baseline","ascent","attributename","attributetype","azimuth","basefrequency","baseline-shift","begin","bias","by","class","clip","clippathunits","clip-path","clip-rule","color","color-interpolation","color-interpolation-filters","color-profile","color-rendering","cx","cy","d","dx","dy","diffuseconstant","direction","display","divisor","dur","edgemode","elevation","end","fill","fill-opacity","fill-rule","filter","filterunits","flood-color","flood-opacity","font-family","font-size","font-size-adjust","font-stretch","font-style","font-variant","font-weight","fx","fy","g1","g2","glyph-name","glyphref","gradientunits","gradienttransform","height","href","id","image-rendering","in","in2","k","k1","k2","k3","k4","kerning","keypoints","keysplines","keytimes","lang","lengthadjust","letter-spacing","kernelmatrix","kernelunitlength","lighting-color","local","marker-end","marker-mid","marker-start","markerheight","markerunits","markerwidth","maskcontentunits","maskunits","max","mask","media","method","mode","min","name","numoctaves","offset","operator","opacity","order","orient","orientation","origin","overflow","paint-order","path","pathlength","patterncontentunits","patterntransform","patternunits","points","preservealpha","preserveaspectratio","primitiveunits","r","rx","ry","radius","refx","refy","repeatcount","repeatdur","restart","result","rotate","scale","seed","shape-rendering","specularconstant","specularexponent","spreadmethod","startoffset","stddeviation","stitchtiles","stop-color","stop-opacity","stroke-dasharray","stroke-dashoffset","stroke-linecap","stroke-linejoin","stroke-miterlimit","stroke-opacity","stroke","stroke-width","style","surfacescale","systemlanguage","tabindex","targetx","targety","transform","transform-origin","text-anchor","text-decoration","text-rendering","textlength","type","u1","u2","unicode","values","viewbox","visibility","version","vert-adv-y","vert-origin-x","vert-origin-y","width","word-spacing","wrap","writing-mode","xchannelselector","ychannelselector","x","x1","x2","xmlns","y","y1","y2","z","zoomandpan"]),H=d(["accent","accentunder","align","bevelled","close","columnsalign","columnlines","columnspan","denomalign","depth","dir","display","displaystyle","encoding","fence","frame","height","href","id","largeop","length","linethickness","lspace","lquote","mathbackground","mathcolor","mathsize","mathvariant","maxsize","minsize","movablelimits","notation","numalign","open","rowalign","rowlines","rowspacing","rowspan","rspace","rquote","scriptlevel","scriptminsize","scriptsizemultiplier","selection","separator","separators","stretchy","subscriptshift","supscriptshift","symmetric","voffset","width","xmlns"]),j=d(["xlink:href","xml:id","xlink:title","xml:space","xmlns:xlink"]),V=u(/\{\{[\w\W]*|[\w\W]*\}\}/gm),$=u(/<%[\w\W]*|[\w\W]*%>/gm),G=u(/\${[\w\W]*}/gm),K=u(/^data-[\-\w.\u00B7-\uFFFF]/),W=u(/^aria-[\-\w]+$/),q=u(/^(?:(?:(?:f|ht)tps?|mailto|tel|callto|cid|xmpp):|[^a-z]|[a-z+.\-]+(?:[^a-z+.\-:]|$))/i),Y=u(/^(?:\w+script|data):/i),J=u(/[\u0000-\u0020\u00A0\u1680\u180E\u2000-\u2029\u205F\u3000]/g),X=u(/^html$/i),Z=function(){return"undefined"==typeof window?null:window};return function t(){var n=arguments.length>0&&void 0!==arguments[0]?arguments[0]:Z(),a=function(e){return t(e)};if(a.version="2.4.7",a.removed=[],!n||!n.document||9!==n.document.nodeType)return a.isSupported=!1,a;var i=n.document,o=n.document,l=n.DocumentFragment,s=n.HTMLTemplateElement,c=n.Node,u=n.Element,p=n.NodeFilter,m=n.NamedNodeMap,f=void 0===m?n.NamedNodeMap||n.MozNamedAttrMap:m,g=n.HTMLFormElement,h=n.DOMParser,C=n.trustedTypes,Q=u.prototype,ee=P(Q,"cloneNode"),te=P(Q,"nextSibling"),ne=P(Q,"childNodes"),re=P(Q,"parentNode");if("function"==typeof s){var ae=o.createElement("template");ae.content&&ae.content.ownerDocument&&(o=ae.content.ownerDocument)}var ie=function(t,n){if("object"!==e(t)||"function"!=typeof t.createPolicy)return null;var r=null,a="data-tt-policy-suffix";n.currentScript&&n.currentScript.hasAttribute(a)&&(r=n.currentScript.getAttribute(a));var i="dompurify"+(r?"#"+r:"");try{return t.createPolicy(i,{createHTML:function(e){return e},createScriptURL:function(e){return e}})}catch(e){return console.warn("TrustedTypes policy "+i+" could not be created."),null}}(C,i),oe=ie?ie.createHTML(""):"",le=o,se=le.implementation,ce=le.createNodeIterator,de=le.createDocumentFragment,ue=le.getElementsByTagName,pe=i.importNode,me={};try{me=O(o).documentMode?o.documentMode:{}}catch(e){}var fe={};a.isSupported="function"==typeof re&&se&&void 0!==se.createHTMLDocument&&9!==me;var ge,he,be=V,ye=$,ve=G,_e=K,we=W,ke=Y,xe=J,Ee=q,Se=null,Te=N({},[].concat(r(L),r(I),r(F),r(M),r(D))),Ae=null,Ce=N({},[].concat(r(z),r(U),r(H),r(j))),Ne=Object.seal(Object.create(null,{tagNameCheck:{writable:!0,configurable:!1,enumerable:!0,value:null},attributeNameCheck:{writable:!0,configurable:!1,enumerable:!0,value:null},allowCustomizedBuiltInElements:{writable:!0,configurable:!1,enumerable:!0,value:!1}})),Oe=null,Pe=null,Le=!0,Ie=!0,Fe=!1,Re=!0,Me=!1,Be=!1,De=!1,ze=!1,Ue=!1,He=!1,je=!1,Ve=!0,$e=!1,Ge=!0,Ke=!1,We={},qe=null,Ye=N({},["annotation-xml","audio","colgroup","desc","foreignobject","head","iframe","math","mi","mn","mo","ms","mtext","noembed","noframes","noscript","plaintext","script","style","svg","template","thead","title","video","xmp"]),Je=null,Xe=N({},["audio","video","img","source","image","track"]),Ze=null,Qe=N({},["alt","class","for","id","label","name","pattern","placeholder","role","summary","title","value","style","xmlns"]),et="http://www.w3.org/1998/Math/MathML",tt="http://www.w3.org/2000/svg",nt="http://www.w3.org/1999/xhtml",rt=nt,at=!1,it=null,ot=N({},[et,tt,nt],w),lt=["application/xhtml+xml","text/html"],st=null,ct=o.createElement("form"),dt=function(e){return e instanceof RegExp||e instanceof Function},ut=function(t){st&&st===t||(t&&"object"===e(t)||(t={}),t=O(t),ge=ge=-1===lt.indexOf(t.PARSER_MEDIA_TYPE)?"text/html":t.PARSER_MEDIA_TYPE,he="application/xhtml+xml"===ge?w:_,Se="ALLOWED_TAGS"in t?N({},t.ALLOWED_TAGS,he):Te,Ae="ALLOWED_ATTR"in t?N({},t.ALLOWED_ATTR,he):Ce,it="ALLOWED_NAMESPACES"in t?N({},t.ALLOWED_NAMESPACES,w):ot,Ze="ADD_URI_SAFE_ATTR"in t?N(O(Qe),t.ADD_URI_SAFE_ATTR,he):Qe,Je="ADD_DATA_URI_TAGS"in t?N(O(Xe),t.ADD_DATA_URI_TAGS,he):Xe,qe="FORBID_CONTENTS"in t?N({},t.FORBID_CONTENTS,he):Ye,Oe="FORBID_TAGS"in t?N({},t.FORBID_TAGS,he):{},Pe="FORBID_ATTR"in t?N({},t.FORBID_ATTR,he):{},We="USE_PROFILES"in t&&t.USE_PROFILES,Le=!1!==t.ALLOW_ARIA_ATTR,Ie=!1!==t.ALLOW_DATA_ATTR,Fe=t.ALLOW_UNKNOWN_PROTOCOLS||!1,Re=!1!==t.ALLOW_SELF_CLOSE_IN_ATTR,Me=t.SAFE_FOR_TEMPLATES||!1,Be=t.WHOLE_DOCUMENT||!1,Ue=t.RETURN_DOM||!1,He=t.RETURN_DOM_FRAGMENT||!1,je=t.RETURN_TRUSTED_TYPE||!1,ze=t.FORCE_BODY||!1,Ve=!1!==t.SANITIZE_DOM,$e=t.SANITIZE_NAMED_PROPS||!1,Ge=!1!==t.KEEP_CONTENT,Ke=t.IN_PLACE||!1,Ee=t.ALLOWED_URI_REGEXP||Ee,rt=t.NAMESPACE||nt,Ne=t.CUSTOM_ELEMENT_HANDLING||{},t.CUSTOM_ELEMENT_HANDLING&&dt(t.CUSTOM_ELEMENT_HANDLING.tagNameCheck)&&(Ne.tagNameCheck=t.CUSTOM_ELEMENT_HANDLING.tagNameCheck),t.CUSTOM_ELEMENT_HANDLING&&dt(t.CUSTOM_ELEMENT_HANDLING.attributeNameCheck)&&(Ne.attributeNameCheck=t.CUSTOM_ELEMENT_HANDLING.attributeNameCheck),t.CUSTOM_ELEMENT_HANDLING&&"boolean"==typeof t.CUSTOM_ELEMENT_HANDLING.allowCustomizedBuiltInElements&&(Ne.allowCustomizedBuiltInElements=t.CUSTOM_ELEMENT_HANDLING.allowCustomizedBuiltInElements),Me&&(Ie=!1),He&&(Ue=!0),We&&(Se=N({},r(D)),Ae=[],!0===We.html&&(N(Se,L),N(Ae,z)),!0===We.svg&&(N(Se,I),N(Ae,U),N(Ae,j)),!0===We.svgFilters&&(N(Se,F),N(Ae,U),N(Ae,j)),!0===We.mathMl&&(N(Se,M),N(Ae,H),N(Ae,j))),t.ADD_TAGS&&(Se===Te&&(Se=O(Se)),N(Se,t.ADD_TAGS,he)),t.ADD_ATTR&&(Ae===Ce&&(Ae=O(Ae)),N(Ae,t.ADD_ATTR,he)),t.ADD_URI_SAFE_ATTR&&N(Ze,t.ADD_URI_SAFE_ATTR,he),t.FORBID_CONTENTS&&(qe===Ye&&(qe=O(qe)),N(qe,t.FORBID_CONTENTS,he)),Ge&&(Se["#text"]=!0),Be&&N(Se,["html","head","body"]),Se.table&&(N(Se,["tbody"]),delete Oe.tbody),d&&d(t),st=t)},pt=N({},["mi","mo","mn","ms","mtext"]),mt=N({},["foreignobject","desc","title","annotation-xml"]),ft=N({},["title","style","font","a","script"]),gt=N({},I);N(gt,F),N(gt,R);var ht=N({},M);N(ht,B);var bt=function(e){v(a.removed,{element:e});try{e.parentNode.removeChild(e)}catch(t){try{e.outerHTML=oe}catch(t){e.remove()}}},yt=function(e,t){try{v(a.removed,{attribute:t.getAttributeNode(e),from:t})}catch(e){v(a.removed,{attribute:null,from:t})}if(t.removeAttribute(e),"is"===e&&!Ae[e])if(Ue||He)try{bt(t)}catch(e){}else try{t.setAttribute(e,"")}catch(e){}},vt=function(e){var t,n;if(ze)e="<remove></remove>"+e;else{var r=k(e,/^[\r\n\t ]+/);n=r&&r[0]}"application/xhtml+xml"===ge&&rt===nt&&(e='<html xmlns="http://www.w3.org/1999/xhtml"><head></head><body>'+e+"</body></html>");var a=ie?ie.createHTML(e):e;if(rt===nt)try{t=(new h).parseFromString(a,ge)}catch(e){}if(!t||!t.documentElement){t=se.createDocument(rt,"template",null);try{t.documentElement.innerHTML=at?oe:a}catch(e){}}var i=t.body||t.documentElement;return e&&n&&i.insertBefore(o.createTextNode(n),i.childNodes[0]||null),rt===nt?ue.call(t,Be?"html":"body")[0]:Be?t.documentElement:i},_t=function(e){return ce.call(e.ownerDocument||e,e,p.SHOW_ELEMENT|p.SHOW_COMMENT|p.SHOW_TEXT,null,!1)},wt=function(t){return"object"===e(c)?t instanceof c:t&&"object"===e(t)&&"number"==typeof t.nodeType&&"string"==typeof t.nodeName},kt=function(e,t,n){fe[e]&&b(fe[e],(function(e){e.call(a,t,n,st)}))},xt=function(e){var t,n;if(kt("beforeSanitizeElements",e,null),(n=e)instanceof g&&("string"!=typeof n.nodeName||"string"!=typeof n.textContent||"function"!=typeof n.removeChild||!(n.attributes instanceof f)||"function"!=typeof n.removeAttribute||"function"!=typeof n.setAttribute||"string"!=typeof n.namespaceURI||"function"!=typeof n.insertBefore||"function"!=typeof n.hasChildNodes))return bt(e),!0;if(T(/[\u0080-\uFFFF]/,e.nodeName))return bt(e),!0;var r=he(e.nodeName);if(kt("uponSanitizeElement",e,{tagName:r,allowedTags:Se}),e.hasChildNodes()&&!wt(e.firstElementChild)&&(!wt(e.content)||!wt(e.content.firstElementChild))&&T(/<[/\w]/g,e.innerHTML)&&T(/<[/\w]/g,e.textContent))return bt(e),!0;if("select"===r&&T(/<template/i,e.innerHTML))return bt(e),!0;if(!Se[r]||Oe[r]){if(!Oe[r]&&St(r)){if(Ne.tagNameCheck instanceof RegExp&&T(Ne.tagNameCheck,r))return!1;if(Ne.tagNameCheck instanceof Function&&Ne.tagNameCheck(r))return!1}if(Ge&&!qe[r]){var i=re(e)||e.parentNode,o=ne(e)||e.childNodes;if(o&&i)for(var l=o.length-1;l>=0;--l)i.insertBefore(ee(o[l],!0),te(e))}return bt(e),!0}return e instanceof u&&!function(e){var t=re(e);t&&t.tagName||(t={namespaceURI:rt,tagName:"template"});var n=_(e.tagName),r=_(t.tagName);return!!it[e.namespaceURI]&&(e.namespaceURI===tt?t.namespaceURI===nt?"svg"===n:t.namespaceURI===et?"svg"===n&&("annotation-xml"===r||pt[r]):Boolean(gt[n]):e.namespaceURI===et?t.namespaceURI===nt?"math"===n:t.namespaceURI===tt?"math"===n&&mt[r]:Boolean(ht[n]):e.namespaceURI===nt?!(t.namespaceURI===tt&&!mt[r])&&!(t.namespaceURI===et&&!pt[r])&&!ht[n]&&(ft[n]||!gt[n]):!("application/xhtml+xml"!==ge||!it[e.namespaceURI]))}(e)?(bt(e),!0):"noscript"!==r&&"noembed"!==r&&"noframes"!==r||!T(/<\/no(script|embed|frames)/i,e.innerHTML)?(Me&&3===e.nodeType&&(t=e.textContent,t=x(t,be," "),t=x(t,ye," "),t=x(t,ve," "),e.textContent!==t&&(v(a.removed,{element:e.cloneNode()}),e.textContent=t)),kt("afterSanitizeElements",e,null),!1):(bt(e),!0)},Et=function(e,t,n){if(Ve&&("id"===t||"name"===t)&&(n in o||n in ct))return!1;if(Ie&&!Pe[t]&&T(_e,t));else if(Le&&T(we,t));else if(!Ae[t]||Pe[t]){if(!(St(e)&&(Ne.tagNameCheck instanceof RegExp&&T(Ne.tagNameCheck,e)||Ne.tagNameCheck instanceof Function&&Ne.tagNameCheck(e))&&(Ne.attributeNameCheck instanceof RegExp&&T(Ne.attributeNameCheck,t)||Ne.attributeNameCheck instanceof Function&&Ne.attributeNameCheck(t))||"is"===t&&Ne.allowCustomizedBuiltInElements&&(Ne.tagNameCheck instanceof RegExp&&T(Ne.tagNameCheck,n)||Ne.tagNameCheck instanceof Function&&Ne.tagNameCheck(n))))return!1}else if(Ze[t]);else if(T(Ee,x(n,xe,"")));else if("src"!==t&&"xlink:href"!==t&&"href"!==t||"script"===e||0!==E(n,"data:")||!Je[e])if(Fe&&!T(ke,x(n,xe,"")));else if(n)return!1;return!0},St=function(e){return e.indexOf("-")>0},Tt=function(t){var n,r,i,o;kt("beforeSanitizeAttributes",t,null);var l=t.attributes;if(l){var s={attrName:"",attrValue:"",keepAttr:!0,allowedAttributes:Ae};for(o=l.length;o--;){var c=n=l[o],d=c.name,u=c.namespaceURI;if(r="value"===d?n.value:S(n.value),i=he(d),s.attrName=i,s.attrValue=r,s.keepAttr=!0,s.forceKeepAttr=void 0,kt("uponSanitizeAttribute",t,s),r=s.attrValue,!s.forceKeepAttr&&(yt(d,t),s.keepAttr))if(Re||!T(/\/>/i,r)){Me&&(r=x(r,be," "),r=x(r,ye," "),r=x(r,ve," "));var p=he(t.nodeName);if(Et(p,i,r)){if(!$e||"id"!==i&&"name"!==i||(yt(d,t),r="user-content-"+r),ie&&"object"===e(C)&&"function"==typeof C.getAttributeType)if(u);else switch(C.getAttributeType(p,i)){case"TrustedHTML":r=ie.createHTML(r);break;case"TrustedScriptURL":r=ie.createScriptURL(r)}try{u?t.setAttributeNS(u,d,r):t.setAttribute(d,r),y(a.removed)}catch(e){}}}else yt(d,t)}kt("afterSanitizeAttributes",t,null)}},At=function e(t){var n,r=_t(t);for(kt("beforeSanitizeShadowDOM",t,null);n=r.nextNode();)kt("uponSanitizeShadowNode",n,null),xt(n)||(n.content instanceof l&&e(n.content),Tt(n));kt("afterSanitizeShadowDOM",t,null)};return a.sanitize=function(t){var r,o,s,d,u,p=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{};if((at=!t)&&(t="\x3c!--\x3e"),"string"!=typeof t&&!wt(t)){if("function"!=typeof t.toString)throw A("toString is not a function");if("string"!=typeof(t=t.toString()))throw A("dirty is not a string, aborting")}if(!a.isSupported){if("object"===e(n.toStaticHTML)||"function"==typeof n.toStaticHTML){if("string"==typeof t)return n.toStaticHTML(t);if(wt(t))return n.toStaticHTML(t.outerHTML)}return t}if(De||ut(p),a.removed=[],"string"==typeof t&&(Ke=!1),Ke){if(t.nodeName){var m=he(t.nodeName);if(!Se[m]||Oe[m])throw A("root node is forbidden and cannot be sanitized in-place")}}else if(t instanceof c)1===(o=(r=vt("\x3c!----\x3e")).ownerDocument.importNode(t,!0)).nodeType&&"BODY"===o.nodeName||"HTML"===o.nodeName?r=o:r.appendChild(o);else{if(!Ue&&!Me&&!Be&&-1===t.indexOf("<"))return ie&&je?ie.createHTML(t):t;if(!(r=vt(t)))return Ue?null:je?oe:""}r&&ze&&bt(r.firstChild);for(var f=_t(Ke?t:r);s=f.nextNode();)3===s.nodeType&&s===d||xt(s)||(s.content instanceof l&&At(s.content),Tt(s),d=s);if(d=null,Ke)return t;if(Ue){if(He)for(u=de.call(r.ownerDocument);r.firstChild;)u.appendChild(r.firstChild);else u=r;return(Ae.shadowroot||Ae.shadowrootmod)&&(u=pe.call(i,u,!0)),u}var g=Be?r.outerHTML:r.innerHTML;return Be&&Se["!doctype"]&&r.ownerDocument&&r.ownerDocument.doctype&&r.ownerDocument.doctype.name&&T(X,r.ownerDocument.doctype.name)&&(g="<!DOCTYPE "+r.ownerDocument.doctype.name+">\n"+g),Me&&(g=x(g,be," "),g=x(g,ye," "),g=x(g,ve," ")),ie&&je?ie.createHTML(g):g},a.setConfig=function(e){ut(e),De=!0},a.clearConfig=function(){st=null,De=!1},a.isValidAttribute=function(e,t,n){st||ut({});var r=he(e),a=he(t);return Et(r,a,n)},a.addHook=function(e,t){"function"==typeof t&&(fe[e]=fe[e]||[],v(fe[e],t))},a.removeHook=function(e){if(fe[e])return y(fe[e])},a.removeHooks=function(e){fe[e]&&(fe[e]=[])},a.removeAllHooks=function(){fe={}},a}()}()},2774:function(e){"use strict";e.exports=function e(t,n){if(t===n)return!0;if(t&&n&&"object"==typeof t&&"object"==typeof n){if(t.constructor!==n.constructor)return!1;var r,a,i;if(Array.isArray(t)){if((r=t.length)!=n.length)return!1;for(a=r;0!=a--;)if(!e(t[a],n[a]))return!1;return!0}if(t.constructor===RegExp)return t.source===n.source&&t.flags===n.flags;if(t.valueOf!==Object.prototype.valueOf)return t.valueOf()===n.valueOf();if(t.toString!==Object.prototype.toString)return t.toString()===n.toString();if((r=(i=Object.keys(t)).length)!==Object.keys(n).length)return!1;for(a=r;0!=a--;)if(!Object.prototype.hasOwnProperty.call(n,i[a]))return!1;for(a=r;0!=a--;){var o=i[a];if(!("_owner"===o&&t.$$typeof||e(t[o],n[o])))return!1}return!0}return t!=t&&n!=n}},5531:function(e,t){var n,r;void 0===(r="function"==typeof(n=e=>{"use strict";var t=(e,t)=>{if(e===S)return S;var n=e.target,r=n.length,a=e._indexes;a=a.slice(0,a.len).sort(((e,t)=>e-t));for(var i="",o=0,l=0,s=!1,c=(e=[],0);c<r;++c){var d=n[c];if(a[l]===c){if(++l,s||(s=!0,e.push(i),i=""),l===a.length){i+=d,e.push(t(i,o++)),i="",e.push(n.substr(c+1));break}}else s&&(s=!1,e.push(t(i,o++)),i="");i+=d}return e},n=e=>{"string"!=typeof e&&(e="");var t=c(e);return{target:e,_targetLower:t._lower,_targetLowerCodes:t.lowerCodes,_nextBeginningIndexes:S,_bitflags:t.bitflags,score:S,_indexes:[0],obj:S}},r=e=>{"string"!=typeof e&&(e=""),e=e.trim();var t=c(e),n=[];if(t.containsSpace){var r=e.split(/\s+/);r=[...new Set(r)];for(var a=0;a<r.length;a++)if(""!==r[a]){var i=c(r[a]);n.push({lowerCodes:i.lowerCodes,_lower:r[a].toLowerCase(),containsSpace:!1})}}return{lowerCodes:t.lowerCodes,bitflags:t.bitflags,containsSpace:t.containsSpace,_lower:t._lower,spaceSearches:n}},a=e=>{if(e.length>999)return n(e);var t=u.get(e);return void 0!==t||(t=n(e),u.set(e,t)),t},i=e=>{if(e.length>999)return r(e);var t=p.get(e);return void 0!==t||(t=r(e),p.set(e,t)),t},o=(e,t,n)=>{var r=[];r.total=t.length;var i=n&&n.limit||y;if(n&&n.key)for(var o=0;o<t.length;o++){var l=t[o];if(u=h(l,n.key)){b(u)||(u=a(u)),u.score=v,u._indexes.len=0;var s=u;if(s={target:s.target,_targetLower:"",_targetLowerCodes:S,_nextBeginningIndexes:S,_bitflags:0,score:u.score,_indexes:S,obj:l},r.push(s),r.length>=i)return r}}else if(n&&n.keys)for(o=0;o<t.length;o++){l=t[o];for(var c=new Array(n.keys.length),d=n.keys.length-1;d>=0;--d)(u=h(l,n.keys[d]))?(b(u)||(u=a(u)),u.score=v,u._indexes.len=0,c[d]=u):c[d]=S;if(c.obj=l,c.score=v,r.push(c),r.length>=i)return r}else for(o=0;o<t.length;o++){var u;if((u=t[o])&&(b(u)||(u=a(u)),u.score=v,u._indexes.len=0,r.push(u),r.length>=i))return r}return r},l=(e,t,n=!1)=>{if(!1===n&&e.containsSpace)return s(e,t);for(var r=e._lower,a=e.lowerCodes,i=a[0],o=t._targetLowerCodes,l=a.length,c=o.length,u=0,p=0,g=0;;){if(i===o[p]){if(m[g++]=p,++u===l)break;i=a[u]}if(++p>=c)return S}u=0;var h=!1,b=0,y=t._nextBeginningIndexes;y===S&&(y=t._nextBeginningIndexes=d(t.target));var v=0;if((p=0===m[0]?0:y[m[0]-1])!==c)for(;;)if(p>=c){if(u<=0)break;if(++v>200)break;--u,p=y[f[--b]]}else if(a[u]===o[p]){if(f[b++]=p,++u===l){h=!0;break}++p}else p=y[p];var _=t._targetLower.indexOf(r,m[0]),w=~_;if(w&&!h)for(var k=0;k<g;++k)m[k]=_+k;var x=!1;if(w&&(x=t._nextBeginningIndexes[_-1]===_),h)var E=f,T=b;else E=m,T=g;var A=0,C=0;for(k=1;k<l;++k)E[k]-E[k-1]!=1&&(A-=E[k],++C);if(A-=(E[l-1]-E[0]-(l-1)+12)*C,0!==E[0]&&(A-=E[0]*E[0]*.2),h){var N=1;for(k=y[0];k<c;k=y[k])++N;N>24&&(A*=10*(N-24))}else A*=1e3;for(w&&(A/=1+l*l*1),x&&(A/=1+l*l*1),A-=c-l,t.score=A,k=0;k<T;++k)t._indexes[k]=E[k];return t._indexes.len=T,t},s=(e,t)=>{for(var n=new Set,r=0,a=S,i=0,o=e.spaceSearches,s=0;s<o.length;++s){var c=o[s];if((a=l(c,t))===S)return S;r+=a.score,a._indexes[0]<i&&(r-=i-a._indexes[0]),i=a._indexes[0];for(var d=0;d<a._indexes.len;++d)n.add(a._indexes[d])}var u=l(e,t,!0);if(u!==S&&u.score>r)return u;a.score=r,s=0;for(let e of n)a._indexes[s++]=e;return a._indexes.len=s,a},c=e=>{for(var t=e.length,n=e.toLowerCase(),r=[],a=0,i=!1,o=0;o<t;++o){var l=r[o]=n.charCodeAt(o);32!==l?a|=1<<(l>=97&&l<=122?l-97:l>=48&&l<=57?26:l<=127?30:31):i=!0}return{lowerCodes:r,bitflags:a,containsSpace:i,_lower:n}},d=e=>{for(var t=e.length,n=(e=>{for(var t=e.length,n=[],r=0,a=!1,i=!1,o=0;o<t;++o){var l=e.charCodeAt(o),s=l>=65&&l<=90,c=s||l>=97&&l<=122||l>=48&&l<=57,d=s&&!a||!i||!c;a=s,i=c,d&&(n[r++]=o)}return n})(e),r=[],a=n[0],i=0,o=0;o<t;++o)a>o?r[o]=a:(a=n[++i],r[o]=void 0===a?t:a);return r},u=new Map,p=new Map,m=[],f=[],g=e=>{for(var t=v,n=e.length,r=0;r<n;++r){var a=e[r];if(a!==S){var i=a.score;i>t&&(t=i)}}return t===v?S:t},h=(e,t)=>{var n=e[t];if(void 0!==n)return n;var r=t;Array.isArray(t)||(r=t.split("."));for(var a=r.length,i=-1;e&&++i<a;)e=e[r[i]];return e},b=e=>"object"==typeof e,y=1/0,v=-y,_=[];_.total=0;var w,k,x,E,S=null,T=(w=[],k=0,E=e=>{for(var t=0,n=w[t],r=1;r<k;){var a=r+1;t=r,a<k&&w[a].score<w[r].score&&(t=a),w[t-1>>1]=w[t],r=1+(t<<1)}for(var i=t-1>>1;t>0&&n.score<w[i].score;i=(t=i)-1>>1)w[t]=w[i];w[t]=n},(x={}).add=e=>{var t=k;w[k++]=e;for(var n=t-1>>1;t>0&&e.score<w[n].score;n=(t=n)-1>>1)w[t]=w[n];w[t]=e},x.poll=e=>{if(0!==k){var t=w[0];return w[0]=w[--k],E(),t}},x.peek=e=>{if(0!==k)return w[0]},x.replaceTop=e=>{w[0]=e,E()},x);return{single:(e,t)=>{if("farzher"==e)return{target:"farzher was here (^-^*)/",score:0,_indexes:[0]};if(!e||!t)return S;var n=i(e);b(t)||(t=a(t));var r=n.bitflags;return(r&t._bitflags)!==r?S:l(n,t)},go:(e,t,n)=>{if("farzher"==e)return[{target:"farzher was here (^-^*)/",score:0,_indexes:[0],obj:t?t[0]:S}];if(!e)return n&&n.all?o(e,t,n):_;var r=i(e),s=r.bitflags,c=(r.containsSpace,n&&n.threshold||v),d=n&&n.limit||y,u=0,p=0,m=t.length;if(n&&n.key)for(var f=n.key,w=0;w<m;++w){var k=t[w];(P=h(k,f))&&(b(P)||(P=a(P)),(s&P._bitflags)===s&&(L=l(r,P))!==S&&(L.score<c||(L={target:L.target,_targetLower:"",_targetLowerCodes:S,_nextBeginningIndexes:S,_bitflags:0,score:L.score,_indexes:L._indexes,obj:k},u<d?(T.add(L),++u):(++p,L.score>T.peek().score&&T.replaceTop(L)))))}else if(n&&n.keys){var x=n.scoreFn||g,E=n.keys,A=E.length;for(w=0;w<m;++w){k=t[w];for(var C=new Array(A),N=0;N<A;++N)f=E[N],(P=h(k,f))?(b(P)||(P=a(P)),(s&P._bitflags)!==s?C[N]=S:C[N]=l(r,P)):C[N]=S;C.obj=k;var O=x(C);O!==S&&(O<c||(C.score=O,u<d?(T.add(C),++u):(++p,O>T.peek().score&&T.replaceTop(C))))}}else for(w=0;w<m;++w){var P,L;(P=t[w])&&(b(P)||(P=a(P)),(s&P._bitflags)===s&&(L=l(r,P))!==S&&(L.score<c||(u<d?(T.add(L),++u):(++p,L.score>T.peek().score&&T.replaceTop(L)))))}if(0===u)return _;var I=new Array(u);for(w=u-1;w>=0;--w)I[w]=T.poll();return I.total=u+p,I},highlight:(e,n,r)=>{if("function"==typeof n)return t(e,n);if(e===S)return S;void 0===n&&(n="<b>"),void 0===r&&(r="</b>");var a="",i=0,o=!1,l=e.target,s=l.length,c=e._indexes;c=c.slice(0,c.len).sort(((e,t)=>e-t));for(var d=0;d<s;++d){var u=l[d];if(c[i]===d){if(o||(o=!0,a+=n),++i===c.length){a+=u+r+l.substr(d+1);break}}else o&&(o=!1,a+=r);a+=u}return a},prepare:n,indexes:e=>e._indexes.slice(0,e._indexes.len).sort(((e,t)=>e-t)),cleanup:()=>{u.clear(),p.clear(),m=[],f=[]}}})?n.apply(t,[]):n)||(e.exports=r)},4717:function(e){e.exports=function(e){var t=function(n,r,a){var i=n.splice(0,50);a=(a=a||[]).concat(e.add(i)),n.length>0?setTimeout((function(){t(n,r,a)}),1):(e.update(),r(a))};return t}},4249:function(e){e.exports=function(e){return e.handlers.filterStart=e.handlers.filterStart||[],e.handlers.filterComplete=e.handlers.filterComplete||[],function(t){if(e.trigger("filterStart"),e.i=1,e.reset.filter(),void 0===t)e.filtered=!1;else{e.filtered=!0;for(var n=e.items,r=0,a=n.length;r<a;r++){var i=n[r];t(i)?i.filtered=!0:i.filtered=!1}}return e.update(),e.trigger("filterComplete"),e.visibleItems}}},4844:function(e,t,n){n(5981);var r=n(6332),a=n(433),i=n(8340),o=n(378),l=n(7481);e.exports=function(e,t){t=a({location:0,distance:100,threshold:.4,multiSearch:!0,searchClass:"fuzzy-search"},t=t||{});var n={search:function(r,a){for(var i=t.multiSearch?r.replace(/ +$/,"").split(/ +/):[r],o=0,l=e.items.length;o<l;o++)n.item(e.items[o],a,i)},item:function(e,t,r){for(var a=!0,i=0;i<r.length;i++){for(var o=!1,l=0,s=t.length;l<s;l++)n.values(e.values(),t[l],r[i])&&(o=!0);o||(a=!1)}e.found=a},values:function(e,n,r){if(e.hasOwnProperty(n)){var a=i(e[n]).toLowerCase();if(l(a,r,t))return!0}return!1}};return r.bind(o(e.listContainer,t.searchClass),"keyup",e.utils.events.debounce((function(t){var r=t.target||t.srcElement;e.search(r.value,n.search)}),e.searchDelay)),function(t,r){e.search(t,r,n.search)}}},9799:function(e,t,n){var r=n(2813),a=n(378),i=n(433),o=n(2859),l=n(6332),s=n(8340),c=n(5981),d=n(8200),u=n(9212);e.exports=function(e,t,p){var m,f=this,g=n(6608)(f),h=n(4717)(f),b=n(3195)(f);m={start:function(){f.listClass="list",f.searchClass="search",f.sortClass="sort",f.page=1e4,f.i=1,f.items=[],f.visibleItems=[],f.matchingItems=[],f.searched=!1,f.filtered=!1,f.searchColumns=void 0,f.searchDelay=0,f.handlers={updated:[]},f.valueNames=[],f.utils={getByClass:a,extend:i,indexOf:o,events:l,toString:s,naturalSort:r,classes:c,getAttribute:d,toArray:u},f.utils.extend(f,t),f.listContainer="string"==typeof e?document.getElementById(e):e,f.listContainer&&(f.list=a(f.listContainer,f.listClass,!0),f.parse=n(8672)(f),f.templater=n(4939)(f),f.search=n(4647)(f),f.filter=n(4249)(f),f.sort=n(6343)(f),f.fuzzySearch=n(4844)(f,t.fuzzySearch),this.handlers(),this.items(),this.pagination(),f.update())},handlers:function(){for(var e in f.handlers)f[e]&&f.handlers.hasOwnProperty(e)&&f.on(e,f[e])},items:function(){f.parse(f.list),void 0!==p&&f.add(p)},pagination:function(){if(void 0!==t.pagination){!0===t.pagination&&(t.pagination=[{}]),void 0===t.pagination[0]&&(t.pagination=[t.pagination]);for(var e=0,n=t.pagination.length;e<n;e++)b(t.pagination[e])}}},this.reIndex=function(){f.items=[],f.visibleItems=[],f.matchingItems=[],f.searched=!1,f.filtered=!1,f.parse(f.list)},this.toJSON=function(){for(var e=[],t=0,n=f.items.length;t<n;t++)e.push(f.items[t].values());return e},this.add=function(e,t){if(0!==e.length){if(!t){var n=[],r=!1;void 0===e[0]&&(e=[e]);for(var a=0,i=e.length;a<i;a++){var o;r=f.items.length>f.page,o=new g(e[a],void 0,r),f.items.push(o),n.push(o)}return f.update(),n}h(e.slice(0),t)}},this.show=function(e,t){return this.i=e,this.page=t,f.update(),f},this.remove=function(e,t,n){for(var r=0,a=0,i=f.items.length;a<i;a++)f.items[a].values()[e]==t&&(f.templater.remove(f.items[a],n),f.items.splice(a,1),i--,a--,r++);return f.update(),r},this.get=function(e,t){for(var n=[],r=0,a=f.items.length;r<a;r++){var i=f.items[r];i.values()[e]==t&&n.push(i)}return n},this.size=function(){return f.items.length},this.clear=function(){return f.templater.clear(),f.items=[],f},this.on=function(e,t){return f.handlers[e].push(t),f},this.off=function(e,t){var n=f.handlers[e],r=o(n,t);return r>-1&&n.splice(r,1),f},this.trigger=function(e){for(var t=f.handlers[e].length;t--;)f.handlers[e][t](f);return f},this.reset={filter:function(){for(var e=f.items,t=e.length;t--;)e[t].filtered=!1;return f},search:function(){for(var e=f.items,t=e.length;t--;)e[t].found=!1;return f}},this.update=function(){var e=f.items,t=e.length;f.visibleItems=[],f.matchingItems=[],f.templater.clear();for(var n=0;n<t;n++)e[n].matching()&&f.matchingItems.length+1>=f.i&&f.visibleItems.length<f.page?(e[n].show(),f.visibleItems.push(e[n]),f.matchingItems.push(e[n])):e[n].matching()?(f.matchingItems.push(e[n]),e[n].hide()):e[n].hide();return f.trigger("updated"),f},m.start()}},6608:function(e){e.exports=function(e){return function(t,n,r){var a=this;this._values={},this.found=!1,this.filtered=!1,this.values=function(t,n){if(void 0===t)return a._values;for(var r in t)a._values[r]=t[r];!0!==n&&e.templater.set(a,a.values())},this.show=function(){e.templater.show(a)},this.hide=function(){e.templater.hide(a)},this.matching=function(){return e.filtered&&e.searched&&a.found&&a.filtered||e.filtered&&!e.searched&&a.filtered||!e.filtered&&e.searched&&a.found||!e.filtered&&!e.searched},this.visible=function(){return!(!a.elm||a.elm.parentNode!=e.list)},function(t,n,r){if(void 0===n)r?a.values(t,r):a.values(t);else{a.elm=n;var i=e.templater.get(a,t);a.values(i)}}(t,n,r)}}},3195:function(e,t,n){var r=n(5981),a=n(6332),i=n(9799);e.exports=function(e){var t=!1,n=function(n,a){if(e.page<1)return e.listContainer.style.display="none",void(t=!0);t&&(e.listContainer.style.display="block");var i,l=e.matchingItems.length,s=e.i,c=e.page,d=Math.ceil(l/c),u=Math.ceil(s/c),p=a.innerWindow||2,m=a.left||a.outerWindow||0,f=a.right||a.outerWindow||0;f=d-f,n.clear();for(var g=1;g<=d;g++){var h=u===g?"active":"";o.number(g,m,f,u,p)?(i=n.add({page:g,dotted:!1})[0],h&&r(i.elm).add(h),i.elm.firstChild.setAttribute("data-i",g),i.elm.firstChild.setAttribute("data-page",c)):o.dotted(n,g,m,f,u,p,n.size())&&(i=n.add({page:"...",dotted:!0})[0],r(i.elm).add("disabled"))}},o={number:function(e,t,n,r,a){return this.left(e,t)||this.right(e,n)||this.innerWindow(e,r,a)},left:function(e,t){return e<=t},right:function(e,t){return e>t},innerWindow:function(e,t,n){return e>=t-n&&e<=t+n},dotted:function(e,t,n,r,a,i,o){return this.dottedLeft(e,t,n,r,a,i)||this.dottedRight(e,t,n,r,a,i,o)},dottedLeft:function(e,t,n,r,a,i){return t==n+1&&!this.innerWindow(t,a,i)&&!this.right(t,r)},dottedRight:function(e,t,n,r,a,i,o){return!e.items[o-1].values().dotted&&t==r&&!this.innerWindow(t,a,i)&&!this.right(t,r)}};return function(t){var r=new i(e.listContainer.id,{listClass:t.paginationClass||"pagination",item:t.item||"<li><a class='page' href='#'></a></li>",valueNames:["page","dotted"],searchClass:"pagination-search-that-is-not-supposed-to-exist",sortClass:"pagination-sort-that-is-not-supposed-to-exist"});a.bind(r.listContainer,"click",(function(t){var n=t.target||t.srcElement,r=e.utils.getAttribute(n,"data-page"),a=e.utils.getAttribute(n,"data-i");a&&e.show((a-1)*r+1,r)})),e.on("updated",(function(){n(r,t)})),n(r,t)}}},8672:function(e,t,n){e.exports=function(e){var t=n(6608)(e),r=function(n,r){for(var a=0,i=n.length;a<i;a++)e.items.push(new t(r,n[a]))},a=function(t,n){var i=t.splice(0,50);r(i,n),t.length>0?setTimeout((function(){a(t,n)}),1):(e.update(),e.trigger("parseComplete"))};return e.handlers.parseComplete=e.handlers.parseComplete||[],function(){var t=function(e){for(var t=e.childNodes,n=[],r=0,a=t.length;r<a;r++)void 0===t[r].data&&n.push(t[r]);return n}(e.list),n=e.valueNames;e.indexAsync?a(t,n):r(t,n)}}},4647:function(e){e.exports=function(e){var t,n,r,a={resetList:function(){e.i=1,e.templater.clear(),r=void 0},setOptions:function(e){2==e.length&&e[1]instanceof Array?t=e[1]:2==e.length&&"function"==typeof e[1]?(t=void 0,r=e[1]):3==e.length?(t=e[1],r=e[2]):t=void 0},setColumns:function(){0!==e.items.length&&void 0===t&&(t=void 0===e.searchColumns?a.toArray(e.items[0].values()):e.searchColumns)},setSearchString:function(t){t=(t=e.utils.toString(t).toLowerCase()).replace(/[-[\]{}()*+?.,\\^$|#]/g,"\\$&"),n=t},toArray:function(e){var t=[];for(var n in e)t.push(n);return t}},i=function(i){return e.trigger("searchStart"),a.resetList(),a.setSearchString(i),a.setOptions(arguments),a.setColumns(),""===n?(e.reset.search(),e.searched=!1):(e.searched=!0,r?r(n,t):function(){for(var r,a=[],i=n;null!==(r=i.match(/"([^"]+)"/));)a.push(r[1]),i=i.substring(0,r.index)+i.substring(r.index+r[0].length);(i=i.trim()).length&&(a=a.concat(i.split(/\s+/)));for(var o=0,l=e.items.length;o<l;o++){var s=e.items[o];if(s.found=!1,a.length){for(var c=0,d=a.length;c<d;c++){for(var u=!1,p=0,m=t.length;p<m;p++){var f=s.values(),g=t[p];if(f.hasOwnProperty(g)&&void 0!==f[g]&&null!==f[g]&&-1!==("string"!=typeof f[g]?f[g].toString():f[g]).toLowerCase().indexOf(a[c])){u=!0;break}}if(!u)break}s.found=u}}}()),e.update(),e.trigger("searchComplete"),e.visibleItems};return e.handlers.searchStart=e.handlers.searchStart||[],e.handlers.searchComplete=e.handlers.searchComplete||[],e.utils.events.bind(e.utils.getByClass(e.listContainer,e.searchClass),"keyup",e.utils.events.debounce((function(t){var n=t.target||t.srcElement;""===n.value&&!e.searched||i(n.value)}),e.searchDelay)),e.utils.events.bind(e.utils.getByClass(e.listContainer,e.searchClass),"input",(function(e){""===(e.target||e.srcElement).value&&i("")})),i}},6343:function(e){e.exports=function(e){var t={els:void 0,clear:function(){for(var n=0,r=t.els.length;n<r;n++)e.utils.classes(t.els[n]).remove("asc"),e.utils.classes(t.els[n]).remove("desc")},getOrder:function(t){var n=e.utils.getAttribute(t,"data-order");return"asc"==n||"desc"==n?n:e.utils.classes(t).has("desc")?"asc":e.utils.classes(t).has("asc")?"desc":"asc"},getInSensitive:function(t,n){var r=e.utils.getAttribute(t,"data-insensitive");n.insensitive="false"!==r},setOrder:function(n){for(var r=0,a=t.els.length;r<a;r++){var i=t.els[r];if(e.utils.getAttribute(i,"data-sort")===n.valueName){var o=e.utils.getAttribute(i,"data-order");"asc"==o||"desc"==o?o==n.order&&e.utils.classes(i).add(n.order):e.utils.classes(i).add(n.order)}}}},n=function(){e.trigger("sortStart");var n={},r=arguments[0].currentTarget||arguments[0].srcElement||void 0;r?(n.valueName=e.utils.getAttribute(r,"data-sort"),t.getInSensitive(r,n),n.order=t.getOrder(r)):((n=arguments[1]||n).valueName=arguments[0],n.order=n.order||"asc",n.insensitive=void 0===n.insensitive||n.insensitive),t.clear(),t.setOrder(n);var a,i=n.sortFunction||e.sortFunction||null,o="desc"===n.order?-1:1;a=i?function(e,t){return i(e,t,n)*o}:function(t,r){var a=e.utils.naturalSort;return a.alphabet=e.alphabet||n.alphabet||void 0,!a.alphabet&&n.insensitive&&(a=e.utils.naturalSort.caseInsensitive),a(t.values()[n.valueName],r.values()[n.valueName])*o},e.items.sort(a),e.update(),e.trigger("sortComplete")};return e.handlers.sortStart=e.handlers.sortStart||[],e.handlers.sortComplete=e.handlers.sortComplete||[],t.els=e.utils.getByClass(e.listContainer,e.sortClass),e.utils.events.bind(t.els,"click",n),e.on("searchStart",t.clear),e.on("filterStart",t.clear),n}},4939:function(e){var t=function(e){var t,n=this,r=function(e){if("string"==typeof e){if(/<tr[\s>]/g.exec(e)){var t=document.createElement("tbody");return t.innerHTML=e,t.firstElementChild}if(-1!==e.indexOf("<")){var n=document.createElement("div");return n.innerHTML=e,n.firstElementChild}}},a=function(t,n,r){var a=void 0,i=function(t){for(var n=0,r=e.valueNames.length;n<r;n++){var a=e.valueNames[n];if(a.data){for(var i=a.data,o=0,l=i.length;o<l;o++)if(i[o]===t)return{data:t}}else{if(a.attr&&a.name&&a.name==t)return a;if(a===t)return t}}}(n);i&&(i.data?t.elm.setAttribute("data-"+i.data,r):i.attr&&i.name?(a=e.utils.getByClass(t.elm,i.name,!0))&&a.setAttribute(i.attr,r):(a=e.utils.getByClass(t.elm,i,!0))&&(a.innerHTML=r))};this.get=function(t,r){n.create(t);for(var a={},i=0,o=r.length;i<o;i++){var l=void 0,s=r[i];if(s.data)for(var c=0,d=s.data.length;c<d;c++)a[s.data[c]]=e.utils.getAttribute(t.elm,"data-"+s.data[c]);else s.attr&&s.name?(l=e.utils.getByClass(t.elm,s.name,!0),a[s.name]=l?e.utils.getAttribute(l,s.attr):""):(l=e.utils.getByClass(t.elm,s,!0),a[s]=l?l.innerHTML:"")}return a},this.set=function(e,t){if(!n.create(e))for(var r in t)t.hasOwnProperty(r)&&a(e,r,t[r])},this.create=function(e){return void 0===e.elm&&(e.elm=t(e.values()),n.set(e,e.values()),!0)},this.remove=function(t){t.elm.parentNode===e.list&&e.list.removeChild(t.elm)},this.show=function(t){n.create(t),e.list.appendChild(t.elm)},this.hide=function(t){void 0!==t.elm&&t.elm.parentNode===e.list&&e.list.removeChild(t.elm)},this.clear=function(){if(e.list.hasChildNodes())for(;e.list.childNodes.length>=1;)e.list.removeChild(e.list.firstChild)},function(){var n;if("function"!=typeof e.item){if(!(n="string"==typeof e.item?-1===e.item.indexOf("<")?document.getElementById(e.item):r(e.item):function(){for(var t=e.list.childNodes,n=0,r=t.length;n<r;n++)if(void 0===t[n].data)return t[n].cloneNode(!0)}()))throw new Error("The list needs to have at least one item on init otherwise you'll have to add a template.");n=function(t,n){var r=t.cloneNode(!0);r.removeAttribute("id");for(var a=0,i=n.length;a<i;a++){var o=void 0,l=n[a];if(l.data)for(var s=0,c=l.data.length;s<c;s++)r.setAttribute("data-"+l.data[s],"");else l.attr&&l.name?(o=e.utils.getByClass(r,l.name,!0))&&o.setAttribute(l.attr,""):(o=e.utils.getByClass(r,l,!0))&&(o.innerHTML="")}return r}(n,e.valueNames),t=function(){return n.cloneNode(!0)}}else t=function(t){var n=e.item(t);return r(n)}}()};e.exports=function(e){return new t(e)}},5981:function(e,t,n){var r=n(2859),a=/\s+/;function i(e){if(!e||!e.nodeType)throw new Error("A DOM element reference is required");this.el=e,this.list=e.classList}Object.prototype.toString,e.exports=function(e){return new i(e)},i.prototype.add=function(e){if(this.list)return this.list.add(e),this;var t=this.array();return~r(t,e)||t.push(e),this.el.className=t.join(" "),this},i.prototype.remove=function(e){if(this.list)return this.list.remove(e),this;var t=this.array(),n=r(t,e);return~n&&t.splice(n,1),this.el.className=t.join(" "),this},i.prototype.toggle=function(e,t){return this.list?(void 0!==t?t!==this.list.toggle(e,t)&&this.list.toggle(e):this.list.toggle(e),this):(void 0!==t?t?this.add(e):this.remove(e):this.has(e)?this.remove(e):this.add(e),this)},i.prototype.array=function(){var e=(this.el.getAttribute("class")||"").replace(/^\s+|\s+$/g,"").split(a);return""===e[0]&&e.shift(),e},i.prototype.has=i.prototype.contains=function(e){return this.list?this.list.contains(e):!!~r(this.array(),e)}},6332:function(e,t,n){var r=window.addEventListener?"addEventListener":"attachEvent",a=window.removeEventListener?"removeEventListener":"detachEvent",i="addEventListener"!==r?"on":"",o=n(9212);t.bind=function(e,t,n,a){for(var l=0,s=(e=o(e)).length;l<s;l++)e[l][r](i+t,n,a||!1)},t.unbind=function(e,t,n,r){for(var l=0,s=(e=o(e)).length;l<s;l++)e[l][a](i+t,n,r||!1)},t.debounce=function(e,t,n){var r;return t?function(){var a=this,i=arguments,o=n&&!r;clearTimeout(r),r=setTimeout((function(){r=null,n||e.apply(a,i)}),t),o&&e.apply(a,i)}:e}},433:function(e){e.exports=function(e){for(var t,n=Array.prototype.slice.call(arguments,1),r=0;t=n[r];r++)if(t)for(var a in t)e[a]=t[a];return e}},7481:function(e){e.exports=function(e,t,n){var r=n.location||0,a=n.distance||100,i=n.threshold||.4;if(t===e)return!0;if(t.length>32)return!1;var o=r,l=function(){var e,n={};for(e=0;e<t.length;e++)n[t.charAt(e)]=0;for(e=0;e<t.length;e++)n[t.charAt(e)]|=1<<t.length-e-1;return n}();function s(e,n){var r=e/t.length,i=Math.abs(o-n);return a?r+i/a:i?1:r}var c=i,d=e.indexOf(t,o);-1!=d&&(c=Math.min(s(0,d),c),-1!=(d=e.lastIndexOf(t,o+t.length))&&(c=Math.min(s(0,d),c)));var u,p,m=1<<t.length-1;d=-1;for(var f,g=t.length+e.length,h=0;h<t.length;h++){for(u=0,p=g;u<p;)s(h,o+p)<=c?u=p:g=p,p=Math.floor((g-u)/2+u);g=p;var b=Math.max(1,o-p+1),y=Math.min(o+p,e.length)+t.length,v=Array(y+2);v[y+1]=(1<<h)-1;for(var _=y;_>=b;_--){var w=l[e.charAt(_-1)];if(v[_]=0===h?(v[_+1]<<1|1)&w:(v[_+1]<<1|1)&w|(f[_+1]|f[_])<<1|1|f[_+1],v[_]&m){var k=s(h,_-1);if(k<=c){if(c=k,!((d=_-1)>o))break;b=Math.max(1,2*o-d)}}}if(s(h+1,o)>c)break;f=v}return!(d<0)}},8200:function(e){e.exports=function(e,t){var n=e.getAttribute&&e.getAttribute(t)||null;if(!n)for(var r=e.attributes,a=r.length,i=0;i<a;i++)void 0!==r[i]&&r[i].nodeName===t&&(n=r[i].nodeValue);return n}},378:function(e){e.exports=function(e,t,n,r){return(r=r||{}).test&&r.getElementsByClassName||!r.test&&document.getElementsByClassName?function(e,t,n){return n?e.getElementsByClassName(t)[0]:e.getElementsByClassName(t)}(e,t,n):r.test&&r.querySelector||!r.test&&document.querySelector?function(e,t,n){return t="."+t,n?e.querySelector(t):e.querySelectorAll(t)}(e,t,n):function(e,t,n){for(var r=[],a=e.getElementsByTagName("*"),i=a.length,o=new RegExp("(^|\\s)"+t+"(\\s|$)"),l=0,s=0;l<i;l++)if(o.test(a[l].className)){if(n)return a[l];r[s]=a[l],s++}return r}(e,t,n)}},2859:function(e){var t=[].indexOf;e.exports=function(e,n){if(t)return e.indexOf(n);for(var r=0,a=e.length;r<a;++r)if(e[r]===n)return r;return-1}},9212:function(e){e.exports=function(e){if(void 0===e)return[];if(null===e)return[null];if(e===window)return[window];if("string"==typeof e)return[e];if(function(e){return"[object Array]"===Object.prototype.toString.call(e)}(e))return e;if("number"!=typeof e.length)return[e];if("function"==typeof e&&e instanceof Function)return[e];for(var t=[],n=0,r=e.length;n<r;n++)(Object.prototype.hasOwnProperty.call(e,n)||n in e)&&t.push(e[n]);return t.length?t:[]}},8340:function(e){e.exports=function(e){return(e=null===(e=void 0===e?"":e)?"":e).toString()}},2813:function(e){"use strict";var t,n,r=0;function a(e){return e>=48&&e<=57}function i(e,t){for(var i=(e+="").length,o=(t+="").length,l=0,s=0;l<i&&s<o;){var c=e.charCodeAt(l),d=t.charCodeAt(s);if(a(c)){if(!a(d))return c-d;for(var u=l,p=s;48===c&&++u<i;)c=e.charCodeAt(u);for(;48===d&&++p<o;)d=t.charCodeAt(p);for(var m=u,f=p;m<i&&a(e.charCodeAt(m));)++m;for(;f<o&&a(t.charCodeAt(f));)++f;var g=m-u-f+p;if(g)return g;for(;u<m;)if(g=e.charCodeAt(u++)-t.charCodeAt(p++))return g;l=m,s=f}else{if(c!==d)return c<r&&d<r&&-1!==n[c]&&-1!==n[d]?n[c]-n[d]:c-d;++l,++s}}return l>=i&&s<o&&i>=o?-1:s>=o&&l<i&&o>=i?1:i-o}i.caseInsensitive=i.i=function(e,t){return i((""+e).toLowerCase(),(""+t).toLowerCase())},Object.defineProperties(i,{alphabet:{get:function(){return t},set:function(e){n=[];var a=0;if(t=e)for(;a<t.length;a++)n[t.charCodeAt(a)]=a;for(r=n.length,a=0;a<r;a++)void 0===n[a]&&(n[a]=-1)}}}),e.exports=i}},t={};function n(r){var a=t[r];if(void 0!==a)return a.exports;var i=t[r]={exports:{}};return e[r].call(i.exports,i,i.exports,n),i.exports}n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,{a:t}),t},n.d=function(e,t){for(var r in t)n.o(t,r)&&!n.o(e,r)&&Object.defineProperty(e,r,{enumerable:!0,get:t[r]})},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},function(){"use strict";var e=window.wp.data,t=window.wp.dataControls,r=window.wp.apiFetch,a=n.n(r),i=window.wp.url;const o={blockLibrary:{state:{},blocks:[],blockKeywords:[]}},l={getBlocks(e){return e.blockLibrary.blocks},getBlockLibraryState(e){return e.blockLibrary.state},getBlockKeywords(e){return e.blockLibrary.blockKeywords}},s={getBlocks(){return async e=>{let{dispatch:t}=e;const n=await a()({path:"boldblocks/v1/getBlocks"});return n&&n.length&&t({type:"SET_BLOCKS",payload:n}),n}},getBlockKeywords(){return async e=>{let{dispatch:t}=e;const n=await a()({path:"boldblocks/v1/getBlockKeywords"});return n&&n.length&&t({type:"SET_BLOCKS_KEYWORDS",payload:n}),n}}},c={loadFullBlocks(e){return async t=>{let{dispatch:n}=t;if(!e.length)return;const r=await a()({path:(0,i.addQueryArgs)("boldblocks/v1/getFullBlockData",{blockIds:e.join(",")})});return r&&r.length&&n({type:"UPDATE_BLOCKS",payload:r}),r}},setBlockLibraryState(e){return{type:"UPDATE_BLOCK_LIBRARY_STATE",payload:e}}},d={variationLibrary:{state:{},variations:[],variationKeywords:[]}},u={getVariations(e){return e.variationLibrary.variations},getVariationLibraryState(e){return e.variationLibrary.state},getVariationKeywords(e){return e.variationLibrary.variationKeywords}},p={getVariations(){return async e=>{let{dispatch:t}=e;const n=await a()({path:"boldblocks/v1/getVariations"});return n&&n.length&&t({type:"SET_VARIATIONS",payload:n}),n}},getVariationKeywords(){return async e=>{let{dispatch:t}=e;const n=await a()({path:"boldblocks/v1/getVariationKeywords"});return n&&n.length&&t({type:"SET_VARIATIONS_KEYWORDS",payload:n}),n}}},m={loadFullVariations(e){return async t=>{let{dispatch:n}=t;if(!e.length)return;const r=await a()({path:(0,i.addQueryArgs)("boldblocks/v1/getFullVariationData",{variationIds:e.join(",")})});return r&&r.length&&n({type:"UPDATE_VARIATIONS",payload:r}),r}},setVariationLibraryState(e){return{type:"UPDATE_VARIATION_LIBRARY_STATE",payload:e}}},f="boldblocks/block-library",g=(0,e.createReduxStore)(f,{selectors:{...l,...u},actions:{...c,...m},controls:t.controls,reducer:(0,e.combineReducers)({blockLibrary:function(){let e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:o.blockLibrary,t=arguments.length>1?arguments[1]:void 0;switch(t.type){case"SET_BLOCKS":return{...e,blocks:[...t.payload]};case"UPDATE_BLOCKS":const n=t.payload.map((e=>{let{id:t}=e;return t})),r=e.blocks.map((e=>{if(n.includes(e.id)){const n=t.payload.find((t=>{let{id:n}=t;return n===e.id}));if(n)return n}return e}));return{...e,blocks:r};case"UPDATE_BLOCK_LIBRARY_STATE":return{...e,state:{...t.payload}};case"SET_BLOCKS_KEYWORDS":return{...e,blockKeywords:[...t.payload]}}return e},variationLibrary:function(){let e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:d.variationLibrary,t=arguments.length>1?arguments[1]:void 0;switch(t.type){case"SET_VARIATIONS":return{...e,variations:[...t.payload]};case"UPDATE_VARIATIONS":const n=t.payload.map((e=>{let{id:t}=e;return t})),r=e.variations.map((e=>{if(n.includes(e.id)){const n=t.payload.find((t=>{let{id:n}=t;return n===e.id}));if(n)return n}return e}));return{...e,variations:r};case"UPDATE_VARIATION_LIBRARY_STATE":return{...e,state:{...t.payload}};case"SET_VARIATIONS_KEYWORDS":return{...e,variationKeywords:[...t.payload]}}return e}}),resolvers:{...s,...p}});(0,e.register)(g);var h=window.wp.element,b=n(2485),y=n.n(b),v=window.lodash,_=window.wp.domReady,w=n.n(_),k=window.wp.i18n,x=window.wp.components,E=window.wp.blocks,S=window.wp.coreData,T=window.wp.blockLibrary,A=window.wp.blockEditor,C=n(2774),N=n.n(C);const O=function(e){let t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:null;const[n,r]=(0,h.useState)((()=>{try{const n=JSON.parse(localStorage.getItem(e));return(0,v.isNil)(n)?t:n}catch(e){return U(e,"error"),t}}));return[n,t=>{r(t),localStorage.setItem(e,JSON.stringify(t))}]};n(2838);var P=window.React,L=window.wp.primitives,I=((0,P.createElement)(L.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24"},(0,P.createElement)(L.Path,{d:"M15 4H9c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h6c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm.5 14c0 .3-.2.5-.5.5H9c-.3 0-.5-.2-.5-.5V6c0-.3.2-.5.5-.5h6c.3 0 .5.2.5.5v12zm-4.5-.5h2V16h-2v1.5z"})),(0,P.createElement)(L.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24"},(0,P.createElement)(L.Path,{d:"M17 4H7c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm.5 14c0 .3-.2.5-.5.5H7c-.3 0-.5-.2-.5-.5V6c0-.3.2-.5.5-.5h10c.3 0 .5.2.5.5v12zm-7.5-.5h4V16h-4v1.5z"})),(0,P.createElement)(L.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24"},(0,P.createElement)(L.Path,{d:"M20.5 16h-.7V8c0-1.1-.9-2-2-2H6.2c-1.1 0-2 .9-2 2v8h-.7c-.8 0-1.5.7-1.5 1.5h20c0-.8-.7-1.5-1.5-1.5zM5.7 8c0-.3.2-.5.5-.5h11.6c.3 0 .5.2.5.5v7.6H5.7V8z"})),window.wp.hooks);const F="Mobile",R="Tablet",M="Desktop",B={},D=getComputedStyle(document.documentElement);B[F]=D.getPropertyValue("--wp--custom--breakpoint--sm")||"576px",B[R]=D.getPropertyValue("--wp--custom--breakpoint--md")||"768px",B[M]=D.getPropertyValue("--wp--custom--breakpoint--lg")||"1024px";const z={};Object.keys(B).map((e=>{z[e]=e===F?"":`@media (min-width: ${B[e]})`})),(0,k.__)("Mobile","content-blocks-builder"),z[F],(0,k.__)("Tablet","content-blocks-builder"),z[R],(0,k.__)("Desktop","content-blocks-builder"),z[M];const U=function(e){let t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"log";e&&"development"===window?.BBLOG?.environmentType&&(["log","info","warn","error","debug","dir","table"].includes(t)?console[t](e):console.log(e))},H={previewModes:{}},j={getPreviewMode(e,t){var n;return null!==(n=e.previewModes[t])&&void 0!==n?n:""}},V={setPreviewMode(e){return{type:"SET_PREVIEW_MODE",payload:e}}},$={patternInserter:{status:!1,modalState:{},patterns:[],patternKeywords:[],missingBlocks:{},missingBlocksStatuses:{},plugins:[]}},G={getPatternInserterModalStatus(e){return e.patternInserter.status},getPatterns(e){return e.patternInserter.patterns},getPatternsModalState(e){return e.patternInserter.modalState},getPatternKeywords(e){return e.patternInserter.patternKeywords},getMissingBlock(e,t){var n;return null!==(n=e.patternInserter.missingBlocks[t])&&void 0!==n&&n},getMissingBlockStatus(e,t){var n;return null!==(n=e.patternInserter.missingBlocksStatuses[t])&&void 0!==n&&n},getPlugins(e){return e.patternInserter.plugins}},K={getPatterns(){return async e=>{let{dispatch:t}=e;const n=await a()({path:"boldblocks/v1/getPatterns"});return n&&n.length&&t({type:"SET_PATTERNS",payload:n}),n}},getPatternKeywords(){return async e=>{let{dispatch:t}=e;const n=await a()({path:"boldblocks/v1/getPatternKeywords"});return n&&n.length&&t({type:"SET_PATTERN_KEYWORDS",payload:n}),n}},getPlugins(){return async e=>{let{dispatch:t}=e,n=await a()({path:"wp/v2/plugins"});n=n.map((e=>{const{plugin:t}=e,n=t.split("/")[0];return{...e,slug:n}})),t({type:"SET_PLUGINS",payload:n})}}},W={setPatternInserterModalStatus(e){return{type:"SET_PATTERN_INSERTER_MODAL_STATUS",payload:e}},loadFullPatterns(e){return async t=>{let{dispatch:n}=t;if(!e.length)return;const r=await a()({path:(0,i.addQueryArgs)("boldblocks/v1/getFullPatternData",{patternIds:e.join(",")})});return r&&r.length&&n({type:"UPDATE_PATTERNS",payload:r}),r}},setPatternsModalState(e){return{type:"UPDATE_PATTERN_INSERTER_MODAL_STATE",payload:e}},setMissingBlockStatus(e){return{type:"SET_MISSING_BLOCK_STATUS",payload:e}},loadMissingBlock(e){return async t=>{let{select:n,dispatch:r}=t,i=n.getMissingBlock(e);var o;!1===i&&(i=null!==(o=(await a()({path:`wp/v2/block-directory/search?term=${e}`}))[0])&&void 0!==o?o:{},r({type:"SET_MISSING_BLOCK",payload:{[e]:i}}));return i}}},q={typography:{fonts:{body:{fontFamily:"Nunito",genericFamily:"sans-serif",fontVariants:[]},headings:{fontFamily:"Roboto",genericFamily:"sans-serif",fontVariants:[]},additionalFonts:[]},fontsPresets:[{body:{fontFamily:"Nunito",genericFamily:"sans-serif"},headings:{fontFamily:"Roboto",genericFamily:"sans-serif"}},{body:{fontFamily:"Montserrat",genericFamily:"sans-serif"},headings:{fontFamily:"Oswald",genericFamily:"sans-serif"}},{body:{fontFamily:"Merriweather",genericFamily:"serif"},headings:{fontFamily:"Oswald",genericFamily:"sans-serif"}},{body:{fontFamily:"Montserrat",genericFamily:"sans-serif"},headings:{fontFamily:"Source Sans Pro",genericFamily:"sans-serif"}},{body:{fontFamily:"Source Sans Pro",genericFamily:"sans-serif"},headings:{fontFamily:"Libre Baskerville",genericFamily:"serif"}},{body:{fontFamily:"Fauna One",genericFamily:"serif"},headings:{fontFamily:"Playfair Display",genericFamily:"serif"}},{body:{fontFamily:"Josefin Slab",genericFamily:"serif"},headings:{fontFamily:"Six Caps",genericFamily:"sans-serif"}},{body:{fontFamily:"Source Sans Pro",genericFamily:"sans-serif"},headings:{fontFamily:"Playfair Display",genericFamily:"serif"}},{body:{fontFamily:"Quattrocento",genericFamily:"serif"},headings:{fontFamily:"Oswald",genericFamily:"sans-serif"}},{body:{fontFamily:"Alice",genericFamily:"serif"},headings:{fontFamily:"Sacramento",genericFamily:"cursive"}},{body:{fontFamily:"Lato",genericFamily:"sans-serif"},headings:{fontFamily:"Arvo",genericFamily:"serif"}},{body:{fontFamily:"Poppins",genericFamily:"sans-serif"},headings:{fontFamily:"Abril Fatface",genericFamily:"cursive"}},{body:{fontFamily:"Inconsolata",genericFamily:"monospace"},headings:{fontFamily:"Karla",genericFamily:"sans-serif"}},{body:{fontFamily:"Andika",genericFamily:"sans-serif"},headings:{fontFamily:"Amatic SC",genericFamily:"sans-serif"}},{body:{fontFamily:"Lato",genericFamily:"sans-serif"},headings:{fontFamily:"Lustria",genericFamily:"serif"}},{body:{fontFamily:"Proza Libre",genericFamily:"sans-serif"},headings:{fontFamily:"Cormorant Garamond",genericFamily:"serif"}},{body:{fontFamily:"EB Garamond",genericFamily:"serif"},headings:{fontFamily:"Oswald",genericFamily:"sans-serif"}},{body:{fontFamily:"Josefin Sans",genericFamily:"sans-serif"},headings:{fontFamily:"Yeseva One",genericFamily:"cursive"}},{body:{fontFamily:"Inter",genericFamily:"sans-serif"},headings:{fontFamily:"EB Garamond",genericFamily:"serif"}}],googleFonts:[]},postTypography:{fonts:null}},Y={getGoogleFonts(e){return e.typography.googleFonts},getTypography(e){return{fonts:e.typography.fonts,fontsPresets:e.typography.fontsPresets}},getPostTypography(e,t){return{fonts:e.postTypography.fonts,fontsPresets:e.typography.fontsPresets}}},J={getGoogleFonts(){return async e=>{let{dispatch:t}=e;const n=await a()({path:"boldblocks/v1/getGoogleFonts"});return n&&n.success&&t({type:"SET_GOOGLE_FONTS",payload:n.data}),n}},getTypography(){return async e=>{let{dispatch:t}=e;const{BoldBlocksTypography:n}=await a()({path:"wp/v2/settings"});if(n)return X(n,t);{const{BoldBlocksTypography:e}=await a()({path:"wp/v2/settings",method:"POST",data:{BoldBlocksTypography:{fonts:JSON.stringify(q.typography.fonts)}}});return X(e,t)}}},getPostTypography(e){return async t=>{let{dispatch:n}=t;if(!e)return;const{meta:{BoldBlocksTypography:r}={}}=await a()({path:e});return Z(r,n)}}},X=(e,t)=>{if(e&&e?.fonts){const n=JSON.parse(e.fonts);return t({type:"UPDATE_FONTS",payload:n}),n}return e},Z=(e,t)=>{let n;return e&&e?.fonts&&(n=JSON.parse(e.fonts)),t({type:"UPDATE_POST_FONTS",payload:n}),n},Q={updateFonts(e){return{type:"UPDATE_FONTS",payload:e}},updatePostFonts(e){return{type:"UPDATE_POST_FONTS",payload:e}},updateAndPersistFonts(e){return async t=>{let{dispatch:n}=t;const{BoldBlocksTypography:r}=await a()({path:"wp/v2/settings",method:"POST",data:{BoldBlocksTypography:{fonts:e}}});return X(r,n)}},updateAndPersistPostFonts(e,t){return async n=>{let{dispatch:r}=n;const{meta:{BoldBlocksTypography:i}={}}=await a()({path:t,method:"POST",data:{meta:{BoldBlocksTypography:{fonts:e}}}});return Z(i,r)}}};(t=>{const n=(0,e.createReduxStore)("boldblocks/cbb-icon-library",{selectors:{getIconLibrary(e){var t;return null!==(t=e?.icons)&&void 0!==t?t:[]}},actions:{loadIconLibrary(e){return async t=>{var n;let{select:r,dispatch:i}=t;if(!e)return;let o=r.getIconLibrary();if(o&&o.length)return o;const l=await a()({path:e});var s;return l?.success&&i({type:"UPDATE_ICONS",payload:null!==(s=l?.data)&&void 0!==s?s:[]}),null!==(n=l?.data)&&void 0!==n?n:[]}}},reducer:function(){let e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:{icons:[]},t=arguments.length>1?arguments[1]:void 0;return"UPDATE_ICONS"===t.type?{...e,icons:t.payload}:e}});(0,e.register)(n)})();const ee=(0,e.createReduxStore)("boldblocks/data",{selectors:{...Y,...G,...j},actions:{...Q,...W,...V},controls:t.controls,reducer:(0,e.combineReducers)({previewModes:function(){let e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:H.previewModes,t=arguments.length>1?arguments[1]:void 0;return"SET_PREVIEW_MODE"===t.type?{...e,[t.payload.clientId]:t.payload.previewMode}:e},typography:function(){let e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:q.typography,t=arguments.length>1?arguments[1]:void 0;switch(t.type){case"SET_GOOGLE_FONTS":return{...e,googleFonts:t.payload};case"UPDATE_FONTS":return{...e,fonts:t.payload}}return e},postTypography:function(){let e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:q.postTypography,t=arguments.length>1?arguments[1]:void 0;return"UPDATE_POST_FONTS"===t.type?{...e,fonts:t.payload}:e},patternInserter:function(){let e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:$.patternInserter,t=arguments.length>1?arguments[1]:void 0;switch(t.type){case"SET_PATTERN_INSERTER_MODAL_STATUS":return{...e,status:t.payload};case"SET_PATTERNS":return{...e,patterns:[...t.payload]};case"UPDATE_PATTERNS":const n=t.payload.map((e=>{let{id:t}=e;return t})),r=e.patterns.map((e=>{if(n.includes(e.id)){const n=t.payload.find((t=>{let{id:n}=t;return n===e.id}));if(n)return n}return e}));return{...e,patterns:r};case"UPDATE_PATTERN_INSERTER_MODAL_STATE":return{...e,modalState:{...t.payload}};case"SET_PATTERN_KEYWORDS":return{...e,patternKeywords:[...t.payload]};case"SET_MISSING_BLOCK":return{...e,missingBlocks:{...e.missingBlocks,...t.payload}};case"SET_MISSING_BLOCK_STATUS":return{...e,missingBlocksStatuses:{...e.missingBlocksStatuses,[t.payload]:!0}};case"SET_PLUGINS":return{...e,plugins:[...t.payload]}}return e}}),resolvers:{...J,...K}});(0,e.register)(ee),n(5531),window.wp.a11y,window.wp.notices;const te=["boldblocks/group","boldblocks/grid-item","boldblocks/grid-item-repeater","boldblocks/carousel-item","boldblocks/carousel-item-repeater","boldblocks/stack-item","boldblocks/stack-item-repeater","boldblocks/accordion-item","boldblocks/accordion-item-repeater"];(0,k.__)("Responsive width","content-blocks-builder"),(0,k.__)("Responsive height","content-blocks-builder"),(0,k.__)("Responsive spacing","content-blocks-builder"),(0,k.__)("Responsive border","content-blocks-builder"),(0,k.__)("Media background","content-blocks-builder"),(0,k.__)("Background overlay","content-blocks-builder"),(0,k.__)("Text alignment","content-blocks-builder"),(0,k.__)("Vertical alignment","content-blocks-builder"),(0,k.__)("Justify alignment","content-blocks-builder"),(0,k.__)("Aspect ratio","content-blocks-builder"),(0,k.__)("Box shadow","content-blocks-builder"),(0,k.__)("Transform","content-blocks-builder"),(0,k.__)("Visibility","content-blocks-builder"),(0,k.__)("Toggle content","content-blocks-builder"),(0,k.__)("Sticky content","content-blocks-builder"),(0,k.__)("Custom attributes","content-blocks-builder"),(0,k.__)("Animation (premium)","content-blocks-builder"),(0,k.__)("Custom CSS (premium)","content-blocks-builder"),(0,k.__)("Steps style (premium)","content-blocks-builder");const ne=(0,I.applyFilters)("boldblocks.CBB.isPremium",!1),re=function(e){let t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:[];if(!e.length)return t;for(let n=0;n<e.length;n++){const{name:r,innerBlocks:a=[],attributes:{originalName:i=""}={}}=e[n];let o="core/missing"===r?i:r;-1===t.indexOf(o)&&t.push(o),t=re(a,t)}return t},ae=function(t){let n=arguments.length>1&&void 0!==arguments[1]?arguments[1]:[],r=arguments.length>2&&void 0!==arguments[2]?arguments[2]:ee;return(0,e.useSelect)((e=>e(r).isResolving(t,n)),[])},ie=function(t){let n=arguments.length>1&&void 0!==arguments[1]?arguments[1]:[],r=arguments.length>2&&void 0!==arguments[2]?arguments[2]:ee;return(0,e.useSelect)((e=>e(r).hasFinishedResolution(t,n)),[])},oe=((0,h.createContext)(),(e,t)=>{let n=[];if(!e.length||!t.length)return t;const[r,...a]=e;return n=t.filter((e=>{let{keywordIds:t}=e;return t.indexOf(r.id)>-1})),oe(a,n)}),le=(0,h.createContext)(),se="bb-library-state",ce={pageSize:6,currentPage:1,sortType:"featured",selectedKeywords:[],isOpenHelp:!1,insertingItem:"",importedItems:[],isReloading:!1,installingPlugins:[],activatingPlugins:[],contentType:"block"},de=(e,t)=>"UPDATE_STATE"===t.type?{...e,...t.payload}:e,ue=(e,t)=>t.find((t=>e===t.slug)),pe=e=>{let t=[];if(!e.length)return t;for(let n=0;n<e.length;n++){let[r,a,i]=e[n];i=pe(i),t.push({name:r,attributes:a,innerBlocks:i})}return t};function me(){return me=Object.assign?Object.assign.bind():function(e){for(var t=1;t<arguments.length;t++){var n=arguments[t];for(var r in n)Object.prototype.hasOwnProperty.call(n,r)&&(e[r]=n[r])}return e},me.apply(this,arguments)}function fe(e){var t=Object.create(null);return function(n){return void 0===t[n]&&(t[n]=e(n)),t[n]}}var ge=/^((children|dangerouslySetInnerHTML|key|ref|autoFocus|defaultValue|defaultChecked|innerHTML|suppressContentEditableWarning|suppressHydrationWarning|valueLink|abbr|accept|acceptCharset|accessKey|action|allow|allowUserMedia|allowPaymentRequest|allowFullScreen|allowTransparency|alt|async|autoComplete|autoPlay|capture|cellPadding|cellSpacing|challenge|charSet|checked|cite|classID|className|cols|colSpan|content|contentEditable|contextMenu|controls|controlsList|coords|crossOrigin|data|dateTime|decoding|default|defer|dir|disabled|disablePictureInPicture|download|draggable|encType|enterKeyHint|form|formAction|formEncType|formMethod|formNoValidate|formTarget|frameBorder|headers|height|hidden|high|href|hrefLang|htmlFor|httpEquiv|id|inputMode|integrity|is|keyParams|keyType|kind|label|lang|list|loading|loop|low|marginHeight|marginWidth|max|maxLength|media|mediaGroup|method|min|minLength|multiple|muted|name|nonce|noValidate|open|optimum|pattern|placeholder|playsInline|poster|preload|profile|radioGroup|readOnly|referrerPolicy|rel|required|reversed|role|rows|rowSpan|sandbox|scope|scoped|scrolling|seamless|selected|shape|size|sizes|slot|span|spellCheck|src|srcDoc|srcLang|srcSet|start|step|style|summary|tabIndex|target|title|translate|type|useMap|value|width|wmode|wrap|about|datatype|inlist|prefix|property|resource|typeof|vocab|autoCapitalize|autoCorrect|autoSave|color|incremental|fallback|inert|itemProp|itemScope|itemType|itemID|itemRef|on|option|results|security|unselectable|accentHeight|accumulate|additive|alignmentBaseline|allowReorder|alphabetic|amplitude|arabicForm|ascent|attributeName|attributeType|autoReverse|azimuth|baseFrequency|baselineShift|baseProfile|bbox|begin|bias|by|calcMode|capHeight|clip|clipPathUnits|clipPath|clipRule|colorInterpolation|colorInterpolationFilters|colorProfile|colorRendering|contentScriptType|contentStyleType|cursor|cx|cy|d|decelerate|descent|diffuseConstant|direction|display|divisor|dominantBaseline|dur|dx|dy|edgeMode|elevation|enableBackground|end|exponent|externalResourcesRequired|fill|fillOpacity|fillRule|filter|filterRes|filterUnits|floodColor|floodOpacity|focusable|fontFamily|fontSize|fontSizeAdjust|fontStretch|fontStyle|fontVariant|fontWeight|format|from|fr|fx|fy|g1|g2|glyphName|glyphOrientationHorizontal|glyphOrientationVertical|glyphRef|gradientTransform|gradientUnits|hanging|horizAdvX|horizOriginX|ideographic|imageRendering|in|in2|intercept|k|k1|k2|k3|k4|kernelMatrix|kernelUnitLength|kerning|keyPoints|keySplines|keyTimes|lengthAdjust|letterSpacing|lightingColor|limitingConeAngle|local|markerEnd|markerMid|markerStart|markerHeight|markerUnits|markerWidth|mask|maskContentUnits|maskUnits|mathematical|mode|numOctaves|offset|opacity|operator|order|orient|orientation|origin|overflow|overlinePosition|overlineThickness|panose1|paintOrder|pathLength|patternContentUnits|patternTransform|patternUnits|pointerEvents|points|pointsAtX|pointsAtY|pointsAtZ|preserveAlpha|preserveAspectRatio|primitiveUnits|r|radius|refX|refY|renderingIntent|repeatCount|repeatDur|requiredExtensions|requiredFeatures|restart|result|rotate|rx|ry|scale|seed|shapeRendering|slope|spacing|specularConstant|specularExponent|speed|spreadMethod|startOffset|stdDeviation|stemh|stemv|stitchTiles|stopColor|stopOpacity|strikethroughPosition|strikethroughThickness|string|stroke|strokeDasharray|strokeDashoffset|strokeLinecap|strokeLinejoin|strokeMiterlimit|strokeOpacity|strokeWidth|surfaceScale|systemLanguage|tableValues|targetX|targetY|textAnchor|textDecoration|textRendering|textLength|to|transform|u1|u2|underlinePosition|underlineThickness|unicode|unicodeBidi|unicodeRange|unitsPerEm|vAlphabetic|vHanging|vIdeographic|vMathematical|values|vectorEffect|version|vertAdvY|vertOriginX|vertOriginY|viewBox|viewTarget|visibility|widths|wordSpacing|writingMode|x|xHeight|x1|x2|xChannelSelector|xlinkActuate|xlinkArcrole|xlinkHref|xlinkRole|xlinkShow|xlinkTitle|xlinkType|xmlBase|xmlns|xmlnsXlink|xmlLang|xmlSpace|y|y1|y2|yChannelSelector|z|zoomAndPan|for|class|autofocus)|(([Dd][Aa][Tt][Aa]|[Aa][Rr][Ii][Aa]|x)-.*))$/,he=fe((function(e){return ge.test(e)||111===e.charCodeAt(0)&&110===e.charCodeAt(1)&&e.charCodeAt(2)<91})),be=function(){function e(e){var t=this;this._insertTag=function(e){var n;n=0===t.tags.length?t.insertionPoint?t.insertionPoint.nextSibling:t.prepend?t.container.firstChild:t.before:t.tags[t.tags.length-1].nextSibling,t.container.insertBefore(e,n),t.tags.push(e)},this.isSpeedy=void 0===e.speedy||e.speedy,this.tags=[],this.ctr=0,this.nonce=e.nonce,this.key=e.key,this.container=e.container,this.prepend=e.prepend,this.insertionPoint=e.insertionPoint,this.before=null}var t=e.prototype;return t.hydrate=function(e){e.forEach(this._insertTag)},t.insert=function(e){this.ctr%(this.isSpeedy?65e3:1)==0&&this._insertTag(function(e){var t=document.createElement("style");return t.setAttribute("data-emotion",e.key),void 0!==e.nonce&&t.setAttribute("nonce",e.nonce),t.appendChild(document.createTextNode("")),t.setAttribute("data-s",""),t}(this));var t=this.tags[this.tags.length-1];if(this.isSpeedy){var n=function(e){if(e.sheet)return e.sheet;for(var t=0;t<document.styleSheets.length;t++)if(document.styleSheets[t].ownerNode===e)return document.styleSheets[t]}(t);try{n.insertRule(e,n.cssRules.length)}catch(e){}}else t.appendChild(document.createTextNode(e));this.ctr++},t.flush=function(){this.tags.forEach((function(e){return e.parentNode&&e.parentNode.removeChild(e)})),this.tags=[],this.ctr=0},e}(),ye=Math.abs,ve=String.fromCharCode,_e=Object.assign;function we(e){return e.trim()}function ke(e,t,n){return e.replace(t,n)}function xe(e,t){return e.indexOf(t)}function Ee(e,t){return 0|e.charCodeAt(t)}function Se(e,t,n){return e.slice(t,n)}function Te(e){return e.length}function Ae(e){return e.length}function Ce(e,t){return t.push(e),e}var Ne=1,Oe=1,Pe=0,Le=0,Ie=0,Fe="";function Re(e,t,n,r,a,i,o){return{value:e,root:t,parent:n,type:r,props:a,children:i,line:Ne,column:Oe,length:o,return:""}}function Me(e,t){return _e(Re("",null,null,"",null,null,0),e,{length:-e.length},t)}function Be(){return Ie=Le>0?Ee(Fe,--Le):0,Oe--,10===Ie&&(Oe=1,Ne--),Ie}function De(){return Ie=Le<Pe?Ee(Fe,Le++):0,Oe++,10===Ie&&(Oe=1,Ne++),Ie}function ze(){return Ee(Fe,Le)}function Ue(){return Le}function He(e,t){return Se(Fe,e,t)}function je(e){switch(e){case 0:case 9:case 10:case 13:case 32:return 5;case 33:case 43:case 44:case 47:case 62:case 64:case 126:case 59:case 123:case 125:return 4;case 58:return 3;case 34:case 39:case 40:case 91:return 2;case 41:case 93:return 1}return 0}function Ve(e){return Ne=Oe=1,Pe=Te(Fe=e),Le=0,[]}function $e(e){return Fe="",e}function Ge(e){return we(He(Le-1,qe(91===e?e+2:40===e?e+1:e)))}function Ke(e){for(;(Ie=ze())&&Ie<33;)De();return je(e)>2||je(Ie)>3?"":" "}function We(e,t){for(;--t&&De()&&!(Ie<48||Ie>102||Ie>57&&Ie<65||Ie>70&&Ie<97););return He(e,Ue()+(t<6&&32==ze()&&32==De()))}function qe(e){for(;De();)switch(Ie){case e:return Le;case 34:case 39:34!==e&&39!==e&&qe(Ie);break;case 40:41===e&&qe(e);break;case 92:De()}return Le}function Ye(e,t){for(;De()&&e+Ie!==57&&(e+Ie!==84||47!==ze()););return"/*"+He(t,Le-1)+"*"+ve(47===e?e:De())}function Je(e){for(;!je(ze());)De();return He(e,Le)}var Xe="-ms-",Ze="-moz-",Qe="-webkit-",et="comm",tt="rule",nt="decl",rt="@keyframes";function at(e,t){for(var n="",r=Ae(e),a=0;a<r;a++)n+=t(e[a],a,e,t)||"";return n}function it(e,t,n,r){switch(e.type){case"@layer":if(e.children.length)break;case"@import":case nt:return e.return=e.return||e.value;case et:return"";case rt:return e.return=e.value+"{"+at(e.children,r)+"}";case tt:e.value=e.props.join(",")}return Te(n=at(e.children,r))?e.return=e.value+"{"+n+"}":""}function ot(e){return $e(lt("",null,null,null,[""],e=Ve(e),0,[0],e))}function lt(e,t,n,r,a,i,o,l,s){for(var c=0,d=0,u=o,p=0,m=0,f=0,g=1,h=1,b=1,y=0,v="",_=a,w=i,k=r,x=v;h;)switch(f=y,y=De()){case 40:if(108!=f&&58==Ee(x,u-1)){-1!=xe(x+=ke(Ge(y),"&","&\f"),"&\f")&&(b=-1);break}case 34:case 39:case 91:x+=Ge(y);break;case 9:case 10:case 13:case 32:x+=Ke(f);break;case 92:x+=We(Ue()-1,7);continue;case 47:switch(ze()){case 42:case 47:Ce(ct(Ye(De(),Ue()),t,n),s);break;default:x+="/"}break;case 123*g:l[c++]=Te(x)*b;case 125*g:case 59:case 0:switch(y){case 0:case 125:h=0;case 59+d:-1==b&&(x=ke(x,/\f/g,"")),m>0&&Te(x)-u&&Ce(m>32?dt(x+";",r,n,u-1):dt(ke(x," ","")+";",r,n,u-2),s);break;case 59:x+=";";default:if(Ce(k=st(x,t,n,c,d,a,l,v,_=[],w=[],u),i),123===y)if(0===d)lt(x,t,k,k,_,i,u,l,w);else switch(99===p&&110===Ee(x,3)?100:p){case 100:case 108:case 109:case 115:lt(e,k,k,r&&Ce(st(e,k,k,0,0,a,l,v,a,_=[],u),w),a,w,u,l,r?_:w);break;default:lt(x,k,k,k,[""],w,0,l,w)}}c=d=m=0,g=b=1,v=x="",u=o;break;case 58:u=1+Te(x),m=f;default:if(g<1)if(123==y)--g;else if(125==y&&0==g++&&125==Be())continue;switch(x+=ve(y),y*g){case 38:b=d>0?1:(x+="\f",-1);break;case 44:l[c++]=(Te(x)-1)*b,b=1;break;case 64:45===ze()&&(x+=Ge(De())),p=ze(),d=u=Te(v=x+=Je(Ue())),y++;break;case 45:45===f&&2==Te(x)&&(g=0)}}return i}function st(e,t,n,r,a,i,o,l,s,c,d){for(var u=a-1,p=0===a?i:[""],m=Ae(p),f=0,g=0,h=0;f<r;++f)for(var b=0,y=Se(e,u+1,u=ye(g=o[f])),v=e;b<m;++b)(v=we(g>0?p[b]+" "+y:ke(y,/&\f/g,p[b])))&&(s[h++]=v);return Re(e,t,n,0===a?tt:l,s,c,d)}function ct(e,t,n){return Re(e,t,n,et,ve(Ie),Se(e,2,-2),0)}function dt(e,t,n,r){return Re(e,t,n,nt,Se(e,0,r),Se(e,r+1,-1),r)}var ut=function(e,t,n){for(var r=0,a=0;r=a,a=ze(),38===r&&12===a&&(t[n]=1),!je(a);)De();return He(e,Le)},pt=new WeakMap,mt=function(e){if("rule"===e.type&&e.parent&&!(e.length<1)){for(var t=e.value,n=e.parent,r=e.column===n.column&&e.line===n.line;"rule"!==n.type;)if(!(n=n.parent))return;if((1!==e.props.length||58===t.charCodeAt(0)||pt.get(n))&&!r){pt.set(e,!0);for(var a=[],i=function(e,t){return $e(function(e,t){var n=-1,r=44;do{switch(je(r)){case 0:38===r&&12===ze()&&(t[n]=1),e[n]+=ut(Le-1,t,n);break;case 2:e[n]+=Ge(r);break;case 4:if(44===r){e[++n]=58===ze()?"&\f":"",t[n]=e[n].length;break}default:e[n]+=ve(r)}}while(r=De());return e}(Ve(e),t))}(t,a),o=n.props,l=0,s=0;l<i.length;l++)for(var c=0;c<o.length;c++,s++)e.props[s]=a[l]?i[l].replace(/&\f/g,o[c]):o[c]+" "+i[l]}}},ft=function(e){if("decl"===e.type){var t=e.value;108===t.charCodeAt(0)&&98===t.charCodeAt(2)&&(e.return="",e.value="")}};function gt(e,t){switch(function(e,t){return 45^Ee(e,0)?(((t<<2^Ee(e,0))<<2^Ee(e,1))<<2^Ee(e,2))<<2^Ee(e,3):0}(e,t)){case 5103:return Qe+"print-"+e+e;case 5737:case 4201:case 3177:case 3433:case 1641:case 4457:case 2921:case 5572:case 6356:case 5844:case 3191:case 6645:case 3005:case 6391:case 5879:case 5623:case 6135:case 4599:case 4855:case 4215:case 6389:case 5109:case 5365:case 5621:case 3829:return Qe+e+e;case 5349:case 4246:case 4810:case 6968:case 2756:return Qe+e+Ze+e+Xe+e+e;case 6828:case 4268:return Qe+e+Xe+e+e;case 6165:return Qe+e+Xe+"flex-"+e+e;case 5187:return Qe+e+ke(e,/(\w+).+(:[^]+)/,Qe+"box-$1$2"+Xe+"flex-$1$2")+e;case 5443:return Qe+e+Xe+"flex-item-"+ke(e,/flex-|-self/,"")+e;case 4675:return Qe+e+Xe+"flex-line-pack"+ke(e,/align-content|flex-|-self/,"")+e;case 5548:return Qe+e+Xe+ke(e,"shrink","negative")+e;case 5292:return Qe+e+Xe+ke(e,"basis","preferred-size")+e;case 6060:return Qe+"box-"+ke(e,"-grow","")+Qe+e+Xe+ke(e,"grow","positive")+e;case 4554:return Qe+ke(e,/([^-])(transform)/g,"$1"+Qe+"$2")+e;case 6187:return ke(ke(ke(e,/(zoom-|grab)/,Qe+"$1"),/(image-set)/,Qe+"$1"),e,"")+e;case 5495:case 3959:return ke(e,/(image-set\([^]*)/,Qe+"$1$`$1");case 4968:return ke(ke(e,/(.+:)(flex-)?(.*)/,Qe+"box-pack:$3"+Xe+"flex-pack:$3"),/s.+-b[^;]+/,"justify")+Qe+e+e;case 4095:case 3583:case 4068:case 2532:return ke(e,/(.+)-inline(.+)/,Qe+"$1$2")+e;case 8116:case 7059:case 5753:case 5535:case 5445:case 5701:case 4933:case 4677:case 5533:case 5789:case 5021:case 4765:if(Te(e)-1-t>6)switch(Ee(e,t+1)){case 109:if(45!==Ee(e,t+4))break;case 102:return ke(e,/(.+:)(.+)-([^]+)/,"$1"+Qe+"$2-$3$1"+Ze+(108==Ee(e,t+3)?"$3":"$2-$3"))+e;case 115:return~xe(e,"stretch")?gt(ke(e,"stretch","fill-available"),t)+e:e}break;case 4949:if(115!==Ee(e,t+1))break;case 6444:switch(Ee(e,Te(e)-3-(~xe(e,"!important")&&10))){case 107:return ke(e,":",":"+Qe)+e;case 101:return ke(e,/(.+:)([^;!]+)(;|!.+)?/,"$1"+Qe+(45===Ee(e,14)?"inline-":"")+"box$3$1"+Qe+"$2$3$1"+Xe+"$2box$3")+e}break;case 5936:switch(Ee(e,t+11)){case 114:return Qe+e+Xe+ke(e,/[svh]\w+-[tblr]{2}/,"tb")+e;case 108:return Qe+e+Xe+ke(e,/[svh]\w+-[tblr]{2}/,"tb-rl")+e;case 45:return Qe+e+Xe+ke(e,/[svh]\w+-[tblr]{2}/,"lr")+e}return Qe+e+Xe+e+e}return e}var ht=[function(e,t,n,r){if(e.length>-1&&!e.return)switch(e.type){case nt:e.return=gt(e.value,e.length);break;case rt:return at([Me(e,{value:ke(e.value,"@","@"+Qe)})],r);case tt:if(e.length)return function(e,t){return e.map(t).join("")}(e.props,(function(t){switch(function(e,t){return(e=/(::plac\w+|:read-\w+)/.exec(e))?e[0]:e}(t)){case":read-only":case":read-write":return at([Me(e,{props:[ke(t,/:(read-\w+)/,":-moz-$1")]})],r);case"::placeholder":return at([Me(e,{props:[ke(t,/:(plac\w+)/,":"+Qe+"input-$1")]}),Me(e,{props:[ke(t,/:(plac\w+)/,":-moz-$1")]}),Me(e,{props:[ke(t,/:(plac\w+)/,Xe+"input-$1")]})],r)}return""}))}}],bt=function(e){var t=e.key;if("css"===t){var n=document.querySelectorAll("style[data-emotion]:not([data-s])");Array.prototype.forEach.call(n,(function(e){-1!==e.getAttribute("data-emotion").indexOf(" ")&&(document.head.appendChild(e),e.setAttribute("data-s",""))}))}var r,a,i=e.stylisPlugins||ht,o={},l=[];r=e.container||document.head,Array.prototype.forEach.call(document.querySelectorAll('style[data-emotion^="'+t+' "]'),(function(e){for(var t=e.getAttribute("data-emotion").split(" "),n=1;n<t.length;n++)o[t[n]]=!0;l.push(e)}));var s,c,d,u,p=[it,(u=function(e){s.insert(e)},function(e){e.root||(e=e.return)&&u(e)})],m=(c=[mt,ft].concat(i,p),d=Ae(c),function(e,t,n,r){for(var a="",i=0;i<d;i++)a+=c[i](e,t,n,r)||"";return a});a=function(e,t,n,r){s=n,at(ot(e?e+"{"+t.styles+"}":t.styles),m),r&&(f.inserted[t.name]=!0)};var f={key:t,sheet:new be({key:t,container:r,nonce:e.nonce,speedy:e.speedy,prepend:e.prepend,insertionPoint:e.insertionPoint}),nonce:e.nonce,inserted:o,registered:{},insert:a};return f.sheet.hydrate(l),f},yt={animationIterationCount:1,aspectRatio:1,borderImageOutset:1,borderImageSlice:1,borderImageWidth:1,boxFlex:1,boxFlexGroup:1,boxOrdinalGroup:1,columnCount:1,columns:1,flex:1,flexGrow:1,flexPositive:1,flexShrink:1,flexNegative:1,flexOrder:1,gridRow:1,gridRowEnd:1,gridRowSpan:1,gridRowStart:1,gridColumn:1,gridColumnEnd:1,gridColumnSpan:1,gridColumnStart:1,msGridRow:1,msGridRowSpan:1,msGridColumn:1,msGridColumnSpan:1,fontWeight:1,lineHeight:1,opacity:1,order:1,orphans:1,tabSize:1,widows:1,zIndex:1,zoom:1,WebkitLineClamp:1,fillOpacity:1,floodOpacity:1,stopOpacity:1,strokeDasharray:1,strokeDashoffset:1,strokeMiterlimit:1,strokeOpacity:1,strokeWidth:1},vt=/[A-Z]|^ms/g,_t=/_EMO_([^_]+?)_([^]*?)_EMO_/g,wt=function(e){return 45===e.charCodeAt(1)},kt=function(e){return null!=e&&"boolean"!=typeof e},xt=fe((function(e){return wt(e)?e:e.replace(vt,"-$&").toLowerCase()})),Et=function(e,t){switch(e){case"animation":case"animationName":if("string"==typeof t)return t.replace(_t,(function(e,t,n){return Tt={name:t,styles:n,next:Tt},t}))}return 1===yt[e]||wt(e)||"number"!=typeof t||0===t?t:t+"px"};function St(e,t,n){if(null==n)return"";if(void 0!==n.__emotion_styles)return n;switch(typeof n){case"boolean":return"";case"object":if(1===n.anim)return Tt={name:n.name,styles:n.styles,next:Tt},n.name;if(void 0!==n.styles){var r=n.next;if(void 0!==r)for(;void 0!==r;)Tt={name:r.name,styles:r.styles,next:Tt},r=r.next;return n.styles+";"}return function(e,t,n){var r="";if(Array.isArray(n))for(var a=0;a<n.length;a++)r+=St(e,t,n[a])+";";else for(var i in n){var o=n[i];if("object"!=typeof o)null!=t&&void 0!==t[o]?r+=i+"{"+t[o]+"}":kt(o)&&(r+=xt(i)+":"+Et(i,o)+";");else if(!Array.isArray(o)||"string"!=typeof o[0]||null!=t&&void 0!==t[o[0]]){var l=St(e,t,o);switch(i){case"animation":case"animationName":r+=xt(i)+":"+l+";";break;default:r+=i+"{"+l+"}"}}else for(var s=0;s<o.length;s++)kt(o[s])&&(r+=xt(i)+":"+Et(i,o[s])+";")}return r}(e,t,n);case"function":if(void 0!==e){var a=Tt,i=n(e);return Tt=a,St(e,t,i)}}if(null==t)return n;var o=t[n];return void 0!==o?o:n}var Tt,At=/label:\s*([^\s;\n{]+)\s*(;|$)/g,Ct=!!P.useInsertionEffect&&P.useInsertionEffect,Nt=Ct||function(e){return e()},Ot=(Ct||P.useLayoutEffect,P.createContext("undefined"!=typeof HTMLElement?bt({key:"css"}):null));Ot.Provider;var Pt=P.createContext({}),Lt=function(e,t,n){var r=e.key+"-"+t.name;!1===n&&void 0===e.registered[r]&&(e.registered[r]=t.styles)},It=he,Ft=function(e){return"theme"!==e},Rt=function(e){return"string"==typeof e&&e.charCodeAt(0)>96?It:Ft},Mt=function(e,t,n){var r;if(t){var a=t.shouldForwardProp;r=e.__emotion_forwardProp&&a?function(t){return e.__emotion_forwardProp(t)&&a(t)}:a}return"function"!=typeof r&&n&&(r=e.__emotion_forwardProp),r},Bt=function(e){var t=e.cache,n=e.serialized,r=e.isStringTag;return Lt(t,n,r),Nt((function(){return function(e,t,n){Lt(e,t,n);var r=e.key+"-"+t.name;if(void 0===e.inserted[t.name]){var a=t;do{e.insert(t===a?"."+r:"",a,e.sheet,!0),a=a.next}while(void 0!==a)}}(t,n,r)})),null},Dt=function e(t,n){var r,a,i=t.__emotion_real===t,o=i&&t.__emotion_base||t;void 0!==n&&(r=n.label,a=n.target);var l=Mt(t,n,i),s=l||Rt(o),c=!s("as");return function(){var d=arguments,u=i&&void 0!==t.__emotion_styles?t.__emotion_styles.slice(0):[];if(void 0!==r&&u.push("label:"+r+";"),null==d[0]||void 0===d[0].raw)u.push.apply(u,d);else{u.push(d[0][0]);for(var p=d.length,m=1;m<p;m++)u.push(d[m],d[0][m])}var f,g=(f=function(e,t,n){var r,i,d,p,m=c&&e.as||o,f="",g=[],h=e;if(null==e.theme){for(var b in h={},e)h[b]=e[b];h.theme=P.useContext(Pt)}"string"==typeof e.className?(r=t.registered,i=g,d=e.className,p="",d.split(" ").forEach((function(e){void 0!==r[e]?i.push(r[e]+";"):p+=e+" "})),f=p):null!=e.className&&(f=e.className+" ");var y=function(e,t,n){if(1===e.length&&"object"==typeof e[0]&&null!==e[0]&&void 0!==e[0].styles)return e[0];var r=!0,a="";Tt=void 0;var i=e[0];null==i||void 0===i.raw?(r=!1,a+=St(n,t,i)):a+=i[0];for(var o=1;o<e.length;o++)a+=St(n,t,e[o]),r&&(a+=i[o]);At.lastIndex=0;for(var l,s="";null!==(l=At.exec(a));)s+="-"+l[1];var c=function(e){for(var t,n=0,r=0,a=e.length;a>=4;++r,a-=4)t=1540483477*(65535&(t=255&e.charCodeAt(r)|(255&e.charCodeAt(++r))<<8|(255&e.charCodeAt(++r))<<16|(255&e.charCodeAt(++r))<<24))+(59797*(t>>>16)<<16),n=1540483477*(65535&(t^=t>>>24))+(59797*(t>>>16)<<16)^1540483477*(65535&n)+(59797*(n>>>16)<<16);switch(a){case 3:n^=(255&e.charCodeAt(r+2))<<16;case 2:n^=(255&e.charCodeAt(r+1))<<8;case 1:n=1540483477*(65535&(n^=255&e.charCodeAt(r)))+(59797*(n>>>16)<<16)}return(((n=1540483477*(65535&(n^=n>>>13))+(59797*(n>>>16)<<16))^n>>>15)>>>0).toString(36)}(a)+s;return{name:c,styles:a,next:Tt}}(u.concat(g),t.registered,h);f+=t.key+"-"+y.name,void 0!==a&&(f+=" "+a);var v=c&&void 0===l?Rt(m):s,_={};for(var w in e)c&&"as"===w||v(w)&&(_[w]=e[w]);return _.className=f,_.ref=n,P.createElement(P.Fragment,null,P.createElement(Bt,{cache:t,serialized:y,isStringTag:"string"==typeof m}),P.createElement(m,_))},(0,P.forwardRef)((function(e,t){var n=(0,P.useContext)(Ot);return f(e,n,t)})));return g.displayName=void 0!==r?r:"Styled("+("string"==typeof o?o:o.displayName||o.name||"Component")+")",g.defaultProps=t.defaultProps,g.__emotion_real=g,g.__emotion_base=o,g.__emotion_styles=u,g.__emotion_forwardProp=l,Object.defineProperty(g,"toString",{value:function(){return"."+a}}),g.withComponent=function(t,r){return e(t,me({},n,r,{shouldForwardProp:Mt(g,r,!0)})).apply(void 0,u)},g}}.bind();["a","abbr","address","area","article","aside","audio","b","base","bdi","bdo","big","blockquote","body","br","button","canvas","caption","cite","code","col","colgroup","data","datalist","dd","del","details","dfn","dialog","div","dl","dt","em","embed","fieldset","figcaption","figure","footer","form","h1","h2","h3","h4","h5","h6","head","header","hgroup","hr","html","i","iframe","img","input","ins","kbd","keygen","label","legend","li","link","main","map","mark","marquee","menu","menuitem","meta","meter","nav","noscript","object","ol","optgroup","option","output","p","param","picture","pre","progress","q","rp","rt","ruby","s","samp","script","section","select","small","source","span","strong","style","sub","summary","sup","table","tbody","td","textarea","tfoot","th","thead","time","title","tr","track","u","ul","var","video","wbr","circle","clipPath","defs","ellipse","foreignObject","g","image","line","linearGradient","mask","path","pattern","polygon","polyline","radialGradient","rect","stop","svg","text","tspan"].forEach((function(e){Dt[e]=Dt(e)}));const zt=Dt.div`
  margin-top: 20px;

  .scrollbar {
    overflow: auto;

    &::-webkit-scrollbar {
      width: 10px;
      height: 10px;
    }

    &::-webkit-scrollbar-thumb,
    &::-webkit-scrollbar-track {
      border-radius: 6px;
    }

    &::-webkit-scrollbar-thumb {
      background-color: #b9b9b9;
    }

    &::-webkit-scrollbar-track {
      background-color: #e2e2e2;
    }
  }

  .template-header {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    padding: 6px 8px;
    margin-bottom: 0.75rem;
    background-color: #fff;
    border: 1px solid #ddd;

    .sort-box {
      width: 120px;
      background-color: #f0f0f0;
    }

    .components-dropdown {
      width: 100%;
    }

    .components-button.has-icon {
      width: 100%;
      padding: 8px;
    }

    .keywords-filter {
      align-self: center;
      order: 2;
      width: 100%;
      margin-top: 0.5rem;

      &__label {
        display: flex;

        svg {
          margin-right: 5px;
        }
      }
    }

    &__actions {
      display: flex;
      margin-left: auto;

      button {
        padding: 0;
      }
    }

    @media (min-width: 1280px) {
      // $break-wide
      .sort-box {
        margin-right: calc(1rem - 5px);
      }

      .keywords-filter {
        order: 0;
        margin-top: 0;
        width: calc(100% - 120px - 240px - 72px - 2rem);
      }
    }
  }

  .template-list-wrapper {
    display: flex;
    flex-direction: column;
    height: 100%;
    max-height: 100%;
    margin-bottom: 0;
    overflow: auto;

    .pagination-links {
      padding-top: 0.5rem;
      margin: auto 0 0;
    }
  }

  .template-list-not-found {
    padding: 0.5rem;
    background-color: #fff;
    border: 1px solid #ddd;
  }

  .template-modal__legend {
    margin: auto 0 1rem;
    font-size: 1.2em;

    p {
      margin: 0;
    }
  }

  &.is-locked {
    pointer-events: none;
  }

  .block-library-notices {
    .block-library-notice {
      margin: 0 0 10px 0;
      padding-right: 10px;
    }

    .components-notice__content {
      display: flex;
      flex-wrap: wrap;
    }

    .notice-message {
      margin-right: 12px;
    }

    .components-notice__actions {
      gap: 12px;

      > .components-notice__action {
        margin-left: 0 !important;
      }
    }
  }

  .template-item {
    .components-spinner {
      position: absolute;
      margin: 10px;
    }
  }

  .item-notices {
    position: absolute;
    bottom: 0;
    left: 0;

    .item-notice {
      width: 100%;
      padding: 4px 10px;
      margin: 0;
    }
  }

  .template-item__preview-wrapper {
    display: flex;
    flex-grow: 1;
  }

  .template-item__footer {
    /* min-height: 90px; */
    overflow: auto;
    padding: 12px !important;
  }

  // Title
  .template-item__title-details {
    flex-wrap: wrap;
    margin-bottom: 4px !important;

    > * {
      flex-basis: 100%;
    }

    .template-item__title {
      max-width: 100% !important;
      margin-bottom: 4px !important;
      line-height: 1.4;
    }
  }

  // Actions
  .template-item__actions {
    gap: 0.25rem !important;
    margin-bottom: 4px;

    > *:not(button) {
      flex-basis: 100%;
    }

    ul {
      margin: 0;

      li {
        margin: 0;
      }
    }

    .template-item__description {
      margin: 0 0 0.25rem;
    }
  }

  .template-item__links {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    width: 100%;
  }
`;Dt(x.BaseControl)`
  margin-bottom: 8px !important;

  &.is-bold {
    font-weight: 600;
  }

  &.h3 {
    font-size: 13px;
    font-weight: bold;
  }

  .components-base-control__field {
    display: flex;
    align-items: center;
    margin-bottom: 0;
  }

  .components-base-control__label {
    margin-bottom: 0;
  }

  div.components-toolbar {
    min-height: 30px;
    margin-bottom: 0;
    border: 0;

    &:first-of-type {
      margin-left: 10px;
    }

    .components-button {
      min-width: 36px;
      height: 30px;

      &.has-icon {
        min-width: 48px;
      }
    }
  }
`,Dt.div`
  padding: 8px 0;
  margin-bottom: 8px;
  border-bottom: 1px solid #ddd;

  > * {
    margin-bottom: 8px !important;
  }

  .repeater-group__item__actions {
    display: flex;
    align-items: center;
    gap: 0.2em;

    > *:first-of-type {
      margin-right: auto;
    }
  }
`,Dt.div`
  > *:not(:last-of-type) {
    margin-bottom: 12px !important;
  }
`,Dt(x.PanelBody)`
  margin-right: -16px;
  margin-left: -16px;
`;const Ut=Dt.div`
  box-sizing: border-box;
  width: 100%;

  .group-control__body {
    gap: 4px;

    > * {
      max-width: 100%;
    }
  }

  &.is-2-columns {
    .group-control__body {
      > * {
        flex-basis: calc(50% - 4px);

        &:nth-of-type(n + 3) {
          margin-top: 8px !important;
        }
      }
    }
  }

  &.is-3-columns {
    .group-control__body {
      > * {
        flex-basis: calc(33.33333% - 4px);

        &:nth-of-type(n + 4) {
          margin-top: 8px !important;
        }
      }
    }
  }

  &.is-4-columns {
    .group-control__body {
      > * {
        flex-basis: calc(25% - 4px);

        &:nth-of-type(n + 5) {
          margin-top: 8px !important;
        }
      }
    }
  }
`,Ht=Dt(x.Flex)`
  padding-bottom: 8px;

  .label-control {
    margin-bottom: 0 !important;
  }
`,jt=Dt(x.Flex)`
  flex-wrap: wrap;
  width: auto;
  gap: 4px;

  > * {
    flex: 1 0 auto;
    margin: 0 !important;
  }
`;var Vt=(0,P.createElement)(L.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24"},(0,P.createElement)(L.Path,{d:"M10 17.389H8.444A5.194 5.194 0 1 1 8.444 7H10v1.5H8.444a3.694 3.694 0 0 0 0 7.389H10v1.5ZM14 7h1.556a5.194 5.194 0 0 1 0 10.39H14v-1.5h1.556a3.694 3.694 0 0 0 0-7.39H14V7Zm-4.5 6h5v-1.5h-5V13Z"})),$t=(0,P.createElement)(L.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24"},(0,P.createElement)(L.Path,{d:"M17.031 4.703 15.576 4l-1.56 3H14v.03l-2.324 4.47H9.5V13h1.396l-1.502 2.889h-.95a3.694 3.694 0 0 1 0-7.389H10V7H8.444a5.194 5.194 0 1 0 0 10.389h.17L7.5 19.53l1.416.719L15.049 8.5h.507a3.694 3.694 0 0 1 0 7.39H14v1.5h1.556a5.194 5.194 0 0 0 .273-10.383l1.202-2.304Z"}));function Gt(e){let{isLinked:t,...n}=e;const r=t?(0,k.__)("Unlink Sides","content-blocks-builder"):(0,k.__)("Link Sides","content-blocks-builder");return(0,h.createElement)(x.Tooltip,{text:r},(0,h.createElement)("span",null,(0,h.createElement)(x.Button,me({},n,{className:"component-group-control__linked-button",variant:t?"primary":"secondary",size:"small",icon:t?Vt:$t,iconSize:16,"aria-label":r}))))}const Kt=e=>{let{values:t,fields:n}=e;const r=n.map((e=>{var n;let{name:r}=e;return null!==(n=t[r])&&void 0!==n?n:void 0}));return(a=r.filter((e=>e))).sort(((e,t)=>(0,v.isObject)(e)?a.filter((t=>N()(t,e))).length-a.filter((e=>N()(e,t))).length:a.filter((t=>t===e)).length-a.filter((e=>e===t)).length)).pop();var a},Wt=e=>{let{values:t,fields:n,renderControl:r,onChange:a,normalizeValue:i}=e;return n.map((e=>{var o;const{name:l}=e;return(0,h.createElement)(h.Fragment,{key:`group-control-${l}`},r({value:null!==(o=t[l])&&void 0!==o?o:void 0,onChange:(s=l,e=>{e=i({side:s,value:e}),a({...t,[s]:e})}),fields:n,values:t,...e}));var s}))},qt=e=>{let{values:t,fields:n,renderControl:r,renderAllControl:a=null,onChange:i,normalizeValue:o,...l}=e;return a||(a=r),a({value:Kt({values:t,fields:n}),fields:n,values:t,onChange:e=>{e=o({side:"all",value:e});let r={...t};n.forEach((t=>{let{name:n}=t;r={...r,[n]:e}})),i(r)},...l})},Yt=e=>{let{label:t,fields:n=[],values:r={},renderLabel:a=v.noop,renderControl:i=v.noop,onChange:o=v.noop,normalizeValue:l=(e=>{let{side:t,value:n}=e;return n}),isLinkedGroup:s=!0,getInitialLinkedState:c=v.noop,className:d,columns:u,...p}=e;const m={fields:n,values:r,renderControl:i,onChange:o,normalizeValue:l,...p},[f,g]=s?function(e){const[t,n]=(0,h.useState)(e);return(0,h.useEffect)((()=>n(e)),[e]),[t,n]}(c(r)):[!1,v.noop];return(0,h.createElement)(Ut,me({className:y()("group-control",d,{[`is-${u}-columns`]:u})},p),(0,h.createElement)(Ht,{className:"group-control__header"},a({label:t,isLinkedGroup:s,...p}),s&&(0,h.createElement)(Gt,{onClick:()=>{g(!f)},isLinked:f})),(0,h.createElement)(jt,{className:"group-control__body"},f&&(0,h.createElement)(qt,m),!f&&(0,h.createElement)(Wt,m)))};Dt(Yt)`
  .group-control__body {
    > *:nth-of-type(3) {
      order: 2;
    }

    .components-input-control__input {
      height: 40px;
    }
  }
`,Dt.div`
  > .block-editor-tools-panel-color-gradient-settings__item {
    border-right: 1px solid #0000001a;
    border-bottom: 1px solid #0000001a;
    border-left: 1px solid #0000001a;

    &:first-of-type {
      border-top: 1px solid #0000001a;
    }
  }

  .block-editor-tools-panel-color-gradient-settings__dropdown {
    display: block;
  }

  /* Deprecated in 6.1 */
  .block-editor-panel-color-gradient-settings__color-indicator {
    background: linear-gradient(-45deg, #0000 48%, #ddd 0, #ddd 52%, #0000 0);
  }
  /* TODO: remove after 6.1 */

  &.is-inner-control {
    > * {
      border: 0 !important;
    }

    .block-editor-tools-panel-color-gradient-settings__dropdown {
      display: flex;
      align-items: center;
      align-self: flex-end;
      height: 30px;
      border: 1px solid #757575;

      > button {
        height: 100%;
        padding: 4px;
      }
    }
  }
`,Dt(Yt)`
  /* .block-editor-panel-color-gradient-settings__item {
    padding: 8px !important;
  } */

  .components-toggle-control {
    > * {
      margin-bottom: 0;
    }
  }
`,Dt.div`
  .shadow-list__title {
    margin-bottom: 8px;
  }

  .shadow-list {
    display: grid;
    grid-template-columns: repeat(5, minmax(0, 1fr));
    gap: 12px;

    margin-bottom: 16px;
  }

  .shadow-item {
    height: 30px;
    cursor: pointer;
    background: #fff;
    border: 1px solid #ddd;

    &.is-selected {
      background: #ddd;
    }
  }
`,Dt(Yt)`
  .components-base-control__field {
    margin-bottom: 0;
  }
`,Dt.div`
  .svg-input-control {
    &__label {
      font-size: 11px;
      font-weight: 500;
      text-transform: uppercase;
    }

    &__actions {
      display: flex;
      gap: 8px;
      margin: 6px 0;
    }

    &__input {
      margin-bottom: 4px;

      > * {
        margin-bottom: 0;
      }
    }
  }
`,Dt.div`
  .settings-section__description {
    margin: 1em 0;
    font-size: 1.1em;
    font-weight: 500;
  }

  .meta-box-sortables {
    @media (min-width: 1080px) {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      align-items: start;
      gap: 1rem;

      .postbox {
        margin-bottom: 0;
      }
    }
  }
`,Dt.div`
  &.is-full-row {
    grid-column: span 2;
  }

  &.is-header-hidden {
    .inside {
      padding: 12px;
    }

    @media (min-width: 1080px) {
      margin: 0;
    }
  }

  .postbox-header {
    .hndle {
      cursor: pointer;
    }
  }

  .inside {
    margin: 0;
  }

  .postbox-footer {
    padding: 12px;
    border-top: 1px solid #f0f0f1;
  }

  &.closed .postbox-footer {
    display: none;
  }

  .components-notice {
    width: 100%;
    padding-top: 0;
    padding-bottom: 0;
    margin-top: 8px;
    margin-right: 0;
    margin-left: 0;
    box-sizing: border-box;
  }
`,Dt.div`
  padding: 12px 16px;
  margin-top: 12px;
  background-color: #fafafa;
  border: 1px solid #ebebeb;
  border-radius: 2px;

  .fieldset__label {
    margin-bottom: 12px;
  }

  .fieldset__list {
    margin-bottom: 0;

    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    column-gap: 1rem;
  }

  .file-upload {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 1em;
  }

  .file-preview {
    display: flex;
    align-items: center;
    gap: 0.5em;

    .icon {
      width: 20px;
      height: 20px;
    }
  }
`,Dt.div`
  flex-wrap: wrap;

  .block-editor-block-variation-picker__variations > li {
    margin-right: 8px;
  }

  .block-editor-block-variation-picker.has-many-variations
    .components-placeholder__fieldset {
    max-width: 100%;
  }

  .placeholder__footer {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    flex-basis: 100%;
    padding: 1em;
    background-color: #fff;
    box-shadow: inset 0 -1px 0 1px #1e1e1e;
  }
`,n(9799),window.wp.keycodes,Dt(x.Modal)`
  // Modal content
  .components-modal__content {
    display: flex;
    flex-direction: column;
    padding: 0 20px 20px;
    margin-top: 50px;
    overflow: hidden;

    &::before {
      margin-bottom: 20px;
    }

    > :not(.components-modal__header, .icon-submit) {
      max-height: 100%;
      display: flex;
      flex-direction: column;
      flex: 1;
      overflow: auto;
    }
  }

  // Modal header
  .components-modal__header {
    height: 50px;
    padding: 0 20px;
  }

  .icon-library-wrapper {
    flex: 1;
    overflow: hidden;
    content-visibility: hidden;

    &.is-loading,
    &.show-library {
      content-visibility: visible;
    }
  }

  .icon-filter {
    flex-wrap: nowrap;
    align-items: flex-start;
    justify-content: flex-start;
    margin-bottom: 20px;

    &__search {
      min-width: 220px;
    }

    .keywords {
      display: flex;
      flex-wrap: wrap;
      margin: 0;
      font-size: 14px;

      > li {
        margin: 0;
      }

      .keyword-label {
        font-weight: 500;
      }

      span {
        display: block;
        padding: 3px 5px;
      }

      .keyword:not(.is-selected) {
        color: var(--wp-admin-theme-color, #007cba);
        cursor: pointer;
      }

      .is-selected {
        font-weight: 500;
        pointer-events: none;
      }
    }

    @media (max-width: 781px) {
      flex-wrap: wrap;

      &__search {
        width: 100%;
      }

      &__keywords {
        margin-top: 8px;
        margin-left: 0 !important;
      }
    }
  }

  .components-search-control > * {
    margin-bottom: 0;
  }

  // Icons list
  .icon-library {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(9em, 1fr));
    gap: 0.5em;
    max-height: calc(100% - 110px);
    overflow: auto;

    /* box-shadow: inset 0 0 2px rgba(0, 0, 0, 0.4); */

    svg {
      width: 4em;
      height: 4em;
    }

    .title {
      max-height: 1.7em;
      font-size: 0.85em;
      line-height: 1.5;
      text-align: center;
      word-break: break-word;
    }

    > * {
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 1.5em 1em;
      overflow: hidden;
      border: 1px solid #ddd;
      border-radius: 4px;
      cursor: pointer;
    }

    .selected {
      background-color: #ccc;
    }

    &:empty::before {
      display: block;
      width: 100%;
      padding: 2rem;
      text-align: center;
      content: attr(data-empty);
      border: 1px solid #ddd;
      border-radius: 4px;
    }
  }

  // Pagination
  .pagination {
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 8px 0;
    font-size: 1.5em;

    > li {
      margin: 0 5px;

      &:only-child {
        display: none;
      }

      &.active {
        a {
          color: #3c434a;
        }
      }

      &:not(.active) {
        a {
          cursor: pointer;
        }
      }
    }

    a {
      display: block;
      padding: 5px 10px;
    }
  }
`;const Jt=Dt.ul`
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  margin: 1rem 0 0;
  font-size: 1.5em;

  > li {
    margin-bottom: 0;

    > * {
      display: block;
      padding: 0.5rem;
    }

    > a {
      cursor: pointer;
    }
  }
`,Xt=e=>{let{total:t,current:n=1,midSize:r=2,endSize:a=1,onChange:i,showAll:o=!1,nextPrev:l=!0}=e;if(t<2)return null;const s=[];let c=!1;l&&n&&n>1&&s.push((0,h.createElement)("a",{className:"pagination-link pagination-link--prev",onClick:()=>i(n-1)},(0,k.__)("Prev","content-blocks-builder")));for(let e=1;e<=t;e++)e===n?(s.push((0,h.createElement)("span",{"aria-current":"page",className:"pagination-link is-active"},e)),c=!0):o||e<=a||e>=n-r&&e<=n+r||e>t-a?(s.push((0,h.createElement)("a",{className:"pagination-link",onClick:()=>i(e)},e)),c=!0):c&&!o&&(s.push((0,h.createElement)("span",{className:"pagination-link dots"},"…")),c=!1);return l&&n&&n<t&&s.push((0,h.createElement)("a",{className:"pagination-link pagination-link--next",onClick:()=>i(n+1)},(0,k.__)("Next","content-blocks-builder"))),(0,h.createElement)(Jt,{className:"pagination-links"},s.map(((e,t)=>(0,h.createElement)("li",{key:t},e))))},Zt=(Dt(x.Modal)`
  .scrollbar {
    overflow: auto;

    &::-webkit-scrollbar {
      width: 10px;
      height: 10px;
    }

    &::-webkit-scrollbar-thumb,
    &::-webkit-scrollbar-track {
      border-radius: 6px;
    }

    &::-webkit-scrollbar-thumb {
      background-color: #b9b9b9;
    }

    &::-webkit-scrollbar-track {
      background-color: #e2e2e2;
    }
  }

  // Modal content
  .components-modal__content {
    display: flex;
    flex-direction: column;
    padding: 1rem;
    background-color: #f9f9f9;

    > div:not(.template-modal__legend, .template-header) {
      display: flex;
      flex-direction: column;
      max-height: 100%;
    }
  }

  .template-header {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    margin-bottom: 0.75rem;

    .search-box {
      flex-grow: 1;
      margin-right: 1rem;
      max-width: calc(100% - 120px - 72px - 1rem);

      @media (max-width: 599px) {
        max-width: calc(100% - 40px - 72px - 1rem);
      }

      > * {
        margin-bottom: 0;
      }
    }

    .sort-box {
      width: 120px;
      background-color: #f0f0f0;

      @media (max-width: 599px) {
        /* $break-medium: 600px */
        width: 40px;

        .text {
          display: none;
        }

        .components-button.has-icon.has-text svg {
          margin-right: 0;
        }
      }

      @media (max-width: 1279px) {
        align-self: flex-start;
      }

      .components-dropdown {
        width: 100%;
      }

      .components-button.has-icon {
        width: 100%;
        padding: 8px;
      }
    }

    .keywords-filter {
      order: 2;
      width: 100%;
      margin-top: 0.5rem;

      &__label {
        display: flex;

        svg {
          margin-right: 5px;
        }
      }
    }

    &__actions {
      display: flex;
      margin-left: auto;
      align-self: flex-start;

      button {
        padding: 0;
      }
    }

    @media (min-width: 1280px) {
      // $break-wide
      .search-box {
        width: 240px;
      }

      .sort-box {
        margin-right: calc(1rem - 5px);
      }

      .keywords-filter {
        order: 0;
        margin-top: 0;
        width: calc(100% - 120px - 240px - 72px - 2rem);
      }
    }
  }

  .template-list-wrapper {
    display: flex;
    flex-direction: column;
    flex: 1;
    height: 100%;
    margin-bottom: 0;
    overflow: auto;

    .pagination-links {
      padding-top: 0.5rem;
      margin: auto 0 0;
    }
  }

  .template-list-not-found {
    padding: 0.5rem;
    background-color: #fff;
    border: 1px solid #ddd;
  }

  .template-modal__legend {
    margin: auto 0 1rem;
    font-size: 1.2em;

    p {
      margin: 0;
    }
  }

  &.is-locked {
    pointer-events: none;
  }
`,Dt.div`
  display: grid;
  grid-template-columns: repeat(1, 1fr);
  gap: 1rem;

  @media (min-width: 782px) {
    // $break-medium
    grid-template-columns: repeat(2, 1fr);
  }

  @media (min-width: 1280px) {
    // $break-wide
    grid-template-columns: repeat(3, 1fr);
  }
`),Qt=Dt.div`
  position: relative;
  display: flex;
  flex-direction: column;
  overflow: hidden;
  background-color: #fff;
  border: 1px solid #ddd;
  transition: border-color 0.15s ease-in-out;

  .template-item__preview {
    position: relative;
    display: flex;
    flex-direction: column;
    justify-content: center;
    flex-grow: 1;
    min-height: 10rem;
    padding-top: 1.25rem;
    padding-bottom: 1.25rem;

    .template-item__thumbnail {
      max-height: 18rem;
      margin: 0 0.5rem;

      img {
        width: 100%;
      }
    }
  }

  &.is-ready {
    .template-item__preview {
      cursor: pointer;
    }

    &:hover {
      border-color: var(--wp-admin-theme-color, #007cba);
    }
  }

  &.is-inserting {
    position: relative;

    .template-item__preview {
      opacity: 0.6;
    }

    .components-spinner {
      position: absolute;
      top: calc(50% - 16px);
      left: calc(50% - 16px);
    }
  }

  .components-spinner {
    position: absolute;
    margin: 10px;
  }

  .item-notices {
    position: absolute;
    bottom: 0;
    left: 0;

    .item-notice {
      width: 100%;
      padding: 4px 10px;
      margin: 0;
    }

    .components-notice__actions {
      gap: 8px;

      > .components-notice__action {
        margin: 4px 0 0 !important;
      }
    }
  }

  .template-item__badges {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;

    > * {
      display: inline-block;
      padding: 3px 6px;
      color: #fff;
      background-color: var(--wp-admin-theme-color, #007cba);
      border-radius: 2px;

      + * {
        margin-left: 4px;
      }
    }
  }

  &.has-missing-blocks {
    .template-item__preview {
      padding: 1.25rem;
    }
  }

  .notice-missing-block,
  .notice-require-pro {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    margin-left: 0;
  }

  .template-item__footer {
    padding: calc(1rem - 0.2em) 1rem calc(1rem + 0.2em);
    background-color: #f0f0f0;
    border-top: 1px solid #ddd;

    > * {
      margin: 0 0 0.5rem;

      &:last-child {
        margin-bottom: 0;
      }
    }
  }

  .template-item__title-details {
    display: flex;

    .template-item__title {
      max-width: calc(100% - 80px);
      margin-bottom: 0;
      overflow: hidden;
    }

    .template-item__more {
      flex-basis: 80px;
      margin-left: auto;
      text-align: right;
    }
  }

  .template-item__title {
    margin-top: 0;
    font-size: 1rem;
    line-height: 1;
    white-space: nowrap;
    text-overflow: ellipsis;
  }

  .template-item__links {
    display: inline-flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    width: 100%;

    li {
      margin-bottom: 0;
    }
  }

  .template-item__actions {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
  }

  .template-item__messages {
    p {
      margin: 0;
    }
  }
`,en=e=>{let{isPro:t,hasProFeatures:n}=e;return(0,h.createElement)(h.Fragment,null,(t||n)&&(0,h.createElement)("div",{className:"template-item__badges"},t&&(0,h.createElement)("span",{className:"pro-item"},(0,k.__)("Pro","content-blocks-builder")),n&&(0,h.createElement)("span",{className:"pro-features-item"},(0,k.__)("Has pro features","content-blocks-builder"))))},tn=e=>{let{links:t,label:n}=e;return(0,h.createElement)(h.Fragment,null,!!t?.length&&(0,h.createElement)("ul",{className:"template-item__links"},(0,h.createElement)("li",{className:"template-item__label"},(0,h.createElement)("strong",null,n)),t.map(((e,t)=>{let{url:n,title:r}=e;return(0,h.createElement)("li",{className:"template-item__link",key:t},(0,h.createElement)("a",{href:n,target:"_blank"},r))}))))},nn=e=>{let{notices:t}=e;return(0,h.createElement)(h.Fragment,null,!!t?.length&&(0,h.createElement)("div",{className:"item-notices"},t.map(((e,t)=>{let{type:n,message:r,actions:a=[],customActions:i=null}=e;return(0,h.createElement)(x.Notice,{className:"item-notice",status:n,isDismissible:!1,key:`notice-${t}`,actions:a},r,i)}))))},rn=e=>{let{items:t,label:n,type:r}=e;return(0,h.createElement)(h.Fragment,null,!!t?.length&&(0,h.createElement)("ul",{className:"template-item__links"},!!n&&(0,h.createElement)("li",{className:"template-item__label"},(0,h.createElement)("strong",null,n)),t.map(((e,t)=>{let{title:n}=e;return(0,h.createElement)("li",{className:"template-item__link",key:`${r}-${t}`},(0,h.createElement)("code",null,n))}))))},an=e=>{let{missingPlugins:t,inactivePlugins:n,libraryState:r,updateLibraryState:i,onPluginInstalled:o,onPluginActivated:l}=e;const{activatingPlugins:s=[],installingPlugins:c=[]}=r,d=e=>i({activatingPlugins:e}),u=e=>i({installingPlugins:e});return(0,h.createElement)(h.Fragment,null,(!!t?.length||!!n?.length)&&(0,h.createElement)("div",{className:"template-item__actions"},!!t.length&&t.map((e=>{let{name:t,slug:n}=e;return!r?.isReloading&&(0,h.createElement)(x.Button,{key:n,variant:"primary",isSmall:!0,disabled:!!c.length,onClick:e=>{e.preventDefault(),u([...c,n]),a()({path:"wp/v2/plugins",method:"POST",data:{slug:n,status:"active"}}).then((()=>{o(t)})).catch((e=>U(e,"error"))).finally((()=>u(c.filter((e=>e!==n)))))}},(0,k.sprintf)((0,k.__)("Install %s","content-blocks-builder"),t),!!c.length&&-1!==c.indexOf(n)&&(0,h.createElement)(x.Spinner,{style:{margin:"0 5px"}}))})),!!n.length&&n.map((e=>{let{name:t,plugin:n,slug:i}=e;return!r?.isReloading&&(0,h.createElement)(x.Button,{key:i,variant:"primary",isSmall:!0,disabled:!!s.length,onClick:e=>{e.preventDefault(),d([...s,i]),a()({path:`wp/v2/plugins/${n}`,method:"POST",data:{status:"active"}}).then((()=>{l(t)})).catch((e=>U(e,"error"))).finally((()=>{d(s.filter((e=>e!==i)))}))}},(0,k.sprintf)((0,k.__)("Activate %s","content-blocks-builder"),t),!!s.length&&-1!==s.indexOf(i)&&(0,h.createElement)(x.Spinner,{style:{margin:"0 5px"}}))}))))};var on=e=>{let{items:t,allItems:n,onChangePage:r,currentPage:a=1,pageSize:i=6,TemplateItemControl:o}=e;return n.length?(0,h.createElement)(h.Fragment,null,(0,h.createElement)(Zt,{className:"template-list"},t.map((e=>(0,h.createElement)(o,{item:e,key:e.id})))),(0,h.createElement)(Xt,{current:a,total:Math.ceil(n.length/i),onChange:r})):(0,h.createElement)("div",{className:"template-list-not-found"},(0,k.__)("There is no items found.","content-blocks-builder"))},ln=(0,P.createElement)(L.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24"},(0,P.createElement)(L.Path,{d:"m19 7.5h-7.628c-.3089-.87389-1.1423-1.5-2.122-1.5-.97966 0-1.81309.62611-2.12197 1.5h-2.12803v1.5h2.12803c.30888.87389 1.14231 1.5 2.12197 1.5.9797 0 1.8131-.62611 2.122-1.5h7.628z"}),(0,P.createElement)(L.Path,{d:"m19 15h-2.128c-.3089-.8739-1.1423-1.5-2.122-1.5s-1.8131.6261-2.122 1.5h-7.628v1.5h7.628c.3089.8739 1.1423 1.5 2.122 1.5s1.8131-.6261 2.122-1.5h2.128z"}));const sn=Dt.div`
  &.has-keywords {
    align-self: flex-start;
  }

  .keywords-filter__label {
    margin-right: 0.5em;
    font-weight: 500;
  }

  .keywords-filter__clear {
    font-size: 14px;
    font-weight: 700;
  }

  ul {
    display: inline-flex;
    flex-wrap: wrap;
    margin: 0;

    li {
      margin: 0;

      span {
        display: inline-block;
        padding-top: 0.2em;
        padding-bottom: 0.2em;
        margin-right: 0.3em;
      }

      .keyword {
        cursor: pointer;
      }

      .keyword:not(.is-selected) {
        color: var(--wp-admin-theme-color, #007cba);
      }

      .is-selected {
        font-weight: 500;
      }

      .clear-filter {
        margin-right: 0;
        margin-left: 0.5em;
        text-decoration: underline;
      }
    }
  }
`;function cn(e){let t,{items:n=[],keywords:r,isLoading:a,selectedKeywords:i,onChange:o}=e;if(n.length){const e=[];n.forEach((t=>{let{keywordIds:n}=t;e.push(...n)}));const a=[...new Set(e)];t=r.filter((e=>{let{id:t}=e;return a.includes(t)}))}else t=r;return(0,h.createElement)(sn,{className:y()("keywords-filter",{"has-keywords":t&&!!t.length})},a&&(0,h.createElement)(x.Spinner,null),t&&!!t.length&&(0,h.createElement)("div",{className:"keywords-filter__keywords"},(0,h.createElement)("ul",{className:""},(0,h.createElement)("li",{className:"keywords-filter__label"},(0,h.createElement)(x.Icon,{icon:ln}),(0,h.createElement)("span",{className:""},(0,k.__)(" Keywords:","content-blocks-builder"))),t.map(((e,n)=>(0,h.createElement)("li",{key:n,onClick:()=>(e=>{let t=[];if(i.length){t=[...i];const n=i.findIndex((t=>{let{id:n}=t;return n===e.id}));n>-1?t.splice(n,1):t.push(e),t.sort(((e,t)=>e.count-t.count))}else t.push(e);o(t)})(e)},(0,h.createElement)("span",{className:y()("keyword",{"is-selected":i.findIndex((t=>{let{id:n}=t;return n===e.id}))>-1})},e.name,n!==t.length-1?",":"")))),!!i.length&&(0,h.createElement)("li",{className:"keywords-filter__clear",onClick:()=>{o([])}},(0,h.createElement)("span",{className:"keyword clear-filter",title:(0,k.__)("Clear filtered keywords","content-blocks-builder")},(0,k.__)("Clear all","content-blocks-builder"))))))}var dn;function un(){return un=Object.assign?Object.assign.bind():function(e){for(var t=1;t<arguments.length;t++){var n=arguments[t];for(var r in n)Object.prototype.hasOwnProperty.call(n,r)&&(e[r]=n[r])}return e},un.apply(this,arguments)}var pn=function(e){return P.createElement("svg",un({xmlns:"http://www.w3.org/2000/svg",width:24,height:24,fill:"currentColor",viewBox:"0 0 16 16"},e),dn||(dn=P.createElement("path",{d:"M3.5 2.5a.5.5 0 0 0-1 0v8.793l-1.146-1.147a.5.5 0 0 0-.708.708l2 1.999.007.007a.497.497 0 0 0 .7-.006l2-2a.5.5 0 0 0-.707-.708L3.5 11.293zm3.5 1a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5M7.5 6a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1zm0 3a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1zm0 3a.5.5 0 0 0 0 1h1a.5.5 0 0 0 0-1z"})))};const mn=[{value:"featured",title:(0,k.__)("Featured","content-blocks-builder")},{value:"latest",title:(0,k.__)("Latest","content-blocks-builder")},{value:"30_days",title:(0,k.__)("Most downloads last 30 days","content-blocks-builder"),shortTitle:(0,k.__)("Last 30 days","content-blocks-builder")},{value:"7_days",title:(0,k.__)("Most downloads last 7 days","content-blocks-builder"),shortTitle:(0,k.__)("Last 7 days","content-blocks-builder")}],fn=Dt(x.MenuGroup)`
  .is-active {
    color: #fff;
    background-color: var(--wp-admin-theme-color, #007cba);
  }
`;function gn(e){let{sortTypes:t=mn,value:n,onChange:r}=e;n=n||"featured";const a=(0,v.find)(t,(e=>e.value===n));return(0,h.createElement)(x.Dropdown,{renderToggle:e=>{var t;let{onToggle:n}=e;return(0,h.createElement)(x.Button,{onClick:n,icon:pn},(0,h.createElement)("span",{className:"text"},null!==(t=a?.shortTitle)&&void 0!==t?t:a.title))},popoverProps:{placement:"bottom-end",__unstableSlotName:"sortType"},renderContent:e=>{let{onClose:a}=e;return(0,h.createElement)(fn,null,t.map((e=>(0,h.createElement)(x.MenuItem,{className:e.value===n?"is-active":"",key:e.value,onClick:()=>{r(e.value),a(!0)}},e.title))))}})}var hn=(0,P.createElement)(L.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24"},(0,P.createElement)(L.Path,{d:"M13 11.8l6.1-6.3-1-1-6.1 6.2-6.1-6.2-1 1 6.1 6.3-6.5 6.7 1 1 6.5-6.6 6.5 6.6 1-1z"})),bn=(0,P.createElement)(L.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24"},(0,P.createElement)(L.Path,{d:"M12 4.75a7.25 7.25 0 100 14.5 7.25 7.25 0 000-14.5zM3.25 12a8.75 8.75 0 1117.5 0 8.75 8.75 0 01-17.5 0zM12 8.75a1.5 1.5 0 01.167 2.99c-.465.052-.917.44-.917 1.01V14h1.5v-.845A3 3 0 109 10.25h1.5a1.5 1.5 0 011.5-1.5zM11.25 15v1.5h1.5V15h-1.5z"}));function yn(e){let{isOpenHelp:t,onToggleHelp:n,onCloseModal:r}=e;return(0,h.createElement)("div",{className:"template-header__actions"},(0,h.createElement)(x.Button,{className:"template-header__help",onClick:n,icon:t?hn:bn,iconSize:36,label:(0,k.__)("Toggle help","content-blocks-builder"),"aria-expanded":t}),r&&(0,h.createElement)(x.Button,{className:"template-header__close",onClick:r,icon:hn,iconSize:36,label:(0,k.__)("Close dialog","content-blocks-builder")}))}class vn{constructor(){let e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:"";e||(e=window.location.href),this.parsedURL=new URL(e)}get(e){let t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:null;return this.parsedURL.searchParams.get(e)||t}set(e,t){let n=!(arguments.length>2&&void 0!==arguments[2])||arguments[2];this.parsedURL.searchParams.set(e,t),n&&history.pushState&&history.pushState({},null,this.parsedURL.href)}delete(e){let t=!(arguments.length>1&&void 0!==arguments[1])||arguments[1];this.parsedURL.searchParams.delete(e),t&&history.pushState&&history.pushState({},null,this.parsedURL.href)}reload(){history?.go?history.go():window.location.reload()}getHref(){return this.parsedURL.href}}const wn=e=>{let{isOpen:t,setIsOpen:n,handleConfirm:r=v.noop,content:a=null}=e;return(0,h.createElement)(x.__experimentalConfirmDialog,{isOpen:t,onConfirm:r,onCancel:()=>{n(!1)}},a)},kn=e=>{var t;let{title:n,item:r}=e;const a=r?.meta?.boldblocks_enable_repeater?null!==(t=r?.meta?.boldblocks_parent_block_title)&&void 0!==t?t:`${n} repeater`:"";return(0,h.createElement)(h.Fragment,null,(0,h.createElement)("h3",{className:"template-item__title"},(0,k.sprintf)((0,k.__)("Block name: %s","content-blocks-builder"),n)),a&&(0,h.createElement)("h3",{className:"template-item__title"},(0,k.sprintf)((0,k.__)("Parent block: %s","content-blocks-builder"),a)))},xn=e=>{let{title:t,item:n}=e;const r=n?.meta?.boldblocks_variation_block_name;return(0,h.createElement)(h.Fragment,null,(0,h.createElement)("h3",{className:"template-item__title"},(0,k.sprintf)((0,k.__)("Variation name: %s","content-blocks-builder"),t)),r&&(0,h.createElement)("h3",{className:"template-item__title"},(0,k.sprintf)((0,k.__)("Block name: %s","content-blocks-builder"),r)))},En=e=>{var t;let{item:n}=e;const{libraryState:r,updateLibraryState:a,onImportItem:i,availableBlockNames:o,plugins:l,canManagePlugins:s,localBlocks:c,localVariations:d,isResolvingLocalData:u,importingMessages:p,setImportingMessages:m,contentType:f}=(0,h.useContext)(le),[,g]=O(`${se}-${f}`),b=()=>function(e,t,n){let r=arguments.length>3&&void 0!==arguments[3]?arguments[3]:500;n({isReloading:!0}),setTimeout((()=>{t(e),(new vn).reload()}),r)}(r,g,a),[_,w]=(0,h.useState)([]),{blocks:S,missingBlocks:T,tutorials:C,externalResources:N,dependentPlugins:P=[],missingPlugins:L,inactivePlugins:I,isPro:F,hasProFeatures:R,isExisting:M}=(e=>{let{item:t,canManagePlugins:n,plugins:r,availableBlockNames:a,localVariations:i,isResolvingLocalData:o,isPremium:l,setItemNotices:s,contentType:c="block"}=e;return(0,h.useMemo)((()=>{var e;if(!t?.slug||o||(0,v.isUndefined)(n))return{};let d,u;var p;u="block"===c?JSON.parse(null!==(p=t?.meta?.boldblocks_block_blocks)&&void 0!==p?p:"[]"):(e=>{if(!e)return[];const t=JSON.parse(e),{blockName:n,variation:{attributes:r,innerBlocks:a}}=t;return[{name:n,attributes:r,innerBlocks:pe(a)}]})(t?.meta?.boldblocks_variation_data);let m=null!==(e=t.meta[`boldblocks_${c}_dependent_blocks`])&&void 0!==e?e:[];t?.thumbnail||(0,v.isArray)(m)&&m.length||!u||(m=re(u,[])),(0,v.isArray)(m)&&m.length&&(d=((e,t)=>{let n=[];e=(e=e.filter((e=>0!==e.indexOf("core/")))).filter((e=>-1===te.indexOf(e)));for(let r=0;r<e.length;r++)-1===t.indexOf(e[r])&&n.push(e[r]);return n})(m,a)),!d?.length&&u&&(u=(0,E.createBlocksFromInnerBlocksTemplate)(u));const f=t?.meta?.boldblocks_tutorials,g=t?.meta?.boldblocks_external_resources,h=(0,v.isNil)(t?.meta?.boldblocks_dependencies)?t?.meta?.boldblocks_block_dependencies:t.meta.boldblocks_dependencies,b=n&&r.length&&h?h.filter((e=>{let{slug:t}=e;return!(0,v.find)(r,["slug",t])})):[],y=n&&r.length&&h?h.map((e=>{let{slug:t}=e;const n=(0,v.find)(r,["slug",t]);return!(!n||"inactive"!==n?.status)&&n})).filter((e=>e)):[],_=t?.meta?.boldblocks_is_pro,w=t?.meta?.boldblocks_has_pro_features,x="block"===c?a.indexOf(`boldblocks/${t?.slug}`)>-1:!(!t?.slug||!i)&&ue(t?.slug,i);let S=[];return x&&S.push({type:"warning",message:(0,k.__)("This item already exists. The import will override the existing item.","content-blocks-builder")}),d?.length?(S.push({type:"warning",message:(0,k.sprintf)((0,k.__)("This item requires the following external block(s): %s. You must install and/or activate the required plugins to use it.","content-blocks-builder"),d.join(", "))}),(0,v.isUndefined)(n)||n||S.push({type:"warning",message:(0,k.__)("You don't have permission to manage plugins, please contact the administrator for help.","content-blocks-builder")})):(b.length||y.length)&&S.push({type:"warning",message:(0,k.__)("You must install and/or activate the required plugin(s) to use this item.","content-blocks-builder")}),!l&&_&&S.push({type:"warning",message:(0,k.__)("This item requires the Pro version of the plugin.","content-blocks-builder")}),s([...S]),{blocks:u,missingBlocks:d,tutorials:f,externalResources:g,dependentPlugins:h,missingPlugins:b,inactivePlugins:y,isPro:_,hasProFeatures:w,isExisting:x}}),[t?.slug,n,r,l,i,o])})({contentType:f,item:n,canManagePlugins:s,plugins:l,availableBlockNames:o,localVariations:d,isResolvingLocalData:u,setItemNotices:w}),B=!T?.length&&!L?.length&&!I?.length&&(!F||F&&ne),[D,z]=(0,h.useState)(!1),U=(0,h.useMemo)((()=>n?.slug?(0,h.createElement)("div",{className:"template-item__preview"},n?.thumbnail?(0,h.createElement)("div",{className:"template-item__thumbnail scrollbar",dangerouslySetInnerHTML:{__html:n?.thumbnail}}):!T?.length&&(0,h.createElement)(A.BlockPreview,{blocks:S,viewportWidth:1400}),(0,h.createElement)(en,{isPro:F,hasProFeatures:R}),(0,h.createElement)(nn,{notices:_})):(0,h.createElement)("div",{className:"template-item__preview"})),[n?.slug,T,r?.insertingItem,_]),H=(j=n.title,(V=document.createElement("textarea")).innerHTML=j,V.value);var j,V;const $="block"===f?n?.meta?.boldblocks_enable_repeater?n?.meta?.boldblocks_parent_block_description:n?.meta?.boldblocks_block_description:n?.meta?.boldblocks_variation_description,G=()=>{a({insertingItem:n.id}),setTimeout((()=>{i({contentType:f,item:n,isExisting:M,localBlocks:c,localVariations:d,importingMessages:p,setImportingMessages:m,setIsOpenConfirm:z})}),0)},K=r?.importedItems?.length&&r.importedItems.indexOf(n?.slug)>-1;return(0,h.createElement)(h.Fragment,null,(0,h.createElement)(Qt,{className:y()("template-item",{"is-ready":B&&!K&&!u,"is-pro":F,"has-pro-features":R,"has-missing-blocks":T?.length,"require-pro":!ne&&F,"is-loading-data":!!n?.loadingFullData||u,"is-inserting":r?.insertingItem===n.id,"is-existing":M})},(n?.loadingFullData||r?.insertingItem===n.id||u)&&(0,h.createElement)(x.Spinner,null),(0,h.createElement)("div",{className:"template-item__preview-wrapper",onClick:!B||r?.insertingItem||K||u?v.noop:()=>{M?z(!0):G()}},U),(0,h.createElement)("div",{className:"template-item__footer scrollbar"},(0,h.createElement)("div",{className:"template-item__title-details"},"block"===f?(0,h.createElement)(kn,{title:H,item:n}):(0,h.createElement)(xn,{title:H,item:n})),$&&(0,h.createElement)("div",{className:"template-item__description"},$),(0,h.createElement)("div",{className:"template-item__actions"},"block"===f&&(0,h.createElement)(rn,{items:null!==(t=n?.variations)&&void 0!==t?t:[],label:(0,k.__)("Variations:","content-blocks-builder"),type:"variation"}),(0,h.createElement)(tn,{links:C,label:(0,k.__)("Tutorials:","content-blocks-builder")}),(0,h.createElement)(tn,{links:N,label:(0,k.__)("Resources:","content-blocks-builder")}),(0,h.createElement)(tn,{links:P.map((e=>{let{slug:t,name:n}=e;return{url:`https://wordpress.org/plugins/${t}`,title:n}})),label:(0,k.__)("Dependencies:","content-blocks-builder")})),(0,h.createElement)(an,{missingPlugins:L,inactivePlugins:I,libraryState:r,updateLibraryState:a,onPluginInstalled:e=>{w([..._,{type:"success",message:(0,k.sprintf)((0,k.__)("The plugin %s has been installed and activated.","content-blocks-builder"),e)},{type:"success",message:(0,k.__)("Reloading the page.","content-blocks-builder")}]),b()},onPluginActivated:e=>{w([..._,{type:"success",message:(0,k.sprintf)((0,k.__)("The plugin %s has been activated.","content-blocks-builder"),e)},{type:"success",message:(0,k.__)("Reloading the page.","content-blocks-builder")}]),b()}}))),M&&(0,h.createElement)(wn,{isOpen:D,setIsOpen:z,handleConfirm:()=>{G(),z(!1)},content:(0,h.createElement)(h.Fragment,null,(0,k.sprintf)((0,k.__)("Are you sure to import this %s?","content-blocks-builder"),f),(0,h.createElement)("br",null),(0,h.createElement)("strong",null,(0,k.sprintf)((0,k.__)("This will override the existing %s!","content-blocks-builder"),f)))}))};(0,T.registerCoreBlocks)();const Sn=t=>{let{className:n}=t;const r="block",{getPlugins:o}=(0,e.useSelect)((e=>e(ee)),[]),{getBlocks:l,getBlockKeywords:s,getBlockLibraryState:c}=(0,e.useSelect)((e=>e(f)),[]),d=(0,e.useSelect)((e=>e(S.store).canUser("create","users")),[]);let u=o();const p=ae("getPlugins");let m=ie("getPlugins");(0,v.isUndefined)(d)||d||(m=!0);const g=l(),b=ae("getBlocks",[],f),_=ie("getBlocks",[],f),w=s(),T=ae("getBlockKeywords",[],f),[A,C]=O(`${se}-${r}`),[N,P]=(0,h.useReducer)(de,ce,(()=>{const e=Object.assign(ce,c(),null!=A?A:{});return C(null),e})),{sortType:L,selectedKeywords:I,isOpenHelp:F,currentPage:R=1,pageSize:M=6,importedItems:B}=N,D=e=>((e,t)=>e({type:"UPDATE_STATE",payload:t}))(P,e),[z,U]=((t,n)=>{const{pageSize:r,currentPage:a,sortType:i,selectedKeywords:o,contentType:l="block"}=t,{loadFullBlocks:s,loadFullVariations:c}=(0,e.useDispatch)(f),d="block"===l?s:c,u=o.join(","),p=(0,h.useMemo)((()=>oe(o,n)),[u,n]),m=(0,h.useMemo)((()=>p.sort(function(e){let t,n=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{"30_days":"count_30","7_days":"count_7"};return"featured"===e?t=(e,t)=>t.order-e.order:"latest"===e?t=(e,t)=>t.id-e.id:"30_days"===e?t=(e,t)=>t.meta[n["30_days"]]-e.meta[n["30_days"]]:"7_days"===e&&(t=(e,t)=>t.meta[n["7_days"]]-e.meta[n["7_days"]]),t}(i,{"30_days":"boldblocks_download_30_count","7_days":"boldblocks_download_7_count"}))),[p,i]);let g=m.slice((a-1)*r,r*a);g=g.map((e=>({...e,loadingFullData:!e?.slug})));let b=g.map((e=>{let{id:t,loadingFullData:n}=e;return!!n&&t})).filter((e=>e));const[,y]=(0,h.useReducer)((()=>({})));return b.length&&d(b).then((()=>{y()})),[g,m]})(N,g),H=b||p||(0,v.isUndefined)(d),j=_&&m,V=(0,E.getBlockTypes)(),$=(0,v.map)(V,"name"),G=e=>{let{item:t,isExisting:n,localBlocks:i,localVariations:o,setImportingMessages:l,setIsOpenConfirm:s}=e;const c=!!n&&i.find((e=>{let{slug:n}=e;return n===t?.slug}));(e=>{let{contentType:t,item:n,existingId:r=!1,localVariations:i,finishCallback:o}=e;"block"===t?(async e=>{let{item:t,existingId:n=!1,localVariations:r,finishCallback:i=v.noop}=e;const{title:o,slug:l,content:s,keywords:c,variations:d,parentVariations:u,meta:{boldblocks_is_pro:p,boldblocks_has_pro_features:m,boldblocks_download_count:f,boldblocks_download_7_count:g,boldblocks_download_30_count:h,boldblocks_download_stats:b,boldblocks_tutorials:y,boldblocks_external_resources:_,...w}={}}=t,x={title:o,slug:l,content:s,meta:w,keywords:c},E=[];d&&d?.length&&E.push(...d.map((e=>{let{title:t,content:n,slug:r,meta:a}=e;return{title:t,content:n,slug:r,meta:a}}))),u&&u?.length&&E.push(...u.map((e=>{let{title:t,content:n,slug:r,meta:a}=e;return{title:t,content:n,slug:r,meta:a}})));let S=[];a()({path:n?`wp/v2/boldblocks-blocks/${n}`:"wp/v2/boldblocks-blocks",method:"POST",data:{...x,status:"publish"}}).then((e=>(S.push({id:e.id,slug:x.slug,type:"success",message:(0,k.sprintf)((0,k.__)("The block '%s' has been imported successfully.","content-blocks-builder"),x.title)}),E.length?Promise.all(E.map((async e=>{const t=ue(e.slug,r);return a()({path:t?`wp/v2/boldblocks-variations/${t.id}`:"boldblocks/v1/createVariation",method:"POST",data:{...e,status:"publish"}}).then((n=>({id:t?n.id:n?.post?.id,slug:e.slug,type:"success",message:(0,k.sprintf)((0,k.__)("The variation '%s' has been imported successfully.","content-blocks-builder"),e.title)}))).catch((t=>(console.error(t),{slug:e.slug,type:"error",message:(0,k.sprintf)((0,k.__)("Failed to import variation: '%s'.","content-blocks-builder"),e.title)})))}))).then((e=>{S.push(...e),i(S)})):i(S),S))).catch((e=>(console.error(e),S.push({slug:x.slug,type:"error",message:(0,k.sprintf)((0,k.__)("Failed to import block: '%s'.","content-blocks-builder"),x.title)}),i(S),S)))})({item:n,existingId:r,localVariations:i,finishCallback:o}):(async e=>{let{item:t,existingId:n=!1,finishCallback:r=v.noop}=e;const{title:i,slug:o,content:l,meta:{boldblocks_is_pro:s,boldblocks_has_pro_features:c,boldblocks_download_count:d,boldblocks_download_7_count:u,boldblocks_download_30_count:p,boldblocks_download_stats:m,boldblocks_tutorials:f,boldblocks_external_resources:g,boldblocks_is_queryable:h,...b}={}}=t,y={title:i,slug:o,content:l,meta:b};let _=[];a()({path:n?`wp/v2/boldblocks-variations/${n}`:"boldblocks/v1/createVariation",method:"POST",data:{...y,status:"publish"}}).then((e=>(_.push({id:n?e.id:e?.post?.id,slug:y.slug,type:"success",message:(0,k.sprintf)((0,k.__)("The variation '%s' has been imported successfully.","content-blocks-builder"),y.title)}),r(_),_))).catch((e=>(console.error(e),_.push({slug:y.slug,type:"error",message:(0,k.sprintf)((0,k.__)("Failed to import variation: '%s'.","content-blocks-builder"),y.title)}),r(_),_)))})({item:n,existingId:r,finishCallback:o})})({contentType:r,item:t,existingId:!!c&&c.id,localVariations:o,finishCallback:e=>{D({insertingItem:"",importedItems:[...B,...e.map((e=>{let{slug:t}=e;return t}))]}),l(e),s(!1)}}),fetch(`${function(){let e="block"===(arguments.length>0&&void 0!==arguments[0]?arguments[0]:"block")?window?.BoldBlocksBlockLibrary?.URL:window?.BoldBlocksVariationLibrary?.URL;return e||(e="https://boldpatterns.net"),e}()}/wp-json/boldblocks/v1/submitDownloadBlock`,{method:"POST",headers:{"Content-Type":"application/json"},body:JSON.stringify({id:t.id}),credentials:"omit"})},{records:K,isResolving:W}=(0,S.useEntityRecords)("postType","boldblocks_block",{per_page:100}),{records:q,isResolving:Y}=(0,S.useEntityRecords)("postType","boldblocks_variation",{per_page:100}),[J,X]=(0,h.useState)([]),Z=(0,h.useMemo)((()=>({libraryState:N,updateLibraryState:D,availableBlockNames:$,plugins:u,canManagePlugins:d,onImportItem:G,localBlocks:K,localVariations:q,isResolvingLocalData:W||Y,importingMessages:J,setImportingMessages:X,contentType:r})),[N,D,$,u,d,G,K,W,q,Y,J,X,r]);return(0,h.createElement)(le.Provider,{value:Z},(0,h.createElement)(zt,{className:y()("template-modal",n,{"is-locked":N?.installingPlugins?.length||N?.activatingPlugins?.length||N?.insertingItem||N?.isReloading})},J&&(0,h.createElement)("div",{className:"block-library-notices"},J.map((e=>{let{type:t,message:n,slug:r,id:a}=e;return(0,h.createElement)(x.Notice,{key:r,className:"block-library-notice",status:t,isDismissible:!0,onRemove:()=>X([...J.filter((e=>e.slug!==r))]),actions:a?[{label:(0,k.__)("Edit item","content-blocks-builder"),url:(o=a,(0,i.addQueryArgs)(`post.php?post=${o}&action=edit`))}]:[]},(0,h.createElement)("span",{className:"notice-message"},n));var o}))),(0,h.createElement)("div",{className:"template-header"},(0,h.createElement)("div",{className:"sort-box"},(0,h.createElement)(gn,{onChange:e=>{D({sortType:e,currentPage:1})},value:L})),(0,h.createElement)(cn,{items:U.length!==g.length?U:[],keywords:w,isLoading:T,selectedKeywords:I,onChange:e=>{D({selectedKeywords:e,currentPage:1})}}),(0,h.createElement)(yn,{isOpenHelp:F,onToggleHelp:()=>{D({isOpenHelp:!F})}})),F&&(0,h.createElement)(x.Notice,{status:"info",className:"template-modal__legend",isDismissible:!1},(0,h.createElement)("p",null,(0,h.createElement)("strong",null,(0,k.__)("Click on the preview to import.","content-blocks-builder"))),(0,h.createElement)("p",null,(0,k.__)("Items with Pro features like parallax, animations... require the Pro version to work full-functional. They will still work perfectly fine on the Free version but without Pro features.","content-blocks-builder")),(0,h.createElement)("p",null,(0,k.__)("Some items require blocks in external plugins. You have to install and activate those required plugins to use those items. All external plugins included in blocks are developed and maintained by us, so they are safe to use.","content-blocks-builder"))),H&&(0,h.createElement)(x.Spinner,null),(0,h.createElement)("div",{className:"template-list-wrapper scrollbar"},j&&(0,h.createElement)(h.Fragment,null,(0,h.createElement)(on,{items:z,allItems:U,onChangePage:e=>{D({currentPage:e})},currentPage:R,pageSize:M,TemplateItemControl:En})))))};w()((()=>{(0,h.render)((0,h.createElement)(Sn,null),document.querySelector(".js-boldblocks-settings-root"))}))}()}();