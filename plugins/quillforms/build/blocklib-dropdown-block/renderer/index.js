(()=>{var e={4184:(e,o)=>{var t;!function(){"use strict";var r={}.hasOwnProperty;function a(){for(var e=[],o=0;o<arguments.length;o++){var t=arguments[o];if(t){var n=typeof t;if("string"===n||"number"===n)e.push(t);else if(Array.isArray(t)){if(t.length){var s=a.apply(null,t);s&&e.push(s)}}else if("object"===n){if(t.toString!==Object.prototype.toString&&!t.toString.toString().includes("[native code]")){e.push(t.toString());continue}for(var l in t)r.call(t,l)&&t[l]&&e.push(l)}}}return e.join(" ")}e.exports?(a.default=a,e.exports=a):void 0===(t=function(){return a}.apply(o,[]))||(e.exports=t)}()}},o={};function t(r){var a=o[r];if(void 0!==a)return a.exports;var n=o[r]={exports:{}};return e[r](n,n.exports,t),n.exports}t.n=e=>{var o=e&&e.__esModule?()=>e.default:()=>e;return t.d(o,{a:o}),o},t.d=(e,o)=>{for(var r in o)t.o(o,r)&&!t.o(e,r)&&Object.defineProperty(e,r,{enumerable:!0,get:o[r]})},t.o=(e,o)=>Object.prototype.hasOwnProperty.call(e,o),t.r=e=>{"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})};var r={};(()=>{"use strict";t.r(r);const e=window.qf.blocks,o=(window.wp.element,window.qf.rendererCore),a=window.qf.utils,n=window.React,s=window.ReactDOM,l=(window.wp.data,window.tinycolor);var i=t.n(l);t(4184);const c=window.lodash;function d(){return d=Object.assign?Object.assign.bind():function(e){for(var o=1;o<arguments.length;o++){var t=arguments[o];for(var r in t)Object.prototype.hasOwnProperty.call(t,r)&&(e[r]=t[r])}return e},d.apply(this,arguments)}const p=window.wp.i18n,u=(0,a.keyframes)({"0%":{transform:"translateY(100%)"},"100%":{transform:"translateY(0%)"}}),f=(0,a.keyframes)({"0%":{transform:"translateY(0%)"},"100%":{transform:"translateY(100%)"}}),m=e=>a.css`
	position: fixed;
	inset: 0;
	height: 100% !important;
	display: flex;
	background-color: #fff;
	flex-direction: column;
	z-index: 111111111;

	&.show {
		transform: translateY(100%);
		animation: ${u} 0.5s ease-in-out 1 forwards;
	}

	&.hide {
		transform: translateY(0%);
		animation: ${f} 0.5s ease-in-out 1 forwards;
	}
	> div {
		background: ${e.backgroundColor};
		${(e=>{let o="";return e.backgroundImage&&e.backgroundImage&&(o=`background-image: url('${e.backgroundImage}');\n\t\t\tbackground-size: cover;\n\t\t\tbackground-position: ${100*parseFloat(e.backgroundImageFocalPoint?.x)}%\n\t\t\t${100*parseFloat(e.backgroundImageFocalPoint?.y)}%;\n\n\t\t\tbackground-repeat: no-repeat;\n\t\t`),o})(e)};
		padding: 20px 10px;
		overflow-y: auto;
		height: 100% !important;
	}
	.back-icon {
		width: 20px;
		height: 20px;
		margin-right: 5px;
		fill: ${e.questionsColor} !important
	}
}`,h=(0,a.keyframes)({"0%":{transform:"scale( 1 )"},"25%":{transform:"scale( 0.97 )"},"50%":{transform:"scale( 0.99 )"},"75%":{transform:"scale( 0.97 )"},"100%":{transform:"scale( 1 )"}}),g=a.css`
	& {
		position: absolute;
		top: 112%;
		right: 0;
		left: 0;
		padding-top: 15px;
		border-radius: 5px;
		width: 100%;
		overflow-y: auto;
		transition: transform, opacity 0.3s linear;
		z-index: 11111;
		opacity: 0;
		visibility: hidden;
		transform: translateY(-10px);
	}

	&.visible {
		max-height: 300px;
		visibility: visible;
		opacity: 1;
		transform: none;
	}

	&.fixed-choices {
		position: static;
		height: auto !important;
		padding: 10px 20px;
	}


}`,b=a.css`
	& {
		padding: 10px;
		margin-bottom: 8px;
		border-width: 1px;
		border-style: solid;
		border-radius: 5px;
		cursor: pointer;
		backface-visibility: hidden;
		-webkit-backface-visibility: hidden;
	}
	&:last-child {
		margin-bottom: 0;
	}

	&.isBeingSelected {
		animation: ${h} 0.4s linear forwards;
	}
}`,w=a.css`
	position: absolute;
	${(0,p.isRTL)()?"left: 0":"right: 0"};
	bottom: 4px;
	cursor: pointer;

	svg {
		width: 26px;
		height: 26px;
	}
`,v=e=>{const t=(0,o.useTheme)(),r=(0,a.useCx)();return React.createElement("div",d({tabIndex:"0"},e,{className:r("block-dropdown-renderer-expand-icon",w)}),React.createElement("svg",{stroke:"currentColor",fill:"currentColor",strokeWidth:"0",viewBox:"0 0 20 20",height:"1em",width:"1em",xmlns:"http://www.w3.org/2000/svg",className:r(a.css`
					fill: ${t.answersColor};
				`)},React.createElement("path",{fillRule:"evenodd",d:"M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z",clipRule:"evenodd"})))},x=e=>{const t=(0,o.useTheme)(),r=(0,a.useCx)();return React.createElement("div",d({},e,{className:r("block-dropdown-renderer-close-icon",w)}),React.createElement("svg",{height:"32",width:"32",viewBox:"0 0 512 512",className:r(a.css`
					fill: ${t.answersColor};
				`)},React.createElement("g",null,React.createElement("g",null,React.createElement("g",null,React.createElement("polygon",{points:"405,136.798 375.202,107 256,226.202 136.798,107 107,136.798 226.202,256 107,375.202 136.798,405 256,285.798\r 375.202,405 405,375.202 285.798,256 \t\t"},React.createElement("polygon",{points:"405,136.798 375.202,107 256,226.202 136.798,107 107,136.798 226.202,256 107,375.202 136.798,405 256,285.798\r 375.202,405 405,375.202 285.798,256 \t\t"})))))))};let k;const y=e=>{let{choice:t,blockId:r,choiceIndex:s,val:l,clickHandler:c,showDropdown:d,clicked:p,hovered:u}=e;const[f,m]=(0,n.useState)(!1),h=(0,n.useRef)(),g=(0,a.useCx)();(0,n.useEffect)((()=>{d||m(!1)}),[d]);const w=(0,o.useTheme)(),v=i()(w.answersColor),x=l&&l===t.value;return(0,n.useEffect)((()=>(p&&h.current.click(),()=>{p=!1})),[p]),React.createElement("div",{ref:h,id:`block-${r}-option-${s}`,className:g("dropdown__choiceWrapper",{selected:x,isBeingSelected:f},a.css`
						background: ${u?v.setAlpha(.2).toString():v.setAlpha(.1).toString()};

						border-color: ${w.answersColor};
						color: ${w.answersColor};

						&:hover {
							background: ${v.setAlpha(.2).toString()};
						}

						&.selected {
							background: ${i()(w.answersColor).setAlpha(.75).toString()};
							color: ${i()(w.answersColor).isDark()?"#fff":"#333"}
					`,b),role:"presentation",onClick:()=>{x&&clearTimeout(k),x||m(!0),c(),k=setTimeout((()=>{f&&m(!1)}),400)}},t.label)};let C,$,E;const R={display:e=>{var t;const{id:r,attributes:l,setIsValid:d,setIsAnswered:p,setValidationErr:u,val:f,setVal:h,next:b,showErrMsg:w,isActive:k,isTouchScreen:R,setFooterDisplay:S,inputRef:T,isPreview:I,isReviewing:q}=e,{choices:_,required:P}=l,N=(0,a.useCx)(),O=window.self!==window.top,A=(0,o.useBlockTheme)(l.themeId),[F,B]=(0,n.useState)(!1),[j,D]=(0,n.useState)(""),[M,z]=(0,n.useState)(-1),[L,H]=(0,n.useState)(!1),[Y,W]=(0,n.useState)(!1),[K,V]=(0,n.useState)(!1),[G,J]=(0,n.useState)(""),[Q,U]=(0,n.useState)(!1),[X,Z]=(0,n.useState)(!1),ee=(0,n.useRef)(),oe=(0,n.useRef)(),te=(0,o.useMessages)(),re=i()(A.answersColor),ae=(0,n.useMemo)((()=>(0,c.cloneDeep)(_).map(((e,o)=>(e.label||(e.label="Choice "+(o+1)),e))).filter((e=>e.label.toLowerCase().includes(f&&R?"":j.toLowerCase())))),[_,j]),ne=e=>{!0!==P||e&&""!==e?(d(!0),u(null)):(d(!1),u(te["label.errorAlert.selectionRequired"]))},se=e=>{ee.current&&!ee.current.contains(e.target)&&(B(!1),z(-1))};(0,n.useEffect)((()=>(F?(document.addEventListener("mousedown",se),document.querySelector(`#block-${r} .renderer-core-field-footer`)&&document.querySelector(`#block-${r} .renderer-core-field-footer`).classList.add("is-hidden")):document.querySelector(`#block-${r} .renderer-core-field-footer`)&&document.querySelector(`#block-${r} .renderer-core-field-footer`).classList.remove("is-hidden"),()=>{document.removeEventListener("mousedown",se)})),[F]),(0,n.useEffect)((()=>{!I&&q||ne(f)}),[l]),(0,n.useEffect)((()=>(X?(U(X),S(!1)):E=setTimeout((()=>{U(X)}),500),()=>clearTimeout(E))),[X]);const le=e=>{if(V(""!==e.target.value),J(e.target.value),R&&!O||B(!0),f)return h(null),D(""),void ne(void 0);D(e.target.value)};(0,n.useEffect)((()=>{k||$&&clearTimeout($),W(!1)}),[k]),(0,n.useEffect)((()=>{if(f){const e=ae.find((e=>e.value===f));D(e?e.label:"")}return()=>{$&&clearTimeout($)}}),[]),(0,n.useEffect)((()=>{Y&&b()}),[Y]);const ie=e=>{if(!R){if(27===e.keyCode)return B(!1),V(""!==G),void z(-1);if(38!==e.keyCode&&40!==e.keyCode){if(13===e.keyCode){if(e.stopPropagation(),-1===M)return B(!1),void z(-1);H(!0)}}else{const o=document.querySelector(`#block-${r}  .qf-block-dropdown-display__choices`);if(!o||M<=0&&38===e.keyCode||M===ae.length-1&&40===e.keyCode)return;B(!0);const t=38===e.keyCode?M-1:M+1;z(t);const a=document.getElementById(`block-${r}-option-${t}`);(function(e,o){const t=e.scrollTop,r=t+e.clientHeight,a=o.offsetTop,n=a+o.clientHeight;return a>=t+10&&n<=r-50})(o,a)||(o.scrollTop=a.offsetTop-30)}}},ce=function(){let e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:ae[M];const o=ae.findIndex((o=>o.value===e.value));if(o!==M&&z(o),H(!1),V(!1),w(!1),clearTimeout(C),$&&clearTimeout($),f&&f===e.value)return h(null),p(!1),D(""),ne(void 0),void W(!1);p(!0),h(e.value),ne(e.value),C=setTimeout((()=>{D(e.label),B(!1),z(-1),R&&!O?(Z(!1),S(!0),$=setTimeout((()=>{W(!0)}),750)):W(!0)}),R?500:700)};return React.createElement("div",{ref:ee,style:{position:"relative"}},React.createElement("input",{autoComplete:"off",ref:T,className:N(a.css`
						& {
							width: 100%;
							border: none;
							outline: none;
							font-size: 30px;
							padding-bottom: 8px;
							background: transparent;
							transition: box-shadow 0.1s ease-out 0s;
							box-shadow: ${re.setAlpha(.3).toString()}
								0px 1px;
							@media ( max-width: 600px ) {
								font-size: 24px;
							}

							@media ( max-width: 480px ) {
								font-size: 20px;
							}
						}

						&::placeholder {
							opacity: 0.3;
							/* Chrome, Firefox, Opera, Safari 10.1+ */
							color: ${A.answersColor};
						}

						&:-ms-input-placeholder {
							opacity: 0.3;
							/* Internet Explorer 10-11 */
							color: ${A.answersColor};
						}

						&::-ms-input-placeholder {
							opacity: 0.3;
							/* Microsoft Edge */
							color: ${A.answersColor};
						}

						&:focus {
							box-shadow: ${re.setAlpha(1).toString()}
								0px 2px;
						}

						color: ${A.answersColor};
					`),id:"dropdown-"+r,placeholder:te["block.dropdown.placeholder"],onChange:le,value:f&&R?j:R?"":j,onClick:()=>{R&&!O?(Z(!0),T?.current?.blur()):B(!0)},onFocus:()=>{R&&S(!1)},onBlur:()=>{R&&S(!0)},onKeyDown:ie,autoComplete:"off"}),f&&f.length>0||K&&(!R||O)?React.createElement(x,{onClick:()=>{clearTimeout(C),$&&clearTimeout($),D(""),p(!1),h(void 0),V(!1),R||T.current.focus()}}):React.createElement(v,{style:{transform:F?"rotate(180deg)":"rotate(0deg)"},onClick:()=>{F&&z(-1),R?Z(!X):(B(!F),T.current.focus())},onKeyDown:e=>{13===e.keyCode&&(e.stopPropagation(),F&&z(-1),R?Z(!X):(B(!F),T.current.focus()))}}),F&&React.createElement("div",{className:N("qf-block-dropdown-display__choices",{visible:F},g,a.css`
							background: ${null!==(t=A.backgroundColor)&&void 0!==t?t:"#fff"};
							padding: 15px;
							border: 1px dashed ${A.answersColor};
						`),ref:oe,onWheel:e=>{F&&e.stopPropagation()}},ae?.length>0?ae.map(((e,o)=>React.createElement(y,{blockId:r,choiceIndex:o,hovered:o===M,clicked:o===M&&L,role:"presentation",key:`block-dropdown-${r}-choice-${e.value}`,clickHandler:()=>ce(e),choice:e,val:f,showDropdown:F}))):React.createElement("div",{className:N(a.css`
								background: ${A.errorsBgColor};
								color: ${A.errorsFontColor};
								display: inline-block;
								padding: 5px 10px;
								border-radius: 5px;
							`)},te["block.dropdown.noSuggestions"])),Q&&React.createElement(React.Fragment,null,(0,s.createPortal)(React.createElement("div",{className:N("fixed-dropdown",{show:X,hide:!X},m(A)),onWheel:e=>e.stopPropagation()},React.createElement("div",{className:"fixed-dropdown-content",onWheel:e=>{e.stopPropagation()}},React.createElement("div",{className:N(a.css`
											display: flex;
											align-items: center;
										`)},React.createElement("svg",{onClick:()=>{Z(!1)},className:"back-icon",focusable:"false",viewBox:"0 0 16 16","aria-hidden":"true",role:"presentation"},React.createElement("path",{d:"M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"})),React.createElement("input",{autoFocus:!1,className:N(a.css`
												& {
													width: 100%;
													border: none;
													outline: none;
													padding-bottom: 8px;
													background: transparent;
													margin-bottom: 10px;
													transition: box-shadow 0.1s
														ease-out 0s;
													box-shadow: ${re.setAlpha(.3).toString()}
														0px 1px;
												}

												&::placeholder {
													opacity: 0.3;
													/* Chrome, Firefox, Opera, Safari 10.1+ */
													color: ${A.answersColor};
												}

												&:-ms-input-placeholder {
													opacity: 0.3;
													/* Internet Explorer 10-11 */
													color: ${A.answersColor};
												}

												&::-ms-input-placeholder {
													opacity: 0.3;
													/* Microsoft Edge */
													color: ${A.answersColor};
												}

												&:focus {
													box-shadow: ${re.setAlpha(1).toString()}
														0px 2px;
												}

												color: ${A.answersColor};
											`),placeholder:te["block.dropdown.placeholder"],onChange:le,value:j,onFocus:()=>{S(!1)},onBlur:()=>{S(!0)},onKeyDown:ie,autoComplete:"off"})),React.createElement("div",{className:"qf-block-dropdown-display__choices visible fixed-choices",ref:oe,onWheel:e=>{Q&&e.stopPropagation()}},ae?.length>0?ae.map(((e,o)=>React.createElement(y,{hovered:o===M,choiceIndex:o,blockId:r,clicked:o===M&&L,role:"presentation",key:`block-dropdown-${r}-choice-${e.value}`,clickHandler:()=>ce(e),choice:e,val:f,showDropdown:F}))):React.createElement("div",{className:N(a.css`
												background: ${A.errorsBgColor};
												color: ${A.errorsFontColor};
												display: inline-block;
												padding: 5px 10px;
												border-radius: 5px;
											`)},te["block.dropdown.noSuggestions"])))),document.querySelector(".renderer-core-form-flow"))))},mergeTag:e=>{let{val:o,attributes:t}=e;const{choices:r}=t,a=r.findIndex((e=>e.value===o));let n="_ _ _ _";return r[a]&&(n=r[a].label,n||(n="Choice "+(index+1))),React.createElement(React.Fragment,null,n)}},{name:S}={name:"dropdown",attributes:{choices:{type:"array",items:{type:"object",properties:{value:{type:"string"},label:{type:"string"}}},default:[{value:"123e45z7o89b",label:"Choice 1"}]}},supports:{editable:!0,required:!0,attachment:!0,description:!0,logic:!0,theme:!0,points:!0,payments:!0,choices:!0},logicalOperators:["is","is_not"]};(0,e.setBlockRendererSettings)(S,R)})(),(window.qf=window.qf||{}).blocklibDropdownBlockRenderer=r})();