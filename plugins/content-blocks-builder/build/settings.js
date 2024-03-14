!function(){var e={2485:function(e,t){var n;!function(){"use strict";var r={}.hasOwnProperty;function a(){for(var e=[],t=0;t<arguments.length;t++){var n=arguments[t];if(n){var o=typeof n;if("string"===o||"number"===o)e.push(n);else if(Array.isArray(n)){if(n.length){var i=a.apply(null,n);i&&e.push(i)}}else if("object"===o){if(n.toString!==Object.prototype.toString&&!n.toString.toString().includes("[native code]")){e.push(n.toString());continue}for(var l in n)r.call(n,l)&&n[l]&&e.push(l)}}}return e.join(" ")}e.exports?(a.default=a,e.exports=a):void 0===(n=function(){return a}.apply(t,[]))||(e.exports=n)}()},2838:function(e){e.exports=function(){"use strict";function e(t){return e="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},e(t)}function t(e,n){return t=Object.setPrototypeOf||function(e,t){return e.__proto__=t,e},t(e,n)}function n(e,r,a){return n=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Boolean.prototype.valueOf.call(Reflect.construct(Boolean,[],(function(){}))),!0}catch(e){return!1}}()?Reflect.construct:function(e,n,r){var a=[null];a.push.apply(a,n);var o=new(Function.bind.apply(e,a));return r&&t(o,r.prototype),o},n.apply(null,arguments)}function r(e){return function(e){if(Array.isArray(e))return a(e)}(e)||function(e){if("undefined"!=typeof Symbol&&null!=e[Symbol.iterator]||null!=e["@@iterator"])return Array.from(e)}(e)||function(e,t){if(e){if("string"==typeof e)return a(e,t);var n=Object.prototype.toString.call(e).slice(8,-1);return"Object"===n&&e.constructor&&(n=e.constructor.name),"Map"===n||"Set"===n?Array.from(e):"Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)?a(e,t):void 0}}(e)||function(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function a(e,t){(null==t||t>e.length)&&(t=e.length);for(var n=0,r=new Array(t);n<t;n++)r[n]=e[n];return r}var o=Object.hasOwnProperty,i=Object.setPrototypeOf,l=Object.isFrozen,s=Object.getPrototypeOf,c=Object.getOwnPropertyDescriptor,u=Object.freeze,d=Object.seal,m=Object.create,p="undefined"!=typeof Reflect&&Reflect,f=p.apply,g=p.construct;f||(f=function(e,t,n){return e.apply(t,n)}),u||(u=function(e){return e}),d||(d=function(e){return e}),g||(g=function(e,t){return n(e,r(t))});var h,b=N(Array.prototype.forEach),y=N(Array.prototype.pop),v=N(Array.prototype.push),_=N(String.prototype.toLowerCase),k=N(String.prototype.toString),E=N(String.prototype.match),w=N(String.prototype.replace),x=N(String.prototype.indexOf),S=N(String.prototype.trim),C=N(RegExp.prototype.test),T=(h=TypeError,function(){for(var e=arguments.length,t=new Array(e),n=0;n<e;n++)t[n]=arguments[n];return g(h,t)});function N(e){return function(t){for(var n=arguments.length,r=new Array(n>1?n-1:0),a=1;a<n;a++)r[a-1]=arguments[a];return f(e,t,r)}}function F(e,t,n){var r;n=null!==(r=n)&&void 0!==r?r:_,i&&i(e,null);for(var a=t.length;a--;){var o=t[a];if("string"==typeof o){var s=n(o);s!==o&&(l(t)||(t[a]=s),o=s)}e[o]=!0}return e}function A(e){var t,n=m(null);for(t in e)!0===f(o,e,[t])&&(n[t]=e[t]);return n}function L(e,t){for(;null!==e;){var n=c(e,t);if(n){if(n.get)return N(n.get);if("function"==typeof n.value)return N(n.value)}e=s(e)}return function(e){return console.warn("fallback value for",e),null}}var P=u(["a","abbr","acronym","address","area","article","aside","audio","b","bdi","bdo","big","blink","blockquote","body","br","button","canvas","caption","center","cite","code","col","colgroup","content","data","datalist","dd","decorator","del","details","dfn","dialog","dir","div","dl","dt","element","em","fieldset","figcaption","figure","font","footer","form","h1","h2","h3","h4","h5","h6","head","header","hgroup","hr","html","i","img","input","ins","kbd","label","legend","li","main","map","mark","marquee","menu","menuitem","meter","nav","nobr","ol","optgroup","option","output","p","picture","pre","progress","q","rp","rt","ruby","s","samp","section","select","shadow","small","source","spacer","span","strike","strong","style","sub","summary","sup","table","tbody","td","template","textarea","tfoot","th","thead","time","tr","track","tt","u","ul","var","video","wbr"]),O=u(["svg","a","altglyph","altglyphdef","altglyphitem","animatecolor","animatemotion","animatetransform","circle","clippath","defs","desc","ellipse","filter","font","g","glyph","glyphref","hkern","image","line","lineargradient","marker","mask","metadata","mpath","path","pattern","polygon","polyline","radialgradient","rect","stop","style","switch","symbol","text","textpath","title","tref","tspan","view","vkern"]),M=u(["feBlend","feColorMatrix","feComponentTransfer","feComposite","feConvolveMatrix","feDiffuseLighting","feDisplacementMap","feDistantLight","feFlood","feFuncA","feFuncB","feFuncG","feFuncR","feGaussianBlur","feImage","feMerge","feMergeNode","feMorphology","feOffset","fePointLight","feSpecularLighting","feSpotLight","feTile","feTurbulence"]),R=u(["animate","color-profile","cursor","discard","fedropshadow","font-face","font-face-format","font-face-name","font-face-src","font-face-uri","foreignobject","hatch","hatchpath","mesh","meshgradient","meshpatch","meshrow","missing-glyph","script","set","solidcolor","unknown","use"]),B=u(["math","menclose","merror","mfenced","mfrac","mglyph","mi","mlabeledtr","mmultiscripts","mn","mo","mover","mpadded","mphantom","mroot","mrow","ms","mspace","msqrt","mstyle","msub","msup","msubsup","mtable","mtd","mtext","mtr","munder","munderover"]),D=u(["maction","maligngroup","malignmark","mlongdiv","mscarries","mscarry","msgroup","mstack","msline","msrow","semantics","annotation","annotation-xml","mprescripts","none"]),I=u(["#text"]),U=u(["accept","action","align","alt","autocapitalize","autocomplete","autopictureinpicture","autoplay","background","bgcolor","border","capture","cellpadding","cellspacing","checked","cite","class","clear","color","cols","colspan","controls","controlslist","coords","crossorigin","datetime","decoding","default","dir","disabled","disablepictureinpicture","disableremoteplayback","download","draggable","enctype","enterkeyhint","face","for","headers","height","hidden","high","href","hreflang","id","inputmode","integrity","ismap","kind","label","lang","list","loading","loop","low","max","maxlength","media","method","min","minlength","multiple","muted","name","nonce","noshade","novalidate","nowrap","open","optimum","pattern","placeholder","playsinline","poster","preload","pubdate","radiogroup","readonly","rel","required","rev","reversed","role","rows","rowspan","spellcheck","scope","selected","shape","size","sizes","span","srclang","start","src","srcset","step","style","summary","tabindex","title","translate","type","usemap","valign","value","width","xmlns","slot"]),z=u(["accent-height","accumulate","additive","alignment-baseline","ascent","attributename","attributetype","azimuth","basefrequency","baseline-shift","begin","bias","by","class","clip","clippathunits","clip-path","clip-rule","color","color-interpolation","color-interpolation-filters","color-profile","color-rendering","cx","cy","d","dx","dy","diffuseconstant","direction","display","divisor","dur","edgemode","elevation","end","fill","fill-opacity","fill-rule","filter","filterunits","flood-color","flood-opacity","font-family","font-size","font-size-adjust","font-stretch","font-style","font-variant","font-weight","fx","fy","g1","g2","glyph-name","glyphref","gradientunits","gradienttransform","height","href","id","image-rendering","in","in2","k","k1","k2","k3","k4","kerning","keypoints","keysplines","keytimes","lang","lengthadjust","letter-spacing","kernelmatrix","kernelunitlength","lighting-color","local","marker-end","marker-mid","marker-start","markerheight","markerunits","markerwidth","maskcontentunits","maskunits","max","mask","media","method","mode","min","name","numoctaves","offset","operator","opacity","order","orient","orientation","origin","overflow","paint-order","path","pathlength","patterncontentunits","patterntransform","patternunits","points","preservealpha","preserveaspectratio","primitiveunits","r","rx","ry","radius","refx","refy","repeatcount","repeatdur","restart","result","rotate","scale","seed","shape-rendering","specularconstant","specularexponent","spreadmethod","startoffset","stddeviation","stitchtiles","stop-color","stop-opacity","stroke-dasharray","stroke-dashoffset","stroke-linecap","stroke-linejoin","stroke-miterlimit","stroke-opacity","stroke","stroke-width","style","surfacescale","systemlanguage","tabindex","targetx","targety","transform","transform-origin","text-anchor","text-decoration","text-rendering","textlength","type","u1","u2","unicode","values","viewbox","visibility","version","vert-adv-y","vert-origin-x","vert-origin-y","width","word-spacing","wrap","writing-mode","xchannelselector","ychannelselector","x","x1","x2","xmlns","y","y1","y2","z","zoomandpan"]),H=u(["accent","accentunder","align","bevelled","close","columnsalign","columnlines","columnspan","denomalign","depth","dir","display","displaystyle","encoding","fence","frame","height","href","id","largeop","length","linethickness","lspace","lquote","mathbackground","mathcolor","mathsize","mathvariant","maxsize","minsize","movablelimits","notation","numalign","open","rowalign","rowlines","rowspacing","rowspan","rspace","rquote","scriptlevel","scriptminsize","scriptsizemultiplier","selection","separator","separators","stretchy","subscriptshift","supscriptshift","symmetric","voffset","width","xmlns"]),$=u(["xlink:href","xml:id","xlink:title","xml:space","xmlns:xlink"]),j=d(/\{\{[\w\W]*|[\w\W]*\}\}/gm),V=d(/<%[\w\W]*|[\w\W]*%>/gm),G=d(/\${[\w\W]*}/gm),W=d(/^data-[\-\w.\u00B7-\uFFFF]/),q=d(/^aria-[\-\w]+$/),K=d(/^(?:(?:(?:f|ht)tps?|mailto|tel|callto|cid|xmpp):|[^a-z]|[a-z+.\-]+(?:[^a-z+.\-:]|$))/i),Y=d(/^(?:\w+script|data):/i),Q=d(/[\u0000-\u0020\u00A0\u1680\u180E\u2000-\u2029\u205F\u3000]/g),Z=d(/^html$/i),J=function(){return"undefined"==typeof window?null:window};return function t(){var n=arguments.length>0&&void 0!==arguments[0]?arguments[0]:J(),a=function(e){return t(e)};if(a.version="2.4.7",a.removed=[],!n||!n.document||9!==n.document.nodeType)return a.isSupported=!1,a;var o=n.document,i=n.document,l=n.DocumentFragment,s=n.HTMLTemplateElement,c=n.Node,d=n.Element,m=n.NodeFilter,p=n.NamedNodeMap,f=void 0===p?n.NamedNodeMap||n.MozNamedAttrMap:p,g=n.HTMLFormElement,h=n.DOMParser,N=n.trustedTypes,X=d.prototype,ee=L(X,"cloneNode"),te=L(X,"nextSibling"),ne=L(X,"childNodes"),re=L(X,"parentNode");if("function"==typeof s){var ae=i.createElement("template");ae.content&&ae.content.ownerDocument&&(i=ae.content.ownerDocument)}var oe=function(t,n){if("object"!==e(t)||"function"!=typeof t.createPolicy)return null;var r=null,a="data-tt-policy-suffix";n.currentScript&&n.currentScript.hasAttribute(a)&&(r=n.currentScript.getAttribute(a));var o="dompurify"+(r?"#"+r:"");try{return t.createPolicy(o,{createHTML:function(e){return e},createScriptURL:function(e){return e}})}catch(e){return console.warn("TrustedTypes policy "+o+" could not be created."),null}}(N,o),ie=oe?oe.createHTML(""):"",le=i,se=le.implementation,ce=le.createNodeIterator,ue=le.createDocumentFragment,de=le.getElementsByTagName,me=o.importNode,pe={};try{pe=A(i).documentMode?i.documentMode:{}}catch(e){}var fe={};a.isSupported="function"==typeof re&&se&&void 0!==se.createHTMLDocument&&9!==pe;var ge,he,be=j,ye=V,ve=G,_e=W,ke=q,Ee=Y,we=Q,xe=K,Se=null,Ce=F({},[].concat(r(P),r(O),r(M),r(B),r(I))),Te=null,Ne=F({},[].concat(r(U),r(z),r(H),r($))),Fe=Object.seal(Object.create(null,{tagNameCheck:{writable:!0,configurable:!1,enumerable:!0,value:null},attributeNameCheck:{writable:!0,configurable:!1,enumerable:!0,value:null},allowCustomizedBuiltInElements:{writable:!0,configurable:!1,enumerable:!0,value:!1}})),Ae=null,Le=null,Pe=!0,Oe=!0,Me=!1,Re=!0,Be=!1,De=!1,Ie=!1,Ue=!1,ze=!1,He=!1,$e=!1,je=!0,Ve=!1,Ge=!0,We=!1,qe={},Ke=null,Ye=F({},["annotation-xml","audio","colgroup","desc","foreignobject","head","iframe","math","mi","mn","mo","ms","mtext","noembed","noframes","noscript","plaintext","script","style","svg","template","thead","title","video","xmp"]),Qe=null,Ze=F({},["audio","video","img","source","image","track"]),Je=null,Xe=F({},["alt","class","for","id","label","name","pattern","placeholder","role","summary","title","value","style","xmlns"]),et="http://www.w3.org/1998/Math/MathML",tt="http://www.w3.org/2000/svg",nt="http://www.w3.org/1999/xhtml",rt=nt,at=!1,ot=null,it=F({},[et,tt,nt],k),lt=["application/xhtml+xml","text/html"],st=null,ct=i.createElement("form"),ut=function(e){return e instanceof RegExp||e instanceof Function},dt=function(t){st&&st===t||(t&&"object"===e(t)||(t={}),t=A(t),ge=ge=-1===lt.indexOf(t.PARSER_MEDIA_TYPE)?"text/html":t.PARSER_MEDIA_TYPE,he="application/xhtml+xml"===ge?k:_,Se="ALLOWED_TAGS"in t?F({},t.ALLOWED_TAGS,he):Ce,Te="ALLOWED_ATTR"in t?F({},t.ALLOWED_ATTR,he):Ne,ot="ALLOWED_NAMESPACES"in t?F({},t.ALLOWED_NAMESPACES,k):it,Je="ADD_URI_SAFE_ATTR"in t?F(A(Xe),t.ADD_URI_SAFE_ATTR,he):Xe,Qe="ADD_DATA_URI_TAGS"in t?F(A(Ze),t.ADD_DATA_URI_TAGS,he):Ze,Ke="FORBID_CONTENTS"in t?F({},t.FORBID_CONTENTS,he):Ye,Ae="FORBID_TAGS"in t?F({},t.FORBID_TAGS,he):{},Le="FORBID_ATTR"in t?F({},t.FORBID_ATTR,he):{},qe="USE_PROFILES"in t&&t.USE_PROFILES,Pe=!1!==t.ALLOW_ARIA_ATTR,Oe=!1!==t.ALLOW_DATA_ATTR,Me=t.ALLOW_UNKNOWN_PROTOCOLS||!1,Re=!1!==t.ALLOW_SELF_CLOSE_IN_ATTR,Be=t.SAFE_FOR_TEMPLATES||!1,De=t.WHOLE_DOCUMENT||!1,ze=t.RETURN_DOM||!1,He=t.RETURN_DOM_FRAGMENT||!1,$e=t.RETURN_TRUSTED_TYPE||!1,Ue=t.FORCE_BODY||!1,je=!1!==t.SANITIZE_DOM,Ve=t.SANITIZE_NAMED_PROPS||!1,Ge=!1!==t.KEEP_CONTENT,We=t.IN_PLACE||!1,xe=t.ALLOWED_URI_REGEXP||xe,rt=t.NAMESPACE||nt,Fe=t.CUSTOM_ELEMENT_HANDLING||{},t.CUSTOM_ELEMENT_HANDLING&&ut(t.CUSTOM_ELEMENT_HANDLING.tagNameCheck)&&(Fe.tagNameCheck=t.CUSTOM_ELEMENT_HANDLING.tagNameCheck),t.CUSTOM_ELEMENT_HANDLING&&ut(t.CUSTOM_ELEMENT_HANDLING.attributeNameCheck)&&(Fe.attributeNameCheck=t.CUSTOM_ELEMENT_HANDLING.attributeNameCheck),t.CUSTOM_ELEMENT_HANDLING&&"boolean"==typeof t.CUSTOM_ELEMENT_HANDLING.allowCustomizedBuiltInElements&&(Fe.allowCustomizedBuiltInElements=t.CUSTOM_ELEMENT_HANDLING.allowCustomizedBuiltInElements),Be&&(Oe=!1),He&&(ze=!0),qe&&(Se=F({},r(I)),Te=[],!0===qe.html&&(F(Se,P),F(Te,U)),!0===qe.svg&&(F(Se,O),F(Te,z),F(Te,$)),!0===qe.svgFilters&&(F(Se,M),F(Te,z),F(Te,$)),!0===qe.mathMl&&(F(Se,B),F(Te,H),F(Te,$))),t.ADD_TAGS&&(Se===Ce&&(Se=A(Se)),F(Se,t.ADD_TAGS,he)),t.ADD_ATTR&&(Te===Ne&&(Te=A(Te)),F(Te,t.ADD_ATTR,he)),t.ADD_URI_SAFE_ATTR&&F(Je,t.ADD_URI_SAFE_ATTR,he),t.FORBID_CONTENTS&&(Ke===Ye&&(Ke=A(Ke)),F(Ke,t.FORBID_CONTENTS,he)),Ge&&(Se["#text"]=!0),De&&F(Se,["html","head","body"]),Se.table&&(F(Se,["tbody"]),delete Ae.tbody),u&&u(t),st=t)},mt=F({},["mi","mo","mn","ms","mtext"]),pt=F({},["foreignobject","desc","title","annotation-xml"]),ft=F({},["title","style","font","a","script"]),gt=F({},O);F(gt,M),F(gt,R);var ht=F({},B);F(ht,D);var bt=function(e){v(a.removed,{element:e});try{e.parentNode.removeChild(e)}catch(t){try{e.outerHTML=ie}catch(t){e.remove()}}},yt=function(e,t){try{v(a.removed,{attribute:t.getAttributeNode(e),from:t})}catch(e){v(a.removed,{attribute:null,from:t})}if(t.removeAttribute(e),"is"===e&&!Te[e])if(ze||He)try{bt(t)}catch(e){}else try{t.setAttribute(e,"")}catch(e){}},vt=function(e){var t,n;if(Ue)e="<remove></remove>"+e;else{var r=E(e,/^[\r\n\t ]+/);n=r&&r[0]}"application/xhtml+xml"===ge&&rt===nt&&(e='<html xmlns="http://www.w3.org/1999/xhtml"><head></head><body>'+e+"</body></html>");var a=oe?oe.createHTML(e):e;if(rt===nt)try{t=(new h).parseFromString(a,ge)}catch(e){}if(!t||!t.documentElement){t=se.createDocument(rt,"template",null);try{t.documentElement.innerHTML=at?ie:a}catch(e){}}var o=t.body||t.documentElement;return e&&n&&o.insertBefore(i.createTextNode(n),o.childNodes[0]||null),rt===nt?de.call(t,De?"html":"body")[0]:De?t.documentElement:o},_t=function(e){return ce.call(e.ownerDocument||e,e,m.SHOW_ELEMENT|m.SHOW_COMMENT|m.SHOW_TEXT,null,!1)},kt=function(t){return"object"===e(c)?t instanceof c:t&&"object"===e(t)&&"number"==typeof t.nodeType&&"string"==typeof t.nodeName},Et=function(e,t,n){fe[e]&&b(fe[e],(function(e){e.call(a,t,n,st)}))},wt=function(e){var t,n;if(Et("beforeSanitizeElements",e,null),(n=e)instanceof g&&("string"!=typeof n.nodeName||"string"!=typeof n.textContent||"function"!=typeof n.removeChild||!(n.attributes instanceof f)||"function"!=typeof n.removeAttribute||"function"!=typeof n.setAttribute||"string"!=typeof n.namespaceURI||"function"!=typeof n.insertBefore||"function"!=typeof n.hasChildNodes))return bt(e),!0;if(C(/[\u0080-\uFFFF]/,e.nodeName))return bt(e),!0;var r=he(e.nodeName);if(Et("uponSanitizeElement",e,{tagName:r,allowedTags:Se}),e.hasChildNodes()&&!kt(e.firstElementChild)&&(!kt(e.content)||!kt(e.content.firstElementChild))&&C(/<[/\w]/g,e.innerHTML)&&C(/<[/\w]/g,e.textContent))return bt(e),!0;if("select"===r&&C(/<template/i,e.innerHTML))return bt(e),!0;if(!Se[r]||Ae[r]){if(!Ae[r]&&St(r)){if(Fe.tagNameCheck instanceof RegExp&&C(Fe.tagNameCheck,r))return!1;if(Fe.tagNameCheck instanceof Function&&Fe.tagNameCheck(r))return!1}if(Ge&&!Ke[r]){var o=re(e)||e.parentNode,i=ne(e)||e.childNodes;if(i&&o)for(var l=i.length-1;l>=0;--l)o.insertBefore(ee(i[l],!0),te(e))}return bt(e),!0}return e instanceof d&&!function(e){var t=re(e);t&&t.tagName||(t={namespaceURI:rt,tagName:"template"});var n=_(e.tagName),r=_(t.tagName);return!!ot[e.namespaceURI]&&(e.namespaceURI===tt?t.namespaceURI===nt?"svg"===n:t.namespaceURI===et?"svg"===n&&("annotation-xml"===r||mt[r]):Boolean(gt[n]):e.namespaceURI===et?t.namespaceURI===nt?"math"===n:t.namespaceURI===tt?"math"===n&&pt[r]:Boolean(ht[n]):e.namespaceURI===nt?!(t.namespaceURI===tt&&!pt[r])&&!(t.namespaceURI===et&&!mt[r])&&!ht[n]&&(ft[n]||!gt[n]):!("application/xhtml+xml"!==ge||!ot[e.namespaceURI]))}(e)?(bt(e),!0):"noscript"!==r&&"noembed"!==r&&"noframes"!==r||!C(/<\/no(script|embed|frames)/i,e.innerHTML)?(Be&&3===e.nodeType&&(t=e.textContent,t=w(t,be," "),t=w(t,ye," "),t=w(t,ve," "),e.textContent!==t&&(v(a.removed,{element:e.cloneNode()}),e.textContent=t)),Et("afterSanitizeElements",e,null),!1):(bt(e),!0)},xt=function(e,t,n){if(je&&("id"===t||"name"===t)&&(n in i||n in ct))return!1;if(Oe&&!Le[t]&&C(_e,t));else if(Pe&&C(ke,t));else if(!Te[t]||Le[t]){if(!(St(e)&&(Fe.tagNameCheck instanceof RegExp&&C(Fe.tagNameCheck,e)||Fe.tagNameCheck instanceof Function&&Fe.tagNameCheck(e))&&(Fe.attributeNameCheck instanceof RegExp&&C(Fe.attributeNameCheck,t)||Fe.attributeNameCheck instanceof Function&&Fe.attributeNameCheck(t))||"is"===t&&Fe.allowCustomizedBuiltInElements&&(Fe.tagNameCheck instanceof RegExp&&C(Fe.tagNameCheck,n)||Fe.tagNameCheck instanceof Function&&Fe.tagNameCheck(n))))return!1}else if(Je[t]);else if(C(xe,w(n,we,"")));else if("src"!==t&&"xlink:href"!==t&&"href"!==t||"script"===e||0!==x(n,"data:")||!Qe[e])if(Me&&!C(Ee,w(n,we,"")));else if(n)return!1;return!0},St=function(e){return e.indexOf("-")>0},Ct=function(t){var n,r,o,i;Et("beforeSanitizeAttributes",t,null);var l=t.attributes;if(l){var s={attrName:"",attrValue:"",keepAttr:!0,allowedAttributes:Te};for(i=l.length;i--;){var c=n=l[i],u=c.name,d=c.namespaceURI;if(r="value"===u?n.value:S(n.value),o=he(u),s.attrName=o,s.attrValue=r,s.keepAttr=!0,s.forceKeepAttr=void 0,Et("uponSanitizeAttribute",t,s),r=s.attrValue,!s.forceKeepAttr&&(yt(u,t),s.keepAttr))if(Re||!C(/\/>/i,r)){Be&&(r=w(r,be," "),r=w(r,ye," "),r=w(r,ve," "));var m=he(t.nodeName);if(xt(m,o,r)){if(!Ve||"id"!==o&&"name"!==o||(yt(u,t),r="user-content-"+r),oe&&"object"===e(N)&&"function"==typeof N.getAttributeType)if(d);else switch(N.getAttributeType(m,o)){case"TrustedHTML":r=oe.createHTML(r);break;case"TrustedScriptURL":r=oe.createScriptURL(r)}try{d?t.setAttributeNS(d,u,r):t.setAttribute(u,r),y(a.removed)}catch(e){}}}else yt(u,t)}Et("afterSanitizeAttributes",t,null)}},Tt=function e(t){var n,r=_t(t);for(Et("beforeSanitizeShadowDOM",t,null);n=r.nextNode();)Et("uponSanitizeShadowNode",n,null),wt(n)||(n.content instanceof l&&e(n.content),Ct(n));Et("afterSanitizeShadowDOM",t,null)};return a.sanitize=function(t){var r,i,s,u,d,m=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{};if((at=!t)&&(t="\x3c!--\x3e"),"string"!=typeof t&&!kt(t)){if("function"!=typeof t.toString)throw T("toString is not a function");if("string"!=typeof(t=t.toString()))throw T("dirty is not a string, aborting")}if(!a.isSupported){if("object"===e(n.toStaticHTML)||"function"==typeof n.toStaticHTML){if("string"==typeof t)return n.toStaticHTML(t);if(kt(t))return n.toStaticHTML(t.outerHTML)}return t}if(Ie||dt(m),a.removed=[],"string"==typeof t&&(We=!1),We){if(t.nodeName){var p=he(t.nodeName);if(!Se[p]||Ae[p])throw T("root node is forbidden and cannot be sanitized in-place")}}else if(t instanceof c)1===(i=(r=vt("\x3c!----\x3e")).ownerDocument.importNode(t,!0)).nodeType&&"BODY"===i.nodeName||"HTML"===i.nodeName?r=i:r.appendChild(i);else{if(!ze&&!Be&&!De&&-1===t.indexOf("<"))return oe&&$e?oe.createHTML(t):t;if(!(r=vt(t)))return ze?null:$e?ie:""}r&&Ue&&bt(r.firstChild);for(var f=_t(We?t:r);s=f.nextNode();)3===s.nodeType&&s===u||wt(s)||(s.content instanceof l&&Tt(s.content),Ct(s),u=s);if(u=null,We)return t;if(ze){if(He)for(d=ue.call(r.ownerDocument);r.firstChild;)d.appendChild(r.firstChild);else d=r;return(Te.shadowroot||Te.shadowrootmod)&&(d=me.call(o,d,!0)),d}var g=De?r.outerHTML:r.innerHTML;return De&&Se["!doctype"]&&r.ownerDocument&&r.ownerDocument.doctype&&r.ownerDocument.doctype.name&&C(Z,r.ownerDocument.doctype.name)&&(g="<!DOCTYPE "+r.ownerDocument.doctype.name+">\n"+g),Be&&(g=w(g,be," "),g=w(g,ye," "),g=w(g,ve," ")),oe&&$e?oe.createHTML(g):g},a.setConfig=function(e){dt(e),Ie=!0},a.clearConfig=function(){st=null,Ie=!1},a.isValidAttribute=function(e,t,n){st||dt({});var r=he(e),a=he(t);return xt(r,a,n)},a.addHook=function(e,t){"function"==typeof t&&(fe[e]=fe[e]||[],v(fe[e],t))},a.removeHook=function(e){if(fe[e])return y(fe[e])},a.removeHooks=function(e){fe[e]&&(fe[e]=[])},a.removeAllHooks=function(){fe={}},a}()}()},2774:function(e){"use strict";e.exports=function e(t,n){if(t===n)return!0;if(t&&n&&"object"==typeof t&&"object"==typeof n){if(t.constructor!==n.constructor)return!1;var r,a,o;if(Array.isArray(t)){if((r=t.length)!=n.length)return!1;for(a=r;0!=a--;)if(!e(t[a],n[a]))return!1;return!0}if(t.constructor===RegExp)return t.source===n.source&&t.flags===n.flags;if(t.valueOf!==Object.prototype.valueOf)return t.valueOf()===n.valueOf();if(t.toString!==Object.prototype.toString)return t.toString()===n.toString();if((r=(o=Object.keys(t)).length)!==Object.keys(n).length)return!1;for(a=r;0!=a--;)if(!Object.prototype.hasOwnProperty.call(n,o[a]))return!1;for(a=r;0!=a--;){var i=o[a];if(!("_owner"===i&&t.$$typeof||e(t[i],n[i])))return!1}return!0}return t!=t&&n!=n}},4717:function(e){e.exports=function(e){var t=function(n,r,a){var o=n.splice(0,50);a=(a=a||[]).concat(e.add(o)),n.length>0?setTimeout((function(){t(n,r,a)}),1):(e.update(),r(a))};return t}},4249:function(e){e.exports=function(e){return e.handlers.filterStart=e.handlers.filterStart||[],e.handlers.filterComplete=e.handlers.filterComplete||[],function(t){if(e.trigger("filterStart"),e.i=1,e.reset.filter(),void 0===t)e.filtered=!1;else{e.filtered=!0;for(var n=e.items,r=0,a=n.length;r<a;r++){var o=n[r];t(o)?o.filtered=!0:o.filtered=!1}}return e.update(),e.trigger("filterComplete"),e.visibleItems}}},4844:function(e,t,n){n(5981);var r=n(6332),a=n(433),o=n(8340),i=n(378),l=n(7481);e.exports=function(e,t){t=a({location:0,distance:100,threshold:.4,multiSearch:!0,searchClass:"fuzzy-search"},t=t||{});var n={search:function(r,a){for(var o=t.multiSearch?r.replace(/ +$/,"").split(/ +/):[r],i=0,l=e.items.length;i<l;i++)n.item(e.items[i],a,o)},item:function(e,t,r){for(var a=!0,o=0;o<r.length;o++){for(var i=!1,l=0,s=t.length;l<s;l++)n.values(e.values(),t[l],r[o])&&(i=!0);i||(a=!1)}e.found=a},values:function(e,n,r){if(e.hasOwnProperty(n)){var a=o(e[n]).toLowerCase();if(l(a,r,t))return!0}return!1}};return r.bind(i(e.listContainer,t.searchClass),"keyup",e.utils.events.debounce((function(t){var r=t.target||t.srcElement;e.search(r.value,n.search)}),e.searchDelay)),function(t,r){e.search(t,r,n.search)}}},9799:function(e,t,n){var r=n(2813),a=n(378),o=n(433),i=n(2859),l=n(6332),s=n(8340),c=n(5981),u=n(8200),d=n(9212);e.exports=function(e,t,m){var p,f=this,g=n(6608)(f),h=n(4717)(f),b=n(3195)(f);p={start:function(){f.listClass="list",f.searchClass="search",f.sortClass="sort",f.page=1e4,f.i=1,f.items=[],f.visibleItems=[],f.matchingItems=[],f.searched=!1,f.filtered=!1,f.searchColumns=void 0,f.searchDelay=0,f.handlers={updated:[]},f.valueNames=[],f.utils={getByClass:a,extend:o,indexOf:i,events:l,toString:s,naturalSort:r,classes:c,getAttribute:u,toArray:d},f.utils.extend(f,t),f.listContainer="string"==typeof e?document.getElementById(e):e,f.listContainer&&(f.list=a(f.listContainer,f.listClass,!0),f.parse=n(8672)(f),f.templater=n(4939)(f),f.search=n(4647)(f),f.filter=n(4249)(f),f.sort=n(6343)(f),f.fuzzySearch=n(4844)(f,t.fuzzySearch),this.handlers(),this.items(),this.pagination(),f.update())},handlers:function(){for(var e in f.handlers)f[e]&&f.handlers.hasOwnProperty(e)&&f.on(e,f[e])},items:function(){f.parse(f.list),void 0!==m&&f.add(m)},pagination:function(){if(void 0!==t.pagination){!0===t.pagination&&(t.pagination=[{}]),void 0===t.pagination[0]&&(t.pagination=[t.pagination]);for(var e=0,n=t.pagination.length;e<n;e++)b(t.pagination[e])}}},this.reIndex=function(){f.items=[],f.visibleItems=[],f.matchingItems=[],f.searched=!1,f.filtered=!1,f.parse(f.list)},this.toJSON=function(){for(var e=[],t=0,n=f.items.length;t<n;t++)e.push(f.items[t].values());return e},this.add=function(e,t){if(0!==e.length){if(!t){var n=[],r=!1;void 0===e[0]&&(e=[e]);for(var a=0,o=e.length;a<o;a++){var i;r=f.items.length>f.page,i=new g(e[a],void 0,r),f.items.push(i),n.push(i)}return f.update(),n}h(e.slice(0),t)}},this.show=function(e,t){return this.i=e,this.page=t,f.update(),f},this.remove=function(e,t,n){for(var r=0,a=0,o=f.items.length;a<o;a++)f.items[a].values()[e]==t&&(f.templater.remove(f.items[a],n),f.items.splice(a,1),o--,a--,r++);return f.update(),r},this.get=function(e,t){for(var n=[],r=0,a=f.items.length;r<a;r++){var o=f.items[r];o.values()[e]==t&&n.push(o)}return n},this.size=function(){return f.items.length},this.clear=function(){return f.templater.clear(),f.items=[],f},this.on=function(e,t){return f.handlers[e].push(t),f},this.off=function(e,t){var n=f.handlers[e],r=i(n,t);return r>-1&&n.splice(r,1),f},this.trigger=function(e){for(var t=f.handlers[e].length;t--;)f.handlers[e][t](f);return f},this.reset={filter:function(){for(var e=f.items,t=e.length;t--;)e[t].filtered=!1;return f},search:function(){for(var e=f.items,t=e.length;t--;)e[t].found=!1;return f}},this.update=function(){var e=f.items,t=e.length;f.visibleItems=[],f.matchingItems=[],f.templater.clear();for(var n=0;n<t;n++)e[n].matching()&&f.matchingItems.length+1>=f.i&&f.visibleItems.length<f.page?(e[n].show(),f.visibleItems.push(e[n]),f.matchingItems.push(e[n])):e[n].matching()?(f.matchingItems.push(e[n]),e[n].hide()):e[n].hide();return f.trigger("updated"),f},p.start()}},6608:function(e){e.exports=function(e){return function(t,n,r){var a=this;this._values={},this.found=!1,this.filtered=!1,this.values=function(t,n){if(void 0===t)return a._values;for(var r in t)a._values[r]=t[r];!0!==n&&e.templater.set(a,a.values())},this.show=function(){e.templater.show(a)},this.hide=function(){e.templater.hide(a)},this.matching=function(){return e.filtered&&e.searched&&a.found&&a.filtered||e.filtered&&!e.searched&&a.filtered||!e.filtered&&e.searched&&a.found||!e.filtered&&!e.searched},this.visible=function(){return!(!a.elm||a.elm.parentNode!=e.list)},function(t,n,r){if(void 0===n)r?a.values(t,r):a.values(t);else{a.elm=n;var o=e.templater.get(a,t);a.values(o)}}(t,n,r)}}},3195:function(e,t,n){var r=n(5981),a=n(6332),o=n(9799);e.exports=function(e){var t=!1,n=function(n,a){if(e.page<1)return e.listContainer.style.display="none",void(t=!0);t&&(e.listContainer.style.display="block");var o,l=e.matchingItems.length,s=e.i,c=e.page,u=Math.ceil(l/c),d=Math.ceil(s/c),m=a.innerWindow||2,p=a.left||a.outerWindow||0,f=a.right||a.outerWindow||0;f=u-f,n.clear();for(var g=1;g<=u;g++){var h=d===g?"active":"";i.number(g,p,f,d,m)?(o=n.add({page:g,dotted:!1})[0],h&&r(o.elm).add(h),o.elm.firstChild.setAttribute("data-i",g),o.elm.firstChild.setAttribute("data-page",c)):i.dotted(n,g,p,f,d,m,n.size())&&(o=n.add({page:"...",dotted:!0})[0],r(o.elm).add("disabled"))}},i={number:function(e,t,n,r,a){return this.left(e,t)||this.right(e,n)||this.innerWindow(e,r,a)},left:function(e,t){return e<=t},right:function(e,t){return e>t},innerWindow:function(e,t,n){return e>=t-n&&e<=t+n},dotted:function(e,t,n,r,a,o,i){return this.dottedLeft(e,t,n,r,a,o)||this.dottedRight(e,t,n,r,a,o,i)},dottedLeft:function(e,t,n,r,a,o){return t==n+1&&!this.innerWindow(t,a,o)&&!this.right(t,r)},dottedRight:function(e,t,n,r,a,o,i){return!e.items[i-1].values().dotted&&t==r&&!this.innerWindow(t,a,o)&&!this.right(t,r)}};return function(t){var r=new o(e.listContainer.id,{listClass:t.paginationClass||"pagination",item:t.item||"<li><a class='page' href='#'></a></li>",valueNames:["page","dotted"],searchClass:"pagination-search-that-is-not-supposed-to-exist",sortClass:"pagination-sort-that-is-not-supposed-to-exist"});a.bind(r.listContainer,"click",(function(t){var n=t.target||t.srcElement,r=e.utils.getAttribute(n,"data-page"),a=e.utils.getAttribute(n,"data-i");a&&e.show((a-1)*r+1,r)})),e.on("updated",(function(){n(r,t)})),n(r,t)}}},8672:function(e,t,n){e.exports=function(e){var t=n(6608)(e),r=function(n,r){for(var a=0,o=n.length;a<o;a++)e.items.push(new t(r,n[a]))},a=function(t,n){var o=t.splice(0,50);r(o,n),t.length>0?setTimeout((function(){a(t,n)}),1):(e.update(),e.trigger("parseComplete"))};return e.handlers.parseComplete=e.handlers.parseComplete||[],function(){var t=function(e){for(var t=e.childNodes,n=[],r=0,a=t.length;r<a;r++)void 0===t[r].data&&n.push(t[r]);return n}(e.list),n=e.valueNames;e.indexAsync?a(t,n):r(t,n)}}},4647:function(e){e.exports=function(e){var t,n,r,a={resetList:function(){e.i=1,e.templater.clear(),r=void 0},setOptions:function(e){2==e.length&&e[1]instanceof Array?t=e[1]:2==e.length&&"function"==typeof e[1]?(t=void 0,r=e[1]):3==e.length?(t=e[1],r=e[2]):t=void 0},setColumns:function(){0!==e.items.length&&void 0===t&&(t=void 0===e.searchColumns?a.toArray(e.items[0].values()):e.searchColumns)},setSearchString:function(t){t=(t=e.utils.toString(t).toLowerCase()).replace(/[-[\]{}()*+?.,\\^$|#]/g,"\\$&"),n=t},toArray:function(e){var t=[];for(var n in e)t.push(n);return t}},o=function(o){return e.trigger("searchStart"),a.resetList(),a.setSearchString(o),a.setOptions(arguments),a.setColumns(),""===n?(e.reset.search(),e.searched=!1):(e.searched=!0,r?r(n,t):function(){for(var r,a=[],o=n;null!==(r=o.match(/"([^"]+)"/));)a.push(r[1]),o=o.substring(0,r.index)+o.substring(r.index+r[0].length);(o=o.trim()).length&&(a=a.concat(o.split(/\s+/)));for(var i=0,l=e.items.length;i<l;i++){var s=e.items[i];if(s.found=!1,a.length){for(var c=0,u=a.length;c<u;c++){for(var d=!1,m=0,p=t.length;m<p;m++){var f=s.values(),g=t[m];if(f.hasOwnProperty(g)&&void 0!==f[g]&&null!==f[g]&&-1!==("string"!=typeof f[g]?f[g].toString():f[g]).toLowerCase().indexOf(a[c])){d=!0;break}}if(!d)break}s.found=d}}}()),e.update(),e.trigger("searchComplete"),e.visibleItems};return e.handlers.searchStart=e.handlers.searchStart||[],e.handlers.searchComplete=e.handlers.searchComplete||[],e.utils.events.bind(e.utils.getByClass(e.listContainer,e.searchClass),"keyup",e.utils.events.debounce((function(t){var n=t.target||t.srcElement;""===n.value&&!e.searched||o(n.value)}),e.searchDelay)),e.utils.events.bind(e.utils.getByClass(e.listContainer,e.searchClass),"input",(function(e){""===(e.target||e.srcElement).value&&o("")})),o}},6343:function(e){e.exports=function(e){var t={els:void 0,clear:function(){for(var n=0,r=t.els.length;n<r;n++)e.utils.classes(t.els[n]).remove("asc"),e.utils.classes(t.els[n]).remove("desc")},getOrder:function(t){var n=e.utils.getAttribute(t,"data-order");return"asc"==n||"desc"==n?n:e.utils.classes(t).has("desc")?"asc":e.utils.classes(t).has("asc")?"desc":"asc"},getInSensitive:function(t,n){var r=e.utils.getAttribute(t,"data-insensitive");n.insensitive="false"!==r},setOrder:function(n){for(var r=0,a=t.els.length;r<a;r++){var o=t.els[r];if(e.utils.getAttribute(o,"data-sort")===n.valueName){var i=e.utils.getAttribute(o,"data-order");"asc"==i||"desc"==i?i==n.order&&e.utils.classes(o).add(n.order):e.utils.classes(o).add(n.order)}}}},n=function(){e.trigger("sortStart");var n={},r=arguments[0].currentTarget||arguments[0].srcElement||void 0;r?(n.valueName=e.utils.getAttribute(r,"data-sort"),t.getInSensitive(r,n),n.order=t.getOrder(r)):((n=arguments[1]||n).valueName=arguments[0],n.order=n.order||"asc",n.insensitive=void 0===n.insensitive||n.insensitive),t.clear(),t.setOrder(n);var a,o=n.sortFunction||e.sortFunction||null,i="desc"===n.order?-1:1;a=o?function(e,t){return o(e,t,n)*i}:function(t,r){var a=e.utils.naturalSort;return a.alphabet=e.alphabet||n.alphabet||void 0,!a.alphabet&&n.insensitive&&(a=e.utils.naturalSort.caseInsensitive),a(t.values()[n.valueName],r.values()[n.valueName])*i},e.items.sort(a),e.update(),e.trigger("sortComplete")};return e.handlers.sortStart=e.handlers.sortStart||[],e.handlers.sortComplete=e.handlers.sortComplete||[],t.els=e.utils.getByClass(e.listContainer,e.sortClass),e.utils.events.bind(t.els,"click",n),e.on("searchStart",t.clear),e.on("filterStart",t.clear),n}},4939:function(e){var t=function(e){var t,n=this,r=function(e){if("string"==typeof e){if(/<tr[\s>]/g.exec(e)){var t=document.createElement("tbody");return t.innerHTML=e,t.firstElementChild}if(-1!==e.indexOf("<")){var n=document.createElement("div");return n.innerHTML=e,n.firstElementChild}}},a=function(t,n,r){var a=void 0,o=function(t){for(var n=0,r=e.valueNames.length;n<r;n++){var a=e.valueNames[n];if(a.data){for(var o=a.data,i=0,l=o.length;i<l;i++)if(o[i]===t)return{data:t}}else{if(a.attr&&a.name&&a.name==t)return a;if(a===t)return t}}}(n);o&&(o.data?t.elm.setAttribute("data-"+o.data,r):o.attr&&o.name?(a=e.utils.getByClass(t.elm,o.name,!0))&&a.setAttribute(o.attr,r):(a=e.utils.getByClass(t.elm,o,!0))&&(a.innerHTML=r))};this.get=function(t,r){n.create(t);for(var a={},o=0,i=r.length;o<i;o++){var l=void 0,s=r[o];if(s.data)for(var c=0,u=s.data.length;c<u;c++)a[s.data[c]]=e.utils.getAttribute(t.elm,"data-"+s.data[c]);else s.attr&&s.name?(l=e.utils.getByClass(t.elm,s.name,!0),a[s.name]=l?e.utils.getAttribute(l,s.attr):""):(l=e.utils.getByClass(t.elm,s,!0),a[s]=l?l.innerHTML:"")}return a},this.set=function(e,t){if(!n.create(e))for(var r in t)t.hasOwnProperty(r)&&a(e,r,t[r])},this.create=function(e){return void 0===e.elm&&(e.elm=t(e.values()),n.set(e,e.values()),!0)},this.remove=function(t){t.elm.parentNode===e.list&&e.list.removeChild(t.elm)},this.show=function(t){n.create(t),e.list.appendChild(t.elm)},this.hide=function(t){void 0!==t.elm&&t.elm.parentNode===e.list&&e.list.removeChild(t.elm)},this.clear=function(){if(e.list.hasChildNodes())for(;e.list.childNodes.length>=1;)e.list.removeChild(e.list.firstChild)},function(){var n;if("function"!=typeof e.item){if(!(n="string"==typeof e.item?-1===e.item.indexOf("<")?document.getElementById(e.item):r(e.item):function(){for(var t=e.list.childNodes,n=0,r=t.length;n<r;n++)if(void 0===t[n].data)return t[n].cloneNode(!0)}()))throw new Error("The list needs to have at least one item on init otherwise you'll have to add a template.");n=function(t,n){var r=t.cloneNode(!0);r.removeAttribute("id");for(var a=0,o=n.length;a<o;a++){var i=void 0,l=n[a];if(l.data)for(var s=0,c=l.data.length;s<c;s++)r.setAttribute("data-"+l.data[s],"");else l.attr&&l.name?(i=e.utils.getByClass(r,l.name,!0))&&i.setAttribute(l.attr,""):(i=e.utils.getByClass(r,l,!0))&&(i.innerHTML="")}return r}(n,e.valueNames),t=function(){return n.cloneNode(!0)}}else t=function(t){var n=e.item(t);return r(n)}}()};e.exports=function(e){return new t(e)}},5981:function(e,t,n){var r=n(2859),a=/\s+/;function o(e){if(!e||!e.nodeType)throw new Error("A DOM element reference is required");this.el=e,this.list=e.classList}Object.prototype.toString,e.exports=function(e){return new o(e)},o.prototype.add=function(e){if(this.list)return this.list.add(e),this;var t=this.array();return~r(t,e)||t.push(e),this.el.className=t.join(" "),this},o.prototype.remove=function(e){if(this.list)return this.list.remove(e),this;var t=this.array(),n=r(t,e);return~n&&t.splice(n,1),this.el.className=t.join(" "),this},o.prototype.toggle=function(e,t){return this.list?(void 0!==t?t!==this.list.toggle(e,t)&&this.list.toggle(e):this.list.toggle(e),this):(void 0!==t?t?this.add(e):this.remove(e):this.has(e)?this.remove(e):this.add(e),this)},o.prototype.array=function(){var e=(this.el.getAttribute("class")||"").replace(/^\s+|\s+$/g,"").split(a);return""===e[0]&&e.shift(),e},o.prototype.has=o.prototype.contains=function(e){return this.list?this.list.contains(e):!!~r(this.array(),e)}},6332:function(e,t,n){var r=window.addEventListener?"addEventListener":"attachEvent",a=window.removeEventListener?"removeEventListener":"detachEvent",o="addEventListener"!==r?"on":"",i=n(9212);t.bind=function(e,t,n,a){for(var l=0,s=(e=i(e)).length;l<s;l++)e[l][r](o+t,n,a||!1)},t.unbind=function(e,t,n,r){for(var l=0,s=(e=i(e)).length;l<s;l++)e[l][a](o+t,n,r||!1)},t.debounce=function(e,t,n){var r;return t?function(){var a=this,o=arguments,i=n&&!r;clearTimeout(r),r=setTimeout((function(){r=null,n||e.apply(a,o)}),t),i&&e.apply(a,o)}:e}},433:function(e){e.exports=function(e){for(var t,n=Array.prototype.slice.call(arguments,1),r=0;t=n[r];r++)if(t)for(var a in t)e[a]=t[a];return e}},7481:function(e){e.exports=function(e,t,n){var r=n.location||0,a=n.distance||100,o=n.threshold||.4;if(t===e)return!0;if(t.length>32)return!1;var i=r,l=function(){var e,n={};for(e=0;e<t.length;e++)n[t.charAt(e)]=0;for(e=0;e<t.length;e++)n[t.charAt(e)]|=1<<t.length-e-1;return n}();function s(e,n){var r=e/t.length,o=Math.abs(i-n);return a?r+o/a:o?1:r}var c=o,u=e.indexOf(t,i);-1!=u&&(c=Math.min(s(0,u),c),-1!=(u=e.lastIndexOf(t,i+t.length))&&(c=Math.min(s(0,u),c)));var d,m,p=1<<t.length-1;u=-1;for(var f,g=t.length+e.length,h=0;h<t.length;h++){for(d=0,m=g;d<m;)s(h,i+m)<=c?d=m:g=m,m=Math.floor((g-d)/2+d);g=m;var b=Math.max(1,i-m+1),y=Math.min(i+m,e.length)+t.length,v=Array(y+2);v[y+1]=(1<<h)-1;for(var _=y;_>=b;_--){var k=l[e.charAt(_-1)];if(v[_]=0===h?(v[_+1]<<1|1)&k:(v[_+1]<<1|1)&k|(f[_+1]|f[_])<<1|1|f[_+1],v[_]&p){var E=s(h,_-1);if(E<=c){if(c=E,!((u=_-1)>i))break;b=Math.max(1,2*i-u)}}}if(s(h+1,i)>c)break;f=v}return!(u<0)}},8200:function(e){e.exports=function(e,t){var n=e.getAttribute&&e.getAttribute(t)||null;if(!n)for(var r=e.attributes,a=r.length,o=0;o<a;o++)void 0!==r[o]&&r[o].nodeName===t&&(n=r[o].nodeValue);return n}},378:function(e){e.exports=function(e,t,n,r){return(r=r||{}).test&&r.getElementsByClassName||!r.test&&document.getElementsByClassName?function(e,t,n){return n?e.getElementsByClassName(t)[0]:e.getElementsByClassName(t)}(e,t,n):r.test&&r.querySelector||!r.test&&document.querySelector?function(e,t,n){return t="."+t,n?e.querySelector(t):e.querySelectorAll(t)}(e,t,n):function(e,t,n){for(var r=[],a=e.getElementsByTagName("*"),o=a.length,i=new RegExp("(^|\\s)"+t+"(\\s|$)"),l=0,s=0;l<o;l++)if(i.test(a[l].className)){if(n)return a[l];r[s]=a[l],s++}return r}(e,t,n)}},2859:function(e){var t=[].indexOf;e.exports=function(e,n){if(t)return e.indexOf(n);for(var r=0,a=e.length;r<a;++r)if(e[r]===n)return r;return-1}},9212:function(e){e.exports=function(e){if(void 0===e)return[];if(null===e)return[null];if(e===window)return[window];if("string"==typeof e)return[e];if(function(e){return"[object Array]"===Object.prototype.toString.call(e)}(e))return e;if("number"!=typeof e.length)return[e];if("function"==typeof e&&e instanceof Function)return[e];for(var t=[],n=0,r=e.length;n<r;n++)(Object.prototype.hasOwnProperty.call(e,n)||n in e)&&t.push(e[n]);return t.length?t:[]}},8340:function(e){e.exports=function(e){return(e=null===(e=void 0===e?"":e)?"":e).toString()}},2813:function(e){"use strict";var t,n,r=0;function a(e){return e>=48&&e<=57}function o(e,t){for(var o=(e+="").length,i=(t+="").length,l=0,s=0;l<o&&s<i;){var c=e.charCodeAt(l),u=t.charCodeAt(s);if(a(c)){if(!a(u))return c-u;for(var d=l,m=s;48===c&&++d<o;)c=e.charCodeAt(d);for(;48===u&&++m<i;)u=t.charCodeAt(m);for(var p=d,f=m;p<o&&a(e.charCodeAt(p));)++p;for(;f<i&&a(t.charCodeAt(f));)++f;var g=p-d-f+m;if(g)return g;for(;d<p;)if(g=e.charCodeAt(d++)-t.charCodeAt(m++))return g;l=p,s=f}else{if(c!==u)return c<r&&u<r&&-1!==n[c]&&-1!==n[u]?n[c]-n[u]:c-u;++l,++s}}return l>=o&&s<i&&o>=i?-1:s>=i&&l<o&&i>=o?1:o-i}o.caseInsensitive=o.i=function(e,t){return o((""+e).toLowerCase(),(""+t).toLowerCase())},Object.defineProperties(o,{alphabet:{get:function(){return t},set:function(e){n=[];var a=0;if(t=e)for(;a<t.length;a++)n[t.charCodeAt(a)]=a;for(r=n.length,a=0;a<r;a++)void 0===n[a]&&(n[a]=-1)}}}),e.exports=o}},t={};function n(r){var a=t[r];if(void 0!==a)return a.exports;var o=t[r]={exports:{}};return e[r].call(o.exports,o,o.exports,n),o.exports}n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,{a:t}),t},n.d=function(e,t){for(var r in t)n.o(t,r)&&!n.o(e,r)&&Object.defineProperty(e,r,{enumerable:!0,get:t[r]})},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},function(){"use strict";var e=window.wp.element,t=window.lodash,r=window.wp.domReady,a=n.n(r),o=window.wp.i18n,i=window.wp.components,l=window.wp.coreData,s=window.wp.data,c=(window.wp.blockEditor,n(2774)),u=n.n(c),d=window.wp.apiFetch,m=n.n(d);const p={headers:{"Content-Type":"application/json"},method:"GET"};n(2838);var f=window.wp.url,g=window.React,h=window.wp.primitives,b=((0,g.createElement)(h.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24"},(0,g.createElement)(h.Path,{d:"M15 4H9c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h6c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm.5 14c0 .3-.2.5-.5.5H9c-.3 0-.5-.2-.5-.5V6c0-.3.2-.5.5-.5h6c.3 0 .5.2.5.5v12zm-4.5-.5h2V16h-2v1.5z"})),(0,g.createElement)(h.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24"},(0,g.createElement)(h.Path,{d:"M17 4H7c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm.5 14c0 .3-.2.5-.5.5H7c-.3 0-.5-.2-.5-.5V6c0-.3.2-.5.5-.5h10c.3 0 .5.2.5.5v12zm-7.5-.5h4V16h-4v1.5z"})),(0,g.createElement)(h.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24"},(0,g.createElement)(h.Path,{d:"M20.5 16h-.7V8c0-1.1-.9-2-2-2H6.2c-1.1 0-2 .9-2 2v8h-.7c-.8 0-1.5.7-1.5 1.5h20c0-.8-.7-1.5-1.5-1.5zM5.7 8c0-.3.2-.5.5-.5h11.6c.3 0 .5.2.5.5v7.6H5.7V8z"})),window.wp.hooks);const y="Mobile",v="Tablet",_="Desktop",k={},E=getComputedStyle(document.documentElement);k[y]=E.getPropertyValue("--wp--custom--breakpoint--sm")||"576px",k[v]=E.getPropertyValue("--wp--custom--breakpoint--md")||"768px",k[_]=E.getPropertyValue("--wp--custom--breakpoint--lg")||"1024px";const w={};Object.keys(k).map((e=>{w[e]=e===y?"":`@media (min-width: ${k[e]})`})),(0,o.__)("Mobile","content-blocks-builder"),w[y],(0,o.__)("Tablet","content-blocks-builder"),w[v],(0,o.__)("Desktop","content-blocks-builder"),w[_];const x=function(e){let t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"log";e&&"development"===window?.BBLOG?.environmentType&&(["log","info","warn","error","debug","dir","table"].includes(t)?console[t](e):console.log(e))},S=(0,e.createContext)();class C{constructor(){let e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:"";e||(e=window.location.href),this.parsedURL=new URL(e)}get(e){let t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:null;return this.parsedURL.searchParams.get(e)||t}set(e,t){let n=!(arguments.length>2&&void 0!==arguments[2])||arguments[2];this.parsedURL.searchParams.set(e,t),n&&history.pushState&&history.pushState({},null,this.parsedURL.href)}delete(e){let t=!(arguments.length>1&&void 0!==arguments[1])||arguments[1];this.parsedURL.searchParams.delete(e),t&&history.pushState&&history.pushState({},null,this.parsedURL.href)}reload(){history?.go?history.go():window.location.reload()}getHref(){return this.parsedURL.href}}function T(){return T=Object.assign?Object.assign.bind():function(e){for(var t=1;t<arguments.length;t++){var n=arguments[t];for(var r in n)Object.prototype.hasOwnProperty.call(n,r)&&(e[r]=n[r])}return e},T.apply(this,arguments)}function N(e){var t=Object.create(null);return function(n){return void 0===t[n]&&(t[n]=e(n)),t[n]}}var F=/^((children|dangerouslySetInnerHTML|key|ref|autoFocus|defaultValue|defaultChecked|innerHTML|suppressContentEditableWarning|suppressHydrationWarning|valueLink|abbr|accept|acceptCharset|accessKey|action|allow|allowUserMedia|allowPaymentRequest|allowFullScreen|allowTransparency|alt|async|autoComplete|autoPlay|capture|cellPadding|cellSpacing|challenge|charSet|checked|cite|classID|className|cols|colSpan|content|contentEditable|contextMenu|controls|controlsList|coords|crossOrigin|data|dateTime|decoding|default|defer|dir|disabled|disablePictureInPicture|download|draggable|encType|enterKeyHint|form|formAction|formEncType|formMethod|formNoValidate|formTarget|frameBorder|headers|height|hidden|high|href|hrefLang|htmlFor|httpEquiv|id|inputMode|integrity|is|keyParams|keyType|kind|label|lang|list|loading|loop|low|marginHeight|marginWidth|max|maxLength|media|mediaGroup|method|min|minLength|multiple|muted|name|nonce|noValidate|open|optimum|pattern|placeholder|playsInline|poster|preload|profile|radioGroup|readOnly|referrerPolicy|rel|required|reversed|role|rows|rowSpan|sandbox|scope|scoped|scrolling|seamless|selected|shape|size|sizes|slot|span|spellCheck|src|srcDoc|srcLang|srcSet|start|step|style|summary|tabIndex|target|title|translate|type|useMap|value|width|wmode|wrap|about|datatype|inlist|prefix|property|resource|typeof|vocab|autoCapitalize|autoCorrect|autoSave|color|incremental|fallback|inert|itemProp|itemScope|itemType|itemID|itemRef|on|option|results|security|unselectable|accentHeight|accumulate|additive|alignmentBaseline|allowReorder|alphabetic|amplitude|arabicForm|ascent|attributeName|attributeType|autoReverse|azimuth|baseFrequency|baselineShift|baseProfile|bbox|begin|bias|by|calcMode|capHeight|clip|clipPathUnits|clipPath|clipRule|colorInterpolation|colorInterpolationFilters|colorProfile|colorRendering|contentScriptType|contentStyleType|cursor|cx|cy|d|decelerate|descent|diffuseConstant|direction|display|divisor|dominantBaseline|dur|dx|dy|edgeMode|elevation|enableBackground|end|exponent|externalResourcesRequired|fill|fillOpacity|fillRule|filter|filterRes|filterUnits|floodColor|floodOpacity|focusable|fontFamily|fontSize|fontSizeAdjust|fontStretch|fontStyle|fontVariant|fontWeight|format|from|fr|fx|fy|g1|g2|glyphName|glyphOrientationHorizontal|glyphOrientationVertical|glyphRef|gradientTransform|gradientUnits|hanging|horizAdvX|horizOriginX|ideographic|imageRendering|in|in2|intercept|k|k1|k2|k3|k4|kernelMatrix|kernelUnitLength|kerning|keyPoints|keySplines|keyTimes|lengthAdjust|letterSpacing|lightingColor|limitingConeAngle|local|markerEnd|markerMid|markerStart|markerHeight|markerUnits|markerWidth|mask|maskContentUnits|maskUnits|mathematical|mode|numOctaves|offset|opacity|operator|order|orient|orientation|origin|overflow|overlinePosition|overlineThickness|panose1|paintOrder|pathLength|patternContentUnits|patternTransform|patternUnits|pointerEvents|points|pointsAtX|pointsAtY|pointsAtZ|preserveAlpha|preserveAspectRatio|primitiveUnits|r|radius|refX|refY|renderingIntent|repeatCount|repeatDur|requiredExtensions|requiredFeatures|restart|result|rotate|rx|ry|scale|seed|shapeRendering|slope|spacing|specularConstant|specularExponent|speed|spreadMethod|startOffset|stdDeviation|stemh|stemv|stitchTiles|stopColor|stopOpacity|strikethroughPosition|strikethroughThickness|string|stroke|strokeDasharray|strokeDashoffset|strokeLinecap|strokeLinejoin|strokeMiterlimit|strokeOpacity|strokeWidth|surfaceScale|systemLanguage|tableValues|targetX|targetY|textAnchor|textDecoration|textRendering|textLength|to|transform|u1|u2|underlinePosition|underlineThickness|unicode|unicodeBidi|unicodeRange|unitsPerEm|vAlphabetic|vHanging|vIdeographic|vMathematical|values|vectorEffect|version|vertAdvY|vertOriginX|vertOriginY|viewBox|viewTarget|visibility|widths|wordSpacing|writingMode|x|xHeight|x1|x2|xChannelSelector|xlinkActuate|xlinkArcrole|xlinkHref|xlinkRole|xlinkShow|xlinkTitle|xlinkType|xmlBase|xmlns|xmlnsXlink|xmlLang|xmlSpace|y|y1|y2|yChannelSelector|z|zoomAndPan|for|class|autofocus)|(([Dd][Aa][Tt][Aa]|[Aa][Rr][Ii][Aa]|x)-.*))$/,A=N((function(e){return F.test(e)||111===e.charCodeAt(0)&&110===e.charCodeAt(1)&&e.charCodeAt(2)<91})),L=function(){function e(e){var t=this;this._insertTag=function(e){var n;n=0===t.tags.length?t.insertionPoint?t.insertionPoint.nextSibling:t.prepend?t.container.firstChild:t.before:t.tags[t.tags.length-1].nextSibling,t.container.insertBefore(e,n),t.tags.push(e)},this.isSpeedy=void 0===e.speedy||e.speedy,this.tags=[],this.ctr=0,this.nonce=e.nonce,this.key=e.key,this.container=e.container,this.prepend=e.prepend,this.insertionPoint=e.insertionPoint,this.before=null}var t=e.prototype;return t.hydrate=function(e){e.forEach(this._insertTag)},t.insert=function(e){this.ctr%(this.isSpeedy?65e3:1)==0&&this._insertTag(function(e){var t=document.createElement("style");return t.setAttribute("data-emotion",e.key),void 0!==e.nonce&&t.setAttribute("nonce",e.nonce),t.appendChild(document.createTextNode("")),t.setAttribute("data-s",""),t}(this));var t=this.tags[this.tags.length-1];if(this.isSpeedy){var n=function(e){if(e.sheet)return e.sheet;for(var t=0;t<document.styleSheets.length;t++)if(document.styleSheets[t].ownerNode===e)return document.styleSheets[t]}(t);try{n.insertRule(e,n.cssRules.length)}catch(e){}}else t.appendChild(document.createTextNode(e));this.ctr++},t.flush=function(){this.tags.forEach((function(e){return e.parentNode&&e.parentNode.removeChild(e)})),this.tags=[],this.ctr=0},e}(),P=Math.abs,O=String.fromCharCode,M=Object.assign;function R(e){return e.trim()}function B(e,t,n){return e.replace(t,n)}function D(e,t){return e.indexOf(t)}function I(e,t){return 0|e.charCodeAt(t)}function U(e,t,n){return e.slice(t,n)}function z(e){return e.length}function H(e){return e.length}function $(e,t){return t.push(e),e}var j=1,V=1,G=0,W=0,q=0,K="";function Y(e,t,n,r,a,o,i){return{value:e,root:t,parent:n,type:r,props:a,children:o,line:j,column:V,length:i,return:""}}function Q(e,t){return M(Y("",null,null,"",null,null,0),e,{length:-e.length},t)}function Z(){return q=W>0?I(K,--W):0,V--,10===q&&(V=1,j--),q}function J(){return q=W<G?I(K,W++):0,V++,10===q&&(V=1,j++),q}function X(){return I(K,W)}function ee(){return W}function te(e,t){return U(K,e,t)}function ne(e){switch(e){case 0:case 9:case 10:case 13:case 32:return 5;case 33:case 43:case 44:case 47:case 62:case 64:case 126:case 59:case 123:case 125:return 4;case 58:return 3;case 34:case 39:case 40:case 91:return 2;case 41:case 93:return 1}return 0}function re(e){return j=V=1,G=z(K=e),W=0,[]}function ae(e){return K="",e}function oe(e){return R(te(W-1,se(91===e?e+2:40===e?e+1:e)))}function ie(e){for(;(q=X())&&q<33;)J();return ne(e)>2||ne(q)>3?"":" "}function le(e,t){for(;--t&&J()&&!(q<48||q>102||q>57&&q<65||q>70&&q<97););return te(e,ee()+(t<6&&32==X()&&32==J()))}function se(e){for(;J();)switch(q){case e:return W;case 34:case 39:34!==e&&39!==e&&se(q);break;case 40:41===e&&se(e);break;case 92:J()}return W}function ce(e,t){for(;J()&&e+q!==57&&(e+q!==84||47!==X()););return"/*"+te(t,W-1)+"*"+O(47===e?e:J())}function ue(e){for(;!ne(X());)J();return te(e,W)}var de="-ms-",me="-moz-",pe="-webkit-",fe="comm",ge="rule",he="decl",be="@keyframes";function ye(e,t){for(var n="",r=H(e),a=0;a<r;a++)n+=t(e[a],a,e,t)||"";return n}function ve(e,t,n,r){switch(e.type){case"@layer":if(e.children.length)break;case"@import":case he:return e.return=e.return||e.value;case fe:return"";case be:return e.return=e.value+"{"+ye(e.children,r)+"}";case ge:e.value=e.props.join(",")}return z(n=ye(e.children,r))?e.return=e.value+"{"+n+"}":""}function _e(e){return ae(ke("",null,null,null,[""],e=re(e),0,[0],e))}function ke(e,t,n,r,a,o,i,l,s){for(var c=0,u=0,d=i,m=0,p=0,f=0,g=1,h=1,b=1,y=0,v="",_=a,k=o,E=r,w=v;h;)switch(f=y,y=J()){case 40:if(108!=f&&58==I(w,d-1)){-1!=D(w+=B(oe(y),"&","&\f"),"&\f")&&(b=-1);break}case 34:case 39:case 91:w+=oe(y);break;case 9:case 10:case 13:case 32:w+=ie(f);break;case 92:w+=le(ee()-1,7);continue;case 47:switch(X()){case 42:case 47:$(we(ce(J(),ee()),t,n),s);break;default:w+="/"}break;case 123*g:l[c++]=z(w)*b;case 125*g:case 59:case 0:switch(y){case 0:case 125:h=0;case 59+u:-1==b&&(w=B(w,/\f/g,"")),p>0&&z(w)-d&&$(p>32?xe(w+";",r,n,d-1):xe(B(w," ","")+";",r,n,d-2),s);break;case 59:w+=";";default:if($(E=Ee(w,t,n,c,u,a,l,v,_=[],k=[],d),o),123===y)if(0===u)ke(w,t,E,E,_,o,d,l,k);else switch(99===m&&110===I(w,3)?100:m){case 100:case 108:case 109:case 115:ke(e,E,E,r&&$(Ee(e,E,E,0,0,a,l,v,a,_=[],d),k),a,k,d,l,r?_:k);break;default:ke(w,E,E,E,[""],k,0,l,k)}}c=u=p=0,g=b=1,v=w="",d=i;break;case 58:d=1+z(w),p=f;default:if(g<1)if(123==y)--g;else if(125==y&&0==g++&&125==Z())continue;switch(w+=O(y),y*g){case 38:b=u>0?1:(w+="\f",-1);break;case 44:l[c++]=(z(w)-1)*b,b=1;break;case 64:45===X()&&(w+=oe(J())),m=X(),u=d=z(v=w+=ue(ee())),y++;break;case 45:45===f&&2==z(w)&&(g=0)}}return o}function Ee(e,t,n,r,a,o,i,l,s,c,u){for(var d=a-1,m=0===a?o:[""],p=H(m),f=0,g=0,h=0;f<r;++f)for(var b=0,y=U(e,d+1,d=P(g=i[f])),v=e;b<p;++b)(v=R(g>0?m[b]+" "+y:B(y,/&\f/g,m[b])))&&(s[h++]=v);return Y(e,t,n,0===a?ge:l,s,c,u)}function we(e,t,n){return Y(e,t,n,fe,O(q),U(e,2,-2),0)}function xe(e,t,n,r){return Y(e,t,n,he,U(e,0,r),U(e,r+1,-1),r)}var Se=function(e,t,n){for(var r=0,a=0;r=a,a=X(),38===r&&12===a&&(t[n]=1),!ne(a);)J();return te(e,W)},Ce=new WeakMap,Te=function(e){if("rule"===e.type&&e.parent&&!(e.length<1)){for(var t=e.value,n=e.parent,r=e.column===n.column&&e.line===n.line;"rule"!==n.type;)if(!(n=n.parent))return;if((1!==e.props.length||58===t.charCodeAt(0)||Ce.get(n))&&!r){Ce.set(e,!0);for(var a=[],o=function(e,t){return ae(function(e,t){var n=-1,r=44;do{switch(ne(r)){case 0:38===r&&12===X()&&(t[n]=1),e[n]+=Se(W-1,t,n);break;case 2:e[n]+=oe(r);break;case 4:if(44===r){e[++n]=58===X()?"&\f":"",t[n]=e[n].length;break}default:e[n]+=O(r)}}while(r=J());return e}(re(e),t))}(t,a),i=n.props,l=0,s=0;l<o.length;l++)for(var c=0;c<i.length;c++,s++)e.props[s]=a[l]?o[l].replace(/&\f/g,i[c]):i[c]+" "+o[l]}}},Ne=function(e){if("decl"===e.type){var t=e.value;108===t.charCodeAt(0)&&98===t.charCodeAt(2)&&(e.return="",e.value="")}};function Fe(e,t){switch(function(e,t){return 45^I(e,0)?(((t<<2^I(e,0))<<2^I(e,1))<<2^I(e,2))<<2^I(e,3):0}(e,t)){case 5103:return pe+"print-"+e+e;case 5737:case 4201:case 3177:case 3433:case 1641:case 4457:case 2921:case 5572:case 6356:case 5844:case 3191:case 6645:case 3005:case 6391:case 5879:case 5623:case 6135:case 4599:case 4855:case 4215:case 6389:case 5109:case 5365:case 5621:case 3829:return pe+e+e;case 5349:case 4246:case 4810:case 6968:case 2756:return pe+e+me+e+de+e+e;case 6828:case 4268:return pe+e+de+e+e;case 6165:return pe+e+de+"flex-"+e+e;case 5187:return pe+e+B(e,/(\w+).+(:[^]+)/,pe+"box-$1$2"+de+"flex-$1$2")+e;case 5443:return pe+e+de+"flex-item-"+B(e,/flex-|-self/,"")+e;case 4675:return pe+e+de+"flex-line-pack"+B(e,/align-content|flex-|-self/,"")+e;case 5548:return pe+e+de+B(e,"shrink","negative")+e;case 5292:return pe+e+de+B(e,"basis","preferred-size")+e;case 6060:return pe+"box-"+B(e,"-grow","")+pe+e+de+B(e,"grow","positive")+e;case 4554:return pe+B(e,/([^-])(transform)/g,"$1"+pe+"$2")+e;case 6187:return B(B(B(e,/(zoom-|grab)/,pe+"$1"),/(image-set)/,pe+"$1"),e,"")+e;case 5495:case 3959:return B(e,/(image-set\([^]*)/,pe+"$1$`$1");case 4968:return B(B(e,/(.+:)(flex-)?(.*)/,pe+"box-pack:$3"+de+"flex-pack:$3"),/s.+-b[^;]+/,"justify")+pe+e+e;case 4095:case 3583:case 4068:case 2532:return B(e,/(.+)-inline(.+)/,pe+"$1$2")+e;case 8116:case 7059:case 5753:case 5535:case 5445:case 5701:case 4933:case 4677:case 5533:case 5789:case 5021:case 4765:if(z(e)-1-t>6)switch(I(e,t+1)){case 109:if(45!==I(e,t+4))break;case 102:return B(e,/(.+:)(.+)-([^]+)/,"$1"+pe+"$2-$3$1"+me+(108==I(e,t+3)?"$3":"$2-$3"))+e;case 115:return~D(e,"stretch")?Fe(B(e,"stretch","fill-available"),t)+e:e}break;case 4949:if(115!==I(e,t+1))break;case 6444:switch(I(e,z(e)-3-(~D(e,"!important")&&10))){case 107:return B(e,":",":"+pe)+e;case 101:return B(e,/(.+:)([^;!]+)(;|!.+)?/,"$1"+pe+(45===I(e,14)?"inline-":"")+"box$3$1"+pe+"$2$3$1"+de+"$2box$3")+e}break;case 5936:switch(I(e,t+11)){case 114:return pe+e+de+B(e,/[svh]\w+-[tblr]{2}/,"tb")+e;case 108:return pe+e+de+B(e,/[svh]\w+-[tblr]{2}/,"tb-rl")+e;case 45:return pe+e+de+B(e,/[svh]\w+-[tblr]{2}/,"lr")+e}return pe+e+de+e+e}return e}var Ae=[function(e,t,n,r){if(e.length>-1&&!e.return)switch(e.type){case he:e.return=Fe(e.value,e.length);break;case be:return ye([Q(e,{value:B(e.value,"@","@"+pe)})],r);case ge:if(e.length)return function(e,t){return e.map(t).join("")}(e.props,(function(t){switch(function(e,t){return(e=/(::plac\w+|:read-\w+)/.exec(e))?e[0]:e}(t)){case":read-only":case":read-write":return ye([Q(e,{props:[B(t,/:(read-\w+)/,":-moz-$1")]})],r);case"::placeholder":return ye([Q(e,{props:[B(t,/:(plac\w+)/,":"+pe+"input-$1")]}),Q(e,{props:[B(t,/:(plac\w+)/,":-moz-$1")]}),Q(e,{props:[B(t,/:(plac\w+)/,de+"input-$1")]})],r)}return""}))}}],Le=function(e){var t=e.key;if("css"===t){var n=document.querySelectorAll("style[data-emotion]:not([data-s])");Array.prototype.forEach.call(n,(function(e){-1!==e.getAttribute("data-emotion").indexOf(" ")&&(document.head.appendChild(e),e.setAttribute("data-s",""))}))}var r,a,o=e.stylisPlugins||Ae,i={},l=[];r=e.container||document.head,Array.prototype.forEach.call(document.querySelectorAll('style[data-emotion^="'+t+' "]'),(function(e){for(var t=e.getAttribute("data-emotion").split(" "),n=1;n<t.length;n++)i[t[n]]=!0;l.push(e)}));var s,c,u,d,m=[ve,(d=function(e){s.insert(e)},function(e){e.root||(e=e.return)&&d(e)})],p=(c=[Te,Ne].concat(o,m),u=H(c),function(e,t,n,r){for(var a="",o=0;o<u;o++)a+=c[o](e,t,n,r)||"";return a});a=function(e,t,n,r){s=n,ye(_e(e?e+"{"+t.styles+"}":t.styles),p),r&&(f.inserted[t.name]=!0)};var f={key:t,sheet:new L({key:t,container:r,nonce:e.nonce,speedy:e.speedy,prepend:e.prepend,insertionPoint:e.insertionPoint}),nonce:e.nonce,inserted:i,registered:{},insert:a};return f.sheet.hydrate(l),f},Pe={animationIterationCount:1,aspectRatio:1,borderImageOutset:1,borderImageSlice:1,borderImageWidth:1,boxFlex:1,boxFlexGroup:1,boxOrdinalGroup:1,columnCount:1,columns:1,flex:1,flexGrow:1,flexPositive:1,flexShrink:1,flexNegative:1,flexOrder:1,gridRow:1,gridRowEnd:1,gridRowSpan:1,gridRowStart:1,gridColumn:1,gridColumnEnd:1,gridColumnSpan:1,gridColumnStart:1,msGridRow:1,msGridRowSpan:1,msGridColumn:1,msGridColumnSpan:1,fontWeight:1,lineHeight:1,opacity:1,order:1,orphans:1,tabSize:1,widows:1,zIndex:1,zoom:1,WebkitLineClamp:1,fillOpacity:1,floodOpacity:1,stopOpacity:1,strokeDasharray:1,strokeDashoffset:1,strokeMiterlimit:1,strokeOpacity:1,strokeWidth:1},Oe=/[A-Z]|^ms/g,Me=/_EMO_([^_]+?)_([^]*?)_EMO_/g,Re=function(e){return 45===e.charCodeAt(1)},Be=function(e){return null!=e&&"boolean"!=typeof e},De=N((function(e){return Re(e)?e:e.replace(Oe,"-$&").toLowerCase()})),Ie=function(e,t){switch(e){case"animation":case"animationName":if("string"==typeof t)return t.replace(Me,(function(e,t,n){return ze={name:t,styles:n,next:ze},t}))}return 1===Pe[e]||Re(e)||"number"!=typeof t||0===t?t:t+"px"};function Ue(e,t,n){if(null==n)return"";if(void 0!==n.__emotion_styles)return n;switch(typeof n){case"boolean":return"";case"object":if(1===n.anim)return ze={name:n.name,styles:n.styles,next:ze},n.name;if(void 0!==n.styles){var r=n.next;if(void 0!==r)for(;void 0!==r;)ze={name:r.name,styles:r.styles,next:ze},r=r.next;return n.styles+";"}return function(e,t,n){var r="";if(Array.isArray(n))for(var a=0;a<n.length;a++)r+=Ue(e,t,n[a])+";";else for(var o in n){var i=n[o];if("object"!=typeof i)null!=t&&void 0!==t[i]?r+=o+"{"+t[i]+"}":Be(i)&&(r+=De(o)+":"+Ie(o,i)+";");else if(!Array.isArray(i)||"string"!=typeof i[0]||null!=t&&void 0!==t[i[0]]){var l=Ue(e,t,i);switch(o){case"animation":case"animationName":r+=De(o)+":"+l+";";break;default:r+=o+"{"+l+"}"}}else for(var s=0;s<i.length;s++)Be(i[s])&&(r+=De(o)+":"+Ie(o,i[s])+";")}return r}(e,t,n);case"function":if(void 0!==e){var a=ze,o=n(e);return ze=a,Ue(e,t,o)}}if(null==t)return n;var i=t[n];return void 0!==i?i:n}var ze,He=/label:\s*([^\s;\n{]+)\s*(;|$)/g,$e=!!g.useInsertionEffect&&g.useInsertionEffect,je=$e||function(e){return e()},Ve=($e||g.useLayoutEffect,g.createContext("undefined"!=typeof HTMLElement?Le({key:"css"}):null));Ve.Provider;var Ge=g.createContext({}),We=function(e,t,n){var r=e.key+"-"+t.name;!1===n&&void 0===e.registered[r]&&(e.registered[r]=t.styles)},qe=A,Ke=function(e){return"theme"!==e},Ye=function(e){return"string"==typeof e&&e.charCodeAt(0)>96?qe:Ke},Qe=function(e,t,n){var r;if(t){var a=t.shouldForwardProp;r=e.__emotion_forwardProp&&a?function(t){return e.__emotion_forwardProp(t)&&a(t)}:a}return"function"!=typeof r&&n&&(r=e.__emotion_forwardProp),r},Ze=function(e){var t=e.cache,n=e.serialized,r=e.isStringTag;return We(t,n,r),je((function(){return function(e,t,n){We(e,t,n);var r=e.key+"-"+t.name;if(void 0===e.inserted[t.name]){var a=t;do{e.insert(t===a?"."+r:"",a,e.sheet,!0),a=a.next}while(void 0!==a)}}(t,n,r)})),null},Je=function e(t,n){var r,a,o=t.__emotion_real===t,i=o&&t.__emotion_base||t;void 0!==n&&(r=n.label,a=n.target);var l=Qe(t,n,o),s=l||Ye(i),c=!s("as");return function(){var u=arguments,d=o&&void 0!==t.__emotion_styles?t.__emotion_styles.slice(0):[];if(void 0!==r&&d.push("label:"+r+";"),null==u[0]||void 0===u[0].raw)d.push.apply(d,u);else{d.push(u[0][0]);for(var m=u.length,p=1;p<m;p++)d.push(u[p],u[0][p])}var f,h=(f=function(e,t,n){var r,o,u,m,p=c&&e.as||i,f="",h=[],b=e;if(null==e.theme){for(var y in b={},e)b[y]=e[y];b.theme=g.useContext(Ge)}"string"==typeof e.className?(r=t.registered,o=h,u=e.className,m="",u.split(" ").forEach((function(e){void 0!==r[e]?o.push(r[e]+";"):m+=e+" "})),f=m):null!=e.className&&(f=e.className+" ");var v=function(e,t,n){if(1===e.length&&"object"==typeof e[0]&&null!==e[0]&&void 0!==e[0].styles)return e[0];var r=!0,a="";ze=void 0;var o=e[0];null==o||void 0===o.raw?(r=!1,a+=Ue(n,t,o)):a+=o[0];for(var i=1;i<e.length;i++)a+=Ue(n,t,e[i]),r&&(a+=o[i]);He.lastIndex=0;for(var l,s="";null!==(l=He.exec(a));)s+="-"+l[1];var c=function(e){for(var t,n=0,r=0,a=e.length;a>=4;++r,a-=4)t=1540483477*(65535&(t=255&e.charCodeAt(r)|(255&e.charCodeAt(++r))<<8|(255&e.charCodeAt(++r))<<16|(255&e.charCodeAt(++r))<<24))+(59797*(t>>>16)<<16),n=1540483477*(65535&(t^=t>>>24))+(59797*(t>>>16)<<16)^1540483477*(65535&n)+(59797*(n>>>16)<<16);switch(a){case 3:n^=(255&e.charCodeAt(r+2))<<16;case 2:n^=(255&e.charCodeAt(r+1))<<8;case 1:n=1540483477*(65535&(n^=255&e.charCodeAt(r)))+(59797*(n>>>16)<<16)}return(((n=1540483477*(65535&(n^=n>>>13))+(59797*(n>>>16)<<16))^n>>>15)>>>0).toString(36)}(a)+s;return{name:c,styles:a,next:ze}}(d.concat(h),t.registered,b);f+=t.key+"-"+v.name,void 0!==a&&(f+=" "+a);var _=c&&void 0===l?Ye(p):s,k={};for(var E in e)c&&"as"===E||_(E)&&(k[E]=e[E]);return k.className=f,k.ref=n,g.createElement(g.Fragment,null,g.createElement(Ze,{cache:t,serialized:v,isStringTag:"string"==typeof p}),g.createElement(p,k))},(0,g.forwardRef)((function(e,t){var n=(0,g.useContext)(Ve);return f(e,n,t)})));return h.displayName=void 0!==r?r:"Styled("+("string"==typeof i?i:i.displayName||i.name||"Component")+")",h.defaultProps=t.defaultProps,h.__emotion_real=h,h.__emotion_base=i,h.__emotion_styles=d,h.__emotion_forwardProp=l,Object.defineProperty(h,"toString",{value:function(){return"."+a}}),h.withComponent=function(t,r){return e(t,T({},n,r,{shouldForwardProp:Qe(h,r,!0)})).apply(void 0,d)},h}}.bind();["a","abbr","address","area","article","aside","audio","b","base","bdi","bdo","big","blockquote","body","br","button","canvas","caption","cite","code","col","colgroup","data","datalist","dd","del","details","dfn","dialog","div","dl","dt","em","embed","fieldset","figcaption","figure","footer","form","h1","h2","h3","h4","h5","h6","head","header","hgroup","hr","html","i","iframe","img","input","ins","kbd","keygen","label","legend","li","link","main","map","mark","marquee","menu","menuitem","meta","meter","nav","noscript","object","ol","optgroup","option","output","p","param","picture","pre","progress","q","rp","rt","ruby","s","samp","script","section","select","small","source","span","strong","style","sub","summary","sup","table","tbody","td","textarea","tfoot","th","thead","time","title","tr","track","u","ul","var","video","wbr","circle","clipPath","defs","ellipse","foreignObject","g","image","line","linearGradient","mask","path","pattern","polygon","polyline","radialGradient","rect","stop","svg","text","tspan"].forEach((function(e){Je[e]=Je(e)}));var Xe=n(2485),et=n.n(Xe);Je(i.BaseControl)`
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
`,Je.div`
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
`,Je.div`
  > *:not(:last-of-type) {
    margin-bottom: 12px !important;
  }
`,Je(i.PanelBody)`
  margin-right: -16px;
  margin-left: -16px;
`;const tt=Je.div`
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
`,nt=Je(i.Flex)`
  padding-bottom: 8px;

  .label-control {
    margin-bottom: 0 !important;
  }
`,rt=Je(i.Flex)`
  flex-wrap: wrap;
  width: auto;
  gap: 4px;

  > * {
    flex: 1 0 auto;
    margin: 0 !important;
  }
`;var at=(0,g.createElement)(h.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24"},(0,g.createElement)(h.Path,{d:"M10 17.389H8.444A5.194 5.194 0 1 1 8.444 7H10v1.5H8.444a3.694 3.694 0 0 0 0 7.389H10v1.5ZM14 7h1.556a5.194 5.194 0 0 1 0 10.39H14v-1.5h1.556a3.694 3.694 0 0 0 0-7.39H14V7Zm-4.5 6h5v-1.5h-5V13Z"})),ot=(0,g.createElement)(h.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24"},(0,g.createElement)(h.Path,{d:"M17.031 4.703 15.576 4l-1.56 3H14v.03l-2.324 4.47H9.5V13h1.396l-1.502 2.889h-.95a3.694 3.694 0 0 1 0-7.389H10V7H8.444a5.194 5.194 0 1 0 0 10.389h.17L7.5 19.53l1.416.719L15.049 8.5h.507a3.694 3.694 0 0 1 0 7.39H14v1.5h1.556a5.194 5.194 0 0 0 .273-10.383l1.202-2.304Z"}));function it(t){let{isLinked:n,...r}=t;const a=n?(0,o.__)("Unlink Sides","content-blocks-builder"):(0,o.__)("Link Sides","content-blocks-builder");return(0,e.createElement)(i.Tooltip,{text:a},(0,e.createElement)("span",null,(0,e.createElement)(i.Button,T({},r,{className:"component-group-control__linked-button",variant:n?"primary":"secondary",size:"small",icon:n?at:ot,iconSize:16,"aria-label":a}))))}const lt=e=>{let{values:n,fields:r}=e;const a=r.map((e=>{var t;let{name:r}=e;return null!==(t=n[r])&&void 0!==t?t:void 0}));return(o=a.filter((e=>e))).sort(((e,n)=>(0,t.isObject)(e)?o.filter((t=>u()(t,e))).length-o.filter((e=>u()(e,n))).length:o.filter((t=>t===e)).length-o.filter((e=>e===n)).length)).pop();var o},st=t=>{let{values:n,fields:r,renderControl:a,onChange:o,normalizeValue:i}=t;return r.map((t=>{var l;const{name:s}=t;return(0,e.createElement)(e.Fragment,{key:`group-control-${s}`},a({value:null!==(l=n[s])&&void 0!==l?l:void 0,onChange:(c=s,e=>{e=i({side:c,value:e}),o({...n,[c]:e})}),fields:r,values:n,...t}));var c}))},ct=e=>{let{values:t,fields:n,renderControl:r,renderAllControl:a=null,onChange:o,normalizeValue:i,...l}=e;return a||(a=r),a({value:lt({values:t,fields:n}),fields:n,values:t,onChange:e=>{e=i({side:"all",value:e});let r={...t};n.forEach((t=>{let{name:n}=t;r={...r,[n]:e}})),o(r)},...l})},ut=n=>{let{label:r,fields:a=[],values:o={},renderLabel:i=t.noop,renderControl:l=t.noop,onChange:s=t.noop,normalizeValue:c=(e=>{let{side:t,value:n}=e;return n}),isLinkedGroup:u=!0,getInitialLinkedState:d=t.noop,className:m,columns:p,...f}=n;const g={fields:a,values:o,renderControl:l,onChange:s,normalizeValue:c,...f},[h,b]=u?function(t){const[n,r]=(0,e.useState)(t);return(0,e.useEffect)((()=>r(t)),[t]),[n,r]}(d(o)):[!1,t.noop];return(0,e.createElement)(tt,T({className:et()("group-control",m,{[`is-${p}-columns`]:p})},f),(0,e.createElement)(nt,{className:"group-control__header"},i({label:r,isLinkedGroup:u,...f}),u&&(0,e.createElement)(it,{onClick:()=>{b(!h)},isLinked:h})),(0,e.createElement)(rt,{className:"group-control__body"},h&&(0,e.createElement)(ct,g),!h&&(0,e.createElement)(st,g)))};Je(ut)`
  .group-control__body {
    > *:nth-of-type(3) {
      order: 2;
    }

    .components-input-control__input {
      height: 40px;
    }
  }
`,Je.div`
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
`,Je(ut)`
  /* .block-editor-panel-color-gradient-settings__item {
    padding: 8px !important;
  } */

  .components-toggle-control {
    > * {
      margin-bottom: 0;
    }
  }
`,Je.div`
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
`,Je(ut)`
  .components-base-control__field {
    margin-bottom: 0;
  }
`;var dt=window.wp.notices;Je.div`
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
`;const mt=Je.div`
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
`,pt=t=>{let{title:n,description:r,children:a}=t;return(0,e.createElement)(mt,{className:"settings-section"},n&&(0,e.createElement)("h3",{className:"settings-section__title"},n),r&&(0,e.createElement)("p",{className:"settings-section__description"},r),(0,e.createElement)("div",{className:"meta-box-sortables"},a))},ft=Je.div`
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
`,gt=Je.div`
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
`,ht=n=>{let{title:r,settingsName:a="boldblocks-settings",children:o,renderFooter:i=null,isFullRow:l=!1,isHeaderHidden:s=!1,className:c,initialOpen:u=!0}=n;const d=`${a}-${(0,f.cleanForSlug)(r)}`,[m,p]=function(n){let r=arguments.length>1&&void 0!==arguments[1]?arguments[1]:null;const[a,o]=(0,e.useState)((()=>{try{const e=JSON.parse(localStorage.getItem(n));return(0,t.isNil)(e)?r:e}catch(e){return x(e,"error"),r}}));return[a,e=>{o(e),localStorage.setItem(n,JSON.stringify(e))}]}(d,!u);return(0,e.createElement)(ft,{className:et()("postbox",c,{closed:m,"is-full-row":l,"is-header-hidden":s})},!s&&(0,e.createElement)("div",{className:"postbox-header","aria-expanded":m?"false":"true",tabIndex:-1,onClick:e=>{e.preventDefault(),p(!m)}},(0,e.createElement)("h2",{className:"hndle"},r),(0,e.createElement)("div",{className:"handle-actions hide-if-no-js"},(0,e.createElement)("button",{type:"button",className:"handlediv","aria-expanded":m?"false":"true",onClick:e=>{e.preventDefault(),p(!m)}},(0,e.createElement)("span",{className:"screen-reader-text"},"Toggle panel: ",r),(0,e.createElement)("span",{className:"toggle-indicator","aria-hidden":m?"true":"false"})))),(0,e.createElement)("div",{className:"inside"},o),(0,t.isFunction)(i)&&(0,e.createElement)("div",{className:"postbox-footer"},i()))};window.wp.blocks,Je.div`
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
`,n(9799),window.wp.keycodes,Je(i.Modal)`
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
`,Je.ul`
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
`;const bt=Je(ht)`
  border-top: 0;

  h1 {
    padding: 0;
    margin: 10px 0;
    font-size: 2.5em;
  }

  .welcome {
    &__description {
      ul,
      p {
        font-size: 1.2em;
      }

      ul {
        padding-left: 20px;
        list-style: disc;
      }
    }
  }

  .video-tutorials {
    display: grid;
    grid-template-columns: minmax(0, 1fr);
    gap: 12px;

    @media (min-width: 782px) {
      // $break-medium
      grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    @media (min-width: 1280px) {
      // $break-wide
      grid-template-columns: repeat(3, minmax(0, 1fr));
    }

    &__item {
      border: 1px solid #ddd;

      &__video {
        position: relative;
        width: 100%;
        height: 0;
        padding-bottom: 56.25%;
        margin: 0;

        iframe {
          position: absolute;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
        }
      }

      &__desc {
        padding: 8px 10px 10px;
        font-size: 1.2em;
        font-weight: 500;
      }
    }
  }

  h2.view-playlists {
    margin-top: 1rem;
  }
`,yt=()=>{const{Docs:{loading:t,docs:n}={}}=(0,e.useContext)(S);return(0,e.createElement)(bt,{isHeaderHidden:!0,isFullRow:!0,className:"welcome-widget welcome"},(0,e.createElement)("h1",null,(0,o.__)("Welcome to Content Blocks Builder","content-blocks-builder")),(0,e.createElement)("div",{className:"welcome__description"},(0,e.createElement)("p",null,(0,o.__)("Content Blocks Builder (CBB) power-up the default Gutenberg Block Editor to help the process of creating sites with the new WordPress much easier and more enjoyable.","content-blocks-builder")),(0,e.createElement)("hr",null),(0,e.createElement)("p",null,(0,o.__)("Thank you for choosing Content Blocks Builder (CBB) for your website. If this is your first time with CBB, we recommend learning about it first by visiting the promotion site or watching the video tutorials below.","content-blocks-builder")),(0,e.createElement)("h2",null,(0,o.__)("Here is a list of starting points for you to get started creating with it.","content-blocks-builder")),(0,e.createElement)("ul",null,(0,e.createElement)("li",null,(0,o.__)("Manage custom blocks: ","content-blocks-builder"),(0,e.createElement)(i.ExternalLink,{href:(0,f.addQueryArgs)("edit.php?post_type=boldblocks_block")},(0,o.__)("All custom blocks","content-blocks-builder")),", ",(0,e.createElement)(i.ExternalLink,{href:(0,f.addQueryArgs)("post-new.php?post_type=boldblocks_block")},(0,o.__)("Create new block","content-blocks-builder")),", ",(0,e.createElement)(i.ExternalLink,{href:(0,f.addQueryArgs)("edit.php?post_type=boldblocks_block&page=cbb-block-library")},(0,o.__)("Block Library","content-blocks-builder")),", ",(0,e.createElement)(i.ExternalLink,{href:"https://youtu.be/mICLfKkF6tU"},(0,o.__)("Learn to create a grid block","content-blocks-builder")),", ",(0,e.createElement)(i.ExternalLink,{href:"https://youtu.be/WgHuo6jwyN8"},(0,o.__)("Create blocks from an external script","content-blocks-builder")),", ",(0,e.createElement)(i.ExternalLink,{href:"https://www.youtube.com/playlist?list=PLPuEwc7dZklcFBm-hwtNGJmuB-J8nV-fD"},(0,o.__)("View all playlist","content-blocks-builder"))),(0,e.createElement)("li",null,(0,o.__)("Manage custom variations: ","content-blocks-builder"),(0,e.createElement)(i.ExternalLink,{href:(0,f.addQueryArgs)("edit.php?post_type=boldblocks_variation")},(0,o.__)("All custom variations","content-blocks-builder")),", ",(0,e.createElement)(i.ExternalLink,{href:(0,f.addQueryArgs)("edit.php?post_type=boldblocks_block&page=cbb-variation-library")},(0,o.__)("Variation Library","content-blocks-builder")),", ",(0,e.createElement)(i.ExternalLink,{href:"https://youtu.be/caiY-YZT7ZY"},(0,o.__)("Learn to create a variation","content-blocks-builder")),", ",(0,e.createElement)(i.ExternalLink,{href:"https://youtu.be/YQHrf4xFctg"},(0,o.__)("Create a variation for the Query Loop block","content-blocks-builder")),", ",(0,e.createElement)(i.ExternalLink,{href:"https://youtu.be/BAY8_evbyL0"},(0,o.__)("Learn to use variation library","content-blocks-builder"))),(0,e.createElement)("li",null,(0,o.__)("Manage custom patterns: ","content-blocks-builder"),(0,e.createElement)(i.ExternalLink,{href:(0,f.addQueryArgs)("edit.php?post_type=boldblocks_pattern")},(0,o.__)("All patterns","content-blocks-builder")),", ",(0,e.createElement)(i.ExternalLink,{href:(0,f.addQueryArgs)("post-new.php?post_type=boldblocks_pattern")},(0,o.__)("Create new pattern","content-blocks-builder")),", ",(0,e.createElement)(i.ExternalLink,{href:"https://youtu.be/gfSNwcAb-xc"},(0,o.__)("Learn to create a pattern","content-blocks-builder")),", ",(0,e.createElement)(i.ExternalLink,{href:"https://youtu.be/BVvImhhZma4"},(0,o.__)("Learn to create a realworld pattern","content-blocks-builder")),", ",(0,e.createElement)(i.ExternalLink,{href:"https://youtu.be/UTrZSBvkzj0"},(0,o.__)("Learn to use the pattern inserter popup","content-blocks-builder"))),(0,e.createElement)("li",null,(0,o.__)("Carousel layouts: ","content-blocks-builder"),(0,e.createElement)(i.ExternalLink,{href:"https://youtu.be/Eh3kX-9_mDg"},(0,o.__)("Learn to create a banner slider","content-blocks-builder")),", ",(0,e.createElement)(i.ExternalLink,{href:"https://youtu.be/bcK_k3IfW8g"},(0,o.__)("Learn to create a banner slider using Query Loop block","content-blocks-builder")),", ",(0,e.createElement)(i.ExternalLink,{href:"https://www.youtube.com/playlist?list=PLPuEwc7dZkleS_5ATLat8arnVUflXSfTk"},(0,o.__)("View all playlist","content-blocks-builder"))),(0,e.createElement)("li",null,(0,o.__)("Grid layouts: ","content-blocks-builder"),(0,e.createElement)(i.ExternalLink,{href:"https://youtu.be/awSC09tTnS8"},(0,o.__)("Learn to create a responsive grid","content-blocks-builder")),", ",(0,e.createElement)(i.ExternalLink,{href:"https://www.youtube.com/playlist?list=PLPuEwc7dZklfsbrRAKe_iUywkjk0fPMUE"},(0,o.__)("View all playlist","content-blocks-builder"))),(0,e.createElement)("li",null,(0,o.__)("Accordion layout: ","content-blocks-builder"),(0,e.createElement)(i.ExternalLink,{href:"https://youtu.be/YA4-duNF_w4"},(0,o.__)("Learn to create an accordion layout","content-blocks-builder"))),(0,e.createElement)("li",null,(0,o.__)("Customize Query Loop: ","content-blocks-builder"),(0,e.createElement)(i.ExternalLink,{href:"https://youtu.be/aHy3spQVBGc"},(0,o.__)("Learn to create a blog page with CBB","content-blocks-builder")),", ",(0,e.createElement)(i.ExternalLink,{href:"https://youtu.be/YQHrf4xFctg"},(0,o.__)("Learn to create blog layouts with overlay style","content-blocks-builder")),", ",(0,e.createElement)(i.ExternalLink,{href:"https://www.youtube.com/playlist?list=PLPuEwc7dZklchm8nVUOKqSOc6OgmRQyha"},(0,o.__)("View all playlist","content-blocks-builder"))),(0,e.createElement)("li",null,(0,o.__)("Background effects: ","content-blocks-builder"),(0,e.createElement)(i.ExternalLink,{href:"https://youtu.be/nDpeQbpu50s"},(0,o.__)("Parallax effect","content-blocks-builder")),", ",(0,e.createElement)(i.ExternalLink,{href:"https://youtu.be/mBleA20caGo"},(0,o.__)("Infinite scrolling effect","content-blocks-builder")),", ",(0,e.createElement)(i.ExternalLink,{href:"https://youtu.be/0g1SLTq-lQ4"},(0,o.__)("A realworld use case","content-blocks-builder"))),(0,e.createElement)("li",null,(0,o.__)("Modal, off-canvas, toggle layouts: ","content-blocks-builder"),(0,e.createElement)(i.ExternalLink,{href:"https://youtu.be/y31TAKHZOD0"},(0,o.__)("Step by step guide","content-blocks-builder")),", ",(0,e.createElement)(i.ExternalLink,{href:"https://youtu.be/52jD9eeBJ78"},(0,o.__)("A video popup","content-blocks-builder")),", ",(0,e.createElement)(i.ExternalLink,{href:"https://youtu.be/YnUt-zQXnCU"},(0,o.__)("Off-canvas content","content-blocks-builder")),", ",(0,e.createElement)(i.ExternalLink,{href:"https://youtu.be/UEh_Da9Sozs"},(0,o.__)("A cookies popup","content-blocks-builder")),", ",(0,e.createElement)(i.ExternalLink,{href:"https://youtu.be/g_KOCqvU0Ps"},(0,o.__)("A notification bar","content-blocks-builder")),", ",(0,e.createElement)(i.ExternalLink,{href:"https://youtu.be/E4usfCydR7U"},(0,o.__)("A responsive header with hamburger menu, search bar toggle","content-blocks-builder"))),(0,e.createElement)("li",null,(0,o.__)("Load Google Fonts for your site: ","content-blocks-builder"),(0,e.createElement)(i.ExternalLink,{href:(0,f.addQueryArgs)("edit.php?post_type=boldblocks_block&page=cbb-settings&tab=typography")},(0,o.__)("Setup Google Fonts","content-blocks-builder")),", ",(0,e.createElement)(i.ExternalLink,{href:"https://youtu.be/rhd4SEKUcHU"},(0,o.__)("Learn to load google fonts","content-blocks-builder"))),(0,e.createElement)("li",null,(0,o.__)("Import/export data from/to other sites: ","content-blocks-builder"),(0,e.createElement)(i.ExternalLink,{href:(0,f.addQueryArgs)("edit.php?post_type=boldblocks_block&page=cbb-settings&tab=tools")},(0,o.__)("Import/export Tools","content-blocks-builder"))),(0,e.createElement)("li",null,(0,o.__)("Learn more about CBB: ","content-blocks-builder"),(0,e.createElement)(i.ExternalLink,{href:"https://contentblocksbuilder.com/?utm_source=Learn+more+CBB&utm_campaign=CBB+visit+site&utm_medium=link&utm_content=setting+description"},"contentblocksbuilder.com"))),(0,e.createElement)("h2",null,(0,o.__)("How to - video tutorials","content-blocks-builder")),(0,e.createElement)("p",null,(0,o.__)("Below is a list of short 'how to' video tutorials, you can use them as learning resources besides the documentation. More videos will be made soon, so, please subscribe to our youtube channel and get notifications when we release new videos. ","content-blocks-builder"),(0,e.createElement)(i.ExternalLink,{href:"https://www.youtube.com/channel/UCB7Y3mlCEKHVM-RCZaTkR1g?sub_confirmation=1"},"Subscribe"),", ",(0,e.createElement)(i.ExternalLink,{href:"https://www.youtube.com/channel/UCB7Y3mlCEKHVM-RCZaTkR1g"},(0,o.__)("View all videos","content-blocks-builder"))),(0,e.createElement)("div",{className:"video-tutorials"},t?(0,e.createElement)(i.Spinner,null):n?.videoTutorials.map((t=>{let{title:n,id:r}=t;return(0,e.createElement)("div",{className:"video-tutorials__item",key:r},(0,e.createElement)("div",{className:"video-tutorials__item__video"},(0,e.createElement)("iframe",{src:`https://www.youtube.com/embed/${r}`,srcDoc:`<style>*{padding:0;margin:0;overflow:hidden}html,body{height:100%}img{position:absolute;width:100%;top:0;bottom:0;margin:auto}.btn-play{position: absolute;top: 50%;left: 50%;z-index: 1;display: block;width: 68px;height: 48px;margin:0;cursor: pointer;transform: translate3d(-50%, -50%, 0);background-color: transparent;background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 68 48"><path d="M66.52 7.74c-.78-2.93-2.49-5.41-5.42-6.19C55.79.13 34 0 34 0S12.21.13 6.9 1.55c-2.93.78-4.63 3.26-5.42 6.19C.06 13.05 0 24 0 24s.06 10.95 1.48 16.26c.78 2.93 2.49 5.41 5.42 6.19C12.21 47.87 34 48 34 48s21.79-.13 27.1-1.55c2.93-.78 4.64-3.26 5.42-6.19C67.94 34.95 68 24 68 24s-.06-10.95-1.48-16.26z" fill="red"/><path d="M45 24 27 14v20" fill="white"/></svg>');filter: grayscale(100%);transition: filter .1s cubic-bezier(0, 0, 0.2, 1);border: none;}body:hover .btn-play,.btn-play:focus{filter:none}.visually-hidden{clip: rect(0 0 0 0);clip-path: inset(50%);height: 1px;overflow: hidden;position: absolute;white-space: nowrap;width: 1px;}</style><a href="https://www.youtube.com/embed/${r}?autoplay=1&enablejsapi=1&playsinline=1"><img src="https://img.youtube.com/vi/${r}/hqdefault.jpg" alt="${n}"><button type="button" class="btn-play"><span class="visually-hidden">Play</span></button></a>`,title:"YouTube video player",allow:"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture",allowFullScreen:!0})),(0,e.createElement)("div",{className:"video-tutorials__item__desc"},n))}))),n&&n.playlists&&(0,e.createElement)(e.Fragment,null,(0,e.createElement)("h2",{className:"view-playlists"},(0,o.__)("View by playlists","content-blocks-builder")),(0,e.createElement)("ul",null,n.playlists.map((t=>{let{title:n,url:r}=t;return(0,e.createElement)("li",{key:r},(0,e.createElement)(i.ExternalLink,{href:r},n))}))))))};var vt=()=>(0,e.createElement)(pt,null,(0,e.createElement)(yt,null)),_t=(window.wp.editor,window.wp.dataControls);const kt={previewModes:{}},Et={getPreviewMode(e,t){var n;return null!==(n=e.previewModes[t])&&void 0!==n?n:""}},wt={setPreviewMode(e){return{type:"SET_PREVIEW_MODE",payload:e}}},xt={patternInserter:{status:!1,modalState:{},patterns:[],patternKeywords:[],missingBlocks:{},missingBlocksStatuses:{},plugins:[]}},St={getPatternInserterModalStatus(e){return e.patternInserter.status},getPatterns(e){return e.patternInserter.patterns},getPatternsModalState(e){return e.patternInserter.modalState},getPatternKeywords(e){return e.patternInserter.patternKeywords},getMissingBlock(e,t){var n;return null!==(n=e.patternInserter.missingBlocks[t])&&void 0!==n&&n},getMissingBlockStatus(e,t){var n;return null!==(n=e.patternInserter.missingBlocksStatuses[t])&&void 0!==n&&n},getPlugins(e){return e.patternInserter.plugins}},Ct={getPatterns(){return async e=>{let{dispatch:t}=e;const n=await m()({path:"boldblocks/v1/getPatterns"});return n&&n.length&&t({type:"SET_PATTERNS",payload:n}),n}},getPatternKeywords(){return async e=>{let{dispatch:t}=e;const n=await m()({path:"boldblocks/v1/getPatternKeywords"});return n&&n.length&&t({type:"SET_PATTERN_KEYWORDS",payload:n}),n}},getPlugins(){return async e=>{let{dispatch:t}=e,n=await m()({path:"wp/v2/plugins"});n=n.map((e=>{const{plugin:t}=e,n=t.split("/")[0];return{...e,slug:n}})),t({type:"SET_PLUGINS",payload:n})}}},Tt={setPatternInserterModalStatus(e){return{type:"SET_PATTERN_INSERTER_MODAL_STATUS",payload:e}},loadFullPatterns(e){return async t=>{let{dispatch:n}=t;if(!e.length)return;const r=await m()({path:(0,f.addQueryArgs)("boldblocks/v1/getFullPatternData",{patternIds:e.join(",")})});return r&&r.length&&n({type:"UPDATE_PATTERNS",payload:r}),r}},setPatternsModalState(e){return{type:"UPDATE_PATTERN_INSERTER_MODAL_STATE",payload:e}},setMissingBlockStatus(e){return{type:"SET_MISSING_BLOCK_STATUS",payload:e}},loadMissingBlock(e){return async t=>{let{select:n,dispatch:r}=t,a=n.getMissingBlock(e);var o;!1===a&&(a=null!==(o=(await m()({path:`wp/v2/block-directory/search?term=${e}`}))[0])&&void 0!==o?o:{},r({type:"SET_MISSING_BLOCK",payload:{[e]:a}}));return a}}},Nt={typography:{fonts:{body:{fontFamily:"Nunito",genericFamily:"sans-serif",fontVariants:[]},headings:{fontFamily:"Roboto",genericFamily:"sans-serif",fontVariants:[]},additionalFonts:[]},fontsPresets:[{body:{fontFamily:"Nunito",genericFamily:"sans-serif"},headings:{fontFamily:"Roboto",genericFamily:"sans-serif"}},{body:{fontFamily:"Montserrat",genericFamily:"sans-serif"},headings:{fontFamily:"Oswald",genericFamily:"sans-serif"}},{body:{fontFamily:"Merriweather",genericFamily:"serif"},headings:{fontFamily:"Oswald",genericFamily:"sans-serif"}},{body:{fontFamily:"Montserrat",genericFamily:"sans-serif"},headings:{fontFamily:"Source Sans Pro",genericFamily:"sans-serif"}},{body:{fontFamily:"Source Sans Pro",genericFamily:"sans-serif"},headings:{fontFamily:"Libre Baskerville",genericFamily:"serif"}},{body:{fontFamily:"Fauna One",genericFamily:"serif"},headings:{fontFamily:"Playfair Display",genericFamily:"serif"}},{body:{fontFamily:"Josefin Slab",genericFamily:"serif"},headings:{fontFamily:"Six Caps",genericFamily:"sans-serif"}},{body:{fontFamily:"Source Sans Pro",genericFamily:"sans-serif"},headings:{fontFamily:"Playfair Display",genericFamily:"serif"}},{body:{fontFamily:"Quattrocento",genericFamily:"serif"},headings:{fontFamily:"Oswald",genericFamily:"sans-serif"}},{body:{fontFamily:"Alice",genericFamily:"serif"},headings:{fontFamily:"Sacramento",genericFamily:"cursive"}},{body:{fontFamily:"Lato",genericFamily:"sans-serif"},headings:{fontFamily:"Arvo",genericFamily:"serif"}},{body:{fontFamily:"Poppins",genericFamily:"sans-serif"},headings:{fontFamily:"Abril Fatface",genericFamily:"cursive"}},{body:{fontFamily:"Inconsolata",genericFamily:"monospace"},headings:{fontFamily:"Karla",genericFamily:"sans-serif"}},{body:{fontFamily:"Andika",genericFamily:"sans-serif"},headings:{fontFamily:"Amatic SC",genericFamily:"sans-serif"}},{body:{fontFamily:"Lato",genericFamily:"sans-serif"},headings:{fontFamily:"Lustria",genericFamily:"serif"}},{body:{fontFamily:"Proza Libre",genericFamily:"sans-serif"},headings:{fontFamily:"Cormorant Garamond",genericFamily:"serif"}},{body:{fontFamily:"EB Garamond",genericFamily:"serif"},headings:{fontFamily:"Oswald",genericFamily:"sans-serif"}},{body:{fontFamily:"Josefin Sans",genericFamily:"sans-serif"},headings:{fontFamily:"Yeseva One",genericFamily:"cursive"}},{body:{fontFamily:"Inter",genericFamily:"sans-serif"},headings:{fontFamily:"EB Garamond",genericFamily:"serif"}}],googleFonts:[]},postTypography:{fonts:null}},Ft={getGoogleFonts(e){return e.typography.googleFonts},getTypography(e){return{fonts:e.typography.fonts,fontsPresets:e.typography.fontsPresets}},getPostTypography(e,t){return{fonts:e.postTypography.fonts,fontsPresets:e.typography.fontsPresets}}},At={getGoogleFonts(){return async e=>{let{dispatch:t}=e;const n=await m()({path:"boldblocks/v1/getGoogleFonts"});return n&&n.success&&t({type:"SET_GOOGLE_FONTS",payload:n.data}),n}},getTypography(){return async e=>{let{dispatch:t}=e;const{BoldBlocksTypography:n}=await m()({path:"wp/v2/settings"});if(n)return Lt(n,t);{const{BoldBlocksTypography:e}=await m()({path:"wp/v2/settings",method:"POST",data:{BoldBlocksTypography:{fonts:JSON.stringify(Nt.typography.fonts)}}});return Lt(e,t)}}},getPostTypography(e){return async t=>{let{dispatch:n}=t;if(!e)return;const{meta:{BoldBlocksTypography:r}={}}=await m()({path:e});return Pt(r,n)}}},Lt=(e,t)=>{if(e&&e?.fonts){const n=JSON.parse(e.fonts);return t({type:"UPDATE_FONTS",payload:n}),n}return e},Pt=(e,t)=>{let n;return e&&e?.fonts&&(n=JSON.parse(e.fonts)),t({type:"UPDATE_POST_FONTS",payload:n}),n},Ot={updateFonts(e){return{type:"UPDATE_FONTS",payload:e}},updatePostFonts(e){return{type:"UPDATE_POST_FONTS",payload:e}},updateAndPersistFonts(e){return async t=>{let{dispatch:n}=t;const{BoldBlocksTypography:r}=await m()({path:"wp/v2/settings",method:"POST",data:{BoldBlocksTypography:{fonts:e}}});return Lt(r,n)}},updateAndPersistPostFonts(e,t){return async n=>{let{dispatch:r}=n;const{meta:{BoldBlocksTypography:a}={}}=await m()({path:t,method:"POST",data:{meta:{BoldBlocksTypography:{fonts:e}}}});return Pt(a,r)}}};(e=>{const t=(0,s.createReduxStore)("boldblocks/cbb-icon-library",{selectors:{getIconLibrary(e){var t;return null!==(t=e?.icons)&&void 0!==t?t:[]}},actions:{loadIconLibrary(e){return async t=>{var n;let{select:r,dispatch:a}=t;if(!e)return;let o=r.getIconLibrary();if(o&&o.length)return o;const i=await m()({path:e});var l;return i?.success&&a({type:"UPDATE_ICONS",payload:null!==(l=i?.data)&&void 0!==l?l:[]}),null!==(n=i?.data)&&void 0!==n?n:[]}}},reducer:function(){let e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:{icons:[]},t=arguments.length>1?arguments[1]:void 0;return"UPDATE_ICONS"===t.type?{...e,icons:t.payload}:e}});(0,s.register)(t)})();const Mt=(0,s.createReduxStore)("boldblocks/data",{selectors:{...Ft,...St,...Et},actions:{...Ot,...Tt,...wt},controls:_t.controls,reducer:(0,s.combineReducers)({previewModes:function(){let e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:kt.previewModes,t=arguments.length>1?arguments[1]:void 0;return"SET_PREVIEW_MODE"===t.type?{...e,[t.payload.clientId]:t.payload.previewMode}:e},typography:function(){let e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:Nt.typography,t=arguments.length>1?arguments[1]:void 0;switch(t.type){case"SET_GOOGLE_FONTS":return{...e,googleFonts:t.payload};case"UPDATE_FONTS":return{...e,fonts:t.payload}}return e},postTypography:function(){let e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:Nt.postTypography,t=arguments.length>1?arguments[1]:void 0;return"UPDATE_POST_FONTS"===t.type?{...e,fonts:t.payload}:e},patternInserter:function(){let e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:xt.patternInserter,t=arguments.length>1?arguments[1]:void 0;switch(t.type){case"SET_PATTERN_INSERTER_MODAL_STATUS":return{...e,status:t.payload};case"SET_PATTERNS":return{...e,patterns:[...t.payload]};case"UPDATE_PATTERNS":const n=t.payload.map((e=>{let{id:t}=e;return t})),r=e.patterns.map((e=>{if(n.includes(e.id)){const n=t.payload.find((t=>{let{id:n}=t;return n===e.id}));if(n)return n}return e}));return{...e,patterns:r};case"UPDATE_PATTERN_INSERTER_MODAL_STATE":return{...e,modalState:{...t.payload}};case"SET_PATTERN_KEYWORDS":return{...e,patternKeywords:[...t.payload]};case"SET_MISSING_BLOCK":return{...e,missingBlocks:{...e.missingBlocks,...t.payload}};case"SET_MISSING_BLOCK_STATUS":return{...e,missingBlocksStatuses:{...e.missingBlocksStatuses,[t.payload]:!0}};case"SET_PLUGINS":return{...e,plugins:[...t.payload]}}return e}}),resolvers:{...At,...Ct}});(0,s.register)(Mt);const Rt=function(e){return(arguments.length>1&&void 0!==arguments[1]?arguments[1]:[]).find((t=>t.label===e))},Bt=function(e){let t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"",n=arguments.length>2&&void 0!==arguments[2]?arguments[2]:[],r=arguments.length>3?arguments[3]:void 0,a=`boldblocks-font-${e.replace(/\s/g,"-").toLowerCase()}`;t&&(a=`${a}-text`);let o=r.querySelector(`#${a}`);if(!o){const i=Rt(e,n);if(i){let{label:e,variants:n}=i,l=`https://fonts.googleapis.com/css2?family=${e.replace(/\s/g,"+")}`,s=[];n=n.map((e=>("regular"===e?e="400":"italic"===e&&(e="400italic"),e))),n.sort().forEach((e=>{-1!==e.indexOf("italic")?s.push(`1,${e.replace("italic","")}`):s.push(`0,${e}`)})),l=`${l}:ital,wght@${s.sort().join(";")}&display=swap`,t&&(l=`${l}&text=${encodeURIComponent(t)}`),o=r.createElement("link"),o.id=a,o.rel="stylesheet",o.href=l,r.head.appendChild(o)}return i}},Dt=(e,t,n)=>{Bt(e?.headings?.fontFamily,"",t,n),Bt(e?.body?.fontFamily,"",t,n)},It=(e,t)=>(e&&t&&t?.headings&&(e=e.map((e=>e.headings.fontFamily===t?.headings?.fontFamily&&e.body.fontFamily===t?.body?.fontFamily?{...e,isActive:!0}:e?.isActive?{...e,isActive:!1}:e))),e),Ut=e=>e.map((e=>("regular"===e?e="400":"italic"===e&&(e="400italic"),e+""))).sort(),zt=(t,n,r,a)=>(0,e.useMemo)((()=>{const e=r(t,n);return e?a(e.variants):[]}),[t,n,r,a]),Ht=(e,t)=>{let n={};if(t){const{fontFamily:r,genericFamily:a}=t;r&&(n[`--cbb--${e}--font-family`]=`"${r}", ${a}`)}return n},$t=(e,t)=>{let n=t.head.querySelector("#boldblocks-css-variables");n?n.innerHTML=e:(n=t.createElement("style"),n.id="boldblocks-css-variables",n.innerHTML=e,t.head.appendChild(n))},jt=Je.div`
  .fonts__actions {
    display: flex;
    gap: 8px;
    margin-top: 12px;
  }

  &.is-fullview {
    margin-top: 12px;

    .fonts__headings-body {
      display: grid;
      gap: 12px;

      .font__actions {
        margin-bottom: 0;
      }

      @media (min-width: 960px) {
        grid-template-columns: repeat(2, minmax(0, 1fr));

        .font {
          display: flex;
          flex-direction: column;
        }

        .font__item {
          flex-grow: 1;
          display: flex;
          flex-direction: column;
        }

        .font__preview {
          flex-grow: 1;
          display: flex;
          flex-direction: column;
        }

        .font__preview__text {
          flex-grow: 1;
        }

        .font__family__value {
          height: 36px;
        }

        .font__variants__value {
          height: 40px;
        }

        .font__actions {
          margin-top: auto;
        }
      }
    }
  }
`,Vt=Je.div`
  /*
  $break-huge: 1440px;
  $break-wide: 1280px;
  $break-xlarge: 1080px;
  $break-large: 960px;	// admin sidebar auto folds
  $break-medium: 782px;	// adminbar goes big
  $break-small: 600px;
  $break-mobile: 480px;
  $break-zoomed-in: 280px;
  */
  .components-combobox-control__suggestions-container,
  .components-form-token-field__input-container {
    width: auto;
  }

  label:empty {
    display: none;
  }

  h3 {
    margin-top: 16px;
    margin-bottom: 0.25em;
    font-size: 1.25em;
    text-transform: none;
  }

  .font {
    &__item {
      > * {
        margin-top: 0;
        margin-bottom: 8px;

        > * {
          margin-bottom: 4px;
        }
      }
    }

    &__label {
      padding-bottom: 4px;
      margin-top: 10;
      margin-bottom: 10px;
      border-bottom: 1px solid #ddd;
    }

    &__item__value {
      padding: 8px;
      border: 1px solid #ddd;
    }

    &__preview {
      &__text {
        font-size: 16px;
        line-height: 1.5;
      }
    }

    // Variants
    &__variants__edit {
      p {
        margin: 0;
      }
    }

    // Actions
    &__actions {
      display: flex;
      gap: 8px;
      margin: 10px 0;
    }
  }

  &.is-fullview {
    padding: 10px;
    border: 1px solid #ddd;

    .font__label {
      margin-top: 0;
    }
  }
`,Gt=t=>{let{label:n,editLabel:r=(0,o.__)("Edit font","content-blocks-builder"),value:a,allFontFamilies:l,text:s,isInSidebar:c=!1,style:u={},isEditable:d,onChange:m}=t;const{fontFamily:p,fontVariants:f=[],allFontVariants:g=[]}=a,[h,b]=(0,e.useState)(!1);return(0,e.createElement)(Vt,{className:et()("font",{"is-edit":h,"is-view":!h,"is-fullview":!c})},(0,e.createElement)("h3",{className:"font__label"},(0,e.createElement)("strong",null,n)),(0,e.createElement)("div",{className:"font__item"},(0,e.createElement)("div",{className:"font__family"},(0,e.createElement)("div",{className:"font__item__label font__family__label"},(0,o.__)("Family:","content-blocks-builder")),h?(0,e.createElement)("div",{className:"font__family__edit"},(0,e.createElement)(i.ComboboxControl,{value:p,options:l,onChange:e=>{m({...a,fontFamily:e})}})):(0,e.createElement)("div",{className:"font__item__value font__family__value",style:{...u,fontFamily:p}},p)),(0,e.createElement)("div",{className:"font__variants"},(0,e.createElement)("div",{className:"font__item__label font__variants__label"},(0,o.__)("Variants:","content-blocks-builder")),h?(0,e.createElement)("div",{className:"font__variants__edit"},(0,e.createElement)(i.FormTokenField,{label:"",value:f,suggestions:g,onChange:e=>{m({...a,fontVariants:e})},placeholder:(0,o.__)("Choose variants to load","content-blocks-builder"),__experimentalExpandOnFocus:!0,__experimentalShowHowTo:!1}),(0,e.createElement)("p",null,(0,o.__)("Leave it blank to load all available variants: ","content-blocks-builder"),!!g.length&&g.map(((t,n)=>(0,e.createElement)("span",{className:"font__variant",key:t},t,n<g.length-1?", ":""))))):(0,e.createElement)("div",{className:"font__item__value font__variants__value"},f.length?f.map(((t,n)=>(0,e.createElement)("span",{className:"font__variant",key:t},t,n<f.length-1?", ":""))):!!g.length&&g.map(((t,n)=>(0,e.createElement)("span",{className:"font__variant",key:t},t,n<g.length-1?", ":""))))),(0,e.createElement)("div",{className:"font__preview"},(0,e.createElement)("div",{className:"font__item__label font__preview__label"},(0,o.__)("Font preview:","content-blocks-builder")),(0,e.createElement)("div",{className:"font__item__value font__preview__text",style:{...u,fontFamily:p}},s))),d&&(0,e.createElement)("div",{className:"font__actions"},!h&&(0,e.createElement)(i.Button,{variant:"primary",isSmall:!0,onClick:()=>{b(!0)}},r),h&&(0,e.createElement)(e.Fragment,null,(0,e.createElement)(i.Button,{variant:"primary",isSmall:!0,onClick:()=>{b(!1)}},(0,o.__)("Back to preview","content-blocks-builder")))))},Wt=t=>{let{value:n,allFontFamilies:r,onChange:a,onReset:l,isInSidebar:s=!1,isEditable:c,isFontsChanged:u}=t;const{headings:d,body:m}=n;return(0,e.createElement)(jt,{className:et()("fonts",{"is-fullview":!s})},(0,e.createElement)("div",{className:"fonts__headings-body"},(0,e.createElement)(Gt,{label:(0,o.__)("Headings font","content-blocks-builder"),editLabel:(0,o.__)("Edit headings font","content-blocks-builder"),value:d,onChange:e=>{a({...n,headings:e})},allFontFamilies:r,style:{fontWeight:"bold",fontSize:"1.25rem"},text:"The spectacle before us was indeed sublime.",isInSidebar:s,isEditable:c}),(0,e.createElement)(Gt,{label:(0,o.__)("Body font","content-blocks-builder"),editLabel:(0,o.__)("Edit body font","content-blocks-builder"),value:m,onChange:e=>{a({...n,body:e})},allFontFamilies:r,style:{fontSize:"1rem"},text:"By the same illusion which lifts the horizon of the sea to the level of the spectator on a hillside, the sable cloud beneath was dished out, and the car seemed to float in the middle of an immense dark sphere, whose upper half was strewn with silver.",isInSidebar:s,isEditable:c})),(0,e.createElement)("div",{className:"fonts__others"}),c&&(0,e.createElement)("div",{className:"fonts__actions"},(0,e.createElement)(i.Button,{variant:"primary",onClick:()=>{const e={...n};a({...e,headings:e.body,body:e.headings})}},(0,o.__)("Swap fonts","content-blocks-builder")),u&&(0,e.createElement)(i.Button,{variant:"secondary",onClick:l},(0,o.__)("Reset fonts","content-blocks-builder"))))},qt=Je.div`
  /*
  $break-huge: 1440px;
  $break-wide: 1280px;
  $break-xlarge: 1080px;
  $break-large: 960px;	// admin sidebar auto folds
  $break-medium: 782px;	// adminbar goes big
  $break-small: 600px;
  $break-mobile: 480px;
  $break-zoomed-in: 280px;
  */

  margin-top: 12px;

  .font-pair {
    position: relative;
    height: 100%;
    padding: 0.5rem;
    font-size: 1.25rem;
    line-height: 1.5;
    cursor: pointer;
    border: 1px solid #ddd;
    border-radius: 3px;
    box-sizing: border-box;

    &:hover {
      border: 1px solid #000;
    }

    &.is-active {
      border: 1px solid #000;
      box-shadow: 0 0 5px #000;
    }

    .button-remove {
      position: absolute;
      top: 0;
      right: 0;
      color: #ddd;
    }

    &:hover {
      .button-remove {
        color: #000;
      }
    }
  }

  h3 {
    margin-top: 0;
    margin-bottom: 0.25em;
    font-size: 1.25em;
    text-transform: none;
  }

  // Fonts presets
  .fonts-presets__list {
    margin: 0 -0.25rem;
    height: 260px;
    overflow-y: auto;

    > * {
      padding: 0.25rem;
      box-sizing: border-box;
    }
  }

  // Grid style
  &.is-grid {
    .fonts-presets__list {
      display: flex;
      flex-wrap: wrap;
      margin: 0 -0.25rem;

      > * {
        flex: 0 0 100%;
        padding: 0.25rem;
        box-sizing: border-box;

        @media (min-width: 600px) {
          flex: 0 0 50%;
        }

        @media (min-width: 960px) {
          flex: 0 0 percentage(1 / 3);
        }
        @media (min-width: 1280px) {
          flex: 0 0 20%;
        }
      }
    }
  }
`,Kt=n=>{let{presets:r=[],onChange:a=t.noop,isGrid:i=!1}=n;return(0,e.createElement)(qt,{className:et()("fonts-presets",{"is-grid":i})},(0,e.createElement)("h3",{className:"fonts-presets__label"},(0,e.createElement)("strong",null,(0,o.__)("Choose a predefined combination:","content-blocks-builder"))),(0,e.createElement)("div",{className:"fonts-presets__list"},r.map(((t,n)=>(0,e.createElement)("div",{className:"fonts-preset",key:n,onClick:()=>{a(t)}},(0,e.createElement)("div",{className:et()("font-pair",{"is-active":t?.isActive})},(0,e.createElement)("div",{className:"font-pair__body",style:{fontFamily:t?.body?.fontFamily}},t?.body?.fontFamily),(0,e.createElement)("div",{className:"font-pair__headings",style:{fontFamily:t?.headings?.fontFamily,fontWeight:"bold"}},t?.headings?.fontFamily)))))))};var Yt=window.wp.compose;const Qt=Je.div`
  .components-notice {
    padding-right: 0;
    margin-right: 0;
    margin-left: 0;
  }
`,Zt=t=>{const{googleFonts:n=[],fonts:r,editingFonts:a,setEditingFonts:o,fontsPresets:l,isFontsChanged:s,messageData:c,setMessageData:u,isInSidebar:d=!1,isEditable:m=!0,isLoadingData:p,deviceType:f="Desktop"}=t,g=(0,e.useMemo)((()=>n.map((e=>{let{label:t}=e;return{label:t,value:t}}))),[n]),{headings:{fontFamily:h},body:{fontFamily:b}}=a,y=(0,Yt.usePrevious)(f),v="Desktop"!==f&&"Desktop"===y;((t,n)=>{(0,e.useEffect)((()=>{t.length&&n.length&&((e,t,n)=>{e.forEach((e=>{Bt(e?.body?.fontFamily,e?.body?.fontFamily,t,n),Bt(e?.headings?.fontFamily,e?.headings?.fontFamily,t,n)}))})(t,n,document)}),[t.length,n.length,Bt])})(l,n),function(t,n,r){let a=arguments.length>3&&void 0!==arguments[3]&&arguments[3];(0,e.useEffect)((()=>{if(t?.headings?.fontFamily&&t?.body?.fontFamily&&n.length){const e=document.querySelector('iframe[name="editor-canvas"]');if(e){const r=e.contentWindow.document;a?function(e,t,n){return new Promise((r=>{if(t.querySelector(e))return r(t);const a=new MutationObserver((()=>{(t=n.querySelector('iframe[name="editor-canvas"]').contentWindow.document).querySelector(e)&&(r(t),a.disconnect())}));a.observe(t,{subtree:!0,childList:!0})}))}("#boldblocks-custom-fonts-css",r,document).then((e=>{Dt(t,n,e)})).catch((e=>x(e,"error"))):Dt(t,n,r)}else Dt(t,n,document)}}),[t?.headings.fontFamily,t?.body?.fontFamily,n.length,Bt,r,a])}(a,n,f,v),function(t,n,r){let a=arguments.length>3&&void 0!==arguments[3]&&arguments[3];(0,e.useEffect)((()=>{const e=document.querySelector('iframe[name="editor-canvas"]'),n=`.editor-styles-wrapper {${(e=>{const{body:t,headings:n}=e;let r={...Ht("body",t),...Ht("headings",n)};return Object.keys(r).reduce(((e,t)=>`${e}${t}: ${r[t]};`),"")})(t)}}`;if(e){const t=e.contentWindow.document;a?e.addEventListener("load",(()=>{$t(n,t)})):$t(n,t)}else $t(n,document)}),[t,n,r,a])}(a,p,f,v);const _=zt(h,n,Rt,Ut),k=zt(b,n,Rt,Ut),E={...a,headings:{...a.headings,allFontVariants:_},body:{...a.body,allFontVariants:k}};return p?(0,e.createElement)(i.Spinner,null):(0,e.createElement)(Qt,null,(0,e.createElement)(Wt,{value:E,allFontFamilies:g,isFontsChanged:s,onChange:e=>{o(e)},onReset:()=>{o(r)},isEditable:m,isInSidebar:d}),m&&(0,e.createElement)(Kt,{presets:It(l,a),onChange:e=>{o(e)},isGrid:!d}),c&&c?.message&&(0,e.createElement)(i.Notice,{status:c?.type,isDismissible:!0,onDismiss:()=>{u({type:"success",message:""})}},c.message))},Jt=Je(i.ToggleControl)`
  margin-top: 12px;
`,Xt=()=>{const n=function(){let n=arguments.length>0&&void 0!==arguments[0]?arguments[0]:"";const{updateFonts:r,updateAndPersistFonts:a,updatePostFonts:o,updateAndPersistPostFonts:i}=(0,s.useDispatch)(Mt),l=(0,s.useSelect)((e=>{const t=e(Mt).getGoogleFonts(),{fonts:r,fontsPresets:a}=e(Mt).getTypography(),o=e(Mt).hasFinishedResolution("getTypography");let i,l;n&&(i=e(Mt).getPostTypography(n),l=e(Mt).hasFinishedResolution("getPostTypography",[n]));let s={fonts:r,globalFonts:r,fontsPresets:a,googleFonts:t,isGlobalTypographyLoaded:o,isPostTypogrpahyLoaded:l};return i&&i?.fonts&&(s={...s,fonts:i.fonts,isPostFonts:!0}),s}),[n]),{fonts:c,isGlobalTypographyLoaded:u,isPostTypogrpahyLoaded:d}=l,m=n?u&&d:u,[p,f]=(0,e.useState)(c);return(0,e.useEffect)((()=>{m&&f(c)}),[m]),{...l,editingFonts:p,setEditingFonts:f,isDataLoaded:m,isFontsChanged:(0,e.useMemo)((()=>{var e,n,r,a;return!(0,t.isEqual)({headingsFontFamily:c?.headings?.fontFamily,headingsFontVariants:null!==(e=c?.headings?.fontVariants)&&void 0!==e?e:[],bodyFontFamily:c?.body?.fontFamily,bodyFontVariants:null!==(n=c?.body?.fontVariants)&&void 0!==n?n:[]},{headingsFontFamily:p?.headings?.fontFamily,headingsFontVariants:null!==(r=p?.headings?.fontVariants)&&void 0!==r?r:[],bodyFontFamily:p?.body?.fontFamily,bodyFontVariants:null!==(a=p?.body?.fontVariants)&&void 0!==a?a:[]})}),[c,p]),updateFonts:r,updateAndPersistFonts:a,updatePostFonts:o,updateAndPersistPostFonts:i}}(),{isDataLoaded:r,isFontsChanged:a,editingFonts:l,setEditingFonts:c,updateAndPersistFonts:u}=n,[d,m]=(0,e.useState)(!1),[p,f]=(0,e.useState)({type:"success",message:""});return(0,e.createElement)(ht,{title:(0,o.__)("Google fonts settings","content-blocks-builder"),renderFooter:()=>(0,e.createElement)(e.Fragment,null,(0,e.createElement)(i.Button,{variant:"primary",disabled:!a,onClick:e=>{e.preventDefault(),m(!0),u(JSON.stringify(l)).then((()=>{f({type:"success",message:(0,o.__)("Setttings saved!","content-blocks-builder")})})).catch((e=>{console.error(e),f({type:"error",message:(0,o.__)((0,o.__)("Something went wrong, please contact the author for support!","content-blocks-builder"))})})).finally((()=>{m(!1)}))}},(0,o.__)("Update typography","content-blocks-builder")),d&&(0,e.createElement)(i.Spinner,null)),isFullRow:!0},(0,e.createElement)(Zt,T({},n,{isLoadingData:!r,editingFonts:l,setEditingFonts:c,isFontsChanged:a,messageData:p,setMessageData:f})))};var en=()=>{const{createSuccessNotice:n,createErrorNotice:r}=(0,s.useDispatch)(dt.store),{saveEditedEntityRecord:a}=(0,s.useDispatch)(l.store),[c,u]=(0,l.useEntityProp)("root","site","EnableTypography"),[d,m]=(0,l.useEntityProp)("root","site","UseBunnyFonts"),[p,f]=(0,e.useState)(!1);return(0,e.createElement)(pt,{description:(0,o.__)("Typography settings","content-blocks-builder")},(0,e.createElement)(ht,{isHeaderHidden:!0,isFullRow:!0},(0,e.createElement)(i.ToggleControl,{checked:null!=c&&c,disabled:(0,t.isUndefined)(c),label:(0,e.createElement)(e.Fragment,null,(0,e.createElement)("span",null,(0,o.__)("Enable google fonts ","content-blocks-builder")),(0,t.isUndefined)(c)||p&&(0,e.createElement)(i.Spinner,{style:{margin:"0 10px 0 0"}})),onChange:e=>{u(e),f(!0),a("root","site").then((()=>{n((0,o.__)("Setttings saved!","content-blocks-builder"),{type:"snackbar"})})).catch((e=>{console.error(e),r((0,o.__)("Something went wrong, please contact the author for support!","content-blocks-builder"),{type:"snackbar"})})).finally((()=>{f(!1)}))}}),(0,e.createElement)("p",{style:{margin:0}},(0,e.createElement)("strong",null,(0,o.__)("Enable this setting will override font families from the theme.","content-blocks-builder"))," ",(0,e.createElement)("strong",null,(0,o.__)("It also generates two CSS classes named: 'headings-font-family', 'body-font-family' and two CSS variables named '--cbb--headings--font-family', '--cbb--body--font-family'. You can use those to set the font family for your blocks.","content-blocks-builder"))),c&&(0,e.createElement)(e.Fragment,null,(0,e.createElement)(Jt,{checked:null!=d&&d,disabled:(0,t.isUndefined)(d),label:(0,e.createElement)(e.Fragment,null,(0,e.createElement)("span",null,(0,o.__)("Load Bunny Fonts instead of Google Fonts for GDPR compliance","content-blocks-builder")),(0,t.isUndefined)(d)||p&&(0,e.createElement)(i.Spinner,{style:{margin:"0 10px 0 0"}})),onChange:e=>{m(e),f(!0),a("root","site").then((()=>{n((0,o.__)("Setttings saved!","content-blocks-builder"),{type:"snackbar"})})).catch((e=>{console.error(e),r((0,o.__)("Something went wrong, please contact the author for support!","content-blocks-builder"),{type:"snackbar"})})).finally((()=>{f(!1)}))},className:"use-bunny-fonts"}),(0,e.createElement)("p",{style:{margin:0}},(0,e.createElement)("span",null,(0,o.__)("Learn more: ","content-blocks-builder")),(0,e.createElement)("strong",null,(0,e.createElement)(i.ExternalLink,{href:"https://fonts.bunny.net/"},"Bunny Fonts"),","," ",(0,e.createElement)(i.ExternalLink,{href:"https://fonts.google.com/"},"Google Fonts"))))),c&&(0,e.createElement)(Xt,null))};const tn=(e,t,n)=>{if(!t)return n;const r=t.find((t=>e===t?.prefix));return r&&r?.breakpoint?r.breakpoint:n};var nn=()=>{const{Messages:t}=(0,e.useContext)(S),{saveEditedEntityRecord:n}=(0,s.useDispatch)(l.store),[r,a]=(0,e.useState)({type:"success",message:""}),[c,u]=(0,l.useEntityProp)("root","site","CBBBreakpoints"),d=(e,t)=>n=>{const r=t.map((t=>t.prefix===e?{...t,breakpoint:n}:t));u(r)},m=(0,e.useMemo)((()=>tn("md",c,768)),[c]),p=(0,e.useMemo)((()=>tn("lg",c,1024)),[c]);return(0,e.createElement)(ht,{title:(0,o.__)("Manage reponsive breakpoints","content-blocks-builder"),renderFooter:()=>{const[r,o]=(0,e.useState)(!1);return(0,e.createElement)(e.Fragment,null,(0,e.createElement)(i.Button,{variant:"primary",onClick:e=>{e.preventDefault(),o(!0),n("root","site").then((()=>{a({type:"success",message:t.Success})})).catch((e=>{x(e,"error"),a({type:"error",message:t.Error})})).finally((()=>{o(!1)}))}},t.UpdateSettings),r&&(0,e.createElement)(i.Spinner,null))}},(0,e.createElement)(gt,{className:"fieldset"},(0,e.createElement)("div",{className:"fieldset__label"},(0,e.createElement)("strong",null,(0,o.__)("Change the breakpoint values for phone, tablet and desktop. All values are in pixels (px).","content-blocks-builder"))),c?(0,e.createElement)(e.Fragment,null,(0,e.createElement)(i.RangeControl,{label:(0,o.__)("Tablet","content-blocks-builder"),value:m,onChange:d("md",c),min:600,max:1200}),(0,e.createElement)(i.RangeControl,{label:(0,o.__)("Desktop","content-blocks-builder"),value:p,onChange:d("lg",c),min:960,max:1920})):(0,e.createElement)(i.Spinner,null)),r&&r?.message&&(0,e.createElement)(i.Notice,{status:r?.type,isDismissible:!1},r.message))};const rn=n=>{let{value:r,onChange:a,onDelete:l,validateData:s,isEdit:c=!1}=n;const[u,d]=(0,e.useState)(r),{name:m,label:p}=u,[f,g]=(0,e.useState)(""),[h,b]=(0,e.useState)(c||(0,t.isEmpty)(m)||(0,t.isEmpty)(p));return(0,e.createElement)(e.Fragment,null,h?(0,e.createElement)(e.Fragment,null,(0,e.createElement)(i.TextControl,{placeholder:(0,o.__)("Name","content-blocks-builder"),value:m,onChange:e=>{d({...u,name:e})},className:"category__name"}),(0,e.createElement)(i.TextControl,{placeholder:(0,o.__)("Label","content-blocks-builder"),value:p,onChange:e=>{d({...u,label:e})},className:"category__label"}),(0,e.createElement)("div",{className:"fieldset__item__actions"},(0,e.createElement)(i.Button,{isSmall:!0,variant:"secondary",onClick:()=>{const e=s(u);if("success"===e?.type){const{name:e,label:t}=u;a({name:e.trim(),label:t.trim()}),b(!1)}else g(e?.message)}},(0,o.__)("Save","content-blocks-builder")),r?.name&&r?.label&&(0,e.createElement)(i.Button,{isSmall:!0,variant:"secondary",onClick:()=>{d(r),b(!1)}},(0,o.__)("Cancel","content-blocks-builder")),(0,e.createElement)(i.Button,{isSmall:!0,variant:"secondary",isDestructive:!0,onClick:()=>{l()}},(0,o.__)("Delete","content-blocks-builder"))),f&&(0,e.createElement)(i.Notice,{className:"message",status:"error",isDismissible:!1},f)):(0,e.createElement)(e.Fragment,null,(0,e.createElement)("code",null,m),(0,e.createElement)("span",null," - "),(0,e.createElement)("span",null,p),(0,e.createElement)("div",{className:"fieldset__item__actions"},(0,e.createElement)(i.Button,{isSmall:!0,variant:"secondary",onClick:()=>{b(!0)}},(0,o.__)("Edit","content-blocks-builder")),(0,e.createElement)(i.Button,{isSmall:!0,variant:"secondary",isDestructive:!0,onClick:()=>{l()}},(0,o.__)("Delete","content-blocks-builder")))))};(0,b.addFilter)("boldblocks.settings.patternCategories","boldblocks/premium",((n,r)=>{let{Fieldset:a,CategoryList:l,customCategories:s,setCustomCategories:c,registeredCategories:u}=r;const d=e=>{let{name:t,label:n}=null!=e?e:{};return t=t.trim(),n=n.trim(),t&&n?u.find((e=>{let{name:r,label:a}=e;return r===t||a===n}))?{type:"error",message:(0,o.__)("Name and label should not be in the list of already registered categories.","content-blocks-builder")}:{type:"success"}:{type:"error",message:(0,o.__)("Both name and label are required!","content-blocks-builder")}};return(0,e.createElement)(e.Fragment,null,(0,e.createElement)(a,{className:"fieldset"},(0,e.createElement)("div",{className:"fieldset__label"},(0,e.createElement)("strong",null,(0,o.__)("Manage custom categories","content-blocks-builder")),(0,e.createElement)("p",null,(0,o.__)("Click the 'Update Settings' button to save data to the database.","content-blocks-builder"))),(0,e.createElement)(l,{className:"category__list"},(0,t.isUndefined)(s)&&(0,e.createElement)(i.Spinner,null),s&&s.length>0&&s.map(((t,n)=>(0,e.createElement)("li",{key:t?.name},(0,e.createElement)(rn,{value:t,validateData:d,onChange:e=>{const t=[...s];t[n]=e,c(t)},onDelete:()=>{const e=[...s];e.splice(n,1),c(e)}}))))),s&&(0,e.createElement)(i.Button,{variant:"primary",isSmall:!0,onClick:()=>{c([...s,{name:"",label:""}])}},(0,o.__)("Add category","content-blocks-builder"))))}));const an=Je.ul`
  li {
    display: flex;
    align-items: center;
    align-self: start;
    flex-wrap: wrap;
    gap: 0.2em;
    padding: 6px 0;
    margin: 0;
    border-bottom: 1px solid #ddd;
  }

  .fieldset__item__actions {
    margin-left: auto;

    > * + * {
      margin-left: 8px;
    }
  }

  .components-base-control + .components-base-control {
    margin-left: 8px;
  }

  .components-base-control__field {
    margin-bottom: 0;
  }
`;var on=()=>{const{Messages:t}=(0,e.useContext)(S),{saveEditedEntityRecord:n}=(0,s.useDispatch)(l.store),[r,a]=(0,e.useState)(!0),[c,u]=(0,e.useState)([]),[d,p]=(0,l.useEntityProp)("root","site","boldblocks_pattern_categories"),[f,g]=(0,e.useState)({type:"success",message:""}),[h,y]=(0,l.useEntityProp)("root","site","boldblocks_pattern_categories_all_label");return(0,e.useEffect)((()=>{m()({path:"boldblocks/v1/getPatternCategories"}).then((e=>{u(e),a(!1)}))}),[]),(0,e.createElement)(ht,{title:(0,o.__)("Manage pattern categories","content-blocks-builder"),renderFooter:()=>{const[r,a]=(0,e.useState)(!1);return(0,e.createElement)(e.Fragment,null,(0,e.createElement)(i.Button,{variant:"primary",onClick:e=>{e.preventDefault(),a(!0),n("root","site").then((()=>{g({type:"success",message:t.Success})})).catch((e=>{console.error(e),g({type:"error",message:t.Error})})).finally((()=>{a(!1)}))}},t.UpdateSettings),r&&(0,e.createElement)(i.Spinner,null))}},(0,e.createElement)("p",null,(0,o.__)("You can create custom pattern categories for this site such as 'Carousel', 'Hero'... Don't register new categories with the same name and label as those already registered.","content-blocks-builder")),(0,e.createElement)("p",null,(0,o.__)("Following pattern categories are already registered:","content-blocks-builder")),(0,e.createElement)(gt,{className:"fieldset"},r&&(0,e.createElement)(i.Spinner,null),c.length>0&&(0,e.createElement)("ul",{className:"fieldset__list"},c.map((t=>{let{name:n,label:r}=t;return(0,e.createElement)("li",{key:n},(0,e.createElement)("code",null,n),(0,e.createElement)("span",null," - "),(0,e.createElement)("span",null,r))})))),(0,b.applyFilters)("boldblocks.settings.patternCategories",null,{Fieldset:gt,CategoryList:an,customCategories:d,setCustomCategories:p,registeredCategories:c}),(0,e.createElement)(gt,{className:"fieldset"},(0,e.createElement)("div",{className:"fieldset__label"},(0,e.createElement)("strong",null,(0,o.__)("Change the label for the 'all custom patterns' category.","content-blocks-builder"))),(0,e.createElement)(i.TextControl,{value:null!=h?h:"",onChange:y})),f&&f?.message&&(0,e.createElement)(i.Notice,{status:f?.type,isDismissible:!1},f.message))},ln=()=>(0,e.createElement)(pt,{description:(0,o.__)("General settings","content-blocks-builder")},(0,e.createElement)(nn,null),(0,e.createElement)(on,null)),sn=(0,g.createElement)(h.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24"},(0,g.createElement)(h.Path,{d:"M18 11.3l-1-1.1-4 4V3h-1.5v11.3L7 10.2l-1 1.1 6.2 5.8 5.8-5.8zm.5 3.7v3.5h-13V15H4v5h16v-5h-1.5z"}));const cn=["boldblocks/group","boldblocks/grid-item","boldblocks/grid-item-repeater","boldblocks/carousel-item","boldblocks/carousel-item-repeater","boldblocks/stack-item","boldblocks/stack-item-repeater","boldblocks/accordion-item","boldblocks/accordion-item-repeater"];(0,o.__)("Responsive width","content-blocks-builder"),(0,o.__)("Responsive height","content-blocks-builder"),(0,o.__)("Responsive spacing","content-blocks-builder"),(0,o.__)("Responsive border","content-blocks-builder"),(0,o.__)("Media background","content-blocks-builder"),(0,o.__)("Background overlay","content-blocks-builder"),(0,o.__)("Text alignment","content-blocks-builder"),(0,o.__)("Vertical alignment","content-blocks-builder"),(0,o.__)("Justify alignment","content-blocks-builder"),(0,o.__)("Aspect ratio","content-blocks-builder"),(0,o.__)("Box shadow","content-blocks-builder"),(0,o.__)("Transform","content-blocks-builder"),(0,o.__)("Visibility","content-blocks-builder"),(0,o.__)("Toggle content","content-blocks-builder"),(0,o.__)("Sticky content","content-blocks-builder"),(0,o.__)("Custom attributes","content-blocks-builder"),(0,o.__)("Animation (premium)","content-blocks-builder"),(0,o.__)("Custom CSS (premium)","content-blocks-builder"),(0,o.__)("Steps style (premium)","content-blocks-builder"),(0,b.applyFilters)("boldblocks.CBB.isPremium",!1);const un=e=>{const{records:n,isResolving:r,hasResolved:a}=(0,l.useEntityRecords)("postType",e,{per_page:-1});return[(0,t.isArray)(n)?n.map((e=>{const{id:t,title:{raw:n,rendered:r},content:{raw:a},slug:o,type:i,meta:l,...s}=e;return{id:t,title:n,content:a,slug:o,type:i,meta:l,renderedTitle:r,...s}})):[],r,a]},dn=(0,e.createContext)(),mn=t=>{let{postType:n,title:r,value:a=[],setValue:l}=t;const s=(0,e.useContext)(dn),c=n.replace("boldblocks_",""),u=`${c}s`,d=`${c.charAt(0).toUpperCase()+c.slice(1)}s`,{[u]:m,[`isLoading${d}`]:p,[`hasFinishedResolution${d}`]:f}=s,g=(t,n)=>{let r=null;switch(n){case"boldblocks_block":case"boldblocks_pattern":r=(0,e.createElement)(e.Fragment,null,`boldblocks/${t?.slug}`);break;case"boldblocks_variation":r=(0,e.createElement)(e.Fragment,null,t?.meta?.boldblocks_variation_name)}return r};let h=m;if("block"===c&&h?.length){const e=cn.map((e=>e.replace("boldblocks/","")));h=h.filter((t=>{let{slug:n}=t;return!e.includes(n)}))}return(0,e.createElement)(gt,{className:"fieldset"},(0,e.createElement)("div",{className:"fieldset__label"},(0,e.createElement)("strong",null,r)),p&&(0,e.createElement)(i.Spinner,null),h&&h.length>0?(0,e.createElement)("fieldset",null,(0,e.createElement)(i.CheckboxControl,{label:(0,o.__)("Toggle All","content-blocks-builder"),checked:a.length===h.length,onChange:e=>{l(e?[...h]:[])}}),(0,e.createElement)("ul",{className:"fieldset__list"},h.map((t=>{return(0,e.createElement)("li",{key:t?.slug},(0,e.createElement)(i.CheckboxControl,{onChange:e=>{let n=[];if(e){const e=h.find((e=>{let{slug:n}=e;return n===t?.slug}));n=[...a,e]}else n=a.filter((e=>{let{slug:n}=e;return n!==t?.slug}));l([...n])},checked:(r=t?.slug,a.find((e=>{let{slug:t}=e;return t===r}))),label:g(t,n)}));var r})))):(0,e.createElement)(e.Fragment,null,f&&(0,e.createElement)("div",null,(0,o.__)("There is no data to export.","content-blocks-builder"))))};var pn=()=>{const{isLoading:t}=(0,e.useContext)(dn),[n,r]=(0,e.useState)([]),[a,l]=(0,e.useState)([]),[s,c]=(0,e.useState)([]);return(0,e.createElement)(ht,{title:(0,o.__)("Export data","content-blocks-builder"),renderFooter:()=>(0,e.createElement)(i.Button,{variant:"primary",disabled:0===n.length&&0===a.length&&0===s.length||t,icon:sn,iconSize:16,onClick:e=>{e.preventDefault();const t={};n.length&&(t.blocks=n.map((e=>{let{title:t,content:n,slug:r,meta:a,keywords:o}=e;return{title:t,content:n,slug:r,meta:a,keywords:o}}))),a.length&&(t.variations=a.map((e=>{let{title:t,content:n,slug:r,meta:a}=e;return{title:t,content:n,slug:r,meta:a}}))),s.length&&(t.patterns=s.map((e=>{let{title:t,content:n,slug:r,meta:a,keywords:o}=e;return{title:t,content:n,slug:r,meta:a,keywords:o}}))),((e,t)=>{const n=new Blob([JSON.stringify(t,null,2)],{type:"text/json"}),r=document.createElement("a");r.download=e,r.href=window.URL.createObjectURL(n),r.dataset.downloadurl=["text/json",r.download,r.href].join(":");const a=new MouseEvent("click",{view:window,bubbles:!0,cancelable:!0});r.dispatchEvent(a),r.remove()})(`boldblocks-${(new Date).toISOString().slice(0,10)}.json`,t)}},(0,o.__)("Export data","content-blocks-builder"))},(0,e.createElement)("p",null,(0,o.__)("Select the blocks, variations, patterns to export a .json file which you can then import to another WordPress site. Be sure to export all dependent blocks and/or variations.","content-blocks-builder")),(0,e.createElement)(mn,{postType:"boldblocks_block",title:(0,o.__)("Select Blocks","content-blocks-builder"),value:n,setValue:r}),(0,e.createElement)(mn,{postType:"boldblocks_variation",title:(0,o.__)("Select Variations","content-blocks-builder"),value:a,setValue:l}),(0,e.createElement)(mn,{postType:"boldblocks_pattern",title:(0,o.__)("Select Patterns","content-blocks-builder"),value:s,setValue:c}))},fn=(0,g.createElement)(h.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24"},(0,g.createElement)(h.Path,{d:"M18.5 15v3.5H13V6.7l4.5 4.1 1-1.1-6.2-5.8-5.8 5.8 1 1.1 4-4v11.7h-6V15H4v5h16v-5z"})),gn=(0,g.createElement)(h.SVG,{viewBox:"0 0 24 24",xmlns:"http://www.w3.org/2000/svg"},(0,g.createElement)(h.Path,{d:"M19 6.2h-5.9l-.6-1.1c-.3-.7-1-1.1-1.8-1.1H5c-1.1 0-2 .9-2 2v11.8c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V8.2c0-1.1-.9-2-2-2zm.5 11.6c0 .3-.2.5-.5.5H5c-.3 0-.5-.2-.5-.5V6c0-.3.2-.5.5-.5h5.8c.2 0 .4.1.4.3l1 2H19c.3 0 .5.2.5.5v9.5z"}));const hn=Je.div`
  margin-top: 12px;
`,bn=n=>{let{posts:r,title:a}=n;return(0,t.isArray)(r)&&(0,e.createElement)(hn,{className:"posts-preview"},(0,e.createElement)("div",{className:"fieldset__label"},(0,e.createElement)("strong",null,a)),(0,e.createElement)("ul",{className:"fieldset__list"},r.map((t=>{let{slug:n}=t;return(0,e.createElement)("li",{key:n},`boldblocks/${n}`)}))))};var yn=()=>{const{saveEditedEntityRecord:n}=(0,s.useDispatch)(l.store),r=(0,e.useContext)(dn),{isLoading:a,registeredCategories:c,customCategories:u,setCustomCategories:d,isLoadingPatternsCategories:p}=r,f=!p&&c.concat(u).concat([{name:"boldblocks"}]).map((e=>{let{name:t}=e;return t})),[g,h]=(0,e.useState)(""),[b,y]=(0,e.useState)({}),[v,_]=(0,e.useState)({}),[k,E]=(0,e.useState)("ignore");let w=e=>e?.blocks||e?.variations||e?.patterns;return(0,e.createElement)(ht,{title:(0,o.__)("Import data","content-blocks-builder"),renderFooter:()=>{const[l,s]=(0,e.useState)(!1);return(0,e.createElement)(e.Fragment,null,(0,e.createElement)(i.Button,{variant:"primary",disabled:!w(b)||!g||a,icon:fn,iconSize:16,onClick:()=>{s(!0);const e=(0,t.pick)(b,["blocks","variations","patterns"]);Promise.all(Object.keys(e).map((async n=>Promise.all(e[n].map((e=>((e,n)=>{const{[n]:a}=r;let o;return o="variations"===n?(0,t.isArray)(a)&&a.find((t=>{let{meta:{boldblocks_variation_name:n}}=t;return n===e?.meta?.boldblocks_variation_name})):(0,t.isArray)(a)&&a.find((t=>{let{slug:n}=t;return n===e?.slug})),o?"override"===k?m()({path:`wp/v2/boldblocks-${n}/${o.id}`,method:"POST",data:{...e,status:"publish"}}):void 0:"variations"===n?m()({path:"boldblocks/v1/createVariation",method:"POST",data:{...e,status:"publish"}}):m()({path:`wp/v2/boldblocks-${n}`,method:"POST",data:{...e,status:"publish"}})})(e,n)))).then((e=>({key:n,response:e})))))).then((e=>{const r=e.reduce(((e,t)=>{let{key:n,response:r}=t;return{...e,[n]:r.filter((e=>e))}}),{});if(r?.patterns&&r.patterns){const e=(0,t.uniqBy)(r.patterns.reduce(((e,t)=>{let{meta:{boldblocks_pattern_categories:n=[]}}=t;return[...e,...n]}),[]),"name");if(e.length){const t=e.filter((e=>{let{name:t}=e;return!f.find((e=>t===e))}));t.length&&(d([...u,...t]),n("root","site"))}}r?.blocks&&r.blocks.length||r?.variations&&r.variations.length||r?.patterns&&r.patterns.length?_({type:"success",message:(0,o.__)("Data has been imported successfully!","content-blocks-builder")}):_({type:"info",message:(0,o.__)("No items have been imported! Please change your settings or upload another JSON file.","content-blocks-builder")})})).catch((e=>{console.error(e),_({type:"error",message:(0,o.__)("Import failed. Please make sure your data is correct!","content-blocks-builder")})})).finally((()=>{s(!1),h(""),y({})}))}},(0,o.__)("Import data","content-blocks-builder")),l&&(0,e.createElement)(i.Spinner,null))}},(0,e.createElement)("p",null,(0,o.__)("Upload your json file and click the import button.","content-blocks-builder")),(0,e.createElement)(gt,{className:"fieldset"},(0,e.createElement)("div",{className:"fieldset__label"},(0,e.createElement)("strong",null,(0,o.__)("Select file","content-blocks-builder"))),(0,e.createElement)("div",{className:"file-upload"},(0,e.createElement)(i.FormFileUpload,{accept:"application/JSON",variant:"primary",onChange:e=>{h(e.target.files[0]);const t=new FileReader;t.onload=e=>{try{const t=JSON.parse(e.target.result);w(t)?(y(t),_({})):(y({}),_({type:"error",message:(0,o.__)("The uploaded file is in the wrong format. Please use a JSON file from the export functionality.","content-blocks-builder")}))}catch(e){y({}),console.error(e)}},t.readAsText(e.target.files[0]),e.target.value=""}},(0,o.__)("Choose file to upload","content-blocks-builder")),w(b)&&g&&(0,e.createElement)(e.Fragment,null,(0,e.createElement)("div",{className:"file-preview"},(0,e.createElement)("span",{className:"icon"},gn),(0,e.createElement)("span",{className:"name"},g?.name),(0,e.createElement)(i.Button,{variant:"tertiary",className:"delete",onClick:()=>{h(""),y({})}},(0,o.__)("Delete?","content-blocks-builder"))),(0,e.createElement)("div",{className:"data-preview",style:{flexBasis:"100%"}},(0,e.createElement)("p",null,(0,o.__)("Following data will be imported.","content-blocks-builder")),(0,e.createElement)(bn,{posts:b?.blocks,title:(0,o.__)("Blocks:","content-blocks-builder")}),(0,e.createElement)(bn,{posts:b?.variations,title:(0,o.__)("Variations:","content-blocks-builder")}),(0,e.createElement)(bn,{posts:b?.patterns,title:(0,o.__)("Patterns:","content-blocks-builder")})))),(0,e.createElement)("div",{className:"fieldset__label",style:{marginTop:"12px"}},(0,e.createElement)("strong",null,(0,o.__)("Import settings","content-blocks-builder"))),(0,e.createElement)(i.RadioControl,{selected:k,onChange:E,options:[{value:"override",label:(0,o.__)("Replace old data.","content-blocks-builder")},{value:"ignore",label:(0,o.__)("Existing items are ignored.","content-blocks-builder")}]}),!(0,t.isEmpty)(v)&&(0,e.createElement)(e.Fragment,null,(0,e.createElement)(i.Notice,{status:v?.type,isDismissible:!1},v?.message))))},vn=()=>{const n=(()=>{const[n,r,a]=un("boldblocks_block"),[o,i,s]=un("boldblocks_variation"),[c,u,d]=un("boldblocks_pattern"),{registeredCategories:p,customCategories:f,setCustomCategories:g,isLoading:h}=(()=>{const[n,r]=(0,e.useState)(!0),[a,o]=(0,e.useState)([]),[i,s]=(0,l.useEntityProp)("root","site","boldblocks_pattern_categories");return(0,e.useEffect)((()=>{m()({path:"boldblocks/v1/getPatternCategories"}).then((e=>{o(e),r(!1)}))}),[]),{registeredCategories:a,customCategories:i,setCustomCategories:s,isLoading:n||(0,t.isUndefined)(i)}})();return{blocks:n,isLoadingBlocks:r,hasFinishedResolutionBlocks:a,variations:o,isLoadingVariations:i,hasFinishedResolutionVariations:s,patterns:c,isLoadingPatterns:u,hasFinishedResolutionPatterns:d,registeredCategories:p,customCategories:f,setCustomCategories:g,isLoadingPatternsCategories:h,isLoading:r||i||u||h}})();return(0,e.createElement)(dn.Provider,{value:n},(0,e.createElement)(pt,{description:(0,o.__)("Import/Export blocks, patterns and variations","content-blocks-builder")},(0,e.createElement)(pn,null),(0,e.createElement)(yn,null)))};const kn=Je(ht)`
  .inside h2 {
    padding: 0;
    margin: 0 0 10px;
    font-size: 1.75em;
  }

  .dev__body {
    padding-top: 1em;
  }
`,En=()=>{const{Debug:{nonce:t,isPurged:n,setIsPurged:r}={}}=(0,e.useContext)(S);return(0,e.createElement)(kn,{title:(0,o.__)("Purge the cache","content-blocks-builder"),className:"debug-widget debug"},(0,e.createElement)("div",{className:"dev__body debug__body"},(0,e.createElement)(i.Flex,{justify:"flex-start"},(0,e.createElement)(i.Button,{variant:"primary",type:"button",href:(0,f.addQueryArgs)(`edit.php?post_type=boldblocks_block&page=cbb-settings&tab=developer&_cbb_purge=${t}`),as:"a"},(0,o.__)("Purge cache","content-blocks-builder")),(0,e.createElement)("p",null,(0,o.__)("Delete the entire cache contents for CBB Blocks, Variations and Patterns.","content-blocks-builder"))),!!n&&(0,e.createElement)(i.Notice,{status:"success",onRemove:()=>{r(!1),(new C).delete("_cbb_purge",!0)}},(0,o.__)("Cache cleared.","content-blocks-builder"))))},wn=()=>{const{Messages:n,pages:r,isResolvingPages:a}=(0,e.useContext)(S),c=(0,e.useMemo)((()=>r?.length?r.map((e=>{let{id:t,title:{rendered:n}={}}=e;return{label:n,value:t}})):[]),[a]),{saveEditedEntityRecord:u}=(0,s.useDispatch)(l.store),[d,m]=(0,l.useEntityProp)("root","site","IsMaintenance"),[p,f]=(0,l.useEntityProp)("root","site","MaintenanceSlug"),[g,h]=(0,l.useEntityProp)("root","site","MaintananceEnableCustomPage"),[b,y]=(0,l.useEntityProp)("root","site","MaintanancePageId"),[v,_]=(0,e.useState)({type:"success",message:""});return(0,e.createElement)(kn,{title:(0,o.__)("Maintenance mode","content-blocks-builder"),renderFooter:()=>{const[t,r]=(0,e.useState)(!1);return(0,e.createElement)(e.Fragment,null,(0,e.createElement)(i.Button,{variant:"primary",onClick:e=>{e.preventDefault(),r(!0),u("root","site").then((()=>{_({type:"success",message:n.Success})})).catch((e=>{log(e,"error"),_({type:"error",message:n.Error})})).finally((()=>{r(!1)}))}},n.UpdateSettings),t&&(0,e.createElement)(i.Spinner,null))},className:"maintenance-widget maintenance"},(0,e.createElement)(gt,{className:"fieldset"},(0,e.createElement)("div",{className:"fieldset__label"},(0,e.createElement)("strong",null,(0,o.__)("Turn on the maintenance mode.","content-blocks-builder"))),(0,t.isUndefined)(p)?(0,e.createElement)(i.Spinner,null):(0,e.createElement)(e.Fragment,null,(0,e.createElement)(i.ToggleControl,{label:(0,o.__)("Enable maintenance mode","content-blocks-builder"),checked:null!=d&&d,disabled:(0,t.isUndefined)(d),onChange:m}),d&&(0,e.createElement)(e.Fragment,null,(0,e.createElement)(i.TextareaControl,{label:(0,o.__)("Ignore slug","content-blocks-builder"),value:p,placeholder:"wp-login.php",onChange:f,help:(0,o.__)("Input the page slugs that will bypass the maintenance mode. Put each item on a new line.","content-blocks-builder"),rows:4}),(0,e.createElement)(i.ToggleControl,{label:(0,o.__)("Use a custom page as the maintenance page","content-blocks-builder"),checked:null!=g&&g,onChange:h}),g&&(0,e.createElement)(e.Fragment,null,a||(0,t.isUndefined)(r)?(0,e.createElement)(i.Spinner,null):(0,e.createElement)(i.SelectControl,{label:(0,o.__)("Custom maintenance page","content-blocks-builder"),value:b,onChange:y,options:c}))))),v&&v?.message&&(0,e.createElement)(i.Notice,{status:v?.type,isDismissible:!1},v.message))};var xn=()=>(0,e.createElement)(pt,{description:(0,o.__)("Settings for developer","content-blocks-builder")},(0,e.createElement)(En,null),(0,e.createElement)(wn,null));const Sn=t=>{let{children:n}=t;return(0,e.createElement)("div",{className:"metabox-holder"},n)},Cn=()=>{const n=[{name:"getting-started",title:(0,o.__)("Getting Started","content-blocks-builder"),className:"setting-tabs__getting-started"},{name:"general",title:(0,o.__)("General","content-blocks-builder"),className:"setting-tabs__general"},{name:"typography",title:(0,o.__)("Typography","content-blocks-builder"),className:"setting-tabs__typography"},{name:"tools",title:(0,o.__)("Tools","content-blocks-builder"),className:"setting-tabs__tools"},{name:"developer",title:(0,o.__)("Developer","content-blocks-builder"),className:"setting-tabs__developer"}],r=new C,a=r.get("tab"),s=(0,t.findKey)(n,["name",a])?a:"getting-started",c=(()=>{const{loading:t,error:n,data:{data:r}={}}=function(t){let n=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{},r=arguments.length>2&&void 0!==arguments[2]?arguments[2]:[];const[a,o]=(0,e.useState)(!0),[i,l]=(0,e.useState)(),[s,c]=(0,e.useState)(),u=(0,e.useCallback)((()=>{o(!0),l(void 0),c(void 0),m()({path:t,...{...p,...n}}).then(c).catch(l).finally((()=>o(!1)))}),r);return(0,e.useEffect)((()=>{u()}),[u]),{loading:a,error:i,data:s}}("boldblocks/v1/getDocs");let a="",i=!1;try{a=CBBSettings?.nonce,i=CBBSettings?.isPurgedCache}catch(n){log("The nonce is not defined!","error")}const[s,c]=(0,e.useState)(i),u={UpdateSettings:(0,o.__)("Update Settings","content-blocks-builder"),Success:(0,o.__)("Setting Saved!","content-blocks-builder"),Error:(0,o.__)("Something went wrong, please contact the author for support!","content-blocks-builder")},{records:d,isResolving:f}=(0,l.useEntityRecords)("postType","page",{per_page:100});return{Docs:{loading:t,error:n,docs:r},Debug:{nonce:a,isPurged:s,setIsPurged:c},Messages:u,pages:d,isResolvingPages:f}})();return(0,e.createElement)(S.Provider,{value:c},(0,e.createElement)(i.TabPanel,{tabs:n,className:"settings-tabs",activeClass:"is-active",initialTabName:s,onSelect:e=>{r.set("tab",e)}},(t=>{switch(t.name){case"getting-started":return(0,e.createElement)(Sn,null,(0,e.createElement)(vt,null));case"general":return(0,e.createElement)(Sn,null,(0,e.createElement)(ln,null));case"typography":return(0,e.createElement)(Sn,null,(0,e.createElement)(en,null));case"tools":return(0,e.createElement)(Sn,null,(0,e.createElement)(vn,null));case"developer":return(0,e.createElement)(Sn,null,(0,e.createElement)(xn,null))}})))};a()((()=>{(0,e.render)((0,e.createElement)(Cn,null),document.querySelector(".js-boldblocks-settings-root"))}))}()}();